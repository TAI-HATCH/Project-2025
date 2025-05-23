<?php

include "admin-logout.php";
include_once "sql_query.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

        echo "Topic and optional languages successfully inserted.";
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

    <?php include 'header.php' ?>

    <?php include 'admin-banner.php' ?>
    <form method="post">
        <section class="root-content">
            <div class="admin-add-content">
                <label class="admin-add-content-label" for="add-topic">Add topic</label>
                <input class="admin-add-content-input-field" type="text" id="add-topic" name="add-topic" placeholder="Type the topic to add here.">
            </div>

            <div class="admin-add-content-checkbox-selection-content">
                <label for="admin-add-content-checkbox-selection-content">Select languages (optional)</label>
                <div class="admin-add-content-checkbox-selection">
                    <label for="admin-add-content-checkbox-selection-list">Languages:</label>


                    <ul>
                        <?php

                        $languages = get_languages(); // Fetch all languages, function found in sql_query.php

                        foreach ($languages as $language) : ?>

                            <li>
                                <input class='checkbox' type='checkbox'
                                    id="language<?= ($language['language_id']) ?>"
                                    name="language[]"
                                    value="<?= ($language['language_id']) ?>">

                                <!--id: Print ID for this topic;
                                    name: collect all selected languages to array language[];
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
</body>

</html>