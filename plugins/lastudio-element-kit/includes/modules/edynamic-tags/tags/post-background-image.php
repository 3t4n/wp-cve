<?php
namespace LaStudioKitThemeBuilder\Modules\EdynamicTags\Tags;

use Elementor\Core\DynamicTags\Data_Tag as Base_Tag;

use Elementor\Controls_Manager;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Background_Image extends Base_Tag {

	public function get_name() {
		return 'post-background-image';
	}

	public function get_title() {
		return esc_html__( 'Post Background Image', 'lastudio-kit' );
	}

	public function get_group() {
		return 'post';
	}

	public function get_categories() {
		return [ 'image' ];
	}

	public function get_value( array $options = [] ) {

        $key = '_la_bg';
        if( $this->get_settings('use_custom_key') === 'yes' ){
            $_key = $this->get_settings( 'custom_key' );
            if(!empty($_key)){
                $key = $_key;
            }
        }

		$thumbnail_id = get_post_meta( get_queried_object_id(), $key, true );

		if ( $thumbnail_id ) {
			$image_data = [
				'id' => $thumbnail_id,
				'url' => wp_get_attachment_image_url( $thumbnail_id, 'full' )[0],
			];
		}
        else {
			$image_data = $this->get_settings( 'fallback' );
		}

		return $image_data;
	}

	protected function register_controls() {
        $this->add_control(
            'use_custom_key',
            [
                'label' => esc_html__( 'Use custom?', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes'
            ]
        );
        $this->add_control(
            'custom_key',
            [
                'label' => esc_html__( 'Custom Key', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'key',
                'condition' => [
                    'use_custom_key' => 'yes'
                ]
            ]
        );
		$this->add_control(
			'fallback',
			[
				'label' => esc_html__( 'Fallback', 'lastudio-kit' ),
				'type' => Controls_Manager::MEDIA,
			]
		);
	}
}
