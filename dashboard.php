<?php
session_start();
$name = " " . $_SESSION['name'];
?>
<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" type = "text/css" href = "css/basic.css">
</head>

<body background = "images/back.jpg">
<h2 class = "white" style = "position: absolute; left: 1120px; top: 2px"><?php echo $name; ?></h2>
<h1 class = "white" style = "position: absolute; left: 590px; top: 53px;"> Dashboard </h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1275px;"></a>
<img src = "images/board.png" height = "520" width = "500" style = "position: absolute; top: 40px; left: 420px;">
<form action = "searchPeople.php" method = "post">
<input type="text" name="searchPeople" class="search" autocomplete="off" spellcheck="false"
       style = "position: absolute; left: 1050px; top: 75px;" placeholder="Search for people">
<button class = "fancy_button" name = 'people' style = "width: 30px; height: 30px; padding: 7px;
        position: absolute; left: 1280px; top: 72px;">
  <img src = "images/search.png" height = 18px; width = 18px;>
</button>
</form>
<form action = "searchMedia.php" method = "post">
<input type="text" name="searchMedia" class="search" autocomplete="off" spellcheck="false"
       style = "position: absolute; left: 1050px; top: 122px;" placeholder="Search for media">
<button class = "fancy_button" name = 'media' style = "width: 30px; height: 30px; padding: 7px;
        position: absolute; left: 1280px; top: 119px;">
  <img src = "images/search.png" height = 18px; width = 18px;>
</button>
</form>
<form action = "advancedSearch.php" method = "post">
<button class = "fancy_button" name = 'advancedSearchButton'
  style = "vertical-align: middle; position: absolute; left: 1113px; top: 169px; width: 200px;">
    <span>Advanced Search</span></button>
</form>


<!--<button type = "button" class = "fancy_button" style = "vertical-align: middle; position: absolute; top: 170px; left: 587px;"
        onclick = "window.location.href = 'uploadMedia.php'"><span> Upload </span></button>-->
<button type = "button" class = "fancy_button" style = "vertical-align: middle; position: absolute; top: 170px; left: 587px;"
        onclick = "window.location.href = 'myUploads.php'"><span> Uploads </span></button>
<button type = "button" class = "fancy_button" style = "vertical-align: middle; position: absolute; top: 220px; left: 587px;"
        onclick = "window.location.href = 'updateProfile.php'"><span>Edit Profile</span></button>
<button type = "button" class = "fancy_button" style = "vertical-align: middle; position: absolute; top: 270px; left: 587px;"
        onclick = "window.location.href = 'contacts.php'"><span> Contacts </span></button>
<button type = "button" class = "fancy_button" style = "vertical-align: middle; position: absolute; top: 320px; left: 587px;"
        onclick = "window.location.href = 'mediaOrganization.php'"><span> Media Organization </span></button>
<button type = "button" class = "fancy_button" style = "vertical-align: middle; position: absolute; top: 390px; left: 587px;"
        onclick = "window.location.href = 'groups.php'"><span> Groups </span></button>

</body>
</html>
