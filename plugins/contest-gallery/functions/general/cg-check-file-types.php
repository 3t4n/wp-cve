<?php

add_action('cg_is_alternative_file_type','cg_is_alternative_file_type');
if(!function_exists('cg_is_alternative_file_type')){
    function cg_is_alternative_file_type($ImgType){

        if($ImgType=='pdf' || $ImgType=='zip' || $ImgType=='txt' || $ImgType=='doc' || $ImgType=='docx' || $ImgType=='xls' || $ImgType=='xlsx' ||
            $ImgType=='csv' || $ImgType=='mp3' || $ImgType=='m4a' || $ImgType=='ogg' || $ImgType=='wav' || $ImgType=='mp4' ||
            $ImgType=='mov' || $ImgType=='avi' || $ImgType=='wmv' || $ImgType=='webm' || $ImgType=='ppt' || $ImgType=='pptx' ){
                return true;
        }

        return false;

    }
}

add_action('cg_is_alternative_file_type_file','cg_is_alternative_file_type_file');
if(!function_exists('cg_is_alternative_file_type_file')){
    function cg_is_alternative_file_type_file($ImgType){

        if($ImgType=='pdf' || $ImgType=='zip' || $ImgType=='txt' || $ImgType=='doc' || $ImgType=='docx' || $ImgType=='xls' || $ImgType=='xlsx' ||
            $ImgType=='csv' || $ImgType=='mp3' || $ImgType=='m4a' || $ImgType=='ogg' || $ImgType=='wav' ||
            $ImgType=='ppt' || $ImgType=='pptx' ){
                return true;
        }

        return false;

    }
}

add_action('cg_is_alternative_file_type_video','cg_is_alternative_file_type_video');
if(!function_exists('cg_is_alternative_file_type_video')){
    function cg_is_alternative_file_type_video($ImgType){

        if($ImgType=='mp4' || $ImgType=='mov' || $ImgType=='avi' || $ImgType=='wmv' || $ImgType=='webm'){
            return true;
        }
        return false;

    }
}
add_action('cg_is_alternative_file_type_audio','cg_is_alternative_file_type_audio');
if(!function_exists('cg_is_alternative_file_type_audio')){
    function cg_is_alternative_file_type_audio($ImgType){

        if($ImgType=='mp3' || $ImgType=='wav' || $ImgType=='m4a' || $ImgType=='ogg'){
            return true;
        }

        return false;

    }
}

add_action('cg_is_is_image','cg_is_is_image');
if(!function_exists('cg_is_is_image')){
    function cg_is_is_image($ImgType){
        // !important, do not remove jpeg, is for additional files type!!!
        if($ImgType=='jpeg' || $ImgType=='jpg' || $ImgType=='png' || $ImgType=='gif' || $ImgType=='ico'){
            return true;
        }

        return false;

    }
}



?>