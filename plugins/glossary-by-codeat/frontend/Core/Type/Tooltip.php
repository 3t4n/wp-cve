<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 2.0+
 * @link      https://codeat.co
 */
namespace Glossary\Frontend\Core\Type;

use  Glossary\Engine ;
use  Glossary\Frontend\Core ;
/**
 * Get the HTML about Tooltips
 */
class Tooltip extends Engine\Base
{
    /**
     * Excerpt generated
     *
     * @var string
     */
    private  $excerpt = '' ;
    /**
     * Tooltip attributes
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
     * Generate a link or the tooltip
     *
     * @param array $atts Parameters.
     * @global object $post The post object.
     * @return array
     */
    public function html( array $atts )
    {
        $class = $this->set_class( $atts['class'] );
        $this->atts = $atts;
        $html = array(
            'value' => '',
        );
        if ( !empty($class) ) {
            $class = ' class="' . $class . '"';
        }
        $excerpt = new Core\Generate_Excerpt();
        $excerpt->initialize();
        $this->excerpt = $excerpt->get( $this->atts );
        $class = $this->default_parameters['css_class_prefix'] . '-link';
        $photo = $this->generate_photo( $this->atts['term_ID'] );
        $terms = \get_the_terms( $this->atts['term_ID'], 'glossary-cat' );
        $tooltip_class = $this->default_parameters['css_class_prefix'] . '-tooltip glossary-term-' . $this->atts['term_ID'];
        if ( !empty($terms) && \is_array( $terms ) ) {
            foreach ( $terms as $term ) {
                $tooltip_class .= ' glossary-cat-' . $term->term_id;
            }
        }
        $html['before'] = '<span class="' . $tooltip_class . '" tabindex="0">' . '<span class="' . $class . '">';
        $tooltip = '</span><span class="hidden ' . $this->default_parameters['css_class_prefix'] . '-tooltip-content clearfix">';
        $tooltip .= $photo;
        $tooltip .= '<span class="' . $this->default_parameters['css_class_prefix'] . '-tooltip-text">' . $this->excerpt . '</span></span></span>';
        /**
         * Filter the HTML generated
         *
         * @param string $tooltip The tooltip.
         * @param string $excerpt The excerpt.
         * @param string $photo   Photo.
         * @param string $post    The post object.
         * @param string $noreadmore The internal html link.
         * @since 1.2.0
         * @return string $html The tooltip filtered.
         */
        $html['after'] = \apply_filters(
            $this->default_parameters['filter_prefix'] . '_tooltip_html',
            $tooltip,
            $this->excerpt,
            $photo,
            $this->atts['term_ID'],
            $this->atts['noreadmore']
        );
        return $html;
    }
    
    /**
     * Generate the thumbnail for the tooltip
     *
     * @param int $theid The ID.
     * @return string
     */
    public function generate_photo( int $theid )
    {
        $theme = \gl_get_settings();
        $photo = '';
        if ( !empty($this->settings['t_image']) ) {
            
            if ( !\in_array( $theme['tooltip_style'], array( 'box', 'line' ), true ) ) {
                $photo = \get_the_post_thumbnail( $theid, apply_filters( $this->default_parameters['filter_prefix'] . '_tooltip_image_size', 'thumbnail' ) );
                if ( !empty($photo) ) {
                    return $photo;
                }
                return '';
            }
        
        }
        return $photo;
    }
    
    /**
     * Return the class of tooltip based on atts and settings
     *
     * @param string $css_class CSS classes.
     * @return string
     */
    public function set_class( string $css_class = '' )
    {
        return $css_class;
    }

}