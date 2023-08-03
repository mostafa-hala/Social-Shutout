<?php
session_start();
if (empty($_SESSION["user"])) {
    header("location:unauth.php");
}
require_once("clases.php");
$user = unserialize($_SESSION["user"]);
if (isset($_GET['postId']) && is_numeric($_GET['postId'])) {
    // Rest of the code
    $posts = $user->deletecommet($_GET['postId']);
    header("location:user_view_post.php");
}


?>