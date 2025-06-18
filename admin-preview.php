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
            // echo "<pre>";F
            // var_dump($form_type);
            // echo "</pre>";

            //Validation: Is there a language in the DB table "languages" with the specified name?:
            $all_languages = get_all_languages(); //Get an array of ALL prog.languages with is_active
            $inputed_name = $_POST['add-language'];
            $isUnique = true;
            $isActive = null;
            $lang_id = null;
            foreach ($all_languages as $language_set) {
                if ($language_set['language_name'] === $inputed_name) {
                    $isUnique = false;
                    $isActive = $language_set['is_active'];
                    $lang_id = $language_set['language_id'];

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
                # code...

                // echo "<pre>";
                // var_dump("You want to add an existing language in the DB");
                // echo "</pre>";

                //Validation: if this language active or not:
                if ($isActive == 1) {
                    $text_message = "The programming language $inputed_name can not be added to the database, because it already exists.";
                    session_start();
                    $_SESSION['text-message'] = $text_message;
                    header("Location: error.php");
                    exit;
                } else {
                    # code...
                    $text_message = "The programming language $inputed_name was previously deactivated. Do you want to restore it along with all its related topics and questions?";
                    $all_existing_topics_for_language = get_all_existing_topics_for_language($lang_id);
                    $all_existing_questions = get_all_existing_questions($lang_id);
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
                    $language_name = $_POST['add-language'] ?? '';
                    $selected_topics = $_POST['topic'] ?? [];
            ?>

                    <h1>Preview add programming language</h1>

                    <div class="admin-preview-content">
                        <p><strong>Language name:</strong> <?= $language_name ?></p>

                        <?php
                        if (isset($text_message)) {
                        ?>
                            <p><strong><?php echo $text_message ?></strong></p>

                            <?php
                            if (!empty($all_existing_topics_for_language) && !empty($selected_topics)) {
                            ?>
                                <p><strong>Below is a list of all topics currently stored in the database for the <?php echo $element_name ?>.</strong></p>
                                <p>Topics that are already active or were selected on the previous page are marked with checkmarks.</p>
                                <p>Topics without checkmarks were previously deactivated.</p>
                                <p>You can modify this selection before saving — check or uncheck the topics as needed:</p>
                        <?php
                                $all_topics = get_all_topics();
                                $temp_topics_array = [];
                                foreach ($all_topics as $topic) {
                                    if (in_array($topic['topic_id'], $selected_topics)) {
                                        $topic_item = ['topic_name' => $topic['topic_name'], 'is_active' => $topic['is_active'], 'topic_id' => $topic['topic_id']];
                                        array_push($temp_topics_array, $topic_item);
                                    }
                                }

                                $merged_topic_array = [];
                                $all_ids = [];
                                foreach ([$all_existing_topics_for_language, $temp_topics_array] as $topic_array) {
                                    foreach ($topic_array as $topic) {
                                        if (!in_array($topic['topic_id'], $all_ids)) {
                                            $merged_topic_array[] = $topic;
                                            $all_ids[] = $topic['topic_id'];
                                        }
                                    }
                                }

                                // echo "<pre>";
                                // echo "Merged array:";
                                // var_dump($merged_topic_array);
                                // echo "</pre>";
                            } else {
                                echo "<pre>";
                                var_dump("No topics");
                                echo "</pre>";
                            }
                        } else {
                            echo "<pre>";
                            var_dump("No text message");
                            echo "</pre>";
                        }
                        ?>

                        <?php
                        if (!empty($merged_topic_array)) {
                        ?>
                            <ul>
                                <?php
                                foreach ($merged_topic_array as $topic) {
                                    $topic_id = $topic['topic_id'];
                                ?>
                                    <li>
                                        <input type="checkbox" name="topic[]" id="topic<?php echo $topic_id; ?>" value="<?php echo $topic_id; ?>" class="checkbox"
                                            <?php if ($topic['is_active'] == 1) { ?> checked <?php } ?>>
                                        <label for="topic<?php echo $topic_id; ?>"><?php echo $topic['topic_name']; ?></label>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                            <?php

                        } else {
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
                        <?php }
                        }
                        ?>
                    </div>

                    <div class="admin-preview-content">
                        <?php
                        if (!isset($isAlreadyExist) || $isAlreadyExist == false) {
                        ?>
                            <p><strong>The name of icon-file to upload is:</strong> <?= $newFile ?></p>
                            <img src="<?= "./temp-uploads/" . htmlspecialchars($temp_file_name) ?>" alt="The preview for icon-file" width="70">
                        <?php
                        } else {
                        ?>
                            <p><strong>The name of icon-file to upload is:</strong> <?= $newFile ?></p>
                            <p>But the file <strong><?php echo $newFile ?></strong> already exists in the <strong>images</strong> folder for <?php echo $element_name; ?>.</p>
                            <p>Which one do you want to use — the existing file or the new upload?</p>
                            <ul class="images-list">
                                <li class="images-list-item">
                                    <label for="selected_image" class="images-list-item">
                                        <img src="<?= "./temp-uploads/" . htmlspecialchars($temp_file_name) ?>" alt="The preview for selected icon-file" width="70">
                                        <p>new upload</p>
                                        <input type="radio" name="image" id="selected_image" value="new upload" onclick="handleRadioButtonText()">
                                    </label>

                                </li>
                                <li class="images-list-item">
                                    <label for="existing_image" class="images-list-item">
                                        <img src="<?= "./images/" . htmlspecialchars($newFile) ?>" alt="The preview for existing icon-file" width="70">
                                        <p>existing file</p>
                                        <input type="radio" name="image" id="existing_image" value="existing image" onclick="handleRadioButtonText()">
                                    </label>

                                </li>
                            </ul>
                            <p id="image-inform-text"></p>
                        <?php
                        }
                        ?>
                    </div>

                    <div class="admin-form-buttons">
                        <form method="POST" action="upload-to-database.php">
                            <input type="hidden" name="form_type" value="add-language">
                            <input type="hidden" name="add-language" value="<?= $language_name ?>">
                            <input type="hidden" name="temp-icon-file" value="<?= htmlspecialchars($temp_file_name) ?>">
                            <input type="hidden" name="new-icon-file-name" value="<?= $newFile ?>">

                            <?php foreach ($selected_topics as $topic_id): ?>
                                <input type="hidden" name="topic[]" value="<?= $topic_id ?>">
                            <?php endforeach; ?>
                            <button class="upload-to-database-button" type="submit">Upload to database</button>
                        </form>

                        <form method="GET" action="admin-add-language.php">
                            <button class="upload-to-database-button" type="submit">Cancel</button>
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
                    $selected_languages = $_POST['language'] ?? []; ?>

                    <h1>Preview add topic</h1>
                    <div class="admin-preview-content">
                        <p><strong>Topic name: </strong><?= $topic_name ?></p>
                        <?php

                        if (!empty($selected_languages)) {
                            $languages = get_languages();
                            $language_names = [];
                            foreach ($languages as $language) {
                                if (in_array($language['language_id'], $selected_languages)) {
                                    $language_names[] = $language['language_name'];
                                }
                            }
                        ?>

                            <p><strong>Selected languages: </strong> <?= implode(", ", $language_names) ?></p>
                        <?php } else { ?>
                            <p><strong>No languages selected.</strong></p>
                        <?php } ?>
                    </div>

                    <div class="admin-preview-content">
                        <p><strong>The name of icon-file to upload is:</strong> <?= $newFile ?></p>
                        <img src="<?= "./temp-uploads/" . htmlspecialchars($temp_file_name) ?>" alt="The preview for icon-file" width="70">
                    </div>

                    <div class="admin-form-buttons">
                        <form method="POST" action="upload-to-database.php">
                            <input type="hidden" name="form_type" value="add-topic">
                            <input type="hidden" name="add-topic" value="<?= $topic_name ?>">
                            <input type="hidden" name="temp-icon-file" value="<?= htmlspecialchars($temp_file_name) ?>">
                            <input type="hidden" name="new-icon-file-name" value="<?= $newFile ?>">

                            <?php foreach ($selected_languages as $language_id): ?>
                                <input type="hidden" name="language[]" value="<?= $language_id ?>">
                            <?php endforeach; ?>
                            <button class="upload-to-database-button" type="submit">Upload to database</button>
                        </form>

                        <form method="GET" action="admin-add-topic.php">
                            <button class="upload-to-database-button" type="submit">Cancel</button>
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
    <script src="./js/upload-icon.js"></script>
</body>

</html>