<?php

use WordPress\Plugin\GalleryManager\{
    I18n,
    Gallery,
    Thumbnails
};

# Load this gallery
$gallery = new Gallery;

# Load the current image dimensions of thumbnail images
$thumbnail_size = Thumbnails::getDimensions('thumbnail');

?>
<div class="dynamic-gallery">
    <?php
    if ($arr_images = $gallery->getImages()) :
        foreach ($arr_images as $image) : ?>
            <param data-image-attachment-id="<?php echo $image->ID ?>">
            </param>
    <?php
        endforeach;
    endif;
    ?>

    <div class="image-template" style="width:<?php echo $thumbnail_size->width ?>px;height:<?php echo $thumbnail_size->height ?>px">
        <input type="hidden" name="images[]" class="image-attachment-id">
        <img class="image">
        <div class="image-index">0</div>
        <div class="delete-image"><button type="button" class="button-link attachment-close media-modal-icon delete-image" title="<?php I18n::_e('Remove image') ?>"><span class="screen-reader-text"><?php I18n::_e('Remove image') ?></span></button></div>
    </div>

    <div class="dynamic-images"></div>

    <button type="button" class="button-secondary add-image"><?php I18n::_e('Add images') ?></button>
</div>
