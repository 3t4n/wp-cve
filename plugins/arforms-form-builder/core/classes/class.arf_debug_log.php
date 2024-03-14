<?php
class arfdebuglog{
	
	function __construct() {

        global $wpdb,$tbl_arforms_debug_log_setting,$arfliteversion;

        add_action('wp_ajax_arforms_save_debug_log_setting_data', array( $this, 'arforms_save_debug_log_settings_details' ));

        add_action('wp_ajax_arforms_view_debug_log', array( $this, 'arforms_view_debug_log_func' ));

        add_action('wp_ajax_arforms_download_debug_log_file', array( $this, 'arforms_download_debug_log_func_file' ));

        add_action('wp_ajax_arforms_clear_debug_log_data', array( $this, 'arforms_clear_debug_log_data_func' ));

        add_action('arforms_debug_log_entry', array( $this, 'arforms_debug_logs_func' ), 10, 5);

        add_action('admin_init', array( $this, 'arforms_email_log_download_file' ));

        add_action( 'admin_enqueue_scripts', array( $this, 'arprice_load_scripts') );

        $tbl_arforms_debug_log_setting    = $wpdb->prefix . 'arf_debug_log_setting';
	}
    function arprice_load_scripts(){
        global $arfliteversion;

        if( empty( $_GET['page'] ) || ( !empty( $_GET['page'] ) && 'ARForms-log' != $_GET['page'] ) ){
            return;
        }

        wp_enqueue_script( 'bootstrap-moment-with-locales' );

        wp_enqueue_script( 'bootstrap-datetimepicker' );

        wp_register_style( 'bootstrap-datetimepicker', ARFLITEURL . '/bootstrap/css/bootstrap-datetimepicker.css', array(), $arfliteversion );

        wp_enqueue_style( 'bootstrap-datetimepicker' );

        wp_register_script( 'tipso', ARFLITEURL . '/js/tipso.min.js', array(), $arfliteversion );

        wp_enqueue_script( 'tipso' );

        wp_register_style( 'tipso', ARFLITEURL . '/css/tipso.min.css', array(), $arfliteversion );

        wp_enqueue_style( 'tipso' );

        wp_register_script('arforms_debug_log', ARFLITEURL . '/js/arforms_debug_log.js', array(), $arfliteversion);

        wp_enqueue_script('arforms_debug_log');

        $arforms_log_i18n_data = array(
            'arf_log_i18n' => array(
                'nonce_msg' => esc_html__( "Sorry, your request could not be processed due to security reason.", "arforms-form-builder" ),
                'capability_msg' => esc_html__( "Sorry, you do not have permission to perform this action.", "arforms-form-builder" ),
            )
        );

        wp_localize_script( 'arforms_debug_log', 'arforms_log_i18n_data', $arforms_log_i18n_data );
    }

    function htmlspecialchars_recursive( $params ){
        if( is_array( $params) ){
            return array_map( array( $this, __FUNCTION__ ), $params );
        } else {
            return htmlspecialchars( $params );
        }
    }

