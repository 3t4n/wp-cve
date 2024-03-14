<?php

if( !class_exists( 'Chatgpt_Assistant_DB_Actions' ) ){
    ob_start();

    /*
     Chatgpt_Assistant_DB_Actions Stands for storing data in 
     Setting page extending main functions from ChatGPT_Assistant_Main_DB_Actions
    */ 
    class Chatgpt_Assistant_DB_Actions extends ChatGPT_Assistant_Main_DB_Actions{

        /**
	     * Insert or update record by id
         *
	     * @since       1.0.0
	     * @access      public
         *
         * @redirect    to specific page based on clicked button
	     * @param       $data
         *
	     * @return      false|void
	     */
        public function store_data() {
            global $wpdb;
            if( is_null( $_REQUEST ) || empty($_REQUEST) ){
                return false;
            }

            $success = 0;
            $name_prefix = 'ays_chatgpt_assistant_';

            $id = isset( $_REQUEST[ $name_prefix . 'id' ] ) && $_REQUEST[ $name_prefix . 'id' ] != '' ? sanitize_text_field( $_REQUEST[ $name_prefix . 'id' ] ) : 0;
            $api_key = isset( $_REQUEST[ $name_prefix . 'api_key' ] ) && $_REQUEST[ $name_prefix . 'api_key' ] != '' ? sanitize_text_field( $_REQUEST[ $name_prefix . 'api_key' ] ) : '';

            $options = array();
            $message = '';
            if( $id == 0 ) {
                $result = $wpdb->insert(
                    $this->db_table,
                    array(
                        'api_key'           => $api_key,
                        'options'           => json_encode( $options ),
                    ),
                    array(
                        '%s', // api_key
                        '%s', // options
                    )
                );

                $inserted_id = $wpdb->insert_id;

                $message = 'saved';
            } else {
                $result = $wpdb->update(
                    $this->db_table,
                    array(
                        'api_key'           => $api_key,
                        'options'           => json_encode( $options ),
                    ),
                    array( 'id' => $id ),
                    array(
                        '%s', // api_key
                        '%s', // options
                    ),
                    array( '%d' )
                );

                $inserted_id = $id;

                $message = 'updated';
            }

            if(isset($_REQUEST['rMethod']) && $_REQUEST['rMethod'] == "GET"){
                return $message;
            }
            if ( $result >= 0  ) {
                $url = esc_url_raw( add_query_arg( array(
                    // "id"        => $inserted_id,
                    "status"    => $message
                ) ) );

                wp_redirect( $url );
                exit;
            }
        }
    }
}