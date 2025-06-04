<?php

include "admin-log.php";
include_once "sql_query.php";

// Check whether the form was sent using the method=post and whether the request contains a file with the "name"="svg-file":
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES["svg-file"])) {

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

        // header("Location: admin-upload-success.php");
    } else {
        echo "Language name is required.";
    }

    // Processing the file:
    // https://www.w3schools.com/php/php_file_upload.asp
    $target_dir = "images/"; //specifies the directory where the file is going to be placed
    //Get a name that was previously generated in JS code:
    //basename() is a save way to get the name of the file without potentially dangerous paths:
    $newFileName = basename($_POST["newFileName"]);
    $target_file = $target_dir . $newFileName; //Form the path with a file name, that should be uploaded to the server
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); //holds the file extension of the file (in lower case)

       // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error

    echo "<pre>";
    var_dump($_FILES);
    echo "</pre>";

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["svg-file"]["tmp_name"], $target_file)) { //copy the temporary file to the server in the folder specified by $target_file
        ?>
            <script>
                console.log(`echo "The file " . <?php htmlspecialchars(basename($_FILES["svg-file"]["name"])) ?> . " has been uploaded to <?php $target_file ?>.";`);
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
    <form method="post" enctype="multipart/form-data"> <!-- attribute: enctype="multipart/form-data" specifies which content-type to use when submitting the form -->
        <section class="root-content">
            <div class="admin-add-content">
                <label class="admin-add-content-label" for="add-language">Add programming language</label>
                <input class="admin-add-content-input-field" type="text" id="add-language" name="add-language" placeholder="Type the language to add here." required onchange="createNameForSvgIcon()" onblur="innerTextToParagragh()">
            </div>

            <div class="admin-add-upload-svg">
                <label class="upload-svg-button-label" for="svg-file">Select an svg-file for uploading it to the server:</label>
                <input type="file" id="svg-file" name="svg-file" class="upload-svg-button" onchange="handleFileUpload()">
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
        <button type="submit" class="upload-to-database-button" >Upload to database</button>
    </form>

    <!-- Scripts for this page -->
    <script>
        // Script to create name for selected svg-file:

        // Function to form the name for svg-icon based on the language's name:
        function createNameForSvgIcon() {
            let inputLanguageNameElement = document.getElementById("add-language"); // define the value of input field
            let insertedLanguageName = inputLanguageNameElement.value.toLowerCase().replaceAll(" ", ""); //Normalize the name: convert to lowercase and remove all spaces
            let newName = `${insertedLanguageName}-icon.svg`; // Add the ending to the file name and extension
            return newName;
        }

        //Function to handle upload button:
        function handleFileUpload() {
            checkFileType(); // call the function to check whether the admin upload svg-type file or not
            let inputLanguageName = document.getElementById("add-language").value; // define the value of input field
            let svgInfoTextElement = document.getElementById("upload-svg-info-text"); //define the element p 
            if (inputLanguageName === "") {
                svgInfoTextElement.innerHTML = `First you should to enter the name of the programming language!`; //inform admin about the need to first enter the language name
                document.getElementById("add-language").focus(); // focus on the input field if the input field is empty
            } else {
                innerTextToParagragh(); // call the function to handle p element

                let newFileName = createNameForSvgIcon();
                console.log("the new name, that will be is", newFileName);
                let svgFile = document.getElementById("svg-file").files[0];
                const formData = new FormData();
                formData.append("svg-file", svgFile);
                formData.append("newFileName", newFileName);

                fetch("admin-add-language.php", {
                    method: "POST",
                    body: formData
                });
            }
        }

        function checkFileType() {
            let fileElement = document.getElementById("svg-file");
            let fileToCheck = '';
            if ('files' in fileElement) {
                fileToCheck = fileElement.files[0]["type"];
            };
            if (fileToCheck !== "image/svg+xml") {
                handleErrorPageChangeTheTypeOfTheFile();
            }
        }

        function handleErrorPageChangeTheTypeOfTheFile() {
            alert("Choose another type of the file. It should be .svg");
            document.getElementById("svg-file").value = "";
        }

        //Function to form and insert info-text depending on whether the admin entered a language name or not:
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