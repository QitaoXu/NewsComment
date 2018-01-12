<!DOCTYPE HTML>
	<html>
		<?php
		session_start();
		unset($_SESSION['username']);
		unset($_SESSION['user_id']);
		$_SESSION['login_token'] = bin2hex(openssl_random_pseudo_bytes(32));
		?>
		<head>
			<meta charset="utf8">
			<title>Welocme to News Comment</title>
			<link rel="stylesheet" href="css/login.css" />
		</head>
		<body>
			<div class= "headline">
				<h1>Welcome to News Comment!</h1>
			</div>
			
			<div class="content" id="news_pic">
				<img src="pictures/news.jpg" alt="newspost"/>
			</div>
			
			<div class="content" id="login_form">
				<form action="loginJudge.php" method="post">
					<label for="username">Username:</label><br>
					<input type="text" name="username" id="username"/><br>
					
					<label for="password">Password:</label><br>
					<input type="password" name="password" id="password"/><br>
					
					<input type="hidden" name="login_token" value="<?php echo $_SESSION['login_token'];?>" />
					
					<input type="submit" value="Login"/><br><br>
				</form>
				<a href="register.php">Not a user? Register now!</a>
				<br><br>
				<a href="findPassword.php">Forget your password? click here!</a>
				<br><br>
				<a href="mainPage.php">I am just a visitor? OK, click here to view!</a>
			</div>
			
			
		</body>
	</html>
