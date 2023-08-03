<?php
session_start();

if (!empty($_POST["content"])){
    $content=$_POST["content"];
    $id=$_POST["id"];
    require_once("clases.php");
    $user=unserialize($_SESSION["user"]);
    // var_dump($_FILES);
    // echo"hr";
    // var_dump($_FILES["image"]["name"])
    $result= $user->addcomment($content,$id);
    // var_dump($result);
    
        header("location:view_post_comments.php?postId=".$_POST["id"] );
 
    
    
}else{
    header("location: unauth.php");
}