<?php
namespace Thim_EL_Kit\Modules\ArchivePost;

use Thim_EL_Kit\Modules\Modules;
use Thim_EL_Kit\Custom_Post_Type;
use Thim_EL_Kit\SingletonTrait;

class Init extends Modules {
	use SingletonTrait;

	public function __construct() {
		$this->tab      = 'archive-post';
		$this->tab_name = esc_html__( 'Archive Post', 'thim-elementor-kit' );

		parent::__construct();

		add_filter( 'thim_ekit/elementor/archive_post/query_posts/query_vars', array( $this, 'query_args' ) );
	}

	public function template_include( $template ) {
		$this->template_include = ( is_archive() || is_search() || is_author() || is_category() || is_home() || is_tag() ) && 'post' == get_post_type();

		return parent::template_include( $template );
	}

	public function query_args( $query_args ) {
		$id   = get_the_ID();
		$type = get_post_meta( $id, Custom_Post_Type::TYPE, true );

		if ( $id && $type && $type === $this->tab && ( $this->is_editor_preview() || $this->is_modules_view() ) ) {
			$query_args = array(
				'post_type' => 'post',
			);
		}

		return $query_args;
	}

	public function is( $condition ) {
		switch ( $condition['type'] ) {
			case 'all':
				return ( is_archive() || is_category() || is_tag() || is_author() || is_search() || is_home() ) && 'post' === get_post_type();
			case 'post_categories':
				return is_category();
			case 'post_tags':
				return is_tag();
			case 'post_term':
				$object      = get_queried_object();
				$taxonomy_id = is_object( $object ) && property_exists( $object, 'term_id' ) ? $object->term_id : false;
				return (int) $taxonomy_id === (int) $condition['query'] && ! is_search();
			case 'post_search':
				return is_search() && 'post' === get_query_var( 'post_type' );
			case 'post_page':
				return is_home();
			case 'post_author':
				return is_author();
			case 'select_post_author':
				// is post author and author id is equal to condition query
				return is_author() && get_the_author_meta( 'ID' ) === (int) $condition['query'];
		}
	}

	public function priority( $type ) {
		$priority = 100;

		switch ( $type ) {
			case 'all':
				$priority = 10;
				break;
			case 'post_page':
				$priority = 20;
				break;
			case 'post_categories':
			case 'post_tags':
				$priority = 30;
				break;
			case 'post_search':
				$priority = 40;
				break;
			case 'post_term':
				$priority = 50;
				break;
			case 'post_author':
				$priority = 60;
				break;
			case 'select_post_author':
				$priority = 70;
				break;
		}

		return apply_filters( 'thim_ekit/condition/priority', $priority, $type );
	}

	public function get_conditions() {
		return array(
			array(
				'label'    => esc_html__( 'All post archives', 'thim-elementor-kit' ),
				'value'    => 'all',
				'is_query' => false,
			),
			array(
				'label'    => esc_html__( 'Post page', 'thim-elementor-kit' ),
				'value'    => 'post_page',
				'is_query' => false,
			),
			array(
				'label'    => esc_html__( 'All categories', 'thim-elementor-kit' ),
				'value'    => 'post_categories',
				'is_query' => false,
			),
			array(
				'label'    => esc_html__( 'All tags', 'thim-elementor-kit' ),
				'value'    => 'post_tags',
				'is_query' => false,
			),
			array(
				'label'    => esc_html__( 'All authors', 'thim-elementor-kit' ),
				'value'    => 'post_author',
				'is_query' => false,
			),
			array(
				'label'    => esc_html__( 'Search page', 'thim-elementor-kit' ),
				'value'    => 'post_search',
				'is_query' => false,
			),
			array(
				'label'    => esc_html__( 'Select term', 'thim-elementor-kit' ),
				'value'    => 'post_term',
				'is_query' => true,
			),
			array(
				'label'    => esc_html__( 'Select author', 'thim-elementor-kit' ),
				'value'    => 'select_post_author',
				'is_query' => true,
			),
		);
	}
}

Init::instance();
