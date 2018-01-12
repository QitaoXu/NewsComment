<?php

	session_start();
	
	require 'Database.php';
			
	//CSRF detct
	if(!hash_equals($_SESSION['find_password_token'], $_POST['find_password_token'])){                      
		die("Request forgery detected");
	}
	
	if(empty($_POST['username'])){                      //username cannot be vacancy
		echo "Please input your username.";
		header('Refresh: 3; URL = login.php');
		die();
	}else if(empty($_POST['email'])){					//email cannot be vacancy
		echo "Please input your email address.";
		header('Refresh: 3; URL = login.php');
		die();
	}else if(!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$_POST['email'])){   //check email address
		echo "Invalid email address!";
		header('Refresh: 3; URL = login.php');
		die();
	}else{
		$username = $_POST['username'];
		$user_email = $_POST['email'];
	}
	
	$stmt = $mysqli->prepare('select count(*), email from users where user_name = ? ');
	
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
		}
	
	$stmt->bind_param('s',$username);
	$stmt->execute();
			
	$stmt->bind_result($cnt, $user_register_email);
	$stmt->fetch();
	$stmt->close();
	
	//Rondomly generate a string
	//Source: https://gist.github.com/binjoo/5633221
	//randString function author: binjoo, who is a GitHub user.
	function randString($length, $specialChars = false) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    if ($specialChars) {
        $chars .= '!@#$%^&*()';
    }

    $result = '';
    $max = strlen($chars) - 1;
    for ($i = 0; $i < $length; $i++) {
        $result .= $chars[rand(0, $max)];
    }
    return $result;
	}
	
	if($cnt == 1 && hash_equals($user_email, $user_register_email)){
		$new_password = randString(8,false);
		
		echo "This is your temporary password: ".$new_password." and you can use it to login. ";
		
		echo "<br>";
		
		echo "Please remember it before you log in. After logging in, please change your password.";
		
		echo "<br>";
		
		$crypted_pass = password_hash($new_password, PASSWORD_BCRYPT);
		
		$stmt = $mysqli->prepare("update users set crypted_pass = ? where user_name = ?");
		
		if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
		}
		$stmt->bind_param('ss',$crypted_pass,$username);
		$stmt->execute();
		$stmt->close();
		echo "<a href = 'login.php'>Back to login</a><br>";
			
	} else{
		echo "You do not have the right to find this account's password.";
		header('Refresh: 3; URL = login.php');
	}
	
	
	
	
?>
