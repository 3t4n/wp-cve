<?php
/**
 * The Subscribe class.
 *
 * @package    Rock_Convert\Inc\Frontend\Widget
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */

namespace Rock_Convert\Inc\Frontend\Widget;

use Rock_Convert\Inc\Admin\Utils;

/**
 * Subscribe widget class
 */
class Subscribe extends \WP_Widget {

	/**
	 * Construct
	 */
	public function __construct() {
		parent::__construct(
			'rock_convert_subscribe_widget',
			__( 'Caixa de captura | Rock Convert', 'rock-convert' ),
			array( 'description' => __( 'Colete e-mails dos leitores no site', 'rock-convert' ) )
		);

		add_action( 'wp_enqueue_scripts', array( $this, 'google_recaptcha_script' ) );
	}

	/**
	 * Enqueu Recaptcha js script
	 *
	 * @return void
	 */
	public function google_recaptcha_script() {
		if ( get_option( '_rock_convert_g_site_key' ) && get_option( '_rock_convert_g_secret_key' ) ) {
			wp_enqueue_script( 'google-recaptcha', 'https://www.google.com/recaptcha/api.js', '', PLUGIN_VERSION, true );
		}
	}

	/**
	 * Create a recaptcha field on form
	 *
	 * @param string $token Google recaptcha account token from user.
	 * @return void
	 */
	public function google_recaptcha_box( $token ) {
		echo '<div id="widget_g_recaptcha" class="g-recaptcha" data-sitekey="' . esc_attr( $token ) . '"></div>';
	}

	/**
	 * Widget that build form
	 *
	 * @param array      $args Array of args.
	 * @param \WP_Widget $instance An intance of WordPress widget class.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		$title         = apply_filters( 'widget_title', $instance['title'] );
		$hint          = apply_filters( 'widget_title', $instance['hint'] );
		$submit        = apply_filters( 'widget_title', $instance['submit'] );
		$redirect_page = apply_filters( 'widget_title', $instance['redirect_page'] );

		$bg_color   = ( ! empty( $instance['bg_color'] ) ) ? $instance['bg_color'] : '#f5f4ef';
		$btn_color  = ( ! empty( $instance['btn_color'] ) ) ? $instance['btn_color'] : '#6083a9';
		$text_color = ( ! empty( $instance['text_color'] ) ) ? $instance['text_color'] : 'rgba(31,30,29,1)';

		$rock_convert_subscribe_once
			= wp_create_nonce( 'rock_convert_subscriber_nonce' );

		$current_url = home_url();
		$get_post_id = ( get_queried_object_id() ? get_queried_object_id() : 0 );

		$allowed_html = array(
			'br'   => array(),
			'div'  => array(),
			'span' => array(),
		);

		echo wp_kses( $args['before_widget'], $allowed_html );
		?>

		<?php if ( $this->isError() ) { ?>
		<div class="rock-convert-alert-error" id="rock-convert-alert-box" role="alert">
			<?php echo esc_html__( '<strong>Ops!</strong><br/> Favor informar um e-mail válido!', 'rock-convert' ); ?>
		</div>
	<?php } ?>

		<?php if ( $this->isRecaptcha() ) { ?>
		<div class="rock-convert-alert-error" id="rock-convert-alert-box" role="alert">
			<?php echo esc_html__( '<strong>Ops!</strong><br/> Favor marcar o captcha corretamente!', 'rock-convert' ); ?>
		</div>
	<?php } ?>

		<?php if ( $this->isSuccess() ) { ?>
		<div class="rock-convert-alert-success" id="rock-convert-alert-box" role="alert">
			<?php echo esc_html__( '<strong>Pronto!</strong><br/> E-mail cadastrado com sucesso.', 'rock-convert' ); ?>
		</div>
	<?php } ?>

		<div class="rock-convert-subscribe-form" style="background-color: <?php echo esc_attr( $bg_color ); ?>">
			<h5 class="rock-convert-subscribe-form-title" style="color: <?php echo esc_attr( $text_color ); ?>">
				<?php echo esc_html( $title ); ?>
			</h5>
			<br>

			<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<div id="html_element"></div>
				<input type="hidden" name="rock_convert_subscribe_nonce"
						value="<?php echo esc_attr( $rock_convert_subscribe_once ); ?>"/>
				<input type="hidden" name="action" value="rock_convert_subscribe_form">
				<input type="hidden" name="rock_convert_subscribe_page" value="<?php echo esc_url( $current_url ); ?>">
				<input type="hidden" name="rock_convert_subscribe_redirect_page" value="<?php echo esc_attr( $redirect_page ); ?>">
				<input type="hidden" name="rock_get_current_post_id" value="<?php echo esc_attr( $get_post_id ); ?>">

				<?php
				if ( get_option( '_rock_convert_name_field' ) ) {
					echo '<input type="text" name="rock_convert_subscribe_name" required
                       class="rock-convert-subscribe-form-email rc-mb-1"
                       placeholder="' . esc_html__( 'Nome', 'rock-convert' ) . '">';
				}
				if ( get_option( '_rock_convert_custom_field' ) ) {
					echo '<input type="text" name="rock_convert_subscribe_custom_field" required
                       class="rock-convert-subscribe-form-email rc-mb-1"
                       placeholder="' . esc_html( get_option( '_rock_convert_custom_field_label' ) ) . '">';
				}
				?>

				<input type="email" name="rock_convert_subscribe_email" required
					class="rock-convert-subscribe-form-email"
					placeholder="E-mail">

				<?php
				if ( get_option( '_rock_convert_g_site_key' ) && get_option( '_rock_convert_g_secret_key' ) ) {
					$this->google_recaptcha_box( get_option( '_rock_convert_g_site_key' ) );
				}
				?>

				<input type="submit" class="rock-convert-subscribe-form-btn"
					value="<?php echo esc_attr( $submit ); ?>" style="background-color: <?php echo esc_attr( $btn_color ); ?>">
				<span class="rock-convert-subscribe-form-hint" style="color: <?php echo esc_attr( $text_color ); ?>">
					<?php echo esc_attr( $hint ); ?>
				</span>
			</form>

		</div>
		<?php
		echo wp_kses( $args['after_widget'], $allowed_html );
	}

	/**
	 * Recaptcha Error function
	 *
	 * @return boolean
	 */
	public function isError() {
		return isset( $_SERVER['QUERY_STRING'] ) && 'error=rc-subscribe-email-invalid' === $_SERVER['QUERY_STRING'];
	}

