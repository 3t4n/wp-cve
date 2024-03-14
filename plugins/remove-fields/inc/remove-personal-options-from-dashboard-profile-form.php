<?php

/**
 * Hide Personal Options section:
 * - Visual Editor
 * - Admin Color Scheme
 * - Keyboard Shortcuts
 * - Toolbar 
 * - Admin Language
 */
add_action('admin_head','hide_personal_options');
function hide_personal_options(){
	?><script>
		jQuery(document).ready(function($) {
			$('form#your-profile > h3:first').remove(); 
			$('form#your-profile > table:first').remove(); 
			$('form#your-profile').show(); 
		});
	</script><?php
}
