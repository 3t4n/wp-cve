<?php
/** Customization Home
 *
 * @package loginpc
 */

/**
 * Customizer class for Custom theme settings
 */
class loginpc_Customize {
	/**
	 * Constructor to Action hook to register with customizer.
	 * */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'register_loginpc_customize_sections' ) );
	}
	/** Function to add Customized sections.
	 *
	 * @param string $wp_customize parameter to bound custom hooks to wp theme customizer.
	 */
	public function register_loginpc_customize_sections( $wp_customize ) {
		/**
		* Add settings to sections.
		*/
		$this->loginpc_customization( $wp_customize );
	}

	/** Customization Start
	 *
	 * @param string $wp_customize parameter to bound custom hooks to wp theme customizer.
	 */
	private function loginpc_customization( $wp_customize ) {
		// Panel for Customizer Login Page .
		$wp_customize->add_panel(
			'lpc-main-panel',
			array(
				'title'    => __( 'Customizer Login Page ' ),
				'priority' => 1,
			)
		);
		/** Lpc Customizer Sections */
		require LOGINPC_PLUGIN_DIR . 'customize/lpc-customize/lpc-section-presets.php';
		require LOGINPC_PLUGIN_DIR . 'customize/lpc-customize/lpc-section-title.php';
		require LOGINPC_PLUGIN_DIR . 'customize/lpc-customize/lpc-section-logo.php';
		require LOGINPC_PLUGIN_DIR . 'customize/lpc-customize/lpc-section-background.php';
		require LOGINPC_PLUGIN_DIR . 'customize/lpc-customize/lpc-section-outerform.php';
		require LOGINPC_PLUGIN_DIR . 'customize/lpc-customize/lpc-section-innerform.php';
		require LOGINPC_PLUGIN_DIR . 'customize/lpc-customize/lpc-section-inputfields.php';
		require LOGINPC_PLUGIN_DIR . 'customize/lpc-customize/lpc-section-buttondesign.php';
		require LOGINPC_PLUGIN_DIR . 'customize/lpc-customize/lpc-section-lostpassword.php';
		require LOGINPC_PLUGIN_DIR . 'customize/lpc-customize/lpc-section-backtolink.php';
		require LOGINPC_PLUGIN_DIR . 'customize/lpc-customize/lpc-section-msgmodify.php';
		require LOGINPC_PLUGIN_DIR . 'customize/lpc-customize/lpc-section-msgboxstyle.php';
		require LOGINPC_PLUGIN_DIR . 'customize/lpc-customize/lpc-section-errormodify.php';
		require LOGINPC_PLUGIN_DIR . 'customize/lpc-customize/lpc-section-errorboxstyle.php';
		require LOGINPC_PLUGIN_DIR . 'customize/lpc-customize/lpc-section-footer.php';
		require LOGINPC_PLUGIN_DIR . 'customize/lpc-customize/lpc-section-customcssjs.php';
		require LOGINPC_PLUGIN_DIR . 'customize/lpc-customize/lpc-section-exportimport.php';
	}
}
