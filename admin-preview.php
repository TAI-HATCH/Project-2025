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
                // ========= ADD LANGUAGE ==========
                case 'add_language':
                    $language_name = $_POST['add-language'] ?? '';
                    $selected_topics = $_POST['topic'] ?? [];

                    echo "<h1>Preview add programming language</h1>";
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

                // ========= EDIT LANGUAGE ==========
                case 'edit_language':
                    // Similar block, with preview of current and edited values
                    break;
                // ========= ADD TOPIC ==========
                case 'add_topic':
                    $topic_name = $_POST['add-topic'] ?? '';
                    $selected_languages = $_POST['languages'] ?? [];

                    echo "<h1>Preview add topic</h1>";
                    echo "<p><strong>Topic name:</strong> " . htmlspecialchars($topic_name) . "</p>";

                    if (!empty($selected_languages)) {
                        $languages = get_languages();
                        $language_names = [];
                        foreach ($languages as $language) {
                            if (in_array($language['language_id'], $selected_languages)) {
                                $language_names[] = htmlspecialchars($language['language_name']);
                            }
                        }
                        echo "<p><strong>Selected languages:</strong> " . implode(", ", $language_names) . "</p>";
                    } else {
                        echo "<p><strong>No languages selected.</strong></p>";
                    }

                    echo '<form method="POST" action="upload-to-database.php">';
                    echo '<input type="hidden" name="form_type" value="add_topic">';
                    echo '<input type="hidden" name="add-topic" value="' . htmlspecialchars($topic_name) . '">';
                    foreach ($selected_languages as $language_id) {
                        echo '<input type="hidden" name="languages[]" value="' . htmlspecialchars($language_id) . '">';
                    }
                    echo '<button type="submit">Upload to database</button>';
                    echo '</form>';
                    break;

                case 'edit topic':
                    break;

                 // ========= ADD QUESTION ==========
                case 'add_question':
                    // Grab all POST data from form on page
                    $language_id = $_POST['language_id'] ?? null;
                    $topic_id = $_POST['topic_id'] ?? null;
                    $question_text = $_POST['question'] ?? '';
                    $text_before = $_POST['text_before'] ?? '';
                    $text_after = $_POST['text_after'] ?? '';
                    $answer_value = $_POST['answer'] ?? '';

                    // Build form content preview
                    $form_content = "<div><span>" . $text_before . "</span><input type=\"text\" name=\"answer_one\"><span>" . $text_after . "</span></div>";
                    echo '<h1>Preview Question</h1>';
                    echo $question_text;
                    echo $form_content;
                    break;
                
                case 'edit question':
                    break;

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