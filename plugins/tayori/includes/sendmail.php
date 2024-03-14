<?php
session_start();
$pos = strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']);
if ($pos === false) {
    exit;
}

if(empty($_SESSION['tayori_token']) || ($_SESSION['tayori_token'] != $_POST['token'])){
    exit;
}

if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
    exit;
}

$url = '../json/button.json';
$json = file_get_contents($url);
$arr = json_decode($json);
$tayori_to = $arr -> mail;
  
  require_once('./language.php');
  if($tayori_result==0){
    mb_language("ja");
  }
  mb_internal_encoding("UTF-8");
  $nametext = filter_var($_POST["name"], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  $maintext = filter_var($_POST["text"], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  $tayori_subject = ($tayori_result==0)?$tayori_text[0][17]:$tayori_text[1][17];
  $title_name = ($tayori_result==0)?$tayori_text[0][18]:$tayori_text[1][18];
  $title_mail = ($tayori_result==0)?$tayori_text[0][19]:$tayori_text[1][19];
  $ttitle_content = ($tayori_result==0)?$tayori_text[0][20]:$tayori_text[1][20];
  $tayori_body = $title_name.$nametext.PHP_EOL.PHP_EOL.$title_mail.$_POST["email"].PHP_EOL.PHP_EOL.$ttitle_content.PHP_EOL.$maintext;
  $tayori_from = $_POST["email"];
  mb_send_mail($tayori_to,$tayori_subject,$tayori_body,"From:".$tayori_from);
?>