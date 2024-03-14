<?php
/*
Plugin Name: CP Related Posts
Plugin URI: http://wordpress.dwbooster.com/content-tools/related-posts
Version: 1.0.46
Text Domain: cp-related-posts
Author: CodePeople
Author URI: http://wordpress.dwbooster.com/content-tools/related-posts
Description: CP Related Posts is a plugin that displays related articles on your website, manually, or by the terms in the content, title or abstract, including the tags assigned to the articles.
*/

require dirname( __FILE__ ) . '/includes/tools.clss.php';
require_once dirname( __FILE__ ) . '/includes/banner.inc.php';
$codepeople_promote_banner_plugins['codepeople-related-posts'] = array(
	'plugin_name' => 'CP Related Posts',
	'plugin_url'  => 'https://wordpress.org/support/plugin/cp-related-posts/reviews/#new-post',
);

add_filter( 'option_sbp_settings', 'cprp_troubleshoot' );
if ( ! function_exists( 'cprp_troubleshoot' ) ) {
	function cprp_troubleshoot( $option ) {
		if ( ! is_admin() ) {
			// Solves a conflict caused by the "Speed Booster Pack" plugin
			if ( is_array( $option ) && isset( $option['jquery_to_footer'] ) ) {
				unset( $option['jquery_to_footer'] );
			}
		}
		return $option;
	} // End cprp_troubleshoot
}

// Default values

$cprp_default_settings = array(
	'title'                         => 'Related Posts',
	'number_of_posts'               => 5,
	'post_type'                     => array( 'page', 'post' ),

	'percentage_symbol'             => 'star',
	'available_symbols'             => array( 'star', 'frame', 'ball' ),
	'similarity'                    => 30,
	'taxonomies'                    => true,

	'selection_type'                => array(
		'manually'     => true,
		'by_user_tags' => true,
		'by_content'   => true,
	),

	'display_in_single'             => array(
		'activate'        => true,
		'show_thumbnail'  => true,
		'thumbnail_size'  => 'thumbnail',
		'show_percentage' => true,
		'show_excerpt'    => true,
		'excerpt_words'   => 50,
		'show_tags'       => true,
		'mode'            => 'list', // slider, thumbnail slider, list, column
	),

	'display_in_multiple'           => array(
		'activate'        => true,
		'display_in'      => array(
			'type'         => 'all', // possible values 'all', 'home', 'list'
			'exclude_home' => false, // Exclude related posts from homepage, valid when type=all
			'exclude_id'   => array(), // Exclude related posts from pages or posts with ID, valid when type=all
			'include_id'   => array(),  // Display related posts only on specific posts or pages, valid when type=list
		),
		'show_thumbnail'  => true,
		'thumbnail_size'  => 'thumbnail',
		'show_percentage' => true,
		'show_excerpt'    => true,
		'excerpt_words'   => 50,
		'show_tags'       => true,
		'mode'            => 'list', // slider, thumbnail slider, list, column
	),

	'google_adsense'                => false,
	'google_adsense_client'         => '',
	'google_adsense_unit'           => '',
	'google_adsense_index'          => 'beginning',
	'google_adsense_index_interval' => 3,
	'google_adsense_local_website'  => false,
	'borlabs_cookie_id'             => 'google-adsense',

);

add_action( 'init', 'cprp_init', 1 );
add_action( 'widgets_init', 'cprp_load_widgets' );
function cprp_init() {
	load_plugin_textdomain( 'cp-related-posts', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	add_shortcode( 'cp-related-posts', 'cprp_shortcode' );
} // End cprp_init

add_action( 'admin_init', 'cprp_admin_init' );
function cprp_admin_init() {
	global $wpdb, $last_processed, $cprp_tags_obj;

	if ( isset( $_REQUEST['cprp-action'] ) ) {
		switch ( strtolower( sanitize_text_field( wp_unslash( $_REQUEST['cprp-action'] ) ) ) ) {
			case 'extract-all':
				$cprp_tags_obj = new CPTagsExtractor();
				$query         = 'SELECT * FROM ' . $wpdb->prefix . "posts WHERE post_status = 'publish'";
				if ( isset( $_REQUEST['id'] ) ) {
					$query = $wpdb->prepare( $query . ' AND ID > %d', @intval( $_REQUEST['id'] ) );
				}
				$query .= ' ORDER BY ID';

				$results = $wpdb->get_results( $query );
				if ( count( $results ) ) {
					register_shutdown_function( 'cprp_shutdown' );
					print '<div style="text-align:center;"><h1>' . esc_html__( 'Processing Posts', 'cp-related-posts' ) . '</h1></div>';
					foreach ( $results as $post ) {
						$last_processed = $post->ID;
						cprp_process_post( $post );
					}
					exit;
				}

				print '<div style="text-align:center;"><h1>' . esc_html__( 'All Posts Processed', 'cp-related-posts' ) . '</h1></div>';
				exit;

			break;
			case 'extract-tags':
				$cprp_tags_obj = new CPTagsExtractor();
				if ( isset( $_REQUEST['id'] ) && isset( $_REQUEST['text'] ) ) {

					$obj                   = new stdClass();
					$obj->recommended_tags = array();

					if ( ! empty( $_REQUEST['text'] ) ) {
						$obj->recommended_tags = $cprp_tags_obj->get_tags( sanitize_text_field( wp_unslash( $_REQUEST['text'] ) ) );
					}

					$post = get_post( @intval( $_REQUEST['id'] ) );
					if ( ! empty( $post ) ) {
						$obj->recommended_tags = array_merge( $obj->recommended_tags, cprp_process_post( $post, false ) );
					}

					print json_encode( $obj );
				}
				exit;
			break;
			case 'get-post':
				global $wp_query;
				if ( isset( $_REQUEST['terms'] ) ) {
					$params = array(
						's'           => sanitize_text_field( wp_unslash( $_REQUEST['terms'] ) ),
						'showposts'   => -1,
						'post_type'   => cprp_get_settings( 'post_type' ),
						'post_status' => 'publish',
					);
					$wp_query->query( $params );
					foreach ( $wp_query->posts as $i => $post ) {
						$_thumbnail_url = get_the_post_thumbnail_url( $post );
						try {
							if ( $_thumbnail_url ) {
								$wp_query->posts[ $i ]->thumbnail_url = esc_attr( $_thumbnail_url );
							}
						} catch ( Exception $err ) {
							error_log( $err->getMessage() );
						}
					}
					@ob_clean();
					print json_encode( $wp_query->posts );
				}
				exit;
			break;
		}
	}

	$post_types = cprp_get_settings( 'post_type' );
	$form_title = esc_html__( 'Select the posts related and tags proposed', 'cp-related-posts' );
	foreach ( $post_types as $post_type ) {
		add_meta_box( 'cprp_related_post_form', $form_title, 'cprp_related_post_form', $post_type, 'normal' );
	}

	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'cprp_customization_links' );

	// Integration with Gutenberg Editor
	add_action( 'enqueue_block_editor_assets', 'cprp_gutenberg_editor' );
}

if ( ! function_exists( 'cprp_gutenberg_editor' ) ) {
	function cprp_gutenberg_editor() {
		wp_enqueue_script( 'cprp_gutenberg_script', plugin_dir_url( __FILE__ ) . 'scripts/cprp_gutenberg.js', array(), '1.0.46' );
	}
}

add_action( 'admin_menu', 'cprp_admin_menu' );
function cprp_admin_menu() {
	add_options_page( 'CP Related Posts', 'CP Related Posts', 'manage_options', 'cprp-settings', 'cprp_settings_page' );
} // End cprp_admin_menu

