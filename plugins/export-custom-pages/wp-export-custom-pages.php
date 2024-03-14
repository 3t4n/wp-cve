<?php
 /**
  * Export Custom Pages
  *
  * @package           WECP
  * @author            Mohammad Okfie
  * @copyright         2019 MOHD. OKFIE - Digital Nudhj Co.
  * @license           GPL-2.0-or-later
  *
  * @wordpress-plugin
  * Plugin Name:       Export Custom Pages
  * Plugin URI:        https://wordpress.org/plugins/export-custom-pages
  * Description:       This plugin to export custom pages you need like a template or whatever to import it anywhere.
  * Version:           1.0
  * Requires at least: 5.0
  * Requires PHP:      5.6
  * Author:            Mohammad Okfie
  * Author URI:        http://twitter.com/mohdokfie
  * Text Domain:       wp-export-custom-pages
	* Domain Path:			 /languages
  * License:           GPL v2 or later
  * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
  */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_Export_custom_pages' ) ) {

	/**
	 * Class WP_Export_custom_pages
	 *
	 * @class WP_Export_custom_pages
	 */
	class WP_Export_custom_pages {

		const WECP_VERSION = '1.0';

		/**
		 * Default Constructor
		 */
		public function __construct() {
			$this->WECP_constants();
			add_action( 'export_filters', array(&$this, 'export_filters' ) );
			add_filter( 'export_args', array(&$this, 'export_args' ) );
			add_action( 'admin_head', array(&$this, 'WECP_export_add_js' ) );
			add_filter( 'ts_deativate_plugin_questions', array( $this, 'WECP_deactivate_add_questions' ), 10, 1 );
			add_filter( 'ts_tracker_data', array( $this, 'WECP_ts_add_plugin_tracking_data' ), 10, 1 );
			add_filter( 'ts_tracker_opt_out_data', array( $this, 'WECP_get_data_for_opt_out' ), 10, 1 );
			add_action( 'admin_init', array( $this, 'WECP_admin_actions' ) );
			// Language Translation.
			add_action('init', array(&$this, 'WECP_update_po_file'));
		}

		/**
		 * Define required constants
		 */
		public function WECP_constants() {
			if ( ! defined( 'WECP_VERSION' ) ) {
					define( 'WECP_VERSION', '1.0' );
			}
		}

		/**
		 * This function will allowed customer to transalte the plugin string using .po and .pot file.
		 *
		 * @hook init
		 * @since 1.1
		 */
		public function WECP_update_po_file() {
			$domain = 'wp-export-custom-pages';
			$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
			$loaded = load_textdomain($domain, trailingslashit( WP_LANG_DIR ).$domain.'-'.$locale.'.mo');
			if ( $loaded ) {
				return $loaded;
			} else {
				load_plugin_textdomain( $domain , false, dirname(plugin_basename(__FILE__)) . '/languages/');
			}
		}

		 /*
		 * Display JavaScript on the page.
		 */
		public static function WECP_export_add_js() {
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($){
					var form = jQuery('#export-filters'),
						filters = form.find( '.export-filters' );
					filters.hide();
					jQuery( '.custom-page-IDs-filters' ).hide();
					jQuery( 'input[name=content]' ).change(function() {
						filters.slideUp( 'fast' );
						jQuery( '.custom-page-IDs-filters' ).slideUp( 'fast' );
						switch ( jQuery(this).val() ) {
							case 'custom_pages': console.log( 'HERE' ); jQuery('#custom-page-IDs-filters').slideDown(); break;
						}
					});
				});
			</script>
			<?php
		}

		/**
		 * Items option in export page.
		 */
		public function export_filters() {
			?>
			<p><label><input type="radio" name="content" value="custom_pages" /> <?php _e('Custom Page(s)', 'wp-export-custom-pages'); ?></label></p>
			<ul id="custom-page-IDs-filters" class="custom-page-IDs-filters">
				<li>
					<fieldset>
					<label class="label-responsive"><?php _e('Page(s): ', 'wp-export-custom-pages' ); ?></label>
					<select name="custom_pages_id[]" multiple style="height: 150px;width: 200px;">
						<?php
						$args = array(
						    'post_type'      => 'page',
						    'posts_per_page' => -1,
						    'order'          => 'DESC'
						 );
						 $pages = new WP_Query( $args );
						 if ( $pages->have_posts() ) :
							 while ( $pages->have_posts() ) : $pages->the_post();
						?>
						<option value="<?php the_ID(); ?>"><?php the_title(); ?></option>
						<?php endwhile; endif; wp_reset_postdata(); ?>
						<?php export_date_options( 'custom_pages' ); ?>
					</select>
					</fieldset>
				</li>
			</ul>
			<?php
		}

		/**
		 * Download XML file with data
		 *
		 * @param array $args Arguments.
		 */
		public static function export_args( $args ) {
			// phpcs:ignore WordPress.Security.NonceVerification
			if ( ! isset( $_GET['download'] ) || empty( $_GET['content'] ) || 'custom_pages' !== $_GET['content'] ) {
					return;
			}
			self::wp_export_custom_pages();
			exit;
		}

		/**
		 * Generates the WXR export file for download - This is a rip of export_wp but supports only exporting menus and it's terms
		 *
		 * @param array $args Filters defining what should be included in the export.
		 */
		public static function wp_export_custom_pages( $args = array() ) {
			global $wpdb, $post;
			$sitename = sanitize_key( get_bloginfo( 'name' ) );
			if ( ! empty( $sitename ) ) {
				$sitename .= '.';
			}
			$filename = $sitename . 'WordPress-' . gmdate( 'Y-m-d' ) . '.xml';
			foreach ($_GET['custom_pages_id'] as $id) {
				$page_ids[] = $id;
			}
			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );
			echo '<?xml version="1.0" encoding="' . esc_attr( get_bloginfo( 'charset' ) ) . "\" ?>\n";
			?>
