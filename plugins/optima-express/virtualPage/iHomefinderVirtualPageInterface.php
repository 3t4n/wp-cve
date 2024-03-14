<?php

interface iHomefinderVirtualPageInterface {
	
	/**
	 * @return string
	 */
	public function getPageTemplate();
	
	/**
	 * @return string
	 */
	public function getPermalink();
	
	/**
	 * @return string
	 */
	public function getHead();
	
	/**
	 * @return string
	 */
	public function getTitle();
	
	/**
	 * @return string
	 */
	public function getContent();
	
	/**
	 * @return string
	 */
	public function getBody();
	
	/**
	 * @return void
	 */
	public function addParameter($name, $value);
	
	/**
	 * @return string
	 */
	public function getMetaTags();
	
	/**
	 * @return array<iHomefinderVariable>
	 */
	public function getVariables();
	
	/**
	 * @return array<iHomefinderVariable>
	 */
	public function getAvailableVariables();
	
}