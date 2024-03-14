<?php

class PixGridder{

	/**
	 * @since   2.0.4
	 *
	 * @var     string
	 */
	protected $version = '2.0.4';

	/**
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'pixgridder';

	/**
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_name = 'PixGridder';

	/**
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * @since     2.0.5
	 */
	public function __construct() {
		add_action( 'loop_start', array( &$this, 'move_nextpage' ) );
		add_action( 'init', array( &$this, 'load_plugin_textdomain' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts' ) );
		add_action( 'admin_menu', array( &$this, 'add_menu' ) );
		add_action( 'add_meta_boxes', array( &$this, 'add_meta' ) );
		add_action( 'save_post', array( &$this, 'content_save' ) );
		add_action( 'save_post', array( &$this, 'disable_save' ) );
		add_action( 'wp_ajax_pixgridder_height_preview', array( &$this, 'save_height_preview' ) );
		add_action( 'wp_ajax_pixgridder_data_save', array( &$this, 'save_via_ajax' ) );
		add_action( 'admin_head', array( &$this, 'js_vars' ) );
		add_action( 'admin_head', array( &$this, 'add_tinyMCE' ) );
		add_action( 'admin_head', array( &$this, 'default_editor' ) );
		add_filter( 'admin_body_class', array( &$this, 'admin_class_by_editor' ) );

		add_action( 'wp_enqueue_scripts', array( &$this, 'front_styles' ) );
		add_filter( 'body_class', array( &$this, 'body_class' ) );
		add_filter( 'the_content', array( &$this, 'filter_content' ), 10 );
		add_filter( 'the_content', array( &$this, 'filter_content' ), 100 );
 		add_filter( 'mce_css', array( &$this, 'add_tinymce_css' ) );
   }

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
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
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		self::add_general();
	}

	/**
	 * Save the options on the admin panel via AJAX.
	 *
	 * @since    2.0.5
	 */
	public function save_via_ajax() {
		global $options;
		check_ajax_referer('pixgridder_data', 'pixgridder_security');

		$data = $_POST;
		unset($data['pixgridder_security'], $data['action']);

		foreach ($_REQUEST as $key => $value) {
			if( isset($_REQUEST[$key]) ) {
				update_option($key, $value);
			}
		}		
	}

	/**
	 * Fired when the plugin is uninstall.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function uninstall( $network_wide ) {

        global $wpdb;
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '%pixgridder_%'");

        $results = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_content LIKE '%pixgridder%' AND post_name NOT LIKE '%autosave%' AND post_name NOT LIKE '%revision%'");
        foreach ( $results as $result ) 
        {
            $id = $result->ID;
            $content = $result->post_content;
            $content = preg_replace('/<!--pixgridder(.*?)-->/', '', $content);
            $content = preg_replace('/<!--\/pixgridder(.*?)-->/', '', $content);
            $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET post_content = %s WHERE ID = $id", $content ) );
        }
	}

	/**
	 * Load text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Load tinyMCE custom functions.
	 *
	 * @since    2.0.6
	 */
	public function add_tinyMCE() {
		global $post, $pagenow;

		if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
			$typenow = get_post_type();
			$editor = get_post_meta( $post->ID, 'pixBuilderDisable', true );

		    if ($typenow == 'page' && $editor != 'on' ) {
		        $display = true;
	        } else {
	        	$display = false;
	        }
			
			if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
				return;
			if ( get_user_option('rich_editing') == 'true' && ( $display == true ) ) {
				add_filter('mce_external_plugins', 'add_pixgridder_js' );
			}

			function add_pixgridder_js($plugin_array) {
				$plugin_array['pixgridder'] = PIXGRIDDER_URL.'scripts/pixgridder_tinyMCE.js';
				return $plugin_array;
			}		

			function tinymce_settings($settings) {
				if ( $display == true ) {
					//$settings['extended_valid_elements'] = "span[!class]";
				    $settings['theme_advanced_resizing'] = false;
				    $settings['wp_autoresize_on'] = false;
				}
			    return $settings;
			}
			add_filter('tiny_mce_before_init','tinymce_settings');
		}
	}

