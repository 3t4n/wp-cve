<?php
defined( 'ABSPATH' ) || exit;

function point_maker_type_heading_icon($atts , $content){

	$color = point_maker_get_base_color( $atts['base_color'] );

	$style['title']['color'] = '';
	$style['title_icon']['border-color'] = '';
	$style['title_icon']['background-color'] = '';
	$style['title_icon']['fill'] = '';
	$style['content']['border-color'] = '';
	$style['content']['background-color'] = '';
	$style['detail']['color'] = '';
	$style['BlackOrWhite']['lighter'] = point_maker_BlackOrWhite($color['lighter']);
	$style['BlackOrWhite']['dark'] = point_maker_BlackOrWhite($color['dark']);

	if( $atts['title_color_border'] === 'true'){
		$style['title_icon']['border-color'] = 'border-color:' . $color['dark'] . ';';
	}
	if( $atts['content_color_border'] === 'true'){
		$style['content']['border-color'] = 'border-color:' . $color['dark'] . ';';
	}



	if( $atts['title_color_background'] === 'true'){

		$style['title_icon']['background-color'] = 'background-color:' . $color['lighter'] . ';';
		$style['title_icon']['fill'] = $color['dark'];

	}else{

		$style['title_icon']['fill'] = $color['dark'];

	}


	if( $atts['content_color_background'] === 'true'){

		$style['content']['background-color'] = 'background-color:' . $color['lighter'] . ';';
		$style['detail']['color'] = 'color:' . $style['BlackOrWhite']['lighter'] . ';';
		$style['title']['color'] = 'color:' . $color['dark'] . ';';

	}else{
		$style['detail']['color'] = '';
		$style['title']['color'] = 'color:' . $color['dark'] . ';';

	}





	$title = '';
	$title_icon = '';

	if( '' !== $atts['title'] )
		$title = '<div class="p_m_title" style="'.$style['title']['color'].'">'.$atts['title'].'</div>';

	if( '' !== $atts['title_icon'] ){

		//$atts['title_background_color'] = $color['base'];
		//$atts['content_border_color'] = $color['base'];
		//$atts['list_icon_fill'] = $color['dark'];

		$title_icon = str_replace('<svg', '<svg style="width:36px;height:36px;fill:'.$style['title_icon']['fill'].';"', point_maker_get_svg_icon( $atts['title_icon'] ) );

		//if( '' !== $atts['title'] )
			//$title = '<div>' . $atts['title'] . '</div>';

	}else{

		//if( !isset( $atts['title_background_color'] ) ) $atts['title_background_color'] = $color['base'];
		//if( !isset( $atts['content_border_color'] ) ) $atts['content_border_color'] = $color['base'];
		//if( !isset( $atts['list_icon_fill'] ) ) $atts['list_icon_fill'] = $color['dark'];

		//if( '' !== $atts['title'] )
			//$title = '<div style="margin:0;">' . $atts['title'] . '</div>';

	}



	if( 'list' === $atts['content_type']){
		require_once POINT_MAKER_DIR . 'inc/content_list.php';
		$content = point_maker_content_list($atts , $color['dark'] , $content);
	}




	$box = '<div class="p_m_wrap p_m_heading_icon" style="margin:64px auto;">';

	$box .= '<div class="p_m_content p_m_relative" style="'.$style['content']['border-color'].$style['content']['background-color'].'">';

	$box .= '<div class="p_m_title_icon p_m_absolute" style="'.$style['title_icon']['border-color'].$style['title_icon']['background-color'].'">';

	$box .= $title_icon;

	$box .= '</div>';

	$box .= $title;

	$box .= '<div class="p_m_detail p_m_'.$atts['content_type'].'" style="'.$style['detail']['color'].'">'.$content.'</div>';

	$box .= '</div>';



	$box .= '</div>';

	return $box;

}

