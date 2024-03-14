<?php
namespace Auxin\Plugin\Portfolio\Elementor\DynamicTags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Auxin_Portfolios_Url extends Tag {

	public function get_name() {
		return 'aux-portfolios-url';
	}

	public function get_title() {
		return __( 'Portfolios URL', 'auxin-portfolio' );
	}

	public function get_group() {
		return 'URL';
	}

	public function get_categories() {
		return [
			TagsModule::URL_CATEGORY
		];
    }

    public function get_posts_list() {

		$items = [
            '' => __( 'Select...', 'auxin-portfolio' ),
        ];
        $posts = get_posts( array(
            'post_type'   =>'portfolio',
            'numberposts' => -1
		) );

        foreach ( $posts as $post ) {
            $items[ $post->ID ] = $post->post_title;
        }

        return $items;
    }

	public function is_settings_required() {
		return true;
	}

	protected function register_controls() {
		$this->add_control(
			'key',
			[
				'label'   => __( 'Portfolios URL', 'auxin-portfolio' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_posts_list(),
				'default' => ''
            ]
        );
	}

	protected function get_post_url() {
		if( $key = $this->get_settings( 'key' ) ){
			return get_permalink( $key );
		}

		return '';
	}

	public function get_value() {
		return $this->get_post_url();
	}

	public function render() {
		echo $this->get_post_url();
	}

}
