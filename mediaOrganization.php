<?php
session_start();
include_once "functions.php";
include_once "dbconnect.php";
$name = $_SESSION['name'];
$uid = $_SESSION['userID'];
$flag = 0;

if(isset($_POST['viewFavorites']) == "View My Favorites") {
  $query = mysqli_query($connection, "select * from favorite where user_id = '$uid'");
  $flag = 1;
}
elseif(isset($_POST['viewPlaylist']) == "View Playlist") {
  $playlist = mysqli_real_escape_string($connection, $_POST['playlist']);
  $query = mysqli_query($connection, "select * from playlist where user_id = '$uid' and name = '$playlist'");
  $flag = 1;  
}
elseif(isset($_POST['viewChannel']) == "View Channel") {
  $channel = mysqli_real_escape_string($connection, $_POST['channels']);
  $query = mysqli_query($connection, "select * from channel where user_id = '$uid' and name = '$channel'");
  $flag = 1;    
}
elseif(isset($_POST['viewSubscribedChannel']) == "View Subscribed Channel") {
  $channel = mysqli_real_escape_string($connection, $_POST['subscribedChannels']);
  $queryFour = mysqli_query($connection, "select * from media_organization where media_org_id = '$channel'");
  $recordFour = mysqli_fetch_assoc($queryFour);
  $user = $recordFour['user_id'];
  $cname = $recordFour['name'];
  $query = mysqli_query($connection, "select * from channel where user_id = '$user' and name = '$cname'");
  $flag = 1;    
}
?>
<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" type = "text/css" href = "css/basic.css">
</head>

<body background = "images/back.jpg">
<h2 class = "white" style = "position: absolute; left: 1150px; top: 2px"><?php echo $name; ?></h2>
<h1 class = "white" style = "position: absolute; left: 545px; top: 53px;"> My Media Organization </h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1300px;"></a>
<form style = "position: absolute; top: 140px; left: 15px;"
	  method = "post" action = "mediaOrganization.php">
<select name = "playlist">
  <option value = "select">Select Playlist</option>
<?php
  $queryTwo = mysqli_query($connection, "select * from media_organization where user_id = '$uid' and isPlaylist = 'yes'");
  while($recordTwo = mysqli_fetch_assoc($queryTwo)) { ?>
    <option value = "<?php echo $recordTwo['name']; ?>">
    <?php echo $recordTwo['name']; ?>
    </option>
  <?php }
?>
</select>
<button type = "submit" name = "viewPlaylist" value = "View Playlist">View Playlist</button>
</form>
<form style = "position: absolute; top: 140px; left: 250px;"
	  method = "post" action = "mediaOrganization.php">
<select name = "channels">
  <option value = "select">Select Channel</option>
<?php
  $queryTwo = mysqli_query($connection, "select * from media_organization where user_id = '$uid' and isChannel = 'yes'");
  while($recordTwo = mysqli_fetch_assoc($queryTwo)) { ?>
    <option value = "<?php echo $recordTwo['name']; ?>">
    <?php echo $recordTwo['name']; ?>
    </option>
  <?php }
?>
</select>
<button type = "submit" name = "viewChannel" value = "View Channel">View My Channel</button>
</form>
<form style = "position: absolute; top: 140px; left: 500px;"
	  method = "post" action = "mediaOrganization.php">
<button type = "submit" name = "viewFavorites" value = "View My Favorites">View My Favorites</button>
</form>

<form style = "position: absolute; top: 140px; left: 650px;"
      method = "post" action = "mediaOrganization.php">
<select name = "subscribedChannels">
  <option value = "select">Select Subscribed Channel</option>
<?php
  $queryTwo = mysqli_query($connection, "select * from subscribers where user_id = '$uid'");
  while($recordTwo = mysqli_fetch_assoc($queryTwo)) {
	$m_org_id = $recordTwo['media_org_id'];
	$queryThree = mysqli_query($connection, "select * from media_organization where media_org_id = '$m_org_id'
							   and isChannel = 'yes'");
	$recordThree = mysqli_fetch_assoc($queryThree);
	?>
	<option value = "<?php echo $recordThree['media_org_id']; ?>">
	<?php echo $recordThree['name']; ?>
	</option>
<?php
  }
?>
</select>
<button type = "submit" name = "viewSubscribedChannel" value = "View Subscribed Channel">View Subscribed Channel</button>
</form>

<table style = "position: absolute; top: 180px; left: 0px;">
  <tr>
    <th>Filename</th>
    <th>Uploaded On</th>
    <th>Total Views</th>
    <th>Average Score</th>
    <th>View/Comment/Score</th>
  </tr>
<?php
if($flag == 1) {
while($record = mysqli_fetch_assoc($query)) {
$m_id = $record['media_id'];
$queryOne = mysqli_query($connection, "select * from media where media_id = '$m_id'");
$recordOne = mysqli_fetch_assoc($queryOne);
$mediaLink = 'uploads/' . $_SESSION['username'] . '/' . $recordOne['filename'];
?>
    <tr>
    <td><?php echo $recordOne['filename']; ?></td>
    <td> <?php echo $recordOne['uploadedOn']; ?> </td>
    <td> <?php echo $recordOne['views']; ?> </td>
    <td> <?php echo $recordOne['averageScore']; ?> </td>
    <?php
    $newTab = "viewMedia.php?id=" . $recordOne['media_id'] . "&flag=0";
    ?>
    <td><a href = "<?php echo $newTab; ?>" target = "_blank">View/Comment/Score</td>
    </tr>
<?php } }?> 
</table>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 60px; left: 10px;"
        onclick = "window.location.href = 'dashboard.php'"><span> Dashboard </span></button>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 60px; left: 1120px; width: 230px;"
        onclick = "window.location.href = 'createPlaylistChannel.php'"><span> Create Playlist/Channel </span></button>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 120px; left: 1120px; width: 230px;"
        onclick = "window.location.href = 'deletePlaylistChannel.php'"><span> Delete Playlist/Channel </span></button>
</body>
</html>