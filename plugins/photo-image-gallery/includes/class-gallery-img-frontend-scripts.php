<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Class UXGallery_Frontend_Scripts
 */
class UXGallery_Frontend_Scripts
{

    /**
     * UXGallery_Frontend_Scripts constructor.
     */
    public function __construct()
    {
        add_action('uxgallery_shortcode_scripts', array($this, 'frontend_scripts'), 10, 4);
        add_action('uxgallery_shortcode_scripts', array($this, 'frontend_styles'), 10, 2);
        add_action('uxgallery_localize_scripts', array($this, 'localize_scripts'), 10, 1);
    }

    /**
     * Enqueue styles
     */
    public function frontend_styles($id, $gallery_view)
    {
        wp_register_style('gallery-all-css', UXGallery()->plugin_url() . '/assets/style/gallery-all.css');
        wp_enqueue_style('gallery-all-css');

        wp_register_style('style2-os-css', UXGallery()->plugin_url() . '/assets/style/style2-os.css');
        wp_enqueue_style('style2-os-css');

        if (get_option('uxgallery_lightbox_type') == 'old_type') {
            wp_register_style('lightbox-css', UXGallery()->plugin_url() . '/assets/style/lightbox.css');
            wp_enqueue_style('lightbox-css');
            wp_enqueue_style('uxgallery_colorbox_css', untrailingslashit(UXGallery()->plugin_url()) . '/assets/style/colorbox-' . get_option('uxgallery_light_box_style') . '.css');
            wp_enqueue_style('uxgallery_colorbox_css');
        } elseif (get_option('uxgallery_lightbox_type') == 'new_type') {
            wp_register_style('uxgallery_resp_lightbox_css', untrailingslashit(UXGallery()->plugin_url()) . '/assets/style/responsive_lightbox.css');
            wp_enqueue_style('uxgallery_resp_lightbox_css');
        }


        wp_register_style('fontawesome-css', UXGallery()->plugin_url() . '/assets/style/css/font-awesome.css');
        wp_enqueue_style('fontawesome-css');


        if ($gallery_view == '1') {
            wp_register_style('animate-css', UXGallery()->plugin_url() . '/assets/style/animate-min.css');
            wp_enqueue_style('animate-css');
            wp_register_style('liquid-slider-css', UXGallery()->plugin_url() . '/assets/style/liquid-slider.css');
            wp_enqueue_style('liquid-slider-css');
        }
        if ($gallery_view == '4') {
            wp_register_style('thumb_view-css', UXGallery()->plugin_url() . '/assets/style/thumb_view.css');
            wp_enqueue_style('thumb_view-css');
        }
        if ($gallery_view == '6') {
            wp_register_style('thumb_view-css', UXGallery()->plugin_url() . '/assets/style/justifiedGallery.css');
            wp_enqueue_style('thumb_view-css');
        }
        if ($gallery_view == '10') {
            wp_register_style('elastic-grid-css', UXGallery()->plugin_url() . '/assets/style/elastic_grid.css');
            wp_enqueue_style('elastic-grid-css');
        }
    }

