<?php

session_start();
if (isset($_SESSION['text-message'])) {
    $text_message = $_SESSION["text-message"];
    unset($_SESSION['text-message']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>There is a language in the DB with the specified name! Wrong!</p>
    <p><?php echo $text_message; ?></p>
</body>
</html>