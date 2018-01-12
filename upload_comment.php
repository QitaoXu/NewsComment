<?php
//post comments
session_start();
if($_SESSION['upload_comment_token'] !== $_POST['upload_comment_token']){
	die("Request forgery detected");
}
require 'Database.php';
    $username = $_SESSION['username'];
    $comment = $_POST['comment'];
    $story_id = (int) $_POST['story_id'];
    
    $stmt = $mysqli->prepare("insert into comments(commenter, comments, story_id) values (?, ?, ?)");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('ssi', $username, $comment, $story_id);
    $stmt->execute();
    $stmt->close();
    $comment_path = sprintf("comment.php?story_id=%s", $story_id);
    header("Location: $comment_path");
?>