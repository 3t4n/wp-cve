<?php
namespace A3Rev\RSlider;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Functions
{
	public static function slider_templates() {
		$templates_list = array(
			'template-1'		=> get_option( 'a3_rslider_template_1' , __( 'Default Skin', 'a3-responsive-slider' ) ),
			'template-2'		=> get_option( 'a3_rslider_template_2' , __( 'Skin 2', 'a3-responsive-slider' ) ),
			'template-card'		=> get_option( 'a3_rslider_template_card' , __( 'Card Skin', 'a3-responsive-slider' ) ),
			'template-widget'	=> get_option( 'a3_rslider_template_widget' , __( 'Widget Skin', 'a3-responsive-slider' ) ),
			'template-mobile'	=> get_option( 'a3_rslider_template_mobile' , __( 'Touch Mobile Skin', 'a3-responsive-slider' ) ),
		);
		
		return $templates_list;
	}
	
	public static function get_slider_template( $slider_template ) {
		$templates_list = self::slider_templates();
		
		if ( isset( $templates_list[ $slider_template ] ) )
			return $templates_list[ $slider_template ];
		else
			return __( 'Your template is not existed', 'a3-responsive-slider' );
	}
	
	public static function slider_transitions_list () {
		$str_effect = ' fade, fadeout, scrollHorz, scrollVert, flipHorz, flipVert, shuffle Left, shuffle Right, tileSlide X, tileSlide Y, tileBlind X, tileBlind Y, kenburns';
		$arr_effect = array(
			'none'			=> __( 'None', 'a3-responsive-slider' ),
			'random'		=> __( 'Random', 'a3-responsive-slider' ),
			'fade'			=> __( 'Fade', 'a3-responsive-slider' ),
			'fadeout'		=> __( 'Fade Out', 'a3-responsive-slider' ),
			'scrollHorz'	=> __( 'Scroll Horizontal', 'a3-responsive-slider' ),
			'scrollVert'	=> __( 'Scroll Vertical', 'a3-responsive-slider' ),
			'flipHorz'		=> __( 'Flip Horizontal', 'a3-responsive-slider' ),
			'flipVert'		=> __( 'Flip Vertical', 'a3-responsive-slider' ),
			'shuffle'		=> __( 'Shuffle', 'a3-responsive-slider' ),
			'tileSlide'		=> __( 'Tile Slide', 'a3-responsive-slider' ),
			'tileBlind'		=> __( 'Tile Blind', 'a3-responsive-slider' ),
		);
		
		return $arr_effect;
	}
	
	public static function yt_slider_transitions_list () {
		$arr_effect = array(
			'none'			=> __( 'None', 'a3-responsive-slider' ),
			'fade'			=> __( 'Fade', 'a3-responsive-slider' ),
			'fadeout'		=> __( 'Fade Out', 'a3-responsive-slider' ),
			'scrollHorz'	=> __( 'Scroll Horizontal', 'a3-responsive-slider' ),
			'scrollVert'	=> __( 'Scroll Vertical', 'a3-responsive-slider' ),
		);
		
		return $arr_effect;
	}
	
	public static function get_slider_transition( $slider_transition_effect = '', $slider_settings = array() ) {
		
			$fx = $slider_transition_effect;
			$transition_attributes = '';
			$timeout = (int) $slider_settings['slider_timeout'] * 1000;
			$delay = (int) $slider_settings['slider_delay'] * 1000;
			$speed = (int) $slider_settings['slider_speed'] * 1000;
			
			if ( $slider_settings['is_auto_start'] == 0 ) {
				$transition_attributes .= 'data-cycle-paused=true' . " \n";
			}
			
			if ( $slider_transition_effect == 'shuffle' ) {
				if ( $slider_settings['data-cycle-shuffle-left'] > 1 ) $transition_attributes .= 'data-cycle-shuffle-left='.$slider_settings['data-cycle-shuffle-left'] . " \n";
				if ( $slider_settings['data-cycle-shuffle-right'] > 1 ) $transition_attributes .= 'data-cycle-shuffle-right='.$slider_settings['data-cycle-shuffle-right']. " \n";
				$transition_attributes .= 'data-cycle-shuffle-top='.$slider_settings['data-cycle-shuffle-top']. " \n";
			} elseif ( $slider_transition_effect == 'tileSlide' || $slider_transition_effect == 'tileBlind'  ) {
				$transition_attributes .= 'data-cycle-tile-count='.$slider_settings['data-cycle-tile-count']. " \n";
				$transition_attributes .= 'data-cycle-tile-delay='.$slider_settings['data-cycle-tile-delay']. " \n";
				$transition_attributes .= 'data-cycle-tile-vertical='.$slider_settings['data-cycle-tile-vertical']. " \n";
			} else {
				$fx = $slider_transition_effect;
			}
			
			$transition_effect = array(
				'fx'					=> $fx,
				'timeout'				=> $timeout,
				'delay'					=> $delay,
				'speed'					=> $speed,
				'transition_attributes'	=> $transition_attributes
			);
		
		return $transition_effect;
	}
	
