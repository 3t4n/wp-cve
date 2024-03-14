<?php 
use Adminz\Helper\ADMINZ_Helper_Flatsome_Shortcodes;
if(!class_exists('WpdiscuzCore')) return;
$_________ = new \Adminz\Helper\ADMINZ_Helper_Flatsome_Shortcodes;
$_________->shortcode_name = 'adminz_wpdiscuz_comments';
$_________->shortcode_title = 'Wp discuz';
$_________->shortcode_icon = 'text';	
// $_________->shortcode_template = '<div class="{{ shortcode.options.visibility }} {{ shortcode.options.class }}" style="min-height: 16px;" ng-bind-html="shortcode.content | html"></div>';


$options = [ ];
$_________->options = $options;

$_________->shortcode_callback = function($atts, $content = null){
    $html = "";
    if (file_exists(ABSPATH . "wp-content/plugins/wpdiscuz/themes/default/comment-form.php")) {
        ob_start();
        include ABSPATH . "wp-content/plugins/wpdiscuz/themes/default/comment-form.php";
        $html = ob_get_clean();
    }
    return $html;
};



$_________->general_element();

