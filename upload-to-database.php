<?php
include_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST"  && isset($_POST['confirm_upload'])) { // Grab the inputted data from the form if form's request method is is "post" and if upload has been confirmed on preview page
    $language_id = $_POST['language_id'] ?? null; // The "$_POST"-variable has the inputted data of [*id*]
    $topic_id = $_POST['topic_id'] ?? null; // ?? Means that if the array [] is empty, return null instead of having an error on hand
    $question_text = $_POST['question'] ?? '';
    $text_before = $_POST['text_before'] ?? '';
    $text_after = $_POST['text_after'] ?? '';
    $answer_value = $_POST['answer'] ?? '';

    if ($language_id && $topic_id && $question_text && $answer_value) {
        try {
            // Step 1: Get languages_topic_id
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

            if (!$languages_topic_id) {
                echo "Error: No matching language-topic combination found.";
                exit;
            }

            // Step 2: Build form_content
            $form_content = "<div><span>" . htmlspecialchars($text_before) . "</span><input type=\"text\" name=\"answer_one\"><span>" . htmlspecialchars($text_after) . "</span></div>";

            // Step 3: Insert into questions table
            $insert = $conn->prepare("INSERT INTO 
                                        questions (languages_topic_id, question, form_content) 
                                     VALUES (?, ?, ?)");
            $insert->execute([$languages_topic_id, $question_text, $form_content]);

            // Step 4: Get the ID of the question just inserted
            $question_id = $conn->lastInsertId();

            // Step 5: Insert the answer
            $answer_insert = $conn->prepare("INSERT INTO 
                                                    answers (question_id, input_name, answer_value) 
                                             VALUES (?, ?, ?)");
            $answer_insert->execute([$question_id, 'answer_one', $answer_value]);

            header("Location: admin-upload-success.php");
        } catch (PDOException $e) {
            echo "Error uploading: " . $e->getMessage();
        }
    } 
} else {
    echo "Invalid request.";
}
?>
