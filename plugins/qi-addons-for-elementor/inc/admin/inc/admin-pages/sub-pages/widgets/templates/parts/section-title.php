<div class="qodef-widgets-section-title-holder">
	<h3 class="qodef-widgets-section-title"><?php echo ucfirst( str_replace( '-', ' ', $shortcode_subcategory ) ); ?></h3>
	<div class="qodef-checkbox-toggle qodef-field">
		<h6><?php esc_html_e( 'Activate All', 'qi-addons-for-elementor' ); ?></h6>
		<input type="checkbox" class="qodef-section-enable" id="<?php echo esc_attr( $shortcode_subcategory ); ?>" name="<?php echo esc_attr( $shortcode_subcategory ); ?>" value="yes" <?php echo ( in_array( $shortcode_subcategory, $enabled_subcategory, true ) ) ? 'checked' : ''; ?> />
		<label for="<?php echo esc_attr( $shortcode_subcategory ); ?>"><?php echo esc_html( ucfirst( str_replace( '-', ' ', $shortcode_subcategory ) ) ); ?></label>
	</div>
</div>
