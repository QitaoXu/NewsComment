<?php
session_start();
unset($_SESSION['username'],$_SESSION['user_id']);
echo "You have logged out!";
header('Refresh: 3; URL = mainPage.php');


?>
