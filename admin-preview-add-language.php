<?php
include "admin-log.php";
include_once "sql_query.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin-start.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>HATCH - Preview</title>
    <link rel="stylesheet" href="./css/style.css" />
</head>

<body>
    <section>
        <div class="admin-preview">

            <?php
            $language_name = $_POST['add-language'];
            $selected_topics = $_POST['topic'] ?? [];
            
            if (isset($_POST['add-language'])) { // Check that the admin came from "admin-add-language.php"


                echo "<h1>Preview: Add Programming Language</h1>";
                echo "<p><strong>Language Name:</strong> " . htmlspecialchars($language_name) . "</p>";

                // Fetch topics and preview topics

                if (!empty($selected_topics)) {
                    $topics = get_all_topics();
                    $topic_names = [];
                    foreach ($topics as $topic) {
                        if (in_array($topic['topic_id'], $selected_topics)) {
                            $topic_names[] = htmlspecialchars($topic['topic_name']);
                        }
                    }
                    echo "<p><strong>Selected Topics:</strong> " . implode(", ", $topic_names) . "</p>";
                } else {
                    echo "<p><strong>No topics selected.</strong></p>";
                }
            }
            ?>

            <!-- Pass the data from the form onto upload-to-database.php-->

            <form method="POST" action="upload-to-database.php">
                <input type="hidden" name="form_type" value="add_language">
                
                <input type="hidden" name="add-language" value="<?= htmlspecialchars($language_name) ?>">
                <?php foreach ($selected_topics as $topic_id): ?>
                    <input type="hidden" name="topic[]" value="<?= htmlspecialchars($topic_id) ?>">
                <?php endforeach; ?>
                <button type="submit" class="upload-to-database-button">Upload to database</button>
            </form>

            <form method="GET" action="admin-add-language.php">
                <button class="button" type="submit">Cancel</button>
            </form>

        </div>
    </section>
</body>

</html>