<?php

session_start(); //Open a php session on the server, is never shut down
unset($_SESSION["correct_answers"]);

// If we need to check which data is now in the Session:
//Using of this code make it as a part of html content:
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
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="./css/style.css">
    <script>
        // script for printing the connection status in console:
        console.log("<?php echo $connection_status; ?>")

        // Print the array with current question:
        // <php if (isset($question)) { ?>
        //     console.log(`Current question is: <php echo json_encode($question); ?>`);
        // <php } ?>

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
        //Script for adjusting the width of input-fields based on the length of the corresponding answer string
        <?php
        if (isset($question)) { //If there is current question - call the function to get the length of answers value:
            $answers_width = get_answer_value_length($question["question_id"]);
            // Output the width array in console:
            if (isset($answers_width)) { ?>
                console.log(`Width array is: <?php echo json_encode($answers_width); ?>`);
        <?php
            }
        }
        ?>
        //Collect all input elements in array:
        const arrayOfInputs = document.querySelectorAll("input");
        //For each input element on the page:
        arrayOfInputs.forEach(inputElement => {
            //Processing the click on the input field:
            inputElement.addEventListener("click", hidePlaceholder);
            //Store the name of input field to find and compare it later in the DB:
            let inputName = inputElement.name;
            <?php
            if (isset($answers_width)) {
                //For each correct answer from the DB:
                //$input_name is the name of input field from the DB:
                foreach ($answers_width as $input_name => $width) {
                    //Find the max string in the array of answer's values:
                    $width_value = max(array_values($width));
            ?>
                    // console.log(`The width of ${inputElement} ${inputName} is ${<php echo ($width_value); ?>}`);

                    //If the input name from DB is equal to input name on the page:
                    if (inputName.localeCompare(`<?php echo $input_name ?>`) == 0) {
                        // Set the width of the future input field as the length of the string multiplied by 0.6:
                        let widthOfInputField = Number.parseInt("<?php echo $width_value; ?>") * 0.7;
                        // console.log("The calculated width is", widthOfInputField);
                        //Set the width of input field in rem:
                        inputElement.style.maxWidth = `${widthOfInputField}rem`;
                    }
            <?php
                }
            }
            ?>
        });

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

            //Store the pair [input_name, input_value] of user's answers in variable usersAnswers:
            const usersInputs = document.querySelectorAll("input"); //Collect all input fields from the page
            const usersAnswers = []; // Create empty array for storing there the pairs of user's answers
            usersInputs.forEach(usersInput => {
                //Remove classes .wrong and .correct from all the inputs:
                usersInput.classList.remove("wrong");
                usersInput.classList.remove("correct");
                usersInput.removeAttribute("placeholder");
                // usersInput.value = "";

                usersAnswers.push([usersInput.name, usersInput.value]); // Pair of user's answer is: the name of input field and its value
            });
            console.log(usersAnswers);

            <?php
            $question = $_SESSION["questions"][$_SESSION["current_question"]];
            $answers = get_answers($question["question_id"]); //Call the function get_answers from sql_query.php
            //Array of correct answers from DB will store as a collection of arrays, where the first element is the name of input field, 
            // and the second element is an array with correct values - can be several correct values for one input field:
            $correct_answers = array();
            foreach ($answers as $answer) {
                $inputName = $answer["input_name"];
                $answerValue = $answer["answer_value"];
                if (!isset($correct_answers[$inputName])) {
                    $correct_answers[$inputName] = array(); //Create the first element in the collection - the name of input field
                }
                $correct_answers[$inputName][] = $answerValue; //Add the value - correct answer
            }

            $_SESSION["correct_answers"] = $correct_answers;
            ?>

            //Output the array with correct answers in console - just for info:
            // let dataToOutput = <php echo json_encode($correct_answers); ?>;
            // console.log("Array correct answers:", dataToOutput);

            <?php

            if (isset($_SESSION["correct_answers"]))
                // Loop in PHP to get the value of the input field and its possible values:
                foreach ($correct_answers as $inputAnswerName => $correct_answer) {
                    // $inputAnswerName = $correct_answer[];
                    $inputAnswerValue = array_values($correct_answer);
            ?>
                //Loop in JS to get the name of input field and user`s value for it:
                usersAnswers.forEach(userAnswer => {
                    //We use variable isCorrect to decide whether to make input border green or red
                    // From the begining isCorrect is False:
                    let isCorrect = false;
                    // console.log("IsCorrect value is", isCorrect);
                    let userAnswerName = userAnswer[0]; //Get the name of input field
                    // console.log("The userAnswerName is", userAnswerName);
                    let userAnswerValue = userAnswer[1]; //Get the value of the input field
                    // console.log("The userAnswerValue is", userAnswerValue);
                    //Check if the user's input field name and correct_answer input name matches:
                    if (userAnswerName.localeCompare(`<?php echo $inputAnswerName; ?>`) == 0) { // 0 means YES
                        //Check if the value of user's answer and value of correct answer matches:

                        // $inputAnswerValue is an array and json_encode() convert PHP data into JavaScript-compatible JSON:

                        // function .includes() in JS check if the variable inside the brackets is present in the array:
                        if (<?php echo json_encode($inputAnswerValue); ?>.includes(userAnswerValue)) {
                            isCorrect = true;
                            // console.log("Yes, user inputs right value");
                        }
                        if (isCorrect == true) {
                            // console.log("You are right!", isCorrect); //Just for info
                            // document.getElementsByName(userAnswerName)[0].style.border = "2px solid green";
                            // document.getElementsByName(userAnswerName)[0].style.boxShadow = "0 0 1rem #0001004d";
                            document.getElementsByName(userAnswerName)[0].classList.add("correct");
                        } else {
                            // console.log("You are wrong", isCorrect); //Just for info
                            // document.getElementsByName(userAnswerName)[0].style.border = "2px solid red";
                            document.getElementsByName(userAnswerName)[0].classList.add("wrong");
                        }
                    }
                });
            <?php
                }
            ?>
        })

        //Function to hide placeholder:
        function hidePlaceholder(e) {
            this.removeAttribute("placeholder");
            this.classList.remove("wrong");
            this.classList.remove("correct");
        }
    </script>
</body>

</html>