<?php
/**
 * Widget Articles line up
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_articles_line_up_widget extends WP_Widget {


	function __construct() {
		parent::__construct(
			'ya_alu', // Base ID
			esc_html__( '[YAHMAN Add-ons] Articles line up', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Arrange to row', 'yahman-add-ons' ), ) // Args
		);
	}

	/**
	 * Set default settings of the widget
	 */
	private function default_settings() {

		$defaults = array(

			'title'    => esc_html__( 'Feature articles', 'yahman-add-ons' ),
			'category' => 0,
			'number_post'   => 2,
			'orderby' => 'date',

			'post_type' => 'post',
			'post_not_in' => '',
			'category_not_in' => '',

			'sticky_posts' => true,

			'columns' => 2,
			'flex_column' => true,
			'thum_size' => 'medium',
			'thumbnail' => true,
			'thum_space' => false,
			'description' => false,
			'view_date' => false,
			'view_category' => false,
		);

		return $defaults;
	}

	public function widget( $args, $instance ) {

		$settings = wp_parse_args( $instance, $this->default_settings() );


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
			echo $args['before_widget'];
			if ( ! empty($settings['title']) ) {
				echo $args['before_title']. $settings['title'] .  $args['after_title'];
			}

			
			require_once YAHMAN_ADDONS_DIR . 'inc/get_thumbnail.php';

			require_once YAHMAN_ADDONS_DIR . 'inc/template-parts/flex_articles.php';

			echo '<div class="alu f_box jc_sb f_wrap">';

			$settings['columns'] = ' f_'.esc_attr($settings['columns']);
			$i=0;
			while ( $latest_posts->have_posts() ) : $latest_posts->the_post();
				global $post;

				yahman_addons_flex_articles($post,$settings);






			endwhile;
			echo '</div>';
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
			<label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>">
				<?php esc_html_e( 'Columns', 'yahman-add-ons' ); ?>
			</label><br />
			<select id="<?php echo esc_attr( $this->get_field_id( 'columns' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'columns' )); ?>">
				<?php
				$columns_list = array(
					1 => esc_html__( 'one column', 'yahman-add-ons' ),
					2 => esc_html__( 'two columns', 'yahman-add-ons' ),
					3 => esc_html__( 'three columns', 'yahman-add-ons' ),
					4 => esc_html__( 'four columns', 'yahman-add-ons' ),
				);
				foreach ($columns_list as $key => $value) {
					echo '<option '. selected( $settings['columns'], $key ) .' value="'.esc_attr($key).'" >'.esc_html($value).'</option>';
				}
				?>
			</select>
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['thumbnail'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id('thumbnail'); ?>" name="<?php echo $this->get_field_name('thumbnail'); ?>" />
			<label for="<?php echo $this->get_field_id('thumbnail'); ?>"><?php esc_html_e( 'Display thumbnail', 'yahman-add-ons' ); ?></label>
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['thum_space'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id('thum_space'); ?>" name="<?php echo $this->get_field_name('thum_space'); ?>" />
			<label for="<?php echo $this->get_field_id('thum_space'); ?>"><?php esc_html_e( 'Display thumbnail space', 'yahman-add-ons' ); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'thum_size' ) ); ?>">
				<?php esc_html_e( 'Original size of image', 'yahman-add-ons' ); ?>
			</label><br />
			<select id="<?php echo esc_attr( $this->get_field_id( 'thum_size' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thum_size' )); ?>">
				<?php
				$image_size = array(
					'thumbnail' => esc_html__( 'Thumbnail', 'yahman-add-ons' ),
					'medium' => esc_html__( 'Medium', 'yahman-add-ons' ),
					'large' => esc_html__( 'Large', 'yahman-add-ons' ),
					'full' => esc_html__( 'Full', 'yahman-add-ons' ),
				);
				foreach ($image_size as $key => $value) {
					echo '<option '. selected( $settings['thum_size'], $key ) .' value="'.esc_attr($key).'" >'.esc_html($value).'</option>';
				}
				?>
			</select>
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['flex_column'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id('flex_column'); ?>" name="<?php echo $this->get_field_name('flex_column'); ?>" />
			<label for="<?php echo $this->get_field_id('flex_column'); ?>"><?php esc_html_e( 'Display column', 'yahman-add-ons' ); ?></label>
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['description'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" />
			<label for="<?php echo $this->get_field_id('description'); ?>"><?php esc_html_e( 'Display description', 'yahman-add-ons' ); ?></label>
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['view_category'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id('view_category'); ?>" name="<?php echo $this->get_field_name('view_category'); ?>" />
			<label for="<?php echo $this->get_field_id('view_category'); ?>"><?php esc_html_e( 'Display category', 'yahman-add-ons' ); ?></label>
		</p>

		<p>
			<input type="checkbox" <?php checked( $settings['view_date'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id('view_date'); ?>" name="<?php echo $this->get_field_name('view_date'); ?>" />
			<label for="<?php echo $this->get_field_id('view_date'); ?>"><?php esc_html_e( 'Display date', 'yahman-add-ons' ); ?></label>
		</p>






		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance[ 'title' ] = sanitize_text_field( $new_instance['title'] );
		$instance[ 'category' ] = (int) $new_instance['category'];
		$instance[ 'number_post' ] = (int) $new_instance['number_post'];
		$instance[ 'sticky_posts' ] = (bool)$new_instance[ 'sticky_posts' ];
		$instance[ 'post_type' ] = sanitize_text_field( $new_instance['post_type'] );

		$instance['post_not_in'] = sanitize_text_field( $new_instance['post_not_in'] );
		$instance['category_not_in'] = sanitize_text_field( $new_instance['category_not_in'] );

		$instance[ 'orderby' ] = sanitize_text_field( $new_instance['orderby'] );

		$instance[ 'columns' ] = (int) $new_instance['columns'] ;
		$instance[ 'flex_column' ] = (bool)$new_instance[ 'flex_column' ];

		$instance[ 'thumbnail' ] = (bool)$new_instance[ 'thumbnail' ];
		$instance[ 'thum_size' ] = sanitize_text_field( $new_instance['thum_size'] );
		$instance[ 'thum_space' ] = (bool)$new_instance[ 'thum_space' ];

		$instance[ 'description' ] = (bool)$new_instance[ 'description' ];
		$instance[ 'view_category' ] = (bool)$new_instance[ 'view_category' ];
		$instance[ 'view_date' ] = (bool)$new_instance[ 'view_date' ];



		return $instance;
	}

} // class simple_days_custom_hp_columns_widget
