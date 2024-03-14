<?php

/**********************************************************************
This installs a fake form on the top of every page as an HTML comment
**********************************************************************/

function astound_install_redherring() {
	add_filter( 'the_content', 'astound_redherring' ); 
}

function astound_redherring($content) {
	// this is the first content from a page.
	remove_filter('the_content', 'astound_redherring' );
	$rhnonce=wp_create_nonce('astound_comment_nonce');
	$loginurl = wp_registration_url();
	$tid=@get_the_ID();
	$commenturl=site_url( '/wp-comments-post.php' );
	// these are the forms - add them to the top of the content of the first thing on the page.
	$cc="
	<!-- 
	Astounding forms added to top of page
	<br>
	<br>
	<form action=\"$commenturl\" method=\"post\" id=\"commentform1\">
	<p><input name=\"author\" id=\"author\" value=\"\" size=\"22\"  aria-required=\"true\" type=\"text\">
	<label for=\"author\"><small>Name (required)</small></label></p>

	<p><input name=\"email\" id=\"email\" value=\"\" size=\"22\"  aria-required=\"true\" type=\"text\">
	<label for=\"email\"><small>Mail (will not be published) (required)</small></label></p>

	<p><input name=\"url\" id=\"url\" value=\"\" size=\"22\" type=\"text\">
	<label for=\"url\"><small>Website</small></label></p>
	<p><textarea name=\"comment\" id=\"comment\" cols=\"58\" rows=\"10\" ></textarea></p>

	<p>
	<input name=\"comment_post_ID\" value=\"$tid\" id=\"comment_post_ID\" type=\"hidden\">
	<input name=\"comment_parent\" id=\"comment_parent\" value=\"0\" type=\"hidden\">
	</p>

	<p><input id=\"astound_comment_nonce\" name=\"astound_comment_nonce\" value=\"$rhnonce\" type=\"hidden\"></p>
	</form>

<form name=\"registerform\" id=\"registerform\" action=\"$loginurl\" method=\"post\" novalidate=\"novalidate\">
	<p>
		<label for=\"user_login\">Username<br>
		<input name=\"user_login\" id=\"user_login\" class=\"input\" value=\"\" size=\"20\" type=\"text\"></label>
	</p>
	<p>
		<label for=\"user_email\">Email<br>
		<input name=\"user_email\" id=\"user_email\" class=\"input\" value=\"\" size=\"25\" type=\"email\"></label>
	</p>
		<p id=\"reg_passmail\">Registration confirmation will be emailed to you.</p>
	<br class=\"clear\">
	<input name=\"redirect_to\" value=\"\" type=\"hidden\">
	<p class=\"submit\"><input name=\"wp-submit\" id=\"wp-submit\" class=\"button button-primary 	<p><input id=\"astound_comment_nonce\" name=\"astound_comment_nonce\" value=\"$rhnonce\" button-large\" value=\"Register\" type=\"submit\"></p>
</form>

	-->
	";
	return $cc.$content;
}


?>
