<?php
if(!defined('ABSPATH')){exit;}

if (!function_exists('contest_gal1ery_mail_image_activation'))   {
    function contest_gal1ery_mail_image_activation($selectSQLemail,$userMail,$nextId,$galeryID,$post_title, $galeryIDuser = 0, $options = null){

        global $wpdb;
        $tablename = $wpdb->prefix . "contest_gal1ery";

        $Subject = contest_gal1ery_convert_for_html_output($selectSQLemail->Header);
        $Admin = $selectSQLemail->Admin;
        $Reply = $selectSQLemail->Reply;
        $cc = $selectSQLemail->CC;
        $bcc = $selectSQLemail->BCC;

        $url = trim(sanitize_text_field($selectSQLemail->URL));

        $contentMail = contest_gal1ery_convert_for_html_output($selectSQLemail->Content);
        $Msg = $contentMail;

        $posUrl = "\$url\$";

        $userMail = sanitize_text_field($userMail);

        if(stripos($contentMail,$posUrl)!==false){
            if(!empty($options->WpPageParent)){
                $rowObject = $wpdb->get_row("SELECT * FROM $tablename WHERE id = $nextId");
                if(strpos($galeryIDuser,'-u')!==false && strpos($galeryIDuser,'-uf')===false){
                    $url1 = get_permalink($rowObject->WpPageUser);
                }else if(strpos($galeryIDuser,'-nv')!==false){
                    $url1 = get_permalink($rowObject->WpPageNoVoting);
                }else if(strpos($galeryIDuser,'-w')!==false){
                    $url1 = get_permalink($rowObject->WpPageWinner);
                }else{
                    $url1 = get_permalink($rowObject->WpPage);
                }
            }else{
                if(empty($galeryIDuser) OR strpos($galeryIDuser,'-uf')!==false OR strpos($galeryIDuser,'-cf')!==false){
                    $galeryIDuser = $galeryID;// because might have be send from cg_gallery_user or cg_gallery_no_voting shortcode
                }
                if(empty($post_title)){// then must be from contact form
                    $post_title = 'entry';
                }
                $url1 = $url."#!gallery/$galeryIDuser/file/$nextId/$post_title";
            }
            $replacePosUrl = '$url$';
            $Msg = str_ireplace($replacePosUrl, $url1, $contentMail);
        }

        $headers = array();
        $headers[] = "From: $Admin <". html_entity_decode(strip_tags($Reply)) . ">\r\n";
        $headers[] = "Reply-To: ". strip_tags($Reply) . "\r\n";

        if(strpos($cc,';')){
            $cc = explode(';',$cc);
            foreach($cc as $ccValue){
                $ccValue = trim($ccValue);
                $headers[] = "CC: $ccValue\r\n";
            }
        }
        else{
            $headers[] = "CC: $cc\r\n";
        }

        if(strpos($bcc,';')){
            $bcc = explode(';',$bcc);
            foreach($bcc as $bccValue){
                $bccValue = trim($bccValue);
                $headers[] = "BCC: $bccValue\r\n";
            }
        }
        else{
            $headers[] = "BCC: $bcc\r\n";
        }

        $headers[] = "MIME-Version: 1.0\r\n";
        $headers[] = "Content-Type: text/html; charset=utf-8\r\n";

        global $cgMailAction;
        global $cgMailGalleryId;
        $cgMailAction = "File activation e-mail frontend";
        $cgMailGalleryId = $galeryID;
        add_action( 'wp_mail_failed', 'cg_on_wp_mail_error', 10, 1 );

        wp_mail($userMail, $Subject, $Msg, $headers);

    }
}


?>