<?php

/**
 * WP Post Nav public functionality.
 *
 * @link:       https://en-gb.wordpress.org/plugins/wp-post-nav/
 * @since      0.0.1
 *
 * @package    wp_post_nav
 * @subpackage wp_post_nav/includes
 */

// If this file is called directly, abort. //
if ( ! defined( 'ABSPATH' ) ) {
  exit;
} 


class wp_post_nav_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.0.1
	 * @var      string    $name       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {

		$this->name = $name;
		$this->version = $version;

		//load the next / previous navigation
		add_action('wp_footer', array ($this, 'display_wp_post_nav'));
		//add the shortcode for shortcode display
		add_shortcode ('wp_post_nav', array ($this, 'wp_post_nav_shortcode_display'));
	}

	/**
	 * Register the stylesheets for the front end.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_styles() {

		/*
		* Enqueue the public styles.
		 */
		wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/wp-post-nav-public.css', array(), $this->version, 'all' );
		$settings = $this->wp_post_nav_get_settings();

		$nav_background     = $settings['wp_post_nav_background_color'];
		$nav_button_width   = $settings['wp_post_nav_nav_button_width'].'px';
		$nav_button_height  = $settings['wp_post_nav_nav_button_height'].'px';
		$nav_button_offset  = '-'.$nav_button_width;

		$nav_open_background= $settings['wp_post_nav_open_background_color'];

		$nav_heading_colour = $settings['wp_post_nav_heading_color'];
		$nav_heading_size   = $settings['wp_post_nav_heading_size'] . 'px';
		$nav_title_colour   = $settings['wp_post_nav_title_color'];
		$nav_font_size      = $settings['wp_post_nav_title_size'] . 'px';
		$nav_category_colour= $settings['wp_post_nav_category_color'];
		$nav_category_size  = $settings['wp_post_nav_category_size'] . 'px';
		$nav_excerpt_colour = $settings['wp_post_nav_excerpt_color'];
		$nav_excerpt_size 	= $settings['wp_post_nav_excerpt_size'] . 'px';

		$nav_css = ".wp-post-nav #post-nav-previous-default,
								.wp-post-nav #post-nav-previous-switched {
						    	background: $nav_background;
								}

								.wp-post-nav #post-nav-previous-default #post-nav-previous-button {
								  background:$nav_background;
								  line-height: $nav_button_height;
								  width: $nav_button_width;
								  height: $nav_button_height;
								  right: $nav_button_offset;
								}

								.wp-post-nav #post-nav-previous-switched #post-nav-previous-button {
									background:$nav_background;
								  line-height: $nav_button_height;
								  width: $nav_button_width;
								  height: $nav_button_height;
								  left: $nav_button_offset;
								}

								.wp-post-nav #post-nav-previous-default:hover,
								.wp-post-nav #post-nav-previous-switched:hover {
									background:$nav_open_background;
								}

								.wp-post-nav #post-nav-previous-default:hover #post-nav-previous-button,
								.wp-post-nav #post-nav-previous-switched:hover #post-nav-previous-button {
									background:$nav_open_background;
								}

								.wp-post-nav #post-nav-next-default,
								.wp-post-nav #post-nav-next-switched {
									background: $nav_background;
								}

								.wp-post-nav #post-nav-next-default #post-nav-next-button {
									background:$nav_background;
									line-height: $nav_button_height;
									width: $nav_button_width;
									height: $nav_button_height;
									left: $nav_button_offset;
								}

								.wp-post-nav #post-nav-next-switched #post-nav-next-button {
									background:$nav_background;
									line-height: $nav_button_height;
									width: $nav_button_width;
									height: $nav_button_height;
									right: $nav_button_offset;
								}

								.wp-post-nav #post-nav-next-default:hover,
								.wp-post-nav #post-nav-next-switched:hover {
									background:$nav_open_background;
								}

								.wp-post-nav #post-nav-next-default:hover #post-nav-next-button,
								.wp-post-nav #post-nav-next-switched:hover #post-nav-next-button {
									background:$nav_open_background;
								}

								.wp-post-nav h4 {
									text-align:center;
									font-weight:600;
								  color:$nav_heading_colour;
								  font-size:$nav_heading_size;
								}

								.wp-post-nav .post-nav-title {
								  color:$nav_title_colour;
								  font-size:$nav_font_size;
								}

								.wp-post-nav .post-nav-category {
									color:$nav_category_colour;
									font-size:$nav_category_size;
								}

								.wp-post-nav .post-nav-excerpt {
									color:$nav_excerpt_colour;
									font-size:$nav_excerpt_size;
								}

								.wp-post-nav #attachment-post-nav-previous-default {
									background: $nav_background;
									color:$nav_title_colour;
								}

								.wp-post-nav #attachment-post-nav-previous-default:after {
									background:$nav_background;
									line-height: $nav_button_height;
									width: $nav_button_width;
									height: $nav_button_height;
									right: $nav_button_offset;
								}

								@media only screen and 
								(max-width: 48em) {
								  .wp-post-nav #post-nav-next-default .post-nav-title,
								  .wp-post-nav #post-nav-next-switched .post-nav-title {
								    color:$nav_title_colour; 
								  }

								  .wp-post-nav #post-nav-previous-default .post-nav-title,
								  .wp-post-nav #post-nav-previous-switched .post-nav-title {
								    color:$nav_title_colour;
								  }       
								}

								.wp-post-nav-shortcode {
									display:inline-flex;
									background: $nav_background;
									margin:10px auto;
								}

								.wp-post-nav-shortcode ul {
									list-style-type:none;
								}

								.wp-post-nav-shortcode h4 {
									text-align:left;
									font-weight:600;
								  color:$nav_heading_colour;
								  font-size:$nav_heading_size;
								  margin-bottom:3px;
								}

								.wp-post-nav-shortcode hr {
									margin:5px auto;
								}

								.wp-post-nav-shortcode .post-nav-title {
								  color:$nav_title_colour;
								  font-size:$nav_font_size;
								}

								.wp-post-nav-shortcode .post-nav-category {
									color:$nav_category_colour;
									font-size:$nav_category_size;
								}

								.wp-post-nav-shortcode .post-nav-excerpt {
									color:$nav_excerpt_colour;
									font-size:$nav_excerpt_size;
								}

								.wp-post-nav-shortcode #attachment-post-nav-previous-default {
									background: $nav_background;
									color:$nav_title_colour;
								}

								.wp-post-nav-shortcode #attachment-post-nav-previous-default:after {
									background:$nav_background;
									line-height: $nav_button_height;
									width: $nav_button_width;
									height: $nav_button_height;
									right: $nav_button_offset;
								}
								";

		wp_add_inline_style( $this->name, $nav_css );

	}

	/**
	 * Register the JavaScript for the front end.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_scripts() {

		/*
		* Enqueue the public scripts.  Not used in Version 0.0.1 so we dont enqueue it
		 */

		//wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/wp-post-nav-public.js', array( 'jquery' ), $this->version, FALSE );

	}

	//get all the settings from the admin panel and build an array of the options
	public function wp_post_nav_get_settings() {
	  $settings = get_option ('wp_post_nav_options');
    return $settings;
	}

	//get the post categories for the current displayed post type
	public function get_post_categories() {
		$category =  get_queried_object();
    $category_post_type = $category->post_type;
    $taxonomies = get_object_taxonomies($category_post_type);

    switch ( $category_post_type ) {
		case 'product':
        	$term = 'product_cat';
		break;

		default:
			if ( $taxonomies ){
				$i = 0;
				foreach( $taxonomies as $terms ) {
				  //we only want the first term in the array so bail after getting it
				  if ($i == 0) {
				    $term = $terms;
				  }
				  $i++;
			  } 
			}
	    else {
	    	$term = false;
	    }
	}
    return $term; 
	}

	//create the excerpt function
	public function wp_post_nav_excerpt($id) {
		$settings = $this->wp_post_nav_get_settings();
			
			if (array_key_exists('wp_post_nav_excerpt_length', $settings)) {
		    $excerpt_length = $settings['wp_post_nav_excerpt_length'];    
		  }

		  //allow devcelopers to override how this is done
		  $over_ride = true;
		  $over_ride = apply_filters( 'wp-post-nav-excerpt', $over_ride);

		  //if the developer hasnt overridden this, use post content and allow altering it
		  if ($over_ride == true ) {

		    $content = get_post($id);
				$excerpt = $content->post_content;
				$excerpt = strip_tags($excerpt);
				$excerpt = substr($excerpt, 0, $excerpt_length);
				$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
				$excerpt = $excerpt;
			}

			//use wordpress default built in post excerpt function
			else {
				$excerpt = get_the_excerpt($id);
			}

		return $excerpt;
	}

	//the main call to make the WP Post Nav display
	public function display_wp_post_nav() {
    
    $settings = $this->wp_post_nav_get_settings();

    if (array_key_exists('wp_post_nav_shortcode', $settings)) {
			return;
    }
    elseif (array_key_exists('wp_post_nav_post_types', $settings)) {
	    $post_types = $settings["wp_post_nav_post_types"];
	  }
	  else {
	  	return;
	  }
	  
		if (array_key_exists('wp_post_nav_same_category', $settings)) {
			$same_category = 'yes';  
    }
    else {
    	$same_category = 'no';
    }
		
		if (array_key_exists('wp_post_nav_switch_nav', $settings)) {
			$switch_nav = '-switched';   
    }
    else {
    	$switch_nav = '-default';
    }

		if (array_key_exists('wp_post_nav_show_title', $settings)) {
			$show_title = 'yes';  
    }
    else {
    	$show_title = 'no';
    }
		
		if (array_key_exists('wp_post_nav_show_category', $settings)) {
			$show_category = 'yes';  
    }
    else {
    	$show_category = 'no';
    }

		if (array_key_exists('wp_post_nav_show_post_excerpt', $settings)) {
			$show_excerpt = 'yes';    
    }
    else {
    	$show_excerpt = 'no';
    }

		if (array_key_exists('wp_post_nav_show_featured_image', $settings)) {
			$show_featured = 'yes'; 
    }
    else {
    	$show_featured = 'no';
    }

		if (array_key_exists('wp_post_nav_fallback_image', $settings)) {
			$fallback = $settings["wp_post_nav_fallback_image"];
		}

		//add in the additional options for yoast and woocommerce
		if ( class_exists('WPSEO_Primary_Term') && array_key_exists('wp_post_nav_yoast_seo', $settings)) {
			$yoast_primary = 'yes';
		}
		else {
			$yoast_primary = 'no';
		}

		//add additional option for seo framework
		if (function_exists( 'the_seo_framework' ) && array_key_exists('wp_post_nav_seo_framework', $settings)) {
			$seo_framework = 'yes';
		}
		else {
			$seo_framework = 'no';
		}

		if (class_exists('WPSEO_Primary_Term') && array_key_exists('wp_post_nav_exclude_primary', $settings) || function_exists( 'the_seo_framework' ) && array_key_exists('wp_post_nav_exclude_primary', $settings)) {
			$exclude_primary = 'yes';
		}
		else {
			$exclude_primary = 'no';
		}

		if (array_key_exists('wp_post_nav_out_of_stock', $settings) && 'product' == get_post_type()) {
			//add the product filter for out of stock products, only if its not already loaded
		  if ( !has_filter( 'get_previous_post_where', array ($this,'wppostnav_outofstock' )) ) {
		  	add_filter( 'get_previous_post_where', array($this,'wppostnav_outofstock' ));

		  }

		  if ( !has_filter( 'get_next_post_where', array($this,'wppostnav_outofstock' )) ) {
		  	add_filter( 'get_next_post_where', array($this,'wppostnav_outofstock' ));
		  }
		}

    //If there are no post types selected or were not on a singular post type page were allowing, exit.  Also exclude home page and blog pages and all archives
    if (!$post_types || !in_array(is_singular($post_types), $post_types) || is_home() || is_front_page() || is_post_type_archive()) {
      return;
    }

    //if using primary categories, load the custom template
    if ($yoast_primary == 'yes' || $seo_framework == 'yes') {
    	include_once( 'partials/wp-post-nav-public-primary.php' );
    }
    //load the normal templates
    else {
    
	    $current_page = get_queried_object();
			$current = $current_page->post_type;

	    //switch the display depending on which type of post is displayed
	    switch ( $current ) {
				case 'page':
		      include_once( 'partials/wp-post-nav-public-page.php' );
				break;

				case 'post':
		      include_once( 'partials/wp-post-nav-public-post.php' );
				break;

				case 'attachment':
					include_once( 'partials/wp-post-nav-public-attachment.php' );
				break;

				case 'product':
					include_once( 'partials/wp-post-nav-public-product.php' );
				break;
				//used to show on custom post types
				default:
					include_once( 'partials/wp-post-nav-public-default.php' );		
			}
		}   
	}

	/*create the out of stock function for woocommerce settings*/
	public function wppostnav_outofstock($where) {
    global $wpdb;
    return $where . " AND p.ID IN ( 
                      SELECT p.ID FROM $wpdb->posts p 
                      LEFT JOIN $wpdb->postmeta m ON p.ID = m.post_id 
                      WHERE m.meta_key = '_stock_status' 
                      AND m.meta_value = 'instock' )";
	}

	/*Create a shortcode for displaying the navigation where they want*/
	public function wp_post_nav_shortcode_display ($atts) {

		//Dont show if its an archive page
    if (is_home() || is_front_page() || is_post_type_archive()) {
      return;
    }

    extract(shortcode_atts(array(
     'display_previous' => 'true',
     'display_next'     => 'true',
    ), $atts));

    $settings = $this->wp_post_nav_get_settings();

		if (array_key_exists('wp_post_nav_same_category', $settings)) {
			$same_category = true;  
    }
    else {
    	$same_category = false;
    }
		
		if (array_key_exists('wp_post_nav_show_title', $settings)) {
			$show_title = 'yes';  
    }
    else {
    	$show_title = 'no';
    }
		
		if (array_key_exists('wp_post_nav_show_category', $settings)) {
			$show_category = 'yes';  
    }
    else {
    	$show_category = 'no';
    }

		if (array_key_exists('wp_post_nav_show_post_excerpt', $settings)) {
			$show_excerpt = 'yes';    
    }
    else {
    	$show_excerpt = 'no';
    }

		if (array_key_exists('wp_post_nav_show_featured_image', $settings)) {
			$show_featured = 'yes'; 
    }
    else {
    	$show_featured = 'no';
    }

		if (array_key_exists('wp_post_nav_fallback_image', $settings)) {
			$fallback = $settings["wp_post_nav_fallback_image"];
		}

		//add in the additional options for yoast and woocommerce
		if (array_key_exists('wp_post_nav_yoast_seo', $settings)) {
			$yoast_primary = 'yes';
		}
		else {
			$yoast_primary = 'no';
		}

		//add additional option for seo framework
		if (array_key_exists('wp_post_nav_seo_framework', $settings)) {
			$seo_framework = 'yes';
		}
		else {
			$seo_framework = 'no';
		}

		if (array_key_exists('wp_post_nav_exclude_primary', $settings)) {
			$exclude_primary = 'yes';
		}
		else {
			$exclude_primary = 'no';
		}

		if (array_key_exists('wp_post_nav_out_of_stock', $settings) && 'product' == get_post_type()) {
			//add the product filter for out of stock products, only if its not already loaded
		  if ( !has_filter( 'get_previous_post_where', array ($this,'wppostnav_outofstock' )) ) {
		  	add_filter( 'get_previous_post_where', array($this,'wppostnav_outofstock' ));

		  }

		  if ( !has_filter( 'get_next_post_where', array($this,'wppostnav_outofstock' )) ) {
		  	add_filter( 'get_next_post_where', array($this,'wppostnav_outofstock' ));
		  }
		}
		
    ob_start();

		//setup the excluded term array.  If yoast primary term is selected we need to only use that for getting terms
		$excluded_terms = [];
		$primary_term = '';
		$previous = '';
		$next ='';

		//get the category the post is in.
		$term = $this->get_post_categories();
		$post_id = get_the_id();
		
		//get the links
		if ($display_previous == 'true') {
			$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post($same_category, $excluded_terms, true, $term );
		}

		if ($display_next == 'true') {
			$next = get_adjacent_post($same_category, $excluded_terms, false, $term );
		}
		
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
		  if ( !empty( $primary_term ) ) {
		    $excluded_terms = get_terms( array(
		        'taxonomy' => $primary_term->taxonomy,
		        'hide_empty' => false,
		        'exclude' => $primary_term->term_id,
		        'fields'   => 'ids'
		    ) );
		    switch ( $exclude_primary ) {
		      case 'yes':
		        if ($previous) {
		        	$prev_primary_term = the_seo_framework()->get_primary_term( $previous->ID, $term );
		           //if no primary term assigned, or its not the same, exit
		          if ( empty( $primary_term ) || $prev_primary_term != $primary_term) {
		            $previous = '';
		          }
		        }

		        if ($next) {
		        	$next_primary_term = the_seo_framework()->get_primary_term( $next->ID, $term );
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
			echo '<nav class="wp-post-nav-shortcode" role="navigation">';   
		    if ($previous) {
		      $prev_link = previous_post_link( 
		            '%link', 
		            '<ul id="post-nav-previous">'
		            . '<h4>' . __('Previous Post', 'wp-post-nav') . '</h4>'
		            .$previous_image
		            .$previous_post_title . '<hr>'
		            .$previous_post_category
		            .$previous_post_excerpt. 
		            '<span id="post-nav-previous-button"></span></ul>'
		            ,true,$excluded_terms,$term );
		      echo $prev_link;
		    }

		    if ($next) {
		      $next_link = next_post_link( 
		          '%link', 
		            '<ul id="post-nav-next">'.
		            '<span id="post-nav-next-button"></span>'
		            . '<h4>' . __('Next Post', 'wp-post-nav') . '</h4>'
		            .$next_image
		            .$next_post_title . '<hr>'
		            .$next_post_category
		            .$next_post_excerpt. 
		            '</ul>'
		            ,true,$excluded_terms,$term );
		      echo $next_link;
		    }		
			echo '</nav>'; 
		}
    $output = ob_get_clean();
    return $output;
	}
}