	/**
	 * Register and enqueue front-end style sheets.
	 *
	 * @since    1.0.0
	 */
	public function front_styles() {
		$theme_style = get_stylesheet_directory().'/gridder.css';
		if (file_exists($theme_style)) {
			wp_enqueue_style( $this->plugin_slug, get_stylesheet_directory_uri().'/gridder.css', array(), $this->version );
		} else {
			wp_enqueue_style( $this->plugin_slug, PIXGRIDDER_URL.'css/front-gridder.css', array(), $this->version );
		}
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since    1.0.0
	 */
	public function admin_styles() {
		global $pagenow;
		if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_style( $this->plugin_slug .'-fontello', PIXGRIDDER_URL.'css/fontello.css', array(), $this->version );
			wp_enqueue_style( $this->plugin_slug .'-open-sans', PIXGRIDDER_URL.'css/open_sans.css', array(), $this->version );
			wp_enqueue_style( $this->plugin_slug, PIXGRIDDER_URL.'css/gridder.css', array(), $this->version );
		}
		if ('options-general.php' == $pagenow && isset($_GET['page']) && $_GET['page']=='pixgridder_admin') {
			wp_enqueue_style( $this->plugin_slug .'-fontello', PIXGRIDDER_URL.'css/admin.css', array(), $this->version );
		}
	}

