<?php
include "admin-log.php";
include_once "sql_query.php";
include "admin-header.php";

//a security mechanism that prevents a page from being processed if it is opened directly through a browser. Instead, it redirects the user to the start page.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin-start.php");
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES["svg-file"])) {
    $form_type = $_POST['form_type'] ?? null;
    // a variable that will be passed via POST for further processing of the sql-request: whether to add a new record to the DB or update existing ones:
    // by default it is "add" action. We assume that the admin will add a record that does not yet exist.
    $sql_action = 'sql-add';
    // a variable that will be passed via POST for further processing of the icon-file: whether to upload a new file to the server or keep existing one:
    // by default it is "upload-new" action. We assume that the admin will add a record that does not yet exist, and therefore there is no such file on the server yet
    $server_file_action = 'upload-new';

    // Processing the uploading of the icon-file:
    // https://www.w3schools.com/php/php_file_upload.asp

    //specify the name of the element - it can be the name of the prog.language or the name of the topic:
    if (isset($_POST['add-topic'])) {
        $element_name = $_POST['add-topic'];
    } elseif (isset($_POST['add-language'])) {
        $element_name = $_POST['add-language'];
    }

    $newFile = generateNewFileName($element_name);
    $temp_file_name = generateTempFileName();

    // Check if file with $newFile-name already exists in images-folder: 
    if (file_exists('images/' . $newFile)) {
        $isAlreadyExist = true;
    }


    switch ($form_type) {
        case 'add-language':
            // echo "<pre>";
            // var_dump($form_type);
            // echo "</pre>";

            //Validation: Is there a language in the DB table "languages" with the entered name?:
            $all_languages = get_all_languages(); //Get an array of ALL prog.languages with is_active
            $inputed_name = trim(strtolower($_POST['add-language']));
            $isUnique = true;
            $isActive = null;
            $lang_id = null;
            foreach ($all_languages as $language_set) {
                if (trim(strtolower($language_set['language_name'])) === $inputed_name) {
                    $isUnique = false;
                    $isActive = $language_set['is_active'];
                    $lang_id = $language_set['language_id'];
                    session_start();
                    $_SESSION['language_id'] = $lang_id;

                    break;
                }
            }
            // echo "<pre>";
            // echo "The value of isUnique: ";
            // var_dump($isUnique);
            // echo "The value of isActive: ";
            // var_dump($isActive);
            // echo "</pre>";

            if ($isUnique == false) { //It means that the mentioned programming language already exists in the DB
                // echo "<pre>";
                // var_dump("You want to add an existing language in the DB");
                // echo "</pre>";

                //Validation: if this language active or not:
                if ($isActive == 1) {
                    $text_message = "The programming language $inputed_name can not be added to the database, because it already exists.";
                    // session_start();
                    $_SESSION['text-message'] = $text_message;
                    header("Location: error.php"); // redirection to the error-page
                    exit;
                } else {
                    $sql_action = 'sql-update';
                    $text_message = "The programming language $inputed_name was previously deactivated. Do you want to restore it along with all its related topics and questions?";
                    $all_existing_topics_for_language = get_all_existing_topics_for_language($lang_id);
                    $all_existing_questions = get_all_existing_questions_for_language($lang_id);
                    // echo "<pre>";
                    // var_dump($all_existing_topics_for_language);
                    // var_dump($all_existing_questions);
                    // echo "</pre>";
                    handleTempIconFile($temp_file_name);
                }
                // echo "<pre>";
                // var_dump($text_message);
                // echo "</pre>";
            } else {
                // echo "<pre>";
                // var_dump("You want to add a new language");
                // echo "</pre>";
                handleTempIconFile($temp_file_name);
            }

            break; //This break is for case add-language

        case 'add-topic':
            // echo "<pre>";
            // var_dump($form_type);
            // echo "</pre>";

            //Validation: Is there a topic in the DB table "topics" with the entered name?:
            $all_existing_topics = get_all_existing_topics(); //Get an array of ALL topics with is_active
            $inputed_name = trim(strtolower($_POST['add-topic']));
            $isUnique = true;
            $isActive = null;
            $topic_id = null;
            foreach ($all_existing_topics as $topic_set) {
                if (trim(strtolower($topic_set['topic_name'])) === $inputed_name) {
                    $isUnique = false;
                    $isActive = $topic_set['is_active'];
                    $topic_id = $topic_set['topic_id'];
                    // session_start();
                    $_SESSION['topic_id'] = $topic_id;

                    break;
                }
            }

            // echo "<pre>";
            // echo "The value of isUnique: ";
            // var_dump($isUnique);
            // echo "The value of isActive: ";
            // var_dump($isActive);
            // echo "</pre>";


            if ($isUnique == false) { //It means that the mentioned topic already exists in the DB
                // echo "<pre>";
                // var_dump("You want to add an existing topic in the DB");
                // echo "</pre>";

                //Validation: if this topic active or not:
                if ($isActive == 1) {
                    $text_message = "The topic '$inputed_name' can not be added to the database, because it already exists.";
                    // session_start();
                    $_SESSION['text-message'] = $text_message;
                    header("Location: error.php"); // redirection to the error-page
                    exit;
                } else {
                    $sql_action = 'sql-update';
                    $text_message = "The topic '$inputed_name' was previously deactivated. Do you want to restore it along with all its related questions?";
                    $all_existing_languages_for_topic = get_all_existing_language_topics_for_topic($topic_id);
                    $all_existing_questions = get_all_existing_questions_for_topic($topic_id);
                    // echo "<pre>";
                    // var_dump($text_message);
                    // var_dump($all_existing_questions);
                    // echo "</pre>";
                    handleTempIconFile($temp_file_name);
                }


                // echo "<pre>";
                // var_dump($text_message);
                // echo "</pre>";
            } else {
                // echo "<pre>";
                // var_dump("You want to add a new language");
                // echo "</pre>";
                handleTempIconFile($temp_file_name);
            }


            break; //This break is for case add-topic 

        default: //This default is for switch form_type
            echo "<pre>";
            var_dump("You added something else but not language");
            echo "</pre>";
            handleTempIconFile($temp_file_name);
            break;
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
                    include ('handling-preview-case-add-language.php');
                    break;

                // ========= EDIT LANGUAGE ==========
                case 'edit-language':
                    break;

                // ========= ADD TOPIC ==========
                case 'add-topic':
                    include ('handling-preview-case-add-topic.php');
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
                            <button class="upload-to-database-button" type="submit">Upload to database</button>
                        </form>
                        <form method="GET" action="admin-add-question.php">
                            <button class="upload-to-database-button" type="submit">Cancel</button>
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
    <script src="./js/scripts.js"></script>
</body>

</html>
