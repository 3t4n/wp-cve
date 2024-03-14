<?php class EModal_Controller_Admin_Theme extends EModal_Controller {

	static function factory() {
		self::$instance = new EModal_Controller_Admin_Theme;
		self::$instance->action_edit();
	}

	public function action_edit() {
		global $current_theme;
		$current_theme = new EModal_Model_Theme( $this->check_id() );
		if ( $this->check_post_nonce() ) {
			$new_values = apply_filters( 'emodal_theme_pre_save', $current_theme->as_array() );
			$current_theme->set_fields( apply_filters( "emodal_model_theme_meta_defaults", $new_values ) );
			$current_theme->save();
			do_action( 'emodal_theme_save', $current_theme );
			EModal_Admin_Notice::add( __( 'Theme Updated.', 'easy-modal' ), 'updated' );
			$this->redirect_to_edit();
		}
		$view = new EModal_View_Admin_Theme_Form;
		$view->set( 'title', __( 'Edit Theme', 'easy-modal' ) );
		$view->set( 'tabs', apply_filters( 'emodal_admin_theme_form_tabs', array() ) );
		self::$instance->view = $view;
	}

	public function check_id() {
		return 1;
	}

	public function edit_url() {
		return $this->index_url();
	}

	public function index_url() {
		return emodal_admin_url( 'themes' );
	}
}


