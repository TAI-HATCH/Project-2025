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

  <?php include 'header.php'?>

  <section class="root-content">
    <p class="content-text">Learn coding for free.</p>
    <p class="content-text">You decide the subject.</p>
    <p class="content-text">You decide when to move on.</p>
    <div class="icon-wrapper">
      <img src="./images/javascript-icon.svg" alt="Icon of JavaScript">
      <img src="./images/python-icon.svg" alt="Icon of Python">
    </div>
    <a href="selecting-language.php"><button type="button" class="start-button">Start</button></a>
  </section>
</body>

</html>