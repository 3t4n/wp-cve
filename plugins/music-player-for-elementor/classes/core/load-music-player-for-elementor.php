<?php

final class Load_Music_Player_For_Elementor {

	private static $instance = null;

	/**
	 * Plugin Version
	 *
	 * @since 1.2.0
	 * @var string The plugin version.
	 */
	const PLUGIN_DOMAIN = 'music-player-for-elementor';

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action('init', array($this, 'init'));
		add_action('admin_enqueue_scripts', array($this, 'load_admin_scripts_and_styles'));
		add_action('wp_enqueue_scripts', array($this, 'load_front_scripts_and_styles'));
		add_action('activated_plugin', array($this, 'mpfe_redirect_to_dash'));
		add_filter('plugin_action_links_' . MPFE_BASE, array($this, 'add_action_links'));

		$this->include_files();

		register_activation_hook(MPFE_PLUGIN_FILE, array($this, 'mpfe_on_activate'));
		register_deactivation_hook(MPFE_PLUGIN_FILE, array($this, 'mpfe_on_deactivate'));
		add_action('wp_ajax_mpfe_prevent_notice', array($this, 'mpfe_prevent_notice'));
		add_action('admin_footer', array($this, 'mpfe_hide_rating_notice_js'));
		add_action('admin_notices', array($this, 'mpfe_rate_notice'));

