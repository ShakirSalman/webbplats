<?php



$database = [
    'host' => 'localhost',
    'name' => '_testdb',
    'user' => 'root',
    'password' => 'mysql'
];

function initDatabase($database)
{
    try {
        return new PDO("mysql:host={$database['host']};dbname={$database['name']}", $database['user'], $database['password']);

    } catch (PDOException $e) {
        var_dump($e->getMessage());
        echo 'connect to db is not ok';
    }
}

# Skapa en users tabell i databasen och lägg till (iaf) följande:
# kolumn    | tips ↓

# id        | primary key auto increment
# username  | bör vara unikt?
# email     | ska bara unikt
# password  | gör inte för kort då hashen kan bil lång (255 blir bra).
# created   | (detta ska vara datum fält)

$pdo = initDatabase($database); 
$email = $_SESSION['email'];
function getAllUsernames($email, $pdo){
    $query = "SELECT username FROM users WHERE email != '$email'";
    $statement = $pdo->prepare($query);

    return $statement;
}

function fetchFromDatabase($statement){

    $statement->execute();

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

class Post 
{
    private $error ="";

    public function create_post($pdo,$user_id, $data)
    {
        if (!empty($data['post'])) {
            $post = addslashes($data['post']);
            $postid = $this->create_postid();         
            $query = "INSERT INTO posts (post_id,user_id,post) VALUES ('$postid','$user_id','$post')";
            $statement = $pdo->prepare($query);
            $statement->execute();
        } else {
            $this->error ="Please type something to post";
        }
        return $this->error;
    }

    public function get_post($pdo)
    {
        $query ="SELECT * FROM posts order by id desc limit 10";
        $result = $pdo->prepare($query);
        $result->execute();
        
    

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }


    private function create_postid() 
    {
        $length = rand(4,19);
        $number = "";
        for ($i=0; $i < $length; $i++) {
            $new_rand = rand(0,9);
            $number = $number . $new_rand;
        }
        return $number;
    }
}


function get_user($pdo,$id)
{
    $query ="SELECT * FROM users WHERE user_id = '$id' limit 1";
    $result = $pdo->prepare($query);
    $result->execute();
    
    if ($result) {
        return $result->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}

function search_user($pdo,$searchUser){
$query = "SELECT * FROM users where username like '$searchUser'";
$statement = $pdo->prepare($query);
$result = fetchFromDatabase($statement);
return $result;
}
