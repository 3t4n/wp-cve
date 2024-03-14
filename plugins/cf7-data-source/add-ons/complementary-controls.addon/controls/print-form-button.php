<?php

require_once dirname( __FILE__ ) . '/base.ctrl.php';

class CF7_CTRL_PRINT_FORM_BUTTON extends CF7_COMPLEMENTARY_CTRL {

	protected $title = 'Print Form Button';
	protected $description;
	protected $image_url = 'print-form-button/image.png';

	public function __construct( $container_obj, $slug ) {
		$this->description = __( 'It enables a form print button. Button control to print only the form area.', 'cf7-datasource' );
		parent::__construct( $container_obj, $slug );

		if ( $this->container_obj->is_control_active( $slug ) ) {
			add_action( 'wpcf7_init', array( $this, 'wpcf7_init' ) );
			add_action( 'wpcf7_admin_init', array( $this, 'wpcf7_add_tag_generator_printform' ), 55, 0 );
		}
	} // End __construct

	public function wpcf7_init() {
		wpcf7_add_form_tag( 'printform', array( $this, 'wpcf7_printform_tag_handler' ) );

	} // End wpcf7_init

	public function wpcf7_printform_tag_handler( $tag ) {
		$class = wpcf7_form_controls_class( $tag->type, 'has-spinner' );

		$atts = array();

		$atts['class']    = $tag->get_class_option( $class );
		$atts['id']       = $tag->get_id_option();
		$atts['tabindex'] = $tag->get_option( 'tabindex', 'signed_int', true );

		$value = isset( $tag->values[0] ) ? $tag->values[0] : '';

		if ( empty( $value ) ) {
			$value = __( 'Print', 'cf7-datasource' );
		}

		$atts['type']  = 'button';
		$atts['value'] = $value;

		$atts = wpcf7_format_atts( $atts );

		$html = sprintf( '<input %s onclick="cf7_ds_print_form(this);" />', $atts );

		// Enqueue resources
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'cf7-ds-print-form-js', plugins_url( '/print-form-button/script.js', __FILE__ ), array( 'jquery' ), CF7_DATASOURCE::$version, true );
		wp_enqueue_style( 'cf7-ds-print-form-css', plugins_url( '/print-form-button/styles.css', __FILE__ ), array(), CF7_DATASOURCE::$version );

		return $html;
	} // End wpcf7_printform_tag_handler

	public function wpcf7_add_tag_generator_printform() {
		$tag_generator = WPCF7_TagGenerator::get_instance();
		$tag_generator->add(
			'printform',
			__( 'print form button', 'cf7-datasource' ),
			array( $this, 'wpcf7_tag_generator_printform' ),
			array( 'nameless' => 1 )
		);
	} // End wpcf7_add_tag_generator_printform

	public function wpcf7_tag_generator_printform( $contact_form, $args = '' ) {
		$args = wp_parse_args( $args, array() );

		$description = __( 'Generate a form-tag for a print form button. For more details, see', 'cf7-datasource' );

		$desc_link = '<a href="https://cf7-datasource.dwbooster.com/complementary-controls-addon#print-form" target="_blank">' . __( 'Print form button', 'cf7-datasource' ) . '</a>';

		?>
		<div class="control-box">
			<fieldset>
				<legend><?php echo sprintf( $description . ' %s.', $desc_link ); // phpcs:ignore WordPress.Security.EscapeOutput ?></legend>

				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'Label', 'contact-form-7' ) ); ?></label></th>
							<td><input type="text" name="values" class="oneline" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>" /></td>
						</tr>

						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', 'contact-form-7' ) ); ?></label></th>
							<td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
						</tr>

						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'contact-form-7' ) ); ?></label></th>
							<td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
						</tr>
					</tbody>
				</table>
			</fieldset>
		</div>

		<div class="insert-box">
			<input type="text" name="printform" class="tag code" readonly="readonly" onfocus="this.select()" />

			<div class="submitbox">
				<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
			</div>
		</div>
		<?php
	} // End wpcf7_tag_generator_printform

} // End CF7_CTRL_PRINT_FORM_BUTTON

if ( isset( $container_obj ) && $slug ) {
	new CF7_CTRL_PRINT_FORM_BUTTON( $container_obj, $slug );
}
