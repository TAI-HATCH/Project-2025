<?php

include "admin-log.php";
include_once "sql_query.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo var_dump($_POST['topic']); // Prints the content of the array "topic" in the top of the page
    $language_name = $_POST['add-language'] ?? null; // If there is no input, return "null"
    $selected_topics = $_POST['topic'] ?? []; // If nothing in the array, return an empty array "[]"

    if (!empty($language_name)) {
        $stmt = $conn->prepare("INSERT INTO 
                                    languages (language_name) 
                                VALUES 
                                    (:language_name)");
        $stmt->bindParam(":language_name", $language_name, PDO::PARAM_STR);
        $stmt->execute();

        $language_id = $conn->lastInsertId();

        if (!empty($selected_topics)) { // Select content from checkbox
            $stmt = $conn->prepare("INSERT INTO 
                                        languages_topic (language_id, topic_id) 
                                    VALUES 
                                        (:language_id, :topic_id)");

            $stmt->bindParam(':language_id', $language_id, PDO::PARAM_INT);
            $stmt->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);

            foreach ($selected_topics as $topic_id) {
                $stmt->execute();
            }
        }

        echo "Language and optional topics successfully inserted.";
    } else {
        echo "Language name is required.";
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
                <label class="admin-add-content-label" for="add-language">Add programming language</label>
                <input class="admin-add-content-input-field" type="text" id="add-language" name="add-language" placeholder="Type the language to add here." required>
            </div>

            <div class="admin-add-content-checkbox-selection-content">
                <label for="admin-add-content-checkbox-selection-content">Select topics (optional)</label>
                <div class="admin-add-content-checkbox-selection">
                    <label for="admin-add-content-checkbox-selection-list">Topics:</label>


                    <ul>
                        <?php

                        $topics = get_all_topics(); // Fetch all topics, function found in sql_query.php

                        foreach ($topics as $topic) : ?>

                            <li>
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
        <button type="submit" class="upload-to-database-button">Upload to database</button>
    </form>
</body>

</html>