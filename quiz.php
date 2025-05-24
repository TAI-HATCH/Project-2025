<?php

session_start(); //Open a php session on the server, is never shut down
unset($_SESSION["correct_answers"]);

// If we need to check which data is now in the Session:
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

// include "connection.php";
include_once "sql_query.php";
//http://localhost/project-2025/quiz.php?language-topic=1

if (isset($_GET['language-topic'])) {
    //https://www.w3schools.com/php/php_superglobals_get.asp
    $topic_id = $_GET['language-topic']; // Read the URL-get-parameter named language-topic

    $questions = get_questions($topic_id); //Call the function get_questions from sql_query.php

    if (empty($questions)) { // Checks if there are no questions in the array and throws an error page
        include "error-no-questions-in-topic.php";
        exit();
    }

    //https://www.w3schools.com/php/php_sessions.asp
    // Set session variables
    $_SESSION["topic_id"] = $topic_id;
    $_SESSION["current_question"] = 0;
    $_SESSION["questions"] = $questions;
} else if (isset($_GET['action'])) { // If URL-get-parameter with name "action" is set, go to the next question

    // echo $_SESSION["topic_id"]; Print the id of selected topic on the page

    $action = $_GET['action'];
    if ($action == "next") {
        $_SESSION["current_question"] += 1;
        if ($_SESSION["current_question"] == count($_SESSION["questions"])) { // "When you get to the last question, hop on to the beginning of the question array."
            $_SESSION["current_question"] = 0;
        }
    } else if ($action == "previous") {
        $_SESSION["current_question"] -= 1;
        if ($_SESSION["current_question"] < 0) { // "When you get to the first question, hop on to the end of the question array."
            $_SESSION["current_question"] = count($_SESSION["questions"]) - 1;
        }
    } else if ($action == "show") { // If user selected button Show answer

        $question = $_SESSION["questions"][$_SESSION["current_question"]]; // Identify the current question from the "questions"-array

        $answers = get_answers($question["question_id"]); //Call the function get_answer from sql_query.php
        // echo var_dump($answers);
    } //else if ($action == "check") {

    //     $question = $_SESSION["questions"][$_SESSION["current_question"]];
    //     $answers = get_answers($question["question_id"]); //Call the function get_answer from sql_query.php
    //     $correct_answers = array();
    //     foreach ($answers as $answer) {
    //         array_push($correct_answers, $answer["answer_value"]);
    //     }
    //     $_SESSION["correct_answers"] = $correct_answers;
    // }

    //else if ($action == "clear") { // If user selected button Clear session
    // remove all session variables
    // session_unset();
    //}
}

$question = $_SESSION["questions"][$_SESSION["current_question"]]; // Identify the current question from the "questions"-array
?>

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
    <script>
        // script for printing the connection status in console:
        console.log("<?php echo $connection_status; ?>")

        // Print the array with answers:
        <?php if (isset($answers)) { ?>
            console.log(`Array with answers: <?php echo json_encode($answers); ?>`);
        <?php } ?>

        // Print the array with correct answer:
        <?php if (isset($correct_answers)) {
        ?>
            console.log(`Array with correct answers: <?php echo json_encode($correct_answers); ?>`);
        <?php
        } ?>
    </script>
</head>

