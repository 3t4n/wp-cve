<?php
/**
 * Apple podcast page link.
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
?>

<?php Markup_Fn::the_icon( array( 'icon' => 'pp-iheartradio' ) ); ?>
<span class="sub-text">
	<span class="sub-listen-text" style="font-size: 9px; text-transform: uppercase; font-weight: bold; margin-left: 10px;"><?php esc_html_e( 'Listen On', 'podcast-player' ); ?></span>
	<span class="sub-item-text">
		<span><?php esc_html_e( 'iHeart ', 'podcast-player' ); ?></span>
		<span style="margin-left: -3px;font-weight: normal;"><?php esc_html_e( 'RADIO', 'podcast-player' ); ?></span>
	</span>
</span>
