<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties]

  class WFACP_Compatibility_With_AutoMateWoo {
	/**
	 * @var AutomateWoo\Birthdays\Frontend
	 */

	private $actives = [];


	public function __construct() {

		add_action( 'init', [ $this, 'init_class' ], 4 );

		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ] );
		//;
		add_filter( 'wfacp_html_fields_aw_birthdays_addon', '__return_false' );

		add_filter( 'wfacp_html_fields_automatewoo_optin_wrap', '__return_false' );

		add_action( 'process_wfacp_html', [ $this, 'call_birthday_addon_hook' ], 10, 3 );

		/* calling the css for plugin */
		add_action( 'wfacp_internal_css', [ $this, 'amw_css_func' ], 99 );

		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );

		/* automate optin */
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'intialise_automate_woo_obj' ] );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'intialise_automate_woo_obj' ] );

	}

	public function intialise_automate_woo_obj() {
		if ( class_exists( 'AutomateWoo\Hooks' ) ) {

			$instanseOfHookCls = WFACP_Common::remove_actions( 'woocommerce_checkout_after_terms_and_conditions', 'AutomateWoo\Frontend', 'output_checkout_optin_checkbox' );
			if ( isset( $instanseOfHookCls ) ) {
				$this->actives['automatewoo_optin'] = $instanseOfHookCls;

			}
		}
	}

	public function init_class() {

		if ( class_exists( 'AutomateWoo\Birthdays\Frontend' ) ) {

			$instanse = WFACP_Common::remove_actions( 'woocommerce_before_checkout_form', 'AutomateWoo\Birthdays\Frontend', 'init_checkout' );
			if ( isset( $instanse ) ) {
				$this->actives['AW_Birthdays_Addon'] = $instanse;
			}
		}

	}


	public function add_field( $field ) {

		if ( $this->is_enable( 'AW_Birthdays_Addon' ) ) {
			$field['aw_birthdays_addon'] = [
				'type'       => 'wfacp_html',
				'class'      => [ 'aw_birthdays_addon_wrap' ],
				'id'         => 'aw_birthdays_addon',
				'field_type' => 'advanced',
				'label'      => __( 'AutomateWoo Birthday', 'woocommerce' ),
			];
		}

		if ( class_exists( 'AutomateWoo\Hooks' ) ) {
			$field['automatewoo_optin_wrap'] = [
				'type'       => 'wfacp_html',
				'class'      => [ 'aw_addon_wrap' ],
				'id'         => 'automatewoo_optin_wrap',
				'field_type' => 'advanced',
				'label'      => __( 'AutomateWoo Optin', 'woocommerce' ),
			];
		}

		return $field;
	}

	public function is_enable( $slug ) {
		if ( isset( $this->actives[ $slug ] ) ) {
			return true;
		}

		return false;
	}

	public function call_birthday_addon_hook( $field, $key, $args ) {
		if ( ! empty( $key ) && 'aw_birthdays_addon' === $key && $this->is_enable( 'AW_Birthdays_Addon' ) ) {
			AutomateWoo\Birthdays\Frontend::add_birthday_field_to_checkout_form();
		}

		if ( ! empty( $key ) && 'automatewoo_optin_wrap' === $key && class_exists( 'AutomateWoo\Hooks' ) ) {

			$optionText = __( 'I want to receive updates about products and promotions.', 'automatewoo' );
			$optionText = AutomateWoo\Options::optin_checkbox_text();

			$defaults = array(
				'type'  => 'checkbox',
				'class' => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'aw_addon_wrap' ],
				'id'    => 'automatewoo_optin',

				'field_type' => 'advanced',
				'label'      => $optionText,
				'default'    => checked( isset( $_POST['automatewoo_optin'] ), true ),
			);
			woocommerce_form_field( 'automatewoo_optin', $defaults );
		}

	}

	public function add_default_wfacp_styling( $args, $key ) {

		$argsclass = [];
		if ( isset( $args['class'] ) ) {
			$argsclass = $args['class'];
		}

		if ( $key == 'aw_birthdays_addon' && $this->is_enable( 'AW_Birthdays_Addon' ) ) {

			$all_cls       = array_merge( [ 'wfacp_drop_list' ], $argsclass );
			$args['class'] = $all_cls;
		}

		return $args;
	}

	public function amw_css_func( $selected_template_slug ) {

		if ( ! $this->is_enable( 'AW_Birthdays_Addon' ) ) {
			return;
		}

		$instance = wfacp_template();
		if ( is_null( $instance ) ) {
			return '';
		}

		$data = $instance->get_checkout_fields();

		if ( ! isset( $data['advanced']['aw_birthdays_addon'] ) ) {
			return;
		}

		$padd_left_right = 'padding: 12px 10px';
		if ( 'layout_2' === $selected_template_slug ) {
			$padd_left_right = 'padding: 10px 18px;';
			echo '<style>body .wfacp_main_form .automatewoo-birthday-section .automatewoo-birthday-field__select{font-size: 14px;}</style>';
		} elseif ( 'layout_4' === $selected_template_slug ) {
			echo '<style>body .wfacp_main_form.woocommerce .automatewoo-birthday-section .automatewoo-birthday-field__select{padding: 12px 8px;}</style>';
		}

		$instance = wfacp_template();
		$px       = $instance->get_template_type_px();

		$array_class = [
			'layout_1' => 15,
			'layout_2' => 15,
			'layout_4' => 15,
			'layout_9' => 7,
		];
		?>
        <style>
            /* Automate field styling */

            <?php
if ( isset( $array_class[ $selected_template_slug ] ) ) {


?>

            body .wfacp_main_form .wfacp-row .automatewoo-birthday-section {
                padding-left: <?php echo $array_class[ $selected_template_slug ]; ?>px;
                padding-right: <?php echo $array_class[ $selected_template_slug ]; ?>px;
            }

            <?php
			}
			?>

            <?php
			if ( isset( $px ) ) {
				printf( 'body .wfacp_main_form .wfacp-row .automatewoo-birthday-section{ padding: 0 %spx;}', $px );
			}
			?>


            /* Automate Plugin Birthday field */
            #wfacp_checkout_form .automatewoo-birthday-section .automatewoo-birthday-field__select {
                height: auto;
                font-size: 14px;
                line-height: 1.5;
                border-radius: 4px;
                position: relative;
                color: #404040;
                min-height: 48px;
            }

            body #wfacp_checkout_form .wfacp-row .automatewoo-birthday-section > label {
                color: #777777;
            }

            body #wfacp_checkout_form .wfacp-row .automatewoo-birthday-section {
                padding: 0 7px;;
            }

            body #wfacp_checkout_form .wfacp-row .automatewoo-birthday-section select {
                width: 31.33%;
                float: left;
                margin-right: 2%;
                display: block;
            }

            body #wfacp_checkout_form .wfacp-row .automatewoo-birthday-section select:nth-child(3n) {
                margin-right: 0;
            }

            body #wfacp_checkout_form .automatewoo-birthday-section .automatewoo-birthday-field__select {
                height: auto;
                padding: 12px 10px;
            }

            body .wfacp_main_form .wfacp-row .automatewoo-birthday-section {
                margin: 0 0 15px;
                clear: both;
            }

            body .wfacp_main_form .automatewoo-birthday-section .automatewoo-birthday-field {
                margin: 0 0 4px;
                max-width: 100%;
            }

            body .wfacp_main_form .automatewoo-birthday-section .automatewoo-birthday-section__description,
            body .wfacp_main_form .automatewoo-birthday-section .automatewoo-birthday-section__already-set-text {
                font-size: 12px;
                line-height: 1.5;
            }

            body .wfacp_main_form .automatewoo-birthday-section .automatewoo-birthday-field__select {
                height: auto;
            <?php echo $padd_left_right; ?>

            }

            body .wfacp_main_form .wfacp-row .automatewoo-birthday-section > label {
                font-weight: normal;
            }


            @media (max-width: 767px) {
                body .wfacp_main_form .automatewoo-birthday-section .automatewoo-birthday-field__select {
                    padding: 12px 8px;
                }

                body #wfacp-e-form .wfacp_main_form .wfacp-row .automatewoo-birthday-section select {
                    width: 100%;
                    margin-bottom: 10px;
                }

                body #wfacp-e-form .wfacp_main_form .wfacp-row .automatewoo-birthday-section select:last-child {
                    margin-bottom: 0;
                }
            }
        </style>
		<?php
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_AutoMateWoo(), 'amw' );



