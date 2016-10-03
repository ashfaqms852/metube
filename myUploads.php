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
<h1 class = "white" style = "position: absolute; left: 575px; top: 53px;"> My Uploads </h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1300px;"></a>
<table style = "position: absolute; top: 120px; left: 0px;">
  <tr>
    <th>Filename</th>
    <th>Uploaded On</th>
    <th>Total Views</th>
    <th>Average Score</th>
    <th>Edit/Delete</th>
    <th>View/Comment/Score</th>
  </tr>
<?php
include_once "dbconnect.php";

$userID = $_SESSION['userID'];
$queryOne = mysqli_query($connection, "select * from media where user_id = '$userID'");
while($recordOne = mysqli_fetch_assoc($queryOne)) {
$mediaLink = 'uploads/' . $_SESSION['username'] . '/' . $recordOne['filename'];
?>
    <tr>
    <td><?php echo $recordOne['filename']; ?></td>
    <td> <?php echo $recordOne['uploadedOn']; ?> </td>
    <td> <?php echo $recordOne['views']; ?> </td>
    <td> <?php echo $recordOne['averageScore']; ?> </td>
    <?php
    $nextPage = "editDeleteMedia.php?id=" . $recordOne['media_id'];
    $newTab = "viewMedia.php?id=" . $recordOne['media_id'] . "&flag=1";
    ?>
    <td><a href = "<?php echo $nextPage; ?>" target = "_blank">Edit/Delete</td>
    <td><a href = "<?php echo $newTab; ?>" target = "_blank">View/Comment/Score</td>
    </tr>
<?php } ?>
</table>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 60px; left: 10px;"
        onclick = "window.location.href = 'dashboard.php'"><span> Dashboard </span></button>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 60px; left: 1200px;"
        onclick = "window.location.href = 'uploadMedia.php'"><span> Upload </span></button>
</body>
</html>