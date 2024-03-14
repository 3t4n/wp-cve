<?php
if(!defined('ABSPATH')) die('You are not allowed to call this page directly.');
/**
 * Dasboard
 */
class ResAds_Dashboard 
{
    /**
     * Hook
     * @var string
     */
    private $hook;
    /**
     * Construct
     */
    public function __construct() 
    {
        if(!current_user_can(RESADS_PERMISSION_ROLE))
            wp_die(__('You do not have permissions.', RESADS_ADMIN_TEXTDOMAIN));
        
        $this->hook = add_menu_page(__('ResAds', RESADS_ADMIN_TEXTDOMAIN), __('ResAds', RESADS_ADMIN_TEXTDOMAIN), RESADS_PERMISSION_ROLE, 'resads', array($this, 'page'), RESADS_PLUGIN_URL . '/img/admin-menu-icon.png');
        add_action("load-$this->hook", array($this, 'add_page_actions'), 9);
        add_action("load-$this->hook", array($this, 'add_scripts'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
    }
    /**
     * Render Page
     */
    public function page()
    {
        if(file_exists(RESADS_TEMPLATE_DIR . '/dashboard/dashboard.php'))
            require_once RESADS_TEMPLATE_DIR . '/dashboard/dashboard.php';
    }
    /**
     * Add Meta Boxes
     */
    public function add_meta_boxes()
    {
        $Meta_Box = new ResAds_Dashboard_Meta_Box();
        
        add_meta_box('resads-dashboard-ads', __('Ads', RESADS_ADMIN_TEXTDOMAIN), array($Meta_Box, 'ads'), $this->hook, 'normal', 'high');        
        add_meta_box('resads-dashboard-adspots', __('AdSpots', RESADS_ADMIN_TEXTDOMAIN), array($Meta_Box, 'adspots'), $this->hook, 'normal', 'high');       
        add_meta_box('resads-dashboard-news', __('News', RESADS_ADMIN_TEXTDOMAIN), array($Meta_Box, 'news'), $this->hook, 'side', 'high');
    }
    /**
     * Add Scripts
     */
    public function add_scripts()
    {
        wp_enqueue_media();
        
        wp_enqueue_script('dashboard'); 
        wp_enqueue_script('postbox'); 
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');          
        wp_enqueue_script('resads-admin', RESADS_JS_DIR . '/admin.min.js', array( 'jquery-ui-tabs', 'jquery'));
        wp_localize_script('resads-admin', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));

        wp_enqueue_script('chosen', RESADS_JS_DIR . '/chosen.jquery.min.js', array('jquery'));
        wp_enqueue_style('chosen', RESADS_CSS_DIR . '/chosen.min.css');
        
        wp_enqueue_style('resads-main', RESADS_CSS_DIR . '/main.min.css');    
    }
    /**
     * Add Page Actions
     */
    public function add_page_actions()
    {
        do_action('add_meta_boxes_'.$this->hook, null);
        do_action('add_meta_boxes', $this->hook, null);
        add_screen_option('layout_columns', array('max' => 2, 'default' => 2));
    }
}
/*
 * Dashboard Meta Boxes 
 */
class ResAds_Dashboard_Meta_Box
{
    /**
     * Ads Meta Box
     */
    public function ads()
    {
        $AdManagement_DB = new ResAds_AdManagement_DB();
        $ads = $AdManagement_DB->get_all('ad_id DESC', '5');
        
        print '<table class="widefat fixed">';
        if($ads && is_array($ads))
        {
            $i = 1;
            foreach($ads as $ad)
            {
                $td_class = '';
                if($i % 2 == 0)
                    $td_class = 'alternate';
                    
                print '<tr>';
                printf('<td class="%s"><a href="%s">%s</a></td>', $td_class, admin_url('admin.php?page=resads-admanagement&action=edit&ad=' . $ad['ad_id']), $ad['ad_name']);
                printf('<td class="%s"><a href="%s">%s</a></td>', $td_class, admin_url('admin.php?page=resads-admanagement&action=edit&ad=' . $ad['ad_id']), __('Edit', RESADS_ADMIN_TEXTDOMAIN));
                print '</tr>';
                
                $i++;
            }
        }
        else
        {
            print '<tr>';
            printf('<td>%s</td>', __('No Elements', RESADS_ADMIN_TEXTDOMAIN));
            print '</tr>';
        }
        print '</table>';
        
        printf('<a style="float:left; margin-top: 10px;" href="%s">%s</a>', admin_url('admin.php?page=resads-admanagement'), __('AdManagement', RESADS_ADMIN_TEXTDOMAIN));
        printf('<a style="float:right; margin-top: 10px;" href="%s">%s</a>', admin_url('admin.php?page=resads-admanagement&action=new'), __('Add New Ad', RESADS_ADMIN_TEXTDOMAIN));
        print '<div style="clear: both; "></div>';
    }
    /**
     * AdSpots Meta Box
     */
    public function adspots()
    {
        $AdSpot_DB = new ResAds_AdSpot_DB();
        $adspots = $AdSpot_DB->get_all('adspot_id DESC', '5');
        
        print '<table class="widefat fixed">';
        if($adspots && is_array($adspots))
        {
            $i = 1;
            foreach($adspots as $adspot)
            {
                $td_class = '';
                if($i % 2 == 0)
                    $td_class = 'alternate';
                    
                print '<tr>';
                printf('<td class="%s"><a href="%s">%s</a></td>', $td_class, admin_url('admin.php?page=resads-adspots&action=edit&adspot=' . $adspot['adspot_id']), $adspot['adspot_name']);
                printf('<td class="%s"><a href="%s">%s</a></td>', $td_class, admin_url('admin.php?page=resads-adspots&action=edit&adspot=' . $adspot['adspot_id']), __('Edit', RESADS_ADMIN_TEXTDOMAIN));
                print '</tr>';
                
                $i++;
            }
        }
        else
        {
            print '<tr>';
            printf('<td>%s</td>', __('No Elements', RESADS_ADMIN_TEXTDOMAIN));
            print '</tr>';
        }
        print '</table>';
        printf('<a style="float:left; margin-top: 10px;" href="%s">%s</a>', admin_url('admin.php?page=resads-adspots'), __('AdSpots', RESADS_ADMIN_TEXTDOMAIN));
        printf('<a style="float:right; margin-top: 10px;" href="%s">%s</a>', admin_url('admin.php?page=resads-adspots&action=new'), __('Add New AdSpot', RESADS_ADMIN_TEXTDOMAIN));
        print '<div style="clear: both; "></div>';
    }
    /**
     * News Meta Box
     */
    public function news()
    {
        require_once ABSPATH . WPINC . '/feed.php';
        
        $rss = fetch_feed('http://www.resads.de/feed/');
        $maxitems = 0;
        
        if(!is_wp_error($rss))
        {
            $maxitems = $rss->get_item_quantity(5);
            $rss_items = $rss->get_items(0, $maxitems);
        }
        
        print '<ul>';
        
        if($maxitems == 0)
        {
            printf('<li>%s</li>', __('No items', RESADS_ADMIN_TEXTDOMAIN));
        }
        else
        {
            foreach($rss_items as $item)
            {
                printf('<li><a href="%s" target="_blank">%s</a></li>', esc_url($item->get_permalink()), esc_html($item->get_title()));
            }
        }
        
        print '</ul>';
    }
}
?>