	public static function get_transition_random( $slider_settings = array() ) {
		$slider_transitions_list = self::slider_transitions_list();
		unset( $slider_transitions_list['none'] );
		unset( $slider_transitions_list['random'] );
		//unset( $slider_transitions_list['kenburns'] );
		
		$transition_random = array_rand( $slider_transitions_list );
		
		$transition_effect = self::get_slider_transition( $transition_random, $slider_settings );
		
		$transition_ouput = ' data-cycle-fx="'.$transition_effect['fx'].'" '.$transition_effect['transition_attributes'].' ';
		
		return $transition_ouput;
		
	}
	
	public static function get_youtube_iframe_ios( $youtube_code = '', $autoplay = false, $exclude_lazyload = '' ) {
		if ( trim( $youtube_code ) == '' ) return '';

		if ( 'true' == $autoplay ) {
			$autoplay = 1;
		} else {
			$autoplay = 0;
		}

		
		$youtube_url = 'https://www.youtube.com/embed/' . trim( $youtube_code ) . '?version=3&hl=en_US&rel=0&enablejsapi=1&controls=1&modestbranding=1&autohide=1&wmode=opaque';
		
		$youtube_iframe = '<div class="video_ojbect_container"><div class="a3-cycle-video-prev"></div><div class="a3-cycle-video-next"></div><iframe class="'. $exclude_lazyload .' video_ojbect" width="640" height="320" src="'. esc_url($youtube_url.'&autoplay='.$autoplay ).'" data-autoplay="'.$autoplay.'" origin_src="'. esc_url( $youtube_url ).'" frameborder="0" allowfullscreen></iframe></div>';
		
		return $youtube_iframe;
	}
	
	public static function printPage( $link, $total = 0,$currentPage = 0,$div = 3,$rows = 5, $li = false ) {
		if(!$total || !$rows || !$div || $total<=$rows) return false;
		$nPage = floor($total/$rows) + (($total%$rows)?1:0);
		$nDiv  = floor($nPage/$div) + (($nPage%$div)?1:0);	
		$currentDiv = floor($currentPage/$div) ;	
		$sPage = '<span class="pagination-links">';	
		if($currentDiv) {	
			if($li){
				$sPage .= '<li><span class="pagenav"><a title="" class="page-numbers" href="'.$link.'&p=0">&laquo;</a></span></li>';	
				$sPage .= '<li><span class="pagenav"><a title="" class="page-numbers" href="'.$link.'&p='.($currentDiv*$div - 1).'">Back</a></span></li>';	
			}else{
				$sPage .= '<a title="" class="page-numbers" href="'.$link.'&p=0">&laquo;</a> ';	
				$sPage .= '<a title="" class="page-numbers" href="'.$link.'&p='.($currentDiv*$div - 1).'">&#8249;</a> ';	
			}
		}else{
			$sPage .= '<a title="" class="first-page disabled" href="#">&laquo;</a> ';	
			$sPage .= '<a title="" class="prev-page disabled" href="#">&#8249;</a> ';	
		}
		$count =($nPage<=($currentDiv+1)*$div)?($nPage-$currentDiv*$div):$div;	
		for($i=0;$i<$count;$i++){	
			$page = ($currentDiv*$div + $i);	
			if($li){
				$sPage .= '<li '.(($page==$currentPage)? 'class="current"':'class="page-numbers"').'><span class="pagenav"><a title="" href="'.$link.'&p='.($currentDiv*$div + $i ).'" '.(($page==$currentPage)? 'class="current"':'class="page-numbers"').'>'.($page+1).'</a></span></li>';
			}else{
				$sPage .= '<a title="" href="'.$link.'&p='.($currentDiv*$div + $i ).'" '.(($page==$currentPage)? 'class="current"':'class="page-numbers"').'>'.($page+1).'</a> ';
			}
		}	
		if($currentDiv < $nDiv - 1){	
			if($li){	
				$sPage .= '<li><span class="pagenav"><a title="" class="page-numbers" href="'.$link.'&p='.(($currentDiv+1)*$div ).'">Next</a></span></li>';	
				$sPage .= '<li><span class="pagenav"><a title="" class="page-numbers" href="'.$link.'&p='.(($nDiv-1)*$div ).'">&raquo;</a></span></li>';	
			}else{
				$sPage .= '<a title="" class="next-page" href="'.$link.'&p='.(($currentDiv+1)*$div ).'">&#8250;</a> ';	
				$sPage .= '<a title="" class="last-page" href="'.$link.'&p='.(($nDiv-1)*$div ).'">&raquo;</a>';	
			}
		}else{
			$sPage .= '<a title="" class="next-page disabled" href="#">&#8250;</a> ';	
			$sPage .= '<a title="" class="last-page disabled" href="#">&raquo;</a>';	
		}	
		$sPage .= '</span>';
		return 	$sPage;	
	}
	
	public static function limit_words( $str, $len, $more ) {
	   if ($str=="" || $str==NULL) return $str;
	   if (is_array($str)) return $str;
	   $str = trim($str);
	   $str = strip_tags($str);
	   if (strlen($str) <= $len) return $str;
	   $str = substr($str,0,$len);
	   if ($str != "") {
			if (!substr_count($str," ")) {
					  if ($more) $str .= " ...";
					return $str;
			}
			while(strlen($str) && ($str[strlen($str)-1] != " ")) {
					$str = substr($str,0,-1);
			}
			$str = substr($str,0,-1);
			if ($more) $str .= " ...";
			}
			return $str;
	}	
}
