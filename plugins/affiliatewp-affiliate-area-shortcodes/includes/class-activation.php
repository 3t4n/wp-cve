<?php
/**
 * AffiliateWP Add-on Activation Handler
 *
 * For use by AffiliateWP and its add-ons.
 *
 * @package     AffiliateWP
 * @subpackage  Tools
 * @copyright   Copyright (c) 2021, Sandhills Development, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @version     1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * AffiliateWP Activation Handler Class
 *
 * @since 1.0.0
 */
class AffiliateWP_Activation {

	/**
	 * Plugin name.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
    public $plugin_name;

	/**
	 * Main plugin file path.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public $plugin_path;

	/**
	 * Main plugin filename.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public $plugin_file;

	/**
	 * Whether AffiliateWP is installed.
	 *
	 * @since 1.0.0
	 * @var   bool
	 */
	public $has_affiliatewp;

    /**
     * Sets up the activation class.
     *
     * @since 1.0.0
     * @since 1.3.1 Code refactored.
     *
     * @param string $plugin_file Main add-on plugin file path.
     * @param string $plugin_path Main add-on plugin file.
     */
    public function __construct( $plugin_path, $plugin_file ) {
        // We need plugin.php!
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        // Set plugin directory.
        $this->plugin_path = array_filter( explode( '/', $plugin_path ) );
        $this->plugin_path = end( $this->plugin_path );

        // Set plugin file.
        $this->plugin_file = $plugin_file;

        // Get the list of plugins.
        $plugins = get_plugins();

        // Set the real plugin name if the plugin is in the given list.
        $this->plugin_name = isset( $plugins[ $this->plugin_path . '/' . $this->plugin_file ]['Name'] )
            ? $plugins[ $this->plugin_path . '/' . $this->plugin_file ]['Name']
            : __( 'This plugin', 'affiliatewp-affiliate-area-shortcodes' );

        // Is AffiliateWP installed?
        $this->has_affiliatewp = ! empty( preg_grep('/\/affiliate-wp\.php$/', array_keys( $plugins ) ) );
    }

    /**
     * Displays the missing AffiliateWP notice.
     *
     * @since 1.0.0
     */
    public function run() {
        // Display notice
        add_action( 'admin_notices', array( $this, 'missing_affiliatewp_notice' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_banner_styles' ) );
    }

	/**
	 * Renders the AffiliateWP banner.
	 *
	 * @since 1.3.1
	 */
	public function render_banner() {
		global $current_screen;

		// Don't show the banner anywhere except the Dashboard and Plugins page.
		if ( 'plugins' !== $current_screen->base && 'dashboard' !== $current_screen->base ) {
			return;
		}
		?>
		<div class="affwp-banner">
			<div class="affwp-banner__top">
				<div>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><rect x="0" fill="none" width="24" height="24"/><g><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm1 15h-2v-2h2v2zm0-4h-2l-.5-6h3l-.5 6z"/></g></svg>
					<span>
						<?php echo sprintf( __( '%s requires AffiliateWP. %s to create a powerful affiliate program for WordPress.', 'affiliatewp-affiliate-area-shortcodes' ), $this->plugin_name, '<a href="https://affiliatewp.com/pricing/" title="AffiliateWP" target="_blank">Get AffiliateWP</a>' ); ?>
					</span>
				</div>
			</div>
			<div class="affwp-banner__inner">
				<div class="affwp-banner__content">
					<div class="affwp-banner__logo">
						<img class="" src="<?php echo esc_url( plugins_url( 'lib/affwp/images/affwp-logo.svg', __FILE__ ) ); ?>" />
					</div>
					<h2 class="affwp-banner__title"><?php esc_html_e( 'Grow Your Revenue with AffiliateWP', 'affiliate-wp' ); ?></h2>
					<p class="affwp-banner__text"><?php esc_html_e( 'AffiliateWP provides a complete affiliate management system for your WordPress website that seamlessly integrates with all major WordPress e-commerce and membership platforms.', 'affiliate-wp' ); ?></p>
					<div class="affwp-banner__footer">
						<a href="<?php echo esc_url( 'https://affiliatewp.com/pricing/' ); ?>" target="_blank" class="affwp-banner-cta-button">
							<?php esc_html_e( 'Get AffiliateWP', 'affiliate-wp' ); ?>
						</a>
					</div>
				</div>
				<div class="affwp-banner__image-container">
					<div class="affwp-banner__image-container-group">
						<img class="affwp-banner__image-use-case-icons" src="<?php echo esc_url( plugins_url( 'lib/affwp/images/affwp-use-cases.svg', __FILE__ ) ); ?>" />
						<picture>
							<source
								type="image/webp"
								srcset="<?php echo esc_url( plugins_url( 'lib/affwp/images/affwp-banner-image.webp', __FILE__ ) ); ?> 1x, <?php echo esc_url( plugins_url( 'lib/affwp/images/affwp-banner-image-2x.webp', __FILE__ ) ); ?> 2x">
							<img
								class="affwp-banner__image"
								srcset="<?php echo esc_url( plugins_url( 'lib/affwp/images/affwp-banner-image.png', __FILE__ ) ); ?> 1x, <?php echo esc_url( plugins_url( 'lib/affwp/images/affwp-banner-image-2x.png', __FILE__ ) ); ?> 2x"
								src="<?php echo esc_url( plugins_url( 'lib/affwp/images/affwp-banner-image.png', __FILE__ ) ); ?>"
								alt="">
						</picture>
						<img class="affwp-banner__image-background" src="<?php echo esc_url( plugins_url( 'lib/affwp/images/affwp-banner-pattern.svg', __FILE__ ) ); ?>" />
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Enqueues CSS for the banner.
	 *
	 * @since 1.3.1
	 */
	public function enqueue_banner_styles() {
		$plugin_url = untrailingslashit( plugin_dir_url( __FILE__ ) );

		wp_enqueue_style(
			'affwp-banner',
			"{$plugin_url}/lib/affwp/css/banner.css",
			null,
			'1.3.1'
		);
	}

	/**
	 * Displays a notice if AffiliateWP isn't installed.
	 *
	 * @since 1.0.0
	 * @since 1.3.1 Added banner.
	 *
	 * @return void
	 */
	public function missing_affiliatewp_notice() {

		if ( $this->has_affiliatewp ) {
			echo '<div class="error"><p>' . sprintf( __( '%s requires %s. Please activate it to continue.', 'affiliatewp-affiliate-area-shortcodes' ), $this->plugin_name, '<a href="https://affiliatewp.com/pricing/" title="AffiliateWP" target="_blank">AffiliateWP</a>' ) . '</p></div>';

		} else {
			$this->render_banner();
		}
	}
}