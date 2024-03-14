<?php
if(!defined('ABSPATH')) die('You are not allowed to call this page directly.');
class ResAds_AdSpot
{
    /**
     * AdSpot
     * @var array
     */
    private $adspot;
    /**
     * Set AdSpot
     * @param array $adspot
     */
    public function set_adspot($adspot)
    {
        $this->adspot = $adspot;
    }
    /**
     * Render an AdSpot
     * @param array $adspot
     */
    public function render_adspot($adspot, $inner_html = '', $echo = true)
    {
        if(is_array($adspot) && isset($adspot['adspot_id']))
        {
            if(isset($adspot['adspot_ad']['ad_id']))
            {
                if($echo)
                {
                    printf('<div class="resads-adspot resads-adspot-%d" adspot="%d" ad="%d">%s</div>', $adspot['adspot_id'], $adspot['adspot_id'], $adspot['adspot_ad']['ad_id'], $inner_html);
                }
                else
                {
                    return sprintf('<div class="resads-adspot resads-adspot-%d" adspot="%d" ad="%d">%s</div>', $adspot['adspot_id'], $adspot['adspot_id'], $adspot['adspot_ad']['ad_id'], $inner_html);
                }
            }
            else 
            {
                if($echo)
                {
                    printf('<div class="resads-adspot resads-adspot-%d" adspot="%d"></div>', $adspot['adspot_id'], $adspot['adspot_id']);
                }
                else
                {
                    return sprintf('<div class="resads-adspot resads-adspot-%d" adspot="%d"></div>', $adspot['adspot_id'], $adspot['adspot_id']);
                }
            }
        }
        
    }
}
/**
 * AdSpots Admin
 */
