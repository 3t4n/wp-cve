<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Ebanx {

	public $user_id = 0;
	private $fields_added = false;
	private $configs = [];

	public function __construct() {
		add_filter( 'wfacp_form_section', [ $this, 'sections' ] );
		add_action( 'wfacp_internal_css', [ $this, 'css' ] );
	}

	public function sections( $sections ) {
		if ( $this->fields_added ) {
			return $sections;
		}
		if ( count( $sections ) == 0 ) {
			return $sections;
		}
		if ( ! class_exists( 'WC_EBANX_Gateway' ) || ! class_exists( 'WC_EBANX_Global_Gateway' ) ) {
			return $sections;
		}

		if ( isset( $sections['fields']['wfacp_end_divider_billing'] ) ) {
			try {
				$payments_ids    = WC()->payment_gateways()->get_payment_gateway_ids();
				$is_enable_ebanx = false;
				foreach ( $payments_ids as $ids ) {
					if ( false !== strpos( $ids, 'ebanx' ) ) {
						$is_enable_ebanx = true;
						break;
					}
				}
				if ( $is_enable_ebanx ) {
					$this->configs = new WC_EBANX_Global_Gateway();

					$disable_own_fields = isset( $this->configs->settings['checkout_manager_enabled'] ) && 'yes' === $this->configs->settings['checkout_manager_enabled'];

					if ( $disable_own_fields ) {
						return $sections;
					}

					$this->fields_added = true;

					$end_adress_found      = false;
					$end_adress_closser    = $sections['fields']['wfacp_end_divider_billing'];
					$after_address_element = [];
					$is_hidedable          = false;
					foreach ( $sections['fields'] as $index => $field ) {
						if ( $end_adress_found ) {
							$after_address_element[] = $field;
							unset( $sections['fields'][ $index ] );
						}
						if ( isset( $field['class'] ) && in_array( 'wfacp_billing_fields', $field['class'] ) ) {
							$is_hidedable = true;
						}
						if ( 'wfacp_end_divider_billing' === $index ) {
							unset( $sections['fields'][ $index ] );
							$end_adress_found = true;
						}
					}

					if ( false == $end_adress_found ) {
						return $sections;
					}

					$this->user_id = get_current_user_id();
					$cpf           = get_user_meta( $this->user_id, '_ebanx_billing_brazil_document', true );
					$cnpj          = get_user_meta( $this->user_id, '_ebanx_billing_brazil_cnpj', true );
					$rut           = get_user_meta( $this->user_id, '_ebanx_billing_chile_document', true );
					$dni           = get_user_meta( $this->user_id, '_ebanx_billing_colombia_document', true );
					$dni_pe        = get_user_meta( $this->user_id, '_ebanx_billing_peru_document', true );
					$cdi           = get_user_meta( $this->user_id, '_ebanx_billing_argentina_document', true );

					$fields_options = array();
					if ( isset( $this->configs->settings['brazil_taxes_options'] ) && is_array( $this->configs->settings['brazil_taxes_options'] ) ) {
						$fields_options = $this->configs->settings['brazil_taxes_options'];
					}

					$ebanx_billing_brazil_person_type = array(
						'type'    => 'select',
						'label'   => __( 'Select an option', 'woocommerce-gateway-ebanx' ),
						'default' => 'cpf',
						'id'      => 'ebanx_billing_brazil_person_type',
						'class'   => array( 'ebanx_billing_brazil_selector', 'ebanx-select-field', 'wfacp_ebanx_field' ),
						'options' => array(
							'cpf'  => __( 'CPF - Individuals', 'woocommerce-gateway-ebanx' ),
							'cnpj' => __( 'CNPJ - Companies', 'woocommerce-gateway-ebanx' ),
						),
					);

					$ebanx_billing_argentina_document_type = array(
						'type'    => 'select',
						'label'   => __( 'Select a document type', 'woocommerce-gateway-ebanx' ),
						'default' => 'ARG_CUIT',
						'id'      => 'ebanx_billing_argentina_document_type',
						'class'   => array( 'ebanx_billing_argentina_selector', 'ebanx-select-field', 'address-field', 'wfacp_ebanx_field' ),
						'options' => array(
							'ARG_CUIT' => __( 'CUIT', 'woocommerce-gateway-ebanx' ),
							'ARG_CUIL' => __( 'CUIL', 'woocommerce-gateway-ebanx' ),
							'ARG_CDI'  => __( 'CDI', 'woocommerce-gateway-ebanx' ),
							'ARG_DNI'  => __( 'DNI', 'woocommerce-gateway-ebanx' ),
						),
					);
					$ebanx_billing_brazil_document         = array(
						'type'    => 'text',
						'id'      => 'ebanx_billing_brazil_document',
						'label'   => 'CPF' . WC_EBANX_Gateway::REQUIRED_MARK,
						'class'   => array(
							'ebanx_billing_brazil_document',
							'ebanx_billing_brazil_cpf',
							'ebanx_billing_brazil_selector_option',

							'address-field',
							'wfacp_ebanx_field',
						),
						'default' => isset( $cpf ) ? $cpf : '',
					);
					$ebanx_billing_brazil_cnpj             = array(
						'type'    => 'text',
						'id'      => 'ebanx_billing_brazil_cnpj',
						'label'   => 'CNPJ' . WC_EBANX_Gateway::REQUIRED_MARK,
						'class'   => array( 'ebanx_billing_brazil_cnpj', 'ebanx_billing_brazil_cnpj', 'ebanx_billing_brazil_selector_option', 'address-field', 'wfacp_ebanx_field' ),
						'default' => isset( $cnpj ) ? $cnpj : '',
					);
					$ebanx_billing_chile_document          = array(
						'type'    => 'text',
						'id'      => 'ebanx_billing_chile_document',
						'label'   => 'RUT' . WC_EBANX_Gateway::REQUIRED_MARK,
						'class'   => array( 'ebanx_billing_chile_document', 'wfacp_ebanx_field' ),
						'default' => isset( $rut ) ? $rut : '',
					);
					$ebanx_billing_colombia_document_type  = array(
						'type'    => 'select',
						'id'      => 'ebanx_billing_colombia_document_type',
						'label'   => __( 'Select a document type', 'woocommerce-gateway-ebanx' ),
						'default' => 'COL_CDI',
						'class'   => array( 'ebanx_billing_colombia_selector', 'ebanx-select-field', 'address-field', 'wfacp_ebanx_field' ),
						'options' => array(
							'COL_CDI' => __( 'Cédula de Ciudadania', 'woocommerce-gateway-ebanx' ),
							'COL_NIT' => __( 'NIT', 'woocommerce-gateway-ebanx' ),
							'COL_CEX' => __( 'Cédula de Extranjeria', 'woocommerce-gateway-ebanx' ),
						),
					);
					$ebanx_billing_colombia_document       = array(
						'type'    => 'text',
						'id'      => 'ebanx_billing_colombia_document',
						'label'   => 'Document' . WC_EBANX_Gateway::REQUIRED_MARK,
						'class'   => array( 'ebanx_billing_colombia_document', 'address-field', 'wfacp_ebanx_field' ),
						'default' => isset( $dni ) ? $dni : '',
					);
					$ebanx_billing_peru_document           = array(
						'type'    => 'text',
						'id'      => 'ebanx_billing_peru_document',
						'label'   => 'DNI' . WC_EBANX_Gateway::REQUIRED_MARK,
						'class'   => array( 'ebanx_billing_peru_document', 'address-field', 'wfacp_ebanx_field' ),
						'default' => isset( $dni_pe ) ? $dni_pe : '',
					);
					$ebanx_billing_argentina_document      = array(
						'type'    => 'text',
						'id'      => 'ebanx_billing_argentina_document',
						'label'   => __( 'Document', 'woocommerce-gateway-ebanx' ) . WC_EBANX_Gateway::REQUIRED_MARK,
						'class'   => array( 'ebanx_billing_argentina_document', 'address-field', 'wfacp_ebanx_field' ),
						'default' => isset( $cdi ) ? $cdi : '',
					);
					if ( $is_hidedable ) {
						$ebanx_billing_brazil_person_type['class'][]      = 'wfacp_billing_fields';
						$ebanx_billing_brazil_person_type['class'][]      = 'wfacp_billing_field_hide';
						$ebanx_billing_argentina_document_type['class'][] = 'wfacp_billing_fields';
						$ebanx_billing_argentina_document_type['class'][] = 'wfacp_billing_field_hide';
						$ebanx_billing_brazil_document['class'][]         = 'wfacp_billing_fields';
						$ebanx_billing_brazil_document['class'][]         = 'wfacp_billing_field_hide';
						$ebanx_billing_brazil_cnpj['class'][]             = 'wfacp_billing_fields';
						$ebanx_billing_brazil_cnpj['class'][]             = 'wfacp_billing_field_hide';
						$ebanx_billing_chile_document['class'][]          = 'wfacp_billing_fields';
						$ebanx_billing_chile_document['class'][]          = 'wfacp_billing_field_hide';
						$ebanx_billing_colombia_document_type['class'][]  = 'wfacp_billing_fields';
						$ebanx_billing_colombia_document_type['class'][]  = 'wfacp_billing_field_hide';
						$ebanx_billing_colombia_document['class'][]       = 'wfacp_billing_fields';
						$ebanx_billing_colombia_document['class'][]       = 'wfacp_billing_field_hide';
						$ebanx_billing_peru_document['class'][]           = 'wfacp_billing_fields';
						$ebanx_billing_peru_document['class'][]           = 'wfacp_billing_field_hide';
						$ebanx_billing_argentina_document['class'][]      = 'wfacp_billing_fields';
						$ebanx_billing_argentina_document['class'][]      = 'wfacp_billing_field_hide';
					}

					if ( in_array( 'cpf', $fields_options ) && in_array( 'cnpj', $fields_options ) ) {
						$sections['fields'][] = $ebanx_billing_brazil_person_type;
					}

					// CPF is enabled.
					if ( in_array( 'cpf', $fields_options ) ) {
						$sections['fields'][] = $ebanx_billing_brazil_document;
					}
					// CNPJ is enabled.
					if ( in_array( 'cnpj', $fields_options ) ) {
						$sections['fields'][] = $ebanx_billing_brazil_cnpj;
					}
					// For Chile.
					$sections['fields'][] = $ebanx_billing_chile_document;
					// For Colombia.
					$sections['fields'][] = $ebanx_billing_colombia_document_type;
					$sections['fields'][] = $ebanx_billing_colombia_document;
					// For Argentina.
					$sections['fields'][] = $ebanx_billing_argentina_document_type;
					$sections['fields'][] = $ebanx_billing_argentina_document;
					// For Peru.
					$sections['fields'][] = $ebanx_billing_peru_document;

					$sections['fields']['wfacp_end_divider_billing'] = $end_adress_closser;

					if ( count( $after_address_element ) > 0 ) {
						foreach ( $after_address_element as $element ) {
							$sections['fields'][] = $element;
						}
					}
				}
			} catch ( Exception $e ) {

			}
		}

		return $sections;
	}

	public function css() {
		if ( class_exists( 'WC_EBANX_Gateway' ) && class_exists( 'WC_EBANX_Global_Gateway' ) ) {
			?>
            <style>.wfacp_ebanx_field {
                    clear: both;
                }</style>
			<?php
		}
	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Ebanx(), 'ebanx_gateway' );


