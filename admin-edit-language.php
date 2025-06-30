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
        <!-- <form action="" method="post"> -->
        <div class="dropdown-section">
            <div class="dropdown-wrapper">
                <button onclick="handlingDropdownMenu()" class="dropdown-btn" id="input-element">Select programming language to edit:</button>
                <ul class="dropdown-content" id="dropdown-content">
                    <?php
                    $languages = get_languages(); // Fetch all languages, function found in sql_query.php
                    foreach ($languages as $language) :
                        $language_icon_name = str_replace(" ", "-", strtolower($language['language_name']));
                    ?>
                        <li class="dropdown-content-item" onclick="selectionHandling(event)">
                            <img src="./images/<?php echo $language_icon_name ?>-icon.svg" alt="<?php echo $language['language_name']; ?>" height="20">
                            <a href="#"><?php echo $language['language_name'] ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <!-- </form> -->
    </section>

    <!-- Scripts for this page -->
    <script src="./js/scripts.js"></script>
</body>

</html>