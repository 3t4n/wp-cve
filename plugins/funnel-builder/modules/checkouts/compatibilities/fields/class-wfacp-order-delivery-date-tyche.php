<?php

/**
 * Order delivery date pro tyche
 * #[AllowDynamicProperties]

  class WFACP_Compatibility_Order_Delivery_Date_Tyche_Pro
 */
if (!class_exists('WFACP_Compatibility_Order_Delivery_Date_Tyche_Pro')) {
	#[AllowDynamicProperties]

  class WFACP_Compatibility_Order_Delivery_Date_Tyche_Pro
	{
		/**
		 * @var orddd_process
		 */
		private $order_instance = null;
		private $orddd_locations_instance = null;

		public function __construct() {
			add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
			add_action( 'wfacp_header_print_in_head', [ $this, 'enqueue_js' ] );
			add_filter( 'wfacp_html_fields_oddt', '__return_false' );
			add_filter( 'wfacp_html_fields_orddd_time_slot', '__return_false' );
			add_filter( 'wfacp_html_fields_orddd_locations', '__return_false' );
			add_action( 'process_wfacp_html', [ $this, 'call_birthday_addon_hook' ], 10, 3 );
			add_action( 'wfacp_checkout_page_found', [ $this, 'actions' ] );
			add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 50, 2 );

			add_action( 'wfacp_internal_css', [ $this, 'wfacp_internal_css' ] );
		}

		public function actions() {
			add_action( 'wp_footer', [ $this, 'add_js' ] );
		}

		public function add_js() {
			if ( ! $this->is_enable() ) {
				return '';
			}
			?>
            <script>
                window.addEventListener('load', function () {

                    var orddd_custom_settings = tyche.orddd.orddd_get_applied_custom_settings(0);
                    (function ($) {
                        add_aero_class();
                        var $this = $("#e_deliverydate_0_field");


                        removeLableClass();
                        $(document.body).on('wfacp_step_switching', function (e, v) {
                            removeLableClass();
                        });

                        setTimeout(function () {
                            add_aero_class();
                            add_anim_class();
                        }, 5000);
                        $(document.body).on('update_checkout', function () {
                            add_aero_class();
                            add_anim_class();
                        });
                        $(document.body).on('updated_checkout', function () {
                            add_aero_class();
                            add_anim_class();
                            removeLableClass();
                        });


                        function add_aero_class() {
                            if ($("#e_deliverydate_0_field").length > 0) {
                                var $this = $("#e_deliverydate_0_field");
                                if (!$this.hasClass('wfacp-col-full')) {
                                    $this.addClass("wfacp-col-full");
                                    $this.addClass("wfacp-form-control-wrapper");
                                }
                            }
                            if ($("#orddd_time_slot_0_field").length > 0) {
                                $("#orddd_time_slot_0_field").addClass("wfacp-col-full");
                                $("#orddd_time_slot_0_field").addClass("wfacp-form-control-wrapper");
                                $("#orddd_time_slot_0_field > label").addClass("wfacp-form-control-label");
                                $("#orddd_time_slot_0").addClass("wfacp-form-control");
                            }
                        }

                        function add_anim_class() {
                            $("#e_deliverydate_0").on('click', function () {
                                if (!$("#e_deliverydate_0").parents("p").hasClass("wfacp-anim-wrap")) {
                                    $("#e_deliverydate_0").parents("p").addClass("wfacp-anim-wrap");
                                }
                            });
                        }

                        function removeLableClass() {
                            if ($('.form-row .list-view.selected').length > 0) {

                                var lableEle = $('.list-view.selected').parents('.orddd_list_view_container').parents('.form-row').children('label');
                                if (lableEle.hasClass('wfacp-form-control-label')) {
                                    lableEle.removeClass('wfacp-form-control-label');
                                }


                            }
                        }


                    })(jQuery);
                });
            </script>
			<?php
		}

		public function add_field( $fields ) {
			if ( $this->is_enable() ) {
				$fields['oddt']            = [
					'type'       => 'wfacp_html',
					'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap', 'oddt' ],
					'id'         => 'oddt',
					'field_type' => 'advanced',
					'label'      => __( 'Delivery Date', 'woofunnels-aero-checkout' ),
				];
				$fields['orddd_time_slot'] = [
					'type'       => 'wfacp_html',
					'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap', 'orddd_time_slot' ],
					'id'         => 'orddd_time_slot',
					'field_type' => 'advanced',
					'label'      => __( 'Time Slot', 'woofunnels-aero-checkout' ),
				];
				$fields['orddd_locations'] = [
					'type'       => 'wfacp_html',
					'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap', 'orddd_locations' ],
					'id'         => 'orddd_locations',
					'field_type' => 'advanced',
					'label'      => __( 'Pickup Location', 'woofunnels-aero-checkout' ),
				];
			}

			return $fields;

		}

		private function is_enable() {
			if ( class_exists( 'order_delivery_date' ) ) {
				if ( get_option( 'orddd_enable_delivery_date' ) === 'on' ) {
					return true;
				}
			}

			return false;
		}

		public function enqueue_js() {
			if ( $this->is_enable() ) {
				if ( class_exists( 'orddd_common' ) ) {
					$orddd_shopping_cart_hook       = orddd_common::orddd_get_shopping_cart_hook();
					$this->order_instance           = WFACP_Common::remove_actions( $orddd_shopping_cart_hook, 'orddd_process', 'orddd_date_after_checkout_billing_form' );
					$this->orddd_locations_instance = WFACP_Common::remove_actions( $orddd_shopping_cart_hook, 'orddd_locations', 'orddd_locations_after_checkout_billing_form' );;
					if ( $this->order_instance instanceof orddd_process ) {
						remove_action( $orddd_shopping_cart_hook, array( $this->order_instance, 'orddd_time_slot_after_checkout_billing_form' ) );
						remove_action( $orddd_shopping_cart_hook, array( $this->order_instance, 'orddd_text_block_after_checkout_billing_form' ) );
					}
				}
				$instance = WFACP_Common::remove_actions( 'wp_enqueue_scripts', 'orddd_scripts', 'orddd_front_scripts_css' );
				if ( $instance instanceof orddd_scripts ) {
					add_action( 'wfacp_internal_css', [ $instance, 'orddd_front_scripts_css' ] );
				}
			}
		}

		public function call_birthday_addon_hook( $field, $key, $args ) {
			if ( $this->is_enable() && ! empty( $key ) ) {
				if ( $this->order_instance instanceof orddd_process ) {
					if ( 'oddt' === $key && method_exists( $this->order_instance, 'orddd_date_after_checkout_billing_form' ) ) {
						$this->order_instance->orddd_date_after_checkout_billing_form();
					}

					if ( 'orddd_time_slot' === $key && method_exists( $this->order_instance, 'orddd_time_slot_after_checkout_billing_form' ) ) {
						$this->order_instance->orddd_time_slot_after_checkout_billing_form();
					}

					if ( $this->orddd_locations_instance instanceof orddd_locations && 'orddd_locations' === $key && method_exists( $this->orddd_locations_instance, 'orddd_locations_after_checkout_billing_form' ) ) {
						$this->order_instance->orddd_text_block_after_checkout_billing_form();
						$this->orddd_locations_instance->orddd_locations_after_checkout_billing_form();

					}
				}

			}
		}

		public function add_default_wfacp_styling( $args, $key ) {
			if ( $key == 'e_deliverydate_0' || $key == 'orddd_time_slot_0' || $key == 'orddd_locations_0' ) {
				$args['input_class'] = array_merge( $args['input_class'], [ 'wfacp-form-control' ] );
				$args['label_class'] = array_merge( $args['label_class'], [ 'wfacp-form-control-label' ] );
				$args['class']       = array_merge( $args['class'], [ 'wfacp-col-full', 'wfacp-form-control-wrapper' ] );
			}

			return $args;
		}

		public function wfacp_internal_css() {

			$instance = wfacp_template();
			if ( ! $instance instanceof WFACP_Template_Common ) {
				return;
			}

			$bodyClass = "body ";
			if ( 'pre_built' !== $instance->get_template_type() ) {
				$bodyClass = "body #wfacp-e-form ";
			}

			$cssHtml = "<style>";
			$cssHtml .= $bodyClass . "#orddd_time_slot_0_field {padding: 0 7px;}";
			$cssHtml .= $bodyClass . ".wfacp_main_form p#e_deliverydate_0_field {padding: 0 7px;}";
			$cssHtml .= $bodyClass . "#e_deliverydate_0_field .orddd_field_note {float: none;}";
			$cssHtml .= $bodyClass . "#e_deliverydate_0_field {position: relative;clear: both;}";
			$cssHtml .= $bodyClass . ".wfacp_main_form #e_deliverydate_0_field.wfacp-anim-wrap label {top: 4px;font-size: 12.5px;background: transparent;bottom: auto;right: auto;margin-top: 0;left: 20px;}";
			$cssHtml .= $bodyClass . ".orddd-checkout-fields p.form-row:not(.wfacp-anim-wrap) .hasDatepicker {padding: 12px 12px 12px;}";
			$cssHtml .= $bodyClass . ".orddd-checkout-fields p.form-row:not(.wfacp-anim-wrap) label {top: 16px;margin: 0;bottom: auto;}";
			$cssHtml .= $bodyClass . ".wfacp-top .orddd-checkout-fields p.form-row:not(.wfacp-anim-wrap) label {top: 0px;}";

			$cssHtml .= "</style>";

			echo $cssHtml;

		}
	}

	WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_Order_Delivery_Date_Tyche_Pro(), 'oddtp' );

}
