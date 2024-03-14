<?php
/**
 * ThemeKit Fonts Class
 *
 * All calls to the class should be setup in the main class ThemeKitForWP
 *
 * @version 1.0
 *
 * @package themekit
 * @author Josh Lyford
 **/
class ThemeKitForWP_Fonts {
	//Font Storage Class
	
	private $_fonts = array();
	private $_tk; //Instance of the Class that loaded this class  - ThemeKitForWP
	private	$_group_types = array('standard','google');
	
	function __construct($instance){
		$this->_tk = $instance;	
		$this->build_font_list();	
	}
	
	/**
	*
	* Builds Default ThemeKit Font Array
	*
	*
	* @since 1.0.0 
	*
	* @access private
	*/
	private function build_font_list(){
		$fonts = array();
		$fonts['Arial'] = array(
			"name" =>"Arial",
			"family"=> "Arial, sans-serif",
			"type" => "standard",
			"warn"=> 0
		);
	
		$fonts['Courier New'] = array(
			"name" =>"Courier New",
			"family"=> "Courier New, Courier New, monospace",
			"type" => "standard",
			"warn"=> 0
		);
		$fonts['Courier'] = array(
			"name" =>"Courier",
			"family"=> "Courier, MonoSpace",
			"type" => "standard",
			"warn"=> 0
		);
		
		$fonts['Garamond'] = array(
			"name" =>"Garamond",
			"family"=> "Garamond, sans-serif",
			"type" => "standard",
			"warn"=> 0
		);
	
		$fonts['Georgia'] = array(
			"name" =>"Georgia",
			"family"=> "Georgia, serif",
			"type" => "standard",
			"warn"=> 0
		);
		
		$fonts['Helvetica'] = array(
			"name" =>"Helvetica",
			"family"=> '"Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif',
			"type" => "standard",
			"warn"=> 0
		);
		
		$fonts['Tahoma'] = array(
			"name" =>"Tahoma",
			"family"=> "Tahoma, Geneva, Verdana, sans-serif",
			"type" => "standard",
			"warn"=> 0
		);
	
		$fonts['Trebuchet MS'] = array(
			"name" =>"Trebuchet MS",
			"family"=> "Trebuchet MS, Tahoma, sans-serif",
			"type" => "standard",
			"warn"=> 0
		);
		
		$fonts['Verdana'] = array(
			"name" =>"Verdana",
			"family"=> "Verdana, Geneva, sans-serif",
			"type" => "standard",
			"warn"=> 0
		);
		
		/*
		*
		*	Google Fonts
		*
		*/
		$fonts['Cantarell'] =	array(
			"name" => "Cantarell", 
			"family"=> "Cantarell, sans-serif",
			"type" => "google",
			"variant" => ':r,b,i,bi'
		);
		
		$fonts['Cardo'] =	array(
			"name" => "Cardo", 
			"family"=> "Cardo, sans-serif",
			"type" => "google",
			"variant" => ''
		);
			
		$fonts['Cherry Cream Soda'] = 	array(
			"name" => "Cherry Cream Soda", 
			"family"=> "Cherry Cream Soda, sans-serif",
			"type" => "google",
			"variant" => ''
		);
			
		$fonts['Droid Sans'] = array(
			"name" =>"Droid Sans",
			"family"=> "'Droid Sans', sans-serif",
			"type" => "google",
			"warn"=> 0,
			'variant' => ':r,b'
		);
		
		$fonts['Droid Serif'] = array(
			"name" =>"Droid serif",
			"family"=> "'Droid Serif', arial, serif",
			"type" => "google",
			"warn"=> 0,
			'variant' => ':r,b,i,bi'
		);
			
		$fonts['Lobster'] = array(
			"name" =>"Lobster",
			"family"=> "'Lobster', arial, serif",
			"type" => "google",
			"warn"=> 0,
			'variant' => ''
		);
		
		$fonts['Molengo'] = array(
			"name" =>"Molengo",
			"family"=> "'Molengo', arial, serif",
			"type" => "google",
			"warn"=> 0,
			'variant' => ''
		);
		
		$fonts['Nobile'] = array(
			"name" =>"Nobile",
			"family"=> "'Nobile', arial, serif",
			"type" => "google",
			"warn"=> 0,
			'variant' => ':r,b,i,bi'
		);
		
		$fonts['Reenie Beanie'] = array(
			"name" =>"Reenie Beanie",
			"family"=> "'Reenie Beanie', arial, serif",
			"type" => "google",
			"warn"=> 0,
			'variant' => ''
		);
		
		$fonts['Yanone Kaffeesatz'] = array(
			"name" =>"Yanone Kaffeesatz",
			"family"=> "'Yanone Kaffeesatz', arial, serif",
			"type" => "google",
			"warn"=> 0,
			'variant' => ':r,b'
		);
		
		
		// Available Google webfont names
		/*	
		$google_fonts = array(	
			array('name' => "Cantarell", 'variant' => ':r,b,i,bi'),
			array('name' => "Cardo", 'variant' => ''),
			array('name' => "Crimson Text", 'variant' => ''),
			array('name' => "Droid Sans", 'variant' => ':r,b'),
			array('name' => "Droid Sans Mono", 'variant' => ''),
			array('name' => "Droid Serif", 'variant' => ':r,b,i,bi'),
			array('name' => "IM Fell DW Pica", 'variant' => ':r,i'),
			array('name' => "Inconsolata", 'variant' => ''),
			array('name' => "Josefin Sans Std Light", 'variant' => ''),
			array('name' => "Lobster", 'variant' => ''),
			array('name' => "Molengo", 'variant' => ''),
			array('name' => "Nobile", 'variant' => ':r,b,i,bi'),
			array('name' => "OFL Sorts Mill Goudy TT", 'variant' => ':r,i'),
			array('name' => "Old Standard TT", 'variant' => ':r,b,i'),
			array('name' => "Reenie Beanie", 'variant' => ''),
			array('name' => "Tangerine", 'variant' => ':r,b'),
			array('name' => "Vollkorn", 'variant' => ':r,b'),
			array('name' => "Yanone Kaffeesatz", 'variant' => ':r,b'),
			array('name' => "Cuprum", 'variant' => ':'),
			array('name' => "Neucha", 'variant' => ':'),
			array('name' => "Neuton", 'variant' => ':'),
			array('name' => "PT Sans", 'variant' => ':r,b,i,bi'),
			array('name' => "Philosopher", 'variant' => ':')
		);
		*/
		$this->_fonts = $fonts;	
	}
	
