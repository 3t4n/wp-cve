<?php
/**
 * Post Type Requirements Checklist.
 *
 * Help Clients Help Themselves
 *
 * @package   Post_Type_Requirements_Checklist
 * @author    Dave Winter (dave@dauid.us)
 * @license   GPL-2.0+
 * @link      http://dauid.us
 * @copyright 2014 dauid.us
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-custom-featured-image-metabox.php`
 *
 * @package Post_Type_Requirements_Checklist_Admin
 */
class Post_Type_Requirements_Checklist_Admin {

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * Call $plugin_slug from public plugin class later.
	 *
	 * @since    1.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = null;

	/**
	 * Instance of this class.
	 *
	 * @since    1.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0
	 */
	private function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 *
		 */
		$plugin = post_type_requirements_checklist::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Add the options page
		require_once( plugin_dir_path( __FILE__ ) . 'includes/settings.php' );

		// Add the menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . 'post-type-requirements-checklist.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		// Fire functions
			add_action( 'admin_enqueue_scripts', array( $this, 'is_edit_page' ) );
			add_action( 'post_submitbox_misc_actions', array( $this, 'insert_publish_metabox_checklist' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 */
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Requirements Checklist', 'aptrc' ),
			__( 'Requirements Checklist', 'aptrc' ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . 'aptrc' ) . '">' . __( 'Settings' ) . '</a>'
			),
			$links
		);

	}

	/**
	 * Get post type
	 *
	 * @return string Post type
	 *
	 * @since 1.0
	 */
	public function get_post_type() {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			if ( isset( $_REQUEST['post_id'] ) ) {
				$post = get_post( $_REQUEST['post_id'] );
				return $post->post_type;
			}
		}

		$screen = get_current_screen();

		return $screen->post_type;

	} // end get_post_type

	/**
	 * enqueue styles
	 *
	 * @since 1.0
	 */
	public function is_edit_page($new_edit = null){

	    global $current_screen;  // Makes the $current_screen object available           
		if ($current_screen && ($current_screen->base == "edit" || $current_screen->base == "post")) {

			wp_enqueue_style('aptrc-style', plugins_url( '/css/aptrc.css', __FILE__ ) );

		}

	} // end is_edit_page

	/**
	 * Insert Publish Metabox Checklist
	 *
	 * @since 1.0
	 */
	public function insert_publish_metabox_checklist() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		// checkbox title
		
		echo '<div id="requirements_list"><span id="rltop">' . __( 'Requirements Checklist', 'aptrc' ) . ':</span>';


		/**
		 * Title
		 *
		 * @since 1.0
		 */
		if ( isset( $options['title_check'] ) && ! empty( $options['title_check'] ) ) {	

			echo '<span class="reqcb">';
			echo '<input name="title_checkbox" type="checkbox" onclick="return false;" onkeydown="return false;" /><label for="title_checkbox"><span></span> ' . __( 'Title', 'aptrc' ) . '</label><br/>';
			echo '</span>'; 
			?>

			<script>

				function checkTitle() {
		
					var titleElement = jQuery( "#title" );
					var title = titleElement.val();

					if ( title.length < 1 ) {
						jQuery( "input[type='checkbox'][name='title_checkbox']").prop('checked', false);
					}	
					else {
						jQuery( "input[type='checkbox'][name='title_checkbox']").prop('checked', true);
					}

				}

				// run when page first loads
				jQuery( "input[type='checkbox'][name='title_checkbox']").prop('checked', false);
				setTimeout(checkTitle,1000);
				// run on title input
				jQuery( "#title" ).keyup( checkTitle );
				//setInterval(checkHeadline,500);
				
			</script>

			<?php
			// should this also check against slug?
		}


		/**
		 * Editor
		 *
		 * @since 1.0
		 */
