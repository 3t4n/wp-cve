<?php
namespace Thim_EL_Kit\Elementor;

use Thim_EL_Kit\SingletonTrait;
use Thim_EL_Kit\LoginRegisterTrait;
use Thim_EL_Kit\Settings;
use Elementor\Plugin;
class Hooks {
	use SingletonTrait;
	use LoginRegisterTrait;

	public function __construct() {
		$this->hook_login_register_from();
		// Add script to head dark mode.
		add_action( 'wp_head', array( $this, 'add_dark_mode_script' ), 1 );
		add_action( 'wp_head', array( $this, 'add_dark_mode_styles' ), 1 );
		$this->init_ajax_hooks();
	}

	public function hook_login_register_from() {
		// end
		// redirect after login success
		add_filter( 'login_redirect', array( $this, 'login_success_redirect' ), 99999, 3 );

		// redirect if login false
		add_filter( 'authenticate', array( $this, 'login_authenticate' ), 99999, 2 );
		/*** End login user */

		/*** Register user */
		// Check extra register if set auto login when register
		add_action( 'register_post', array( $this, 'check_extra_register_fields' ), 10, 3 );

		// Update password if set auto login when register
		add_action( 'user_register', array( $this, 'register_update_pass_and_login' ), 99999 );

		// redirect if register false
		add_action( 'registration_errors', array( $this, 'register_failed' ), 99999, 3 );

		// redirect if register success if not set auto login when register
		add_action( 'register_new_user', array( $this, 'register_verify_mail_success_redirect' ), 999999 );

		add_filter( 'wp_new_user_notification_email', array( $this, 'message_set_password_when_not_auto_login' ), 999999, 2 );
		/*** End register user */

		/*** Reset password */
		add_action( 'lostpassword_post', array( $this, 'check_field_to_reset_password' ), 99999, 1 );
		add_filter( 'login_form_rp', array( $this, 'validate_password_reset' ), 99999 );
		add_filter( 'login_form_resetpass', array( $this, 'validate_password_reset' ), 99999 );

		/*** Override message send mail with case auto-login */
		add_filter( 'password_change_email', array( $this, 'message_when_user_register_auto_login' ), 999999, 1 );

	}

	public function add_dark_mode_styles() {
		$options = get_option( Settings::ADVANCED_OPTIONS, array() );

		if ( empty( $options['enableDarkMode'] ) ) {
			return;
		}

		$styles = '';

		if ( ! empty( $options ) ) {
			if ( ! empty( $options['elementorSystemColors'] ) ) {
				foreach ( $options['elementorSystemColors'] as $key => $value ) {
					$styles .= '--e-global-color-' . $key . ': ' . $value . ';';
				}
			}
			if ( ! empty( $options['elementorThemeColors'] ) ) {
				foreach ( $options['elementorThemeColors'] as $key => $value ) {
					$styles .=  $key . ': ' . $value . ';';
				}
			}

			if ( ! empty( $options['customVariableColors'] ) ) {
				foreach ( $options['customVariableColors'] as $key => $value ) {
					if ( ! empty( $value['property'] ) ) {
						$styles .= $value['property'] . ': ' . ( ! empty( $value['value'] ) ? $value['value'] : 'transparent' ) . ';';
					}
				}
			}
		}

		if ( ! empty( $styles ) ) {
			?>
			<style>
				.thim-ekit-dark-mode body {
					<?php echo esc_html( $styles ); ?>
				}
			</style>
			<?php
		}
	}

