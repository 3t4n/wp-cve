<?php


if(!empty($_GET['confirmation_code'])){

    global $wpdb;
    $tablename_mails_collected = $wpdb->prefix . "contest_gal1ery_mails_collected";
    $tablenameentries = $wpdb->prefix . "contest_gal1ery_entries";
    $tablename_mail_confirmation = $wpdb->prefix . "contest_gal1ery_mail_confirmation";

    $hash = $_GET['confirmation_code'];
    $checkCgMail = $wpdb->get_row( "SELECT * FROM $tablename_mails_collected WHERE Hash = '$hash'" );

    if(!empty($checkCgMail)){

        $GalleryID = $checkCgMail->GalleryID;
        $ConfirmationText = $wpdb->get_var( "SELECT ConfirmationText FROM $tablename_mail_confirmation WHERE GalleryID = '$GalleryID'" );
        $ConfirmationText = contest_gal1ery_convert_for_html_output($ConfirmationText);

        if($checkCgMail->Confirmed){

            echo "<div id='cg_mail_confirmed' style='padding-bottom:60px;'>";
            echo "<p>";
            echo "$ConfirmationText";
            echo "</p>";
            echo "</div>";


        }
        else{

            $wpdb->update(
                "$tablename_mails_collected",
                array(
                    'Confirmed' => 1
                ),
                array('Hash' => $hash),
                array('%d'),
                array('%s')
            );

            $wpdb->update(
                "$tablenameentries",
                array(
                    'ConfMailId' => $checkCgMail->id
                ),
                array('Short_Text' => $checkCgMail->Mail),
                array('%d'),
                array('%s')
            );

            echo "<div id='cg_mail_confirmed' style='padding-bottom:60px;'>";
            echo "<p>";
            echo "$ConfirmationText";
            echo "</p>";
            echo "</div>";

        }

    }
    else{
        include(__DIR__ ."/../../../check-language.php");

        echo $language_ConfirmationWentWrong;
    }

}




?>