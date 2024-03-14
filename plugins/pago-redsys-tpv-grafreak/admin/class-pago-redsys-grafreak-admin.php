<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pago_Redsys_Grafreak
 * @subpackage Pago_Redsys_Grafreak/admin
 * @author     Adrian Cobo <adrian@grafreak.net>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Pago_Redsys_Grafreak_Admin Class Doc Comment
 */
class Pago_Redsys_Grafreak_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The options name to be used in this plugin
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var     string      $option_name    Option name of this plugin
	 */
	private $option_name = 'pago_redsys_grafreak';

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pago-redsys-grafreak-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pago-redsys-grafreak-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'TPV Configuration', 'pago-redsys-grafreak' ),
			__( 'TPV Configuration', 'pago-redsys-grafreak' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);
	}
	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {
		include_once 'partials/pago-redsys-grafreak-admin-display.php';
	}
	/**
	 * Register all related settings of this plugin
	 *
	 * @since  1.0.0
	 */
	public function register_setting() {
		add_settings_section(
			$this->option_name . '_general',
			__( 'General', 'pago-redsys-grafreak' ),
			array( $this, $this->option_name . '_general_cb' ),
			$this->plugin_name
		);
		add_settings_field(
			$this->option_name . '_habilitado',
			__( 'Enabled', 'pago-redsys-grafreak' ),
			array( $this, $this->option_name . '_habilitado_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_habilitado' )
		);
		add_settings_field(
			$this->option_name . '_titulo',
			__( 'Title', 'pago-redsys-grafreak' ),
			array( $this, $this->option_name . '_titulo_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_titulo' )
		);
		add_settings_field(
			$this->option_name . '_urltest',
			__( 'Url Test Environment', 'pago-redsys-grafreak' ),
			array( $this, $this->option_name . '_urltest_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_urltest' )
		);
		add_settings_field(
			$this->option_name . '_urlreal',
			__( 'URL Real Environment', 'pago-redsys-grafreak' ),
			array( $this, $this->option_name . '_urlreal_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_urlreal' )
		);
		add_settings_field(
			$this->option_name . '_entornoact',
			__( 'Active environment', 'pago-redsys-grafreak' ),
			array( $this, $this->option_name . '_entornoact_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_entornoact' )
		);
		add_settings_field(
			$this->option_name . '_nombrecomercio',
			__( 'Trade name', 'pago-redsys-grafreak' ),
			array( $this, $this->option_name . '_nombrecomercio_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_nombrecomercio' )
		);
		add_settings_field(
			$this->option_name . '_idfuc',
			__( 'Merchant identifier (FUC)', 'pago-redsys-grafreak' ),
			array( $this, $this->option_name . '_idfuc_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_idfuc' )
		);
		add_settings_field(
			$this->option_name . '_idioma',
			__( 'Language', 'pago-redsys-grafreak' ),
			array( $this, $this->option_name . '_idioma_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_idioma' )
		);
		add_settings_field(
			$this->option_name . '_terminal',
			__( 'Terminal', 'pago-redsys-grafreak' ),
			array( $this, $this->option_name . '_terminal_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_terminal' )
		);
		add_settings_field(
			$this->option_name . '_encriptkey',
			__( 'Encryption key', 'pago-redsys-grafreak' ),
			array( $this, $this->option_name . '_encriptkey_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_encriptkey' )
		);
		add_settings_field(
			$this->option_name . '_moneda',
			__( 'Currency', 'pago-redsys-grafreak' ),
			array( $this, $this->option_name . '_moneda_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_moneda' )
		);
		add_settings_field(
			$this->option_name . '_urlok',
			__( 'Global URL OK', 'pago-redsys-grafreak' ),
			array( $this, $this->option_name . '_urlok_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_urlok' )
		);
		add_settings_field(
			$this->option_name . '_urlko',
			__( 'Global URL KO', 'pago-redsys-grafreak' ),
			array( $this, $this->option_name . '_urlko_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_urlko' )
		);
		register_setting( $this->plugin_name, $this->option_name . '_habilitado', array( $this, $this->option_name . '_sanitize_habilitado' ) );
		register_setting( $this->plugin_name, $this->option_name . '_entornoact', array( $this, $this->option_name . '_sanitize_entornoact' ) );
		register_setting( $this->plugin_name, $this->option_name . '_titulo', 'sanitize_text_field' );
		register_setting( $this->plugin_name, $this->option_name . '_urltest', 'esc_url' );
		register_setting( $this->plugin_name, $this->option_name . '_urlreal', 'esc_url' );
		register_setting( $this->plugin_name, $this->option_name . '_nombrecomercio', 'sanitize_text_field' );
		register_setting( $this->plugin_name, $this->option_name . '_idfuc', 'intval' );
		register_setting( $this->plugin_name, $this->option_name . '_idioma', 'intval' );
		register_setting( $this->plugin_name, $this->option_name . '_terminal', 'intval' );
		register_setting( $this->plugin_name, $this->option_name . '_encriptkey' );
		register_setting( $this->plugin_name, $this->option_name . '_moneda', 'intval' );
		register_setting(
			$this->plugin_name,
			$this->option_name . '_urlok',
			array(
				'description'       => esc_html__( 'The URL that all the form with succesful payment will redirect. You can override this on every form', 'pago-redsys-grafreak' ),
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		register_setting(
			$this->plugin_name,
			$this->option_name . '_urlko',
			array(
				'description'       => esc_html__( 'The URL that all the form with error at payment will redirect. You can override this on every form', 'pago-redsys-grafreak' ),
				'sanitize_callback' => 'esc_url_raw',
			)
		);
	}
	/**
	 * Render the text for the general section
	 *
	 * @since  1.0.0
	 */
	public function pago_redsys_grafreak_general_cb() {
		echo '<p>' . esc_html__( 'Change the data for those that have been provided to you.', 'pago-redsys-grafreak' ) . '</p>';
	}
	/**
	 * Render the radio input field for habilitado option
	 *
	 * @since  1.0.0
	 */
	public function pago_redsys_grafreak_habilitado_cb() {
		$entorno = get_option( $this->option_name . '_habilitado' );
		?>
				<fieldset>
					<label>
						<input type="radio" name="<?php echo esc_html( $this->option_name ) . '_habilitado'; ?>" id="<?php echo esc_html( $this->option_name ) . '_habilitado'; ?>" value="enabled" <?php checked( $entorno, 'enabled' ); ?>>
					<?php esc_html_e( 'Enabled', 'pago-redsys-grafreak' ); ?>
					</label>
					<label style="margin-left: 30px !important; display:inline-block;">
						<input type="radio" name="<?php echo esc_html( $this->option_name ) . '_habilitado'; ?>" value="disabled" <?php checked( $entorno, 'disabled' ); ?>>
					<?php esc_html_e( 'Disabled', 'pago-redsys-grafreak' ); ?>
					</label>
				</fieldset>
			<?php
	}
	/**
	 * Render the radio input field for entorno activo option
	 *
	 * @since  1.0.0
	 */
	public function pago_redsys_grafreak_entornoact_cb() {
		$entorno = get_option( $this->option_name . '_entornoact' );
		?>
				<fieldset>
					<label>
						<input type="radio" name="<?php echo esc_html( $this->option_name ) . '_entornoact'; ?>" id="<?php echo esc_html( $this->option_name ) . '_position'; ?>" value="test" <?php checked( $entorno, 'test' ); ?>>
					<?php esc_html_e( 'Test environment', 'pago-redsys-grafreak' ); ?>
					</label>
					<label style="margin-left: 30px !important; display:inline-block;">
						<input type="radio" name="<?php echo esc_html( $this->option_name ) . '_entornoact'; ?>" value="real" <?php checked( $entorno, 'real' ); ?>>
					<?php esc_html_e( 'Real environment', 'pago-redsys-grafreak' ); ?>
					</label>
				</fieldset>
			<?php
	}
	/**
	 * Input para el titulo
	 *
	 * @since  1.0.0
	 */
	public function pago_redsys_grafreak_titulo_cb() {
		$titulo = get_option( $this->option_name . '_titulo' );
		echo '<input type="text" name="' . esc_html( $this->option_name ) . '_titulo" id="' . esc_html( $this->option_name ) . '_titulo" value="' . esc_html( $titulo ) . '">';
	}
	/**
	 * Input para la url de test
	 *
	 * @since  1.0.0
	 */
	public function pago_redsys_grafreak_urltest_cb() {
		$titulo = get_option( $this->option_name . '_urltest' );
		echo '<input type="text" name="' . esc_html( $this->option_name ) . '_urltest" id="' . esc_html( $this->option_name ) . '_urltest" value="' . esc_html( $titulo ) . '">';
	}
	/**
	 * Input para la url real
	 *
	 * @since  1.0.0
	 */
	public function pago_redsys_grafreak_urlreal_cb() {
		$titulo = get_option( $this->option_name . '_urlreal' );
		echo '<input type="text" name="' . esc_html( $this->option_name ) . '_urlreal" id="' . esc_html( $this->option_name ) . '_urlreal" value="' . esc_html( $titulo ) . '">';
	}
	/**
	 * Input para el nombre del comercio
	 *
	 * @since  1.0.0
	 */
	public function pago_redsys_grafreak_nombrecomercio_cb() {
		$titulo = get_option( $this->option_name . '_nombrecomercio' );
		echo '<input type="text" name="' . esc_html( $this->option_name ) . '_nombrecomercio" id="' . esc_html( $this->option_name ) . '_nombrecomercio" value="' . esc_html( $titulo ) . '">';
	}
	/**
	 * Input para el FUC
	 *
	 * @since  1.0.0
	 */
	public function pago_redsys_grafreak_idfuc_cb() {
		$titulo = get_option( $this->option_name . '_idfuc' );
		echo '<input type="text" name="' . esc_html( $this->option_name ) . '_idfuc" id="' . esc_html( $this->option_name ) . '_idfuc" value="' . esc_html( $titulo ) . '">';
	}
	/**
	 * Input para un terminal
	 *
	 * @since  1.0.0
	 */
	public function pago_redsys_grafreak_terminal_cb() {
		$titulo = get_option( $this->option_name . '_terminal' );
		echo '<input type="text" name="' . esc_html( $this->option_name ) . '_terminal" id="' . esc_html( $this->option_name ) . '_terminal" value="' . esc_html( $titulo ) . '">';
	}
	/**
	 * Input para un encript key
	 *
	 * @since  1.0.0
	 */
	public function pago_redsys_grafreak_encriptkey_cb() {
		$titulo = get_option( $this->option_name . '_encriptkey' );
		echo '<input type="text" name="' . esc_html( $this->option_name ) . '_encriptkey" id="' . esc_html( $this->option_name ) . '_encriptkey" value="' . esc_html( $titulo ) . '">';
	}
	/**
	 * Select para una moneda
	 *
	 * @since  1.0.0
	 */
	public function pago_redsys_grafreak_moneda_cb() {
		$moneda = get_option( $this->option_name . '_moneda' );
		echo '<select name="' . esc_html( $this->option_name ) . '_moneda" id="' . esc_html( $this->option_name ) . '_moneda">' .
		'<option value="978" ' . selected( $moneda, '978', false ) . '>Euro</option>' .
		'<option value="840" ' . selected( $moneda, '840', false ) . '>Dólar</option>' .
		'<option value="826" ' . selected( $moneda, '826', false ) . '>Libra</option>' .
		'<option value="392" ' . selected( $moneda, '392', false ) . '>Yen</option>' .
		'<option value="032" ' . selected( $moneda, '032', false ) . '>Peso Argentino</option>' .
		'<option value="124" ' . selected( $moneda, '124', false ) . '>Dólar Canadiense</option>' .
		'<option value="152" ' . selected( $moneda, '152', false ) . '>Peso Chileno</option>' .
		'<option value="170" ' . selected( $moneda, '170', false ) . '>Peso Colombiano</option>' .
		'<option value="356" ' . selected( $moneda, '356', false ) . '>Rupia India</option>' .
		'<option value="484" ' . selected( $moneda, '484', false ) . '>Nuevo Peso Mexicano</option>' .
		'<option value="604" ' . selected( $moneda, '604', false ) . '>Nuevos Soles</option>' .
		'<option value="756" ' . selected( $moneda, '756', false ) . '>Franco Suizo</option>' .
		'<option value="986" ' . selected( $moneda, '986', false ) . '>Real Brasileño</option>' .
		'<option value="937" ' . selected( $moneda, '937', false ) . '>Bolívar Venezolano</option>' .
		'<option value="949" ' . selected( $moneda, '949', false ) . '>Lira Turca</option>' .
		'<option value="156" ' . selected( $moneda, '156', false ) . '>Yuan Chino Extracontinental</option>' .
		'<option value="578" ' . selected( $moneda, '578', false ) . '>Corona Noruega</option>' .
		'<option value="752" ' . selected( $moneda, '752', false ) . '>Corona Suiza</option>' .
		'<option value="710" ' . selected( $moneda, '710', false ) . '>Rand Sudafricano</option>' .
		'<option value="208" ' . selected( $moneda, '208', false ) . '>Corona Danesa</option>' .
		'<option value="36" ' . selected( $moneda, '36', false ) . '>Dólar Australiano</option></select>';
	}

	/**
	 * Select para una moneda
	 *
	 * @since  1.0.0
	 */
	public function pago_redsys_grafreak_idioma_cb() {
		$idioma = get_option( $this->option_name . '_idioma' );
		echo '<select name="' . esc_html( $this->option_name ) . '_idioma" id="' . esc_html( $this->option_name ) . '_idioma">' .
			'<option value="">--Auto--</option>' .
			'<option value="1" ' . selected( $idioma, '1', false ) . '>Castellano</option>' .
			'<option value="2" ' . selected( $idioma, '2', false ) . '>Inglés</option>' .
			'<option value="3" ' . selected( $idioma, '3', false ) . '>Catalán</option>' .
			'<option value="4" ' . selected( $idioma, '4', false ) . '>Francés</option>' .
			'<option value="5" ' . selected( $idioma, '5', false ) . '>Aleman</option>' .
			'<option value="6" ' . selected( $idioma, '6', false ) . '>Holandes</option>' .
			'<option value="7" ' . selected( $idioma, '7', false ) . '>Italiano</option>' .
			'<option value="8" ' . selected( $idioma, '8', false ) . '>Sueco</option>' .
			'<option value="9" ' . selected( $idioma, '9', false ) . '>Portugués</option>' .
			'<option value="10" ' . selected( $idioma, '10', false ) . '>Valenciano</option>' .
			'<option value="11" ' . selected( $idioma, '11', false ) . '>Polaco</option>' .
			'<option value="12" ' . selected( $idioma, '12', false ) . '>Gallego</option>' .
			'<option value="13" ' . selected( $idioma, '13', false ) . '>Euskera</option>' .
		'</select>';
	}
	/**
	 * Input para el nombre del comercio
	 *
	 * @since  1.0.4
	 */
	public function pago_redsys_grafreak_urlok_cb() {
		$titulo = get_option( $this->option_name . '_urlok' );
		echo '<input type="text" name="' . esc_html( $this->option_name ) . '_urlok" id="' . esc_html( $this->option_name ) . '_urlok" value="' . esc_html( $titulo ) . '">';
		echo '<span>' . esc_html__( 'The URL that all the form with succesful payment will redirect. You can override this on every form', 'pago-redsys-grafreak' ) . '</span>';
	}
	/**
	 * Input para el nombre del comercio
	 *
	 * @since  1.0.4
	 */
	public function pago_redsys_grafreak_urlko_cb() {
		$titulo = get_option( $this->option_name . '_urlko' );
		echo '<input type="text" name="' . esc_html( $this->option_name ) . '_urlko" id="' . esc_html( $this->option_name ) . '_urlko" value="' . esc_html( $titulo ) . '">';
		echo '<span>' . esc_html__( 'The URL that all the form with error at payment will redirect. You can override this on every form', 'pago-redsys-grafreak' ) . '</span>';	}
	/**
	 * Sanitize the text position value before being saved to database
	 *
	 * @param  string $position $_POST value.
	 * @since  1.0.0
	 * @return string           Sanitized value
	 */
	public function pago_redsys_grafreak_sanitize_entornoact( $position ) {
		if ( in_array( $position, array( 'test', 'real' ), true ) ) {
			return $position;
		}
	}
	/**
	 * Sanitize the text position value before being saved to database
	 *
	 * @param  string $position $_POST value.
	 * @since  1.0.0
	 * @return string           Sanitized value
	 */
	public function pago_redsys_grafreak_sanitize_habilitado( $position ) {
		if ( in_array( $position, array( 'enabled', 'disabled' ), true ) ) {
					return $position;
		}
	}

}
