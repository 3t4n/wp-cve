<?php
/**
 * Template for Timeline and History Slider Pro - Design 1
 *
 *
 * @package Timeline and History Slider
 * @version 1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post; ?>

<div id="wpostahs-slider-nav-<?php echo esc_attr($unique); ?>" class="wpostahs-slider-nav-<?php echo esc_attr($unique); ?> wpostahs-slider-nav wpostahs-slick-slider" <?php echo $slider_as_nav_for; ?>>
	<?php while ( $query->have_posts() ) : $query->the_post();
		$feat_orig_img	= wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
		$feat_image 	= $feat_orig_img;

		if ( $lazyload ) {
			$feat_image = WPOSTAHS_URL .'assets/images/spacer.gif';
		}
		?>
		<div class="wpostahs-slider-nav-title">
			<div class="wpostahs-main-title">
			<?php if( ! empty( $feat_orig_img ) ) { ?>
				<img <?php if( $lazyload ) { ?>data-lazy="<?php echo esc_url( $feat_orig_img ); ?>"<?php } ?> src="<?php echo esc_url( $feat_image ); ?>" alt="<?php the_title_attribute(); ?>">
			<?php } echo the_title(); ?>
			</div>
		</div>
	<?php endwhile; ?>
</div>

<div class="wpostahs-slider-for-<?php echo esc_attr($unique); ?> wpostahs-slider-for wpostahs-slick-slider">
	<?php while ( $query->have_posts() ) : $query->the_post(); ?>
		<div class="wpostahs-slider-nav-content">
			<h2 class="wpostahs-centent-title"><?php echo the_title(); ?></h2>
			<div class="wpostahs-centent">
				<?php echo the_content(); ?>
			</div>
		</div>
	<?php endwhile; ?>
</div><!-- #post-## -->