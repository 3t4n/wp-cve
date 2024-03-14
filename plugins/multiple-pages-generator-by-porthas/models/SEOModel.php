<?php

class MPG_SEOModel
{

    public static function mpg_yoast($project_id)
    {
        // Disable canonical url in Yoast
        add_filter('wpseo_canonical', function ( $canonical ) use ( $project_id ) {
            if ( apply_filters( 'mpg_enable_canonical_url_generate', true ) ) {
                return false;
            }
            return MPG_CoreModel::mpg_shortcode_replacer( $canonical, $project_id );
        }, 1, 1);


        // Over overiding canonical
        add_filter('wpseo_opengraph_url', function ( $url ) use ( $project_id ) {
            global $wp;
            if ( apply_filters( 'mpg_enable_canonical_url_generate', true ) ) {
                return home_url( $wp->request );
            }
            return MPG_CoreModel::mpg_shortcode_replacer( $url, $project_id );
        }, 1, 1);

        // Скрываем блок с JSON LD, потому что нет возможности его переопределить, и выкинуть оттуда
        add_filter('wpseo_schema_graph', function( $data ) use ( $project_id ) {
            return self::seo_data_shortcode_replacer( $data, $project_id );
        });

        // Заменяем шорткоды в <title>...</title>
        add_filter('wpseo_title', function ($title) use ($project_id) {
            $post_id = get_queried_object_id();
            $description = get_post_meta($post_id, '_yoast_wpseo_title',  true);

            return  MPG_CoreModel::mpg_shortcode_replacer($description, $project_id);
        }, 1);


        add_filter('wpseo_metadesc', function ($description) use ($project_id) {

            $post_id = get_queried_object_id();
            $description = get_post_meta($post_id, '_yoast_wpseo_metadesc',  true);

            return  MPG_CoreModel::mpg_shortcode_replacer($description, $project_id);
        });

        // Переписывает свойство <meta property="og:title">
        add_filter('wpseo_opengraph_title', function ($title) use ($project_id) {
            $post_id = get_queried_object_id();
            $description = get_post_meta($post_id, '_yoast_wpseo_title',  true);

            return  MPG_CoreModel::mpg_shortcode_replacer($description, $project_id);
        });

        // Переписывает свойство <meta property="og:description">
        add_filter('wpseo_opengraph_desc', function ($description) use ($project_id) {
            $post_id = get_queried_object_id();
            $description = get_post_meta($post_id, '_yoast_wpseo_metadesc',  true);

            return  MPG_CoreModel::mpg_shortcode_replacer($description, $project_id);
        });

        add_filter('wpseo_twitter_title', function ($description) use ($project_id) {
            return  MPG_CoreModel::mpg_shortcode_replacer($description, $project_id);
        });

        add_filter('wpseo_twitter_description', function ($description) use ($project_id) {
            return  MPG_CoreModel::mpg_shortcode_replacer($description, $project_id);
        });


        add_filter('wpseo_opengraph_image',  function () use ($project_id) {

            $key = 'mpg_opengraph_image_src:' . $project_id;

            global $wpdb;
            $row = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = '" . $key . "'", ARRAY_A);

            $value =  isset($row[0]) ? $row[0]['meta_value'] : null;


            return  MPG_CoreModel::mpg_shortcode_replacer($value, $project_id);
        });

		// Filter SmartCrawl SEO plugin title.
		add_filter(
			'wds_title',
			function( $new_title ) use ( $project_id ) {
				if ( false !== strpos( $new_title, '{{mpg_' ) ) {
					$new_title = MPG_CoreModel::mpg_shortcode_replacer( $new_title, $project_id );
				};
				return $new_title;
			}
		);
	}

