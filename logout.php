<?php
include_once "dbconnect.php";
session_unset();
session_destroy();
mysqli_close($connection);
header("Location: index.html");       
?>