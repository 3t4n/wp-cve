<?php
/**
 * Social
 *
 * @package YITH WooCommerce Popup
 * @since   1.0.0
 * @author  YITH <plugins@yithemes.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


global $post;

if ( empty( $socials ) ) {
	return;
}

echo "<div class='share-container'>";

foreach ( $socials as $social => $values ) {
	$social_icon = $social;
	if ( empty( $values['url'] ) ) {
		$title_soc = rawurlencode( get_the_title() );
		$permalink = rawurlencode( get_permalink() );
		$excerpt   = get_the_excerpt();
	} else {
		$title_soc = get_bloginfo( 'name' );
		$permalink = urldecode( $values['url'] );
		$excerpt   = get_bloginfo( 'description' );
	}


	if ( 'facebook' === $social ) {
		$url = apply_filters( 'ypop_share_facebook', 'https://www.facebook.com/sharer.php?u=' . $permalink . '&t=' . $title_soc . '' );

	} elseif ( 'twitter' === $social ) {
		$url = apply_filters( 'ypop_share_twitter', 'https://twitter.com/share?url=' . $permalink . '&amp;text=' . $title_soc . '' );
	} elseif ( 'pinterest' === $social ) {
		$src   = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
		$url   = apply_filters( 'ypop_share_pinterest', 'http://pinterest.com/pin/create/button/?url=' . $permalink . '&amp;media=' . $src[0] . '&amp;description=' . wp_strip_all_tags( $excerpt ) );
		$attrs = ' onclick="window.open(this.href); return false;';
	} elseif ( 'linkedin' === $social ) {
		$url = 'http://www.linkedin.com/shareArticle?mini=true&url=' . $permalink . '&title=' . $title_soc . '&summary=' . $excerpt;
	}

	?>
	<?php if ( yith_plugin_fw_is_true( $icon ) ) : ?>

		<a href="<?php echo esc_url( $url ); ?>" class="link_socials" title="<?php echo esc_attr( $social ); ?>" target="_blank">
			<span class="icon-circle">
					<i class="fa fa-<?php echo esc_attr( $social_icon ); ?>"></i>
			</span>
		</a>

	<?php else : ?>
		<div class="socials-text">
			<a href="<?php echo esc_url( $url ); ?>" class="link-<?php echo esc_attr( $social ); ?>" target="_blank">
				<?php echo wp_kses_post( $social ); ?>
			</a>
		</div>

	<?php endif ?>

	<?php

}
echo '</div>';
