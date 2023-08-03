<?php
class Users
{
    public $id;
    public $email;
    private $password;
    public $username;

    public function __construct($id, $username, $password, $email)
    {
        $this->email = $email;
        $this->password = $password;
        $this->id = $id;
        $this->username = $username;
    }

    static public function login($email, $password)
    {
        $user = null;
        $email = htmlspecialchars(trim($email));
        $password1 = md5($password);
        require_once("config.php");
        $qry = "SELECT * FROM users WHERE email = '$email' AND password = '$password1'";
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        $result = mysqli_query($cn, $qry);
        var_dump($result);
        if ($rslt = mysqli_fetch_assoc($result)) {
            switch ($rslt['role']) {
                case 'user':
                    $id = $rslt['id'];
                    $username = $rslt['username'];
                    $email = $rslt['email'];
                    $password = $rslt['password'];

                    $user = new user($id, $username, $password, $email);
                    break;
                case 'admin':
                    $id = $rslt['id'];
                    $username = $rslt['username'];
                    $email = $rslt['email'];
                    $password = $rslt['password'];

                    $user = new admin($id, $username, $password, $email);
                    break;
            }
        }
        return $user;
    }

    static public function signup($username, $email, $password)
    {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = md5($_POST["password"]);
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        $result = mysqli_query($cn, $sql);
        mysqli_close($cn);
        return $result;
    }
}
class user extends Users
{
    public $role = 'user';
    function showallmyposts()
    {
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);

        $qry = "SELECT id,posts.content, posts.title, posts.image, posts.created_at 
            FROM posts  
            WHERE posts.user_id = '$this->id' 
            ORDER BY posts.created_at DESC";

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

    function addpost($content, $title, $image)
    {
        $user_id = $this->id;
        $sql = "INSERT INTO posts (title, content, user_id,image) VALUES ('$title', '$content', $user_id,'$image')";
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        $result = mysqli_query($cn, $sql);
        mysqli_close($cn);
        return $result;
    }
    function editpost($image, $content, $title, $id)
    {
        $sql = "UPDATE posts SET title='$title', content='$content', image='$image' WHERE posts.id=$id";
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        $result = mysqli_query($cn, $sql);
        mysqli_close($cn);
        return $result;
    }
    function deletePost($id)
    {
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);

        if (!$cn) {
            die('Database connection failed: ' . mysqli_connect_error());
        }

        // Delete reactions associated with the post
        $deleteReactionsQuery = "DELETE FROM reactions WHERE post_id = $id";
        $result = mysqli_query($cn, $deleteReactionsQuery);

        if ($result) {
            // Reactions deleted successfully, proceed with deleting the post
            $deletePostQuery = "DELETE FROM posts WHERE id = $id";
            $deletePostResult = mysqli_query($cn, $deletePostQuery);

            if ($deletePostResult) {
                mysqli_close($cn);
                return true;
            } else {
                echo "Error deleting post: " . mysqli_error($cn);
            }
        } else {
            echo "Error deleting reactions: " . mysqli_error($cn);
        }

