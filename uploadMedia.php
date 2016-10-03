<?php
session_start();
include_once "dbconnect.php";
$uid = $_SESSION['userID'];
$name = " " . $_SESSION['name'];
$queryOne = mysqli_query($connection, "select * from media_organization where user_id = '$uid' and isPlaylist = 'yes'");
$queryTwo = mysqli_query($connection, "select * from media_organization where user_id = '$uid' and isChannel = 'yes'");
?>
<!DOCTYPE html>
<html>
<head>
<link rel = "stylesheet" type = "text/css" href = "css/basic.css">
<link rel = "stylesheet" type = "text/css" href = "css/upload.css">
</head>

<body background = "images/back.jpg">
<h2 class = "white" style = "position: absolute; left: 1120px; top: 2px"><?php echo $name; ?></h2>
<h1 class = "white" style = "position: absolute; left: 595px; top:25px;"> Upload </h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1280px;"></a>
<img src = "images/board.png" height = "770" width = "650" style = "position: absolute; top: -40px; left: 330px;">

<form name = "upload_form" style = "position: absolute; top: 138px; left: 500px;"
	  method = "post" action = "upload.php" enctype="multipart/form-data">

<b style = "color: white; font-size: large;">Select a file to upload: *</b><br/>
<input type="text" id="fileName" class="file_textbox" readonly="readonly">
<div class="file_div">
  <input id="fileButton" type="button" value="Browse" class="file_button" />
  <input type="file" class="file_hidden"  name = "file"
      onchange="javascript: document.getElementById('fileName').value = this.value" 
      onmouseover="document.getElementById('fileButton').className='file_button_hover';"
      onmouseout="document.getElementById('fileButton').className='file_button';" />
</div>
<b style = "color: white;"><i>Formats allowed: jpg, jpeg, png, gif, mp3 and mp4.</i></b><br/>
<b style = "color: white;"><i>Maximum file size: 64MB.</i></b><br/><br/>
<b style = "color: white; font-size: large;">Title: *</b>&nbsp;
<input name = "title" type = "text"><br/><br/>
<b style = "color: white; font-size: large;">Description: *</b> &nbsp;
<input name = "description" type = "text"><br/><br/>
<b style = "color: white; font-size: large;">Keywords: *</b><br/>
<input name = "keywordOne" type = "text" size = "8"> &nbsp; &nbsp;
<input name = "keywordTwo" type = "text" size = "8"> &nbsp; &nbsp;
<input name = "keywordThree" type = "text" size = "8"> <br/><br/>
<b style = "color: white; font-size: large;">Share with: *</b>&nbsp;&nbsp;
<select name = "shareWith">
  <option value="everybody">Everybody</option>
  <option value="contacts">Only Contacts</option>
  <option value="friends">Only Friends</option>
  <option value="nobody">Nobody</option>
</select><br/><br/>
<b style = "color: white; font-size: large;">Allow discussion: *</b>&nbsp;&nbsp;&nbsp;&nbsp;
<b style = "color: white; font-size: large;">Allow scoring: *</b><br/>
<select name = "allowDiscussion">
  <option value = "yes">Yes</option>
  <option value = "no">No</option>
</select>
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<select name = "allowScoring">
  <option value = "yes">Yes</option>
  <option value = "no">No</option>
</select><br/><br/>
<b style = "color: white; font-size: large;">Insert Into Playlist: </b>
<select name = "playlist">
  <option value = ""></option>
  <?php
  while($recordOne = mysqli_fetch_assoc($queryOne)) { ?>
    <option value = "<?php echo $recordOne['name']; ?>">
    <?php echo $recordOne['name']; ?>
	</option>  
  <?php }
  ?>
</select><br/><br/>
<b style = "color: white; font-size: large;">Insert Into Channel: </b>
<select name = "channel">
  <option value = ""></option>
  <?php
  while($recordTwo = mysqli_fetch_assoc($queryTwo)) { ?>
    <option value = "<?php echo $recordTwo['name']; ?>">
    <?php echo $recordTwo['name']; ?>
	</option>  
  <?php }
  ?>
</select>

<button type = "button" class = "fancy_button" style = "position: absolute; top: 440px; left: -8px;"
        onclick = "window.location.href = 'dashboard.php'"><span> Dashboard </span></button>

<button class = "fancy_button" name = "upload" type = "submit" value = "Upload"
          style = "position: absolute; left: 157px; top: 440px;">
    <span>Upload</span></button>
</form>
</body>
</html>