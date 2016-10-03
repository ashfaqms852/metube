<?php
session_start();
include_once "dbconnect.php";
$name = $_SESSION['name'];
$uid = $_SESSION['userID'];
if(isset($_GET['unjoin_id'])) {
  $unjoin_id = $_GET['unjoin_id'];
  mysqli_query($connection, "delete from group_members where group_id = '$unjoin_id' and user_id = '$uid'");
}
elseif(isset($_GET['join_id'])) {
  $join_id = $_GET['join_id'];
  mysqli_query($connection, "insert into group_members (group_id, user_id)
                             values ('$join_id', '$uid')");
}
$queryOne = mysqli_query($connection, "select * from group_members where user_id = '$uid'");
?>
<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" type = "text/css" href = "css/basic.css">
</head>

<body background = "images/back.jpg">
<h2 class = "white" style = "position: absolute; left: 1150px; top: 2px"><?php echo $name; ?></h2>
<h1 class = "white" style = "position: absolute; left: 575px; top: 113px;"> My Groups </h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1300px;"></a>
<form action = "searchGroup.php" method = "post">
<input type="text" name="searchGroup" class="search" autocomplete="off" spellcheck="false"
       style = "position: absolute; left: 1080px; top: 72px;" placeholder="Search for groups">
<button class = "fancy_button" name = 'groups' style = "width: 30px; height: 30px; padding: 7px;
        position: absolute; left: 1310px; top: 69px;">
  <img src = "images/search.png" height = 18px; width = 18px;>
</button>
</form>
<table style = "position: absolute; top: 180px; left: 0px;">
  <tr>
    <th>Group Name</th>
    <th>Description</th>
    <th>View</th>
    <th>Total Members</th>
    <th>Unjoin</th>
    <th>Created By</th>
  </tr>
  <?php
  while($recordOne = mysqli_fetch_assoc($queryOne)) {
    $g_id = $recordOne['group_id'];
    $queryTwo = mysqli_query($connection, "select * from group_details where group_id = '$g_id'");
    $recordTwo = mysqli_fetch_assoc($queryTwo);
    $queryThree = mysqli_query($connection, "select * from group_members where group_id = '$g_id'");
    $totalMembers = mysqli_num_rows($queryThree);
    $gowner = $recordTwo['user_id'];
    $queryFour = mysqli_query($connection, "select * from signup_details where signup_id = '$gowner'");
    $recordFour = mysqli_fetch_assoc($queryFour);
    $ownerName = $recordFour['firstname'] . " " . $recordFour['lastname'];
    $urlOne = "groups.php?unjoin_id=" . $g_id;
    $urlTwo = "viewGroup.php?g_id=" . $g_id;
    ?>
    <tr>
    <td><?php echo $recordTwo['name']; ?></td>
    <td><?php echo $recordTwo['description']; ?></td> 
    <td><a href = "<?php echo $urlTwo; ?>">View</a></td>
    <td><?php echo $totalMembers; ?></td>
    <td><a href = "<?php echo $urlOne; ?>">Unjoin</a></td>
    <td><?php echo $ownerName; ?></td>
    </tr>
  <?php  
  }
  ?>
</table>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 120px; left: 10px;"
        onclick = "window.location.href = 'dashboard.php'"><span> Dashboard </span></button>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 120px; left: 1190px;"
        onclick = "window.location.href = 'createGroup.php'"><span> Create Group </span></button>
</body>
</html>