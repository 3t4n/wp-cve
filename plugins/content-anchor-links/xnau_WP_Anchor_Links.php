<?php

/**
 * supplies anchor links to the WP linker
 *
 * @package    WordPress
 * @subpackage Participants Database Plugin
 * @author     Roland Barker <webdesign@xnau.com>
 * @copyright  2016  xnau webdesign
 * @license    GPL3
 * @version    0.5
 * @link       http://xnau.com/wordpress-plugins/
 * @depends    
 */
class xnau_WP_Anchor_Links {

  /**
   * @var string  the post content
   */
  private $post_content;

  /**
   * @var int the post ID
   */
  private $post_id;

  /**
   * @var array of content ids 
   */
  private $content_id_list;

  /**
   * sets up the class
   */
  private function __construct()
  {
    $this->setup_post();
  }

  /**
   * supplies a list of named anchors
   * 
   * @param string  $results
   * @return string
   */
  public static function get_content_anchors( $results, $query )
  {
    if ( ! isset( $query['s'] ) ) {
      return $results;
    }
    
    $headings = new self();

    $anchor_title = __( 'Anchor' );

    foreach ( $headings->content_id_list( $query['s'] ) as $id => $title ) {
      $results[] = array(
          'ID' => '',
          'title' => $title,
          'permalink' => '#' . $id,
          'info' => $anchor_title,
      );
    }

    return $results;
  }

  /**
   * gets the post's ID list from the transient
   */
  private function get_post_ids()
  {
    $all_post_ids = get_transient( xnau_WP_Headings_IDs::id_list_transient );
    $this->content_id_list = is_array( $all_post_ids ) ? $all_post_ids[$this->post_id] : array();
  }

  /**
   * gets the current post in an admin AJAX call
   * 
   * @param int $post_id
   * @return null
   */
  private function setup_post()
  {
    $url = parse_url( filter_var( $_SERVER['HTTP_REFERER'], FILTER_SANITIZE_URL ) );
    parse_str( $url['query'], $vars );
    $this->post_id = $vars['post'];
    $this->get_post_ids();
  }
  
  /**
   * supplies the filtered list of anchor ids
   * 
   * @param string  $search term
   * @return array
   */
  private function content_id_list( $search ) {
    $terms = explode(' ', $search);
    /*
     * if there is no search term or there are too many, just provide the whole list
     */
    if ( empty( $terms ) || count( $terms ) > 2 ) {
      return $this->content_id_list;
    }
    
    $return = array();
    if ( is_array( $this->content_id_list ) ) {
      foreach ( $this->content_id_list as $id => $title ) {
        foreach ( $terms as $term ) {
          if ( stripos( $id, $term ) !== false ) {
            $return[$id] = $title;
          }
        }
      }
    }
    return $return;
  }

}
