<?php
/*
Plugin Name: Old Core Files
Plugin URI:  http://www.wp-tricks.co.il/old_core_files
Description: Old Core Files notifies the user when old core files which are due removal exist in the filesystem.
Version:     1.4
Author:      Maor Chasen
Author URI:  http://maorchasen.com
Text Domain: old-core-files
License:     GPL2+
*/

/**
 * =========================== DESCRIPTION ===========================
 * 
 * When core is being upgraded, usually some files are no longer used by WordPress, and they are set for removal.
 * On some occasions, PHP has no permissions to delete these files, and they stay on the server, possibly
 * exposing your site to attackers.
 *
 * =========================== OPTIONAL FEATURES TODO: ===============
 *
 * - Email administrator when old files were detected 
 * 	(most probably will happen right after an upgrade)
 *
 * =========================== IMPORTNAT THINGS TODO: ================
 *
 * - Caching (added by maor)
 *   Iteration through the filesystem is an expensive task. I'm thinking (maor) maybe we should
 * 	 cache the file lists with a presistent cache for a short period of time. Plus, we can add
 * 	 a button that allows the user to clear the cache, or in their word "re-scan" the file base.
 * 	 This can be a nice approach, where we do the first scan, cache the files for a long period of
 * 	 time. Then allow the user to do a rescan in the entire filebase.
 *
 * =========================== NOTES: =================================
 *
 * - Want to extend this plugin? Check out ocf-extension-example.php in the plugin's directory
 */



/**
 * Security check
 * Prevent direct access to the file.
 *
 * @since 1.3
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/**
 * Base OCF class.
 *
 * @since 1.0
 */
class Old_Core_Files {

	/**
	 * Holds the basename of the plugin.
	 *
	 * @access private
	 * @var string object
	 */
	private static $basename;

	/**
	 * The ID of the page, as set by WordPress
	 *
	 * @access private
	 * @var string
	 */
	private $page;

	/**
	 * The slug of the parent page.
	 *
	 * @access private
	 * @var string
	 */
	private $parent_slug = 'tools.php';

	/**
	 * The slug of the main page.
	 *
	 * @access private
	 * @var string
	 */
	private $page_slug = 'old-core-files';

	/**
	 * The capability required for viewing the main page.
	 *
	 * @access private
	 * @var string
	 */
	private $view_cap = 'manage_options';

	/**
	 * Temporary cache/pool of files groups.
	 *
	 * @access private
	 * @var string
	 */
	private $filtered_files_pool = array();

	/**
	 * Hold on to your seats!
	 *
	 * @since 1.0
	 */
	public function __construct() {

		// This plugin only runs in the admin, but we need it initialized on init
		add_action( 'init', array( $this, 'action_init' ) );

		/**
		 * @todo load plugin textdomain, is there a need for de/activation hooks?
		 */
		register_activation_hook( __FILE__, array( $this, 'activate' ) );

	}

	public function action_init() {

		if ( ! is_admin() )
			return;

		isset( self::$basename ) || self::$basename = plugin_basename( __FILE__ );

		// Add OCF action links
		add_filter( 'plugin_action_links_' . self::$basename, array( $this, 'action_links' ), 10, 2 );

		// Add OCF admin menu
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		// Add OCF meta boxes
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		// Allow the view to be placed elsewhere than tools.php
		$this->parent_slug = apply_filters( 'ocf_parent_slug', $this->parent_slug );

		// Hijack the default capability for viewing the page
		$this->view_cap = apply_filters( 'ocf_view_cap', $this->view_cap );

		// Load our textdomain to allow multilingual translations
		load_plugin_textdomain( 'old-core-files' );

	}

	/**
	 * Register action links for OCF
	 *
	 * @since 1.0
	 */
	public function action_links( $links, $file ) {

		$links[] = sprintf( '<a href="%s">%s</a>', menu_page_url( $this->page_slug, false ), __( 'Settings' /*, 'old-core-files'*/ ) );
		return $links;

	}

	/**
	 * Register menu item for OCF
	 *
	 * @since 1.0
	 */
	public function admin_menu() {

		$this->page = add_submenu_page(
			$this->parent_slug,
			__( 'Old Core Files', 'old-core-files' ),
			__( 'Old Core Files', 'old-core-files' ),
			$this->view_cap,
			$this->page_slug, 
			array( $this, 'dashboard_page' )
		);

		// Add callbacks for this screen only 
		add_action( "load-$this->page", array( $this, 'page_actions' ), 9 );
		add_action( "admin_footer-$this->page", array( $this,'footer_scripts' ) );

	}

	/**
	 * Triggers rendering of our metaboxes plus some layout configuration 
	 *
	 * @since 1.0
	 */
	public function page_actions() {

		do_action( "add_meta_boxes_$this->page", null );
		do_action( 'add_meta_boxes', $this->page, null );

		// User can choose between 1 or 2 columns (default 2) 
		add_screen_option( 'layout_columns', array(
			'max' 		=> 2, 
			'default' 	=> 2 
		) );

		// Enqueue WordPress' postbox script for handling the metaboxes 
		wp_enqueue_script( 'postbox' );

		// Initialize WP_Filesystem for use in our page
		$this->initialize_filesystem();

	}

