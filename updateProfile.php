<?php
session_start();
include_once 'dbconnect.php';
include_once 'functions.php';
$name = $_SESSION['name'];
$email = $_SESSION['email'];
$result = mysqli_query($connection, "select * from signup_details where email = '$email'");
$rows = mysqli_fetch_assoc($result);

if(isset($_POST['updateProfile']) == "Update Profile") {
  $fname = mysqli_real_escape_string($connection, $_POST['firstname']);
  $lname = mysqli_real_escape_string($connection, $_POST['lastname']);
  $password = mysqli_real_escape_string($connection, $_POST['password']);
  $confirm_password = mysqli_real_escape_string($connection, $_POST['confirm_password']);

  echo 'Kindly press BACK button.';
  
  if(empty($fname) or empty($lname) or empty($password)or empty($confirm_password)) {
    phpAlert("Atleast one field was empty.");
  }
  elseif($password != $confirm_password ) {
	phpAlert("Passwords do not match. Please try again.");
  }
  else {
    $updateQuery = "update signup_details set firstname = '$fname', lastname = '$lname',
                    password = '$password' where email = '$email'";
    if(mysqli_query($connection, $updateQuery)) {
        header('Location: updateProfile.php');
    }
    else {
        phpAlert("Something went wrong while updating.");
    }
  }
}
?>

<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" type = "text/css" href = "css/basic.css">
</head>

<body background = "images/back.jpg">
<h2 class = "white" style = "position: absolute; left: 1150px; top: 2px"><?php echo $name; ?></h2>
<h1 class = "white" style = "position: absolute; left: 515px; top: 30px;"> Update Profile </h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1300px;"></a>
<img src = "images/board.png" height = "650" width = "470" style = "position: absolute; top: 0px; left: 400px;">

<form name = "update_form" style = "position: absolute; top: 148px; left: 520px;"
	  method = "post" action = "updateProfile.php">
  <b style = "color: white; font-size: large;">First Name: *</b><br/>
  <input name = "firstname" type = "text" value = "<?php echo $rows['firstname']; ?>" /> <br/><br/>
  <b style = "color: white; font-size: large;">Last Name: *</b><br/>
  <input name = "lastname" type = "text" value = "<?php echo $rows['lastname']; ?>" /> <br/><br/>
  <b style = "color: white; font-size: large;">Email Address: </b><br/>
  <input name="email" type="text" value = "<?php echo $rows['email']; ?>" disabled /> <br/><br/>
  <b style = "color: white; font-size: large;">Username: </b><br/>
  <input name="username" type="text" value = "<?php echo $rows['username']; ?>"  disabled/> <br/><br/>
  <b style = "color: white; font-size: large;">Password: *</b><br/>
  <input name="password" type="password" value = "<?php echo $rows['password']; ?>" /> <br/><br/>
  <b style = "color: white; font-size: large;">Confirm Password: *</b><br/>
  <input name="confirm_password" type="password" value = "<?php echo $rows['password']; ?>" /> <br/><br/>
  
<button type = "button" class = "fancy_button" style = "position: absolute; top: 390px; left: -50px;"
        onclick = "window.location.href = 'dashboard.php'"><span> Dashboard </span></button>

<button class = "fancy_button" name = "updateProfile" type = "submit" value = "Edit Profile"
          style = "position: absolute; left: 127px; top: 390px;">
    <span>Edit Profile</span></button>

</form>

</body>
</html>