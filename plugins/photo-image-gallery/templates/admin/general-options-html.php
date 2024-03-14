<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$uxgallery_nonce_save_gen_options = wp_create_nonce('uxgallery_nonce_save_gen_options');

?>
<div class="wrap uxgallery_wrap">
    <div class="clear"></div>
    <div id="poststuff">
        <?php $path_site = plugins_url("../../assets/images/front_images", __FILE__);
        $path_album = plugins_url("../../assets/images/albums", __FILE__);
        ?>

        <div id="post-body-content" class="gallery-options">
            <div id="post-body-heading" class="for_general_">
                <h1><?php echo __('Templates Settings', 'gallery-img'); ?></h1>
            </div>

            <form action="admin.php?page=Options_gallery_styles&task=save&gen_options_nonce=<?php echo $uxgallery_nonce_save_gen_options; ?>"
                  method="post" id="adminForm" name="adminForm">
                <div id="gallery-options-list" class=" uxgallery_options_list">
                    <a onclick="" class="free_notice_button save-gallery-options button-primary"><?php echo __('Save Options', 'gallery-img'); ?></a>

                    <ul id="gallery-view-tabs" class="uxgallery_options_tabs">
                        <li><a href="#gallery-view-options-7"><?php echo __('Elastic Grid', 'gallery-img'); ?></a></li>
                        <li><a href="#gallery-view-options-0"><?php echo __('Popup Grid', 'gallery-img'); ?></a></li>
                        <li><a href="#gallery-view-options-1"><?php echo __('Info Slider', 'gallery-img'); ?></a></li>
                        <li><a href="#gallery-view-options-2"><?php echo __('Masonry', 'gallery-img'); ?></a></li>
                        <li><a href="#gallery-view-options-3"><?php echo __('Slideshow', 'gallery-img'); ?></a></li>
                        <li><a href="#gallery-view-options-4"><?php echo __('Lightbox Grid', 'gallery-img'); ?></a></li>
                        <li><a href="#gallery-view-options-5"><?php echo __('Justified', 'gallery-img'); ?></a></li>
                        <li><a href="#gallery-view-options-6"><?php echo __('Blog Style Gallery', 'gallery-img'); ?></a></li>
                    </ul>

                    <ul class="options-block uxgallery_options_contents" id="gallery-view-tabs-contents">
                        <div class="free_overlay">
                            <div>
                            <p>Template Optioons cant be edited in Free version.</br> Get the Pro version and customise your Gallery</p>
                                <a href="https://uxgallery.net/pricing/" target="_blank">Get Now</a>
                            </div>
                        </div>
                        <li class="gallery-view-options-0">
                            <span class="content_heading">Popup Grid</span>
                            <div>
                              <h3><?php echo __('Element Styles', 'gallery-img'); ?></h3>

                                <div class="has-background">
                                    <label for="ht_view2_content_in_center"><?php echo __('Show Content In The Center', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off"
                                           name="params[uxgallery_ht_view2_content_in_center]"/>
                                    <input type="checkbox"
                                           id="ht_view2_content_in_center" <?php if (get_option('uxgallery_ht_view2_content_in_center') == 'on') {
			                            echo 'checked="checked"';
		                            } ?> name="params[uxgallery_ht_view2_content_in_center]" value="on"/>
                                </div>
                                <div>
                                    <label for="image_natural_size_contentpopup"><?php echo __('Image Behavior', 'gallery-img'); ?></label>
                                    <select id="image_natural_size_contentpopup"
                                            name="params[uxgallery_image_natural_size_contentpopup]">
                                        <option <?php if (get_option('uxgallery_image_natural_size_contentpopup') == 'resize') {
                                            echo 'selected="selected"';
                                        } ?> value="resize"><?php echo __('Resize', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_image_natural_size_contentpopup') == 'natural') {
                                            echo 'selected="selected"';
                                        } ?> value="natural"><?php echo __('Natural', 'gallery-img'); ?></option>
                                    </select>
                                </div>


                                <div>
                                    <label for="ht_view2_element_width"><?php echo __('Element Image Width', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view2_element_width]"
                                           id="ht_view2_element_width"
                                           value="<?php echo get_option('uxgallery_ht_view2_element_width'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div class="has-background">
                                    <label for="ht_view2_element_height"><?php echo __('Element Image Height', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view2_element_height]"
                                           id="ht_view2_element_height"
                                           value="<?php echo get_option('uxgallery_ht_view2_element_height'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div>
                                    <label for="ht_view2_element_border_width"><?php echo __('Element Border Width', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view2_element_border_width]"
                                           id="ht_view2_element_border_width"
                                           value="<?php echo get_option('uxgallery_ht_view2_element_border_width'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div class="has-background">
                                    <label for="ht_view2_element_border_color"><?php echo __('Element Border Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view2_element_border_color]" type="text"
                                           class="color" id="ht_view2_element_border_color"
                                           value="#<?php echo get_option('uxgallery_ht_view2_element_border_color'); ?>"
                                           size="10"/>
                                </div>
                                <?php /*ns onhover effects start here*/ ?>
                                <div class="">
                                    <label for="uxgallery_album_popup_onhover_effects"><?= __('On Hover Effect', 'gallery-img') ?></label>
                                    <select id="uxgallery_album_popup_onhover_effects"
                                            name="params[uxgallery_album_popup_onhover_effects]">
                                        <option value="0" <?php if (get_option('uxgallery_album_popup_onhover_effects') == 0) {
                                            echo "selected='selected'";
                                        } ?>>
                                            dark layer
                                        </option>
                                        <option value="1" <?php if (get_option('uxgallery_album_popup_onhover_effects') == 1) {
                                            echo "selected='selected'";
                                        } ?>>
                                            blur
                                        </option>
                                        <option value="2" <?php if (get_option('uxgallery_album_popup_onhover_effects') == 2) {
                                            echo "selected='selected'";
                                        } ?>>
                                            image scale
                                        </option>
                                        <option value="3" <?php if (get_option('uxgallery_album_popup_onhover_effects') == 3) {
                                            echo "selected='selected'";
                                        } ?>>
                                            content in the bottom
                                        </option>
                                        <option value="4" <?php if (get_option('uxgallery_album_popup_onhover_effects') == 4) {
                                            echo "selected='selected'";
                                        } ?>>
                                            elastic
                                        </option>

                                    </select>
                                </div>
                                <div class="has-background for_popup_dark_hover">
                                    <label for="uxgallery_album_popup_dark_text_color"><?php echo __('Text color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_popup_dark_text_color]" type="text"
                                           class="color" id="uxgallery_album_popup_dark_text_color"
                                           value="#<?php echo get_option('uxgallery_album_popup_dark_text_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class=" for_popup_blur_hover">
                                    <label for="uxgallery_album_popup_blur_text_color"><?php echo __('Text color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_popup_blur_text_color]" type="text"
                                           class="color" id="uxgallery_album_popup_blur_text_color"
                                           value="#<?php echo get_option('uxgallery_album_popup_blur_text_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background for_popup_scale_hover">
                                    <label for="uxgallery_album_popup_scale_color"><?php echo __('Scale background color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_popup_scale_color]" type="text"
                                           class="color" id="uxgallery_album_popup_scale_color"
                                           value="#<?php echo get_option('uxgallery_album_popup_scale_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class=" for_popup_scale_hover">
                                    <label for="uxgallery_album_popup_scale_opacity"><?php echo __('Scale background opacity(%)', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_popup_scale_opacity]" min="0" max="100"
                                           id="uxgallery_album_popup_scale_opacity"
                                           value="<?= get_option('uxgallery_album_popup_scale_opacity'); ?>" size="10"
                                           autocomplete="off" type="number">
                                </div>
                                <div class="has-background for_popup_scale_hover">
                                    <label for="uxgallery_album_popup_scale_text_color"><?php echo __('Scale text color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_popup_scale_text_color]" type="text"
                                           class="color" id="uxgallery_album_popup_scale_text_color"
                                           value="#<?php echo get_option('uxgallery_album_popup_scale_text_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class=" for_popup_bottom_hover">
                                    <label for="uxgallery_album_popup_bottom_color"><?php echo __('Hover place background color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_popup_bottom_color]" type="text"
                                           class="color" id="uxgallery_album_popup_bottom_color"
                                           value="#<?php echo get_option('uxgallery_album_popup_bottom_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background for_popup_bottom_hover">
                                    <label for="uxgallery_album_popup_bottom_text_color"><?php echo __('Hover place text color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_popup_bottom_text_color]" type="text"
                                           class="color" id="uxgallery_album_popup_bottom_text_color"
                                           value="#<?php echo get_option('uxgallery_album_popup_bottom_text_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class=" for_popup_elastic_hover">
                                    <label for="uxgallery_album_popup_elastic_text_color"><?php echo __('Hover place text color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_popup_elastic_text_color]" type="text"
                                           class="color" id="uxgallery_album_popup_elastic_text_color"
                                           value="#<?php echo get_option('uxgallery_album_popup_elastic_text_color'); ?>"
                                           size="10"/>
                                </div>
                                <?php /*ns hover effects options end here*/ ?>
                            </div>
                            <div>
                                <h3><?php echo __('Element Title', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_view2_element_title_font_size"><?php echo __('Element Title Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view2_element_title_font_size]"
                                           id="ht_view2_element_title_font_size"
                                           value="<?php echo get_option('uxgallery_ht_view2_element_title_font_size'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div class="">
                                    <label for="uxgallery_album_popup_show_title"><?php echo __('Show Title', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off"
                                           name="params[uxgallery_album_popup_show_title]"/>
                                    <input type="checkbox"
                                           id="uxgallery_album_popup_show_title" <?php if (get_option('uxgallery_album_popup_show_title') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_album_popup_show_title]" value="on"/>
                                </div>
                            </div>


                            <div>
                                <h3><?php echo __('Popup Styles', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_view2_popup_full_width"><?php echo __('Popup Image Full Width', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off"
                                           name="params[uxgallery_ht_view2_popup_full_width]"/>
                                    <input type="checkbox"
                                           id="ht_view2_popup_full_width" <?php if (get_option('uxgallery_ht_view2_popup_full_width') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_view2_popup_full_width]" value="on"/>
                                </div>
                                <div class="has-background">
                                    <label for="ht_view2_popup_background_color"><?php echo __('Popup Background Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view2_popup_background_color]" type="text"
                                           class="color" id="ht_view2_popup_background_color"
                                           value="#<?php echo get_option('uxgallery_ht_view2_popup_background_color'); ?>"
                                           size="10"/>
                                </div>
                                <div>
                                    <label for="ht_view2_popup_overlay_color"><?php echo __('Popup Overlay Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view2_popup_overlay_color]" type="text"
                                           class="color" id="ht_view2_popup_overlay_color"
                                           value="#<?php echo get_option('uxgallery_ht_view2_popup_overlay_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background">
                                    <label for="ht_view2_popup_overlay_transparency_color"><?php echo __('Popup Overlay Opacity', 'gallery-img'); ?></label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_ht_view2_popup_overlay_transparency_color]"
                                               id="ht_view2_popup_overlay_transparency_color"
                                               data-slider-highlight="true"
                                               data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text"
                                               data-slider="true"
                                               value="<?php echo get_option('uxgallery_ht_view2_popup_overlay_transparency_color'); ?>"/>
                                        <span><?php echo get_option('uxgallery_ht_view2_popup_overlay_transparency_color'); ?>
                                            %</span>
                                    </div>
                                </div>
                                <div>
                                    <label for="ht_view2_popup_closebutton_style"><?php echo __('Popup Close Button Style', 'gallery-img'); ?></label>
                                    <select id="ht_view2_popup_closebutton_style"
                                            name="params[uxgallery_ht_view2_popup_closebutton_style]">
                                        <option <?php if (get_option('uxgallery_ht_view2_popup_closebutton_style') == 'light') {
                                            echo 'selected="selected"';
                                        } ?> value="light"><?php echo __('Light', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_ht_view2_popup_closebutton_style') == 'dark') {
                                            echo 'selected="selected"';
                                        } ?> value="dark"><?php echo __('Dark', 'gallery-img'); ?></option>
                                    </select>
                                </div>
                                <div class="has-background">
                                    <label for="ht_view2_show_separator_lines"><?php echo __('Show Separator Lines', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off"
                                           name="params[uxgallery_ht_view2_show_separator_lines]"/>
                                    <input type="checkbox"
                                           id="ht_view2_show_separator_lines" <?php if (get_option('uxgallery_ht_view2_show_separator_lines') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_view2_show_separator_lines]" value="on"/>
                                </div>
                                <div>
                                    <label for="light_box_arrowkey"><?php echo __('Keyboard navigation', 'gallery-img'); ?></label>
                                    <input type="hidden" value="false" name="params[uxgallery_light_box_arrowkey]"/>
                                    <input type="checkbox"
                                           id="light_box_arrowkey" <?php if (get_option('uxgallery_light_box_arrowkey') == 'true') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_light_box_arrowkey]" value="true"/>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Popup Title', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_view2_popup_title_font_size"><?php echo __('Popup Title Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view2_popup_title_font_size]"
                                           id="ht_view2_element_title_font_size"
                                           value="<?php echo get_option('uxgallery_ht_view2_popup_title_font_size'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div>
                                    <label for="ht_view2_popup_title_font_color"><?php echo __('Popup Title Font Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view2_popup_title_font_color]" type="text"
                                           class="color" id="ht_view2_popup_title_font_color"
                                           value="#<?php echo get_option('uxgallery_ht_view2_popup_title_font_color'); ?>"
                                           size="10"/>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Popup Description', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_view2_show_description"><?php echo __('Show Description', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off"
                                           name="params[uxgallery_ht_view2_show_description]"/>
                                    <input type="checkbox"
                                           id="ht_view2_show_description" <?php if (get_option('uxgallery_ht_view2_show_description') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_view2_show_description]" value="on"/>
                                </div>
                                <div>
                                    <label for="ht_view2_description_font_size"><?php echo __('Description Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view2_description_font_size]"
                                           id="ht_view2_description_font_size"
                                           value="<?php echo get_option('uxgallery_ht_view2_description_font_size'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div class="has-background">
                                    <label for="ht_view2_description_color"><?php echo __('Description Font Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view2_description_color]" type="text"
                                           class="color" id="ht_view2_description_color"
                                           value="#<?php echo get_option('uxgallery_ht_view2_description_color'); ?>"
                                           size="10"/>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Popup Link Button', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_view2_show_popup_linkbutton"><?php echo __('Show Link Button', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off"
                                           name="params[uxgallery_ht_view2_show_popup_linkbutton]"/>
                                    <input type="checkbox"
                                           id="ht_view2_show_popup_linkbutton" <?php if (get_option('uxgallery_ht_view2_show_popup_linkbutton') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_view2_show_popup_linkbutton]" value="on"/>
                                </div>
                                <div>
                                    <label for="ht_view2_popup_linkbutton_text"><?php echo __('Link Button Text', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view2_popup_linkbutton_text]"
                                           id="ht_view2_popup_linkbutton_text"
                                           value="<?php echo esc_attr(get_option('uxgallery_ht_view2_popup_linkbutton_text')); ?>"
                                           class="text"/>
                                </div>
                                <div class="has-background">
                                    <label for="ht_view2_popup_linkbutton_font_size"><?php echo __('Link Button Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view2_popup_linkbutton_font_size]"
                                           id="ht_view2_popup_linkbutton_font_size"
                                           value="<?php echo get_option('uxgallery_ht_view2_popup_linkbutton_font_size'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div>
                                    <label for="ht_view2_popup_linkbutton_color"><?php echo __('Link Button Font Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view2_popup_linkbutton_color]" type="text"
                                           class="color" id="ht_view2_popup_linkbutton_color"
                                           value="#<?php echo get_option('uxgallery_ht_view2_popup_linkbutton_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background">
                                    <label for="ht_view2_popup_linkbutton_font_hover_color"><?php echo __('Link Button Font Hover Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view2_popup_linkbutton_font_hover_color]"
                                           type="text" class="color" id="ht_view2_popup_linkbutton_font_hover_color"
                                           value="#<?php echo get_option('uxgallery_ht_view2_popup_linkbutton_font_hover_color'); ?>"
                                           size="10"/>
                                </div>
                                <div>
                                    <label for="ht_view2_popup_linkbutton_background_color"><?php echo __('Link Button Background Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view2_popup_linkbutton_background_color]"
                                           type="text" class="color" id="ht_view2_popup_linkbutton_background_color"
                                           value="#<?php echo get_option('uxgallery_ht_view2_popup_linkbutton_background_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background">
                                    <label for="ht_view2_popup_linkbutton_background_hover_color"><?php echo __('Link Button Background Hover Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view2_popup_linkbutton_background_hover_color]"
                                           type="text" class="color"
                                           id="ht_view2_popup_linkbutton_background_hover_color"
                                           value="#<?php echo get_option('uxgallery_ht_view2_popup_linkbutton_background_hover_color'); ?>"
                                           size="10"/>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Pagination Styles', 'gallery-img'); ?></h3>
                                <div class="fixed-size has-background">
                                    <label for="video_ht_view1_paginator_fontsize"><?php echo __('Pagination Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view1_paginator_fontsize]"
                                           id="video_ht_view1_paginator_fontsize"
                                           value="<?php echo get_option('uxgallery_video_ht_view1_paginator_fontsize'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class="  fixed-size">
                                    <label for="video_ht_view1_paginator_color"><?php echo __('Pagination Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view1_paginator_color]"
                                           class="color" id="video_ht_view1_paginator_color"
                                           value="<?php echo get_option('uxgallery_video_ht_view1_paginator_color'); ?>"
                                           class="text">

                                </div>
                                <div class="fixed-size has-background">
                                    <label for="video_ht_view1_paginator_icon_size"><?php echo __('Pagination Icons Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view1_paginator_icon_size]"
                                           id="video_ht_view1_paginator_icon_size"
                                           value="<?php echo get_option('uxgallery_video_ht_view1_paginator_icon_size'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class=" fixed-size">
                                    <label for="video_ht_view1_paginator_icon_color"><?php echo __('Pagination Icons Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view1_paginator_icon_color]"
                                           class="color" id="video_ht_view1_paginator_icon_color"
                                           value="<?php echo get_option('uxgallery_video_ht_view1_paginator_icon_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background">
                                    <label for="video_ht_view1_paginator_position"><?php echo __('Pagination Position', 'gallery-img'); ?></label>
                                    <select id="video_ht_view1_paginator_position"
                                            name="params[uxgallery_video_ht_view1_paginator_position]">
                                        <option <?php if (get_option('uxgallery_video_ht_view1_paginator_position') == 'left') {
                                            echo 'selected';
                                        } ?> value="left"><?php echo __('Left', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_video_ht_view1_paginator_position') == 'center') {
                                            echo 'selected';
                                        } ?> value="center"><?php echo __('Center', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_video_ht_view1_paginator_position') == 'right') {
                                            echo 'selected';
                                        } ?> value="right"><?php echo __('Right', 'gallery-img'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Load More Styles', 'gallery-img'); ?></h3>
                                <div class="fixed-size has-background">
                                    <label for="video_ht_view1_loadmore_text"><?php echo __('Load More Text', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view1_loadmore_text]"
                                           id="video_ht_view1_loadmore_text"
                                           value="<?php echo get_option('uxgallery_video_ht_view1_loadmore_text'); ?>"
                                           class="text">

                                </div>
                                <div>
                                    <label for="video_ht_view1_loadmore_position"><?php echo __('Load More Position', 'gallery-img'); ?></label>
                                    <select id="video_ht_view1_loadmore_position"
                                            name="params[uxgallery_video_ht_view1_loadmore_position]">
                                        <option <?php if (get_option('uxgallery_video_ht_view1_loadmore_position') == 'left') {
                                            echo 'selected';
                                        } ?> value="left"><?php echo __('Left', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_video_ht_view1_loadmore_position') == 'center') {
                                            echo 'selected';
                                        } ?> value="center"><?php echo __('Center', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_video_ht_view1_loadmore_position') == 'right') {
                                            echo 'selected';
                                        } ?> value="right"><?php echo __('Right', 'gallery-img'); ?></option>
                                    </select>
                                </div>
                                <div class="has-background fixed-size">
                                    <label for="video_ht_view1_loadmore_fontsize"><?php echo __('Load More Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view1_loadmore_fontsize]"
                                           id="video_ht_view1_loadmore_fontsize"
                                           value="<?php echo get_option('uxgallery_video_ht_view1_loadmore_fontsize'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class="fixed-size">
                                    <label for="video_ht_view1_loadmore_font_color"><?php echo __('Load More Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view1_loadmore_font_color]"
                                           class="color" id="video_ht_view1_loadmore_font_color"
                                           value="<?php echo get_option('uxgallery_video_ht_view1_loadmore_font_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="video_ht_view1_loadmore_font_color_hover"><?php echo __('Load More Font Hover Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_video_ht_view1_loadmore_font_color_hover]"
                                           class="color" id="video_ht_view1_loadmore_font_color_hover"
                                           value="<?php echo get_option('uxgallery_video_ht_view1_loadmore_font_color_hover'); ?>"
                                           class="text">
                                </div>
                                <div class="fixed-size">
                                    <label for="video_ht_view1_button_color"><?php echo __('Load More Button Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view1_button_color]"
                                           class="color" id="video_ht_view1_button_color"
                                           value="<?php echo get_option('uxgallery_video_ht_view1_button_color'); ?>"
                                           class="text">

                                </div>
                                <div class="fixed-size has-background">
                                    <label for="video_ht_view1_button_color_hover"><?php echo __('Load More Background Hover Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view1_button_color_hover]"
                                           class="color" id="video_ht_view1_button_color_hover"
                                           value="<?php echo get_option('uxgallery_video_ht_view1_button_color_hover'); ?>"
                                           class="text">
                                </div>

                                <div class="navigation-type-block has-height">
                                    <label for=""><?php echo __('Loading Animation', 'gallery-img'); ?></label>

                                    <div class="has-height has-background clear">
                                        <div>
                                            <ul id="arrows-type">
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_video_ht_view1_loading_type') == 1) {
                                                    echo "class='active'";
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading1.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_video_ht_view1_loading_type]"
                                                           value="1" <?php if (get_option('uxgallery_video_ht_view1_loading_type') == 1) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_video_ht_view1_loading_type') == 2) {
                                                    echo 'class=""';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading4.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_video_ht_view1_loading_type]"
                                                           value="2" <?php if (get_option('uxgallery_video_ht_view1_loading_type') == 2) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_video_ht_view1_loading_type') == 3) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading36.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_video_ht_view1_loading_type]"
                                                           value="3" <?php if (get_option('uxgallery_video_ht_view1_loading_type') == 3) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_video_ht_view1_loading_type') == 4) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading51.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_video_ht_view1_loading_type]"
                                                           value="4" <?php if (get_option('uxgallery_video_ht_view1_loading_type') == 4) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Ratings Styles', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_popup_rating_count"><?php echo __('Show Ratings Count', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off" name="params[uxgallery_ht_popup_rating_count]"/>
                                    <input type="checkbox"
                                           id="ht_popup_rating_count" <?php if (get_option('uxgallery_ht_popup_rating_count') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_popup_rating_count]" value="on"/>
                                </div>
                                <div class="fixed-size">
                                    <label for="ht_popup_likedislike_bg"><?php echo __('Ratings Background Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_popup_likedislike_bg]" class="color"
                                           id="ht_popup_likedislike_bg"
                                           value="<?php echo get_option('uxgallery_ht_popup_likedislike_bg'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background">
                                    <label for="ht_popup_likedislike_bg_trans"><?php echo __('Ratings Background Color Opacity', 'gallery-img'); ?></label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_ht_popup_likedislike_bg_trans]"
                                               id="ht_popup_likedislike_bg_trans" data-slider-highlight="true"
                                               data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text"
                                               data-slider="true"
                                               value="<?php echo get_option('uxgallery_ht_popup_likedislike_bg_trans'); ?>"/>
                                        <span><?php echo get_option('uxgallery_ht_popup_likedislike_bg_trans'); ?>
                                            %</span>
                                    </div>
                                </div>
                                <div class="fixed-size">
                                    <label for="ht_popup_likedislike_font_color"><?php echo __('Ratings Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_popup_likedislike_font_color]"
                                           class="color" id="ht_popup_likedislike_font_color"
                                           value="<?php echo get_option('uxgallery_ht_popup_likedislike_font_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_popup_active_font_color"><?php echo __('Ratings Rated Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_popup_active_font_color]"
                                           class="color" id="ht_popup_active_font_color"
                                           value="<?php echo get_option('uxgallery_ht_popup_active_font_color'); ?>"
                                           class="text">

                                </div>

                                <div class="fixed-size">
                                    <label for="ht_popup_likedislike_thumb_color"><?php echo __('Like/Dislike Icon Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_popup_likedislike_thumb_color]"
                                           class="color" id="ht_popup_likedislike_thumb_color"
                                           value="<?php echo get_option('uxgallery_ht_popup_likedislike_thumb_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_popup_likedislike_thumb_active_color"><?php echo __('Like/Dislike Rated Icon Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_ht_popup_likedislike_thumb_active_color]"
                                           class="color" id="ht_popup_likedislike_thumb_active_color"
                                           value="<?php echo get_option('uxgallery_ht_popup_likedislike_thumb_active_color'); ?>"
                                           class="text">

                                </div>
                                <div class="fixed-size">
                                    <label for="ht_popup_heart_likedislike_thumb_color"><?php echo __('Heart Icon Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_popup_heart_likedislike_thumb_color]"
                                           class="color" id="ht_popup_heart_likedislike_thumb_color"
                                           value="<?php echo get_option('uxgallery_ht_popup_heart_likedislike_thumb_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_popup_heart_likedislike_thumb_active_color"><?php echo __('Heart Rated Icon Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_ht_popup_heart_likedislike_thumb_active_color]"
                                           class="color" id="ht_popup_heart_likedislike_thumb_active_color"
                                           value="<?php echo get_option('uxgallery_ht_popup_heart_likedislike_thumb_active_color'); ?>"
                                           class="text">

                                </div>
                            </div>

                            <div>
                                <h3><?php echo __('Album options', 'gallery-img'); ?></h3>

                                <div class="has-background">
                                    <label for="uxgallery_album_popup_show_image_count_2"><?= __('Show images count', 'gallery-img') ?></label>
                                    <input value="off" name="params[uxgallery_album_popup_show_image_count_2]"
                                           type="hidden">
                                    <input id="uxgallery_album_popup_show_image_count_2"
                                           name="params[uxgallery_album_popup_show_image_count_2]"
                                           value="on" <?php if (get_option("uxgallery_album_popup_show_image_count_2") == "on") {
                                        echo "checked='checked'";
                                    } ?> size="10" type="checkbox">
                                </div>




                                <div class="has-height">
                                    <label for="uxgallery_album_popup_show_image_count_2"><?= __('Images Count Style', 'gallery-img') ?></label>
                                    <ul id="arrows-type">
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_popup_count_style') == 0) {
                                            echo "class='active'";
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/count/count-0.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_popup_count_style]"
                                                   value="0" <?php if (get_option('uxgallery_album_popup_count_style') == 0) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_popup_count_style') == 1) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/count/count-1.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_popup_count_style]"
                                                   value="1" <?php if (get_option('uxgallery_album_popup_count_style') == 1) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_popup_count_style') == 2) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/count/count-2.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_popup_count_style]"
                                                   value="2" <?php if (get_option('uxgallery_album_popup_count_style') == 2) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_popup_count_style') == 3) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/count/count-3.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_popup_count_style]"
                                                   value="3" <?php if (get_option('uxgallery_album_popup_count_style') == 3) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_popup_count_style') == 4) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/count/count-4.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_popup_count_style]"
                                                   value="4" <?php if (get_option('uxgallery_album_popup_count_style') == 4) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                    </ul>
                                </div>

                                <div class="has-height">
                                    <label for="uxgallery_album_popup_show_image_count_2"><?= __('Category Buttons Style', 'gallery-img') ?></label>
                                    <ul id="arrows-type">
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_popup_category_style') == 0) {
                                            echo "class='active'";
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/0.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_popup_category_style]"
                                                   value="0" <?php if (get_option('uxgallery_album_popup_category_style') == 0) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_popup_category_style') == 1) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/1.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_popup_category_style]"
                                                   value="1" <?php if (get_option('uxgallery_album_popup_category_style') == 1) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_popup_category_style') == 2) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/2.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_popup_category_style]"
                                                   value="2" <?php if (get_option('uxgallery_album_popup_category_style') == 2) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_popup_category_style') == 3) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/3.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_popup_category_style]"
                                                   value="3" <?php if (get_option('uxgallery_album_popup_category_style') == 3) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_popup_category_style') == 4) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/4.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_popup_category_style]"
                                                   value="4" <?php if (get_option('uxgallery_album_popup_category_style') == 4) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_popup_category_style') == 5) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/5.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_popup_category_style]"
                                                   value="5" <?php if (get_option('uxgallery_album_popup_category_style') == 5) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_popup_category_style') == 6) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/6.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_popup_category_style]"
                                                   value="6" <?php if (get_option('uxgallery_album_popup_category_style') == 6) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                    </ul>
                                </div>
                                <div class="has-background">
                                    <label for="uxgallery_album_popup_show_description"><?= __('Show Description', 'gallery-img') ?></label>
                                    <input value="off" name="params[uxgallery_album_popup_show_description]"
                                           type="hidden">
                                    <input id="uxgallery_album_popup_show_description"
                                           name="params[uxgallery_album_popup_show_description]"
                                           value="on" <?php if (get_option("uxgallery_album_popup_show_description") == "on") {
                                        echo "checked='checked'";
                                    } ?> size="10" type="checkbox">
                                </div>

                                <div class="">
                                    <label for="uxgallery_album_popup_window_thumbnails"><?= __('Show Thumbnails in Popup Window', 'gallery-img') ?></label>
                                    <input value="off" name="params[uxgallery_album_popup_window_thumbnails]"
                                           type="hidden">
                                    <input id="uxgallery_album_popup_window_thumbnails"
                                           name="params[uxgallery_album_popup_window_thumbnails]"
                                           value="on" <?php if (get_option("uxgallery_album_popup_window_thumbnails") == "on") {
                                        echo "checked='checked'";
                                    } ?> size="10" type="checkbox">
                                </div>
                                <div class="has-background">
                                    <label for="uxgallery_album_popup_window_controls"><?= __('Show Controls in Popup Window', 'gallery-img') ?></label>
                                    <input value="off" name="params[uxgallery_album_popup_window_controls]"
                                           type="hidden">
                                    <input id="uxgallery_album_popup_window_controls"
                                           name="params[uxgallery_album_popup_window_controls]"
                                           value="on" <?php if (get_option("uxgallery_album_popup_window_controls") == "on") {
                                        echo "checked='checked'";
                                    } ?> size="10" type="checkbox">
                                </div>
                                <div class="">
                                    <label for="uxgallery_album_popup_window_controls_on_top"><?= __('Show Controls on the Top in Popup Window', 'gallery-img') ?></label>
                                    <input value="off" name="params[uxgallery_album_popup_window_controls_on_top]"
                                           type="hidden">
                                    <input id="uxgallery_album_popup_window_controls_on_top"
                                           name="params[uxgallery_album_popup_window_controls_on_top]"
                                           value="on" <?php if (get_option("uxgallery_album_popup_window_controls_on_top") == "on") {
                                        echo "checked='checked'";
                                    } ?> size="10" type="checkbox">
                                </div>

                            </div>
                        </li>

                        <li class="gallery-view-options-1">
                            <span class="content_heading">Info slider</span>
                            <div>
                                <h3><?php echo __('Slider Container', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_view5_main_image_width"><?php echo __('Main Image Width', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view5_main_image_width]"
                                           id="ht_view5_main_image_width"
                                           value="<?php echo get_option('uxgallery_ht_view5_main_image_width'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div>
                                    <label for="ht_view5_slider_background_color"><?php echo __('Slider Background Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view5_slider_background_color]" type="text"
                                           class="color" id="ht_view5_slider_background_color"
                                           value="#<?php echo get_option('uxgallery_ht_view5_slider_background_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background">
                                    <label for="ht_view5_icons_style"><?php echo __('Arrow Icons Style', 'gallery-img'); ?></label>
                                    <select id="ht_view5_icons_style" name="params[uxgallery_ht_view5_icons_style]">
                                        <option <?php if (get_option('uxgallery_ht_view5_icons_style') == 'light') {
                                            echo 'selected="selected"';
                                        } ?> value="light"><?php echo __('Light', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_ht_view5_icons_style') == 'dark') {
                                            echo 'selected="selected"';
                                        } ?> value="dark"><?php echo __('Dark', 'gallery-img'); ?></option>
                                    </select>
                                </div>
                                <div>
                                    <label for="ht_view5_show_separator_lines"><?php echo __('Show Separator Lines', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off"
                                           name="params[uxgallery_ht_view5_show_separator_lines]"/>
                                    <input type="checkbox"
                                           id="ht_view5_show_separator_lines" <?php if (get_option('uxgallery_ht_view5_show_separator_lines') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_view5_show_separator_lines]" value="on"/>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Title', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_view5_title_font_size"><?php echo __('Title Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view5_title_font_size]"
                                           id="ht_view5_title_font_size"
                                           value="<?php echo get_option('uxgallery_ht_view5_title_font_size'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div>
                                    <label for="ht_view5_title_font_color"><?php echo __('Title Font Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view5_title_font_color]" type="text"
                                           class="color" id="ht_view5_title_font_color"
                                           value="#<?php echo get_option('uxgallery_ht_view5_title_font_color'); ?>"
                                           size="10"/>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Description', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_view5_show_description"><?php echo __('Show Description', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off"
                                           name="params[uxgallery_ht_view5_show_description]"/>
                                    <input type="checkbox"
                                           id="ht_view5_show_description" <?php if (get_option('uxgallery_ht_view5_show_description') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_view5_show_description]" value="on"/>
                                </div>
                                <div>
                                    <label for="ht_view5_description_font_size"><?php echo __('Description Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view5_description_font_size]"
                                           id="ht_view5_description_font_size"
                                           value="<?php echo get_option('uxgallery_ht_view5_description_font_size'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div class="has-background">
                                    <label for="ht_view5_description_color"><?php echo __('Description Font Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view5_description_color]" type="text"
                                           class="color" id="ht_view5_description_color"
                                           value="#<?php echo get_option('uxgallery_ht_view5_description_color'); ?>"
                                           size="10"/>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Link Button', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_view5_show_linkbutton"><?php echo __('Show Link Button', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off"
                                           name="params[uxgallery_ht_view5_show_linkbutton]"/>
                                    <input type="checkbox"
                                           id="ht_view5_show_linkbutton" <?php if (get_option('uxgallery_ht_view5_show_linkbutton') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_view5_show_linkbutton]" value="on"/>
                                </div>
                                <div>
                                    <label for="ht_view5_linkbutton_text"><?php echo __('Link Button Text', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view5_linkbutton_text]"
                                           id="ht_view5_linkbutton_text"
                                           value="<?php echo esc_attr(get_option('uxgallery_ht_view5_linkbutton_text')); ?>"
                                           class="text"/>
                                </div>
                                <div class="has-background">
                                    <label for="ht_view5_linkbutton_font_size"><?php echo __('Link Button Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view5_linkbutton_font_size]"
                                           id="ht_view5_linkbutton_font_size"
                                           value="<?php echo get_option('uxgallery_ht_view5_linkbutton_font_size'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div>
                                    <label for="ht_view5_linkbutton_color"><?php echo __('Link Button Font Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view5_linkbutton_color]" type="text"
                                           class="color" id="ht_view5_linkbutton_color"
                                           value="#<?php echo get_option('uxgallery_ht_view5_linkbutton_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background">
                                    <label for="ht_view5_linkbutton_font_hover_color"><?php echo __('Link Button Font Hover Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view5_linkbutton_font_hover_color]" type="text"
                                           class="color" id="ht_view5_linkbutton_font_hover_color"
                                           value="#<?php echo get_option('uxgallery_ht_view5_linkbutton_font_hover_color'); ?>"
                                           size="10"/>
                                </div>
                                <div>
                                    <label for="ht_view5_linkbutton_background_color"><?php echo __('Link Button Background Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view5_linkbutton_background_color]" type="text"
                                           class="color" id="ht_view5_linkbutton_background_color"
                                           value="#<?php echo get_option('uxgallery_ht_view5_linkbutton_background_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background">
                                    <label for="ht_view5_linkbutton_background_hover_color"><?php echo __('Link Button Background Hover Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view5_linkbutton_background_hover_color]"
                                           type="text" class="color" id="ht_view5_linkbutton_background_hover_color"
                                           value="#<?php echo get_option('uxgallery_ht_view5_linkbutton_background_hover_color'); ?>"
                                           size="10"/>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Ratings Styles', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_contentsl_rating_count"><?php echo __('Show Ratings Count', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off"
                                           name="params[uxgallery_ht_contentsl_rating_count]"/>
                                    <input type="checkbox"
                                           id="ht_contentsl_rating_count" <?php if (get_option('uxgallery_ht_contentsl_rating_count') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_contentsl_rating_count]" value="on"/>
                                </div>
                                <div class="fixed-size">
                                    <label for="ht_contentsl_likedislike_bg"><?php echo __('Ratings Background Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_contentsl_likedislike_bg]"
                                           class="color" id="ht_contentsl_likedislike_bg"
                                           value="<?php echo get_option('uxgallery_ht_contentsl_likedislike_bg'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background">
                                    <label for="ht_contentsl_likedislike_bg_trans"><?php echo __('Ratings Background Color Opacity', 'gallery-img'); ?></label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_ht_contentsl_likedislike_bg_trans]"
                                               id="ht_contentsl_likedislike_bg_trans" data-slider-highlight="true"
                                               data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text"
                                               data-slider="true"
                                               value="<?php echo get_option('uxgallery_ht_contentsl_likedislike_bg_trans'); ?>"/>
                                        <span><?php echo get_option('uxgallery_ht_contentsl_likedislike_bg_trans'); ?>
                                            %</span>
                                    </div>
                                </div>
                                <div class="fixed-size">
                                    <label for="ht_contentsl_likedislike_font_color"><?php echo __('Ratings Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_contentsl_likedislike_font_color]"
                                           class="color" id="ht_contentsl_likedislike_font_color"
                                           value="<?php echo get_option('uxgallery_ht_contentsl_likedislike_font_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_contentsl_active_font_color"><?php echo __('Ratings Rated Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_contentsl_active_font_color]"
                                           class="color" id="ht_contentsl_active_font_color"
                                           value="<?php echo get_option('uxgallery_ht_contentsl_active_font_color'); ?>"
                                           class="text">

                                </div>

                                <div class="fixed-size">
                                    <label for="ht_contentsl_likedislike_thumb_color"><?php echo __('Like/Dislike Icon Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_contentsl_likedislike_thumb_color]"
                                           class="color" id="ht_contentsl_likedislike_thumb_color"
                                           value="<?php echo get_option('uxgallery_ht_contentsl_likedislike_thumb_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_contentsl_likedislike_thumb_active_color"><?php echo __('Like/Dislike Rated Icon Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_ht_contentsl_likedislike_thumb_active_color]"
                                           class="color" id="ht_contentsl_likedislike_thumb_active_color"
                                           value="<?php echo get_option('uxgallery_ht_contentsl_likedislike_thumb_active_color'); ?>"
                                           class="text">

                                </div>
                                <div class="fixed-size">
                                    <label for="ht_contentsl_heart_likedislike_thumb_color"><?php echo __('Heart Icon Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_ht_contentsl_heart_likedislike_thumb_color]"
                                           class="color" id="ht_contentsl_heart_likedislike_thumb_color"
                                           value="<?php echo get_option('uxgallery_ht_contentsl_heart_likedislike_thumb_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_contentsl_heart_likedislike_thumb_active_color"><?php echo __('Heart Rated Icon Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_ht_contentsl_heart_likedislike_thumb_active_color]"
                                           class="color" id="ht_contentsl_heart_likedislike_thumb_active_color"
                                           value="<?php echo get_option('uxgallery_ht_contentsl_heart_likedislike_thumb_active_color'); ?>"
                                           class="text">

                                </div>
                            </div>
                        </li>

                        <li class="gallery-view-options-2">
                            <span class="content_heading">Masonry</span>

                            <div class="light_margin">
                                <h3><?php echo __('Title', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_view6_title_font_size"><?php echo __('Title Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view6_title_font_size]"
                                           id="ht_view6_title_font_size"
                                           value="<?php echo get_option('uxgallery_ht_view6_title_font_size'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <!--<div>
                                    <label for="ht_view6_title_font_color"><?php echo __('Title Font Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view6_title_font_color]" type="text"
                                           class="color" id="ht_view6_title_font_color"
                                           value="#<?php echo get_option('uxgallery_ht_view6_title_font_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background">
                                    <label for="ht_view6_title_font_hover_color"><?php echo __('Title Font Hover Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view6_title_font_hover_color]" type="text"
                                           class="color" id="ht_view6_title_font_hover_color"
                                           value="#<?php echo get_option('uxgallery_ht_view6_title_font_hover_color'); ?>"
                                           size="10"/>
                                </div>
                                <div>
                                    <label for="ht_view6_title_background_color"><?php echo __('Title Background Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view6_title_background_color]" type="text"
                                           class="color" id="ht_view6_title_background_color"
                                           value="#<?php echo get_option('uxgallery_ht_view6_title_background_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background">
                                    <label for="ht_view6_title_background_transparency"><?php echo __('Title Background Opacity', 'gallery-img'); ?></label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_ht_view6_title_background_transparency]"
                                               id="ht_view6_title_background_transparency" data-slider-highlight="true"
                                               data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text"
                                               data-slider="true"
                                               value="<?php echo get_option('uxgallery_ht_view6_title_background_transparency'); ?>"/>
                                        <span><?php echo get_option('uxgallery_ht_view6_title_background_transparency'); ?>
                                            %</span>
                                    </div>
                                </div> -->
                                <div class="">
                                    <label for="uxgallery_album_lightbox_show_title"><?= __('Show Title', 'gallery-img') ?> </label>
                                    <input value="off" name="params[uxgallery_album_lightbox_show_title]"
                                           type="hidden">
                                    <input id="uxgallery_album_lightbox_show_title" <?php if (get_option('uxgallery_album_lightbox_show_title') == 'on') echo "checked='checked'" ?>
                                           name="params[uxgallery_album_lightbox_show_title]" value="on"
                                           type="checkbox">
                                </div>
                            </div>

                            <div>
                                <h3><?php echo __('Pagination Styles', 'gallery-img'); ?></h3>
                                <div class="fixed-size has-background">
                                    <label for="video_ht_view4_paginator_fontsize"><?php echo __('Pagination Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view4_paginator_fontsize]"
                                           id="video_ht_view4_paginator_fontsize"
                                           value="<?php echo get_option('uxgallery_video_ht_view4_paginator_fontsize'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class="  fixed-size">
                                    <label for="video_ht_view4_paginator_color"><?php echo __('Pagination Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view4_paginator_color]"
                                           class="color" id="video_ht_view4_paginator_color"
                                           value="<?php echo get_option('uxgallery_video_ht_view4_paginator_color'); ?>"
                                           class="text">

                                </div>
                                <div class="fixed-size has-background">
                                    <label for="video_ht_view4_paginator_icon_size"><?php echo __('Pagination Icons Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view4_paginator_icon_size]"
                                           id="video_ht_view4_paginator_icon_size"
                                           value="<?php echo get_option('uxgallery_video_ht_view4_paginator_icon_size'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class=" fixed-size">
                                    <label for="video_ht_view4_paginator_icon_color"><?php echo __('Pagination Icons Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view4_paginator_icon_color]"
                                           class="color" id="video_ht_view4_paginator_icon_color"
                                           value="<?php echo get_option('uxgallery_video_ht_view4_paginator_icon_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background">
                                    <label for="video_ht_view4_paginator_position"><?php echo __('Pagination Position', 'gallery-img'); ?></label>
                                    <select id="video_ht_view4_paginator_position"
                                            name="params[uxgallery_video_ht_view4_paginator_position]">
                                        <option <?php if (get_option('uxgallery_video_ht_view4_paginator_position') == 'left') {
                                            echo 'selected';
                                        } ?> value="left"><?php echo __('Left', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_video_ht_view4_paginator_position') == 'center') {
                                            echo 'selected';
                                        } ?> value="center"><?php echo __('Center', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_video_ht_view4_paginator_position') == 'right') {
                                            echo 'selected';
                                        } ?> value="right"><?php echo __('Right', 'gallery-img'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Load More Styles', 'gallery-img'); ?></h3>
                                <div class="fixed-size has-background">
                                    <label for="video_ht_view4_loadmore_text"><?php echo __('Load More Text', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view4_loadmore_text]"
                                           id="video_ht_view4_loadmore_text"
                                           value="<?php echo esc_attr(get_option('uxgallery_video_ht_view4_loadmore_text')); ?>"
                                           class="text">

                                </div>
                                <div>
                                    <label for="video_ht_view4_loadmore_position"><?php echo __('Load More Position', 'gallery-img'); ?></label>
                                    <select id="video_ht_view4_loadmore_position"
                                            name="params[uxgallery_video_ht_view4_loadmore_position]">
                                        <option <?php if (get_option('uxgallery_video_ht_view4_loadmore_position') == 'left') {
                                            echo 'selected';
                                        } ?> value="left"><?php echo __('Left', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_video_ht_view4_loadmore_position') == 'center') {
                                            echo 'selected';
                                        } ?> value="center"><?php echo __('Center', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_video_ht_view4_loadmore_position') == 'right') {
                                            echo 'selected';
                                        } ?> value="right"><?php echo __('Right', 'gallery-img'); ?></option>
                                    </select>
                                </div>
                                <div class="has-background fixed-size">
                                    <label for="video_ht_view4_loadmore_fontsize"><?php echo __('Load More Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view4_loadmore_fontsize]"
                                           id="video_ht_view4_loadmore_fontsize"
                                           value="<?php echo get_option('uxgallery_video_ht_view4_loadmore_fontsize'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class=" fixed-size">
                                    <label for="video_ht_view4_loadmore_font_color"><?php echo __('Load More Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view4_loadmore_font_color]"
                                           class="color" id="video_ht_view4_loadmore_font_color"
                                           value="<?php echo get_option('uxgallery_video_ht_view4_loadmore_font_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="video_ht_view4_loadmore_font_color_hover"><?php echo __('Load More Font Hover Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_video_ht_view4_loadmore_font_color_hover]"
                                           class="color" id="video_ht_view4_loadmore_font_color_hover"
                                           value="<?php echo get_option('uxgallery_video_ht_view4_loadmore_font_color_hover'); ?>"
                                           class="text">
                                </div>
                                <div class="fixed-size">
                                    <label for="video_ht_view4_button_color"><?php echo __('Load More Button Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view4_button_color]"
                                           class="color" id="video_ht_view4_button_color"
                                           value="<?php echo get_option('uxgallery_video_ht_view4_button_color'); ?>"
                                           class="text">

                                </div>
                                <div class="fixed-size has-background">
                                    <label for="video_ht_view4_button_color_hover"><?php echo __('Load More Background Hover Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view4_button_color_hover]"
                                           class="color" id="video_ht_view4_button_color_hover"
                                           value="<?php echo get_option('uxgallery_video_ht_view4_button_color_hover'); ?>"
                                           class="text">
                                </div>
                                <div class="navigation-type-block has-height">
                                    <label for=""><?php echo __('Loading Animation', 'gallery-img'); ?></label>

                                    <div class="has-height " >
                                        <div>
                                            <ul id="arrows-type">
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_video_ht_view4_loading_type') == 1) {
                                                    echo "class='active'";
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading1.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_video_ht_view4_loading_type]"
                                                           value="1" <?php if (get_option('uxgallery_video_ht_view4_loading_type') == 1) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_video_ht_view4_loading_type') == 2) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading4.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_video_ht_view4_loading_type]"
                                                           value="2" <?php if (get_option('uxgallery_video_ht_view4_loading_type') == 2) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_video_ht_view4_loading_type') == 3) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading36.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_video_ht_view4_loading_type]"
                                                           value="3" <?php if (get_option('uxgallery_video_ht_view4_loading_type') == 3) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_video_ht_view4_loading_type') == 4) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading51.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_video_ht_view4_loading_type]"
                                                           value="4" <?php if (get_option('uxgallery_video_ht_view4_loading_type') == 4) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Ratings Styles', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_lightbox_rating_count"><?php echo __('Show Ratings Count', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off"
                                           name="params[uxgallery_ht_lightbox_rating_count]"/>
                                    <input type="checkbox"
                                           id="ht_lightbox_rating_count" <?php if (get_option('uxgallery_ht_lightbox_rating_count') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_lightbox_rating_count]" value="on"/>
                                </div>
                                <div class="fixed-size">
                                    <label for="ht_lightbox_likedislike_bg"><?php echo __('Ratings Background Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_lightbox_likedislike_bg]"
                                           class="color" id="ht_lightbox_likedislike_bg"
                                           value="<?php echo get_option('uxgallery_ht_lightbox_likedislike_bg'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background">
                                    <label for="ht_lightbox_likedislike_bg_trans"><?php echo __('Ratings Background Color Opacity', 'gallery-img'); ?></label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_ht_lightbox_likedislike_bg_trans]"
                                               id="ht_lightbox_likedislike_bg_trans" data-slider-highlight="true"
                                               data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text"
                                               data-slider="true"
                                               value="<?php echo get_option('uxgallery_ht_lightbox_likedislike_bg_trans'); ?>"/>
                                        <span><?php echo get_option('uxgallery_ht_lightbox_likedislike_bg_trans'); ?>
                                            %</span>
                                    </div>
                                </div>
                                <div class="fixed-size">
                                    <label for="ht_lightbox_likedislike_font_color"><?php echo __('Ratings Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_lightbox_likedislike_font_color]"
                                           class="color" id="ht_lightbox_likedislike_font_color"
                                           value="<?php echo get_option('uxgallery_ht_lightbox_likedislike_font_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_lightbox_active_font_color"><?php echo __('Ratings Rated Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_lightbox_active_font_color]"
                                           class="color" id="ht_lightbox_active_font_color"
                                           value="<?php echo get_option('uxgallery_ht_lightbox_active_font_color'); ?>"
                                           class="text">

                                </div>

                                <div class="fixed-size">
                                    <label for="ht_lightbox_likedislike_thumb_color"><?php echo __('Like/Dislike Icon Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_lightbox_likedislike_thumb_color]"
                                           class="color" id="ht_lightbox_likedislike_thumb_color"
                                           value="<?php echo get_option('uxgallery_ht_lightbox_likedislike_thumb_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_lightbox_likedislike_thumb_active_color"><?php echo __('Like/Dislike Rated Icon Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_ht_lightbox_likedislike_thumb_active_color]"
                                           class="color" id="ht_lightbox_likedislike_thumb_active_color"
                                           value="<?php echo get_option('uxgallery_ht_lightbox_likedislike_thumb_active_color'); ?>"
                                           class="text">

                                </div>
                                <div class="fixed-size">
                                    <label for="ht_lightbox_heart_likedislike_thumb_color"><?php echo __('Heart Icon Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_ht_lightbox_heart_likedislike_thumb_color]"
                                           class="color" id="ht_lightbox_heart_likedislike_thumb_color"
                                           value="<?php echo get_option('uxgallery_ht_lightbox_heart_likedislike_thumb_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_lightbox_heart_likedislike_thumb_active_color"><?php echo __('Heart Rated Icon Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_ht_lightbox_heart_likedislike_thumb_active_color]"
                                           class="color" id="ht_lightbox_heart_likedislike_thumb_active_color"
                                           value="<?php echo get_option('uxgallery_ht_lightbox_heart_likedislike_thumb_active_color'); ?>"
                                           class="text">

                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Content Styles', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_view6_content_in_center"><?php echo __('Show Content In The Center', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off"
                                           name="params[uxgallery_ht_view6_content_in_center]"/>
                                    <input type="checkbox"
                                           id="ht_view6_content_in_center" <?php if (get_option('uxgallery_ht_view6_content_in_center') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_view6_content_in_center]" value="on"/>
                                </div>

                                <h3><?php echo __('Image', 'gallery-img'); ?></h3>
                                <div class="">
                                    <label for="ht_view6_width"><?php echo __('Image Width', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view6_width]" id="ht_view6_width"
                                           value="<?php echo get_option('uxgallery_ht_view6_width'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div class="has-background">
                                    <label for="ht_view6_height"><?php echo __('Image Height', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view6_height]" id="ht_view6_height"
                                           value="<?php echo get_option('uxgallery_ht_view6_height'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div>
                                    <label for="ht_view6_border_width"><?php echo __('Image Border Width', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view6_border_width]"
                                           id="ht_view6_border_width"
                                           value="<?php echo get_option('uxgallery_ht_view6_border_width'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div class="has-background">
                                    <label for="ht_view6_border_color"><?php echo __('Image Border Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view6_border_color]" type="text" class="color"
                                           id="ht_view6_border_color"
                                           value="#<?php echo get_option('uxgallery_ht_view6_border_color'); ?>"
                                           size="10"/>
                                </div>
                                <div>
                                    <label for="ht_view6_border_radius"><?php echo __('Border Radius', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view6_border_radius]"
                                           id="ht_view6_border_radius"
                                           value="<?php echo get_option('uxgallery_ht_view6_border_radius'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>

                                <div class="has-background">
                                    <label for="uxgallery_album_lightbox_onhover_effects"><?= __('On Hover Effect', 'gallery-img') ?></label>
                                    <select id="uxgallery_album_lightbox_onhover_effects"
                                            name="params[uxgallery_album_lightbox_onhover_effects]">
                                        <option value="0" <?php if (get_option('uxgallery_album_lightbox_onhover_effects') == 0) {
                                            echo "selected='selected'";
                                        } ?>>
                                            dark layer
                                        </option>
                                        <option value="1" <?php if (get_option('uxgallery_album_lightbox_onhover_effects') == 1) {
                                            echo "selected='selected'";
                                        } ?>>
                                            blur
                                        </option>
                                        <option value="2" <?php if (get_option('uxgallery_album_lightbox_onhover_effects') == 2) {
                                            echo "selected='selected'";
                                        } ?>>
                                            image scale
                                        </option>
                                        <option value="3" <?php if (get_option('uxgallery_album_lightbox_onhover_effects') == 3) {
                                            echo "selected='selected'";
                                        } ?>>
                                            content in the bottom
                                        </option>
                                        <option value="4" <?php if (get_option('uxgallery_album_lightbox_onhover_effects') == 4) {
                                            echo "selected='selected'";
                                        } ?>>
                                        </option>

                                    </select>
                                </div>
                                <div class=" for_light_dark_hover">
                                    <label for="uxgallery_album_lightbox_dark_text_color"><?php echo __('Text color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_lightbox_dark_text_color]" type="text"
                                           class="color" id="uxgallery_album_lightbox_dark_text_color"
                                           value="#<?php echo get_option('uxgallery_album_lightbox_dark_text_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background for_light_blur_hover">
                                    <label for="uxgallery_album_lightbox_blur_text_color"><?php echo __('Text color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_lightbox_blur_text_color]" type="text"
                                           class="color" id="uxgallery_album_lightbox_blur_text_color"
                                           value="#<?php echo get_option('uxgallery_album_lightbox_blur_text_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class=" for_light_scale_hover">
                                    <label for="uxgallery_album_lightbox_scale_color"><?php echo __('Scale background color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_lightbox_scale_color]" type="text"
                                           class="color" id="uxgallery_album_lightbox_scale_color"
                                           value="#<?php echo get_option('uxgallery_album_lightbox_scale_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background for_light_scale_hover">
                                    <label for="uxgallery_album_lightbox_scale_opacity"><?php echo __('Scale background opacity(%)', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_lightbox_scale_opacity]" min="0" max="100"
                                           id="uxgallery_album_lightbox_scale_opacity"
                                           value="<?= get_option('uxgallery_album_lightbox_scale_opacity'); ?>"
                                           size="10"
                                           autocomplete="off" type="number">
                                </div>
                                <div class=" for_light_scale_hover">
                                    <label for="uxgallery_album_lightbox_scale_text_color"><?php echo __('Scale text color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_lightbox_scale_text_color]" type="text"
                                           class="color" id="uxgallery_album_lightbox_scale_text_color"
                                           value="#<?php echo get_option('uxgallery_album_lightbox_scale_text_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background for_light_bottom_hover">
                                    <label for="uxgallery_album_lightbox_bottom_color"><?php echo __('Hover place background color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_lightbox_bottom_color]" type="text"
                                           class="color" id="uxgallery_album_lightbox_bottom_color"
                                           value="#<?php echo get_option('uxgallery_album_lightbox_bottom_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class=" for_light_bottom_hover">
                                    <label for="uxgallery_album_lightbox_bottom_text_color"><?php echo __('Hover place text color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_lightbox_bottom_text_color]" type="text"
                                           class="color" id="uxgallery_album_lightbox_bottom_text_color"
                                           value="#<?php echo get_option('uxgallery_album_lightbox_bottom_text_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background for_light_elastic_hover">
                                    <label for="uxgallery_album_lightbox_elastic_text_color"><?php echo __('Hover place text color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_lightbox_elastic_text_color]" type="text"
                                           class="color" id="uxgallery_album_lightbox_elastic_text_color"
                                           value="#<?php echo get_option('uxgallery_album_lightbox_elastic_text_color'); ?>"
                                           size="10"/>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Album options', 'gallery-img'); ?></h3>

                                <div class="has-background">
                                    <label for="uxgallery_album_lightbox_show_image_count_2"><?= __('Show images count', 'gallery-img') ?></label>
                                    <input value="off" name="params[uxgallery_album_lightbox_show_image_count_2]"
                                           type="hidden">
                                    <input id="uxgallery_album_lightbox_show_image_count_2"
                                           name="params[uxgallery_album_lightbox_show_image_count_2]"
                                           value="on" <?php if (get_option("uxgallery_album_lightbox_show_image_count_2") == "on") {
                                        echo "checked='checked'";
                                    } ?> size="10" type="checkbox">
                                </div>
                                <div class="has-height">
                                    <label for="params[uxgallery_album_lightbox_count_style]"><?= __('Images Count Style', 'gallery-img') ?></label>
                                    <ul id="arrows-type">
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_lightbox_count_style') == 0) {
                                            echo "class='active'";
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/count/count-0.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_lightbox_count_style]"
                                                   value="0" <?php if (get_option('uxgallery_album_lightbox_count_style') == 0) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_lightbox_count_style') == 1) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/count/count-1.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_lightbox_count_style]"
                                                   value="1" <?php if (get_option('uxgallery_album_lightbox_count_style') == 1) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_lightbox_count_style') == 2) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/count/count-2.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_lightbox_count_style]"
                                                   value="2" <?php if (get_option('uxgallery_album_lightbox_count_style') == 2) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_lightbox_count_style') == 3) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/count/count-3.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_lightbox_count_style]"
                                                   value="3" <?php if (get_option('uxgallery_album_lightbox_count_style') == 3) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_lightbox_count_style') == 4) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/count/count-4.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_lightbox_count_style]"
                                                   value="4" <?php if (get_option('uxgallery_album_lightbox_count_style') == 4) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                    </ul>
                                </div>

                                <div class="has-height">
                                    <label for=""><?= __('Category Buttons Style', 'gallery-img') ?></label>

                                    <ul id="arrows-type" style="">
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_lightbox_category_style') == 0) {
                                            echo "class='active'";
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/0.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_lightbox_category_style]"
                                                   value="0" <?php if (get_option('uxgallery_album_lightbox_category_style') == 0) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_lightbox_category_style') == 1) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/1.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_lightbox_category_style]"
                                                   value="1" <?php if (get_option('uxgallery_album_lightbox_category_style') == 1) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_lightbox_category_style') == 2) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/2.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_lightbox_category_style]"
                                                   value="2" <?php if (get_option('uxgallery_album_lightbox_category_style') == 2) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_lightbox_category_style') == 3) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/3.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_lightbox_category_style]"
                                                   value="3" <?php if (get_option('uxgallery_album_lightbox_category_style') == 3) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_lightbox_category_style') == 4) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/4.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_lightbox_category_style]"
                                                   value="4" <?php if (get_option('uxgallery_album_lightbox_category_style') == 4) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_lightbox_category_style') == 5) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/5.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_lightbox_category_style]"
                                                   value="5" <?php if (get_option('uxgallery_album_lightbox_category_style') == 5) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_lightbox_category_style') == 6) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/6.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_lightbox_category_style]"
                                                   value="6" <?php if (get_option('uxgallery_album_lightbox_category_style') == 6) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                    </ul>
                                </div>
                                <div class="has-background">
                                    <label for="uxgallery_album_lightbox_show_description"><?= __('Show Description', 'gallery-img') ?></label>
                                    <input value="off" name="params[uxgallery_album_lightbox_show_description]"
                                           type="hidden">
                                    <input id="uxgallery_album_lightbox_show_description"
                                           name="params[uxgallery_album_lightbox_show_description]"
                                           value="on" <?php if (get_option("uxgallery_album_lightbox_show_description") == "on") {
                                        echo "checked='checked'";
                                    } ?> size="10" type="checkbox">
                                </div>


                            </div>
                        </li>

                        <li class="gallery-view-options-3">
                            <span class="content_heading">Slideshow</span>
                            <div class="options-block" id="options-block-slider">
                                <h3><?php echo __('Slider', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="slider_crop_image"><?php echo __('Image Behavior', 'gallery-img'); ?></label>
                                    <select id="slider_crop_image" name="params[uxgallery_slider_crop_image]">
                                        <option <?php if (get_option('uxgallery_slider_crop_image') == 'crop') {
                                            echo 'selected';
                                        } ?> value="crop"><?php echo __('Natural', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_slider_crop_image') == 'resize') {
                                            echo 'selected';
                                        } ?> value="resize"><?php echo __('Resize', 'gallery-img'); ?></option>
                                    </select>
                                </div>
                                <div>
                                    <label for="slider_slider_background_color"><?php echo __('Slider Background Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_slider_slider_background_color]" type="text"
                                           class="color" id="slider_slider_background_color"
                                           value="#<?php echo get_option('uxgallery_slider_slider_background_color'); ?>"
                                           size="10">
                                </div>
                                <div class="has-background">
                                    <label for="slider_slideshow_border_size"><?php echo __('Slider Border Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_slider_slideshow_border_size]"
                                           id="slider_slideshow_border_size"
                                           value="<?php echo get_option('uxgallery_slider_slideshow_border_size'); ?>"
                                           class="text"/>
                                </div>
                                <div>
                                    <label for="slider_slideshow_border_color"><?php echo __('Slider Border Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_slider_slideshow_border_color]" type="text"
                                           class="color" id="slider_slideshow_border_color"
                                           value="#<?php echo get_option('uxgallery_slider_slideshow_border_color'); ?>"
                                           size="10">
                                </div>
                                <div class="has-background">
                                    <label for="slider_slideshow_border_radius"><?php echo __('Slider Border radius', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_slider_slideshow_border_radius]"
                                           id="slider_slideshow_border_radius"
                                           value="<?php echo get_option('uxgallery_slider_slideshow_border_radius'); ?>"
                                           class="text"/>
                                </div>
                            </div>
                            <div class="options-block" id="options-block-title">
                                <h3><?php echo __('Title', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="title-container-width"><?php echo __('Title Width', 'gallery-img'); ?></label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_slider_title_width]" id="title-container-width"
                                               data-slider-range="1,100" type="text" data-slider="true"
                                               data-slider-highlight="true"
                                               value="<?php echo get_option('uxgallery_slider_title_width'); ?>"/>
                                        <span><?php echo get_option('uxgallery_slider_title_width'); ?>%</span>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div>
                                    <label for="slider_title_has_margin"><?php echo __('Title Has Margin', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off"
                                           name="params[uxgallery_slider_title_has_margin]"/>
                                    <input type="checkbox"
                                           id="slider_title_has_margin" <?php if (get_option('uxgallery_slider_title_has_margin') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_slider_title_has_margin]" value="on"/>
                                </div>
                                <div class="has-background">
                                    <label for="slider_title_font_size"><?php echo __('Title Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_slider_title_font_size]"
                                           id="slider_title_font_size"
                                           value="<?php echo get_option('uxgallery_slider_title_font_size'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div>
                                    <label for="slider_title_color"><?php echo __('Title Text Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_slider_title_color]" type="text" class="color"
                                           id="slider_title_color"
                                           value="#<?php echo get_option('uxgallery_slider_title_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background">
                                    <label for="slider_title_text_align"><?php echo __('Title Text Align', 'gallery-img'); ?></label>
                                    <select id="slider_title_text_align"
                                            name="params[uxgallery_slider_title_text_align]">
                                        <option <?php if (get_option('uxgallery_slider_title_text_align') == 'justify') {
                                            echo 'justify';
                                        } ?> value="justify"><?php echo __('Full width', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_slider_title_text_align') == 'center') {
                                            echo 'selected';
                                        } ?> value="center"><?php echo __('Center', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_slider_title_text_align') == 'left') {
                                            echo 'selected';
                                        } ?> value="left"><?php echo __('Left', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_slider_title_text_align') == 'right') {
                                            echo 'selected';
                                        } ?> value="right"><?php echo __('Right', 'gallery-img'); ?></option>
                                    </select>
                                </div>
                                <div>
                                    <label for="title-background-transparency"><?php echo __('Title Background Opacity', 'gallery-img'); ?></label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_slider_title_background_transparency]"
                                               id="title-background-transparency" data-slider-highlight="true"
                                               data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text"
                                               data-slider="true"
                                               value="<?php echo get_option('uxgallery_slider_title_background_transparency'); ?>"/>
                                        <span><?php echo get_option('uxgallery_slider_title_background_transparency'); ?>
                                            %</span>
                                    </div>
                                </div>
                                <div class="has-background">
                                    <label for="slider_title_background_color"><?php echo __('Title Background Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_slider_title_background_color]" type="text"
                                           class="color" id="slider_title_background_color"
                                           value="#<?php echo get_option('uxgallery_slider_title_background_color'); ?>"
                                           size="10"/>
                                </div>
                                <div>
                                    <label for="slider_title_border_size"><?php echo __('Title Border Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_slider_title_border_size]"
                                           id="slider_title_border_size"
                                           value="<?php echo get_option('uxgallery_slider_title_border_size'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div class="has-background">
                                    <label for="slider_title_border_color"><?php echo __('Title Border Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_slider_title_border_color]" type="text"
                                           class="color" id="slider_title_border_color"
                                           value="#<?php echo get_option('uxgallery_slider_title_border_color'); ?>"
                                           size="10">
                                </div>
                                <div>
                                    <label for="slider_title_border_radius"><?php echo __('Title Border Radius', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_slider_title_border_radius]"
                                           id="slider_title_border_radius"
                                           value="<?php echo get_option('uxgallery_slider_title_border_radius'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div class="has-height has-background">
                                    <label for=""><?php echo __('Title Position', 'gallery-img'); ?></label>
                                    <div>
                                        <table class="bws_position_table">
                                            <tbody>
                                            <tr>
                                                <td><input type="radio" value="left-top" id="slideshow_title_top-left"
                                                           name="params[uxgallery_slider_title_position]" <?php if (get_option('uxgallery_slider_title_position') == 'left-top') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                                <td><input type="radio" value="center-top"
                                                           id="slideshow_title_top-center"
                                                           name="params[uxgallery_slider_title_position]" <?php if (get_option('uxgallery_slider_title_position') == 'center-top') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                                <td><input type="radio" value="right-top" id="slideshow_title_top-right"
                                                           name="params[uxgallery_slider_title_position]" <?php if (get_option('uxgallery_slider_title_position') == 'right-top') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                            </tr>
                                            <tr>
                                                <td><input type="radio" value="left-middle"
                                                           id="slideshow_title_middle-left"
                                                           name="params[uxgallery_slider_title_position]" <?php if (get_option('uxgallery_slider_title_position') == 'left-middle') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                                <td><input type="radio" value="center-middle"
                                                           id="slideshow_title_middle-center"
                                                           name="params[uxgallery_slider_title_position]" <?php if (get_option('uxgallery_slider_title_position') == 'center-middle') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                                <td><input type="radio" value="right-middle"
                                                           id="slideshow_title_middle-right"
                                                           name="params[uxgallery_slider_title_position]" <?php if (get_option('uxgallery_slider_title_position') == 'right-middle') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                            </tr>
                                            <tr>
                                                <td><input type="radio" value="left-bottom"
                                                           id="slideshow_title_bottom-left"
                                                           name="params[uxgallery_slider_title_position]" <?php if (get_option('uxgallery_slider_title_position') == 'left-bottom') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                                <td><input type="radio" value="center-bottom"
                                                           id="slideshow_title_bottom-center"
                                                           name="params[uxgallery_slider_title_position]" <?php if (get_option('uxgallery_slider_title_position') == 'center-bottom') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                                <td><input type="radio" value="right-bottom"
                                                           id="slideshow_title_bottom-right"
                                                           name="params[uxgallery_slider_title_position]" <?php if (get_option('uxgallery_slider_title_position') == 'right-bottom') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="options-block">
                                <h3><?php echo __('Description', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="description-container-width"><?php echo __('Description Width', 'gallery-img'); ?></label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_slider_description_width]"
                                               id="description-container-width" data-slider-range="1,100" type="text"
                                               data-slider="true" data-slider-highlight="true"
                                               value="<?php echo get_option('uxgallery_slider_description_width'); ?>"/>
                                        <span><?php echo get_option('uxgallery_slider_description_width'); ?>%</span>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div>
                                    <label for="slider_description_has_margin"><?php echo __('Description Has Margin', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off"
                                           name="params[uxgallery_slider_description_has_margin]"/>
                                    <input type="checkbox"
                                           id="slider_description_has_margin" <?php if (get_option('uxgallery_slider_description_has_margin') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_slider_description_has_margin]" value="on"/>
                                </div>
                                <div class="has-background">
                                    <label for="slider_description_font_size"><?php echo __('Description Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_slider_description_font_size]"
                                           id="slider_description_font_size"
                                           value="<?php echo get_option('uxgallery_slider_description_font_size'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div>
                                    <label for="slider_description_color"><?php echo __('Description Text Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_slider_description_color]" type="text" class="color"
                                           id="slider_description_color"
                                           value="#<?php echo get_option('uxgallery_slider_description_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background">
                                    <label for="slider_description_text_align"><?php echo __('Description Text Align', 'gallery-img'); ?></label>
                                    <select id="slider_description_text_align"
                                            name="params[uxgallery_slider_description_text_align]">
                                        <option <?php if (get_option('uxgallery_slider_description_text_align') == 'justify') {
                                            echo 'selected';
                                        } ?> value="justify"><?php echo __('Full width', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_slider_description_text_align') == 'center') {
                                            echo 'selected';
                                        } ?> value="center"><?php echo __('Center', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_slider_description_text_align') == 'left') {
                                            echo 'selected';
                                        } ?> value="left"><?php echo __('Left', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_slider_description_text_align') == 'right') {
                                            echo 'selected';
                                        } ?> value="right"><?php echo __('Right', 'gallery-img'); ?></option>
                                    </select>
                                </div>
                                <div>
                                    <label for="description-background-transparency"><?php echo __('Description Background Opacity', 'gallery-img'); ?></label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_slider_description_background_transparency]"
                                               id="description-background-transparency" data-slider-highlight="true"
                                               data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text"
                                               data-slider="true"
                                               value="<?php echo get_option('uxgallery_slider_description_background_transparency'); ?>"/>
                                        <span><?php echo get_option('uxgallery_slider_description_background_transparency'); ?>
                                            %</span>
                                    </div>
                                </div>
                                <div class="has-background">
                                    <label for="slider_description_background_color"><?php echo __('Description Background Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_slider_description_background_color]" type="text"
                                           class="color" id="slider_description_background_color"
                                           value="#<?php echo get_option('uxgallery_slider_description_background_color'); ?>"
                                           size="10">
                                </div>
                                <div>
                                    <label for="slider_description_border_size"><?php echo __('Description Border Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_slider_description_border_size]"
                                           id="slider_description_border_size"
                                           value="<?php echo get_option('uxgallery_slider_description_border_size'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div class="has-background">
                                    <label for="slider_description_border_color"><?php echo __('Description Border Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_slider_description_border_color]" type="text"
                                           class="color" id="slider_description_border_color"
                                           value="#<?php echo get_option('uxgallery_slider_description_border_color'); ?>"
                                           size="10">
                                </div>
                                <div>
                                    <label for="slider_description_border_radius"><?php echo __('Description Border Radius', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_slider_description_border_radius]"
                                           id="slider_description_border_radius"
                                           value="<?php echo get_option('uxgallery_slider_description_border_radius'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div class="has-height has-background">
                                    <label for="params[uxgallery_slider_description_position]"><?php echo __('Description Position', 'gallery-img'); ?></label>
                                    <div>
                                        <table class="bws_position_table">
                                            <tbody>
                                            <tr>
                                                <td><input type="radio" value="left-top"
                                                           id="slideshow_description_top-left"
                                                           name="params[uxgallery_slider_description_position]" <?php if (get_option('uxgallery_slider_description_position') == 'left-top') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                                <td><input type="radio" value="center-top"
                                                           id="slideshow_description_top-center"
                                                           name="params[uxgallery_slider_description_position]" <?php if (get_option('uxgallery_slider_description_position') == 'center-top') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                                <td><input type="radio" value="right-top"
                                                           id="slideshow_description_top-right"
                                                           name="params[uxgallery_slider_description_position]" <?php if (get_option('uxgallery_slider_description_position') == 'right-top') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                            </tr>
                                            <tr>
                                                <td><input type="radio" value="left-middle"
                                                           id="slideshow_description_middle-left"
                                                           name="params[uxgallery_slider_description_position]" <?php if (get_option('uxgallery_slider_description_position') == 'left-middle') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                                <td><input type="radio" value="center-middle"
                                                           id="slideshow_description_middle-center"
                                                           name="params[uxgallery_slider_description_position]" <?php if (get_option('uxgallery_slider_description_position') == 'center-middle') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                                <td><input type="radio" value="right-middle"
                                                           id="slideshow_description_middle-right"
                                                           name="params[uxgallery_slider_description_position]" <?php if (get_option('uxgallery_slider_description_position') == 'right-middle') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                            </tr>
                                            <tr>
                                                <td><input type="radio" value="left-bottom"
                                                           id="slideshow_description_bottom-left"
                                                           name="params[uxgallery_slider_description_position]" <?php if (get_option('uxgallery_slider_description_position') == 'left-bottom') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                                <td><input type="radio" value="center-bottom"
                                                           id="slideshow_description_bottom-center"
                                                           name="params[uxgallery_slider_description_position]" <?php if (get_option('uxgallery_slider_description_position') == 'center-bottom') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                                <td><input type="radio" value="right-bottom"
                                                           id="slideshow_description_bottom-right"
                                                           name="params[uxgallery_slider_description_position]" <?php if (get_option('uxgallery_slider_description_position') == 'right-bottom') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="options-block" id="options-block-navigation">
                                <h3><?php echo __('Navigation', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="slider_show_arrows"><?php echo __('Show Navigation Arrows', 'gallery-img'); ?> </label>
                                    <input type="hidden" value="off" name="params[uxgallery_slider_show_arrows]"/>
                                    <input type="checkbox"
                                           id="slider_show_arrows" <?php if (get_option('uxgallery_slider_show_arrows') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_slider_show_arrows]" value="on"/>
                                </div>
                                <div>
                                    <label for="slider_dots_position"><?php echo __('Navigation Dots Position / Hide Dots', 'gallery-img'); ?></label>
                                    <select id="slider_dots_position" name="params[uxgallery_slider_dots_position]">
                                        <option <?php if (get_option('uxgallery_slider_dots_position') == 'none') {
                                            echo 'selected';
                                        } ?> value="none"><?php echo __('', 'gallery-img'); ?>Dont Show
                                        </option>
                                        <option <?php if (get_option('uxgallery_slider_dots_position') == 'top') {
                                            echo 'selected';
                                        } ?> value="top"><?php echo __('', 'gallery-img'); ?>Top
                                        </option>
                                        <option <?php if (get_option('uxgallery_slider_dots_position') == 'bottom') {
                                            echo 'selected';
                                        } ?> value="bottom"><?php echo __('', 'gallery-img'); ?>Bottom
                                        </option>
                                    </select>
                                </div>
                                <div class="has-background">
                                    <label for="slider_dots_color"><?php echo __('Navigation Dots Color', 'gallery-img'); ?></label>
                                    <input type="text" class="color" name="params[uxgallery_slider_dots_color]"
                                           id="slider_dots_color"
                                           value="<?php echo get_option('uxgallery_slider_dots_color'); ?>"
                                           class="text"/>
                                </div>
                                <div>
                                    <label for="slider_active_dot_color"><?php echo __('Navigation Active Dot Color', 'gallery-img'); ?></label>
                                    <input type="text" class="color" name="params[uxgallery_slider_active_dot_color]"
                                           id="slider_active_dot_color"
                                           value="<?php echo get_option('uxgallery_slider_active_dot_color'); ?>"
                                           class="text"/>
                                </div>
                                <div class="navigation-type-block has-height has-background">
                                    <label for=""><?php echo __('Navigation Type', 'gallery-img'); ?></label>

                                    <div class="has-height has-background">
                                        <div>
                                            <ul id="arrows-type">
                                                <li <?php if (get_option('uxgallery_slider_navigation_type') == 1) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/arrows.simple.png"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_slider_navigation_type]"
                                                           value="1" <?php if (get_option('uxgallery_slider_navigation_type') == 1) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li <?php if (get_option('uxgallery_slider_navigation_type') == 2) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/arrows.circle.shadow.png"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_slider_navigation_type]"
                                                           value="2" <?php if (get_option('uxgallery_slider_navigation_type') == 2) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li <?php if (get_option('uxgallery_slider_navigation_type') == 3) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/arrows.circle.simple.dark.png"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_slider_navigation_type]"
                                                           value="3" <?php if (get_option('uxgallery_slider_navigation_type') == 3) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>

                                                <li <?php if (get_option('uxgallery_slider_navigation_type') == 4) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/arrows.cube.dark.png"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_slider_navigation_type]"
                                                           value="4" <?php if (get_option('uxgallery_slider_navigation_type') == 4) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li <?php if (get_option('uxgallery_slider_navigation_type') == 5) {
                                                    echo 'class="active"';
                                                } ?> >
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/arrows.light.blue.png"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_slider_navigation_type]"
                                                           value="5" <?php if (get_option('uxgallery_slider_navigation_type') == 5) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li <?php if (get_option('uxgallery_slider_navigation_type') == 6) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/arrows.light.cube.png"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_slider_navigation_type]"
                                                           value="6" <?php if (get_option('uxgallery_slider_navigation_type') == 6) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li <?php if (get_option('uxgallery_slider_navigation_type') == 8) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/arrows.png" alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_slider_navigation_type]"
                                                           value="8" <?php if (get_option('uxgallery_slider_navigation_type') == 8) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li <?php if (get_option('uxgallery_slider_navigation_type') == 9) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/arrows.circle.blue.png"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_slider_navigation_type]"
                                                           value="9" <?php if (get_option('uxgallery_slider_navigation_type') == 9) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li <?php if (get_option('uxgallery_slider_navigation_type') == 10) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/arrows.circle.green.png"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_slider_navigation_type]"
                                                           value="10" <?php if (get_option('uxgallery_slider_navigation_type') == 10) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li <?php if (get_option('uxgallery_slider_navigation_type') == 11) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/arrows.blue.retro.png"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_slider_navigation_type]"
                                                           value="11" <?php if (get_option('uxgallery_slider_navigation_type') == 11) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li <?php if (get_option('uxgallery_slider_navigation_type') == 12) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/arrows.green.retro.png"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_slider_navigation_type]"
                                                           value="12" <?php if (get_option('uxgallery_slider_navigation_type') == 12) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li <?php if (get_option('uxgallery_slider_navigation_type') == 13) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/arrows.red.circle.png"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_slider_navigation_type]"
                                                           value="13" <?php if (get_option('uxgallery_slider_navigation_type') == 13) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li class="color" <?php if (get_option('uxgallery_slider_navigation_type') == 14) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/arrows.triangle.white.png"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_slider_navigation_type]"
                                                           value="14" <?php if (get_option('uxgallery_slider_navigation_type') == 14) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li <?php if (get_option('uxgallery_slider_navigation_type') == 15) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/arrows.ancient.png"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_slider_navigation_type]"
                                                           value="15" <?php if (get_option('uxgallery_slider_navigation_type') == 15) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li <?php if (get_option('uxgallery_slider_navigation_type') == 16) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/arrows.black.out.png"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_slider_navigation_type]"
                                                           value="16" <?php if (get_option('uxgallery_slider_navigation_type') == 16) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Ratings Styles', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_slider_rating_count"><?php echo __('Show Ratings Count', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off" name="params[uxgallery_ht_slider_rating_count]"/>
                                    <input type="checkbox"
                                           id="ht_slider_rating_count" <?php if (get_option('uxgallery_ht_slider_rating_count') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_slider_rating_count]" value="on"/>
                                </div>
                                <div class="fixed-size">
                                    <label for="ht_slider_likedislike_bg"><?php echo __('Ratings Background Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_slider_likedislike_bg]" class="color"
                                           id="ht_slider_likedislike_bg"
                                           value="<?php echo get_option('uxgallery_ht_slider_likedislike_bg'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background">
                                    <label for="ht_slider_likedislike_bg_trans"><?php echo __('Ratings Background Color Opacity', 'gallery-img'); ?></label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_ht_slider_likedislike_bg_trans]"
                                               id="ht_slider_likedislike_bg_trans" data-slider-highlight="true"
                                               data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text"
                                               data-slider="true"
                                               value="<?php echo get_option('uxgallery_ht_slider_likedislike_bg_trans'); ?>"/>
                                        <span><?php echo get_option('uxgallery_ht_slider_likedislike_bg_trans'); ?>
                                            %</span>
                                    </div>
                                </div>
                                <div class="fixed-size">
                                    <label for="ht_slider_likedislike_font_color"><?php echo __('Ratings Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_slider_likedislike_font_color]"
                                           class="color" id="ht_slider_likedislike_font_color"
                                           value="<?php echo get_option('uxgallery_ht_slider_likedislike_font_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_slider_active_font_color"><?php echo __('Ratings Rated Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_slider_active_font_color]"
                                           class="color" id="ht_slider_active_font_color"
                                           value="<?php echo get_option('uxgallery_ht_slider_active_font_color'); ?>"
                                           class="text">

                                </div>

                                <div class="fixed-size">
                                    <label for="ht_slider_likedislike_thumb_color"><?php echo __('Like/Dislike Icon Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_slider_likedislike_thumb_color]"
                                           class="color" id="ht_slider_likedislike_thumb_color"
                                           value="<?php echo get_option('uxgallery_ht_slider_likedislike_thumb_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_slider_likedislike_thumb_active_color"><?php echo __('Like/Dislike Rated Icon Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_ht_slider_likedislike_thumb_active_color]"
                                           class="color" id="ht_slider_likedislike_thumb_active_color"
                                           value="<?php echo get_option('uxgallery_ht_slider_likedislike_thumb_active_color'); ?>"
                                           class="text">

                                </div>
                                <div class="fixed-size">
                                    <label for="ht_slider_heart_likedislike_thumb_color"><?php echo __('Heart Icon Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_ht_slider_heart_likedislike_thumb_color]"
                                           class="color" id="ht_slider_heart_likedislike_thumb_color"
                                           value="<?php echo get_option('uxgallery_ht_slider_heart_likedislike_thumb_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_slider_heart_likedislike_thumb_active_color"><?php echo __('Heart Rated Icon Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_ht_slider_heart_likedislike_thumb_active_color]"
                                           class="color" id="ht_slider_heart_likedislike_thumb_active_color"
                                           value="<?php echo get_option('uxgallery_ht_slider_heart_likedislike_thumb_active_color'); ?>"
                                           class="text">

                                </div>
                            </div>
                        </li>

                        <li class="gallery-view-options-4">
                            <span class="content_heading">Lightbox Grid</span>
                            <div>
                                <h3><?php echo __('Container Style', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="thumb_box_padding"><?php echo __('Box padding', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_thumb_box_padding]"
                                           id="thumb_box_padding"
                                           value="<?php echo get_option('uxgallery_thumb_box_padding'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div>
                                    <label for="thumb_box_background"><?php echo __('Box background', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_thumb_box_background]" type="text" class="color"
                                           id="thumb_box_background"
                                           value="#<?php echo get_option('uxgallery_thumb_box_background'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background">
                                    <label for="thumb_box_use_shadow"><?php echo __('Box Use shadow', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off" name="params[uxgallery_thumb_box_use_shadow]"/>
                                    <input type="checkbox"
                                           id="thumb_box_use_shadow" <?php if (get_option('uxgallery_thumb_box_use_shadow') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_thumb_box_use_shadow]" value="on"/>
                                </div>
                                <div>
                                    <label for="thumb_box_has_background"><?php echo __('Box Has background', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off"
                                           name="params[uxgallery_thumb_box_has_background]"/>
                                    <input type="checkbox"
                                           id="thumb_box_has_background" <?php if (get_option('uxgallery_thumb_box_has_background') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_thumb_box_has_background]" value="on"/>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Image', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="image_natural_size_thumbnail"><?php echo __('Image Behavior', 'gallery-img'); ?></label>
                                    <select id="image_natural_size_thumbnail"
                                            name="params[uxgallery_image_natural_size_thumbnail]">
                                        <option <?php if (get_option('uxgallery_image_natural_size_thumbnail') == 'resize') {
                                            echo 'selected="selected"';
                                        } ?> value="resize">Resize
                                        </option>
                                        <option <?php if (get_option('uxgallery_image_natural_size_thumbnail') == 'natural') {
                                            echo 'selected="selected"';
                                        } ?> value="natural">Natural
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label for="thumb_image_width"><?php echo __('Image Width', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_thumb_image_width]"
                                           id="thumb_image_width"
                                           value="<?php echo get_option('uxgallery_thumb_image_width'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div class="has-background">
                                    <label for="thumb_image_height"><?php echo __('Image Height', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_thumb_image_height]"
                                           id="thumb_image_height"
                                           value="<?php echo get_option('uxgallery_thumb_image_height'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div>
                                    <label for="thumb_image_border_width"><?php echo __('Image Border Width', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_thumb_image_border_width]"
                                           id="thumb_image_border_width"
                                           value="<?php echo get_option('uxgallery_thumb_image_border_width'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div class="has-background">
                                    <label for="thumb_image_border_color"><?php echo __('Image Border Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_thumb_image_border_color]" type="text" class="color"
                                           id="thumb_image_border_color"
                                           value="#<?php echo get_option('uxgallery_thumb_image_border_color'); ?>"
                                           size="10"/>
                                </div>
                                <div>
                                    <label for="thumb_image_border_radius"><?php echo __('Border Radius', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_thumb_image_border_radius]"
                                           id="thumb_image_border_radius"
                                           value="<?php echo get_option('uxgallery_thumb_image_border_radius'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div class="has-background">
                                    <label for="thumb_margin_image"><?php echo __('Margin Image', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_thumb_margin_image]"
                                           id="thumb_margin_image"
                                           value="<?php echo get_option('uxgallery_thumb_margin_image'); ?>"
                                           class="text"/>
                                </div>
                                <div class="has-background">
                                    <label for="uxgallery_album_thumbnail_onhover_effects"><?= __('On Hover Effect', 'gallery-img') ?></label>
                                    <select id="uxgallery_album_thumbnail_onhover_effects"
                                            name="params[uxgallery_album_thumbnail_onhover_effects]">
                                        <option value="0" <?php if (get_option('uxgallery_album_thumbnail_onhover_effects') == 0) {
                                            echo "selected='selected'";
                                        } ?>>
                                            dark layer
                                        </option>
                                        <option value="1" <?php if (get_option('uxgallery_album_thumbnail_onhover_effects') == 1) {
                                            echo "selected='selected'";
                                        } ?>>
                                            blur
                                        </option>
                                        <option value="2" <?php if (get_option('uxgallery_album_thumbnail_onhover_effects') == 2) {
                                            echo "selected='selected'";
                                        } ?>>
                                            image scale
                                        </option>
                                        <option value="3" <?php if (get_option('uxgallery_album_thumbnail_onhover_effects') == 3) {
                                            echo "selected='selected'";
                                        } ?>>
                                            content in the bottom
                                        </option>
                                        <option value="4" <?php if (get_option('uxgallery_album_thumbnail_onhover_effects') == 4) {
                                            echo "selected='selected'";
                                        } ?>>
                                            elastic
                                        </option>

                                    </select>
                                </div>
                                <div class=" for_thumbnail_dark_hover">
                                    <label for="uxgallery_album_thumbnail_dark_text_color"><?php echo __('Text color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_thumbnail_dark_text_color]" type="text"
                                           class="color" id="uxgallery_album_thumbnail_dark_text_color"
                                           value="#<?php echo get_option('uxgallery_album_thumbnail_dark_text_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background for_thumbnail_blur_hover">
                                    <label for="uxgallery_album_thumbnail_blur_text_color"><?php echo __('Text color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_thumbnail_blur_text_color]" type="text"
                                           class="color" id="uxgallery_album_thumbnail_blur_text_color"
                                           value="#<?php echo get_option('uxgallery_album_thumbnail_blur_text_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class=" for_thumbnail_scale_hover">
                                    <label for="uxgallery_album_thumbnail_scale_color"><?php echo __('Scale background color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_thumbnail_scale_color]" type="text"
                                           class="color" id="uxgallery_album_thumbnail_scale_color"
                                           value="#<?php echo get_option('uxgallery_album_thumbnail_scale_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background for_thumbnail_scale_hover">
                                    <label for="uxgallery_album_thumbnail_scale_opacity"><?php echo __('Scale background opacity(%)', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_thumbnail_scale_opacity]" min="0" max="100"
                                           id="uxgallery_album_thumbnail_scale_opacity"
                                           value="<?= (get_option('uxgallery_album_thumbnail_scale_opacity')) ? get_option('uxgallery_album_thumbnail_scale_opacity') : 27; ?>"
                                           size="10"
                                           autocomplete="off" type="number">
                                </div>
                                <div class=" for_thumbnail_scale_hover">
                                    <label for="uxgallery_album_thumbnail_scale_text_color"><?php echo __('Scale text color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_thumbnail_scale_text_color]" type="text"
                                           class="color" id="uxgallery_album_thumbnail_scale_text_color"
                                           value="#<?php echo get_option('uxgallery_album_thumbnail_scale_text_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background for_thumbnail_bottom_hover">
                                    <label for="uxgallery_album_thumbnail_bottom_color"><?php echo __('Hover place background color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_thumbnail_bottom_color]" type="text"
                                           class="color" id="uxgallery_album_thumbnail_bottom_color"
                                           value="#<?php echo get_option('uxgallery_album_thumbnail_bottom_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class=" for_thumbnail_bottom_hover">
                                    <label for="uxgallery_album_thumbnail_bottom_text_color"><?php echo __('Hover place text color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_thumbnail_bottom_text_color]" type="text"
                                           class="color" id="uxgallery_album_thumbnail_bottom_text_color"
                                           value="#<?php echo get_option('uxgallery_album_thumbnail_bottom_text_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background for_thumbnail_elastic_hover">
                                    <label for="uxgallery_album_thumbnail_elastic_text_color"><?php echo __('Hover place text color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_album_thumbnail_elastic_text_color]" type="text"
                                           class="color" id="uxgallery_album_thumbnail_elastic_text_color"
                                           value="#<?php echo get_option('uxgallery_album_thumbnail_elastic_text_color'); ?>"
                                           size="10"/>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Title', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="thumb_title_font_size"><?php echo __('Title Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_thumb_title_font_size]"
                                           id="thumb_title_font_size"
                                           value="<?php echo get_option('uxgallery_thumb_title_font_size'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <!--<div>
                                    <label for="thumb_title_font_color"><?php echo __('Title Font Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_thumb_title_font_color]" type="text" class="color"
                                           id="thumb_title_font_color"
                                           value="#<?php echo get_option('uxgallery_thumb_title_font_color'); ?>"
                                           size="10"/>
                                </div>
                                <div class="has-background">
                                    <label for="thumb_title_background_color"><?php echo __('Overlay Background Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_thumb_title_background_color]" type="text"
                                           class="color" id="thumb_title_background_color"
                                           value="#<?php echo get_option('uxgallery_thumb_title_background_color'); ?>"
                                           size="10"/>
                                </div>
                                <div>
                                    <label for="thumb_title_background_transparency"><?php echo __('Title Background Opacity', 'gallery-img'); ?></label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_thumb_title_background_transparency]"
                                               id="thumb_title_background_transparency" data-slider-highlight="true"
                                               data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text"
                                               data-slider="true"
                                               value="<?php echo get_option('uxgallery_thumb_title_background_transparency'); ?>"/>
                                        <span><?php echo get_option('uxgallery_thumb_title_background_transparency'); ?>
                                            %</span>
                                    </div>
                                </div>
                                <div class="has-background">
                                    <label for="thumb_view_text"><?php echo __('Link Text', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_thumb_view_text]" type="text" id="thumb_view_text"
                                           value="<?php echo get_option('uxgallery_thumb_view_text'); ?>"/>
                                </div>  -->
                                <div class="">
                                    <label for="uxgallery_album_thumbnail_show_title"><?= __('Show Title', 'gallery-img') ?> </label>
                                    <input value="off" name="params[uxgallery_album_thumbnail_show_title]"
                                           type="hidden">
                                    <input id="uxgallery_album_thumbnail_show_title" <?php if (get_option('uxgallery_album_thumbnail_show_title') == 'on') echo "checked='checked'" ?>
                                           name="params[uxgallery_album_thumbnail_show_title]" value="on"
                                           type="checkbox">
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Pagination Styles', 'gallery-img'); ?></h3>
                                <div class="fixed-size has-background">
                                    <label for="video_ht_view7_paginator_fontsize"><?php echo __('Pagination Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view7_paginator_fontsize]"
                                           id="video_ht_view7_paginator_fontsize"
                                           value="<?php echo get_option('uxgallery_video_ht_view7_paginator_fontsize'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class="  fixed-size">
                                    <label for="video_ht_view7_paginator_color"><?php echo __('Pagination Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view7_paginator_color]"
                                           class="color" id="video_ht_view7_paginator_color"
                                           value="<?php echo get_option('uxgallery_video_ht_view7_paginator_color'); ?>"
                                           class="text">

                                </div>
                                <div class="fixed-size has-background">
                                    <label for="video_ht_view7_paginator_icon_size"><?php echo __('Pagination Icons Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view7_paginator_icon_size]"
                                           id="video_ht_view7_paginator_icon_size"
                                           value="<?php echo get_option('uxgallery_video_ht_view7_paginator_icon_size'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class=" fixed-size">
                                    <label for="video_ht_view7_paginator_icon_color"><?php echo __('Pagination Icons Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view7_paginator_icon_color]"
                                           class="color" id="video_ht_view7_paginator_icon_color"
                                           value="<?php echo get_option('uxgallery_video_ht_view7_paginator_icon_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background">
                                    <label for="video_ht_view7_paginator_position"><?php echo __('Pagination Position', 'gallery-img'); ?></label>
                                    <select id="video_ht_view7_paginator_position"
                                            name="params[uxgallery_video_ht_view7_paginator_position]">
                                        <option <?php if (get_option('uxgallery_video_ht_view7_paginator_position') == 'left') {
                                            echo 'selected';
                                        } ?> value="left"><?php echo __('Left', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_video_ht_view7_paginator_position') == 'center') {
                                            echo 'selected';
                                        } ?> value="center"><?php echo __('Center', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_video_ht_view7_paginator_position') == 'right') {
                                            echo 'selected';
                                        } ?> value="right"><?php echo __('Right', 'gallery-img'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="thumb_margin">
                                <h3><?php echo __('Load More Styles', 'gallery-img'); ?></h3>
                                <div class="fixed-size has-background">
                                    <label for="video_ht_view7_loadmore_text"><?php echo __('Load More Text', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view7_loadmore_text]"
                                           id="video_ht_view7_loadmore_text"
                                           value="<?php echo esc_attr(get_option('uxgallery_video_ht_view7_loadmore_text')); ?>"
                                           class="text">

                                </div>
                                <div>
                                    <label for="video_ht_view7_loadmore_position"><?php echo __('Load More Position', 'gallery-img'); ?></label>
                                    <select id="video_ht_view7_loadmore_position"
                                            name="params[uxgallery_video_ht_view7_loadmore_position]">
                                        <option <?php if (get_option('uxgallery_video_ht_view7_loadmore_position') == 'left') {
                                            echo 'selected';
                                        } ?> value="left"><?php echo __('Left', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_video_ht_view7_loadmore_position') == 'center') {
                                            echo 'selected';
                                        } ?> value="center"><?php echo __('Center', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_video_ht_view7_loadmore_position') == 'right') {
                                            echo 'selected';
                                        } ?> value="right"><?php echo __('Right', 'gallery-img'); ?></option>
                                    </select>
                                </div>
                                <div class="fixed-size has-background">
                                    <label for="video_ht_view7_loadmore_fontsize"><?php echo __('Load More Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view7_loadmore_fontsize]"
                                           id="video_ht_view7_loadmore_fontsize"
                                           value="<?php echo get_option('uxgallery_video_ht_view7_loadmore_fontsize'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class="  fixed-size">
                                    <label for="video_ht_view7_loadmore_font_color"><?php echo __('Load More Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view7_loadmore_font_color]"
                                           class="color" id="video_ht_view7_loadmore_font_color"
                                           value="<?php echo get_option('uxgallery_video_ht_view7_loadmore_font_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="video_ht_view7_loadmore_font_color_hover"><?php echo __('Load More Font Hover Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_video_ht_view7_loadmore_font_color_hover]"
                                           class="color" id="video_ht_view7_loadmore_font_color_hover"
                                           value="<?php echo get_option('uxgallery_video_ht_view7_loadmore_font_color_hover'); ?>"
                                           class="text">
                                </div>
                                <div class="fixed-size">
                                    <label for="video_ht_view7_button_color"><?php echo __('Load More Button Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view7_button_color]"
                                           class="color" id="video_ht_view7_button_color"
                                           value="<?php echo get_option('uxgallery_video_ht_view7_button_color'); ?>"
                                           class="text">

                                </div>
                                <div class="fixed-size has-background">
                                    <label for="video_ht_view7_button_color_hover"><?php echo __('Load More Background Hover Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view7_button_color_hover]"
                                           class="color" id="video_ht_view7_button_color_hover"
                                           value="<?php echo get_option('uxgallery_video_ht_view7_button_color_hover'); ?>"
                                           class="text">
                                </div>
                                <div class="navigation-type-block has-height">
                                    <label for=""><?php echo __('Loading Animation', 'gallery-img'); ?> </label>

                                    <div class="has-height ">
                                        <div>
                                            <ul id="arrows-type">
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_video_ht_view7_loading_type') == 1) {
                                                    echo "class='active'";
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading1.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_video_ht_view7_loading_type]"
                                                           value="1" <?php if (get_option('uxgallery_video_ht_view7_loading_type') == 1) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_video_ht_view7_loading_type') == 2) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading4.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_video_ht_view7_loading_type]"
                                                           value="2" <?php if (get_option('uxgallery_video_ht_view7_loading_type') == 2) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_video_ht_view7_loading_type') == 3) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading36.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_video_ht_view7_loading_type]"
                                                           value="3" <?php if (get_option('uxgallery_video_ht_view7_loading_type') == 3) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_video_ht_view7_loading_type') == 4) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading51.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_video_ht_view7_loading_type]"
                                                           value="4" <?php if (get_option('uxgallery_video_ht_view7_loading_type') == 4) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Ratings Styles', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_thumb_rating_count"><?php echo __('Show Ratings Count', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off" name="params[uxgallery_ht_thumb_rating_count]"/>
                                    <input type="checkbox"
                                           id="ht_thumb_rating_count" <?php if (get_option('uxgallery_ht_thumb_rating_count') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_thumb_rating_count]" value="on"/>
                                </div>
                                <div class="fixed-size">
                                    <label for="ht_thumb_likedislike_bg"><?php echo __('Ratings Background Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_thumb_likedislike_bg]" class="color"
                                           id="ht_thumb_likedislike_bg"
                                           value="<?php echo get_option('uxgallery_ht_thumb_likedislike_bg'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background">
                                    <label for="ht_thumb_likedislike_bg_trans"><?php echo __('Ratings Background Color Opacity', 'gallery-img'); ?></label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_ht_thumb_likedislike_bg_trans]"
                                               id="ht_thumb_likedislike_bg_trans" data-slider-highlight="true"
                                               data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text"
                                               data-slider="true"
                                               value="<?php echo get_option('uxgallery_ht_thumb_likedislike_bg_trans'); ?>"/>
                                        <span><?php echo get_option('uxgallery_ht_thumb_likedislike_bg_trans'); ?>
                                            %</span>
                                    </div>
                                </div>
                                <div class="fixed-size">
                                    <label for="ht_thumb_likedislike_font_color"><?php echo __('Ratings Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_thumb_likedislike_font_color]"
                                           class="color" id="ht_thumb_likedislike_font_color"
                                           value="<?php echo get_option('uxgallery_ht_thumb_likedislike_font_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_thumb_active_font_color"><?php echo __('Ratings Rated Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_thumb_active_font_color]"
                                           class="color" id="ht_thumb_active_font_color"
                                           value="<?php echo get_option('uxgallery_ht_thumb_active_font_color'); ?>"
                                           class="text">

                                </div>

                                <div class="fixed-size">
                                    <label for="ht_thumb_likedislike_thumb_color"><?php echo __('Like/Dislike Icon Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_thumb_likedislike_thumb_color]"
                                           class="color" id="ht_thumb_likedislike_thumb_color"
                                           value="<?php echo get_option('uxgallery_ht_thumb_likedislike_thumb_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_thumb_likedislike_thumb_active_color"><?php echo __('Like/Dislike Rated Icon Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_ht_thumb_likedislike_thumb_active_color]"
                                           class="color" id="ht_thumb_likedislike_thumb_active_color"
                                           value="<?php echo get_option('uxgallery_ht_thumb_likedislike_thumb_active_color'); ?>"
                                           class="text">

                                </div>
                                <div class="fixed-size">
                                    <label for="ht_thumb_heart_likedislike_thumb_color"><?php echo __('Heart Icon Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_thumb_heart_likedislike_thumb_color]"
                                           class="color" id="ht_thumb_heart_likedislike_thumb_color"
                                           value="<?php echo get_option('uxgallery_ht_thumb_heart_likedislike_thumb_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_thumb_heart_likedislike_thumb_active_color"><?php echo __('Heart Rated Icon Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_ht_thumb_heart_likedislike_thumb_active_color]"
                                           class="color" id="ht_thumb_heart_likedislike_thumb_active_color"
                                           value="<?php echo get_option('uxgallery_ht_thumb_heart_likedislike_thumb_active_color'); ?>"
                                           class="text">

                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Album options', 'gallery-img'); ?></h3>

                                <div class="has-background">
                                    <label for="uxgallery_album_thumbnail_show_image_count_2"><?= __('Show images count', 'gallery-img') ?></label>
                                    <input value="off" name="params[uxgallery_album_thumbnail_show_image_count_2]"
                                           type="hidden">
                                    <input id="uxgallery_album_thumbnail_show_image_count_2"
                                           name="params[uxgallery_album_thumbnail_show_image_count_2]"
                                           value="on" <?php if (get_option("uxgallery_album_thumbnail_show_image_count_2") == "on") {
                                        echo "checked='checked'";
                                    } ?> size="10" type="checkbox">
                                </div>
                                <div class="has-height">
                                    <label for="params[uxgallery_album_lightbox_count_style]"><?= __('Images Count Style', 'gallery-img') ?></label>
                                    <ul id="arrows-type">
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_thumbnail_count_style') == 0) {
                                            echo "class='active'";
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/count/count-0.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_thumbnail_count_style]"
                                                   value="0" <?php if (get_option('uxgallery_album_thumbnail_count_style') == 0) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_thumbnail_count_style') == 1) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/count/count-1.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_thumbnail_count_style]"
                                                   value="1" <?php if (get_option('uxgallery_album_thumbnail_count_style') == 1) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_thumbnail_count_style') == 2) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/count/count-2.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_thumbnail_count_style]"
                                                   value="2" <?php if (get_option('uxgallery_album_thumbnail_count_style') == 2) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_thumbnail_count_style') == 3) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/count/count-3.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_thumbnail_count_style]"
                                                   value="3" <?php if (get_option('uxgallery_album_thumbnail_count_style') == 3) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_thumbnail_count_style') == 4) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/count/count-4.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_thumbnail_count_style]"
                                                   value="4" <?php if (get_option('uxgallery_album_thumbnail_count_style') == 4) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                    </ul>
                                </div>
                                <div class="has-height">
                                    <label for="params[uxgallery_album_lightbox_count_style]"><?= __('Category Buttons Style', 'gallery-img') ?></label>
                                    <ul id="arrows-type">
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_thumbnail_category_style') == 0) {
                                            echo "class='active'";
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/0.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_thumbnail_category_style]"
                                                   value="0" <?php if (get_option('uxgallery_album_thumbnail_category_style') == 0) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_thumbnail_category_style') == 1) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/1.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_thumbnail_category_style]"
                                                   value="1" <?php if (get_option('uxgallery_album_thumbnail_category_style') == 1) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_thumbnail_category_style') == 2) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/2.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_thumbnail_category_style]"
                                                   value="2" <?php if (get_option('uxgallery_album_thumbnail_category_style') == 2) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_thumbnail_category_style') == 3) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/3.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_thumbnail_category_style]"
                                                   value="3" <?php if (get_option('uxgallery_album_thumbnail_category_style') == 3) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_thumbnail_category_style') == 4) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/4.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_thumbnail_category_style]"
                                                   value="4" <?php if (get_option('uxgallery_album_thumbnail_category_style') == 4) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_thumbnail_category_style') == 5) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/5.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_thumbnail_category_style]"
                                                   value="5" <?php if (get_option('uxgallery_album_thumbnail_category_style') == 5) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                        <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_album_thumbnail_category_style') == 6) {
                                            echo 'class="active"';
                                        } ?>>
                                            <div class="image-block">
                                                <img src="<?php echo $path_album; ?>/category/6.png"
                                                     alt=""/>
                                            </div>
                                            <input type="radio"
                                                   name="params[uxgallery_album_thumbnail_category_style]"
                                                   value="6" <?php if (get_option('uxgallery_album_thumbnail_category_style') == 6) {
                                                echo 'checked="checked"';
                                            } ?>>
                                        </li>
                                    </ul>
                                </div>
                                <div class="has-background">
                                    <label for="uxgallery_album_thumbnail_show_description"><?= __('Show Description', 'gallery-img') ?></label>
                                    <input value="off" name="params[uxgallery_album_thumbnail_show_description]"
                                           type="hidden">
                                    <input id="uxgallery_album_thumbnail_show_description"
                                           name="params[uxgallery_album_thumbnail_show_description]"
                                           value="on" <?php if (get_option("uxgallery_album_thumbnail_show_description") == "on") {
                                        echo "checked='checked'";
                                    } ?> size="10" type="checkbox">
                                </div>


                            </div>
                        </li>

                        <li class="gallery-view-options-5">
                            <span class="content_heading">Justified</span>
                            <div>
                                <h3><?php echo __('Element Styles', 'gallery-img'); ?></h3>

                                <!--                                                    <div class="has-background">
		                        <label for="ht_view8_element_size_fix">Size fix</label>
		                        <input type="hidden" value="false" name="params[uxgallery_ht_view8_element_size_fix]" />
		                        <input type="checkbox" id="ht_view8_element_size_fix"  <?php if (get_option('uxgallery_ht_view8_element_size_fix') == 'true') {
                                    echo 'checked="checked"';
                                } ?>  name="params[uxgallery_ht_view8_element_size_fix]" value="true" />
		                </div>-->

                                <div class="has-background fixed-size">
                                    <label for="ht_view8_element_height"><?php echo __('Image height', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view8_element_height]"
                                           id="ht_view8_element_height"
                                           value="<?php echo get_option('uxgallery_ht_view8_element_height'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>

                                <!--                                                    <div class="has-background not-fixed-size">
		                        <label for="ht_view8_element_maxheight">Popup maxHeight</label>
		                        <input type="number" name="params[uxgallery_ht_view8_element_maxheight]" id="ht_view8_element_maxheight" value="<?php echo get_option('uxgallery_ht_view8_element_maxheight'); ?>" class="text">
		                        <span>px</span>
		                </div>-->


                                <div class="">
                                    <label for="ht_view8_element_padding"><?php echo __('Image margin', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view8_element_padding]"
                                           id="ht_view8_element_border_radius"
                                           value="<?php echo get_option('uxgallery_ht_view8_element_padding'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>


                                <div class="has-background">
                                    <label for="ht_view8_element_justify"><?php echo __('Image Justify', 'gallery-img'); ?></label>
                                    <input type="hidden" value="false"
                                           name="params[uxgallery_ht_view8_element_justify]"/>
                                    <input type="checkbox"
                                           id="ht_view8_element_justify" <?php if (get_option('uxgallery_ht_view8_element_justify') == 'true') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_view8_element_justify]" value="true"/>
                                </div>

                                <div class="">
                                    <label for="ht_view8_element_randomize"><?php echo __('Image Randomize', 'gallery-img'); ?></label>
                                    <input type="hidden" value="false"
                                           name="params[uxgallery_ht_view8_element_randomize]"/>
                                    <input type="checkbox"
                                           id="ht_view8_element_justify" <?php if (get_option('uxgallery_ht_view8_element_randomize') == 'true') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_view8_element_randomize]" value="true"/>
                                </div>

                                <div class="has-background">
                                    <label for="ht_view8_element_cssAnimation"><?php echo __('Opening With Animation', 'gallery-img'); ?></label>
                                    <input type="hidden" value="false"
                                           name="params[uxgallery_ht_view8_element_cssAnimation]"/>
                                    <input type="checkbox"
                                           id="ht_view8_element_justify" <?php if (get_option('uxgallery_ht_view8_element_cssAnimation') == 'true') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_view8_element_cssAnimation]" value="true"/>
                                </div>

                                <div class="">
                                    <label for="ht_view8_element_animation_speed"><?php echo __('Opening Animation Speed', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view8_element_animation_speed]"
                                           id="ht_view8_element_animation_speed"
                                           value="<?php echo get_option('uxgallery_ht_view8_element_animation_speed'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Element Title', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_view8_element_show_caption"><?php echo __('Show Title', 'gallery-img'); ?></label>
                                    <input type="hidden" value="false"
                                           name="params[uxgallery_ht_view8_element_show_caption]"/>
                                    <input type="checkbox"
                                           id="ht_view8_element_show_caption" <?php if (get_option('uxgallery_ht_view8_element_show_caption') == 'true') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_view8_element_show_caption]" value="true"/>
                                </div>
                                <div>
                                    <label for="ht_view8_element_title_font_size"><?php echo __('Element Title Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view8_element_title_font_size]"
                                           id="ht_view8_element_title_font_size"
                                           value="<?php echo get_option('uxgallery_ht_view8_element_title_font_size'); ?>"
                                           class="text"/>
                                    <span>px</span>
                                </div>
                                <div class="has-background">
                                    <label for="ht_view8_element_title_font_color"><?php echo __('Element Title Font Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view8_element_title_font_color]" type="text"
                                           class="color" id="ht_view8_element_title_font_color"
                                           value="#<?php echo get_option('uxgallery_ht_view8_element_title_font_color'); ?>"
                                           size="10"/>
                                </div>
                                <div>
                                    <label for="ht_view8_element_title_background_color"><?php echo __('Element Title Background Color', 'gallery-img'); ?></label>
                                    <input name="params[uxgallery_ht_view8_element_title_background_color]"
                                           type="text" class="color" id="ht_view8_element_title_background_color"
                                           value="#<?php echo get_option('uxgallery_ht_view8_element_title_background_color'); ?>"
                                           size="10"/>
                                </div>

                                <div class="has-background">
                                    <label for="ht_view8_zoombutton_style"><?php echo __('Elements Title Overlay Opacity', 'gallery-img'); ?></label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_ht_view8_element_title_overlay_transparency]"
                                               id="ht_view8_element_title_overlay_transparency"
                                               data-slider-highlight="true"
                                               data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text"
                                               data-slider="true"
                                               value="<?php echo get_option('uxgallery_ht_view8_element_title_overlay_transparency'); ?>"/>
                                        <span><?php echo get_option('uxgallery_ht_view8_element_title_overlay_transparency'); ?>
                                            %</span>
                                    </div>
                                </div>

                            </div>
                            <div>
                                <h3><?php echo __('Pagination Styles', 'gallery-img'); ?></h3>
                                <div class="fixed-size has-background">
                                    <label for="video_ht_view8_paginator_fontsize"><?php echo __('Pagination Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view8_paginator_fontsize]"
                                           id="video_ht_view8_paginator_fontsize"
                                           value="<?php echo get_option('uxgallery_video_ht_view8_paginator_fontsize'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class="  fixed-size">
                                    <label for="video_ht_view8_paginator_color"><?php echo __('Pagination Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view8_paginator_color]"
                                           class="color" id="video_ht_view8_paginator_color"
                                           value="<?php echo get_option('uxgallery_video_ht_view8_paginator_color'); ?>"
                                           class="text">

                                </div>
                                <div class="fixed-size has-background">
                                    <label for="video_ht_view8_paginator_icon_size"><?php echo __('Pagination Icons Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view8_paginator_icon_size]"
                                           id="video_ht_view8_paginator_icon_size"
                                           value="<?php echo get_option('uxgallery_video_ht_view8_paginator_icon_size'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class=" fixed-size">
                                    <label for="video_ht_view8_paginator_icon_color"><?php echo __('Pagination Icons Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view8_paginator_icon_color]"
                                           class="color" id="video_ht_view8_paginator_icon_color"
                                           value="<?php echo get_option('uxgallery_video_ht_view8_paginator_icon_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background">
                                    <label for="video_ht_view8_paginator_position"><?php echo __('Pagination Position', 'gallery-img'); ?></label>
                                    <select id="video_ht_view8_paginator_position"
                                            name="params[uxgallery_video_ht_view8_paginator_position]">
                                        <option <?php if (get_option('uxgallery_video_ht_view8_paginator_position') == 'left') {
                                            echo 'selected';
                                        } ?> value="left"><?php echo __('Left', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_video_ht_view8_paginator_position') == 'center') {
                                            echo 'selected';
                                        } ?> value="center"><?php echo __('Center', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_video_ht_view8_paginator_position') == 'right') {
                                            echo 'selected';
                                        } ?> value="right"><?php echo __('Right', 'gallery-img'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="just_margin">
                                <h3><?php echo __('Load More Styles', 'gallery-img'); ?></h3>
                                <div class="fixed-size has-background">
                                    <label for="video_ht_view8_loadmore_text"><?php echo __('Load More Text', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view8_loadmore_text]"
                                           id="video_ht_view8_loadmore_text"
                                           value="<?php echo esc_attr(get_option('uxgallery_video_ht_view8_loadmore_text')); ?>"
                                           class="text">

                                </div>
                                <div>
                                    <label for="video_ht_view8_loadmore_position"><?php echo __('Load More Position', 'gallery-img'); ?></label>
                                    <select id="video_ht_view8_loadmore_position"
                                            name="params[uxgallery_video_ht_view8_loadmore_position]">
                                        <option <?php if (get_option('uxgallery_video_ht_view8_loadmore_position') == 'left') {
                                            echo 'selected';
                                        } ?> value="left"><?php echo __('Left', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_video_ht_view8_loadmore_position') == 'center') {
                                            echo 'selected';
                                        } ?> value="center"><?php echo __('Center', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_video_ht_view8_loadmore_position') == 'right') {
                                            echo 'selected';
                                        } ?> value="right"><?php echo __('Right', 'gallery-img'); ?></option>
                                    </select>
                                </div>
                                <div class="has-background fixed-size">
                                    <label for="video_ht_view8_loadmore_fontsize"><?php echo __('Load More Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view8_loadmore_fontsize]"
                                           id="video_ht_view8_loadmore_fontsize"
                                           value="<?php echo get_option('uxgallery_video_ht_view8_loadmore_fontsize'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class=" fixed-size">
                                    <label for="video_ht_view8_loadmore_font_color"><?php echo __('Load More Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view8_loadmore_font_color]"
                                           class="color" id="video_ht_view8_loadmore_font_color"
                                           value="<?php echo get_option('uxgallery_video_ht_view8_loadmore_font_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="video_ht_view8_loadmore_font_color_hover"><?php echo __('Load More Font Hover Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_video_ht_view8_loadmore_font_color_hover]"
                                           class="color" id="video_ht_view8_loadmore_font_color_hover"
                                           value="<?php echo get_option('uxgallery_video_ht_view8_loadmore_font_color_hover'); ?>"
                                           class="text">
                                </div>
                                <div class="fixed-size">
                                    <label for="video_ht_view8_button_color"><?php echo __('Load More Background Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view8_button_color]"
                                           class="color" id="video_ht_view8_button_color"
                                           value="<?php echo get_option('uxgallery_video_ht_view8_button_color'); ?>"
                                           class="text">

                                </div>
                                <div class="fixed-size has-background">
                                    <label for="video_ht_view8_button_color_hover"><?php echo __('Load More Background Hover Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view8_button_color_hover]"
                                           class="color" id="video_ht_view8_button_color_hover"
                                           value="<?php echo get_option('uxgallery_video_ht_view8_button_color_hover'); ?>"
                                           class="text">
                                </div>
                                <div class="navigation-type-block has-height has-background ">
                                    <label for=""><?php echo __('Loading Animation', 'gallery-img'); ?> </label>

                                    <div class="has-height ">
                                        <div>
                                            <ul id="arrows-type">
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_video_ht_view8_loading_type') == 1) {
                                                    echo "class='active'";
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading1.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_video_ht_view8_loading_type]"
                                                           value="1" <?php if (get_option('uxgallery_video_ht_view8_loading_type') == 1) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_video_ht_view8_loading_type') == 2) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading4.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_video_ht_view8_loading_type]"
                                                           value="2" <?php if (get_option('uxgallery_video_ht_view8_loading_type') == 2) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_video_ht_view8_loading_type') == 3) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading36.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_video_ht_view8_loading_type]"
                                                           value="3" <?php if (get_option('uxgallery_video_ht_view8_loading_type') == 3) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_video_ht_view8_loading_type') == 4) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading51.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio"
                                                           name="params[uxgallery_video_ht_view8_loading_type]"
                                                           value="4" <?php if (get_option('uxgallery_video_ht_view8_loading_type') == 4) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Ratings Styles', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_just_rating_count"><?php echo __('Show Ratings Count', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off" name="params[uxgallery_ht_just_rating_count]"/>
                                    <input type="checkbox"
                                           id="ht_just_rating_count" <?php if (get_option('uxgallery_ht_just_rating_count') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_just_rating_count]" value="on"/>
                                </div>
                                <div class="fixed-size">
                                    <label for="ht_just_likedislike_bg"><?php echo __('Ratings Background Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_just_likedislike_bg]" class="color"
                                           id="ht_just_likedislike_bg"
                                           value="<?php echo get_option('uxgallery_ht_just_likedislike_bg'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background">
                                    <label for="ht_just_likedislike_bg_trans"><?php echo __('Ratings Background Color Opacity', 'gallery-img'); ?></label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_ht_just_likedislike_bg_trans]"
                                               id="ht_just_likedislike_bg_trans" data-slider-highlight="true"
                                               data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text"
                                               data-slider="true"
                                               value="<?php echo get_option('uxgallery_ht_just_likedislike_bg_trans'); ?>"/>
                                        <span><?php echo get_option('uxgallery_ht_just_likedislike_bg_trans'); ?>
                                            %</span>
                                    </div>
                                </div>
                                <div class="fixed-size">
                                    <label for="ht_just_likedislike_font_color"><?php echo __('Ratings Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_just_likedislike_font_color]"
                                           class="color" id="ht_just_likedislike_font_color"
                                           value="<?php echo get_option('uxgallery_ht_just_likedislike_font_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_just_active_font_color"><?php echo __('Ratings Rated Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_just_active_font_color]"
                                           class="color" id="ht_just_active_font_color"
                                           value="<?php echo get_option('uxgallery_ht_just_active_font_color'); ?>"
                                           class="text">

                                </div>

                                <div class="fixed-size">
                                    <label for="ht_just_likedislike_thumb_color"><?php echo __('Like/Dislike Icon Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_just_likedislike_thumb_color]"
                                           class="color" id="ht_just_likedislike_thumb_color"
                                           value="<?php echo get_option('uxgallery_ht_just_likedislike_thumb_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_just_likedislike_thumb_active_color"><?php echo __('Like/Dislike Rated Icon Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_just_likedislike_thumb_active_color]"
                                           class="color" id="ht_just_likedislike_thumb_active_color"
                                           value="<?php echo get_option('uxgallery_ht_just_likedislike_thumb_active_color'); ?>"
                                           class="text">

                                </div>
                                <div class="fixed-size">
                                    <label for="ht_just_heart_likedislike_thumb_color"><?php echo __('Heart Icon Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_just_heart_likedislike_thumb_color]"
                                           class="color" id="ht_just_heart_likedislike_thumb_color"
                                           value="<?php echo get_option('uxgallery_ht_just_heart_likedislike_thumb_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_just_heart_likedislike_thumb_active_color"><?php echo __('Heart Rated Icon Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_ht_just_heart_likedislike_thumb_active_color]"
                                           class="color" id="ht_just_heart_likedislike_thumb_active_color"
                                           value="<?php echo get_option('uxgallery_ht_just_heart_likedislike_thumb_active_color'); ?>"
                                           class="text">

                                </div>
                            </div>
                        </li>

                        <li class="gallery-view-options-6">
                            <span class="content_heading">Blog Style Gallery</span>
                            <div>
                                <h3><?php echo __('General Styles', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_view9_general_width"><?php echo __('Width', 'gallery-img'); ?></label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_ht_view9_general_width]"
                                               id="ht_view9_general_width" data-slider-highlight="true"
                                               data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text"
                                               data-slider="true"
                                               value="<?php echo get_option('uxgallery_ht_view9_general_width'); ?>"/>
                                        <span><?php echo get_option('uxgallery_ht_view9_general_width'); ?>%</span>
                                    </div>
                                </div>
                                <div>
                                    <label for="view9_general_position"><?php echo __('Content Position', 'gallery-img'); ?></label>
                                    <select id="view9_general_position"
                                            name="params[uxgallery_view9_general_position]">
                                        <option <?php if (get_option('uxgallery_view9_general_position') == 'left') {
                                            echo 'selected';
                                        } ?> value="left"><?php echo __('Left', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_view9_general_position') == 'center') {
                                            echo 'selected';
                                        } ?> value="center"><?php echo __('Center', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_view9_general_position') == 'right') {
                                            echo 'selected';
                                        } ?> value="right"><?php echo __('Right', 'gallery-img'); ?></option>
                                    </select>
                                </div>
                                <div class="has-background">
                                    <label for="view9_image_position"><?php echo __('Image Position', 'gallery-img'); ?></label>
                                    <select id="view9_image_position" name="params[uxgallery_view9_image_position]">
                                        <option <?php if (get_option('uxgallery_view9_image_position') == '1') {
                                            echo 'selected';
                                        } ?> value="1"><?php echo __('Before Title', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_view9_image_position') == '2') {
                                            echo 'selected';
                                        } ?> value="2"><?php echo __('After Title', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_view9_image_position') == '3') {
                                            echo 'selected';
                                        } ?> value="3"><?php echo __('After Description', 'gallery-img'); ?></option>
                                    </select>
                                </div>
                                <div class=" fixed-size">
                                    <label for="ht_view9_general_space"><?php echo __('Space Between Containers', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view9_general_space]"
                                           id="ht_view9_general_space"
                                           value="<?php echo get_option('uxgallery_ht_view9_general_space'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class="has-background">
                                    <label for="view9_general_separator_style"><?php echo __('Separator Line Style', 'gallery-img'); ?></label>
                                    <select id="view9_general_separator_style"
                                            name="params[uxgallery_view9_general_separator_style]">
                                        <option <?php if (get_option('uxgallery_view9_general_separator_style') == 'none') {
                                            echo 'selected';
                                        } ?> value="none"><?php echo __('None', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_view9_general_separator_style') == 'solid') {
                                            echo 'selected';
                                        } ?> value="solid"><?php echo __('Solid', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_view9_general_separator_style') == 'dashed') {
                                            echo 'selected';
                                        } ?> value="dashed"><?php echo __('Dashed', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_view9_general_separator_style') == 'dotted') {
                                            echo 'selected';
                                        } ?> value="dotted"><?php echo __('Dotted', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_view9_general_separator_style') == 'groove') {
                                            echo 'selected';
                                        } ?> value="groove"><?php echo __('Groove', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_view9_general_separator_style') == 'double') {
                                            echo 'selected';
                                        } ?> value="double"><?php echo __('Double', 'gallery-img'); ?></option>
                                    </select>
                                </div>
                                <div class=" fixed-size">
                                    <label for="ht_view9_general_separator_size"><?php echo __('Separator Line Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view9_general_separator_size]"
                                           id="ht_view9_general_separator_size"
                                           value="<?php echo get_option('uxgallery_ht_view9_general_separator_size'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_view9_general_separator_color"><?php echo __('Separator Line Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view9_general_separator_color]"
                                           class="color" id="ht_view9_general_separator_color"
                                           value="<?php echo get_option('uxgallery_ht_view9_general_separator_color'); ?>"
                                           class="text">

                                </div>

                            </div>
                            <div>
                                <h3><?php echo __('Pagination Styles', 'gallery-img'); ?></h3>
                                <div class="has-background fixed-size">
                                    <label for="ht_view9_paginator_fontsize"><?php echo __('Pagination Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view9_paginator_fontsize]"
                                           id="ht_view9_paginator_fontsize"
                                           value="<?php echo get_option('uxgallery_ht_view9_paginator_fontsize'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class="fixed-size">
                                    <label for="ht_view9_paginator_color"><?php echo __('Pagination Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view9_paginator_color]" class="color"
                                           id="ht_view9_paginator_color"
                                           value="<?php echo get_option('uxgallery_ht_view9_paginator_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background  fixed-size">
                                    <label for="ht_view9_paginator_icon_size"><?php echo __('Pagination Icons Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view9_paginator_icon_size]"
                                           id="ht_view9_paginator_icon_size"
                                           value="<?php echo get_option('uxgallery_ht_view9_paginator_icon_size'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class="fixed-size">
                                    <label for="ht_view9_paginator_color"><?php echo __('Pagination Icons Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view9_paginator_icon_color]"
                                           class="color" id="ht_view9_paginator_icon_color"
                                           value="<?php echo get_option('uxgallery_ht_view9_paginator_icon_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background">
                                    <label for="view9_paginator_position"><?php echo __('Pagination Position', 'gallery-img'); ?></label>
                                    <select id="view9_paginator_position"
                                            name="params[uxgallery_view9_paginator_position]">
                                        <option <?php if (get_option('uxgallery_view9_paginator_position') == 'left') {
                                            echo 'selected';
                                        } ?> value="left"><?php echo __('Left', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_view9_paginator_position') == 'center') {
                                            echo 'selected';
                                        } ?> value="center"><?php echo __('Center', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_view9_paginator_position') == 'right') {
                                            echo 'selected';
                                        } ?> value="right"><?php echo __('Right', 'gallery-img'); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="blog_margin">
                                <h3><?php echo __('Title Styles', 'gallery-img'); ?></h3>
                                <div class="has-background fixed-size">
                                    <label for="ht_view9_element_title_show"><?php echo __('Show Title', 'gallery-img'); ?></label>
                                    <input type="hidden" value="false"
                                           name="params[uxgallery_ht_view9_element_title_show]"/>
                                    <input type="checkbox"
                                           id="ht_view9_element_title_show" <?php if (get_option('uxgallery_ht_view9_element_title_show') == 'true') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_view9_element_title_show]" value="true"/>
                                </div>
                                <div class="fixed-size">
                                    <label for="ht_view9_title_fontsize"><?php echo __('Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view9_title_fontsize]"
                                           id="ht_view9_title_fontsize"
                                           value="<?php echo get_option('uxgallery_ht_view9_title_fontsize'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_view9_title_color"><?php echo __('Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view9_title_color]" class="color"
                                           id="ht_view9_title_color"
                                           value="<?php echo get_option('uxgallery_ht_view9_title_color'); ?>"
                                           class="text">

                                </div>
                                <div class="fixed-size">
                                    <label for="ht_view9_title_back_color"><?php echo __('Background Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view9_title_back_color]"
                                           class="color" id="ht_view9_title_back_color"
                                           value="<?php echo get_option('uxgallery_ht_view9_title_back_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background">
                                    <label for="ht_view9_title_opacity"><?php echo __('Background Opacity', 'gallery-img'); ?></label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_ht_view9_title_opacity]"
                                               id="ht_view9_title_opacity" data-slider-highlight="true"
                                               data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text"
                                               data-slider="true"
                                               value="<?php echo get_option('uxgallery_ht_view9_title_opacity'); ?>"/>
                                        <span><?php echo get_option('uxgallery_ht_view9_title_opacity'); ?>%</span>
                                    </div>
                                </div>
                                <div>
                                    <label for="view9_title_textalign"><?php echo __('Text Align', 'gallery-img'); ?></label>
                                    <select id="view9_title_textalign" name="params[uxgallery_view9_title_textalign]">
                                        <option <?php if (get_option('uxgallery_view9_title_textalign') == 'left') {
                                            echo 'selected';
                                        } ?> value="left"><?php echo __('Left', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_view9_title_textalign') == 'center') {
                                            echo 'selected';
                                        } ?> value="center"><?php echo __('Center', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_view9_title_textalign') == 'right') {
                                            echo 'selected';
                                        } ?> value="right"><?php echo __('Right', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_view9_title_textalign') == 'justify') {
                                            echo 'selected';
                                        } ?> value="justify"><?php echo __('Justify', 'gallery-img'); ?></option>
                                    </select>
                                </div>

                            </div>
                            <div>
                                <h3><?php echo __('Description Styles', 'gallery-img'); ?></h3>
                                <div class="has-background fixed-size">
                                    <label for="ht_view9_element_desc_show"><?php echo __('Show Description', 'gallery-img'); ?></label>
                                    <input type="hidden" value="false"
                                           name="params[uxgallery_ht_view9_element_desc_show]"/>
                                    <input type="checkbox"
                                           id="ht_view9_element_desc_show" <?php if (get_option('uxgallery_ht_view9_element_desc_show') == 'true') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_view9_element_desc_show]" value="true"/>
                                </div>
                                <div class="fixed-size">
                                    <label for="ht_view9_desc_fontsize"><?php echo __('Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view9_desc_fontsize]"
                                           id="ht_view9_desc_fontsize"
                                           value="<?php echo get_option('uxgallery_ht_view9_desc_fontsize'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_view9_desc_color"><?php echo __('Font Color', 'gallery-img'); ?></label>
                                    <input type="text" class="color" name="params[uxgallery_ht_view9_desc_color]"
                                           id="ht_view9_desc_color"
                                           value="<?php echo get_option('uxgallery_ht_view9_desc_color'); ?>"
                                           class="text">

                                </div>
                                <div class="fixed-size">
                                    <label for="ht_view9_desc_back_color"><?php echo __('Background Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_view9_desc_back_color]" class="color"
                                           id="ht_view9_desc_back_color"
                                           value="<?php echo get_option('uxgallery_ht_view9_desc_back_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background">
                                    <label for="ht_view9_desc_opacity"><?php echo __('Background Opacity', 'gallery-img'); ?></label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_ht_view9_desc_opacity]"
                                               id="ht_view9_desc_opacity" data-slider-highlight="true"
                                               data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text"
                                               data-slider="true"
                                               value="<?php echo get_option('uxgallery_ht_view9_desc_opacity'); ?>"/>
                                        <span><?php echo get_option('uxgallery_ht_view9_desc_opacity'); ?>%</span>
                                    </div>
                                </div>
                                <div>
                                    <label for="view9_desc_textalign"><?php echo __('Text Align', 'gallery-img'); ?></label>
                                    <select id="view9_desc_textalign" name="params[uxgallery_view9_desc_textalign]">
                                        <option <?php if (get_option('uxgallery_view9_desc_textalign') == 'left') {
                                            echo 'selected';
                                        } ?> value="left"><?php echo __('Left', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_view9_desc_textalign') == 'center') {
                                            echo 'selected';
                                        } ?> value="center"><?php echo __('Center', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_view9_desc_textalign') == 'right') {
                                            echo 'selected';
                                        } ?> value="right"><?php echo __('Right', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_view9_desc_textalign') == 'justify') {
                                            echo 'selected';
                                        } ?> value="justify"><?php echo __('Justify', 'gallery-img'); ?></option>
                                    </select>
                                </div>

                            </div>
                            <div>
                                <h3><?php echo __('Load More Styles', 'gallery-img'); ?></h3>
                                <div class="fixed-size has-background">
                                    <label for="video_ht_view9_loadmore_text"><?php echo __('Load More Text', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view9_loadmore_text]"
                                           id="video_ht_view9_loadmore_text"
                                           value="<?php echo esc_attr(get_option('uxgallery_video_ht_view9_loadmore_text')); ?>"
                                           class="text">

                                </div>
                                <div>
                                    <label for="video_view9_loadmore_position"><?php echo __('Load More Position', 'gallery-img'); ?></label>
                                    <select id="video_view9_loadmore_position"
                                            name="params[uxgallery_video_view9_loadmore_position]">
                                        <option <?php if (get_option('uxgallery_video_view9_loadmore_position') == 'left') {
                                            echo 'selected';
                                        } ?> value="left"><?php echo __('Left', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_video_view9_loadmore_position') == 'center') {
                                            echo 'selected';
                                        } ?> value="center"><?php echo __('Center', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_video_view9_loadmore_position') == 'right') {
                                            echo 'selected';
                                        } ?> value="right"><?php echo __('Right', 'gallery-img'); ?></option>
                                    </select>
                                </div>
                                <div class="has-background fixed-size">
                                    <label for="video_ht_view9_loadmore_fontsize"><?php echo __('Load More Font Size', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view9_loadmore_fontsize]"
                                           id="video_ht_view9_loadmore_fontsize"
                                           value="<?php echo get_option('uxgallery_video_ht_view9_loadmore_fontsize'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class="  fixed-size">
                                    <label for="video_ht_view9_loadmore_font_color"><?php echo __('Load More Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view9_loadmore_font_color]"
                                           class="color" id="video_ht_view9_loadmore_font_color"
                                           value="<?php echo get_option('uxgallery_video_ht_view9_loadmore_font_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="video_ht_view9_loadmore_font_color_hover"><?php echo __('Load More Font Hover Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_video_ht_view9_loadmore_font_color_hover]"
                                           class="color" id="video_ht_view9_loadmore_font_color_hover"
                                           value="<?php echo get_option('uxgallery_video_ht_view9_loadmore_font_color_hover'); ?>"
                                           class="text">

                                </div>
                                <div class="fixed-size ">
                                    <label for="video_ht_view9_button_color"><?php echo __('Load More Background Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view9_button_color]"
                                           class="color" id="video_ht_view9_button_color"
                                           value="<?php echo get_option('uxgallery_video_ht_view9_button_color'); ?>"
                                           class="text">

                                </div>
                                <div class="fixed-size has-background">
                                    <label for="video_ht_view9_button_color_hover"><?php echo __('Load More Background Hover Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_video_ht_view9_button_color_hover]"
                                           class="color" id="video_ht_view9_button_color_hover"
                                           value="<?php echo get_option('uxgallery_video_ht_view9_button_color_hover'); ?>"
                                           class="text">

                                </div>

                                <div class="navigation-type-block has-height">
                                    <label for=""><?php echo __('Loading Animation', 'gallery-img'); ?> </label>

                                    <div class="has-height ">
                                        <div>
                                            <ul id="arrows-type">
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_loading_type') == 1) {
                                                    echo "class='active'";
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading1.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio" name="params[uxgallery_loading_type]"
                                                           value="1" <?php if (get_option('uxgallery_loading_type') == 1) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_loading_type') == 2) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading4.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio" name="params[uxgallery_loading_type]"
                                                           value="2" <?php if (get_option('uxgallery_loading_type') == 2) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_loading_type') == 3) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading36.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio" name="params[uxgallery_loading_type]"
                                                           value="3" <?php if (get_option('uxgallery_loading_type') == 3) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                                <li onclick="jQuery(this).parent().find('li').removeClass('active');jQuery(this).addClass('active');" <?php if (get_option('uxgallery_loading_type') == 4) {
                                                    echo 'class="active"';
                                                } ?>>
                                                    <div class="image-block">
                                                        <img src="<?php echo $path_site; ?>/arrows/loading51.gif"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio" name="params[uxgallery_loading_type]"
                                                           value="4" <?php if (get_option('uxgallery_loading_type') == 4) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo __('Ratings Styles', 'gallery-img'); ?></h3>
                                <div class="has-background">
                                    <label for="ht_blog_rating_count"><?php echo __('Show Ratings Count', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off" name="params[uxgallery_ht_blog_rating_count]"/>
                                    <input type="checkbox"
                                           id="ht_blog_rating_count" <?php if (get_option('uxgallery_ht_blog_rating_count') == 'on') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_ht_blog_rating_count]" value="on"/>
                                </div>
                                <div class="fixed-size">
                                    <label for="ht_blog_likedislike_bg"><?php echo __('Ratings Background Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_blog_likedislike_bg]" class="color"
                                           id="ht_blog_likedislike_bg"
                                           value="<?php echo get_option('uxgallery_ht_blog_likedislike_bg'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background">
                                    <label for="ht_blog_likedislike_bg_trans"><?php echo __('Ratings Background Color Opacity', 'gallery-img'); ?></label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_ht_blog_likedislike_bg_trans]"
                                               id="ht_blog_likedislike_bg_trans" data-slider-highlight="true"
                                               data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text"
                                               data-slider="true"
                                               value="<?php echo get_option('uxgallery_ht_blog_likedislike_bg_trans'); ?>"/>
                                        <span><?php echo get_option('uxgallery_ht_blog_likedislike_bg_trans'); ?>
                                            %</span>
                                    </div>
                                </div>
                                <div class="fixed-size">
                                    <label for="ht_blog_likedislike_font_color"><?php echo __('Ratings Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_blog_likedislike_font_color]"
                                           class="color" id="ht_blog_likedislike_font_color"
                                           value="<?php echo get_option('uxgallery_ht_blog_likedislike_font_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_blog_active_font_color"><?php echo __('Ratings Rated Font Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_blog_active_font_color]"
                                           class="color" id="ht_blog_active_font_color"
                                           value="<?php echo get_option('uxgallery_ht_blog_active_font_color'); ?>"
                                           class="text">

                                </div>

                                <div class="fixed-size">
                                    <label for="ht_blog_likedislike_thumb_color"><?php echo __('Like/Dislike Icon Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_blog_likedislike_thumb_color]"
                                           class="color" id="ht_blog_likedislike_thumb_color"
                                           value="<?php echo get_option('uxgallery_ht_blog_likedislike_thumb_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_blog_likedislike_thumb_active_color"><?php echo __('Like/Dislike Rated Icon Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_blog_likedislike_thumb_active_color]"
                                           class="color" id="ht_blog_likedislike_thumb_active_color"
                                           value="<?php echo get_option('uxgallery_ht_blog_likedislike_thumb_active_color'); ?>"
                                           class="text">

                                </div>
                                <div class="fixed-size">
                                    <label for="ht_blog_heart_likedislike_thumb_color"><?php echo __('Heart Icon Color', 'gallery-img'); ?></label>
                                    <input type="text" name="params[uxgallery_ht_blog_heart_likedislike_thumb_color]"
                                           class="color" id="ht_blog_heart_likedislike_thumb_color"
                                           value="<?php echo get_option('uxgallery_ht_blog_heart_likedislike_thumb_color'); ?>"
                                           class="text">

                                </div>
                                <div class="has-background fixed-size">
                                    <label for="ht_blog_heart_likedislike_thumb_active_color"><?php echo __('Heart Rated Icon Color', 'gallery-img'); ?></label>
                                    <input type="text"
                                           name="params[uxgallery_ht_blog_heart_likedislike_thumb_active_color]"
                                           class="color" id="ht_blog_heart_likedislike_thumb_active_color"
                                           value="<?php echo get_option('uxgallery_ht_blog_heart_likedislike_thumb_active_color'); ?>"
                                           class="text">

                                </div>
                            </div>
                        </li>


                        <li class="gallery-view-options-7">
                            <span class="content_heading">Elastic Grid</span>
                                <div>
                                    <h3><?php _e('Content Styles', 'gallery-img'); ?></h3>
                                    <div class="has-background">
                                        <label
                                                for="uxgallery_ht_view10_show_center"><?php echo __('Show Content In The Center', 'gallery-img'); ?></label>
                                        <input type="hidden" value="off"
                                               name="params[uxgallery_ht_view10_show_center]"/>
                                        <input type="checkbox"
                                               id="uxgallery_ht_view10_show_center" <?php if ($uxgallery_get_option['uxgallery_ht_view10_show_center'] == 'on') {
                                            echo 'checked="checked"';
                                        } ?> name="params[uxgallery_ht_view10_show_center]"
                                               value="on"/>
                                    </div>
                                    <h3><?php echo __('Element Styles', 'gallery-img'); ?></h3>
                                    <div class="has-background">
                                        <label
                                                for="uxgallery_ht_view10_image_behaviour"><?php echo __("Element's Image Behaviour", 'gallery-img'); ?></label>
                                        <select id="uxgallery_ht_view10_image_behaviour"
                                                name="params[uxgallery_ht_view10_image_behaviour]">
                                            <option <?php if ($uxgallery_get_option['uxgallery_ht_view10_image_behaviour'] == 'resize') {
                                                echo 'selected="selected"';
                                            } ?>
                                                    value="resize"><?php echo __('Resize', 'gallery-img'); ?></option>
                                            <option <?php if ($uxgallery_get_option['uxgallery_ht_view10_image_behaviour'] == 'crop') {
                                                echo 'selected="selected"';
                                            } ?>
                                                    value="crop"><?php echo __('Natural', 'gallery-img'); ?></option>
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                                for="uxgallery_ht_view10_element_width"><?php echo __('Element Image Width', 'gallery-img'); ?></label>
                                        <input type="text" name="params[uxgallery_ht_view10_element_width]"
                                               id="uxgallery_ht_view10_element_width"
                                               value="<?php echo $uxgallery_get_option['uxgallery_ht_view10_element_width']; ?>"
                                               class="text"/>
                                        <span>px</span>
                                    </div class="has-background">
                                    <div class="has-background">
                                        <label
                                                for="uxgallery_ht_view10_element_height"><?php echo __('Element Image Height', 'gallery-img'); ?></label>
                                        <input type="text" name="params[uxgallery_ht_view10_element_height]"
                                               id="uxgallery_ht_view10_element_height"
                                               value="<?php echo $uxgallery_get_option['uxgallery_ht_view10_element_height']; ?>"
                                               class="text"/>
                                        <span>px</span>
                                    </div>
                                    <div>
                                        <label
                                                for="uxgallery_ht_view10_element_margin"><?php echo __('Margin Between Elements', 'gallery-img'); ?></label>
                                        <input type="text" name="params[uxgallery_ht_view10_element_margin]"
                                               id="uxgallery_ht_view10_element_margin"
                                               value="<?php echo $uxgallery_get_option['uxgallery_ht_view10_element_margin']; ?>"
                                               class="text"/>
                                        <span>px</span>
                                    </div>
                                    <div class="has-background">
                                        <label
                                                for="uxgallery_ht_view10_element_hover_effect"><?php echo __('Element Hover Effect', 'gallery-img'); ?></label>
                                        <input type="hidden" value="false"
                                               name="params[uxgallery_ht_view10_element_hover_effect]"/>
                                        <input type="checkbox"
                                               id="uxgallery_ht_view10_element_hover_effect" <?php if ($uxgallery_get_option['uxgallery_ht_view10_element_hover_effect'] == 'true') {
                                            echo 'checked="checked"';
                                        } ?> name="params[uxgallery_ht_view10_element_hover_effect]"
                                               value="true"/>
                                    </div>
                                    <div>
                                        <label
                                                for="uxgallery_ht_view10_element_overlay_background_color_"><?php echo __('Element Overlay Background Color', 'gallery-img'); ?></label>
                                        <input
                                                name="params[uxgallery_ht_view10_element_overlay_background_color_]"
                                                type="text" class="color"
                                                id="uxgallery_ht_view10_element_overlay_background_color_"
                                                value="#<?php echo $uxgallery_get_option['uxgallery_ht_view10_element_overlay_background_color_']; ?>"
                                                size="10"/>
                                    </div>
                                    <div class="has-background">
                                        <label
                                                for="uxgallery_ht_view10_element_border_color"><?php echo __('Element Border Color', 'gallery-img'); ?></label>
                                        <input name="params[uxgallery_ht_view10_element_border_color]"
                                               type="text" class="color"
                                               id="uxgallery_ht_view10_element_border_color"
                                               value="#<?php echo $uxgallery_get_option['uxgallery_ht_view10_element_border_color']; ?>"
                                               size="10"/>
                                    </div>
                                    <div>
                                        <label
                                                for="uxgallery_ht_view10_element_border_width"><?php echo __('Element Border Width', 'gallery-img'); ?></label>
                                        <input type="text"
                                               name="params[uxgallery_ht_view10_element_border_width]"
                                               id="uxgallery_ht_view10_element_border_width"
                                               value="<?php echo $uxgallery_get_option['uxgallery_ht_view10_element_border_width']; ?>"
                                               class="text"/>
                                        <span>px</span>
                                    </div>
                                    <div class="has-background">
                                        <label
                                                for="uxgallery_ht_view10_element_overlay_opacity"><?php echo __("Element's Image Overlay Opacity", 'gallery-img'); ?></label>
                                        <div class="slider-container">
                                            <input name="params[uxgallery_ht_view10_element_overlay_opacity]"
                                                   id="uxgallery_ht_view10_element_overlay_opacity"
                                                   data-slider-highlight="true"
                                                   data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text"
                                                   data-slider="true"
                                                   value="<?php echo $uxgallery_get_option['uxgallery_ht_view10_element_overlay_opacity']; ?>"/>
                                            <span><?php echo $uxgallery_get_option['uxgallery_ht_view10_element_overlay_opacity']; ?>
                                                %</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                                for="uxgallery_ht_view10_hover_effect_delay"><?php echo __('Element  Hover Effect Delay', 'gallery-img'); ?></label>
                                        <input type="text" name="params[uxgallery_ht_view10_hover_effect_delay]"
                                               id="uxgallery_ht_view10_hover_effect_delay"
                                               value="<?php echo $uxgallery_get_option['uxgallery_ht_view10_hover_effect_delay']; ?>"
                                               class="text"/>
                                        <span>ms</span>
                                    </div>
                                    <div class="has-background">
                                        <label
                                                for="uxgallery_ht_view10_hover_effect_inverse"><?php echo __('Element Hover Effect Inverse', 'gallery-img'); ?></label>
                                        <input type="hidden" value="false"
                                               name="params[uxgallery_ht_view10_hover_effect_inverse]"/>
                                        <input type="checkbox"
                                               id="uxgallery_ht_view10_hover_effect_inverse" <?php if ($uxgallery_get_option['uxgallery_ht_view10_hover_effect_inverse'] == 'true') {
                                            echo 'checked="checked"';
                                        } ?> name="params[uxgallery_ht_view10_hover_effect_inverse]"
                                               value="true"/>
                                    </div>
                                </div>
                                <div>
                                    <h3><?php echo __('Expand Options', 'gallery-img'); ?></h3>
                                    <div class="has-background">
                                        <label
                                                for="uxgallery_ht_view10_expanding_speed"><?php echo __('Expanding Speed', 'gallery-img'); ?></label>
                                        <input type="text" name="params[uxgallery_ht_view10_expanding_speed]"
                                               id="uxgallery_ht_view10_expanding_speed"
                                               value="<?php echo $uxgallery_get_option['uxgallery_ht_view10_expanding_speed']; ?>"
                                               class="text"/>
                                        <span>ms</span>
                                    </div>
                                    <div>
                                        <label
                                                for="uxgallery_ht_view10_expand_block_height"><?php echo __('Expand Block Initial Height', 'gallery-img'); ?></label>
                                        <input type="text" name="params[uxgallery_ht_view10_expand_block_height]"
                                               id="uxgallery_ht_view10_expand_block_height"
                                               value="<?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_height']; ?>"
                                               class="text"/>
                                        <span>px</span>
                                    </div>
                                    <div class="has-background">
                                        <label
                                                for="uxgallery_ht_view10_expand_width"><?php echo __('Expand Block Width', 'gallery-img'); ?></label>
                                        <input type="text" name="params[uxgallery_ht_view10_expand_width]"
                                               id="uxgallery_ht_view10_expand_width"
                                               value="<?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_width']; ?>"
                                               class="text"/>
                                        <span>%</span>
                                    </div>
                                    <div>
                                        <label
                                                for="uxgallery_ht_view10_expand_block_opacity"><?php echo __("Expand Block Opacity", 'gallery-img'); ?></label>
                                        <div class="slider-container">
                                            <input name="params[uxgallery_ht_view10_expand_block_opacity]"
                                                   id="uxgallery_ht_view10_expand_block_opacity"
                                                   data-slider-highlight="true"
                                                   data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text"
                                                   data-slider="true"
                                                   value="<?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_opacity']; ?>"/>
                                            <span><?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_opacity']; ?>
                                                %</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h3><?php echo __('Expand Title', 'gallery-img'); ?></h3>
                                    <div class="has-background">
                                        <label
                                                for="uxgallery_ht_view10_expand_block_title_color"><?php echo __('Expand Description Title Color', 'gallery-img'); ?></label>
                                        <input name="params[uxgallery_ht_view10_expand_block_title_color]"
                                               type="text" class="color"
                                               id="uxgallery_ht_view10_expand_block_title_color"
                                               value="#<?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_title_color']; ?>"
                                               size="10"/>
                                    </div>
                                    <div>
                                        <label
                                                for="uxgallery_ht_view10_expand_block_title_font_size"><?php echo __('Expand Title Font Size', 'gallery-img'); ?></label>
                                        <input type="text"
                                               name="params[uxgallery_ht_view10_expand_block_title_font_size]"
                                               id="uxgallery_ht_view10_expand_block_title_font_size"
                                               value="<?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_title_font_size']; ?>"
                                               class="text"/>
                                        <span>px</span>
                                    </div>
                                </div>
                                <div>
                                    <h3><?php _e('Element Title', 'gallery-img'); ?></h3>
                                    <div class="has-background">
                                        <label
                                                for="uxgallery_ht_view10_element_title_font_size"><?php echo __('Title Font Size', 'gallery-img'); ?></label>
                                        <input type="text"
                                               name="params[uxgallery_ht_view10_element_title_font_size]"
                                               id="uxgallery_ht_view10_element_title_font_size"
                                               value="<?php echo $uxgallery_get_option['uxgallery_ht_view10_element_title_font_size']; ?>"
                                               class="text"/>
                                        <span>px</span>
                                    </div>
                                    <div>
                                        <label
                                                for="uxgallery_ht_view10_element_title_font_color"><?php echo __('Title Font Color', 'gallery-img'); ?></label>
                                        <input name="params[uxgallery_ht_view10_element_title_font_color]"
                                               type="text" class="color" id="ht_view0_title_font_color"
                                               value="#<?php echo $uxgallery_get_option['uxgallery_ht_view10_element_title_font_color']; ?>"
                                               size="10"/>
                                    </div>
                                    <div class="has-background">
                                        <label
                                                for="uxgallery_ht_view10_element_title_align"><?php echo __("Title Alignment", 'gallery-img'); ?></label>
                                        <select id="uxgallery_ht_view10_element_title_align"
                                                name="params[uxgallery_ht_view10_element_title_align]">
                                            <option <?php if ($uxgallery_get_option['uxgallery_ht_view10_element_title_align'] == 'left') {
                                                echo 'selected="selected"';
                                            } ?>
                                                    value="left"><?php echo __('Left', 'gallery-img'); ?></option>
                                            <option <?php if ($uxgallery_get_option['uxgallery_ht_view10_element_title_align'] == 'center') {
                                                echo 'selected="selected"';
                                            } ?>
                                                    value="center"><?php echo __('Center', 'gallery-img'); ?></option>
                                            <option <?php if ($uxgallery_get_option['uxgallery_ht_view10_element_title_align'] == 'right') {
                                                echo 'selected="selected"';
                                            } ?>
                                                    value="right"><?php echo __('Right', 'gallery-img'); ?></option>
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                                for="uxgallery_ht_view10_element_title_border_width"><?php echo __('Element Title Bottom Border Width', 'gallery-img'); ?></label>
                                        <input type="text"
                                               name="params[uxgallery_ht_view10_element_title_border_width]"
                                               id="uxgallery_ht_view10_element_title_border_width"
                                               value="<?php echo $uxgallery_get_option['uxgallery_ht_view10_element_title_border_width']; ?>"
                                               class="text"/>
                                        <span>px</span>
                                    </div>
                                    <div class="has-background">
                                        <label
                                                for="uxgallery_ht_view10_element_title_border_color"><?php echo __('Element Title Bottom Border Color', 'gallery-img'); ?></label>
                                        <input name="params[uxgallery_ht_view10_element_title_border_color]"
                                               type="text" class="color" id="ht_view0_title_font_color"
                                               value="#<?php echo $uxgallery_get_option['uxgallery_ht_view10_element_title_border_color']; ?>"
                                               size="10"/>
                                    </div>
                                    <div>
                                        <label
                                                for="uxgallery_ht_view10_element_title_margin_top"><?php echo __('Element Title Margin Top', 'gallery-img'); ?></label>
                                        <input type="text"
                                               name="params[uxgallery_ht_view10_element_title_margin_top]"
                                               id="uxgallery_ht_view10_element_title_margin_top"
                                               value="<?php echo $uxgallery_get_option['uxgallery_ht_view10_element_title_margin_top']; ?>"
                                               class="text"/>
                                        <span>px</span>
                                    </div>
                                    <div class="has-background">
                                        <label
                                                for="uxgallery_ht_view10_element_title_padding_top_bottom"><?php echo __('Element Title Top Bottom Padding', 'gallery-img'); ?></label>
                                        <input type="text"
                                               name="params[uxgallery_ht_view10_element_title_padding_top_bottom]"
                                               id="uxgallery_ht_view10_element_title_padding_top_bottom"
                                               value="<?php echo $uxgallery_get_option['uxgallery_ht_view10_element_title_padding_top_bottom']; ?>"
                                               class="text"/>
                                        <span>px</span>
                                    </div>
                                </div>
                                <div>
                                    <h3><?php echo __('Expand Description', 'gallery-img'); ?></h3>
                                    <div class="has-background">
                                        <label
                                                for="ht_view0_description_font_size"><?php echo __('Expand Description Font Size', 'gallery-img'); ?></label>
                                        <input type="text"
                                               name="params[uxgallery_ht_view10_expand_block_description_font_size]"
                                               id="uxgallery_ht_view10_expand_block_description_font_size"
                                               value="<?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_description_font_size']; ?>"
                                               class="text"/>
                                        <span>px</span>
                                    </div>
                                    <div>
                                        <label
                                                for="uxgallery_ht_view10_expand_block_description_font_color"><?php echo __('Description Font Color', 'gallery-img'); ?></label>
                                        <input
                                                name="params[uxgallery_ht_view10_expand_block_description_font_color]"
                                                type="text" class="color"
                                                id="uxgallery_ht_view10_expand_block_description_font_color"
                                                value="#<?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_description_font_color']; ?>"
                                                size="10"/>
                                    </div>
                                    <div class="has-background">
                                        <label
                                                for="uxgallery_ht_view10_expand_block_background_color"><?php echo __('Expand Block Color', 'gallery-img'); ?></label>
                                        <input name="params[uxgallery_ht_view10_expand_block_background_color]"
                                               type="text" class="color"
                                               id="uxgallery_ht_view10_expand_block_background_color"
                                               value="#<?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_background_color']; ?>"
                                               size="10"/>
                                    </div>
                                    <div>
                                        <label
                                                for="uxgallery_ht_view10_expand_block_description_text_align"><?php echo __("Expand Description Text Alignment", 'gallery-img'); ?></label>
                                        <select id="uxgallery_ht_view10_expand_block_description_text_align"
                                                name="params[uxgallery_ht_view10_expand_block_description_text_align]">
                                            <option <?php if ($uxgallery_get_option['uxgallery_ht_view10_expand_block_description_text_align'] == 'left') {
                                                echo 'selected="selected"';
                                            } ?>
                                                    value="left"><?php echo __('Left', 'gallery-img'); ?></option>
                                            <option <?php if ($uxgallery_get_option['uxgallery_ht_view10_expand_block_description_text_align'] == 'center') {
                                                echo 'selected="selected"';
                                            } ?>
                                                    value="center"><?php echo __('Center', 'gallery-img'); ?></option>
                                            <option <?php if ($uxgallery_get_option['uxgallery_ht_view10_expand_block_description_text_align'] == 'right') {
                                                echo 'selected="selected"';
                                            } ?>
                                                    value="right"><?php echo __('Right', 'gallery-img'); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <h3><?php echo __('Link Button', 'gallery-img'); ?></h3>
                                    <div class="has-background">
                                        <label
                                                for="uxgallery_ht_view10_expand_block_button_text"><?php echo __('Link Button Text', 'gallery-img'); ?></label>
                                        <input type="text"
                                               name="params[uxgallery_ht_view10_expand_block_button_text]"
                                               id="uxgallery_ht_view10_expand_block_button_text"
                                               value="<?php echo esc_attr($uxgallery_get_option['uxgallery_ht_view10_expand_block_button_text']); ?>"
                                               class="text"/>
                                    </div>
                                    <div>
                                        <label
                                                for="uxgallery_ht_view10_expand_block_button_font_size"><?php echo __('Link Button Font Size', 'gallery-img'); ?></label>
                                        <input type="text"
                                               name="params[uxgallery_ht_view10_expand_block_button_font_size]"
                                               id="uxgallery_ht_view10_expand_block_button_font_size"
                                               value="<?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_button_font_size']; ?>"
                                               class="text"/>
                                        <span>px</span>
                                    </div>
                                    <div class="has-background">
                                        <label
                                                for="uxgallery_ht_view10_expand_block_button_text_color"><?php echo __('Link Button Font Color', 'gallery-img'); ?></label>
                                        <input name="params[uxgallery_ht_view10_expand_block_button_text_color]"
                                               type="text" class="color"
                                               id="uxgallery_ht_view10_expand_block_button_text_color"
                                               value="#<?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_button_text_color']; ?>"
                                               size="10"/>
                                    </div>
                                    <div>
                                        <label
                                                for="uxgallery_ht_view10_expand_block_description_font_hover_color"><?php echo __('Link Button Font Hover Color', 'gallery-img'); ?></label>
                                        <input
                                                name="params[uxgallery_ht_view10_expand_block_description_font_hover_color]"
                                                type="text" class="color"
                                                id="uxgallery_ht_view10_expand_block_description_font_hover_color"
                                                value="#<?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_description_font_hover_color']; ?>"
                                                size="10"/>
                                    </div>
                                    <div class="has-background">
                                        <label
                                                for="uxgallery_ht_view10_expand_block_button_background_color"><?php echo __('Link Button Background Color', 'gallery-img'); ?></label>
                                        <input
                                                name="params[uxgallery_ht_view10_expand_block_button_background_color]"
                                                type="text" class="color"
                                                id="uxgallery_ht_view10_expand_block_button_background_color"
                                                value="#<?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_button_background_color']; ?>"
                                                size="10"/>
                                    </div>
                                    <div>
                                        <label
                                                for="uxgallery_ht_view10_expand_block_button_background_hover_color"><?php echo __('Link Button Background Hover Color', 'gallery-img'); ?></label>
                                        <input
                                                name="params[uxgallery_ht_view10_expand_block_button_background_hover_color]"
                                                type="text" class="color"
                                                id="uxgallery_ht_view10_expand_block_button_background_hover_color"
                                                value="#<?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_button_background_hover_color']; ?>"
                                                size="10"/>
                                    </div>
                                </div>
                                <div>
                                    <h3><?php echo __('Pagination Styles', 'gallery-img'); ?></h3>
                                    <div class="fixed-size has-background">
                                        <label for="uxgallery_ht_view10_paginator_fontsize"><?php echo __('Pagination Font Size', 'gallery-img'); ?></label>
                                        <input type="text" name="params[uxgallery_ht_view10_paginator_fontsize]"
                                               id="uxgallery_ht_view10_paginator_fontsize"
                                               value="<?php echo get_option('uxgallery_ht_view10_paginator_fontsize'); ?>"
                                               class="text">
                                        <span>px</span>
                                    </div>
                                    <div class="  fixed-size">
                                        <label for="uxgallery_ht_view10_paginator_color"><?php echo __('Pagination Font Color', 'gallery-img'); ?></label>
                                        <input type="text" name="params[uxgallery_ht_view10_paginator_color]"
                                               class="color" id="uxgallery_ht_view10_paginator_color"
                                               value="<?php echo get_option('uxgallery_ht_view10_paginator_color'); ?>"
                                               class="text">

                                    </div>
                                    <div class="fixed-size has-background">
                                        <label for="uxgallery_ht_view10_paginator_icon_size"><?php echo __('Pagination Icons Size', 'gallery-img'); ?></label>
                                        <input type="text" name="params[uxgallery_ht_view10_paginator_icon_size]"
                                               id="uxgallery_ht_view10_paginator_icon_size"
                                               value="<?php echo get_option('uxgallery_ht_view10_paginator_icon_size'); ?>"
                                               class="text">
                                        <span>px</span>
                                    </div>
                                    <div class=" fixed-size">
                                        <label for="uxgallery_ht_view10_paginator_icon_color"><?php echo __('Pagination Icons Color', 'gallery-img'); ?></label>
                                        <input type="text" name="params[uxgallery_ht_view10_paginator_icon_color]"
                                               class="color" id="uxgallery_ht_view10_paginator_icon_color"
                                               value="<?php echo get_option('uxgallery_ht_view10_paginator_icon_color'); ?>"
                                               class="text">

                                    </div>
                                    <div class="has-background">
                                        <label for="uxgallery_ht_view10_paginator_position"><?php echo __('Pagination Position', 'gallery-img'); ?></label>
                                        <select id="uxgallery_ht_view10_paginator_position"
                                                name="params[uxgallery_ht_view10_paginator_position]">
                                            <option <?php if (get_option('uxgallery_ht_view10_paginator_position') == 'left') {
                                                echo 'selected';
                                            } ?> value="left"><?php echo __('Left', 'gallery-img'); ?></option>
                                            <option <?php if (get_option('uxgallery_ht_view10_paginator_position') == 'center') {
                                                echo 'selected';
                                            } ?> value="center"><?php echo __('Center', 'gallery-img'); ?></option>
                                            <option <?php if (get_option('uxgallery_ht_view10_paginator_position') == 'right') {
                                                echo 'selected';
                                            } ?> value="right"><?php echo __('Right', 'gallery-img'); ?></option>
                                        </select>
                                    </div>
                                </div>
                        </li>
                    </ul>
                    <div class="footer_save_wrapper">
                        <a onclick=""
                           class="save-gallery-options save-gallery-options button-primary"><?php echo __('Save Options', 'gallery-img'); ?></a>
                        <div class="clear"></div>
                    </div>
                    <div style="clear:both"></div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<input type="hidden" name="option" value=""/>
<input type="hidden" name="task" value=""/>
<input type="hidden" name="controller" value="options"/>
<input type="hidden" name="op_type" value="styles"/>
<input type="hidden" name="boxchecked" value="0"/>