	/**
	 * Prints the jQuery script to initiliaze the metaboxes
	 * Called on admin_footer-*
	 *
	 * @since 1.0
	*/
	public function footer_scripts() {
		?>
		<script>postboxes.add_postbox_toggles( pagenow );</script>
		<?php
	}

	/**
	 * Add ocf metaboxes.
	 *
	 * @since 1.0
	 */
	public function add_meta_boxes() {

		add_meta_box( 'list-files', __( 'Old Core Files', 'old-core-files' ), array( $this, 'metabox_list_files' ), $this->page, 'normal', 'high' );
		add_meta_box( 'about', __( 'About', 'old-core-files' ), array( $this, 'metabox_about' ), $this->page, 'side', 'high' );

		do_action( 'ocf_add_meta_boxes' );

	}

	private function initialize_filesystem() {

		global $wp_filesystem, $wp_version;

		// Require the file that stores $_old_files
		require_once ABSPATH . 'wp-admin/includes/update-core.php';

		/**
		 * Before we initialize WP_Filesystem, we should make sure the right
		 * filesystem transport is set
		 */
		add_filter( 'filesystem_method', array( $this, 'assert_filesystem_method' ), 10, 2 );

		// If $wp_filesystem isn't there, make it be there!
		if ( ! $wp_filesystem )
			WP_Filesystem();

	}

	/**
	 * Return files from $_old_files based on a fixed condition
	 *
	 * @since 1.0
	 * @param  string $condition The type of condition
	 * @return array Array of files based on the condition
	 */
	private function filter_old_files( $condition ) {

		global $wp_filesystem, $_old_files;

		/**
		 * Not sure why I had to add this. Maybe shuffling through the filesystem
		 * can take up more time than usual. Thought maybe we should cache the results
		 * but there is really no point in doing so, since data must be real-time.
		 */
		@set_time_limit( 300 );

		$path_to_wp = trailingslashit( $wp_filesystem->abspath() );
		$filtered_files = array();

		/**
		 * An idea - saving an array of files for every filter/condition creates
		 * duplicate entries across groups.
		 * My other idea is to map each file to its group. That instead of this:
		 *
		 *  - Main Container Array
		 *  	- existing (group)
		 *  		- wp-admin/import-rss.php
		 *  		- wp-admin/execute-pings.php
		 *  		- wp-images/get-firefox.png
		 *  	- all (group)
		 *  		- ...
		 *  	- extensible (group)
		 *  		- wp-admin/import-rss.php
		 *  		- wp-admin/cat-js.php
		 *
		 * We can have:
		 *
		 * 	- Main Container Array
		 * 		- wp-admin/import-rss.php => array( 'existing', 'extensible' )
		 * 		- ...
		 */

		switch ( $condition ) {
			case 'existing':
				// Pile up old, existing files
				foreach ( $_old_files as $old_file )
					if ( $wp_filesystem->exists( $path_to_wp . $old_file ) )
						$filtered_files[] = $old_file;
				break;
			case 'all':
				$filtered_files = &$_old_files;
				break;
			default:
				// We don't want others changing core functions (the user expects them to work)
				// Here they can use their custom filtering method
				$filtered_files = apply_filters( "ocf_filter_files_$condition", $filtered_files, $_old_files );
		}
		// Add the results of the current filter to the main pool
		$this->filtered_files_pool[ $condition ] = $filtered_files;

	}

	private function iterate_groups( $groups ) {
		array_walk( $groups, array( $this, 'filter_old_files' ) );
	}

	private function count_group( $filter ) {
		return ( ! empty( $this->filtered_files_pool[ $filter ] ) ) ? count( $this->filtered_files_pool[ $filter ] ) : 0;
	}

	private function get_files_group( $group ) {
		return ( ! empty( $this->filtered_files_pool[ $group ] ) ) ? $this->filtered_files_pool[ $group ] : array();
	}

