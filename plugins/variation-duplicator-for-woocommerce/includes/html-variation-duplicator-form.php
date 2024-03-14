<?php
	defined( 'ABSPATH' ) or die( 'Keep Silent' );
	
	/**
	 * @var $product
	 * @var $variation_id
	 * @var $parent_id
	 * @var $selected_variation_image_id
	 * @var $variation
	 * @var $child_ids
	 */
	
	// echo $product->get_type(); // variable
?>

<div class="woo-variable-image-duplicator-wrapper form-row form-row-full">
    <div class="form-field form-row form-row-first">
        <fieldset class="form-field variable_image_duplicate_type_form_field">
            <ul class="wc-radios">
                <li>
                    <label>
						<?php $available = ( has_post_thumbnail( $parent_id ) || ! empty( $child_ids ) ) ?>
                        <input name="variable_image_duplicate_type[<?php echo esc_attr( $variation_id ) ?>]" value="from" type="radio" class="no-track-change variable-image-duplicate-type">
						<?php esc_html_e( 'Set image from another variation / from this product', 'variation-duplicator-for-woocommerce' ) ?>
						<?php echo wc_help_tip( esc_attr__( 'Bring image from another variation or featured image of this product', 'variation-duplicator-for-woocommerce' ) ) ?>
                    </label>
                </li>
                <li>
                    <label>
                        <input name="variable_image_duplicate_type[<?php echo esc_attr( $variation_id ) ?>]" value="to" type="radio" class="no-track-change variable-image-duplicate-type">
						<?php esc_html_e( 'Set this image to other variation(s)', 'variation-duplicator-for-woocommerce' ) ?>
						<?php echo wc_help_tip( esc_attr__( 'Copy this variation image to other variation(s)', 'variation-duplicator-for-woocommerce' ) ) ?>
                    </label>
                </li>
            </ul>
        </fieldset>
    </div>

    <div class="form-field form-row form-row-last">

        <p class="variable-list variable-image-duplicate-from">
            <label for="variation_image_from_<?php echo esc_attr( $variation_id ); ?>">
				<?php esc_html_e( 'Variation or Product ID:', 'variation-duplicator-for-woocommerce' ); ?>
                <a target="_blank" href="https://getwooplugins.com/documentation/variation-duplicator-for-woocommerce/#add-variation-image"><?php esc_html_e( 'See how it works', 'variation-duplicator-for-woocommerce' ); ?></a>
            </label>

            <select id="variation_image_from_<?php echo esc_attr( $variation_id ); ?>" data-allow_clear="true"
                    data-placeholder="<?php esc_html_e( 'Variation or Product ID:', 'variation-duplicator-for-woocommerce' ); ?>"
                    class="variable-image-duplicate-select"
                    name="variable_image_duplicate_from[<?php echo esc_attr( $variation_id ); ?>]">

                <option value="" data-thumbnail_url=""></option>
				
				<?php
					$item_thumb_url = ( has_post_thumbnail( $parent_id ) ) ? esc_url( get_the_post_thumbnail_url( $parent_id, 'thumbnail' ) ) : '';
					
					// Product ID Loop
					printf( '<optgroup label="%s">', esc_attr__( 'Product ID', 'variation-duplicator-for-woocommerce' ) );
					printf( '<option %2$s value="%1$s" data-thumbnail_url="%5$s">#%1$s %4$s %3$s</option>', esc_attr( $parent_id ), disabled( ! has_post_thumbnail( $parent_id ), true, false ), ( ! has_post_thumbnail( $parent_id ) ? esc_html__( ' - Have no image to clone', 'variation-duplicator-for-woocommerce' ) : '' ), ( has_post_thumbnail( $parent_id ) ? esc_html__( ' - Product Featured Image', 'variation-duplicator-for-woocommerce' ) : '' ), $item_thumb_url );
					echo '</optgroup>';
					
					// Variation ID Loop
					if ( ! empty( $child_ids ) ) {
						printf( '<optgroup label="%s">', esc_attr__( 'Variation ID', 'variation-duplicator-for-woocommerce' ) );
						foreach ( $child_ids as $child_id ) {
							
							$variation      = wc_get_product_object( 'variation', $child_id );
							$variation_data = $variation->get_data();
							$item_thumb_url = ( has_post_thumbnail( $child_id ) ) ? esc_url( get_the_post_thumbnail_url( $child_id, 'thumbnail' ) ) : '';
							$variation_name = '';
							
							if ( ! empty( $variation_data[ 'attribute_summary' ] ) && ! empty( $variation_data[ 'image_id' ] ) ) {
								$variation_name = '- ' . self::format_attribute_summary( $variation_data[ 'attribute_summary' ] );
							}
							
							printf( '<option %2$s value="%1$s" data-thumbnail_url="%5$s">#%1$s %4$s %3$s</option>', esc_attr( $child_id ), disabled( empty( $variation_data[ 'image_id' ] ), true, false ), ( empty( $variation_data[ 'image_id' ] ) ) ? esc_html__( ' - Have no image to clone', 'variation-duplicator-for-woocommerce' ) : '', ( ! empty( $variation_name ) ) ? $variation_name : '', $item_thumb_url );
							
						}
						echo '</optgroup>';
					}
				?>
            </select>
        </p>

        <p class="variable-list variable-image-duplicate-to">
            <label for="variation_image_to_<?php echo esc_attr( $variation_id ); ?>">
				<?php esc_html_e( 'Variation IDs:', 'variation-duplicator-for-woocommerce' ); ?>
                <a target="_blank" href="https://getwooplugins.com/documentation/variation-duplicator-for-woocommerce/#set-variation-image"><?php esc_html_e( 'See how it works', 'variation-duplicator-for-woocommerce' ); ?></a>
            </label>

            <select id="variation_image_to_<?php echo esc_attr( $variation_id ); ?>" multiple="multiple"
                    data-placeholder="<?php esc_html_e( 'Variation IDs:', 'variation-duplicator-for-woocommerce' ); ?>"
                    class="variable-image-duplicate-select multiselect wc-enhanced-select"
                    name="variable_image_duplicate_to[<?php echo esc_attr( $variation_id ); ?>][]">
				<?php
					foreach ( $child_ids as $child_id ) {
						echo '<option value="' . esc_attr( $child_id ) . '">#' . esc_attr( $child_id ) . '</option>';
					}
				?>
            </select>

            <span class="button-wrapper">
                    <button class="button select_all_variations"><?php esc_html_e( 'Select all', 'variation-duplicator-for-woocommerce' ); ?></button>
                    <button class="button select_no_variations"><?php esc_html_e( 'Select none', 'variation-duplicator-for-woocommerce' ); ?></button>
            </span>
        </p>

        <div class="variable-list variable-image-duplicate-to-notice">
            <p class="notice"><?php esc_html_e( 'Variation image not chosen. Please choose or upload a variation image first.', 'variation-duplicator-for-woocommerce' ); ?></p>
        </div>

    </div>
	
	<?php do_action( 'woo_variation_duplicator_form', $variation, $product ); ?>

    <div class="clear"></div>
</div> <!-- .variation-image-duplicator-wrapper -->