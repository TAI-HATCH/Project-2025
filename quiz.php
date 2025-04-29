<?php

session_start();


include "connection.php";
//http://localhost/project-2025/quiz.php?language-topic=1

if (!isset($_SESSION['topic_id'])) {


    //https://www.w3schools.com/php/php_superglobals_get.asp
    $topic_id = $_GET['language-topic'];
    $table_name = "questions";

    //https://www.w3schools.com/php/php_mysql_prepared_statements.asp
    $stmt = $conn->prepare("SELECT question_id, question, form_content FROM $table_name WHERE languages_topic_id = :topic_id;");
    $stmt->bindParam(':topic_id', $topic_id);
    $stmt->execute();

    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //https://www.w3schools.com/php/php_sessions.asp
    // Set session variables
    $_SESSION["topic_id"] = $topic_id;
    $_SESSION["current_question"] = 0;
    $_SESSION["questions"] = $questions;

} else if (isset($_GET['go']))  {

    echo $_SESSION["topic_id"];

    $go = $_GET['go'];
    if ($go == "next") {
        $_SESSION["current_question"] += 1;
        if ($_SESSION["current_question"] == count($_SESSION["questions"])){ // "When you get to the last question, hop on to the beginning of the question array."
            $_SESSION["current_question"] = 0;
        }
    } else if ($go == "previous") {
        $_SESSION["current_question"] -= 1;
        if ($_SESSION["current_question"] == 0){ // "When you get to the first question, hop on to the end of the question array."
            $_SESSION["current_question"] = count($_SESSION["questions"]);
        }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <?php
    $question = $_SESSION["questions"][$_SESSION["current_question"]];

    echo var_dump($question);
    ?>
    
    <a href="?go=next">Next</a>

    <a href="?go=previous">Back</a>

</body>
</html>
