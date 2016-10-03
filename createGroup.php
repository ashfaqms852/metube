<?php
session_start();
include_once "dbconnect.php";
include_once "functions.php";
$name = $_SESSION['name'];
$uid = $_SESSION['userID'];

if(isset($_POST['create'])) {
  $gname = mysqli_real_escape_string($connection, $_POST['gname']);
  $gdesc = mysqli_real_escape_string($connection, $_POST['gdescription']);
  
  if(empty($gname) or empty($gdesc)) {
    phpAlert("Atleast one field is empty.");
  }
  else {
    mysqli_query($connection, "insert into group_details (user_id, name, description)
                               values ('$uid', '$gname', '$gdesc')");
    $g_id = mysqli_insert_id($connection);
    mysqli_query($connection, "insert into group_members (group_id, user_id)
                               values ('$g_id', '$uid')");
    header("Location: groups.php");
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
<h1 class = "white" style = "position: absolute; left: 500px; top: 53px;"> Create Group </h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1300px;"></a>
<img src = "images/board.png" height = "330" width = "650" style = "position: absolute; top: 70px; left: 350px;">
<form style = "position: absolute; top: 165px; left: 510px;"
	  method = "post" action = "createGroup.php">
<b style = "color: white; font-size: large;">Name: *</b><br/>
<input name = "gname" type = "text"><br/><br/>
<b style = "color: white; font-size: large;">Description: *</b><br/>
<textarea name = "gdescription" rows = "3" cols = "45" placeholder = "Type your description here">
</textarea><br/><br/>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 200px; left: -32px;"
        onclick = "window.location.href = 'groups.php'"><span> My Groups </span></button>

<button class = "fancy_button" name = "create" type = "submit" value = "Create"
          style = "position: absolute; left: 200px; top: 200px;">
    <span>Create</span></button>
</form>
</body>
</html>