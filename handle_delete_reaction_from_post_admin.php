<?php
session_start();

require_once("clases.php");
$user = unserialize($_SESSION["user"]);
// $posts2 = $user->showpost();
// var_dump($posts2);
if (isset($_GET['postId'])&&is_numeric($_GET['postId'])&& isset($_GET['userId'])&&is_numeric($_GET['userId'])) {
    $reactions= $user->delete_reaction_to_post($_GET['postId']);
    $count=$user->getReactionNumberpost($_GET['postId']);
    // var_dump($count);

    // var_dump($reactions);
    header("location:view_details_admin_want.php?userId=".$_GET['userId']. "&postId=" .$_GET['postId'] );

    //  echo "<hr>";
    // var_dump($comments);
}
?>