<?php

if( class_exists( 'Xoo_Aff_fields' ) ){

	class Xoo_Wl_Aff_Fields{

		public $fields;

		public function __construct(){
			add_action( 'xoo_aff_waitlist-woocommerce_add_predefined_fields', array( $this, 'add_wl_predefined_fields' ) );
		}


		public function add_wl_predefined_fields(){
			$this->fields = xoo_wl()->aff->fields;
			$this->predefined_field_useremail();
			$this->predefined_field_quantity();
		}


		
		public function predefined_field_useremail(){

			$field_type_id = $field_id = 'xoo_wl_user_email';

			$this->fields->add_type(
				$field_type_id,
				'email',
				'User Email',
				array(
					'is_selectable' => 'no',
					'can_delete'	=> 'no',
					'icon' 			=> 'fas fa-at',
				)
			);

			$setting_options = $this->fields->settings['xoo_aff_email'];

			unset($setting_options['active']);
			unset($setting_options['required']);

			$my_settings = array(	
				'icon' => array(
					'value' => 'fas fa-at'
				),
				'placeholder' => array(
					'value' => 'Email',
				),
				'unique_id' => array(
					'disabled' => 'disabled',
				),
			);

			$setting_options = array_merge(
				$setting_options,
				$my_settings
			);


			$this->fields->create_field_settings(
				$field_type_id,
				$setting_options
			);

			$this->fields->add_field(
				$field_id,
				$field_type_id,
				array(
					'active' 	=> 'yes',
					'required' 	=> 'yes',
					'unique_id' 	=> $field_id,
				)			
			);

		}

		public function predefined_field_quantity(){

			$field_type_id = $field_id = 'xoo_wl_required_qty';

			$this->fields->add_type(
				$field_type_id,
				'number',
				'Quantity',
				array(
					'is_selectable' => 'no',
					'can_delete'	=> 'no',
					'icon' 			=> 'fas fa-cart-arrow-down',
				)
			);

			$setting_options = $this->fields->settings['xoo_aff_number'];

			$my_settings = array(	
				'icon' => array(
					'value' => 'fas fa-cart-arrow-down'
				),
				'placeholder' => array(
					'value' => 'Quantity',
				),
				'unique_id' => array(
					'disabled' => 'disabled',
				)
			);

			//Older version check quantity field
			if( get_option( 'xoo-wl-gl-enqty' ) !== false && !get_option( 'xoo-wl-gl-enqty' ) ){
				$my_settings[]['active'] = array(
					'value' => 'no'
				);
			}

			$setting_options = array_merge(
				$setting_options,
				$my_settings
			);

			$this->fields->create_field_settings(
				$field_type_id,
				$setting_options
			);

			$this->fields->add_field(
				$field_id,
				$field_type_id,
				array(
					'unique_id' 	=> $field_id,
				)			
			);

		}



	}

	new Xoo_Wl_Aff_Fields();

}


?>