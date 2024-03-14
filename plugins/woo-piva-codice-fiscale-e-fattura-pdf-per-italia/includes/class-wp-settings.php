<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WC_Piva_Cf_Invoice_Ita_Setting' ) ) {

	class WC_Piva_Cf_Invoice_Ita_Setting {


		public $options;
		public $options_page_hook;
		public $general_settings_key = 'wcpdf_IT_general_settings';
		public $wc_cfpiva_next_receipt_number = 'wc_cfpiva_next_receipt_number';
		private $plugin_options_key = 'wcpdf_IT_options_page';
		private $plugin_settings_tabs = array();
        private $parent;

		public function __construct( $parent ) {
			global $wcpdf_IT;
			$defaults                            = array(
				'_view_selected_choices'         => array(
					'receipt'               => false,
					'invoice'               => true,
					'private_invoice'       => true,
					'professionist_invoice' => true,
				),
				'_receipt_prefix'            => '',
				'_receipt_number_padding'            => 0,
				'_set_receipt_for_zero_order'	=> false,

			);
			$this->parent = $parent;
			$settings = get_option( $this->general_settings_key );
			
			$this->options = wp_parse_args( $settings,$defaults );
			//$parent->view_selected_choices = $this->options["_view_selected_choices"];
			//$parent->receipt_label = $this->options["_receipt_label"];
			$parent->receipt_prefix = $this->options["_receipt_prefix"];
			$parent->receipt_number_padding = $this->options["_receipt_number_padding"];
			$parent->set_receipt_for_zero_order = $this->options["_set_receipt_for_zero_order"]; 
			//$parent->private_invoice_label = $this->options["_private_invoice_label"];
			//$parent->company_invoice_label = $this->options["_company_invoice_label"];
			//$parent->professionist_invoice_label = $this->options["_professionist_invoice_label"];
			add_action( 'admin_menu', array( &$this, 'add_admin_menus' ) ); // Add menu.
			add_action( 'admin_init', array( &$this, 'register_general_settings' ) ); // Registers settings
		}

		public function add_admin_menus() {
			$parent_slug = 'woocommerce';

			$this->options_page_hook = add_submenu_page(
				$parent_slug,
				__( 'WC CF e PIVA Italia', WCPIVACF_IT_DOMAIN ),
				__( 'WC CF e PIVA Italia', WCPIVACF_IT_DOMAIN ),
				'manage_woocommerce',
				'wcpdf_IT_options_page',
				array( $this, 'settings_page' )
			);
		}

		function plugin_options_tabs() {
			$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->general_settings_key;
			echo '<h2 class="nav-tab-wrapper">';
			echo '<i class="icon icon32"></i>';
			foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
				$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
				echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->plugin_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
			}
			echo '</h2>';
		}

		public function settings_page() {
			global $wcpdf_IT;
			// This is the secret key for API authentication. You configured it in the settings menu of the license manager plugin.
			define( 'YOUR_SPECIAL_SECRET_KEY',
				'' ); //Rename this constant name so it is specific to your plugin or theme.

			// This is the URL where API query request will be sent to. This should be the URL of the site where you have installed the main license manager plugin. Get this value from the integration help page.
			define( 'YOUR_LICENSE_SERVER_URL',
				'' ); //Rename this constant name so it is specific to your plugin or theme.

			// This is a value that will be recorded in the license manager data so you can identify licenses for this item/product.
			define( 'YOUR_ITEM_REFERENCE',
				'WooCommerce P.IVA e Codice Fiscale per Italia' ); //Rename this constant name so it is specific to your plugin or theme.
			$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->general_settings_key;
			$this->plugin_options_tabs();
			switch ( $tab ) {
				case $this->general_settings_key :

					/*** License activate button was clicked ***/
					if ( isset( $_REQUEST['activate_license'] ) ) {
						$license_key = $_REQUEST['sample_license_key'];

						// API query parameters
						$api_params = array(
							'slm_action'        => 'slm_activate',
							'secret_key'        => YOUR_SPECIAL_SECRET_KEY,
							'license_key'       => $license_key,
							'registered_domain' => $_SERVER['SERVER_NAME'],
							'item_reference'    => urlencode( YOUR_ITEM_REFERENCE ),
						);

						// Send query to the license manager server
						$query    = esc_url_raw( add_query_arg( $api_params, YOUR_LICENSE_SERVER_URL ) );
						$response = wp_remote_get( $query, array( 'timeout' => 20, 'sslverify' => false ) );

						// Check for error in the response
						if ( is_wp_error( $response ) ) {
							echo "Errore imprevisto. Contattare l\'amministratore del sistema.";
						}

						//var_dump($response);//uncomment it if you want to look at the full response

						// License data.
						$license_data = json_decode( wp_remote_retrieve_body( $response ) );

						// TODO - Do something with it.
						//var_dump($license_data);//uncomment it to look at the data

						if ( $license_data->result == 'success' ) {//Success was returned for the license activation

							//Uncomment the followng line to see the message that returned from the license server
							echo '<br /><div style="border:2px solid #37ab3a;padding:20px;text-align:center;">Complimenti! La tua licenza per il plugin <b>WooCommerce P.IVA e Codice Fiscale per Italia PRO</b>  &egrave; attiva. <br />Puoi cominicare a configurare le opzioni di seguito!</div>';

							//Save the license key in the options table
							update_option( 'sample_license_key', $license_key );
						} else {
							//Show error to the user. Probably entered incorrect license key.

							//Uncomment the followng line to see the message that returned from the license server
							echo '<br />Errore imprevisto in fase di attivazione. Perfavore contattare lo sviluppatore del plugin.';
						}

					}
					/*** End of license activation ***/

					/*** License activate button was clicked ***/
					if ( isset( $_REQUEST['deactivate_license'] ) ) {
						$license_key = $_REQUEST['sample_license_key'];

						// API query parameters
						$api_params = array(
							'slm_action'        => 'slm_deactivate',
							'secret_key'        => YOUR_SPECIAL_SECRET_KEY,
							'license_key'       => $license_key,
							'registered_domain' => $_SERVER['SERVER_NAME'],
							'item_reference'    => urlencode( YOUR_ITEM_REFERENCE ),
						);

						// Send query to the license manager server
						$query    = esc_url_raw( add_query_arg( $api_params, YOUR_LICENSE_SERVER_URL ) );
						$response = wp_remote_get( $query, array( 'timeout' => 20, 'sslverify' => false ) );

						// Check for error in the response
						if ( is_wp_error( $response ) ) {
							echo "Errore imprevisto. Contattare l\'amministratore del sistema.";
						}

						//var_dump($response);//uncomment it if you want to look at the full response

						// License data.
						$license_data = json_decode( wp_remote_retrieve_body( $response ) );

						// TODO - Do something with it.
						//var_dump($license_data);//uncomment it to look at the data

						if ( $license_data->result == 'success' ) {//Success was returned for the license activation

							//Uncomment the followng line to see the message that returned from the license server
							echo '<br />La richiesta di disattivazione della licenza &egrave; andata a buon fine. Grazie per aver utilizzato il plugin <b>WooCommerce P.IVA e Codice Fiscale per Italia PRO</b>';

							//Remove the licensse key from the options table. It will need to be activated again.
							update_option( 'sample_license_key', '' );
						} else {
							//Show error to the user. Probably entered incorrect license key.

							//Uncomment the followng line to see the message that returned from the license server
							echo '<br />Errore imprevisto in fase di attivazione. Perfavore contattare lo sviluppatore del plugin.';
						}

					}
					/*** End of sample license deactivation ***/

					echo '</div>';

					?>
                    <form method="post" action="options.php" id="wcpdf-IT-settings"
                          style="border:1px solid #005677;padding:10px; background-color:#ffffff;">
						<?php
						settings_fields( $tab );
						do_settings_sections( $tab );
						?>

						<?php submit_button(); ?>
                    </form>

					<?php
					break;
			}
			echo '</div>';

		}

		public function register_general_settings() {
			$this->plugin_settings_tabs[ $this->general_settings_key ] = __( 'Settings', WCPIVACF_IT_DOMAIN );
			add_settings_section(
				$this->general_settings_key,
				__( 'General settings', WCPIVACF_IT_DOMAIN ),
				array( &$this, 'section_options_callback' ),
				$this->general_settings_key
			);


			/*add_settings_field(
				'view_selected_choices',
				__( 'enabled invoice types', WCPIVACF_IT_DOMAIN ),
				array( &$this, 'multiple_checkbox_element_callback' ),
				$this->general_settings_key,
				$this->general_settings_key,
				array(
					'menu'        => $this->general_settings_key,
					'id'          => '_view_selected_choices',
					'help'        => __( "Select which kind of invoice you want to enable", WCPIVACF_IT_DOMAIN ),
					'description' => __( "Select which kind of invoice you want to enable", WCPIVACF_IT_DOMAIN ),
					'options'     => array(
						'receipt'               => __( 'Receipt', WCPIVACF_IT_DOMAIN ),
						'invoice'               => __( 'Invoice', WCPIVACF_IT_DOMAIN ),
						'private_invoice'       => __( 'Invoice with Fiscal Code', WCPIVACF_IT_DOMAIN ),
						'professionist_invoice' => __( 'Invoice with VAT number + Fiscal Code', WCPIVACF_IT_DOMAIN )
					)
				)
			);*/
			// CHECK PRESENZA PLUGIN INVOICE PER IMPLEMENTAZIONE FATTURE
			if ( in_array( 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || (function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php' ) )) 		{
			add_settings_field(
				'receipt_prefix',
				__( 'Receipt prefix', WCPIVACF_IT_DOMAIN ),
				array( &$this, 'text_element_callback' ),
				$this->general_settings_key,
				$this->general_settings_key,
				array(
					'menu'        => $this->general_settings_key,
					'id'          => '_receipt_prefix',
					'description' => __( 'Add receipt prefix. You can leave it empty if you won\'t prefix on receipt',
						WCPIVACF_IT_DOMAIN ),
				)
			);
			add_settings_field(
				'receipt_number_padding',
				__( 'Receipt number padding', WCPIVACF_IT_DOMAIN ),
				array( &$this, 'text_element_callback' ),
				$this->general_settings_key,
				$this->general_settings_key,
				array(
					'menu'        => $this->general_settings_key,
					'id'          => '_receipt_number_padding',
					'description' => __( 'You can add receipt number padding. Add 6 to show receipt number like 000001 or leave empty to show as 1',
						WCPIVACF_IT_DOMAIN ),
				)
			);
			
			//set_receipt_for_zero_order
			add_settings_field(
				'set_receipt_for_zero_order',
				__( 'Disable for orders with all free products.', WCPIVACF_IT_DOMAIN ),
				array( &$this, 'checkbox_element_callback' ),
				$this->general_settings_key,
				$this->general_settings_key,
				array(
					'menu'        => $this->general_settings_key,
					'id'          => '_set_receipt_for_zero_order',
					'help'        => __( "<i>Check the flag for disable receipt for orders with all free products</i>",
						WCPIVACF_IT_DOMAIN ),
					'description' => __( "<i>If this is checked, the generation of receipt for orders with 0 total there aren't generated.</i>",
						WCPIVACF_IT_DOMAIN ),
				)
			);
			
			// receipt number is stored separately for direct retrieval
			register_setting( $this->general_settings_key, $this->wc_cfpiva_next_receipt_number,
				array( &$this, 'validate_options' ) );
			add_settings_field(
				'next_receipt_number',
				__( 'Next receipt number', WCPIVACF_IT_DOMAIN ),
				array( &$this, 'nextnumberreceipt_element_callback' ),
				$this->general_settings_key,
				$this->general_settings_key,
				array(
					'menu'        => $this->wc_cfpiva_next_receipt_number,
					'id'          => $this->wc_cfpiva_next_receipt_number,
					'help'        => __( "This is the number that will be used on the next receipt that is created. By default, numbering starts from the 1 and increases for every new invoice. Note that if you override this and set it lower than the highest receipt number, this could create double receipt numbers!",
						WCPIVACF_IT_DOMAIN ),
					'description' => __( "This is the number that will be used on the next receipt that is created. By default, numbering starts from the 1 and increases for every new invoice. Note that if you override this and set it lower than the highest receipt number, this could create double receipt numbers!",
						WCPIVACF_IT_DOMAIN ),
				)
			);


						register_setting( $this->general_settings_key, $this->general_settings_key,
				array( &$this, 'validate_options' ) );
		}
		}

		// Receipt number field
		public function nextnumberreceipt_element_callback( $args ) {
			global $wpdb;
			$menu = $args['menu'];
			$id   = $args['id'];
			$size = isset( $args['size'] ) ? $args['size'] : '25';

			$next_receipt_number = $this->parent->wcpdf_add_on->get_next_receipt_number();
			if ( !$next_receipt_number ) {
				// first time invoice number
                // determine highest invoice number if option not set
                // Based on code from WooCommerce Sequential Order Numbers
                // get highest invoice_number in postmeta table
                $max_receipt_number = $wpdb->get_var( 'SELECT max(cast(pm.meta_value as UNSIGNED)) from ' . $wpdb->postmeta . ' pm INNER JOIN '.$wpdb->postmeta.' pm2 ON (pm.post_id=pm2.post_id AND pm2.meta_key = "_billing_invoice_type" AND pm2.meta_value = "receipt") where pm.meta_key="_wcpdf_invoice_number"' );
                if ( !empty($max_receipt_number) ) {
                    $next_receipt_number = $max_receipt_number+1;
                } else {
                    $next_receipt_number = '';
                }

				$this->parent->wcpdf_add_on->update_next_receipt_number($next_receipt_number);
			};

			$html = sprintf( '<input type="number" step="1" id="%1$s" name="%1$s" value="%3$s" size="%4$s"/>', $id, $menu,
				$next_receipt_number, $size );
			// Displays option description.
			if ( isset( $args['description'] ) ) {
				$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
			}

			echo $html;
		}

		// Text element field
		public function text_element_callback( $args ) {
			$menu = $args['menu'];
			$id   = $args['id'];
			$size = isset( $args['size'] ) ? $args['size'] : '25';

			$options = $this->options;

			if ( isset( $options[ $id ] ) ) {
				$current = $options[ $id ];
			} else {
				$current = isset( $args['default'] ) ? $args['default'] : '';
			}

			$html = sprintf( '<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" size="%4$s"/>', $id, $menu,
				$current, $size );
			// Displays option description.
			if ( isset( $args['description'] ) ) {
				$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
			}

			echo $html;
		}

		// Textarea element callback.
		public function textarea_element_callback( $args ) {
			$menu   = $args['menu'];
			$id     = $args['id'];
			$width  = $args['width'];
			$height = $args['height'];

			$options = $this->options;

			if ( isset( $options[ $id ] ) ) {
				$current = $options[ $id ];
			} else {
				$current = isset( $args['default'] ) ? $args['default'] : '';
			}

			$html = sprintf( '<textarea id="%1$s" name="%2$s[%1$s]" cols="%4$s" rows="%5$s"/>%3$s</textarea>', $id,
				$menu, $current, $width, $height );

			// Displays option description.
			if ( isset( $args['description'] ) ) {
				$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
			}

			echo $html;
		}


		/**
		 * Checkbox field callback.
		 *
		 * @param  array $args Field arguments.
		 *
		 * @return string      Checkbox field.
		 */
		public function checkbox_element_callback( $args ) {
			$menu  = $args['menu'];
			$id    = $args['id'];
			$value = isset( $args['value'] ) ? $args['value'] : 1;

			$options = $this->options;

			if ( isset( $options[ $id ] ) ) {
				$current = $options[ $id ];
			} else {
				$current = isset( $args['default'] ) ? $args['default'] : '';
			}

			$html = sprintf( '<input type="checkbox" id="%1$s" name="%2$s[%1$s]" value="%3$s"%4$s />', $id, $menu,
				$value, checked( $value, $current, false ) );

			// Displays option description.
			if ( isset( $args['description'] ) ) {
				$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
			}

			echo $html;
		}

		/**
		 * Multiple Checkbox field callback.
		 *
		 * @param  array $args Field arguments.
		 *
		 * @return string      Checkbox field.
		 */
		public function multiple_checkbox_element_callback( $args ) {
			$menu = $args['menu'];
			$id   = $args['id'];

			$options = $this->options;

			foreach ( $args['options'] as $key => $label ) {
				$current = ( isset( $options[ $id ][ $key ] ) ) ? $options[ $id ][ $key ] : '';
				printf( '<input type="checkbox" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="1"%4$s /> %5$s<br/>',
					$menu, $id, $key, checked( 1, $current, false ), $label );
			}

			// Displays option description.
			if ( isset( $args['description'] ) ) {
				printf( '<p class="description">%s</p>', $args['description'] );
			}
		}

		/**
		 * Checkbox fields table callback.
		 *
		 * @param  array $args Field arguments.
		 *
		 * @return string      Checkbox field.
		 */
		public function checkbox_table_callback( $args ) {
			$menu = $args['menu'];
			$id   = $args['id'];

			$options = $this->options;

			$rows    = $args['rows'];
			$columns = $args['columns'];

			?>
            <table style="">
                <tr>
                    <td style="padding:0 10px 5px 0;">&nbsp;</td>
					<?php foreach ( $columns as $column => $title ) { ?>
                        <td style="padding:0 10px 5px 0;"><?php echo $title; ?></td>
					<?php } ?>
                </tr>
                <tr>
                    <td style="padding: 0;">
						<?php foreach ( $rows as $row ) {
							echo $row . '<br/>';
						} ?>
                    </td>
					<?php foreach ( $columns as $column => $title ) { ?>
                        <td style="text-align:center; padding: 0;">
							<?php foreach ( $rows as $row => $title ) {
								$current = ( isset( $options[ $id . '_' . $column ][ $row ] ) ) ? $options[ $id . '_' . $column ][ $row ] : '';
								$name    = sprintf( '%1$s[%2$s_%3$s][%4$s]', $menu, $id, $column, $row );
								printf( '<input type="checkbox" id="%1$s" name="%1$s" value="1"%2$s /><br/>', $name,
									checked( 1, $current, false ) );
							} ?>
                        </td>
					<?php } ?>
                </tr>
            </table>

			<?php
			// Displays option description.
			if ( isset( $args['description'] ) ) {
				printf( '<p class="description">%s</p>', $args['description'] );
			}
		}

		/**
		 * Select element callback.
		 *
		 * @param  array $args Field arguments.
		 *
		 * @return string      Select field.
		 */
		public function select_element_callback( $args ) {
			$menu = $args['menu'];
			$id   = $args['id'];

			$options = $this->options;

			if ( isset( $options[ $id ] ) ) {
				$current = $options[ $id ];
			} else {
				$current = isset( $args['default'] ) ? $args['default'] : '';
			}

			printf( '<select id="%1$s" name="%2$s[%1$s]">', $id, $menu );

			foreach ( $args['options'] as $key => $label ) {
				printf( '<option value="%s"%s>%s</option>', $key, selected( $current, $key, false ), $label );
			}

			echo '</select>';


			if ( isset( $args['custom'] ) ) {
				$custom = $args['custom'];

				$custom_id = $id . '_custom';

				printf( '<br/><br/><div id="%s" style="display:none;">', $custom_id );

				switch ( $custom['type'] ) {
					case 'text_element_callback':
						$this->text_element_callback( $custom['args'] );
						break;
					case 'multiple_text_element_callback':
						$this->multiple_text_element_callback( $custom['args'] );
						break;
					case 'multiple_checkbox_element_callback':
						$this->multiple_checkbox_element_callback( $custom['args'] );
						break;
					default:
						break;
				}

				echo '</div>';

				?>
                <script type="text/javascript">
                    jQuery(document).ready(function ($) {
                        function check_<?php echo $id; ?>_custom() {
                            var custom = $('#<?php echo $id; ?>').val();
                            if (custom == 'custom') {
                                $('#<?php echo $custom_id; ?>').show();
                            } else {
                                $('#<?php echo $custom_id; ?>').hide();
                            }
                        }

                        check_<?php echo $id; ?>_custom();

                        $('#<?php echo $id; ?>').change(function () {
                            check_<?php echo $id; ?>_custom();
                        });

                    });
                </script>
				<?php
			}

			// Displays option description.
			if ( isset( $args['description'] ) ) {
				printf( '<p class="description">%s</p>', $args['description'] );
			}

		}

		/**
		 * Displays a radio settings field
		 *
		 * @param array $args settings field args
		 */
		public function radio_element_callback( $args ) {
			$menu = $args['menu'];
			$id   = $args['id'];

			$options = $this->options;
			if ( isset( $options[ $id ] ) ) {
				$current = $options[ $id ];
			} else {
				$current = isset( $args['default'] ) ? $args['default'] : '';
			}

			$html = '';
			foreach ( $args['options'] as $key => $label ) {
				$html .= sprintf( '<input type="radio" class="radio" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s"%4$s />',
					$menu, $id, $key, checked( $current, $key, false ) );
				$html .= sprintf( '<label for="%1$s[%2$s][%3$s]"> %4$s</label><br>', $menu, $id, $key, $label );
			}

			// Displays option description.
			if ( isset( $args['description'] ) ) {
				$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
			}

			echo $html;
		}

		public function radio_element_explained_callback( $args ) {
			$menu = $args['menu'];
			$id   = $args['id'];

			$options = $this->options;

			if ( isset( $options[ $id ] ) ) {
				$current = $options[ $id ];
			} else {
				$current = isset( $args['default'] ) ? $args['default'] : '';
			}

			$html = '';
			if ( isset( $args['description'] ) ) {
				$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
			}

			$html .= "<p>";

			foreach ( $args['options'] as $key => $label ) {
				$html .= sprintf( '<input type="radio" class="radio" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s"%4$s />',
					$menu, $id, $key, checked( $current, $key, false ) );
				$html .= sprintf( '<label for="%1$s[%2$s][%3$s]"> %4$s<br>', $menu, $id, $key, $label[0] );
				$html .= sprintf( '<span class="description">%1$s</span></label><br><br>', $label[1] );
			}

			$html .= "</p>";

			echo $html;
		}

		/**
		 * Section null callback.
		 *
		 * @return void.
		 */
		public function section_options_callback() {
			$this->plugin_basename = plugin_basename( __FILE__ );
			$this->plugin_url      = plugin_dir_url( $this->plugin_basename );
			echo '<div style="background-color:#f6f6f6; border:1px solid green; padding:20px;">';
			echo '<h2 style="color:#4CAF50;">VERSIONE PRO - WooCommerce P.IVA e Codice Fiscale per Italia V. 2</h2>';
			echo '<h4>Con la versione PRO del plugin <b>WooCommerce P.IVA e Codice Fiscale per Italia</b> potrai:</h4><ul><li style="list-style:circle; margin-left:20px !important;"><strong>Scegliere se abilitare o meno il controllo VIES delle partite IVA Comunitarie</strong></li>
<li style="list-style:circle; margin-left:20px !important;"><strong>Il cliente verrà avvisato tramite Notice, nel checkout, della validità o meno della partita IVA inserita </strong></li>
<li style="list-style:circle; margin-left:20px !important;"><strong>Recuperare i campi P.IVA e C.F. al momento della creazione manuale di un ordine in amministrazione</strong></li><li style="list-style:circle; margin-left:20px !important;"><strong>Modificare la label della select relativa alla tipologia di Utente/Documento Fiscale</strong></li>
<li style="list-style:circle; margin-left:20px !important;"><strong>Definire quali voci mostrare al cliente nella tendina dedicata alla tipologia di Utente/Documento Fiscale</strong> desiderato</li><li style="list-style:circle; margin-left:20px !important;">Utilizzare la <strong>validazione live</strong> dei campi <strong>Codice Fiscale e P.IVA nel checkout</strong></li><li style="list-style:circle; margin-left:20px !important;">Utilizzare la <strong>validazione live</strong> dei campi <strong>Codice Fiscale e P.IVA nella pagina dell\'account utente</strong></li><li style="list-style:circle; margin-left:20px !important;">Possibilità di mostrare il <b>generatore di Codice Fiscale</b></li><li style="list-style:circle; margin-left:20px !important;">Personalizzazione della label del Generatore di Codice Fiscale</b></li><li style="list-style:circle; margin-left:20px !important;"><strong>Definire le etichette</strong> della tendina della Tipologia di Documento Fiscale che verr&agrave; mostrata al cliente</li><li style="list-style:circle; margin-left:20px !important;"><strong>Nascondere</strong> automaticamente <strong>il campo Ragione Sociale</strong> nei casi in cui non &egrave; richiesto</li><li style="list-style:circle; margin-left:20px !important;"><strong>Ordinamento avanzato</strong> dei campi del checkout.</li><li style="list-style:circle; margin-left:20px !important;">Possibilità di Generare Fatture/Ricevute in momenti differenti dal Completamento dell\'Ordine (Ottimo per ordini ricevuti con modalità tipo Bonifico)</li><li style="list-style:circle; margin-left:20px !important;"><strong>Aggiunta dettagli relativi all\'orario di Completamento Ordine e Generazione Fattura/Ricevuta in Admin.</li></ul>';
			echo '<a href="http://dot4all.it/prodotto/plugin-woocommerce-p-iva-e-codice-fiscale-per-italia-pro/" title="plugin woocommerce partita iva e codice fiscale per italia PRO" alt="plugin woocommerce partita iva e codice fiscale per italia PRO" style="background-color: #4CAF50; /* Green */border: none;color: white;padding: 15px 32px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;" target="_blank">Acquista ORA</a>';
			echo '</div>';
			if ( !in_array( 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || (function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php' ) )) 		{
				echo('<h4 style="color:#990033;border:1px solid #990033;padding:10px;margin-top:50px;">ATTENZIONE - Il plugin Woocommerce Invoice & Packaging Slips non risulta attivo - Attivarlo per mostrare anche impostazioni avanzate per la generazione di ricevute non presenti altrimenti</h4>');	
			}
		}

		/**
		 * Validate options.
		 *
		 * @param  array $input options to valid.
		 *
		 * @return array        validated options.
		 */
		public function validate_options( $input ) {
			// Create our array for storing the validated options.
			$output = array();

			if ( empty( $input ) || ! is_array( $input ) ) {
				return $input;
			}

			// Loop through each of the incoming options.
			foreach ( $input as $key => $value ) {

				// Check to see if the current option has a value. If so, process it.
				if ( isset( $input[ $key ] ) ) {
					if ( is_array( $input[ $key ] ) ) {
						foreach ( $input[ $key ] as $sub_key => $sub_value ) {
							$output[ $key ][ $sub_key ] = $input[ $key ][ $sub_key ];
						}
					} else {
						$output[ $key ] = $input[ $key ];
					}
				}
			}

			// Return the array processing any additional functions filtered by this action.
			return apply_filters( 'wcpdf_IT_validate_input', $output, $input );
		}

	} // end class

}