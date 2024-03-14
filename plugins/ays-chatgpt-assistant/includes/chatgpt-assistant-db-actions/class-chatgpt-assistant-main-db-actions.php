<?php

if( ! class_exists( 'ChatGPT_Assistant_Main_DB_Actions' ) ){
	ob_start();

	/*
     ChatGPT_Assistant_Main_DB_Actions Stands for the implementation of main functions with DataBase
    */ 
    class ChatGPT_Assistant_Main_DB_Actions {
	    
		protected $db_table;
	    protected $plugin_name;	    
	    
        public function __construct( $plugin_name , $db_table ) {
	        $this->plugin_name = $plugin_name;
	        $this->db_table = $db_table;
        }

        public function store_data(){}
            
	    /**
	     * @return array
	     */
        public function get_all_data(){
            global $wpdb;

            $sql = "SELECT * FROM " . $this->db_table;

            $results = $wpdb->get_results($sql, ARRAY_A);

            if( count( $results ) > 0 ){
                return $this->convert_settings_data($results);
            }else{
                return array();
            }
        }

	    /**
	     * Get record meta by record id and meta key
	     *
	     * @since       1.0.0
	     * @access      public
	     *
	     * @param       $id
	     * @param       $meta_key
	     *
	     * @return      false|array
	     */
	    public function get_setting( $meta_key, $table = '' ){
		    global $wpdb;
			$table = $table == '' ? $this->db_table : $table;

		    if( is_null( $meta_key ) || trim( $meta_key ) === '' ){
			    return false;
		    }
		    $sql = "SELECT meta_value FROM ". $table ." WHERE meta_key = '".$meta_key."'";
		    $result = $wpdb->get_var($sql);

		    if($result != ""){
			    return $result;
		    }

		    return false;
	    }

		public function update_setting( $meta_key, $meta_value, $column_name = "meta_value" , $where_column = "meta_key", $note = "", $options = "", $table = '' ){
		    global $wpdb;

		    if( is_null( $meta_key ) || trim( $meta_key ) === '' ){
			    return false;
		    }

		    $value = array(
			    $column_name  => $meta_value,
		    );

			$value_s = array( '%s' );
		    if($note != null){
			    $value['note'] = $note;
			    $value_s[] = '%s';
		    }

		    if($options != null){
			    $value['options'] = $options;
			    $value_s[] = '%s';
		    }

			if($where_column == 'id'){
				$where_value = array( '%d' );
			}
			else{
				$where_value = array( '%s' );
			}

			$table = $table == '' ? $this->db_table : $table;

			$result = $wpdb->update(
			    $table,
			    $value,
			    array(
				    $where_column => $meta_key,
			    ),
			    $value_s,
			    $where_value
		    );

		    if($result >= 0){
			    return true;
		    }

		    return false;
	    }

		public function add_setting( $meta_key, $meta_value, $note = "", $options = "" ){
            global $wpdb;
			
            if( is_null( $meta_key ) || trim( $meta_key ) === '' ){
                return false;
            }

            $result = $wpdb->insert(
                $this->db_table,
                array(
                    'meta_key'    => $meta_key,
                    'meta_value'  => $meta_value,
                    'note'        => $note,
                    'options'     => $options
                ),
                array( '%s', '%s', '%s', '%s' )
            );

            if($result >= 0){
                return true;
            }

            return false;
        }

		public function get_data() {
            global $wpdb;

            // if ( !isset($id) || $id > 1) {
            //     return;
            // }

            $sql = "SELECT * FROM ". $this->db_table;
            $result = $wpdb->get_row( $sql, ARRAY_A );

            // if ( ! $result ) {
            //     return;
            // }
            
            $data = array();

            $data['id'] = isset( $result['id'] ) && $result['id'] != '' ? intval( $result['id'] ) : 0;
            $data['api_key'] = isset( $result['api_key'] ) && $result['api_key'] != '' ? sanitize_text_field( $result['api_key'] ) : '';

            $options = isset( $result['options'] ) && $result['options'] != '' ? json_decode(sanitize_text_field( $result['options'] )) : '';
            
            $data['options'] = json_encode($options);
            
            if ( $data ) {
                return $data;
            }
        }

		public function convert_settings_data( $settings ){

            if( ! is_array( $settings ) || empty( $settings ) ){
                return array();
            }

            $data = array();
            foreach ( $settings as $k => $setting ) {
                $data[ CHATGPT_ASSISTANT_OPTIONS_PREFIX.$setting['meta_key'] ] = $setting['meta_value'];
            }

            return $data;
        }
    }
}
