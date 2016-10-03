<?php
include_once "dbconnect.php";
session_start();
if(isset($_GET)) {
  $messageID = $_GET['mid'];
  $uid = $_GET['uid'];
  $flag = $_GET['flag'];
}
$sentBy = $_SESSION['userID'];
$q = mysqli_query($connection, "delete from messages where message_id = '$messageID'");
if($q) {
  if($flag == 0) {
    $location = "Location: chatbox.php?uid=" . $uid;
  }
  else {
    $location = "Location: fullchat.php?sentBy=" . $sentBy . "&sentTo=" . $uid;
  }
  header($location);
}

?>