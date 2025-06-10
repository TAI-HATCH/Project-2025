<?php

include "admin-log.php";
include_once "sql_query.php";

// Check whether the form was sent using the method=post and whether the request contains a file with the "name"="svg-file":
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES["svg-file"])) {

    echo var_dump($_POST['language']); // Prints the content of the array "language" in the top of the page
    $topic_name = $_POST['add-topic'] ?? null; // If nothing is added in the form, return null 
    $selected_languages = $_POST['language'] ?? []; // If nothing in the array, return an empty array "[]"

    if (!empty($topic_name)) {
        $stmt = $conn->prepare("INSERT INTO 
                                    topics (topic_name) 
                                VALUES 
                                    (:topic_name)");
        $stmt->bindParam(":topic_name", $topic_name, PDO::PARAM_STR);
        $stmt->execute();

        $topic_id = $conn->lastInsertId();

        if (!empty($selected_languages)) { // Select content from the checkbox
            $stmt = $conn->prepare("INSERT INTO 
                                        languages_topic (language_id, topic_id) 
                                    VALUES 
                                        (:language_id, :topic_id)");

            $stmt->bindParam(':language_id', $language_id, PDO::PARAM_INT);
            $stmt->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);

            foreach ($selected_languages as $language_id) {
                $stmt->execute();
            }
        }

        // header("Location: admin-upload-success.php");
    } else {
        echo "Topic name is required.";
    }

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

    <?php include 'admin-header.php' ?>

    <?php include 'admin-banner.php' ?>
    <form method="post" enctype="multipart/form-data" action="admin-preview.php"> <!-- attribute: enctype="multipart/form-data" specifies which content-type to use when submitting the form -->
        <input type="hidden" name="form_type" value="add-topic">
        
        <section class="root-content">
            <div class="admin-add-content">
                <label class="admin-add-content-label" for="add-topic">Add topic</label>
                <input class="admin-add-content-input-field" type="text" id="add-topic" name="add-topic" placeholder="Type the topic to add here." required onchange="createNameForSvgIcon('add-topic')" onblur="innerTextToParagragh('add-topic')">
            </div>

            <div class="admin-add-upload-svg">
                <label class="upload-svg-button-label" for="svg-file">Select an svg-file for uploading it to the server:</label>
                <input type="file" id="svg-file" name="svg-file" class="upload-svg-button" onchange="handleFileUpload('add-topic')">
                <p class="upload-svg-info-text" id="upload-svg-info-text"></p>
            </div>

            <div class="admin-add-content-checkbox-selection-content">
                <label for="admin-add-content-checkbox-selection-content">Select languages (optional)</label>
                <div class="admin-add-content-checkbox-selection">
                    <label for="admin-add-content-checkbox-selection-list">Languages:</label>

                    <ul>
                        <?php

                        $languages = get_languages(); // Fetch all languages, function found in sql_query.php

                        foreach ($languages as $language) : ?>

                            <li> <!--It is important to use name="languages[]" with [] in the case with checkbox. 
                            If we will not, then in PHP in $_POST['languages'] we recieve only the value of last element -->
                                <input class='checkbox' type='checkbox'
                                    id="language<?= ($language['language_id']) ?>"
                                    name="language[]"
                                    value="<?= ($language['language_id']) ?>">

                                <!--id: Print ID for this topic;
                                        name: collect all selected languages to array languages[];
                                        value: define value for checkbox-->

                                <label for="language<?= ($language['language_id']) ?>"> <!--Link checkbox to topic-->
                                    <?= ($language['language_name']) ?> <!--Print language-->
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </div>
        </section>

        <button type="submit" class="upload-to-database-button">Upload to database</button>
    </form>

    <!-- Scripts for this page -->
    <script src="./js/upload-icon.js"></script>

</body>

</html>