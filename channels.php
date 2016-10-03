<?php
session_start();
include_once "dbconnect.php";
$name = $_SESSION['name'];
$u_id = $_SESSION['userID'];
if(isset($_GET)) {
  $contactID = $_GET['contact_id'];
}
$queryOne = mysqli_query($connection, "select * from signup_details where signup_id = '$contactID'");
$recordOne = mysqli_fetch_assoc($queryOne);
$contactName = $recordOne['firstname'] . " " . $recordOne['lastname'] . "'s ";
$queryTwo = mysqli_query($connection, "select * from media_organization where user_id = '$contactID' and isChannel = 'yes'");
?>
<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" type = "text/css" href = "css/basic.css">
</head>

<body background = "images/back.jpg">
<h2 class = "white" style = "position: absolute; left: 1120px; top: 2px"><?php echo $name; ?></h2>
<h1 class = "white" style = "position: absolute; left: 475px; top: 53px;"><?php echo $contactName; ?> Channels</h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1275px;"></a>

<table style = "position: absolute; top: 120px; left: 0px;">
  <tr>
    <th>Channel Name</th>
    <th>Subscribe/Unsubscribe</th>
    <th>View Channel</th>
    <th>Total Subscribers</th>
  </tr>
<?php
  while($recordTwo = mysqli_fetch_assoc($queryTwo)) {

    $m_org_id = $recordTwo['media_org_id'];
    $queryThree = mysqli_query($connection, "select * from subscribers where user_id = '$u_id'
                               and media_org_id = '$m_org_id'");
    $totalSubsribers = mysqli_num_rows($queryThree);
    $recordThree = mysqli_fetch_assoc($queryThree);
    $urlTwo = "viewChannel.php?m_org_id=" . $recordTwo['media_org_id'] . "&contact_id=" . $contactID;
    ?>
    <tr>
    <td><?php echo $recordTwo['name']; ?></td>
    <?php
    if(count($recordThree) > 0) {
      $urlOne = "subscribe.php?m_org_id=" . $recordTwo['media_org_id'] . "&contact_id=" . $contactID . "&flag=0";
      $subUnsub = "Unsubscribe";
    }
    else {
      $urlOne = "subscribe.php?m_org_id=" . $recordTwo['media_org_id'] . "&contact_id=" . $contactID . "&flag=1";
      $subUnsub = "Subscribe";
    }
    ?>
    <td><a href = "<?php echo $urlOne; ?>"><?php echo $subUnsub; ?></td>
    <td><a href = "<?php echo $urlTwo; ?>">View Channel</td>
    <td><?php echo $totalSubsribers; ?></td>
    </tr>
<?php  }
?>
</table>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 62px; left: 20px;"
        onclick = "window.location.href = 'dashboard.php'"><span> Dashboard </span></button>
</body>
</html>