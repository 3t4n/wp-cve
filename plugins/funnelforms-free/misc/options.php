<?php

// Include misc functions
require_once FNSF_AF2_MISC_FUNCTIONS_PATH;


if (!get_option('af2_version_num_')) {
    add_option('af2_version_num_', '1');
}

if (!get_option('af2_version')) {
    add_option('af2_version', FNSF_AF2_FINAL_VERSION);
} else {
    update_option('af2_version', FNSF_AF2_FINAL_VERSION);
}

if (!get_option('af2_dark_mode')) {
    add_option('af2_dark_mode', '0');
}

add_action('init', function() {
    if(!get_option('af2_categories')) {

        $id = wp_insert_post(array('post_content' => urlencode(serialize(array())), 'post_type' => 'af2_categories', 'post_status' => 'privat'));
        add_option('af2_categories', strval($id));
    }

});

add_action('init', function() {
    if(!get_option('af2_question_categories')) {

        $id = wp_insert_post(array('post_content' => '{ "categories": [] }', 'post_type' => 'af2_categories', 'post_status' => 'privat'));
        add_option('af2_question_categories', strval($id));
    }

    if(!get_option('af2_verification_codes')) {

        $id = wp_insert_post(array('post_content' => '{ "codes": [] }', 'post_type' => 'af2_ver_codes', 'post_status' => 'privat'));
        add_option('af2_verification_codes', strval($id));
    }
});


// Checklist options

if (!get_option('checklist_question')) {
    add_option('checklist_question', '');
}
if(!get_option('checklist_contactform')) {
    add_option('checklist_contactform', 'false');
}
if(!get_option('checklist_form')) {
    add_option('checklist_form', 'false');
}
if(!get_option('checklist_shortcode')) {
    add_option('checklist_shortcode', 'false');
}