	/**
	*
	* Add Font to ThemeKit Font List
	*
	* Currently only supports text input
	*
	* @since 1.0.0 
	*
	* @param array $font option currently being created
	*
	* 	$fonts['Yanone Kaffeesatz'] = array(
	*		"name" =>"Yanone Kaffeesatz",
	*		"family"=> "'Yanone Kaffeesatz', arial, serif",
	*		"type" => "google",
	*		"warn"=> 0,
	*		'variant' => ':r,b'
	*	);
	*/
	public function add_font( $font ){
		$this->_fonts[$font['name']] = $font;
	}
	
	public function remove_font(){
	
	}
	
	/**
	*
	* Remove All fonts from list so New fonts can be added.
	*
	*
	* @since 1.0.0 
	*
	*/
	public function remove_all_fonts(){
		$this->_fonts = array();
	}
	
	/**
	*
	* Return Current Registered Font Array
	*
	* Currently only supports text input
	*
	* @since 1.0.0 
	*
	* @return array - font list
	*/
	public function get_fonts(){
		return $this->_fonts;
	}
	
	/**
	*
	* Add A new font Section to Drop Down List
	*
	*
	* @since 1.0.0 
	*
	* @param string $type name of new group ex: Google
	*/
	public function add_group_type( $type ){
		array_push($this->_group_types, $type);
	}
	
	/**
	*
	* Get Current Font Groups
	*
	*
	* @since 1.0.0 
	*
	* @return array - ThemeKit Fonts groups
	*/
	public function get_group_types( ){
		return $this->_group_types;
	}
	
}