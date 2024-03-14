<?php
namespace LaStudioKitThemeBuilder\Modules\EdynamicTags\Tags;

use Elementor\Core\DynamicTags\Data_Tag as Base_Tag;

use Elementor\Controls_Manager;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Archive_Image extends Base_Tag {

	public function get_name() {
		return 'archive-image';
	}

	public function get_title() {
		return esc_html__( 'Archive Image', 'lastudio-kit' );
	}

	public function get_group() {
		return 'archive';
	}

	public function get_categories() {
		return [ 'image' ];
	}

	public function get_value( array $options = [] ) {

		$thumbnail_id = get_term_meta( get_queried_object_id(), '_thumbnail_id', true );
		$thumbnail_id_fallback = get_term_meta( get_queried_object_id(), 'thumbnail_id', true );

		if ( $thumbnail_id ) {
			$image_data = [
				'id' => $thumbnail_id,
				'url' => wp_get_attachment_image_src( $thumbnail_id, 'full' )[0],
			];
		} elseif ($thumbnail_id_fallback){
            $image_data = [
                'id' => $thumbnail_id_fallback,
                'url' => wp_get_attachment_image_src( $thumbnail_id_fallback, 'full' )[0],
            ];
        }
        else {
			$image_data = $this->get_settings( 'fallback' );
		}

		return $image_data;
	}

	protected function register_controls() {
		$this->add_control(
			'fallback',
			[
				'label' => esc_html__( 'Fallback', 'lastudio-kit' ),
				'type' => Controls_Manager::MEDIA,
			]
		);
	}
}
