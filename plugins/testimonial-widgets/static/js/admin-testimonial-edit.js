jQuery(document).ready(function () {
	// Put notices before the custom menu menu
	setTimeout(function (){
		jQuery('.notice').insertBefore('#testimonial-widgets-plugin-settings-page');
	}, 0);

	let wp_testimonials_current_location = location.href;
	if (wp_testimonials_current_location.includes("post.php") || wp_testimonials_current_location.includes("post-new.php"))
	{
		// Change image position to 2 (after submit)
		jQuery('#postimagediv').insertAfter('#submitdiv');
		let wp_testimonials_post_type = document.getElementById("post_type").value;
		if (wp_testimonials_post_type === "wpt-testimonial") 
		{
			// Add Back to list button
			jQuery('<a href="edit.php?post_type=wpt-testimonial" class="page-title-action">Back to list</a>').insertBefore(".wp-header-end");
		}
	}
});