<?php
include_once "dbconnect.php";
session_start();
$u_id = $_SESSION['userID'];
if(isset($_GET)) {
  $m_org_id = $_GET['m_org_id'];
  $contactID = $_GET['contact_id'];
  $flag = $_GET['flag'];
  $location = "Location: channels.php?contact_id=" . $contactID;
}
if($flag == 1) {
  mysqli_query($connection, "insert into subscribers (user_id, media_org_id) values ('$u_id', '$m_org_id')");
}
else {
  mysqli_query($connection, "delete from subscribers where user_id = '$u_id' and media_org_id = '$m_org_id'");
}
header($location);
?>