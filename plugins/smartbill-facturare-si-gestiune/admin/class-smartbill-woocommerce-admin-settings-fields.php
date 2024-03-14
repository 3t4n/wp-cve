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
 * SmartBill Settings.
 *
 * @copyright  Intelligent IT SRL 2018
 * @author     Intelligent IT SRL <vreauapi@smartbill.ro>
 */
class Smartbill_Woocommerce_Admin_Settings_Fields {
	/**
	 * Register and display SmartBill Settings from fields.
	 *
	 * @since    1.0.0
	 */
	public function register_fields() {
		if ( ! empty( $_GET['page'] ) && in_array( $_GET['page'], array( 'smartbill-woocommerce-settings', 'smartbill-woocommerce' ) ) ) {
			$settings['isTaxPayer'] = Smartbill_Woocommerce_Settings::is_vat_payable();
		}

		// valid auth?
		$options = get_option( 'smartbill_plugin_options' );
		if ( ! empty( $options ) && is_array( $options ) && ! empty( $options['token'] ) ) {
			$token = $options['token'];
		} else {
			$token = '';
		}

		// for settings.
		register_setting(
			'smartbill_plugin_options_settings',
			'smartbill_plugin_options_settings',
			array( 'sanitize_callback' => array( $this, 'smartbill_plugin_options_settings_validate' ) )
		);
		add_settings_section(
			'smartbill_plugin_settings',
			'',
			array( $this, 'smartbill_plugin_settings_section_text' ),
			'smartbill_plugin_settings'
		);
		if ( ! empty( $token ) ) {

			if ( ! empty( $settings['isTaxPayer'] ) ) {
				add_settings_section(
					'smartbill_plugin_settings_vat',
					__( 'Setari TVA', 'smartbill-woocommerce' ),
					array( $this, 'smartbill_plugin_settings_vat_section_text' ),
					'smartbill_plugin_settings'
				);
			}
			if ( ! empty( $settings ) ) {
				add_settings_section(
					'smartbill_plugin_settings_documents',
					__( 'Setari emitere documente', 'smartbill-woocommerce' ),
					array( $this, 'smartbill_plugin_settings_documents_section_text' ),
					'smartbill_plugin_settings'
				);
			}

			if ( ! empty( $settings['isTaxPayer'] ) ) {
				add_settings_field(
					'smartbill_plugin_options_settings_included_vat',
					__( 'Preturile includ TVA', 'smartbill-woocommerce' ),
					array( $this, 'display_included_vat' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_vat'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_product_vat',
					__( 'Cota TVA produse', 'smartbill-woocommerce' ),
					array( $this, 'display_product_vat' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_vat'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_shipping_included_vat',
					__( 'Transportul include TVA', 'smartbill-woocommerce' ),
					array( $this, 'display_shipping_included_vat' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_vat'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_shipping_vat',
					__( 'Cota TVA transport', 'smartbill-woocommerce' ),
					array( $this, 'display_shipping_vat' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_vat'
				);

				add_settings_field(
					'smartbill_plugin_options_settings_use_payment_tax',
					__( 'Adaugare TVA la incasare', 'smartbill-woocommerce' ),
					array( $this, 'display_use_payment_tax' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_vat'
				);
			}

			if ( ! empty( $settings ) ) {
				add_settings_field(
					'smartbill_plugin_options_settings_cif',
					__( 'CIF utilizat', 'smartbill-woocommerce' ),
					array( $this, 'display_cif' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_document_type',
					__( 'Tipul de document emis in SmartBill', 'smartbill-woocommerce' ),
					array( $this, 'display_document_type' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_document_series',
					__( 'Serie implicita document', 'smartbill-woocommerce' ),
					array( $this, 'display_document_series' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_order_status',
					__( 'Factureaza automat comanda cand statusul comenzii devine', 'smartbill-woocommerce' ),
					array( $this, 'display_order_status' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_document_date',
					__( 'Data emiterii documentului', 'smartbill-woocommerce' ),
					array( $this, 'display_document_date' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_billing_currency',
					__( 'Moneda documentului emis in SmartBill', 'smartbill-woocommerce' ),
					array( $this, 'display_billing_currency' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_invoice_lang',
					__( 'Limba documentului emis in SmartBill', 'smartbill-woocommerce' ),
					array( $this, 'display_language' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);

				add_settings_field(
					'smartbill_plugin_options_settings_gestiune',
					__( 'Descarca gestiunea', 'smartbill-woocommerce' ),
					array( $this, 'display_stock' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_invoice_cashing',
					__( 'Comanda platita va fi facturata ca incasata', 'smartbill-woocommerce' ),
					array( $this, 'display_invoice_cashing' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents',
					array( 'class' => 'smartbill-show-if-type-invoice' )
				);
				add_settings_field(
					'smartbill_plugin_options_settings_payment_url',
					__( 'Afiseaza buton "Plateste cu cardul" pe factura', 'smartbill-woocommerce' ),
					array( $this, 'display_payment_url' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents',
					array( 'label_for' => 'smartbill-display-payment-url' )
				);
				add_settings_field(
					'smartbill_plugin_options_settings_invoice_is_draft',
					__( 'Emite ciorna', 'smartbill-woocommerce' ),
					array( $this, 'display_invoice_is_draft' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_issue_with_due_date',
					__( 'Emite factura cu scadenta', 'smartbill-woocommerce' ),
					array( $this, 'display_issue_with_due_date' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);

				add_settings_field(
					'smartbill_plugin_options_settings_due_days',
					__( 'Numar de zile pana la scadenta', 'smartbill-woocommerce' ),
					array( $this, 'display_due_days' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents',
					array( 'class' => 'smartbill-show-if-due-days-on' )
				);

				add_settings_field(
					'smartbill_plugin_options_settings_show_delivery_days',
					__( 'Afiseaza data livrarii pe document', 'smartbill-woocommerce' ),
					array( $this, 'display_show_delivery_days' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);

				add_settings_field(
					'smartbill_plugin_options_settings_delivery_days',
					__( 'Numar de zile pana la data livrarii', 'smartbill-woocommerce' ),
					array( $this, 'display_delivery_days' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents',
					array( 'class' => 'smartbill-show-if-delivery-days-on' )
				);

				add_settings_field(
					'smartbill_plugin_options_settings_include_shipping',
					__( 'Afiseaza costul transportului pe document', 'smartbill-woocommerce' ),
					array( $this, 'display_include_shipping' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);

				add_settings_field(
					'smartbill_plugin_options_settings_shipping_name',
					__( 'Numele afisat pe factura', 'smartbill-woocommerce' ),
					array( $this, 'display_shipping_name' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents',
					array( 'class' => 'smartbill-show-if-shipping-cost-is-included' )
				);

				add_settings_field(
					'smartbill_plugin_options_settings_free_shipping',
					__( 'Cand pe comanda costul transportului e 0', 'smartbill-woocommerce' ),
					array( $this, 'display_free_shipping' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents',
					array( 'class' => 'smartbill-show-if-shipping-cost-is-included' )
				);

				add_settings_field(
					'smartbill_plugin_options_settings_show_discount_on_document',
					__( 'Afiseaza pe factura reducerea de pret pe produs', 'smartbill-woocommerce' ),
					array( $this, 'display_show_discount_on_document' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents',
					array()
				);
				add_settings_field(
					'smartbill_plugin_options_settings_discount_text',
					__( 'Numele reducerii de pret afisate pe factura', 'smartbill-woocommerce' ),
					array( $this, 'display_discount_text' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents',
					array( 'class' => 'discount_text' )
				);

				add_settings_field(
					'smartbill_plugin_options_settings_coupon_text',
					__( 'Numele cuponului afisat pe factura', 'smartbill-woocommerce' ),
					array( $this, 'display_coupon_text' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);

				add_settings_field(
					'smartbill_plugin_options_settings_add_delegate_data',
					__( 'Adauga datele intocmitorului/delegatului pe factura', 'smartbill-woocommerce' ),
					array( $this, 'display_add_delegate_data' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);

				add_settings_field(
					'smartbill_plugin_options_settings_issuer_name',
					__( 'Nume intocmitor', 'smartbill-woocommerce' ),
					array( $this, 'display_issuer_name' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents',
					array( 'class' => 'smartbill-show-if-add-delegate-data-on' )
				);

				add_settings_field(
					'smartbill_plugin_options_settings_issuer_cnp',
					__( 'CNP intocmitor', 'smartbill-woocommerce' ),
					array( $this, 'display_issuer_cnp' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents',
					array( 'class' => 'smartbill-show-if-add-delegate-data-on' )
				);

				add_settings_field(
					'smartbill_plugin_options_settings_delegate_name',
					__( 'Nume delegat', 'smartbill-woocommerce' ),
					array( $this, 'display_delegate_name' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents',
					array( 'class' => 'smartbill-show-if-add-delegate-data-on' )
				);

				add_settings_field(
					'smartbill_plugin_options_settings_delegate_bulletin',
					__( 'Buletin', 'smartbill-woocommerce' ),
					array( $this, 'display_delegate_bulletin' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents',
					array( 'class' => 'smartbill-show-if-add-delegate-data-on' )
				);

				add_settings_field(
					'smartbill_plugin_options_settings_delegate_auto',
					__( 'Auto', 'smartbill-woocommerce' ),
					array( $this, 'display_delegate_auto' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents',
					array( 'class' => 'smartbill-show-if-add-delegate-data-on' )
				);

				add_settings_field(
					'smartbill_plugin_options_settings_show_order_mention',
					__( 'Mentiuni factura', 'smartbill-woocommerce' ),
					array( $this, 'display_show_order_mention' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_show_order_obs',
					__( 'Observatii factura', 'smartbill-woocommerce' ),
					array( $this, 'display_show_order_obs' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);

				add_settings_field(
					'smartbill_plugin_options_settings_save_client',
					__( 'Salveaza clientul in SmartBill', 'smartbill-woocommerce' ),
					array( $this, 'display_save_client' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);

				add_settings_field(
					'smartbill_plugin_options_settings_custom_checkout',
					__( 'Ofera posibilitatea facturarii pe persoana juridica la checkout', 'smartbill-woocommerce' ),
					array( $this, 'display_custom_checkout' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_custom_checkout_options',
					'',
					array( $this, 'display_custom_checkout_options' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_public_invoice',
					__( 'Afiseaza factura in contul clientului', 'smartbill-woocommerce' ),
					array( $this, 'display_public_invoice' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_view_invoice_text',
					__( 'Denumire buton care redirectioneaza spre factura', 'smartbill-woocommerce' ),
					array( $this, 'display_view_invoice_text' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_documents',
					array( 'class' => 'public-invoice-name' )
				);
			}
			if ( ! empty( $settings ) ) {
				add_settings_section(
					'smartbill_plugin_settings_email',
					__( 'Setari trimitere documente', 'smartbill-woocommerce' ),
					array( $this, 'smartbill_plugin_settings_documents_section_text' ),
					'smartbill_plugin_settings'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_send_mail_with_document',
					__( 'Trimite automat documentul clientului', 'smartbill-woocommerce' ),
					array( $this, 'display_send_mail_with_document' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_email'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_send_mail_cc',
					__( 'Cc', 'smartbill-woocommerce' ),
					array( $this, 'display_send_mail_cc' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_email',
					array( 'class' => 'smartbill-show-if-send-mail' )
				);
				add_settings_field(
					'smartbill_plugin_options_settings_send_mail_bcc',
					__( 'Bcc', 'smartbill-woocommerce' ),
					array( $this, 'display_send_mail_bcc' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_email',
					array( 'class' => 'smartbill-show-if-send-mail' )
				);
				add_settings_field(
					'smartbill_plugin_options_settings_send_mail_text',
					'',
					array( $this, 'display_send_mail_text' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_email',
					array( 'class' => 'smartbill-show-if-send-mail' )
				);
			}
			if ( ! empty( $settings ) ) {
				add_settings_section(
					'smartbill_plugin_settings_products',
					__( 'Setari produse', 'smartbill-woocommerce' ),
					array( $this, 'smartbill_plugin_settings_documents_section_text' ),
					'smartbill_plugin_settings'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_save_product',
					__( 'Salveaza produsul in SmartBill', 'smartbill-woocommerce' ),
					array( $this, 'display_save_product' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_products'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_um',
					'<p class="settings-font-size">' . __( 'Unitatea de masura implicita', 'smartbill-woocommerce' ) . '</p>',
					array( $this, 'display_um' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_products'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_smartbill_product',
					__( 'La facturare, preia denumirile produselor din SmartBill', 'smartbill-woocommerce' ),
					array( $this, 'display_smartbill_product' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_settings_products'
				);

			}

			if ( ! empty( $settings ) ) {
				add_settings_section(
					'smartbill_plugin_stocks',
					__( 'Sincronizare stocuri', 'smartbill-woocommerce' ),
					array( $this, 'smartbill_plugin_settings_documents_section_text' ),
					'smartbill_plugin_settings'
				);
				add_settings_field(
					'smartbill_plugin_options_settings_sincronizare',
					__( 'Actualizeaza stocurile din magazinul online', 'smartbill-woocommerce' ),
					array( $this, 'display_sync_stock' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_stocks',
					array( 'class' => 'smartbill-show-if-company-sync-stock' )
				);
				add_settings_field(
					'smartbill_plugin_options_settings_gestiune_utilizata',
					__( 'Gestiune utilizata', 'smartbill-woocommerce' ),
					array( $this, 'display_used_stock' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_stocks',
					array( 'class' => 'smartbill-show-sync-stock-both-ways' )
				);

				add_settings_field(
					'smartbill_plugin_options_settings_save_stock_history',
					__( 'Activeaza istoric sincronizare automata', 'smartbill-woocommerce' ),
					array( $this, 'display_save_stock_history' ),
					'smartbill_plugin_settings',
					'smartbill_plugin_stocks',
					array( 'class' => 'smartbill-show-sync-stock-both-ways' )
				);

			}
		}
	}
	/**
	 * This function will show the settings
	 *
	 * @return void
	 */
	public function show_settings_window() {
		// check version compatbility.
		if ( ! check_smartbill_compatibility() ) {
			show_smartbill_version_err();
		}
		// verify if user is logged into SmartBill Cloud.
		$options = get_option( 'smartbill_plugin_options' );
		if ( ! empty( $options ) && is_array( $options ) && ! empty( $options['token'] ) ) {
			$token    = $options['token'];
			$username = $options['username'];
		} else {
			$token = '';
		}
		?>
	<div class="wrap">
		<h2 class="smartbill-flex">
			<?php
			echo esc_attr__( 'Setari SmartBill v. ', 'smartbill-woocommerce' );
			echo esc_attr( SMARTBILL_PLUGIN_VERSION );
			?>
				<?php if ( ! empty( $token ) ) : ?>
				<input id="get-smartbill-setting" type="button" class="button button-secondary" value="<?php echo esc_attr__( 'Preia configurari din SmartBill', 'smartbill-woocommerce' ); ?>" />
				<?php endif; ?>
				<?php if ( ! empty( $token ) ) : ?>
				<input id="export-settings" type="button" class="button button-secondary" value="<?php echo esc_attr__( 'Exporta setari', 'smartbill-woocommerce' ); ?>" />
				<?php endif; ?>
		</h2>
		<?php settings_errors(); ?>
		<form action="<?php echo esc_url_raw( admin_url( 'options.php' ) ); ?>" method="post">
			<?php settings_fields( 'smartbill_plugin_options_settings' ); ?>
			<?php do_settings_sections( 'smartbill_plugin_settings' ); ?>
			<?php if ( ! empty( $token ) ) : ?>
			<input name="Submit" type="submit" class="button button-primary" value="<?php echo esc_attr__( 'Salveaza modificarile', 'smartbill-woocommerce' ); ?>" />
			<?php endif; ?>
		</form>
	</div>
		<?php
	}


	/**
	 * Redirect to auth page if SmartBill auth is not valid.
	 *
	 * @return void
	 */
	public function smartbill_plugin_settings_section_text() {
		$options = get_option( 'smartbill_plugin_options' );
		if ( ! empty( $options ) && is_array( $options ) && ! empty( $options['token'] ) ) {
			$token = $options['token'];
		} else {
			$token = '';
		}

		if ( empty( $token ) ) {
			wp_safe_redirect( admin_url( 'admin.php' ) . '?page=smartbill-woocommerce' );
		}
	}

	/**
	 * Display nothing, only the heading.
	 *
	 * @return void
	 */
	public function smartbill_plugin_settings_vat_section_text() {
	}

	/**
	 * Display nothing, only the heading.
	 *
	 * @return void
	 */
	public function smartbill_plugin_settings_documents_section_text() {
	}

	/**
	 * Get Included VAT value.
	 *
	 * @return boolean
	 */
	public function get_included_vat() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['included_vat'] ) ) {
			$included_vat = $options['included_vat'];
		} else {
			$included_vat = 1;
		}
		return $included_vat;
	}

	/**
	 * Display include VAT settings.
	 *
	 * @return void
	 */
	public function display_included_vat() {
		$included_vat = $this->get_included_vat();

		echo '
    <select class="smartbill-settings-size" name="smartbill_plugin_options_settings[included_vat]">
        <option value="1" ' . ( ! empty( $included_vat ) ? 'selected' : '' ) . '>' . esc_attr__( 'Da', 'smartbill-woocommerce' ) . '</option>
        <option value="0" ' . ( empty( $included_vat ) ? 'selected' : '' ) . '>' . esc_attr__( 'Nu', 'smartbill-woocommerce' ) . '</option>
    </select>';
		echo '<p class="description">' . esc_attr__( 'Daca vrei ca preturile sa fie transmise din WooCommerce catre SmartBill cu TVA inclus', 'smartbill-woocommerce' ) . '</p>';
	}

	/**
	 * Get Include Shipping VAT settings value.
	 *
	 * @return boolean
	 */
	public function get_shipping_included_vat() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['shipping_included_vat'] ) ) {
			$shipping_included_vat = $options['shipping_included_vat'];
		} else {
			$shipping_included_vat = 0;
			if ( ! isset( $options['shipping_included_vat'] ) ) {
				$shipping_included_vat = 'yes'== get_option('woocommerce_calc_taxes') ? 0 : 1;
			}
		}
		return $shipping_included_vat;

	}

	/**
	 * Display Include Shipping VAT settings.
	 *
	 * @return void
	 */
	public function display_shipping_included_vat() {
		$shipping_included_vat = $this->get_shipping_included_vat();
		echo '
    <select class="smartbill-settings-size" name="smartbill_plugin_options_settings[shipping_included_vat]">
        <option value="1" ' . ( ! empty( $shipping_included_vat ) ? 'selected' : '' ) . '>' . esc_attr__( 'Da', 'smartbill-woocommerce' ) . '</option>
        <option value="0" ' . ( empty( $shipping_included_vat ) ? 'selected' : '' ) . '>' . esc_attr__( 'Nu', 'smartbill-woocommerce' ) . '</option>
    </select>';
		echo '<p class="description">' . esc_attr__( 'Daca vrei ca transportul sa fie transmis din WooCommerce catre SmartBill cu TVA inclus', 'smartbill-woocommerce' ) . '</p>';
	}

	/**
	 * Get product VAT setting value.
	 *
	 * @return string|false
	 */
	public function get_product_vat() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['product_vat'] ) ) {
			$product_vat = $options['product_vat'];
		} else {
			$product_vat = 0;
		}

		return $product_vat;

	}

	/**
	 * Display product VAT setting.
	 *
	 * @return void
	 */
	public function display_product_vat() {

		$product_vat = $this->get_product_vat();
		$vat_rates   = Smartbill_Woocommerce_Settings::get_vat_rates();
		echo '<select class="smartbill-settings-size" name="smartbill_plugin_options_settings[product_vat]">';
		$counter  = 0;
		$selected = '';
		if ( is_array( $vat_rates ) ) {
			foreach ( $vat_rates as $key => $vat ) {
				$counter++;
				if ( ! is_numeric( $product_vat ) ) {
					$selected = '';
				} elseif ( $product_vat == $key ) {
					$selected = ' selected ';
				} else {
					$selected = '';
				}
				echo '<option value="' . esc_attr( $key ) . '" ' . esc_attr( $selected ) . '>' . esc_attr( $vat['percentage'] ) . '% - ' . esc_attr( $vat['name'] ) . '</option>';
			}
		}
		// Last added value will always be the one from Woocommerce.
		if ( ! is_numeric( $product_vat ) ) {
			$selected = ' selected ';
		}
		echo '<option value="' . esc_attr( Smartbill_Woocommerce_Settings::SMARTBILL_VAT_VALUE_FOR_PLATFORM ) . '" ' . esc_attr( $selected ) . '>' . esc_attr__( 'Preluata din WooCommerce', 'smartbill-woocommerce' ) . '</option>';
		echo '</select>';
		echo '<p class="description">' . esc_attr__( 'Cota TVA aplicata produselor facturate in SmartBill', 'smartbill-woocommerce' ) . '</p>';
	}

	/**
	 * Get shipping VAT setting.
	 *
	 * @return string
	 */
	public function get_shipping_vat() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['shipping_vat'] ) ) {
			$shipping_vat = $options['shipping_vat'];
		} else {
			$shipping_vat = '';
		}
		return $shipping_vat;

	}

	/**
	 * Display shipping VAT setting.
	 *
	 * @return void
	 */
	public function display_shipping_vat() {
		$shipping_vat = $this->get_shipping_vat();

		$vat_rates = Smartbill_Woocommerce_Settings::get_vat_rates();
		echo '<select class="smartbill-settings-size" name="smartbill_plugin_options_settings[shipping_vat]">';
		$counter = 0;
		if ( is_array( $vat_rates ) ) {
			foreach ( $vat_rates as $key => $vat ) {
				$counter++;
				echo '<option value="' . esc_attr( $key ) . '" ' . ( $shipping_vat == $key ? 'selected' : '' ) . '>' . esc_attr( $vat['percentage'] ) . '% - ' . esc_attr( $vat['name'] ) . '</option>';
			}
			echo '<option value="WooCommerce" '. ("WooCommerce" == $shipping_vat ? 'selected' : '' ) . '>' . esc_attr__('Preluata din WooCommerce', 'smartbill-woocommerce') . '</option>';
		}
		echo '</select>';

	}

	/**
	 * Get statuses for automatic document issueing
	 *
	 * @return array
	 */
	public function get_order_status() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['order_status'] ) ) {
			// Old version suport. If status is text add it to an array.
			if ( ! is_array( $options['order_status'] ) ) {
				$options['order_status'] = array( $options['order_status'] );
			}
			$selected_order_status = $options['order_status'];
		} else {
			$selected_order_status = array();
		}
		return $selected_order_status;
	}

	/**
	 * Get statuses for automatic document issueing
	 *
	 * @return void
	 */
	public function display_order_status() {
		$selected_order_status = $this->get_order_status();
		$order_statuses        = wc_get_order_statuses();

		echo '<select class="smartbill-settings-size" id="smrt-order-select" multiple name="smartbill_plugin_options_settings[order_status][]">';
		if ( ! empty( $order_statuses ) && is_array( $order_statuses ) ) {
			foreach ( $order_statuses as $k => $o ) {
				echo '<option value="' . esc_attr( $k ) . '" ' . ( in_array( $k, $selected_order_status ) ? 'selected' : '' ) . '>' . esc_attr( $o ) . '</option>';
			}
		}
		echo '</select>';

		echo '<p class="description">' . esc_attr__( 'Cand comanda va avea statusul ales mai sus, documentul va fi emis automat in SmartBill.', 'smartbill-woocommerce' ) . '</p>';
	}

	/**
	 * Display document type select setting
	 *
	 * @return void
	 */
	public function display_document_type() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['document_type'] ) ) {
			$document_type = $options['document_type'];
		} else {
			$document_type = Smartbill_Woocommerce_Settings::SMARTBILL_DOCUMENT_TYPE_INVOICE;
		}

		if ( Smartbill_Woocommerce_Settings::SMARTBILL_DOCUMENT_TYPE_INVOICE == $document_type && isset( $options['invoice_series'] ) ) {
			$document_series = $options['invoice_series'];
		} elseif ( Smartbill_Woocommerce_Settings::SMARTBILL_DOCUMENT_TYPE_ESTIMATE == $document_type && isset( $options['estimate_series'] ) ) {
			$document_series = $options['estimate_series'];
		} else {
			$document_series = '';
		}

		echo '
    <select class="smartbill-settings-size" id="smartbill_plugin_options_settings_document_type" name="smartbill_plugin_options_settings[document_type]">
        <option value="0" ' . ( empty( $document_type ) ? 'selected' : '' ) . '>' . esc_attr__( 'Factura', 'smartbill-woocommerce' ) . '</option>
        <option value="1" ' . ( ! empty( $document_type ) ? 'selected' : '' ) . '>' . esc_attr__( 'Proforma', 'smartbill-woocommerce' ) . '</option>
    </select>';
	}

	/**
	 * Get document series
	 *
	 * @param string $document_type Either invoice or estimate.
	 *
	 * @return string
	 */
	public function get_document_series( $document_type = 'invoice' ) {
		$options           = get_option( 'smartbill_plugin_options_settings' );
		$smb_document_type = 0;
		if ( 'estimate' == $document_type ) {
			$smb_document_type = 1;
		}
		// initially these won't be set in the database.
		if ( Smartbill_Woocommerce_Settings::SMARTBILL_DOCUMENT_TYPE_INVOICE == $smb_document_type && isset( $options['invoice_series'] ) ) {
			$document_series = $options['invoice_series'];
		} elseif ( Smartbill_Woocommerce_Settings::SMARTBILL_DOCUMENT_TYPE_ESTIMATE == $smb_document_type && isset( $options['estimate_series'] ) ) {
			$document_series = $options['estimate_series'];
		} else {
			$document_series = '';
		}
		return $document_series;

	}

	/**
	 * Display document series
	 *
	 * @return void
	 */
	public function display_document_series() {
		$options               = get_option( 'smartbill_plugin_options_settings' );
		$saved_document_series = array();
		$field_title           = 'invoice_series';
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['document_type'] ) ) {

			if ( Smartbill_Woocommerce_Settings::SMARTBILL_DOCUMENT_TYPE_INVOICE == $options['document_type'] ) {
				$saved_document_series = $this->get_document_series( 'invoice' );
				$field_title           = 'invoice_series';
				$document_series       = self::get_series( 'f' );
			} elseif ( Smartbill_Woocommerce_Settings::SMARTBILL_DOCUMENT_TYPE_ESTIMATE == $options['document_type'] ) {
				$saved_document_series = $this->get_document_series( 'estimate' );
				$field_title           = 'estimate_series';
				$document_series       = self::get_series( 'p' );
			}
		} else {
			// Set invoice series by default.
			$document_series = self::get_series( 'f' );
		}
		echo '
    	<select class="smartbill-settings-size" id="smartbill_plugin_options_settings_document_series" name="smartbill_plugin_options_settings[' . esc_attr( $field_title ) . ']">';
		if ( ! empty( $document_series ) && is_array( $document_series ) ) {
			foreach ( $document_series as $s ) {
				echo '<option value="' . esc_attr( $s ) . '" ' . ( $saved_document_series == $s ? 'selected' : '' ) . '>' . esc_attr( $s ) . '</option>';
			}
		}
		echo '</select>';
		echo '<div class="hide-element warning-setting-text">' . esc_attr__( 'Seriile vor fi vizibile dupa adaugarea primei serii in', 'smartbill-woocommerce' ) . ' <a target="_blank" href="https://cloud.smartbill.ro/core/configurare/serii/"><strong>' . esc_attr__( 'SmartBill Facturare/Gestiune > Configurare > Serii', 'smartbill-woocommerce' ) . '</strong></a>. </br>' . esc_attr__( 'Dupa crearea seriei, din pagina de setari acceseaza butonul', 'smartbill-woocommerce' ) . ' <a href="' . esc_url_raw( site_url() . '/wp-admin/admin.php?page=smartbill-woocommerce-settings' ) . '"><strong>' . esc_attr__( 'Preia configurari din SmartBill', 'smartbill-woocommerce' ) . '</strong></a>.</div>';
	}

	/**
	 * Get document date setting value
	 *
	 * @return int 1 for curent date // 2 for creation date.
	 */
	public function get_document_date() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['document_date'] ) ) {
			$document_date = $options['document_date'];
		} else {
			$document_date = '1';
		}
		return $document_date;
	}

	/**
	 * Display document date setting
	 *
	 * @return void
	 */
	public function display_document_date() {
		$document_date = $this->get_document_date();
		echo '
            <select class="smartbill-settings-size" name="smartbill_plugin_options_settings[document_date]" id="smartbill_settings_document_date">
            <option value="1" ' . ( '1' == $document_date ? 'selected' : '' ) . '>' . esc_attr__( 'Data curenta', 'smartbill-woocommerce' ) . '</option>
            <option value="2" ' . ( '2' == $document_date ? 'selected' : '' ) . '>' . esc_attr__( 'Data crearii comenzii', 'smartbill-woocommerce' ) . '</option>
        ';
	}

	/**
	 * Get selectet measuring unit
	 *
	 * @return string
	 */
	public function get_um() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['um'] ) ) {
			$um = $options['um'];
		} else {
			$um = 'preluata-din-smartbill';
		}
		return $um;
	}

	/**
	 * Display measuring unit setting
	 *
	 * @return void
	 */
	public function display_um() {
		$um              = trim( $this->get_um() );
		$measuring_units = self::get_measuring_units();
		if ( empty( $um ) ) {
			$um = 'preluata-din-smartbill';
		}
		echo '
            <select class="smartbill-settings-size" name="smartbill_plugin_options_settings[um]" id="smartbill_settings_display_um">
            <option value="preluata-din-smartbill" ' . ( 'preluata-din-smartbill' == $um ? 'selected' : '' ) . '>' . esc_attr__( 'Preluata din nomenclatorul SmartBill', 'smartbill-woocommerce' ) . '</option>
        ';
		if ( ! empty( $measuring_units ) && is_array( $measuring_units ) ) {
			foreach ( $measuring_units as $m ) {
				if ( 'no_value' != $m && 'preluata-din-smartbill' != $m ) {
					echo '<option value="' . esc_attr( $m ) . '" ' . ( $m == $um ? 'selected' : '' ) . '>' . esc_attr( $m ) . '</option>';
				}
			}
		}
		echo '</select>';
		echo '<p class="description">' . esc_attr__( 'Alege unitatea de masura care se va aplica produselor de pe comanda, la facturare', 'smartbill-woocommerce' ) . '</p><div class="hide-element warning-setting-text">' . esc_attr__( 'Unitatile de masura vor fi vizibile dupa adaugarea primului produs in', 'smartbill-woocommerce' ) . ' <a target="_blank" href="https://cloud.smartbill.ro/nomenclator/produse/"><strong>' . esc_attr__( 'SmartBill Facturare/Gestiune > Nomenclator > Produse', 'smartbill-woocommerce' ) . '</strong></a>. </br>' . esc_attr__( 'Dupa crearea produsului, din pagina de setari acceseaza butonul', 'smartbill-woocommerce' ) . ' <a href="' . esc_url_raw( site_url() . '/wp-admin/admin.php?page=smartbill-woocommerce-settings' ) . '"><strong> ' . esc_attr__( 'Preia configurari din SmartBill', 'smartbill-woocommerce' ) . ' </strong></a>.</div>';
	}

	/**
	 * Get selected cif setting vlaue
	 *
	 * @return boolean
	 */
	public function get_cif() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['cif'] ) ) {
			if ( 'intracom' == $options['cif'] ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Display cif setting
	 *
	 * @return void
	 */
	public function display_cif() {
		$cif = $this->get_cif();

		$login_options = get_option( 'smartbill_plugin_options' );
		echo '
            <select class="smartbill-settings-size" name="smartbill_plugin_options_settings[cif]" id = "smartbill_settings_display_cif">
			<option value = "authcif" ' . ( ! $cif ? 'selected' : '' ) . '>' . esc_attr( $login_options['vat_code'] ) . '</option>
            <option value="intracom" ' . ( $cif ? 'selected' : '' ) . '>' . esc_attr__( 'CIF intracomunitar', 'smartbill-woocommerce' ) . '</option>
            </select>
        ';
		echo '<p class="description">' . esc_attr__( 'In cazul', 'smartbill-woocommerce' ) . ' <a target="_blank" href ="https://ajutor.smartbill.ro/article/245-cif-intracomunitar">' . esc_attr__( 'CIF-ului intracomunitar', 'smartbill-woocommerce' ) . '</a>, ' . esc_attr__( 'acesta va fi preluat din', 'smartbill-woocommerce' ) . ' <a target="_blank" href="https://cloud.smartbill.ro/core/configurare/date-firma/"><strong>' . esc_attr__( 'SmartBill>Configurare', 'smartbill-woocommerce' ) . '</strong></a> ' . esc_attr__( 'si va fi prezent doar pe facturile emise catre clienti din afara Romaniei', 'smartbill-woocommerce' ) . '.</p>';

	}

	/**
	 * Get selected billing currency
	 *
	 * @return string
	 */
	public static function get_billing_currency() {
		$options    = get_option( 'smartbill_plugin_options_settings' );
		$currencies = Smartbill_Woocommerce_Settings::get_currencies();
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['billing_currency'] ) ) {
			$billing_currency = $options['billing_currency'];
			if ( is_array( $currencies ) && ! empty( $billing_currency ) ) {
				$has_changed = true;
				foreach ( $currencies as $currency ) {
					if ( $currency['value'] == $billing_currency ) {
						$has_changed = false;
						break;
					}
				}
				if ( $has_changed ) {
					$billing_currency = $currencies[ count( $currencies ) - 1 ]['value'];
				}
			}
		} else {
			if ( is_array( $currencies ) ) {
				$billing_currency = $currencies[0]['value'];
			}
		}
		return $billing_currency;
	}

	/**
	 * Display billing currency select field
	 *
	 * @return void
	 */
	public function display_billing_currency() {
		$billing_currency = self::get_billing_currency();
		$currencies       = Smartbill_Woocommerce_Settings::get_currencies();

		echo '
        <select class="smartbill-settings-size" name="smartbill_plugin_options_settings[billing_currency]">';
		if ( is_array( $currencies ) ) {
			foreach ( $currencies as $currency ) {
				echo '<option value="' . esc_attr( $currency['value'] ) . '" ' . ( $currency['value'] == $billing_currency ? 'selected' : '' ) . '>' . esc_attr( $currency['label'] ) . '</option>';
			}
		}

		echo '</select>';
	}

	/**
	 * Get selected language
	 *
	 * @return string
	 */
	public static function get_language() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['invoice_lang'] ) ) {
			$invoice_lang = $options['invoice_lang'];
		} else {
			$invoice_lang = 'RO';
		}
		return $invoice_lang;
	}

	/**
	 * Display document language select field
	 *
	 * @return void
	 */
	public function display_language() {
		$invoice_lang = self::get_language();
		$languages    = Smartbill_Woocommerce_Settings::get_languages();

		echo '
        <select class="smartbill-settings-size" name="smartbill_plugin_options_settings[invoice_lang]">';
		if ( is_array( $languages ) ) {
			foreach ( $languages as $language ) {
				echo '<option value="' . esc_attr( $language['value'] ) . '" ' . ( $language['value'] == $invoice_lang ? 'selected' : '' ) . '>' . esc_attr( $language['label'] ) . '</option>';
			}
		}

		echo '</select>';
	}

	/**
	 * Get is shipping included value
	 *
	 * @return boolean
	 */
	public function get_include_shipping() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['include_shipping'] ) ) {
			$include_shipping = $options['include_shipping'];
		} else {
			$include_shipping = true;
		}
		return $include_shipping;
	}

	/**
	 * Display is shipping included setting
	 *
	 * @return void
	 */
	public function display_include_shipping() {
		$include_shipping = $this->get_include_shipping();

		echo '
    <select class="smartbill-settings-size show_shipping_cost" name="smartbill_plugin_options_settings[include_shipping]">
        <option value="1" ' . ( ! empty( $include_shipping ) ? 'selected' : '' ) . '>' . esc_attr__( 'Da', 'smartbill-woocommerce' ) . '</option>
        <option value="0" ' . ( empty( $include_shipping ) ? 'selected' : '' ) . '>' . esc_attr__( 'Nu', 'smartbill-woocommerce' ) . '</option>
    </select>';
	}

	/**
	 * Get shipping name
	 *
	 * @return string
	 */
	public function get_shipping_name() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['shipping_name'] ) && ! empty( $options['shipping_name'] ) ) {
			$shipping_name = $options['shipping_name'];
		} else {
			$shipping_name = esc_attr__( 'Transport', 'smartbill-woocommerce' );
		}
		return $shipping_name;
	}

	/**
	 * Display shipping name setting
	 *
	 * @return void
	 */
	public function display_shipping_name() {
		$shipping_name = $this->get_shipping_name();
		echo '<input class="smartbill-settings-size" name="smartbill_plugin_options_settings[shipping_name]" value="' . esc_attr( $shipping_name ) . '">';
		echo '<p class="description">' . esc_attr__( 'Pentru afisarea numelui metodei de livrare setat in WooCommerce poti introduce #nume_transport#.', 'smartbill-woocommerce' ) . '</p>';
	}


	/**
	 * Get free shipping smartbill setting
	 *
	 * @return boolean
	 */
	public function get_free_shipping() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['free_shipping'] ) ) {
			$free_shipping = $options['free_shipping'];
		} else {
			$free_shipping = 0;
		}
		return $free_shipping;
	}

	/**
	 * Display free shipping options
	 *
	 * @return void
	 */
	public function display_free_shipping(){
		$free_shipping = $this->get_free_shipping();
		echo '
		<select class="smartbill-settings-size"  name="smartbill_plugin_options_settings[free_shipping]">
			<option value="0" ' . ( empty( $free_shipping ) ? 'selected' : '' ) . '>' . esc_attr__( 'Adauga transportul pe factura', 'smartbill-woocommerce' ) . '</option>
			<option value="1" ' . ( !empty( $free_shipping ) ? 'selected' : '' ) . '>' . esc_attr__( 'Nu adauga transportul pe factura', 'smartbill-woocommerce' ) . '</option>
		</select>';
	}



	/**
	 * Get save client in smartbill setting
	 *
	 * @return boolean
	 */
	public function get_save_client() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['save_client'] ) ) {
			$save_client = $options['save_client'];
		} else {
			$save_client = 0;
		}
		return $save_client;
	}

	/**
	 * Display Save client in smartbill select field
	 *
	 * @return void
	 */
	public function display_save_client() {
		$save_client = $this->get_save_client();

		echo '
    <select class="smartbill-settings-size"  name="smartbill_plugin_options_settings[save_client]">
        <option value="1" ' . ( ! empty( $save_client ) ? 'selected' : '' ) . '>' . esc_attr__( 'Da', 'smartbill-woocommerce' ) . '</option>
        <option value="0" ' . ( empty( $save_client ) ? 'selected' : '' ) . '>' . esc_attr__( 'Nu', 'smartbill-woocommerce' ) . '</option>
    </select>';
		echo '<p class="description">' . esc_attr__( 'Salvand clientul in SmartBill, vei avea datele lui disponibile pentru emiteri ulterioare', 'smartbill-woocommerce' ) . '</p>';
	}

	/**
	 * Get smartbill product setting value
	 *
	 * @return boolean
	 */
	public function get_smartbill_product() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['smartbill_product'] ) ) {
			$smartbill_product = $options['smartbill_product'];
		} else {
			$smartbill_product = 0;
		}
		return $smartbill_product;
	}

	/**
	 * Dispaly smartbill product select field
	 *
	 * @return void
	 */
	public function display_smartbill_product() {
		 $smartbill_product = $this->get_smartbill_product();

		echo '
        <select class="smartbill-settings-size" name="smartbill_plugin_options_settings[smartbill_product]">
            <option value="1" ' . ( ! empty( $smartbill_product ) ? 'selected' : '' ) . '>' . esc_attr__( 'Da', 'smartbill-woocommerce' ) . '</option>
            <option value="0" ' . ( empty( $smartbill_product ) ? 'selected' : '' ) . '>' . esc_attr__( 'Nu', 'smartbill-woocommerce' ) . '</option>
        </select>';
		echo '<p class="description">' . esc_attr__( 'Pe documentul emis se vor afisa denumirile produselor din SmartBill, pe baza codului de produs', 'smartbill-woocommerce' ) . '</p>';
	}

	/**
	 * Get save product in smartbill setting value
	 *
	 * @return boolean
	 */
	public function get_save_product() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['save_product'] ) ) {
			$save_product = $options['save_product'];
		} else {
			$save_product = 0;
		}
		return $save_product;
	}

	/**
	 * Dispaly save product in smartbill select field
	 *
	 * @return void
	 */
	public function display_save_product() {
		$save_product = $this->get_save_product();

		echo '
    <select class="smartbill-settings-size" name="smartbill_plugin_options_settings[save_product]">
        <option value="1" ' . ( ! empty( $save_product ) ? 'selected' : '' ) . '>' . esc_attr__( 'Da', 'smartbill-woocommerce' ) . '</option>
        <option value="0" ' . ( empty( $save_product ) ? 'selected' : '' ) . '>' . esc_attr__( 'Nu', 'smartbill-woocommerce' ) . '</option>
    </select>';
		echo '<p class="description">' . esc_attr__( 'Salvand produsul in SmartBill, vei avea datele disponibile pentru emiteri ulterioare', 'smartbill-woocommerce' ) . '</p>';
	}

	/**
	 * Get due days setting value
	 *
	 * @return int
	 */
	public function get_due_days() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['due_days'] ) ) {
			$selected_due_days = $options['due_days'];
		} else {
			$selected_due_days = 15;
		}
		return $selected_due_days;
	}

	/**
	 * Get value of issue with due date setting
	 * If false the document won't have a due date
	 *
	 * @return boolean
	 */
	public function get_issue_with_due_date() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['issue_with_due_date'] ) ) {
			$selected_issue_with_due_date = $options['issue_with_due_date'];
		} else {
			// by default it should be false.
			$selected_issue_with_due_date = false;
		}
		return $selected_issue_with_due_date;

	}

	/**
	 * Display select field document has due date
	 *
	 * @return void
	 */
	public function display_issue_with_due_date() {
		$selected_issue_with_due_date = $this->get_issue_with_due_date();

		$options = array(
			true  => esc_attr__( 'Da', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);

		echo '<select class="smartbill-settings-size issue_with_due_date" name="smartbill_plugin_options_settings[issue_with_due_date]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $selected_issue_with_due_date == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}
		echo '</select>';
	}

	/**
	 * Get show delivery days
	 *
	 * @return boolean
	 */
	public function get_show_delivery_days() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['show_delivery_days'] ) ) {
			$selected_show_delivery_days = $options['show_delivery_days'];
		} else {
			// by default it should be false.
			$selected_show_delivery_days = false;
		}
		return $selected_show_delivery_days;

	}

	/**
	 * Display show velivery days select field
	 *
	 * @return void
	 */
	public function display_show_delivery_days() {
		$selected_show_delivery_days = $this->get_show_delivery_days();

		$options = array(
			true  => esc_attr__( 'Da', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);

		echo '<select class="smartbill-settings-size show_delivery_days" name="smartbill_plugin_options_settings[show_delivery_days]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $selected_show_delivery_days == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}
		echo '</select>';
	}

	/**
	 * Display due days input field
	 *
	 * @return void
	 */
	public function display_due_days() {
		$selected_due_days = $this->get_due_days();
		echo '<input class="smartbill-settings-size" name="smartbill_plugin_options_settings[due_days]" value="' . esc_attr( $selected_due_days ) . '">';
	}

	/**
	 * Get delivery days setting value
	 *
	 * @return int
	 */
	public function get_delivery_days() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['delivery_days'] ) ) {
			$selected_delivery_days = $options['delivery_days'];
		} else {
			$selected_delivery_days = 15;
		}
		return $selected_delivery_days;
	}

	/**
	 * Display delivery days input field
	 *
	 * @return void
	 */
	public function display_delivery_days() {
		$selected_delivery_days = $this->get_delivery_days();
		echo '<input class="smartbill-settings-size"  name="smartbill_plugin_options_settings[delivery_days]" value="' . esc_attr( $selected_delivery_days ) . '">';
	}

	/**
	 * Get dd payment link to invoice setting value
	 *
	 * @return boolean
	 */
	public function get_payment_url() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['payment_url'] ) ) {
			$payment_url = $options['payment_url'];
		} else {
			$payment_url = false;
		}
		return $payment_url;
	}

	/**
	 * Display add payment link to invoice setting
	 *
	 * @return void
	 */
	public function display_payment_url(){
		$payment_url = $this->get_payment_url();

		$options = array(
			true  => esc_attr__( 'Da', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);

		echo '<select class="smartbill-settings-size" name="smartbill_plugin_options_settings[payment_url]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $payment_url == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}
		echo '</select>';
		echo '<p class="description">' .sprintf( esc_attr__('Este necesar ca in %1$s SmartBill > Contul Meu > Integrari%2$s sa fie configurat un procesator de plati ( Netopia sau EuPlatesc)', 'smartbill-woocommerce' ),'<a target="_blank" href="https://cloud.smartbill.ro/core/integrari/">','</a>') . '</p>';	
	}

	/**
	 * Get invoice cashing setting value
	 *
	 * @return boolean
	 */
	public function get_invoice_cashing() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['invoice_cashing'] ) ) {
			$selected_invoice_cashing = $options['invoice_cashing'];
		} else {
			$selected_invoice_cashing = false;
		}
		return $selected_invoice_cashing;
	}

	/**
	 * Display invoice cashing select field
	 *
	 * @return void
	 */
	public function display_invoice_cashing() {
		$selected_invoice_cashing = $this->get_invoice_cashing();

		$options = array(
			true  => esc_attr__( 'Da, cu card online', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);

		echo '<select class="smartbill-settings-size" name="smartbill_plugin_options_settings[invoice_cashing]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $selected_invoice_cashing == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}
		echo '</select>';
	}

	/**
	 * Display invoice draft setting value
	 *
	 * @return boolean
	 */
	public function get_invoice_is_draft() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['invoice_is_draft'] ) ) {
			$selected_invoice_is_draft = $options['invoice_is_draft'];
		} else {
			$selected_invoice_is_draft = false;
		}
		return $selected_invoice_is_draft;
	}

	/**
	 * Display invoice is draft select field
	 *
	 * @return void
	 */
	public function display_invoice_is_draft() {
		$selected_invoice_is_draft = $this->get_invoice_is_draft();

		$options = array(
			true  => esc_attr__( 'Da', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);

		echo '<select class="smartbill-settings-size" name="smartbill_plugin_options_settings[invoice_is_draft]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $selected_invoice_is_draft == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}
		echo '</select>';
	}

	/**
	 * Get download stock setting value
	 *
	 * @return string
	 */
	public function get_stock() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['stock'] ) ) {
			$selected_stock = $options['stock'];
		} else {
			$selected_stock = 'fara-gestiune';
		}
		return $selected_stock;
	}

	/**
	 * Display download stock select field
	 *
	 * @return void
	 */
	public function display_stock() {
		$selected_stock = $this->get_stock();
		// get list of smartbill stocks.
		$stocks = self::get_stocks();

		echo '<select class="smartbill-settings-size" name="smartbill_plugin_options_settings[stock]" id="smartbill_settings_display_stock">';
		echo '<option value="fara-gestiune">' . esc_attr__( 'Nu', 'smartbill-woocommerce' ) . '</option>';
		if ( is_array( $stocks ) ) {
			foreach ( $stocks as $stock ) {
				echo '<option value="' . esc_attr( $stock ) . '" ' . ( $stock == $selected_stock ? 'selected' : '' ) . '>' . esc_attr( $stock ) . '</option>';
			}
		}
		echo '</select>';
		echo '<p class="description">' . esc_attr__( 'Alege gestiunea din care se face descarcarea la emiterea facturii', 'smartbill-woocommerce' ) . '</p>';
		echo '<div class="hide-element warning-setting-text">' . esc_attr__( 'Gestiunile vor fi vizibile dupa adaugarea primei gestiuni in', 'smartbill-woocommerce' ) . ' <a target="_blank" href="https://cloud.smartbill.ro/nomenclator/gestiuni/"><strong>' . esc_attr__( 'SmartBill Gestiune > Nomenclatoare > Gestiuni', 'smartbill-woocommerce' ) . '</strong></a> ' . esc_attr__( 'si dupa adaugarea unui produs pe aceasta gestiune.', 'smartbill-woocommerce' ) . '</br>' . esc_attr__( 'Dupa adaugarea produsului pe gestiune, din pagina de setari acceseaseaza butonul', 'smartbill-woocommerce' ) . ' <a href="' . esc_url_raw( site_url() . '/wp-admin/admin.php?page=smartbill-woocommerce-settings' ) . '"><strong>' . esc_attr__( 'Preia configurari din SmartBill', 'smartbill-woocommerce' ) . '</strong></a>.</div>';
	}

	/**
	 * Get synchronize stock setting value
	 *
	 * @return boolean
	 */
	public function get_sync_stock() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['sync_stock'] ) ) {
			$selected_stock = $options['sync_stock'];
		} else {
			$selected_stock = 0;
		}
		return $selected_stock;
	}

	/**
	 * Display synchronize stock setting value
	 *
	 * @return void
	 */
	public function display_sync_stock() {
		$selected_sync_stock = $this->get_sync_stock();

		$options = array(
			true  => esc_attr__( 'Da', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);

		echo '<select class="smartbill-settings-size smartbill_sync_stock" name="smartbill_plugin_options_settings[sync_stock]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $selected_sync_stock == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}

		echo '</select>';
		echo '<div class="token-details"><p id="url_container_stocks">URL:  <strong >' . esc_url_raw( $this->get_webhook_url() ) . '</strong>';
		echo '</p><p>' . esc_attr__( 'Acest URL va fi introdus in ', 'smartbill_woocommerce' ) .
		'<a target="_blank" href="https://cloud.smartbill.ro/core/integrari/">' . esc_attr__( 'SmartBill Cloud > Contul Meu > Integrari > Sincronizare stocuri > URL.', 'smartbill_woocommerce' ) . '</a></p>';
		echo '<p>' . esc_attr__( 'Token-ul cu care se face autentificarea in plugin-ul SmartBill va trebui introdus in ', 'smartbill_woocommerce' ) .
		'<a target="_blank" href="https://cloud.smartbill.ro/core/integrari/">' . esc_attr__( 'SmartBill Cloud > Contul Meu > Integrari > Sincronizare stocuri > Token autentificare.' ) . '</a></p>';
		echo '<p><a href="https://www.youtube.com/watch?v=sqXSp0MH4h0" id="smartbill-youtube-stock" target="_blank" class="button button-secondary">' . esc_attr__( 'Vezi video', 'smartbill_woocommerce' ) . '</a>';
		echo '<a href="https://ajutorgestiune.smartbill.ro/article/822-sincronizarea-stocurilor-cu-magazinul-online" target="_blank" class="button button-secondary">' . esc_attr__( 'Consulta ghid', 'smartbill_woocommerce' ) . '</a></p></div>';
	}

	/**
	 * Get public endpoint for stock sync
	 *
	 * @return string
	 */
	public function get_webhook_url() {
		return site_url() . '/wp-json/smartbill_woocommerce/v1/stocks';
	}

	/**
	 * Get used stock for stoc sync setting value
	 *
	 * @return string
	 */
	public function get_used_stock() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['used_stock'] ) ) {
			$selected_stock = $options['used_stock'];
		} else {
			$selected_stock = 'fara-gestiune';
		}
		return $selected_stock;
	}

	/**
	 * Display used stock select field
	 *
	 * @return void
	 */
	public function display_used_stock() {
		$selected_stock = $this->get_used_stock();
		$stocks         = self::get_stocks();
		echo '<select class="smartbill-settings-size" name="smartbill_plugin_options_settings[used_stock]">';
		echo '<option value="fara-gestiune" >' . esc_attr__( 'Alege gestiunea', 'smartbill-woocommerce' ) . '</option>';
		if ( is_array( $stocks ) ) {
			foreach ( $stocks as $stock ) {
				echo '<option value="' . esc_attr( $stock ) . '" ' . ( $stock == $selected_stock ? 'selected' : '' ) . '>' . esc_attr( $stock ) . '</option>';
			}
		}
		echo '</select> <input id="smartbill-manually-sync-stock" type="button" class="button button-secondary" value="' . esc_attr__( 'Preia manual stocurile din SmartBill', 'smartbill-woocommerce' ) . '" />';;
		echo '<p class="description">' . esc_attr__( 'Alege gestiunea din care se preia stocul produselor.', 'smartbill-woocommerce' ) . '</p>';
	}

	/**
	 * Get save stock history
	 *
	 * @return boolean
	 */
	public function get_save_stock_history() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		// create & write log file.
		$file = __DIR__ . DIRECTORY_SEPARATOR . 'smartbill_sincronizare_stocuri.log';
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['save_stock_history'] ) ) {
			$save_stock_history = $options['save_stock_history'];
			if ( true == $save_stock_history && ! file_exists( $file ) ) {
				$mylog        = fopen( $file, 'a' );
				$firstmessage = json_encode( '##Started Logging##' ) . "\n";
				fwrite( $mylog, $firstmessage );
				fclose( $mylog );
			}
			if ( file_exists( $file ) && false == $save_stock_history ) {
				unlink( $file );
			}
		} else {
			if ( file_exists( $file ) ) {
				unlink( $file );
			}
			$save_stock_history = false;
		}
		return $save_stock_history;
	}

	/**
	 * Dispaly save stock history select field
	 *
	 * @return void
	 */
	public function display_save_stock_history() {
		$save_stock_history = $this->get_save_stock_history();
		$options            = array(
			true  => esc_attr__( 'Da', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);

		echo '<select class="smartbill-settings-size smartbill_display_save_stock_history" name="smartbill_plugin_options_settings[save_stock_history]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $save_stock_history == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}
		echo '</select>';
		if ( true == $save_stock_history ) {
			echo '<br/><br/><input id="smartbill-download-sync-stock-history" type="button" class="button button-secondary" value="' . esc_attr__( 'Descarca istoric', 'smartbill-woocommerce' ) . '" />';
		}
		$date = get_option('smartbill_stock_update');
		if(!empty($date)){
			$date = unserialize($date);
			echo '<p>'.esc_attr__('Data ultimei actualizari a stocurilor: ').$date->format('d/m/Y H:i').'</p>';
		}
	}

	/**
	 * Get company info setting value
	 *
	 * @return boolean
	 */
	public function get_company_info() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['company_info'] ) ) {
			$selected_company_info = $options['company_info'];
		} else {
			$selected_company_info = false;
		}
		return $selected_company_info;
	}

	/**
	 * Display company info select field
	 *
	 * @return void
	 */
	public function display_company_info() {
		$company_info = $this->get_company_info();

		$options = array(
			true  => esc_attr__( 'Da', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);

		echo '<select class="smartbill-settings-size smartbill_display_company_info" name="smartbill_plugin_options_settings[company_info]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $company_info == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}
		echo '</select>';
	}

	/**
	 * Get personal information for company setting value
	 *
	 * @return boolean
	 */
	public function get_person_info_for_company() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['person_info_for_company'] ) ) {
			$selected_person_info_for_company = $options['person_info_for_company'];
		} else {
			$selected_person_info_for_company = false;
		}
		return $selected_person_info_for_company;
	}

	/**
	 * Display personal information for company select field
	 *
	 * @return void
	 */
	public function display_person_info_for_company() {
		$person_info_for_company = $this->get_person_info_for_company();

		$options = array(
			true  => esc_attr__( 'Da', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);

		echo '<select class="smartbill-settings-size" name="smartbill_plugin_options_settings[person_info_for_company]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $person_info_for_company == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}
		echo '</select>';
	}

	/**
	 * Get enable custom checkout setting value
	 *
	 * @return boolean
	 */
	public function get_custom_checkout() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['custom_checkout'] ) ) {
			$selected_custom_checkout = $options['custom_checkout'];
		} else {
			$selected_custom_checkout = false;
		}
		return $selected_custom_checkout;
	}

	/**
	 * Dispaly custom checkout select field
	 *
	 * @return void
	 */
	public function display_custom_checkout() {
		$display_custom_checkout = $this->get_custom_checkout();
		$options                 = array(
			true  => esc_attr__( 'Da', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);

		echo '<select id="custom-checkout" class="smartbill-settings-size" name="smartbill_plugin_options_settings[custom_checkout]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $display_custom_checkout == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}
		echo '</select>';
	}

	/**
	 * Get custom checkout options values
	 *
	 * @return array
	 */
	public function get_custom_checkout_options() {
		$options                 = get_option( 'smartbill_plugin_options_settings' );
		$custom_checkout_options = array();
		if ( ! empty( $options ) && is_array( $options ) ) {
			if ( isset( $options['client_type_trans'] ) ) {
				$custom_checkout_options['client_type_trans'] = $options['client_type_trans'];
			} else {
				$custom_checkout_options['client_type_trans'] = esc_attr__( 'Tip Client', 'smartbill-woocommerce' );
			}
			if ( isset( $options['client_individual_trans'] ) ) {
				$custom_checkout_options['client_individual_trans'] = $options['client_individual_trans'];
			} else {
				$custom_checkout_options['client_individual_trans'] = esc_attr__( 'Persoana fizica', 'smartbill-woocommerce' );
			}
			if ( isset( $options['client_entity_trans'] ) ) {
				$custom_checkout_options['client_entity_trans'] = $options['client_entity_trans'];
			} else {
				$custom_checkout_options['client_entity_trans'] = esc_attr__( 'Persoana juridica', 'smartbill-woocommerce' );
			}
			if ( isset( $options['cif_trans'] ) ) {
				$custom_checkout_options['cif_trans'] = $options['cif_trans'];
			} else {
				$custom_checkout_options['cif_trans'] = esc_attr__( 'CIF', 'smartbill-woocommerce' );
			}
			if ( isset( $options['cif_error_trans'] ) ) {
				$custom_checkout_options['cif_error_trans'] = $options['cif_error_trans'];
			} else {
				$custom_checkout_options['cif_error_trans'] = esc_attr__( 'Campul CIF este obligatoriu.', 'smartbill-woocommerce' );
			}
			if ( isset( $options['company_name_trans'] ) ) {
				$custom_checkout_options['company_name_trans'] = $options['company_name_trans'];
			} else {
				$custom_checkout_options['company_name_trans'] = esc_attr__( 'Denumire companie', 'smartbill-woocommerce' );
			}
			if ( isset( $options['company_name_error_trans'] ) ) {
				$custom_checkout_options['company_name_error_trans'] = $options['company_name_error_trans'];
			} else {
				$custom_checkout_options['company_name_error_trans'] = esc_attr__( 'Campul Denumire companie este obligatoriu.', 'smartbill-woocommerce' );
			}
			if ( isset( $options['reg_com_trans'] ) ) {
				$custom_checkout_options['reg_com_trans'] = $options['reg_com_trans'];
			} else {
				$custom_checkout_options['reg_com_trans'] = esc_attr__( 'Nr. inregistrare Registrul Comertului', 'smartbill-woocommerce' );
			}
			if ( isset( $options['reg_com_error_trans'] ) ) {
				$custom_checkout_options['reg_com_error_trans'] = $options['reg_com_error_trans'];
			} else {
				$custom_checkout_options['reg_com_error_trans'] = esc_attr__( 'Campul Nr. inregistrare Registrul Comertului este obligatoriu.', 'smartbill-woocommerce' );
			}
			if ( isset( $options['cif_vis'] ) ) {
				$custom_checkout_options['cif_vis'] = $options['cif_vis'];
			} else {
				$custom_checkout_options['cif_vis'] = true;
			}
			if ( isset( $options['cif_req'] ) ) {
				$custom_checkout_options['cif_req'] = $options['cif_req'];
			} else {
				$custom_checkout_options['cif_req'] = true;
			}
			$custom_checkout_options['company_name_req'] = true;
			$custom_checkout_options['company_name_vis'] = true;
			if ( isset( $options['reg_com_req'] ) ) {
				$custom_checkout_options['reg_com_req'] = $options['reg_com_req'];
			} else {
				$custom_checkout_options['reg_com_req'] = true;
			}
			if ( isset( $options['reg_com_vis'] ) ) {
				$custom_checkout_options['reg_com_vis'] = $options['reg_com_vis'];
			} else {
				$custom_checkout_options['reg_com_vis'] = true;
			}
		} else {
			$custom_checkout_options['client_type_trans']        = esc_attr__( 'Tip Client', 'smartbill-woocommerce' );
			$custom_checkout_options['client_individual_trans']  = esc_attr__( 'Persoana fizica', 'smartbill-woocommerce' );
			$custom_checkout_options['client_entity_trans']      = esc_attr__( 'Persoana juridica', 'smartbill-woocommerce' );
			$custom_checkout_options['cif_trans']                = esc_attr__( 'CIF', 'smartbill-woocommerce' );
			$custom_checkout_options['cif_error_trans']          = esc_attr__( 'Campul CIF este obligatoriu.', 'smartbill-woocommerce' );
			$custom_checkout_options['company_name_trans']       = esc_attr__( 'Denumire companie', 'smartbill-woocommerce' );
			$custom_checkout_options['company_name_error_trans'] = esc_attr__( 'Campul Denumire companie este obligatoriu.', 'smartbill-woocommerce' );
			$custom_checkout_options['reg_com_trans']            = esc_attr__( 'Nr. inregistrare Registrul Comertului', 'smartbill-woocommerce' );
			$custom_checkout_options['reg_com_error_trans']      = esc_attr__( 'Campul Nr. inregistrare Registrul Comertului este obligatoriu.', 'smartbill-woocommerce' );
			$custom_checkout_options['cif_vis']                  = true;
			$custom_checkout_options['reg_com_req']              = true;
			$custom_checkout_options['reg_com_vis']              = true;
			$custom_checkout_options['company_name_req']         = true;
			$custom_checkout_options['company_name_vis']         = true;
			$custom_checkout_options['cif_req']                  = true;
		}

		return $custom_checkout_options;
	}

	/**
	 * Display custom checkout options fields in a table
	 *
	 * @return void
	 */
	public function display_custom_checkout_options() {
		$custom_checkout_options = $this->get_custom_checkout_options();
		$options                 = array(
			true  => esc_attr__( 'Da', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);

		echo '<table id="custom-checkout-options-table" class="wc_tax_rates wc_input_table widefat">
        <thead >
            <tr>
                <th>' . esc_attr__( 'Optiune', 'smartbill-woocommerce' ) . '</th>
                <th>' . esc_attr__( 'Text Afisat', 'smartbill-woocommerce' ) . '</th>
                <th>' . esc_attr__( 'Vizibil pe pagina de checkout', 'smartbill-woocommerce' ) . '</th>
                <th>' . esc_attr__( 'Camp obligatoriu', 'smartbill-woocommerce' ) . '</th>
                <th>' . esc_attr__( 'Eroarea afisata cand campul nu este completat', 'smartbill-woocommerce' ) . '</th>
            </tr>
        </thead>
        <tbody id="custom-checkout-options">
        <tr>
            <td class="custom-checkout-title">
              ' . esc_attr__( 'Tip client', 'smartbill-woocommerce' ) . '
            </td>

            <td class="client-type-trans">
                <input type="text" name="smartbill_plugin_options_settings[client_type_trans]" value="' . esc_attr( $custom_checkout_options['client_type_trans'] ) . '" placeholder=""  autocomplete="off">
            </td>

            <td class="empty-cell"></td><td class="empty-cell"></td><td class="empty-cell"></td>
        </tr>
        <tr>
            <td class="custom-checkout-title">
                ' . esc_attr__( 'Persoana fizica', 'smartbill-woocommerce' ) . '
            </td>

            <td class="client-individual-trans">
                <input type="text" name="smartbill_plugin_options_settings[client_individual_trans]" value="' . esc_attr( $custom_checkout_options['client_individual_trans'] ) . '" placeholder="" autocomplete="off">
            </td>

            <td class="empty-cell"></td><td class="empty-cell"></td><td class="empty-cell"></td>
        </tr>
        <tr>
            <td class="custom-checkout-title">
                ' . esc_attr__( 'Persoana juridica', 'smartbill-woocommerce' ) . '
            </td>

            <td class="client-entity-trans">
                <input type="text" name="smartbill_plugin_options_settings[client_entity_trans]" value="' . esc_attr( $custom_checkout_options['client_entity_trans'] ) . '" placeholder="" autocomplete="off">
            </td>

            <td class="empty-cell"></td><td class="empty-cell"></td><td class="empty-cell"></td>
        </tr>
        <tr>
            <td class="custom-checkout-title">
                ' . esc_attr__( 'CIF', 'smartbill-woocommerce' ) . '
            </td>

            <td class="cif-trans">
                <input type="text" name="smartbill_plugin_options_settings[cif_trans]" value="' . esc_attr( $custom_checkout_options['cif_trans'] ) . '" placeholder="" autocomplete="off">
            </td>
            <td class="cif-vis">
                <select name="smartbill_plugin_options_settings[cif_vis]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $custom_checkout_options['cif_vis'] == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}
		echo '</select>
            </td>
            <td class="cif-req">    
                <select name="smartbill_plugin_options_settings[cif_req]">';

		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $custom_checkout_options['cif_req'] == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}

		echo '</select>
            </td>
            <td class="cif-error-trans">
                <input type="text" name="smartbill_plugin_options_settings[cif_error_trans]" value="' . esc_attr( $custom_checkout_options['cif_error_trans'] ) . '" placeholder="" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td class="custom-checkout-title">
              ' . esc_attr__( ' Denumire companie', 'smartbill-woocommerce' ) . '
            </td>

            <td class="company-name-trans">
                <input type="text" name="smartbill_plugin_options_settings[company_name_trans]" value="' . esc_attr( $custom_checkout_options['company_name_trans'] ) . '" placeholder="" autocomplete="off">
            </td>
                
            <td class="company-name-visb">
                &nbsp; &nbsp; ' . esc_attr__( 'Da', 'smartbill-woocommerce' ) . '
                
            </td>
            <td class="company-name-req">
                &nbsp; &nbsp; ' . esc_attr__( 'Da', 'smartbill-woocommerce' ) . '
            </td>
            <td class="company-name-error-trans">
                <input type="text" name="smartbill_plugin_options_settings[company_name_error_trans]" value="' . esc_attr( $custom_checkout_options['company_name_error_trans'] ) . '" placeholder="" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td class="custom-checkout-title">
                ' . esc_attr__( 'Nr. inregistrare Registrul Comertului', 'smartbill-woocommerce' ) . '
            </td>

            <td class="reg-com-trans">
                <input type="text" name="smartbill_plugin_options_settings[reg_com_trans]" value="' . esc_attr( $custom_checkout_options['reg_com_trans'] ) . '" placeholder="" autocomplete="off">
            </td>

            <td class="reg-com-visb">
                <select name="smartbill_plugin_options_settings[reg_com_vis]">';

		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $custom_checkout_options['reg_com_vis'] == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}

		echo '  </select>
            </td>
            <td class="reg-com-req">
                <select name="smartbill_plugin_options_settings[reg_com_req]">
            ';

		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $custom_checkout_options['reg_com_req'] == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}

		echo '</select>
            </td>
            <td class="reg-com-error-trans">
                <input type="text" name="smartbill_plugin_options_settings[reg_com_error_trans]" value="' . esc_attr( $custom_checkout_options['reg_com_error_trans'] ) . '" placeholder="" autocomplete="off">
            </td>
        </tr>
        </tbody>
        </table>';
	}

	/**
	 * Get display public invoice setting value
	 *
	 * @return boolean
	 */
	public function get_public_invoice() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['public_invoice'] ) ) {
			$selected_public_invoice = $options['public_invoice'];
		} else {
			$selected_public_invoice = false;
		}
		return $selected_public_invoice;
	}

	/**
	 * Display public invoice select field
	 *
	 * @return void
	 */
	public function display_public_invoice() {
		$display_public_invoice = $this->get_public_invoice();
		$options                = array(
			true  => esc_attr__( 'Da', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);

		echo '<select class="smartbill-settings-size smartbill-public-invoice" name="smartbill_plugin_options_settings[public_invoice]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $display_public_invoice == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}
		echo '</select>';
		echo '<p class="description">' . esc_attr__( 'Clientul va putea vedea factura emisa in SmartBill la accesarea comenzii din contul creat pe site.', 'smartbill-woocommerce' ) . '</p>';
	}

	/**
	 * Get View invoice text
	 *
	 * @return string
	 */
	public function get_view_invoice_text() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['view_invoice_text'] ) && ! empty( $options['view_invoice_text'] ) ) {
			$view_invoice_text = $options['view_invoice_text'];
		} else {
			$view_invoice_text = esc_attr__( 'Vezi factura', 'smartbill-woocommerce' );
		}
		return $view_invoice_text;
	}

	/**
	 * Display view invoice text
	 *
	 * @return void
	 */
	public function display_view_invoice_text() {
		$view_invoice_text = $this->get_view_invoice_text();
		echo '<input class="smartbill-settings-size" name="smartbill_plugin_options_settings[view_invoice_text]" value="' . esc_attr( $view_invoice_text ) . '">';
	}

	/**
	 * Get debugging mode
	 *
	 * @return boolean
	 */
	public function get_debugging_mode() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['debugging_mode'] ) ) {
			$selected_debugging_mode = $options['debugging_mode'];
		} else {
			$selected_debugging_mode = false;
		}
		return $selected_debugging_mode;
	}
	/**
	 * Display debugging mode
	 *
	 * @return void
	 */
	public function display_debugging_mode() {
		$display_debugging_mode = $this->get_debugging_mode();

		$options = array(
			true  => esc_attr__( 'Da', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);

		echo '<select class="smartbill-settings-size" name="smartbill_plugin_options_settings[debugging_mode]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $display_debugging_mode == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}
		echo '</select>';
	}

	/**
	 * Get use payment tax setting value
	 *
	 * @return boolean
	 */
	public function get_use_payment_tax() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['use_payment_tax'] ) ) {
			$use_payment_tax = $options['use_payment_tax'];
		} else {
			$use_payment_tax = 0;
		}
		return $use_payment_tax;

	}

	/**
	 * Display use payment tax select field
	 *
	 * @return void
	 */
	public function display_use_payment_tax() {
		$use_payment_tax = $this->get_use_payment_tax();

		echo '
    <select class="smartbill-settings-size" name="smartbill_plugin_options_settings[use_payment_tax]">
        <option value="1" ' . ( ! empty( $use_payment_tax ) ? 'selected' : '' ) . '>' . esc_attr__( 'Da', 'smartbill-woocommerce' ) . '</option>
        <option value="0" ' . ( empty( $use_payment_tax ) ? 'selected' : '' ) . '>' . esc_attr__( 'Nu', 'smartbill-woocommerce' ) . '</option>
    </select>';
		echo '<p class="description">' . esc_attr__( 'Daca vrei ca pe factura sa fie afisata mentiunea "TVA la incasare"', 'smartbill-woocommerce' ) . '</p>';
	}

	/**
	 * Validate form input
	 *
	 * @param array $input Form input values.
	 *
	 * @return array $input
	 */
	public function smartbill_plugin_options_settings_validate( $input ) {
		// check if company has changed.
		$options = get_option( 'smartbill_plugin_options_settings' );

		if ( isset( $input['included_vat'] ) && ! in_array( $input['included_vat'], array( 0, 1 ) ) ) {
			$input['included_vat'] = 0;
		}

		if ( isset( $input['shipping_vat'] ) && ! strlen( $input['shipping_vat'] ) ) {
			add_settings_error( 'smartbill_settings_shipping_vat', '', esc_attr__( 'Trebuie sa alegi o valoare pentru "Cota TVA transport"', 'smartbill-woocommerce' ), 'error' );
		} else {
			$input['shipping_vat'] = isset( $input['shipping_vat'] ) ? $input['shipping_vat'] : 0;
		}

		if ( ! in_array(
			$input['document_type'],
			array(
				Smartbill_Woocommerce_Settings::SMARTBILL_DOCUMENT_TYPE_INVOICE,
				Smartbill_Woocommerce_Settings::SMARTBILL_DOCUMENT_TYPE_ESTIMATE,
			)
		) ) {
			$input['document_type'] = Smartbill_Woocommerce_Settings::SMARTBILL_DOCUMENT_TYPE_INVOICE;
		}

		if ( isset( $input['estimate_series'] ) ) {
			if ( empty( $input['estimate_series'] ) ) {
				add_settings_error( 'smartbill_settings_document_series', '', esc_attr__( 'Trebuie sa alegi o valoare pentru "Serie implicita document emis"', 'smartbill-woocommerce' ), 'error' );
			}
		}

		if ( isset( $input['invoice_series'] ) ) {
			if ( empty( $input['invoice_series'] ) ) {
				add_settings_error( 'smartbill_settings_document_series', '', esc_attr__( 'Trebuie sa alegi o valoare pentru "Serie implicita document emis"', 'smartbill-woocommerce' ), 'error' );
			}
		}

		if ( empty( $input['um'] ) || 'no_value' == $input['um'] ) {
			add_settings_error( 'smartbill_settings_um', '', esc_attr__( 'Este necesara selectarea unei unitati de masura.', 'smartbill-woocommerce' ), 'error' );
		}

		$currencies         = Smartbill_Woocommerce_Settings::get_currencies();
		$currencies_symbols = array_map(
			function( $currency ) {
				return $currency['value'];
			},
			$currencies
		);
		if ( ! in_array( $input['billing_currency'], $currencies_symbols ) ) {
			$input['billing_currency'] = '';
		}

		if ( ! in_array( $input['include_shipping'], array( 0, 1 ) ) ) {
			$input['include_shipping'] = 0;
		}

		if ( ! in_array( $input['save_client'], array( 0, 1 ) ) ) {
			$input['save_client'] = 0;
		}

		if ( ! in_array( $input['save_product'], array( 0, 1 ) ) ) {
			$input['save_product'] = 0;
		}

		if ( '1' == $input['send_mail_with_document'] ) {
			$show_error             = false;
			$input['send_mail_cc']  = trim( $input['send_mail_cc'] );
			$input['send_mail_bcc'] = trim( $input['send_mail_bcc'] );
			if ( ! empty( $input['send_mail_cc'] ) ) {
				if ( ! filter_var( $input['send_mail_cc'], FILTER_VALIDATE_EMAIL ) ) {
					$show_error = true;
				}
			}
			if ( ! empty( $input['send_mail_bcc'] ) ) {
				if ( ! filter_var( $input['send_mail_bcc'], FILTER_VALIDATE_EMAIL ) ) {
					$show_error = true;
				}
			}
			if ( true == $show_error ) {
				add_settings_error( 'smartbill_settings_send_mail', '', esc_attr__( 'Email-ul introdus in sectiunea Setari emitere documente nu este valid.', 'smartbill-woocommerce' ), 'error' );
			}
		}

		if ( empty( $input['billing_currency'] ) || preg_match( '/\ /', $input['billing_currency'] ) ) {
			$input['billing_currency'] = get_woocommerce_currency() . ' ';
		}

		add_settings_error( 'smartbill_settings_saved', '', esc_attr__( 'Setarile au fost salvate.', 'smartbill-woocommerce' ), 'updated' );
		return $input;
	}

	/**
	 * Ajax call function for downloading manual stock history.
	 *
	 * @throws Exception Missing file.
	 *
	 * Returns JSON array.
	 */
	public function smartbill_woocommerce_download_manual_stock_history(){
		check_ajax_referer( 'smartbill_nonce', 'security' );
		$return = array();
		$error  = '';
		if ( is_admin() ) {
			try {
				$file = __DIR__ . DIRECTORY_SEPARATOR . 'Istoric sincronizare manuala SmartBill.csv';
				if ( ! file_exists( $file ) ) {
					throw new Exception( esc_attr__( 'Fisier inexistent!', 'smartbill-woocommerce' ) );
				}

				$zip_name = 'smartbill_istoric_sincronizare_manuala_stocuri';
				$zip      = new ZipArchive();

				$zip_path = wp_upload_dir()['path'] . DIRECTORY_SEPARATOR . $zip_name . '.zip';
				$zip->open( $zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE );
				$zip->addFile( $file, 'Istoric sincronizare manuala SmartBill.csv' );
				$zip->close();

				$return['data'] = wp_upload_dir()['url'] . DIRECTORY_SEPARATOR . $zip_name . '.zip';
			} catch ( Exception $e ) {
				$error = $e->getMessage();
			}
		}

		$return['error'] = $error;
		echo json_encode( $return );
		die();
	}


	/**
	 * Ajax call function for manually synchronizing stock from smartbill
	 *
	 * @throws Exception Missing file.
	 *
	 * Returns JSON array.
	 */
	public function smartbill_woocommerce_manually_sync_stock(){
		check_ajax_referer( 'smartbill_nonce', 'security' );
		$return = array();
		$error  = '';
		if ( is_admin() ) {
			try {
				if ( isset( $_POST['body'] ) ) {
					$warehouse = sanitize_text_field( wp_unslash( $_POST['body']) );
				}else{
					throw new Exception( esc_attr__( 'Lipsa gestiune', 'smartbill-woocommerce' ));
				}

				$now = new DateTime('now');
				$now->setTimezone(new DateTimeZone(wp_timezone_string()));
				$last_date=get_option('smartbill-last-sync');
				if(!empty($last_date)){
					$last_date=unserialize($last_date);
					if(5 > (int)$now->diff($last_date)->i){
						$temp_date=$last_date;
						$temp_date->modify("+5 minutes");
						
						throw new Exception( sprintf( esc_attr__( 'Preluarea manuala a stocurilor se poate face doar o data la 5 minute. Urmatoarea preluare va fi posibila la ora %s.', 'smartbill-woocommerce' ),$temp_date->format('H:i')));
					}
				}

				$login_options = get_option( 'smartbill_plugin_options' );
				if ( empty( $login_options['username'] ) || empty( $login_options['password'] ) ) {
					throw new \Exception( esc_attr__( 'Este necesar sa furnizati un utilizator si o parola valide.', 'smartbill-woocommerce' ) );
				}
				
				$client = new SmartBill_Cloud_REST_Client( trim( $login_options['username'] ), trim( $login_options['password'] ) );
				
				if(empty($last_date) || 4 < (int)$now->diff($last_date)->i){
					$stocks = $client->get_ware_stock( $login_options['vat_code'], $warehouse);
				}

				if(empty($stocks)){
					throw new \Exception( esc_attr__( 'Eroare sintaxa. Verifica valididatea JSON-ului primit.', 'smartbill-woocommerce' ) );
				}
				
				if(isset( $stocks['errorText'] ) && !empty( $stocks['errorText'] )){
					throw new \Exception( $stocks['errorText'] );
				}

				if ( isset( $stocks['products'] ) && 0 < count($stocks['products']) ) {
					$updated_products=0;
					
					$file = fopen(__DIR__ . DIRECTORY_SEPARATOR . 'Istoric sincronizare manuala SmartBill.csv', 'w');
					fputcsv($file, [ 
						esc_attr__( 'Cod produs SmartBill' , 'smartbill-woocommerce' ),
						esc_attr__( 'Denumire produs SmartBill' , 'smartbill-woocommerce' ),
						esc_attr__( 'Denumire produs WooCommerce' , 'smartbill-woocommerce' ),
						esc_attr__( 'ID produs WooCommerce' , 'smartbill-woocommerce' ),
						esc_attr__( 'Stoc nou' , 'smartbill-woocommerce' )
					]);
				
					//Update time at the start and end of the sync.
					update_option('smartbill-last-sync',serialize($now));

					foreach($stocks['products'] as $product){
						$product = array_values($product);
						$product = new Smartbill_Product(...$product);
						if( true === $product->sync_quantity() ){
							$date=new DateTime('now');
							$date->setTimezone(new DateTimeZone(wp_timezone_string()));
							update_option('smartbill_stock_update',serialize($date));
							$updated_products++;
						}
						fputcsv($file, $product->to_arr());
					}
					$product=null;
					fclose($file);

				}else{
					$error_message= sprintf(esc_attr__( 'Nu exista produse pe Gestiunea %s. Acceseaza SmartBill Cloud, adauga produse in gestiune apoi incearca din nou preluarea stocurilor.', 'smartbill-woocommerce' ),$warehouse);
					throw new \Exception($error_message);
				}
				
				//Update time at the start and end of the sync.
				update_option('smartbill-last-sync',serialize($now));

				if( count($stocks['products']) == $updated_products ){
					$return['icon'] = 'success';
					$return['data'] = sprintf(esc_attr__('A fost actualizat stocul pentru %s produs.', 'smartbill-woocommerce'), $updated_products);
					if(1 < $updated_products){
						$return['data'] = sprintf(esc_attr__('A fost actualizat stocul pentru %s produse.', 'smartbill-woocommerce'), $updated_products);
					}
				}else{
					$return['icon'] = 'warning';
					$return['data'] = sprintf(esc_attr__('A fost actualizat stocul pentru %s produs. Descarca istoricul pentru consultarea erorilor.', 'smartbill-woocommerce'), $updated_products);
	
					if( 1 < $updated_products ){
						$return['data'] = sprintf(esc_attr__('A fost actualizat stocul pentru %s produse.  Descarca istoricul pentru consultarea erorilor.', 'smartbill-woocommerce'), $updated_products);
					}
					if( 0 == $updated_products ){
						$return['data'] = sprintf(esc_attr__('Nu au fost actualizate stocurile. Descarca istoricul pentru consultarea erorilor.', 'smartbill-woocommerce'), $updated_products);
						$return['icon'] = 'error';
					}
				}
				
			} catch ( Exception $e ) {
				$error = $e->getMessage();
			}
		}

		$return['error'] = $error;
		
		echo json_encode( $return );
		die();
	}

	/**
	 * Ajax call function for downloading stock history
	 *
	 * @throws Exception Missing file.
	 *
	 * Returns JSON array.
	 */
	public function smartbill_woocommerce_download_stock_history() {
		check_ajax_referer( 'smartbill_nonce', 'security' );
		$return = array();
		$error  = '';
		if ( is_admin() ) {
			try {
				$file = __DIR__ . DIRECTORY_SEPARATOR . 'smartbill_sincronizare_stocuri.log';
				if ( ! file_exists( $file ) ) {
					throw new Exception( esc_attr__( 'Fisier inexistent!', 'smartbill-woocommerce' ) );
				}

				$zip_name = 'smartbill_sincronizare_stocuri';
				$zip      = new ZipArchive();

				$zip_path = wp_upload_dir()['path'] . DIRECTORY_SEPARATOR . $zip_name . '.zip';
				$zip->open( $zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE );
				$zip->addFile( $file, 'smartbill_sincronizare_stocuri.log' );
				$zip->close();

				$return['data'] = wp_upload_dir()['url'] . DIRECTORY_SEPARATOR . $zip_name . '.zip';
			} catch ( Exception $e ) {
				$error = $e->getMessage();
			}
		}

		$return['error'] = $error;
		echo json_encode( $return );
		die();
	}

	/**
	 * Update smartbill related data.
	 *
	 * @throws \Exception Invalid credentials.
	 *
	 * Returns JSON array.
	 */
	public function smartbill_woocommerce_sync_settings() {
		check_ajax_referer( 'smartbill_nonce', 'security' );
		$return  = array();
		$message = esc_attr__( 'Au fost actualizate setarile modulului: gestiunile, unitatile de masura, seriile si cotele de TVA.' );
		$error   = '';
		if ( is_admin() ) {
			// force call measuring units, taxes, stock, invoice and estimate series (make api call).
			$series = self::get_series( '', true );
			if ( false == $series ) {
				$error .= esc_attr__( 'Seriile nu au fost actualizate!' );
			}

			$stocks = self::get_stocks( true );
			if ( false == $stocks ) {
					$error .= esc_attr__( 'Stocurile nu au fost actualizate!' ) . '</br>';
			}

			$measuring_units = self::get_measuring_units( true );
			if ( false == $measuring_units ) {
				$error .= esc_attr__( 'Unitatile de masura nu au fost actualizate!' ) . '</br>';
			}

			$login_options = get_option( 'smartbill_plugin_options' );
			try {
				if ( empty( $login_options['username'] ) || empty( $login_options['password'] ) ) {
					throw new \Exception( esc_attr__( 'Este necesar sa furnizati un utilizator si o parola valide.', 'smartbill-woocommerce' ) );
				}
				$client = new SmartBill_Cloud_REST_Client( trim( $login_options['username'] ), trim( $login_options['password'] ) );
				$taxes  = $client->get_taxes( $login_options['vat_code'] );
				update_option( 'smartbill_s_taxes', $taxes );
				if ( false == $taxes ) {
					$error .= esc_attr__( 'Taxele nu au fost actualizate!' ) . '</br>';
				}
			} catch ( \Exception $e ) {
				if ( esc_attr__( 'Firma este neplatitoare de tva.', 'smartbill-woocommerce' ) != $e->getMessage() ) {
					$error .= esc_attr__( 'Va rugam sa verificati conexiunea intre WooCommerce si SmartBill Cloud.', 'smartbill-woocommerce' );
				}
			}
		} else {
			$error .= esc_attr__( 'Permisiuni insuficiente' );
		}
		$return['error']   = $error;
		$return['message'] = $message;
		echo wp_json_encode( $return );
		die();
	}


	/**
	 * The function returns the saved measuring units
	 *
	 * @param boolean $force_call if true calls smartbill api and updates measuring units data.
	 *
	 * @throws \Exception Invalid credentials.
	 * @throws Exception Company does not pay vat.
	 *
	 * @return false|array $final_values
	 */
	public static function get_measuring_units( $force_call = false ) {
		$login_options = get_option( 'smartbill_plugin_options' );
		$vat_code      = $login_options['vat_code'];
		$final_values  = get_option( 'smartbill_s_um' );
		if ( $force_call ) {
			try {
				if ( empty( $login_options['username'] ) || empty( $login_options['password'] ) ) {
					throw new \Exception( esc_attr__( 'Este necesar sa furnizati un utilizator si o parola valide.', 'smartbill-woocommerce' ) );
				}
				$client = new SmartBill_Cloud_REST_Client( $login_options['username'], $login_options['password'] );
				$mu     = $client->get_measuring_units( $vat_code );
				if ( is_array( $mu ) && isset( $mu['mu'] ) ) {
					update_option( 'smartbill_s_um', $mu['mu'] );
					return $mu['mu'];
				} else {
					delete_option( 'smartbill_s_um' );
					throw new Exception( esc_attr__( 'Firma este neplatitoare de TVA sau nu au fost setate valori de TVA in SmartBill Cloud', 'smartbill-woocommerce' ) );
				}
			} catch ( \Exception $e ) {
				$error = '';
				if ( trim( $e->getMessage() ) != '' ) {
					$error .= $e->getMessage();
				} else {
					$error .= esc_attr__( 'Va rugam sa verificati conexiunea intre WooCommerce si SmartBill Cloud.' );
				}
				add_settings_error( 'smartbill_settings_company', '', $error, 'error' );
				return false;
			}
		} else {
			return $final_values;
		}

	}
	/**
	 * The function returns the saved stock
	 *
	 * @param boolean $force_call if true calls smartbill api and updates stock data.
	 *
	 * @throws \Exception Invalid credentials.
	 *
	 * @return false|array
	 */
	public static function get_stocks( $force_call = false ) {
		$login_options = get_option( 'smartbill_plugin_options' );
		$vat_code      = $login_options['vat_code'];
		$final_values  = get_option( 'smartbill_stocks' );
		if ( $force_call ) {
			try {
				if ( empty( $login_options['username'] ) || empty( $login_options['password'] ) ) {
					throw new \Exception( esc_attr__( 'Este necesar sa furnizati un utilizator si o parola valide.', 'smartbill-woocommerce' ) );
				}
				$client = new SmartBill_Cloud_REST_Client( $login_options['username'], $login_options['password'] );
				$stocks = $client->get_stock( $vat_code );
				update_option( 'smartbill_stocks', $stocks['warehouses'] );
				return $stocks['warehouses'];
			} catch ( \Exception $e ) {
				$error = '';
				if ( trim( $e->getMessage() ) != '' ) {
					$error .= $e->getMessage();
				} else {
					$error .= esc_attr__( 'Va rugam sa verificati conexiunea intre WooCommerce si SmartBill Cloud.' );
				}
				add_settings_error( 'smartbill_settings_company', '', $error, 'error' );
				return false;
			}
		} else {
			return $final_values;
		}
	}


	/**
	 * The function returns the saved series
	 *
	 * @param string  $type If empty returns both estimate and invoice series. f for invoice or p for estimate.
	 * @param boolean $force_call If true calls smartbill api and updates series data.
	 *
	 * @throws \Exception Error message.
	 *
	 * @return null|array $finalValues
	 */
	public static function get_series( $type = '', $force_call = false ) {
		$login_options = get_option( 'smartbill_plugin_options' );
		$vat_code      = $login_options['vat_code'];
		// get data from WordPress.
		$invoice_values  = get_option( 'smartbill_invoice_series' );
		$estimate_values = get_option( 'smartbill_estimate_series' );

		if ( $force_call ) {
			try {
				if ( empty( $login_options['username'] ) || empty( $login_options['password'] ) ) {
					throw new \Exception( esc_attr__( 'Este necesar sa furnizati un utilizator si o parola valide.', 'smartbill-woocommerce' ) );
				}
				$connector = new SmartBill_Cloud_REST_Client( $login_options['username'], $login_options['password'] );
				$series    = $connector->get_document_series( $vat_code );

				$save_inv_values = array();
				$save_est_values = array();
				if ( isset( $series['list'] ) && is_array( $series['list'] ) ) {
					foreach ( $series['list'] as $ser ) {
						if ( 'f' == $ser['type'] ) {
							$save_inv_values[ $ser['name'] ] = $ser['name'];
						}
						if ( 'p' == $ser['type'] ) {
							$save_est_values[ $ser['name'] ] = $ser['name'];
						}
					}
				} else {
					throw new \Exception( esc_attr__( 'Raspuns invalid primit de la SmartBill Cloud la primirea seriilor pentru facturi.', 'smartbill-woocommerce' ) );
				}

				// save data to WordPress.
				update_option( 'smartbill_invoice_series', array( $vat_code => $save_inv_values ) );
				update_option( 'smartbill_estimate_series', array( $vat_code => $save_est_values ) );

				if ( 'f' == $type ) {
					return $save_inv_values;
				}
				if ( 'p' == $type ) {
					return $save_est_values;
				}
				if ( '' == $type ) {
					return array(
						'f' => $save_inv_values,
						'p' => $save_est_values,
					);
				}
			} catch ( \Exception $e ) {
				$error = '';
				if ( trim( '' != $e->getMessage() ) ) {
					$error .= $e->getMessage();
				} else {
					$error .= esc_attr__( 'Va rugam sa verificati conexiunea intre WooCommerce si SmartBill Cloud.' );
				}
				add_settings_error( 'smartbill_settings_company', '', $error, 'error' );
				return false;
			}
		} else {
			if ( 'f' == $type ) {
				if(isset($invoice_values[ $vat_code ])){
					return $invoice_values[ $vat_code ];
				}
				return false;
			}
			if ( 'p' == $type ) {
				if(isset($invoice_values[ $vat_code ])){
					return $estimate_values[ $vat_code ];
				}
				return false;
			}
			if ( '' == $type ) {
				if(isset($invoice_values[ $vat_code ]) && isset($invoice_values[ $vat_code ])){
					return array(
						'f' => $invoice_values[ $vat_code ],
						'p' => $estimate_values[ $vat_code ],
					);
				}
				return false;
			}
		}
	}

	/**
	 * Get fiscal code for company setting value
	 *
	 * @return boolean
	 */
	public function get_fiscal_code_for_company() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['fiscal_code_for_company'] ) ) {
			$selected_fiscal_code_for_company = $options['fiscal_code_for_company'];
		} else {
			$selected_fiscal_code_for_company = false;
		}
		return $selected_fiscal_code_for_company;
	}

	/**
	 * Dispaly fiscal code for company select field
	 *
	 * @return void
	 */
	public function display_fiscal_code_for_company() {
		$fiscal_code_for_company = $this->get_fiscal_code_for_company();

		$options = array(
			true  => esc_attr__( 'Da', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);

		echo '<select class="smartbill-settings-size" name="smartbill_plugin_options_settings[fiscal_code_for_company]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $fiscal_code_for_company == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}
		echo '</select>';

	}


	/**
	 * Get woocommerce number for company
	 *
	 * @return boolean
	 */
	public function get_commerce_number_for_company() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['commerce_number_for_company'] ) ) {
			$selected_commerce_number_for_company = $options['commerce_number_for_company'];
		} else {
			$selected_commerce_number_for_company = false;
		}
		return $selected_commerce_number_for_company;
	}

	/**
	 * Dispaly woocommerce number for company select field
	 *
	 * @return void
	 */
	public function display_commerce_number_for_company() {
		$commerce_number_for_company = $this->get_commerce_number_for_company();

		$options = array(
			true  => esc_attr__( 'Da', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);

		echo '<select class="smartbill-settings-size" name="smartbill_plugin_options_settings[commerce_number_for_company]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $commerce_number_for_company == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}
		echo '</select>';
	}

	/**
	 * Get iban for company setting vlaue
	 *
	 * @return boolean
	 */
	public function get_iban_for_company() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['iban_for_company'] ) ) {
			$selected_iban_for_company = $options['iban_for_company'];
		} else {
			$selected_iban_for_company = false;
		}
		return $selected_iban_for_company;
	}

	/**
	 * Display iban for company select field
	 *
	 * @return void
	 */
	public function display_iban_for_company() {
		$iban_for_company = $this->get_iban_for_company();

		$options = array(
			true  => esc_attr__( 'Da', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);

		echo '<select class="smartbill-settings-size" name="smartbill_plugin_options_settings[iban_for_company]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $iban_for_company == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}
		echo '</select>';

	}

	/**
	 * Get bank for company setting value
	 *
	 * @return boolean
	 */
	public function get_bank_for_company() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['bank_for_company'] ) ) {
			$selected_bank_for_company = $options['bank_for_company'];
		} else {
			$selected_bank_for_company = false;
		}
		return $selected_bank_for_company;
	}

	/**
	 * Display bank for company select field
	 *
	 * @return void
	 */
	public function display_bank_for_company() {
		$bank_for_company = $this->get_bank_for_company();

		$options = array(
			true  => esc_attr__( 'Da', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);

		echo '<select class="smartbill-settings-size" name="smartbill_plugin_options_settings[bank_for_company]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $bank_for_company == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}
		echo '</select>';
	}


	/**
	 * Get send mail with document setting value
	 *
	 * @return boolean
	 */
	public function get_send_mail_with_document() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['send_mail_with_document'] ) ) {
			$selected_send_mail_with_document = $options['send_mail_with_document'];
		} else {
			$selected_send_mail_with_document = false;
		}
		return $selected_send_mail_with_document;
	}

	/**
	 * Display send mail with document select field
	 *
	 * @return void
	 */
	public function display_send_mail_with_document() {
		$send_mail_with_document = $this->get_send_mail_with_document();

		$options = array(
			true  => esc_attr__( 'Da', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);
		echo '<select class="smartbill-settings-size smartbill_display_send_mail_with_document" name="smartbill_plugin_options_settings[send_mail_with_document]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $send_mail_with_document == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}
		echo '</select>';
		echo '<div class="description">
		<p>' . esc_attr__( 'Documentul va fi trimis pe email clientului automat dupa facturarea comenzii', 'smartbill-woocommerce' ) . '</p> 
		<p>'. sprintf( esc_attr__( 'Este necesar ca in %1$s SmartBill > Configurare > Email%2$s  sa fie configurat propriul server de email', 'smartbill-woocommerce' ),'<a href="https://cloud.smartbill.ro/core/configurare_mailuri/" target="_blank">','</a>').'</p>
		</div>';
	}

	/**
	 * Get cc setting value
	 *
	 * @return false|string
	 */
	public function get_send_mail_cc() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['send_mail_cc'] ) ) {
			$selected_send_mail_cc = $options['send_mail_cc'];
		} else {
			$selected_send_mail_cc = false;
		}
		return $selected_send_mail_cc;
	}

	/**
	 * Display cc input field
	 *
	 * @return void
	 */
	public function display_send_mail_cc() {
		$display_send_mail_cc = $this->get_send_mail_cc();
		echo '<input class="smartbill-settings-size" name="smartbill_plugin_options_settings[send_mail_cc]" value="' . esc_attr( $display_send_mail_cc ) . '">';
	}

	/**
	 * Get ncc setting value
	 *
	 * @return false|string
	 */
	public function get_send_mail_bcc() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['send_mail_bcc'] ) ) {
			$selected_send_mail_bcc = $options['send_mail_bcc'];
		} else {
			$selected_send_mail_bcc = false;
		}
		return $selected_send_mail_bcc;
	}

	/**
	 * Display custom message
	 *
	 * @return void
	 */
	public function display_send_mail_text() {
		echo '<p id="smartbill-mail-text"' . esc_attr__( 'Subiectul si mesajul email-ului trimis clientului este cel configurat in SmartBill > Configurare> Email. Factura in format PDF va fi atasata email-ului.', 'smartbill-woocommerce' ) . '</p>';
	}

	/**
	 * Display bcc input field
	 *
	 * @return void
	 */
	public function display_send_mail_bcc() {
		$display_send_mail_bcc = $this->get_send_mail_bcc();
		echo '<input class="smartbill-settings-size" name="smartbill_plugin_options_settings[send_mail_bcc]" value="' . esc_attr( $display_send_mail_bcc ) . '">';
	}

	/**
	 * Get coupon text setting vlaue
	 *
	 * @return string
	 */
	public function get_coupon_text() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['coupon_text'] ) && ! empty( $options['coupon_text'] ) ) {
			$show_order_mention = $options['coupon_text'];
		} else {
			$show_order_mention = esc_attr__( 'Discount (#nume_cupon#)', 'smartbill-woocommerce' );
		}
		return $show_order_mention;
	}

	/**
	 * Display coupon text input field
	 *
	 * @return void
	 */
	public function display_coupon_text() {
		$coupon_text = $this->get_coupon_text();
		echo '<input class="smartbill-settings-size" name="smartbill_plugin_options_settings[coupon_text]" value="' . esc_attr( $coupon_text ) . '">';
		echo '<p class="description">' . esc_attr__( 'Pentru afisarea numelui cuponului aplicat poti introduce #nume_cupon#.', 'smartbill-woocommerce' ) . '</p>';
	}

	/**
	 * Get show discount on document setting vlaue
	 *
	 * @return boolean
	 */
	public function get_show_discount_on_document() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['show_discount_on_document'] ) ) {
			$selected_show_discount_on_document = $options['show_discount_on_document'];
		} else {
			$selected_show_discount_on_document = false;
		}
		return $selected_show_discount_on_document;
	}

	/**
	 * Display show discount on document select field
	 *
	 * @return void
	 */
	public function display_show_discount_on_document() {
		$show_discount_on_document = $this->get_show_discount_on_document();

		$options = array(
			true  => esc_attr__( 'Da', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);

		echo '<select class="smartbill-settings-size smartbill-discount-name" name="smartbill_plugin_options_settings[show_discount_on_document]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $show_discount_on_document == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}
		echo '</select>';

	}

	/**
	 * Get dicount text setting value
	 *
	 * @return string
	 */
	public function get_discount_text() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['discount_text'] ) && ! empty( $options['discount_text'] ) ) {
			$discount_text = $options['discount_text'];
		} else {
			$discount_text = 'Discount #nume_produs# - Cantitate: #cantitate_produse#';
		}
		return $discount_text;
	}

	/**
	 * Display dicount text input field
	 *
	 * @return void
	 */
	public function display_discount_text() {
		$discount_text = $this->get_discount_text();
		echo '<input class="smartbill-settings-size" name="smartbill_plugin_options_settings[discount_text]" value="' . esc_attr( $discount_text ) . '">';
		echo '<p class="description">' . esc_attr__( 'Poti utiliza variabile precum #nume_produs# si #cantitate_produse#.', 'smartbill-woocommerce' ) . '</p>';

	}

	/**
	 * Function that returns the issuing person's cnp
	 *
	 * @return string The cnp of the person issuing the document
	 */
	public function get_issuer_cnp() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['issuer_cnp'] ) && ! empty( $options['issuer_cnp'] ) ) {
			$issuer_cnp = trim($options['issuer_cnp']);
		} else {
			$issuer_cnp = '';
		}
		return $issuer_cnp;
	}

	/**
	 * Function that display's the CNP intocmitor setting
	 *
	 * @return void
	 */
	public function display_issuer_cnp() {
		$issuer_cnp = $this->get_issuer_cnp();
		echo '<input class="smartbill-settings-size" name="smartbill_plugin_options_settings[issuer_cnp]" value="' . esc_attr( $issuer_cnp ) . '">';
	}

	/**
	 * Retrieve delegate_data value.
	 *
	 * @return boolean
	 */
	public function get_add_delegate_data() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['add_delegate_data'] ) && ! empty( $options['add_delegate_data'] ) ) {
			$add_delegate_data = $options['add_delegate_data'];
		} else {
			$add_delegate_data = 0;
		}
		return $add_delegate_data;
	}

	/**
	 * Function that display's the "Adauga datele intocmitorului/delegatului pe factura" input.
	 *
	 * @return void
	 */
	public function display_add_delegate_data() {
		$show_delegate_data = $this->get_add_delegate_data();

		$options = array(
			true  => esc_attr__( 'Da', 'smartbill-woocommerce' ),
			false => esc_attr__( 'Nu', 'smartbill-woocommerce' ),
		);

		echo '<select class="smartbill-settings-size smartbill-delegate-data" name="smartbill_plugin_options_settings[add_delegate_data]">';
		foreach ( $options as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . ( $show_delegate_data == $status ? 'selected' : '' ) . '>' . esc_attr( $label ) . '</option>';
		}
		echo '</select>';
	}

	/**
	 * Function that retrieves the delegate's name value
	 *
	 * @return string
	 */
	public function get_delegate_name() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['delegate_name'] ) && ! empty( $options['delegate_name'] ) ) {
			$delegate_name = trim($options['delegate_name']);
		} else {
			$delegate_name = '';
		}
		return $delegate_name;
	}

	/**
	 * Function that display's the Name of the delegate input.
	 *
	 * @return void
	 */
	public function display_delegate_name() {
		$delegate_name = $this->get_delegate_name();
		echo '<input class="smartbill-settings-size" name="smartbill_plugin_options_settings[delegate_name]" value="' . esc_attr( $delegate_name ) . '">';
	}

	/**
	 * Function that retrieves the delegate's buletin value
	 *
	 * @return string
	 */
	public function get_delegate_bulletin() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['delegate_bulletin'] ) && ! empty( $options['delegate_bulletin'] ) ) {
			$delegate_bulletin = trim($options['delegate_bulletin']);
		} else {
			$delegate_bulletin = '';
		}
		return $delegate_bulletin;
	}

	/**
	 * Function that display's the "Buletin" input.
	 *
	 * @return void
	 */
	public function display_delegate_bulletin() {
		$delegate_bulletin = $this->get_delegate_bulletin();
		echo '<input class="smartbill-settings-size" name="smartbill_plugin_options_settings[delegate_bulletin]" value="' . esc_attr( $delegate_bulletin ) . '">';
	}

	/**
	 * Function that retrieves the "delegate auto" value
	 *
	 * @return string
	 */
	public function get_delegate_auto() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['delegate_auto'] ) && ! empty( $options['delegate_auto'] ) ) {
			$delegate_auto = trim($options['delegate_auto']);
		} else {
			$delegate_auto = '';
		}
		return $delegate_auto;
	}

	/**
	 * Function that display's the "Auto" input.
	 *
	 * @return void
	 */
	public function display_delegate_auto() {
		$delegate_auto = $this->get_delegate_auto();
		echo '<input class="smartbill-settings-size" name="smartbill_plugin_options_settings[delegate_auto]" value="' . esc_attr( $delegate_auto ) . '">';
	}

	/**
	 * Function that returns the issuing person's name
	 *
	 * @return string The name of the person issuing the document
	 */
	public function get_issuer_name() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['issuer_name'] ) && ! empty( $options['issuer_name'] ) ) {
			$issuer_name = trim($options['issuer_name']);
		} else {
			$issuer_name = '';
		}
		return $issuer_name;
	}

	/**
	 * Function that display's the Nume intocmitor setting
	 *
	 * @return void
	 */
	public function display_issuer_name() {
		$issuer_name = $this->get_issuer_name();
		echo '<input class="smartbill-settings-size" name="smartbill_plugin_options_settings[issuer_name]" value="' . esc_attr( $issuer_name ) . '">';
	}

	/**
	 * Get order mention text setting value.
	 *
	 * @return string
	 */
	public function get_show_order_mention() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['show_order_mention'] ) ) {
			$show_order_mention = $options['show_order_mention'];
		} else {
			$show_order_mention = 'Comanda online #nr_comanda_online#';
		}
		return $show_order_mention;
	}

	/**
	 * Function that display's the order mention textarea field.
	 *
	 * @return void
	 */
	public function display_show_order_mention() {
		$show_order_mention = $this->get_show_order_mention();
		echo '<textarea class="smartbill-settings-size" name="smartbill_plugin_options_settings[show_order_mention]">' . esc_attr( $show_order_mention ) . '</textarea>';
		echo '<p class="description">' . esc_attr__( 'Mentinunile vor fi vizibile pe factura. Personalizeaza mentiunile cu date preluate automat de pe comanda folosind termeni variabili: #nr_comanda_online#, #tip_plata#.', 'smartbill-woocommerce' ) . '</p>';
	}

	/**
	 * Get order observations setting value.
	 *
	 * @return string
	 */
	public function get_show_order_obs() {
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['show_order_obs'] ) ) {
			$show_order_obs = $options['show_order_obs'];
		} else {
			$show_order_obs = '';
		}
		return $show_order_obs;
	}

	/**
	 * Function that display's the order observations textarea field.
	 *
	 * @return void
	 */
	public function display_show_order_obs() {
		$show_order_obs = $this->get_show_order_obs();
		echo '<textarea class="smartbill-settings-size" name="smartbill_plugin_options_settings[show_order_obs]">' . esc_attr( $show_order_obs ) . '</textarea>';
		echo '<p class="description">' . esc_attr__( 'Observatiile vor fi vizibile doar pe raportul de facturi din SmartBill. Pentru a afisa numarul comenzii poti introduce #nr_comanda_online#.', 'smartbill-woocommerce' ) . '</p>';
	}

}

//phpcs:ignore.
