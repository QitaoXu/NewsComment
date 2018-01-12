<!DOCTYPE HTML>
	<html>
		
		<?php
		session_start();
		
		if(!isset($_SESSION['username'])){
		echo "If you want to change your password, please login first.";
		die();
		header('Refresh: 3; URL = login.php');
		}
		
		$_SESSION['change_password_token'] = bin2hex(openssl_random_pseudo_bytes(32));
		?>
		<head>
			<meta charset="utf8">
			<title>Welocme to News Comment</title>
			<link rel="stylesheet" href="css/login.css" />
			<style type="text/css">
			a {
				padding:20px 20px 20px 200px;
			}
			</style>
		</head>
		<body>
			<div class= "headline">
				<h1>Welcome to News Comment!</h1><br><br>
				<a href="mainPage.php">Back to main page</a>
			</div>
			
			<div class="content" id="news_pic">
				<img src="pictures/news.jpg" alt="newspost"/>
			</div>
			
			<div class="content">
				<form action="changePasswordJudge.php" method="post">
					<label for="old_password">Please input your old password:</label><br>
					<input type="password" name="old_password" id="old_password"/><br>
					
					<label for="new_password">Please input your new password:</label><br>
					<input type="password" name="new_password" id="new_password"/><br>
					
					<input type="hidden" name="change_password_token" value="<?php echo $_SESSION['change_password_token'];?>" />
					
					<input type="submit" value="Change password"/><br>
				</form>
				
			</div>
			
			
		</body>
	</html>
