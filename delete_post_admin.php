<?php
session_start();
if (empty($_SESSION["user"])) {
    header("location:unauth.php");
}
require_once("clases.php");
$user = unserialize($_SESSION["user"]);
if (isset($_GET['postId']) && is_numeric($_GET['postId'])&&isset($_GET['userId']) && is_numeric($_GET['userId'])) {
    // Rest of the code
    $posts = $user->deletepost($_GET['postId']);
    // var_dump($posts);
    header("location:view_user_from_admin.php?userId=".$_GET['userId']);
}


?>