        mysqli_close($cn);
        return false;
    }


    function showpost()
    {
        require_once("config.php");
        $qry = "SELECT users.username, posts.content,posts.id,posts.user_id ,posts.title, posts.image, posts.created_at 
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
    function showpostdetails($id)
    {
        require_once("config.php");
        $qry = "SELECT posts.content, posts.title, posts.image, posts.id,posts.created_at 
                FROM posts 
                where posts.id=$id ";
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


    function addcomment($content, $posts_id)
    {
        $user_id = $this->id;
        $sql = "INSERT INTO comments (content, user_id,posts_id) VALUES ('$content', '$user_id','$posts_id')";
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        $result = mysqli_query($cn, $sql);
        mysqli_close($cn);
        return $result;
    }
    function deletecommet($id)
    {
        $qry = "DELETE FROM comments where id=$id";
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        $result = mysqli_query($cn, $qry);
        mysqli_close($cn);
        return $result;
    }
    function showallmycomments()
    {
        $qry = "SELECT * FROM comments where user_id='$this->id'";
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        $result = mysqli_query($cn, $qry);
        $data = mysqli_fetch_all($result);
        mysqli_close($cn);
        return $data;
    }
    function editcomment($content, $id)
    {
        $sql = "UPDATE comments SET content='$content' WHERE comments.id= $id ";
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        $result = mysqli_query($cn, $sql);
        mysqli_close($cn);
        return $result;
    }
    function showpostcomments($id)
    {
        $qry = "SELECT c.*, u.username FROM comments AS c
                INNER JOIN users AS u ON c.user_id = u.id
                WHERE c.posts_id = '$id'";
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        $result = mysqli_query($cn, $qry);

        $data = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        mysqli_close($cn);
        return $data;
    }
    function showcommentdetails($id)
    {
        require_once("config.php");
        $qry = "SELECT comments.content, comments.id,users.username,comments.user_id 
                FROM comments join users on comments.user_id = users.id
                where comments.id=$id ";
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
    function add_reaction_to_post($post_id)
    {
        $user_id = $this->id;
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);

        // Check if the same user_id and post_id combination already exists in the table
        $checkSql = "SELECT COUNT(*) AS existing_rows FROM reactions WHERE post_id = $post_id AND user_id= $user_id";
        $checkResult = mysqli_query($cn, $checkSql);

        if ($checkResult) {
            $checkRow = mysqli_fetch_assoc($checkResult);
            $existingRows = $checkRow['existing_rows'];

            // If the combination exists, do not add a new row
            if ($existingRows > 0) {
                mysqli_close($cn);
                return false;
            }
        } else {
            // Handle query error
            mysqli_close($cn);
            return null;
        }

        $sql = "INSERT INTO reactions (post_id, user_id) VALUES ($post_id, $user_id)";
        $result = mysqli_query($cn, $sql);
        mysqli_close($cn);
        return $result;
    }
    // Get all usernames who made a reaction
    function getUsernamesByPost($postId)
    {
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);

        if (!$cn) {
            die('Database connection failed: ' . mysqli_connect_error());
        }

        $sql = "SELECT DISTINCT u.username FROM users u 
                INNER JOIN reactions r ON u.id = r.user_id
                WHERE r.post_id = $postId";

        $result = mysqli_query($cn, $sql);

        $usernames = array();

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $usernames[] = $row['username'];
            }
        }

        mysqli_close($cn);

        return $usernames;
    }


    // Usage example


    // function add_reaction_to_post($posts_id,$user_id){
    //     $sql = "INSERT INTO reactions (post_id,user_id) VALUES ($posts_id,$user_id)";
    //     require_once("config.php");
    //     $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
    //     $result = mysqli_query($cn, $sql);
    //     mysqli_close($cn);
    //     return $result;
    // }
    function delete_reaction_to_post($posts_id)
    {
        $user_id = $this->id;
        $qry = "DELETE FROM reactions where user_id=$user_id and post_id=$posts_id";
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        $result = mysqli_query($cn, $qry);
        mysqli_close($cn);
        return $result;
    }
    function getReactionNumberpost($post_id)
    {
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        if (!$cn) {
            die('Database connection failed: ' . mysqli_connect_error());
        }

        $sql = "SELECT COUNT(DISTINCT user_id, post_id) AS total_rows FROM reactions where post_id=$post_id";

        // Execute the query
        $result = mysqli_query($cn, $sql);

        if ($result) {
            // Fetch the row as an associative array
            $row = mysqli_fetch_assoc($result);

            // Access the 'total_rows' column
            $totalRows = $row['total_rows'];

            // Display the number of rows
            return $totalRows;
        } else {
            // Handle query error
            return null;
            // echo "Error retrieving row count: " . mysqli_error($cn);
        }

        // Close the database connection
        mysqli_close($cn);
    }
    function getReactionNumber()
    {
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        if (!$cn) {
            die('Database connection failed: ' . mysqli_connect_error());
        }

        $sql = "SELECT COUNT(DISTINCT user_id, post_id) AS total_rows FROM reactions";

        // Execute the query
        $result = mysqli_query($cn, $sql);

        if ($result) {
            // Fetch the row as an associative array
            $row = mysqli_fetch_assoc($result);

            // Access the 'total_rows' column
            $totalRows = $row['total_rows'];

            // Display the number of rows
            return $totalRows;
        } else {
            // Handle query error
            return null;
            // echo "Error retrieving row count: " . mysqli_error($cn);
        }

        // Close the database connection
        mysqli_close($cn);
    }


    // function getreactionnumber(){
    //     require_once("config.php");
    //     $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
    //     if (!$cn) {
    //         die('Database connection failed: ' . mysqli_connect_error());
    //       }
    //     $sql = "SELECT COUNT(*) AS total_rows FROM reactions";

    //       // Execute the query
    //     $result = mysqli_query($cn, $sql);
    //     if ($result) {
    //         // Fetch the row as an associative array
    //         $row = mysqli_fetch_assoc($result);

    //         // Access the 'total_rows' column
    //         $totalRows = $row['total_rows'];

    //         // Display the number of rows
    //         return $totalRows;
    //       } else {
    //         // Handle query error
    //         return null;
    //         // echo "Error retrieving row count: " . mysqli_error($connection);
    //       }

    //       // Close the database connection
    //       mysqli_close($cn);
    // }

}
class admin extends Users
{
    public $role = 'admin';
    function showAllUsers()
    {
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);

