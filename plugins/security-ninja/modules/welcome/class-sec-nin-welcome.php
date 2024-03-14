<?php
namespace WPSecurityNinja\Plugin;
if ( ! function_exists( 'add_action' ) ) {
	die( 'Please don\'t open this file directly!' );
}

class sec_nin_welcome {

	public static function init() {
		//add_action('admin_menu', array(__NAMESPACE__ . '\\sec_nin_welcome', 'welcome_screen_page'));
	}

	/**
	* welcome_screen_page.
	*
	* @author   Lars Koudal
	* @since    v0.0.1
	* @version  v1.0.0  Wednesday, December 23rd, 2020.
	* @access   public static
	* @return   void
	*/
	public static function welcome_screen_page() {
		add_submenu_page('wf-sn', 'Welcome to Security Ninja', 'Welcome', 'manage_options', 'security-ninja-welcome', array(__NAMESPACE__ . '\\sec_nin_welcome', 'welcome_page'));
	}

	/**
	* welcome_page.
	*
	* @author   Lars Koudal
	* @since    v0.0.1
	* @version  v1.0.0  Wednesday, December 23rd, 2020.
	* @access   public static
	* @return   void
	*/
	public static function welcome_page() {

		require_once ABSPATH . 'wp-admin/admin-header.php';

		?>
		<style>
		.about__container .about__section .fs-notice {
			display: none !important;
		}
		</style>
		<div class="wrap about__container">

		<div class="about__section">
			<div class="column">
			<div class="alignleft" style="margin-right:20px;">
			<img src="<?php echo esc_url( WF_SN_PLUGIN_URL . '/images/plugin-icon.png' ); ?>" width="90" height="90"/>
			</div>
			<h2><?php esc_html_e( 'Welcome to Security Ninja' ); ?> <span>v. <?php echo esc_html( Wf_Sn::get_plugin_version() ); ?></span></h2>

			<p><strong><span class="dashicons dashicons-arrow-right-alt"></span> <a href="<?php echo esc_url( admin_url( 'admin.php?page=wf-sn' ) ); ?>">Click here to open plugin dashboard</a></strong></p>
			</div>
		</div>

<hr />
		<div class="about__section has-2-columns is-fullwidth">
		
		<div class="column">
		<h2>Security Testing</h2>
		<p>Do 50+ tests in a few minutes. Each test comes with detailed information on what the problem is and how you can solve it.</p>
		
		<p>Although these tests cover years of best practices in security, getting all test green does not guarantee your site will not get hacked. Likewise, having them all red does not mean you will get hacked.</p>
		</div>
		
		<div class="column is-vertically-aligned-center">
		<p><a href="<?php echo esc_url( admin_url( 'admin.php?page=wf-sn#sn_tests' ) ); ?>" class="button button-primary button-hero">Start testing</a></p>
		
		</div>
		</div>
		
		<hr />

		<div class="about__section has-2-columns is-fullwidth">
		<div class="column">
		<h2>Vulnerability testing</h2>
		<p>Your WordPress website consists of code from many different developers. Security flaws can happen everywhere.</p>
		
		<p>WP Security Ninja maintains a list of known vulnerable plugins and checks your website regularly.</p>
		
		<p><small><strong>Your data is safe!</strong> Nothing is sent to our servers, all is checked on your own website. <a target="_blank" href="<?php echo esc_url( Wf_Sn::generate_sn_web_link( 'sn_plugin_welcome_page', '/vulnerabilities/' ) ); ?>">Learn more</a> (external link)</small></p>
		
		</div>
		
		<?php
		$vulns = Wf_Sn_Vu::return_vulnerabilities();

		$total_vulnerabilities = 0;
		$plugin_vulns_count    = 0;
		$theme_vulns_count     = 0;
		$wp_vulns_count        = 0;

		if ( isset( $vulns['plugins'] ) ) {
			$plugin_vulns_count    = count( $vulns['plugins'] );
			$total_vulnerabilities = $total_vulnerabilities + $plugin_vulns_count;
		}

		if ( isset( $vulns['themes'] ) ) {
			$theme_vulns_count     = count( $vulns['themes'] );
			$total_vulnerabilities = $total_vulnerabilities + $theme_vulns_count;
		}

		if ( isset( $vulns['wordpress'] ) ) {
			$wp_vulns_count        = count( $vulns['wordpress'] );
			$total_vulnerabilities = $total_vulnerabilities + $wp_vulns_count;
		}

		$divclass = 'column is-vertically-aligned-center';
		if ( 0 < $total_vulnerabilities ) {
			$divclass .= ' warningbox';
		}
?>

<div class="<?php echo esc_attr($divclass); ?>">

<?php
		if ( 0 < $total_vulnerabilities ) {

			?>
			<h2>WARNING: Vulnerabilities found!</h2>
			<h3><span class="dashicons dashicons-warning"></span>
			<?php
			// translators: Shown if one or multiple vulnerabilities found
			echo esc_html( sprintf( _n( 'You have %s known vulnerability on your website!', 'You have %s known vulnerabilities on your website!', $total_vulnerabilities, 'security-ninja' ), number_format_i18n( $total_vulnerabilities ) ) );
			?>
			
			</h3>
			<p>We have discovered vulnerable plugins on your website. Please click the link for more details and advice.</p>
			<p><a href="<?php echo esc_url( admin_url( 'admin.php?page=wf-sn#sn_vuln' ) ); ?>" class="button button-primary button-hero">Visit vulnerabilities tab</a></p>
			<?php
		} else {
			?>
			<h3><span class="dashicons dashicons-yes"></span> No vulnerabilities found</h3>
			<p>Great, no known vulnerabilities were found right now. You will be alerted if any shows up.</p>
			<p><a href="<?php echo esc_url( admin_url( 'admin.php?page=wf-sn#sn_vuln' ) ); ?>">Visit vulnerabilities tab</a></p>
			<?php
		}
		?>
		
		</div>
		</div>
		<hr />

		
		<div class="about__section has-3-columns has-subtle-background-color">
		<div class="column ">
		<h3>Help and documentation</h3>
		<ul>
		<li>Get help here: <a target="_blank" href="<?php echo esc_url( Wf_Sn::generate_sn_web_link( 'sn_plugin_welcome_page', '/help/' ) ); ?>">Help page</a> (external link)</li>
		<li>Browse the documentation here: <a target="_blank" href="<?php echo esc_url( Wf_Sn::generate_sn_web_link( 'sn_plugin_welcome_page', '/docs/' ) ); ?>">Documentation</a> (external link)</li>
		
		</ul>
		
		</div>

		<div class="column">
		<h3>Protect your website</h3>
		<p>Check out what the Pro version does: <a target="_blank" href="<?php echo esc_url( Wf_Sn::generate_sn_web_link( 'sn_plugin_welcome_page', '/' ) ); ?>">WP Security Ninja Pro</a> (external link)</p>
		
		<ul>
		<li>Blog - Security, WordPress and more <a target="_blank" href="<?php echo esc_url( Wf_Sn::generate_sn_web_link( 'sn_plugin_welcome_page', '/blog/' ) ); ?>">Read the latest articles</a> (external link)</li>
		</ul>


		</div>


		<div class="column ">
		<h3>More</h3>
		<ul>
		<li>Wonder what changed? <a target="_blank" href="<?php echo esc_url( Wf_Sn::generate_sn_web_link( 'sn_plugin_welcome_page', '/changelog/' ) ); ?>">Changelog</a> (external link)</li>
		<li>Want to talk to us? <a target="_blank" href="<?php echo esc_url( Wf_Sn::generate_sn_web_link( 'sn_plugin_welcome_page', '/contact/' ) ); ?>">Contact us</a> (external link)</li>
		</ul>
		</div>
		</div>



		</div>

		<?php
	}



}

// hook everything up
add_action( 'plugins_loaded', array( __NAMESPACE__ . '\Sec_Nin_Welcome', 'init' ) );