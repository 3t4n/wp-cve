<?php

/**
 * WPB WooCommerce Category Slider Plugin
 *
 * Template file for category grid
 *
 * Author: WpBean
 */
	
?>

<?php 
	wp_enqueue_style('wpb-wcs-bootstrap-grid');

	extract($data->atts);
	$terms = $data->terms;
	$wpb_wcs_template_loader = new WPB_WCS_Template_Loader;
	$new_data = array( 'atts' => $data->atts );
?>

<?php do_action( 'wpb_wcs_before_slider' ); ?>

<div class="wpb-woo-cat-items wpb-wcs-category-type-<?php echo esc_attr( $type ); ?> wpb-wcs-content-type-<?php echo esc_attr( $content_type ); ?> row">

	<?php foreach ( $terms as $term ): ?>

		<?php do_action( 'wpb_wcs_before_slider_loop' ); ?>

			<div class="wpb-wcs-column <?php echo esc_attr( $data->loop_css_classes )?>">
				<?php
					$new_data['term'] = $term;
					$wpb_wcs_template_loader->set_template_data( $new_data );
					$wpb_wcs_template_loader->get_template_part( $content_type );
				?>
			</div>

		<?php do_action( 'wpb_wcs_after_slider_loop' ); ?>
		
	<?php endforeach; ?>

</div>

<?php do_action( 'wpb_wcs_after_slider' ); ?>