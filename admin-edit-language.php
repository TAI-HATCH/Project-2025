<?php

include "admin-log.php";
include_once "sql_query.php";

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

    <?php include 'admin-header.php' ?>

    <?php include 'admin-banner.php' ?>
    <section class="root-content">

    <p>There is a page to edit language</p>
    <div class="dropdown-section">
    <div class="dropdown-wrapper">
        <button onclick="handlingDropdownBtn()" class="dropdown-btn">Select programming language to edit:</button>
        <div class="dropdown-content" id="dropdown-content">
            <a href="#">JavaScript</a>
            <a href="#">Python</a>
            <a href="#">SQL</a>
        </div>
    </div>
    </div>

    </section>


</body>

</html>