    /**
     * Enqueue scripts
     */
    public function frontend_scripts($id, $gallery_view, $has_youtube, $has_vimeo)
    {
        $view_slug = uxgallery_get_view_slag_by_id($id);

        if (!wp_script_is('jquery')) {
            wp_enqueue_script('jquery');
        }

        if (get_option('uxgallery_lightbox_type') == 'old_type') {
            wp_register_script('jquery.gicolorbox-js', UXGallery()->plugin_url() . '/assets/js/jquery.colorbox.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('jquery.gicolorbox-js');
        } elseif (get_option('uxgallery_lightbox_type') == 'new_type') {
            wp_register_script('gallery-resp-lightbox-js', UXGallery()->plugin_url() . '/assets/js/lightbox.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('gallery-resp-lightbox-js');
            wp_register_script('mousewheel-min-js', UXGallery()->plugin_url() . '/assets/js/mousewheel.min.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('mousewheel-min-js');
            wp_register_script('froogaloop2-js', UXGallery()->plugin_url() . '/assets/js/froogaloop2.min.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('froogaloop2-js');
        }

        wp_register_script('jquery.gicolorbox-js', UXGallery()->plugin_url() . '/assets/js/jquery.colorbox.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('jquery.gicolorbox-js');

        wp_register_script('gallery-uxgallerymicro-min-js', UXGallery()->plugin_url() . '/assets/js/jquery.uxgallerymicro.min.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('gallery-uxgallerymicro-min-js');

        wp_register_script('front-end-js-' . $view_slug, UXGallery()->plugin_url() . '/assets/js/view-' . $view_slug . '.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('front-end-js-' . $view_slug);

        wp_register_script('gallery-custom-js', UXGallery()->plugin_url() . '/assets/js/custom.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('gallery-custom-js');

        if ($gallery_view == '1') {
            wp_register_script('easing-js', UXGallery()->plugin_url() . '/assets/js/jquery.easing.min.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('easing-js');
            wp_register_script('touch_swipe-js', UXGallery()->plugin_url() . '/assets/js/jquery.touchSwipe.min.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('touch_swipe-js');
            wp_register_script('liquid-slider-js', UXGallery()->plugin_url() . '/assets/js/jquery.liquid-slider.min.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('liquid-slider-js');
        }
        if ($gallery_view == '4') {
            wp_register_script('thumb-view-js', UXGallery()->plugin_url() . '/assets/js/thumb_view.min.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('thumb-view-js');
            wp_register_script('lazyload-min-js', UXGallery()->plugin_url() . '/assets/js/jquery.lazyload.min.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('lazyload--min-js');
        }
        if ($gallery_view == '6') {
            wp_register_script('jusiifed-js', UXGallery()->plugin_url() . '/assets/js/justifiedGallery.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('jusiifed-js');
        }
        if ($gallery_view == '3') {
            if ($has_youtube) {
                wp_enqueue_script('youtube-lib-js', UXGallery()->plugin_url() . '/assets/js/youtube.lib.js', array('jquery'), '1.0.0', true);
            }
            if ($has_vimeo) {
                wp_enqueue_script('vimeo-lib-js', UXGallery()->plugin_url() . '/assets/js/vimeo.lib.js', array('jquery'), '1.0.0', true);
            }
        }
        if ($gallery_view == '10') {
            wp_register_script('modernizr.custom-js', UXGallery()->plugin_url() . '/assets/js/modernizr.custom.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('modernizr.custom-js');
            wp_register_script('classie-js', UXGallery()->plugin_url() . '/assets/js/classie.js', array('jquery'), '1.3.0', true);
            wp_enqueue_script('classie-js');
            wp_register_script('jquery.elastislide-js', UXGallery()->plugin_url() . '/assets/js/jquery.elastislide.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('jquery.elastislide-js');
            wp_register_script('jquery.hoverdir-js', UXGallery()->plugin_url() . '/assets/js/jquery.hoverdir.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('jquery.hoverdir-js');
            wp_register_script('elastic_grid-js', UXGallery()->plugin_url() . '/assets/js/elastic_grid.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('elastic_grid-js');
        }

        //ns code start here

        wp_enqueue_script("uxgallery_album_page_view", UXGallery()->plugin_url() . "/assets/albums/js/album_page_view.js", array('jquery'));

    }

    public function localize_scripts($id)
    {

        $id = intval($id);
        global $wpdb;
        global $post;
        $pID = (string)$post->ID;
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_gallerys WHERE id=%d", $id);
        $gallery = $wpdb->get_results($query);
        if (empty($gallery)) {
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_albums WHERE id=%d", $id);
            $gallery = $wpdb->get_results($query);
            if (!isset($gallery[0])) {
                return false;
            }


            $gallery[0]->ux_sl_effects = 2;
            $gallery[0]->content_per_page = 5;
            $gallery[0]->display_type = 1;
        }

        $admin_url = admin_url("admin-ajax.php");
        $gallery_default_params = uxgallery_get_general_options();
        $gallery_params = array();
        foreach ($gallery_default_params as $name => $value) {
            if (strpos($name, 'uxgallery_') === false) {
                $gallery_params['uxgallery_' . $name] = get_option('uxgallery_' . $name);
            } else {
                $gallery_params[$name] = get_option($name);
            }
        }


        $query = $wpdb->prepare("SELECT image_url FROM " . $wpdb->prefix . "ux_gallery_images WHERE gallery_id=%d", $id);
        $image_urls = $wpdb->get_col($query);
        $has_youtube = 'false';
        $has_vimeo = 'false';
        $view_slug = $view_slug = uxgallery_get_view_slag_by_id($id);
        foreach ($image_urls as $image_url) {
            if (strpos($image_url, 'youtu') !== false) {
                $has_youtube = 'true';
            }
            if (strpos($image_url, 'vimeo') !== false) {
                $has_vimeo = 'true';
            }
        }

        $gallery_view = $gallery[0]->ux_sl_effects;
        $lightbox = array(
            'lightbox_transition' => get_option('uxgallery_light_box_transition'),
            'lightbox_speed' => get_option('uxgallery_light_box_speed'),
            'lightbox_fadeOut' => get_option('uxgallery_light_box_fadeout'),
            'lightbox_title' => get_option('uxgallery_light_box_title'),
            'lightbox_scalePhotos' => get_option('uxgallery_light_box_scalephotos'),
            'lightbox_scrolling' => get_option('uxgallery_light_box_scrolling'),
            'lightbox_opacity' => (get_option('uxgallery_light_box_opacity') / 100) + 0.001,
            'lightbox_open' => get_option('uxgallery_light_box_open'),
            'lightbox_returnFocus' => get_option('uxgallery_light_box_returnfocus'),
            'lightbox_trapFocus' => get_option('uxgallery_light_box_trapfocus'),
            'lightbox_fastIframe' => get_option('uxgallery_light_box_fastiframe'),
            'lightbox_preloading' => get_option('uxgallery_light_box_preloading'),
            'lightbox_overlayClose' => get_option('uxgallery_light_box_overlayclose'),
            'lightbox_escKey' => get_option('uxgallery_light_box_esckey'),
            'lightbox_arrowKey' => get_option('uxgallery_light_box_arrowkey'),
            'lightbox_loop' => get_option('uxgallery_light_box_loop'),
            'lightbox_closeButton' => get_option('uxgallery_light_box_closebutton'),
            'lightbox_previous' => get_option('uxgallery_light_box_previous'),
            'lightbox_next' => get_option('uxgallery_light_box_next'),
            'lightbox_close' => get_option('uxgallery_light_box_close'),
            'lightbox_html' => get_option('uxgallery_light_box_html'),
            'lightbox_photo' => get_option('uxgallery_light_box_photo'),
            'lightbox_innerWidth' => get_option('uxgallery_light_box_innerwidth'),
            'lightbox_innerHeight' => get_option('uxgallery_light_box_innerheight'),
            'lightbox_initialWidth' => get_option('uxgallery_light_box_initialwidth'),
            'lightbox_initialHeight' => get_option('uxgallery_light_box_initialheight'),
            'lightbox_slideshow' => get_option('uxgallery_light_box_slideshow'),
            'lightbox_slideshowSpeed' => get_option('uxgallery_light_box_slideshowspeed'),
            'lightbox_slideshowAuto' => get_option('uxgallery_light_box_slideshowauto'),
            'lightbox_slideshowStart' => get_option('uxgallery_light_box_slideshowstart'),
            'lightbox_slideshowStop' => get_option('uxgallery_light_box_slideshowstop'),
            'lightbox_fixed' => get_option('uxgallery_light_box_fixed'),
            'lightbox_reposition' => get_option('uxgallery_light_box_reposition'),
            'lightbox_retinaImage' => get_option('uxgallery_light_box_retinaimage'),
            'lightbox_retinaUrl' => get_option('uxgallery_light_box_retinaurl'),
            'lightbox_retinaSuffix' => get_option('uxgallery_light_box_retinasuffix'),
            'lightbox_maxWidth' => get_option('uxgallery_light_box_maxwidth'),
            'lightbox_maxHeight' => get_option('uxgallery_light_box_maxheight'),
            'lightbox_sizeFix' => get_option('uxgallery_light_box_size_fix'),
            'galleryID' => $id,
            'liquidSliderInterval' => $gallery[0]->description
        );

        if (get_option('uxgallery_light_box_size_fix') == 'false') {
            $lightbox['lightbox_width'] = '';
        } else {
            $lightbox['lightbox_width'] = get_option('uxgallery_light_box_width');
        }

        if (get_option('uxgallery_light_box_size_fix') == 'false') {
            $lightbox['lightbox_height'] = '';
        } else {
            $lightbox['lightbox_height'] = get_option('uxgallery_light_box_height');
        }

        $pos = get_option('uxgallery_lightbox_open_position');
        switch ($pos) {
            case 1:
                $lightbox['lightbox_top'] = '10%';
                $lightbox['lightbox_bottom'] = 'false';
                $lightbox['lightbox_left'] = '10%';
                $lightbox['lightbox_right'] = 'false';
                break;
            case 2:
                $lightbox['lightbox_top'] = '10%';
                $lightbox['lightbox_bottom'] = 'false';
                $lightbox['lightbox_left'] = 'false';
                $lightbox['lightbox_right'] = 'false';
                break;
            case 3:
                $lightbox['lightbox_top'] = '10%';
                $lightbox['lightbox_bottom'] = 'false';
                $lightbox['lightbox_left'] = 'false';
                $lightbox['lightbox_right'] = '10%';
                break;
            case 4:
                $lightbox['lightbox_top'] = 'false';
                $lightbox['lightbox_bottom'] = 'false';
                $lightbox['lightbox_left'] = '10%';
                $lightbox['lightbox_right'] = 'false';
                break;
            case 5:
                $lightbox['lightbox_top'] = 'false';
                $lightbox['lightbox_bottom'] = 'false';
                $lightbox['lightbox_left'] = 'false';
                $lightbox['lightbox_right'] = 'false';
                break;
            case 6:
                $lightbox['lightbox_top'] = 'false';
                $lightbox['lightbox_bottom'] = 'false';
                $lightbox['lightbox_left'] = 'false';
                $lightbox['lightbox_right'] = '10%';
                break;
            case 7:
                $lightbox['lightbox_top'] = 'false';
                $lightbox['lightbox_bottom'] = '10%';
                $lightbox['lightbox_left'] = '10%';
                $lightbox['lightbox_right'] = 'false';
                break;
            case 8:
                $lightbox['lightbox_top'] = 'false';
                $lightbox['lightbox_bottom'] = '10%';
                $lightbox['lightbox_left'] = 'false';
                $lightbox['lightbox_right'] = 'false';
                break;
            case 9:
                $lightbox['lightbox_top'] = 'false';
                $lightbox['lightbox_bottom'] = '10%';
                $lightbox['lightbox_left'] = 'false';
                $lightbox['lightbox_right'] = '10%';
                break;
        }

        $justified = array(
            'imagemargin' => get_option('uxgallery_ht_view8_element_padding'),
            'imagerandomize' => get_option('uxgallery_ht_view8_element_randomize'),
            'imagecssAnimation' => get_option('uxgallery_ht_view8_element_cssAnimation'),
            'imagecssAnimationSpeed' => get_option('uxgallery_ht_view8_element_animation_speed'),
            'imageheight' => get_option('uxgallery_ht_view8_element_height'),
            'imagejustify' => get_option('uxgallery_ht_view8_element_justify'),
            'imageshowcaption' => get_option('uxgallery_ht_view8_element_show_caption')
        );
        $justified_params = array();
        foreach ($justified as $name => $value) {
            $justified_params[$name] = $value;
        }
        if ($gallery[0]->content_per_page) {
            $num = absint($gallery[0]->content_per_page);
        } else {
            $num = 999;
        }
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_images where gallery_id = '%d' ORDER BY ordering ASC", $id);
        $all_images = $wpdb->get_results($query);
        $total = intval(((count($all_images) - 1) / $num) + 1);
        $disp_type = $gallery[0]->display_type;
        if (isset($_GET['page-img' . $id . $pID])) {
            $page = absint($_GET['page-img' . $id . $pID]);
        } else {
            $page = '';
        }
        if (empty($page) or $page < 0) {
            $page = 1;
        }
        if ($page > $total) {
            $page = $total;
        }
        $start = $page * $num - $num;
        if ($start < 0) $start = 0;
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_images where gallery_id = '%d' order by ordering ASC LIMIT " . $start . "," . $num, $id);
        $images[$id] = $wpdb->get_results($query);
        if ($disp_type != 0) {
            $images[$id] = $all_images;
        }

        $images_obj = array();
        foreach ($images[$id] as $image) {
            $thumbnail = $image->image_url;
            $thumbs = array();
            $larg_images = array();
            if (uxgallery_youtube_or_vimeo($thumbnail) == 'image') {
                $smal_img = esc_url(uxgallery_get_image_by_sizes_and_src($thumbnail, 'medium', false));
                $big_img = $thumbnail;
            } elseif (uxgallery_youtube_or_vimeo($thumbnail) == 'youtube') {
                $videourl = uxgallery_get_video_id_from_url($thumbnail);
                $smal_img = esc_url("//img.youtube.com/vi/" . $videourl[0] . "/mqdefault.jpg");
                $videourl = uxgallery_get_video_id_from_url($thumbnail);
                $big_img = "https://www.youtube.com/embed/" . $videourl[0];
            } elseif (uxgallery_youtube_or_vimeo($thumbnail) == 'vimeo') {
                $videourl = uxgallery_get_video_id_from_url($thumbnail);
                $hash = unserialize(wp_remote_fopen("http://vimeo.com/api/v2/video/" . $videourl[0] . ".php"));
                $smal_img = esc_url($hash[0]['thumbnail_large']);
                $videourl = uxgallery_get_video_id_from_url($thumbnail);
                $big_img = "https://player.vimeo.com/video/" . $videourl[0];
            }
            array_push($thumbs, $smal_img);
            array_push($larg_images, $big_img);
            if ($image->link_target == 'on') {
                $target = '_blank';
            } else {
                $target = '';
            }
            $images_parent_obj = array(
                'title' => $image->name,
                'description' => $image->description,
                'thumbnail' => $thumbs,
                'large' => $larg_images,
                'button_list' => array(
                    array(
                        'title' => $gallery_params['uxgallery_ht_view10_expand_block_button_text'],
                        'url' => $image->sl_url,
                        'new_window' => $target
                    ),
                ),
                'tags' => array()
            );
            array_push($images_obj, $images_parent_obj);
        }


        $lightbox_options = array(
            'uxgallery_lightbox_slideAnimationType' => $gallery_params['uxgallery_lightbox_slideAnimationType'],
            'uxgallery_lightbox_lightboxView' => $gallery_params['uxgallery_lightbox_lightboxView'],
            'uxgallery_lightbox_speed_new' => $gallery_params['uxgallery_lightbox_speed_new'],
            'uxgallery_lightbox_width_new' => $gallery_params['uxgallery_lightbox_width_new'],
            'uxgallery_lightbox_height_new' => $gallery_params['uxgallery_lightbox_height_new'],
            'uxgallery_lightbox_videoMaxWidth' => $gallery_params['uxgallery_lightbox_videoMaxWidth'],
            'uxgallery_lightbox_overlayDuration' => $gallery_params['uxgallery_lightbox_overlayDuration'],
            'uxgallery_lightbox_overlayClose_new' => $gallery_params['uxgallery_lightbox_overlayClose_new'],
            'uxgallery_lightbox_loop_new' => $gallery_params['uxgallery_lightbox_loop_new'],
            'uxgallery_lightbox_escKey_new' => $gallery_params['uxgallery_lightbox_escKey_new'],
            'uxgallery_lightbox_keyPress_new' => $gallery_params['uxgallery_lightbox_keyPress_new'],
            'uxgallery_lightbox_arrows' => $gallery_params['uxgallery_lightbox_arrows'],
            'uxgallery_lightbox_mouseWheel' => $gallery_params['uxgallery_lightbox_mouseWheel'],
            'uxgallery_lightbox_download' => $gallery_params['uxgallery_lightbox_download'],
            'uxgallery_lightbox_showCounter' => $gallery_params['uxgallery_lightbox_showCounter'],
            'uxgallery_lightbox_nextHtml' => $gallery_params['uxgallery_lightbox_nextHtml'],
            'uxgallery_lightbox_prevHtml' => $gallery_params['uxgallery_lightbox_prevHtml'],
            'uxgallery_lightbox_sequence_info' => $gallery_params['uxgallery_lightbox_sequence_info'],
            'uxgallery_lightbox_sequenceInfo' => $gallery_params['uxgallery_lightbox_sequenceInfo'],
            'uxgallery_lightbox_slideshow_new' => $gallery_params['uxgallery_lightbox_slideshow_new'],
            'uxgallery_lightbox_slideshow_auto_new' => $gallery_params['uxgallery_lightbox_slideshow_auto_new'],
            'uxgallery_lightbox_slideshow_speed_new' => $gallery_params['uxgallery_lightbox_slideshow_speed_new'],
            'uxgallery_lightbox_slideshow_start_new' => $gallery_params['uxgallery_lightbox_slideshow_start_new'],
            'uxgallery_lightbox_slideshow_stop_new' => $gallery_params['uxgallery_lightbox_slideshow_stop_new'],
            'uxgallery_lightbox_watermark' => $gallery_params['uxgallery_lightbox_watermark'],
            'uxgallery_lightbox_socialSharing' => $gallery_params['uxgallery_lightbox_socialSharing'],
            'uxgallery_lightbox_facebookButton' => $gallery_params['uxgallery_lightbox_facebookButton'],
            'uxgallery_lightbox_twitterButton' => $gallery_params['uxgallery_lightbox_twitterButton'],
            'uxgallery_lightbox_googleplusButton' => $gallery_params['uxgallery_lightbox_googleplusButton'],
            'uxgallery_lightbox_pinterestButton' => $gallery_params['uxgallery_lightbox_pinterestButton'],
            'uxgallery_lightbox_linkedinButton' => $gallery_params['uxgallery_lightbox_linkedinButton'],
            'uxgallery_lightbox_tumblrButton' => $gallery_params['uxgallery_lightbox_tumblrButton'],
            'uxgallery_lightbox_redditButton' => $gallery_params['uxgallery_lightbox_redditButton'],
            'uxgallery_lightbox_bufferButton' => $gallery_params['uxgallery_lightbox_bufferButton'],
            'uxgallery_lightbox_diggButton' => $gallery_params['uxgallery_lightbox_diggButton'],
            'uxgallery_lightbox_vkButton' => $gallery_params['uxgallery_lightbox_vkButton'],
            'uxgallery_lightbox_yummlyButton' => $gallery_params['uxgallery_lightbox_yummlyButton'],
            'uxgallery_lightbox_watermark_text' => $gallery_params['uxgallery_lightbox_watermark_text'],
            'uxgallery_lightbox_watermark_textColor' => $gallery_params['uxgallery_lightbox_watermark_textColor'],
            'uxgallery_lightbox_watermark_textFontSize' => $gallery_params['uxgallery_lightbox_watermark_textFontSize'],
            'uxgallery_lightbox_watermark_containerBackground' => $gallery_params['uxgallery_lightbox_watermark_containerBackground'],
            'uxgallery_lightbox_watermark_containerOpacity' => $gallery_params['uxgallery_lightbox_watermark_containerOpacity'],
            'uxgallery_lightbox_watermark_containerWidth' => $gallery_params['uxgallery_lightbox_watermark_containerWidth'],
            'uxgallery_lightbox_watermark_position_new' => $gallery_params['uxgallery_lightbox_watermark_position_new'],
            'uxgallery_lightbox_watermark_opacity' => $gallery_params['uxgallery_lightbox_watermark_opacity'],
            'uxgallery_lightbox_watermark_margin' => $gallery_params['uxgallery_lightbox_watermark_margin'],
            'uxgallery_lightbox_watermark_img_src_new' => $gallery_params['uxgallery_lightbox_watermark_img_src_new'],
        );

        if ($gallery_params['uxgallery_lightbox_type'] == 'old_type') {
            wp_localize_script('jquery.gicolorbox-js', 'lightbox_obj', $lightbox);
        } elseif ($gallery_params['uxgallery_lightbox_type'] == 'new_type') {
            list($r, $g, $b) = array_map('hexdec', str_split($gallery_params['uxgallery_lightbox_watermark_containerBackground'], 2));
            $titleopacity = $gallery_params["uxgallery_lightbox_watermark_containerOpacity"] / 100;
            $lightbox_options['uxgallery_lightbox_watermark_container_bg_color'] = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $titleopacity . ')';
            wp_localize_script('gallery-resp-lightbox-js', 'gallery_resp_lightbox_obj', $lightbox_options);
            wp_localize_script('gallery-custom-js', 'is_watermark', $gallery_params['uxgallery_lightbox_watermark']);
            wp_localize_script('gallery-resp-lightbox-js', 'GalleryImgDisableRightClickLightbox', get_option('uxgallery_disable_right_click'));
        }

        wp_localize_script('front-end-js-' . $view_slug, 'img_gallery_param_obj', $gallery_params);
        wp_localize_script('front-end-js-' . $view_slug, 'img_gallery_adminUrl', $admin_url);
        wp_localize_script('front-end-js-' . $view_slug, 'postID', $pID);
        wp_localize_script('front-end-js-' . $view_slug, 'img_gallery_hasYoutube', $has_youtube);
        wp_localize_script('front-end-js-' . $view_slug, 'img_gallery_hasVimeo', $has_vimeo);
        wp_localize_script('front-end-js-' . $view_slug, 'img_gallery_postID', $pID);
        wp_localize_script('jquery.gicolorbox-js', 'lightbox_obj', $lightbox);
        wp_localize_script('gallery-custom-js', 'galleryId', array($id));
        wp_localize_script('jusiifed-js', 'justified_obj', $justified);
        if (strpos($id, ",") !== false) {
            $id = str_replace(",", "_", $id);
        }
        wp_localize_script('front-end-js-' . $view_slug, 'gallery_images_obj_' . $id, $images_obj);
        wp_localize_script('elastic_grid-js', 'elements_margin', $gallery_params['uxgallery_ht_view10_element_margin']);
        wp_localize_script('gallery-custom-js', 'galleryImgDisableRightClick', get_option('uxgallery_disable_right_click'));
        wp_localize_script('gallery-custom-js', 'galleryImgDisableRightClickElastic', get_option('uxgallery_disable_right_click'));
        wp_localize_script('gallery-custom-js', 'galleryImgLigtboxType', get_option('uxgallery_lightbox_type'));
        wp_localize_script('uxgallery_album_page_view', 'uxgallery_album_page_view_obj',
            array(
                'ajax_url' => UXGallery()->ajax_url(),
                'front_nonce' => wp_create_nonce('get_album_images')
            ));
    }
}