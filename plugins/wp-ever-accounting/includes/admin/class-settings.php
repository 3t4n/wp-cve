<?php
/**
 * Admin Settings.
 *
 * @since       1.0.2
 * @subpackage  Admin
 * @package     EverAccounting
 */

namespace EverAccounting\Admin;

defined( 'ABSPATH' ) || exit();

/**
 * Class Settings
 *
 * @since   1.0.2
 * @package EverAccounting\Admin
 */
class Settings {
	/**
	 * Contains registered fields.
	 *
	 * @var array
	 */
	protected $settings = array();

	/**
	 * Settings constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'init_settings' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'register_settings_page' ), 100 );
	}

	/**
	 * Initialize plugin settings.
	 *
	 * @return void
	 */
	public function init_settings() {
		$settings = array(
			'general'    => array(
				'title'    => __( 'General', 'wp-ever-accounting' ),
				'sections' => array(
					'main'     => array(
						'title'  => __( 'General', 'wp-ever-accounting' ),
						'fields' => array(
							array(
								'id'    => 'company_settings',
								'title' => __( 'Company Settings', 'wp-ever-accounting' ),
								'desc'  => 'company related settings.',
								'type'  => 'section',
							),
							array(
								'id'          => 'company_name',
								'title'       => __( 'Name', 'wp-ever-accounting' ),
								'type'        => 'text',
								'required'    => 'required',
								'placeholder' => __( 'XYZ Company', 'wp-ever-accounting' ),
							),
							array(
								'id'                => 'company_email',
								'title'             => __( 'Email', 'wp-ever-accounting' ),
								'type'              => 'email',
								'default'           => get_option( 'admin_email' ),
								'sanitize_callback' => 'sanitize_email',
							),
							array(
								'id'    => 'company_phone',
								'title' => __( 'Phone Number', 'wp-ever-accounting' ),
								'type'  => 'text',
							),
							array(
								'id'    => 'company_vat_number',
								'title' => __( 'VAT Number', 'wp-ever-accounting' ),
								'type'  => 'text',
							),
							array(
								'id'    => 'company_address',
								'title' => __( 'Street', 'wp-ever-accounting' ),
								'type'  => 'text',
							),
							array(
								'id'    => 'company_city',
								'title' => __( 'City', 'wp-ever-accounting' ),
								'type'  => 'text',
							),
							array(
								'id'    => 'company_state',
								'title' => __( 'State', 'wp-ever-accounting' ),
								'type'  => 'text',
							),
							array(
								'id'    => 'company_postcode',
								'title' => __( 'Postcode', 'wp-ever-accounting' ),
								'type'  => 'text',
							),
							array(
								'id'          => 'company_country',
								'title'       => __( 'Country', 'wp-ever-accounting' ),
								'type'        => 'country_select',
								'input_class' => 'ea-select2',
							),
							array(
								'id'    => 'company_logo',
								'title' => __( 'Logo', 'wp-ever-accounting' ),
								'type'  => 'file_upload',
							),
							array(
								'id'    => 'general_settings',
								'title' => __( 'General Settings', 'wp-ever-accounting' ),
								'desc'  => '',
								'type'  => 'section',
							),
							array(
								'id'      => 'financial_year_start',
								'title'   => __( 'Financial Year Start', 'wp-ever-accounting' ),
								'tooltip' => __( 'Enter the company financial start date.', 'wp-ever-accounting' ),
								'default' => '01-01',
								'class'   => 'ea-financial-start',
								'type'    => 'text',
							),
							array(
								'id'    => 'tax_enabled',
								'title' => __( 'Enable taxes', 'wp-ever-accounting' ),
								'desc'  => 'Enable tax rates and calculations',
								'type'  => 'checkbox',
							),
							array(
								'id'    => 'dashboard_transactions_limit',
								'title' => __( 'Total Transactions', 'wp-ever-accounting' ),
								'type'  => 'checkbox',
								'desc'  => 'Limit dashboard total transactions to current financial year.',
							),
							array(
								'id'    => 'local_settings',
								'title' => __( 'Default Settings', 'wp-ever-accounting' ),
								'desc'  => '',
								'type'  => 'section',
							),
							array(
								'id'          => 'default_account',
								'title'       => __( 'Account', 'wp-ever-accounting' ),
								'type'        => 'select',
								'tooltip'     => __( 'Default account will be used for automatic transactions.', 'wp-ever-accounting' ),
								'input_class' => 'ea-select2',
								'options'     => $this->get_accounts(),
							),
							array(
								'id'          => 'default_currency',
								'title'       => __( 'Currency', 'wp-ever-accounting' ),
								'type'        => 'select',
								'tooltip'     => __( 'Default currency rate will update to 1.', 'wp-ever-accounting' ),
								'input_class' => 'ea-select2',
								'options'     => $this->get_currencies(),
							),
							array(
								'id'          => 'default_payment_method',
								'title'       => __( 'Payment Method', 'wp-ever-accounting' ),
								'default'     => 'cash',
								'type'        => 'select',
								'tooltip'     => __( 'Default currency rate will update to 1.', 'wp-ever-accounting' ),
								'input_class' => 'ea-select2',
								'options'     => eaccounting_get_payment_methods(),
							),
						),
					),
					'invoices' => array(
						'title'  => __( 'Invoices', 'wp-ever-accounting' ),
						'fields' => array(
							array(
								'id'      => 'invoice_prefix',
								'title'   => __( 'Invoice Prefix', 'wp-ever-accounting' ),
								'default' => 'INV-',
								'type'    => 'text',
							),
							array(
								'id'      => 'invoice_digit',
								'title'   => __( 'Minimum Digits', 'wp-ever-accounting' ),
								'default' => '5',
								'type'    => 'number',
							),
							array(
								'id'      => 'invoice_terms',
								'title'   => __( 'Invoice Terms', 'wp-ever-accounting' ),
								'default' => '',
								'type'    => 'textarea',
							),
							array(
								'id'      => 'invoice_note',
								'title'   => __( 'Invoice Note', 'wp-ever-accounting' ),
								'default' => '',
								'type'    => 'textarea',
							),
							array(
								'id'      => 'invoice_due',
								'title'   => __( 'Invoice Due', 'wp-ever-accounting' ),
								'default' => '15',
								'type'    => 'select',
								'options' => array(
									'7'  => __( 'Due within 7 days', 'wp-ever-accounting' ),
									'15' => __( 'Due within 15 days', 'wp-ever-accounting' ),
									'30' => __( 'Due within 30 days', 'wp-ever-accounting' ),
									'45' => __( 'Due within 45 days', 'wp-ever-accounting' ),
									'60' => __( 'Due within 60 days', 'wp-ever-accounting' ),
									'90' => __( 'Due within 90 days', 'wp-ever-accounting' ),
								),
							),
							array(
								'id'      => 'invoice_item_label',
								'title'   => __( 'Item Label', 'wp-ever-accounting' ),
								'default' => __( 'Item', 'wp-ever-accounting' ),
								'type'    => 'text',
							),
							array(
								'id'      => 'invoice_price_label',
								'title'   => __( 'Price Label', 'wp-ever-accounting' ),
								'default' => __( 'Price', 'wp-ever-accounting' ),
								'type'    => 'text',
							),
							array(
								'id'      => 'invoice_quantity_label',
								'title'   => __( 'Quantity Label', 'wp-ever-accounting' ),
								'default' => __( 'Quantity', 'wp-ever-accounting' ),
								'type'    => 'text',
							),
						),
					),
					'bills'    => array(
						'title'  => __( 'Bills', 'wp-ever-accounting' ),
						'fields' => array(
							array(
								'id'      => 'bill_prefix',
								'title'   => __( 'Bill Prefix', 'wp-ever-accounting' ),
								'default' => 'BILL-',
								'type'    => 'text',
							),
							array(
								'id'      => 'bill_digit',
								'title'   => __( 'Bill Digits', 'wp-ever-accounting' ),
								'default' => '5',
								'type'    => 'number',
							),
							array(
								'id'      => 'bill_terms',
								'title'   => __( 'Bill Terms & Conditions', 'wp-ever-accounting' ),
								'default' => '',
								'type'    => 'textarea',
							),
							array(
								'id'      => 'bill_note',
								'title'   => __( 'Bill Note', 'wp-ever-accounting' ),
								'default' => '',
								'type'    => 'textarea',
							),
							array(
								'id'      => 'bill_due',
								'title'   => __( 'Bill Due', 'wp-ever-accounting' ),
								'default' => '15',
								'type'    => 'select',
								'options' => array(
									'7'  => __( 'Due within 7 days', 'wp-ever-accounting' ),
									'15' => __( 'Due within 15 days', 'wp-ever-accounting' ),
									'30' => __( 'Due within 30 days', 'wp-ever-accounting' ),
									'45' => __( 'Due within 45 days', 'wp-ever-accounting' ),
									'60' => __( 'Due within 60 days', 'wp-ever-accounting' ),
									'90' => __( 'Due within 90 days', 'wp-ever-accounting' ),
								),
							),
							array(
								'id'      => 'bill_item_label',
								'title'   => __( 'Item Label', 'wp-ever-accounting' ),
								'default' => __( 'Item', 'wp-ever-accounting' ),
								'type'    => 'text',
							),
							array(
								'id'      => 'bill_price_label',
								'title'   => __( 'Price Label', 'wp-ever-accounting' ),
								'default' => __( 'Price', 'wp-ever-accounting' ),
								'type'    => 'text',
							),
							array(
								'id'      => 'bill_quantity_label',
								'title'   => __( 'Quantity Label', 'wp-ever-accounting' ),
								'default' => __( 'Quantity', 'wp-ever-accounting' ),
								'type'    => 'text',
							),
						),
					),
				),
			),
			'categories' => array(
				'title' => __( 'Categories', 'wp-ever-accounting' ),
			),
			'currencies' => array(
				'title' => __( 'Currencies', 'wp-ever-accounting' ),
			),
			'extensions' => array(
				'title'    => __( 'Extensions', 'wp-ever-accounting' ),
				'sections' => apply_filters( 'eaccounting_settings_extensions', array() ),
			),
			'licenses'   => array(
				'title'  => __( 'Licenses', 'wp-ever-accounting' ),
				'fields' => apply_filters( 'eaccounting_settings_licenses', array() ),
			),
		);

		if ( eaccounting_tax_enabled() ) {
			$settings['general']['sections']['tax_settings'] = array(
				'title'  => __( 'Tax', 'wp-ever-accounting' ),
				'fields' => array(
					array(
						'id'    => 'tax_subtotal_rounding',
						'title' => __( 'Rounding', 'wp-ever-accounting' ),
						'type'  => 'checkbox',
						'desc'  => __( 'Round tax at subtotal level, instead of rounding per tax rate.', 'wp-ever-accounting' ),
					),
					array(
						'id'      => 'prices_include_tax',
						'title'   => __( 'Prices entered with tax', 'wp-ever-accounting' ),
						'type'    => 'select',
						'default' => 'yes',
						'options' => array(
							'yes' => __( 'Yes, I will enter prices inclusive of tax', 'wp-ever-accounting' ),
							'no'  => __( 'No, I will enter prices exclusive of tax', 'wp-ever-accounting' ),
						),
					),
					array(
						'id'      => 'tax_display_totals',
						'title'   => __( 'Display tax totals	', 'wp-ever-accounting' ),
						'type'    => 'select',
						'default' => 'total',
						'options' => array(
							'total'      => __( 'As a single total', 'wp-ever-accounting' ),
							'individual' => __( 'As individual tax rates', 'wp-ever-accounting' ),
						),
					),
				),
			);
		}

		$this->settings = $settings;
	}