<body>

    <!-- Insert the header from the file header.php -->
    <?php include 'header.php' ?>

    <!-- Insert the breadcrumb from the file breadcrumb.php -->
    <?php include 'breadcrumb.php' ?>

    <p class="quiz"><?php echo $question["question"]; ?></p>

    <form action="" method="POST" class="code-snippet quiz">
        <?php echo $question["form_content"]; ?>
    </form>

    <div class="buttons">
        <a class="button" href="?action=show">Show answer</a>
        <a class="button" href="#" id="check">Check answer</a> <!--Checks if answer is correct or wrong-->
        <a class="button" href="?action=previous">Back</a> <!--Link to the previous question-->
        <a class="button" href="?action=next">Next</a> <!--Link to the next question-->
        <!-- <a class="button" href="?action=clear">Clear session</a> Clear the session`s variables-->
    </div>

    <script>
        // Script for adding placeholder to the input field after selecting option Show answer:
        <?php
        if (isset($answers)) {
            foreach ($answers as $answer) {
        ?>
                document.getElementsByName("<?php echo $answer["input_name"]; ?>")[0].setAttribute("placeholder", `<?php echo $answer["answer_value"]; ?>`)
        <?php
            }
        }
        ?>

        // Output the selected languages_topic_id in console:
        <?php
        if (isset($topic_id)) { ?>
            console.log("Selected topic is <?php echo $topic_id; ?>");
        <?php
        }
        ?>

        // Processing the Check answer button:
        document.getElementById("check").addEventListener("click", function(event) {
            // event.preventDefault(); //Prevent the reloading of the page when user clicks on Check answer button

            //Store the pair [input_name, input_value] of user's answers in variable users_answers:
            const users_inputs = document.querySelectorAll("input"); //Collect all input fields from the page
            const users_answers = []; // Create empty array for storing there the pairs of user's answers
            users_inputs.forEach(users_input => {
                users_answers.push([users_input.name, users_input.value]);
            });
            console.log(users_answers);

            <?php
            $question = $_SESSION["questions"][$_SESSION["current_question"]];
            $answers = get_answers($question["question_id"]); //Call the function get_answers from sql_query.php
            $correct_answers = array();
            foreach ($answers as $answer) {
                $inputName = $answer["input_name"];
                $answerValue = $answer["answer_value"];
                if (!isset($correct_answers[$inputName])) {
                    $correct_answers[$inputName] = array();
                }
                $correct_answers[$inputName][] = $answerValue;
            }

            $_SESSION["correct_answers"] = $correct_answers;

            ?>
            //Output the array with correct answers in consol:
            let dataToOutput = <?php echo json_encode($correct_answers); ?>;
            console.log("Array correct answers:", dataToOutput);


            //We use variable checkingResult to compare user's answer with correct answer/answers:
            // let checkingResult = null;

            <?php

            if (isset($_SESSION["correct_answers"])) {
                // echo json_encode($_SESSION["correct_answers"]);
                foreach ($correct_answers as $inputAnswerName => $correct_answer) {
                    // $inputAnswerName = $correct_answer[];
                    $inputAnswerValue = array_values($correct_answer);
            ?>
                    // let inputAnswerName = <?php echo json_encode($inputAnswerName); ?>;
                    // console.log("inputAnswerName is:", inputAnswerName);
                    // let inputAnswerValue = <?php echo json_encode($inputAnswerValue); ?>;
                    // console.log("inputAnswerValue is:", inputAnswerValue);

                    users_answers.forEach(element => {
                        //We use variable isCorrect to decide whether to make input border green or red
                        // From the begining isCorrect is False:
                        let isCorrect = false;
                        userAnswerName = element[0];
                        console.log("The userAnswerName is", userAnswerName);
                        userAnswerValue = element[1];
                        console.log("The userAnswerValue is", userAnswerValue);
                        //Check if the user's input field name and correct_answer input name matches:
                        if (userAnswerName.localeCompare(`<?php echo $inputAnswerName; ?>`) == 0) { // 0 means YES
                            //Check if the value of user's answer and value of correct answer matches:
                            if (`<?php echo json_encode($inputAnswerValue) ?>`.includes(userAnswerValue)) {
                                isCorrect = true;
                                // bsreak;
                            }
                            if (isCorrect == true) {
                                console.log("You are right!");
                                document.getElementsByName(userAnswerName)[0].style.borderColor = "green";
                            } else {
                                console.log("You are wrong");
                                document.getElementsByName(userAnswerName)[0].style.borderColor = "red";
                            }
                        }
                    });
                    

                <?php
                } ?>



            <?php
            }
            ?>
        })
    </script>
</body>

</html>