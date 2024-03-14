<?php 
/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

class GJMAA_Controller_Support extends GJMAA_Controller_Dashboard {
	
	protected $template = 'support.phtml';
	
	protected $parent = 'gjmaa_dashboard';
	
	public function getName(){
		return 'Support';
	}
	
	public function getMenuName(){
		return 'Support';
	}
	
	public function getSlug(){
		return 'gjmaa_support';
	}
	
	public function restart(){
	    GJMAA::restartSystem();
	    
	    $this->redirect($this->getIndexUrl());
	}

	public function check_and_fix_db(){
	    GJMAA::checkAndFixDatabaseCompatibility();

	    $this->redirect($this->getIndexUrl());
    }
}