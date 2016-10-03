<?php
include_once "dbconnect.php";

if(isset($_GET)) {
  $mediaID = $_GET['id'];
  $flag = $_GET['flag'];
  $commID = $_GET['comm_id'];
}

$q = mysqli_query($connection, "delete from comment where comment_id = '$commID'");
if($q) {
  $location = "Location: viewMedia.php?id=" . $mediaID . "&flag=" . $flag;
  header($location);
}

?>