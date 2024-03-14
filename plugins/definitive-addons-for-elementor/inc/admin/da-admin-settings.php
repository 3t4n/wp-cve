<?php
/**
 * Definitive Addons Dashboard
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://wordpress.org/support/article/administration-screens/
 */
namespace Definitive_Addons_Elementor\Elements;

use Definitive_Addons_Elementor\Elements\Definitive_Addon_Elements;


// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit();
}

/**
 * Definitive Addons Dashboard
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://wordpress.org/support/article/administration-screens/
 */
class Definitive_Addons_Dashboard
{

    
    private $_all_elements;
    private $_admin_save_settings;
    private $_saved_elements;

    /**
     * Constructor
     *
     * @since 1.5.0
     *
     * @access public
     */
    public function __construct()
    {
        $this->_admin_save_settings = [];
        
        add_action('admin_menu', [$this, 'definitive_addons_admin_menu'],  '', 12);
        
        add_action('admin_enqueue_scripts', [$this, 'definitive_addons_css_admin_scripts'], 88);
        add_action('admin_enqueue_scripts', [$this, 'definitive_addons_js_admin_scripts'], 88);
        add_action('admin_head', [$this, 'dafe_admin_menu_logo_script']);
        

        add_action('wp_ajax_dafe_dashboard_save_elements', [$this, 'dafe_dashboard_save_elements']);
        add_action('wp_ajax_nopriv_dafe_dashboard_save_elements', [$this, 'dafe_dashboard_save_elements']);

        
    
        self::dafe_admin_include_files();
        
    }

    /**
     * Two admin files include to dashboard settings
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return void.
     */
    public static function dafe_admin_include_files()
    {
        include_once __DIR__ . '/dafe-elements/Da_all_elements.php';
        include_once __DIR__ . '/admin_sections.php';
    
        
    }

    
 
    /**
     * Admin Menu
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return void.
     */
    public function definitive_addons_admin_menu()
    {

        $dafe_logo_image = DAFE_URI . '/inc/admin/assets/img/icon-256x256.png';
        
        
        add_menu_page(
            __('Definitive Addons', 'definitive-addons-for-elementor'), // Page Title
            __('Definitive Addons', 'definitive-addons-for-elementor'),    // Menu Title
            'manage_options',
            'definitive-addons-settings',
            [$this, 'dafe_dashboard_settings_content'],
            $dafe_logo_image,
            55
        );
    }
    
    /**
     * Enqueue script
     *
     * @param string $hook enqueue and localized script
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return void.
     */
    public function definitive_addons_css_admin_scripts($hook)
    {
        $admin_screen = get_current_screen();
    
        if (is_object($admin_screen)) {
            if ($admin_screen->id == 'toplevel_page_definitive-addons-settings' || $admin_screen->id == 'toplevel_page_definitive-addons-settings-network') {
        
                wp_enqueue_style('definitive-addons-dashboard', DAFE_URI . '/inc/admin/assets/css/definitive-addons-admin.css');
                
                
            }
        }

        
    }
    
    /**
     * Enqueue script
     *
     * @param string $hook enqueue and localized script
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return void.
     */         
    public function definitive_addons_js_admin_scripts($hook)
    {
        $admin_screen = get_current_screen();

        if (is_object($admin_screen)) {
			
            if ($admin_screen->id == 'toplevel_page_definitive-addons-settings' || $admin_screen->id == 'toplevel_page_definitive-addons-settings-network') {
        
                wp_enqueue_script('definitive-addons-tabs', DAFE_URI . '/inc/admin/assets/js/admin-tabs.js', ['jquery'], '', true);
    
                wp_enqueue_script('definitive-addons-dashboard', DAFE_URI . '/inc/admin/assets/js/definitive-addons-admin.js', ['jquery'], '', true);

        
                wp_localize_script(
                    'definitive-addons-dashboard', 'dafe_admin_settings_js', array( 
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'security' => wp_create_nonce('dafe_dashboard_settings_nonce'),
                    'home_url' => home_url(),
                    ) 
                );
        
        
            }
        }

        
    }
    /**
     * Admin logo
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return void.
     */    
    public function dafe_admin_menu_logo_script()
    {
        ?>

            <style>

                #adminmenu .wp-menu-image img {
                    width: 18px;
                }
            </style>
        <?php 
    }
    
    /**
     * Get file name of all elements
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return array.
     */    
    public static function dafe_get_elements_file_name()
    {
        
        $elements_file_name = [];
        $elements = Definitive_Addon_Elements::definitive_addons();

        foreach ($elements['elements'] as  $element) {
            $elements_file_name[] = $element['file_name'];
        }

        return $elements_file_name;
    }

    /**
     * Get file name of all elements
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return array.
     */    
    public function setAllElements()
    {
        
        $this->_all_elements = array_fill_keys(self::dafe_get_elements_file_name(), true);
        
    }
    
    /**
     * Get saved elements
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return void.
     */
    public function getSavedElements()
    {
        
        $this->_all_elements = array_fill_keys(self::dafe_get_elements_file_name(), true);
        $this->_saved_elements  = get_option('dafe_admin_save_settings', $this->_all_elements);
        
    }

    /**
     * Calling SavedElements and tab_content to dashboard settings
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return void.
     */
    public function dafe_dashboard_settings_content()
    {

        $this->getSavedElements();
        $this->dafe_admin_tab_contents();
    }
    
    /**
     * Tab contents to dashboard settings
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return void.
     */
    public function dafe_admin_tab_contents()
    {
        ?>
    <div class="dafe-definitive-addons-admin">
    
        <?php 
        $this->dafe_get_admin_header();

        Definitive_Addons_Admin_Section::dafe_get_admin_nav();
        ?>

        <div class="definitive-admin-tab-contents">
        <?php
            
        Definitive_Addons_Admin_Section::dafe_get_support();
            
        $this->dafe_get_show_hide_elements();

        ?>
        </div>

    
</div>
        <?php
    }

    /**
     * Print dashboard settings header
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return void.
     */
    public function dafe_get_admin_header()
    {
        ?>
<div class="dafe-definitive-admin-header">
        <?php $logo_path = DAFE_URI . '/inc/admin/assets/img/icon-256x256.png'; ?>
            <a class="dafe-definitive-admin-logo" href="https://wordpress.org/plugins/definitive-addons-for-elementor" target="_blank">
                <img src="<?php echo esc_url($logo_path); ?>" />
            </a>

            <div class="dafe-definitive-admin-title">
        <?php 
        $title = __('Welcome to Definitive Addons for Elementor', 'definitive-addons-for-elementor');
                
        $description = __('Thank you for choosing Definitive Addons', 'definitive-addons-for-elementor');
        $txt = '<h3 class="admin-title">'.esc_html($title).'</h3>';
        $txt .= '<h5 class="admin-txt">'.esc_html($description).'</h5>';                
         printf('%s v %s', $txt, DAFE_CURRENT_VERSION);
                
        ?>
            </div>

            
</div>
        <?php
    }

    /**
     * Get saved elements
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return bool.
     */
