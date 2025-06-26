<?php
                    $language_name = $_POST['add-language'] ?? '';
                    $selected_topics = $_POST['topic'] ?? [];
            ?>

                    <h1>Preview add programming language</h1>

                    <!-- Block for processing the selected topics -->
                    <div class="admin-preview-content">
                        <p><strong>Language name:</strong> <?= $language_name ?></p>

                        <?php
                        if (isset($text_message)) { //It means that admin typed the language's name, that already exists in the DB and it is unactive (is_active = 0)
                        ?>
                            <p><strong><?php echo $text_message ?></strong></p>

                            <?php
                            if (!empty($all_existing_topics_for_language) && !empty($selected_topics)) {
                            ?>
                                <p><strong>Below is a list of all topics currently stored in the database for the <?php echo $element_name ?>.</strong></p>
                                <p>Topics that are already active or were selected on the previous page are marked with checkmarks.</p>
                                <p>Topics without checkmarks were previously deactivated.</p>
                                <p>You can modify this selection before saving — check or uncheck the topics as needed:</p>
                        <?php
                                $all_topics = get_all_topics();
                                $temp_topics_array = []; // the array to store all necessary information about selected topics (name, id and is_active)
                                foreach ($all_topics as $topic) {
                                    if (in_array($topic['topic_id'], $selected_topics)) {
                                        $topic_item = ['topic_name' => $topic['topic_name'], 'is_active' => $topic['is_active'], 'topic_id' => $topic['topic_id']];
                                        array_push($temp_topics_array, $topic_item);
                                    }
                                }

                                $merged_topic_array = []; // array to merge all already existing topics and selected topics
                                $all_ids = [];
                                foreach ([$all_existing_topics_for_language, $temp_topics_array] as $topic_array) {
                                    foreach ($topic_array as $topic) {
                                        if (!in_array($topic['topic_id'], $all_ids)) {
                                            $merged_topic_array[] = $topic;
                                            $all_ids[] = $topic['topic_id'];
                                        }
                                    }
                                }

                                // echo "<pre>";
                                // echo "Merged array:";
                                // var_dump($merged_topic_array);
                                // echo "</pre>";
                            } else {
                                echo "<pre>";
                                var_dump("No topics");
                                echo "</pre>";
                            }
                        } else {
                            echo "<pre>";
                            var_dump("No text message");
                            echo "</pre>";
                        }
                        ?>

                        <?php
                        if (!empty($merged_topic_array)) { // If there is at least one item (topic) in the merged topics array, then we output this block:
                        ?>
                            <ul>
                                <?php
                                foreach ($merged_topic_array as $topic) {
                                    $topic_id = $topic['topic_id'];
                                ?>
                                    <li>
                                        <input type="checkbox" name="topic[]" id="topic<?php echo $topic_id; ?>" value="<?php echo $topic_id; ?>" class="checkbox"
                                            <?php if ($topic['is_active'] == 1) { ?> checked <?php } ?>>
                                        <label for="topic<?php echo $topic_id; ?>"><?php echo $topic['topic_name']; ?></label>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                            <?php

                        } else {
                            if (!empty($selected_topics)) {
                                $topics = get_all_topics();
                                $topic_names = [];
                                foreach ($topics as $topic) {
                                    if (in_array($topic['topic_id'], $selected_topics)) {
                                        $topic_names[] = $topic['topic_name'];
                                    }
                                }
                            ?>
                                <p><strong>Selected Topics:</strong> <?= join(", ", $topic_names) ?></p>
                            <?php } else { ?>
                                <p>No topics selected.</p>
                        <?php }
                        }
                        ?>
                    </div>

                    <!-- Block for processing the selected icon-file -->
                    <?php include 'handling-icon-file-to-preview.php'; ?>

                    <!-- Section to preview language's questions. Validation: is array with questions empty or not. If it is empty: do not show this section-->
                    <?php include 'handling-questions-to-preview.php'; ?>


                    <div class="admin-form-buttons">
                        <form method="POST" action="upload-to-database.php">
                            <input type="hidden" name="form_type" value="add-language">
                            <input type="hidden" name="add-language" value="<?= $language_name; ?>">
                            <input type="hidden" name="temp-icon-file" value="<?= htmlspecialchars($temp_file_name); ?>">
                            <input type="hidden" name="new-icon-file-name" value="<?= $newFile; ?>">
                            <input type="hidden" name="sql-action" value="<?php echo $sql_action; ?>">
                            <input type="hidden" name="server-file-action">

                            <!-- Validation: is there merged topics-array or not? -->
                            <?php
                            if (!empty($merged_topic_array)) {
                            ?>
                                <div id="form-input-hidden-topics">
                                    <!-- The content will be generated using a JS script -->
                                </div>
                                <?php
                            } else {
                                foreach ($selected_topics as $topic_id): ?>
                                    <input type="hidden" name="topic[]" value="<?= $topic_id ?>">
                            <?php endforeach;
                            } ?>

                            <!-- Validation: is there questions-array or not? -->
                            <?php
                            if (!empty($all_existing_questions)) {
                            ?>
                                <div id="form-input-hidden-questions">
                                    <!-- The content will be generated using a JS script -->
                                </div>
                                <div id="form-input-hidden-answers">
                                    <!-- The content will be generated using a JS script -->
                                </div>

                            <?php
                            }
                            ?>

                            <button class="upload-to-database-button" type="submit">Upload to database</button>
                        </form>

                        <form method="GET" action="admin-add-language.php">
                            <button class="upload-to-database-button" type="submit">Cancel</button>
                        </form>
                    </div>

                <?php
?>