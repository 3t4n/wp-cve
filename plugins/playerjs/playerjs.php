<?php
/**
 * Plugin Name:       PlayerJS
 * Plugin URI:        https://playerjs.com/docs/q=wordpress
 * Description:       Embed your created player in PlayerJS Builder and play HTML5 Video, Audio, HLS, DASH, YouTube, Vimeo
 * Version:           2.23
 * Author:            Playerjs.com
 * Author URI:        https://playerjs.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       playerjs
 */


if (!defined('ABSPATH')) {
    exit;
}

if(is_admin()) {

    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'playerjs_com_action_links' );

    include( dirname( __FILE__ ) . '/admin/playerjs_com_admin.php' );

}else{

    if (!class_exists('PLAYERJS_HTML5')) {

        class PLAYERJS_HTML5 {

            var $plugin_version = '2.23';

            function __construct() {
                define('PLAYERJS_VERSION', $this->plugin_version);
                add_action('wp_enqueue_scripts',array($this, 'plugin_script'));
                $this->plugin_includes();
            }

            function plugin_script() {
                $path = get_option('playerjs_com_script_path');
                $path = $path ? $path['input'] : '';
                if(trim($path)==''){
                    $path = plugins_url('').'/playerjs/playerjs_default.js';
                }
                wp_enqueue_script('playerjs-js', $path);
            }

            function plugin_includes() {
                add_filter('the_content', 'playerjs_content');
                add_shortcode('playerjs', 'playerjs_com_handler');
                add_filter('widget_text', 'do_shortcode');
                add_filter('the_excerpt', 'do_shortcode', 11);
                add_filter('the_content', 'do_shortcode', 11);
                add_filter('the_content', 'playerjs_content2',12);
                add_filter('script_loader_tag', 'playerjs_async', 11, 2);
            }

        }
        $GLOBALS['playerjs_configs'] = new stdClass();
        $GLOBALS['playerjs_player'] = new PLAYERJS_HTML5();

    }

}
 
function playerjs_async( $tag, $handle ) {
    if ( 'playerjs-js' !== $handle ) {
        return $tag;
    }
    return str_replace( ' src', ' async="async" src', $tag );
}

function playerjs_com_action_links ( $links ) {
    $mylinks = array('<a href="' . admin_url( 'options-general.php?page=playerjs_com_admin' ) . '">Settings</a>');    return array_merge($links, $mylinks);
}

function playerjs_content($x){
    $y = strpos($x,'[playerjs');
    if($y!==FALSE){
        $z = explode('[playerjs',$x);
        for ($i = ($y===0?0:1); $i < count($z); $i++) {
            $open=0;
            $x1 = 'playerjs';
            for ($j = 0; $j < strlen($z[$i]); $j++) {

                if ($z[$i][$j] == "]" && $open==0) {
                    break;
                }else{
                    if($z[$i][$j] == "["){
                        $open++;
                    }
                    if($z[$i][$j] == "]"){
                        $open--;
                    }
                    $x1.= $z[$i][$j];
                }
            }
            if($x1!=''){
                $x2 = str_replace('[','&#91;',$x1);
                $x2 = str_replace(']','&#93;',$x2);
                $x2 = str_replace("'",'&#8216;',$x2);
                $x = str_replace("[".$x1."]","[".$x2."]",$x);
            }
        }
    }
    return $x;
}

function playerjs_content2($x){
    $p = $GLOBALS['playerjs_configs'];
    if(isset($p)){
        $x.= '<script>function PlayerjsAsync(){';
        foreach ($p as $k => $v){
            $x.='var '.$k.' = new Playerjs({'.$v.'});';
        }
    }
    return $x.'} if(window["Playerjs"]){PlayerjsAsync();}</script>';
}

function playerjs_com_handler($atts) {
    $x = '';
    $u = wp_get_current_user();

    if(isset($atts['file'])||isset($atts['replace'])){

        $r = rand(10000,20000);

        $style = '';

        foreach ($atts as $k => $v){
            if(gettype($k) == "integer" && isset($k0)){
                $atts[$k0] .=' '.$v;
                unset($atts[$k]);
            }else{
                $k0 = $k;
            }
        }
        $vars = '';
        foreach ($atts as $k => $v){
            $v = str_replace("&#091;","[",$v);
            $v = str_replace("&#093;","]",$v);
            $v = str_replace("&#187;","",$v);
            $v = str_replace("&#8243;","",$v);
            $v = str_replace("&#8221;","",$v);

            if(strpos($v,"[")!==false && strpos($v,"{")!==false){
                $v = str_replace("&#8216;","pjs'qt",$v);
                $v = str_replace("&#8217;","pjs'qt",$v);
                $v = str_replace("&#8242;","pjs'qt",$v);
            }

            if($k=="file"){
                $r = md5($v);
                $base = dirname( __FILE__ ) . '/admin/playerjs_base64.php';
                if(file_exists($base)){
                    include_once($base);
                    $v = pjsBase64Encrypt($v);
                }
            }

            

            $dont = false;
            if($k=="watermark" && $v==1){
                if($u){
                    if($u->data->ID>0){
                        $vars.=',wid:"'.$u->data->user_login.'"';
                        $dont = true;
                    }
                }
            }

            if(!$dont && $k!='align' && $k!='margin' && $k!='width' && $k!='height'){
                $vars .= ','.$k.':"'.$v.'"';
            }
        }

        if(!isset($atts['replace'])){
            $vars.= ',id:"playerjs'.$r.'"';
        }

        $align = playerjs_com_get_option('align',0,'');

        if(isset($atts['align'])){
            $atts['align']=='left'?$style='float:left;':'';
            $align = $atts['align'];
        }

        if(isset($atts['margin'])){
            $style.='margin:'.$atts['margin'].'px;';
        }

        // width

        $width = playerjs_com_get_option('width',0,'');

        isset($atts['width'])?$width = $atts['width']:'';

        if($width!=''){
            $style.='width:'.(strpos($width,'%')>0?$width:(strpos($width,'px')>0?$width:$width.'px')).';';
        }

        // height

        $height = '';

        $customheight = playerjs_com_get_option('customheight',0,'');

        if($customheight=="custom"){
            $height = playerjs_com_get_option('height',0,'280px');
        }

        isset($atts['height'])?$height = $atts['height']:'';

        if($height!=''){
            $style.='height:'.(strpos($height,'%')>0?$height:(strpos($height,'px')>0?$height:$height.'px')).';';
            $vars .= ',aspect:"off"';
        }
    }
    if(substr($vars,0,1)==","){
        $vars = substr($vars,1);
    }
    if(!isset($atts['replace'])){
        $x = ($align=='center'?'<center>':'').'<div id="playerjs'.$r.'" '.($style!=''?'style="'.$style.'"':'').'></div>'.($align=='center'?'</center>':'');
    }else{
        $x = '';
    }
    $GLOBALS['playerjs_configs']->{'player'.$r} = $vars;
    return $x;
}

function playerjs_com_get_option($x,$checkbox,$default){
    $y = get_option('playerjs_com_'.$x);
    //echo($x.' '.gettype($y).' '.$y.'<br>');
    $y = gettype($y)=="array" ? $y[($checkbox==1?'checkbox':'input')] : $default;
    if($checkbox==0){
        if(trim($y)==''||trim($y)=='0'){
            $y = $default;
        }
    }
    return $y;
}
