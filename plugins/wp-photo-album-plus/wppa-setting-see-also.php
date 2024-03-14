<?php
/* wppa-setting-see-also.php
* Package: wp-photo-album-plus
*
* manage all options
* Version 8.4.04.001
*
*/

global $wppa_tab_names;
global $wppa_subtab_names;

	// The header tabs
	$wppa_tab_names = array(
		'general' 		=> 	__( 'General', 'wp-photo-album-plus' ),
		'generaladv' 	=> 	__( 'General', 'wp-photo-album-plus' ),
		'layout' 		=> 	__( 'Layout', 'wp-photo-album-plus' ),
		'covers' 		=> 	__( 'Albums', 'wp-photo-album-plus' ),
		'photos' 		=>	__( 'Photos', 'wp-photo-album-plus' ),
		'thumbs' 		=> 	__( 'Thumbnails', 'wp-photo-album-plus' ),
		'slide' 		=>	__( 'Slideshow', 'wp-photo-album-plus' ),
		'lightbox' 		=>	__( 'Lightbox', 'wp-photo-album-plus' ),
		'comments' 		=>	__( 'Comments', 'wp-photo-album-plus' ),
		'rating' 		=> 	__( 'Rating', 'wp-photo-album-plus' ),
		'search' 		=> 	__( 'Search', 'wp-photo-album-plus' ),
		'widget' 		=> 	__( 'Widgets', 'wp-photo-album-plus' ),
		'links' 		=>	__( 'Links', 'wp-photo-album-plus' ),
		'users' 		=>	__( 'Users', 'wp-photo-album-plus' ),
		'email' 		=> 	__( 'Email', 'wp-photo-album-plus' ),
		'share' 		=>	__( 'Share', 'wp-photo-album-plus' ),
		'system' 		=> 	__( 'System', 'wp-photo-album-plus' ),
		'files'			=> 	__( 'Files', 'wp-photo-album-plus' ),
		'new' 			=> 	__( 'New', 'wp-photo-album-plus' ),
		'admin' 		=>	__( 'Admin', 'wp-photo-album-plus' ),
		'maintenance' 	=> 	__( 'Maintenance', 'wp-photo-album-plus' ),
		'exif' 			=> 	'EXIF',
		'iptc' 			=> 	'IPTC',
		'gpx' 			=> 	'GPX',
		'watermark' 	=>	__( 'Watermark', 'wp-photo-album-plus' ),
		'custom' 		=> 	__( 'Custom data', 'wp-photo-album-plus' ),
		'constants' 	=>	__( 'Constants', 'wp-photo-album-plus' ),
		'misc' 			=> 	__( 'Misc', 'wp-photo-album-plus' ),
		'miscadv' 		=> 	__( 'Misc', 'wp-photo-album-plus' ),
	);
	$wppa_subtab_names = array(
		'general' 		=> array(
			'1' => __( 'Features', 'wp-photo-album-plus' ),
			),
		'generaladv' 	=> array(
			'1' => __( 'Features', 'wp-photo-album-plus' ),
			),
		'layout' 		=> array(
			'1' => __( 'General layout settings', 'wp-photo-album-plus' ),
			'2' => __( 'Breadcrumb specifications', 'wp-photo-album-plus' ),
			'3' => __( 'Navigation symbol specifications', 'wp-photo-album-plus' ),
			'4' => __( 'Multimedia icon and stubfile specifications', 'wp-photo-album-plus' ),
			'5' => __( 'Fonts', 'wp-photo-album-plus' ),
			'6' => __( 'Audio only specifications', 'wp-photo-album-plus' ),
			),
		'covers' 		=> array(
			'1' => __( 'Album cover size specifications', 'wp-photo-album-plus' ),
			'2' => __( 'Album cover options', 'wp-photo-album-plus' ),
			'3' => __( 'Album cover layout settings', 'wp-photo-album-plus' ),
			),
		'photos' 		=> array(
			'1' => __( 'Photo specifications', 'wp-photo-album-plus' ),
			'2' => __( 'Shortcode [photo ... ] specifications', 'wp-photo-album-plus' ),
			'3' => __( 'Photo of the day settings', 'wp-photo-album-plus' ),
			),
		'thumbs' 		=> array(
			'1' => __( 'Thumbnail size specifications', 'wp-photo-album-plus' ),
			'2' => __( 'Thumbnail display options', 'wp-photo-album-plus' ),
			'3' => __( 'Thumbnail layout settings', 'wp-photo-album-plus' ),
			),
		'slide' 		=> array(
			'1' => __( 'Slideshow component specifications', 'wp-photo-album-plus' ),
			'2' => __( 'Slideshow component sequence', 'wp-photo-album-plus' ),
			'3' => __( 'Slideshow layout settings', 'wp-photo-album-plus' ),
			'4' => __( 'Slideshow dynamic behaviour', 'wp-photo-album-plus' ),
			'5' => __( 'Filmstrip settings', 'wp-photo-album-plus' ),
			),
		'lightbox' 		=> array(
			'1' => __( 'Lightbox overlay configuration settings', 'wp-photo-album-plus' ),
			),
		'comments' 		=> array(
			'1' => __( 'Comments system related settings', 'wp-photo-album-plus' ),
			),
		'rating' 		=> array(
			'1' => __( 'Rating system related settings', 'wp-photo-album-plus' ),
			),
		'search' 		=> array(
			'1' => __( 'Search albums and photos features related settings', 'wp-photo-album-plus' ),
			),
		'widget' 		=> array(
			'1' => __( 'General widget size settings', 'wp-photo-album-plus' ),
			'2' => __( 'Visibility settings', 'wp-photo-album-plus' ),
			'3' => __( 'QR Code widget settings', 'wp-photo-album-plus' ),
			),
		'links' 		=> array(
			'1' => __( 'System Links configuration', 'wp-photo-album-plus' ),
			'2' => __( 'Links from standard images', 'wp-photo-album-plus' ),
			'3' => __( 'Links from items and images in widgets', 'wp-photo-album-plus' ),
			'4' => __( 'Frontend download links', 'wp-photo-album-plus' ),
			'5' => __( 'Other links', 'wp-photo-album-plus' ),
			),
		'users' 		=> array(
			'1' => __( 'Frontend (user) upload related settings', 'wp-photo-album-plus' ),
			),
		'email' 		=> array(
			'1' => __( 'Email configuration settings', 'wp-photo-album-plus' ),
			'2' => __( 'Failed mails', 'wp-photo-album-plus' ),
			'3' => __( 'Permanently failed mails', 'wp-photo-album-plus' ),
			),
		'share' 		=> array(
			'1' => __( 'Social media related settings', 'wp-photo-album-plus' ),
			'2' => __( 'Search Engine Optimazation settings', 'wp-photo-album-plus' ),
			),
		'system' 		=> array(
			'1' => __( 'System behaviour related settings' , 'wp-photo-album-plus' ),
			),
		'files'			=> array(
			'1' => __( 'Original source file related settings', 'wp-photo-album-plus' ),
			),
		'new' 			=> array(
			'1' => __( 'New albums / photos related settings', 'wp-photo-album-plus' ),
			),
		'admin' 		=> array(
			'1' => __( 'WPPA+ related roles and capabilities', 'wp-photo-album-plus' ),
			'2' => __( 'Frontend create albums and upload photos enabling and limiting settings' , 'wp-photo-album-plus' ),
			'3' => __( 'Import related settings', 'wp-photo-album-plus' ),
			'4' => __( 'Admin Functionality restrictions for non administrators' , 'wp-photo-album-plus' ),
			'5' => __( 'Miscellaneous limiting settings' , 'wp-photo-album-plus' ),
			'6' => __( 'Miscellaneous admin related settings', 'wp-photo-album-plus' ),
			'7' => __( 'Optional menu items', 'wp-photo-album-plus' ),
			),
		'maintenance' 	=> array(
			'1' => __( 'Regular maintenance procedures', 'wp-photo-album-plus' ),
			'2' => __( 'Clearing and other irreversable maintenance procedures', 'wp-photo-album-plus' ),
			'3' => __( 'One time conversions', 'wp-photo-album-plus' ),
			),
		'exif' 			=> array(
			'1' => __( 'EXIF tags and their labels as found in the uploaded photos', 'wp-photo-album-plus' ),
			),
		'iptc' 			=> array(
			'1' => __( 'IPTC tags and their labels as found in the uploaded photos', 'wp-photo-album-plus' ),
			),
		'gpx' 			=> array(
			'1' => __( 'GPX configuration', 'wp-photo-album-plus' ),
			),
		'custom' 		=> array(
			'1' => __( 'Album custom data fields configuration', 'wp-photo-album-plus' ),
			'2' => __( 'Photo custom data fields configuration', 'wp-photo-album-plus' ),
			),
		'watermark' 	=> array(
			'1' => __( 'Watermark related settings', 'wp-photo-album-plus' ),
			),
		'constants' 	=> array(
			'1' => __( 'System constants (read only)', 'wp-photo-album-plus' ),
			),
		'misc' 			=> array(
			'1' => __( 'Miscellaneous settings', 'wp-photo-album-plus' ),
			'2' => __( 'Panorama related settings', 'wp-photo-album-plus' ),
			),
		'miscadv' 		=> array(
			'1' => __( 'Advanced miscellaneous settings', 'wp-photo-album-plus' ),
			'2' => __( 'Logging', 'wp-photo-album-plus' ),
			'3' => __( 'External services related settings and actions', 'wp-photo-album-plus' ),
			'4' => __( 'Other plugins related settings', 'wp-photo-album-plus' ),
			),
	);

