<?php
/**
 * Ajax Search Widget Class
 */
if ( !defined('ABSPATH')) exit;

class Themeidol_ajaxsearch_widget extends WP_Widget {

	/**
	 * __construct
	 *
	 * Setup the widget, register scripts etc
	 *
	 * @return void
	 */
	function __construct() {

		parent::__construct(
			'themeidol-ajaxsearch-widget',
			__( 'Themeidol-AJAX Search', 'themeidol-all-widget' ),
			array( 'classname' => 'themeidol-ajaxsearch-widget', 'description' => __( 'Search form with AJAX results', 'themeidol-all-widget' ) )
		);

		add_action( 'init', array( $this, 'themeidol_ajaxsearch_register_script' ) ) ;
		// Register site styles and scripts
    	add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
		// only load scripts when an instance is active
		if ( is_active_widget( false, false, $this->id_base ) && !is_admin() )
			add_action( 'wp_footer', array( $this, 'themeidolasw_print_script' ) );

		add_action( 'wp_ajax_themeidolasw', array( $this, 'themeidolasw_ajax' ) );
		add_action( 'wp_ajax_nopriv_themeidolasw', array( $this, 'themeidolasw_ajax' ) );
		
	    // Refreshing the widget's cached output with each new post
	    add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
	    add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
	    add_action( 'delete_attachment', array( $this, 'flush_group_cache' ) );
	    add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
	}

	/**
	 * widget
	 *
	 * Output the widget
	 *
	 * @return void
	 */
	function widget( $args, $instance ) {
		    // Check if there is a cached output
			$cache    = (array) wp_cache_get( 'themeidol-ajaxsearch', 'widget' );

		    if(!is_array($cache)) $cache = array();
		
			if(isset($cache[$args['widget_id']])){
				echo $cache[$args['widget_id']];
				return;
			}
		ob_start();
		extract( $args, EXTR_SKIP );
		$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', esc_attr($instance['title']) );
		$username = empty( $instance['username'] ) ? '' : esc_attr($instance['username']);
		$limit = empty( $instance['number'] ) ? 10 : esc_attr($instance['number']);

         // Adding the custom class idol-widget for default widget class
        if (strpos($before_widget, 'widget ') !== false) {
            $before_widget = preg_replace('/widget /', "idol-widget ", $before_widget, 1);
        }
		
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };

			do_action( 'wpasw_before_widget', $instance );

			get_search_form( true );
			?><div class="themeidolasw-results"></div><?php

			do_action( 'wpasw_after_widget', $instance );
			
			echo $after_widget;
			$widget_string = ob_get_flush();
			$cache[$args['widget_id']] = $widget_string;
			wp_cache_add('themeidol-ajaxsearch', $cache, 'widget');
	}
	public function flush_widget_cache() {
    	wp_cache_delete( 'themeidol-ajaxsearch', 'widget' );
  	}

	/**
	 * form
	 *
	 * Edit widget form
	 *
	 * @return void
	 */
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => __( 'Search', 'themeidol-all-widget' ), 'number' => 10 ) );
		$title = esc_attr( $instance['title'] );
		$number = absint( $instance['number'] );
		?>
		<p class="wpasw-title"><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'themeidol-all-widget' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		<p class="wpasw-result-limit"><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Results Limit', 'themeidol-all-widget' ); ?>:</label>
			<select id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" class="widefat">
				<option value="-1" <?php selected( '-1', $number ) ?>><?php _e( 'All', 'themeidol-all-widget' ); ?></option>
				<option value="5" <?php selected( '5', $number ) ?>><?php _e( '5', 'themeidol-all-widget' ); ?></option>
				<option value="10" <?php selected( '10', $number ) ?>><?php _e( '10', 'themeidol-all-widget' ); ?></option>
				<option value="15" <?php selected( '15', $number ) ?>><?php _e( '15', 'themeidol-all-widget' ); ?></option>
				<option value="20" <?php selected( '20', $number ) ?>><?php _e( '20', 'themeidol-all-widget' ); ?></option>
			</select>
		</p>
		<?php

	}
	/**
	 * update
	 *
	 * Save the new widget instance
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = !absint( $new_instance['number'] ) ? 10 : $new_instance['number'];
		return $instance;
	}

	/**
	 * wpasw_register_script
	 *
	 * Register the JS for ajax request
	 *
	 * @return void
	 */
	function themeidol_ajaxsearch_register_script() {
		wp_register_script( 'themeidolasw', THEMEIDOL_WIDGET_JS_URL.'themeidolasw.js', array( 'jquery' ), '1.0', true );

		wp_localize_script( 'themeidolasw', 'themeidolasw', array(
			'ajax_url' => add_query_arg( array( 'action' => 'themeidolasw', '_wpnonce' => wp_create_nonce( 'themeidolasw' ) ), untrailingslashit( set_url_scheme( admin_url( 'admin-ajax.php' ) ) ) ),
		) );
	}

	/**
   	* Registers and enqueues widget-specific styles.
   	*/
	  public function register_widget_styles() {
	    wp_enqueue_style( 'themeidol-ajaxsearch', THEMEIDOL_WIDGET_CSS_URL.'search-style.css');
	  } // end register_widget_styles

	/**
	 * wpasw_print_script
	 *
	 * Output JS only when widget in use on page
	 *
	 * @return void
	 */
	function themeidolasw_print_script() {
		wp_print_scripts( 'themeidolasw' );
	}

	/**
	 * ajax
	 *
	 * Handle the search request
	 *
	 * @return string
	 */
	function themeidolasw_ajax() {

		// verify the nonce
		if ( wp_verify_nonce( $_REQUEST['_wpnonce'], 'themeidolasw' ) ) {

			// clean up the query
			$s = trim( stripslashes( $_POST['s'] ) );

			// cancel if no search term is set
			if ( !$s ) die();

			// get the settings for this widget instance
			$instance = $this->get_settings();
			if ( array_key_exists( $this->number, $instance ) ) {
				$instance = $instance[$this->number];
			}

			do_action( 'themeidolasw_before_results' );

			// set the query limit
			$limit = empty( $instance['number'] ) ? 10: $instance['number'];

			$query_args = apply_filters( 'themeidolasw_query', array( 's' => $s, 'post_status' => 'publish', 'posts_per_page' => $limit ), $s, $limit );

			$search = new WP_Query( $query_args );

			if ( $search->have_posts() ) :

				?><ul class="themeidolasw-result-list"><?php
				while ( $search->have_posts() ) : $search->the_post();

						?>
						<li <?php post_class(); ?>>
							<h5 class="entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h5>
							<div class="entry-date"><time class="published" datetime="<?php the_time( 'Y-m-d\TH:i:s' ) ?>"><?php the_date(); ?></time></div>
						</li>
						<?php
					
				endwhile;

				// link to more?
				if ( $search->max_num_pages > 1 ) {

					
						?>
						<li class="themeidolasw-more-link"><a href="<?php echo esc_url( add_query_arg( array( 's' => $s ) , home_url() ) ); ?>"><?php _e( 'View all search results &hellip;', 'wpasw' ); ?></a></li>
						<?php
					
				}

				?></ul><?php

			else:

					?><div class="alert alert-info"><?php _e( 'No results found.', 'wpasw' ); ?></div><?php
				

			endif;

			wp_reset_postdata();

			do_action( 'themeidolasw_after_results' );
		}

		die();
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget("Themeidol_ajaxsearch_widget");' ) );