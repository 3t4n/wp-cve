<?php
namespace Vimeotheque\Blocks;

use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 * @ignore
 */
class Video extends Block_Abstract implements Block_Interface {

	public function __construct( Plugin $plugin ) {
		parent::__construct( $plugin );
		// register block script
		parent::register_script( 'vimeotheque-video-block', 'video' );

		parent::register_block_type(
			'vimeotheque/video',
			[
				'attributes' => [
					'id' => [
						'type' => 'number',
						'default' => 0
					],
					'volume' => [
						'type' => 'string',
						'default' => '70'
					],
					'video_align' => [
						'type' => 'string',
						'default' => 'align-left'
					],
					'width' => [
						'type' => 'string',
						'default' => '900'
					],
					'aspect_ratio' => [
						'type' => 'string',
						'default' => '16x9'
					],
					'loop' => [
						'type' => 'boolean',
						'default' => true
					],
					'autoplay' => [
						'type' => 'boolean',
						'default' => true
					],
					'post' => [
						'type' => 'array',
						'default' => [],
						'items' => [
							'type' => 'number'
						]
					],
				],
				'editor_script' => parent::get_script_handle(),
				'editor_style' => '',
				'render_callback' => function( $attr ){
					$video = new \Vimeotheque\Shortcode\Video();
					return $video->get_output( $attr, '' );
				}
			]
		);
	}
}