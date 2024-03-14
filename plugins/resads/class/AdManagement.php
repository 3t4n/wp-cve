<?php
if(!defined('ABSPATH')) die('You are not allowed to call this page directly.');
/**
 * AdManagement
 */
class ResAds_AdManagement 
{
    /**
     * Ad
     * @var array
     */
    private $ad;
    /**
     * Set Ad
     * @param array $ad
     */
    public function set_ad($ad)
    {
        $this->ad = $ad;
    }
    /**
     * Get Ad Code
     * @return string
     */
    public function get_code($ad = false)
    {
        if(is_array($ad))
            $this->ad = $ad;
        
        if(isset($this->ad['ad_target_url']) && trim($this->ad['ad_target_url']) != '' && isset($this->ad['ad_banner_url']) && trim($this->ad['ad_banner_url']) != '')
        {
            $is_follow = '';
            if(isset($this->ad['ad_is_follow']) && $this->ad['ad_is_follow'] == 1)
                $is_follow = 'rel="nofollow"';

            $is_new_window = '';
            if(isset($this->ad['ad_is_new_window']) && $this->ad['ad_is_new_window'] == 1)
                $is_new_window = 'target="_blank"';
            
            $target_url = $this->get_target_url();

            if($this->ad['ad_banner_width'] > 0 && $this->ad['ad_banner_height'] > 0 )          
                return sprintf('<a href="%s" %s %s><img src="%s" width="%d" height="%d" /></a>', $target_url, $is_follow, $is_new_window, $this->ad['ad_banner_url'], $this->ad['ad_banner_width'], $this->ad['ad_banner_height']);
            else
                return sprintf('<a href="%s" %s %s><img src="%s"/></a>', $target_url, $is_follow, $is_new_window, $this->ad['ad_banner_url']);
        }
        elseif(isset($this->ad['ad_source_code']) && trim($this->ad['ad_source_code']) != '')
        {
            return $this->get_source_code();
        }
    }
    /**
     * Get Target URL
     * @param array $ad
     * @return string
     */
    public function get_target_url($ad = false)
    {
        if(is_array($ad))
            $this->ad = $ad;
        
        if(isset($this->ad['ad_target_url']) && trim($this->ad['ad_target_url']) != '')
        {
            $target_url = $this->ad['ad_target_url'];
            if(isset($this->ad['ad_is_masked']) && $this->ad['ad_is_masked'] == 1 && isset($this->ad['ad_id']) && is_numeric($this->ad['ad_id']) && get_option('permalink_structure') != '')
                $target_url = home_url('resads/' . $this->ad['ad_id'] . '/');
            elseif(isset($this->ad['ad_is_masked']) && $this->ad['ad_is_masked'] == 1 && isset($this->ad['ad_id']) && is_numeric($this->ad['ad_id']))
                $target_url = home_url('?resads=' . $this->ad['ad_id']);
            
            return $target_url;
        }
    }
    /**
     * Get source code
     * @param array $ad
     * @return string
     */
    public function get_source_code($ad = false)
    {
        if(is_array($ad))
            $this->ad = $ad;
        
        if(isset($this->ad['ad_source_code']) && trim($this->ad['ad_source_code']) != '')
        {
            return stripslashes($this->ad['ad_source_code']);
        }
    }
    /**
     * Forward to url or selected ad_id data
     * @param int $ad_id
     * @param string $url
     */
    public function forward($ad_id = false, $url = false)
    {
        if(filter_var($url, FILTER_VALIDATE_URL))
        {
            wp_redirect($url, 301);
            exit;
        }
        else if(is_numeric($ad_id))
        {
            $AdManagement_DB = new ResAds_AdManagement_DB();
            $ad = $AdManagement_DB->get($ad_id);;
            if($ad && isset($ad['ad_target_url']) && filter_var($ad['ad_target_url'], FILTER_VALIDATE_URL) && isset($ad['ad_is_masked']) && $ad['ad_is_masked'] == 1)
            {
                wp_redirect($ad['ad_target_url'], 301);
                exit;
            }         
        }
    }
}
/**
 * AdManagement Admin
 */
