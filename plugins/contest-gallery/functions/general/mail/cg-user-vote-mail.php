<?php

if (!function_exists('contest_gal1ery_user_vote_mail'))   {
    function contest_gal1ery_user_vote_mail($selectSQLemailUserVote,$Msg,$galeryID,$to) {

        $Subject = contest_gal1ery_convert_for_html_output($selectSQLemailUserVote->Subject);
        $Header = $selectSQLemailUserVote->Header;
        $Reply = $selectSQLemailUserVote->Reply;
        $cc = $selectSQLemailUserVote->CC;
        $bcc = $selectSQLemailUserVote->BCC;

        $headers = array();
        $headers[] = "From: $Header <". html_entity_decode(strip_tags($Reply)) . ">\r\n";
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
        $cgMailAction = "E-mail to user when votes were done";
        $cgMailGalleryId = $galeryID;
        add_action( 'wp_mail_failed', 'cg_on_wp_mail_error', 10, 1 );

        wp_mail($to, $Subject, $Msg, $headers);

    }
}
