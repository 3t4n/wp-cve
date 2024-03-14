<?php

namespace WpifyWoo\Modules\DeliveryDates;

use WC_Shipping_Zones;
use WpifyWoo\Abstracts\AbstractModule;
use WpifyWoo\Modules\DeliveryDates\Api\DeliveryDatesApi;
use WpifyWooDeps\Wpify\CustomFields\CustomFields;

class DeliveryDatesModule extends AbstractModule {
	private CustomFields $custom_fields;

	public function __construct( CustomFields $custom_fields ) {
		parent::__construct();
		$this->custom_fields = $custom_fields;
	}

	/**
	 * @return void
	 */
	public function setup() {
		add_filter( 'wpify_woo_settings_' . $this->id(), array( $this, 'settings' ) );
		add_action( 'init', array( $this, 'product_metabox' ) );
		add_action( 'init', array( $this, 'add_rest_api' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		if ( is_array( $this->get_setting( 'display_locations' ) ) ) {
			foreach ( $this->get_setting( 'display_locations' ) as $location ) {
				add_action( $location, array( $this, 'display_delivery_date' ) );
			}
		}

		add_action( 'admin_init', array( $this, 'convert_old_product_data' ) );
		add_action( 'wp_ajax_wpify_delivery_dates_dismiss_notice', array(
			$this,
			'wpify_delivery_dates_dismiss_admin_notice'
		) );
		add_action( 'admin_enqueue_scripts', array( $this, 'make_delivery_dates_admin_notice_dismissable' ) );
		add_action( 'admin_notices', array( $this, 'delivery_dates_removed_notice' ) );
		add_action( 'admin_notices', array( $this, 'maybe_show_notice' ) );
		add_shortcode( 'wpify_woo_delivery_dates', array( $this, 'delivery_date_shortcode' ) );
	}

	function id() {
		return 'delivery_dates';
	}

	public function name() {
		return __( 'Delivery dates', 'wpify-woo' );
	}

	/**
	 * @return array[]
	 */
	public function settings(): array {
		$locations = [
			'woocommerce_single_product_summary',
			'woocommerce_before_add_to_cart_form',
			'woocommerce_before_variations_form',
			'woocommerce_before_add_to_cart_button',
			'woocommerce_before_add_to_cart_quantity',
			'woocommerce_after_add_to_cart_quantity',
			'woocommerce_after_add_to_cart_button',
			'woocommerce_after_add_to_cart_form',
			'woocommerce_after_variations_form',
			'woocommerce_product_meta_start',
			'woocommerce_product_meta_end',
			'woocommerce_after_single_product_summary',
		];
		$settings  = array(
			array(
				'id'      => 'delivery_days',
				'type'    => 'multi_group',
				'title'   => 'Delivery days',
				'buttons' => array(
					'add' => __( 'Add delivery days', 'wpify-woo-conditional-payment' ),
				),
				'items'   => array(
					array(
						'id'    => 'group_title',
						'type'  => 'text',
						'label' => __( 'Group title', 'wpify-woo' ),
					),
					array(
						'id'    => 'delivery_order_time',
						'type'  => 'time',
						'label' => __( 'Bridging time to next day', 'wpify-woo' ),
						'desc'  => __( 'If this time is exceeded, the next day will count for delivery.', 'wpify-woo' ),
					),
					array(
						'id'    => 'delivery_days_in_stock',
						'type'  => 'text',
						'label' => __( 'In stock delivery days', 'wpify-woo' ),
						'desc'  => __( 'Enter a number indicating the number of days, a range in <code>1-2</code> format, or any text. Leave blank to not show.', 'wpify-woo' ),
					),
					array(
						'id'    => 'delivery_days_out_of_stock',
						'type'  => 'text',
						'label' => __( 'Out of stock delivery days', 'wpify-woo' ),
						'desc'  => __( 'Enter a number indicating the number of days, a range in <code>1-2</code> format, or any text. Leave blank to not show.', 'wpify-woo' ),
					),
					array(
						'id'    => 'delivery_days_backorder',
						'type'  => 'text',
						'label' => __( 'On backorder delivery days', 'wpify-woo' ),
						'desc'  => __( 'Enter a number indicating the number of days, a range in <code>1-2</code> format, or any text. Leave blank to not show.', 'wpify-woo' ),
					),
					array(
						'id'    => 'skip_weekends',
						'type'  => 'toggle',
						'label' => __( 'Skip weekends', 'wpify-woo' ),
					),
					array(
						'id'      => 'delivery_date_message',
						'type'    => 'text',
						'label'   => __( 'Delivery date message', 'wpify-woo' ),
						'desc'    => __( 'Use <code>%date%</code> code to render calculated date in messsage.', 'wpify-woo' ),
						'default' => __( 'Delivered on %date%', 'wpify-woo' ),
					),
					array(
						'id'    => 'delivery_date_info',
						'type'  => 'wysiwyg',
						'label' => __( 'Delivery date more info', 'wpify-woo' ),
					),
					array(
						'id'      => 'shipping_methods',
						'label'   => __( 'Display shipping methods', 'wpify-woo-conditional-shipping' ),
						'type'    => 'multiselect',
						'multi'   => true,
						'desc'    => __( 'Select the shipping methods that appear in more information.', 'wpify-woo-conditional-shipping' ),
						'options' => $this->get_shipping_methods_option(),
					),
					array(
						'type'      => 'hidden',
						'id'        => 'uuid',
						'generator' => 'uuid',
					),

				)
			),
			array(
				'id'      => 'delivery_date_format',
				'type'    => 'text',
				'label'   => __( 'Delivery date format', 'wpify-woo' ),
				'default' => 'd.m.',
			),
			array(
				'id'    => 'date_as_text',
				'type'  => 'toggle',
				'label' => __( 'Today and tomorrow as text', 'wpify-woo' ),
			),
			array(
				'id'    => 'title',
				'type'  => 'text',
				'label' => __( 'Title of delivery date block', 'wpify-woo' ),
			),
			array(
				'id'    => 'country_select_label',
				'type'  => 'text',
				'label' => __( 'Label of country selector', 'wpify-woo' ),
			),
			array(
				'id'      => 'more_info_label',
				'type'    => 'text',
				'label'   => __( 'More info link label', 'wpify-woo' ),
				'default' => __( 'more info', 'wpify-woo' ),
			),
			array(
				'id'    => 'payments_message',
				'type'  => 'text',
				'label' => __( 'Payment methods message', 'wpify-woo' ),
				'desc'  => __( 'Insert payment methods message. Leave empty to not show.', 'wpify-woo' ),
			),
			array(
				'id'    => 'payments_info',
				'type'  => 'wysiwyg',
				'label' => __( 'Payment methods more info', 'wpify-woo' ),
			),
			array(
				'id'      => 'display_locations',
				'type'    => 'multi_select',
				'label'   => __( 'Display locations', 'wpify-woo' ),
				'options' => function () use ( $locations ) {
					return array_map( function ( $item ) {
						return [
							'label' => $item,
							'value' => $item,
						];
					}, $locations );
				},
			),

		);

		return $settings;
	}


	/**
	 * Product options
	 */
	public function product_metabox() {
		$delivery_days = $this->get_setting( 'delivery_days' ) ?? [];
		$items         = [];

		foreach ( $delivery_days as $key => $group ) {
			$items[] = array(
				'id'    => 'delivery_dates_' . $group['uuid'] ?? $key,
				'type'  => 'group',
				'items' => array(
					array(
						'type' => 'title',
						'desc' => $group['group_title'],
					),
					array(
						'id'    => 'delivery_days_in_stock',
						'type'  => 'text',
						'label' => __( 'In stock delivery days', 'wpify-woo' ),
						'desc'  => __( 'Override the default value from the settings.', 'wpify-woo' ),
					),
					array(
						'id'    => 'delivery_days_out_of_stock',
						'type'  => 'text',
						'label' => __( 'Out of stock delivery days', 'wpify-woo' ),
						'desc'  => __( 'Override the default value from the settings.', 'wpify-woo' ),
					),
					array(
						'id'    => 'delivery_days_backorder',
						'type'  => 'text',
						'label' => __( 'On backorder delivery days', 'wpify-woo' ),
						'desc'  => __( 'Override the default value from the settings.', 'wpify-woo' ),
					),
				),
			);
		}

		$this->custom_fields->create_product_options(
			[
				'tab'   => array(
					'id'       => 'wpify_woo_delivery_dates',
					'label'    => __( 'Delivery dates', 'wpify-woo' ),
					'priority' => 100,
					'class'    => array(),
				),
				'items' => array(
					array(
						'id'    => '_wpify_woo_delivery_dates',
						'type'  => 'group',
						'items' => $items,
					),

				),
			]
		);
	}

	/**
	 * Get list of shipping methods for options
	 *
	 * @return array
	 */
	public function get_shipping_methods_option(): array {
		if ( ! is_admin() ) {
			return array();
		}

		$shipping_methods = [];

		foreach ( $this->get_all_zones() as $zone ) {
			$name = $zone['zone_name'];

			foreach ( $zone['shipping_methods'] as $shipping ) {
				/** @var $shipping \WC_Shipping_Flat_Rate */
				$shipping_methods[] = array(
					'label' => sprintf( '%s: %s', $name, $shipping->get_title() ),
					'value' => $shipping->get_rate_id(),
				);
			}
		}

		return $shipping_methods;
	}

	/**
	 * Enqueue frontend scripts
	 */
	public function enqueue_scripts() {
		$this->plugin->get_asset_factory()->wp_script( $this->plugin->get_asset_path( 'build/delivery-dates.css' ) );
		$this->plugin->get_asset_factory()->wp_script( $this->plugin->get_asset_path( 'build/delivery-dates.js' ), array(
			'handle'    => 'wpify-woo-delivery-dates',
			'in_footer' => true,
			'variables' => array(
				'wpifyDeliveryDates' => array(
					'namespace' => $this->plugin->get_api_manager()->get_rest_namespace()
				),
			),
		) );
	}

	/**
	 * Init Rest API
	 *
	 * @throws \WpifyWooDeps\Wpify\Core\Exceptions\ComponentInitFailureException
	 * @throws \WpifyWooDeps\Wpify\Core\Exceptions\PluginException
	 */
	public function add_rest_api() {
		$api = $this->get_plugin()->create_component( DeliveryDatesApi::class );
		$api->init();
		$this->get_plugin()->get_api_manager()->add_module( $api );
	}

	/**
	 * Get all shipping zones
	 *
	 * @return array
	 */
	public function get_all_zones(): array {
		$zones        = WC_Shipping_Zones::get_zones();
		$default_zone = new \WC_Shipping_Zone( 0 );

		if ( empty( $default_zone->get_shipping_methods() ) ) {
			return $zones;
		}

		$zones[ $default_zone->get_id() ]                            = $default_zone->get_data();
		$zones[ $default_zone->get_id() ]['formatted_zone_location'] = __( 'Other regions', 'wpify-woo' );
		$zones[ $default_zone->get_id() ]['shipping_methods']        = $default_zone->get_shipping_methods();

		return $zones;
	}

	/**
	 * Get formatted date or date name
	 *
	 * @param string|float $days       number of days or string
	 * @param array        $days_group array of delivery date group settings
	 *
	 * @return string
	 */
	public function get_formatted_date( $days, $days_group ): string {
		$format     = $this->get_setting( 'delivery_date_format' );
		$type       = $days_group['skip_weekends'] ? 'weekdays' : 'days';
		$order_time = $days_group['delivery_order_time'];
		$days       = strtotime( current_time( 'H:i' ) ) > strtotime( $order_time ) ? (int) $days + 1 : (int) $days;
		$days       = strtotime( sprintf( '+ %s %s', $days, $type ), strtotime( current_time( 'Y-m-d H:i' ) ) );
		$date       = date_i18n( $format, $days );

		if ( $this->get_setting( 'date_as_text' ) ) {
			if ( strtotime( current_time( 'Y-m-d' ) ) === strtotime( date( 'Y-m-d', $days ) ) ) {
				return __( 'Today', 'wpify-woo' );
			} elseif ( strtotime( current_time( 'Y-m-d' ) . ' +1 day' ) === strtotime( date( 'Y-m-d', $days ) ) ) {
				return __( 'Tomorrow', 'wpify-woo' );
			}
		}

		return $date;
	}

	/**
	 * Return table of delivery methods
	 *
	 * @param array  $methods_ids        delivery methods ids
	 * @param array  $shipping_countries shipping countries from WC settings
	 * @param array  $shipping_zones     all shipping zones
	 * @param string $actual_country     code of actual country
	 *
	 * @throws \Exception
	 */
	public function display_delivery_methods( $methods_ids, $shipping_countries, $shipping_zones, $actual_country ) {
		// Get array of allowed countries names
		$zone_countries = [];
		foreach ( $shipping_zones as $key => $zone ) {
			$zone_countries[ $key ] = $zone['formatted_zone_location'];
		}

		// Render list of shipping methods by country
		foreach ( $shipping_zones as $key => $zone ) {
			// Remove unassigned shipping methods
			foreach ( $zone['shipping_methods'] as $shipping_key => $method ) {
				if ( ! in_array( $method->get_rate_id(), $methods_ids ) ) {
					unset( $shipping_zones[ $key ]['shipping_methods'][ $shipping_key ] );
				}
			}

			// Skip zones without shipping methods
			if ( empty( $shipping_zones[ $key ]['shipping_methods'] ) ) {
				continue;
			}

			// Get selected country
			$selected = in_array( $shipping_countries[ $actual_country ], $zone_countries ) ? array_search( $shipping_countries[ $actual_country ], $zone_countries ) : '0';
			$show     = $selected == $zone['id'];

			// Show all methods if selector is disabled
			if ( apply_filters( 'wpify_woo_delivery_dates_disable_country_select', false ) ) {
				$show = true;
			}
			?>

			<div id="zone-<?= esc_attr( $zone['id'] ) ?>"
				 class="wpify-woo-delivery-date__shipping-methods <?= $show ? 'show' : '' ?>">
				<table>

					<?php
					foreach ( $shipping_zones[ $key ]['shipping_methods'] as $method ) {
						// Skip disabled methods
						if ( $method->enabled !== 'yes' ) {
							continue;
						}

						// set data
						$line_data = array(
							'title' => $method->title,
							'price' => is_object( 'WC_Shipping_Free_Shipping' ) || empty( $method->cost ) ? '' : wc_price( $method->cost ),
						);

						/**
						 * Filter to change shipping methods line data
						 *
						 * @param array  $line_data current line data
						 * @param object $method    shipping methods data
						 */
						$line_data = apply_filters( 'wpify_woo_delivery_dates_shipping_table_line', $line_data, $method );

						?>
						<tr>
							<th><?= $line_data['title'] ?></th>
							<td><?= $line_data['price'] ?></td>
						</tr>
						<?php
					}
					?>
				</table>
			</div>
			<?php
		}
	}

	/**
	 * Return table of payment methods
	 */
	public function display_payment_methods() {
		$gateways = WC()->payment_gateways->get_available_payment_gateways();

		if ( $gateways ) {
			?>
			<table class="wpify-woo-delivery-date__payments">
				<?php
				foreach ( $gateways as $gateway ) {
					// Skip disabled gateways
					if ( $gateway->enabled !== 'yes' ) {
						continue;
					}

					// set data
					$line_data = array(
						'title' => $gateway->title,
						'price' => '',
					);

					/**
					 * Filter to change gateway line data
					 *
					 * @param array  $line_data current line data
					 * @param object $gateway   gateway data
					 */
					$line_data = apply_filters( 'wpify_woo_delivery_dates_payment_table_line', $line_data, $gateway );

					?>
					<tr>
						<th><?= $line_data['title'] ?></th>
						<td><?= $line_data['price'] ?></td>
					</tr>
					<?php
				}
				?>
			</table>
			<?php
		}
	}

	/**
	 * Return country selector
	 *
	 * @param array  $shipping_countries shipping countries from WC settings
	 * @param array  $shipping_zones     all shipping zones
	 * @param array  $zone_countries     country names from zones
	 * @param string $actual_country     code of actual country
	 */
	public function display_country_select( $shipping_countries, $shipping_zones, $zone_countries, $actual_country ) {
		/**
		 * Filter to disable country selector
		 */
		if ( apply_filters( 'wpify_woo_delivery_dates_disable_country_select', false ) ) {
			return;
		}

		// Show country selector if set is multiple shipping zones
		if ( 1 < count( $shipping_zones ) ) {
			$country_select_label = $this->get_setting( 'country_select_label' );

			if ( $country_select_label ) {
				?>
				<label for="shipping_country"
					   class="wpify-woo-delivery-date__country-select-label">
					<?php echo esc_html( $country_select_label ); ?>
				</label>
				<?php
			} else {
				?>
				<label for="shipping_country"
					   class="screen-reader-text">
					<?php esc_html_e( 'Country / region:', 'woocommerce' ); ?>
				</label>
				<?php
			}
			?>
			<select name="shipping_country" id="shipping_country"
					class="wpify-woo-delivery-date__country-select">
				<?php
				foreach ( $shipping_zones as $zone ) {
					// Skip zones without shipping methods
					if ( empty( $zone['shipping_methods'] ) ) {
						continue;
					}

					$selected     = in_array( $shipping_countries[ $actual_country ], $zone_countries ) ? array_search( $shipping_countries[ $actual_country ], $zone_countries ) : '0';
					$country_code = array_search( $zone['formatted_zone_location'], $shipping_countries );
					echo '<option value="zone-' . esc_attr( $zone['id'] ) . '" data-country="' . esc_attr( $country_code ) . '" ' . selected( $selected, $zone['id'], false ) . '>' . esc_html( $zone['formatted_zone_location'] ) . '</option>';
				}
				?>
			</select>

			<?php
		}
	}

	/**
	 * Render html
	 */
	public function display_delivery_date() {
		global $product;

		$delivery_days = $this->get_setting( 'delivery_days' );

		if ( empty( $delivery_days ) || empty( WC()->countries ) ) {
			return;
		}

		$shipping_countries = WC()->countries->get_shipping_countries();
		$actual_country     = ! empty( WC()->customer ) && WC()->customer->get_shipping_country() ? WC()->customer->get_shipping_country() : WC()->countries->get_base_country();
		$shipping_zones     = $this->get_all_zones();
		$zone_countries     = [];

		// Get array of allowed and enabled countries names and unset zones without enabled methods
		foreach ( $shipping_zones as $key => $zone ) {
			$enabled_count = 0;
			if ( isset( $zone['shipping_methods'] ) ) {

				foreach ( $zone['shipping_methods'] as $method ) {
					if ( $method->enabled !== 'yes' ) {
						continue;
					}

					$enabled_count += 1;
				}
			}

			if ( $enabled_count < 1 ) {
				unset( $shipping_zones[ $key ] );
				continue;
			}

			$zone_countries[ $key ] = $zone['formatted_zone_location'];
		}

		// Set first country from zones as actual country if default country isn't exist or not in zones
		if ( ! isset( $shipping_countries[ $actual_country ] ) || ! in_array( $shipping_countries[ $actual_country ], $zone_countries ) ) {
			$actual_country = array_search( reset( $zone_countries ), $shipping_countries );
		}

		?>
		<div class="wpify-woo-delivery-date">
			<?php
			$title = $this->get_setting( 'title' );
			if ( $title ) {
				echo '<h3 class="wpify-woo-delivery-date__title">' . esc_html( $title ) . '</h3>';
			}

			$this->display_country_select( $shipping_countries, $shipping_zones, $zone_countries, $actual_country );

			$custom_dates = $product->get_meta( '_wpify_woo_delivery_dates' );
			foreach ( $delivery_days as $key => $days_group ) {
				if ( $product->is_on_backorder() ) {
					$custom_date = $custom_dates[ 'delivery_dates_' . $days_group['uuid'] ]['delivery_days_backorder'] ?? null;
					$days        = ! empty( $custom_date ) || $custom_date === '0' ? $custom_date : $days_group['delivery_days_backorder'];
				} elseif ( $product->is_in_stock() ) {
					$custom_date = $custom_dates[ 'delivery_dates_' . $days_group['uuid'] ]['delivery_days_in_stock'] ?? null;
					$days        = ! empty( $custom_date ) || $custom_date === '0' ? $custom_date : $days_group['delivery_days_in_stock'];
				} else {
					$custom_date = $custom_dates[ 'delivery_dates_' . $days_group['uuid'] ]['delivery_days_out_of_stock'] ?? null;
					$days        = ! empty( $custom_date ) || $custom_date === '0' ? $custom_date : $days_group['delivery_days_out_of_stock'];
				}
				if ( empty( $days ) && $days !== '0' || $days === '-' ) {
					continue;
				}

				// set data
				$data = array(
					'date' => $days,
				);
				if ( is_numeric( $days ) ) {
					$data['date'] = $this->get_formatted_date( $days, $days_group );
				}

				if ( str_contains( $days, '-' ) ) {
					$range = explode( '-', $days );
					$days  = [];
					foreach ( $range as $day ) {
						$days[] = $this->get_formatted_date( $day, $days_group );
					}
					$data['date'] = implode( '–', $days );
				}

				$data['message']          = $days_group['delivery_date_message'] ?? '';
				$data['more_info_label']  = $this->get_setting( 'more_info_label' );
				$data['more_info_text']   = $days_group['delivery_date_info'] ?? null;
				$data['shipping_methods'] = $days_group['shipping_methods'] ?? [];

				/**
				 * Filter to change delivery date group data
				 *
				 * @param array $data current group data
				 */
				$data = apply_filters( 'wpify_woo_delivery_dates_data', $data );

				// don't render date line if message not exist
				if ( empty( $data['message'] ) ) {
					continue;
				}

				$more_info = $data['more_info_label'] && ( $data['more_info_text'] || $data['shipping_methods'] );
				$message   = str_replace( '%date%', '<span>' . $data['date'] . '</span>', $data['message'] );

				// save all shipping zones
				$all_shipping_zones = $shipping_zones;

				// Get array of zones in current day group
				$allowed_zones = [];
				foreach ( $shipping_zones as $zone_key => $zone ) {

					// Skip methods only if is set
					if ( ! empty( $data['shipping_methods'] ) ) {

						// Remove unassigned shipping methods
						foreach ( $zone['shipping_methods'] as $shipping_key => $method ) {
							if ( ! in_array( $method->get_rate_id(), $data['shipping_methods'] ) ) {
								unset( $shipping_zones[ $zone_key ]['shipping_methods'][ $shipping_key ] );
							}
						}

						// Skip zones without shipping methods
						if ( empty( $shipping_zones[ $zone_key ]['shipping_methods'] ) ) {
							continue;
						}
					}

					$allowed_zones[] = '"zone-' . $zone_key . '"';
				}

				// get selected zone
				$selected = in_array( $shipping_countries[ $actual_country ], $zone_countries ) ? array_search( $shipping_countries[ $actual_country ], $zone_countries ) : '0';

				?>
				<div class="wpify-woo-delivery-date__line"
					 data-zones='[<?= implode( ',', $allowed_zones ) ?>]'
					 style="<?= ! in_array( '"zone-' . $selected . '"', $allowed_zones ) ? 'display:none' : '' ?>">
					<p>
						<?php echo $message; ?>
						<?php if ( $more_info ) { ?>
							<a href="#<?php echo $key; ?>"
							   data-id="wpify-woo-delivery-date-<?php echo $key; ?>"><?php echo $data['more_info_label']; ?></a>
						<?php } ?>
					</p>
					<?php if ( $more_info ) { ?>
						<div id="wpify-woo-delivery-date-<?php echo $key; ?>" class="wpify-woo-delivery-date__info">
							<?php echo apply_filters( 'the_content', $data['more_info_text'] ); ?>
							<?php
							if ( $data['shipping_methods'] ) {
								$this->display_delivery_methods( $data['shipping_methods'], $shipping_countries, $shipping_zones, $actual_country );
							} ?>
						</div>
					<?php } ?>
				</div>
				<?php
				// reset shipping zones
				$shipping_zones = $all_shipping_zones;
			} ?>

			<?php
			$payments_data = array(
				'message'         => $this->get_setting( 'payments_message' ),
				'more_info_label' => $this->get_setting( 'more_info_label' ),
				'more_info_text'  => $this->get_setting( 'payments_info' ),
			);
			/**
			 * Filter to change delivery date payments message
			 *
			 * @param array $payments_data data from settings
			 */
			$payments_data = apply_filters( 'wpify_woo_delivery_dates_payments_data', $payments_data );

			if ( ! empty( $payments_data['message'] ) ) {
				?>
				<p class="wpify-woo-delivery-date__line">
					<?php echo $payments_data['message']; ?>
					<a href="#"
					   data-id="wpify-woo-delivery-date-payment"><?php echo $payments_data['more_info_label']; ?></a>
				</p>
				<div id="wpify-woo-delivery-date-payment" class="wpify-woo-delivery-date__info">
					<?php echo $payments_data['more_info_text'] ?? ''; ?>
					<?php $this->display_payment_methods(); ?>
				</div>
				<?php
			}
			?>
		</div>
		<?php

	}

	/**
	 * Render the [wpify_woo_delivery_dates] shortcode.
	 *
	 * @return string
	 */
	public function delivery_date_shortcode(): string {
		ob_start();
		$this->display_delivery_date();

		return ob_get_clean();
	}

	/**
	 * Convert old product data to new structure
	 */
	public function convert_old_product_data() {
		if ( ! isset( $_GET['wpify-delivery-dates-convert-data'] ) ) {
			return;
		};

		$delivery_days = $this->get_setting( 'delivery_days' ) ?? [];

		if ( ! isset( $delivery_days[0]['uuid'] ) ) {
			$return_url = add_query_arg( array(
				'wpf-delivery-dates-data-migrated' => 'migrate-error',
			), $this->get_settings_url() );

			wp_safe_redirect( $return_url, 302, 'WPifyWooDeliveryDates' );
			exit();
		}

		$params = array(
			'post_type'      => 'product',
			'meta_query'     => array(
				array(
					'key' => '_wpify_woo_delivery_dates',
				)
			),
			'posts_per_page' => - 1

		);

		$products = get_posts( $params );
		$success  = 0;

		foreach ( $products as $product ) {
			$post_meta = get_post_meta( $product->ID, '_wpify_woo_delivery_dates', true );

			if ( empty( $post_meta ) ) {
				continue;
			}

			$new_meta = [];
			foreach ( $delivery_days as $key => $day ) {

				if ( isset( $post_meta[ 'delivery_dates_' . $day['uuid'] ] ) ) {
					continue;
				};

				$new_meta[ 'delivery_dates_' . $day['uuid'] ] = $post_meta[ 'delivery_dates_' . $key ];
			}

			if ( ! empty( $new_meta ) ) {
				update_post_meta( $product->ID, '_wpify_woo_delivery_dates', $new_meta );
				$success += 1;
			}
		}

		$return_url = add_query_arg( array(
			'wpf-delivery-dates-data-migrated' => 'migrate-data',
			'success'                          => $success,
		), $this->get_settings_url() );

		wp_safe_redirect( $return_url, 302, 'WPifyWooDeliveryDates' );
		exit();
	}

	/**
	 * Show admin notices
	 */
	public function maybe_show_notice() {
		$process = $_GET['wpf-delivery-dates-data-migrated'] ?? null;

		if ( ! empty( $process ) ) {
			$success = $_GET['success'] ?? '';
			if ( $process === 'migrate-data' ) {
				$string = sprintf( __( 'Wpify Woo delivery date data migration is success for %s products.', 'wpify-woo' ), $success );
			} else {
				$string = sprintf( __( 'Wpify Woo delivery date data migration failed.', 'wpify-woo' ), $success );
			}

			printf( '<div class="notice-success notice"><p>%s</p></div>', $string );
		}
	}

	/**
	 * Notice that the data structure has changed
	 */
	public function delivery_dates_removed_notice() {
		if ( get_option( 'wpify_delivery_dates_admin_notice_dismissed' ) ) {
			return;
		}

		$title  = __( 'You need to update the Wpify Woo delivery date settings data!', 'wpify-woo' );
		$string = sprintf(
			__( 'For functional reasons, the savings data in the settings of individual products has been changed. If you are editing the delivery date settings at the individual product level, you need to update and convert the settings. <ol><li>Go to <a href="%s">Delivery date module settings</a> and save the settings to re-save the data.</li><li>Click on this link to <a href="%s">converting old data to the new structure</a> for individual products.</li></ol>', 'wpify-woo' ),
			admin_url( 'admin.php?page=wc-settings&tab=wpify-woo-settings&section=delivery_dates' ),
			admin_url( 'admin.php?page=wc-settings&tab=wpify-woo-settings&section=delivery_dates&wpify-delivery-dates-convert-data' )
		);
		printf( '<div class="notice notice-warning is-dismissible wpify-delivery-dates-notice"><h2 style="color: darkred">%s</h2>%s</div>', $title, $string );
	}

	/**
	 * Save the information that you dismissed the message
	 */
	public function wpify_delivery_dates_dismiss_admin_notice() {
		update_option( 'wpify_delivery_dates_admin_notice_dismissed', true );
	}

	/**
	 * Dismiss the message
	 */
	public function make_delivery_dates_admin_notice_dismissable() {
		if ( get_option( 'wpify_delivery_dates_admin_notice_dismissed' ) ) {
			return;
		}

		$script = "jQuery(document).on('click','.wpify-delivery-dates-notice .notice-dismiss',function(){jQuery.post(ajaxurl,{action:'wpify_delivery_dates_dismiss_notice'});});";
		wp_add_inline_script( 'jquery', $script );
	}

}
