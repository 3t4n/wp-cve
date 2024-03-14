<?php
/**
 * Widget Recipe Categories
 *
 * @package Blossom_Recipe_Maker
 */

// register Brm_Recipe_category widget
function brm_register_recipe_categories_widget() {
	register_widget( 'Brm_Recipe_Categories' );
}
add_action( 'widgets_init', 'brm_register_recipe_categories_widget' );

// Creating the widget
class Brm_Recipe_Categories extends WP_Widget {

	public function __construct() {
		parent::__construct(
		// Base ID of your widget
			'Brm_Recipe_Categories',
			// Widget name will appear in UI
			__( 'Blossom Recipe: Recipe Categories', 'blossom-recipe-maker' ),
			// Widget description
			array( 'description' => __( 'Widget to display recipe categories with Image and Posts Count', 'blossom-recipe-maker' ) )
		);
	}

	// function to add different styling classes
	public function brm_recipe_taxonomies() {
		$arr = array(
			'recipe-category'       => __( 'Recipe Category', 'blossom-recipe-maker' ),
			'recipe-cuisine'        => __( 'Recipe Cuisine', 'blossom-recipe-maker' ),
			'recipe-cooking-method' => __( 'Recipe Cooking Method', 'blossom-recipe-maker' ),
		);

		$arr = apply_filters( 'brm_add_recipe_taxs', $arr );
		return $arr;
	}

	// Creating widget front-end

	public function widget( $args, $instance ) {

		$title               = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$obj                 = new Blossom_Recipe_Maker_Functions();
		$brm_recipe_cat_size = apply_filters( 'brm_recipe_cat_size', 'recipe-maker-thumbnail-size' );

		// before and after widget arguments are defined by themes
		echo wp_kses_post( $args['before_widget'] );
		ob_start();

		$target = '_self';
		if ( isset( $instance['target'] ) && $instance['target'] != '' ) {
			$target = '_blank';
		}

		if ( $title ) {
			echo wp_kses_post( $args['before_title'] ) . esc_html( apply_filters( 'widget_title', $title, $instance, $this->id_base ) ) . wp_kses_post( $args['after_title'] );
		}

		echo '<div class="brm-recipe-categories-wrap">';
		echo '<ul class="brm-recipe-categories-meta-wrap">';

		$taxonomy = '';
		if ( isset( $instance['taxonomy'] ) && $instance['taxonomy'] != '' ) {
			$taxonomy = $instance['taxonomy'];
		}

		$cats[] = '';
		if ( isset( $instance['categories'] ) && $instance['categories'] != '' ) {
			$cats = $instance['categories'];
		}
		if ( $taxonomy != '' && is_array( $cats ) ) {

			foreach ( $cats as $key => $value ) {
				$category = get_term( $value, $taxonomy );

				if ( ! empty( $category ) && ! is_wp_error( $category ) ) {
					$count = $category->count;
					$name  = $category->name;
					$id    = $category->term_id;
					$img   = get_term_meta( $value, 'taxonomy-thumbnail-id', false );

					if ( isset( $img ) && is_array( $img ) && isset( $img[0] ) && $img[0] != '' ) {
						$url1 = wp_get_attachment_image_url( $img[0], $brm_recipe_cat_size );

						echo '<li style="background-image: url(' . esc_url( $url1 ) . ')">';
						echo '<a target="' . esc_attr( $target ) . '" href="' . esc_url( get_term_link( $id ) ) . '"><span class="cat-title">' . esc_html( $name ) . '</span>';
						if ( $count > 0 ) {
							   echo '<span class="post-count">' . esc_html( $count ) . esc_html__( ' Recipe(s)', 'blossom-recipe-maker' ) . '</span>';
						}
						echo '</a></li>';
					} else {

						echo '<li class="brm-category-fallback-svg">';
						echo '<a target="' . esc_attr( $target ) . '" href="' . esc_url( get_term_link( $id ) ) . '"><span class="cat-title">' . esc_html( $name ) . '</span>';
						if ( $count > 0 ) {
							echo '<span class="post-count">' . esc_html( $count ) . esc_html__( ' Recipe(s)', 'blossom-recipe-maker' ) . '</span>';
						}
						echo '</a></li>';

						$image_size = $obj->brm_get_image_sizes( $brm_recipe_cat_size );
						$svg_fill   = apply_filters( 'brm_background_svg_fill', 'fill:%23f2f2f2;' );

						if ( $image_size ) {
							$url1 = ( "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 " . esc_attr( $image_size['width'] ) . ' ' . esc_attr( $image_size['height'] ) . "' preserveAspectRatio='none'><rect width='" . esc_attr( $image_size['width'] ) . "' height='" . esc_attr( $image_size['height'] ) . "' style='" . $svg_fill . "'></rect></svg>" );
							$url1 = "data:image/svg+xml; utf-8, $url1";
						}
						echo '<style>
						.brm-category-fallback-svg{
							background-image: url("' . esc_url( $url1 ) . '")
						}
						</style>';

					}
				}
			}
		}
		echo '</ul></div>';
		// This is where you run the code and display the output
		$html = ob_get_clean();
		echo wp_kses_post( apply_filters( 'brm_custom_categories_widget_filter', $html, $args, $instance ) );
		echo wp_kses_post( $args['after_widget'] );
	}

