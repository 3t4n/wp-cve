<?php

/**
 * Admin Notices class
 *
 * @package WordPress
 * @subpackage Admin Notices
 */

defined( 'ABSPATH' ) || die( 'Cheatin&#8217; uh?' );

class WCTLR_Admin_Notices {
	
	// Configuration
	// ---------------------------------------------------------------------------------------------------
	
	/**
	 * Plugin suggestions
	 */
	private $days_dismissing_suggestions = 9999; // 6 months reappear
	private $suggestions_message;
	private $suggestions;
	
	// Properties
	// ---------------------------------------------------------------------------------------------------
	/**
	 * Store missing plugins
	 */
	private $missing;
	/**
	 * Default prefix
	 * Can be changed by the external initialization.
	 */
	private $prefix = 'lbladn';
	/**
	 * Caller plugin file
	 */
	private $plugin_file;
	
	/**
	 * Single class instance
	 */
	private static $instance;
	
	// Initialization
	// ---------------------------------------------------------------------------------------------------
	
	/**
	 * Create or retrieve instance
	 */
	public static function instance( $plugin_file = null ) {
		
		// Avoid direct calls
		if ( ! function_exists( 'add_action' ) ) {
			die;
		}
		
		// Check instance
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self( $plugin_file );
		}
		
		// Done
		return self::$instance;
	}
	
	
	/**
	 * Constructor
	 */
	private function __construct( $plugin_file = null ) {
		// Main plugin file
		$this->plugin_file = isset( $plugin_file ) ? $plugin_file : __FILE__;
		
		// Uninstall hook endpoint
		register_uninstall_hook( $this->plugin_file, array( __CLASS__, 'uninstall' ) );
		
		// Prefix from namespace constant
		$this->prefix = 'wbcr_cyrandlat_an_';;
		$convert_exists_slugs_url = wp_nonce_url( add_query_arg( 'wctlr_convert_existing_slugs', '' ), 'convert_exising_slugs' );
		
		if ( in_array( get_locale(), array( 'ru_RU', 'bel', 'kk', 'uk', 'bg', 'bg_BG', 'ka_GE' ) ) ) {
			$this->suggestions_message = '<b style="font-size: 16px;">%plugin% Внимание:</b><br> <b style="color:orangered;">Вы хотите преобразовать старые записи, рубрики, метки в латиницу? Нажмите <a href="' . esc_url( $convert_exists_slugs_url ) . '" class="button button-default">преобразовать</a>, чтобы завершить процесс установки!</b><br>';
			$this->suggestions_message .= 'Если что-то пошло не так, вы можете сделать откат преобразованных ссылок с помощью плагина <a href="https://ru.wordpress.org/plugins/cyrlitera/" target="_blank">Cyrlitera</a> или связаться с нашей <a href="http://forum.webcraftic.com" target="_blank" rel="noopener">службой поддержки</a>, мы обязательно поможем вам. ';
			$this->suggestions_message .= '<br><b>Мы также рекомендуем вам более продвинутые плагины транслитерации, смотрите видео в чем их различие:</b>';
			
			$this->suggestions = array(
				'cyrlitera' => array(
					'name'     => 'Cyrlitera - это расширенный плагин транслитерации',
					'desc'     => 'В отличии от Cyr to lat reloaded, плагин Cyrlitera имеет удобный интерфейс для полного контроля преобразования ссылок, возможность отката преобразованных ссылок, возможность перенаправления со старых ссылок на новые, для устранения битых ссылок.',
					'filename' => 'cyrlitera.php',
				),
				'clearfy'   => array(
					'name'     => '<span>Clearfy - все инструменты оптимизации в одном плагине</span>',
					'desc'     => 'Комплексный плагин оптимизации Wordpress, в который уже входят функции транслитерации. Мы рекомендуем вам использовать его, потому что он выполняет комплексную оптимизацию, улучшает SEO, улучшает защиту вашего сайта, ускоряет ваш сайт не заменяя популярные плагины оптимизации, а просто дополняя их.',
					'filename' => 'clearfy.php',
				)
			);
		} else {
			//$this->suggestions_message = '<b>%plugin% Warning:</b><br>
			//Your old slugs, posts and terms are automatically converted to Latin!
			//If something went wrong or you did not know that this plugin works automatically, you can rollback the converted links using the <a href="https://wordpress.org/plugins/cyrlitera/" target="_blank">Cyrlitera</a> plugin.
			//Cyr to lat reloaded is compatible with the Cyrlitera plugin, so using the <a href="https://wordpress.org/plugins/cyrlitera/" target="_blank">Cyrlitera</a> plugin you can roll back the Cyr to lat reloaded plugin. In any case, if something does not work out for you, please let us know about the problem on the <a href="https://wordpress.org/support/plugin/cyr-and-lat" target="_blank">support forum</a>, we will help you!';
			
			$this->suggestions_message = '<b style="font-size: 16px;">%plugin% Warning:</b><br> <b style="color:orangered;">Do you want to convert old post, tag, categories slugs to Latin? Click <a href="' . esc_url( $convert_exists_slugs_url ) . '" class="button button-default">convert </a>, to complete the installation process!</b><br>';
			$this->suggestions_message .= 'If something went wrong, you can rollback converted slugs with the <a href="https://wordpress.org/plugins/cyrlitera/" target="_blank">Cyrlitera</a> plugin or contact with our <a href="http://forum.webcraftic.com" target="_blank" rel="noopener">support team</a>, we will definitely help you. ';
			$this->suggestions_message .= '<br><b>We also recommend you more advanced transliteration plugins:</b>';
			
			$this->suggestions = array(
				'cyrlitera' => array(
					'name'     => 'Cyrlitera - plugin for transliteration with extended features',
					'desc'     => 'Unlike Cyr to lat reloaded, the Cyrlitera plugin has a convenient interface for full control for tranliteration links, the ability to rollback converted links, the ability to redirect from old links to new ones, to eliminate broken links.',
					'filename' => 'cyrlitera.php',
				),
				'clearfy'   => array(
					'name'     => '<span>Clearfy - base Wordpress optimization in one plugin</span>',
					'desc'     => 'This is a free plugin to optimize Wordpress, it includes transliteration functions for links and file names. We recommend that you use it, because it performs complex optimization, improves SEO, improves securety of your site, speeds up your site without replacing the popular optimization plugins, but simply completing them. For example, you can extended YOAST SEO features at 30%, and speed optimization plug-ins by 15-20%! Clearfy will allow you to get rid of a large number of small plug-ins and reduce the load on your site. Try it, it\'s free!',
					'filename' => 'clearfy.php',
				)
			);
		}
		
		// Check notices
		if ( is_admin() ) {
			$this->check_timestamps();
			$this->check_suggestions();
		}
	}
	
	// Timestamp checks
	// ---------------------------------------------------------------------------------------------------
	
	/**
	 * Creates the activation timestamp only if it does not exist
	 */
	private function check_timestamps() {
		$timestamp = $this->get_activation_timestamp();
		if ( empty( $timestamp ) ) {
			$this->update_activation_timestamp();
		}
	}
	
	/**
	 * Check the suggestions dismissed timestamp
	 */
	private function check_suggestions() {
		
		// Compare timestamp
		$timestamp = $this->get_dismissed_timestamp( 'suggestions' );
		if ( empty( $timestamp ) || ( time() - $timestamp ) > ( $this->days_dismissing_suggestions * 86400 ) ) {
			
			// Check AJAX submit
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				add_action( 'wp_ajax_' . $this->prefix . '_dismiss_suggestions', array(
					&$this,
					'dismiss_suggestions'
				) );
				// Admin area (except install or activate plugins page)
			} elseif ( ! in_array( basename( $_SERVER['PHP_SELF'] ), array(
				//'plugins.php',
				'plugin-install.php',
				'update.php'
			) ) ) {
				add_action( 'wp_loaded', array( &$this, 'load_notices_suggestions' ), PHP_INT_MAX );
			}
		}
	}
	
	// Loaders
	// ---------------------------------------------------------------------------------------------------
	
	/**
	 * Check and load the sugestions notices
	 */
	public function load_notices_suggestions() {
		// Check the disable nag constant
		if ( $this->disable_nag_notices() ) {
			return;
		}
		
		// Collect missing plugins
		$this->missing = $this->get_missing_plugins();
		if ( ! empty( $this->missing ) && is_array( $this->missing ) ) {
			add_action( 'admin_footer', array( &$this, 'admin_footer_suggestions' ) );
			add_action( 'admin_notices', array( &$this, 'admin_notices_suggestions' ) );
		}
	}
	
	// Admin Notices display
	// ---------------------------------------------------------------------------------------------------
	
	/**
	 * Suggestions display
	 */
	public function admin_notices_suggestions() {
		$plugin_data = get_plugin_data( $this->plugin_file );
		$is_ru       = in_array( get_locale(), array( 'ru_RU', 'bel', 'kk', 'uk', 'bg', 'bg_BG', 'ka_GE' ) );
		
		?>
        <div class="<?php echo esc_attr( $this->prefix ); ?>-dismiss-suggestions notice notice-success is-dismissible" data-nonce="<?php echo esc_attr( wp_create_nonce( $this->prefix . '-dismiss-suggestions' ) ); ?>">
            <p><?php echo str_replace( '%plugin%', $plugin_data['Name'], $this->suggestions_message ); ?></p>
			<?php if ( $is_ru ): ?>
                <a href="https://youtu.be/fNRWy-1aZmA" style="float:left;margin: 10px 15px 10px 0;" target="_blank" rel="noopener"><img src="<?= WCTLR_PLUGIN_URL; ?>/assets/img/ctr-reloaded-video_03.png" alt=""></a>
			<?php endif; ?>
            <ul>
				<?php foreach ( $this->missing as $plugin ) : ?>
                    <li><strong><?php echo $this->suggestions[ $plugin ]['name']; ?></strong>
                        <a href="<?php echo esc_url( $this->get_install_url( $plugin ) ); ?>">
							<?php if ( $is_ru ): ?>
                                (Установить)
							<?php else: ?>
                                (Install)
							<?php endif ?>
                        </a><br/><?php echo $this->suggestions[ $plugin ]['desc']; ?></li>
				<?php endforeach; ?>
            </ul>
            <div style="clear:both;"></div>
        </div>
		<?php
	}
	
	// AJAX Handlers
	// ---------------------------------------------------------------------------------------------------
	
	/**
	 * Dismiss suggestions
	 */
	public function dismiss_suggestions() {
		if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], $this->prefix . '-dismiss-suggestions' ) ) {
			$this->update_dismissed_timestamp( 'suggestions' );
		}
	}
	
	
	// Plugins information retrieval
	// ---------------------------------------------------------------------------------------------------
	
	/**
	 * Retrieve uninstalled plugins
	 */
	private function get_missing_plugins() {
		
		// Initialize
		$inactive = array();
		
		// Check plugins directory
		$directories = array_merge( self::get_mu_plugins_directories(), self::get_plugins_directories() );
		if ( ! empty( $directories ) ) {
			$required = array_keys( $this->suggestions );
			foreach ( $required as $plugin ) {
				if ( ! in_array( $plugin, $directories ) ) {
					$inactive[] = $plugin;
				}
			}
		}
		
		// Check inactives
		if ( empty( $inactive ) ) {
			return false;
		}
		
		// Done
		return $inactive;
	}
	
	
	/**
	 * Collects all active plugins
	 */
	private function get_plugins_directories() {
		
		// Initialize
		$directories = array();
		
		// Plugins split directory
		$split = '/' . basename( WP_CONTENT_DIR ) . '/' . basename( WP_PLUGIN_DIR ) . '/';
		
		// Multisite plugins
		if ( is_multisite() ) {
			$ms_plugins = wp_get_active_network_plugins();
			if ( ! empty( $ms_plugins ) && is_array( $ms_plugins ) ) {
				foreach ( $ms_plugins as $file ) {
					$directory = explode( $split, $file );
					$directory = explode( '/', ltrim( $directory[1], '/' ) );
					$directory = $directory[0];
					if ( ! in_array( $directory, $directories ) ) {
						$directories[] = $directory;
					}
				}
			}
		}
		
		// Active plugins
		$plugins = wp_get_active_and_valid_plugins();
		if ( ! empty( $plugins ) && is_array( $plugins ) ) {
			foreach ( $plugins as $file ) {
				$directory = explode( $split, $file );
				$directory = explode( '/', ltrim( $directory[1], '/' ) );
				$directory = $directory[0];
				if ( ! in_array( $directory, $directories ) ) {
					$directories[] = $directory;
				}
			}
		}
		
		// Done
		return $directories;
	}
	
	
	/**
	 * Retrieve mu-plugins directories
	 */
	private function get_mu_plugins_directories() {
		
		// Initialize
		$directories = array();
		
		// Dependencies
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		
		// Retrieve mu-plugins
		$plugins = get_plugins( '/../mu-plugins' );
		if ( ! empty( $plugins ) && is_array( $plugins ) ) {
			foreach ( $plugins as $path => $info ) {
				$directory = dirname( $path );
				if ( ! in_array( $directory, array( '.', '..' ) ) ) {
					$directories[] = $directory;
				}
			}
		}
		
		// Done
		return $directories;
	}
	
	
	/**
	 * Plugin install/activate URL
	 */
	private function get_install_url( $plugin ) {
		if ( ! isset( $this->suggestions[ $plugin ]['filename'] ) && isset( $this->suggestions[ $plugin ]['url'] ) ) {
			return $this->suggestions[ $plugin ]['url'];
		}
		
		// Check existing plugin
		$exists = @file_exists( WP_PLUGIN_DIR . '/' . $plugin );
		
		// Activate
		if ( $exists ) {
			
			// Existing plugin
			$path = $plugin . '/' . $this->suggestions[ $plugin ]['filename'];
			
			return admin_url( 'plugins.php?action=activate&plugin=' . $path . '&_wpnonce=' . wp_create_nonce( 'activate-plugin_' . $path ) );
			// Install
		} else {
			
			// New plugin
			return admin_url( 'update.php?action=install-plugin&plugin=' . $plugin . '&_wpnonce=' . wp_create_nonce( 'install-plugin_' . $plugin ) );
		}
	}
	
	/**
	 * Determines the admin notices display
	 */
	private function disable_nag_notices() {
		return ( defined( 'DISABLE_NAG_NOTICES' ) && DISABLE_NAG_NOTICES );
	}
	
	// Plugin related
	// ---------------------------------------------------------------------------------------------------
	
	/**
	 * Plugin uninstall hook
	 */
	public static function uninstall() {
		$admin_notices = self::instance();
		$admin_notices->delete_activation_timestamp();
		$admin_notices->delete_dismissed_timestamp( 'suggestions' );
	}
	
	
	
	// Activation timestamp management
	// ---------------------------------------------------------------------------------------------------
	
	/**
	 * Retrieves the plugin activation timestamp
	 */
	private function get_activation_timestamp() {
		return (int) get_option( $this->prefix . '_activated_on' );
	}
	
	/**
	 * Updates activation timestamp
	 */
	private function update_activation_timestamp() {
		update_option( $this->prefix . '_activated_on', time(), true );
	}
	
	/**
	 * Removes activation timestamp
	 */
	private function delete_activation_timestamp() {
		delete_option( $this->prefix . '_activated_on' );
	}
	
	// Dismissed timestamp management
	// ---------------------------------------------------------------------------------------------------
	
	/**
	 * Current timestamp by key
	 */
	private function get_dismissed_timestamp( $key ) {
		return (int) get_option( $this->prefix . '_dismissed_' . $key . '_on' );
	}
	
	/**
	 * Update with the current timestamp
	 */
	private function update_dismissed_timestamp( $key ) {
		update_option( $this->prefix . '_dismissed_' . $key . '_on', time(), true );
	}
	
	/**
	 * Removes dismissied option
	 */
	private function delete_dismissed_timestamp( $key ) {
		delete_option( $this->prefix . '_dismissed_' . $key . '_on' );
	}
	
	// Javascript code
	// ---------------------------------------------------------------------------------------------------
	
	/**
	 * Footer script for Suggestions
	 */
	public function admin_footer_suggestions() {
		?>
        <script type="text/javascript">
			jQuery(function($) {

				$(document).on('click', '.<?php echo $this->prefix; ?>-dismiss-suggestions .notice-dismiss', function() {
					$.post(ajaxurl, {
						'action': '<?php echo $this->prefix; ?>_dismiss_suggestions',
						'nonce': $(this).parent().attr('data-nonce')
					});
				});

			});
        </script>
		<?php
	}
}
