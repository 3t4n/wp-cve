<?php

class advExcLoc {
	function __construct( $options, $content, $width, $justify ) {
		$this->options = $options;
		$this->content = $content;
		$this->width   = $width;
		$this->justify = $justify;
	}

	function do_excerpt( $type, $post_type, $category ) {
		// Excerpt size.
		$width = $this->width;

		if ( isset( $this->options[ $type . '_width' ] ) && $this->options[ $type . '_width' ] > 0 ) {
			$width = $this->options[ $type . '_width' ];
		}

		// Excerpt.
		switch ( $this->options[ $type ] ) {
			case 'all':
				return tonjoo_ecae_excerpt( $this->content, $width, $this->justify );
				break;

			case 'selection':
				$return = $this->excerpt_in_post_type( $this->options[ $post_type ], $width );

				if ( $return['excerpt'] == false ) {
					$return = $this->excerpt_in_category( $this->options[ $category ], $width );
					return $return['data'];
				} else {
					return $return['data'];
				}

			default:
				return $this->content;
				break;
		}
	}

	function get_excerpt_method( $type ) {
		if ( isset( $this->options[ $type . '_width' ] ) && $this->options[ $type . '_width' ] > 0 ) {
			return 'word';
		} else {
			return $this->options['excerpt_method'];
		}
	}

	function excerpt_in_post_type( $opt_post_type, $width ) {
		/**
		 * Excerpt in post type.
		 */
		$return['data']    = $this->content;
		$return['excerpt'] = false;

		$current_post_type = get_post_type( get_the_ID() );

		$excerpt_in_post_type = $opt_post_type;

		if ( is_array( $excerpt_in_post_type ) && in_array( $current_post_type, $excerpt_in_post_type ) ) {
			$return['data']    = tonjoo_ecae_excerpt( $this->content, $width, $this->justify );
			$return['excerpt'] = true;
		}

		return $return;
	}

	function excerpt_in_category( $opt_category, $width ) {
		/**
		 * Excerpt in category.
		 */
		$return['data']    = $this->content;
		$return['excerpt'] = false;

		$taxonomies = get_the_taxonomies( get_the_ID() );

		foreach ( $taxonomies as $key => $value ) {
			$taxonomy = $key;
			$category = wp_get_post_terms( get_the_ID(), $taxonomy );

			foreach ( $category as $n ) {
				$current_category    = $n->term_id;
				$excerpt_in_category = $opt_category;

				if ( is_array( $excerpt_in_category ) && in_array( $current_category, $excerpt_in_category ) ) {
					$return['data']    = tonjoo_ecae_excerpt( $this->content, $width, $this->justify );
					$return['excerpt'] = true;

					break 2;
				}
			}
		}

		return $return;
	}
}