	/**
	 * Add all settings sections and fields
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public function register_settings() {
		$whitelisted = array();
		foreach ( $this->settings as $tab => $setting ) {
			if ( ! empty( $setting['fields'] ) ) {
				$setting['sections']['']['fields'] = $setting['fields'];
			}
			// Bail if no sections.
			if ( empty( $setting['sections'] ) ) {
				continue;
			}

			foreach ( $setting['sections'] as $section_id => $section ) {
				// Bail if no fields.
				if ( empty( $section['fields'] ) ) {
					continue;
				}

				add_settings_section(
					$section_id,
					__return_null(),
					'__return_false',
					'eaccounting_settings_' . $tab . '_' . $section_id
				);

				foreach ( $section['fields'] as $field ) {
					// Bail if no fields.
					if ( empty( $field['id'] ) ) {
						continue;
					}
					// Restrict duplicate.
					if ( in_array( $field['id'], $whitelisted, true ) ) {
						continue;
					}

					$args = wp_parse_args(
						$field,
						array(
							'id'          => $field['id'],
							'title'       => '',
							'type'        => 'text',
							'desc'        => '',
							'tooltip'     => '',
							'size'        => 'regular',
							'options'     => array(),
							'default'     => '',
							'min'         => null,
							'max'         => null,
							'step'        => null,
							'multiple'    => null,
							'placeholder' => null,
							'required'    => '',
							'disabled'    => '',
							'input_class' => '',
							'class'       => '',
							'callback'    => '',
							'style'       => '',
							'html'        => '',
							'attrs'       => array(),
							'args'        => array(),
						)
					);

					$tooltip = wp_kses(
						html_entity_decode( $args['tooltip'] ),
						array(
							'br'     => array(),
							'em'     => array(),
							'strong' => array(),
							'small'  => array(),
							'span'   => array(),
							'ul'     => array(),
							'li'     => array(),
							'ol'     => array(),
							'p'      => array(),
						)
					);

					if ( ! in_array(
						$args['type'],
						array(
							'checkbox',
							'multicheck',
							'radio',
						),
						true
					) && ! empty( $tooltip ) ) {
						$args['title']     = sprintf( '%s<span class="ea-help-tip" title="%s"></span>', esc_html( $args['title'] ), wp_kses_post( $tooltip ) );
						$args['label_for'] = $args['id'];
					}
					if ( 'section' === $args['type'] ) {
						$args['title'] = sprintf( '<h3>%s</h3>', esc_html( $args['title'] ) );
					}

					$callback = ! empty( $args['callback'] ) ? $args['callback'] : array( $this, 'render_field' );
					add_settings_field(
						$args['id'],
						$args['title'],
						is_callable( $callback ) ? $callback : '__return_false',
						'eaccounting_settings_' . $tab . '_' . $section_id,
						$section_id,
						$args
					);

				}
			}
		}

		register_setting( 'eaccounting_settings', 'eaccounting_settings', array( $this, 'sanitize_settings' ) );
	}

	/**
	 * Render settings field
	 *
	 * @param array $field Field data.
	 */
	public function render_field( $field ) {
		$description = '';
		if ( ! empty( $field['desc'] ) && in_array( $field['type'], array( 'textarea', 'radio' ), true ) ) {
			$description = '<p style="margin-top:0">' . wp_kses_post( $field['desc'] ) . '</p>';
		} elseif ( ! empty( $field['desc'] ) && 'checkbox' === $field['type'] ) {
			$description = wp_kses_post( $field['desc'] );
		} elseif ( ! empty( $field['desc'] ) ) {
			$description = '<p class="description">' . wp_kses_post( $field['desc'] ) . '</p>';
		}
		$value = eaccounting_get_option( $field['id'], $field['default'] );
		// Switch based on type.
		switch ( $field['type'] ) {
			// Standard text inputs and subtypes like 'number'.
			case 'text':
			case 'password':
			case 'datetime':
			case 'date':
			case 'month':
			case 'time':
			case 'week':
			case 'number':
			case 'email':
			case 'url':
			case 'tel':
				?>
				<input
						name="eaccounting_settings[<?php echo esc_attr( $field['id'] ); ?>]" id="<?php echo esc_attr( $field['id'] ); ?>" type="<?php echo esc_attr( $field['type'] ); ?>"
						style="<?php echo esc_attr( $field['style'] ); ?>"
						value="<?php echo esc_attr( wp_unslash( $value ) ); ?>"
						class="<?php echo esc_attr( sprintf( '%s-text %s', $field['size'], $field['input_class'] ) ); ?>"
						placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" />
				<?php echo wp_kses_post( $description ); ?>
				<?php
				break;
			case 'textarea':
				echo wp_kses_post( $description );
				?>
				<textarea
						name="eaccounting_settings[<?php echo esc_attr( $field['id'] ); ?>]"
						id="<?php echo esc_attr( $field['id'] ); ?>"
						style="<?php echo esc_attr( $field['style'] ); ?>"
						class="<?php echo esc_attr( sprintf( '%s-text %s', $field['size'], $field['input_class'] ) ); ?>"
						placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>"><?php echo esc_textarea( wp_unslash( $value ) ); ?></textarea>
				<?php
				break;
			case 'country_select':
			case 'select':
				if ( 'country_select' === $field['type'] ) {
					$field['options'] = array( '' => __( 'Select Country', 'wp-ever-accounting' ) ) + eaccounting_get_countries();
				}
				?>
				<select
						name="eaccounting_settings[<?php echo esc_attr( $field['id'] ); ?><?php echo ( 'multiselect' === $field['type'] ) ? '[]' : ''; ?>]"
						id="<?php echo esc_attr( $field['id'] ); ?>"
						style="<?php echo esc_attr( $field['style'] ); ?>"
						class="<?php echo esc_attr( sprintf( '%s-text %s', $field['size'], $field['input_class'] ) ); ?>"
				>
					<?php
					foreach ( $field['options'] as $key => $val ) {
						?>
						<option value="<?php echo esc_attr( $key ); ?>"
								<?php
								if ( is_array( $value ) ) {
									selected( in_array( (string) $key, $value, true ), true );
								} else {
									selected( $value, (string) $key );
								}
								?>
						><?php echo esc_html( $val ); ?></option>
						<?php
					}
					?>
				</select> <?php echo wp_kses_post( $description ); ?>
				<?php
				break;
			case 'checkbox':
				?>
				<label for="<?php echo esc_attr( $field['id'] ); ?>">
					<input
							name="eaccounting_settings[<?php echo esc_attr( $field['id'] ); ?>]"
							id="<?php echo esc_attr( $field['id'] ); ?>"
							type="checkbox"
							value="yes"
							<?php checked( $value, 'yes' ); ?>
					/> <?php echo wp_kses_post( $description ); ?>
				</label>
				<?php
				break;

			case 'radio':
			case 'multicheck':
				$value = ! is_array( $value ) ? array() : $value;
				$type  = 'multicheck' === $field['type'] ? 'checkbox' : $field['type'];
				?>
				<fieldset>
					<?php echo wp_kses_post( $description ); ?>
					<ul>
						<?php
						foreach ( $field['options'] as $key => $option ) {
							$checked = isset( $value[ $key ] ) ? $value[ $key ] : 'no';
							?>
							<li>
								<label><input
											name="eaccounting_settings[<?php echo esc_attr( $field['id'] ); ?>][<?php echo esc_attr( $key ); ?>]"
											value="yes"
											type="<?php echo esc_attr( $type ); ?>"
											style="<?php echo esc_attr( $field['style'] ); ?>"
											class="<?php echo esc_attr( $field['class'] ); ?>"
											<?php checked( 'yes', $checked ); ?>
									/> <?php echo esc_html( $option ); ?></label>
							</li>
							<?php
						}
						?>
					</ul>
				</fieldset>
				<?php
				break;
			case 'wysiwyg':
				wp_editor( stripslashes( $value ), 'eaccounting_settings_' . $field['id'], array( 'textarea_name' => 'eaccounting_settings[' . $field['id'] . ']' ) );
				break;

			case 'file_upload':
				?>
				<?php echo wp_kses_post( $description ); ?>
				<input
						name="eaccounting_settings[<?php echo esc_attr( $field['id'] ); ?>]"
						id="<?php echo esc_attr( $field['id'] ); ?>"
						type="text"
						style="<?php echo esc_attr( $field['style'] ); ?>"
						value="<?php echo esc_attr( wp_unslash( $value ) ); ?>"
						class="<?php echo esc_attr( sprintf( '%s-text %s', $field['size'], $field['input_class'] ) ); ?>"
				<span>&nbsp;<button type="button" class="ea_settings_upload_button button-secondary"><?php esc_html_e( 'Upload File', 'wp-ever-accounting' ); ?></button></span>
				<?php
				break;
			case 'section':
				if ( ! empty( $field['desc'] ) ) {
					echo wp_kses_post( wpautop( wptexturize( $field['desc'] ) ) );
				}
				break;
			// Default: run an action.
			default:
				do_action( 'eaccounting_admin_field_' . $field['type'], $field );
				break;
		}
	}

