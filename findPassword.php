<!DOCTYPE HTML>
	<html>
		<?php
		session_start();
		$_SESSION['find_password_token'] = bin2hex(openssl_random_pseudo_bytes(32));
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
			
			<div class="content">
				<form action="findPasswordJudge.php" method="post">
					<label for="username">Please input your username:</label><br>
					<input type="text" name="username" id="username"/><br>
					
					<label for="email">Please input your email address when you register</label><br>
					<input type="email" name="email" id="eamil"/><br>
					
					<input type="hidden" name="find_password_token" value="<?php echo $_SESSION['find_password_token'];?>" />
					
					<input type="submit" value="Verify"/><br>
				</form>
			</div>
			
			
		</body>
	</html>