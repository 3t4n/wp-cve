<?php
// This page is displayed at WP Admin > WP Courses > All Students > Student XYZ
// This page is NOT displayed via the shortcode [wpc_profile]
?>

<div id="wpc-profile-page"></div>

<script>
jQuery(document).ready(function($){
	var userID = <?php echo (int) $_GET['student_id']; ?>;

	// Used to overwrite window.wpcd.user.ID etc. in ui.js
	// And to load the profile window
	new WPC_UI({
		loggedIn 				: true,
		userID 					: userID,
		onLoad 					: false,
		ajaxLinks 				: false,
	});
});
</script>