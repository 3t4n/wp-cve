<?php

/**
 *
 * WP Post Nav default display page.
 *
 * @link:      https://en-gb.wordpress.org/plugins/wp-post-nav/
 * @since      0.0.1
 *
 * @package    wp_post_nav
 * @subpackage wp_post_nav/public/partials
 */
?>

<?php 
// If this file is called directly, abort. //
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

//select if its from the same category
switch ( $same_category ) {
  case 'yes':
    //get the category the post is in.
    $term = $this->get_post_categories();
    //fetch previous and next posts
    $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post(true, '', true, $term );
    $next = get_adjacent_post(true, '', false, $term );
  break;

  default:
    //fetch previous and next posts
    $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_previous_post();
    $next = get_next_post();    
  }

//if there arent any next AND previous posts, leave.
if ( !$previous && !$next) {
    return;
}

//We have posts - lets do this
else {
    
  //get all the information

  //if theres a previous post, get its details
  if ($previous) {       
      //are we showing featured images?
      switch ( $show_featured ) {
        case 'yes':
          if (!$previous_image = get_the_post_thumbnail( $previous->ID, 'thumbnail' )) 
            {
                $previous_image = $fallback;
                $previous_image = '<li class="post-nav-image"><image src="'.$previous_image.'"/></li>';   
            }

          else {
                $previous_image = get_the_post_thumbnail( $previous->ID, 'thumbnail' );
                $previous_image = $previous_image ? '<li class="post-nav-image">' . $previous_image . '</li>' : '';
            }
          break;
        default:
          $previous_image = '';
      }

        //are we showing the post title
      switch ( $show_title ) {
        case 'yes':
          $previous_title = get_the_title( $previous->ID );
          $previous_post_title = 
                                '<li class="post-nav-title">'
                                      .$previous_title.
                                    '</li>';
              break;
            default:
              $previous_post_title = '';
        } 

      //are we showing the post category
      switch ( $show_category ) {
        case 'yes':
          if ($previous_categories = get_the_category ($previous->ID)) {
                $previous_category   = $previous_categories[0]->name;
                }

                else {
                  $previous_category = '';
                }

          $previous_post_category = 
                                '<li class="post-nav-category">'.
                                      __('Category: ', 'wp-post-nav') 
                                      .$previous_category.
                                      '</strong>'.
                                '</li>';
              break;
            default:
              $previous_post_category = '';
           
        }

      //are we showing the post excerpt?
      switch ( $show_excerpt ) {
        case 'yes':
          $post_excerpt   = $this->wp_post_nav_excerpt($previous->ID); //$this->wp_post_nav_excerpt( $previous->ID );

          $previous_post_excerpt = 
                                '<li class="post-nav-excerpt">'
                                    .$post_excerpt.
                                '</li>';
              break;
            default:
              $previous_post_excerpt = '';
           
        }                   
    }

    //if theres a next post, get its details
  if ($next) {       
      //are we showinf featured images?
      switch ( $show_featured ) {
        case 'yes':
            if (!$next_image = get_the_post_thumbnail( $next->ID, 'thumbnail' )) 
                    {
                        $next_image = $fallback;
                        $next_image = '<li class="post-nav-image"><image src="'.$next_image.'"/></li>';   
                    }

                else {
                        $next_image = get_the_post_thumbnail( $next->ID, 'thumbnail' );
                        $next_image = $next_image ? '<li class="post-nav-image">' . $next_image . '</li>' : '';
                }
            break;
            default:
              $next_image = '';
            }

        //are we showing the post title
      switch ( $show_title ) {
        case 'yes':
          $next_title = get_the_title( $next->ID );
          $next_post_title = 
                                '<li class="post-nav-title">'
                                      .$next_title.
                                    '</li>';
              break;
            default:
              $next_post_title = '';
        } 

      //are we showing the post category
      switch ( $show_category ) {
        case 'yes':
          if ($next_categories = get_the_category ($next->ID)) {
                $next_category   = $next_categories[0]->name;
                }

                else {
                  $next_category = '';
                }

          $next_post_category = 
                                '<li class="post-nav-category">'.
                                      __('Category: ', 'wp-post-nav') 
                                      .$next_category.
                                      '</strong>'.
                                '</li>';
              break;
            default:
              $next_post_category = '';
           
        }

      //are we showing the post excerpt?
      switch ( $show_excerpt ) {
        case 'yes':
          $post_excerpt = $this->wp_post_nav_excerpt( $next->ID );

          $next_post_excerpt = 
                                '<li class="post-nav-excerpt">'
                                    .$post_excerpt.
                                '</li>';
              break;
            default:
              $next_post_excerpt = '';
           
        }                   
    }

    //lets build the nav links             
  echo '<nav class="wp-post-nav" role="navigation">'; 
  //use a different query if the same category checkbox is checked
    
  switch ( $same_category ) {
    case 'yes':
        if ($previous) {
              
              $prev_link = previous_post_link( 
                    '%link', 
                    '<ul id="post-nav-previous'.$switch_nav.'">'
                    . '<h4>' . __('Previous Post', 'wp-post-nav') . '</h4>'
                    .$previous_image
                    .$previous_post_title
                    .$previous_post_category
                    .$previous_post_excerpt. 
                    '<span id="post-nav-previous-button"></span></ul>'
                    ,true,'',$term );
              }

          if ($next) {
              
              $next_link = next_post_link( 
                  '%link', 
                    '<ul id="post-nav-next'.$switch_nav.'">'.
                    '<span id="post-nav-next-button"></span>'
                    . '<h4>' . __('Next Post', 'wp-post-nav') . '</h4>'
                    .$next_image
                    .$next_post_title
                    .$next_post_category
                    .$next_post_excerpt. 
                    '</ul>'
                    ,true,'',$term );
              }
    break;
    default:
      if ($previous) {
                  
              $prev_link = previous_post_link( 
                    '%link', 
                    '<ul id="post-nav-previous'.$switch_nav.'">'
                    . '<h4>' . __('Previous Post', 'wp-post-nav') . '</h4>'
                    .$previous_image
                    .$previous_post_title
                    .$previous_post_category
                    .$previous_post_excerpt. 
                    '<span id="post-nav-previous-button"></span></ul>'
                    ,false,
                    '');
              }
          if ($next) {
          
            $next_link = next_post_link( 
                  '%link', 
                    '<ul id="post-nav-next'.$switch_nav.'">'.
                    '<span id="post-nav-next-button"></span>'
                    . '<h4>' . __('Next Post', 'wp-post-nav') . '</h4>'
                    .$next_image
                    .$next_post_title
                    .$next_post_category
                    .$next_post_excerpt. 
                    '</ul>'
                    ,false,
                    ''
                    );
            }   
    }
        
        
        if ($previous) {
            echo $prev_link;
        }
        if ($next) {
            echo $next_link;
        }
  echo '</nav>'; 
}
?>
   