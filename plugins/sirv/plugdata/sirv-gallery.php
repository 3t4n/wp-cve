<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Sirv_Gallery
{
    private $params;
    private $items;
    private $captions;
    private $initialized = false;
    private $inline_css = array();
    //private $network_type;

    public function __construct($params=array(),$items=array(),$captions=array())
    {
        $this->params = array(
            'width'     => 'auto',
            'height'    => 'auto',
            'is_gallery'=> false,
            'profile'   => false,
            'default_profile' => get_option('SIRV_SHORTCODES_PROFILES'),
            'link_image'=> false,
            'show_caption'=> true,
            'thumbnails_height' => 80,
            'apply_zoom'=> false,
            'gallery_styles' => '',
            'gallery_align' => '',
            'zgallery_data_options' => array(),
            'zgallery_thumbs_position' => 'bottom',
            'spin_options' => array(),
            'global_options' => array(),
        );

        foreach($params as $name=>$value) {
            $this->params[$name] = $value;
        }

        if (empty($this->params['id'])) {
            $this->params['id'] = substr(uniqid(rand(),true),0,10);
        }

        $this->params['id'] = 'sirv-gallery-'.$this->params['id'];

        //$this->network_type = isset($this->params['global_options']['networkType']) ? $this->params['global_options']['networkType'] : 'cdn';

        $this->items = $items;
        $this->captions = $captions;

        return true;
    }

    public function render_data_options($data_options_arr, $type){
        if(empty($data_options_arr)) return '';

        $data_options_str = '';
        foreach ($data_options_arr as $key => $value) {
            if($key == 'thumbnails'){
                $data_options_str = "{$key} : #sirv-thumbs-box-" . $this->params['id'] . "; ";
            }else{
                $data_options_str .= $type == 'data' ? "{$key} : {$value}; " : "{$key}={$value}&";
            }
        }

        $data_options_str .= $this->thumbs_orientation($type);

        $data_options_str = substr($data_options_str, -1) == '&' ? substr($data_options_str, 0, -1) : $data_options_str;
        return $data_options_str;
    }

    public function thumbs_orientation($type){
        if($type !== 'data') return '';

        $orientation = ' thumbnails-orientation:horizontal;';
        switch ($this->params['zgallery_thumbs_position']) {
            case 'left':
            case 'right':
                $orientation = ' thumbnails-orientation:vertical;';
                break;
        }

        return $orientation;
    }


    public function addCss($rule) {
        $this->inline_css[] = $rule;
    }


    public function getSpinParams($size){
        return "image&w=$size&h=$size&canvas.width=$size&canvas.height=$size&scale.option=fit";
    }


    public function fixUrl($url){
        $sirv_cdn_url = get_option('SIRV_CDN_URL');

        $p_url = parse_url($url);
        $m_url = 'https://' . $sirv_cdn_url . $p_url['path'];

        return $m_url;
    }


    public function getInlineCss() {
        return join("\r\n",$this->inline_css);
    }


    public function render() {
        if (count($this->items)==0) return '';

        $styles = array();
        if ($this->params['width']!='' && intval($this->params['width']) !== 0) {
                $this->addCss('#'.$this->params['id'].' { width: '.((preg_match('/%/',$this->params['width'])) ? intval($this->params['width']).'%' : intval($this->params['width']).'px').' }');
        } else {
            $this->addCss('#'.$this->params['id'].' { min-width: 200px; }');
        }

        $this->addCss('.sirv-hidden { display:none; }');
        $this->addCss('#'.$this->params['id'].'.sirv-thumbs-box, #'.$this->params['id'].' .sirv-zoom-thumbnails.thumbs-horizontal { height: '.($this->params['thumbnails_height']).'px !important; }');
        $this->addCss('#'.$this->params['id'].'.sirv-thumbs-box, #'.$this->params['id'].' .sirv-zoom-thumbnails.thumbs-vertical{ width: '.($this->params['thumbnails_height']).'px !important; }');
        $this->addCss('#'.$this->params['id'].'.sirv-thumbs-box, #'.$this->params['id'].' .fullscreen-mode .sirv-zoom-thumbnails.thumbs-vertical{ width: auto !important; }');


        $this->addCss('sirv-gallery.selectorsRight:not(.no-sirv-zoom) .sirv-thumbs-box{ flex-basis: '. $this->params['thumbnails_height'] .'px; width: '. $this->params['thumbnails_height'] .'px; }');
        $this->addCss('sirv-gallery.selectorsLeft:not(.no-sirv-zoom) .sirv-thumbs-box{ flex-basis: '. $this->params['thumbnails_height'] .'px; width: '. $this->params['thumbnails_height'] .'px; }');
        $this->addCss('sirv-gallery.selectorsTop:not(.no-sirv-zoom) .sirv-thumbs-box{ flex-basis: '. $this->params['thumbnails_height'] .'px; height: '. $this->params['thumbnails_height'] .'px; }');

        $this->addCss('div.sirv-gallery.selectorsLeft:not(.no-sirv-zoom) .sirv-gallery-item{ padding-left: '. (intval($this->params['thumbnails_height']) + 10) .'px;}');
        $this->addCss('div.sirv-gallery.selectorsRight:not(.no-sirv-zoom) .sirv-gallery-item{ padding-right: '. (intval($this->params['thumbnails_height']) + 10) .'px;}');
        $this->addCss('div.sirv-gallery.selectorsTop:not(.no-sirv-zoom) .sirv-gallery-item{ padding-top: '. (intval($this->params['thumbnails_height']) + 10) .'px;}');

        $html[] = '<div class="sirv-gallery selectors'. ucfirst($this->params['zgallery_thumbs_position']). ' '.$this->params['gallery_align'].' '.(($this->params['is_gallery'])?' is-sirv-gallery':' no-gallery').((!$this->params['apply_zoom'])?' no-sirv-zoom':'').' '.$this->params['gallery_styles'].'" id="'.$this->params['id'].'">';

        $profile = ((bool)$this->params['profile'])?'?profile='.$this->params['profile']:'';

        if($profile =='' && !empty($this->params['default_profile'])){
            $profile = '?profile=' . $this->params['default_profile'];
        }

        $spins = $spins_html = $images = array();

        $sirv_class = '';

        foreach($this->items as $i=>$item) {
            $caption = htmlspecialchars($this->captions[$i]);
            $caption_tag = ($this->params['show_caption'] && !$this->params['apply_zoom'])?'<div class="sirv-caption">'.$caption.'</div>':'';

            $item['url'] = $this->fixUrl($item['url']);

            $im_w = (int) $item['image_width'];
            $im_h = (int) $item['image_height'];
            //$im_proportion = ($im_w == 0 || $im_h == 0) ? 0 : ($im_w > $im_h) ? ($im_h/$im_w)*100 : 100;
            $im_proportion = ($im_w == 0 || $im_h == 0) ? 0 : ($im_h/$im_w)*100;
            //echo floor($im_proportion*100)/100;

            $max_width = '';
            $sub_div_style = ($im_proportion == 0) ? '' : 'style="position:relative; padding-bottom:'.(int)$im_proportion.'%; line-height: 0px; height: 0px; margin: auto;"';
            $img_style = $sub_div_style == '' ? '' : 'style="position:absolute; top:0; left: 0; bottom: 0; right: 0; margin: auto;"';

            //preg_match('/\.(mp4|mpg|mpeg|mov|qt|webm|avi|mp2|mpe|mpv|ogg|m4p|m4v|wmv|flw|swf|avchd)/is', $item['url'])
            if (preg_match('/\.spin/is',$item['url'])) {
                $paramSymbol = $profile == '' ? '?' : '&';
                $uri_options = $paramSymbol . $this->render_data_options($this->params['spin_options'], 'uri');
                $spins_html[$i] = '<div data-item-id="'.$i.'" class="sirv-gallery-item'.($this->params['apply_zoom'] || ($this->params['is_gallery']&&$i>0)?' sirv-hidden':'').'"><div class="Sirv" data-src="'.$item['url'].$profile. $uri_options . '"></div>'.$caption_tag.'</div>';
                $spins[$i] = $item['url'].$profile;
                $image_captions[] = '';
            }else {

                $image_captions[] = $this->captions[$i];

                if (!isset($defaultCaption)) {
                    $defaultCaption = $this->captions[$i];
                }

                $open_tag = ($this->params['link_image'])?'<a href="'.$item['url'].$profile.'">':'';
                $close_tag = ($this->params['link_image'])?'</a>':'';

                if ($this->params['apply_zoom']) {
                    $images[$i] = '<img data-title="'.$caption.'" class="Sirv" data-src="'.$item['url'].$profile.'"/>';
                } else {
                        $sirv_class = ($i == 0) ? 'Sirv' : '';
                        $images[$i] = '<div '.$max_width.' data-item-id="'.$i.'" class="sirv-gallery-item'.(($i>0 && $this->params['is_gallery'])?' sirv-hidden':'').'"><div '.$sub_div_style.'>'.$open_tag.'<img '.$img_style.' data-title="'.$caption.'" class="'. $sirv_class .'" data-src="'.$item['url'].$profile.'"/>'.$close_tag.'</div>'.$caption_tag.'</div>';
                }
            }
        }

        if ($this->params['is_gallery']) {
            if ($this->params['apply_zoom']) {
                $data_options = $this->render_data_options($this->params['zgallery_data_options'], 'data');
                $html[] = '<div data-item-id="sirv-zoom" class="sirv-gallery-item"><div class="Sirv" data-id="'.$this->params['id'].'" data-effect="zoom" data-options="'. $data_options .'">';
            }

            $html[] = join('',$images);

            if ($this->params['apply_zoom']) {
                $html[] = '</div></div>';
            }
            $html[] = join("\r\n",$spins_html);

            if ($this->params['apply_zoom'] && $this->params['show_caption']) {
                if (empty($defaultCaption)) {
                    $defaultCaption = '';
                }
                $html[] = '<div class="sirv-caption sirv-zoom-caption" style="display: none;">'.$defaultCaption.'</div>';
            }
$html[] = '<div class="sirv-thumbs-box" id="sirv-thumbs-box-'.$this->params['id'].'">';
            if ($this->params['is_gallery'] && !$this->params['apply_zoom'] && count($this->items) > 1) {
                $html[] = '<ul>';
                foreach($this->items as $i=>$item) {
                    $caption = htmlspecialchars($this->captions[$i]);
                    $t_height = $this->params['thumbnails_height'];
                    //$item['url'] = $this->network_type == 'cdn' ? str_replace('.sirv.com', '-cdn.sirv.com', $item['url']) : $item['url'];
                    $html[] = '<li'.(($i==0)?' class="sirv-thumb-selected"':'').'>
                                <img title="'.$caption.'" alt="'.$caption.'" data-item-id="'.$i.'" src="'.$item['url'].$profile.((empty($profile))?'?': '&').
                                ((preg_match('/.*\.spin$/is', $item['url'])) ? $this->getSpinParams($t_height): "thumbnail=$t_height").'"/></li>';
                }
                $html[] = '</ul>';

            }
            $html[] = '</div>';

        } else {
            $images = $spins_html+$images;
            ksort($images);

            foreach($images as $image) {
                $html[] = $image;
            }
        }

        $html[] = '</div>';

        $spins = ($this->params['is_gallery'] && $this->params['apply_zoom'] && count($spins))?json_encode($spins):'[]';

        $image_captions = json_encode($image_captions,JSON_HEX_APOS);
        $captions = json_encode($this->captions,JSON_HEX_APOS);


        $html = join("\r\n",$html);

        $html = preg_replace('/<div/i','<div data-spins=\''.$spins.'\' data-captions=\''.$captions.'\' data-image-captions=\''.$image_captions.'\' data-thumbnails-height="'.$this->params['thumbnails_height'].'"',$html,1);
        // $html = preg_replace('/<div/i','<div data-spins=\''.$spins.'\' data-captions="'.$captions.'" data-image-captions="'.$image_captions.'" data-thumbnails-height="'.$this->params['thumbnails_height'].'"',$html,1);

        return $html.'<style type="text/css">'.$this->getInlineCss().'</style>';

    }
}

?>