    public static function mpg_all_in_one_seo_pack($project_id)
    {

        // For All in One SEO Pack ver < 4
        add_filter('aioseop_title',  function ($title) use ($project_id) {
            return  MPG_CoreModel::mpg_shortcode_replacer($title, $project_id);
        });

        add_filter('aioseop_description_override',  function ($description) use ($project_id) {
            return  MPG_CoreModel::mpg_shortcode_replacer($description, $project_id);
        });


        // For All in One SEO Pack ver >= 4
        add_filter('aioseo_title',  function ($title) use ($project_id) {
            return  MPG_CoreModel::mpg_shortcode_replacer($title, $project_id);
        });

        add_filter('aioseo_description',  function ($description) use ($project_id) {
            return  MPG_CoreModel::mpg_shortcode_replacer($description, $project_id);
        });

        add_filter('aioseop_canonical_url', function ( $canonical_url ) {
            if ( apply_filters( 'mpg_enable_canonical_url_generate', true ) ) {
                return false;
            }
            return $canonical_url;
        }, 10, 1);

        // Filter Open Graph Data.
        add_filter(
            'aioseo_facebook_tags',
            function( $facebook_data ) use ( $project_id ) {
                return self::seo_data_shortcode_replacer( $facebook_data, $project_id );
            },
            99
        );

        add_filter(
            'aioseo_twitter_tags',
            function( $facebook_data ) use ( $project_id ) {
                return self::seo_data_shortcode_replacer( $facebook_data, $project_id );
            },
            99
        );
    }

    public static function mpg_rank_math($post, $project_id)
    {
        // RankMath SEO Plugin fix. Filter to change the page title
        add_filter('rank_math/frontend/title', function ($title) use ($post, $project_id) {
            $title = RankMath\Post::get_meta( 'title', $post->ID );
            if ( ! $title ) {
                $title = RankMath\Helper::get_settings( "titles.pt_{$post->post_type}_title" );
                if ( $title ) {
                    return MPG_CoreModel::mpg_shortcode_replacer( RankMath\Helper::replace_vars( $title, $post ), $project_id );
                }
            }
            return  MPG_CoreModel::mpg_shortcode_replacer( $title, $project_id );
        });


        add_filter('rank_math/frontend/description', function ($description) use ($project_id) {

            if ($description) {

                // This description is a global for all pages or posts.
                return  MPG_CoreModel::mpg_shortcode_replacer($description, $project_id);
            } else {

                global $post;
                $desc = RankMath\Post::get_meta('description', $post->ID);
                if (!$desc) {
                    $desc = RankMath\Helper::get_settings("titles.pt_{$post->post_type}_description");
                    if ($desc) {
                        return MPG_CoreModel::mpg_shortcode_replacer(RankMath\Helper::replace_vars($desc, $post), $project_id);
                    }
                }

                return  MPG_CoreModel::mpg_shortcode_replacer($desc, $project_id);
            }
        });

        add_filter('rank_math/frontend/robots', function () {
            // https://porthas.atlassian.net/browse/MPGWP-54
            $rank_math_settings = get_option('rank-math-options-titles');
            $robots_options = ['follow'];
            if ($rank_math_settings && isset($rank_math_settings['robots_global']) && is_array($rank_math_settings['robots_global'])) {
                $robots_options = array_merge($rank_math_settings['robots_global'], $robots_options);
            }

            return  $robots_options;
        });
    }

    public static function mpg_seopress($project_id)
    {

        add_filter('seopress_titles_title', function ($title) use ($project_id) {
            return  MPG_CoreModel::mpg_shortcode_replacer($title, $project_id);
        });

        add_filter('seopress_titles_desc', function ($description) use ($project_id) {
            return  MPG_CoreModel::mpg_shortcode_replacer($description, $project_id);
        });
    }

