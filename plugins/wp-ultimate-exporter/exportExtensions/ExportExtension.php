<?php
/**
 * WP Ultimate Exporter plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

namespace Smackcoders\SMEXP;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
if (class_exists('\Smackcoders\FCSV\MappingExtension'))
{

    class ExportExtension extends \Smackcoders\FCSV\MappingExtension
    {

        public $response = array();
        public $headers = array();
        public $module;
        public $exportType = 'csv';
        public $optionalType = null;
        public $conditions = array();
        public $eventExclusions = array();
        public $fileName;
        public $data = array();
        public $heading = true;
        public $delimiter = ',';
        public $enclosure = '"';
        public $auto_preferred = ",;\t.:|";
        public $output_delimiter = ',';
        public $linefeed = "\r\n";
        public $export_mode;
        public $export_log = array();
        public $limit;
        protected static $instance = null, $mapping_instance, $metabox_export, $jetengine_export, $export_handler, $post_export, $woocom_export, $review_export, $ecom_export, $learnpress_export;
        protected $plugin, $activateCrm, $crmFunctionInstance;
        public $plugisnScreenHookSuffix = null;

        public static function getInstance()
        {
            if (null == self::$instance)
            {
                self::$instance = new self;
                ExportExtension::$export_handler = ExportHandler::getInstance();
                ExportExtension::$post_export = PostExport::getInstance();
                ExportExtension::$woocom_export = WooCommerceExport::getInstance();
                ExportExtension::$review_export = CustomerReviewExport::getInstance();
                ExportExtension::$learnpress_export = LearnPressExport::getInstance();
                ExportExtension::$jetengine_export = JetEngineExport::getInstance();
                ExportExtension::$metabox_export = metabox::getInstance();
                self::$instance->doHooks();
            }
            return self::$instance;
        }

        public function doHooks()
        {
            $plugin_pages = ['com.smackcoders.csvimporternew.menu'];
            require_once WP_PLUGIN_DIR . '/wp-ultimate-exporter/wp-exp-hooks.php';
            global $plugin_ajax_hooks;

            $request_page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
            $request_action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
            if (in_array($request_page, $plugin_pages) || in_array($request_action, $plugin_ajax_hooks))
            {
                add_action('wp_ajax_parse_data', array(
                    $this,
                    'parseData'
                ));
                add_action('wp_ajax_total_records', array(
                    $this,
                    'totalRecords'
                ));
                add_action('wp_ajax_get_download', array(
                    $this,
                    'downloadFunction'
                ));
            }
        }

        public function downloadFunction(){
            check_ajax_referer('smack-ultimate-csv-importer', 'securekey');            
            $file_name = $_POST['fileName'];
            $file_path = $_POST['filePath'];
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            ob_clean();
            flush();
            readfile($file_path);
            wp_die();           
        }

        public function totalRecords()
        {
            check_ajax_referer('smack-ultimate-csv-importer', 'securekey');
            global $wpdb;
            $module = sanitize_text_field($_POST['module']);

            $optionalType = isset($_POST['optionalType']) ? sanitize_text_field($_POST['optionalType']) : '';
            if ($module == 'WooCommerceOrders')
            {
                $module = 'shop_order';
            }
            elseif ($module == 'WooCommerceCoupons')
            {
                $module = 'shop_coupon';
            }
            elseif ($module == 'WooCommerceRefunds')
            {
                $module = 'shop_order_refund';
            }
            elseif ($module == 'WooCommerceVariations')
            {
                $module = 'product_variation';
            }
            elseif ($module == 'WPeCommerceCoupons')
            {
                $module = 'wpsc-coupon';
            }
            elseif ($module == 'Users')
            {
                $get_available_user_ids = "select DISTINCT ID from $wpdb->users u join $wpdb->usermeta um on um.user_id = u.ID";
                $availableUsers = $wpdb->get_col($get_available_user_ids);
                $total = count($availableUsers);
                return $total;
            }
            elseif ($module == 'Tags')
            {
                $get_all_terms = get_tags('hide_empty=0');
                return count($get_all_terms);
                wp_die();
            }
            elseif ($module == 'Categories')
            {
                $get_all_terms = get_categories('hide_empty=0');
                return count($get_all_terms);
                wp_die();
            }
            elseif ($module == 'CustomPosts' && $optionalType == 'nav_menu_item')
            {
                $get_menu_ids = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}terms AS t LEFT JOIN {$wpdb->prefix}term_taxonomy AS tt ON tt.term_id = t.term_id WHERE tt.taxonomy = 'nav_menu' ", ARRAY_A);
                echo wp_json_encode(count($get_menu_ids));
                wp_die();
            }
            // elseif($module == 'CustomPosts' && $optionalType == 'widgets'){
            // 	echo wp_json_encode(1);
            // 	wp_die();
            // }
            else
            {
                $optional_type = NULL;
                if ($module == 'CustomPosts')
                {
                    $optional_type = $optionalType;
                }
                $module = ExportExtension::$post_export->import_post_types($module, $optional_type);
            }
            $get_post_ids = "select DISTINCT ID from $wpdb->posts";
            $get_post_ids .= " where post_type = '$module'";

            /**
             * Check for specific status
             */
            if($module == 'product' && is_plugin_active('woocommerce/woocommerce.php')){
                if(is_plugin_active('polylang/polylang.php') || is_plugin_active('polylang-pro/polylang.php') || is_plugin_active('polylang-wc/polylang-wc.php')){
                    //TODO temporary fix 
                    //wc_get_products only exports default language product 
                    $products = "select DISTINCT ID from {$wpdb->prefix}posts";
                    $products .= " where post_type = '$module'";
                    $products .= "and post_status in ('publish','draft','future','private','pending') ";
                    $products = $wpdb->get_col($products);
                    
                }
                else{
                    $product_statuses = array('publish', 'draft', 'future', 'private', 'pending');
			        $products = wc_get_products(array('status' => $product_statuses , 'limit' => -1));
                }
                $total = count($products);
                return $total;
                
            }
            elseif($module == 'shop_order'){
                if(is_plugin_active('polylang/polylang.php') || is_plugin_active('polylang-pro/polylang.php') || is_plugin_active('polylang-wc/polylang-wc.php')){
                    $order_statuses_id = array('wc-completed', 'wc-cancelled', 'wc-on-hold', 'wc-processing', 'wc-pending');
                    $orders_id = wc_get_orders(array('status' => $order_statuses));
                    $order_ids = wp_list_pluck($orders_id, 'ID');
                    $get_post_ids = array_reverse($order_ids);  
                    foreach($get_post_ids as $ids){
                        $module =$wpdb->get_var("SELECT post_type FROM {$wpdb->prefix}posts where id=$ids");
                    }
                    if($module == 'shop_order_placehold'){
                        $orders = "select DISTINCT p.ID from {$wpdb->prefix}posts as p inner join {$wpdb->prefix}wc_orders as wc ON p.ID=wc.id";
                        $orders.= " where p.post_type = '$module'";
                        $orders .= "and wc.status in ('wc-completed', 'wc-cancelled', 'wc-on-hold', 'wc-processing', 'wc-pending')";
                        $orders = $wpdb->get_col($orders);
                    }
                    else{
                        $orders = "select DISTINCT ID from {$wpdb->prefix}posts";
                        $orders.= " where post_type = '$module'";
                        $orders .= "and post_status in ('wc-completed','wc-cancelled','wc-on-hold','wc-processing','wc-pending')";
                        $orders = $wpdb->get_col($orders);
                    }
                    
                }
                else{
                    $order_statuses = array('wc-completed', 'wc-cancelled', 'wc-on-hold', 'wc-processing', 'wc-pending');
                    $orders = wc_get_orders(array('status' => $order_statuses));
                }
                $total = count($orders);
                return $total;
    
            }
            elseif($module  == 'product_variation'){
                if(is_plugin_active('polylang/polylang.php') || is_plugin_active('polylang-pro/polylang.php') || is_plugin_active('polylang-wc/polylang-wc.php')){
                    $extracted_ids = "select DISTINCT ID from {$wpdb->prefix}posts";
                    $extracted_ids .= " where post_type = '$module'";
                    $extracted_ids .= "and post_status in ('publish','draft','future','private','pending') AND post_parent !=0";
                    $extracted_id = $wpdb->get_col($extracted_ids);
                    $extracted_ids =array();
                    //fix added for prema
                    foreach($extracted_id as $ids){
                        $parent_id = $wpdb->get_var("SELECT post_parent FROM {$wpdb->prefix}posts where ID=$ids");
                        $post_status =$wpdb->get_var("SELECT post_status FROM {$wpdb->prefix}posts where ID=$parent_id");
                        if(!empty($post_status )){
                            if($post_status !='trash' && $post_status != 'inherit'){
                                $extracted_ids [] =$ids;
                            }
    
                        }
                        
                    }
    
                }
                else{
                    $product_statuses = array('publish', 'draft', 'future', 'private', 'pending');
                    $products = wc_get_products(array('status' => $product_statuses , 'limit' => -1));
                    $variable_product_ids = [];
                    foreach($products as $product){
                        if ($product->is_type('variable')) {
                            $variable_product_ids[] = $product->get_id();
                        }
                    }	
                    $variation_count = 0;
                    $variation_ids = array();
                    foreach($variable_product_ids as $variable_product_id){
                        $variable_product = wc_get_product($variable_product_id);
                        $variation_ids[]  = $variable_product->get_children();
                    }
                    $extracted_ids = [];
                    foreach ($variation_ids as $v_ids) {
                        foreach ($v_ids as $v_id) {
                            $extracted_ids[] = $v_id;
                        }
                    }
                }
    
                // $product_statuses = array('publish', 'draft', 'future', 'private', 'pending');
                // $products = wc_get_products(array('status' => $product_statuses));
                // $variable_product_ids = [];
                // foreach($products as $product){
                //     if ($product->is_type('variable')) {
                //         $variable_product_ids[] = $product->get_id();
                //     }
                // }	
                // $variation_count = 0;
                // foreach($variable_product_ids as $variable_product_id){
                //     $variations = wc_get_products(array('parent_id' => $variable_product_id,'type' => 'variation','limit'=> -1));
                //     $variation_count += count($variations);
                // }	
                // $total = $variation_count;
                $total=count($extracted_ids);
                return $total;			
            }
            elseif ($module == 'shop_coupon')
            {
                $get_post_ids .= " and post_status in ('publish','draft','pending')";

            }
            elseif ($module == 'shop_order_refund')
            {

            }
            elseif ($module == 'forum')
            {
                $get_post_ids .= " and post_status in ('publish','draft','future','private','pending','hidden')";
            }
            elseif ($module == 'topic')
            {
                $get_post_ids .= " and post_status in ('publish','draft','future','open','pending','closed','spam')";
            }
            elseif ($module == 'reply')
            {
                $get_post_ids .= " and post_status in ('publish','spam','pending')";
            }
            $get_post_ids .= " and post_status in ('publish','draft','future','private','pending')";
            $get_total_row_count = $wpdb->get_col($get_post_ids);
            $total = count($get_total_row_count);
            return $total;
        }

        /**
         * ExportExtension constructor.
         * Set values into global variables based on post value
         */
        public function __construct()
        {
            $this->plugin = Plugin::getInstance();
        }

        public function parseData()
        {
            check_ajax_referer('smack-ultimate-csv-importer', 'securekey');
            if (!empty($_POST))
            {

                $this->module = sanitize_text_field($_POST['module']);
                $this->exportType = isset($_POST['exp_type']) ? sanitize_text_field($_POST['exp_type']) : 'csv';
                $conditions = str_replace("\\", '', sanitize_text_field($_POST['conditions']));
                $conditions = json_decode($conditions, True);
                $conditions['specific_period']['to'] = date("Y-m-d", strtotime($conditions['specific_period']['to']));
                $conditions['specific_period']['from'] = date("Y-m-d", strtotime($conditions['specific_period']['from']));
                $this->conditions = isset($conditions) && !empty($conditions) ? $conditions : array();
                if ($this->module == 'Taxonomies' || $this->module == 'CustomPosts')
                {
                    $this->optionalType = sanitize_text_field($_POST['optionalType']);
                }
                else
                {
                    $this->optionalType = $this->getOptionalType($this->module);
                }
                $eventExclusions = str_replace("\\", '', sanitize_text_field(isset($_POST['eventExclusions']) ? sanitize_text_field($_POST['eventExclusions']) : ''));
                $eventExclusions = json_decode($eventExclusions, True);
                $this->eventExclusions = isset($eventExclusions) && !empty($eventExclusions) ? $eventExclusions : array();
                $this->fileName = isset($_POST['fileName']) ? sanitize_text_field($_POST['fileName']) : '';
                if (empty($_POST['offset']) || sanitize_text_field($_POST['offset']) == 'undefined')
                {
                    $this->offset = 0;
                }
                else
                {
                    $this->offset = isset($_POST['offset']) ? sanitize_text_field($_POST['offset']) : 0;
                }
                if (!empty($_POST['limit']))
                {
                    $this->limit = isset($_POST['limit']) ? sanitize_text_field($_POST['limit']) : 1000;
                }
                else
                {
                    $this->limit = 50;
                }
                if (!empty($this->conditions['delimiter']['optional_delimiter']))
                {
                    $this->delimiter = $this->conditions['delimiter']['optional_delimiter'] ? $this->conditions['delimiter']['optional_delimiter'] : ',';
                }
                elseif (!empty($this->conditions['delimiter']['delimiter']))
                {
                    $this->delimiter = $this->conditions['delimiter']['delimiter'] ? $this->conditions['delimiter']['delimiter'] : ',';
                    if ($this->delimiter == '{Tab}')
                    {
                        $this->delimiter = " ";
                    }
                    elseif ($this->delimiter == '{Space}')
                    {
                        $this->delimiter = " ";
                    }
                }

                $this->export_mode = 'normal';
                $this->checkSplit = isset($_POST['is_check_split']) ? sanitize_text_field($_POST['is_check_split']) : 'false';
                $this->exportData();
            }
        }

        public function commentsCount($mode = null)
        {
            global $wpdb;
            self::generateHeaders($this->module, $this->optionalType);
            $get_comments = "select * from {$wpdb->prefix}comments";
            // Check status
            if ($this->conditions['specific_status']['is_check'] == 'true')
            {
                if ($this->conditions['specific_status']['status'] == 'Pending') $get_comments .= " where comment_approved = '0'";
                elseif ($this->conditions['specific_status']['status'] == 'Approved') $get_comments .= " where comment_approved = '1'";
                else $get_comments .= " where comment_approved in ('0','1')";
            }
            else $get_comments .= " where comment_approved in ('0','1')";
            // Check for specific period
            if ($this->conditions['specific_period']['is_check'] == 'true')
            {
                if ($this->conditions['specific_period']['from'] == $this->conditions['specific_period']['to'])
                {
                    $get_comments .= " and comment_date >= '" . $this->conditions['specific_period']['from'] . "'";
                }
                else
                {
                    $get_comments .= " and comment_date >= '" . $this->conditions['specific_period']['from'] . "' and comment_date <= '" . $this->conditions['specific_period']['to'] . "'";
                }
            }
            // Check for specific authors
            if ($this->conditions['specific_authors']['is_check'] == '1')
            {
                if (isset($this->conditions['specific_authors']['author']))
                {
                    $get_comments .= " and comment_author_email = '" . $this->conditions['specific_authors']['author'] . "'";
                }
            }
            $get_comments .= " order by comment_ID";
            $comments = $wpdb->get_results($get_comments);
            $totalRowCount = count($comments);
            return $totalRowCount;
        }

        public function getOptionalType($module)
        {
            if ($module == 'Tags')
            {
                $optionalType = 'post_tag';
            }
            elseif ($module == 'Posts')
            {
                $optionalType = 'posts';
            }
            elseif ($module == 'Pages')
            {
                $optionalType = 'pages';
            }
            elseif ($module == 'Categories')
            {
                $optionalType = 'category';
            }
            elseif ($module == 'Users')
            {
                $optionalType = 'users';
            }
            elseif ($module == 'Comments')
            {
                $optionalType = 'comments';
            }
            elseif ($module == 'CustomerReviews')
            {
                $optionalType = 'wpcr3_review';
            }
            elseif ($module == 'WooCommerce' || $module == 'WooCommerceOrders' || $module == 'WooCommerceCoupons' || $module == 'WooCommerceRefunds' || $module == 'WooCommerceVariations')
            {
                $optionalType = 'product';
            }
            elseif ($module == 'WooCommerce')
            {
                $optionalType = 'product';
            }
            elseif ($module == 'WPeCommerce')
            {
                $optionalType = 'wpsc-product';
            }
            elseif ($module == 'WPeCommerce' || $module == 'WPeCommerceCoupons')
            {
                $optionalType = 'wpsc-product';
            }
            return $optionalType;
        }

        /**
         * set the delimiter
         */
        public function setDelimiter($conditions)
        {
            if (isset($conditions['optional_delimiter']) && $conditions['optional_delimiter'] != '')
            {
                return $conditions['optional_delimiter'];
            }
            elseif (isset($conditions['delimiter']) && $conditions['delimiter'] != 'Select')
            {
                if ($conditions['delimiter'] == '{Tab}') return "\t";
                elseif ($conditions['delimiter'] == '{Space}') return " ";
                else return $conditions['delimiter'];
            }
            else
            {
                return ',';
            }
        }

        /**
         * Export records based on the requested module
         */
        public function exportData()
        {
            $this->mode = isset($this->mode) ? $this->mode : '';
            switch ($this->module)
            {
                case 'Posts':
                case 'Pages':
                case 'CustomPosts':
                case 'WooCommerce':
                case 'WooCommerceVariations':
                case 'WooCommerceOrders':
                case 'WooCommerceCoupons':
                case 'WooCommerceRefunds':
                case 'WPeCommerce':
                case 'WPeCommerceCoupons':
                    self::FetchDataByPostTypes();
                break;
                case 'Users':
                    self::FetchUsers();
                break;
                case 'Comments':
                    self::FetchComments();
                break;
                case 'CustomerReviews':
                    ExportExtension::$review_export->FetchCustomerReviews($this->module, $this->optionalType, $this->conditions, $this->offset, $this->limit, $this->mode);
                break;
                case 'Categories':
                    ExportExtension::$post_export->FetchCategories($this->module, $this->optionalType);
                break;
                case 'Tags':
                    ExportExtension::$post_export->FetchTags($this->mode, $this->module, $this->optionalType);
                case 'Taxonomies':
                    ExportExtension::$woocom_export->FetchTaxonomies($this->module, $this->optionalType);
                break;

            }
        }

        /**
         * Fetch users and their meta information
         * @param $mode
         *
         * @return array
         */
        public function FetchUsers($mode = null)
        {
            global $wpdb;
            self::generateHeaders($this->module, $this->optionalType);
            $get_available_user_ids = "select DISTINCT ID from {$wpdb->prefix}users u join {$wpdb->prefix}usermeta um on um.user_id = u.ID";
            // Check for specific period
            if ($this->conditions['specific_period']['is_check'] == 'true')
            {
                if ($this->conditions['specific_period']['from'] == $this->conditions['specific_period']['to'])
                {
                    $get_available_user_ids .= " where u.user_registered >= '" . $this->conditions['specific_period']['from'] . "'";
                }
                else
                {
                    $get_available_user_ids .= " where u.user_registered >= '" . $this->conditions['specific_period']['from'] . "' and u.user_registered <= '" . $this->conditions['specific_period']['to'] . " 23:00:00'";
                }
            }
            $availableUsers = $wpdb->get_col($get_available_user_ids);

            if (!empty($this->conditions['specific_period']['is_check']) && $this->conditions['specific_period']['is_check'] == 'true')
            {
                if ($this->conditions['specific_period']['from'] == $this->conditions['specific_period']['to'])
                {
                    $availableUserss = array();
                    foreach ($availableUsers as $user_value)
                    {
                        $get_user_date_time = $wpdb->get_results($wpdb->prepare("SELECT user_registered FROM {$wpdb->prefix}users WHERE ID=$user_value") , ARRAY_A);
                        $get_user_date = date("Y-m-d", strtotime($get_user_date_time[0]['user_registered']));
                        if ($get_user_date == $this->conditions['specific_period']['from'])
                        {
                            $get_user_id_value[] = $user_value;
                        }

                    }
                    $this->totalRowCount = count($get_user_id_value);
                    $availableUserss = $get_user_id_value;
                }
                else
                {
                    $this->totalRowCount = count($availableUsers);
                    $get_available_user_ids .= " order by ID asc limit $this->offset, $this->limit";
                    $availableUserss = $wpdb->get_col($get_available_user_ids);
                }
            }
            else
            {
                $this->totalRowCount = count($availableUsers);
                $get_available_user_ids .= " order by ID asc limit $this->offset, $this->limit";
                $availableUserss = $wpdb->get_col($get_available_user_ids);
            }

            if (!empty($availableUserss))
            {
                $whereCondition = '';
                foreach ($availableUserss as $userId)
                {
                    if ($whereCondition != '')
                    {
                        $whereCondition = $whereCondition . ',' . $userId;
                    }
                    else
                    {
                        $whereCondition = $userId;
                    }
                    // Prepare the user details to be export
                    $query_to_fetch_users = "SELECT * FROM {$wpdb->prefix}users where ID in ($whereCondition);";
                    $users = $wpdb->get_results($query_to_fetch_users);
                    if (!empty($users))
                    {
                        foreach ($users as $userInfo)
                        {
                            foreach ($userInfo as $userKey => $userVal)
                            {
                                $this->data[$userId][$userKey] = $userVal;
                            }
                        }
                    }
                    // Prepare the user meta details to be export
                    $query_to_fetch_users_meta = $wpdb->prepare("SELECT user_id, meta_key, meta_value FROM  {$wpdb->prefix}users wp JOIN {$wpdb->prefix}usermeta wpm  ON wpm.user_id = wp.ID where ID= %d", $userId);
                    $userMeta = $wpdb->get_results($query_to_fetch_users_meta);

                    $wptypesfields = get_option('wpcf-usermeta');
                    $wptypesfields = get_option('wpcf-usermeta');

                    if (!empty($wptypesfields))
                    {
                        $i = 1;
                        foreach ($wptypesfields as $key => $value)
                        {
                            $typesf[$i] = 'wpcf-' . $key;
                            $typeOftypesField[$typesf[$i]] = $value['type'];
                            $i++;
                        }
                    }
                    if (!empty($userMeta))
                    {
                        foreach ($userMeta as $userMetaInfo)
                        {
                            if ($userMetaInfo->meta_key == 'wp_capabilities')
                            {
                                $userRole = $this->getUserRole($userMetaInfo->meta_value);
                                $this->data[$userId]['role'] = $userRole;
                            }
                            elseif ($userMetaInfo->meta_key == 'description')
                            {
                                $this->data[$userId]['biographical_info'] = $userMetaInfo->meta_value;
                            }
                            elseif ($userMetaInfo->meta_key == 'comment_shortcuts')
                            {
                                $this->data[$userId]['enable_keyboard_shortcuts'] = $userMetaInfo->meta_value;
                            }
                            elseif ($userMetaInfo->meta_key == 'show_admin_bar_front')
                            {
                                $this->data[$userId]['show_toolbar'] = $userMetaInfo->meta_value;
                            }
                            elseif ($userMetaInfo->meta_key == 'rich_editing')
                            {
                                $this->data[$userId]['disable_visual_editor'] = $userMetaInfo->meta_value;
                            }
                            elseif ($userMetaInfo->meta_key == 'locale')
                            {
                                $this->data[$userId]['language'] = $userMetaInfo->meta_value;
                            }
                            elseif (isset($typesf) && in_array($userMetaInfo->meta_key, $typesf))
                            {
                                $typeoftype = $typeOftypesField[$userMetaInfo->meta_key];
                                if (is_serialized($userMetaInfo->meta_value))
                                {
                                    $typefileds = unserialize($userMetaInfo->meta_value);
                                    $typedata = "";
                                    foreach ($typefileds as $key2 => $value2)
                                    {
                                        if (is_array($value2))
                                        {
                                            foreach ($value2 as $key3 => $value3)
                                            {
                                                $typedata .= $value3 . ',';
                                            }
                                        }
                                        else $typedata .= $value2 . ',';
                                    }
                                    if (preg_match('/wpcf-/', $userMetaInfo->meta_key))
                                    {
                                        $userMetaInfo->meta_key = preg_replace('/wpcf-/', '', $userMetaInfo->meta_key);
                                        $this->data[$userId][$userMetaInfo->meta_key] = substr($typedata, 0, -1);
                                    }
                                }
                                elseif ($typeoftype == 'date')
                                {
                                    $this->data[$userId][$userMetaInfo->meta_key] = date('Y-m-d', $userMetaInfo->meta_value);
                                }
                                $multi_row = '_' . $userMetaInfo->meta_key . '-sort-order';

                                $multi_data = get_user_meta($userId, $multi_row);
                                $multi_data = $multi_data[0];
                                if (is_array($multi_data))
                                {
                                    foreach ($multi_data as $k => $mid)
                                    {
                                        $m_data = $this->get_common_post_metadata($mid);
                                        if ($typeoftype == 'date') $multi_data[$k] = date('Y-m-d H:i:s', $m_data['meta_value']);
                                        else $multi_data[$k] = $m_data['meta_value'];
                                    }
                                    $this->data[$userId][$userMetaInfo->meta_key] = implode('|', $multi_data);
                                    if (preg_match('/wpcf-/', $userMetaInfo->meta_key))
                                    {
                                        $userMetaInfo->meta_key = preg_replace('/wpcf-/', '', $userMetaInfo->meta_key);

                                        $this->data[$userId][$userMetaInfo->meta_key] = implode('|', $multi_data);
                                    }
                                }
                                else
                                {
                                    if (preg_match('/wpcf-/', $userMetaInfo->meta_key))
                                    {
                                        $userMetaInfo->meta_key = preg_replace('/wpcf-/', '', $userMetaInfo->meta_key);
                                        $this->data[$userId][$userMetaInfo
                                            ->meta_key] = $userMetaInfo->meta_value;
                                    }
                                }
                            }

                            else
                            {

                                $this->data[$userId][$userMetaInfo
                                    ->meta_key] = $userMetaInfo->meta_value;
                            }
                        }
                        // Prepare the buddy meta details to be export
                        if (is_plugin_active('buddypress/bp-loader.php'))
                        {
                            $query_to_fetch_buddy_meta = $wpdb->prepare("SELECT user_id,field_id,value,name FROM {$wpdb->prefix}bp_xprofile_data bxd inner join {$wpdb->prefix}users wp  on bxd.user_id = wp.ID inner join {$wpdb->prefix}bp_xprofile_fields bxf on bxf.id = bxd.field_id where user_id=%d", $userId);
                            $buddy = $wpdb->get_results($query_to_fetch_buddy_meta);
                            if (!empty($buddy))
                            {
                                foreach ($buddy as $buddyInfo)
                                {
                                    foreach ($buddyInfo as $field_id => $value)
                                    {
                                        $this->data[$userId][$buddyInfo
                                            ->name] = $buddyInfo->value;
                                    }
                                }
                            }
                        }
                        ExportExtension::$post_export->getPostsMetaDataBasedOnRecordId($userId, $this->module, $this->optionalType);
                    }
                }
            }

            $result = self::finalDataToExport($this->data, $this->module);
            if ($mode == null) self::proceedExport($result);
            else return $result;
        }

        public function mergeWithUserMeta($acf_field_values)
        {

            foreach ($acf_field_values as $acf_field_value)
            {

            }
        }

        /**
         * Fetch all Comments
         * @param $mode
         *
         * @return array
         */
        public function FetchComments($mode = null)
        {
            global $wpdb;
            self::generateHeaders($this->module, $this->optionalType);
            $get_comments = "select * from {$wpdb->prefix}comments";
            // Check status
            if (isset($this->conditions['specific_status']['is_check']) && $this->conditions['specific_status']['is_check'] == 'true')
            {
                if ($this->conditions['specific_status']['status'] == 'Pending') $get_comments .= " where comment_approved = '0'";
                elseif ($this->conditions['specific_status']['status'] == 'Approved') $get_comments .= " where comment_approved = '1'";
                else $get_comments .= " where comment_approved in ('0','1')";
            }
            else $get_comments .= " where comment_approved in ('0','1')";
            // Check for specific period
            if ($this->conditions['specific_period']['is_check'] == 'true')
            {
                if ($this->conditions['specific_period']['from'] == $this->conditions['specific_period']['to'])
                {
                    $get_comments .= " and comment_date >= '" . $this->conditions['specific_period']['from'] . "'";
                }
                else
                {
                    $get_comments .= " and comment_date >= '" . $this->conditions['specific_period']['from'] . "' and comment_date <= '" . $this->conditions['specific_period']['to'] . " 23:00:00'";
                }
            }
            // Check for specific authors
            if ($this->conditions['specific_authors']['is_check'] == '1')
            {
                if (isset($this->conditions['specific_authors']['author']))
                {
                    $get_comments .= " and comment_author_email = '" . $this->conditions['specific_authors']['author'] . "'";
                }
            }
            $comments = $wpdb->get_results($get_comments);

            if (!empty($this->conditions['specific_period']['is_check']) && $this->conditions['specific_period']['is_check'] == 'true')
            {
                if ($this->conditions['specific_period']['from'] == $this->conditions['specific_period']['to'])
                {
                    $limited_comments = array();
                    foreach ($comments as $comments_value)
                    {
                        $get_comment_date_time = $wpdb->get_results($wpdb->prepare("SELECT comment_date FROM {$wpdb->prefix}comments WHERE comment_id=$comments_value->comment_ID") , ARRAY_A);
                        $get_comment_date = date("Y-m-d", strtotime($get_comment_date_time[0]['comment_date']));
                        if ($get_comment_date == $this->conditions['specific_period']['from'])
                        {
                            $get_comment_date_value[] = $comments_value;
                        }

                    }
                    $this->totalRowCount = count($get_comment_date_value);
                    $limited_comments = $get_comment_date_value;
                }
                else
                {
                    $this->totalRowCount = count($comments);
                    $get_comments .= " order by comment_ID asc limit $this->offset, $this->limit";
                    $limited_comments = $wpdb->get_results($get_comments);
                }
            }
            else
            {
                $this->totalRowCount = count($comments);
                $get_comments .= " order by comment_ID asc limit $this->offset, $this->limit";
                $limited_comments = $wpdb->get_results($get_comments);
            }

            if (!empty($limited_comments))
            {
                foreach ($limited_comments as $commentInfo)
                {
                    $user_id = $commentInfo->user_id;
                    if (!empty($user_id))
                    {
                        $users_login = $wpdb->get_results("SELECT user_login FROM {$wpdb->prefix}users WHERE ID = '$user_id'");
                        foreach ($users_login as $users_key => $users_value)
                        {
                            foreach ($users_value as $u_key => $u_value)
                            {
                                $users_id = $u_value;
                            }
                        }
                    }
                    foreach ($commentInfo as $commentKey => $commentVal)
                    {
                        $this->data[$commentInfo->comment_ID][$commentKey] = $commentVal;
                        $this->data[$commentInfo->comment_ID]['user_id'] = isset($users_id) ? $users_id : '';
                    }
                }
            }
            $result = self::finalDataToExport($this->data, $this->module);
            if ($mode == null) self::proceedExport($result);
            else return $result;
        }

        /**
         * Generate CSV headers
         *
         * @param $module       - Module to be export
         * @param $optionalType - Exclusions
         */
        public function generateHeaders($module, $optionalType)
        {
            if ($module == 'CustomPosts' || $module == 'Taxonomies' || $module == 'Categories' || $module == 'Tags')
            {
                if($optionalType == 'event'){
                    $optionalType = 'Events';
                }
                $default = $this->get_fields($optionalType); // Call the super class function
                
            }
            else
            {
                $default = $this->get_fields($module);
            }

            $headers = [];
            foreach ($default as $key => $fields)
            {
                foreach ($fields as $groupKey => $fieldArray)
                {

                    foreach ($fieldArray as $fKey => $fVal)
                    {
                        if (is_array($fVal) || is_object($fVal))
                        {
                            foreach ($fVal as $rKey => $rVal)
                            {
                                if (!in_array($rVal['name'], $headers)) $headers[] = $rVal['name'];
                            }
                        }
                    }

                }
            }
            if ($optionalType == 'elementor_library')
            {
                $headers = [];
                $headers = ['ID', 'Template title', 'Template content', 'Style', 'Template type', 'Created time', 'Created by', 'Template status', 'Category'];
            }
            if (isset($this->eventExclusions['is_check']) && $this->eventExclusions['is_check'] == 'true'):
                $headers_with_exclusion = self::applyEventExclusion($headers, $optionalType);
                $this->headers = $headers_with_exclusion;
            else:
                $this->headers = $headers;
            endif;
        }

        /**
         * Fetch data by requested Post types
         * @param $mode
         * @return array
         */
        public function FetchDataByPostTypes($mode = null)
        {
            if (empty($this->headers)) $this->generateHeaders($this->module, $this->optionalType);
            $recordsToBeExport = ExportExtension::$post_export->getRecordsBasedOnPostTypes($this->module, $this->optionalType, $this->conditions, $this->offset, $this->limit, $this->headers);
            if (!empty($recordsToBeExport))
            {
                foreach ($recordsToBeExport as $postId)
                {
                    $this->data[$postId] = $this->getPostsDataBasedOnRecordId($postId);
                    $exp_module = $this->module;

                    if ($exp_module == 'Posts' || $exp_module == 'WooCommerce' || $exp_module == 'CustomPosts' || $exp_module == 'Categories' || $exp_module == 'Tags' || $exp_module == 'Taxonomies' || $exp_module == 'Pages')
                    {
                        $this->getWPMLData($postId, $this->optionalType, $exp_module);
                    }

                    if ($exp_module == 'Posts' || $exp_module == 'CustomPosts' || $exp_module == 'Pages' || $exp_module == 'WooCommerce')
                    {
                        if(is_plugin_active('polylang/polylang.php') || is_plugin_active('polylang-pro/polylang.php') || is_plugin_active('polylang-wc/polylang-wc.php')){
                            $this->getPolylangData($postId, $this->optionalType, $exp_module);
                        }
                    }
                    ExportExtension::$post_export->getPostsMetaDataBasedOnRecordId($postId, $this->module, $this->optionalType);
                    $this->getTermsAndTaxonomies($postId, $this->module, $this->optionalType);

                    if ($this->module == 'WooCommerce') ExportExtension::$woocom_export->getProductData($postId, $this->module, $this->optionalType);
                    if ($this->module == 'WooCommerceRefunds') ExportExtension::$woocom_export->getWooComCustomerUser($postId, $this->module, $this->optionalType);
                    if ($this->module == 'WooCommerceOrders') ExportExtension::$woocom_export->getWooComOrderData($postId, $this->module, $this->optionalType);
                    if ($this->module == 'WooCommerceVariations') ExportExtension::$woocom_export->getVariationData($postId, $this->module, $this->optionalType);
                    if ($this->module == 'WPeCommerce') ExportExtension::$ecom_export->getEcomData($postId, $this->module, $this->optionalType);
                    if ($this->module == 'WPeCommerceCoupons') ExportExtension::$ecom_export->getEcomCouponData($postId, $this->module, $this->optionalType);

                    if ($this->optionalType == 'lp_course') ExportExtension::$learnpress_export->getCourseData($postId);
                    if ($this->optionalType == 'lp_lesson') ExportExtension::$learnpress_export->getLessonData($postId);
                    if ($this->optionalType == 'lp_quiz') ExportExtension::$learnpress_export->getQuizData($postId);
                    if ($this->optionalType == 'lp_question') ExportExtension::$learnpress_export->getQuestionData($postId);
                    if ($this->optionalType == 'lp_order') ExportExtension::$learnpress_export->getOrderData($postId);

                    if ($this->optionalType == 'stm-courses') ExportExtension::$woocom_export->getCourseDataMasterLMS($postId);

                    if ($this->optionalType == 'stm-questions') ExportExtension::$woocom_export->getQuestionDataMasterLMS($postId);

                    if ($this->optionalType == 'stm-lessons') ExportExtension::$woocom_export->getLessonDataMasterLMS($postId);
                    if ($this->optionalType == 'stm-orders') ExportExtension::$woocom_export->orderDataMasterLMS($postId);
                    if ($this->optionalType == 'stm-quizzes') ExportExtension::$woocom_export->quizzDataMasterLMS($postId);
                    if ($this->optionalType == 'elementor_library') ExportExtension::$woocom_export->elementor_export($postId);
                    if ($this->optionalType == 'nav_menu_item') ExportExtension::$woocom_export->getMenuData($postId);

                    if ($this->optionalType == 'widgets') self::$instance->getWidgetData($postId, $this->headers);
                }
            }
            /** Added post format for 'standard' property */
            if ($exp_module == 'Posts' || $exp_module == 'CustomPosts' || $exp_module == 'WooCommerce')
            {
                foreach ($this->data as $id => $records)
                {
                    if (!array_key_exists('post_format', $records))
                    {
                        $records['post_format'] = 'standard';
                        $this->data[$id] = $records;
                    }
                }
            }

            /** End post format */
            $result = self::finalDataToExport($this->data, $this->module);

            if ($mode == null) self::proceedExport($result);
            else return $result;
        }

        public function getWidgetData($postId, $headers)
        {

            global $wpdb;
            $get_sidebar_widgets = get_option('sidebars_widgets');

            $total_footer_arr = [];

            foreach ($get_sidebar_widgets as $footer_key => $footer_arr)
            {
                if ($footer_key != 'wp_inactive_widgets' || $footer_key != 'array_version')
                {
                    if (strpos($footer_key, 'sidebar') !== false)
                    {
                        $get_footer = explode('-', $footer_key);
                        $footer_number = $get_footer[1];

                        foreach ($footer_arr as $footer_values)
                        {
                            $total_footer_arr[$footer_values] = $footer_number;
                        }
                    }
                }
            }

            foreach ($headers as $key => $value)
            {
                $get_widget_value[$value] = $wpdb->get_row("SELECT option_value FROM {$wpdb->prefix}options where option_name = '{$value}'", ARRAY_A);

                $header_key = explode('widget_', $value);

                if ($value == 'widget_recent-posts')
                {
                    $recent_posts = unserialize($get_widget_value[$value]['option_value']);
                    $recent_post = '';
                    foreach ($recent_posts as $dk => $dv)
                    {
                        if ($dk != '_multiwidget')
                        {
                            $post_key = $header_key[1] . '-' . $dk;
                            $recent_post .= $dv['title'] . ',' . $dv['number'] . ',' . $dv['show_date'] . '->' . $total_footer_arr[$post_key] . '|';
                        }
                    }
                    $recent_post = rtrim($recent_post, '|');
                }
                elseif ($value == 'widget_pages')
                {
                    $recent_pages = unserialize($get_widget_value[$value]['option_value']);
                    $recent_page = '';
                    foreach ($recent_pages as $dk => $dv)
                    {
                        if (isset($dv['exclude']))
                        {
                            $exclude_value = str_replace(',', '/', $dv['exclude']);
                        }

                        if ($dk != '_multiwidget')
                        {
                            $page_key = $header_key[1] . '-' . $dk;
                            $recent_page .= $dv['title'] . ',' . $dv['sortby'] . ',' . $exclude_value . '->' . $total_footer_arr[$page_key] . '|';
                        }
                    }
                    $recent_page = rtrim($recent_page, '|');
                }
                elseif ($value == 'widget_recent-comments')
                {
                    $recent_comments = unserialize($get_widget_value[$value]['option_value']);
                    $recent_comment = '';
                    foreach ($recent_comments as $dk => $dv)
                    {
                        if ($dk != '_multiwidget')
                        {
                            $comment_key = $header_key[1] . '-' . $dk;
                            $recent_comment .= $dv['title'] . ',' . $dv['number'] . '->' . $total_footer_arr[$comment_key] . '|';
                        }
                    }
                    $recent_comment = rtrim($recent_comment, '|');
                }
                elseif ($value == 'widget_archives')
                {
                    $recent_archives = unserialize($get_widget_value[$value]['option_value']);
                    $recent_archive = '';
                    foreach ($recent_archives as $dk => $dv)
                    {
                        if ($dk != '_multiwidget')
                        {
                            $archive_key = $header_key[1] . '-' . $dk;
                            $recent_archive .= $dv['title'] . ',' . $dv['count'] . ',' . $dv['dropdown'] . '->' . $total_footer_arr[$archive_key] . '|';
                        }
                    }
                    $recent_archive = rtrim($recent_archive, '|');
                }
                elseif ($value == 'widget_categories')
                {
                    $recent_categories = unserialize($get_widget_value[$value]['option_value']);
                    $recent_category = '';
                    foreach ($recent_categories as $dk => $dv)
                    {
                        if ($dk != '_multiwidget')
                        {
                            $cat_key = $header_key[1] . '-' . $dk;
                            $recent_category .= $dv['title'] . ',' . $dv['count'] . ',' . $dv['hierarchical'] . ',' . $dv['dropdown'] . '->' . $total_footer_arr[$cat_key] . '|';
                        }
                    }
                    $recent_category = rtrim($recent_category, '|');
                }
            }

            $this->data[$postId]['widget_recent-posts'] = $recent_post;
            $this->data[$postId]['widget_pages'] = $recent_page;
            $this->data[$postId]['widget_recent-comments'] = $recent_comment;
            $this->data[$postId]['widget_archives'] = $recent_archive;
            $this->data[$postId]['widget_categories'] = $recent_category;
        }

        /**
         * Function used to fetch the Terms & Taxonomies for the specific posts
         *
         * @param $id
         * @param $type
         * @param $optionalType
         */
        public function getTermsAndTaxonomies($id, $type, $optionalType)
        {
            $TermsData = array();

            if ($type == 'WooCommerce' || ($type == 'CustomPosts' && $type == 'WooCommerce'))
            {
                $type = 'product';
                $postTags = '';
                $taxonomies = get_object_taxonomies($type);
                $get_tags = get_the_terms($id, 'product_tag');
                if ($get_tags)
                {
                    foreach ($get_tags as $tags)
                    {
                        $postTags .= $tags->name . ',';
                    }
                }
                $postTags = substr($postTags, 0, -1);
                $this->data[$id]['product_tag'] = $postTags;
                foreach ($taxonomies as $taxonomy)
                {
                    $postCategory = '';
                    if ($taxonomy == 'product_cat' || $taxonomy == 'product_category')
                    {
                        $get_categories = get_the_terms($id, $taxonomy);
                        if ($get_categories)
                        {
                            $postCategory = $this->hierarchy_based_term_name($get_categories, $taxonomy);
                            // foreach($get_categories as $category){
                            // 	$postCategory .= $this->hierarchy_based_term_name($category, $taxonomy) . ',';
                            // }
                            
                        }
                        $postCategory = substr($postCategory, 0, -1);
                        $this->data[$id]['product_category'] = $postCategory;
                    }
                    else
                    {
                        $get_categories = get_the_terms($id, $taxonomy);
                        if ($get_categories)
                        {
                            $postCategory = $this->hierarchy_based_term_name($get_categories, $taxonomy);
                            // foreach($get_categories as $category){
                            // 	$postCategory .= $this->hierarchy_based_term_name($category, $taxonomy) . ',';
                            // }
                            
                        }
                        $postCategory = substr($postCategory, 0, -1);
                        $this->data[$id][$taxonomy] = $postCategory;
                    }
                }
                if ($type == 'WooCommerce' && $type != 'CustomPosts')
                {
                    $product = wc_get_product($id);
                    $pro_type = $product->get_type();
                    switch ($pro_type)
                    {
                        case 'simple':
                            $product_type = 1;
                        break;
                        case 'grouped':
                            $product_type = 2;
                        break;
                        case 'external':
                            $product_type = 3;
                        break;
                        case 'variable':
                            $product_type = 4;
                        break;
                        case 'subscription':
                            $product_type = 5;
                        break;
                        case 'variable-subscription':
                            $product_type = 6;
                        break;
                        case 'bundle':
                            $product_type = 7;
                        break;
                        default:
                            $product_type = 1;
                        break;
                    }
                    $this->data[$id]['product_type'] = $product_type;
                }

                //product_shipping_class
                $shipping = get_the_terms($id, 'product_shipping_class');
                if ($shipping)
                {
                    $taxo_shipping = $shipping[0]->name;
                    $this->data[$id]['product_shipping_class'] = $taxo_shipping;
                }
                //product_shipping_class
                
            }
            else if ($type == 'WPeCommerce')
            {
                $type = 'wpsc-product';
                $postTags = $postCategory = '';
                $taxonomies = get_object_taxonomies($type);
                $get_tags = get_the_terms($id, 'product_tag');
                if ($get_tags)
                {
                    foreach ($get_tags as $tags)
                    {
                        $postTags .= $tags->name . ',';
                    }
                }
                $postTags = substr($postTags, 0, -1);
                $this->data[$id]['product_tag'] = $postTags;
                foreach ($taxonomies as $taxonomy)
                {
                    $postCategory = '';
                    if ($taxonomy == 'wpsc_product_category')
                    {
                        $get_categories = wp_get_post_terms($id, $taxonomy);
                        if ($get_categories)
                        {
                            $postCategory = $this->hierarchy_based_term_name($get_categories, $taxonomy);

                        }
                        $postCategory = substr($postCategory, 0, -1);
                        $this->data[$id]['product_category'] = $postCategory;
                    }
                    else
                    {
                        $get_categories = wp_get_post_terms($id, $taxonomy);
                        if ($get_categories)
                        {
                            $postCategory = $this->hierarchy_based_term_name($get_categories, $taxonomy);

                        }
                        $postCategory = substr($postCategory, 0, -1);
                        $this->data[$id]['product_category'] = $postCategory;
                    }
                }
            }
            else
            {
                global $wpdb;
                $postTags = $postCategory = '';
                $taxonomyId = $wpdb->get_col($wpdb->prepare("select term_taxonomy_id from {$wpdb->prefix}term_relationships where object_id = %d", $id));
                $taxo = [];
                $termTaxonomyIds = array();
                foreach ($taxonomyId as $taxonomyIds)
                {
                    $termTaxonomyId = $wpdb->get_results($wpdb->prepare("select term_id from {$wpdb->prefix}term_taxonomy where term_taxonomy_id = %d", $taxonomyIds));

                    foreach ($termTaxonomyId as $term)
                    {
                        $termTaxonomyIds[] = $term->term_id;
                    }
                }
                foreach ($termTaxonomyIds as $taxonomy)
                {

                    $taxo[] = get_term($taxonomy);
                }
                foreach ($taxonomyId as $taxonomy)
                {
                    $taxonomytypeid = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}term_taxonomy WHERE term_taxonomy_id='$taxonomy' ");
                    if ($taxonomytypeid[0]->taxonomy == 'course_category')
                    {
                        $taxonomyTypeId = $wpdb->get_col($wpdb->prepare("select term_id from {$wpdb->prefix}term_taxonomy where term_taxonomy_id = %d", $taxonomytypeid[0]->term_taxonomy_id));
                        $taxonomy_Type_Id = $taxonomyTypeId[0];
                        $taxo0[] = get_term($taxonomy_Type_Id);
                    }
                    if ($taxonomytypeid[0]->taxonomy == 'course_tag')
                    {
                        $taxonomyTypeId1 = $wpdb->get_col($wpdb->prepare("select term_id from {$wpdb->prefix}term_taxonomy where term_taxonomy_id = %d", $taxonomytypeid[0]->term_taxonomy_id));
                        $taxonomy_Type_Id1 = $taxonomyTypeId1[0];
                        $taxo2[] = get_term($taxonomy_Type_Id1);
                    }
                }

                if (!empty($taxo))
                {
                    foreach ($taxo as $key => $taxo_val)
                    {
                        if ($taxo_val->taxonomy == 'category')
                        {
                            $taxo1[] = $taxo_val;
                        }
                    }
                }

                if (!empty($taxonomyId))
                {
                    foreach ($taxonomyId as $taxonomy)
                    {
                        $taxonomyType = $wpdb->get_col($wpdb->prepare("select taxonomy from {$wpdb->prefix}term_taxonomy where term_taxonomy_id = %d", $taxonomy));
                        if (!empty($taxonomyType))
                        {
                            foreach ($taxonomyType as $taxanomy_name)
                            {
                                if ($taxanomy_name == 'category')
                                {
                                    $termName = 'post_category';
                                }
                                else
                                {
                                    $termName = $taxanomy_name;
                                }
                                if (in_array($termName, $this->headers))
                                {
                                    if ($termName != 'post_tag' && $termName != 'post_category')
                                    {

                                        $taxonomyData = $wpdb->get_col($wpdb->prepare("select name from {$wpdb->prefix}terms where term_id = %d", $taxonomy));
                                        if (!empty($taxonomyData))
                                        {

                                            if (isset($TermsData[$termName]))
                                            {
                                                $this->data[$id][$termName] = $TermsData[$termName] . ',' . $taxonomyData[0];
                                            }
                                            else
                                            {
                                                $get_exist_data = $this->data[$id][$termName];
                                            }

                                            if ($get_exist_data == '')
                                            {
                                                $this->data[$id][$termName] = $taxonomyData[0];
                                            }
                                            else
                                            {
                                                $taxonomyID = $wpdb->get_col($wpdb->prepare("select term_id from {$wpdb->prefix}terms where name = %s", $taxonomyData[0]));
                                                if ($taxanomy_name == 'course_category')
                                                {
                                                    foreach ($taxo0 as $taxo_key => $taxo_value)
                                                    {
                                                        $postterm1 .= $taxo_value->name . ',';
                                                    }
                                                    $this->data[$id][$termName] = rtrim($postterm1, ',');
                                                }
                                                elseif ($taxanomy_name == 'course_tag')
                                                {
                                                    foreach ($taxo2 as $taxo_key1 => $taxo_value1)
                                                    {
                                                        $postterm2 .= $taxo_value1->name . ',';
                                                    }
                                                    $this->data[$id][$termName] = rtrim($postterm2, ',');
                                                }
                                                else
                                                {
                                                    $postterm = substr($this->hierarchy_based_term_name($taxo, $taxanomy_name) , 0, -1);
                                                    $this->data[$id][$termName] = $postterm;
                                                }
                                            }

                                        }
                                    }
                                    else
                                    {
                                        if (!isset($TermsData['post_tag']))
                                        {
                                            if ($termName == 'post_tag')
                                            {
                                                $postTags = '';
                                                $get_tags = wp_get_post_tags($id, array(
                                                    'fields' => 'names'
                                                ));
                                                foreach ($get_tags as $tags)
                                                {
                                                    $postTags .= $tags . ',';
                                                }
                                                $postTags = substr($postTags, 0, -1);
                                                $this->data[$id][$termName] = $postTags;
                                            }
                                            if ($termName == 'post_category')
                                            {
                                                $postCategory = '';
                                                $get_categories = wp_get_post_categories($id, array(
                                                    'fields' => 'names'
                                                ));

                                                $postterm1 = substr($this->hierarchy_based_term_name($taxo1, $taxanomy_name) , 0, -1);
                                                $this->data[$id][$termName] = $postterm1;

                                            }

                                        }
                                    }
                                }
                                else
                                {
                                    $this->data[$id][$termName] = '';
                                }
                            }
                        }
                    }
                }
            }
        }

        /**
         * Get user role based on the capability
         * @param null $capability  - User capability
         * @return int|string       - Role of the user
         */
        public function getUserRole($capability = null)
        {
            if ($capability != null)
            {
                $getRole = unserialize($capability);
                foreach ($getRole as $roleName => $roleStatus)
                {
                    $role = $roleName;
                }
                return $role;
            }
            else
            {
                return 'subscriber';
            }
        }

        /**
         * Get activated plugins
         * @return mixed
         */
        public function get_active_plugins()
        {
            $active_plugins = get_option('active_plugins');
            return $active_plugins;
        }

        public function array_to_xml($data, &$xml_data)
        {
            foreach ($data as $key => $value)
            {
                if (is_numeric($key))
                {
                    $key = 'item'; //dealing with <0/>..<n/> issues
                    
                }
                if (is_array($value))
                {
                    $subnode = $xml_data->addChild($key);
                    $this->array_to_xml($value, $subnode);
                }
                else
                {
                    $xml_data->addChild("$key", htmlspecialchars("$value"));
                }
            }
        }
        public function getPolylangData($id, $optional_type, $exp_module)
        {
            global $wpdb;
            global $sitepress;
            $post_title = '';
            $terms = $wpdb->get_results("select term_taxonomy_id from $wpdb->term_relationships where object_id ='{$id}'");
            $terms_id = json_decode(json_encode($terms) , true);
            if(is_plugin_active('polylang-pro/polylang.php')){
                if($exp_module == 'Categories' || $exp_module == 'Tags' || $exp_module == 'Taxonomies'){
                    $get_language = pll_get_term_language($id);
                    $get_translation = pll_get_term_translations($id);
                    unset($get_translation[$get_language]);
                    $this->data[$id]['language_code'] = $get_language;
                    foreach($get_translation as $trans_key => $trans_val){
                        $title = $wpdb->get_var("SELECT name FROM {$wpdb->prefix}terms where term_id=$trans_val");
                        $post_title .= $title.',';
                    }
                    $this->data[$id]['translated_taxonomy_title'] = rtrim($post_title,',');
                }
                else{
                    $get_language=pll_get_post_language( $id );
                    $get_translation=pll_get_post_translations($id);
                    unset($get_translation[$get_language]);
                    $this->data[$id]['language_code'] = $get_language;
                    foreach($get_translation as $trans_key => $trans_val){
                        $title = $wpdb->get_var("SELECT post_title FROM {$wpdb->prefix}posts where id=$trans_val");
                        $post_title .= $title.',';
                    }
                    $this->data[$id]['translated_post_title'] = rtrim($post_title,',');
                }
            }
            else{
            foreach ($terms_id as $termkey => $termvalue)
            {
                $post_title = '';
                $termids = $termvalue['term_taxonomy_id'];
                $check = $wpdb->get_var("select taxonomy from $wpdb->term_taxonomy where term_id ='{$termids}'");
                if ($check == 'category')
                {
                    $category = $wpdb->get_var("select name from $wpdb->terms where term_id ='{$termids}'");
                }
                elseif ($check == 'language')
                {
                    $language = $wpdb->get_var("select description from $wpdb->term_taxonomy where term_id ='{$termids}'");
                    $lang = unserialize($language);
                    $langcode = explode('_', $lang['locale']);
                    $lang_code = $langcode[0];
                    $this->data[$id]['language_code'] = $lang_code;

                }

                elseif($check == 'term_language'){
                    if($exp_module == 'Categories' || $exp_module == 'Tags' || $exp_module == 'Taxonomies'){
                        $language = $wpdb->get_var("select description from $wpdb->term_taxonomy where term_id ='{$termids}'");
                        $lang = unserialize($language);
                        $langcode = explode('_', $lang['locale']);
                        $lang_code = $langcode[0];
                        if(empty($this->data[$id]['language_code'])){
                            $this->data[$id]['language_code'] = $lang_code;
                        }
                        
                    }
                }
                elseif(($exp_module == 'Categories' || $exp_module == 'Tags') &&$check == 'term_translations'){
                    $description = $wpdb->get_var("select description from $wpdb->term_taxonomy where term_id ='{$termids}'");
                    $desc = unserialize($description);
                    $post_id = is_array($desc) ? array_values($desc) : array();
                    // $postid = min($post_id);
                    foreach($post_id as $post_key => $post_value){
                        if($id == $post_value){
                            unset($post_id[$post_key]);
                        }
                    }
                   
                    foreach($post_id as $trans_key => $trans_val){
                        $title = $wpdb->get_var("SELECT name FROM {$wpdb->prefix}terms where term_id=$trans_val");
                        $post_title .= $title.',';
                    }

                    $this->data[$id]['translated_taxonomy_title'] = rtrim($post_title,',');
                }
                elseif (($exp_module !== 'Categories' && $exp_module !== 'Tags') &&  $check == 'post_translations')
                {
                    $description = $wpdb->get_var("select description from $wpdb->term_taxonomy where term_id ='{$termids}'");
                    $desc = unserialize($description);
                    $post_id = is_array($desc) ? array_values($desc) : array();
                    // $postid = min($post_id);
                    foreach($post_id as $post_key => $post_value){
                        if($id == $post_value){
                            unset($post_id[$post_key]);
                        }
                    }
                    foreach($post_id as $trans_key => $trans_val){
                     $post_title = $wpdb->get_var("select post_title from $wpdb->posts where ID ='{$trans_val}'");
                        $this->data[$id]['translated_post_title'] = $post_title;
                    }
                }
                elseif ($check == 'post_tag')
                {
                    $tag = $wpdb->get_var("select name from $wpdb->terms where term_id ='{$termids}'");

                }
            }
        }
        }

        /**
         * Export Data
         * @param $data
         */
        public function proceedExport($data)
        {

            if (is_user_logged_in() && current_user_can('administrator'))
            {
                $upload_dir = ABSPATH . 'wp-content/uploads/smack_uci_uploads/exports/';
                if (!is_dir($upload_dir))
                {
                    wp_mkdir_p($upload_dir);
                }
                $base_dir = wp_upload_dir();
                $upload_url = $base_dir['baseurl'] . '/smack_uci_uploads/exports/';
                chmod($upload_dir, 0777);
            }

            if ($this->checkSplit == 'true')
            {
                $i = 1;
                while ($i != 0)
                {
                    $file = $upload_dir . $this->fileName . '_' . $i . '.' . $this->exportType;
                    if (file_exists($file))
                    {
                        $allfiles[$i] = $file;
                        $i++;
                    }
                    else break;
                }
                $fileURL = $upload_url . $this->fileName . '_' . $i . '.' . $this->exportType;
            }
            else
            {
                $file = $upload_dir . $this->fileName . '.' . $this->exportType;
                $fileURL = $upload_url . $this->fileName . '.' . $this->exportType;
            }

            $spsize = 100;
            if ($this->offset == 0)
            {
                if (file_exists($file)) unlink($file);
            }

            $checkRun = "no";
            if ($this->checkSplit == 'true' && ($this->totalRowCount - $this->offset) > 0)
            {
                $checkRun = 'yes';
            }
            if ($this->checkSplit != 'true')
            {
                $checkRun = 'yes';
            }

            if ($checkRun == 'yes')
            {
                if ($this->exportType == 'xml')
                {
                    $xml_data = new \SimpleXMLElement('<?xml version="1.0"?><data></data>');
                    $this->array_to_xml($data, $xml_data);
                    $result = $xml_data->asXML($file);
                }
                else
                {
                    if ($this->exportType == 'json') $csvData = json_encode($data);
                    else $csvData = $this->unParse($data, $this->headers);
                    try
                    {

                        file_put_contents($file, $csvData, FILE_APPEND | LOCK_EX);

                    }
                    catch(\Exception $e)
                    {
                        // TODO - write exception in log
                        
                    }
                }
            }

            $this->offset = $this->offset + $this->limit;

            $filePath = $upload_dir . $this->fileName . '.' . $this->exportType;
            $filename = $fileURL;
            if (($this->offset) > ($this->totalRowCount) && $this->checkSplit == 'true')
            {
                $allfiles[$i] = $file;
                $zipname = $upload_dir . $this->fileName . '.' . 'zip';
                $zip = new \ZipArchive;
                $zip->open($zipname, \ZipArchive::CREATE);
                foreach ($allfiles as $allfile)
                {
                    $newname = str_replace($upload_dir, '', $allfile);
                    $zip->addFile($allfile, $newname);
                }
                $zip->close();
                $fileURL = $upload_url . $this->fileName . '.' . 'zip';
                foreach ($allfiles as $removefile)
                {
                    unlink($removefile);
                }
                $filename = $upload_url . $this->fileName . '.' . 'zip';
            }
            if ($this->checkSplit == 'true' && !($this->offset) > ($this->totalRowCount))
            {
                $responseTojQuery = array(
                    'success' => false,
                    'new_offset' => $this->offset,
                    'limit' => $this->limit,
                    'total_row_count' => $this->totalRowCount,
                    'exported_file' => $zipname,
                    'exported_path' => $zipname,
                    'export_type' => $this->exportType
                );
            }
            elseif ($this->checkSplit == 'true' && (($this->offset) > ($this->totalRowCount)))
            {
                $responseTojQuery = array(
                    'success' => true,
                    'new_offset' => $this->offset,
                    'limit' => $this->limit,
                    'total_row_count' => $this->totalRowCount,
                    'exported_file' => $fileURL,
                    'exported_path' => $fileURL,
                    'export_type' => $this->exportType
                );
            }
            elseif (!(($this->offset) > ($this->totalRowCount)))
            {
                $responseTojQuery = array(
                    'success' => false,
                    'new_offset' => $this->offset,
                    'limit' => $this->limit,
                    'total_row_count' => $this->totalRowCount,
                    'exported_file' => $filename,
                    'exported_path' => $filePath,
                    'export_type' => $this->exportType
                );
            }
            else
            {
                $responseTojQuery = array(
                    'success' => true,
                    'new_offset' => $this->offset,
                    'limit' => $this->limit,
                    'total_row_count' => $this->totalRowCount,
                    'exported_file' => $filename,
                    'exported_path' => $filePath,
                    'export_type' => $this->exportType
                );
            }

            // $responseTojQuery["file_path"]=WP_PLUGIN_DIR . '/wp-ultimate-exporter/download.php';

            if ($this->export_mode == 'normal')
            {
                echo wp_json_encode($responseTojQuery);
                wp_die();
            }
            elseif ($this->export_mode == 'FTP')
            {
                $this->export_log = $responseTojQuery;
            }
        }

        /**
         * Fetch ACF field information to be export
         * @param $recordId - Id of the Post (or) Page (or) Product (or) User
         */
        public function FetchACFData($recordId)
        {

        }

        /**
         * Get post data based on the record id
         * @param $id       - Id of the records
         * @return array    - Data based on the requested id.
         */
        public function getPostsDataBasedOnRecordId($id)
        {
            global $wpdb;
            $PostData = array();
            $query1 = $wpdb->prepare("SELECT wp.* FROM {$wpdb->prefix}posts wp where ID=%d", $id);
            $result_query1 = $wpdb->get_results($query1);
            if (!empty($result_query1))
            {
                foreach ($result_query1 as $posts)
                {
                    if($posts->post_type =='event' ||$posts->post_type =='event-recurring'){

                        $loc=get_post_meta($id , '_location_id' , true);
                        $event_id=get_post_meta($id , '_event_id' , true);
                        $res = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}em_locations WHERE location_id='$loc' "); 
        
                        if($res){
                            foreach($res as $location){
                                unset($location-> post_content);	
                            $posts=array_merge((array)$posts,(array)$location);
                            }
                        }

                            $ticket = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}em_tickets WHERE event_id='$event_id' "); 
                                                                                            
                            $ticket[0]=isset($ticket[0])?$ticket[0]:'';
                            $ticket_meta= $ticket[0];
                            if(isset($ticket_meta->{'ticket_meta'})){
                            $ticket_meta_value=$ticket_meta->{'ticket_meta'};
                            }
                            $ticket_meta_value=isset($ticket_meta_value)?$ticket_meta_value:'';
                            $ticket_value=unserialize($ticket_meta_value);
                            if(isset($ticket_id)){
                            $ticket_values = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}em_tickets WHERE ticket_id='$ticket_id' ");
                            }
                            $count=count($ticket);
                            if($count>1){
                                $ticknamevalue = '';
                                $tickidvalue = '';
                                $eventidvalue = '';
                                $tickdescvalue = '';
                                $tickpricevalue = '';
                                $tickstartvalue = '';
                                $tickendvalue = '';
                                $tickminvalue = '';
                                $tickmaxvalue = '';
                                $tickspacevalue = '';
                                $tickmemvalue = '';
                                $tickmemrolevalue = '';									
                                $tickguestvalue = '';
                                $tickreqvalue = '';
                                $tickparvalue = '';
                                $tickordervalue = '';
                                $tickmetavalue = '';
                                $tickstartdays = '';
                                $tickenddays = '';
                                $tickstarttime = '';
                                $tickendtime = '';
                                $t=0;
                                
                                foreach($ticket as $tic => $ticval){
                                    $ticknamevalue .= $ticval->ticket_name . ', ';
                                    $tickidvalue .=$ticval->ticket_id . ', ';
                                    $eventidvalue .=$ticval->event_id . ', ';
                                    $tickdescvalue .=$ticval->ticket_description . ', ';
                                    $tickpricevalue .=$ticval->ticket_price . ', ';
                                    $tickstartvalue .=$ticval->ticket_start . ', ';
                                    $tickendvalue .=$ticval->ticket_end . ', ';
                                    $tickminvalue .=$ticval->ticket_min . ', ';
                                    $tickmaxvalue .=$ticval->ticket_max . ', ';
                                    $tickspacevalue .=$ticval->ticket_spaces . ', ';
                                    $tickmemvalue .=$ticval->ticket_members . ', ';
                                    $tickmemroles =unserialize($ticval->ticket_members_roles);
                                    $tickmemroleval=implode('| ',(array)$tickmemroles);
                                    $tickmemrolevalue .=$tickmemroleval . ', ';
                                
                                    
                                    $tickguestvalue .=$ticval->ticket_guests . ', ';
                                    $tickreqvalue .=$ticval->ticket_required . ', ';
                                    $tickparvalue .=$ticval->ticket_parent . ', ';
                                    $tickordervalue .=$ticval->ticket_order . ', ';
                                    $tickmetavalue .=$ticval->ticket_meta . ', ';
                                    $ticket[$t]=isset($ticket[$t])?$ticket[$t]:'';
                                    $ticket_meta= $ticket[$t];
                                    if(isset($ticket_meta->{'ticket_meta'})){
                                    $ticket_meta_value=$ticket_meta->{'ticket_meta'};
                                    }
                                    $ticket_meta_value=isset($ticket_meta_value)?$ticket_meta_value:'';
                                    if(!empty($ticket_meta_value)){
                                        $ticket_value=unserialize($ticket_meta_value);
                                    }
                                    
                                    foreach($ticket_value as $tickval => $val){
                                        $tickstartdays .= $val['start_days'].', ';
                                        $tickenddays .= $val['end_days'].', ';
                                        $tickstarttime .= $val['start_time'].', ';
                                        $tickendtime .= $val['end_time'].', ';
                                    }
                                
                                    $ticknamevalues = rtrim($ticknamevalue, ', ');
                                    $tickidvalues = rtrim($tickidvalue, ', ');
                                    $eventidvalues=rtrim($eventidvalue, ', ');
                                    $tickdescvalues=rtrim($tickdescvalue, ', ');
                                    $tickpricevalues =rtrim($tickpricevalue, ', ');
                                    $tickstartvalues   =rtrim($tickstartvalue, ', ');
                                    $tickendvalues   =rtrim($tickendvalue, ', ');
                                    $tickminvalues   =rtrim($tickminvalue, ', ');
                                    $tickmaxvalues =rtrim($tickmaxvalue, ', ');
                                    $tickspacevalues =rtrim($tickspacevalue, ', ');	
                                    $tickmemvalues	=rtrim($tickmemvalue, ', ');
                                    $tickmemrolevalues	=rtrim($tickmemrolevalue, ', ');
                                    $tickguestvalues	=rtrim($tickguestvalue, ', ');
                                    $tickreqvalues	=rtrim($tickreqvalue, ', ');
                                    $tickparvalues	=rtrim($tickparvalue, ', ');
                                    $tickordervalues	=rtrim($tickordervalue, ', ');	
                                    $tickmetavalues	=rtrim($tickmetavalue, ', ');	
                                    $tickstartdaysvalues = rtrim($tickstartdays, ', ');
                                    $tickenddaysvalues = rtrim($tickenddays, ', ');
                                    $tickstarttimevalues = rtrim($tickstarttime, ', ');
                                    $tickendtimevalues = rtrim($tickendtime, ', ');	

                                    
                                    $tic_key1 = array('ticket_id', 'event_id', 'ticket_name','ticket_description','ticket_price','ticket_start','ticket_end','ticket_min','ticket_max','ticket_spaces','ticket_members','ticket_members_roles','ticket_guests','ticket_required','ticket_parent','ticket_order','ticket_meta','start_days','end_days','start_time','end_time');
                                    $tic_val1 = array($tickidvalues,$eventidvalues, $ticknamevalues,$tickdescvalues,$tickpricevalues,$tickstartvalues,$tickendvalues,$tickminvalues,$tickmaxvalues,$tickspacevalues,$tickmemvalues,$tickmemrolevalues,$tickguestvalues,$tickreqvalues,$tickparvalues,$tickordervalues,$tickmetavalues,$tickstartdaysvalues,$tickenddaysvalues,$tickstarttimevalues,$tickendtimevalues);
                                                                                                                    
                                    $tickets1 = array_combine($tic_key1,$tic_val1);
                                    $posts=array_merge((array)$posts,(array)$tickets1);
                                    $ticket_start[] = $ticval->ticket_start;
                                    
                                    $ticket_start_date = '';
                                    $ticket_start_time ='';
                                    foreach(  $ticket_start as $loc =>$locval){
                                        $date = strtotime($locval);
                                        $ticket_start_date .= date('Y-m-d', $date) . ', ';
                                        
                                        $ticket_start_time .= date('H:i:s',$date) .', ';	
                                        
        
                                    }
                                    $ticket_start_times = rtrim($ticket_start_time, ', ');
                                    $ticket_start_dates = rtrim($ticket_start_date, ', ');
                                    $ticket_end[] = trim($ticval->ticket_end);
                                    $ticket_end_time = '';
                                    $ticket_end_date = '';
                                    foreach($ticket_end as $loc => $locvalend){											
                                        if(isset($locvalend) && !empty($locvalend)){
                                            $time = strtotime($locvalend);
                                            $ticket_end_date .= date('Y-m-d', $time) .', ';
                                            $ticket_end_time .= date('H:i:s',$time) .', ';
                                        }
                    
                                    }	
                                    if(isset($ticket_start_date) && !empty($ticket_start_date)){   
                                        $ticket_end_times = rtrim($ticket_end_time, ', ');
                                        $ticket_end_dates = rtrim($ticket_end_date, ', ');
                                        $tic_key = array('ticket_start_date', 'ticket_start_time', 'ticket_end_date','ticket_end_time');
                                        $tic_val = array($ticket_start_dates,$ticket_start_times, $ticket_end_dates,$ticket_end_times);
                                        $tickets = array_combine($tic_key,$tic_val);
                                        $posts=array_merge((array)$posts,(array)$tickets);
                                    }
                                    
                                }

                            }
                            else{
                                foreach($ticket as $tic => $ticval){
                                    $posts=array_merge((array)$posts,(array)$ticval);
                                    if(isset($ticval->ticket_start)){
                                    $ticket_start=$ticval->ticket_start;
                                    }
                                    if(is_array($ticket_value)){
                                        foreach($ticket_value as $tick => $val){
                                            $posts=array_merge((array)$posts,(array)$val);
                                        }
                                    }										
                                    if(isset($ticket_start) && ($ticket_start != null)){
                                        $date = strtotime($ticket_start);																						
                                        $ticket_start_date = date('Y-m-d', $date);
                                        $ticket_start_time= date('H:i:s',$date);
                                        $ticket_end=$ticval->ticket_end;
                                        $time = strtotime($ticket_end);
                                        $ticket_end_date = date('Y-m-d', $time);
                                        $ticket_end_time= date('H:i:s',$time);
                                        $tic_key = array('ticket_start_date', 'ticket_start_time', 'ticket_end_date','ticket_end_time');
                                        $tic_val = array($ticket_start_date,$ticket_start_time, $ticket_end_date,$ticket_end_time);
                                        $tickets = array_combine($tic_key,$tic_val);
                                        $posts=array_merge((array)$posts,(array)$tickets);
                                    
                                    }
                                }
                            }
                        
                    }

                    //pods export
                    $post_type = isset($posts->post_type) ? $posts->post_type : '';
                    $p_type = $post_type;
                    $posid = $wpdb->get_results("SELECT ID FROM {$wpdb->prefix}posts  where post_name='$p_type' and post_type='_pods_pod'");
                    foreach ($posid as $podid)
                    {
                        $pods_id = $podid->ID;
                        $storage = $wpdb->get_results("SELECT meta_value FROM {$wpdb->prefix}postmeta  where post_id=$pods_id AND meta_key='storage'");
                        foreach ($storage as $pod_storage)
                        {
                            $pod_stype = $pod_storage->meta_value;
                        }
                    }
                    if (isset($pod_stype) && $pod_stype == 'table')
                    {
                        $tab = 'pods_' . $p_type;
                        $tab_val = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}$tab where id=$id");
                        foreach ($tab_val as $table_key => $table_val)
                        {
                            $posts = array_merge((array)$posts, (array)$table_val);
                        }
                    }

                    foreach ($posts as $post_key => $post_value)
                    {
                        if ($post_key == 'post_status')
                        {
                            if (is_sticky($id))
                            {
                                $PostData[$post_key] = 'Sticky';
                                $post_status = 'Sticky';
                            }
                            else
                            {
                                $PostData[$post_key] = $post_value;
                                $post_status = $post_value;
                            }
                        }
                        else
                        {
                            $PostData[$post_key] = $post_value;
                        }
                        if ($post_key == 'post_password')
                        {
                            if ($post_value)
                            {
                                $PostData['post_status'] = "{" . $post_value . "}";
                            }
                            else
                            {
                                $PostData['post_status'] = $post_status;
                            }
                        }

                        if ($post_key == 'post_author')
                        {
                            $user_info = get_userdata($post_value);
                            $PostData['post_author'] = $user_info->user_login;
                        }
                    }
                }
            }

            return $PostData;
        }

        public function getWPMLData($id, $optional_type, $exp_module)
        {
            global $wpdb;
            global $sitepress;
            if ($sitepress != null && is_plugin_active('wpml-ultimate-importer/wpml-ultimate-importer.php'))
            {
                $icl_translation_table = $wpdb->prefix . 'icl_translations';
                if ($exp_module == 'Categories' || $exp_module == 'Tags' || $exp_module == 'Taxonomies')
                {
                    $get_element_type = 'tax_' . $optional_type;
                }
                else
                {
                    $get_element_type = 'post_' . $optional_type;
                }
                $args = array(
                    'element_id' => $id,
                    'element_type' => $get_element_type
                );
                $get_language_code = apply_filters('wpml_element_language_code', null, $args);

                $get_source_language = $wpdb->get_var("select source_language_code from {$icl_translation_table} where element_id ='{$id}' and language_code ='{$get_language_code}'");

                $get_trid = apply_filters('wpml_element_trid', NULL, $id, $get_element_type);
                if (!empty($get_source_language))
                {
                    $original_element_id_prepared = $wpdb->prepare("SELECT element_id
								FROM {$wpdb->prefix}icl_translations
								WHERE trid=%d
								AND source_language_code IS NULL
								LIMIT 1", $get_trid);

                    $element_id = $wpdb->get_var($original_element_id_prepared);
                    if ($exp_module == 'Posts' || $exp_module == 'WooCommerce' || $exp_module == 'CustomPosts' || $exp_module == 'Pages')
                    {
                        $element_title = get_the_title($element_id);
                        $this->data[$id]['translated_post_title'] = $element_title;
                    }
                    else
                    {
                        $element_title = $wpdb->get_var("select name from $wpdb->terms where term_id ='{$element_id}'");
                        $this->data[$id]['translated_taxonomy_title'] = $element_title;
                    }
                }
                $this->data[$id]['language_code'] = $get_language_code;
                return $this->data[$id];
            }
        }

        public function getAttachment($id)
        {
            global $wpdb;
            $get_attachment = $wpdb->prepare("select guid from {$wpdb->prefix}posts where ID = %d AND post_type = %s", $id, 'attachment');
            $attachment = $wpdb->get_results($get_attachment);
            $attachment_file = $attachment[0]->guid;
            return $attachment_file;

        }

        public function getRepeater($parent)
        {
            global $wpdb;
            $get_fields = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}posts where post_parent = %d", $parent) , ARRAY_A);
            $i = 0;
            foreach ($get_fields as $key => $value)
            {
                $array[$i] = $value['post_excerpt'];
                $i++;
            }
            return $array;
        }

        /**
         * Get types fields
         * @return array    - Types fields
         */
        public function getTypesFields()
        {
            $getWPTypesFields = get_option('wpcf-fields');
            $typesFields = array();
            if (!empty($getWPTypesFields) && is_array($getWPTypesFields))
            {
                foreach ($getWPTypesFields as $fKey)
                {
                    $typesFields[$fKey['meta_key']] = $fKey['name'];
                }
            }
            return $typesFields;
        }

        /**
         * Final data to be export
         * @param $data     - Data to be export based on the requested information
         * @return array    - Final data to be export
         */
        public function finalDataToExport($data, $module = false)
        {
            $result = array();
            foreach ($this->headers as $key => $value)
            {
                if (empty($value))
                {
                    unset($this->headers[$key]);
                }
            }

            // Fetch Category Custom Field Values
            if ($module)
            {
                if ($module == 'Categories')
                {
                    return $this->fetchCategoryFieldValue($data, $this->module);
                }
            }

            foreach ($data as $recordId => $rowValue)
            {
                foreach ($this->headers as $hKey)
                {
                    if (array_key_exists($hKey, $rowValue) && (!empty($rowValue[$hKey])))
                    {
                        $result[$recordId][$hKey] = $this->returnMetaValueAsCustomerInput($rowValue[$hKey], $hKey);
                    }
                    else
                    {
                        $key = $hKey;
                        $rowValue['post_type'] = isset($rowValue['post_type']) ? $rowValue['post_type'] : '';
                        // Replace the third party plugin name from the fieldname
                        $key = $this->replace_prefix_aioseop_from_fieldname($key);
                        $key = $this->replace_prefix_yoast_wpseo_from_fieldname($key);
                        $key = $this->replace_prefix_wpcf_from_fieldname($key);
                        $key = $this->replace_prefix_wpsc_from_fieldname($key);
                        $key = $this->replace_underscore_from_fieldname($key);
                        $key = $this->replace_wpcr3_from_fieldname($key);
                        // Change fieldname depends on the post type
                        $key = $this->change_fieldname_depends_on_post_type($rowValue['post_type'], $key);

                        if (isset($rowValue['wpcr3_' . $key]))
                        {
                            $rowValue[$key] = $this->returnMetaValueAsCustomerInput($rowValue['wpcr3_' . $key], $hKey);
                        }
                        else
                        {
                            if (isset($rowValue['_yoast_wpseo_' . $key]))
                            { // Is available in yoast plugin
                                $rowValue[$key] = $this->returnMetaValueAsCustomerInput($rowValue['_yoast_wpseo_' . $key]);
                            }
                            else if (isset($rowValue['_aioseop_' . $key]))
                            { // Is available in all seo plugin
                                $rowValue[$key] = $this->returnMetaValueAsCustomerInput($rowValue['_aioseop_' . $key]);
                            }
                            else if (isset($rowValue['_' . $key]))
                            { // Is wp custom fields
                                $rowValue[$key] = $this->returnMetaValueAsCustomerInput($rowValue['_' . $key], $hKey);
                            }
                            else if ($fieldvalue = $this->getWoocommerceMetaValue($key, $rowValue['post_type'], $rowValue))
                            {
                                $rowValue[$key] = $fieldvalue;
                            }
                            else if (isset($rowValue['ID']) && $aioseo_field_value = $this->getaioseoFieldValue($rowValue['ID']))
                            {
                                $rowValue['og_title'] = $aioseo_field_value[0]->og_title;
                                $rowValue['og_description'] = $aioseo_field_value[0]->og_description;
                                $rowValue['custom_link'] = $aioseo_field_value[0]->canonical_url;
                                $rowValue['og_image_type'] = $aioseo_field_value[0]->og_image_type;
                                $rowValue['og_image_custom_url'] = $aioseo_field_value[0]->og_image_custom_url;
                                $rowValue['og_image_custom_fields'] = $aioseo_field_value[0]->og_image_custom_fields;
                                $rowValue['og_video'] = $aioseo_field_value[0]->og_video;
                                $rowValue['og_object_type'] = $aioseo_field_value[0]->og_object_type;
                                $value = $aioseo_field_value[0]->og_article_tags;
                                $article_tags = json_decode($value);
                                $og_article_tags = $article_tags[0]->value;
                                $rowValue['og_article_tags'] = $og_article_tags;
                                $rowValue['og_article_section'] = $aioseo_field_value[0]->og_article_section;
                                $rowValue['twitter_use_og'] = $aioseo_field_value[0]->twitter_use_og;
                                $rowValue['twitter_card'] = $aioseo_field_value[0]->twitter_card;
                                $rowValue['twitter_image_type'] = $aioseo_field_value[0]->twitter_image_type;
                                $rowValue['twitter_image_custom_url'] = $aioseo_field_value[0]->twitter_image_custom_url;
                                $rowValue['twitter_image_custom_fields'] = $aioseo_field_value[0]->twitter_image_custom_fields;
                                $rowValue['twitter_title'] = $aioseo_field_value[0]->twitter_title;
                                $rowValue['twitter_description'] = $aioseo_field_value[0]->twitter_description;
                                $rowValue['robots_default'] = $aioseo_field_value[0]->robots_default;
                                // $rowValue['robots_noindex'] = $aioseo_field_value[0]->robots_noindex;
                                $rowValue['robots_noarchive'] = $aioseo_field_value[0]->robots_noarchive;
                                $rowValue['robots_nosnippet'] = $aioseo_field_value[0]->robots_nosnippet;
                                // $rowValue['robots_nofollow'] = $aioseo_field_value[0]->robots_nofollow;
                                $rowValue['robots_noimageindex'] = $aioseo_field_value[0]->robots_noimageindex;
                                $rowValue['noodp'] = $aioseo_field_value[0]->robots_noodp;
                                $rowValue['robots_notranslate'] = $aioseo_field_value[0]->robots_notranslate;
                                $rowValue['robots_max_snippet'] = $aioseo_field_value[0]->robots_max_snippet;
                                $rowValue['robots_max_videopreview'] = $aioseo_field_value[0]->robots_max_videopreview;
                                $rowValue['robots_max_imagepreview'] = $aioseo_field_value[0]->robots_max_imagepreview;
                                $rowValue['aioseo_title'] = $aioseo_field_value[0]->title;
                                $rowValue['aioseo_description'] = $aioseo_field_value[0]->description;
                                $key = $aioseo_field_value[0]->keyphrases;

                                $key1 = json_decode($key);
                                $rowValue['keyphrases'] = $key1
                                    ->focus->keyphrase;
                            }
                            else
                            {
                                $rowValue[$key] = isset($rowValue[$key]) ? $rowValue[$key] : '';
                                $rowValue[$key] = $this->returnMetaValueAsCustomerInput($rowValue[$key], $hKey);
                            }
                        }
                        global $wpdb;
                        //Added for user export
                        if ($key == 'user_login')
                        {
                            $wpsc_query = $wpdb->prepare("select ID from {$wpdb->prefix}users where user_login =%s", $rowValue['user_login']);
                            $wpsc_meta = $wpdb->get_results($wpsc_query, ARRAY_A);
                        }
                        if (isset($rowValue['_bbp_forum_type']) && ($rowValue['_bbp_forum_type'] == 'forum' || $rowValue['_bbp_forum_type'] == 'category'))
                        {
                            if ($key == 'Visibility')
                            {
                                $rowValue[$key] = $rowValue['post_status'];
                            }
                            if ($key == 'bbp_moderators')
                            {
                                $get_forum_moderator_ids = $wpdb->get_results("SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = $recordId AND meta_key = '_bbp_moderator_id' ", ARRAY_A);
                                $forum_moderators = '';
                                foreach ($get_forum_moderator_ids as $get_moderator_id)
                                {
                                    $forum_user_meta = get_user_by('id', $get_moderator_id['meta_value']);
                                    $forum_user = $forum_user_meta
                                        ->data->user_login;
                                    $forum_moderators .= $forum_user . ',';
                                }

                                $rowValue[$key] = rtrim($forum_moderators, ',');
                            }

                        }
                        if ($key == 'topic_status' || $key == 'author' || $key == 'topic_type')
                        {
                            $rowValue['topic_status'] = $rowValue['post_status'];
                            $rowValue['author'] = $rowValue['post_author'];
                            if ($key == 'topic_type')
                            {
                                $Topictype = get_post_meta($rowValue['_bbp_forum_id'], '_bbp_sticky_topics');
                                $topic_types = get_option('_bbp_super_sticky_topics');
                                $rowValue['topic_type'] = 'normal';
                                if ($Topictype)
                                {
                                    foreach ($Topictype as $t_type)
                                    {
                                        if ($t_type['0'] == $recordId)
                                        {
                                            $rowValue['topic_type'] = 'sticky';
                                        }
                                    }
                                }
                                elseif (!empty($topic_types))
                                {
                                    foreach ($topic_types as $top_type)
                                    {
                                        if ($top_type == $rowValue['ID'])
                                        {
                                            $rowValue['topic_type'] = 'super sticky';
                                        }
                                    }
                                }
                            }
                        }
                        if ($key == 'reply_status' || $key == 'reply_author')
                        {
                            $rowValue['reply_status'] = $rowValue['post_status'];
                            $rowValue['reply_author'] = $rowValue['post_author'];
                        }
                        if (array_key_exists($hKey, $rowValue))
                        {
                            $result[$recordId][$hKey] = $rowValue[$hKey];
                        }
                        else
                        {
                            $result[$recordId][$hKey] = '';
                        }
                    }
                }
            }
            return $result;
        }

        function get_common_post_metadata($meta_id)
        {
            global $wpdb;
            $mdata = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}usermeta WHERE umeta_id = %d", $meta_id) , ARRAY_A);
            return $mdata[0];
        }

        function get_common_unserialize($serialize_data)
        {
            return unserialize($serialize_data);
        }

        /**
         * Create CSV data from array
         * @param array $data       2D array with data
         * @param array $fields     field names
         * @param bool $append      if true, field names will not be output
         * @param bool $is_php      if a php die() call should be put on the first
         *                          line of the file, this is later ignored when read.
         * @param null $delimiter   field delimiter to use
         * @return string           CSV data (text string)
         */
        public function unParse($data = array() , $fields = array() , $append = false, $is_php = false, $delimiter = null)
        {
            if (!is_array($data) || empty($data)) $data = & $this->data;
            if (!is_array($fields) || empty($fields)) $fields = & $this->titles;
            if ($delimiter === null) $delimiter = $this->delimiter;

            $string = ($is_php) ? "<?php header('Status: 403'); die(' '); ?>" . $this->linefeed : '';
            $entry = array();

            // create heading
            if ($this->offset == 0 || $this->checkSplit == 'true')
            {
                if ($this->heading && !$append && !empty($fields))
                {
                    foreach ($fields as $key => $value)
                    {
                        $entry[] = $this->_enclose_value($value);
                    }
                    $string .= implode($delimiter, $entry) . $this->linefeed;
                    $entry = array();
                }
            }

            // create data
            foreach ($data as $key => $row)
            {
                foreach ($row as $field => $value)
                {
                    $entry[] = $this->_enclose_value($value);
                }
                $string .= implode($delimiter, $entry) . $this->linefeed;
                $entry = array();
            }
            return $string;
        }

        /**
         * Enclose values if needed
         *  - only used by unParse()
         * @param null $value
         * @return mixed|null|string
         */
        public function _enclose_value($value = null)
        {
            if ($value !== null && $value != '')
            {
                $delimiter = preg_quote($this->delimiter, '/');
                $enclosure = preg_quote($this->enclosure, '/');
                if ($value[0] == '=') $value = "'" . $value; # Fix for the Comma separated vulnerabilities.
                if ( isset($value) && is_string($value) && preg_match("/".$delimiter."|".$enclosure."|\n|\r/i", $value) ||isset($value[0]) && ($value[0] == ' ' ||isset($value) && substr($value, -1) == ' ') ) {
                    $value = str_replace($this->enclosure, $this->enclosure . $this->enclosure, $value);
                    $value = $this->enclosure . $value . $this->enclosure;
                }
                else{
                    if(is_string($value) || is_numeric($value)){
                        $value = $this->enclosure.$value.$this->enclosure;
                    }
                    else {
                        $value = '';
                    }
                }
            }
            return $value;
        }

        /**
         * Apply exclusion before export
         * @param $headers  - Apply exclusion headers
         * @return array    - Available headers after applying the exclusions
         */
        public function applyEventExclusion($headers, $optionalType)
        {
            $header_exclusion = array();
            $exclusion = $this->eventExclusions['exclusion_headers']['header'];
            $this->eventExclusions['exclusion_headers']['header'] = $exclusion;
            $required_header = $this->eventExclusions['exclusion_headers']['header'];

            if ($optionalType == 'elementor_library')
            {
                $required_head = array();

                if (isset($required_header['ID']))
                {
                    $required_head['ID'] = $required_header['ID'];
                }
                if (isset($required_header['Template title']))
                {
                    $required_head['Template title'] = $required_header['Template title'];
                }
                if (isset($required_header['Template content']))
                {
                    $required_head['Template content'] = $required_header['Template content'];
                }
                if (isset($required_header['Style']))
                {
                    $required_head['Style'] = $required_header['Style'];
                }
                if (isset($required_header['Template type']))
                {
                    $required_head['Template type'] = $required_header['Template type'];
                }
                if (isset($required_header['Created time']))
                {
                    $required_head['Created time'] = $required_header['Created time'];
                }
                if (isset($required_header['Template status']))
                {
                    $required_head['Template status'] = $required_header['Template status'];
                }
                if (isset($required_header['Category']))
                {
                    $required_head['Category'] = $required_header['Category'];
                }
                if (isset($required_header['Created by']))
                {
                    $required_head['Created by'] = $required_header['Created by'];
                }
                if (!empty($required_head))
                {
                    foreach ($headers as $hVal)
                    {
                        if (array_key_exists($hVal, $required_head))
                        {
                            $header_exclusion[] = $hVal;
                        }
                    }
                    return $header_exclusion;
                }
                else
                {
                    return $headers;
                }
            }
            else
            {
                if (!empty($required_header))
                {
                    foreach ($headers as $hVal)
                    {
                        if (array_key_exists($hVal, $required_header))
                        {
                            $header_exclusion[] = $hVal;
                        }
                    }
                    return $header_exclusion;
                }
                else
                {
                    return $headers;
                }
            }
        }

        public function replace_prefix_aioseop_from_fieldname($fieldname)
        {
            if (preg_match('/_aioseop_/', $fieldname))
            {
                return preg_replace('/_aioseop_/', '', $fieldname);
            }

            return $fieldname;
        }
        public function getaioseoFieldValue($post_id)
        {
            if (is_plugin_active('all-in-one-seo-pack/all_in_one_seo_pack.php') || is_plugin_active('all-in-one-seo-pack-pro/all_in_one_seo_pack.php'))
            {
                global $wpdb;
                $aioseo_slug = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}aioseo_posts WHERE post_id='$post_id' ");
                return $aioseo_slug;
            }

        }

        public function replace_prefix_pods_from_fieldname($fieldname)
        {
            if (preg_match('/_pods_/', $fieldname))
            {
                return preg_replace('/_pods_/', '', $fieldname);
            }

            return $fieldname;
        }

        public function replace_prefix_yoast_wpseo_from_fieldname($fieldname)
        {

            if (preg_match('/_yoast_wpseo_/', $fieldname))
            {
                $fieldname = preg_replace('/_yoast_wpseo_/', '', $fieldname);

                if ($fieldname == 'focuskw')
                {
                    $fieldname = 'focus_keyword';
                }
                else if ($fieldname == 'bread-crumbs-title')
                { // It is comming as bctitle nowadays
                    $fieldname = 'bctitle';
                }
                elseif ($fieldname == 'metadesc')
                {
                    $fieldname = 'meta_desc';
                }
            }

            return $fieldname;
        }

        public function replace_prefix_wpcf_from_fieldname($fieldname)
        {
            if (preg_match('/_wpcf/', $fieldname))
            {
                return preg_replace('/_wpcf/', '', $fieldname);
            }

            return $fieldname;
        }

        public function replace_prefix_wpsc_from_fieldname($fieldname)
        {
            if (preg_match('/_wpsc_/', $fieldname))
            {
                return preg_replace('/_wpsc_/', '', $fieldname);
            }

            return $fieldname;
        }

        public function replace_wpcr3_from_fieldname($fieldname)
        {
            if (preg_match('/wpcr3_/', $fieldname))
            {
                $fieldname = preg_replace('/wpcr3_/', '', $fieldname);
            }

            return $fieldname;
        }

        public function change_fieldname_depends_on_post_type($post_type, $fieldname)
        {
            if ($post_type == 'wpcr3_review')
            {
                switch ($fieldname)
                {
                    case 'ID':
                        return 'review_id';
                    case 'post_status':
                        return 'status';
                    case 'post_content':
                        return 'review_text';
                    case 'post_date':
                        return 'date_time';
                    default:
                        return $fieldname;
                }
            }
            if ($post_type == 'shop_order_refund')
            {
                switch ($fieldname)
                {
                    case 'ID':
                        return 'REFUNDID';
                    default:
                        return $fieldname;
                }
            }
            else if ($post_type == 'shop_order')
            {
                switch ($fieldname)
                {
                    case 'ID':
                        return 'ORDERID';
                    case 'post_status':
                        return 'order_status';
                    case 'post_excerpt':
                        return 'customer_note';
                    case 'post_date':
                        return 'order_date';
                    default:
                        return $fieldname;
                }
            }
            else if ($post_type == 'shop_coupon')
            {
                switch ($fieldname)
                {
                    case 'ID':
                        return 'COUPONID';
                    case 'post_status':
                        return 'coupon_status';
                    case 'post_excerpt':
                        return 'description';
                    case 'post_date':
                        return 'coupon_date';
                    case 'post_title':
                        return 'coupon_code';
                    default:
                        return $fieldname;
                }
            }
            else if ($post_type == 'product_variation')
            {
                switch ($fieldname)
                {
                    case 'ID':
                        return 'VARIATIONID';
                    case 'post_parent':
                        return 'PRODUCTID';
                    case 'sku':
                        return 'VARIATIONSKU';
                    default:
                        return $fieldname;
                }
            }

            return $fieldname;
        }

        public function replace_underscore_from_fieldname($fieldname)
        {
            if (preg_match('/_/', $fieldname))
            {
                $fieldname = preg_replace('/^_/', '', $fieldname);
            }

            return $fieldname;
        }

        public function fetchCategoryFieldValue($categories)
        {

            global $wpdb;
            $bulk_category = [];

            foreach ($categories as $category_id => $category)
            {
                $term_meta = get_term_meta($category_id);
                $single_category = [];
                foreach ($this->headers as $header)
                {

                    if ($header == 'name')
                    {
                        $cato[] = get_term($category_id);
                        $single_category[$header] = $this->hierarchy_based_term_cat_name($cato, 'category');
                        continue;
                    }

                    if (array_key_exists($header, $category))
                    {
                        $single_category[$header] = $category[$header];
                    }
                    else
                    {
                        if (isset($term_meta[$header]))
                        {
                            $single_category[$header] = $this->returnMetaValueAsCustomerInput($term_meta[$header]);
                        }
                        else
                        {
                            $single_category[$header] = null;
                        }
                    }
                }
                array_push($bulk_category, $single_category);
            }
            return $bulk_category;
        }

        public function returnMetaValueAsCustomerInput($meta_value, $header = false)
        {

            if (is_array($meta_value))
            {
                $meta_value = $meta_value[0];
                if (!empty($meta_value))
                {
                    if (is_serialized($meta_value))
                    {
                        return unserialize($meta_value);
                    }
                    else if (is_array($meta_value))
                    {
                        return implode('|', $meta_value);
                    }
                    else if (is_string($meta_value))
                    {
                        return $meta_value;
                    }
                    else if ($this->isJSON($meta_value) === true)
                    {
                        return json_decode($meta_value);
                    }

                    return $meta_value;
                }

                return $meta_value;
            }
            else
            {
                if (is_serialized($meta_value))
                {
                    $meta_value = unserialize($meta_value);
                    if (is_array($meta_value))
                    {
                        return implode('|', $meta_value);
                    }
                    return $meta_value;
                }
                else if (is_array($meta_value))
                {
                    return implode('|', $meta_value);
                }
                else if (is_string($meta_value))
                {
                    return $meta_value;
                }
                else if ($this->isJSON($meta_value) === true)
                {
                    return json_decode($meta_value);
                }
            }

            return $meta_value;
        }

        public function isJSON($meta_value)
        {
            $json = json_decode($meta_value);
            return $json && $meta_value != $json;
        }

        public function hierarchy_based_term_name($term, $taxanomy_type)
        {

            $tempo = array();
            $termo = '';
            $i = 0;
            foreach ($term as $termkey => $terms)
            {
                $tempo[] = $terms->name;
                $temp_hierarchy_terms = [];

                if (!empty($terms->parent))
                {
                    $temp1 = $terms->name;
                    $i++;

                    $termexp = explode(',', $termo);

                    $termo = implode(',', $termexp);
                    $temp_hierarchy_terms[] = $terms->name;
                    $hierarchy_terms = $this->call_back_to_get_parent($terms->parent, $taxanomy_type, $tempo, $temp_hierarchy_terms);
                    $parent_name = get_term($terms->parent);
                    $termo .= $this->split_terms_by_arrow($hierarchy_terms, $parent_name->name) . ',';

                }
                else
                {

                    if (in_array($terms->name, $tempo))
                    {

                        $termo .= $terms->name . ',';

                    }
                }
            }
            return $termo;

        }

        public function hierarchy_based_term_cat_name($term, $taxanomy_type)
        {
            $tempo = array();
            $termo = '';
            foreach ($term as $terms)
            {
                $tempo[] = $terms->name;
                $temp_hierarchy_terms = [];
                if (!empty($terms->parent))
                {
                    $temp_hierarchy_terms[] = $terms->name;
                    $hierarchy_terms = $this->call_back_to_get_parent($terms->parent, $taxanomy_type, $tempo, $temp_hierarchy_terms);
                    $parent_name = get_term($terms->parent);
                    $termo = $this->split_terms_by_arrow($hierarchy_terms, $parent_name->name);

                }
                else
                {
                    $termo = $terms->name;

                }
            }
            return $termo;
        }
        public function call_back_to_get_parent($term_id, $taxanomy_type, $tempo, $temp_hierarchy_terms = [])
        {
            $term = get_term($term_id, $taxanomy_type);
            if (!empty($term->parent))
            {
                if (in_array($term->name, $tempo))
                {

                    $temp_hierarchy_terms[] = $term->name;

                    $temp_hierarchy_terms = $this->call_back_to_get_parent($term->parent, $taxanomy_type, $tempo, $temp_hierarchy_terms);
                }
                else
                {
                    $temp_hierarchy_terms[] = '';

                    $temp_hierarchy_terms = $this->call_back_to_get_parent($term->parent, $taxanomy_type, $tempo, $temp_hierarchy_terms);
                }

            }
            else
            {
                if (in_array($term->name, $tempo))
                {
                    $temp_hierarchy_terms[] = $term->name;
                }
                else
                {
                    $temp_hierarchy_terms[] = '';
                }
            }
            return $temp_hierarchy_terms;
        }
        // public function call_back_to_get_parent($term_id, $taxanomy_type, $temp_hierarchy_terms = []){
        // 	$term = get_term($term_id, $taxanomy_type);
        // 	if(!empty($term->parent)){
        // 		$temp_hierarchy_terms[] = $term->name;
        // 		$temp_hierarchy_terms = $this->call_back_to_get_parent($term->parent, $taxanomy_type, $temp_hierarchy_terms);
        // 	}else{
        // 		$temp_hierarchy_terms[] = $term->name;
        // 	}
        // 	return $temp_hierarchy_terms;
        // }
        public function split_terms_by_arrow($hierarchy_terms, $termParentName)
        {

            krsort($hierarchy_terms);
            $terms_value = $termParentName . '>' . $hierarchy_terms[0];
            //return implode('>', $hierarchy_terms);
            return $terms_value;
        }

        public function getWoocommerceMetaValue($fieldname, $post_type, $post)
        {
            $post_type = isset($post_type) ? $post_type : '';
            if ($post_type == 'shop_order_refund')
            {
                switch ($fieldname)
                {
                    case 'REFUNDID':
                        return $post['ID'];
                    default:
                        return $post[$fieldname];
                }
            }
            else if ($post_type == 'shop_order')
            {
                switch ($fieldname)
                {
                    case 'ORDERID':
                        return $post['ID'];
                    case 'order_status':
                        return $post['post_status'];
                    case 'customer_note':
                        return $post['post_excerpt'];
                    case 'order_date':
                        return $post['post_date'];
                    default:
                        return $post[$fieldname];
                }
            }
            else if ($post_type == 'shop_coupon')
            {
                switch ($fieldname)
                {
                    case 'COUPONID':
                        return $post['ID'];
                    case 'coupon_status':
                        return $post['post_status'];
                    case 'description':
                        return $post['post_excerpt'];
                    case 'coupon_date':
                        return $post['post_date'];
                    case 'coupon_code':
                        return $post['post_title'];
                    case 'expiry_date':
                        if (isset($post['date_expires']))
                        {
                            $timeinfo = date('m/d/Y', $post['date_expires']);
                        }
                        $timeinfo = isset($timeinfo) ? $timeinfo : '';
                        return $timeinfo;
                    default:
                        return $post[$fieldname];
                }
            }
            else if ($post_type == 'product_variation')
            {
                switch ($fieldname)
                {
                    case 'VARIATIONID':
                        return $post['ID'];
                    case 'PRODUCTID':
                        return $post['post_parent'];
                    case 'VARIATIONSKU':
                        return $post['sku'];
                    default:
                        return $post[$fieldname];
                }
            }
            return false;
        }

    }

    return new exportExtension();
}

