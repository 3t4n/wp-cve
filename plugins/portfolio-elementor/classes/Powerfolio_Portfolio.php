<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Powerfolio_Portfolio {
	
	public function __construct() {

		add_action( 'init', array( $this, 'register_portfolio_post_type') , 20 );
		add_action( 'init', array( $this, 'create_portfolio_taxonomies') , 20 );
		add_action( 'init', array( $this, 'register_portfolio_shortcodes') , 20 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_scripts') , 20 );		
		
		//Flush rewrite rules
		add_action( 'init', array( __CLASS__, 'flush_rewrite_rules_maybe') , 20 );
	}

	/*
	* Register Elemenfolio/Portfolio Post Type
	*/
	public function register_portfolio_post_type()	{
		$args = array();	

		// Filters
		$portfolio_cpt_slug_rewrite = apply_filters( 'elpt_portfolio_cpt_slug_rewrite', 'portfolio' ); 
		$portfolio_cpt_has_archive = apply_filters( 'elpt_portfolio_cpt_has_archive', false ); 
		$portfolio_cpt_name = apply_filters( 'elpt_portfolio_cpt_name', __( 'Portfolio', 'elemenfolio' ) ); 


		// Portfolio Post Type
		$args['post-type-portfolio'] = array(
			'labels' => array(
				'name' => $portfolio_cpt_name,
				'singular_name' => __( 'Item', 'elemenfolio' ),
				'add_new' => __( 'Add New Item', 'elemenfolio' ),
				'add_new_item' => __( 'Add New Item', 'elemenfolio' ),
				'edit_item' => __( 'Edit Item', 'elemenfolio' ),
				'new_item' => __( 'New Item', 'elemenfolio' ),
				'view_item' => __( 'View Item', 'elemenfolio' ),
				'search_items' => __( 'Search Through portfolio', 'elemenfolio' ),
				'not_found' => __( 'No items found', 'elemenfolio' ),
				'not_found_in_trash' => __( 'No items found in Trash', 'elemenfolio' ),
				'parent_item_colon' => __( 'Parent Item:', 'elemenfolio' ),
				'menu_name' => $portfolio_cpt_name,				
			),		  
			'hierarchical' => false,
	        'description' => __( 'Add a New Item', 'elemenfolio' ),
	        'menu_icon' =>  'dashicons-images-alt',
	        'public' => true,
	        'publicly_queryable' => true,
			'exclude_from_search' => false,
			'has_archive' => $portfolio_cpt_has_archive,
	        'query_var' => true,
			'rewrite' => array( 'slug' => $portfolio_cpt_slug_rewrite ),
			'show_in_rest' => true,
            'supports' => array('title','editor', 'thumbnail')
	        // This is where we add taxonomies to our CPT
        	//'taxonomies'          => array( 'category' ),
		);	

		// Register post type: name, arguments
		register_post_type('elemenfolio', $args['post-type-portfolio']);
	}	

	/*
	* Register Taxonomies
	*/
	public function create_portfolio_taxonomies() {
		// Config
		$elemenfoliocategory_slug_rewrite = apply_filters( 'elpt_elemenfoliocategory_slug_rewrite', 'portfoliocategory' );

		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => _x( 'Portfolio Categories', 'taxonomy general name', 'elemenfolio' ),
			'singular_name'     => _x( 'Portfolio Category', 'taxonomy singular name', 'elemenfolio' ),
			'search_items'      => __( 'Search Portfolio Categories', 'elemenfolio' ),
			'all_items'         => __( 'All Portfolio Categories', 'elemenfolio' ),
			'parent_item'       => __( 'Parent Portfolio Category', 'elemenfolio' ),
			'parent_item_colon' => __( 'Parent Portfolio Category:', 'elemenfolio' ),
			'edit_item'         => __( 'Edit Portfolio Category', 'elemenfolio' ),
			'update_item'       => __( 'Update Portfolio Category', 'elemenfolio' ),
			'add_new_item'      => __( 'Add New Portfolio Category', 'elemenfolio' ),
			'new_item_name'     => __( 'New Portfolio Category', 'elemenfolio' ),
			'menu_name'         => __( 'Portfolio Categories', 'elemenfolio' ),
		);
	
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => $elemenfoliocategory_slug_rewrite ),
			'show_in_rest' =>true,
		);
	
		register_taxonomy( 'elemenfoliocategory', array( 'elemenfolio' ), $args );
	}	

	/*
	* flush_rewrite_rules_maybe()
	*/
	public static function flush_rewrite_rules_maybe() {
		if ( get_option( 'elpt_flush_rewrite_rules_flag' ) ) {
			flush_rewrite_rules();
			delete_option( 'elpt_flush_rewrite_rules_flag' );
		}
	}

	//Enable Elementor on portfolio post type
	//From https://wordpress.org/support/topic/option-to-enable-by-default-elementor-for-custom-post-type/
	public static function add_cpt_support_for_elementor() {
		
		$cpt_support = get_option( 'elementor_cpt_support' );
		
		//check if option DOESN'T exist in db
		if( ! $cpt_support ) {
			$cpt_support = [ 'page', 'post', 'elemenfolio' ]; //create array of our default supported post types
			update_option( 'elementor_cpt_support', $cpt_support ); //write it to the database
		}
		
		//if it DOES exist, but portfolio is NOT defined
		else if( ! in_array( 'elemenfolio', $cpt_support ) ) {
			$cpt_support[] = 'elemenfolio'; //append to array
			update_option( 'elementor_cpt_support', $cpt_support ); //update database
		}
	}

	/*
	*  Enqueue scripts for shortcode
	*/
	public static function enqueue_scripts() {
		$assets_dir =  plugin_dir_url( __DIR__ );	

		////Isotope			
		wp_enqueue_script( 'jquery-isotope',  $assets_dir. 'vendor/isotope/js/isotope.pkgd.js', array('jquery', 'imagesloaded'), '20151215', true );
		wp_enqueue_script( 'jquery-packery', $assets_dir. 'vendor/isotope/js/packery-mode.pkgd.min.js', array('jquery', 'imagesloaded', 'jquery-isotope'), '20151215', true );

		//Image Lightbox
		if ( apply_filters( 'elpt-enable-simple-lightbox', true ) == true ) {
			wp_enqueue_script( 'simple-lightbox-js',  $assets_dir.  'vendor/simplelightbox/dist/simple-lightbox.min.js', array('jquery'), '20151218', true );
			wp_enqueue_style( 'simple-lightbox-css', $assets_dir .  'vendor/simplelightbox/dist/simplelightbox.min.css' );
			wp_enqueue_script( 'elpt-portfoliojs-lightbox', $assets_dir . 'assets/js/custom-portfolio-lightbox.js', array('jquery'), '20151215', true );
		}
		
		//Custom JS
		wp_enqueue_script( 'elpt-portfoliojs', $assets_dir . 'assets/js/custom-portfolio.js', array('jquery'), '20151215', true );

		//Custom CSS
		wp_enqueue_style( 'elpt-portfolio-css', $assets_dir .  'assets/css/powerfolio_css.css' );
	}

	/*
	* get_widget_settings()
	*/
	public static function get_widget_settings($settings, $widget = 'portfolio') {

		switch ($widget) {
			case 'portfolio':
				
				extract(shortcode_atts(array(
					//"id" => '',
					"postsperpage" => '',
					"pagination" => '',
					"pagination_postsperpage" => '',
					"showfilter" => '',
					"taxonomy" => '',
					"type" => '',
					"style" => '',
					"columns" => '',
					"columns_mobile" => '',			
					"margin" => '',
					"linkto" => '',
					"hover" => '',
					"zoom_effect" => '',
					"post_type" => '',
					"tax_text" => '',
					"showallbtn" => '',		
					"hide_item_title" => '',
					"hide_item_category" => '',	
					"taxonomy" => '',			
					'element_id' => '',
				), $settings));


				// Set Default Values
				if ( $type == "yes"  ) {
					$type = true;
				}

				if ( $post_type == '' ) {
					$post_type = 'elemenfolio';
				}
		
				// Escape and validate the attributes
				$settings = array(
					'postsperpage'       => esc_attr($postsperpage),
					'pagination'       => esc_attr($pagination),
					'pagination_postsperpage' => esc_attr($pagination_postsperpage),
					'showfilter'         => esc_attr($showfilter),
					'taxonomy'           => esc_attr($taxonomy),
					'type'               => esc_attr($type),
					'style'              => esc_attr($style),
					'columns'            => esc_attr($columns),
					'columns_mobile'     => esc_attr($columns_mobile),
					'margin'             => esc_attr($margin),
					'linkto'             => esc_attr($linkto),
					'hover'              => esc_attr($hover),
					'zoom_effect'        => esc_attr($zoom_effect),
					'post_type'          => esc_attr($post_type),
					'tax_text'           => esc_attr($tax_text),
					'showallbtn'         => esc_attr($showallbtn),
					'hide_item_title'    => esc_attr($hide_item_title),
					'hide_item_category' => esc_attr($hide_item_category),
					'element_id' => esc_attr($element_id),
				);
			
				break;

			case 'image_gallery':
				
				$settings = $settings;
				$settings['taxonomy'] = '';
				$settings['post_type'] = '';
				$settings['type'] = '';
				$settings['hide_item_category'] = '';
				$settings['hide_item_title'] = '';				
				$settings['postsperpage'] = 50;
				$settings['linkto'] = 'image';
				$settings['zoom_effect'] = '';
				$settings['columns_mobile'] = '';

			break;			
		}		

		//Element ID		
		if (! array_key_exists('element_id', $settings) || $settings['element_id'] == '') {
			$settings['element_id'] = Powerfolio_Common_Settings::generate_element_id();
		}	

		return $settings;			
	}

	/*
	* Get Items for grid/portfolio
	*/
	public static function get_items_for_grid($settings, $widget) {

		$items = array();

		switch ($widget) {
			case 'portfolio':
				if(! $settings['post_type'] || $settings['post_type'] == '') {
					$settings['post_type'] = 'elemenfolio';
				}	
		
				if ( $settings['type'] == true) {
					$args = array(
						'post_type' => $settings['post_type'],
						'posts_per_page' => $settings['postsperpage'],		
						'suppress_filters' => false,  
						'tax_query' => array(
							array(
								'taxonomy' => 'elemenfoliocategory',
								'field'    => 'id',
								'terms'    => $settings['taxonomy'],
							),
						),		
						//'p' => $id
					); 	
				} else { 
					$args = array(
						'post_type' => $settings['post_type'],
						'posts_per_page' => $settings['postsperpage'],	
						'suppress_filters' => false,  
					);			
				}

				$items = (array)get_posts($args);
			break;

			case 'image_gallery':

				$items = $settings['list'];

			break;		
		}
		
		return (array)$items;
	}

	/*
	* Get Terms filter output
	*/
	public static function get_grid_filter($settings, $widget) {		
		if ($settings['showfilter'] === 'no' || $settings['showfilter'] === false) {
			return ''; 
		}

		$output = '';

		$output .='<div class="elpt-portfolio-filter">';						
		
			//All text filters and variables
			$settings['tax_text'] = apply_filters( 'elpt_tax_text', $settings['tax_text'] );
			$tax_text_filter = apply_filters( 'elpt_tax_text_filter', '*' );
			
			if ($settings['tax_text'] =='') {
				$settings['tax_text'] = __('All', 'elemenfolio');
			}
			
			if ($settings['showallbtn'] !== 'no') {
				if ($settings['type'] == true && is_array($settings['taxonomy']) && count($settings['taxonomy']) > 1 ) {
					$output .='<button class="portfolio-filter-item item-active" data-filter="'.$tax_text_filter.'" style="background-color:' .';">'.$settings['tax_text'].'</button>';
				}
				else if ($settings['type'] !== true) {
					$output .='<button class="portfolio-filter-item item-active" data-filter="'.$tax_text_filter.'" style="background-color:' .';">'.$settings['tax_text'].'</button>';
				} 
			}
			
			switch ($widget) {
				case 'portfolio':

					if ( $settings['post_type'] === 'elemenfolio' || $settings['post_type'] === '' ) {							
						$terms = get_terms( array(
							'taxonomy' => 'elemenfoliocategory',
							'hide_empty' => false,
						) );
			
						$terms = apply_filters( 'elpt_tax_terms_list', $terms );
						
			
						foreach ( $terms as $term ) {
							$thisterm = $term->name;
							$thistermslug = $term->slug;
			
							if ($settings['type'] == true && is_array($settings['taxonomy']) && in_array($term->term_id, $settings['taxonomy']) && count($settings['taxonomy']) > 1 ) {
								$output .='<button class="portfolio-filter-item" style="background-color:' .';" data-filter=".elemenfoliocategory-'.esc_attr($thistermslug).'">'.esc_html($thisterm).'</button>';
							}
							else if ($settings['type'] != true) {
								$output .='<button class="portfolio-filter-item" style="background-color:' .';" data-filter=".elemenfoliocategory-'.esc_attr($thistermslug).'">'.esc_html($thisterm).'</button>';
							}
						}				
					} else {
						return ;
					}

				break;

				// Image Gallery Widget	
				case 'image_gallery':

					//Get all Tags
					$tag_list = array();
					foreach($settings['list'] as $item) {
						$tag_array = $str_arr = explode (",", $item['list_filter_tag']);
						foreach ($tag_array as $tag) {
							if ( ! in_array ($tag, $tag_list ) ){
								$tag_list[] = $tag;
							} 
						}						
					}
					
					//Sort tags in alphabetical order
					sort($tag_list);

					//Filter tag list
					$tag_list = apply_filters( 'elpt_gallery_terms_list', $tag_list );
					
					//List Tags
					foreach($tag_list as $item) {
						$item_slug = elpt_get_text_slug($item);
						$output .='<button class="portfolio-filter-item" style="background-color:' .';" data-filter=".elemenfoliocategory-'.esc_attr($item_slug).'">'.$item.'</button>';
					}
					
				break;
						
			}

			$output .='</div>';	
		
		return (string)$output;
	}
	

	/*
	* get_columns_css_classes()
	*/
	static function get_columns_css_classes($settings) {

		$portfoliocolumns = 'elpt-portfolio-columns-4';
		
		if ($settings['columns'] == '2') {
			$portfoliocolumns = 'elpt-portfolio-columns-2';
		}
		else if ($settings['columns'] == '3') {
			$portfoliocolumns = 'elpt-portfolio-columns-3';
		}
		else if ($settings['columns'] == '5') {
			$portfoliocolumns = 'elpt-portfolio-columns-5';
		}
		else if ($settings['columns'] == '6') {
			$portfoliocolumns = 'elpt-portfolio-columns-6';
		}

		return $portfoliocolumns;
	}

	/*
	* get_columns_class_for_mobile()
	*/
	static function get_columns_class_for_mobile($settings) {

		$portfoliocolumns_mobile = '';

		if ( array_key_exists('columns_mobile', $settings) ) {

			if ( $settings['columns_mobile'] == '2') {
				$portfoliocolumns_mobile = 'elpt-portfolio-columns-mobile-2';
			}
			else if ( $settings['columns_mobile'] == '3') {
				$portfoliocolumns_mobile = 'elpt-portfolio-columns-mobile-3';
			}
		}

		return $portfoliocolumns_mobile;
	}

	/*
	* get_margin_css_class()
	*/
	static function get_margin_css_class($settings) {
		$portfolio_margin_css_class = '';

		if ( $settings['margin'] === 'yes' || $settings['margin'] === true || $settings['margin'] === 'true' ) {
			$portfolio_margin_css_class = 'elpt-portfolio-margin';
		}

		return $portfolio_margin_css_class;
	}


	/*
	* get_portfolio_styles()
	*/
	static function get_portfolio_styles($settings) {
		$styles = array();

		$styles['portfoliostyle'] = '';
		$styles['portfolio_isotope'] = 'elpt-portfolio-content-isotope';

		if ( isset($settings['pagination'] ) && $settings['pagination'] == 'true' ) {
			$styles['portfolio_isotope'] = 'elpt-portfolio-content-isotope-pro';
		}
		
		if ($settings['style'] == 'masonry' ) {
			$styles['portfoliostyle'] = 'elpt-portfolio-style-masonry';
		}
		else if ($settings['style'] == 'specialgrid1' ) {
			$styles['portfoliostyle'] = 'elpt-portfolio-special-grid-1';
		}
		else if ($settings['style'] == 'specialgrid2' ) {
			$styles['portfoliostyle'] = 'elpt-portfolio-special-grid-2';
		}
		else if ($settings['style'] == 'specialgrid3' ) {
			$styles['portfoliostyle'] = 'elpt-portfolio-special-grid-3';
		}
		else if ($settings['style'] == 'specialgrid4' ) {
			$styles['portfoliostyle'] = 'elpt-portfolio-special-grid-4';
		}
		else if ($settings['style'] == 'specialgrid5' ) {
			$styles['portfoliostyle'] = 'elpt-portfolio-special-grid-5';
		}	
		else if ($settings['style'] == 'specialgrid6' ) {
			$styles['portfoliostyle'] = 'elpt-portfolio-special-grid-6';
		}	
		else if ($settings['style'] == 'purchasedgrid' ) {
			$styles['portfoliostyle'] = apply_filters( 'powerfolio_custom_style_class_filter', 'elpt-portfolio-purchased-grid');
			$styles['portfolio_isotope'] = apply_filters( 'powerfolio_custom_isotope_class_filter', 'elpt-portfolio-content-isotope');
			$styles['portfoliocolumns'] = apply_filters( 'powerfolio_custom_cols_class_filter', 'elpt-portfolio-columns-3');
		}	
		else if ($settings['style'] == 'grid_builder' ) {
			$styles['portfoliostyle'] = 'elpt-portfolio-grid-builder';
			$styles['portfolio_isotope'] = 'elpt-portfolio-content-packery';
		}					
		else {
			$styles['portfoliostyle'] = 'elpt-portfolio-style-box';
		}
	
		return $styles;
	}

	/*
	* get_portfolio_link_data()
	*/
	public static function get_portfolio_link_data($post, $settings, $widget, $portfolio_image) {
        		
        $portfolio_link_target = '';
        $portfolio_link_rel = '';
        $portfolio_link_class = '';
        $portfolio_link_follow = '';
		$portfolio_link = '';

		$rel_id = Powerfolio_Common_Settings::generate_element_id(); 
		
		if ( array_key_exists('element_id', $settings) ) {
			$rel_id = $settings['element_id'];
		}		
		
		if ( $widget == 'portfolio' ) {
			$portfolio_link = get_the_permalink($post['ID']);
		}

        if ($settings['linkto'] == 'image') {
            $portfolio_link = $portfolio_image;
            $portfolio_link_class = 'elpt-portfolio-lightbox';
            $portfolio_link_rel = 'rel=elpt-portfolio_' . $rel_id;
        } 
		else if ($settings['linkto'] == 'image_elementor') {
            $portfolio_link = $portfolio_image;
            $portfolio_link_class = 'elpt-portfolio-elementor-lightbox';
            $portfolio_link_rel = 'rel="elpt-portfolio_' . $rel_id . '"';
        } 
		else if ($settings['linkto'] == 'link' && array_key_exists('list_external_link', $post)) {
            $portfolio_link = $post['list_external_link']['url'];
            if ($post['list_external_link']['is_external'] == true) {
                $portfolio_link_target = 'target="_blank"';
            }
            if ($post['list_external_link']['nofollow'] == true) {
                $portfolio_link_follow = 'rel="nofollow"';
            }
        }

        return [
            'link' => $portfolio_link,
            'target' => $portfolio_link_target,
            'rel' => $portfolio_link_rel,
            'class' => $portfolio_link_class,
            'follow' => $portfolio_link_follow,
        ];
    }

	/*
	* get_portfolio_terms()
	*/
	public static function get_portfolio_terms($post, $widget) {
        $term_names = [];

        if ($widget == 'portfolio') {
            $terms = get_the_terms($post['ID'], 'elemenfoliocategory');
            if (is_array($terms) || is_object($terms)) {
                foreach ($terms as $term) {
                    $term_names[] = $term->name;
                }
            }
        } else if ($widget == 'image_gallery') {
            $tag_array = explode(",", $post['list_filter_tag']);
            foreach ($tag_array as $tag) {
                $term_names[] = $tag;
            }
        }

        return $term_names;
    }

	/*
	* get_single_item_data()
	*/
	static function get_single_item_data($post, $settings, $widget) {
		$data = array();			
	
		switch ($widget) {
			case 'portfolio':
				$data['post_id'] = $post['ID'];
				$data['post_title'] = get_the_title($data['post_id'] );
				$data['portfolio_image'] = Powerfolio_Common_Settings::get_image_url( get_post_thumbnail_id($data['post_id'] ) );
		
				if (is_array($data['portfolio_image'])) {
					$data['portfolio_image'] = $data['portfolio_image'][0];
				}
		
				$data['classes'] = get_post_class($data['post_id']);

				if ( $settings['post_type'] == 'elemenfolio' ) {
					$terms = get_the_terms($data['post_id'], 'elemenfoliocategory');
					if ( is_array( $terms ) ) {
						foreach ($terms as $term) {
							if (!in_array('elemenfoliocategory-' . $term->slug, $data['classes'])) {
								$data['classes'][] = 'elemenfoliocategory-' . $term->slug;
							}
						}	
					}									
				}
				
				$data['classes'] = join(' ', $data['classes']);

			break;

			case 'image_gallery':
				if ( array_key_exists('list_description', $post) ) {
					$data['list_description'] = $post['list_description'];
				}			

				$data['post_title'] = $post['list_title'];

				$data['portfolio_image'] = $post['list_image']['url'];

				$tag_array = explode(",", $post['list_filter_tag']);

				$data['classes'] = '';

				foreach ($tag_array as $tag) {
					$data['classes'] .= ' elemenfoliocategory-' . elpt_get_text_slug($tag);
				}
			
			break;
		}

		// Terms
		$data['term_names'] = self::get_portfolio_terms($post, $widget);
		// Link Data
		$data['link_data'] = self::get_portfolio_link_data($post, $settings, $widget, $data['portfolio_image']);
	
		return $data;
	}
	

	/*
	* Get settings for shortcode
	*/
	public static function get_shortcode_settings($settings, $widget) {
		// Get widget settings
		$settings = self::get_widget_settings($settings, $widget);
		$settings['taxonomy'] = explode(",", $settings['taxonomy']);
	
		// Columns
		$settings['portfoliocolumns'] = self::get_columns_css_classes($settings);
	
		// Columns Mobile
		$settings['portfoliocolumns_mobile'] = self::get_columns_class_for_mobile($settings);
	
		// Margin
		$settings['portfoliomargin'] = self::get_margin_css_class($settings);
	
		// Styles
		$styles = self::get_portfolio_styles($settings);
		$settings['portfoliostyle'] = $styles['portfoliostyle'];
	
		if (!empty($styles['portfolio_isotope'])) {
			$settings['portfolio_isotope'] = $styles['portfolio_isotope'];
		}
		if (!empty($styles['portfoliocolumns'])) {
			$settings['portfoliocolumns'] = $styles['portfoliocolumns'];
		}
	
		return $settings;
	}


	/*
	* Get single item Output
	*/
	static function get_single_item_output($post, $settings, $widget) {

		// Get data for single item
		$data = self::get_single_item_data($post, $settings, $widget);

		$output = '';
	
		$output .= '<div class="portfolio-item-wrapper ' . $data['classes'] . '">';
			$output .= '<a href="' . esc_url($data['link_data']['link']) . '" class="portfolio-item ' . esc_attr($data['link_data']['class']) . '" ' . esc_attr($data['link_data']['rel']) . ' style="background-image: url(' . esc_url($data['portfolio_image']) . ')" title="' . $data['post_title'] . '" ' . $data['link_data']['target'] . ' ' . $data['link_data']['follow'] . '">';
		
				$output .= '<img src="' . esc_url($data['portfolio_image']) . '" title="' . $data['post_title'] . '" alt="' . $data['post_title'] . '"/>';
				$output .= '<div class="portfolio-item-infos-wrapper" style="background-color:' . ';"><div class="portfolio-item-infos">';
			
					// Title
					if ($settings['hide_item_title'] != 'yes') {
						$output .= '<div class="portfolio-item-title"><span class="portfolio-item-title-span">' . $data['post_title'] . '</span></div>';
					}
				
					// Description
					if (array_key_exists('list_description', $data) && $data['list_description'] != '') {
						$output .= '<div class="portfolio-item-desc">' . $data['list_description'] . '</div>';
					}
				
					// Categories / Tags
					if ($settings['hide_item_category'] != 'yes') {
						$output .= '<div class="portfolio-item-category">';

						foreach ($data['term_names'] as $term_name) {
							$output .= '<span class="elpt-portfolio-cat">' . esc_html($term_name) . '</span>';
						}

						$output .= '</div>';
					}
			
				$output .= '</div></div>';
			$output .= '</a>';
		$output .= '</div>';
	
		return $output;
	}	


	/*
	* Create shortcode and returns the output
	*/
	public static function get_portfolio_shortcode_output($settings, $content = null, $shortcode = null, $widget="portfolio") {
		
		// enqueue scripts for shortcode
		if (! is_null($shortcode) ) {
			self::enqueue_scripts();
		}		
	
		// Get settings
		$settings = self::get_shortcode_settings($settings, $widget);

		if ( isset($settings['pagination'] ) && $settings['pagination'] == 'true' )  {

			$data_to_send = array(
				'itemsPerPageDefault' => $settings['pagination_postsperpage'],
			);
			$data_to_send = json_encode($data_to_send);
			wp_add_inline_script( 'elpt-portfoliojs', 'const gridSettings = ' . $data_to_send, 'before' );
		}
	
		// Get widget items
		$portfolio_items = self::get_items_for_grid($settings, $widget);

		// Workarounds
		// To-do: Fix missing array keys error
		if (! array_key_exists('zoom_effect', $settings) ) {
			$settings['zoom_effect'] = '';
		}
		if (! array_key_exists('portfolio_isotope', $settings) ) {
			$settings['portfolio_isotope'] = '';
		}
	
		if (count($portfolio_items)) {
			$output = '';
	
			$output .= '<div class="elpt-portfolio '.$settings['element_id'].'">';
	
				//Filter
				$output .= self::get_grid_filter($settings, $widget);
		
				$output .= '<div class="elpt-portfolio-content ' . $settings['portfolio_isotope'] . ' ' . $settings['portfoliostyle'] . ' ' . $settings['zoom_effect'] . ' ' . $settings['hover'] . ' ' . $settings['portfoliocolumns'] . ' ' . $settings['portfoliocolumns_mobile'] . ' ' . $settings['portfoliomargin'] . '">';
		
				foreach ($portfolio_items as $post) {
					$output .= self::get_single_item_output((array)$post, $settings, $widget);
				}
		
				$output .= '</div>';
	
			$output .= '</div>';
	
			return wp_kses_post($output);			
		}
	}

	//Register the shortcode shortcode
	public function register_portfolio_shortcodes() {	
	  add_shortcode("powerfolio", array( __CLASS__, 'get_portfolio_shortcode_output') );
      add_shortcode("elemenfolio", array( __CLASS__, 'get_portfolio_shortcode_output') );
	}	
}

// Start Powerfolio_Portfolio
add_action( 'init', function(){
	new Powerfolio_Portfolio(); 
});