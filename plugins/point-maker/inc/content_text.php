<?php
defined( 'ABSPATH' ) || exit;


function point_maker_content_text( $atts , $color , $content = null ){

/*
	$atts = shortcode_atts(
		array(
			'list_icon' => 'caret-right-solid',
			'list_icon_fill' => '#94c395',
		), $atts );
*/

		//if( !isset( $atts['list_icon'] ) ) $atts['list_icon'] = 'caret-right-solid';
		//if( !isset( $atts['list_icon_fill'] ) ) $atts['list_icon_fill'] = '#94c395';



		$icon = str_replace('<svg', '<svg style="margin:0 8px 0 0;width:14px;height:14px;fill:'.$color.';"', point_maker_get_svg_icon( $atts['list_icon'] )  );

		if($atts['block_editor'] === 'true'){

			$pattern = '/<ul class="p_m_block_editor p_m_block_list">(.+?)<\/ul>/is';

			preg_match( $pattern, $content, $pickup );

			$pickup = str_replace('<li>', '<li class="p_m_flex p_m_ai_c" style="margin:8px 0;padding:0;">'.$icon.'<div style="flex:1;">', $pickup[1] );
			$pickup = str_replace('</li>', '</div></li>', $pickup );

			$content = preg_replace($pattern, '<ul class="p_m_block_editor p_m_block_list" style="margin:0;padding:0;">'.$pickup.'</ul>', $content);

		}else{

			$array = explode("\n", trim( $content ) ); 
			$array = array_filter($array, 'strlen'); 
			$array = array_values($array); 

			$content = '<ul style="margin:0;padding:0;">';

			foreach ($array as $key => $value) {
				$content .= '<li class="p_m_flex p_m_ai_c" style="margin:8px 0;padding:0;">'.$icon.'<div style="flex:1;">'.$value.'</div></li>';
			}

			$content .= '</ul>';

		}





		return $content;

	}
