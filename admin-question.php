<?php
session_start();

// Jos ei admin, ohjataan takaisin kirjautumissivulle
if (!isset($_SESSION['is_admin'])) {
    header("Location: adminpw.php");
    exit;
}

include 'admin-banner.php';



// Käsittele uloskirjautuminen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: adminpw.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to HATCH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="./css/style.css">
  </head>
  <body>
    <h1 hidden>HATCH</h1>

    <?php
        if (isset($_SESSION['is_admin'])) {
    ?>
        <form method="POST">
        <button class="logout-button" type="submit" name="logout">Log out</button>
        </form>
    <?php
        }
    ?>

    <?php include 'header.php'?>

    <section class="root-content">
    <div class="admin-question-upload">
        
        <form action="upload-to-database.php" method="POST">
            <div class="admin-choice">
                <label for="admin-programming-language-choice">Choose programming language</label>
                <select class="admin-dropdown-menu" name="language_id" id="language_id">
                       <?php
                        include_once "connection.php";

                        try {
                            $stmt = $conn->query("SELECT 
                                                    language_id, language_name 
                                                FROM 
                                                    languages 
                                                ORDER BY 
                                                    language_name 
                                                ASC");

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // As long as there is a language in the database, fetch it
                                $id = htmlspecialchars($row['language_id']);
                                $name = htmlspecialchars($row['language_name']);
                                echo "<option value=\"$id\">$name</option>"; // Add them to the dropdown menu as an option
                            }
                        } catch (PDOException $e) {
                            echo "<option disabled>Error loading languages</option>";
                        }
                        ?>
                </select>
            </div>
            <div class="admin-choice">
                <label for="topic-choice">Choose topic</label>
                <select class="admin-dropdown-menu" name="topic_id" id="topic_id">
                  <?php
                        include_once "connection.php";

                        try {
                            $stmt = $conn->query("SELECT 
                                                    topic_id, topic_name 
                                                FROM 
                                                    topics
                                                ORDER BY 
                                                    topic_name 
                                                ASC");

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // As long as there is a topic in the database, fetch it
                                $id = htmlspecialchars($row['topic_id']);
                                $name = htmlspecialchars($row['topic_name']);
                                echo "<option value=\"$id\">$name</option>"; // Add them to the dropdown menu as an option
                            }
                        } catch (PDOException $e) {
                            echo "<option disabled>Error loading languages</option>";
                        }
                        ?>


                    </select>
            </div>
        <div class="admin-input">
            <label for="question-text">Question text</label>
            <input class="admin-input-field" type="text" id="question_id" name="question" placeholder="Type your question text here.">
        </div>
            <div class="admin-question-content">    
                <label for="question-content">Question content</label>
                <div class="admin-input-content">
                    <label for="text-before">Text before user input field:</label>
                    <input class="admin-input-field" type="text" id="text_before" name="text_before" placeholder="Type the question text before the user input field.">
                </div>
                <div class="admin-input-content">
                    <label for="answer">Answer for the user to type:</label>
                    <input class="admin-input-field" type="text" id="answer_value" name="answer" placeholder="Type the answer for the user to type.">
                </div>
                <div class="admin-input-content"> 
                    <label for="text-after">Text after user input field:</label>
                    <input class="admin-input-field" type="text" id="text_after" name="text_after" placeholder="Type the question text after the user input field.">
                </div>
            </div>
        
        <button type="submit" class="upload-to-database-button">Upload to database</button>
        </form> 
      </div>
    </section>
  </body>
</html>
