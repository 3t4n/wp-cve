<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

abstract class WADA_Model_Base
{
    public $_last_error;
    public $_id;
    public $_data;

    public function __construct($id=null){
        $this->_id = $id;
        $this->_data = null;
        $this->loadData();
    }

    abstract public function getAttributes();
    abstract protected function check();
    abstract protected function save();

    public static function getTable(){
        return 'UNKNOWN-WADA-TABLE'; // has to be overridden!
    }

    public function store($data){
        if(!$this->bind($data)){
            return false;
        }
        if(!$this->check()){
            WADA_Log::error('Check failed, last error: '.$this->_last_error.', data to store was: '.print_r($this->_data, true));
            return false;
        }
        return $this->save();
    }

    protected function bind($data){
        $attributes = $this->getAttributes();
        $this->_data = null;
        if($data) {
            $dataForObj = new stdClass();
            foreach ($data as $key => $value) {
                if (in_array($key, $attributes)) {
                    $dataForObj->$key = $value;
                }
            }
            $this->_id = property_exists($dataForObj, 'id') ? $dataForObj->id : 0;
            $this->_data = $dataForObj;
            //WADA_Log::debug('Binding successful for: '.print_r($this->_data, true));
            return true;
        }else{
            WADA_Log::error('Binding failed for '.print_r($data, true));
            return false;
        }
    }

    protected function loadData($onlyReturnNoInternalUpdate = false){
        if($this->_id){
            global $wpdb;
            $query = 'SELECT * FROM '.$this->getTable().' WHERE id = %d';
            $result = $wpdb->get_results($wpdb->prepare($query, $this->_id));
            if(count($result) == 1){
                $result = $result[0];
            }
            if($onlyReturnNoInternalUpdate){
                return $result;
            }
            $this->_data = $result;
            if($this->_data){
                return true;
            }
        }
        return false;
    }

    protected function deleteRowById(){
        if($this->_id){
            global $wpdb;
            $query = 'DELETE FROM '.$this->getTable().' WHERE id = %d';
            $res = $wpdb->query($wpdb->prepare($query, $this->_id));
            WADA_Log::debug('deleteRowById table '.$this->getTable().', id: '.$this->_id.', result: '.$res);
            return $res;
        }
        return false;
    }

}