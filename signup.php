<?php
session_start();
include_once 'dbconnect.php';
include_once 'functions.php';

if(isset($_POST['signup']) == "Sign Up") {
  $fname = mysqli_real_escape_string($connection, $_POST['firstname']);
  $lname = mysqli_real_escape_string($connection, $_POST['lastname']);
  $email = mysqli_real_escape_string($connection, $_POST['email']);
  $username = mysqli_real_escape_string($connection, $_POST['username']);
  $password = mysqli_real_escape_string($connection, $_POST['password']);
  $confirm_password = mysqli_real_escape_string($connection, $_POST['confirm_password']);
  
  echo 'Kindly press BACK button.';
  
  if(checkIfEmailReused($connection, $email)) {
	phpAlert("An account with this e-mail ID already exists.");
  }
  elseif(checkIfUsernameReused($connection, $username)) {
	phpAlert("An account with this username already exists.");
  }
  elseif(empty($fname) or empty($lname) or empty($email)or empty($username)or empty($password)or empty($confirm_password)) {
    phpAlert("Atleast one field was empty.");
  }
  elseif(filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
	phpAlert("Email format is incorrect.");
  }
  elseif($password != $confirm_password ) {
	phpAlert("Passwords do not match. Please try again.");
  }
  else {
	$insert_query = "insert into signup_details (firstname, lastname, email, username, password)
                   values ('$fname', '$lname', '$email', '$username', '$password')";
	if(mysqli_query($connection, $insert_query)) {
	  $_SESSION['email'] = $email;
	  $_SESSION['username'] = $username;
	  $_SESSION['name'] = $fname . " " . $lname;
	  $_SESSION['userID'] = mysqli_insert_id($connection);
	    header('Location: dashboard.php');
	}
	else {
	  $message = "ERROR: " . $insert_query . "<br>" . mysqli_error($connection);
	  phpAlert($message);
	}
	mysqli_close($connection);
  }
} 
?>