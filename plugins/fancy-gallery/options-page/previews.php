<?php

use WordPress\Plugin\GalleryManager\{
    I18n,
    Options,
    Thumbnails
}

?>
<p>
    <?php I18n::_e('Gallery previews are randomly chosen images from a gallery.') ?>
    <?php I18n::_e('They will be shown where your theme would display a text excerpt for regular posts usually.') ?>
</p>

<?php /*
$arr_previews = Array(
  'enable_previews_gallery_single' => I18n::__('Enable previews for the gallery single page'),
  'enable_previews_gallery_archive' => I18n::__('Enable previews for gallery archives'),
  'enable_previews_gallery_taxonomy' => I18n::__('Enable previews for gallery taxonomy archives'),
  'enable_previews_gallery_search' => I18n::__('Enable previews for gallery search results'),
  'enable_previews_global_search' => I18n::__('Enable previews for global search results'),
);

foreach ($arr_previews as $preview_option => $preview_caption): ?>
<p>
  <input type="hidden" name="<?php echo $preview_option ?>" value="0">
  <input type="checkbox" name="<?php echo $preview_option ?>" id="<?php echo $preview_option ?>" value="1" <?php checked(Options::get($preview_option)) ?>>
  <label for="<?php echo $preview_option ?>"><?php echo $preview_caption ?></label>
</p>
<?php endforeach
*/ ?>

<p>
    <input type="hidden" name="enable_previews" value="0">
    <input type="checkbox" name="enable_previews" id="enable_previews" value="1" <?php checked(Options::get('enable_previews')) ?> data-toggle="div#preview-options">
    <label for="enable_previews"><?php I18n::_e('Enable previews for auto generated excerpts.') ?></label>
</p>

<div id="preview-options">
    <table class="form-table">
        <tr>
            <th><label for="preview_image_number"><?php I18n::_e('Number of images') ?></label></th>
            <td><input type="number" name="preview_image_number" id="preview_image_number" value="<?php echo esc_Attr((int) Options::get('preview_image_number')) ?>" min="1"></td>
        </tr>

        <tr>
            <th><label for="preview_columns"><?php I18n::_e('Columns') ?></label></th>
            <td><input type="number" name="preview_columns" id="preview_columns" value="<?php echo esc_Attr((int) Options::get('preview_columns')) ?>" min="1" max="9" step="1"></td>
        </tr>

        <tr>
            <th><label for="preview_thumb_size"><?php I18n::_e('Size') ?></label></th>
            <td><?php echo Thumbnails::getDropdown([
                    'name' => 'preview_thumb_size',
                    'id' => 'preview_thumb_size',
                    'selected' => Options::get('preview_thumb_size')
                ]) ?></td>
        </tr>
    </table>

    <p>
        <input type="hidden" name="enable_previews_for_custom_excerpts" value="0">
        <input type="checkbox" name="enable_previews_for_custom_excerpts" id="enable_previews_for_custom_excerpts" value="1" <?php checked(Options::get('enable_previews_for_custom_excerpts')) ?>>
        <label for="enable_previews_for_custom_excerpts"><?php I18n::_e('Enable previews for custom excerpts too. (Do not activate this option if your theme displays the excerpt and the content on the same page.)') ?></label>
    </p>

</div>
