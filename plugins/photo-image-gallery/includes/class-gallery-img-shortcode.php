<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class UXGallery_Shortcode
{

    /**
     * UXGallery_Shortcode constructor.
     */
    public function __construct()
    {
        add_shortcode('uxgallery', array($this, 'run_shortcode'));
        add_shortcode('uxgallery_album', array($this, 'run_shortcode_album'));
        add_action('admin_footer', array($this, 'inline_popup_content'));
        add_action('media_buttons_context', array($this, 'add_editor_media_button'));
        add_action('media_buttons_context', array($this, 'add_editor_media_button_album'));

    }

    /**
     * Run the shortcode on front-end
     *
     * @param $attrs
     *
     * @return string
     */
    public function run_shortcode($attrs)
    {
        $attrs = shortcode_atts(array(
            'id' => 'no ux gallery',

        ), $attrs);

        global $wpdb;
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_gallerys WHERE id=%d", $attrs['id']);
        $gallery = $wpdb->get_results($query);
        if (!$gallery) {
            ob_start();
            printf(__("Gallery with ID %s doesn't exist.", "gallery-img"), $attrs['id']);
            return ob_get_clean();
        }

        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_images WHERE gallery_id=%d", $attrs['id']);
        $images_row = $wpdb->get_results($query);
        if (!$images_row) {
            ob_start();
            printf(__("Gallery with ID %s is empty.", "gallery-img"), $attrs['id']);
            return ob_get_clean();
        }

        $query = $wpdb->prepare("SELECT ux_sl_effects FROM " . $wpdb->prefix . "ux_gallery_gallerys WHERE id=%d", $attrs['id']);
        $gallery_view = $wpdb->get_var($query);
        $query = $wpdb->prepare("SELECT image_url FROM " . $wpdb->prefix . "ux_gallery_images WHERE gallery_id=%d", $attrs['id']);
        $images = $wpdb->get_col($query);
        $has_youtube = false;
        $has_vimeo = false;
        foreach ($images as $image_row) {
            if (strpos($image_row, 'youtu') !== false) {
                $has_youtube = true;
            }
            if (strpos($image_row, 'vimeo') !== false) {
                $has_vimeo = true;
            }
        }

        do_action('uxgallery_shortcode_scripts', $attrs['id'], $gallery_view, $has_youtube, $has_vimeo);
        do_action('uxgallery_localize_scripts', $attrs['id']);

        return $this->init_frontend($attrs['id'], "gallery");
    }

    public function run_shortcode_album($attrs)
    {
        global $wpdb;

        $id_array = explode(",", $attrs["id"]);
        $attrs = shortcode_atts(array(
            'id' => 'no ux gallery album',
            'style' => '3'
        ), $attrs);

        $album_style = (int)$attrs['style'];

        if ($album_style == 0) {
            $album_style = 3;
        }


        $query = $wpdb->prepare("SELECT ux_sl_effects FROM " . $wpdb->prefix . "ux_gallery_gallerys WHERE id=%d", $attrs['id']);
        $gallery_view = $wpdb->get_var($query);
        $query = $wpdb->prepare("SELECT image_url FROM " . $wpdb->prefix . "ux_gallery_images WHERE gallery_id=%d ORDER BY `id` DESC LIMIT 1", $attrs['id']);
        $images = $wpdb->get_col($query);

        $has_youtube = false;
        $has_vimeo = false;
        foreach ($images as $image_row) {
            if (strpos($image_row, 'youtu') !== false) {
                $has_youtube = true;
            }
            if (strpos($image_row, 'vimeo') !== false) {
                $has_vimeo = true;
            }
        }

        do_action('uxgallery_shortcode_scripts', $attrs['id'], $gallery_view, $has_youtube, $has_vimeo);
        do_action('uxgallery_localize_scripts', $attrs['id']);


        return $this->init_frontend($id_array, "album", $album_style);
    }

    /**
     * Show published galleries in frontend
     *
     * @param $id
     *
     * @return string
     */
    protected function init_frontend($id_array, $flag, $style = null)
    {
        global $wpdb;
        ob_start();

        //ns code start here

        $format = '';
        $album_style = $style;

        if (is_array($id_array)) {
            if (count($id_array) == 1) {
                $id = $id_array[0];
                $format = "%d";
            } else {
                $id = implode(",", $id_array);
                $format = rtrim(str_repeat("%d, ", count($id_array)), ", ");
            }
        } else {
            $id = $id_array;
        }

        $gallery_default_params = uxgallery_get_general_options();
        $uxgallery_get_option = array();
        foreach ($gallery_default_params as $name => $value) {
            if (strpos($name, 'uxgallery_') === false) {
                $uxgallery_get_option['uxgallery_' . $name] = get_option('uxgallery_' . $name);
            } else {
                $uxgallery_get_option[$name] = get_option($name);
            }
        }

        if ($flag == "gallery") {
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_images where gallery_id = '%d' order by ordering ASC", $id);
            $images = $wpdb->get_results($query);

            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_gallerys where id = '%d' order by id ASC", $id);
            $gallery = $wpdb->get_results($query);
            UXGallery()->template_loader->load_front_end($images, $uxgallery_get_option, $gallery);
        }


        if ($flag == "album") {

            $albums = array();
            $album_galleries = array();

            if (!empty($id_array)) {

                $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_albums where id IN (" . $format . ") order by `date` DESC", $id_array);
                $albums = $wpdb->get_results($query);


                $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_album_has_gallery AS album_has_gallery LEFT JOIN " . $wpdb->prefix . "ux_gallery_gallerys AS galleries 
         ON (album_has_gallery.id_gallery = galleries.id) WHERE album_has_gallery.id_album IN (" . $format . ") ORDER BY album_has_gallery.order ASC ", $id_array);
                $album_galleries = $wpdb->get_results($query);

                if (!isset($albums[0])) {
                    ob_start();
                    printf(__("Album with ID %s doesn't exist.", "gallery-img"), implode(",", $id_array));
                    return ob_get_clean();
                }

                if (count($id_array) > 1) {
                    $album_style = $style;
                } elseif (count($id_array) == 1 && isset($albums[0])) {
                    $album_style = $albums[0]->photo_gallery_wp_album_style;
                } else {
                    $album_style = 3;
                }
            }

            // get album images

            foreach ($album_galleries as $key => $val) {
                $index = 0;
                $query = $wpdb->prepare("SELECT id,gallery_id, name, description, image_url, sl_type FROM " . $wpdb->prefix . "ux_gallery_images where gallery_id = '%d' order by ordering ASC", $val->id);
                $val->images = $wpdb->get_results($query);
                if (!empty($val->images)) {
                    if ($val->images[0]->sl_type == "video") {
                        $videourl = uxgallery_get_video_id_from_url($val->images[0]->image_url);
                        if ($videourl[1] == 'youtube') {
                            $thumb = "https://img.youtube.com/vi/" . esc_html($videourl[0]) . "/mqdefault.jpg";
                        } else {
                            $hash = unserialize(wp_remote_fopen("https://vimeo.com/api/v2/video/" . $videourl[0] . ".php"));
                            $imgsrc = $hash[0]['thumbnail_large'];
                            $thumb = esc_attr($imgsrc);
                        }
                    } else {
                        $thumb = esc_attr($val->images[0]->image_url);
                    }
                    $val->image_url = $thumb;
                } else {
                    $val->image_url = "";
                }

                if ($val->cover_image != "") {
                    $val->image_url = esc_url($val->cover_image);
                } elseif (!empty($val->images)) {
                    $val->image_url = "";
                    foreach ($val->images as $image) {
                        if ($val->images[$index]->sl_type == "image") {
                            $val->image_url = esc_url($val->images[$index]->image_url);
                            break;
                        }
                        $index++;
                    }
                    if ($val->image_url == "") {
                        $val->image_url = UXGALLERY_IMAGES_URL . "/admin_images/noimage.jpg";
                    }

                } else {
                    $val->image_url = "";
                }

                $val->image_count = count($val->images);
            }


            // make album array with galleries and images
            foreach ($albums as $key => $val) {
                $album_image_count[$key] = 0;
                foreach ($album_galleries as $k => $v) {
                    if ($v->id_album == $val->id) {
                        $album_image_count[$key] = $album_image_count[$key] + count($v->images);
                        $val->image_count = $album_image_count[$key];
                        $val->galleries[] = $v;
                        if (!is_null($val->cover_image)) {
                            $val->image_url = $val->cover_image;
                        } else {
                            $val->image_url = $v->image_url;
                        }
                    }
                }
                if (!isset($val->galleries)) {
                    unset($albums[$key]);
                }
            }


            UXGallery()->template_loader->load_album_front_end($albums, $gallery_default_params, $album_style);
        }


        //ns end


        return ob_get_clean();

    }

    /**
     * Add editor media button
     *
     * @param $context
     *
     * @return string
     */
    public function add_editor_media_button($context)
    {
        $img = UXGALLERY_IMAGES_URL . '/admin_images/post.button.png';
        $container_id = 'uxgallery';
        $title = __('Select UX gallery to insert into post', 'gallery-img');
        $context .= '<a class="button thickbox" title="Select gallery to insert into post" title="' . $title . '" href="#TB_inline?width=400&inlineId=' . $container_id . '">
        <span class="wp-media-buttons-icon" style="background: url(' . $img . '); background-repeat: no-repeat; background-position: left bottom;"></span>
    Add gallery
    </a>';

        return $context;
    }

    public function add_editor_media_button_album($context)
    {
        $img = UXGALLERY_IMAGES_URL . '/admin_images/post.button.png';
        $container_id = 'uxgallery-2';
        $title = __('Select UX album to insert into post', 'gallery-img');
        $context .= '<a class="button thickbox" title="Select album to insert into post" title="' . $title . '" href="#TB_inline?width=400&inlineId=' . $container_id . '">
        <span class="wp-media-buttons-icon" style="background: url(' . $img . '); background-repeat: no-repeat; background-position: left bottom;"></span>
    Add Album
    </a>';

        return $context;
    }

    /**
     * Inline popup contents
     */
    public function inline_popup_content()
    {
        global $wpdb;
        $query = "SELECT albums.*, COUNT(album_has_gallery.id_gallery) as galleries_count FROM " . $wpdb->prefix . "ux_gallery_albums AS albums LEFT JOIN " . $wpdb->prefix . "ux_gallery_album_has_gallery AS album_has_gallery ON albums.id = album_has_gallery.id_album GROUP BY albums.id";
        $shortcodealbums = $wpdb->get_results($query);

        require UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'inline-popup-content-html.php';
    }


}
