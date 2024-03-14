<?php
/*
Plugin Name: HTML in Author Bio
Plugin URI: http://www.itsabhik.com/wp-plugins/allow-html-in-wordpress-author-bio.html
Description: The Plugin stops the stripping of html formatting from the description (bio) field and sanitize content for allowed HTML tags for post content. Once you put this up, you can go and use all the HTML formatting allowed in posts in author’s bio. Pluginized by <a href="http://www.itsabhik.com">Abhik</a>. Add me on Circles on <a href="https://plus.google.com/106671843900352433725?rel=author">Google +</a>.
Version: 1.0
Author: Abhik
Author URI: http://www.itsabhik.com
License: GPL2
*/

// Do NOT Remove This Line. This is to remove HTML stripping from Author Profile
remove_filter('pre_user_description', 'wp_filter_kses');
// Do NOT Remove This Line. This is to sanitize content for allowed HTML tags for post content
add_filter( 'pre_user_description', 'wp_filter_post_kses' );
?>