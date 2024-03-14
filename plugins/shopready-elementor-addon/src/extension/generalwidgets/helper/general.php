<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!function_exists('woo_ready_get_we_forms_list')) {

    /**
     * get widgets class list
     *
     * @since 1.0
     * @return array
     */
    function woo_ready_get_we_forms_list()
    {

        $forms = [];
        if (class_exists('WeForms')) {
            $_forms = get_posts([
                'post_type' => 'wpuf_contact_form',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order' => 'ASC',
            ]);

            if (!empty($_forms)) {
                $forms = wp_list_pluck($_forms, 'post_title', 'ID');
            }
        }
        return $forms;
    }
}


if (!function_exists('woo_ready_get_contact_forms_seven_list')) {
    /*----------------------------
        CONTACT FORM 7 RETURN ARRAY
    -------------------------------*/
    function woo_ready_get_contact_forms_seven_list()
    {

        $forms_list = array();
        $forms_args = array('posts_per_page' => -1, 'post_type' => 'wpcf7_contact_form');
        $forms = get_posts($forms_args);

        if ($forms) {
            foreach ($forms as $form) {
                $forms_list[$form->ID] = $form->post_title;
            }
        } else {
            $forms_list[esc_html__('No contact form found', 'shopready-elementor-addon')] = 0;
        }
        return $forms_list;
    }
}