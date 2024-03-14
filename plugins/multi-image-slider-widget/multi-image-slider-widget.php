<?php
/*
 * Plugin Name:		Multi Image Slider Widget
 * Plugin URI: 		http://www.pmydigital.com
 * Description: 	Display Multi Images from your Media Library in a Modern Responsive Slider. 
 * Author: 			PMYDigital
 * Version: 		1.0
 * Author URI: 		http://www.pmydigital.com
 * License: 		GPLv2 or later
 * License URI:		http://www.gnu.org/licenses/gpl-2.0.html
 * Last Updated : 	17/5/2015
 */
?>
<?php

/**
 * Adds Multi Image Slider Widget.
 */
class multi_image_slider_widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
                'multi-image-slider-widget-id', // Widget Base ID
                __('Multi Image Slider Widget', 'Multi_Image_Slider_Widget'), // Widget Name
                array('description' => __('Display Multi Images from your Media Library in a Modern Responsive Slider.', 'Multi_Image_Slider_Widget'),) // Widget Description
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        extract($args);

        /* Get the default values. */
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $show_image_title = isset($instance['show_image_title']) ? (bool) $instance['show_image_title'] : true;
        $show_image_caption = isset($instance['show_image_caption']) ? (bool) $instance['show_image_caption'] : true;
        $show_image_desc = isset($instance['show_image_desc']) ? (bool) $instance['show_image_desc'] : true;
        $autoplay_slider = isset($instance['autoplay_slider']) ? (bool) $instance['autoplay_slider'] : true;
        $autoplay_delay = isset($instance['autoplay_delay']) ? esc_attr($instance['autoplay_delay']) : '5000';
        $slider_pager = isset($instance['slider_pager']) ? (bool) $instance['slider_pager'] : true;
        $shuffle_images = isset($instance['shuffle_images']) ? (bool) $instance['shuffle_images'] : false;
        $image_fade = isset($instance['image_fade']) ? (bool) $instance['image_fade'] : false;
        $fade_delay = isset($instance['fade_delay']) ? esc_attr($instance['fade_delay']) : '500';
        $slider_arrow = isset($instance['slider_arrow']) ? (bool) $instance['slider_arrow'] : false;
        $lightbox_effect = isset($instance['lightbox_effect']) ? (bool) $instance['lightbox_effect'] : true;
        $order_by = isset($instance['order_by']) ? esc_attr($instance['order_by']) : '';
        $order_param = isset($instance['order_param']) ? esc_attr($instance['order_param']) : '';

        /* Open the before widget HTML. */
        echo $before_widget;

        /* Widget title. */
        if ($title) :
            echo $before_title;
            echo $title;
            echo $after_title;
        endif;
        ?>

        <?php /* Hide Slider Before Load Script */ ?>
        <style type="text/css">
            .misw-slider-<?php echo $this->id; ?> {
                display:none;
            }
        </style>

        <?php /* Load Slider and Settings. */ ?>
        <script type="text/javascript">
            (function ($) {
                "use strict";
                $(window).load(function () {
                    $('.misw-slider-<?php echo $this->id; ?>').show().sliderPro({
                        loop: false,
                        autoplay: <?php
        if ($autoplay_slider) : echo 'true';
        else : echo 'false';
        endif;
        ?>,
                        autoplayDelay: <?php echo $autoplay_delay; ?>,
                        buttons: <?php
        if ($slider_pager) : echo 'true';
        else : echo 'false';
        endif;
        ?>,
                        arrows: <?php
        if ($slider_arrow) : echo 'false';
        else : echo 'true';
        endif;
        ?>,
                        autoHeight: true,
                        fullScreen: false,
                        shuffle: <?php
        if ($shuffle_images) : echo 'true';
        else : echo 'false';
        endif;
        ?>,
                        fade: <?php
        if ($image_fade) : echo 'true';
        else : echo 'false';
        endif;
        ?>,
                        fadeDuration: <?php echo $fade_delay; ?>
                    });
                });
            })(jQuery);
        </script>

        <?php
        if (is_array($instance['thumbnail'])) :
            ?>
            <div class="misw-slider-container">
                <div class="slider-pro misw-slider-<?php echo $this->id; ?>">
                    <div class="sp-slides">                        						
                        <?php
                        /* Identify Checked Checkboxes from Backend using WP Query */
                        $args = array(
                            'include' => $instance['thumbnail'],
                            'post_type' => 'attachment',
                            'post_mime_type' => array(
                                'image/jpeg',
                                'image/png',
                                'image/gif'
                            ),
                            'posts_per_page' => -1,
                            'post_status' => 'any',
                            'orderby' => '' . $order_by . '',
                            'order' => '' . $order_param . '',
                        );
                        $thumb_images = get_posts($args);
                        foreach ($thumb_images as $thumb_image) :
                            $image_url = wp_get_attachment_url($thumb_image->ID);
                            ?>			
                            <div class="sp-slide">

                                <?php /* Show Image Title */ ?>
                                <?php if ($show_image_title && $thumb_image->post_title): ?>
                                    <div class="misw-slider-image-title">
                                        <?php echo $thumb_image->post_title; ?>
                                    </div>
                                <?php endif; ?>

                                <?php /* Show Image and Lightbox */ ?>
                                <div class="misw-slider-image">
                                    <a href="<?php echo $image_url ?>" 
                                    <?php
                                    if ($lightbox_effect) : echo '';
                                    else : echo 'data-lightbox="misw" data-title="' . $thumb_image->post_excerpt . '"';
                                    endif;
                                    ?>><img src="<?php echo $image_url; ?>" alt="<?php echo $thumb_image->post_title; ?>"/></a>
                                </div>

                                <?php /* Show Image Caption */ ?>
                                <?php if ($show_image_caption && $thumb_image->post_excerpt): ?>
                                    <div class="misw-slider-image-caption">
                                        <?php echo $thumb_image->post_excerpt; ?>
                                    </div>
                                <?php endif; ?>

                                <?php /* Show Image Description */ ?>
                                <?php if ($show_image_desc && $thumb_image->post_content): ?>
                                    <div class="misw-slider-image-description">
                                        <?php echo $thumb_image->post_content; ?>
                                    </div>
                                <?php endif; ?>
                            </div>				
                            <?php
                        endforeach;
                        ?>
                    </div>
                </div>
            </div>			
            <?php
        else :
            /* If no images, show message to admin and visitors */
            if (is_home() && current_user_can('publish_posts')) :
                ?>
                <div class="misw-slider-image-noimage">            
                    <?php printf(__('No Images To Display. <a href="%1$s">Select Images</a> or <a href="%2$s">Upload Images</a>.', 'Multi_Image_Slider_Widget'), esc_url(admin_url('widgets.php')), esc_url(admin_url('media-new.php'))); ?>
                </div>
                <?php
            else:
                ?>
                <div class="misw-slider-image-noimage">   
                    <?php printf(__('No Images To Display', 'Multi_Image_Slider_Widget')); ?>
                </div>
            <?php
            endif;
        endif;

        /* Close the after widget HTML. */
        echo $after_widget;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $show_image_title = isset($instance['show_image_title']) ? (bool) $instance['show_image_title'] : true;
        $show_image_caption = isset($instance['show_image_caption']) ? (bool) $instance['show_image_caption'] : true;
        $show_image_desc = isset($instance['show_image_desc']) ? (bool) $instance['show_image_desc'] : true;
        $autoplay_slider = isset($instance['autoplay_slider']) ? (bool) $instance['autoplay_slider'] : true;
        $autoplay_delay = isset($instance['autoplay_delay']) ? esc_attr($instance['autoplay_delay']) : '5000';
        $slider_pager = isset($instance['slider_pager']) ? (bool) $instance['slider_pager'] : true;
        $shuffle_images = isset($instance['shuffle_images']) ? (bool) $instance['shuffle_images'] : false;
        $image_fade = isset($instance['image_fade']) ? (bool) $instance['image_fade'] : false;
        $fade_delay = isset($instance['fade_delay']) ? esc_attr($instance['fade_delay']) : '500';
        $slider_arrow = isset($instance['slider_arrow']) ? (bool) $instance['slider_arrow'] : false;
        $checkbox_all = isset($instance['checkbox_all']) ? (bool) $instance['checkbox_all'] : false;
        $lightbox_effect = isset($instance['lightbox_effect']) ? (bool) $instance['lightbox_effect'] : true;
        if ($instance) {
            $order_by = esc_attr($instance['order_by']);
            $order_param = esc_attr($instance['order_param']);
        } else {
            $order_by = 'Date';
            $order_param = 'DESC';
        }
        ?>

        <?php /* Check or Uncheck all checkboxes JQuery Script. */ ?>
        <script type="text/javascript">
            (function ($) {
                "use strict";
                $(document).ready(function () {
                    $('.misw-checkbox-all').click(function (event) {
                        if (this.checked) {
                            $('.misw-checkbox').each(function () {
                                this.checked = true;
                            });
                        } else {
                            $('.misw-checkbox').each(function () {
                                this.checked = false;
                            });
                        }
                    });

                });
            })(jQuery);
        </script>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'Multi_Image_Slider_Widget'); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
        </p>
        <h4>
            <?php printf(__('Slider Settings:', 'Multi_Image_Slider_Widget')); ?>
        </h4>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_image_title); ?> id="<?php echo $this->get_field_id('show_image_title'); ?>" name="<?php echo $this->get_field_name('show_image_title'); ?>" />
            <label for="<?php echo $this->get_field_id('show_image_title'); ?>"><?php _e('Display Image Title', 'Multi_Image_Slider_Widget'); ?></label> 
        </p>		
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_image_caption); ?> id="<?php echo $this->get_field_id('show_image_caption'); ?>" name="<?php echo $this->get_field_name('show_image_caption'); ?>" />
            <label for="<?php echo $this->get_field_id('show_image_caption'); ?>"><?php _e('Display Image Caption', 'Multi_Image_Slider_Widget'); ?></label> 
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_image_desc); ?> id="<?php echo $this->get_field_id('show_image_desc'); ?>" name="<?php echo $this->get_field_name('show_image_desc'); ?>" />
            <label for="<?php echo $this->get_field_id('show_image_desc'); ?>"><?php _e('Display Image Description', 'Multi_Image_Slider_Widget'); ?></label> 
        </p>		
        <p>
            <input class="checkbox" type="checkbox" <?php checked($autoplay_slider); ?> id="<?php echo $this->get_field_id('autoplay_slider'); ?>" name="<?php echo $this->get_field_name('autoplay_slider'); ?>" />
            <label for="<?php echo $this->get_field_id('autoplay_slider'); ?>"><?php _e('Enable Autoplay Slider', 'Multi_Image_Slider_Widget'); ?></label> 
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('autoplay_delay'); ?>"><?php _e('Sets the delay at which the autoplay will run (1000 = 1second):', 'Multi_Image_Slider_Widget'); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('autoplay_delay'); ?>" name="<?php echo $this->get_field_name('autoplay_delay'); ?>" value="<?php echo $autoplay_delay; ?>" />
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($slider_pager); ?> id="<?php echo $this->get_field_id('slider_pager'); ?>" name="<?php echo $this->get_field_name('slider_pager'); ?>" />
            <label for="<?php echo $this->get_field_id('slider_pager'); ?>"><?php _e('Display Slider Pager', 'Multi_Image_Slider_Widget'); ?></label> 
        </p>
        <h4>
            <?php printf(__('Advance Slider Settings:', 'Multi_Image_Slider_Widget')); ?>
        </h4>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($shuffle_images); ?> id="<?php echo $this->get_field_id('shuffle_images'); ?>" name="<?php echo $this->get_field_name('shuffle_images'); ?>" />
            <label for="<?php echo $this->get_field_id('shuffle_images'); ?>"><?php _e('Shuffle Images', 'Multi_Image_Slider_Widget'); ?></label> 
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($image_fade); ?> id="<?php echo $this->get_field_id('image_fade'); ?>" name="<?php echo $this->get_field_name('image_fade'); ?>" />
            <label for="<?php echo $this->get_field_id('image_fade'); ?>"><?php _e('Enable Image Fade', 'Multi_Image_Slider_Widget'); ?></label> 
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('fade_delay'); ?>"><?php _e('Sets the fade delay duration (Default: 500):', 'Multi_Image_Slider_Widget'); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('fade_delay'); ?>" name="<?php echo $this->get_field_name('fade_delay'); ?>" value="<?php echo $fade_delay; ?>" />
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($slider_arrow); ?> id="<?php echo $this->get_field_id('slider_arrow'); ?>" name="<?php echo $this->get_field_name('slider_arrow'); ?>" />
            <label for="<?php echo $this->get_field_id('slider_arrow'); ?>"><?php _e('Remove Slider Arrow', 'Multi_Image_Slider_Widget'); ?></label> 
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($lightbox_effect); ?> id="<?php echo $this->get_field_id('lightbox_effect'); ?>" name="<?php echo $this->get_field_name('lightbox_effect'); ?>" />
            <label for="<?php echo $this->get_field_id('lightbox_effect'); ?>"><?php _e('Disable LightBox Effect', 'Multi_Image_Slider_Widget'); ?></label> 
        </p>
        <p>
            <label><?php _e('Order Image By', 'Multi_Image_Slider_Widget'); ?></label>
            <select name="<?php echo $this->get_field_name('order_by'); ?>" id="<?php echo $this->get_field_id('order_by'); ?>" class="widefat">
                <?php
                $options = array('Date', 'Title');
                foreach ($options as $option) :
                    ?>
                    <option value="<?php echo $option ?>" id="<?php echo $option ?>" <?php
                    if ($order_by == $option) : echo 'selected="selected"';
                    else: echo '';
                    endif;
                    ?>><?php echo $option ?></option>
                <?php endforeach;
                ?>
            </select>	
        </p>

        <p>
            <label><?php _e('Ascending(ASC) or Decending(DESC) Order', 'Multi_Image_Slider_Widget'); ?></label>
            <select name="<?php echo $this->get_field_name('order_param'); ?>" id="<?php echo $this->get_field_id('order_param'); ?>" class="widefat">
                <?php
                $options = array('ASC', 'DESC');
                foreach ($options as $option) :
                    ?>				
                    <option value="<?php echo $option ?>" id="<?php echo $option ?>" <?php
                    if ($order_param == $option) : echo 'selected="selected"';
                    else: echo '';
                    endif;
                    ?>><?php echo $option ?></option>
                        <?php endforeach;
                        ?>
            </select>		 
        </p>

        <?php
        $query = array(
            'post_type' => 'attachment',
            'post_mime_type' => array(
                'image/jpeg',
                'image/png',
                'image/gif'
            ),
            'posts_per_page' => -1,
            'post_status' => 'any',
            'orderby' => 'date',
            'order' => 'DESC',
        );
        $attachments = get_posts($query);
        if ($attachments) :
            ?>
            <p>
                <?php printf(__('Select Images or <a href="%1$s">Upload Images</a>', 'Multi_Image_Slider_Widget'), esc_url(admin_url('media-new.php'))); ?>
            </p>
            <?php
            $thumbnail = '';
            if (!empty($instance['thumbnail'])) {
                $thumbnail = (array) $instance['thumbnail'];
            }
            ?>
            <p>
            <div class="misw-media">
                <table>  
                    <tr>
                        <th class="misw-table-title"><label for="<?php echo $this->get_field_id('checkbox_all'); ?>"><?php _e('Select All:', 'Multi_Image_Slider_Widget'); ?></label></th>
                        <th><input class="misw-checkbox-all" <?php checked($checkbox_all); ?> type="checkbox" id="<?php echo $this->get_field_id('checkbox_all'); ?>" name="<?php echo $this->get_field_name('checkbox_all'); ?>" /></th>
                    </tr>
                    <?php
                    foreach ($attachments as $image) :
                        $images = wp_get_attachment_url($image->ID);
                        ?>
                        <tr>
                            <td class="misw-image-container">							
                                <label for="<?php echo $this->get_field_id($image->ID) ?>">
                                    <div class="misw-image-title"><?php echo get_the_title($image->ID); ?></div>
                                    <div class="misw-image"><img src="<?php echo $images; ?>"/></div>
                                </label>                            
                            </td>				

                            <td class="misw-checkbox-container">
                                <div class="misw-checkbox">
                                    <input type="checkbox" 
                                           id="<?php echo $this->get_field_id($image->ID) ?>" class="misw-checkbox"
                                           name="<?php echo $this->get_field_name('thumbnail') ?>[]"value="<?php echo $image->ID ?>" <?php
                                           if (is_array($thumbnail) && in_array($image->ID, $thumbnail)) {
                                               if (in_array($image->ID, $thumbnail)) {
                                                   echo 'checked="checked"';
                                               }
                                           }
                                           ?>/>	
                                </div>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                </table>
            </div> 
            </p>
            <?php
        else:
            ?>
            <p>
                <?php printf(__('No Images Found in Media Library. <a href="%1$s">Upload Here</a>.', 'Multi_Image_Slider_Widget'), esc_url(admin_url('media-new.php'))); ?>
            </p>
        <?php
        endif;
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;

        /* Set the instance to the new instance. */
        $instance = $new_instance;

        /* Strip tags from elements that don't need them. */
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['show_image_title'] = (bool) $new_instance['show_image_title'];
        $instance['show_image_caption'] = (bool) $new_instance['show_image_caption'];
        $instance['show_image_desc'] = (bool) $new_instance['show_image_desc'];
        $instance['autoplay_slider'] = (bool) $new_instance['autoplay_slider'];
        $instance['autoplay_delay'] = strip_tags($new_instance['autoplay_delay']);
        $instance['thumbnail'] = $new_instance['thumbnail'];
        $instance['slider_pager'] = (bool) $new_instance['slider_pager'];
        $instance['shuffle_images'] = (bool) $new_instance['shuffle_images'];
        $instance['image_fade'] = (bool) $new_instance['image_fade'];
        $instance['fade_delay'] = strip_tags($new_instance['fade_delay']);
        $instance['slider_arrow'] = (bool) $new_instance['slider_arrow'];
        $instance['checkbox_all'] = (bool) $new_instance['checkbox_all'];
        $instance['lightbox_effect'] = (bool) $new_instance['lightbox_effect'];
        $instance['order_by'] = strip_tags($new_instance['order_by']);
        $instance['order_param'] = strip_tags($new_instance['order_param']);
        return $instance;
    }

}

