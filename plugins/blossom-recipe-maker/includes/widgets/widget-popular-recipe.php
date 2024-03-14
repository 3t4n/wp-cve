<?php
/**
 * Widget Popular Recipe
 *
 * @package Blossom_Recipe_Maker
 */

// register Brm_Popular_Recipe widget
function brm_register_popular_recipe_widget() {
	 register_widget( 'Brm_Popular_Recipe' );
}
add_action( 'widgets_init', 'brm_register_popular_recipe_widget' );

if ( ! class_exists( 'Brm_Popular_Recipe' ) ) :
	/**
	 * Adds Brm_Popular_Recipe widget.
	 */
	class Brm_Popular_Recipe extends WP_Widget {


		/**
		 * Register widget with WordPress.
		 */
		function __construct() {
			if ( ! is_customize_preview() ) {
				add_action( 'wp', array( $this, 'brm_set_views' ) );
			}

			parent::__construct(
				'brm_popular_recipe', // Base ID
				esc_html__( 'Blossom Recipe: Popular Recipes', 'blossom-recipe-maker' ), // Name
				array( 'description' => esc_html__( 'A Popular Recipe Widget', 'blossom-recipe-maker' ) ) // Args
			);
		}

		/**
		 * Function to add the post view count
		 */
		function brm_set_views( $post_id ) {
			if ( in_the_loop() ) {
				$post_id = get_the_ID();
			} else {
				global $wp_query;
				$post_id = $wp_query->get_queried_object_id();
			}
			if ( is_singular( 'blossom-recipe' ) ) {
				$count_key = '_brm_view_count';
				$count     = get_post_meta( $post_id, $count_key, true );
				if ( $count == '' ) {
					$count = 0;
					delete_post_meta( $post_id, $count_key );
					add_post_meta( $post_id, $count_key, '1' );
				} else {
					$count++;
					update_post_meta( $post_id, $count_key, $count );
				}
			}
		}
		/**
		 * Function to get the post view count
		 */
		function brm_get_views( $post_id ) {
			$count_key = '_brm_view_count';
			$count     = get_post_meta( $post_id, $count_key, true );
			if ( $count == '' ) {
				return __( '0 View', 'blossom-recipe-maker' );
			} elseif ( $count <= 1 ) {
				return $count . __( ' View', 'blossom-recipe-maker' );
			} else {
				return $count . __( ' Views', 'blossom-recipe-maker' );
			}
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {

			$title          = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Popular Recipes', 'blossom-recipe-maker' );
			$num_post       = ! empty( $instance['num_post'] ) ? $instance['num_post'] : 3;
			$show_thumbnail = ! empty( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : '';
			$show_date      = ! empty( $instance['show_postdate'] ) ? $instance['show_postdate'] : '';
			$based_on       = ! empty( $instance['based_on'] ) ? $instance['based_on'] : 'views';
			$comment_num    = ! empty( $instance['comment_num'] ) ? $instance['comment_num'] : '';
			$view_count     = ! empty( $instance['view_count'] ) ? $instance['view_count'] : '';
			$style          = ! empty( $instance['style'] ) ? $instance['style'] : 'style-one';

			$cat = get_theme_mod( 'exclude_categories' );
			if ( $cat ) {
				$cat = array_diff( array_unique( $cat ), array( '' ) );
			}

			$arg = array(
				'post_type'           => 'blossom-recipe',
				'post_status'         => 'publish',
				'posts_per_page'      => $num_post,
				'ignore_sticky_posts' => true,
				'category__not_in'    => $cat,
			);

			if ( $based_on == 'views' ) {
				$arg['orderby']  = 'meta_value_num';
				$arg['meta_key'] = '_brm_view_count';
			} elseif ( $based_on == 'comments' ) {
				$arg['orderby'] = 'comment_count';
			}

			$obj      = new Blossom_Recipe_Maker_Functions();
			$img_size = apply_filters( 'brm_popular_post_size', 'recipe-maker-thumbnail-size' );

			$qry = new WP_Query( $arg );

			if ( $qry->have_posts() ) {

				echo wp_kses_post( $args['before_widget'] );
				ob_start();

				if ( $title ) {
					echo wp_kses_post( $args['before_title'] ) . esc_html( apply_filters( 'widget_title', $title, $instance, $this->id_base ) ) . wp_kses_post( $args['after_title'] );
				}

				$target = '_self';
				if ( isset( $instance['target'] ) && $instance['target'] != '' ) {
					$target = '_blank';
				}
				?>
			<ul class="<?php echo esc_attr( $style ); ?>">
				<?php
				while ( $qry->have_posts() ) {
					  $qry->the_post();
					?>
					<li>
						<a target="<?php echo esc_attr( $target ); ?>" href="<?php the_permalink(); ?>" class="post-thumbnail">
					<?php
					if ( has_post_thumbnail() && $show_thumbnail ) {
						the_post_thumbnail( $img_size );
					}
					if ( $show_thumbnail && ! has_post_thumbnail() ) {
						// fallback svg
						$obj->brm_get_fallback_svg( $img_size );
					}
					?>
						</a>
						<div class="entry-header">
					<?php
					 $category_detail = get_the_terms( get_the_ID(), 'recipe-category' );

					if ( ! empty( $category_detail ) && ! is_wp_error( $category_detail ) ) {
						echo '<span class="cat-links">';
						foreach ( $category_detail as $cd ) {
							  echo '<a target="' . esc_attr( $target ) . '" href="' . esc_url( get_category_link( $cd->term_id ) ) . '" alt="' . sprintf( esc_attr__( 'View all posts in %s', 'blossom-recipe-maker' ), esc_attr( $cd->name ) ) . '">' . esc_html( $cd->name ) . '</a>';
						}
						echo '</span>';
					}
					?>
							<h3 class="entry-title"><a target="<?php echo esc_attr( $target ); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

					<?php
					if ( $show_date ) {
						?>
								<div class="entry-meta"> 
						<?php $obj->brm_posted_on(); ?>
								</div>
						<?php
					}

					if ( $based_on == 'views' && $view_count ) {
						?>
								<span class="view-count"><?php echo esc_html( $this->brm_get_views( get_the_ID() ) ); ?></span>
						<?php
					} elseif ( $based_on == 'comments' && $comment_num ) {
						?>
								<span class="comment-count"><i class="fa fa-comment" aria-hidden="true"></i><?php echo absint( get_comments_number() ); ?></span>
					<?php } ?>
							
						</div>                        
					</li>        
					   <?php
				}
				wp_reset_postdata();
				?>
			</ul>
				<?php
				$html = ob_get_clean();
				echo wp_kses_post( apply_filters( 'brm_popular_recipe_widget_filter', $html, $args, $instance ) );
				echo wp_kses_post( $args['after_widget'] );
			}
		}

		// function to add different styling classes
		public function brm_add_popular_post_class() {
			$arr = array(
				'style-one'   => __( 'Style One', 'blossom-recipe-maker' ),
				'style-two'   => __( 'Style Two', 'blossom-recipe-maker' ),
				'style-three' => __( 'Style Three', 'blossom-recipe-maker' ),
			);
			$arr = apply_filters( 'brm_add_popular_post_class', $arr );
			return $arr;
		}
		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {

			$title          = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Popular Recipes', 'blossom-recipe-maker' );
			$num_post       = ! empty( $instance['num_post'] ) ? $instance['num_post'] : 3;
			$show_thumbnail = ! empty( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : '';
			$show_postdate  = ! empty( $instance['show_postdate'] ) ? $instance['show_postdate'] : '';
			$based_on       = ! empty( $instance['based_on'] ) ? $instance['based_on'] : 'views';
			$comment_num    = ! empty( $instance['comment_num'] ) ? $instance['comment_num'] : '';
			$view_count     = ! empty( $instance['view_count'] ) ? $instance['view_count'] : '';
			$style          = ! empty( $instance['style'] ) ? $instance['style'] : 'style-one';
			$target         = ! empty( $instance['target'] ) ? $instance['target'] : '';
			?>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'blossom-recipe-maker' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'num_post' ) ); ?>"><?php esc_html_e( 'Number of Posts', 'blossom-recipe-maker' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'num_post' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'num_post' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $num_post ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'based_on' ) ); ?>"><?php esc_html_e( 'Popular based on:', 'blossom-recipe-maker' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'based_on' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'based_on' ) ); ?>" class="based-on widefat">
				<option value="views" <?php selected( $based_on, 'views' ); ?>><?php esc_html_e( 'Post Views', 'blossom-recipe-maker' ); ?></option>
				<option value="comments" <?php selected( $based_on, 'comments' ); ?>><?php esc_html_e( 'Comment Count', 'blossom-recipe-maker' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Layout:', 'blossom-recipe-maker' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>" class="based-on widefat">
			<?php
			$styles = $this->brm_add_popular_post_class();
			foreach ( $styles as $key => $value ) {
				?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $style, $key ); ?>><?php echo esc_html( $value ); ?></option>
				<?php
			}
			?>
				
			</select>
		</p>

		
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumbnail' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_thumbnail ); ?>/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>"><?php esc_html_e( 'Show Post Thumbnail', 'blossom-recipe-maker' ); ?></label>
		</p>
		
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_postdate' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_postdate' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_postdate ); ?>/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_postdate' ) ); ?>"><?php esc_html_e( 'Show Post Date', 'blossom-recipe-maker' ); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>">
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" value="1" <?php checked( $target, 1 ); ?> /><?php esc_html_e( 'Open Recipes in New Tab', 'blossom-recipe-maker' ); ?> </label>
		</p>
		
			<?php
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {

			$instance                   = array();
			$instance['title']          = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : __( 'Recent Posts', 'blossom-recipe-maker' );
			$instance['num_post']       = ! empty( $new_instance['num_post'] ) ? absint( $new_instance['num_post'] ) : 3;
			$instance['show_thumbnail'] = ! empty( $new_instance['show_thumbnail'] ) ? absint( $new_instance['show_thumbnail'] ) : '';
			$instance['show_postdate']  = ! empty( $new_instance['show_postdate'] ) ? absint( $new_instance['show_postdate'] ) : '';
			$instance['based_on']       = ! empty( $new_instance['based_on'] ) ? esc_attr( $new_instance['based_on'] ) : 'views';
			$instance['comment_num']    = ! empty( $new_instance['comment_num'] ) ? absint( $new_instance['comment_num'] ) : '';
			$instance['view_count']     = ! empty( $new_instance['view_count'] ) ? absint( $new_instance['view_count'] ) : '';
			$instance['style']          = ! empty( $new_instance['style'] ) ? esc_attr( $new_instance['style'] ) : 'style-one';
			$instance['target']         = ! empty( $new_instance['target'] ) ? esc_attr( $new_instance['target'] ) : '';

			return $instance;

		}

	} // class Brm_Popular_Recipe
endif;
