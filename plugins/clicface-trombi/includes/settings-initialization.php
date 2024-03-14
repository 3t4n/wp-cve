<?php
$clicface_trombi_settings = get_option('clicface_trombi_settings');

if ( !isset( $clicface_trombi_settings['vignette_color_border'] ) )						$clicface_trombi_settings['vignette_color_border'] = '#000000';
if ( !isset( $clicface_trombi_settings['vignette_border_thickness'] ) )					$clicface_trombi_settings['vignette_border_thickness'] = 1;
if ( !isset( $clicface_trombi_settings['vignette_border_radius'] ) )					$clicface_trombi_settings['vignette_border_radius'] = 1;
if ( !isset( $clicface_trombi_settings['vignette_color_background_top'] ) )				$clicface_trombi_settings['vignette_color_background_top'] = '#FFFFFF';
if ( !isset( $clicface_trombi_settings['vignette_color_background_bottom'] ) )			$clicface_trombi_settings['vignette_color_background_bottom'] = '#FFFFFF';
if ( !isset( $clicface_trombi_settings['vignette_ext_drop_shadow'] ) )					$clicface_trombi_settings['vignette_ext_drop_shadow'] = 'oui';
if ( !isset( $clicface_trombi_settings['vignette_int_drop_shadow'] ) )					$clicface_trombi_settings['vignette_int_drop_shadow'] = 'oui';
if ( !isset( $clicface_trombi_settings['trombi_affichage_type'] ) )						$clicface_trombi_settings['trombi_affichage_type'] = 'grid';
if ( !isset( $clicface_trombi_settings['trombi_display_service'] ) )					$clicface_trombi_settings['trombi_display_service'] = 'oui';
if ( !isset( $clicface_trombi_settings['trombi_display_phone'] ) )						$clicface_trombi_settings['trombi_display_phone'] = 'non';
if ( !isset( $clicface_trombi_settings['trombi_display_cellular'] ) )					$clicface_trombi_settings['trombi_display_cellular'] = 'non';
if ( !isset( $clicface_trombi_settings['trombi_display_email'] ) )						$clicface_trombi_settings['trombi_display_email'] = 'non';
if ( !isset( $clicface_trombi_settings['trombi_collaborateurs_par_ligne'] ) )			$clicface_trombi_settings['trombi_collaborateurs_par_ligne'] = 3;
if ( !isset( $clicface_trombi_settings['vignette_width'] ) )							$clicface_trombi_settings['vignette_width'] = 250;
if ( !isset( $clicface_trombi_settings['trombi_target_window'] ) )						$clicface_trombi_settings['trombi_target_window'] = 'thickbox';
if ( !isset( $clicface_trombi_settings['trombi_profile_width_type'] ) )					$clicface_trombi_settings['trombi_profile_width_type'] = 'fixed';
if ( !isset( $clicface_trombi_settings['trombi_profile_width_size'] ) )					$clicface_trombi_settings['trombi_profile_width_size'] = 720;
if ( !isset( $clicface_trombi_settings['trombi_profile_height_type'] ) )				$clicface_trombi_settings['trombi_profile_height_type'] = 'fixed';
if ( !isset( $clicface_trombi_settings['trombi_profile_height_size'] ) )				$clicface_trombi_settings['trombi_profile_height_size'] = 540;
if ( !isset( $clicface_trombi_settings['trombi_display_worksite'] ) )					$clicface_trombi_settings['trombi_display_worksite'] = 'non';
if ( !isset( $clicface_trombi_settings['trombi_display_return_link'] ) )				$clicface_trombi_settings['trombi_display_return_link'] = 'non';
if ( !isset( $clicface_trombi_settings['trombi_move_to_anchor'] ) )						$clicface_trombi_settings['trombi_move_to_anchor'] = 'non';
if ( !isset( $clicface_trombi_settings['trombi_thickbox_width'] ) )						$clicface_trombi_settings['trombi_thickbox_width'] = 800;
if ( !isset( $clicface_trombi_settings['trombi_thickbox_height'] ) )					$clicface_trombi_settings['trombi_thickbox_height'] = 670;
if ( !isset( $clicface_trombi_settings['trombi_title_name_singular'] ) )				$clicface_trombi_settings['trombi_title_name_singular'] = __('Employee', 'clicface-trombi');
if ( !isset( $clicface_trombi_settings['trombi_title_name_plural'] ) )					$clicface_trombi_settings['trombi_title_name_plural'] = __('Employees', 'clicface-trombi');

update_option( 'clicface_trombi_settings', clicface_trombi_settings_validate($clicface_trombi_settings) );

if ( is_plugin_active( 'clicface-organi/clicface-organi.php' ) ) {
	$clicface_organi_settings = get_option('clicface_organi_settings');
	
	if ( !isset( $clicface_organi_settings['organi_css_stylesheet'] ) )					$clicface_organi_settings['organi_css_stylesheet'] = 'style1';
	if ( !isset( $clicface_organi_settings['organi_display_service'] ) )				$clicface_organi_settings['organi_display_service'] = 'oui';
	if ( !isset( $clicface_organi_settings['organi_display_phone'] ) )					$clicface_organi_settings['organi_display_phone'] = 'non';
	if ( !isset( $clicface_organi_settings['organi_display_cellular'] ) )				$clicface_organi_settings['organi_display_cellular'] = 'oui';
	if ( !isset( $clicface_organi_settings['organi_display_email'] ) )					$clicface_organi_settings['organi_display_email'] = 'non';
	if ( !isset( $clicface_organi_settings['vignette_min_height'] ) )					$clicface_organi_settings['vignette_min_height'] = 230;
	if ( !isset( $clicface_organi_settings['vignette_min_width'] ) )					$clicface_organi_settings['vignette_min_width'] = 160;
	if ( !isset( $clicface_organi_settings['organi_line_color'] ) )						$clicface_organi_settings['organi_line_color'] = '#3388DD';
	
	update_option( 'clicface_organi_settings', clicface_organi_settings_validate($clicface_organi_settings) );
}