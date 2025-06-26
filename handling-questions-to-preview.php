<?php
if (!empty($all_existing_questions)) {
    // echo "<pre>";
    // var_dump($all_existing_questions);
    // echo "</pre>";
?>
    <div class="admin-preview-content">
        <p><strong>Below is a list of all questions currently stored in the database for the <?php echo $element_name ?>.</strong></p>
        <p>Questions without checkmarks were previously deactivated.</p>
        <p>You can modify this selection before saving — check or uncheck the questions as needed:</p>
        <ul class="question-list">
            
            <?php
            foreach ($all_existing_questions as $question) {
                $all_answers = get_all_answers($question['question_id']);
                // echo "<pre>";
                // var_dump($question);
                // echo "</pre>";
            ?>
                <li class="question-list-item checkbox-group">

                    <div class="question-list-item-checkbox">
                        <input type="checkbox" name="question[]" id="question<?php echo $question['question_id']; ?>" value="<?php echo $question['question_id']; ?>"
                            class="checkbox checkbox-parent" onchange="handleCheckboxUncheck(this)"
                            <?php if ($question['is_active'] == 1) { ?> checked <?php } ?>>
                    </div>

                    <div class="question-list-item-wrapper">
                        <div class="question-list-item-text">
                            <p><strong>Question text:</strong></p>
                            <label for="question<?php echo $question['question_id']; ?>"><?php echo $question['question']; ?></label>
                        </div>

                        <div class="question-list-item-snippet">
                            <p><strong>Question code snippet:</strong></p>
                            <p class="question-list-item-snippet-paragragh"><?php echo $question['form_content']; ?></p>
                        </div>

                        <div class="question-list-item-answer">
                            <p><strong>Answers:</strong></p>
                            <ul class="answer-list">
                                <?php
                                foreach ($all_answers as $answer) {
                                ?>
                                    <li class="answer-list-item">
                                        <input type="checkbox" name="answer[]" id="answer<?php echo $answer['id']; ?>" value="<?php echo $answer['id']; ?>"
                                            class="checkbox checkbox-child"
                                            <?php if ($answer['is_active'] == 1) { ?> checked <?php } ?>>
                                        <label for="answer<?php echo $answer['id']; ?>">
                                            <?php echo $answer['input_name']; ?>
                                            :
                                            <?php echo $answer['answer_value']; ?>
                                        </label>
                                    </li>
                                <?php
                                }
                                ?>

                            </ul>

                        </div>
                    </div>
                </li>
            <?php
            }
            ?>
        </ul>

    </div>
<?php
}
?>