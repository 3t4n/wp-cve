<?php

class Zwt_wp_linkpreviewer_Img_Tool
{

    public function resize($imgData, $isFull)
    {
        $dest_width = Zwt_wp_linkpreviewer_Constants::$FETCH_IMG_COMPACT_SIZE;
        $dest_height = Zwt_wp_linkpreviewer_Constants::$FETCH_IMG_COMPACT_SIZE;
        if($isFull){
            $dest_width = Zwt_wp_linkpreviewer_Constants::$FETCH_IMG_FULL_SIZE;
            $dest_height = round(Zwt_wp_linkpreviewer_Constants::$FETCH_IMG_FULL_SIZE*630/1200);
        }
        return $this->resizePng($imgData, $dest_width, $dest_height);
    }

    private function resizePng($imgData, $destWith, $destHeight){
        $ratio_thumb=$destWith/$destHeight;
        $img = imagecreatefromstring($imgData);
        $xx  = imagesx($img);
        $yy = imagesy($img);
        $ratio_original=$xx/$yy;
        if ($ratio_original>=$ratio_thumb) {
            $yo=$yy;
            $xo=ceil(($yo*$destWith)/$destHeight);
            $xo_ini=ceil(($xx-$xo)/2);
            $xy_ini=0;
        } else {
            $xo=$xx;
            $yo=ceil(($xo*$destHeight)/$destWith);
            $xy_ini=ceil(($yy-$yo)/2);
            $xo_ini=0;
        }
        $thumb = imagecreatetruecolor($destWith, $destHeight);
        imagecopyresampled($thumb, $img, 0, 0, $xo_ini, $xy_ini, $destWith, $destHeight, $xo, $yo);
        ob_start();
        imagejpeg($thumb, null, 75);
        return ob_get_clean();
    }

}