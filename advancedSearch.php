<?php
session_start();
$name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" type = "text/css" href = "css/cloud.css">    
<link rel = "stylesheet" type = "text/css" href = "css/basic.css">    
</head>

<body background = "images/back.jpg">
<h2 class = "white" style = "position: absolute; left: 1150px; top: 2px"><?php echo $name; ?></h2>
<h1 class = "white" style = "position: absolute; left: 520px; top: 30px;"> Advanced Search </h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1300px;"></a>
<div class="cloud" style = "position: absolute;  left: 35px; top:200px;">
  <ul>
  <li><a href="searchMedia.php?sort=uploadedOn">latest uploads</a></li>
  <li><a href="searchMedia.php?keyword=football">football</a></li>
  <li><a href="searchMedia.php?category=image">image</a></li>
  <li><a href="searchMedia.php?keyword=song">song</a></li>
  <li><a href="searchMedia.php?sort=views">most views</a></li>
  <li><a href="searchMedia.php?category=video">video</a></li>
  <li><a href="searchMedia.php?keyword=clemson">clemson</a></li>
  <li><a href="searchMedia.php?sort=averageScore">highly rated</a></li>
  <li><a href="searchMedia.php?category=audio">audio</a></li>
  <li><a href="searchMedia.php?keyword=wallpaper">wallpaper</a></li>
  <li><a href="searchMedia.php?shareWith=contacts">contacts</a></li>
  <li><a href="searchMedia.php?keyword=insta">insta</a></li>
  <li><a href="searchMedia.php?keyword=movie">movie</a></li>
  <li><a href="searchMedia.php?keyword=selfie">selfie</a></li>
  <li><a href="searchMedia.php?shareWith=friends">friends</a></li>
  </ul>
</div>
<div>
<img src = "images/board.png" height = "670" width = "770" style = "position: absolute; top: 40px; left: 550px;">
</div>
<form action = "searchMedia.php" style = "position: absolute; left: 740px; top: 200px"
      method = "post">
<b>Keyword:</b>&nbsp;&nbsp;<input type = "text" name = "keyword"><br/><br/>
<b>Category:</b>&nbsp;&nbsp;
<select name = "category">
  <option value = "select">Select</option>
  <option value = "image">Image</option>
  <option value = "audio">Audio</option>
  <option value = "video">Video</option>
</select>
<br/><br/>
<b>Average score of atleast:</b>&nbsp;&nbsp;
<input type = "number" name = "averageScore" min = "0" max = "5" step = "1" value = "0">
<br/><br/>
<b>Discussion allowed:</b>&nbsp;&nbsp;
<select name = "allowDiscussion">
  <option value = "select">Select</option>
  <option value = "yes">Yes</option>
  <option value = "no">No</option>
</select><br/><br/>
<b>Uploaded before:</b>&nbsp;&nbsp;
<input type = "date" name = "uploadedBefore"><br/><br/>
<b>Uploaded after:</b>&nbsp;&nbsp;
<input type = "date" name = "uploadedAfter"><br/><br/>
<b>Size more than:</b>&nbsp;&nbsp;
<input type = "number" name = "sizeMoreThan" min = "0" max = "63" step = "0.1" value = "0">
<i>MB</i>
&nbsp;&nbsp; <br/><br/>
<b>Less than:</b>&nbsp;&nbsp;
<input type = "number" name = "sizeLessThan" min = "1" max = "64" step = "0.1" value = "64">
<i>MB</i><br/><br/>
<b>Views of atleast:</b>&nbsp;&nbsp;
<input type = "number" name = "viewsOfAtleast" min = "0" step = "1" value = "0">
  <button class = "fancy_button" name = "advancedSearch" type = "submit" value = "Search"
          style = "position: absolute; left: 220px; top: 375px;">
    <span>Search</span></button>
</form>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 575px; left: 737px;"
        onclick = "window.location.href = 'dashboard.php'"><span> Dashboard </span></button>
</body>
</html>
