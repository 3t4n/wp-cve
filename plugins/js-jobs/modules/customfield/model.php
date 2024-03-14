<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCustomfieldModel {


    function getUnpublishedFieldsFor($fieldfor,$section = null){
        if(!is_numeric($fieldfor)) return false;
        if($section != null)
            if(!is_numeric($section)) return false;

        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        if ($uid != "" AND $uid != 0){ // is admin Or is logged in
            $published = "published = 0";
        }else{
            $published = "isvisitorpublished = 0";
        }
        if($section != null){
            $published .= ' AND section = '.$section;
        }

        $query = "SELECT field FROM `". jsjobs::$_db->prefix ."js_job_fieldsordering` WHERE fieldfor = ".$fieldfor." AND ".$published;
        $fields = jsjobsdb::get_results($query);
        return $fields;
    }


    function getResumeFieldsOrderingBySection($section) { 
        if(!is_numeric($section))  return false;

        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        $is_visitor = '';
        if ($uid != "" AND $uid != 0){ // is admin Or is logged in
            $published = "published = 1";
        }else{
            $published = "isvisitorpublished = 1";
            $is_visitor = ' , fields.isvisitorpublished AS published ';
        }

        $query = "SELECT fields.* ".$is_visitor." FROM `". jsjobs::$_db->prefix ."js_job_fieldsordering` AS fields
            WHERE ".$published." AND fieldfor = 3 AND section = ".$section;
        $query .= " ORDER BY section,ordering ASC";
        $fieldsOrdering = jsjobsdb::get_results($query);
        return $fieldsOrdering;
    }

    function getResumeFieldsOrderingBySection1($section) { // created and used by muhiaudin for resume view 'formresume'
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        if (empty($section)) {
            return false;
        }
        if ($uid != "" AND $uid != 0) {
            $fieldfor = 3;
        } else {
            $fieldfor = 16;
        }

        if ($fieldfor == 16) { // resume visitor case 
            $fieldfor = 3;
            $query = "SELECT  id,field,fieldtitle,ordering,section,fieldfor,isvisitorpublished AS published,sys,cannotunpublish,required 
                        FROM `" . jsjobs::$_db->prefix . "js_job_fieldsordering` 
                        WHERE isvisitorpublished = 1 AND fieldfor =  " . $fieldfor . " AND section = " . $section
                    . " ORDER BY section,ordering";
        } else {
            $published_field = "published = 1";
            if (is_user_logged_in() == false) {
                $published_field = "isvisitorpublished = 1";
            }
            $query = "SELECT  * FROM `" . jsjobs::$_db->prefix . "js_job_fieldsordering` 
                        WHERE " . $published_field . " AND fieldfor =  " . $fieldfor . " AND section = " . $section
                    . " ORDER BY section,ordering ";
        }
        $fieldsOrdering = jsjobsdb::get_results($query);
        return $fieldsOrdering;
    }
    function getMessagekey(){
        $key = 'customfield';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }



}

?>
