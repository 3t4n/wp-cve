<?php
/**
 * Podcast episode list search field.
 *
 * This template can be overridden by copying it to yourtheme/podcast-player/list/search-field.php.
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

<div class="episode-list__search">
	<label class="label-episode-search">
		<span class="ppjs__offscreen"><?php esc_attr_e( 'Search Episodes', 'podcast-player' ); ?></span>
		<input type="text" placeholder="<?php esc_attr_e( 'Search Episodes', 'podcast-player' ); ?>" title="<?php esc_attr_e( 'Search Podcast Episodes', 'podcast-player' ); ?>"/>
	</label>
	<span class="episode-list__search-icon">
		<?php Markup_Fn::the_icon( array( 'icon' => 'pp-search' ) ); ?>
	</span>
</div>
