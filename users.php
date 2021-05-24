<?php

    require 'header.php';
    include 'database.php';

    $result = search_user($pdo,$_GET['username']);

    while ($row == $result) {
    echo "<div id='link' onClick='addText(\"".$row['username']."\");'>" . $row['username'] . "</div>";  
    }

    // for Messaging 
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {   
        $user_id = $_SESSION['user_id'];
        $receive_id = $result[0]["user_id"];

        if (!empty($_POST['message'])) {
            $message = addslashes($_POST['message']);  
            
            
            $query = "INSERT INTO messages (send_id, receive_id,message) VALUES ('$user_id','$receive_id','$message')";
            $statement = $pdo->prepare($query);
            $statement->execute();
        } else {
             echo "Please type something to message";
        } 
    }

    // collect messages
    $sendid = $_SESSION['user_id'];

    $query ="SELECT * FROM messages WHERE (receive_id = '$receive_id' and send_id = '$sendid') OR (receive_id = '$sendid' and send_id ='$receive_id' ) order by id desc limit 10";
    $statement = $pdo->prepare($query);
    $res = fetchFromDatabase($statement);

    $messages = $res;
    $send_id = $res[0]['send_id'];

   
    
   ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $_SESSION['username'] ?></title>
    </head>
    <body style="font-family: tahoma;background:#d0d8e4;">
        <div style="width: 800px;margin:auto;min-height:100px">
            <div style="background-color: while;text-align:center;color:#405d9b">
                <img src="<?php echo $result[0]['profile_image']?>" id="profile_pic">
                <h2 class="mt-6 text-center text-3xl font-extrabold"style="margin-top: 0px; color: black"><?php echo $result[0]["username"] ?></h2>
            </div>
        </div>
        <!-- Massage area -->
        <div style="min-height: 400px;flex:3.5; padding:20px;padding-right:0px ">        
            <div style="border: solid thin #aaa;padding:10px; background-color:white ;min-height: 200px ">
                <form method="post">
                <textarea name ="message" placeholder="Type a massage" style="width: 100%;min-height: 133px"></textarea>
                <input class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium" style="float:right" id="post_button" type="submit" value="Send">
                <br>
                </form>
            </div>
            <div id="message">
                <?php

                    if($messages)
                    {
                        foreach ($messages as $row) {
                            $USER= get_user($pdo,$row['send_id']);
                            include("message.php");
                        }
                    }             
                ?>
                
            </div>
    </body>
</html>