	/**
	 * Register and enqueue admin-specific scripts.
	 *
	 * @since    1.0.0
	 */
	public function admin_scripts() {
		global $pagenow;
		if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
			wp_enqueue_script( $this->plugin_slug . '-modernizr', PIXGRIDDER_URL.'scripts/modernizr.pix.js', array(), '2.6.2' );
			wp_enqueue_script( $this->plugin_slug . '-ui-touch-punch', PIXGRIDDER_URL.'scripts/jquery.ui.touch-punch.min.js', array('jquery-ui-mouse'), '0.2.2', false );
			wp_enqueue_script( $this->plugin_slug . '-livequery', PIXGRIDDER_URL.'scripts/jquery.livequery.js', array('jquery'), '1.1.1', false );
			wp_enqueue_script( $this->plugin_slug, PIXGRIDDER_URL.'scripts/gridder.js', array($this->plugin_slug.'-modernizr','jquery','jquery-ui-core',$this->plugin_slug.'-ui-touch-punch','jquery-ui-sortable',$this->plugin_slug.'-livequery','jquery-ui-resizable','jquery-ui-dialog') );
		}
		if ('options-general.php' == $pagenow && isset($_GET['page']) && $_GET['page']=='pixgridder_admin') {
			wp_enqueue_script( $this->plugin_slug . '-admin', PIXGRIDDER_URL.'scripts/admin.js', array('jquery'), $this->version );
		}
	}

	/**
	 * Set the defalt tinyMCE editor.
	 *
	 * @since    1.0.0
	 */
	public function default_editor() {
		global $post, $pagenow;

		if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
			$typenow = get_post_type();
			$editor = get_post_meta( $post->ID, 'pixBuilderDisable', true );

			$pixgridder_array_rules_ = get_option('pixgridder_array_rules_'); 

		    if ( $typenow == 'page' && $editor != 'on' ) {
		        $display = true;
	        } else {
		        $display = false;        	
	        }	    	

			if ( $display == true ) {
				add_filter( 'wp_default_editor', create_function('', 'return "tinymce";') );
			}
		}
	}


	/**
	 * Add the class "pixgridder" to the front-end body
	 *
	 * @since    1.0.0
	 */
	public function body_class($classes) {
		$classes[] = 'pixgridder';
		return $classes;
	}


	/**
	 * Change the admin body class if the grid builder is activated for that particular post/page.
	 *
	 * @since    1.0.0
	 */
	public function admin_class_by_editor($classes) {
		global $pagenow, $post;

		if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {

			$typenow = get_post_type();
			$editor = get_post_meta( $post->ID, 'pixBuilderDisable', true );

		    if ( $typenow == 'page' && $editor != 'on' ) {
		        $display = true;
	        } else {
		        $display = false;        	
	        }	    	
			if ( $display==true ) {
				$classes .= ' pix_body_builder';
			}

			return $classes;
		}
	}

	/**
	 * Add the metaboxes: the grid builder and its tabs to switch between builder and preview.
	 *
	 * @since    1.0.0
	 */
	public function add_meta() {
		global $post;

		$editor = get_post_meta( $post->ID, 'pixBuilderDisable', true );

	    if ( $editor != 'on' ) {
	        $display = true;
        } else {
	        $display = false;        	
        }	    	

		if ( $display == true ) {
	        add_meta_box( 'pixgridder_builder', 'PixGridder', 'pixgridder_builder', 'page', 'normal', 'low' );
	        add_meta_box( 'pixgridder_content', 'PixGridder Content', 'pixgridder_content', 'page', 'normal' );
		}

		if ( get_option('pixgridder_hide_donate')!='true' )
	        add_meta_box( 'pixgridder_donate', __( 'Thank you for using PixGridder', 'pixgridder' ), array( $this, 'meta_donate_output' ), 'page', 'side');
	    
		add_meta_box( 'pixgridder_disable', 'PixGridder options', 'pixgridder_disable', 'page', 'normal', 'high' );

		function pixgridder_content( $post, $display ) {
		    $values = get_post_custom( $post->ID );
		    $pixgridder_content = isset( $values['pixgridder_content'] ) ? esc_attr( $values['pixgridder_content'][0] ) : '';
		    wp_nonce_field( 'pixgridder_content_nonce', 'pixgridder_content_nonce' );
		    ?>
		    <div class="pix_meta_boxes">
		        <p>
		            <label for="pixBuilderTxtArea"><?php _e('Content','pixgridder'); ?></label><br>
		            <div class="field_wrap"><textarea name="pixgridder_content" id="pixBuilderTxtArea" ><?php echo $pixgridder_content; ?></textarea></div>
		        </p>
		        <div class="clear"></div>
		        
		 
		    </div><!-- .pix_meta_boxes -->
		    <?php  
		}

		function pixgridder_builder( $post, $display ) {
		    require_once( PIXGRIDDER_PATH.'lib/pixgridder-builder.php' );
		}

		function pixgridder_disable( $post, $display ) {
		    $values = get_post_custom( $post->ID );
			$pixBuilderDisable = isset( $values['pixBuilderDisable'] ) ? esc_attr( $values['pixBuilderDisable'][0] ) : 'off';
			$pixBuilderRemove = isset( $values['pixBuilderRemove'] ) ? esc_attr( $values['pixBuilderRemove'][0] ) : 'off';
		    wp_nonce_field( 'pixgridder_disable_nonce', 'pixgridder_disable_nonce' );
		    ?>
		    <div class="pix_meta_boxes">
		        <p>
		            <label for="pixBuilderDisable"><?php _e('Disable the grid builder','pixgridder'); ?>
		            <input type="checkbox" name="pixBuilderDisable" id="pixBuilderDisable" <?php checked( $pixBuilderDisable, 'on' ); ?>></label>
		        </p>
		        <p>
		            <label for="pixBuilderRemove"><?php _e('Remove any trace of PixGridder from this page','pixgridder'); ?>
		            <input type="checkbox" name="pixBuilderRemove" id="pixBuilderRemove"></label>
		        </p>
		        <p>
		        	<small>Icons are from <a href="http://fontello.com/" target="_blank">Fontello.com</a> (licenses available there for all the sets)</small>
		        </p>
		    </div><!-- .pix_meta_boxes -->
		    <?php 
		}

	}

	/**
	 * Save the data sent thorugh metaboxes.
	 *
	 * @since    1.0.0
	 */
	public function content_save( $post_id ) {
	    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	    if( !isset( $_POST['pixgridder_content_nonce'] ) || !wp_verify_nonce( $_POST['pixgridder_content_nonce'], 'pixgridder_content_nonce' ) ) return;

	    if( !current_user_can( 'edit_post', $post_id ) ) return;
	    
	    if( isset( $_POST['pixgridder_content'] ) )
	        update_post_meta( $post_id, 'pixgridder_content', esc_attr( $_POST['pixgridder_content'] ) );
	        
	}
	public function disable_save( $post_id ) {
	    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	    if( !isset( $_POST['pixgridder_disable_nonce'] ) || !wp_verify_nonce( $_POST['pixgridder_disable_nonce'], 'pixgridder_disable_nonce' ) ) return;

	    if( !current_user_can( 'edit_post', $post_id ) ) return;
	    
		$chkedit = ( isset( $_POST['pixBuilderDisable'] ) && $_POST['pixBuilderDisable'] ) ? 'on' : 'off';
		update_post_meta( $post_id, 'pixBuilderDisable', $chkedit );
	    
		if ( isset( $_POST['pixBuilderRemove'] ) && $_POST['pixBuilderRemove'] ) {
	        global $wpdb;
	        $results = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID = $post_id");
	        foreach ( $results as $result ) {
	            $content = $result->post_content;
	            $content = preg_replace('/<!--pixgridder(.*?)-->/', '', $content);
	            $content = preg_replace('/<!--\/pixgridder(.*?)-->/', '', $content);
	            $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET post_content = %s WHERE ID = $post_id", $content ) );
	        }
		}
	}

	/**
	 * Save via AJAX the height of the preview wrap.
	 *
	 * @since    1.0.0
	 */
	public function save_height_preview() {
		update_option('pixgridder_height_preview',$_POST['height']);
		die();
	}

	/**
	 * Save via AJAX the height of the preview wrap.
	 *
	 * @since    2.0.1
	 */
	public function filter_content($content) {

	    global $post;

		$typenow = get_post_type();
		$editor = get_post_meta( $post->ID, 'pixBuilderDisable', true );

	    if ( $typenow == 'page' ) {
	        $display = true;
        } else {
	        $display = false;        	
        }

        $row_open = apply_filters('pixgridder_row_open', "<div class=\"row\" data-cols=\"$1\">");
        $row_close = apply_filters('pixgridder_row_close', "</div><!--.row[data-cols=\"$1\"]-->");
        $column_open = apply_filters('pixgridder_column_open', "<div class=\"column\" data-col=\"$1\">");
        $column_close = apply_filters('pixgridder_column_close', "</div><!--.column[data-col=\"$1\"]-->");

	    if ( $display == true ) {

			require_once( ABSPATH . WPINC . '/class-oembed.php' );
			$oembed = _wp_oembed_get_object();
			$providers = $oembed->providers;

			if ( !function_exists('pixgridder_match_oembed') ) {
				function pixgridder_match_oembed($matches) {
		        	$var = preg_replace('/<p>/', '', $matches[0]);
		        	$var = preg_replace('/<\/p>/', '', $var);
				    global $wp_embed;
		            return $wp_embed->autoembed($var);
		        }
		    }

			foreach ($providers as $key => $value) {
				if(substr($key,0,1) == '#') {
					$content = preg_replace_callback(
				        "$key",
				        'pixgridder_match_oembed',
				        $content
				    );
				}
			}

			$content = preg_replace('/data-id\[(.+?)\]/', 'id="$1"', $content);
			$content = preg_replace('/data-class\[(.+?)\]/', 'class="$1"', $content);
			$content = preg_replace('/<!--pixgridder:column\[(.?[^\]\s]+)\]--><!--\/pixgridder:column(.+?)-->/', '', $content);
			$content = preg_replace('/<!--pixgridder:row\[(.?[^\]\s]+)\]--><!--\/pixgridder:row(.+?)-->/', '', $content);
			$content = preg_replace('/<p><!--pixgridder:(.+?)-->(?!<!--)/', '<!--pixgridder:$1--><p>', $content);
			$content = preg_replace('/<p><!--\/pixgridder:(.+?)-->(?!<!--)/', '<!--/pixgridder:$1--><p>', $content);
			$content = preg_replace('/<p><!--pixgridder:(.+?)--><\/p>/', '<!--pixgridder:$1-->', $content);
			$content = preg_replace('/<p><!--\/pixgridder:(.+?)--><\/p>/', '<!--/pixgridder:$1-->', $content);
			$content = preg_replace('/<!--\/pixgridder:(.+?)--><p><\/p>/', '<!--/pixgridder:$1-->', $content);
			if ( strpos($column_open,' class=') !== false ) {
				preg_match('/ class=[\'"](.+?)[\'"]/',$column_open,$class);
				$column_open = preg_replace('/ class=[\'"](.+?)[\'"]/', ' class="$1 dollar2"', $column_open);
				$column_open = str_replace("dollar2", "$2", $column_open);
				$content = preg_replace('/<!--pixgridder:column(.+?) class="(.+?)"-->/', $column_open, $content);
				$column_open = str_replace(" $2", "", $column_open);
				$content = preg_replace('/<!--pixgridder:column(.+?)-->/', $column_open, $content);
				$content = preg_replace('/data-col="\[col=(.?[^\]\s]+)\] id="(.+?)""/', 'data-col="$1" id="$2"', $content);
				$content = preg_replace('/data-col="\[col=(.+?)\]"/', 'data-col="$1"', $content);
			} else {
				$content = preg_replace('/<!--pixgridder:column\[col=(.+?)\]-->/', $column_open, $content);
				$column_open = preg_replace('/<(.+?)>/', '<$1 dollar2>', $column_open);
				$column_open = str_replace("dollar2", "$2", $column_open);
				$content = preg_replace('/<!--pixgridder:column\[col=(.?[^\]\s]+)\](.+?)-->/', $column_open, $content);
			}
			$content = preg_replace('/ class=/', ' class=', $content);
			if ( strpos($row_open,' class=') !== false ) {
				preg_match('/ class=[\'"](.+?)[\'"]/',$row_open,$class);
				$row_open = preg_replace('/ class=[\'"](.+?)[\'"]/', ' class="$1 dollar2"', $row_open);
				$row_open = str_replace("dollar2", "$2", $row_open);
				$content = preg_replace('/<!--pixgridder:row(.+?) class="(.+?)"-->/', $row_open, $content);
				$row_open = str_replace(" $2", "", $row_open);
				$content = preg_replace('/<!--pixgridder:row(.+?)-->/', $row_open, $content);
				$content = preg_replace('/data-cols="\[cols=(.?[^\]\s]+)\] id="(.+?)""/', 'data-cols="$1" id="$2"', $content);
				$content = preg_replace('/data-cols="\[cols=(.+?)\]"/', 'data-cols="$1"', $content);
			} else {
				$content = preg_replace('/<!--pixgridder:row\[cols=(.+?)\]-->/', $row_open, $content);
				$row_open = preg_replace('/<(.+?)>/', '<$1 dollar2>', $row_open);
				$row_open = str_replace("dollar2", "$2", $row_open);
				$content = preg_replace('/<!--pixgridder:row\[cols=(.?[^\]\s]+)\](.+?)-->/', $row_open, $content);
			}
			$content = preg_replace('/<!--\/pixgridder:row\[cols=(.+?)\]-->/', $row_close, $content);
			$content = preg_replace('/<!--\/pixgridder:column\[col=(.+?)\]-->/', $column_close, $content);
			$content = preg_replace('/  class=/', ' class=', $content);
			$content = preg_replace('/<p><\/p>/', '', $content);

		} else {

			$content = preg_replace('/<!--pixgridder(.+?)-->/', '', $content);
			$content = preg_replace('/<!--\/pixgridder(.+?)-->/', '', $content);

		}

		return $content;
	}

	/**
	 * Fix for <!--nextpage--> position
	 *
	 * @since    2.0.2
	 */
	function move_nextpage($post){

		global $post, $pixgridder_move_nextpage;

		if ( !$post )
			return;

		if ( $pixgridder_move_nextpage != true ) {
			$content = $post->post_content;

			$content = preg_replace('/<!--nextpage-->(.+?)<!--\/pixgridder:row(.+?)-->/s', "$1<!--/pixgridder:row1[$2]-->\n\n<!--nextpage-->\n\n", $content);
			$post->post_content = $content;

			$pixgridder_move_nextpage = true;
		}

		return $post;

	}

	/**
	 * Options.
	 *
	 * @since    2.0.5
	 */
	public static function register_options() {
	    global $options;

		$options = array (
			array( "id" => "pixgridder_height_preview",
				"std" => "550"),
			array( "id" => "pixgridder_hide_donate",
				"std" => "0")
		);
		
		self::pixgridder_admin( array( &$this, 'register_options' ) );
		return $options;
	}


	/**
	 * Register options in the database.
	 *
	 * @since    1.0.0
	 */
	public static function add_general() {
		global $options;
		self::register_options();
		
		foreach ($options as $value) :
			if(!get_option($value['id'])){
				add_option($value['id'], $value['std']);
			}
		endforeach;
	}

	/**
	 * Set the content width as JS var.
	 *
	 * @since    1.0.0
	 */
	public function js_vars() {
		global $content_width, $post, $pagenow;

		if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
			$typenow = get_post_type();
			$editor = get_post_meta( $post->ID, 'pixBuilderDisable', true );

		    if ($typenow == 'page' && $editor != 'on' ) {
		        $display = 'true';
	        } else {
	        	$display = 'false';
	        }
			if ( isset( $content_width ) ) {
				$pix_content_width = $content_width;
		    } else {
				$pix_content_width = 980;
		    } ?>

			<script type="text/javascript">
			//<![CDATA[
				var pixgridder_content_width = <?php echo $pix_content_width; ?>, pixgridder_display = <?php echo $display; ?>, pixgridder_url = "<?php echo PIXGRIDDER_URL; ?>", pixgridder_preview_text = "<?php _e('Preview','pixgridder'); ?>", pixgridder_builder_text = "<?php _e('Builder','pixgridder'); ?>";
			//]]>
			</script>
		<?php }
	}

	/**
	 * Add custom stylesheet to tinyMCE.
	 *
	 * @since    2.0.6
	 */
	public static function add_tinymce_css($wp) {
		global $post, $pagenow;

		if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
			$typenow = get_post_type();
			$editor = get_post_meta( $post->ID, 'pixBuilderDisable', true );

		    if ($typenow == 'page' && $editor != 'on' ) {
		        $wp .= ',' . PIXGRIDDER_URL . 'css/tinymce_frame.css';
	        }

	    }
        return $wp;
    }

	/**
	 * Adds descriptive page.
	 *
	 * @since    2.0.5
	 */
	public function add_menu() {
		if (function_exists('add_options_page') && get_option('pixgridder_hide_donate')!='true') {
			add_options_page($this->plugin_name, $this->plugin_name, 'activate_plugins', 'pixgridder_admin', array( $this, 'register_options' ));
		}
	}

	/**
	 * Display the menu for admin panel.
	 *
	 * @since    2.0.5
	 */
	public static function pixgridder_admin() {
		require_once( PIXGRIDDER_PATH . 'lib/admin.php' );
	}

	/**
	 * Donate metabox content.
	 *
	 * @since    2.0.5
	 */
	public function meta_donate_output( $post ) {

		printf( __('<p>Remove this box and consider, please, to support the plugin author somehow.<br><a href="%1$s">Just visit this page</a></p>', 'hilite'),
			esc_url( admin_url( 'options-general.php?page=pixgridder_admin' ) )
		);
	}
}