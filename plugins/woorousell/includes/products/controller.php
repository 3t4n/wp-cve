<?php
/**
 * products Controller Class
 *
 * @author 		MojofyWP
 * @package 	includes/products
 * 
 */

if ( !class_exists('WRSL_Products_Controller') ) :

class WRSL_Products_Controller {

	/**
	 * instance
	 *
	 * @access private
	 * @var array
	 */
	private $_instance = null;

	/**
	 * model
	 *
	 * @access private
	 * @var object
	 */
	private $_model = null;

	/**
	 * hook prefix
	 *
	 * @access private
	 * @var string
	 */
	private $_hook_prefix = null;

	/**
	 * Get instance
	 *
	 * @access public
	 * @return array
	 */
	public function get_instance() {
		return $this->_instance;
	}

	/**
	 * Get Model
	 *
	 * @access public
	 * @return WRSL_Products_Model
	 */
	public function get_model() {
		return $this->_model;
	}
	
	/**
	 * Class Constructor
	 * @param array $args {
	 * 		@type string feature - feature slug
	 * }
	 * @access private
	 */
    function __construct( $args = array() ) {

		$this->_hook_prefix = wrsl()->plugin_hook() . 'products/';
		$defaults = $this->default_value();
		$defaults['hook_prefix'] = $this->_hook_prefix;

		$instance = apply_filters( $this->_hook_prefix . 'args' , wp_parse_args( $args, $defaults ) , $args , $defaults );

    	// setup variables
    	$this->_instance = $instance;
    	$this->_model = new WRSL_Products_Model( $this->_instance );

    }

	/**
	 * default values
	 *
	 * @access public
	 */
	public function default_value() {
		return apply_filters( $this->_hook_prefix . 'default_value' , array(
			'widget_id' => null,
			'component_id' => rand( 1 , 9999 ),
            'total_col' => 3,
            'widget_bg' => '',
            'col_bg' => '#F5F5F5',

            'post_type' => 'product',
            'taxonomy' => 'product_cat',
            'box_style' => 'style-1',
            'text_style' => 'regular',
            'category' => 0,
            'show_media' => true,
            'show_titles' => true,
            'show_excerpts' => true,
            'show_price' => true,
            'show_badges' => true,
            'show_ratings' => true,
            'show_buy_button' => true,
            'excerpt_length' => 200,
            'posts_per_page' => 6,
            'order' => null,
			'content_align' => 'text-left',
            
            'c_mode' => 'horizontal',
            'c_speed' => 500,
            'c_moveone' => true,
            'c_slidemargin' => 10,
            'c_randomstart' => false,
            'c_adaptiveheight' => false,
            'c_adaptiveheightspeed' => 500,
            'c_touchenabled' => true,
            'c_swipethreshold' => 50,
            'c_auto' => false,
            'c_pause' => 4000,
            'c_autohover' => false,
            'c_autodelay' => 0,
            'c_ticker' => false,
            'c_ticker_hover' => true,
            'controller_type' => 'center',
            'controller_icon' => 'caret',
		) );
	}

	/**
	 * Render
	 *
	 * @access public
	 */
	public function render() {

		$view = new WRSL_Products_View();

		$post_query = $this->_model->get_query();

		if ( empty( $post_query ) )
			return;

		$show_control = !$this->_instance['c_ticker'] ? true : false;
		$top_control = ( strpos( $this->_instance['controller_type'] , 'top-' ) !== false ? true : false  );

		$control_args = array(
				'widget_id' => $this->_instance['widget_id'],
				'type' => $this->_instance['controller_type'],
				'prev_icon' => 'fa-'.$this->_instance['controller_icon'].'-' . ( $this->_instance['c_mode'] == 'vertical' ? 'up' : 'left' ),
				'next_icon' => 'fa-'.$this->_instance['controller_icon'].'-' . ( $this->_instance['c_mode'] == 'vertical' ? 'down' : 'right' ),
			);

		ob_start();
		?>
		<div class="wrsl-carousel-container" <?php echo $this->render_carousel_data(); ?>>
			<?php if ( $post_query->have_posts() ) {

				if ( $top_control && $show_control ) {
					$view->render_controller( $control_args ); 
				}
			?>
				<div class="wrsl-carousel">
				<?php 
				while ( $post_query->have_posts() ) {
					$post_query->the_post();

					$single_args = array(
							'widget_id' => $this->_instance['widget_id'],
							'id' => get_the_ID(),
							'widget_bg' => $this->_instance['widget_bg'],
							'col_bg' => $this->_instance['col_bg'],
							'price_bg' => $this->_instance['price_bg'],
							'sale_badge_bg' => $this->_instance['sale_badge_bg'],
							'box_style' => $this->_instance['box_style'],
							'text_style' => $this->_instance['text_style'],
							'show_media' => $this->_instance['show_media'],
							'show_titles' => $this->_instance['show_titles'],
							'show_excerpts' => $this->_instance['show_excerpts'],
				            'show_price' => $this->_instance['show_price'],
				            'show_badges' => $this->_instance['show_badges'],
				            'show_ratings' => $this->_instance['show_ratings'],
							'excerpt_length' => $this->_instance['excerpt_length'],
				            'show_buy_button' => $this->_instance['show_buy_button'],
							'content_align' => $this->_instance['content_align'],
						);
				?>
					<div class="wrsl-carousel-item">
					<?php
						$view->render_single_box( $single_args );
					?>
					</div><!-- .wrsl-carousel-item -->
				<?php } ?>
				</div>
			<?php 
				if ( !$top_control && $show_control ) {
					$view->render_controller( $control_args ); 
				}
			
			} else { ?>	
				<p><?php _e( 'No product(s) found.' , WRSL_SLUG ); ?></p>
			<?php } ?>
		</div><!-- .wrsl-carousel-container -->
		<?php
		wp_reset_postdata();
		$html = ob_get_clean();

		echo apply_filters( $this->_hook_prefix  . 'render' , ( !empty( $html ) ? $html : '' ) , $this );
	}

	/**
	 * Render carousel data
	 *
	 * @access public
	 */
	public function render_carousel_data() {

		$output = '
			data-mode="'.$this->_instance['c_mode'].'"
			data-speed="'.intval( $this->_instance['c_speed'] ).'"
			data-maxslides="'.intval( $this->_instance['total_col'] ).'"
			data-moveslides="'.( $this->_instance['c_moveone'] ? 1 : 0 ).'"
			data-slidemargin="'.intval( $this->_instance['c_slidemargin'] ).'"
			data-randomstart='.( $this->_instance['c_randomstart'] ? "true" : "false" ).'
			data-adaptiveheight='.( $this->_instance['c_adaptiveheight'] ? "true" : "false" ).'
			data-adaptiveHeightspeed="'.intval( $this->_instance['c_adaptiveheightspeed'] ).'"
			data-touchenabled='.( $this->_instance['c_touchenabled'] ? "true" : "false" ).'
			data-auto='.( $this->_instance['c_auto'] ? "true" : "false" ).'
			data-pause="'.intval( $this->_instance['c_pause'] ).'"
			data-autohover='.( $this->_instance['c_autohover'] ? "true" : "false" ).'
			data-autodelay="'.intval( $this->_instance['c_autodelay'] ).'"
			data-ticker='.( $this->_instance['c_ticker'] ? "true" : "false" ).'
			data-tickerhover='.( $this->_instance['c_ticker_hover'] ? "true" : "false" ).'
		';

		return $output;
	}

	/* END
	------------------------------------------------------------------- */

} // end - class WRSL_Products_Controller

endif; // end - !class_exists('WRSL_Products_Controller')