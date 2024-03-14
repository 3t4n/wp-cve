<?php
/**
 * The template for displaying videos in a video tag.
 *
 * This template can be overridden by copying it to your-theme/vimeotheque/taxonomy-video-tag.php
 *
 * @version 1.0
 */
if( !defined( 'ABSPATH' ) ){
	exit; // exit if accessed directly
}

/*
* Include the post format-specific template for the content.
*/
vimeotheque_get_template_part( 'archive', 'vimeo-video' );
