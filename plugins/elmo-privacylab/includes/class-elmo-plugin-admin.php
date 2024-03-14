<?php
/**
 * Elmo plugin admin class
 *
 * @package Elmo
 */

if ( ! class_exists( 'Elmo_Plugin_Admin' ) ) {
	/**
	 * Elmo plugin admin class
	 */
	final class Elmo_Plugin_Admin {
		/**
		 * The existing instance of this class.
		 *
		 * @var Elmo_Plugin_Admin
		 */
		private static $instance;

		/**
		 * Instance of Elmo_Plugin class
		 *
		 * @var Elmo_Plugin
		 */
		private $elmo_plugin;

		/**
		 * Constructor. Requires an instance of Elmo_Plugin class.
		 *
		 * @param Elmo_Plugin $elmo_plugin Dependency injection of the Elmo_Plugin class.
		 */
		public function __construct( $elmo_plugin ) {
			$this->elmo_plugin = $elmo_plugin;
		}

		/**
		 * Get the existing instance of this class. If not exists instantiate a new one.
		 * Requires an instance of Elmo_Plugin class.
		 *
		 * @param Elmo_Plugin $elmo_plugin Dependency injection of the Elmo_Plugin class.
		 *
		 * @return Elmo_Plugin_Admin
		 */
		public static function get_instance( $elmo_plugin ) {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self( $elmo_plugin );
			}

			return self::$instance;
		}

		/**
		 * The init function. Call it to start the plugin's backend part.
		 *
		 * @return void
		 */
		public function init() {
			if ( is_admin() ) {
				add_action( 'init', array( $this, 'on_init' ) );

				add_action( 'admin_init', array( $this, 'on_admin_init' ) );
				add_action( 'admin_menu', array( $this, 'on_admin_menu' ) );
			}
		}

		/**
		 * `init` callback.
		 *
		 * @return void
		 */
		public function on_init() {
			load_plugin_textdomain( 'elmo-privacylab', false, dirname( plugin_basename( __FILE__ ) ) . '/../languages' );
		}

		/**
		 * `admin_init` callback.
		 *
		 * @return void
		 */
		public function on_admin_init() {
			// Set up settings.
			register_setting( 'elmo_settings_page', 'elmo_code' );
			register_setting( 'elmo_settings_page', 'elmo_code_v2' );
			add_option( 'elmo_code' );
			add_option( 'elmo_code_v2' );
			add_option( 'elmo_language' );
			add_settings_section( 'elmo_settings_section', esc_html__( 'General settings', 'elmo-privacylab' ), null, 'elmo_settings_page' );
			add_settings_field( 'elmo_code_field', esc_html__( 'Elmo legacy code (disused)', 'elmo-privacylab' ), array( $this, 'elmo_code_field_callback' ), 'elmo_settings_page', 'elmo_settings_section' );
			add_settings_field( 'elmo_code_v2_field', esc_html__( 'Elmo code', 'elmo-privacylab' ), array( $this, 'elmo_code_v2_field_callback' ), 'elmo_settings_page', 'elmo_settings_section' );
			add_settings_field( 'elmo_language_field', esc_html__( 'Elmo banner language', 'elmo-privacylab' ), array( $this, 'elmo_language_field_callback' ), 'elmo_settings_page', 'elmo_settings_section' );
		}

		/**
		 * `elmo_code_field` callback.
		 *
		 * @return void
		 */
		public function elmo_code_field_callback() {
			$elmo_code = get_option( 'elmo_code' );
			?>
			<input type="text" name="elmo_code" value="<?php echo isset( $elmo_code ) ? esc_attr( $elmo_code ) : ''; ?>">
			<?php
			if ( ! empty( $elmo_code ) ) {
				echo '<span>' . esc_html__( 'Code activated', 'elmo-privacylab' ) . '</span>';
			}
			?>
			<?php
		}

		/**
		 * `elmo_code_v2_field` callback.
		 *
		 * @return void
		 */
		public function elmo_code_v2_field_callback() {
			$elmo_code_v2 = get_option( 'elmo_code_v2' );
			?>
			<input type="text" name="elmo_code_v2" value="<?php echo isset( $elmo_code_v2 ) ? esc_attr( $elmo_code_v2 ) : ''; ?>">
			<?php
			if ( ! empty( $elmo_code_v2 ) ) {
				echo '<span>' . esc_html__( 'Code activated', 'elmo-privacylab' ) . '</span>';
			}
			?>
			<p class="description">
				<a href="<?php echo esc_attr( $this->elmo_plugin->elmo_site ); ?>/elmo.phtml?step=domains" target="_blank">
					<?php esc_html_e( 'Get your code', 'elmo-privacylab' ); ?>
				</a>
			</p>
			<?php
		}

		/**
		 * `elmo_language_field` callback.
		 *
		 * @return void
		 */
		public function elmo_language_field_callback() {
			?>
			<select name="elmo_language">
				<option value="default" <?php selected( get_option( 'elmo_language' ), 'default' ); ?>><?php esc_html_e( 'Use WordPress language', 'elmo-privacylab' ); ?></option>
				<option value="it" <?php selected( get_option( 'elmo_language' ), 'it' ); ?>>Italiano</option>
				<option value="en" <?php selected( get_option( 'elmo_language' ), 'en' ); ?>>English</option>
				<option value="fr" <?php selected( get_option( 'elmo_language' ), 'fr' ); ?>>Français</option>
				<option value="de" <?php selected( get_option( 'elmo_language' ), 'de' ); ?>>Deutsch</option>
				<option value="ro" <?php selected( get_option( 'elmo_language' ), 'ro' ); ?>>Română</option>
				<option value="es" <?php selected( get_option( 'elmo_language' ), 'es' ); ?>>Español</option>
			</select>
			<?php
		}

		/**
		 * `admin_menu` callback.
		 *
		 * @return void
		 */
		public function on_admin_menu() {
			// admin/images/elmo-transparent.svg base64 content.
			$icon      = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAwIiBoZWlnaHQ9IjgwMCIgdmVyc2lvbj0iMS4xIiB2aWV3Qm94PSIwIDAgODAwIDgwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4NCiA8cGF0aCBkPSJtNDAwIDBjLTIyMC45MSAwLTQwMCAxNzkuMDktNDAwIDQwMCAwIDY1LjM2MSAxNS42OCAxMjcuMDYgNDMuNDc5IDE4MS41NCA4LjkzOTMtMTIuOTE1IDIxLjM1NS0yMy4wODIgMzUuMTA5LTMwLjQ1MSAxOS44OC0xMC42NTIgNDMuMTI4LTE1LjgwNiA2NC40MTQtMTQuOTkyIDE0LjAyOCAwLjUzNiAyMS44MDcgOS42NzA4IDI3LjE3IDE2Ljg0IDAuODA3OTkgMS4wOCAxLjYwOTggMi4yMjU2IDIuNDI1OCAzLjM5MjZsMC4wMDk3NiAwLjAxNTYzYzMuMzIyIDQuNzUgNi44ODQ3IDkuODQ0NSAxMi4wOTggMTIuMjMgNi43NTUgMy4wOTIgNy4yNDMzIDkuOTk3IDcuMDcwMyAxMi45NTctMC4xNDEgMi40MTYtMC41NTk3IDQuNTQ1OS0wLjk3MDcgNi42Mjg5LTAuNzYzIDMuODczLTEuNDkzOCA3LjU4MzktMC4zNDM3NSAxMi42NyAxLjIyOCA1LjQyNyAxMy4wNjMgMTQuMTQ5IDMyLjM5MyAyMS4wOTIgMS45MDUgMC42ODQgMy43ODA5IDEuMTM0NCA1LjU4NzkgMS41Njg0bDAuMDExNzEgMC4wMDM5MWM0LjAzNyAwLjk2ODgxIDcuNzI1NyAxLjg2NCAxMC42MDkgNS4wNzQyIDIuNjA3NS00LjgwNTggNS4wNC05Ljc4OTMgNy41MDItMTQuODY5IDcuNDY2LTE1LjQwNCAxNS4zMTEtMzEuNTkyIDI5LjkwMi00NS42NzJsMC4yNTc4MS0wLjgzMjAzYy0xNC43MDUgNS41NTktMjYuOTc3IDIuOTc4OC0zNi43MjUtMi4xOTkyIDI2LjQ4Mi0xMi42MTEgMzAuNzAyLTIzLjQzMiAzNi40NzEtMzguMjMgMy4zOC04LjY3MSA3LjI5MjMtMTguNzA4IDE2LjUyOS0zMS4yN2wwLjAwOTc3IDAuMDA1ODYgMC4xNDY0OC0xLjAxMzdjLTM1LjQ1LTcuMTA1LTczLjkyOS0zOS43NzgtNTkuNjctNTQuMjUyIDE2LjE2NyA3Ljg5MSAzNC40MjkgMTMuNTAxIDUzLjk0MSAxNy4wODQgMC4xNjctMC4yNDYgMC4zMzQ4Ni0wLjQ5NTA1IDAuNTA1ODYtMC43NDgwNWwwLjAwMTk2LTAuMDAxOTVjNi4xNjEtOS4xMiAxNS4wOTMtMjIuMzQxIDI0LjU2MS0yMC40NDEgOS41ODQgMS45MjQgMTMuNTc1IDE2LjE1NSAxNi4wNyAyNS43NiA5LjQ5MSAwLjQzNCAxOS4xMjEgMC40ODE5NyAyOC44MTEgMC4xNjc5NyAwLjAyOS0wLjM3NSAwLjExNDcyLTAuNzUyMTQgMC4yNjE3Mi0xLjExOTEgMC4yODQtMC43MDcgMC41NzY4Ni0xLjQ0ODggMC44ODA4Ni0yLjIxNDggNC4wNTEtMTAuMjMyIDkuOTI3LTI1LjA3IDE5LjU4Mi0yNS4yNTQgMTAuMTE1LTAuMTkzIDE3LjIxOCAxMy43OCAyMS43MDUgMjIuNjA1djAuMDAxOTZjMC4xMzcgMC4yNjggMC4yNzAzNCAwLjUzMTA2IDAuNDAyMzQgMC43ODkwNiAwLjI0MyAwLjQ3NiAwLjM3ODkzIDAuOTgwMzggMC40MTk5MyAxLjQ4NDQgOC4zNi0xLjE1MSAxNi42NzUtMi41NDUgMjQuODkzLTQuMTY2LTAuMjI0LTAuNzIzLTAuMjY1MjYtMS41MTI4LTAuMDcyMjYtMi4zMDA4IDAuMjI5LTAuOTM0IDAuNDYzMDMtMS45MTg0IDAuNzA3MDMtMi45Mzk0bDAuMDA1ODYtMC4wMTk1NGMyLjk4Ni0xMi41MjIgNy4zMTctMzAuNjc5IDE4LjU4Mi0zMi40MzQgMTEuODAxLTEuODM4IDIyLjUwNyAxMy4yNDcgMjkuMjY4IDIyLjc3M2wwLjE1ODIgMC4yMjI2NWMwLjI0NiAwLjM0NyAwLjQ4NzY2IDAuNjg3NTggMC43MjI2NiAxLjAxNzYgMC4yNjYgMC4zNzMgMC40NjA4IDAuNzczNDYgMC41OTE4IDEuMTg5NSA3LjI0My0xLjggMTEuMTI1LTMuNTM3NyAyMS4wMzEtNy45NzA3IDAuODg2MDQtMC4zOTcgMS44MTk2LTAuODE0ODYgMi44MDg2LTEuMjU1OSAwLjAyLTAuMzI4IDAuMDQxNS0wLjY2MTk1IDAuMDYyNS0xLjAwMnYtMC4wMDE5NWMwLjU0OS04Ljg3NiAxLjM0NDgtMjEuNzQ2IDguNzE2OC0yNC4zMDcgNy4yNTYtMi41MiAxNS42MDUgNS4yMzcgMjEuMjc5IDEwLjg3NSAyMi43NzMtMTIuNTYzIDQwLjk1NC0yNy4yMjYgNTEuOTYxLTQzLjIxNyA1LjE1Ni04LjMxOCAyLjVlLTQgLTE2LjMxOC04Ljg0MzgtMTAuODE4LTc1LjQ2OCA1Mi42NTMtMjI3LjQxIDY3LjctMjk0LjExIDUyLjY5NWwtMC4wNTI3My0wLjAxMzY3aC0wLjAwMTk2bC0wLjAyNTM5LTAuMDA1ODZoLTAuMDAzOWMtMTUuNzA2LTMuNzAyMS0zNi40NTEtOS45NzYtNTkuMDg0LTE4LjgxOC0xOS44MzItOC4yNTUtMzcuNDM4LTE3LjY2OS01Mi4zODUtMjcuMjExLTE5LjA4NC0xMy43ODMtMzIuMzc5LTMwLjUxOC0zMi4zNzktNTAuMzQgMC0xMi44NTkgNC4zMDYyLTI2LjUzOCAxMi4yMDEtNDAuMzI2LTE0LjYyNC01LjI4MS0yMy40NzEtMTUuMDI4LTI5LjAwOC0yNS40NjUgMzQuMjY5IDUuMTg5IDUzLjA2LTEwLjQ1OSA3My40NTMtMjcuNDQzIDEyLjgwNS0xMC42NjMgMjYuMjQxLTIxLjg1MiA0NC41MzktMjguNzQyIDUuMjYtNDMuMDE1IDQxLjkxNS03Ni4zMyA4Ni4zNTItNzYuMzMgMTkuNTQ1IDAgMzcuNTg0IDYuNDQ1MiA1Mi4xMDkgMTcuMzI2IDE3LjYxMy0xNi45MjMgNDEuNTM3LTI3LjMyNiA2Ny44OTEtMjcuMzI2IDE5LjkyMSAwIDM4LjQ1NCA1Ljk0MzMgNTMuOTIyIDE2LjE1NCA2LjcxOCAzLjc0NCAxMy4wNTcgNy43ODggMTkuMjc1IDExLjc1MiAyNC43NDUgMTUuNzc4IDQ3LjU0NiAzMC4zMTMgODQuNDEgMTkuODI4LTUuNDgzIDE0LjUyOC0xNi4yMzQgMjguNjQyLTM3LjY4IDM1Ljk2MyAyNi4xMzYgMTYuOTE4IDUwLjg5MyAzOS4zMDEgNzMuMzI0IDY4LjA4IDIuMzk4IDMuMDc3IDQuNzMyOCA2LjE4NjIgNy4wMDc4IDkuMzI0MiAxNS4yMDIgMTUuMjUxIDIzLjYwOSAzMi41OTUgMzEuNjIxIDQ5LjEyNSAxMi43OTkgMjYuNDA5IDI0LjU5IDUwLjc0MSA2MS40OCA2MS4xNDEtOS4yNzIgNy4xNjUtMjAuOTczIDEyLjU1OS0zNS42NTQgMTIuNjIxIDIyLjQ3OCA4My4zNjIgMjQuNDI0IDE3Mi45NyAxNS4xOTMgMjUwLjY5IDUzLjMxMS02Ny45NTkgODUuMS0xNTMuNjEgODUuMS0yNDYuNjggMC0yMjAuOTEtMTc5LjA5LTQwMC00MDAtNDAwem00MiAxMzZjLTQwLjg2OSAwLTc0IDMzLjEzMS03NCA3NHMzMy4xMzEgNzQgNzQgNzQgNzQtMzMuMTMxIDc0LTc0LTMzLjEzMS03NC03NC03NHptLTEyMCA5Yy0zNS4zNDYgMC02NCAyOC42NTQtNjQgNjRzMjguNjU0IDY0IDY0IDY0YzEzLjYxNCAwIDI2LjIzNS00LjI1MDEgMzYuNjA5LTExLjQ5Ni05LjI2My0xNC45NjYtMTQuNjA5LTMyLjYxMS0xNC42MDktNTEuNTA0IDAtMTkuNDc3IDUuNjgxNS0zNy42MjkgMTUuNDc5LTUyLjg4NS0xMC41MzQtNy42MjMtMjMuNDgyLTEyLjExNS0zNy40NzktMTIuMTE1em0tMyA0MmM4LjgzNyAwIDE2IDcuMTYzIDE2IDE2IDAgOC44MzYtNy4xNjMgMTYtMTYgMTZzLTE2LTcuMTY0LTE2LTE2YzAtOC44MzcgNy4xNjMtMTYgMTYtMTZ6bTg5IDBjOC44MzcgMCAxNiA3LjE2MyAxNiAxNiAwIDguODM2LTcuMTYzIDE2LTE2IDE2cy0xNi03LjE2NC0xNi0xNmMwLTguODM3IDcuMTYzLTE2IDE2LTE2em0tMjkzLjM2IDM4MC41OGMtNy41NzI4LTAuMTA4My0xMi4wNzkgNy44NDUtOS45NTkgMTYuNjE5IDEuNjk2IDcuMDE5IDkuMzkwOCAxNS4zNzEgMTYuNDY5IDguMzc4OSA1Ljc5OC01LjcyOSA3Ljc3MzctMTkuNDMtMC44MjIyNi0yMy41NDctMi4wMzA2LTAuOTcyNTYtMy45Mzk5LTEuNDI2Mi01LjY4NzUtMS40NTEyem0tMzQuNjMzIDM4LjMyNGMtMi4wNTk3IDAuMDY1MzEtNC4wODcyIDAuOTEyMDktNS43NzE1IDIuODI4MS00LjQ5MjEgNS4xMS0xLjEyNzcgMTAuNDA5IDMuMDIzNCAxNC4wNTUgMy41NTQgMy4xMjEgOC45NjY4IDIuNzcxOCAxMi4wOS0wLjc4MTI1IDUuODAxNC02LjU5OTItMS45ODU3LTE2LjMzNS05LjM0MTgtMTYuMTAyem01NS4xNzYgMTQuMTc2Yy03LjA0OS0wLjMzNi0xNC4yODIgNi45MzY5LTEwLjE1OCAxNC4wMDQgNi40NiAxMS4wNjkgMjUuOTExLTAuNDQwNDkgMTYuNzU2LTEwLjg5Ni0yLjM4OS0yLjczLTUuNDc2Ni0zLjA1NDQtNi41OTc2LTMuMTA3NHptMi4yMjI2IDMwLjgzOGMtMS43MjY5IDAuMDI1NDQtMy40NjExIDAuNDAzOTEtNS4wMzUyIDEuMTI4OS01Ljg3NCAyLjcwNi03LjU2NDIgMTAuNjYzLTQuOTkyMiAxNi4xOTFsMC4wNDQ5MiAwLjA5NTdjMy4wNDUgNi41NjkgOS41OTk0IDkuNTc2NyAxNi40MDIgNi40NzA3IDUuOTIzLTIuNzA1IDYuMjEwMi0xMy41NTYgMy4xMTcyLTE4Ljg5My0xLjk3MTgtMy40MDE4LTUuNzM4LTUuMDUwMS05LjUzNzEtNC45OTQxem01NS4yNTggMTMuOTk4Yy0yLjU2MDEtMC4wMTU3Ny01LjA2NjggMC41Mzk0NS02Ljk3NDYgMS42NTA0LTQuNDE1IDIuNTcxLTkuMzQ2MyA3Ljg4OTUtOS44MjIzIDEzLjI3MS0wLjYwOSA2Ljg4IDcuOTQyMyAxMi42OSAxNC40MDQgMTEuMTc2IDUuNzIwNy0xLjMzOTkgMTIuNDE5LTguMjM5NyAxMy45NjEtMTQuNDk2LTQuODgzOS0xLjk5MTItOS4yNDczLTQuNjQ2LTEzLjEzNS03LjY1MDQgMi44MDI2LTAuNzkwMDggNS40MzIyLTEuNjc2NyA3LjkyNzctMi42Mzg3LTEuOTMyNy0wLjg2MDQtNC4xNjUzLTEuMjk5LTYuMzYxMy0xLjMxMjV6bS01Mi41NTEgMzkuMTZjMS42NTI3IDEuNDEzOSAzLjMxNDQgMi44MTc4IDQuOTkwMiA0LjIwNTEtMS4yODUtMS45MDA3LTIuOTkzOS0zLjQyMjktNC45OTAyLTQuMjA1MXoiIGZpbGw9IiNmZmYiLz4NCiA8Zz4NCiAgPHBhdGggZD0ibTIxNS45NCA1NDMuMjNjMC41NDEtNi4wMDgtMi45MDgtMTEuNjY0LTguNDk3LTEzLjkzM2wtMTMuOTE0LTUuNjUxYy0yLjMyMy0wLjk0My00Ljg5MyAwLjYzOS01LjA5OCAzLjEzN2wtMC41ODEgNy4wNTljLTAuNzgxIDkuNTA4IDQuMzY1IDE4LjUxNyAxMi45NTEgMjIuNjczIDYuNDU4IDMuMTI3IDE0LjA0OS0xLjE3IDE0LjY5Mi04LjMxNnoiLz4NCiAgPHBhdGggZD0ibTI0NS44IDU4Ny41M2MwLjM3LTQuODA3LTEuNzE4LTkuNDc1LTUuNTQ4LTEyLjQwMy03LjkxMS02LjA0OS0xOS40MjgtMi4zMTQtMjIuMjg1IDcuMjI2bC00LjM5NCAxNC42NzZjLTIuMTY2IDcuMjMzIDMuNDQxIDE0LjQ0NCAxMC45ODUgMTQuMTI3bDAuNTEzLTAuMDIxYzEwLjgyNC0wLjQ1NSAxOS41OTktOC45MyAyMC40MzEtMTkuNzMxeiIvPg0KICA8cGF0aCBkPSJtMjc3LjMyIDYwNC4zN2MxLjg2OCAwLjgyNyAzLjE5MSAyLjU0MiAzLjUxNSA0LjU1OWwwLjI1NiAxLjU5M2MwLjcyNSA0LjUxMi0yLjA0NiA4Ljg1Mi02LjQ0NCAxMC4wOTNsLTAuMjQ4IDAuMDdjLTMuMDY5IDAuODY2LTYuMDc4LTEuNTQ3LTUuOTAxLTQuNzNsMC4zNTctNi40MDRjMC4yMzUtNC4yMTEgNC42MDgtNi44ODggOC40NjUtNS4xODF6Ii8+DQogPC9nPg0KPC9zdmc+DQo=';
			$hook_name = add_menu_page( esc_html__( 'Elmo settings', 'elmo-privacylab' ), 'Elmo', 'manage_options', 'elmo', array( $this, 'admin_menu_html' ), $icon );

			add_action( 'load-' . $hook_name, array( $this, 'admin_menu_submit' ) );
		}

		/**
		 * HTML rendering of the admin menu.
		 *
		 * @return void
		 */
		public function admin_menu_html() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			?>
			<div class="wrap">
				<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
				<p style="float: left; margin-right: 1em;">
					<a href="<?php echo esc_html( $this->elmo_plugin->elmo_site ); ?>/" target="_blank">
						<img src="<?php echo esc_url( plugins_url( '/../admin/images/elmo-logo.svg', __FILE__ ) ); ?>" width="100" alt="Elmo">
					</a>
				</p>
				<p>
					<?php
					// translators: the placeholder strings denote the positions of <b> and </b> HTML tags.
					echo sprintf( esc_html__( '%1$sElmo%2$s is PrivacyLab\'s tool for managing cookies.', 'elmo-privacylab' ), '<b>', '</b>' );
					?>
				</p>
				<p>
					<?php esc_html_e( 'It allows you to verify, catalog and properly manage the compliance of your site to the GDPR.', 'elmo-privacylab' ); ?>
				</p>
				<p>
					<?php
					// translators: the placeholder strings denote the positions of <b> and </b> HTML tags.
					echo sprintf( esc_html__( 'With %1$sElmo%2$s you keep under control all the consents expressed in the cookie banner and you can collect them in the "register of consents" required by law.', 'elmo-privacylab' ), '<b>', '</b>' );
					?>
					<br>
					<?php
					// translators: the placeholder strings denote the positions of <b> and </b> HTML tags.
					echo sprintf( esc_html__( '%1$sElmo%2$s\'s consent log is certified! More help to prove your compliance with GDPR.', 'elmo-privacylab' ), '<b>', '</b>' );
					?>
				</p>
				<p>
					<?php
					// translators: the placeholder strings denote the positions of <b> and </b> HTML tags.
					echo sprintf( esc_html__( 'With %1$sElmo%2$s you can automatically create guaranteed and certified treatments and information and you can also create your own cookie banner starting from the information of the site you already have.', 'elmo-privacylab' ), '<b>', '</b>' );
					?>
				</p>
				<p>
					<a href="<?php echo esc_attr( $this->elmo_plugin->elmo_site ); ?>/" target="_blank">
						<?php esc_html_e( 'Find out all the details', 'elmo-privacylab' ); ?>
					</a>
				</p>
				<form action="<?php menu_page_url( 'elmo' ); ?>" method="post">
					<?php
					// Output security fields.
					settings_fields( 'elmo_settings_page' );
					// Output setting sections and their fields.
					do_settings_sections( 'elmo_settings_page' );
					// Output save settings button.
					submit_button();
					?>
				</form>
			</div>
			<?php
		}

		/**
		 * Submit callback of the admin menu form.
		 *
		 * @return void
		 */
		public function admin_menu_submit() {
			$is_post        = isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'];
			$is_nonce_valid = isset( $_POST['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'elmo_settings_page-options' );
			if ( $is_post && $is_nonce_valid && isset( $_POST['elmo_code'] ) && isset( $_POST['elmo_code_v2']) && isset( $_POST['elmo_language'])) {
				update_option( 'elmo_code', sanitize_text_field( wp_unslash( $_POST['elmo_code'] ) ) );
				update_option( 'elmo_code_v2', sanitize_text_field( wp_unslash( $_POST['elmo_code_v2'] ) ) );
				update_option( 'elmo_language', sanitize_text_field( wp_unslash( $_POST['elmo_language'] ) ) );
			}
		}
	}
}
