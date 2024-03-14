<?php
if(intval($galleryDbVersion)>=14){
    $ForwardAfterLoginText = contest_gal1ery_convert_for_html_output($wpdb->get_var("SELECT ForwardAfterLoginText FROM $tablename_pro_options WHERE GeneralID = '1'"));
}else{
    $ForwardAfterLoginText = contest_gal1ery_convert_for_html_output($wpdb->get_var("SELECT ForwardAfterLoginText FROM $tablename_pro_options WHERE GalleryID = '$GalleryID'"));
}

global $isForwardAfterLoginTextAppears;
$isForwardAfterLoginTextAppears = true;

echo "<div id='cg_login' class='mainCGdivUploadForm mainCGdivUploadFormStatic $FeControlsStyleLogin $BorderRadiusLogin'>";
echo "<p>$ForwardAfterLoginText</p>";
echo "</div>";

?>
    <pre>
    <script>

        setTimeout(function (){
            jQuery("html, body").animate({ scrollTop: jQuery('#cg_login').offset().top-60}, 0);
        },100);

        window.history.replaceState({}, document.title, location.protocol + '//' + location.host + location.pathname);

    </script>
</pre>
<?php


echo $PermanentTextWhenLoggedIn;


?>