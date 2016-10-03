<?php

$servername = "localhost";
$dbname = "metube2016";
$db_username = "root";
$db_password = "";

/*$servername = "mysql1.cs.clemson.edu";
$dbname = "metube2016_dbrn";
$db_username = "metube2016_pie9";
$db_password = "asherwa@clemson.edu";*/

$connection = mysqli_connect($servername, $db_username, $db_password, $dbname);

if(mysqli_connect_errno()) {
  echo "Failed to connect to the database: " . mysqli_connect_error();
}

?>
