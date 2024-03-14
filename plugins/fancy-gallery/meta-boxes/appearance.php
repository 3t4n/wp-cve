<?php

use WordPress\Plugin\GalleryManager\{
    I18n,
    PostType,
    Thumbnails
}

?>
<table>
    <tr>
        <th><label for="gallery_columns"><?php I18n::_e('Columns') ?></label></th>
        <td>
            <select name="gallery[columns]" id="gallery_columns">
                <?php $selected = PostType::getMeta('columns');
                for ($columns = 1; $columns < 10; $columns++) : ?>
                    <option value="<?php echo $columns ?>" <?php selected($selected, $columns) ?>><?php echo $columns ?></option>
                <?php endfor ?>
            </select>
        </td>
    </tr>
    <tr>
        <th><label for="gallery_image_size"><?php I18n::_e('Size') ?></label></th>
        <td><?php echo Thumbnails::getDropdown([
                'name' => 'gallery[image_size]',
                'id' => 'gallery_image_size',
                'selected' => PostType::getMeta('image_size')
            ]) ?></td>
    </tr>
</table>
