<?php
include_once "dbconnect.php";

if(isset($_GET['message_id'])) {
  $m_id = $_GET['message_id'];
  $g_id = $_GET['g_id'];
}
mysqli_query($connection, "delete from group_discussion where message_id = '$m_id'");
$urlOne = "Location: viewGroup.php?g_id=" . $g_id;
header($urlOne);
?>