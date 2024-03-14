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
 * Process the content to inject tooltips
 */
class Term_Injector extends Engine\Base
{
    /**
     * Terms to parse
     *
     * @var array
     */
    private  $terms = array() ;
    /**
     * Terms found to insert
     *
     * @var array
     */
    private  $terms_to_inject = array() ;
    /**
     * Terms already added
     *
     * @var array
     */
    private  $already_found = array() ;
    /**
     * List of ignore area
     *
     * @var array
     */
    private  $ignore_area = array() ;
    /**
     * HTML_TYPE_Injector class
     *
     * @var object
     */
    private  $type_injector ;
    /**
     * Text to inject
     *
     * @var string
     */
    private  $text = '' ;
    /**
     * Initialize the class.
     *
     * @since 1.0.0
     * @return bool
     */
    public function initialize()
    {
        $this->ignore_area = array();
        $this->terms = array();
        $this->already_found = array();
        $this->type_injector = new HTML_Type_Injector();
        return true;
    }
    
    /**
     * Return the array list of terms found
     *
     * @return array
     */
    public function get_terms_injected()
    {
        return $this->terms_to_inject;
    }
    
    /**
     * Wrap the string with a tooltip/link.
     *
     * @param string $text  The string to find.
     * @param array  $terms The list of links.
     * @return string
     */
    public function do_wrap( string $text, array $terms )
    {
        //phpcs:ignore SlevomatCodingStandard.Complexity.Cognitive.ComplexityTooHigh
        
        if ( !empty($text) && !empty($terms) ) {
            $this->terms = $terms;
            $this->text = \trim( $text );
            $this->terms_to_inject = array();
            if ( !\defined( 'ELEMENTOR_VERSION' ) && !\function_exists( 'et_setup_theme' ) && !class_exists( 'ACF' ) ) {
                $this->already_found = array();
            }
            $this->regex_match();
            $this->replace_with_utf_8();
            if ( !empty($this->terms_to_inject) ) {
                // This eventually remove broken UTF-8
                return (string) \iconv( 'UTF-8', 'UTF-8//IGNORE', $this->text );
            }
        }
        
        return $text;
    }
    
    /**
     * Find terms with the regex
     *
     * @return array The list of terms finded in the text.
     */
    public function regex_match()
    {
        foreach ( $this->terms as $term ) {
            try {
                $this->create_html_pair( $term );
            } catch ( \Throwable $error ) {
                \error_log(
                    // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log, Squiz.PHP.DiscouragedFunctions.Discouraged -- In few cases was helpful on debugging.
                    $error->getMessage() . ' at ' . $error->getFile() . ':' . $error->getLine() . ', regex:' . $term['regex']
                );
            }
        }
        return $this->terms_to_inject;
    }
    
    /**
     * Inject based on the settings
     *
     * @param array $term List of terms.
     * @return void
     */
    public function create_html_pair( array $term )
    {
        $matches = array();
        if ( !\preg_match_all(
            $term['regex'],
            $this->text,
            $matches,
            PREG_OFFSET_CAPTURE
        ) ) {
            return;
        }
        $this->type_injector->initialize();
        $html_generated = array();
        foreach ( $matches[0] as $match ) {
            list( $term['replace'], $text_found ) = $match;
            if ( $this->is_already_found( $text_found, $term ) ) {
                continue;
            }
            if ( !isset( $html_generated[$text_found] ) ) {
                $html_generated[$text_found] = '';
            }
            $html_generated[$text_found] = $this->add_term( $term, $text_found, $html_generated[$text_found] );
            if ( \gl_get_bool_settings( 'first_occurrence' ) ) {
                break;
            }
        }
    }
    
    /**
     * Add term to index
     *
     * @param array  $term The term.
     * @param int    $text_found Text found.
     * @param string $html_generated Tooltip generated.
     * @return string
     */
    public function add_term( array $term, int $text_found, string $html_generated )
    {
        if ( empty($html_generated) ) {
            $html_generated = $this->type_injector->html( $term );
        }
        $this->terms_to_inject[$text_found] = array(
            $term['long'],
            $html_generated,
            $term['replace'],
            $term['term_ID']
        );
        $this->already_found[$text_found] = $text_found + $term['long'];
        return $html_generated;
    }
    
    /**
     * Is already find
     *
     * @param int   $text_found Found.
     * @param array $term       Term data.
     * @return bool
     */
    public function is_already_found( int $text_found, array $term )
    {
        // Avoid annidate detection
        foreach ( $this->already_found as $previous_init => $previous_end ) {
            if ( !\is_numeric( $previous_init ) ) {
                continue;
            }
            if ( $previous_init <= $text_found && $text_found + $term['long'] <= $previous_end ) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Replace the terms with the link or tooltip with UTF-8 support
     *
     * @return string The new text.
     */
    public function replace_with_utf_8()
    {
        if ( empty($this->terms_to_inject) ) {
            return '';
        }
        \uksort( $this->terms_to_inject, 'strnatcmp' );
        // Copy of text is required for replace
        $new_term_length = $new_end_position_previous_term = 0;
        $new_text = $this->text;
        $old_end_position_previous_term = 0;
        foreach ( $this->terms_to_inject as $original_position => $term ) {
            list( $original_term_length, $term_value, $value ) = $term;
            $new_term_length = \gl_get_len( $term_value );
            // If first word but not at the top keep going
            $insert_position = $original_position;
            // If not first word
            
            if ( $new_end_position_previous_term !== 0 ) {
                $difference_between_old_words = $original_position - $old_end_position_previous_term;
                $insert_position = $new_end_position_previous_term + $difference_between_old_words;
            }
            
            $new_end_position_previous_term = $insert_position + $new_term_length;
            // If first word is at the top set X axis
            
            if ( $original_position === 0 ) {
                $new_end_position_previous_term = $new_term_length;
                $insert_position = $original_position;
            }
            
            $old_end_position_previous_term = $original_term_length + $original_position;
            // 0 is the term long, 1 is the new html
            $new_text = \substr_replace(
                $new_text,
                $term_value,
                $insert_position,
                $this->get_real_length( $value, $original_term_length )
            );
        }
        $this->text = $new_text;
        return $this->text;
    }
    
    /**
     * Check encoding to calculate the real length
     *
     * @param string $value  Text.
     * @param int    $length Original length.
     * @return int
     */
    public function get_real_length( string $value, int $length )
    {
        $encode = \mb_detect_encoding( $value );
        // With utf-8 character with multiple bits this is the workaround for the right value
        if ( 'ASCII' === $encode ) {
            return $length;
        }
        if ( !\gl_text_is_rtl( $this->text ) ) {
            return $length;
        }
        return $length + $length;
    }

}