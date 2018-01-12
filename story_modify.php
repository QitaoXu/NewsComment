<?php
    session_start();
    $story_id = $_GET['story_id'];
    require 'Database.php';
    $stmt = $mysqli->prepare("select fromwho from stories where story_id = '$story_id'");
    if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
        }
    
    $stmt->execute();
	$stmt->bind_result($username);
	$stmt->fetch();
    if(!hash_equals($username,$_SESSION['username'])){
        echo "You have no right to delete the story!";
	header('Refresh: 3; URL = mainPage.php');
        die();
    }
    
    $stmt->close();
   
    //delete all comments associated with the story.
    $stmt = $mysqli->prepare("delete from comments where story_id = '$story_id'");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
        }
    $stmt->bind_param('i', $story_id);
    $stmt->execute();
    $stmt->close();
		
    //delete the story.	 
    $stmt = $mysqli->prepare("delete from stories where story_id = '$story_id'");
    if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
        }
    
    $stmt->bind_param('i', $story_id);
    $stmt->execute();
    $stmt->close();
	echo "You have deleted the story!";
    header('Refresh: 3; URL = mainPage.php');	
?>
    
    
