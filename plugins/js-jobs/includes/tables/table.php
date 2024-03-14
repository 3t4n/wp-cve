<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBStable {

    public $isnew = false;
    public $columns = array();
    public $primarykey = '';
    public $tablename = '';

    function __construct($tbl, $pk) {
        $this->tablename = jsjobs::$_db->prefix . 'js_job_' . $tbl;
        $this->primarykey = $pk;
    }

    public function bind($data) {
        if ((!is_array($data)) || (empty($data)))
            return false;
        if (isset($data['id']) && !empty($data['id'])) { // Edit case
            $this->isnew = false;
        } else { // New case
            $this->isnew = true;
        }
        $result = $this->setColumns($data);
        return $result;
    }

    protected function setColumns($data) {
        if ($this->isnew == true) { // new record insert
            $array = get_object_vars($this);
            unset($array['isnew']);
            unset($array['primarykey']);
            unset($array['tablename']);
            unset($array['columns']);
            foreach ($array AS $k => $v) {
                if (isset($data[$k])) {
                    $this->$k = $data[$k];
                }
                $this->columns[$k] = $this->$k;
            }
        } else { // update record
            if (isset($data[$this->primarykey])) {
                foreach ($data AS $k => $v) {
                    if (isset($this->$k)) {
                        $this->$k = $v;
                        $this->columns[$k] = $v;
                    }
                }
            } else {
                return false; // record cannot be updated b/c of pk not exist
            }
        }
        return true;
    }

    function store() {
        if ($this->isnew == true) { // new record store
            jsjobs::$_db->insert($this->tablename, $this->columns);
            if (jsjobs::$_db->last_error == null) {
                $this->{$this->primarykey} = jsjobs::$_db->insert_id;
                $id = jsjobs::$_db->insert_id;
                //activity log //1 for insert
                JSJOBSincluder::getJSModel('activitylog')->storeActivity(1, $this->tablename, $this->columns, $id);
            } else {
                JSJOBSincluder::getJSModel('systemerror')->addSystemError();
                return false;
            }
        } else { // record updated
            jsjobs::$_db->update($this->tablename, $this->columns, array($this->primarykey => $this->columns[$this->primarykey]));
            JSJOBSincluder::getJSModel('activitylog')->storeActivity(2, $this->tablename, $this->columns);
            if (jsjobs::$_db->last_error != null) {
                JSJOBSincluder::getJSModel('systemerror')->addSystemError();
                return false;
            }
        }
        return true;
    }

    function update($data) {
        $result = $this->bind($data);
        if ($result == false) {
            return false;
        }
        $result = $this->store();
        if ($result == false) {
            return false;
        }
        return true;
    }

    function delete($id) {
        if (!is_numeric($id))
            return false;
        //data for delete
        $data = JSJOBSincluder::getJSModel('activitylog')->getDeleteActionDataToStore($this->tablename, $id);
        jsjobs::$_db->delete($this->tablename, array($this->primarykey => $id));
        if (jsjobs::$_db->last_error == null) {
            JSJOBSincluder::getJSModel('activitylog')->storeActivityLogForActionDelete($data, $id);
            return true;
        } else {
            JSJOBSincluder::getJSModel('systemerror')->addSystemError();
            return false;
        }
    }

    function check() {
        return true;
    }

    function load($id){
        if(!is_numeric($id)) return false;
        $query = "SELECT * FROM `".$this->tablename."` WHERE ".$this->primarykey." = ".$id;
        $result = jsjobs::$_db->get_row($query);
        $array = get_object_vars($this);
        unset($array['isnew']);
        unset($array['primarykey']);
        unset($array['tablename']);
        unset($array['columns']);
        foreach ($array AS $k => $v) {
            if (isset($result->$k)) {
                $this->$k = $result->$k;
            }
            $this->columns[$k] = $this->$k;
        }
        return true;
    }

}

?>