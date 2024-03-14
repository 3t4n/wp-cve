<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */
namespace Glossary\Frontend\Core;

use  Glossary\Engine ;
/**
 * Engine system that add the tooltips
 */
class Generate_Excerpt extends Engine\Base
{
    /**
     * Settings
     *
     * @var array
     */
    private  $atts = array() ;
    /**
     * Initialize the class
     *
     * @return bool
     */
    public function initialize()
    {
        parent::initialize();
        return true;
    }
    
    /**
     * Get the excerpt by our limit
     *
     * @param array $atts      Various attributes.
     * @return string
     */
    public function get( array $atts )
    {
        $this->atts = $atts;
        $excerpt = $this->get_the_excerpt();
        $excerpt = \strip_shortcodes( $excerpt );
        // We cannot use wp_strip_all_tags because remove HTML lists
        // and span cannot include lists so this is a workaround
        $excerpt = \str_replace( '<li>', '• ', $excerpt );
        $excerpt = \str_replace( '<ul>', '<br>', $excerpt );
        $excerpt = \str_replace( '</li>', '<br>', $excerpt );
        // Code extracted from wp_strip_all_tags
        $excerpt = \preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $excerpt );
        $excerpt = \strip_tags( (string) $excerpt, '<br><sup>' );
        $excerpt = \preg_replace( '/[\\r\\n\\t ]+/', ' ', \trim( $excerpt ) );
        /**
         * Filter the excerpt before printing
         *
         * @param string $excerpt The excerpt.
         * @param string $theid   The ID.
         * @since 1.2.0
         * @return string $excerpt The excerpt filtered.
         */
        $excerpt = \apply_filters( $this->default_parameters['filter_prefix'] . '_excerpt', \strval( $excerpt ), $atts['term_ID'] );
        $readmore = $this->noreadmore( $atts );
        return $this->elaborate_the_excerpt( $excerpt ) . $readmore;
    }
    
    /**
     * Get the excerpt
     *
     * @return string
     */
    public function get_the_excerpt()
    {
        //phpcs:ignore SlevomatCodingStandard.Complexity.Cognitive.ComplexityTooHigh
        $excerpt = '';
        if ( isset( $this->atts['excerpt'] ) ) {
            $excerpt = $this->atts['excerpt'];
        }
        
        if ( isset( $this->atts['term_ID'] ) ) {
            $theid = $this->atts['term_ID'];
            
            if ( \is_numeric( $theid ) ) {
                $term = \get_post( (int) $theid );
                
                if ( !\is_null( $term ) ) {
                    $excerpt = \trim( $term->post_excerpt );
                    if ( empty($excerpt) ) {
                        $excerpt = $term->post_content;
                    }
                }
            
            }
        
        }
        
        return $excerpt;
    }
    
    /**
     * Elaborate the excerpt based on the settings
     *
     * @param string $excerpt  The excerpt.
     * @return string
     */
    public function elaborate_the_excerpt( string $excerpt )
    {
        $excerpt = $this->limit_excerpt( $excerpt );
        $excerpt = \str_replace( array(
            '<b...',
            '<...',
            '<br...',
            '<br>...'
        ), '...', $excerpt );
        return \trim( $excerpt );
    }
    
    /**
     * Generate the read more link
     *
     * @param array $atts Various Attributes.
     * @return string
     */
    public function noreadmore( array $atts )
    {
        if ( (bool) $atts['noreadmore'] ) {
            return '';
        }
        $text_readmore = \__( 'More', GT_TEXTDOMAIN );
        $readmore = ' <a href="' . $atts['link'] . '">' . $text_readmore . '</a>';
        return $readmore;
    }
    
    /**
     * Limit excerpt based on the settings
     *
     * @param string $excerpt  The excerpt.
     * @return string
     */
    public function limit_excerpt( string $excerpt )
    {
        $excerpt_temp = $excerpt;
        $dots = '...';
        if ( !empty($this->settings['excerpt_dots']) ) {
            $dots = '';
        }
        $excerpt_limit = \absint( $this->settings['excerpt_limit'] );
        
        if ( 0 !== $excerpt_limit ) {
            if ( \strlen( $excerpt ) >= $excerpt_limit ) {
                $excerpt_temp = \substr( $excerpt, 0, $excerpt_limit ) . $dots;
            }
            // Strip the excerpt based on the words or char limit
            
            if ( !empty($this->settings['excerpt_words']) ) {
                $excerpt_temp = $excerpt;
                if ( \str_word_count( $excerpt ) > $excerpt_limit ) {
                    $excerpt_temp = \wp_trim_words( $excerpt, $excerpt_limit, '' ) . $dots;
                }
            }
        
        }
        
        return $excerpt_temp;
    }

}