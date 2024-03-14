<?php
/**
 * Widget Recipes Category Slider
 *
 * @package Blossom_Recipe_Maker
 */

// Register and load the widget
function brm_register_recipes_category_slider_widget() {
	register_widget( 'Brm_Recipes_Category_Slider' );
}
add_action( 'widgets_init', 'brm_register_recipes_category_slider_widget' );

// Creating the widget
class Brm_Recipes_Category_Slider extends WP_Widget {


	function __construct() {
		parent::__construct(
		// Base ID of your widget
			'Brm_Recipe_Categories_Slider',
			// Widget name will appear in UI
			__( 'Blossom Recipe: Recipes Category Slider', 'blossom-recipe-maker' ),
			// Widget description
			array( 'description' => __( 'Simple recipes slider from category.', 'blossom-recipe-maker' ) )
		);
	}

	// function to add different styling classes
	public function brm_recipe_taxonomies() {
		$arr = array(
			'recipe-category'       => 'Recipe Category',
			'recipe-cuisine'        => 'Recipe Cuisine',
			'recipe-cooking-method' => 'Recipe Cooking Method',
		);

		$arr = apply_filters( 'brm_add_recipe_taxs', $arr );
		return $arr;
	}

	// Creating widget front-end
	public function widget( $args, $instance ) {

		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';

		$target = '_self';
		if ( isset( $instance['target'] ) && $instance['target'] != '' ) {
			$target = '_blank';
		}
		// before and after widget arguments are defined by themes
		echo wp_kses_post( $args['before_widget'] );
		ob_start();

		if ( $title ) {
			echo wp_kses_post( $args['before_title'] ) . esc_html( apply_filters( 'widget_title', $title, $instance, $this->id_base ) ) . wp_kses_post( $args['after_title'] );
		}

		$slides          = ! empty( $instance['slides'] ) ? $instance['slides'] : '1';
		$taxonomy        = ! empty( $instance['taxonomy'] ) ? $instance['taxonomy'] : '';
		$category        = ! empty( $instance['category'] ) ? $instance['category'] : '';
		$show_arrow      = '0';
		$show_pagination = '0';
		$direction       = '0';

		if ( isset( $instance['show_arrow'] ) && $instance['show_arrow'] != '' ) {
			$show_arrow = $instance['show_arrow'];
		}
		if ( isset( $instance['show_pagination'] ) && $instance['show_pagination'] != '' ) {
			$show_pagination = $instance['show_pagination'];
		}
		if ( isset( $instance['direction'] ) && $instance['direction'] != '' ) {
			$direction = $instance['direction'];
		}

		$ran = rand( 1, 100 );
		$ran++;
		if ( $direction == '1' ) {
			$direction = 'true';
		} else {
			$direction = 'false';
		}
		$obj = new Blossom_Recipe_Maker_Functions();

		// This is where you run the code and display the output
     echo '<div id="sync1-'.esc_attr( absint( $ran ) ).'" class="owl-carousel owl-theme">'; // @phpcs:ignore
		$catquery = new WP_Query(
			array(
				'post_type'           => 'blossom-recipe',
				'post_status'         => 'publish',
				'posts_per_page'      => $slides,
				'tax_query'           => array(
					array(
						'taxonomy' => $taxonomy,
						'terms'    => $category,
					),
				),
				'ignore_sticky_posts' => true,
			)
		);

		while ( $catquery->have_posts() ) :
			$catquery->the_post();

			$brm_category_img_size = apply_filters( 'brm_category_img_size', 'recipe-maker-thumbnail-size' );
			?>
				<div class="item">
					<a target="<?php echo esc_attr( $target ); ?>" href="<?php the_permalink(); ?>" class="post-thumbnail">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( $brm_category_img_size );
			} else {
				// fallback svg
				$obj->brm_get_fallback_svg( $brm_category_img_size );
			}
			?>
					</a>
					<div class="carousel-title">
			<?php
			$category_detail = get_the_terms( get_the_ID(), $taxonomy );

