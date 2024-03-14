<?php
// to check whether accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $post;
$product = wc_get_product( $post->ID );
?>
<div>
	<h4 style="padding-left: 3%;"><?php esc_html_e( 'Catalog mode options:', 'elex-catmode-rolebased-price' ); ?></h4>
	<h4 style="padding-left: 3%;font-style: italic;font-weight:normal;"><?php esc_html_e( 'The changes you make here will be applicable across user roles. You can exclude Administrator role from these settings.', 'elex-catmode-rolebased-price' ); ?></h4>
	<div style="padding-left: 3%;height: 60px;">
	<label style="margin-left: 0px;width: 40%;float: left;"><?php esc_html_e( 'Remove Add to Cart', 'elex-catmode-rolebased-price' ); ?></label>
	<?php $checked = ( ( $product->get_meta( 'product_adjustment_hide_addtocart_catalog' ) ) == 'yes' ) ? true : false; ?>
	<input type="checkbox" style="float: left;margin-left: 0px;" name="product_adjustment_hide_addtocart_catalog" id="product_adjustment_hide_addtocart_catalog" <?php checked( $checked, true ); ?> />
	<label style="float: left;margin-left:5px;"><?php esc_html_e( 'Enable', 'elex-catmode-rolebased-price' ); ?></label>
	<span class="description" style="width: 60%;float: right;margin-top: 6px;">
		<?php esc_html_e( 'Check to remove Add to Cart.', 'elex-catmode-rolebased-price' ); ?></span>
</div>
<div style="padding-left: 3%;height: 60px;" id="shop_product_checkbox_prod_hide_addtocart_catalog">
	<?php 
	$checked_shop_catalog = ( ( $product->get_meta( 'product_adjustment_hide_addtocart_catalog_shop' ) ) == 'yes' ) ? true : false;
	$checked_product_catalog = ( ( $product->get_meta( 'product_adjustment_hide_addtocart_catalog_product' ) ) == 'yes' ) ? true : false;
	?>
	<input type="checkbox" style="float: left;margin-left: 40%;" name="product_adjustment_hide_addtocart_catalog_shop" id="product_adjustment_hide_addtocart_catalog_shop" <?php checked( $checked_shop_catalog, true ); ?> />
	<label style="float: left;margin-left:5px;"><?php esc_html_e( 'Shop Page', 'elex-catmode-rolebased-price' ); ?></label>
	<input type="checkbox" style="float: left;margin-left: 0px;" name="product_adjustment_hide_addtocart_catalog_product" id="product_adjustment_hide_addtocart_catalog_product" <?php checked( $checked_product_catalog, true ); ?> />
	<label style="float: left;margin-left:5px;"><?php esc_html_e( 'Product Page', 'elex-catmode-rolebased-price' ); ?></label>
</div>
<div style="padding-left: 3%;height: 60px;" id="place_holder_prod_hide_addtocart_catalog">
	<label style="margin-left: 0px;width: 40%;float: left;"><?php esc_html_e( 'Placeholder text', 'elex-catmode-rolebased-price' ); ?></label>
	<?php $place_text = $product->get_meta( 'product_adjustment_hide_addtocart_placeholder_catalog', true ); ?>
	<textarea name="product_adjustment_hide_addtocart_placeholder_catalog" id="product_adjustment_hide_addtocart_placeholder_catalog" style="width: 40%;"><?php echo esc_html( $place_text ); ?></textarea>
	<span style="font-size: 1.4em;"> <?php echo wc_help_tip( __( "Enter a text or html content to show as placeholder when Add to Cart button is removed. Leave it empty if you don't want to show any content.", 'elex-catmode-rolebased-price' ) ); ?></span>
</div>

<!-- Option to Customize add to cart for catalog-->
<div style="padding-left: 3%;height: 60px;">
	<label style="margin-left: 0px;width: 40%;float: left;"><?php esc_html_e( 'Customize Add to Cart', 'elex-catmode-rolebased-price' ); ?></label>
	<?php $checked = ( ( $product->get_meta( 'product_adjustment_customize_addtocart_catalog' ) ) == 'yes' ) ? true : false; ?>
	<input type="checkbox" style="float: left;margin-left: 0px;" name="product_adjustment_customize_addtocart_catalog" id="product_adjustment_customize_addtocart_catalog" <?php checked( $checked, true ); ?> />
	<label style="float: left;margin-left:5px;"><?php esc_html_e( 'Enable', 'elex-catmode-rolebased-price' ); ?></label>
	<span class="description" style="width: 60%;float: right;margin-top: 6px;">
		<?php esc_html_e( 'Check to customize Add to Cart.', 'elex-catmode-rolebased-price' ); ?></span>
