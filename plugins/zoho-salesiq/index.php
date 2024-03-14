<?php


/*
Plugin Name: Zoho SalesIQ
Plugin URI: http://wordpress.org/plugins/zoho-salesiq/
Description: Convert Website Visitors into Customers
Author: Zoho SalesIQ Team
Version: 2.0.2
Author URI: http://zoho.com/salesiq
*/



add_action('admin_menu', 'ld_menu');   


function ld_menu() {
   add_menu_page('Account Configuration', 'Zoho SalesIQ', 'administrator', 'LD_dashboard', 'LD_dashboard',plugins_url().'/zoho-salesiq/favicon.png', '79');
    

  }


function LD_dashboard() {
include ('salesiq.php');
}


function ld_embedchat()
{
    $ldcode_str = trim(get_option('ldcode'));
    $ldwidgetcodeurl = trim(get_option('ldwidgetcodeurl'));
    if(empty($ldcode_str) && empty($ldwidgetcodeurl))
    {
        return;
    }
    $script = '<script type="text/javascript" id="zsiqchat">var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode:"WIDGETCODE", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="SALESIQURL";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);</script>';
    if(!empty($ldwidgetcodeurl) && preg_match("/^(https:\/\/salesiq\.)(zoho\.|unionbankofindia\.|zohopublic\.)(([a-z]{1,3}\.)?[a-z]{1,3})(\/widget\?widgetcode\=)([a-z0-9]{10,200})$/s", $ldwidgetcodeurl))
    {
        $widget_info = explode("?widgetcode=",$ldwidgetcodeurl);
        $script = str_replace("WIDGETCODE",trim($widget_info[1]),$script);
        $script = str_replace("SALESIQURL",trim($widget_info[0]),$script);
        if(!empty($ldcode_str) && preg_match("/^<script[^>]*>\s*.+\s*(float\.ls|.+widgetcode.+\/widget)\s*.+\s*<\/script>$/s", $ldcode_str))
        {
            delete_option('ldcode');
        }
    }
    elseif(!empty($ldcode_str) && preg_match("/^<script[^>]*>\s*.+\s*(float\.ls|.+widgetcode.+\/widget)\s*.+\s*<\/script>$/s", $ldcode_str))
    {
        $ldcode_str = str_replace(" ","",$ldcode_str);
        preg_match_all('~(?<=widgetcode:").+?(?=",)~',$ldcode_str,$matches);
        $widgetcode = $matches[0][0];
        preg_match_all('~(?<=s.src=").+?(?=";)~',$ldcode_str,$matches);
        $url = $matches[0][0];
        if(strpos($url, "/widget?plugin_source"))
        {
            $url = str_replace("/widget?plugin_source=wordpress","/widget",$url);
        }
        $widget_url = trim($url)."?widgetcode=".trim($widgetcode);
        update_option('ldwidgetcodeurl', sanitize_url($widget_url));
        delete_option('ldcode');
        $script = str_replace("WIDGETCODE",trim($widgetcode),$script);
        $script = str_replace("SALESIQURL",trim($url),$script);
    }
    
    if(!strpos($script, "/widget?plugin_source")){
      $script = str_replace("/widget","/widget?plugin_source=wordpress",$script);
    }
    echo $script;

}


add_action("wp_footer","ld_embedchat", 5);

