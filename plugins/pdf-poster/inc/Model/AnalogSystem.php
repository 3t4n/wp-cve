<?php
namespace PDFPro\Model;
use PDFPro\Helper\DefaultArgs;
use PDFPro\Services\PDFTemplate;

class AnalogSystem{

    public static function html($id, $raw =  false){
        $data  = DefaultArgs::parseArgs(self::getData($id, $raw));
        return PDFTemplate::html($data);
    }


    public static function parseArgs($data){
        $default = DefaultArgs::get();
        $data['options'] = wp_parse_args( $data['options'], $default['options'] );
        $data['infos'] = wp_parse_args( $data['infos'], $default['infos'] );
        $data['template'] = wp_parse_args( $data['template'], $default['template'] );

        return wp_parse_args( $data, $default );
    }

    public static function getData($id, $raw){
        $options = [];

        $infos = [
            'protect' => self::GPM($id, 'protect', false, true),
            'alert' => (boolean) self::GPM($id, 'disable_alert', false, true) == true ? false : true,
        ];

        $height = self::GPM($id, 'height', ['height' => 1122, 'unit' => 'px']);
        $width = self::GPM($id, 'width', ['width' => 100, 'unit' => '%']);
        $template = array(
            'title' => '',
            'file' => self::GPM($id, 'source', ''),
            'height' => $height['height'].$height['unit'],
            'width' => $width['width'].$width['unit'],
            'classes' => '',
            'showName' => self::GPM($id, 'show_filename', false, true),
            'print' => self::GPM($id, 'print', false, 'false') == '1' ? 'vera' : false,
            'titleFontSize' => '16px',
            'onlyPDF' => self::GPM($id, 'only_pdf', false, true),
            'raw' => $raw ? true : self::GPM($id, 'only_pdf', false, true),
            'defaultBrowser' => self::GPM($id, 'default_browser', false, true),
            'downloadButton' => self::GPM($id, 'show_download_btn', false, true),
            'downloadButtonText' => self::GPM($id, 'download_btn_text', 'Download File'),
            'fullscreenButton' => self::GPM($id, 'view_fullscreen_btn', true, true),
            'fullscreenButtonText' => self::GPM($id, 'fullscreen_btn_text', 'View Fullscreen'),
            'newWindow' => self::GPM($id, 'view_fullscreen_btn_target_blank', false, true),
            'protect' => self::GPM($id, 'protect', false, true),
            'thumbMenu' => self::GPM($id, 'thumbnail_toggle_menu', '0') == '1' ? 'true' : 'false',
            'sidebarOpen' => self::GPM($id, 'sidebar_open', '0') == '1' ? 'true' : 'false',
            'initialPage' => self::GPM($id, 'jump_to', 0),
            'lastVersion' => self::GPM($id, 'ppv_load_last_version', false),
            'hrscroll' => self::GPM($id, 'hr_scroll', false),
            'zoomLevel' => self::GPM($id, 'zoomLevel', 'auto'),
            'download' => 'false',
            'popupBtnText' => self::GPM($id, 'popupBtnText', 'Open Document'),
        );

        return [
            'options' => $options,
            'infos' => $infos,
            'template' => $template,
            'additional' => []
        ];

    }


    public static function get_post_meta($id, $key, $default = false){
        if (metadata_exists('post', $id, $key)) {
            $value = get_post_meta($id, $key, true);
            if ($value != '') {
                return $value;
            } else {
                return $default;
            }
        } else {
            return $default;
        }
    }

    /**
     * GPM = get post meta
     */
    public static function GPM($id, $key, $default = false, $true = false){
        $meta = metadata_exists( 'post', $id, '_fpdf' ) ? get_post_meta($id, '_fpdf', true) : '';
        if(isset($meta[$key]) && $meta != ''){
            if($true == true){
                if($meta[$key] == '1'){
                    return true;
                }else if($meta[$key] == '0'){
                    return false;
                }
            }else {
                return $meta[$key];
            }
            
        }

        return $default;
    }

    public static function getQuickPlayerData(){
        
    }
}