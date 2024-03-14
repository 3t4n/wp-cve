<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class jsHelperAchvImages
{
    public static function getEmblem($img, $type = 0, $class = '', $width = 0)
    {
        global $jsConfig;

        if ($width === 0) {
            $width = $jsConfig->get('teamlogo_height', 40);
        }
        if(is_array($img)){
            $image_arr = wp_get_attachment_image_src($img['id'], array($jsConfig->get('teamlogo_height',40),'auto'));
            if (($image_arr[0])) {
                $img = $image_arr[0];
            }else{
                $img = $img['src'];
            }
        }
        $defimg = $type ? JSCONF_TEAM_DEFAULT_IMG : JSCONF_PLAYER_DEFAULT_IMG;

        $html = '';
        $resize_to = 150;

        if ($width < 40) {
            $class .= ' emblpadd3';
        }

        if ($img) {
            $html = '<img alt="" class="img-thumbnail img-responsive'.$class.'" src="'.$img.'" '.($width?'width="'.$width.'"':"").'  style="max-width: '.$width.'px;" />';
        } else {
            $html = '<img alt="" class="img-thumbnail img-responsive'.$class.'" src="'.JOOMSPORT_LIVE_URL_IMAGES_DEF.$defimg.'" width="'.$width.'" style="max-width: '.$width.'px;" />';
        }

        return $html;
    }
    public static function getEmblemBig($img, $type = 1, $class = 'emblInline', $width = '0', $light = true)
    {
        global $jsConfig;
        $img_full = '';
        if(is_array($img)){
            $img_full = wp_get_attachment_image_src($img["id"], 'full');
            $img = $img['src'];
        }
        
        $add_styles = '';
        if (!$width) {
            $width = 200;//$jsConfig->get('set_defimgwidth', 200);
            $add_styles = 'style="width:'.$width.'px;max-width:'.$width.'px;"';
        }
        $html = '';
        $resize_to = 300;

        if ($img) {
            if ($light) {
                $html = '<a class="jsLightLink" href="'.(isset($img_full[0])?$img_full[0]:$img).'" data-lightbox="jsteam'.$type.'">';
            }
            $html .= '<img alt="" class="img-thumbnail img-responsive" src="'.$img.'" width="'.$width.'"  '.$add_styles.' />';
            if ($light) {
                $html .= '</a>';
            }
        } else {
            $html = '<img alt="" class="img-thumbnail img-responsive" src="'.JOOMSPORT_ACHV_LIVE_URL_IMAGES_DEF.JSCONF_ACHV_PLAYER_DEFAULT_IMG.'" width="'.$width.'"  '.$add_styles.' />';
        }

        return $html;
    }
    public static function getEmblemEvents($img, $type = 0, $class = '', $width = 24)
    {
        $html = '';
        $resize_to = 40;
        if ($width < 40) {
            $class .= ' emblpadd3';
        }
        $imgpath = wp_get_attachment_image_src($img);

        if(isset($imgpath[0]) && $imgpath[0]){
        $html .= '<img alt="" class="img-responsive '.$class.'"  src="'.$imgpath[0].'" width="'.$width.'" />';
        }
        return $html;
    }
}
