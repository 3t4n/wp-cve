<?php
/**
 * @author CodeFlavors
 * @project Vimeotheque 2.0 Lite
 */

namespace Vimeotheque\Admin\Customizer;

use Vimeotheque\Plugin;
use WP_Customize_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Customizer
 * @package Vimeotheque\Admin\Customizer
 * @ignore
 */
class Customizer {
	/**
	 * @var WP_Customize_Manager
	 */
	private $wp_customizer;
	/**
	 * @var \WP_Customize_Panel
	 */
	private $panel;
	/**
	 * @var \WP_Customize_Section[]
	 */
	private $sections;

	/**
	 * Customizer constructor.
	 */
	public function __construct() {

		add_action(
			'customize_register',
			[$this, 'init'],
			10
		);

	}

	/**
	 * @param WP_Customize_Manager $customizer
	 */
	public function init( WP_Customize_Manager $customizer ){
		$this->wp_customizer = $customizer;

		$this->add_panel();
		$this->add_sections();
	}

	/**
	 * "customize_register" action callback
	 */
	private function add_panel(){
		$this->panel = $this->get_wp_customizer()->add_panel(
			'vimeotheque-plugin',
			[
				'priority' => 200,
				'title' => 'Vimeotheque',
				'description' => __( 'Customize Vimeotheque video embeds and plugin options', 'codeflavors-vimeo-video-post-lite' )
			]
		);
	}

	/**
	 * Add sections to panel
	 */
	private function add_sections(){
		$section = $this->add_section(
			'post_options',
			__( 'Video post', 'codeflavors-vimeo-video-post-lite' ),
			__( 'Control how video posts are used in your website', 'codeflavors-vimeo-video-post-lite' )
		);
		$this->add_post_options( $section );
	}

	/**
	 * @param \WP_Customize_Section $section
	 */
	private function add_post_options( \WP_Customize_Section $section ){
		$options = [
			'archives' => __( 'Embed videos in archive pages', 'codeflavors-vimeo-video-post-lite' )
		];

		foreach( $options as $key => $label ){
			$setting = $this->add_setting(
				sprintf(
					'%s[%s]',
					Plugin::instance()->get_options_obj()->get_option_name(),
					$key
				),
				[
					'type' => 'option',
					'capability' => 'manage_options',
					'transport' => 'refresh'
				]
			);

			$this->add_control(
				'vmtq-post-' . $key,
				$section,
				$setting,
				[
					'type' => 'checkbox',
					'label' => $label
				]
			);
		}
	}

	/**
	 * @see \WP_Customize_Section::__construct() documentation for all arguments
	 *
	 * @param $section_id
	 * @param $title
	 * @param $description
	 *
	 * @return mixed
	 */
	public function add_section( $section_id, $title, $description ){
		if( !isset( $this->sections[ $section_id ] ) ) {
			$this->sections[ $section_id ] = $this->get_wp_customizer()->add_section(
				$section_id,
				[
					//'priority' => 10,
					'panel'       => $this->panel->id,
					//'capability' => 'manage_options',
					//'theme_supports' => false,
					'title'       => $title,
					'description' => $description
				]
			);
		}

		return $this->sections[ $section_id ];
	}

	/**
	 * @see WP_Customize_Setting::__construct() documentation for a complete list of arguments
	 *
	 * @param $id
	 * @param $args
	 *
	 * @return mixed
	 *
	 */
	public function add_setting( $id, array $args ){
		return $this->settings[ $id ] = $this->wp_customizer->add_setting(
			$id,
			$args
		);
	}

	/**
	 * @param $id
	 * @param \WP_Customize_Section $section
	 * @param \WP_Customize_Setting $setting
	 * @param array $args
	 */
	public function add_control( $id, \WP_Customize_Section $section, \WP_Customize_Setting $setting, array $args ){
		$args['settings'] = $setting->id;
		$args['section'] = $section->id;

		return $this->controls[ $id ] = $this->wp_customizer->add_control(
			$id,
			$args
		);
	}

	/**
	 * @param $id
	 *
	 * @return \WP_Customize_Section
	 * @throws \Exception
	 */
	public function get_section( $id ){
		if( isset( $this->sections[ $id ] ) ){
			return $this->sections[ $id ];
		}else{
			throw new \Exception( sprintf( 'Section ID %s could not be identified.', $id ) );
		}
	}

	/**
	 * @return WP_Customize_Manager
	 */
	public function get_wp_customizer() {
		return $this->wp_customizer;
	}

	/**
	 * @return \WP_Customize_Panel
	 */
	public function get_panel() {
		return $this->panel;
	}

	/**
	 * @return \WP_Customize_Section[]
	 */
	public function get_sections() {
		return $this->sections;
	}

}