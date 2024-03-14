<?php
/**
 * Popup Preview Screen
 *
 * Handles the popup preview functionality of plugin
 *
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $paoc_preview;

// Tweak to hide query monitor output at bottom
define('IFRAME_REQUEST', true);

// Set Preview Flag
$paoc_preview = 1;

// Taking form post data
if( ! empty( $_POST['paoc_preview_form_data'] ) ) {
	parse_str( wp_unslash( $_POST['paoc_preview_form_data'] ), $form_data ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$_POST['paoc_preview_form_data'] = popupaoc_preview_data( $form_data );
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta http-equiv="Content-Type" content="text/html;" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php esc_html_e("Popup Anything On Click - Preview", 'popup-anything-on-click'); ?></title>

		<?php wp_head(); ?>
		<style type="text/css">
			html{overflow: auto;}
			body{background: #fff; overflow-x: hidden;}
			.paoc-customizer-container{padding:0 16px;}
			.paoc-customizer-container a[href^="http"]{cursor:not-allowed !important;}
			a:focus, a:active{box-shadow: none; outline: none;}
			.paoc-link-notice{display: none; position: fixed; color: #a94442; background-color: #f2dede; border:1px solid #ebccd1; max-width:400px; width: 100%; left:0; right:0; bottom:30%; margin:auto; padding:10px; text-align: center; z-index: 100005; line-height: normal;}
		</style>
	</head>
	<body>
		<div id="paoc-customizer-container" class="paoc-customizer-container"></div>
		<div class="paoc-link-notice"><?php esc_html_e('Sorry, Some of the actions like link visit, form submission and etc will not work in popup preview.', 'popup-anything-on-click'); ?></div>

		<script type="text/javascript">
		(function($) {

			"use strict";
			
			$(document).on('click', 'a', function(event) {

				var href_val = $(this).attr('href');

				if( href_val.indexOf('javascript:') < 0 ) {
					$('.paoc-link-notice').fadeIn();
				}
				event.preventDefault();

				setTimeout(function() {
					$(".paoc-link-notice").fadeOut('normal');
				}, 5000 );
			});

			/* Process Form Field Submission */
			$(document).on('submit', 'form', function(e) {

				$('.paoc-link-notice').fadeIn();
				setTimeout(function() {
					$(".paoc-link-notice").fadeOut('normal');
				}, 5000 );

				return false;
			});
		})(jQuery);
		</script>

		<?php wp_footer(); ?>
	</body>
</html>