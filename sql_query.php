
<?php

include_once "connection.php";

// echo $connection_status; // Prints out "Connection successful" if successful

function get_languages() // Function to get an array of programming languages from the DB
{
    global $conn;

    //Send request to the DB to the table Languages without any parameters
    // because we need to get all the records from the tabel:

    $stmt = $conn->prepare("SELECT language_id, language_name FROM languages;");
    $stmt->execute(); // Run the thing

    $languages = $stmt->fetchAll(PDO::FETCH_ASSOC); // Take all the languages that we just fetched and put it into an array

    return $languages;
}

function get_topics($language_id) //Function to get an array of topics from the DB with the specified language id:
{

    global $conn; //give access to the variable $conn defining in the connection.php
    $table_name = "languages_topic"; // Create a variable with the table name

    //Select all records from the table "languages_topic" with language_id = $language_id;
    //Store the name of the programming language selected on the previous page ("selecting_language.php") in order to find its corresponding icon;
    //Store the id of topic in order to send it on the next page ("quiz.php")
    $stmt = $conn->prepare("SELECT 
                                L.language_name, T.topic_name, $table_name.id 
                            FROM 
                                $table_name, `languages` 
                            AS 
                                L, `topics` 
                            AS 
                                T 
                            WHERE 
                                L.language_id = $table_name.language_id 
                            AND 
                                T.topic_id = $table_name.topic_id 
                            AND 
                                $table_name.language_id = :language_id;"); // Go into db, take hold of topics for the specific language-id
    $stmt->bindParam(':language_id', $language_id); // Binding together (reflecting) :language_id and $language_id
    $stmt->execute(); // Run the thing

    $topics = $stmt->fetchAll(PDO::FETCH_ASSOC); // Take all the data that we just fetched and put it into an array
    return $topics;
}

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
}

function get_selected_topic_info($language_topic_id)
{
    global $conn;
    // Create a variable with the table name:
    $table_name = "languages_topic";

    $stmt = $conn->prepare("SELECT 
                                L.language_name, T.topic_name, $table_name.id, $table_name.language_id 
                            FROM 
                                $table_name, 
                                `languages` AS L, 
                                `topics` AS T 
                            WHERE 
                                L.language_id = $table_name.language_id 
                            AND 
                                T.topic_id = $table_name.topic_id 
                            AND 
                                $table_name.id = :language_topic_id;
                            AND
                                $table_name.language_id = :language_id"); 
    // Binding together (reflecting) :language_topic_id and $language_topic_id:
    $stmt->bindParam(':language_topic_id', $language_topic_id); 
    $stmt->bindParam(':language_id', $language_id); 
    // Run the thing:s
    $stmt->execute(); 

    $selected_topic_info = $stmt->fetchAll(PDO::FETCH_ASSOC); // Take all the data that we just fetched and put it into an array
    return $selected_topic_info;
}

?>
