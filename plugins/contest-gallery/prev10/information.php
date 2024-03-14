<?php

extract( shortcode_atts( array(
    'id' => ''
), $atts ) );
$galeryID = absint(trim($atts['id']));

global $wpdb;

$tablenameOptions = $wpdb->prefix . "contest_gal1ery_options";

$optionsSQL = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tablenameOptions WHERE id= %d ",[$galeryID]));

if(empty($optionsSQL)){
    @ob_start();// Wichtig!!! Anonsten kommt es zu Fehler im Backend beim speichern!
    $checkIfGalleryExists = false;
    echo "<p class='cg-error-shortcode cg-error-shortcode-id-not-found'>Contest Gallery: Please check your gallery shortcode. The id does not exists.</p>";

    ?>

    <script>

        alert('Contest Gallery message:\r\nYou have a shortcode of a gallery inserted with id which does not exits. Please remove the shortcode. Otherwise functionality is broken.');

    </script>

    <?php

    return;

}else{

    if(empty($optionsSQL->Version) OR intval($optionsSQL->Version)<10){
        echo "<p class='cg-error-shortcode' style='text-align:center;font-weight:bold;'>You are using a gallery with old engine.<br>Please copy this gallery to use new engine and full Contest Gallery abilities.</p>";
    }else{
        echo "<p class='cg-error-shortcode' style='text-align:center;font-weight:bold;'>$usedShortcode shortcode information:
<br>wp-content/uploads/contest-gallery... folder and files can not be found or read.<br>They might be deleted by someone manually or wrong files/folders permissions are set.
</p>";
    }

}





?>