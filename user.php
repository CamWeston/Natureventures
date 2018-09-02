<?php
//Get the users information regardless if they are in our DB or not
//Were able to do this because we have their info in the UVM DB and they are required to use webAuth to get to our site
$userNetId = htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8");
$userFullName = ldapName($userNetId);
//Break full name to get first and last for more flexibility
$breakPoint = strpos($userFullName, ':');
$userFirstName = substr($userFullName,0,$breakPoint);
$userLastName = substr($userFullName,$breakPoint+1);
//Check if this user is in our database
$query = 'SELECT pmkNetId FROM `tblUsers` WHERE pmkNetId = ?';
$data = array($userNetId);
$isUser = $thisDatabaseReader->select($query,$data,1);
//get current page so we dont have a loop of sending them to signUp.php
$currentPage = basename($_SERVER['PHP_SELF']);
//If the user is not in our Database and the Current Page is not the signup page than send them to signUp.php
if((count($isUser) == 0) && (strcmp($currentPage,'signUp.php') != 0)) {
	echo '<script type="text/javascript">';
	echo 'window.location.href="signUp.php";';
	echo '</script>';
}
//If the user IS in our database and they try to goto the signup page we will redirect them to home.php to avoid database errors
if((count($isUser) == 1) && (strcmp($currentPage,'signUp.php') == 0)) {
	echo '<script type="text/javascript">';
	echo 'window.location.href="home.php";';
	echo '</script>';
}
//IF we are not redirecting the user than lets get their information so we can use it :) 
elseif(count($isUser) == 1){
$query = 'SELECT fldEmail,fldUserLevel,fldProfilePictureLocation FROM `tblUsers` WHERE pmkNetId = ?';
$data = array($userNetId);
$userInfo = $thisDatabaseReader->select($query,$data,1);
$userEmail = $userInfo[0][0];
$userLevel = $userInfo[0][1];
$userProfilePictureLocation = $userInfo[0][2];
}




?>