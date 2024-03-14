<?php

/*
  Plugin Name: WordPress SEO Images
  Description: SEO image Manager, Improve google images ranking, developed by AdrenalinaIG + Interdigital
  Author: Eduard Oliva and Pablo Martinez
  Version: 1.1
  Author URI: http://adrenalina.es/
 */

register_activation_hook(__FILE__, 'wp_seo_images_activation_hook');
register_deactivation_hook(__FILE__, 'wp_seo_images_deactivation_hook');

add_action('wp_print_scripts', 'wp_seo_images_enqueue_wp_seo_images_scripts');
add_action('admin_menu', 'wp_seo_images_menu');
add_action('wp_ajax_wp_seo_images_action_update', 'wp_seo_images_action_update_callback');
add_action('wp_ajax_wp_seo_images_action_get', 'wp_seo_images_action_get');
add_action('wp_ajax_nopriv_wp_seo_images_action_get', 'wp_seo_images_action_get');

function wp_seo_images_activation_hook()
{
    global $wpdb;
    $charset_collate = '';
    if (method_exists($wpdb, 'has_cap') && $wpdb->has_cap('collation'))
    {
        if (!empty($wpdb->charset))
        {
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }
        if (!empty($wpdb->collate))
        {
            $charset_collate .= "COLLATE {$wpdb->collate}";
        }
    }
    $table_name = $wpdb->prefix . "seo_images";

    $sql_crete_table = "CREATE TABLE IF NOT EXISTS {$table_name} (
     `wgi_id` int(11) NOT NULL AUTO_INCREMENT,
     `wgi_text` varchar(255) NOT NULL,
     PRIMARY KEY (`wgi_id`)
   ) ENGINE=MyISAM {$charset_collate} AUTO_INCREMENT=1";
    $wpdb->query($sql_crete_table);

    $sql_insert = "INSERT INTO {$table_name} (`wgi_id` ,`wgi_text`) VALUES (NULL,'watermark text')";
    $wpdb->query($sql_insert);
}

function wp_seo_images_deactivation_hook()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "seo_images";
    $wpdb->query("DROP TABLE {$table_name}");
}

function wp_seo_images_enqueue_wp_seo_images_scripts()
{
    wp_enqueue_script('ajax-script', plugins_url('/assets/js/admin.js', __FILE__), array('jquery'));
}

function wp_seo_images_menu()
{
    add_options_page('SEO Images', 'SEO Images', 'manage_options', 'wp-seo-images', 'call_wp_seo_images_menu');
    return;
}

function call_wp_seo_images_menu()
{
    require ('update_options.php');
}

function wp_seo_images_action_update_callback()
{
    global $wpdb;
    $wgi_text = $_POST['wgi_text'];

    $table_name = $wpdb->prefix . 'seo_images';

    $update_watermark_text = $wpdb->prepare(
        "UPDATE {$table_name} SET wgi_text = %s WHERE wgi_id = 1"
        , $wgi_text
    );
    $wpdb->query($update_watermark_text);
}

function wp_seo_images_action_get()
{
    Show_image::process($_GET['url']);
}

Class Show_image
{

    private static $attr = null;
    private static $data = null;

    public static function process($request = null)
    {
        $qselect_watermark_text = self::get_watermark_text();
        $filename = realpath(ABSPATH . trim($request, '/'));

        if (!empty($filename))
        {
            self::$attr = getimagesize($filename);
            self::$data = file_get_contents($filename);
            try
            {
                if (function_exists('imagecreatefromstring')
                    && !empty($qselect_watermark_text)
                    && !empty($qselect_watermark_text->wgi_text))
                {

                    $fontsize = (self::$attr[0] < 100) ? 1 : (
                        (self::$attr[0] < 200) ? 2 : (
                            (self::$attr[0] < 380) ? 3 : (
                                (self::$attr[0] < 640) ? 4 : (
                                    5)
                                )
                            )
                        );

                    $block_top = (int) (self::$attr[1] / 1.5);
                    $block_height = self::$attr[1] - (int) (self::$attr[1] / 1.5);
                    ($block_height < 50)
                        and $block_height = 50;
                    $img = imagecreatefromstring(self::$data);
                    $black = imagecolorallocate($img, 0, 0, 0);
                    $white = imagecolorallocate($img, 255, 255, 255);
                    $img_block = imagecreatetruecolor(self::$attr[0], $block_height);
                    imagefilledrectangle($img_block, 0, 0, self::$attr[0], $block_height, $black);
                    imagecopymerge($img, $img_block, 0, self::$attr[1] - $block_height, 0, 0, self::$attr[0], self::$attr[1], 60);
                    $font_height = imagefontheight($fontsize);
                    $font_width = imagefontwidth($fontsize);
                    $chars = (self::$attr[0] / $font_width ) - 1;
                    foreach (explode("\n", wordwrap($qselect_watermark_text->wgi_text, $chars)) as $key => $string)
                    {
                        imagestring($img, $fontsize, $font_height * 0.8, $block_top + $font_height * 0.6 + ($key * $font_height * 1.2), $string, $white);
                    }

                    imageinterlace($img, true);

                    ob_start();
                    if (self::$attr[2] == IMAGETYPE_JPEG)
                    {
                        imagejpeg($img, null, 90);
                    }
                    else
                    {
                        imagepng($img, null, 9);
                        self::$attr[2] = IMAGETYPE_PNG;
                    }

                    self::$data = ob_get_contents();
                    ob_end_clean();
                    imagedestroy($img);
                }
            }
            catch (Exception $e)
            {
                
            }
            return self::send();
        }
    }

    private static function send()
    {
        header('X-Server: WordPress SEO Images plugin');
        header('Content-type: ' . image_type_to_mime_type(self::$attr[2]), true);
        echo self::$data;
        return true;
    }

    private static function get_watermark_text()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'seo_images';
        $select_watermark_text = "SELECT * FROM {$table_name} WHERE wgi_id = 1 LIMIT 1";
        return $wpdb->get_row($select_watermark_text);
    }

}
