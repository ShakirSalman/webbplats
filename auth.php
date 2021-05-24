<?php
# Här tar vi emot uppgifter från inloggningsformuläret.
# Vi kollar den datan mot databasen och om allt stämmer sparas information i SESSION
# och man skickas vidare till home.php


session_start();

require_once 'database.php';

$connection = initDatabase($database);

$statement = $connection->prepare("SELECT * FROM users WHERE email = :email limit 1");
$statement->bindParam('email', $_POST['email']);
$statement->execute();

$user = $statement->fetch(PDO::FETCH_ASSOC);
# password verify kollar lösenordet som kommer in mot det hashade.
# Ni kan läsa mer om metoden här: https://www.php.net/manual/en/function.password-verify.php

if(password_verify($_POST['password'], $user['password'])){
    session_regenerate_id();
    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['profile_image'] = $user['profile_image'];

    
    
    header('Location: home.php');
} else {
    header('Location: index.html');
}
