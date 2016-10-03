<?php
session_start();
include_once "dbconnect.php";
include_once "functions.php";
$name = $_SESSION['name'];
$uid = $_SESSION['userID'];
$queryOne = mysqli_query($connection, "select * from media_organization where user_id = '$uid' and isPlaylist = 'yes'");
$queryTwo = mysqli_query($connection, "select * from media_organization where user_id = '$uid' and isChannel = 'yes'");
if(isset($_POST['delete']) == "Delete") {
  $playlist = mysqli_real_escape_string($connection, $_POST['playlist']);
  $channel = mysqli_real_escape_string($connection, $_POST['channel']);
  if(!empty($playlist)) {
    mysqli_query($connection, "delete from media_organization where media_org_id = '$playlist'");
    mysqli_query($connection, "delete from playlist where playlist_id = '$playlist'");
  }
  if(!empty($channel)) {
    mysqli_query($connection, "delete from media_organization where media_org_id = '$channel'");
    mysqli_query($connection, "delete from channel where channel_id = '$channel'");
    mysqli_query($connection, "delete from subscribers where media_org_id = '$channel'");
  }
  header("Location: mediaOrganization.php");
}
?>
<!DOCTYPE html>
<html>
<head>
<link rel = "stylesheet" type = "text/css" href = "css/basic.css">
</head>

<body background = "images/back.jpg">
    
<h2 class = "white" style = "position: absolute; left: 1150px; top: 2px"><?php echo $name; ?></h2>
<h1 class = "white" style = "position: absolute; left: 500px; top: 53px;"> Delete Playlist/Channel </h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1300px;"></a>
<img src = "images/board.png" height = "330" width = "650" style = "position: absolute; top: 70px; left: 350px;">
<form style = "position: absolute; top: 165px; left: 510px;"
	  method = "post" action = "deletePlaylistChannel.php">
<b style = "color: white; font-size: large;">Playlist:</b><br/><br/>
<select name = "playlist">
  <option value = ""></option>
  <?php
    while($recordOne = mysqli_fetch_assoc($queryOne)) { ?>
      <option value = "<?php echo $recordOne['media_org_id']; ?>">
      <?php echo $recordOne['name']; ?>
      </option>
   <?php 
    }
  ?>
</select><br/><br/>
<b style = "color: white; font-size: large;">Channel:</b><br/><br/>
<select name = "channel">
  <option value = ""></option>
  <?php
    while($recordTwo = mysqli_fetch_assoc($queryTwo)) { ?>
      <option value = "<?php echo $recordTwo['media_org_id']; ?>">
      <?php echo $recordTwo['name']; ?>
      </option>
   <?php 
    }
  ?>
</select><br/><br/>

<button type = "button" class = "fancy_button" style = "position: absolute; top: 220px; left: -32px; width: 220px;"
        onclick = "window.location.href = 'mediaOrganization.php'"><span> Media Organization </span></button>

<button class = "fancy_button" name = "delete" type = "submit" value = "Delete"
          style = "position: absolute; left: 221px; top: 220px;">
    <span>Delete</span></button>
</form>
</body>
</html>