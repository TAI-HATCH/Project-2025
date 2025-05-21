<?php
session_start();

$correct_password = 'openTheHatch';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['password'] === $correct_password) {
        $_SESSION['is_admin'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $pwerror = "Incorrect password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HATCH - Quiz</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap"
        rel="stylesheet" />


    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
   
    
        
        <div class="admin-login">
            <p>Log in as admin</p>
            <form method="POST">
                <div>
                    <input class="admin-password" type="password" name="password" placeholder="Password" required>
                    <?php if (isset($pwerror)) echo "<h5 class='pwerror'>$pwerror</h5>"; ?>
                </div>
                <div>
                    <button class="login-button" type="submit" value="Kirjaudu sisään">Log in</button>
                </div>
            </form>
        </div>
</body>
