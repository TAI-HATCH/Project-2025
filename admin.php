
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
      rel="stylesheet"
    />
    <link rel="stylesheet" href="./css/style.css">
  </head>
  <body>
    <h1 hidden>HATCH</h1>
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
            <a href="selecting_language.php" class="nav-link">Training</a>
            <!-- <a href="quiz.php?language-topic=1" class="nav-link">Training</a> -->
          </li>
          <li class="nav-list-item">
            <a href="#" class="nav-link">About</a>
          </li>
          <!--<li class="nav-list-item">
            <a href="#" class="nav-link">Sign up</a> Not for MVP -->
          </li>
          <!-- <li class="nav-list-item">
            <a href="#" class="nav-link">Login</a> Not for MVP -->
          </li>
        </ul>
      </nav>
    </header>

    <section class="root-content">
    <div class="admin-question-upload">
        
        <form action="">
            <div class="admin-choice">
                <label for="admin-programming-language-choice">Choose programming language</label>
                <select class="admin-dropdown-menu" name="admin-programming-language-dropdown-menu" id="admin-programming-language-dropdown-menu">
                       <?php
                        include_once "connection.php";

                        try {
                            $stmt = $conn->query("SELECT 
                                                    language_id, language_name 
                                                FROM 
                                                    languages 
                                                ORDER BY 
                                                    language_name 
                                                ASC");

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // As long as there is a language in the database, fetch it
                                $id = htmlspecialchars($row['language_id']);
                                $name = htmlspecialchars($row['language_name']);
                                echo "<option value=\"$id\">$name</option>"; // Add them to the dropdown menu as an option
                            }
                        } catch (PDOException $e) {
                            echo "<option disabled>Error loading languages</option>";
                        }
                        ?>
                </select>
            </div>
            <div class="admin-choice">
                <label for="topic-choice">Choose topic</label>
                <select class="admin-dropdown-menu" name="topic-dropdown-menu" id="topic-dropdown-menu">
                  <?php
                        include_once "connection.php";

                        try {
                            $stmt = $conn->query("SELECT 
                                                    topic_id, topic_name 
                                                FROM 
                                                    topics
                                                ORDER BY 
                                                    topic_name 
                                                ASC");

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // As long as there is a topic in the database, fetch it
                                $id = htmlspecialchars($row['topic_id']);
                                $name = htmlspecialchars($row['topic_name']);
                                echo "<option value=\"$id\">$name</option>"; // Add them to the dropdown menu as an option
                            }
                        } catch (PDOException $e) {
                            echo "<option disabled>Error loading languages</option>";
                        }
                        ?>


                    </select>
            </div>
        <div class="admin-input">
            <label for="question-text">Question text</label>
            <input class="admin-input-field" type="text" id="question-text-input" name="question-text-input" placeholder="Type your question text here.">
        </div>
            <div class="admin-question-content">
                <label for="question-content">Question content</label>
                <div class="admin-input-content">
                    <label for="text-before-user-input">Text before user input field:</label>
                    <input class="admin-input-field" type="text" id="text-before-input-field" name="text-before-input-field" placeholder="Type the question text before the user input field.">
                </div>
                <div class="admin-input-content"> 
                    <label for="text-after-user-input">Text after user input field:</label>
                    <input class="admin-input-field" type="text" id="text-after-input-field" name="text-after-input-field" placeholder="Type the question text after the user input field.">
                </div>
            </div>
        </form> 
        <a href="upload-to-database"><button type="button" class="upload-to-database-button">Upload to database</button></a>
      </div>
    </section>
  </body>
</html>