// Setting pathinfo
function wppa_setting_path( $b_a, $tab, $subtab = '', $item = '', $default = '' ) {
global $wppa_tab_names;
global $wppa_subtab_names;

	$greek = array('0', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X');

	$result = ( $b_a == 'b' ? __( 'Basic settings', 'wp-photo-album-plus' ) : __( 'Advanced settings', 'wp-photo-album-plus' ) ) . ' -&gt;  ';

	// Subtab given?
	if ( ! $subtab ) {
		$result .= $wppa_tab_names[$tab];
	}

	// Yes, subtab
	else {

		// Item given?
		if ( ! $item ) {
			$result .= $wppa_tab_names[$tab] . ' -&gt; ' . $greek[$subtab] . ': ' . $wppa_subtab_names[$tab][$subtab];
		}

		// Yes
		else {
			if ( is_array( $item ) ) {
				$count = count( $item );
				$disp = implode( ', ', $item );
			}
			else {
				$count = '1';
				$disp = $item;
			}
			$itemlabel = _n( 'Item', 'Items', $count, 'wp-photo-album-plus' );

			$result .= $wppa_tab_names[$tab] . ' -&gt; ' . $greek[$subtab] . ': ' . $wppa_subtab_names[$tab][$subtab] . ' -&gt; ' . $itemlabel . ': ' . $disp;

			// Default given?
			if ( $default ) {
				$result .= ' (' . $default . ')';
			}
		}
	}
	return $result;
}

// See also
function wppa_see_also( $tab, $subtab = '', $items = '', $switch = '', $value = '', $error = false ) {
global $wppa_tab_names;
global $wppa_subtab_names;

	$display = 'inline';

	// Do they need us?
	if ( $switch && ! $value ) {
		if ( ! wppa_switch( $switch ) ) {
			$display = 'none';
		}
	}
	if ( $switch && $value ) {
		if ( wppa_opt( $switch ) != $value ) {
			$display = 'none';
		}
	}

	// Make the full link
	$link = admin_url( 'admin.php' ) . '?page=wppa_options';
	if ( $tab ) {
		$link .= '&wppa-tab=' . sanitize_text_field( $tab );
	}
	if ( $subtab ) {
		$link .= '&wppa-subtab=' . strval( intval( $subtab ) );
	}
	if ( $items !== '' ) {
		$link .= '&wppa-item=' . sanitize_text_field( $items );
	}
	if ( $error ) {
		$link .= '&wppa-error=1';
	}
	$new_tab = wppa_get( 'page' ) != 'wppa_options';

	// Make the htnl
	$result = '
	<div
		style="display:' . $display . ';;"
		class="' . $switch . '" >
		&nbsp;' . __( 'See also', 'wp-photo-album-plus' ) . ':&nbsp;
		<a
			href="' . $link . '"
			title="' . esc_attr( 'Jump to related (sub)tab', 'wp-photo-album-plus' ) . '"' .
			( $new_tab ? ' target="_blank"' : '' ) . '
			style="cursor:pointer">' .
			$wppa_tab_names[$tab] . ' -&gt; ' . $wppa_subtab_names[$tab][$subtab] . '
		</a>&nbsp;
	</div>';

	return $result;
}
