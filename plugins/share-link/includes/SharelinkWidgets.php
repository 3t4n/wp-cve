<?php

class SharelinkWidgets {
    protected $key;

    public function __construct() {
        $this->key = get_option('sharelink-license');
    }

    public function getAll() {
        $api = new SharelinkApi();
        $json = $api->getWidgets();

        if ($json) {
            foreach ($json as $widget) {
                if (isset($widget['uuid'])) {
                    $myfile = @fopen(plugin_dir_path(__FILE__) . "../assets/js/".$widget['uuid'].".js", "w");
                    $txt = $this->generateCustomBlockScript($widget['name'], $widget['uuid']);
                    fwrite($myfile, $txt);
                    fclose($myfile);
                }
            }
        }

        return $json;
    }

    public function generateCustomBlockScript($widgetName, $widgetId) {
        $script = "( function( blocks, element ) {";
        $script .= "var widgetName = '".$widgetName."';var el = element.createElement; var widgetId = '".$widgetId."';var widgetUrl = '".SHARELINK_WIDGET_BASE_URL."/'; var blockStyle = {width: '1px',minWidth: '100%',padding: '20px',};";
        $script .= 'const iconEl = el("svg", { width: 20, height: 20, viewBox: "0 0 112 98" },el("path", { d:"M100,11.39l-14.75,4L90,19.64,76,35.45,60.62,28.84l-21.18,22-12.63-5L2.58,70.77a6.38,6.38,0,0,0,1.25,1.92A8,8,0,0,0,5.46,74L27.88,50.8l12.64,5,21.12-22,15.55,6.66,16-18.1,4.71,4.15Z", d: "M104.59,17l7.6-2.85-8.08-.78,5.79-5.7-7.71,2.57,3-7.56-6,5.48L98.81,0,95.56,7.44,91.94.18,92,8.3,85.73,3.14l3.36,7.39L81.27,8.36l4.83,4.29H11.34A11.38,11.38,0,0,0,0,24.07v42A11.39,11.39,0,0,0,11.34,77.49H21.4l.07,21.15,17-21.15H88.66A11.39,11.39,0,0,0,100,66.06V27.53l2.91,3.66-1.75-7.93,7.2,3.74-4.82-6.53,8.11.49Z" } ));';
        $script .= "blocks.registerBlockType( 'sharelink/widgets-' + widgetId, {";
            $script .= "title: ''+widgetName,icon: iconEl,category: 'sharelink-widgets',edit: function() {return el('iframe',{loading: 'lazy', width: '100%',frameborder: 0,class: 'sharelink',scrolling: 'no',style: blockStyle, src: widgetUrl + widgetId});},";
            $script .= "save: function() {return el('iframe',{loading: 'lazy', width: '100%',frameborder: 0,class: 'sharelink',scrolling: 'no',style: blockStyle,src: widgetUrl + widgetId});},";
        $script .= "} );";
        $script .= "}(window.wp.blocks,window.wp.element));";
        
        return $script;
    }
}
