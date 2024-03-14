<?php
namespace Thim_EL_Kit\Modules\SinglePost;

use Thim_EL_Kit\Modules\Modules;
use Thim_EL_Kit\SingletonTrait;
use Thim_EL_Kit\Custom_Post_Type;

class Init extends Modules {
	use SingletonTrait;

	public function __construct() {
		$this->tab      = 'single-post';
		$this->tab_name = esc_html__( 'Single Post', 'thim-elementor-kit' );

		parent::__construct();
	}

	public function template_include( $template ) {
		$this->template_include = is_singular( 'post' );

		return parent::template_include( $template );
	}

	public function get_preview_id() {
		global $post;

		$output = false;

		if ( $post ) {
			$document = \Elementor\Plugin::$instance->documents->get( $post->ID );

			if ( $document ) {
				$preview_id = $document->get_settings( 'thim_ekits_preview_id' );

				$output = ! empty( $preview_id ) ? absint( $preview_id ) : false;
			}
		}

		return $output;
	}

	public function before_preview_query() {
		$preview_id = $this->get_preview_id();

		if ( $preview_id ) {
			$query = array(
				'p'         => absint( $preview_id ),
				'post_type' => 'post',
			);
		} else {
			$query_vars = array(
				'post_type'      => 'post',
				'posts_per_page' => 1,
			);

			$posts = get_posts( $query_vars );

			if ( ! empty( $posts ) ) {
				$query = array(
					'p'         => $posts[0]->ID,
					'post_type' => 'post',
				);
			}
		}

		if ( ! empty( $query ) ) {
			\Elementor\Plugin::instance()->db->switch_to_query( $query, true );
		}
	}

	public function is( $condition ) {
		switch ( $condition['type'] ) {
			case 'all':
				return is_single() && 'post' === get_post_type();
			case 'post_category':
				return is_single() && has_category( $condition['query'] );
			case 'post_tag':
				return is_single() && has_tag( $condition['query'] );
			case 'select_post':
				return is_single() && get_the_ID() === (int) $condition['query'];
		}
	}

	public function priority( $type ) {
		$priority = 100;

		switch ( $type ) {
			case 'all':
				$priority = 10;
				break;
			case 'post_category':
			case 'post_tag':
				$priority = 20;
				break;
			case 'select_post':
				$priority = 30;
				break;
		}

		return apply_filters( 'thim_ekit/condition/priority', $priority, $type );
	}

	public function get_conditions() {
		return array(
			array(
				'label'    => esc_html__( 'All posts', 'thim-elementor-kit' ),
				'value'    => 'all',
				'is_query' => false,
			),
			array(
				'label'    => esc_html__( 'Select post', 'thim-elementor-kit' ),
				'value'    => 'select_post',
				'is_query' => true,
			),
			array(
				'label'    => esc_html__( 'Select category', 'thim-elementor-kit' ),
				'value'    => 'post_category',
				'is_query' => true,
			),
			array(
				'label'    => esc_html__( 'Select tag', 'thim-elementor-kit' ),
				'value'    => 'post_tag',
				'is_query' => true,
			),
		);
	}
}

Init::instance();