	/**
	 * Magic happens right here.
	 *
	 * @since 1.0
	 */
	public function metabox_list_files() {

		$allowed_filters = apply_filters( 'ocf_filter_methods', array(
			'existing' => __( 'Existing', 'old-core-files' ),
			'all' => __( 'All', 'old-core-files' ),
		) );
		$selected_filter = 'existing'; // default - should be variable

		if ( isset( $_GET['filter'] ) && array_key_exists( $_GET['filter'], $allowed_filters ) )
			$selected_filter = $_GET['filter'];
		
		// Collect counters
		$this->iterate_groups( array_keys( $allowed_filters ) );
		?>
		<ul class="subsubsub">
			<?php foreach ( $allowed_filters as $filter => $label ) :
					$css_class = ( $filter == $selected_filter ) ? 'current' : 'inactive'; 
			?>
			<li class="<?php echo sanitize_html_class( $filter ); ?>">
				<a class="<?php echo $css_class; ?>" href="<?php echo esc_url( add_query_arg( 'filter', $filter ) ); ?>"><?php echo esc_html( $label ); ?> <span class="count">(<?php echo absint( $this->count_group( $filter ) ); ?>)</span></a>
				<?php echo $label == end( $allowed_filters ) ? '' : ' |'; // don't display seperator at the end ?>
			</li>
			<?php endforeach; ?>
		</ul>
		
		<br class="clear" />

		<?php
		// Get list of files for the selected group
		$files_to_list = $this->get_files_group( $selected_filter );

		/**
		 * @todo consider using WP_List_Table instead of arbitrary HTML
		 */
		if ( ! empty( $files_to_list ) ) :
			?>
			<p><?php esc_html_e( 'We have found some old files in this WordPress installation. Please review the files below.', 'old-core-files' ); ?></p>

			<table class="widefat" cellspacing="0">
				<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'File', 'old-core-files' ); ?></th>
						<!-- <th scope="col" class="action-links"><?php esc_html_e( 'Actions', 'old-core-files' ); ?></th> -->
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $files_to_list as $existing_file ) : ?>
						<tr>
							<td>
								<code><?php echo esc_html( $existing_file ); ?></code>
							</td>
							<!--
							<td class="action-links">
								<?php if ( current_user_can( $this->view_cap ) ) : // Double check befor allowing 'delete' action ?>
								<span class="trash">
									<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'delete', $existing_file ) ) ); /* Add nonce, Add 'action=delete', Add File name (for deletion) */ ?>"><?php echo __( 'Delete', 'old-core-files' ); ?></a>
								</span>
								<?php endif; ?>
							</td>
							-->
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<th>
							<?php printf( __( '%d files in total', 'old-core-files' ), count( $files_to_list ) ); ?>
						</th>
						<!--
						<th class="action-links">
							<?php if ( current_user_can( $this->view_cap ) ) : // Double check befor allowing 'delete' action ?>
							<span class="trash"><a class="button" href="<?php echo esc_url( add_query_arg( 'action', 'trash-all' ) ); /* Add nonce, Add 'action=delete', Add File name (for deletion) */ ?>"><?php echo __( 'Delete All', 'old-core-files' ); ?></a></span>
							<?php endif; ?>
						</th>
						-->
					</tr>
				</tfoot>
			</table><?php
		else: ?>
			<p><?php esc_html_e( 'Seems like there are no old files in your installation. Dont forget to delete old WordPress files after each upgrade.', 'old-core-files' ); ?></p>
			<?php
		endif;

	}

	public function metabox_about() {
		?>
		<h4><?php esc_html_e( 'What is this about?', 'old-core-files' ); ?></h4>
		<p><?php esc_html_e( 'When core is being upgraded, there are some files that are no longer needed by WordPress, and they are set for removal. On some occasions, PHP/Apache has no permissions to delete these files, and they stay on the server, potentially exposing your site to attacks (Not always the case, but who wants to keep old files anyway?).', 'old-core-files' ); ?></p>
		<?php
	}

	/**
	 * Main dashboard page. Can be found under the "tools" menu.
	 *
	 * @since 1.0
	 */
	public function dashboard_page() {
		?>
		<div class="wrap">

			<h1><?php echo esc_html__( 'Old Core Files', 'old-core-files' ); ?></h1>

			<?php
			// We can add a FAQ tab with the full list of files.
			/*
			<h2 class="nav-tab-wrapper">
				<a class="nav-tab nav-tab-active" href="<?php echo admin_url( $this->parent_slug.'?page='.$this->page_slug ); ?>"><?php echo __( 'Action', 'old-core-files' ); ?></a>
				<a class="nav-tab" href="<?php echo admin_url( $this->parent_slug.'?page='.$this->page_slug ); ?>"><?php echo __( 'FAQ', 'old-core-files' ); ?></a>
			</h2>
			*/
			?>

			<form name="oldfiles" method="post">
				<input type="hidden" name="action" value="some-action">
				<?php
				wp_nonce_field( 'some-action-nonce' );

				// Used for saving metaboxes state (close/open) and their order 
				wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
				wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
				?>

				<div id="poststuff">

					<div id="post-body" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>"> 

						<!-- We had the description of the plugin here, moved to the metabox.
						<div id="post-body-content"></div> -->

						<div id="postbox-container-1" class="postbox-container">
							<?php do_meta_boxes( '', 'side', null ); ?>
						</div>

						<div id="postbox-container-2" class="postbox-container">
							<?php do_meta_boxes( '', 'normal', null ); ?>
							<?php do_meta_boxes( '', 'advanced', null ); ?>
						</div>

					</div> <!-- #post-body -->
				
				 </div> <!-- #poststuff -->

			</form><!-- #oldfiles -->

		 </div><!-- .wrap -->
	<?php
	}

	/**
	 * Here is where we need to do a crazy calculation of
	 * what type of filesystem transport we should use
	 * to ensure the files get removed properly.
	 *
	 * @since 1.0
	 */
	public function assert_filesystem_method( $method, $args ) {
		return $method;
	}

	/**
	 * Activation hook.
	 *
	 * @since 1.0
	 */
	public function activate() {
		// Nothing to do here right now
	}

}

function ocf_load_old_core_files() {
	return new Old_Core_Files;
}
add_action( 'plugins_loaded', 'ocf_load_old_core_files' );