<?php
/**
 * Provides the 'Recipe Ingredients Instruction' view for the corresponding tab in the Post Meta Box.
 *
 * @link       test.com
 * @since      1.0.0
 *
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/admin/meta-data
 */
?>
 
<div id="blossom-recipe-tab-recipe-instructions" class="inside hidden">
		
	<div id="br-recipe-instructions">
		<h1><?php esc_html_e( 'Instructions', 'blossom-recipe-maker' ); ?></h1>

		<?php
		$instructions = get_post_meta( get_the_ID(), 'br_recipe', true );

		if ( isset( $instructions['instructions'] ) ) {
				// print_r($instructions['instructions']);

				$instruction_description = array_column( $instructions['instructions'], 'description' );

				$filtereddescription = array_filter( $instruction_description );

				$instruction_image = array_column( $instructions['instructions'], 'image' );

				$filteredimage = array_filter( $instruction_image );
		}
		?>
		 

		<table id="br_recipe_instructions">

			<thead>
				<tr>
					<td>&nbsp;</td>
					<th><?php esc_html_e( 'IMAGE', 'blossom-recipe-maker' ); ?></th>
					<th><?php esc_html_e( 'INSTRUCTION', 'blossom-recipe-maker' ); ?></th>   				
				</tr>

			</thead>

			<tbody>

				<?php
				$count = 0;


				if ( isset( $instructions['instructions'] ) && ( ! empty( $filtereddescription ) || ! empty( $filteredimage ) ) ) {
					foreach ( $instructions['instructions'] as $instruction ) {

						if ( isset( $instruction['heading'] ) && ! empty( $instruction['heading'] ) ) {
							?>
							<tr class="br_instructions_heading">
								<td class="br_instructions_sort_handle">
								   <i class="fas fa-bars"></i>
								</td>
								<td colspan="4">
									<input name="br_recipe[instructions][<?php echo esc_attr( $count ); ?>][heading]" id="br_recipe_instructions_heading_<?php echo esc_attr( $count ); ?>" class="br_recipe_instructions_heading" type="text" placeholder="Section Heading" value="<?php echo isset( $instruction['heading'] ) ? esc_attr( $instruction['heading'] ) : ''; ?>">
								</td>
								<td>
									<span class="br_instructions_heading_delete">
										<i class="fas fa-trash"></i>
									</span>
								</td>
							</tr>

							<?php
						} elseif ( ! empty( $instruction['description'] ) || ! empty( $instruction['image'] ) ) {

							?>

						 <tr class="br_instructions">

							<td class="br_instructions_sort_handle">

								<i class="fas fa-bars"></i>

							</td>

							<?php
							$img_size = apply_filters( 'br_description_img_size', 'thumbnail' );
							$image    = wp_get_attachment_image_url( $instruction['image'], $img_size );
							$class    = ! empty( $image ) ? 'has-image' : ''
							?>


							<td class="add-instruction-image <?php echo esc_attr( $class ); ?>">

								<input name="br_recipe[instructions][<?php echo esc_attr( $count ); ?>][image]" class="br_instructions_image" type="hidden" value="<?php echo isset( $instruction['image'] ) ? esc_attr( $instruction['image'] ) : ''; ?>" />

								<div class="br-btn-wrap">
									<input id="br-add-image" class="br_instructions_add_image" type="button" value="<?php echo ! empty( $image ) ? esc_attr__( 'Change Image', 'blossom-recipe-maker' ) : esc_attr__( 'Add Image', 'blossom-recipe-maker' ); ?>" />
									<label for="br-add-image">
										<div class="br-recipe-instruction-image-meta">
											<img src="<?php echo ! empty( $image ) ? esc_url( $image ) : ''; ?>" class="br_instructions_thumbnail"/>
										</div>
									</label>
								</div>
								<?php

								if ( ! empty( $image ) ) {
									?>
									<a class="br_instructions_remove_image" href='javascript:void(0);'>
										<i class="fas fa-trash"></i>
									</a>
									<a class="br_instructions_add_btn" href="javascript:void(0);" style="display:none">
										<i class="fas fa-plus"></i>
									</a>
									<?php
								} else {
									?>
									<a class="br_instructions_remove_image" href='javascript:void(0);' style="display:none">
										<i class="fas fa-trash"></i>
									</a>
									<a class="br_instructions_add_btn" href="javascript:void(0);">
										<i class="fas fa-plus"></i>
									</a>
									<?php
								}
								?>
																	   
							</td>

							<td>

								<textarea name="br_recipe[instructions][<?php echo esc_attr( $count ); ?>][description]" rows="6" cols="60" id="br_instructions_description_<?php echo esc_attr( $count ); ?>" class="br_instructions_description"><?php echo isset( $instruction['description'] ) ? esc_textarea( $instruction['description'] ) : ''; ?></textarea>

							</td>

							<td>

								<span class="br_instructions_delete">
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

					<tr class="br_instructions">

					<td class="br_instructions_sort_handle">

						<i class="fas fa-bars"></i>

					</td>

					<td class="add-instruction-image">

							<input name="br_recipe[instructions][<?php echo esc_attr( $count ); ?>][image]" class="br_instructions_image" type="hidden" value="" />
						
							<div class="br-btn-wrap">
								<input id="br-add-image" class="br_instructions_add_image" type="button" value="<?php esc_attr_e( 'Add Image', 'blossom-recipe-maker' ); ?>" />

								<label for="br-add-image">
									<div class="br-recipe-instruction-image-meta">
										<img src="" class="br_instructions_thumbnail">
									</div>
								</label>
							</div>
							<a class="br_instructions_add_btn" href="javascript:void(0);">
								<i class="fas fa-plus"></i>
							</a>
							<a class="br_instructions_remove_image" href='javascript:void(0);' style="display:none">
								<i class="fas fa-trash"></i>
							</a>                                                                   

					</td>

					<td>

						<textarea name="br_recipe[instructions][<?php echo esc_attr( $count ); ?>][description]" rows="6" cols="60" id="br_instructions_description_<?php echo esc_attr( $count ); ?>" class="br_instructions_description"></textarea>

					</td>

					<td>

						<span class="br_instructions_delete">
							<i class="fas fa-trash"></i>
						</span>

					</td>

				</tr>

				<?php } ?>



			</tbody>

		</table>
		<br>

		<div id="br-add-instructions-block"> 

			<a class="button button-primary button-large" href="javascript:void(0);" id="br-add-instructions"><?php esc_html_e( 'Add an Instruction', 'blossom-recipe-maker' ); ?></a>

			<a class="button button-primary button-large" href="javascript:void(0);" id="br-add-instructions-heading"><?php esc_html_e( 'Add Section Heading', 'blossom-recipe-maker' ); ?></a>

		</div>

	</div>
</div>

