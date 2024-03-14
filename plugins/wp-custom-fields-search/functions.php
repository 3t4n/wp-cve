<?php

    function wpcfs_strip_hash_keys($data){
        $expressions = array('"\$\$hashKey":"[^"]*"','"unsaved": *(true|false)');

        foreach($expressions as $expression){
            $formats = array("/$expression,/","/,$expression/","/{".$expression."}/");
            $data = preg_replace($formats,'',$data);
        }
        return $data;
    }

    function wpcfs_escape_string($string){
        global $wpdb;
        if(function_exists('mysqli_escape_string') && !is_resource($wpdb->dbh)) return mysqli_escape_string($wpdb->dbh,$string);
        elseif(function_exists('mysql_real_escape_string')) return mysql_real_escape_string($string);
        elseif(function_exists('mysql_escape_string')) return mysql_escape_string($string);
        elseif(function_exists('addslashes')) return addslashes($string);
        else {
            throw Exception("No escape string installed");
        }
    }
