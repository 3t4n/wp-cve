<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Oxygen_WFTY_Order_Details_Widget
 */
if ( ! class_exists( 'Oxygen_WFTY_Order_Details_Widget' ) ) {

	#[AllowDynamicProperties]

  class Oxygen_WFTY_Order_Details_Widget extends WFFN_ThankYou_WC_HTML_Block_Oxy {
		public $slug = 'wffn_thankyou_order_details';
		protected $id = 'wffn_thankyou_order_details';


		/**
		 * Oxygen_WFTY_Order_Details_Widget constructor.
		 */
		public function __construct() {
			$this->name = __( 'Order Details', 'woofunnels-aero-checkout' );
			parent::__construct();
		}


		public function name() {
			return $this->name;
		}

		/**
		 * @param $template WFACP_Template_Common;
		 */
		public function setup_controls() {
			$this->register_heading_fields();
			$this->register_details_fields();
			$this->register_subscription_fields();
			$this->register_downloads_fields();
		}


		protected function register_heading_fields() {
			$defaults = WFFN_Core()->thank_you_pages->default_shortcode_settings();


			$tab_id          = $this->add_tab( __( 'Heading', 'funnel-builder' ) );
			$default_heading = isset( $defaults['order_details_heading'] ) ? $defaults['order_details_heading'] : __( 'Order Details', 'funnel-builder' );

			$this->add_text( $tab_id, 'order_details_heading', __( 'Heading' ), $default_heading );


			$this->add_typography( $tab_id, 'typography_heading', '.wffn_order_details_table .wfty_title' );


			do_action( 'wffn_additional_controls', $this );

		}

		protected function register_details_fields() {

			$product_tab_id = $this->add_tab( __( 'Details', 'funnel-builder' ) );
			$this->add_switcher( $product_tab_id, 'order_details_img', __( 'Show Images', 'funnel-builder' ), 'on' );

			$product_name_typography = [
				'.wfty_wrap .wfty_order_details .wfty_pro_list .wfty_leftDiv .wfty_p_name a',
				'.wfty_wrap .wfty_order_details .wfty_pro_list .wfty_leftDiv .wfty_p_name a span',
				'.wfty_wrap .wfty_order_details .wfty_pro_list .wfty_leftDiv .wfty_p_name .wfty_quantity_value_box *'
			];
			$divider_color           = [
				'.wffn_order_details_table table',
				'.wfty_wrap .wfty_order_details .wfty_pro_list',
				'.wfty_order_details table tfoot tr:last-child th',
				'.wfty_order_details table tfoot tr:last-child td'
			];

			$this->add_border_color( $product_tab_id, 'divider_color', implode( ',', $divider_color ), '#dddddd', __( 'Divider', 'funnel-builder' ) );

			$this->add_typography( $product_tab_id, 'product_name_typography', implode( ',', $product_name_typography ), __( 'Product Typography', 'funnel-builder' ) );


			$product_variation_typography = [
				'.wfty_wrap .wfty_order_details .wfty_pro_list .wfty_leftDiv .wfty_p_name .wfty_info *',
				'.wfty_wrap .wfty_order_details .wfty_pro_list .wfty_leftDiv .wfty_p_name .wfty_info p',
				'.wfty_wrap .wfty_order_details .wfty_pro_list .wfty_leftDiv .wfty_p_name .wfty_info span',

			];
			$this->add_typography( $product_tab_id, 'product_variation_typography', implode( ',', $product_variation_typography ), __( 'Variation Typography', 'funnel-builder' ) );

			$product_price_typography = [
				'.wfty_wrap .wfty_order_details .wfty_pro_list .wfty_rightDiv *',
				'.wfty_wrap .wfty_order_details .wfty_pro_list .wfty_rightDiv span',
				'.wfty_wrap .wfty_order_details .wfty_pro_list .wfty_rightDiv span.amount',
				'.wfty_wrap .wfty_order_details .wfty_pro_list .wfty_rightDiv span bdi',

			];
			$this->add_typography( $product_tab_id, 'product_price_typography', implode( ',', $product_price_typography ), __( 'Price Typography', 'funnel-builder' ) );

			$subtotal_tab_id           = $this->add_tab( __( 'Subtotal', 'funnel-builder' ) );
			$subtotal_label_typography = [
				'.wffn_order_details_table .wfty_pro_list_cont table tr:not(:last-child) th *',
				'.wffn_order_details_table .wfty_pro_list_cont table tr:not(:last-child) th'
			];
			$this->add_typography( $subtotal_tab_id, 'subtotal_label_typography', implode( ',', $subtotal_label_typography ), __( 'Label Typography', 'funnel-builder' ) );

			$subtotal_price_typography = [
				'.wffn_order_details_table .wfty_pro_list_cont table tr:not(:last-child) td *',
				'.wffn_order_details_table .wfty_pro_list_cont table tr:not(:last-child) td span',
				'.wffn_order_details_table .wfty_pro_list_cont table tr:not(:last-child) td',
			];
			$this->add_typography( $subtotal_tab_id, 'subtotal_price_typography', implode( ',', $subtotal_price_typography ), __( 'Price Typography', 'funnel-builder' ) );


			$total_tab_id           = $this->add_tab( __( 'Total', 'funnel-builder' ) );
			$total_label_typography = [
				'.wffn_order_details_table .wfty_pro_list_cont table tr:last-child th *',
				'.wffn_order_details_table .wfty_pro_list_cont table tr:last-child th'
			];

			$this->add_typography( $total_tab_id, 'total_label_typography', implode( ',', $total_label_typography ), __( 'Label Typography', 'funnel-builder' ) );
			$total_price_typography = [
				'.wffn_order_details_table .wfty_pro_list_cont table tr:last-child td *',
				'.wffn_order_details_table .wfty_pro_list_cont table tr:last-child td span',
				'.wffn_order_details_table .wfty_pro_list_cont table tr:last-child td',
				'.wffn_order_details_table .wfty_pro_list_cont table tr:last-child td bdi',
			];
			$this->add_typography( $total_tab_id, 'total_price_typography', implode( ',', $total_price_typography ), __( 'Price Typography', 'funnel-builder' ) );


			do_action( 'wffn_additional_controls', $this );

		}

		protected function register_subscription_fields() {
			$defaults = WFFN_Core()->thank_you_pages->default_shortcode_settings();
			$tab_id   = $this->add_tab( __( 'Subscription', 'funnel-builder' ) );


			$default_heading = isset( $defaults['order_subscription_heading'] ) ? $defaults['order_subscription_heading'] : __( 'Subscription', 'funnel-builder' );
			$this->add_sub_heading( $tab_id, __( 'This section will only show up in case of order will have downloads.', 'funnel-builder' ) );
			$this->add_text( $tab_id, 'order_subscription_heading', __( 'Heading' ), $default_heading );
			$this->add_switcher( $tab_id, 'order_subscription_preview', __( 'Show Subscription Preview' ), 'off' );
			$this->add_typography( $tab_id, 'subscription_typography_heading', '.wffn_order_details_table .wfty_wrap .wfty_subscription table *, .wffn_order_details_table .wfty_wrap .wfty_subscription table tr th, .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td, .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td:before' );


			$this->add_heading( $tab_id, __( 'Button', 'funnel-builder' ) );
			$this->add_color( $tab_id, 'button_text_color', '  .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td.subscription-actions a', __( 'Text', 'funnel-builder' ), '#fff' );
			$this->add_background_color( $tab_id, 'background_color', ' .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td.subscription-actions a', '#70dc1d', __( 'Background', 'funnel-builder' ) );

			$this->add_color( $tab_id, 'hover_color', ' .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td.subscription-actions:hover a', __( 'Text Hover', 'funnel-builder' ), '#fff' );
			$this->add_background_color( $tab_id, 'button_background_hover_color', ' .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td.subscription-actions:hover a', '#89e047', __( 'Background Hover', 'funnel-builder' ) );


			do_action( 'wffn_additional_controls', $this );

		}


		protected function register_downloads_fields() {
			$defaults = WFFN_Core()->thank_you_pages->default_shortcode_settings();
			$tab_id   = $this->add_tab( __( 'Downloads', 'funnel-builder' ) );


			$default_heading = isset( $defaults['order_download_heading'] ) ? $defaults['order_download_heading'] : __( 'Downloads', 'funnel-builder' );
			$this->add_sub_heading( $tab_id, __( 'This section will only show up in case of order will have downloads.', 'funnel-builder' ) );

			$selector = [
				'.wfty_wrap table.wfty_order_downloads tr *',
				'.wfty_wrap table.wfty_order_downloads tr td.download-file a',
				'.wfty_wrap .wfty_order_downloads ',
				'.wfty_wrap .wfty_order_downloads tr td.download-product',
				'.wfty_wrap table.wfty_order_downloads tr th',
				'.wfty_wrap table.wfty_order_downloads tr td'
			];


			$this->add_text( $tab_id, 'order_download_heading', __( 'Heading' ), $default_heading );
			$this->add_text( $tab_id, 'order_downloads_btn_text', __( 'Download Button Text' ), $defaults['order_downloads_btn_text'] );
			$this->add_switcher( $tab_id, 'order_download_preview', __( 'Show Download Preview' ), 'off' );
			$this->add_switcher( $tab_id, 'order_downloads_show_file_downloads', __( 'Show File Downloads Column' ), 'off' );
			$this->add_switcher( $tab_id, 'order_downloads_show_file_expiry', __( 'Show File Expiry Column' ), 'off' );


			$this->add_typography( $tab_id, 'subscription_typography_heading', implode( ',', $selector ) );

			$this->add_heading( $tab_id, __( 'Button', 'funnel-builder' ) );
			$this->add_color( $tab_id, 'download_button_text_color', ' .wfty_wrap table.wfty_order_downloads tr td.download-file a', __( 'Text', 'funnel-builder' ), '#fff' );
			$this->add_background_color( $tab_id, 'download_background_color', ' .wfty_wrap table.wfty_order_downloads tr td.download-file a', '#70dc1d', __( 'Background', 'funnel-builder' ) );

			$this->add_color( $tab_id, 'tab_download_button_hover', ' .wfty_wrap table.wfty_order_downloads tr td.download-file:hover a', __( 'Text Hover', 'funnel-builder' ), '#fff' );
			$this->add_background_color( $tab_id, 'download_hover_color', ' .wfty_wrap table.wfty_order_downloads tr td.download-file:hover a', '#89e047', __( 'Background Hover', 'funnel-builder' ) );


			$this->add_sub_heading( $tab_id, __( 'This section will only show up in case of order will have downloads.', 'funnel-builder' ) );
			do_action( 'wffn_additional_controls', $this );

		}


		protected function html( $settings, $defaults, $content ) {//phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter

			$order_heading_text         = $settings['order_details_heading'];
			$order_subscription_heading = isset( $settings['order_subscription_heading'] ) ? $settings['order_subscription_heading'] : '';

			$download_btn_text    = $settings['order_downloads_btn_text'];
			$divider_color        = $settings['divider_color'];
			$show_column_download = "false";

			if ( $settings['order_downloads_show_file_downloads'] === 'on' ) {
				$show_column_download = "true";
			}

			$show_column_file_expiry = "false";

			if ( $settings['order_downloads_show_file_expiry'] === 'on' ) {
				$show_column_file_expiry = "true";
			}

			$order_download_heading = isset( $settings['order_download_heading'] ) ? $settings['order_download_heading'] : '';


			$order_details_img = true;
			if ( isset( $settings['order_details_img'] ) && "off" === $settings['order_details_img'] ) {
				$order_details_img = false;
			}
			$class = ( ! isset( $settings['order_download_preview'] ) || 'on' !== $settings['order_download_preview'] ) ? 'wfty-hide-download' : '';
			$class .= ( ! isset( $settings['order_subscription_preview'] ) || 'on' !== $settings['order_subscription_preview'] ) ? ' wfty-hide-subscription' : '';

			?>
            <div class='oxy-order-details-wrapper <?php echo esc_attr( $class ); ?>'>

				<?php
				if ( ! empty( $divider_color ) ) {
					?>
                    <style>
                        #
                        <?php echo $settings['selector'];//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        .wffn_order_details_table table {
                            border-color: <?php echo $divider_color;//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
                        }

                        #
                        <?php echo $settings['selector'];//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        .wfty_pro_list_cont .wfty_pro_list .wfty-hr {
                            color: <?php echo $divider_color;//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
                            background-color: <?php echo $divider_color;//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
                            opacity: 1;
                            border: none;
                        }

                        #
                        <?php echo $settings['selector'];//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        .wfty_order_details table tfoot tr:last-child th,

                        #
                        <?php echo $settings['selector'];//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                        .wfty_order_details table tfoot tr:last-child td {
                            border-top-color: <?php echo $divider_color;//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        }
                    </style>
					<?php
				}


				echo do_shortcode( '[wfty_order_details order_details_img="' . $order_details_img . '" order_details_heading="' . $order_heading_text . '" order_subscription_heading="' . $order_subscription_heading . '" order_download_heading="' . $order_download_heading . '" order_downloads_btn_text="' . $download_btn_text . '" order_downloads_show_file_downloads="' . $show_column_download . '"  order_downloads_show_file_expiry="' . $show_column_file_expiry . '"]' );
				?>
            </div>
			<?php
		}


	}

	new Oxygen_WFTY_Order_Details_Widget;
}