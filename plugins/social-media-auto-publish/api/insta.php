<?php
if (!function_exists("xyz_smap_ig_create_media_container")) {
    function xyz_smap_ig_create_media_container($xyz_media_param_enc)
    {
        $xyz_media_param=json_decode($xyz_media_param_enc);
        $xyz_page_acces_token=$xyz_media_param[0];
        $xyz_ig_id=$xyz_media_param[1];
        $xyz_media_url=$xyz_media_param[2];
        $xyz_caption=$xyz_media_param[3];
        $sslverify=(get_option('xyz_smap_peer_verification')=='1') ? TRUE : FALSE;
        $containers="https://graph.facebook.com/".XYZ_SMAP_IG_API_VERSION."/".$xyz_ig_id."/media";
            $xyz_url_param = array('image_url'=>$xyz_media_url,
                'access_token'=>$xyz_page_acces_token,
                'caption'=>$xyz_caption
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$containers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$xyz_url_param);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $sslverify);
            $server_output = curl_exec($ch);
            curl_close ($ch);
            return $server_output;
    }
}
if (!function_exists("xyz_smap_ig_publish_media")) {
    function xyz_smap_ig_publish_media($xyz_media_param_enc)
    {
    
        $videoCount=0;
    $xyz_media_param=json_decode($xyz_media_param_enc);
    $xyz_page_acces_token=$xyz_media_param[0];
    $xyz_ig_id=$xyz_media_param[1];
    $xyz_ig_container_id=$xyz_media_param[2];
    $containers="https://graph.facebook.com/".XYZ_SMAP_IG_API_VERSION."/".$xyz_ig_id."/media_publish";
    $sslverify=(get_option('xyz_smap_peer_verification')=='1') ? TRUE : FALSE;
    $xyz_url_param = array(
        'creation_id'=>$xyz_ig_container_id,
        'access_token'=>$xyz_page_acces_token);
    do{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$containers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$xyz_url_param);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $sslverify);
        $server_output = curl_exec($ch);
        curl_close ($ch);
        $xyz_ig_publish_result=json_decode($server_output);
        if(isset($xyz_ig_publish_result->error))
            $err=$xyz_ig_publish_result->error;
        if(isset($err->code) && ($err->code)==9007)
        { sleep(5); }
        $videoCount++;
    }while (isset($err->code) && ($err->code)==9007 && $videoCount < 5);
        return $server_output;
    }
}
?>