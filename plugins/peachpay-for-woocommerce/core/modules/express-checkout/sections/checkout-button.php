<?php
/**
 * PeachPay button settings.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * Calls functions that add the settings fields to the express checkout / checkout button section.
 */
function peachpay_express_checkout_button_render() {
	peachpay_checkout_button_sub_nav();
	peachpay_button_section_overall();
	peachpay_button_display_by_pages();
	peachpay_button_section_shop_page();
}

/**
 * Adds thee checkout button / floating button tabs.
 */
function peachpay_checkout_button_sub_nav() {
	?>
	<div class='pp-flex-row pp-gap-12 pp-sub-nav-container pp-sub-nav-controller'>
		<?php
		$buttons = array(
			array(
				'href'  => 'full',
				'title' => __( 'Full button', 'peachpay-for-woocommerce' ),
			),
			array(
				'href'  => 'floating',
				'title' => __( 'Floating button', 'peachpay-for-woocommerce' ),
			),
		);
		foreach ( $buttons as $button ) {
			?>
				<div class='pp-sub-nav-button' href='#<?php echo esc_attr( $button['href'] ); ?>'><?php echo esc_html( $button['title'] ); ?></div>
			<?php
		}
		?>
	</div>
	<?php
}

/**
 * Adds the general settings fields.
 */
function peachpay_button_section_overall() {
	$class = 'pp-header pp-sub-nav-full';

	add_settings_field(
		'peachpay_field_button_appearance',
		peachpay_build_section_header( __( 'Appearance', 'peachpay-for-woocommerce' ), 'https://youtu.be/CLoNYAWQxY0' ),
		'peachpay_button_appearance_section_cb',
		'peachpay',
		'peachpay_express_checkout_render',
		array( 'class' => $class )
	);
}

/**
 * Give a user a choice of where to put the button on the product page
 */
function peachpay_button_product_page_position_cb() {
	$position = array(
		'after'  => '/public/img/button-alignment/button-placement-bottom.svg',
		'inline' => '/public/img/button-alignment/button-placement-inline.svg',
	);
	?>
	<h4 style="margin-bottom: 5px;">Arrangement</h4>
	<section class="pp-alignment-section">
	<?php
	foreach ( $position as $value => $position_img ) {
		?>
			<div class='pp-button-alignment'>
				<input
				id='<?php echo esc_attr( 'peachpay_button_before_after_cart_' . $value ); ?>'
				type='radio'
				name='peachpay_express_checkout_button[product_button_position]'
				value='<?php echo esc_attr( $value ); ?>'
			<?php checked( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'product_button_position', false ), $value, true ); ?>
				>
				<label for='<?php echo esc_attr( 'peachpay_button_before_after_cart_' . $value ); ?>'>
					<img class='pp-alignment-img' src='<?php echo esc_url( peachpay_url( $position_img ) ); ?>'/>
				<?php
				if ( 'inline' === $value ) {
					esc_html_e( 'Inline with "Add to Cart"', 'peachpay-for-woocommerce' );
				} else {
					esc_html_e( 'After "Add to Cart"', 'peachpay-for-woocommerce' );
				}
				?>
				</label>
			</div>
			<?php
	}
	?>
	</section>
	<p
	for='peachpay_button_before_after_cart'
	class="description">
	<?php
	esc_html_e( 'Choose whether the PeachPay button appears inline or below the "add to cart" button on the product page.', 'peachpay-for-woocommerce' );
	?>
	</p>
	<?php
}

/**
 * This function creates the field for toggling PeachPay button icon.
 *
 * @param string $args Contains which button.
 */
