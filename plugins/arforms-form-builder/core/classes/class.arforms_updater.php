<?php

class arforms_updater{

    var $arforms_db_updates = array(
        'lite' => array(
            '1.5.9' => array(
                array(
                    'type' => 'migrate_tables',
                    'hook' => 'arforms_merge_tables_159',
                    'is_pro_check' => 1,
                    'execute_with_pro' => 0,
                    'use_pro_data' => 0,
                    'hook_order' => 0,
                ),
                array(
                    'type' => 'generic',
                    'hook' => 'arforms_merge_settings_159',
                    'is_pro_check' => 1,
                    'execute_with_pro' => 1,
                    'use_pro_data' => 1,
                    'hook_order' => 1,
                ),
                array(
                    'type' => 'new_columns',
                    'hook' => 'arforms_field_order_new_column_159',
                    'hook_order' => 2,
                    'is_pro_check' => 1,
                    'execute_with_pro' => 1,
                    'use_pro_data' => 1,
                    'dependent_hook' => 'arforms_merge_tables_159'
                )
            ),
            '1.6.0' => array(
                array(
                    'type' => 'generic',
                    'hook' => 'arforms_merge_settings_160',
                    'is_pro_check' => 1,
                    'execute_with_pro' => 1,
                    'use_pro_data' => 1,
                    'hook_order' => 1,
                ),
            )
        )
    );

    function __construct(){

        $this->arforms_db_updates = apply_filters( 'arforms_modify_db_updates_data', $this->arforms_db_updates );

        add_action( 'admin_notices', array( $this, 'arf_display_update_notice' ) );
        add_action( 'plugins_loaded', array( $this, 'arforms_check_upgrade' ) );

    }

    function arf_display_update_notice(){

        global $arformsmain;

        $arforms_migration_flag = get_option('arforms_process_db_update');

        if( !empty( $arforms_migration_flag ) && 1 == $arforms_migration_flag ){

            $update_url = wp_nonce_url(
                add_query_arg( 'do_update_arforms', 'true', admin_url( 'admin.php?page=ARForms-settings' ) ),
                'arf_db_update',
                'arf_db_update_nonce'
            );
            if( $arformsmain->arforms_is_pro_active())
            {
                $update_url = wp_nonce_url(
                    add_query_arg( 'do_update_arforms', 'true', admin_url( 'admin.php?page=ARForms-settings&arflite_settings_nonce=' . wp_create_nonce( 'arflite_settings_nonce' ) ) ),
                    'arf_db_update',
                    'arf_db_update_nonce'
                );

            } else {

                $update_url = wp_nonce_url(
                    add_query_arg( 'do_update_arforms', 'true', admin_url( 'admin.php?page=page=ARForms-settings' ) ),
                    'arf_db_update',
                    'arf_db_update_nonce'
                );
            }

            echo '<div id="arf_update_migration_message" class="updated arf_migration_update">';
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
                echo '</p>';
            echo '</div>';
            
        }
    }

    function arforms_check_upgrade(){

        if( !empty( $_GET['do_update_arforms'] ) ) {
            
            
            $arforms_process_db_update = get_option( 'arforms_process_db_update' );

            if( !empty( $arforms_process_db_update ) && 1 == $arforms_process_db_update ){
                /** set flag for the processing */
                global $arflitedbversion, $arformsmain, $tbl_arf_settings;

                $scheduler_data = $this->arforms_db_updates;

                $lite_version_schedular = $scheduler_data['lite'];

                $schedular_actions = array();
                
                foreach( $lite_version_schedular as $version => $schedule_details ){

                    if( version_compare( $arflitedbversion, $version, '<=') ){

                        $counter = 0;
                        foreach( $schedule_details as $process_data ){
                                
                            $arforms_hook_name  = $process_data['hook'];

                            $schedular_actions[ $version ][] = $arforms_hook_name;

                            do_action( $arforms_hook_name, $process_data );

                        }
                    }
                }
                $arforms_schedular_data = $arformsmain->arforms_get_settings( 'arforms_schedular_data', 'scheduling_settings' );

                if( empty( $arforms_schedular_data ) ){
                    $arformsmain->arforms_update_settings( 'arforms_schedular_data', json_encode( $schedular_actions ), 'scheduling_settings' );
                }

                do_action( 'arforms_process_external_upgrade', $scheduler_data );

                update_option( 'arforms_process_db_update', 2 );

            }
        }

    }
}

new arforms_updater();