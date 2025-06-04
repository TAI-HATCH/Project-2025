<?php

include "admin-log.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: admin-start.php"); // Redirect to admin start page if accessed directly
    exit;
}

// Grab all POST data from form on page
$language_id = $_POST['language_id'] ?? null;
$topic_id = $_POST['topic_id'] ?? null;
$question_text = $_POST['question'] ?? '';
$text_before = $_POST['text_before'] ?? '';
$text_after = $_POST['text_after'] ?? '';
$answer_value = $_POST['answer'] ?? '';

// Build form content preview
$form_content = "<div><span>" . $text_before . "</span><input type=\"text\" name=\"answer_one\"><span>" . $text_after . "</span></div>";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HATCH - Quiz</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap"
        rel="stylesheet" />


    <link rel="stylesheet" href="./css/style.css">
</head>


<body>
    <section>
        <div class="admin-preview">
        <h1>Preview Question</h1>
        <div class="admin-preview-field">

        <?= $question_text ?>
        <?= $form_content ?>
        </div>

        <form method="POST" action="upload-to-database.php">
            <!-- Pass all data forward as hidden fields -->
            <input type="hidden" name="language_id" value="<?= $language_id ?>">
            <input type="hidden" name="topic_id" value="<?= $topic_id ?>">
            <input type="hidden" name="question" value="<?= $question_text ?>">
            <input type="hidden" name="text_before" value="<?= $text_before ?>">
            <input type="hidden" name="text_after" value="<?= $text_after ?>">
            <input type="hidden" name="answer" value="<?= $answer_value ?>">

            <button class="button" type="submit" name="confirm_upload" value="1">Confirm upload</button>
        </form>

        <form method="GET" action="admin-add-question.php" style="margin-top: 1em;">
            <button class="button" type="submit">Cancel</button>
        </form>
        </div>
    </section>
</body>

</html>