class ResAds_AdManagement_Admin 
{
    /**
     * Hook
     * @var string
     */
    private $hook;
    /**
     * Data
     * @var array
     */
    private $data;
    /**
     * Action
     * @var string
     */
    private $action = null;
    /**
     * Submit Response
     * @var mixed
     */
    private $submit_response = null;
    /**
     * Construct
     */
    public function __construct() 
    {
        if(!current_user_can(RESADS_PERMISSION_ROLE))
            wp_die(__('You do not have permissions.', RESADS_ADMIN_TEXTDOMAIN));

        if(isset($_REQUEST['page']) && trim($_REQUEST['page']) == 'resads-admanagement')
            $this->process_action();

        $this->hook = add_submenu_page('resads', __('AdManagement', RESADS_ADMIN_TEXTDOMAIN), __('AdManagement', RESADS_ADMIN_TEXTDOMAIN), RESADS_PERMISSION_ROLE, 'resads-admanagement', array($this, 'page'));
        add_action("load-$this->hook", array(new ResAds_AdManagement_List_Table(), 'add_options'));
        add_action("load-$this->hook", array($this, 'add_page_actions'), 9);
        add_action("load-$this->hook", array($this, 'add_scripts'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
    }
    /**
     * Process Action
     */
    public function process_action()
    {  
        if(isset($_GET['action']) && trim($_GET['action']) != '')
        {
            $this->action = trim($_GET['action']);
            if(trim($_GET['action']) == 'edit' && isset($_GET['ad']) && is_numeric($_GET['ad']))
            {
                if(isset($_POST['submit_ad'])) 
                {
                    $this->submit_response = $this->save($_POST);
                    if($this->submit_response === TRUE)
                    {
                        wp_redirect(admin_url('admin.php?page=resads-admanagement'));
                        exit;
                    }
                }
                
                $AdManagement_DB = new ResAds_AdManagement_DB();
                $value = $AdManagement_DB->get($_GET['ad']);
                if(isset($value['adspot_ids']) && stripos($value['adspot_ids'], '|'))
                    $value['adspot_ids'] = explode('|', $value['adspot_ids']);
                else
                    $value['adspot_ids'] = array($value['adspot_ids']);
                
                $this->data = $value;
            }
            elseif(trim($this->action) == 'delete' && isset($_GET['ad']) && is_numeric($_GET['ad']))
            {
                $AdManagement_DB = new ResAds_AdManagement_DB();
                $AdManagement_DB->delete_ads(array($_GET['ad']));
                
                wp_redirect(admin_url('admin.php?page=resads-admanagement'));
                exit;
            }
            elseif(trim($this->action) == 'new')
            {                
                if(isset($_POST['submit_ad']))
                {
                    $this->submit_response = $this->save($_POST);
                    if($this->submit_response === TRUE)
                    {
                        wp_redirect(admin_url('admin.php?page=resads-admanagement'));
                        exit;
                    }
                }              
            }
        }
    }  
    /**
     * Render Page
     */
    public function page()
    {  
        if(isset($this->action) && trim($this->action) != '')
        {
            if($this->action == 'edit' && isset($_GET['ad']) && is_numeric($_GET['ad']))
            {                           
                if(file_exists(RESADS_TEMPLATE_DIR . '/admanagement/edit.php'))
                    require_once RESADS_TEMPLATE_DIR . '/admanagement/edit.php';
            }
            elseif($this->action == 'new')
            {                              
                if(file_exists(RESADS_TEMPLATE_DIR . '/admanagement/new.php'))
                    require_once RESADS_TEMPLATE_DIR . '/admanagement/new.php';
            }
            else
            {
                if(file_exists(RESADS_TEMPLATE_DIR . '/admanagement/admanagement.php'))
                require_once RESADS_TEMPLATE_DIR . '/admanagement/admanagement.php';
            }
        }
        else
        {
            if(file_exists(RESADS_TEMPLATE_DIR . '/admanagement/admanagement.php'))
                require_once RESADS_TEMPLATE_DIR . '/admanagement/admanagement.php';
        }
    }    
    /**
     * Add Scripts
     */
    public function add_scripts()
    {
        if(isset($this->action))
        {
            if($this->action == 'new' || $this->action == 'edit')
            {
                wp_enqueue_media();
                
                wp_enqueue_script('dashboard'); 
                wp_enqueue_script('postbox'); 
                wp_enqueue_script('jquery');
                wp_enqueue_script('jquery-ui-core');
                wp_enqueue_script('jquery-ui-tabs');               
                wp_enqueue_script('resads-admin', RESADS_JS_DIR . '/admin.min.js', array( 'jquery-ui-tabs', 'jquery'));
                wp_localize_script('resads-admin', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
                
                wp_enqueue_script('chosen', RESADS_JS_DIR . '/chosen.jquery.min.js', array('jquery'));
                wp_enqueue_style('chosen', RESADS_CSS_DIR . '/chosen.min.css');
                
                wp_enqueue_style('resads-main', RESADS_CSS_DIR . '/main.min.css');                
            }
        }
    }
    /**
     * Add Page Actions
     */
    public function add_page_actions()
    {
        if(isset($this->action))
        {
            if($this->action == 'new' || $this->action == 'edit')
            {
                do_action('add_meta_boxes_'.$this->hook, null);
                do_action('add_meta_boxes', $this->hook, null);
                add_screen_option('layout_columns', array('max' => 2, 'default' => 2));
            }
        }
    }
    /** 
     * Add Meta Boxes
     */
    public function add_meta_boxes()
    {
        if(isset($this->action))
        {
            if($this->action == 'new' || $this->action == 'edit')
            {
                $Meta_Box = new ResAds_AdManagement_Meta_Box();

                /** Normal */
                add_meta_box('resads-ad-detail', __('Ad Details', RESADS_ADMIN_TEXTDOMAIN), array($Meta_Box, 'ad_details'), $this->hook, 'normal', 'high', array('data' => $this->data));
                add_meta_box('resads-link-graphic', __('Link and Graphic', RESADS_ADMIN_TEXTDOMAIN), array($Meta_Box, 'ad_link_and_graphic'), $this->hook, 'normal', 'high', array('data' => $this->data));
                add_meta_box('resads-sourcecode', __('Sourcecode', RESADS_ADMIN_TEXTDOMAIN), array($Meta_Box, 'ad_sourcecode'), $this->hook, 'normal', 'high', array('data' => $this->data));
                add_meta_box('resads-banner-option', __('Options', RESADS_ADMIN_TEXTDOMAIN), array($Meta_Box, 'ad_banner_option'), $this->hook, 'normal', 'high', array('data' => $this->data));

                /** Side */
                add_meta_box('resads-banner-submit', __('Submit', RESADS_ADMIN_TEXTDOMAIN), array($Meta_Box, 'ad_submit'), $this->hook, 'side', 'high', array('data' => $this->data));
                add_meta_box('resads-banner-adspots', __('AdSpots', RESADS_ADMIN_TEXTDOMAIN), array($Meta_Box, 'ad_banner_adspots'), $this->hook, 'side', 'high', array('data' => $this->data));
                add_meta_box('resads-tutorial-description', __('Tutorial', RESADS_ADMIN_TEXTDOMAIN), array($Meta_Box, 'ad_tutorial_description'), $this->hook, 'side', 'high', array('data' => $this->data));
            }
        }
    }
    /**
     * Create or Update an Ad
     * @param array $data
     * @return boolean
     */
    public function save($data)
    {
        $message = array();
        
         if(isset($data['ad_graphic_banner_width']) && is_numeric($data['ad_graphic_banner_width']) && $data['ad_graphic_banner_width'] > 0)
            $data['ad_banner_width'] = $data['ad_graphic_banner_width'];
         elseif(isset($data['ad_source_banner_width']) && is_numeric($data['ad_source_banner_width']) && $data['ad_source_banner_width'] > 0)
              $data['ad_banner_width'] = $data['ad_source_banner_width'];
        
        if(isset($data['ad_graphic_banner_height']) && is_numeric($data['ad_graphic_banner_height']) && $data['ad_graphic_banner_height'] > 0)
            $data['ad_banner_height'] = $data['ad_graphic_banner_height'];
        elseif(isset($data['ad_source_banner_height']) && is_numeric($data['ad_source_banner_height']) && $data['ad_source_banner_height'] > 0)
            $data['ad_banner_height'] = $data['ad_source_banner_height'];
              
        if(!isset($data['ad_name']) || trim($data['ad_name']) == '')
            $message['error'][] = __('Ad Name is missing', RESADS_ADMIN_TEXTDOMAIN);
        
        if((!isset($data['ad_banner_width']) || trim($data['ad_banner_width']) == '' || !is_numeric($data['ad_banner_width'])) && ((!isset($data['ad_is_responsive']) || $data['ad_is_responsive'] == 0) || (!isset($data['ad_source_code']) || trim($data['ad_source_code']) == '')))
            $message['error'][] = __('Banner Width is missing', RESADS_ADMIN_TEXTDOMAIN);
               
        if((!isset($data['ad_banner_height']) || trim($data['ad_banner_height']) == '' || !is_numeric($data['ad_banner_height'])) && ((!isset($data['ad_is_responsive']) || $data['ad_is_responsive'] == 0) || (!isset($data['ad_source_code']) || trim($data['ad_source_code']) == '')))
            $message['error'][] = __('Banner Height is missing', RESADS_ADMIN_TEXTDOMAIN);
        
        if((!isset($data['ad_target_url']) || trim($data['ad_target_url']) == '' || !isset($data['ad_banner_url']) || !filter_var($data['ad_target_url'], FILTER_VALIDATE_URL) || trim($data['ad_banner_url']) == '') && (!isset($data['ad_source_code']) || trim($data['ad_source_code']) == ''))
            $message['error'][] = __('Banner-URL and Banner Target-URL or Banner Source Code is missing', RESADS_ADMIN_TEXTDOMAIN);       
             
        if(isset($data['ad_banner_width']) && is_numeric($data['ad_banner_width']) && isset($data['ad_banner_height']) && is_numeric($data['ad_banner_height']) && ((!isset($data['ad_is_responsive']) || $data['ad_is_responsive'] == 0) || (!isset($data['ad_source_code']) || trim($data['ad_source_code']) == ''))) 
        {
            require_once RESADS_CLASS_DIR . '/Resolution.php';
            $Resolution_DB = new ResAds_Resolution_DB();
            $resolution = $Resolution_DB->get_by_banner_size($data['ad_banner_width'], $data['ad_banner_height']);
            if(!$resolution)
                $message['error'][] = __('There is no resolution for your banner size', RESADS_ADMIN_TEXTDOMAIN);
            elseif(isset($resolution['res_id']))
                $data['res_id'] = $resolution['res_id'];           
        }
        
        if(isset($message['error']))
            return $message;
        
        if(!isset($data['ad_is_responsive']))
            $data['ad_is_responsive'] = 0;
        
        if(!isset($data['ad_is_masked']))
            $data['ad_is_masked'] = 0;
        
        if(!isset($data['ad_is_follow']))
            $data['ad_is_follow'] = 0;
        
        if(!isset($data['ad_is_new_window']))
            $data['ad_is_new_window'] = 0;
        
        if(!isset($data['adspot']))
            $data['adspot'] = null;
        
        if(!isset($data['ad_is_active']) || $data['ad_is_active'] == 0)
            $data['ad_is_active'] = 0;
        
        if(!isset($data['res_id']))
            $data['res_id'] = 0;      
        
        if(!isset($data['ad_banner_width']))
            $data['ad_banner_width'] = 0;
        
        if(!isset($data['ad_banner_height']))
            $data['ad_banner_height'] = 0;
        
        if(isset($data['ad_id']) && is_numeric($data['ad_id']) && $data['ad_id'] > 0)
        {
            $AdManagement_DB = new ResAds_AdManagement_DB();
            if($AdManagement_DB->edit($data))
                return true;
        }
        else
        {
            $AdManagement_DB = new ResAds_AdManagement_DB();
            if($AdManagement_DB->add($data))
                return true;
        }            
        return $message;
    }
}
/**
 * AdManagement DB
 */
class ResAds_AdManagement_DB 
{
    /**
     * DB Instance
     * @var wpdb
     */
    private $wpdb;
    /**
     * Construct
     * @global wpdb $wpdb
     */
    public function __construct() 
    {        
        global $wpdb;
        $this->wpdb = $wpdb;
    }
    /**
     * Create Database Table
     */
    public function create_database_table()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        $sql = "CREATE TABLE " .  $this->wpdb->prefix . "resads_ad (
            ad_id int(10) unsigned NOT NULL AUTO_INCREMENT,
            resads_resolution_res_id int(10) unsigned NOT NULL,
            ad_name varchar(255) NOT NULL,
            ad_description text,
            ad_target_url varchar(255) NOT NULL,
            ad_banner_url varchar(255) NOT NULL,
            ad_source_code text,
            ad_banner_width int(4) unsigned NOT NULL,
            ad_banner_height int(4) unsigned NOT NULL,
            ad_is_responsive int(1) unsigned NOT NULL,
            ad_is_masked int(1) unsigned NOT NULL,
            ad_is_follow int(1) unsigned NOT NULL,
            ad_is_new_window int(1) unsigned NOT NULL,
            ad_is_active int(1) unsigned NOT NULL,
            ad_creation_date date NOT NULL,
            PRIMARY KEY (ad_id)
          ) DEFAULT CHARSET=utf8;";
        dbDelta($sql);
    }
    /**
     * Delete Database Tables
     */
    public function delete_database_table()
    {
        $this->wpdb->query("DROP TABLE IF EXISTS " . $this->wpdb->prefix . "resads_ad");
        $this->wpdb->query("DROP TABLE IF EXISTS " . $this->wpdb->prefix . "resads_ad_statistik");
    }
    /**
     * Edit an Ad
     * @param array $data
     * @return boolean
     */
    public function edit($data)
    {
        if(is_array($data))
        {
            $ad_update = $this->wpdb->update($this->wpdb->prefix . 'resads_ad', array('resads_resolution_res_id' => $data['res_id'], 'ad_name' => $data['ad_name'], 'ad_description' => $data['ad_description'], 'ad_target_url' => $data['ad_target_url'], 'ad_banner_url' => $data['ad_banner_url'], 'ad_source_code' => $data['ad_source_code'], 'ad_banner_width' => $data['ad_banner_width'], 'ad_banner_height' => $data['ad_banner_height'], 'ad_is_responsive' => $data['ad_is_responsive'], 'ad_is_masked' => $data['ad_is_masked'], 'ad_is_follow' => $data['ad_is_follow'], 'ad_is_new_window' => $data['ad_is_new_window'], 'ad_is_active' => $data['ad_is_active']), array('ad_id' => $data['ad_id']), array('%d', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%d', '%d', '%d', '%d', '%d'), array('%d'));
            if($ad_update !== FALSE)
            {
                if(isset($data['adspot']) && is_array($data['adspot']))
                {
                    $delete_ad_spots = $this->wpdb->delete($this->wpdb->prefix . 'resads_ad_adspot', array('resads_ad_ad_id' => $data['ad_id']), array('%s'));
                    if($delete_ad_spots !== FALSE)
                    {
                        $data['adspot'] = array_unique($data['adspot']);
                        foreach($data['adspot'] as $adspot_id)
                        {
                            $adspot_insert = $this->wpdb->insert($this->wpdb->prefix . 'resads_ad_adspot', array('resads_ad_ad_id' => $data['ad_id'], 'resads_adspot_adspot_id' => $adspot_id), array('%s', '%s'));
                            if(!$adspot_insert)
                            {
                                $this->wpdb->delete($this->wpdb->prefix . 'resads_ad_adspot', array('resads_ad_ad_id' => $data['ad_id']), array('%s'));
                                return false;
                            }
                        }
                        return $ad_update;
                    }
                }
                else
                {
                    $delete_ad_spots = $this->wpdb->delete($this->wpdb->prefix . 'resads_ad_adspot', array('resads_ad_ad_id' => $data['ad_id']), array('%s'));
                    if($delete_ad_spots !== FALSE)
                        return true;
                }
            }
            elseif($ad_update !== FALSE)
            {
                return $ad_update;
            }
        }
    }   
    /**
     * Add an Ad
     * @param array $data
     * @return boolean
     */
    public function add($data)
    {      
        if(is_array($data))
        {
            $ad_insert = $this->wpdb->insert($this->wpdb->prefix . 'resads_ad', array('resads_resolution_res_id' => $data['res_id'], 'ad_name' => $data['ad_name'], 'ad_description' => $data['ad_description'], 'ad_target_url' => $data['ad_target_url'], 'ad_banner_url' => $data['ad_banner_url'], 'ad_source_code' => $data['ad_source_code'], 'ad_banner_width' => $data['ad_banner_width'], 'ad_banner_height' => $data['ad_banner_height'], 'ad_is_responsive' => $data['ad_is_responsive'], 'ad_is_masked' => $data['ad_is_masked'], 'ad_is_follow' => $data['ad_is_follow'], 'ad_is_new_window' => $data['ad_is_new_window'], 'ad_is_active' => $data['ad_is_active'], 'ad_creation_date' => current_time('mysql')), array('%d', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%s'));           
            if($ad_insert && isset($data['adspot']) && is_array($data['adspot']))
            {
                $ad_id = $this->wpdb->insert_id;
                $data['adspot'] = array_unique($data['adspot']);
                foreach($data['adspot'] as $adspot_id)
                {
                    $adspot_insert = $this->wpdb->insert($this->wpdb->prefix . 'resads_ad_adspot', array('resads_ad_ad_id' => $ad_id, 'resads_adspot_adspot_id' => $adspot_id), array('%d', '%d'));
                    if(!$adspot_insert)
                    {
                        $this->wpdb->delete($this->wpdb->prefix . 'resads_ad_adspot', array('resads_ad_ad_id' => $ad_id), array('%d'));
                        return false;
                    }
                }
                return $ad_insert;
            }
            elseif($ad_insert)
            {
                return $ad_insert;
            }
        }
    }
    /**
     * Return an Ad
     * @param int $ad_id
     * @return array
     */
    public function get($ad_id)
    {
        if(is_numeric($ad_id))
        {
            return $this->wpdb->get_row(
                    $this->wpdb->prepare('SELECT AD.*, GROUP_CONCAT(AASP.resads_adspot_adspot_id SEPARATOR "|") as adspot_ids FROM ' . $this->wpdb->prefix . 'resads_ad as AD LEFT JOIN '. $this->wpdb->prefix . 'resads_ad_adspot as AASP ON AASP.resads_ad_ad_id = AD.ad_id WHERE AD.ad_id LIKE %d GROUP BY AD.ad_id',
                        $ad_id),
                    ARRAY_A
                    );
        }
    }   
    /**
     * Delete one or more ads
     * @param array $ad_ids
     */
    public function delete_ads($ad_ids)
    {
        if(is_array($ad_ids))
        {
            $this->wpdb->query('DELETE FROM ' . $this->wpdb->prefix . 'resads_ad WHERE ad_id IN (' . implode(',', $ad_ids) . ')');           
            $this->wpdb->query('DELETE FROM ' . $this->wpdb->prefix . 'resads_ad_adspot WHERE resads_ad_ad_id IN (' . implode(',', $ad_ids) . ')');
        }
    }
    /**
     * Search an ad by name
     * @param string $name
     * @return array
     */    
    public function search_by_name($name)
    {
        if(trim($name) != '')
        {
            return $this->wpdb->get_results($this->wpdb->prepare('SELECT * FROM ' . $this->wpdb->prefix . 'resads_ad WHERE ad_name LIKE %s', "%$name%"), ARRAY_A);
        }
    }
    /**
     * Returns all Ads
     * @param string $limit
     * @return array
     */
    public function get_all($order = '', $limit = '')
    {
        if(trim($limit) != '')
            $limit = ' LIMIT ' . $limit;
        
        if(trim($order) != '')
            $order = ' ORDER BY ' . $order;
            
        return $this->wpdb->get_results('SELECT * FROM ' . $this->wpdb->prefix . 'resads_ad ' . $order . $limit, ARRAY_A);
    }
    /**
     * Deactive one or more Ads
     * @param array $ad_ids
     */
    public function deactive_ads($ad_ids)
    {
        if(is_array($ad_ids))
        {
            $this->wpdb->query('UPDATE ' . $this->wpdb->prefix . 'resads_ad SET ad_is_active = 0 WHERE ad_id IN (' . implode(',', $ad_ids) . ')');
        }
    }
    /**
     * Active one or more ads
     * @param array $ad_ids
     */
    public function active_ads($ad_ids)
    {
        if(is_array($ad_ids))
        {
            $this->wpdb->query('UPDATE ' . $this->wpdb->prefix . 'resads_ad SET ad_is_active = 1 WHERE ad_id IN (' . implode(',', $ad_ids) . ')');
        }
    }
}
if(!class_exists('WP_List_Table'))
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
/**
 * List Table
 */
class ResAds_AdManagement_List_Table extends WP_List_Table
{
    /**
     * Set Columns
     * @return array
     */
    public function get_columns() 
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'ad_name' => __('Name', RESADS_ADMIN_TEXTDOMAIN),
            'ad_is_active' => __('Active', RESADS_ADMIN_TEXTDOMAIN),
            'ad_statistik' => __('Statistik', RESADS_ADMIN_TEXTDOMAIN),
            'ad_spots' => __('AdSpot', RESADS_ADMIN_TEXTDOMAIN)
        );
        return $columns;
    }
    /**
     * Add Options
     */
    public function add_options()
    {
        if(!isset($_REQUEST['action']))
        {
            $option = 'per_page';
            $args = array(
                'label' => __('Ads', RESADS_ADMIN_TEXTDOMAIN),
                'default' => 10,
                'option' => 'ads_per_page'
            );
            add_screen_option($option, $args);
        }
    }
    /**
     * Get and set Data
     * @global wpdb $wpdb
     */
    public function prepare_items() 
    {
        global $wpdb; 

        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
        
        $this->process_bulk_action();
        
        $per_page = $this->get_items_per_page('ads_per_page', 10);
        $current_page = $this->get_pagenum();
        $limit = $per_page * $current_page - $per_page . ',' . $per_page;
        
        if(isset($_REQUEST['s']) && trim($_REQUEST['s']) != '')
        {
            $search = trim($_REQUEST['s']);
            $total_items = $wpdb->get_var($wpdb->prepare('SELECT count(ad_id) FROM ' . $wpdb->prefix . 'resads_ad WHERE ad_name LIKE %s', "%$search%"));
            $this->items = $wpdb->get_results(
                    $wpdb->prepare('SELECT AD.ad_id, AD.ad_name, AD.ad_is_active, GROUP_CONCAT(ASP.adspot_name SEPARATOR ", ") as ad_spots, '
                        . '(SELECT sum(adstat_views) FROM ' . $wpdb->prefix .'resads_ad_statistik as ADS WHERE ADS.resads_ad_ad_id = AD.ad_id) as sum_views, '
                        . '(SELECT sum(adstat_clicks) FROM ' . $wpdb->prefix .'resads_ad_statistik as ADS WHERE ADS.resads_ad_ad_id = AD.ad_id) as sum_clicks '
                        . 'FROM ' . $wpdb->prefix . 'resads_ad as AD '
                        . 'LEFT JOIN '. $wpdb->prefix . 'resads_ad_adspot as AASP ON AASP.resads_ad_ad_id = AD.ad_id '
                        . 'LEFT JOIN ' . $wpdb->prefix . 'resads_adspot as ASP ON AASP.resads_adspot_adspot_id = ASP.adspot_id '
                        . 'WHERE AD.ad_name LIKE %s '
                        . 'GROUP BY AD.ad_id '
                        . 'ORDER BY ' . $this->reorder() . ' '
                        . 'LIMIT ' . $limit,
                            "%$search%"), 
                ARRAY_A);
        }
        else
        {
            $total_items = $wpdb->get_var('SELECT count(ad_id) FROM ' . $wpdb->prefix . 'resads_ad');
            $this->items = $wpdb->get_results('SELECT AD.ad_id, AD.ad_name, AD.ad_is_active, GROUP_CONCAT(ASP.adspot_name SEPARATOR ", ") as ad_spots, '
                . '(SELECT sum(adstat_views) FROM ' . $wpdb->prefix .'resads_ad_statistik as ADS WHERE ADS.resads_ad_ad_id = AD.ad_id) as sum_views, '
                . '(SELECT sum(adstat_clicks) FROM ' . $wpdb->prefix .'resads_ad_statistik as ADS WHERE ADS.resads_ad_ad_id = AD.ad_id) as sum_clicks '
                . 'FROM ' . $wpdb->prefix . 'resads_ad as AD '
                . 'LEFT JOIN '. $wpdb->prefix . 'resads_ad_adspot as AASP ON AASP.resads_ad_ad_id = AD.ad_id '
                . 'LEFT JOIN ' . $wpdb->prefix . 'resads_adspot as ASP ON AASP.resads_adspot_adspot_id = ASP.adspot_id '
                . 'GROUP BY AD.ad_id '
                . 'ORDER BY ' . $this->reorder() . ' '
                . 'LIMIT ' . $limit, 
                ARRAY_A);
        }
                
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page
        ));
    }
    /**
     * Return an column
     * @param array $item
     * @param string $column_name
     * @return string
     */
    public function column_default( $item, $column_name ) 
    {
        switch($column_name) 
        { 
            case 'ad_name':
            case 'ad_spots':
                return $item[$column_name];
            case 'ad_is_active':
                if(is_null($item[$column_name]) || $item[$column_name] == '0')
                    return __('No', RESADS_ADMIN_TEXTDOMAIN);
                else
                    return __('Yes', RESADS_ADMIN_TEXTDOMAIN);
            case 'ad_statistik':
                if(isset($item['sum_views']))
                    $statistik[] = sprintf(_n('%s View', '%s Views', $item['sum_views'], RESADS_ADMIN_TEXTDOMAIN), $item['sum_views']);
                else
                    $statistik[] = __('0 Views', RESADS_ADMIN_TEXTDOMAIN);
                
                if(isset($item['sum_clicks']))
                    $statistik[] = sprintf(_n('%s Click', '%s Clicks', $item['sum_clicks'], RESADS_ADMIN_TEXTDOMAIN), $item['sum_clicks']);
                else
                    $statistik[] = __('0 Clicks', RESADS_ADMIN_TEXTDOMAIN);
                
                return implode(', ', $statistik);
        }
    }
    /**
     * Set sortable columns
     * @return array
     */
    public function get_sortable_columns() 
    {
        $sortable_columns = array(
            'ad_name' => array('ad_name', false),
            'ad_is_active' => array('ad_is_active', false)
        );
        return $sortable_columns;
    }
    /**
     * Set Order
     * @return string
     */
    public function reorder() 
    {
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'ad_id';
        $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'desc';
        $result = $orderby . ' ' . $order;
        return $result;
    }
    /**
     * Set Ad Name column
     * @param array $item
     * @return string
     */
    public function column_ad_name($item)
    {
        $actions = array(
            'edit' => sprintf('<a href="?page=%s&action=%s&ad=%s">' . __('Edit', RESADS_ADMIN_TEXTDOMAIN) . '</a>', 'resads-admanagement', 'edit', $item['ad_id']),
            'delete' => sprintf('<a href="?page=%s&action=%s&ad=%s">' . __('Delete', RESADS_ADMIN_TEXTDOMAIN) . '</a>', 'resads-admanagement', 'delete', $item['ad_id'])
        );
        return sprintf('%1$s %2$s', $item['ad_name'], $this->row_actions($actions));
    }
    /**
     * Define Column CB
     * @param array $item
     * @return string
     */
    public function column_cb($item) 
    {
        return sprintf('<input type="checkbox" name="ad[]" value="%s" />', $item['ad_id']);   
    }
    /**
     * Set Bulk Actions
     * @return array
     */
    public function get_bulk_actions() 
    {
        $actions = array(
            'delete_selected' => __('Delete', RESADS_ADMIN_TEXTDOMAIN),
            'active_selected' => __('Active', RESADS_ADMIN_TEXTDOMAIN),
            'inactive_selected' => __('Inactive', RESADS_ADMIN_TEXTDOMAIN)
        );
        return $actions;
    }
    /**
     * Process Bulk Action
     */
    public function process_bulk_action()
    {
        switch ($this->current_action()) 
        {
            case 'delete_selected':
                if(class_exists('ResAds_AdManagement_Admin'))
                {
                    if(isset($_REQUEST['ad']))
                    {
                        $AdManagement_DB = new ResAds_AdManagement_DB();
                        $AdManagement_DB->delete_ads($_REQUEST['ad']);
                        wp_redirect(admin_url('admin.php?page=resads-admanagement'));
                        exit;
                    }
                }
                break;
            case 'inactive_selected':
                if(class_exists('ResAds_AdManagement_Admin'))
                {
                    if(isset($_REQUEST['ad']))
                    {
                        $AdManagement_DB = new ResAds_AdManagement_DB();
                        $AdManagement_DB->deactive_ads($_REQUEST['ad']);
                        wp_redirect(admin_url('admin.php?page=resads-admanagement'));
                        exit;
                    }
                }
                break;
            case 'active_selected':
                if(class_exists('ResAds_AdManagement_Admin'))
                {
                    if(isset($_REQUEST['ad']))
                    {
                        $AdManagement_DB = new ResAds_AdManagement_DB();
                        $AdManagement_DB->active_ads($_REQUEST['ad']);
                        wp_redirect(admin_url('admin.php?page=resads-admanagement'));
                        exit;
                    }
                }
                break;
            default:
                break;
        }
    }
}
/**
 * Meta Boxes
 */
class ResAds_AdManagement_Meta_Box
{
    /**
     * Submit Meta Box
     * @param post $post
     * @param array $value
     */
    public function ad_submit($post, $value)
    {
        if(isset($value['args']['data']['ad_creation_date']))
            printf('<p>%s : %s</p>', __('Created on', RESADS_ADMIN_TEXTDOMAIN), date_i18n(get_option('date_format'), strtotime($value['args']['data']['ad_creation_date'])));

        printf('<p>Status: <select name="ad_is_active"><option value="1" %s>%s</option><option value="0" %s>%s</option></select></p>', ((isset($value['args']['data']['ad_is_active']) && $value['args']['data']['ad_is_active'] == 1) ? 'selected="selected"' : ''), __('Active', RESADS_ADMIN_TEXTDOMAIN), ((isset($value['args']['data']['ad_is_active']) && $value['args']['data']['ad_is_active'] == 0) ? 'selected="selected"' : ''), __('Inactive', RESADS_ADMIN_TEXTDOMAIN));
       
        if(isset($value['args']['data']['ad_id']))
            printf('<p><a href="?page=%s&action=delete&ad=%s">%s</a></p>','resads-admanagement', $value['args']['data']['ad_id'], __('Remove', RESADS_ADMIN_TEXTDOMAIN));
        
        if(isset($value['args']['data']['ad_id']))
            printf('<input type="hidden" value="%d" name="ad_id" />',$value['args']['data']['ad_id']);
        
        printf('<input class="button button-primary button-large right" type="submit" value="%s" name="submit_ad">', __('Submit', RESADS_ADMIN_TEXTDOMAIN));
        print '<div class="clear"></div>';
    }
    /**
     * Details Meta Box
     * @param post $post
     * @param array $value
     */
    public function ad_details($post, $value)
    {
        print '<p>';       
        printf('<label for="bannername" ><strong>%s</strong></label>', __('Bannername', RESADS_ADMIN_TEXTDOMAIN));
        
        if(isset($_POST['ad_name']))
            $ad_name = $_POST['ad_name'];
        elseif(isset($value['args']['data']['ad_name']))
            $ad_name = $value['args']['data']['ad_name'];
        else
            $ad_name = '';
        
        printf('<input id="bannername" class="input-full-width" type="text" name="ad_name" value="%s" />', $ad_name);
        
        print '</p>';
        print '<p>';
        
        printf('<label for="bannerdescription" ><strong>%s</strong></label>', __('Banner Description', RESADS_ADMIN_TEXTDOMAIN)); 
        
        if(isset($_POST['ad_description'])) 
            $ad_description = $_POST['ad_description']; 
        elseif(isset($value['args']['data']['ad_description'])) 
            $ad_description = $value['args']['data']['ad_description'];
        else
            $ad_description = '';
        
        printf('<input id="bannerdescription" class="input-full-width" type="text" name="ad_description" value="%s" />', $ad_description);
        
        print '</p>';
    }
    /**
     * Link and Graphic Meta Box
     * @param post $post
     * @param array $value
     */
    public function ad_link_and_graphic($post, $value)
    {
?>
        <p>
            <label for="target-url" ><strong><?php _e('Target URL', RESADS_ADMIN_TEXTDOMAIN); ?></strong></label>
            <input id="target-url" class="regular-text" type="text" name="ad_target_url" value="<?php if(isset($_POST['ad_target_url'])) : print $_POST['ad_target_url']; elseif(isset($value['args']['data']['ad_target_url'])) : print $value['args']['data']['ad_target_url']; endif; ?>" style="width: 100%;"/>
        </p>
        <p>
            <label for="banner-url" ><strong><?php _e('Banner URL', RESADS_ADMIN_TEXTDOMAIN); ?></strong></label>
            <div style="width: 100%; clear: both;">
            <input id="banner-url" class="regular-text" type="text" name="ad_banner_url" value="<?php if(isset($_POST['ad_banner_url'])) : print $_POST['ad_banner_url']; elseif(isset($value['args']['data']['ad_banner_url'])) : print $value['args']['data']['ad_banner_url']; endif; ?>" style="width: 80%; float: left;" />
            <input id="banner-url_button" class="upload-button button" name="banner-url_button" type="text" value="<?php _e('Upload', RESADS_ADMIN_TEXTDOMAIN); ?>"/>
            </div>
        </p>
        <div style="clear: both;"></div>
        <div id="show-banner">
            <?php if(isset($_POST['ad_banner_url']) && trim($_POST['ad_banner_url']) != '' && isset($_POST['ad_banner_width']) && $_POST['ad_banner_width'] > 0 && isset($_POST['ad_banner_height']) && $_POST['ad_banner_height'] > 0 && !isset($save['error']['incorrect_banner'])) : ?>
                <?php if(isset($_POST['ad_target_url']) && trim($_POST['ad_target_url']) != '') : ?>
                    <a href="<?php print $_POST['ad_target_url']; ?>">
                    <?php $post_ad_target_url = true; ?>
                <?php endif; ?>
                    <img src="<?php print $_POST['ad_banner_url']; ?>" height="<?php print $_POST['ad_banner_height']; ?>" width="<?php print $_POST['ad_banner_width']; ?>" />
                <?php if(isset($post_ad_target_url)) : ?></a><?php endif; ?>
            <?php elseif(isset($value['args']['data']['ad_banner_url']) && trim($value['args']['data']['ad_banner_url']) != '' && isset($value['args']['data']['ad_banner_width']) && $value['args']['data']['ad_banner_width'] > 0 && isset($value['args']['data']['ad_banner_height']) && $value['args']['data']['ad_banner_height'] > 0) : ?>
                <?php if(isset($value['args']['data']['ad_target_url']) && trim($value['args']['data']['ad_target_url']) != '') : ?>
                    <a href="<?php print $value['args']['data']['ad_target_url']; ?>">
                    <?php $post_ad_target_url = true; ?>
                <?php endif; ?>
                    <img src="<?php print $value['args']['data']['ad_banner_url']; ?>" height="<?php print $value['args']['data']['ad_banner_height']; ?>" width="<?php print $value['args']['data']['ad_banner_width']; ?>" />
                <?php if(isset($post_ad_target_url)) : ?></a><?php endif; ?>
            <?php endif; ?>
        </div>
        <p>
            <label><strong><?php _e('Banner Size', RESADS_ADMIN_TEXTDOMAIN); ?></strong></label>
            <input class="regular-text ad_banner_width" type="text" name="ad_graphic_banner_width" value="<?php if(isset($_POST['ad_graphic_banner_width'])) : print $_POST['ad_graphic_banner_width']; elseif(isset($value['args']['data']['ad_banner_width'])) : print $value['args']['data']['ad_banner_width']; endif; ?>" style="width: 10%"/> x <input class="regular-text ad_banner_height" type="text" name="ad_graphic_banner_height" value="<?php if(isset($_POST['ad_graphic_banner_height'])) : print $_POST['ad_graphic_banner_height']; elseif(isset($value['args']['data']['ad_banner_height'])) : print $value['args']['data']['ad_banner_height']; endif; ?>" style="width: 10%"/>
        </p>
<?php
    }
    /**
     * Sourcecode Meta Box
     * @param post $post
     * @param array $value
     */
    public function ad_sourcecode($post , $value)
    {
?>
        <p>
            <textarea class="code" name="ad_source_code" style="width: 100%; min-height: 200px;" id="ad_source_code"><?php if(isset($_POST['ad_source_code'])) : print stripcslashes($_POST['ad_source_code']); elseif(isset($value['args']['data']['ad_source_code'])) : print stripcslashes($value['args']['data']['ad_source_code']); endif; ?></textarea>
        </p>
        <div id="show-sourcecode"></div>
        <p>
            <label><strong><?php _e('Banner Size', RESADS_ADMIN_TEXTDOMAIN); ?></strong></label>
            <input class="regular-text ad_banner_width" type="text" name="ad_source_banner_width" value="<?php if(isset($_POST['ad_source_banner_width'])) : print $_POST['ad_source_banner_width']; elseif(isset($value['args']['data']['ad_banner_width'])) : print $value['args']['data']['ad_banner_width']; endif; ?>" style="width: 10%"/> x <input class="regular-text ad_banner_height" type="text" name="ad_source_banner_height" value="<?php if(isset($_POST['ad_source_banner_height'])) : print $_POST['ad_source_banner_height']; elseif(isset($value['args']['data']['ad_banner_height'])) : print $value['args']['data']['ad_banner_height']; endif; ?>" style="width: 10%"/> <input type="checkbox" value="1" name="ad_is_responsive" id="is-responsive" <?php if(isset($_POST['ad_is_responsive']) && $_POST['ad_is_responsive'] == 1) : print 'checked'; elseif(isset($value['args']['data']['ad_is_responsive']) && $value['args']['data']['ad_is_responsive'] == 1) : print 'checked'; endif; ?>/> <label for="is-responsive"><?php _e('Banner is responsive', RESADS_ADMIN_TEXTDOMAIN); ?></label>
        </p>                            
<?php
    }
    /**
     * Banner Options Meta Box
     * @param post $post
     * @param array $value
     */
    public function ad_banner_option($post, $value)
    {
?>
        <p>
            <input type="checkbox" id="is-masked" name="ad_is_masked" value="1" <?php if(isset($_POST['ad_is_masked']) && $_POST['ad_is_masked'] == 1) : print 'checked'; elseif(isset($value['args']['data']['ad_is_masked']) && $value['args']['data']['ad_is_masked'] == 1) : print 'checked'; endif; ?> /> <label for="is-masked"><?php _e('Link mask', RESADS_ADMIN_TEXTDOMAIN); ?></label>
        </p>
        <p>
            <input type="checkbox" id="is-follow" name="ad_is_follow" value="1" <?php if(isset($_POST['ad_is_follow']) && $_POST['ad_is_follow'] == 1) : print 'checked'; elseif(isset($value['args']['data']['ad_is_follow']) && $value['args']['data']['ad_is_follow'] == 1) : print 'checked'; endif; ?> /> <label for="is-follow"><?php _e('Set link to nofollow', RESADS_ADMIN_TEXTDOMAIN); ?></label>
        </p>
        <p>
            <input type="checkbox" id="is-new-window" name="ad_is_new_window" value="1" <?php if(isset($_POST['ad_is_new_window']) && $_POST['ad_is_new_window'] == 1) : print 'checked'; elseif(isset($value['args']['data']['ad_is_new_window']) && $value['args']['data']['ad_is_new_window'] == 1) : print 'checked'; endif; ?> /> <label for="is-new-window"><?php _e('Open Link in new Window', RESADS_ADMIN_TEXTDOMAIN); ?></label>
        </p>
<?php
    }
    /**
     * AdSpots Meta Box
     * @param post $post
     * @param array $value
     */
    public function ad_banner_adspots($post, $value)
    {
        if(file_exists(RESADS_CLASS_DIR . '/AdSpot.php'))
        {
            require_once RESADS_CLASS_DIR . '/AdSpot.php';
            $AdSpots = new ResAds_AdSpot_DB();
            $ad_spots = $AdSpots->get_all_with_count();
        }
?>
        <div class="tabs-resads">
            <ul>
                <li><a href="#all"><?php  _e('All AdSpots', RESADS_ADMIN_TEXTDOMAIN); ?></a></li>
                <li><a href="#most"><?php  _e('Most Used', RESADS_ADMIN_TEXTDOMAIN); ?></a></li>
            </ul>
            <div id="all" style="height: 90px; overflow: auto; padding: 5px;">
                <?php if(isset($ad_spots)) : ?>
                    <?php foreach($ad_spots as $ad_spot) : ?>
                <div style="display: block;"><input type="checkbox" name="adspot[]" value="<?php print $ad_spot['adspot_id']; ?>" id="ad_spot_<?php print $ad_spot['adspot_id']; ?>" class="ad_spot_checkbox ad_spot_<?php print $ad_spot['adspot_id']; ?>" <?php if(isset($_POST['adspot']) && is_array($_POST['adspot']) && in_array($ad_spot['adspot_id'], $_POST['adspot'])) : print 'checked'; elseif(isset($value['args']['data']['adspot_ids']) && is_array($value['args']['data']['adspot_ids']) && in_array($ad_spot['adspot_id'], $value['args']['data']['adspot_ids'])) : print 'checked'; endif; ?> /><label for="ad_spot_<?php print $ad_spot['adspot_id']; ?>"><?php print $ad_spot['adspot_name']; ?></label></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div id="most" style="padding: 5px;">
                <?php if(isset($ad_spots)) : ?>
                    <?php $most_used = array_chunk($ad_spots, 5); ?>
                    <?php if(is_array($most_used)) : ?>
                    <?php if(isset($most_used[0])) : ?>
                    <?php foreach($most_used[0] as $most) : ?>
                        <div style="display: block;"><input type="checkbox" name="adspot[]" value="<?php print $most['adspot_id']; ?>" id="ad_spot_<?php print $most['adspot_id']; ?>" class="ad_spot_checkbox ad_spot_<?php print $most['adspot_id']; ?>" <?php if(isset($_POST['adspot']) && is_array($_POST['adspot']) && in_array($most['adspot_id'], $_POST['adspot'])) : print 'checked'; elseif(isset($value['args']['data']['adspot_ids']) && is_array($value['args']['data']['adspot_ids']) && in_array($most['adspot_id'], $value['args']['data']['adspot_ids'])) : print 'checked'; endif; ?> /><label for="ad_spot_<?php print $most['adspot_id']; ?>"><?php print $most['adspot_name']; ?></label></div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>        
        </div>
<?php
    }
    /**
     * Tutorial Description Meta Box
     * @param post $post
     * @param array $value
     */
    public function ad_tutorial_description($post, $value)
    {
        print '<ol>';
        printf('<li>%s</li>', __('Carrying a banner name', RESADS_ADMIN_TEXTDOMAIN));
        printf('<li>%s<br /><span style="color: red;">%s</span></li>', __('Carrying a target url and banner url OR a source code and banner size', RESADS_ADMIN_TEXTDOMAIN), __('You don\'t need both!', RESADS_ADMIN_TEXTDOMAIN));
        printf('<li>%s</li>', __('Select options', RESADS_ADMIN_TEXTDOMAIN));
        printf('<li>%s</li>', __('Select AdSpot(s)', RESADS_ADMIN_TEXTDOMAIN));
        printf('<li>%s</li>', __('Submit', RESADS_ADMIN_TEXTDOMAIN));
        print '</ol>';
    }
}
?>