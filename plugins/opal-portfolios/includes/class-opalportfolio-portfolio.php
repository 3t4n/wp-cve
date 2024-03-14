<?php
/**
 * $Desc$
 *
 * @version    $Id$
 * @package    opalportfolios
 * @author     Opal  Team <opalwordpressl@gmail.com >
 * @copyright  Copyright (C) 2016 wpopal.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @website  http://www.wpopal.com
 * @support  http://www.wpopal.com/support/forum.html
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Opalportfolio_Portfolios {
	/**
	 *
	 */
	protected $post_id; 

	/**
	 * Constructor 
	 */
	public function __construct( $post_id ){
		
		$this->post_id 	= $post_id;   
	}

	/**
	 * Gets status
	 *
	 * @access public
	 * @return array
	 */
	public function getCategoryTax(){
		$terms = wp_get_post_terms( $this->post_id, 'portfolio_cat' );
		return $terms; 
	}

	/**
	 * Gets meta box value
	 *
	 * @access public
	 * @param $key
	 * @param $single
	 * @return string
	 */
	public function getMetaboxValue( $key, $single = true ) {
		return get_post_meta( $this->post_id, OPAL_PORTFOLIO_PREFIX.$key, $single ); 
	}	

	/**
	 * Gets client ids value
	 *
	 * @access public
	 * @return array
	 */
	public function getClient() {
		return $this->getMetaboxValue( 'client', true );
	}

	/**
	 * Gets Awards ids value
	 *
	 * @access public
	 * @return array
	 */
	public function getBudgets() {
		return $this->getMetaboxValue( 'budgets', true );
	}

	/**
	 * Gets completed ids value
	 *
	 * @access public
	 * @return array
	 */
	public function getCompleted() {
		return $this->getMetaboxValue( 'completed', true );
	}

	/**
	 * Gets location ids value
	 *
	 * @access public
	 * @return array
	 */
	public function getLocation() {
		return $this->getMetaboxValue( 'location', true );
	}

	/**
	 * Gets Link ids value
	 *
	 * @access public
	 * @return array
	 */
	public function getLink() {
		return $this->getMetaboxValue( 'link', true );
	}

	/**
	 * Gets Description  ids value
	 *
	 * @access public
	 * @return array
	 */
	public function getDesc () {
		return $this->getMetaboxValue( 'desc ', true );
	}
	
}