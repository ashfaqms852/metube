<?php
include_once "dbconnect.php";
include_once "functions.php";

session_start();
$name = $_SESSION['name'];
$mediaID = $_GET['id'];
$uid = $_SESSION['userID'];
$queryOne = mysqli_query($connection, "select * from media where media_id = '$mediaID'");
$recordOne = mysqli_fetch_assoc($queryOne);
$queryTwo = mysqli_query($connection, "select * from media_organization where user_id = '$uid' and isPlaylist = 'yes'");
$queryThree = mysqli_query($connection, "select * from media_organization where user_id = '$uid' and isChannel = 'yes'");

if(isset($_POST['edit']) == "Edit") {
  $title = mysqli_real_escape_string($connection, $_POST['title']);
  $description = mysqli_real_escape_string($connection, $_POST['description']);
  $keywordOne = mysqli_real_escape_string($connection, $_POST['keywordOne']);
  $keywordTwo = mysqli_real_escape_string($connection, $_POST['keywordTwo']);
  $keywordThree = mysqli_real_escape_string($connection, $_POST['keywordThree']);
  $shareWith = mysqli_real_escape_string($connection, $_POST['shareWith']);
  $allowDiscussion = mysqli_real_escape_string($connection, $_POST['allowDiscussion']);
  $allowScoring = mysqli_real_escape_string($connection, $_POST['allowScoring']);
  $playlist = mysqli_real_escape_string($connection, $_POST['playlist']);
  $channel = mysqli_real_escape_string($connection, $_POST['channel']);
  
  echo 'Kindly press BACK button.';
  
  if(empty($title) or empty($description) or empty($keywordOne) or empty($keywordTwo)
    or empty($keywordThree) or empty($shareWith) or empty($allowDiscussion) or empty($allowScoring)) {
    phpAlert("Atleast one field is empty");  
  }
  else {
    $queryTwo = "update media set title = '$title', description = '$description',
                 keywordOne = '$keywordOne', keywordTwo = '$keywordTwo', keywordThree = '$keywordThree',
                 shareWith = '$shareWith', allowDiscussion = '$allowDiscussion', allowScoring = '$allowScoring',
				 playlist = '$playlist', channel = '$channel' where media_id = '$mediaID'";
	mysqli_query($connection, $queryTwo);
	$querySix = mysqli_query($connection, "select * from playlist where user_id = '$uid' and media_id = '$mediaID'");
	$recordSix = mysqli_fetch_assoc($querySix);
	if(count($recordSix) > 0) {
	  if(!empty($playlist)) {
		$querySeven = mysqli_query($connection, "update playlist set name = '$playlist' where user_id = '$uid' and
								   media_id = '$mediaID'");
	  }
	  else {
	    $querySeven = mysqli_query($connection, "delete from playlist where user_id = '$uid' and media_id = '$mediaID'");
	  }
	}  
    else {
	  $queryEight = mysqli_query($connection, "select * from media_organization where user_id = '$uid' and
								   isPlaylist = 'yes' and name = '$playlist'");
	  $recordEight = mysqli_fetch_assoc($queryEight);
	  $pid = $recordEight['media_org_id'];
	  $querySeven = mysqli_query($connection, "insert into playlist (playlist_id, user_id, media_id, name)
								   values ('$pid', '$uid', '$mediaID', '$playlist')");
    }
	
	$queryNine = mysqli_query($connection, "select * from channel where user_id = '$uid' and media_id = '$mediaID'");
	$recordNine = mysqli_fetch_assoc($queryNine);
	if(count($recordNine) > 0) {
	  if(!empty($channel)) {
		$queryTen = mysqli_query($connection, "update channel set name = '$channel' where user_id = '$uid' and
								   media_id = '$mediaID'");
	  }
	  else {
	    $queryTen = mysqli_query($connection, "delete from channel where user_id = '$uid' and media_id = '$mediaID'");
	  }
	}  
    else {
	  $queryEleven = mysqli_query($connection, "select * from media_organization where user_id = '$uid' and
								   isChannel = 'yes' and name = '$channel'");
	  $recordEleven = mysqli_fetch_assoc($queryEleven);
	  $cid = $recordEleven['media_org_id'];
	  $queryTen = mysqli_query($connection, "insert into channel (channel_id, user_id, media_id, name)
								   values ('$cid', '$uid', '$mediaID', '$channel')");
    }
    $urlOne = "Location: editDeleteMedia.php?id=" . $mediaID;   
    header($urlOne);
  }  
}

if(isset($_POST['delete']) == "Delete") {
  $directory = 'uploads' . DIRECTORY_SEPARATOR .  $_SESSION['username'] . DIRECTORY_SEPARATOR . $recordOne['filename'];
  if(unlink($directory)) {
    $queryThree = "delete from media where media_id = '$mediaID'";
    mysqli_query($connection, $queryThree);
    header('Location: myUploads.php');
  }
  else {
    phpAlert("Something went wrong while deleting.");
  }
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel = "stylesheet" type = "text/css" href = "css/basic.css">
<body background = "images/back.jpg">
<h2 class = "white" style = "position: absolute; left: 1150px; top: 2px"><?php echo $name; ?></h2>
<h1 class = "white" style = "position: absolute; left: 566px; top:65px;"> Edit/Delete </h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1300px;"></a>
<img src = "images/board.png" height = "700" width = "600" style = "position: absolute; top: 0px; left: 350px;">

<?php $urlTwo = "editDeleteMedia.php?id=" . $mediaID; ?>
<form name = "edit_media" style = "position: absolute; top: 160px; left: 500px;"
	  method = "post" action = "<?php echo $urlTwo; ?>" enctype="multipart/form-data">
      
<b style = "color: white; font-size: large;">Filename: </b><br/>
<input name = "filename" type = "text" value = "<?php echo $recordOne['filename']; ?>" disabled><br/><br/>

<b style = "color: white; font-size: large;">Title: *</b> &nbsp;
<input name = "title" type = "text" value = "<?php echo $recordOne['title']; ?>"><br/><br/>

<b style = "color: white; font-size: large;">Description: *</b> &nbsp;
<input name = "description" type = "text" value = "<?php echo $recordOne['description']; ?>"><br/><br/>

<b style = "color: white; font-size: large;">Keywords: *</b><br/>
<input name = "keywordOne" type = "text" size = "8" value = "<?php echo $recordOne['keywordOne']; ?>"> &nbsp; &nbsp;
<input name = "keywordTwo" type = "text" size = "8" value = "<?php echo $recordOne['keywordTwo']; ?>"> &nbsp; &nbsp;
<input name = "keywordThree" type = "text" size = "8" value = "<?php echo $recordOne['keywordThree']; ?>"> <br/><br/>

<b style = "color: white; font-size: large;">Share with: *</b>
<select name = "shareWith">
  <?php
  $selOne = ($recordOne['shareWith'] == "everybody") ? "selected = 'selected'" : "";
  $selTwo = ($recordOne['shareWith'] == "contacts") ? "selected = 'selected'" : "";
  $selThree = ($recordOne['shareWith'] == "friends") ? "selected = 'selected'" : "";
  $selFour = ($recordOne['shareWith'] == "nobody") ? "selected = 'selected'" : "";
  ?>
  <option value="everybody" <?php echo $selOne; ?>>Everybody</option>
  <option value="contacts" <?php echo $selTwo; ?>>Only Contacts</option>
  <option value="friends" <?php echo $selThree; ?>>Only Friends</option>
  <option value="nobody" <?php echo $selFour; ?>>Nobody</option>
</select><br/><br/>

<b style = "color: white; font-size: large;">Allow discussion: *</b>&nbsp;&nbsp;&nbsp;&nbsp;
<b style = "color: white; font-size: large;">Allow scoring: *</b><br/>

<select name = "allowDiscussion">
  <?php
  $selFive = ($recordOne['allowDiscussion'] == "yes") ? "selected = 'selected'" : "";
  $selSix = ($recordOne['allowDiscussion'] == "no") ? "selected = 'selected'" : "";
  ?>
  <option value = "yes" <?php echo $selFive; ?>>Yes</option>
  <option value = "no" <?php echo $selSix; ?>>No</option>
</select>

&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<select name = "allowScoring">
  <?php
  $selSeven = ($recordOne['allowScoring'] == "yes") ? "selected = 'selected'" : "";
  $selEight = ($recordOne['allowScoring'] == "no") ? "selected = 'selected'" : "";
  ?>
  <option value = "yes" <?php echo $selSeven; ?>>Yes</option>
  <option value = "no" <?php echo $selEight; ?>>No</option>
</select><br/><br/>
<b style = "color: white; font-size: large;">Insert Into Playlist: </b> &nbsp;&nbsp;
<select name = "playlist">
<option value = ""></option>
<?php
$queryFour = mysqli_query($connection, "select * from playlist where user_id = '$uid' and media_id = '$mediaID'");
$recordFour = mysqli_fetch_assoc($queryFour);

while($recordTwo = mysqli_fetch_assoc($queryTwo)) {
  if(($recordFour['name'] == $recordTwo['name']))  { ?>
  <option value = "<?php echo $recordTwo['name']; ?>" selected = "selected">
  <?php echo $recordTwo['name']; ?>
  </option>
<?php
  }
  else { ?>
  <option value = "<?php echo $recordTwo['name']; ?>">
  <?php echo $recordTwo['name']; ?>
  </option>	
<?php  
  }
} ?>
</select><br/><br/>
<b style = "color: white; font-size: large;">Insert Into Channel: </b> &nbsp;&nbsp;
<select name = "channel">
<option value = ""></option>
<?php
$queryFive = mysqli_query($connection, "select * from channel where user_id = '$uid' and media_id = '$mediaID'");
$recordFive = mysqli_fetch_assoc($queryFive);
while($recordThree = mysqli_fetch_assoc($queryThree)) {
  if($recordFive['name'] == $recordThree['name'])  { ?>
  <option value = "<?php echo $recordThree['name']; ?>" selected = "selected">
  <?php echo $recordThree['name']; ?>
  </option>
<?php
  }
  else { ?>
  <option value = "<?php echo $recordThree['name']; ?>">
  <?php echo $recordThree['name']; ?>
  </option>	
<?php  
  }
} ?>
</select>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 420px; left: -90px;"
        onclick = "window.location.href = 'dashboard.php'"><span> Dashboard </span></button>
  <button class = "fancy_button" name = "edit" type = "submit" value = "Edit"
          style = "position: absolute; left: 77px; top: 420px;">
    <span>Edit</span></button>
  <button class = "fancy_button" name = "delete" type = "submit" value = "Delete"
          style = "position: absolute; left: 242px; top: 420px;">
    <span>Delete</span></button>

</form>
</body>
</html>