function peachpay_field_button_icon_cb( $args ) {
	$icons       = array(
		'pp-icon-lock'     => __( 'Lock', 'peachpay-for-woocommerce' ),
		'pp-icon-baseball' => __( 'Baseball', 'peachpay-for-woocommerce' ),
		'pp-icon-arrow'    => __( 'Arrow', 'peachpay-for-woocommerce' ),
		'pp-icon-mountain' => __( 'Mountain', 'peachpay-for-woocommerce' ),
		'pp-icon-bag'      => __( 'Bag', 'peachpay-for-woocommerce' ),
		'pp-icon-cart'     => __( 'Cart', 'peachpay-for-woocommerce' ),
		'pp-icon-disabled' => __( 'None', 'peachpay-for-woocommerce' ),
	);
	$id          = 'peachpay_' . $args . '_icon_';
	$button_name = $args . '_icon';
	?>
	<section class='pp-radio-section'>
		<?php
		foreach ( $icons as $icon_class => $icon_label ) {
			if ( 'floating_button' === $args && 'pp-icon-disabled' === $icon_class ) {
				continue;
			}
			?>
			<div class='pp-radio-icon'>
				<input
				id='<?php echo esc_attr( $id . $icon_class ); ?>'
				type='radio'
				name='peachpay_express_checkout_button[<?php echo esc_html( $button_name ); ?>]'
				value='<?php echo esc_attr( $icon_class ); ?>'
				<?php checked( peachpay_get_settings_option( 'peachpay_express_checkout_button', $button_name, 'floating_button' === $args ? 'shopping_cart' : 'none' ), $icon_class, true ); ?>
				>
				<label for='<?php echo esc_attr( $id . $icon_class ); ?>'>
					<i class="<?php echo esc_attr( $icon_class ); ?>" style="display:inline-block;width: 1em; height: 1em;font-size: 30px;"></i>
					<p>
						<?php echo esc_attr( $icon_label ); ?>
					</p>
				</label>
			</div>
			<?php
		}
		?>
	</section>
	<p class="description">
	<?php
	if ( 'floating_button' === $args ) {
		esc_html_e( 'Choose one of the icons to be displayed inside your button.', 'peachpay-for-woocommerce' );
	} else {
		esc_html_e( 'Choose one of the icons or none to be displayed inside your button.', 'peachpay-for-woocommerce' );
	}
	?>
	</p>
	<?php
}

/**
 * Callback for peachpay_field_button_border_radius that renders the button
 * radius (rounded corners) setting field.
 *
 * @param array $args Button arguments.
 */
function peachpay_field_button_border_radius_cb( $args ) {
	$options = get_option( 'peachpay_express_checkout_button' );
	$key     = $args['key'];
	?>
	<div>
		<input
			id="<?php echo esc_attr( $key ); ?>"
			name="peachpay_express_checkout_button[<?php echo esc_attr( $key ); ?>]"
			type="number"
			value="<?php echo esc_attr( ( $options && array_key_exists( $key, $options ) ) ? $options[ $key ] : 5 ); ?>"
			style="width: 75px"
		> px
	</div>
	<p class="description"><?php esc_html_e( 'Customize the button corner radius. Leaving it blank defaults it to 5px.', 'peachpay-for-woocommerce' ); ?></p>
	<?php
}

/**
 * Callback for button text field.
 */
function peachpay_field_button_text_cb() {
	?>
	<input
		id="peachpay_button_text"
		name="peachpay_express_checkout_button[peachpay_button_text]"
		type="text"
		class="pp-text-box"
		value='<?php echo esc_attr( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'peachpay_button_text' ) ); ?>'
		style='width: 300px'
		placeholder="<?php echo esc_attr( __( 'Express checkout', 'peachpay-for-woocommerce' ) ); ?>"
	>
	<p class="description"><?php esc_html_e( 'Customize the text of the PeachPay button. Leaving it blank defaults it to "Express checkout" in your chosen language.', 'peachpay-for-woocommerce' ); ?></p>
	<?php
}

/**
 * Callback for button effect field.
 */
