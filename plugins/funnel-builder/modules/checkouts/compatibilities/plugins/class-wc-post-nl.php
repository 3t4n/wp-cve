<?php
/*
 * WooCommerce PostNL
 * Author Name: PostNL
 * https://wordpress.org/plugins/woo-postnl/
 */

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Wc_PostNL {

	public $wc_postNl = null;

	public function __construct() {

		/* Register Field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_wc_output_delivery_options', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'display_field' ], 999, 2 );


		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );

		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
		add_filter( 'wc_postnl_delivery_options_location', function () {
			return "wfacp_after_wfacp_divider_billing_end_field";
		} );
		add_action( 'wp_footer', [ $this, 'add_js' ] );

	}

	public function add_field( $fields ) {
		$fields['wc_output_delivery_options'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_wc_output_delivery_options' ],
			'id'         => 'wc_output_delivery_options',
			'field_type' => 'wc_output_delivery_options',
			'label'      => __( 'PostNl Delivery Options', 'woofunnels-aero-checkout' ),
		];

		return $fields;
	}

	public function is_enable() {
		return class_exists( 'WCPN_Checkout' );
	}

	public function display_field( $field, $key ) {
		if ( ! $this->is_enable() || empty( $key ) || 'wc_output_delivery_options' !== $key || ! $this->wc_postNl instanceof WCPN_Checkout ) {
			return '';
		}
		echo "<div class=wfacp_output_delivery_options id='wfacp_output_delivery_options'>";
		$this->wc_postNl->output_delivery_options();
		echo '</div>';
	}

	public function action() {
		if ( ! $this->is_enable() || ! function_exists( 'WCPOST' ) ) {
			return;
		}
		$hookName = WCPOST()->setting_collection->getByName( WCPOST_Settings::SETTING_DELIVERY_OPTIONS_POSITION );
		WFACP_Common::remove_actions( $hookName, 'WCPN_Checkout', 'output_delivery_options' );
		$this->wc_postNl = WFACP_Common::remove_actions( 'wp_enqueue_scripts', 'WCPN_Checkout', 'enqueue_frontend_scripts' );
		if ( ! $this->wc_postNl instanceof WCPN_Checkout ) {
			return;
		}
		add_action( "wp_enqueue_scripts", [ $this->wc_postNl, "enqueue_frontend_scripts" ], 101 );
	}

	public function internal_css() {
		if ( ! $this->is_enable() || ! function_exists( 'wfacp_template' ) ) {
			return '';
		}

		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}

		$bodyClass = "body ";
		if ( 'pre_built' !== $instance->get_template_type() ) {
			$bodyClass = "body #wfacp-e-form ";
		}
		$css = "
		<style>
		 /* My Parcel Option */
            $bodyClass .wfacp_main_form.woocommerce #wfacp_output_delivery_options  input[type='radio'], 
            $bodyClass  .wfacp_main_form.woocommerce #wfacp_output_delivery_options  input[type='checkbox']
             {
                position: relative;
                left: auto;
                margin: 0 10px 0 0px;
                right: auto;
                top: auto;
                width: auto;
            }

            $bodyClass .wfacp_main_form.woocommerce #wfacp_output_delivery_options  td,
             body #wfacp-e-form .wfacp_main_form.woocommerce #wfacp_output_delivery_options th {
                padding: 0;

            }

            $bodyClass .wfacp_main_form.woocommerce #wfacp_output_delivery_options button {
                width: auto;
                padding: 10px 12px;
                margin: 0 10px 10px 0;
                display:inline-block;
            }

            $bodyClass .wfacp_main_form.woocommerce #wfacp_output_delivery_options form label {
                display: block; color: #777;
             }

            $bodyClass .wfacp_main_form.woocommerce #wfacp_output_delivery_options form select {
                -webkit-appearance: menulist;
                -moz-appearance: menulist;
                -webkit-appearance: menulist;
                padding: 10px 12px;
            }

            $bodyClass .wfacp_main_form.woocommerce #wfacp_output_delivery_options {
                margin-bottom: 15px;
            }
            
			$bodyClass #wfacp_output_delivery_options {
			    clear: both;
			    padding: 0 7px;
			}
			
			$bodyClass #wfacp_output_delivery_options .myparcel-delivery-options__modal {
			    padding: 0;
			}
			
			
			$bodyClass #wfacp_output_delivery_options h1,
			$bodyClass #wfacp_output_delivery_options h2,
			$bodyClass #wfacp_output_delivery_options h3,
			$bodyClass #wfacp_output_delivery_options h4,
			$bodyClass #wfacp_output_delivery_options h5,
			$bodyClass #wfacp_output_delivery_options h6 {
			    margin: 0 0 15px;
			    line-height: 1.5;
			}
	
			$bodyClass #wfacp_output_delivery_options p label {
			    margin: 0;
			}
			$bodyClass #wfacp_output_delivery_options button {
			    padding: 15px 52px;
			    margin: 0;
			    color: #fff;
			    display: block;
			    text-transform: capitalize;
			    box-shadow: none;
			    font-family: inherit;
			    background-color: #999;
			    font-size: 15px;
			    font-weight: 400;
			    border: none;
			    min-height: 50px;
			    border-radius: 4px;
			    margin-right: 5px;
			}
			$bodyClass #wfacp_output_delivery_options button:last-child {
   				 margin-right: 0;
			}
			
			$bodyClass #wfacp_output_delivery_options button:hover {
			    background-color: #878484;
			    outline: 0;
			    border: none;
			}
			$bodyClass #post-delivery-option-form {
                padding: 0 7px;
                margin-bottom: 20px;
            }
           $bodyClass #post-message {
                padding: 0 7px;
            }

          $bodyClass tr#header-delivery-options-title td {
                padding-left: 0;
                padding-right: 0;
            }
            
          $bodyClass tr#header-delivery-options-title td h3 {
                font-weight: normal;
            }

           $bodyClass #post-delivery-option-form .post-delivery-option-table {
                width: 100%;
            }

          $bodyClass #post-delivery-option-form .post-delivery-option-table h1,
          $bodyClass  #post-delivery-option-form .post-delivery-option-table h2,
          $bodyClass #post-delivery-option-form .post-delivery-option-table h3,
          $bodyClass #post-delivery-option-form .post-delivery-option-table h4,
          $bodyClass  #post-delivery-option-form .post-delivery-option-table h5 {
                margin: 0 0 15px;
            }

           $bodyClass #post-message h3 {
                margin: 0 0 15px;
                font-weight: normal;
            }

          $bodyClass  #post-delivery-option-form .post-delivery-option-table label {
                padding: 0 !important;
                display: inline-block;
                margin: 0;
            }
          $bodyClass  #post-delivery-option-form input[type='radio'],
          $bodyClass  #post-delivery-option-form input[type='checkbox'] {
                position: relative;
                top: auto;
                bottom: auto;
                left: auto;
                right: auto;
                margin: 0 0 0 0px;
            }
		
		$bodyClass #post-delivery-option-form table td {
                padding: 15px 8px;
                border: none;
                border-bottom: 1px solid #E6E6E6;
                text-align: left;
                font-weight: inherit;
            }

          $bodyClass  #post-delivery-option-form table td select {
                margin: 0;
                margin: 0;
                width: calc(100% - 25px) !important;
                display: inline-block;
            }

           $bodyClass #post-delivery-option-form table tr td:last-child {
                white-space: nowrap;
                vertical-align: top;
                width: 20px;
            }

           $bodyClass #post-spinner-model svg {
                width: auto;
                max-width: 100px;
                margin: auto;
                float: none;
            }

           $bodyClass #post-delivery-option-form .post-fa-clock {
                width: 16px;
                display: inline-block;
                margin-bottom: -30px;
                overflow: hidden;
                vertical-align: middle;
            }

           $bodyClass #header-delivery-options-title td {

                padding: 0 !important;
            }
           
            </style>
		";

		echo $css;

	}

	public function add_js() {
		?>
        <script>
            window.addEventListener('bwf_checkout_load', function () {
                (function ($) {
                    setTimeout(function () {
                        add_aero_title_class();
                    }, 200);

                    function add_aero_title_class() {
                        if ($('#post-message h3').length > 0) {
                            $('#post-message h3').addClass('wfacp_section_title');
                        }
                        if ($('#header-delivery-options-title td h3').length > 0) {
                            $('#header-delivery-options-title td h3').addClass('wfacp_section_title');
                        }
                    }
                })(jQuery);
            });
        </script>
		<?php


	}
}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Wc_PostNL(), 'woocommerce-postnl' );

