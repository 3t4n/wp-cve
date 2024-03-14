<?php
/**
 * Bootstrap Blocks for WP Editor settings page class
 *
 * Displays the settings page.
 * 
 * Copyright (c) Freeamigos
 *
 * @package Bootstrap Blocks for WP Editor
 * @author Virgial Berveling
 *
 * version: 1.1.1
 * added site network
 */


class GtbBootstrapSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    const MENU_SLUG = GUTENBERGBOOTSTRAP_SLUG .'-settings';
    var $tabs = array();
    private static $sections = array();


    /**
     * Start up
     */
    public function __construct()
    {
        GutenbergBootstrap::update_check(GUTENBERGBOOTSTRAP_VERSION);
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        if(current_user_can('manage_network_options')) add_action( 'network_admin_menu', array( $this, 'admin_menu' ) );
        add_filter( 'plugin_action_links_' . GUTENBERGBOOTSTRAP_PLUGIN_BASENAME, array($this,'add_action_links') );
    }

    /**
     * Add options page
     */
    public function admin_menu()
    {

        $capability = apply_filters('gtb_capability','manage_options');
        add_menu_page( 
            'Bootstrap Blocks options',
            'Bootstrap Blocks',
            $capability,
            self::MENU_SLUG,
            array($this,'print_admin_page'),
            plugins_url( '/modules/settings-page/assets/logo-wp-editor-bootstrap-blocks.svg',GUTENBERGBOOTSTRAP_PLUGIN_BASENAME ),
            50  );


        if (!is_network_admin()):
            $this->tabs[10] = array('title'=>__('Settings',GUTENBERGBOOTSTRAP_SLUG),'slug'=>'settings','view_file'=>'settings.php','icon'=>'dashicons-admin-settings');
            $this->tabs[30] = array('title'=>__('Design Package',GUTENBERGBOOTSTRAP_SLUG),'slug'=>'design','view_file'=>'designpackage.php','icon'=>'dashicons-admin-plugins');
            $this->tabs[90] = array('title'=>__('Extra',GUTENBERGBOOTSTRAP_SLUG),'slug'=>'extra','view_file'=>'extra.php','icon'=>'dashicons-editor-help');    

            foreach($this->tabs as $tab):
                add_submenu_page( 
                    self::MENU_SLUG, 
                    $tab['title'], 
                    $tab['title'], 
                    $capability, 
                    self::MENU_SLUG.'&section=settings#'.$tab['slug'], 
                    array($this,'print_admin_page')
                );
            endforeach;
        endif; 
        do_action('gtb_bootstrap_register_submenu');

        add_action( 'admin_init', array( $this, 'load_sections' ) );

    }


    public function enqueue_scripts($hook)
    {

        // Add the color picker css file
        wp_enqueue_style( 'wp-color-picker' ); 
        wp_enqueue_script( 'wp-color-picker');

        wp_enqueue_style( 
            'wp-editor-bootstrap-blocks-settings-page', 
            plugins_url( '/modules/settings-page/dist/settings.css', GUTENBERGBOOTSTRAP_PLUGIN_BASENAME),
            null, 
            time()
        );

        wp_enqueue_script(
            'wp-editor-bootstrap-blocks-settings-page', // Handle.
            plugins_url( '/modules/settings-page/dist/settings.js',GUTENBERGBOOTSTRAP_PLUGIN_BASENAME),
            array( 'jquery','wp-color-picker' ), // Dependencies, defined above.
            time(), // Version: File modification time.
            true // Enqueue the script in the footer.
        );
    }



    private function save_settings($post)
    {
        if ( ! isset( $post['wpnonce_gtb'] ) 
            || ! wp_verify_nonce( $post['wpnonce_gtb'], 'update_gtb' ) 
        ) {
            print 'Sorry, your nonce did not verify.';
            exit;
        }
        if (!empty($post['action']) && $post['action'] == 'update' && !empty($post['gtbbootstrap_options']))
        {
            $gtb_options = $this->sanitize($post['gtbbootstrap_options']);
            GutenbergBootstrap::update_option('gtbbootstrap_options',$gtb_options);
            
            fa_add_notice(__( 'settings updated', GUTENBERGBOOTSTRAP_SLUG ), 'success',true);
        }
    }

    static function IsGTBSettingsPage()
    {
        global $pagenow;
        return ( $pagenow == 'admin.php' && isset($_GET['page']) && $_GET['page'] == self::MENU_SLUG );
    }


    public function load_sections()
    {
        global $pagenow;
        if ( !self::IsGTBSettingsPage() ) return;

        if (isset($_POST['option_page']) && $_POST['option_page'] == 'gtbbootstrap_option_group')
        {
            $this->save_settings($_POST);
        }

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts') ); 
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        if (empty($input)) $input = array();

        $input = apply_filters('gtb_bootstrap_sanitize_settings_page',$input);

        $new_input = GutenbergBootstrap::get_option('gtbbootstrap_options');
        // unset boolean;
        unset($new_input['bootstrap_included']);
        unset($new_input['bootstrap_colors_included']);
        unset($new_input['bootstrap_on_template']);

        foreach( $input as $key=>$val )
        {
            switch($key){
                case 'bootstrap_version':
                $new_input[$key] = $val;
                break;
                case 'bootstrap_included':
                $new_input[$key] = $val == 'N'?'N':'Y';
                break;
                case 'bootstrap_colors_included':
                $new_input[$key] = $val == '1'?'1':'';
                break;
                case 'bootstrap_on_template':
                $new_input[$key] = $val == '1'?'1':'';
                break;
                case 'gridsize':
                    if (defined('GTBBOOTSTRAP_DESIGN_LC')):
                        $new_input[$key] = intval($val);
                        if ($new_input[$key] > 14) $new_input[$key] = 14;
                        if ($new_input[$key] < 2) $new_input[$key] = 2;
                    endif;
                break;
                case 'bootstrap_color1':
                case 'bootstrap_color2':
                case 'bootstrap_color3':
                case 'bootstrap_color4':
                case 'bootstrap_color5':
                case 'bootstrap_color6':
                case 'bootstrap_color7':
                case 'bootstrap_color8':
                    if (preg_match( '/^#[a-f0-9]{6}$/i', $val) ) $new_input[$key] = $val;
                break;
            }

        }
        return $new_input;
    }


    private function reset_settings($gtb_options) {

        if ( ! isset( $_GET['gtb_reset'] ) || ! is_string( $_GET['gtb_reset'] ) || '1' !== $_GET['gtb_reset'] || ! isset( $_GET['gtb_nonce'] ) ) :
            return $gtb_options;
        endif;

        if ( ! wp_verify_nonce( $_GET['gtb_nonce'], 'gtb_reset_n' ) ) :
            wp_nonce_ays( '' );
            die();
        endif;

        $gtb_options = GutenbergBootstrap::$default_options;
        GutenbergBootstrap::update_option('gtbbootstrap_options', $gtb_options );
        fa_add_notice(__( 'settings resetted', GUTENBERGBOOTSTRAP_SLUG ), 'success',true);

        return $gtb_options;
    }



    public function print_admin_page()
    {
        global $gtb_options;

        if (isset($_GET['gtb_reset'])):
            $gtb_options = $this->reset_settings($gtb_options);
        else:
            $gtb_options = GutenbergBootstrap::get_option( 'gtbbootstrap_options' );
        endif;

        foreach($this->tabs as $key=>$tab):
            self::register_section($tab,$key);
        endforeach;

        do_action('gtb_bootstrap_register_settings_section');
        include_once dirname(__FILE__).'/views/container.php';
    }

	static function register_section( $section, $ord = 99 ) {
        while ( isset( self::$sections[ $ord ] ) ) {
			$ord++;
		}


		self::$sections[ $ord ] = $section;
	}

	static function print_sections( $output = true ) {
		ksort( self::$sections );
        $printed = array();
		foreach ( self::$sections as $section ) {
            if(in_array($section['slug'],$printed)) continue;
            $printed[]=$section['slug'];

			$icon = ! empty( $section['icon'] ) ? 'data-icon="' . $section['icon'] . '"' : '';
			$slug = ! isset( $section['slug'] ) || in_array( $section['slug'], array( 'design', 'settings', 'extra' ), true ) ? 'settings-page' : $section['slug'];
			echo PHP_EOL;
			echo '<div class="sections-container" id="sections-' . $section['slug'] . '" title="' . esc_attr( ucfirst( __( $section['title'] ) ) ) . '" ' . $icon . '>';
			if ( ! empty( $section['view_file'] ) ) :
				include_once GUTENBERGBOOTSTRAP_PLUGIN_DIR . '/modules/' . basename( $slug ) . '/views/' . basename( $section['view_file'] );
			endif;
			if ( ! empty( $section['html'] ) ) {
				echo  $section['html'] ;
			}
			echo '</div>';
			echo PHP_EOL;
		}
	}



    function add_action_links ( $links ) {
        $mylinks = array(
        '<a href="' . admin_url( 'admin.php?page='.self::MENU_SLUG ) . '">'.__('Settings').'</a>',
        );

        if(current_user_can('manage_network_options')){
            $mylinks = array(
            '<a href="' . admin_url( 'network/admin.php?page='.self::MENU_SLUG ) . '">'.__('Settings').'</a>',
            );
        }

        return array_merge( $links, $mylinks );
    }

}