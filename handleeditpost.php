<?php
session_start();
// $postId = $_GET['postId'];
// var_dump($postId);
if (!empty($_POST["title"]) && !empty($_POST["content"])){
    $title=$_POST["title"];
    $id=$_POST["id"];
    $content=$_POST["content"];
    require_once("clases.php");
    // $postId = $_GET['postId'];
    $user=unserialize($_SESSION["user"]);
    // var_dump($_FILES);
    // echo"hr";
    // var_dump($_FILES["image"]["name"])
    $extension=pathinfo($_FILES["image"]["name"],PATHINFO_EXTENSION);
    $file_name="img/posts/" .Date("YmdHms.") .$extension;
    move_uploaded_file($_FILES["image"]["tmp_name"],$file_name);
    $result= $user->editpost($file_name,$content,$title,$id);
    var_dump($result);
    
        header("location:user_view_post.php");
 
    
    
}else{
    header("location:edit_post.php?postId=" . $_POST["id"] . "&msg=empty_title_content");
}
