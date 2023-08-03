<!-- bashmohandes data base code dh 3ayzk tfhmholi dh chatgpt 3shan kan bytl3 lia mo4kla dehh 
Query execution failed: Column 'created_at' in field list is ambiguous
Warning: mysqli_fetch_assoc() expects parameter 1 to be mysqli_result, bool given in C:\xampp\htdocs\RMZ-tech\blogs\blogclass.php on line 9
NULL
-->
<?php
class Blog {
    static function get_posts() {
        require_once("config.php");
        $qry = "SELECT users.username, posts.content, posts.title, posts.image, posts.created_at 
                FROM users 
                JOIN posts ON users.id = posts.user_id 
                ORDER BY posts.created_at DESC";
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        
        if (!$cn) {
            die('Database connection failed: ' . mysqli_connect_error());
        }
        
        $result = mysqli_query($cn, $qry);
        
        if (!$result) {
            die('Query execution failed: ' . mysqli_error($cn));
        }
        
        $data = array();
        
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        
        mysqli_free_result($result);
        mysqli_close($cn);
        
        return $data;
    }
}



// $data1= blog::get_posts();
// var_dump($data1);
?>