<form id="mailup-form" class="mailup-form" name="mailup-form" method="get" novalidate="novalidate" action>
    <?php
    if ($api_list) { ?>
    <fieldset>
        <h2><?php esc_attr_e('List:', 'mailup'); ?>
        </h2>
        <span class="info"><?php _e('Select the list you want to use.', 'mailup'); ?></span>
        <select name='lists' id='lists' class="long_select">
                <?php foreach ($lists['api-lists'] as $list) { ?>
            <option data-desc="<?php esc_attr_e($list['description'], 'mailup'); ?>" <?php if (null !== $form_mup && intval($form_mup->list_id) === $list['id']) {
                echo 'selected';
            } ?> value="<?php echo $list['id']; ?>">
                    <?php echo $list['name']; ?>
            </option>
                <?php } ?>
        </select>
    </fieldset>
    <div class="separator-with-border"></div>
    <h2><?php esc_attr_e('Group:', 'mailup'); ?>
    </h2>
    <span
        class="info"><?php _e('Users will always be part of this group. They will also be placed in specific groups based on privacy choices.', 'mailup'); ?></span>
    <input name="sel_group" id="sel-group" type="text" class="long_input" value="<?php echo $form_mup->group; ?>"
        required maxlength="45">
    <div class="separator-with-border"></div>
    <?php } ?>

    <h2><?php esc_attr_e('Form Title:', 'mailup'); ?>
    </h2>
    <span
        class="info"><?php _e('Used to give your form title. Leave blank if you think is not necessary.', 'mailup'); ?></span>
    <input name="title_form" id="title-form" type="text" class="long_input"
        value="<?php echo trim(__($form_mup->title ?? '', 'mailup')); ?>">
    <div class="separator-with-border"></div>

    <h2><?php esc_attr_e('Form Description:', 'mailup'); ?>
    </h2>
    <span
        class="info"><?php _e('Used to give your form a description. Leave blank if you think is not necessary.', 'mailup'); ?></span>
    <?php wp_editor($form_mup->description ?? '', 'form-description', $settings = ['textarea_name' => 'form-description', 'media_buttons' => 0, 'wpautop' => 0]); ?>
    <div class="separator-with-border"></div>

    <h2><?php esc_attr_e('Submit button text:', 'mailup'); ?>
    </h2>
    <span
        class="info"><?php _e('Used to pesonalize the submit button text (e.g. Send, Subscribe, Sign-in, Sign-up...)', 'mailup'); ?></span>
    <input name="submit-text" id="submit-text" type="text"
        value="<?php isset($form_mup->submit_text) ? _e($form_mup->submit_text) : _e('Send', 'mailup'); ?>" required>
    <div class="separator-with-border"></div>
    <input type="submit" id="form-save-general-settings" name="save" value="<?php _e('Save'); ?>"
        class="button button-primary">
    <span class="spinner"></span>
    <span class="feedback"></span>
</form>