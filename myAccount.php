<!-- This page will allow users to view their pictures and change their profile picture. -->
<!-- Written by Cameron Weston and Ivan Spizizen -->
<?php require ('header.php') ?>
<?php 	
$errors = array();
if(isset($_POST['submit'])){
if(strlen($_FILES['profilePicture']['name']) > 0){
$target_dir = "uploads/profilePictures/";
$uploadError = false;
$counter = 1;
$fileName = $_FILES['profilePicture']['name'];
$imageFileType = pathinfo($fileName,PATHINFO_EXTENSION);
$ext = ".".strtolower($imageFileType);
$target_file = $target_dir .'profilePicture'.$ext;
if($ext != ".jpg" && $ext != ".jpeg"){
       $uploadError = true; 
       $errormsg = $fileName."-User uploaded invalid image type: ".$ext." JPG or JPEG is only acceptable file type<br>";
       array_push($errors, $errormsg);
} 
// Check if file already exists
while (file_exists($target_file)) {
    $target_file = $target_dir .'profilePicture'.$counter.$ext;
    $counter = $counter + 1;
}
if($uploadError == false){
   $tmp_name = $_FILES['profilePicture']['tmp_name'];
   if(move_uploaded_file($tmp_name, $target_file)){
    $uploadError = false;
   }
   else{
    $errormsg = "move_uploaded_file was unsuccessfull";
    array_push($errors, $errormsg);
    $uploadError = true;
   }
  } 
}
else{
	$errormsg = "You did not choose a file to be uploaded";
    array_push($errors, $errormsg);
	$uploadError = true;
}
if($uploadError == false){
	$query = 'UPDATE tblUsers SET fldProfilePictureLocation = ? WHERE pmkNetId = ?';
	$data = array($target_file,$userNetId);
	$updateUsersProfilePicture = $thisDatabaseWriter->update($query,$data,1);
	echo '<script type="text/javascript">';
	echo 'window.location.href="myAccount.php";';
	echo '</script>';
}
}














					 ?>





    <div class = 'myProfilePicture'>
	<h2 class = 'myProfilePictureImg'> My Profile Picture </h2>

	<?php
	echo '<img class ="myProfilePictureImg2" src="'.$userProfilePictureLocation.'">';
	?>
	<form action='myAccount.php' method = 'POST' enctype="multipart/form-data">
	<input name = "profilePicture" id='updateProfilePic' type="file" accept="image/jpeg,image/gif,image/jpg" multiple />
	<input name = 'submit' type = 'submit' value="Update Profile Picture">
	</form>
	</div>
    <div class = 'myInfo'>
    <h2 class = 'myInfoHeader'> My Info </h2>

		<ul class = 'myInfoUL'>
			<li class = 'myInfoLI'> NetID: <?php echo $userNetId ?> </li>
			<li class = 'myInfoLI'> First Name: <?php echo $userFirstName ?> </li>
			<li class = 'myInfoLI'> Last Name: <?php echo $userLastName ?> </li>
			<li class = 'myInfoLI'> UVM Email: <?php echo $userEmail ?> </li>
	</ul>
	</div>
	<div class ='myPictures'>
	<h2 class = 'myInfoHeader'> My Pictures </h2>
	<?php
	$query = "SELECT fldPictureLocation FROM tblPictures WHERE fnkNetId = ?";
	$data = array($userNetId);

	$usersPictures = $thisDatabaseReader -> select($query,$data,1);
	echo "<ul class='myPictures' id='userPictures'>";
	foreach ($usersPictures as $picture) {

	echo "<li class = 'myPictures2'><img class='myPictures' id='userPicture' src ='".$picture[0]."'></li>";
	}
	echo "</ul>";
	?>
	</div>	




<?php require ('footer.php') ?>