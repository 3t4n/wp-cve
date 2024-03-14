<?php
namespace PDFPro\Model;
use PDFPro\Model\Block;
use PDFPro\Helper\DefaultArgs;
use PDFPro\Services\PDFTemplate;

class AdvanceSystem{

    public static function html($id, $raw = false){
        $blocks =  Block::getBlock($id);
        $output = '';
        if(is_array($blocks)){
            foreach($blocks as $block){
                if(isset($block['attrs'])){
                    $output .= render_block($block);
                }else {
                    $data = DefaultArgs::parseArgs(self::getData($block, $raw));
                    $output .= PDFTemplate::html($data);
                }
            }
        }
        return $output;
    }

    public static function getData($block, $raw = false){
        
        $options = [];

        $infos = [
            'protect' => self::i($block, 'protect', '', false),
            'alert' => self::i($block, 'alert', '', false),
            'adobeOptions' => self::i($block, 'adobeOptions', '', []),
        ];

        $template = array(
            'classes' => 'pdfp_initialize',
            'file' => self::i($block, 'file', '', ''),
            'title' => self::i($block, 'title', '', ''),
            'titleFontSize' => self::i($block, 'titleFontSize', '', '16px'),
            'height' => self::i($block, 'height', '', '1122px'),
            'width' => self::i($block, 'width', '', '100%'),
            'onlyPDF' => self::i($block, 'onlyPDF', '', false) == true ? 'vera' : false,
            'raw' => $raw ? $raw : self::i($block, 'onlyPDF', '', false),
            'print' => self::i($block, 'print', '', false) == true ? 'vera' : false,
            'defaultBrowser' => self::i($block, 'defaultBrowser', '', false),
            'adobeEmbedder' => self::i($block, 'adobeEmbedder', '', false),
            'showName' => self::i($block, 'showName', '', false),
            'downloadButton' => self::i($block, 'downloadButton', '', false),
            'downloadButtonText' => self::i($block, 'downloadButtonText', '', 'Download File'),
            'fullscreenButton' => self::i($block, 'fullscreenButton', '', true),
            'fullscreenButtonText' => self::i($block, 'fullscreenButtonText', '', 'Vew Fullscreen'),
            'newWindow' => self::i($block, 'newWindow', '', false),
            'protect' => self::i($block, 'protect', '', false),
            'lastVersion' => self::i($block, 'lastVersion', '', false),
            'hrscroll' => self::i($block, 'hrScroll', '', false),
            'zoomLevel' => self::i($block, 'zoomLevel', '', 'auto'),
            'sidebarOpen' => self::i($block, 'sidebarOpen', '', false) == true ? 'true' : 'false',
            'thumbMenu' => self::i($block, 'thumbMenu', '', false) == true ? 'true' : 'false',
            'initialPage' => self::i($block, 'initialPage', '', 0),
            'align' => self::i($block, 'align', ''),
            'CSS' => self::i($block, 'CSS', '' ),
            'uniqueId' => self::i($block, 'uniqueId', '' ),
            'popupBtnText' => self::i($block, 'popupBtnText', '', 'Open Document'),
            'embedMode' => self::i($block, 'adobeOptions', 'embedMode')
        );

        $result = [
            'options' => $options,
            'infos' => $infos,
            'template' => $template,
            'additional' => self::i($block, 'additional', '', [])
        ];

        return $result;
    }

    public static function i($array, $key1, $key2 = '', $default = false){
        if(isset($array[$key1][$key2])){
            return $array[$key1][$key2];
        }else if (isset($array[$key1])){
            return $array[$key1];
        }
        return $default;
    }

}