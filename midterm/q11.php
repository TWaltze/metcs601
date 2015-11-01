<?php
if (array_key_exists('username', $_POST) && array_key_exists('password', $_POST)) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $dbUser = 'root';
    $dbPass = 'password';
    $dbName = 'midterm';
    $dbHost = 'localhost';
    $dbPort = 3306;
    $conn = "mysql:dbname=$dbName;host=$dbHost;port=$dbPort";

    try {
        $db = new PDO($conn, $dbUser, $dbPass);

        $query = $db->prepare('SELECT * FROM users WHERE `username` = :username AND `password` = :password');
        $query->bindValue(':username', $username);
        $query->bindValue(':password', $password);

        $query->execute();
        $rows = $query->fetch(PDO::FETCH_NUM);

        if (count($rows) > 0) {
            $message = "Successfully authenticated.";
            // Do success stuff
        } else {
            $message = "There was a problem and we could not authenticate you.";
        }
    } catch(PDOException $e) {
        // echo 'Could not connect to the database:<br/>' . $e;
        $message = "There was a problem and we could not authenticate you.";
    }
} else {
    $message = null;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Question 11</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            * {
                box-sizing: border-box;
            }

            html, body {
                font-family: sans-serif;
            }

            .wrapper {
                width: 800px;
                margin: 0 auto;
            }

            label {
                font-size: 1.3em;
                display: block;
            }

            input {
                padding: 10px;
                font-size: inherit;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <h1>Authenticate Yourself</h1>
            <form method="post">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Username" />

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" />
                <br />
                <input type="submit" value="Login" />
                <br />
                <span><?php echo $message; ?></span>
            </form>
        </div>
    </body>
</html>
