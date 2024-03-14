<?php
// Don't allow direct execution
defined( 'ABSPATH' ) or die ( 'Forbidden' );

function wp247_body_classes_admin_sections() {
	global $wp247_mobile_detect;
	$sections = array(
		array(
			'id' => 'wp247_body_classes_mobile',
			'title' => __( 'Mobile Classes', 'wp247-body-classes' ),
			'desc' => __( 'Mobile classes are based on the results from the <a href="http://mobiledetect.net/" target="_blank">Mobile_Detect</a> script by Serban Ghita, Nick Ilyin, and Victor Stanciu. This script parses the value passed by the browser in the HTTP_USER_AGENT string. Consequently, mobile detection is more of an art than a science and, unfortunately, is not perfect.<br /><br />Check the Body Classes that you want to be included on your web pages.' /* testing */ /* . '<pre>' . var_export( get_option( 'wp247_body_classes_mobile' ), true ) . '</pre>' /* end testing */, 'wp247-body-classes' )
		),
		array(
			'id' => 'wp247_body_classes_environment',
			'title' => __( 'Environment Classes', 'wp247-body-classes' ),
			'desc' => __( 'Check the Body Classes that you want to be included on your web pages.', 'wp247-body-classes' )
		),
		array(
			'id' => 'wp247_body_classes_user',
			'title' => __( 'User Classes', 'wp247-body-classes' ),
			'desc' => __( 'Check the Body Classes that you want to be included on your web pages.', 'wp247-body-classes' )
		),
		array(
			'id' => 'wp247_body_classes_archive',
			'title' => __( 'Archive Classes', 'wp247-body-classes' ),
			'desc' => __( 'Check the Body Classes that you want to be included on your web pages.', 'wp247-body-classes' )
		),
		array(
			'id' => 'wp247_body_classes_post',
			'title' => __( 'Post Classes', 'wp247-body-classes' ),
			'desc' => __( 'Check the Body Classes that you want to be included on your web pages.', 'wp247-body-classes' )
		),
		array(
			'id' => 'wp247_body_classes_scroll',
			'title' => __( 'Scroll Classes', 'wp247-body-classes' ),
			'desc' => __( 'Check the Body Classes that you want to be included on your web pages and then select the appropriate options.<p>There are three types of <b>Scroll by</b> Body Classes:</p><p style="padding-left: 24px;"><b>Scroll by Pixel</b> Body Classes perform scroll measurement in pixels. All <b>Scroll by Pixel</b> Body Classes have a <b>-px</b> suffix at the end of the class name.</p><p style="padding-left: 24px;"><b>Scroll by Viewport Height</b> Body Classes perform scroll measurement in percent of Viewport Height. All <b>Scroll by Viewport Height</b> Body Classes have a <b>-vh</b> suffix at the end of the class name.</p><p style="padding-left: 24px;"><b>Scroll by Document Height</b> Body Classes perform scroll measurement in percent of Document Height. All <b>Scroll by Document Height</b> Body Classes have a <b>-dh</b> suffix at the end of the class name.</p><p>Each of the three "<b>Scroll by</b>" Body Classes can set any of the following class names where ? is the appropriate suffix described above.</p><p style="padding-left: 24px;"><b>is-scroll-top-?</b> indicates that scrolling has not reached the first scroll increment.</p><p style="padding-left: 24px;"><b>is-scroll-mid-?</b> indicates that scrolling has reached the first scroll increment but has not reached the scroll limit.</p><p style="padding-left: 24px;"><b>is-scroll-##-?</b> indicates that scrolling has scrolled ## (pixels or percent of viewport height or percent of document height) when this value is between the scroll increment and the scroll limit.</p><p style="padding-left: 24px;"><b>is-scroll-max-?</b> indicates that scrolling has reached or is past the scroll limit.</p>', 'wp247-body-classes' )
		),
		array(
			'id' => 'wp247_body_classes_custom',
			'title' => __( 'Custom Classes', 'wp247-body-classes' ),
			'desc' => __( 'Not enough Body Classes for you? It\'s unlikely that we thought of everything. So use this section to create your own custom Body Classes.', 'wp247-body-classes' )
		),
		array(
			'id' => 'wp247_body_classes_css',
			'title' => __( 'Custom CSS', 'wp247-body-classes' ),
			'desc' => __( 'Add the Custom CSS styling that you want to be included on your web pages.', 'wp247-body-classes' )
		),
	);
	return $sections;
}
?>