<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH\PreOrder\Templates\Admin
 * @author YITH <plugins@yithemes.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Vars used on this template.
 *
 * @var WC_Product $product                      The product object.
 * @var int        $loop                         Position in the loop.
 * @var string     $pre_order_status             Whether Pre-Order status is enabled or not for this product.
 * @var string     $availability_date_mode       Type of availability date used.
 * @var string     $availability_date            Standard availability date. Exact date and exact time.
 * @var string     $price_mode                   Price mode.
 * @var string     $preorder_price               Pre-Order price.
 * @var string     $discount_percentage          Discount the Pre-Order price by percentage.
 * @var string     $discount_fixed               Discount the Pre-Order price by a fixed amount.
 * @var string     $increase_percentage          Increase the Pre-Order price by percentage.
 * @var string     $increase_fixed               Increase the Pre-Order price by a fixed amount.
 */

$offset_label = '(' . ywpo_get_timezone_offset_label() . ')';
?>
<div class="show_if_variation_pre_order woocommerce_options_panel">
	<div class="options_group ywpo_options_group">
		<h4 class="ywpo_options_panel_title"><?php esc_html_e( 'Pre-order options', 'yith-pre-order-for-woocommerce' ); ?></h4>
		<fieldset class="form-field yith-plugin-ui ywpo_preorder onoff">
			<legend for="_ywpo_preorder"><?php esc_html_e( 'Manage pre-order options for this product', 'yith-pre-order-for-woocommerce' ); ?></legend>
			<?php
			yith_plugin_fw_get_field(
				array(
					'type'  => 'onoff',
					'id'    => '_ywpo_preorder[' . $loop . ']',
					'name'  => '_ywpo_preorder[' . $loop . ']',
					'value' => $pre_order_status,
				),
				true
			);
			?>
			<span class="description"><?php esc_html_e( 'Enable to set pre-order options for this product.', 'yith-pre-order-for-woocommerce' ); ?></span>
		</fieldset>
		<div class="form-field" data-deps-on="_ywpo_preorder[<?php echo esc_attr( $loop ); ?>]" data-deps-val="yes">
			<?php
			woocommerce_wp_radio(
				array(
					'label'         => __( 'Set product availability date', 'yith-pre-order-for-woocommerce' ),
					'value'         => $availability_date_mode,
					'id'            => '_ywpo_availability_date_mode[' . $loop . ']',
					'name'          => '_ywpo_availability_date_mode[' . $loop . ']',
					'wrapper_class' => 'ywpo_availability_date_mode',
					'default'       => 'no_date',
					'options'       => array(
						'no_date' => __( 'No date - end pre-order mode manually', 'yith-pre-order-for-woocommerce' ),
						'date'    => __( 'Choose a date from the calendar', 'yith-pre-order-for-woocommerce' ),
					),
				)
			);
			?>
			<p class="form-field yith-plugin-ui _ywpo_availability_date_mode-description">
				<span class="description"><?php esc_html_e( 'Choose how to manage the availability date.', 'yith-pre-order-for-woocommerce' ); ?></span>
			</p>

			<fieldset class="form-field yith-plugin-ui _ywpo_availability_date" data-deps-on="_ywpo_availability_date_mode[<?php echo esc_attr( $loop ); ?>]" data-deps-val="date">
				<legend for="_ywpo_for_sale_date">
					<?php echo esc_html__( 'Availability date and time', 'yith-pre-order-for-woocommerce' ) . ' ' . esc_attr( $offset_label ); ?>
				</legend>
				<input type="text" class="short ywpo_datetimepicker" name="_ywpo_for_sale_date[<?php echo esc_attr( $loop ); ?>]"
					value="<?php echo esc_attr( $availability_date ); ?>" title="YYYY/MM/DD hh:mm:ss" maxlength="16" autocomplete="off" style="width: 300px;" />
				<span class="yith-icon yith-icon-calendar yith-icon--right-overlay"></span>
				<?php echo wc_help_tip( esc_html__( 'Set the date when the product will become available for sale. The timezone used is the WordPress local timezone. Settings -> General -> Timezone.', 'yith-pre-order-for-woocommerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
				<span class="ywpo-description"><?php esc_html_e( 'Set the date when this product will become available.', 'yith-pre-order-for-woocommerce' ); ?></span>
			</fieldset>

			<fieldset class="form-field _ywpo_price_mode">
				<legend for="_ywpo_price_mode"><?php esc_html_e( 'Pre-order price', 'yith-pre-order-for-woocommerce' ); ?></legend>
				<?php
				yith_plugin_fw_get_field(
					array(
						'id'      => '_ywpo_price_mode[' . $loop . ']',
						'name'    => '_ywpo_price_mode[' . $loop . ']',
						'value'   => $price_mode,
						'type'    => 'select',
						'options' => array(
							'default'             => __( 'Use the selling price', 'yith-pre-order-for-woocommerce' ),
							'fixed'               => __( 'Set a fixed pre-order price', 'yith-pre-order-for-woocommerce' ),
							'discount_percentage' => __( 'Discount a percentage % of the selling price', 'yith-pre-order-for-woocommerce' ),
							'discount_fixed'      => __( 'Discount a fixed amount of the selling price', 'yith-pre-order-for-woocommerce' ),
							'increase_percentage' => __( 'Increase a percentage % of the selling price', 'yith-pre-order-for-woocommerce' ),
							'increase_fixed'      => __( 'Increase a fixed amount of the selling price', 'yith-pre-order-for-woocommerce' ),
						),
					),
					true
				);
				?>
			</fieldset>
			<p class="form-field yith-plugin-ui _ywpo_price_mode-description">
				<span class="description"><?php esc_html_e( 'Choose how to manage the pre-order price.', 'yith-pre-order-for-woocommerce' ); ?></span>
			</p>

			<fieldset class="form-field _ywpo_preorder_price" data-deps-on="_ywpo_price_mode[<?php echo esc_attr( $loop ); ?>]" data-deps-val="fixed">
				<legend for="_ywpo_preorder_price"><?php echo esc_html__( 'Price', 'yith-pre-order-for-woocommerce' ) . ' (' . esc_attr( get_woocommerce_currency_symbol() ) . ')'; ?></legend>
				<?php
				yith_plugin_fw_get_field(
					array(
						'id'                => '_ywpo_preorder_price[' . $loop . ']',
						'name'              => '_ywpo_preorder_price[' . $loop . ']',
						'type'              => 'text',
						'value'             => wc_format_localized_price( $preorder_price ),
						'class'             => 'wc_input_price',
						'custom_attributes' => 'style="width:80px"',
					),
					true
				);
				?>
				<span class="ywpo-description"><?php esc_html_e( 'Set the pre-order price for this product.', 'yith-pre-order-for-woocommerce' ); ?></span>
			</fieldset>

			<fieldset class="form-field ywpo_preorder_discount_percentage" data-deps-on="_ywpo_price_mode[<?php echo esc_attr( $loop ); ?>]" data-deps-val="discount_percentage">
				<legend for="_ywpo_preorder_discount_percentage"><?php esc_html_e( 'Discount on selling price', 'yith-pre-order-for-woocommerce' ); ?></legend>
				<span class="wrap">
					<input type="number" name="_ywpo_preorder_discount_percentage[<?php echo esc_attr( $loop ); ?>]" id="_ywpo_preorder_discount_percentage[<?php echo esc_attr( $loop ); ?>]"
					min="1" style="width: 80px; margin-right: 15px;" value="<?php echo esc_attr( $discount_percentage ); ?>" />
					<span>%</span>
				</span>
				<span class="ywpo-description"><?php esc_html_e( 'Set the pre-order price for this product.', 'yith-pre-order-for-woocommerce' ); ?></span>
			</fieldset>

			<fieldset class="form-field ywpo_preorder_discount_fixed" data-deps-on="_ywpo_price_mode[<?php echo esc_attr( $loop ); ?>]" data-deps-val="discount_fixed">
				<legend for="_ywpo_preorder_discount_fixed"><?php esc_html_e( 'Discount on selling price', 'yith-pre-order-for-woocommerce' ); ?></legend>
				<span class="wrap">
					<input type="number" name="_ywpo_preorder_discount_fixed[<?php echo esc_attr( $loop ); ?>]" id="_ywpo_preorder_discount_fixed[<?php echo esc_attr( $loop ); ?>]"
					min="1" style="width: 80px; margin-right: 15px;" value="<?php echo esc_attr( $discount_fixed ); ?>" />
					<span><?php echo esc_html( get_woocommerce_currency_symbol() ); ?></span>
				</span>
				<span class="ywpo-description"><?php esc_html_e( 'Set the pre-order price for this product.', 'yith-pre-order-for-woocommerce' ); ?></span>
			</fieldset>

			<fieldset class="form-field ywpo_preorder_increase_percentage" data-deps-on="_ywpo_price_mode[<?php echo esc_attr( $loop ); ?>]" data-deps-val="increase_percentage">
				<legend for="_ywpo_preorder_increase_percentage"><?php esc_html_e( 'Increase on selling price', 'yith-pre-order-for-woocommerce' ); ?></legend>
				<span class="wrap">
					<input type="number" name="_ywpo_preorder_increase_percentage[<?php echo esc_attr( $loop ); ?>]" id="_ywpo_preorder_increase_percentage[<?php echo esc_attr( $loop ); ?>]"
					min="1" style="width: 80px; margin-right: 15px;" value="<?php echo esc_attr( $increase_percentage ); ?>" />
					<span>%</span>
				</span>
				<span class="ywpo-description"><?php esc_html_e( 'Set the pre-order price for this product.', 'yith-pre-order-for-woocommerce' ); ?></span>
			</fieldset>

			<fieldset class="form-field ywpo_preorder_increase_fixed" data-deps-on="_ywpo_price_mode[<?php echo esc_attr( $loop ); ?>]" data-deps-val="increase_fixed">
				<legend for="_ywpo_preorder_increase_fixed"><?php esc_html_e( 'Increase on selling price', 'yith-pre-order-for-woocommerce' ); ?></legend>+
				<span class="wrap">
					<input type="number" name="_ywpo_preorder_increase_fixed[<?php echo esc_attr( $loop ); ?>]" id="_ywpo_preorder_increase_fixed[<?php echo esc_attr( $loop ); ?>]"
					min="1" style="width: 80px; margin-right: 15px;" value="<?php echo esc_attr( $increase_fixed ); ?>" />
					<span><?php echo esc_html( get_woocommerce_currency_symbol() ); ?></span>
				</span>
				<span class="ywpo-description"><?php esc_html_e( 'Set the pre-order price for this product.', 'yith-pre-order-for-woocommerce' ); ?></span>
			</fieldset>
		</div>
	</div>
</div>
