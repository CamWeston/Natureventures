<!-- This page will allow users to create and view posts from all users. -->
<!-- Written by Cameron Weston and Ivan Spizizen -->


<?php
//require the header page to get current user info
require ('header.php'); 
//The following html is containers and inputs allowing users to create posts and upload files

?>

<div class = 'createPost' >
<h2 class = 'createPost' > Create a Post <?php  echo $userFirstName ?></h2>
<form class = 'createPost' enctype="multipart/form-data" method = 'post' action = 'theWall.php'>
<?php echo '<img class = "profilePicture" src = "'.$userProfilePictureLocation.'">';?>
<textarea name = 'postText' class = 'createPost' id = 'createPostText' type='textarea' placeholder = 'Tell us about your latest adventure' rows="3" cols="3"/></textarea>
<div class ='createPostBtn'>
<input name = "postPictures[]" class = 'createPost' id ='btnpictures'  type="file" accept="image/jpeg,image/gif,image/jpg" multiple />
<input name = 'postSubmit'  class = 'createPost' id= 'btnpictures' type ='submit'/>
</div>
</form>
</div>
<div class = 'allPosts' >
<?php 
//This block will pull posts from db to be displayed
$query = "SELECT fnkNetId,pmkPostId,fldDescription,fldLikes,fldDateCreated,fldFirstName,fldLastName,fldProfilePictureLocation FROM tblPosts JOIN tblUsers ON tblUsers.pmkNetId = tblPosts.fnkNetId  ORDER BY pmkPostId DESC ";
$postIds = $thisDatabaseReader->select($query,null,0,1);
//Each post will print with pictures and all other details entered 
foreach ($postIds as $post) {
$query = "SELECT fldPictureLocation FROM tblPictures WHERE fnkPostId = ?";
$data = array($post['pmkPostId']);
$postPictures = $thisDatabaseReader->select($query,$data,1);
$query = "SELECT fnkNetId,fldComment,pmkCommentId FROM tblComments WHERE fnkPostId = ?";
$postComments = $thisDatabaseReader->select($query,$data,1);
echo "<div class='post' id ='postContainer'>";
  echo "<div class = 'userImg' id = 'imgHead'>";
  if($userNetId == $post['fnkNetId']){
    echo "<div class = 'delete'>";
    echo "<form method ='post' action ='theWall.php'>";
    echo '<input type="submit" id="deletePost" name="deletePostButton" value ="Delete Post">';
    echo "<input type='hidden' name='postIdToDelete' value ='".$post['pmkPostId']."' >";
    echo "</form>";
    echo "</div>";
  }
  echo '<img class = "profilePicture2" src = "'.$post['fldProfilePictureLocation'].'">';
  echo "<h4 class='post'>".$post['fldFirstName']." ".$post['fldLastName']."</h4>";
  //close userImg div
  echo "</div>";

  echo "<div class='postDescription'>";
  echo '<p class="post" id="postDescription">'.$post['fldDescription']."</p>";
  if(strlen($postPictures[0][0]) > 1){
    foreach ($postPictures as $picture) {

        echo '<img class="post" id="postPictures" src = "'.$picture[0].'">';
    }
  }
  //close post description div
  echo "</div>";
  //close header div
 // echo "</div>";
  echo "<div class ='post' id = 'postCommentContainer'>";
  foreach ($postComments as $comment) {
    $query = "SELECT fldFirstName,fldLastName,fldProfilePictureLocation FROM tblUsers WHERE pmkNetId = ?";
    $data = array($comment['fnkNetId']);
    $commentorData = $thisDatabaseReader->select($query,$data,1);
    $author = $commentorData[0][0]." ".$commentorData[0][1];
    echo "<div class='post' id='comment'>";
    echo "<div class = 'userImg2' id = 'imgHead2'>";
    if($userNetId == $comment['fnkNetId']){
    echo "<div class = 'delete'>";
    echo "<form method ='post' action ='theWall.php'>";
    echo '<input type="submit" id="deleteComment" name="deleteCommentButton" value ="Delete Comment">';
    echo "<input type='hidden' name='commentIdToDelete' value ='".$comment['pmkCommentId']."' >";
    echo "</form>";
    echo "</div>";
    }
    echo "<img class ='profilePicture2' src ='".$commentorData[0][2]."'>"; 
    echo "<h4 class='post' id='commentAuthor'>".$author."</h4>";

    echo "</div>";
    echo "<p class='post' id='commentText'>".$comment['fldComment']."</p>";
    echo "</div>";
  }
  echo "<div class = 'post' id='createComment'>";
  echo "<form method ='post' action ='theWall.php'>";
  //echo "<h2 class='post' id='createCommentHeader'>".$userFirstName." ".$userLastName."</h2>";
  echo "<input name ='commentTextInput' type='text' class='post' id='commentTextInput' placeholder = 'leave a comment'>";
  echo "<input class ='post' name ='commentSubmit' type='submit' >";
  echo "<input type='hidden' name='postId' value ='".$post['pmkPostId']."' >";
  echo "</form>";
  echo "</div>";




  //close the comment div
  echo "</div>";
  //close container div
  echo "</div>";
  





}



 ?>

