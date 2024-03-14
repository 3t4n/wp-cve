<?php

$plugin_data = get_plugin_data( __DIR__.'/../../../index.php' );
$plugin_version = $plugin_data['Version'];

$keyToSend = '';

$p_cgal1ery_reg_code = get_option("p_cgal1ery_reg_code");
$p_c1_k_g_r_8_real = get_option("p_c1_k_g_r_9");
$p_c1_k_g_r_8 = md5($p_c1_k_g_r_8_real);


$arrayNew = array(
    '824f6b8e4d606614588aa97eb8860b7e',
    'add4012c56f21126ba5a58c9d3cffcd7',
    'bfc5247f508f427b8099d17281ecd0f6',
    'a29de784fb7699c11bf21e901be66f4e',
    'e5a8cb2f536861778aaa2f5064579e29',
    '36d317c7fef770852b4ccf420855b07b'
);

if(in_array($p_c1_k_g_r_8, $arrayNew)){
    $keyToSend = $p_c1_k_g_r_8_real;
}else{
    $keyToSend = $p_cgal1ery_reg_code;
}
/*
$p_cgal1ery_pro_version_main_key_to_show = '';
if(strlen($keyToSend)>3){
    foreach (str_split(substr($keyToSend,0,strlen($keyToSend)-3)) as $value){
        $p_cgal1ery_pro_version_main_key_to_show .= 'x';
    }
    $p_cgal1ery_pro_version_main_key_to_show .= substr($keyToSend,strlen($keyToSend)-3,3);
}*/

$cgLinkTextForProVersionNote = '<a href="https://www.contest-gallery.com/pro-version-area/?key='.$keyToSend.'&current-version='.$plugin_version.'&upgrade-from-normal-version=true" target="_blank">www.contest-gallery.com/pro-version-area</a>';
echo '<div id="cgDownloadProperProVersionInfoGeneralHeadersArea" ><div style="padding:5px 20px 5px 20px;position:relative;">
<div id="cgDownloadProperProVersionInfoGeneralHeadersAreaBox" >
In order to continue to use PRO version functions<br> you require to change your PRO version.<br> Please do it here:<br>'.$cgLinkTextForProVersionNote.'<br><span style="font-weight:normal;">(It will take you two minutes)</span>
</div>
</div></div>';

?>


