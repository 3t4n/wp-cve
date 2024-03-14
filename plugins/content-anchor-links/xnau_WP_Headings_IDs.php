<?php

/**
 * adds unique IDs to content headings
 *
 * @package    WordPress
 * @subpackage Participants Database Plugin
 * @author     Roland Barker <webdesign@xnau.com>
 * @copyright  2016  xnau webdesign
 * @license    GPL3
 * @version    0.7
 * @link       https://xnau.com/content-anchor-links/
 * @depends    
 */
class xnau_WP_Headings_IDs {

  /**
   * @var array of existing IDs
   */
  private $content_id_list = array();

  /**
   * @var string the current content
   */
  private $content;
  
  /**
   * @var int the current post ID
   */
  private $post_id;

  /**
   * @var int max length of the id
   */
  const id_length = 30;
  
  /**
   * @var string name of the id list transient
   */
  const id_list_transient = 'content-anchor-links';

  /**
   * @param string $content the incoming content
   * @patam int $post_id
   */
  private function __construct( $content, $post_id )
  {
    $this->content = $content;
    $this->post_id = $post_id;
    $this->element_id_list();
    
    // add fallback id generation if mb_string not available
    if ( !function_exists( 'mb_convert_encoding' ) ) {
      add_filter( 'content-anchor-links_id_string', function ( $string ) {
        return sanitize_title_with_dashes( $string, null, 'save' );
      });
    }
  }

  /**
   * supplies the content with heading ID attributes added
   * 
   * @param string  $content
   * @patam int $post_id
   * @return string modified content
   */
  public static function add_heading_ids( $content, $post_id )
  {
//    error_log(__METHOD__.' post id: '.$post_id.' content: '.$content);
    $headings = new self( $content, $post_id );
    return $headings->add_anchors_to_headings();
  }

  /**
   * adds ids to headers in the content
   * 
   * this adds anchors to all h2, h3 headings, can be expanded to others if needed
   * 
   * @return string the modified content
   */
  private function add_anchors_to_headings()
  {
    // pattern to select all headings without ids
    $pattern = apply_filters( 'content-anchor-links-headings_regex', '%<(?<tag>h[1-6])(?![^>]*id="[^>]*)(?<atts>[^>]*)>(?<content>.+?)</\1>%s' );

    // now run the pattern and callback function on content
    // and process it through a function that replaces the title with an id 
    $content = preg_replace_callback( $pattern, array($this, 'place_ids'), $this->content );
    
    // store the assembled ist of ids to the transient
    $this->update_transient();
    
    return $content;
  }

  /**
   * places the ID in the heading
   * 
   * @param array $matches the match array
   * @return string the new heading
   */
  public function place_ids( $matches )
  {
    $slug = esc_attr( $this->unique_id( $this->make_slug( $matches['content'] ) ) );
    
    // add the new slug to the id list
    $this->add_id_to_list( $this->make_title( $matches ), $slug );
    
    return '<' . $matches['tag'] . ' id="' . $slug . '" ' . $matches['atts'] . '>' . $matches['content'] . '</' . $matches['tag'] . '>';
  }
  
  /**
   * creates an anchor link title
   * 
   * @param array $matches the regex matches array
   * @return string
   */
  private function make_title( $matches )
  {
    return strip_tags( $matches['content'] ) . ' (' . $matches['tag'] . ')';
  }
  
  /**
   * makes a slug out of a title
   * 
   * @param string $content the title or content of the element
   * 
   * @return string the derived slug
   */
  private function make_slug( $content )
  {
    $title = $this->prepare_slug($content);
    
    if ( empty( $title ) ) {
      $title = uniqid('anchor-');
    }
    /**
     * @since 1.3
     * 
     * skipped using the WP func sanitize_title_with_dashes becsause it encodes 
     * utf-8 characrers needlessly
     */
    //return substr( sanitize_title_with_dashes( $title ), 0, self::id_length );
    return substr( $title, 0, self::id_length );
  }
  
  /**
   * prepares a slug string
   * 
   * @param string  the raw heading content
   * 
   * @return string prepared for use as an ID attribute
   */
  private function prepare_slug( $content )
  {
    /**
     * @filter content-anchor-links_id_string
     * 
     * allows for an alternate id string prep method
     */
    if ( has_filter( 'content-anchor-links_id_string' ) ) {
      $title = apply_filters('content-anchor-links_id_string', $content );
    } else {
      /*
       * string preparation sequence:
       * 
       * 1. URL-encoded characters are decoded
       * 2. tags are stripped out
       * 3. convert to lowercase
       * 4. string is converted to utf-8
       * 5. all whitespace is replaced with hyphens
       * 6. strip out all non-printing characters
       * 7. remove any leading numerals for HTML4 comptatibility
       * 8. strip out any punctuation
       * 
       */
      $title = preg_replace( 
              array( '/\s/', '/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '/^[0-9]*/', '/[.,~`!@#$%^&*()+=?]/' ), 
              array( '-', '', '', '' ), 
              mb_convert_encoding( strtolower( strip_tags( urldecode( $content ) ) ), 'UTF-8', 'UTF-8') );
    }
    return $title;
  }

  /**
   * prepares a unique id string
   * 
   * @param string $title the title string
   * @param array $id_list list of existing ids
   * 
   * @return string the unique ID
   */
  private function unique_id( $slug )
  {
    $check = 0;
    $base_slug = $slug;
    while ( $this->is_not_unique( $slug ) && $check < 50 ) {
      $slug = $base_slug . '-' . $check;
      $check++;
    }
    
    return $slug;
  }

  /**
   * checks the slug for uniqueness
   * 
   * @param string $slug the string to test
   * @return bool true if the string is not unique
   */
  private function is_not_unique( $slug )
  {
    return in_array( $slug, array_keys( $this->content_id_list ) );
  }

  /**
   * provides an array of all the element IDs in a block of content
   * 
   * @return array  of found IDs
   */
  private function element_id_list()
  {
    preg_match_all( '%<(?<tag>.+?) id="(?<id>.*?)".*(?:>(?<title>.*)</\1|/>)%', $this->content, $matches );
    
//    error_log(__METHOD__.' matches: '.print_r($matches,1));
    
    foreach ( array_keys( $matches['tag'] ) as $index ) {
      $this->content_id_list[ $matches['id'][$index] ] = ( empty( $matches['title'][$index] ) ? $matches['id'][$index] . ' (' . $matches['tag'][$index] . ')' : $matches['title'][$index] );
    }
  }
  
  /**
   * adds a new ID to the list
   * 
   * @param string  $title
   * @param string $slug the new id
   */
  private function add_id_to_list( $title, $slug )
  {
    $this->content_id_list[$slug] = $title;
  }
  
  /**
   * updates the transient
   * 
   */
  private function update_transient()
  {
    $all_post_id_lists = get_transient(self::id_list_transient);
    
    if ( isset( $all_post_id_lists[ $this->post_id ] ) && is_array( $all_post_id_lists[ $this->post_id ] ) ) {
      $all_post_id_lists[ $this->post_id ] += $this->content_id_list;
    } else {
      $all_post_id_lists[ $this->post_id ] = $this->content_id_list;
    }
    
//    error_log(__METHOD__.' all id lists: '.print_r($all_post_id_lists,1));
    
    set_transient(self::id_list_transient, $all_post_id_lists, DAY_IN_SECONDS);
  }

}
