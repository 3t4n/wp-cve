<?php

class MegaMenu_WP_Menu_Item {
	public $item;
	public $mega_menu_settings;
	public $mega_menu_post;
	function __construct( $menu_item ) {
		$this->item = $menu_item;
		$this->setup_widget_classes();
		$this->setup_settings();
		$this->setup_post_query();
	}

	public function render() {
		$html = $this->render_mega_content();
		return $html;
	}

	function setup_settings() {
		$default = array(
			'enable'            => '',
			'menu_type'         => '',
			'layout'            => '',
			'content_layout'    => '',
			'column_heading'    => 'hide_on_mobile',
			'content_width'     => '',
			'content_position'  => 'left',
			'bg_color'          => '',
		);
		$this->mega_menu_settings = wp_parse_args( $this->item->mega_menu_settings, $default );
	}

	function setup_post_query() {
		$default = array(
			'post_type'         => '',
			'tax'               => '',
			'cat'               => '',
			'terms'             => '',
			'columns'           => 3,
			'tabs_layout'       => 'left',
			'show_when'         => 'hover',
			'post__not_in'      => '',
			'post__in'          => '',
			'posts_per_page'    => '',
			'orderby'           => '',
			'order'             => '',
			'show_all_link'     => '',
			'all_item_text'     => esc_html__( 'All', 'megamenu-wp' ),
			'all_item_link'     => '',
		);
		$this->mega_menu_post = wp_parse_args( $this->item->mega_menu_post, $default );
		if ( ! $this->mega_menu_post['columns'] ) {
			$this->mega_menu_post['columns'] = 3;
		}
	}

	function col_settings( $col_settings ) {
		$default = array(
			'col' => 4,
			'heading' => '',
		);
		return wp_parse_args( $col_settings, $default );
	}


	function setup_widget_classes() {
		if ( ! isset( $GLOBALS['_registered_widgets'] ) ) {
			$GLOBALS['_registered_widgets'] = array();
			global $wp_widget_factory;
			foreach ( $wp_widget_factory->widgets as $widget_class => $settings ) {
				if ( class_exists( $widget_class ) ) {
					$GLOBALS['_registered_widgets'][ $settings->id_base ] = $widget_class;
				}
			}
		}
	}

	function get_widget_class_by_id( $widget_id ) {
		if ( ! $widget_id ) {
			return false;
		}
		if ( ! is_array( $GLOBALS['_registered_widgets'] ) ) {
			return false;
		}

		if ( isset( $GLOBALS['_registered_widgets'][ $widget_id ] ) ) {
			return $GLOBALS['_registered_widgets'][ $widget_id ];
		}

		return false;
	}

	function render_widget( $item ) {
		$item = wp_parse_args(
			$item,
			array(
				'widget_id' => '',
				'settings' => array(),
			)
		);

		if ( ! is_array( $item['settings'] ) ) {
			$item['settings'] = array();
		}

		$widget_class = $this->get_widget_class_by_id( $item['widget_id'] );
		if ( ! $widget_class ) {
			return false;
		}

		ob_start();

		$instance = $item['settings'];
		global $wp_widget_factory;

		$widget_obj = $wp_widget_factory->widgets[ $widget_class ];
		if ( ! ( $widget_obj instanceof WP_Widget ) ) {
			return false;
		}

		$args = array(
			'before_widget' => '<div class="widget %s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>',
		);
		$args['before_widget'] = sprintf( $args['before_widget'], $widget_obj->widget_options['classname'] );

		$instance = wp_parse_args( $instance );

		$widget_obj->widget( $args, $instance );

		return ob_get_clean();

	}

