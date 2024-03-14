<?php
class PLZ_PleziForm extends ET_Builder_Module {
	public $slug = 'plz_plezi_form';
	public $vb_support = 'on';
	public $debug_module = true;

	protected $module_credits = array(
		'module_uri' => 'https://www.plezi.co/en/one/?utm_medium=referral&utm_source=wordpress&utm_campaign=plezi_one&utm_content=plugin&utm_term=wp-plugins-list',
		'author' => 'Plezi',
		'author_uri' => 'https://www.plezi.co?utm_medium=referral&utm_source=wordpress&utm_campaign=plezi_one&utm_content=plugin&utm_term=wp-plugins-list',
	);

	public function init() {
		$this->name = esc_html__( 'Plezi form', 'plezi-for-wordpress' );
		$this->main_css_element = '%%order_class%%.additional_class';

		if ( is_admin() && $this->debug_module ) :
			add_action( 'admin_head', array( $this, 'remove_from_local_storage' ) );
		endif;
	}

	public function get_fields() {
		$options = array(
			'body'            => array(
				'_wpnonce'      => wp_create_nonce( 'wp_rest' ),
				'args' 					=> 'sort_by=created_at&sort_dir=desc&page=1&per_page=20',
				'filters' 			=> array('sort_by' => 'created_at', 'sort_dir' => 'desc', 'page' => '1', 'per_page' => '20' )
			),
			'headers'         => array(
				'Cache-Control' => 'no-cache',
			),
			'cookies'         => plz_get_user_cookies()
		);

		$result = wp_remote_post( get_rest_url( null, 'plz/v2/configuration/get-forms-list' ), $options );
		$forms = json_decode( wp_remote_retrieve_body( $result ) );
		$options = array( '' => __( 'Choose a Plezi form', 'plezi-for-wordpress' ) );

		if ( $forms && ! isset( $forms->error ) && isset( $forms->list ) ) :
			foreach ( $forms->list as $form ) :
				$options[ $form->id ] = $form->attributes->custom_title;
			endforeach;
		endif;

		return array(
			'plezi_form' => array(
				'default' => esc_html__( 'Choose a form', 'plezi-for-wordpress' ),
				'label' => esc_html__( 'Plezi form', 'plezi-for-wordpress' ),
				'type' => 'select',
				'option_category' => 'basic_option',
				'options' => $options,
				'toggle_slug' => 'main_content',
				'description' => esc_html__( 'Choose a plezi form', 'plezi-for-wordpress' ),
			),
		);
	}

	public function remove_from_local_storage() {
		echo "<script>localStorage.removeItem('et_pb_templates_" . esc_attr( $this->slug ) . "');</script>";
	}

	public function render( $attrs, $content = null, $render_slug ) {
		$plezi_form = $this->props['plezi_form'];
		$output = '';

		if ( ! empty( $plezi_form ) ) :
			$output = do_shortcode( sprintf( '[plezi form=%1$s]', esc_html( $plezi_form ) ) );
		endif;

		return $output;
	}
}

new PLZ_PleziForm();