function peachpay_field_button_effect_cb() {
	$options = array(
		__( 'Fade', 'peachpay-for-woocommerce' ) => 'effect-fade',
		__( 'None', 'peachpay-for-woocommerce' ) => 'effect-none',
	);
	?>
	<section id="pp-button-effect-section" class='pp-radio-section'>
		<?php
		foreach ( $options as $effect_name => $effect_val ) {
			?>
			<div class='pp-radio-effect' style='<?php echo esc_attr( peachpay_get_branding_css_vars() ); ?>'>
				<input
				id='<?php echo esc_attr( 'peachpay_button_effect_' . $effect_val ); ?>'
				type='radio'
				name='peachpay_express_checkout_button[button_effect]'
				value='<?php echo esc_attr( $effect_val ); ?>'
				<?php checked( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_effect', 'effect-fade' ), $effect_val, true ); ?>
				>
				<label
				class='<?php print( 'effect-fade' === $effect_val ) ? 'effect-fade' : ''; ?>'
				for='<?php echo esc_attr( 'peachpay_button_effect_' . $effect_val ); ?>'
				style='<?php echo esc_attr( peachpay_get_branding_css_vars() ); ?>'
				>
					<?php echo esc_attr( ucfirst( $effect_name ) ); ?>
					<p
					class="<?php print( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_effect', 'effect-fade' ) === $effect_val ) ? '' : 'hide'; ?>">
					<?php echo esc_attr( '(Selected)' ); ?>
					</p>
				</label>
			</div>
			<?php
		}
		?>
	</section>
	<p for='pp-button-effect-section' class="description">
		<?php esc_html_e( 'Choose what effect you would like to see on the button on hover. Hover over the options to see how they look!', 'peachpay-for-woocommerce' ); ?>
	</p>
	<?php
}

/**
 * Adds the fields for PeachPay button on all pages subsection.
 */
function peachpay_button_display_by_pages() {
	$class = 'pp-header pp-sub-nav-full no-border-bottom';

	add_settings_field(
		'peachpay_button_by_all_pages',
		peachpay_build_section_header( __( 'Button display by pages', 'peachpay-for-woocommerce' ), 'https://youtu.be/3k7Gfz_iLS0' ),
		'peachpay_button_by_all_pages_cb',
		'peachpay',
		'peachpay_express_checkout_render',
		array( 'class' => $class )
	);
}

/**
 * Callback for peachpay_field_product_button_alignment that renders the product alignment selector.
 *
 * @param array $args Contains which page.
 */
function peachpay_field_button_alignment_cb( $args ) {
	$key = $args['key'];

	$alignment_product_page = array(
		__( 'Left', 'peachpay-for-woocommerce' )   => 'left',
		__( 'Right', 'peachpay-for-woocommerce' )  => 'right',
		__( 'Full', 'peachpay-for-woocommerce' )   => 'full',
		__( 'Center', 'peachpay-for-woocommerce' ) => 'center',
	);

	// Keep order the same so the default is "Full".
	$alignment_cart_page = array(
		__( 'Left', 'peachpay-for-woocommerce' )   => 'left',
		__( 'Right', 'peachpay-for-woocommerce' )  => 'right',
		__( 'Full', 'peachpay-for-woocommerce' )   => 'full',
		__( 'Center', 'peachpay-for-woocommerce' ) => 'center',
	);

	$alignment_checkout_page = array(
		__( 'Center', 'peachpay-for-woocommerce' ) => 'center',
	);

	$alignment_floating_button = array(
		__( 'Bottom right', 'peachpay-for-woocommerce' ) => 'right',
		__( 'Bottom left', 'peachpay-for-woocommerce' )  => 'left',
	);

	$alignment = array();

	$option_heading = 'Position';

	switch ( $key ) {
		case ( 'product_button_alignment' ):
			$alignment      = $alignment_product_page;
			$option_heading = 'Desktop position';
			break;
		case ( 'cart_button_alignment' ):
			$alignment = $alignment_cart_page;
			break;
		case ( 'checkout_button_alignment' ):
			$alignment = $alignment_checkout_page;
			break;
		case ( 'floating_button_alignment' ):
			$alignment = $alignment_floating_button;
			break;
	}
	?>
	<h4 style="margin-bottom: 5px;"><?php echo esc_attr( $option_heading ); ?></h4>
	<section class="pp-alignment-section">
		<?php foreach ( $alignment as $alignments => $value ) { ?>
			<div class='pp-button-alignment'>
				<input
				id='<?php echo esc_attr( $key . '_' . $value ); ?>'
				type='radio'
				name='peachpay_express_checkout_button[<?php echo esc_attr( $key ); ?>]'
				value='<?php echo esc_attr( $value ); ?>'
				<?php checked( peachpay_get_settings_option( 'peachpay_express_checkout_button', $key, 'right' ), $value, true ); ?>
				>
				<label
				class=''
				for='<?php echo esc_attr( $key . '_' . $value ); ?>'
				>
					<img
					class="pp-alignment-img"
					src="
					<?php
					if ( 'left' === $value ) {
						if ( 'product_button_alignment' === $key ) {
							echo esc_url( peachpay_url( '/public/img/button-alignment/left-product-page.svg' ) );
						} elseif ( 'cart_button_alignment' === $key ) {
							echo esc_url( peachpay_url( '/public/img/button-alignment/left-cart-page.svg' ) );
						} elseif ( 'floating_button_alignment' === $key ) {
							echo esc_url( peachpay_url( '/public/img/button-alignment/float-button-left.svg' ) );
						}
					} elseif ( 'right' === $value ) {
						if ( 'product_button_alignment' === $key ) {
							echo esc_url( peachpay_url( '/public/img/button-alignment/right-product-page.svg' ) );
						} elseif ( 'cart_button_alignment' === $key ) {
							echo esc_url( peachpay_url( '/public/img/button-alignment/right-cart-page.svg' ) );
						} elseif ( 'floating_button_alignment' === $key ) {
							echo esc_url( peachpay_url( '/public/img/button-alignment/float-button-right.svg' ) );
						}
					} elseif ( 'center' === $value ) {
						echo 'cart_button_alignment' === $key ? esc_url( peachpay_url( '/public/img/button-alignment/center-cart-page.svg' ) ) : esc_url( peachpay_url( '/public/img/button-alignment/center-product-page.svg' ) );
					} elseif ( 'full' === $value ) {
						echo 'cart_button_alignment' === $key ? esc_url( peachpay_url( '/public/img/button-alignment/full-cart-page.svg' ) ) : esc_url( peachpay_url( '/public/img/button-alignment/full-product-page.svg' ) );
					}
					?>
					"/>
					<?php echo esc_attr( ucfirst( $alignments ) ); ?>
				</label>
			</div>
		<?php } ?>
	</section>
	<?php
}

/**
 * Callback for peachpay_field_button_width that renders the button width input.
 *
 * @param array $args Page arguments.
 */
function peachpay_field_button_width_cb( $args ) {
	$options = get_option( 'peachpay_express_checkout_button' );
	$key     = $args['key'];
	$page    = '';
	switch ( $key ) {
		case 'button_width_cart_page':
			$page = 'cart';
			break;
		case 'button_width_product_page':
			$page = 'product';
			break;
		case 'button_width_checkout_page':
			$page = 'checkout';
			break;
	}
	$disabled = peachpay_get_settings_option( 'peachpay_express_checkout_button', $page . '_button_position' ) !== 'full' ? '' : 'disabled';

	// If the hidden field is not here then the second time the form is saved
	// while the field is set to "full" the value is lost because disabled
	// inputs are not submitted.
	if ( $disabled ) {
		?>
		<h4>Width</h4>
		<div>
			<input
				id="<?php echo esc_attr( $key ); ?>"
				name="peachpay_express_checkout_button[<?php echo esc_attr( $key ); ?>]"
				type="hidden"
				value="<?php echo esc_attr( ( $options && array_key_exists( $key, $options ) ) ? $options[ $key ] : 220 ); ?>"
			>
			<p class="description">
		</div>
			<?php
			if ( 'button_width_checkout_page' === $key ) {
				esc_html_e( 'Customize the width of the button. Leaving it blank defaults it to 320px.', 'peachpay-for-woocommerce' );
			} else {
				esc_html_e( 'Customize the width of the button. Leaving it blank defaults it to 220px.', 'peachpay-for-woocommerce' );
			}
			?>
		</p>
		<?php
	}

	?>
		<h4>Width</h4>
		<div>
			<input
				id="<?php echo esc_attr( $key ); ?>"
				name="peachpay_express_checkout_button[<?php echo esc_attr( $key ); ?>]"
				type="number"
				value="<?php echo esc_attr( ( $options && array_key_exists( $key, $options ) ) ? $options[ $key ] : 220 ); ?>"
				style="width: 75px" <?php echo esc_attr( $disabled ); ?>
			> px
		</div>
		<p class="description">
			<?php
			if ( 'button_width_checkout_page' === $key ) {
				esc_html_e( 'Customize the width of the button. Leaving it blank defaults it to 320px.', 'peachpay-for-woocommerce' );
			} else {
				esc_html_e( 'Customize the width of the button. Leaving it blank defaults it to 220px.', 'peachpay-for-woocommerce' );
			}
			?>
		</p>
	<?php
}

/**
 * Callback for peachpay_field_product_button_preview that renders the product page button preview.
 */
function peachpay_field_button_preview_cb() {
	?>
	<style>
		.pp-button-preview-container {
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			height: 100%;
		}

		.pp-button-preview-container a {
			padding: .6180469716em 1.41575em !important;
			font-size: 1.41575em !important;
			font-weight: 600 !important;
		}
	</style>
	<div class="pp-button-preview-container">
		<div style="height: 115px">
			<?php
			//PHPCS:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo pp_checkout_button_template(
				array(
					'text'                            => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'peachpay_button_text', __( 'Express Checkout', 'peachpay-for-woocommerce' ) ),
					'text_color'                      => peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'button_text_color', PEACHPAY_DEFAULT_TEXT_COLOR ),
					'background_color'                => peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'button_color', PEACHPAY_DEFAULT_BACKGROUND_COLOR ),
					'icon_class'                      => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_icon', '' ),
					'effect_class'                    => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_effect', 'none' ),
					'border_radius'                   => intval( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_border_radius', 5 ) ),
					'preview'                         => true,

					'display_available_payment_icons' => filter_var( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_display_payment_method_icons', false ), FILTER_VALIDATE_BOOLEAN ),
				)
			);
			?>
		</div>
	</div>
	<?php
}

