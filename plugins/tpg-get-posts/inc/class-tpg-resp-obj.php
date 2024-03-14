<?php
/*
 * Name: tpg_resp_obj
 *
 * Description: This is a std class for returning responses 
 * Version: 0.1.0
 *
 */
class tpg_resp_obj {
	
	/**
	 * boolean sucess
	 * 
	 * set to true, any error sets to false
	 *
	 * @since 0.1.0
	 * @var boolean
	 * @access public
	 */
	public	$success=true;
	
	/**
	 * data associative array
	 *
	 * @since 0.1.0
	 * @var string	key
	 * @var string	value
	 * @access public
	 */
	public $data=array();
	
	/**
	 * message array
	 *
	 * @since 0.1.0
	 * 
	 * @access public
	 */
	public $msgs=array();
	
	/**
	 * Error msg associative array
	 *
	 * @since 0.1.0
	 * @var		string	errcode
	 * @var		strig	errmsg
	 * @access public
	 */
	public $err_msgs=array();
	
	/**
	 * Error msg text associative array
	 * 
	 * This array provides more explanation for an error message
	 *
	 * @since 0.1.0
	 * @var		string	errcode
	 * @var		strig	errmsg explanation
	 * @access public
	 */
	public $err_txt=array();

	
	/**
	 * Constrtor for lic validation
	 *
	 * @param	array	$gp_opts	options array
 	 * @param	array	$gp_paths	paths array
 	 * @param	array	$module		module data array
	 */
	
	function __construct() {
		return;
	}
	
	/**
     * set variables in class
     * 
	 * desc
	 *
     * @param 	string	variable name
	 * @param   string  value 
	 * @return	bool 
     */
	public function set_var($_var,$_val) {
		// var must exist; dynamic var not allowed
		if (array_key_exists($_var, get_class_vars(__CLASS__))) {
			$this->$_var = $_val;
			return true;
		} else {
			return false;
		}
	}
    
    /**
     * get variables from class
     * 
	 * desc
	 *
     * @param 	string	variable name 
	 * @return	value
     */
	public function get_var($_var) {
		// var must exist; dynamic var not allowed
		if (array_key_exists($_var, get_class_vars(__CLASS__))) {
			return $_var;
		} 
    }
    
	/**
     * reset resp 
     * 
	 * reset the values of the object
	 *
     *  
	 * @return	null
     */
	public function reset() {
		$this->success=true;
		$this->data=array();
		$this->msgs=array();
		$this->err_msgs=array();
    }

    
    /**
	 * success.
	 *
	 * set the success flag true
	 *
	 * @since 0.1.0
	 *
	 * @return bool True, if resp success. False, if not success.
	 */
	function success() {
		$this->success=true;
	}
	
    /**
	 * error.
	 *
	 * set the success flag false
	 *
	 * @since 0.1.0
	 *
	 * @return bool True, if resp success. False, if not success.
	 */
	function error() {
		$this->success=false;
	}


    /**
	 * Add message.
	 *
	 * add message to message array
	 *
	 * @since 0.1.0
	 *
	 * @return bool True, if resp success. False, if not success.
	 */
	function add_msg($_msg) {
		$this->msgs[]=$_msg;
	}

    /**
	 * add data
	 *
	 * add data item (key,value) to data array
	 *
	 * @since 0.1.0
	 *
	 */
	function add_data($_key,$_val) {
		$this->data[$_key]=$_val;
	}
	
    /**
	 * Add error messge.
	 *
	 * add an error code and message to the error msg array
	 *
	 * @since 0.1.0
	 *
	 */
	function add_errmsg($_ec,$_em) {
		$this->err_msgs[$_ec]=$_em;
	}

    /**
	 * Add error messge explanation.
	 *
	 * add an error code and explanation to code.
	 *
	 * @since 0.1.0
	 *
	 */
	function add_errtxt($_ec,$_em) {
		$this->err_txt[$_ec]=$_em;
	}
    
    /**
	 * Check if return is sucess.
	 *
	 * check the success flag for true or false.
	 *
	 * @since 0.1.0
	 *
	 * @return bool True, if resp success. False, if not success.
	 */
	function is_success() {
		if ($this->success)  {
			return true;
		} else {
			return false;
		}
	}

    
    /**
	 * Check if return is error.
	 *
	 * check the success flag for true or false.
	 *
	 * @since 0.1.0
	 *
	 * @return bool True, if error. False, if not error.
	 */
	function is_error() {
		if ($this->success) {
			return false;
		} else {
			return true;
		}
	}
}
?>
