<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * The template loader of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/admin
 * @author     Blossom <test@test.com>
 */
class Blossom_Recipe_Maker_Hook_Functions {


	/**
	 * Hook in methods.
	 */
	public function __construct() {
		add_action( 'br_recipe_rating_action', array( $this, 'br_recipe_rating' ) );
		add_action( 'br_recipe_category_links_action', array( $this, 'br_recipe_category_links' ) );
		add_action( 'br_recipe_description_action', array( $this, 'br_recipe_description' ) );
		add_action( 'br_recipe_details_action', array( $this, 'br_recipe_details' ) );
		add_action( 'br_recipe_gallery_action', array( $this, 'br_recipe_gallery' ) );
		add_action( 'br_recipe_ingredients_action', array( $this, 'br_recipe_ingredients' ) );
		add_action( 'br_recipe_instructions_action', array( $this, 'br_recipe_instructions' ) );
		add_action( 'br_recipe_notes_action', array( $this, 'br_recipe_notes' ) );
		add_action( 'br_recipe_post_tags_action', array( $this, 'br_recipe_post_tags' ) );

		add_filter( 'br_recipe', 'do_shortcode', 10 );
	}

	function br_recipe_rating( $post_id ) {
		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$recipe         = get_post_meta( $post_id, 'br_recipe', true );
		$filteredrecipe = array_filter( $recipe['details'] );

		$rating = isset( $recipe['ratings'] ) ? $recipe['ratings'] : 0;
		if ( isset( $recipe['details'] ) && ! empty( $filteredrecipe ) && $rating != 0 ) {
			?>
			<div class="brm-comment-rating">
				<span class="rating-title"><?php esc_html_e( 'Ratings: ', 'blossom-recipe-maker' ); ?></span>
				<span class="brm-rating-stars">
					<?php
					for ( $i = 1; $i <= 5; $i++ ) {
						echo '<span class="brm-rating-star">';
						if ( $i <= $rating ) {
							ob_start();
							echo wp_kses_post( file_get_contents( BLOSSOM_RECIPE_MAKER_URL . '/public/images/star-full.svg' ) );
							$star_icon = ob_get_contents();
							ob_end_clean();

							echo wp_kses_post( apply_filters( 'brm_comment_rating_star_full_icon', $star_icon ) );
						} else {
							ob_start();
							echo wp_kses_post( file_get_contents( BLOSSOM_RECIPE_MAKER_URL . '/public/images/star-empty.svg' ) );
							$star_icon = ob_get_contents();
							ob_end_clean();

							echo wp_kses_post( apply_filters( 'brm_comment_rating_star_icon', $star_icon ) );
						}
						echo '</span>';
					}
					?>
				</span>
			</div>
			<?php
		}
	}

	function br_recipe_category_links( $post_id ) {
		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$recipe         = get_post_meta( $post_id, 'br_recipe', true );
		$filteredrecipe = array_filter( $recipe['details'] );

		if ( isset( $recipe['details'] ) && ! empty( $filteredrecipe ) ) {
			?>
			<div id="br-recipe-category-links">

				<?php
				echo '<div class="br-author">';
				echo '<i class="far fa-user-circle"></i>';
				echo '<span class="br-author-info">';
				esc_html_e( 'Author: ', 'blossom-recipe-maker' );
				the_author_posts_link();
				echo '</span></div>';
				?>

				<?php
				if ( has_term( '', 'recipe-category', $post_id ) ) {
					echo '<div class="br-category">';
					echo '<i class="fas fa-tag"></i>';
					echo '<span class="br-cat-links">';
					echo wp_kses_post( get_the_term_list( $post_id, 'recipe-category', esc_html__( 'Category: ', 'blossom-recipe-maker' ), ', ' ) );
					echo '</span></div>';
				}

				if ( has_term( '', 'recipe-cuisine', $post_id ) ) {
					echo '<div class="br-cuisine">';
					echo '<i class="fas fa-utensils"></i>';
					echo '<span class="cuisine-links">';
					echo wp_kses_post( get_the_term_list( $post_id, 'recipe-cuisine', esc_html__( 'Cuisine: ', 'blossom-recipe-maker' ), ', ' ) );
					echo '</span></div>';
				}

				if ( has_term( '', 'recipe-cooking-method', $post_id ) ) {
					echo '<div class="br-cooking-method">';
					echo '<i class="fas fa-utensils"></i>';
					echo '<span class="cooking-method-links">';
					echo wp_kses_post( get_the_term_list( $post_id, 'recipe-cooking-method', esc_html__( 'Cooking Method: ', 'blossom-recipe-maker' ), ', ' ) );
					echo '</span></div>';
				}

				?>
			</div>
			<?php
		}
	}

