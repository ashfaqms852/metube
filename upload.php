<?php
include_once "functions.php";
include_once "dbconnect.php";

session_start();

if(isset($_POST['upload']) == "Upload") {
    
  $title = mysqli_real_escape_string($connection, $_POST['title']);
  $description = mysqli_real_escape_string($connection, $_POST['description']);
  $keywordOne = mysqli_real_escape_string($connection, $_POST['keywordOne']);
  $keywordTwo = mysqli_real_escape_string($connection, $_POST['keywordTwo']);
  $keywordThree = mysqli_real_escape_string($connection, $_POST['keywordThree']);
  $shareWith = mysqli_real_escape_string($connection, $_POST['shareWith']);
  $allowDiscussion = mysqli_real_escape_string($connection, $_POST['allowDiscussion']);
  $allowScoring = mysqli_real_escape_string($connection, $_POST['allowScoring']);
  $playlist = mysqli_real_escape_string($connection, $_POST['playlist']);
  $channel = mysqli_real_escape_string($connection, $_POST['channel']);
  
  $filename = $_FILES['file']['name'];
  $filesize = $_FILES['file']['size'];
  $filetype = $_FILES['file']['type'];
  
  if(!isset($_FILES['file']) || $_FILES['file']['error'] == UPLOAD_ERR_NO_FILE || empty($title)
    || empty($description) || empty($keywordOne) || empty($keywordTwo) || empty($keywordThree) ||
    empty($shareWith) || empty($allowDiscussion) || empty($allowScoring)) {
    phpAlert("Atleast one of the fields is empty.");
  }
  else {
    $allowedExts = array("jpg", "jpeg", "gif", "png", "mp3", "mp4", "JPG", "JPEG", "GIF", "PNG", "MP3", "MP4");
    $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    if($extension == "jpeg" || $extension == "jpg" || $extension == "png" || $extension == "gif" ||
       $extension == "JPEG" || $extension == "JPG" || $extension == "PNG" || $extension == "GIF") {
        $category = "image";
    }
    elseif($extension == "mp3" or $extension == "MP3") {
        $category = "audio";
    }
    else {
        $category = "video";
    }
    $userID = $_SESSION['userID'];
    $filename = $_FILES['file']['name'];
    $queryOne = mysqli_query($connection, "select * from media where user_id = '$userID' and filename = '$filename'");
    $recordOne = mysqli_fetch_assoc($queryOne);
    if(count($recordOne) > 0) {
      $fileInDBFlag = 1;
    }
    else {
      $fileInDBFlag = 0;
    }
  
    if(!file_exists('uploads/' . $_SESSION['username'] . '/')) {
      mkdir('uploads/' . $_SESSION['username'] . '/', 0755, true);
      chmod('uploads/' . $_SESSION['username'] . '/', 0755);
    }

    if ((($filetype == "video/mp4") || ($filetype == "audio/mp3")
       || ($filetype == "image/pjpeg") || ($filetype == "image/gif")
       || ($filetype == "image/jpeg") || ($filetype == "image/png"))
       && ($filesize < 67108864) && in_array($extension, $allowedExts)) {
  
       if ($_FILES["file"]["error"] > 0) {
         phpAlert($_FILES["file"]["error"]);
       } 
       else {
         if (file_exists("uploads/" . $_SESSION['username'] . '/' . $filename) and ($fileInDBFlag == 1)) {
           phpAlert($filename . " already exists. ");
         }
         else {
           move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/" . $_SESSION['username'] . "/" . $filename);
           chmod("uploads/" . $_SESSION['username'] . "/" . $filename, 0755);
           $queryTwo = "insert into media (user_id, title, description, filename, keywordOne, keywordTwo, keywordThree,
                        category, shareWith, allowDiscussion, allowScoring, size, views, scoredBy, totalScore, averageScore) 
                        values ('$userID', '$title', '$description', '$filename', '$keywordOne',
                        '$keywordTwo', '$keywordThree', '$category', '$shareWith', '$allowDiscussion', '$allowScoring',
                        '$filesize', 0, 0, 0, 0)";
            mysqli_query($connection, $queryTwo);
            $m_id = mysqli_insert_id($connection);
            if(!empty($playlist)) {
              $queryFour = mysqli_query($connection, "select * from media_organization where user_id = '$userID'
                                        and isPlaylist = 'yes' and name = '$playlist'");
              $recordFour = mysqli_fetch_assoc($queryFour);
              $p_id = $recordFour['media_org_id'];
              $queryThree = "insert into playlist (playlist_id, user_id, media_id, name)
              values ('$p_id','$userID', '$m_id', '$playlist')";
              mysqli_query($connection, $queryThree);
            }
            if(!empty($channel)) {
              $queryFive = mysqli_query($connection, "select * from media_organization where user_id = '$userID'
                                        and isChannel = 'yes' and name = '$channel'");
              $recordFive = mysqli_fetch_assoc($queryFive);
              $c_id = $recordFive['media_org_id'];
              $querySix = "insert into channel (channel_id, user_id, media_id, name)
              values ('$c_id','$userID', '$m_id', '$channel')";
              mysqli_query($connection, $querySix);
            }
            header('Location:myUploads.php');
         }
       }
    }
    else {
      phpAlert("Invalid file. Either file type or file size is not allowed.");
    }
  }
  echo 'Kindly press BACK button.';
}  
?>