        $qry = "SELECT users.username ,users.id,users.role,users.email,users.password
            FROM users  
            ";

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
    function showSpecificUsers($user_id)
    {
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);

        $qry = "SELECT users.username ,users.id,users.role,users.email,users.password
            FROM users  where users.id=$user_id
            ";

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
    function showpostdetailstoadmin($id)
    {
        require_once("config.php");
        $qry = "SELECT posts.content, posts.title, posts.image, posts.id,posts.created_at 
                FROM posts 
                where posts.user_id=$id ";
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
    function getUserCount()
    {
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);

        if (!$cn) {
            die('Database connection failed: ' . mysqli_connect_error());
        }

        $sql = "SELECT COUNT(*) AS total_rows FROM users";

        $result = mysqli_query($cn, $sql);

        if ($result) {
            // Fetch the row as an associative array
            $row = mysqli_fetch_assoc($result);

            // Access the 'total_rows' column
            $totalRows = $row['total_rows'];

            // Display the number of rows
            return $totalRows;
        } else {
            // Handle query error
            return null;
            // echo "Error retrieving row count: " . mysqli_error($cn);
        }

        // Close the database connection
        mysqli_close($cn);
    }

    function getReactionNumberpost($post_id)
    {
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        if (!$cn) {
            die('Database connection failed: ' . mysqli_connect_error());
        }

        $sql = "SELECT COUNT(DISTINCT user_id, post_id) AS total_rows FROM reactions where post_id=$post_id";

        // Execute the query
        $result = mysqli_query($cn, $sql);

        if ($result) {
            // Fetch the row as an associative array
            $row = mysqli_fetch_assoc($result);

            // Access the 'total_rows' column
            $totalRows = $row['total_rows'];

            // Display the number of rows
            return $totalRows;
        } else {
            // Handle query error
            return null;
            // echo "Error retrieving row count: " . mysqli_error($cn);
        }

        // Close the database connection
        mysqli_close($cn);
    }
    function getUsernamesByPost($postId)
    {
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);

        if (!$cn) {
            die('Database connection failed: ' . mysqli_connect_error());
        }

        $sql = "SELECT DISTINCT u.username FROM users u 
                INNER JOIN reactions r ON u.id = r.user_id
                WHERE r.post_id = $postId";

        $result = mysqli_query($cn, $sql);

