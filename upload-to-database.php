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

if (isset($fileAction)) {
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
        $language_name = $_POST['add-language'] ?? null;
        $selected_topics = $_POST['topic'] ?? [];
        $selected_questions = $_POST['question'] ?? [];
        $selected_answers = $_POST['answer'] ?? [];
        // echo "<pre>";
        // var_dump($selected_topics);
        // echo "</pre>";

        // Statement if the DB will be updated:
        if (isset($sqlAction) && $sqlAction == 'sql-update') {
            session_start();
            $language_id = $_SESSION["language_id"];
            // echo "<pre>";
            // var_dump($language_id);
            // echo "</pre>";
            // Set is_active = 1 for language:
            $stmt = $conn->prepare("UPDATE 
                                        languages 
                                    SET 
                                        is_active = '1' 
                                    WHERE 
                                        language_id = :language_id;");
            $stmt->bindParam(':language_id', $language_id, PDO::PARAM_INT);
            $stmt->execute();

            //Set is_active = 1 for all topics, that were deactivated and create a new topic if there is no topic with a specific id:
            if (!empty($selected_topics)) { // Select content from checkbox
                //Important! In SQL for the table 'languages_topic' should be an unique key for combinations language_id + topic_id
                $stmt = $conn->prepare("INSERT INTO 
                                            languages_topic (language_id, topic_id, is_active)
                                        VALUES
                                            (:language_id, :topic_id, 1)
                                        ON DUPLICATE KEY UPDATE 
                                            is_active = 1;");

                foreach ($selected_topics as $topic_id) {
                    $stmt->bindParam(':language_id', $language_id, PDO::PARAM_INT);
                    $stmt->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }

            //Set is_active = 1 for all questions, that were deactivated:
                if (!empty($selected_questions)) { 
                    $stmt = $conn->prepare("UPDATE 
                                                questions
                                            SET
                                                is_active = 1
                                            WHERE
                                                question_id = :question_id;");
    
                    foreach ($selected_questions as $question_id) {
                        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }

            //Set is_active = 1 for all answers, that were deactivated:
                if (!empty($selected_answers)) {
                    $stmt = $conn->prepare("UPDATE 
                                                answers
                                            SET
                                                is_active = 1
                                            WHERE
                                                id = :answer_id;");
    
                    foreach ($selected_answers as $answer_id) {
                        $stmt->bindParam(':answer_id', $answer_id, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }

        } else {
            $stmt = $conn->prepare("INSERT INTO 
                                        languages (language_name) 
                                    VALUES 
                                        (:language_name)");
            $stmt->bindParam(":language_name", $language_name, PDO::PARAM_STR);
            $stmt->execute();

            $language_id = $conn->lastInsertId();

            if (!empty($selected_topics)) { // Select content from checkbox
                $stmt = $conn->prepare("INSERT INTO 
                                            languages_topic (language_id, topic_id) 
                                        VALUES 
                                            (:language_id, :topic_id)");

                foreach ($selected_topics as $topic_id) {
                    $stmt->bindParam(':language_id', $language_id, PDO::PARAM_INT);
                    $stmt->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }

            // session_start();
            $_SESSION['text-message'] = "The temporary icon-file <b>" . $_POST['temp-icon-file'] . "</> from <b>temp-uploads/</b> has been moved to <b>images/</b> and renamed to <b>" . $newFileName . "</b>.";
            
        }
        header("Location: admin-upload-success.php");
        exit;
        break;

    case 'add-topic':
        $topic_name = $_POST['add-topic'] ?? null;
        $selected_languages = $_POST['language'] ?? [];

        $stmt = $conn->prepare("INSERT INTO 
                                    topics (topic_name) 
                                VALUES 
                                    (:topic_name)");
        $stmt->bindParam(":topic_name", $topic_name);
        $stmt->execute();

        $topic_id = $conn->lastInsertId();

        if (!empty($selected_languages)) {
            $stmt = $conn->prepare("INSERT INTO 
                                            languages_topic (language_id, topic_id) 
                                        VALUES 
                                            (:language_id, :topic_id)");

            foreach ($selected_languages as $language_id) {
                $stmt->bindParam(':language_id', $language_id, PDO::PARAM_INT);
                $stmt->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        session_start();
        $_SESSION['text-message'] = "The temporary icon-file <b>" . $_POST['temp-icon-file'] . "</> from <b>temp-uploads/</b> has been moved to <b>images/</b> and renamed to <b>" . $newFileName . "</b>.";
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