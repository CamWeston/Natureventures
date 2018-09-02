<!-- This page will allow users to create and view TagAlong events which are basically just meetups. -->
<!-- Written by Cameron Weston and Ivan Spizizen -->
<?php require ('header.php');
//the following html will create a event. Users enter their activity,description and date of activity
 ?>

<div class = 'createEvent' >
<div class = 'createEventBox' >
<h2 class = 'createEvent' > Create a TagAlong Event <?php  echo $userFirstName ?></h2>  
<form class = 'createEvent' enctype="multipart/form-data" method = 'post' action = 'tagAlong.php'>

<textarea name = 'createEventText' class = 'createEvent' id = 'createEventText' type='text' placeholder = 'Tell us about your event!'/></textarea>
<textarea name = 'createEventActivity' class = 'createEvent' id = 'createEventActivity' type='text' placeholder = 'What activity is your event ex:Skiing' /></textarea>
<br>
<input name = 'createEventDate' class = 'createEvent' id = 'datepicker' type ='text' placeholder='Enter the date of your event' />
<input name = 'createEventSubmit' class = 'createEvent' type ='submit'/>
</form> 
</div>
</div>
<div class = 'viewEvents' id ='eventsContainer'>
<?php
//select all flds for each event for all events order by descending ids
$query = 'SELECT * FROM tblEvents WHERE 1 ORDER BY pmkEventId DESC';
$events = $thisDatabaseReader->select($query,null,1,1);
foreach($events as $event){
$isUserGoing = false;
//select the users first name last name and profile picture to show event authors name
$query = 'SELECT fldFirstName,fldLastName,fldProfilePictureLocation FROM tblUsers WHERE pmkNetId = ?';
$data = array($event[0]);
$postAuthor = $thisDatabaseReader->select($query,$data,1);
echo "<div class ='event' id ='fullEventDiv'>";
echo "<div class ='event' id ='eventDiv'>";
echo "<div class ='event' id ='eventHead'>";
//if the user created the event they can delete it
if($userNetId == $event['fnkNetId']){
    echo "<div class = 'delete'>";
    echo "<form method ='post' action ='tagAlong.php'>";
    echo '<input type="submit" id="deletePost" name="deletePostButton" value ="Delete Event">';
    echo "<input type='hidden' name='postIdToDelete' value ='".$event['pmkEventId']."' >";
    echo "</form>";
    echo "</div>";
  }
echo "<img class='profilePicture' id ='eventAuthorProfilePicture'src ='".$postAuthor[0][2]."'>";
echo "<h3 class ='event' id ='eventAuthor'>".$postAuthor[0][0]." ".$postAuthor[0][1]."</h3>";
echo "<h3 class ='event' id ='eventActivity'>Activity: ".$event[2]."</h3>";
echo "<h3 class ='event' id ='eventDate'>Date: ".$event[1]."</h3>";
echo "</div>";
echo "<div class ='event' id ='eventBody'>";
echo "<p class ='event' id ='eventDescription'>".$event[3]."</p>";
echo "</div>";
//select all users going for the event
//the following code block will allow users to signup for an event just by the click of button
//if the users signed up we will change the button to be an undo
$query = "SELECT fldUsersGoing FROM tblEvents WHERE pmkEventId = ?";
$data = array($event[5]);
$getUsersGoing = $thisDatabaseReader->select($query,$data,1);
$usersGoing = explode(",", $getUsersGoing[0][0]);
if(in_array($userNetId, $usersGoing)){
	$isUserGoing = true;
}
echo "<div class ='event' id='eventFoot'>";
if($event[0] != $userNetId){
echo "<div class ='event' id='TagAlongDiv'>";
echo "<form action='tagAlong.php' method='POST'>";
if($isUserGoing){
echo "<input type='hidden' name='userToRemove' value ='".$userNetId.",'>";
echo "<input type='hidden' name='eventId' value ='".$event[5]."'>";
echo "<input type='submit' name='removeUserFromTagAlong' value='Nevermind bruh I cant go!'>";
}
else{
echo "<input type='hidden' name='userToAdd' value ='".$userNetId."'>";
echo "<input type='hidden' name='eventId' value ='".$event[5]."'>";
echo "<input type='submit' name='tagAlongButton' value='TagAlong!'>";
}
echo "</form>";
echo "</div>";
}
echo "<div class ='event' id='usersGoingDiv'>";
echo "<h4 class ='event' id ='usersGoingHeader'>Adventurers currently signed up:</h4>";
echo "<ul class ='event' id='usersGoingList'>";
//get the names of each user going by using their netid
foreach ($usersGoing as $netId) {
	if(strlen($netId) > 1){
	$query = 'SELECT fldFirstName,fldLastName FROM tblUsers WHERE pmkNetId = ?';
	$data = array($netId);
	$personGoing = $thisDatabaseReader->select($query,$data,1);
	echo "<li class ='event' id='userGoingLI'>".$personGoing[0][0]." ".$personGoing[0][1]."</li>";
}
}
echo "</ul>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "<div class ='eventComments' id = 'eventCommentContainer'>";
//comments are postable by anyone but only deletable by the author
$query = 'SELECT fnkNetId,fldComment,pmkCommentId FROM tblEventsComments WHERE fnkEventId = ?';
$data = array($event[5]);
$EventComments = $thisDatabaseReader->select($query,$data,1);
echo "Comments";  
  foreach ($EventComments as $comment) {
    $query = "SELECT fldFirstName,fldLastName,fldProfilePictureLocation FROM tblUsers WHERE pmkNetId = ?";
    $data = array($comment['fnkNetId']);
    $commentorData = $thisDatabaseReader->select($query,$data,1);
    $author = $commentorData[0][0]." ".$commentorData[0][1];
    echo "<div class='eventComments' id='eventComment'>";
    if($userNetId == $comment['fnkNetId']){
    echo "<div class = 'delete'>";
    echo "<form method ='post' action ='tagAlong.php'>";
    echo '<input type="submit" id="deleteComment" name="deleteCommentButton" value ="Delete Comment">';
    echo "<input type='hidden' name='commentIdToDelete' value ='".$comment['pmkCommentId']."' >";
    echo "</form>";
    echo "</div>";
    }

    echo "<img class ='profilePicture2' src ='".$commentorData[0][2]."'>";
    echo "<h4 class='eventComments' id='commentAuthor'>".$author."</h4>";

    echo "<p class='eventComments' id='commentText'>".$comment['fldComment']."</p>";
    echo "</div>";
  }
  echo "<div class = 'eventComments' id='createComment'>";
  echo "<form method ='post' action ='tagAlong.php'>";
  echo "<input name ='commentTextInput' type='text' class='eventComments' id='commentTextInput' placeholder = 'leave a comment'>";
  echo "<input class ='eventComments' name ='commentSubmit' type='submit' >";
  echo "<input type='hidden' name='eventId' value ='".$event['pmkEventId']."' >";
  echo "</form>";
  echo "</div>";
echo "</div>";
echo "</div>";
}
?>
</div>




