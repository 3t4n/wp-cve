<?php

namespace WpifyWoo\Modules\PacketaShipping;

use WpifyWoo\Abstracts\AbstractModule;
use WpifyWoo\Models\PacketaOrderModel;
use WpifyWoo\Plugin;
use WpifyWoo\Repositories\PacketaOrderRepository;

/**
 * Class PacketaShippingModule
 *
 * @package WpifyWoo\Modules\PacketaShipping
 * @property Plugin $plugin
 */
class PacketaShippingModule extends AbstractModule {

	/**
	 * Packeta API
	 *
	 * @var PacketaApi
	 */
	private $packeta_api;
	/**
	 * @var ShippingMethodsGenerator
	 */
	private $shipping_methods_generator;

	/**
	 * PacketaShippingModule constructor.
	 *
	 * @param PacketaApi               $packeta_api PacketaApi.
	 * @param ShippingMethodsGenerator $shipping_methods_generator
	 */
	public function __construct( PacketaApi $packeta_api, ShippingMethodsGenerator $shipping_methods_generator ) {
		$this->packeta_api                = $packeta_api;
		$this->shipping_methods_generator = $shipping_methods_generator;
		parent::__construct();
	}

	/**
	 * Setup
	 *
	 * @return void
	 */
	public function setup() {
		require __DIR__ . '/PacketaShippingMethod.php';

		add_filter( 'woocommerce_shipping_methods', array( $this, 'register_packeta_shipping_method' ) );
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'checkout_validation' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_checkout_script' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_script' ), 10, 2 );
		add_filter( 'wpify_woo_settings_' . $this->id(), array( $this, 'settings' ) );
		add_action(
			'woocommerce_checkout_update_order_meta',
			array(
				$this,
				'store_pickup_field_update_order_meta',
			),
			15,
			2
		);
		add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'show_packeta_details' ) );
		add_action( 'woocommerce_email_customer_details', array( $this, 'add_packeta_to_email_notifications' ), 25, 4 );
		add_action( 'woocommerce_thankyou', array( $this, 'show_packeta_details' ), 12, 1 );
		add_action( 'woocommerce_checkout_order_processed', array( $this, 'maybe_replace_shipping_address' ), 25, 4 );
		add_filter( 'manage_edit-shop_order_columns', array( $this, 'packeta_order_column' ), 20 );
		add_action( 'manage_shop_order_posts_custom_column', array( $this, 'packeta_order_column_content' ) );
		add_action( 'admin_footer', array( $this, 'packeta_order_modal' ) );
		add_action( 'admin_init', array( $this, 'maybe_send_to_packeta' ) );
		add_filter( 'bulk_actions-edit-shop_order', [ $this, 'custom_bulk_actions' ], 20, 1 );
		add_filter( 'handle_bulk_actions-edit-shop_order', [ $this, 'handle_custom_bulk_actions' ], 10, 3 );
		add_action( 'init', function () {
			if ( apply_filters( 'wpify_woo_packeta_table_checkout_style', false ) ) {
				add_action( 'woocommerce_review_order_after_shipping', array( $this, 'add_table_branch_id_input' ), 10, 1 );
			} else {
				add_action( 'woocommerce_after_shipping_rate', array( $this, 'add_branch_id_input' ), 10, 1 );
			}
		} );
		$auto_statuses = $this->get_setting( 'send_to_packeta_status', true );
		if ( ! empty( $auto_statuses ) ) {
			foreach ( $auto_statuses as $status ) {
				add_action( "woocommerce_order_status_{$status}", [ $this, 'maybe_schedule_packeta' ] );
			}
		}
		add_action( 'wpify_woo_send_to_packeta', [ $this, 'create_packeta_for_order' ] );
		add_action( 'admin_action_send_to_packeta', [ $this, 'order_detail_create_packeta' ] );
		if ( ! defined( 'DISABLE_NAG_NOTICES' ) || ! DISABLE_NAG_NOTICES ) {
			add_action( 'admin_notices', [ $this, 'packeta_admin_notice' ] );
			add_action( 'admin_notices', [ $this, 'packeta_removed_notice' ] );
		}
		add_action( 'wp_ajax_wpify_packeta_dismiss_notice', [ $this, 'wpify_packeta_dismiss_admin_notice' ] );
		add_action( 'wp_ajax_wpify_packeta_removed_dismiss_notice', [ $this, 'wpify_packeta_removed_dismiss_admin_notice' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'make_packeta_admin_notice_dismissable' ] );
	}

	/**
	 * Set the module ID.
	 *
	 * @return string
	 */
	public function id(): string {
		return 'packeta_shipping';
	}

	/**
	 * Set the module ID.
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Packeta Shipping', 'wpify-woo' );
	}


	/**
	 * Add settings
	 *
	 * @return array[] Settings.
	 */
	public function settings(): array {
		$statuses = [];
		foreach ( wc_get_order_statuses() as $key => $status ) {
			$statuses[] = [
				'value' => str_replace( 'wc-', '', $key ),
				'label' => $status,
			];
		}

		$settings = array(
			array(
				'id'    => 'api_key',
				'type'  => 'text',
				'label' => __( 'API Key', 'wpify-woo' ),
				'desc'  => __( 'Enter your API key that can be found in <a href="https://client.packeta.com/en/support/">https://client.packeta.com/en/support/</a>', 'wpify-woo' ),
			),
			array(
				'id'    => 'api_password',
				'type'  => 'text',
				'label' => __( 'API Password', 'wpify-woo' ),
				'desc'  => __( 'Enter your API password that can be found in <a href="https://client.packeta.com/en/support/">https://client.packeta.com/en/support/</a>', 'wpify-woo' ),
			),
			array(
				'id'    => 'sender_name',
				'type'  => 'text',
				'label' => __( 'Sender name', 'wpify-woo' ),
				'desc'  => __( 'Enter your sender name that can be found in <a href="https://client.packeta.com/en/senders/">https://client.packeta.com/en/senders/</a>', 'wpify-woo' ),
			),
			array(
				'id'    => 'order_weight',
				'type'  => 'text',
				'label' => __( 'Order weight', 'wpify-woo' ),
				'desc'  => __( 'Enter weight in kg that will be sent to Zasilkovna if the products in order do not have weight specified.', 'wpify-woo' ),
			),
			array(
				'id'    => 'order_default_price',
				'type'  => 'number',
				'label' => __( 'Order default price', 'wpify-woo' ),
				'desc'  => __( 'Enter the default value of the package, if the order price is zero.', 'wpify-woo' ),
			),
			array(
				'id'    => 'replace_shipping_address',
				'type'  => 'switch',
				'label' => __( 'Replace shipping address', 'wpify-woo' ),
				'desc'  => __( 'Enable to replace the WooCommerce shipping address with Packeta address when an order is submitted', 'wpify-woo' ),
			),
			array(
				'id'    => 'display_logo',
				'type'  => 'switch',
				'label' => __( 'Display logo on checkout', 'wpify-woo' ),
				'desc'  => __( 'Enable to display Packetera logo on checkout', 'wpify-woo' ) . '<br>' .
						   sprintf(
							   __( 'The logo selection can be found in the Packeta shipping method for each transport zone in the <a href="%s"> WooCommerce shipping settings</a>.', 'wpify-woo' ),
							   admin_url( 'admin.php?page=wc-settings&tab=shipping' )
						   ),
			),
			array(
				'id'      => 'cod_gateways',
				'type'    => 'multiselect',
				'label'   => __( 'Select the COD gateways', 'wpify-woo' ),
				'desc'    => __( 'When sending the order to Packeta, the order will be marked as COD for the selected gateways. This setting does NOT add COD fee to the checkout - to setup the payment gateways fees you can use free plugin WooCommerce Pay for Payment.',
					'wpify-woo' ),
				'options' => function () {
					return $this->plugin->get_woocommerce_integration()->get_gateways();
				},
				'multi'   => true,
			),
			array(
				'id'    => 'round_cod',
				'type'  => 'switch',
				'label' => __( 'Rounding amount of COD', 'wpify-woo' ),
				'desc'  => __( 'Check if you want to round up the total amount sent to Packeta for COD payment method', 'wpify-woo' ),
			),
			array(
				'id'      => 'carriers',
				'type'    => 'multiselect',
				'label'   => __( 'Carriers', 'wpify-woo' ),
				'desc'    => __( 'BETA: Select external carriers to use for Zasilkovna. These will appear as shipping methods in WooCommerce - Settings - Shipping. This is BETA feature - some of the carriers do have limitations on what packages they accept, and we cannot guarantee yet this feature will work smoothly on all setups. If you encounter any issue, please write us at support@wpify.io with the description of the issue and the error message you see in the order notes.',
					'wpify-woo' ),
				'options' => function () {
					return $this->get_carriers_select();
				},
				'multi'   => true,
			),
			array(
				'id'      => 'send_to_packeta_status',
				'type'    => 'multiselect',
				'label'   => __( 'Send automatically to packeta', 'wpify-woo' ),
				'desc'    => __( 'When the order is switched to selected status, the order will be automatically sent to Packeta.', 'wpify-woo' ),
				'options' => $statuses,
				'multi'   => true,
			),
		);

		return $settings;
	}

	public function get_carriers_select() {
		$select = [];
		foreach ( $this->get_carriers() as $carrier ) {
			$select[] = [
				'label' => $carrier->label,
				'value' => $carrier->id,
			];
		}

		return $select;
	}

	public function get_carriers() {
		return json_decode( file_get_contents( __DIR__ . '/carriers.json' ) );
	}

	/**
	 * Enqeueue the frontend scripts
	 *
	 * @throws \Wpify\Core\Exceptions\PluginException
	 */
	public function enqueue_checkout_script() {
		if ( ! is_checkout() ) {
			return;
		}

		$this->plugin->get_asset_factory()->url( 'https://widget.packeta.com/v6/www/js/library.js', array(
			'handle'    => 'packeta-main',
			'version'   => null,
			'variables' => array(
				'packeta' => array(
					'apiKey' => $this->get_setting( 'api_key' ),
				),
			),
		) );

		$this->plugin->get_asset_factory()->wp_script( $this->plugin->get_asset_path( 'build/packeta.js' ), array(
			'handle'    => 'wpify-woo-packeta',
			'variables' => array(
				'wpifyWooPacketa' => array(
					'stringNone' => __( 'No branch selected', 'wpify-woo' ),
					'language'   => explode( '_', get_locale() )[0],
				),
			),
			'deps'      => array( 'packeta-main' ),
			'in_footer' => true,
		) );
	}

	public function enqueue_admin_script() {
		global $pagenow;

		if ( ( $pagenow == 'edit.php' ) && isset( $_GET['post_type'] ) && ( $_GET['post_type'] == 'shop_order' ) ) {
			$this->plugin->get_asset_factory()->admin_wp_script( $this->plugin->get_asset_path( 'build/packeta-admin.css' ), array(
				'handle' => 'packeta-admin-css',
			) );

			$this->plugin->get_asset_factory()->admin_wp_script( $this->plugin->get_asset_path( 'build/packeta-admin.js' ), array(
				'handle'    => 'packeta-admin-js',
				'variables' => array(
					'packetaAdmin' => array(
						'restUrl' => $this->plugin->get_api_manager()->get_rest_url(),
						'nonce'   => wp_create_nonce( 'wp_rest' ),
					),
				),
			) );
		}
	}

	/**
	 * Register the shipping method
	 *
	 * @param array $methods Shipping methods.
	 *
	 * @return array
	 */
	public function register_packeta_shipping_method( array $methods ): array {
		$methods['packeta'] = PacketaShippingMethod::class;

		$selected_carriers = $this->get_setting( 'carriers', true );
		if ( ! empty( $selected_carriers ) ) {
			$carriers = array_filter( $this->get_carriers(), function ( $item ) use ( $selected_carriers ) {
				return in_array( $item->id, $selected_carriers );
			} );
			foreach ( $carriers as $carrier ) {
				$methods[ sprintf( 'packeta_%s', $carrier->id, ) ] = sprintf( 'WpifyWoo\Modules\PacketaShipping\ShippingMethods\%s', $carrier->class );
			}
		}

		return $methods;
	}

	/**
	 * Validate the checkout
	 *
	 * @param array $fields Array of the fields.
	 * @param       $errors
	 */
	public function checkout_validation( $fields, $errors ) {
		$packages = WC()->shipping()->get_packages();

		foreach ( $fields['shipping_method'] as $method ) {
			foreach ( $packages as $i => $package ) {
				if ( isset( $package['rates'][ $method ] ) ) {
					if ( strpos( $package['rates'][ $method ]->id, 'packeta:' ) !== false && empty( $_POST['packeta_id'] ) ) {
						$errors->add( 'validation', __( 'Select Packeta pickup point', 'wpify-woo' ) );
						continue( 2 );
					}
				}
			}
		}
	}

	/**
	 * Save branch ID
	 *
	 * @param int $order_id Order ID.
	 *
	 * @since 1.0.0
	 */
	public function store_pickup_field_update_order_meta( int $order_id ) {
		/** @var  PacketaOrderModel $order */
		$repository = $this->plugin->get_repository( PacketaOrderRepository::class );
		$order      = $repository->get( $order_id );
		if ( $order->is_packeta_shipping() ) {
			$order->set_packeta_id( sanitize_text_field( $_POST['packeta_id'] ) );
			$order->set_packeta_name( sanitize_text_field( $_POST['packeta_name'] ) );
			$order->set_packeta_street( sanitize_text_field( $_POST['packeta_street'] ) );
			$order->set_packeta_city( sanitize_text_field( $_POST['packeta_city'] ) );
			$order->set_packeta_postcode( sanitize_text_field( $_POST['packeta_postcode'] ) );
			$order->set_packeta_url( sanitize_text_field( $_POST['packeta_url'] ) );
			$repository->save( $order );
		}
	}

	/**
	 * Add the button and inputs
	 *
	 * @param $method
	 */
	public function add_branch_id_input( $method ) {
		if ( ! is_checkout() ) {
			return;
		}

		$chosen_shipping_rates = array_filter(
			WC()->session->get( 'chosen_shipping_methods' ),
			function ( $item ) {
				return strpos( $item, 'packeta:' ) !== false;
			}
		);

		if ( empty( $chosen_shipping_rates ) ) {
			return;
		}

		if ( strpos( $method->id, 'packeta:' ) !== false ) {
			$option    = get_option( 'woocommerce_' . $method->method_id . '_' . $method->instance_id . '_settings' );
			$just_icon = $this->get_setting( 'display_logo' ) && ! empty( $option['logo_type'] ) && $option['logo_type'] === 'packeta_ico';
			?>
			<style type="text/css" style="display: none;">
				.wpify-woo-packeta__shipping-method #packeta-point-info {
					display: none;
				}

				.wpify-woo-packeta__shipping-method.wpify-woo-packeta__shipping-method--selected #packeta-point-info {
					display: block;
				}

				.wpify-woo-packeta__shipping-method.wpify-woo-packeta__shipping-method--selected .wpify-woo-packeta__button {
					display: none;
				}

				.wpify-woo-packeta__shipping-method img {
					max-width: 100%;
					height: auto;
					margin-bottom: 20px;
				}

				<?php if ($just_icon) { ?>
				.wpify-woo-packeta__shipping-method .wpify-woo-packeta__button-wrapper {
					display: flex;
					flex-wrap: wrap;
					flex-direction: row;
					margin-bottom: 20px;
				}

				.wpify-woo-packeta__shipping-method .wpify-woo-packeta__button-wrapper wpify-woo-packeta__button {
					flex: 1;
				}

				.wpify-woo-packeta__shipping-method .wpify-woo-packeta__button-wrapper img {
					flex: 1;
					margin-bottom: 0;
					max-width: 60px;
				}

				<?php } ?>
			</style>
			<div class="wpify-woo-packeta__shipping-method">
				<?php
				if ( $this->get_setting( 'display_logo' ) && ! empty ( $option['logo_type'] ) ) {
					$url = $this->plugin->get_asset_url( 'src/Modules/PacketaShipping/assets/images/' . $option['logo_type'] . '.png' );
					$url = apply_filters( 'wpify_woo_packeta_logo', $url );

					if ( $just_icon ) {
						echo '<div class="wpify-woo-packeta__button-wrapper">';
					}
					?>
					<img src="<?php echo $url; ?>" alt="">
				<?php } ?>
				<button type="button" class="zasilkovna-open-widget wpify-woo-packeta__button"
						value=""><?php _e( 'Select pickup point', 'wpify-woo' ); ?></button>
				<?php if ( $just_icon ) {
					echo '</div>';
				} ?>
				<input type="hidden" name="packeta_id" id="packeta-point-id">
				<input type="hidden" name="packeta_name" id="packeta-point-name">
				<input type="hidden" name="packeta_city" id="packeta-point-city">
				<input type="hidden" name="packeta_street" id="packeta-point-street">
				<input type="hidden" name="packeta_postcode" id="packeta-point-postcode">
				<input type="hidden" name="packeta_url" id="packeta-point-url">
				<div id="packeta-point-info">
					<div class="packeta-point-info__details"></div>
					<div class="packeta-point-info__change"><a href="#"
															   class="zasilkovna-open-widget"><?php _e( 'Change pickup point', 'wpify-woo' ); ?></a>
					</div>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Add the button and inputs
	 *
	 * @param $method
	 */
	public function add_table_branch_id_input( $method ) {
		if ( ! is_checkout() ) {
			return;
		}


		$chosen_shipping_rates = array_filter(
			WC()->session->get( 'chosen_shipping_methods' ),
			function ( $item ) {
				return strpos( $item, 'packeta:' ) !== false;
			}
		);

		if ( empty( $chosen_shipping_rates ) ) {
			return;
		}

		$option = get_option( 'woocommerce_' . $method->method_id . '_' . $method->instance_id . '_settings' );

		?>
		<style type="text/css">
			.wpify-woo-packeta__shipping-method #packeta-point-info {
				display: none;
			}

			.wpify-woo-packeta__shipping-method.wpify-woo-packeta__shipping-method--selected #packeta-point-info {
				display: block;
			}

			.wpify-woo-packeta__shipping-method.wpify-woo-packeta__shipping-method--selected .wpify-woo-packeta__button {
				display: none;
			}

		</style>
		<tr id="select-packeta">
			<th class="packeta-ico">
				<?php if ( $this->get_setting( 'display_logo' ) && ! empty ( $option['logo_type'] ) ) {
					$url = $this->plugin->get_asset_url( 'src/Modules/PacketaShipping/assets/images/' . $option['logo_type'] . '.png' );
					$url = apply_filters( 'wpify_woo_packeta_logo', $url );
					?>
					<img src="<?php echo $url; ?>" alt="" style="max-width: 100%; height: auto;">
				<?php } ?>
			</th>
			<td>
				<div class="wpify-woo-packeta__shipping-method">

					<button type="button" class="zasilkovna-open-widget wpify-woo-packeta__button"
							value=""><?php _e( 'Select pickup point', 'wpify-woo' ); ?></button>
					<input type="hidden" name="packeta_id" id="packeta-point-id">
					<input type="hidden" name="packeta_name" id="packeta-point-name">
					<input type="hidden" name="packeta_city" id="packeta-point-city">
					<input type="hidden" name="packeta_street" id="packeta-point-street">
					<input type="hidden" name="packeta_postcode" id="packeta-point-postcode">
					<input type="hidden" name="packeta_url" id="packeta-point-url">
					<div id="packeta-point-info">
						<div class="packeta-point-info__details"></div>
						<div class="packeta-point-info__change"><a href="#"
																   class="zasilkovna-open-widget"><?php _e( 'Change pickup point', 'wpify-woo' ); ?></a>
						</div>
					</div>
				</div>
			</td>
		</tr>

		<?php
	}


	/**
	 * Add Packeta details to the email notifications
	 *
	 * @param $order
	 * @param $sent_to_admin
	 * @param $plain_text
	 * @param $email
	 */
	public function add_packeta_to_email_notifications( $order, $sent_to_admin, $plain_text, $email ) {
		$this->show_packeta_details( $order );
	}

	/**
	 * Render the packeta details.
	 *
	 * @param \WC_Order $order WC Order.
	 */
	public function show_packeta_details( $order ) {
		/** @var PacketaOrderModel $order */
		$order = $this->plugin->get_repository( PacketaOrderRepository::class )->get( $order );
		if ( ! $order->is_packeta_shipping() || ! $order->get_packeta_url() ) {
			return;
		}
		?>
		<div class="address">
			<h3><?php echo __( 'Packeta', 'wpify-woo' ); ?></h3>
			<?php if ( ! $this->get_setting( 'replace_shipping_address' ) ) { ?>
				<?php echo $order->get_packeta_name(); ?><br/>
				<?php echo $order->get_packeta_street(); ?><br/>
				<?php echo $order->get_packeta_city(); ?><br/>
				<?php echo $order->get_packeta_postcode(); ?><br/>
				<?php
			}

			printf( '<a href="%s" target="_blank">%s</a><br/>', $order->get_packeta_url(), __( 'Show pickup point details', 'wpify-woo' ) );
			?>
		</div>
		<?php
	}

	/**
	 * Replace the shipping address if set in plugin settings
	 *
	 * @param int $order_id Order ID.
	 *
	 * @throws \WC_Data_Exception
	 */
	public function maybe_replace_shipping_address( int $order_id ) {
		if ( ! $this->get_setting( 'replace_shipping_address' ) ) {
			return;
		}
		/** @var PacketaOrderModel $order */
		$order = $this->plugin->get_repository( PacketaOrderRepository::class )->get( $order_id );
		if ( ! $order->is_packeta_shipping() || ! $order->get_packeta_name() ) {
			return;
		}

		$order->get_wc_order()->set_shipping_company( $order->get_packeta_name() );
		$order->get_wc_order()->set_shipping_address_1( $order->get_packeta_street() );
		$order->get_wc_order()->set_shipping_city( $order->get_packeta_city() );
		$order->get_wc_order()->set_shipping_postcode( $order->get_packeta_postcode() );
		$order->get_wc_order()->save();
	}

	/**
	 * Create Packeta columns in order list
	 *
	 * @param string[] $columns
	 *
	 * @return string[] $new_columns
	 */
	public function packeta_order_column( $columns ) {
		$new_columns = array();

		// Inserting columns to a specific location
		foreach ( $columns as $key => $column ) {
			$new_columns[ $key ] = $column;
			if ( $key == 'shipping_address' ) {
				// Inserting after "Status" column
				$new_columns['packeta'] = __( 'Packeta', 'wpify-woo' );
			}
		}

		return $new_columns;
	}

	/**
	 * Add content for Packeta columns in order list
	 *
	 * @param string[] $column name of column being displayed
	 */
	public function packeta_order_column_content( $column ) {
		global $post;
		/** @var PacketaOrderModel $order */
		$order = $this->plugin->get_repository( PacketaOrderRepository::class )->get( $post->ID );

		if ( 'packeta' === $column ) {
			$packetaId        = $order->get_package_id();
			$packetaLanguages = array( 'en', 'cs', 'sk', 'pl', 'hu', 'de', 'ro', 'uk', 'es', 'fr', 'ru', );
			$curentLanguage   = substr( get_locale(), 0, 2 );

			if ( in_array( $curentLanguage, $packetaLanguages ) ) {
				$language = $curentLanguage;
			} else {
				$language = 'en';
			}

			if ( $packetaId ) {
				echo '<p><a href="https://tracking.packeta.com/' . $language . '/?id=' . $packetaId . '" target="_blank">Z ' . $packetaId . '</a></p>';
			};

			$weight = $order->get_package_weight(); ?>
			<?php echo sprintf( __( '<span class="packeta__weight" data-id="%s">%s</span> kg' ), $order->get_id(), $weight ); ?></span>
			<?php


			if ( $order->is_packeta_shipping() && ! $packetaId ) {
				$url = ( isset( $_SERVER['HTTPS'] ) ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				?>
				<a id="packeta-details-link" class="button packeta-button packeta-button--edit" href="javascript:void(null);" data-order_id="<?php echo $post->ID; ?>"
				   data-weight="<?php echo $weight; ?>" title="<?php _e( 'Edit weight', 'wpify-woo' ); ?>">
					<?php _e( 'Edit weight', 'wpify-woo' ); ?>
				</a>
				<a class="button packeta-button packeta-button--send"
				   href="<?php echo add_query_arg( array( 'action' => 'send_to_packeta', 'order_id' => $order->get_id(), 'redirect' => urlencode( esc_url( $url ) ) ), admin_url() ); ?>"
				   title="<?php _e( 'Send to Packeta', 'wpify-woo' ); ?>">
					<?php _e( 'Send to Packeta', 'wpify-woo' ); ?>
				</a>
				<?php
			};
		}
	}

	/**
	 * Add modal window for Packeta in order list
	 */
	public function packeta_order_modal() {
		global $pagenow;
		if ( 'edit.php' !== $pagenow || ! isset( $_GET['post_type'] ) || 'shop_order' !== $_GET['post_type'] ) {
			return;
		} ?>

		<div class="packeta-modal">
			<div class="packeta-modal_content">
				<span id="close-packeta-modal" class="dashicons dashicons-no-alt"></span>
				<form id="packeta-details" data-order_id="">
					<input type="hidden" id="order_id" name="order_id" value="">
					<p><label for="weight"><?php _e( 'Change package weight (kg)', 'wpify-woo' ); ?></label></p>
					<input type="number" id="weight" name="weight" step=".01" value="" required>
					<input type="submit" style="button" value="<?php _e( 'Submit', 'wpify-woo' ); ?>">
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Maybe send order to packet or generate labels
	 */
	public function maybe_send_to_packeta() {
		if ( ! current_user_can( apply_filters( 'wpify_woo_send_to_packeta_capability', 'manage_woocommerce' ) ) ) {
			return;
		}
		
		if ( ! empty( $_GET['send-to-packeta'] ) && ! empty( $_GET['post'] ) ) {
			$this->create_packeta_for_order( sanitize_text_field( $_GET['post'] ) );
		}
		if ( ! empty( $_GET['packeta-generate-label'] ) ) {
			$date = date( 'Y-m-dH:i:s' );
			header( 'Content-type:application/pdf' );
			header( "Content-Disposition:attachment;filename=labels-{$date}.pdf" );
			$labels = $this->packeta_api->get_packets_labels( $_GET['packeta-generate-label'] );
			if ( is_wp_error( $labels ) ) {
				wp_die( $labels->get_error_message() );
			} else {
				echo $this->packeta_api->get_packets_labels( $_GET['packeta-generate-label'] );
				exit();
			}
		}
	}

	/**
	 * Create a Packeta package for a specific order
	 *
	 * @param $order_id
	 */
	public function create_packeta_for_order( $order_id ) {
		$order = $this->plugin->get_repository( PacketaOrderRepository::class )->get( sanitize_text_field( $order_id ) );
		$this->packeta_api->create_packet( $order );
	}

	public function order_detail_create_packeta() {
		$order_id = $_GET['order_id'];
		$order    = $this->plugin->get_repository( PacketaOrderRepository::class )->get( sanitize_text_field( $order_id ) );
		$this->packeta_api->create_packet( $order );
		$redirect = $_GET['redirect'] ?? get_edit_post_link( $order_id, '' );
		wp_safe_redirect( $redirect );
		exit();
	}

	/**
	 * @return PacketaApi
	 */
	public function get_packeta_api(): PacketaApi {
		return $this->packeta_api;
	}

	public function custom_bulk_actions( $actions ) {
		$actions['wpify_woo_packeta_generate_labels'] = __( 'Packeta - Generate labels', 'wpify-woo' );

		return $actions;
	}

	/**
	 * Handle custom bulk action
	 *
	 * @param $redirect_to
	 * @param $action
	 * @param $post_ids
	 *
	 * @return mixed|string
	 */
	public function handle_custom_bulk_actions( $redirect_to, $action, $post_ids ) {
		if ( 'wpify_woo_packeta_generate_labels' === $action ) {
			$package_ids = [];
			foreach ( $post_ids as $post_id ) {
				/** @var PacketaOrderModel $order */
				$order = $this->plugin->get_repository( PacketaOrderRepository::class )->get( $post_id );
				if ( $order->get_package_id() ) {
					$package_ids[] = $order->get_package_id();
				} else {
					$result = $this->packeta_api->create_packet( $order );
					if ( ! is_wp_error( $result ) ) {
						$package_ids[] = $result['package_id'];
					}
				}
			}

			$redirect_to = add_query_arg( [ 'packeta-generate-label' => $package_ids ], admin_url() );
		}

		return $redirect_to;
	}

	/**
	 * @return ShippingMethodsGenerator
	 */
	public function get_shipping_methods_generator(): ShippingMethodsGenerator {
		return $this->shipping_methods_generator;
	}

	/**
	 * Maybe schedule sending to packeta
	 *
	 * @param $order_id
	 */
	public function maybe_schedule_packeta( $order_id ) {
		$order = $this->plugin->get_repository( PacketaOrderRepository::class )->get( $order_id );

		if ( ! $order || ! $order->is_packeta_shipping() || $order->get_package_id() ) {
			return;
		}

		as_schedule_single_action( time(), 'wpify_woo_send_to_packeta', [ 'order_id' => $order_id ] );
	}

	/**
	 * Notice that the settings have changed
	 */
	function packeta_admin_notice() {
		if ( get_option( 'wpify_packeta_admin_notice_dismissed' ) ) {
			return;
		}

		$title  = __( 'The logo setting for the Packeta shipping method has been moved!', 'wpify-woo' );
		$string = sprintf(
			__( 'The logo setting has been moved to the transport method setting. So now you can define a different logo for each transport zone. In order to display the logo again in checkout, you need to set it in the Packeta shipping method for each shipping zone in the <a href="%s"> WooCommerce shipping settings</a>.',
				'wpify-woo' ),
			admin_url( 'admin.php?page=wc-settings&tab=shipping' )
		);
		printf( '<div class="notice notice-warning is-dismissible wpify-packeta-notice"><h2>%s</h2><p>%s</p></div>', $title, $string );
	}

	/**
	 * Dismiss the message
	 */
	function make_packeta_admin_notice_dismissable() {
		if ( ! get_option( 'wpify_packeta_admin_notice_dismissed' ) ) {
			$script = "jQuery(document).on('click','.wpify-packeta-notice .notice-dismiss',function(){jQuery.post(ajaxurl,{action:'wpify_packeta_dismiss_notice'});});";
			wp_add_inline_script( 'jquery', $script );
		}

		if ( ! get_option( 'wpify_packeta_removed_admin_notice_dismissed' ) ) {
			$script = "jQuery(document).on('click','.wpify-packeta-remove-notice .notice-dismiss',function(){jQuery.post(ajaxurl,{action:'wpify_packeta_removed_dismiss_notice'});});";
			wp_add_inline_script( 'jquery', $script );
		}
	}

	/**
	 * Save the information that you dismissed the message
	 */
	function wpify_packeta_dismiss_admin_notice() {
		update_option( 'wpify_packeta_admin_notice_dismissed', true );
	}

	/**
	 * Save the information that you dismissed the message
	 */
	function wpify_packeta_removed_dismiss_admin_notice() {
		update_option( 'wpify_packeta_removed_admin_notice_dismissed', true );
	}

	/**
	 * Notice that the Packeta module will be removed
	 */
	function packeta_removed_notice() {
		if ( get_option( 'wpify_packeta_removed_admin_notice_dismissed' ) ) {
			return;
		}

		$title      = __( 'The Packeta module will be removed from the Wpify Woo plugin soon', 'wpify-woo' );
		$string     = sprintf(
			__( 'We worked together with Packeta developers to provide the official free Packeta plugin, which is already available in the <a href="%s" target="_blank">wp.org repository</a> and will replace our module soon. Our Packeta module will be be available until the end of September, after that it will be removed from Wify Woo in favour of the official plugin. Please install the official plugin early to avoid loosing the Packeta shipping method.',
				'wpify-woo' ),
			'https://wordpress.org/plugins/packeta/'
		);
		$button     = __( 'Install official Packeta plugin', 'wpify-woo' );
		$plugin_url = wp_nonce_url(
			add_query_arg(
				array(
					'action' => 'install-plugin',
					'plugin' => 'packeta',
				),
				admin_url( 'update.php' )
			),
			'install-plugin_packeta'
		);
		printf( '<div class="notice notice-warning is-dismissible wpify-packeta-remove-notice"><h2>%s</h2><p>%s</p><p><a href="%s" class="button action">%s</a></p></div>', $title, $string, $plugin_url, $button );
	}
}
