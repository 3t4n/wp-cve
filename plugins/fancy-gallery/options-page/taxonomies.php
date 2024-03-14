<?php

use WordPress\Plugin\GalleryManager\{
    I18n,
    Mocking_Bird,
    Options,
    Taxonomies
};

$active_taxonomies = (array) Options::get('gallery_taxonomies');
$registered_taxonomies = Taxonomies::getTaxonomies();

$disabled_taxonomies = [I18n::__('Events'), I18n::__('Places'), I18n::__('Dates'), I18n::__('Persons'), I18n::__('Photographers')];

?>
<p><?php I18n::_e('Please select the taxonomies you want to use to classify your galleries.') ?></p>

<table>
    <?php foreach ($registered_taxonomies as $taxonomy => $tax_args) : ?>
        <tr>
            <td>
                <input type="checkbox" name="gallery_taxonomies[<?php echo $taxonomy ?>][name]" id="gallery_taxonomies_<?php echo $taxonomy ?>" value="<?php echo $taxonomy ?>" <?php checked(isset($active_taxonomies[$taxonomy])) ?>><label for="gallery_taxonomies_<?php echo $taxonomy ?>"><?php echo $tax_args['labels']['name'] ?></label>
            </td>
            <td>
                <input type="checkbox" name="gallery_taxonomies[<?php echo $taxonomy ?>][hierarchical]" id="gallery_taxonomies_<?php echo $taxonomy ?>_hierarchical" <?php checked(isset($active_taxonomies[$taxonomy]['hierarchical'])) ?>><label for="gallery_taxonomies_<?php echo $taxonomy ?>_hierarchical"><?php I18n::_e('hierarchical') ?></label>
            </td>
        </tr>
    <?php endforeach ?>
    
    <?php foreach ($disabled_taxonomies as $taxonomy) : ?>
        <tr>
            <td><input type="checkbox" <?php disabled(true) ?>><?php echo $taxonomy ?></td>
            <td><input type="checkbox" <?php disabled(true) ?>><?php I18n::_e('hierarchical') ?></td>
        </tr>
    <?php endforeach ?>
</table>

<p><?php Mocking_Bird::printProNotice('feature') ?></p>