function cprp_settings_page() {
	print '<h1>CP Related Posts Settings</h1>';
	?>
	<p  style="border:1px solid #E6DB55;margin-bottom:10px;padding:5px;background-color: #FFFFE0;">
	<?php _e( 'For any question, go to the <a href="https://wordpress.org/support/plugin/cp-related-posts#new-post" target="_blank">support</a> and leave us a message.', 'cp-related-posts' ); ?><br/><br />
	<?php _e( 'If you want test the premium version of CP Related Posts go to the following links:<br/> <a href="https://demos.dwbooster.com/related-posts/wp-login.php" target="_blank">Administration area: Click to access the administration area demo</a><br/> <a href="https://demos.dwbooster.com/related-posts/" target="_blank">Public page: Click to access the CP Related Posts</a>', 'cp-related-posts' ); ?><br/><br />
	<?php _e( 'To get the premium version of CP Related Posts go to the following links:<br/> <a href="http://wordpress.dwbooster.com/content-tools/related-posts#download" target="_blank">CLICK HERE</a>', 'cp-related-posts' ); ?><br/><br />
	<a href="http://wordpress.dwbooster.com/content-tools/related-posts#download" target="_blank" style="font-size:1.3em">To upgrade your copy of the plugin click here</a>
	</p>
	<?php
	if ( isset( $_POST['cprp_settings'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cprp_settings'] ) ), plugin_basename( __FILE__ ) ) ) {
		$settings = array(
			'title'                         => '',
			'number_of_posts'               => 0,
			'similarity'                    => 30,
			'taxonomies'                    => true,
			'post_type'                     => array( 'post', 'page' ),
			'selection_type'                => array(
				'manually'     => true,
				'by_user_tags' => true,
				'by_content'   => true,
			),

			'display_in_single'             => array(
				'activate'        => false,
				'show_thumbnail'  => false,
				'thumbnail_size'  => 'thumbnail',
				'show_percentage' => false,
				'show_excerpt'    => false,
				'excerpt_words'   => 50,
				'show_tags'       => false,
				'mode'            => 'list',
			),

			'display_in_multiple'           => array(
				'activate'        => false,
				'display_in'      => array(
					'type'         => 'all',
					'exclude_home' => false,
					'exclude_id'   => array(),
					'include_id'   => array(),
				),
				'show_thumbnail'  => false,
				'thumbnail_size'  => 'thumbnail',
				'show_percentage' => false,
				'show_excerpt'    => false,
				'excerpt_words'   => 50,
				'show_tags'       => false,
				'mode'            => 'list',
			),

			'google_adsense'                => false,
			'google_adsense_client'         => '',
			'google_adsense_unit'           => '',
			'google_adsense_local_website'  => false,
			'google_adsense_index'          => 'beginning',
			'google_adsense_index_interval' => 3,
			'borlabs_cookie_id'             => 'google-adsense',
		);
		if ( isset( $_REQUEST['cprp_title'] ) ) {
			$settings['title'] = sanitize_text_field( wp_unslash( $_REQUEST['cprp_title'] ) );
		}
		if ( ! empty( $_REQUEST['cprp_number_of_posts'] ) ) {
			$settings['number_of_posts'] = is_numeric( $_REQUEST['cprp_number_of_posts'] ) ? intval( $_REQUEST['cprp_number_of_posts'] ) : 0;
		}
		if ( ! empty( $_REQUEST['cprp_similarity'] ) ) {
			$settings['similarity'] = is_numeric( $_REQUEST['cprp_similarity'] ) ? intval( $_REQUEST['cprp_similarity'] ) : 0;
		}
		$settings['taxonomies'] = isset( $_REQUEST['cprp_taxonomies'] ) ? true : false;
		if ( isset( $_REQUEST['cprp_display_in_single_activate'] ) ) {
			$settings['display_in_single']['activate'] = true;
		}
		if ( isset( $_REQUEST['cprp_display_in_single_show_thumbnail'] ) ) {
			$settings['display_in_single']['show_thumbnail'] = true;
		}
		if ( isset( $_REQUEST['cprp_display_in_single_thumbnail_size'] ) ) {
			$settings['display_in_single']['thumbnail_size'] = sanitize_text_field( wp_unslash( $_REQUEST['cprp_display_in_single_thumbnail_size'] ) );
		}
		if ( isset( $_REQUEST['cprp_display_in_single_show_percentage'] ) ) {
			$settings['display_in_single']['show_percentage'] = true;
		}
		if ( isset( $_REQUEST['cprp_display_in_single_show_excerpt'] ) ) {
			$settings['display_in_single']['show_excerpt'] = true;
		}
		if ( isset( $_REQUEST['cprp_display_in_single_excerpt_words'] ) ) {
			$settings['display_in_single']['excerpt_words'] = sanitize_text_field( wp_unslash( $_REQUEST['cprp_display_in_single_excerpt_words'] ) );
		}
		if ( isset( $_REQUEST['cprp_display_in_single_show_tags'] ) ) {
			$settings['display_in_single']['show_tags'] = true;
		}

		if ( isset( $_REQUEST['cprp_display_in_multiple_activate'] ) ) {
			$settings['display_in_multiple']['activate'] = true;
		}
		if ( isset( $_REQUEST['cprp_display_in_multiple_type'] ) ) {
			$settings['display_in_multiple']['display_in']['type'] = sanitize_text_field( wp_unslash( $_REQUEST['cprp_display_in_multiple_type'] ) );
		}
		if ( isset( $_REQUEST['cprp_display_in_multiple_exclude_home'] ) ) {
			$settings['display_in_multiple']['display_in']['exclude_home'] = true;
		}
		if ( ! empty( $_REQUEST['cprp_display_in_multiple_exclude_id'] ) ) {
			$settings['display_in_multiple']['display_in']['exclude_id'] = explode( ',', str_replace( ' ', '', sanitize_text_field( wp_unslash( $_REQUEST['cprp_display_in_multiple_exclude_id'] ) ) ) );
		}
		if ( ! empty( $_REQUEST['cprp_display_in_multiple_include_id'] ) ) {
			$settings['display_in_multiple']['display_in']['include_id'] = explode( ',', str_replace( ' ', '', sanitize_text_field( wp_unslash( $_REQUEST['cprp_display_in_multiple_include_id'] ) ) ) );
		}
		if ( isset( $_REQUEST['cprp_display_in_multiple_show_thumbnail'] ) ) {
			$settings['display_in_multiple']['show_thumbnail'] = true;
		}
		if ( isset( $_REQUEST['cprp_display_in_multiple_thumbnail_size'] ) ) {
			$settings['display_in_multiple']['thumbnail_size'] = sanitize_text_field( wp_unslash( $_REQUEST['cprp_display_in_multiple_thumbnail_size'] ) );
		}
		if ( isset( $_REQUEST['cprp_display_in_multiple_show_percentage'] ) ) {
			$settings['display_in_multiple']['show_percentage'] = true;
		}
		if ( isset( $_REQUEST['cprp_display_in_multiple_show_excerpt'] ) ) {
			$settings['display_in_multiple']['show_excerpt'] = true;
		}
		if ( isset( $_REQUEST['cprp_display_in_multiple_excerpt_words'] ) ) {
			$settings['display_in_multiple']['excerpt_words'] = sanitize_text_field( wp_unslash( $_REQUEST['cprp_display_in_multiple_excerpt_words'] ) );
		}
		if ( isset( $_REQUEST['cprp_display_in_multiple_show_tags'] ) ) {
			$settings['display_in_multiple']['show_tags'] = true;
		}

		$settings['google_adsense']                = isset( $_REQUEST['cprp_google_adsense'] ) ? true : false;
		$settings['google_adsense_client']         = isset( $_REQUEST['cprp_google_adsense_client'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cprp_google_adsense_client'] ) ) : '';
		$settings['google_adsense_unit']           = isset( $_REQUEST['cprp_google_adsense_unit'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cprp_google_adsense_unit'] ) ) : '';
		$settings['google_adsense_index']          = isset( $_REQUEST['cprp_google_adsense_index'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cprp_google_adsense_index'] ) ) : '';
		$settings['google_adsense_index_interval'] = isset( $_REQUEST['cprp_google_adsense_index_interval'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cprp_google_adsense_index_interval'] ) ) : '';
		$settings['google_adsense_local_website']  = isset( $_REQUEST['cprp_google_adsense_local_website'] ) ? true : false;
		$settings['borlabs_cookie_id']             = isset( $_REQUEST['cprp_borlabs_cookie_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cprp_borlabs_cookie_id'] ) ) : '';

		update_option( 'cprp_settings', $settings );
		echo '<div class="updated"><p><strong>' . esc_html__( 'Settings Updated', 'cp-related-posts' ) . '</strong></div>';
	}

	$display_modes = array( 'slider', 'thumbnail slider', 'list', 'column', 'accordion' );

	$images_dir      = plugin_dir_url( __FILE__ ) . 'images/';
	$list_of_symbols = cprp_get_settings( 'available_symbols' );

	?>
	<form method="post" action="<?php print esc_attr( cprp_site_url() ) . 'admin.php?page=cprp-settings'; ?>">
		<div class="postbox" style="margin: 5px 15px;">
			<h3 class='hndle' style="padding:5px;"><span><?php esc_html_e( 'Related Posts Settings', 'cp-related-posts' ); ?></span></h3>
			<div class="inside">
				<table class="form-table">
					<tr valign="top">
						<th><?php esc_html_e( 'Section title', 'cp-related-posts' ); ?></th>
						<td>
							<input type="text" name="cprp_title" value="<?php echo esc_attr( cprp_get_settings( 'title' ) ); ?>" style="width:150px;" />
							<em><?php esc_html_e( 'The title of the related posts section', 'cp-related-posts' ); ?></em>
						</td>
					</tr>

					<tr valign="top">
						<th><?php esc_html_e( 'Number of related posts', 'cp-related-posts' ); ?></th>
						<td>
							<input type="text" name="cprp_number_of_posts" value="<?php echo esc_attr( cprp_get_settings( 'number_of_posts' ) ); ?>" style="width:150px;" />
							<em><?php esc_html_e( 'Number of posts to display as related posts', 'cp-related-posts' ); ?></em>
						</td>
					</tr>

					<tr valign="top">
						<th><?php esc_html_e( 'Post types that allow related posts', 'cp-related-posts' ); ?></th>
						<td>
							<?php
								$post_types      = get_post_types( array( 'public' => true ), 'names' );
								$cprp_post_types = cprp_get_settings( 'post_type' );
							?>
							<select multiple size="3" style="width:150px;" DISABLED >
							<?php
							foreach ( $post_types as $post_type ) {
								$selected = ( in_array( $post_type, $cprp_post_types ) ) ? 'SELECTED' : '';
								print '<option value="' . esc_attr( $post_type ) . '" ' . $selected . '>' . esc_html( $post_type ) . '</option>';
							}
							?>
							</select>
							<em><?php esc_html_e( 'Select the posts types that will display related posts', 'cp-related-posts' ); ?></em>
							<p style="color:red;">To display related posts with custom post types will be required the premium version of plugin. Please, <a href="http://wordpress.dwbooster.com/content-tools/related-posts#download" target="_blank">CLICK HERE</a> to get the premium version of plugin</p>
						</td>
					</tr>

					<tr valign="top">
						<th><?php esc_html_e( 'Display percentage of similarity with the symbol', 'cp-related-posts' ); ?></th>
						<td>
					<?php
					foreach ( $list_of_symbols as $symbol ) {
						echo '<input type="radio" DISABLED value="' . esc_attr( $symbol ) . '" ' . ( ( cprp_get_settings( 'percentage_symbol' ) == $symbol ) ? 'CHECKED' : '' ) . ' />';
						for ( $i = 0; $i < 3; $i++ ) {
							echo '<img src="' . esc_attr( $images_dir . $symbol . '_on.png' ) . '" />';
						}
						for ( $i = 0; $i < 2; $i++ ) {
							echo '<img src="' . esc_attr( $images_dir . $symbol . '_off.png' ) . '" />';
						}
							echo '<br />';
					}
					?>
						<p style="color:red;">To use a different icon graphic to display the percentage of similarity, will be required the premium version of plugin. Please, <a href="http://wordpress.dwbooster.com/content-tools/related-posts#download" target="_blank">CLICK HERE</a>  to get the premium version of plugin</p>
						</td>
					</tr>

					<tr valign="top">
						<th><?php esc_html_e( 'Display related posts with a percentage of similarity bigger than', 'cp-related-posts' ); ?></th>
						<td>
							<input type="text" name="cprp_similarity" value="<?php echo esc_attr( cprp_get_settings( 'similarity' ) ); ?>" style="width:150px;" /> %
						</td>
					</tr>

					<tr valign="top">
						<th><?php esc_html_e( 'In addition to extracting the terms from the titles and contents, use the taxonomies', 'cp-related-posts' ); ?></th>
						<td>
							<input type="checkbox" name="cprp_taxonomies" <?php if ( cprp_get_settings( 'taxonomies' ) ) {
								echo 'CHECKED';} ?> />
						</td>
					</tr>
				</table>
				<div style="border: 1px solid #CCC; padding: 10px;" >
					<?php
						$cprp_display_in_single = cprp_get_settings( 'display_in_single' );
					?>
					<table class="form-table">
						<tr>
							<td colspan="2">
								<h2><?php esc_html_e( 'How to display related posts in single pages', 'cp-related-posts' ); ?></h2>
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Display related posts in single pages', 'cp-related-posts' ); ?></th>
							<td>
								<input type="checkbox" name="cprp_display_in_single_activate" <?php if ( isset( $cprp_display_in_single['activate'] ) && $cprp_display_in_single['activate'] ) {
									echo 'CHECKED';} ?> />
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Display featured images in related posts', 'cp-related-posts' ); ?></th>
							<td>
								<input type="checkbox" name="cprp_display_in_single_show_thumbnail" <?php if ( isset( $cprp_display_in_single['show_thumbnail'] ) && $cprp_display_in_single['show_thumbnail'] ) {
									echo 'CHECKED';} ?> />
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Size of featured images', 'cp-related-posts' ); ?></th>
							<td>
								<select name="cprp_display_in_single_thumbnail_size">
							<?php
								$intermediate_image_sizes = get_intermediate_image_sizes();
							foreach ( $intermediate_image_sizes as $image_size ) {
								echo '<option value="' . esc_attr( $image_size ) . '" ' . ( ( isset( $cprp_display_in_single['thumbnail_size'] ) && $cprp_display_in_single['thumbnail_size'] == $image_size ) ? 'SELECTED' : '' ) . '>' . esc_html( $image_size ) . '</option>';
							}
							?>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Display percentage of similarity', 'cp-related-posts' ); ?></th>
							<td>
								<input type="checkbox" name="cprp_display_in_single_show_percentage" <?php if ( isset( $cprp_display_in_single['show_percentage'] ) && $cprp_display_in_single['show_percentage'] ) {
									echo 'CHECKED';} ?> />
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Display excerpt of related posts', 'cp-related-posts' ); ?></th>
							<td>
								<input type="checkbox" name="cprp_display_in_single_show_excerpt" <?php if ( isset( $cprp_display_in_single['show_excerpt'] ) && $cprp_display_in_single['show_excerpt'] ) {
									echo 'CHECKED';} ?> />
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Number of words on posts excerpts', 'cp-related-posts' ); ?></th>
							<td>
								<input type="text" name="cprp_display_in_single_excerpt_words" value="<?php
									print esc_attr(
										( isset( $cprp_display_in_single['excerpt_words'] ) ) ? $cprp_display_in_single['excerpt_words'] : 50
									);
								?>" />
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Display related terms between related posts', 'cp-related-posts' ); ?></th>
							<td>
								<input type="checkbox" name="cprp_display_in_single_show_tags" <?php if ( isset( $cprp_display_in_single['show_tags'] ) && $cprp_display_in_single['show_tags'] ) {
									echo 'CHECKED';} ?> />
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Display mode', 'cp-related-posts' ); ?></th>
							<td>
								<select style="width:150px;" DISABLED >
								<?php
								foreach ( $display_modes as $mode ) {
									print '<option value="' . esc_attr( $mode ) . '" ' . ( ( $mode == $cprp_display_in_single['mode'] ) ? 'selected' : '' ) . '>' . esc_html( $mode ) . '</option>';
								}
								?>
								</select>
								<p style="color:red;">The premium version of plugin allows display the related posts with different formats like: slider, thumbnail slider, accordion or column. Please, <a href="http://wordpress.dwbooster.com/content-tools/related-posts#download" target="_blank">CLICK HERE</a>  to get the premium version of plugin</p>
							</td>
						</tr>
					</table>
				</div>
				<div style="border: 1px solid #CCC;margin-top:10px;padding:10px;" >
					<?php
						$cprp_display_in_multiple = cprp_get_settings( 'display_in_multiple' );
					?>
					<table class="form-table">
						<tr>
							<td colspan="2">
								<h2><?php esc_html_e( 'How to display related posts in multiple-posts pages', 'cp-related-posts' ); ?></h2>
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Display related posts in multiple-posts pages', 'cp-related-posts' ); ?></th>
							<td style="vertical-align:top;">
								<table style="width:100%;">
									<tr>
										<td style="vertical-align:top;">
											<input type="checkbox" name="cprp_display_in_multiple_activate" <?php if ( isset( $cprp_display_in_multiple['activate'] ) && $cprp_display_in_multiple['activate'] ) {
												echo 'CHECKED';} ?> />
										</td>
										<td style="vertical-align:top;padding:0 10px;">
											<table>
												<tr >
													<td  style="vertical-align:top;border-bottom:1px solid #CCC;">
														<input type="radio" name="cprp_display_in_multiple_type" value="all" <?php if ( ! isset( $cprp_display_in_multiple['display_in']['type'] ) || 'all' == $cprp_display_in_multiple['display_in']['type'] ) {
															echo 'CHECKED';} ?> > Display in all Multiple-post pages
													</td>
													<td style="vertical-align:top;border-bottom:1px solid #CCC;">
														<input type="checkbox" name="cprp_display_in_multiple_exclude_home" <?php if ( isset( $cprp_display_in_multiple['display_in']['exclude_home'] ) && $cprp_display_in_multiple['display_in']['exclude_home'] ) {
															echo 'CHECKED';} ?> > Exclude related posts from Homepage<br><br>
														Exclude from posts and pages with IDs <input type="text" name="cprp_display_in_multiple_exclude_id" value="<?php echo esc_attr( ( isset( $cprp_display_in_multiple['display_in']['exclude_id'] ) ) ? implode( ',', $cprp_display_in_multiple['display_in']['exclude_id'] ) : '' ); ?>"><br>(separated by comma ",")
													</td>
												</tr>
												<tr>
													<td colspan="2" style="vertical-align:top;border-bottom:1px solid #CCC;">
														<input type="radio" name="cprp_display_in_multiple_type" value="home" <?php if ( isset( $cprp_display_in_multiple['display_in']['type'] ) && 'home' == $cprp_display_in_multiple['display_in']['type'] ) {
															echo 'CHECKED';} ?> > Display in Homepage only
													</td>
												</tr>
												<tr>
													<td>
														<input type="radio" name="cprp_display_in_multiple_type" value="list" <?php if ( isset( $cprp_display_in_multiple['display_in']['type'] ) && 'list' == $cprp_display_in_multiple['display_in']['type'] ) {
															echo 'CHECKED';} ?> > Display in the following posts and pages
													</td>
													<td>
														Enter the IDs of posts and pages <input type="text" name="cprp_display_in_multiple_include_id" value="<?php echo esc_attr( ( isset( $cprp_display_in_multiple['display_in']['include_id'] ) ) ? implode( ',', $cprp_display_in_multiple['display_in']['include_id'] ) : '' ); ?>" ><br>(separated by comma ",")
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Display featured images in related posts', 'cp-related-posts' ); ?></th>
							<td>
								<input type="checkbox" name="cprp_display_in_multiple_show_thumbnail" <?php if ( isset( $cprp_display_in_multiple['show_thumbnail'] ) && $cprp_display_in_multiple['show_thumbnail'] ) {
									echo 'CHECKED';} ?> />
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Size of featured images', 'cp-related-posts' ); ?></th>
							<td>
								<select name="cprp_display_in_multiple_thumbnail_size">
							<?php
								$intermediate_image_sizes = get_intermediate_image_sizes();
							foreach ( $intermediate_image_sizes as $image_size ) {
								echo '<option value="' . esc_attr( $image_size ) . '" ' . ( ( isset( $cprp_display_in_multiple['thumbnail_size'] ) && $cprp_display_in_multiple['thumbnail_size'] == $image_size ) ? 'SELECTED' : '' ) . '>' . esc_html( $image_size ) . '</option>';
							}
							?>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Display percentage of similarity', 'cp-related-posts' ); ?></th>
							<td>
								<input type="checkbox" name="cprp_display_in_multiple_show_percentage" <?php if ( isset( $cprp_display_in_multiple['show_percentage'] ) && $cprp_display_in_multiple['show_percentage'] ) {
									echo 'CHECKED';} ?> />
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Display excerpt of related posts', 'cp-related-posts' ); ?></th>
							<td>
								<input type="checkbox" name="cprp_display_in_multiple_show_excerpt" <?php if ( isset( $cprp_display_in_multiple['show_excerpt'] ) && $cprp_display_in_multiple['show_excerpt'] ) {
									echo 'CHECKED';} ?> />
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Number of words on posts excerpts', 'cp-related-posts' ); ?></th>
							<td>
								<input type="text" name="cprp_display_in_multiple_excerpt_words" value="<?php
									print esc_attr(
										( isset( $cprp_display_in_multiple['excerpt_words'] ) ) ? $cprp_display_in_multiple['excerpt_words'] : 50
                                    );
								?>" />
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Display related terms between related posts', 'cp-related-posts' ); ?></th>
							<td>
								<input type="checkbox" name="cprp_display_in_multiple_show_tags" <?php if ( isset( $cprp_display_in_multiple['show_tags'] ) && $cprp_display_in_multiple['show_tags'] ) {
									echo 'CHECKED';} ?> />
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Display mode', 'cp-related-posts' ); ?></th>
							<td>
								<select DISABLED style="width:150px;" >
								<?php
								foreach ( $display_modes as $mode ) {
									print '<option value="' . esc_attr( $mode ) . '" ' . ( ( $mode == $cprp_display_in_multiple['mode'] ) ? 'SELECTED' : '' ) . ' >' . esc_html( $mode ) . '</option>';
								}
								?>
								</select>
								<p style="color:red;">The premium version of plugin allows display the related posts with different formats like: slider, thumbnail slider, accordion or column. Please, <a href="http://wordpress.dwbooster.com/content-tools/related-posts#download" target="_blank">CLICK HERE</a>  to get the premium version of plugin</p>
							</td>
						</tr>
					</table>
				</div>

				<div style="border: 1px solid #CCC;margin-top:10px;padding:10px;" >
					<table class="form-table">
						<tr>
							<td colspan="2">
								<h2><?php esc_html_e( 'Google Adsense Integration', 'cp-related-posts' ); ?> <?php esc_html_e( '(Experimental)', 'cp-related-posts' ); ?></h2>
								<p><?php esc_html_e( 'Includes ads into the list of related posts.', 'cp-related-posts' ); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Enabling Google Adsense', 'cp-related-posts' ); ?></th>
							<td><input type="checkbox" name="cprp_google_adsense" <?php if ( cprp_get_settings( 'google_adsense' ) ) {
								print 'CHECKED';} ?> /></td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'This is my localhost website', 'cp-related-posts' ); ?></th>
							<td><input type="checkbox" name="cprp_google_adsense_local_website" <?php if ( cprp_get_settings( 'google_adsense_local_website' ) ) {
								print 'CHECKED';} ?> />
							<i><?php esc_html_e( 'Tick the checkbox if you are testing Google Adsense in the local website copy', 'cp-related-posts' ); ?></i>
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Google Adsense Client', 'cp-related-posts' ); ?></th>
							<td><input type="text" name="cprp_google_adsense_client" value="<?php print esc_attr( cprp_get_settings( 'google_adsense_client' ) ); ?>" /><br>
							<div style="margin-top: 10px; padding: 10px; border-radius: 5px; border: 2px dashed #cf2d7f; display: inline-block;">
							&lt;ins class="adsbygoogle"<br>
								style="display:block"<br>
								data-ad-client="<span style="color:#cf2d7f; font-weight:bold;">ca-pub-XXXXXXXXXXXXX</span>"<br>
								data-ad-slot="XXXXXXX"<br>
								data-ad-format="auto"<br>
								data-full-width-responsive="true"&gt;&lt;/ins&gt;
							</div>
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Google Adsense Unit', 'cp-related-posts' ); ?></th>
							<td><input type="text" name="cprp_google_adsense_unit" value="<?php print esc_attr( cprp_get_settings( 'google_adsense_unit' ) ); ?>" /><br>
							<div style="margin-top: 10px; padding: 10px; border-radius: 5px; border: 2px dashed #cf2d7f; display: inline-block;">
							&lt;ins class="adsbygoogle"<br>
								style="display:block"<br>
								data-ad-client="ca-pub-XXXXXXXXXXXXX"<br>
								data-ad-slot="<span style="color:#cf2d7f; font-weight:bold;">XXXXXXX</span>"<br>
								data-ad-format="auto"<br>
								data-full-width-responsive="true"&gt;&lt;/ins&gt;
							</div>
							</td>
						</tr>
						<tr valign="top">
							<th></th>
							<td>
								<input type="radio" name="cprp_google_adsense_index" value="beginning" <?php
								if ( 'beginning' == cprp_get_settings( 'google_adsense_index' ) ) {
									print 'CHECKED';
								}
								?> /> <?php esc_html_e( 'Display the Ad at the beginning of related posts', 'cp-related-posts' ); ?> <br /><br />

								<input type="radio" name="cprp_google_adsense_index" value="end" <?php
								if ( 'end' == cprp_get_settings( 'google_adsense_index' ) ) {
									print 'CHECKED';
								}
								?> /> <?php esc_html_e( 'Display the Ad at the end of related posts', 'cp-related-posts' ); ?> <br /><br />

								<input type="radio" name="cprp_google_adsense_index" value="interval" <?php
								if ( 'interval' == cprp_get_settings( 'google_adsense_index' ) ) {
									print 'CHECKED';
								}
								?> /> <?php esc_html_e( 'Display Ads every X related posts', 'cp-related-posts' ); ?>
								<input type="number" name="cprp_google_adsense_index_interval" value="<?php
								if ( is_numeric( cprp_get_settings( 'google_adsense_index_interval' ) ) ) {
									print intval( cprp_get_settings( 'google_adsense_index_interval' ) );
								}
								?>">
							</td>
						</tr>
						<tr valign="top">
							<td colspan="2"><hr /></td>
						</tr>
						<tr valign="top">
							<td colspan="2">
								<p><?php esc_html_e( 'If you have installed Borlabs Cookie plugin...', 'cp-related-posts' ); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th><?php esc_html_e( 'Enter the  Borlabs Cookie Id', 'cp-related-posts' ); ?></th>
							<td><input type="text" name="cprp_borlabs_cookie_id" value="<?php print esc_attr( cprp_get_settings( 'borlabs_cookie_id' ) ); ?>" /></td>
						</tr>
					</table>
				</div>

				<?php wp_nonce_field( plugin_basename( __FILE__ ), 'cprp_settings' ); ?>
				<div class="submit">
					<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Update Settings', 'cp-related-posts' ); ?>" />
					<a class="button" href="<?php print esc_url( cprp_site_url() . '?cprp-action=extract-all' ); ?>" target="_blank"><?php esc_html_e( 'Process Previous Posts', 'cp-related-posts' ); ?></a>
				</div>
			</div>
		</div>
	</form>
	<?php
} // End cprp_settings_page

function cprp_related_post_form( $post ) {
	$cprp_tags = array();
	$tags      = array();

	if ( isset( $post ) ) {
		$cprp_exclude_from_posts = get_post_meta( $post->ID, 'cprp_exclude_from_posts', true );
		$cprp_hide_related_posts = get_post_meta( $post->ID, 'cprp_hide_related_posts', true );

		// Get cprp_tags
		$cprp_tags = get_post_meta( $post->ID, 'cprp_tags' );
		if ( ! empty( $cprp_tags ) ) {
			if ( is_string( $cprp_tags ) ) {
				$cprp_tags = unserialize( $cprp_tags );
			}
			$cprp_tags = $cprp_tags[0];
			if ( 'auto-draft' == $post->post_status && isset( $cprp_tags['auto'] ) && isset( $cprp_tags['draft'] ) ) {
				unset( $cprp_tags['auto'] );
				unset( $cprp_tags['draft'] );
			}
		}
	}

	?>
	<input type="hidden" name="i_am_cprp" value="1" />
	<p  style="border:1px solid #E6DB55;margin-bottom:10px;padding:5px;background-color: #FFFFE0;">
	<?php _e( 'For any issues with the plugin, go to our <a href="http://wordpress.dwbooster.com/contact-us" target="_blank">contact page</a> and leave us a message.', 'cp-related-posts' ); ?><br/><br />
	<?php _e( 'If you want test the premium version of CP Related Posts go to the following links:<br/> <a href="https://demos.dwbooster.com/related-posts/wp-login.php" target="_blank">Administration area: Click to access the administration area demo</a><br/> <a href="https://demos.dwbooster.com/related-posts/" target="_blank">Public page: Click to access the CP Related Posts</a>', 'cp-related-posts' ); ?><br/><br />
	<?php _e( 'To get the premium version of CP Related Posts go to the following links:<br/> <a href="http://wordpress.dwbooster.com/content-tools/related-posts#download" target="_blank">CLICK HERE</a>', 'cp-related-posts' ); ?>
	</p>
	<p>
	 <input type="checkbox" name="cprp_exclude_from_posts" <?php echo ( ( ! empty( $cprp_exclude_from_posts ) ) ? 'CHECKED' : '' ); ?> /> <?php esc_html_e( 'Exclude this post from others related posts', 'cp-related-posts' ); ?><br />
	 <input type="checkbox" name="cprp_hide_related_posts" <?php echo ( ( ! empty( $cprp_hide_related_posts ) ) ? 'CHECKED' : '' ); ?> /> <?php esc_html_e( 'Hide the related posts from this post', 'cp-related-posts' ); ?>
	</p>
	<p><?php esc_html_e( 'After complete the post writing, press the "Get recommended tags" button to get a list of possible tags determined by content, and select the most relevant tags', 'cp-related-posts' ); ?></p>
	<p style="border:1px solid #E6DB55;margin-bottom:10px;padding:5px;background-color: #FFFFE0; text-align:center;display:none;text-transform: uppercase;" id="cprp_tags_updated_message"><?php esc_html_e( 'The post tags were updated', 'cp-related-posts' ); ?></p>
	<div><input type="button" value="Get recommended tags" onclick="cprp_get_tags(<?php print esc_attr( $post->ID ); ?>);" /></div>
	<div style="width:100%; height:150px; overflow: auto; border: 1px solid #CCC;" id="cprp_tags">
	<?php
	foreach ( $cprp_tags as $tag => $count ) {
		$checked = '';
		if ( $count > 100 ) {
			$count = $count - 100;
		}
		print '<span style="border:1px solid #CCC;display:inline-block;padding:5px;margin:5px;"><input type="checkbox" name="cprp_tag[]" value="' . esc_attr( $tag ) . '"' . $checked . ' /> ' . esc_html( $tag ) . ' (' . esc_html( $count ) . ') </span>';
	}
	?>
	</div>
	<p><?php esc_html_e( 'If there is a post or page related directly to the current article, then it is possible associate both items manually. Search the related article, and press the "+" symbol.', 'cp-related-posts' ); ?></p>
	<div><input type="text" name="cprp_search" id="cprp_search" style="width: 300px;" /> <input type="button" value="Search" onclick="cprp_search_manually();" /></div>
	<div id="cprp_manually_added"><div class="cprp-section-title"><?php esc_html_e( 'Items manually related, press "-" symbol to remove item', 'cp-related-posts' ); ?></div><div class="cprp-container"><ul>
	<?php
		$cprp_manually_related = get_post_meta( $post->ID, 'cprp_manually_related' );
	if ( ! empty( $cprp_manually_related ) ) {
		if ( is_string( $cprp_manually_related ) ) {
			$cprp_manually_related = unserialize( $cprp_manually_related );
		}
		$cprp_manually_related = $cprp_manually_related[0];
	}
	foreach ( $cprp_manually_related as $post_id ) {
		$tmp_post = get_post( $post_id );
		print '<li><span class="cprp-hndl" onclick="cprp_remove_manually(this);">-</span><input type="hidden" name="cprp_manually[]" value="' . esc_attr( $post_id ) . '" />' . esc_html( $tmp_post->post_title ) . '</li>';
	}
	?>
	</ul></div></div>
	<div id="cprp_found"><div class="cprp-section-title"><?php esc_html_e( 'Items found, press "+" symbol to associate the item', 'cp-related-posts' ); ?></div><div class="cprp-container"></div></div>
	<?php
} // End cprp_related_post_form

function cprp_customization_links( $links ) {
	array_unshift(
		$links,
		'<a href="' . esc_attr( cprp_site_url() ) . 'admin.php?page=cprp-settings">' . esc_html__( 'Settings', 'cp-related-posts' ) . '</a>',
		'<a href="http://wordpress.dwbooster.com/contact-us" target="_blank">' . esc_html__( 'Request custom changes', 'cp-related-posts' ) . '</a>',
		'<a href="https://wordpress.org/support/plugin/cp-related-posts#new-post" target="_blank">' . esc_html__( 'Help', 'cp-related-posts' ) . '</a>'
	);
	return $links;
} // End cprp_customization_links

add_action( 'save_post', 'cprp_save' );
function cprp_save( $id ) {
	if ( ! isset( $_REQUEST['i_am_cprp'] ) ) {
		return;
	}
	global $cprp_tags_obj;

	$cprp_tags_obj  = new CPTagsExtractor();
	$post           = get_post( $id );
	$extracted_tags = cprp_process_post( $post );

	if ( isset( $_REQUEST['cprp_tag'] ) ) {
		$cprp_tag = $_REQUEST['cprp_tag'];
		if ( ! is_array( $cprp_tag ) ) {
			$cprp_tag = array( $cprp_tag );
		}
		$cprp_tag = array_map( 'sanitize_text_field', $cprp_tag );
		wp_set_object_terms( $id, $cprp_tag, 'post_tag', true );
	} elseif ( ! is_object_in_taxonomy( $post->post_type, 'post_tag' ) ) {
		wp_delete_object_term_relationships( $id, 'post_tag' );
	}

	if ( isset( $_REQUEST['cprp_manually'] ) ) {
		$cprp_manually = $_REQUEST['cprp_manually'];
		if ( is_array( $cprp_manually ) ) {
			$cprp_manually = array_map( 'sanitize_text_field', $cprp_manually );
		} else {
			$cprp_manually = sanitize_text_field( wp_unslash( $cprp_manually ) );
		}
		update_post_meta( $id, 'cprp_manually_related', $cprp_manually );
	} else {
		delete_post_meta( $id, 'cprp_manually_related' );
	}

	if ( isset( $_REQUEST['cprp_exclude_from_posts'] ) ) {
		update_post_meta( $id, 'cprp_exclude_from_posts', 1 );
	} else {
		delete_post_meta( $id, 'cprp_exclude_from_posts' );
	}

	if ( isset( $_REQUEST['cprp_hide_related_posts'] ) ) {
		update_post_meta( $id, 'cprp_hide_related_posts', 1 );
	} else {
		delete_post_meta( $id, 'cprp_hide_related_posts' );
	}

} // End cprp_save

add_action( 'admin_enqueue_scripts', 'cprp_load_admin_resources', 1 );
function cprp_load_admin_resources() {
	global $post;

	$post_types = cprp_get_settings( 'post_type' );
	if ( isset( $post ) && in_array( $post->post_type, $post_types ) ) {
		$plugin_dir_url = plugin_dir_url( __FILE__ );
		wp_enqueue_style( 'cprp_admin_style', $plugin_dir_url . 'styles/cprp_admin.css', array(), '1.0.46' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'cprp_admin_script', $plugin_dir_url . 'scripts/cprp_admin.js', array( 'jquery', 'jquery-ui-sortable' ), '1.0.46', true );
		wp_localize_script( 'cprp_admin_script', 'cprp', array( 'admin_url' => cprp_site_url() ) );
	}
} // End cprp_load_admin_resources

add_action( 'wp_enqueue_scripts', 'cprp_enqueue_scripts', 10, 1 );
function cprp_enqueue_scripts() {
	global $post;
	$plugin_dir_url = plugin_dir_url( __FILE__ );

	wp_enqueue_script( 'jquery' );

	if ( is_singular() ) {
		$display = cprp_get_settings( 'display_in_single' );
	} else {
		$display = cprp_get_settings( 'display_in_multiple' );
	}
	$dependencies = array( 'jquery' );

	$percentage_symbol = cprp_get_settings( 'percentage_symbol' );

	wp_enqueue_script( 'cprp_script', $plugin_dir_url . 'scripts/cprp.js', $dependencies, '1.0.46', true );
	wp_enqueue_style( 'cprp_style', $plugin_dir_url . 'styles/cprp.css', array(), '1.0.46' );
	wp_localize_script(
		'cprp_script',
		'cprp',
		array(
			'star_on'  => $plugin_dir_url . 'images/' . $percentage_symbol . '_on.png',
			'star_off' => $plugin_dir_url . 'images/' . $percentage_symbol . '_off.png',
		)
	);
} // End cprp_enqueue_scripts

add_filter( 'the_content', 'cprp_content', 99 );

if ( ! function_exists( 'cprp_shortcode' ) ) {
	/**
	 * Allows to insert the related posts in the place you want
	 *      Possible atts items
	 *          - post_id, if it is not passed the shortcode would be used $post global var.
	 *          - number, the number of related posts, by default would be used the number of related posts on settings.
	 *          - mode, the display mode, by default would be used the display mode on settings.
	 *          - posts, list of posts ids separated by comma, by default would be loaded the related posts.
	 */
	function cprp_shortcode( $atts ) {
		if ( empty( $atts ) ) {
			$atts = array();
		}
		$atts['shortcode'] = true;
		unset( $atts['mode'] ); // The free version includes only one mode
		return _cprp_content( '', $atts );
	} // End cprp_shortcode
}

if ( ! function_exists( 'cprp_display' ) ) {
	/**
	 * Return true/false depending on if the related posts should be displayed or not.
	 *      $page, single or multiple, depending on the number of entries in the web page.
	 *      $is_widget, the related posts are included by the WordPress widget.
	 */
	function cprp_display( $page, $is_widget = false ) {
		global $post;

		if ( 'single' == $page ) {
			$display = cprp_get_settings( 'display_in_single' );
			if ( ! $display['activate'] && ! $is_widget ) {
				return false;
			}
			$cprp_hide_related_posts = get_post_meta( $post->ID, 'cprp_hide_related_posts' );
			if ( ! empty( $cprp_hide_related_posts ) ) {
				return false;
			}
		} else {
			$display = cprp_get_settings( 'display_in_multiple' );
			if ( ! $display['activate'] ) {
				return false;
			}
			if ( ! empty( $display['display_in'] ) ) {
				if ( 'home' == $display['display_in']['type'] && ! ( is_home() || is_front_page() ) ) {
					return false;
				}
				if (
					'list' == $display['display_in']['type'] &&
					(
						empty( $display['display_in']['include_id'] ) ||
						(
							! is_category( $display['display_in']['include_id'] ) &&
							! is_tag( $display['display_in']['include_id'] )
						)
					)
				) {
					return false;
				}
				if (
					'all' == $display['display_in']['type'] &&
					(
						( $display['display_in']['exclude_home'] && ( is_home() || is_front_page() ) ) ||
						(
							! empty( $display['display_in']['exclude_id'] ) &&
							(
								is_category( $display['display_in']['exclude_id'] ) ||
								is_tag( $display['display_in']['exclude_id'] )
							)
						)
					)
				) {
					return false;
				}
			}
		}

		return true;
	} // End cprp_display
}

if ( ! function_exists( '_cprp_content' ) ) {
	/**
	 * Generates the HTML structure of the related posts.
	 */
	function _cprp_content( $the_content, $options = array() ) {
		global $wpdb;

		$str              = '';
		$related_posts    = array();
		$manually_related = array();
		$is_shortcode     = ! empty( $options['shortcode'] );

		if ( ! empty( $options['post_id'] ) ) {
			$post = get_post( @intval( $options['post_id'] ) );
		} else {
			global $post;
		}

		// Checks if the post_type is valid
		if (
			empty( $post ) ||
			( ! $is_shortcode && ! in_array( $post->post_type, cprp_get_settings( 'post_type' ) ) )
		) {
			return $str;
		}

		// Checks if the element is displayed on single or multiple page, and if the related posts are activated for it
		if ( $is_shortcode || is_singular() ) {
			$display = cprp_get_settings( 'display_in_single' );
			if ( ! $is_shortcode && ! cprp_display( 'single', ! empty( $options['mode'] ) ) ) {
				return $str;
			}
		} else {
			$display = cprp_get_settings( 'display_in_multiple' );
			if ( ! cprp_display( 'multiple' ) ) {
				return $str;
			}
		}

		$mode = ( ! empty( $options['mode'] ) ) ? $options['mode'] : $display['mode'];
		$mode = preg_replace( '/\s/', '-', $mode );

		if ( ! empty( $options['posts'] ) ) {
			$options['posts'] = preg_replace( '/[^\d\,]/', '', $options['posts'] );
			$options['posts'] = explode( ',', $options['posts'] );
			foreach ( $options['posts'] as $id ) {
				$r_post = get_post( @intval( $id ) );
				if ( $r_post ) {
					$r_post->percentage = 100;
					$related_posts[]    = $r_post;
				}
			}
		}

		if ( empty( $related_posts ) ) {
			$tags_arr = get_post_meta( $post->ID, 'cprp_tags', true );
			if ( ! empty( $tags_arr ) ) {
				if ( is_string( $tags_arr ) ) {
					$tags_arr = unserialize( $tags_arr );
				}
			} else {
				$tags_arr = array();
			}

			$selection_type = cprp_get_settings( 'selection_type' );

			// Get posts related manually to the current post
			if ( $selection_type['manually'] ) {
				$manually_related = get_post_meta( $post->ID, 'cprp_manually_related' );
				if ( ! empty( $manually_related ) ) {
					if ( is_string( $manually_related ) ) {
						$manually_related = unserialize( $manually_related );
					}
					$manually_related = $manually_related[0];
					foreach ( $manually_related as $id ) {
						$r_post                  = get_post( $id );
						$cprp_exclude_from_posts = get_post_meta( $id, 'cprp_exclude_from_posts' );
						if ( $r_post && empty( $cprp_exclude_from_posts ) ) {
							$r_post->percentage = 100;
							$r_post->matching   = array();
							if ( ! empty( $tags_arr ) ) {
								$post_tags = get_post_meta( $id, 'cprp_tags', true );
								if ( ! empty( $post_tags ) ) {
									if ( is_string( $post_tags ) ) {
										$post_tags = unserialize( $post_tags );
									}
									foreach ( $post_tags as $tag => $value ) {
										if ( isset( $tags_arr[ $tag ] ) ) {
											array_push( $r_post->matching, $tag );
										}
									}
								}
							}
							$related_posts[] = $r_post;
						}
					}
				}
			}

			if ( ! empty( $tags_arr ) ) {
				$query = '';

				$s = array_sum( $tags_arr );

				$tags = array_keys( $tags_arr );
				foreach ( $tags as $_k => $a ) {
					$tags[ $_k ] = esc_sql( $wpdb->esc_like( $a ) );
				}

				$post_types = cprp_get_settings( 'post_type' );
				foreach ( $post_types as $_k => $a ) {
					$post_types[ $_k ] = esc_sql( $a );
				}

				$query   = "SELECT DISTINCT posts.*, postmeta.meta_value FROM $wpdb->posts as posts, $wpdb->postmeta as postmeta WHERE posts.post_type IN ('" . implode( "','", $post_types ) . "') AND posts.post_status='publish' AND posts.ID = postmeta.post_id AND posts.ID <> " . $post->ID . " AND postmeta.meta_key = 'cprp_tags'"
				. ( ( cprp_get_settings( 'similarity' ) ) ? " AND (postmeta.meta_value LIKE '%" . implode( "%' OR postmeta.meta_value LIKE '%", $tags ) . "%')" : '' )
				. " AND postmeta.post_id NOT IN ( SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'cprp_exclude_from_posts')";
				$results = $wpdb->get_results( $query );

				if ( count( $results ) ) {

					$similarity = cprp_get_settings( 'similarity' ) / 100;

					foreach ( $results as $key => $result ) {
						if ( in_array( $result->ID, $manually_related ) ) {
							unset( $results[ $key ] );
							continue;
						}
						$c = 0;

						$post_tags = unserialize( $result->meta_value );
						if ( is_string( $post_tags ) ) {
							$post_tags = unserialize( $post_tags );
						}

						$t                = array_sum( $post_tags );
						$result->matching = array();
						foreach ( $post_tags as $tag => $value ) {
							if ( isset( $tags_arr[ $tag ] ) ) {
								array_push( $result->matching, $tag );
								$c += $tags_arr[ $tag ] + $value;
							}
						}
						$result->percentage = ( $s + $t > 0 ) ? round( $c / ( $s + $t ), 2 ) : 0;
						if ( $result->percentage < $similarity ) {
							unset( $results[ $key ] );
							continue;
						}
					}

					usort( $results, 'cprp_sort' );
					$related_posts = array_merge( $related_posts, $results );
				}
			}
		}

		$number_of_posts = ( ! empty( $options['number'] ) ) ? @intval( $options['number'] ) : cprp_get_settings( 'number_of_posts' );

		if ( count( $related_posts ) ) {

			$h = min( count( $related_posts ), $number_of_posts );

			// Google Adsense
			$ad_tag   = cprp_embed_google_ads();
			$ad_index = 0;

			switch ( cprp_get_settings( 'google_adsense_index' ) ) {
				case 'end':
					$ad_index = $h;
					break;
				case 'interval':
					if ( is_numeric( cprp_get_settings( 'google_adsense_index_interval' ) ) ) {
						$ad_index = min( $h, intval( cprp_get_settings( 'google_adsense_index_interval' ) ) );
					}
					break;
			}

			$str .= '<div class="cprp_items ' . esc_attr( $mode ) . ' ' . ( ( $is_shortcode ) ? 'by-shortcode' : '' ) . '"><ul>';

			$i = 0;
			while ( $i <= $h ) {

				if (
					! empty( $ad_tag ) &&
					(
						$i == $ad_index ||
						( $ad_index && $i && 0 == $i % $ad_index )
					)
				) {
					$str .= '<li><div class="cprp_data">' . $ad_tag . '</div></li>';
				}

				if ( $i < $h ) {
					$str .= '<li><div class="cprp_data">';

					$thumb = '';
					$title = wp_strip_all_tags( $related_posts[ $i ]->post_title, true );
					$link  = get_permalink( $related_posts[ $i ]->ID );

					if ( $display['show_thumbnail'] && has_post_thumbnail( $related_posts[ $i ]->ID ) ) {
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $related_posts[ $i ]->ID ), ( ( isset( $display ) && ! empty( $display['thumbnail_size'] ) ) ? $display['thumbnail_size'] : 'thumbnail' ) );
						$thumb = apply_filters(
							'cprp_post_thumbnail',
							'<a href="' . esc_url( $link ) . '" title="' . esc_attr( $title ) . '"><img src="' . esc_url( $image[0] ) . '" class="cprp_thumbnail" title="' . esc_attr( $title ) . '" alt="' . esc_attr( $title ) . '" /></a>',
							$related_posts[ $i ]
						);
					}

					$str .= apply_filters(
						'cprp_post_title',
						'<div class="cprp_title"><a href="' . esc_url( $link ) . '" title="' . esc_attr( $title ) . '">' . $title . '</a></div>',
						$related_posts[ $i ]
					);

					if ( $display['show_percentage'] ) {
						$str .= apply_filters(
							'cprp_post_percentage',
							'<div class="cprp_percentage">' . ( $related_posts[ $i ]->percentage * 100 ) . '</div>',
							$related_posts[ $i ]
						);
					}

					if ( $display['show_excerpt'] ) {
						$str .= '<div class="cprp_excerpt">' . $thumb . '<span class="cprp_excerpt_content">' .
						apply_filters(
							'cprp_post_excerpt',
							wp_trim_words(
								strip_shortcodes(
									( ! empty( $related_posts[ $i ]->post_excerpt ) ) ? $related_posts[ $i ]->post_excerpt : $related_posts[ $i ]->post_content
								),
								( ( isset( $display['excerpt_words'] ) && is_numeric( $display['excerpt_words'] ) ) ? intval( $display['excerpt_words'] ) : 50 )
							),
							$related_posts[ $i ]
						)
						. '</span></div>';
					} else {
						$str .= $thumb;
					}

					if ( $display['show_tags'] && ! empty( $related_posts[ $i ]->matching ) ) {
						$str .= apply_filters(
							'cprp_post_tags',
							'<div class="cprp_tags">Tags: ' . wp_strip_all_tags( implode( ', ', array_slice( $related_posts[ $i ]->matching, 0, 10 ) ), true ) . '</div>',
							$related_posts[ $i ]
						);
					}

					$str .= '</div></li>';
				}

				$i++;
			}

			$str .= '</ul></div><div style="clear:both;"></div>';
		}

		$str = apply_filters(
			'cprp_content',
			$str,
			$related_posts
		);

		return $str;
	} // End _cprp_content
}

if ( ! function_exists( 'cprp_embed_google_ads' ) ) {
	function cprp_embed_google_ads() {
		if (
			cprp_get_settings( 'google_adsense' ) &&
			(
				! defined( 'BORLABS_COOKIE_VERSION' ) ||
				(
					function_exists( 'BorlabsCookieHelper' ) &&
					BorlabsCookieHelper()->gaveConsent(
						( $borlabs_cookie_id = cprp_get_settings( 'borlabs_cookie_id' ) ) != '' ?
						$borlabs_cookie_id : 'google-adsense'
					)
				)
			)
		) {
			$ad_client = cprp_get_settings( 'google_adsense_client' );
			$ad_unit   = cprp_get_settings( 'google_adsense_unit' );

			if ( ! empty( $ad_client ) && ! empty( $ad_unit ) ) {
				add_filter(
					'script_loader_tag',
					function( $tag, $handle ) {
						if ( 'google-adsense' == $handle ) {
							return preg_replace( '/\ssrc=/i', ' crossorigin="anonymous" async src=', $tag );
						}
						return $tag;
					},
					10,
					2
				);

				wp_enqueue_script( 'google-adsense', '//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=' . esc_attr( $ad_client ), array(), '1.0.46' );

				return '<div><div class="cprp_data">
                    <!-- Related_Ads -->
                    <ins class="adsbygoogle"
                        style="display:block" ' . ( cprp_get_settings( 'google_adsense_local_website' ) ? ' data-adtest="on"' : '' ) . '
                        data-ad-client="' . esc_attr( $ad_client ) . '"
                        data-ad-slot="' . esc_attr( $ad_unit ) . '"
                        data-ad-format="auto"
                        data-full-width-responsive="true"></ins>
                    <script>
                        window.addEventListener("load", function(){
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        });
                    </script>
                </div></div>';
			}
		}
		return '';
	}
} // End cprp_embed_google_ads

function cprp_content( $the_content ) {
	$str = _cprp_content( $the_content );
	if ( strlen( $str ) ) {
		$str          = '<h2 class="cprp_section_title">' . wp_strip_all_tags( __( cprp_get_settings( 'title' ), 'cp-related-posts' ) ) . '</h2>' . $str;
		$the_content .= $str;
	}
	return $the_content;
} // End cprp_content

// Additional routines

function cprp_get_settings( $key = '' ) {
	global $cprp_default_settings;

	$cprp_settings = get_option( 'cprp_settings' );
	if ( false !== $cprp_settings ) {
		if ( ! empty( $key ) ) {
			if ( isset( $cprp_settings[ $key ] ) ) {
				return $cprp_settings[ $key ];
			} else {
				return $cprp_default_settings[ $key ];
			}
		} else {
			return $cprp_settings;
		}
	} else {
		if ( ! empty( $key ) ) {
			return $cprp_default_settings[ $key ];
		} else {
			return $cprp_default_settings;
		}
	}
} // cprp_get_settings

function cprp_sort( $a, $b ) {
	if ( $a->percentage == $b->percentage ) {
		return 0;
	}
	return ( $a->percentage < $b->percentage ) ? 1 : -1;
} // End cprp_sort

function cprp_process_post( $post, $update_post_meta = true ) {
	global $cprp_tags_obj, $wpdb;
	$tags = $cprp_tags_obj->get_tags( $post->post_title . ' ' . $post->post_excerpt . ' ' . $post->post_content );

	if ( cprp_get_settings( 'taxonomies' ) ) {
		$associated_tags = $wpdb->get_results(
			$wpdb->prepare( 'SELECT terms.name as name FROM ' . $wpdb->prefix . 'terms as terms, ' . $wpdb->prefix . 'term_relationships as relationships WHERE relationships.term_taxonomy_id = terms.term_id AND relationships.object_id=%d', $post->ID )
		);
	} else {
		$associated_tags = wp_get_post_tags( $post->ID );
	}

	if ( ! empty( $associated_tags ) ) {
		$tags_as_text = '';
		foreach ( $associated_tags as $t ) {
			$tags_as_text .= str_replace( '"', '', $t->name ) . ' ';
		}

		$associated_tags = $cprp_tags_obj->get_tags( $tags_as_text );
		foreach ( $associated_tags as $key => $value ) {
			if ( isset( $tags[ $key ] ) ) {
				$tags[ $key ] += 100;
			} else {
				$tags[ $key ] = 100;
			}
		}
	}
	if ( $update_post_meta ) {
		update_post_meta( $post->ID, 'cprp_tags', $tags );
	}
	return $tags;
}

/**
 * tags_extractor_shutdown is executed when the PHP script is stopped
 */
function cprp_shutdown() {
	global $last_processed;
	print "<script>document.location='" . esc_url_raw( cprp_site_url() . '?cprp-action=extract-all&id=' . $last_processed ) . "';</script>";
}

function cprp_site_url() {
	return trim( get_admin_url( get_current_blog_id() ), '/' ) . '/';
}

// ************************************** CREATE WIDGETS *********************************************/

function cprp_load_widgets() {
	register_widget( 'CPRPWidget' );
}

/**
 * CPRPWidget Class
 */
class CPRPWidget extends WP_Widget {

	/** constructor */
	public function __construct() {
		parent::__construct( false, $name = 'CP Related Posts' );
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( is_singular() ) {
			global $post;
			$str = _cprp_content( $post->post_content, array( 'mode' => 'cprp-widget' ) );
			if ( strlen( $str ) ) {
				echo $before_widget;
				if ( $title ) {
					echo $before_title . esc_html( $title ) . $after_title;
				}
				echo $str;
				echo $after_widget;
			}
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}

	public function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title = $instance['title'];
		?>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:' ); ?> <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		<?php
	}

} // class CPRPWidget

?>