/**
 * Enqueue Scripts
 *
 */
function multi_image_slider_widget_enqueue_scripts() {
    wp_enqueue_style('misw-style-css', plugins_url('/assets/css/style.css', __FILE__));
    wp_enqueue_script('misw-jquery.sliderPro.min', plugins_url('/assets/js/jquery.sliderPro.min.js', __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_style('misw-slider-pro.min.css', plugins_url('/assets/css/slider-pro.min.css', __FILE__));
    wp_enqueue_script('misw-lightbox.min', plugins_url('/assets/js/lightbox.min.js', __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_style('misw-lightbox.css', plugins_url('/assets/css/lightbox.css', __FILE__));
}

add_action('wp_enqueue_scripts', 'multi_image_slider_widget_enqueue_scripts');

/**
 * Enqueue Admin Scripts
 *
 */
function multi_image_slider_widget_admin_enqueue_scripts() {
    wp_enqueue_style('multi-image-slider-widget-style-css', plugins_url('/assets/css/style.css', __FILE__));
}

add_action('admin_enqueue_scripts', 'multi_image_slider_widget_admin_enqueue_scripts');

/**
 * Register the Multi Image Slider Widget.
 *
 * Hooks into the widgets_init action.
 *
 */
function multi_image_slider_widget_load() {
    register_widget('multi_image_slider_widget');
}

add_action('widgets_init', 'multi_image_slider_widget_load');