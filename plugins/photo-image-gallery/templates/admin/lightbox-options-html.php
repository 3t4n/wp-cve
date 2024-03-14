<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="wrap uxgallery_wrap">
    <div class="clear"></div>
    <div id="poststuff">
        <?php $path_site2 = plugins_url("../images", __FILE__);
        $lightbox_options_nonce = wp_create_nonce('uxgallery_nonce_save_lightbox_options');
        ?>


    <div id="post-body-content" class="gallery-options">
        <div id="post-body-heading" class="for_general_">
            <h1><?php echo __('Lightbox Settings', 'gallery-img'); ?></h1>
        </div>

        <form action="admin.php?page=Options_gallery_lightbox_styles&task=save&gallery_lightbox_options_nonce=<?php echo $lightbox_options_nonce; ?>"
              method="post" id="adminForm"
              name="adminForm">

            <div id="lightbox_options_list" class="uxgallery_options_list">

                <ul id="lightbox_type" class="uxgallery_options_tabs">
                    <li class="<?php if (get_option('uxgallery_lightbox_type') == 'new_type') {echo "active";} ?>">
                        <label for="new_type">Modern Style <input type="checkbox" name="params[uxgallery_lightbox_type]" id="new_type" <?php if (get_option('uxgallery_lightbox_type') == 'new_type') {echo 'checked';} ?> value="new_type"></label>
                    </li>
                    <li class="<?php if (get_option('uxgallery_lightbox_type') == 'old_type') {echo "active";} ?>">
                        <label for="old_type">Classic Style <input type="checkbox" name="params[uxgallery_lightbox_type]" id="old_type" <?php if (get_option('uxgallery_lightbox_type') == 'old_type') {echo 'checked';} ?> value="old_type"></label>
                    </li>
                    <a onclick="document.getElementById('adminForm').submit()"
                       class=" button-primary save_lightbox"><?php echo __('Save Lightbox', 'gallery-img'); ?></a>
                </ul>

                <ul class="uxgallery_options_contents">
                    <div class="free_overlay">
                        <div>
                            <p>Template Optioons cant be edited in Free version.</br> Get the Pro version and customise your Gallery</p>
                            <a href="https://uxgallery.net/pricing/" target="_blank">Get Now</a>
                        </div>
                    </div>
                    <li id="new-lightbox-options-list"
                         class="unique-type-options-wrapper <?php if (get_option('uxgallery_lightbox_type') == 'new_type') {
                             echo "active";
                         } ?>">
                        <span class="content_heading">Modern Style</span>

                            <div class="lightbox-options-block">
                                <h3>General Options</h3>
                                <div class="has-background">
                                    <label for="uxgallery_lightbox_lightboxView">Lightbox style
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Choose the style of your popup</p>
                                            </div>
                                        </div>
                                    </label>
                                    <select id="uxgallery_lightbox_lightboxView" >
                                        <option <?php selected('view1', get_option('uxgallery_lightbox_lightboxView')); ?>
                                                value="view1">1
                                        </option>
                                        <option <?php selected('view2', get_option('uxgallery_lightbox_lightboxView')); ?>
                                                value="view2">2
                                        </option>
                                        <option <?php selected('view3', get_option('uxgallery_lightbox_lightboxView')); ?>
                                                value="view3">3
                                        </option>
                                        <option <?php selected('view4', get_option('uxgallery_lightbox_lightboxView')); ?>
                                                value="view4">4
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label for="uxgallery_lightbox_speed_new">Lightbox Opening Speed
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Set lightbox opening speed</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="number" name="params[uxgallery_lightbox_speed_new]"
                                           id="uxgallery_lightbox_speed_new"
                                           value="<?php echo get_option('uxgallery_lightbox_speed_new'); ?>"
                                           class="text">
                                    <span>ms</span>
                                </div>
                                <div class="has-background">
                                    <label for="uxgallery_lightbox_overlayClose_new">Overlay close
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Check to enable closing lightbox by clicking outside the picture.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="hidden" value="false" name="params[uxgallery_lightbox_overlayClose_new]"/>
                                    <input type="checkbox"
                                           id="uxgallery_lightbox_overlayClose_new" <?php if (get_option('uxgallery_lightbox_overlayClose_new') == 'true') {
				                        echo 'checked="checked"';
			                        } ?> name="params[uxgallery_lightbox_overlayClose_new]" value="true"/>
                                </div>
                                <div>
                                    <label for="light_box_style">Loop content
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Check to enable repeating images after one cycle.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="hidden" value="false" name="params[uxgallery_lightbox_loop_new]"/>
                                    <input type="checkbox"
                                           id="uxgallery_lightbox_loop_new" <?php if (get_option('uxgallery_lightbox_loop_new') == 'true') {
				                        echo 'checked="checked"';
			                        } ?> name="params[uxgallery_lightbox_loop_new]" value="true"/>
                                </div>
                                <div class="has-background">
                                    <label for="light_box_style">EscKey close
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Check to enable close by Esc key.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="hidden" value="false" name="params[uxgallery_lightbox_escKey_new]"/>
                                    <input type="checkbox"
                                           id="uxgallery_lightbox_escKey_new" <?php if (get_option('uxgallery_lightbox_escKey_new') == 'true') {
				                        echo 'checked="checked"';
			                        } ?> name="params[uxgallery_lightbox_escKey_new]" value="true"/>
                                </div>
                                <div>
                                    <label for="uxgallery_lightbox_keyPress_new">Keyboard navigation
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Check to enable navigation by keyboard.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="hidden" value="false" name="params[uxgallery_lightbox_keyPress_new]"/>
                                    <input type="checkbox"
                                           id="uxgallery_lightbox_keyPress_new" <?php if (get_option('uxgallery_lightbox_keyPress_new') == 'true') {
				                        echo 'checked="checked"';
			                        } ?> name="params[uxgallery_lightbox_keyPress_new]" value="true"/>
                                </div>
                                <div class="has-background">
                                    <label for="uxgallery_lightbox_arrows">Show Arrows
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Check to display arrow symbols.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="hidden" value="false" name="params[uxgallery_lightbox_arrows]"/>
                                    <input type="checkbox"
                                           id="uxgallery_lightbox_arrows" <?php if (get_option('uxgallery_lightbox_arrows') == 'true') {
				                        echo 'checked="checked"';
			                        } ?> name="params[uxgallery_lightbox_arrows]" value="true"/>
                                </div>
                                <div>
                                    <label for="uxgallery_lightbox_mouseWheel">Mouse Wheel Navigation
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Check to enable navigation by mouse scrolling.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="hidden" value="false" name="params[uxgallery_lightbox_mouseWheel]"/>
                                    <input type="checkbox"
                                           id="uxgallery_lightbox_mouseWheel" <?php if (get_option('uxgallery_lightbox_mouseWheel') == 'true') {
				                        echo 'checked="checked"';
			                        } ?> name="params[uxgallery_lightbox_mouseWheel]" value="true"/>
                                </div>
                                <div class="has-background">
                                    <label for="uxgallery_lightbox_download">Show Download Button
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Check to display download button for the images.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="hidden" value="false" name="params[uxgallery_lightbox_download]"/>
                                    <input type="checkbox"
                                           id="uxgallery_lightbox_download" <?php if (get_option('uxgallery_lightbox_download') == 'true') {
				                        echo 'checked="checked"';
			                        } ?> name="params[uxgallery_lightbox_download]" value="true"/>
                                </div>
                                <div>
                                    <label for="uxgallery_lightbox_showCounter">Show Counter
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Check to display the image sequence numbers.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="hidden" value="false" name="params[uxgallery_lightbox_showCounter]"/>
                                    <input type="checkbox"
                                           id="uxgallery_lightbox_showCounter" <?php if (get_option('uxgallery_lightbox_showCounter') == 'true') {
				                        echo 'checked="checked"';
			                        } ?> name="params[uxgallery_lightbox_showCounter]" value="true"/>
                                </div>
                                <div class="has-background">
                                    <label for="uxgallery_lightbox_sequence_info">Sequence Info text
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Write the texts of the sequence (e.g. Image 5 of 10).</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="text" name="params[uxgallery_lightbox_sequence_info]"
                                           id="uxgallery_lightbox_sequence_info" style="width: 13%"
                                           value="<?php echo esc_attr(get_option('uxgallery_lightbox_sequence_info')); ?>"
                                           class="text">
                                    X <input type="text" name="params[uxgallery_lightbox_sequenceInfo]"
                                             id="uxgallery_lightbox_sequenceInfo" style="width: 13%"
                                             value="<?php echo esc_attr(get_option('uxgallery_lightbox_sequenceInfo')); ?>"
                                             class="text">
                                    XX
                                </div>
                                <div class="has-background">
                                    <label for="uxgallery_lightbox_slideAnimationType">Transition type
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Choose the changing effect of the images.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <select id="uxgallery_lightbox_slideAnimationType"
                                            name="params[uxgallery_lightbox_slideAnimationType]">
                                        <option <?php selected('effect_1', get_option('uxgallery_lightbox_slideAnimationType')); ?>
                                                value="effect_1">Effect 1
                                        </option>
                                        <option <?php selected('effect_2', get_option('uxgallery_lightbox_slideAnimationType')); ?>
                                                value="effect_2">Effect 2
                                        </option>
                                        <option <?php selected('effect_3', get_option('uxgallery_lightbox_slideAnimationType')); ?>
                                                value="effect_3">Effect 3
                                        </option>
                                        <option <?php selected('effect_4', get_option('uxgallery_lightbox_slideAnimationType')); ?>
                                                value="effect_4">Effect 4
                                        </option>
                                        <option <?php selected('effect_5', get_option('uxgallery_lightbox_slideAnimationType')); ?>
                                                value="effect_5">Effect 5
                                        </option>
                                        <option <?php selected('effect_6', get_option('uxgallery_lightbox_slideAnimationType')); ?>
                                                value="effect_6">Effect 6
                                        </option>
                                        <option <?php selected('effect_7', get_option('uxgallery_lightbox_slideAnimationType')); ?>
                                                value="effect_7">Effect 7
                                        </option>
                                        <option <?php selected('effect_8', get_option('uxgallery_lightbox_slideAnimationType')); ?>
                                                value="effect_8">Effect 8
                                        </option>
                                        <option <?php selected('effect_9', get_option('uxgallery_lightbox_slideAnimationType')); ?>
                                                value="effect_9">Effect 9
                                        </option>
                                    </select>
                                </div>
                            </div>


                            <div class="">
                                <h3>Lightbox Watermark styles</h3>
                                <div class="has-background">
                                    <label for="uxgallery_lightbox_watermark">Watermark
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Check to set an image or text overlay on all images in popup.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="hidden" value="false" name="params[uxgallery_lightbox_watermark]"/>
                                    <input type="checkbox"
                                           id="uxgallery_lightbox_watermark" <?php if (get_option('uxgallery_lightbox_watermark') == 'true') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_lightbox_watermark]" value="true"/>
                                </div>
                                <div>
                                    <label for="uxgallery_lightbox_watermark_text">Watermark Text
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Write the text of the watermark.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="text" name="params[uxgallery_lightbox_watermark_text]"
                                           id="uxgallery_lightbox_watermark_text"
                                           value="<?php echo esc_attr(get_option('uxgallery_lightbox_watermark_text')); ?>"
                                           class="text">
                                </div>
                                <div class="has-background">
                                    <label for="uxgallery_lightbox_watermark_textColor">Watermark Text Color
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Set the color of the text.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input name="params[uxgallery_lightbox_watermark_textColor]"
                                           type="text" class="color" id="uxgallery_lightbox_watermark_textColor"
                                           value="#<?php echo get_option('uxgallery_lightbox_watermark_textColor'); ?>"
                                           size="10"/>
                                </div>
                                <div>
                                    <label for="uxgallery_lightbox_watermark_textFontSize">Watermark Text Font Size
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Set the size of the text.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="number" name="params[uxgallery_lightbox_watermark_textFontSize]"
                                           id="uxgallery_lightbox_watermark_textFontSize"
                                           value="<?php echo get_option('uxgallery_lightbox_watermark_textFontSize'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class="has-background">
                                    <label for="uxgallery_lightbox_watermark_containerBackground">Watermark Background Color
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Set the background color of the text.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input name="params[uxgallery_lightbox_watermark_containerBackground]"
                                           type="text" class="color" id="uxgallery_lightbox_watermark_containerBackground"
                                           value="#<?php echo get_option('uxgallery_lightbox_watermark_containerBackground'); ?>"
                                           size="10"/>
                                </div>
                                <div>
                                    <label for="uxgallery_lightbox_watermark_containerOpacity">Watermark Opacity
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Set the opacity of the watermark</p>
                                            </div>
                                        </div>
                                    </label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_lightbox_watermark_containerOpacity]"
                                               id="uxgallery_lightbox_watermark_containerOpacity" data-slider-highlight="true"
                                               data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text" data-slider="true"
                                               value="<?php echo get_option('uxgallery_lightbox_watermark_containerOpacity'); ?>"/>
                                        <span><?php echo get_option('uxgallery_lightbox_watermark_containerOpacity'); ?>
                                            %</span>
                                    </div>
                                </div>
                                <div class="has-background">
                                    <label for="uxgallery_lightbox_watermark_containerWidth">Watermark Width
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Set the size of the watermark in pixels.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="number" name="params[uxgallery_lightbox_watermark_containerWidth]"
                                           id="uxgallery_lightbox_watermark_containerWidth"
                                           value="<?php echo get_option('uxgallery_lightbox_watermark_containerWidth'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class="has-height">
                                    <label for="uxgallery_lightbox_watermark_containerWidth">Watermark Position
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Choose the position to display the watermark.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <div>
                                        <table class="bws_position_table">
                                            <tbody>
                                            <tr>
                                                <td><input type="radio" value="1" id="watermark_top-left"
                                                           name="params[uxgallery_lightbox_watermark_position_new]" <?php if (get_option('uxgallery_lightbox_watermark_position_new') == '1') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                                <td><input type="radio" value="2" id="watermark_top-center"
                                                           name="params[uxgallery_lightbox_watermark_position_new]" <?php if (get_option('uxgallery_lightbox_watermark_position_new') == '2') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                                <td><input type="radio" value="3" id="watermark_top-right"
                                                           name="params[uxgallery_lightbox_watermark_position_new]" <?php if (get_option('uxgallery_lightbox_watermark_position_new') == '3') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                            </tr>
                                            <tr>
                                                <td><input type="radio" value="4" id="watermark_middle-left"
                                                           name="params[uxgallery_lightbox_watermark_position_new]" <?php if (get_option('uxgallery_lightbox_watermark_position_new') == '4') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                                <td><input type="radio" value="5" id="watermark_middle-center"
                                                           name="params[uxgallery_lightbox_watermark_position_new]" <?php if (get_option('uxgallery_lightbox_watermark_position_new') == '5') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                                <td><input type="radio" value="6" id="watermark_middle-right"
                                                           name="params[uxgallery_lightbox_watermark_position_new]" <?php if (get_option('uxgallery_lightbox_watermark_position_new') == '6') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                            </tr>
                                            <tr>
                                                <td><input type="radio" value="7" id="watermark_bottom-left"
                                                           name="params[uxgallery_lightbox_watermark_position_new]" <?php if (get_option('uxgallery_lightbox_watermark_position_new') == '7') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                                <td><input type="radio" value="8" id="watermark_bottom-center"
                                                           name="params[uxgallery_lightbox_watermark_position_new]" <?php if (get_option('uxgallery_lightbox_watermark_position_new') == '8') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                                <td><input type="radio" value="9" id="watermark_bottom-right"
                                                           name="params[uxgallery_lightbox_watermark_position_new]" <?php if (get_option('uxgallery_lightbox_watermark_position_new') == '9') {
                                                        echo 'checked="checked"';
                                                    } ?> /></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="has-background">
                                    <label for="uxgallery_lightbox_watermark_margin">Watermark Margin
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Watermark distance from image sides.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="number" name="params[uxgallery_lightbox_watermark_margin]"
                                           id="uxgallery_lightbox_watermark_margin"
                                           value="<?php echo get_option('uxgallery_lightbox_watermark_margin'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                                <div class="has-background" style="display: none">
                                    <label for="uxgallery_lightbox_watermark_opacity">Watermark Text Opacity
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Choose the style of your popup</p>
                                            </div>
                                        </div>
                                    </label>
                                    <div class="slider-container">
                                        <input name="params[uxgallery_lightbox_watermark_opacity]"
                                               id="uxgallery_lightbox_watermark_opacity" data-slider-highlight="true"
                                               data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text" data-slider="true"
                                               value="<?php echo get_option('uxgallery_lightbox_watermark_opacity'); ?>"/>
                                        <span><?php echo get_option('uxgallery_lightbox_watermark_opacity'); ?>
                                            %</span>
                                    </div>
                                </div>
                                <div class="has-height">
                                    <label for="watermark_image_btn">Select Watermark Image
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Set image for the watermark; leave the ‘Watermark text’ box empty to display the
                                                    image</p>
                                            </div>
                                        </div>
                                    </label>
                                    <div class="watermark_block">
                                        <img src="<?php echo get_option('uxgallery_lightbox_watermark_img_src_new'); ?>" id="watermark_image_new">
                                        <input type="button" class="button wp-media-buttons-icon" id="watermark_image_btn_new" value="Change Image">
                                        <input type="hidden" id="img_watermark_hidden_new" name="params[uxgallery_lightbox_watermark_img_src_new]" value="<?php echo get_option('uxgallery_lightbox_watermark_img_src_new'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <h3>Dimensions</h3>
                                <div class="has-background">
                                    <label for="uxgallery_lightbox_width_new">Lightbox Width
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Set the width of the popup in percentages.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="number" name="params[uxgallery_lightbox_width_new]"
                                           id="uxgallery_lightbox_width_new"
                                           value="<?php echo get_option('uxgallery_lightbox_width_new'); ?>"
                                           class="text">
                                    <span>%</span>
                                </div>
                                <div>
                                    <label for="uxgallery_lightbox_height_new">Lightbox Height
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Set the height of the popup in percentages.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="number" name="params[uxgallery_lightbox_height_new]"
                                           id="uxgallery_lightbox_height_new"
                                           value="<?php echo get_option('uxgallery_lightbox_height_new'); ?>"
                                           class="text">
                                    <span>%</span>
                                </div>
                                <div class="has-background">
                                    <label for="uxgallery_lightbox_videoMaxWidth">Lightbox Video maximum width
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Set the maximum width of the popup in pixels, the height will be fixed
                                                    automatically.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="number" name="params[uxgallery_lightbox_videoMaxWidth]"
                                           id="uxgallery_lightbox_videoMaxWidth"
                                           value="<?php echo get_option('uxgallery_lightbox_videoMaxWidth'); ?>"
                                           class="text">
                                    <span>px</span>
                                </div>
                            </div>
                            <div class="">
                                <h3>Slideshow</h3>
                                <div class="has-background">
                                    <label for="uxgallery_lightbox_slideshow_new">Slideshow
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Check to enable slideshow button for images.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="hidden" value="false" name="params[uxgallery_lightbox_slideshow_new]"/>
                                    <input type="checkbox"
                                           id="uxgallery_lightbox_slideshow_new" <?php if (get_option('uxgallery_lightbox_slideshow_new') == 'true') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_lightbox_slideshow_new]" value="true"/>
                                </div>
                                <div>
                                    <label for="uxgallery_lightbox_slideshow_auto_new">Slideshow autostart
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Check to start slideshow automatically.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="hidden" value="false" name="params[uxgallery_lightbox_slideshow_auto_new]"/>
                                    <input type="checkbox"
                                           id="uxgallery_lightbox_slideshow_auto_new" <?php if (get_option('uxgallery_lightbox_slideshow_auto_new') == 'true') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_lightbox_slideshow_auto_new]" value="true"/>
                                </div>
                                <div class="has-background">
                                    <label for="uxgallery_lightbox_slideshow_speed_new">Slideshow interval
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Set the time between the sliding in milliseconds.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="number" name="params[uxgallery_lightbox_slideshow_speed_new]"
                                           id="uxgallery_lightbox_slideshow_speed_new"
                                           value="<?php echo get_option('uxgallery_lightbox_slideshow_speed_new'); ?>"
                                           class="text">
                                    <span>ms</span>
                                </div>
                            </div>
                            <div class="">
                                <h3>Social Share Buttons</h3>
                                <div class="has-background">
                                    <label for="uxgallery_lightbox_socialSharing">Social Share Buttons
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Check to activate social sharing buttons.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="hidden" value="false" name="params[uxgallery_lightbox_socialSharing]"/>
                                    <input type="checkbox"
                                           id="uxgallery_lightbox_socialSharing" <?php if (get_option('uxgallery_lightbox_socialSharing') == 'true') {
                                        echo 'checked="checked"';
                                    } ?> name="params[uxgallery_lightbox_socialSharing]" value="true"/>
                                </div>
                                <div class="social-buttons-list has-height">
                                    <label>Social Share Buttons List
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Check to activate your preferable ones.</p>
                                            </div>
                                        </div>
                                    </label>
                                    <div>
                                        <table>
                                            <tr>
                                                <td>
                                                    <label for="uxgallery_lightbox_facebookButton">Facebook
                                                        <input type="checkbox"
                                                               id="uxgallery_lightbox_facebookButton" <?php if (get_option('uxgallery_lightbox_facebookButton') == 'true') {
                                                            echo 'checked="checked"';
                                                        } ?> name="share_params[uxgallery_lightbox_facebookButton]"
                                                               value="true"/></label>
                                                </td>
                                                <td>
                                                    <label for="uxgallery_lightbox_twitterButton">Twitter
                                                        <input type="checkbox"
                                                               id="uxgallery_lightbox_twitterButton" <?php if (get_option('uxgallery_lightbox_twitterButton') == 'true') {
                                                            echo 'checked="checked"';
                                                        } ?> name="share_params[uxgallery_lightbox_twitterButton]"
                                                               value="true"/></label>
                                                </td>
                                                <td>
                                                    <label for="uxgallery_lightbox_googleplusButton">Google Plus
                                                        <input type="checkbox"
                                                               id="uxgallery_lightbox_googleplusButton" <?php if (get_option('uxgallery_lightbox_googleplusButton') == 'true') {
                                                            echo 'checked="checked"';
                                                        } ?> name="share_params[uxgallery_lightbox_googleplusButton]"
                                                               value="true"/></label>
                                                </td>
                                                <td>
                                                    <label for="uxgallery_lightbox_pinterestButton">Pinterest
                                                        <input type="checkbox"
                                                               id="uxgallery_lightbox_pinterestButton" <?php if (get_option('uxgallery_lightbox_pinterestButton') == 'true') {
                                                            echo 'checked="checked"';
                                                        } ?> name="share_params[uxgallery_lightbox_pinterestButton]"
                                                               value="true"/></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label for="uxgallery_lightbox_linkedinButton">Linkedin
                                                        <input type="checkbox"
                                                               id="uxgallery_lightbox_linkedinButton" <?php if (get_option('uxgallery_lightbox_linkedinButton') == 'true') {
                                                            echo 'checked="checked"';
                                                        } ?> name="share_params[uxgallery_lightbox_linkedinButton]"
                                                               value="true"/></label>
                                                </td>
                                                <td>
                                                    <label for="uxgallery_lightbox_tumblrButton">Tumblr
                                                        <input type="checkbox"
                                                               id="uxgallery_lightbox_tumblrButton" <?php if (get_option('uxgallery_lightbox_tumblrButton') == 'true') {
                                                            echo 'checked="checked"';
                                                        } ?> name="share_params[uxgallery_lightbox_tumblrButton]"
                                                               value="true"/></label>
                                                </td>
                                                <td>
                                                    <label for="uxgallery_lightbox_redditButton">Reddit
                                                        <input type="checkbox"
                                                               id="uxgallery_lightbox_redditButton" <?php if (get_option('uxgallery_lightbox_redditButton') == 'true') {
                                                            echo 'checked="checked"';
                                                        } ?> name="share_params[uxgallery_lightbox_redditButton]"
                                                               value="true"/></label>
                                                </td>
                                                <td>
                                                    <label for="uxgallery_lightbox_bufferButton">Buffer
                                                        <input type="checkbox"
                                                               id="uxgallery_lightbox_bufferButton" <?php if (get_option('uxgallery_lightbox_bufferButton') == 'true') {
                                                            echo 'checked="checked"';
                                                        } ?> name="share_params[uxgallery_lightbox_bufferButton]"
                                                               value="true"/></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label for="uxgallery_lightbox_vkButton">Vkontakte
                                                        <input type="checkbox"
                                                               id="uxgallery_lightbox_vkButton" <?php if (get_option('uxgallery_lightbox_vkButton') == 'true') {
                                                            echo 'checked="checked"';
                                                        } ?> name="share_params[uxgallery_lightbox_vkButton]" value="true"/></label>
                                                </td>
                                                <td>
                                                    <label for="uxgallery_lightbox_yummlyButton">Yumly
                                                        <input type="checkbox"
                                                               id="uxgallery_lightbox_yummlyButton" <?php if (get_option('uxgallery_lightbox_yummlyButton') == 'true') {
                                                            echo 'checked="checked"';
                                                        } ?> name="share_params[uxgallery_lightbox_yummlyButton]"
                                                               value="true"/></label>
                                                </td>
                                                <td>
                                                    <label for="uxgallery_lightbox_diggButton">Digg
                                                        <input type="checkbox"
                                                               id="uxgallery_lightbox_diggButton" <?php if (get_option('uxgallery_lightbox_diggButton') == 'true') {
                                                            echo 'checked="checked"';
                                                        } ?> name="share_params[uxgallery_lightbox_diggButton]"
                                                               value="true"/></label>
                                                </td>
                                                <td>

                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                    </li>

                    <li id="lightbox-options-list"
                         class="unique-type-options-wrapper <?php if (get_option('uxgallery_lightbox_type') == 'old_type') {
                             echo "active";
                         } ?>">
                        <span class="content_heading">Classic Style</span>
                        <div class="">
                            <h3><?php echo __('Internationalization', 'gallery-img'); ?></h3>
                            <?php include_once(ABSPATH . 'wp-admin/includes/plugin.php');
                            if (!(is_plugin_active('lightbox/lightbox.php'))) {
                                ?>
                                <div class="has-background">
                                    <label for="light_box_style"><?php echo __('Lightbox style', 'gallery-img'); ?>
                                        <div class="help">?
                                            <div class="help-block">
                                                <span class="pnt"></span>
                                                <p>Check to choose your lightbox style</p>
                                            </div>
                                        </div>
                                    </label>
                                    <select id="light_box_style" name="params[uxgallery_light_box_style]">
                                        <option <?php if (get_option('uxgallery_light_box_style') == '1') {
                                            echo 'selected="selected"';
                                        } ?> value="1">1
                                        </option>
                                        <option <?php if (get_option('uxgallery_light_box_style') == '2') {
                                            echo 'selected="selected"';
                                        } ?> value="2">2
                                        </option>
                                        <option <?php if (get_option('uxgallery_light_box_style') == '3') {
                                            echo 'selected="selected"';
                                        } ?> value="3">3
                                        </option>
                                        <option <?php if (get_option('uxgallery_light_box_style') == '4') {
                                            echo 'selected="selected"';
                                        } ?> value="4">4
                                        </option>
                                        <option <?php if (get_option('uxgallery_light_box_style') == '5') {
                                            echo 'selected="selected"';
                                        } ?> value="5">5
                                        </option>
                                    </select>
                                </div>
                            <?php } ?>
                            <div>
                                <label
                                        for="light_box_transition"><?php echo __('Transition type', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check light box transition type</p>
                                        </div>
                                    </div>
                                </label>
                                <select id="light_box_transition" name="params[uxgallery_light_box_transition]">
                                    <option <?php if (get_option('uxgallery_light_box_transition') == 'elastic') {
                                        echo 'selected="selected"';
                                    } ?> value="elastic"><?php echo __('Elastic', 'gallery-img'); ?></option>
                                    <option <?php if (get_option('uxgallery_light_box_transition') == 'fade') {
                                        echo 'selected="selected"';
                                    } ?> value="fade"><?php echo __('Fade', 'gallery-img'); ?></option>
                                    <option <?php if (get_option('uxgallery_light_box_transition') == 'none') {
                                        echo 'selected="selected"';
                                    } ?> value="none"><?php echo __('none', 'gallery-img'); ?></option>
                                </select>
                            </div>
                            <div class="has-background">
                                <label for="light_box_speed"><?php echo __('Opening speed', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check the opening speed in ms</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="number" name="params[uxgallery_light_box_speed]" id="light_box_speed"
                                       value="<?php echo get_option('uxgallery_light_box_speed'); ?>" class="text">
                                <span><?php echo __('ms', 'gallery-img'); ?></span>
                            </div>
                            <div>
                                <label for="light_box_fadeout"><?php echo __('Closing speed', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check light box closing speed in ms</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="number" name="params[uxgallery_light_box_fadeout]" id="light_box_fadeout"
                                       value="<?php echo get_option('uxgallery_light_box_fadeout'); ?>" class="text">
                                <span><?php echo __('ms', 'gallery-img'); ?></span>
                            </div>
                            <div class="has-background">
                                <label for="light_box_title"><?php echo __('Show the title', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Choose whether to show the title</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="hidden" value="false" name="params[uxgallery_light_box_title]"/>
                                <input type="checkbox"
                                       id="light_box_title" <?php if (get_option('uxgallery_light_box_title') == 'true') {
                                    echo 'checked="checked"';
                                } ?> name="params[uxgallery_light_box_title]" value="true"/>
                            </div>
                            <div>
                                <label
                                        for="light_box_opacity"><?php echo __('Overlay Opacity', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check overlay opacity level</p>
                                        </div>
                                    </div>
                                </label>
                                <div class="slider-container">
                                    <input name="params[uxgallery_light_box_opacity]" id="light_box_opacity"
                                           data-slider-highlight="true"
                                           data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text" data-slider="true"
                                           value="<?php echo get_option('uxgallery_light_box_opacity'); ?>"/>
                                    <span><?php echo get_option('uxgallery_light_box_opacity'); ?>%</span>
                                </div>
                            </div>
                            <div class="has-background">
                                <label for="light_box_open"><?php echo __('Auto open', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check to auto open lightbox</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="hidden" value="false" name="params[uxgallery_light_box_open]"/>
                                <input type="checkbox"
                                       id="light_box_open" <?php if (get_option('uxgallery_light_box_open') == 'true') {
                                    echo 'checked="checked"';
                                } ?> name="params[uxgallery_light_box_open]" value="true"/>
                            </div>
                            <div>
                                <label
                                        for="light_box_overlayclose"><?php echo __('Overlay close', 'gallery-img'); ?><?php if (get_option('uxgallery_light_box_overlayclose')) {
                                        echo "true";
                                    } ?></label>
                                <input type="hidden" value="false" name="params[uxgallery_light_box_overlayclose]"/>
                                <input type="checkbox"
                                       id="light_box_overlayclose" <?php if (get_option('uxgallery_light_box_overlayclose') == 'true') {
                                    echo 'checked="checked"';
                                } ?> name="params[uxgallery_light_box_overlayclose]" value="true"/>
                            </div>
                            <div class="has-background">
                                <label for="light_box_esckey"><?php echo __('EscKey close', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check to enable Esc close</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="hidden" value="false" name="params[uxgallery_light_box_esckey]"/>
                                <input type="checkbox"
                                       id="light_box_esckey" <?php if (get_option('uxgallery_light_box_esckey') == 'true') {
                                    echo 'checked="checked"';
                                } ?> name="params[uxgallery_light_box_esckey]" value="true"/>
                            </div>
                            <div>
                                <label
                                        for="light_box_arrowkey"><?php echo __('Keyboard navigation', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check to enable keyboard navigation</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="hidden" value="false" name="params[uxgallery_light_box_arrowkey]"/>
                                <input type="checkbox"
                                       id="light_box_arrowkey" <?php if (get_option('uxgallery_light_box_arrowkey') == 'true') {
                                    echo 'checked="checked"';
                                } ?> name="params[uxgallery_light_box_arrowkey]" value="true"/>
                            </div>
                            <div class="has-background">
                                <label for="light_box_loop"><?php echo __('Loop content', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check to enable content loop</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="hidden" value="false" name="params[uxgallery_light_box_loop]"/>
                                <input type="checkbox"
                                       id="light_box_loop" <?php if (get_option('uxgallery_light_box_loop') == 'true') {
                                    echo 'checked="checked"';
                                } ?> name="params[uxgallery_light_box_loop]" value="true"/>
                            </div>
                            <div>
                                <label
                                        for="light_box_closebutton"><?php echo __('Show close button', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check to show close button</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="hidden" value="false" name="params[uxgallery_light_box_closebutton]"/>
                                <input type="checkbox"
                                       id="light_box_closebutton" <?php if (get_option('uxgallery_light_box_closebutton') == 'true') {
                                    echo 'checked="checked"';
                                } ?> name="params[uxgallery_light_box_closebutton]" value="true"/>
                            </div>
                        </div>
                        <div class="lightbox-options-block">
                            <h3><?php echo __('Dimensions', 'gallery-img'); ?></h3>

                            <div class="has-background">
                                <label for="light_box_size_fix"><?php echo __('Popup size fix', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check to make popup size fixed</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="hidden" value="false" name="params[uxgallery_light_box_size_fix]"/>
                                <input type="checkbox"
                                       id="light_box_size_fix" <?php if (get_option('uxgallery_light_box_size_fix') == 'true') {
                                    echo 'checked="checked"';
                                } ?> name="params[uxgallery_light_box_size_fix]" value="true"/>
                            </div>

                            <div class="fixed-size">
                                <label for="light_box_width"><?php echo __('Popup width', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check lightbox width in px</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="number" name="params[uxgallery_light_box_width]" id="light_box_width"
                                       value="<?php echo get_option('uxgallery_light_box_width'); ?>" class="text">
                                <span>px</span>
                            </div>

                            <div class="has-background fixed-size">
                                <label for="light_box_height"><?php echo __('Popup height', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check lightbox height in px</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="number" name="params[uxgallery_light_box_height]" id="light_box_height"
                                       value="<?php echo get_option('uxgallery_light_box_height'); ?>" class="text">
                                <span>px</span>
                            </div>

                            <div class="not-fixed-size">
                                <label for="light_box_maxwidth"><?php echo __('Popup maxWidth', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check lightbox maximum width in px</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="number" name="params[uxgallery_light_box_maxwidth]" id="light_box_maxwidth"
                                       value="<?php echo get_option('uxgallery_light_box_maxwidth'); ?>" class="text">
                                <span>px</span>
                            </div>
                            <div class="has-background not-fixed-size">
                                <label for="light_box_maxheight"><?php echo __('Popup maxHeight', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check lightbox maximum height in px</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="number" name="params[uxgallery_light_box_maxheight]" id="light_box_maxheight"
                                       value="<?php echo get_option('uxgallery_light_box_maxheight'); ?>" class="text">
                                <span>px</span>
                            </div>
                            <div>
                                <label
                                        for="light_box_initialwidth"><?php echo __('Popup initial width', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check lightbox initial width in px</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="number" name="params[uxgallery_light_box_initialwidth]" id="light_box_initialwidth"
                                       value="<?php echo get_option('uxgallery_light_box_initialwidth'); ?>" class="text">
                                <span>px</span>
                            </div>
                            <div class="has-background">
                                <label
                                        for="light_box_initialheight"><?php echo __('Popup initial height', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check lightbox initial height in px</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="number" name="params[uxgallery_light_box_initialheight]" id="light_box_initialheight"
                                       value="<?php echo get_option('uxgallery_light_box_initialheight'); ?>" class="text">
                                <span>px</span>
                            </div>
                        </div>
                        <div class="lightbox-options-block">
                            <h3><?php echo __('Slideshow', 'gallery-img'); ?></h3>

                            <div class="has-background">
                                <label for="light_box_slideshow"><?php echo __('Slideshow', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check to enable slideshow</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="hidden" value="false" name="params[uxgallery_light_box_slideshow]"/>
                                <input type="checkbox"
                                       id="light_box_slideshow" <?php if (get_option('uxgallery_light_box_slideshow') == 'true') {
                                    echo 'checked="checked"';
                                } ?> name="params[uxgallery_light_box_slideshow]" value="true"/>
                            </div>
                            <div>
                                <label
                                        for="light_box_slideshowspeed"><?php echo __('Slideshow interval', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check slideshow speed in ms</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="number" name="params[uxgallery_light_box_slideshowspeed]" id="light_box_slideshowspeed"
                                       value="<?php echo get_option('uxgallery_light_box_slideshowspeed'); ?>" class="text">
                                <span><?php echo __('ms', 'gallery-img'); ?></span>
                            </div>
                            <div class="has-background">
                                <label
                                        for="light_box_slideshowauto"><?php echo __('Slideshow auto start', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check to set slideshow auto</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="hidden" value="false" name="params[uxgallery_light_box_slideshowauto]"/>
                                <input type="checkbox"
                                       id="light_box_slideshowauto" <?php if (get_option('uxgallery_light_box_slideshowauto') == 'true') {
                                    echo 'checked="checked"';
                                } ?> name="params[uxgallery_light_box_slideshowauto]" value="true"/>
                            </div>
                            <div>
                                <label
                                        for="light_box_slideshowstart"><?php echo __('Slideshow start button text', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check slideshow start button text</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="text" name="params[uxgallery_light_box_slideshowstart]" id="light_box_slideshowstart"
                                       value="<?php echo esc_attr(get_option('uxgallery_light_box_slideshowstart')); ?>" class="text">
                            </div>
                            <div class="has-background">
                                <label
                                        for="light_box_slideshowstop"><?php echo __('Slideshow stop button text', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check slideshow stop button text</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="text" name="params[uxgallery_light_box_slideshowstop]" id="light_box_slideshowstop"
                                       value="<?php echo esc_attr(get_option('uxgallery_light_box_slideshowstop')); ?>" class="text">
                            </div>
                        </div>
                        <div class="lightbox-options-block">
                            <h3><?php echo __('Positioning', 'gallery-img'); ?></h3>

                            <div class="has-background">
                                <label for="light_box_fixed"><?php echo __('Fixed position', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check to set slideshow position fixed</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="hidden" value="false" name="params[uxgallery_light_box_fixed]"/>
                                <input type="checkbox"
                                       id="light_box_fixed" <?php if (get_option('uxgallery_light_box_fixed') == 'true') {
                                    echo 'checked="checked"';
                                } ?> name="params[uxgallery_light_box_fixed]" value="true"/>
                            </div>
                            <div class="has-height">
                                <label for=""><?php echo __('Popup position', 'gallery-img'); ?>
                                    <div class="help">?
                                        <div class="help-block">
                                            <span class="pnt"></span>
                                            <p>Check slideshow position</p>
                                        </div>
                                    </div>
                                </label>
                                <div>
                                    <table class="bws_position_table">
                                        <tbody>
                                        <tr>
                                            <td><input type="radio" value="1" id="slideshow_title_top-left"
                                                       name="params[uxgallery_lightbox_open_position]" <?php if (get_option('uxgallery_lightbox_open_position') == '1') {
                                                    echo 'checked="checked"';
                                                } ?> /></td>
                                            <td><input type="radio" value="2" id="slideshow_title_top-center"
                                                       name="params[uxgallery_lightbox_open_position]" <?php if (get_option('uxgallery_lightbox_open_position') == '2') {
                                                    echo 'checked="checked"';
                                                } ?> /></td>
                                            <td><input type="radio" value="3" id="slideshow_title_top-right"
                                                       name="params[uxgallery_lightbox_open_position]" <?php if (get_option('uxgallery_lightbox_open_position') == '3') {
                                                    echo 'checked="checked"';
                                                } ?> /></td>
                                        </tr>
                                        <tr>
                                            <td><input type="radio" value="4" id="slideshow_title_middle-left"
                                                       name="params[uxgallery_lightbox_open_position]" <?php if (get_option('uxgallery_lightbox_open_position') == '4') {
                                                    echo 'checked="checked"';
                                                } ?> /></td>
                                            <td><input type="radio" value="5" id="slideshow_title_middle-center"
                                                       name="params[uxgallery_lightbox_open_position]" <?php if (get_option('uxgallery_lightbox_open_position') == '5') {
                                                    echo 'checked="checked"';
                                                } ?> /></td>
                                            <td><input type="radio" value="6" id="slideshow_title_middle-right"
                                                       name="params[uxgallery_lightbox_open_position]" <?php if (get_option('uxgallery_lightbox_open_position') == '6') {
                                                    echo 'checked="checked"';
                                                } ?> /></td>
                                        </tr>
                                        <tr>
                                            <td><input type="radio" value="7" id="slideshow_title_bottom-left"
                                                       name="params[uxgallery_lightbox_open_position]" <?php if (get_option('uxgallery_lightbox_open_position') == '7') {
                                                    echo 'checked="checked"';
                                                } ?> /></td>
                                            <td><input type="radio" value="8" id="slideshow_title_bottom-center"
                                                       name="params[uxgallery_lightbox_open_position]" <?php if (get_option('uxgallery_lightbox_open_position') == '8') {
                                                    echo 'checked="checked"';
                                                } ?> /></td>
                                            <td><input type="radio" value="9" id="slideshow_title_bottom-right"
                                                       name="params[uxgallery_lightbox_open_position]" <?php if (get_option('uxgallery_lightbox_open_position') == '9') {
                                                    echo 'checked="checked"';
                                                } ?> /></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="footer_save_wrapper">
                    <a onclick="document.getElementById('adminForm').submit()"
                       class="save-gallery-options button-primary"><?php echo __('Save Options', 'gallery-img'); ?></a>
                    <div class="clear"></div>
                </div>
                <div style="clear:both"></div>
            </div>
        </form>
    </div>
    </div>
</div>

<input type="hidden" name="option" value=""/>
<input type="hidden" name="task" value=""/>
<input type="hidden" name="controller" value="options"/>
<input type="hidden" name="op_type" value="styles"/>
<input type="hidden" name="boxchecked" value="0"/>