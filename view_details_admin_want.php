<?php
session_start();
if (empty($_SESSION["user"])) {
    header("location:unauth.php");
}
require_once("clases.php");
$user = unserialize($_SESSION["user"]);
// $posts2 = $user->showpost();
// var_dump($posts2);
if (isset($_GET['postId']) && is_numeric($_GET['postId'])) {
    $count = $user->getReactionNumberpost($_GET['postId']);
    $username = $user->getUsernamesByPost($_GET['postId']);
    $comments = $user->showpostcomments($_GET['postId']);
    $posts = $user->showpostdetails($_GET['postId']);
    $number = $user->getUserCount();
    $r_all=$user->getreactionNumber();

    $number_comments = $user->getcommentsNumber();
    $c_p_p=$user->getcommentsNumberperpost($_GET['postId']);
    // var_dump($c_p_p);

    $number_posts = $user->getpostsNumber();

    // var_dump($posts);
}
?>
<!doctype html>
<html lang="en" data-bs-theme="auto">

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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300&family=Mea+Culpa&family=Pacifico&family=Yesteryear&display=swap" rel="stylesheet">

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/dashboard/">
    <title>Offcanvas navbar template · Bootstrap v5.3</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/offcanvas-navbar/">





    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            width: 100%;
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .btn-bd-primary {
            --bd-violet-bg: #712cf9;
            --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

            --bs-btn-font-weight: 600;
            --bs-btn-color: var(--bs-white);
            --bs-btn-bg: var(--bd-violet-bg);
            --bs-btn-border-color: var(--bd-violet-bg);
            --bs-btn-hover-color: var(--bs-white);
            --bs-btn-hover-bg: #6528e0;
            --bs-btn-hover-border-color: #6528e0;
            --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
            --bs-btn-active-color: var(--bs-btn-hover-color);
            --bs-btn-active-bg: #5a23c8;
            --bs-btn-active-border-color: #5a23c8;
        }

        .bd-mode-toggle {
            z-index: 1500;
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="offcanvas-navbar.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check2" viewBox="0 0 16 16">
            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
        </symbol>
        <symbol id="circle-half" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z" />
        </symbol>
        <symbol id="moon-stars-fill" viewBox="0 0 16 16">
            <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z" />
            <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z" />
        </symbol>
        <symbol id="sun-fill" viewBox="0 0 16 16">
            <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z" />
        </symbol>
    </svg>

    <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
        <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
            <svg class="bi my-1 theme-icon-active" width="1em" height="1em">
                <use href="#circle-half"></use>
            </svg>
            <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                    <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em">
                        <use href="#sun-fill"></use>
                    </svg>
                    Light
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                    <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em">
                        <use href="#moon-stars-fill"></use>
                    </svg>
                    Dark
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
                    <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em">
                        <use href="#circle-half"></use>
                    </svg>
                    Auto
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
        </ul>
    </div>


    <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark" aria-label="Main navigation">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Offcanvas navbar</a>
            <button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Dashboard</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">LogOut</a>
                    </li>

                </ul>

            </div>
        </div>
    </nav>

    <div class="nav-scroller bg-body shadow-sm">
        <nav class="nav" aria-label="Secondary navigation">
            <a class="nav-link " aria-current="page" href="admin.php">Dashboard</a>
            <a class="nav-link active" href="admin.php">
                Users
                <?php
                require_once("clases.php");
                $user = unserialize($_SESSION["user"]);
                $number = $user->getUserCount();
                $number_comments = $user->getcommentsNumber();

                $number_posts = $user->getpostsNumber();
                ?>
                <span class="badge text-bg-light rounded-pill align-text-bottom "><?= $number ?></span>
            </a>
            <a class="nav-link active" href="#">
                Posts
                <span class="badge text-bg-light rounded-pill align-text-bottom "><?= $number_posts ?></span>
            </a>
            <a class="nav-link active" href="#">
                Comments
                <span class="badge text-bg-light rounded-pill align-text-bottom "><?= $number_comments ?></span>
            </a>
            <a class="nav-link active" href="#">
                Reactions
                <span class="badge text-bg-light rounded-pill align-text-bottom "><?= $r_all ?></span>
            </a>
            <!-- <a class="nav-link" href="#">Explore</a>
            <a class="nav-link" href="#">Suggestions</a>
            <a class="nav-link" href="#">Link</a>
            <a class="nav-link" href="#">Link</a>
            <a class="nav-link" href="#">Link</a>
            <a class="nav-link" href="#">Link</a>
            <a class="nav-link" href="#">Link</a> -->
        </nav>
    </div>

    <main class="container">




        <div class="row mb-2 d-flex justify-content-center mt-3">

            <?php
            require_once("clases.php");
            $user = unserialize($_SESSION["user"]);
            $number = $user->getUserCount();

            // $posts2 = $user->showpost();
            // var_dump($posts2);
            if (isset($_GET['postId']) && is_numeric($_GET['postId']) && isset($_GET['userId']) && is_numeric($_GET['userId'])) {
                $persons = $user->showSpecificUsers($_GET['postId']);
                $count = $user->getReactionNumberpost($_GET['postId']);
                $username = $user->getUsernamesByPost($_GET['postId']);
                $comments = $user->showpostcomments($_GET['postId']);
                $posts = $user->showpostdetails($_GET['postId']);
                $r_all=$user->getreactionNumber();

                $c_p_p=$user->getcommentsNumberperpost($_GET['postId']);

                $number = $user->getUserCount();
                // var_dump($counts);
                // var_dump($username);
            }
            foreach ($posts as $post) {

            ?>
                <div class="col-2"></div>
                <div class="col-8">
                    <div class="card shadow-sm">
                    
                        <img src="<?= $post['image'] ?>" alt="" width="100%" height="500px">
                        <!-- <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns=""  role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                                            
                                            <rect width="100%" height="100%" fill="#55595c" /><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text>
                                        </svg> -->
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?= $post['title'] ?></h4>
                                    <small class="d-block">Comments : <?= $c_p_p ?></small>

                                    <small class="d-block">Post_Id : <?= $post["id"] ?></small>
                                    <p class="card-text"><?= $post['content'] ?></p>
                                </div>
                                <div class="d-flex flex-column">

                                    <!-- <button type="button" class="btn btn-sm btn-outline-secondary" >View</button> -->
                                    <!-- <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button> -->
                                    <small class="text-body-secondary"><?= $post['created_at'] ?></small>
                                    <small>total reaction on post:<?= $count ?> </small>
                                </div>
                            </div>


                            <div class="d-flex justify-content-between">
                                <div class="d-flex justify-content-between">
                                    <div>


                                        <a href="handle_add_reaction_from_post_admin.php?postId=<?= $post['id'] ?>&userId=<?= $post['user_id'] ?>" class="me-4" style="text-decoration: none;">
                                            <span class="material-symbols-outlined">
                                                thumb_up
                                            </span>
                                        </a>


                                        <h6>like</h6>
                                    </div>
                                    <div>
                                        <a href="handle_delete_reaction_from_post_admin.php?postId=<?= $post['id'] ?>&userId=<?= $post['user_id'] ?>" class="me-4" style="text-decoration: none;">
                                            <span class="material-symbols-outlined">
                                                thumb_down
                                            </span>
                                        </a>
                                        <h6>unlike</h6>
                                    </div>

                                </div>



                            </div>
                            <!-- <p id="myParagraph" class="card-text"></p> -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a href="delete_post_admin.php?postId=<?= $post['id'] ?>&userId=<?= $post['user_id'] ?>" class="btn btn-sm btn-outline-secondary">Delete</a>


                                    <!-- <button type="button" class="btn btn-sm btn-outline-secondary" >View</button> -->
                                    <!-- <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button> -->
                                </div>


                            </div>
                        </div>
                        <hr>
                        <?php
                        require_once("clases.php");
                        $user = unserialize($_SESSION["user"]);
                        $number = $user->getUserCount();

                        // $posts2 = $user->showpost();
                        // var_dump($posts2);
                        if (isset($_GET['postId']) && is_numeric($_GET['postId']) && isset($_GET['userId']) && is_numeric($_GET['userId'])) {
                            $persons = $user->showSpecificUsers($_GET['postId']);
                            $count = $user->getReactionNumberpost($_GET['postId']);
                            $username = $user->getUsernamesByPost($_GET['postId']);
                            $comments = $user->showpostcomments($_GET['postId']);
                            $posts = $user->showpostdetails($_GET['postId']);
                            $r_all=$user->getreactionNumber();

                            $c_p_p=$user->getcommentsNumberperpost($_GET['postId']);

                            $number = $user->getUserCount();
                            // var_dump($counts);
                            // var_dump($username);
                        }
                        foreach ($comments as $comment) {
                        ?>
                            <!-- <div class="col-2"></div> -->
                            <div class="col-12">
                                <div class="">
                                    <div class="card-body d-flex justify-content-between">
                                        <div>
                                            <p class="card-text pt-2"><?= $comment["content"] ?></p>
                                        </div>
                                        <div class="d-flex">
                                            <div>

                                                <p class="card-text pt-2">By <?= $comment["username"] ?></p>
                                            </div>
                                            <div>
                                                <a href="handle_delete_comment_admin.php?postId=<?= $post['id'] ?>&userId=<?= $post['user_id'] ?>&Id=<?= $comment['id'] ?>" class="btn btn-sm btn-info">Delete</a>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-2"></div> -->
                        <?php
                        }
                        ?>
                        <hr>
                        <?php
                        require_once("clases.php");
                        $user = unserialize($_SESSION["user"]);
                        $number = $user->getUserCount();

                        // $posts2 = $user->showpost();
                        // var_dump($posts2);
                        if (isset($_GET['postId']) && is_numeric($_GET['postId']) && isset($_GET['userId']) && is_numeric($_GET['userId'])) {
                            $count = $user->getReactionNumberpost($_GET['postId']);
                            $username = $user->getUsernamesByPost($_GET['postId']);
                            $comments = $user->showpostcomments($_GET['postId']);
                            $posts = $user->showpostdetails($_GET['postId']);
                            $r_all=$user->getreactionNumber();

                            $c_p_p=$user->getcommentsNumberperpost($_GET['postId']);

                            $number = $user->getUserCount();

                            // var_dump($posts);
                        }
                        ?>
                        <form id="addPostForm" action="handleaddcommentadmin.php" method="POST">
                            <div class="container mt-3">
                                <div class="row">

                                    <div class="col-6">
                                        <div class="mb-3">
                                            <p class="addcomment">ADD COMMENT</p>

                                            <hr>

                                        </div>

                                        <div class="mb-3">
                                            <label for="exampleFormControlTextarea1" class="form-label">Content</label>
                                            <textarea class="form-control" name="content" id="exampleFormControlTextarea1" rows="3" type="longtext"></textarea>
                                        </div>
                                        <input type="hidden" class="form-control" name="id" id="exampleFormControlInput1" onchange="previewImage(event)" value=" <?= $_GET['postId'] ?>">
                                        <div id="imagePreview"></div>
                                        <div class="mb-3">
                                            <input type="submit" class="btn btn-primary" value="ADD">
                                        </div>
                                        
                                    </div>
                                    <div class="col-3"></div>
                                    <a class="nav-link active btn btn-primary" style="text-align: center;" href="view_user_from_admin.php?userId=<?= $post['user_id'] ?>">
                Pervious Page
                <span class="badge text-bg-light rounded-pill align-text-bottom "></span>
            </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-2"></div>
            <?php
            }
            ?>
        </div>
    </main>
    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

    <script src="offcanvas-navbar.js"></script>
</body>

</html>