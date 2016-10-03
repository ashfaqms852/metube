<!DOCTYPE html>
<html>
<head>
<link rel = "stylesheet" type = "text/css" href = "css/basic.css">
<style type = "text/css">
select.fancy_dd{
  margin: 50px;
  border: 1px solid #ff3300;
  background: #cc5200;
  width: 150px;
  padding: 5px;
  font-size: 16px;
  color: #ffffff;
  border: 1px solid #cc5200;
  height: 34px;
  position: absolute;
  top: 630px;
  left: -17px;
}
</style>
</head>
<body background = "images/back.jpg">

<?php
include_once "dbconnect.php";
include_once "functions.php";
session_start();
$name = $_SESSION['name'];
$u_id = $_SESSION['userID'];
if(isset($_GET)) {
  $mediaID = $_GET['id'];
  $flag = $_GET['flag'];
}

$incrementViews = true;
if(isset($_POST['favorite'])) {
  mysqli_query($connection, "insert into favorite (user_id, media_id) values ('$u_id', '$mediaID')");
}
if(isset($_POST['removefavorite'])) {
  mysqli_query($connection, "delete from favorite where user_id = '$u_id' and media_id = '$mediaID'");
}

$queryFour = mysqli_query($connection, "select * from favorite where user_id = '$u_id' and media_id = '$mediaID'");
$recordFour = mysqli_fetch_assoc($queryFour);
if(count($recordFour) > 0) {
  $isFavorite = 1;
}
else {
  $isFavorite = 0;
}


