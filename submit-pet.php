<!DOCTYPE HTML>
	<html>
		<head>
			<meta charset="utf8">
			<link rel="stylesheet" href="CSS/pet-style.css" />
			<title>Submit Pet</title>
		</head>
		
		<body>
			<?php
			session_start();
                        
	        require 'Database.php';
			
			//CSRF detct
			if(!hash_equals($_SESSION['add_token'], $_POST['add_token'])){
                               // echo "tttt";
				die("Request forgery detected");
			}
			
			$username = $_POST['username'];
			$species = $_POST['species'];
			$petname = $_POST['petname'];
			$weight = $_POST['weight'];
			$description = $_POST['description'];
			$picture = $_POST['picture'];
			
			$stmt = $mysqli->prepare("INSERT INTO pets (username, species, petname, weight, description, filename) values(?,?,?,?,?,?)");
			
			if(!$stmt){
                printf("Check username Query Prep Failed: %s\n", $mysqli->error);
                exit;
                }
			
			$stmt->bind_param('sssdss', $username,$specie,$petname,$weight,$description,$picture);
				
			$stmt->execute();
			
			$stmt->close();
			 
			
			?>
			
			
			
		</body>
		
	</html>