    function arforms_debug_logs_func($arf_debug_log_type = '', $arf_debug_log_event = '', $arf_debug_log_event_from = '', $arf_debug_log_raw_data = '', $arf_debug_log_ref_id = 0 ){

        global $wpdb,$arf_debug_log_id, $tbl_arforms_debug_log_setting,$arfsettings,$tbl_arf_settings;

        if( null != $arf_debug_log_raw_data ){       
            if( is_array($arf_debug_log_raw_data ) ){
                $arf_debug_log_raw_data['backtrace_summary'] = wp_debug_backtrace_summary( null, 0, false );
            } else {
                $arf_debug_log_raw_data = str_replace("\n"," ",$arf_debug_log_raw_data);
                $arf_debug_log_raw_data .= " | Backtrace Summary ==> ".wp_json_encode(wp_debug_backtrace_summary( null, 0, false ));
            }
            
            if( is_array( $arf_debug_log_raw_data ) ){
                $arf_debug_log_raw_data = array_map( array( $this, 'htmlspecialchars_recursive' ), $arf_debug_log_raw_data );
            } else {
                $arf_debug_log_raw_data = htmlspecialchars( $arf_debug_log_raw_data );
            }
        }

        $arf_active_gateway = false;
       
        $arf_debug_log_type_name = $arf_debug_log_type;
        if (! empty($arf_debug_log_type_name) ) {
            $check_settings = $wpdb->get_var( $wpdb->prepare( "SELECT setting_value FROM {$tbl_arf_settings} WHERE setting_name = %s AND setting_type = %s",$arf_debug_log_type_name ,'debug_log_settings' )); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_arf_settings is table name defined globally. False Positive alarm
            $arf_active_gateway=$check_settings; 
        }

        $inserted_id = 0;
        if ($arf_active_gateway == 1) {
            if ($arf_debug_log_ref_id == null ) {
                $arf_debug_log_ref_id = 0;
            }
            $arf_database_log_data = array(
                'arf_debug_log_ref_id'     => sanitize_text_field($arf_debug_log_ref_id),
                'arf_debug_log_type'       => sanitize_text_field($arf_debug_log_type),
                'arf_debug_log_event'      => sanitize_text_field($arf_debug_log_event),
                'arf_debug_log_event_from' => sanitize_text_field($arf_debug_log_event_from),
                'arf_debug_log_raw_data'   => ( is_array( $arf_debug_log_raw_data ) ? json_encode( $arf_debug_log_raw_data ) : stripslashes_deep( $arf_debug_log_raw_data ) ),
                'arf_debug_log_added_date' => current_time('mysql'),
            );

            $wpdb->insert($tbl_arforms_debug_log_setting, $arf_database_log_data);

            $inserted_id = $wpdb->insert_id;

            if (empty($arf_debug_log_ref_id) ) {
                $arf_debug_log_ref_id = $inserted_id;
            }
        }
        $arf_debug_log_id = $arf_debug_log_ref_id;
        return $inserted_id;
    }

