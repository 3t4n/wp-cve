<?php
// If the file is accessed directly, close access
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	exit();

$glideffxf_data_install_related_and_upsells = 'glideffxf_data_install_related_and_upsells';

$glideffxf_related_title = 'glideffxf_related_title';
$glideffxf_related_autoplay = 'glideffxf_related_autoplay';
$glideffxf_related_hover_stop = 'glideffxf_related_hover_stop';
$glideffxf_related_interval = 'glideffxf_related_interval';
$glideffxf_related_quantity = 'glideffxf_related_quantity';
$glideffxf_related_visible = 'glideffxf_related_visible';
$glideffxf_related_lm = 'glideffxf_related_lm';
$glideffxf_related_td = 'glideffxf_related_td';
$glideffxf_related_md = 'glideffxf_related_md';
$glideffxf_upsells_title = 'glideffxf_upsells_title';
$glideffxf_upsells_autoplay = 'glideffxf_upsells_autoplay';
$glideffxf_upsells_hover_stop = 'glideffxf_upsells_hover_stop';
$glideffxf_upsells_interval = 'glideffxf_upsells_interval';
$glideffxf_upsells_quantity = 'glideffxf_upsells_quantity';
$glideffxf_upsells_visible = 'glideffxf_upsells_visible';
$glideffxf_upsells_lm = 'glideffxf_upsells_lm';
$glideffxf_upsells_td = 'glideffxf_upsells_td';
$glideffxf_upsells_md = 'glideffxf_upsells_md';
$glideffxf_related_no_varusel = 'glideffxf_related_no_varusel';
$glideffxf_related_no_upsells = 'glideffxf_related_no_upsells';

$glideffxf_related_mobile_notification = 'glideffxf_related_mobile_notification';
$glideffxf_related_mobile_tooltip_color = 'glideffxf_related_mobile_tooltip_color';
$glideffxf_related_center_mode = 'glideffxf_related_center_mode';
$glideffxf_related_center_mode_mobile = 'glideffxf_related_center_mode_mobile';
$glideffxf_related_center_mode_left = 'glideffxf_related_center_mode_left';
$glideffxf_related_center_mode_right = 'glideffxf_related_center_mode_right';
$glideffxf_related_animation = 'glideffxf_related_animation';
$glideffxf_releted_animationDuration = 'glideffxf_releted_animationDuration';
$glideffxf_releted_gap = 'glideffxf_releted_gap';
$glideffxf_releted_navigation = 'glideffxf_releted_navigation';
$glideffxf_releted_picker = 'glideffxf_releted_picker';

$glideffxf_upsells_mobile_notification = 'glideffxf_upsells_mobile_notification';
$glideffxf_upsells_mobile_tooltip_color = 'glideffxf_upsells_mobile_tooltip_color';
$glideffxf_upsells_center_mode = 'glideffxf_upsells_center_mode';
$glideffxf_upsells_center_mode_mobile = 'glideffxf_upsells_center_mode_mobile';
$glideffxf_upsells_center_mode_left = 'glideffxf_upsells_center_mode_left';
$glideffxf_upsells_center_mode_right = 'glideffxf_upsells_center_mode_right';
$glideffxf_upsells_animation = 'glideffxf_upsells_animation';
$glideffxf_upsells_animationDuration = 'glideffxf_upsells_animationDuration';
$glideffxf_upsells_gap = 'glideffxf_upsells_gap';
$glideffxf_upsells_navigation = 'glideffxf_upsells_navigation';
$glideffxf_upsells_picker = 'glideffxf_upsells_picker';

$glideffxf_releted_filter_fix = 'glideffxf_releted_filter_fix';
$glideffxf_releted_function_fix = 'glideffxf_releted_function_fix';
$glideffxf_upsells_filter_fix = 'glideffxf_upsells_filter_fix';

$glideffxf_releted_javascript_fix = 'glideffxf_releted_javascript_fix';
$glideffxf_upsells_javascript_fix = 'glideffxf_releted_javascript_fix';




