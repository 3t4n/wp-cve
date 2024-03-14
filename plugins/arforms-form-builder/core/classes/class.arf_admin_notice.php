<?php
if (!class_exists('ARF_adminnotice')) {

    class ARF_adminnotice
    {
        var $arf_db_updates = array(
            '6.0.2' => array(
                'arf_merge_setting_tables',
            ),
        );

        public function __construct() {
            
            /* add_action('init', array($this, 'arf_install_action'));
            
            add_action( 'run_arf_update_callback', array( $this, 'arf_update_callback_func')); */
        }

        /* for setting migration table code  */
        /* function arf_merge_setting_tables(){

            global $wpdb, $ARFLiteMdlDb;

            $sql = "CREATE TABLE IF NOT EXISTS `{$ARFLiteMdlDb->arf_setting_table}`(
                `setting_id` int(11) NOT NULL AUTO_INCREMENT,
                `setting_name` varchar(255) NOT NULL,
                `setting_value` TEXT DEFAULT NULL,
                `setting_type` varchar(255) DEFAULT NULL,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`setting_id`)
                ) {$charset_collate}";
                
            dbDelta( $sql );
            if ( $wpdb->last_error !== '' ) {
                update_option( 'ARF_ERROR_' . time() . rand(), 'ERROR===>' . htmlspecialchars( $wpdb->last_result, ENT_QUOTES ) . 'QUERY===>' . htmlspecialchars( $wpdb->last_query, ENT_QUOTES ) ); 
            } 

            if ( file_exists( WP_PLUGIN_DIR . '/arforms/arforms.php' ) && ! is_plugin_active( 'arforms/arforms.php' ) ) {

                $arforms_setting_table_data = get_option('arf_options');

                if( !empty( $arforms_setting_table_data )){

                    $arforms_setting_data = maybe_unserialize( $arforms_setting_table_data);
                }

            } else {

                $arformslite_setting_table_data = get_option('arflite_options');
    
                if( !empty( $arformslite_setting_table_data )){
    
                    $arformslite_setting_data = maybe_unserialize( $arformslite_setting_table_data);
                    $added_date = current_time('mysql');
                        
                    foreach( $arformslite_setting_data as $arf_key=>$arf_val){

                        $wpdb->query($wpdb->prepare("INSERT INTO ".$ARFLiteMdlDb->arf_setting_table."(setting_name, setting_value, setting_type, created_at) VALUES ( %s, %s, %s, %s)", $arf_key, $arf_val, 'arforms_general_settings', $added_date));

                        if ( $wpdb->last_error !== '' ) {
                            update_option( 'ARF_ERROR_' . time() . rand(), 'ERROR===>' . htmlspecialchars( $wpdb->last_result, ENT_QUOTES ) . 'QUERY===>' . htmlspecialchars( $wpdb->last_query, ENT_QUOTES ) ); 
                        } 
                    }
                }
            }

        } */

        function get_db_update_callbacks() {
            return $this->arf_db_updates;
        }

        function arf_update_callback_func( $update_callback ){

            update_option('check_function_inside_this_call_'.__LINE__, '75 line');

            //include_once ARFLITE_FORMPATH . '/core/classes/class.arf_callback_function.php';
        }

        function arf_update_func_call() {

            global $wpdb, $ARFLiteMdlDb;

            /* $get_all_form_data = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $ARFLiteMdlDb->forms . ' ORDER BY id' ) );

            $all_inner_field_data = array();
            if( !empty( $get_all_form_data )){

                foreach( $get_all_form_data as $key=>$val ){

                    $field_options = maybe_unserialize($val->options);
                    $inner_field_data_order = $field_options['arf_field_order'];
                    $all_inner_field_data[ $val->id ] =  json_decode($inner_field_data_order, true);
                }
            }

            $get_all_fields = $wpdb->get_results( $wpdb->prepare('SELECT * FROM ' . $ARFLiteMdlDb->fields . ' ORDER BY id'));
            $add_inner_field_order = array();
            if( !empty( $get_all_fields )){
                foreach( $get_all_fields as $all_key=>$all_val ){
                    $field_id = $all_val->id;
                    $form_id = $all_val->form_id;
                    $add_inner_field_order[ $field_id ] = $form_id;
                    
                }
            }

            $new_field_order = array();
            foreach( $all_inner_field_data as $inner_field_key => $inner_field_val ){

                foreach( $inner_field_val as $inner_key=>$inner_val){

                    foreach( $add_inner_field_order as $key=>$val ){
                        if( $key == $inner_key ){
                            $new_field_order[ $key ] = $inner_val; 
                        }

                    }
                }   
            }

            foreach( $new_field_order as $ar_field_key=>$ar_field_val){
                global $wpdb, $ARFLiteMdlDb;

                $wpdb->query($wpdb->prepare("UPDATE ".$ARFLiteMdlDb->fields." SET arf_field_order = ".$ar_field_val."  WHERE id= %d", $ar_field_key));

                if ( $wpdb->last_error !== '' ) {
                    update_option( 'ARF_ERROR_' . time() . rand(), 'ERROR===>' . htmlspecialchars( $wpdb->last_result, ENT_QUOTES ) . 'QUERY===>' . htmlspecialchars( $wpdb->last_query, ENT_QUOTES ) ); 
                } 

            } */

            update_option('check_function_inside_this_call_'.__LINE__, '82 line');
            $current_db_version = get_option( 'arf_db_version' );
            $loop               = 0;
            
            foreach ( $this->get_db_update_callbacks() as $version => $update_callbacks ) {

                if ( version_compare( $current_db_version, $version, '<' ) ) {  

                    foreach( $update_callbacks as $update_callback ){

                        update_option('check_function_inside_this_call_'.__LINE__, '92 line');
                        wp_schedule_single_event( ( current_time('timestamp',true) + $loop ), 'run_arf_update_callback' );
                    }

                    /* foreach ( $update_callbacks as $update_callback ) {
                        WC()->queue()->schedule_single(                
                            time() + $loop,
                            'woocommerce_run_update_callback',
                            array(
                                'update_callback' => $update_callback,
                            ),
                            'woocommerce-db-updates'
                        );
                        $loop++;
                    } */
                }
            }

            // After the callbacks finish, update the db version to the current ARF version.
           /*  $current_wc_version = WC()->version;
            if ( version_compare( $current_db_version, $current_wc_version, '<' ) &&
                ! WC()->queue()->get_next( 'woocommerce_update_db_to_current_version' ) ) {
                WC()->queue()->schedule_single(
                    time() + $loop,
                    'woocommerce_update_db_to_current_version',
                    array(
                        'version' => $current_wc_version,
                    ),
                    'woocommerce-db-updates'
                );
            } */
        }

        function arf_install_action(){

            if ( ! empty( $_GET['do_update_arforms'] ) ) { 

                update_option('check_function_inside_this_call_'.__LINE__, '129 line');
                check_admin_referer( 'arf_db_update', 'arf_db_update_nonce' );
                $this->arf_update_func_call();
    
            }
        }

        function arf_display_update_notice(){

            $arforms_migration_flag = get_option('arforms_process_db_update');

            if( !empty( $arforms_migration_flag ) && 1 == $arforms_migration_flag ){

                $update_url = wp_nonce_url(
                    add_query_arg( 'do_update_arforms', 'true', admin_url( 'admin.php?page=ARForms-status' ) ),
                    'arf_db_update',
                    'arf_db_update_nonce'
                );

                echo '<div id="arf_update_migration_message" class="updated">';
                    echo '<p>';
                        echo '<strong>'.esc_html("ARForms database update required","ARForms" ).'</strong>';
                    echo '</p>';
                    echo '<p>';
                        esc_html_e( 'ARForms has been updated! To keep things running smoothly, we have to update your database to the newest version.', 'arforms-form-builder' );
                    echo '</p>';

                    echo '<p class="submit">';
                        echo '<a href='.esc_url( $update_url ).' class="arf-update-now button-primary" style="margin-right:20px;">';
                            echo esc_html_e( 'Update ARForms Database', 'arforms-form-builder' );
                        echo '</a>';
                        /* echo '<a href="" class="button-secondary">';
                            echo esc_html_e( 'Learn more about updates', 'ARForms' );
                        echo '</a>'; */
                    echo '</p>';
                echo '</div>';
                
            }
        }
    }
}
global $ARF_adminnotice;
$ARF_adminnotice = new ARF_adminnotice();