<?php
/**
 * Formstack Form Widget
 *
 * @package Formstack
 * @author Formstack
 */

/**
 * Class Formstack_Widget
 */
class Formstack_Widget extends WP_Widget {

	private $fields = array( 'formkey', 'nojquery', 'nojqueryui', 'nomodernizr', 'no_style', 'no_style_strict' );

	/**
	 * Formstack_Widget constructor.
	 */
	public function __construct() {
		parent::__construct(
			'fs_wp_widget',
			esc_html__( 'Formstack', 'formstack' ),
			array(
				'description' => esc_html__( 'Easily embed Formstack forms into your sidebar.', 'formstack' ),
			),
			array(
				'width' => 200,
			)
		);
	}

	/**
	 * Render our frontend output.
	 *
	 * @since unknown
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		if ( empty( $instance['formkey'] ) ) {
			return;
		}

		list( $form ) = explode( '-', $instance['formkey'] );
		$wp = wp_remote_get( "https://www.formstack.com/forms/wp-ad.php?form={$form}" );
		$extras = formstack_get_extra_url_params( $instance );

		$script_url   = add_query_arg( $extras, "https://www.formstack.com/forms/js.php?{$instance['formkey']}" );
		$noscript_url = add_query_arg( $extras, "https://www.formstack.com/forms/?{$instance['formkey']}" );
	    ?>

		<div class="fs_wp_sidebar">
			<script type="text/javascript" src="<?php echo esc_url( $script_url ); ?>"></script>
			<noscript>
				<a href="<?php echo esc_url( $noscript_url ); ?>" title="<?php esc_attr_e( 'Online Form', 'formstack' ); ?>"><?php esc_html_e( 'Online Form', 'formstack' ); ?></a>
			</noscript>
			<?php
				echo wp_kses_post( wp_remote_retrieve_body( $wp ) );
			?>
		</div>
	<?php

	}

	/**
	 * Save our widget settings.
	 *
	 * @since unknown
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		foreach ( $this->fields as $i => $field ) {
			if ( empty( $new_instance[ $field ] ) ) {
				unset( $instance[ $field ] );
				continue;
			}

			$instance[ $field ] = 'true';
			if ( 'formkey' === $field ) {
				$instance[ $field ] = strip_tags( $new_instance[ $field ] );
			}
		}

		return $instance;
	}

	/**
	 * Render our admin side widget form.
	 *
	 * @since unknown
	 *
	 * @param array $instance
	 * @return mixed
	 */
	public function form( $instance ) {

		$settings      = get_option( 'formstack_settings', '' );
		$client_id     = ( isset( $settings['client_id'] ) ) ? $settings['client_id'] : '';
		$client_secret = ( isset( $settings['client_secret'] ) ) ? $settings['client_secret'] : '';
		$oauth_code    = get_option( 'formstack_oauth2_code', '' );

		if ( empty( $client_id ) || empty ( $client_secret ) || empty ( $oauth_code ) ) {
			echo $this->no_app_set_up();
			return;
		}
		if ( $client_id && $client_secret && $oauth_code ) {
			$formstack_api = new Formstack_API_V2(
				array(
					'client_id'     => $client_id,
					'client_secret' => $client_secret,
					'redirect_uri'  => admin_url( 'admin.php?page=Formstack' ),
					'code'          => $oauth_code
				)
			);
		}

		$form_count = $formstack_api->get_form_count();
		if ( empty( $form_count ) ) {
			printf(
				'<p>' . esc_attr( 'Your account does not appear to have any forms at the moment, please add some at %s or refresh form cache', 'formstack' ) . '</p>',
				'<a href="https://www.formstack.com" target="_blank">https://www.formstack.com</a>'
			);
			return;
		}

		$fields = array();
		foreach ( $this->fields as $i => $field ) {
			$fields[ $field ] = array(
				'id'    => $this->get_field_id( $field ),
				'name'  => $this->get_field_name( $field ),
				'value' => ( isset( $instance[ $field ] ) ) ? esc_attr( $instance[ $field ] ) : '',
			);
		}
		?>
		<label for="<?php echo esc_attr( $fields['formkey']['id'] ); ?>">
			<?php esc_html_e( 'Choose a form to embed:', 'formstack' ); ?>
			<select class="widefat" name="<?php echo esc_attr( $fields['formkey']['name'] ); ?>" id="<?php echo esc_attr( $fields['formkey']['id'] ); ?>">
				<option value=''><?php esc_html_e( 'Select a form to display', 'formstack' ); ?></option>
			<?php
			$forms_response = $formstack_api->get_forms();
			if ( ! empty( $forms_response['forms'] ) ) {
				foreach ( $forms_response['forms'] as $form ) {
					$sel = selected( $fields['formkey']['value'], "{$form->id}-{$form->viewkey}", false );
					?>
					<option <?php echo $sel; ?> value="<?php echo esc_attr( "{$form->id}-{$form->viewkey}" ); ?>">
						<?php echo esc_html( $form->name ); ?></option>
					<?php
				}
			}
			?>
			</select>
		</label>

		<ul>
			<?php
			$extras = $this->get_url_extras();
			foreach ( $extras as $name => $text ) {
				$extras_field = ( isset( $instance[ $name ] ) ) ? 'on' : '';
				?>
				<li>
					<label for="<?php echo $this->get_field_id( $name ); ?>"><input class="checkbox" name="<?php echo $this->get_field_name( $name ); ?>" id="<?php echo $this->get_field_id( $name ); ?>" type="checkbox" value="<?php echo esc_attr( $name ); ?>" <?php checked( $extras_field, 'on' ); ?>><?php echo $text; ?>
					</label></li>
				<?php
			}
			?>
		</ul>
		<?php
	}

	public function no_app_set_up() {
	?>
		<p>
			<?php esc_html_e( 'Please set your app client credentials on the Formstack settings page.', 'formstack' ); ?>
		</p>
	<?php
	}

	public function get_url_extras() {
		return array(
			'nojquery'         => esc_html__( 'I do not need jQuery', 'formstack' ),
			'nojqueryui'       => esc_html__( 'I do not need jQuery UI', 'formstack' ),
			'nomodernizr'      => esc_html__( 'I do not need Modernizr', 'formstack' ),
			'no_style'         => esc_html__( 'Use bare-bones-css', 'formstack' ),
			'no_style_strict' => esc_html__( 'Use no CSS', 'formstack' ),
		);
	}
}

/**
 * Register our widget.
 *
 * @since unknown
 */
function formstack_widget_init() {
	register_widget( 'Formstack_Widget' );
}
add_action( 'widgets_init', 'formstack_widget_init' );
