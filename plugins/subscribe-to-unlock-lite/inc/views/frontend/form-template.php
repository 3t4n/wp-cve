<div class="stul-main-outer-wrap <?php echo (!isset($atts['page_lock'])) ? 'stul-content-locked' : ''; ?> <?php echo esc_attr($lock_mode_class); ?>">
    <div class="stul-blur-overlay">
        <div class="stul-form-wrap stul-<?php echo esc_attr($form_template); ?> stul-free-version">
            <form method="post" action="" class="stul-subscription-form" data-form-alias="stul-free-version">
                <?php
                /**
                 * Triggers just before displaying the subscription form
                 *
                 * @param object $form_row
                 *
                 * @since 1.0.0
                 */
                do_action('stul_before_form', $form_details);

                if (file_exists(STUL_PATH . 'inc/views/frontend/form-templates/' . $form_template . '.php')) {
                    include(STUL_PATH . 'inc/views/frontend/form-templates/' . $form_template . '.php');
                }

                /**
                 * Triggers just after displaying the subscription form
                 *
                 * @param object $form_row
                 *
                 * @since 1.0.0
                 */
                do_action('stul_after_form', $form_details);
                ?>
            </form>
            <?php
            if (!empty($form_details['general']['verification']) && $form_details['general']['verification_type'] == 'unlock_code') {
                ?>
                <div class="stul-unlock-form-wrap">
                    <div class="stul-unlock-label"><?php echo esc_attr($form_details['general']['unlock_message']); ?></div>
                    <input type="text" class="stul-unlock-code-field"/>
                    <input type="button" class="stul-unlock-button stul-form-submit" value="<?php echo esc_attr($form_details['general']['unlock_button_label']) ?>"/>
                    <div class="stul-unlock-error-message"><?php echo $this->sanitize_html($form_details['general']['unlock_error_message']); ?></div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php if (!isset($atts['page_lock'])) { ?>
        <div class="stul-lock-content" <?php $this->display_none($lock_mode, 'soft'); ?>>
            <?php
            if (empty($content)) {
                echo do_shortcode($this->sanitize_html($form_details['general']['lock_content']));
            } else {
                echo do_shortcode($content);
            }
            ?>
        </div>
    <?php } ?>
</div>
