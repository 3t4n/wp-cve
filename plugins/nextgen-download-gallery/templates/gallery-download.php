<?php
/*
Template for the NextGEN Download Gallery
Based on NextGEN Gallery (legacy) basic template (gallery.php) with a few custom additions

These variables are useable :

	$gallery     : Contains all about the gallery
	$images      : Contains all images, path, title
	$pagination  : Contains the pagination content

	$ngg_dlgallery_all_id  : for use in downloading the entire gallery, not only those displayed on one page
*/

if (!defined('ABSPATH')) {
	exit;
}

if (!empty($gallery)): ?>

	<div class="ngg-galleryoverview ngg-download" id="<?= $gallery->anchor ?>">

		<h3><?= $gallery->title; ?></h3>

		<?php if (!empty($gallery->description)): ?>
		<p><?= $gallery->description; ?></p>
		<?php endif; ?>

		<?php if (!empty($gallery->show_slideshow)) { ?>
			<div class="slideshowlink">
				<a class="slideshowlink" href="<?= $gallery->slideshow_link ?>">
					<?= $gallery->slideshow_link_text ?>
				</a>
			</div>
		<?php } ?>

		<?php if (!empty($gallery->show_piclens)) { ?>
			<div class="piclenselink">
				<a class="piclenselink" href="<?= $gallery->piclens_link ?>">
					<?php _e('[View with PicLens]','nextgen-download-gallery'); ?>
				</a>
			</div>
		<?php } ?>

		<!-- Thumbnails -->
		<form action="<?= esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="<?= $gallery->anchor ?>-download-frm" class="ngg-download-frm">
			<input type="hidden" name="action" value="ngg-download-gallery-zip" />
			<input type="hidden" name="gallery" value="<?= $gallery->title; ?>" />

			<?php $i = 0; foreach ( $images as $image ) : ?>

				<div id="ngg-image-<?= $image->pid ?>" class="ngg-gallery-thumbnail-box" <?= $image->style ?> >
					<div class="ngg-gallery-thumbnail" >
						<a href="<?= esc_url($image->imageURL); ?>" title="<?= esc_attr($image->description) ?>" <?= $image->thumbcode ?> >
							<?php if ( !$image->hidden ) { ?>
							<img title="<?= esc_attr($image->alttext) ?>" alt="<?= esc_attr($image->alttext) ?>" src="<?= esc_url($image->thumbnailURL); ?>" <?= $image->size ?> />
							<?php } ?>
						</a>
						<label><input type="checkbox" name="pid[]" value="<?= esc_attr($image->pid); ?>" /><span><?= esc_html($image->alttext) ?></span></label>
					</div>
				</div>

				<?php if ( $image->hidden ) continue; ?>
				<?php if ( $gallery->columns > 0 && (++$i % $gallery->columns) === 0 ) { ?>
				<br style="clear: both" />
				<?php } ?>

			<?php endforeach; ?>

			<hr class="ngg-download-separator" />
			<input class="button ngg-download-selectall" type="button" style="display:none" value="<?php _e('select all', 'nextgen-download-gallery'); ?>" />
			<input class="button ngg-download-download downloadButton" type="submit" value="<?php _e('download selected images', 'nextgen-download-gallery'); ?>" />
			<?php
			// get gallery ID for downloading all images, or false if not configured to do so
			$ngg_dlgallery_all_id = NextGENDownloadGallery::getDownloadAllId($gallery);
			if ($ngg_dlgallery_all_id): ?>
			<input class="button ngg-download-everything" type="submit" name="download-all" style="display:none" value="<?php _e('download all images', 'nextgen-download-gallery'); ?>" />
			<input type="hidden" name="all-id" value="<?= esc_attr($ngg_dlgallery_all_id); ?>" />
			<?php endif; ?>
		</form>

	<!-- Pagination -->
	<?= $pagination ?>

	</div>

<?php endif; ?>
