<?php

require_once dirname( __FILE__ ) . '/base.ctrl.php';

class CF7_CTRL_COPY_TO_CLIPBOARD extends CF7_COMPLEMENTARY_CTRL {

	protected $title = 'Copy to Clipboard';
	protected $description;
	protected $image_url = 'copy-to-clipboard/image.png';

	public function __construct( $container_obj, $slug ) {
		$this->description = __( 'Adds the copy to clipboard behavior to text fields and textareas.<br>After enabling Copy to Clipboard, assign the <b><i>clipboard</i></b> class name to the text and textarea fields where to apply the control.<br>Ex. [text my-field class:<b>clipboard</b>]', 'cf7-datasource' );
		parent::__construct( $container_obj, $slug );

		if ( $this->container_obj->is_control_active( $slug ) ) {
			add_action( 'wpcf7_init', array( $this, 'wpcf7_init' ), 10 );
		}
	} // End __construct

	public function wpcf7_init() {
		// Enqueue resources
		wp_enqueue_script( 'jquery' );
		wp_enqueue_style( 'cf7-ds-clopy-to-clipboard-css', plugins_url( '/copy-to-clipboard/styles.css', __FILE__ ), array(), CF7_DATASOURCE::$version );
		wp_enqueue_script( 'cf7-ds-clopy-to-clipboard-js', plugins_url( '/copy-to-clipboard/script.js', __FILE__ ), array( 'jquery' ), CF7_DATASOURCE::$version, true );
		wp_localize_script( 'cf7-ds-clopy-to-clipboard-js', 'cf7_ds_clopy_to_clipboard', array( 'copied' => __( 'copied', 'cf7-datasource' ) ) );
	} // End wpcf7_init
}

if ( isset( $container_obj ) && $slug ) {
	new CF7_CTRL_COPY_TO_CLIPBOARD( $container_obj, $slug );
}
