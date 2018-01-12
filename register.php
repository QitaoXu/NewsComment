<!DOCTYPE HTML>
	<html>
		<head>
			<meta charset="utf8">
			<link rel="stylesheet" href="css/login.css" />
			<title>Register News Comment</title>
		</head>
		<body>
                <?php
                session_start();
                $_SESSION['register_token'] = bin2hex(openssl_random_pseudo_bytes(32));
                ?>
			
			<div class="headline">
				<h1>Welocme to News Comment</h1>
			</div>
			
			<div class="content" id="news_pic">
				<img src="pictures/news.jpg" alt="newspost"/>
			</div>
			
			<div class="content" id="register_form">
				<form action="registerJudge.php" method="post">
					<label for="username">Username:</label><br>
					<input type="text" name="username" id="username"><br>
					
					<label for="password">Password:</label><br>
					<input type="password" name="password" id="password"><br>
					
					<label for="retype_password">Retype Password:</label><br>
					<input type="password" name="retype_password" id="retype_password"><br>
					
					<label for="email">email:</label><br>
					<input type="email" name="email" id="email"><br>
				<input type="hidden" name="register_token" value="<?php echo $_SESSION['register_token'];?>" />	
					<input type="submit" value="Register">
				</form>
			</div>
		</body>
	</html>