        $usernames = array();

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $usernames[] = $row['username'];
            }
        }

        mysqli_close($cn);

        return $usernames;
    }
    function showpostcomments($id)
    {
        $qry = "SELECT c.*, u.username FROM comments AS c
                INNER JOIN users AS u ON c.user_id = u.id
                WHERE c.posts_id = '$id'";
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        $result = mysqli_query($cn, $qry);

        $data = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        mysqli_close($cn);
        return $data;
    }
    function showpostdetails($id)
    {
        require_once("config.php");
        $qry = "SELECT posts.content, posts.title, posts.image, posts.id,posts.created_at,posts.user_id 
                FROM posts 
                where posts.id=$id ";
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
    function editpost($image, $content, $title, $id)
    {
        $sql = "UPDATE posts SET title='$title', content='$content', image='$image' WHERE posts.id=$id";
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        $result = mysqli_query($cn, $sql);
        mysqli_close($cn);
        return $result;
    }
    function showallmyposts()
    {
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);

        $qry = "SELECT id,posts.content, posts.title, posts.image, posts.created_at 
            FROM posts  
            WHERE posts.user_id = '$this->id' 
            ORDER BY posts.created_at DESC";

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
    function add_reaction_to_post($post_id)
    {
        $user_id = $this->id;
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);

        // Check if the same user_id and post_id combination already exists in the table
        $checkSql = "SELECT COUNT(*) AS existing_rows FROM reactions WHERE post_id = $post_id AND user_id= $user_id";
        $checkResult = mysqli_query($cn, $checkSql);
        if ($checkResult) {
            $checkRow = mysqli_fetch_assoc($checkResult);
            $existingRows = $checkRow['existing_rows'];

            // If the combination exists, do not add a new row
            if ($existingRows > 0) {
                mysqli_close($cn);
                return false;
            }
        } else {
            // Handle query error
            mysqli_close($cn);
            return null;
        }

        $sql = "INSERT INTO reactions (post_id, user_id) VALUES ($post_id, $user_id)";
        $result = mysqli_query($cn, $sql);
        mysqli_close($cn);
        return $result;
    }
    function delete_reaction_to_post($posts_id)
    {
        $user_id = $this->id;
        $qry = "DELETE FROM reactions where user_id=$user_id and post_id=$posts_id";
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        $result = mysqli_query($cn, $qry);
        mysqli_close($cn);
        return $result;
    }
    function deletePost($id)
    {
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);

        if (!$cn) {
            die('Database connection failed: ' . mysqli_connect_error());
        }

        // Delete reactions associated with the post
        $deleteReactionsQuery = "DELETE FROM reactions WHERE post_id = $id";
        $result = mysqli_query($cn, $deleteReactionsQuery);

        if ($result) {
            // Reactions deleted successfully, proceed with deleting the post
            $deletePostQuery = "DELETE FROM posts WHERE id = $id";
            $deletePostResult = mysqli_query($cn, $deletePostQuery);

            if ($deletePostResult) {
                mysqli_close($cn);
                return true;
            } else {
                echo "Error deleting post: " . mysqli_error($cn);
            }
        } else {
            echo "Error deleting reactions: " . mysqli_error($cn);
        }

        mysqli_close($cn);
        return false;
    }
    function deletecommet($id)
    {
        $qry = "DELETE FROM comments where id=$id";
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        $result = mysqli_query($cn, $qry);
        mysqli_close($cn);
        return $result;
    }
    function addcomment($content, $posts_id)
    {
        $user_id = $this->id;
        $sql = "INSERT INTO comments (content, user_id,posts_id) VALUES ('$content', '$user_id','$posts_id')";
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        $result = mysqli_query($cn, $sql);
        mysqli_close($cn);
        return $result;
    }
    function getpostsNumber()
    {
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        if (!$cn) {
            die('Database connection failed: ' . mysqli_connect_error());
        }
    
        $sql = "SELECT COUNT(*) AS total_rows FROM posts";
    
        // Execute the query
        $result = mysqli_query($cn, $sql);
    
        if ($result) {
            // Fetch the row as an associative array
            $row = mysqli_fetch_assoc($result);
    
            // Access the 'total_rows' column
            $totalRows = $row['total_rows'];
    
            // Display the number of rows
            return $totalRows;
        } else {
            // Handle query error
            return null;
            // echo "Error retrieving row count: " . mysqli_error($cn);
        }
    
        // Close the database connection
        mysqli_close($cn);
    }
    function getpostsNumberperuser($userId)
    {
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        if (!$cn) {
            die('Database connection failed: ' . mysqli_connect_error());
        }
    
        $sql = "SELECT COUNT(*) AS total_rows FROM posts where posts.user_id=$userId";
    
        // Execute the query
        $result = mysqli_query($cn, $sql);
    
        if ($result) {
            // Fetch the row as an associative array
            $row = mysqli_fetch_assoc($result);
    
            // Access the 'total_rows' column
            $totalRows = $row['total_rows'];
    
            // Display the number of rows
            return $totalRows;
        } else {
            // Handle query error
            return null;
            // echo "Error retrieving row count: " . mysqli_error($cn);
        }
    
        // Close the database connection
        mysqli_close($cn);
    }
    function getcommentsNumber()
    {
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        if (!$cn) {
            die('Database connection failed: ' . mysqli_connect_error());
        }
    
        $sql = "SELECT COUNT(*) AS total_rows FROM comments";
    
        // Execute the query
        $result = mysqli_query($cn, $sql);
    
        if ($result) {
            // Fetch the row as an associative array
            $row = mysqli_fetch_assoc($result);
    
            // Access the 'total_rows' column
            $totalRows = $row['total_rows'];
    
            // Display the number of rows
            return $totalRows;
        } else {
            // Handle query error
            return null;
            // echo "Error retrieving row count: " . mysqli_error($cn);
        }
    
        // Close the database connection
        mysqli_close($cn);
    }
    function getreactionNumber()
    {
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        if (!$cn) {
            die('Database connection failed: ' . mysqli_connect_error());
        }
    
        $sql = "SELECT COUNT(*) AS total_rows FROM reactions";
    
        // Execute the query
        $result = mysqli_query($cn, $sql);
    
        if ($result) {
            // Fetch the row as an associative array
            $row = mysqli_fetch_assoc($result);
    
            // Access the 'total_rows' column
            $totalRows = $row['total_rows'];
    
            // Display the number of rows
            return $totalRows;
        } else {
            // Handle query error
            return null;
            // echo "Error retrieving row count: " . mysqli_error($cn);
        }
    
        // Close the database connection
        mysqli_close($cn);
    }
    function getcommentsNumberperuser($userId)
    {
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        if (!$cn) {
            die('Database connection failed: ' . mysqli_connect_error());
        }
    
        $sql = "SELECT COUNT(*) AS total_rows FROM comments where comments.user_id=$userId";
    
        // Execute the query
        $result = mysqli_query($cn, $sql);
    
        if ($result) {
            // Fetch the row as an associative array
            $row = mysqli_fetch_assoc($result);
    
            // Access the 'total_rows' column
            $totalRows = $row['total_rows'];
    
            // Display the number of rows
            return $totalRows;
        } else {
            // Handle query error
            return null;
            // echo "Error retrieving row count: " . mysqli_error($cn);
        }
    
        // Close the database connection
        mysqli_close($cn);
    }

    function getreactionsNumberperuser($userId)
    {
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        if (!$cn) {
            die('Database connection failed: ' . mysqli_connect_error());
        }
    
        $sql = "SELECT COUNT(*) AS total_rows FROM reactions where reactions.user_id=$userId";
    
        // Execute the query
        $result = mysqli_query($cn, $sql);
    
        if ($result) {
            // Fetch the row as an associative array
            $row = mysqli_fetch_assoc($result);
    
            // Access the 'total_rows' column
            $totalRows = $row['total_rows'];
    
            // Display the number of rows
            return $totalRows;
        } else {
            // Handle query error
            return null;
            // echo "Error retrieving row count: " . mysqli_error($cn);
        }
    
        // Close the database connection
        mysqli_close($cn);
    }
    function getcommentsNumberperpost($postId)
    {
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        if (!$cn) {
            die('Database connection failed: ' . mysqli_connect_error());
        }
    
        $sql = "SELECT COUNT(*) AS total_rows FROM comments where comments.posts_id=$postId";
    
        // Execute the query
        $result = mysqli_query($cn, $sql);
    
        if ($result) {
            // Fetch the row as an associative array
            $row = mysqli_fetch_assoc($result);
    
            // Access the 'total_rows' column
            $totalRows = $row['total_rows'];
    
            // Display the number of rows
            return $totalRows;
        } else {
            // Handle query error
            return null;
            // echo "Error retrieving row count: " . mysqli_error($cn);
        }
    
        // Close the database connection
        mysqli_close($cn);
    }
    function deleteuser($userId)
    {
        $qry = "DELETE FROM users where id=$userId";
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        $result = mysqli_query($cn, $qry);
        mysqli_close($cn);
        return $result;
    }
    function updateuser($userId)
    {
        $sql = "UPDATE users SET role='admin' WHERE users.id=$userId";
        require_once("config.php");
        $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);
        $result = mysqli_query($cn, $sql);
        mysqli_close($cn);
        return $result;
    }
    // function addpost()
    // {
    // }
    // function editpost()
    // {
    // }
    // function deletpost()
    // {
    // }
    // function showpost()
    // {
    // }
}
