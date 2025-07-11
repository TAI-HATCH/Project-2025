<?php
include_once "connection.php";
include_once "sql_query.php";

echo '<pre>';
var_dump($_POST);
echo '</pre>';

$form_type = $_POST['form_type'] ?? null;
$tempFileName = $_POST['temp-icon-file'] ?? null; //Get the name of temporary file: temp-name.extension
$newFileName = $_POST['new-icon-file-name'] ?? null; //Get the new name of file to upload: name.extension
$sqlAction = $_POST['sql-action'] ?? null;
$fileAction = $_POST['server-file-action'] ?? null;

// Processing the uploading of the icon-file:

//specify the name of the element - it can be the name of the prog.language or the name of the topic:
// if (isset($_POST['add-topic'])) {
//     $element_name = $_POST['add-topic'];
// } elseif (isset($_POST['add-language'])) {
//     $element_name = $_POST['add-language'];
// }

//specify the directory where the file is going to be placed:
$target_dir = "images/";

if (!empty($fileAction)) {
    switch ($fileAction) {
        case 'keep-existing':
            if (file_exists('temp-uploads/' . $tempFileName)) {
                unlink('temp-uploads/' . $tempFileName);
            }
            break;

        case 'upload-new':
            if (file_exists('temp-uploads/' . $tempFileName)) {
                rename('temp-uploads/' . $tempFileName, $target_dir . $newFileName);
            }
            break;

        default:
            echo "<pre>";
            var_dump("Ooops... You broke the code");
            echo "</pre>";
            break;
    }
} else {
    //Get the name of the temporary file from the folder uploads/temp with the temporary name:
    if (!$tempFileName) { ?>
        <script>
            console.log("There is no $_POST['temp-icon-file']");
        </script>
<?php } else {
        $uploadOk = 1;

        // Check if file already exists
        //Syntax: file_exists(path), where "path" specifies the path to the file or directory to check:
        if (file_exists($target_dir . $newFileName)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            //copy the temporary file to the server with a new name in the folder specified by $target_file:  
            if (rename('temp-uploads/' . $tempFileName, $target_dir . $newFileName)) {
                // echo $_POST['temp-icon-file'] . " has been uploaded to " . $newFileName;    
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
}


switch ($form_type) {
    case 'add-language':
        include ("handling-upload-to-db-case-add-language.php");
        header("Location: admin-upload-success.php");
        exit;
        break;

    case 'add-topic':
        include ("handling-upload-to-db-case-add-topic.php");
        header("Location: admin-upload-success.php");
        exit;
        break;

    case 'add-question':
        $language_id = $_POST['language_id'] ?? null;
        $topic_id = $_POST['topic_id'] ?? null;
        $question_text = $_POST['question'] ?? '';
        $text_before = $_POST['text_before'] ?? '';
        $text_after = $_POST['text_after'] ?? '';
        $answer_value = $_POST['answer'] ?? '';

        if ($language_id && $topic_id && $question_text && $answer_value) {
            try {
                $stmt = $conn->prepare("SELECT 
                                                id 
                                            FROM 
                                                languages_topic 
                                            WHERE 
                                                language_id = ? 
                                            AND 
                                                topic_id = ?");
                $stmt->execute([$language_id, $topic_id]);
                $languages_topic_id = $stmt->fetchColumn();

                $form_content = "<div><span>" . htmlspecialchars($text_before) . "</span><input type=\"text\" name=\"answer_one\"><span>" . htmlspecialchars($text_after) . "</span></div>";

                $insert = $conn->prepare("INSERT INTO 
                                                    questions (languages_topic_id, question, form_content) 
                                              VALUES 
                                                    (?, ?, ?)");
                $insert->execute([$languages_topic_id, $question_text, $form_content]);

                $question_id = $conn->lastInsertId();

                $answer_insert = $conn->prepare("INSERT INTO 
                                                            answers (question_id, input_name, answer_value) 
                                                    VALUES (?, ?, ?)");
                $answer_insert->execute([$question_id, 'answer_one', $answer_value]);

                session_start();
                $_SESSION['text-message'] = "The temporary icon-file <b>" . $_POST['temp-icon-file'] . "</> from <b>temp-uploads/</b> has been moved to <b>images/</b> and renamed to <b>" . $newFileName . "</b>.";
                // header("Location: admin-upload-success.php");
                exit;
            } catch (PDOException $e) {
                echo "Error uploading: " . $e->getMessage();
            }
        }
        break;

    default:
        echo "Invalid form type.";
        break;
}

?>