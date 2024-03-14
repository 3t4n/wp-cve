<?php
/**
 * This file contains the javascript code for stat collection related functions.
 */

?>
jQuery(document).ready(function() {
	jQuery(".<?php echo esc_js( quick_adsense_get_value( $args, 'target' ) ); ?>").click(function() {
		jQuery.post(
			"<?php echo esc_js( quick_adsense_get_value( $args, 'ajax_url' ) ); ?>", {
				"action": "quick_adsense_onpost_ad_click",
				"quick_adsense_onpost_ad_index": jQuery(this).attr("data-index"),
				"quick_adsense_nonce": "<?php echo esc_js( quick_adsense_get_value( $args, 'nonce' ) ); ?>",
			}, function(response) { }
		);
	});
});
