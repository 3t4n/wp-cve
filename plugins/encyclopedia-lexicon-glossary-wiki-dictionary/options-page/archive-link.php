<?php

use WordPress\Plugin\Encyclopedia\{
    I18n,
    PostType
};

$archive_url = get_Post_Type_Archive_Link(PostType::post_type_name);

if (empty($archive_url)) : ?>

    <p><?php I18n::_e('There is no link to the archive page because the archive is disabled.') ?></p>

<?php else : ?>

    <p>
        <?php printf(I18n::__('The archive url is: <a href="%1$s" target="_blank">%1$s</a>'), $archive_url) ?>
    </p>

    <p>
        <?php printf(I18n::__('The RSS feed url is: <a href="%1$s" target="_blank">%1$s</a>'), get_Post_Type_Archive_Feed_Link(PostType::post_type_name)) ?>
    </p>

<?php endif;