</div>









<?php 
//The following block is for uploading pictures and posts to the database
//If the user hits submit than start
if(isset($_POST['postSubmit'])){
$filesToBeUploaded = array();
$noFiles = false;
$totalFiles = count($_FILES['postPictures']['name']);
$uploadError = false;
$counter = 0;
$allFilesSuccess = 0;
$target_dir = "uploads/postPictures/";
if(strlen($_FILES['postPictures']['name'][0]) != 0){
for ($i=0; $i < $totalFiles ; $i++) { 
  $fileName = clean($_FILES['postPictures']['name'][$i]);
  $target_file = $target_dir .'postPicture';
  $imageFileType = pathinfo($fileName,PATHINFO_EXTENSION);
  $ext = ".".strtolower($imageFileType);
  $target_file = $target_dir .'postPicture'.$ext;
if($ext != ".jpg" && $ext != ".gif" && $ext != ".jpeg"){
       $uploadError = true;

       $errormsg = $fileName."-User uploaded invalid image type: ".$ext."<br>";
}
// Check if file already exists
while (file_exists($target_file)) {
    $target_file = $target_dir.'postPicture'.$counter.$ext;
    $counter = $counter + 1;
}
if(!$uploadError){
   $tmp_name = $_FILES['postPictures']['tmp_name'][$i];
   if(move_uploaded_file($tmp_name, $target_file)){
    $allFilesSuccess +=1;
    array_push($filesToBeUploaded, $target_file);
   }
   else{
    echo "move_uploaded_file was unsuccessfull for file - ".$fileName;
   }
   }
else{
  echo $errormsg;
}
}
}
else{
  $noFiles = true;
}












if($allFilesSuccess == $totalFiles || $noFiles == true){
$date = date("Y-m-d h:i:sa");
$data = array($userNetId,$_POST['postText'],$date,0);
$query = "INSERT INTO tblPosts SET
    fnkNetId = ?,
    fldDescription = ?,
    fldDateCreated = ?,
    fldLikes = ?";
$insertNewPost = $thisDatabaseWriter->insert($query,$data);
if($noFiles == true){
echo '<script type="text/javascript">';
echo 'window.location.href="theWall.php";';
echo '</script>';
}
//only run the upload picture to DB loop if the user uploaded pictures AND the post insert was successfull 
if ($insertNewPost && !$noFiles){
$pmkPostId = $thisDatabaseWriter->lastInsert();
foreach($filesToBeUploaded as $file){
    $query = "INSERT INTO tblPictures SET
        fnkNetId = ?,
          fnkPostId = ?,
            fldPictureLocation = ?";
       $data = array($userNetId,$pmkPostId,$file);
       $insertNewPicture = $thisDatabaseWriter ->insert($query,$data);
  }
echo '<script type="text/javascript">';
echo 'window.location.href="theWall.php";';
echo '</script>';
}
}
}

//submit comment
if(isset($_POST['commentSubmit'])){
  if(strlen($_POST['commentTextInput']) > 0){
    $pid = $_POST['postId'];
    $query = "INSERT INTO tblComments SET
        fnkNetId = ?,
          fnkPostId = ?,
            fldComment = ?";
       $data = array($userNetId,$pid,$_POST['commentTextInput']);
       $insertNewComment = $thisDatabaseWriter ->insert($query,$data);
       if($insertNewComment){
        echo '<script type="text/javascript">';
        echo 'window.location.href="theWall.php";';
        echo '</script>';
       }
  }
}
//deletes a post
if(isset($_POST['deletePostButton'])){
  $postIdToDelete = $_POST['postIdToDelete'];
  $query = 'DELETE FROM tblPosts WHERE pmkPostId = ?';
  $data = array($postIdToDelete);
  $deletePost = $thisDatabaseWriter->delete($query,$data,1);
  if($deletePost){
  $query = 'DELETE FROM tblComments WHERE fnkPostId = ?';
  $deleteComments = $thisDatabaseWriter->delete($query,$data,1);
  }
  if($deleteComments){
        echo '<script type="text/javascript">';
        echo 'window.location.href="theWall.php";';
        echo '</script>';
       }
}
//deletes a comment
if(isset($_POST['deleteCommentButton'])){
  $commentIdToDelete = $_POST['commentIdToDelete'];
  $query = 'DELETE FROM tblComments WHERE pmkCommentId = ?';
  $data = array($commentIdToDelete);

  $deleteComment = $thisDatabaseWriter->delete($query,$data,1);
  if($deleteComment){
        echo '<script type="text/javascript">';
        echo 'window.location.href="theWall.php";';
        echo '</script>';
       }
}
?>






<?php require ('footer.php') ?>