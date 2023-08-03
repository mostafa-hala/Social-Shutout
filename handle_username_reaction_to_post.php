<?php
session_start();

require_once("clases.php");
$user = unserialize($_SESSION["user"]);
// $posts2 = $user->showpost();
// var_dump($posts2);
if (isset($_GET['postId'])&&is_numeric($_GET['postId'])) {
    $reactions= $user->getUsernamesByPost($_GET['postId']);
    $count=$user->getReactionNumberpost($_GET['postId']);
    // var_dump($count);

    var_dump($reactions);
    // header("location:user_view_posts.php");

    //  echo "<hr>";
    // var_dump($comments);
}
?>