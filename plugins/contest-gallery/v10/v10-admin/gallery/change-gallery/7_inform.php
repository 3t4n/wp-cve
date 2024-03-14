<?php
if ($informORnot == 1) {

//echo "Post:";

    if (!empty($_POST['cg_activate']) && !empty($_POST['cg_email'])) {

        // if informed cg_email will be not send!
        $emails = $_POST['cg_email'];

        $informIds = $_POST['cg_activate'];

        $isInformedAtLeastOnce = false;

        $querySETrowForInformedIds = 'UPDATE ' . $tablename . ' SET Informed = CASE id ';
        $querySETaddRowForInformedIds = ' ELSE Informed END WHERE id IN (';

        $queryArgsArray = [];

        foreach ($informIds as $key => $value) {

            $key = absint(sanitize_text_field($key));
            $value = absint(sanitize_text_field($value));

            if (!empty($emails[$value])) {

                $To = sanitize_text_field($emails[$value]);

                if (is_email($To)) {

                    $post_title = $_POST['cg_image_name'][$value];

                    if ($urlCheck == 1) {

                        if(!empty($galeryrow->WpPageParent)){
                            $WpPage = $wpdb->get_var($wpdb->prepare("SELECT WpPage FROM $tablename WHERE id = %d",[$key]));
                            $url1 = get_permalink($WpPage);
                        }else{
                            $url1 = $url . "#!gallery/$GalleryID/image/$value/$post_title";
                        }

                        $replacePosUrl = '$url$';
                        $Msg = str_ireplace($replacePosUrl, $url1, $contentMail);

                    }else{
                        $Msg = $contentMail;
                    }

                    $headers = array();
                    $headers[] = "From: $Admin <" . html_entity_decode(strip_tags(@$Reply)) . ">\r\n";
                    $headers[] = "Reply-To: " . @strip_tags(@$Reply) . "\r\n";


                    if (strpos($cc, ';')) {
                        $cc = explode(';', $cc);
                        foreach ($cc as $ccValue) {
                            $ccValue = trim($ccValue);
                            $headers[] = "CC: $ccValue\r\n";
                        }
                    } else {
                        $headers[] = "CC: $cc\r\n";
                    }

                    if (strpos($bcc, ';')) {
                        $bcc = explode(';', $bcc);
                        foreach ($bcc as $bccValue) {
                            $bccValue = trim($bccValue);
                            $headers[] = "BCC: $bccValue\r\n";
                        }
                    } else {
                        $headers[] = "BCC: $bcc\r\n";
                    }


                    $headers[] = "MIME-Version: 1.0";
                    $headers[] = "Content-Type: text/html; charset=utf-8";


                    global $cgMailAction;
                    global $cgMailGalleryId;
                    $cgMailAction = "File activation e-mail backend";
                    $cgMailGalleryId = $GalleryID;
                    add_action('wp_mail_failed', 'cg_on_wp_mail_error', 10, 1);
                    wp_mail($To, $Subject, $Msg, $headers);

                    $isInformedAtLeastOnce = true;

                    $querySETrowForInformedIds .= " WHEN %d THEN 1";
                    $querySETaddRowForInformedIds .= "%d,";
                    $queryArgsArray[] = $key;
                    $queryArgsArray[] = $value;

                }

            }

        }

        if($isInformedAtLeastOnce){

            $querySETaddRowForInformedIds = substr($querySETaddRowForInformedIds,0,-1);
            $querySETaddRowForInformedIds .= ")";

            $querySETrowForInformedIds .= $querySETaddRowForInformedIds;
            $wpdb->query($wpdb->prepare($querySETrowForInformedIds,$queryArgsArray));

        }

    }

}
