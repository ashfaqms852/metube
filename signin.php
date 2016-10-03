<?php
session_start();
include_once 'dbconnect.php';
include_once 'functions.php';

if(isset($_POST['signin']) == "Sign In") {
  $username = mysqli_real_escape_string($connection, $_POST['username']);
  $password = mysqli_real_escape_string($connection, $_POST['password']);
  
  $select_query = mysqli_query($connection, "select * from signup_details where username = '$username'
                               and password = '$password'");

  $record = mysqli_fetch_assoc($select_query);
  
  $_SESSION['email'] = $record['email'];
  $_SESSION['username'] = $record['username'];
  $_SESSION['name'] = $record['firstname'] . " " . $record['lastname'];
  $_SESSION['userID'] = $record['signup_id'];
  if(count($record) > 0) {
    header('Location: dashboard.php');
  }
  else {
    phpAlert("Either username or password is incorrect.");
    echo 'Kindly press BACK button.';
  }
}
?>