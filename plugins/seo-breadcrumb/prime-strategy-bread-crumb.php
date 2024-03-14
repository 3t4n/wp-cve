<?php
/*
Plugin Name: SEO BreadCrumb
Plugin URI: http://nob-log.info/wordpress-plugin-seo-bread-crumb
Description: This plugin adds the function to display breadcrumbs (topic path) navigation that supports HTML5 micorodata. You can use display styles, lots of parameters of styles and original plugin hooks of breadcrumbs navigation, and you can customize navigations flexibly.
Author: Nobuhiko Kimoto
Version: 1.0.2
Author URI: http://nob-log.info
License: GPLv2 or later
PHP Version: Require PHP5
Text Domain: seo-breadcrumb
Domain Path: /lang
Origin: http://wordpress.org/plugins/prime-strategy-bread-crumb/
*/

/*  Copyright 2013 Nobuhiko Kimoto (email : info@nob-log.info)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
class prime_strategy_bread_crumb
{
    function __construct()
    {
        add_action( 'plugins_loaded', array( &$this, 'load_textdomain' ) );
    }

    public function load_textdomain()
    {
        load_plugin_textdomain(
            "seo-breadcrumb",
            false,
            dirname( plugin_basename( __FILE__ ) ) . '/languages'
        );
    }

    public function bread_crumb( $args = '' )
    {
        $default = array(
            'type'				=> 'string',
            'home_label'		=> __( 'Home', 'seo-breadcrumb' ),
            'search_label'		=> __( 'Search Result of "%s"', 'seo-breadcrumb' ),
            '404_label'			=> __( '404 Not Found', 'seo-breadcrumb' ),
            'category_label'	=> _x( '%s', 'category label', 'seo-breadcrumb' ),
            'tag_label'			=> _x( '%s', 'tag label', 'seo-breadcrumb' ),
            'taxonomy_label'	=> _x( '%s', 'taxonomy label', 'seo-breadcrumb' ),
            'author_label'		=> _x( '%s', 'author label', 'seo-breadcrumb' ),
            'attachment_label'	=> _x( '%s', 'attachment label', 'seo-breadcrumb' ),
            'year_label'		=> _x( '%s', 'year label', 'seo-breadcrumb' ),
            'month_label'		=> _x( '%s', 'month label', 'seo-breadcrumb' ),
            'day_label'			=> _x( '%s', 'day label', 'seo-breadcrumb' ),
            'post_type_label'	=> _x( '%s', 'post type label', 'seo-breadcrumb' ),
            'joint_string'		=> __( ' &gt; ', 'seo-breadcrumb' ),
            'navi_element'		=> 'div',
            'elm_class'			=> 'bread_crumb',
            'elm_id'			=> 'breadcrumb',
            'li_class'			=> '',
            'class_prefix'		=> '',
            'current_class'		=> 'current',
            'indent'			=> 0,
            'echo'				=> true,
            'disp_current'		=> false,
        );
        $default = apply_filters( 'bread_crumb_default', $default );
        $args = wp_parse_args( $args, $default );

        $elm = in_array( $args['navi_element'], array( 'nav', 'div', '' ) ) ? $args['navi_element'] : 'div';
        $args['elm_id'] = is_array( $args['elm_id'] ) ? $default['elm_id'] : $args['elm_id'];
        $args['elm_id'] = preg_replace( '/[^\w_-]+/', '', $args['elm_id'] );
        $args['elm_id'] = preg_replace( '/^[\d_-]+/', '', $args['elm_id'] );

        $args['class_prefix'] = is_array( $args['class_prefix'] ) ? $default['class_prefix'] : $args['class_prefix'];
        $args['class_prefix'] = preg_replace( '/[^\w_-]+/', '', $args['class_prefix'] );
        $args['class_prefix'] = preg_replace( '/^[\d_-]+/', '', $args['class_prefix'] );

        $args['elm_class'] = $this->sanitize_attr_classes( $args['elm_class'], $args['class_prefix'] );
        $args['li_class'] = $this->sanitize_attr_classes( $args['li_class'], $args['class_prefix'] );
        $args['current_class'] = $this->sanitize_attr_classes( $args['current_class'], $args['class_prefix'] );
        $args['current_class'] = $args['current_class'] ? $args['current_class'] : $args['class_prefix'] . $default['current_class'];
        $args['echo'] = $this->uniform_boolean( $args['echo'], $default['echo'] );

        $tabs = str_repeat( "\t", (int) $args['indent'] );

        $bread_crumb_arr = $this->get_bread_crumb_array( $args );

        $elm_attrs = '';
        if ($args['elm_id']) {
            $elm_attrs = ' id="' . $args['elm_id'] . '"';
        }
        if ($args['elm_class']) {
            $elm_attrs .= ' class="' . $args['elm_class'] . '"';
        }

        $output = '';
        $elm_tabs = '';

        $output = '';
        if ($elm) {
            $elm_tabs = "\t";
            $output = $tabs . '<' . $elm;
            if ($elm_attrs) {
                $output .= $elm_attrs . ">\n";
            }
        }

        if ($args['type'] == 'string') {
            $breadcrumbs = array();
            $cnt = 1;
            foreach ($bread_crumb_arr as $ancestor) {
                if ( $cnt == count( $bread_crumb_arr ) ) {
                    if ($args['disp_current']) {
                        $breadcrumbs[] = '<strong class="' . $args['current_class'] . '">' . apply_filters( 'the_title', $ancestor['title'] ) . '</strong>';
                    }
                } else {
                    $breadcrumbs[] = '
                        <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                        <a href="' . $ancestor['link'] . '" itemprop="url">
                        <span itemprop="title">' . apply_filters( 'the_title', $ancestor['title'] ) . '</span>
                        </a> ' . esc_html($args['joint_string']) . '
                        </div>';
                }
                $cnt++;
            }
            $output .= implode( '' , $breadcrumbs );
            $output = apply_filters( 'bread_crumb_after', $output, $args );

        } else {
            $output .= $elm_tabs . $tabs . '<ul';
            if (! $elm && $elm_attrs) {
                $output .= $elm_attrs;
            }
            $output .= ">\n";

            $output = apply_filters( 'bread_crumb_before', $output, $args );

            $cnt = 1;
            foreach ($bread_crumb_arr as $ancestor) {
                $classes = array();
                $classes[] = $args['class_prefix'] . 'level-' . $cnt;
                if ($cnt == 1) {
                    $classes[] = $args['class_prefix'] . 'top';
                } else {
                    $classes[] = $args['class_prefix'] . 'sub';
                }
                if ( $cnt == count( $bread_crumb_arr ) ) {
                    if ($args['disp_current'] === false) continue;
                    $classes[] = $args['class_prefix'] . 'tail';
                    $output .= $elm_tabs . $tabs . '<li class="' . implode( ' ', $classes );
                    if ($args['li_class']) {
                        $output .= ' ' . $args['li_class'];
                    }
                    $output .= ' ' .  $args['current_class'];
                    $output .= '">' . apply_filters( 'the_title', $ancestor['title'] ) . '</li>' . "\n";
                } else {
                    $output .= $elm_tabs . $tabs . '
                        <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="' . implode( ' ', $classes );
                    if ($args['li_class']) {
                        $output .= ' ' . $args['li_class'];
                    }
                    $output .= '">
                        <a href="' . $ancestor['link'] . '" itemprop="url">
                        <span itemprop="title">' . apply_filters( 'the_title', $ancestor['title'] ) . '</span>
                        </a>' . esc_html($args['joint_string']) . '</li>' . "\n";
                }
                $cnt++;
            }
            $output = apply_filters( 'bread_crumb_after', $output, $args );

            $output .= $elm_tabs . $tabs . '</ul>' . "\n";
        }

        if ($elm) {
            $output .= $tabs . '</' . $elm . ">\n";
        }

        $output = apply_filters( 'bread_crumb', $output, $args );

        if ($args['echo']) {
            echo $output;
        } else {
            return $output;
        }
    }

    private function get_bread_crumb_array( $args )
    {
        global $post;

        $bread_crumb_arr = array();
        $bread_crumb_arr[] = array( 'title' => $args['home_label'], 'link' => get_bloginfo( 'url' ) . '/' );
        $bread_crumb_arr = $this->add_posts_page_array( $bread_crumb_arr );
        if ( is_404() ) {
            $bread_crumb_arr[] = array( 'title' => $args['404_label'], 'link' => false );
        } elseif ( is_search() ) {
            $bread_crumb_arr[] = array( 'title' => sprintf( $args['search_label'], get_search_query() ), 'link' => false );
        } elseif ( is_tax() ) {
            $taxonomy = get_query_var( 'taxonomy' );
            $term = get_term_by( 'slug', get_query_var( 'term' ), $taxonomy );
            if ( is_taxonomy_hierarchical( $taxonomy ) && $term->parent != 0 ) {
                $ancestors = array_reverse( get_ancestors( $term->term_id, $taxonomy ) );
                foreach ($ancestors as $ancestor_id) {
                    $ancestor = get_term( $ancestor_id, $taxonomy );
                    $bread_crumb_arr[] = array( 'title' => $ancestor->name, 'link' => get_term_link( $ancestor, $term->slug ) );
                }
            }
            $bread_crumb_arr[] = array( 'title' => sprintf( $args['taxonomy_label'], $term->name ), 'link' => get_term_link( $term->term_id, $term->slug ) );
        } elseif ( is_attachment() ) {
            if ($post->post_parent) {
                if ( $parent_post = get_post( $post->post_parent ) ) {
                    $singular_bread_crumb_arr = $this->get_singular_bread_crumb_array( $parent_post, $args );
                    $bread_crumb_arr = array_merge( $bread_crumb_arr, $singular_bread_crumb_arr );
                }
            }
            $bread_crumb_arr[] = array( 'title' => $parent_post->post_title, 'link' => get_permalink( $parent_post->ID ) );
            $bread_crumb_arr[] = array( 'title' => sprintf( $args['attachment_label'], $post->post_title ), 'link' => get_permalink( $post->ID ) );
        } elseif ( is_singular() && ! is_front_page() ) {
            $singular_bread_crumb_arr = $this->get_singular_bread_crumb_array( $post, $args );
            $bread_crumb_arr = array_merge( $bread_crumb_arr, $singular_bread_crumb_arr );
            $bread_crumb_arr[] = array( 'title' => $post->post_title, 'link' => get_permalink( $post->ID ) );
        } elseif ( is_category() ) {
            global $cat;

            $category = get_category( $cat );
            if ($category->parent != 0) {
                $ancestors = array_reverse( get_ancestors( $category->term_id, 'category' ) );
                foreach ($ancestors as $ancestor_id) {
                    $ancestor = get_category( $ancestor_id );
                    $bread_crumb_arr[] = array( 'title' => $ancestor->name, 'link' => get_category_link( $ancestor->term_id ) );
                }
            }
            $bread_crumb_arr[] = array( 'title' => sprintf( $args['category_label'], $category->name ), 'link' => get_category_link( $cat ) );
        } elseif ( is_tag() ) {
            global $tag_id;
            $tag = get_tag( $tag_id );
            $bread_crumb_arr[] = array( 'title' => sprintf( $args['tag_label'], $tag->name ), 'link' => get_tag_link( $tag_id ) );
        } elseif ( is_author() ) {
            $author = get_query_var( 'author' );
            $bread_crumb_arr[] = array( 'title' => sprintf( $args['author_label'], get_the_author_meta( 'display_name', get_query_var( 'author' ) ) ), 'link' => get_author_posts_url( $author ) );
        } elseif ( is_day() ) {
            if ( $m = get_query_var( 'm' ) ) {
                $year = substr( $m, 0, 4 );
                $month = substr( $m, 4, 2 );
                $day = substr( $m, 6, 2 );
            } else {
                $year = get_query_var( 'year' );
                $month = get_query_var( 'monthnum' );
                $day = get_query_var( 'day' );
            }
            $month_title = $this->get_month_title( $month );
            $bread_crumb_arr[] = array( 'title' => sprintf( $args['year_label'], $year ), 'link' => get_year_link( $year ) );
            $bread_crumb_arr[] = array( 'title' => sprintf( $args['month_label'], $month_title ), 'link' => get_month_link( $year, $month ) );
            $bread_crumb_arr[] = array( 'title' => sprintf( $args['day_label'], $day ), 'link' => get_day_link( $year, $month, $day ) );
        } elseif ( is_month() ) {
            if ( $m = get_query_var( 'm' ) ) {
                $year = substr( $m, 0, 4 );
                $month = substr( $m, 4, 2 );
            } else {
                $year = get_query_var( 'year' );
                $month = get_query_var( 'monthnum' );
            }
            $month_title = $this->get_month_title( $month );
            $bread_crumb_arr[] = array( 'title' => sprintf( $args['year_label'], $year ), 'link' => get_year_link( $year ) );
            $bread_crumb_arr[] = array( 'title' => sprintf( $args['month_label'], $month_title ), 'link' => get_month_link( $year, $month ) );
        } elseif ( is_year() ) {
            if ( $m = get_query_var( 'm' ) ) {
                $year = substr( $m, 0, 4 );
            } else {
                $year = get_query_var( 'year' );
            }
            $bread_crumb_arr[] = array( 'title' => sprintf( $args['year_label'], $year ), 'link' => get_year_link( $year ) );
        } elseif ( is_post_type_archive() ) {
            $post_type = get_post_type_object( get_query_var( 'post_type' ) );
            $bread_crumb_arr[] = array( 'title' => sprintf( $args['post_type_label'], $post_type->label ), 'link' => get_post_type_archive_link( $post_type->name ) );
        }

        return apply_filters( 'bread_crumb_arr', $bread_crumb_arr, $args );
    }

    private function get_singular_bread_crumb_array( $post, $args )
    {
        $bread_crumb_arr = array();
        $post_type = get_post_type_object( $post->post_type );

        if ($post_type && $post_type->has_archive) {
            $bread_crumb_arr[] = array( 'title' => sprintf( $args['post_type_label'], $post_type->label ), 'link' => get_post_type_archive_link( $post_type->name ) );
        }

        if ( is_post_type_hierarchical( $post_type->name ) ) {
            $ancestors = array_reverse( get_post_ancestors( $post ) );
            if ( count( $ancestors ) ) {
                $ancestor_posts = get_posts( 'post_type=' . $post_type->name . '&include=' . implode( ',', $ancestors ) );
                foreach ($ancestors as $ancestor) {
                    foreach ($ancestor_posts as $ancestor_post) {
                        if ($ancestor == $ancestor_post->ID) {
                            $bread_crumb_arr[] = array( 'title' => apply_filters( 'the_title', $ancestor_post->post_title ), 'link' => get_permalink( $ancestor_post->ID ) );
                        }
                    }
                }
            }
        } else {
            $post_type_taxonomies = get_object_taxonomies( $post_type->name, false );
            if ( is_array( $post_type_taxonomies ) && count( $post_type_taxonomies ) ) {
                foreach ($post_type_taxonomies as $tax_slug => $taxonomy) {
                    if ($taxonomy->hierarchical) {
                        $terms = get_the_terms( $post->ID, $tax_slug );
                        if ($terms) {
                            $term = array_shift( $terms );
                            if ($term->parent != 0) {
                                $ancestors = array_reverse( get_ancestors( $term->term_id, $tax_slug ) );
                                foreach ($ancestors as $ancestor_id) {
                                    $ancestor = get_term( $ancestor_id, $tax_slug );
                                    $bread_crumb_arr[] = array( 'title' => $ancestor->name, 'link' => get_term_link( $ancestor, $tax_slug ) );
                                }
                            }
                            $bread_crumb_arr[] = array( 'title' => $term->name, 'link' => get_term_link( $term, $tax_slug ) );
                            break;
                        }
                    }
                }
            }
        }

        return $bread_crumb_arr;
    }

    private function add_posts_page_array( $bread_crumb_arr )
    {
        if ( is_page() || is_front_page() ) {
            return $bread_crumb_arr;
        } elseif ( is_category() ) {
            $tax = get_taxonomy( 'category' );
            if ( count( $tax->object_type ) != 1 || $tax->object_type[0] != 'post' ) {
                return $bread_crumb_arr;
            }
        } elseif ( is_tag() ) {
            $tax = get_taxonomy( 'post_tag' );
            if ( count( $tax->object_type ) != 1 || $tax->object_type[0] != 'post' ) {
                return $bread_crumb_arr;
            }
        } elseif ( is_tax() ) {
            $tax = get_taxonomy( get_query_var( 'taxonomy' ) );
            if ( count( $tax->object_type ) != 1 || $tax->object_type[0] != 'post' ) {
                return $bread_crumb_arr;
            }
        } elseif ( is_home() && ! get_query_var( 'pagename' ) ) {
            return $bread_crumb_arr;
        } else {
            $post_type = get_query_var( 'post_type' ) ? get_query_var( 'post_type' ) : 'post';
            if ($post_type != 'post') {
                return $bread_crumb_arr;
            }
        }
        if ( get_option( 'show_on_front' ) == 'page' && $posts_page_id = get_option( 'page_for_posts' ) ) {
            $posts_page = get_post( $posts_page_id );
            $bread_crumb_arr[] = array( 'title' => $posts_page->post_title, 'link' => get_permalink( $posts_page->ID ) );
        }

        return $bread_crumb_arr;
    }

    private function sanitize_attr_classes( $classes, $prefix = '' )
    {
        if ( ! is_array( $classes ) ) {
            $classes = preg_replace( '/[^\s\w_-]+/', '', $classes );
            $classes = preg_split( '/[\s]+/', $classes );
        }

        foreach ($classes as $key => $class) {
            if ( is_array( $class ) ) {
                unset( $classes[$key] );
            } else {
                $class = preg_replace( '/[^\w_-]+/', '', $class );
                $class = preg_replace( '/^[\d_-]+/', '', $class );
                if ($class) {
                    $classes[$key] = $prefix . $class;
                }
            }
        }
        $classes = implode( ' ', $classes );

        return $classes;
    }

    private function uniform_boolean( $arg, $default = true )
    {
        if ( is_numeric( $arg ) ) {
            $arg = (int) $arg;
        }
        if ( is_string( $arg ) ) {
            $arg = strtolower( $arg );
            if ($arg == 'false') {
                $arg = false;
            } elseif ($arg == 'true') {
                $arg = true;
            } else {
                $arg = $default;
            }
        }

        return $arg;
    }

    private function get_month_title( $monthnum = 0 )
    {
        global $wp_locale;
        $monthnum = (int) $monthnum;
        $date_format = get_option( 'date_format' );
        if ( in_array( $date_format, array( 'DATE_COOKIE', 'DATE_RFC822', 'DATE_RFC850', 'DATE_RFC1036', 'DATE_RFC1123', 'DATE_RFC2822', 'DATE_RSS' ) ) ) {
            $month_format = 'M';
        } elseif ( in_array( $date_format, array( 'DATE_ATOM', 'DATE_ISO8601', 'DATE_RFC3339', 'DATE_W3C' ) ) ) {
            $month_format = 'm';
        } else {
            preg_match( '/(^|[^\\\\]+)(F|m|M|n)/', str_replace( '\\\\', '', get_option( 'date_format' ) ), $m );
            if ( isset( $m[2] ) ) {
                $month_format = $m[2];
            } else {
                $month_format = 'F';
            }
        }

        switch ($month_format) {
        case 'F' :
            $month = $wp_locale->get_month( $monthnum );
            break;
        case 'M' :
            $month = $wp_locale->get_month_abbrev( $wp_locale->get_month( $monthnum ) );
            break;
        default :
            $month = $monthnum;
        }

        return $month;
    }

} // class end
$prime_strategy_bread_crumb = new prime_strategy_bread_crumb();

if ( ! function_exists( 'bread_crumb' ) ) {
    function bread_crumb( $args = '' )
    {
        global $prime_strategy_bread_crumb;

        return $prime_strategy_bread_crumb->bread_crumb( $args );
    }
}
