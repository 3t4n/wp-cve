<div id="ke-form-post">
    <input type="hidden" id="ke-metabox-edit-url"
           data-url="<?php echo esc_attr(KIRIMEMAIL_APP_URL . 'forms/edit/'); ?>%id%?embed=1&TB_iframe=true&width=%width%&height=%height%">
    <div class="form-group">
        <label for="ke-widget" class="label ke-metabox-label">Widget Form</label>
        <input type="hidden" id="widget-selected" value=<?php echo sanitize_text_field($widget_selected); ?>>
        <select name="ke-widget" class="ke-metabox-select-form" id="ke-widget">
            <?php if (isset($widget_selected)) { ?>
                <option
                    value=<?php echo sanitize_text_field($widget_selected); ?> selected><?php echo sanitize_text_field($widget_selected_name); ?></option>
            <?php } ?>
        </select>
        <a id="ke-widget-edit" class="button">Edit</a>
    </div>
    <div class="form-group">
        <label for="ke-bar" class="label ke-metabox-label">Bar Form</label>
        <input type="hidden" id="bar-selected" value=<?php echo sanitize_text_field($bar_selected); ?>>
        <select name="ke-bar" class="ke-metabox-select-form" id="ke-bar">
            <?php if (isset($bar_selected)) { ?>
                <option
                    value=<?php echo sanitize_text_field($bar_selected); ?> selected><?php echo sanitize_text_field($bar_selected_name); ?></option>
            <?php } ?>
        </select>
        <a id="ke-bar-edit" class="button">Edit</a>
    </div>
    <div><span id="ke-metabox-saved" class="success">Saved</span><span id="ke-metabox-error" class="errors">Error</span>
    </div>
    <div class="form-group">
        <a class="button" id="ke-save-metabox" data-id="<?php echo get_the_ID(); ?>">Save</a>
    </div>
</div>
