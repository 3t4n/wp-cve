<?php
/**
 * @package Show_Posts
 * @author Niraj Shah
 * @version 0.1
 */

/*
Plugin Name: Show Post Shortcode
Plugin URI: https://www.webniraj.com/2010/05/07/show-post-shortcode-plugin/
Description: Shortcode to display posts in a category: <code>[showposts category="2" num="4"]</code>. You can also use parameters <code>order</code> and <code>orderby</code>.
Author: Niraj Shah
Version: 0.1
Author URI: http://twitter.com/WebNiraj
*/

function showMyPosts( $atts )
{

	extract( shortcode_atts( array(
		'category' => '',
		'num' => '5',
		'order' => 'ASC',
		'orderby' => 'date',
	), $atts) );

	$out = '';

	$query = array();

	if ( $category != '' )
		$query[] = 'category=' . $category;

	if ( $num )
		$query[] = 'numberposts=' . $num;

	if ( $order )
		$query[] = 'order=' . $order;

	if ( $orderby )
		$query[] = 'orderby=' . $orderby;

	$posts_to_show = get_posts( implode( '&', $query ) );

	$out = '<ul>';

	foreach ($posts_to_show as $post_to_show) {
		$permalink = get_permalink( $post_to_show->ID );
		$out .= <<<HTML
		<li>
			<a href ="{$permalink}" title="{$post_to_show->post_title}">{$post_to_show->post_title}</a>
		</li>
HTML;
	}

	$out .= '</ul>';

    return $out;
}

add_shortcode('showposts', 'showMyPosts');
