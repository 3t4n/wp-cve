<?php
/* ---------------------------------------------------------------------------
 * Shortcodes | Map
 * --------------------------------------------------------------------------- */
if (function_exists('vc_map')) {
    if(!function_exists( 'lbi_vc_integrateWithVC' ) ) {
        //add_action ( 'vc_before_init', 'lbi_vc_integrateWithVC');
        //add_action ( 'vc_after_init', 'lbi_vc_integrateWithVC');
        add_action ( 'init', 'lbi_vc_integrateWithVC', 20 );

    	function lbi_vc_integrateWithVC() {
    	    global $exch_currencies;
            $lbi_category = 'Leartes.NET';
    	    // Exchange Rates  ---------------------------------------
            global $tlds_popular;
            vc_map(  array (
                'base'                    => 'lbi_exchange_rates',
                'name'                    => __( 'Turkish Lira Exchange Rates', 'lbi-exchrates' ),
                'category'                => $lbi_category,
                'description'             => __('Gets TRY Exchange Rates from TCMB (Turkish Central Bank)', 'lbi-exchrates'),
                'icon'                    => 'fa fa-money',
                "show_settings_on_create" => true,
                'params'                  => array (
                   array(
                        'param_name'  => 'title',
                        'type'        => 'textfield',
                        'heading'     => __( 'Title', 'lbi-exchrates' ),
                        'description' => __( 'Optional Title', 'lbi-exchrates' ),
                        'admin_label' => true
                    ),
                    array(
                        'type'        => 'lbi_select',
                        'heading'     => __( 'Display Currencies', 'lbi-exchrates' ),
                        'param_name'  => 'currencies_all',
                        'values'      => array(
                            'true'   => __('Show all currencies', 'lbi-exchrates'),
                            'false'  => __('Choose currencies to display ', 'lbi-exchrates') ,
                        ),
                        'value'       => 'true'
                    ),
                    array(
                        'type'        => 'lbi_multiple',
                        'heading'     => __( 'Choose currencies to display ( hold ctrl or shift to select multiple )', 'lbi-exchrates' ),
                        'param_name'  => 'currencies',
                        'values'      => $exch_currencies,
                        'admin_label' => true,
                        'dependency'  => array(
                            'element' => 'currencies_all',
                            'value'   => array( 'false' )
                        ),
                        'std'         => 'USD'
                    ),
                    array(
                        'type'        => 'lbi_select',
                        'heading'     => __( 'Currency Title', 'lbi-exchrates' ),
                        'param_name'  => 'caption',
                        'values'      => array(
                            'code'   => __('Currency Code', 'lbi-exchrates'),
                            'name'   => __('Currency Name', 'lbi-exchrates'),
                            'both'   => __('Both', 'lbi-exchrates')
                        ),
                        'value'       => 'name'
                    ),
                    array(
                        'type'        => 'checkbox',
                        'heading'     => __( 'Show Captions', 'lbi-exchrates' ),
                        'param_name'  => 'captions',
                        'description' => __( 'Show Rate\'s Captions ( e.g "Buy", "Sell")', 'lbi-exchrates' ),
                        'value'       => array( __( 'Yes', 'lbi-exchrates' ) => 'true' ),
                    ),
                    array(
                        'type'        => 'checkbox',
                        'heading'     => __( 'Show Currency Unit', 'lbi-exchrates' ),
                        'param_name'  => 'unit',
                        'value'       => array( __( 'Yes', 'lbi-exchrates' ) => 'true' ),
                    ),
                    array(
                        'type'        => 'checkbox',
                        'heading'     => __( 'Show Country Flags', 'lbi-exchrates' ),
                        'param_name'  => 'flag',
                        'value'       => array( __( 'Yes', 'lbi-exchrates' ) => 'true' ),
                        'std'         => 'true'
                    ),
                    array(
                        'param_name'  => 'flag_path',
                        'type'        => 'textfield',
                        'heading'     => __( 'Flag Path', 'lbi-exchrates' ),
                        'description' => __( 'Leave empty to use default', 'lbi-exchrates' ) ,
                        'dependency'  => array(
                            'element' => 'flag',
                            'value'   => array( 'true' )
                        ),
                    ),
                    array(
                        'type'        => 'checkbox',
                        'heading'     => __( 'Rates', 'lbi-exchrates' ),
                        'param_name'  => 'fb',
                        'value'       => array( __( 'Show Forex Buying', 'lbi-exchrates' ) => 'true' ),
                    ),
                    array(
                        'type'        => 'checkbox',
                        'param_name'  => 'fs',
                        'value'       => array( __( 'Show Forex Selling', 'lbi-exchrates' ) => 'true' ),
                        'std'         => 'true'
                    ),
                    array(
                        'type'        => 'checkbox',
                        'param_name'  => 'bb',
                        'value'       => array( __( 'Show Banknote Buying', 'lbi-exchrates' ) => 'true' ),
                    ),
                    array(
                        'type'        => 'checkbox',
                        'param_name'  => 'bs',
                        'value'       => array( __( 'Show Banknote Selling', 'lbi-exchrates' ) => 'true' ),
                    ),
                    array(
                        'type'        => 'checkbox',
                        'param_name'  => 'cr',
                        'value'       => array( __( 'Show Cross Rate', 'lbi-exchrates' ) => 'true' ),
                    ),
                    array(
                        'type'        => 'checkbox',
                        'heading'     => __( 'Footer', 'lbi-exchrates' ),
                        'param_name'  => 'showdate',
                        'value'       => array( __( 'Show Date Announced', 'lbi-exchrates' ) => 'true' ),
                    ),
                    array(
                        'type'        => 'checkbox',
                        'param_name'  => 'showsource',
                        'value'       => array( __( 'Show Data Source', 'lbi-exchrates' ) => 'true' ),
                    ),
                    array(
                        'param_name'  => 'class',
                        'type'        => 'textfield',
                        'heading'     => __( 'Class', 'lbi-exchrates' ),
                        'description' => __( 'Extra CSS class', 'lbi-exchrates' )
                    )
                )
            ));
    	}
    }

    if(!function_exists( 'lbi_custom_param_select' ) ) {
        vc_add_shortcode_param( 'lbi_select', 'lbi_custom_param_select' );

        function lbi_custom_param_select( $settings, $value ){

            $_out = '<select class="wpb_vc_param_value" name="' . esc_attr( $settings['param_name'] ) . '">';

            foreach( $settings['values'] as $k => $v ){
                $_out .= '<option value="'.$k.'"';
                if( $k == $value ){
                    $_out .= ' selected';
                }
                $_out .= '>'.$v.'</option>';
            }

            $_out .= '</select>';

            return $_out;

        }
    }

    if(!function_exists( 'lbi_custom_param_multiple' ) ) {
        vc_add_shortcode_param( 'lbi_multiple', 'lbi_custom_param_multiple' );
        function lbi_custom_param_multiple( $settings, $value ){

            if( !is_array( $value ) ){
                $value = explode( ',', $value );
            }

            $_out = '<select ';
            if( !empty( $settings['height'] ) ){
                $_out .= 'style="height:'.$settings['height'].'" ';
            }
            $_out .=  'multiple class="wpb_vc_param_value lbi-multiple-field" name="' . esc_attr( $settings['param_name'] ) . '">';

            foreach( $settings['values'] as $k => $v ){
                $_out .= '<option value="'.$k.'"';
                if( in_array( $k, $value ) ){
                    $_out .= ' selected';
                }
                $_out .= '>'.$v.'</option>';
            }

            $_out .= '</select><br /><button class="button" onclick="jQuery(this.parentNode).find(\'.lbi-multiple-field option:selected\').removeAttr(\'selected\');"><i class="fa fa-times"></i> ' . __('Clear Selected','lbi-exchrates') . '</button>';

            return $_out;

        }
    }
}
?>