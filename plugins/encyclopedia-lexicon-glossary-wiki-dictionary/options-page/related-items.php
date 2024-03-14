<?php

use WordPress\Plugin\Encyclopedia\{
    I18n,
    MockingBird,
    Options,
    PostTypeLabels,
    Taxonomies
};

$arr_taxonomies = Taxonomies::getTaxonomies();

?>
<table class="form-table">

    <tr>
        <th><label for="related_items"><?php printf(I18n::__('Display related %s'), PostTypeLabels::getItemPluralName()) ?></label></th>
        <td>
            <input type="radio" id="related_items_below" <?php checked(true) ?>> <label for="related_items_below"><?php I18n::_e('below the content') ?></label><br>
            <input type="radio" id="related_items_above" <?php disabled(true) ?>> <label for="related_items_above"><?php I18n::_e('above the content') ?></label><?php MockingBird::printProNotice('unlock') ?><br>
            <input type="radio" id="related_items_none" <?php disabled(true) ?>> <label for="related_items_none"><?php printf(I18n::__('Do not show related %s.'), PostTypeLabels::getItemPluralName()) ?></label><?php MockingBird::printProNotice('unlock') ?>
        </td>
    </tr>

    <tr>
        <th><label for="relation_taxonomy"><?php I18n::_e('Relation Taxonomy') ?></label></th>
        <td>
            <select>
                <option value="" disabled="disabled">&mdash; <?php I18n::_e('Please choose a taxonomy') ?> &mdash;</option>
                <?php $relation_taxonomy = Options::get('relation_taxonomy');
                foreach ($arr_taxonomies as $taxonomy) :
                    $arr_post_type_labels = [];
                    foreach ($taxonomy->post_types as $post_type) $arr_post_type_labels[] = $post_type->label; ?>
                    <option <?php selected($relation_taxonomy, $taxonomy->name);
                            disabled($taxonomy->name != 'encyclopedia-tag') ?>>
                        <?php echo $taxonomy->label ?>
                        <?php if (!empty($arr_post_type_labels)) : ?>(<?php echo join(', ', $arr_post_type_labels) ?>)<?php endif ?>
                    </option>
                <?php endforeach ?>
            </select><?php MockingBird::printProNotice('unlock') ?>
        </td>
    </tr>

    <tr>
        <th><label><?php printf(I18n::__('Number of related %s'), PostTypeLabels::getItemPluralName()) ?></label></th>
        <td>
            <input type="number" value="10" <?php disabled(true) ?>><?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php printf(I18n::__('Number of related %s which should be shown.'), PostTypeLabels::getItemPluralName()) ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php I18n::_e('Relation Threshold') ?></label></th>
        <td>
            <input type="number" value="<?php echo Options::get('min_relation_threshold') ?>" <?php disabled(true) ?>><?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php printf(I18n::__('Minimum number of common taxonomy terms to generate a relation.'), PostTypeLabels::getItemPluralName()) ?></p>
        </td>
    </tr>

</table>