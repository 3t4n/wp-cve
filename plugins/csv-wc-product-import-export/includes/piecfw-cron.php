<?php
/**
 * Add our own custom cron interval callback
 */
if(!function_exists( 'piecfw_product_import_cron_intervals_callback')){
    function piecfw_product_import_cron_intervals_callback($schedules) {
        // add 'Every 15 minutes' cron  interval
        $schedules['piecfw_every_15_minutes'] = array(
            'interval' => (15*60),
            'display' => __('CSV - Every 15 minutes')
        );
        // add 'Every 30 minutes' cron  interval
        $schedules['piecfw_every_30_minutes'] = array(
            'interval' => (30*60),
            'display' => __('CSV - Every 30 minutes')
        );
        // add 'Once hourly' cron  interval
        $schedules['piecfw_hourly'] = array(
            'interval' => (60*60),
            'display' => __('CSV - Once hourly')
        );
        // add 'Once daily' cron  interval
        $schedules['piecfw_daily'] = array(
            'interval' => (24*(60*60)),
            'display' => __('CSV - Once daily')
        );
        // add 'Twice daily' cron  interval
        $schedules['piecfw_twicedaily'] = array(
            'interval' => (12*(60*60)),
            'display' => __('CSV - Twice daily')
        );
        // add 'Once hourly' cron  interval
        $schedules['piecfw_weekly'] = array(
            'interval' => (7*(24*(60*60))),
            'display' => __('CSV - Once weekly')
        );
        // add 'Every 15 days' cron  interval
        $schedules['piecfw_fifteendays'] = array(
            'interval' => (15*(24*(60*60))),
            'display' => __('CSV - Every 15 days')
        );
        // add 'Monthly' cron  interval
        $schedules['piecfw_monthly'] = array(
            'interval' => (30*(24*(60*60))),
            'display' => __('CSV - Monthly')
        );

        return $schedules;
    }
}
/**
 * Add our own custom cron interval hook
 */
add_filter( 'cron_schedules', 'piecfw_product_import_cron_intervals_callback');

/**
 * add method to register event to WordPress init
 */
add_action( 'init', 'piecfw_register_product_import_cron_event');

/**
 * this method will register the cron event
 */
if(!function_exists( 'piecfw_register_product_import_cron_event')){
    function piecfw_register_product_import_cron_event() {
        global $wpdb; 
        //$current_time = strtotime('+6 hours');
        $current_time = current_time( 'timestamp' );

        $sql = "SELECT * FROM ".$wpdb->prefix."piecfw_product_import_cron ORDER BY cron_id";
        $results = $wpdb->get_results($sql);
        foreach( $results as $result ) {
            $cron_time = strtotime($result->start_date);
            if($current_time>=$cron_time){
                if( !wp_next_scheduled( 'piecfw_product_import_cron_'.$result->cron_id ) ) {
                    if($result->frequency=='piecfw_one_time'){
                        wp_schedule_single_event(time(), 'piecfw_product_import_cron_'.$result->cron_id);
                    }else{
                        wp_schedule_event(time(), $result->frequency, 'piecfw_product_import_cron_'.$result->cron_id);
                    }
                }
            }    
        }
    }
}

global $wpdb;
//$current_time = strtotime('+6 hours');
$current_time = current_time( 'timestamp' );

$sql = "SELECT * FROM ".$wpdb->prefix."piecfw_product_import_cron WHERE status!='Completed' ORDER BY cron_id";
$results = $wpdb->get_results($sql);
$cron_files = array();

foreach( $results as $result ) {
	$cron_time = strtotime($result->start_date);

	if($current_time>=$cron_time){
        /**
		 * notify_user_send_email method will be call when the cron is executed
		 */
        add_action('piecfw_product_import_cron_'.$result->cron_id, 'piecfw_all_product_import_cron', 10);                
        /*$GLOBALS['cron_id'] = $result->cron_id;
        $GLOBALS['cron_file'] = $result->file_name;*/
        $cron_files[] = $result->file_name;
	}
}
$GLOBALS['cron_files'] = $cron_files;

