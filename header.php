<?php
// Start the session if we want to use session variables
session_start();

?> 


<!DOCTYPE html>
<html lang="en">
    <head>
        <title>NatureVentures: Lets Explore Together</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Cameron Weston, Ivan Spizizen">
        <meta name="description" content="UVM CLUB NatureVentures Website">
        <link rel="stylesheet" type="text/css" href="assets/style/style.css?<?php echo time(); ?>" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="/resources/demos/style.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


        <script>
         $( function() {
         $( "#datepicker" ).datepicker({dateFormat:'yy-mm-dd', minDate: 0});
                        } );
                function startTime() {
                    var today = new Date();
                     var h = today.getHours();
                    var m = today.getMinutes();
                    var s = today.getSeconds();
                    m = checkTime(m);
                    s = checkTime(s);
                    
                    if(h>12 && h != 24){
                        h = h-12;
                        document.getElementById('time').innerHTML =
                    "The current time is: " + h + ":" + m + ":" + s + " PM";
                    }
                    else if(h == 24){
                         h = h-12;
                        document.getElementById('time').innerHTML =
                    "The current time is: " + h + ":" + m + ":" + s + " AM";
                    }
                     else if(h<12 && h != 12){
                        document.getElementById('time').innerHTML =
                    "The current time is: " + h + ":" + m + ":" + s + " AM";
                    }
                    else{
                        document.getElementById('time').innerHTML =
                    "The current time is: " + h + ":" + m + ":" + s + " PM";
                    }
                    
                    var t = setTimeout(startTime, 500);
                }



                function checkTime(i) {
                    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
                    return i;
                }





                function msg(str){
                    alert(str);
                }










                function validateForm() {
                var phperrors = document.getElementById("phpErrors");
                if(phperrors != null){
                  phperrors.style.display = "none";
                }
                var email = document.forms["signUpForm"]["txtStudentEmail"].value;
                var insta = document.forms["signUpForm"]["insta"].value;
                var phone = document.forms["signUpForm"]["phoneNumber"].value;
                var phonelength = phone.length;
                var instalength = insta.length;
                var atpos = email.indexOf("@");
                var dotpos = email.lastIndexOf(".");
                var error = document.getElementById("errorBlock");
                var errorMsg = "";

                if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length) {
                error.style.display = 'block';
                errorMsg = "Please enter a valid email address ";
                error.innerHTML = errorMsg;
                return false;
                 }
                 else if(instalength > 0){
                     var instaatpost = insta.indexOf("@");
                     if(instaatpost == 0){
                     error.style.display = 'block';
                     errorMsg = "This is not a valid instagram account (do not include the @ ~ Ex: instagram_user)";
                     error.innerHTML = errorMsg;
                     return false;
                     }
                     }
                 else if(phonelength > 0){
                   var isNum = /^\d+$/.test(phone);
                   if(isNum && phonelength != 10){
                    error.style.display = 'block';
                    errorMsg = "Please enter a valid 10 digit phone number Ex:1234567890";
                    error.innerHTML = errorMsg;
                    return false;
                   }
                   if(!isNum){
                    error.style.display = 'block';
                    errorMsg = "Please enter a valid 10 digit phone number with only numbers Ex:1234567890";
                    error.innerHTML = errorMsg;
                    return false;
                   }
                }
                else{
                    error.style.display = 'none';
                }
}
        </script>
         <!--[if lt IE 9]>
        <script src="//html5shim.googlecode.com/sin/trunk/html5.js"></script>
        <![endif]-->
 <?php
        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
        //
        // inlcude all libraries. 
        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%

       print "<!-- require Database.php -->";
        require('assets/lib/database.php');

        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
        //
        // Set up database connection
        //
        // generally you dont need the admin on the web

        print "<!-- make Database connections -->"; 
        $dbName = 'CKWESTON_CS148';
        $dbUserName = get_current_user() . '_writer';
        $whichPass = "w";
        $thisDatabaseWriter = new Database($dbUserName, $whichPass, $dbName);
        $dbUserName = get_current_user() . '_reader';
        $whichPass = "r"; //flag for which one to use.
        $thisDatabaseReader = new Database($dbUserName, $whichPass, $dbName);
        




        require('phpFunctions.php');
        print "<!-- require phpFunctions.php success -->";
        
        require('user.php');
        print "<!-- require user.php success -->";
        
        ?> 


        </head>
        
        <img id = "banner" src="assets/images/banner.png">


    <nav>
        <?php
     $currentPage = basename($_SERVER['PHP_SELF']);
     if(strcmp($currentPage,'signUp.php') != 0){
     require('navBar.php');
        }
      ?>
  </nav>

    <body onload='startTime()'>