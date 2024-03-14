<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class UXGallery_Install
{

    /**
     * Install  Gallery Image.
     */
    public static function install()
    {
        if (!defined('UXGALLERY_INSTALLING')) {
            define('UXGALLERY_INSTALLING', true);
        }

        set_time_limit(100);

        self::create_tables();
        // Flush rules after install
        flush_rewrite_rules();
        // Trigger action
        do_action('uxgallery_installed');
    }

    private static function create_tables()
    {
        global $wpdb;
/// creat database tables
        $sql_ux_gallery_images = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "ux_gallery_images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `gallery_id` varchar(200) DEFAULT NULL,
  `description` text,
  `image_url` text,
  `sl_url` varchar(128) DEFAULT NULL,
  `sl_type` text NOT NULL,
  `link_target` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(4) unsigned DEFAULT NULL,
  `published_in_sl_width` tinyint(4) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
)";
        $sql_ux_gallery_like_dislike = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "ux_gallery_like_dislike` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_id` int(11) NOT NULL,
  `image_status` varchar(10) NOT NULL,
  `ip` varchar(35) NOT NULL,
  `cook` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
)";
        $sql_ux_gallery_gallerys = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "ux_gallery_gallerys` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `sl_height` int(11) unsigned DEFAULT NULL,
  `sl_width` int(11) unsigned DEFAULT NULL,
  `pause_on_hover` text,
  `gallery_list_effects_s` text,
  `description` text,
  `param` text,
  `sl_position` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` text,
   `ux_sl_effects` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
)";

        //create album table
        $sql_ux_gallery_albums = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "ux_gallery_albums` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `category` text,
  `sl_height` int(11) unsigned DEFAULT NULL,
  `sl_width` int(11) unsigned DEFAULT NULL,
  `gallery_list_effects_s` text,
  `description` text,
  `sl_position` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` text,
  `photo_gallery_wp_sl_effects` text,
  `photo_gallery_wp_album_style` int(2) DEFAULT 3,
  `cover_image` varchar(255) NULL,
  `gallery_loader_type` tinyint DEFAULT 1,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)";

        //create categories table
        $sql_uxgallery_album_categories = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "ux_gallery_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)";

        //create album has gallery table

        $sql_ux_gallery_album_has_gallery = "
             CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "ux_gallery_album_has_gallery` (
   `id_album` int(11) unsigned NOT NULL,
   `id_gallery` int(11) unsigned NOT NULL,
   `order` int(11) unsigned NOT NULL DEFAULT 0,
   `cover_image` varchar(255) NULL,
   `categories` varchar(255) NULL,
   PRIMARY KEY (`id_album`,`id_gallery`)
 )";


        $table_name = $wpdb->prefix . "ux_gallery_images";
        $album_table_name = $wpdb->prefix . "ux_gallery_albums";
        $album_has_gallery = $wpdb->prefix . "ux_gallery_album_has_gallery";

	    $sql_2 = "