    public static function mpg_squirrly_seo( $project_id = 0 ) {
        // Filter Squirrly SEO plugin title.
        add_filter(
            'sq_title',
            function( $new_title ) use ( $project_id ) {
                if ( false !== strpos( $new_title, '{{mpg_' ) ) {
                    $new_title = MPG_CoreModel::mpg_shortcode_replacer( $new_title, $project_id );
                };
                return $new_title;
            },
            99
        );

        // Filter Squirrly SEO plugin description.
        add_filter(
            'sq_description',
            function( $description ) use ( $project_id ) {
                if ( false !== strpos( $description, '{{mpg_' ) ) {
                    $description = MPG_CoreModel::mpg_shortcode_replacer( $description, $project_id );
                };
                return $description;
            },
            99
        );

        // Filter Open Graph Data.
        add_filter(
            'sq_open_graph',
            function( $og_data ) use ( $project_id ) {
                return self::seo_data_shortcode_replacer( $og_data, $project_id );
            },
            99
        );

        // Filter Twitter Card.
        add_filter(
            'sq_twitter_card',
            function( $tw_data ) use ( $project_id ) {
                return self::seo_data_shortcode_replacer( $tw_data, $project_id );
            },
            99
        );

        // Filter JSON-LD data.
        add_filter(
            'sq_json_ld',
            function( $ld_data ) use ( $project_id ) {
                return self::seo_data_shortcode_replacer( $ld_data, $project_id );
            },
            50
        );
    }

    public static function seo_data_shortcode_replacer( $data = array(), $project_id = 0 ) {
        if ( ! empty( $data ) ) {
            global $post;
            foreach ( $data as $key => $value ) {
                if ( is_array( $value ) ) {
                    $data[ $key ] = self::seo_data_shortcode_replacer( $value, $project_id );
                } else {
                    $value = str_replace( home_url( '/' ), '', $value );
                    if ( false !== strpos( $value, '{{mpg_' ) ) {
                        $data[ $key ] = MPG_CoreModel::mpg_shortcode_replacer( $value, $project_id );
                    } else {
                        $mpg_shortcode = 'mpg_.*';
                        preg_match( "/{$mpg_shortcode}/i", $value, $matches );
                        if ( ! empty( $matches ) ) {
                            $matches              = reset( $matches );
                            $append_curly_bracket = '{{' . $matches . '}}';
                            if ( $post ) {
                                $value = str_replace( $post->post_name, get_the_permalink(), $value );
                            }
                            $value                = str_replace( $matches, $append_curly_bracket, $value );
                            $data[ $key ]         = MPG_CoreModel::mpg_shortcode_replacer( $value, $project_id );
                        }
                    }
                }
            }
        }
        return $data;
    }

    public static function mpg_the_seo_framework( $project_id ) {
        add_filter(
            'the_seo_framework_title_from_custom_field',
            function( $title ) use ( $project_id ) {
                if ( false !== strpos( $title, '{{mpg_' ) ) {
                    $title = MPG_CoreModel::mpg_shortcode_replacer( $title, $project_id );
                };
                return $title;
            },
            99
        );

        add_filter(
            'the_seo_framework_custom_field_description',
            function( $description ) use ( $project_id ) {
                if ( false !== strpos( $description, '{{mpg_' ) ) {
                    $description = MPG_CoreModel::mpg_shortcode_replacer( $description, $project_id );
                }
                return $description;
            },
            99
        );

        add_filter(
            'the_seo_framework_image_details',
            function( $images )  use ( $project_id )  {
                if ( ! empty( $images ) ) {
                    foreach( $images as $key => $image ) {
                        if ( ! empty( $image['url'] ) ) {
                            $mpg_shortcode = 'mpg_.*';
                            preg_match( "/{$mpg_shortcode}/i", $image['url'], $matches );
                            if ( ! empty( $matches ) ) {
                                $image['url'] = str_replace( home_url( '/' ), '', $image['url'] );
                                $matches               = reset( $matches );
                                $append_curly_bracket  = '{{' . $matches . '}}';
                                $image['url']          = str_replace( $matches, $append_curly_bracket, $image['url'] );
                                $images[ $key ]['url'] = MPG_CoreModel::mpg_shortcode_replacer( $image['url'], $project_id );
                            }
                        }
                    }
                }
                return $images;
            }
        );
    }
}
