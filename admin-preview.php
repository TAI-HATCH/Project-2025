<?php
include "admin-log.php";
include_once "sql_query.php";
include "admin-header.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin-start.php");
    exit;
}

$form_type = $_POST['form_type'] ?? null;

// Processing the uploading of the icon-file:
// https://www.w3schools.com/php/php_file_upload.asp

//specify the name of the element - it can be the name of the prog.language or the name of the topic:
if (isset($_POST['add-topic'])) {
    $element_name = $_POST['add-topic'];
} elseif (isset($_POST['add-language'])) {
    $element_name = $_POST['add-language'];
}

//specifies the directory where the file is going to be placed:
$target_dir = "images/";
//Get the temporary file from the server with original name:
$tempFile = $_FILES["svg-file"]["tmp_name"];
//Get the extension of the selected file by admin:
$fileExtension = pathinfo($_FILES["svg-file"]["name"], PATHINFO_EXTENSION);
//create a new name for the file according to the defined rules for uploading to the server: 
$newFileName = str_replace(" ", "-", strtolower($element_name)) . "-icon";
$newFile = $newFileName . '.' . $fileExtension;

$target_file = $target_dir . $newFile; //Form the path with a file name, that should be uploaded to the server
$uploadOk = 1;

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error

// echo "<pre>";
// var_dump($_FILES);
// echo "</pre>";

if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
} else {
    //copy the temporary file to the server with a new name in the folder specified by $target_file:
    if (move_uploaded_file($tempFile, $target_file)) {
?>
        <script>
            console.log(`The file <?php echo htmlspecialchars($_FILES["svg-file"]["name"]) ?>  has been uploaded to <?php echo $target_file ?>.`);
        </script>
    <?php

    } else {
    ?>
        <script>
            console.log(`Sorry, there was an error uploading your file.`);
        </script>
<?php
        echo "Sorry, there was an error uploading your file.";
    }
}

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

    <!-- Scripts for this page -->
    <script src="./js/upload-icon.js"></script>
</body>

</html>