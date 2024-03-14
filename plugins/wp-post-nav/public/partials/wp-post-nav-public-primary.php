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

//setup the excluded term array.  If yoast primary term is selected we need to only use that for getting terms
$excluded_terms = [];
$primary_term = '';

//get the category the post is in.
$term = $this->get_post_categories();
$post_id = get_the_id();

//get the links
$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post(true, $excluded_terms, true, $term );
$next = get_adjacent_post(true, $excluded_terms, false, $term );

if ($yoast_primary == 'yes') {
  if ( class_exists('WPSEO_Primary_Term') ) {
    // Show the post's 'Primary' category, if the Yoast feature is available, & one is set
    //get the category the post is in.
    $wpseo_primary_term = new WPSEO_Primary_Term($term, $post_id );
    $wpseo_primary_term = $wpseo_primary_term->get_primary_term();
    $primary_term = get_term( $wpseo_primary_term );
    if ( !is_wp_error( $primary_term ) ) {
      
      $excluded_terms = get_terms( array(
            'taxonomy' => $primary_term->taxonomy,
            'hide_empty' => false,
            'exclude' => $primary_term->term_id,
            'fields'   => 'ids'
        ) );

      switch ( $exclude_primary ) {
        case 'yes':
          if ($previous) {
            $prev_wpseo_primary_term = new WPSEO_Primary_Term($term, $previous->ID );
            $prev_wpseo_primary_term = $prev_wpseo_primary_term->get_primary_term();
            $prev_primary_term = get_term( $prev_wpseo_primary_term );
            
            //if no primary term assigned, or its not the same, exit
            if ( is_wp_error( $primary_term ) || $prev_primary_term != $primary_term) {
              $previous = '';
            }
          }

          if ($next) {
            $next_wpseo_primary_term = new WPSEO_Primary_Term($term, $next->ID );
            $next_wpseo_primary_term = $next_wpseo_primary_term->get_primary_term();
            $next_primary_term = get_term( $next_wpseo_primary_term );
           
            //if no primary term assigned, or its not the same, exit
            if ( is_wp_error( $primary_term ) || $next_primary_term != $primary_term) {
              $next = '';
            }
          }
        break;
      }
    }
  }
}

//allow using seo frameowrk primary term
if (function_exists( 'the_seo_framework' )) {
  $primary_term = the_seo_framework()->get_primary_term( $post_id, $term );
  if ( empty( $primary_term ) ) {
    $excluded_terms = get_terms( array(
        'taxonomy' => $primary_term->taxonomy,
        'hide_empty' => false,
        'exclude' => $primary_term->term_id,
        'fields'   => 'ids'
    ) );
    switch ( $exclude_primary ) {
      case 'yes':
        if ($previous) {
          $prev_wpseo_primary_term = new WPSEO_Primary_Term($category, $previous->ID );
          $prev_wpseo_primary_term = $prev_wpseo_primary_term->get_primary_term();
          $prev_primary_term = get_term( $prev_wpseo_primary_term );
          
          //if no primary term assigned, or its not the same, exit
          if ( empty( $primary_term ) || $prev_primary_term != $primary_term) {
            $previous = '';
          }
        }

        if ($next) {
          $next_wpseo_primary_term = new WPSEO_Primary_Term($category, $next->ID );
          $next_wpseo_primary_term = $next_wpseo_primary_term->get_primary_term();
          $next_primary_term = get_term( $next_wpseo_primary_term );

          //if no primary term assigned, or its not the same, exit
          if ( empty( $primary_term ) || $next_primary_term != $primary_term) {
            $next = '';
          }
        }
      break;
      }
    }
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
          if ($term == 'product_cat') {
            $previous_cat = get_the_terms( $previous->ID, 'product_cat' );
          }
          else {
            $previous_cat = get_the_category ($previous->ID);
          }

					if ($primary_term && !is_wp_error( $primary_term )) {
            $previous_category = $primary_term->name;
          }

          elseif ($previous_categories = $previous_cat) {
            $previous_category 	 = $previous_categories[0]->name;
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
					$post_excerpt 	= $this->wp_post_nav_excerpt($previous->ID); 

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

        if ($term == 'product_cat') {
          $next_cat = get_the_terms( $next->ID, 'product_cat' );
        }
        else {
          $next_cat = get_the_category ($next->ID);
        }

        if ($primary_term && !is_wp_error( $primary_term )) {
          $next_category = $primary_term->name;
        }
				elseif ($next_categories = $next_cat) {
          		$next_category 	 = $next_categories[0]->name;
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
            ,true,$excluded_terms,$term );
      echo $prev_link;
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
            ,true,$excluded_terms,$term );
      echo $next_link;
    }		
	echo '</nav>'; 
}
?> 