class ResAds_AdSpot_Admin
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
        
        if(isset($_REQUEST['page']) && trim($_REQUEST['page']) == 'resads-adspots')  
            $this->process_action();

        $this->hook = add_submenu_page('resads', __('AdSpots', RESADS_ADMIN_TEXTDOMAIN), __('AdSpots', RESADS_ADMIN_TEXTDOMAIN), RESADS_PERMISSION_ROLE, 'resads-adspots', array($this, 'page'));               
        add_action("load-$this->hook", array(new ResAds_AdSpot_List_Table(), 'add_options'));
        add_action("load-$this->hook", array($this, 'add_page_actions'), 9);
        add_action("load-$this->hook", array($this, 'add_scripts'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
    }
    /**
     * Process Action
     */
    public function process_action()
    {
        if(isset($_REQUEST['action']) && trim($_REQUEST['action']) != '')
        {
            $this->action = trim($_REQUEST['action']);
            if($this->action == 'new')
            {
                if(isset($_POST['submit_adspot']))
                {
                    $this->submit_response = $this->save($_POST);
                    if($this->submit_response === TRUE)
                    {
                        wp_redirect(admin_url('admin.php?page=resads-adspots'));
                        exit;
                    }
                }
            }
            elseif($this->action == 'edit')
            {
                if(isset($_POST['submit_adspot']))
                {
                    $this->submit_response = $this->save($_POST);
                    if($this->submit_response === TRUE)
                    {
                        wp_redirect(admin_url('admin.php?page=resads-adspots'));
                        exit;
                    }
                }
                $AdSpot_DB = new ResAds_AdSpot_DB();
                $this->data = $AdSpot_DB->get($_GET['adspot']);
                
                if(isset($this->data['adspot_ads']) && is_array($this->data['adspot_ads']))
                {
                    foreach($this->data['adspot_ads'] as $adspot)
                    {
                        $this->data['adspot_banner'][] = $adspot['ad_id'];
                    }
                }
            }
            elseif($this->action == 'delete')
            {
                $AdSpot_DB = new ResAds_AdSpot_DB();
                $AdSpot_DB->delete_adspots(array($_GET['adspot']));               
                wp_redirect(admin_url('admin.php?page=resads-adspots'));
                exit;
            }
        }
    }
    /**
     * Include Template
     */
    public function page()
    {
        if(isset($this->action) && trim($this->action) != '')
        {
            if($this->action == 'new')
            {
                if(file_exists(RESADS_TEMPLATE_DIR . '/adspot/new.php'))
                    require_once RESADS_TEMPLATE_DIR . '/adspot/new.php';
            }
            elseif($this->action == 'edit' && isset($_GET['adspot']) && is_numeric($_GET['adspot']) && $_GET['adspot'] > 0)
            {  
                if(file_exists(RESADS_TEMPLATE_DIR . '/adspot/edit.php'))
                    require_once RESADS_TEMPLATE_DIR . '/adspot/edit.php';
            }
            else
            {
                if(file_exists(RESADS_TEMPLATE_DIR . '/adspot/adspot.php'))
                    require_once RESADS_TEMPLATE_DIR . '/adspot/adspot.php';
            }
        }
        else
        {
            if(file_exists(RESADS_TEMPLATE_DIR . '/adspot/adspot.php'))
                require_once RESADS_TEMPLATE_DIR . '/adspot/adspot.php';
        }
    }
    /**
     * Add or Edit an AdSpot
     * @param array $data
     * @return boolean
     */
    public function save($data)
    {
        $message = array();
        
        if(!isset($data['adspot_name']) || trim($data['adspot_name']) == '')
            $message['error'][] = __('Name failed', RESADS_ADMIN_TEXTDOMAIN);
        
        if(isset($message['error']))
            return $message;
        
        if(!isset($data['adspot_description']) || trim($data['adspot_description']) == '')
            $data['adspot_description'] = null;
        
        if(!isset($data['adspot_show_bottom_article']) || trim($data['adspot_show_bottom_article']) == '')
            $data['adspot_show_bottom_article'] = 0;
        
        if(!isset($data['adspot_show_top_article']) || trim($data['adspot_show_top_article']) == '')
            $data['adspot_show_top_article'] = 0;
        
        if(!isset($data['adspot_show_top_inside_article']) || trim($data['adspot_show_top_inside_article']) == '')
            $data['adspot_show_top_inside_article'] = 0;
            
        if(isset($data['adspot_id']) && is_numeric($data['adspot_id']) && $data['adspot_id'] > 0)
        {
            $AdSpot_DB = new ResAds_AdSpot_DB();
            if($AdSpot_DB->edit($data))
                return true;
        }
        else
        {
            $AdSpot_DB = new ResAds_AdSpot_DB();
            if($AdSpot_DB->add($data))
                return true;
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
                $Meta_Box = new ResAds_AdSpot_Meta_Box();
                
                add_meta_box('resads-adspot-details', __('AdSpot Details', RESADS_ADMIN_TEXTDOMAIN), array($Meta_Box, 'adspot_details'), $this->hook, 'normal', 'high', array('data' => $this->data));
                add_meta_box('resads-adspot-banner', __('Banner', RESADS_ADMIN_TEXTDOMAIN), array($Meta_Box, 'adspot_banner'), $this->hook, 'normal', 'high', array('data' => $this->data));
                add_meta_box('resads-adspot-option', __('AdSpot Options', RESADS_ADMIN_TEXTDOMAIN), array($Meta_Box, 'adspot_option'), $this->hook, 'normal', 'high', array('data' => $this->data));               
                if($this->action == 'edit')
                {
                    add_meta_box('resads-adspot-shortcode', __('AdSpot Shortcode', RESADS_ADMIN_TEXTDOMAIN), array($Meta_Box, 'adspot_shortcode'), $this->hook, 'normal', 'high', array('data' => $this->data));
                }
                
                add_meta_box('resads-adspot-submit', __('Submit', RESADS_ADMIN_TEXTDOMAIN), array($Meta_Box, 'adspot_submit'), $this->hook, 'side', 'high', array('data' => $this->data));               
            }
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
                wp_enqueue_script('suggest');
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
}
/**
 * AdSpot DB
 */
class ResAds_AdSpot_DB 
{
    /**
     * wpdb instance
     * @var wpdb $wpdb
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
     * Create DB-Table
     */
    public function create_database_table()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      
        $sql = "CREATE TABLE " . $this->wpdb->prefix . "resads_adspot (
            adspot_id int(10) unsigned NOT NULL AUTO_INCREMENT,
            adspot_name varchar(255) NOT NULL,
            adspot_description varchar(255) DEFAULT NULL,
            adspot_show_bottom_article int(1) unsigned NOT NULL,
            adspot_show_top_article int(1) unsigned NOT NULL,
            adspot_show_top_inside_article int(1) unsigned NOT NULL,
            PRIMARY KEY (adspot_id)
          ) DEFAULT CHARSET=utf8;";
        dbDelta($sql);
        
        $sql = "CREATE TABLE " . $this->wpdb->prefix . "resads_ad_adspot (
            resads_ad_ad_id int(10) unsigned NOT NULL,
            resads_adspot_adspot_id int(10) unsigned NOT NULL,
            PRIMARY KEY  (resads_ad_ad_id,resads_adspot_adspot_id))";
        dbDelta($sql);
    }
    /**
     * Delete DB-Table
     */
    public function delete_database_table()
    {
        $this->wpdb->query("DROP TABLE IF EXISTS " . $this->wpdb->prefix . "resads_adspot");
        $this->wpdb->query("DROP TABLE IF EXISTS " . $this->wpdb->prefix . "resads_ad_adspot");
    }
    /**
     * Returns all AdSpots
     * @return array
     */
    public function get_all($order = '', $limit = '')
    {
        if(trim($limit) != '')
            $limit = ' LIMIT ' . $limit;
        
        if(trim($order) != '')
            $order = ' ORDER BY ' . $order;
        
        return $this->wpdb->get_results('SELECT * FROM ' . $this->wpdb->prefix . 'resads_adspot ' . $order . $limit, ARRAY_A);
    }
    /**
     * Returns all AdSpots with there count
     * @return array
     */
    public function get_all_with_count()
    {
        return $this->wpdb->get_results('SELECT (SELECT count(resads_adspot_adspot_id) FROM ' . $this->wpdb->prefix. 'resads_ad_adspot as AAS WHERE AAS.resads_adspot_adspot_id = ADS.adspot_id) as cnt, ADS.* FROM ' . $this->wpdb->prefix . 'resads_adspot as ADS ORDER BY cnt DESC', ARRAY_A);
    }
    /**
     * Get an AdSpot by ID
     * @param int $adspot_id
     * @return array
     */
    public function get($adspot_id)
    {
        $adspot = $this->wpdb->get_row($this->wpdb->prepare('SELECT * FROM ' . $this->wpdb->prefix . 'resads_adspot WHERE adspot_id = %d', $adspot_id), ARRAY_A);
        if($adspot && isset($adspot['adspot_id']))
        {
            $adspot['adspot_ads'] = $this->wpdb->get_results($this->wpdb->prepare('SELECT A.* FROM ' . $this->wpdb->prefix . 'resads_ad_adspot AAS JOIN ' . $this->wpdb->prefix . 'resads_ad A ON A.ad_id = AAS.resads_ad_ad_id WHERE AAS.resads_adspot_adspot_id = %d', $adspot['adspot_id']), ARRAY_A);
        }
        return $adspot;
    }
    /**
     * Return an adspot with random banner
     * @param int $adspot_id
     * @param array $accepted_resolutions
     * @return array
     */
    public function get_random_banner($adspot_id, $accepted_resolutions = array())
    {
        $adspot = $this->wpdb->get_row($this->wpdb->prepare('SELECT * FROM ' . $this->wpdb->prefix . 'resads_adspot WHERE adspot_id = %d', $adspot_id), ARRAY_A);
        if($adspot && isset($adspot['adspot_id']))
        {
            if(is_array($accepted_resolutions) && count($accepted_resolutions) > 0)
            {
                $banner_where_str = '';
                $counter = 1;
                foreach ($accepted_resolutions as $key => $resolution) 
                {
                    if($counter == 1)
                    {
                        $banner_where_str .= ' AND (';
                        $banner_where_str .= sprintf('(A.ad_banner_width = %d AND A.ad_banner_height = %d)', $resolution['res_banner_width'], $resolution['res_banner_height']);                   
                    }
                    else
                    {
                        $banner_where_str .= sprintf(' OR (A.ad_banner_width = %d AND A.ad_banner_height = %d)', $resolution['res_banner_width'], $resolution['res_banner_height']);
                    }
                    
                    if($counter == count($accepted_resolutions))
                        $banner_where_str .= ' OR (ad_source_code IS NOT NULL AND ad_is_responsive = 1) )';
                        
                    $counter++;
                }
                $adspot['adspot_ad'] = $this->wpdb->get_row($this->wpdb->prepare('SELECT A.* FROM ' . $this->wpdb->prefix . 'resads_ad_adspot AAS JOIN ' . $this->wpdb->prefix . 'resads_ad A ON A.ad_id = AAS.resads_ad_ad_id WHERE AAS.resads_adspot_adspot_id = %d AND A.ad_is_active = 1 ' . $banner_where_str .' ORDER BY RAND() LIMIT 1', $adspot['adspot_id']), ARRAY_A);
            }
            else
            {
                $adspot['adspot_ad'] = $this->wpdb->get_row($this->wpdb->prepare('SELECT A.* FROM ' . $this->wpdb->prefix . 'resads_ad_adspot AAS JOIN ' . $this->wpdb->prefix . 'resads_ad A ON A.ad_id = AAS.resads_ad_ad_id WHERE AAS.resads_adspot_adspot_id = %d AND a.ad_is_active = 1 ORDER BY RAND() LIMIT 1', $adspot['adspot_id']), ARRAY_A);
            }
            
            if(isset($adspot['adspot_ad']))      
                return $adspot;
        }
    }
    /**
     * Delete all AdSpots
     * @param array $adspot_ids
     */
    public function delete_adspots($adspot_ids)
    {
        if(is_array($adspot_ids))
        {
            $this->wpdb->query('DELETE FROM ' . $this->wpdb->prefix . 'resads_adspot WHERE adspot_id IN (' . implode(',', $adspot_ids) . ')');
            $this->wpdb->query('DELETE FROM ' . $this->wpdb->prefix . 'resads_ad_adspot WHERE resads_adspot_adspot_id IN (' . implode(',', $adspot_ids) . ')');
        }
    }
    /**
     * Edit an AdSpot
     * @param array $data
     * @return boolean
     */
    public function edit($data)
    {
        if(is_array($data))
        {
            $adspot_update = $this->wpdb->update($this->wpdb->prefix . 'resads_adspot', array('adspot_name' => $data['adspot_name'], 'adspot_description' => $data['adspot_description'], 'adspot_show_bottom_article' => $data['adspot_show_bottom_article'], 'adspot_show_top_article' => $data['adspot_show_top_article'], 'adspot_show_top_inside_article' => $data['adspot_show_top_inside_article']), array('adspot_id' => $data['adspot_id']), array('%s', '%s', '%d', '%d', '%d'), array('%d'));
            $this->wpdb->delete($this->wpdb->prefix . 'resads_ad_adspot', array('resads_adspot_adspot_id' => $data['adspot_id']), array('%d'));
            if(isset($data['adspot_banner']) && is_array($data['adspot_banner']))
            {
                $data['adspot_banner'] = array_unique($data['adspot_banner']);
                foreach($data['adspot_banner'] as $adspot_banner_id)
                {
                    $banner_insert = $this->wpdb->insert($this->wpdb->prefix . 'resads_ad_adspot', array('resads_adspot_adspot_id' => $data['adspot_id'], 'resads_ad_ad_id' => $adspot_banner_id), array('%d', '%d'));
                    if(!$banner_insert)
                    {
                        $this->wpdb->delete($this->wpdb->prefix . 'resads_ad_adspot', array('resads_adspot_adspot_id' => $data['adspot_id']), array('%d'));
                        return false;
                    }
                }
                return true;
            }
            else
            {
                return $adspot_update;
            }
        }
    }
    /**
     * Add an AdSpot
     * @param array $data
     * @return boolean
     */
    public function add($data)
    {
        if(is_array($data))
        {
            $adspot_insert = $this->wpdb->insert($this->wpdb->prefix . 'resads_adspot', array('adspot_name' => $data['adspot_name'], 'adspot_description' => $data['adspot_description'], 'adspot_show_bottom_article' => $data['adspot_show_bottom_article'], 'adspot_show_top_article' => $data['adspot_show_top_article'], 'adspot_show_top_inside_article' => $data['adspot_show_top_inside_article']), array('%s', '%s', '%d', '%d', '%d'));
            if($adspot_insert && isset($data['adspot_banner']) && is_array($data['adspot_banner']))
            {
                $adspot_id = $this->wpdb->insert_id;
                $data['adspot_banner'] = array_unique($data['adspot_banner']);
                foreach($data['adspot_banner'] as $adspot_banner_id)
                {
                    $banner_insert = $this->wpdb->insert($this->wpdb->prefix . 'resads_ad_adspot', array('resads_adspot_adspot_id' => $adspot_id, 'resads_ad_ad_id' => $adspot_banner_id), array('%d', '%d'));
                    if(!$banner_insert)
                    {
                        $this->wpdb->delete($this->wpdb->prefix . 'resads_ad_adspot', array('resads_adspot_adspot_id' => $adspot_id), array('%d'));
                        return false;
                    }
                }
                return $adspot_insert;
            }
            elseif($adspot_insert)
            {
                return $adspot_insert;
            }
        }
    }
    /**
     * Get random adspot by position(s)
     * @param boolean $bottom
     * @param boolean $top
     * @return array
     */
    public function get_random_by_article_position($bottom = false, $top = false, $top_inside = false)
    {
        $adspots = array();
        if($bottom)
            $adspots['bottom'] = $this->get_random_bottom_article();
        
        if($top)
            $adspots['top'] = $this->get_random_top_article();
        
        if($top_inside)
            $adspots['top_inside'] = $this->get_random_top_inside_article();
        
        return $adspots;
    }
    /**
     * Get AdSpot on bottom
     * @return array
     */
    public function get_random_bottom_article()
    {
        $adspot = $this->wpdb->get_row('SELECT * FROM ' . $this->wpdb->prefix . 'resads_adspot WHERE adspot_show_bottom_article > 0 ORDER BY RAND() LIMIT 1', ARRAY_A);
        return $adspot;
    }
    /**
     * Get AdSpot on top
     * @return array
     */
    public function get_random_top_article()
    {
        $adspot = $this->wpdb->get_row('SELECT * FROM ' . $this->wpdb->prefix . 'resads_adspot WHERE adspot_show_top_article > 0 ORDER BY RAND() LIMIT 1', ARRAY_A);
        return $adspot;
    }
    /**
     * Get random  AdSpot top inside article
     * @return array
     */
    public function get_random_top_inside_article()
    {
        $adspot = $this->wpdb->get_row('SELECT * FROM ' . $this->wpdb->prefix . 'resads_adspot WHERE adspot_show_top_inside_article > 0 ORDER BY RAND() LIMIT 1', ARRAY_A);
        return $adspot;
    }
    /**
     * Return only adspot data
     * @param int $adspot_id
     * @return array
     */
    public function get_only_adspot($adspot_id)
    {
        return $this->wpdb->get_row($this->wpdb->prepare('SELECT * FROM ' . $this->wpdb->prefix . 'resads_adspot WHERE adspot_id = %d', $adspot_id), ARRAY_A);
    }
}
if(!class_exists('WP_List_Table'))
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
/**
 * AdSpots List Table
 */