    function arforms_save_debug_log_settings_details(){
        global $arformsmain;

        /* if( !isset( $_POST['arf_save_debug_log_nonce'] ) ){
            echo esc_html__("nonce_error",'arforms-form-builder');
            die;
        } */

        /* if ( isset( $_POST['arf_save_debug_log_nonce'] ) && '' != $_POST['arf_save_debug_log_nonce'] && ! wp_verify_nonce( sanitize_text_field( $_POST['arf_save_debug_log_nonce'] ), 'arf_save_debug_log_nonce' ) ) {
            echo esc_html__("nonce_error",'arforms-form-builder');
            die;
		} */
        if ( !isset( $_POST['_wpnonce_arfnonce'] ) || ( isset( $_POST['_wpnonce_arfnonce'] ) && '' != $_POST['_wpnonce_arfnonce'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_arfnonce'] ), 'arflite_wp_nonce' ) ) ) {
            $response['variant'] = 'error';
            $response['title'] = esc_html__( 'Error', 'arforms-form-builder');
            $response['msg'] = esc_html__( 'Sorry, your request could not be processed due to security reason.', 'arforms-form-builder' );

            wp_send_json( $response );
            die;
		}

        /* if( !current_user_can( 'arfchangesettings' ) ){
            echo esc_html__("capabilitie_error" , 'arforms-form-builder');
            die;
        } */
        
        if( !current_user_can( 'arfchangesettings' ) ){
            $response['variant'] = 'error';
            $response['title'] = esc_html__( 'Error', 'arforms-form-builder');
            $response['msg'] = esc_html__( 'Sorry, you do not have permission to perform this action', 'arforms-form-builder' );;
            wp_send_json( $response );
            die;
        }
        
        
        //$posted_data = json_decode( stripslashes_deep( $_POST['formData'] ), true ); //phpcs:ignore
        
        $arf_setting_filterd_form = isset( $_POST['setting_form_data'] ) ?  stripslashes_deep( $_POST['setting_form_data'] ) : array(); //phpcs:ignore
		/* $str = json_decode( stripslashes_deep( $arf_setting_filterd_form ), true );; */
		$posted_data = json_decode( $arf_setting_filterd_form, true );
        $email_notification_logs = isset( $posted_data['email_notification_log'] ) ? $posted_data['email_notification_log'] : 0;
        $arformsmain->arforms_update_settings( 'email_notification', $email_notification_logs, 'debug_log_settings' );

        $this->arforms_save_additional_debug_log_data( $posted_data );

        update_option('arforms_current_tab', 'logs_settings' );
        //die;
        $response['variant'] = 'success';
        $response['title']   = esc_html__('Success', 'arforms-form-builder');
        $response['msg']     = esc_html__('Debug Log Setting Saved Successfully.', 'arforms-form-builder');
        echo json_encode($response);
        die;
       
    }
    function arforms_view_debug_log_func(){

        global $current_page,$arfliteversion,$arf_view_log_selector;
        
        if ( isset( $_POST['arflite_view_debug_log_nonce'] ) && '' != $_POST['arflite_view_debug_log_nonce'] && ! wp_verify_nonce( sanitize_text_field( $_POST['arflite_view_debug_log_nonce'] ), 'arf_wp_nonce' ) ) {
            echo esc_html__("nonce_error",'arforms-form-builder');
            die;
        }

        if( !current_user_can( 'arfchangesettings' ) ){
                echo esc_html__("capabilitie_error" , 'arforms-form-builder');
            die;
        }

        $perpage     = isset($_REQUEST['perpage']) ? intval($_REQUEST['perpage']) : 20;
        $current_page = isset($_REQUEST['currentpage']) ? intval($_REQUEST['currentpage']) : 1;
        $offset      = ( ! empty($current_page) && $current_page > 1 ) ? ( ( $current_page - 1 ) * $perpage ) : 0;
        
        global $wpdb,$tbl_arforms_debug_log_setting;
        $arf_view_log_selector = isset($_REQUEST['arforms_debug_log_method']) ? sanitize_text_field($_REQUEST['arforms_debug_log_method']) : '';
        ?>
        
        <?php
        if (! empty($arf_view_log_selector)) {
    
                    $total_debug_logs   = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $tbl_arforms_debug_log_setting . " WHERE arf_debug_log_type = %s ORDER BY arf_debug_log_id DESC", $arf_view_log_selector ), ARRAY_A); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $tbl_arforms_debug_log_setting is table name defined globally. False Positive alarm

                    $arf_debug_logs         = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $tbl_arforms_debug_log_setting . " WHERE arf_debug_log_type = %s ORDER BY arf_debug_log_id DESC LIMIT %d, %d", $arf_view_log_selector,$offset , $perpage ), ARRAY_A); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $tbl_arforms_debug_log_setting is table name defined globally. False Positive alarm
            