/**
 * Renders the shop page button options.
 */
function peachpay_button_section_shop_page() {
	$class = 'pp-header pp-sub-nav-floating no-border-bottom';

	add_settings_field(
		'peachpay_shop_page_button',
		peachpay_build_section_header( __( 'Floating button', 'peachpay-for-woocommerce' ), 'https://youtu.be/YjMG7IZlziM' ),
		'peachpay_shop_button_cb',
		'peachpay',
		'peachpay_express_checkout_render',
		array( 'class' => $class )
	);
}

/**
 * Callback for configuring the position of the floating peachpay button
 *
 * @param array $args Position arguments.
 */
function peachpay_field_floating_button_position_cb( $args ) {
	$options = get_option( 'peachpay_express_checkout_button' );
	?>
	<div>
		<input
			id="<?php echo esc_attr( $args ); ?>"
			name="peachpay_express_checkout_button[<?php echo esc_attr( $args ); ?>]"
			type="number"
			value="<?php echo esc_attr( isset( $options[ $args ] ) ? $options[ $args ] : ( 'floating_button_bottom_gap' === $args ? 27 : 45 ) ); ?>"
			style="width: 75px"
		> px
	</div>
	<p class="description">
	<?php
	if ( 'floating_button_bottom_gap' === $args ) {
		esc_html_e( 'Set the bottom gap between the button and the bottom of the page. Leaving it blank defaults it to 27px.', 'peachpay-for-woocommerce' );
	} else {
		esc_html_e( 'Set the right gap between the button and the right side of the page. Leaving it blank defaults it to 45px.', 'peachpay-for-woocommerce' );
	}
	?>
	</p>
	<?php
}

