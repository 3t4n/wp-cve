<?php
/**
 * Shortcodes  functions
 *
 * @link       https://timersys.com/geotargeting/
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 * @author     Your Name <email@example.com>
 */
class GeoTarget_Shortcodes {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $GeoTarget    The ID of this plugin.
	 */
	private $GeoTarget;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Plugin functions
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    Plugin functions
	 */
	private $functions;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $GeoTarget       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 * @var      class    instance of GeotFunctions
	 */
	public function __construct( $GeoTarget, $version, $functions ) {

		$this->GeoTarget = $GeoTarget;
		$this->version = $version;
		$this->functions = $functions;
	}



	/**
	  * Shows provided content only if the location
	  * criteria are met.
	  * [geot country="US,CA"]content[/geot]
	  */
	function geot_filter($atts, $content)
	{
		extract( shortcode_atts( array(
			'ip' 				=>$this->functions->getUserIP(),
			'country'			=>'',
			'region'			=>'',
			'exclude_country'	=>'',
			'exclude_region'	=>''
		), $atts ) );
		
				
		if ( $this->functions->target( $country, $region, $exclude_country, $exclude_region ) )
			return do_shortcode( $content );
			
		return '';
	}
	/**
	  * Shows country name
	  * [geot_country_name]
	  */
	function geot_name($atts, $content)
	{
		return geot_country_name();
	}

	/**
	  * Shows country name
	  * [geot_country_code]
	  */
	function geot_code($atts, $content)
	{
		return geot_country_code();
	}
	

}	
