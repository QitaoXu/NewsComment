<!DOCTYPE HTML>
	<html>
		<head>
			<meta charset="utf8">
			<title>Welocme to News Comment</title>
		</head>
		<body>
			<?php
			require 'Database.php';
			session_start();
			
			if(!hash_equals($_SESSION['login_token'], $_POST['login_token'])){
				die("Request forgery detected");
			}
			
			$username = $_POST['username'];
			$password = $_POST['password'];
			
			$stmt = $mysqli->prepare("SELECT COUNT(*),user_id,crypted_pass FROM users WHERE user_name=?");
			
			if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
			}

			$stmt->bind_param('s',$username);
			$stmt->execute();
			
			$stmt->bind_result($cnt, $user_id, $pwd_hash);
			$stmt->fetch();
		        //var_dump($cnt,$user_id,$pwd_hash);echo "<br>";echo $cnt; echo "<br>"; echo $user_id;echo "<br>";				
			//var_dump(password_verify($password, $pwd_hash));
			if($cnt == 1 && password_verify($password, $pwd_hash)){
				// Login succeeded!
				$_SESSION['user_id'] = $user_id;
				$_SESSION['username'] = $username;
                                echo "Login succeeded! You will be redirected into main page automatically.";
				// Redirect to your target page
				header('Refresh: 3; URL = mainPage.php');
			} else{
                        echo "Login failed;";
			var_dump($cnt);
			var_dump($password_verify($password, $pwd_hash));
			// Login failed; redirect back to the login screen
			header('Refresh: 3; URL = login.php');
			}

			?>
		</body>
	</html>
