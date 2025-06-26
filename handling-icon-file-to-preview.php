<!-- Block for processing the selected icon-file -->
<div class="admin-preview-content">
    <?php
    if (!isset($isAlreadyExist) || $isAlreadyExist == false) { //The situation, when there is no icon-file with this name in the server:
    ?>
        <p><strong>The name of icon-file to upload is:</strong> <?= $newFile ?></p>
        <img src="<?= "./temp-uploads/" . htmlspecialchars($temp_file_name) ?>" alt="The preview for icon-file" width="70">
    <?php
    } else { //The situation, when there is ALREADY icon-file with this name in the server:
    ?>
        <p><strong>The name of icon-file to upload is:</strong> <?= $newFile ?></p>
        <p>But the file <strong><?php echo $newFile ?></strong> already exists in the <strong>images</strong> folder for <?php echo $element_name; ?>.</p>
        <p>Which one do you want to use — the existing file or the new upload?</p>
        <ul class="images-list">
            <li class="images-list-item">
                <label for="selected_image" class="images-list-item">
                    <img src="<?= "./temp-uploads/" . htmlspecialchars($temp_file_name) ?>" alt="The preview for selected icon-file" width="70">
                    <p>new upload</p>
                    <input type="radio" name="image" id="selected_image" value="new upload" onclick="handleRadioButtonText()">
                    <?php $server_file_action = "upload-new" ?>
                </label>
            </li>

            <li class="images-list-item">
                <label for="existing_image" class="images-list-item">
                    <img src="<?= "./images/" . htmlspecialchars($newFile) ?>" alt="The preview for existing icon-file" width="70">
                    <p>existing file</p>
                    <input type="radio" name="image" id="existing_image" value="existing image" onclick="handleRadioButtonText()">
                    <?php $server_file_action = "keep-existing" ?>
                </label>
            </li>
        </ul>
        <p id="image-inform-text"></p>
    <?php
    }
    ?>
</div>