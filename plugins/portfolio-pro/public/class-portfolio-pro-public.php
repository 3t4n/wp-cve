<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://logichunt.com
 * @since      1.0.0
 *
 * @package    Portfolio_Pro
 * @subpackage Portfolio_Pro/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Portfolio_Pro
 * @subpackage Portfolio_Pro/public
 * @author     LogicHunt <logichunt.info@gmail.com>
 */
class Portfolio_Pro_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * @var Lgx_Carousel_Settings_API
	 */
	private $settings_api;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->settings_api = new Portfolio_pro_Settings_API($plugin_name, $version);

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Portfolio_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Portfolio_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/portfolio-pro-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Portfolio_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Portfolio_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'lazyload-script', plugin_dir_url( __FILE__ ) . 'lib/lazyload/jquery.lazyload.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'isotope-script', plugin_dir_url( __FILE__ ) . 'lib/isotope/isotope.pkgd.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/portfolio-pro-public.js', array( 'jquery' ), $this->version, false );

	}



	/**
	 * Define Short Code Function
	 *
	 * @param $atts
	 *
	 * @return mixed
	 * @since 1.0.0
	 */

	public function portfolio_shortcode_function($atts) {

		$limit_set      = $this->settings_api->get_option('portfoliopro_settings_limit', 'portfoliopro_basic', -1);
		$order_set      = $this->settings_api->get_option('portfoliopro_settings_order', 'portfoliopro_basic', 'DESC');
		$orderby_set    = $this->settings_api->get_option('portfoliopro_settings_orderby', 'portfoliopro_basic', 'orderby');
		$layout_type     = $this->settings_api->get_option('portfoliopro_settings_grid_layout', 'portfoliopro_basic', 'masonry');
		$row_item     	= $this->settings_api->get_option('portfoliopro_settings_row_item', 'portfoliopro_basic', 'three');
		$grid_type     	= $this->settings_api->get_option('portfoliopro_settings_grid_type', 'portfoliopro_basic', 'normal');
		$gray_img     	= $this->settings_api->get_option('portfoliopro_settings_grayimg', 'portfoliopro_basic', 'yes');
		$full_link_ype     	= $this->settings_api->get_option('portfoliopro_settings_fulllinktype', 'portfoliopro_basic', 'none');

		$atts = shortcode_atts(array(
			'order'             => $order_set,
			'orderby'           => $orderby_set,
			'limit'             => $limit_set,
			'layout'            => $layout_type,
			'rowitem'            => $row_item,
			'gridtype'            => $grid_type,
			'grayimg'            => $gray_img,
			'fulllinktype'       => $full_link_ype,
		), $atts, 'portfolio-pro');

		$output = $this->portfolio_pro_output_function($atts);

		return $output;
	}






	/**
	 *  Shortcode Function
	 *
	 * @since   1.0.0
	 *
	 * @param   $args   Shortcode Parameter
	 */

	public function portfolio_pro_output_function( $params ) {

		//Query args
		$order      = trim($params['order'] );
		$orderby    = trim( $params['orderby']);
		$limit      = intval(trim($params['limit']));
		$layout      =  $params['layout'];
		$row_item    =  $params['rowitem'];
		$grid_type    =  $params['gridtype'];
		$gray_img    =  $params['grayimg'];
		$full_img_link_type = $params['fulllinktype'];
		//var_dump($full_img_link_type);



		if($gray_img == 'yes') {
			$gray_img = 'pp-gray-img';
		}
		else {
			$gray_img = null;
		}




		// WP_Query arguments
		$args = array(
			'post_type' => array( 'portfoliopro' ),
			'post_status'       => array( 'publish' ),
			'order'             => $order,
			'orderby'           => $orderby,
			'posts_per_page'    => $limit
		);


		// The Query
		$portfolio_query = new WP_Query( $args );


		// Default Value
		$portfolio_output ='';
		$portfolio_item         = '';
		$portfolio_filter_list = '';
		$portfolio_cat = array();

		// Settings
		$show_filter = 1;


		// The Loop
		if ( $portfolio_query->have_posts() ) {
			while ( $portfolio_query->have_posts() ) {
				$portfolio_query->the_post();

				$post_id        = get_the_ID();
				$title          = get_the_title();
				$content        = get_the_content();
				$link           = get_permalink();

				//$default_thumb_url = plugin_dir_url( __FILE__ ).'img/website.png';
				$thumb_url = '';

				if (has_post_thumbnail( $post_id )) {
					$thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id), true );
					$thumb_url      = $thumb_url[0];
				}

				$metavalues     = get_post_meta( get_the_ID(), '_portfolioprometa', true );
				$ext_url        = $metavalues['ext_url'];
				$project_name   = $metavalues['project_name'];
				$client_name    = $metavalues['client_name'];
				$custom_tag_one = $metavalues['custom_tag_one'];
				$custom_tag_two = $metavalues['custom_tag_two'];
				$custom_tag_three =$metavalues['custom_tag_three'];


				//var_dump($full_img_link_type);

				if( !empty($full_img_link_type) && $full_img_link_type == 'full-external-link' ) {
					$full_link = $ext_url;
				}
				elseif( !empty($full_img_link_type) && $full_img_link_type == 'full-internal-link' ) {
					$full_link = $link;
				}


				//get catergory data
				$cat_list          = get_the_terms($post_id, 'portfolioprocat');

				$term_slugs_arr = array();
				$cat_filter_class_str = '';

				if ($cat_list && !is_wp_error($cat_list)) {

					foreach ($cat_list as $term) {
						//create the filters for unique categories
						if (!isset($portfolio_cat[ $term->term_id ])) {
							$portfolio_filter_list .= '<li><a   class="pp-filter-item" href="javascript:void(0)" data-filter=".filter'.$term->term_id.'">' . $term->name . '</a></li>';
						}
						$portfolio_cat[ $term->term_id ] = $term->name;

						//get the terms
						$term_slugs_arr[] = 'filter' . $term->term_id;

					}
					$cat_filter_class_str =  implode(' ', $term_slugs_arr);
				}



				// Vertical action
				if(empty($full_img_link_type) || $full_img_link_type == 'none') {
					$vertical_action  = '<div class="lgx-vertical-action">';
					$vertical_action .= '<ul>';
					$vertical_action .= (!empty($ext_url) ? '<li><a class="lgx-view" href="'.$ext_url.'" rel="nofollow" target="_blank">'.__('View', $this->plugin_name ).'</a></li>' : '');
					$vertical_action .= '<li><a class="lgx-details" href="'.$link.'" target="_blank">'.__('Details', $this->plugin_name ).'</a></li>';
					$vertical_action .= '</ul>';
					$vertical_action .= '</div>';

				} else {
					$vertical_action = '';
					$full_link_first_part = '<a href="'.$full_link.'" target="_blank" rel="nofollow">';
				}





				// Custom Tag
				$custom_tag = '<div class="lgx-custom-tag">';
				$custom_tag .= '<ul>';
				$custom_tag .= (!empty($custom_tag_one) ? '<li><span>'.$custom_tag_one.'</span></li>' : '');
				$custom_tag .= (!empty($custom_tag_two) ? '<li><span>'.$custom_tag_two.'</span></li>' : '');
				$custom_tag .= (!empty($custom_tag_three) ? '<li><span>'.$custom_tag_three.'</span></li>' : '');
				$custom_tag .= '</ul>';
				$custom_tag .= '</div>';




				// item info
				$item_info   = '<div class="lgx-item-info">';
				$item_info  .= (!empty($project_name) ? '<h4 class="lgx-item-title pp-tooltip"><span class="text">'.$project_name.'</span> <span class="pp-tooltiptext">'.$project_name.'</span></h4>' : '');
				$item_info  .= (!empty($client_name) ? '<p class="lgx-item-intro pp-tooltip"><span class="text">'.$client_name.'</span> <span class="pp-tooltiptext">'.$client_name.'</span></p>' : '');
				$item_info  .= '';
				$item_info .= '</div>';





				// Item Card
				$item_inner_card  = '<div class="lgx-item-card">';
				$item_inner_card .= '<div class="lgx-item-figure">';
				$item_inner_card .= (!empty($full_link_first_part) ? $full_link_first_part : '');
				$item_inner_card .= '<div class="lgxpp-figure">';
				
				$item_inner_card .= (!empty($thumb_url) ? '<img class="pp-portfolio-img '.$gray_img.'" src="'.$thumb_url.'" alt="'.$title.'" title="'.$title.'" />' : '<div class="default-img-card "></div>');
				$item_inner_card .= '<div class="lgx-figcaption">';
				$item_inner_card .= '<div class="lgx-caption">';
				$item_inner_card .=  $vertical_action;
				$item_inner_card .= '</div>';
				$item_inner_card .= '</div>';
				$item_inner_card .= '</div>';
				$item_inner_card .= (!empty($full_link_first_part) ? '</a>' : '');;
				$item_inner_card .= (!empty($custom_tag_one) || !empty($custom_tag_two) || !empty($custom_tag_three) ? $custom_tag : '');
				$item_inner_card .= '</div>';
				$item_inner_card .= (!empty($project_name) || !empty($client_name) ? $item_info : '');
				$item_inner_card .= '</div>';


				// Portfolio Item
				$portfolio_item .= '<div class="lgx-grid-item item-'.$row_item .' '. $cat_filter_class_str . '">';
				$portfolio_item .= $item_inner_card;
				$portfolio_item .= '</div>';

			}//foreach

			// Filter Grid

			$portfolio_filter_list_group = '<ul id="pp-filter" class="pp-filter">';
			$portfolio_filter_list_group .= '<li class="active" ><a class="pp-filter-item" href="javascript:void(0)"  data-filter="*">'.__('All', $this->plugin_name).'</a></li>';
			$portfolio_filter_list_group .= $portfolio_filter_list;
			$portfolio_filter_list_group .= '</ul>';


			// Restore original Post Data
			wp_reset_postdata();

			//Arrange All Data
			$portfolio_output .= '<section>';
			$portfolio_output .= '<div id="lgx-portfolio" class="lgx-portfolio">';
            $portfolio_output .= '<div class="lgx-port-inner '.$grid_type.' item-'.$row_item.'">';

            $portfolio_output .= ($show_filter) ? '<div class="pp-filter-area">'.$portfolio_filter_list_group.'</div>' : '';
			$portfolio_output .= '<div id="lgx-grid-wrapper" class="lgx-grid-wrapper" data-layout="'.$layout.'" >';
			$portfolio_output .= $portfolio_item;
			
			$portfolio_output .= '</div>';//wrapper

            $portfolio_output .= '</div>';//inner
            $portfolio_output .= '</div>';
	
			$portfolio_output .= '</section>';


		} else {
			$portfolio_output ='<div class="lgxport-warning">'.__('There are no portfolio items created, add some please.', $this->plugin_name ).'</div>';
		}

		// Return Output
		return $portfolio_output;
	}



}
