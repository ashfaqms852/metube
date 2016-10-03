<?php
session_start();
include_once "dbconnect.php";
$name = $_SESSION['name'];
$uid = $_SESSION['userID'];
?>
<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" type = "text/css" href = "css/basic.css">
</head>

<body background = "images/back.jpg">
<h2 class = "white" style = "position: absolute; left: 1150px; top: 2px"><?php echo $name; ?></h2>
<h1 class = "white" style = "position: absolute; left: 575px; top: 113px;"> Search Results </h1>
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
    <th>Join/Unjoin</th>
    <th>Created By</th>
  </tr>
<?php
if(isset($_POST['groups'])) {
  $keyword = $_POST['searchGroup'];
  $queryOne = mysqli_query($connection, "select * from group_details where name like '%"
                                         . $keyword . "%' or description like '%" . $keyword . "%'");
  while($recordOne = mysqli_fetch_assoc($queryOne)) {
    $g_id = $recordOne['group_id'];
    $queryTwo = mysqli_query($connection, "select * from group_members where group_id = '$g_id'");
    $totalMembers = mysqli_num_rows($queryTwo);
    $queryThree = mysqli_query($connection, "select * from group_members where group_id = '$g_id'
                                             and user_id = '$uid'");
    $recordThree = mysqli_fetch_assoc($queryThree);
    $gowner = $recordOne['user_id'];
    $queryFour = mysqli_query($connection, "select * from signup_details where signup_id = '$gowner'");
    $recordFour = mysqli_fetch_assoc($queryFour);
    $ownerName = $recordFour['firstname'] . " " . $recordFour['lastname']; ?>
    <td><?php echo $recordOne['name']; ?></td>
    <td><?php echo $recordOne['description']; ?></td>
    <td><a href = "">View</a></td>
    <td><?php echo $totalMembers; ?></td>
    <?php
    if(count($recordThree) > 0) {
      $urlOne = "groups.php?unjoin_id=" . $g_id;
    ?>
      <td><a href = "<?php echo $urlOne; ?>">Unjoin</a></td>
    <?php
    }
    else {
      $urlOne = "groups.php?join_id=" . $g_id;
    ?>
      <td><a href = "<?php echo $urlOne; ?>">Join</a></td>
    <?php
    } ?>
    <td><?php echo $ownerName; ?></td>
  <?php
  }
}
?>
</table>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 120px; left: 10px;"
        onclick = "window.location.href = 'groups.php'"><span> Groups </span></button>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 120px; left: 1190px;"
        onclick = "window.location.href = 'createGroup.php'"><span> Create Group </span></button>
</body>
</html>