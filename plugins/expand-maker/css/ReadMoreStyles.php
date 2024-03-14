<?php
Class ReadMoreStyles {

	public function __construct() {
		
	}

	public function registerStyles($hook) {

		wp_register_style('readMoreBootstrap', YRM_CSS_URL.'bootstrap.css', array(), EXPM_VERSION);
        wp_register_style('readMoreAdmin', YRM_CSS_URL.'readMoreAdmin.css', array(), EXPM_VERSION);
        wp_register_style('yrmselect2', YRM_CSS_URL.'select2.css', array(), EXPM_VERSION);
        wp_register_style('yrmcolorpicker.css', YRM_ADMIN_CSS_URL.'colorpicker.css', array(), EXPM_VERSION);

        wp_register_style('RMGutenberg', YRM_ADMIN_CSS_GENERAL_URL.'RMGutenberg.css', array(), EXPM_VERSION);
        wp_enqueue_style('RMGutenberg');

        wp_register_style('ion.rangeSlider.skinFlat.css', YRM_CSS_URL.'ion.rangeSlider.skinFlat.css', array(), EXPM_VERSION);
        wp_register_style('ion.rangeSlider.css', YRM_CSS_URL.'ion.rangeSlider.css', array(), EXPM_VERSION);

		$allowPages = array(
			'read-more_page_'.YRM_SUPPORT_MENU_KEY,
			'toplevel_page_readMore',
			'toplevel_page_'.YRM_FIND_PAGE,
			'toplevel_page_'.YRM_ACCORDION_PAGE,
			'read-more_page_addNew',
			'read-more_page_button',
			'read-more_page_rmmore-help',
			'read-more_page_rmmore-settings',
			'read-more_page_'.YRM_FIND_PAGE,
			'read-more_page_'.YRM_ACCORDION_PAGE,
			'read-more_page_rmmore-license'
		);

		if(in_array($hook, $allowPages)) {
			wp_enqueue_style('yrmcolorpicker.css');
			wp_enqueue_style('yrmselect2');
			wp_enqueue_style('readMoreAdmin');
			wp_enqueue_style('readMoreBootstrap');
			wp_enqueue_style('ion.rangeSlider.skinFlat.css');
			wp_enqueue_style('ion.rangeSlider.css');
			wp_enqueue_style( 'dashicons' );

			if(YRM_PKG > YRM_SILVER_PKG) {
				wp_register_style('colorbox.css', YRM_CSS_URL . "colorbox/colorbox.css");
				wp_enqueue_style('colorbox.css');
			}
		}

		if($hook == 'read-more_page_rmmore-plugins') {
			wp_enqueue_style('readMoreAdmin');
		}
	}

}
