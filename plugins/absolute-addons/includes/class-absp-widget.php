<?php
/**
 *
 *
 * @package AbsoluteAddons
 * @version 1.0.0
 * @since 1.0.0
 */

namespace AbsoluteAddons;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/** @define "ABSOLUTE_ADDONS_WIDGETS_PATH" "./../widgets/" */
/** @define "ABSOLUTE_ADDONS_PRO_WIDGETS_PATH" "./../../absolute-addons-pro/widgets/" */

/**
 * Class Absp_Widget
 * @package AbsoluteAddons
 */
class Absp_Widget extends Widget_Base {

	const READ_MORE_KEY = 'show_read_more';

	/**
	 * Widget Name.
	 * Prevent generate everytime get called.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Widget Base Id.
	 * Prevent generate everytime get called.
	 * @var string
	 */
	protected $base_id;

	/**
	 * Widget Style Id.
	 * Prevent generate everytime get called.
	 * @var string
	 */
	protected $style_id;

	/**
	 * Enable or disable style type.
	 * If enables this will output current style with
	 * elements base id as a value of data-widget_type
	 *
	 * this is a workaround to similar things for event
	 * handling elementor does with skin class.
	 *
	 * @var bool
	 */
	protected $use_style_type = false;

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		if ( null === $this->name ) {
			$called_class = str_replace(
				[
					'AbsoluteAddons\\Widgets\\',
					'AbsoluteAddonsPro\\Widgets\\',
				],
				'',
				get_called_class()
			);
			$called_class = str_replace( 'Absoluteaddons_Style_', '', $called_class );
			$called_class = str_replace( '_', '-', $called_class );
			$this->name   = 'absolute-' . strtolower( $called_class );
		}

