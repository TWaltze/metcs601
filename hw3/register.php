<?php
require('db.php');

$errors = [];
if (array_key_exists('email', $_POST) && array_key_exists('password', $_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = $db->prepare("SELECT * FROM users WHERE AES_DECRYPT(email, 'sugar') = :email");
    $query->bindValue(':email', $email);

    $query->execute();
    $rows = $query->fetch(PDO::FETCH_NUM);

    if ($rows > 0) {
        $errors[] = "This email is already being used.";
    }

    if (!$email || !$password) {
        $errors[] = "All fields are required.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "This is an invalid email address";
    }

    if (count($errors) === 0) {
        $query = $db->prepare("INSERT INTO users (email, password) VALUES (AES_ENCRYPT(:email, 'sugar'), AES_ENCRYPT(:password, 'sugar'))");
        $query->bindValue(':email', $email);
        $query->bindValue(':password', $password);

        $query->execute();
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Homework 3 - Registration</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
        <link rel="stylesheet" href="main.css">

        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    </head>
    <body>
        <div class="wrapper">
            <h1>Create a New Account</h1>
            <form method="post">
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <input type="submit" value="Register" />
                <div class="error">
                    <?php
                        foreach ($errors as $key => $error) {
                            echo $error . '<br>';
                        }
                    ?>
                </div>
            </form>
        </div>
    </body>
</html>
