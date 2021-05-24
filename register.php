<?php
    require 'database.php';

# Ta emot och validera data från registreringsformuläret.

# Skapa en ny användare i databasen, tänk på följande:
# 1) Lösenordet behöver hashas, se:
#       https://www.php.net/manual/en/function.password-hash.php
# 2) epost kolumnen ska vara unik (kanske även username)
# 3) fyll datum/tid i created när datan sparas. Tips: detta kan göras på databas nivå!

# När användaren sparats i databasen, sätt hen som inloggad eller skicka till inloggningssidan.
# Om hen sätts som inloggad, behöver man redirecta hen till t.ex. home.php.

if(isset($_POST['username'])){
    $pdo = initDatabase($database);
    $email=$_POST['email'];
    $username=$_POST['username']; 
    $pass=$_POST['password'];
    $password = password_hash($pass, PASSWORD_DEFAULT);

    getAllUsersVal($pdo ,$email, $username, $password);
}


function registerUsers($pdo, $username, $email, $password){
    
    $user_id = create_rand();
    
    $statement = $pdo->prepare("INSERT INTO users (user_id, username, email, password) VALUES ('$user_id',  '$username', '$email', '$password')");
    $statement->execute();
    echo 'Registaration is done!'. "<br />";
    return $statement;
}

function getAllUsersVal($pdo ,$email, $username, $password){
    $email1 = $email;
    $existeEmail=$pdo->query("SELECT email FROM users WHERE email = '$email'");
    if(!$email1== $existeEmail->fetch()){
        registerUsers($pdo, $username, $email, $password);
    }
    else{
        echo "The email is already existed";
}
}

function create_rand() {
$today = date('YmdHi');
$startDate = date('YmdHi', strtotime('-10 days'));
$range = $today - $startDate;
$rand = rand(0, $range);
return ($rand);
}