/**
 * this method will call when cron executes
 */
if(!function_exists( 'piecfw_all_product_import_cron')){
    function piecfw_all_product_import_cron() {
        global $wpdb; 
        $upload = wp_upload_dir();
        $upload_dir = $upload['basedir'];
        foreach($GLOBALS['cron_files'] as $cron_file){
            /*$file_name = $GLOBALS['cron_file'];
            $imported_file = $GLOBALS['cron_file'];*/
            $file_name = $cron_file;
            $imported_file = $cron_file;
            $file = $upload_dir.'/piecfw_product_import_export/cron/'.$cron_file;
            $delimiter = ',';
            $merge_empty_cells = 0;

            $timezone_format = _x( 'Y-m-d H:i:s', 'timezone date format' );
            $created_at = date_i18n( $timezone_format );

            /*wp_mail( 'pramod.r@vrinsoft.com', 'CSV Cron-1', $file);*/
            if ($file) {
                $exists_status = $wpdb->get_var( $wpdb->prepare( "SELECT status FROM " . $wpdb->prefix . "piecfw_product_import_cron WHERE file_name = %s", $file_name ) );
                $exists_freq = $wpdb->get_var( $wpdb->prepare( "SELECT frequency FROM " . $wpdb->prefix . "piecfw_product_import_cron WHERE file_name = %s", $file_name ) );

                if(($exists_status=='Pending' || $exists_status=='Running') || $exists_freq!='piecfw_one_time'){

                    $sql = "UPDATE ".$wpdb->prefix."piecfw_product_import_cron SET status='Running' WHERE file_name='".$file_name."'";
                    $wpdb->query($sql);

                    //includes
                    require_once ABSPATH.'wp-admin/includes/import.php';
                    if ( ! class_exists( 'WP_Importer' ) ) {
                        $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
                        if ( file_exists( $class_wp_importer ) ) {
                            require $class_wp_importer;
                        }
                    }
                    
                    require 'importer/class-piecfw-product-import-cron.php';
                    require 'importer/class-piecfw-parser-cron.php';
                    require 'importer/class-piecfw-product_variation-import-cron.php';
                    /*wp_mail( 'pramod.r@vrinsoft.com', 'CSV Cron-2', $file);*/
            

                    @set_time_limit(0);
                    @ob_flush();
                    @flush();
                    $wpdb->hide_errors();
                    

                    $mapping   = json_decode('{"sku":"sku","parent_sku":"parent_sku","post_title":"post_title","post_excerpt":"post_excerpt","post_content":"post_content","post_status":"post_status","featured":"featured","downloadable":"downloadable","virtual":"virtual","visibility":"visibility","stock":"stock","stock_status":"stock_status","backorders":"backorders","manage_stock":"manage_stock","regular_price":"regular_price","sale_price":"sale_price","weight":"weight","length":"length","width":"width","height":"height","tax_status":"tax_status","tax_class":"tax_class","variation_description":"variation_description","upsell_skus":"upsell_skus","crosssell_skus":"crosssell_skus","sale_price_dates_from":"sale_price_dates_from","sale_price_dates_to":"sale_price_dates_to","download_limit":"download_limit","download_expiry":"download_expiry","product_url":"product_url","button_text":"button_text","images":"import_as_images","downloadable_files":"downloadable_files","tax:product_type":"tax:product_type","tax:product_visibility":"tax:product_visibility","tax:product_cat":"tax:product_cat","tax:product_tag":"tax:product_tag","tax:product_shipping_class":"tax:product_shipping_class","attribute:pa_colour":"attribute:pa_colour","attribute_data:pa_colour","attribute_data:pa_colour","meta:attribute_pa_colour":"meta:attribute_pa_colour","attribute:pa_size":"attribute:pa_size","attribute_data:pa_size":"attribute_data:pa_size","meta:attribute_pa_size":"meta:attribute_pa_size","meta:custom_field":"meta:custom_field"}', true );
                    /*wp_mail( 'pramod.r@vrinsoft.com', 'CSV Cron-3', $file);*/


                    //Insert File Log
                    $exists_in_db = $wpdb->get_var( $wpdb->prepare( "SELECT log_id FROM " . $wpdb->prefix . "piecfw_product_import_file_log WHERE file_name = %s", $imported_file ) );
                    if (!$exists_in_db) {
                        $wpdb->insert($wpdb->prefix.'piecfw_product_import_file_log', array(
                            'file_name' => $imported_file,
                            'file_status' => 'Success',
                            'file_date' => $created_at, 
                        ));
                    }        

                    //Import Start
                    $memory    = size_format( wc_let_to_num( ini_get( 'memory_limit' ) ) );
                    $wp_memory = size_format( wc_let_to_num( WP_MEMORY_LIMIT ) );
                    PIECFW_Product_Import_Export::log( '---[ New Import ] PHP Memory: ' . $memory . ', WP Memory: ' . $wp_memory );
                    PIECFW_Product_Import_Export::log( __( 'Parsing products CSV.', PIECFW_TRANSLATE_NAME ) );
                    $parser = new PIECFW_Parser( 'product' );
                    list( $parsed_data, $raw_headers, $position ) = $parser->parse_data( $file, $delimiter, $mapping, 0 );
                    PIECFW_Product_Import_Export::log( __( 'Finished parsing products CSV.', PIECFW_TRANSLATE_NAME ) );
                    unset( $import_data );
                    wp_defer_term_counting( true );
                    wp_defer_comment_counting( true );
                    /*wp_mail( 'pramod.r@vrinsoft.com', 'CSV Cron-4', $file);*/


                    //Import Process
                    PIECFW_Product_Import_Export::log( '---' );
                    PIECFW_Product_Import_Export::log( __( 'Processing products.', PIECFW_TRANSLATE_NAME ) );
                    foreach ( $parsed_data as $key => &$item ) {
                        $product = $parser->parse_product( $item, $merge_empty_cells );
                        if ( ! is_wp_error( $product ) ) {
                            if($item['tax:product_type']=='variation'){
                                $variation_product = new PIECFW_Product_Variation_Import();
                                $variation_product->process_product( $product, $imported_file );
                            }else{
                                PIECFW_Product_Import::process_product( $product, $merge_empty_cells, $imported_file );
                            }
                        } 
                        unset( $item, $product );
                    }
                    if ( function_exists( 'wc_update_product_lookup_tables' ) ) {
                        wc_update_product_lookup_tables();
                    }
                    PIECFW_Product_Import_Export::log( __( 'Finished processing products.', PIECFW_TRANSLATE_NAME ) );
                    /*wp_mail( 'pramod.r@vrinsoft.com', 'CSV Cron-5', $file);*/


                    //Import End
                    foreach ( get_taxonomies() as $tax ) {
                        delete_option( "{$tax}_children" );
                        _get_term_hierarchy( $tax );
                    }
                    wp_defer_term_counting( false );
                    wp_defer_comment_counting( false );
                    do_action( 'import_end' );
                    /*wp_mail( 'pramod.r@vrinsoft.com', 'CSV Cron-6', $file);*/

                    $sql = "UPDATE ".$wpdb->prefix."piecfw_product_import_cron SET status='Completed' WHERE file_name='".$file_name."'";
                    $wpdb->query($sql);
                }
            }
            else{
                //Insert File Log
                $exists_in_db = $wpdb->get_var( $wpdb->prepare( "SELECT log_id FROM " . $wpdb->prefix . "piecfw_product_import_file_log WHERE file_name = %s", $imported_file ) );
                if (!$exists_in_db) {
                    $wpdb->insert($wpdb->prefix.'piecfw_product_import_file_log', array(
                        'file_name' => $imported_file,
                        'file_status' => 'Failed',
                        'file_date' => $created_at, 
                    ));
                }
            }
        }    
    }
}