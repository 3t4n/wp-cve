<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://etracker.com
 * @since      1.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Backend;

use Etracker\Database\ReportingDataTable;
use Etracker\Plugin\CapabilityManager;
use Etracker\Util\Logger;
use Etracker\Reporting\Report\ReportConfigFilter\ReportConfigFilterFactory;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 *
	 * @access   private
	 *
	 * @var string $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 *
	 * @access   private
	 *
	 * @var string $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Callback for admin_enqueue_scripts action to enqueue additional css for admin interface.
	 *
	 * @return void
	 */
	public function enqueue_custom_admin_style() {
		wp_register_style( 'etracker_admin_css', plugins_url( $this->plugin_name . '/public/css/admin.css' ), false, $this->version );
		wp_enqueue_style( 'etracker_admin_css' );
	}

	/**
	 * Register plugin settings page.
	 *
	 * @since   1.0.0
	 */
	public function add_admin_menu() {
		add_options_page( 'etracker', 'etracker Analytics', 'manage_options', 'etracker', array( $this, 'options_page' ) );
	}

	/**
	 * Admin page with plugin settings.
	 *
	 * @since    1.0.0
	 */
	public function options_page() {
		// check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Get the active tab from the $_GET param.
		$default_tab = null;
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : $default_tab;
		// phpcs:enable

		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'etracker Analytics', 'etracker' ); ?></h1>
			<nav class="nav-tab-wrapper">
				<a href="?page=<?php echo esc_attr( $this->plugin_name ); ?>" class="nav-tab
					<?php
					if ( null === $tab ) :
						?>
					nav-tab-active<?php endif; ?>"><?php _e( 'Settings', 'etracker' ); ?></a>
				<a href="?page=<?php echo esc_attr( $this->plugin_name ); ?>&tab=log" class="nav-tab
					<?php
					if ( 'log' === $tab ) :
						?>
					nav-tab-active<?php endif; ?>"><?php _e( 'Logging', 'etracker' ); ?></a>
			</nav>

			<div class="tab-content">
				<?php
				switch ( $tab ) :
					case 'log':
						$this->logging_page_tab();
						break;
					default:
						?>
				<form action='options.php' method='post'>

						<?php
						settings_fields( 'pluginPage' );
						do_settings_sections( 'pluginPage' );
						submit_button();
						?>

				</form>
				<?php endswitch; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Register admin page settins for this plugin.
	 *
	 * @since       1.0.0
	 */
	public function settings_init() {
		register_setting(
			'pluginPage',
			'etracker_settings',
			array(
				'sanitize_callback' => array( $this, 'validate_options' ),
				'default'           => array(
					'etracker_block_cookies'   => 'true',
					'etracker_log_level'       => Logger::WARNING,
					'etracker_log_maxage_days' => 10,
					'etracker_fetch_report_by' => 'url',
				),
			)
		);

		add_settings_section(
			'etracker_pluginPage_section',
			__( 'General Settings', 'etracker' ),
			array( $this, 'settings_section_callback' ),
			'pluginPage'
		);

		add_settings_section(
			'etracker_pluginPage_reporting',
			__( 'Integrated Reporting', 'etracker' ),
			array( $this, 'settings_reporting_section_callback' ),
			'pluginPage'
		);

		add_settings_section(
			'etracker_pluginPage_signalize',
			__( 'Signalize', 'etracker' ),
			array( $this, 'settings_signalize_section_callback' ),
			'pluginPage'
		);

		add_settings_field(
			'etracker_secure_code',
			__( 'Account Key', 'etracker' ),
			array( $this, 'render_secure_code' ),
			'pluginPage',
			'etracker_pluginPage_section'
		);

		add_settings_field(
			'etracker_reporting_token',
			__( 'Access Token', 'etracker' ),
			array( $this, 'render_reporting_token' ),
			'pluginPage',
			'etracker_pluginPage_reporting'
		);

		add_settings_field(
			'etracker_reporting_timespan',
			__( 'Timespan', 'etracker' ),
			array( $this, 'render_reporting_timespan' ),
			'pluginPage',
			'etracker_pluginPage_reporting'
		);

		add_settings_field(
			'etracker_reporting_enabled_checkmark',
			__( 'Integrated Reporting enabled', 'etracker' ),
			array( $this, 'render_reporting_enabled_checkmark' ),
			'pluginPage',
			'etracker_pluginPage_reporting'
		);

		add_settings_field(
			'etracker_block_cookies',
			__( 'Cookieless Tracking', 'etracker' ),
			array( $this, 'render_block_cookies' ),
			'pluginPage',
			'etracker_pluginPage_section'
		);

		add_settings_field(
			'etracker_disable_et_pagename',
			__( 'Disable et_pagename', 'etracker' ),
			array( $this, 'render_disable_et_pagename' ),
			'pluginPage',
			'etracker_pluginPage_section'
		);

		add_settings_field(
			'etracker_signalize_ready',
			__( 'Signalize ready', 'etracker' ),
			array( $this, 'render_signalize_ready' ),
			'pluginPage',
			'etracker_pluginPage_signalize'
		);

		add_settings_field(
			'etracker_custom_attributes',
			__( 'Custom Attributes', 'etracker' ),
			array( $this, 'render_custom_attributes' ),
			'pluginPage',
			'etracker_pluginPage_section'
		);

		add_settings_field(
			'etracker_custom_tracking_domain',
			__( 'Custom Tracking Domain', 'etracker' ),
			array( $this, 'render_custom_tracking_domain' ),
			'pluginPage',
			'etracker_pluginPage_section'
		);
	}

	/**
	 * Validate setting values before processing them.
	 *
	 * @param array $options Options submitted by settings form.
	 *
	 * @return array Validated options
	 *
	 * @since   1.0.0
	 */
	public function validate_options( $options ) {
		$validated                                    = array();
		$validated['etracker_block_cookies']          = self::validate_boolean_option( $options, 'etracker_block_cookies' );
		$validated['etracker_secure_code']            = trim( $options['etracker_secure_code'] );
		$validated['etracker_disable_et_pagename']    = self::validate_boolean_option( $options, 'etracker_disable_et_pagename' );
		$validated['etracker_custom_attributes']      = $options['etracker_custom_attributes'];
		$validated['etracker_custom_tracking_domain'] = self::validate_domain_option( $options, 'etracker_custom_tracking_domain' );
		$validated['etracker_reporting_token']        = $options['etracker_reporting_token'];
		$validated['etracker_reporting_timespan']     = self::validate_timespan_option( $options, 'etracker_reporting_timespan' );

		return $validated;
	}

	/**
	 * Admin page settings description callback.
	 *
	 * @since   1.0.0
	 */
	public function settings_section_callback() {
		esc_html_e( 'This plugin activates etracker Analytics for your entire WordPress site.', 'etracker' );
		?>
		<p><?php esc_html_e( 'How to use this plugin', 'etracker' ); ?>:</p>
		<ol>
			<li><?php esc_html_e( 'Enter your etracker Account Key', 'etracker' ); ?></li>
			<li><?php esc_html_e( 'Choose either to enable or disable cookies by default', 'etracker' ); ?></li>
			<li><?php esc_html_e( 'Save your changes', 'etracker' ); ?></li>
		</ol>
		<?php
	}

	/**
	 * Admin page settings description callback for Signalize.
	 *
	 * @since   2.0.0
	 */
	public function settings_signalize_section_callback() {
		esc_html_e( 'Installing this plugin also prepares your website for our Signalize remarketing tool. Here you can see if your website is technically suitable for using Signalize.', 'etracker' );
		?>
		<?php
	}

	/**
	 * Admin page settings description callback for reporting.
	 *
	 * @since   2.0.0
	 */
	public function settings_reporting_section_callback() {
		esc_html_e( 'See etracker Analytics reporting data directly in your posts overview and pages overview by entering your individual Access Token below.', 'etracker' );
		?>
		<p><?php esc_html_e( 'Reporting data is automatically updated twice a day.', 'etracker' ); ?></p>
		<?php
	}

	/**
	 * Logging page tab with printed logging informations.
	 *
	 * @since 2.0.0
	 */
	public function logging_page_tab() {
		$l = new Logger();
		$c = new Cron( $this->plugin_name, $this->version );

		?>
		<h2><?php esc_html_e( 'Logging messages collected by this plugin.', 'etracker' ); ?></h2>
		<?php /* translators: %s: log level */ ?>
		<p><?php echo esc_html( sprintf( __( 'Current log level is %s.', 'etracker' ), $l->get_level_name() ) ); ?></p>
		<?php /* translators: %s: maxage */ ?>
		<p><?php echo esc_html( sprintf( __( 'Messages older than %d days will be deleted automatically.', 'etracker' ), $c->log_maxage_days() ) ); ?></p>

		<style>
			.column-priority {
				width: 10rem;
			}
			.column-time {
				width: 10rem;
			}
		</style>
		<?php
		$logviewer = new LogViewer();
		$logviewer->prepare_items();
		$logviewer->display();
	}

	/**
	 * Render reporting token settings form part for admin page.
	 *
	 * @since       2.0.0
	 */
	public function render_reporting_token() {
		$current_value = $this->get_etracker_setting_or_default( 'etracker_reporting_token', '' );
		?>
		<input type='password' name='etracker_settings[etracker_reporting_token]' value='<?php echo esc_attr( $current_value ); ?>'>
		<p class="description">
			<?php esc_html_e( 'Create your individual Access Token in your etracker Analytics account.', 'etracker' ); ?>
			<?php esc_html_e( 'Find more information here:', 'etracker' ); ?>
			<?php $domain = __( 'https://www.etracker.com/en/docs/integration-setup-2/cms-shop-plugins/wordpress/', 'etracker' ); ?>
			<a href='<?php echo esc_url( $domain ); ?>' target="_blank"><?php esc_html_e( 'etracker WordPress Documentation', 'etracker' ); ?></a>.
		</p>
		<?php
	}

	/**
	 * Render reporting timespan settings form part for admin page.
	 *
	 * @since       2.4.0
	 */
	public function render_reporting_timespan() {
		$current_value = $this->get_etracker_setting_or_default( 'etracker_reporting_timespan', 'last-30-days' );
		?>
		<select name='etracker_settings[etracker_reporting_timespan]'>
			<?php foreach ( ReportConfigFilterFactory::get_slugs() as $slug ) : ?>
				<?php $setting_description = ReportConfigFilterFactory::get_instance( $slug )->get_name(); ?>
			<option value="<?php echo esc_attr( $slug ); ?>"<?php echo ( $slug == $current_value ) ? 'selected' : ''; ?>><?php echo esc_html( $setting_description ); ?></option>
			<?php endforeach; ?>
		</select>
		<p class="description">
			<?php esc_html_e( 'Select your reporting timespan.', 'etracker' ); ?>
		</p>
		<?php
	}

	/**
	 * Render reporting enabled checkmark.
	 *
	 * @since       2.4.0
	 */
	public function render_reporting_enabled_checkmark() {
		$reporting_token      = $this->get_etracker_setting_or_default( 'etracker_reporting_token', '' );
		$reporting_data_table = new ReportingDataTable();

		if ( true === $reporting_data_table->is_ready() && ! empty( $reporting_token ) ) {
			?>
				<p id="etracker_reporting_enabled_checked">✅</p>
				<p class="description" id="etracker_reporting_enabled_description">
					<?php esc_html_e( 'etracker Analytics integrated reporting is enabled.', 'etracker' ); ?>
				</p>
					<?php
		} else {
			?>
				<p id="etracker_reporting_enabled_unchecked">❌</p>
				<p class="description" id="etracker_reporting_enabled_description">
					<?php esc_html_e( 'etracker Analytics integrated reporting is disabled.', 'etracker' ); ?>
				</p>
			<?php
		}
	}

	/**
	 * Render secure code settings form part for admin page.
	 *
	 * @since       1.0.0
	 */
	public function render_secure_code() {
		$current_value = $this->get_etracker_setting_or_default( 'etracker_secure_code', '' );
		?>
		<input type='text' name='etracker_settings[etracker_secure_code]' value='<?php echo esc_attr( $current_value ); ?>'>
		<p class="description">
			<?php esc_html_e( 'Activate the plugin by entering your individual Account Key.', 'etracker' ); ?>
			<a href="https://www.etracker.com/signup?etcc_cmp=eA%20Plugin&etcc_med=Pluginstore&etcc_grp=wordpress&etcc_ctv=pluginsettings" target="_blank"><?php esc_html_e( 'Signup for free.', 'etracker' ); ?></a>
		</p>
		<?php
	}

	/**
	 * Render block-cookies settings form part for admin page.
	 *
	 * @since       1.0.0
	 */
	public function render_block_cookies() {
		$current_value = $this->get_etracker_setting_or_default( 'etracker_block_cookies', false );
		?>
		<input type='checkbox' name='etracker_settings[etracker_block_cookies]' <?php checked( 'true', $current_value ); ?> value='true'>
		<p class="description">
			<?php esc_html_e( 'Set the default to disable cookies to comply with the EU regulations in accordance with GDPR and TTDSG.', 'etracker' ); ?>
		</p>
		<?php
	}

	/**
	 * Render disable_et_pagename settings form part for admin page.
	 *
	 * @since       1.3.0
	 */
	public function render_disable_et_pagename() {
		$current_value = $this->get_etracker_setting_or_default( 'etracker_disable_et_pagename', false );
		?>
		<input type='checkbox' name='etracker_settings[etracker_disable_et_pagename]' <?php checked( 'true', $current_value ); ?> value='true'>
		<p class="description">
			<?php esc_html_e( 'If enabled, automated page name registration setting from your etracker account will apply and no et_pagename option will be submitted. It is recommended to leave this option disabled.', 'etracker' ); ?>
		</p>
		<?php
	}

	/**
	 * Render signalize ready jQuery checker for admin page.
	 *
	 * @since       1.5.0
	 */
	public function render_signalize_ready() {
		?>
		<p id="etracker_signalize_ready_checked" style="display: none">✅</p>
		<p id="etracker_signalize_ready_unchecked" style="display: none">❌</p>
		<p class="description" id="etracker_signalize_ready_unknown">
			<?php esc_html_e( 'Signalize service worker integration will be checked in a moment.', 'etracker' ); ?>
		</p>
		<p class="description" id="etracker_signalize_ready_success" style="display: none">
			<?php esc_html_e( 'Your website is ready to use Signalize.', 'etracker' ); ?>
		</p>
		<p class="description" id="etracker_signalize_ready_failure" style="display: none">
			<?php esc_html_e( 'Your website is NOT ready to use Signalize. Try to disable and reenable the plugin etracker Analytics.', 'etracker' ); ?>
		</p>
		<script>
			jQuery(document).ready(function($) {
				$.ajax({
					type: 'GET',
					url: '/sw.js',
					dataType: 'text',
					success: function(data) {
						jQuery("#etracker_signalize_ready_unknown")[0].style.display = "none";
						jQuery("#etracker_signalize_ready_failure")[0].style.display = "none";
						jQuery("#etracker_signalize_ready_unchecked")[0].style.display = "none";
						jQuery("#etracker_signalize_ready_success")[0].style.display = null;
						jQuery("#etracker_signalize_ready_checked")[0].style.display = null;
					},
					error: function(xhr, status, error) {
						jQuery("#etracker_signalize_ready_unknown")[0].style.display = "none";
						jQuery("#etracker_signalize_ready_unchecked")[0].style.display = null;
						jQuery("#etracker_signalize_ready_failure")[0].style.display = null;
						jQuery("#etracker_signalize_ready_success")[0].style.display = "none";
						jQuery("#etracker_signalize_ready_checked")[0].style.display = "none";
					},
				});
			});
		</script>
		<?php
	}

	/**
	 * Render custom attributes settings form part for admin page.
	 *
	 * @since       1.7.0
	 */
	public function render_custom_attributes() {
		$current_value = $this->get_etracker_setting_or_default( 'etracker_custom_attributes', '' );
		?>
		<input size="75" type='text' name='etracker_settings[etracker_custom_attributes]' value='<?php echo esc_attr( $current_value ); ?>'>
		<p class="description">
			<?php esc_html_e( 'Attention: Incorrect custom attributes can affect the tracking. Please have a look at our recommended attributes here:', 'etracker' ); ?>
			<?php $domain = __( 'https://www.etracker.com/en/docs/integration-setup-2/cms-shop-plugins/wordpress/', 'etracker' ); ?>
			<a href='<?php echo esc_url( $domain ); ?>' target="_blank"><?php esc_html_e( 'etracker WordPress Documentation', 'etracker' ); ?></a>.
		</p>
		<?php
	}

	/**
	 * Render custom tracking domain setting form part for admin page.
	 *
	 * @since       1.7.0
	 */
	public function render_custom_tracking_domain() {
		$current_value = $this->get_etracker_setting_or_default( 'etracker_custom_tracking_domain', '' );
		?>
		<input type='text' name='etracker_settings[etracker_custom_tracking_domain]' pattern="^(http(s)?://)?((?=[a-z0-9-]{1,63}\.)[a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,63}$" value='<?php echo esc_attr( $current_value ); ?>'>
		<p class="description">
			<?php esc_html_e( 'This option should only be activated if an own tracking domain has been set up, otherwise there is a risk of counting failure.', 'etracker' ); ?>
			<?php $domain = __( 'https://www.etracker.com/en/docs/integration-setup-2/tracking-code-sdks/set-up-your-own-tracking-domain/', 'etracker' ); ?>
			<a href='<?php echo esc_url( $domain ); ?>' target="_blank"><?php esc_html_e( 'More information here', 'etracker' ); ?></a>.
		</p>
		<?php
	}

	/**
	 * Render settings link next to disable on plugin page.
	 *
	 * @param array $links Action links to be filtered.
	 *
	 * @return array $links
	 *
	 * @since       1.5.0
	 */
	public function add_settings_link( $links ) {
		// Build and escape the URL.
		$url = esc_url(
			add_query_arg(
				'page',
				'etracker',
				get_admin_url() . 'admin.php'
			)
		);
		// Create the link.
		$settings_link = "<a href='$url'>" . __( 'Settings', 'etracker' ) . '</a>';
		// Adds the link to the beginning of the array.
		array_unshift(
			$links,
			$settings_link
		);
		return $links;
	}

	/**
	 * Add rewrite rule to enable Signalize service worker.
	 *
	 * Adds rewrite rules required to provide Service Worker sw.js as DOC_ROOT.
	 *
	 * @param string $wp_rewrite \WP_Rewrite object.
	 *
	 * @since    1.5.0
	 */
	public function generate_rewrite_rules( $wp_rewrite ) {
		// Add signalize rewrite rules.
		$wp_rewrite->add_external_rule( 'sw\.js$', 'wp-content/plugins/etracker/public/js/sw.js' );
	}

	/**
	 * Adds the column headings for edit posts / pages overview.
	 *
	 * @param array $columns Already existing columns.
	 *
	 * @return array Array containing the column headings.
	 *
	 * @since       2.0.0
	 */
	public function column_heading( $columns ) {
		$added_columns = array();

		$reporting_data_table = new ReportingDataTable();

		if ( true === $reporting_data_table->is_ready() && CapabilityManager::current_user_can_read_reporting_figures() ) {
			$added_columns['etracker-unique_visits'] = '<span class="etracker-column-unique_visits etracker-column-header-has-tooltip" data-tooltip-text="' . esc_attr__( 'A visit is also called session. A visit includes all activities of the website visitor, eg. entry, visited subpage(s), events, purchases, etc. A visit by etracker ends after 30 minutes without activity of the visitor.', 'etracker' ) . '"><span class="screen-reader-text">etracker</span>&nbsp;' . __( 'Visits', 'etracker' ) . '</span></span>';
		}

		return array_merge( $columns, $added_columns );
	}

	/**
	 * Displays the column content for the given column.
	 *
	 * @param string $column_name Column to display the content for.
	 * @param int    $post_id     Post to display the column content for.
	 *
	 * @since       2.0.0
	 */
	public function column_content( $column_name, $post_id ) {
		global $wpdb;

		$reporting_data_table = new ReportingDataTable();

		$table_name = $reporting_data_table->get_table_name();

		switch ( $column_name ) {
			case 'etracker-unique_visits':
				$cache_key    = 'etracker-unique_visits_' . $post_id;
				$report_entry = wp_cache_get( $cache_key );

				if ( false === $report_entry ) {
					$report_entry = $wpdb->get_row(
						// phpcs:disable WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder
						$wpdb->prepare(
							'
								SELECT unique_visits, start_date, end_date
								FROM `%1$s`
								WHERE ID = %2$d
								LIMIT 1
							',
							array(
								$table_name,
								$post_id,
							)
						),
						ARRAY_A
						// phpcs:enable WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder
					); // WPCS: db call ok.

					wp_cache_set( $cache_key, $report_entry );
				}

				if ( ! $this->is_valid_report_entry( $report_entry ) ) {
					echo '<span class="etracker-figure etracker-figure-empty etracker-figure-has-tooltip" data-tooltip-text="' . esc_attr__( 'No reporting available.', 'etracker' ) . '">-</span>';
				} else {
					$this->render_report_entry_unique_visits( $report_entry );
				}

				return;
		}
	}

	/**
	 * Renders HTML of report_entry figure unique_visits.
	 *
	 * @see column_content
	 * @since 2.0.0
	 *
	 * @param array $report_entry Array with figure.
	 */
	public function render_report_entry_unique_visits( $report_entry ) {
		$figure     = $report_entry['unique_visits'];
		$start_date = $report_entry['start_date'];
		$end_date   = $report_entry['end_date'];

		$this->render_report_entry_figure( $figure, $start_date, $end_date );
	}

	/**
	 * Common function to render HTML of report_entry figure.
	 *
	 * @param int    $figure     Figure to be shown in cell.
	 * @param string $start_date Start date of report shown in tooltip.
	 * @param string $end_date   End date of report shown in tooltip.
	 *
	 * @return void
	 */
	public function render_report_entry_figure( $figure, $start_date, $end_date ) {
		$start = new \DateTime( $start_date );
		$end   = new \DateTime( $end_date );

		$localized_start_date = wp_date( get_option( 'date_format' ), $start->getTimestamp() );
		$localized_end_date   = wp_date( get_option( 'date_format' ), $end->getTimestamp() );

		?>
		<span
			class="etracker-figure etracker-figure-has-tooltip"
			data-tooltip-text="<?php echo esc_attr( sprintf( '%s - %s', $localized_start_date, $localized_end_date ) ); ?>"
		><?php echo esc_html( $figure ); ?></span>
		<?php
	}

	/**
	 * Indicates which of the etracker columns are sortable.
	 *
	 * @param array $columns Appended with their orderby variable.
	 *
	 * @return array Array containing the sortable columns.
	 */
	public function column_sort( $columns ) {
		$reporting_data_table = new ReportingDataTable();

		if ( true === $reporting_data_table->is_ready() ) {
			$columns['etracker-unique_visits'] = array( 'etracker-unique_visits', 'desc' );
		}

		return $columns;
	}

	/**
	 * Hides the etracker columns if the user hasn't chosen which columns to hide.
	 *
	 * @param array $hidden The hidden columns.
	 *
	 * @return array Array containing the columns to hide.
	 *
	 * @since       2.0.0
	 */
	public function column_hidden( $hidden ) {
		if ( ! is_array( $hidden ) ) {
			$hidden = array();
		}

		return $hidden;
	}

	/**
	 * Filters the JOIN clause of the query.
	 *
	 * @param string    $join  current JOIN string.
	 * @param \WP_Query $query \WP_Query object.
	 *
	 * @return string $join
	 *
	 * @since       2.0.0
	 */
	public function posts_join( string $join, \WP_Query $query ) {
		global $wpdb;

		$posts_table_name     = $wpdb->posts;
		$reporting_data_table = new ReportingDataTable();

		$table_name = $reporting_data_table->get_table_name();

		if ( true === $reporting_data_table->is_ready() ) {
			$join .= "
                LEFT JOIN
                    {$table_name}
                ON
                    ($posts_table_name.ID = {$table_name}.ID)
            ";
		}

		return $join;
	}

	/**
	 * Filters the ORDER BY clause of the query.
	 *
	 * @param string    $orderby current ORDER BY string.
	 * @param \WP_Query $query   \WP_Query object.
	 *
	 * @return string $orderby
	 *
	 * @since       2.0.0
	 */
	public function posts_orderby( string $orderby, \WP_Query $query ) {
		// only modify queries done by admin screen.
		if ( ! is_admin() ) {
			return $orderby;
		}

		$reporting_data_table = new ReportingDataTable();

		if ( false === $reporting_data_table->is_ready() ) {
			// ReportingDataTable is not ready.
			return $orderby;
		}

		$current_orderby = $query->get( 'orderby' );
		if ( 'etracker-unique_visits' == $current_orderby ) {
			$order = $query->get( 'order' );
			return $reporting_data_table->get_table_name() . '.unique_visits ' . $order;
		}

		return $orderby;
	}

	/**
	 * Is tracking enabled?
	 *
	 * Tells us, if an account key has been set or not. If yes, it means the
	 * tracklet code will be rendered within frontend.
	 *
	 * @since 2.0.0
	 *
	 * @return boolean Wether tracking is enabled or not.
	 */
	public function has_tracking_enabled(): bool {
		$has_tracking_enabled = ! empty( $this->get_etracker_setting_or_default( 'etracker_secure_code', '' ) );
		return $has_tracking_enabled;
	}

	/**
	 * Action to inform admins if tracking is not enabled.
	 *
	 * Action to renders an admin notification if tracking is not enabled
	 * because of missing settings.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function action_admin_notice_enable_tracking() {
		$whitelist_admin_pages = array( 'settings_page_etracker', 'dashboard', 'edit', 'plugins' );
		$admin_page            = get_current_screen();

		// Build and escape the settings URL.
		$settings_url = esc_url(
			add_query_arg(
				'page',
				'etracker',
				get_admin_url() . 'admin.php'
			)
		);

		$settings_link = "<a href='$settings_url'>" . esc_html__( 'Settings', 'etracker' ) . '</a>';

		if ( in_array( $admin_page->base, $whitelist_admin_pages ) && ! $this->has_tracking_enabled() ) :
			$message = sprintf(
				/* translators: %1$s: Settings link, %2$s: Account Key */
				esc_html__(
					'Your setup of etracker Analytics is almost done. Go to %1$s and insert your %2$s.',
					'etracker'
				),
				$settings_link,
				// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped

				'<i>' . esc_html__( 'Account Key', 'etracker' ) . '</i>'
			);

			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped

			?>
			<div id="etracker-notice-user-should-enable-tracking" class="notice notice-info is-dismissible">
				<p><?php echo $message; ?></p>
			</div>
			<?php
			// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
		endif;
	}

	/**
	 * Action to inform admins if integrated reporting is not enabled.
	 *
	 * Action to renders an admin notification if integrated reporting
	 * is not enabled because of missing settings.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function action_admin_notice_enable_integrated_reporting() {
		$whitelist_admin_pages = array( 'settings_page_etracker', 'edit' );
		$admin_page            = get_current_screen();
		$has_reporting_enabled = ! empty( $this->get_etracker_setting_or_default( 'etracker_reporting_token', '' ) );

		if ( in_array( $admin_page->base, $whitelist_admin_pages ) && ! $has_reporting_enabled && $this->has_tracking_enabled() ) :

			// Check if user has already dismissed the message.
			$actual = get_transient( 'etracker_notice_enable_integrated_reporting' );

			if ( 'dismissed' === $actual && 'settings_page_etracker' !== $admin_page->base ) {
				// Hide message if user already dismissed it AND user is not on settings_page_etracker.
				return;
			}

			$docs_domain = __( 'https://www.etracker.com/en/docs/integration-setup-2/cms-shop-plugins/wordpress/', 'etracker' );
			$message     = sprintf(
				/* translators: %s: documentation link */
				esc_html__(
					'etracker Analytics offers an integrated reporting. Click %s to see how to activate it.',
					'etracker'
				),
				'<a href=' . esc_url( $docs_domain ) . ' target="_blank">' . esc_html__( 'here', 'etracker' ) . '</a>'
			);
			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped

			?>
			<div id="etracker-notice-user-should-enable-integrated-reporting" class="notice notice-info is-dismissible">
				<p><?php echo $message; ?></p>
			</div>
			<script>
				jQuery(document).ready(function($) {

					$(document).on('click', '#etracker-notice-user-should-enable-integrated-reporting .notice-dismiss', function( event ) {

						data = {
							action : 'etracker_dismiss_notice_enable_integrated_reporting',
						};

						$.post(ajaxurl, data, function (response) {
							// console.log(response, 'DONE!');
						});

					});
				});
			</script>
			<?php
			// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
		endif;
	}

	/**
	 * Action called by `wp_ajax_etracker_dismiss_notice_enable_integrated_reporting` to mark etracker_notice_enable_integrated_reporting as dismissed.
	 *
	 * @return void
	 */
	public function action_dismiss_notice_enable_integrated_reporting() {
		\set_transient( 'etracker_notice_enable_integrated_reporting', 'dismissed', 30 * DAY_IN_SECONDS );
		wp_die();
	}

	/**
	 * Action to ask to fill customer polling form.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function action_admin_notice_request_customer_polling() {
		$actual = get_transient( 'etracker_customer_polling' );
		if ( 'requested' !== $actual ) {
			// Ignore request.
			return;
		}
		if ( true !== CapabilityManager::current_user_can_read_reporting_figures() ) {
			// We should only ask for customer polling if user has read reporting figures capabilities.
			return;
		}

		$whitelist_admin_pages = array( 'settings_page_etracker', 'edit' );
		$admin_page            = get_current_screen();
		$has_reporting_enabled = ! empty( $this->get_etracker_setting_or_default( 'etracker_reporting_token', '' ) );

		if ( in_array( $admin_page->base, $whitelist_admin_pages ) && $has_reporting_enabled && $this->has_tracking_enabled() ) :
			$poll_url = __( 'https://www.etracker.com/umfrage-wordpress-plugin/', 'etracker' );
			$message  = sprintf(
				/* translators: %s: poll link */
				esc_html__(
					'You have been using etracker Analytics for some days now. We would love to hear your feedback. Click %s to open our survey.',
					'etracker'
				),
				'<a href=' . esc_url( $poll_url ) . ' target="_blank">' . esc_html__( 'here', 'etracker' ) . '</a>'
			);
			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped

			?>
			<div id="etracker-notice-ask-for-customer-polling" class="notice notice-info is-dismissible">
				<p><?php echo $message; ?></p>
			</div>
			<script>
				jQuery(document).ready(function($) {

					$(document).on('click', '#etracker-notice-ask-for-customer-polling .notice-dismiss', function( event ) {

						data = {
							action : 'etracker_dismiss_customer_polling',
						};

						$.post(ajaxurl, data, function (response) {
							// console.log(response, 'DONE!');
						});

					});
				});
			</script>
			<?php
			// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
		endif;
	}

	/**
	 * Action called by `wp_ajax_etracker_dismiss_customer_polling` to mark customer polling as done.
	 *
	 * @return void
	 */
	public function action_dismiss_customer_polling() {
		\set_transient( 'etracker_customer_polling', 'done' );
		wp_die();
	}

	/**
	 * Returns etracker_settings value or false on error.
	 *
	 * @param string $setting Name of etracker_settings member to return.
	 * @param object $default Default value if setting does not exist.
	 *
	 * @return string Settings value.
	 *
	 * @since       1.8.5
	 */
	public function get_etracker_setting_or_default( string $setting, $default ) {
		$options = get_option( 'etracker_settings' );

		// get_option returns boolean false if option etracker_settings does not exist.
		if ( false === $options || ! is_array( $options ) ) {
			// Broken plugin options detected, delete them to start over with defaults.
			delete_option( 'etracker_settings' );
			return $default;
		}

		if ( false === array_key_exists( $setting, $options ) ) {
			// Setting not definied, return default.
			return $default;
		}

		// Setting $setting found, return it's value.
		return $options[ $setting ];
	}

	/**
	 * Validate boolean setting '$name' in $options supplied.
	 *
	 * @param array  $options Options submitted by settings form.
	 * @param string $name    Name of option array key of $options.
	 *
	 * @return string
	 *
	 * @since   1.8.5
	 */
	private static function validate_boolean_option( array $options, string $name ) {
		if ( ! array_key_exists( $name, $options ) ) {
			// Option was not set. Return default "false".
			return 'false';
		}
		if ( 'true' == $options[ $name ] ) {
			// Option was set to 'true'.
			return 'true';
		} else {
			// Option was set to 'false' or something other than 'true'.
			// Validate it to 'false' to get clean settings.
			return 'false';
		}
	}

	/**
	 * Validate custom tracking domain setting.
	 *
	 * @param array  $options Options submitted by settings form.
	 * @param string $name    Name of option array key of $options.
	 *
	 * @return string
	 *
	 * @since   2.3.0
	 */
	private static function validate_domain_option( array $options, string $name ) {
		if ( ! array_key_exists( $name, $options ) ) {
			// Option was not set. Return default "".
			return '';
		}

		$domain = $options[ $name ];
		$url    = $domain;

		// Temporarily add protocol for validation.
		if ( $domain && strpos( $domain, '//' ) === false ) {
			$url = 'https://' . $domain;
		}

		if ( filter_var( $url, FILTER_VALIDATE_URL ) !== false ) {
			return $domain;
		} else {
			return '';
		}
	}

	/**
	 * Gets a list of all post type objects to enable reporting for.
	 *
	 * Some post_types get registered after our plugin loader runs. In this case
	 * we need to write a ThirdParty-Integration to add there public post_type
	 * to our etracker_reporting_post_types.
	 *
	 * @since 2.1.0
	 *
	 * @return string[]|WP_Post_Type[] An array of post type names or objects.
	 */
	public function get_reporting_post_types() {
		$reporting_post_types = get_post_types( array( 'public' => true ) );
		/**
		 * Filter allows you to enable or disable etrackers integrated reporting for custom post types.
		 *
		 * Adding a filter to `etracker_reporting_post_types` allows you to enable
		 * etrackers integrated reporting for custom post types.
		 *
		 * Example usage:
		 *
		 * ```
		 * function my_theme_etracker_reporting_post_types( $reporting_post_types ) {
		 *  // Enable custom reporting for my_custom_post_type.
		 *  $reporting_post_types['my_custom_post_type'] = 'my_custom_post_type';
		 *  // Disable custom reporting for pages.
		 *  if ( ( $key = array_search( 'page', $reporting_post_types ) ) !== false ) {
		 *    unset( $reporting_post_types[$key] );
		 *  }
		 *  return $reporting_post_types;
		 * };
		 *
		 * add_filter( 'etracker_reporting_post_types', 'my_theme_etracker_reporting_post_types' );
		 * ```
		 *
		 * @since 2.1.0
		 *
		 * @param string[] $reporting_post_types List of post types to enable integrated reporting for.
		 */
		$reporting_post_types = (array) apply_filters( 'etracker_reporting_post_types', $reporting_post_types );
		return array_unique( $reporting_post_types );
	}

	/**
	 * Validate report_entry object.
	 *
	 * @param array $report_entry Report entry object to validate.
	 *
	 * @return boolean True if $report_entry is valid. If not, False.
	 */
	private function is_valid_report_entry( $report_entry ) {
		if ( is_null( $report_entry ) ) {
			return false;
		}

		if ( true !== is_array( $report_entry ) ) {
			return false;
		}

		if ( ! array_key_exists( 'unique_visits', $report_entry ) ) {
			return false;
		}

		if ( ! is_numeric( $report_entry['unique_visits'] ) ) {
			return false;
		}

		if ( ! array_key_exists( 'start_date', $report_entry ) ) {
			return false;
		}

		if ( ! array_key_exists( 'end_date', $report_entry ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Validate option with name $name is valid timespan setting.
	 *
	 * @param array  $options Options submitted by settings form.
	 * @param string $name    Name of option array key of $options.
	 *
	 * @return string
	 */
	private static function validate_timespan_option( array $options, string $name ) {
		if ( ! array_key_exists( $name, $options ) ) {
			// Option was not set. Return default "".
			return '';
		}

		$slug = $options[ $name ];

		if ( ReportConfigFilterFactory::has_filter_with_slug( $slug ) ) {
			return $slug;
		} else {
			return '';
		}
	}
}
