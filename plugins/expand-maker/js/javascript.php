<?php
Class ExpmJs {

	public function __construct() {
		add_action('admin_enqueue_scripts', array($this,'registerScripts'));
	}

	public function registerScripts($hook) {
		wp_register_script('bootstrap.min', YRM_JAVASCRIPT.'bootstrap.min.js', array('wp-color-picker'), EXPM_VERSION);
		//wp_register_script('yrmGoogleFonts', YRM_JAVASCRIPT.'yrmGoogleFonts.js', array(), EXPM_VERSION);
		wp_register_script('yrmminicolors.js', YRM_JAVASCRIPT.'minicolors.js', array(), EXPM_VERSION);
		wp_register_script('readMoreJs', YRM_JAVASCRIPT.'yrmMore.js', array(), EXPM_VERSION);
		wp_register_script('yrmMorePro', YRM_JAVASCRIPT.'yrmMorePro.js', array('readMoreJs'), EXPM_VERSION);
		wp_register_script('yrmselect2', YRM_JAVASCRIPT.'select2.js', array(), EXPM_VERSION);
		wp_register_script('yrmBackend', YRM_JAVASCRIPT.'yrmBackend.js', array('wp-color-picker'), EXPM_VERSION);
		wp_register_script('yrmBackendPro.js', YRM_JAVASCRIPT.'yrmBackendPro.js', array('wp-color-picker'), EXPM_VERSION);
		wp_register_script('ConditionBuilder.js', YRM_ADMIN_JAVASCRIPT.'ConditionBuilder.js', array(), EXPM_VERSION);
		wp_register_script('ionRangeSlider.js', YRM_JAVASCRIPT.'ionRangeSlider.js', array('wp-color-picker'), EXPM_VERSION);

        $blockSettings = $this->gutenbergParams();
		wp_register_script('WpReadMoreBlockMin', YRM_ADMIN_JAVASCRIPT_GENERAL.'WpReadMoreBlockMin.js', array(), EXPM_VERSION);
        wp_localize_script('WpReadMoreBlockMin', 'YRM_GUTENBERG_PARAMS',$blockSettings);
        wp_enqueue_script('WpReadMoreBlockMin');

		$allowPages = array(
			'read-more_page_'.YRM_SUPPORT_MENU_KEY,
			'read-more_page_'.YRM_FIND_PAGE,
			'toplevel_page_'.YRM_ACCORDION_PAGE,
			'toplevel_page_'.YRM_FIND_PAGE,
			'toplevel_page_readMore',
			'read-more_page_addNew',
			'read-more_page_button',
			'read-more_page_rmmore-settings'
		);

		if(in_array($hook, $allowPages)) {
            if(function_exists('wp_enqueue_code_editor')) {
                wp_enqueue_code_editor(array( 'type' => 'text/html'));
            }
			wp_enqueue_media();
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery');
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('yrmminicolors.js');
			wp_enqueue_script('bootstrap.min');
			wp_enqueue_script('yrmselect2');
			wp_enqueue_script('ionRangeSlider.js');
			wp_enqueue_script('yrmBackend');
			wp_enqueue_script('ConditionBuilder.js');
			$ajaxNonce = wp_create_nonce("YrmNonce");
			wp_localize_script('yrmBackend', 'yrmBackendData', array(
				'nonce' => $ajaxNonce,
				'copied' => __('Copied', YRM_LANG),
				'copyToClipboard' => __('Copy to clipboard', YRM_LANG),
				'YRM_PAGE_URL' => YRM_PAGE_URL
			));
			if(YRM_PKG > YRM_FREE_PKG) {
				wp_enqueue_script('yrmBackendPro.js');
				//wp_enqueue_script('yrmGoogleFonts');
				wp_enqueue_script('yrmMorePro');
			}
			wp_enqueue_script('readMoreJs');
		}
	}

    private function gutenbergParams() {

        $settings = array(
            'allReadMores' => \ReadMoreData::getReadMoresIdAndTitle(),
            'title'   => __('Read More', YRM_LANG),
            'description'   => __('This block will help you to add read more’s shortcode inside the page content', YRM_LANG),
            'logo_classname' => 'yrm-gutenberg-logo',
            'read_more_select' => __('Select Read More', YRM_LANG)
        );

        return $settings;
    }

}

$jsObj = new ExpmJs();