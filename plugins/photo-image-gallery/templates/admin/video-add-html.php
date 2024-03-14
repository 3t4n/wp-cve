<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div id="uxgallery_add_videos" style="display: none;width: 500px">

    <div id="uxgallery_add_videos_wrap" data-gallery-id="" data-gallery-add-video-nonce="">
        <h2><?php echo __('Add Video URL From Youtube or Vimeo', 'gallery-img'); ?></h2>
        <div class="control-panel">
            <form method="post"
                  action="">
                <input type="text" id="ux_add_video_input" name="ux_add_video_input"/>
                <button class='save-slider-options button-primary ux-insert-video-button'
                        id='ux-insert-video-button'><?php echo __('Insert Video', 'gallery-img'); ?></button>
                <div id="add-video-popup-options">
                    <div>
                        <div>
                            <label for="show_title"><?php echo __('Title:', 'gallery-img'); ?></label>
                            <div>
                                <input name="show_title" value="" type="text"/>
                            </div>
                        </div>
                        <div>
                            <label for="show_description"><?php echo __('Description:', 'gallery-img'); ?></label>
                            <textarea id="show_description" name="show_description"></textarea>
                        </div>
                        <div>
                            <label for="show_url"><?php echo __('Url:', 'gallery-img'); ?></label>
                            <input type="text" name="show_url" value=""/>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
