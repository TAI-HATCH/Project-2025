<?php
include "admin-log.php";
include_once "sql_query.php";
include "admin-header.php";


$form_type = $_POST['form_type'] ?? null;
echo "<pre>";
var_dump($form_type);
echo "</pre>";

//a security mechanism that prevents a page from being processed if it is opened directly through a browser. Instead, it redirects the user to the start page.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin-start.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    switch ($form_type) {
        case 'add-language':
            # code...
            if (isset($_FILES["svg-file"])) {
                //Validation: Is there a language in the DB table "languages" with the specified name?:
                $all_languages = get_all_languages(); //Get an array of prog.languages with is_active
                echo "<pre>";
                var_dump($all_languages);
                echo "</pre>";
    
                $inputed_name = $_POST['add-language'];
    
                $isUnique = true;
                foreach ($all_languages as $language_set) {
                    if ($language_set['language_name'] === $inputed_name) {
                        $isUnique = false;
                        $isActive = $language_set['is_active'];
                        $lang_id = $language_set['language_id'];
                        echo "<pre>";
                        var_dump($isUnique);
                        var_dump($isActive);
                        echo "</pre>";
                        break;
                    }
                }
    
                if ($isUnique == true) { // It means that there is NO programming language with the specified name in the DB
                    # code... then html runs?
                } else { // It means that THERE IS the programming language with the specified name in the DB
                    //Validation: Is this programming language active or not?:
                    if ($isActive == 1) { // It means that the inputed language name is active
                        $text_message = "The programming language $inputed_name can not be added to the database, because it already exists.";
                        header("Location: error.php");
                        exit;
                        
                    } else { // It means that the inputed name is unactive = was earlier "deleted":
                        $text_message = "The programming language $inputed_name was earlier deactivated. Do you want to restore it and all topics and questions for it? You can choose:";
                        $all_existing_topics = get_all_existing_topics($lang_id);
                        $all_existing_questions = get_all_existing_questions($lang_id);
                        echo "<pre>";
                        var_dump($all_existing_topics);
                        var_dump($all_existing_questions);
                        echo "</pre>";
                    }
                }
            }

            break;
        
        default:
            # code...
            break;
    }
    
    // Processing the uploading of the icon-file:
    // https://www.w3schools.com/php/php_file_upload.asp

    //specify the name of the element - it can be the name of the prog.language or the name of the topic:
    if (isset($_POST['add-topic'])) {
        $element_name = $_POST['add-topic'];
    } elseif (isset($_POST['add-language'])) {
        $element_name = $_POST['add-language'];
    }

    //Get the extension of the selected file by admin:
    $fileExtension = pathinfo($_FILES["svg-file"]["name"], PATHINFO_EXTENSION);
    //create a new NAME for the file according to the defined rules for uploading to the server without extension:
    $newFileName = str_replace(" ", "-", strtolower($element_name)) . "-icon";
    //create the full  NAME for the file with extension:
    $newFile = $newFileName . '.' . $fileExtension;

    //specify the directory to store TEMPORARY files:
    $temp_upload_dir = "temp-uploads/";
    //Get a temporary file with the initial name:
    $tempFile = $_FILES["svg-file"]["tmp_name"];
    //Set the unique name to the file just to store it as temporary file before uploading to the server:
    $temp_file_name = uniqid() . "-" . $_FILES["svg-file"]["name"];
    //Set the path to store the file (folder/temp-name.extension):
    $temp_file_path = $temp_upload_dir . $temp_file_name;

    $uploadOk = 1;

    // Check if file already exists
    if (file_exists($temp_file_path)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error  

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        //copy the temporary file to the server with a new name in the folder specified by $target_file:
        //Syntax: move_uploaded_file(file, dest):
        // file - filename of the uploaded file
        //dest - the new location for the file
        if (move_uploaded_file($tempFile, $temp_file_path)) {
?>
            <script>
                console.log(`The temporary file <?php echo $temp_file_name ?>  has been uploaded to <?php echo $temp_file_path ?>.`);
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
            if (!$form_type) {
                echo "<p>Error: form_type is not defined.</p>";
                exit;
            }

            switch ($form_type) {
                
                case 'add-language': // ========= ADD LANGUAGE ==========
                    $language_name = $_POST['add-language'] ?? '';
                    $selected_topics = $_POST['topic'] ?? [];
            ?>

                    <h1>Preview add programming language</h1>
                    <div class="admin-preview-content">
                        <p><strong>Language name:</strong> <?= $language_name; ?></p>

                        <?php if ($isUnique == false && $isActive == 0) {?>
                            <p>HUOM! <?= $text_message ?></p>

                            <!-- FORM POST starts -->
                            <form action="upload-to-database.php" method="post">
                                <?php if (!empty($all_existing_topics)) { ?>
                                    <p>Topics:</p>
                                    <p>The set of topics can be changed:</p>
                                    <ul>
                                        <?php foreach ($all_existing_topics as $topic) {
                                            echo "<pre>";
                                            var_dump($topic);
                                            echo "</pre>";
                                        ?>
                                            <li>
                                                <input class="checkbox" type="checkbox" id="topic<?php echo $topic['id']; ?>" value="<?php echo $topic['topic_name']; ?>" name="topic[]" 
                                                <?php if ($topic['is_active'] == 1) {?>checked<?php;}?>>
                                                <label for="topic<?php echo $topic['id']; ?>"><?php echo $topic['topic_name']; ?></label>
                                            </li>
                                        <?php } ?>
                                    </ul>



                                    
                                <?php
                            

                                 } //else {
                                //     # code...
                                // }

                                ?>
                            </form>


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
                        <?php } } ?>
                    </div>

                    <div class="admin-preview-content">
                        <p><strong>The name of icon-file to upload is:</strong> <?= $newFile; ?></p>
                        <img src="<?= "./temp-uploads/" . htmlspecialchars($temp_file_name); ?>" alt="The preview for icon-file" width="70">
                    </div>

                    <div class="admin-form-buttons">
                        <form method="POST" action="upload-to-database.php">
                            <input type="hidden" name="form_type" value="add-language">
                            <input type="hidden" name="add-language" value="<?= $language_name; ?>">
                            <input type="hidden" name="temp-icon-file" value="<?= htmlspecialchars($temp_file_name); ?>">
                            <input type="hidden" name="new-icon-file-name" value="<?= $newFile; ?>">

                            <?php foreach ($selected_topics as $topic_id): ?>
                                <input type="hidden" name="topic[]" value="<?= $topic_id; ?>">
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
                    $selected_languages = $_POST['language'] ?? []; 
                ?>

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