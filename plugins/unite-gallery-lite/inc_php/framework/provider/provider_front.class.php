<?php

class UniteProviderFrontUG{
	
	private static $t;
	private static $replaceDone = false;
	const OPTION_PROTECTION = "unitegallery_front_protection";
	
	const ACTION_ADD_SCRIPTS = "wp_enqueue_scripts";

	
	/**
	 *
	 * add some WordPress action
	 */
	protected static function addAction($action,$eventFunction){
	
		add_action( $action, array(self::$t, $eventFunction) );
	}
	
	
	
	
	
	/**
	 * get first priority of the filter function
	 */
	public static function searchFilterFirstPriority($tag, $function){
		global $wp_filter;
		
		$arrPriority = (array) array_keys($wp_filter[$tag]);
		asort($arrPriority);
		
		foreach ($arrPriority as $priority ) {
			if ( isset($wp_filter[$tag][$priority][$function]) )
				return $priority;
		}
		
		return(false);
	}
	
	
	/**
	 * check unite gallery output
	 */
	public static function process_shortcode($content){
		
		//clear all other tags
		global $shortcode_tags;
		$current_shortcodes = $shortcode_tags;
		$shortcode_tags = array();
		
		//process unite gallery shortcode
		add_shortcode( 'unitegalleryprocess', 'unitegallery_shortcode' );
		$content = do_shortcode($content);
		
		//return all other tags
		$shortcode_tags = $current_shortcodes;
		
		return($content);
	}	

	/**
	 * print message
	 */
	public static function printErrorMessage($type = "full"){
		
		if($type == "half")
			$message = "Unite Gallery Error: hmm, the gallery still not show :( but, the gallery will now remove the filters protection. should will work on next run. Please refresh the page again.";
		else
			$message = "Unite Gallery Error: some plugin of yours changing the output, something like this: <font color='#0B752E'>apply_filters('the_content', get_the_content())</font> and the gallery is not shown. However, the gallery will try to adopt to this on the next run. Please refresh the page.";
		
		$output = "<div style='border:1px solid red;font-size:18px;color:red;padding:10px;'>{$message}</div>";
		
		echo $output;
	}
	
	
	/**
	 * process shortcode full way (999)
	 */
	public static function process_shortcode_full($content){
		
		//if problem exists, reduce protection level to half
		if(self::$replaceDone == true){
			
			if(strpos($content, "[unitegalleryprocess") === false){
				update_option(self::OPTION_PROTECTION, "half");
				self::printErrorMessage("full");
			}
			
			self::$replaceDone = false;		//check only once
		}
		
		return self::process_shortcode($content);
	}

	
	/**
	 * process shortcode half way (11)
	 */
	public static function process_shortcode_half($content){
		
		if(self::$replaceDone == true){
		
			if(strpos($content, "[unitegalleryprocess") === false){
				update_option(self::OPTION_PROTECTION, "nothing");
				self::printErrorMessage("half");
			}
		
			self::$replaceDone = false;		//check only once
		}
		
		
		return self::process_shortcode($content);
	}
	
	
	
	/**
	 * rename shortcode to another shortcode, don't let filters in between to touch it.
	 * process it in 999 position. don't touch the unitegallery original shortcode
	 */
	public static function rename_shortcode($content){

		$content = str_replace("[unitegallery ", "[unitegalleryprocess ", $content);
		
		if(strpos($content, "[unitegalleryprocess") !== false)
			self::$replaceDone = true;
		
		return($content);
	}

	
	/**
	 * print all the filters
	 */
	public static function printFilters(){
	
		global $wp_filter;
		$filterContent = $wp_filter["the_content"];
	
		$keys = array_keys($filterContent);
	
		dmp($keys); exit();
	}
	
	
	/**
	 * function for testing
	 */
	/*
	public static function makeProblems($content){
		
		$content = "the gallery is not here";
		
		return($content);
	}
	*/
	
	
	/**
	 * on after theme setup - fix the wpautop after do_shortcode (if exists)
	 */
	public static function onAfterThemeSetup(){
		
		//code for testing
		//update_option(self::OPTION_PROTECTION, "full");
		//add_filter("the_content", array(self::$t, "makeProblems"), 11);
		
		$protectionLevel = get_option(self::OPTION_PROTECTION, "full");
		
		switch($protectionLevel){
			case "full":
			default:
				add_filter("the_content", array(self::$t, "rename_shortcode"), 1);
				add_filter("the_content", array(self::$t, "process_shortcode_full"), 9999);
			break;
			case "half":
				add_filter("the_content", array(self::$t, "rename_shortcode"), 1);
				add_filter("the_content", array(self::$t, "process_shortcode_half"), 11);
			break;
			case "nothing":		//don't protect, do_shortcode will do the job
			break;
		}
		
	}
	
	
	/**
	 * print footer scripts
	 */
	public static function onPrintFooterScripts(){
		
		$arrScrips = UniteProviderFunctionsUG::getCustomScripts();
		
		if(empty($arrScrips))
			return(true);
					
		echo "<script type='text/javascript'>\n";
		foreach ($arrScrips as $script){
			echo $script."\n";
		}
		echo "</script>";
		
	}
	
	
	/**
	 * register widgets
	 */
	public static function registerWidgets(){
	    
	    register_widget( 'UniteGallery_Widget' );
	    	    
	}
	
	
	/**
	 *
	 * the constructor
	 */
	public function __construct(){
		self::$t = $this;
		
		$this->addAction("after_setup_theme", "onAfterThemeSetup");
		
		$this->addAction("wp_print_footer_scripts", "onPrintFooterScripts");
		
		$this->addAction("widgets_init", "registerWidgets");
	}
	
}

?>