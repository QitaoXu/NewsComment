<!DOCTYPE html>
    
<html>
<head>
    <title>Edit Your Comments</title></head>
<body>

<?php
//show news lists'
session_start();
$_SESSION['delete_token'] = bin2hex(openssl_random_pseudo_bytes(32));
$_SESSION['change_comment_token'] = bin2hex(openssl_random_pseudo_bytes(32));
require 'Database.php';
$story_id = (int) $_GET['story_id'];
$comment_id = (int) $_GET['comment_id'];

echo '<a href = "mainPage.php" >Back to mainpage</a>';
echo '<br><br>';

require 'Database.php';
$stmt = $mysqli->prepare("select comments from comments where comment_id = '$comment_id'");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->execute();
$stmt->bind_result($comment);
$stmt->fetch();

echo '<form action = "change_comment.php" method = "POST">';

echo '<input type = "hidden" name = "story_id" value = "'.$story_id.'">';
echo '<input type = "hidden" name = "comment_id" value = "'.$comment_id.'">';

echo '<input type = "hidden" name = "story_id" value = "'.htmlentities($story_id).'">';
echo '<input type = "hidden" name = "comment_id" value = "'.htmlentities($comment_id).'">';
echo '<input type="hidden" name="change_comment_token" value="'.$_SESSION['change_comment_token'].'">';
echo 'Comment: <input type ="text" name = "comment" size = "35" value = "'.htmlentities($comment).'">';
echo '<input type ="submit" value ="Change my comment">';
echo '</form>';

echo "<br> <br>";

echo '<form action = "delete_comment.php" method = "POST">';
echo '<input type = "hidden" name = "story_id" value = "'.htmlentities($story_id).'">';
echo '<input type = "hidden" name = "comment_id" value = "'.htmlentities($comment_id).'">';
echo '<input type="hidden" name="delete_token" value="'.$_SESSION['delete_token'].'">';
echo '<input type = "submit" value = "Delete Comment">';
echo '</form>';

$stmt->close();
//}
?>


</body>
</html>
