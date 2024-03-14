<?php
if(!defined('ABSPATH')){exit;}

$GalleryID = $_POST['option_id'];
$cg_file_name_mail_log = $_POST['cg_file_name_mail_log'];

$uploadFolder = wp_upload_dir();

$file = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/logs/errors/mail-'.$cg_file_name_mail_log.'.log';

if(file_exists($file)){
    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    header("Content-Type: text/plain");
    readfile($file);
    die();
}
