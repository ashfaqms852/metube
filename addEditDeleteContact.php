<?php
session_start();
include_once "dbconnect.php";
include_once "functions.php";
$uID = $_GET['uid'];
$cOf = $_SESSION['userID'];
$name = $_SESSION['name'];

if(isset($_POST['add']) == "Add") {
  $friend = mysqli_real_escape_string($connection, $_POST['isFriend']);
  $queryEleven = "select * from block where user_id = '$cOf' and blockedBy = '$uID'";
  $recordEleven = mysqli_query($connection, $queryEleven);
  if(mysqli_num_rows($recordEleven) <= 0) {
    $queryOne = "insert into contacts (userID, contactOf, isFriend)
					 values ('$uID', '$cOf', '$friend')";
    mysqli_query($connection, $queryOne);
    $queryTen = "delete from block where user_id = '$uID' and blockedBy = '$cOf'";
    mysqli_query($connection, $queryTen);
    header('Location: contacts.php');
  }
  else {
	phpAlert("You have been blocked by the user who you are trying to add.");
  }
}
elseif(isset($_POST['edit']) == "Edit") {
  $friend = mysqli_real_escape_string($connection, $_POST['isFriend']);
  $blocked = mysqli_real_escape_string($connection, $_POST['isBlocked']);
  $queryTwo = "update contacts set isFriend = '$friend'
			   where userID = '$uID' and contactOf = '$cOf'";
  if(mysqli_query($connection, $queryTwo)) {
	header('Location: contacts.php');
  }
}
elseif(isset($_POST['delete']) == "Delete") {
  $queryThree = "delete from contacts where userID = '$uID' and contactOf = '$cOf'";
  if(mysqli_query($connection, $queryThree)) {
	header('Location: contacts.php');
  }
}
elseif(isset($_POST['block']) == "Block") {
  $querySeven = "insert into block (user_id, blockedBy) values ('$uID', '$cOf')";
  mysqli_query($connection, $querySeven);
  $queryNine = "delete from contacts where userID = '$uID' and contactOf = '$cOf'";
  mysqli_query($connection, $queryNine);
  $queryTwelve = "delete from contacts where userID = '$cOf' and contactOf = '$uID'";
  mysqli_query($connection, $queryTwelve);
  header('Location: contacts.php');
}
elseif(isset($_POST['unblock']) == "Unblock") {
  $queryEight = "delete from block where user_id = '$uID' and blockedBy = '$cOf'";
  if(mysqli_query($connection, $queryEight)) {
	header('Location: contacts.php');
  }
}
$queryFour = mysqli_query($connection, "select * from contacts where userID = '$uID' and
                         contactOf = '$cOf' ");
$recordFour = mysqli_fetch_assoc($queryFour);
if(count($recordFour) > 0) {
  $isFriend = $recordFour['isFriend'];
  $deleteFlag = 1;
}
else {
  $isFriend = "no";
  $deleteFlag = 0;
}
$queryFive = mysqli_query($connection, "select * from signup_details where signup_id = '$uID'");
$recordFive = mysqli_fetch_assoc($queryFive);
$fname = $recordFive['firstname'];
$lname = $recordFive['lastname'];
$email = $recordFive['email'];
$uname = $recordFive['username'];

$querySix = mysqli_query($connection, "select * from block where user_id = '$uID' and blockedBy = '$cOf'");
$recordSix = mysqli_fetch_assoc($querySix);
$blockFlag = (count($recordSix) > 0) ? 1 : 0;
?>
<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" type = "text/css" href = "css/basic.css">
</head>

<body background = "images/back.jpg">
<h2 class = "white" style = "position: absolute; left: 1150px; top: 2px"><?php echo $name; ?></h2>
<h1 class = "white" style = "position: absolute; left: 500px; top: 33px;"> Add/Edit/Delete Contact </h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1300px;"></a>
<img src = "images/board.png" height = "580" width = "430" style = "position: absolute; top: 30px; left: 470px;">


<?php $urlTwo = "addEditDeleteContact.php?uid=" . $uID; ?>
<form name = "contact_form" style = "position: absolute; top: 160px; left: 580px;"
	  method = "post" action = "<?php echo $urlTwo; ?>">
  <b style = "color: white; font-size: large;">First Name: </b><br/>
  <input name = "firstname" type = "text" value = "<?php echo $fname; ?>" disabled/> <br/><br/>
  <b style = "color: white; font-size: large;">Last Name: </b><br/>
  <input name = "lastname" type = "text" value = "<?php echo $lname; ?>" disabled/> <br/><br/>
  <b style = "color: white; font-size: large;">Email Address: </b><br/>
  <input name="email" type="text" value = "<?php echo $email; ?>" disabled/> <br/><br/>
  <b style = "color: white; font-size: large;">Username: </b><br/>
  <input name="username" type="text" value = "<?php echo $uname; ?>"  disabled/> <br/><br/>
  <b style = "color: white; font-size: large;">Add as friend: *</b><br/>
  <select name = "isFriend">
    <?php
    $selOne = ($isFriend == "yes") ? "selected = 'selected'" : "";
    $selTwo = ($isFriend == "no") ? "selected = 'selected'" : "";
    ?>
    <option value = "yes" <?php echo $selOne; ?>>Yes</option>
    <option value = "no" <?php echo $selTwo; ?>>No</option>
  </select><br/><br/>

  <?php if($deleteFlag == 1) { ?>
    
    <button class = "fancy_button" name = "delete" type = "submit" value = "Delete"
          style = "position: absolute; left: 188px; top: 380px;">
    <span>Delete</span></button>
          
    <button class = "fancy_button" name = "edit" type = "submit" value = "Edit"
          style = "position: absolute; left: 28px; top: 380px;">
    <span>Edit</span></button>
          
    <button type = "button" class = "fancy_button" style = "position: absolute; top: 380px; left: -140px;"
        onclick = "window.location.href = 'dashboard.php'"><span> Dashboard </span></button>
  <?php }
  else { ?>
    <button class = "fancy_button" name = "add" type = "submit" value = "Add"
          style = "position: absolute; left: 128px; top: 380px;">
    <span>Add</span></button>
    <button type = "button" class = "fancy_button" style = "position: absolute; top: 380px; left: -50px;"
        onclick = "window.location.href = 'dashboard.php'"><span> Dashboard </span></button>
  <?php }
    if($blockFlag == 1) { ?>
      <button class = "fancy_button" name = "unblock" type = "submit" value = "Unblock"
              style = "position: absolute; left: 28px; top: 435px;">
      <span>Unblock</span></button>
  <?php	}
    else { ?>
      <button class = "fancy_button" name = "block" type = "submit" value = "Block"
              style = "position: absolute; left: 28px; top: 435px;">
      <span>Block</span></button>
  <?php	}
  ?>

</form>
</body>
</html>