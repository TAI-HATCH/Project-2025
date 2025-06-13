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
    <!-- The form will be redirected to the page admin-preview.php after submit button click: -->
    <form method="post" enctype="multipart/form-data" action="admin-preview.php"> <!-- attribute: enctype="multipart/form-data" specifies which content-type to use when submitting the form -->
        <input type="hidden" name="form_type" value="add-language">

        <section class="root-content">
            <div class="admin-add-content">
                <label class="admin-add-content-label" for="add-language">Add programming language</label>
                <input class="admin-add-content-input-field" type="text" id="add-language" name="add-language" placeholder="Type the language to add here." required onchange="createNameForSvgIcon('add-language')" onblur="innerTextToParagragh('add-language')" oninput="activateButton()">
            </div>

            <div class="admin-add-upload-svg">
                <label class="upload-svg-button-label" for="svg-file">Select an svg-file for uploading it to the server:</label>
                <input type="file" id="svg-file" name="svg-file" class="upload-svg-button" onchange="handleFileUpload('add-language')" oninput="activateButton()">
                <p class="upload-svg-info-text" id="upload-svg-info-text"></p>
            </div>

            <div class="admin-add-content-checkbox-selection-content">
                <label for="admin-add-content-checkbox-selection-content">Select topics (optional)</label>
                <div class="admin-add-content-checkbox-selection">
                    <label for="admin-add-content-checkbox-selection-list">Topics:</label>

                    <ul>
                        <?php

                        $topics = get_all_topics(); // Fetch all topics, function found in sql_query.php

                        foreach ($topics as $topic) : ?>

                            <li> <!--It is important to use name="topic[]" with [] in the case with checkbox. 
                                If we will not, then in PHP in $_POST['topic'] we recieve only the value of last element -->
                                <input class='checkbox' type='checkbox'
                                    id="topic<?= ($topic['topic_id']) ?>"
                                    name="topic[]"
                                    value="<?= ($topic['topic_id']) ?>">

                                <!--id: Print ID for this topic;
                                    name: collect all selected topics to array topic[];
                                    value: define value for checkbox-->

                                <label for="topic<?= ($topic['topic_id']) ?>"> <!--Link checkbox to topic-->
                                    <?= ($topic['topic_name']) ?> <!--Print topic-->
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </div>
        </section>
        <button type="submit" id="submitBtn" class="upload-to-database-button" disabled onclick="validateForm(event)">Preview and upload</button>

    </form>
    <!-- Scripts for this page -->
    <script src="./js/upload-icon.js"></script>

</body>

</html>