			if ( ! empty( $category_detail ) && ! is_wp_error( $category_detail ) ) {
				echo '<span class="cat-links">';
				foreach ( $category_detail as $cd ) {
					echo '<a ' . esc_attr( $target ) . ' href="' . esc_url( get_category_link( $cd->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', 'blossom-recipe-maker' ), esc_attr( $cd->name ) ) ) . '">' . esc_html( $cd->name ) . '</a>';
				}
				echo '</span>';
			}
			?>
						<h3 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					</div>
				</div>
			<?php
		endwhile;
		wp_reset_postdata();

      echo '</div>'; //@phpcs:ignore

		echo wp_kses_post(
			$obj->brm_minify_css(
				'<style>
			#sync1-' . esc_attr( absint( $ran ) ) . ' {
			  .item {
			    background: #0c83e7;
			    padding: 80px 0px;
			    margin: 5px;
			    color: #FFF;
			    -webkit-border-radius: 3px;
			    -moz-border-radius: 3px;
			    border-radius: 3px;
			    text-align: center;
			  }
			}
			.owl-theme {
				.owl-nav {
				    /*default owl-theme theme reset .disabled:hover links */
				    [class*="owl-"] {
				      transition: all .3s ease;
				      &.disabled:hover {
				       background-color: #D6D6D6;
				      }   
				    }
				    
				  }
				}

				//arrows on first carousel
				#sync1-' . esc_attr( absint( $ran ) ) . '.owl-theme {
				  position: relative;
				  .owl-next, .owl-prev {
				    width: 22px;
				    height: 40px;
				    margin-top: -20px;
				    position: absolute;
				    top: 50%;
				  }
				  .owl-prev {
				    left: 10px;
				  }
				  .owl-next {
				    right: 10px;
				  }
				}
			</style>'
			)
		);

      // @phpcs:disable
      echo '<script>
				jQuery(document).ready(function($) {
				  var sync1 = $("#sync1-'.esc_attr( absint( $ran ) ).'");
				  var slidesPerPage = 1;
				  var syncedSecondary = true;
				  sync1.owlCarousel({
				    items : 1,
				    slideSpeed : '. absint( apply_filters('posts_category_slider_speed', '5000') ).',
				    nav: '. esc_attr( $show_arrow ).',
				    dots: '.esc_attr( $show_pagination ).',
				    rtl : '.esc_attr( $direction ).',
				    autoplay: true,
				    loop: true,
				    responsiveRefreshRate : 200,
				  }); });</script>';

      // @phpcs:enable

