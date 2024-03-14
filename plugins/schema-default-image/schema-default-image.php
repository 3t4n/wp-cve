<?php
/**
 * Plugin Name: Schema Default Image
 * Plugin URI: https://schema.press
 * Description: Add ability to set a default Featured image for schema.org markup, an extension for the Schema plugin.
 * Author: Hesham
 * Author URI: http://zebida.com
 * Version: 1.2.3
 * Text Domain: schema-wp-default-image
 * Domain Path: languages
 *
 * Schema Review is distributed under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Schema Review is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Schema. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Schema Default Image
 * @category Core
 * @author Hesham Zebida
 * @version 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


add_action( 'plugins_loaded', array( 'Schema_WP_Default_Image', 'init' ));
/**
 * Main Schema_WP_Editor_Review Class
 *
 * @since 1.0
 */
if(!class_exists('Schema_WP_Default_Image')) {
	 
class Schema_WP_Default_Image {
	
	/**
	 * Path to this plugin's directory.
	 *
	 * @type string
	 */
	public $plugin_path = '';
	

    public static function init() {
		
		if ( ! class_exists( 'Schema_WP' ) ) 
			return; // Schema not present
		
        $class = __CLASS__;
        new $class;
    }
	
	/**
	 * Constructor 
	 *
	 * @see plugin_setup()
	 * @since 1.0
	 */
    public function __construct() {
           //construct what you see fit here...
		   
		   add_action('plugins_loaded', array(&$this,'load_language'));
		   
		   require_once 'includes/functions.php';
    }
	
		/**
	 * Loads translation file.
	 *
	 * Accessible to other classes to load different language files (admin and
	 * front-end for example).
	 *
	 * @wp-hook init
	 * @param   string $domain
	 * @since   1.0
	 * @return  void
	 */
	public function load_language( $domain ) {
		load_plugin_textdomain(
			$domain,
			FALSE,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/'
		);
	}
}

} // end if class_exists
 