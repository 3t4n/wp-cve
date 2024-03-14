<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class UXGallery_Admin
{

    /**
     * Array of pages in admin
     * @var array
     */
    public $pages = array();

    /**
     * Instance of UXGallery_General_Options class
     *
     * @var UXGallery_General_Options
     */
    public $general_options = null;

    /**
     * Instance of UXGallery_Galleries class
     *
     * @var UXGallery_Galleries
     */
    public $galleries = null;

    /**
     * Instance of UXGallery_Lightbox_Options class
     *
     * @var UXGallery_Lightbox_Options
     */
    public $lightbox_options = null;

    /**
     * UXGallery_Admin constructor.
     */
    public function __construct()
    {
        $this->init();
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('wp_loaded', array($this, 'wp_loaded'));
    }

    /**
     * Initialize Image Gallery's admin
     */
    protected function init()
    {
        $this->general_options = new UXGallery_General_Options();
        $this->galleries = new UXGallery_Galleries();
        $this->albums = new UXGallery_Albums();
        $this->lightbox_options = new UXGallery_Lightbox_Options();
    }

    /**
     * Prints Gallery Menu
     */
    public function admin_menu()
    {
        $this->pages[] = add_menu_page(__('UX  Gallery', 'gallery-img'), __('UX Gallery', 'gallery-img'), 'delete_pages', 'galleries_uxgallery', array(
            UXGallery()->admin->galleries,
            'load_gallery_page'
        ), UXGALLERY_IMAGES_URL . "/admin_images/small.logo.png");
        $this->pages[] = add_submenu_page('galleries_uxgallery', __('Galleries', 'gallery-img'), __('Galleries', 'gallery-img'), 'delete_pages', 'galleries_uxgallery', array(
            UXGallery()->admin->galleries,
            'load_gallery_page'
        ));

        $this->pages[] = add_submenu_page('galleries_uxgallery', __('Albums', 'gallery-img'), __('Albums <i class="free_notice">(PRO)</i>', 'gallery-img'), 'delete_pages', 'galleries_ux_albums', array(
            UXGallery()->admin->albums,
            'load_album_page'
        ));

        $this->pages[] = add_submenu_page('galleries_uxgallery', __('General Options', 'gallery-img'), __('Template Settings', 'gallery-img'), 'delete_pages', 'Options_gallery_styles', array(
            UXGallery()->admin->general_options,
            'load_page'
        ));
        $this->pages[] = add_submenu_page('galleries_uxgallery', __('Lightbox Options', 'gallery-img'), __('Lightbox Settings', 'gallery-img'), 'delete_pages', 'Options_gallery_lightbox_styles', array(
            UXGallery()->admin->lightbox_options,
            'load_page'
        ));
    }


    public function wp_loaded()
    {
        if (isset($_GET['page'])) {
            if ($_GET['page'] == 'galleries_uxgallery') {
                if (isset($_GET['task'])) {
                    $task = sanitize_text_field($_GET['task']);
                    switch ($task) {
                        case 'add_gallery':
                            $this->uxgallery_image_add_gallery();
                            break;
                        case 'gallery_video':
                            $this->uxgallery_image_add_video();
                            break;
                        case 'duplicate_gallery_image':
                            $this->uxgallery_image_duplicate_gallery();
                            break;
                        case 'remove_gallery':
                            $this->uxgallery_image_remove_gallery();
                            break;
                    }

                }
            } elseif ($_GET['page'] == 'galleries_ux_albums') {
                if (isset($_GET['task'])) {
                    $task = sanitize_text_field($_GET['task']);
                    switch ($task) {
                        case 'add_album':
                            $this->uxgallery_image_add_album();
                            break;
                        case 'remove_album':
                            $this->uxgallery_image_remove_album();
                            break;
                    }

                }
            }

        }
    }

    /**
     * Add New Gallery
     */
    public static function uxgallery_image_add_gallery()
    {
        if (!isset($_REQUEST['gallery_wp_nonce_add_gallery']) || !wp_verify_nonce($_REQUEST['gallery_wp_nonce_add_gallery'], 'gallery_wp_nonce_add_gallery')) {
            wp_die('Security check fail');
        }
        global $wpdb;
        $table_name = $wpdb->prefix . "ux_gallery_gallerys";
        $sql_2 = "
INSERT INTO 
`" . $table_name . "` ( `name`, `sl_height`, `sl_width`, `pause_on_hover`, `gallery_list_effects_s`, `description`, `param`, `sl_position`, `ordering`, `published`, `ux_sl_effects`) VALUES
( 'New gallery', '375', '600', 'on', 'cubeH', '', '1000', 'center', '1', '300', '4')";
        $wpdb->query($sql_2);
        $last_key = $wpdb->insert_id;
        $save_data_nonce = wp_create_nonce('uxgallery_nonce_save_data' . $last_key);
        header('Location: admin.php?page=galleries_uxgallery&id=' . $last_key . '&task=apply' . '&save_data_nonce=' . $save_data_nonce);
    }

    /***
     * add new album
     */

    public function uxgallery_image_add_album()
    {
        if (!isset($_REQUEST['gallery_wp_nonce_add_album']) || !wp_verify_nonce($_REQUEST['gallery_wp_nonce_add_album'], 'gallery_wp_nonce_add_album')) {
            wp_die('Security check fail');
        }
        global $wpdb;
        $table_name = $wpdb->prefix . "ux_gallery_albums";
        $sql = "
INSERT INTO 
`" . $table_name . "` ( `name`, `sl_height`, `sl_width`, `gallery_list_effects_s`, `description`, `sl_position`, `ordering`, `published`, `photo_gallery_wp_sl_effects`) VALUES
( 'New Album', '375', '600', 'cubeH', '', 'center', '1', '300', '4')";
        $wpdb->query($sql);
        $last_key = $wpdb->insert_id;
        $save_data_nonce = wp_create_nonce('uxgallery_nonce_save_data' . $last_key);
        header('Location: admin.php?page=galleries_ux_albums&id=' . $last_key . '&task=apply' . '&save_data_nonce=' . $save_data_nonce);
    }

    /**
     *Add Video
     */
    public static function uxgallery_image_add_video()
    {
        if ($_GET['closepop'] == 1) {
            if (!isset($_GET["id"]) || !absint($_GET['id']) || absint($_GET['id']) != $_GET['id']) {
                wp_die('"id" parameter is required to be not negative integer');
            }
            $id = absint($_GET["id"]);
            if (!isset($_REQUEST['gallery_nonce_add_video']) || !wp_verify_nonce($_REQUEST['gallery_nonce_add_video'], 'gallery_add_video_nonce' . $id)) {
                wp_die('Security check fail');
            }
            global $wpdb;
            if (isset($_POST["ux_add_video_input"])) {
                if ($_POST["ux_add_video_input"] != '') {
                    $table_name = $wpdb->prefix . "ux_gallery_images";
                    $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_gallerys WHERE id= %d", $id);
                    $row = $wpdb->get_row($query);
                    $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_images where gallery_id = %d ", $row->id);
                    $rowplusorder = $wpdb->get_results($query);
                    foreach ($rowplusorder as $key => $rowplusorders) {
                        if ($rowplusorders->ordering == 0) {
                            $rowplusorderspl = 1;
                            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET ordering = '" . $rowplusorderspl . "' WHERE id = %s ", $rowplusorders->id));
                        } else {
                            $rowplusorderspl = $rowplusorders->ordering + 1;
                            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET ordering = '" . $rowplusorderspl . "' WHERE id = %s ", $rowplusorders->id));
                        }
                    }
                    $sql_video = "INSERT INTO 
`" . $table_name . "` ( `name`, `gallery_id`, `description`, `image_url`, `sl_url`, `sl_type`, `link_target`, `ordering`, `published`, `published_in_sl_width`) VALUES 
( '" . sanitize_text_field($_POST["show_title"]) . "', '" . $id . "', '" . sanitize_text_field($_POST["show_description"]) . "', '" . sanitize_text_field($_POST["ux_add_video_input"]) . "', '" . sanitize_text_field($_POST["show_url"]) . "', 'video', 'on', '0', '1', '1' )";
                    $wpdb->query($sql_video);
                }
            }
            $save_data_nonce = wp_create_nonce('uxgallery_nonce_save_data' . $id);
            header('Location: admin.php?page=galleries_uxgallery&id=' . $id . '&task=apply&save_data_nonce=' . $save_data_nonce);
        }
    }

    /**
     * Duplicate Video
     */
    public static function uxgallery_image_duplicate_gallery()
    {
        if (!isset($_GET["id"]) || !absint($_GET['id']) || absint($_GET['id']) != $_GET['id']) {
            wp_die('"id" parameter is required to be not negative integer');
        }
        $id = absint($_GET["id"]);
        if (!isset($_REQUEST['gallery_duplicate_nonce']) || !wp_verify_nonce($_REQUEST['gallery_duplicate_nonce'], 'uxgallery_nonce_duplicate_gallery' . $id)) {
            wp_die('Security check fail');
        }
        global $wpdb;
        $table_name = $wpdb->prefix . "ux_gallery_gallerys";
        $query = $wpdb->prepare("SELECT * FROM " . $table_name . " WHERE id=%d", $id);
        $gallery_img = $wpdb->get_results($query);
        $wpdb->insert(
            $table_name,
            array(
                'name' => $gallery_img[0]->name . ' Copy',
                'sl_height' => $gallery_img[0]->sl_height,
                'sl_width' => $gallery_img[0]->sl_width,
                'pause_on_hover' => $gallery_img[0]->pause_on_hover,
                'gallery_list_effects_s' => $gallery_img[0]->gallery_list_effects_s,
                'description' => $gallery_img[0]->description,
                'param' => $gallery_img[0]->param,
                'sl_position' => $gallery_img[0]->sl_position,
                'ordering' => $gallery_img[0]->ordering,
                'published' => $gallery_img[0]->published,
                'ux_sl_effects' => $gallery_img[0]->ux_sl_effects,
                'display_type' => $gallery_img[0]->display_type,
                'content_per_page' => $gallery_img[0]->content_per_page,
                'rating' => $gallery_img[0]->rating,
                'autoslide' => $gallery_img[0]->autoslide
            )
        );
        $last_key = $wpdb->insert_id;
        $table_name = $wpdb->prefix . "ux_gallery_images";
        $query = $wpdb->prepare("SELECT * FROM " . $table_name . " WHERE gallery_id=%d", $id);
        $galleries = $wpdb->get_results($query);
        $galleries_list = "";
        foreach ($galleries as $key => $gallery) {
            $new_gallery = "('";
            $new_gallery .= $gallery->name . "','" . $last_key . "','" . $gallery->description . "','" . $gallery->image_url . "','" .
                $gallery->sl_url . "','" . $gallery->sl_type . "','" . $gallery->link_target . "','" . $gallery->ordering . "','" .
                $gallery->published . "','" . $gallery->published_in_sl_width . "','" . $gallery->like . "','" .
                $gallery->dislike . "')";
            $galleries_list .= $new_gallery . ",";
        }
        $galleries_list = substr($galleries_list, 0, strlen($galleries_list) - 1);
        $query = "INSERT into " . $table_name . " (`name`,`gallery_id`,`description`,`image_url`,`sl_url`,`sl_type`,`link_target`,`ordering`,`published`,`published_in_sl_width`,`like`,`dislike`)
					VALUES " . $galleries_list;
        $wpdb->query($query);
        wp_redirect('admin.php?page=galleries_uxgallery');
    }

    /**
     * Removes Gallery
     */
    public static function uxgallery_image_remove_gallery()
    {
        if (!isset($_GET["id"]) || !absint($_GET['id']) || absint($_GET['id']) != $_GET['id']) {
            wp_die('"id" parameter is required to be not negative integer');
        }
        $id = absint($_GET["id"]);
        if (!isset($_REQUEST['uxgallery_nonce_remove_gallery']) || !wp_verify_nonce($_REQUEST['uxgallery_nonce_remove_gallery'], 'uxgallery_nonce_remove_gallery' . $id)) {
            wp_die('Security check fail');
        }
        global $wpdb;
        $sql_remov_tag = $wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ux_gallery_gallerys WHERE id = %d", $id);
        $sql_remove_image = $wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ux_gallery_images WHERE gallery_id = %d", $id);
        $sql_remove_gallery_relation = $wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ux_gallery_album_has_gallery WHERE id_gallery = %d", $id);
        if (!$wpdb->query($sql_remov_tag)) {
            setcookie('gallery_deleted', 'fail', time() + 2);
        } else {
            $wpdb->query($sql_remov_tag);
            $wpdb->query($sql_remove_image);
            $wpdb->query($sql_remove_gallery_relation);
            setcookie('gallery_deleted', 'success', time() + 2);
        }
        wp_redirect('admin.php?page=galleries_uxgallery');
    }


    /***
     *remove album
     */

    public function uxgallery_image_remove_album()
    {
        if (isset($_GET["task"]) && $_GET["task"] == 'remove_album') {


            $id = absint($_GET["id"]);
            if (!isset($_REQUEST['uxgallery_nonce_remove_album']) || !wp_verify_nonce($_REQUEST['uxgallery_nonce_remove_album'], 'uxgallery_nonce_remove_album' . $id)) {
                wp_die('Security check fail');
            }
            global $wpdb;
            $sql_remove_album = $wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ux_gallery_albums WHERE id = %d", $id);
            $sql_remove_album_relation = $wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ux_gallery_album_has_gallery WHERE id_album = %d", $id);
            if (!$wpdb->query($sql_remove_album)) {
                setcookie('album_deleted', 'fail', time() + 2);
            } else {
                $wpdb->query($sql_remove_album);
                $wpdb->query($sql_remove_album_relation);
                setcookie('album_deleted', 'success', time() + 2);
            }
            wp_redirect('admin.php?page=galleries_ux_albums');
        }
    }

}

