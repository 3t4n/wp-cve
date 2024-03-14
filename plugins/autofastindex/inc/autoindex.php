<?php
include_once('bingtest.php');
include_once('logs.php');
// check();

$get = file_get_contents(autoindex_upload . '/settings.json');
$data = json_decode($get);
$site = $data->url;

$bingapi = $data->bingapi;
$file = $data->google_json_file;
$email = $data->email;
$url = $data->url;
$permalink = get_permalink($id);

  try{
    $result = complete($url, $data, $email, $permalink);
  }catch(\Error $e){
    addLog($e,'autoIndex');
  }
   
   


?>