</div>
<div style="padding-left: 3%;height: 60px;" id="btn_text_prod_replace_addtocart_catalog">
	<label style="margin-left: 0px;width: 40%;float: left;"><?php esc_html_e( 'Change Button Text (Product Page)', 'elex-catmode-rolebased-price' ); ?></label>
	<?php $place_text = $product->get_meta( 'product_adjustment_customize_addtocart_prod_btn_text_catalog' ); ?>
	<input type="text" value="<?php echo esc_html( $place_text ); ?>" name="product_adjustment_customize_addtocart_prod_btn_text_catalog" id="product_adjustment_customize_addtocart_prod_btn_text_catalog" style="width: 40%;">
	<span style="font-size: 1.4em;"> <?php echo wc_help_tip( __( 'Enter a text to replace the existing Add to Cart button text on the product page.', 'elex-catmode-rolebased-price' ) ); ?></span>
</div>
<div style="padding-left: 3%;height: 60px;" id="btn_text_shop_replace_addtocart_catalog">
	<label style="margin-left: 0px;width: 40%;float: left;"><?php esc_html_e( 'Change Button Text (Shop Page)', 'elex-catmode-rolebased-price' ); ?></label>
	<?php $place_text = $product->get_meta( 'product_adjustment_customize_addtocart_shop_btn_text_catalog' ); ?>
	<input type="text" value="<?php echo esc_html( $place_text ); ?>" name="product_adjustment_customize_addtocart_shop_btn_text_catalog" id="product_adjustment_customize_addtocart_shop_btn_text_catalog" style="width: 40%;">
	<span style="font-size: 1.4em;"> <?php echo wc_help_tip( __( 'Enter a text to replace the existing Add to Cart button text on the shop page.', 'elex-catmode-rolebased-price' ) ); ?></span>
</div>
<div style="padding-left: 3%;height: 60px;" id="btn_url_replace_addtocart_catalog">
	<label style="margin-left: 0px;width: 40%;float: left;"><?php esc_html_e( 'Change Button URL', 'elex-catmode-rolebased-price' ); ?></label>
	<?php $place_text = $product->get_meta( 'product_adjustment_customize_addtocart_btn_url_catalog' ); ?>
	<input type="text" value="<?php echo esc_html( $place_text ); ?>" name="product_adjustment_customize_addtocart_btn_url_catalog" id="product_adjustment_customize_addtocart_btn_url_catalog" style="width: 40%;">
	<span style="font-size: 1.4em;"> <?php echo wc_help_tip( __( 'Enter a url to redirect customers from Add to Cart button. Leave this field empty to not change the button functionality. Make sure to enter a text in the above fields to apply these changes.', 'elex-catmode-rolebased-price' ) ); ?></span>
</div>

<!-- Option to hide price for catalog-->
<div style="padding-left: 3%;height: 60px;">
	<label style="margin-left: 0px;width: 40%;float: left;"><?php esc_html_e( 'Hide Price', 'elex-catmode-rolebased-price' ); ?></label>
	<?php $checked = ( ( $product->get_meta( 'product_adjustment_hide_price_catalog' ) ) == 'yes' ) ? true : false; ?>
	<input type="checkbox" style="float: left;margin-left: 0px;" name="product_adjustment_hide_price_catalog" id="product_adjustment_hide_price_catalog" <?php checked( $checked, true ); ?> />
	<label style="float: left;margin-left:5px;"><?php esc_html_e( 'Enable', 'elex-catmode-rolebased-price' ); ?></label>
	<span class="description" style="width: 60%;float: right;margin-top: 6px;">
		<?php esc_html_e( 'Check to hide price.', 'elex-catmode-rolebased-price' ); ?></span>
</div>
<div style="padding-left: 3%;height: 60px;" id="place_holder_prod_hide_price_catalog">
	<label style="margin-left: 0px;width: 40%;float: left;"><?php esc_html_e( 'Placeholder text', 'elex-catmode-rolebased-price' ); ?></label>
	<?php $place_text = $product->get_meta( 'product_adjustment_hide_price_placeholder_catalog' ); ?>
	<textarea name="product_adjustment_hide_price_placeholder_catalog" id="product_adjustment_hide_price_placeholder_catalog" style="width: 40%;"><?php echo esc_html( $place_text ); ?></textarea>
	<span style="font-size: 1.4em;"> <?php echo wc_help_tip( __( "Enter the text you want to show when price is removed. Leave it empty if you don't want to show any placeholder text.", 'elex-catmode-rolebased-price' ) ); ?></span>