	function setup_menu_item( $menu_item ) {

		if ( $menu_item['type'] == 'custom' && $menu_item['menu_item_id'] == 'home' ) {
			if ( ! $menu_item['title'] ) {
				$menu_item['title'] = esc_html__( 'Home', 'megamenu-wp' );
			}
			$menu_item['url'] = home_url( '/' );
		} elseif ( $menu_item['type'] == 'custom' ) {

			if ( ! $menu_item['title'] ) {
				$menu_item['title'] = esc_html__( 'Untitled', 'megamenu-wp' );
			}
			if ( ! $menu_item['url'] ) {
				$menu_item['url'] = '#';
			}
		} else {

			if ( $menu_item['type'] && $menu_item['object_id'] ) {

				if ( $menu_item['type'] == 'taxonomy' ) {
					$term = get_term( $menu_item['object_id'], $menu_item['object'] );
					if ( $term && ! is_wp_error( $term ) ) {
						$menu_item['url'] = get_term_link( $term );
						if ( ! $menu_item['title'] ) {
							$menu_item['title'] = $term->name;
						}
					}
				} elseif ( $menu_item['type'] == 'post_type' ) {
					$p = get_post( $menu_item['object_id'] );
					if ( $p ) {
						$menu_item['url'] = get_permalink( $p );
					} else {
						$menu_item['title'] = apply_filters( 'the_title', $p->post_title, $p->ID );
					}
				}
			}
		}

		return $menu_item;
	}

	function render_menu_item( $item ) {
		$item = wp_parse_args(
			$item,
			array(
				'attr_title'        => '',
				'classes'           => '',
				'description'       => '',
				'item_type_label'   => '',
				'menu_item_id'      => '',
				'object'            => '',
				'object_id'         => '',
				'status'            => '',
				'target'            => '',
				'title'             => '',
				'type'              => '',
				'url'               => '',
				'xfn'               => '',
			)
		);

		$item = $this->setup_menu_item( $item );

		if ( ! $item['url'] ) {
			return false;
		}

		$html = '<a href="' . esc_url( $item['url'] ) . '"';
		if ( $item['classes'] ) {
			$html .= ' class="' . esc_attr( $item['classes'] ) . '" ';
		}

		if ( $item['target'] ) {
			$html .= ' target="' . esc_attr( $item['target'] ) . '" ';
		}

		$html .= '>';
		$html .= balanceTags( $item['title'], true );
		$html .= '</a>';

		return $html;
	}

	function render_mega_content() {
		if ( $this->mega_menu_settings['menu_type'] == 'layout' ) {
			return $this->render_builder_layout();
		} else {
			return $this->render_post();
		}

	}

	function render_builder_layout() {

		$html = false;

		if ( ! empty( $this->item->mega_menu_layout ) ) {
			$child_li = MegaMenu_WP::get_theme_support( 'child_li' );
			$tag = 'div';
			$tag_child = 'div';
			if ( $child_li ) {
				$tag = 'ul';
				$tag_child = 'li';
			}
			foreach ( $this->item->mega_menu_layout as $row_index => $row ) {
				if ( ! empty( $row ) ) {
					$html .= '<div class="mega-container rc-' . esc_attr( $this->mega_menu_settings['column_heading'] ) . '">';
						$html .= '<div class="mega-row">';

					foreach ( $row as $col_index => $col ) {
						$col['settings'] = $this->col_settings( $col['settings'] );
						$col_classes = array();
						$col_classes[] = 'mega-col';
						$col_classes[] = 'col-' . $col['settings']['col'];

						$html .= '<div class="' . esc_attr( join( ' ', $col_classes ) ) . '">';

						if ( $col['settings']['heading'] ) {
							$html .= '<div class="column-heading">' . balanceTags( $col['settings']['heading'] ) . '</div>';
						}

						$html .= '<' . $tag . ' class="mega-items">';
						foreach ( $col['items'] as $item_index => $item ) {
							$item = wp_parse_args(
								$item,
								array(
									'_item_type' => '',
								)
							);
							if ( $item['_item_type'] == 'widget' ) {
								$widget_content = $this->render_widget( $item );
								if ( $widget_content ) {
									$html .= '<div class="mega-element mega-widget">' . $widget_content . '</div>';
								}
							} elseif ( $item['_item_type'] == 'menu_item' ) {
								$item_menu = $this->render_menu_item( $item );
								if ( $item_menu ) {
									$html .= '<' . $tag_child . ' class="mega-element mega-menu-item">' . $item_menu . '</' . $tag_child . '>';
								}
							}
						}
							$html .= '</' . $tag . '>';

							$html .= '</div>';
					}

						$html .= '</div>';
					$html .= '</div>';
				}
			}
		}

		if ( $html ) {
			$html = '<div class="mega-builder-container">' . $html . '</div>';
		}

		return $html;

	}

