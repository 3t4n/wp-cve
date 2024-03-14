<?php

defined( 'ABSPATH' ) || die();

class Cookie_Notice_Consent_Admin {
	
	/**
	 * Constructor
	 */
	public function __construct( $instance ) {
		$this->cnc = $instance;
		// Add actions in init, since settings need to be loaded earlier
		add_action( 'init', array( $this, 'init_consent_actions' ) );
	}
	
	/**
	 * Run admin init actions
	 */
	public function init_consent_actions() {
		if( $this->cnc->settings->get_option( 'consent_settings', 'log_consents' ) )
			$this->add_ajax_actions();
		$this->add_plugin_settings_page();
		$this->add_plugin_settings_link();
	}
	
	/**
	 * Add ajax actions
	 */
	public function add_ajax_actions() {
		add_action( 'wp_ajax_save_cookie_consent', array( $this, 'ajax_save_cookie_consent' ) );
		add_action( 'wp_ajax_nopriv_save_cookie_consent', array( $this, 'ajax_save_cookie_consent' ) );
	}
	
	/**
	 * Ajax save cookie consent
	 */
	public function ajax_save_cookie_consent() {
		// Check referer if no caching in place
		if( ! ( defined( 'WP_CACHE' ) && WP_CACHE ) )
			check_ajax_referer( 'cookie_notice_consent' );
		// Decode posted data
		$data = json_decode( stripslashes( $_REQUEST['data'] ) );
		// Run logger
		$this->cnc->logger->add_cookie_consent_log( $data );
		exit;
	}
	
	/**
	 * Add plugin settings page
	 */
	public function add_plugin_settings_page() {
		add_action( 'admin_menu', array( $this, 'add_options_page' ), 98 );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_scripts' ), 98 );
	}
	
	/**
	 * Add submenu page
	 */
	public function add_options_page() {
		add_submenu_page(
			'options-general.php',
			__( 'Cookie Notice & Consent Settings', 'cookie-notice-consent' ),
			__( 'Cookies', 'cookie-notice-consent' ),
			'manage_options',
			'cookie-notice-consent',
			array( $this, 'render_plugin_settings_page' ),
			98
		);
	}
	
	/**
	 * Render plugin settings page
	 */
	public function render_plugin_settings_page() {
		// Bail early if user is not allowed
		if( !current_user_can( 'manage_options' ) )
			return;
		
		// Get option groups as tabs
		$tabs = $this->cnc->helper->get_option_groups();
		
		// Add consent statistics tab only if logging is active
		if( $this->cnc->settings->get_option( 'consent_settings', 'log_consents' ) )
			$tabs['consent_statistics'] = __( 'Consent Statistics', 'cookie-notice-consent' );
		
		// Determine current tab
		$active_tab = ( isset( $_GET['tab'] ) && in_array( isset( $_GET['tab'] ), array_keys( $tabs ) ) ) ? $_GET['tab'] : array_key_first( $tabs );
		
		?>
		
		<div class="wrap cookie-notice-consent-page">
			
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<nav class="nav-tab-wrapper">
				<?php foreach( $tabs as $slug => $title ) { ?>
				<a href="?page=cookie-notice-consent&tab=<?php echo $slug; ?>" class="nav-tab<?php echo $slug == $active_tab ? ' nav-tab-active' : ''; ?>"><?php echo $title; ?></a>
				<?php } ?>
			</nav>
			
			<?php
			if( 'consent_statistics' == $active_tab ) {
				$this->render_consent_statistics();
			} else {
				$this->render_options( $active_tab );
			}
			?>
			
		</div>
		
		<?php
	}
	
	/**
	 * Enqueue admin styles and scripts
	 */
	public function add_admin_scripts( $hook ) {
		if( 'settings_page_cookie-notice-consent' == $hook ) {
			wp_enqueue_script( 'cookie-notice-consent', plugins_url( 'js/admin.js', dirname( __FILE__ ) ), array( 'jquery', 'wp-color-picker' ), CNC_VERSION );
			wp_enqueue_style( 'wp-color-picker' );
		}
		if( 'settings_page_cookie-notice-consent' == $hook || ( 'post.php' == $hook && 'cookie_consent' == get_current_screen()->post_type ) ) {
			wp_enqueue_style( 'cookie-notice-consent', plugins_url( 'css/admin.css', dirname( __FILE__ ) ), array(), CNC_VERSION );
		}
	}
	
