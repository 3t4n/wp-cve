<?php
namespace LaStudioKitThemeBuilder\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use LaStudioKitThemeBuilder\Modules\DynamicTags\Tags\Base\Tag;
use LaStudioKitThemeBuilder\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Date extends Tag {
	public function get_name() {
		return 'post-date';
	}

	public function get_title() {
		return esc_html__( 'Post Date', 'lastudio-kit' );
	}

	public function get_group() {
		return Module::POST_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	protected function register_controls() {
		$this->add_control(
			'type',
			[
				'label' => esc_html__( 'Type', 'lastudio-kit' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'post_date_gmt' => esc_html__( 'Post Published', 'lastudio-kit' ),
					'post_modified_gmt' => esc_html__( 'Post Modified', 'lastudio-kit' ),
				],
				'default' => 'post_date_gmt',
			]
		);

		$this->add_control(
			'format',
			[
				'label' => esc_html__( 'Format', 'lastudio-kit' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'lastudio-kit' ),
					'F j, Y' => gmdate( 'F j, Y' ),
					'Y-m-d' => gmdate( 'Y-m-d' ),
					'm/d/Y' => gmdate( 'm/d/Y' ),
					'd/m/Y' => gmdate( 'd/m/Y' ),
					'human' => esc_html__( 'Human Readable', 'lastudio-kit' ),
					'custom' => esc_html__( 'Custom', 'lastudio-kit' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'custom_format',
			[
				'label' => esc_html__( 'Custom Format', 'lastudio-kit' ),
				'default' => '',
				'description' => sprintf( '<a href="https://go.elementor.com/wordpress-date-time/" target="_blank">%s</a>', esc_html__( 'Documentation on date and time formatting', 'lastudio-kit' ) ),
				'condition' => [
					'format' => 'custom',
				],
			]
		);
	}

	public function render() {
		$date_type = $this->get_settings( 'type' );
		$format = $this->get_settings( 'format' );

		if ( 'human' === $format ) {
			/* translators: %s: Human readable date/time. */
			$value = sprintf( esc_html__( '%s ago', 'lastudio-kit' ), human_time_diff( strtotime( get_post()->{$date_type} ) ) );
		} else {
			switch ( $format ) {
				case 'default':
					$date_format = '';
					break;
				case 'custom':
					$date_format = $this->get_settings( 'custom_format' );
					break;
				default:
					$date_format = $format;
					break;
			}

			if ( 'post_date_gmt' === $date_type ) {
				$value = get_the_date( $date_format );
			} else {
				$value = get_the_modified_date( $date_format );
			}
		}
		echo wp_kses_post( $value );
	}
}
