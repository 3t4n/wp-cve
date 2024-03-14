<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Sirv_Gallery_MV
{
    protected $params;
    protected $items;
    protected $captions;
    protected $inline_css = array();

    public function __construct($params = array(), $items = array(), $captions = array()){
        $this->params = array(
            'width'     => 'auto',
            'height'    => 'auto',
            'is_gallery' => false,
            'profile'   => '',
            'default_profile' => get_option('SIRV_SHORTCODES_PROFILES'),
            'link_image' => false,
            'show_caption' => false,
            'thumbnails_height' => 80,
            'apply_zoom' => false,
            'gallery_styles' => '',
            'gallery_align' => '',
            'zgallery_data_options' => array(),
            'zgallery_thumbs_position' => 'bottom',
            'spin_options' => array(),
            'global_options' => array(),
        );

        foreach ($params as $name => $value) {
            $this->params[$name] = $value;
        }

        if (empty($this->params['id'])) {
            $this->params['id'] = substr(uniqid(rand(), true), 0, 10);
        }

        $this->params['id'] = 'sirv-gallery-' . $this->params['id'];


        $this->items = $items;
        $this->captions = $captions;

        return true;
    }


    protected function addCss($rule){
        $this->inline_css[] = $rule;
    }


    protected function getInlineCss(){
        return join("\r\n", $this->inline_css);
    }


    protected function fixUrl($url){
        $sirv_cdn_url = get_option('SIRV_CDN_URL');

        $p_url = parse_url($url);
        $m_url = 'https://' . $sirv_cdn_url . $p_url['path'];

        $profile = $this->get_profile();

        if($profile) $m_url .= "?profile=$profile";

        return $m_url;
    }


    protected function get_profile(){
        $profile = '';

        if ($this->params['profile'] == '') {
            if ($this->params['default_profile'] !== '') {
                $profile = $this->params['default_profile'];
            }
        } else {
            $profile = $this->params['profile'];
        }

        return $profile;
    }


    protected function renderOptions($options){
        $opt_str = $this->optionsToString($options);
        return $opt_str ? ' data-options="' . $opt_str .'"' : '';
    }


    protected function optionsToString($options){
        $opt_str = '';

        foreach ($options as $key => $value) {
            if($value == null) continue;
            $opt_str .= "$key:$value;";
        }
        return $opt_str;
    }


    protected function get_smv_option($type_name, $option_name, $default){
        $smv_option = isset($this->params[$type_name][$option_name]) ? $this->params[$type_name][$option_name] : $default;

        if($smv_option == $default) $smv_option = null;

        return $smv_option;
    }


    protected function check_on_default_value($option, $default){
        if($option == $default) return null;

        return $option;
    }


    protected function getViewerOptions(){
        $thumbs_type = filter_var($this->params['zgallery_data_options']['squareThumbnails'], FILTER_VALIDATE_BOOLEAN) ? 'square' : 'auto';
        $thumbs_height = isset($this->params['thumbnails_height']) ? $this->params['thumbnails_height'] : null;

        return array(
            'thumbnails.position' => $this->get_smv_option('zgallery_data_options','thumbnails', 'bottom'),
            'thumbnails.type' => $this->check_on_default_value($thumbs_type, 'square'),
            'thumbnails.size' => $this->check_on_default_value($thumbs_height,  '70'),
            'fullscreen.always' => $this->get_smv_option('zgallery_data_options','fullscreen-only', 'false'),
            'contextmenu.enable' => $this->get_smv_option('zgallery_data_options','contextmenu', 'false'),
        );
    }


    protected function getVideoOptions(){
        return array(
            "autoplay" => $this->get_smv_option('zgallery_data_options','videoAutoplay', 'false'),
            "loop" => $this->get_smv_option('zgallery_data_options','videoLoop', 'false'),
            "controls.enable" => $this->get_smv_option('zgallery_data_options','videoControls', 'true'),
        );
    }


    protected function getModelOptions(){
        return array(
            "hint.finger" => $this->get_smv_option('zgallery_data_options','modelHintFinger', 'true'),
            "autorotate.enable" => $this->get_smv_option('zgallery_data_options','modelAutorotate', 'false'),
            "autorotate.speed" => $this->get_smv_option('zgallery_data_options','modelAutorotateSpeed', '15'),
            "autorotate.delay" => $this->get_smv_option('zgallery_data_options','modelAutorotateDelay', '0'),
            "shadow.intensity" => $this->get_smv_option('zgallery_data_options','modelShadowSlider', '0'),
        );
    }


    protected function getSpinOptions(){
        $autospinEnable = $this->params['spin_options']['autospin'] === 'on' ? 'true' : 'false';

        return array(
            'speed' => $this->get_smv_option('spin_options','autospinSpeed', '3600'),
            'autospin.enable' => $this->check_on_default_value($autospinEnable, 'false'),
        );
    }


    protected function getZoomOptions(){
        return array(
            'wheel' => $this->get_smv_option('zgallery_data_options','zoom-on-wheel',  'true'),
            //'mode' => 'deep',
        );
    }


    protected function getCaptions(){
        $captions = $this->params['show_caption'] ? $this->remove_tags($this->captions) : array();


        return json_encode($captions, JSON_HEX_QUOT | JSON_HEX_APOS);
    }


    /*
    * $taggedData string or array of strings
    * $allowedTags string or null
    *
    */
    protected function remove_tags($taggedData, $allowedTags = "<em>,<strong>,<b>,<i>,<br>,<a>"){
        if( is_array($taggedData) ){
            $tmpArr = array();

            for ($i = 0; $i < count($taggedData); $i++) {
                $tmpArr[] = strip_tags($taggedData[$i], $allowedTags);
            }
            return $tmpArr;
        }

        return strip_tags($taggedData, $allowedTags);
    }


    protected function fixCaptionPosition($id){
        $thumbsOrientation = $this->params['zgallery_data_options']['thumbnails'];
        $thumbsHeight = (int)$this->params['thumbnails_height'];
        $position = '';
        $width = '';

        if($thumbsOrientation == 'left'){
            $position = 'padding-left:' . ($thumbsHeight + 2) . 'px;';
        }else if($thumbsOrientation == 'right'){
            $position = 'padding-right:' . ($thumbsHeight + 2) . 'px;';
        }

        if (($this->params['width'] != '' && intval($this->params['width']) !== 0) && $thumbsOrientation !=='bottom' ) {
            $width = 'width: ' . $this->params['width'] . 'px;';
        }
        $this->addCss('.sirv-mv-caption.' . $id . "{". $position . $width ."}");

    }

    protected function getAlign(){
        $align = $this->params['gallery_align'];
        $align_class = '';
        if($align){
            switch($align){
                case 'sirv-left':
                    $align_class = 'sirv-mv-left';
                    break;
                case 'sirv-center':
                    $align_class = 'sirv-mv-center';
                    break;
                case 'sirv-right':
                    $align_class = 'sirv-mv-right';
                    break;
            }
        }

        return $align_class;
    }


    protected function get_global_smv_options(){
        global $post;
        global $sirv_woo_is_enable;

        if ($sirv_woo_is_enable){
            if(isset($post->post_type) && $post->post_type === 'product'){
                return '';
            }
        };

        $global_shortcode_smv_options = get_option("SIRV_CUSTOM_SMV_SH_OPTIONS");

        return "<script nowprocket>$global_shortcode_smv_options</script>" . PHP_EOL;
    }


    protected function get_smv_options(){
        return array(
            "viewer" => $this->renderOptions($this->getViewerOptions()),
            "spin" => $this->renderOptions($this->getSpinOptions()),
            "video" => $this->renderOptions($this->getVideoOptions()),
            "model" => $this->renderOptions($this->getModelOptions()),
            "zoom" => $this->renderOptions($this->getZoomOptions()),
        );
    }


    public function render(){
        if ($this->params['width'] != '' && intval($this->params['width']) !== 0) {
            $this->addCss('#' . $this->params['id'] . ' { width: ' . ((preg_match('/%/', $this->params['width'])) ? intval($this->params['width']) . '%' : intval($this->params['width']) . 'px') . ' }');
        } else {
            $this->addCss('#' . $this->params['id'] . ' { min-width: 200px; }');
        }

        $spinHeight = isset($this->params['spin_options']['spinHeight']) ? $this->params['spin_options']['spinHeight'] : '';
        if( $spinHeight  !== '' && intval($spinHeight) !== 0){
            $this->addCss('#' . $this->params['id'] . " { height: {$spinHeight}px; }");
        }

        $this->fixCaptionPosition($this->params['id']);

        $smv_options = $this->get_smv_options();
        $captions = 'data-mv-captions=\'' . $this->getCaptions().'\'';
        $thumbsOrientation = $this->params['zgallery_data_options']['thumbnails'];
        $align = $this->getAlign();
        $captions_html = ($thumbsOrientation == 'bottom' && count($this->items) > 1) ? '' : '<div class="sirv-align-wrapper '. $align .'"><div class="sirv-mv-caption '. $this->params['id']. '"></div></div>';

        $html = '<div class="sirv-align-wrapper ' . $align .'">' . PHP_EOL . '<div id="' . $this->params['id'] . '" '.$captions.' class="Sirv"'. $smv_options['viewer']  .'>' . PHP_EOL;

        foreach ($this->items as $item_id => $item) {
            $html .= $this->get_item_html($item, $smv_options, $item_id);
        }
        $html .= '</div>'. PHP_EOL . '</div>';

        return $html . $captions_html . $this->get_global_smv_options() .  '<style type="text/css">' . $this->getInlineCss() . '</style>';
    }

    protected function get_item_html($item, $options, $item_id){
        $html = '';
        $caption = htmlspecialchars($this->remove_tags($item['caption'], null));
        $url = $this->fixUrl($item['url']);
        $url = stripslashes($url);
        $dataItemId = 'data-item-id="' . $item_id . '"';

        switch ($item['type']) {
            case 'image':
                if($this->params['apply_zoom']){
                    $html = '<div ' . $dataItemId . ' data-type="zoom" data-src="' . $item['url'] . '"' . $options['zoom'] . ' data-alt="' . $caption . '"></div>' . PHP_EOL;
                }else{
                    $html = '<img ' . $dataItemId . ' data-src="' . $item['url'] . '">' . PHP_EOL;
                }
                break;
            case 'video':
                $html = '<div ' . $dataItemId . ' data-src="' . $item['url'] . '"' . $options['video'] . ' data-alt="' . $caption . '"></div>' . PHP_EOL;
                break;
            case 'spin':
                $html = '<div ' . $dataItemId . ' data-src="' . $item['url'] . '"' . $options['spin'] . ' data-alt="' . $caption . '"></div>' . PHP_EOL;
                break;
            case 'model':
                $html = '<div ' . $dataItemId . ' data-src="' . $item['url'] . '"' . $options['model'] . ' data-alt="' . $caption . '"></div>' . PHP_EOL;
                break;
        }

        return $html;
    }
}

?>
