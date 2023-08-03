<?php
session_start();
if (empty($_SESSION["user"])) {
    header("location:unauth.php");
}
require_once("clases.php");
$user = unserialize($_SESSION["user"]);
if (isset($_GET['postId']) && is_numeric($_GET['postId'])&&isset($_GET['userId']) && is_numeric($_GET['userId'])&&isset($_GET['Id']) && is_numeric($_GET['Id'])) {
    // Rest of the code
    $posts = $user->deletecommet($_GET['Id']);
    header("location:view_details_admin_want.php?userId=".$_GET['userId']. "&postId=" .$_GET['postId']."&Id=" .$_GET['Id'] );
}


?>