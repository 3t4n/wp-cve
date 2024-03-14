<?php
/**
 * @package Velocity Style
 *
 * @param $btn        Boolean
 * @param $counter    Int - Current velocity item
 */
class Velocity_Style {
   
   public static function renderStyles($btn, $counter){
      
      $return = '';
      
      if($counter === 1){ // only render once
         $return .= '<style>';
         $return .= '.velocity-embed {overflow: hidden !important; display: block; margin: 0 0 20px;} .velocity-target .fluid-width-video-wrapper {padding-top: 56% !important; background: #f7f7f7 url('.VELOCITY_URL.'/core/img/ajax-loader.gif) no-repeat center center;}';
         $return .= '.velocity-embed iframe {max-width: 100%;}';
         $return .= '.velocity-embed img{padding: 0; margin: 0; border: none;}';
         $return .= '.velocity-target{display: block;}';
         $return .='</style>';
      
      
         if($btn){
            $return .= '<style>';
            
            $return .= '.velocity-embed a{position: relative; display: block;}';
            $return .= '.velocity-embed img{display: block; width: 100%;}';
            
            $return .= '.velocity-embed .velocity-arrow {display: block; position: absolute; z-index: 10;  top: 50%; left: 50%; margin-top: -17px; margin-left: -9px; width: 0; height: 0; border-top: 18px solid transparent; border-bottom: 18px solid transparent; border-left: 26px solid transparent;}';
            
            $return .= '.velocity-embed .velocity-play-btn {display: block; position: absolute; z-index: 9;  top: 50%; left: 50%; margin-top: -50px; margin-left: -50px; width: 100px; height: 100px; border-radius: 100%; -webkit-border-radius: 100%; -moz-border-radius: 100%; opacity: 0.5;-webkit-transition: all 0.3s ease; -moz-transition: all 0.3s ease; transition: all 0.3s ease;}';
            
            $return .= '.velocity-embed a:hover .velocity-play-btn, .velocity-embed a:focus .velocity-play-btn{opacity: 0.9; -webkit-transform: scale(1.1); -moz-transform: scale(1.1); transform: scale(1.1);}';
            
            // Media Query
            $return .= '@media screen and (max-width: 640px){';
            	$return .= '.velocity-embed a .velocity-play-btn{margin-top: -40px; margin-left: -40px; width: 80px; height: 80px; }';
					$return .= '.velocity-embed .velocity-arrow {margin-top: -11px; margin-left: -6px; border-top: 12px solid transparent; border-bottom: 12px solid transparent; border-left: 20px solid transparent;}';
            $return .= '}';
            
            // Media Query
            $return .= '@media screen and (max-width: 480px){';
            	$return .= '.velocity-embed a .velocity-play-btn{margin-top: -30px; margin-left: -30px; width: 60px; height: 60px; }';
					$return .= '.velocity-embed .velocity-arrow {margin-top: -11px; margin-left: -6px; border-top: 12px solid transparent; border-bottom: 12px solid transparent; border-left: 20px solid transparent;}';
            $return .= '}';
           
            $return .='</style>';
         }  
      }
      
      return $return;
      
   }

}