/**
 * Render number fields for floating button.
 *
 * @param string $args Contains which number input type for floating button.
 */
function peachpay_floating_button_number_fields_cb( $args ) {
	$options       = get_option( 'peachpay_express_checkout_button' );
	$default_value = 'floating_button_icon_size' === $args ? 35 : 70;
	?>
	<div>
		<input
			id="<?php echo esc_attr( $args ); ?>"
			name="peachpay_express_checkout_button[<?php echo esc_attr( $args ); ?>]"
			type="number"
			value="<?php echo esc_attr( isset( $options[ $args ] ) ? $options[ $args ] : $default_value ); ?>"
			style="width: 75px"
		> px
	</div>
	<p class="description">
		<?php
		if ( 'floating_button_size' === $args ) {
			esc_html_e( 'Set the size of the button. Leaving it blank defaults it to 70px.', 'peachpay-for-woocommerce' );
		} else {
			esc_html_e( 'Set the size of the icon. Leaving it blank defaults it to 35px.', 'peachpay-for-woocommerce' );
		}
		?>
	</p>
	<?php
}

/**
 * Renders the shop button preview.
 */
function peachpay_field_shop_button_preview_cb() {
	?>
	<div class="pp-button-preview-container">
		<?php
		//PHPCS:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo pp_checkout_floating_button_template(
			array(
				'icon_class'       => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'floating_button_icon', 'pp-icon-lock' ),
				'effect_class'     => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_effect', 'none' ),

				'text_color'       => peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'button_text_color', PEACHPAY_DEFAULT_TEXT_COLOR ),
				'background_color' => peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'button_color', PEACHPAY_DEFAULT_BACKGROUND_COLOR ),

				'button_length'    => intval( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'floating_button_size', 70 ) ),
				'icon_length'      => intval( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'floating_button_icon_size', 35 ) ),

				'position'         => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'floating_button_alignment', 'right' ),
				'bottom_gap'       => intval( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'floating_button_bottom_gap', 27 ) ),
				'side_gap'         => intval( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'floating_button_side_gap', 45 ) ),

				'preview'          => true,
			)
		);
		?>
	</div>
	<?php
}

