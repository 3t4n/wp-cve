<?php
/**
 * ThemeKit CSS Engine Class
 *
 * All calls to the class should be setup in the main class ThemeKitForWP
 *
 * @version 1.0
 *
 * @package themekit
 * @author Josh Lyford
 **/
class ThemeKitForWP_CSSEngine {
	
	private $_tk; //Instance of the Class that loaded this class  - ThemeKitForWP
	
	function __construct($instance){
		$this->_tk = $instance;     
	}
	
	/**
	*
	* Builds the Styles that have been set in ThemeKit Options
	*
	*
	* @since 1.0.0 
	*
	*/
	public function start($css_only = false){
		$saved = get_option( $this->_tk->get_option_name() );
		$font_list = $this->_tk->get_fonts();
		$styles ='';
		if ( false == $css_only ) {
			$styles = '<!-- THEMEKITFORWP STYLE OPTIONS '.$this->_tk->get_option_name().'-->
			<style>';
		}
		
		foreach ($this->_tk->get_registered_options() as $k => $v) {
			if( isset($v['selector']) ) {
				$styles.= $v['selector'].'{';
				switch( $v['style'] ){
					case 'add-style':
						$styles .= $v['styles'];
					break;
					case 'background-image':
						$styles .= ' background-image: url("'.$saved[ $v[ "id" ] ].'");';
					break;
					case 'background-color':
						$bgcolor = $saved[ $v[ "id" ] ];
						if($bgcolor == '#'){
							$styles .= ' background-color: none;';
						}else{
							$styles .= ' background-color: '. $bgcolor .';';
						}
					break;
					case 'color':
						if($saved[ $v[ "id" ] ] !== '#'){
							$styles .= ' color: '. $saved[ $v[ "id" ] ] .';';
						}
					break;
					case 'border-top':
						$styles .= ' border-top: '. $saved[ $v[ "id" ] ]["color"] .' '. $saved[ $v[ "id" ] ]["style"] .' '. $saved[ $v[ "id" ] ]["width"] .'px;';
					break;
					case 'border-bottom':
						$styles .= ' border-bottom: '. $saved[ $v[ "id" ] ]["color"] .' '. $saved[ $v[ "id" ] ]["style"] .' '. $saved[ $v[ "id" ] ]["width"] .'px;';
					break;
					case 'border-left':
						$styles .= ' border-left: '. $saved[ $v[ "id" ] ]["color"] .' '. $saved[ $v[ "id" ] ]["style"] .' '. $saved[ $v[ "id" ] ]["width"] .'px;';
					break;
					case 'border-right':
						$styles .= ' border-right: '. $saved[ $v[ "id" ] ]["color"] .' '. $saved[ $v[ "id" ] ]["style"] .' '. $saved[ $v[ "id" ] ]["width"] .'px;';
					break;
					case 'font':
						
						if(isset( $saved[ $v[ "id" ] ]["underline"] )){
							$decor =  $saved[ $v[ "id" ] ]["underline"];
							if($decor == 'underline'){
								$styles .= ' text-decoration: underline; ';
							} elseif($decor =='none'){
								$styles .= ' text-decoration: none; ';
							}
						}
						
						if(isset( $saved[ $v[ "id" ] ]["style"])){
							$style = $saved[ $v[ "id" ] ]["style"];
							if($style == "bold"){
								$styles .= ' font-weight: bold; ';
								$styles .= ' font-style: normal; ';							
							} elseif($style == "bold italic"){
								$styles .= ' font-weight: bold; ';
								$styles .= ' font-style: italic; ';							
							} elseif($style == "italic"){
								$styles .= ' font-weight: normal; ';
								$styles .= ' font-style: italic; ';							
							} else {
								$styles .= ' font-weight: normal; ';
								$styles .= ' font-style: normal; ';
							}
						}
							
						if(isset($saved[ $v[ "id" ] ]["size"])){
							$styles .= ' font-size: '. $saved[ $v[ "id" ] ]["size"] .'px; ';
						}
						
						if($saved[ $v[ "id" ] ]["color"] !== '#'){
							$styles .= ' color: '. $saved[ $v[ "id" ] ]["color"] .'; ';
						}
						
						if(isset($saved[$v[ "id" ]]["face"] )){
							if( $font_list[ $saved[$v[ "id" ]]["face"] ]["family"] ){
								$styles .= ' font-family: '. $font_list[ $saved[$v[ "id" ]]["face"] ]["family"] .'; ';
							}
						}
						
					break;
				}
				$filter = apply_filters('themekitforwp_css_engine_'.$this->_tk->get_option_name(), $v, $saved );
				if(is_string( $filter )){
					$styles .= $filter;				
				}
			$styles.= '}
			';
			}
		}
		if ( false == $css_only ) {
			$styles .='</style><!-- END THEMEKITFORWP STYLE OPTIONS -->';
		}
		return $styles;
	}


	/**
	*
	* Creates Google Fonts Link to style sheet
	*
	*
	* @since 1.0.0 
	*
	*/
	public function add_google_font_api(){
		$saved = get_option( $this->_tk->get_option_name() );
		if( ! empty( $saved[ 'google_font_list' ] ) ) {
			$font_list = "". implode( "|" , $saved[ 'google_font_list' ] ) ."";
			?>
		 	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=<?php echo $font_list ?>" rel="stylesheet" type="text/css" />
 			<?php
		}
	}
} //End CSS ENGINE
?>