<?php
include_once "dbconnect.php";
include_once "functions.php";
session_start();
$name = $_SESSION['name'];
if(isset($_GET)) {
  $sentTo = $_GET['uid'];
}
$sentBy = $_SESSION['userID'];
$urlOne = "chatbox.php?uid=" . $sentTo;
$urlTwo = "fullchat.php?sentBy=" . $sentBy . "&sentTo=" . $sentTo;

if(isset($_POST['postMessage'])) {
  $message = $_POST['message'];
  mysqli_query($connection, "insert into messages (sentBy, sentTo, message) values
                           ('$sentBy', '$sentTo', '$message')");
}

$queryTwo = mysqli_query($connection, "select * from (select * from messages where (sentBy = '$sentBy' or sentBy = '$sentTo')
                                       and (sentTo = '$sentBy' or sentTo = '$sentTo') order by messageTime desc limit 9)
                                       sub order by messageTime asc");

$queryThree = mysqli_query($connection, "select * from signup_details where signup_id = '$sentTo'");
$recordThree = mysqli_fetch_assoc($queryThree);
$receiver = " " . $recordThree['firstname'] . " " . $recordThree['lastname'];
?>
<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" type = "text/css" href = "css/basic.css">
</head>

<body background = "images/back.jpg">
<h2 class = "white" style = "position: absolute; left: 1150px; top: 2px"><?php echo $name; ?></h2>
<h1 class = "white" style = "position: absolute; left: 10px; top: 0px;">Chat with: <?php echo $receiver; ?></h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1300px;"></a>
<form action = "<?php echo $urlOne; ?>" style = "position: absolute; left : 10px; top: 65px;" method = "post">
<textarea name = "message" rows = "4" cols = "60" placeholder = "Type your message here">
</textarea><br/><br/>
<button class = "fancy_button" name = "postMessage" type = "submit" value = "Post Message"
        style = "position: absolute; left: -5px; top: 80px;"><span>Send</span>
</button>
</form>
<table style = "position: absolute; top: 200px; left: 250px; width: 800px;">
  <tr>
  <th><?php echo $receiver; ?></th>
  <th>You</th>
  <th>Delete</th>
  </tr>
  <?php
  while($recordTwo = mysqli_fetch_assoc($queryTwo)) {
    $urlThree = "deleteMessage.php?uid=" . $sentTo . "&mid=" . $recordTwo['message_id'] . "&flag=0";
    if($recordTwo['sentBy'] == $sentBy) { ?>
      <tr>
        <td></td>
        <td><?php echo $recordTwo['message']; ?></td>
	    <td><button onclick = "window.location.href = '<?php echo $urlThree; ?>'">Delete</button></td>
      </tr>  
    <?php
    }
    else { ?>
      <tr>
        <td><?php echo $recordTwo['message']; ?></td>
        <td></td>
	    <td><button onclick = "window.location.href = ''" disabled = "disabled">Delete</button></td>
      </tr>
    <?php
    }
  }
  ?>
</table>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 610px; left: 450px;"
        onclick = "window.location.href = 'dashboard.php'"><span> Dashboard </span></button>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 610px; left: 650px;"
        onclick = "window.location.href = '<?php echo $urlTwo; ?>'"><span> Full Chat </span></button>
</body>
</html>