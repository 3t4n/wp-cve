<?php
/*
Plugin Name: remove links from comments
Plugin URI: http://fivera.net/remove-links-comments-free-wordpress-plugin
Description: Removes the url field in comments and url from  author box 

Author: Nikola fivera Petrovic
Email: info@fivera.net
Version: 0.1
Author URI: http://fivera.net
*/

add_filter('comment_form_default_fields', 'url_filtered');
function url_filtered($fields)
{
if(isset($fields['url']))
unset($fields['url']);
return $fields;
}

	if( !function_exists("disable_comment_author_links")){
		function disable_comment_author_links( $author_link ){
			return strip_tags( $author_link );
		}
		add_filter( 'get_comment_author_link', 'disable_comment_author_links' );
	}

?>