/**
 * Use to render PeachPay button inclusion settings.
 *
 * @param string $args Arguments passed to this callback from where we add the
 * fields.
 */
function peachpay_button_display_html( $args ) {
	?>
	<div class="pp-switch-section">
		<div>
			<label class="pp-switch">
				<input
					id = "peachpay_enabled_on_<?php echo esc_html( $args[0] ); ?>"
					name = "peachpay_express_checkout_button[<?php echo esc_html( $args[0] ); ?>_enabled]"
					type = "checkbox"
					value = 1
					<?php checked( 1, peachpay_get_settings_option( 'peachpay_express_checkout_button', $args[0] . '_enabled' ), true ); ?>
				>
				<span class="pp-slider round"></span>
			<label>
		</div>
		<div style="pointer-events: none;">
			<label class="pp-setting-label" for='peachpay_enabled_on_<?php echo esc_html( $args[0] ); ?>'>
				<?php
				if ( 'cart_page' === $args[0] ) {
					esc_html_e( 'Show the Express Checkout button on the cart page', 'peachpay-for-woocommerce' );
				} elseif ( 'mini_cart' === $args[0] ) {
					esc_html_e( 'Show the Express Checkout button in the theme’s mini and/or sidebar cart', 'peachpay-for-woocommerce' );
				} elseif ( 'floating_button' === $args[0] ) {
					esc_html_e( 'Show a floating Express Checkout button on all pages', 'peachpay-for-woocommerce' );
				} else {
					esc_html_e( 'Show the Express Checkout button on the checkout page', 'peachpay-for-woocommerce' );
				}
				?>
			</label>
		</div>
	</div>
	<?php
}

/**
 * Display reset button.
 *
 * @param string $args Indicate which button setting section.
 */
function peachpay_reset_button_cb( $args ) {
	$key     = $args['key'];
	$section = $args['section'];
	?>
		<a onclick="return confirm('Are you sure would you like to reset all your changes made to the <?php echo esc_html( $section ); ?> preferences?' )" href="
		<?php
		echo esc_url( add_query_arg( $key, 'peachpay' ) );
		peachpay_reset_settings( $key );
		?>
		" class="button-secondary pp-button-secondary">
		<?php esc_html_e( 'Reset preferences', 'peachpay-for-woocommerce' ); ?>
		</a>
	<?php
}

/**
 * Reset the button settings to original values.
 *
 * @param string $args Indicate which button setting section.
 */
function peachpay_reset_settings( $args ) {
	// phpcs:disable
	if ( isset( $_GET[$args] ) && 'peachpay' === $_GET[$args] && current_user_can( 'manage_options' ) ) {
		peachpay_reset_button( $args );
		wp_safe_redirect( remove_query_arg( $args ) );
		exit();
	}
	//phpcs:enable
}

/**
 * Render button appearance settings options.
 */
