
<?php

include_once "connection.php";

// echo $connection_status; // Prints out "Connection successful" if successful

function get_questions($topic_id)
{
    $table_name = "questions"; // Create a variable with the table name

    global $conn; //give access to the variable $conn defining in the connection.php

    //https://www.w3schools.com/php/php_mysql_prepared_statements.asp
    $stmt = $conn->prepare("SELECT question_id, question, form_content FROM $table_name WHERE languages_topic_id = :topic_id;"); // Go into db, take hold of questions for the specific language-topic
    $stmt->bindParam(':topic_id', $topic_id); // Binding together (reflecting) :topic_id and $topic_id
    $stmt->execute(); // Run the thing

    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC); // Take all the data the we just fetched and put in into an array
    return $questions;
}

function get_answers($question_id)
{
    global $conn; //give access to the variable $conn defining in the connection.php

    //Send request to the DB to the table Answers with the question_id = $question["question_id"]
    $stmt = $conn->prepare("SELECT input_name, answer_value FROM answers WHERE question_id = :question_id;"); // Go into db, take hold of answer for the specific question
    $stmt->bindParam(':question_id', $question_id); // Binding together (reflecting) :question_id and $question["question_id"]s
    $stmt->execute(); // Run the thing

    $answers = $stmt->fetchAll(PDO::FETCH_ASSOC); // Take all the answers that we just fetched and put it into an array
    return $answers;

    global $conn; //give access to the variable $conn defining in the connection.php


}

function get_languages()
{
    global $conn;

    //$table_name = "languages"; //assign the value 'languages'(the table's name in our DB) to the variable $table_name

    //Send request to the DB to the table Languages without any parameters
    // because we need to get all the records from the tabel:

    $stmt = $conn->prepare("SELECT language_id, language_name FROM languages;");
    $stmt->execute(); // Run the thing

    $languages = $stmt->fetchAll(PDO::FETCH_ASSOC); // Take all the languages that we just fetched and put it into an array

    return $languages;
}
?>
