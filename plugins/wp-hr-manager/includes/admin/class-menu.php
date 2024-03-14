<?php
namespace WPHR\HR_MANAGER\Admin;
use WPHR\HR_MANAGER\Framework\Traits\Hooker;

/**
 * Administration Menu Class
 *
 * @package payroll
 */
class Admin_Menu {

    use Hooker;

    /**
     * Kick-in the class
     */
    public function __construct() {
        $this->action( 'admin_menu', 'admin_menu', 99 );
        $this->action( 'admin_menu', 'hide_admin_menus', 100 );
        $this->action( 'wp_before_admin_bar_render', 'hide_admin_bar_links', 100 );
    }

    /**
     * Get the admin menu position
     *
     * @return int the position of the menu
     */
    public function get_menu_position() {
        return apply_filters( 'payroll_menu_position', 9999 );
    }

    /**
     * Add menu items
     *
     * @return void
     */
    public function admin_menu() {
        add_menu_page( __( 'wphr', 'wphr' ), __( 'WPHR Settings', 'wphr' ), 'manage_options', 'wphr-support', array( $this, 'support_page' ), 'dashicons-hr-settings-icon', 70);
        add_submenu_page( 'wphr-support', __( 'Support', 'wphr' ), __( 'Support', 'wphr' ), 'manage_options', 'wphr-support', array( $this, 'support_page' ));
        add_submenu_page( 'wphr-support', __( 'Company', 'wphr' ), __( 'Company', 'wphr' ), 'manage_options', 'wphr-company', array( $this, 'company_page' ) );
        add_submenu_page( 'wphr-support', __( 'Tools', 'wphr' ), __( 'Tools', 'wphr' ), 'manage_options', 'wphr-tools', array( $this, 'tools_page' ) );
        add_submenu_page( 'wphr-support', __( 'Audit Log', 'wphr' ), __( 'Audit Log', 'wphr' ), 'manage_options', 'wphr-audit-log', array( $this, 'log_page' ) );
        add_submenu_page( 'wphr-support', __( 'Settings', 'wphr' ), __( 'Settings', 'wphr' ), 'manage_options', 'wphr-settings', array( $this, 'settings_page' ) );
        add_submenu_page( 'wphr-support', __( 'Modules', 'wphr' ), __( 'Modules', 'wphr' ), 'manage_options', 'wphr-modules', array( $this, 'module' ) );
    }

    /**
     * wphr Settings page
     *
     * @return void
     */
    function settings_page() {
        new \WPHR\HR_MANAGER\Settings();
    }

    /**
     * wphr module
     *
     * @return void
     */
    function module() {
        new \WPHR\HR_MANAGER\Admin\Admin_Module();
    }

    /**
     * Hide default WordPress menu's
     *
     * @return void
     */
    function hide_admin_menus() {
        global $menu;

        $menus = get_option( '_wphr_admin_menu', array() );
        
        if ( ! $menus ) {
            return;
        }
        
        foreach ($menus as $item) {
            remove_menu_page( $item );
        }

        remove_menu_page( 'edit-tags.php?taxonomy=link_category' );
        remove_menu_page( 'separator1' );
        remove_menu_page( 'separator2' );
        remove_menu_page( 'separator-last' );

        $position = 9998;
        $menu[$position] = array(
            0   =>  '',
            1   =>  'read',
            2   =>  'separator' . $position,
            3   =>  '',
            4   =>  'wp-menu-separator'
        );
    }

    /**
     * Hide default admin bar links
     *
     * @return void
     */
    function hide_admin_bar_links() {
        global $wp_admin_bar;

        $adminbar_menus = get_option( '_wphr_adminbar_menu', array() );
        if ( ! $adminbar_menus ) {
            return;
        }

        foreach ($adminbar_menus as $item) {
            $wp_admin_bar->remove_menu( $item );
        }
    }
    
    /**
     * Handles the support page
     *
     * @return void
     */
    public function support_page()
    {
		include_once dirname( __FILE__ ) . '/views/support.php';
	}

    /**
     * Handles the company page
     *
     * @return void
     */
    public function company_page() {
        $action = isset( $_GET['action'] ) ? sanitize_text_field($_GET['action']) : 'list';

        switch ($action) {
            case 'edit':
                $company    = new \WPHR\HR_MANAGER\Company();
                $template = WPHR_VIEWS . '/company-editor.php';
                break;

            default:
                $template = WPHR_VIEWS . '/company.php';
                break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }

    /**
     * Handles the company locations page
     *
     * @return void
     */
    public function locations_page() {
        include_once dirname( __FILE__ ) . '/views/locations.php';
    }

    /**
     * Handles the tools page
     *
     * @return void
     */
    public function tools_page() {
        include_once dirname( __FILE__ ) . '/views/tools.php';
    }

    /**
     * Handles the log page
     *
     * @return void
     */
    public function log_page() {
        include_once dirname( __FILE__ ) . '/views/log.php';
    }

}

return new Admin_Menu();