public function dafe_dashboard_save_elements()
{
   
	// Verify the nonce before proceeding.
    $nonce = isset($_POST['security']) ? sanitize_key($_POST['security']) : '';
    if (!wp_verify_nonce($nonce, 'dafe_dashboard_settings_nonce')) {
        die(__('Invalid nonce','definitive-addons-for-elementor'));
    }

    // Check if fields are present and sanitize
	$settings = array();
    $setting = isset($_POST['fields']) ? sanitize_text_field(wp_unslash($_POST['fields'])) : '';
    $settings = wp_parse_args($setting,$settings);
	

    // Stop WP from clearing custom fields on autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    foreach (self::dafe_get_elements_file_name() as $file_name) {
        if (isset($settings[$file_name])) {
            $this->_admin_save_settings[$file_name] = 1;
        } else {
            $this->_admin_save_settings[$file_name] = 0;
        }
    }

    update_option('dafe_admin_save_settings', $this->_admin_save_settings);

    return true;
    die();
}


    
    /**
     * Display saved all elements with switcher
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return void.
     */
    public function dafe_get_show_hide_elements()
    {
        ?>    
    <div class="dafe-admin-tab-bar" id="dafe-definitive-addons-addons" style="display: none;">
    

        <div class="dafe-tab-dashboard-wrapper">
            <form action="" method="POST" id="dafe-addons-tab-settings" class="dafe-addons-tab-settings" name="dafe-addons-tab-settings">
                
        <?php wp_nonce_field('dafe_dashboard_settings_nonce'); ?>
               
               <div class="dafe-addons-dashboard-tabs-wrapper">
                
                <h3 class="dafe-elements-heading">
        <?php echo __('Definitive All Elements', 'definitive-addons-for-elementor'); ?>
                </h3>

<div class="dafe-definitive-admin-section-item">

        <?php $this->dafe_definitive_admin_buttons(); ?>

    
    <div class="dafe-definitive-admin-element-container">
        <?php 
        $elements = Definitive_Addon_Elements::definitive_addons();
        

        foreach ($elements['elements'] as  $element) : ?>
        

            <div class="dafe-definitive-dashboard-element-item">
    
                        <div class="dafe-definitive-dashboard-element-title">
            <?php echo esc_html($element['title']); ?>
                        </div> 

                    <div class="switcher-container">
                        
                        <label>

                    <input type="checkbox" id="<?php echo esc_attr($element['file_name']); ?>" class="switcher" name="<?php echo esc_attr($element['file_name']); ?>" <?php checked(1, $this->_saved_elements[$element['file_name']], true); ?>>

                     <div class="siblings">
                        <div class="childred"></div>
                    </div>
                            
                        </label>
                    
                        
                    </div>
                
            </div>
        <?php endforeach; ?>
    </div>

</div>
                        
                </div> 
            </form>
        </div>
    
</div>

        <?php
    }

    /**
     * Display saved all elements with switcher
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return void.
     */
    public function dafe_definitive_admin_buttons()
    {
        ?>
        <div class="dafe-definitive-dashboard-selection-header">
        
            <button class="dafe-enable-all-element">
        <?php echo __('Enable All', 'definitive-addons-for-elementor'); ?>
            </button>
            <button class="dafe-disable-all-element">
        <?php echo __('Disable All', 'definitive-addons-for-elementor'); ?>
            </button>

            
            <div class="dafe-admin-element-saving-container save-btn">
                <button type="submit" class="dafe-admin-element-state-saving dafe-button">
         <?php echo __('Save Elements', 'definitive-addons-for-elementor'); ?>
                </button>
                <span class="da-element-saved"></span>
            </div>
            

    </div><!--  -->
        <?php
    }

}
$da_board = new Definitive_Addons_Dashboard();
