<?php
/**
 * Podcast pod entry for episode entry list.
 *
 * This template can be overridden by copying it to yourtheme/podcast-player/header/subscribe-buttons.php.
 *
 * HOWEVER, on occasion Podcast Player will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Podcast Player
 * @version 1.0.0
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Podcast_Player\Helper\Functions\Markup as Markup_Fn;
use Podcast_Player\Helper\Functions\Getters as Get_Fn;

$pp_sub_links = array(
	$this->args['apple-sub'],
	$this->args['google-sub'],
	$this->args['spotify-sub'],
	$this->args['breaker-sub'],
	$this->args['castbox-sub'],
	$this->args['castro-sub'],
	$this->args['iheart-sub'],
	$this->args['amazon-sub'],
	$this->args['overcast-sub'],
	$this->args['pocketcasts-sub'],
	$this->args['podcastaddict-sub'],
	$this->args['podchaser-sub'],
	$this->args['radiopublic-sub'],
	$this->args['soundcloud-sub'],
	$this->args['stitcher-sub'],
	$this->args['tunein-sub'],
	$this->args['youtube-sub'],
	$this->args['bullhorn-sub'],
	$this->args['podbean-sub'],
	$this->args['playerfm-sub'],
);

$pp_sub_markup = '';

foreach ( $pp_sub_links as $pp_link ) {
	if ( ! $pp_link ) {
		continue;
	}

	$service = Get_Fn::get_podcast_service( $pp_link );
	if ( $service ) {
		$pp_sub_markup .= sprintf(
			'<a href="%1$s" class="subscribe-item pp-badge %2$s-sub" target="_blank">%3$s</a>',
			esc_attr( esc_url( $pp_link ) ),
			esc_attr( $service ),
			Markup_Fn::get_template_markup( 'subscribe', $service )
		);
	}
}

echo $pp_sub_markup;
