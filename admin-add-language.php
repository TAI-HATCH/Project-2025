<?php

include "admin-logout.php";

include_once "sql_query.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo var_dump($_POST['topic']);
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
            <div class="admin-add-language">
                <label class="admin-add-language-label" for="add-language">Add programming language</label>
                <input class="admin-add-language-input-field" type="text" id="add-language" name="add-language" placeholder="Type the language to add here.">
            </div>

            <div class="admin-add-language-topic-selection-content">
                <label for="admin-add-language-topic-selection-content">Select topics (optional)</label>
                <div class="admin-add-language-topic-selection">
                    <label for="admin-add-language-topic-selection-list">Topics:</label>


                    <ul>
                        <?php

                        $topics = get_all_topics(); // hae kaikki aiheet

                        foreach ($topics as $topic) : ?>

                            <li>
                                <input class='checkbox' type='checkbox'
                                    id="topic<?= ($topic['topic_id']) ?>"
                                    name="topic[]" value="<?= ($topic['topic_id']) ?>">

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