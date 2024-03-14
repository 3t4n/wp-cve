<?php

/**
 * Plugin class
 **/
class Blossom_Recipe_Maker_Taxonomy_Thumbnail {


	public function init() {

		add_action( 'recipe-category_add_form_fields', array( $this, 'add_recipe_taxonomy_image' ), 10, 2 );
		add_action( 'created_recipe-category', array( $this, 'save_recipe_taxonomy_image' ), 10, 2 );
		add_action( 'recipe-category_edit_form_fields', array( $this, 'update_recipe_taxonomy_image' ), 10, 2 );
		add_action( 'edited_recipe-category', array( $this, 'updated_recipe_taxonomy_image' ), 10, 2 );

		add_action( 'recipe-cuisine_add_form_fields', array( $this, 'add_recipe_taxonomy_image' ), 10, 2 );
		add_action( 'created_recipe-cuisine', array( $this, 'save_recipe_taxonomy_image' ), 10, 2 );
		add_action( 'recipe-cuisine_edit_form_fields', array( $this, 'update_recipe_taxonomy_image' ), 10, 2 );
		add_action( 'edited_recipe-cuisine', array( $this, 'updated_recipe_taxonomy_image' ), 10, 2 );

		add_action( 'recipe-cooking-method_add_form_fields', array( $this, 'add_recipe_taxonomy_image' ), 10, 2 );
		add_action( 'created_recipe-cooking-method', array( $this, 'save_recipe_taxonomy_image' ), 10, 2 );
		add_action( 'recipe-cooking-method_edit_form_fields', array( $this, 'update_recipe_taxonomy_image' ), 10, 2 );
		add_action( 'edited_recipe-cooking-method', array( $this, 'updated_recipe_taxonomy_image' ), 10, 2 );

		add_action( 'admin_enqueue_scripts', array( $this, 'blossom_recipe_load_media' ) );
		add_action( 'admin_footer', array( $this, 'load_taxonomy_image_script' ) );

	}

	public function blossom_recipe_load_media() {
		wp_enqueue_media();
	}

	/*
	* Add a form field in the taxonomy page
	* @since 1.0.0
	*/
	public function add_recipe_taxonomy_image( $taxonomy ) {
		?>
	<div class="form-field term-group">
	 <label for="taxonomy-thumbnail-id"><?php esc_html_e( 'Image', 'blossom-recipe-maker' ); ?></label>
	 <input type="hidden" id="taxonomy-thumbnail-id" name="taxonomy-thumbnail-id" class="taxonomy_media_id" value="">
	 <div id="taxonomy-thumbnail-wrapper"></div>
	 <p>
	   <input type="button" class="button button-secondary taxonomy_media_button" id="taxonomy_media_button" name="taxonomy_media_button" value="<?php esc_attr_e( 'Add Image', 'blossom-recipe-maker' ); ?>" />
	   <input type="button" class="button button-secondary taxonomy_media_remove" id="taxonomy_media_remove" name="taxonomy_media_remove" value="<?php esc_attr_e( 'Remove Image', 'blossom-recipe-maker' ); ?>" style="display:none;">
	 </p>
	</div>
		<?php
	}

	/*
	* Save the form field
	* @since 1.0.0
	*/
	public function save_recipe_taxonomy_image( $term_id, $tt_id ) {

		$submitted_post_data = blossom_recipe_maker_get_submitted_data( 'post' );

		if ( isset( $submitted_post_data['taxonomy-thumbnail-id'] ) && '' !== $submitted_post_data['taxonomy-thumbnail-id'] ) {
			$image = $submitted_post_data['taxonomy-thumbnail-id'];
			add_term_meta( $term_id, 'taxonomy-thumbnail-id', $image, true );
		}
	}

