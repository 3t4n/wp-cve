<?php

$idToCopy = absint($_POST['cg_copy_id']);

$wp_upload_dir = wp_upload_dir();

$galleryToCopy = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tablenameOptions WHERE id = %d ",[$idToCopy]));

$cgVersion = intval($galleryToCopy->Version);

$galleryDBversion = $galleryToCopy->Version;

// check if sort values files exists
if(!file_exists($wp_upload_dir['basedir'] . "/contest-gallery/gallery-id-".$idToCopy."/json/".$idToCopy."-images-sort-values.json") and $cgVersion>=10){
    cg_actualize_all_images_data_sort_values_file($idToCopy,true,true);
}
// check if sort values files exists --- ENDE

// check if image-info-values-file-exists
if(!file_exists($wp_upload_dir['basedir'] . "/contest-gallery/gallery-id-".$idToCopy."/json/".$idToCopy."-images-info-values.json") and $cgVersion>=10){
    //cg_actualize_all_images_data_info_file($idToCopy);
}
// check if image-info-values-file-exists

//var_dump('cg version');
//var_dump($cgVersion);

if(!empty($_POST['option_id_next_gallery'])){
    $nextIDgallery = absint($_POST['option_id_next_gallery']);
}

$uploadFolder = wp_upload_dir();

$FbLikeGoToGalleryLink = '';

if($_POST['cg_copy_start']==0){

    include('copy-gallery-options-and-translations.php');

}

$copyFullGallery = false;
$copyGalleryPre7 = false;
$setCgCopyType = '';

// V 7 EXTRA
if ($cgVersion < 7) {
    $copyGalleryPre7 = true;
}

if ($cgVersion >= 7) {
    $copyFullGallery = true;
}

if($cgVersion<7 && !empty($_POST['copy_v7'])){
    $copyFullGallery = true;
    $setCgCopyType = 'cg_copy_type_all';
}
// V 7 EXTRA   -- END

$cgCopyType = 'cg_copy_type_options_and_images';

if(!empty($_POST['cg_copy_type'])){
    $cgCopyType = $_POST['cg_copy_type'];
}


if($cgCopyType=='cg_copy_type_options'){
    $copyFullGallery = false;
}

// V 7 EXTRA
if ($cgVersion < 7) {
    $cgCopyType = 'cg_copy_type_options';
}

if($setCgCopyType=='cg_copy_type_all'){
    $cgCopyType = 'cg_copy_type_all';
}

// V 7 EXTRA   -- END

if ($copyFullGallery==true) {
    include('copy-gallery-images.php');
}

// create images-info-values file here, because still missing for new gallery
//cg_actualize_all_images_data_info_file($nextIDgallery);

// unlink files which were only created for copiying
unlink($galleryUpload . '/json/' . $nextIDgallery . '-collect-input-ids-array.json');
unlink($galleryUpload . '/json/' . $nextIDgallery . '-collect-cat-ids-array.json');

// Formular Output für User wird ermittelt
do_action('cg_json_single_view_order',$nextIDgallery);

$fp = fopen($uploadFolder['basedir'] . '/contest-gallery/cg-copying-gallery.txt', 'w');
fwrite($fp, 'cg-copying-gallery');
fclose($fp);

if(empty($_POST['option_id_next_gallery'])){
    $_POST['option_id_next_gallery'] = $nextIDgallery;
}

$galleryCopiedText = '';

if($cgCopyType=='cg_copy_type_options'){
    $galleryCopiedText = 'Only options and forms were copied.';
}
if($cgCopyType=='cg_copy_type_options_and_images'){
    $galleryCopiedText = 'Options, forms and files were copied.';
}

if($cgCopyType=='cg_copy_type_all'){
    $galleryCopiedText = 'Options, forms, files, votes and comments were copied.';
}

echo "<input type='hidden' id='cgNextIdGallery' value='$nextIDgallery' />";

if ($cgVersion < 7 && empty($_POST['copy_v7'])){

    echo "<br>";
    echo "<div style='width:100%;background-color:#fff;margin-bottom:0 !important;margin-bottom:0;padding: 0 10px; border: thin solid black;text-align:center;'>";
    echo "<h2>You created a new gallery based on a gallery which was created before version 7 update. Only settings were copied.<br/>
        If you copy this new created gallery again images will be also copied then.</h2>";
    echo "</div>";
    echo "<br>";

} else {
    echo "<br>";
    echo "<div class='cg-copied-new-gallery' >";
    echo "<h2>You created a new gallery based on a copy.<br>$galleryCopiedText</h2>";
    echo "</div>";
    echo "<br>";
}

?>
<script>

    var cg_in_process = document.getElementsByClassName('cg_in_process');

    while(cg_in_process.length > 0){
        cg_in_process[0].remove();
    }

</script>


