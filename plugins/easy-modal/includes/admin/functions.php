<?php
if(!function_exists("is_emodal_admin_page")){
function is_emodal_admin_page(){
    if(is_admin() && !empty($_GET['page']) && ( strpos($_GET['page'], 'easy-modal' ) === 0 || in_array($_GET['page'], apply_filters('emodal_admin_pages', array()))))
    {
        return true;
    }
    return false;
}}

if(!function_exists("empost")){
function empost($name, $do_stripslashes = true){
    $value = emresolve($_POST, $name, false);
    return $do_stripslashes ? stripslashes_deep($value) : $value;
}}

if(!function_exists("empost_clean")){
function empost_clean($value, $type = 'text', $default = NULL){
    switch($type)
    {
        case 'hexcolor': return preg_match('/^#[a-f0-9]{6}$/i', $val) ? $value : $default; break;
    }
}}

if(!function_exists("is_all_numeric")){ 
function is_all_numeric($array){
    if(!is_array($array)) return false;
    foreach($array as $val) if(!is_numeric($val)) return false;
    return true;
}}


if(!function_exists("emodal_admin_url")){ 
function emodal_admin_url($page = ''){
    return admin_url( 'admin.php?page=' . emodal_admin_slug($page));
}}

if(!function_exists("emodal_admin_slug")){ 
function emodal_admin_slug($page = ''){
    return EMCORE_SLUG . ($page != '' ? '-'. $page : '');
}}


