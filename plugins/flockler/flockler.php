<?php

/**
 * @link              https://flockler.com
 * @since             1.0.3
 * @package           Flockler
 *
 * @wordpress-plugin
 * Plugin Name:       Flockler
 * Plugin URI:        https://github.com/flockler/flockler-wordress-plugin
 * Description:       Flockler helps marketers and website managers to gather and display social media feeds from Instagram, Facebook, Twitter, YouTube, and more.
 * Version:           1.0.3
 * Author:            Flockler
 * Author URI:        http://flockler.com/
 * License:           GPLv2 or later
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'FLOCKLER_VERSION', '1.0.3' );

add_shortcode( 'flockler', 'flockler_inject_html' );

function flockler_html_for_default_embed_code( $site_uuid, $embed_uuid ) {
  wp_enqueue_script(
    "plugins.flockler.com/embed/$site_uuid/$embed_uuid",
    "https://plugins.flockler.com/embed/$site_uuid/$embed_uuid",
    [],
    null,
    true
  );

  return <<<FLOCKLER_EMBED
    <div id="flockler-embed-$embed_uuid" data-via-shortcode="true"></div>
FLOCKLER_EMBED;
}

function flockler_html_for_iframe_embed_code( $site_uuid, $embed_uuid ) {
  wp_enqueue_script(
    'flockler-iframe-resize-listener',
    'https://fl-1.cdn.flockler.com/embed/flockler-iframe-resize-listener.js',
    [],
    null,
    false
  );

  return <<<FLOCKLER_IFRAME_EMBED
    <iframe src="https://plugins.flockler.com/embed/preview/$site_uuid/$embed_uuid?resize_events=true"
            id="flockler-embed-iframe-$embed_uuid"
            style="display: block; border: none; width: 100%;"
            data-via-shortcode="true"
    ></iframe>
FLOCKLER_IFRAME_EMBED;
}

function flockler_inject_html( $atts ) {
  $site_uuid = esc_attr( $atts['site_uuid'] );
  $embed_uuid = esc_attr( $atts['embed_uuid'] );

  if (isset( $atts['iframe'] ) && $atts['iframe'] === 'true') {
    return flockler_html_for_iframe_embed_code($site_uuid, $embed_uuid);
  } else {
    return flockler_html_for_default_embed_code($site_uuid, $embed_uuid);
  }
}
