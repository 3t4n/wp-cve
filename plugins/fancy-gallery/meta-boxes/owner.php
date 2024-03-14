<?php

use WordPress\Plugin\GalleryManager\{
    I18n
}

?>
<label class="screen-reader-text" for="post_author_override"><?php I18n::_e('Owner') ?></label>

<?php
global $post;
WP_DropDown_Users([
    'name' => 'post_author_override',
    'selected' => empty($post->ID) ? $user_ID : $post->post_author,
    'include_selected' => true
]);
?>

<small>(<?php I18n::_e('Changes the owner of this gallery.') ?>)</small>