	/**
	 * Load accounts on settings.
	 *
	 * @since 1.1.0
	 * @return array|int
	 */
	protected function get_accounts() {
		$page     = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		$accounts = array();
		if ( 'ea-settings' === $page ) {
			$results    = eaccounting_get_accounts(
				array(
					'number' => - 1,
					'return' => 'raw',
				)
			);
			$accounts[] = __( 'Select account', 'wp-ever-accounting' );
			foreach ( $results as $result ) {
				$accounts[ $result->id ] = $result->name . '(' . $result->currency_code . ')';
			}
		}

		return $accounts;
	}

	/**
	 * Load categories on settings.
	 *
	 * @param string $type Type of category.
	 *
	 * @since 1.1.0
	 *
	 * @return array|int
	 */
	protected function get_categories( $type = 'income' ) {
		$page       = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		$categories = array();
		if ( 'ea-settings' === $page ) {
			$results      = eaccounting_get_categories(
				array(
					'number' => - 1,
					'type'   => $type,
					'return' => 'raw',
				)
			);
			$categories[] = __( 'Select category', 'wp-ever-accounting' );
			$categories   = array_merge( $categories, wp_list_pluck( $results, 'name', 'id' ) );
		}

		return $categories;
	}

	/**
	 * Load currencies
	 *
	 * @since 1.1.0
	 *
	 * @return array|int
	 */
	protected function get_currencies() {
		$currencies = array();
		$page       = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		if ( 'ea-settings' === $page ) {
			$results      = eaccounting_get_currencies(
				array(
					'number' => - 1,
					'return' => 'raw',
				)
			);
			$currencies[] = __( 'Select currencies', 'wp-ever-accounting' );
			foreach ( $results as $result ) {
				$currencies[ $result->code ] = $result->name . '(' . $result->symbol . ')';
			}
		}

		return $currencies;
	}

