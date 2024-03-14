<?php

use WordPress\Plugin\Encyclopedia\{
    I18n,
    MockingBird,
    Options,
    PostTypeLabels,
    Taxonomies
};

# Move encyclopedia taxonomies to the top
$arr_taxonomies = Array_Merge([
    'encyclopedia-category' => (object) [
        'label' => I18n::__('Encyclopedia Categories'),
        'name' => 'encyclopedia-category'
    ],
    'encyclopedia-tag' => false
], Taxonomies::getTaxonomies());
$arr_taxonomies = Array_Filter($arr_taxonomies);

# Change default labels
$arr_taxonomies['category']->label = I18n::__('Post Categories');
$arr_taxonomies['post_tag']->label = I18n::__('Post Tags');
$arr_taxonomies['encyclopedia-category']->label = I18n::__('Encyclopedia Categories');
$arr_taxonomies['encyclopedia-tag']->label = I18n::__('Encyclopedia Tags');

$tax_options = (array) Options::get('taxonomies');
?>
<p>
    <?php printf(I18n::__('Please choose the taxonomies you want to use for your %s.'), PostTypeLabels::getItemPluralName()) ?>
    <?php I18n::_e('The choosen taxonomies will show a prefix filter above the term archives and ordered by post title. The posts per page limit will be applied from the archive option below.') ?>
</p>

<input type="hidden" name="taxonomies" value="0">

<table class="form-table">

    <?php foreach ($arr_taxonomies as $taxonomy) : ?>
        <tr>
            <th>
                <label for="use_taxonomy_<?php echo $taxonomy->name ?>"><?php echo $taxonomy->label ?></label>
                <div class="taxonomy-name"><code>(<?php echo $taxonomy->name ?>)</code></div>
            </th>
            <td>
                <label for="register_<?php echo $taxonomy->name ?>_taxonomy_for_encyclopedia">
                    <?php if ($taxonomy->name == 'encyclopedia-tag') : ?>
                        <input type="checkbox" name="taxonomies[]" value="<?php echo $taxonomy->name ?>" id="register_<?php echo $taxonomy->name ?>_taxonomy_for_encyclopedia" <?php checked(in_Array($taxonomy->name, $tax_options)) ?>>
                    <?php else : ?>
                        <input type="checkbox" <?php disabled(true) ?>>
                    <?php endif ?>
                    <?php printf(I18n::__('Use this taxonomy for %s.'), PostTypeLabels::getItemPluralName()) ?>
                    <?php if ($taxonomy->name != 'encyclopedia-tag') MockingBird::printProNotice('unlock') ?>
                </label>
                <p class="help">
                    <?php printf(I18n::__('Enables %1$s for %2$s.'), $taxonomy->label, PostTypeLabels::getItemPluralName()) ?>
                    <?php if (!empty($taxonomy->post_types)) {
                        $arr_post_types = [];
                        foreach ($taxonomy->post_types as $post_type) $arr_post_types[] = $post_type->label;
                        echo wp_sprintf(I18n::__('This taxonomy is used for %l.'), $arr_post_types);
                    } ?>
                </p>
            </td>
        </tr>
    <?php endforeach ?>

</table>