	public function add_dark_mode_script() {
		$options = get_option( Settings::ADVANCED_OPTIONS, array() );

		if ( empty( $options['enableDarkMode'] ) ) {
			return;
		}
		// Add script to head.
		?>
		<script>
			(function () {
				'use strict';
				var storageKey = 'thimEkitDarkMode';
				var classNameDark = 'thim-ekit-dark-mode';
				var classNameLight = 'thim-ekit-light-mode';
				var darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
				var darkModeOn = darkModeMediaQuery.matches;
				var rootElement = document.documentElement;
				var localStorageTheme = null;

				try {
					localStorageTheme = localStorage.getItem(storageKey);
				} catch (err) {
				}

				if (localStorageTheme === 'dark') {
					darkModeOn = true;
				} else if (localStorageTheme === 'light') {
					darkModeOn = false;
				}

				if (darkModeOn) {
					rootElement.classList.add(classNameDark);
				} else {
					rootElement.classList.add(classNameLight);
				}

				function toggleDarkMode() {
					if (rootElement.classList.contains(classNameDark)) {
						rootElement.classList.remove(classNameDark);
						rootElement.classList.add(classNameLight);
						try {
							localStorage.setItem(storageKey, 'light');
						} catch (err) {
						}
					} else {
						rootElement.classList.remove(classNameLight);
						rootElement.classList.add(classNameDark);
						try {
							localStorage.setItem(storageKey, 'dark');
						} catch (err) {
						}
					}
				}

				function handleDarkModeChange(e) {
					if (e.matches) {
						toggleDarkMode();
					} else {
						toggleDarkMode();
					}
				}

				darkModeMediaQuery.addListener(handleDarkModeChange);

				document.addEventListener('DOMContentLoaded', function () {
					var darkModeToggle = document.querySelector('.dark-mode-toggle');
					if (darkModeToggle) {
						darkModeToggle.addEventListener('click', toggleDarkMode);
					}
				});
			})();
		</script>
		<?php
	}

	public function init_ajax_hooks() {
 		add_action( 'wp_ajax_thim_load_content', array( $this, 'ajax_load_content_course' ) );
		add_action( 'wp_ajax_nopriv_thim_load_content', array( $this, 'ajax_load_content_course' ) );
 	}

	public function ajax_load_content_course() {
		ob_start();
		if ( ! class_exists( 'LearnPress' ) ) {
			return;
		}
		$params = htmlspecialchars_decode( $_POST['params'] );
		$params = json_decode( str_replace( '\\', '', $params ), true );
		$cat_id = $_POST['category'];

		if ( ! class_exists( 'Elementor\Thim_Ekit_Widget_List_Course' ) ) {
			include THIM_EKIT_PLUGIN_PATH . 'inc/elementor/widgets/global/list-course.php';
		}
		$list_course = new \Elementor\Thim_Ekit_Widget_List_Course();
		$settings    = $this->get_widget_settings( intval( $params['page_id'] ), sanitize_text_field( $params['widget_id'] ) );
		$list_course->render_data_content_tab( $settings, $cat_id );
		$html = ob_get_contents();

		ob_end_clean();

		wp_send_json_success( $html );

		wp_die();
	}
 	public static function get_widget_settings( $page_id, $widget_id ) {
		$document = Plugin::$instance->documents->get( $page_id );
		$settings = array();
		if ( $document ) {
			$elements    = Plugin::instance()->documents->get( $page_id )->get_elements_data();
			$widget_data = self::element_recursive( $elements, $widget_id );
			if ( ! empty( $widget_data ) && is_array( $widget_data ) ) {
				$widget = Plugin::instance()->elements_manager->create_element_instance( $widget_data );
			}
			if ( ! empty( $widget ) ) {
				$settings = $widget->get_settings_for_display();
			}
		}

		return $settings;
	}

	/**
	 * Get Widget data.
	 *
	 * @param array  $elements Element array.
	 * @param string $form_id  Element ID.
	 *
	 * @return bool|array
	 */
	public static function element_recursive( $elements, $form_id ) {

		foreach ( $elements as $element ) {
			if ( $form_id === $element['id'] ) {
				return $element;
			}

			if ( ! empty( $element['elements'] ) ) {
				$element = self::element_recursive( $element['elements'], $form_id );

				if ( $element ) {
					return $element;
				}
			}
		}

		return false;
	}
}
Hooks::instance();