	/**
	 * Retrieve the array of plugin settings
	 *
	 * @param array $input The value inputted in the field.
	 *
	 * @since 1.0.2
	 * @return array
	 */
	public function sanitize_settings( $input = array() ) {
		if ( empty( $_POST['_wp_http_referer'] ) ) {
			return $input;
		}

		parse_str( wp_get_referer(), $referrer );
		$fields          = array();
		$tabs            = array_keys( $this->settings );
		$current_tab     = isset( $referrer['tab'] ) && in_array( $referrer['tab'], $tabs, true ) ? sanitize_title( $referrer['tab'] ) : current( $tabs );
		$sections        = $this->settings[ $current_tab ];
		$sections        = ! empty( $sections['sections'] ) ? $sections['sections'] : array();
		$current_section = isset( $referrer['section'] ) && array_key_exists( $referrer['section'], $sections ) ? sanitize_title( $referrer['section'] ) : current( array_keys( $sections ) );

		if ( ! empty( $this->settings[ $current_tab ]['sections'][ $current_section ]['fields'] ) ) {
			$fields = $this->settings[ $current_tab ]['sections'][ $current_section ]['fields'];
		}

		$input = $input ? $input : array();
		foreach ( $fields as $field ) {
			if ( ! isset( $field['id'] ) || ! isset( $field['type'] ) ) {
				continue;
			}
			$raw_value = isset( $input[ $field['id'] ] ) ? wp_unslash( $input[ $field['id'] ] ) : null;
			$name      = $field['id'];
			// Format the value based on option type.
			switch ( $field['type'] ) {
				case 'checkbox':
					$value = ! empty( $raw_value ) ? 'yes' : 'no';
					break;
				case 'textarea':
					$value = wp_kses_post( trim( $raw_value ) );
					break;
				case 'multicheck':
					$raw_value      = ! is_array( $raw_value ) || empty( $raw_value ) ? array() : $raw_value;
					$allowed_values = empty( $field['options'] ) ? array() : array_map( 'strval', array_keys( $field['options'] ) );
					$value          = array();
					foreach ( $allowed_values as $allowed_value ) {
						$value[ $allowed_value ] = array_key_exists( $allowed_value, $raw_value ) ? 'yes' : 'no';
					}
					break;
				case 'select':
				default:
					$value = eaccounting_clean( $raw_value );
					break;
			}

			$sanitize_callback = isset( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : false;
			if ( $sanitize_callback && is_callable( $sanitize_callback ) ) {
				$value = call_user_func( $sanitize_callback, $value );
			}

			/**
			 * Sanitize the value of an option.
			 *
			 * @since 1.1.3
			 */
			$value = apply_filters( 'eaccounting_admin_settings_sanitize_option', $value, $field, $raw_value );

			/**
			 * Sanitize the value of an option by option name.
			 *
			 * @since 1.1.3
			 */
			$value = apply_filters( "eaccounting_admin_settings_sanitize_option_$name", $value, $field, $raw_value );

			if ( is_null( $value ) ) {
				continue;
			}

			$input[ $name ] = $value;
		}

		$saved = get_option( 'eaccounting_settings', array() );
		if ( ! is_array( $saved ) ) {
			$saved = array();
		}

		add_settings_error( 'eaccounting-notices', '', __( 'Settings updated.', 'wp-ever-accounting' ), 'updated' );

		return array_merge( $saved, $input );
	}

	/**
	 * Registers the page.
	 */
	public function register_settings_page() {
		add_submenu_page(
			'eaccounting',
			__( 'Settings', 'wp-ever-accounting' ),
			__( 'Settings', 'wp-ever-accounting' ),
			'ea_manage_options',
			'ea-settings',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Displays the settings page.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function render_settings_page() {
		$settings          = $this->settings;
		$tabs              = wp_list_pluck( $settings, 'title' );
		$requested_tab     = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
		$current_tab       = ! empty( $requested_tab ) && array_key_exists( $requested_tab, $tabs ) ? sanitize_title( $requested_tab ) : current( array_keys( $tabs ) );
		$sections          = array_key_exists( 'sections', $settings[ $current_tab ] ) && ! empty( $settings[ $current_tab ]['sections'] ) ? $settings[ $current_tab ]['sections'] : array();
		$sections          = wp_list_pluck( $sections, 'title' );
		$requested_section = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_STRING );
		$current_section   = ! empty( $requested_section ) && array_key_exists( $requested_section, $sections ) ? sanitize_title( $requested_section ) : current( array_keys( $sections ) );
		$menu_tabs         = apply_filters( 'eaccounting_settings_menu_tabs', $tabs );

		foreach ( array_keys( $menu_tabs ) as $tab ) {
			if ( empty( $settings[ $tab ]['sections'] ) && empty( $settings[ $tab ]['fields'] ) && ! has_action( 'eaccounting_settings_tab_' . $tab ) ) {
				unset( $tabs[ $tab ] );
			}
		}

		// Section have name but not in url then redirect.
		if ( ! empty( $current_section ) && empty( $requested_section ) ) {
			wp_safe_redirect( add_query_arg( array( 'section' => $current_section ) ) );
			exit();
		}

		$subsub_links = array();
		foreach ( $sections as $section_slug => $section_title ) {
			if ( empty( $settings[ $current_tab ]['sections'][ $current_section ]['fields'] ) && ! has_action( 'eaccounting_settings_tab_' . $current_tab . '_' . $current_section . '_content' ) ) {
				unset( $sections[ $section_slug ] );
			}
			$link           = add_query_arg(
				array(
					'tab'     => $current_tab,
					'section' => $section_slug,
				)
			);
			$active         = ( $current_section === $section_slug ) ? 'current' : '';
			$subsub_links[] = sprintf( '<a href="%s" class="%s">%s</a>', esc_url( $link ), sanitize_html_class( $active ), esc_html( $section_title ) );
		}
		ob_start();
		?>
		<div class="wrap ea-settings">
			<h2><?php esc_html_e( 'Ever Accounting - Settings', 'wp-ever-accounting' ); ?></h2>
			<?php if ( count( $menu_tabs ) > 1 ) : ?>
				<h2 class="nav-tab-wrapper ea-tab-wrapper">
					<?php foreach ( $tabs as $tab_slug => $tab_title ) : ?>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=ea-settings' . ( empty( $tab_slug ) ? '' : '&tab=' . $tab_slug ) ) ); ?>" class="nav-tab <?php echo sanitize_html_class( ( $current_tab === $tab_slug ) ? 'nav-tab-active' : '' ); ?>">
							<?php echo esc_html( $tab_title ); ?>
						</a>
					<?php endforeach; ?>
				</h2>
			<?php endif; ?>
			<?php if ( count( $sections ) > 1 ) : ?>
				<ul class="subsubsub">
					<?php echo wp_kses_post( implode( ' | </li><li>', $subsub_links ) ); ?>
				</ul>
			<?php endif; ?>
			<br class="clear"/>
			<h1 class="screen-reader-text"><?php echo esc_html( $tabs[ $current_tab ] ); ?></h1>
			<?php do_action( 'eaccounting_settings_page_before_' . $current_tab . '_' . $current_section . '_content' ); ?>
			<?php
			if ( has_action( 'eaccounting_settings_tab_' . $current_tab ) ) {
				do_action( 'eaccounting_settings_tab_' . $current_tab );
			} elseif ( has_action( 'eaccounting_settings_tab_' . $current_tab . '_' . $current_section . '_content' ) ) {
				do_action( 'eaccounting_settings_tab_' . $current_tab . '_' . $current_section . '_content' );
			} else {
				?>
				<form method="post" id="mainform" action="options.php" enctype="multipart/form-data">
					<?php settings_errors(); ?>
					<?php settings_fields( 'eaccounting_settings' ); ?>
					<?php do_settings_sections( "eaccounting_settings_{$current_tab}_{$current_section}" ); ?>
					<?php if ( empty( $GLOBALS['hide_save_button'] ) ) : ?>
						<?php submit_button(); ?>
					<?php endif; ?>

				</form>
			<?php } ?>

		</div>
		<?php
	}
}

return new Settings();
