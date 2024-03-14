<?php
/**
 * Control de horas Ui.
 *
 * @since   0.0.0
 * @package Control_Horas
 */

/**
 * Control de horas Ui.
 *
 * @since 0.0.0
 */
class CH_Ui {
	/**
	 * Parent plugin class.
	 *
	 * @since 0.0.0
	 *
	 * @var object
	 */
	protected $plugin = null;

	const WP_DATATABLE_VERSION = '1.10.19';
	/**
	 * Constructor.
	 *
	 * @since  0.0.0
	 *
	 * @param object $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->register();
		$this->hooks();
	}

	/**
	 * Register styles and scripts.
	 *
	 * @since  0.0.0
	 */
	public function register() {

		// Register admin styles.
		wp_register_style(
			'wp-datatable-style',
			$this->plugin->url . 'assets/css/jquery.dataTables.min.css?v=' . $this::WP_DATATABLE_VERSION,
			array(),
			$this->plugin->version
		);

		// Register admin styles.
		wp_register_script(
			'wp-datatable-script',
			$this->plugin->url . 'assets/js/jquery.dataTables.min.js?v=' . $this::WP_DATATABLE_VERSION,
			array(),
			$this->plugin->version
		);

	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  0.0.0
	 */
	public function hooks() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		// tabs control and content.
		add_action( 'controlhoras_settings_tab', array( $this, 'registros_nav_tab' ), 1 );
		add_action( 'controlhoras_settings_tab', array( $this, 'ajustes_nav_tab' ), 2 );
		add_action( 'controlhoras_settings_tab', array( $this, 'ayuda_nav_tab' ), 3 );

		add_action( 'controlhoras_settings_content', array( $this, 'registros_content' ) );
		add_action( 'controlhoras_settings_content', array( $this, 'ajustes_content' ) );
		add_action( 'controlhoras_settings_content', array( $this, 'ayuda_content' ) );
		add_action( 'controlhoras_settings_content', array( $this, 'editar_content' ) );

		// ajax scripts.
		add_action( 'wp_ajax_ch_load_data', array( $this, 'ajax_ch_load_data' ) );

		// enqueue admin styles and scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	/**
	 * Register scripts and Styles.
	 *
	 * @param string $hook is the hookname.
	 * @since  0.0.0
	 */
	public function enqueue_admin_assets( $hook ) {
		// Load only on ?page=mypluginname.
		if ( 'toplevel_page_controlhoras' != $hook ) {
			return;
		}
		wp_enqueue_style( 'wp-datatable-style' );
		wp_enqueue_script( 'wp-datatable-script' );
	}

	/**
	 * Get shifts data via Ajax.
	 *
	 * @since  0.0.0
	 */
	public function ajax_ch_load_data() {
		// check security.
		check_ajax_referer( 'ch_load_data', 'security' );

		$ch_db = $this->plugin->ch_db;
		$data  = $ch_db->all_shifts();

		echo json_encode( $data );

		wp_die();
	}

	/**
	 * Echo 'Control de horas' tab of plugin settings
	 *
	 * @since  0.0.0
	 */
	public function registros_nav_tab() {
		global $controlhoras_active_tab;
		$classname = ( empty( $controlhoras_active_tab ) || 'registros' == $controlhoras_active_tab || 'editar' == $controlhoras_active_tab ) ? 'nav-tab-active' : '';
		?>
		<a 	class="nav-tab <?php echo esc_attr( $classname ); ?>" 
			href="<?php echo esc_attr( admin_url( 'admin.php?page=controlhoras&tab=registros' ) ); ?>">
			<?php esc_html_e( 'Control de horas', 'control-horas' ); ?>
		</a>
		<?php
	}

	/**
	 * Echo 'Ayuda' tab of plugin settings
	 *
	 * @since  0.0.0
	 */
	public function ayuda_nav_tab() {
		global $controlhoras_active_tab;
		$classname = ( ! empty( $controlhoras_active_tab ) && 'ayuda' == $controlhoras_active_tab ) ? 'nav-tab-active' : '';
		?>
		<a 	class="nav-tab <?php echo esc_attr( $classname ); ?>" 
			href="<?php echo esc_attr( admin_url( 'admin.php?page=controlhoras&tab=ayuda' ) ); ?>">
			<?php esc_html_e( 'Ayuda', 'control-horas' ); ?>
		</a>
		<?php
	}

	/**
	 * Echo 'Ajustes' tab of plugin settings
	 *
	 * @since  1.0.1
	 */
	public function ajustes_nav_tab() {
		global $controlhoras_active_tab;
		$classname = ( ! empty( $controlhoras_active_tab ) && 'ajustes' == $controlhoras_active_tab ) ? 'nav-tab-active' : '';
		?>
		<a 	class="nav-tab <?php echo esc_attr( $classname ); ?>" 
			href="<?php echo esc_attr( admin_url( 'admin.php?page=controlhoras&tab=ajustes' ) ); ?>">
			<?php esc_html_e( 'Ajustes', 'control-horas' ); ?>
		</a>
		<?php
	}

	/**
	 * Echo 'Ayuda' content of plugin settings
	 *
	 * @since  0.0.0
	 */
	public function ayuda_content() {
		global $controlhoras_active_tab;
		if ( empty( $controlhoras_active_tab ) || 'ayuda' != $controlhoras_active_tab ) {
			return;
		}
		?>
		<div class="wrap" style="max-width: 540px">
			<h1>Ayuda</h1>
			
			<h2>Registro diario de la jornada laboral</h2>
			<p>
				El artículo 34.9 del Estatuto de los Trabajadores (modificado por el Real Decreto Ley 8/2019, de 8 de marzo, de medidas urgentes de protección social y de lucha contra la precariedad laboral en la jornada de trabajo) establece que empresas y autónomos con trabajadores garantizarán el registro diario de la jornada laboral de los trabajadores.
			</p>	
			<p>
				El registro recogerá la hora de inicio y de finalización de la jornada de laboral de cada trabajador. 
			</p>
			<p>
				La empresa deberá <b>conservar el registro durante cuatro años</b> que estará a disposición de los trabajadores, de sus representantes legales y de los inspectores de la Seguridad Social.
			</p>

			<h2>Registro electrónico de la jornada laboral</h2>
			<p>
				&laquo;Control de Horas&raquo; sirve para que los empleados puedan registrar su jornada laboral de forma telemática y así cumplir con la legislación española.
			</p>

			<h2>¿Cómo funciona?</h2>
			<h4>Los usuarios</h4>
			<p>
				Cada trabajador tendrá una cuenta de usuario, un usuario y una contraseña. Puedes dar de alta los usuarios desde <a href="users.php">aquí</a> o pedirles que se registren ellos mismos desde <a href="../wp-login.php">aquí</a>. En este caso, comprueba en el menú &laquo;Ajustes&raquo; dentro del apartado &laquo;Generales&raquo; que tienes marcada la casilla &laquo;Cualquiera puede registrarse&raquo;.
			</p>

			<h4>Empezar la jornada laboral</h4>
			<p>
				Para empezar la jornada laboral cada usuario entrará a su escritorio y clicará en el botón EMPEZAR que se encuentra en la barra de estado. La fecha y hora del registro queda almacenada en la base de datos de forma segura.
			</p>

			<h4>Finalizar la jornada laboral</h4>
			<p>
				El usuario que ha iniciado su jornada laboral puede finalizarla en cualquier momento al clicar en el botón FINALIZAR que está en la barra de estado. 				
			</p>			
		
			<h2>Ver los registros</h2>
			<p>
				Los usuarios con permisos administrativos puedes revisar los fichajes de todos los usuarios desde el menú "Control de horas". En la tabla de datos se mostrarán las columnas Nombre, hora de entrada, hora de salida y duración de la jornada.
			</p>

			<h2>Shortcodes</h2>
			<table class="wp-list-table widefat fixed striped posts">
				<thead>
					<tr>
						<td><?php esc_html_e( 'Shortcode', 'control-horas' ); ?></td>
						<td>&nbsp;</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>[ch_registro note='texto para la nota'/]</td>
						<td>Muestra el botón Empezar/Finalizar jornada</td>
					</tr>
				</tbody>
			</table>

		</div>
		<?php
	}

	/**
	 * Echo 'Control de horas' content of plugin settings
	 *
	 * @since  0.0.0
	 */
	public function registros_content() {
		global $controlhoras_active_tab;
		if ( empty( $controlhoras_active_tab ) || 'registros' != $controlhoras_active_tab ) {
			return;
		}
		?>
		<br/><h1>Registro de fichajes</h1><br/><br/>	
		<div>			
			<table id="ch-turnos" class="display" style="width:100%">
				<thead>
					<tr>
						<th class="dt-head-left"><?php esc_html_e( 'Nombre', 'control-horas' ); ?></th>
						<th class="dt-head-left"><?php esc_html_e( 'Entrada', 'control-horas' ); ?></th>
						<th class="dt-head-left"><?php esc_html_e( 'Salida', 'control-horas' ); ?></th>
						<th class="dt-head-left"><?php esc_html_e( 'Duración', 'control-horas' ); ?></th>
						<th class="dt-head-left"><?php esc_html_e( 'IP', 'control-horas' ); ?></th>
						<th class="dt-head-left"><?php esc_html_e( 'Nota', 'control-horas' ); ?></th>
						<th class="dt-head-left"><?php esc_html_e( 'Acciones', 'control-horas' ); ?></th>
					</tr>
				</thead>
				<tbody></tbody>
				<tfoot>
					<tr>
						<th class="dt-head-left"><?php esc_html_e( 'Nombre', 'control-horas' ); ?></th>
						<th class="dt-head-left"><?php esc_html_e( 'Entrada', 'control-horas' ); ?></th>
						<th class="dt-head-left"><?php esc_html_e( 'Salida', 'control-horas' ); ?></th>
						<th class="dt-head-left"><?php esc_html_e( 'Duración', 'control-horas' ); ?></th>
						<th class="dt-head-left"><?php esc_html_e( 'IP', 'control-horas' ); ?></th>
						<th class="dt-head-left"><?php esc_html_e( 'Nota', 'control-horas' ); ?></th>
						<th class="dt-head-left"><?php esc_html_e( 'Acciones', 'control-horas' ); ?></th>
					</tr>
				</tfoot>
			</table>
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function($) {

				const ajaxurl = '<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>';
				var data= {
					'action': 'ch_load_data',            
					'security': '<?php echo esc_attr( wp_create_nonce( 'ch_load_data' ) ); ?>'
				}

				$.fn.dataTable.ext.errMode = 'throw';

				$('#ch-turnos').DataTable( {
					"ajax": {
						"url": ajaxurl,
						"data": data
					},
					"columnDefs": [{
						"targets": 5,
						"data": "note",
						"render": function (data, type, row) {
							if (null == data) return '';
							return data.length > 40 ?
								'<span title="'+data+'">'+data.substr( 0, 38 )+'...</span>' : data;
							},
						},{
						"targets": 6,
						"data": "id",
						"render": function (data, type, row) {
							return '<a style="text-decoration:none" href="admin.php?page=controlhoras&tab=editar&id=' + data +'"><span class="dashicons dashicons-edit"></span></a>';
						},
					}],
					"columns": [
						{ "data": "name" },
						{ "data": "start" },
						{ "data": "end" },
						{ "data": "diff" },
						{ "data": "ip" },
						{ "data": "note" },
						{ "data": "id" },
					],
					"aaSorting": [ 
						[1, 'desc'],
					],
					"language": {
						"decimal":        "",
						"emptyTable":     "Sin datos",
						"info":           "Mostrando las entradas de la _START_ a la _END_ de un total de _TOTAL_",
						"infoEmpty":      "Mostrando 0 a 0 de 0 entradas",
						"infoFiltered":   "(filtered from _MAX_ total entries)",
						"infoPostFix":    "",
						"thousands":      ",",
						"lengthMenu":     "Mostrar _MENU_ entradas",
						"loadingRecords": "Cargando...",
						"processing":     "Cargando...",
						"search":         "Buscar:",
						"zeroRecords":    "Ningún resultado",
						"paginate": {
							"first":      "Primera",
							"last":       "Última",
							"next":       "Siguiente",
							"previous":   "Anterior"
						},
						"aria": {
							"sortAscending":  ": activate to sort column ascending",
							"sortDescending": ": activate to sort column descending"
						}
					},
				});
			});
		</script> 	
		<?php
	}

