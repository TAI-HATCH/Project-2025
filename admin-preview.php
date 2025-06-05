<?php
include "admin-log.php";
include_once "sql_query.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin-start.php");
    exit;
}

$form_type = $_POST['form_type'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>HATCH - Preview</title>
    <link rel="stylesheet" href="./css/style.css" />
</head>
<body>
    <section>
        <div class="admin-preview">
            <?php
            switch ($form_type) {
                case 'add_language':
                    $language_name = $_POST['add-language'] ?? '';
                    $selected_topics = $_POST['topic'] ?? [];

                    echo "<h1>Preview: Add Programming Language</h1>";
                    echo "<p><strong>Language Name:</strong> " . htmlspecialchars($language_name) . "</p>";

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

                    echo '<form method="POST" action="upload-to-database.php">';
                    echo '<input type="hidden" name="form_type" value="add_language">';
                    echo '<input type="hidden" name="add-language" value="' . htmlspecialchars($language_name) . '">';
                    foreach ($selected_topics as $topic_id) {
                        echo '<input type="hidden" name="topic[]" value="' . htmlspecialchars($topic_id) . '">';
                    }
                    echo '<button type="submit">Upload to database</button>';
                    echo '</form>';
                    break;

                case 'edit_language':
                    // Similar block, with preview of current and edited values
                    break;

                case 'add_topic':
                    // Preview topic name
                    break;

                case 'add_question':
                    // Preview question structure and answer
                    break;

                // Add more cases as needed

                default:
                    echo "<p>Invalid preview type.</p>";
                    break;
            }
            ?>

            <form method="GET" action="admin-start.php">
                <button class="button" type="submit">Cancel</button>
            </form>
        </div>
    </section>
</body>
</html>