		return $this->name;
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'absp-widgets' ];
	}

	/**
	 * Get Widget Base ID.
	 *
	 * @return string
	 */
	protected function get_base_id() {
		if ( null === $this->base_id ) {
			$this->base_id = str_replace( 'absolute-', '', $this->get_name() );
		}

		return $this->base_id;
	}

	public function get_custom_help_url() {
		return 'https://go.absoluteplugins.com/to/docs/absolute-addons/widgets/' . $this->get_base_id();
	}

	/**
	 * Get Widget Base ID.
	 *
	 * @return string
	 */
	protected function get_style_id() {
		if ( null === $this->style_id ) {
			$this->style_id = str_replace( [ '-' ], '_', $this->get_name() );
		}

		return $this->style_id;
	}

	protected function get_prefixed_hook( $slug ) {
		return sprintf(
			'absp/widgets/%1$s/%2$s',
			$this->get_base_id(),
			rtrim( ltrim( $slug, '/' ), '/' )
		);
	}

	/**
	 * Start widget controls section.
	 *
	 * Used to add a new section of controls to the widget. Regular controls and
	 * skin controls.
	 *
	 * Note that when you add new controls to widgets they must be wrapped by
	 * `start_section()` and `end_section()`.
	 *
	 * This method should be used inside `register_controls()`.
	 *
	 * @param string       $id   Section ID.
	 * @param string $label Section arguments label Optional.
	 * @param array $args   Section arguments Optional.
	 *
	 * @see Widget_Base::start_controls_section
	 * @see Widget_Base::end_controls_section
	 *
	 */
	public function start_section( $id, $label = '', array $args = [] ) {

		if ( empty( $args ) ) {
			$args = [];
		}

		if ( $label ) {
			$args['label'] = $label;
		}

		$this->start_controls_section( $id, $args );
	}

	/**
	 * End controls section.
	 *
	 * Used to close an existing open controls section. When you use this method
	 * it stops adding new controls to this section.
	 *
	 * This method should be used inside `register_controls()`.
	 *
	 * @see Widget_Base::end_controls_section
	 *
	 */
	public function end_section() {
		$this->end_controls_section();
	}

	/**
	 * Add widget render attributes.
	 *
	 * Used to add attributes to the current widget wrapper HTML tag.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function add_render_attributes() {
		parent::add_render_attributes();

		if ( apply_filters( "absp/widgets/{$this->get_base_id()}/use_style_type", $this->use_style_type ) ) {
			$settings = $this->get_settings();

			$widget_type = $this->get_base_id() . '.' . ( ! empty( $settings['_skin'] ) ? $settings['_skin'] : 'default' );
			$settings    = $this->get_settings_for_display();
			$style_id    = $this->get_style_id();
			if ( isset( $settings[ $style_id ] ) ) {
				$widget_type = $this->get_base_id() . '.' . ( ( 'one' === $settings[ $style_id ] || empty( $settings[ $style_id ] ) ) ? 'default' : $settings[ $style_id ] );
			}

			$this->add_render_attribute( '_wrapper', 'data-widget_type', $widget_type, true );
		}
	}

	/**
	 * Add link render attributes.
	 *
	 * Used to add link tag attributes to a specific HTML element.
	 *
	 * The HTML link tag is represented by the element parameter. The `url_control` parameter
	 * needs to be an array of link settings in the same format they are set by Elementor's URL control.
	 *
	 * Example usage:
	 *
	 * `$this->add_link_attributes( 'button', $settings['link'] );`
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array|string $element   The HTML element.
	 * @param array $url_control      Array of link settings.
	 * @param bool $overwrite         Optional. Whether to overwrite existing
	 *                                attribute. Default is false, not to overwrite.
	 *
	 * @return Element_Base Current instance of the element.
	 */
	public function add_link_attributes( $element, array $url_control, $overwrite = false ) {
		$attributes = [];

		if ( ! empty( $url_control['url'] ) ) {
			$allowed_protocols = array_merge( wp_allowed_protocols(), [ 'skype', 'viber' ] );

			$attributes['href'] = esc_url( $url_control['url'], $allowed_protocols );
		}

		if ( ! empty( $url_control['is_external'] ) ) {
			$attributes['target'] = '_blank';
		}

		if ( ! empty( $url_control['nofollow'] ) ) {
			$attributes['rel'] = 'nofollow';
		}

		if ( ! empty( $url_control['custom_attributes'] ) ) {
			// Custom URL attributes should come as a string of comma-delimited key|value pairs
			$attributes = array_merge( $attributes, Utils::parse_custom_attributes( $url_control['custom_attributes'] ) );
		}

		if ( $attributes ) {
			// Elementor core missed the value argument, passing overwrite args to it.
			// This make issue while working inside nested loop.
			$this->add_render_attribute( $element, $attributes, null, $overwrite );
		}

		return $this;
	}

	/**
	 * Add render attribute.
	 *
	 * Alias for add_render_attribute
	 *
	 * @param array|string $element The HTML element.
	 * @param array|string $key Optional. Attribute key. Default is null.
	 * @param array|string $value Optional. Attribute value. Default is null.
	 * @param bool $overwrite Optional. Whether to overwrite existing
	 *                                attribute. Default is false, not to overwrite.
	 *
	 * @return Element_Base Current instance of the element.
	 * @see Element_Base::add_render_attribute
	 *
	 *
	 */
	public function add_attribute( $element, $key = null, $value = null, $overwrite = false ) {
		return $this->add_render_attribute( $element, $key, $value, $overwrite );
	}

	public function get_attributes( $element = '', $key = '' ) {
		return parent::get_render_attributes( $element, $key );
	}

	public function get_attribute_string( $element ) {
		return parent::get_render_attribute_string( $element );
	}

	/**
	 * @param string $element
	 */
	public function print_attribute( $element ) {
		parent::print_render_attribute_string( $element );
	}

	protected function init_pro_alert( $styles = [] ) {
		if ( is_string( $styles ) ) {
			$styles = [ $styles ];
		}
		if ( ! absp_has_pro() && ! empty( $styles ) ) {
			$this->add_control(
				'absp_pro_alert_' . $this->get_style_id(),
				[
					'label'     => esc_html__( 'Only available in pro version!', 'absolute-addons' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						$this->get_style_id() => $styles,
					],
				]
			);
		}
	}

	protected function plugin_dependency_alert( $dependencies ) {
		if ( isset( $dependencies['plugins'] ) ) {
			$dependencies = [ $dependencies ];
		}
		foreach ( $dependencies as $dependency ) {
			if ( empty( $dependency['plugins'] ) ) {
				continue;
			}

			$messages = [];

			foreach ( $dependency['plugins'] as $plugin ) {
				$plugin = wp_parse_args( $plugin, [
					'path' => '',
					'name' => '',
					'slug' => '',
				] );

				if ( empty( $plugin['path'] ) || empty( $plugin['name'] ) || empty( $plugin['slug'] ) ) {
					continue;
				}

				if ( absp_is_plugin_active( $plugin['path'] ) ) {
					continue;
				}

				/** @noinspection HtmlUnknownTarget */
				$messages[] = sprintf(
				/* translators: 1. Required Plugin Name. 2. Plugin installation/activation URL */
					__( '<strong>%1$s</strong> is not installed/activated on your site. Click <a href="%2$s" target="_blank" rel="noopener noreferrer">here</a> to install/activate <strong>%1$s</strong> first.', 'absolute-addons' ),
					$plugin['name'],
					esc_url( absp_plugin_install_url( $plugin['slug'] ) )
				);
			}

			if ( ! empty( $messages ) ) {
				$args = [
					'raw'       => '<p>' . implode( '</p><br><p>', $messages ) . '</p>',
					'type'      => Controls_Manager::RAW_HTML,
					'separator' => 'before',
				];

				if ( isset( $dependency['conditions'] ) && ! empty( $dependency['conditions'] ) ) {
					$control_id         = md5( wp_json_encode( $dependency['conditions'] ) );
					$args['conditions'] = $dependency['conditions'];
				} elseif ( isset( $dependency['condition'] ) && ! empty( $dependency['condition'] ) ) {
					$control_id        = md5( wp_json_encode( $dependency['condition'] ) );
					$args['condition'] = $dependency['condition'];
				} else {
					$control_id = md5( wp_json_encode( $dependency['plugins'] ) );
				}

				$control_id = 'absp_dependency_alert_' . $this->get_style_id() . '_' . $control_id;

				$this->add_control( $control_id, $args );
			}
		}
	}

	protected function render_controller( $slug = '', $args = [] ) {

		$controllers = [];
		if ( absp_has_pro() ) {
			$controllers = array_merge( $controllers, $this->list_controller( $slug, ABSOLUTE_ADDONS_PRO_WIDGETS_PATH ) );
		}

		// Base template has lower priority over Pro template, so it can be loaded if exists.
		$controllers = array_merge( $controllers, $this->list_controller( $slug ) );

		$found_controller = $this->locate_file( $controllers );

		// phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_error_log
		if ( ! $found_controller && Plugin::is_dev() ) {
			error_log(
				sprintf( '%s trying to load a controller (%s) that is not exists.',
					$this->get_name(),
					$slug
				)
			);
		}
		// phpcs:enable

		$this->load_file( $found_controller, $args );
	}

	protected function get_style_slug( $settings ) {

		$key  = str_replace( [ '-' ], '_', $this->get_base_id() );
		$slug = isset( $settings[ 'absolute_' . $key ] ) ? $settings[ 'absolute_' . $key ] : '';
		if ( ! $slug ) {
			$slug = isset( $settings[ $key ] ) ? $settings[ $key ] : '';
		}

		return is_string( $slug ) ? $slug : '';
	}

	protected function render_icon( $key, $default = '', $settings = [], $tag = 'i', $compatibility_key = 'absolute-addons' ) {
		if ( empty( $settings ) ) {
			$settings = $this->get_settings_for_display();
		}

		$migrated = isset( $settings['__fa4_migrated'][ $key ] );
		$is_new   = empty( $settings[ $compatibility_key ] ) && Icons_Manager::is_migration_allowed();
		$has_icon = ( ! $is_new || ! empty( $settings[ $key ]['value'] ) );

		if ( $has_icon ) {
			if ( $is_new || $migrated ) {
				Icons_Manager::render_icon( $settings[ $key ], [ 'aria-hidden' => 'true' ], $tag );
			} else {
				if ( $default ) {
					?>
					<<?php absp_tag_name( $tag ); ?> class="<?php echo esc_attr( $default ); ?>" aria-hidden="true"></<?php absp_tag_name( $tag ); ?>>
					<?php
				}
			}
		}
	}

	/**
	 * Find template to render and includes it.
	 * Prioritise Pro Template Over Free.
	 *
	 * @param string|array $slug template slug or args.
	 * @param array $args extra args array, that will be extracted.
	 *
	 * @return void
	 */
	protected function render_template( $slug = '', $args = [] ) {

		if ( is_array( $slug ) && empty( $args ) ) {
			$args = $slug;
			$slug = '';
		}

		if ( isset( $args['slug'] ) && is_string( $args['slug'] ) ) {
			$slug = $args['slug'];
		}

		$settings = $this->get_settings_for_display();
		if ( ! $slug ) {
			$slug = $this->get_style_slug( $settings );
		}

		$templates = [];
		if ( absp_has_pro() ) {
			$templates = array_merge( $templates, $this->list_templates( $slug, ABSOLUTE_ADDONS_PRO_WIDGETS_PATH ) );
		}

		// Base template has lower priority over Pro template, so it can be loaded if exists.
		$templates = array_merge( $templates, $this->list_templates( $slug ) );

		$found_template = $this->locate_file( $templates );

		// phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_print_r,WordPress.PHP.DevelopmentFunctions.error_log_error_log
		if ( ! $found_template && Plugin::is_dev() ) {
			error_log(
				sprintf( '%s trying to load a template (%s) that is not exists.',
					$this->get_name(),
					$slug
				)
			);
		}

		if ( Plugin::is_template_debug() ) {
			echo '<pre><h3>Template Debug Data:</h3>';
			echo '<code>';
			print_r( [
				'WidgetClass'   => get_called_class(),
				'BaseId'        => $this->get_base_id(),
				'TemplateSlug'  => $slug,
				'TemplateFound' => $found_template,
				'Templates'     => $templates,
			] );
			echo '</code>';
			echo '</pre>';
		}

		// phpcs:enable

		$this->load_file( $found_template, array_merge( $args, [
			'settings' => $settings,
			'slug'     => $slug,
		] ) );
	}

	/**
	 * Find content template to render.
	 *
	 * @param string $slug
	 */
	protected function render_content_template( $slug = '' ) {
		$settings = $this->get_settings_for_display();
		if ( ! $slug ) {
			$slug = $this->get_style_slug( $settings );
		}

		$templates = [];

		if ( absp_has_pro() ) {
			$templates = array_merge( $templates, $this->list_content_templates( $slug, ABSOLUTE_ADDONS_PRO_WIDGETS_PATH ) );
		}

		// Base template has lower priority over Pro template, so it can be loaded if exists.
		$templates = array_merge( $templates, $this->list_content_templates( $slug ) );

		$found_template = $this->locate_file( $templates );

		// phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_error_log
		if ( ! $found_template && Plugin::is_dev() ) {
			error_log(
				sprintf( '%s trying to load a content template (%s) that is not exists.',
					$this->get_name(),
					$slug
				)
			);
		}
		// phpcs:enable

		$this->load_file( $found_template );
	}

	/**
	 * Locate File from template array.
	 *
	 * @param string[] $files
	 *
	 * @return string|false
	 */
	private function locate_file( $files ) {
		foreach ( $files as $file ) {
			if ( file_exists( $file ) ) {
				return $file;
			}
		}

		return false;
	}

	/**
	 * Includes a file.
	 *
	 * @param $file_path
	 * @param array $args
	 * @param bool $require
	 * @param bool $once
	 *
	 * @return mixed|void
	 * @noinspection PhpReturnValueOfMethodIsNeverUsedInspection
	 * @noinspection PhpSameParameterValueInspection
	 */
	private function load_file( $file_path, $args = [], $require = false, $once = false ) {
		$file_path = realpath( $file_path );

		if ( $file_path && $this->is_plugin_file( $file_path ) && file_exists( $file_path ) ) {
			if ( is_array( $args ) && ! empty( $args ) ) {
				extract( $args );
			}

			if ( ! $require ) {
				return ! $once ? include $file_path : include_once $file_path;
			} else {
				return ! $once ? require $file_path : require_once $file_path;
			}
		}
	}

	/**
	 * Validate File path for including.
	 * Checks if file path is withing the plugin directory for dynamic inclusion.
	 *
	 * @param string $file_path
	 *
	 * @return bool
	 */
	private function is_plugin_file( $file_path ) {
		return (
			0 === strpos( $file_path, untrailingslashit( ABSOLUTE_ADDONS_PATH ) ) ||
			( defined( 'ABSOLUTE_ADDONS_PRO_PATH' ) && 0 === strpos( $file_path, untrailingslashit( ABSOLUTE_ADDONS_PRO_PATH ) ) )
		);
	}

	/**
	 * Get Template HTML.
	 *
	 * @param string $slug template slug.
	 * @param array $args extra args array, that will be extracted.
	 *
	 * @return false|string
	 */
	protected function get_template_html( $slug = '', $args = [], $content_template = false ) {
		ob_start();

		// Render the template.
		if ( ! $content_template ) {
			$this->render_template( $slug, $args );
		} else {
			$this->render_content_template( $slug );
		}

		return ob_get_clean();
	}

	/**
	 * Prepare Template Path with fallback list to render
	 *
	 * @param string $slug
	 * @param string $path
	 *
	 * @return array
	 */
	protected function list_templates( $slug, $path = ABSOLUTE_ADDONS_WIDGETS_PATH ) {
		return [
			// @XXX if we introduce template override from the theme, then it would be added on the top of main template.
			sprintf(
				$path . '%1$s/template/%2$s.php',
				$this->get_base_id(),
				$slug
			),
			sprintf(
				$path . '%1$s/template/template-%1$s-item-%2$s.php',
				$this->get_base_id(),
				$slug
			),
			// Fallbacks.
			sprintf(
				$path . '%1$s/template-%1$s-item-%2$s.php',
				$this->get_base_id(),
				$slug
			),
			sprintf(
				$path . '%1$s/template/template-%2$s.php',
				$this->get_base_id(),
				$slug
			),
		];
	}

	protected function list_controller( $slug = '', $path = ABSOLUTE_ADDONS_WIDGETS_PATH ) {
		$controller = [
			sprintf(
				$path . '%1$s/controller/%2$s.php',
				$this->get_base_id(),
				$slug
			),
			sprintf(
				$path . '%1$s/controller/template-%1$s-item-%2$s-controller.php',
				$this->get_base_id(),
				$slug
			),
			// Fallbacks.
			sprintf(
				$path . '%1$s/template-%1$s-item-%2$s-controller.php',
				$this->get_base_id(),
				$slug
			),
			sprintf(
				$path . '%1$s/controller/controller-%2$s.php',
				$this->get_base_id(),
				$slug
			),
		];
		if ( '' === $slug ) {
			$controller[] = sprintf(
				$path . '%1$s/controller.php', // same as previous but no template directory.
				$this->get_base_id()
			);
			$controller[] = sprintf(
				$path . '%1$s/controller/controller-%1$s-item.php', // it's a singular template widget
				$this->get_base_id()
			);
		}

		return $controller;
	}

	/**
	 * Prepare Content Template Path with fallback list to render
	 *
	 * @param string $slug
	 * @param string $path
	 *
	 * @return array
	 */
	protected function list_content_templates( $slug, $path = ABSOLUTE_ADDONS_WIDGETS_PATH ) {
		return [
			// @XXX if we introduce template override from the theme, then it would be added on the top of main template.
			sprintf(
				$path . '%1$s/content_template/%2$s.php',
				$this->get_base_id(),
				$slug
			),
			sprintf(
				$path . '%1$s/content_template/content-%1$s-item-%2$s.php',
				$this->get_base_id(),
				$slug
			),
			// Fallbacks.
			sprintf(
				$path . '%1$s/content_template/content-%2$s.php',
				$this->get_base_id(),
				$slug
			),
			sprintf(
				$path . '%1$s/content_template/content-%1$s-item-one.php', // maybe dev forgets to add the file.
				$this->get_base_id()
			),
			sprintf(
				$path . '%1$s/content_template/content-%1$s-item.php', // it's a singular template widget
				$this->get_base_id()
			),
			sprintf(
				$path . '%1$s/content.php', // same as previous but no template directory.
				$this->get_base_id()
			),
		];
	}
}

// End of file class-base-widget.php.
