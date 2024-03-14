<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class UXGallery_Template_Loader
{

    public function __contstruct()
    {
        add_action('media_buttons_context', array($this, 'add_editor_media_button'));
    }


    /**
     * Load the Plugin shortcode's frontend
     *
     * @param $images
     * @param $uxgallery_get_option
     * @param $gallery
     */


    public function load_album_front_end($albums, $get_option, $style)
    {
        global $post;
        global $wpdb;

        $album_categories = array();
        if (!empty($albums)) {

            // get album categories
            $query = esc_sql("SELECT * FROM " . $wpdb->prefix . "ux_gallery_categories");
            $album_categories = $wpdb->get_results($query);

            // create category classes for items
            foreach ($albums as $val) {
                foreach ($val->galleries as $v) {
                    $v->cat_class = explode(",", $v->categories);
                    foreach ($v->cat_class as $k => $cat) {
                        $v->cat_class[$k] = "ux_cat_" . $cat;
                    }
                }
            }


            wp_enqueue_script("album_filter.js", UXGallery()->plugin_url() . "/assets/albums/js/jquery.mixitup.min.js", false);
            wp_enqueue_style("album_filter.css", UXGallery()->plugin_url() . "/assets/albums/style/filterize.css", false);

            if ($style != 3) {
                wp_register_script('mosaicflow.js', UXGallery()->plugin_url() . '/assets/albums/js/jquery.mosaicflow.js', array('jquery'), '1.0.0', true);
                wp_enqueue_script('mosaicflow.js');
            }
            wp_register_script('hoverdir.js', UXGallery()->plugin_url() . '/assets/albums/js/jquery.hoverdir.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('hoverdir.js');

            wp_register_script('hover_custom.js', UXGallery()->plugin_url() . '/assets/albums/js/modernizr.custom.97074.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('hover_custom.js');


            require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'album' . DIRECTORY_SEPARATOR . 'album-general.css.php';
            switch ($style) {
                case 1:
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'album' . DIRECTORY_SEPARATOR . 'content-popup' . DIRECTORY_SEPARATOR . 'content-popup-view.php';
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'album' . DIRECTORY_SEPARATOR . 'content-popup' . DIRECTORY_SEPARATOR . 'content-popup-view.css.php';
                    break;
                case 3:
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'album' . DIRECTORY_SEPARATOR . 'thumbnails' . DIRECTORY_SEPARATOR . 'thumbnails-view.php';
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'album' . DIRECTORY_SEPARATOR . 'thumbnails' . DIRECTORY_SEPARATOR . 'thumbnails-view.css.php';
                    break;
                case 2:
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'album' . DIRECTORY_SEPARATOR . 'lightbox' . DIRECTORY_SEPARATOR . 'lightbox-gallery-view.php';
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'album' . DIRECTORY_SEPARATOR . 'lightbox' . DIRECTORY_SEPARATOR . 'lightbox-gallery-view.css.php';
                    break;
                /* case 4:
                     require PHOTO_GALLERY_WP_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'album' . DIRECTORY_SEPARATOR . 'masonry' . DIRECTORY_SEPARATOR . 'masonry-gallery-view.php';
                     require PHOTO_GALLERY_WP_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'album' . DIRECTORY_SEPARATOR . 'masonry' . DIRECTORY_SEPARATOR . 'masonry-gallery-view-css.php';
                     break;
                 case 5:
                     require PHOTO_GALLERY_WP_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'album' . DIRECTORY_SEPARATOR . 'mosaic' . DIRECTORY_SEPARATOR . 'mosaic-gallery-view.php';
                     require PHOTO_GALLERY_WP_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'album' . DIRECTORY_SEPARATOR . 'mosaic' . DIRECTORY_SEPARATOR . 'mosaic-gallery-view-css.php';
                     break;*/
            }

        }
    }


    public function load_front_end($images, $uxgallery_get_option, $gallery)
    {
        global $post;
        global $wpdb;
        if (!empty($gallery)) {
            $galleryID = $gallery[0]->id;
            $view = $gallery[0]->ux_sl_effects;
            $disp_type = $gallery[0]->display_type;
            if ($gallery[0]->content_per_page) {
                $num = $gallery[0]->content_per_page;
            } else {
                $num = 999;
            }
            $like_dislike = $gallery[0]->rating;
            $total = intval(((count($images) - 1) / $num) + 1);
            $pattern = '/-/';
            $ux_ip = uxgallery_get_ip();
            $pID = $post->ID;
            $slidertitle = $gallery[0]->name;
            $sliderheight = $gallery[0]->sl_height - 2 * $uxgallery_get_option['uxgallery_slider_slideshow_border_size'];
            $sliderwidth = $gallery[0]->sl_width - 2 * $uxgallery_get_option['uxgallery_slider_slideshow_border_size'];
            $slidereffect = $gallery[0]->gallery_list_effects_s;
            $slidepausetime =  $gallery[0]->param;
            $sliderpauseonhover = $gallery[0]->pause_on_hover;
            $sliderposition = $gallery[0]->sl_position;
            $slidechangespeed = $gallery[0]->param;
            $trim_slider_title_position = trim($uxgallery_get_option['uxgallery_slider_title_position']);
            $slideshow_title_position = explode('-', $trim_slider_title_position);
            $trim_slider_description_position = trim($uxgallery_get_option['uxgallery_slider_description_position']);
            $slideshow_description_position = explode('-', $trim_slider_description_position);
            $has_youtube = 'false';
            $has_vimeo = 'false';
            foreach ($images as $image) {
                if (strpos($image->image_url, 'youtu') !== false) {
                    $has_youtube = 'true';
                }
                if (strpos($image->image_url, 'vimeo') !== false) {
                    $has_vimeo = 'true';
                }
            }
            if (isset($_GET['page-img' . $galleryID . $pID]) && $disp_type == 0) {
                $page = absint($_GET['page-img' . $galleryID . $pID]);
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

            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_images where gallery_id = '%d' order by ordering ASC LIMIT " . $start . "," . $num, $galleryID);
            $page_images = $wpdb->get_results($query);
            if ($disp_type == 2) {
                $page_images = $images;
            }

           
            if (in_array($view, array(0, 4, 5))) {
                wp_register_script('hoverdir.js', UXGallery()->plugin_url() . '/assets/albums/js/jquery.hoverdir.js', array('jquery'), '1.0.0', true);
                wp_enqueue_script('hoverdir.js');

                wp_register_script('hover_custom.js', UXGallery()->plugin_url() . '/assets/albums/js/modernizr.custom.97074.js', array('jquery'), '1.0.0', true);
                wp_enqueue_script('hover_custom.js');
            }

            require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'album' . DIRECTORY_SEPARATOR . 'album-general.css.php';
            switch ($view) {
                case 0:
                    $view_slug = uxgallery_get_view_slag_by_id($galleryID);
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'content-popup' . DIRECTORY_SEPARATOR . 'content-popup-view.php';
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'content-popup' . DIRECTORY_SEPARATOR . 'content-popup-view.css.php';
                    break;
                case 1:
                    $view_slug = uxgallery_get_view_slag_by_id($galleryID);
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'content-slider' . DIRECTORY_SEPARATOR . 'content-slider-view.php';
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'content-slider' . DIRECTORY_SEPARATOR . 'content-slider-view.css.php';
                    break;
                case 3:
                    $view_slug = uxgallery_get_view_slag_by_id($galleryID);
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'slider' . DIRECTORY_SEPARATOR . 'slider-view.php';
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'slider' . DIRECTORY_SEPARATOR . 'slider-view.css.php';
                    break;
                case 4:
                    $view_slug = uxgallery_get_view_slag_by_id($galleryID);
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'thumbnails' . DIRECTORY_SEPARATOR . 'thumbnails-view.php';
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'thumbnails' . DIRECTORY_SEPARATOR . 'thumbnails-view.css.php';
                    break;
                case 5:
                    $view_slug = uxgallery_get_view_slag_by_id($galleryID);
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'lightbox-gallery' . DIRECTORY_SEPARATOR . 'lightbox-gallery-view.php';
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'lightbox-gallery' . DIRECTORY_SEPARATOR . 'lightbox-gallery-view.css.php';
                    break;
                case 6:
                    $view_slug = uxgallery_get_view_slag_by_id($galleryID);
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'justified' . DIRECTORY_SEPARATOR . 'justified-view.php';
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'justified' . DIRECTORY_SEPARATOR . 'justified-view.css.php';
                    break;
                case 7:
                    $view_slug = uxgallery_get_view_slag_by_id($galleryID);
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'blog-style-gallery' . DIRECTORY_SEPARATOR . 'blog-style-gallery-view.php';
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'blog-style-gallery' . DIRECTORY_SEPARATOR . 'blog-style-gallery-view.css.php';
                    break;
                case 10:
                    $view_slug = uxgallery_get_view_slag_by_id($galleryID);
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'elastic-grid' . DIRECTORY_SEPARATOR . 'elastic-grid-view.php';
                    require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'front-end' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'elastic-grid' . DIRECTORY_SEPARATOR . 'elastic-grid-view.css.php';
                    break;
            }
        }

    }
}