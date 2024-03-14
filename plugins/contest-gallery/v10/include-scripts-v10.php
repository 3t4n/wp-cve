<?php

wp_enqueue_script( 'jquery-touch-punch' );
wp_enqueue_script( 'jquery-ui-slider' );
wp_enqueue_script( 'jquery-ui-datepicker' );
wp_enqueue_script( 'jquery-ui-sortable' );

wp_enqueue_style( 'cg_v10_css_cg_gallery', plugins_url('/v10-css-min/cg_gallery.min.css', __FILE__), false, cg_get_version_for_scripts() );

wp_enqueue_script( 'cg_v10_js_cg_gallery', plugins_url( '/v10-js-min/cg_gallery.min.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts());

// Achtung! Nicht von hier verschieben und die Reihenfolge beachten. Wp_enque kommt for wp_localize
wp_localize_script( 'cg_v10_js_cg_gallery', 'post_cg_rate_v10_oneStar_wordpress_ajax_script_function_name', array(
    'cg_rate_v10_oneStar_ajax_url' => admin_url( 'admin-ajax.php' )
));

// Reihenfolge beachten
wp_localize_script( 'cg_v10_js_cg_gallery', 'post_cg_rate_v10_fiveStar_wordpress_ajax_script_function_name', array(
    'cg_rate_v10_fiveStar_ajax_url' => admin_url( 'admin-ajax.php' )
));

wp_localize_script( 'cg_v10_js_cg_gallery', 'post_cg_gallery_form_upload_wordpress_ajax_script_function_name', array(
    'cg_gallery_form_upload_ajax_url' => admin_url( 'admin-ajax.php' )
));

wp_localize_script( 'cg_v10_js_cg_gallery', 'cg_show_set_comments_v10_wordpress_ajax_script_function_name', array(
    'cg_show_set_comments_v10_ajax_url' => admin_url( 'admin-ajax.php' )
));

wp_localize_script( 'cg_v10_js_cg_gallery', 'post_cg_gallery_user_delete_image_wordpress_ajax_script_function_name', array(
    'cg_gallery_user_delete_image_ajax_url' => admin_url( 'admin-ajax.php' )
));

wp_localize_script( 'cg_v10_js_cg_gallery', 'post_cg_gallery_user_edit_image_data_wordpress_ajax_script_function_name', array(
    'cg_gallery_user_edit_image_data_ajax_url' => admin_url( 'admin-ajax.php' )
));

wp_localize_script( 'cg_v10_js_cg_gallery', 'post_cg_pro_version_info_recognized_wordpress_ajax_script_function_name', array(
    'cg_pro_version_info_recognized_ajax_url' => admin_url( 'admin-ajax.php' )
));

wp_localize_script( 'cg_v10_js_cg_gallery', 'post_cg_changes_recognized_wordpress_ajax_script_function_name', array(
    'cg_changes_recognized_ajax_url' => admin_url( 'admin-ajax.php' )
));

wp_localize_script( 'cg_v10_js_cg_gallery', 'post_cg_set_frontend_cookie_wordpress_ajax_script_function_name', array(
    'cg_set_frontend_cookie_ajax_url' => admin_url( 'admin-ajax.php' )
));

ob_start();
echo "<pre class='cg_main_pre cg_10 cg_20' >";
include("v10-frontend/v10-get-data.php");
echo "</pre>";

$frontend_gallery = ob_get_clean();
apply_filters( 'cg_filter_frontend_gallery', $frontend_gallery );