	/**
	 * Recaptcha check function
	 *
	 * @return boolean
	 */
	public function isRecaptcha() {
		return isset( $_SERVER['QUERY_STRING'] ) && 'recaptcha=rc-recaptcha-invalid' === $_SERVER['QUERY_STRING'];
	}

	/**
	 * Recaptcha Success function
	 *
	 * @return boolean
	 */
	public function isSuccess() {
		return isset( $_SERVER['QUERY_STRING'] ) && 'success=rc-subscribed' === $_SERVER['QUERY_STRING'];
	}

	/**
	 * Widget form function
	 *
	 * @param  \WP_Widget $instance An instance of WordPress widget class.
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$call_back_title = esc_html__( 'Receba nossos conteúdos gratuitamente!', 'rock-convert' );
		$title           = isset( $instance['title'] ) ? $instance['title'] : $call_back_title;

		$call_back_submit = esc_html__( 'Receber conteúdo', 'rock-convert' );
		$submit           = isset( $instance['submit'] ) ? $instance['submit'] : $call_back_submit;

		$call_back_hint = esc_html__( 'Não te mandaremos spam!', 'rock-convert' );
		$hint           = isset( $instance['hint'] ) ? $instance['hint'] : $call_back_hint;

		$call_back_redirect_page = 'rc-no-redirect';
		$redirect_page           = isset( $instance['redirect_page'] ) ?
									$instance['redirect_page'] : $call_back_redirect_page;

		$call_back_bg_color = '#F5F4EF';
		$bg_color           = isset( $instance['bg_color'] ) ? $instance['bg_color'] : $call_back_bg_color;

		$call_back_btn_color = '#6083A9';
		$btn_color           = isset( $instance['btn_color'] ) ? $instance['btn_color'] : $call_back_btn_color;

		$call_back_text_color = 'rgba(31,30,29,1)';
		$text_color           = isset( $instance['text_color'] ) ? $instance['text_color'] : $call_back_text_color;

		$site_pages = get_pages();

		?>
		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Título', 'rock-convert' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_html( $this->get_field_name( 'title' ) ); ?>"
				type="text" value="<?php echo esc_html( $title ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'hint' ) ); ?>">
				<?php esc_html_e( 'Texto de ajuda', 'rock-convert' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'hint' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'hint' ) ); ?>"
				type="text" value="<?php echo esc_attr( $hint ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'submit' ) ); ?>">
				<?php esc_html_e( 'Texto do botão', 'rock-convert' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'submit' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'submit' ) ); ?>"
				type="text" value="<?php echo esc_attr( $submit ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'bg_color' ) ); ?>">
				<?php esc_html_e( 'Cor do fundo', 'rock-convert' ); ?>
			</label>
			<br>
			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'bg_color' ) ); ?>" class="color-picker"
				id="<?php echo esc_attr( $this->get_field_id( 'bg_color' ) ); ?>" value="<?php echo esc_attr( $bg_color ); ?>"
			/>
		</p>


		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'btn_color' ) ); ?>">
				<?php esc_html_e( 'Cor do botão', 'rock-convert' ); ?>
			</label>
			<br>
			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'btn_color' ) ); ?>" class="color-picker"
				id="<?php echo esc_attr( $this->get_field_id( 'btn_color' ) ); ?>" value="<?php echo esc_attr( $btn_color ); ?>"
			/>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'text_color' ) ); ?>">
				<?php esc_html_e( 'Cor do texto', 'rock-convert' ); ?>
					</label>
					<br>
			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'text_color' ) ); ?>" class="color-picker"
				id="<?php echo esc_attr( $this->get_field_id( 'text_color' ) ); ?>" value="<?php echo esc_attr( $text_color ); ?>"
			/>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'redirect_page' ) ); ?>">
				<?php esc_html_e( 'Redirecionar para', 'rock-convert' ); ?>
			</label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'redirect_page' ) ); ?>"
					id="<?php echo esc_attr( $this->get_field_id( 'redirect_page' ) ); ?>"
					class="widefat">
				<option value="rc-no-redirect" <?php echo 'rc-no-redirect' === $redirect_page ? 'selected' : null; ?>>
					-- <?php esc_html_e( 'Continuar na mesma página', 'rock-convert' ); ?> --
				</option>

				<?php foreach ( $site_pages as $obj ) { ?>
					<option value="<?php echo esc_attr( $obj->ID ); ?>" <?php echo $redirect_page === $obj->ID ? 'selected' : null; ?>>
						<?php echo esc_html( $obj->post_title ); ?>
					</option>
				<?php } ?>
			</select>
			<small>
				<?php
					esc_html_e(
						'Selecione para onde o usuário será redirecionado ao cadastrar o email.',
						'rock-convert'
					);
				?>
			</small>
		</p>

		<?php
	}

	/**
	 * Update form settings.
	 *
	 * @param \WP_Widget $new_instance A new Widget instance of WordPress default class.
	 * @param \WP_Widget $old_instance An old Widget instance of WordPress default class.
	 * @return \WP_Widget
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                  = array();
		$instance['title']         = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['hint']          = ! empty( $new_instance['hint'] ) ? sanitize_text_field( $new_instance['hint'] ) : '';
		$instance['submit']        = ! empty( $new_instance['submit'] ) ? sanitize_text_field( $new_instance['submit'] ) : '';
		$instance['redirect_page'] = ! empty( $new_instance['redirect_page'] ) ?
																	sanitize_text_field( $new_instance['redirect_page'] ) : '';

		$instance['bg_color']   = ! empty( $new_instance['bg_color'] ) ?
									sanitize_hex_color( $new_instance['bg_color'] ) : '';
		$instance['btn_color']  = ! empty( $new_instance['btn_color'] ) ?
									sanitize_hex_color( $new_instance['btn_color'] ) : '';
		$instance['text_color'] = ! empty( $new_instance['text_color'] ) ?
									sanitize_hex_color( $new_instance['text_color'] ) : '';

		return $instance;
	}
}
