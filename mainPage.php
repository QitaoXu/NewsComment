<!DOCTYPE html>
<html>
<head>
    <title>Story Site</title>
    <link rel="stylesheet" href="css/table.css" />
</head>

<body>
Amazing Stories <br><br>

<?php
//<a href = "logout.php" >Log out</a>
$err = '';
//upload new story's name and link
session_start();
    if(!isset($_SESSION['username'])){
        echo "<a href = 'login.php'>login</a><br><br>";
    }else{
        echo "Hi, ".$_SESSION['username']."! Stay sharp when commenting!:)"."<br>";
        echo "<a href = 'logout.php'>logout</a><br><br>";
	echo "<a href = 'changePassword.php'>change your password</a><br><br><br>";
    }
$_SESSION['mainPage_token'] = bin2hex(openssl_random_pseudo_bytes(32));
?>
<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
    <div>
       
        <label for="title">Title:</label>
        <input type="text" name="title" size="45" id="title" />
        <br><br>
        <label for="link"> Link:</label>
        <input type="text" name="link" size="45" id="link">
        
        <input type="submit" value="Post News" />
        <input type="hidden" name="token" value="'.$_SESSION['mainPage_token'].'" /><br>
        <br>
        
    </div>
</form>


<?php
// post new story and link address 
require 'Database.php';
if(isset($_SESSION['user_id']) && isset($_POST['title']) && isset($_POST['link']) ) {
    
    $url = $_POST['link'];

    if(!preg_match("/http:/", $url ) && !preg_match("/https:/", $url )){
        //if url contains any special charactors or don't have a http:
        $err = "Your URL link is invalid, please enter again!";
        header('Location: mainPage.php');
    }else{
        $err='';
        // after checking, we need to put the url into the databse
        $username = $_SESSION['username'];
        //$url = $_POST['link'];
        $title = $_POST['title'];
        $stmt = $mysqli->prepare("insert into stories (story_name, link, fromwho) values (?, ?, ?)");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param('sss', $title, $url, $username);
        $stmt->execute();
        $stmt->close();
        header('Location: mainPage.php');
    }

}else if(isset($_POST['title']) && isset($_POST['link'])){
    print("Guest can only view news ! But if you want to post your story, please register.");
}
?>


<?php
//show news lists
require 'Database.php';
// Use a prepared statement
$stmt = $mysqli->prepare("SELECT story_id, story_name, link, fromwho, story_time FROM stories");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->execute();

// Bind the results
$stmt->bind_result($story_id, $title, $link, $submitter, $story_time);

//echo "<a href='profile.php'>User's Page</a>";

echo "<table>";
echo "<tr>";
echo "<th>&nbsp&nbsp&nbsp</th>";
echo "<th>Title&nbsp</th>";
echo "<th>From&nbsp</th>";
echo "<th>Time&nbsp</th>";
echo "<th>Write Comment&nbsp</th>";
echo "<th>Edit&nbsp</th>";
echo "</tr>";

$story_count = 1;
while($stmt->fetch()){
    echo "<tr>";
    echo '<td>'.htmlentities($story_count).'</td>';$story_count = $story_count + 1;
    echo '<td><a href="'.htmlentities($link).'" target="_blank"> '.htmlentities($title).'</a></td>';
    echo '<td>'.htmlentities($submitter).'</td>';
    echo '<td>'.htmlentities($story_time).'</td>';
    
    $comment_path = sprintf("comment.php?story_id=%s", $story_id);
    echo '<td><a href="'.$comment_path.'"> Comments </a></td>';

    //submitter can delete or edit story
    if(isset($_SESSION['user_id'])){
        if($submitter == $_SESSION['username']){
            $modify_path = sprintf("story_modify.php?story_id=%s", $story_id);
            echo '<td><a href="'.$modify_path.'"> Delete Story </a></td>';
        } else {
            echo '<td></td>';
        }
    }
    echo '</tr>';
}
echo "</table>";
//}
?>
<div class="err">
    <span><?php echo htmlentities($err); ?></span>
</div>
</body>
</html>
