<?php

namespace Whodunit\MywpCustomPatterns\Admin;


use Whodunit\MywpCustomPatterns\Init\Core;


class Patterns {
	protected $core;

	public function __construct( Core $Core ) {
		$this->core = $Core;

		add_action( 'init', array( $this, 'register_pattern' ) );
	}

	private function set_pattern_category( $slug, $name ) {
		register_block_pattern_category(
			$slug,
			array( 'label' => $name )
		);
	}

	public function register_pattern() {
		if ( ! class_exists( 'WP_Block_Patterns_Registry' ) ) {
			return;
		}

		$mywp_categories = $this->core->get_categories();
		$default_cat     = esc_html__( 'Custom Patterns', 'mywp-custom-patterns' );
		$this->set_pattern_category( 'mywp_pattern_default', $default_cat );

		if ( count( $mywp_categories ) > 0 ) {
			foreach ( $mywp_categories as $mywp_category ) {
				$this->set_pattern_category( 'mywp_pattern_tax_' . $mywp_category->term_id, $mywp_category->name );
			}
		}
		$mywp_templates = $this->core->get_templates();

		if ( count( $mywp_templates ) > 0 ) {
			foreach ( $mywp_templates as $item_template ) {
				if ( '' !== $item_template->post_content ) {
					$pattern_cats = array();
					$terms_list   = get_the_terms( $item_template->ID, $this->core->name_cat );
					if ( isset( $terms_list ) && is_array( $terms_list ) && count( $terms_list ) > 0 ) {
						foreach ( $terms_list as $term_item ) {
							$pattern_cats[] = 'mywp_pattern_tax_' . $term_item->term_id;
						}
					} else {
						$pattern_cats[] = 'mywp_pattern_default';
					}

					register_block_pattern(
						'mywp-custom-patterns/pattern_' . $item_template->ID,
						array(
							'title'       => $item_template->post_title,
							'description' => '',
							'categories'  => $pattern_cats,
							'content'     => $item_template->post_content,
						)
					);
				}
			}
		}
	}
}
