<?php
include_once "dbconnect.php";
session_start();
$name = $_SESSION['name'];
if(isset($_GET)) {
  $sentBy = $_GET['sentBy'];
  $sentTo = $_GET['sentTo'];
}

$queryOne = mysqli_query($connection, "select * from messages where (sentBy = '$sentBy' or sentBy = '$sentTo')
                                       and (sentTo = '$sentBy' or sentTo = '$sentTo')");
$queryTwo = mysqli_query($connection, "select * from signup_details where signup_id = '$sentTo'");
$recordTwo = mysqli_fetch_assoc($queryTwo);
$receiver = " " . $recordTwo['firstname'] . " " . $recordTwo['lastname'];
$urlOne = "chatbox.php?uid=" . $sentTo;
?>

<!DOCTYPE html>
<html>
<head>
<link rel = "stylesheet" type = "text/css" href = "css/basic.css">
</head>

<body background = "images/back.jpg">
<h2 class = "white" style = "position: absolute; left: 1150px; top: 2px"><?php echo $name; ?></h2>
<h1 class = "white" style = "position: absolute; left: 10px; top: 0px;">Chat with:<?php echo $receiver; ?></h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1300px;"></a>
<table style = "position: absolute; top: 160px; left: 250px; width: 800px;">
  <tr>
  <th><?php echo $receiver; ?></th>
  <th>You</th>
  <th>Delete</th>
  </tr>
  <?php
  while($recordOne = mysqli_fetch_assoc($queryOne)) {
    $urlTwo = "deleteMessage.php?uid=" . $sentTo . "&mid=" . $recordOne['message_id'] . "&flag=1";
    if($recordOne['sentBy'] == $sentBy) { ?>
      <tr>
        <td></td>
        <td><?php echo $recordOne['message']; ?></td>
	    <td><button onclick = "window.location.href = '<?php echo $urlTwo ?>'">Delete</button></td>
      </tr>  
    <?php
    }
    else { ?>
      <tr>
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
        onclick = "window.location.href = 'dashboard.php'"><span> Dashboard </span></button>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 70px; left: 180px;"
        onclick = "window.location.href = '<?php echo $urlOne; ?>'"><span> Message </span></button>
</body>
</html>