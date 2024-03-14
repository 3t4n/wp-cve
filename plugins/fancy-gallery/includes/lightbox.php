<?php

namespace WordPress\Plugin\GalleryManager;

abstract class Lightbox
{
    public static function init(): void
    {
        add_Action('wp_footer', [static::class, 'printLightboxWrapper']);
    }

    public static function printLightboxWrapper(): void
    {
        if (Options::get('lightbox')) : ?>
            <div class="gallery-lightbox-container blueimp-gallery blueimp-gallery-controls">
                <div class="slides"></div>

                <?php if (Options::get('title_description')) : ?>
                    <div class="title-description">
                        <div class="title"></div>
                        <a class="gallery"></a>
                        <div class="description"></div>
                    </div>
                <?php endif ?>

                <a class="prev" title="<?php I18n::_e('Previous image') ?>"></a>
                <a class="next" title="<?php I18n::_e('Next image') ?>"></a>

                <?php if (Options::get('close_button')) : ?>
                    <a class="close" title="<?php I18n::_e('Close') ?>"></a>
                <?php endif ?>

                <?php if (Options::get('indicator_thumbnails')) : ?>
                    <ol class="indicator"></ol>
                <?php endif ?>

                <?php if (Options::get('slideshow_button')) : ?>
                    <a class="play-pause"></a>
                <?php endif ?>

                <?php do_Action('gallery_manager_lightbox_wrapper') ?>
            </div>
        <?php endif;
    }
}

Lightbox::init();
