<?php
namespace EazyGrid\Elementor\Base;

use Elementor\Widget_Base;

defined( 'ABSPATH' ) || die();

abstract class EazyGrid_Base extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		/**
		 * Automatically generate widget name from class
		 *
		 * Card will be card
		 * Blog_Card will be blog-card
		 */
		$name = str_replace( strtolower( 'EazyGrid\Elementor\Widgets' ), '', strtolower( $this->get_class_name() ) );
		$name = str_replace( '_', '-', $name );
		$name = ltrim( $name, '\\' );
		return 'eazy-' . $name;
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'ezicon ezicon-eazygrid';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'eazygrid' ];
	}

	/**
	 * Override from addon to add custom wrapper class.
	 *
	 * @return string
	 */
	protected function get_custom_wrapper_class() {
		return '';
	}

	/**
	 * Overriding default function to add custom html class.
	 *
	 * @return string
	 */
	public function get_html_wrapper_class() {
		$html_class  = parent::get_html_wrapper_class();
		$html_class .= ' eazy-grid-elementor';
		$html_class .= ' ' . $this->get_name();
		$html_class .= ' ' . $this->get_custom_wrapper_class();
		return rtrim( $html_class );
	}

	/**
	 * Register widget controls
	 */
	protected function register_controls() {

		do_action( 'eazygridElementor/start/register/controls', $this );

		$this->register_content_controls();

		$this->register_style_controls();

		do_action( 'eazygridElementor/end/register/controls', $this );
	}

	/**
	 * Register content controls
	 *
	 * @return void
	 */
	abstract protected function register_content_controls();

	/**
	 * Register style controls
	 *
	 * @return void
	 */
	abstract protected function register_style_controls();

}