<?php
//if comment submit
if(isset($_POST['commentSubmit'])){
  if(strlen($_POST['commentTextInput']) > 0){
    $eventId = $_POST['eventId'];
    $query = "INSERT INTO tblEventsComments SET
        fnkNetId = ?,
          fnkEventId = ?,
            fldComment = ?";
       $data = array($userNetId,$eventId,$_POST['commentTextInput']);
       $insertNewComment = $thisDatabaseWriter ->insert($query,$data);
       if($insertNewComment){
        echo '<script type="text/javascript">';
echo 'window.location.href="tagAlong.php";';
echo '</script>';
       }
  }
}





if(isset($_POST['tagAlongButton'])){
  //if tagalong submit
$query = "SELECT fldUsersGoing FROM tblEvents WHERE pmkEventId = ?";
$data = array($_POST['eventId']);
$getUsersGoing = $thisDatabaseReader->select($query,$data,1);
$usersGoing = $getUsersGoing[0][0];
$usersGoing .= $_POST['userToAdd'].",";
$query = 'UPDATE tblEvents SET fldUsersGoing = ? WHERE pmkEventId = ?';
$data = array($usersGoing,$_POST['eventId']);
$updateUsersGoing = $thisDatabaseWriter->update($query,$data,1);
if($updateUsersGoing){
       echo '<script type="text/javascript">';
	   echo 'window.location.href="tagAlong.php";';
	   echo '</script>';
}   
else{
 	   echo '<script type="text/javascript">';
 	   echo 'alert("There was an error adding you to the event please try again. If this problem persists please contact ckweston@uvm.edu")';
	   echo 'window.location.href="tagAlong.php";';
	   echo '</script>';
}         
}
if(isset($_POST['removeUserFromTagAlong'])){
  //if remove user from tag along submit
$query = "SELECT fldUsersGoing FROM tblEvents WHERE pmkEventId = ?";
$data = array($_POST['eventId']);
$getUsersGoing = $thisDatabaseReader->select($query,$data,1);
$usersGoing = $getUsersGoing[0][0];
$userToRemove = $_POST['userToRemove'];
$usersGoing = str_ireplace($userToRemove, '', $usersGoing);
$query = 'UPDATE tblEvents SET fldUsersGoing = ? WHERE pmkEventId = ?';
$data = array($usersGoing,$_POST['eventId']);
$updateUsersGoing = $thisDatabaseWriter->update($query,$data,1);
if($updateUsersGoing){
       echo '<script type="text/javascript">';
	   echo 'window.location.href="tagAlong.php";';
	   echo '</script>';
}   
else{
 	   echo '<script type="text/javascript">';
 	   echo 'alert("There was an error removing you from the event please try again. If this problem persists please contact ckweston@uvm.edu")';
	   echo 'window.location.href="tagAlong.php";';
	   echo '</script>';
}         
}

