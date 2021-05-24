<?php
require 'header.php';
require_once 'database.php';

# Först kollar vi om ändra epost formuläret är skickat, samt om vår CSRF-token stämmer.
# Om så är fallet så försöker vi uppdatera informationen i databasen.
# Vi gör en koll så vår execute() gick igenom och uppdaterar isåfall vår SESSION data.

# I annat fall ( else{} ), så skapar vi en token och token-expire i Session.

if (isset($_POST['submit']) && $_SESSION['token'] == $_POST['token']) {
    if (time() >= $_SESSION['token-expire']) {
        exit("Token expired. Please reload form.");
    }

    $email = $_POST['email'];
    $username = $_SESSION['username'];

    $connection = initDatabase($database);
    $statement = $connection->prepare("UPDATE users SET email = :email WHERE username = :username");
    $statement->bindParam(':email', $email);
    $statement->bindParam(':username', $username);

    if ($statement->execute()) {
        $_SESSION['email'] = $email;
    }
} else {
    $_SESSION['token'] = bin2hex(random_bytes(32));
    $_SESSION['token-expire'] = time() + 600;
}

//posting profile_image starts here
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    

    // print_r($_POST);
    //print_r($_FILES);

    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != " ") {

        $filename = "uploads/" . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $filename);

        $iD = $_SESSION['user_id'];

        if (file_exists($filename)) {

            $query = "UPDATE users SET profile_image = '$filename' WHERE user_id = '$iD' limit 1";
            $statement = $pdo->prepare($query);
            $statement->execute();
        }

    }else{
        echo "<div>";
        // echo "<br>Thr following errors occured:<br><br>";
        echo "Please add a valid image";
        echo "</div>";
   }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Change Profile</h2>
        </div>
        <form action="profile.php" method="post">
            <div class="shadow overflow-hidden sm:rounded-md px-4 py-5 bg-white sm:p-6">
            <h2 class="mt-6 text-center font-extrabold text-gray-900">Change Email</h2>
                <label for="email" class="block text-sm font-medium text-gray-700">Email: </label>
                <input type="email" name="email" id="email" class="mt-1 p-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border border-gray-400 rounded-md" />
                <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>" />
                <button type="submit" name="submit" class="mt-2 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Spara</button>
            </div>
        </form>
        <div class="shadow overflow-hidden sm:rounded-md px-4 py-5 bg-white sm:p-6">
            <h2 class="mt-6 text-center  font-extrabold text-gray-900">Change Profile Image</h2>
            <form method="post" enctype="multipart/form-data">
                <input type="file" name="file">
                <input id="post_button"type="submit" value="Change"class="mt-2 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            </form>
        </div>




    </body>
</html>