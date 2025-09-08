<?php
$serverName = "localhost";
$userName = "root";
$password = "Bikalpa@123#";
$apiKey = "34a06356e51c79aa2d327a7a2206fc8a";
$dbName = "prototype2";
$cityName = isset($_GET['city']) ? $_GET['city'] : "Birgunj";


//setting up connections
$connection = mysqli_connect($serverName, $userName, $password);
if (!$connection) {
  die(
    "Could not connect to db server:" . mysqli_connect_error()
  );
}
$database = "CREATE DATABASE IF NOT EXISTS $dbName";
if (!mysqli_query($connection, $database)) {
  die(
    "Could not connect/create db:" . mysqli_connect_error()
  );
}
mysqli_select_db($connection, $dbName);
$table = "CREATE TABLE IF NOT EXISTS weather (
    --  unique id for each city weather data
    id INT AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(100) NOT NULL,
    country VARCHAR(30) NOT NULL,
    description TEXT NOT NULL,
    icon VARCHAR(10) NOT NULL,
    temp FLOAT NOT NULL,
    feels_like FLOAT NOT NULL,
    temp_min FLOAT NOT NULL,
    temp_max FLOAT NOT NULL,
    humidity FLOAT NOT NULL,
    pressure FLOAT NOT NULL,
    wind_speed FLOAT NOT NULL,
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );";
if (!mysqli_query($connection, $table)) {
  die(
    "Failed to create/connect table <br>" . mysqli_connect_error()
  );
}
//if connected to the table
$cityName = mysqli_real_escape_string($connection, $cityName); // sanitization
$weatherData = "SELECT * FROM weather where city = '$cityName'";
$query = mysqli_query($connection, $weatherData);
if (mysqli_num_rows($query) == 0) {
  $open_weather_url = "https://api.openweathermap.org/data/2.5/weather?q=$cityName&appid=$apiKey";
  $response = file_get_contents($open_weather_url);
  $data = json_decode($response, true);
  //checks if data exist
  if (!$data)
    die("API Error/City not found");
  //'destructuring' the data
  $city = $data['name'];
  $country = $data['sys']['country'];
  $weather = $data['weather'];
  $description = $weather[0]['description'];
  $iconEncoding = $weather[0]['icon'];
  $temp = $data['main']['temp'];
  $feels_like = $data['main']['feels_like'];
  $temp_min = $data['main']['temp_min'];
  $temp_max = $data['main']['temp_max'];
  $humidity = $data['main']['humidity'];
  $pressure = $data['main']['pressure'];
  $wind_speed = $data['wind']['speed'];
  //inserting to the database
  $insertData = "INSERT INTO weather (city,country,description,icon,temp,feels_like,temp_min,temp_max,humidity,pressure,wind_speed)
        VALUES ('$city', '$country', '$description','$iconEncoding','$temp','$feels_like','$temp_min','$temp_max','$humidity','$pressure','$wind_speed')
      ";
  if (!mysqli_query($connection, $insertData)) {
    die("Failed to insert data,try again." . mysqli_error($connection));
  }
}
$finalResult = mysqli_query($connection, $weatherQuery);
$weatherRows = [];

while ($row = mysqli_fetch_assoc($finalResult)) {
  $weatherRows[] = $row;
}
header('Content-Type: application/json');
echo json_encode($all_rows);



mysqli_close($connection);
?>