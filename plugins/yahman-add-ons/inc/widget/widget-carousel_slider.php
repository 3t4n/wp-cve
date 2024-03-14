<?php
/**
 * Widget Slider with Carousel Slider
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_carousel_slider_widget extends WP_Widget {


	function __construct() {

		parent::__construct(
			'ya_carousel', // Base ID
			esc_html__( '[YAHMAN Add-ons] Slider with Carousel Slider', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Display thumbnail carousel', 'yahman-add-ons' ), ) // Args
		);
	}

	private $settings;

	/**
	 * Set default settings of the widget
	 */
	private function default_settings() {

		$defaults = array(
			'title'    => '',
			'category' => 0,
			'number_post'   => 6,
			'orderby' => 'date',

			'post_type' => 'post',
			'post_not_in' => '',
			'category_not_in' => '',

			'sticky_posts' => true,

			'image_size' => 'medium',
			'date' => true,

			'thumbnail' => true,

			'animation' => 'slide',//fade,slide
			'direction' => 'horizontal',//horizontal,vertical
			'reverse' => false,//false
			'animationLoop' => true,//true
			'smoothHeight' => false,//false
			'startAt' => 0,//0
			'slideshow' => true,//true
			'slideshowSpeed' => 7000,//7000
			'animationSpeed' => 600,//600
			'initDelay' => 0,//
						'randomize' => false,//false

			'pauseOnAction' => true,//true
			'pauseOnHover' => false,//false

			'controlNav' => false,//true
			'directionNav' => true,//true
			'directionNavText' => false,//false

			'prevText' => esc_html_x( 'Previous', 'Carousel slider' ,'yahman-add-ons' ),//Previous
			'nextText' => esc_html_x( 'Next', 'Carousel slider' ,'yahman-add-ons' ),//Next

			'mousewheel' => false,//false
			'pausePlay' => false,//false
			'pausePlayText' => false,//false

			'pauseText' => esc_html_x( 'Pause', 'Carousel slider' ,'yahman-add-ons' ),//Pause
			'playText' => esc_html_x( 'Play', 'Carousel slider' ,'yahman-add-ons' ),//Play

		);

		return $defaults;
	}

	public function widget( $args, $instance ) {



		$settings = wp_parse_args( $instance, $this->default_settings() );

		$settings['widget_id_num'] = str_replace('ya_carousel-', '' , $args['widget_id']);

		$post_type = array('post','page');
		if($settings['post_type'] !== 'both') $post_type = array($settings['post_type']);

		$post_not_in = explode(',', $settings['post_not_in']);
		$category_not_in = explode(',', $settings['category_not_in']);

		$latest_posts = new WP_Query(
			array(
				'post_type'             => $post_type,
				'cat'					=> $settings['category'],
				'posts_per_page'		=> $settings['number_post'],
				'post_status'			=> 'publish',
				'ignore_sticky_posts' 	=> $settings['sticky_posts'],
				'orderby'               => $settings['orderby'],
				'post__not_in' => $post_not_in,
				'category__not_in' => $category_not_in,
			)
		);
		if ( $latest_posts->have_posts() ) :
			//$args['before_widget'] = str_replace( 'widget_custom_hp_slider','widget_custom_hp_slider fit_content',$args['before_widget']);
			$args['before_widget'] = str_replace( '">','" style="padding:0;max-width:none;">',$args['before_widget']);

			echo $args['before_widget'];

			$this->scripts( $settings );
			$this->slider( $settings , $latest_posts , $args );



			echo $args['after_widget'];
		endif;

		wp_reset_query();  

	}


	public function form( $instance ) {

	    // Get Widget Settings.
		$settings = wp_parse_args( $instance, $this->default_settings() );

		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'yahman-add-ons' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php esc_html_e( 'Category&#58;', 'yahman-add-ons' ); ?></label>
			<?php // Display Category Select.
			$args = array(
				'show_option_all'    => esc_html__( 'All Categories', 'yahman-add-ons' ),
				'show_count' 		 => true,
				//'hide_empty'		 => true,
				'selected'           => $settings['category'],
				'name'               => $this->get_field_name( 'category' ),
				'id'                 => $this->get_field_id( 'category' ),
				//'depth'              => 0,
				'hierarchical'		 => true,
			);
			wp_dropdown_categories( $args );
			?>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number_post' ) ); ?>"><?php esc_html_e( 'Number of posts', 'yahman-add-ons' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number_post' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number_post' ) ); ?>" type="number" step="1" min="1" max="20" value="<?php echo absint( $settings['number_post'] ); ?>" />
		</p>


		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>">
				<?php esc_html_e( 'Order by', 'yahman-add-ons' ); ?>
			</label><br />
			<select id="<?php echo esc_attr( $this->get_field_id( 'orderby' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' )); ?>">
				<?php
				$dummy = array(
					'date' => esc_html__( 'Date' ,'yahman-add-ons' ),
					'modified' => esc_html__( 'Modified' ,'yahman-add-ons' ),
					'rand' => esc_html__( 'Randomize' ,'yahman-add-ons' ),
					'comment_count' => esc_html__( 'Comment count' ,'yahman-add-ons' ),
					'author' => esc_html__( 'Author' ,'yahman-add-ons' ),
					'title' => esc_html__( 'Title' ,'yahman-add-ons' ),
					'ID' => esc_html__( 'ID' ,'yahman-add-ons' ),
				);
				foreach ($dummy as $key => $value) {
					echo '<option '. selected( $settings['orderby'], $key ) .' value="'.esc_attr($key).'" >'.esc_html($value).'</option>';
				}
				?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>">
				<?php esc_html_e( 'Post type', 'yahman-add-ons' ); ?>
			</label><br />
			<select id="<?php echo esc_attr( $this->get_field_id( 'post_type' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_type' )); ?>">
				<?php
				$dummy = array(
					'post' => esc_html__( 'Post' ,'yahman-add-ons' ),
					'page' => esc_html__( 'Page' ,'yahman-add-ons' ),
					'both' => esc_html__( 'Post and Page' ,'yahman-add-ons' ),
				);
				foreach ($dummy as $key => $value) {
					echo '<option '. selected( $settings['post_type'], $key ) .' value="'.esc_attr($key).'" >'.esc_html($value).'</option>';
				}
				?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'category_not_in' ) ); ?>">
				<?php esc_html_e( 'Disappear when you type category id.', 'yahman-add-ons' ); ?><br /><?php echo sprintf( esc_html__('Multiple %s must be separated by a comma.', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'category_not_in' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category_not_in' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['category_not_in'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_not_in' ) ); ?>"><?php esc_html_e( 'Disappear when you type post id.', 'yahman-add-ons' ); ?><br /><?php echo sprintf( esc_html__('Multiple %s must be separated by a comma.', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_not_in' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_not_in' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['post_not_in'] ); ?>">
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['sticky_posts'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id('sticky_posts'); ?>" name="<?php echo $this->get_field_name('sticky_posts'); ?>" />
			<label for="<?php echo $this->get_field_id('sticky_posts'); ?>"><?php esc_html_e( 'Ignore sticky posts', 'yahman-add-ons' ); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'image_size' ) ); ?>">
				<?php esc_html_e( 'Original size of image', 'yahman-add-ons' ); ?>
			</label><br />
			<select id="<?php echo esc_attr( $this->get_field_id( 'image_size' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_size' )); ?>">
				<?php
				$dummy = array(
					'thumbnail' => esc_html__( 'Thumbnail', 'yahman-add-ons' ),
					'medium' => esc_html__( 'Medium', 'yahman-add-ons' ),
					'large' => esc_html__( 'Large', 'yahman-add-ons' ),
					'full' => esc_html__( 'Full', 'yahman-add-ons' ),
				);
				foreach ($dummy as $key => $value) {
					echo '<option '. selected( $settings['image_size'], $key ) .' value="'.esc_attr($key).'" >'.esc_html($value).'</option>';
				}
				?>
			</select>
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['date'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id('date'); ?>" name="<?php echo $this->get_field_name('date'); ?>" />
			<label for="<?php echo $this->get_field_id('date'); ?>"><?php esc_html_e( 'Display date', 'yahman-add-ons' ); ?></label>
		</p>


		<p>
			<input type="checkbox" <?php checked( $settings['thumbnail'], true ) ?> class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'thumbnail' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumbnail' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'thumbnail' ) ); ?>">
				<?php esc_html_e( 'Show thumbnail', 'yahman-add-ons' ); ?>
			</label>
		</p>

		<p style="background:#000;padding:4px 8px;color:#fff;margin-bottom:-8px;">
			<?php esc_html_e( 'Basic option', 'yahman-add-ons' ); ?>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'animation' ) ); ?>">
				<?php esc_html_e( 'Animation', 'yahman-add-ons' ); ?>
			</label><br />
			<select id="<?php echo esc_attr( $this->get_field_id( 'animation' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'animation' )); ?>">
				<?php
				$dummy = array(
					'fade' => esc_html_x( 'Fade', 'Carousel slider' ,'yahman-add-ons' ),
					'slide' => esc_html_x( 'Slide', 'Carousel slider' ,'yahman-add-ons' ),
				);
				foreach ($dummy as $key => $value) {
					echo '<option '. selected( $settings['animation'], $key ) .' value="'.esc_attr($key).'" >'.esc_html($value).'</option>';
				}
				?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'animationSpeed' ) ); ?>"><?php esc_html_e( 'Animation speed', 'yahman-add-ons' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'animationSpeed' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'animationSpeed' ) ); ?>" type="number" step="1" min="0" max="70000" value="<?php echo esc_attr( $settings['animationSpeed'] ); ?>" />
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['animationLoop'], true ) ?> class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'animationLoop' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'animationLoop' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'animationLoop' ) ); ?>">
				<?php esc_html_e( 'Animation loop', 'yahman-add-ons' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'direction' ) ); ?>">
				<?php esc_html_e( 'Direction', 'yahman-add-ons' ); ?>
			</label><br />
			<select id="<?php echo esc_attr( $this->get_field_id( 'direction' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'direction' )); ?>">
				<?php
				$dummy = array(
					'horizontal' => esc_html_x( 'Horizontal', 'Carousel slider' ,'yahman-add-ons' ),
					'vertical' => esc_html_x( 'Vertical', 'Carousel slider' ,'yahman-add-ons' ),
				);
				foreach ($dummy as $key => $value) {
					echo '<option '. selected( $settings['direction'], $key ) .' value="'.esc_attr($key).'" >'.esc_html($value).'</option>';
				}
				?>
			</select>
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['reverse'], true ) ?> class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'reverse' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'reverse' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'reverse' ) ); ?>">
				<?php esc_html_e( 'Reverse', 'yahman-add-ons' ); ?>
			</label>
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['smoothHeight'], true ) ?> class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'smoothHeight' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'smoothHeight' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'smoothHeight' ) ); ?>">
				<?php esc_html_e( 'Smooth height', 'yahman-add-ons' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'startAt' ) ); ?>"><?php esc_html_e( 'Start at', 'yahman-add-ons' ); ?></label>
			<input class="" id="<?php echo esc_attr( $this->get_field_id( 'startAt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'startAt' ) ); ?>" type="number" step="1" min="0" max="20" value="<?php echo esc_attr( $settings['startAt'] ); ?>" />
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['slideshow'], true ) ?> class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'slideshow' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'slideshow' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'slideshow' ) ); ?>">
				<?php esc_html_e( 'Slide show', 'yahman-add-ons' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'slideshowSpeed' ) ); ?>"><?php esc_html_e( 'Slide show speed', 'yahman-add-ons' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'slideshowSpeed' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'slideshowSpeed' ) ); ?>" type="number" step="1" min="0" max="70000" value="<?php echo esc_attr( $settings['slideshowSpeed'] ); ?>" />
		</p>



		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'initDelay' ) ); ?>"><?php esc_html_e( 'init Delay', 'yahman-add-ons' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'initDelay' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'initDelay' ) ); ?>" type="number" step="1" min="0" max="70000" value="<?php echo esc_attr( $settings['initDelay'] ); ?>" />
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['randomize'], true ) ?> class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'randomize' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'randomize' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'randomize' ) ); ?>">
				<?php esc_html_e( 'Randomize', 'yahman-add-ons' ); ?>
			</label>
		</p>

		<p style="background:#000;padding:4px 8px;color:#fff;margin-bottom:-8px;">
			<?php esc_html_e( 'Usability', 'yahman-add-ons' ); ?>
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['pauseOnAction'], true ) ?> class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'pauseOnAction' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pauseOnAction' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'pauseOnAction' ) ); ?>">
				<?php esc_html_e( 'Pause on action', 'yahman-add-ons' ); ?>
			</label>
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['pauseOnHover'], true ) ?> class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'pauseOnHover' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pauseOnHover' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'pauseOnHover' ) ); ?>">
				<?php esc_html_e( 'Pause on hover', 'yahman-add-ons' ); ?>
			</label>
		</p>

		<p style="background:#000;padding:4px 8px;color:#fff;margin-bottom:-8px;">
			<?php esc_html_e( 'Control', 'yahman-add-ons' ); ?>
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['controlNav'], true ) ?> class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'controlNav' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'controlNav' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'controlNav' ) ); ?>">
				<?php esc_html_e( 'Control nav', 'yahman-add-ons' ); ?>
			</label>
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['mousewheel'], true ) ?> class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'mousewheel' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'mousewheel' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'mousewheel' ) ); ?>">
				<?php esc_html_e( 'Mouse wheel', 'yahman-add-ons' ); ?>
			</label>
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['directionNav'], true ) ?> class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'directionNav' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'directionNav' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'directionNav' ) ); ?>">
				<?php esc_html_e( 'Direction nav', 'yahman-add-ons' ); ?>
			</label>
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['directionNavText'], true ) ?> class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'directionNavText' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'directionNavText' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'directionNavText' ) ); ?>">
				<?php esc_html_e( 'Direction nav text', 'yahman-add-ons' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'prevText' ); ?>"><?php esc_html_e( 'Previous text:', 'yahman-add-ons' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'prevText' ); ?>" name="<?php echo $this->get_field_name( 'prevText' ); ?>" type="text" value="<?php echo esc_attr( $settings['prevText'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'nextText' ); ?>"><?php esc_html_e( 'Next text:', 'yahman-add-ons' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'nextText' ); ?>" name="<?php echo $this->get_field_name( 'nextText' ); ?>" type="text" value="<?php echo esc_attr( $settings['nextText'] ); ?>" />
		</p>


		<p style="background:#000;padding:4px 8px;color:#fff;margin-bottom:-8px;">
			<?php esc_html_e( 'Navigation', 'yahman-add-ons' ); ?>
		</p>



		<p>
			<input type="checkbox" <?php checked( $settings['pausePlay'], true ) ?> class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'pausePlay' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pausePlay' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'pausePlay' ) ); ?>">
				<?php esc_html_e( 'Pause play', 'yahman-add-ons' ); ?>
			</label>
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['pausePlayText'], true ) ?> class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'pausePlayText' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pausePlayText' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'pausePlayText' ) ); ?>">
				<?php esc_html_e( 'Pause play text', 'yahman-add-ons' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'pauseText' ); ?>"><?php esc_html_e( 'Pause text:', 'yahman-add-ons' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'pauseText' ); ?>" name="<?php echo $this->get_field_name( 'pauseText' ); ?>" type="text" value="<?php echo esc_attr( $settings['pauseText'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'playText' ); ?>"><?php esc_html_e( 'Play text:', 'yahman-add-ons' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'playText' ); ?>" name="<?php echo $this->get_field_name( 'playText' ); ?>" type="text" value="<?php echo esc_attr( $settings['playText'] ); ?>" />
		</p>



		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['category'] = (int) $new_instance['category'];
		$instance['number_post'] = (int) $new_instance['number_post'];
		$instance[ 'sticky_posts' ] = (bool)$new_instance[ 'sticky_posts' ];
		$instance[ 'post_type' ] = sanitize_text_field( $new_instance['post_type'] );

		$instance['post_not_in'] = sanitize_text_field( $new_instance['post_not_in'] );
		$instance['category_not_in'] = sanitize_text_field( $new_instance['category_not_in'] );

		$instance[ 'orderby' ] = sanitize_text_field( $new_instance['orderby'] );
		$instance['image_size'] = sanitize_text_field( $new_instance['image_size'] );
		$instance[ 'date' ] = (bool)$new_instance[ 'date' ];

		$instance[ 'thumbnail' ] = (bool)$new_instance[ 'thumbnail' ];

		$instance['animation'] = sanitize_text_field( $new_instance['animation'] );
		$instance['direction'] = sanitize_text_field( $new_instance['direction'] );
		$instance[ 'reverse' ] = (bool)$new_instance[ 'reverse' ];
		$instance[ 'animationLoop' ] = (bool)$new_instance[ 'animationLoop' ];
		$instance[ 'smoothHeight' ] = (bool)$new_instance[ 'smoothHeight' ];
		$instance['startAt'] = sanitize_text_field( $new_instance['startAt'] );
		$instance[ 'slideshow' ] = (bool)$new_instance[ 'slideshow' ];
		$instance['slideshowSpeed'] = sanitize_text_field( $new_instance['slideshowSpeed'] );
		$instance['animationSpeed'] = sanitize_text_field( $new_instance['animationSpeed'] );
		$instance['initDelay'] = sanitize_text_field( $new_instance['initDelay'] );
		$instance[ 'randomize' ] = (bool)$new_instance[ 'randomize' ];

		$instance[ 'pauseOnAction' ] = (bool)$new_instance[ 'pauseOnAction' ];
		$instance[ 'pauseOnHover' ] = (bool)$new_instance[ 'pauseOnHover' ];

		$instance[ 'controlNav' ] = (bool)$new_instance[ 'controlNav' ];
		$instance[ 'directionNav' ] = (bool)$new_instance[ 'directionNav' ];
		$instance[ 'directionNavText' ] = (bool)$new_instance[ 'directionNavText' ];

		$instance['prevText'] = sanitize_text_field( $new_instance['prevText'] );
		$instance['nextText'] = sanitize_text_field( $new_instance['nextText'] );

		$instance[ 'mousewheel' ] = (bool)$new_instance[ 'mousewheel' ];
		$instance[ 'pausePlay' ] = (bool)$new_instance[ 'pausePlay' ];
		$instance[ 'pausePlayText' ] = (bool)$new_instance[ 'pausePlayText' ];

		$instance['pauseText'] = sanitize_text_field( $new_instance['pauseText'] );
		$instance['playText'] = sanitize_text_field( $new_instance['playText'] );

		return $instance;
	}

	public function scripts($settings){

		//add_action('wp_enqueue_scripts', array($this, 'scripts'));
		wp_enqueue_script('flexslider_'.$settings['widget_id_num'],YAHMAN_ADDONS_URI . 'assets/js/flexslider/jquery.flexslider.min.js', array('jquery'), null , true );

		$inline_script = 'jQuery(\'#slider_'.$settings['widget_id_num'].'\').flexslider({
			animation: "'.$settings['animation'].'",
			animationSpeed: '.$settings['animationSpeed'].',
			animationLoop: '.$settings['animationLoop'].',
			direction: "'.$settings['direction'].'",
			reverse: '.($settings['reverse'] ? 'true':'false').',
			smoothHeight: '.($settings['smoothHeight'] ? 'true':'false').',
			startAt: '.$settings['startAt'].',
			slideshow: '.($settings['slideshow'] ? 'true':'false').',
			slideshowSpeed: '.$settings['slideshowSpeed'].',
			initDelay: '.$settings['initDelay'].',
			randomize: '.($settings['randomize'] ? 'true':'false').',

			pauseOnAction: '.($settings['pauseOnAction'] ? 'true':'false').',
			pauseOnHover: '.($settings['pauseOnHover'] ? 'true':'false').',

			controlNav: '.($settings['controlNav'] ? 'true':'false').',
			directionNav: '.($settings['directionNav'] ? 'true':'false').',
			prevText: "'.$settings['prevText'].'",
			nextText: "'.$settings['nextText'].'",

			mousewheel: '.($settings['mousewheel'] ? 'true':'false').',
			pausePlay: '.($settings['pausePlay'] ? 'true':'false').',
			pauseText: "'.$settings['pauseText'].'",
			playText: "'.$settings['playText'].'",


			selector: ".slides > .slide",
		});';

		if($settings['thumbnail']){
			$inline_script = str_replace('selector: ".slides > .slide",' , 'sync: "#carousel_'.$settings['widget_id_num'].'",selector: ".slides > .slide",' ,$inline_script);
			$inline_script = 'jQuery(\'#carousel_'.$settings['widget_id_num'].'\').flexslider({
				animation: "slide",
				controlNav: false,
				animationLoop: false,
				slideshow: '.$settings['slideshow'].',
				mousewheel: '.($settings['mousewheel'] ? 'true':'false').',
				pauseOnAction: '.($settings['pauseOnAction'] ? 'true':'false').',
				itemWidth: 144,
				itemMargin: 8,
				asNavFor: "#slider_'.$settings['widget_id_num'].'",
				selector: ".slides > .slide",
			});' . $inline_script;
		}

		
		$inline_script = preg_replace('/(^[\s\t]+|[\s\t]+$|^\n)/mu', '', $inline_script);
		
		$inline_script = preg_replace('/[\s\t]{2,}/u', ' ', $inline_script);
		
		$inline_script = str_replace(array("\r", "\n"), '', $inline_script);

		wp_add_inline_script( 'flexslider_'.$settings['widget_id_num'], 'jQuery(document).ready(function(){'.$inline_script.'});');

		wp_enqueue_style('flexslider', YAHMAN_ADDONS_URI . 'assets/css/flexslider.min.css', array(), null);

	}




	public function slider( $settings , $latest_posts ){
		$has_thum = '';
		if($settings['thumbnail']) $has_thum .= ' has_thum';
		if($settings['directionNavText']) $has_thum .= ' has_navtext';
		if($settings['pausePlayText']) $has_thum .= ' has_playtext';


		
		require_once YAHMAN_ADDONS_DIR . 'inc/get_thumbnail.php';
		?>
		<div class="slider_wrap fit_content relative<?php echo $has_thum; ?>">
			<?php
			if ( ! empty($settings['title']) ) {
				echo '<div class="slider_title absolute z3 top0 left0 dib m0 hp_p b_mask">'. $settings['title'] .  '</div>';
			}
			?>


			<div id="slider_<?php echo $settings['widget_id_num']; ?>" class="flexslider">

				<div class="slides">

					<?php
					while( $latest_posts->have_posts() ) : $latest_posts->the_post();
						global $post;
						$thumurl = yahman_addons_get_thumbnail( $post->ID , $settings['image_size']);
						$title = $post ->post_title;

						$this_url = get_permalink();


						?>
						<div class="slide">
							<div class="relative">
								<a href="<?php echo esc_url($this_url); ?>" class="non_hover tap_no db w100 h100 absolute z1"></a>
								<div class="fit_box_img_wrap slider_img_wrap">

									<img decoding="async" src="<?php echo esc_url( $thumurl[0] ); ?>" width="<?php echo esc_attr($thumurl[1]); ?>" height="<?php echo esc_attr($thumurl[2]); ?>" alt="<?php echo esc_attr($title); ?>" class="slider_img" />

								</div>
								<div class="slider_info b_mask absolute z1 bottom0 left0 hp_p w100 bc_shadow">
									<div class="slider_category dn100">
										<?php
										$category = get_the_category();
										if(!empty($category)){
											echo '<a href="' . esc_url(get_category_link( $category[0]->term_id )) . '" class="post_card_category fsS non_hover">' . esc_html($category[0]->cat_name) . '</a>';
										}
										?>
									</div>
									<div>
										<a href="<?php echo esc_url($this_url); ?>" rel="bookmark" class="slider_title_a">
											<h3 class="slider_title" style="color:inherit;"><?php echo esc_html($title); ?></h3>
										</a>
									</div>
									<div class='slider_date dn100 fsS'>
										<?php echo get_the_date(); ?>
									</div>
								</div>
							</div>

						</div>

						<?php

					endwhile;

					?>

				</div>
			</div>

			<?php $latest_posts->rewind_posts();

			if($settings['thumbnail']):
				?>

				<div id="carousel_<?php echo $settings['widget_id_num']; ?>" class="flexslider b_mask">
					<div class="slides">
						<?php
						while( $latest_posts->have_posts() ) : $latest_posts->the_post();
							global $post;
							$thumurl = yahman_addons_get_thumbnail( $post->ID , 'thumbnail' );

							?>

							<div class="slide">
								<div class="fit_box_img_wrap"><img class="slider_thum_img" decoding="async" src="<?php echo esc_url( $thumurl[0] ); ?>" width="<?php echo esc_attr($thumurl[1]); ?>" height="<?php echo esc_attr($thumurl[2]); ?>" alt="<?php echo esc_attr($title); ?>" /></div>
							</div>

							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</div>
			<?php endif ?>
		</div>
		<?php
	}



} // class yahman_addons_carousel_slider_widget
