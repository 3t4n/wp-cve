<?php
defined( 'ABSPATH' ) || exit;

function point_maker_type_simple_box($atts , $content){


	$color = point_maker_get_base_color( $atts['base_color'] );

	$style['title']['color'] = '';
	$style['title']['border-color'] = '';
	$style['title']['background-color'] = '';
	$style['title_icon']['fill'] = '';
	$style['content']['border-color'] = '';
	$style['content']['background-color'] = '';
	$style['detail']['color'] = '';
	$style['detail']['background-color'] = '';
	$style['BlackOrWhite']['lighter'] = point_maker_BlackOrWhite($color['lighter']);
	$style['BlackOrWhite']['dark'] = point_maker_BlackOrWhite($color['dark']);

	if( $atts['title_color_border'] === 'true'){
		$style['title']['border-color'] = 'border-color:' . $color['base'] . ';';
	}
	if( $atts['content_color_border'] === 'true'){
		$style['content']['border-color'] = 'border-color:' . $color['base'] . ';';
	}


	if( $atts['title_color_background'] === 'true'){

		if( $atts['content_color_background'] === 'true'){

			$style['title']['background-color'] = 'background-color:' . $color['dark'] . ';';
			$style['title']['color'] = 	'color:' . $style['BlackOrWhite']['dark'] . ';';

			if( $style['BlackOrWhite']['dark'] === '#000000'){
				$style['title_icon']['fill'] = '#ffffff';
			}else{
				$style['title_icon']['fill'] = $color['lighter'];
			}

		}else{

			$style['title']['background-color'] = 'background-color:' . $color['lighter'] . ';';
			$style['title']['color'] = 	'color:' . $style['BlackOrWhite']['lighter'] . ';';

			if( $style['BlackOrWhite']['lighter'] === '#000000'){
				$style['title_icon']['fill'] = $color['dark'];
			}else{
				$style['title_icon']['fill'] = '#ffffff';
			}

		}

	}else{

		$style['title']['background-color'] = '';
		$style['title']['color'] = '';
		$style['title_icon']['fill'] = $color['dark'];
	}

	if( $atts['content_color_background'] === 'true'){

		$style['detail']['background-color'] = 'background-color:' . $color['lighter'] . ';';
		$style['detail']['color'] = 'color:' . $style['BlackOrWhite']['lighter'] . ';';

	}else{

		$style['detail']['background-color'] = '';
		$style['detail']['color'] = '';

	}








	//if( !isset( $atts['title_background_color'] ) ) $atts['title_background_color'] = $color['base'];
	//if( !isset( $atts['content_border_color'] ) ) $atts['content_border_color'] = $color['base'];
	//if( !isset( $atts['list_icon_fill'] ) ) $atts['list_icon_fill'] = $color['dark'];

	if( 'list' === $atts['content_type']){
		require_once POINT_MAKER_DIR . 'inc/content_list.php';

		$content = point_maker_content_list($atts , $color['dark'] , $content);
	}

	$title = '';
	$title_icon = '';

	if( '' !== $atts['title_icon']){
		$title_icon = str_replace('<svg', '<svg style="width:22px;height:22px;fill:'.$style['title_icon']['fill'].';"', point_maker_get_svg_icon( $atts['title_icon'] ) );
	}

	if( '' !== $atts['title']){
		$title = '<div>' . $atts['title'] . '</div>';
	}

	$box = '<div class="p_m_wrap p_m_simple_box" style="margin:64px auto;">';

	$box .= '<div class="p_m_content p_m_relative" style="'.$style['content']['border-color'].$style['content']['background-color'].'">';

	$box .= '<div class="p_m_title p_m_relative p_m_flex p_m_ai_c p_m_jc_c" style="'.$style['title']['color'].$style['title']['border-color'].$style['title']['background-color'].'">'.$title_icon.$title.'</div>';

	$box .= '<div class="p_m_detail p_m_'.$atts['content_type'].'" style="'.$style['detail']['color'].$style['detail']['background-color'].'">'.$content.'</div>';

	$box .= '</div>';

	$box .= '</div>';

	return $box;

}
