<?php

include_once "connection.php";

// echo $connection_status; // Prints out "Connection successful" if successful

function get_languages() // Function to get an array of programming languages from the DB
{
    global $conn;

    //Send request to the DB to the table Languages without any parameters
    // because we need to get all the records from the tabel:

    $stmt = $conn->prepare("SELECT 
                                language_id, language_name, is_active 
                            FROM 
                                languages
                            WHERE
                                is_active = 1;");
    $stmt->execute(); // Run the thing

    $languages = $stmt->fetchAll(PDO::FETCH_ASSOC); // Take all the languages that we just fetched and put it into an array

    return $languages;
}

function get_all_languages() // Function to get an array of ALL programming languages from the DB even not active
{
    global $conn;

    //Send request to the DB to the table Languages without any parameters
    // because we need to get all the records from the tabel:

    $stmt = $conn->prepare("SELECT 
                                language_id, language_name, is_active 
                            FROM 
                                languages");
    $stmt->execute(); // Run the thing

    $all_languages = $stmt->fetchAll(PDO::FETCH_ASSOC); // Take all the languages that we just fetched and put it into an array

    return $all_languages;
}

function get_selected_language_name($language_id)
{
    global $conn;

    //Send request to the DB to the table Languages with language_id:

    $stmt = $conn->prepare("SELECT 
                                language_id, language_name, is_active 
                            FROM 
                                languages
                            WHERE
                                is_active = 1
                            AND
                                language_id = :language_id;");
    $stmt->bindParam(':language_id', $language_id); // Binding together (reflecting) :language_id and $language_id
    $stmt->execute(); // Run the thing

    $language = $stmt->fetch(PDO::FETCH_ASSOC); // Take the info about the language into an array

    return $language ? $language["language_name"] : null; //ternary operator
}

function get_topics($language_id) //Function to get an array of topics from the DB with the specified language id:
{

    global $conn; //give access to the variable $conn defining in the connection.php
    $table_name = "languages_topic"; // Create a variable with the table name

    //Select all records from the table "languages_topic" with language_id = $language_id;
    //Store the name of the programming language selected on the previous page ("selecting_language.php") in order to find its corresponding icon;
    //Store the id of topic in order to send it on the next page ("quiz.php")
    $stmt = $conn->prepare("SELECT 
                                L.language_name, T.topic_name, $table_name.id, $table_name.is_active 
                            FROM 
                                $table_name, `languages` 
                            AS 
                                L, `topics` 
                            AS 
                                T 
                            WHERE 
                                $table_name.is_active = 1
                            AND
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

function get_all_existing_topics($language_id) //Function to get an array of topics from the DB with the specified language id:
{
    global $conn; //give access to the variable $conn defining in the connection.php
    $table_name = "languages_topic"; // Create a variable with the table name
    $stmt = $conn->prepare("SELECT 
                                    L.language_name, T.topic_name, $table_name.id, $table_name.is_active 
                                FROM 
                                    $table_name, 
                                    `languages` AS L,
                                     `topics` AS T 
                                WHERE 
                                    L.language_id = $table_name.language_id 
                                AND 
                                    T.topic_id = $table_name.topic_id 
                                AND 
                                    $table_name.language_id = :language_id;"); // Go into db, take hold of topics for the specific language-id
    $stmt->bindParam(':language_id', $language_id); // Binding together (reflecting) :language_id and $language_id
    $stmt->execute(); // Run the thing

    $all_topics = $stmt->fetchAll(PDO::FETCH_ASSOC); // Take all the data that we just fetched and put it into an array
    return $all_topics;
}

function get_questions($topic_id)
{

    $table_name = "questions"; // Create a variable with the table name

    global $conn; //give access to the variable $conn defining in the connection.php

    //https://www.w3schools.com/php/php_mysql_prepared_statements.asp
    $stmt = $conn->prepare("SELECT 
                                question_id, question, form_content, is_active 
                            FROM 
                                $table_name 
                            WHERE 
                                languages_topic_id = :topic_id
                            AND 
                                is_active = 1;"); // Go into db, take hold of questions for the specific language-topic
    $stmt->bindParam(':topic_id', $topic_id); // Binding together (reflecting) :topic_id and $topic_id
    $stmt->execute(); // Run the thing

    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC); // Take all the data the we just fetched and put in into an array
    return $questions;
}

function get_all_existing_questions($language_id)
{

    $table_name = "questions"; // Create a variable with the table name
    $all_related_topics = get_all_existing_topics($language_id);
    $all_questions = [];

    global $conn; //give access to the variable $conn defining in the connection.php

    foreach ($all_related_topics as $topic) {

        // echo "<pre>";
        // var_dump($topic['id']);
        // echo "</pre>";

        //https://www.w3schools.com/php/php_mysql_prepared_statements.asp
        $stmt = $conn->prepare("SELECT question_id, languages_topic_id, question, form_content, is_active
                                FROM 
                                    $table_name 
                                WHERE 
                                    languages_topic_id = :languages_topic_id"); // Go into db, take hold of questions for the specific language-topic
        $stmt->bindParam(':languages_topic_id', $topic['id']); // Binding together (reflecting) :topic_id and $topic_id
        $stmt->execute(); // Run the thing

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC); // Take all the data the we just fetched and put in into an array 

        $all_questions = array_merge($all_questions, $results);
    }

    return $all_questions;
}

function get_answers($question_id)
{
    global $conn; //give access to the variable $conn defining in the connection.php

    //Send request to the DB to the table Answers with the question_id = $question["question_id"]
    $stmt = $conn->prepare("SELECT 
                                input_name, answer_value, is_active 
                            FROM 
                                answers 
                            WHERE
                                 question_id = :question_id
                            AND
                                is_active = 1;"); // Go into db, take hold of answer for the specific question
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
                                L.language_name, T.topic_name, $table_name.id, $table_name.language_id, $table_name.is_active 
                            FROM 
                                $table_name, 
                                `languages` AS L, 
                                `topics` AS T 
                            WHERE
                                $table_name.is_active = 1
                            AND 
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
    // Run the thing:
    $stmt->execute();

    $selected_topic_info = $stmt->fetchAll(PDO::FETCH_ASSOC); // Take all the data that we just fetched and put it into an array
    return $selected_topic_info;
}

//Function to determine the length of each answer value:
//This data will be used to adjust the width of input fields in html and css:
function get_answer_value_length($question_id)
{

    global $conn; //give access to the variable $conn defining in the connection.php

    //Send request to the DB to the table Answers with the question_id = $question["question_id"]
    $stmt = $conn->prepare("SELECT 
                                input_name, answer_value, is_active 
                            FROM 
                                answers 
                            WHERE 
                                question_id = :question_id
                            AND
                                is_active = 1;"); // Go into db, take hold of answer for the specific question
    $stmt->bindParam(':question_id', $question_id); // Binding together (reflecting) :question_id and $question["question_id"]s
    $stmt->execute(); // Run the thing

    $current_answers = $stmt->fetchAll(PDO::FETCH_ASSOC); // Take all the answers that we just fetched and put it into array

    //Create array to store the names of input fields and its values:
    $answers_width = array();

    //Loop through the array with current answers:
    foreach ($current_answers as $correct_answer) {
        $input_answer_name = $correct_answer["input_name"];
        $input_answer_value = $correct_answer["answer_value"];
        if (!isset($answers_width[$input_answer_name])) {
            //Create an associative array with a key equal to input name, and a value as an empty array:
            $answers_width[$input_answer_name] = array();
        }
        //Add the value to the array with string width:
        $answers_width[$input_answer_name][] = strlen($input_answer_value);
    }
    return $answers_width;
}

function get_all_topics() // Function to fetch all topics in the table "topics" for admin shenanigans
{
    global $conn;

    $stmt = $conn->prepare("SELECT 
                                topic_id, topic_name, is_active 
                            FROM 
                                topics
                            WHERE
                                is_active = 1 
                            ORDER BY 
                                topic_id;");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to handle the selected icon-file
function handleTempIconFile($temp_file_name)
{
    global $conn;

    //specify the directory to store TEMPORARY files:
    $temp_upload_dir = "temp-uploads/";
    //Get a temporary file with the initial name:
    $tempFile = $_FILES["svg-file"]["tmp_name"];
    //Set the unique name to the file just to store it as temporary file before uploading to the server:
    // $temp_file_name = generateTempFileName();
    //Set the path to store the file (folder/temp-name.extension):
    $temp_file_path = $temp_upload_dir . $temp_file_name;

    $uploadOk = 1;

    // Check if file already exists
    if (file_exists($temp_file_path)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error  
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        //copy the temporary file to the server with a new name in the folder specified by $target_file:
        //Syntax: move_uploaded_file(file, dest):
        // file - filename of the uploaded file
        //dest - the new location for the file
        if (move_uploaded_file($tempFile, $temp_file_path)) {
?>
            <script>
                console.log(`The temporary file <?php echo $temp_file_name ?>  has been uploaded to <?php echo $temp_file_path ?>.`);
            </script>
        <?php

        } else {
        ?>
            <script>
                console.log(`Sorry, there was an error uploading your file.`);
            </script>
<?php
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

function generateNewFileName($element_name) {
    //Get the extension of the selected file by admin:
    $fileExtension = pathinfo($_FILES["svg-file"]["name"], PATHINFO_EXTENSION);
    //create a new NAME for the file according to the defined rules for uploading to the server without extension:
    $newFileName = str_replace(" ", "-", strtolower($element_name)) . "-icon";
    //create the full  NAME for the file with extension:
    $newFile = $newFileName . '.' . $fileExtension;
    return $newFile;
}

function generateTempFileName() {
    //Set the unique name to the file just to store it as temporary file before uploading to the server:
        $temp_file_name = uniqid() . "-" . $_FILES["svg-file"]["name"];
        return $temp_file_name;
    
}

?>