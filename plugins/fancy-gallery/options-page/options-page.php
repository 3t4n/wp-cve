<?php

use WordPress\Plugin\GalleryManager\{
    I18n,
    Options
};

$arr_columns = [
    'left' => (array) Options::$arr_option_box['main'],
    'right' => (array) Options::$arr_option_box['side']
];

$options_saved = isset($_GET['options_saved']);

?>
<div class="wrap">
    <h2><?php I18n::_e('Gallery Settings') ?></h2>

    <?php if ($options_saved) : ?>
        <div id="message" class="updated fade">
            <p><strong><?php I18n::_e('Settings saved.') ?></strong></p>
        </div>
    <?php endif ?>

    <form method="post" action="<?php echo remove_Query_Arg('options_saved') ?>">
        <div class="metabox-holder">
            <?php foreach ($arr_columns as $column => $boxes) : ?>
                <div class="postbox-container <?php echo $column ?>">
                    <?php foreach ($boxes as $box) : ?>
                        <div class="postbox <?php echo $box->slug ?>">
                            <div class="postbox-header">
                                <h2 class="hndle"><?php echo $box->title ?></h2>
                            </div>
                            <div class="inside"><?php include $box->file ?></div>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php endforeach ?>
        </div>

        <p class="submit">
            <input type="submit" class="button-primary" value="<?php I18n::_e('Save Changes') ?>">
        </p>

        <?php WP_Nonce_Field('save_gallery_manager_options') ?>
    </form>
</div>