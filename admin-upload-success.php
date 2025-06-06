<?php
session_start();

// Jos ei admin, ohjataan takaisin kirjautumissivulle
if (!isset($_SESSION['is_admin'])) {
    header("Location: adminpw.php");
    exit;
}

include 'admin-banner.php';

// Käsittele uloskirjautuminen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: adminpw.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to HATCH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <h1 hidden>HATCH</h1>

    <?php
    if (isset($_SESSION['is_admin'])) {
    ?>
        <form method="POST">
            <button class="logout-button" type="submit" name="logout">Log out</button>
        </form>
    <?php
    }
    ?>

    <?php include 'admin-header.php' ?>

    <section class="root-content">
        <div class="admin-upload-success">
        <p>Upload successful.</p>
        <a class="button" href="admin-start.php">To admin start page</a>
        </div>
    </section>