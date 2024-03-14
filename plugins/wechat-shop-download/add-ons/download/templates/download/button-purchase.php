<?php
if (!defined('ABSPATH')) exit ();

/*-------------------------------------------------------新的页面-----------------------------------------------------*/
//获取post_id和roles
$data = WShop_Temp_Helper::clear('atts', 'templates');

if(!$data['atts']){return;}
$atts=$data['atts'];

$post_id = isset($atts['post_id']) ? $atts['post_id'] : null;
//判断是否存在
$post_id = isset($atts['post_id']) ? $atts['post_id'] : null;
if (!$post_id) return;


$product = new WShop_Product($post_id);
if (!$product->is_load())return;
//获取免费角色
$roles = isset($atts['roles']) ? explode(',', $atts['roles']) : [];
//新的免费角色获取方式
$postRoles = ['pay' => ['all'], 'free' => []];
if(WShop::instance()->get_available_addon('wshop_add_ons_membership')){
    $currentPost = new WShop_Product_Roles(get_post($product->post_ID));
    if ($currentPost->is_load()) {
        $_postRoles = $currentPost->get_roles();
        $postRoles = json_decode($_postRoles[0], true);
        $roles = $postRoles['free'];
    }
}
//获取downloads
$download = new WShop_Download($post_id);
if (!$download->is_load()) return;
$downloads = $download->downloads ? maybe_unserialize($download->downloads) : null;
if(!$downloads||!is_array($downloads)){
    $downloads = array(
        'type'=>'simple',
        'content'=>$download->downloads
    );
}
$download->downloads = $downloads;


/*----------------------------------------------------输出html--------------------------------------------------------*/
//输出下载链接
if (WShop::instance()->payment->is_validate_get_pay_per_view($post_id, $roles)) {
    $downloadTypes = WShop_Add_On_Download::instance()->get_download_types();
    if (isset($downloadTypes[$downloads['type']])) {
        $call = $downloadTypes[$downloads['type']]['render'];
        $call($download);
    }
    return;
}


//输出下载提示
echo WShop::instance()->WP->requires(WShop_Add_On_Download::instance()->domain_dir, 'download/output_download_notice_html.php',[
    'data'=>$data,
    'product'=>$product,
    'postRoles'=>$postRoles
]);
/*--------------------------------------------------------------------------------------------------------------------*/
return;
