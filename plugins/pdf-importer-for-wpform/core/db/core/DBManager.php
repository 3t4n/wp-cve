<?php


namespace rnpdfimporter\core\db\core;


use Exception;

class DBManager
{
    public function GetPrefix(){
        global $wpdb;
        return $wpdb->prefix;
    }


    public function EscSQLName($sqlName){
        return \esc_sql($sqlName);
    }

    public function GetResults($query,...$args)
    {
        return \call_user_func_array(array($this,'GetResultsWithOffset'),\array_merge([$query,null,null],$args));
    }

    public function GetResultsWithOffset($query,$limit=-1,$offset=-1,...$args)
    {
        global $wpdb;
        if(count($args)>0)
            $query=$wpdb->prepare($query,$args);

        if($limit>0)
        {
            $query.=' limit ';
            if($offset>0)
                $query.=\intval($offset). ', ';
            $query.=\floatval($limit).' ';
        }

        $result= $wpdb->get_results($query);

        if($result===false)
            return array();

        return $result;
    }

    public function Insert($tableName,$data)
    {
        global $wpdb;

        $result=$wpdb->insert($tableName,$data);

        if($result==false)
            throw new Exception($wpdb->last_error);

        return $wpdb->insert_id;
    }

    public function Update($table, $data,$where)
    {
        global $wpdb;

        $result=$wpdb->update($table,$data,$where);

        if($result===false)
            throw new Exception($wpdb->last_error);

    }

    public function GetVar($query,...$args)
    {
        $result=\call_user_func_array(array($this,'GetResults'),\array_merge([$query],$args));

        if(count($result)==0)
            return null;

        return current((array)$result[0]);


    }

    public function GetResult($query,...$args)
    {
        $result=\call_user_func_array(array($this,'GetResults'),\array_merge([$query],$args));

        if(count($result)==0)
            return null;

        return $result[0];

    }

    public function Delete($table, $where)
    {
        global $wpdb;
        $result=$wpdb->delete($table,$where);

        if($result===false)
            throw new Exception($wpdb->last_error);


    }

    public function EscapeLike($value)
    {
        global $wpdb;
        return $wpdb->esc_like($value);
    }

    public function Escape($FormId)
    {
        global $wpdb;
        return $wpdb->prepare('%s',$FormId);
    }

    public function Execute($query)
    {
        global $wpdb;
        return $wpdb->query($query);
    }


}