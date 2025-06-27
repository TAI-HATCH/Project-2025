<?php

session_start();
if (isset($_SESSION['text-message'])) {
    $text_message = $_SESSION["text-message"];
    // unset($_SESSION['text-message']);
}

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
    <section class="root-content">
        <!-- <p>There is a language in the DB with the specified name! Wrong!</p> -->
        <p class="error-info-text"><?php echo $text_message; ?></p>
        <a class="button" href="admin-start.php">To admin start page</a>
    </section>


</body>

</html>