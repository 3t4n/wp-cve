<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currency Converted Classic widget class
 */
class CBCurrencyConverterWidget extends WP_Widget {

	/**
	 *Unique identifier for  widget.
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * widget file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $widget_slug = 'cbcurrencyconverterwidget';

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {
		parent::__construct(
			$this->widget_slug,
			esc_html__( 'CBX Currency Converter', 'cbcurrencyconverter' ),
			[
				'classname'   => $this->widget_slug . '-class',
				'description' => esc_html__( 'Currency Converter', 'cbcurrencyconverter' ),
			]
		);


	} // end constructor


	/**
	 * Outputs the content of the widget.
	 *
	 * @param  array args  The array of form elements
	 * @param  array instance The current instance of the widget
	 */
	public function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );

		$default_values = CBCurrencyConverterHelper::global_default_values();

		//take care(comma sep string to array) array related properties
		if ( isset( $default_values['calc_from_currencies'] ) && is_string( $default_values['calc_from_currencies'] ) ) {
			$default_values['calc_from_currencies'] = explode( ',', $default_values['calc_from_currencies'] );
		}

		if ( isset( $default_values['calc_to_currencies'] ) && is_string( $default_values['calc_to_currencies'] ) ) {
			$default_values['calc_to_currencies'] = explode( ',', $default_values['calc_to_currencies'] );
		}

		if ( isset( $default_values['list_to_currencies'] ) && is_string( $default_values['list_to_currencies'] ) ) {
			$default_values['list_to_currencies'] = explode( ',', $default_values['list_to_currencies'] );
		}
		extract( $default_values, EXTR_SKIP );

		$atts = [];

		$atts['layout']        = isset( $instance['layout'] ) ? sanitize_text_field( $instance['layout'] ) : $layout;
		$atts['decimal_point'] = isset( $instance['decimal_point'] ) ? intval( $instance['decimal_point'] ) : $decimal_point;

		$atts['calc_title']           = isset( $instance['calc_title'] ) ? sanitize_text_field( $instance['calc_title'] ) : $calc_title;
		$atts['calc_default_amount']  = isset( $instance['calc_default_amount'] ) ? floatval( $instance['calc_default_amount'] ) : $calc_default_amount;
		$atts['calc_from_currencies'] = isset( $instance['calc_from_currencies'] ) ? wp_unslash( $instance['calc_from_currencies'] ) : $calc_from_currencies;
		$atts['calc_from_currency']   = isset( $instance['calc_from_currency'] ) ? sanitize_text_field( $instance['calc_from_currency'] ) : $calc_from_currency;
		$atts['calc_to_currencies']   = isset( $instance['calc_to_currencies'] ) ? wp_unslash( $instance['calc_to_currencies'] ) : $calc_to_currencies;
		$atts['calc_to_currency']     = isset( $instance['calc_to_currency'] ) ? sanitize_text_field( $instance['calc_to_currency'] ) : $calc_to_currency;


		$atts['list_title']          = isset( $instance['list_title'] ) ? sanitize_text_field( $instance['list_title'] ) : $list_title;
		$atts['list_default_amount'] = isset( $instance['list_default_amount'] ) ? floatval( $instance['list_default_amount'] ) : $list_default_amount;
		$atts['list_to_currencies']  = isset( $instance['list_to_currencies'] ) ? wp_unslash( $instance['list_to_currencies'] ) : $list_to_currencies;
		$atts['list_from_currency']  = isset( $instance['list_from_currency'] ) ? sanitize_text_field( $instance['list_from_currency'] ) : $list_from_currency;


		$atts['calc_from_currencies'] = array_values( array_filter( $atts['calc_from_currencies'] ) );
		$atts['calc_to_currencies']   = array_values( array_filter( $atts['calc_to_currencies'] ) );
		$atts['list_to_currencies']   = array_values( array_filter( $atts['list_to_currencies'] ) );

		extract( $atts );

		if ( sizeof( $calc_from_currencies ) == 0 ) {
			$calc_from_currencies = $atts['calc_from_currencies'] = $default_values['calc_from_currencies'];
		}
		if ( sizeof( $calc_to_currencies ) == 0 ) {
			$calc_to_currencies = $atts['calc_to_currencies'] = $default_values['calc_to_currencies'];
		}
		if ( sizeof( $list_to_currencies ) == 0 ) {
			$list_to_currencies = $atts['list_to_currencies'] = $default_values['list_to_currencies'];
		}


		if ( ! in_array( $calc_from_currency, $calc_from_currencies ) || $calc_from_currency == '' ) {
			$calc_from_currency = $atts['calc_from_currency'] = cbcurrencyconverter_first_value( $calc_from_currencies );
		}

		if ( ! in_array( $calc_to_currency, $calc_to_currencies ) || $calc_to_currency == '' ) {
			$calc_to_currency = $atts['calc_to_currency'] = cbcurrencyconverter_first_value( $calc_to_currencies );
		}

		if ( $list_from_currency == '' ) {
			$list_from_currency = $atts['list_from_currency'] = $default_values['list_from_currency'];
		}

		echo $before_widget;

		$title = empty( $instance['title'] ) ? ' ' : apply_filters( 'widget_title', $instance['title'] );

		echo $before_title . $title . $after_title;


		if ( $layout == 'list' ) {
			echo CBCurrencyConverterHelper::cbxcclistview( 'widget', $atts );
		} elseif ( $layout == 'cal' ) {
			echo CBCurrencyConverterHelper::cbxcccalcview( 'widget', $atts );
		} elseif ( $layout == 'calwithlistbottom' ) {
			echo CBCurrencyConverterHelper::cbxcccalcview( 'widget', $atts ) . CBCurrencyConverterHelper::cbxcclistview( 'widget', $atts );
		} elseif ( $layout == 'calwithlisttop' ) {
			echo CBCurrencyConverterHelper::cbxcclistview( 'widget', $atts ) . CBCurrencyConverterHelper::cbxcccalcview( 'widget', $atts );
		}

		echo $after_widget;
	}//end widget


	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param  array  $new_instance
	 * @param  array  $old_instance
	 *
	 * @return array|bool
	 */
	public function update( $new_instance, $old_instance ) {
		if ( ! isset( $new_instance['submit'] ) ) {
			return false;
		}
		$instance = $old_instance;


		//widget title
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		$instance['layout']        = sanitize_text_field( $new_instance['layout'] );
		$instance['decimal_point'] = intval( $new_instance['decimal_point'] );

		//calculator settings
		$instance['calc_title']           = sanitize_text_field( $new_instance['calc_title'] );
		$instance['calc_default_amount']  = floatval( $new_instance['calc_default_amount'] );
		$calc_from_currencies             = wp_unslash( $new_instance['calc_from_currencies'] );
		$instance['calc_from_currencies'] = array_values( array_filter( $calc_from_currencies ) );
		$instance['calc_from_currency']   = sanitize_text_field( $new_instance['calc_from_currency'] );
		$calc_to_currencies               = wp_unslash( $new_instance['calc_to_currencies'] );
		$instance['calc_to_currencies']   = array_values( array_filter( $calc_to_currencies ) );
		$instance['calc_to_currency']     = sanitize_text_field( $new_instance['calc_to_currency'] );

		//list settings
		$instance['list_title']          = sanitize_text_field( $new_instance['list_title'] );
		$instance['list_default_amount'] = floatval( $new_instance['list_default_amount'] );

		$list_to_currencies             = wp_unslash( $new_instance['list_to_currencies'] );
		$instance['list_to_currencies'] = array_values( array_filter( $list_to_currencies ) );
		$instance['list_from_currency'] = sanitize_text_field( $new_instance['list_from_currency'] );

		return $instance;
	}//end widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param  array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		$default_values = CBCurrencyConverterHelper::global_default_values();

		//take care(comma sep string to array) array related properties
		if ( isset( $default_values['calc_from_currencies'] ) && is_string( $default_values['calc_from_currencies'] ) ) {
			$default_values['calc_from_currencies'] = explode( ',', $default_values['calc_from_currencies'] );
		}

		if ( isset( $default_values['calc_to_currencies'] ) && is_string( $default_values['calc_to_currencies'] ) ) {
			$default_values['calc_to_currencies'] = explode( ',', $default_values['calc_to_currencies'] );
		}

		if ( isset( $default_values['list_to_currencies'] ) && is_string( $default_values['list_to_currencies'] ) ) {
			$default_values['list_to_currencies'] = explode( ',', $default_values['list_to_currencies'] );
		}

		//widget title
		$default_values['title'] = esc_html__( 'Currency Calculator', 'cbcurrencyconverter' );

		$instance = wp_parse_args( (array) $instance, $default_values );

		//widget specific
		$title = sanitize_text_field( $instance['title'] );

		//general
		$layout        = sanitize_text_field( $instance['layout'] );
		$decimal_point = intval( $instance['decimal_point'] );


		//calculator
		$calc_title          = sanitize_text_field( $instance['calc_title'] );
		$calc_default_amount = floatval( $instance['calc_default_amount'] );

		$calc_from_currency   = sanitize_text_field( $instance['calc_from_currency'] );
		$calc_from_currencies = wp_unslash( $instance['calc_from_currencies'] );
		$calc_from_currencies = array_values( array_filter( $calc_from_currencies ) );

		$calc_to_currency   = sanitize_text_field( $instance['calc_to_currency'] );
		$calc_to_currencies = wp_unslash( $instance['calc_to_currencies'] );
		$calc_to_currencies = array_values( array_filter( $calc_to_currencies ) );


		//list
		$list_title          = sanitize_text_field( $instance['list_title'] );
		$list_default_amount = floatval( $instance['list_default_amount'] );
		$list_from_currency  = sanitize_text_field( $instance['list_from_currency'] );
		$list_to_currencies  = wp_unslash( $instance['list_to_currencies'] );
		$list_to_currencies  = array_values( array_filter( $list_to_currencies ) );

		?>
        <div class="cbcurrencyconverter-admin-widget-wrap" data-old="0">
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>">
					<?php esc_html_e( 'Title', 'cbcurrencyconverter' ); ?>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"/> </label>
            </p>
            <div class="selecttwo-select-wrapper">
                <label for="<?php echo $this->get_field_id( 'layout' ); ?>"><?php esc_html_e( 'Select Layout:', 'cbcurrencyconverter' ); ?></label>
                <select name="<?php echo $this->get_field_name( 'layout' ); ?>" id="<?php echo $this->get_field_id( 'layout' ); ?>" class="selecttwo-select widefat">
                    <option value="cal" <?php selected( $layout, 'cal' ); ?>><?php esc_html_e( 'Calculator', 'cbcurrencyconverter' ); ?></option>
                    <option value="list" <?php selected( $layout, 'list' ); ?>><?php esc_html_e( 'List', 'cbcurrencyconverter' ); ?></option>
                    <option value="calwithlistbottom" <?php selected( $layout, 'calwithlistbottom' ); ?>><?php esc_html_e( 'Calc with List Bottom', 'cbcurrencyconverter' ); ?></option>
                    <option value="calwithlisttop" <?php selected( $layout, 'calwithlisttop' ); ?>><?php esc_html_e( 'Calc with List Top', 'cbcurrencyconverter' ); ?></option>
                </select>
            </div>
            <p>
                <label for="<?php echo $this->get_field_id( 'decimal_point ' ); ?>">
					<?php esc_html_e( 'Decimal Point', 'cbcurrencyconverter' ); ?>
                    <input class="widefat decimal_point" id="<?php echo $this->get_field_id( 'decimal_point' ); ?>" name="<?php echo $this->get_field_name( 'decimal_point' ); ?>" type="number" value="<?php echo intval( $decimal_point ); ?>"/>
                </label>
            </p>
            <p>
            <h3><?php esc_html_e( 'Calculator settings', 'cbcurrencyconverteraddon' ) ?></h3></p>
            <p>
                <label for="<?php echo $this->get_field_id( 'calc_title' ); ?>">
					<?php esc_html_e( 'Calculator Header:', 'cbcurrencyconverteraddon' ); ?>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'calc_title' ); ?>" name="<?php echo $this->get_field_name( 'calc_title' ); ?>" type="text" value="<?php echo $calc_title; ?>"/> </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'calc_default_amount' ); ?>">
					<?php esc_html_e( 'Default Amount for Calculator', 'cbcurrencyconverteraddon' ); ?>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'calc_default_amount' ); ?>"
                           name="<?php echo $this->get_field_name( 'calc_default_amount' ); ?>" type="number" step="any"
                           value="<?php echo floatval( $calc_default_amount ); ?>"/> </label>
            </p>
			<?php
			$all_currencies = CBCurrencyConverterHelper::getCurrencyList();
			?>
            <div class="selecttwo-select-wrapper">
                <label for="<?php echo $this->get_field_id( 'calc_from_currencies' ); ?>"><?php esc_html_e( 'Calculator Enabled From Currencies', 'cbcurrencyconverteraddon' ); ?></label>
                <div class="clear clearfix"></div>
                <select name="<?php echo $this->get_field_name( 'calc_from_currencies' ) . '[]'; ?>" id="<?php echo $this->get_field_id( 'calc_from_currencies' ); ?>" multiple="multiple" class="widefat selecttwo-select calc_from_currencies">
					<?php foreach ( $all_currencies as $key => $title ) { ?>
                        <option value="<?php echo $key; ?>" <?php echo in_array( $key, $calc_from_currencies ) ? 'selected ' : ''; ?>>
							<?php echo $title . ' - ' . $key; ?>
                        </option>
					<?php } ?>
                </select>
            </div>
			<?php

			?>
            <div class="selecttwo-select-wrapper">
                <label for="<?php echo $this->get_field_id( 'calc_from_currency' ); ?>"><?php esc_html_e( 'Calculator From Default Currency', 'cbcurrencyconverteraddon' ); ?></label>
                <select name="<?php echo $this->get_field_name( 'calc_from_currency' ); ?>"
                        id="<?php echo $this->get_field_id( 'calc_from_currency' ); ?>" class="selecttwo-select widefat calc_from_currency">
					<?php
					foreach ( $all_currencies as $key => $title ) {
						if ( ! in_array( $key, $calc_from_currencies ) ) {
							continue;
						}
						?>
                        <option value="<?php echo $key; ?>"<?php selected( $key, $calc_from_currency ); ?>>
							<?php echo $title . ' - ' . $key; ?>
                        </option>
					<?php } ?>
                </select>
            </div>

            <div class="selecttwo-select-wrapper">
                <label for="<?php echo $this->get_field_id( 'calc_to_currencies' ); ?>"><?php esc_html_e( 'Calculator Enabled To Currencies', 'cbcurrencyconverteraddon' ); ?></label>
                <div class="clear clearfix"></div>
                <select name="<?php echo $this->get_field_name( 'calc_to_currencies' ) . '[]'; ?>" id="<?php echo $this->get_field_id( 'calc_to_currencies' ); ?>" multiple="multiple" class="widefat selecttwo-select calc_to_currencies">
					<?php
					foreach ( $all_currencies as $key => $title ) {
						?>
                        <option value="<?php echo $key; ?>" <?php echo in_array( $key, $calc_to_currencies ) ? 'selected ' : ''; ?>>
							<?php echo $title . ' - ' . $key; ?>
                        </option>
					<?php } ?>
                </select>
            </div>


            <div class="selecttwo-select-wrapper">
                <label for="<?php echo $this->get_field_id( 'calc_to_currency' ); ?>"><?php esc_html_e( 'Calculator To Default Currency', 'cbcurrencyconverteraddon' ); ?></label>
                <select name="<?php echo $this->get_field_name( 'calc_to_currency' ); ?>"
                        id="<?php echo $this->get_field_id( 'calc_to_currency' ); ?>" class="selecttwo-select widefat calc_to_currency">
					<?php
					foreach ( $all_currencies as $key => $title ) {
						if ( ! in_array( $key, $calc_to_currencies ) ) {
							continue;
						}
						?>
                        <option
                                value="<?php echo $key; ?>"<?php selected( $key, $calc_to_currency ); ?>>
							<?php echo $title . ' - ' . $key; ?>
                        </option>
					<?php } ?>
                </select>
            </div>


            <p>
            <h3><?php esc_html_e( 'List settings', 'cbcurrencyconverteraddon' ) ?></h3></p>

            <p>
                <label for="<?php echo $this->get_field_id( 'list_title' ); ?>">
					<?php esc_html_e( 'List Header:', 'cbcurrencyconverteraddon' ); ?>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'list_title' ); ?>"
                           name="<?php echo $this->get_field_name( 'list_title' ); ?>" type="text"
                           value="<?php echo $list_title; ?>"/> </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'list_default_amount ' ); ?>">
					<?php esc_html_e( 'Default Amount for List', 'cbcurrencyconverteraddon' ); ?>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'list_default_amount' ); ?>"
                           name="<?php echo $this->get_field_name( 'list_default_amount' ); ?>" type="number" step="any"
                           value="<?php echo floatval( $list_default_amount ); ?>"/> </label>
            </p>

			<?php
			//$all_currencies = CBCurrencyConverterHelper::getCurrencyList();
			?>


            <div class="selecttwo-select-wrapper cbcurrencylist">
                <label for="<?php echo $this->get_field_id( 'list_to_currencies' ); ?>"><?php esc_html_e( 'List To Currencies', 'cbcurrencyconverteraddon' ); ?></label>
                <div class="clear clearfix"></div>
                <select name="<?php echo $this->get_field_name( 'list_to_currencies' ) . '[]'; ?>"
                        id="<?php echo $this->get_field_id( 'list_to_currencies' ); ?>" multiple="multiple" class="widefat selecttwo-select">
					<?php foreach ( $all_currencies as $index => $currency ) { ?>
                        <option
                                value="<?php echo $index; ?>"<?php echo in_array( $index, $list_to_currencies ) ? 'selected ' : ''; ?>><?php echo $currency . ' - ' . $index; ?></option>
					<?php } ?>
                </select>
            </div>
            <div class="selecttwo-select-wrapper">
                <label for="<?php echo $this->get_field_id( 'list_from_currency' ); ?>"><?php esc_html_e( 'List Default From Currency', 'cbcurrencyconverteraddon' ); ?></label>
                <select name="<?php echo $this->get_field_name( 'list_from_currency' ); ?>"
                        id="<?php echo $this->get_field_id( 'list_from_currency' ); ?>" class="selecttwo-select widefat">
					<?php foreach ( $all_currencies as $index => $all_currenciescalc ) { ?>
                        <option
                                value="<?php echo $index; ?>"<?php selected( $index, $list_from_currency ); ?>><?php echo $all_currenciescalc . ' - ' . $index; ?></option>
					<?php } ?>
                </select>
            </div>
            <input type="hidden" id="<?php echo $this->get_field_id( 'submit' ); ?>"
                   name="<?php echo $this->get_field_name( 'submit' ); ?>" value="1"/>
        </div>
        <style>
            .selecttwo-select-wrapper .select2-container {
                width: 100% !important;

            }
        </style>

		<?php

	}//end form
}//end class CBCurrencyConverterWidget