            if (! empty($arf_debug_logs) ) {
            ?>
                <table>
                    <colgroup>
                        <col width="100"><col width="200"><col width="450">
                        <col width="400"><col width="150"><col width="200">
                    </colgroup>
                    <thead>
                        <tr class="table-heading">
                            <th class="table-data">
                                <div class="table-div"><?php esc_html_e( 'Log ID', 'arforms-form-builder' ); ?></div>
                            </th>
                            <th class="table-data">
                                <div class="table-div"><?php esc_html_e( 'Log Name', 'arforms-form-builder' ); ?></div>
                            </th>
                            <th class="table-data">
                                <div class="table-div"><?php esc_html_e( 'Log Data', 'arforms-form-builder' ); ?></div>
                            </th>
                            <th class="table-data">
                                <div class="table-div"><?php esc_html_e('Log Caller', 'arforms-form-builder' ); ?></div>
                            </th>
                            <th class="table-data">
                                <div class="table-div"><?php esc_html_e( 'Log Type', 'arforms-form-builder' ); ?></div>
                            </th>
                            <th colspan="1" rowspan="1"  class="table-data">
                                <div class="table-div"><?php esc_html_e( 'Log Date & Time', 'arforms-form-builder' ); ?></div>
                            </th>
                        </tr>
                        
                    </thead>
                </table>
                <table>
                <colgroup>
                    <col width="100"><col width="200"><col width="450">
                    <col width="400"><col width="150"><col width="200">
                    
                </colgroup>
                
                    <?php
                
                    foreach ( $arf_debug_logs as $arf_debug_log_key => $arf_debug_log_val ) {


                        $arf_debug_lod_id         = ! empty($arf_debug_log_val['arf_debug_log_id']) ? intval($arf_debug_log_val['arf_debug_log_id']) : '';

                        $arf_debug_log_event      = ! empty($arf_debug_log_val['arf_debug_log_event']) ? esc_html($arf_debug_log_val['arf_debug_log_event']) : '';
        
                        $arf_debug_log_raw_data   = ! empty($arf_debug_log_val['arf_debug_log_raw_data']) ? stripslashes_deep($arf_debug_log_val['arf_debug_log_raw_data']) : '';
                        
                        $arf_debug_log_added_date = ! empty($arf_debug_log_val['arf_debug_log_added_date']) ? esc_html($arf_debug_log_val['arf_debug_log_added_date']) : '';

                        $arf_debug_log_type =!empty($arf_debug_log_val['arf_debug_log_type']) ? esc_html($arf_debug_log_val['arf_debug_log_type']) : '';
                        ?>
                        
                        <tr>
                            <td class="table-data">
                                        <div class="table-div"> <?php echo intval($arf_debug_lod_id); ?></div>
                            </td>
                        
                            <td class="table-data">
                                        <div class="table-div"><?php echo esc_html($arf_debug_log_event); ?></div>
                            </td>
                            <td class="table-data">
                                <div class="table-div"> <?php 
                                $raw_data = json_decode( $arf_debug_log_raw_data, true );
                                if( is_array($raw_data) ){
                                    $param_data = array();
                                    foreach( $raw_data as $raw_key => $debug_prepared_data ){
                                        if( 'backtrace_summary' != $raw_key ){
                                            $param_data[ $raw_key ] = $debug_prepared_data;
                                        }
                                    }
                                    echo json_encode( $param_data );
                                }
                                else{
                                    $log_data=explode('The full debugging output is shown below:',$arf_debug_log_raw_data);
                                    print_r($log_data[0]);
                                }
                                ?>  
                                </div>
                            </td>
                        
                            <td class="table-data">
                                <div class="table-div" style="text-align:left;max-width: 400px;">
                                <?php
                                    if(is_array($raw_data))
                                    {   
                                        $count=count($raw_data['backtrace_summary']);
                                        
                                        for($i=0;$i<$count;$i++)
                                        {
                                                    echo $raw_data['backtrace_summary'][$i]."<br>"; //phpcs:ignore
                                        }
                                    }
                                    else
                                    {   
                                        $raw_data=explode('backtrace_summary":',$arf_debug_log_raw_data);
                                        $arf_data=$raw_data[1];
                                        $arf_raw_data=explode('}',$arf_data);
                                        $json_raw_data=json_decode($arf_raw_data[0]);
                                        $count=count($json_raw_data);

                                        for($i=0;$i<$count;$i++)
                                        {
                                                    echo $json_raw_data[$i]."<br>"; //phpcs:ignore
                                        }
                                    }
                                ?>
                                </div>
                            </td>
                            <td class="table-data">
                                <div class="table-div">
                                    <?php
                                    
                                    $raw_data=json_decode($arf_debug_log_raw_data,true);
                                    if(is_array($raw_data))
                                    {
                                        if($raw_data['success']=="true" )  
                                        {
                                            ?>
                                            <span class="arf-logtype-success">
                                            <?php echo "Success"; ?>
                                            </span>
                                            <?php
                                        }
                                        else 
                                        {
                                            ?>
                                            <span class="arf-logtype-error">
                                            <?php echo "Error"; ?>
                                            </span>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <span class="arf-logtype-error">
                                        <?php echo "Error"; ?>
                                        </span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </td>
                            <td class="table-data">
                                        <div class="table-div"> <?php echo $arf_debug_log_added_date; //phpcs:ignore ?></div>
                            </td>
                            
                        </tr>
                        
                    <?php
                        
                    }        
                ?>                    
                </table>
            <?php
            }
            else
            {
                ?>
                <div class="arf-no-record">
                <?php
                    esc_html_e( "No Logs Found!", 'arforms-form-builder');
                ?>
                </div>
                <?php
            }
            $total_recored = count($total_debug_logs);
            $page_recored = count($arf_debug_logs);                   
            
            $arf_pagination_nonce= wp_create_nonce("arf_pagination_nonce");
            $pagination='<div class="arf-view-popup-footer">
                        <div class="arf-showing-data">
                        <div class="arf-show-record"> 
                        Showing '.$page_recored.' of '.$total_recored.' </div>
                        <div class="arf-pagination">';
                    
                        $pagi=ceil($total_recored/$perpage);
                        
                        if($current_page==1) 
                        {

                            $pagination .= '<input type="button" class="pagi_button arf-pagi-fist-page " id="pagi_prev_'.$arf_view_log_selector.'" style="cursor: not-allowed;">';


                            $pagination .= '<input type="button" class="pagi_button  arf-lessthan" id="pagi_prev_'.$arf_view_log_selector.'" style="cursor: not-allowed;">';

                        
                        }else{

                            $pagination .= '<input type="button" class="pagi_button  arf-pagi-fist-page" id="pagi_prev_'.$arf_view_log_selector.'" onclick="return Currentpage(1)"; >';

                                $pagination .= '<input type="button" class="pagi_button arf-lessthan" id="pagi_prev_'.$arf_view_log_selector.'" onclick="return PagiPrev('.$current_page.')";>';
                        }

                        $pagination.='<input type="button" value="'.$current_page.'" class="pagi_button active_pagination"> of  &nbsp &nbsp'. $pagi;
                        if($current_page==$pagi)
                        {
                            $pagination .= '<input type="button" class="pagi_button arf-greterthan" id="pagi_prev_'.$arf_view_log_selector.'" style="cursor: not-allowed;">';

                            $pagination .= '<input type="button" class="pagi_button arf-pagi-last-page" id="pagi_prev_'.$arf_view_log_selector.'" style="cursor: not-allowed;">';
                        }
                        elseif($pagi==0){
                            $pagination .= '<input type="button" class="pagi_button arf-greterthan" id="pagi_prev_'.$arf_view_log_selector.'" style="cursor: not-allowed;">';

                            $pagination .= '<input type="button" class="pagi_button arf-pagi-last-page" id="pagi_prev_'.$arf_view_log_selector.'" style="cursor: not-allowed;">';
                        }
                        else
                        {
                                $pagination .= '<input type="button" class="pagi_button arf-greterthan" id="pagi_prev_'.$arf_view_log_selector.'" onclick="return PagiNext('.$current_page.')";>';

                                $pagination .= '<input type="button" class="pagi_button arf-pagi-last-page" id="pagi_prev_'.$arf_view_log_selector.'"  onclick="return Currentpage('.$pagi.')";>';
                        }
                        $pagination.='</div><input type="hidden" value="'.$arf_view_log_selector.'" id="arf_currentpage"></div></div>';

                    echo $pagination; //phpcs:ignore
        }
        ?>
       <?php
        die();
    }
    

