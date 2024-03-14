<?php

abstract class badrSyndication{

	protected $_aOptions = array();
	protected $_aCategory = array();
	protected $_sCategory = null;
	protected $_maxentry = 100;
	protected $_error = 0;
	protected $_baseUrl = null;
	
	final function __construct(){
		$this->_aOptions = get_option( '_syndication' );
		if( !empty($this->_aOptions['except_category']) ) {
			$this->_aCategory = array_diff( get_all_category_ids(), explode(',',$this->_aOptions['except_category']));
		} else {
			$this->_aCategory = get_all_category_ids();
		}
		$this->_sCategory = implode(',',$this->_aCategory);
		$this->_baseUrl = get_bloginfo('url');
		$this->init();
	}
		
	function init() {}

	public function _procDB($method, $arr = null){
		if( !class_exists('naverSyndicationDB') )
			require_once(trailingslashit(dirname(__FILE__)) . 'badr-syndication-db.php');
	
		$oDB = &badrSyndicationDB::getInstance();
	
		return call_user_func( array($oDB, $method),  $arr);
	}
	
	public function _getPostsQuery() {
		$arg = array(
				'post_type' => $this->_aOptions['post_type'],
				'post_status' => 'publish',
				'has_password' => false ,
				'posts_per_page' => $this->_maxentry,
				'category__in' => $this->_aCategory
		);
		return $arg;
	}
}