<?php
class Setting{

	private static $tableName;
	private static $wPDB;

	public function __construct(){
		global $table_prefix,$wpdb;
		self::$tableName = $table_prefix.'pincode_zipcode_setting_free';
		self::$wPDB 	 = $wpdb;
	}

	public static function get(){
		$get = self::$wPDB->get_results( self::$wPDB->prepare( "SELECT * FROM ".self::$tableName),ARRAY_A  ); 
		return $get[0];
	} 	

	public static function update($request,$ID){
		$update = [
                'enter_pincode_heading'     => $request['enter_pincode_heading'],
                'check_btn_name'            => $request['check_btn_name'],
                'available_pincode_heading' => $request['available_pincode_heading'],
                'change_btn_name'           => $request['change_btn_name'],
                'show_state'                => $request['show_state'],
                'show_city'                 => $request['show_city'],
                'dod_heading'               => $request['dod_heading'],
                'enable_delivery_date'		=> $request['enable_delivery_date'],
                'delivery_date_help_text'   => $request['delivery_date_help_text'], 
                'box_bg_color'              => $request['box_bg_color'], 
                'label_txt_color'           => $request['label_txt_color'], 
                'btn_bg_color'              => $request['btn_bg_color'], 
                'btn_txt_color'             => $request['btn_txt_color'],
                'pincode_verify_error'      => $request['pincode_verify_error'],
                'pincode_input_error'       => $request['pincode_input_error'],
                'wrong_pincode_error'       => $request['wrong_pincode_error'],
                'cod_help_text'				=> $request['cod_help_text'],
                'cod_heading'				=> $request['cod_heading'],
            ];

		return self::$wPDB->update( self::$tableName, $update,array('id'=>$ID));
	}
}
?>