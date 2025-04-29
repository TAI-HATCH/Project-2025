<?php

session_start(); // Open a php session on the server, is never shut down


include "connection.php";
//http://localhost/project-2025/quiz.php?language-topic=1

if (!isset($_SESSION['topic_id'])) { // If topic_id is not set in the session, run


    //https://www.w3schools.com/php/php_superglobals_get.asp
    $topic_id = $_GET['language-topic']; // Read the URL-get-parameter named language-topic
    $table_name = "questions"; // Create a variable with the table name

    //https://www.w3schools.com/php/php_mysql_prepared_statements.asp
    $stmt = $conn->prepare("SELECT question_id, question, form_content FROM $table_name WHERE languages_topic_id = :topic_id;"); // Go into db, take hold of questions for the specific language-topic
    $stmt->bindParam(':topic_id', $topic_id); // Binding together (reflecting) :topic_id and $topic_id
    $stmt->execute(); // Run the thing

    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC); // Take all the data the we just fetched and put in into an array

    //https://www.w3schools.com/php/php_sessions.asp
    // Set session variables
    $_SESSION["topic_id"] = $topic_id;
    $_SESSION["current_question"] = 0;
    $_SESSION["questions"] = $questions;

} else if (isset($_GET['go']))  { // If URL-get-parameter with name "go" is set, go to the next question

    echo $_SESSION["topic_id"];

    $go = $_GET['go'];
    if ($go == "next") {
        $_SESSION["current_question"] += 1;
        if ($_SESSION["current_question"] == count($_SESSION["questions"])) { // "When you get to the last question, hop on to the beginning of the question array."
            $_SESSION["current_question"] = 0;
        }
    } else if ($go == "previous") {
        $_SESSION["current_question"] -= 1;
        if ($_SESSION["current_question"] < 0) { // "When you get to the first question, hop on to the end of the question array."
            $_SESSION["current_question"] = count($_SESSION["questions"])-1;
        }
}
}

$question = $_SESSION["questions"][$_SESSION["current_question"]]; // Identify the current question from the "questions"-array
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/style.css">
    <script> // script for printing the connection status in console:
        console.log("<?php echo $connection_status;?>")
    </script>
</head>
<body>
    <br>
    <header class="root-header">
    <div class="logo">
        <a href="index.html">HATCH</a>
      </div>
      <nav>
        <ul class="nav-list">
          <li class="nav-list-item">
            <a href="quiz.php?language-topic=1" class="nav-link">Training</a>
          </li>
          <li class="nav-list-item">
            <a href="#" class="nav-link">About</a>
          </li>
          <li class="nav-list-item">
            <a href="#" class="nav-link">Sign up</a>
          </li>
          <li class="nav-list-item">
            <a href="#" class="nav-link">Login</a>
          </li>
        </ul>
      </nav>
    </header>
    <!--?php

    //echo var_dump($question); // Output the question onto the web page

    ?-->
    <p><?php echo $question["question"];?></p>

    <form action="" method="POST" class="code-snippet">
        <?php echo $question["form_content"];?>
    </form>

    <!-- <br> -->
    <a href="?go=previous">Back</a>
    <a href="?go=next">Next</a> <!--Link to the next question-->

</body>
</html>
