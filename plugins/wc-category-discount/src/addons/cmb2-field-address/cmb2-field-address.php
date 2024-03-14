<?php
/**
 * @package	CMB2\Field_Address
 * @author 	scottsawyer
 * @copyright	Copyright (c) scottsawyer
 *
 * Plugin Name: CMB2 Field Type: Address
 * Plugin URI: https://github.com/scottsawyer/cmb2-field-address
 * Github Plugin URI: https://github.com/scottsawyer/cmb2-field-address
 * Description: CMB2 field type to create an address.
 * Version: 1.0
 * Author: scottsawyer
 * Author URI: https://www.scottsawyerconsulting.com
 * License: GPLv2+
 */

if ( !defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'CMB2_Field_Address' ) ) {
  /**
   * Class CMB2_Field_Address
   */
  class CMB2_Field_Address {
    
    /**
     * Current version number
     */
    const VERSION = '1.0.0';
	  /**
		 * List of states. To translate, pass array of states in the 'state_list' field param.
		 *
		 * @var array
		 */
	  protected static $discount_list = ['flat'=>'Flat','percentage'=>'Percentage'];
    /**
     * Initialize the plugin
     */
    public function __construct() {
      add_action( 'cmb2_render_address', [$this, 'render_address'], 10, 5 );
      add_filter( 'cmb2_sanitize_address', [$this, 'maybe_save_split_values'], 12, 4 );
      add_filter( 'cmb2_sanitize_address', [$this, 'sanitize'], 10, 5 );
      add_filter( 'cmb2_types_esc_address', [$this, 'escape'], 10, 4 );
    }    

    //public static function class_name() { return __CLASS__; }

		/**
		 * Handles outputting the address field.
		 */
    public static function render_address( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {

    	$field_escaped_value = wp_parse_args( $field_escaped_value, [
    		    'minimum-product'	=> '',
	    	    'maximum-product' => '',
				'discount-type' => '',
				'amount'      => '',
				]
			);

        $discount_label = 'Discount Type';

        $discount_list = self::$discount_list;

        // Add the "label" option. Can override via the field text param
        $discount_list = ['' => esc_html( 'Select Type' ) ] + $discount_list;
        $discount_options = '';
        foreach ( $discount_list as $abrev => $discount_type ) {
            $discount_options .= '<option value="' . $abrev . '" ' . selected( $field_escaped_value['discount-type'], $abrev, false ) . '>' . $discount_type . '</option>';
        }

			?>

			<div style="overflow: hidden;">

                <div class="alignleft"><p><label for="<?= $field_type_object->_id( '_minimum-product', false ); ?>"><?= esc_html(  'Min Products' ); ?></label></p>
                    <?= $field_type_object->input( [
                        'type'	=> 'number',
                        'class' => 'cmb_text_small',
                        'name'  => $field_type_object->_name( '[minimum-product]' ),
                        'id'    => $field_type_object->_id( '_minimum_product' ),
                        'value' => $field_escaped_value['minimum-product'],
                        'min' => '1',
                        'desc'  => '',
                    ] ); ?>
                </div>
                <div class="alignleft"><p><label for="<?= $field_type_object->_id( '_maximum-product', false ); ?>"><?= esc_html(  'Max Products' ); ?></label></p>
                    <?= $field_type_object->input( [
                        'type'	=> 'number',
                        'class' => 'cmb_text_small',
                        'name'  => $field_type_object->_name( '[maximum-product]' ),
                        'id'    => $field_type_object->_id( '_maximum_product' ),
                        'value' => $field_escaped_value['maximum-product'],
                        'min' => '1',
                        'desc'  => '',
                    ] ); ?>
                </div>

                <div class="alignleft"><p><label for="<?= $field_type_object->_id( '_discount_type', false ); ?>'"><?= esc_html(  $discount_label ); ?></label></p>

                    <?= $field_type_object->select( [
                        'name'    => $field_type_object->_name( '[discount-type]' ),
                        'id'      => $field_type_object->_id( '_discount_type' ),
                        'value' 	=> $field_escaped_value['discount-type'],
                        'options' => $discount_options,
                        'desc'    => '',
                        'onchange' => 'showDiscountRange(this);',
                    ] ); ?>

                </div>
                <div class="alignleft"><p><label for="<?= $field_type_object->_id( '_amount', false ); ?>'"><?= esc_html( 'Amount' ); ?></label></p>
                    <?= $field_type_object->input( [
                        'type'  => 'number',
                        'class' => 'cmb_text_small',
                        'name'  => $field_type_object->_name( '[amount]' ),
                        'id'    => $field_type_object->_id( '_amount' ),
                        'value' => $field_escaped_value['amount'],
                        'min' => '1',
                        'desc'  => '',
                    ] ); ?>
                </div>

			</div>
			<?php
			$field_type_object->_desc( 'true' );
			
    }

    /**
		 * Optionally save the Address values into separate fields
		 */
		public static function maybe_save_split_values( $override_value, $value, $object_id, $field_args ) {
			if ( ! isset( $field_args['split_values'] ) || ! $field_args['split_values'] ) {
				// Don't do the override
				return $override_value;
			}
			$address_keys = ['minimum-product', 'maximum-product', 'discount-type', 'amount'];
			foreach ( $address_keys as $key ) {
				if ( ! empty( $value[ $key ] ) ) {
					update_post_meta( $object_id, $field_args['id'] . 'addr_'. $key, sanitize_text_field( $value[ $key ] ) );
				}
			}
			remove_filter( 'cmb2_sanitize_address', [ $this, 'sanitize' ], 10, 5 );
			// Tell CMB2 we already did the update
			return true;
		}

		public static function sanitize( $check, $meta_value, $object_id, $field_args, $sanitize_object ) {
			// if not repeatable, bail out.
			if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
				return $check;
			}
			foreach ( $meta_value as $key => $val ) {
				$meta_value[ $key ] = array_filter( array_map( 'sanitize_text_field', $val ) );
			}
			return array_filter($meta_value);
		}


		public static function escape( $check, $meta_value, $field_args, $field_object ) {
			// if not repeatable, bail out.
			if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
				return $check;
			}
			foreach ( $meta_value as $key => $val ) {
				$meta_value[ $key ] = array_filter( array_map( 'esc_attr', $val ) );
			}
			return array_filter($meta_value);
		}
	}
	$cmb2_field_address = new CMB2_Field_Address();
}

?>