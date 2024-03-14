<?php

/**
 * The public-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-specific stylesheet and JavaScript.
 *
 * @package    Blogsqode
 * @subpackage Blogsqode/public
 * @author     The_Krishna
 */
class Blogsqode_Public extends Blogsqode_Public_Templates {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		add_shortcode( 'blogsqode_blog_list', array( $this, 'blogsqode_views' ) );
		add_action( 'wp_enqueue_scripts', array($this, 'add_blogsqode_public_scripts_func'), 999 );
		add_action('wp_ajax_blogsqode_loadmore', array( $this, 'blogsqode_views_ajax' )); 
		add_action('wp_ajax_nopriv_blogsqode_loadmore', array( $this, 'blogsqode_views_ajax' ));
		$design_mode = esc_attr(get_option('blogsqode_single_post_design_mode'));
		if(esc_attr($design_mode) === 'Unable'){
			add_filter('template_include', array($this, 'blogsqode_templates'));
		}
	}
	function blogsqode_templates( $template ) {
		$post_types = array('post');
		
		if (is_singular($post_types)) {
			
			$template = BLOGSQODE_PLUGIN_PATH.'/public/single/single-blogsqode.php';
		}

		return $template;
	}

	 /**
 * Enqueue a script with jQuery as a dependency.
 */

	 function add_blogsqode_public_scripts_func( $hook_suffix ) {
	 	global $wp_query;
	 	$version = BLOGSQODE_VERSION;
    // first check that $hook_suffix is appropriate for your public page
	 	$post_types = array('post');
	 	$design_mode = esc_attr(get_option('blogsqode_single_post_design_mode'));	 	

	 		wp_enqueue_style( 'blogsqode-public-styles', plugins_url( '/assets/css/blogsqode-public.css', __FILE__ ), array(), $version, 'all' );

	 		wp_enqueue_script( 'blogsqode-public-scripts', plugins_url('/assets/js/blogsqode-public.js', __FILE__ ), array( 'jquery' ), $version );

	 	wp_enqueue_script('jquery');

	 	wp_localize_script( 'blogsqode-public-scripts', 'blogsqode_loadmore_params', array(
		'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
		'posts' => json_encode( $wp_query->query_vars ), // everything about your loop is here
		'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
		'max_page' => $wp_query->max_num_pages
	) );

	 }

	 public function blogsqode_setting_arr_func(){
	 	$settings = array();

	 	$settings['blogsqode_dark_mode']  = esc_attr(get_option('blogsqode_dark_mode'));
	 	$settings['blogsqode_blog_post_grid']  = esc_attr(get_option('blogsqode_blog_post_grid'));
	 	$settings['blogsqode_short_desc_allow']  = strtolower(esc_attr(get_option('blogsqode_short_desc_allow')));
	 	$settings['blogsqode_auhtor_thumb_allow']  = strtolower(esc_attr(get_option('blogsqode_auhtor_thumb_allow')));
	 	$settings['blogsqode_author_name_allow']  = strtolower(esc_attr(get_option('blogsqode_author_name_allow')));
	 	$settings['blogsqode_blog_date_allow']  = strtolower(esc_attr(get_option('blogsqode_blog_date_allow')));
	 	$settings['blogsqode_comment_count_allow']  = strtolower(esc_attr(get_option('blogsqode_comment_count_allow')));
	 	$settings['blogsqode_read_time_allow']  = strtolower(esc_attr(get_option('blogsqode_read_time_allow')));
	 	$settings['blogsqode_category_allow']  = strtolower(esc_attr(get_option('blogsqode_category_allow')));
	 	$settings['blogsqode_read_more_btn_allow']  = strtolower(esc_attr(get_option('blogsqode_read_more_btn_allow')));
	 	$settings['blogsqode_read_more_button_layout']  = esc_attr(get_option('blogsqode_read_more_button_layout'));
	 	return $settings;
	 }
	 public function blogsqode_views(){
	 	ob_start();
	 	$settings = self::blogsqode_setting_arr_func();
	 	$posts_per_page = (esc_attr(get_option('blogsqode_blogs_per_page')))?:12;
	 	$intpostperpage = (int) filter_var($posts_per_page, FILTER_SANITIZE_NUMBER_INT);  
	 	$paged = (int) filter_var(self::blogsqode_paged(), FILTER_SANITIZE_NUMBER_INT);  
	 	$args           = array(
	 		'post_type'      => 'post',
	 		'posts_per_page' => $intpostperpage,
	 		'paged'          => $paged,
	 		'orderby'        => 'date',
	 		'order'          => 'DESC',
	 	);
	 	$loop = new WP_Query( $args );
	 	$max_num_pages             = $loop->max_num_pages;
	 	$pagination_type           = (esc_attr(get_option('blogsqode_blog_pagination_option'))) ?: 'pagination';
	 	$layout = (esc_attr(get_option('blogsqode_blog_layout')))?:'1';
	 	
	 	if ( $loop->have_posts() ) {
	 	?> <div class="blogsqode-blog-wrap">
	 		<div class="selected-layout layout-<?php echo esc_attr($layout); ?> post-grid-<?php echo esc_attr($settings['blogsqode_blog_post_grid']); ?>">
	 			<?php

	 			while ( $loop->have_posts() ) :
	 				$loop->the_post();
	 				$post_id = get_the_ID();
	 				if($layout === '1'){
	 					echo $this->layout_one($settings, $layout);							
	 				} else if($layout === '2' || $layout === '3'){
	 					echo $this->layout_two($settings, $layout);							
	 				} else if($layout === '4'){
	 					echo $this->layout_four($settings, $layout);
	 				} else if($layout === '5'){
	 					echo $this->layout_five($settings, $layout);
	 				} else if($layout === '6'){
	 					echo $this->layout_six($settings, $layout);
	 				}                        
	 			endwhile;
	 			wp_reset_query(); ?>
	 		</div>
	 		</div> <?php 
	 		if($pagination_type === 'pagination'){
	 			echo self::blogsqode_numeric_posts_nav($max_num_pages,$paged);
	 		} else {
	 			echo '<div class="load_more_posts" data-pages="'.esc_attr($max_num_pages).'">'.esc_html__("Load More", "blogsqode").'</div>';
	 		}
	 	}
	 	$content = ob_get_clean();
	 	return $content;
	 }

	 public function blogsqode_views_ajax(){
	 	$settings = self::blogsqode_setting_arr_func();
	 	$posts_per_page = esc_attr(get_option('blogsqode_blogs_per_page')?:'12');
	 	$intpostperpage = (int) filter_var($posts_per_page, FILTER_SANITIZE_NUMBER_INT);  
	 	$paged = (int) filter_var($_POST['page'], FILTER_SANITIZE_NUMBER_INT);  
	 	$args           = array(
	 		'post_type'      => 'post',
	 		'posts_per_page' => $intpostperpage,
	 		'paged'          => $paged,
	 		'orderby'        => 'date',
	 		'order'          => 'DESC',
	 	);
	 	$loop = new WP_Query( $args );
	 	$layout = esc_attr(get_option('blogsqode_blog_layout')?:'1');
	 	$output = '';
	 	if ( $loop->have_posts() ) {

	 		while ( $loop->have_posts() ) :
	 			$loop->the_post();
	 			$post_id = get_the_ID();
	 			if($layout === '1'){
	 				$output .= $this->layout_one($settings, $layout);							
	 			} else if($layout === '2' || $layout === '3'){
	 				$output .= $this->layout_two($settings, $layout);							
	 			} else if($layout === '4'){
	 				$output .= $this->layout_four($settings, $layout);
	 			} else if($layout === '5'){
	 				$output .= $this->layout_five($settings, $layout);
	 			}else if($layout === '6'){
	 				$output .= $this->layout_six($settings, $layout);
	 			}            
	 		endwhile;?>
	 		<?php 
	 	}
	 	wp_reset_postdata();
	 	wp_reset_query();
	 	die($output);
	 }

		/**
	 * Return page
	 *
	 * @return $paged
	 */
		public static function blogsqode_paged() {
			if ( isset( $_SERVER['REQUEST_URI'] ) || strstr( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'paged' ) || strstr( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'page' ) ) {
			if ( isset( $_REQUEST['paged'] ) ) { //phpcs:ignore
				$paged = intval( $_REQUEST['paged'] ); //phpcs:ignore
			} else {
				if ( isset( $_SERVER['REQUEST_URI'] ) ) {
					$uri = explode( '/', sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
				}
				$uri   = array_reverse( $uri );
				$paged = $uri[1];
			}
		} else {
			$paged = 1;
		}
		/* Pagination issue on home page */
		if ( is_front_page() ) {
			$paged = get_query_var( 'page' ) ? intval( get_query_var( 'page' ) ) : 1;
		} else {
			$paged = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		}
		return $paged;
	}/**
	 * Return page
	 *
	 * @return $paged
	 */
	public static function blogsqode_numeric_posts_nav($max_pages, $paged) {
		$paginate = esc_attr(get_option('blogsqode_pagination_layout'));
		$l_class = 'paginate'.$paginate;
		/** Stop execution if there's only 1 page */
		if( $max_pages <= 1 )
			return;

		$paged = $paged ? absint( $paged ) : 1;
		$max   = intval( $max_pages );

		/** Add current page to the array */
		if ( $paged >= 1 )
			$links[] = $paged;

		/** Add the pages around the current page to the array */
		if ( $paged >= 3 ) {
			$links[] = $paged - 1;
			$links[] = $paged - 2;
		}

		if ( ( $paged + 2 ) <= $max ) {
			$links[] = $paged + 2;
			$links[] = $paged + 1;
		}
		$pagination_layout = get_option('blogsqode_pagination_layout');
		echo '<div class="blogsqode-navigation paginate-layout-'.esc_attr($pagination_layout).'"><ul>' . "\n";

		/** Previous Post Link */
		if ( $paged > 1 ){
			printf( '<li><a class="blogsqode_previous_link" href="%s"><span class=%s></span></a></li>' . "\n",  esc_url( get_pagenum_link( $paged-1 ) ), esc_attr($l_class));
		}

		/** Link to first page, plus ellipses if necessary */
		if ( ! in_array( 1, $links ) ) {
			$class = 1 == $paged ? ' class=active' : '';

			printf( '<li%s><a href="%s"><span class=%s></span> %s</a></li>' . "\n", esc_attr($class), esc_url( get_pagenum_link( 1 ) ),esc_attr($l_class), esc_html__('1', 'blogsqode') );

			if ( ! in_array( 2, $links ) )
				echo '<li>…</li>';
		}

		/** Link to current page, plus 2 pages in either direction if necessary */
		sort( $links );
		foreach ( (array) $links as $link ) {
			$class = $paged == $link ? ' class=active' : '';
			printf( '<li%s><a href="%s"><span class=%s></span> %s</a></li>' . "\n", esc_attr($class), esc_url( get_pagenum_link( $link ) ),esc_attr($l_class), esc_html($link,) );
		}

		/** Link to last page, plus ellipses if necessary */
		if ( ! in_array( $max, $links ) ) {
			if ( ! in_array( $max - 1, $links ) )
				echo '<li>…</li>' . "\n";

			$class = $paged == $max ? ' class=active' : '';
			printf( '<li%s><a href="%s"><span class=%s></span> %s</a></li>' . "\n", esc_attr($class), esc_url( get_pagenum_link( $max ) ),esc_attr($l_class), esc_html($max,) );
		}

		/** Next Post Link */
		if($paged < $max){
			printf( '<li><a class="blogsqode_next_link" href="%s"><span class=%s></span></a></li>' . "\n",  esc_url( get_pagenum_link( $paged+1 ) ),esc_attr($l_class) );
		}

		echo '</ul></div>' . "\n";

	}


}


new Blogsqode_Public();