<?php
session_start();
if (empty($_SESSION["user"])) {
    header("location:unauth.php");
}
require_once("clases.php");
$user = unserialize($_SESSION["user"]);
if (isset($_GET['userId']) && is_numeric($_GET['userId'])) {
    // Rest of the code
    $delte = $user->deleteuser($_GET['userId']);
    // var_dump($delte);
    header("location:admin.php");
}
?>