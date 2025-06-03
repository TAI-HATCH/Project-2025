<?php

include "admin-log.php";
include_once "sql_query.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo var_dump($_POST['topic']); // Prints the content of the array "topic" in the top of the page
    $language_name = $_POST['add-language'] ?? null; // If there is no input, return "null"
    $selected_topics = $_POST['topic'] ?? []; // If nothing in the array, return an empty array "[]"

    if (!empty($language_name)) {
        $stmt = $conn->prepare("INSERT INTO 
                                    languages (language_name) 
                                VALUES 
                                    (:language_name)");
        $stmt->bindParam(":language_name", $language_name, PDO::PARAM_STR);
        $stmt->execute();

        $language_id = $conn->lastInsertId();

        if (!empty($selected_topics)) { // Select content from checkbox
            $stmt = $conn->prepare("INSERT INTO 
                                        languages_topic (language_id, topic_id) 
                                    VALUES 
                                        (:language_id, :topic_id)");

            $stmt->bindParam(':language_id', $language_id, PDO::PARAM_INT);
            $stmt->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);

            foreach ($selected_topics as $topic_id) {
                $stmt->execute();
            }
        }

        header("Location: admin-upload-success.php");
    } else {
        echo "Language name is required.";
    }
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
        rel="stylesheet" />
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <h1 hidden>HATCH</h1>

    <?php include 'header.php' ?>

    <?php include 'admin-banner.php' ?>
    <form method="post">
        <section class="root-content">
            <div class="admin-add-content">
                <label class="admin-add-content-label" for="add-language">Add programming language</label>
                <input class="admin-add-content-input-field" type="text" id="add-language" name="add-language" placeholder="Type the language to add here." required onchange="createNameForSvgIcon()"  onblur="innerTextToParagragh()">
            </div>

            <div class="admin-add-upload-svg">
                <label class="upload-svg-button-label" for="svg-file">Select an svg-file for uploading it to the server:</label>
                <input type="file" id="svg-file" name="svg-file" class="upload-svg-button" onchange="innerTextToParagragh()">
                <p class="upload-svg-info-text" id="upload-svg-info-text"></p>
            </div>

            <div class="admin-add-content-checkbox-selection-content">
                <label for="admin-add-content-checkbox-selection-content">Select topics (optional)</label>
                <div class="admin-add-content-checkbox-selection">
                    <label for="admin-add-content-checkbox-selection-list">Topics:</label>


                    <ul>
                        <?php

                        $topics = get_all_topics(); // Fetch all topics, function found in sql_query.php

                        foreach ($topics as $topic) : ?>

                            <li>
                                <input class='checkbox' type='checkbox'
                                    id="topic<?= ($topic['topic_id']) ?>"
                                    name="topic[]"
                                    value="<?= ($topic['topic_id']) ?>">

                                <!--id: Print ID for this topic;
                                    name: collect all selected topics to array topic[];
                                    value: define value for checkbox-->

                                <label for="topic<?= ($topic['topic_id']) ?>"> <!--Link checkbox to topic-->
                                    <?= ($topic['topic_name']) ?> <!--Print topic-->
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </div>
        </section>
        <button type="submit" class="upload-to-database-button">Upload to database</button>
    </form>

    <!-- Scripts for this page -->
    <script>
        // Script to create name for selected svg-file:

        // Function to form the name for svg-icon based on the language's name:
        function createNameForSvgIcon() {
            let inputLanguageNameElement = document.getElementById("add-language"); // define the value of input field
            let insertedLanguageName = inputLanguageNameElement.value.toLowerCase().replaceAll(" ", ""); //Normalize the name: convert to lowercase and remove all spaces
            // console.log("Inserted language name is", insertedLanguageName);
            let newName = `${insertedLanguageName}-icon.svg`; // Add the ending to the file name and extension
            // console.log("New name is:", newName); 
            return newName;
        }

        //Function to handle upload button:
        function handleFileUpload() {
            let inputLanguageName = document.getElementById("add-language").value; // define the value of input field
            let svgInfoTextElement = document.getElementById("upload-svg-info-text"); //define the element p 
            if (inputLanguageName === "") {
                console.log("The name will be displaed later");
                svgInfoTextElement.innerHTML = `First you should to enter the name of the programming language!`; //inform admin about the need to first enter the language name
                document.getElementById("add-language").focus(); // focus on the input field if the input field is empty
            } else {
                innerTextToParagragh(); // call the function to handle p element
            }
        }

        
        // let fileElement = document.getElementById("svg-file");
        // console.log("Just an element:", fileElement);
        // console.log("Just files:", fileElement.files);

        // // console.log("Just an element:", fileElement[0]["name"]);
        // if ('files' in fileElement){
        //     console.log("fileElement.files[0]:", fileElement.files[0]);
        //     console.log("Just type of the file:", fileElement.files[0]["type"]);
        //         console.log("fileElement.files[0]['name']:", fileElement.files[0]["name"]);  
        //     };                 

        //Function to form and insert info-text depending on whether the user entered a language name or not:
        function innerTextToParagragh() {
            let infoText = "";
            let svgInfoTextElement = document.getElementById("upload-svg-info-text"); //define the element p 
            let inputLanguageName = document.getElementById("add-language").value; // define the value of input field
            if (inputLanguageName === "") {
                infoText = `First you should to enter the name of the programming language!`; // form the text
                document.getElementById("add-language").focus(); // focus on the input field if the input field is empty
            } else {
                let newName = createNameForSvgIcon();
                infoText = `The icon-file will be uploaded to the project under the name: <b>${newName}</b>`; // form the text
            }
            svgInfoTextElement.innerHTML = infoText; // input text in the p element
        }

    </script>
</body>

</html>