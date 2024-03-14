<?php

wp_enqueue_script( 'lpagery_tomtom', plugins_url( '../assets/js/external/lpagery_tomtom.js', __FILE__ ) );
wp_enqueue_script( 'lpagery_tomtom-searchbox', plugins_url( '../assets/js/external/lpagery_tomtom-searchbox.js', __FILE__ ) );
wp_enqueue_style( 'lpagery_tomtom-searchbox_css', plugins_url( '../assets/css/external/lpagery_tomtom-searchbox.css', __FILE__ ) );
wp_enqueue_script( 'jsintro', plugins_url( '../assets/js/external/lpagery_jsintro.js', __FILE__ ) );
wp_enqueue_script( 'select2', plugins_url( '../assets/js/external/lpagery_select2.js', __FILE__ ) );
wp_enqueue_script( 'jquery_validate', plugins_url( '../assets/js/external/lpagery_jquery_validate.js', __FILE__ ) );
wp_enqueue_script( 'jquery_validate_add', plugins_url( '../assets/js/external/lpagery_jquery_validate_add.js', __FILE__ ) );
wp_enqueue_style( 'select2_css', plugins_url( '../assets/css/external/lpagery_select2_css.css', __FILE__ ) );
wp_enqueue_style( 'jsintro_css', plugins_url( '../assets/css/external/lpagery_jsintro_css.css', __FILE__ ) );
wp_enqueue_style( 'toastify_css', plugins_url( '../assets/css/external/lpagery_toastify.css', __FILE__ ) );
wp_enqueue_script( 'toastify_js', plugins_url( '../assets/js/external/lpagery_toastify.js', __FILE__ ) );
wp_enqueue_style( 'fontawesome_css', plugins_url( '../assets/css/external/lpagery_fontawesome.min.css', __FILE__ ) );
//wp_enqueue_style( 'bootstrap_css', plugins_url( '../assets/css/external/lpagery_bootstrap.min.css', __FILE__ ) );
//wp_enqueue_style( 'bootstrap_css', plugins_url( '../assets/css/external/lpagery_bootstrap-grid.min.css', __FILE__ ) );
wp_enqueue_script(
    "jquery_modal",
    plugins_url( '../assets/js/external/lpagery_jquery_modal.js', __FILE__ ),
    array(),
    '1.4.11'
);
wp_enqueue_style(
    'jquery_modal_css',
    plugins_url( '../assets/css/external/lpagery_jquery_modal_css.css', __FILE__ ),
    array(),
    '1.4.11'
);
wp_enqueue_style( "js_grid_css", plugins_url( '../assets/css/external/lpagery_js_grid.css', __FILE__ ) );
wp_enqueue_style( "js_grid_theme_css", plugins_url( '../assets/css/external/lpagery_js_grid_theme_css.css', __FILE__ ) );
wp_enqueue_script( "jsgrid_js", plugins_url( '../assets/js/external/lpagery_jsgrid_js.js', __FILE__ ) );
wp_enqueue_script( "xlsx_js", plugins_url( '../assets/js/external/lpagery_xlsx_js.js', __FILE__ ) );
wp_enqueue_script( "jquery_csv", plugins_url( '../assets/js/external/lpagery_papaparse.min.js', __FILE__ ) );
wp_register_script(
    'lpagery_modal_js',
    plugin_dir_url( __FILE__ ) . '../assets/js/lpagery_modal.js',
    array( 'jquery', 'jsgrid_js', 'jquery_modal' ),
    '1.4.11',
    false
);
wp_enqueue_script( 'lpagery_modal_js' );
wp_localize_script( 'lpagery_modal_js', 'lpagery_ajax_object_modal', array(
    'is_free_plan'         => lpagery_fs()->is_free_plan(),
    'is_extended_plan'     => lpagery_fs()->is_plan_or_trial( "extended" ),
    'ajax_url'             => admin_url( 'admin-ajax.php' ),
    'nonce'                => wp_create_nonce( "lpagery_ajax" ),
    'plugin_dir'           => plugin_dir_url( dirname( __FILE__ ) ),
    'upload_dir'           => wp_upload_dir(),
    'allowed_placeholders' => json_encode( lpagery_get_placeholder_counts() ),
) );
wp_enqueue_style(
    'lpagery_css',
    plugins_url( '../assets/css/lpagery.css', __FILE__ ),
    null,
    "1.4.11"
);
$deps = array( 'jquery', 'lpagery_modal_js' );
wp_register_script(
    'lpagery_js',
    plugin_dir_url( __FILE__ ) . '../assets/js/lpagery.js',
    $deps,
    '1.4.11',
    false
);
wp_enqueue_script( 'lpagery_js' );
wp_localize_script( 'lpagery_js', 'lpagery_ajax_object_root', array(
    'is_free_plan'                    => lpagery_fs()->is_free_plan(),
    'is_permalink_structure_disabled' => empty(get_option( "permalink_structure" )),
    'ajax_url'                        => admin_url( 'admin-ajax.php' ),
    'plugin_dir'                      => plugin_dir_url( dirname( __FILE__ ) ),
    'nonce'                           => wp_create_nonce( "lpagery_ajax" ),
) );
wp_register_script(
    'lpagery_history_js',
    plugin_dir_url( __FILE__ ) . '../assets/js/lpagery_history.js',
    $deps,
    '1.4.11',
    false
);
wp_enqueue_script( 'lpagery_history_js' );
wp_localize_script( 'lpagery_history_js', 'lpagery_ajax_object_history', array(
    'ajax_url'   => admin_url( 'admin-ajax.php' ),
    'plugin_dir' => plugin_dir_url( dirname( __FILE__ ) ),
    'upload_dir' => wp_upload_dir(),
    'nonce'      => wp_create_nonce( "lpagery_ajax" ),
) );
wp_register_script(
    'lpagery_settings_js',
    plugin_dir_url( __FILE__ ) . '../assets/js/lpagery_settings.js',
    $deps,
    '1.4.11',
    false
);
wp_enqueue_script( 'lpagery_settings_js' );
wp_localize_script( 'lpagery_settings_js', 'lpagery_ajax_object_settings', array(
    'ajax_url' => admin_url( 'admin-ajax.php' ),
    'nonce'    => wp_create_nonce( "lpagery_ajax" ),
) );