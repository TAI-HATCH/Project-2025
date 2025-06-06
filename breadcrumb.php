<?php

//session_start(); // Open a php session on the server, is never shut down

include_once "sql_query.php";

//Check if there is an array $selected_topic_info in Session:
if (isset($_GET['language-topic'])) {
    // Read the URL-parameter named 'language_topic' and store it in variable
    // This variable is an id of the selected topic for sertain selected language - table 'languages_topic' in DB
    $language_topic_id = $_GET['language-topic'];

    // Call the function get_selected_topic_info from sql_query.php
    // and store information about selected topic:
    $selected_topic_info = get_selected_topic_info($language_topic_id);

    $_SESSION["selected_topic_info"] = $selected_topic_info;
} else {
    $selected_topic_info = $_SESSION["selected_topic_info"];
}

?>

<ul class="breadcrumb" id="breadcrumb">
    <!-- There is an item for selected language icon: -->
    <li class="breadcrumb-item" id="language-section">
        <a href="http://" id="language-section-link">
            <img id="language-section-icon" height="50">
        </a>
    </li>
    <!-- There is an item for arrow: -->
    <li>
        <img src="./images/right-arrow-icon.svg" alt="Right arrow" height="40">
    </li>
    <!-- There is an item for selected topic icon and name: -->
    <li class="breadcrumb-item" id="topic-section">
        <img src="" alt="" id="topic-section-icon" height="40">
        <h3 id="topic-section-name" class="topic-section-name"></h3>
    </li>
</ul>

<script>
    // Script for filling the breadcrumb id="breadcrumb" with icons for selected language and topic:
    <?php
        // store the id of selected language:
        $lang_id = $selected_topic_info[0]["language_id"];
        // string to form the "src"-element for language icon:
        $language_icon_name = strtolower($selected_topic_info[0]["language_name"]);
        // string to form the "src"-element for topic icon:
        $topic_icon_name = str_replace(" ", "-", strtolower($selected_topic_info[0]["topic_name"]));
        // string to form the "h3"-element for topic name:
        $topic_name = $selected_topic_info[0]["topic_name"];
    ?>

        // Java script code:

        //Add attributes to the element "a" with link to the page with selecting topic for selected language:
        topicLink = document.getElementById("language-section-link");
        topicLink.setAttribute("href", "selecting-topic.php?language-id=<?php echo $lang_id ?>");
        //Add attributes to the element img with icon for language:
        langIcon = document.getElementById("language-section-icon");
        langIcon.setAttribute("src", "./images/<?php echo $language_icon_name ?>-icon.svg");
        langIcon.setAttribute("alt", "Icon for <?php echo $language_icon_name ?>");

        //Add attributes to the element img with icon for topic:
        topicIcon = document.getElementById("topic-section-icon");
        topicIcon.setAttribute("src", "./images/<?php echo $topic_icon_name ?>-icon.svg");
        topicIcon.setAttribute("alt", "Icon for <?php echo $topic_icon_name ?>");

        //Add attributes to the element h3 with name for topic:
        topicName = document.getElementById("topic-section-name");
        topicName.innerHTML = "<?php echo $topic_name ?>";

</script>