	static function posts_content( $query_args ) {
		$query_args = self::get_post_query_args( $query_args );
		if ( ! $query_args['posts_per_page'] ) {
			$query_args['posts_per_page'] = get_option( 'posts_per_page' );
		}
		$query_args = array_filter( $query_args );

		$query_args['ignore_sticky_posts'] = true;
		$query_args['post_status'] = 'publish';

		$query = new WP_Query( $query_args );
		$post_type = $query->get( 'post_type' );
		if ( is_array( $post_type ) ) {
			$post_type = current( $post_type );
		}

		if ( in_array( $post_type, array( 'product', 'product_variation' ) ) ) {
			$post_type = 'product';
		}

		$content = null;
		if ( $query->have_posts() ) {
			ob_start();
			$file = MegaMenu_WP::get_template( 'nav-' . $post_type . '.php' );
			if ( ! $file ) {
				$file = MegaMenu_WP::get_template( 'nav-post.php' );
			}

			$file = apply_filters( 'megamenuwp_nav_post_tpl', $file, $post_type );
			if ( is_file( $file ) ) {
				include $file;
			}

			$paging_file = MegaMenu_WP::get_template( 'paging.php' );
			if ( $paging_file ) {
				include $paging_file;
			}

			$content = ob_get_clean();
		}

		wp_reset_postdata();
		wp_reset_query();

		return $content;
	}

	static function get_post_query_args( $data ) {

		$post_args = array(
			'posts_per_page'    => 10,
			'post__not_in'      => '',
			'post__in'          => '',
			'tax_query'         => '',
			'post_type'         => 'post',
			'orderby'           => '',
			'order'             => '',
			'paged'             => '',
		);

		$data = wp_parse_args(
			$data,
			array(
				'taxonomy'  => '',
				'terms'     => '',
			)
		);

		$args = wp_array_slice_assoc( $data, array_keys( $post_args ) );

	
			if ( $args['post_type'] != 'post' ) {
				$args['post_type'] = 'post';
			}
			// Validate taxonomy
			if ( ! $data['taxonomy'] ) {
				if ( ! in_array( $data['taxonomy'], array( 'category', 'tag', 'post_format' ) ) ) {
					$data['taxonomy'] = 'category';
				}
			}
		

		if ( ! empty( $data['terms'] ) && $data['taxonomy'] ) {
			if ( ! is_array( $data['terms'] ) ) {
				$data['terms'] = (array) $data['terms'];
			}

			$_t = current( $data['terms'] );
			reset( $data['terms'] );
			if ( $_t instanceof WP_Term ) {
				$data['terms'] = wp_list_pluck( $data['terms'], 'term_id' );
			} else {
				$data['terms'] = array_map( 'absint', $data['terms'] );
				$data['terms'] = array_filter( $data['terms'] );
			}

			if ( ! empty( $data['terms'] ) ) {
				$tax = array(
					'taxonomy' => $data['taxonomy'],
					'field' => 'term_id',
					'terms' => $data['terms'],
				);
				unset( $data['terms'] );
				unset( $data['taxonomy'] );

				$args['tax_query'] = array(
					'relation' => 'AND',
					$tax,
				);
			}
		}

		if ( isset( $args['post__not_in'] ) ) {
			if ( empty( $args['post__not_in'] ) ) {
				unset( $args['post__not_in'] );
			} elseif ( is_string( $args['post__not_in'] ) ) {
				$args['post__not_in'] = explode( ',', $args['post__not_in'] );
				$args['post__not_in'] = array_map( 'trim', $args['post__not_in'] );
				$args['post__not_in'] = array_map( 'absint', $args['post__not_in'] );
				$args['post__not_in'] = array_filter( $args['post__not_in'] );
				if ( empty( $args['post__not_in'] ) ) {
					unset( $args['post__not_in'] );
				}
			} else { // is array
				$args['post__not_in'] = array_map( 'absint', $args['post__not_in'] );
				$args['post__not_in'] = array_filter( $args['post__not_in'] );
				if ( empty( $args['post__not_in'] ) ) {
					unset( $args['post__not_in'] );
				}
			}
		}

		if ( isset( $args['post__in'] ) ) {
			if ( empty( $args['post__in'] ) ) {
				unset( $args['post__in'] );
			} elseif ( is_string( $args['post__in'] ) ) {
				$args['post__in'] = explode( ',', $args['post__in'] );
				$args['post__in'] = array_map( 'trim', $args['post__in'] );
				$args['post__in'] = array_map( 'absint', $args['post__in'] );
				$args['post__in'] = array_filter( $args['post__in'] );
				if ( empty( $args['post__in'] ) ) {
					unset( $args['post__in'] );
				}
			} else {
				$args['post__in'] = array_map( 'absint', $args['post__in'] );
				$args['post__in'] = array_filter( $args['post__in'] );
				if ( empty( $args['post__in'] ) ) {
					unset( $args['post__in'] );
				}
			}
		}

		return $args;
	}

