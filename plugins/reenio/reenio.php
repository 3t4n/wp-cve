<?php
/**
 * Plugin Name:       reenio
 * Plugin URI:        https://wordpress.org/plugins/reenio/
 * Description:       Plug-in for embedding of reservation system reenio into the web presentation.
 * Version:           1.6
 * Author:            reenio
 * Author URI:        https://reenio.cz/
 * Text Domain:       reenio
 * Domain Path:       /languages
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

// load languages
function wt_reenio_load_textdomain() {
  load_plugin_textdomain( 'reenio', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'wt_reenio_load_textdomain' );

// option page for reenio plugin
require_once( plugin_dir_path( __FILE__ ) . 'reenio-option-page.php' );

// shortcode for reenio
// format [reenio id="key" lang="cs" type="button" name="button text"], "lang", "type" and "name" parameters are optional, only [reenio id="key"] is required
if ( !function_exists( 'wt_reenio' ) ) {

  function wt_reenio( $atts ) {

    // default options
    $atts = shortcode_atts(
      array(
        'id' => '',
        'lang' => 'cs',
        'type' => '',
        'name' => 'Reservation'
      ),
      $atts,
      'reenio'
    );

    $content = '';

    // sanitize data
    $reenio_id = sanitize_text_field( $atts['id'] );
    $reenio_lang = sanitize_text_field( $atts['lang'] );
    $reenio_type = sanitize_text_field( $atts['type'] );
    $reenio_button = sanitize_text_field( $atts['name'] );

    if ( $atts['type'] == 'button' ) {
      // reservation - button
      $content .= '<a href="https://reenio.cz/'.$reenio_lang.'/redirect/subjectpage/'.$reenio_id.'" class="reenio-reservation-btn" target="_blank" data-no-dialog="1" style="color: #fff; background-color: #f05033; border-radius: 4px; font-family: Arial, Helvetica, sans-serif; font-weight: bold; display: inline-block; padding: 6px 12px; text-decoration: none;">'.$reenio_button.'</a>';
      $content .= '<script src="https://reenio.cz/'.$reenio_lang.'/'.$reenio_id.'/widget-reservation-btn.js" async defer></script>';
    }
    else {
      // reservation - iframe
      $content .= '<div class="reenio-iframe" data-size="auto"></div>';
      $content .= '<script src="https://reenio.cz/'.$reenio_lang.'/'.$reenio_id.'/widget-iframe.js" async defer></script>';
    }

    return $content;

  }
  add_shortcode( 'reenio', 'wt_reenio' );

}

?>
