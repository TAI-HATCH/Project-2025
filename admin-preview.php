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
            if (isset($_POST['add-language'])): // Checks that the admin came from the page "admin-add-language.php"
                $language_name = $_POST['add-language'];
                $selected_topics = $_POST['topic'] ?? [];
                
                echo "Preview: Add Programming Language";
                echo "<p></p>";
                echo "Language Name: " . $language_name . "";
            
                if (!empty($selected_topics)) {
                    // Get topics, put in array and show them to admin
                    $topics = get_all_topics();
                    $topic_names = [];
                    foreach ($topics as $topic) {
                        if (in_array($topic['topic_id'], $selected_topics)) {
                            $topic_names[] = $topic['topic_name'];
                        }
                    }
                    echo "<p><strong>Selected Topics:</strong> " . implode(", ", $topic_names) . "</p>";
                } else {
                    echo "<p>No topics selected.</p>";
                } 
            ?> 
            
                <!-- Covers sending the data to upload-to-database.php -->

                <form method="POST" action="upload-to-database.php">
                    <input type="hidden" name="add-language" value="<?= $language_name ?>">
                    <?php foreach ($selected_topics as $topic_id): ?>
                        <input type="hidden" name="topic[]" value="<?= $topic_id ?>">
                    <?php endforeach; ?>
                     <button type="submit" class="upload-to-database-button">Upload to database</button>
                </form>

                <form method="GET" action="admin-add-language.php">
                    <button class="button" type="submit">Cancel</button>
                </form>
            <?php


            // Question preview 
            
            elseif (isset($_POST['language_id']) && isset($_POST['question'])):

                $language_id = $_POST['language_id'];
                $topic_id = $_POST['topic_id'] ?? null;
                $question_text = $_POST['question'] ?? '';
                $text_before = $_POST['text_before'] ?? '';
                $text_after = $_POST['text_after'] ?? '';
                $answer_value = $_POST['answer'] ?? '';

                $form_content = "<div><span>" . htmlspecialchars($text_before) . "</span>"
                    . "<input type=\"text\" name=\"answer_one\" value=\"\">"
                    . "<span>" . htmlspecialchars($text_after) . "</span></div>";

                echo "<h1>Preview Question</h1>";
                echo "<div class='admin-preview-field'>";
                echo "<p>" . htmlspecialchars($question_text) . "</p>";
                echo $form_content;
                echo "</div>";
            ?>
                <form method="POST" action="upload-question.php">
                    <input type="hidden" name="language_id" value="<?= htmlspecialchars($language_id) ?>">
                    <input type="hidden" name="topic_id" value="<?= htmlspecialchars($topic_id) ?>">
                    <input type="hidden" name="question" value="<?= htmlspecialchars($question_text) ?>">
                    <input type="hidden" name="text_before" value="<?= htmlspecialchars($text_before) ?>">
                    <input type="hidden" name="text_after" value="<?= htmlspecialchars($text_after) ?>">
                    <input type="hidden" name="answer" value="<?= htmlspecialchars($answer_value) ?>">

                    <button class="button" type="submit" name="confirm_upload" value="1">Confirm upload</button>
                </form>

                <form method="GET" action="admin-add-question.php" style="margin-top: 1em;">
                    <button class="button" type="submit">Cancel</button>
                </form>

            <?php else: ?>
                <p>Invalid data submitted.</p>
                <a href="admin-start.php">Return to start</a>
            <?php endif; ?>

        </div>
    </section>
</body>

</html>