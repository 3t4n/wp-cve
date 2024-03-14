<?php

function fnsf_af2_load_backend_resources() {
    af2_register_style('af2_general_styles', FNSF_AF2_BACKEND_STYLES_PATH.'/general_styles.css');
    af2_register_style('af2_theme', FNSF_AF2_BACKEND_STYLES_PATH.'/theme.css');

    af2_register_style('af2_builder_styles', FNSF_AF2_BACKEND_STYLES_PATH.'/builder/builder_styles.css');
    af2_register_style('af2_fragenbuilder_style', FNSF_AF2_BACKEND_STYLES_PATH.'/builder/physical_builder/fragenbuilder.css');
    af2_register_style('af2_kontaktformularbuilder_style', FNSF_AF2_BACKEND_STYLES_PATH.'/builder/physical_builder/kontaktformularbuilder.css');
    af2_register_style('af2_kontaktformularbuilder_settings_style', FNSF_AF2_BACKEND_STYLES_PATH.'/builder/physical_builder/kontaktformularbuilder_settings.css');
    af2_register_style('af2_formularbuilder_style', FNSF_AF2_BACKEND_STYLES_PATH.'/builder/physical_builder/formularbuilder.css');
    af2_register_style('af2_formularbuilder_settings_style', FNSF_AF2_BACKEND_STYLES_PATH.'/builder/physical_builder/formularbuilder_settings.css');
    af2_register_style('af2_formularbuilder_preview_style', FNSF_AF2_BACKEND_STYLES_PATH.'/builder/physical_builder/formularbuilder_preview.css');


    af2_register_style('af2_fontawesome', FNSF_AF2_BACKEND_STYLES_PATH.'/fontawesome_5.15.4.css');

    af2_register_script('af2_jQuery', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/libs/noconflict.js', array()); 
    af2_register_script('af2_menu_components', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/menu_components.js', array('af2_jQuery'));
    af2_register_script('af2_builder', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/builder/builder.js', array('af2_jQuery', 'af2_modal', 'af2_toast'));
    af2_register_script('af2_sidebar', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/builder/sidebar.js', array('af2_jQuery', 'af2_builder'));
    af2_register_script('af2_fragenbuilder', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/builder/physical_builder/fragenbuilder.js', array('af2_jQuery', 'af2_builder'));
    af2_register_script('af2_kontaktformularbuilder', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/builder/physical_builder/kontaktformularbuilder.js', array('af2_jQuery', 'af2_builder'));
    af2_register_script('af2_kontaktformularbuilder_settings', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/builder/physical_builder/kontaktformularbuilder_settings.js', array('af2_jQuery', 'af2_builder'));
    af2_register_script('af2_formularbuilder', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/builder/physical_builder/formularbuilder.js', array('af2_jQuery', 'af2_builder', 'af2_svg_handler', 'af2_modal'));
    af2_register_script('af2_formularbuilder_settings', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/builder/physical_builder/formularbuilder_settings.js', array('af2_jQuery', 'af2_builder'));
    af2_register_script('af2_formularbuilder_preview', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/builder/physical_builder/formularbuilder_preview.js', array('af2_jQuery', 'af2_builder'));



     af2_register_script('af2_demoimport', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/custom_menus/demoimport.js', array('af2_jQuery'));
    
    af2_register_script('af2_category_script', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/table_menus/category.js', array('af2_jQuery'));
    af2_register_style('af2_category', FNSF_AF2_BACKEND_STYLES_PATH.'/category.css', array('af2_jQuery'));


    af2_register_style('af2_import_export_style', FNSF_AF2_BACKEND_STYLES_PATH.'/custom_menus/import_export.css');
    af2_register_style('af2_demoimport_style', FNSF_AF2_BACKEND_STYLES_PATH.'/custom_menus/demoimport.css');
    af2_register_style('af2_integrationen_style', FNSF_AF2_BACKEND_STYLES_PATH.'/custom_menus/integrationen.css');
    af2_register_style('af2_dashboard_style', FNSF_AF2_BACKEND_STYLES_PATH.'/custom_menus/dashboard.css');
    af2_register_style('af2_checklist_style', FNSF_AF2_BACKEND_STYLES_PATH.'/custom_menus/checklist.css');
    af2_register_style('af2_leaddetails_style', FNSF_AF2_BACKEND_STYLES_PATH.'/custom_menus/leaddetails.css');

    af2_register_script('af2_toast', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/builder/toast.js', array('af2_jQuery'));
    af2_register_style('af2_toast_style', FNSF_AF2_BACKEND_STYLES_PATH.'/builder/toast.css');


    af2_register_script('af2_media', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/builder/icon_imagepicker/media.js', array('af2_jQuery'));
    af2_register_style('af2_iconpicker_style', FNSF_AF2_BACKEND_STYLES_PATH.'/builder/icon_imagepicker/iconpicker.css');
    af2_register_script('af2_iconpicker', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/builder/icon_imagepicker/iconpicker.js', array('af2_jQuery', 'af2_modal'));

    af2_register_style('af2_colorpicker_style', FNSF_AF2_BACKEND_STYLES_PATH.'/builder/color_picker/color_picker.css');
    af2_register_script('af2_colorpicker', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/builder/color_picker/color_picker.js', array('af2_jQuery'));

    af2_register_style('af2_modal_style', FNSF_AF2_BACKEND_STYLES_PATH.'/modal.css');
    af2_register_script('af2_modal', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/modal.js', array('af2_jQuery'));
    
    af2_register_script('af2_interact_js', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/libs/interact.min.js', array());
    af2_register_script('af2_svg_handler', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/builder/drag_drop_zoom/svg_handler.js', array('af2_jQuery', 'af2_zoom'));
    af2_register_script('af2_dragscroll', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/builder/drag_drop_zoom/dragscroll.js', array('af2_jQuery'));
    af2_register_script('af2_drag_drop', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/builder/drag_drop_zoom/drag_drop.js', array('af2_jQuery', 'af2_interact_js', 'af2_builder', 'af2_svg_handler', 'af2_zoom'));
    af2_register_script('af2_zoom', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/builder/drag_drop_zoom/zoom.js', array('af2_jQuery', 'af2_builder'));


    af2_register_script('af2_daterangepicker', FNSF_AF2_BACKEND_SCRIPTS_PATH.'/libs/daterangepicker.min.js', array('af2_jQuery', 'af2_momentjs'));
    af2_register_style_url('af2_daterangepicker_style', FNSF_AF2_FRONTEND_STYLES_PATH.'/daterangepicker.css');
}

add_action('admin_enqueue_scripts', 'fnsf_af2_load_backend_resources');


function af2_load_daterangepicker_resources() {
    wp_enqueue_script( 'moment' );
    wp_enqueue_script( 'af2_daterangepicker' );
    wp_enqueue_style('af2_daterangepicker_style');
}


function af2_register_style($slug, $path) {
    wp_register_style($slug, plugins_url($path, AF2F_PLUGIN), array(), FNSF_AF2_FINAL_VERSION);
}
function af2_register_style_url($slug, $url) {
    wp_register_style($slug, $url, array(), FNSF_AF2_FINAL_VERSION);
}
function af2_register_script($slug, $path, $deps) {
    wp_register_script($slug, plugins_url($path, AF2F_PLUGIN), $deps, FNSF_AF2_FINAL_VERSION);
}
/*
function af2_register_script_url($slug, $url, $deps = array()) {
    wp_register_script($slug, $url, $deps, FNSF_AF2_FINAL_VERSION);
}
*/

function load_basic_admin_menu_resources() {

    load_basic_admin_resources();

    wp_localize_script( 'af2_menu_components', 'af2_menu_components_object', array(
        'ajax_url' => admin_url( 'admin-ajax.php'),
        'dark_mode' => get_option('af2_dark_mode'),
        'nonce' => wp_create_nonce('af2_FE_nonce'),
    ));
    wp_enqueue_script('af2_menu_components');
}

function load_basic_admin_builder_resources(array $builder_localize_array = array()) {
    load_basic_admin_resources();

    wp_enqueue_style('af2_builder_styles');

    wp_localize_script( 'af2_builder', 'af2_builder_object', $builder_localize_array );

    wp_enqueue_style('af2_modal_style');
    wp_enqueue_script('af2_modal');

    wp_enqueue_script('af2_builder');
    wp_enqueue_script('af2_sidebar');

    wp_enqueue_style('af2_toast_style');
    wp_enqueue_script('af2_toast');
}

function load_basic_admin_resources() {

    wp_enqueue_style('af2_general_styles');
    wp_enqueue_style('af2_theme');
    wp_enqueue_style('af2_google_font');
    wp_enqueue_style('af2_fontawesome');

    wp_enqueue_style('af2_modal_style');
    wp_enqueue_script('af2_modal');
}

function load_media_iconpicker() {
    wp_enqueue_media();
    wp_localize_script( 'af2_media', 'af2_media_object', array(
        'grafik_auswählen_string' => __('Select image', 'funnelforms-free'),
    ));
    wp_enqueue_script('af2_media');
    wp_enqueue_script('af2_iconpicker');
    wp_enqueue_style('af2_iconpicker_style');
}

function load_colorpicker() {
    wp_enqueue_script('af2_colorpicker');
    wp_enqueue_style('af2_colorpicker_style');
}


// 0 usual - 1 preview - 2 no media
function af2_load_frontend_resources($type = 0) {
    af2_register_script( 'af2_frontend', FNSF_AF2_FRONTEND_SCRIPTS_PATH.'/frontend.js', array('jquery') );
    af2_register_script('af2_phoneinput', FNSF_AF2_FRONTEND_SCRIPTS_PATH.'/intlTelInputSelect2.js', array('jquery'));
    af2_register_style( 'af2_fa_style', FNSF_AF2_FRONTEND_STYLES_PATH.'/fontawesome_5.15.4.css' );
    switch($type) {
        case 0: {
            af2_register_style( 'af2_frontend_style_usual',  FNSF_AF2_FRONTEND_STYLES_PATH.'/frontend.css' );
            break;
        }
        case 1: {
            af2_register_style( 'af2_frontend_style_preview',  FNSF_AF2_FRONTEND_STYLES_PATH.'/frontend_preview.css' );
            break;
        }
        case 2: {
            af2_register_style( 'af2_frontend_style_no_media',  FNSF_AF2_FRONTEND_STYLES_PATH.'/frontend_no_media.css' );
            break;
        }
        default: {
            af2_register_style( 'af2_frontend_style_usual',  FNSF_AF2_FRONTEND_STYLES_PATH.'/frontend.css' );
            break;
        }
    }
    af2_register_style( 'af2_phoneinput_style',  FNSF_AF2_FRONTEND_STYLES_PATH.'/intlTelInput.css' );
}

function load_basic_frontend_resources($type = 0) {
    wp_enqueue_script( 'af2_frontend' );
    wp_enqueue_script( 'af2_phoneinput' );
    wp_enqueue_style( 'af2_fa_style' );
    
    switch($type) {
        case 0: {
            wp_enqueue_style( 'af2_frontend_style_usual' );
            break;
        }
        case 1: {
            wp_enqueue_style( 'af2_frontend_style_preview' );
            break;
        }
        case 2: {
            wp_enqueue_style( 'af2_frontend_style_no_media' );
            break;
        }
        default: {
            wp_enqueue_style( 'af2_frontend_style_usual' );
            break;
        }
    }
    wp_enqueue_style( 'af2_phoneinput_style' );
}

