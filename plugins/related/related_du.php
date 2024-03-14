<?php

if ( ! class_exists('Related_du')) {
	class Related_du {

		/*
		 * Constructor
		 */
		public function __construct() {

			// Set some helpful constants
			$this->define_constants();

			// Register hook to save the related posts when saving the post
			add_action('save_post', array( &$this, 'save' ));

			// Start the plugin
			add_action('admin_menu', array( &$this, 'start' ), 11);

			// Load the scripts
			add_action('admin_enqueue_scripts', array( &$this, 'admin_scripts' ));

			// Add the related posts to the content, if set in options
			add_filter( 'the_content', array( $this, 'related_content_filter' ), 23 );

			// Add the related posts to the RSS Feed, if set in options
			add_filter( 'the_excerpt_rss', array( $this, 'related_content_rss' ), 23 );
			add_filter( 'the_content', array( $this, 'related_content_rss' ), 23 );
		}


		/*
		 * defineConstants
		 * Defines a few static helper values we might need
		 */
		protected function define_constants() {
			define('RELATED_DU_FILE', plugin_basename(dirname(__FILE__)));
			define('RELATED_DU_ABSPATH', str_replace('\\', '/', WP_PLUGIN_DIR . '/' . plugin_basename(dirname(__FILE__))));
		}


		/*
		 * start
		 * Main function
		 */
		public function start() {

			// Adds a meta box for related posts to the edit screen of each post type in WordPress
			$related_show = get_option('related_du_show');
			$related_show = json_decode( $related_show );
			if ( empty( $related_show ) ) {
				$related_show = array();
				$related_show[] = 'any';
			}
			if ( in_array( 'any', $related_show ) ) {
				foreach (get_post_types() as $post_type) {
					$post_type = sanitize_text_field( $post_type );
					add_meta_box($post_type . '-related_du-posts-box', esc_html__('Related posts (Doubled Up)', 'related' ), array( &$this, 'display_metabox' ), $post_type, 'normal', 'high');
				}
			} else {
				foreach ($related_show as $post_type) {
					$post_type = sanitize_text_field( $post_type );
					add_meta_box($post_type . '-related_du-posts-box', esc_html__('Related posts (Doubled Up)', 'related' ), array( &$this, 'display_metabox' ), $post_type, 'normal', 'high');
				}
			}

		}


		/*
		 * Load JavaScript for Admin
		 */
		public function admin_scripts() {
			wp_enqueue_script( 'related_du-scripts', RELATED_URLPATH . '/js/scripts_du.js', false, RELATED_VERSION, true );
		}


		/*
		 * save
		 * Save related posts when saving the post
		 *
		 * @param $post_id int ID of the current post.
		 */
		public function save( $post_id ) {
			global $pagenow;

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
				return;
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
				return;
			if ( defined( 'DOING_CRON' ) && DOING_CRON )
				return;

			/* Check Nonce */
			$verified = false;
			if ( isset($_POST['related_du_nonce']) ) {
				$verified = wp_verify_nonce( $_POST['related_du_nonce'], 'related_du_nonce' );
			}
			if ( $verified == false ) {
				// Nonce is invalid.
				return;
			}

			if ( isset($_POST['related_du-posts']) ) {
				$related_posts = $_POST['related_du-posts'];
				$related_posts_new = array();
				foreach ( $related_posts as $related_post ) {
					if ( $post_id == (int) $related_post ) { continue; }
					$related_posts_new[] = (int) $related_post; // cast to int for security.
				}
				update_post_meta($post_id, 'related_du_posts', $related_posts_new);
			}
			/* Only delete on post.php page, not on Quick Edit. */
			if ( empty($_POST['related_du-posts']) ) {
				if ( $pagenow == 'post.php' ) {
					delete_post_meta($post_id, 'related_du_posts');
				}
			}
		}


		/*
		 * display_metabox
		 * Creates the output on the post screen
		 */
		public function display_metabox() {
			global $post;
			$post_id = $post->ID;

			/* Nonce */
			$nonce = wp_create_nonce( 'related_du_nonce' );
			echo '<input type="hidden" id="related_du_nonce" name="related_du_nonce" value="' . esc_attr( $nonce ) . '" />';

			echo '<p>' . esc_html__('Choose related posts. You can drag-and-drop them into the desired order:', 'related' ) . '</p><div id="related_du-posts">';

			// Get related posts if existing
			$related = get_post_meta($post_id, 'related_du_posts', true);

			if ( ! empty($related)) {
				foreach ($related as $r) {
					if ( $post_id == $r ) { continue; }
					$p = get_post( (int) $r );

					if ( is_object( $p ) ) {
						if ($p->post_status !== 'trash') {
							echo '
								<div class="related_du-post" id="related_du-post-' . (int) $r . '">
									<input type="hidden" name="related_du-posts[]" value="' . (int) $r . '">
									<span class="related_du-post-title">' . esc_html( $p->post_title ) . ' (' . ucfirst(get_post_type($p->ID)) . ')</span>
									<a href="#">' . esc_html__('Delete', 'related' ) . '</a>
								</div>';
						}
					}

				}
			}

			/* First option should be empty with a data placeholder for text.
			 * The jQuery call allow_single_deselect makes it possible to empty the selection
			 */
			echo '
				</div>
				<p class="related_du-posts-select">
					<select class="related_du-posts-select chosen-select" name="related_du-posts-select" data-placeholder="' . esc_html__('Choose a related post... ', 'related' ) . '">';

			echo '<option value="0"></option>';


			$related_list = get_option('related_du_list');
			$related_list = json_decode( $related_list );

			if ( empty( $related_list ) || in_array( 'any', $related_list ) ) {
				// list all the post_types
				$related_list = array();

				$post_types = get_post_types( '', 'names' );
				foreach ( $post_types as $post_type ) {
					if ( $post_type === 'revision' || $post_type === 'nav_menu_item' ) {
						continue;
					}
					$related_list[] = $post_type;
				}
			}

			foreach ( $related_list as $post_type ) {

				if ( is_post_type_hierarchical($post_type) ) {
					$orderby = 'title';
					$order = 'ASC';
				} else {
					$orderby = 'date';
					$order = 'DESC';
				}
				echo '<optgroup label="' . ucwords($post_type) . ' ' . sprintf( esc_html__('(sorted on %s)', 'related'), $orderby ) . '">';

				/* Use suppress_filters to support WPML, only show posts in the right language. */
				$query_args = array(
					'nopaging' => true,
					'posts_per_page' => 500,
					'orderby' => $orderby,
					'order' => $order,
					'post_type' => $post_type,
					'suppress_filters' => 0,
					'post_status' => 'publish, inherit',
					'exclude' => array( $post_id ),
				);

				$posts = get_posts( $query_args );

				if ( ! empty( $posts ) ) {
					$args = array( $posts, 0, $query_args );

					$walker = new Walker_RelatedDropdown();
					echo call_user_func_array( array( $walker, 'walk' ), $args );
				}

				echo '</optgroup>';

			} // endforeach

			wp_reset_postdata();

			echo '
					</select>
				</p>';

		}


		/*
		 * show
		 * The frontend function that is used to display the related post list.
		 *
		 * Parameters:
		 * - Post ID: the post ID with the list of related posts.
		 * - Return: If true, returns a simple array of related posts to do as you please.
		 *           If false (default), it will return a string with formatted HTML.
		 */
		public function show( $id, $return = false ) {

			global $wpdb;

			/* Compatibility for Qtranslate, Qtranslate-X and Qtranslate-XT, and the get_permalink function */
			if ( function_exists( 'qtrans_convertURL' ) ) {
				add_filter('post_type_link', 'qtrans_convertURL');
			}
			if ( function_exists( 'qtranxf_convertURL' ) ) {
				add_filter('post_type_link', 'qtranxf_convertURL');
			}

			if ( ! empty($id) && is_numeric($id)) {
				$related = get_post_meta($id, 'related_du_posts', true);

				if ( ! empty($related)) {
					$rel = array();
					foreach ($related as $r) {
						$p = get_post( (int) $r );
						$rel[] = $p;
					}

					// If value should be returned as array, return it.
					if ($return) {
						return $rel;
					}

					// Otherwise return a formatted list
					if ( is_array( $rel ) && count( $rel ) > 0 ) {
						$extended_view = get_option('related_du_content_extended', 0);
						if ( $extended_view ) {
							$list = '
									<ul class="related-posts extended_view">';
						} else {
							$list = '
									<ul class="related-posts">';
						}
						foreach ($rel as $r) {
							if ( is_object( $r ) ) {
								if ($r->post_status !== 'trash') {
									if ( $extended_view ) {
										$thumb_id = get_post_thumbnail_id($r->ID);
										$tn_size = apply_filters( 'related_show_post_tn_size', 'medium' );
										$tn = wp_get_attachment_image_src( $thumb_id, sanitize_text_field( $tn_size ) );
										$image_url = '';
										if ( isset($tn[0]) ) {
											$image_url = $tn[0];
										}
										$image_alt = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true );
										if ( strlen($image_alt) == 0 ) { // No alt set for featured image, use the related post title.
											$image_alt = get_the_title($r->ID);
										}
										$image = '';
										if ( strlen($image_url) > 0 ) {
											$image = '<img src="' . $image_url . '" alt="' . $image_alt . '" class="related-post-image" />';
										}
										$related_post = '
										<li class="related-post extended_view">
											<a href="' . get_permalink($r->ID) . '" class="related-post-link">
												' . $image
												 . '<span class="related-post-title">' . get_the_title($r->ID) . '</span>
											</a>
										</li>';
										/*
										 * Filter for developers, where you can change content, or add content.
										 */
										$related_post = apply_filters( 'related_show_post', $related_post, $r );
										$list .= $related_post;
									} else {
										$related_post = '
										<li class="related-post">
											<a href="' . get_permalink($r->ID) . '">' . get_the_title($r->ID) . '</a>
										</li>';
										/*
										 * Filter for developers, where you can change content, or add content.
										 */
										$related_post = apply_filters( 'related_show_post', $related_post, $r );
										$list .= $related_post;
									}
								}
							}
						}
						$list .= '
									</ul>
									<div style="clear:both;"></div>';

						/*
						 * Filter for developers, where you can change content, or add content.
						 */
						$list = apply_filters( 'related_show_post_list', $list, $rel );

						return $list;
					}
				} else {
					return false;
				}
			} else {
				return esc_html__('Invalid post ID specified', 'related' );
			}
		}


		/*
		 * Add the plugin data to the content, if it is set in the options.
		 */
		public function related_content_filter( $content ) {
			if ( is_feed() ) {
				return $content;
			}
			if ( ( get_option( 'related_du_content', 0 ) == 1 && is_singular() ) || get_option( 'related_du_content_all', 0 ) == 1 ) {
				global $related_du;
				$related_posts = $related_du->show( get_the_ID() );
				if ( $related_posts ) {
					$content .= '<div class="related_du_content" style="clear:both;">';
					$filtered_title = '<h3 class="widget-title">';
					$filtered_title .= stripslashes(get_option('related_du_content_title', esc_html__('Related Posts', 'related')));
					$filtered_title .= '</h3>';
					$content .= apply_filters( 'related_du_content_title', $filtered_title );
					$content .= $related_posts;
					$content .= '</div>
					';
				}
			}
			// otherwise returns the old content
			return $content;
		}


		/*
		 * Add the plugin data to the content of the RSS Feed.
		 */
		public function related_content_rss( $content ) {
			if ( is_feed() && get_option( 'related_du_content_rss', 0 ) == 1 ) {
				global $related_du;
				$related_posts = $related_du->show( get_the_ID() );
				if ( $related_posts ) {
					$content .= '<div class="related_du_content" style="clear:both;">';
					$filtered_title = '<h3 class="widget-title">';
					$filtered_title .= stripslashes(get_option('related_du_content_title', esc_html__('Related Posts', 'related')));
					$filtered_title .= '</h3>';
					$content .= apply_filters( 'related_du_content_title', $filtered_title );
					$content .= $related_posts;
					$content .= '</div>
					';
				}
			}
			// otherwise returns the old content
			return $content;
		}

	}

}


/*
 * related_du_init
 * Function called at initialisation.
 * - Make an instance of Related_du()
 *
 * @since 3.0.0
 */
function related_du_init() {

	// Safely upgrade from V2.
	$related_double = get_option('related_double_plugin');
	if ( $related_double === false ) { // never set...
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		$active = is_plugin_active( 'related/related_du.php' ); // true or false
		if ( $active ) {
			update_option('related_double_plugin', 1);
			deactivate_plugins( 'related/related_du.php' );
		} else {
			update_option('related_double_plugin', 0);
		}
	}

	$related_double = get_option('related_double_plugin');
	if ( $related_double ) {

		// Start the Double Up plugin.
		global $related_du;
		$related_du = new Related_du();

		/* Include Settings page */
		require_once 'adminpages/page-related_du.php';

		/* Include widget */
		require_once 'widgets/related_du-widget.php';
	}
}
add_action('plugins_loaded', 'related_du_init');
