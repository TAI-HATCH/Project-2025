<?php

include "connection.php";
//http://localhost/project-2025/quiz.php?language-topic=1
echo "Hello" . " " . $_GET['language-topic'];

$topic_id = $_GET['language-topic'];
$table_name = "questions";

$stmt = $conn->prepare("SELECT question, form_content FROM $table_name WHERE languages_topic_id = :topic_id;");
$stmt->bindParam(':topic_id',$topic_id);
$stmt->execute();

$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo var_dump($questions);

?>