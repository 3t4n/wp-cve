<?php
/**
 * Widget Two columns posts (each other down to straight)
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_two_lists_two_col_widget extends WP_Widget {


	function __construct() {
		parent::__construct(
			'ya_2lists_2col', // Base ID dts is down to straight
			esc_html__( '[YAHMAN Add-ons] Two lists of articles', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Two lists of articles into two columns and display them.', 'yahman-add-ons' ), ) // Args
		);
	}

	/**
	 * Set default settings of the widget
	 */
	private function default_settings() {

		$defaults = array(

			'columns' => '',

			'left_title'    => esc_html__( 'Feature articles', 'yahman-add-ons' ),
			'left_category' => 0,
			'left_number_post'   => 5,
			'left_orderby' => 'date',

			'left_post_type' => 'post',
			'left_post_not_in' => '',
			'left_category_not_in' => '',
			'left_sticky_posts' => true,

			'left_heading_flex_column' => true,
			'left_heading_thumbnail' => true,
			'left_heading_thum_space' => false,
			'left_heading_thum_size' => 'medium',
			'left_heading_description' => true,
			'left_heading_view_date' => false,
			'left_heading_view_category' => false,
			'left_second_flex_column' => false,
			'left_second_thumbnail' => true,
			'left_second_thum_space' => false,
			'left_second_thum_size' => 'thumbnail',
			'left_second_description' => false,
			'left_second_view_date' => false,
			'left_second_view_category' => false,

			'right_title'    => esc_html__( 'Feature articles', 'yahman-add-ons' ),
			'right_category' => 0,
			'right_number_post'   => 5,
			'right_orderby' => 'date',

			'right_post_type' => 'post',
			'right_post_not_in' => '',
			'right_category_not_in' => '',
			'right_sticky_posts' => true,

			'right_heading_flex_column' => true,
			'right_heading_thumbnail' => true,
			'right_heading_thum_space' => false,
			'right_heading_thum_size' => 'medium',
			'right_heading_description' => true,
			'right_heading_view_date' => false,
			'right_heading_view_category' => false,
			'right_second_flex_column' => false,
			'right_second_thumbnail' => true,
			'right_second_thum_space' => false,
			'right_second_thum_size' => 'thumbnail',
			'right_second_description' => false,
			'right_second_view_date' => false,
			'right_second_view_category' => false,
		);

		return $defaults;
	}

	public function widget( $args, $instance ) {

		$settings = wp_parse_args( $instance, $this->default_settings() );

		
		require_once YAHMAN_ADDONS_DIR . 'inc/get_thumbnail.php';

		require_once YAHMAN_ADDONS_DIR . 'inc/template-parts/flex_articles.php';

		$angle = array('left','right');
		//$grid_margin = array('left' => 'ch_sep','right' => '');
		echo $args['before_widget'];

		echo '<div class="f_box f_wrap jc_sb">';
		foreach ($angle as $angle_value) {

			$post_type = array('post','page');
			if($settings[$angle_value.'_post_type'] !== 'both') $post_type = array($settings[$angle_value.'_post_type']);

			$post_not_in = explode(',', $settings[$angle_value.'_post_not_in']);
			$category_not_in = explode(',', $settings[$angle_value.'_category_not_in']);

			$latest_posts = new WP_Query(
				array(
					'post_type'             => $post_type,
					'cat'					=> $settings[$angle_value.'_category'],
					'posts_per_page'		=> $settings[$angle_value.'_number_post'],
					'post_status'			=> 'publish',
					'ignore_sticky_posts' 	=> $settings[$angle_value.'_sticky_posts'],
					'orderby'               => $settings[$angle_value.'_orderby'],
					'post__not_in'          => $post_not_in,
					'category__not_in'      => $category_not_in,
				)
			);
			if ( $latest_posts->have_posts() ) :





				echo '<div class="f_2'.'">';

				if ( ! empty($settings[$angle_value.'_title']) ) {
					echo $args['before_title']. $settings[$angle_value.'_title'] .  $args['after_title'];
				}


				$i=0;
				$settings['flex_column'] = $settings[$angle_value.'_heading_flex_column'];
				$settings['thum_size'] = $settings[$angle_value.'_heading_thum_size'];
				$settings['thum_space'] = $settings[$angle_value.'_heading_thum_space'];
				$settings['thumbnail'] = $settings[$angle_value.'_heading_thumbnail'];
				$settings['description'] = $settings[$angle_value.'_heading_description'];
				$settings['view_date'] =	$settings[$angle_value.'_heading_view_date'];
				$settings['view_category'] =	$settings[$angle_value.'_heading_view_category'];
				while ( $latest_posts->have_posts() ) : $latest_posts->the_post();
					global $post;

					yahman_addons_flex_articles($post,$settings);

					if($i === 0){
						$settings['flex_column'] = $settings[$angle_value.'_second_flex_column'];
						$settings['thum_size'] = $settings[$angle_value.'_second_thum_size'];
						$settings['thum_space'] = $settings[$angle_value.'_second_thum_space'];
						$settings['thumbnail'] = $settings[$angle_value.'_second_thumbnail'];
						$settings['description'] = $settings[$angle_value.'_second_description'];
						$settings['view_date'] =	$settings[$angle_value.'_second_view_date'];
						$settings['view_category'] =	$settings[$angle_value.'_second_view_category'];
					}

					++$i;
				endwhile;

				echo '</div>';

			endif;

			wp_reset_query();  

		}

		echo '</div>';
		echo $args['after_widget'];

	}






	public function form( $instance ) {

		// Get Widget Settings.
		$settings = wp_parse_args( $instance, $this->default_settings() );

		$left_right = array(
			'left' => esc_html__( 'Left side', 'yahman-add-ons' ),
			'right' => esc_html__( 'Right side', 'yahman-add-ons' ),
		);
		$first_second = array(
			'heading' => esc_html__( 'Heading article', 'yahman-add-ons' ),
			'second' => esc_html__( 'From second articles', 'yahman-add-ons' ),
		);

		foreach ($left_right as $left_right_key => $left_right_value):

			?>

			<div style="margin-top:10px;border:1px solid #000;padding:8px;">
				<div style="background:#000;color:#fff;padding:7px;font-size:16px;margin:-8px -8px 8px;"><?php echo esc_html__( $left_right_value ); ?></div>
				<p>
					<label for="<?php echo $this->get_field_id( $left_right_key.'_title' ); ?>">
						<?php esc_html_e( 'Title:', 'yahman-add-ons' ); ?>
					</label>
					<input class="widefat" id="<?php echo $this->get_field_id( $left_right_key.'_title' ); ?>" name="<?php echo $this->get_field_name( $left_right_key.'_title' ); ?>" type="text" value="<?php echo esc_attr( $settings[$left_right_key.'_title'] ); ?>" />
				</p>

				<p>
					<label for="<?php echo $this->get_field_id( $left_right_key.'_category' ); ?>"><?php esc_html_e( 'Category&#58;', 'yahman-add-ons' ); ?></label>
					<?php
			        // Display Category Select.
					$args = array(
						'show_option_all'    => esc_html__( 'All Categories', 'yahman-add-ons' ),
						'show_count' 		 => true,
				//'hide_empty'		 => true,
						'selected'           => $settings[ $left_right_key.'_category' ],
						'name'               => $this->get_field_name( 'category' ),
						'id'                 => $this->get_field_id( 'category' ),
				//'depth'              => 0,
						'hierarchical'		 => true,
					);
					wp_dropdown_categories( $args );
					?>
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( $left_right_key.'_number_post' ) ); ?>"><?php esc_html_e( 'Number of posts', 'yahman-add-ons' ); ?></label>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $left_right_key.'_number_post' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $left_right_key.'_number_post' ) ); ?>" type="number" step="1" min="1" max="20" value="<?php echo absint( $settings[$left_right_key.'_number_post'] ); ?>" />
				</p>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( $left_right_key.'_orderby' ) ); ?>">
						<?php esc_html_e( 'Order by', 'yahman-add-ons' ); ?>
					</label><br />
					<select id="<?php echo esc_attr( $this->get_field_id( $left_right_key.'_orderby' )); ?>" name="<?php echo esc_attr( $this->get_field_name( $left_right_key.'_orderby' )); ?>">
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
							echo '<option '. selected( $settings[$left_right_key.'_orderby'], $key ) .' value="'.esc_attr($key).'" >'.esc_html($value).'</option>';
						}
						?>
					</select>
				</p>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( $left_right_key.'_post_type' ) ); ?>">
						<?php esc_html_e( 'Post type', 'yahman-add-ons' ); ?>
					</label><br />
					<select id="<?php echo esc_attr( $this->get_field_id( $left_right_key.'_post_type' )); ?>" name="<?php echo esc_attr( $this->get_field_name( $left_right_key.'_post_type' )); ?>">
						<?php
						$dummy = array(
							'post' => esc_html__( 'Post' ,'yahman-add-ons' ),
							'page' => esc_html__( 'Page' ,'yahman-add-ons' ),
							'both' => esc_html__( 'Post and Page' ,'yahman-add-ons' ),
						);
						foreach ($dummy as $key => $value) {
							echo '<option '. selected( $settings[$left_right_key.'_post_type'], $key ) .' value="'.esc_attr($key).'" >'.esc_html($value).'</option>';
						}
						?>
					</select>
				</p>


				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( $left_right_key.'_category_not_in' ) ); ?>">
						<?php esc_html_e( 'Disappear when you type category id.', 'yahman-add-ons' ); ?><br /><?php echo sprintf( esc_html__('Multiple %s must be separated by a comma.', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?>
					</label>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $left_right_key.'_category_not_in' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $left_right_key.'_category_not_in' ) ); ?>" type="text" value="<?php echo esc_attr( $settings[$left_right_key.'_category_not_in'] ); ?>">
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( $left_right_key.'_post_not_in' ) ); ?>"><?php esc_html_e( 'Disappear when you type post id.', 'yahman-add-ons' ); ?><br /><?php echo sprintf( esc_html__('Multiple %s must be separated by a comma.', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?></label>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $left_right_key.'_post_not_in' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $left_right_key.'_post_not_in' ) ); ?>" type="text" value="<?php echo esc_attr( $settings[$left_right_key.'_post_not_in'] ); ?>">
				</p>

				<p>
					<input type="checkbox" <?php checked( $settings[$left_right_key.'_sticky_posts'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id($left_right_key.'_sticky_posts'); ?>" name="<?php echo $this->get_field_name($left_right_key.'_sticky_posts'); ?>" />
					<label for="<?php echo $this->get_field_id($left_right_key.'_sticky_posts'); ?>"><?php esc_html_e( 'Ignore sticky posts', 'yahman-add-ons' ); ?></label>
				</p>




				<?php
				foreach ($first_second as $key_1st_2nd => $value_1st_2nd):
					?>

					<p style="background:#000;padding:4px 8px;color:#fff;margin-bottom:-8px;">
						<?php echo esc_html__( $value_1st_2nd ); ?>
					</p>

					<p>
						<input type="checkbox" <?php checked( $settings[$left_right_key.'_'.$key_1st_2nd.'_thumbnail'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id($left_right_key.'_'.$key_1st_2nd.'_thumbnail'); ?>" name="<?php echo $this->get_field_name($left_right_key.'_'.$key_1st_2nd.'_thumbnail'); ?>" />
						<label for="<?php echo $this->get_field_id($left_right_key.'_'.$key_1st_2nd.'_thumbnail'); ?>"><?php esc_html_e( 'Display thumbnail', 'yahman-add-ons' ); ?></label>
					</p>

					<p>
						<input type="checkbox" <?php checked( $settings[$left_right_key.'_'.$key_1st_2nd.'_thum_space'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id($left_right_key.'_'.$key_1st_2nd.'_thum_space'); ?>" name="<?php echo $this->get_field_name($left_right_key.'_'.$key_1st_2nd.'_thum_space'); ?>" />
						<label for="<?php echo $this->get_field_id($left_right_key.'_'.$key_1st_2nd.'_thum_space'); ?>"><?php esc_html_e( 'Display thumbnail space', 'yahman-add-ons' ); ?></label>
					</p>

					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( $left_right_key.'_'.$key_1st_2nd.'_thum_size' ) ); ?>">
							<?php esc_html_e( 'Original size of thumbnail', 'yahman-add-ons' ); ?>
						</label><br />
						<select id="<?php echo esc_attr( $this->get_field_id( $left_right_key.'_'.$key_1st_2nd.'_thum_size' )); ?>" name="<?php echo esc_attr( $this->get_field_name( $left_right_key.'_'.$key_1st_2nd.'_thum_size' )); ?>">
							<?php
							$image_size = array(
								'thumbnail' => esc_html__( 'Thumbnail', 'yahman-add-ons' ),
								'medium' => esc_html__( 'Medium', 'yahman-add-ons' ),
								'large' => esc_html__( 'Large', 'yahman-add-ons' ),
								'full' => esc_html__( 'Full', 'yahman-add-ons' ),
							);
							foreach ($image_size as $key => $value) {
								echo '<option '. selected( $settings[$left_right_key.'_'.$key_1st_2nd.'_thum_size'], $key ) .' value="'.esc_attr($key).'" >'.esc_html($value).'</option>';
							}
							?>
						</select>
					</p>

					<p>
						<input type="checkbox" <?php checked( $settings[$left_right_key.'_'.$key_1st_2nd.'_flex_column'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id($left_right_key.'_'.$key_1st_2nd.'_flex_column'); ?>" name="<?php echo $this->get_field_name($left_right_key.'_'.$key_1st_2nd.'_flex_column'); ?>" />
						<label for="<?php echo $this->get_field_id($left_right_key.'_'.$key_1st_2nd.'_flex_column'); ?>"><?php esc_html_e( 'Display column', 'yahman-add-ons' ); ?></label>
					</p>

					<p>
						<input type="checkbox" <?php checked( $settings[$left_right_key.'_'.$key_1st_2nd.'_description'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id($left_right_key.'_'.$key_1st_2nd.'_description'); ?>" name="<?php echo $this->get_field_name($left_right_key.'_'.$key_1st_2nd.'_description'); ?>" />
						<label for="<?php echo $this->get_field_id($left_right_key.'_'.$key_1st_2nd.'_description'); ?>"><?php esc_html_e( 'Display description', 'yahman-add-ons' ); ?></label>
					</p>

					<p>
						<input type="checkbox" <?php checked( $settings[$left_right_key.'_'.$key_1st_2nd.'_view_category'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id($left_right_key.'_'.$key_1st_2nd.'_view_category'); ?>" name="<?php echo $this->get_field_name($left_right_key.'_'.$key_1st_2nd.'_view_category'); ?>" />
						<label for="<?php echo $this->get_field_id($left_right_key.'_'.$key_1st_2nd.'_view_category'); ?>"><?php esc_html_e( 'Display category', 'yahman-add-ons' ); ?></label>
					</p>

					<p>
						<input type="checkbox" <?php checked( $settings[$left_right_key.'_'.$key_1st_2nd.'_view_date'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id($left_right_key.'_'.$key_1st_2nd.'_view_date'); ?>" name="<?php echo $this->get_field_name($left_right_key.'_'.$key_1st_2nd.'_view_date'); ?>" />
						<label for="<?php echo $this->get_field_id($left_right_key.'_'.$key_1st_2nd.'_view_date'); ?>"><?php esc_html_e( 'Display date', 'yahman-add-ons' ); ?></label>
					</p>


					<?php
				endforeach;
				?>





			</div>


			<?php

		endforeach;


		?>






		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;


		$left_right = array(
			'left' => esc_html__( 'Left side', 'yahman-add-ons' ),
			'right' => esc_html__( 'Right side', 'yahman-add-ons' ),
		);

		foreach ($left_right as $key => $value) {

			$instance[ $key.'_title'] = sanitize_text_field( $new_instance[$key.'_title'] );
			$instance[ $key.'_category'] = (int) $new_instance[$key.'_category'];
			$instance[ $key.'_number_post'] = (int) $new_instance[$key.'_number_post'];
			$instance[ $key.'_orderby' ] = sanitize_text_field( $new_instance[$key.'_orderby'] );
			$instance[ $key.'_post_type' ] = sanitize_text_field( $new_instance[$key.'_post_type'] );
			$instance[ $key.'_post_not_in'] = sanitize_text_field( $new_instance[ $key.'_post_not_in'] );
			$instance[ $key.'_category_not_in'] = sanitize_text_field( $new_instance[ $key.'_category_not_in'] );
			$instance[ $key.'_sticky_posts' ] = (bool)$new_instance[ $key.'_sticky_posts' ];

			$instance[ $key.'_heading_flex_column' ] = (bool)$new_instance[ $key.'_heading_flex_column' ];

			$instance[ $key.'_heading_thumbnail' ] = (bool)$new_instance[ $key.'_heading_thumbnail' ];
			$instance[ $key.'_heading_thum_size' ] = sanitize_text_field( $new_instance[ $key.'_heading_thum_size'] );
			$instance[ $key.'_heading_thum_space' ] = (bool)$new_instance[ $key.'_heading_thum_space' ];

			$instance[ $key.'_heading_description' ] = (bool)$new_instance[ $key.'_heading_description' ];
			$instance[ $key.'_heading_view_date' ] = (bool)$new_instance[ $key.'_heading_view_date' ];
			$instance[ $key.'_heading_view_category' ] = (bool)$new_instance[ $key.'_heading_view_category' ];

			$instance[ $key.'_second_flex_column' ] = (bool)$new_instance[ $key.'_second_flex_column' ];


			$instance[ $key.'_second_thumbnail' ] = (bool)$new_instance[ $key.'_second_thumbnail' ];
			$instance[ $key.'_second_thum_size' ] = sanitize_text_field( $new_instance[$key.'_second_thum_size'] );
			$instance[ $key.'_second_thum_space' ] = (bool)$new_instance[ $key.'_second_thum_space' ];

			$instance[ $key.'_second_description' ] = (bool)$new_instance[ $key.'_second_description' ];
			$instance[ $key.'_second_view_date' ] = (bool)$new_instance[ $key.'_second_view_date' ];
			$instance[ $key.'_second_view_category' ] = (bool)$new_instance[ $key.'_second_view_category' ];
		}

		return $instance;
	}

} // class yahman_addons_two_columns_dts_widget
