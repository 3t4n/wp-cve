<?php

if(!function_exists('contest_gal1ery_htmlentities_and_preg_replace')){
    function contest_gal1ery_htmlentities_and_preg_replace ($content){
        if(!empty($content)){
            $content = trim($content);
        }else{
            $content = '';
        }

        $content = str_replace("&zwj;", "", $content);// might be inserted by html parser
        $content = htmlentities($content, ENT_QUOTES);
        $content = str_replace("&zwj;", "", $content);// might be inserted by html parser

        //$content = nl2br($content);

        //Ganz wichtig, ansonsten werden bei vielen Servern immer / (Backslashes bei Anf�hrungszeichen und aneren speziellen Sonderzeichen) hinzugef�gt
        $content = preg_replace('/\\\\/', '', $content);

        return sanitize_textarea_field($content);

    }
}

if(!function_exists('contest_gal1ery_htmlentities_and_preg_replace_textarea')){
    function contest_gal1ery_htmlentities_and_preg_replace_textarea ($content){
        if(!empty($content)){
            $content = trim($content);
        }else{
            $content = '';
        }

        $content = str_replace("&zwj;", "", $content);// might be inserted by html parser
        $content = htmlentities($content, ENT_QUOTES);
        $content = str_replace("&zwj;", "", $content);// might be inserted by html parser

        //$content = nl2br($content);

        //Ganz wichtig, ansonsten werden bei vielen Servern immer / (Backslashes bei Anf�hrungszeichen und aneren speziellen Sonderzeichen) hinzugef�gt
        $content = preg_replace('/\\\\/', '', $content);

        return sanitize_textarea_field($content);

    }
}
if(!function_exists('contest_gal1ery_no_convert')){
    function contest_gal1ery_no_convert ($content){
        if(!empty($content)){
            $content = trim($content);
        }else{
            $content = '';
        }
        return $content;
    }
}
if(!function_exists('cg_stripslashes_recursively')){
    function cg_stripslashes_recursively ($content){
        $content=implode("",explode("\\",$content));
        return stripslashes(trim($content));
    }
}
if(!function_exists('contest_gal1ery_convert_for_html_output')){
    function contest_gal1ery_convert_for_html_output ($content){
        if(!empty($content)){
            $content = trim($content);
        }else{
            $content = '';
        }
        $content = nl2br(html_entity_decode(cg_stripslashes_recursively($content)));

        return $content;
    }
}

if(!function_exists('contest_gal1ery_convert_for_html_output_without_nl2br')){
    function contest_gal1ery_convert_for_html_output_without_nl2br ($content){
        if(!empty($content)){
            $content = trim($content);
        }else{
            $content = '';
        }

        $content = str_replace("&zwj;", "", $content);// might be inserted by html parser
        $content = html_entity_decode(cg_stripslashes_recursively($content));

        return $content;
    }
}

if(!function_exists('contest_gal1ery_return_bytes')){
    function contest_gal1ery_return_bytes($val) {
        $last = strtolower(substr($val,strlen($val)-1,1));
        $val = intval(trim($val));
        switch($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;break;
            case 'm':
                $val *= 1024;break;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }
}

if(!function_exists('contest_gal1ery_return_mega_byte')){
    function contest_gal1ery_return_mega_byte($val) {
        $last = strtolower(substr($val,strlen($val)-1,1));
        $val = intval(trim($val));
        switch($last) {
            // The 'G' for Gigabyte
            case 'g':
                $val *= 1000;break;
        }

        return $val;
    }
}