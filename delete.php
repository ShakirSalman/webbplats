<?php

include "database.php"; // Using database connection file here

$id = $_GET['id']; // get id through query string

$query ="DELETE FROM messages WHERE id = '$id' limit 1";
$result = $pdo->prepare($query);
$result->execute();

if($result)
{
   echo "deleting is done";
   echo "<br>";
}
else
{
    echo "Error in deleting ";
}
?>
<a href='./home.php'>Back</a>