if(isset($_POST['addToPlaylist'])) {
  $playlist = mysqli_real_escape_string($connection, $_POST['playlist']);
  $queryEight = mysqli_query($connection, "select * from playlist where user_id = '$u_id' and media_id = '$mediaID'");
  $recordEight = mysqli_fetch_assoc($queryEight);
  if(empty($playlist)) {
    if(count($recordEight) > 0) {
      mysqli_query($connection, "delete from playlist where user_id = '$u_id' and media_id = '$mediaID'");  
    }
  }
  else {
    if(empty($recordEight['name'])) {
      $querySeven = mysqli_query($connection, "select * from media_organization where user_id = '$u_id'
                                 and isPlaylist = 'yes' and name = '$playlist'");
      $recordSeven = mysqli_fetch_assoc($querySeven);
      $pid = $recordSeven['media_org_id'];
      mysqli_query($connection, "insert into playlist (playlist_id, user_id, media_id, name) values (
                                 '$pid', '$u_id', '$mediaID', '$playlist')");
    }
    else {
      mysqli_query($connection, "update playlist set name = '$playlist' where user_id = '$u_id' and media_id = '$mediaID'");
    }
  }
}
$queryFive = mysqli_query($connection, "select * from media_organization where user_id = '$u_id' and isPlaylist = 'yes'");
$querySix = mysqli_query($connection, "select * from playlist where user_id = '$u_id' and media_id = '$mediaID'");
$recordSix = mysqli_fetch_assoc($querySix);

if(isset($_POST['postCommentScore'])) {
  $q = mysqli_query($connection, "select * from media where media_id = '$mediaID'");
  $r = mysqli_fetch_assoc($q);  
  $comment = isset($_POST['comment']) ? $_POST['comment'] : "";
  $score = isset($_POST['score']) ? $_POST['score'] : 0;
  
  if(($r['allowDiscussion'] == "no") and ($r['allowScoring'] == "no")) {
    phpAlert('Posting comments and score for this media is not allowed.');
  }
  elseif(($r['allowDiscussion'] == "yes") and ($r['allowScoring'] == "no")) {
    if(!empty($comment)) {
      mysqli_query($connection, "insert into comment (media_id, postedBy, comment)
                                   values ('$mediaID', '$u_id', '$comment')");
    }
  }
  elseif(($r['allowDiscussion'] == "no") and ($r['allowScoring'] == "yes")) {
    postScore($connection, $mediaID, $u_id, $score);
  }
  elseif(($r['allowDiscussion'] == "yes") and ($r['allowScoring'] == "yes")) {
    if(!empty($comment)) {
      mysqli_query($connection, "insert into comment (media_id, postedBy, comment)
                                   values ('$mediaID', '$u_id', '$comment')");
    }
    postScore($connection, $mediaID, $u_id, $score);
  }
  $incrementViews = false;
}

if($incrementViews) {
  mysqli_query($connection, "update media set views = views+1 where media_id = '$mediaID'");
}

$queryOne = mysqli_query($connection, "select * from media where media_id = '$mediaID'");
$recordOne = mysqli_fetch_assoc($queryOne);
$recommendKeyword = $recordOne['title'] . $recordOne['description'] . $recordOne['filename'] . $recordOne['keywordOne'] .
                    $recordOne['keywordTwo'] . $recordOne['keywordThree'];
$queryThree = mysqli_query($connection, "select * from media where (instr('$recommendKeyword', title) or
                           instr('$recommendKeyword', description) or instr('$recommendKeyword', filename) or
                           instr('$recommendKeyword', keywordOne) or instr('$recommendKeyword', keywordTwo) or
                           instr('$recommendKeyword', keywordThree)) and user_id != '$u_id' and media_id != '$mediaID'"); 

$disabledOne = ($recordOne['allowDiscussion'] == "yes") ? "" : "disabled";
$disabledTwo = ($recordOne['allowScoring'] == "yes") ? "" : "disabled";
if($flag == 1) {
  $uname = $_SESSION['username'];
}
elseif($flag == 0) {
  $uID = $recordOne['user_id'];  
  $queryTwo = mysqli_query($connection, "select * from signup_details where signup_id = '$uID'");
  $recordTwo = mysqli_fetch_assoc($queryTwo);
  $uname = $recordTwo['username'];
} ?>
<h2 class = "white" style = "position: absolute; left: 1130px; top: 2px"><?php echo $name; ?></h2>
<h1 class = "white" style = "position: absolute; left: 30px; top: -10px;"><?php echo $recordOne['filename']; ?></h1>
<h1 class = "white" style = "position: absolute; left: 600px; top: 110px;">Recommended Media</h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1280px;"></a>
<table style = "position: absolute; top: 170px; left: 600px; width: 600px;">
<?php
//recommendMedia($queryThree, 0);
searchFunctionality($connection, $queryThree, $u_id);
?>
</table>
<img src = "images/board.png" height = "290" width = "850" style = "position: absolute; top: 370px; left: -150px;">
<?php $mediaLink = "/uploads/" . $uname . "/" . $recordOne['filename'];
//<?php $mediaLink = "/~asherwa/uploads/" . $uname . "/" . $recordOne['filename'];
$category = $recordOne['category'];
if($category == "image") {
?>
<img src = "<?php echo $mediaLink; ?>" style = "position: absolute; left: 30px; top: 85px;"
     height = "300" width = "500">
<?php }
elseif($category == "video") {
?>
<video width = "500" height = "300" style = "position: absolute; left: 30px; top: 55px;" controls>
<source src = "<?php echo $mediaLink; ?>" type = "video/mp4">
Sorry, your browser does not support HTML5 video.
</video>
<?php }
elseif($category == "audio") { ?>
<img src = "images/songs.png" style = "position: absolute; left: 50px; top: 45px"
     height = "290" width = "450">
<audio controls style = "position: absolute; left: 80px; top: 345px;">
<source src = "<?php echo $mediaLink; ?>" type = "audio/mp3">
Sorry, your browser does not support HTML5 audio.
</audio>
<?php }
$path = "viewMedia.php?id=" . $mediaID . "&flag=" . $flag;
?>


<form action = "<?php echo $path; ?>" style = "position: absolute; top: 442px; left: 45px" method = "post">
<b>Comment:</b><br/>
<textarea name = "comment" rows = "3" cols = "50" placeholder = "Type your comment here"
          <?php echo $disabledOne; ?>>
</textarea><br/><br/>
<?php
$q3 = mysqli_query($connection, "select * from score where media_id = '$mediaID' and postedBy = '$u_id'");
$r3 = mysqli_fetch_assoc($q3);
$scr = count($r3) > 0 ? $r3['score'] : 0; ?>
<b>Score:</b> &nbsp;
<input type = "number" name = "score" min = "0" max = "5" step = "0.1"
       value = "<?php echo $scr; ?>" <?php echo $disabledTwo; ?>>
       
  <button class = "fancy_button" name = "postCommentScore" type = "submit" value = "Post"
          style = "position: absolute; left: -20px; top: 170px;">
    <span>Post</span></button>
  <?php if($isFavorite == 0) { ?>
  
  <button class = "fancy_button" name = "favorite" type = "submit" value = "Favorite"
          style = "position: absolute; left: 190px; top: 170px; width: 200px;">
    <span>Add To Favorites</span></button>
  <?php } else { ?>
  <button class = "fancy_button" name = "removefavorite" type = "submit" value = "Favorite"
          style = "position: absolute; left: 190px; top: 170px; width: 200px;">
    <span>Remove From Favorites</span></button>
  <?php } ?> 
</form>
<form action = "<?php echo $path; ?>" method = "post">
<select class = "fancy_dd" name = "playlist">
  <option value = ""></option>
<?php
while($recordFive = mysqli_fetch_assoc($queryFive)) {
  if($recordSix['name'] == $recordFive['name']) { ?>
  <option value = "<?php echo $recordFive['name']; ?>" selected = "selected">
  <?php echo $recordFive['name']; ?>
  </option>
<?php    
  }
  else { ?>
  <option value = "<?php echo $recordFive['name']; ?>">
  <?php echo $recordFive['name']; ?>
  </option>
<?php  
  }
}
?>
</select>
<button class = "fancy_button" name = "addToPlaylist" type = "submit" value = "Playlist"
        style = "position: absolute; left: 235px; top: 670px; width: 200px;">
<span>Add To Playlist</span></button>
</form>
<h2 style = "position: absolute; top: 710px;">Average Score = <?php echo $recordOne['averageScore']; ?></h2>
<h2 style = "position: absolute; top: 750px;">Comments:</h2>
<table style = "position: absolute; top: 820px; left: 0px;">
  <tr>
    <th>Posted By</th>
    <th>Comment</th>
    <th>Posted On</th>
    <th>Delete</th>
  </tr>
  <?php addTableRowForComments($connection, $mediaID, $u_id, $flag); ?>
</table>
</body>
</html>