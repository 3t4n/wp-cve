<?php
/**
 * Plugin Name: Announcement Bar
 * Plugin URI: http://austin.passy.co/wordpress-plugins/announcement-bar
 * Description: A fixed position (header) HTML and jQuery pop-up announcemnet bar. <em>currently <strong>&alpha;</strong>lpha testing</em>
 * Version: 0.4.1
 * Author: Austin Passy
 * Author URI: http://austin.passy.co
 *
 * @copyright 2009 - 2015
 * @author Austin Passy
 * @link http://frostywebdesigns.com/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package AnnouncementBar
 */

if ( !class_exists( 'Announcement_Bar' ) ) {
	class Announcement_Bar {

		/** Singleton *************************************************************/
		private static $instance;

		const domain	= 'announcement-bar';
		const version	= '0.4';

		var $settings;

		/**
		 * Main Instance
		 *
		 * @staticvar 	array 	$instance
		 * @return 		The one true instance
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Announcement_Bar ) ) {
				self::$instance = new Announcement_Bar;
			}
			return self::$instance;
		}

		/**
		 * Sets up the Announcement_Bar plugin and loads files at the appropriate time.
		 *
		 * @since 0.2
		 */
		function __construct() {
			$this->register_init();

			/* Define constants */
			add_action( 'plugins_loaded',					array( $this, 'constants' ) );

			add_action( 'plugins_loaded',					array( $this, 'required' ) );
			add_action( 'admin_init',						array( $this, 'localize' ) );

			/* Print script */
			add_action( 'wp_enqueue_scripts',			array( $this, 'enqueue_script' ) );

			/* Print style */
			add_action( 'wp_enqueue_scripts',			array( $this, 'enqueue_style' ) );
			add_action( 'wp_ajax_announcment_bar_style',			array( $this, 'php_style' ) );
			add_action( 'wp_ajax_nopriv_announcment_bar_style',	array( $this, 'php_style' ) );

			/* Register post_types & multiple templates */
			add_action( 'init',							array( $this, 'register_post_type' ) );

			/* Column manager */
			add_filter( 'manage_posts_columns',			array( $this, 'columns' ), 10, 2 );
			add_action( 'manage_posts_custom_column',	array( $this, 'column_data' ), 10, 2 );

			/* Save the meta data */
			add_action( 'save_post',						array( $this, 'save_meta_box' ), 10, 2 );

			add_action( 'template_redirect',				array( $this, 'count_and_redirect' ) ) ;

			/* Add HTML */
			add_action( 'wp_footer',						array( $this, 'html' ), 999 );

			do_action( 'announcement_bar_loaded' );
		}

		private function register_init() {
			register_activation_hook( __FILE__,			array( 'Announcement_Bar', 'activate' ) );
			register_uninstall_hook( __FILE__,			array( 'Announcement_Bar', 'deactivate' ) );
		}

		public static function activate() {
			global $announcement_bar;

			$announcement_bar->register_post_type();
			flush_rewrite_rules();
		}

		public static function deactivate() {
			flush_rewrite_rules();
		}

		public function constants() {
			/* Set constant path to the Cleaner Gallery plugin directory. */
			define( 'ANNOUNCEMENT_BAR_DIR', plugin_dir_path( __FILE__ ) );
			define( 'ANNOUNCEMENT_BAR_ADMIN', trailingslashit( ANNOUNCEMENT_BAR_DIR ) . 'admin/' );

			/* Set constant path to the Cleaner Gallery plugin URL. */
			define( 'ANNOUNCEMENT_BAR_URL', plugin_dir_url( __FILE__ ) );
			define( 'ANNOUNCEMENT_BAR_CSS', ANNOUNCEMENT_BAR_URL . 'css/' );
			define( 'ANNOUNCEMENT_BAR_JS', ANNOUNCEMENT_BAR_URL . 'js/' );

			/* Set the post type */
			define( 'ANNOUNCEMENT_BAR_POST_TYPE', apply_filters( 'announcement_bar_post_type', 'announcement' ) );
		}

		public function required() {
			if ( is_admin() ) {
				require_once( trailingslashit( ANNOUNCEMENT_BAR_ADMIN ) . 'admin.php' );
			}
		}

		public function localize() {
			load_plugin_textdomain( self::domain, null, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Function for quickly grabbing settings for the plugin without having to call get_option()
		 * every time we need a setting.
		 *
		 * @since 0.1
		 */
		public static function get_setting( $option = '', $default = false ) {

			$options = get_option( 'announcement_bar_settings', array() );

			if ( isset( $options[$option] ) ) {
				return $options[$option];
			}

			return $default;
		}

		/**
		 * WordPress 3.x check
		 *
		 * @since 0.01
		 */
		public static function is_version( $version = '3.0' ) {
			global $wp_version;

			if ( version_compare( $wp_version, $version, '<' ) ) {
				return false;
			}
			return true;
		}

		/**
		 * Add script
		 * @since 0.01
		 */
		public function enqueue_script() {

			if ( !is_admin() && self::get_setting( 'activate' ) == true )
				wp_enqueue_script( self::domain, ANNOUNCEMENT_BAR_JS . 'announcement.js', array( 'jquery' ), self::version, true );
		}

		/**
		 * Add stylesheet
		 * @since 0.01
		 */
		public function enqueue_style() {

			if ( !is_admin() && self::get_setting( 'activate' ) == true )
				wp_enqueue_style( self::domain, add_query_arg( 'action', 'announcment_bar_style', esc_url( admin_url( 'admin-ajax.php' ) ) ), false, self::version, 'screen' );
		}

		public function php_style() {
			require_once( ANNOUNCEMENT_BAR_DIR . 'css/announcement.css.php' );
			exit;
		}

		/**
		 * Fire this during init
		 * @ref http://wordpress.pastebin.com/VCeaJBt8
		 * Thanks to @_mfields
		 */
		public function register_post_type() {

			$slug = sanitize_title_with_dashes( self::get_setting( 'slug' ) );

			if ( !empty( $slug ) )
				$rewrite['slug'] = $slug;

			/* Labels for the announcement post type. */
			$labels = array(
				'menu_name'			=> __( 'Announcements', self::domain ),
				'name'					=> __( 'Announcements', self::domain ),
				'singular_name'		=> __( 'Announcement', self::domain ),
				'add_new'				=> __( 'Add New', self::domain ),
				'add_new_item'			=> __( 'Add New Announcement', self::domain ),
				'edit'					=> __( 'Edit', self::domain ),
				'edit_item'			=> __( 'Edit an Announcement', self::domain ),
				'new_item'				=> __( 'New Announcement', self::domain ),
				'view'					=> __( 'View Announcements', self::domain ),
				'view_item'			=> __( 'View Announcement', self::domain ),
				'search_items'			=> __( 'Search Announcements', self::domain ),
				'not_found'			=> __( 'No Announcements found', self::domain ),
				'not_found_in_trash'	=> __( 'No Announcements found in Trash', self::domain ),
			);

			/* Arguments for the announcements post type. */
			$args = array(
				'labels'				=> $labels,
				'has_archive'			=> false,
				'capability_type'		=> 'post',
				'public'				=> true,
				'can_export'			=> true,
				'query_var'			=> true,
				'rewrite'				=> array( 'slug' => $slug, 'with_front' => false ),
				'menu_icon'			=> 'dashicons-megaphone',
				'supports'				=> array( 'title', 'entry-views' ),
				'register_meta_box_cb'=> array( $this, 'add_meta_box' ),
			);

			/* Register the announcements post type. */
			register_post_type( ANNOUNCEMENT_BAR_POST_TYPE, $args );
		}

		public function columns( $columns, $post_type ) {
			if ( ANNOUNCEMENT_BAR_POST_TYPE == $post_type ) {
				$columns = array(
					'cb'			=> '<input type="checkbox" />',
					'title'			=> 'Title', //So an edit link shows. :P
					'author'		=> 'Author',
					'link'			=> 'Link',
					'count'			=> 'Hits',
					'date'			=> 'Date'
				);
			}
			return $columns;
		}

		public function column_data( $column_name, $post_id ) {
			global $post_type, $post, $user;

			if ( ANNOUNCEMENT_BAR_POST_TYPE == $post_type ) {
				if( 'email' == $column_name ) :
					$email =  get_the_author_meta( $user_email, $userID );
					$default = '';
					$size = 40;
					$gravatar = 'http://www.gravatar.com/avatar/' . md5( strtolower( trim( $email ) ) ) . '?d=' . $default . '&s=' . $size;
					echo '<img alt="" src="'.$gravatar.'" />';
				elseif( 'link' == $column_name ) :
					$perm	= get_permalink( $post->ID );
					$url	= get_post_meta( $post->ID, '_announcement_link', true );
					//echo make_clickable( esc_url( $perm ? $perm : '' ) );
					echo '<a href="' . esc_url( $perm ) . '">' . esc_url( $url ? $url : $perm ) . '</a>';
				elseif( 'count' == $column_name ) :
					$count = get_post_meta( $post->ID, '_announcement_count', true );
					echo esc_html( $count ? $count : 0 );
				endif;
			}
		}

		/**
		 * Register the metaboxes
		 */
		public function add_meta_box() {
			add_meta_box( 'AnnouncementBar-meta-box', __( 'Announcement', self::domain ), array( $this, 'meta_box_settings' ), ANNOUNCEMENT_BAR_POST_TYPE, 'normal', 'default' );
		}

		/**
		 * The announcement metabox
		 */
		public function meta_box_settings() {
			global $post;

			$announcement	= get_post_meta( $post->ID, '_announcement_content', 	true );
			$count	= get_post_meta( $post->ID, '_announcement_count', 	true );
			$link	= get_post_meta( $post->ID, '_announcement_link', 	true ); ?>

			<input type="hidden" name="<?php echo 'announcement_meta_box_nonce'; ?>" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />
			<table class="form-table">
				<tr>
					<td style="width:10%;vertical-align:top"><label for="content"><?php _e( 'Content:', self::domain ); ?></label></td>
					<td colspan="3"><textarea name="_announcement_content" id="_announcement_content" rows="4" cols="80" tabindex="30" style="width:97%"><?php echo esc_html( $announcement ); ?></textarea>
                    <br />
					<span class="description"><?php _e( 'Please enter your plain text content here.', self::domain ); ?></span></td>
				</tr>
				<tr>
					<td style="width:10%;vertical-align:top"><label for="cite"><?php _e( 'Link:', self::domain ); ?></label></td>
					<td>
						<input type="text" name="_announcement_link" id="_announcement_link" value="<?php echo esc_url( $link ); ?>" size="30" tabindex="30" style="width:90%" />
                        <br />
						<?php $counter = isset( $post->ID ) ? $count : 0; ?>
						<span class="description"><?php echo sprintf( __( 'This URL has been accessed <strong>%d</strong> times.', self::domain ), esc_attr( $counter ) ); ?></span>
					</td>
				</tr>
			</table><!-- .form-table --><?php
		}

		/**
		 * Save the metabox aata
		 */
		public function save_meta_box( $post_id, $post ) {

			/* Make sure the form is valid. */
			if ( !isset( $_POST['announcement_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['announcement_meta_box_nonce'], basename( __FILE__ ) ) )
				return $post_id;

			// Is the user registered as a subscriber.
			if ( !current_user_can( 'publish_posts', $post_id ) )
				return $post_id;

			$meta['_announcement_content']	= esc_html( $_POST['_announcement_content'] );
			$meta['_announcement_link']		= esc_url( $_POST['_announcement_link'] );

			foreach ( $meta as $key => $value ) {
				if( $post->post_type == 'revision' )
					return;
				$value = implode( ',', (array)$value );
				if ( get_post_meta( $post_id, $key, FALSE ) ) {
					update_post_meta( $post_id, $key, $value );
				} else {
					add_post_meta( $post_id, $key, $value );
				}
				if ( !$value ) delete_post_meta( $post_id, $key );
			}
		}

		public function count_and_redirect() {

			if ( !is_singular( ANNOUNCEMENT_BAR_POST_TYPE ) )
				return;

			global $wp_query;

			// Update the count
			$count = (int) isset( $wp_query->post->ID ) ? get_post_meta( $wp_query->post->ID, '_announcement_count', true ) : 0;
			update_post_meta( $wp_query->post->ID, '_announcement_count', $count + 1 );

			// Handle the redirect
			$redirect = isset( $wp_query->post->ID ) ? get_post_meta( $wp_query->post->ID, '_announcement_link', true ) : '';

			if ( !empty( $redirect ) ) {
				wp_redirect( esc_url_raw( $redirect ), 301 );
				exit;
			}
			else {
				wp_redirect( home_url(), 302 );
				exit;
			}

		}

		/**
		 * Add the HTML
		 */
		public function html() {
			global $post;

			if ( self::get_setting( 'activate' ) == true ) {

				query_posts( array( 'post_type' => ANNOUNCEMENT_BAR_POST_TYPE, 'posts_per_page' => '1', 'orderby' => 'rand' ) ); ?>

				<div id="announcementbar-container" class="show-if-no-js">

					<div class="tab">
						<div class="toggle">
							<a class="open" title="<?php _e( 'Show panel', self::domain ); ?>" style="display: none;"><?php _e( '<span class="arrow">&darr;</span>', self::domain ); ?></a>
							<a class="close" title="<?php _e( 'Hide panel', self::domain ); ?>"><?php _e( '<span class="arrow">&uarr;</span>', self::domain ); ?></a>
						</div><!-- /.toggle -->
					</div><!-- /.tab -->

					<div id="announcementbar" class="show-if-no-js"><?php
						if ( have_posts() ) : while ( have_posts() ) : the_post();
							$content = get_post_meta( $post->ID, '_announcement_content', true );
							$thelink = get_post_meta( $post->ID, '_announcement_link', true );
							$prelink = get_permalink( $post->ID ); ?>

						<div id="announcementbar-<?php the_ID(); ?>" class="announcement">

							<p><?php echo wp_specialchars_decode( stripslashes( $content ), 1, 0, 1 );

							if ( $thelink ) echo '&nbsp;<a href="' . $prelink . '">' . $thelink . '</a>'; ?></p>

						</div>

                        <?php endwhile; else : ?>

                        <div id="announcementbar-0" class="announcement">

							<p><?php echo sprintf( __( 'Please add a <a href="%s">post</a>. Powered by <a href="%s">Announcement Bar</a>', self::domain ), admin_url( 'post-new.php?post_type=' . ANNOUNCEMENT_BAR_POST_TYPE  ), 'http://austin.passy.co/wordpress-plugins/announcement-bar' ); ?></p>

						</div>

						<?php endif; ?>

						<div class="branding">
							<a class="branding" href="http://austin.passy.co/wordpress-plugins/announcement-bar" rel="bookmark" title="Plugin by Austin &ldquo;Frosty&rdquo; Passy">&#9731;</a>
						</div><!-- /.branding -->

					</div><!-- /#announcementbar -->

				</div><!-- /#announcementbar-container --><?php
			}
		}

	}
};
$GLOBALS['announcement_bar'] = Announcement_Bar::instance();
