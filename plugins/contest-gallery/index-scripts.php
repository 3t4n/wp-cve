<?php

wp_enqueue_script( 'jquery-touch-punch' );
wp_enqueue_script( 'jquery-ui-sortable' );

wp_enqueue_script( 'jquery-ui-datepicker' );

wp_enqueue_editor();

wp_enqueue_script( 'cg_gallery_admin_vendor_moment', plugins_url( '/v10/v10-js/admin/vendor/moment.min.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_gallery_admin_vendor_daterangepicker_js', plugins_url('/v10/v10-js/admin/vendor/daterangepicker.js', __FILE__), array('jquery'), cg_get_version_for_scripts() );

wp_enqueue_script( 'cg_gallery_admin_index_events', plugins_url( '/v10/v10-js/admin/index-events.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_gallery_admin_index_functions', plugins_url( '/v10/v10-js/admin/index-functions.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_gallery_admin_index_load', plugins_url( '/v10/v10-js/admin/load/index-load.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_gallery_admin_index_indexeddb', plugins_url( '/v10/v10-js/admin/index-indexeddb.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_gallery_admin_objects', plugins_url( '/v10/v10-js/admin/gallery/gallery-admin-objects.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_gallery_admin_functions', plugins_url( '/v10/v10-js/admin/gallery/gallery-admin-functions.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_gallery_admin_gallery_load_init_or_add', plugins_url( '/v10/v10-js/admin/load/gallery-load-init-or-add.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_gallery_admin_gallery_load_change_or_sort', plugins_url( '/v10/v10-js/admin/load/gallery-load-change-or-sort.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_gallery_admin_events', plugins_url( '/v10/v10-js/admin/gallery/events/gallery-admin-events.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_gallery_admin_events_multiple_files', plugins_url( '/v10/v10-js/admin/gallery/events/gallery-admin-events-multiple-files.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_check_wp_admin_upload_v10', plugins_url( '/v10/v10-js/admin/gallery/cg_check_wp_admin_upload.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_style( 'cg_backend_gallery', plugins_url('/v10/v10-css/backend/cg_backend_gallery.css', __FILE__), false, cg_get_version_for_scripts() );
wp_enqueue_style( 'cg_gallery_admin_vendor_daterangepicker_css', plugins_url('/v10/v10-css/vendor/daterangepicker.css', __FILE__), false, cg_get_version_for_scripts() );


wp_enqueue_script( 'cg_js_admin_main_menu_events', plugins_url( '/v10/v10-js/admin/main-menu-events.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_js_admin_main_menu_functions', plugins_url( '/v10/v10-js/admin/main-menu-functions.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );

wp_enqueue_script( 'cg_general_admin_functions', plugins_url( '/v10/v10-js/admin/general_admin_functions.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );

wp_enqueue_script( 'cg_js_admin_corrections_and_improvements', plugins_url( '/v10/v10-js/admin/options/corrections-and-improvements.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );

wp_enqueue_script( 'cg_js_admin_options_edit_options', plugins_url( '/v10/v10-js/admin/options/edit-options-events.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_js_admin_options_edit_options_functions', plugins_url( '/v10/v10-js/admin/options/edit-options-functions.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_color_picker_js', plugins_url( '/v10/v10-admin/options/color-picker.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
#wp_enqueue_script( 'cg_options_tabcontent_js', plugins_url( '/v10/v10-admin/options/cg_options_tabcontent.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_style( 'cg_datepicker_css', plugins_url('/v10/v10-admin/options/datepicker.css', __FILE__), false, cg_get_version_for_scripts() );
wp_enqueue_style( 'cg_color_picker_css', plugins_url('/v10/v10-admin/options/color-picker.css', __FILE__), false, cg_get_version_for_scripts() );

//wp_enqueue_script( 'cg_backend_bootstrap_js', plugins_url( '/v10/v10-js/admin/bootstrap.min.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
//wp_enqueue_style( 'cg_backend_bootstrap_css', plugins_url('v10-css/backend/cg_backend_bootstrap.css', __FILE__), false , cg_get_version_for_scripts() );

wp_enqueue_script( 'cg_js_admin_options_show_votes', plugins_url( '/v10/v10-js/admin/show_votes.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );

wp_enqueue_style( 'cg_css_general_rotate_image', plugins_url('/v10/v10-css/backend/cg_rotate_image.css', __FILE__), false, cg_get_version_for_scripts() );

wp_enqueue_style( 'cg_options_style_v10', plugins_url('/v10/v10-css/cg_options_style.css', __FILE__), false , cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_admin_create_upload_create_upload_v10', plugins_url( '/v10/v10-js/admin/create-upload/create-upload-events.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_admin_create_upload_create_upload_tinymce', plugins_url( '/v10/v10-js/admin/create-upload/create-upload-tinymce.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_admin_create_upload_create_upload_v10_functions', plugins_url( '/v10/v10-js/admin/create-upload/create-upload-functions.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_admin_create_upload_tinymce', plugins_url( '/v10/v10-js/admin/create-upload/tinymce.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );


wp_enqueue_script( 'cg_create_registry_events', plugins_url( '/v10/v10-js/admin/create-registry/create-registry-events.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_create_registry_functions', plugins_url( '/v10/v10-js/admin/create-registry/create-registry-functions.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );


wp_enqueue_style( 'cg_backend_gallery', plugins_url('v10-css/backend/cg_backend_gallery.css', __FILE__), false, cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_admin_users_management_users_management_v10', plugins_url( '/v10/v10-js/admin/users-management/users-management-events.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );

wp_enqueue_script( 'cg_check_wp_admin_upload_v10', plugins_url( '/v10/v10-js/admin/gallery/cg_check_wp_admin_upload.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );

wp_enqueue_script( 'cg_check_wp_admin_upload_v10', plugins_url( '/v10/v10-js/admin/gallery/cg_check_wp_admin_upload.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );

wp_enqueue_script( 'cg_js_admin_interval_conf_events', plugins_url( '/v10/v10-js/admin/interval-conf-events.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );
wp_enqueue_script( 'cg_js_admin_interval_conf_functions', plugins_url( '/v10/v10-js/admin/interval-conf-functions.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts() );

