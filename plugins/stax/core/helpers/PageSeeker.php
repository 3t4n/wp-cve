<?php

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PageSeeker {

    /**
     * @var array
     */
    private $url = [
        'query' => false,
        'value' => ''
    ];

    /**
     * @var null
     */
    public static $instance = null;

    /**
     * @return null|PageSeeker
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param \WP_REST_Request $request
     *
     * @return string
     */
    public function find( \WP_REST_Request $request ) {
        $data = $request->get_params();

        if ( ! isset( $data['zone'] ) || ! is_numeric( $data['zone'] ) ) {
            return rest_ensure_response( $this->url );
        }

        $zone = Model_Zones::instance()->getById( $data['zone'] );

        if ( ! $zone ) {
            return rest_ensure_response( $this->url );
        }

        $conditions     = @json_decode( $zone->condition );
        $firstCondition = null;
        foreach ( $conditions as $condition ) {
            if ( $firstCondition ) {
                continue;
            }

            if ( $condition->type === 'include' ) {
                $firstCondition = $condition;
            }
        }

        if ( $firstCondition ) {

            $plugin = Plugin::instance();
            if ( $firstCondition->category === 'single' ) {
                $singles = $plugin->display_conditions_single();
                if ( ! $firstCondition->subcategory && ! $firstCondition->target ) {
                    $this->get_page_single();
                } else {
                    foreach ( $singles as $single ) {
                        if ( $single['tag'] === $firstCondition->subcategory ) {
                            $target = (int) $firstCondition->target;
                            $this->{$single['fetch']}( $target );
                        }
                    }
                }

            } elseif ( $firstCondition->category === 'archive' ) {
                $archives = $plugin->display_conditions_archive();
                foreach ( $archives as $archive ) {
                    if ( $archive['tag'] === $firstCondition->subcategory ) {
                        $this->{$archive['fetch']}();
                    }
                }
            } else {
                $this->url['value'] = get_home_url();
            }
        }

        return rest_ensure_response( $this->url );
    }

    /**
     * @param null $target
     */
    public function get_page_front( $target = null ) {
        $this->url['value'] = get_home_url();
    }

    /**
     * @param null $target
     */
    public function get_page_author( $target = null ) {
        $this->url['value'] = get_author_posts_url( get_current_user_id() );
    }

    /**
     * @param null $target
     */
    public function get_page_date( $target = null ) {
        $args = array(
            'post_type'      => 'post',
            'orderby'        => 'rand',
            'posts_per_page' => 1,
        );

        $query = new \WP_Query( $args );
        if ( ! empty( $query->posts ) ) {
            $post      = array_shift( $query->posts );
            $postYear  = date( 'Y', strtotime( $post->post_date ) );
            $postMonth = date( 'm', strtotime( $post->post_date ) );

            $this->url['value'] = get_home_url() . '/' . $postYear . '/' . $postMonth;
        }
    }

    /**
     * @param null $target
     */
    public function get_page_search( $target = null ) {
        $this->url['query'] = true;
        $this->url['value'] = get_home_url() . '?s=stax-search';
    }

    /**
     * Check page
     * @param null $target
     */
    public function get_page_single( $target = null ) {
        if ( $target ) {
            $page               = get_post( $target );
            $this->url['value'] = get_permalink( $page );
        } else {
            $args = array(
                'post_type'      => 'page',
                'orderby'        => 'rand',
                'posts_per_page' => 1,
            );

            $query = new \WP_Query( $args );
            if ( ! empty( $query->posts ) ) {
                $page               = array_shift( $query->posts );
                $this->url['value'] = get_permalink( $page );
            }
        }
    }

    /**
     * @param null $target
     */
    public function get_page_post( $target = null ) {
        if ( $target ) {
            $post               = get_post( $target );
            $this->url['value'] = get_permalink( $post );
        } else {
            $args = array(
                'post_type'      => 'post',
                'orderby'        => 'rand',
                'posts_per_page' => 1,
            );

            $query = new \WP_Query( $args );
            if ( ! empty( $query->posts ) ) {
                $post               = array_shift( $query->posts );
                $this->url['value'] = get_permalink( $post );
            }
        }
    }

	/**
	 * @param null $target
	 */
	public function get_post_type( $target = null ) {
		if ( $target ) {
			$post               = get_post( $target );
			$this->url['value'] = get_permalink( $post );
		} else {
			$args = array(
				'post_type'      => 'post', // TODO post type here
				'orderby'        => 'rand',
				'posts_per_page' => 1,
			);

			$query = new \WP_Query( $args );
			if ( ! empty( $query->posts ) ) {
				$post               = array_shift( $query->posts );
				$this->url['value'] = get_permalink( $post );
			}
		}
	}

    /**
     * @param null $target
     */
    public function get_page_category( $target = null ) {
        if ( $target ) {
            $category           = get_the_category_by_ID( $target );
            $this->url['value'] = get_category_link( $category );
        } else {
            $categories = get_categories();
            if ( ! empty( $categories ) ) {
                $category           = array_shift( $categories );
                $this->url['value'] = get_category_link( $category );
            }
        }
    }

    /**
     * @param null $target
     */
    public function get_page_tag( $target = null ) {
        if ( $target ) {
            $this->url['value'] = get_tag_link( $target );
        } else {
            $tags = get_tags();
            if ( ! empty( $tags ) ) {
                $tag                = array_shift( $tags );
                $this->url['value'] = get_tag_link( $tag->term_id );
            }
        }
    }

    /**
     * @param null $target
     */
    public function get_page_notfound( $target = null ) {
        $this->url['value'] = get_home_url() . '/' . uniqid() . '-' . uniqid() . '-' . uniqid();
    }

}
