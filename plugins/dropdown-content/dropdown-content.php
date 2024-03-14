<?php
class DropdownContent {
	public $template_vars = array();
	public $default_item = 0;

	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'textdomain' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueues' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'public_enqueues' ) );
		add_shortcode( 'dropdown', array( $this, 'dropdown' ) );
		add_shortcode( 'dropdown-option', array( $this, 'dropdown_option' ) );
		add_shortcode( 'dropdown-content', array( $this, 'dropdown_content' ) );
		add_filter( 'mce_external_plugins', array( $this, 'register_mce_javascript' ) );
		add_filter( 'mce_buttons', array( $this, 'register_mce_buttons' ) );
		add_filter( 'mce_external_languages', array( $this, 'mce_external_languages' ) );
	}

	public function textdomain() {
		load_plugin_textdomain( MDRDOCO__SLUG, false, MDRDOCO__DIR . 'languages' );
	}

	public function admin_enqueues() {
		wp_enqueue_style( MDRDOCO__SLUG, MDRDOCO__URL . '/css/dropdowncontent-admin.css', array(), MDRDOCO__VERSION, 'all' );
	}

	public function public_enqueues() {
		wp_enqueue_style( MDRDOCO__SLUG, MDRDOCO__URL . '/css/dropdowncontent.css', array(), MDRDOCO__VERSION, 'all' );
		wp_enqueue_script( MDRDOCO__SLUG, MDRDOCO__URL . '/js/dropdowncontent.js', array(
			'jquery'
		), MDRDOCO__VERSION, true );
	}

	/**
	 * This seemed like a good way to allow themes to override the HTML output of the `dropdowncontent-partials` folder.
	 * If your theme has a `dropdowncontent-partials` that contains a file with the same name as one of the ones in the
	 * plugin's `dropdowncontent-partials` folder, the theme version will be loaded instead.
	 *
	 * @param $slug
	 * @param null $name
	 *
	 * @return string
	 */
	private function get_template_part( $slug, $name = null ) {
		do_action( "get_template_part_{$slug}", $slug, $name );
		$templates = array();
		$name = (string) $name;
		if ( '' !== $name ) {
			$templates[] = "{$slug}-{$name}.php";
		}
		$templates[] = "{$slug}.php";

		if ( $overridden_template = locate_template( $templates ) ) {
			return $this->load_template( $overridden_template );
		} else {
			$located = '';
			foreach ( (array) $templates as $template_name ) {
				if ( !$template_name ) {
					continue;
				}
				if ( file_exists( MDRDOCO__DIR . $template_name)) {
					$located = MDRDOCO__DIR . $template_name;
					break;
				}
			}

			if ( '' != $located ) {
				return $this->load_template( $located );
			}
		}
	}

	/**
	 * This exists so we can return content instead of echoing it (which is a requirement of shortcode output) and so
	 * we can expose the argument array and default option to the templates if needed.
	 *
	 * @param $_template_file
	 *
	 * @return string
	 */
	private function load_template( $_template_file ) {
		ob_start();
		$dropdown_vars = $this->template_vars;
		$dropdown_default = $this->default_item;
		include( $_template_file );
		$content = ob_get_clean();
		return $content;
	}

	public function dropdown( $atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				'class' => '',
				'name' => 'dropdown-content'
			), $atts
		);

		$class = explode( ' ', trim( $atts['class'] ) );
		$class[] = 'dropdowncontent-dropdown';
		$class = implode( ' ', $class );

		$this->default_item = 0;
		$this->template_vars = array(
			'class' => $class,
			'name' => trim( $atts['name'] ),
			'content' => do_shortcode( $content )
		);

		return $this->get_template_part( 'dropdowncontent-partials/dropdown' );
	}

	public function dropdown_option( $atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				'value' => '',
				'default' => false
			), $atts
		);

		$this->template_vars = array(
			'value' => trim( $atts['value'] ),
			'selected' => $atts['default'] === 'true' ? 'selected' : '',
			'content' => do_shortcode( $content )
		);

		if ( $this->template_vars['selected'] ) {
			$this->default_item = $this->template_vars['value'];
		}

		return $this->get_template_part( 'dropdowncontent-partials/dropdown-option' );
	}

	public function dropdown_content( $atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				'class' => '',
				'name' => 'dropdown-content',
				'value' => ''
			), $atts
		);

		$class = explode( ' ', trim( $atts['class'] ) );
		$class[] = 'dropdowncontent-content';
		if ( $this->default_item === $atts['value'] ) {
			$class[] = 'dropdowncontent-content-selected';
		}
		$class = implode( ' ', $class );

		$this->template_vars = array(
			'class' => trim( $class ),
			'name' => trim( $atts['name'] ),
			'value' => trim( $atts['value'] ),
			'content' => do_shortcode( $content )
		);

		return $this->get_template_part( 'dropdowncontent-partials/dropdown-content' );
	}

	public static function register_mce_javascript( $plugin_array ) {
		$plugin_array['dropdowncontent'] = MDRDOCO__URL . "js/dropdowncontent-tinymce.js";
		return $plugin_array;
	}

	public static function register_mce_buttons( $buttons ) {
		array_push( $buttons, 'separator', 'dropdowncontent' );
		return $buttons;
	}

	public function mce_external_languages( $locales ) {
		$locales['dropdowncontent'] = MDRDOCO__DIR . 'tinymce_langs.php';
		return $locales;
	}
}
