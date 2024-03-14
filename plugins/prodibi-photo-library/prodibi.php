<?php
/*
Plugin Name: Prodibi Photo Library
Description: Get the highest resolution images with the smoothest zoom, the fastest speed and the best quality. Responsive gallery and zoomable images.
Author: Prodibi SA
Version: 2.0.2
Author URI: https://www.prodibi.com/
*/

Class Prodibi_manager
{

    public $version = '2.0.2';
    public $valid = false;
    public $prodibi_settings;

    function __construct()
    {
        $this->init();
    }

    function prodibi_root_url() 
    {
        return 'https://max1.prodibicdn.com/libraries/';
    }

    function prodibi_get_account_settings()
    {

        $this->prodibi_settings();

        if (!$this->prodibi_settings) {

            $prodibi_data = get_option('prodibi_account_settings');
            if ($prodibi_data) {
                $decodedObject = json_decode($prodibi_data);

                $prodibi_account = $decodedObject->{'account'};
                $prodibi_user_name = $decodedObject->{'username'};
                $prodibi_api_key = $decodedObject->{'apiKey'};
                
                $this->prodibi_settings = array(
                    'account' => $prodibi_account,
                    'username' => $prodibi_user_name,
                    'apiKey' => str_rot13 ($prodibi_api_key),
                    'version' => $this->version,
                    'environment' => 'wordpress'
                );
				$this->valid = $prodibi_account != '' && $prodibi_api_key != '';
            } else {
                $this->prodibi_settings = array(
                    'account' => '',
                    'username' => '',
                    'apiKey' => '',
                    'version' => $this->version,
                    'environment' => 'wordpress'
                );
                $this->valid = false;
            }
        } else {
			$this->valid = $this->prodibi_settings['account'] != '' && $this->prodibi_settings['apiKey'] != '';
        }
        return $this->prodibi_settings;
    }


    function prodibi_sanitize($text)
    {
        return stripslashes(stripslashes(trim($text)));
    }

    function prodibi_client_scripts() {
        $prodibi_url = $this->prodibi_root_url();

        wp_register_script('prodibi-script', $prodibi_url . 'pages/prodibi.embed.2.0.min.js', array(), $this->version, TRUE);
        wp_register_style('prodibi-image-block-styles', $prodibi_url . 'wordpress/prodibi-block.css', $this->version);

        $prodibi_settings = $this->prodibi_get_account_settings();
        $account = $this->prodibi_settings['account'];

        wp_add_inline_script( 'prodibi-script', 'window.prodibiAsync=window.prodibiAsync||[];prodibiAsync.push({type:"settings",settings:{account:"' . $account . '"}});', 'before' );
    }

	function prodibi_admin_scripts()
    {

        $prodibi_settings = $this->prodibi_get_account_settings();
        $prodibi_url = $this->prodibi_root_url();

        wp_register_script('prodibi-admin', $prodibi_url . 'wordpress/prodibi-login.js', array(), $this->version, TRUE);

        wp_register_style('prodibi-image-editor-block-styles', $prodibi_url . 'wordpress/prodibi-editor-block.css', $this->version);
        

        wp_register_script('prodibi-image-block', $prodibi_url . 'wordpress/prodibi-block.js', array( 'wp-blocks', 'wp-element', 'prodibi-script' ), $this->version, TRUE);

        

        wp_localize_script('prodibi-image-block', 'prodibiWpSettings', $prodibi_settings);
        wp_localize_script('prodibi-admin', 'prodibiWpSettings', $prodibi_settings);


    }


    function prodibi_add_defer_attribute($tag, $handle) {
	    if ( 'prodibi-script' === $handle || 'prodibi-script-loader' === $handle ) {
	        return str_replace( ' src', ' async defer src', $tag );    
	    } else {
	        return $tag;    
	    }
    }



    function prodibi_register_block() {
        if (!function_exists('register_block_type')) {
		    return;
	    }
        register_block_type( 'prodibi/image', array('editor_script' => 'prodibi-image-block', 'script' => 'prodibi-script', 'editor_style' => 'prodibi-image-editor-block-styles', 'style' => 'prodibi-image-block-styles') );
        register_block_type( 'prodibi/gallery', array('editor_script' => 'prodibi-image-block', 'script' => 'prodibi-script', 'editor_style' => 'prodibi-image-editor-block-styles', 'style' => 'prodibi-image-block-styles') );
    }

    public function init()
    {
        add_action('init', array($this, 'prodibi_register_block'));
        add_filter('script_loader_tag', array($this, 'prodibi_add_defer_attribute'), 10, 2);
        $this->prodibi_client_scripts();
        add_action('wp_enqueue_scripts', array($this, 'prodibi_client_scripts') );

        if (is_admin()) {

            register_deactivation_hook(__FILE__, array($this, 'prodibi_deactivate'));
			
			$this->prodibi_admin_scripts();
            $this->prodibi_get_account_settings();
            
            add_action('admin_enqueue_scripts', array($this, 'prodibi_admin_scripts'));
            add_action('admin_menu', array($this, 'prodibi_add_menu'));
			
        } else {
			
            add_shortcode('Prodibi', array($this, 'prodibi_shortcode'));
			
        }

        
    }

   

    /**************************************************************   ADMIN  ********************************************************************************/

    function prodibi_add_menu()
    {
        $prodibi_url = $this->prodibi_root_url();
        $this->prodibi_get_account_settings();
        add_menu_page('Prodibi', 'Prodibi', 'manage_options', 'prodibi_admin', array($this, 'prodibi_media_library'), $prodibi_url . 'wordpress/images/prodibi_logo_16x16.png');
    }

    function prodibi_media_library()
    {
         add_filter('script_loader_tag', array($this, 'prodibi_add_defer_attribute'), 10, 2);
         wp_enqueue_script('prodibi-admin');
         echo '<div id="prodibi-root"></div>';
    }

    function prodibi_deactivate()
    {
        delete_option('prodibi_account_settings');
    }

    function prodibi_settings()
    {

        if (isset($_POST["prodibi_account"])) {

            $obj = get_option('prodibi_account_settings');

            $arr = array(
                'account' => $this->prodibi_sanitize($_POST["prodibi_account"]),
                'username' => $this->prodibi_sanitize($_POST["prodibi_username"]),
                'apiKey' => $this->prodibi_sanitize($_POST["prodibi_api_key"])
            );
            $txt = json_encode($arr);

            if (!$obj) {
                add_option('prodibi_account_settings', $txt);
            } else {
                update_option('prodibi_account_settings', $txt);
            }

            $this->prodibi_settings = array(
                'account' => $this->prodibi_sanitize($_POST["prodibi_account"]),
                'username' => $this->prodibi_sanitize($_POST["prodibi_username"]),
                'apiKey' => $this->prodibi_sanitize($_POST["prodibi_api_key"]),
                'version' => $this->version,
                'environment' => 'wordpress'
            );
            $this->valid = true;
        }
    }

  
    /**************************************************************   View  ********************************************************************************/

    function prodibi_shortcode($atts)
    {

        wp_enqueue_script('prodibi-script');
	
		$prodibi_settings = $this->prodibi_get_account_settings();
		
		$account = $this->prodibi_settings['account'];
      
        // fix for the wordpress shortcode [] bug
        $command = str_replace('-__', '[', $atts['command']);
        $command = str_replace('__-', ']', $command);


        $json = json_decode($command);
        $type = $json->{'type'};
        $mediaView = $json->{'mediaView'};
        $settings = $json->{'settings'};
        $output = $command;
        //$output = json_encode($json);

		
        if (($type == 'albumsMediaView') || ($type == 'albumsGrid')) {
            return '<div data-prodibi=\'' . $output . '\' ></div>';
        } else if ($type == 'grid') {
            return '<div data-prodibi=\'' . $output . '\' ></div>';
        } else if ($mediaView->{'thumbnail'} === FALSE || $settings->{'thumbnail'} === FALSE) {
            return '<div style="display:none" data-prodibi=\'' . $output . '\' ></div>';
        } else {
            return '<canvas data-prodibi=\'' . $output . '\' ></canvas>';
        }
    }

   
}


$prodibi_manager = new Prodibi_manager();

?>