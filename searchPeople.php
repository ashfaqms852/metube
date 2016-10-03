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
<h2 class = "white" style = "position: absolute; left: 1120px; top: 2px"><?php echo $name; ?></h2>
<h1 class = "white" style = "position: absolute; left: 575px; top: 53px;"> Search Results </h1>
<a href = "logout.php"><img src = "images/logout.png" height = "45" width = "45"
                            style = "position: absolute; top: 10px; left: 1275px;"></a>
<form action = "searchPeople.php" method = "post">
<input type="text" name="searchPeople" class="search" autocomplete="off" spellcheck="false"
       style = "position: absolute; left: 1050px; top: 73px;" placeholder="Search for people">
<button class = "fancy_button" name = 'people' style = "width: 30px; height: 30px; padding: 7px;
        position: absolute; left: 1280px; top: 70px;">
  <img src = "images/search.png" height = 18px; width = 18px;>
</button>
</form>
<table style = "position: absolute; top: 120px; left: 0px;">

<?php
  include_once "dbconnect.php";
  if(isset($_POST['people'])) {
    $keyword = $_POST['searchPeople'];
    
    $queryOne = mysqli_query($connection, "select * from signup_details where firstname like '%" . $keyword .
                             "%' or lastname like '%" . $keyword . "%' or email like '%" . $keyword .
                             "%' or username like '%" . $keyword . "%'"); ?>
  <tr>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Username</th>
    <th>E-mail</th>
    <th>Add/Edit/Delete as Contact</th>
    <th>Channels</th>
    <th>Message</th>
  </tr>     
  <?php
    while($recordOne = mysqli_fetch_assoc($queryOne)) {
      if($recordOne['signup_id'] != $_SESSION['userID']) { ?>
        <tr>
        <td><?php echo $recordOne['firstname']; ?></td>
        <td><?php echo $recordOne['lastname']; ?></td>
        <td><?php echo $recordOne['username']; ?></td>
        <td><?php echo $recordOne['email']; ?></td>
        <?php
          $nextPage = "addEditDeleteContact.php?uid=" . $recordOne['signup_id'];
          $urlOne = "chatbox.php?uid=" . $recordOne['signup_id'];
          $urlTwo = "channels.php?contact_id=" . $recordOne['signup_id'];
        ?>
        <td><a href = "<?php echo $nextPage; ?>">Add/Edit/Delete</td>
        <td><a href = "<?php echo $urlTwo; ?>">View</td>
        <td><a href = "<?php echo $urlOne; ?>">Message</a>
        </tr>
<?php }
    }
  } ?>
</table>
<button type = "button" class = "fancy_button" style = "position: absolute; top: 62px; left: 20px;"
        onclick = "window.location.href = 'dashboard.php'"><span> Dashboard </span></button>
</body>
</html>