</div>
<div style="padding-left: 3%;height: 60px;">
	<label style="margin-left: 0px;width: 40%;float: left;"><?php esc_html_e( 'Exclude Administrator', 'elex-catmode-rolebased-price' ); ?></label>
	<?php $checked = ( ( $product->get_meta( 'product_adjustment_exclude_admin_catalog' ) ) == 'yes' ) ? true : false; ?>
	<input type="checkbox" style="float: left;margin-left: 0px;" name="product_adjustment_exclude_admin_catalog" id="product_adjustment_exclude_admin_catalog" <?php checked( $checked, true ); ?> />
	<label style="float: left;margin-left:5px;"><?php esc_html_e( 'Enable', 'elex-catmode-rolebased-price' ); ?></label>
	<span class="description" style="width: 60%;float: right;margin-top: 6px;">
		<?php esc_html_e( 'Check to exclude Administrator role from the above catalog mode settings.', 'elex-catmode-rolebased-price' ); ?></span>
</div>
</div>

<script type="text/javascript">

	jQuery(window).on('load',function () {
		// Ordering
		jQuery('.product_price_adjustment_catalog tbody').sortable({
			items: 'tr',
			cursor: 'move',
			axis: 'y',
			handle: '.sort',
			scrollSensitivity: 40,
			forcePlaceholderSize: true,
			helper: 'clone',
			opacity: 0.65,
			placeholder: 'wc-metabox-sortable-placeholder',
			start: function (event, ui) {
				ui.item.css('baclbsround-color', '#f6f6f6');
			},
			stop: function (event, ui) {
				ui.item.removeAttr('style');
				elex_cm_price_adjustment_row_indexes();
			}
		});
		elex_cm_hide_product_placeholder_and_shop_product_checkbox('#product_adjustment_hide_addtocart_catalog','#place_holder_prod_hide_addtocart_catalog','#shop_product_checkbox_prod_hide_addtocart_catalog');
		elex_cm_hide_product_placeholder_text('#product_adjustment_hide_price_catalog','#place_holder_prod_hide_price_catalog');
		elex_cm_customize_addtocart_product_catalog();
		
		jQuery("#product_adjustment_hide_addtocart_catalog").click(function () {
			elex_cm_hide_product_placeholder_and_shop_product_checkbox('#product_adjustment_hide_addtocart_catalog','#place_holder_prod_hide_addtocart_catalog','#shop_product_checkbox_prod_hide_addtocart_catalog');
		});
		jQuery("#product_adjustment_hide_price_catalog").click(function () {
			elex_cm_hide_product_placeholder_text('#product_adjustment_hide_price_catalog','#place_holder_prod_hide_price_catalog');
		});
		jQuery('#product_adjustment_customize_addtocart_catalog').change(function () {
			elex_cm_customize_addtocart_product_catalog();
		});
		
		function elex_cm_hide_product_placeholder_text(check, hide_field) {
			if (jQuery(check).is(":checked")) {
				jQuery(hide_field).show();
			} else {
				jQuery(hide_field).hide();
			}
		}
		function elex_cm_hide_product_placeholder_and_shop_product_checkbox(check, hide_field1, hide_field2) {
			if (jQuery(check).is(":checked")) {
				jQuery(hide_field1).show();
				jQuery(hide_field2).show();
			} else {
				jQuery(hide_field1).hide();
				jQuery(hide_field2).hide();
			}
		}
		function elex_cm_customize_addtocart_product_catalog() {
			if (jQuery('#product_adjustment_customize_addtocart_catalog').is(":checked")) {
				jQuery('#btn_text_prod_replace_addtocart_catalog').show();
				jQuery('#btn_text_shop_replace_addtocart_catalog').show();
				jQuery('#btn_url_replace_addtocart_catalog').show();
			} else {
				jQuery('#btn_text_prod_replace_addtocart_catalog').hide();
				jQuery('#btn_text_shop_replace_addtocart_catalog').hide();
				jQuery('#btn_url_replace_addtocart_catalog').hide();
			}
		}


	});

</script>
	
