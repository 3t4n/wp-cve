<?php declare(strict_types=1);

/**
 * Provide a public-facing view for the plugin.
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @see  https://mailup.it
 * @since 1.2.6
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="mpwp-container">
    <div id="mupwp-form-content">
        <form id="mupwp-form" class="mupwp-form" novalidate="novalidate" action="javascript:void(0);">
            <h3 id="mupwp-form-title"><?php esc_attr_e($form->title); ?>
            </h3>
            <?php if ($form->description) { ?>
            <div id="mupwp-form-description">
                <?php _e($form->description); ?>
            </div>
            <?php } ?>
            <div id="mupwp-form-fields" class="form-fields">
                <?php foreach ($form->fields as $field) { ?>
                <div class="mupwp-form-field">
                    <label <?php if ($form->placeholder) {
                        echo 'style="display:none;"';
                    }?> for="<?php echo $field->id; ?>"><?php esc_attr_e($field->label); ?>
                    </label>
                    <input type="<?php echo $field->type; ?>" id="<?php echo $field->id; ?>" <?php if ($field->required) {
                        echo 'required';
                    } ?> class="label-field" <?php if ($form->placeholder) {
                        echo 'placeholder="'.$field->label.'"';
                    }?> name="<?php _e($field->id); ?>">

                </div>

                <?php } ?>
            </div>
            <div id="mupwp-form-terms" class="mupwp-form-terms">
                <?php foreach ($form->terms as $term) {
                    if ($term->show) { ?>
                <div class="mupwp-form-term">
                    <label class="label terms small-font" for="<?php echo sprintf('term-%s', $term->id); ?>">
                        <input type="checkbox" id="<?php echo sprintf('term-%s', $term->id); ?>"
                            name="<?php echo sprintf('term-%s', $term->id); ?>" <?php if ($term->required) {
                                echo 'required';
                            } ?>>
                        <?php _e($term->text); ?>
                    </label>
                </div>
                    <?php }
                    } ?>
            </div>
            <div class="separator-20px-top"></div>
            <div id="mupwp-form-submit-container">
                <input type="submit" id="mupwp-form-save" value="<?php esc_attr_e($form->submit_text); ?>"
                    class="button-primary">
                <span class="ajax-loader"></span>
                <span class="feedback"></span>
            </div>
        </form>
    </div>
</div>