<?php
/**
 * This file contains the javascript code to handle admin notice related functions.
 */

?>
jQuery(document).ready(function() {
	jQuery('.quick_adsense_adstxt_adsense_notice').on('click', '.notice-dismiss', function() {
		wp.ajax.post('quick_adsense_adstxt_adsense_admin_notice_dismiss', {
			nonce: quick_adsense_adstxt_adsense.nonce,
		});
	});
});
