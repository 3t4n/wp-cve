<?php
namespace Thim_EL_Kit\Elementor\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Data_Tag as Data_Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

defined( 'ABSPATH' ) || exit;

class Item_Featured_Image extends Data_Tag {

	public function get_name() {
		return 'thim-item-featured-image';
	}

	public function get_categories() {
		return array( TagsModule::IMAGE_CATEGORY );
	}

	public function get_group() {
		return array( 'thim-ekit' );
	}

	public function get_title() {
		return 'Featured Image';
	}

	public function get_value( array $options = array() ) {
		$thumbnail_id = get_post_thumbnail_id();

		if ( $thumbnail_id ) {
			$image_data = array(
				'id'  => $thumbnail_id,
				'url' => wp_get_attachment_image_src( $thumbnail_id, 'full' )[0],
			);
		} else {
			$image_data = $this->get_settings( 'fallback' );
		}

		return $image_data;
	}

	protected function register_controls() {
		$this->add_control(
			'fallback',
			array(
				'label' => esc_html__( 'Fallback', 'thim-elementor-kit' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);
	}
}