add_filter( 'emodal_theme_pre_save', 'emodal_theme_pre_save', 1 );
function emodal_theme_pre_save( $new_values ) {
	$new_values['name'] = sanitize_text_field( empost( 'theme.name' ) );

	$new_values['meta']['overlay']['background']['color']   = sanitize_text_field( empost( 'theme.meta.overlay.background.color' ) );
	$new_values['meta']['overlay']['background']['opacity'] = floatval( empost( 'theme.meta.overlay.background.opacity' ) );

	$new_values['meta']['container']['padding']                 = floatval( empost( 'theme.meta.container.padding' ) );
	$new_values['meta']['container']['background']['color']     = sanitize_text_field( empost( 'theme.meta.container.background.color' ) );
	$new_values['meta']['container']['background']['opacity']   = floatval( empost( 'theme.meta.container.background.opacity' ) );
	$new_values['meta']['container']['border']['style']         = empost( 'theme.meta.container.border.style' );
	$new_values['meta']['container']['border']['color']         = sanitize_text_field( empost( 'theme.meta.container.border.color' ) );
	$new_values['meta']['container']['border']['width']         = floatval( empost( 'theme.meta.container.border.width' ) );
	$new_values['meta']['container']['border']['radius']        = floatval( empost( 'theme.meta.container.border.radius' ) );
	$new_values['meta']['container']['boxshadow']['inset']      = empost( 'theme.meta.container.boxshadow.inset' );
	$new_values['meta']['container']['boxshadow']['horizontal'] = floatval( empost( 'theme.meta.container.boxshadow.horizontal' ) );
	$new_values['meta']['container']['boxshadow']['vertical']   = floatval( empost( 'theme.meta.container.boxshadow.vertical' ) );
	$new_values['meta']['container']['boxshadow']['blur']       = floatval( empost( 'theme.meta.container.boxshadow.blur' ) );
	$new_values['meta']['container']['boxshadow']['spread']     = floatval( empost( 'theme.meta.container.boxshadow.spread' ) );
	$new_values['meta']['container']['boxshadow']['color']      = sanitize_text_field( empost( 'theme.meta.container.boxshadow.color' ) );
	$new_values['meta']['container']['boxshadow']['opacity']    = floatval( empost( 'theme.meta.container.boxshadow.opacity' ) );

	$new_values['meta']['title']['font']['color']            = sanitize_text_field( empost( 'theme.meta.title.font.color' ) );
	$new_values['meta']['title']['font']['size']             = floatval( empost( 'theme.meta.title.font.size' ) );
	$new_values['meta']['title']['font']['family']           = empost( 'theme.meta.title.font.family' );
	$new_values['meta']['title']['text']['align']            = empost( 'theme.meta.title.text.align' );
	$new_values['meta']['title']['textshadow']['horizontal'] = floatval( empost( 'theme.meta.title.textshadow.horizontal' ) );
	$new_values['meta']['title']['textshadow']['vertical']   = floatval( empost( 'theme.meta.title.textshadow.vertical' ) );
	$new_values['meta']['title']['textshadow']['blur']       = floatval( empost( 'theme.meta.title.textshadow.blur' ) );
	$new_values['meta']['title']['textshadow']['color']      = sanitize_text_field( empost( 'theme.meta.title.textshadow.color' ) );
	$new_values['meta']['title']['textshadow']['opacity']    = floatval( empost( 'theme.meta.title.textshadow.opacity' ) );

	$new_values['meta']['content']['font']['color']  = sanitize_text_field( empost( 'theme.meta.content.font.color' ) );
	$new_values['meta']['content']['font']['family'] = empost( 'theme.meta.content.font.family' );

	$new_values['meta']['close']['text']                     = sanitize_text_field( empost( 'theme.meta.close.text' ) );
	$new_values['meta']['close']['location']                 = empost( 'theme.meta.close.location' );
	$new_values['meta']['close']['position']['top']          = empost( 'theme.meta.close.position.top' );
	$new_values['meta']['close']['position']['left']         = empost( 'theme.meta.close.position.left' );
	$new_values['meta']['close']['position']['bottom']       = empost( 'theme.meta.close.position.bottom' );
	$new_values['meta']['close']['position']['right']        = empost( 'theme.meta.close.position.right' );
	$new_values['meta']['close']['padding']                  = floatval( empost( 'theme.meta.close.padding' ) );
	$new_values['meta']['close']['background']['color']      = sanitize_text_field( empost( 'theme.meta.close.background.color' ) );
	$new_values['meta']['close']['background']['opacity']    = floatval( empost( 'theme.meta.close.background.opacity' ) );
	$new_values['meta']['close']['font']['color']            = sanitize_text_field( empost( 'theme.meta.close.font.color' ) );
	$new_values['meta']['close']['font']['size']             = floatval( empost( 'theme.meta.close.font.size' ) );
	$new_values['meta']['close']['font']['family']           = empost( 'theme.meta.close.font.family' );
	$new_values['meta']['close']['border']['style']          = empost( 'theme.meta.close.border.style' );
	$new_values['meta']['close']['border']['color']          = sanitize_text_field( empost( 'theme.meta.close.border.color' ) );
	$new_values['meta']['close']['border']['width']          = floatval( empost( 'theme.meta.close.border.width' ) );
	$new_values['meta']['close']['border']['radius']         = floatval( empost( 'theme.meta.close.border.radius' ) );
	$new_values['meta']['close']['boxshadow']['inset']       = empost( 'theme.meta.close.boxshadow.inset' );
	$new_values['meta']['close']['boxshadow']['horizontal']  = floatval( empost( 'theme.meta.close.boxshadow.horizontal' ) );
	$new_values['meta']['close']['boxshadow']['vertical']    = floatval( empost( 'theme.meta.close.boxshadow.vertical' ) );
	$new_values['meta']['close']['boxshadow']['blur']        = floatval( empost( 'theme.meta.close.boxshadow.blur' ) );
	$new_values['meta']['close']['boxshadow']['spread']      = floatval( empost( 'theme.meta.close.boxshadow.spread' ) );
	$new_values['meta']['close']['boxshadow']['color']       = sanitize_text_field( empost( 'theme.meta.close.boxshadow.color' ) );
	$new_values['meta']['close']['boxshadow']['opacity']     = floatval( empost( 'theme.meta.close.boxshadow.opacity' ) );
	$new_values['meta']['close']['textshadow']['horizontal'] = floatval( empost( 'theme.meta.close.textshadow.horizontal' ) );
	$new_values['meta']['close']['textshadow']['vertical']   = floatval( empost( 'theme.meta.close.textshadow.vertical' ) );
	$new_values['meta']['close']['textshadow']['blur']       = floatval( empost( 'theme.meta.close.textshadow.blur' ) );
	$new_values['meta']['close']['textshadow']['color']      = sanitize_text_field( empost( 'theme.meta.close.textshadow.color' ) );
	$new_values['meta']['close']['textshadow']['opacity']    = floatval( empost( 'theme.meta.close.textshadow.opacity' ) );

	return $new_values;
}