<?php
	session_start();
	
	require 'Database.php';
	
	if(!isset($_SESSION['username'])){
		echo "If you want to change your password, please login first.";
		header('Refresh: 3; URL = login.php');
    }
	
	//CSRF detct
	if(!hash_equals($_SESSION['change_password_token'], $_POST['change_password_token'])){                      
		die("Request forgery detected");
	}
	
	if(empty($_POST['old_password'])){                      //old password cannot be vacancy
		echo "Please input your username.";
		header('Refresh: 3; URL = mainPage.php');
		die();
	}else if(empty($_POST['new_password'])){					//new password cannot be vacancy
		echo "Please input your email address.";
		header('Refresh: 3; URL = mainPage.php');
		die();
	}else{
		$username = $_SESSION['username'];
		$user_old_password_input = $_POST['old_password'];
		$user_new_password = $_POST['new_password'];
		
	}
	
	$stmt = $mysqli->prepare('select crypted_pass from users where user_name = ?');
	
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	
	$stmt->bind_param('s',$username);
	$stmt->execute();
	
	$stmt->bind_result($user_old_password);
	$stmt->fetch();
	$stmt->close();
	
	if(!password_verify($user_old_password_input, $user_old_password)){
		echo "Your old password is not the same as what we have record. You cannot change your password.";
		
		header('Refresh: 3; URL = mainPage.php');
		
	}else{
		
		$stmt = $mysqli->prepare('update users set crypted_pass = ? where user_name = ?');
		
		if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
		}
		
		$new_crypted_password = password_hash($user_new_password, PASSWORD_BCRYPT);
		
		$stmt->bind_param('ss',$new_crypted_password,$username);
		
		$stmt->execute();
		
		$stmt->close();
		
		echo "You have changed your password, please re-login.";
		
		header('Refresh: 3; URL = login.php');
	}
	
	

?>
