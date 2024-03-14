<?php
if (!defined('ABSPATH')) exit;

$params = WShop_Temp_Helper::clear('atts', 'templates');
$type=$params['type'];
$content=$params['content'];
//获取后台设置下载链接内容
$html=$content;
switch ($type){
    case 'general':
        $data=json_decode($content,true);
        $html='<div class="xunhu-text-center xunhu-font font-16 text-download">支付成功！点击下载按钮，即可复制提取码</div>
                        <div class="xunhu-text-center xunhu-mt10">'.do_shortcode('[wshop_download_link code='.($data['code']?$data['code']:'xx').' type='.$data['type'].' link='.$data['url'].']'.$data['url'].'[/wshop_download_link]').($data['url1']?do_shortcode('[wshop_download_link code='.($data['code1']?$data['code1']:'xx').' type='.$data['type1'].' link='.$data['url1'].']'.$data['url1'].'[/wshop_download_link]'):'').($data['url2']?do_shortcode('[wshop_download_link code='.($data['code2']?$data['code2']:'xx').' type='.$data['type2'].' link='.$data['url2'].']'.$data['url2'].'[/wshop_download_link]'):'').'</div>';
        break;
}

?>
<div class="xunhu-downbox xunhu-ptb20 xunhu-radius xunhu-bg-color xunhu-pr xunhu-font xunhu-mr-auto xunhu-ml-auto">
    <?php echo do_shortcode($html);?>
</div>