	// Widget Backend
	public function form( $instance ) {

		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'Recipe Categories', 'blossom-recipe-maker' );
		}
		$taxonomy = '';
		if ( isset( $instance['taxonomy'] ) && $instance['taxonomy'] != '' ) {
			$taxonomy = $instance['taxonomy'];
		}

		$categories[] = '';
		if ( isset( $instance['categories'] ) && $instance['categories'] != '' ) {
			$categories = $instance['categories'];
		}
		$target = ! empty( $instance['target'] ) ? $instance['target'] : '';

		// Widget admin form
		$ran = rand( 1, 1000 );
		$ran++;
		?>

		<input id="brm-terms-ran" value="<?php echo esc_attr( absint( $ran ) ); ?>" type="hidden">

		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'blossom-recipe-maker' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>"><?php esc_html_e( 'Choose Recipe Taxonomy:', 'blossom-recipe-maker' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'taxonomy' ) ); ?>" class="brm-taxonomy-selector widefat" id="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>" >

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
			<span class="brm-terms-holder">
		<?php
		if ( isset( $taxonomy ) && $taxonomy != '' ) {
			?>
				<label for="brm-categories-select-<?php echo esc_attr( absint( $ran ) ); ?>"><?php esc_html_e( 'Choose Terms: (Press Ctrl to select Multiple Terms)', 'blossom-recipe-maker' ); ?></label>

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
					<select name="<?php echo esc_attr( $this->get_field_name( 'categories[]' ) ); ?>" class="brm-cat-select brm-categories-select-<?php echo esc_attr( absint( $ran ) ); ?>" id="brm-categories-select-<?php echo esc_attr( absint( $ran ) ); ?>" multiple style="width:350px;" tabindex="4">
				<?php
				$cats = get_categories( 'taxonomy=' . $taxonomy );

				foreach ( $cats as $cat ) {
					$selected = ( in_array( $cat->term_id, $categories ) ? 'selected' : '' );
					printf(
						'<option value="%1$s" selected="%2$s">%3$s</option>',
						esc_html( $cat->term_id ),
						esc_attr( $selected ),
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

		<span class="brm-option-side-note" class="example-text">
		<?php
		printf( wp_kses_post( __( 'To set thumbnail for categories, go to %1$sBlossom Recipes > Categories%2$s and %3$sEdit%4$s the categories.', 'blossom-recipe-maker' ) ), '<b>', '</b>', '<b>', '</b>' );
		?>
		</span>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>">
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" value="1" <?php checked( $target, 1 ); ?> /><?php esc_html_e( 'Open Recipes in new Tab', 'blossom-recipe-maker' ); ?> </label>
		</p>

		
		<?php
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {

		$instance['title']    = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['taxonomy'] = ( ! empty( $new_instance['taxonomy'] ) ) ? strip_tags( $new_instance['taxonomy'] ) : '';
		$instance['target']   = ! empty( $new_instance['target'] ) ? esc_attr( $new_instance['target'] ) : '';

		$instance['categories'] = '';
		if ( isset( $new_instance['categories'] ) && $new_instance['categories'] != '' ) {
			$instance['categories'] = $new_instance['categories'];
		}
		return $instance;
	}
}
