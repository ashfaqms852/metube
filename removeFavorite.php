<?php
include_once "dbconnect.php";
include_once "functions.php";
session_start();
phpAlert('yes');
if(isset($_GET)) {
  $mid = $_GET['mid'];
  $uid = $_SESSION['userID'];
  $query = "delete from favorite where user_id = '$uid' and media_id = '$mid'";
  if(mysqli_query($connection, $query)) {
    header("Location: mediaOrganization.php");
  }
}
?>