<!DOCTYPE html>
<html lang="en">

<head>
    <script src="assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.112.5">
    <title>Dashboard Template · Bootstrap v5.3</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300&family=Mea+Culpa&family=Pacifico&family=Yesteryear&display=swap" rel="stylesheet">

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/dashboard/">





    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .reaction-icon {
            position: relative;
            display: inline-block;
        }

        .icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            background-image: url('path/to/icon.png');
            background-size: cover;
            cursor: pointer;
        }

        .tooltip {
            position: relative;
        }

        .tooltip .dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            width: 200px;
            padding: 10px;
            background-color: #000;
            color: #fff;
            display: none;
        }

        .icon:hover+.tooltip .dropdown {
            display: block;
        }
    </style>
</head>

<body>
    <div class="reaction-icon">
        <span class="material-symbols-outlined icon" data-postid="<?php echo $postId; ?>">thumb_down</span>
        <div class="tooltip">
            <?php
            // Get the usernames of all users who made a reaction
            function getUsernamesByPost($postId)
            {
                require_once("config.php");
                $cn = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PW, DB_NAME);

                if (!$cn) {
                    die('Database connection failed: ' . mysqli_connect_error());
                }

                $sql = "SELECT username FROM users WHERE id IN (SELECT user_id FROM reactions WHERE post_id = $postId)";
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
            $postID = 37;
            $usernames = getUsernamesByPost($postID);
            $usernamesString = implode(', ', $usernames);

            

            echo $usernamesString;
            ?>
            <div class="dropdown">
                <!-- Dropdown content here -->
            </div>
        </div>
    </div>



    

    <script>
        // Get the tooltip and dropdown elements
        var tooltip = document.querySelector('.tooltip');
        var dropdown = tooltip.querySelector('.dropdown');

        // Show the dropdown when hovering over the icon
        var icon = document.querySelector('.icon');
        icon.addEventListener('mouseenter', function() {
            dropdown.style.display = 'block';
        });

        // Hide the dropdown when moving the mouse out of the icon
        icon.addEventListener('mouseleave', function() {
            dropdown.style.display = 'none';
        });
    </script>



</body>

</html>