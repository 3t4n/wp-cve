<?php

#[AllowDynamicProperties]

  class WFTY_Order_Details extends WFTY_Divi_HTML_BLOCK {
	public $slug = 'wfty_order_details';
	public $main_css = '%%order_class%%.et_wfty_order_details';

	public function __construct() {
		parent::__construct();
		add_action( 'wp_footer', [ $this, 'localize_locals' ] );
	}

	public function setup_data() {
		$tab_id = $this->add_tab( __( 'Order Details', 'funnel-builder' ), 5 );
		$this->add_text( $tab_id, 'order_details_heading', __( 'Heading', 'funnel-builder' ), __( 'Order Details', 'funnel-builder' ) );

		$sub_id = $this->add_tab( __( 'Subscription', 'funnel-builder' ), 5 );
		$this->add_text( $sub_id, 'order_subscription_heading', __( 'Heading', 'funnel-builder' ), __( 'Subscription', 'funnel-builder' ) );
		$this->add_switcher( $sub_id, 'order_subscription_preview', __( 'Show Subscription Preview', 'funnel-builder' ), 'off' );


		$down_id = $this->add_tab( __( 'Download', 'funnel-builder' ), 5 );
		$this->add_text( $down_id, 'order_download_heading', __( 'Heading', 'funnel-builder' ), __( 'Downloads', 'funnel-builder' ) );
		$this->add_text( $down_id, 'order_downloads_btn_text', __( 'Download Button Text', 'funnel-builder' ), __( 'Download', 'funnel-builder' ) );
		$this->add_switcher( $down_id, 'order_download_preview', __( 'Show Download Preview', 'funnel-builder' ), 'off' );
		$this->add_switcher( $down_id, 'order_downloads_file', __( 'Show File Downloads Column', 'funnel-builder' ), 'off' );
		$this->add_switcher( $down_id, 'order_downloads_file_expiry', __( 'Show File Expiry Column', 'funnel-builder' ), 'off' );

		$this->style_field();

	}

	private function style_field() {

		$key = "wfty_order_details";

		$font_side_default = array(
			'font_size'   => array(
				'default' => '24px',
				'unit'    => 'px'
			),
			'line_height' => array(
				'default' => '1.5',
				'unit'    => 'em'
			),
		);


		$head_id = $this->add_tab( __( 'Heading', 'funnel-builder' ), 2 );

		$this->add_typography( $head_id, $key . '_heading_typography', '%%order_class%% .wffn_order_details_table .wfty_title, %%order_class%% .wffn_order_details_table .wc-bacs-bank-details-heading', '', '', $font_side_default );

		$this->add_color( $head_id, $key . '_heading_color', '%%order_class%% .wffn_order_details_table .wfty_title, %%order_class%% .wffn_order_details_table .wc-bacs-bank-details-heading', __( 'Color', 'funnel-builder' ), '#000000' );

		$this->add_text_alignments( $head_id, 'align', '%%order_class%% .wffn_order_details_table .wfty_title, %%order_class%% .wffn_order_details_table .wc-bacs-bank-details-heading', '', 'left' );


		$detail_id = $this->add_tab( __( 'Details', 'funnel-builder' ), 2 );

		$this->add_heading( $detail_id, __( 'Product', 'funnel-builder' ) );

		$this->add_switcher( $detail_id, 'order_details_img', __( 'Show Images', 'funnel-builder' ), 'on' );

		$font_side_default['font_size']['default'] = '15px';
		$this->add_typography( $detail_id, $key . '_product_typography', '%%order_class%% .wffn_order_details_table .wfty_pro_list_cont .wfty_pro_list *, %%order_class%% .wffn_order_details_table .wfty_pro_list_cont.wfty_show_images .wfty_pro_list .wfty_leftDiv a *, %%order_class%% .wffn_order_details_table .wfty_pro_list_cont.wfty_show_images .wfty_pro_list .wfty_leftDiv .wfty_info, %%order_class%% .wffn_order_details_table .wfty_pro_list_cont.wfty_show_images .wfty_pro_list .wfty_rightDiv *', '', '', $font_side_default );

		$this->add_color( $detail_id, $key . '_product_text_color', '%%order_class%% .wffn_order_details_table .wfty_pro_list_cont.wfty_hide_images .wfty_pro_list *, %%order_class%% .wffn_order_details_table .wfty_pro_list_cont.wfty_show_images .wfty_pro_list .wfty_leftDiv a *, %%order_class%% .wffn_order_details_table .wfty_pro_list_cont.wfty_show_images .wfty_pro_list .wfty_leftDiv .wfty_info, %%order_class%% .wffn_order_details_table .wfty_pro_list_cont.wfty_show_images .wfty_pro_list .wfty_rightDiv *', __( 'Color', 'funnel-builder' ), '#565656' );

		$this->add_heading( $detail_id, __( 'Subtotal', 'funnel-builder' ) );

		$this->add_typography( $detail_id, $key . '_subtotal_typography', '%%order_class%% .wffn_order_details_table .wfty_pro_list_cont table tr:not(:last-child) *', '', '', $font_side_default );
		$this->add_color( $detail_id, $key . '_subtotal_text_color', '%%order_class%% .wffn_order_details_table .wfty_pro_list_cont table tr:not(:last-child) *', __( 'Color', 'funnel-builder' ), '#565656' );

		$this->add_heading( $detail_id, __( 'Total', 'funnel-builder' ) );

		$font_side_default['font_size']['default'] = '20px';
		$this->add_typography( $detail_id, $key . '_total_typography', '%%order_class%% .wffn_order_details_table .wfty_pro_list_cont table tr:last-child *', '', '', $font_side_default );

		$this->add_color( $detail_id, $key . '_total_text_color', '%%order_class%% .wffn_order_details_table .wfty_pro_list_cont table tr:last-child *', __( 'Color', 'funnel-builder' ), '#565656' );

		$this->add_heading( $detail_id, __( 'Variation', 'funnel-builder' ) );

		$font_side_default['font_size']['default'] = '12px';
		$this->add_typography( $detail_id, $key . '_variation_typography', '%%order_class%% .wffn_order_details_table .wfty_pro_list_cont .wfty_pro_list .wfty_info *', '', '', $font_side_default );
		$this->add_color( $detail_id, $key . '_variation_text_color', '%%order_class%% .wffn_order_details_table .wfty_pro_list_cont .wfty_pro_list .wfty_info *', __( 'Color', 'funnel-builder' ), '#000000' );

		$this->add_heading( $detail_id, __( 'Divider', 'funnel-builder' ) );

		$this->add_border_color( $detail_id, $key . '_divider_color', '%%order_class%% .wfty_wrap .wfty_order_details table tfoot tr:last-child td, %%order_class%% .wfty_wrap .wfty_order_details table tfoot tr:last-child th, %%order_class%% .wfty_wrap .wfty_order_details table', '#dddddd', __( 'Color', 'funnel-builder' ) );

		$subs_id = $this->add_tab( __( 'Subscription', 'funnel-builder' ), 2 );

		$this->add_heading( $subs_id, __( 'Details', 'funnel-builder' ) );

		$font_side_default['font_size']['default'] = '15px';
		$this->add_typography( $subs_id, $key . '_subscription_typography', '%%order_class%% .wffn_order_details_table .wfty_wrap .wfty_subscription table *, %%order_class%% .wffn_order_details_table .wfty_wrap .wfty_subscription table tr th, %%order_class%% .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td, %%order_class%% .wffn_order_details_table .wfty_wrap .wfty_subscription .shop_table.shop_table_responsive tr td::before', '', '', $font_side_default );

		$this->add_color( $subs_id, $key . '_subscription_text_color', '%%order_class%% .wffn_order_details_table .wfty_wrap .wfty_subscription table tr th, %%order_class%% .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td:not(:last-child), %%order_class%% .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td:not(:last-child) *', __( 'Text Color', 'funnel-builder' ), '#565656' );

		$this->add_heading( $subs_id, __( 'Button', 'funnel-builder' ) );

		$controls_tabs_id = $this->add_controls_tabs( $subs_id, "Button Color" );

		$colors_field = [];

		$colors_field[] = $this->add_color( $subs_id, $key . '_subs_button_text_color', '%%order_class%% .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td.subscription-actions a', __( 'Label', 'funnel-builder' ), '#ffffff' );

		$colors_field[] = $this->add_background_color( $subs_id, $key . '_subs_button_background_color', '%%order_class%% .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td.subscription-actions a', '#70dc1d', __( 'Background', 'funnel-builder' ) );

		$this->add_controls_tab( $controls_tabs_id, "Normal", $colors_field );

		$colors_field = [];

		$colors_field[] = $this->add_color( $subs_id, $key . '_subs_button_text_hover_color', '%%order_class%% .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td.subscription-actions:hover a', __( 'Label', 'funnel-builder' ), '#ffffff' );

		$colors_field[] = $this->add_background_color( $subs_id, $key . '_subs_button_background_hover_color', '%%order_class%% .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td.subscription-actions:hover a', '#89e047', __( 'Background', 'funnel-builder' ) );

		$this->add_controls_tab( $controls_tabs_id, "Hover", $colors_field );

		$down_id = $this->add_tab( __( 'Download', 'funnel-builder' ), 2 );

		$this->add_heading( $down_id, __( 'Details', 'funnel-builder' ) );

		$font_side_default['font_size']['default'] = '15px';
		$this->add_typography( $down_id, $key . '_download_typography', '%%order_class%% .wffn_order_details_table .wfty_wrap .wfty_order_download table *, %%order_class%% .wffn_order_details_table .wfty_wrap .wfty_order_download table tr th, %%order_class%% .wffn_order_details_table .wfty_wrap .wfty_order_download table tr td,   %%order_class%% .wffn_order_details_table .wfty_wrap .wfty_order_download .shop_table.shop_table_responsive tr td::before', '', '', $font_side_default );

		$this->add_color( $down_id, $key . '_download_text_color', '%%order_class%% .wffn_order_details_table .wfty_wrap .wfty_order_download table tr th, %%order_class%% .wffn_order_details_table .wfty_wrap .wfty_order_download table tr td:not(:last-child), %%order_class%% .wffn_order_details_table .wfty_wrap .wfty_order_download table tr td:not(:last-child) *', __( 'Text Color', 'funnel-builder' ), '#565656' );

		$this->add_heading( $down_id, __( 'Button', 'funnel-builder' ) );

		$controls_tabs_id = $this->add_controls_tabs( $down_id, "Button Color" );

		$colors_field = [];

		$colors_field[] = $this->add_color( $down_id, $key . '_download_button_text_color', '%%order_class%% .wffn_order_details_table .wfty_wrap .wfty_order_download table tr td.download-file a', __( 'Label', 'funnel-builder' ), '#ffffff' );

		$colors_field[] = $this->add_background_color( $down_id, $key . '_download_button_background_color', '%%order_class%% .wffn_order_details_table .wfty_wrap .wfty_order_download table tr td.download-file a', '#70dc1d', __( 'Background', 'funnel-builder' ) );

		$this->add_controls_tab( $controls_tabs_id, "Normal", $colors_field );

		$colors_field = [];

		$colors_field[] = $this->add_color( $down_id, $key . '_download_button_text_hover_color', '%%order_class%% .wffn_order_details_table .wfty_wrap .wfty_order_download table tr td.download-file a:hover', __( 'Label', 'funnel-builder' ), '#ffffff' );

		$colors_field[] = $this->add_background_color( $down_id, $key . '_download_button_background_hover_color', '%%order_class%% .wffn_order_details_table .wfty_wrap .wfty_order_download table tr td.download-file a:hover', '#89e047', __( 'Background', 'funnel-builder' ) );

		$this->add_controls_tab( $controls_tabs_id, "Hover", $colors_field );

	}

	public function localize_locals() {
		$exp_date = gmdate( 'Y-m-d H:i:s', strtotime( '+10 days' ) );
		if ( ! empty( $exp_date ) ) {
			$exp_date = date_i18n( get_option( 'date_format' ), strtotime( $exp_date ) );
		} else {
			$exp_date = __( 'Never', 'funnel-builder' );
		}

		$data = array(
			'price'          => '12.00',
			'total_price'    => '12.00',
			'shipping_price' => '3.00',
			'shipping'       => 'false',
			'currency'       => html_entity_decode( get_woocommerce_currency_symbol() ),
			'img_url'        => WC()->plugin_url() . '/assets/images/placeholder.png',
			'pro_name'       => __( 'Test Product', 'funnel-builder' ),
			'sub_head'       => __( 'Subtotal', 'funnel-builder' ),
			'ship_head'      => __( 'Shipping', 'funnel-builder' ),
			'payment_head'   => __( 'Payment method', 'funnel-builder' ),
			'payment_text'   => __( 'Credit Card', 'funnel-builder' ),
			'total_head'     => __( 'Total', 'funnel-builder' ),
			'subs_head'      => __( 'Related Subscriptions', 'funnel-builder' ),
			'subs_th_title'  => __( 'Subscription', 'funnel-builder' ),
			'subs_th_pay'    => __( 'Next Payment', 'funnel-builder' ),
			'subs_th_tot'    => __( 'Total', 'funnel-builder' ),
			'subs_th_act'    => __( 'Action', 'funnel-builder' ),
			'subs_td_title'  => __( 'Active', 'funnel-builder' ),
			'subs_td_pay'    => __( 'In 24 hours', 'funnel-builder' ),
			'subs_td_tot'    => __( '7.50 /day', 'funnel-builder' ),
			'subs_td_act'    => __( 'View', 'funnel-builder' ),
			'down_th_file'   => __( 'File', 'funnel-builder' ),
			'down_th_down'   => __( 'Downloads remaining', 'funnel-builder' ),
			'down_th_exp'    => __( 'Expires', 'funnel-builder' ),
			'down_td_file'   => __( 'Your_file_name.pdf', 'funnel-builder' ),
			'down_td_exp'    => $exp_date,
		);

		$shipping_option = get_option( 'woocommerce_ship_to_countries' );
		if ( 'disabled' !== $shipping_option ) {
			$data['total_price'] += $data['shipping_price'];
			$data['total_price'] = $data['total_price'] . '.00';
			$data['shipping']    = 'true';
		}

		$data = implode( ', ', array_map( function ( $v, $k ) {
			return sprintf( "%s:'%s'", $k, $v );
		}, $data, array_keys( $data ) ) );
		?>
        <script>
            let wftyDiviOrder = {<?php echo $data; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>};
        </script>
		<?php
	}

	protected function html() {
		$settings                   = $this->props;
		$order_heading_text         = $settings['order_details_heading'];
		$order_subscription_heading = $settings['order_subscription_heading'];
		$order_download_heading     = $settings['order_download_heading'];
		$download_btn_text          = $settings['order_downloads_btn_text'];
		$show_column_download       = ( ! isset( $settings['order_downloads_file'] ) || 'on' !== $settings['order_downloads_file'] ) ? 'false' : 'true';
		$show_column_file_expiry    = ( ! isset( $settings['order_downloads_file_expiry'] ) || 'on' !== $settings['order_downloads_file_expiry'] ) ? 'false' : 'true';
		$order_details_img          = ( ! isset( $settings['order_details_img'] ) || 'on' !== $settings['order_details_img'] ) ? 'false' : 'true';
		$class                      = ( ! isset( $settings['order_download_preview'] ) || 'on' !== $settings['order_download_preview'] ) ? 'wfty-hide-download' : '';
		$class                      .= ( ! isset( $settings['order_subscription_preview'] ) || 'on' !== $settings['order_subscription_preview'] ) ? ' wfty-hide-subscription' : '';

		ob_start();
		?>
		<?php
		echo '<div class="' . esc_attr( $class ) . '">';
		echo do_shortcode( '[wfty_order_details order_details_img="' . $order_details_img . '" order_details_heading="' . $order_heading_text . '" order_subscription_heading="' . $order_subscription_heading . '" order_download_heading="' . $order_download_heading . '" order_downloads_btn_text="' . $download_btn_text . '" order_downloads_show_file_downloads="' . $show_column_download . '"  order_downloads_show_file_expiry="' . $show_column_file_expiry . '"]' );
		echo '</div>';
		?>
		<?php
		return ob_get_clean();
	}


}

return new WFTY_Order_Details;