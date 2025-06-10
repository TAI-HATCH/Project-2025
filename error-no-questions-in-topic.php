<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HATCH - Quiz</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap"
        rel="stylesheet" />


    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php include 'header.php' ?>
    <?php include 'breadcrumb.php' ?>

    <p class="quiz">Sorry, there are no questions available for the <?= $selected_topic_info[0]["language_name"] ?> topic "<?php echo $topic_name; ?>" – yet!</p>
    <a href="selecting-topic.php?language-id=<?php echo $lang_id; ?>">
        <button type="button" class="return-button">Return to selection</button>
    </a>
</body>