	/*
	* Edit the form field
	* @since 1.0.0
	*/
	public function update_recipe_taxonomy_image( $term, $taxonomy ) {
		?>
   <tr class="form-field term-group-wrap">
	 <th scope="row">
	   <label for="taxonomy-thumbnail-id"><?php esc_html_e( 'Image', 'blossom-recipe-maker' ); ?></label>
	 </th>
	 <td>
		<?php
		$image_id = get_term_meta( $term->term_id, 'taxonomy-thumbnail-id', true );
		?>
	   <input type="hidden" id="taxonomy-thumbnail-id" name="taxonomy-thumbnail-id" class="taxonomy_media_id" value="<?php echo esc_attr( $image_id ); ?>">
	   <div id="taxonomy-thumbnail-wrapper">
		<?php
		if ( $image_id ) {

			$img_size = apply_filters( 'br_tax_img_size', 'thumbnail' );
			$image    = wp_get_attachment_image_url( $image_id, $img_size );
			?>
			 <img class="recipe-taxonomy_thumbnail" src="<?php echo esc_url( $image ); ?>"/>
		<?php } ?>
	   </div>
		<?php
		if ( $image_id ) {
			?>
		   <p>
		   <input type="button" class="button button-secondary taxonomy_media_button" id="taxonomy_media_button" name="taxonomy_media_button" value="<?php esc_attr_e( 'Change Image', 'blossom-recipe-maker' ); ?>" />
		   <input type="button" class="button button-secondary taxonomy_media_remove" id="taxonomy_media_remove" name="taxonomy_media_remove" value="<?php esc_attr_e( 'Remove Image', 'blossom-recipe-maker' ); ?>">
		 </p>
			<?php
		} else {
			?>
			 <p>
			   <input type="button" class="button button-secondary taxonomy_media_button" id="taxonomy_media_button" name="taxonomy_media_button" value="<?php esc_attr_e( 'Add Image', 'blossom-recipe-maker' ); ?>" />
			   <input type="button" class="button button-secondary taxonomy_media_remove" id="taxonomy_media_remove" name="taxonomy_media_remove" value="<?php esc_attr_e( 'Remove Image', 'blossom-recipe-maker' ); ?>" style="display:none;">
			</p>
			<?php
		}
		?>
	 </td>
   </tr>
		<?php
	}

	/*
	* Update the form field value
	* @since 1.0.0
	*/
	public function updated_recipe_taxonomy_image( $term_id, $tt_id ) {

		$submitted_post_data = blossom_recipe_maker_get_submitted_data( 'post' );

		if ( isset( $submitted_post_data['taxonomy-thumbnail-id'] ) && '' !== $submitted_post_data['taxonomy-thumbnail-id'] ) {
			$image = $submitted_post_data['taxonomy-thumbnail-id'];
			update_term_meta( $term_id, 'taxonomy-thumbnail-id', $image );
		} else {
			update_term_meta( $term_id, 'taxonomy-thumbnail-id', '' );
		}
	}

	/*
	* Add script
	* @since 1.0.0
	*/

	public function load_taxonomy_image_script() {
		?>
	 <script>
		 jQuery(document).ready( function($) {
			 var mediaUploader;
			 $('.taxonomy_media_button.button').on('click', function(e) {

				e.preventDefault();

				if (mediaUploader) {
					mediaUploader.open();
					return;
				}

				var add = jQuery(this);

				var change = add.parent().find('input#taxonomy_media_button');
				var remove = add.parent().find('input#taxonomy_media_remove');

				mediaUploader = wp.media.frames.file_frame = wp.media({
					title: 'Choose Image',
					button: {
					text: 'Choose Image'},
					multiple: false });

				mediaUploader.on('select', function() {

					var attachment = mediaUploader.state().get('selection').first().toJSON();

					$('input#taxonomy-thumbnail-id').val(attachment.id).trigger('change');
					
					$('#taxonomy-thumbnail-wrapper').html('<img class="recipe-taxonomy_thumbnail" src=""/>');
					$('#taxonomy-thumbnail-wrapper .recipe-taxonomy_thumbnail').attr('src',attachment.url).css('display','block');

					jQuery(change).val(RecipeInstructions.change_image).trigger('change');
					jQuery(remove).show();
				});

				mediaUploader.open();

			});

			$('input#taxonomy_media_remove').on('click', function(e) {

				e.preventDefault();

				var image_delete = confirm(RecipeGallery.delete_warning);

				if( image_delete == true)
				{ 
				  $('input#taxonomy-thumbnail-id').val('');
				  $('#taxonomy-thumbnail-wrapper .recipe-taxonomy_thumbnail').attr('src','').css('display','none');
				  $('input#taxonomy_media_button').val(RecipeInstructions.add_image).trigger('change');
				  $('input#taxonomy_media_remove').hide();
				  }

			});

	  $(document).ajaxComplete(function(event, xhr, settings) {
		 var queryStringArr = settings.data.split('&');
		 if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
		   var xml = xhr.responseXML;
		   $response = $(xml).find('term_id').text();
		   if($response!=""){
			 // Clear the thumbnail image and hide the remove button
			 $('#taxonomy-thumbnail-wrapper').html('');
			 $('input#taxonomy_media_button').val(RecipeInstructions.add_image).trigger('change');
			 $('input#taxonomy_media_remove').hide();

		   }
		 }
	   });

		});
				
	 </script>
		<?php
	}

}
$obj = new Blossom_Recipe_Maker_Taxonomy_Thumbnail();
$obj->init();
