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

    <?php include 'header.php' ?>

    <section class="root-content">
        <div class="admin-navigate">
            <a class="admin-start-action" href="admin-question.php">
                <div class="admin-start-action">
                     <svg class="admin-start-svg-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24">
                        <path d="m13.679,15.632c-.716.331-1.179,1.119-1.179,2.008v1.36h-1v-1.36c0-1.277.69-2.422,1.759-2.916,1.34-.62,
                        2.013-2.072,1.637-3.53-.26-1.008-1.08-1.827-2.087-2.088-.938-.242-1.901-.054-2.643.521-.741.573-1.166,1.438-1.166,
                        2.374h-1c0-1.247.566-2.4,1.553-3.165s2.263-1.02,3.505-.698c1.354.351,2.456,1.452,2.806,2.807.499,1.934-.4,3.861-2.186,
                        4.688Zm-1.679,5.368c-.552,0-1,.448-1,1s.448,1,1,1,1-.448,
                        1-1-.448-1-1-1Zm5.002-13.978c-1.132-3.019-4.003-5.022-7.252-5.022-4.273,0-7.75,
                        3.477-7.75,7.75,0,.763.116,1.523.345,2.267-1.476,1.032-2.345,2.681-2.345,4.483,0,3.032,
                        2.467,5.5,5.5,5.5h3.5v-1h-3.5c-2.481,0-4.5-2.019-4.5-4.5,0-1.582.822-3.021,
                        2.2-3.847l.354-.212-.141-.388c-.273-.752-.412-1.527-.412-2.304,0-3.722,3.028-6.75,6.75-6.75,2.931,0,5.51,1.873,6.417,
                        4.661l.11.339.355.007c3.511.071,6.368,2.984,6.368,6.493,0,3.584-2.916,6.5-6.5,6.5h-1.5v1h1.5c4.136,0,7.5-3.364,7.5-7.5,
                        0-3.933-3.108-7.216-6.998-7.478Z"/>
                    </svg>
                    <label for="add-question">Add question</label>
                </div>
            </a>
            
           <a class="admin-start-action" href="admin-add-language.php">
                <div class="admin-start-action">
                     <svg class="admin-start-svg-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24">
                       <path xmlns="http://www.w3.org/2000/svg" 
                       d="m11.026,23H2.5c-.827,0-1.5-.673-1.5-1.5s.673-1.5,1.5-1.5h6.875c-.1-.325-.174-.66-.235-1h-4.14S5,1,5,1h13.5c.275,
                       0,.5.225.5.5v7.64c.34.061.675.135,1,.235V1.5c0-.827-.673-1.5-1.5-1.5H2.5C1.121,0,0,1.121,0,2.5v19c0,
                       1.379,1.121,2.5,2.5,2.5h9.529c-.362-.305-.696-.64-1.003-1ZM2.5,1h1.5v18h-1.5c-.565,
                       0-1.081.195-1.5.512V2.5c0-.827.673-1.5,1.5-1.5Zm15,10c-3.584,0-6.5,2.916-6.5,6.5s2.916,6.5,6.5,6.5,6.5-2.916,
                       6.5-6.5-2.916-6.5-6.5-6.5Zm0,12c-3.032,0-5.5-2.468-5.5-5.5s2.468-5.5,5.5-5.5,5.5,2.468,
                       5.5,5.5-2.468,5.5-5.5,5.5Zm.5-6h2.5v1h-2.5v2.5h-1v-2.5h-2.5v-1h2.5v-2.5h1v2.5Z"/>
                    </svg>
                    <label for="add-question">Add language</label>
                </div>
            </a>
            
            <a class="admin-start-action" href="#">
                <div class="admin-start-action">
                     <svg class="admin-start-svg-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24">
                       <path xmlns="http://www.w3.org/2000/svg" 
                       d="M.243,7.444l10.31,6.186c.447,.268,.947,.401,1.448,.401s1.001-.134,1.448-.401l10.31-6.186c.15-.09,
                       .243-.253,.243-.429s-.092-.339-.243-.429L13.448,.401c-.893-.537-2.003-.537-2.896,0L.243,6.587c-.15,
                       .09-.243,.253-.243,.429s.092,.339,.243,.429ZM11.067,1.259c.576-.346,1.291-.346,1.866,0l9.595,5.757-9.595,
                       5.757c-.575,.346-1.291,.346-1.866,0L1.472,7.016,11.067,1.259Zm12.933,18.241c0,.276-.224,
                       .5-.5,.5h-3.5v3.5c0,.276-.224,.5-.5,.5s-.5-.224-.5-.5v-3.5h-3.5c-.276,0-.5-.224-.5-.5s.224-.5,
                       .5-.5h3.5v-3.5c0-.276,.224-.5,.5-.5s.5,.224,.5,.5v3.5h3.5c.276,0,.5,.224,.5,.5Zm-.071-9.157c.142,
                       .236,.065,.544-.171,.686l-11.5,6.9c-.079,.048-.168,.071-.257,.071s-.178-.023-.257-.071L.243,
                       11.028c-.237-.142-.313-.449-.171-.686s.449-.316,.686-.172l11.243,6.746,11.243-6.746c.237-.145,
                       .544-.064,.686,.172Zm-11.5,10.914c-.094,.156-.259,.243-.429,.243-.087,0-.176-.022-.257-.071L.243,
                       14.528c-.237-.142-.313-.449-.171-.686s.449-.315,.686-.172l11.5,6.9c.237,.142,.313,.449,.171,.686Z"/>

                    </svg>
                    <label for="add-question">Add topic</label>
                </div>
            </a>
    </section>