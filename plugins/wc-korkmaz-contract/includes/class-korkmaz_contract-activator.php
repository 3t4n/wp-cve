<?php

/**
 * Fired during plugin activation
 *
 * @link       http://yemlihakorkmaz.com
 * @since      1.0.0
 *
 * @package    Korkmaz_contract
 * @subpackage Korkmaz_contract/includes
 */


/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Korkmaz_contract
 * @subpackage Korkmaz_contract/includes
 * @author     Yemliha KORKMAZ <yemlihakorkmaz@hotmail.com>
 */
class Korkmaz_contract_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {

        // //burada dosya kontrolü yapılıyor    yoksa oluşturuluyor
        // $current_user = wp_get_current_user();
        // $upload_dir   = wp_upload_dir();



        // if (isset($current_user->user_login) && !empty($upload_dir['basedir'])) {
        //     $sozlesmedosyasi = $upload_dir['basedir'] . '/korkmazsozlesme';
        //     $htac = $upload_dir['basedir'] . '/korkmazsozlesme/.htaccess';
        //     if (!file_exists($sozlesmedosyasi)) {
        //         wp_mkdir_p($sozlesmedosyasi);
        //     }

        //     if (!file_exists($htac)) {
        //         $icerikk = 'Options -Indexes';
        //         file_put_contents('.htaccess', $icerikk);
        //     }
        // }

        self::dosya_olustur();


        update_option('birinci_sozlesme_link_ismi', __('Ön Bilgilendirme Formu', 'korkmaz_contract'));

        update_option('ikinci_sozlesme_link_ismi', __('Mesafeli Satış Sözleşmesi', 'korkmaz_contract'));

        update_option('sozlesme_ozellik_1', '1');

        update_option('sozlesme_ozellik_2', '1');

        update_option('sozlesme_ozellik_3', '1');

        update_option('sozlesme_ozellik_4', '1');

        update_option('sozlesme_ozellik_5', '1');

        update_option('sozlesme_ozellik_6', '1');
    }


    public static function dosya_olustur()
    {
        $current_user = wp_get_current_user();
        $yol   = wp_upload_dir();
        $upload_dir = $yol['basedir'] . '/korkmazsozlesme';

        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0755);
        }

        $files_to_create = array('.htaccess' => 'Options -Indexes', 'index.php' => '<?php // Silence is golden');
        foreach ($files_to_create as $file => $file_content) {
            if (!file_exists($upload_dir . '/' . $file)) {
                $fh = @fopen($upload_dir . '/' . $file, "w");
                if (is_resource($fh)) {
                    fwrite($fh, $file_content);
                    fclose($fh);
                }
            }
        }
    }
}
