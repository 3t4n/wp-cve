<?php

/**
 *
 * WP Post Nav attachment display page.
 *
 * @link:      https://en-gb.wordpress.org/plugins/wp-post-nav/
 * @since      0.0.1
 *
 * @package    wp_post_nav
 * @subpackage wp_post_nav/public/partials
 */
?>

<?php 
// If this file is called directly, abort. //
if ( ! defined( 'ABSPATH' ) ) {
  exit;
} 

$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_previous_post();
 
//if there arent any previous links, leave
if ( !$previous) {
    return;
}

//Return To Post - lets do this
echo '<nav class="wp-post-nav" role="navigation">';
	$prev_link = previous_post_link( 
			'%link', 
			'<ul id="attachment-post-nav-previous'.$switch_nav.'">'
			.__( '<li id="wp-prev-nav">' . ___('Return To Post', 'wp-post-nav') .'</li>' )
			.'<span id="post-nav-previous-button"></span></ul>'
			, false, '' );
        
     echo $prev_link;           
echo '</nav>'; 

