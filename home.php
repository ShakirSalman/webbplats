<?php
require 'header.php';
require 'database.php';


// posting starts here
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $post = new Post();
    $results = $post->create_post($pdo, $_SESSION['user_id'], $_POST);

    if ($results ==" ") {
        header("Location: header.php");
        die;
    } else {
        
        echo "<div>";
        // echo "<br>Thr following errors occured:<br><br>";
        echo $results;
        echo "</div>";
   }
    
}

// collect post
$post = new Post();
$id = $_SESSION['user_id'];
$posts = $post->get_post($pdo);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_SESSION['username'] ?></title>
</head>
<body style="font-family: tahoma;background:#d0d8e4;">
    <div style="width: 800px;margin:auto;min-height:200px">
        <div style="background-color: while;text-align:center;color:#405d9b">

            <a href="profile.php">
                <?php
 
                    $image = "";
                    if (file_exists($_SESSION['profile_image']))
                    {
                        $image = $_SESSION['profile_image'];
                        
                    }

                ?>
                <img src="<?php echo $image ?>" id="profile_pic">
            </a>
            <h2 class="mt-6 text-center text-3xl font-extrabold"style="margin-top: 0px; color: black"><?php echo $_SESSION['username'] ?></h2>
        </div>
    </div>
    <form action="users.php" method="GET">
    <input style="border: solid thin #aaa;padding:5px; background-color:white" id="search" type="text" name="username"placeholder="Search after friend">
    <input class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium"     id="submit" type="submit" value="Search">
    </form>

    <div style="display: flex;width: 800px;margin:auto; ">

        <!-- friends area -->
        <div style="min-height: 400px;flex:1.5">
            <div id="friends_bar">
               <h2 style="font:bolder;font-size: x-large;color: darkslategray;font-weight: 600;">Friends</h2>
                <div >
                    <?php $users = getAllUsernames($email, $pdo);
                    $results = fetchFromDatabase($users); ?>

                    <?php foreach ($results as $users) : ?>
                        <a href="http://127.0.0.1/Webbplats/users.php?username=<?= $users['username'] ?>">
                    <p style="font:bolder;color: darkslategray;font-weight: 600;" ><?= $users['username']?></p></a>
                    <?endforeach; ?>
                </div>
                <?php
                    if($friends)
                    {
                        foreach ($friends as $FRIEND_ROW)
                        {
                            include("user.php");
                        }

                    }
                    ?>
            </div>

        </div>
        <!-- posts area -->
        <div style="min-height: 400px;flex:3.5; padding:20px;padding-right:0px ">
                    
            <div style="border: solid thin #aaa;padding:10px; background-color:white ;min-height: 200px ">
                <form method="post">
                <textarea name ="post" placeholder="whats on your mind?" style="width: 100%;min-height: 133px"></textarea>
                <input class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium" style="float:right" id="post_button" type="submit" value="post ">
                <br>
                </form>
            </div>
            <div id="post">
                <?php

                    if($posts)
                    {
                        foreach ($posts as $row) {
                            $USER= get_user($pdo,$row['user_id']);
                            include("post.php");
                        }
                    }             
                ?>
                
            </div>

    
    
        </div>


    </div>

</div>
</body>
</html>