// rethink how this updates - every 10 seconds now
		if ( isset( $options['editor_check'] ) && ! empty( $options['editor_check'] ) ) {				
			echo '<span class="reqcb">';
			echo '<input name="editor_checkbox" type="checkbox" onclick="return false;" onkeydown="return false;" /><label for="editor_checkbox"><span></span> ' . __( 'WYSIWYG Editor', 'aptrc' ) . '</label><br/>';
			echo '</span>';
			?>

			<script>

				function checkEditor() {
		
					var editorElement = jQuery( ".wp-editor-area" );
					var editor = editorElement.val();

					if ( editor.length < '1' ) {		
						jQuery( "input[type='checkbox'][name='editor_checkbox']").prop('checked', false);
					} else {
						jQuery( "input[type='checkbox'][name='editor_checkbox']").prop('checked', true);
					}

				}

				// run when page first loads
				jQuery( "input[type='checkbox'][name='editor_checkbox']").prop('checked', false);
				// checkEditor();
				// run on editor input (this currently has problems)
				setInterval(checkEditor,1000);
				
			</script>

			<?php
		}


		/**
		 * Featured Image
		 *
		 * @since 1.0
		 */
		if ( isset( $options['thumbnail_check'] ) && ! empty( $options['thumbnail_check'] ) ) {				
			echo '<span class="reqcb">';
			echo '<input name="thumbnail_checkbox" type="checkbox" onclick="return false;" onkeydown="return false;" /><label for="thumbnail_checkbox"><span></span> ' . __( 'Featured Image', 'aptrc' ) . '</label><br/>';
			echo '</span>';
			?>

			<script>

				function checkThumbnail() {

					if (jQuery("#postimagediv img").length) {
						jQuery( "input[type='checkbox'][name='thumbnail_checkbox']").prop('checked', true);
					}	
					// support for 'Drag & Drop Featured Image' plugin
					// suggestion by Jean-Philippe (on WP support forums)
					// since 2.3
					else if ( (jQuery("#current-uploaded-image img").length) && (jQuery('#current-uploaded-image').is(':visible')) ) { 
						jQuery( "input[type='checkbox'][name='thumbnail_checkbox']").prop('checked', true);
					}
					else {
						jQuery( "input[type='checkbox'][name='thumbnail_checkbox']").prop('checked', false);
					}

				}

				// run when page first loads
				jQuery( "input[type='checkbox'][name='thumbnail_checkbox']").prop('checked', false);
				// checkThumbnail();
				// set check time
				setInterval(checkThumbnail,1000);
				
			</script>

			<?php
		}


		/**
		 * Excerpt
		 *
		 * @since 1.0
		 */
		if ( isset( $options['excerpt_check'] ) && ! empty( $options['excerpt_check'] ) ) {				
			echo '<span class="reqcb">';
			echo '<input name="excerpt_checkbox" type="checkbox" onclick="return false;" onkeydown="return false;" /><label for="excerpt_checkbox"><span></span> ' . __( 'Excerpt', 'aptrc' ) . '</label><br/>';
			echo '</span>';
			?>

			<script>

				function checkExcerpt() {

					var excerptElement = jQuery( "#excerpt" );
					var excerpt = excerptElement.val();

					if ( excerpt == '' ) {
						jQuery( "input[type='checkbox'][name='excerpt_checkbox']").prop('checked', false);
					}	
					else {
						jQuery( "input[type='checkbox'][name='excerpt_checkbox']").prop('checked', true);
					}

				}

				// run when page first loads
				jQuery( "input[type='checkbox'][name='excerpt_checkbox']").prop('checked', false);
				// checkExcerpt();
				// set check time
				setInterval(checkExcerpt,1000);
				
			</script>

			<?php
		}


		/**
		 * Built-In Taxonomies
		 *
		 * @since 1.0, reimagined in 2.2
		 */
		$bi_argums = array(
		    'public'   => true,
		    '_builtin' => true
		); 
		$bi_outputs = 'names'; // or objects
		$bi_operators = 'and'; // 'and' or 'or'
		$bi_taxonomy_names = get_taxonomies( $bi_argums, $bi_outputs, $bi_operators );
		// remove second tags listing from post screens
		if ( 'post' == $post_type ) {
			unset( $bi_taxonomy_names['post_tag'] );
		}
		echo '<div id="builtin-taxonomies">';
		foreach ( $bi_taxonomy_names as $bi_tn ) {

			if ( is_object_in_taxonomy( $post_type, $bi_tn ) ) {

				if ( is_taxonomy_hierarchical( $bi_tn ) ) {
					if ( isset( $options['categories_check'] ) && ! empty( $options['categories_check'] ) ) {				
						echo '<span class="reqcb">';
						echo '<input name="categories_checkbox" id="categories_checkbox" type="checkbox" onclick="return false;" onkeydown="return false;" /><label for="categories_checkbox"><span></span> ' . __( 'Categories', $this->plugin_slug . '');

						$cat_num = $options['categories_dropdown'];
						$cat_num_max = $options['categories_max_dropdown'];

						if ( $cat_num == $cat_num_max ) {
							$cat_num_html = ' &nbsp ' . __( 'exactly ', 'aptrc' ) . '' .$cat_num_max . '';
							echo '<em>'.$cat_num_html.'</em>';
						}
						else if ( $cat_num_max == '1000' ) {
							$cat_num_html = ' &nbsp ' . $cat_num . ' or more';
							echo '<em>'.$cat_num_html.'</em>';
						}
						else {
							$cat_num_html = ' &nbsp ' . $cat_num . '-' . $cat_num_max . '';
							echo '<em>'.$cat_num_html.'</em>';
						}

						echo '</label><br/>';
						echo '</span>';
						?>

						<script>

							function checkCategories() {

								var cat_num = '<?php echo $cat_num; ?>';
								var cat_num_max = '<?php echo $cat_num_max; ?>';
								var catschecked = jQuery("#categorychecklist input[type='checkbox']:checked").length;

								if ( ( cat_num == cat_num_max ) && ( catschecked == cat_num ) ) {
									jQuery( "input[type='checkbox'][name='categories_checkbox']").prop('checked', true);
								} 
								else if ( ( catschecked >= cat_num ) && ( catschecked <= cat_num_max ) ) {
									jQuery( "input[type='checkbox'][name='categories_checkbox']").prop('checked', true);
								}
								else {
									jQuery( "input[type='checkbox'][name='categories_checkbox']").prop('checked', false);
								}

							}

							// run when page first loads
							jQuery( "input[type='checkbox'][name='categories_checkbox']").prop('checked', false);
							// checkCategories();
							// set check time
							setInterval(checkCategories,1000);
							
						</script>

						<?php
						// logic for minimum number of categories
					}

				} else {
					if ( isset( $options['tags_check'] ) && ! empty( $options['tags_check'] ) ) {				
						echo '<span class="reqcb">';
						echo '<input name="tags_checkbox" id="tags_checkbox" type="checkbox" onclick="return false;" onkeydown="return false;" /><label for="tags_checkbox"><span></span> ' . __( 'Tags', $this->plugin_slug . '');
						
						$tag_num = $options['tags_dropdown'];
						$tag_num_max = $options['tags_max_dropdown'];

						if ( $tag_num == $tag_num_max ) {
							$tag_num_html = ' &nbsp; ' . __( 'exactly ', 'aptrc' ) . '' .$tag_num_max . '';
							echo '<em>'.$tag_num_html.'</em>';
						}
						else if ( $tag_num_max == '1000' ) {
							$tag_num_html = ' &nbsp ' . $tag_num . ' or more';
							echo '<em>'.$tag_num_html.'</em>';
						}
						else {
							$tag_num_html = ' &nbsp; ' . $tag_num . '-' . $tag_num_max . '';
							echo '<em>'.$tag_num_html.'</em>';
						}

						echo '</label><br/>';
						echo '</span>';
						?>

						<script>

							function checkTags() {

								var tag_num = '<?php echo $tag_num; ?>';
								var tag_num_max = '<?php echo $tag_num_max; ?>';
								var tagschecked = jQuery("#tagsdiv-post_tag .ntdelbutton").length;

								if ( ( tag_num == tag_num_max ) && ( tagschecked == tag_num ) ) {
									jQuery( "input[type='checkbox'][name='tags_checkbox']").prop('checked', true);
								} 
								else if ( ( tagschecked >= tag_num ) && ( tagschecked <= tag_num_max ) ) {
									jQuery( "input[type='checkbox'][name='tags_checkbox']").prop('checked', true);
								}
								else {
									jQuery( "input[type='checkbox'][name='tags_checkbox']").prop('checked', false);
								}

							}

							// run when page first loads
							jQuery( "input[type='checkbox'][name='tags_checkbox']").prop('checked', false);
							// checkTags();
							// set check time
							setInterval(checkTags,1000);
							
						</script>

						<?php
						// logic for minimum number of tags
					}
				}
			}

		}
		echo '</div>';


		/**
		 * Custom Taxonomies
		 *
		 * @since 2.0
		 */
		$argums = array(
		    'public'   => true,
		    '_builtin' => false
		); 
		$outputs = 'names'; // or objects
		$operators = 'and'; // 'and' or 'or'
		$taxonomy_names = get_taxonomies( $argums, $outputs, $operators );
		$x = '1';
		echo '<div id="custom-taxonomies">';
		foreach ( $taxonomy_names as $tn ) {

			$thingargums = array(
			  'name' => $tn
			);
			$thingoutputs = 'objects'; // or names
			$things = get_taxonomies( $thingargums, $thingoutputs ); 

			foreach ($things as $thing ) {

				if ( is_object_in_taxonomy( $post_type, $tn ) ) {
					if ( is_taxonomy_hierarchical( $tn ) ) {

						if ( isset( $options['hierarchical_check_'.$x.''] ) && ! empty( $options['hierarchical_check_'.$x.''] ) ) {				
							echo '<span class="reqcb">';
							echo '<input name="'.$tn.'_checkbox" id="'.$tn.'_checkbox" type="checkbox" onclick="return false;" onkeydown="return false;" /><label for="'.$tn.'_checkbox"><span></span> ' . $thing->label;
							
							$cat_num = $options['hierarchical_dropdown_'.$x.''];
							$cat_num_max = $options['hierarchical_max_dropdown_'.$x.''];

							if ( $cat_num == $cat_num_max ) {
								$cat_num_html = ' &nbsp ' . __( 'exactly ', 'aptrc' ) . '' .$cat_num_max . '';
								echo '<em>'.$cat_num_html.'</em>';
							}
							else if ( $cat_num_max == '1000' ) {
								$cat_num_html = ' &nbsp ' . $cat_num . ' or more';
								echo '<em>'.$cat_num_html.'</em>';
							}
							else {
								$cat_num_html = ' &nbsp ' . $cat_num . '-' . $cat_num_max . '';
								echo '<em>'.$cat_num_html.'</em>';
							}

							echo '</label><br/>';
							echo '</span>';
							?>

							<script>

								function hier() {

									var cat_num = '<?php echo $cat_num; ?>';
									var cat_num_max = '<?php echo $cat_num_max; ?>';
									var catschecked = jQuery("#<?php echo $tn; ?>checklist input[type='checkbox']:checked").length;

									if ( ( cat_num == cat_num_max ) && ( catschecked == cat_num ) ) {
										jQuery( "input[type='checkbox'][name='<?php echo $tn; ?>_checkbox']").prop('checked', true);
									}
									else if ( ( catschecked >= cat_num ) && ( catschecked <= cat_num_max ) ) {
										jQuery( "input[type='checkbox'][name='<?php echo $tn; ?>_checkbox']").prop('checked', true);
									}
									else {
										jQuery( "input[type='checkbox'][name='<?php echo $tn; ?>_checkbox']").prop('checked', false);
									}
								}

								// run when page first loads
								jQuery( "input[type='checkbox'][name='<?php echo $tn; ?>_checkbox']").prop('checked', false);
								// hier();
								// set check time
								setInterval(hier,1000);
								
							</script>

							<?php
							// logic for minimum number of categories
						}

					}
					else {
						if ( isset( $options['flat_check_'.$x.''] ) && ! empty( $options['flat_check_'.$x.''] ) ) {				
							echo '<span class="reqcb">';
							echo '<input name="'.$tn.'_checkbox" id="'.$tn.'_checkbox" type="checkbox" onclick="return false;" onkeydown="return false;" /><label for="'.$tn.'_checkbox"><span></span> ' .$thing->label;
							
							$tag_num = $options['flat_dropdown_'.$x.''];
							$tag_num_max = $options['flat_max_dropdown_'.$x.''];

							if ( $tag_num == $tag_num_max ) {
								$tag_num_html = ' &nbsp; ' . __( 'exactly ', 'aptrc' ) . '' .$tag_num_max . '';
								echo '<em>'.$tag_num_html.'</em>';
							}
							else if ( $tag_num_max == '1000' ) {
								$tag_num_html = ' &nbsp ' . $tag_num . ' or more';
								echo '<em>'.$tag_num_html.'</em>';
							}
							else {
								$tag_num_html = ' &nbsp; ' . $tag_num . '-' . $tag_num_max . '';
								echo '<em>'.$tag_num_html.'</em>';
							}

							echo '</label><br/>';
							echo '</span>';
							?>

							<script>

								function flat() {

									var tag_num = '<?php echo $tag_num; ?>';
									var tag_num_max = '<?php echo $tag_num_max; ?>';
									var tagschecked = jQuery("#tagsdiv-<?php echo $tn; ?> .ntdelbutton").length;

									if ( ( tag_num == tag_num_max ) && ( tagschecked == tag_num ) ) {
										jQuery( "input[type='checkbox'][name='<?php echo $tn; ?>_checkbox']").prop('checked', true);
									}
									else if ( ( tagschecked >= tag_num ) && ( tagschecked <= tag_num_max ) ) {
										jQuery( "input[type='checkbox'][name='<?php echo $tn; ?>_checkbox']").prop('checked', true);
									}
									else {
										jQuery( "input[type='checkbox'][name='<?php echo $tn; ?>_checkbox']").prop('checked', false);
									}
								}

								// run when page first loads
								jQuery( "input[type='checkbox'][name='<?php echo $tn; ?>_checkbox']").prop('checked', false);
								// flat();
								// set check time 
								setInterval(flat,1000);
								
							</script>

							<?php
							// logic for minimum number of tags
						}

					}
				}

			}

			$x++;
		}
		echo '</div>';


		/**
		 * WordPress SEO by Yoast
		 * suggestion by Courtney Engle Robertson (on WP support forums)
		 *
		 * @since 2.3
		 */
		if (class_exists('WPSEO_Utils')) {	
			if ( ( isset( $options['yoastseo_focus_keyword'] ) && ! empty( $options['yoastseo_focus_keyword'] )) || 
				 ( isset( $options['yoastseo_meta_description'] ) && ! empty( $options['yoastseo_meta_description'] )) ) {	

				echo '<div id="custom-taxonomies">';	
				echo '<span class="reqcb list">';
				echo '<input name="seo_checkbox" type="checkbox" onclick="return false;" onkeydown="return false;" /><label for="seo_checkbox"><span></span> ' . __( 'WordPress SEO by Yoast', 'aptrc' );

				if ( isset( $options['yoastseo_focus_keyword'] ) && ! empty( $options['yoastseo_focus_keyword'] )) {
					$keyword = 'y';
				} else { $keyword = 'n'; }

				if ( isset( $options['yoastseo_meta_description'] ) && ! empty( $options['yoastseo_meta_description'] )) {
					$meta = 'y';
				} else { $meta = 'n'; }

				echo ' &nbsp; ';
				if ( $keyword == 'y' ) {
					echo '<em>'. __( 'Keyword', 'aptrc' ) .'</em>';
				}
				if ( $meta == 'y' ) {
					echo '<em>'. __( 'Description', 'aptrc' ) .'</em>';
				}

				echo '</label><br/>';
				echo '</span>';
				?>

				<script>

					function checkSEO() {

						var keyword = '<?php echo $keyword; ?>';
						var meta = '<?php echo $meta; ?>';

						// WP SEO focus keyword field
						var seoFocusElement = jQuery( "#yoast_wpseo_focuskw" );
						var seoFocus = seoFocusElement.val();
						// WP SEO meta description field
						var seoDescElement = jQuery( "#yoast_wpseo_metadesc" );
						var seoDesc = seoDescElement.val();

						if ( ( keyword == 'y' ) && ( seoFocus == '' ) || ( meta == 'y' ) && ( seoDesc == '' ) ) {
							jQuery( "input[type='checkbox'][name='seo_checkbox']").prop('checked', false);
						}	
						else {
							jQuery( "input[type='checkbox'][name='seo_checkbox']").prop('checked', true);
						}

					}

					// run when page first loads
					jQuery( "input[type='checkbox'][name='seo_checkbox']").prop('checked', false);
					// checkSEO();
					// set check time
					setInterval(checkSEO,1000);
					
				</script>

				<?php
				echo '</div>';

			}
		}


		/**
		 * All In One SEO Pack
		 * suggestion by Courtney Li-An (on WP support forums)
		 *
		 * @since 2.3
		 */
		if (class_exists('All_in_One_SEO_Pack')) {	
			if ( ( isset( $options['allinone_title'] ) && ! empty( $options['allinone_title'] )) || 
				 ( isset( $options['allinone_description'] ) && ! empty( $options['allinone_description'] )) || 
				 ( isset( $options['allinone_keywords'] ) && ! empty( $options['allinone_keywords'] )) ) 
			{	

				echo '<div id="custom-taxonomies">';	
				echo '<span class="reqcb list">';
				echo '<input name="allinone_checkbox" type="checkbox" onclick="return false;" onkeydown="return false;" /><label for="allinone_checkbox"><span></span> ' . __( 'All In One SEO Pack', 'aptrc' );

				if ( isset( $options['allinone_title'] ) && ! empty( $options['allinone_title'] )) {
					$title = 'y';
				} else { $title = 'n'; }

				if ( isset( $options['allinone_description'] ) && ! empty( $options['allinone_description'] )) {
					$desc = 'y';
				} else { $desc = 'n'; }

				if ( isset( $options['allinone_keywords'] ) && ! empty( $options['allinone_keywords'] )) {
					$keywords = 'y';
				} else { $keywords = 'n'; }

				echo ' &nbsp; ';
				if ( $title == 'y' ) {
					echo '<em>'. __( 'Title', 'aptrc' ) .'</em>';
				}
				if ( $desc == 'y' ) {
					echo '<em>'. __( 'Description', 'aptrc' ) .'</em>';
				}
				if ( $keywords == 'y' ) {
					echo '<em>'. __( 'Keywords', 'aptrc' ) .'</em>';
				}

				echo '</label><br/>';
				echo '</span>';
				?>

				<script>

					function checkAioSEO() {

						var title = '<?php echo $title; ?>';
						var desc = '<?php echo $desc; ?>';
						var keywords = '<?php echo $keywords; ?>';

						// All In One Title field
						var aioTitleElement = jQuery( "input[name=aiosp_title]" );
						var aioTitle = aioTitleElement.val();
						// All In One Description field
						var aioDescElement = jQuery( "textarea[name=aiosp_description]" );
						var aioDesc = aioDescElement.val();
						// All In One Keywords field
						var aioKeywordsElement = jQuery( "input[name=aiosp_keywords]" );
						var aioKeywords = aioKeywordsElement.val();

						if ( ( title == 'y' ) && ( aioTitle == '' ) || 
							 ( desc == 'y' ) && ( aioDesc == '' ) ||
							 ( keywords == 'y' ) && ( aioKeywords == '' ) ) 
						{
							jQuery( "input[type='checkbox'][name='allinone_checkbox']").prop('checked', false);
						}	
						else {
							jQuery( "input[type='checkbox'][name='allinone_checkbox']").prop('checked', true);
						}

					}

					// run when page first loads
					jQuery( "input[type='checkbox'][name='allinone_checkbox']").prop('checked', false);
					// checkaioSEO();
					// set check time
					setInterval(checkAioSEO,1000);
					
				</script>

				<?php
				echo '</div>';

			}
		}


		echo '<span id="rlbot">' . __( 'Drafts may be saved above', 'aptrc' ) . '</span>';


		/**
		 * Hide/Enable Publish Button
		 *
		 * @since 1.0
		 */
		?>
		<script>
			function hideShowPublish() {

				//hide or shows publish box based on whether all the boxes on the page are checked
				var number = jQuery("#requirements_list input[type='checkbox']");
				var numberchecked = jQuery("#requirements_list input[type='checkbox']:checked");

				if ( number.length == numberchecked.length ) {
					jQuery( "#publish" ).slideDown("slow");
					jQuery( "#rlbot" ).slideUp("slow");
					jQuery( "#requirements_list" ).css( "background-color", "transparent" );
				} else {
					jQuery( "#publish" ).slideUp("slow");
					jQuery( "#rlbot" ).slideDown("slow");
					jQuery( "#requirements_list" ).css( "background-color", "#ffffe6" );
				}

				if ( number.length == 0 ) {
					jQuery( "#requirements_list" ).fadeOut();
				} else {
					jQuery( "#requirements_list" ).fadeIn();
				}

			}

			// hide by default
			//jQuery( "#publish" ).hide();
			jQuery( "#rlbot" ).fadeIn();
			// run when page first loads
			hideShowPublish();
			jQuery( "#publish" ).fadeOut();
			// check every second
			setInterval(hideShowPublish,1000);
		</script>
		<?php


		echo '</div>';

	} // end insert_publish_metabox_checklist



}
