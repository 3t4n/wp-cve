<?php
/**
 * Provides the 'Recipe Ingredients Information' view for the corresponding tab in the Post Meta Box.
 *
 * @link       test.com
 * @since      1.0.0
 *
 * @package    Blossom_Recipe_Maker
 * @subpackage Blossom_Recipe_Maker/admin/meta-data
 */
?>

<div id="blossom-recipe-tab-recipe-gallery" class="inside hidden">

	<div id="br-recipe-gallery">
		<h1><?php esc_html_e( 'Gallery', 'blossom-recipe-maker' ); ?></h1>

		<?php
		$gallery = get_post_meta( get_the_ID(), 'br_recipe_gallery', true );
		?>

		<div class="br-recipe-meta-enable has_checkbox">
			<h4><?php esc_html_e( 'Enable Gallery', 'blossom-recipe-maker' ); ?></h4>

			<span class="recipe-gallery-tooltip" title="<?php esc_html_e( 'Check this to enable gallery instead of featured image in single recipe.', 'blossom-recipe-maker' ); ?>">
				<i class="far fa-question-circle"></i>
			</span>
			
			<input type='checkbox' name='br_recipe_gallery[enable]' id='br_recipe_gallery_enable' value='1' <?php $c = isset( $gallery['enable'] ) ? esc_attr( $gallery['enable'] ) : '0'; ?> value='1' <?php checked( $c, true ); ?>/>

			<label for="br_recipe_gallery_enable" class="checkbox-label"></label>               

		</div>

		<div class="br-recipe-meta-video">

			<h4><?php esc_html_e( 'YouTube or Vimeo Video:', 'blossom-recipe-maker' ); ?></h4>
			<span class="recipe-gallery-tooltip" title="<?php esc_html_e( 'Paste a valid YouTube or Vimeo URL to display a video as the first item in single recipe.', 'blossom-recipe-maker' ); ?>">
				<i class="far fa-question-circle"></i>
			</span>                

			<input type='text' name='br_recipe_gallery[video_url]' id="br_recipe_gallery_video_url" value="<?php echo isset( $gallery['video_url'] ) ? esc_url( $gallery['video_url'] ) : ''; ?>" placeholder="ex. https://www.youtube.com/watch?v=recipe"/>

		</div>

		<div class="br-recipe-meta-gallery-btn">

			<h4><?php esc_html_e( 'Gallery Items', 'blossom-recipe-maker' ); ?></h4>

			<div class="br-recipe-meta-gallery-items">

				<ul id='recipe_feat_img_gallery_list'>

					<?php
					if ( $gallery ) :
						unset( $gallery['enable'], $gallery['video_url'] );

						foreach ( $gallery as $key => $value ) :

							$img_size = apply_filters( 'br_gallery_img_size', 'recipe_maker_gallery_size' );
							$image    = wp_get_attachment_image_url( $value, $img_size );

							?>
							<li>

								<input type='hidden' name='br_recipe_gallery[<?php echo esc_attr( $key ); ?>]' value='<?php echo esc_attr( $value ); ?>'/>

								<img class='recipe_image_preview' src='<?php echo esc_url( $image ); ?>'>

								<div class="br-gallery-btn-wrap">
									<a class='recipe_change_image_button' href='javascript:void(0);' data-uploader-title='<?php esc_attr_e( 'Change Image', 'blossom-recipe-maker' ); ?>' data-uploader-button-text='<?php esc_html_e( 'Change image', 'blossom-recipe-maker' ); ?>'>
										<i class="fas fa-pencil-alt"></i>
									</a>

									<a class='recipe_remove_image_button' href='javascript:void(0);'>
										<i class="fas fa-times"></i>                    
									</a>
								</div>

							</li>

							<?php
						endforeach;
					endif;
					?>

				</ul>
			</div>

			<a id='recipe_add_image_button' class="button button-primary button-large" href='javascript:void(0);' data-uploader-title='<?php esc_attr_e( 'Add Image(s) to Recipe gallery', 'blossom-recipe-maker' ); ?>' data-uploader-button-text='<?php esc_attr_e( 'Add Image(s)', 'blossom-recipe-maker' ); ?>'>
				<?php esc_html_e( 'Add Image(s)', 'blossom-recipe-maker' ); ?>
			</a>
			
		</div>

		
	</div>
</div>