class ResAds_AdSpot_List_Table extends WP_List_Table
{
    /**
     * Set Columns
     * @return array
     */
    public function get_columns() 
    {
        $columns = array(
            'cb' => '<input type="checkbox">',
            'adspot_name' => __('Name', RESADS_ADMIN_TEXTDOMAIN),
            'adspot_id' => __('ID', RESADS_ADMIN_TEXTDOMAIN),
            'adspot_banner' => __('Banner', RESADS_ADMIN_TEXTDOMAIN),
            'adspot_shortcode' => __('Shortcode', RESADS_ADMIN_TEXTDOMAIN)
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
                'label' => __('AdSpots', RESADS_ADMIN_TEXTDOMAIN),
                'default' => 10,
                'option' => 'adspot_per_page'
            );
            add_screen_option($option, $args);
        }
    }
    /**
     * Get and set data
     * @global wpdb $wpdb
     */
    public function prepare_items() 
    {
        global $wpdb;
        
        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
        
        $this->process_bulk_action();
        
        $per_page = $this->get_items_per_page('adspot_per_page', 10);
        $current_page = $this->get_pagenum();
        $limit = $per_page * $current_page - $per_page . ',' . $per_page;

        if(isset($_REQUEST['s']) && trim($_REQUEST['s']) != '')
        {
            $search = trim($_REQUEST['s']);
            $total_items = $wpdb->get_var($wpdb->prepare('SELECT count(adspot_id) FROM ' . $wpdb->prefix . 'resads_adspot WHERE adspot_name LIKE %s', "%$search%"));
            $this->items = $wpdb->get_results(
                    $wpdb->prepare('SELECT ASP.adspot_name, ASP.adspot_id, (SELECT count(resads_adspot_adspot_id) FROM ' . $wpdb->prefix . 'resads_ad_adspot as AASP WHERE AASP.resads_adspot_adspot_id = ASP.adspot_id) as banner_anzahl '
                            . 'FROM ' . $wpdb->prefix . 'resads_adspot as ASP '
                            . 'WHERE ASP.adspot_name LIKE %s '
                            . 'ORDER BY ' . $this->reorder() . ' '
                            . 'LIMIT ' . $limit,
                            "%$search%"), 
                    ARRAY_A);
        }
        else
        {
            $total_items = $wpdb->get_var('SELECT count(adspot_id) FROM ' . $wpdb->prefix . 'resads_adspot');
            $this->items = $wpdb->get_results('SELECT ASP.adspot_name, ASP.adspot_id, (SELECT count(resads_adspot_adspot_id) FROM ' . $wpdb->prefix . 'resads_ad_adspot as AASP WHERE AASP.resads_adspot_adspot_id = ASP.adspot_id) as banner_anzahl '
                        . 'FROM ' . $wpdb->prefix . 'resads_adspot as ASP '
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
     * Set Columns returns
     * @param array $item
     * @param string $column_name
     * @return string
     */
    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'adspot_name':
            case 'adspot_id':
                return $item[$column_name];
                break;
            case 'adspot_banner':
                return $item['banner_anzahl'];
                break;
         case 'adspot_shortcode':
                return sprintf('[resads_adspot id="%d"]', $item['adspot_id']);
                break;
            default:
                break;
        }
    }
    /**
     * Define AdSpot Name column
     * @param array $item
     * @return string
     */
    public function column_adspot_name($item)
    {
        $actions = array(
            'edit' => sprintf('<a href="?page=%s&action=%s&adspot=%s">' . __('Edit', RESADS_ADMIN_TEXTDOMAIN) . '</a>', 'resads-adspots', 'edit', $item['adspot_id']),
            'delete' => sprintf('<a href="?page=%s&action=%s&adspot=%s">' . __('Delete', RESADS_ADMIN_TEXTDOMAIN) . '</a>', 'resads-adspots', 'delete', $item['adspot_id'])
        );
        return sprintf('%1$s %2$s', $item['adspot_name'], $this->row_actions($actions));
    }
    /**
     * Define sortable columns
     * @return array
     */
    public function get_sortable_columns() 
    {
        $sortable_columns = array(
            'adspot_name' => array('adspot_name', false),
            'adspot_id' => array('adspot_id', false)
        );
        return $sortable_columns;
    }
    /**
     * Order
     * @return string
     */
    public function reorder()
    {
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'adspot_id';
        $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'desc';
        $result = $orderby . ' ' . $order;
        return $result;        
    }
    /**
     * Define Column CB
     * @param array $item
     * @return string
     */
    public function column_cb($item) 
    {
        return sprintf('<input type="checkbox" name="adspot[]" value="%s" />', $item['adspot_id']);   
    }
    /**
     * Set Bulk Actions
     * @return array
     */
    public function get_bulk_actions() 
    {
        $actions = array(
            'delete_selected' => __('Delete', RESADS_ADMIN_TEXTDOMAIN)
        );
        return $actions;
    }
    /**
     * Process Bulk Actions
     */
    public function process_bulk_action()
    {
        switch ($this->current_action()) 
        {
            case 'delete_selected':
                if(class_exists('ResAds_AdSpot_Admin'))
                {
                    if(isset($_REQUEST['adspot']))
                    {
                        $AdSpot_DB = new ResAds_AdSpot_DB();
                        $AdSpot_DB->delete_adspots($_REQUEST['adspot']);
                        wp_redirect(admin_url('admin.php?page=resads-adspots'));
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
 * AdSpot Meta Boxes
 */
class ResAds_AdSpot_Meta_Box
{
    /**
     * Submit Meta Box
     * @param post $post
     * @param array $value
     */
    public function adspot_submit($post, $value)
    {
        if(isset($value['args']['data']['adspot_id']))
        {
            printf('<input type="hidden" value="%d" name="adspot_id" />', $value['args']['data']['adspot_id']);
            printf('<p><a href="?page=%s&action=delete&adspot=%s">%s</a></p>', 'resads-adspots', $value['args']['data']['adspot_id'], __('Remove', RESADS_ADMIN_TEXTDOMAIN));
        }
        
        printf('<input class="button button-primary button-large right" type="submit" value="%s" name="submit_adspot">', __('Submit', RESADS_ADMIN_TEXTDOMAIN));
        print '<div class="clear"></div>';
    }
    /**
     * Details Meta Box
     * @param post $post
     * @param array $value
     */
    public function adspot_details($post, $value)
    {
        print '<p>';
        printf('<label for="adspot_name"><strong>%s</strong></label>', __('Name', RESADS_ADMIN_TEXTDOMAIN));
        
        $adspot_name = '';
        if(isset($_POST['adspot_name']))
            $adspot_name = $_POST['adspot_name'];
        elseif(isset($value['args']['data']['adspot_name']))
            $adspot_name = $value['args']['data']['adspot_name'];
        
        printf('<input id="adspot_name" type="text" class="input-full-width" name="adspot_name" value="%s" />', $adspot_name);       
        print '</p>';
        
        print '<p>';
        printf('<label for="adspot_description"><strong>%s</strong></label>', __('Description', RESADS_ADMIN_TEXTDOMAIN));
        
        $adspot_description = '';
        if(isset($_POST['adspot_description']))
            $adspot_description = $_POST['adspot_description'];
        elseif(isset($value['args']['data']['adspot_description']))
            $adspot_description = $value['args']['data']['adspot_description'];
               
        printf('<input id="adspot_description" type="text" class="input-full-width" name="adspot_description" value="%s" />', $adspot_description);   
        print '</p>';
    }
    /**
     * Banner Meta Box
     * @param post $post
     * @param array $value
     */
    public function adspot_banner($post, $value)
    {
        print '<p>';
        printf('<label for="adspot_banner"><strong>%s</strong></label>', __('Choose Banner', RESADS_ADMIN_TEXTDOMAIN));

        $adspot_banner = array();
        if(isset($_POST['adspot_banner']))
            $adspot_banner = $_POST['adspot_banner'];
        elseif(isset($value['args']['data']['adspot_banner']))
            $adspot_banner = $value['args']['data']['adspot_banner'];
        
        if(file_exists(RESADS_CLASS_DIR . '/AdManagement.php'))
        {
            $AdManagement_DB = new ResAds_AdManagement_DB();
            $ads = $AdManagement_DB->get_all();
        }
        
        print '<select id="adspot_banner" type="text" name="adspot_banner[]" class="input-full-width" multiple="true" />';       
        
        if(isset($ads) && is_array($ads))
        {
            $count_banner = count($adspot_banner);
            foreach($ads as $ad)
            {
                $selected = '';
                if(isset($adspot_banner) && is_array($adspot_banner) && $count_banner > 0)
                {
                    if(in_array($ad['ad_id'], $adspot_banner))
                    {
                        $selected = 'selected="selected"';
                    }
                }
                printf('<option value="%s" %s>%s (%dx%d)</option>', $ad['ad_id'], $selected, $ad['ad_name'], $ad['ad_banner_width'], $ad['ad_banner_height']);
            }
        }
        
        print '</select>';
        print '</p>';
    }
    /**
     * Option Meta Box
     * @param post $post
     * @param array $value
     */    
    public function adspot_option($post, $value)
    {  
        $adspot_show_top_article_right = '';
        $adspot_show_top_article_left = '';
        $adspot_show_top_article_middle = '';
        $adspot_show_top_article_not = '';
        
        if(isset($_POST['adspot_show_top_article']) && $_POST['adspot_show_top_article'] == 3)
            $adspot_show_top_article_right = 'checked="checked"';
        elseif(isset($value['args']['data']['adspot_show_top_article']) && $value['args']['data']['adspot_show_top_article'] == 3)
            $adspot_show_top_article_right = 'checked="checked"';
        
        if(isset($_POST['adspot_show_top_article']) && $_POST['adspot_show_top_article'] == 1)
            $adspot_show_top_article_left = 'checked="checked"';
        elseif(isset($value['args']['data']['adspot_show_top_article']) && $value['args']['data']['adspot_show_top_article'] == 1)
            $adspot_show_top_article_left = 'checked="checked"';
        
        if(isset($_POST['adspot_show_top_article']) && $_POST['adspot_show_top_article'] == 2)
            $adspot_show_top_article_middle = 'checked="checked"';
        elseif(isset($value['args']['data']['adspot_show_top_article']) && $value['args']['data']['adspot_show_top_article'] == 2)
            $adspot_show_top_article_middle = 'checked="checked"';
        
        if(isset($_POST['adspot_show_top_article']) && $_POST['adspot_show_top_article'] == 0)
            $adspot_show_top_article_not = 'checked="checked"';
        elseif(isset($value['args']['data']['adspot_show_top_article']) && $value['args']['data']['adspot_show_top_article'] == '0')
            $adspot_show_top_article_not = 'checked="checked"';
        
        print '<div class="admin-adspot-position">';

        printf('<strong>%s</strong><br />', __('Show top of an article', RESADS_ADMIN_TEXTDOMAIN));
        
        
        print '<div class="admin-adspot-position-option">';
        printf('<input id="adspot_show_top_article_left" type="radio" name="adspot_show_top_article" value="1" %s />', $adspot_show_top_article_left);
        printf('<label for="adspot_show_top_article_left"><img src="%s"> <strong>%s</strong></label>', RESADS_PLUGIN_URL . '/img/top-left.png' ,__('align left', RESADS_ADMIN_TEXTDOMAIN));
        print '</div>';
        
        print '<div class="admin-adspot-position-option">';
        printf('<input id="adspot_show_top_article_middle" type="radio" name="adspot_show_top_article" value="2" %s />', $adspot_show_top_article_middle);
        printf('<label for="adspot_show_top_article_middle"><img src="%s"> <strong>%s</strong></label>', RESADS_PLUGIN_URL . '/img/top-middle.png' ,__('align middle', RESADS_ADMIN_TEXTDOMAIN));
        print '</div>';
        
        print '<div class="admin-adspot-position-option">';
        printf('<input id="adspot_show_top_article_right" type="radio" name="adspot_show_top_article" value="3" %s />', $adspot_show_top_article_right);
        printf('<label for="adspot_show_top_article_right"><img src="%s"> <strong>%s</strong></label>', RESADS_PLUGIN_URL . '/img/top-right.png' ,__('align right', RESADS_ADMIN_TEXTDOMAIN));
        print '</div>';
        
        print '<div class="admin-adspot-position-option">';
        printf('<input id="adspot_show_top_article_not" type="radio" name="adspot_show_top_article" value="0" %s />', $adspot_show_top_article_not);
        printf('<label for="adspot_show_top_article_not"><strong>%s</strong></label>', __('don\'t show', RESADS_ADMIN_TEXTDOMAIN));
        print '</div>';

        print '</div>';
        print '<div style="clear:both;"></div>';
        
        $adspot_show_bottom_article_right = '';
        $adspot_show_bottom_article_left = '';
        $adspot_show_bottom_article_middle = '';
        $adspot_show_bottom_article_not = '';
        
        if(isset($_POST['adspot_show_bottom_article']) && $_POST['adspot_show_bottom_article'] == 3)
            $adspot_show_bottom_article_right = 'checked="checked"';
        elseif(isset($value['args']['data']['adspot_show_bottom_article']) && $value['args']['data']['adspot_show_bottom_article'] == 3)
            $adspot_show_bottom_article_right = 'checked="checked"';
        
        if(isset($_POST['adspot_show_bottom_article']) && $_POST['adspot_show_bottom_article'] == 1)
            $adspot_show_bottom_article_left = 'checked="checked"';
        elseif(isset($value['args']['data']['adspot_show_bottom_article']) && $value['args']['data']['adspot_show_bottom_article'] == 1)
            $adspot_show_bottom_article_left = 'checked="checked"';
        
        if(isset($_POST['adspot_show_bottom_article']) && $_POST['adspot_show_bottom_article'] == 2)
            $adspot_show_bottom_article_middle = 'checked="checked"';
        elseif(isset($value['args']['data']['adspot_show_bottom_article']) && $value['args']['data']['adspot_show_bottom_article'] == 2)
            $adspot_show_bottom_article_middle = 'checked="checked"';
        
        if(isset($_POST['adspot_show_bottom_article']) && $_POST['adspot_show_bottom_article'] == '0')
            $adspot_show_bottom_article_not = 'checked="checked"';
        elseif(isset($value['args']['data']['adspot_show_bottom_article']) && $value['args']['data']['adspot_show_bottom_article'] == 0)
            $adspot_show_bottom_article_not = 'checked="checked"';
        
        print '<div class="admin-adspot-position">';
        printf('<strong>%s</strong><br />', __('Show bottom of an article', RESADS_ADMIN_TEXTDOMAIN));
        
        print '<div class="admin-adspot-position-option">';
        printf('<input id="adspot_show_bottom_article_left" type="radio" name="adspot_show_bottom_article" value="1" %s />', $adspot_show_bottom_article_left);
        printf('<label for="adspot_show_bottom_article_left"><img src="%s"> <strong>%s</strong></label>', RESADS_PLUGIN_URL . '/img/bottom-left.png',__('align left', RESADS_ADMIN_TEXTDOMAIN));
        print '</div>';
        
        print '<div class="admin-adspot-position-option">';
        printf('<input id="adspot_show_bottom_article_middle" type="radio" name="adspot_show_bottom_article" value="2" %s />', $adspot_show_bottom_article_middle);
        printf('<label for="adspot_show_bottom_article_middle"><img src="%s"> <strong>%s</strong></label>', RESADS_PLUGIN_URL . '/img/bottom-middle.png', __('align middle', RESADS_ADMIN_TEXTDOMAIN));
        print '</div>';
       
        print '<div class="admin-adspot-position-option">';
        printf('<input id="adspot_show_bottom_article_right" type="radio" name="adspot_show_bottom_article" value="3" %s />', $adspot_show_bottom_article_right);
        printf('<label for="adspot_show_bottom_article_right"><img src="%s"> <strong>%s</strong></label>', RESADS_PLUGIN_URL . '/img/bottom-right.png', __('align right', RESADS_ADMIN_TEXTDOMAIN));
        print '</div>';
        
        print '<div class="admin-adspot-position-option">';
        printf('<input id="adspot_show_bottom_article_not" type="radio" name="adspot_show_bottom_article" value="0" %s />', $adspot_show_bottom_article_not);
        printf('<label for="adspot_show_bottom_article_not"><strong>%s</strong></label>', __('don\'t show', RESADS_ADMIN_TEXTDOMAIN));
        print '</div>';
        
        print '</div>';
        print '<div style="clear:both;"></div>';
        
        $adspot_show_top_inside_article_left = '';
        $adspot_show_top_inside_article_right = '';
        $adspot_show_top_inside_article_not = '';
        
        if(isset($_POST['adspot_show_top_inside_article']) && $_POST['adspot_show_top_inside_article'] == 3)
            $adspot_show_top_inside_article_right = 'checked="checked"';
        elseif(isset($value['args']['data']['adspot_show_top_inside_article']) && $value['args']['data']['adspot_show_top_inside_article'] == 3)
            $adspot_show_top_inside_article_right = 'checked="checked"';
        
        if(isset($_POST['adspot_show_top_inside_article']) && $_POST['adspot_show_top_inside_article'] == 1)
            $adspot_show_top_inside_article_left = 'checked="checked"';
        elseif(isset($value['args']['data']['adspot_show_top_inside_article']) && $value['args']['data']['adspot_show_top_inside_article'] == 1)
            $adspot_show_top_inside_article_left = 'checked="checked"';
        
        if(isset($_POST['adspot_show_top_inside_article']) && $_POST['adspot_show_top_inside_article'] == '0')
            $adspot_show_top_inside_article_not = 'checked="checked"';
        elseif(isset($value['args']['data']['adspot_show_top_inside_article']) && $value['args']['data']['adspot_show_top_inside_article'] == 0)
            $adspot_show_top_inside_article_not = 'checked="checked"';
        
        print '<div class="admin-adspot-position">';
        printf('<strong>%s</strong><br />', __('Show inside top of an article', RESADS_ADMIN_TEXTDOMAIN));
        
        print '<div class="admin-adspot-position-option">';
        printf('<input id="adspot_show_top_inside_article_left" type="radio" name="adspot_show_top_inside_article" value="1" %s />', $adspot_show_top_inside_article_left);
        printf('<label for="adspot_show_top_inside_article_left"><img src="%s"> <strong>%s</strong></label>', RESADS_PLUGIN_URL . '/img/align-left.png',__('align left', RESADS_ADMIN_TEXTDOMAIN));
        print '</div>';
        
        print '<div class="admin-adspot-position-option">';
        printf('<input id="adspot_show_top_inside_article_right" type="radio" name="adspot_show_top_inside_article" value="3" %s />', $adspot_show_top_inside_article_right);
        printf('<label for="adspot_show_top_inside_article_right"><img src="%s"> <strong>%s</strong></label>', RESADS_PLUGIN_URL . '/img/align-right.png',__('align right', RESADS_ADMIN_TEXTDOMAIN));
        print '</div>';
        
        print '<div class="admin-adspot-position-option">';
        printf('<input id="adspot_show_top_inside_article_not" type="radio" name="adspot_show_top_inside_article" value="0" %s />', $adspot_show_top_inside_article_not);
        printf('<label for="adspot_show_top_inside_article_not"><strong>%s</strong></label>', __('don\'t show', RESADS_ADMIN_TEXTDOMAIN));
        print '</div>';
        
        print '</div>';
        print '<div style="clear:both;"></div>';
        
        printf('<p>%s</p>', __('To show your adspot inside of an article, you can use shortcodes after created adspot.', RESADS_ADMIN_TEXTDOMAIN));
    }
    /**
     * Shortcode Meta Box
     * @param post $post
     * @param array $value
     */
    public function adspot_shortcode($post, $value)
    {
        printf('<label for="php-shortcode"><strong>%s</strong></label>', __('PHP Code', RESADS_ADMIN_TEXTDOMAIN));
        printf('<input id="php-shortcode" type="text" class="input-full-width" value="<?php if(function_exists(\'resads_adspot\')) print resads_adspot(%d); ?>" readonly="readonly" onclick="this.select();"/>', $value['args']['data']['adspot_id']);
        
        printf('<label for="article-shortcode"><strong>%s</strong></label>', __('Article Code', RESADS_ADMIN_TEXTDOMAIN));
        printf('<input id="article-shortcode" type="text" class="input-full-width" value=\'[resads_adspot id="%d"]\' readonly="readonly" onclick="this.select();" />', $value['args']['data']['adspot_id']);
    }
}
/**
 * AdSpot Widget class
 */
class ResAds_AdSpot_Widget extends WP_Widget 
{
    /**
     * constrct
     */
    public function __construct() 
    {
        parent::__construct('resads_adspot_widget', 'ResAds AdSpot', array('description' => __('Shows an AdSpot', RESADS_ADMIN_TEXTDOMAIN)));
    }
    /**
     * Backend Widget Form
     * @param array $instance
     */
    public function form($instance)
    {
        $defaults = array(
            'title' => '',
            'adspot' => '0'
        );
        
        $instance = wp_parse_args((array)$instance, $defaults);
        $title = $instance['title'];
        $adspot = $instance['adspot'];
        
        printf('<p><label for="%s">%s</label><input class="widefat" id="%s" name="%s" type="text" value="%s" /></p>', $this->get_field_id('title'), __('Titel', RESADS_ADMIN_TEXTDOMAIN), $this->get_field_id('title'), $this->get_field_name('title'), esc_attr($title));
        
        printf('<p><label for="%s">%s</label><select class="widefat" id="%s" name="%s">', $this->get_field_id('adspot'), __('AdSpot', RESADS_ADMIN_TEXTDOMAIN), $this->get_field_id('adspot'), $this->get_field_name('adspot'));
        
        if(file_exists(RESADS_CLASS_DIR . '/AdSpot.php'))
        {
            require_once RESADS_CLASS_DIR . '/AdSpot.php';
            $AdSpot_DB = new ResAds_AdSpot_DB();
            $adspots = $AdSpot_DB->get_all();
            if($adspots)
            {
                foreach($adspots as $cur_adspot)
                {
                    $selected = '';
                    if($cur_adspot['adspot_id'] == $adspot)
                        $selected = 'selected';
                    
                    printf('<option value="%d" %s>%s</option>', $cur_adspot['adspot_id'], $selected, $cur_adspot['adspot_name']);
                }
            }
        }
       
        printf('</select></p>');
    }
    /**
     * Update new Widget Settings
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        
        $instance['title'] = $new_instance['title'];
        $instance['adspot'] = $new_instance['adspot'];
        
        return $instance;
    }
    /**
     * Frontend Widget output
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $adspot = $instance['adspot'];
        
        print $before_widget;
        
        if(trim($title) != '')
        {
            print $before_title . $title . $after_title;
        }
        
        print resads_adspot($adspot);
        
        print $after_widget;
    }
}
?>