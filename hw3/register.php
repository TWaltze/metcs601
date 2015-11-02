<?php
session_start();

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

        $_SESSION['email'] = $email;
    }
} else if (array_key_exists('newPassword', $_POST)) {
    $query = $db->prepare("UPDATE users SET password = AES_ENCRYPT(:password, 'sugar') WHERE AES_DECRYPT(email, 'sugar') = :email");
    $query->bindValue(':email', $_SESSION['email']);
    $query->bindValue(':password', $_POST['newPassword']);

    $query->execute();
}

if (array_key_exists('email', $_SESSION)) {
    $query = $db->prepare("SELECT AES_DECRYPT(password, 'sugar') as password FROM users WHERE AES_DECRYPT(email, 'sugar') = :email");
    $query->bindValue(':email', $_SESSION['email']);

    $query->execute();

    $row = $query->fetch();
    $currentPassword = $row['password'];
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

            <?php if (array_key_exists('email', $_SESSION)) { ?>
            <h1>Reset Password</h1>
            <form method="post">
                <input type="password" name="newPassword" placeholder="New Password" required />
                <input type="submit" value="Reset" />
            </form>

            <h1>
                View password <input type="checkbox" id="viewPassword" />
                <span id="password">
                    <?php echo $currentPassword; ?>
                </span>
            </h1>
            <?php } ?>
        </div>
    </body>
</html>
