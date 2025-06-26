<?php
$topic_name = $_POST['add-topic'] ?? null;
$selected_languages = $_POST['language'] ?? [];
$selected_questions = $_POST['question'] ?? [];
$selected_answers = $_POST['answer'] ?? [];
// echo "<pre>";
// var_dump($selected_questions);
// echo "</pre>";

// Statement if the DB will be updated:
if (isset($sqlAction) && $sqlAction == 'sql-update') {
    session_start();
    $topic_id = $_SESSION["topic_id"];
    // echo "<pre>";
    // var_dump($topic_id);
    // echo "</pre>";
    // Set is_active = 1 for language:
    $stmt = $conn->prepare("UPDATE 
                                    topics 
                                SET 
                                    is_active = '1' 
                                WHERE 
                                    topic_id = :topic_id;");
    $stmt->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
    $stmt->execute();

    ////Set is_active = 1 for all languages, that were deactivated and create a new pair of language-topic if there is no such pair with a specific id:
    if (!empty($selected_languages)) { // Select content from checkbox
        //Important! In SQL for the table 'languages_topic' should be an unique key for combinations language_id + topic_id
        $stmt = $conn->prepare("INSERT INTO 
                                        languages_topic (language_id, topic_id, is_active)
                                    VALUES
                                        (:language_id, :topic_id, 1)
                                    ON DUPLICATE KEY UPDATE 
                                        is_active = 1;");

        foreach ($selected_languages as $language_id) {
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

    // session_start();
    $_SESSION['text-message'] = "The temporary icon-file <b>" . $_POST['temp-icon-file'] . "</> from <b>temp-uploads/</b> has been moved to <b>images/</b> and renamed to <b>" . $newFileName . "</b>.";
}
?>
