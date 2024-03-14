<?php
/**
 * Handles Post Setting metabox HTML
 *
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $post;

$prefix = WP_AAS_META_PREFIX; // Metabox prefix

// Carousel Variables 
$width 						= get_post_meta( $post->ID, $prefix.'width', true );
$width						= ! empty( $width )		? wp_aas_clean_number( $width )		: '';

$height 					= get_post_meta( $post->ID, $prefix.'height', true );
$height						= ! empty( $height )	? wp_aas_clean_number( $height )	: '';

$image_size 				= get_post_meta( $post->ID, $prefix.'image_size', true );
$image_size					= ! empty( $image_size )		? wp_aas_clean( $image_size )	: 'large';

$visible_panels 			= get_post_meta( $post->ID, $prefix.'visible_panels', true );
$visible_panels				= ! empty( $visible_panels )	? wp_aas_clean_number( $visible_panels )	: '';

$orientation 				= get_post_meta( $post->ID, $prefix.'orientation', true );
$orientation				= ! empty( $orientation )	? wp_aas_clean( $orientation )	: 'horizontal';

$panel_distance 			= get_post_meta( $post->ID, $prefix.'panel_distance', true );
$panel_distance				= ! empty( $panel_distance )	? wp_aas_clean_number( $panel_distance )	: '';

$max_openedaccordion_size 	= get_post_meta( $post->ID, $prefix.'max_openedaccordion_size', true );
$max_openedaccordion_size	= ! empty( $max_openedaccordion_size )	? wp_aas_clean( $max_openedaccordion_size )	: '';

$open_panel_on 				= get_post_meta( $post->ID, $prefix.'open_panel_on', true );
$open_panel_on				= ! empty( $open_panel_on )	? wp_aas_clean( $open_panel_on )	: '';

$shadow						= get_post_meta( $post->ID, $prefix.'shadow', true );
$shadow						= ! empty( $shadow )	? wp_aas_clean( $shadow )	: '';

$autoplay 					= get_post_meta( $post->ID, $prefix.'autoplay', true );
$autoplay					= ! empty( $autoplay )	? wp_aas_clean( $autoplay )	: '';

$mouse_wheel 				= get_post_meta( $post->ID, $prefix.'mouse_wheel', true );
$mouse_wheel				= ! empty( $mouse_wheel )	? wp_aas_clean( $mouse_wheel )	: '';
?>

<div class="wp-aas-mb-tabs-wrp">
	<div id="wp-aas-sdetails" class="wp-aas-sdetails wpaas-carousel">
		<table class="form-table wp-aas-sdetails-tbl">
		<h3><?php esc_html_e('Choose your Settings for Accordion', 'accordion-and-accordion-slider') ?></h3>
		<hr>	
			<tbody>
				<tr valign="top">					
					<td>
						<label><?php esc_html_e('Width', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>
					<input type="number" min="100"  name="<?php echo esc_attr($prefix); ?>width" value="<?php echo esc_attr( $width ); ?>"> px<br/>
					<em style="font-size:11px;"><?php esc_html_e('Enter width eg. 900','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>

				<tr valign="top">
					<td>
						<label><?php esc_html_e('Height', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>
						<input type="number" min="200" name="<?php echo esc_attr($prefix); ?>height" value="<?php echo esc_attr($height); ?>"> px<br/>
						<em style="font-size:11px;"><?php esc_html_e('Enter height eg. 300','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>
				<tr valign="top">					
					<td>
						<label><?php esc_html_e('Image Size', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>						
						<input type="text"  name="<?php echo esc_attr($prefix); ?>image_size" value="<?php echo esc_attr($image_size); ?>"> <br/>
						<em style="font-size:11px;"><?php esc_html_e('Enter the image size. You can use following size: thumbnail, medium, medium_large, large and full','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>
				<tr valign="top">					
					<td>
						<label><?php esc_html_e('Visible Accordion Panels', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>
					<input type="number" min="1" step="1" name="<?php echo esc_attr($prefix); ?>visible_panels" value="<?php echo esc_attr($visible_panels); ?>"><br/>
					<em style="font-size:11px;"><?php esc_html_e('Enter Visible Accordion Panels at a time. eg. 5','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>

				<tr valign="top">
					<td>
						<label><?php esc_html_e('Orientation', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo esc_attr($prefix); ?>orientation" value="horizontal" <?php checked( 'horizontal', $orientation ); ?>> Horizontal
						<input type="radio" name="<?php echo esc_attr($prefix); ?>orientation" value="vertical" <?php checked( 'vertical', $orientation ); ?>> Vertical<br/>
						<em style="font-size:11px;"><?php esc_html_e('Select orientation for accordion','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>

				<tr valign="top">
					<td>
						<label><?php esc_html_e('Space Between Accordion', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>
						<input type="number"  name="<?php echo esc_attr($prefix); ?>panel_distance" value="<?php echo esc_attr($panel_distance); ?>"><br/>
						<em style="font-size:11px;"><?php esc_html_e('Distance between accordion. eg 10','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>
				<tr valign="top">					
					<td>
						<label><?php esc_html_e('Max Opened Accordion Size', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>						
						<input type="text"  name="<?php echo esc_attr($prefix); ?>max_openedaccordion_size" value="<?php echo esc_attr($max_openedaccordion_size); ?>"> <br/>
						<em style="font-size:11px;"><?php esc_html_e('Enter opened accordion size eg. 80%','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>

				<tr valign="top">
					<td>
						<label><?php esc_html_e('Open Accordion Panel On', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo esc_attr($prefix); ?>open_panel_on" value="hover" <?php checked( 'hover', $open_panel_on ); ?>>Hover
						<input type="radio" name="<?php echo esc_attr($prefix); ?>open_panel_on" value="click" <?php checked( 'click', $open_panel_on ); ?>>Click<br/>
						<em style="font-size:11px;"><?php esc_html_e('Select accordion panel open option','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td>
						<label><?php esc_html_e('Shadow', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo esc_attr($prefix); ?>shadow" value="true" <?php checked( 'true', $shadow ); ?>>True
						<input type="radio" name="<?php echo esc_attr($prefix); ?>shadow" value="false" <?php checked( 'false', $shadow ); ?>>False<br/>
						<em style="font-size:11px;"><?php esc_html_e('Enable shadow or not','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td>
						<label><?php esc_html_e('Autoplay', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo esc_attr($prefix); ?>autoplay" value="true" <?php checked( 'true', $autoplay ); ?>>True
						<input type="radio" name="<?php echo esc_attr($prefix); ?>autoplay" value="false" <?php checked( 'false', $autoplay ); ?>>False<br/>
						<em style="font-size:11px;"><?php esc_html_e('Enable autoplay or not','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td>
						<label><?php esc_html_e('Mouse Wheel', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo esc_attr($prefix); ?>mouse_wheel" value="true" <?php checked( 'true', $mouse_wheel ); ?>>True
						<input type="radio" name="<?php echo esc_attr($prefix); ?>mouse_wheel" value="false" <?php checked( 'false', $mouse_wheel ); ?>>False<br/>
						<em style="font-size:11px;"><?php esc_html_e('Enable mouse wheel or not','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>

				<tr class="wp-aas-pro-feature">
				<td>
					<?php esc_html_e('Fancy Box ', 'accordion-and-accordion-slider'); ?><span class="wp-aas-pro-tag"><?php esc_html_e('PRO','accordion-and-accordion-slider');?></span>
				</td>
				<td>
					<input type="radio" name="<?php echo esc_attr($prefix); ?>mouse_wheel" value="true" disabled="">True
					<input type="radio" name="<?php echo esc_attr($prefix); ?>mouse_wheel" value="false" disabled="">False<br/>
					<span class="description"><?php esc_html_e('Enable Fancy Box or not.', 'accordion-and-accordion-slider'); ?></span><br/><strong><?php echo sprintf( __( ' Utilize this <a href="%s" target="_blank">Premium Features (With Risk-Free 30 days money back guarantee)</a> to get best of this plugin with Annual or Lifetime bundle deal.', 'accordion-and-accordion-slider'), WP_AAS_PLUGIN_LINK_UNLOCK); ?></strong>
				</td>
			</tr>
			</tbody>
		</table>
		<hr>
		<table class="form-table wp-aas-sdetails-tbl">
			<h3><?php esc_html_e('Breakdown Panels for Responsive Device (Mobile/Tablet/iPad)', 'accordion-and-accordion-slider') ?></h3>
			<hr>
			<tbody>
				<tr class="wp-aas-pro-feature">					
					<td>
						<label><?php esc_html_e('Visible Accordion Panels (width: 960) ', 'accordion-and-accordion-slider'); ?><span class="wp-aas-pro-tag"><?php esc_html_e('PRO','accordion-and-accordion-slider');?></span></label>
					</td>
					<td>
					<input type="number" name="<?php echo esc_attr($prefix); ?>visible_panels_960" disabled=""><br/>
					<span class="description"><?php esc_html_e('Enter Visible Accordion Panels at a time. eg. 4 ', 'accordion-and-accordion-slider'); ?></span><br/><strong><?php echo sprintf( __( ' Utilize this <a href="%s" target="_blank">Premium Features (With Risk-Free 30 days money back guarantee)</a> to get best of this plugin with Annual or Lifetime bundle deal.', 'accordion-and-accordion-slider'), WP_AAS_PLUGIN_LINK_UNLOCK); ?></strong>
					</td>
				</tr>
				<tr class="wp-aas-pro-feature">					
					<td>
						<label><?php esc_html_e('Visible Accordion Panels (width: 800) ', 'accordion-and-accordion-slider'); ?><span class="wp-aas-pro-tag"><?php esc_html_e('PRO','accordion-and-accordion-slider');?></span></label>
					</td>
					<td>
					<input type="number" name="<?php echo esc_attr($prefix); ?>visible_panels_800" disabled=""><br/>
					<span class="description"><?php esc_html_e('Enter Visible Accordion Panels at a time. eg. 3 ', 'accordion-and-accordion-slider'); ?></span><br/><strong><?php echo sprintf( __( ' Utilize this <a href="%s" target="_blank">Premium Features (With Risk-Free 30 days money back guarantee)</a> to get best of this plugin with Annual or Lifetime bundle deal.', 'accordion-and-accordion-slider'), WP_AAS_PLUGIN_LINK_UNLOCK); ?></strong>
					</td>
				</tr>
				<tr class="wp-aas-pro-feature">					
					<td>
						<label><?php esc_html_e('Visible Accordion Panels (width: 650) ', 'accordion-and-accordion-slider'); ?><span class="wp-aas-pro-tag"><?php esc_html_e('PRO','accordion-and-accordion-slider');?></span></label>
					</td>
					<td>
					<input type="number" name="<?php echo esc_attr($prefix); ?>visible_panels_650" disabled=""><br/>
					<span class="description"><?php esc_html_e('Enter Visible Accordion Panels at a time. eg. 2 ', 'accordion-and-accordion-slider'); ?></span><br/><strong><?php echo sprintf( __( ' Utilize this <a href="%s" target="_blank">Premium Features (With Risk-Free 30 days money back guarantee)</a> to get best of this plugin with Annual or Lifetime bundle deal.', 'accordion-and-accordion-slider'), WP_AAS_PLUGIN_LINK_UNLOCK); ?></strong>
					</td>
				</tr>
				<tr class="wp-aas-pro-feature">					
					<td>
						<label><?php esc_html_e('Visible Accordion Panels (width: 500) ', 'accordion-and-accordion-slider'); ?><span class="wp-aas-pro-tag"><?php esc_html_e('PRO','accordion-and-accordion-slider');?></span></label>
					</td>
					<td>
					<input type="number" name="<?php echo esc_attr($prefix); ?>visible_panels_500" disabled=""><br/>
					<span class="description"><?php esc_html_e('Enter Visible Accordion Panels at a time. eg. 1 ', 'accordion-and-accordion-slider'); ?></span><br/><strong><?php echo sprintf( __( ' Utilize this <a href="%s" target="_blank">Premium Features (With Risk-Free 30 days money back guarantee)</a> to get best of this plugin with Annual or Lifetime bundle deal.', 'accordion-and-accordion-slider'), WP_AAS_PLUGIN_LINK_UNLOCK); ?></strong>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>