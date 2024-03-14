<?php
/**
 * Widget Articles into two columns
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_art_two_col_widget extends WP_Widget {


	function __construct() {
		parent::__construct(
			'ya_art_2col', // Base ID fpl is first post is large size
			esc_html__( '[YAHMAN Add-ons] Articles into two columns', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Split list of articles into two columns and display them.', 'yahman-add-ons' ), ) // Args
		);
	}

	/**
	 * Set default settings of the widget
	 */
	private function default_settings() {

		$defaults = array(
			'title'    => esc_html__( 'Feature articles', 'yahman-add-ons' ),
			'category' => 0,
			'number_post'   => 5,
			'orderby' => 'date',

			'post_type' => 'post',
			'post_not_in' => '',
			'category_not_in' => '',

			'sticky_posts' => true,

			'columns' => '',
			'heading_flex_column' => true,
			'heading_thum_size' => 'medium',
			'heading_thumbnail' => true,
			'heading_thum_space' => false,
			'heading_description' => true,
			'heading_view_date' => false,
			'heading_view_category' => false,
			'second_flex_column' => false,
			'second_thum_size' => 'thumbnail',
			'second_thumbnail' => true,
			'second_thum_space' => false,
			'second_description' => false,
			'second_view_date' => false,
			'second_view_category' => false,
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

			echo $args['before_title']. $settings['title'] .  $args['after_title'];

			
			require_once YAHMAN_ADDONS_DIR . 'inc/get_thumbnail.php';

			require_once YAHMAN_ADDONS_DIR . 'inc/template-parts/flex_articles.php';


			echo '<div class="f_box jc_sb f_wrap">';

			echo '<div class="f_2">';





			$i=0;
			$settings['flex_column'] = $settings['heading_flex_column'];
			$settings['thum_size'] = $settings['heading_thum_size'];
			$settings['thum_space'] = $settings['heading_thum_space'];
			$settings['thumbnail'] = $settings['heading_thumbnail'];
			$settings['description'] = $settings['heading_description'];
			$settings['view_date'] =	$settings['heading_view_date'];
			$settings['view_category'] =	$settings['heading_view_category'];
			while ( $latest_posts->have_posts() ) : $latest_posts->the_post();

				global $post;

				if($i === 1) echo '</div><div class="f_2">';

				yahman_addons_flex_articles($post,$settings);

				if($i === 0){
					$settings['flex_column'] = $settings['second_flex_column'];
					$settings['thum_size'] = $settings['second_thum_size'];
					$settings['thum_space'] = $settings['second_thum_space'];
					$settings['thumbnail'] = $settings['second_thumbnail'];
					$settings['description'] = $settings['second_description'];
					$settings['view_date'] =	$settings['second_view_date'];
					$settings['view_category'] =	$settings['second_view_category'];
				}

				++$i;

			endwhile;
			/*
			if($i >= 2){
				echo '</div></div>';
			}elseif($i === 1){
				echo '</div></div>';
			}*/
			echo '</div></div>';
		endif;

		wp_reset_query();  


		echo $args['after_widget'];

	}






	public function form( $instance ) {

		// Get Widget Settings.
		$settings = wp_parse_args( $instance, $this->default_settings() );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<?php esc_html_e( 'Title:', 'yahman-add-ons' ); ?>
			</label>
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





		<?php
		$first_second = array(
			'heading' => esc_html__( 'Heading article', 'yahman-add-ons' ),
			'second' => esc_html__( 'From second articles', 'yahman-add-ons' ),
		);
		foreach ($first_second as $key_1st_2nd => $value_1st_2nd): ?>

			<p style="background:#000;padding:4px 8px;color:#fff;margin-bottom:-8px;">
				<?php echo esc_html__( $value_1st_2nd ); ?>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( $key_1st_2nd.'_thum_size' ) ); ?>">
					<?php esc_html_e( 'Original size of thumbnail', 'yahman-add-ons' ); ?>
				</label><br />
				<select id="<?php echo esc_attr( $this->get_field_id( $key_1st_2nd.'_thum_size' )); ?>" name="<?php echo esc_attr( $this->get_field_name( $key_1st_2nd.'_thum_size' )); ?>">
					<?php
					$image_size = array(
						'thumbnail' => esc_html__( 'Thumbnail', 'yahman-add-ons' ),
						'medium' => esc_html__( 'Medium', 'yahman-add-ons' ),
						'large' => esc_html__( 'Large', 'yahman-add-ons' ),
						'full' => esc_html__( 'Full', 'yahman-add-ons' ),
					);
					foreach ($image_size as $key => $value) {
						echo '<option '. selected( $settings[$key_1st_2nd.'_thum_size'], $key ) .' value="'.esc_attr($key).'" >'.esc_html($value).'</option>';
					}
					?>
				</select>
			</p>

			<p>
				<input type="checkbox" <?php checked( $settings[$key_1st_2nd.'_thumbnail'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id($key_1st_2nd.'_thumbnail'); ?>" name="<?php echo $this->get_field_name($key_1st_2nd.'_thumbnail'); ?>" />
				<label for="<?php echo $this->get_field_id($key_1st_2nd.'_thumbnail'); ?>"><?php esc_html_e( 'Display thumbnail', 'yahman-add-ons' ); ?></label>
			</p>

			<p>
				<input type="checkbox" <?php checked( $settings[$key_1st_2nd.'_thum_space'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id($key_1st_2nd.'_thum_space'); ?>" name="<?php echo $this->get_field_name($key_1st_2nd.'_thum_space'); ?>" />
				<label for="<?php echo $this->get_field_id($key_1st_2nd.'_thum_space'); ?>"><?php esc_html_e( 'Display thumbnail space', 'yahman-add-ons' ); ?></label>
			</p>

			<p>
				<input type="checkbox" <?php checked( $settings[$key_1st_2nd.'_flex_column'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id($key_1st_2nd.'_flex_column'); ?>" name="<?php echo $this->get_field_name($key_1st_2nd.'_flex_column'); ?>" />
				<label for="<?php echo $this->get_field_id($key_1st_2nd.'_flex_column'); ?>"><?php esc_html_e( 'Display column', 'yahman-add-ons' ); ?></label>
			</p>

			<p>
				<input type="checkbox" <?php checked( $settings[$key_1st_2nd.'_description'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id($key_1st_2nd.'_description'); ?>" name="<?php echo $this->get_field_name($key_1st_2nd.'_description'); ?>" />
				<label for="<?php echo $this->get_field_id($key_1st_2nd.'_description'); ?>"><?php esc_html_e( 'Display description', 'yahman-add-ons' ); ?></label>
			</p>

			<p>
				<input type="checkbox" <?php checked( $settings[$key_1st_2nd.'_view_category'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id($key_1st_2nd.'_view_category'); ?>" name="<?php echo $this->get_field_name($key_1st_2nd.'_view_category'); ?>" />
				<label for="<?php echo $this->get_field_id($key_1st_2nd.'_view_category'); ?>"><?php esc_html_e( 'Display category', 'yahman-add-ons' ); ?></label>
			</p>

			<p>
				<input type="checkbox" <?php checked( $settings[$key_1st_2nd.'_view_date'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id($key_1st_2nd.'_view_date'); ?>" name="<?php echo $this->get_field_name($key_1st_2nd.'_view_date'); ?>" />
				<label for="<?php echo $this->get_field_id($key_1st_2nd.'_view_date'); ?>"><?php esc_html_e( 'Display date', 'yahman-add-ons' ); ?></label>
			</p>

			<?php
		endforeach;
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

		$instance[ 'heading_flex_column' ] = (bool)$new_instance[ 'heading_flex_column' ];

		$instance[ 'heading_thumbnail' ] = (bool)$new_instance[ 'heading_thumbnail' ];
		$instance[ 'heading_thum_size' ] = sanitize_text_field( $new_instance['heading_thum_size'] );
		$instance[ 'heading_thum_space' ] = (bool)$new_instance[ 'heading_thum_space' ];

		$instance[ 'heading_description' ] = (bool)$new_instance[ 'heading_description' ];
		$instance[ 'heading_view_date' ] = (bool)$new_instance[ 'heading_view_date' ];
		$instance[ 'heading_view_category' ] = (bool)$new_instance[ 'heading_view_category' ];

		$instance[ 'second_flex_column' ] = (bool)$new_instance[ 'second_flex_column' ];

		$instance[ 'second_thumbnail' ] = (bool)$new_instance[ 'second_thumbnail' ];
		$instance[ 'second_thum_size' ] = sanitize_text_field( $new_instance['second_thum_size'] );
		$instance[ 'second_thum_space' ] = (bool)$new_instance[ 'second_thum_space' ];

		$instance[ 'second_description' ] = (bool)$new_instance[ 'second_description' ];
		$instance[ 'second_view_date' ] = (bool)$new_instance[ 'second_view_date' ];
		$instance[ 'second_view_category' ] = (bool)$new_instance[ 'second_view_category' ];
		return $instance;
	}

} // class yahman_addons_two_columns_fpl_widget
