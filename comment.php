<!DOCTYPE html>
<html>
<head>
    <title>Comments </title>
    <link rel="stylesheet" href="css/table.css" />
</head>
<body>

<?php
    session_start();
    $_SESSION['upload_comment_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    require 'Database.php';
   echo '<div style="text-align: center">';
   echo '<a href = "mainPage.php" >Back to mainpage</a><br><br>';
   echo '</div>';
   
    // create a link, that users can go back to see the stories.
    $stmt = $mysqli->prepare("SELECT story_name, link, fromwho FROM stories WHERE story_id=?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('i', $story_id);
    $story_id = (int)$_GET['story_id'];
    $stmt->execute();
    $stmt->bind_result($mytitle, $mylink, $mysubmitter);
    $stmt->fetch();
    echo 'Story Link: ';
    echo '<a href="'.htmlentities($mylink).'" target="_blank"> '.htmlentities($mytitle).'</a> <br><br>';
    $stmt->close();

// create a link for submit comments
    if (isset($_SESSION['user_id'])){
        echo '<form action = "upload_comment.php" method = "POST">';
        echo '<input type = "hidden" name = "story_id" value = "'.$story_id.'">';
        echo 'Comment: <input type ="text" name = "comment" size = "35">';
        echo '<input type="hidden" name="upload_comment_token" value="'.$_SESSION['upload_comment_token'].'">';
        echo '<input type ="submit" value ="Post">';
        echo '</form>';
        echo '<br>';
    }

    //read the comments from the databse, and show in the list
    $stmt = $mysqli->prepare("SELECT comment_id, commenter, comments, story_id, comment_time FROM comments WHERE story_id = ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    
    $stmt->bind_param('i', $story_id);
    //$story_id = (int)$_GET['story_id'];
    $stmt->execute();

    // Bind the results
    $stmt->bind_result($comment_id, $commenter, $comments, $story_id, $comment_time);

    echo "<table>";
    echo "<tr>";
    echo "<th>Commenter_ID</th>";
    echo "<th>Comments</th>";
    echo "<th>Comment Time</th>";
    echo "<th>Notes</th>";
    echo "</tr>";
    while($stmt->fetch()){
        echo "<tr>";
        echo '<td>'.htmlentities($commenter).'</td>';
        echo '<td>'.htmlentities($comments).'</td>';
        echo '<td>'.htmlentities($comment_time).'</td>';
        
        //only the poster can edit his own comments
        if(isset($_SESSION['user_id'])){
            if($commenter == $_SESSION['username']){
                $modify_path = sprintf("edit_comment.php?story_id=%s&comment_id=%s", $story_id, $comment_id);
                echo '<td><a href="'.$modify_path.'"> Edit or Delete Comment </a></td>';
            } else {
                echo '<td></td>';
            }
        }
        echo '</tr>';
    }
    echo "</table>";
    
    
/*if(isset($_SESSION['user_id']) && isset($_POST['comment']) ) {
 
    $username = $_SESSION['user_id'];
    $comment = $_POST['comment'];
 
    $stmt = $mysqli->prepare("insert into comment(story_id, commenter, comments) values (?, ?, ?)");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('iss', $story_id, $username, $comment);
    $stmt->execute();
    $stmt->close();
    $comment_path = sprintf("comment.php?story_id=%s", $story_id);
    header("Location: $comment_path");

}*/

?>
</body>
</html>
