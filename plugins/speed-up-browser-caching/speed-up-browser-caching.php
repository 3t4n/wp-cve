<?php
/*
 Plugin Name: Speed Up - Browser Caching
 Plugin URI: http://wordpress.org/plugins/speed-up-browser-caching/
 Description: Help browser to cache a local copy of static files and improve page load times.
 Version: 1.0.10
 Author: Simone Nigro
 Author URI: https://profiles.wordpress.org/nigrosimone
 License: GPLv2 or later
 License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( !defined('ABSPATH') ) exit;

class SpeedUp_BrowserCaching {
    
	const PLUGIN_NAMESPACE = 'speed-up-browser-caching';
    const HTACCESS_SECTION = 'SpeedUp_BrowserCaching';
    
    private static $HTACCESS_SECTION_START = null;
    private static $HTACCESS_SECTION_END   = null;
    
    /**
     * Instance of the object.
     *
     * @since  1.0.0
     * @static
     * @access public
     * @var null|object
     */
    public static $instance = null;
    
    
    /**
     * Access the single instance of this class.
     *
     * @since  1.0.0
     * @return SpeedUp_BrowserCaching
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     *
     * @since  1.0.0
     * @return SpeedUp_BrowserCaching
     */
    private function __construct(){
        
    	self::$HTACCESS_SECTION_START = '# BEGIN '.self::HTACCESS_SECTION;
    	self::$HTACCESS_SECTION_END   = '# END '.self::HTACCESS_SECTION;
        
        register_activation_hook( __FILE__, array('SpeedUp_BrowserCaching', 'install') );
        register_deactivation_hook( __FILE__, array('SpeedUp_BrowserCaching', 'uninstall') );
    }
    
    /**
     * Install
     *
     * @since  1.0.0
     * @return void
     */
    public static function install() {
        self::add_htaccess_rule();
    }
    
    /**
     * Uninstall
     *
     * @since  1.0.0
     * @return void
     */
    public static function uninstall() {
        self::remove_htaccess_rule();
    }
    
    /**
     * Add the htaccess rule.
     * 
     * @since  1.0.0
     * @return boolean
     */
    public static function add_htaccess_rule() {
        
        if( empty(self::$HTACCESS_SECTION_START) || empty(self::$HTACCESS_SECTION_END) ){
            return false;
        }
        
        $original_htaccess = self::get_wp_htaccess_file_path();
        $temp_htaccess     = self::get_tm_htaccess_file_path();
        $my_htaccess       = self::get_my_htaccess_file_path();
        
        
        if( !$original_htaccess || !$temp_htaccess || !$my_htaccess ){
            return false;
        }
        
        // Get file lines as array
        $old_lines = file($original_htaccess);
        $my_lines  = file($my_htaccess);
            
            
        if( (!empty($old_lines) && is_array($old_lines)) && (!empty($my_lines) && is_array($my_lines)) ){
            
            $htaccess_is_edited = false;
            
            // add begin of section
            array_unshift($my_lines, "\n" . self::$HTACCESS_SECTION_START . "\n");
                
            // add end of section
            array_push($my_lines, "\n" . self::$HTACCESS_SECTION_END . "\n");
            
            // Open file for writing
            if( $file_handle = @fopen($temp_htaccess, "w") ){

                // add the new line at the beginning
                $new_lines = array_merge($my_lines, $old_lines);
                            
                for($i = 0, $e = count($new_lines); $i < $e; $i++) {
                    fwrite($file_handle, $new_lines[$i]);
                }
                            
                // flush all buffer
                $htaccess_is_edited = fflush($file_handle);
            }
            // Close file
            fclose($file_handle);
            
            if( $htaccess_is_edited ){
                if( self::make_htaccess_backup() ){
                    return rename($temp_htaccess, $original_htaccess);
                }
            }
        }
        
        return false;
    }
    
    /**
     * Remove the htaccess rule.
     *
     * @since  1.0.0
     * @return boolean
     */
    public static function remove_htaccess_rule() {
        
        if( empty(self::$HTACCESS_SECTION_START) || empty(self::$HTACCESS_SECTION_END) ){
            return false;
        }
        
        $original_htaccess = self::get_wp_htaccess_file_path();
        $temp_htaccess     = self::get_tm_htaccess_file_path();
        
        
        if( !$original_htaccess || !$temp_htaccess ){
            return false;
        }
        
        // Get file lines as array
        $old_lines = file($temp_htaccess);
             
        if( !empty($old_lines) && is_array($old_lines) ){
            
            $htaccess_is_edited = false;
            
            // Open file for writing
            if( $file_handle = @fopen($temp_htaccess, "w") ){

                $speed_up_directives = null;
                            
                // loop over the htaccess lines
                for($i = 0, $e = count($old_lines); $i < $e; $i++) {
                                
                    $line = $old_lines[$i];
                                
                    // when we find the first line of Speed Up directives
                    if( strpos($line, self::$HTACCESS_SECTION_START) === 0 ) {
                        $speed_up_directives = true;
                    }

                    // remove the line if is in a Speed Up section
                    if( $speed_up_directives === true ){
                        unset($old_lines[$i]);
                    }
                                
                    // when we find the last line of Speed Up directives
                    if( strpos($line, self::$HTACCESS_SECTION_END) === 0 ) {
                        $speed_up_directives = false;
                        break; // end of operation, exit for
                    }
                }
                unset($i, $e);
                
                // mhhh this is strange!
                if( $speed_up_directives !== false ){
                    return false;
                }
                            
                // reindex
                $new_lines = array_values($old_lines);
                                
                // loop over lines
                for($i = 0, $e = count($new_lines); $i < $e; $i++) {
                    fwrite($file_handle, $new_lines[$i]);
                }

                // flush all buffer
                $htaccess_is_edited = fflush($file_handle);
            }
            // close file
            fclose($file_handle);
            
            if( $htaccess_is_edited ){
                if( self::make_htaccess_backup() ){
                    return rename($temp_htaccess, $original_htaccess);
                }
            }
        }
        
        return false;
    }
    
    /**
     * Make a backup/copy of htaccess.
     * 
     * @since  1.0.0
     * @return string|false
     */
    private static function make_htaccess_backup(){
        
        if( $file_path = self::get_wp_htaccess_file_path() ){
            $backup_file_path = ABSPATH . 'speed-up-backup-' . date('Y-m-d_His') .'.htaccess';
            
            if( copy($file_path, $backup_file_path)) {
                return $backup_file_path;
            }
        }
        
        return false;
    }
    
    /**
     * Make a copy of wordpress htaccess file.
     *
     * @since  1.0.0
     * @param  string $dest
     * @return boolean
     */
    private static function copy_wp_htaccess($dest){
        
        if( $source = self::get_wp_htaccess_file_path() ){
            return copy($source, $dest);
        }
         
        return false;
    }
    
    /**
     * Restore a htaccess backup.
     *
     * @since  1.0.0
     * @return boolean
     */
    private static function restore_htaccess_backup($backup_file_path){
        
        if( !is_file($backup_file_path) ){
            return false;
        }
        
        if( $file_path = self::get_wp_htaccess_file_path() ){
            return copy($backup_file_path, $file_path);
        }
         
        return false;
    }
   
    /**
     * Return the wordpress htaccess file path.
     * 
     * @since  1.0.0
     * @return string|false
     */
    private static function get_wp_htaccess_file_path(){
        
        $file_path = ABSPATH . '.htaccess';
        
        if ( is_writable($file_path) && is_readable($file_path) ) {
            return $file_path;
        }
        
        return false;
    }
    
    /**
     * Return the plugin htaccess template file path.
     *
     * @since  1.0.0
     * @return string|false
     */
    private static function get_my_htaccess_file_path(){
        
        $file_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'htaccess.txt';
        
        if ( is_writable($file_path) && is_readable($file_path) ) {
            return $file_path;
        }
         
        return false;
    }
    
    /**
     * Create and return the temp htaccess file path.
     *
     * @since  1.0.0
     * @return string|false
     */
    private static function get_tm_htaccess_file_path(){
        
        $tm_file_path = ABSPATH . 'speed-up-temp.htaccess';
        
        if( self::copy_wp_htaccess($tm_file_path) ){
            if ( is_writable($tm_file_path) && is_readable($tm_file_path) ) {
                return $tm_file_path;
            }
        }
    
        return false;
    }
}

// Init
SpeedUp_BrowserCaching::get_instance();