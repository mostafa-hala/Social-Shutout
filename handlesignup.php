<?php
require_once("clases.php");
        if (!empty($_POST["username"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
            $username = htmlspecialchars(trim($_POST["username"]));
            $email = htmlspecialchars(trim($_POST["email"]));
            $password = md5($_POST["password"]);
            $user =Users::signup($username,$email,$password);
            if ($user) {
                header("location: login.php");
            } else {
                header("location: signup.php?msg=already_exist");
            }
        } else {
            header("location: signup.php?msg=empty_field");
        }

?>