	function render_post() {
		$html = '';
		if ( empty( $this->mega_menu_post['terms'] ) ) {
			$this->mega_menu_post['terms'] = array();
		}

		if ( ! is_array( $this->mega_menu_post['terms'] ) && $this->mega_menu_post['terms'] ) {
			$this->mega_menu_post['terms'] = explode( ',', $this->mega_menu_post['terms'] );
		}

		$query_args = $this->get_post_query_args( $this->mega_menu_post );
		if ( ! $query_args['posts_per_page'] ) {
			$query_args['posts_per_page'] = $this->mega_menu_post['columns'];
		}

		$categories = false;
		// Check if is array of object
		if ( isset( $this->mega_menu_post['terms'][0] ) && $this->mega_menu_post['terms'][0] instanceof WP_Term ) {
			$categories = $this->mega_menu_post['terms'];
		}
		if ( ! $categories ) {
			$this->mega_menu_post['terms'] = array_map( 'absint', $this->mega_menu_post['terms'] );
			$this->mega_menu_post['terms'] = array_filter( $this->mega_menu_post['terms'] );
			if ( ! empty( $this->mega_menu_post['terms'] ) ) {
				$categories = get_terms(
					array(
						'taxonomy' => $this->mega_menu_post['tax'],
						'include' => $this->mega_menu_post['terms'],
						'hide_empty' => false,
						'orderby' => 'include',
					)
				);
			}
		}

		if ( $categories ) {

			$all_link = '';
			$all_terms = null;
			$all_content = null;

			// Show all tabs
			if ( count( $categories ) > 1 && $this->mega_menu_post['show_all_link'] && $this->mega_menu_post['show_all_link'] != 'no' ) {

				$all_terms = wp_list_pluck( $categories, 'term_id' );

				$tax = array(
					'taxonomy' => $this->mega_menu_post['tax'],
					'field'    => 'term_id',
					'terms'    => $all_terms,
				);

				$text = $this->mega_menu_post['all_item_text'];
				$link = $this->mega_menu_post['all_item_link'];
				if ( ! $text ) {
					$text = esc_html__( 'All', 'megamenu-wp' );
				}
				if ( ! $link ) {
					$link = '#';
				}

				$all_link = '<div class="li" data-id="' . esc_attr( join( '-', $all_terms ) ) . '" data-query="' . esc_attr( json_encode( array_filter( array_merge( $query_args, $tax ) ) ) ) . '"><a href="' . esc_url( $link ) . '">' . balanceTags( $text, true ) . '</a><span class="arrow"><span class="arrow-inner"></span></span></div>';

				$query_args['tax_query'] = $this->mega_menu_post['tax'];
				$query_args['terms'] = $all_terms;

				$content = $this->posts_content( $query_args );
				if ( $content ) {
					$all_content .= '<div data-id="' . esc_attr( join( '-', $all_terms ) ) . '"  data-col="' . esc_attr( $this->mega_menu_post['columns'] ) . '" class="nav-posts-tab">';
					$all_content .= $content;
					$all_content .= '</div>';
				}
			} // end show all

			$html .= '<div data-show-when="' . esc_attr( $this->mega_menu_post['show_when'] ) . '" class="mega-tab-posts tabs-' . esc_attr( $this->mega_menu_post['tabs_layout'] ) . '">';

			if ( $this->mega_menu_post['tabs_layout'] != 'no-tabs' ) {

				if ( $this->mega_menu_post['tabs_layout'] != 'right' ) {
					$this->mega_menu_post['tabs_layout'] = 'left';
				}
				$tab_contents = '';
				$html .= '<div class="mega-tab-post-nav ul">';

				if ( $all_link && $this->mega_menu_post['show_all_link'] == 'top' ) {
					$html .= $all_link;
					$tab_contents .= $all_content;
				}

				foreach ( $categories as $cat ) {
					$link = get_term_link( $cat );

					unset( $query_args['tax_query'] );
					unset( $query_args['terms'] );

					$tax = array(
						'taxonomy' => $this->mega_menu_post['tax'],
						'field'    => 'term_id',
						'terms'    => array( $cat->term_id ),
					);

					$html .= '<div class="li" data-id="' . esc_attr( $cat->term_id ) . '" data-query="' . esc_attr( json_encode( array_filter( array_merge( $query_args, $tax ) ) ) ) . '"><a href="' . esc_url( $link ) . '">' . $cat->name . '</a><span class="arrow"><span class="arrow-inner"></span></span></div>';

					$query_args['tax_query'] = array(
						'relation' => 'AND',
						$tax,
					);

					$content = $this->posts_content( $query_args );
					if ( $content ) {
						$tab_contents .= '<div data-id="' . esc_attr( $cat->term_id ) . '" data-col="' . esc_attr( $this->mega_menu_post['columns'] ) . '" class="nav-posts-tab">';
						$tab_contents .= $content;
						$tab_contents .= '</div>';
					}
				}

				if ( $all_link && $this->mega_menu_post['show_all_link'] == 'bottom' ) {
					$html .= $all_link;
					$tab_contents .= $all_content;
				}

				$html .= '</div>'; // end UL

				$html .= '<div class="mega-tab-post-cont">';
					$html .= $tab_contents;
				$html .= '</div>';

			} else {

				$query_args['tax_query'] = $this->mega_menu_post['tax'];
				$query_args['terms'] = wp_list_pluck( $categories, 'term_id' );

				$html .= '<div class="mega-tab-post-cont" data-query="' . esc_attr( json_encode( $query_args ) ) . '">';

				$content = $this->posts_content( $query_args );
				if ( $content ) {
					$html .= '<div data-col="' . esc_attr( $this->mega_menu_post['columns'] ) . '" class="nav-posts-tab">';
					$html .= $content;
					$html .= '</div>';
				}

				$html .= '</div>';
			}

			$html .= '</div>';

		} else { // No tax, terms chosen
			$html .= '<div data-show-when="' . esc_attr( $this->mega_menu_post['show_when'] ) . '" class="mega-tab-posts tabs-' . esc_attr( $this->mega_menu_post['tabs_layout'] ) . '">';

				$html .= '<div class="mega-tab-post-cont" data-query="' . esc_attr( json_encode( $query_args ) ) . '">';

					$content = $this->posts_content( $query_args );
			if ( $content ) {
				$html .= '<div data-col="' . esc_attr( $this->mega_menu_post['columns'] ) . '" class="nav-posts-tab">';
				$html .= $content;
				$html .= '</div>';
			}

				$html .= '</div>';

			$html .= '</div>';

		}

		if ( $html ) {
			$html  = '<div class="mega-posts-wrapper">' . $html . '</div>';
		}

		return $html;
	}

}
