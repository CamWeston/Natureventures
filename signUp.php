<!-- This page will allow UVM students to sign up as users for the site.
If the user is already in our db than they will be redirected to home.php 
by our users.php page that is found in header. -->
<!-- Written by Cameron Weston & Ivan Spizizen-->


<?php
//Require header that contains user.php that will grab our info 
require('header.php');
$errors = array();
if(isset($_POST['submit'])){
$target_dir = "uploads/profilePictures/";
$uploadError = false;
$counter = 1;
$fileName = $_FILES['profilePicture']['name'];
$imageFileType = pathinfo($fileName,PATHINFO_EXTENSION);
$ext = ".".strtolower($imageFileType);
$target_file = $target_dir .'profilePicture'.$ext;
if($ext != ".jpg" && $ext != ".jpeg"){
       $uploadError = true; 
       $errormsg = $fileName." -User uploaded invalid image type: ".$ext." JPG or JPEG is only acceptable file type<br>";
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
if($uploadError == false){
$phoneNumber = $_POST['phoneNumber'];
$instagram = $_POST['insta'];
if(strlen($instagram > 0)){
    $url = 'https://www.instagram.com/'.$instaName."/";
    $array = get_headers($url);
    $string = $array[0];
    if(strpos($string,"200"))
        {}
        else
        { 
        $errormsg = "This is not a valid instagram account (do not include the @ ~ Ex: instagram_user)";
        array_push($errors, $errormsg);
        }
      }

//if success upload users info to database
$gradYear = $_POST['gradYear'];
$userEmail = $userNetId."@uvm.edu";
$hobbies = $_POST['hobby'];
$gender = $_POST['gender'];
if(!$uploadError){
$query = "INSERT INTO tblUsers SET
           pmkNetId = ?, 
           fldFirstName = ?,
           fldLastName = ?, 
           fldEmail = ?,
           fldUserLevel = ?, 
           fldProfilePictureLocation = ?,
           fldPhoneNumber =?,
           fldInstagramName = ?,
           fldGradYear = ?,
           fldHobbies = ?,
           fldGender = ? ";
$data = array($userNetId,$userFirstName,$userLastName,$userEmail,1,$target_file,$instagram,$phoneNumber,$gradYear,$hobbies,$gender);
$insertNewUser = $thisDatabaseWriter->insert($query,$data);
if ($insertNewUser){
 echo '<script type="text/javascript">';
echo 'window.location.href="home.php";';
 echo '</script>';
}
}


}
}


//get current year for grad date form
$currentYear = intval(date("Y"));
//$userFirstName and $userLastName come from users.php
?>
<div id='signUpFormContainer'>
<fieldset id='signUpHeaderFieldset'>
<?php
echo '<h1 id="signUpHeader">Welcome to NatureVentures '.$userFirstName.' '.$userLastName.'</h1>'; 
?>

<h3 id='signUpDescription'>Looks like your a new user here at NatureVentures, lets get you signed up </h3>
</fieldset>
<form name ='signUpForm' action='signUp.php' onsubmit="return validateForm();" method = 'POST' enctype="multipart/form-data">
<div id='signUpLeftCol'>
<label id ='emailLabel'> Enter Your Student Email </label>
<input placeholder ='Enter your email' type="text" id="studentEmail" name="txtStudentEmail" value="<?php print $userNetId; ?>@uvm.edu">
<label id ='phoneLabel'> Enter Your Phone Number </label>
<input type="text" id="phoneNumber" name="phoneNumber" placeholder="Enter your phone number">
</div>
<div id='signUpRightCol'> 
<label id ='instaLabel'> Enter Your Instagram </label>
<input type="text" id="insta" name="insta" placeholder="Enter your instagram">
<label id ='gradYearLabel'> Enter Your Grad Year </label>
<div class ='gradYear'>
<select name ='gradYear' class='gradYear'>
<?php
echo '<option value ="'.$currentYear.'">'.$currentYear.'</option>';
echo '<option value ="'.($currentYear+1).'">'.($currentYear+1).'</option>';
echo '<option value ="'.($currentYear+2).'">'.($currentYear+2).'</option>';
echo '<option value ="'.($currentYear+3).'">'.($currentYear+3).'</option>';
?>
</select>
</div>
</div>
<div id='errorDiv'>
<p id='errorBlock'>
</p>
<?php
if(count($errors)>0){
  echo '<p id="phpErrors" >';
  foreach ($errors as $e) {
    echo $e.'<br>';
  }
  echo '</p>';
}
?>

</div>


            <fieldset class="checkbox">
            <legend>Check your favorite NatureVentures (check all that apply):</legend>
                <label for="chkBackpacking"><input type="checkbox"
                                       id="chkBackpacking"
                                       name="hobby[]"
                                       value="Backpacking/Hiking">Backpacking/Hiking
            </label>
                <label for="chkSki"><input type="checkbox"
                                                id="chkSki"
                                                name="hobby[]"
                                                value="Ski/Snowboard">Ski/Snowboard
            </label>

                <label for="chkBiking"><input type="checkbox"
                                                id="chkBiking"
                                                name="hobby[]"
                                                value="Biking">Biking
            </label>

                <label for="chkRunning"><input type="checkbox"
                                                id="chkRunning"
                                                name="hobby[]"
                                                value="Running">Running
            </label>

                    <label for="chkSwimming"><input type="checkbox"
                                                id="chkSwimming"
                                                name="hobby[]"
                                                value="Swimming">Swimming
            </label> 
</fieldset>
<fieldset class="radiobutton">
<legend class ="radiobutton">Choose your gender</legend>
<input name ='gender' type='radio' class = 'radiobutton' value = 'Male'>Male<br>
<input name ='gender' type='radio' class = 'radiobutton' value = 'Female'>Female<br>
<input name ='gender' type='radio' class = 'radiobutton' value = 'Other'>Other<br>
</fieldset>
<label for = "uploadProfilePic" id='uploadLabel'>
<p id="uploadText">Upload a profile Picture </p> 
</label>
<input name = "profilePicture" id='uploadProfilePic' type="file" accept="image/jpeg,image/gif,image/jpg" multiple />
<label for = "signUpSubmit" id='signUpSubmitLabel'>
<p id="signUpSubmitText">Submit</p> 
</label>
<input id ='signUpSubmit' name = 'submit' type = 'submit' >





</form>
</div>












<?php require('footer.php'); ?>