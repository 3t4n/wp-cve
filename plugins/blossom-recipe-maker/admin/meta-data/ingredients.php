<?php
/**
 * Provides the 'Recipe Ingredients Information' view for the corresponding tab in the Post Meta Box.
 *
 * @link       test.com
 * @since      1.0.0
 *
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/admin/meta-data
 */
?>
 
<div id="blossom-recipe-tab-recipe-ingredients" class="inside hidden">
	

	<div id="br-recipe-ingredients">
		<h1><?php esc_html_e( 'Ingredients', 'blossom-recipe-maker' ); ?></h1>

		<?php
		$ingredients = get_post_meta( get_the_ID(), 'br_recipe', true );

		$measurements = Blossom_Recipe_Maker_Functions::measurements();


		if ( isset( $ingredients['ingredient'] ) ) {
				// print_r($ingredients['ingredient']);

				$ingredient_names = array_column( $ingredients['ingredient'], 'ingredient' );

				$filteredingredients = array_filter( $ingredient_names );

		}
		?>

		<div>

			<label for="br_recipe_servings">
				<h4><?php esc_html_e( 'Servings', 'blossom-recipe-maker' ); ?></h4>
			</label>
			<input min="1" id="br_recipe_servings" name="br_recipe[details][servings]" placeholder="4" type="number" value="<?php echo isset( $ingredients['details']['servings'] ) ? esc_attr( $ingredients['details']['servings'] ) : '1'; ?>">

		</div>
		
		<table id="br_recipe_ingredients">
  
			<thead>
				<tr>
					<td>&nbsp;</td>
					<th><?php esc_html_e( 'QUANTITY', 'blossom-recipe-maker' ); ?></th>
					<th><?php esc_html_e( 'UNIT', 'blossom-recipe-maker' ); ?></th>
					<th><?php esc_html_e( 'INGREDIENT', 'blossom-recipe-maker' ); ?><em style="color: red"> <?php esc_html_e( '*', 'blossom-recipe-maker' ); ?></em></th>
					<th><?php esc_html_e( 'NOTES', 'blossom-recipe-maker' ); ?></th>
				</tr>

			</thead>

			<tbody>

				<?php
				$count = 0;

				if ( isset( $ingredients['ingredient'] ) && ! empty( $filteredingredients ) ) {
					foreach ( $ingredients['ingredient'] as $ingredient ) {

						if ( isset( $ingredient['heading'] ) && ! empty( $ingredient['heading'] ) ) {
							?>
						<tr class="br_ingredients_heading">
							<td class="br_ingredients_sort_handle">
								<i class="fas fa-bars"></i>
							</td>
							<td colspan="4">
								<input name="br_recipe[ingredient][<?php echo esc_attr( $count ); ?>][heading]" id="br_recipe_ingredient_heading_<?php echo esc_attr( $count ); ?>" class="br_recipe_ingredient_heading" type="text" value="<?php echo isset( $ingredient['heading'] ) ? esc_attr( $ingredient['heading'] ) : ''; ?>">
							</td>
							<td>
								<span class="br_ingredients_heading_delete">
									<i class="fas fa-trash"></i>
								</span>
							</td>
						</tr>


							<?php
						} elseif ( isset( $ingredient['ingredient'] ) && ! empty( $ingredient['ingredient'] ) ) {
							?>
   
						<tr class="br_ingredients">

							<td class="br_ingredients_sort_handle">

								<i class="fas fa-bars"></i>

							</td>

							<td>

								<input name="br_recipe[ingredient][<?php echo esc_attr( $count ); ?>][quantity]" class="br_ingredients_quantity" id="br_ingredients_quantity_<?php echo esc_attr( $count ); ?>" placeholder="1" type="text" value="<?php echo isset( $ingredient['quantity'] ) ? esc_attr( $ingredient['quantity'] ) : ''; ?>">

							</td>

							<td>

								<select name="br_recipe[ingredient][<?php echo esc_attr( $count ); ?>][unit]" class="br_ingredients_unit" id="br_ingredients_unit_<?php echo esc_attr( $count ); ?>">

									<option value="">--</option>
									<?php
									foreach ( $measurements as $key => $measurement ) :
										echo '<option value="' . esc_attr( $key ) . '"' . ( $ingredient['unit'] == $key ? ' selected' : '' ) . '>' . esc_attr( $measurement['plural_abbr'] ) . '</option>';
									endforeach;
									?>
								</select>

							</td>

							<td>

								<input name="br_recipe[ingredient][<?php echo esc_attr( $count ); ?>][ingredient]" class="br_ingredients_name" id="br_ingredients_name_<?php echo esc_attr( $count ); ?>" placeholder="oil" type="text" value="<?php echo isset( $ingredient['ingredient'] ) ? esc_attr( $ingredient['ingredient'] ) : ''; ?>" required>

							</td>

							<td>
								
								<input name="br_recipe[ingredient][<?php echo esc_attr( $count ); ?>][notes]" class="br_ingredients_notes" id="br_ingredient_notes_<?php echo esc_attr( $count ); ?>" placeholder="" type="text" value="<?php echo isset( $ingredient['notes'] ) ? esc_attr( $ingredient['notes'] ) : ''; ?>">

							</td>

							<td>

								<span class="br_ingredients_delete">
									<i class="fas fa-trash"></i>
								</span>

							</td>

						</tr>
							<?php

						}
						$count++;
					}
				} else {
					?>

				<tr class="br_ingredients">

					<td class="br_ingredients_sort_handle">

						<i class="fas fa-bars"></i>

					</td>

					<td>

						<input name="br_recipe[ingredient][<?php echo esc_attr( $count ); ?>][quantity]" class="br_ingredients_quantity" id="br_ingredients_quantity_<?php echo esc_attr( $count ); ?>" placeholder="1" type="text" value="">

					</td>

					<td>

						<select name="br_recipe[ingredient][<?php echo esc_attr( $count ); ?>][unit]" class="br_ingredients_unit" id="br_ingredients_unit_<?php echo esc_attr( $count ); ?>">

							<option value="">--</option>
							<?php
							foreach ( $measurements as $key => $measurement ) :
								echo '<option value="' . esc_attr( $key ) . '">' . esc_attr( $measurement['plural_abbr'] ) . '</option>';
							endforeach;
							?>
						</select>

					</td>

					<td>

						<input name="br_recipe[ingredient][<?php echo esc_attr( $count ); ?>][ingredient]" class="br_ingredients_name" id="br_ingredients_name_<?php echo esc_attr( $count ); ?>" placeholder="oil" type="text" value="">

					</td>

					<td>
						
						<input name="br_recipe[ingredient][<?php echo esc_attr( $count ); ?>][notes]" class="br_ingredients_notes" id="br_ingredient_notes_<?php echo esc_attr( $count ); ?>" placeholder="" type="text" value="">

					</td>

					<td>

						<span class="br_ingredients_delete">
							<i class="fas fa-trash"></i>
						</span>

					</td>

				</tr>
					<?php
				}

				?>

			</tbody>

		</table>
		<br>

		<div id="br-add-ingredients-block"> 

			<a class="button button-primary button-large" href="javascript:void(0);" id="br-add-ingredients"><?php esc_html_e( 'Add an Ingredient', 'blossom-recipe-maker' ); ?></a>

			<a class="button button-primary button-large" href="javascript:void(0);" id="br-add-ingredients-heading"><?php esc_html_e( 'Add Section Heading', 'blossom-recipe-maker' ); ?></a>

		</div>

	</div>

	
</div>

