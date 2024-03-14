<?php
if(!defined('ABSPATH'))  die('You are not allowed to call this page directly.');
class ResAds_Settings 
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
        $this->hook = add_submenu_page('resads', __('Settings', RESADS_ADMIN_TEXTDOMAIN), __('Settings', RESADS_ADMIN_TEXTDOMAIN), RESADS_PERMISSION_ROLE, 'resads-settings', array($this, 'page'));
        add_action("load-$this->hook", array($this, 'add_page_actions'), 9);
        add_action("load-$this->hook", array($this, 'add_scripts'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
    }
    /**
     * Render page
     */
    public function page()
    {
        if(file_exists(RESADS_TEMPLATE_DIR . '/settings/settings.php'))
            require RESADS_TEMPLATE_DIR . '/settings/settings.php';       
    }
    /**
     * Add Scripts
     */
    public function add_scripts()
    {
        wp_enqueue_script('dashboard'); 
        wp_enqueue_script('postbox'); 
        wp_enqueue_script('jquery');        

        wp_enqueue_style('resads-main', RESADS_CSS_DIR . '/main.min.css');
    }
    /**
     * Add Page Actions
     */
    public function add_page_actions()
    {
        do_action('add_meta_boxes_'.$this->hook, null);
        do_action('add_meta_boxes', $this->hook, null);
        add_screen_option('layout_columns', array('max' => 1, 'default' => 1));
    }
    /** 
     * Add Meta Boxes
     */
    public function add_meta_boxes()
    {
        $Meta_Box = new ResAds_Settings_Meta_Box();
   
        add_meta_box('resads-settings-resolution', __('Resolutions', RESADS_ADMIN_TEXTDOMAIN), array($Meta_Box, 'resolutions'), $this->hook, 'normal', 'high');
    }
}
/**
 * Settings Meta Boxes
 */
class ResAds_Settings_Meta_Box
{
    public function resolutions()
    {
        require_once RESADS_CLASS_DIR . '/Resolution.php';
        $Resolution_DB = new ResAds_Resolution_DB();
        $resolutions = $Resolution_DB->get_all();
        
        print '<table class="widefat fixed">';
        print '<thead>';
        print '<tr>';
        printf('<th><strong>%s</strong></th>', __('Size', RESADS_ADMIN_TEXTDOMAIN));
        printf('<th><strong>%s</strong></th>', __('Mobil', RESADS_ADMIN_TEXTDOMAIN));
        printf('<th><strong>%s</strong></th>', __('Tablet', RESADS_ADMIN_TEXTDOMAIN));
        printf('<th><strong>%s</strong></th>', __('Desktop', RESADS_ADMIN_TEXTDOMAIN));
        print '</thead>';
        print '</tr>';
        
        if($resolutions && is_array($resolutions))
        {
            $i = 1;
            foreach($resolutions as $resolution)
            {
                if($i % 2 == 0)
                    print '<tr class="alternate">';
                else
                    print '<tr>';
                
                printf('<td>%dx%d</td>', $resolution['res_banner_width'], $resolution['res_banner_height']);
                
                $mobil_checked = '';
                if(isset($resolution['res_smartphone']) && $resolution['res_smartphone'] == 1)
                    $mobil_checked = 'checked';
                
                printf('<td><input type="checkbox" disabled name="mobil[]" %s /></td>', $mobil_checked);
                
                $tablet_checked = '';
                if(isset($resolution['res_tablet']) && $resolution['res_tablet'] == 1)
                    $tablet_checked = 'checked';
                
                printf('<td><input type="checkbox" disabled name="tablet[]" %s /></td>', $tablet_checked);
                
                $desktop_checked = '';
                if(isset($resolution['res_desktop']) && $resolution['res_desktop'] == 1)
                    $desktop_checked = 'checked';
                
                printf('<td><input type="checkbox" disabled name="desktop[]" %s /></td>', $desktop_checked);
                
                print '</tr>';
                
                $i++;
            }
        }
        
        print '</table>';
    }
}
?>