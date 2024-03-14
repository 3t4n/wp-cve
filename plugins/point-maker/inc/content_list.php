<?php
defined( 'ABSPATH' ) || exit;


function point_maker_content_list( $atts , $color , $content = null ){

/*
	$atts = shortcode_atts(
		array(
			'list_icon' => 'caret-right-solid',
			'list_icon_fill' => '#94c395',
		), $atts );
*/

		//if( !isset( $atts['list_icon'] ) ) $atts['list_icon'] = 'caret-right-solid';
		//if( !isset( $atts['list_icon_fill'] ) ) $atts['list_icon_fill'] = '#94c395';



		$icon = str_replace('<svg', '<svg class="p_m_list_icon" width="16" height="16" fill="'.$color.'"', point_maker_get_svg_icon( $atts['list_icon'] )  );

		if($atts['block_editor'] === 'true'){

			$pattern = '/<ul class="p_m_block_editor p_m_block_list">(.+?)<\/ul>/is';

			preg_match( $pattern, $content, $pickup );

			if(!empty($pickup)){
				$pickup = str_replace('<li>', '<li class="p_m_flex p_m_ai_c">'.$icon.'<div style="flex:1;">', $pickup[1] );
				$pickup = str_replace('</li>', '</div></li>', $pickup );

				$content = preg_replace($pattern, '<ul class="p_m_list p_m_block_editor p_m_block_list">'.$pickup.'</ul>', $content);
			}




		}else{

			$array = explode("\n", trim( $content ) ); 
			$array = array_filter($array, 'strlen'); 
			$array = array_values($array); 

			$content = '<ul class="p_m_list">';

			foreach ($array as $key => $value) {
				$content .= '<li class="p_m_flex p_m_ai_c">'.$icon.'<div class="p_m_flex1">'.$value.'</div></li>';
				
			}

			$content .= '</ul>';

		}





		return $content;

	}