	function br_recipe_description( $post_id ) {
		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		?>
		<div class="recipe-description">
			<?php
			$post    = get_post( $post_id );
			$content = apply_filters( 'the_content', $post->post_content );
			echo wp_kses_post( $content );
			// the_content();
			?>

		</div>
		<?php
	}

	public function calculate_time( $name, $mins_time ) {

		$hours   = floor( esc_html( $mins_time ) / 60 );
		$minutes = esc_html( $mins_time ) % 60;

		echo '<span class="recipe_' . esc_attr( $name ) . '">';

		if ( isset( $hours ) && $hours != 0 ) {
			echo esc_html( $hours );
			esc_html_e( ' Hr ', 'blossom-recipe-maker' );
		}
		if ( isset( $minutes ) && $minutes != 0 ) {
			echo esc_html( $minutes );
			esc_html_e( ' Mins', 'blossom-recipe-maker' );
		}
		echo '</span>';
	}

	function br_recipe_details( $post_id ) {
		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$details         = get_post_meta( $post_id, 'br_recipe', true );
		$filtereddetails = array_filter( $details['details'] );

		if ( isset( $details['details'] ) && ! empty( $filtereddetails ) ) {
			?>
			<div id="br-recipe-details">

				<?php
				if ( isset( $details['details']['servings'] ) && ! empty( $details['details']['servings'] ) ) {
					?>

					<span class="br_recipe_servings">

						<i class="fas fa-utensils"></i>

						<?php esc_html_e( 'Yields: ', 'blossom-recipe-maker' ); ?>

						<?php
						echo '<span class="recipe_servings">' . esc_html( $details['details']['servings'] );
						echo esc_html( _n( ' Serving', ' Servings', absint( $details['details']['servings'] ), 'blossom-recipe-maker' ) );
						echo '</span>';

						?>
					</span>
					<?php
				}

				if ( isset( $details['details']['difficulty_level'] ) && ! empty( $details['details']['difficulty_level'] ) ) {
					?>
					<span class="br_recipe_difficulty">

						<i class="fas fa-tachometer-alt"></i>

						<?php
						esc_html_e( 'Difficulty: ', 'blossom-recipe-maker' );

						$difficulty = Blossom_Recipe_Maker_Functions::get_difficulty_label( $details['details']['difficulty_level'] );

						echo '<span class="recipe_difficulty_level">';
						echo esc_html( $difficulty );
						echo '</span>';

						?>
					</span>
					<?php
				}

				if ( isset( $details['details']['prep_time'] ) && ! empty( $details['details']['prep_time'] ) ) {
					?>

					<span class="br_recipe_prep_time">

						<i class="far fa-clock"></i>

						<?php esc_html_e( 'Prep Time: ', 'blossom-recipe-maker' ); ?>
						<?php
						$name = 'prep_time';
						$this->calculate_time( $name, $details['details']['prep_time'] );

						?>
					</span>
					<?php
				}

				if ( isset( $details['details']['cook_time'] ) && ! empty( $details['details']['cook_time'] ) ) {
					?>
					<span class="br_recipe_cook_time">

						<i class="far fa-clock"></i>

						<?php esc_html_e( 'Cook Time: ', 'blossom-recipe-maker' ); ?>

						<?php
						$name = 'cook_time';
						$this->calculate_time( $name, $details['details']['cook_time'] );

						?>
					</span>
					<?php
				}

				if ( isset( $details['details']['total_time'] ) && ! empty( $details['details']['total_time'] ) ) {
					?>
					<span class="br_recipe_total_time">

						<i class="far fa-clock"></i>

						<?php esc_html_e( 'Total Time: ', 'blossom-recipe-maker' ); ?>

						<?php
						$name = 'total_time';
						$this->calculate_time( $name, $details['details']['total_time'] );

						?>
					</span>
					<?php
				}
				?>

			</div>

			<?php
		}
	}

