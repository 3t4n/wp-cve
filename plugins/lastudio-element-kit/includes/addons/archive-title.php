<?php

/**
 * Class: LaStudioKit_Archive_Title
 * Name: Archive Title
 * Slug: lakit-archive-title
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}


/**
 * Post Title Widget
 */
class LaStudioKit_Archive_Title extends Widget_Heading {

    public function get_name() {
        return 'lakit-archive-title';
    }

	public function get_title() {
		return esc_html__( 'LaStudioKit Archive Title', 'lastudio-kit' );
	}

    public function get_icon() {
        return 'eicon-archive-title';
    }

    public function get_keywords() {
        return [ 'title', 'heading', 'archive' ];
    }

    public function get_categories() {
        return [ 'lastudiokit-builder' ];
    }

	protected function register_controls() {
		parent::register_controls();

		$dynamic_tag_name = 'archive-title';

		$this->update_control(
			'title',
			[
				'dynamic' => [
					'default' => lastudio_kit()->elementor()->dynamic_tags->tag_data_to_tag_text( null, $dynamic_tag_name ),
				],
			],
			[
				'recursive' => true,
			]
		);

		$this->update_control(
			'header_size',
			[
				'default' => 'h1',
			]
		);
	}

}