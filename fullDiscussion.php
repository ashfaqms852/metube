<?php
include_once "dbconnect.php";
include_once "functions.php";
session_start();
$name = $_SESSION['name'];
$u_id = $_SESSION['userID'];
if(isset($_GET)) {
  $g_id = $_GET['g_id'];
}
$queryThree = mysqli_query($connection, "select * from group_details where group_id = '$g_id'");
$recordThree = mysqli_fetch_assoc($queryThree);
$urlOne = "viewGroup.php?g_id=" . $g_id;
?>
<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" type = "text/css" href = "css/basic.css">
</head>

<body background = "images/back.jpg">
<h2 class = "white" style = "position: absolute; left: 1150px; top: 2px"><?php echo $name; ?></h2>
<h1 class = "white" style = "position: absolute; left: 10px; top: 0px;">Discussion in group:
                             &nbsp;<?php echo $recordThree['name']; ?></h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1300px;"></a>
<h1 class = "white" style = "position: absolute; left: 250px; top: 130px;">Discussion about:
                             &nbsp;<?php echo $recordThree['description']; ?></h1>
<table style = "position: absolute; top: 200px; left: 250px; width: 800px;">
  <tr>
  <th>Sent By</th>
  <th>Message</th>
  <th>Your Message</th>
  <th>Delete</th>
  </tr>
<?php
$queryOne = mysqli_query($connection, "select * from group_discussion where group_id = '$g_id'");
while($recordOne = mysqli_fetch_assoc($queryOne)) {
  $urlTwo = "deleteGroupMessage.php?message_id=" . $recordOne['message_id'] . "&g_id=" . $g_id; 
  if($recordOne['user_id'] == $u_id) { ?>
    <tr>
    <td></td>
    <td></td>
    <td><?php echo $recordOne['message']; ?></td>
    <td><button onclick = "window.location.href = '<?php echo $urlTwo; ?>'">Delete</button></td>
    </tr>
  <?php
  }
  else {
    $sender = $recordOne['user_id'];
    $queryTwo = mysqli_query($connection, "select * from signup_details where signup_id = '$sender'");
    $recordTwo = mysqli_fetch_assoc($queryTwo);
    $senderName = $recordTwo['firstname'] . " " . $recordTwo['lastname'];
  ?>
    <tr>
    <td><?php echo $senderName; ?></td>
    <td><?php echo $recordOne['message']; ?></td>
    <td></td>
    <td><button onclick = "window.location.href = ''" disabled = "disabled">Delete</button></td>
    </tr>
  <?php  
  }
}
?>
</table>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 70px; left: 10px;"
        onclick = "window.location.href = 'groups.php'"><span> Groups </span></button>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 70px; left: 180px;"
        onclick = "window.location.href = '<?php echo $urlOne; ?>'"><span> Message </span></button>
</body>
</html>