	function br_recipe_gallery( $post_id ) {

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$gallery = get_post_meta( $post_id, 'br_recipe_gallery', true );
		$options = get_option( 'br_recipe_settings', array() );

		if ( isset( $gallery['enable'] ) && ! empty( $gallery['video_url'] ) || isset( $gallery['enable'] ) && ! empty( $gallery[0] ) ) {
			?>
			<div class="feat-images-container owl-carousel owl-theme" id="recipe-gallery">
				<?php

				if ( isset( $gallery['video_url'] ) && ! empty( $gallery['video_url'] ) ) {
					$url      = $gallery['video_url'];
					$oembed   = new WP_oEmbed();
					$provider = $oembed->discover( $url );
					$video    = $oembed->fetch(
						$provider,
						$url,
						array(
							'width'  => 1170,
							'height' => 650,
						)
					);
					// $video = $oembed->get_data($url);
					$html  = $video->html;
					$thumb = $video->thumbnail_url;
					?>
					<div class="lightbox-img video">
						<img src="<?php echo esc_url( $thumb ); ?>">
						<a data-fancybox="gallery" href="<?php echo esc_url( $url ); ?>" data-width="1170" data-height="650">
							<i class="far fa-play-circle"></i>
						</a>
					</div>
					<?php
				}

				if ( ! empty( $gallery[0] ) ) {
					if ( $gallery ) :
						unset( $gallery['enable'], $gallery['video_url'] );
						foreach ( $gallery as $feat_img ) {
							$img_size = apply_filters( 'br_feat_gallery_img_size', 'recipe-maker-single-size' );
							$fimage   = wp_get_attachment_image_url( $feat_img, $img_size );

							if ( ! empty( $fimage ) ) {
								?>
								<div class="lightbox-img">
									<img src="<?php echo esc_url( $fimage ); ?>">
									<a href="<?php echo esc_url( $fimage ); ?>" data-fancybox="gallery">
										<i class="fas fa-expand-arrows-alt"></i>
									</a>
								</div>
								<?php
							}
						}
					endif;
				}
				?>
			</div>
			<?php
		} elseif ( isset( $options['feat_image'] ) && has_post_thumbnail( $post_id ) ) {
			?>
			<div class="feat-image">

				<?php
				$img_size = apply_filters( 'br_feat_img_size', 'recipe-maker-single-size' );
				echo wp_kses_post( wp_get_attachment_image( get_post_thumbnail_id( $post_id ), $img_size ) );
				?>
			</div>

			<?php
		} elseif ( isset( $options['feat_image'] ) && ! has_post_thumbnail( $post_id ) ) {
			$img_size = apply_filters( 'br_feat_img_size', 'recipe-maker-single-size' );
			?>
			<div class="svg-holder">

				<?php
				$obj = new Blossom_Recipe_Maker_Functions();
				$obj->brm_get_fallback_svg( $img_size ); // falback
				?>

			</div>

			<?php
		}
	}