function peachpay_button_appearance_section_cb() {
	?>
	<div class="peachpay-setting-section">
		<div>
			<h4><?php esc_html_e( 'Button text', 'peachpay-for-woocommerce' ); ?></h4>
			<?php peachpay_field_button_text_cb(); ?>
		</div>
		<div>
			<h4><?php esc_html_e( 'Rounded corners', 'peachpay-for-woocommerce' ); ?></h4>
			<?php
			peachpay_field_button_border_radius_cb(
				array(
					'label_for' => 'button_border_radius',
					'key'       => 'button_border_radius',
				)
			);
			?>
		</div>
		<div>
			<h4><?php esc_html_e( 'Icon', 'peachpay-for-woocommerce' ); ?></h4>
			<?php peachpay_field_button_icon_cb( 'button' ); ?>
		</div>
		<div>
			<h4><?php esc_html_e( 'Hover effect', 'peachpay-for-woocommerce' ); ?></h4>
			<?php peachpay_field_button_effect_cb(); ?>
		</div>
		<div>
		<?php
		peachpay_admin_input(
			'peachpay_payment_method_icons',
			'peachpay_express_checkout_button',
			'button_display_payment_method_icons',
			1,
			__( 'Display payment method icons below the PeachPay button', 'peachpay-for-woocommerce' ),
			'',
			array( 'input_type' => 'checkbox' )
		);
		?>
		</div>
		<div>
			<h3><?php esc_html_e( 'Preview', 'peachpay-for-woocommerce' ); ?></h3>
			<?php peachpay_field_button_preview_cb(); ?>
		</div>
		<div class="pp-save-button-section">
			<?php
			peachpay_reset_button_cb(
				array(
					'key'     => 'button_appearance',
					'section' => 'Button appearance',
				)
			);
			?>
			<?php submit_button( 'Save changes', 'pp-button-primary' ); ?>
		</div>
	</div>
	<?php
}

/**
 * Renders all the fields for PeachPay button on product, cart, and checkout page.
 */
function peachpay_button_by_all_pages_cb() {
	?>
	<div class="peachpay-setting-section">
		<div>
			<h3 style="font-weight: bold;"><?php esc_html_e( 'Product page', 'peachpay-for-woocommerce' ); ?></h3>
			<?php
			peachpay_admin_input(
				'peachpay_display_on_product_page',
				'peachpay_express_checkout_button',
				'display_on_product_page',
				1,
				__( 'Enable PeachPay on product pages', 'peachpay-for-woocommerce' ),
				'',
				array( 'input_type' => 'checkbox' )
			);
			?>
		</div>
		<div class="pp-page-setting pp-product-page-settings <?php print( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'display_on_product_page' ) ) ? '' : 'hide'; ?>">
			<?php
			peachpay_field_button_width_cb(
				array(
					'label_for' => 'button_width_product_page',
					'key'       => 'button_width_product_page',
				)
			);
			?>
			<div>
				<?php
				peachpay_field_button_alignment_cb(
					array(
						'label_for' => 'product_button_alignment',
						'key'       => 'product_button_alignment',
					)
				);
				?>
				<p class="description">
				<?php esc_html_e( 'Set the alignment of the PeachPay button on the product page.', 'peachpay-for-woocommerce' ); ?>
				</p>
			</div>
			<div>
				<?php peachpay_button_product_page_position_cb(); ?>
			</div>
		</div>
		<div>
			<h3 style="font-weight: bold;"><?php esc_html_e( 'Cart page', 'peachpay-for-woocommerce' ); ?></h3>
			<?php peachpay_button_display_html( array( 'cart_page' ) ); ?>
		</div>
		<div class="pp-page-setting pp-cart-page-settings <?php print( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'cart_page_enabled' ) ) ? '' : 'hide'; ?>">
			<?php
			peachpay_field_button_width_cb(
				array(
					'label_for' => 'button_width_cart_page',
					'key'       => 'button_width_cart_page',
				)
			);
			?>
			<div>
			<?php
				peachpay_field_button_alignment_cb(
					array(
						'label_for' => 'cart_button_alignment',
						'key'       => 'cart_button_alignment',
					)
				);
			?>
			<p class="description">
				<?php esc_html_e( 'Set the alignment of the PeachPay button on the cart page.', 'peachpay-for-woocommerce' ); ?>
			</p>
			</div>
		</div>
		<div>
			<h3 style="font-weight: bold;"><?php esc_html_e( 'Checkout page', 'peachpay-for-woocommerce' ); ?></h3>
			<?php peachpay_button_display_html( array( 'checkout_page' ) ); ?>
		</div>
		<div class="pp-page-setting pp-checkout-page-settings <?php print( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'checkout_page_enabled' ) ) ? '' : 'hide'; ?>">
			<?php
			peachpay_field_button_width_cb(
				array(
					'label_for' => 'button_width_checkout_page',
					'key'       => 'button_width_checkout_page',
				)
			);
			?>
			<div>
				<?php
				peachpay_admin_input(
					'checkout_header_text',
					'peachpay_express_checkout_button',
					'checkout_header_text',
					'',
					__( 'Header text', 'peachpay-for-woocommerce' ),
					__( 'Customize the text above the PeachPay button on the checkout page. By default this is blank.', 'peachpay-for-woocommerce' ),
					array( 'input_type' => 'text' )
				);
				?>
			</div>
			<div>
				<?php
				peachpay_admin_input(
					'checkout_subtext_text',
					'peachpay_express_checkout_button',
					'checkout_subtext_text',
					'',
					__( 'Additional text', 'peachpay-for-woocommerce' ),
					__( 'Customize the text below the PeachPay button on the checkout page. By default this is blank.', 'peachpay-for-woocommerce' ),
					array( 'input_type' => 'text' )
				);
				?>
			</div>
		</div>
		<div>
			<h3 style="font-weight: bold;"><?php esc_html_e( 'Mini/sidebar cart', 'peachpay-for-woocommerce' ); ?></h3>
			<?php peachpay_button_display_html( array( 'mini_cart' ) ); ?>
		</div>
		<div class="pp-save-button-section">
			<?php
			peachpay_reset_button_cb(
				array(
					'key'     => 'button_pages',
					'section' => 'Button by pages',
				)
			);
			?>
			<?php submit_button( 'Save changes', 'pp-button-primary' ); ?>
		</div>
	</div>
	<?php
}

