<div class="wpsf-form-wrap wpsf-<?php echo esc_attr($form_template); ?> wpsf-<?php echo esc_attr($form_row->form_alias); ?>">
    <form method="post" action="" class="wpsf-subscription-form" data-form-alias="<?php echo esc_attr($form_row->form_alias); ?>">

        <?php
        /**
         * Triggers just before displaying the subscription form
         *
         * @param object $form_row
         *
         * @since 1.0.0
         */
        do_action('wpsf_before_form', $form_row);

        if (file_exists(WPSF_PATH . 'inc/views/frontend/form-templates/' . $form_template . '.php')) {
            include(WPSF_PATH . 'inc/views/frontend/form-templates/' . $form_template . '.php');
        }

        /**
         * Triggers just after displaying the subscription form
         *
         * @param object $form_row
         *
         * @since 1.0.0
         */
        do_action('wpsf_after_form', $form_row);
        ?>
    </form>
</div>