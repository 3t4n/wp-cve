<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;

wp_enqueue_media();
wp_enqueue_style('wp-jquery-ui-dialog');
wp_enqueue_script('jquery-ui-dialog');
wp_enqueue_script( WPAPE_GALLERY_ASSETS_PREFIX.'-field-type-gallery-lib', WPAPE_GALLERY_FIELDS_URL.'asset/fields/gallery/gallery.lib.min.js', array('jquery'), false, true);
wp_enqueue_script( WPAPE_GALLERY_ASSETS_PREFIX.'-field-type-gallery-lib1', WPAPE_GALLERY_FIELDS_URL.'asset/fields/gallery/gallery.lib.min.js', array('jquery'), false, true);
wp_register_script( WPAPE_GALLERY_ASSETS_PREFIX.'-field-type-gallery', WPAPE_GALLERY_FIELDS_URL.'asset/fields/gallery/script.min.js', array('jquery'), false, true);

wp_enqueue_script( WPAPE_GALLERY_ASSETS_PREFIX.'-field-type-gallery' );
$translation_array = array( 'iconUrl' => admin_url('/images/spinner.gif') );
wp_localize_script( WPAPE_GALLERY_ASSETS_PREFIX.'-field-type-gallery', 'apeGalleryFieldGallery', $translation_array );

wp_enqueue_style ( WPAPE_GALLERY_ASSETS_PREFIX.'-field-type-gallery', WPAPE_GALLERY_FIELDS_URL.'asset/fields/gallery/style.css', array( ), '' );

if ( $value == null || empty( $value ) || $value == ' ' || $value == '' ) $value = '';
?>

<?php if ($label) : ?>
	<div class="field small-12 columns">
		<label>
			<?php echo $label; ?>
		</label>
	</div>
<?php endif; ?>

<div class="content small-12 columns small-centered text-center">

	<button type="button" data-id="<?php echo $id; ?>" class="success large button expanded wpapeGalleryFieldImagesButton">
		<?php _e('Manage Images','gallery-images-ape'); ?>
	</button>
	<?php $value = is_array($value) ? implode(',', $value) : $value; ?>
	<input id="<?php echo $id; ?>" <?php echo $attributes; ?> type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
</div>

<?php if ($description) : ?>
	<div class="content small-12 columns">
		<p class="help-text"><?php echo $description; ?></p>
	</div>
<?php endif; ?>
	
	<div class="content small-12 columns">
		<p class="help-text">
			<?php _e('Open images manager and configure <strong>Link</strong> and <strong>Video</strong> (YouTube, Vimeo) for every gallery image.', 'gallery-images-ape'); ?>
		</p>
	</div>
	

<div class="content small-12 columns small-centered text-center">
	<div id="ape_gallery_images_preview" class="text-center">
		<span class="spinner is-active" style="margin-right: 50%; margin-bottom: 1em;"></span>
	</div>
</div>

<?php if (!WPAPE_GALLERY_PREMIUM) : ?>
	<div class="content small-12 columns text-center" style="margin: 15px 0 -25px;">
		<a href="<?php echo WPAPE_GALLERY_URL_ADDONS; ?>" target="_blank" class="warning button strong">
			+ <?php _e('Add Link and Video (Youtube/Vimeo) link Add-on', 'gallery-images-ape'); ?>		
		</a> 
	</div>	
<?php endif; ?>