// For a regular site.
if ( !is_multisite() ) {
    delete_option( $glideffxf_data_install_related_and_upsells );
    delete_option( $glideffxf_related_title );
    delete_option( $glideffxf_related_autoplay );
    delete_option( $glideffxf_related_hover_stop );
    delete_option( $glideffxf_related_interval );
    delete_option( $glideffxf_related_quantity );
    delete_option( $glideffxf_related_visible );
    delete_option( $glideffxf_related_lm );
    delete_option( $glideffxf_related_td );
    delete_option( $glideffxf_related_md );
    delete_option( $glideffxf_upsells_title );
    delete_option( $glideffxf_upsells_autoplay );
    delete_option( $glideffxf_upsells_hover_stop );
    delete_option( $glideffxf_upsells_interval );
    delete_option( $glideffxf_upsells_quantity );
    delete_option( $glideffxf_upsells_lm );
    delete_option( $glideffxf_upsells_td );
    delete_option( $glideffxf_upsells_md );
	delete_option( $glideffxf_related_no_varusel );
	delete_option( $glideffxf_related_no_upsells );

	delete_option( $glideffxf_related_mobile_notification );
	delete_option( $glideffxf_related_mobile_tooltip_color );
	delete_option( $glideffxf_related_center_mode );
	delete_option( $glideffxf_related_center_mode_mobile );
	delete_option( $glideffxf_related_center_mode_left );
	delete_option( $glideffxf_related_center_mode_right );
	delete_option( $glideffxf_related_animation );
	delete_option( $glideffxf_releted_animationDuration );
	delete_option( $glideffxf_releted_gap );
	delete_option( $glideffxf_releted_navigation );
	delete_option( $glideffxf_releted_picker );

	delete_option( $glideffxf_upsells_mobile_notification );
	delete_option( $glideffxf_upsells_mobile_tooltip_color );
	delete_option( $glideffxf_upsells_center_mode );
	delete_option( $glideffxf_upsells_center_mode_mobile );
	delete_option( $glideffxf_upsells_center_mode_left );
	delete_option( $glideffxf_upsells_center_mode_right );
	delete_option( $glideffxf_upsells_animation );
	delete_option( $glideffxf_upsells_animationDuration );
	delete_option( $glideffxf_upsells_gap );
	delete_option( $glideffxf_upsells_navigation );
	delete_option( $glideffxf_upsells_picker );

	delete_option( $glideffxf_releted_javascript_fix );
	delete_option( $glideffxf_upsells_javascript_fix );

} 
// For multisite assembly.
else {
	global $wpdb;

	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
	$original_blog_id = get_current_blog_id();

	foreach ( $blog_ids as $blog_id )   {
        switch_to_blog( $blog_id );
        delete_site_option( $glideffxf_data_install_related_and_upsells );
        delete_site_option( $glideffxf_related_title );
        delete_site_option( $glideffxf_related_autoplay );
        delete_site_option( $glideffxf_related_hover_stop );
        delete_site_option( $glideffxf_related_interval );
        delete_site_option( $glideffxf_related_quantity );
        delete_site_option( $glideffxf_related_visible );
        delete_site_option( $glideffxf_related_lm );
        delete_site_option( $glideffxf_related_td );
        delete_site_option( $glideffxf_related_md );
        delete_site_option( $glideffxf_upsells_title );
        delete_site_option( $glideffxf_upsells_autoplay );
        delete_site_option( $glideffxf_upsells_hover_stop );
        delete_site_option( $glideffxf_upsells_interval );
        delete_site_option( $glideffxf_upsells_quantity );
        delete_site_option( $glideffxf_upsells_lm );
        delete_site_option( $glideffxf_upsells_td );
        delete_site_option( $glideffxf_upsells_md );
		delete_site_option( $glideffxf_related_no_varusel );
		delete_site_option( $glideffxf_related_no_upsells );

		delete_site_option( $glideffxf_related_mobile_notification );
		delete_site_option( $glideffxf_related_mobile_tooltip_color );
		delete_site_option( $glideffxf_related_center_mode );
		delete_site_option( $glideffxf_related_center_mode_mobile );
		delete_site_option( $glideffxf_related_center_mode_left );
		delete_site_option( $glideffxf_related_center_mode_right );
		delete_site_option( $glideffxf_related_animation );
		delete_site_option( $glideffxf_releted_animationDuration );
		delete_site_option( $glideffxf_releted_gap );
		delete_site_option( $glideffxf_releted_navigation );
		delete_site_option( $glideffxf_releted_picker );

		delete_site_option( $glideffxf_upsells_mobile_notification );
		delete_site_option( $glideffxf_upsells_mobile_tooltip_color );
		delete_site_option( $glideffxf_upsells_center_mode );
		delete_site_option( $glideffxf_upsells_center_mode_mobile );
		delete_site_option( $glideffxf_upsells_center_mode_left );
		delete_site_option( $glideffxf_upsells_center_mode_right );
		delete_site_option( $glideffxf_upsells_animation );
		delete_site_option( $glideffxf_upsells_animationDuration );
		delete_site_option( $glideffxf_upsells_gap );
		delete_site_option( $glideffxf_upsells_navigation );
		delete_site_option( $glideffxf_upsells_picker );

		delete_site_option( $glideffxf_releted_filter_fix );
		delete_site_option( $glideffxf_releted_function_fix );
		delete_site_option( $glideffxf_upsells_filter_fix );

		delete_site_option( $glideffxf_releted_javascript_fix );
		delete_site_option( $glideffxf_upsells_javascript_fix );

	}

	switch_to_blog( $original_blog_id );
}