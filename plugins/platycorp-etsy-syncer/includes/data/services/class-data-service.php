<?php

namespace platy\etsy;

abstract class DataService{
    protected $tbl_name;
    public function __construct($tbl_name) {
        $this->tbl_name = $tbl_name;
    }

    public static function with_id_keys($data, $id = 'id'){
        $ret = [];
        foreach($data as $d){
            $ret[$d[$id]] = $d;
        }
        return $ret;
    }
}