/**
 * PostNL for WooCommerce
 * Author: PostNL
 * https://github.com/Progressus-io/postnl-for-woocommerce/
 */
class WFACP_PostNLWooCommerce {
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'register_field' ] );
		add_action( 'wfacp_template_load', [ $this, 'remove_action' ] );
		add_action( 'wfacp_internal_css', [ $this, 'js' ] );
	}

	public function register_field() {
		if ( ! class_exists( 'WFACP_Add_Address_Field' ) ) {
			return;
		}
		new WFACP_Add_Address_Field( 'house_number', array(
			'label'       => __( 'House number', 'postnl-for-woocommerce' ),
			'placeholder' => esc_attr__( 'House number', 'postnl-for-woocommerce' ),
			'class'       => [ 'form-row-first', 'address-field', 'wfacp_house_number', 'wfacp-draggable' ],
			'cssready'    => [ 'wfacp-col-full' ],
			'clear'       => false,
			'required'    => false,
			'priority'    => 90,
		) );
		new WFACP_Add_Address_Field( 'house_number', array(
			'label'       => __( 'House number', 'postnl-for-woocommerce' ),
			'placeholder' => esc_attr__( 'House number', 'postnl-for-woocommerce' ),
			'class'       => [ 'form-row-first', 'address-field', 'wfacp_house_number', 'wfacp-draggable' ],
			'cssready'    => [ 'wfacp-col-full' ],
			'clear'       => false,
			'required'    => false,
			'priority'    => 90,
		), 'shipping' );
	}

	public function remove_Action() {
		$container = WFACP_Common::remove_actions( 'woocommerce_review_order_after_shipping', 'PostNLWooCommerce\Frontend\Container', 'postnl_fields' );
		if ( $container instanceof PostNLWooCommerce\Frontend\Container ) {
			add_action( 'wfacp_woocommerce_review_order_after_shipping', [ $container, 'postnl_fields' ] );
		}
	}

	public function js() {
		?>
        <script>
            window.addEventListener('bwf_checkout_load', function () {
                (function ($) {
                    $(document.body).on('updated_checkout', function () {
                        add_hide_animate();
                    });

                    function add_hide_animate() {
                        var addresses = ['billing', 'shipping'];
                        for (var i in addresses) {
                            var key = addresses[i];
                            $(".wfacp_divider_" + key + " .form-row").each(function () {
                                let field_id = $(this).attr("id");
                                if (field_id != '') {
                                    let field_val_id1 = field_id.replace('_field', '');
                                    let field_val = $('#' + field_val_id1).val();
                                    $('#' + field_val_id1).addClass('wfacp-form-control');
                                    if (field_val != '' && field_val != null && !$(this).hasClass('wfacp-anim-wrap')) {
                                        $(this).addClass("wfacp-anim-wrap");
                                    }
                                }
                            });
                        }
                    }
                })(jQuery);
            });
        </script>
		<?php
	}
}

if ( function_exists( 'PostNLWooCommerce\postnl' ) ) {
	new WFACP_PostNLWooCommerce();
}
