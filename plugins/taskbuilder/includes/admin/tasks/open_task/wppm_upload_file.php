<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

global $wpdb;
$isError       = false;
$errorMessege  = '';
$attachment_id = 0;
if(!$_FILES){
    $isError=true;
}
$upload_dir = wp_upload_dir();
$now   = date("Y-m-d H:i:s");
$time  = strtotime($now);
$month = date("m",$time);
$year  = date("Y",$time);
$filename = sanitize_file_name($_FILES['file']['name']);
$path = $upload_dir['basedir'] . '/wppm/'.$year.'/'.$month.'/'.$_FILES['file']['name'];
$filetype_extention = wp_check_filetype_and_ext($path,$_FILES['file']['name']);
if( !$isError ){
  switch ($filetype_extention['ext']){
    case 'exe':
    case 'php':
    case 'js':
        $isError      = true;
        $errorMessege = __('Error: file format not supported!','taskbuilder');
        break;
  }
  if ( preg_match('/php/i', $filetype_extention['ext']) || preg_match('/phtml/i', $filetype_extention['ext']) ){
    $isError=true;
    $errorMessege=__('Error: file format not supported!','taskbuilder');
  }
}

if( !$isError && $_FILES['file']['tmp_name']==''){
  $isError      = true;
  $errorMessege = __('Error: file size exceeded allowed limit!','taskbuilder');
}

if( !$isError ){
  if (!file_exists($upload_dir['basedir'] . '/wppm/'.$year)) {
    mkdir($upload_dir['basedir'] . '/wppm/'.$year, 0755, true);
  }
  if (!file_exists($upload_dir['basedir'] . '/wppm/'.$year.'/'.$month)) {
      mkdir($upload_dir['basedir'] . '/wppm/'.$year.'/'.$month, 0755, true);
  }

  $file_name = sanitize_file_name($_FILES['file']['name']);
  $save_file_name = str_replace(' ','_',$file_name);
  $save_file_name = str_replace(',','_',$file_name);
  $save_file_name = explode('.', $save_file_name);
  
  $img_extensions = array('png','jpeg','jpg','bmp','pdf','gif','PNG','JPEG','JPG','BMP','PDF','GIF');
  //$extension      = $save_file_name[count($save_file_name)-1];
  if(!in_array($filetype_extention['ext'], $img_extensions)){
    $extension = $filetype_extention['ext'].'.txt';
    $is_image = 0;
  } else {
    $is_image = 1;
  }
  unset( $save_file_name[count($save_file_name)-1] );
  $save_file_name = implode('-', $save_file_name);
  $save_file_name = time().'_'.preg_replace('/[^A-Za-z0-9\-]/', '', $save_file_name).'.'.$filetype_extention['ext'];
  $save_directory = $upload_dir['basedir'] . '/wppm/'.$year.'/'.$month.'/'.$save_file_name;
  move_uploaded_file( $_FILES['file']['tmp_name'], $save_directory );
}

$values=array(
  'name'=>$save_file_name,
  'file_name'=> $file_name,
  'file_path'=>$save_directory,
  'is_image'=>$is_image,
  'is_active'=>0,
  'is_uploaded'=>0,
  'date_created'=>$now
);

$wpdb->insert($wpdb->prefix.'wppm_attachments',$values);
$attachment_id= $wpdb->insert_id;
$errorMessege=__('done','taskbuilder');

$isError=($isError)?'yes':'no';

$response = array(
    'error'        => $isError,
    'errorMessege' => $errorMessege,
    'id'           => $attachment_id
);

echo json_encode($response);