	/**
	 * Echo 'Ajustes' content of plugin settings
	 *
	 * @since  0.0.0
	 */
	public function ajustes_content() {
		global $controlhoras_active_tab;
		if ( empty( $controlhoras_active_tab ) || 'ajustes' != $controlhoras_active_tab ) {
			return;
		}
		$settings = $this->plugin->ch_settings;
		?>
		<br/>
		<h1>Ajustes</h1>
		<form method="post" action="admin-post.php">			
			<?php wp_nonce_field( 'control-horas' ); ?>			
			<input type="hidden" value="control_horas_setup" name="action"/>
			<table class="form-table">
				<tbody>					
					<?php /* Header title */ ?>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Privacidad', 'control-horas' ); ?></label>
						</th>
						<td>							
							<fieldset>
								<label  for="guardar-ip">
									<input 	name="guardar-ip" 
											type="checkbox" 
											id="guardar-ip" 
											value="1"
											<?php ( $settings->get_setting( 'guardar-ip' ) == 1 ) && printf( 'checked' ); ?>
											>
											Guardar la dirección IP del usuario
								</label>								
							</fieldset>
						</td>
					</tr>					
				</tbody>				
			</table>
			<?php submit_button(); ?>
		</form>
		<?php
	}


	/**
	 * Echo 'Ajustes' content of plugin settings
	 *
	 * @since  0.0.0
	 */
	public function editar_content() {
		global $controlhoras_active_tab;
		if ( empty( $controlhoras_active_tab ) || 'editar' != $controlhoras_active_tab ) {
			return;
		}

		$id = sanitize_text_field( wp_unslash ( $_GET['id'] ) );
		if ( ! is_numeric( $id ) ) {
			return;
		}

		$ch_db = $this->plugin->ch_db;
		$shift  = $ch_db->shift_by_id( $id );
		?>
		<br/>
		<h2><?php esc_html_e( 'Editar registro número', 'control-horas' ); ?>&nbsp;<?php echo esc_html( strval( $id ) ); ?></h2>
		<form method="post" action="admin-post.php">			
			<?php wp_nonce_field( 'control-horas' ); ?>			
			<input type="hidden" value="control_horas_edit" name="action"/>
			<input type="hidden" value="<?php echo esc_attr( $id ); ?>" name="id"/>
			<table class="form-table">
				<tbody>					
					<?php /* Header title */ ?>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Usuario', 'control-horas' ); ?></label>
						</th>
						<td>
							<span><?php echo esc_html( $shift['name'] ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Inicio', 'control-horas' ); ?></label>
						</th>
						<td>							
							<span><?php echo esc_html( $shift['start'] ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Fin', 'control-horas' ); ?></label>
						</th>
						<td>							
							<span><?php echo esc_html( $shift['end'] ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Duración', 'control-horas' ); ?></label>
						</th>
						<td>							
							<span><?php echo esc_html( $shift['diff'] ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'IP', 'control-horas' ); ?></label>
						</th>
						<td>							
							<span><?php echo esc_html( $shift['ip'] ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Navegador', 'control-horas' ); ?></label>
						</th>
						<td>							
							<span><?php echo esc_html( $shift['ua'] ); ?></span>
						</td>
					</tr>					
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Nota', 'control-horas' ); ?></label>
						</th>
						<td>							
							<fieldset>
								<label  for="note">
									<textarea
										name="note" 										
										id="note"										
										rows="3"
										cols="24"
										><?php echo esc_html( $shift['note'] ); ?></textarea>											
								</label>								
							</fieldset>
						</td>
					</tr>
				</tbody>				
			</table>
			
			<a href="admin.php?page=controlhoras&tab=registros"><span class="button button-secondary">Cancelar</span></a>&nbsp;
			<?php submit_button( null, 'primary', 'submit', false ); ?>
		</form>
		<?php
	}


	/**
	 * Echo plugin settings view
	 *
	 * @since  0.0.0
	 */
	public function options_ui() {
		global $controlhoras_active_tab;
		$controlhoras_active_tab = ! empty( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'registros';

		/* messages */
		if ( isset( $_GET['message'] ) ) {

			$message       = __( 'Algo fue mal', 'control-horas' );
			$message_class = 'notice-success';

			switch ( $_GET['message'] ) {
				case 'saved':
					$message = __( 'Cambios guardados.', 'control-horas' );
					break;
				default:
					$message_class = 'notice-error';
					break;
			}
			?>
			<div id="message" class="notice <?php echo esc_attr( $message_class ); ?> is-dismissible">
				<p><?php echo esc_html( $message ); ?></p>
				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text">
						<?php esc_html_e( 'Descartar este aviso ', 'control-horas' ); ?> 
					</span>
				</button>
			</div>
		<?php } ?>

		<div class="wrap">		
			<h1>Control de horas</h1>
			<div>
				<h2 class="nav-tab-wrapper">
					<?php
						// echo tabs by tab param.
						do_action( 'controlhoras_settings_tab' );
					?>
				</h2>
		
				<?php
					// echo content by tab param.
					do_action( 'controlhoras_settings_content' );
				?>
			</div>
			<hr>
			<p>
				Tu valoración de <a href="https://wordpress.org/support/plugin/control-horas/reviews?rate=5#new-post" target="_blank">★★★★★</a>&nbsp;&nbsp; ayuda a mejorar el plugin. ¡Muchas gracias!
			</p>
		</div>
		<?php
	}
}
