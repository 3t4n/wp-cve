<?php
class BookeroPanelPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private $pages;

    /**
     * Start up
     */
    public function __construct()
    {
        $this->pages['settings'] = new BookeroSettingsPage();
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        $this->options = get_option( 'bookero_options' );
        add_menu_page( 'Bookero', 'Rezerwacje', 'manage_options', 'bookero-panel', array($this, 'bookero_dashboard'), plugins_url('images/bookero-logo.png', getPluginDir().'images/'),'2.0.0');
        add_submenu_page( 'bookero-panel', 'Bookero - Kalendarz', 'Kalendarz', 'manage_options', 'bookero-panel', array($this, 'bookero_dashboard'));
        add_submenu_page( 'bookero-panel', 'Bookero - Ustawienia', 'Ustawienia', 'manage_options', 'bookero-settings', array($this->pages['settings'], 'create_admin_page'));
        add_submenu_page( 'bookero-panel', 'Bookero - Pomoc', 'Pomoc', 'manage_options', 'bookero-help', array($this, 'bookero_help'));
    }

    public function bookero_dashboard(){
        include_once getPluginDir().'views/dashboard.php';
    }

    public function bookero_help(){
        include_once getPluginDir().'views/help.php';
    }

}


