<?php
session_start();
include_once "dbconnect.php";
$name = $_SESSION['name'];
$u_id = $_SESSION['userID'];
if(isset($_GET)) {
  $m_org_id = $_GET['m_org_id'];
  $contactID = $_GET['contact_id'];
}
$queryOne = mysqli_query($connection, "select * from media_organization where media_org_id = '$m_org_id' and isChannel = 'yes'");
$recordOne = mysqli_fetch_assoc($queryOne);
$channelName = $recordOne['name'];
$queryTwo = mysqli_query($connection, "select * from channel where channel_id = '$m_org_id' and user_id = '$contactID'
                         and name = '$channelName'");
$urlOne = "channels.php?contact_id=" . $contactID;
?>
<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" type = "text/css" href = "css/basic.css">
</head>

<body background = "images/back.jpg">
<h2 class = "white" style = "position: absolute; left: 1120px; top: 2px"><?php echo $name; ?></h2>
<h1 class = "white" style = "position: absolute; left: 525px; top: 53px;">
    <?php echo $recordOne['name']; ?>&nbsp;Channel</h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1275px;"></a>

<table style = "position: absolute; top: 120px; left: 0px;">
  <tr>
    <th>Title</th>
    <th>Description</th>
    <th>Views</th>
    <th>Rating</th>
    <th>View/Comment/Score</th>
  </tr>
    <?php
    while($recordTwo = mysqli_fetch_assoc($queryTwo)) {
      $mid = $recordTwo['media_id'];
      $urlTwo = "viewMedia.php?id=" . $mid . "&flag=0";
      $queryThree = mysqli_query($connection, "select * from media where media_id = '$mid'");
      $recordThree = mysqli_fetch_assoc($queryThree); ?>
      <tr>
      <td><?php echo $recordThree['title']; ?></td>
      <td><?php echo $recordThree['description']; ?></td>
      <td><?php echo $recordThree['views']; ?></td>
      <td><?php echo $recordThree['averageScore']; ?></td>
      <td><a href = "<?php echo $urlTwo; ?>" target = "_blank">View/Comment/Score</td>
      </tr>
    <?php
    }
    ?>
</table>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 62px; left: 20px;"
        onclick = "window.location.href = 'dashboard.php'"><span> Dashboard </span></button>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 62px; left: 1200px;"
        onclick = "window.location.href = '<?php echo $urlOne; ?>'"><span> Channels </span></button>
</body>
</html>