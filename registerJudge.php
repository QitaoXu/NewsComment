<!DOCTYPE HTML>
	<html>
		<head>
			<meta charset="utf8">
			<title>Welocme to News Comment</title>
		</head>
		<body>
			<?php

                        session_start();
                        
	        	require 'Database.php';
			
			
			//CSRF detct
			if(!hash_equals($_SESSION['register_token'], $_POST['register_token'])){
                               // echo "tttt";
				die("Request forgery detected");
			}

			//username cannot be vacancy
			if(empty($_POST['username'])){                      //username cannot be vacancy
				echo "Please input your username.";
				header('Refresh: 3; URL = register.php');
				die();
			}else if(empty($_POST['password'])){                //password cannot be vacancy
				echo "Password cannot be vacancy.";
				header('Refresh: 3; URL = register.php');
				die();
			}else if(empty($_POST['retype_password'])){          //retype_password cannot be vacancy
				echo "Please retype the password.";
				header('Refresh: 3; URL = register.php');
				die();
			}else if(empty($_POST['email'])){                     //email cannot be vacancy
				echo "Email cannot be vacancy.";
				header('Refresh: 3; URL = register.php');
				die();
			}else if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$_POST['email'])) {  //email address check
				echo "Invalid email address!";
				header('Refresh: 3; URL = register.php');
				die();
			}else{
				echo "";
			}
			
			//Check password input 
			if($_POST['password'] == $_POST['retype_password']){
			//var_dump($_POST['password']);
			echo "<br>";
                	$password = $_POST['password'];
			}else{
				echo "Your password input twice don't match";
				header('Refresh: 3; URL = login.php');
				die();
			}
			
			//Check username whether exists
			$username = $_POST['username'];
			$stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE user_name=?");
                        
                        if(!$stmt){
                        printf("Check username Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                        }

                        $stmt->bind_param('s',$username);
                        $stmt->execute();

                        $stmt->bind_result($cnt);
                        $stmt->fetch();

			if($cnt > 0 ){
			echo "This username has been registered. Please select another username.";
			header('Refresh: 3; URL = login.php');
			die();
			}
			
			$stmt->close();

			
			//Add salt and hash
			$crypted_pass = password_hash($password, PASSWORD_BCRYPT);
			
			$user_email = $_POST['email'];
			$username = $_POST['username'];
			//var_dump($user_email,$username);
			$stmt = $mysqli->prepare("insert into users (user_name,crypted_pass,email) values(?,?,?)");
			
			if(!$stmt){
			printf("Inser Query Prep Failed: %s\n", $mysqli->error);
			exit;
			}

			$stmt->bind_param('sss',$username,$crypted_pass,$user_email);
			
			$stmt->execute();
			
			$stmt->close();

			//echo sprintf("%s",htmlentities("Now, you are a user of News Comment!"));

			//To get user_id
			$stmt = $mysqli->prepare("SELECT COUNT(*), user_id FROM users WHERE user_name=?");
	        	//$stmt->bind_param('s',$username);	
			if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
			}

			$stmt->bind_param('s',$username);
			$stmt->execute();
			
			$stmt->bind_result($cnt, $user_id);
			$stmt->fetch();
			$stmt->close();
			if($cnt == 1 ){
	
				$_SESSION['user_id'] = $user_id;
				$_SESSION['username'] = $username;
                                //var_dump($user_id,$username);
				echo sprintf("%s",htmlentities("Now, you are a user of News Comment!"));
				header('Refresh: 3; URL = mainPage.php');
			} else{
			echo sprintf("%s",htmlentities("Fail to register"));
			header('Refresh: 3; URL = register.php');
			}
			
			?>
		</body>
	</html>
