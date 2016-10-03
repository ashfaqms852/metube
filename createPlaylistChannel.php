<?php
session_start();
include_once "dbconnect.php";
include_once "functions.php";
$name = $_SESSION['name'];
$uid = $_SESSION['userID'];

if(isset($_POST['create']) == "Create") {
  $playlistChannel = mysqli_real_escape_string($connection, $_POST['playlistChannel']);
  $name = mysqli_real_escape_string($connection, $_POST['name']);
  phpAlert($playlistChannel);
  if(empty($playlistChannel) or empty($name)) {
    phpAlert("Atleast one field was empty.");
  }
  if($playlistChannel == "playlist") {
    $isPlaylist = "yes";
    $isChannel = "no";
  }
  elseif($playlistChannel == "channel") {
    $isPlaylist = "no";
    $isChannel = "yes";    
  }
  $queryOne = "insert into media_organization (user_id, name, isChannel, isPlaylist)
               values ('$uid', '$name', '$isChannel', '$isPlaylist')";
  if(mysqli_query($connection, $queryOne)) {
    header("Location: mediaOrganization.php");
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
<h1 class = "white" style = "position: absolute; left: 500px; top: 53px;"> Create Playlist/Channel </h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1300px;"></a>
<img src = "images/board.png" height = "330" width = "650" style = "position: absolute; top: 70px; left: 350px;">
<form style = "position: absolute; top: 165px; left: 510px;"
	  method = "post" action = "createPlaylistChannel.php">
<b style = "color: white; font-size: large;">Playlist or Channel: *</b><br/><br/>
<select name = "playlistChannel">
  <option value = "playlist">Playlist</option>
  <option value = "channel">Channel</option>
</select><br/><br/>
<b style = "color: white; font-size: large;">Name: *</b><br/><br/>
<input name = "name" type = "text"><br/><br/>

<button type = "button" class = "fancy_button" style = "position: absolute; top: 220px; left: -32px; width: 220px;"
        onclick = "window.location.href = 'mediaOrganization.php'"><span> Media Organization </span></button>

<button class = "fancy_button" name = "create" type = "submit" value = "Create"
          style = "position: absolute; left: 221px; top: 220px;">
    <span>Create</span></button>
</form>
</body>
</html>