<!-- This is a WordPress eXtended RSS file generated by WordPress as an export of your site. -->
<!-- It contains information about your site's posts, pages, comments, categories, and other content. -->
<!-- You may use this file to transfer that content from one site to another. -->
<!-- This file is not intended to serve as a complete backup of your site. -->

<!-- To import this information into a WordPress site follow these steps: -->
<!-- 1. Log in to that site as an administrator. -->
<!-- 2. Go to Tools: Import in the WordPress admin panel. -->
<!-- 3. Install the "WordPress" importer from the list. -->
<!-- 4. Activate & Run Importer. -->
<!-- 5. Upload this file using the form provided on that page. -->
<!-- 6. You will first be asked to map the authors in this export file to users -->
<!--    on the site. For each author, you may choose to map to an -->
<!--    existing user on the site or to create a new user. -->
<!-- 7. WordPress will then import each of the posts, pages, comments, categories, etc. -->
<!--    contained in this file into your site. -->

<?php the_generator( 'export' ); ?>
<rss version="2.0"
	xmlns:excerpt="http://wordpress.org/export/<?php echo '1.1'; ?>/excerpt/"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:wp="http://wordpress.org/export/<?php echo '1.1'; ?>/"
>
<channel>
	<title><?php bloginfo_rss( 'name' ); ?></title>
	<link><?php bloginfo_rss( 'url' ); ?></link>
	<description><?php bloginfo_rss( 'description' ); ?></description>
	<pubDate><?php echo esc_attr( gmdate( 'D, d M Y H:i:s +0000' ) ); ?></pubDate>
	<language><?php echo esc_attr( get_option( 'rss_language' ) ); ?></language>
	<wp:wxr_version><?php echo '1.1'; ?></wp:wxr_version>
	<wp:base_site_url><?php echo esc_url( self::WECP_site_url() ); ?></wp:base_site_url>
	<wp:base_blog_url><?php bloginfo_rss( 'url' ); ?></wp:base_blog_url>

			<?php self::WECP_page_terms_and_posts( $page_ids ); ?>

			<?php do_action( 'rss2_head' ); ?>

			<?php
			if ( $page_ids ) {
				global $wp_query;
				$wp_query->in_the_loop = true; // Fake being in the loop.
				// fetch 20 posts at a time rather than loading the entire table into memory.
				$next_posts = array_splice( $page_ids, 0, 20 );
				$count      = 0;
				if ( is_array( $next_posts ) && count( $next_posts ) > 0 ) {
					$count = count( $next_posts );
				}
				while ( $count > 0 ) {
					$posts = $wpdb->get_results(
						$wpdb->prepare(
							"SELECT * FROM {$wpdb->posts} WHERE ID IN ( %5s ) and {$wpdb->posts}.post_type = %s",
							join( ',', $next_posts ), 'page'
						)
					);// WPCS: db call ok, WPCS: cache ok.

					// Begin Loop.
					foreach ( $posts as $post ) {
						setup_postdata( $post );
						$is_sticky = is_sticky( $post->ID ) ? 1 : 0;
						?>
						<item>
							<title><?php echo esc_attr( apply_filters( 'the_title_rss', $post->post_title ) ); ?></title>
							<link><?php the_permalink_rss(); ?></link>
							<pubDate><?php echo esc_attr( mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ) ); ?></pubDate>
							<dc:creator><?php echo esc_attr( get_the_author_meta( 'login' ) ); ?></dc:creator>
							<guid isPermaLink="false"><?php esc_url( the_guid() ); ?></guid>
							<description></description>
							<content:encoded><?php echo self::WECP_cdata( apply_filters( 'the_content_export', $post->post_content ) ); ?></content:encoded>
							<excerpt:encoded><?php echo self::WECP_cdata( apply_filters( 'the_excerpt_export', $post->post_excerpt ) ); ?></excerpt:encoded>
							<wp:post_id><?php echo esc_attr( $post->ID ); ?></wp:post_id>
							<wp:post_date><?php echo esc_attr( $post->post_date ); ?></wp:post_date>
							<wp:post_date_gmt><?php echo esc_attr( $post->post_date_gmt ); ?></wp:post_date_gmt>
							<wp:comment_status><?php echo esc_attr( $post->comment_status ); ?></wp:comment_status>
							<wp:ping_status><?php echo esc_attr( $post->ping_status ); ?></wp:ping_status>
							<wp:post_name><?php echo esc_attr( $post->post_name ); ?></wp:post_name>
							<wp:status><?php echo esc_attr( $post->post_status ); ?></wp:status>
							<wp:post_parent><?php echo esc_attr( $post->post_parent ); ?></wp:post_parent>
							<wp:menu_order><?php echo esc_attr( $post->menu_order ); ?></wp:menu_order>
							<wp:post_type><?php echo esc_attr( $post->post_type ); ?></wp:post_type>
							<wp:post_password><?php echo esc_attr( $post->post_password ); ?></wp:post_password>
							<wp:is_sticky><?php echo esc_attr( $is_sticky ); ?></wp:is_sticky>
							<?php if ( 'attachment' === $post->post_type ) { ?>
								<wp:attachment_url><?php echo esc_url( wp_get_attachment_url( $post->ID ) ); ?></wp:attachment_url>
							<?php } ?>
							<?php self::WECP_post_taxonomy(); ?>
							<?php
							$postmeta = $wpdb->get_results(
								$wpdb->prepare(
									"SELECT * FROM $wpdb->postmeta WHERE post_id = %d",
									$post->ID
								)
							);// WPCS: db call ok, WPCS: cache ok.
							if ( $postmeta ) {
								foreach ( $postmeta as $meta ) {
									if ( '_edit_lock' !== $meta->meta_key ) {
										?>
										<wp:postmeta>
											<wp:meta_key><?php echo esc_attr( $meta->meta_key ); ?></wp:meta_key>
											<wp:meta_value><?php echo self::WECP_cdata( $meta->meta_value ); ?></wp:meta_value>
										</wp:postmeta>
										<?php
									}
								}
							}
							?>
							<?php
							$comments = $wpdb->get_results(
								$wpdb->prepare(
									"SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_approved <> 'spam'",
									$post->ID
								)
							); // WPCS: db call ok, WPCS: cache ok.
							if ( $comments ) {
								foreach ( $comments as $c ) {
									?>
								<wp:comment>
									<wp:comment_id><?php echo esc_attr( $c->comment_ID ); ?></wp:comment_id>
									<wp:comment_author><?php echo self::WECP_cdata( $c->comment_author ); ?></wp:comment_author>
									<wp:comment_author_email><?php echo esc_attr( $c->comment_author_email ); ?></wp:comment_author_email>
									<wp:comment_author_url><?php echo esc_url_raw( $c->comment_author_url ); ?></wp:comment_author_url>
									<wp:comment_author_IP><?php echo esc_attr( $c->comment_author_IP ); ?></wp:comment_author_IP>
									<wp:comment_date><?php echo esc_attr( $c->comment_date ); ?></wp:comment_date>
									<wp:comment_date_gmt><?php echo esc_attr( $c->comment_date_gmt ); ?></wp:comment_date_gmt>
									<wp:comment_content><?php echo self::WECP_cdata( $c->comment_content ); ?></wp:comment_content>
									<wp:comment_approved><?php echo esc_attr( $c->comment_approved ); ?></wp:comment_approved>
									<wp:comment_type><?php echo esc_attr( $c->comment_type ); ?></wp:comment_type>
									<wp:comment_parent><?php echo esc_attr( $c->comment_parent ); ?></wp:comment_parent>
									<wp:comment_user_id><?php echo esc_attr( $c->user_id ); ?></wp:comment_user_id>
								</wp:comment>
									<?php
								}
							}
							?>
					</item>
						<?php
					}
					$next_posts = array_splice( $page_ids, 0, 20 );
					if ( is_array( $next_posts ) && count( $next_posts ) > 0 ) {
						$count = count( $next_posts );
					} else {
						$count = 0;
					}
				}
			}
			?>
	</channel>
