<?php
/*
 * Plugin Name: Analytics Tracker
 * Plugin URI: https://stylishwp.com/product/wordpress-plugins/google-analytics-tracker/
 * Description: Analytics Tracker makes it super easy to add Google Analytics tracking code on your site
 * Text Domain: analytics-tracker
 * Domain Path: /languages
 * Version: 2.0.1
 * Author: Valeriu Tihai
 * Author URI: https://valeriu.tihai.ca
 * Contributors: valeriutihai
 * Donate link: https://paypal.me/valeriu/25
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );
// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Main Class - Analytics Tracker
 *
 * @since 1.0.0
 */
class AnalyticsTracker {


	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Load language file
		add_action( 'plugins_loaded', array( &$this, 'analyticstracker_load_textdomain' ), 2 );

		// Add Support Link
		add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), array( &$this, 'analyticstracker_links' ), 1 );

		// Add GA code
		add_action( 'wp_head', array( &$this, 'analyticstracker_ga_script' ), 3 );

		//Add AMP analytics JavaScript
		add_action( 'amp_post_template_head', array( &$this, 'analyticstracker_amp_analytics_scripts' ), 1 );

		//Add AMP analytics tracking code
		add_action( 'amp_post_template_footer', array( &$this, 'analyticstracker_amp_analytics_code' ), 1 );

		// Add Admin menu
		add_action( 'admin_menu', array( $this, 'analyticstracker_admin_menu' ), 4 );

		// Load settings
		add_action( 'plugins_loaded', array( &$this, 'analyticstracker_settings' ), 1 );

		// Setting Initialization
		add_action( 'admin_init', array( &$this, 'analyticstracker_settings_init' ), 1 );

		// Load external JavaScript
		add_action( 'wp_enqueue_scripts', array( &$this, 'analyticstracker_load_js' ), 1 );

		// Load external JavaScript
		add_action( 'admin_enqueue_scripts', array( &$this, 'analyticstracker_enqueue_admin' ), 1 );

		//Add Comment meta
		add_action( 'wp_insert_comment', array( &$this, 'analyticstracker_ga_comment_meta' ), 99, 2 );

	}

	/**
	 * Adding amp-analytics script to head
	 *
	 * @since 1.1.0
	 * @access public
	 */
	public function analyticstracker_amp_analytics_scripts(){ ?>
		<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
	<?php
	}


	/**
	 * Adding amp-analytics tracking code
	 *
	 * @since 1.1.0
	 * @access public
	 */
	public function analyticstracker_amp_analytics_code() {
		$saved_options = get_option( 'analyticstracker_settings' );
		if( preg_match("/UA-[0-9]{3,9}-[0-9]{1,4}/", $saved_options['analyticstracker_ga']) ) { ?>
			<amp-analytics type="googleanalytics" id="googleanalytics1">
				<script type="application/json">
					{
						"vars": {
							"account": "<?php echo $saved_options['analyticstracker_ga']; ?>"
						},
						"triggers": {
							"trackPageview": {
								"on": "visible",
								"request": "pageview"
							}
						}
					}
				</script>
			</amp-analytics>
		<?php
		}
	}


	/**
	 * Load language file
	 *
	 * This will load the MO file for the current locale.
	 * The translation file must be named analytics-tracker-$locale.mo.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function analyticstracker_load_textdomain() {
		load_plugin_textdomain( 'analytics-tracker', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
	}


	/**
	 * Add settings links to plugin page
	 *
	 * @since 1.0.0
	 * @access  public
	 */
	public function analyticstracker_links( $links ) {
		$links[] = '<a href="https://wordpress.org/support/plugin/analytics-tracker" target="_blank">'.__( 'Support', 'analytics-tracker' ).'</a>';
		return $links;
	}


	/**
	 * Add plugin menu
	 *
	 * @since 1.0.0
	 * @access  public
	 */
	public function analyticstracker_admin_menu() {
		add_menu_page( __( 'Google Analytics', 'analytics-tracker' ), __( 'Google Analytics', 'analytics-tracker' ), 'manage_options', 'analyticstracker-menu', array( $this, 'analyticstracker_options_page' ), 'dashicons-chart-line' );
		add_submenu_page( 'analyticstracker-menu', __( 'Other Products', 'analytics-tracker' ), __( 'Other Products', 'analytics-tracker' ), 'manage_options', 'analyticstracker-other-plugins', array( $this, 'analyticstracker_other_plugins' ) );
	}


	/**
	 * My others plugins
	 *
	 * @since 1.0.0
	 * @access  public
	 */
	public function  analyticstracker_other_plugins() { ?>
		 <?php add_thickbox(); ?>
		<div class="wrap">
			<h2><?php _e( 'My Recommendations', 'analytics-tracker' ); ?></h2>
			<div class='card pressthis'>
				<h2><?php _e( 'WordPress Hosting', 'analytics-tracker' ); ?></h2>
				<ul>
					<li>

						<a href="https://kinsta.com/plans/?kaid=NDINHGAQXILS" target="_blank"><?php _e( 'Kinsta Managed WordPress Hosting', 'analytics-tracker'); ?></a> - <?php _e('We have over 10 years of experience working with WordPress and we\'ve poured all that know-how into creating the best managed WordPress hosting solution available today.', 'analytics-tracker' ) ?>
						<p>
							<a href="https://kinsta.com/plans/?kaid=NDINHGAQXILS" target="_blank">
								<img class="pressthis_img" src="https://i2.wp.com/valeriu.files.wordpress.com/2018/02/kinsta-dark.png" alt="<?php _e( 'Kinsta Managed WordPress Hosting', 'analytics-tracker'); ?>">
							</a>
						</p>
					</li>
				</ul>
			</div>
			<div class='card pressthis'>
				<h2><?php _e( 'WordPress Plugins', 'analytics-tracker' ); ?></h2>
				<ul>
					<li>
						<a href="<?php echo network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=wplook-twitter-follow-button-new&TB_iframe=true&width=762&height=600' ); ?>" class="thickbox"><?php _e( 'Twitter Follow Button', 'analytics-tracker' ); ?></a> - <?php _e( 'Add the Twitter Follow Button to your blog to increase engagement and create a lasting connection with your audience.', 'analytics-tracker' ); ?>
					</li>
					<li>
						<a href="<?php echo network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=auto-update&TB_iframe=true&width=762&height=600' ); ?>" class="thickbox"><?php _e( 'Auto Update', 'analytics-tracker'); ?></a> - <?php _e( 'This plugin enable Auto Update for WordPress core, Themes and Plugins.', 'analytics-tracker' ); ?>
					</li>
					<li>
						<a href="https://wplook.com/product/plugins/comingsoon-maintenance-mode-wordpress-plugin/?ref=104&campaign=AnalyticsTracker" target="_blank"><?php _e( 'ComingSoon', 'analytics-tracker'); ?></a> - <?php _e('ComingSoon is a Premium Maintenance Mode Plugin designed specifically for Editors, Designers or Developers who want to let visitors know the blog is down for Maintenance or Under Construction.', 'analytics-tracker' ) ?>
					</li>
				</ul>
			</div>
			<div class='card pressthis'>
				<h2><?php _e( 'WordPress Themes', 'analytics-tracker' ); ?></h2>
				<ul>
					<li>
						<a href="<?php echo network_admin_url( 'theme-install.php?theme=blogolife'); ?>"><?php _e( 'BlogoLife', 'analytics-tracker' ); ?></a> - <?php _e( 'BlogoLife is a simple and perfect WordPress theme for personal blogging that supports post formats, and several customization options.', 'analytics-tracker' ); ?>
					</li>
					<li>
						<a href="<?php echo esc_url( 'https://themeforest.net/item/avada-responsive-multipurpose-theme/2833226?ref=stylishwp' ); ?>" target="_blank"><?php _e( 'Avada', 'analytics-tracker' ); ?></a> - <?php _e( 'the #1 selling WordPress theme on the market. Simply put, it is the most versatile, easy to use multi-purpose WordPress theme. Avada is all about building unique, creative and professional websites through industry leading options network without having to touch a line of code. ', 'analytics-tracker' ); ?>
					</li>
					<li>
						<a href="<?php echo esc_url( 'https://themeforest.net/item/the7-responsive-multipurpose-wordpress-theme/5556590?ref=stylishwp' ); ?>" target="_blank"><?php _e( 'The7', 'analytics-tracker' ); ?></a> - <?php _e( 'Its 750+ Theme Options allows to craft almost any imaginable design. And Design Wizard feature lets you create a boutique-grade website design in mere minutes.', 'analytics-tracker' ); ?>
					</li>
					<li>
						<a href="<?php echo esc_url( 'https://themeforest.net/item/betheme-responsive-multipurpose-wordpress-theme/7758048?ref=stylishwp' ); ?>" target="_blank"><?php _e( 'BeTheme', 'analytics-tracker' ); ?></a> - <?php _e( 'This is more than just WordPress theme. Such advanced options panel and Drag&Drop builder tool give unlimited possibilities.', 'analytics-tracker' ); ?>
					</li>
					<li>
						<a href="<?php echo esc_url( 'https://themeforest.net/item/enfold-responsive-multipurpose-theme/4519990?ref=stylishwp' ); ?>" target="_blank"><?php _e( 'Enfold', 'analytics-tracker' ); ?></a> - <?php _e( 'Enfold is a clean, super flexible and fully responsive WordPress Theme (try resizing your browser), suited for business websites, shop websites, and users who want to showcase their work on a neat portfolio site.', 'analytics-tracker' ); ?>
					</li>
				</ul>
			</div>
			<div class='card pressthis'>
				<h2><a target="_blank" title="<?php _e( 'Daily Beautiful WordPress Templates for your business', 'analytics-tracker' ); ?>" href="https://dailydesigncafe.com/?utm_source=DailyDesign&utm_medium=Recommendations&utm_campaign=AnalyticsTracker">Daily Design Cafe ☕</a></h2>
				<div class="dailydesign_container"></div>
			</div>
		</div>
	<?php
	}


	/**
	 * Settings
	 *
	 * @return Array with all settings
	 * @since 1.0.0
	 * @access public
	 */
	public function analyticstracker_settings () {
		return array(
			array(
				'settings_type' => 'section',
				'id'            => 'analyticstracker_section_settings_general',
				'title'         => __( 'General Settings', 'analytics-tracker' ),
				'callback'      => 'analyticstracker_description_section_callback',
				'page'          => 'analyticstracker_page'
			),
			array (
				'settings_type' => 'field',
				'id'            => 'analyticstracker_ga',
				'title'         => __( 'Google Analytics tracking ID', 'analytics-tracker' ),
				'callback'      => 'analyticstracker_settings_field_render',
				'page'          => 'analyticstracker_page',
				'section'       => 'analyticstracker_section_settings_general',
				'args'          => 	array (
									'id'          => 'analyticstracker_ga',
									'type'        => 'text',
									'class'       => '',
									'name'        => 'analyticstracker_ga',
									'value'       => 'analyticstracker_ga',
									'label_for'   => '',
									'description' => __( 'Add Google Analytics tracking ID (UA-XXXXXXX-YY). Where can I find <a href="https://support.google.com/analytics/answer/1032385?rd=1" target="_blank">my tracking ID?</a>', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id'            => 'analyticstracker_userid',
				'title'         => __( 'User ID', 'analytics-tracker' ),
				'callback'      => 'analyticstracker_settings_field_render',
				'page'          => 'analyticstracker_page',
				'section'       => 'analyticstracker_section_settings_general',
				'args'          => 	array (
									'id'          => 'analyticstracker_userid',
									'type'        => 'checkbox',
									'class'       => '',
									'name'        => 'analyticstracker_userid',
									'value'       => 1,
									'label_for'   => '',
									'description' => __( 'This is intended to be a known identifier for a user provided by the site owner/tracking library user.', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id'            => 'analyticstracker_anonymizeip',
				'title'         => __( 'Anonymize IP', 'analytics-tracker' ),
				'callback'      => 'analyticstracker_settings_field_render',
				'page'          => 'analyticstracker_page',
				'section'       => 'analyticstracker_section_settings_general',
				'args'          => 	array (
									'id'          => 'analyticstracker_anonymizeip',
									'type'        => 'checkbox',
									'class'       => '',
									'name'        => 'analyticstracker_anonymizeip',
									'value'       => 1,
									'label_for'   => '',
									'description' => __( 'The IP address of the sender will be anonymized', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id'            => 'analyticstracker_displayfeatures',
				'title'         => __( 'Display Features', 'analytics-tracker' ),
				'callback'      => 'analyticstracker_settings_field_render',
				'page'          => 'analyticstracker_page',
				'section'       => 'analyticstracker_section_settings_general',
				'args'          => 	array (
									'id'          => 'analyticstracker_displayfeatures',
									'type'        => 'checkbox',
									'class'       => '',
									'name'        => 'analyticstracker_displayfeatures',
									'value'       => 1,
									'label_for'   => '',
									'description' => __( 'The plugin works by sending an additional request to stats.g.doubleclick.net that is used to provide advertising features like remarketing and demographics and interest reporting in Google Analytics.', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id'            => 'analyticstracker_enhancedlinkatt',
				'title'         => __( 'Enhanced Link Attribution', 'analytics-tracker' ),
				'callback'      => 'analyticstracker_settings_field_render',
				'page'          => 'analyticstracker_page',
				'section'       => 'analyticstracker_section_settings_general',
				'args'          => 	array (
									'id'          => 'analyticstracker_enhancedlinkatt',
									'type'        => 'checkbox',
									'class'       => '',
									'name'        => 'analyticstracker_enhancedlinkatt',
									'value'       => 1,
									'label_for'   => '',
									'description' => __( 'Enhanced Link Attribution improves the accuracy of your In-Page Analytics report by automatically differentiating between multiple links to the same URL on a single page by using link element IDs.', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id'            => 'analyticstracker_events',
				'title'         => __( 'Event Tracking', 'analytics-tracker' ),
				'callback'      => 'analyticstracker_settings_field_render',
				'page'          => 'analyticstracker_page',
				'section'       => 'analyticstracker_section_settings_general',
				'args'          => 	array (
									'id'          => 'analyticstracker_events',
									'type'        => 'checkbox',
									'class'       => '',
									'name'        => 'analyticstracker_events',
									'value'       => 1,
									'label_for'   => '',
									'description' => __( 'Track events feature: Downloads, Emails, Phone numbers Error 404, Search and Outbound links, Add a comment, Scroll Depth', 'analytics-tracker' ),
				)
			),

			//Custom Dimension
			array(
				'settings_type' => 'section',
				'id'            => 'analyticstracker_section_settings_custom_dimension',
				'title'         => __( 'Custom Dimension', 'analytics-tracker' ),
				'callback'      => 'analyticstracker_description_section_cd_callback',
				'page'          => 'analyticstracker_page'
			),
			array (
				'settings_type' => 'field',
				'id'            => 'analyticstracker_custom_dimension',
				'title'         => __( 'Custom Dimension', 'analytics-tracker' ),
				'callback'      => 'analyticstracker_settings_field_render',
				'page'          => 'analyticstracker_page',
				'section'       => 'analyticstracker_section_settings_custom_dimension',
				'args'          => array (
									'id'          => 'analyticstracker_custom_dimension',
									'type'        => 'checkbox',
									'class'       => '',
									'name'        => 'analyticstracker_custom_dimension',
									'value'       => 1,
									'label_for'   => '',
									'description' => __( 'Enable Custom Dimension', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id'            => 'analyticstracker_cu_tags',
				'title'         => __( 'Tags', 'analytics-tracker' ),
				'callback'      => 'analyticstracker_settings_field_render',
				'page'          => 'analyticstracker_page',
				'section'       => 'analyticstracker_section_settings_custom_dimension',
				'args'          => array (
									'id'          => 'analyticstracker_cu_tags',
									'type'        => 'text',
									'class'       => 'small-text',
									'name'        => 'analyticstracker_cu_tags',
									'value'       => '',
									'default'     => '1',
									'label_for'   => 'analyticstracker_cu_tags',
									'description' => __( 'The index suffix for tags', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id'            => 'analyticstracker_cu_category',
				'title'         => __( 'Category', 'analytics-tracker' ),
				'callback'      => 'analyticstracker_settings_field_render',
				'page'          => 'analyticstracker_page',
				'section'       => 'analyticstracker_section_settings_custom_dimension',
				'args'          => 	array (
									'id'         => 'analyticstracker_cu_category',
									'type'        => 'text',
									'class'       => 'small-text',
									'name'        => 'analyticstracker_cu_category',
									'value'       => '',
									'default'     => '2',
									'label_for'   => 'analyticstracker_cu_category',
									'description' => __( 'The index suffix for category', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id'            => 'analyticstracker_cu_archive',
				'title'         => __( 'Archive', 'analytics-tracker' ),
				'callback'      => 'analyticstracker_settings_field_render',
				'page'          => 'analyticstracker_page',
				'section'       => 'analyticstracker_section_settings_custom_dimension',
				'args'          => array (
									'id'         => 'analyticstracker_cu_archive',
									'type'        => 'text',
									'class'       => 'small-text',
									'name'        => 'analyticstracker_cu_archive',
									'value'       => '',
									'default'     => '3',
									'label_for'   => 'analyticstracker_cu_archive',
									'description' => __( 'The index suffix for archive', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id'            => 'analyticstracker_cu_author',
				'title'         => __( 'Author', 'analytics-tracker' ),
				'callback'      => 'analyticstracker_settings_field_render',
				'page'          => 'analyticstracker_page',
				'section'       => 'analyticstracker_section_settings_custom_dimension',
				'args'          => 	array (
									'id'          => 'analyticstracker_cu_author',
									'type'        => 'text',
									'class'       => 'small-text',
									'name'        => 'analyticstracker_cu_author',
									'value'       => '',
									'default'     => '4',
									'label_for'   => 'analyticstracker_cu_author',
									'description' => __( 'The index suffix for author', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id'            => 'analyticstracker_cu_post_format',
				'title'         => __( 'Post Format', 'analytics-tracker' ),
				'callback'      => 'analyticstracker_settings_field_render',
				'page'          => 'analyticstracker_page',
				'section'       => 'analyticstracker_section_settings_custom_dimension',
				'args'          => 	array (
									'id'          => 'analyticstracker_cu_post_format',
									'type'        => 'text',
									'class'       => 'small-text',
									'name'        => 'analyticstracker_cu_post_format',
									'value'       => '',
									'default'     => '5',
									'label_for'   => 'analyticstracker_cu_post_format',
									'description' => __( 'The index suffix for Post Format', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id'            => 'analyticstracker_cu_post_type',
				'title'         => __( 'Post Type', 'analytics-tracker' ),
				'callback'      => 'analyticstracker_settings_field_render',
				'page'          => 'analyticstracker_page',
				'section'       => 'analyticstracker_section_settings_custom_dimension',
				'args'          => 	array (
									'id'          => 'analyticstracker_cu_post_type',
									'type'        => 'text',
									'class'       => 'small-text',
									'name'        => 'analyticstracker_cu_post_type',
									'value'       => '',
									'default'     => '6',
									'label_for'   => 'analyticstracker_cu_post_type',
									'description' => __( 'The index suffix for Post Type', 'analytics-tracker' ),
				)
			),
		);
	}


	/**
	 * Get Goolge Analytics code
	 *
	 * @since 1.0.2
	 * @access public
	 */
	public function analyticstracker_ga_get() {
		$saved_options 					= get_option( 'analyticstracker_settings' );
		$analyticstracker_gtag_general 	= $analyticstracker_gtag_events = array();

		if ( preg_match( "/UA-[0-9]{3,9}-[0-9]{1,4}/", $saved_options['analyticstracker_ga'] ) ) {
			global $post;

			/**
			 * User Identification
			 *
			 * @since 2.0.0
			 */
			if ( is_user_logged_in() ) {

				$current_user = wp_get_current_user();

				if ( isset( $saved_options['analyticstracker_userid'] ) && $saved_options['analyticstracker_userid'] != '' ) {
					$analyticstracker_gtag_general['user_id'] = $current_user->ID;
				}
			}

			/**
			 * IP anonymization
			 *
			 * @since 2.0.0
			 */
			if ( isset( $saved_options['analyticstracker_anonymizeip'] ) && $saved_options['analyticstracker_anonymizeip'] != '' ) {
				$analyticstracker_gtag_general['anonymize_ip'] = true;
			}

			/**
			 * Display Features
			 *
			 * @since 2.0.0
			 */
			if ( ! isset( $saved_options['analyticstracker_displayfeatures'] ) ) {
				$analyticstracker_gtag_general['allow_display_features'] = false;
			}

			/**
			 * Enhanced Link Attribution
			 *
			 * @since 2.0.0
			 */
			if ( isset( $saved_options['analyticstracker_enhancedlinkatt'] ) && $saved_options['analyticstracker_enhancedlinkatt'] != '' ) {
				$analyticstracker_gtag_general['link_attribution'] = true;
			}


			if ( isset( $saved_options['analyticstracker_events'] ) && $saved_options['analyticstracker_events'] != '' ) {

				/**
				 * Search Event
				 *
				 * @since 2.0.0
				 */
				if ( is_search() ) {
					$analyticstracker_gtag_events[] = array(
						'event',
						'keyword',
						array(
							'event_category' => 'Search',
							'event_label' => get_search_query( true ),
						),
					);
				}


				if ( is_singular() ) {


					/**
					 * Comments Event
					 *
					 * @since 2.0.0
					 */
					$args = array(
						'meta_key' 	=> 'analyticstracker_comment_event',
						'post_id' 	=> $post->ID,
					);
					$at_comments = get_comments($args);
					foreach( $at_comments as $at_comment ){
						$analyticstracker_gtag_events[] = array(
							'event',
							$at_comment->comment_author,
							array(
								'event_category'	=> 'Comments',
								'event_label' 		=> 'Post ID: '.$at_comment->comment_post_ID.' | Comment ID: '.$at_comment->comment_ID,
							),
						);
						delete_comment_meta( $at_comment->comment_ID, 'analyticstracker_comment_event' );
					}

					/**
					 * Custom Dimension
					 *
					 * @since 2.0.0
					 */
					if ( isset( $saved_options['analyticstracker_custom_dimension'] ) && $saved_options['analyticstracker_custom_dimension'] != '' ) {

						/**
						 * Custom Dimension for tags
						 *
						 * @since 2.0.0
						 */
						if ( isset( $saved_options['analyticstracker_cu_tags'] ) && $saved_options['analyticstracker_cu_tags'] != '' ) {

							if ( (int)$saved_options['analyticstracker_cu_tags'] AND  ( $saved_options['analyticstracker_cu_tags'] > 0 && $saved_options['analyticstracker_cu_tags'] < 201 ) ) {

								$at_post_tags = get_the_tags();

								if ($at_post_tags) {
									foreach($at_post_tags as $tag) {
										$at_post_tags_array[] = $tag->name;
									}
									$at_post_tags_cu = implode( '|', $at_post_tags_array );
								} else {
									$at_post_tags_cu = __( 'No Tags', 'analytics-tracker' );
								}

								$analyticstracker_gtag_general['custom_map']['dimension'.$saved_options['analyticstracker_cu_tags']] = 'analyticstracker_cu_tags';

								$analyticstracker_gtag_events[] = array(
									'event',
									'atracker_gtag',
									array(
										'analyticstracker_cu_tags' => $at_post_tags_cu,
										'non_interaction' => true,
									),
								);
							}
						}

						/**
						 * Custom Dimension for category
						 *
						 * @since 2.0.0
						 */
						if ( isset( $saved_options['analyticstracker_cu_category'] ) && $saved_options['analyticstracker_cu_category'] != '' ) {

							if ( (int)$saved_options['analyticstracker_cu_category'] AND  ( $saved_options['analyticstracker_cu_category'] > 0 && $saved_options['analyticstracker_cu_category'] < 201 ) ) {

								$at_post_categories = get_the_category();
								if ( $at_post_categories ) {

									foreach($at_post_categories as $category) {
										$at_post_categories_array[] = $category->name;
									}
									$at_post_categories_cu = implode( '|', $at_post_categories_array );
								} else {
									$at_post_categories_cu = __( 'No Category', 'analytics-tracker' );
								}

								$analyticstracker_gtag_general['custom_map']['dimension'.$saved_options['analyticstracker_cu_category']] = 'analyticstracker_cu_category';

								$analyticstracker_gtag_events[] = array(
									'event',
									'atracker_gtag',
									array(
										'analyticstracker_cu_category' => $at_post_categories_cu,
										'non_interaction' => true,
									),
								);
							}
						}

						/**
						 * Custom Dimension for archive
						 *
						 * @since 2.0.0
						 */
						if ( isset( $saved_options['analyticstracker_cu_archive'] ) && $saved_options['analyticstracker_cu_archive'] != '' ) {

							if ( (int)$saved_options['analyticstracker_cu_archive'] AND  ( $saved_options['analyticstracker_cu_archive'] > 0 && $saved_options['analyticstracker_cu_archive'] < 201 ) ) {

								$analyticstracker_gtag_general['custom_map']['dimension'.$saved_options['analyticstracker_cu_archive']] = 'analyticstracker_cu_archive';

								$analyticstracker_gtag_events[] = array(
									'event',
									'atracker_gtag',
									array(
										'analyticstracker_cu_archive' => get_the_date('Y|m|N|A'),
										'non_interaction' => true,
									),
								);
							}
						}

						/**
						 * Custom Dimension for author
						 *
						 * @since 2.0.0
						 */
						if ( isset( $saved_options['analyticstracker_cu_author'] ) && $saved_options['analyticstracker_cu_author'] != '' ) {

							if ( (int)$saved_options['analyticstracker_cu_author'] AND  ( $saved_options['analyticstracker_cu_author'] > 0 && $saved_options['analyticstracker_cu_author'] < 201 ) ) {

								$analyticstracker_gtag_general['custom_map']['dimension'.$saved_options['analyticstracker_cu_author']] = 'analyticstracker_cu_author';

								$analyticstracker_gtag_events[] = array(
									'event',
									'atracker_gtag',
									array(
										'analyticstracker_cu_author' => get_the_author_meta( 'display_name', $post->post_author ),
										'non_interaction' => true,
									),
								);
							}
						}

						/**
						 * Custom Dimension for Post Format
						 *
						 * @since 2.0.0
						 */
						if ( isset( $saved_options['analyticstracker_cu_post_format'] ) && $saved_options['analyticstracker_cu_post_format'] != '' ) {

							if ( (int)$saved_options['analyticstracker_cu_post_format'] AND  ( $saved_options['analyticstracker_cu_post_format'] > 0 && $saved_options['analyticstracker_cu_post_format'] < 201 ) ) {

								$postformat = get_post_format() ? : 'standard';

								$analyticstracker_gtag_general['custom_map']['dimension'.$saved_options['analyticstracker_cu_post_format']] = 'analyticstracker_cu_post_format';

								$analyticstracker_gtag_events[] = array(
									'event',
									'atracker_gtag',
									array(
										'analyticstracker_cu_post_format' => $postformat,
										'non_interaction' => true,
									),
								);
							}
						}

						/**
						 * Custom Dimension for Post Type
						 *
						 * @since 2.0.0
						 */
						if ( isset( $saved_options['analyticstracker_cu_post_type'] ) && $saved_options['analyticstracker_cu_post_type'] != '' ) {

							if ( (int)$saved_options['analyticstracker_cu_post_type'] AND  ( $saved_options['analyticstracker_cu_post_type'] > 0 && $saved_options['analyticstracker_cu_post_type'] < 201 ) ) {

								$analyticstracker_gtag_general['custom_map']['dimension'.$saved_options['analyticstracker_cu_post_type']] = 'analyticstracker_cu_post_type';
								$analyticstracker_gtag_events[] = array(
									'event',
									'atracker_gtag',
									array(
										'analyticstracker_cu_post_type' => $post->post_type,
										'non_interaction' => true,
									),
								);
							}
						}
					}
				}
			}

			return $analyticstracker_gtag = array(
				'general'	=> $analyticstracker_gtag_general,
				'events'	=> $analyticstracker_gtag_events,
			);
		}
	}


	/**
	 * Insert Google Analytics code
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function analyticstracker_ga_script() {
		$saved_options 				= get_option( 'analyticstracker_settings' );
		$analyticstracker 			= $this->analyticstracker_ga_get();
		$analyticstracker_config 	= ", ".str_replace( array( '[', ']' ), '', htmlspecialchars( wp_json_encode( $analyticstracker['general'] ), ENT_NOQUOTES ) );
		$analyticstracker_events 	= '';
		if ( ! empty( $analyticstracker['events'] ) ) {
			foreach ( $analyticstracker['events'] as $key => $value) {
				$analyticstracker_events .= "gtag(".str_replace( array( '[', ']' ), '', htmlspecialchars( wp_json_encode( $value ), ENT_NOQUOTES) ).");\r\n";
			}
		};

		if( preg_match("/UA-[0-9]{3,9}-[0-9]{1,4}/", $saved_options['analyticstracker_ga']) ) { ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $saved_options['analyticstracker_ga']; ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag("js", new Date());
  gtag("config", "<?php echo esc_js( $saved_options["analyticstracker_ga"] ) ?>"<?php echo( $analyticstracker_config ); ?>);
  <?php if ( $analyticstracker_events != '' ){ echo $analyticstracker_events; } ?>
</script>

	<?php }
	}


	/**
	 * Add JavaScript file
	 *
	 * @since 1.0.3
	 * @access  public
	 */
	public function analyticstracker_load_js() {
		$saved_options = get_option( 'analyticstracker_settings' );
		if ( isset($saved_options['analyticstracker_events']) && $saved_options['analyticstracker_events'] != '' ) {
			wp_enqueue_script( 'analyticstracker-js', plugins_url( '/javascripts/analyticstracker.js' , __FILE__ ), array( 'jquery', 'analyticstracker-jquery-scrolldepth' ) );
			wp_enqueue_script( 'analyticstracker-jquery-scrolldepth', plugins_url( '/javascripts/vendors/jquery-scrolldepth/jquery.scrolldepth.min.js' , __FILE__ ), array( 'jquery' ) );
		}
	}

	/**
	 * Add JavaScript and CSS files to wp-admin
	 *
	 * @since 1.0.4
	 * @access  public
	 */
	public function analyticstracker_enqueue_admin($hook) {
		if ( 'google-analytics_page_analyticstracker-other-plugins' == $hook ) {
			wp_enqueue_script( 'analyticstracker-js-admin', plugins_url( '/javascripts/analyticstracker-admin.js' , __FILE__ ), array( 'jquery' ) );

			wp_register_style( 'analyticstracker-css-admin', plugins_url( '/analytics-tracker/css/analyticstracker-admin.css', false, '2.0.1' ) );
			wp_enqueue_style( 'analyticstracker-css-admin' );
		}
	}


	/**
	 * Add comment meta for analytics tracker event
	 *
	 * @since 1.0.4
	 * @access  public
	 */
	public function analyticstracker_ga_comment_meta ( $comment_id, $comment_object ) {
		if ($comment_object->comment_approved == 1) {
			add_comment_meta( $comment_id, 'analyticstracker_comment_event', 1, true );
		}
	}


	/**
	 * Description Section callback
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function analyticstracker_description_section_callback( ) {	}


	/**
	 * Description Section callback for Custom Dimension
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function analyticstracker_description_section_cd_callback( ) {
		_e( 'Each custom dimension has an associated index. There is a maximum of 20 custom dimensions (200 for Premium accounts). The index suffix must be a positive integer greater than 0. How to <a href="https://support.google.com/analytics/answer/2709829?hl=en#set_up_custom_dimensions" target="_blank">set up</a> custom dimensions', 'analytics-tracker' );
	}


	/*
	 * Setting Initialization
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function analyticstracker_settings_init() {
		register_setting( 'analyticstracker_page', 'analyticstracker_settings' );
		foreach ( $this->analyticstracker_settings() AS $setting ) {
			if ( $setting['settings_type'] === 'section' ) {
				add_settings_section(
					$setting['id'],
					$setting['title'],
					array( $this, $setting['callback'] ),
					$setting['page']
				);
			}
			if ( $setting['settings_type'] === 'field' ) {
				add_settings_field(
					$setting['id'],
					$setting['title'],
					array( $this, $setting['callback'] ),
					$setting['page'],
					$setting['section'],
					$setting['args']
				);
			}
		}
	}


	/**
	 * Append a settings field to the the fields section.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param array $args
	 */
		public function analyticstracker_settings_field_render( array $options = array() ) {
		$saved_options = get_option( 'analyticstracker_settings' );

		$atts = array(
			'id'          => $options['id'],
			'type'        => ( isset( $options['type'] ) ? $options['type'] : 'text' ),
			'class'       => $options['class'],
			'name'        => 'analyticstracker_settings[' . $options['name'] . ']',
			'value'       => ( array_key_exists( 'default', $options ) ? $options['default'] : null ),
			'label_for'   => ( array_key_exists( 'label_for', $options ) ? $options['label_for'] : false ),
			'description' => ( array_key_exists( 'description', $options ) ? $options['description'] : false )
		);

		if ( isset( $options['id'] ) ) {
			if ( isset( $saved_options[$options['id']] ) AND ( $saved_options[$options['id']] != '') )  {
				$val = $saved_options[$options['id']];
			} else {
				$val = ( array_key_exists( 'default', $options ) ? $options['default'] : '' );
			}
			$atts['value'] = $val;
		}
		if ( isset( $options['type'] ) && $options['type'] == 'checkbox' ) {
			if ( $atts['value'] ) {
				$atts['checked'] = 'checked';
			}
				$atts['value'] = true;
		}


		/**
		 * Input type Checkbox
		 */
		if ( $atts['type'] == 'checkbox' ) {
			$html = sprintf( '<input type="%1$s" class="%2$s" id="%3$s" name="%4$s" value="%5$s" %6$s />', $atts['type'], $atts['class'], $atts['id'], $atts['name'], $atts['value'], ( isset( $atts['checked'] ) ? "checked=".$atts['checked'] : '') );
			if ( array_key_exists( 'description', $atts ) ){
				$html .= sprintf( '<p class="description">%1$s</p>', $atts['description'] );
			}
			echo $html;
		}


		/**
		 * Input type Text
		 */
		if ( $atts['type'] == 'text' ) {
			$html = sprintf( '<input type="%1$s" class="%2$s" id="%3$s" name="%4$s" value="%5$s"/>', $atts['type'], $atts['class'], $atts['id'], $atts['name'], $atts['value'] );
			if ( array_key_exists( 'description', $atts ) ){
				$html .= sprintf( '<p class="description">%1$s</p>', $atts['description'] );
			}
			echo $html;
		}


		/**
		 * Input type Textarea
		 */
		 if ( $atts['type'] == 'textarea' ) {
 			$html = sprintf( '<textarea cols="60" rows="5" class="%1$s" id="%2$s" name="%3$s">%4$s</textarea>', $atts['class'], $atts['id'], $atts['name'], $atts['value'] );
 			if ( array_key_exists( 'description', $atts ) ){
 				$html .= sprintf( '<p class="description">%1$s</p>', $atts['description'] );
 			}
 			echo $html;
 		}
	}


	/**
	 * Generate settings form
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function analyticstracker_options_page() {
		?>
		<div class="wrap">
			<form action='options.php' method='post'>
				<h1><?php _e( 'Google Analytics Settings', 'analytics-tracker' ); ?></h1>
				<?php settings_errors(); ?>
				<?php
					settings_fields( 'analyticstracker_page' );
					do_settings_sections( 'analyticstracker_page' );
					submit_button();
				?>
			</form>
		</div>
		<?php
	}


}
// Instantiate the main class
 new AnalyticsTracker();
?>
