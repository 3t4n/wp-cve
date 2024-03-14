<?php
/**
 * Template Loader
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://averta.net/phlox/
 * @copyright  (c) 2010-2023 averta
 */

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;


class Auxpfo_Template_Loader {

    public static function init() {
        add_filter( 'template_include' , array( __CLASS__, 'template_loader' ) );
    }

    /**
     * Load a template.
     *
     * @param mixed $template
     * @return string
     */
    public static function template_loader( $template ) {
        $find = array();
        $file = '';

        if ( is_embed() ) {
            return $template;
        }


        if ( is_single() && get_post_type() == 'portfolio' ) {

            $find[] = AUXPFO()->template_path() . 'single-portfolio.php';

        } elseif ( is_tax( get_object_taxonomies( 'portfolio' ) ) ) {

            $term   = get_queried_object();

            if ( is_tax( 'portfolio-cat' ) || is_tax( 'portfolio-tag' ) ) {
                $file = 'taxonomy-' . $term->taxonomy . '.php';
            } elseif ( !is_search() ) {
                $file = 'archive-portfolio.php';
            }

            $find[] = AUXPFO()->template_path() . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
            $find[] = AUXPFO()->template_path() . 'taxonomy-' . $term->taxonomy . '.php';
            $find[] = AUXPFO()->template_path() . $file;

        } elseif ( is_post_type_archive( 'portfolio' ) && !is_search() ) {

            $find[] = AUXPFO()->template_path() . 'archive-portfolio.php';
        }

        $find      = array_unique( $find );

        if ( $find && $templates = locate_template( array_unique( $find ) ) ) {
            return $templates;
        }

        foreach ( $find as $file ) {
            if( file_exists( $file ) ){
                $template = $file;
                break;
            }
        }

        return $template;
    }

}

Auxpfo_Template_Loader::init();
