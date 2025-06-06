<?php
include "admin-log.php";
include_once "sql_query.php";
include "admin-header.php";

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HATCH - Preview</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="./css/style.css">
</head>
<!-- Shared handler for all six pages-->

<body>
    <section>
        <div class="admin-preview">
            <?php
            switch ($form_type) {
                // ========= ADD LANGUAGE ==========

                case 'add-language':
                    $language_name = $_POST['add-language'] ?? '';
                    $selected_topics = $_POST['topic'] ?? [];
            ?>

                    <h1>Preview add programming language</h1>

                    <div class="admin-preview-content">
                        <p><strong>Language name:</strong> <?= $language_name ?></p>

                        <?php
                        if (!empty($selected_topics)) {
                            $topics = get_all_topics();
                            $topic_names = [];
                            foreach ($topics as $topic) {
                                if (in_array($topic['topic_id'], $selected_topics)) {
                                    $topic_names[] = $topic['topic_name'];
                                }
                            }
                        ?>
                            <p><strong>Selected Topics:</strong> <?= join(", ", $topic_names) ?></p>
                        <?php } else { ?>
                            <p>No topics selected.</p>
                        <?php } ?>
                    </div>

                    <div class="admin-form-buttons">
                        <form method="POST" action="upload-to-database.php">
                            <input type="hidden" name="form_type" value="add-language">
                            <input type="hidden" name="add-language" value="<?= $language_name ?>">

                            <?php foreach ($selected_topics as $topic_id): ?>
                                <input type="hidden" name="topic[]" value="<?= $topic_id ?>">
                            <?php endforeach; ?>
                            <button class="button" type="submit">Upload to database</button>
                        </form>

                        <form method="GET" action="admin-add-language.php">
                            <button class="button" type="submit">Cancel</button>
                        </form>
                    </div>

                <?php
                    break;

                // ========= EDIT LANGUAGE ==========
                case 'edit-language':
                    break;

                // ========= ADD TOPIC ==========
                case 'add-topic':
                    $topic_name = $_POST['add-topic'] ?? '';
                    $selected_languages = $_POST['languages'] ?? []; ?>

                    <h1>Preview add topic</h1>
                    <div class="admin-preview-content">
                        <p><strong>Topic name: </strong><?= $topic_name ?></p>
                        <?php

                        if (!empty($selected_languages)) {
                            $languages = get_languages();
                            $language_names = [];
                            foreach ($languages as $language) {
                                if (in_array($language['language_id'], $selected_languages)) {
                                    $language_names[] = htmlspecialchars($language['language_name']);
                                }
                            } ?>

                            <p><strong>Selected languages: </strong> <?= implode(", ", $language_names) ?></p>
                        <?php } else { ?>
                            <p><strong>No languages selected.</strong></p>
                        <?php } ?>
                    </div>

                    <div class="admin-form-buttons">
                        <form method="POST" action="upload-to-database.php">
                            <input type="hidden" name="form_type" value="add-topic">
                            <input type="hidden" name="add-topic" value="<?= $topic_name ?>">

                            <?php
                            foreach ($selected_languages as $language_id) { ?>
                                <input type="hidden" name="languages[]" value="<?= $language_id ?>">
                            <?php } ?>
                            <button class="button" type="submit">Upload to database</button>
                        </form>
                        <form method="GET" action="admin-add-topic.php">
                            <button class="button" type="submit">Cancel</button>
                        </form>
                    </div>
                <?php
                    break;

                case 'edit-topic':
                    break;

                // ========= ADD QUESTION ==========

                case 'add-question':

                    // Grab all POST data from form on earlier page

                    $language_id = $_POST['language_id'] ?? null;
                    $topic_id = $_POST['topic_id'] ?? null;
                    $question_text = $_POST['question'] ?? '';
                    $text_before = $_POST['text_before'] ?? '';
                    $text_after = $_POST['text_after'] ?? '';
                    $answer_value = $_POST['answer'] ?? '';

                    // Build form content preview
                ?>

                    <h1 class="">Preview question</h1>
                    <div class="admin-preview-question">
                        <div><?php echo $question_text; ?></div>

                        <div class="admin-preview-question-content">
                            <span><?php echo $text_before; ?></span>
                            <input type="text" name="answer_one" placeholder="<?= $answer_value ?>">
                            <span><?php echo $text_after; ?></span>
                        </div>
                    </div>

                    <!-- Push form data forward to upload -->

                    <div class="admin-form-buttons">
                        <form method="POST" action="upload-to-database.php">
                            <input type="hidden" name="form_type" value="add-question">
                            <input type="hidden" name="language_id" value="<?= htmlspecialchars($language_id) ?>">
                            <input type="hidden" name="topic_id" value="<?= htmlspecialchars($topic_id) ?>">
                            <input type="hidden" name="question" value="<?= htmlspecialchars($question_text) ?>">
                            <input type="hidden" name="text_before" value="<?= htmlspecialchars($text_before) ?>">
                            <input type="hidden" name="text_after" value="<?= htmlspecialchars($text_after) ?>">
                            <input type="hidden" name="answer" value="<?= htmlspecialchars($answer_value) ?>">
                            <button class="button" type="submit">Upload to database</button>
                        </form>
                        <form method="GET" action="admin-add-question.php">
                            <button class="button" type="submit">Cancel</button>
                        </form>
                    </div>


            <?php
                    break;
                case 'edit-question':
                    break;

                default:
                    echo "<p>Invalid preview type.</p>";
                    break;
            }
            ?>
        </div>
    </section>
</body>

</html>