INSERT INTO 
`" . $table_name . "` (`id`, `name`, `gallery_id`, `description`, `image_url`, `sl_url`, `sl_type`, `link_target`, `ordering`, `published`, `published_in_sl_width`) VALUES
(1, 'Turpis egestas', '1', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p><p>Eget sit amet tellus cras adipiscing enim.</p>', '" . UXGALLERY_IMAGES_URL . "/front_images/projects/project-1.jpg" . "', 'https://uxgallery.net/demo/', 'image', 'on', 0, 1, NULL),
(2, 'Diam sollicitudin', '1', '<ul><li>Donec ultrices tincidunt arcu non sodales neque sodales ut etiam.</li></ul><p>Sed faucibus turpis in eu mi bibendum. Tempus iaculis urna id volutpat lacus laoreet non curabitur.</p>', '" . UXGALLERY_IMAGES_URL . "/front_images/projects/project-2.jpg" . "', 'https://uxgallery.net/demo/', 'image', 'on', 1, 1, NULL),
(3, 'Ornare quam', '1', '<ul><li>Purus sit amet luctus venenatis lectus.</li><li>Morbi tristique senectus</li></ul><p>Eget aliquet nibh praesent tristique magna sit amet purus. Massa enim nec dui nunc mattis enim ut tellus elementum.</p>', '" . UXGALLERY_IMAGES_URL . "/front_images/projects/project-3.jpg" . "', 'https://uxgallery.net/demo/', 'image', 'on', 1, 1, NULL),
(4, 'Fringilla urna', '1', '<h6>Donec ultrices </h6><p>Nunc non blandit massa enim nec. Non nisi est sit amet facilisis. Ante metus dictum at tempor commodo. Convallis tellus id interdum velit laoreet id donec ultrices. </p><p>Massa massa ultricies mi quis. Ut placerat orci nulla pellentesque dignissim enim sit.</p><ul><li>lorem ipsum</li><li>dolor sit amet</li><li>lorem ipsum</li><li>scelerisque in dictum.</li></ul>', '" . UXGALLERY_IMAGES_URL . "/front_images/projects/project-4.jpg" . "', 'https://uxgallery.net/demo/', 'image', 'on', 2, 1, NULL)";
        $table_name = $wpdb->prefix . "ux_gallery_gallerys";
        $sql_3 = "
INSERT INTO `$table_name` (`id`, `name`, `sl_height`, `sl_width`, `pause_on_hover`, `gallery_list_effects_s`, `description`, `param`, `sl_position`, `ordering`, `published`, `ux_sl_effects`) VALUES
(1, 'My First Gallery', 375, 600, 'on', 'random', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', '1000', 'center', 1, '300', '5')";

        $sql_4 = "
INSERT INTO `$album_table_name` (`id`, `name`, `sl_height`, `sl_width`, `gallery_list_effects_s`, `description`, `sl_position`, `ordering`, `published`, `photo_gallery_wp_sl_effects`) VALUES
(1, 'Demo Album', 375, 600, 'random', 'My first Album description', 'center', 1, '300', '5')";


        $wpdb->query($sql_ux_gallery_images);
        $wpdb->query($sql_ux_gallery_gallerys);
        $wpdb->query($sql_ux_gallery_albums);
        $wpdb->query($sql_uxgallery_album_categories);
        $wpdb->query($sql_ux_gallery_album_has_gallery);
        $wpdb->query($sql_ux_gallery_like_dislike);


        /////// add columns if not exist in album_has_gallery table

        $album_has_gallery_all_fields = $wpdb->get_results("DESCRIBE " . $wpdb->prefix . "ux_gallery_album_has_gallery", ARRAY_A);
        $foralbumUpdate = 0;
        foreach ($album_has_gallery_all_fields as $field) {
            if ($field['Field'] == 'cover_image' || $field['Field'] == 'categories') {
                $foralbumUpdate = 1;
            }
        }
        if ($foralbumUpdate != 1) {
            $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "photo_gallery_wp_album_has_gallery` ADD `cover_image` varchar( 255 )  NULL  after `order`");
            $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "photo_gallery_wp_album_has_gallery` ADD `categories` varchar( 255 )  NULL  after `cover_image`");
        }


        if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "ux_gallery_images")) {
            $wpdb->query($sql_2);
        }
        if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "ux_gallery_gallerys")) {
            $wpdb->query($sql_3);
        }
        if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "ux_gallery_albums")) {
            $wpdb->query($sql_4);
        }

        ////////////////////////////////////////
        $imagesAllFieldsInArray2 = $wpdb->get_results("DESCRIBE " . $wpdb->prefix . "ux_gallery_gallerys", ARRAY_A);
        $fornewUpdate = 0;
        foreach ($imagesAllFieldsInArray2 as $galleriesField2) {
            if ($galleriesField2['Field'] == 'display_type') {
                $fornewUpdate = 1;
            }
        }
        if ($fornewUpdate != 1) {
            $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ux_gallery_gallerys ADD display_type integer DEFAULT '2' ");
            $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ux_gallery_gallerys ADD content_per_page integer DEFAULT '5' ");
        }
        ///////////////////////////////////////////////////////////////////////
        $imagesAllFieldsInArray3 = $wpdb->get_results("DESCRIBE " . $wpdb->prefix . "ux_gallery_images", ARRAY_A);
        $fornewUpdate2 = 0;
        foreach ($imagesAllFieldsInArray3 as $galleriesField3) {
            if ($galleriesField3['Field'] == 'sl_url' && $galleriesField3['Type'] == 'text') {
                $fornewUpdate2 = 1;
            }
        }
        if ($fornewUpdate2 != 1) {
            $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ux_gallery_images CHANGE sl_url sl_url TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL");
        }
        //ADDING LIKE/DISLIKE COLUMNS
        ///////////////////////////////////////////////////////////////////////
        $imagesAllFieldsInArray4 = $wpdb->get_results("DESCRIBE " . $wpdb->prefix . "ux_gallery_images", ARRAY_A);
        $fornewUpdate3 = 0;
        foreach ($imagesAllFieldsInArray4 as $galleriesField4) {
            if ($galleriesField4['Field'] == 'like') {
                $fornewUpdate3 = 1;
            }
        }
        if ($fornewUpdate3 != 1) {
            $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ux_gallery_images  ADD `like` INT NOT NULL DEFAULT 0 AFTER `published_in_sl_width`");
            $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ux_gallery_images  ADD `dislike` INT NOT NULL DEFAULT '0' AFTER `like`");
        }
        //ADDING Rating COLUMNS
        $imagesAllFieldsInArray5 = $wpdb->get_results("DESCRIBE " . $wpdb->prefix . "ux_gallery_gallerys", ARRAY_A);
        $fornewUpdate4 = 0;
        foreach ($imagesAllFieldsInArray5 as $galleriesField5) {
            if ($galleriesField5['Field'] == 'rating') {
                $fornewUpdate4 = 1;
            }
        }
        if ($fornewUpdate4 != 1) {
            $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ux_gallery_gallerys  ADD `rating` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'off'");
        }
        /////////////////////////////////////////////
        $imagesAllFieldsInArray6 = $wpdb->get_results("DESCRIBE " . $wpdb->prefix . "ux_gallery_gallerys", ARRAY_A);
        $fornewUpdate5 = 0;
        foreach ($imagesAllFieldsInArray6 as $galleriesField6) {
            if ($galleriesField5['Field'] == 'autoslide') {
                $fornewUpdate5 = 1;
            }
        }
        if ($fornewUpdate5 != 1) {
            $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ux_gallery_gallerys  ADD `autoslide` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'on'");
        }
        $table_name = $wpdb->prefix . "ux_gallery_images";
        $query = "SELECT id,image_url,gallery_id FROM " . $table_name . " WHERE gallery_id=1";
        $images_url = $wpdb->get_results($query);
        foreach ($images_url as $image_url) {
            if (strpos($image_url->image_url, '/gallery-images/Front_images') > -1) {

                $new_url = str_replace('gallery-images/Front_images/', 'gallery-images/assets/images/front_images/', $image_url->image_url);
                $wpdb->query($wpdb->prepare("UPDATE " . $table_name . " SET image_url= %s WHERE id=%d", $new_url, $image_url->id));
            }
            if (strpos($image_url->image_url, '/gallery-images-pro-master/Front_images') > -1) {
                $new_url = str_replace('gallery-images-pro-master/Front_images/', 'gallery-images-pro-master/assets/images/front_images/', $image_url->image_url);
                $wpdb->query($wpdb->prepare("UPDATE " . $table_name . " SET image_url= %s WHERE id=%d", $new_url, $image_url->id));
            }
        }
        $table_name = $wpdb->prefix . 'uxgallery_params';
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
            $query = "SELECT name,value FROM " . $table_name;
            $gallery_table_params = $wpdb->get_results($query);
        }
        $gallery_default_params = uxgallery_get_general_options();
        if (!(get_option('ht_blog_heart_likedislike_thumb_active_color'))) {
            if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
                if (count($gallery_table_params) > 0) {
                    foreach ($gallery_table_params as $gallery_table_param) {
                        update_option($gallery_table_param->name, $gallery_table_param->value);
                    }
                }
            } else {
                foreach ($gallery_default_params as $name => $value) {
                    update_option($name, $value);
                }
            }
        }
        if (!(get_option('uxgallery_ht_blog_heart_likedislike_thumb_active_color'))) {
            foreach ($gallery_default_params as $name => $value) {
                if (strpos($name, 'uxgallery_') === false) {
                    update_option('uxgallery_' . $name, get_option($name));
                    delete_option($name);
                } else {
                    update_option($name, $value);
                }
            }
        }
        if (!get_option('uxgallery_admin_image_hover_preview')) {
            add_option('uxgallery_admin_image_hover_preview');
        }

        if (!get_option('uxgallery_disable_right_click')) {
            update_option('uxgallery_disable_right_click', 'off');
        }


        $lightbox_options = array(
            'uxgallery_lightbox_slideAnimationType' => 'effect_1',
            'uxgallery_lightbox_lightboxView' => 'view1',
            'uxgallery_lightbox_speed_new' => '600',
            'uxgallery_lightbox_width_new' => '100',
            'uxgallery_lightbox_height_new' => '100',
            'uxgallery_lightbox_videoMaxWidth' => '790',
            'uxgallery_lightbox_overlayDuration' => '150',
            'uxgallery_lightbox_overlayClose_new' => 'true',
            'uxgallery_lightbox_loop_new' => 'true',
            'uxgallery_lightbox_escKey_new' => 'true',
            'uxgallery_lightbox_keyPress_new' => 'true',
            'uxgallery_lightbox_arrows' => 'true',
            'uxgallery_lightbox_mouseWheel' => 'true',
            'uxgallery_lightbox_download' => 'false',
            'uxgallery_lightbox_showCounter' => 'true',
            'uxgallery_lightbox_nextHtml' => '',     //not used
            'uxgallery_lightbox_prevHtml' => '',     //not used
            'uxgallery_lightbox_sequence_info' => 'image',
            'uxgallery_lightbox_sequenceInfo' => 'of',
            'uxgallery_lightbox_slideshow_new' => 'true',
            'uxgallery_lightbox_slideshow_auto_new' => 'false',
            'uxgallery_lightbox_slideshow_speed_new' => '2500',
            'uxgallery_lightbox_slideshow_start_new' => '',     //not used
            'uxgallery_lightbox_slideshow_stop_new' => '',     //not used
            'uxgallery_lightbox_watermark' => 'false',
            'uxgallery_lightbox_socialSharing' => 'true',
            'uxgallery_lightbox_facebookButton' => 'true',
            'uxgallery_lightbox_twitterButton' => 'true',
            'uxgallery_lightbox_googleplusButton' => 'true',
            'uxgallery_lightbox_pinterestButton' => 'false',
            'uxgallery_lightbox_linkedinButton' => 'false',
            'uxgallery_lightbox_tumblrButton' => 'false',
            'uxgallery_lightbox_redditButton' => 'false',
            'uxgallery_lightbox_bufferButton' => 'false',
            'uxgallery_lightbox_diggButton' => 'false',
            'uxgallery_lightbox_vkButton' => 'false',
            'uxgallery_lightbox_yummlyButton' => 'false',
            'uxgallery_lightbox_watermark_text' => 'WaterMark',
            'uxgallery_lightbox_watermark_textColor' => 'ffffff',
            'uxgallery_lightbox_watermark_textFontSize' => '30',
            'uxgallery_lightbox_watermark_containerBackground' => '000000',
            'uxgallery_lightbox_watermark_containerOpacity' => '90',
            'uxgallery_lightbox_watermark_containerWidth' => '300',
            'uxgallery_lightbox_watermark_position_new' => '9',
            'uxgallery_lightbox_watermark_opacity' => '70',
            'uxgallery_lightbox_watermark_margin' => '10',
            'uxgallery_lightbox_watermark_img_src_new' => UXGALLERY_IMAGES_URL . '/admin_images/No-image-found.jpg',
            'uxgallery_lightbox_type' => 'old_type'
        );

        if (!get_option('uxgallery_lightbox_watermark_img_src_new')) {
            foreach ($lightbox_options as $name => $value) {
                update_option($name, $value);
            }
        }

    }

}