<?php
/**
 * Widget Recent Recipes
 *
 * @package Blossom_Recipe_Maker
 */

// register Brm_Recent_Recipe widget
function brm_register_recent_recipe_widget() {
	register_widget( 'Brm_Recent_Recipe' );
}
add_action( 'widgets_init', 'brm_register_recent_recipe_widget' );

 /**
  * Adds Brm_Recent_Recipe widget.
  */
class Brm_Recent_Recipe extends WP_Widget {


	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'brm_recent_recipe', // Base ID
			__( 'Blossom Recipe: Recent Recipes', 'blossom-recipe-maker' ), // Name
			array( 'description' => __( 'A Recent Recipe Widget', 'blossom-recipe-maker' ) ) // Args
		);
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
		$title      = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Recent Recipes', 'blossom-recipe-maker' );
		$num_post   = ! empty( $instance['num_post'] ) ? $instance['num_post'] : 3;
		$show_thumb = ! empty( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : '';
		$show_date  = ! empty( $instance['show_postdate'] ) ? $instance['show_postdate'] : '';
		$cats[]     = '';
		$cat        = apply_filters( 'brm_exclude_categories', $cats );
		$style      = ! empty( $instance['style'] ) ? $instance['style'] : 'style-one';

		$target = '_self';
		if ( isset( $instance['target'] ) && $instance['target'] != '' ) {
			$target = '_blank';
		}

		$obj      = new Blossom_Recipe_Maker_Functions();
		$img_size = apply_filters( 'brm_recent_post_size', 'recipe-maker-thumbnail-size' );

		$qry = new WP_Query(
			array(
				'post_type'           => 'blossom-recipe',
				'post_status'         => 'publish',
				'posts_per_page'      => $num_post,
				'ignore_sticky_posts' => true,
				'category__not_in'    => $cat,
			)
		);

		if ( $qry->have_posts() ) {
			echo wp_kses_post( $args['before_widget'] );
			ob_start();
			if ( $title ) {
				echo wp_kses_post( $args['before_title'] ) . esc_html( apply_filters( 'widget_title', $title, $instance, $this->id_base ) ) . wp_kses_post( $args['after_title'] );
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
				if ( has_post_thumbnail() && $show_thumb ) {
					the_post_thumbnail( $img_size );
				}
				if ( $show_thumb && ! has_post_thumbnail() ) {
					// fallback
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
						echo '<a target="' . esc_attr( $target ) . '" href="' . esc_url( get_category_link( $cd->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', 'blossom-recipe-maker' ), esc_attr( $cd->name ) ) ) . '">' . esc_html( $cd->name ) . '</a>';
					}
					echo '</span>';
				}
				?>

							<h3 class="entry-title"><a target="<?php echo esc_attr( $target ); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

				<?php if ( $show_date ) { ?>
								<div class="entry-meta">
									<span class="posted-on"><a target="<?php echo esc_attr( $target ); ?>" href="<?php the_permalink(); ?>">
										<time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></time></a>
									</span>
								</div>
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
			echo wp_kses_post( apply_filters( 'brm_recent_recipe_widget_filter', $html, $args, $instance ) );
			echo wp_kses_post( $args['after_widget'] );
		}
	}

	// function to add different styling classes
	public function brm_add_recent_post_class() {
		$arr = array(
			'style-one'   => __( 'Style One', 'blossom-recipe-maker' ),
			'style-two'   => __( 'Style Two', 'blossom-recipe-maker' ),
			'style-three' => __( 'Style Three', 'blossom-recipe-maker' ),
		);
		$arr = apply_filters( 'brm_add_recent_post_class', $arr );
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

		$title          = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Recent Recipes', 'blossom-recipe-maker' );
		$num_post       = ! empty( $instance['num_post'] ) ? $instance['num_post'] : 3;
		$show_thumbnail = ! empty( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : '';
		$show_postdate  = ! empty( $instance['show_postdate'] ) ? $instance['show_postdate'] : '';
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
			<label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Layout:', 'blossom-recipe-maker' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>" class="based-on widefat">
		<?php
		$styles = $this->brm_add_recent_post_class();
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
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" value="1" <?php echo checked( $target, 1 ); ?> /><?php esc_html_e( 'Open Recipes in New Tab', 'blossom-recipe-maker' ); ?> </label>
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

		$instance = array();

		$instance['title']          = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : __( 'Recent Recipes', 'blossom-recipe-maker' );
		$instance['num_post']       = ! empty( $new_instance['num_post'] ) ? absint( $new_instance['num_post'] ) : 3;
		$instance['show_thumbnail'] = ! empty( $new_instance['show_thumbnail'] ) ? absint( $new_instance['show_thumbnail'] ) : '';
		$instance['show_postdate']  = ! empty( $new_instance['show_postdate'] ) ? absint( $new_instance['show_postdate'] ) : '';
		$instance['style']          = ! empty( $new_instance['style'] ) ? esc_attr( $new_instance['style'] ) : 'style-one';

		$instance['target'] = ! empty( $new_instance['target'] ) ? esc_attr( $new_instance['target'] ) : '';

		return $instance;

	}

} // class Brm_Recent_Recipe
