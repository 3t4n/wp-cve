<?php

use WordPress\Plugin\GalleryManager\{
    I18n,
    Options,
    PostType
}

?>
<p>
    <input type="hidden" name="enable_archive" value="0">
    <input type="checkbox" name="enable_archive" id="enable_archive" value="1" <?php Checked(Options::get('enable_archive')) ?> data-toggle="p.archive-link">
    <label for="enable_archive"><?php I18n::_e('Enable the gallery archive.') ?></label>
</p>

<?php if ($post_type_archive_link = get_Post_Type_Archive_Link(PostType::post_type_name)) : ?>
    <p class="archive-link"><?php printf(I18n::__('The archive link for your galleries is: <a href="%1$s" target="_blank">%1$s</a>'), $post_type_archive_link) ?></p>
<?php endif ?>

<?php if ($post_type_archive_feed_link = get_Post_Type_Archive_Feed_Link(PostType::post_type_name)) : ?>
    <p class="archive-link"><?php printf(I18n::__('The archive feed for your galleries is: <a href="%1$s" target="_blank">%1$s</a>'), $post_type_archive_feed_link) ?></p>
<?php endif;
