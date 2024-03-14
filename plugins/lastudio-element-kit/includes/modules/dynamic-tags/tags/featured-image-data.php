<?php
namespace LaStudioKitThemeBuilder\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use LaStudioKitThemeBuilder\Modules\DynamicTags\Tags\Base\Tag;
use LaStudioKitThemeBuilder\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Featured_Image_Data extends Tag {

	public function get_name() {
		return 'featured-image-data';
	}

	public function get_group() {
		return Module::MEDIA_GROUP;
	}

	public function get_categories() {
		return [
			Module::TEXT_CATEGORY,
			Module::URL_CATEGORY,
			Module::POST_META_CATEGORY,
		];
	}

	public function get_title() {
		return esc_html__( 'Featured Image Data', 'lastudio-kit' );
	}

	private function get_attacment() {
		$id = get_post_thumbnail_id();

		if ( ! $id ) {
			return false;
		}

		return get_post( $id );
	}

	public function render() {
		$settings = $this->get_settings();
		$attachment = $this->get_attacment();

		if ( ! $attachment ) {
			return;
		}

		$value = '';

		switch ( $settings['attachment_data'] ) {
			case 'alt':
				$value = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
				break;
			case 'caption':
				$value = $attachment->post_excerpt;
				break;
			case 'description':
				$value = $attachment->post_content;
				break;
			case 'href':
				$value = get_permalink( $attachment->ID );
				break;
			case 'src':
				$value = $attachment->guid;
				break;
			case 'title':
				$value = $attachment->post_title;
				break;
		}

		echo wp_kses_post( $value );
	}

	protected function register_controls() {

		$this->add_control(
			'attachment_data',
			[
				'label' => esc_html__( 'Data', 'lastudio-kit' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => [
					'title' => esc_html__( 'Title', 'lastudio-kit' ),
					'alt' => esc_html__( 'Alt', 'lastudio-kit' ),
					'caption' => esc_html__( 'Caption', 'lastudio-kit' ),
					'description' => esc_html__( 'Description', 'lastudio-kit' ),
					'src' => esc_html__( 'File URL', 'lastudio-kit' ),
					'href' => esc_html__( 'Attachment URL', 'lastudio-kit' ),
				],
			]
		);
	}
}
