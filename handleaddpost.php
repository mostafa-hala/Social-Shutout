<?php
session_start();
if (!empty($_POST["title"]) && !empty($_POST["content"])){
    $title=$_POST["title"];
    $content=$_POST["content"];
    require_once("clases.php");
    $user=unserialize($_SESSION["user"]);
    // var_dump($_FILES);
    // echo"hr";
    // var_dump($_FILES["image"]["name"])
    $extension=pathinfo($_FILES["image"]["name"],PATHINFO_EXTENSION);
    $file_name="img/posts/" .Date("YmdHms.") .$extension;
    move_uploaded_file($_FILES["image"]["tmp_name"],$file_name);
    $result= $user->addpost($content,$title,$file_name);
    // var_dump($result);
    
        header("location:user.php?msg=done");
 
    
    
}else{
    header("location:user.php?msg=empty_title_content");
}
