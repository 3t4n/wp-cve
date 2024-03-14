<?php

class arforms_divi_extension extends DiviExtension {
    /**
	 * The gettext domain for the extension's translations.
	 *
	 * @since 1.6.2
	 *
	 * @var string
	 */
	public $gettext_domain = 'arforms-form-builder';

	/**
	 * The extension's WP Plugin name.
	 *
	 * @since 1.6.2
	 *
	 * @var string
	 */
	public $name = 'arforms-divi-module';

	/**
	 * The extension's version
	 *
	 * @since 1.6.2
	 *
	 * @var string
	 */
	public $version = '1.6.2';

    /**
	 * arforms_divi_extension constructor.
	 *
	 * @param string $name
	 * @param array  $args
	 */
	public function __construct( $name = 'arforms-divi-module', $args = array() ) {
		
		$this->plugin_dir     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugin_dir_url( $this->plugin_dir ).'/Divi/';
		$this->_builder_js_data  = [
			'i10n' => [
				'arformsdivi_module_data' => [
					'arformsdivi_ajax_url' => admin_url( 'admin-ajax.php' )
				]
			]
		];

		parent::__construct( $name, $args );

		if(is_plugin_active( 'divi-builder/divi-builder.php' ))
		{
			global $arfliteversion;
			wp_enqueue_style(
				'arforms_divi_style',
				'/wp-content/plugins/arforms-form-builder/integrations/Divi/styles/style.min.css',
				array(),
				$arfliteversion
			);
		}

	}

}

new arforms_divi_extension;