<?php
/**
 * Podcast player playpause button.
 *
 * This template can be overridden by copying it to yourtheme/podcast-player/misc/buttons/single-close.php.
 *
 * HOWEVER, on occasion Podcast Player will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Podcast Player
 * @version 1.0.0
 * @since   3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Podcast_Player\Helper\Functions\Markup as Markup_Fn;
?>

<button class="episode-single__close" aria-expanded="false" aria-label="<?php esc_html_e( 'Close Single Episode', 'podcast-player' ); ?>">
	<span class="btn-icon-wrap">
		<?php Markup_Fn::the_icon( array( 'icon' => 'pp-x' ) ); ?>
	</span>
</button>
