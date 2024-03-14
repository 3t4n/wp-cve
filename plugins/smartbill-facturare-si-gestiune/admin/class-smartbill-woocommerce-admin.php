<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.smartbill.ro
 * @since      1.0.0
 * @package    smartbill-facturare-si-gestiune
 * @subpackage Smartbill_Woocommerce/admin
 */

/**
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @copyright  Intelligent IT SRL 2018
 * @author     Intelligent IT SRL <vreauapi@smartbill.ro>
 */
class Smartbill_Woocommerce_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The permissions for the plugin
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $capability    The name of the WordPress capability
	 */
	private $capability = 'manage_options';

	/**
	 * The URL used for the plugin
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $menu_slug    This will appear as the GET page value
	 */
	private $menu_slug = 'smartbill-woocommerce';

	/**
	 * The Auth Screen settings
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Smartbill_Woocommerce_Admin_Auth_Screen $auth_screen    The object used to register admin menus
	 */
	private $auth_screen = null;

	/**
	 * This function returns the Auth Screen object above
	 *
	 * @return Smartbill_Woocommerce_Admin_Auth_Screen $auth_screen
	 */
	public function get_auth_screen() {
		return $this->auth_screen;
	}

	/**
	 * The Settings fields object
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Smartbill_Woocommerce_Admin_Settings_Fields    $settings_fields    The object used to register settings fields
	 */
	private $settings_fields = null;

	/**
	 * This function returns the Settings Fields object above
	 *
	 * @return Smartbill_Woocommerce_Admin_Settings_Fields $settings_fields
	 */
	public function get_settings_fields() {
		return $this->settings_fields;
	}

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name     = $plugin_name;
		$this->version         = $version;
		$this->auth_screen     = new Smartbill_Woocommerce_Admin_Auth_Screen();
		$this->settings_fields = new Smartbill_Woocommerce_Admin_Settings_Fields();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Smartbill_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Smartbill_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( is_admin() ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/smartbill-woocommerce-admin.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'smrt-toastify-css', plugin_dir_url( __FILE__ ) . 'css/toastify.min.css', array(), $this->version, 'all' );
			global $post;
			// load css only on edit/info order pages.
			if ( ( isset( $_GET['page'] ) && ( 'smartbill-woocommerce' == $_GET['page'] || 'smartbill-woocommerce-settings' == $_GET['page'] ) ) || ( isset( $_GET['post_type'] ) ) ) {
				wp_enqueue_style( 'smrt-sweetalert2-css', plugin_dir_url( __FILE__ ) . 'css/sweetalert2.min.css', array(), $this->version, 'all' );
				wp_enqueue_style( 'smrt-select2-css', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
			}elseif( isset( $post ) && isset( $_GET['post_type']) && 'shop_order' == $_GET['post_type'] ){
				wp_enqueue_style( 'smrt-sweetalert2-css', plugin_dir_url( __FILE__ ) . 'css/sweetalert2.min.css', array(), $this->version, 'all' );
				wp_enqueue_style( 'smrt-select2-css', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
			}
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Smartbill_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Smartbill_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$smartbill_settings = Smartbill_Woocommerce_Settings::get_series_settings();
		if ( $smartbill_settings ) {
			// restrictionare incarcare scripturi doar pe paginile de setari si de editare comenzi.
			global $post;
			if ( is_admin() ) {
				if ( isset( $_GET['page'] ) && ('smartbill-woocommerce-settings' == $_GET['page'] || 'wc-orders' == $_GET['page']) ) {
					if(isset($_GET['id'])){
						$smartbill_settings['post_id'] = $_GET['id'];
					}
					$smartbill_settings['security'] = wp_create_nonce( 'smartbill_nonce' );

					$options        = get_option( 'smartbill_plugin_options_settings' );
					$export_options = array();
					if ( ! empty( $options ) && is_array( $options ) ) {
						if ( isset( $options['order_status'] ) && !empty( $options['order_status'] ) ) {
							$smartbill_settings['woocommerce_status'] = $options['order_status'];
						}else{
							$smartbill_settings['woocommerce_status'] = false;
						}
						if ( isset( $options['send_mail_with_document'] ) ) {
							$smartbill_settings['send_mail_with_document'] = $options['send_mail_with_document'];
						}
					}
					wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/smartbill-woocommerce-admin.min.js', array( 'jquery' ), $this->version . time(), false );
					wp_localize_script( $this->plugin_name, 'smartbill', $smartbill_settings );
					if ( 'smartbill-woocommerce-settings' == $_GET['page'] ) {
						if ( get_option( 'smartbill_set_toast' ) ) {
							wp_localize_script( $this->plugin_name, 'hide_toast', array( get_option( 'smartbill_set_toast' ) ) );

						} else {
							wp_localize_script( $this->plugin_name, 'hide_toast', array( get_option( 'smartbill_set_toast' ) ) );
							update_option( 'smartbill_set_toast', true );
						}
						$export_options = $this->export_formated_settings( $options );
						wp_localize_script( $this->plugin_name, 'smartbill_export', $export_options );
					}
				
					if (!wp_script_is('toastify', 'registered')) {
						wp_enqueue_script( 'toastify', plugin_dir_url( __FILE__ ) . ( 'js/toastify.js' ), array( 'jquery' ), $this->version, false );
					}
					if (!wp_script_is('select2', 'registered')) {
						wp_enqueue_script( 'select2', plugin_dir_url( __FILE__ ) . ( 'js/select2.full.min.js' ), array( 'jquery' ), $this->version, false );
					}
					if (!wp_script_is('sweetalert2', 'registered')) {
						wp_enqueue_script( 'sweetalert2', plugin_dir_url( __FILE__ ) . 'js/sweetalert2.all.min.js', array( 'jquery' ), $this->version, false );
					}
				}elseif( isset( $post ) && 'shop_order' == $post->post_type ) {
					$smartbill_settings['post_id'] = $post->ID;
					$smartbill_settings['security'] = wp_create_nonce( 'smartbill_nonce' );

					$options        = get_option( 'smartbill_plugin_options_settings' );
					$export_options = array();
					if ( ! empty( $options ) && is_array( $options ) ) {
						if ( isset( $options['order_status'] ) && !empty( $options['order_status'] ) ) {
							$smartbill_settings['woocommerce_status'] = $options['order_status'];
						}else{
							$smartbill_settings['woocommerce_status'] = false;
						}
						if ( isset( $options['send_mail_with_document'] ) ) {
							$smartbill_settings['send_mail_with_document'] = $options['send_mail_with_document'];
						}
					}
					wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/smartbill-woocommerce-admin.min.js', array( 'jquery' ), $this->version . time(), false );
					wp_localize_script( $this->plugin_name, 'smartbill', $smartbill_settings );
					if (!wp_script_is('toastify', 'registered')) {
						wp_enqueue_script( 'toastify', plugin_dir_url( __FILE__ ) . ( 'js/toastify.js' ), array( 'jquery' ), $this->version, false );
					}
					if (!wp_script_is('select2', 'registered')) {
						wp_enqueue_script( 'select2', plugin_dir_url( __FILE__ ) . ( 'js/select2.full.min.js' ), array( 'jquery' ), $this->version, false );
					}
					if (!wp_script_is('sweetalert2', 'registered')) {
						wp_enqueue_script( 'sweetalert2', plugin_dir_url( __FILE__ ) . 'js/sweetalert2.all.min.js', array( 'jquery' ), $this->version, false );
					}
				}
			}
		}
	}

	/**
	 * Add smartbill section in admin menu
	 *
	 * @param Array $options smartbill settings options.
	 *
	 * @return Array $formated_options
	 */
	private function export_formated_settings( $options ) {
		$formated_options = array();
		if( check_smartbill_compatibility() ){
			$login_options    = get_option( 'smartbill_plugin_options' );
			$vat_rates        = Smartbill_Woocommerce_Settings::get_vat_rates();

			if ( ! empty( $login_options ) && is_array( $login_options ) ) {
				$formated_options['utilizator'] = array(
					'CIF'   => $login_options['vat_code'],
					'email' => $login_options['username'],
				);
			}

			if ( ! empty( $options ) && is_array( $options ) ) {
				if ( ! empty( Smartbill_Woocommerce_Settings::is_vat_payable() ) ) {
					$include_vat                   = isset( $vat_rates[ $options['product_vat'] ] ) ?  $vat_rates[ $options['product_vat'] ]['percentage'] . '% - ' . $vat_rates[ $options['product_vat'] ]['name'] : 'Preluata din WooCommerce';
					$include_ship_vat              = isset( $vat_rates[ $options['shipping_vat'] ] ) ? $vat_rates[ $options['shipping_vat'] ]['percentage'] . '% - ' . $vat_rates[ $options['shipping_vat'] ]['name'] : 'Preluata din WooCommerce';
					$formated_options['setariTVA'] = array(
						'preturile_contin_TVA'     => (bool) $options['included_vat'],
						'cota_TVA_produse'         => $include_vat,
						'transportul_include_TVA'  => (bool) $options['shipping_included_vat'],
						'cota_TVA_transport'       => $include_ship_vat,
						'cupoanele_includ_TVA'     => (bool) 'yes' == get_option( 'woocommerce_calc_taxes' ) ? false : true,
						'adaugare_TVA_la_incasare' => (bool) $options['use_payment_tax'],
					);
				}

				$formated_options['setariEmitere'] = array(
					'cif_utilizat'                            => $this->settings_fields->get_cif() ? 'CIF Intracomunitar' : $login_options['vat_code'],
					'tipul_de_document_emis_in_smartBill'     => (bool) $options['document_type'] ? 'proforma' : 'factura',
					'serie_document'                          => (bool) $options['document_type'] ? $this->settings_fields->get_document_series( 'estimate' ) : $this->settings_fields->get_document_series( 'invoice' ),
					'data_emiterii_documentului'              => '2' == $options['document_date'] ? 'Data crearii comenzii' : 'Data curenta',
					'moneda'                                  => $options['billing_currency'],
					'limba'                                   => ! empty( $options['invoice_lang'] ) ? $options['invoice_lang'] : 'RO',
					'unitatea_de_masura_implicita'            => $options['um'],
					'la_facturare_preia_denumirile_produselor_din_SB' => (bool) $options['smartbill_product'],
					'descarca_gestiunea'                      => $options['stock'],
					'emite_factura_incasata_la_incasare_cu_procesator_plati' => (bool)$options['invoice_cashing'],
					'afiseaza_buton_PlatesteCuCardul_pe_factura' => (bool)$options['payment_url'],
					'emite_ciorna'                            => (bool) $options['invoice_is_draft'],
					'emite_factura_cu_scadenta'               => (bool) $options['issue_with_due_date'],
					'numar_de_zile_pana_la_scadenta'          => $options['due_days'],
					'afiseaza_data_livrarii_pe_document'      => (bool) $options['show_delivery_days'],
					'numar_de_zile_pana_la_livrare'           => $options['delivery_days'],
					'afiseaza_costul_transportului_pe_document' => (bool) $options['include_shipping'],
					'afisare_transport_0_pe_factura'		  => (bool) $options['free_shipping'],
					'denumirea_transportului_pe_document'     => $this->settings_fields->get_shipping_name(),
					'afiseaza_pe_factura_reducerea_de_pret_pe_produs' => (bool) $options['show_discount_on_document'],
					'denumirea_reducerii_de_pret_pe_document' => $this->settings_fields->get_discount_text(),
					'denumirea_cuponului_pe_document'         => $this->settings_fields->get_coupon_text(),
				);

				$order_statuses                  = wc_get_order_statuses();
				$formated_options['setariModul'] = array(
					'statusul_comenzii_pentru_facturare_automata' => $this->settings_fields->get_order_status(),
					'netopia_emite_factura_ca_incasata' 	=> (bool)$options['invoice_cashing'],
					'trimite_automat_documentul_clientului' => (bool) $options['send_mail_with_document'],
					'cc'                                    => $options['send_mail_cc'],
					'Bcc'                                   => $options['send_mail_bcc'],
					'afiseaza_check-out_persoana_fizica/juridica' => (bool) $options['custom_checkout'],
					'afiseaza_factura_in_contul_clientului' => (bool) $options['public_invoice'],
					'denumire_buton_factura'                => $this->settings_fields->get_view_invoice_text(),
				);

				$formated_options['salvari'] = array(
					'salveaza_clientul_in_SB' => (bool) $options['save_client'],
					'salveaza_produsul_in_SB' => (bool) $options['save_product'],
				);
				$date = get_option('smartbill_stock_update');
				if(!empty($date)){
					$date = unserialize($date);
					$date = $date->format('d/m/Y H:i');
				}
				$formated_options['setariSincronizareStocuri'] = array(
					'urlSincronizareStocuri' 		   => site_url() . '/wp-json/smartbill_woocommerce/v1/stocks',
					'actualizeaza_stocurile' 		   => (bool) $options['sync_stock'],
					'gestiuneUtilizata'     	       => $options['used_stock'],
					'afiseaza_istoric'       	       => (bool) $this->settings_fields->get_save_stock_history(),
					'data_ultimei_actualizari_stocuri' => $date,
				);
				$formated_options['setariWoocommerce']         = array(
					'moneda' 				=> get_woocommerce_currency(),
					'clasa_TVA_transport' 	=> get_option( 'woocommerce_shipping_tax_class' ),
				);
				$formated_options['versiuni']                  = array(
					'versiune_PHP'         => phpversion(),
					'versiune_WordPress'   => $GLOBALS['wp_version'],
					'versiune_WooCommerce' => WC_VERSION,
					'versiune_SmartBill'   => SMARTBILL_PLUGIN_VERSION,
					'data_export'          => '',
				);
			}
		}
		return $formated_options;
	}

	/**
	 * Add smartbill section in admin menu
	 *
	 * @return void
	 */
	public function add_menu_pages() {
		global $submenu;
		$menu_title          = __( 'SmartBill', 'smartbill-woocommerce' );
		$page_title          = $menu_title;
		$menu_title_settings = __( 'Setari', 'smartbill-woocommerce' );
		$sb_auth_screen      = $this->get_auth_screen();
		$main_function_auth  = array( $sb_auth_screen, 'show_auth_window' );

		$sb_settings_screen     = $this->get_settings_fields();
		$main_function_settings = array( $sb_settings_screen, 'show_settings_window' );
		$icon_url               = 'dashicons-format-aside';
		$position               = 98;
		add_menu_page( $page_title, $menu_title, $this->capability, $this->menu_slug, $main_function_auth, plugin_dir_url( __FILE__ ) . '../assets/images/logo_gray.png', $position );
		add_submenu_page( $this->menu_slug, $page_title, $menu_title_settings, $this->capability, $this->menu_slug . '-settings', $main_function_settings );
		// overwrite first submenu name to be Authentication.
		$submenu['smartbill-woocommerce'][0][0] = __( 'Autentificare', 'smartbill-woocommerce' );
	}

	/**
	 * Display billing fields on order info page
	 *
	 * @param Order $order woocommerce order.
	 *
	 * @return void
	 */
	public function smartbill_add_billing_fileds_in_admin_order( $order ) {
		$admin_settings = new Smartbill_Woocommerce_Admin_Settings_Fields();
		if ( $admin_settings->get_custom_checkout() ) {
			$id_cif        = 'smartbill_billing_cif';
			$id_nr_reg_com = 'smartbill_billing_nr_reg_com';
			$cif           = get_post_meta( $order->get_id(), $id_cif, true );
			$nr_reg_com    = get_post_meta( $order->get_id(), $id_nr_reg_com, true );
			$shipping_type = get_post_meta( $order->get_id(), 'smartbill_billing_type', true );

			?>
			<div class="edit_address smartbill-order-billing">
			<?php
			woocommerce_wp_select(
				array(
					'id'            => 'smartbill_billing_type',
					'label'         => 'Tip facturare:',
					'value'         => $shipping_type,
					'options'       => array(
						'pf' => __( 'Persoana fizica', 'smartbill_woocommerce' ),
						'pj' => __( 'Persoana juridica', 'smartbill_woocommerce' ),
					),
					'wrapper_class' => 'form-field-wide',
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'    => $id_cif,
					'name'  => $id_cif,
					'label' => __( 'CIF', 'smartbill_woocommerce' ),
					'value' => $cif,
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'    => $id_nr_reg_com,
					'name'  => $id_nr_reg_com,
					'label' => __( 'Nr. Reg. Com.', 'smartbill_woocommerce' ),
					'value' => $nr_reg_com,
				)
			);
			?>
			</div>
			<?php
		}
	}

	/**
	 * Display shipping fields on order info page
	 *
	 * @param Order $order woocommerce order.
	 *
	 * @return void
	 */
	public function smartbill_add_shipping_fileds_in_admin_order( $order ) {
		$admin_settings = new Smartbill_Woocommerce_Admin_Settings_Fields();
		if ( $admin_settings->get_custom_checkout() ) {
			$id_cif        = 'smartbill_shipping_cif';
			$id_nr_reg_com = 'smartbill_shipping_nr_reg_com';
			$cif           = get_post_meta( $order->get_id(), $id_cif, true );
			$nr_reg_com    = get_post_meta( $order->get_id(), $id_nr_reg_com, true );
			$shipping_type = get_post_meta( $order->get_id(), 'smartbill_shipping_type', true );

			?>
			<div class="edit_address smartbill-order-billing">
			<?php
			woocommerce_wp_select(
				array(
					'id'            => 'smartbill_shipping_type',
					'label'         => 'Tip facturare:',
					'value'         => $shipping_type,
					'options'       => array(
						'pf' => __( 'Persoana fizica', 'smartbill_woocommerce' ),
						'pj' => __( 'Persoana juridica', 'smartbill_woocommerce' ),
					),
					'wrapper_class' => 'form-field-wide',
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'    => $id_cif,
					'name'  => $id_cif,
					'label' => __( 'CIF', 'smartbill_woocommerce' ),
					'value' => $cif,
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'    => $id_nr_reg_com,
					'name'  => $id_nr_reg_com,
					'label' => __( 'Nr. Reg. Com.', 'smartbill_woocommerce' ),
					'value' => $nr_reg_com,
				)
			);
			?>
			</div>
			<?php
		}
	}

	/**
	 * Save custom billing/shipping fields values as order meta
	 *
	 * @param int $order_id order id.
	 *
	 * @return void
	 */
	public function smartbill_billing_order_save_fields( $order_id ) {
		$order = wc_get_order( $order_id );
		$billing_type = get_post_meta( $order_id, 'smartbill_billing_type' );
		if ( isset( $_POST['smartbill_billing_type'] ) ) {
			update_post_meta( $order_id, 'smartbill_billing_type', trim( sanitize_text_field( wp_unslash( $_POST['smartbill_billing_type'] ) ) ));
			$order->update_meta_data( 'smartbill_billing_type',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_billing_type'] ) ) ) );
		}if ( isset( $_POST['smartbill_billing_cif'] ) ) {
			update_post_meta( $order_id, 'smartbill_billing_cif', trim( sanitize_text_field( wp_unslash( $_POST['smartbill_billing_cif'] ) ) ) );
			$order->update_meta_data( 'smartbill_billing_cif',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_billing_cif'] ) ) ) );
		}if ( isset( $_POST['smartbill_billing_nr_reg_com'] ) ) {
			update_post_meta( $order_id, 'smartbill_billing_nr_reg_com', trim( sanitize_text_field( wp_unslash( $_POST['smartbill_billing_nr_reg_com'] ) ) ) );
			$order->update_meta_data( 'smartbill_billing_nr_reg_com',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_billing_nr_reg_com'] ) ) ) );
		}if ( 'pj' == $billing_type ) {
			if ( isset( $_POST['_billing_company'] ) ) {
				update_post_meta( $order_id, 'smartbill_billing_company_name',trim( sanitize_text_field( wp_unslash( $_POST['_billing_company'] ) ) ) );
				$order->update_meta_data( 'smartbill_billing_company_name',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_billing_company_name'] ) ) ) );
			}
		}

		$billing_type = get_post_meta( $order_id, 'smartbill_shipping_type' );
		if ( isset( $_POST['smartbill_shipping_type'] ) ) {
			update_post_meta( $order_id, 'smartbill_shipping_type', trim( sanitize_text_field( wp_unslash( $_POST['smartbill_shipping_type'] ) ) ) );
			$order->update_meta_data( 'smartbill_shipping_type',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_shipping_type'] ) ) ) );
		}
		if ( isset( $_POST['smartbill_shipping_cif'] ) ) {
			update_post_meta( $order_id, 'smartbill_shipping_cif', trim( sanitize_text_field( wp_unslash( $_POST['smartbill_shipping_cif'] ) ) ) );
			$order->update_meta_data( 'smartbill_shipping_cif',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_shipping_cif'] ) ) ) );
		}
		if ( isset( $_POST['smartbill_shipping_nr_reg_com'] ) ) {
			update_post_meta( $order_id, 'smartbill_shipping_nr_reg_com',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_shipping_nr_reg_com'] ) ) ) );
			$order->update_meta_data( 'smartbill_shipping_nr_reg_com',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_shipping_nr_reg_com'] ) ) ) );
		}
		if ( 'pj' == $billing_type ) {
			if ( isset( $_POST['_billing_company'] ) ) {
				update_post_meta( $order_id, 'smartbill_shipping_company_name',trim( sanitize_text_field( wp_unslash( $_POST['_billing_company'] ) ) ) );
				$order->update_meta_data( 'smartbill_shipping_company_name',trim( sanitize_text_field( wp_unslash( $_POST['smartbill_shipping_company_name'] ) ) ) );
			}
		}
		$order->save();
	}


	/**
	 * Dispaly invoice in Woocommerce My Account Order View.
	 *
	 * @param int $order_id order id.
	 *
	 * @return void
	 */
	public function show_public_invoice( $order_id ) {
		$admin_settings = new Smartbill_Woocommerce_Admin_Settings_Fields();

		if ( $admin_settings->get_public_invoice() ) {
			$wtclass = get_post_meta( $order_id, 'smartbill_invoice_log' );

			if ( $wtclass && isset( $wtclass[0] ) && isset( $wtclass[0]['smartbill_view_document_url'] ) ) {
				echo '<section class="woocommerce-customer-details"> <h2 class="woocommerce-column__title">';
				echo esc_attr( __( 'Factura', 'smartbill_woocommerce' ) ) . '</h2> <a target="_blank" href="' . esc_url( $wtclass[0]['smartbill_view_document_url'] ) . '" class="woocommerce-button button view">';
				echo esc_attr( $admin_settings->get_view_invoice_text() ) . '</a></section>';
			}
		}
	}


	/**
	 * Add settings button in plugins page.
	 *
	 * @param Array  $actions array with actions.
	 * @param string $plugin_file plugin path.
	 *
	 * @return Array $actions
	 */
	public function add_plugin_settings_link( $actions, $plugin_file ) {
		$setting_link = array();
		if ( basename( plugin_dir_path( plugin_dir_path( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'smartbill-woocommerce.php' == $plugin_file ) {
			$setting_link['smartbill-woocommerce-settings'] = sprintf( '<a href="%s"> ' . __( 'Setari', 'smartbill-woocommerce-settings' ) . '</a>', esc_url( admin_url( 'admin.php?page=smartbill-woocommerce-settings' ) ) );
			$actions                                        = array_merge( $setting_link, $actions );
		}

		return $actions;
	}

	/**
	 * Add meta to order item
	 * Save product as a service on order item metadata.
	 * Save reg price when only sales price is available on order item metadata.
	 * Save reg price including tax when only sales price is available on order item metadata.
	 *
	 * @param int                                          $item_id order item id.
	 * @param WC_Order_Item_Product|WC_Order_Item_Shipping $product order product.
	 * @param int                                          $order_id order id.
	 *
	 * @return void
	 */
	public function smartbill_add_order_custom_item_meta( $item_id, $item, $order_id ) {
		if ( $item instanceof WC_Order_Item_Product ) {
			if ( ! empty( $item->get_variation_id() ) ) {
				$product=wc_get_product($item->get_variation_id());
				if ($product->is_virtual()) {
					wc_update_order_item_meta( $item_id, '_smartbill_service', 'yes' );
				}
				if ( ! empty( $product )) {
					$price = $product->get_regular_price();
				
					wc_update_order_item_meta( $item_id, '_smartbill_prod_reg_price',$price);
					wc_update_order_item_meta( $item_id, '_smartbill_prod_reg_price_including_tax', wc_get_price_including_tax( $product , array('price' => $price )));
					wc_update_order_item_meta( $item_id, '_smartbill_prod_reg_price_excluding_tax', wc_get_price_excluding_tax( $product , array('price' => $price )));
				}
			} else {
				$product = wc_get_product( $item->get_product_id() );

				if ( ! empty( $product )) {
					$price = $product->get_regular_price();
					wc_update_order_item_meta( $item_id, '_smartbill_prod_reg_price',$price);
					wc_update_order_item_meta( $item_id, '_smartbill_prod_reg_price_including_tax', wc_get_price_including_tax( $product , array('price' => $price )));
					wc_update_order_item_meta( $item_id, '_smartbill_prod_reg_price_excluding_tax', wc_get_price_excluding_tax( $product , array('price' => $price )));
					if($product->is_virtual() ){
						wc_update_order_item_meta( $item_id, '_smartbill_service', 'yes' );
					}
				}
			}
		}
	}

	/**
	 * Hide admin order product item meta: service (is virtual?) reg price, reg price including tax.
	 *
	 * @param Array $arr array with hidden item meta.
	 *
	 * @return Array
	 */
	public function smartbill_hide_custom_item_meta( $arr ) {
		$arr[] = '_smartbill_service';
		$arr[] = '_smartbill_prod_reg_price';
		$arr[] = '_smartbill_prod_reg_price_including_tax';
		$arr[] = '_smartbill_prod_reg_price_excluding_tax';
		return $arr;
	}

	// phpcs: ignore!
}
