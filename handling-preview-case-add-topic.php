<?php
$topic_name = $_POST['add-topic'] ?? '';
$selected_languages = $_POST['language'] ?? []; ?>

<h1>Preview add topic</h1>

<!-- Block for processing the selected languages -->
<div class="admin-preview-content">
    <p><strong>Topic name: </strong><?= $topic_name ?></p>

    <?php
    if (isset($text_message)) { //It means that admin typed the topic's name, that already exists in the DB and it is unactive (is_active = 0)
    ?>
        <p><strong><?php echo $text_message ?></strong></p>

        <?php
        if (!empty($all_existing_languages_for_topic) && !empty($selected_languages)) {
        ?>
            <p><strong>Below is a list of all languages currently stored in the database for the <?php echo $element_name ?>.</strong></p>
            <p>Languages that are already active or were selected on the previous page are marked with checkmarks.</p>
            <p>Languages without checkmarks were previously deactivated.</p>
            <p>You can modify this selection before saving — check or uncheck the Languages as needed:</p>
    <?php
            $all_languages = get_languages();
            $temp_languages_array = []; // the array to store all necessary information about selected languages (name, id and is_active)
            foreach ($all_languages as $language) {
                if (in_array($language['language_id'], $selected_languages)) {
                    $language_item = ['language_name' => $language['language_name'], 'is_active' => $language['is_active'], 'language_id' => $language['language_id']];
                    array_push($temp_languages_array, $language_item);
                }
            }

            $merged_language_array = []; // array to merge all already existing languages and selected languages
            $all_ids = [];
            foreach ([$all_existing_languages_for_topic, $temp_languages_array] as $language_array) {
                foreach ($language_array as $language) {
                    if (!in_array($language['language_id'], $all_ids)) {
                        $merged_language_array[] = $language;
                        $all_ids[] = $language['language_id'];
                    }
                }
            }

            // echo "<pre>";
            // echo "Merged array:";
            // var_dump($merged_language_array);
            // echo "</pre>";
        } else {
            echo "<pre>";
            var_dump("No languages");
            echo "</pre>";
        }
    } else {
        echo "<pre>";
        var_dump("No text message");
        echo "</pre>";
    }
    ?>

    <?php
    if (!empty($merged_language_array)) { // If there is at least one item (language) in the merged languages array, then we output this block:
    ?>
        <ul>
            <?php
            foreach ($merged_language_array as $language) {
                $language_id = $language['language_id'];
                // echo "<pre>";
                // echo "Language:";
                // var_dump($language['language_name']);
                // echo "</pre>";
            ?>
                <li>
                    <input type="checkbox" name="language[]" id="language<?php echo $language_id; ?>" value="<?php echo $language_id; ?>" class="checkbox"
                        <?php if ($language['is_active'] == 1) { ?> checked <?php } ?>>
                    <label for="language<?php echo $language_id; ?>"><?php echo $language['language_name']; ?></label>
                </li>
            <?php
            }
            ?>
        </ul>
        <?php

    } else {
        if (!empty($selected_languages)) {
            $languages = get_languages();
            $language_names = [];
            foreach ($languages as $language) {
                if (in_array($language['language_id'], $selected_languages)) {
                    $language_names[] = $language['language_name'];
                }
            }
        ?>

            <p><strong>Selected languages: </strong> <?= implode(", ", $language_names) ?></p>
        <?php } else { ?>
            <p><strong>No languages selected.</strong></p>
    <?php }
    }
    ?>
</div>

<!-- Block for processing the selected icon-file -->
<?php include 'handling-icon-file-to-preview.php'; ?>

<!-- Section to preview topic's questions. Validation: is array with questions empty or not. If it is empty: do not show this section-->
<?php include 'handling-questions-to-preview.php'; ?>


<div class="admin-form-buttons">
    <form method="POST" action="upload-to-database.php">
        <input type="hidden" name="form_type" value="add-topic">
        <input type="hidden" name="add-topic" value="<?= $topic_name ?>">
        <input type="hidden" name="temp-icon-file" value="<?= htmlspecialchars($temp_file_name) ?>">
        <input type="hidden" name="new-icon-file-name" value="<?= $newFile ?>">
        <input type="hidden" name="sql-action" value="<?php echo $sql_action; ?>">
        <input type="hidden" name="server-file-action">

        <!-- Validation: is there merged topics-array or not? -->
        <?php
        if (!empty($merged_language_array)) {
        ?>
            <div id="form-input-hidden-languages">
                <!-- The content will be generated using a JS script -->
            </div>
            <?php
        } else {
            foreach ($selected_languages as $language_id): ?>
                <input type="hidden" name="language[]" value="<?= $language_id ?>">
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
        } ?>

        <button class="upload-to-database-button" type="submit">Upload to database</button>
    </form>

    <form method="GET" action="admin-add-topic.php">
        <button class="upload-to-database-button" type="submit">Cancel</button>
    </form>
</div>
<?php

?>