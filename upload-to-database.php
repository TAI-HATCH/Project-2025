<?php
include_once "connection.php";
include_once "sql_query.php";

$form_type = $_POST['form_type'] ?? null;


switch ($form_type) {
    case 'add-language':
        $language_name = $_POST['add-language'] ?? null;
        $selected_topics = $_POST['topic'] ?? [];

        $stmt = $conn->prepare("INSERT INTO 
                                        languages (language_name) 
                                    VALUES (:language_name)");
        $stmt->bindParam(":language_name", $language_name);
        $stmt->execute();

        $language_id = $conn->lastInsertId();

        if (!empty($selected_topics)) {
            $stmt = $conn->prepare("INSERT INTO 
                                            languages_topic (language_id, topic_id) 
                                        VALUES (:language_id, :topic_id)");

            foreach ($selected_topics as $topic_id) {
                $stmt->bindParam(':language_id', $language_id, PDO::PARAM_INT);
                $stmt->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        header("Location: admin-upload-success.php");
        exit;
        break;

    case 'add-topic':
        $topic_name = $_POST['add-topic'] ?? null;

        $stmt = $conn->prepare("INSERT INTO 
                                    topics (topic_name) 
                                VALUES 
                                    (:topic_name)");
        $stmt->bindParam(":topic_name", $topic_name);
        $stmt->execute();

        header("Location: admin-upload-success.php");
        exit;

    case 'add_question':
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

                header("Location: admin-upload-success.php");
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
