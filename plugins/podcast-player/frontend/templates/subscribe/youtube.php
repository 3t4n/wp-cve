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

<?php Markup_Fn::the_icon( array( 'icon' => 'pp-youtube' ) ); ?>
<span class="sub-text">
	<span class="sub-listen-text"><?php esc_html_e( 'Listen On', 'podcast-player' ); ?></span>
	<span class="sub-item-text"><?php esc_html_e( 'YouTube', 'podcast-player' ); ?></span>
</span>
