<?php

	session_start();
	$story_id = $_POST['story_id'];
	$comment_id = (int) $_POST['comment_id'];
	$comment = $_POST['comment'];
	if($_SESSION['change_comment_token'] !== $_POST['change_comment_token']){
	die("Request forgery detected");
        }
	
	require 'Database.php';
	$stmt = $mysqli->prepare("update comments set comments = '$comment' where comment_id = '$comment_id'");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->execute();
	$stmt->bind_result($comments);
	$stmt->close();
    
	$comment_path = sprintf("comment.php?story_id=%s", $story_id);
        header("Location: $comment_path");


?>
