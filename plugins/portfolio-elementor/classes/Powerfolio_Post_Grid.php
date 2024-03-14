<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Image Gallery Element
 *
 */
class Powerfolio_Post_Grid {	
	
	//limit content on excerpt
	public static function pwgd_get_the_content($length = 100){
		$text = get_the_content();
		
		if(strlen($text)<$length+10) {
			return strip_tags($text); //don't cut if too shortx'x'Z
		} 
		
		$break_pos = strpos($text, ' ', $length); //find next space after desired length

		$visible = substr($text, 0, $break_pos);
		
		return strip_tags($visible). " […]";
	} 

	public static function get_post_grid_template($settings) {   

		//Grid Classes
		$grid_classes_array[] = '';
		$grid_classes_array[] = 'pwgd-post-grid-cols';
		$grid_classes_array[] = $settings['columns'];
		$grid_classes_array[] = $settings['grid_style'];

		//Grid or float (for masonry)
		$grid_format_class = 'pwgd-post-grid-cols-grid';
		$grid_content_classes_array[] = '';
		if ( $settings['showfilter'] == 'yes' || $settings['grid_style'] == 'grid-style-masonry' ) { 
			$grid_format_class = 'pwgd-post-grid-cols-float';  
			$grid_content_classes_array[] = 'pwgd-grid-content-isotope';
		}
		if ( $settings['showfilter'] == 'yes' && $settings['grid_style'] != 'grid-style-masonry' ) {
			$grid_classes_array[] = 'pwgd-grid-content-equalheights';  
		}
		$grid_classes_array[] = $grid_format_class;

		$grid_classes = implode(' ', $grid_classes_array);
		$grid_content_classes = implode(' ', $grid_content_classes_array);

			//Loop Variables
			$post_type = 'post';
			$postsperpage = $settings['posts_per_page'];
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$order = $settings['query_order'];
			$order_by = $settings['query_order_by'];
			
			//Loop
			// https://www.billerickson.net/code/wp_query-arguments/
			$args = array(
				'post_type' => $post_type,
				'posts_per_page' => $postsperpage,	
				'order' => $order,
				'order_by' => $order_by,
				'paged' => $paged,	
				'ignore_sticky_posts' => 1
				//'p' => $id
			); 	

			$posts_list = new WP_Query( $args );

			//Start of render structure
			$return ='';	

			$return .='<div class="pwgd-post-grid '.$grid_classes.'">';	

				//Filter
				if ( $settings['showfilter'] == 'yes' ) {
					$return .='<div class="pwgd-posts-filter">';
					
					$all_button_data_filter = apply_filters( 'pwgd_posts_all_button_data_filter', '*' );
					$tax_text = apply_filters( 'pwgd_posts_tax_text', 'All' );

					if ($tax_text !='') {
						$return .='<button class="posts-filter-item item-active" data-filter="'.$all_button_data_filter.'" style="background-color:' .';">'.$tax_text.'</button>';
					} 

					//Get all Terms
					$terms = get_terms( array(
						'taxonomy' => 'category',
						'hide_empty' => false,
						'parent' => 0
					) );

					foreach($terms as $term) {
						$term_name = $term->name;
						$term_slug = $term->slug;
						$return .='<button class="posts-filter-item" data-filter=".category_'.esc_attr($term_slug).'">'.esc_html($term_name).'</button>';
					}            
					

					$return .='</div>';
				}	

				//Grid Content
				$return .= '<div class="pwgd-post-grid-content'.$grid_content_classes.'">';

					//Item - Foreach
					if ( $posts_list->have_posts() ) :
						while ( $posts_list->have_posts() ) : $posts_list->the_post(); 

						$post_item_wrapper_classes_array = (array) null;
						$cats = get_the_category();
						foreach( $cats as $cat){
							$post_item_wrapper_classes_array[] = 'category_'.$cat->slug;
						}
						
						$post_item_wrapper_classes = implode(' ', $post_item_wrapper_classes_array);

						$return .= '<div class="pwgd-post-grid-item-wrapper '.$post_item_wrapper_classes.'">';
							$return .= '<div class="pwgd-post-grid-item">';
							
								//Featured Image
								if( $settings['show_featured_image'] == true ) {

									$featured_image_url = get_the_post_thumbnail_url();
									$featured_img_link_css = '';
									if ($featured_image_url != '') {
										$featured_img_link_css = 'background-image: url('.$featured_image_url.');';
									}     
									$return .= '<div class="pwgd-post-grid-item-img-wrapper">';
										$return .= '<a href="'.get_the_permalink().'" class="pwgd-post-grid-item-img-link" style="'.$featured_img_link_css.'"/>'; 
											$return .= '<img src="'.$featured_image_url.'"/>'; 
										$return .= '</a>'; 
									$return .= '</div>';

								}   
								
								$return .= '<div class="pwgd-post-grid-item-content">';

									//Post Meta
									$return .= '<div class="pwgd-post-grid-item-meta-wrapper">';

										//Date
										if( $settings['show_date'] == true ) {
											$return .= '<div class="pwgd-post-grid-item-meta pwgd-post-grid-item-meta-date">';
												$return .= '<span class="pwgd-post-grid-item-meta-icon"><i class="far fa-calendar-alt"></i></span> ';     
												$return .= get_the_date(); 
											$return .= '</div>';
										}

										//Author
										if( $settings['show_author'] == true ) {
											$return .= '<div class="pwgd-post-grid-item-meta pwgd-post-grid-item-meta-author">';
												$return .= '<span class="pwgd-post-grid-item-meta-icon"><i class="fas fa-user"></i></span> ';     
												$return .= get_the_author(); 
											$return .= '</div>';  
										}

										//Comments - Create custom function
										if( $settings['show_comments'] == true ) {
											$return .= '<div class="pwgd-post-grid-item-meta pwgd-post-grid-item-meta-comments">';
											$return .= '<span class="pwgd-post-grid-item-meta-icon"><i class="far fa-comments"></i></span> ';
												$return .= get_comments_number();  
											$return .= '</div>';
										}

										//Taxonomies
										if( $settings['show_categories'] == true ) {                                                                
											$return .= '<div class="pwgd-post-grid-item-meta pwgd-post-grid-item-meta-tax">';
												$cats = get_the_category();
												$cats_array = [];
												foreach( $cats as $cat){
													$term_link = get_term_link( $cat );
													$cats_array[] .= '<a href="'.esc_url( $term_link ).'">'.$cat->name.'</a>';
												}
												$return .= implode(', ', $cats_array );
											$return .= '</div>'; 
										}

									$return .= '</div>';

									//Post Title
									$return .= '<div class="pwgd-post-grid-item-title-wrapper">';                            
										$return .= '<h3 class="pwgd-post-grid-item-title">';
											$return .= '<a href="'.get_the_permalink().'" class="pwgd-post-grid-item-title-link"/>'.get_the_title().'</a>'; 
										$return .= '</h3>';
									$return .= '</div>';

									//Post Excerpt
									$return .= '<div class="pwgd-post-grid-item-text-wrapper">';
										$return .= '<div class="pwgd-post-grid-item-text">';
											$post_excerpt = self::pwgd_get_the_content(200);                        
											$return .= $post_excerpt;
										$return .= '</div>';
									$return .= '</div>';

									//See Post Btn
									if( $settings['show_btn'] == true ) {
										$btn_text = __('See More', 'pwrgrids');
										$btn_link = get_permalink();

										$return .= '<div class="pwgd-post-grid-item-btn-wrapper">';
											$return .= '<a href="'.$btn_link.'" class="pwgd-post-grid-item-btn">'.$settings['see_more_btn_text'].'</a>';
										$return .= '</div>';
									}

								$return .= '</div>';
							$return .= '</div>';
						$return .= '</div>';

						endwhile;
					$return .= '</div>';
					//End of Grid Content
						

					//Pagination goes here            
					if( $settings['show_pagination'] == true ) {
						$return .= '<div class="pwgd-post-grid-pagination">';

							$btn_text_previous = '<i class="fas fa-arrow-left"></i> '.__('Previous','pwrgrids');
							$btn_text_next = __('Next ','pwrgrids').'<i class="fas fa-arrow-right"></i>';

							if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
								$return .= '<a href="#">'.$btn_text_previous.'</a>';
								$return .= '<a href="#">'.$btn_text_next.'</a>';
							} 
							else {
								$return .= get_previous_posts_link( $btn_text_previous  );
								$return .= get_next_posts_link( $btn_text_next , $posts_list->max_num_pages );
							}
						
										
						$return .= '</div>';
					}

					wp_reset_postdata();
				endif;     
			
			$return .='</div>';	
			//End of render structure

			return wp_kses_post($return);
	}
}