		$html = ob_get_clean();
		echo wp_kses_post( apply_filters( 'brm_recipes_category_slider_widget_filter', $html, $args, $instance ) );
		echo wp_kses_post( $args['after_widget'] );
	}

	// Widget Backend
	public function form( $instance ) {
		$target = ! empty( $instance['target'] ) ? $instance['target'] : '';

		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'Recipes Category Slider', 'blossom-recipe-maker' );
		}
		$taxonomy = '';
		if ( isset( $instance['taxonomy'] ) && $instance['taxonomy'] != '' ) {
			$taxonomy = $instance['taxonomy'];
		}
		$category = '';
		if ( isset( $instance['category'] ) && $instance['category'] != '' ) {
			$category = $instance['category'];
		}
		if ( isset( $instance['show_arrow'] ) ) {
			$show_arrow = $instance['show_arrow'];
		} else {
			$show_arrow = '';
		}
		if ( isset( $instance['show_pagination'] ) ) {
			$show_pagination = $instance['show_pagination'];
		} else {
			$show_pagination = '';
		}
		if ( isset( $instance['slides'] ) ) {
			$slides = $instance['slides'];
		} else {
			$slides = '1';
		}
		if ( isset( $instance['direction'] ) ) {
			$direction = $instance['direction'];
		} else {
			$direction = '';
		}
		// Widget admin form
		$ran = rand( 1, 1000 );
		$ran++;
		?>
		<input id="brm-cat-terms-ran" value="<?php echo esc_attr( absint( $ran ) ); ?>" type="hidden">

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'blossom-recipe-maker' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>"><?php esc_html_e( 'Choose Recipe Taxonomy:', 'blossom-recipe-maker' ); ?></label> 
			<select id="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'taxonomy' ) ); ?>" class="brm-cat-taxonomy-selector widefat" style="width:100%;">

		<?php
		$taxonomies = $this->brm_recipe_taxonomies();

		?>
				<option disabled selected><?php esc_html_e( '--Select Taxonomy--', 'blossom-recipe-maker' ); ?></option> 
		<?php

		foreach ( $taxonomies as $key => $value ) {
			?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $taxonomy, $key ); ?>><?php echo esc_html( $value ); ?></option>
			<?php
		}
		?>
				   
			</select>
		</p>

		<p>
			<span class="brm-cat-terms-holder">
		<?php
		if ( isset( $taxonomy ) && $taxonomy != '' ) {
			?>
				<label for="brm-categories-term-select-<?php echo esc_attr( absint( $ran ) ); ?>"><?php esc_html_e( 'Choose Taxonomy Term:', 'blossom-recipe-maker' ); ?></label> 

			<?php
			$terms = get_terms(
				array(
					'taxonomy'   => $taxonomy,
					'hide_empty' => false,
				)
			);

			if ( empty( $terms ) ) {
				 $taxonomy = preg_replace( '#[-]+#', ' ', $taxonomy );
				 $taxonomy = ucwords( $taxonomy );
				?>
					<span class="brm-terms-error-note">
				<?php
				 printf( wp_kses_post( __( 'No Terms available. To set terms for %1$s, go to %2$sBlossom Recipes > %3$s%4$s and %5$sAdd the %6$s%7$s.', 'blossom-recipe-maker' ) ), esc_html( $taxonomy ), '<b>', esc_html( $taxonomy ), '</b>', '<b>', esc_html( $taxonomy ), '</b>' );
				?>
					</span>

				<?php
			} else {
				?>
					<select id="brm-categories-term-select-<?php echo esc_attr( absint( $ran ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>" class="brm-categories-term-select-<?php echo esc_attr( absint( $ran ) ); ?> widefat" style="width:100%;">

				<?php
				$cats = get_categories( 'taxonomy=' . $taxonomy );

				foreach ( $cats as $cat ) {
					printf(
						'<option value="%1$s"%2$s>%3$s</option>',
						esc_html( $cat->term_id ),
						selected( $category, $cat->term_id ),
						esc_html( $cat->name )
					);
				}
				?>
							  
					</select>
				<?php
			}
		}
		?>
			</span>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'slides' ) ); ?>"><?php esc_html_e( 'Number of Slides:', 'blossom-recipe-maker' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'slides' ) ); ?>" class="widefat" min="1" name="<?php echo esc_attr( $this->get_field_name( 'slides' ) ); ?>" type="number" max="100" value="<?php echo esc_attr( $slides ); ?>"/>
			<div class="example-text"><?php esc_html_e( 'Total number of posts available in the selected category will be the maximum number of slides.', 'blossom-recipe-maker' ); ?></div>
		</p>

		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_arrow' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_arrow' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_arrow ); ?>/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_arrow' ) ); ?>"><?php esc_html_e( 'Show Slider Arrows', 'blossom-recipe-maker' ); ?></label>
		</p>

		   <p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_pagination' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_pagination' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_pagination ); ?>/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_pagination' ) ); ?>"><?php esc_html_e( 'Show Slider Pagination', 'blossom-recipe-maker' ); ?></label>
		</p>

		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'direction' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'direction' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $direction ); ?>/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'direction' ) ); ?>"><?php esc_html_e( 'Change Direction', 'blossom-recipe-maker' ); ?></label>
			<div class="example-text"><?php esc_html_e( "Enabling this will change slider direction from 'right to left' to 'left to right'.", 'blossom-recipe-maker' ); ?></div>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>">
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" value="1" <?php echo checked( $target, 1 ); ?> /><?php esc_html_e( 'Open Recipes in New Tab', 'blossom-recipe-maker' ); ?> </label>
		</p>

		<?php
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance                    = array();
		$instance['title']           = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( wp_unslash( $new_instance['title'] ) ) : '';
		$instance['taxonomy']        = ( ! empty( $new_instance['taxonomy'] ) ) ? sanitize_text_field( wp_unslash( $new_instance['taxonomy'] ) ) : '';
		$instance['category']        = ( ! empty( $new_instance['category'] ) ) ? sanitize_text_field( wp_unslash( $new_instance['category'] ) ) : '';
		$instance['show_arrow']      = ( ! empty( $new_instance['show_arrow'] ) ) ? sanitize_text_field( wp_unslash( $new_instance['show_arrow'] ) ) : '';
		$instance['show_pagination'] = ( ! empty( $new_instance['show_pagination'] ) ) ? sanitize_text_field( wp_unslash( $new_instance['show_pagination'] ) ) : '';
		$instance['slides']          = ( ! empty( $new_instance['slides'] ) ) ? sanitize_text_field( wp_unslash( $new_instance['slides'] ) ) : '1';
		$instance['direction']       = ( ! empty( $new_instance['direction'] ) ) ? sanitize_text_field( wp_unslash( $new_instance['direction'] ) ) : '';
		$instance['target']          = ! empty( $new_instance['target'] ) ? esc_attr( $new_instance['target'] ) : '';

		return $instance;
	}
} // Class Brm_Recipes_Category_Slider ends here
