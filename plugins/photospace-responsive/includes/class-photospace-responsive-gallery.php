<?php
/**
 * Main plugin class file.
 *
 * @package WordPress Plugin Template/Includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 */
class Photospace_Responsive_Gallery {

	/**
	 * The single instance of Photospace_Responsive_Gallery.
	 *
	 * @var     object
	 * @access  private
	 * @since   1.0.0
	 */
	private static $_instance = null; //phpcs:ignore

	/**
	 * Local instance of Photospace_Responsive_Gallery_Admin_API
	 *
	 * @var Photospace_Responsive_Gallery_Admin_API|null
	 */
	public $admin = null;

	/**
	 * Settings class object
	 *
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version; //phpcs:ignore

	/**
	 * The token.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token; //phpcs:ignore

	/**
	 * The main plugin file.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for JavaScripts.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Constructor funtion.
	 *
	 * @param string $file File constructor.
	 * @param string $version Plugin version.
	 */
	public function __construct( $file = '', $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token   = 'photospace_responsive_gallery';

		// Load plugin environment variables.
		$this->file       = $file;
		$this->dir        = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'plugin-assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/plugin-assets/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Load frontend JS & CSS.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

		// Load admin JS & CSS.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Add  short code overtides
		add_shortcode( 'gallery', array( $this, 'photospace_responsive_shortcode' ) );
		add_shortcode( 'photospace_res', array( $this, 'photospace_responsive_shortcode' ) );

		// Add header scripts
		add_action( 'wp_head', array( $this, 'photospace_responsive_wp_headers'), 10 );

		// Add custom image sizes
		add_theme_support( 'post-thumbnails' );

		$psres_thumbnail_width  = intval(get_option('psres_max_image_width'));
		$psres_thumbnail_height = intval(get_option('psres_max_image_height'));		
		$psres_thumbnail_crop  = (get_option('psres_thumbnail_crop') == 'on')? true : false;
		$psres_max_image_width = intval(get_option('psres_max_image_width'));
		$psres_max_image_height = intval(get_option('psres_max_image_height'));

		add_image_size('photospace_responsive_thumbnails', $psres_thumbnail_width * 2, $psres_thumbnail_height * 2, $psres_thumbnail_crop);
		add_image_size('photospace_responsive_full', $psres_max_image_width, $psres_max_image_height );

		// Load API for generic admin functions.
		if ( is_admin() ) {
			$this->admin = new Photospace_Responsive_Gallery_Admin_API();
		}

		// Handle localisation.
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );
	} // End __construct ()

	/**
	 * Register post type function.
	 *
	 * @param string $post_type Post Type.
	 * @param string $plural Plural Label.
	 * @param string $single Single Label.
	 * @param string $description Description.
	 * @param array  $options Options array.
	 *
	 * @return bool|string|Photospace_Responsive_Gallery_Post_Type
	 */
	public function register_post_type( $post_type = '', $plural = '', $single = '', $description = '', $options = array() ) {

		if ( ! $post_type || ! $plural || ! $single ) {
			return false;
		}

		$post_type = new Photospace_Responsive_Gallery_Post_Type( $post_type, $plural, $single, $description, $options );

		return $post_type;
	}

	/**
	 * Wrapper function to register a new taxonomy.
	 *
	 * @param string $taxonomy Taxonomy.
	 * @param string $plural Plural Label.
	 * @param string $single Single Label.
	 * @param array  $post_types Post types to register this taxonomy for.
	 * @param array  $taxonomy_args Taxonomy arguments.
	 *
	 * @return bool|string|Photospace_Responsive_Gallery_Taxonomy
	 */
	public function register_taxonomy( $taxonomy = '', $plural = '', $single = '', $post_types = array(), $taxonomy_args = array() ) {

		if ( ! $taxonomy || ! $plural || ! $single ) {
			return false;
		}

		$taxonomy = new Photospace_Responsive_Gallery_Taxonomy( $taxonomy, $plural, $single, $post_types, $taxonomy_args );

		return $taxonomy;
	}

	/**
	 * Load frontend CSS.
	 *
	 * @access  public
	 * @return void
	 * @since   1.0.0
	 */
	public function enqueue_styles() {
		wp_register_style( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'css/frontend.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-frontend' );
	} // End enqueue_styles ()

	/**
	 * Load frontend Javascript.
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function enqueue_scripts() {
		wp_register_script( $this->_token . '-migrate-min-frontend', esc_url( $this->assets_url ) . 'js/jquery-migrate.min.js', array( 'jquery' ), $this->_version, true );
		wp_enqueue_script( $this->_token . '-migrate-min-frontend' );
		wp_register_script( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'js/frontend' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version, true );
		wp_enqueue_script( $this->_token . '-frontend' );
	} // End enqueue_scripts ()

	/**
	 * Admin enqueue style.
	 *
	 * @param string $hook Hook parameter.
	 *
	 * @return void
	 */
	public function admin_enqueue_styles( $hook = '' ) {
		wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'css/admin.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-admin' );
	} // End admin_enqueue_styles ()

	/**
	 * Load admin Javascript.
	 *
	 * @access  public
	 *
	 * @param string $hook Hook parameter.
	 *
	 * @return  void
	 * @since   1.0.0
	 */
	public function admin_enqueue_scripts( $hook = '' ) {
		wp_register_script( $this->_token . '-admin', esc_url( $this->assets_url ) . 'js/admin' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version, true );
		wp_enqueue_script( $this->_token . '-admin' );
	} // End admin_enqueue_scripts ()

	/**
	 * Load plugin localisation
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function load_localisation() {
		load_plugin_textdomain( 'photospace-responsive-gallery', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_localisation ()

	/**
	 * Load plugin textdomain
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function load_plugin_textdomain() {
		$domain = 'photospace-responsive-gallery';

		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain ()

	/**
	 * Main Photospace_Responsive_Gallery Instance
	 *
	 * Ensures only one instance of Photospace_Responsive_Gallery is loaded or can be loaded.
	 *
	 * @param string $file File instance.
	 * @param string $version Version parameter.
	 *
	 * @return Object Photospace_Responsive_Gallery instance
	 * @see Photospace_Responsive_Gallery()
	 * @since 1.0.0
	 * @static
	 */
	public static function instance( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}

		return self::$_instance;
	} // End instance ()

	/**
	 * Insert headers
	 *
	 * @access  public
	 *
	 * @param string $hook Hook parameter.
	 *
	 * @return  void
	 * @since   1.0.0
	 */
	public function photospace_responsive_wp_headers( $hook = '' ) {
		ob_start();
		require(dirname(__FILE__) . '/photospace-responsive-wp-headers.php');
		return ob_end_flush();
	}


	/**
	 * Run short code override
	 *
	 * @access  public
	 *
	 * @param string $hook Hook parameter.
	 *
	 * @return  void
	 * @since   1.0.0
	 */
	public function photospace_responsive_shortcode( $atts , $hook = '' ) {

		global $post;
		global $photospace_res_count;

		if ( ! empty( $atts['ids'] ) ) {
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if ( empty( $atts['orderby'] ) )
				$atts['orderby'] = 'post__in';
			$atts['include'] = $atts['ids'];
		}

		$num_thumb = intval(get_option('psres_num_thumb'));
		$num_preload = intval(get_option('psres_num_thumb'));
		$show_captions = filter_var(get_option('psres_show_captions'), FILTER_VALIDATE_BOOLEAN);
		$show_controls = filter_var(get_option('psres_show_controls'), FILTER_VALIDATE_BOOLEAN);
		$auto_play = filter_var(get_option('psres_auto_play'), FILTER_VALIDATE_BOOLEAN);
		$hide_thumbs = filter_var(get_option('psres_hide_thumbs'), FILTER_VALIDATE_BOOLEAN);

		$delay = intval(get_option('psres_delay'));

		extract(shortcode_atts(array(
			'id' => intval($post->ID),
			'num_thumb' => $num_thumb,
			'num_preload' => $num_preload,
			'show_captions' => $show_captions,
			'show_controls' => $show_controls,
			'auto_play' => $auto_play,
			'delay' => $delay,
			'hide_thumbs' => $hide_thumbs,
			'horizontal_thumb' 	=> 0,
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'include'    => '',
			'exclude'    => ''

		), $atts));


		$photospace_res_count += 1;
		$post_id = intval($post->ID) . '_' . $photospace_res_count;

		if ( 'RAND' == $order )
			$orderby = 'none';

		$hide_thumb_style = '';
		if($hide_thumbs){
			$hide_thumb_style = 'hide_me';
		}

		$thumb_style_init = "display:none; opacity:0; cursor: default;";
		$thumb_style_on  = "{'opacity' : '1' , 'display' : 'inline-block', 'cursor' : 'pointer'}";
		$thumb_style_off  = "{'opacity': '0.3' , 'display' : 'inline-block', 'cursor' : 'default'}";

		$output_buffer ='
			<div class="gallery_clear"></div>
			<div id="gallery_'.$post_id.'" class="photospace_res">
				';

				if($show_controls){
					$output_buffer .='<div id="controls_'.$post_id.'" class="controls"></div>';
				}

				$output_buffer .='
				<!-- Start Advanced Gallery Html Containers -->
				<div class="thumbs_wrap2">
					<div class="thumbs_wrap">
						<div id="thumbs_'.$post_id.'" class="thumnail_row '. $hide_thumb_style . '" >';


							if ( !empty($include) ) {
								$include = preg_replace( '/[^0-9,]+/', '', $include );
								$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

								$attachments = array();
								foreach ( $_attachments as $key => $val ) {
									$attachments[$val->ID] = $_attachments[$key];
								}
							} elseif ( !empty($exclude) ) {
								$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
								$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
							} else {
								$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
							}

							if($num_thumb < count($attachments)):

								$output_buffer .='
									<div class="psr_paging">
										<a class="pageLink prev" style="'. $thumb_style_init . '" href="#" title="Previous Page"></a>
										<a class="pageLink next" style="'.$thumb_style_init.'" href="#" title="Next Page"></a>
									</div>
								';

							endif;

							$output_buffer .='
							<ul class="thumbs noscript">';


							if ( !empty($attachments) ) {
								foreach ( $attachments as $aid => $attachment ) {
									$img = wp_get_attachment_image_src( $aid , 'photospace_responsive_full');
									$thumb = wp_get_attachment_image_src( $aid , 'photospace_responsive_thumbnails');
									$_post = get_post($aid);

									$image_title = esc_attr($_post->post_title);
									$image_alttext = get_post_meta($aid, '_wp_attachment_image_alt', true);
									$image_caption = $_post->post_excerpt;
									$image_description = $_post->post_content;

									$output_buffer .='
										<li><a class="thumb" href="' . $img[0] . '" title="' . $image_title . '" >
												<img src="' . $thumb[0] . '" alt="' . $image_alttext . '" title="' . $image_title . '" />
											</a>
											';

											$output_buffer .='
											<div class="caption">
												';
												if($show_captions){

													if($image_caption != ''){
														$output_buffer .='
															<div class="image-caption">' .  $image_caption . '</div>
														';
													}

													if($image_description != ''){
														$output_buffer .='
														<div class="image-desc">' .  $image_description . '</div>
														';
													}
												}

											$output_buffer .='

												<a class="pageLink next" style="'.$thumb_style_init.'" href="#" title="Next Page"></a>

											</div>
											';


										$output_buffer .='
										</li>
									';
									}
								}

							$output_buffer .='
							</ul>

						</div>
					</div>
				</div>

				<!-- Start Advanced Gallery Html Containers -->
				<div class="gal_content">
					';

					$output_buffer .='
					<div class="slideshow-container">
						<div id="loading_'.$post_id.'" class="loader"></div>
						<div id="slideshow_'.$post_id.'" class="slideshow"></div>
						<div id="caption_'.$post_id.'" class="caption-container"></div>
					</div>

				</div>

		</div>

		<div class="gallery_clear"></div>

		';

		$output_buffer .= "

		<script type='text/javascript'>

				jQuery(document).ready(function($) {

					// We only want these styles applied when javascript is enabled
					$('.gal_content').css('display', 'block');
					";

					$psres_enable_history = ( get_option('psres_enable_history') == 'on')? 'true' : 'false';
					$psres_auto_play = ( get_option('psres_auto_play') == 'on')? 'true' : 'false';

					$output_buffer .= "

					// Initialize Advanced Galleriffic Gallery
					var gallery = $('#thumbs_".$post_id."').galleriffic({
						delay:                     " . intval($delay) . ",
						numThumbs:                 " . intval($num_thumb) . ",
						preloadAhead:              " . intval($num_preload) . ",
						enableTopPager:            false,
						enableBottomPager:         false,
						imageContainerSel:         '#slideshow_".$post_id."',
						controlsContainerSel:      '#controls_".$post_id."',
						captionContainerSel:       '#caption_".$post_id."',
						loadingContainerSel:       '#loading_".$post_id."',
						renderSSControls:          true,
						renderNavControls:         false,
						playLinkText:              '". esc_js(get_option('psres_play_text')) ."',
						pauseLinkText:             '". esc_js(get_option('psres_pause_text')) ."',
						enableHistory:              " . $psres_enable_history . ",
						autoStart:                 	" . $psres_auto_play . ",
						enableKeyboardNavigation:		true,
						syncTransitions:           	false,
						defaultTransitionDuration: 	300,

						onTransitionOut:           function(slide, caption, isSync, callback) {
							slide.fadeTo(this.getDefaultTransitionDuration(isSync), 0.0, callback);
							caption.fadeTo(this.getDefaultTransitionDuration(isSync), 0.0);
						},
						onTransitionIn:            function(slide, caption, isSync) {
							var duration = this.getDefaultTransitionDuration(isSync);
							slide.fadeTo(duration, 1.0);

							// Position the caption at the bottom of the image and set its opacity
							var slideImage = slide.find('img');
							caption.fadeTo(duration, 1.0);

						},
						onPageTransitionOut:       function(callback) {
							//this.hide();
							setTimeout(callback, 100); // wait a bit
						},
						onPageTransitionIn:        function() {
							var prevPageLink = this.find('a.prev').css(".$thumb_style_off.");
							var nextPageLink = this.find('a.next').css(".$thumb_style_off.");

							// Show appropriate next / prev page links
							if (this.displayedPage > 0)
								prevPageLink.css(".$thumb_style_on.");

							var lastPage = this.getNumPages() - 1;
							if (this.displayedPage < lastPage)
								nextPageLink.css(".$thumb_style_on.");

							this.fadeTo('fast', 1.0);
						}

					});

					";

					if ( (bool) get_option('psres_enable_history')) {

						$output_buffer .= "

							/**** Functions to support integration of galleriffic with the jquery.history plugin ****/

							// PageLoad function
							// This function is called when:
							// 1. after calling $.historyInit();
							// 2. after calling $.historyLoad();
							// 3. after pushing Go Back button of a browser
							function pageload(hash) {
								// alert('pageload: ' + hash);
								// hash doesn't contain the first # character.
								if(hash) {
									$.galleriffic.gotoImage(hash);
								} else {
									gallery.gotoIndex(0);
								}
							}

							// Initialize history plugin.
							// The callback is called at once by present location.hash.
							$.historyInit(pageload, 'advanced.html');

							// set onlick event for buttons 
							$('a[rel=history]').on('click', function(e) {
								if (e.button != 0) return true;

								var hash = this.href;
								hash = hash.replace(/^.*#/, '');

								// moves to a new page.
								// pageload is called at once.
								$.historyLoad(hash);

								return false;
							});

							/****************************************************************************************/


							";
					}



				$output_buffer .= "

					/**************** Event handlers for custom next / prev page links **********************/

					gallery.find('a.prev').click(function(e) {
						gallery.previousPage();
						e.preventDefault();
					});

					gallery.find('a.next').click(function(e) {
						gallery.nextPage();
						e.preventDefault();
					});

				});
			</script>

			";

			return $output_buffer;


	} // End photospace_responsive_shortcode ()


	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Cloning of Photospace_Responsive_Gallery is forbidden' ) ), esc_attr( $this->_version ) );

	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Unserializing instances of Photospace_Responsive_Gallery is forbidden' ) ), esc_attr( $this->_version ) );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function install() {
		$this->_log_version_number();
	} // End install ()

	/**
	 * Log the plugin version number.
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	private function _log_version_number() { //phpcs:ignore
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

}
