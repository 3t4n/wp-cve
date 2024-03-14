<?php
/**
 * BuddyPress Birthdays
 * Core Functions
 *
 * @package BP_Birthdays/assets/inc
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;} // end if.

/**
 * Custom Front End Ajax Scripts / Loads In WP Footer
 */
function bb_frontend_ajax_form_scripts() {
	?>
<script type="text/javascript">
jQuery(document).ready(function($){
	"use strict";
	// add basic front-end ajax page scripts here
	$('#bb_custom_plugin_form').submit(function(event){
		event.preventDefault();
		// Vars
		var myInputFieldValue = $('#myInputField').val();
		// Ajaxify the Form
		var data = {
			'action': 'bb_custom_plugin_frontend_ajax',
			'myInputFieldValue':   myInputFieldValue,
		};
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		var ajaxurl = "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>";
		$.post(ajaxurl, data, function(response) {
				console.log(response);
				if(response.Status == true)
				{
					console.log(response.message);
					$('#bb_custom_plugin_form_wrap').html(response);

				}
				else
				{
					console.log(response.message);
					$('#bb_custom_plugin_form_wrap').html(response);
				}
		});
	});
}(jQuery));    
</script>
	<?php
}
add_action( 'wp_footer', 'bb_frontend_ajax_form_scripts' );
