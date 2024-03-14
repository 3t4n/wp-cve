<?php 

namespace Element_Ready\Base;
use Element_Ready\Base\BaseController;

class PolyLang extends BaseController
{
	public function register() {
      
    if( function_exists('pll_the_languages') ){
        add_shortcode( 'polylang', [ $this, 'polylang_shortcode' ] );
    }
       
	}
	
	function polylang_shortcode() {

      ob_start();
        if(function_exists('pll_the_languages')){
          pll_the_languages(array('show_flags'=>1,'show_names'=>0));
        }
        
      $flags = ob_get_clean();
      print_r($flags);
      return $flags;
  }
   

    
    
	
}