<?php
include_once "dbconnect.php";

function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

function checkIfEmailReused($connection, $email) {
  $result = mysqli_query($connection, "select * from signup_details where email = '$email'");
  $rows = mysqli_fetch_assoc($result);
  return $rows;
}

function checkIfUsernameReused($connection, $username) {
  $result = mysqli_query($connection, "select * from signup_details where username = '$username'");
  $rows = mysqli_fetch_assoc($result);
  return $rows;
}

function verifyCredentials($connection, $username, $password) {
  $select_query = mysqli_query($connection, "select * from signup_details where username = '$username'
                               and password = '$password'");
  $record = mysqli_fetch_assoc($select_query);
  return $record;
}


function addTableRowForMediaSearch($connection, $rec) {
  $id = $rec['user_id'];
  $query = mysqli_query($connection, "select * from signup_details where signup_id = '$id'");
  $record = mysqli_fetch_assoc($query);
  $path = "/uploads/" . $record['username'] . "/" . $rec['filename'];
  //$path = "/~asherwa/uploads/" . $record['username'] . "/" . $rec['filename'];
  $newTab = "viewMedia.php?id=" . $rec['media_id'] . "&flag=0";
?>
<tr>
  <td><?php echo $rec['title']; ?></td>
  <td><?php echo $rec['description']; ?></td>
  <td><?php echo $rec['filename']; ?></td>
  <td><?php echo $rec['uploadedOn']; ?></td>
  <td><?php echo $rec['views']; ?></td>
  <td><?php echo $rec['averageScore']; ?></td>
  <td><a href = "<?php echo $path; ?>" target = "_blank">Download</td>
  <td><a href = "<?php echo $newTab; ?>" target = "_blank">View/Comment/Score</td>
</tr>
<?php	
}

function searchFunctionality($connection, $queryOne, $currUser) { ?>
	  <tr>
      <th>Title</th>
      <th>Description</th>
      <th>Filename</th>
      <th>Uploaded On</th>
      <th>Total Views</th>
      <th>Average Score</th>
      <th>Download</th>
	  <th>View/Comment/Score</th>
    </tr>
  <?php
    while($recordOne = mysqli_fetch_assoc($queryOne)) {
      $uploadedBy = $recordOne['user_id'];
	  //$queryFour = "select * from block where user_id = '$currUser' and blockedBy = '$uploadedBy'";
	  $queryFour = mysqli_query($connection, "select * from block where user_id = '$currUser'
								               and blockedBy = '$uploadedBy'");
	  $recordFour = mysqli_fetch_assoc($queryFour);
	  if(count($recordFour) > 0) {
		$blockID = $recordFour['blockedBy'];
	  }
	  else {
		$blockID = -1;
	  }
      if($recordOne['user_id'] != $currUser and $recordOne['user_id'] != $blockID) {
        if($recordOne['shareWith'] == "everybody") {
          addTableRowForMediaSearch($connection, $recordOne);
        }
        elseif($recordOne['shareWith'] == "contacts") {
          $queryTwo = mysqli_query($connection, "select * from contacts where contactOf = '$uploadedBy' and
                                   userID = '$currUser'");
          $recordTwo = mysqli_fetch_assoc($queryTwo);
          if($uploadedBy == $recordTwo['contactOf']) {
            addTableRowForMediaSearch($connection, $recordOne);
          }
        }
        elseif($recordOne['shareWith'] == "friends") {
          $queryThree = mysqli_query($connection, "select * from contacts where contactOf = '$uploadedBy' and
                                     userID = '$currUser' and isFriend = 'yes'");
          $recordThree = mysqli_fetch_assoc($queryThree);
          if($uploadedBy == $recordThree['contactOf']) {
            addTableRowForMediaSearch($connection, $recordOne);
          }        
        }
      }  
    }
}

function postScore($connection, $mediaID, $u_id, $score) {
  $q1 = mysqli_query($connection, "select * from score where media_id = '$mediaID' and
                       postedBy = '$u_id'");
  $r1 = mysqli_fetch_assoc($q1);
  if(count($r1) > 0) {
    $q2 = mysqli_query($connection, "select * from media where media_id = '$mediaID'");
    $r2 = mysqli_fetch_assoc($q2);
	$totalScore = ($r2['totalScore'] - $r1['score']) + $score;
    $averageScore = $totalScore/$r2['scoredBy'];
    mysqli_query($connection, "update media set totalScore = '$totalScore', averageScore = '$averageScore'
                   where media_id = '$mediaID'");
    mysqli_query($connection, "update score set score = '$score' where media_id = '$mediaID'
                     and postedBy = '$u_id'");
  }
  else {
    mysqli_query($connection, "insert into score (media_id, postedBy, score)
                   values ('$mediaID', '$u_id', '$score')");
    $q2 = mysqli_query($connection, "select * from media where media_id = '$mediaID'");
    $r2 = mysqli_fetch_assoc($q2);
    $totalScore = $r2['totalScore'] + $score;
    $scoredBy = $r2['scoredBy'] + 1;
    $averageScore = $totalScore/$scoredBy;
    mysqli_query($connection, "update media set scoredBy = '$scoredBy', totalScore = '$totalScore',
                   averageScore = '$averageScore' where media_id = '$mediaID'");
  }
}

function addTableRowForComments($connection, $mediaID, $u_id, $flag) {
  $q = mysqli_query($connection, "select * from comment where media_id = '$mediaID'");
  while($r = mysqli_fetch_assoc($q)) {
	$disabled = ($r['postedBy'] == $u_id) ? "" : "disabled";
	$path = "deleteComment.php?id=" . $mediaID . "&flag=" . $flag . "&comm_id=" . $r['comment_id'];
	$commentedBy = $r['postedBy'];
    $q1 = mysqli_query($connection, "select * from signup_details where signup_id = '$commentedBy'");
    $r1 = mysqli_fetch_assoc($q1);
	$name = $r1['firstname'] . " " . $r1['lastname'];
?>
	<tr>	
	  <td><?php echo $name; ?></td>
	  <td><?php echo $r['comment']; ?></td>
	  <td><?php echo $r['postTime']; ?></td>
      <td>
	  <button onclick = "window.location.href = '<?php echo $path; ?>'"
	          <?php echo $disabled; ?>>Delete</button>
	  </td>
	</tr>
<?php  }
}
?>