	/**
	 * Render consent statistics section
	 */
	public function render_consent_statistics() {
		// Get latest consents
		$latest_consents = new WP_Query( array(
			'post_type' => 'cookie_consent',
			'posts_per_page' => 5
		) );
		// If there are any, output a list
		if( $latest_consents->have_posts() ) {
			?>
			<h2><?php _e( 'Latest consents', 'cookie-notice-consent' ); ?></h2>
			<table class="wp-list-table widefat striped">
				<thead>
					<tr>
						<th><?php _e( 'Date/Time', 'cookie-notice-consent' ); ?></th>
						<th><?php _e( 'UUID', 'cookie-notice-consent' ); ?></th>
						<th><?php _e( 'Cookie Categories', 'cookie-notice-consent' ); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php
				while( $latest_consents->have_posts() ) {
					$latest_consents->the_post();
					?>
					<tr>
						<td><?php echo get_the_date( get_option( 'date_format' ) ) . ' ' . get_the_time( get_option( 'time_format' ) ); ?></td>
						<td><?php the_title(); ?></td>
						<td><?php $this->cnc->helper->pretty_print_logged_categories( get_the_id() ); ?></td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<p><a href="<?php echo admin_url( 'edit.php?post_type=cookie_consent' ); ?>" class="button button-primary"><?php _e( 'View all consents', 'cookie-notice-consent'); ?></a></p>
			<?php
			// Build chart data
			$categories = array_merge( array( 'total' ), $this->cnc->helper->get_active_cookie_categories() );
			$timeframes = array( 7, 30 );
			$data = array();
			foreach( $timeframes as $timeframe ) {
				foreach( $categories as $category ) {
					$data[$timeframe][$category] = new WP_Query( array(
						'post_type' => 'cookie_consent',
						'fields' => 'ids',
						'date_query' => array(
							array(
								'after' => $timeframe . ' days ago',
							),
						),
						'meta_query' => 'total' != $category ? array(
							array(
								'field' => 'categories',
								'compare' => 'LIKE',
								'value' => $category,
							),
						) : null,
					) );
				}
			}
			// Output data as bar charts
			?>
			<h2 class="cnc__bar-chart__title"><?php echo _e( 'Cookie Categories', 'cookie-notice-consent' ); ?></h2>
			<?php
			foreach( $timeframes as $timeframe ) {
				$timeframe_total = $data[$timeframe]['total']->found_posts;
				?>
				<div class="cnc__bar-chart">
					<div class="cnc__bar-chart__legend">
						<?php printf( __( 'Last %s days', 'cookie-notice-consent' ), $timeframe ); ?>
						<span><?php printf( _n( '%s consent log', '%s consent logs', $timeframe_total, 'cookie-notice-consent' ), number_format_i18n( $timeframe_total ) ); ?></span>
					</div>
				<?php
				foreach( $categories as $category ) {
					if( 'total' == $category )
						continue;
					$category_total = $data[$timeframe][$category]->found_posts;
					$percentage = $timeframe_total > 0 ? round( $category_total / $timeframe_total * 100 ) : 0;
					?>
						<div class="cnc__bar-chart__bar" style="width:<?php echo $percentage; ?>%">
							<span class="cnc__bar-chart__label"><?php echo $this->cnc->settings->get_option( $category, 'label' ); ?></span>
							<span class="cnc__bar-chart__percentage"><?php echo $percentage; ?> %</span>
							<span class="cnc__bar-chart__value">(<?php echo number_format_i18n( $category_total ); ?>)</span>
						</div>
					<?php
				}
				?>
				</div>
				<?php
			}
		} else {
			?><p><?php _e( 'No data yet. Please check back later.', 'cookie-notice-consent' ); ?></p><?php
		}
	}
	
	/**
	 * Render plugin options section for given tab
	 */
	public function render_options( $tab ) {
		?>
		<form action="options.php" method="post">
			<?php
			settings_fields( "cookie_notice_consent_" . $tab . "_group" );
			do_settings_sections( "cookie_notice_consent_" . $tab . "_group" );
			submit_button( __( 'Save Changes', 'cookie-notice-consent' ) );
			?>
		</form>
		<?php
	}
	
	/**
	 * Add settings link via plugin action list filter
	 */
	public function add_plugin_settings_link() {
		add_filter( 'plugin_action_links', array( $this, 'settings_link_filter' ), 10, 2 );
	}
	
	/**
	 * Settings link function
	 */
	public function settings_link_filter( $plugin_actions, $plugin_file ) {
		$actions = array();
		if( basename( dirname( __DIR__ ) ) . '/cookie-notice-consent.php' === $plugin_file ) {
			$settings_url = add_query_arg( array( 'page' => 'cookie-notice-consent' ), admin_url( 'options-general.php' ) );
			$actions['settings'] = '<a href="' . $settings_url . '">' . __( 'Settings', 'cookie-notice-consent' ) . '</a>';
		}
		return array_merge( $actions, $plugin_actions );
	}
	
}
