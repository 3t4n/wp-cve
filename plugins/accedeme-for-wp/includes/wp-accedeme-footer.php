<?php

/**
 * Adds our one line of code to the footer.
 *
 * This class defines all code necessary to run the Accedeme widget.
 *
 * @package    wp_accedeme
 * @subpackage wp_accedeme/includes
 * @author     Accedeme
 */
class wp_accedeme_footer
{
	/**
     * Constructor
     *
     * added v 0.1
     */
    public function __construct() {

//$this->myfile = fopen('./wp-content/plugins/wp-accedeme/wp-accedeme-footer_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        add_action( 'wp_footer', array( $this, 'frontendFooterScript' ), 100); // v 0.1
        //add_action( 'wp_enqueue_scripts', array( $this, 'my_plugin_assets' ), 100 ); // v 0.4
        //add_action( 'wp_footer', array( $this, 'externalScript' ), 100); // v 0.5
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * Adds the line to the portal.
     *
     * added on v 0.1
     *
     * Deprecated
     */
    function frontendFooterScript(){
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        _e(html_entity_decode(wp_unslash('<script id="accssmm" src="https://widget.accssmm.com/accssme/accssmetool.js"></script>'.PHP_EOL)));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * Enqueues script
     *
     * added on v 0.4
     *
     * Deprecated
     */
    function my_plugin_assets() {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        wp_register_script( 'wp-accedeme', plugins_url( '/wp-accedeme/assets/js/index.js' ) );
        wp_enqueue_script( 'wp-accedeme' );
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * Adds the line to the portal footer.
     *
     * added on v 0.5
     */
    function externalScript(){
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        //wp_register_script( 'wp-accedeme-script', plugins_url( '/wp-accedeme/assets/js/wp_accedeme_script.js' ) );
        //wp_register_script('wp-accedeme-script', plugins_url('/wp-accedeme/assets/js/wp_accedeme_script.js', __FILE__), array('jquery'),'1.1', true);
//$file_path = plugins_url('../wp-accedeme/assets/js/wp_accedeme_script.js', __FILE__);
//$txt = 'Plugins path '.$file_path.PHP_EOL.plugins_url().'/wp-accedeme/assets/js/wp_accedeme_script.js'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Plugins path '.get_template_directory_uri().PHP_EOL; fwrite($this->myfile, $txt);
        wp_register_script('wp-accedeme-script', plugins_url().'/wp-accedeme/assets/js/wp_accedeme_script.js');
        //wp_register_script('wp-accedeme-script', plugins_url('/wp-accedeme/assets/js/wp_accedeme_script.js'), __FILE__);
        wp_enqueue_script( 'wp-accedeme-script' );
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
