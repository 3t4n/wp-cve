<?php
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

class PL_Customizer_Control_Page_Editor extends WP_Customize_Control {

	/**
	 * Customizer_Page_Editor constructor.
	 *
	 * @param WP_Customize_Manager $manager Manager.
	 * @param string               $id Id.
	 * @param array                $args Constructor args.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		if ( ! empty( $args['needsync'] ) ) {
			$this->needsync = $args['needsync'];
		}
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue() {
		wp_enqueue_style( 'customizer_text_editor_css', PL_PLUGIN_INC_URL . 'customizer/css/customizer-page-editor/customizer-page-editor.css', array(), '' );
		wp_enqueue_script(
			'customizer_text_editor', PL_PLUGIN_INC_URL . 'customizer/js/customizer-page-editor/customizer-text-editor.js', array(
				'jquery',
				'customize-preview',
			), '', true
		);
	}

	/**
	 * Render the content on the theme customizer page
	 */
	public function render_content() {
		?>
		<label>
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_textarea( $this->value() ); ?>" id="<?php echo esc_attr( $this->id ); ?>" class="editorfield">
			<a onclick="javascript:WPEditorWidget.toggleEditor('<?php echo $this->id; ?>');" class="button edit-content-button"><?php _e( 'Edit', 'biznol' ); ?></a>
		</label>
		<?php
	}
}
