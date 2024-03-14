<?php
/**
 * Template for print.
 *
 * @package Blosson Recipe Maker
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$submission_get = blossom_recipe_maker_get_submitted_data( 'get' );

if ( empty( $submission_get['print'] ) ) {
	return;
}


$brm_post_id = $submission_get['print'];
$recipe      = get_post_meta( $brm_post_id, 'br_recipe', true );

?>
<!DOCTYPE HTML>
<html>

<head>
	<title><?php bloginfo( 'name' ); ?></title>
</head>

<body class="blossom-recipe-print">

	<div id="br-recipe-container-<?php echo esc_attr( $brm_post_id ); ?>" data-id="<?php echo esc_attr( $brm_post_id ); ?>">

		<div class="br-recipe-rows">

			<div class="br-recipe-row">

				<span class="br-recipe-title">
					<h4><?php the_title(); ?></h4>
				</span>
			</div>

			<div class="br-recipe-row">

				<span class="br-recipe-description">
					<?php
					$post    = get_post( $brm_post_id );
					$content = strip_shortcodes( $post->post_content );
					echo wp_kses_post( $content );
					?>
				</span>

			</div>

			<div class="br-recipe-row">
				<ul class="br-recipe-tags">
					<?php
					if ( isset( $recipe['details']['difficulty_level'] ) && ! empty( $recipe['details']['difficulty_level'] ) ) {
						?>
						<li class="br-recipe-tags-difficulty">
							<span class="br-recipe-tag-name">
						<?php esc_html_e( 'Difficulty: ', 'blossom-recipe-maker' ); ?>
							</span>
							<span class="br-recipe-tag-terms">
						<?php esc_html_e( $recipe['details']['difficulty_level'], 'blossom-recipe-maker' ); ?>
							</span>
						</li>
						<?php
					}

					if ( has_term( '', 'recipe-category', $brm_post_id ) ) {
						?>
						<li class="br-recipe-tags-category">
							<span class="br-recipe-tag-name">
						<?php esc_html_e( 'Category: ', 'blossom-recipe-maker' ); ?>
							</span>
							<span class="br-recipe-tag-terms">
						<?php echo wp_kses_post( get_the_term_list( $brm_post_id, 'recipe-category', '', ', ' ) ); ?>
							</span>
						</li>
						<?php
					}

					if ( has_term( '', 'recipe-cuisine', $brm_post_id ) ) {
						?>
						<li class="br-recipe-tags-cuisine">
							<span class="br-recipe-tag-name">
						<?php esc_html_e( 'Cuisine: ', 'blossom-recipe-maker' ); ?>
							</span>
							<span class="br-recipe-tag-terms">
						<?php echo wp_kses_post( get_the_term_list( $brm_post_id, 'recipe-cuisine', '', ', ' ) ); ?>
							</span>
						</li>
						<?php
					}

					if ( has_term( '', 'recipe-cooking-method', $brm_post_id ) ) {
						?>
						<li class="br-recipe-tags-cooking-method">
							<span class="br-recipe-tag-name">
						<?php esc_html_e( 'Cooking Method: ', 'blossom-recipe-maker' ); ?>
							</span>
							<span class="br-recipe-tag-terms">
						<?php echo wp_kses_post( get_the_term_list( $brm_post_id, 'recipe-cooking-method', '', ', ' ) ); ?>
							</span>
						</li>
						<?php
					}
					?>
				</ul>
			</div>

			<div class="br-recipe-row">
				<?php
				if ( isset( $recipe['details']['servings'] ) && ! empty( $recipe['details']['servings'] ) ) {
					?>
					<div class="br-recipe-servings">
						<span class="br-recipe-title">
					<?php esc_html_e( 'Servings: ', 'blossom-recipe-maker' ); ?></span>

						<span class="br-recipe-servings">
					<?php echo esc_html( $recipe['details']['servings'] ); ?></span>

						<span class="br-recipe-servings-yield">
					<?php esc_html_e( 'yield(s)', 'blossom-recipe-maker' ); ?></span>
					</div>

					<?php
				}

				if ( isset( $recipe['details']['prep_time'] ) && ! empty( $recipe['details']['prep_time'] ) ) {
					?>

					<div class="br-recipe-prep-time">
						<span class="br-recipe-title">
					<?php esc_html_e( 'Prep Time: ', 'blossom-recipe-maker' ); ?></span>

						<span class="br-recipe-prep-time">
					<?php echo esc_html( $recipe['details']['prep_time'] ); ?></span>

						<span class="br-recipe-prep-time-text">
					<?php esc_html_e( 'mins', 'blossom-recipe-maker' ); ?></span>
					</div>
					<?php
				}

				if ( isset( $recipe['details']['cook_time'] ) && ! empty( $recipe['details']['cook_time'] ) ) {
					?>
					<div class="br-recipe-cook-time">

						<span class="br-recipe-title">
					<?php esc_html_e( 'Cook Time: ', 'blossom-recipe-maker' ); ?></span>

						<span class="br-recipe-cook-time">
					<?php echo esc_html( $recipe['details']['cook_time'] ); ?></span>

						<span class="br-recipe-cook-time-text">
					<?php esc_html_e( 'mins', 'blossom-recipe-maker' ); ?></span>

					</div>
					<?php
				}

				if ( isset( $recipe['details']['total_time'] ) && ! empty( $recipe['details']['total_time'] ) ) {
					?>

					<div class="br-recipe-total-time">
						<span class="br-recipe-title">
					<?php esc_html_e( 'Total Time: ', 'blossom-recipe-maker' ); ?></span>

						<span class="br-recipe-total-time">
					<?php echo esc_html( $recipe['details']['total_time'] ); ?></span>

						<span class="br-recipe-total-time-text">
					<?php esc_html_e( 'mins', 'blossom-recipe-maker' ); ?></span>
					</div>
					<?php
				}
				?>
			</div>

			<div class="br-recipe-row">

				<?php

				$ingredient_names = array_column( $recipe['ingredient'], 'ingredient' );

				$filteredingredients = array_filter( $ingredient_names );

				if ( isset( $recipe['ingredient'] ) && ! empty( $filteredingredients ) ) {
					?>
					<span class="br-recipe-title">
						<h5><?php esc_html_e( 'Ingredients', 'blossom-recipe-maker' ); ?></h5>
					</span>

					<ul class="br-recipe-ingredient-container">

					<?php
					foreach ( $recipe['ingredient'] as $ingredient ) {

						if ( isset( $ingredient['heading'] ) && ! empty( $ingredient['heading'] ) ) {
							?>
								<span class="br-recipe-ingredient-group">
									<h5><?php echo esc_attr( $ingredient['heading'] ); ?></h5>
								</span>
							<?php
						} elseif ( isset( $ingredient['ingredient'] ) && ! empty( $ingredient['ingredient'] ) ) {
							?>
								<li class="br-recipe-ingredient">
									<span class="br-recipe-box">

										<span class="br-recipe-ingredient-quantity">
							<?php esc_html_e( $ingredient['quantity'], 'blossom-recipe-maker' ); ?></span>

										<span class="br-recipe-ingredient-unit">
							<?php echo esc_html( $ingredient['unit'] ); ?></span>
									</span>

									<span class="br-recipe-box">

										<span class="br-recipe-ingredient-name">
							<?php echo esc_html( $ingredient['ingredient'] ); ?></span>

							<?php
							if ( ! empty( $ingredient['notes'] ) ) {
								 echo '<span class="br-recipe-ingredient-notes">('
								  . esc_html( $ingredient['notes'] ) . '</span>)';
							}
							?>
									</span>
								</li>
							<?php

						}
					}
					?>
					</ul>
					<?php
				}
				?>
			</div>

			<div class="br-recipe-row">

				<?php

				$instruction_description = array_column( $recipe['instructions'], 'description' );

				$filtereddescription = array_filter( $instruction_description );

				if ( ( $recipe['instructions'] ) && ( ! empty( $filtereddescription ) ) ) {
					?>
					<span class="br-recipe-title">
						<h5><?php esc_html_e( 'Instructions', 'blossom-recipe-maker' ); ?></h5>
					</span>

					<ol class="br-recipe-instruction-container">

					<?php
					foreach ( $recipe['instructions'] as $instruction ) {

						if ( isset( $instruction['heading'] ) && ! empty( $instruction['heading'] ) ) {
							?>
								<span class="br-recipe-instruction-group">
									<h5><?php echo esc_attr( $instruction['heading'] ); ?></h5>
								</span>
							<?php
						} elseif ( isset( $instruction['description'] ) && ! empty( $instruction['description'] ) ) {
							?>
								<li class="br-recipe-instruction">

									<span class="br-recipe-instruction-text">
							<?php echo esc_html( $instruction['description'] ); ?></span>
								</li>
							<?php

						}
					}
					?>
					</ol>
					<?php
				}
				?>
			</div>

			<?php
			if ( isset( $recipe['notes'] ) && ! empty( $recipe['notes'] ) ) {
				?>
				<div class="br-recipe-row">
					<span class="br-recipe-title">
						<h5><?php esc_html_e( 'Recipe Notes', 'blossom-recipe-maker' ); ?></h5>
					</span>

					<div class="br-recipe-notes">
						<p><?php echo wp_kses_post( html_entity_decode( $recipe['notes'] ) ); ?></p>
					</div>
				</div>
				<?php
			}
			?>

			<script>
				(function() {
					window.print();
				})();
			</script>

</body>

</html>