    function arforms_download_debug_log_func_file()
    {
        global $wpdb,$tbl_arforms_debug_log_setting;
        $response              = array();

        if ( isset( $_POST['_wpnone'] ) && '' != $_POST['_wpnone'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnone'] ), 'arf_wp_nonce' ) ) {
            $response['variant'] = 'error';
            $response['title'] = esc_html__( 'Error', 'arforms-form-builder');
            $response['msg'] = esc_html__( 'Sorry, your request could not be processed due to security reason.', 'arforms-form-builder' );

            wp_send_json( $response );
            die;
		}

        if( !current_user_can( 'arfchangesettings' ) ){
            $response['variant'] = 'error';
            $response['title'] = esc_html__( 'Error', 'arforms-form-builder');
            $response['msg'] = esc_html__( 'Sorry, you do not have permission to perform this action', 'arforms-form-builder' );;
            wp_send_json( $response );
            die;
        }
        
        $response['variant']   = 'error';
        $response['title']     = esc_html__('Error', 'arforms-form-builder');
        $response['msg']       = esc_html__('Something went wrong', 'arforms-form-builder');

        $arf_download_log_selector = ! empty($_REQUEST['arforms_debug_log_method']) ? sanitize_text_field($_REQUEST['arforms_debug_log_method']) : ''; 
        $arf_download_log_duration = ! empty($_REQUEST['arforms_selected_download_duration']) ? sanitize_text_field($_REQUEST['arforms_selected_download_duration']) : '';
        if(!empty($arf_download_log_selector) && !empty($arf_download_log_duration))
        {
            if (! empty($_REQUEST['custom_datapicker1']) && !empty($_REQUEST['custom_datapicker2']) && $arf_download_log_duration == 'custom' ) {
                $arf_start_date                   = !empty($_REQUEST['custom_datapicker1']) ? date('Y-m-d 00:00:00', strtotime(sanitize_text_field($_REQUEST['custom_datapicker1']))) : '';
                $arf_end_date                     = !empty($_REQUEST['custom_datapicker2']) ? date('Y-m-d 23:59:59', strtotime(sanitize_text_field($_REQUEST['custom_datapicker2']))) : '';
                $arf_debug_log_where_cond = $wpdb->prepare(' AND (arf_debug_log_added_date >= %s AND arf_debug_log_added_date <= %s)', $arf_start_date, $arf_end_date);
            } 
            elseif (! empty($arf_download_log_duration) && $arf_download_log_duration != 'custom' && $arf_download_log_duration != 'all' ) {
                $arf_last_selected_days           = date('Y-m-d', strtotime('-' . $arf_download_log_duration . ' days'));

                $arf_debug_log_where_cond = $wpdb->prepare(' AND (arf_debug_log_added_date >= %s)', $arf_last_selected_days);
            }

            $arf_download_debug_log_query = 'SELECT * FROM `' . $tbl_arforms_debug_log_setting . "` WHERE `arf_debug_log_type` = '" . $arf_download_log_selector . "'  " . $arf_debug_log_where_cond . ' ORDER BY arf_debug_log_id DESC';

            $arf_download_debug_log_data = $wpdb->get_results($arf_download_debug_log_query, ARRAY_A); //phpcs:ignore

            $arf_download_data = json_encode(stripslashes_deep($arf_download_debug_log_data));
            
            if (! function_exists('WP_Filesystem') ) {
                include_once ABSPATH . 'wp-admin/includes/file.php';
            }
            WP_Filesystem();
            global $wp_filesystem,$filename;
         
            $arf_debug_log_file_name ='arforms_debug_logs_' . $arf_download_log_selector . '_' . $arf_download_log_duration;
            $filename       = $arf_debug_log_file_name . '.txt';
            
            $result                            = $wp_filesystem->put_contents(ARFLITE_UPLOAD_DIR . '/' . $arf_debug_log_file_name . '.txt', $arf_download_data, 0777);

            $filepath = ARFLITE_UPLOAD_DIR . '/' .$filename;
        
            $response['variant']    = 'success';
            $response['title']      = esc_html__('Success', 'arforms-form-builder');
            $response['msg']        = esc_html__('log download successfully', 'arforms-form-builder');
            $response['url']        = admin_url('admin.php?page=ARForms-settings&arforms_action=download_log&file=' . $filename);
        }
        echo json_encode($response);
        die;

    }
    function arforms_email_log_download_file()
    {
       
        if (!empty($_REQUEST['arforms_action']) && 'download_log' == sanitize_text_field($_REQUEST['arforms_action']) ) {
            $filename =  ! empty($_REQUEST['file']) ? basename(sanitize_file_name($_REQUEST['file'])) : '';
            if(!empty($filename))
            {
                $filepath = ARFLITE_UPLOAD_DIR . '/' .$filename;
                if(file_exists($filepath))
                {
                    $now = gmdate('D, d M Y H:i:s');
                    header('Expires: Tue, 03 Jul 2020 06:00:00 GMT');
                    header('Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate');
                    header("Last-Modified: {$now} GMT");
                    header('Content-Type: application/force-download');
                    header('Content-Type: application/octet-stream');
                    header('Content-Type: application/download');
                    header("Content-Disposition: attachment;filename={$filename}");
                    header('Content-Transfer-Encoding: binary');

                    readfile($filepath);

                    @unlink($filepath);

                    $bpa_txt_file_name = str_replace('.zip', '.txt', $filename);
                    $bpa_txt_file_path = ARFLITE_UPLOAD_DIR . '/' . $bpa_txt_file_name;
                    if (file_exists($bpa_txt_file_path) ) {
                           @unlink($bpa_txt_file_path);
                    }
                    die;
                }      
            }    
        }
    }   

   
    function arforms_clear_debug_log_data_func(){
        global $wpdb,$tbl_arforms_debug_log_setting;

        if( !isset( $_POST['arflite_clear_debug_nonce'] ) ){
            $response['variant'] = 'error';
            $response['title'] = esc_html__( 'Error', 'arforms-form-builder');
            $response['msg'] = esc_html__( 'Sorry, your request could not be processed due to security reason.', 'arforms-form-builder' );

            wp_send_json( $response );
            die;
        }

        if ( isset( $_POST['arflite_clear_debug_nonce'] ) && '' != $_POST['arflite_clear_debug_nonce'] && ! wp_verify_nonce( sanitize_text_field( $_POST['arflite_clear_debug_nonce'] ), 'arf_wp_nonce' ) ) {
			$response['variant'] = 'error';
            $response['title'] = esc_html__( 'Error', 'arforms-form-builder');
            $response['msg'] = esc_html__( 'Sorry, your request could not be processed due to security reason.', 'arforms-form-builder' );

            wp_send_json( $response );
		}

		if( !current_user_can( 'arfviewentries' ) ){
            $response['variant'] = 'error';
            $response['title'] = esc_html__( 'Error', 'arforms-form-builder');
            $response['msg'] = esc_html__( 'Sorry, you do not have permission to perform this action', 'arforms-form-builder' );;
            echo json_encode($response);
            die;
        }
        
        $arf_clear_log_selector = ! empty($_REQUEST['arforms_debug_log_method']) ? sanitize_text_field($_REQUEST['arforms_debug_log_method']) : '';
        if (! empty($arf_clear_log_selector) ) {
            $response['variant'] = 'success';
            $response['title'] = esc_html__( 'success', 'arforms-form-builder');
            $response['msg'] = "";

           
            $wpdb->delete($tbl_arforms_debug_log_setting, array( 'arf_debug_log_type' => $arf_clear_log_selector ), array( '%s' ));
            echo json_encode($response);
        }
        die();
    }

    function arforms_render_pro_debug_log(){
        if( class_exists( 'arforms_pro_debug_log' ) && method_exists( 'arforms_pro_debug_log', 'arforms_render_pro_debug_log_ui' ) ){
            arforms_pro_debug_log::arforms_render_pro_debug_log_ui();
        }
    }

    function arforms_save_additional_debug_log_data( $setting_key ){
        if( class_exists( 'arforms_pro_debug_log' ) && method_exists( 'arforms_pro_debug_log', 'arforms_save_pro_debug_log_data' ) ){
            arforms_pro_debug_log::arforms_save_pro_debug_log_data( $setting_key );
        }
    }
}
global $arfdebuglog;
$arfdebuglog = new arfdebuglog();