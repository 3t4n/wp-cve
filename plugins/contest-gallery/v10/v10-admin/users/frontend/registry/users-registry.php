<noscript>
    <div style="border: 1px solid purple; padding: 10px">
        <span style="color:red">Enable JavaScript to use the form</span>
    </div>
</noscript>
<?php
if (!defined('ABSPATH')) {
    exit;
}
if(empty($atts['id'])){
    "<p>Please provide a gallery id to the Contest Gallery shortcode</p>";
    return;
}

if(!empty($atts)){
    extract(shortcode_atts(array(
        'id' => ''
    ), $atts));
    $GalleryID = trim($atts['id']);
}
$GalleryID = absint($GalleryID);

$is_frontend = true;
include(__DIR__ . "/../../../../../check-language.php");

global $wpdb;
$tablename_options = $wpdb->prefix . "contest_gal1ery_options";
$tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";
$tablename_options_visual = $wpdb->prefix . "contest_gal1ery_options_visual";
$tablename_registry_and_login_options = $wpdb->prefix . "contest_gal1ery_registry_and_login_options";

$cg_current_page_id = get_the_ID();
$currentPageUrl = get_permalink($cg_current_page_id);

$optionsNormal = $wpdb->get_row("SELECT RegistryUserRole, Version FROM $tablename_options WHERE id='$GalleryID'");
$RegistryUserRole = $optionsNormal->RegistryUserRole;
$galleryDbVersion = $optionsNormal->Version;

if(intval($galleryDbVersion)>=14){
    $registry_and_login_options = $wpdb->get_row("SELECT RegistryUserRole, TextBeforeRegFormBeforeLoggedIn FROM $tablename_registry_and_login_options WHERE GeneralID='1'");//get row here to not rewrite RegistryUserRole if not exists
    // then use galleries v14 or higher logic
    if(!empty($registry_and_login_options)){
        $RegistryUserRole = $registry_and_login_options->RegistryUserRole;
        $galleryDbVersion = cg_get_db_version();
    }
}else{
    $registry_and_login_options = $wpdb->get_row("SELECT TextBeforeRegFormBeforeLoggedIn FROM $tablename_registry_and_login_options WHERE GalleryID='$GalleryID'");
}

if(intval($galleryDbVersion)>=14){
    $optionsVisual = $wpdb->get_row( "SELECT * FROM $tablename_options_visual WHERE GeneralID='1'");
}else{
    $optionsVisual = $wpdb->get_row( "SELECT * FROM $tablename_options_visual WHERE GalleryID='$GalleryID'");
}

$FeControlsStyleRegistry = ($optionsVisual->FeControlsStyleRegistry=='white' || empty($optionsVisual->FeControlsStyleRegistry)) ?  'cg_fe_controls_style_white' : 'cg_fe_controls_style_black';
$BorderRadiusRegistry = ($optionsVisual->BorderRadiusRegistry=='1' || empty($optionsVisual->FeControlsStyleRegistry)) ? 'cg_border_radius_controls_and_containers' : '';

if(intval($galleryDbVersion)>=14){
    $pro_options = $wpdb->get_row("SELECT * FROM $tablename_pro_options WHERE GeneralID='1'");
}else{
    $pro_options = $wpdb->get_row("SELECT * FROM $tablename_pro_options WHERE GalleryID='$GalleryID'");

}

$HideRegFormAfterLogin = $pro_options->HideRegFormAfterLogin;

// has definetly to be not empty! Not isset only!
if (!empty($_GET["cgkey"])) {// joins here when email is trying to get confirmed, when forwarding from email
   include('users-registry-check-after-email-confirmation.php');
}else if (!empty($_GET['cg_login_user_after_registration']) OR !empty($_GET['cg_forward_user_after_reg'])) {// in both cases simply ForwardAfterRegText will be shown with login or without

        $GalleryID = sanitize_text_field($_GET['cg_gallery_id_registry']);
        echo "<div id='cg_activation' class='mainCGdivUploadForm mainCGdivUploadFormStatic $FeControlsStyleRegistry $BorderRadiusRegistry'>";
        $ForwardAfterRegText = nl2br(html_entity_decode(stripslashes($pro_options->ForwardAfterRegText)));
        echo $ForwardAfterRegText;
        echo "</div>";
        ?>
        <script>
            setTimeout(function (){
                jQuery("html, body").animate({ scrollTop: jQuery('#cg_activation').offset().top-60}, 0);
            },100);
        </script>
        <?php

} else {// show registration form then!!!!

    ob_start();
    include(__DIR__ . "/../../../../../check-language.php");

    global $wpdb;
    $tablenameCreateUserForm = $wpdb->prefix . "contest_gal1ery_create_user_form";

    if(intval($galleryDbVersion)>=14){
        $selectUserForm = $wpdb->get_results("SELECT * FROM $tablenameCreateUserForm WHERE GeneralID = '1' && Active = '1' ORDER BY Field_Order ASC");
    }else{
        $selectUserForm = $wpdb->get_results("SELECT * FROM $tablenameCreateUserForm WHERE GalleryID = '$GalleryID' && Active = '1' ORDER BY Field_Order ASC");
    }

    if (empty($selectUserForm)) {
        echo "Please check your shortcode. The id does not exists.<br>";
        return false;
    }

    $i = 1;

    $HideRegForm = false;

    if (($HideRegFormAfterLogin == '1' && is_user_logged_in())) {
        $HideRegForm = true;
    }

    if(!is_user_logged_in()){
        echo contest_gal1ery_convert_for_html_output($registry_and_login_options->TextBeforeRegFormBeforeLoggedIn);
    }

    if (!$HideRegForm) {
       include ('users-registry-form.php');
    }

    if ($pro_options->HideRegFormAfterLoginShowTextInstead == 1 && $HideRegFormAfterLogin == 1 && is_user_logged_in()) {
        $HideRegFormAfterLoginTextToShow = contest_gal1ery_convert_for_html_output($pro_options->HideRegFormAfterLoginTextToShow);
        echo "<div id='cg_user_registry_div_hide_after_login'>";
        echo $HideRegFormAfterLoginTextToShow;
        echo "</div>";
    }

    // Wichtig! Ajax Abarbeitung hier!
    echo "<div class='cg_registry_message'>";

    echo "</div>";

    $formOutput = ob_get_clean();

    echo $formOutput;

}
/*if (!$HideRegForm) {
    echo "</div>";
}*/

?>