/**
 * Renders all the fields for PeachPay button on shop page.
 */
function peachpay_shop_button_cb() {
	?>
	<div class="peachpay-setting-section">
		<div>
			<?php peachpay_button_display_html( array( 'floating_button' ) ); ?>
		</div>
		<div>
			<h4><?php esc_html_e( 'Icon', 'peachpay-for-woocommerce' ); ?></h4>
			<?php peachpay_field_button_icon_cb( 'floating_button' ); ?>
		</div>
		<div>
			<h4><?php esc_html_e( 'Button size', 'peachpay-for-woocommerce' ); ?></h4>
			<?php peachpay_floating_button_number_fields_cb( 'floating_button_size' ); ?>
		</div>
		<div>
			<h4><?php esc_html_e( 'Icon size', 'peachpay-for-woocommerce' ); ?></h4>
			<?php peachpay_floating_button_number_fields_cb( 'floating_button_icon_size' ); ?>
		</div>
		<div>
			<h3><?php esc_html_e( 'Preview', 'peachpay-for-woocommerce' ); ?></h3>
			<?php peachpay_field_shop_button_preview_cb(); ?>
		</div>
		<div>
			<?php
			peachpay_field_button_alignment_cb(
				array(
					'label_for' => 'floating_button_alignment',
					'key'       => 'floating_button_alignment',
				)
			);
			?>
			<p class="description">
				<?php esc_html_e( 'Set the alignment of the floating PeachPay button on the shop page', 'peachpay-for-woocommerce' ); ?>
			</p>
		</div>
		<div>
			<h4><?php esc_html_e( 'Bottom gap', 'peachpay-for-woocommerce' ); ?></h4>
			<?php peachpay_field_floating_button_position_cb( 'floating_button_bottom_gap' ); ?>
		</div>
		<div>
			<h4><?php esc_html_e( 'Side gap', 'peachpay-for-woocommerce' ); ?></h4>
			<?php peachpay_field_floating_button_position_cb( 'floating_button_side_gap' ); ?>
		</div>
		<div class="pp-save-button-section">
			<?php
			peachpay_reset_button_cb(
				array(
					'key'     => 'floating_button',
					'section' => 'Floating button',
				)
			);
			?>
			<?php submit_button( 'Save changes', 'pp-button-primary' ); ?>
		</div>
	<?php
}

/**
 * Gets the current branding background/foreground colors from the settings options and returns them as in-line CSS variables.
 */
function peachpay_get_branding_css_vars() {
	$bg_color   = peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'button_color', PEACHPAY_DEFAULT_BACKGROUND_COLOR );
	$text_color = peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'button_text_color', PEACHPAY_DEFAULT_TEXT_COLOR );
	$css_vars   = '--pp-button-background-color: ' . $bg_color . '; --pp-button-text-color: ' . $text_color . ';';
	return $css_vars;
}
