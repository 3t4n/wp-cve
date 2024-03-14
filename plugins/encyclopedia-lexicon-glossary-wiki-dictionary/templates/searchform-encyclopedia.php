<?php

use WordPress\Plugin\Encyclopedia\{
    I18n,
    PostType
};

$permalink_structure = get_Option('permalink_structure');
$search_url = get_Post_Type_Archive_Link(PostType::post_type_name);
$search_field_name = $options->search_mode == 'prefix' ? 'prefix' : 's';
$search_field_value = !empty($_GET[$search_field_name]) ? $_GET[$search_field_name] : '';

?>
<form role="search" method="get" class="encyclopedia search-form" action="<?php echo esc_URL($search_url) ?>">
    <?php if (empty($permalink_structure)) : ?>
        <input type="hidden" name="post_type" value="<?php echo PostType::post_type_name ?>">
    <?php endif ?>

    <?php if ($options->search_mode == 'exact') : ?>
        <input type="hidden" name="exact" value="1">
        <input type="hidden" name="sentence" value="1">
    <?php endif ?>

    <label class="screen-reader-text" for="encyclopedia-search-term"><?php I18n::_e('Search') ?></label>
    <input type="text" id="encyclopedia-search-term" name="<?php echo esc_Attr($search_field_name) ?>" class="search-field" value="<?php echo esc_Attr($search_field_value) ?>" placeholder="<?php echo esc_Attr(I18n::_x('Search&hellip;', 'placeholder')) ?>">
    <button type="submit" class="search-submit submit button" id="encyclopedia-search-submit"><?php echo esc_Attr(I18n::__('Search')) ?></button>
</form>