		add_action('wp_ajax_mpfe_import_template', array($this, 'import_mpfe_template'));
	}

	public function init() {
		$locale = apply_filters('plugin_locale', get_locale(), self::PLUGIN_DOMAIN);
		$trans_location = trailingslashit(WP_LANG_DIR) . "plugins/" . self::PLUGIN_DOMAIN . '-' . $locale . '.mo';
		
		/*wp-content/languages/plugins/music-player-for-elementor-es_ES.mo*/
		if ($loaded = load_plugin_textdomain(self::PLUGIN_DOMAIN, FALSE, $trans_location)) {
			return $loaded;
		}

		/*music-player-for-elementor/languages/es_ES.mo*/
		load_plugin_textdomain(self::PLUGIN_DOMAIN, FALSE, MPFE_DIR_PATH . '/languages/');
	}

	public function load_admin_scripts_and_styles() {
		wp_enqueue_style('mpfe_admin_style',  MPFE_DIR_URL . '/css/mpfe-admin-style.css', array(), MPFE_VERSION);
		wp_enqueue_script('mpfe_admin_js',  MPFE_DIR_URL . '/js/mpfe-admin.js', array('jquery'), MPFE_VERSION);

		wp_localize_script(
			'mpfe_admin_js', 
			'sdata', 
			array(
				'ajaxurl'	=>	admin_url('admin-ajax.php')
			)
		);
	}

	public function load_front_scripts_and_styles() {
		wp_enqueue_style('mpfe_front_style',  MPFE_DIR_URL . 'css/mpfe-front-style.css', array(), MPFE_VERSION);
		wp_enqueue_style('font-awesome-5.15.1', MPFE_DIR_URL . 'assets/fontawesome-free-5.15.1/css/all.min.css', array(), '5.15.1', 'all');
	}

	private function include_files() {
		require_once(MPFE_DIR_PATH."/classes/core/mpfe-check-elementor.php");
		require_once(MPFE_DIR_PATH."/classes/core/mpfe-plugin-menu-pages.php");
	}

	public function mpfe_redirect_to_dash($plugin) {
	    if($plugin != 'music-player-for-elementor/music-player-for-elementor.php') {
	    	return;
	    }

        wp_safe_redirect(admin_url('admin.php?page=mpfe-dashboard'));
        exit;
	}

	public function add_action_links($links) {
		$cust_links = array(
			'<a href="' . admin_url('admin.php?page=mpfe-dashboard') . '">How To</a>'
		);

		return array_merge($links, $cust_links);
	}

	public function mpfe_on_activate() {
		add_option('mpfe_install_date', date('Y-m-d h:i:s'));
		add_option('mpfe_already_shown_rate_notice', "no");
	}

	public function mpfe_on_deactivate() {
		delete_option('mpfe_install_date');
		delete_option('mpfe_already_shown_rate_notice');
	}

	public function mpfe_rate_notice() {
		$notice_shown = get_option('mpfe_already_shown_rate_notice');

		if ("yes" == $notice_shown) {
			return;
		}

		$install_date = get_option('mpfe_install_date');
		if (false == $install_date) {
			add_option('mpfe_install_date', date('Y-m-d h:i:s'));
			return;
		}
		$display_date = date('Y-m-d h:i:s');
		$install_datetime = new DateTime($install_date);
		$display_datetime = new DateTime($display_date);

		$one_day = 60 * 60 * 24;

		$diff_interval = round(($display_datetime->format('U') - $install_datetime->format('U')) / intval($one_day));
		if ($diff_interval < 7) {
			return;
		}

		?>
		<div class="mpfe_rate_notice notice is-dismissible">
			<div class="mpfe_message_left">
				<img src="<?php echo  esc_attr(MPFE_DIR_URL . "/img/icon-256x256.png"); ?>">
			</div>
			<div class="mpfe_message_right">
				<div class="mpfe_rate_message">
					<div class="mpfe_rate_message_content">
					It looks that you've been using Music Player for Elementor for some time. That's really awesome!<br>
					Could you please do me a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation to continue implementing new features.
					</div>
				<strong><em>~ Alex from SmartWPress</em></strong>
				</div>
				<div class="mpfe_rate_options">
					<a class="mpfe_adm_btn mpfe_rate_option" target="_blank" href="https://wordpress.org/support/plugin/music-player-for-elementor/reviews/">Sure, you deserve it</a>
					<a class="mpfe_adm_btn btn_naked mpfe_rate_option mpfe_rate_close" href="#">Nope, maybe later</a>
				</div>
			</div>
		</div>

		<?php
	}

	public function mpfe_hide_rating_notice_js() {
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				jQuery('.mpfe_rate_close').click(function(){
		            var data={
		            	'action':'mpfe_prevent_notice',
		            	'id':'101',
		            }

		            var $notice = $(this).closest('.mpfe_rate_notice');

		            jQuery.ajax({
		                url: "<?php echo admin_url('admin-ajax.php'); ?>",
		                type: "post",
		                data: data,
		                dataType: "json",
		                async: !0,
		                success: function(e) {
		                    if (e=="success") {
		                       	$notice.fadeTo(100, 0, function() {
									$notice.slideUp(100, function() {
										$notice.remove();
									});
								});
		                    }
		                }
		            });
				});
			});
		</script>
		<?php
	}

	public function mpfe_prevent_notice() {
		update_option('mpfe_already_shown_rate_notice', "yes");
		echo json_encode(array("success"));
    	exit;
	}

	public function import_mpfe_template() {
		$ret = array(); 
		if (!did_action('elementor/loaded')) {
	    	$ret['success'] = false;
	    	$ret['message'] = esc_html__('Elementor plugin must be installed and active to run the template importer.', 'music-player-for-elementor');
			
			echo json_encode($ret);
			exit;
		}

		if (null == \Elementor\Plugin::instance()->templates_manager) {
	    	$ret['success'] = false;
	    	$ret['message'] = esc_html__('Could not use the Elementor importer.', 'music-player-for-elementor');
			
			echo json_encode($ret);
			exit;
		}

		$filename = $_POST['filename'];
		$filepath = MPFE_DIR_PATH . 'templates/' . $filename;

	    $fileContent = file_get_contents($filepath);
	    if (false == $fileContent) {
	    	$ret['success'] = false;
	    	$ret['message'] = esc_html__('Could not load the template file.', 'music-player-for-elementor');
			
			echo json_encode($ret);
			exit;
	    }

	    $result = \Elementor\Plugin::instance()->templates_manager->import_template( [
	            'fileData' => base64_encode( $fileContent ),
	            'fileName' => $filename,
	        ]
	    );

	    if (is_wp_error($result)) {
	    	$ret['success'] = false;
	    	$ret['message'] = $result->get_error_message();
			
			echo json_encode($ret);
			exit;
	    }

	    if (empty($result) || empty($result[0])) {
			$ret['success'] = false;
			$ret['message'] = 'Importer did not return successfully.';

			echo json_encode($ret);
			exit;
	    }

		$ret['success'] = true;

		echo json_encode($ret);
		exit;
	}

    public static function get_instance() {
        if(null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}

if (!function_exists('mpfe_instance')) {
	/**
	 * Returns an instance of the plugin class.
	 */
	function mpfe_instance() {
		return Load_Music_Player_For_Elementor::get_instance();
	}
}

mpfe_instance();


