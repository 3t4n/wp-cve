<?php
trait Stored {

	private function pincode_stored($ID = 0){
		if( !empty($_POST['submit']) && current_user_can('manage_options')):

			if ( ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_add_pincode_form'] ), 'add_pincode_form' ) ) {
				die(  'Security check failed'  ); 
			}

			$data['pincode'] 		= isset($_POST['pincode']) ? sanitize_text_field( $_POST['pincode'] ) : ''; 
			$data['city'] 			= isset($_POST['city']) ? sanitize_text_field( $_POST['city'] ) : '';
			$data['state'] 			= isset($_POST['state']) ? sanitize_text_field( $_POST['state'] ) : '';
			$data['country'] 		= isset($_POST['country']) ? sanitize_text_field( $_POST['country'] ) : '';
			$data['dod'] 			= isset($_POST['dod']) ? sanitize_text_field( $_POST['dod'] ) : '';
			$data['cod'] 			= isset($_POST['cod']) ? sanitize_text_field( $_POST['cod'] ) : '';

			if($_POST['submit'] == 'Add' && isset($data['pincode'])):
				
				if(empty($this->pincode::select($data['pincode'],'pincode'))):
					
					$result = $this->pincode::insert($data);
					$result == 1 ? $this->phoeniixx_pincode_zipcode_print_message('success','Data Successfully Added') : $this->phoeniixx_pincode_zipcode_print_message('error','Something Went Wrong Please Try Again With Valid Data.');
				else:
					$this->phoeniixx_pincode_zipcode_print_message('error','Pincode is already added');
				endif;

			elseif($_POST['submit'] == 'Update' && isset($data['pincode'])):
				
				$result = $this->pincode::update($data,$ID);
				$result == 1 ? $this->phoeniixx_pincode_zipcode_print_message('success','Data Successfully Updated') : $this->phoeniixx_pincode_zipcode_print_message('error','Data Won\'t Modified.');
			endif;
		endif;

		$this->include_pincode_file($ID);
	}

	public function setting_stored(){
		if( isset($_POST['submit']) && current_user_can( 'manage_options' ) ):
	
			$nonce_check = sanitize_text_field( $_POST['_wpnonce_check_pincode_setting'] );
			
			if ( ! wp_verify_nonce( $nonce_check, 'check_pincode_setting' ) ) {
				die( 'Security check failed' ); 
			}

			$result = $this->setting::update($_POST,1);
			$result == '1' ? $this->phoeniixx_pincode_zipcode_print_message('success','Data Successfully Updated') : $this->phoeniixx_pincode_zipcode_print_message('error','Something Went Wrong Please Try Again With Valid Data.');
		endif;

		$this->include_setting_file();
	}

	private function include_pincode_file($ID){
		$get_data = $this->pincode::select($ID,'id');
		$get_data = !empty($get_data) ? $get_data[0] : [];
		$country  = isset($get_data['country']) ? sanitize_text_field($get_data['country']) : '';
		$create   = PHOEN_PINCODE_ZIPCODE_PATH.'admin/pages/create.php';
		file_exists($create) ? require_once($create) : 'FILE NOT FOUND';
	}

	private function include_setting_file(){
		$setting 		= PHOEN_PINCODE_ZIPCODE_PATH.'admin/pages/setting.php';
		$setting_data 	= $this->setting::get();
		file_exists($setting) ? require_once($setting) : 'FILE NOT FOUND';
	}
}
?>