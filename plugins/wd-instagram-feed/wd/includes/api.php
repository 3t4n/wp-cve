<?php
    if ( !defined( 'ABSPATH' ) ) {
        exit;
    }

    class TenWebLibApi{
        ////////////////////////////////////////////////////////////////////////////////////////
        // Events                                                                             //
        ////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////
        // Constants                                                                          //
        ////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////
        // Variables                                                                          //
        ////////////////////////////////////////////////////////////////////////////////////////

        public $config ;
        public $userhash = array();
     
 
        ////////////////////////////////////////////////////////////////////////////////////////
        // Constructor & Destructor                                                           //
        ////////////////////////////////////////////////////////////////////////////////////////
        public function __construct( $config = array() ) {
            $this->config = $config;
            $this->userhash = $this->get_userhash();
        }
        ////////////////////////////////////////////////////////////////////////////////////////
        // Public Methods                                                                     //
        ////////////////////////////////////////////////////////////////////////////////////////

       
        public function get_remote_data( $id ) {
            $remote_data_path = TEN_WEB_LIB_API_PLUGIN_DATA_PATH . '/' . $this->userhash;
            //wp_remote_get is a native WordPress function
            /* phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get */
            $request = wp_remote_get( ( str_replace( '_id_', $id, $remote_data_path ) ) );
		
            if ( !is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200 ) {
                return json_decode($request['body'], true);
            }
            return false;
        } 


        public function get_userhash(){
            $wd_options =  $this->config;
            $userhash = 'nohash';
            if ( file_exists( $wd_options->plugin_dir . '/.keep') && is_readable( $wd_options->plugin_dir . '/.keep' ) ) {
                //phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fopen
                $f = fopen( $wd_options->plugin_dir . '/.keep', 'r' );
                $userhash = fgets( $f );
                fclose( $f );
            }    
            return $userhash;
        }
		
		public function get_hash(){
            //wp_remote_get is a native WordPress function
            /* phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get, WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders, WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___SERVER__REMOTE_ADDR__, WordPress.Security.ValidatedSanitizedInput.InputNotValidated  */
			$response = wp_remote_get("https://api.web-dorado.com/hash/" . sanitize_text_field($_SERVER['REMOTE_ADDR']) . "/" . sanitize_text_field($_SERVER['HTTP_HOST']));
			
			$response_body = ( !is_wp_error($response) && isset($response["body"])) ? json_decode($response["body"], true) : null;
			
			if(is_array($response_body)){
				$hash = $response_body["body"]["hash"];
			}
			else{
				$hash = null;
			}

			return $hash;
		}
   
        
        ////////////////////////////////////////////////////////////////////////////////////////
        // Getters & Setters                                                                  //
        ////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////
        // Private Methods                                                                    //
        ////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////
        // Listeners                                                                          //
        ////////////////////////////////////////////////////////////////////////////////////////
        
    }  