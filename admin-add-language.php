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

    //specifies the directory where the file is going to be placed:
    $target_dir = "images/";
    //Get the temporary file from the server with original name:
    $tempFile = $_FILES["svg-file"]["tmp_name"];
    //Get the extension of the selected file by admin:
    $fileExtension = pathinfo($_FILES["svg-file"]["name"], PATHINFO_EXTENSION);
    //create a new name for the file according to the defined rules for uploading to the server: 
    $newFileName = str_replace(" ", "-", strtolower($language_name)) . "-icon";
    $newFile = $newFileName . '.' . $fileExtension;

    $target_file = $target_dir . $newFile; //Form the path with a file name, that should be uploaded to the server
    $uploadOk = 1;
    // $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); //holds the file extension of the file (in lower case)

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error

    // echo "<pre>";
    // var_dump($_FILES);
    // echo "</pre>";

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        //copy the temporary file to the server with a new name in the folder specified by $target_file:
        if (move_uploaded_file($tempFile, $target_file)) {
?>
            <script>

                console.log(`The file <?php echo htmlspecialchars($_FILES["svg-file"]["name"]) ?>  has been uploaded to <?php echo $target_file ?>.`);
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

    <?php include 'admin-header.php' ?>

    <?php include 'admin-banner.php' ?>
    <form method="post" enctype="multipart/form-data" action="admin-preview.php"> <!-- attribute: enctype="multipart/form-data" specifies which content-type to use when submitting the form -->
        <input type="hidden" name="form_type" value="add-language">
        
        <section class="root-content">
            <div class="admin-add-content">
                <label class="admin-add-content-label" for="add-language">Add programming language</label>
                <input class="admin-add-content-input-field" type="text" id="add-language" name="add-language" placeholder="Type the language to add here." required onchange="createNameForSvgIcon('add-language')" onblur="innerTextToParagragh('add-language')">
            </div>

            <div class="admin-add-upload-svg">
                <label class="upload-svg-button-label" for="svg-file">Select an svg-file for uploading it to the server:</label>
                <input type="file" id="svg-file" name="svg-file" class="upload-svg-button" onchange="handleFileUpload('add-language')">
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

                            <li> <!--It is important to use name="topic[]" with [] in the case with checkbox. 
                                If we will not, then in PHP in $_POST['topic'] we recieve only the value of last element -->
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
        <button type="submit" class="upload-to-database-button">Preview and upload</button>
    </form>

    <!-- Scripts for this page -->
    <script src="./js/upload-icon.js"></script>
</body>

</html>