	function br_recipe_ingredients( $post_id ) {
		?>
		<div id="br-recipe-ingredients">
			<?php

			if ( empty( $post_id ) ) {
				$post_id = get_the_ID();
			}

			$ingredients = get_post_meta( $post_id, 'br_recipe', true );

			$ingredient_names = array_column( $ingredients['ingredient'], 'ingredient' );

			$filteredingredients = array_filter( $ingredient_names );

			if ( isset( $ingredients['ingredient'] ) && ! empty( $filteredingredients ) ) {
				$total_ingredients = count( $filteredingredients );
				?>
				<div class="br-ingredient-wrap">
					<h4><?php esc_html_e( 'Ingredients', 'blossom-recipe-maker' ); ?></h4>

					<?php
					if ( isset( $ingredients['details']['servings'] ) && ! empty( $ingredients['details']['servings'] ) ) {
						?>
						<div class="br-progressbar-wrap">
							<div id="br_ingredients_counter">
								<?php
								echo '<span class="ingredient_checked" data-post="' . esc_attr( $post_id ) . '">0</span>';
								echo '<span class="ingredient_total">' . esc_html__( '/', 'blossom-recipe-maker' ) . esc_html( $total_ingredients );
								echo esc_html__( ' Ingredients', 'blossom-recipe-maker' ) . '</span>';
								?>
							</div>

							<div class="ingredient-progressbar-container">
								<div class="ingredient-progressbar-bar" data-post="<?php echo esc_attr( $post_id ); ?>"></div>
							</div>
						</div>
						<?php
					}
					?>
				</div>

				<?php
				$options = get_option( 'br_recipe_settings', array() );

				if ( ! empty( $ingredients['details']['servings'] && isset( $options['adjust_servings'] ) && $options['adjust_servings'] == 1 ) ) {
					?>

					<div id="adjust_recipe_servings">

						<?php esc_html_e( 'Adjust Servings', 'blossom-recipe-maker' ); ?>

						<input min="1" class="br_adjust-recipe-servings" data-original="<?php echo esc_attr( $ingredients['details']['servings'] ); ?>" data-post="<?php echo esc_attr( $post_id ); ?>" value="<?php echo esc_attr( $ingredients['details']['servings'] ); ?>" type="number">
					</div>
					<?php
				}
				?>

				<div class="br-ingredients-list-wrap">

					<ul>
						<?php
						$abc = 1;
						foreach ( $ingredients['ingredient'] as $ingredient ) {

							if ( isset( $ingredient['heading'] ) && ! empty( $ingredient['heading'] ) ) {
								?>
								<span class="br_ingredients_heading">

									<?php echo esc_attr( $ingredient['heading'] ); ?>

								</span>
								<?php
							} elseif ( isset( $ingredient['ingredient'] ) && ! empty( $ingredient['ingredient'] ) ) {
								$ran = rand( 1, 1000 );
								?>
								<li>
									<input type="checkbox" id="ingre-counter-wrap-<?php echo esc_attr( $ran ); ?>" class="ingredients_checkcounter" data-ingre="<?php echo esc_attr( $abc ); ?>" data-post="<?php echo esc_attr( $post_id ); ?>">

									<label for="ingre-counter-wrap-<?php echo esc_attr( $ran ); ?>" class="single_ingredient" id="ingredient_<?php echo esc_attr( $abc ); ?>" data-post="<?php echo esc_attr( $post_id ); ?>">

										<?php
										echo '<span class="ingredient_quantity" data-original="' . esc_html( $ingredient['quantity'] ) . '" data-post="' . esc_attr( $post_id ) . '">' . esc_html( $ingredient['quantity'] ) . '</span>&nbsp;';

										$measurement = Blossom_Recipe_Maker_Functions::get_measurement_label( $ingredient['unit'] );

										echo '<span class="ingredient_unit">' . esc_html( $measurement ) . '</span>&nbsp;';

										echo '<span class="ingredient_name">' . esc_html( $ingredient['ingredient'] ) . '</span>&nbsp;';

										if ( ! empty( $ingredient['notes'] ) ) {
											echo '<span class="ingredient_note">(' . esc_html( $ingredient['notes'] ) . ') </span>';
										}
										?>
									</label>
								</li>
								<?php
								$abc++;
							}
						}
						?>
					</ul>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}

	function br_recipe_instructions( $post_id ) {
		?>
		<div id="br-recipe-instructions">
			<?php
			if ( empty( $post_id ) ) {
				$post_id = get_the_ID();
			}

			$instructions = get_post_meta( $post_id, 'br_recipe', true );

			$instruction_description = array_column( $instructions['instructions'], 'description' );

			$filtereddescription = array_filter( $instruction_description );

			$instruction_image = array_column( $instructions['instructions'], 'image' );

			$filteredimage = array_filter( $instruction_image );

			if ( isset( $instructions['instructions'] ) && ( ! empty( $filtereddescription ) || ! empty( $filteredimage ) ) ) {
				$test = array_filter( $instructions['instructions'] );
				$i    = 0;
				foreach ( $test as $key ) {
					if ( ! empty( $key['description'] ) || ! empty( $key['image'] ) ) {
						$i++;
					}
				}
				$total_instructions = $i;

				?>
				<div class="br-instruction-wrap">
					<h4><?php esc_html_e( 'Instructions', 'blossom-recipe-maker' ); ?></h4>

					<div class="br-progressbar-wrap">
						<div id="br_instructions_counter">
							<?php
							echo '<span class="instructions_checked" data-post="' . esc_attr( $post_id ) . '">' . esc_html__( '0', 'blossom-recipe-maker' ) . '</span>';
							echo '<span class="instructions_total">' . esc_html__( '/', 'blossom-recipe-maker' ) . esc_html( $total_instructions );
							echo esc_html__( ' Instructions', 'blossom-recipe-maker' ) . '</span>';
							?>
						</div>

						<div class="instruction-progressbar-container">
							<div class="instruction-progressbar-bar" data-post="<?php echo esc_attr( $post_id ); ?>"></div>
						</div>
					</div>

				</div>

				<div class="br-instructions-list-wrap">

					<ul>
						<?php
						$count = 1;
						foreach ( $instructions['instructions'] as $instruction ) {

							if ( isset( $instruction['heading'] ) && ! empty( $instruction['heading'] ) ) {
								?>
								<span class="br_instructions_heading">

									<?php echo esc_attr( $instruction['heading'] ); ?>

								</span>

								<?php
							} elseif ( ! empty( $instruction['description'] ) || ! empty( $instruction['image'] ) ) {
								$ran      = rand( 1, 1000 );
								$img_size = apply_filters( 'br_instruction_img_size', 'recipe-maker-thumbnail-size' );
								$image    = wp_get_attachment_image_url( $instruction['image'], $img_size );

								?>
								<li>
									<input type="checkbox" id="instr-counter-wrap-<?php echo esc_attr( $ran ); ?>" class="instructions_checkcounter" data-count="<?php echo esc_attr( $count ); ?>" data-post="<?php echo esc_attr( $post_id ); ?>">

									<?php echo '<label for="instr-counter-wrap-' . esc_attr( $ran ) . '" class="instruction_step" id="step_' . esc_attr( $count ) . '" data-post="' . esc_attr( $post_id ) . '">' . esc_html__( 'Step ', 'blossom-recipe-maker' ) . esc_html( $count ) . '</label>'; ?>

									<?php
									if ( ! empty( $image ) ) {
										?>
										<div class="instruction-img">
											<img src="<?php echo esc_url( $image ); ?>" class="br_instructions_thumbnail" />
										</div>
										<?php
									}
									?>
									<?php
									echo '<span class="instruction_description">' . wp_kses_post( html_entity_decode( $instruction['description'] ) ) . '</span>';
									?>

								</li>
								<?php
								$count++;
							}
						}
						?>

					</ul>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}

	function br_recipe_notes( $post_id ) {
		?>
		<div id="br-recipe-notes">
			<?php

			if ( empty( $post_id ) ) {
				$post_id = get_the_ID();
			}

			$notes = get_post_meta( $post_id, 'br_recipe', true );

			if ( isset( $notes['notes'] ) && ! empty( $notes['notes'] ) ) {
				?>
				<h4><?php esc_html_e( 'Notes', 'blossom-recipe-maker' ); ?></h4>

				<?php
				$notes = apply_filters( 'br_recipe', $notes['notes'] );
				echo '<span class="recipe_notes">' . wp_kses_post( wpautop( html_entity_decode( $notes, 3, 'UTF-8' ) ) ) . '</span>';
			}

			?>
		</div>
		<?php
	}

	function br_recipe_post_tags( $post_id ) {
		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}
		if ( has_term( '', 'recipe-tag', $post_id ) ) {
			$tags = get_the_terms( $post_id, 'recipe-tag' );

			?>
			<div class="recipe-tags">
				<h4><?php esc_html_e( 'Tags', 'blossom-recipe-maker' ); ?></h4>

			<?php
			echo '<span class="recipe_tags">';
			foreach ( $tags as $tag ) {
				echo '<a href="' . esc_url( get_term_link( $tag->slug, 'recipe-tag' ) ) . '">#' . esc_html( $tag->name ) . '</a>&nbsp;&nbsp;';
			}
			echo '</span>';
			echo '</div>';
		}
	}
}
new Blossom_Recipe_Maker_Hook_Functions();
