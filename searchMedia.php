<?php
session_start();
$name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" type = "text/css" href = "css/basic.css">
</head>

<body background = "images/back.jpg">
<h2 class = "white" style = "position: absolute; left: 1150px; top: 2px"><?php echo $name; ?></h2>
<h1 class = "white" style = "position: absolute; left: 560px; top: 53px;"> Search Results </h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1300px;"></a>
<table style = "position: absolute; top: 120px; left: 0px;">

<?php
  include_once "dbconnect.php";
  include_once "functions.php";
  $usID = $_SESSION['userID'];
  if(isset($_POST['media'])) {
    $keyword = $_POST['searchMedia'];
    $queryOne = mysqli_query($connection, "select * from media where title like '%" . $keyword .
                             "%' or description like '%" . $keyword . "%' or filename like '%" . $keyword .
                             "%' or keywordOne like '%" . $keyword . "%' or keywordTwo like '%" . $keyword .
                             "%' or keywordThree like '%" . $keyword . "%'");
  }
  elseif(isset($_POST['advancedSearch'])) {
    $keyword = $_POST['keyword'];
    $category = ($_POST['category'] == 'select') ? "" : $_POST['category'];
    $avgScore = $_POST['averageScore'];
    $allowDiscussion = ($_POST['allowDiscussion'] == 'select') ? "" : $_POST['allowDiscussion'];
    $uploadedBefore = ($_POST['uploadedBefore'] == '') ? date('Y/m/d') : $_POST['uploadedBefore']; 
    $uploadedAfter = $_POST['uploadedAfter'];
    $sizeMoreThan = $_POST['sizeMoreThan']*1048576;
    $sizeLessThan = $_POST['sizeLessThan']*1048576;
    $views = $_POST['viewsOfAtleast'];
    
    $queryOne = mysqli_query($connection, "select * from media where (title like '%" . $keyword .
                             "%' or description like '%" . $keyword . "%' or filename like '%" . $keyword .
                             "%' or keywordOne like '%" . $keyword . "%' or keywordTwo like '%" . $keyword .
                             "%' or keywordThree like '%" . $keyword . "%') and category like '%$category%'
                             and averageScore >= '$avgScore' and allowDiscussion like '%$allowDiscussion%' and
                             date(uploadedOn) < '$uploadedBefore' and date(uploadedOn) >= '$uploadedAfter' and
                             size < '$sizeLessThan' and size >= '$sizeMoreThan' and views >= '$views'");
  }
  elseif(isset($_GET)) {
    if(isset($_GET['keyword'])) {
      $keyword = $_GET['keyword'];
      $queryOne = mysqli_query($connection, "select * from media where title like '%" . $keyword .
                             "%' or description like '%" . $keyword . "%' or filename like '%" . $keyword .
                             "%' or keywordOne like '%" . $keyword . "%' or keywordTwo like '%" . $keyword .
                             "%' or keywordThree like '%" . $keyword . "%'");
    }
    elseif(isset($_GET['sort'])) {
      if($_GET['sort'] == "uploadedOn") {
        $queryOne = mysqli_query($connection, "select * from media order by uploadedOn desc");
      }
      elseif($_GET['sort'] == "views") {
        $queryOne = mysqli_query($connection, "select * from media order by views desc");
      }
      elseif($_GET['sort'] == "averageScore") {
        $queryOne = mysqli_query($connection, "select * from media order by averageScore desc");
      }
    }
    elseif(isset($_GET['category'])) {
      if($_GET['category'] == "image") {
        $queryOne = mysqli_query($connection, "select * from media where category = 'image'");
      }
      elseif($_GET['category'] == "audio") {
        $queryOne = mysqli_query($connection, "select * from media where category = 'audio'");
      }
      elseif($_GET['category'] == "video") {
        $queryOne = mysqli_query($connection, "select * from media where category = 'video'");
      }
    }
    elseif(isset($_GET['shareWith'])) {
      if($_GET['shareWith'] == "contacts") {
        $queryOne = mysqli_query($connection, "select * from media m inner join contacts c
                                 on m.user_id = c.userID where c.contactOf = '$usID'");
      }
      elseif($_GET['shareWith'] == "friends") {
        $queryOne = mysqli_query($connection, "select * from media m inner join contacts c
                                 on m.user_id = c.userID where c.contactOf = '$usID' and c.isFriend = 'yes'");
      }
    }
  }

  searchFunctionality($connection, $queryOne, $usID);
?> 
</table>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 65px; left: 10px;"
        onclick = "window.location.href = 'dashboard.php'"><span> Dashboard </span></button>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 65px; left: 1150px; width: 200px;"
        onclick = "window.location.href = 'advancedSearch.php'"><span> Advanced Search </span></button>
</body>
</html>