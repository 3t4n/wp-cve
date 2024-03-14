<?php
/**
 * Plugin Name: AffiliateWP - Affiliate Area Shortcodes
 * Plugin URI: https://affiliatewp.com/add-ons/official-free/affiliate-area-shortcodes/
 * Description: Provides shortcodes for each tab of the Affiliate Area + other useful shortcodes
 * Author: AffiliateWP
 * Author URI: https://affiliatewp.com
 * Version: 1.3.1
 * Text Domain: affiliatewp-affiliate-area-shortcodes
 * Domain Path: languages
 *
 * AffiliateWP is distributed under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * AffiliateWP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with AffiliateWP. If not, see <http://www.gnu.org/licenses/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AffiliateWP_Requirements_Check_v1_1' ) ) {
	require_once dirname( __FILE__ ) . '/includes/lib/affwp/class-affiliatewp-requirements-check-v1-1.php';
}

/**
 * Class used to check requirements for and bootstrap the plugin.
 *
 * @since 1.2
 *
 * @see Affiliate_WP_Requirements_Check
 */
class AffiliateWP_AAS_Requirements_Check extends AffiliateWP_Requirements_Check_v1_1 {

	/**
	 * Plugin slug.
	 *
	 * @since 1.2
	 * @var   string
	 */
	protected $slug = 'affiliatewp-affiliate-area-shortcodes';

	/**
	 * Add-on requirements.
	 *
	 * @since 1.2
	 * @var   array[]
	 */
	protected $addon_requirements = array(
		// AffiliateWP.
		'affwp' => array(
			'minimum' => '2.6',
			'name'    => 'AffiliateWP',
			'exists'  => true,
			'current' => false,
			'checked' => false,
			'met'     => false,
		),
	);

	/**
	 * Bootstrap everything.
	 *
	 * @since 1.2
	 */
	public function bootstrap() {
		if ( ! class_exists( 'Affiliate_WP' ) ) {

			if ( ! class_exists( 'AffiliateWP_Activation' ) ) {
				require_once 'includes/class-activation.php';
			}

			// AffiliateWP activation.
			if ( ! class_exists( 'Affiliate_WP' ) ) {
				( new AffiliateWP_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) ) )->run();
			}
		} else {
			\AffiliateWP_Affiliate_Area_Shortcodes::instance( __FILE__ );
		}
	}

	/**
	 * Loads the add-on.
	 *
	 * @since 1.2
	 * @since 1.3.1 bootrastrap method no longer runs on affwp_plugins_loaded hook.
	 */
	protected function load() {
		// Maybe include the bundled bootstrapper.
		if ( ! class_exists( 'AffiliateWP_Affiliate_Area_Shortcodes' ) ) {
			require_once dirname( __FILE__ ) . '/includes/class-affiliatewp-affiliate-area-shortcodes.php';
		}

		// Maybe hook-in the bootstrapper.
		if ( class_exists( 'AffiliateWP_Affiliate_Area_Shortcodes' ) ) {

			// Bootstrap to plugins_loaded before priority 10 to make sure add-ons are loaded after us.
			add_action( 'plugins_loaded', array( $this, 'bootstrap' ), 101 );

			// Register the activation hook.
			register_activation_hook( __FILE__, array( $this, 'install' ) );
		}
	}

	/**
	 * Install, usually on an activation hook.
	 *
	 * @since 1.2
	 */
	public function install() {
		// Bootstrap to include all of the necessary files.
		$this->bootstrap();

		if ( defined( 'AFFWP_AAS_VERSION' ) ) {
			update_option( 'affwp_aas_version', AFFWP_AAS_VERSION );
		}
	}

	/**
	 * Plugin-specific aria label text to describe the requirements link.
	 *
	 * @since 1.2
	 *
	 * @return string Aria label text.
	 */
	protected function unmet_requirements_label() {
		return esc_html__( 'AffiliateWP - Affiliate Area Shortcodes Requirements', 'affiliatewp-affiliate-area-shortcodes' );
	}

	/**
	 * Plugin-specific text used in CSS to identify attribute IDs and classes.
	 *
	 * @since 1.2
	 *
	 * @return string CSS selector.
	 */
	protected function unmet_requirements_name() {
		return 'affiliatewp-affiliate-area-shortcodes-requirements';
	}

	/**
	 * Plugin specific URL for an external requirements page.
	 *
	 * @since 1.2
	 *
	 * @return string Unmet requirements URL.
	 */
	protected function unmet_requirements_url() {
		return 'https://affiliatewp.com/docs/minimum-requirements-roadmap/';
	}

}

$requirements = new AffiliateWP_AAS_Requirements_Check( __FILE__ );

$requirements->maybe_load();
