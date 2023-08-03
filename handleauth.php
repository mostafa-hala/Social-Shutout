<?php
session_start();

if (!empty($_POST["email"]) && !empty($_POST["password"])) {
    $email = htmlspecialchars(trim($_POST["email"]));
    $password = $_POST["password"];
    require_once("clases.php");
    $user = Users::login($email,$password);
    var_dump($user);
    if (empty($user)) {
        header("location: login.php?msg=invaled_email_and_password");
        } else {
            $role=$user->role;
            $_SESSION["user"] = serialize($user);
            if ($role == 'user') {
                header("location:user.php");
            }else {
                 header("location:admin.php");
            }
            }
 } else {
    header("location: login.php?msg=empty_field");
}
