<?php
session_start();
$name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" type = "text/css" href = "css/basic.css">
</head>

<body background = "images/back.jpg">
<h2 class = "white" style = "position: absolute; left: 1150px; top: 2px"><?php echo $name; ?></h2>
<h1 class = "white" style = "position: absolute; left: 580px; top: 68px;"> My Contacts </h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1300px;"></a>
<form action = "searchPeople.php" method = "post">
<input type="text" name="searchPeople" class="search" autocomplete="off" spellcheck="false"
       style = "position: absolute; left: 1050px; top: 83px;" placeholder="Search for people">

<button class = "fancy_button" name = 'people' style = "width: 30px; height: 30px; padding: 7px;
        position: absolute; left: 1280px; top: 79px;">
  <img src = "images/search.png" height = 18px; width = 18px;> </button>
</form>
<table style = "position: absolute; top: 130px; left: 0px;">
  <tr>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Friend</th>
    <th>Blocked</th>
    <th>View Channels</th>
    <th>Edit/Delete</th>
    <th>Message</th>
  </tr>
<?php
include_once "dbconnect.php";
$userID = $_SESSION['userID'];
$queryOne = mysqli_query($connection, "select * from contacts where contactOf = '$userID'");
while($recordOne = mysqli_fetch_assoc($queryOne)) {
  $signupID = $recordOne['userID'];
  $queryTwo = mysqli_query($connection, "select * from signup_details where signup_id = '$signupID'");
  $recordTwo = mysqli_fetch_assoc($queryTwo);
  $queryThree = mysqli_query($connection, "select * from block where user_id = '$signupID' and blockedBy = '$userID'");
  $recordThree = mysqli_fetch_assoc($queryThree);
  $blockFlag = (count($recordThree) > 0) ? 1 : 0;
  ?>
  <tr>
    <td><?php echo $recordTwo['firstname']; ?></td>
    <td><?php echo $recordTwo['lastname']; ?></td>
    <td><?php echo $recordOne['isFriend']; ?></td>
    <?php
    $urlOne = "addEditDeleteContact.php?uid=" . $signupID;
    $urlTwo = "chatbox.php?uid=" . $signupID;
    $urlThree = "channels.php?contact_id=" . $signupID;
    if($blockFlag == 1) {
    ?>
    <td>yes</td>
    <?php }
    else { ?>
    <td>no</td>
    <?php } ?>
    <td><a href = "<?php echo $urlThree; ?>">View Channels</a></td>
    <td><a href = "<?php echo $urlOne; ?>">Edit/Delete</a></td>
    <td><a href = "<?php echo $urlTwo; ?>">Message</a></td>
  </tr>
<?php } ?>
</table>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 70px; left: 20px;"
        onclick = "window.location.href = 'dashboard.php'"><span> Dashboard </span></button>
</body>
</html>