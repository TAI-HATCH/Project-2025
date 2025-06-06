<?php

session_start(); // Open a php session on the server, is never shut down

include_once "sql_query.php";

$language_id = $_GET['language-id']; // Read the URL-parameter named 'language_id'

$topics = get_topics($language_id); // Call the function get_topics from sql_query.php

$_SESSION["topics"] = $topics;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select programming language's topic</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="./css/style.css">

    <script>
        // script for printing the connection status in console:
        console.log("<?php echo $connection_status; ?>")
    </script>
</head>

<body>
    <?php include 'header.php' ?>

    <section class="language-section">
        <div id="lang-icon-wrapper"></div>
        <h2>Select topic</h2>
    </section>

    <section class="topic-section">
        <p class="quiz" id="info-text"></p>
        <a href="selecting-language.php" class="hidden-btn" id="btn-return">
            <button type="button" class="return-button">Return to selection</button>
        </a>
        <ul class="topic-list" id="topic-list"> <!--Create an unordered list of icons, each of which corresponds to its own programming language's topic -->
            <!--Elements will be created by script -->
        </ul>
    </section>

    <script>
        // Script for filling the div with id="lang-icon-wrapper" by programming language's icon:
        // This div should display the icon in any case: regardless of whether there are topics for the selected programming language or not
        <?php
        if (isset($language_id)) {
            $language_name = get_selected_language_name($language_id);
            $language_icon_name = str_replace(" ", "-", strtolower($language_name));
        ?>

            langIcon = document.createElement("img");
            langIcon.classList.add("language-icon-img");
            langIcon.setAttribute("src", "./images/<?php echo $language_icon_name ?>-icon.svg");
            langIcon.setAttribute("alt", "Icon for <?php echo $language_name ?>");
            langIcon.setAttribute("height", "50");
            document.getElementById("lang-icon-wrapper").appendChild(langIcon);

            <?php
        }

        // Script for filling the list class="topic-list" with elements:
        if (!empty($topics)) {
            $i = 0; // Increment for if loop to decide whether or not to add arrow after  topic block
            foreach ($topics as $topic) { // Make a loop for every element in array of topics
                $topic_name = $topic["topic_name"]; // Store the name of the topic
                $icon_name = str_replace(" ", "-", strtolower($topic["topic_name"])); // Create a string based on the topic's name for later use as the icon name
                $topic_id = $topic["id"]; // Store the topic's id for later use for creating the link to the next page
                $i = $i + 1;
            ?>

                topicListItem = document.createElement("li");
                topicListItem.classList.add("topic-list-item");

                topicLink = document.createElement("a");
                topicLink.classList.add("topic-link");
                topicLink.setAttribute("title", "<?php echo $topic_name ?>"); // Create title for link
                //!!!Create the path for link with the id of selecting topic:
                topicLink.setAttribute("href", "quiz.php?language-topic=<?php echo $topic_id ?>");

                topicIconImg = document.createElement("img"); // Create element for icon
                topicIconImg.classList.add("topic-icon");
                topicIconImg.setAttribute("src", "./images/<?php echo $icon_name ?>-icon.svg"); // Create path to icon
                topicIconImg.setAttribute("alt", "Icon for <?php echo $topic_name ?>");
                topicIconImg.setAttribute("height", "100");
                topicLink.appendChild(topicIconImg);

                topicName = document.createElement("h3"); //Create header for each topic
                topicName.classList.add("topic-name");
                topicName.innerHTML = "<?php echo $topic_name ?>";
                topicLink.appendChild(topicName);
                topicListItem.appendChild(topicLink);
                document.getElementById("topic-list").appendChild(topicListItem);

                <?php
                if ($i < count($topics)) {
                ?>
                    arrowIcon = document.createElement("img");
                    arrowIcon.classList.add("down-arrow-icon");
                    arrowIcon.setAttribute("src", "./images/down-arrow-icon.svg");
                    arrowIcon.setAttribute("alt", "Icon for down arrow");
                    arrowIcon.setAttribute("height", "100");
                    topicListItem.appendChild(arrowIcon);
            <?php
                }
            }
        } else {
            $language_name = get_selected_language_name($language_id);
            ?>
            let btnToShow = document.getElementById("btn-return");
            btnToShow.classList.remove("hidden-btn");
            let elementForInfoMessage = document.getElementById("info-text");
            let textMessage = `Sorry, there are no questions available for language <?php echo $language_name; ?> - yet!`;
            elementForInfoMessage.innerHTML = textMessage;

        <?php
        }
        ?>
    </script>

</body>

</html>