if(isset($_POST['createEventSubmit'])){
  //if create an event submit
$eventText = $_POST['createEventText'];
$eventActivity = $_POST['createEventActivity'];
$eventDate = $_POST['createEventDate'];
$query = 'INSERT INTO tblEvents SET
							    fnkNetId = ?,
							      fldEventDate = ?,
							        fldActivity = ?,
							         fldDescription = ?';
$data = array($userNetId,$eventDate,$eventActivity,$eventText);
$insertNewEvent = $thisDatabaseWriter->insert($query,$data);
if($insertNewEvent){
       echo '<script type="text/javascript">';
	   echo 'window.location.href="tagAlong.php";';
	   echo '</script>';
}   
else{
 	   echo '<script type="text/javascript">';
 	   echo 'alert("There was an error uploading your event please try again. If this problem persists please contact ckweston@uvm.edu")';
	   echo 'window.location.href="tagAlong.php";';
	   echo '</script>';
}         
              
}
if(isset($_POST['deletePostButton'])){
  $postIdToDelete = $_POST['postIdToDelete'];
  $query = 'DELETE FROM tblEvents WHERE pmkEventId = ?';
  $data = array($postIdToDelete);
  $deletePost = $thisDatabaseWriter->delete($query,$data,1);
  if($deletePost){
  $query = 'DELETE FROM tblEventsComments WHERE fnkEventId = ?';
  $deleteComments = $thisDatabaseWriter->delete($query,$data,1);
  }
  if($deleteComments){
        echo '<script type="text/javascript">';
        echo 'window.location.href="tagAlong.php";';
        echo '</script>';
       }
}
if(isset($_POST['deleteCommentButton'])){
  $commentIdToDelete = $_POST['commentIdToDelete'];
  $query = 'DELETE FROM tblEventsComments WHERE pmkCommentId = ?';
  $data = array($commentIdToDelete);

  $deleteComment = $thisDatabaseWriter->delete($query,$data,1);
  if($deleteComment){
        echo '<script type="text/javascript">';
        echo 'window.location.href="tagAlong.php";';
        echo '</script>';
       }
}

?>
<?php require ('footer.php'); ?> 