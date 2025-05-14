<?php

session_start();

include_once "sql_query.php";

$languages = get_languages();
$_SESSION["languages"] = $languages;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select programming language</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="./css/style.css">
    <script>
        // script for printing the connection status in console:
        console.log("<?php echo $connection_status; ?>")
        console.log(`<?php if (isset($languages)) {
                            echo var_dump($languages);
                        } ?>`)
    </script>
</head>

<body>
    <br>
    <header class="root-header">
    <div class="logo">
        <a href="./">
          <svg class="logo-image" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 720 540">
          <path fill-rule="evenodd" clip-rule="evenodd" d="M0.0527344 540.001L135.053 0H584.948L719.948 540.001H0.0527344Z" fill="#C5DBE0"/></svg>
          </a>
        <div class="hatch-logo"><a href="./">HATCH</a></div>
      </div>
        <nav>
            <ul class="nav-list">
                <li class="nav-list-item">
                    <a href="quiz.php?language-topic=1" class="nav-link">Training</a>
                </li>
                <li class="nav-list-item">
                    <a href="#" class="nav-link">About</a>
                </li>
                <!--<li class="nav-list-item">
                    <a href="#" class="nav-link">Sign up</a>
                </li>
                <li class="nav-list-item">
                    <a href="#" class="nav-link">Login</a>-->
                </li>
            </ul>
        </nav>
    </header>

    <section class="root-section">
        <h2>Select programming language:</h2>
        <ul class="icon-lang-list" id="lang-list"> <!--Create an unordered list of icons, each of which corresponds to its own programming language -->
            <!--Elements will be created by script -->
        </ul>
    </section>

    <script> // Script for filling the list class="icon-lang-list" with elements:
        <?php
        if (isset($languages)) {
            foreach ($languages as $language) { // Make a loop for every element in array of languages
                $language_name = $language["language_name"];
                $icon_name = strtolower($language["language_name"]);
                $lang_id = $language["language_id"];
        ?>

        langIconItem = document.createElement("li");
        langIconItem.classList.add("icon-lang-item");
        langIconLink = document.createElement("a");
        langIconLink.classList.add("icon-lang-link");
        langIconLink.setAttribute("title", "<?php echo $language_name?>");
        langIconLink.setAttribute("href", "selecting_topic.php?language_id=<?php echo $lang_id?>");
        langIconImg = document.createElement("img");
        langIconImg.classList.add("language-icon");
        langIconImg.setAttribute("src", "./images/<?php echo $icon_name?>-icon.svg");
        langIconImg.setAttribute("alt", "Icon for <?php echo $language_name?>");
        langIconImg.setAttribute("height", "100");
        langIconLink.appendChild(langIconImg);
        langIconItem.appendChild(langIconLink);
        document.getElementById("lang-list").appendChild(langIconItem);
        <?php        
            }
        }
        ?>
    </script>

</body>

</html>