</rss>
			<?php
		}

		/**
		 * Wrap given string in XML CDATA tag.
		 *
		 * @since 2.1.0
		 *
		 * @param string $str String to wrap in XML CDATA tag.
		 */
		public static function WECP_cdata( $str ) {
			if ( false === seems_utf8( $str ) ) {
				$str = utf8_encode( $str );
			}

			$str = "<![CDATA[$str" . ( ( substr( $str, -1 ) === ']' ) ? ' ' : '' ) . ']]>';
			return $str;
		}

		/**
		 * Return the URL of the site
		 *
		 * @since 2.5.0
		 * @return string Site URL.
		 */
		public static function WECP_site_url() {
			// ms: the base url.
			if ( is_multisite() ) {
				return network_home_url();
			} else {
				return get_bloginfo_rss( 'url' );
			}
		}

		/**
		 * Output a term_name XML tag from a given term object
		 *
		 * @since 2.9.0
		 *
		 * @param object $term Term Object.
		 */
		public static function WECP_term_name( $term ) {
			if ( empty( $term->name ) ) {
				return;
			}

			echo '<wp:term_name>' . self::WECP_cdata( $term->name ) . '</wp:term_name>';
		}

		/**
		 * Get menu item terms and posts.
		 *
		 * @param array $page_ids Post IDs.
		 */
		public static function WECP_page_terms_and_posts( &$page_ids ) {
			$posts_to_add = array();
			foreach ( $page_ids as $page_id ) {
				$type = get_post_meta( $page_id, '_menu_item_type', true );
				if ( 'taxonomy' === $type ) {
					$tax       = get_post_meta( $page_id, '_menu_item_object', true );
					$object_id = get_post_meta( $page_id, '_menu_item_object_id', true );
					$term      = get_term( $object_id, $tax );
					echo "\t<wp:term><wp:term_id>" . esc_attr( $term->term_id ) . '</wp:term_id><wp:term_taxonomy>' . esc_attr( $tax ) . '</wp:term_taxonomy><wp:term_slug>' . esc_attr( $term->slug ) . '</wp:term_slug>';
					self::WECP_term_name( $term );
					echo "</wp:term>\n";
				} elseif ( 'post_type' === $type && in_array( get_post_meta( $page_id, '_menu_item_object', true ), array( 'page' ), true ) ) {
					$posts_to_add[] = get_post_meta( $page_id, '_menu_item_object_id', true );
				}
			}
			$page_ids = array_merge( $posts_to_add, $page_ids );
		}

		/**
		 * Output list of taxonomy terms, in XML tag format, associated with a post
		 *
		 * @since 2.3.0
		 */
		public static function WECP_post_taxonomy() {
			global $post;
			$taxonomies = get_object_taxonomies( $post->post_type );
			if ( empty( $taxonomies ) ) {
				return;
			}

			$terms = wp_get_object_terms( $post->ID, $taxonomies );
			foreach ( (array) $terms as $term ) {
				echo "\t\t<category domain=\"" . esc_attr( $term->taxonomy ) . '" nicename="' . esc_attr( $term->slug ) . '">' . self::WECP_cdata( $term->name ) . "</category>\n";
			}
		}

		/**
		 * Questions asked while deactivating the plugin.
		 *
		 * @param array $WECP_deactivate_questions Array to Questions.
		 */
		public function WECP_deactivate_add_questions( $WECP_deactivate_questions ) {

			$WECP_deactivate_questions = array(
				0 => array(
					'id'                => 4,
					'text'              => __( 'WordPress pages are not exported not getting exported!', 'wp-export-custom-pages' ),
					'input_type'        => '',
					'input_placeholder' => '',
				),

			);
			return $WECP_deactivate_questions;
		}

		/**
		 * We need to store the plugin version in DB, so we can show the welcome page and other contents.
		 */
		public function WECP_admin_actions() {
			$WECP_version_in_db = get_option( 'WECP_version' );
			if ( self::WECP_VERSION !== $WECP_version_in_db ) {
				update_option( 'WECP_version', self::WECP_VERSION );
			}
		}
		/**
		 * Plugin's data to be tracked when Allow option is choosed.
		 *
		 * @hook ts_tracker_data
		 *
		 * @param array $data Contains the data to be tracked.
		 *
		 * @return array Plugin's data to track.
		 */
		public static function WECP_ts_add_plugin_tracking_data( $data ) {
			if ( isset( $_GET['WECP_tracker_optin'] ) &&
				isset( $_GET['WECP_tracker_nonce'] ) &&
				wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['WECP_tracker_nonce'] ), 'WECP_tracker_optin' ) ) ) {
				$plugin_data['ts_meta_data_table_name'] = 'ts_tracking_WECP_meta_data';
				$plugin_data['ts_plugin_name']          = __('Export Custom Pages', 'wp-export-custom-pages');
				$plugin_data['ts_plugin_description']          = __('This plugin to export custom pages you need like a template or whatever to import it anywhere.', 'wp-export-custom-pages');
				$plugin_data['ts_plugin_author']          = __('Mohammad Okfie', 'wp-export-custom-pages');
				/**
				 * Add Plugin data
				 */
				$plugin_data['WECP_plugin_version'] = self::WECP_VERSION;

				$plugin_data['WECP_allow_tracking'] = get_option( 'WECP_allow_tracking' );
				$data['plugin_data']               = $plugin_data;
			}
			return $data;
		}

		/**
		 * Tracking data to send when No, thanks. button is clicked.
		 *
		 * @hook ts_tracker_opt_out_data
		 *
		 * @param array $params Parameters to pass for tracking data.
		 *
		 * @return array Data to track when opted out.
		 */
		public static function WECP_get_data_for_opt_out( $params ) {
			$plugin_data['ts_meta_data_table_name'] = 'ts_tracking_WECP_meta_data';
			$plugin_data['ts_plugin_name']          = __('Export Custom Pages', 'wp-export-custom-pages');
			$plugin_data['ts_plugin_description']          = __('This plugin to export custom pages you need like a template or whatever to import it anywhere.', 'wp-export-custom-pages');
			$plugin_data['ts_plugin_author']          = __('Mohammad Okfie', 'wp-export-custom-pages');

			// Store count info.
			$params['plugin_data'] = $plugin_data;

			return $params;
		}
	}
	$WP_Export_custom_pages = new WP_Export_custom_pages();
}
