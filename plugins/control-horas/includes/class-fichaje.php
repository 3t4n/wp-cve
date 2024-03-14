<?php
/**
 * Control de horas Fichaje.
 *
 * @since   0.0.0
 * @package Control_Horas
 */

/**
 * Control de horas Fichaje.
 *
 * @since 0.0.0
 */
class CH_Fichaje {
	/**
	 * Parent plugin class.
	 *
	 * @since 0.0.0
	 * @var object
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since  0.0.0
	 *
	 * @param  object $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->register();
		$this->hooks();
	}

	/**
	 * Register scripts and Styles for cookies banner
	 *
	 * @since  0.0.0
	 */
	public function register() {
		// Register admin styles.
		wp_register_style(
			'controlhoras-admin',
			$this->plugin->url . 'assets/css/controlhoras-admin.css',
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

		// Add Shortcode.
		add_shortcode( 'ch_registro', 
			function( $atts = [] ) {
				$note = isset( $atts['note'] ) ? $atts['note'] : null;
				return $this->registro( $note );
			}			
		);

		if ( ! is_user_logged_in() ) {
			return false;
		}

		// add toolbar button.
		add_action( 'admin_bar_menu', array( $this, 'add_toolbar_button' ), 99 );
		// ajax scripts.
		add_action( 'wp_ajax_change_state', array( $this, 'ajax_change_state' ) );
		// enqueue admin styles and scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'admin_footer', array( $this, 'change_state_event' ) );
		add_action( 'wp_footer', array( $this, 'change_state_event' ) );
	}

	/**
	 * Register styles and scripts.
	 *
	 * @since  0.0.0
	 */
	public function enqueue_admin_assets() {
		wp_enqueue_style( 'controlhoras-admin' );
	}

	/**
	 * Initiate our hooks.
	 *
	 * @param object $admin_bar is the $admin_bar object.
	 * @since  0.0.0
	 */
	public function add_toolbar_button( $admin_bar ) {
		$ch_db = $this->plugin->ch_db;
		$active = $ch_db->status_shift( get_current_user_id() );

		$class = 'ch_button ' . ( $active ? 'parar' : 'iniciar' );

		$title = '<span class="ab-icon"></span><span class="ab-content">' . ( $active ? __( 'Finalizar', 'control-horas' ) : __( 'Empezar', 'control-horas' ) . '</span>' );

		$args = array(
			'id'     => 'ch_button',
			'parent' => 'top-secondary',
			'title'  => $title,
			'meta'   => array(
				'class'  => $class,
			),
		);
		$admin_bar->add_node( $args );
	}

	/**
	 * Add jQuery event listener to toolbar button.
	 *
	 * @since  1.0.0
	 */
	public function change_state_event() {
		?>
		<script type="text/javascript" >						
			jQuery(document).ready(function($) {

				const ajaxurl = '<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>';								
				
				var changeState= function(command, note, callback){
					var data= {
						'action': 'change_state',
						'command': command,
						'note': note,
						'security': '<?php echo esc_attr( wp_create_nonce( 'change_state' ) ); ?>'
					}
					$.post(ajaxurl, data, function(response) {						
						if (response.success) {
							callback();
						}						
					});
				}				

				// evento
				$('#wp-admin-bar-ch_button, .ch_button2').click( function() {

					var note= $(this).attr("data-ch-note");

					switch (true) {
						//////// start ////////
						case $(this).hasClass( "iniciar" ):
							
							$('#wp-admin-bar-ch_button, .ch_button2').removeClass("iniciar");
							$('#wp-admin-bar-ch_button, .ch_button2').addClass("disabled");

							changeState('iniciar', note, function(){
								$('#wp-admin-bar-ch_button > .ab-item:last, .ch_button2 > .ab-item' ).html("<span class=ab-icon></span><span class=ab-content><?php esc_html_e( 'Finalizar', 'control-horas' ); ?></span>" );
								$('.ch_button2 > .ab-item > .ab-content').addClass("button");

								$('#wp-admin-bar-ch_button, .ch_button2').removeClass("disabled");
								$('#wp-admin-bar-ch_button, .ch_button2').addClass("parar");
							});
							break;
						//////// end ////////
						case $(this).hasClass( "parar" ):							

							$('#wp-admin-bar-ch_button, .ch_button2').removeClass("parar");
							$('#wp-admin-bar-ch_button, .ch_button2').addClass("disabled");

							changeState('parar', note, function(){
								$('#wp-admin-bar-ch_button > .ab-item:last, .ch_button2 > .ab-item' ).html("<span class=ab-icon></span><span class=ab-content><?php esc_html_e( 'Empezar', 'control-horas' ); ?></span>" );
								$('.ch_button2 > .ab-item > .ab-content').addClass("button");

								$('#wp-admin-bar-ch_button, .ch_button2').removeClass("disabled");
								$('#wp-admin-bar-ch_button, .ch_button2').addClass("iniciar");
							});
							break;
						//////// pause ////////
						case $('#wp-admin-bar-ch_button').hasClass( "pausar" ):							
							return;
							break;
						default:
							return;
							break;
					}	
				});
			});
		</script> 
		<?php
	}

	/**
	 * Function wp-ajax to change states: iniciar, parar, pausar
	 *
	 * @since  1.0.0
	 */
	public function ajax_change_state() {
		check_ajax_referer( 'change_state', 'security' );

		$command = ! empty( $_POST['command'] ) ? sanitize_text_field( wp_unslash( $_POST['command'] ) ) : '';
		$note = ! empty( $_POST['note'] ) ? sanitize_text_field( wp_unslash( $_POST['note'] ) ) : '';

		if ( 'iniciar' == $command || 'parar' == $command ) {
			$user_id = get_current_user_id();
			if ( $this->change_state( $user_id, $command, $note ) ) {
				wp_send_json_success();
				wp_die();
			}
		}
		wp_send_json_error();
		wp_die();
	}

	/**
	 * Function wp-ajax to change states: iniciar, parar, pausar
	 *
	 * @param int    $user_id the wp user ID.
	 * @param string $command is the control: start, stop.
	 * @since  1.0.0
	 */
	private function change_state( $user_id, $command, $note = null ) {

		$ch_db = $this->plugin->ch_db;
		$guardarip = $this->plugin->ch_settings->get_setting( 'guardar-ip' );

		$remote_addr = '';
		$ua = '';
		if ( $guardarip ) {
			// remote_addr.
			$remote_addr = sanitize_text_field( wp_unslash ( $_SERVER['REMOTE_ADDR'] ) );
			if ( ! empty( $_SERVER['X_FORWARDED_FOR'] ) ) {
				$x_forwarded_for = explode( ',', $_SERVER['X_FORWARDED_FOR'] );
				if ( ! empty( $x_forwarded_for ) ) {
					$remote_addr = trim( $x_forwarded_for[0] );
				}
			}
			$remote_addr = preg_replace( '/[^0-9a-f:\., ]/si', '', $remote_addr );

			// user_agent.
			$user_agent = '';
			if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
				$user_agent = sanitize_text_field( wp_unslash ( $_SERVER['HTTP_USER_AGENT'] ) );
			}
		}

		$ch_db = $this->plugin->ch_db;
		$result = false;
		switch ( $command ) {
			case 'iniciar':
				$result = $ch_db->start_shift( $user_id, $remote_addr, $user_agent, $note );
				break;
			case 'parar':
				$result = $ch_db->stop_shift( $user_id, $remote_addr, $user_agent );
				break;
		}
		return is_numeric( $result );
	}


	/**
	 * Register shortcode.
	 *
	 * @since  1.0.2
	 */
	public function registro( $note = null ) {
		if ( ! is_user_logged_in() ) {
			return;
		}

		$note = isset( $note )? trim( sanitize_text_field( $note ) ) : '';
		
		$ch_db = $this->plugin->ch_db;
		$active = $ch_db->status_shift( get_current_user_id() );

		$class = 'ch_button2 ' . ( $active ? 'parar' : 'iniciar' );

		$title = '<div class="ab-item"><span class="ab-icon"></span><span class="button ab-content">' . ( $active ? __( 'Finalizar', 'control-horas' ) : __( 'Empezar', 'control-horas' ) . '</span></div>' );


		return '<div data-ch-note="' . $note . '" class="' . $class . '">'. $title . '</div>';

	}


}
