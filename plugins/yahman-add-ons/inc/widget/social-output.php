<?php
defined( 'ABSPATH' ) || exit;
/**
 * Social Output
 *
 * @package YAHMAN Add-ons
 */
function yahman_addons_social_output($sns_info){



	$sns_info['icon_attribute'] = $sns_info['icon_type'] = $out_put_style = "";

	
	if($sns_info['icon_shape'] == "icon_rectangle" || $sns_info['icon_shape'] == "icon_hollow_rectangle"){
		$sns_info['icon_attribute'] = " icon_rec";
	}

	
	$sns_info['icon_tooltip']  = $sns_info['icon_attribute'] === ' icon_rec' ? '' : $sns_info['icon_tooltip'];

	
	$sns_info['opacity'] = ( $sns_info['icon_tooltip'] === '' && $sns_info['icon_user_hover_color'] === $sns_info['icon_user_color']) ? ' sns_opacity' : '';

	if($sns_info['icon_shape'] == "icon_hollow_square" || $sns_info['icon_shape'] == "icon_hollow_circle" || $sns_info['icon_shape'] == "icon_hollow_rectangle" || $sns_info['icon_shape'] == "icon_character"){
		$sns_info['icon_type'] = "hollow";
	}

	if($sns_info['icon_shape'] == "icon_square" || $sns_info['icon_shape'] == "icon_circle" ||  $sns_info['icon_shape'] == "icon_rectangle"){
		$sns_info['icon_type'] = "non_hollow";
	}

	
	$sns_info['svg_need_color'] = false;
	if($sns_info['icon_shape'] === "icon_hollow_square" || $sns_info['icon_shape'] === "icon_hollow_circle" || $sns_info['icon_shape'] === "icon_hollow_rectangle" || $sns_info['icon_shape'] == "icon_character"){
		$sns_info['svg_need_color'] = true;
	}

	
	$sns_info['need_bc'] = false;
	if($sns_info['icon_shape'] == "icon_square" || $sns_info['icon_shape'] == "icon_circle" ||  $sns_info['icon_shape'] == "icon_rectangle"){
		$sns_info['need_bc'] = true;
	}

	
	$sns_info['need_border'] = false;
	if($sns_info['icon_shape'] === "icon_hollow_square" || $sns_info['icon_shape'] === "icon_hollow_circle" || $sns_info['icon_shape'] === "icon_hollow_rectangle"){
		$sns_info['need_border'] = true;
	}

	
	
	$sns_info['change_color'] = '';
	if($sns_info['icon_user_color'] !== ''){
		$sns_info['change_color'] .= '1';
	}else{
		$sns_info['change_color'] .= '0';
	}
	if($sns_info['icon_user_hover_color'] !== ''){
		$sns_info['change_color'] .= '1';
	}else{
		$sns_info['change_color'] .= '0';
	}


	
	$sns_info['inline_style_base'] = '';
	$sns_info['style_border_width'] = '1';
	$sns_info['base_size'] = '';
	if($sns_info['icon_size'] === 'icon_small'){
		$sns_info['base_size'] = 28;
		$sns_info['inline_style_base'] .= 'font-size:16px;';
	}elseif($sns_info['icon_size'] === 'icon_large'){
		$sns_info['base_size'] = 40;
		$sns_info['inline_style_base'] .= 'font-size:25px;';
		$sns_info['style_border_width'] = '2';
	}elseif($sns_info['icon_size'] === 'icon_xlarge'){
		$sns_info['base_size'] = 50;
		$sns_info['inline_style_base'] .= 'font-size:30px;';
		$sns_info['style_border_width'] = '2';
	}else{
		$sns_info['base_size'] = 32;
		$sns_info['inline_style_base'] .= 'font-size:18px;';
	}

	
	if($sns_info['icon_attribute'] === " icon_rec"){
		$sns_info['style_size'] = 'width:auto;';
	}else{
		$sns_info['style_size'] = 'width:'.($sns_info['base_size'] + 8).'px;';
	}

	$sns_info['style_size'] .= 'height:'.($sns_info['base_size'] + 8).'px;';
	$sns_info['inline_style_base'] .= $sns_info['style_size'];
$sns_info['svg_size'] = 'width="'.($sns_info['base_size'] ).'" height="'.($sns_info['base_size'] - 8).'"';
$sns_info['svg_style_size'] = 'width:'.($sns_info['base_size'] ).'px;height:'.($sns_info['base_size'] - 8).'px;';

$sns_info['inline_style_base'] .= 'position:relative;text-decoration:none;';



if($sns_info['icon_shape'] === "icon_circle" || $sns_info['icon_shape'] == "icon_hollow_circle"){
	$sns_info['inline_style_base'] .= '-webkit-border-radius:50px;border-radius:50px;';
}


if($sns_info['icon_shape'] === "icon_square" || $sns_info['icon_shape'] == "icon_rectangle" ){
	$sns_info['inline_style_base'] .= '-webkit-box-shadow:inset 0 -4px 0 rgba(0,0,0,0.2);
	box-shadow:inset 0 -4px 0 rgba(0,0,0,.15);';
}


if($sns_info['icon_shape'] == "icon_hollow_square" || $sns_info['icon_shape'] === "icon_square" || $sns_info['icon_attribute'] === " icon_rec"){
	$sns_info['inline_style_base'] .= '-webkit-border-radius:5px;border-radius:5px;';
}


if( $sns_info['need_border'] ){
	$sns_info['inline_style_base'] .= 'border-style:solid;border-width:'.$sns_info['style_border_width'].'px;';
}


if($sns_info['icon_attribute'] === " icon_rec"){
	$sns_info['inline_style_base'] .= 'text-align:left;padding:0 15px 2px 10px;';
}else{
	if($sns_info['icon_size'] === 'icon_large' || $sns_info['icon_size'] === 'icon_xlarge' ){
		$sns_info['inline_style_base'] .= 'padding:6px;';
	}else{
		$sns_info['inline_style_base'] .= 'padding:4px;';
	}

}


$out_put_style .= '#'.$sns_info['widget_id'].' .sns_icon_base{'.$sns_info['inline_style_base'].'}';


echo '<ul class="sns_link_icon f_box f_wrap'.esc_attr($sns_info['opacity']).esc_attr($sns_info['class']).' m0 p0" style="list-style:none;">';

require_once YAHMAN_ADDONS_DIR . 'inc/social-list.php';
$sns_list = yahman_addons_social_name_list();

$i = 0;

while($i++ < $sns_info['loop']){

	if($sns_info['icon'][$i] === 'none'){
		continue;
	}

	$sns_info['javascript'] = '';

	if(isset($sns_list[$sns_info['icon'][$i]]['javascript'])){
		$sns_info['javascript'] = ' '.$sns_list[$sns_info['icon'][$i]]['javascript'];
	}

	$sns_info['icon'][$i] === 'feedly' ? $sns_info['account'][$i] = 'feedly' : '';
	$sns_info['icon'][$i] === 'rss' ? $sns_info['account'][$i] = 'rss' : '';
	$sns_info['icon'][$i] === 'rss2' ? $sns_info['account'][$i] = 'rss2' : '';
	$sns_info['icon'][$i] === 'rdf' ? $sns_info['account'][$i] = 'rdf' : '';
	$sns_info['icon'][$i] === 'atom' ? $sns_info['account'][$i] = 'atom' : '';


	
	$sns_info['svg_radialGradient'] = '';
	$sns_info['css_radialGradient'] = '';
	if ($sns_info['icon'][$i] === 'instagram'){
		$sns_info['svg_radialGradient'] = '<radialGradient id="instagram" r="150%" cx="30%" cy="107%"><stop stop-color="#fdf497" offset="0" /><stop stop-color="#ffe52a" offset="0.05" /><stop stop-color="#fd5949" offset="0.45" /><stop stop-color="#d6249f" offset="0.6" /><stop stop-color="#285AEB" offset="0.9" /></radialGradient>';
		$sns_info['css_radialGradient'] = 'background:radial-gradient(circle at 30% 107%,#fdf497 0%,#ffe52a 5%,#fd5949 45%,#d6249f 60%,#285AEB 90%);';
	}

	if ($sns_info['icon'][$i] != 'none' && $sns_info['account'][$i] != ''){
		switch ($sns_info['icon'][$i]){

			case 'amazon':
			$sns_info['url'][$i] = esc_url($sns_info['account'][$i]);
			break;

			case 'tumblr':
			$sns_info['url'][$i] = esc_url('https://'.$sns_info['account'][$i].'.tumblr.com/');
			break;


			case 'feedly':
			case 'rss':
			case 'rss2':
			case 'rdf':
			case 'atom':
			$sns_info['url'][$i] = esc_url($sns_list[$sns_info['icon'][$i]]['base']);
			break;

			default:
			$sns_info['url'][$i] = esc_url($sns_list[$sns_info['icon'][$i]]['base'].$sns_info['account'][$i]);
		}
	}


	if ($sns_info['icon'][$i] != 'none' && $sns_info['share'][$i] != ''){
		$title = get_the_title();
		$blogname = get_bloginfo( 'name' );
		$excerpt = mb_strimwidth(strip_tags(get_post()->post_content), 0, 120, '...');



		switch ($sns_info['share'][$i]){
			case 'buffer':
			$sns_info['url'][$i] = esc_url($sns_list[$sns_info['icon'][$i]]['share'].$title.'&url='.$sns_info['url'][$i]);
			break;

			case 'digg':
			case 'evernote':
			case 'hatenabookmark':
			case 'pocket':
			case 'reddit':
			$sns_info['url'][$i] = esc_url($sns_list[$sns_info['icon'][$i]]['share'].$sns_info['url'][$i].'&title='.$title);
			break;

			case 'mail':        
			$sns_info['url'][$i] = esc_url('mailto:?subject='.sprintf(esc_html__( 'Shared from &ldquo;%s&rdquo;', 'yahman-add-ons' ), $blogname ).'&amp;body='.$title.'%0d%0a'.$sns_info['url'][$i]);
			break;


			case 'facebook':
        //case 'googleplus':
			case 'line':
			case 'linkedin':
			case 'note':
			$sns_info['url'][$i] = esc_url($sns_list[$sns_info['icon'][$i]]['share'].$sns_info['url'][$i]);
			break;


			case 'messenger':
			$sns_info['url'][$i] = $sns_list[$sns_info['icon'][$i]]['share'].$sns_info['url'][$i].'&app_id='.$sns_info['facebook_app_id'];
			break;
        //<a href=”fb-messenger://share/?link= https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fsharing%2Freference%2Fsend-dialog&app_id=123456789”>Send In Messenger</a>

			case 'pinterest':

			
			require_once YAHMAN_ADDONS_DIR . 'inc/get_thumbnail.php';
			$post = get_post();
			$thumurl = yahman_addons_get_thumbnail( $post->ID , 'medium' );

			$sns_info['url'][$i] = esc_url('https://www.pinterest.com/pin/create/bookmarklet/?url='.$sns_info['url'][$i].'&media='.esc_url($thumurl[0]).'&is_video=false&description='.$excerpt);
        //'https://www.pinterest.com/pin/create/button/?url='.$sns_info['url'][$i]
			break;

			case 'tumblr':
			$sns_info['url'][$i] = esc_url($sns_list[$sns_info['icon'][$i]]['share'].$sns_info['url'][$i].'&name='.$title.'&description='.$excerpt);
			break;

			case 'twitter':
			case 'x':
			$sns_info['url'][$i] = esc_url($sns_list[$sns_info['icon'][$i]]['share'].$sns_info['url'][$i].'&text='.$title);
			break;

			case 'whatsapp':
			$sns_info['url'][$i] = esc_url($sns_list[$sns_info['icon'][$i]]['share'].$title.' '.$sns_info['url'][$i]);
			break;

			case 'print':
			$sns_info['url'][$i] = $sns_list[$sns_info['icon'][$i]]['share'];
			break;

			default:
			
		}
	}



	switch ($sns_info['icon'][$i]){
		case 'hatenabookmark':
		$sns_info['title'][$i] = esc_attr_x('Hatena', 'hatebu', 'yahman-add-ons');
		break;

		case 'rss':
		case 'rss2':
		case 'rdf':
		case 'atom':
		$sns_info['title'][$i] = $sns_list[$sns_info['icon'][$i]]['name'];
		$sns_info['icon'][$i] = 'rss';
		break;

		case 'print':
		case 'note':
		$sns_info['title'][$i] = $sns_list[$sns_info['icon'][$i]]['name'];
		break;

		default:
		$sns_info['title'][$i] = esc_attr( ucfirst( $sns_info['icon'][$i] ) );
	}


	
	$sns_info['hover_style'] = $sns_info['flickr_style'] = $sns_info['flickr_hover_style'] = $sns_info['External_color'] = $sns_info['External_bg_color'] = $sns_info['inline_style'] = '';

	$sns_info['External_fill'] = 'fill:'.$sns_list[$sns_info['icon'][$i]]['color'].';';

	$sns_info['base_color'] = $sns_list[$sns_info['icon'][$i]]['color'];

	$sns_info['flickr_l_color'] = $sns_info['flickr_l_hover_color'] = '#f40083';
	$sns_info['flickr_r_color'] = $sns_info['flickr_r_hover_color'] = '#006add';
	$sns_info['flickr_bg_color'] = $sns_info['flickr_bg_hover_color'] = '#fff';
	$sns_info['flickr_after_color'] = '#333';
	$sns_info['flickr_after_hover_color'] = '#333';






	$sns_info['inline_style'] .= $sns_info['icon_shape'] === "icon_rectangle" ? 'color:#fff;':'';

	if($sns_info['need_border']){

		if( 'flickr' !== $sns_info['icon'][$i] ){
			if($sns_info['change_color'] === '00' || $sns_info['change_color'] === '01' ){
				$sns_info['External_color'] = 'color:'.$sns_info['base_color'].';';
				if( 'instagram' === $sns_info['icon'][$i] ){
					$sns_info['External_fill'] = 'fill:url(#instagram);';
				}
			}
			if($sns_info['change_color'] === '10' || $sns_info['change_color'] === '11' ){
				$sns_info['External_fill'] = 'fill:'.$sns_info['icon_user_color'].';';
				$sns_info['External_color'] = 'color:'.$sns_info['icon_user_color'].';';
			}
			if($sns_info['change_color'] === '01' || $sns_info['change_color'] === '11' ){
				$sns_info['hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover{color:'.$sns_info['icon_user_hover_color'].';}';
				$sns_info['hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover .sns_icon_'.$i.'{fill:'.$sns_info['icon_user_hover_color'].';}';
			}
			if($sns_info['change_color'] === '10'){
				$sns_info['hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover .sns_icon_'.$i.'{fill:'.$sns_info['base_color'].';}';
				$sns_info['hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover{color:'.$sns_info['base_color'].';}';

				if( 'instagram' === $sns_info['icon'][$i] ){
					$sns_info['hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover .sns_icon_'.$i.'{fill:url(#instagram);}';
				}
			}
		}else{


			if($sns_info['change_color'] === '00' || $sns_info['change_color'] === '01' ){
				$sns_info['External_color'] = 'color:#333;';
			}
			if($sns_info['change_color'] === '10' || $sns_info['change_color'] === '11' ){
				$sns_info['External_color'] = 'color:'.$sns_info['icon_user_color'].';';

			}
			if($sns_info['change_color'] === '01' || $sns_info['change_color'] === '11' ){
				$sns_info['flickr_hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover path{fill:'.$sns_info['icon_user_hover_color'].';}';
				$sns_info['flickr_hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover{color:'.$sns_info['icon_user_hover_color'].';}';
			}
			if($sns_info['change_color'] === '10'){
				$sns_info['flickr_hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover .sns_icon_l_'.$i.'{fill:'.$sns_info['flickr_l_color'].';}';
				$sns_info['flickr_hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover .sns_icon_r_'.$i.'{fill:'.$sns_info['flickr_r_color'].';}';
				$sns_info['flickr_hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover{color:#333;}';
			}

		}



	}


	if($sns_info['need_bc']){
		$sns_info['External_fill'] = 'fill:#fff;';

		if($sns_info['change_color'] === '00' || $sns_info['change_color'] === '01' ){
			$sns_info['External_bg_color'] = 'background:'.$sns_info['base_color'].';';
			if( 'flickr' === $sns_info['icon'][$i] ){
				$sns_info['External_bg_color'] = 'background:#fff;';
				$sns_info['inline_style'] = 'color:#000;';
			}
			if( 'instagram' === $sns_info['icon'][$i] ){
				$sns_info['External_bg_color'] .= $sns_info['css_radialGradient'];
			}
		}
		if($sns_info['change_color'] === '10' || $sns_info['change_color'] === '11' ){
			$sns_info['External_bg_color'] = 'background:'.$sns_info['icon_user_color'].';';
		}
		if($sns_info['change_color'] === '01' || $sns_info['change_color'] === '11' ){
			$sns_info['hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover{background:'.$sns_info['icon_user_hover_color'].';}';
			if( 'flickr' === $sns_info['icon'][$i] ){
				$sns_info['flickr_hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover path{fill:#fff;}';
			}
		}
		if($sns_info['change_color'] === '10'){
			$sns_info['hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover{background:'.$sns_info['base_color'].';}';

			if( 'instagram' === $sns_info['icon'][$i] ){
				$sns_info['hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover{'.$sns_info['css_radialGradient'].'}';
			}

			if( 'flickr' === $sns_info['icon'][$i] ){
				$sns_info['flickr_hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover .sns_icon_l_'.$i.'{fill:'.$sns_info['flickr_l_color'].';}';
				$sns_info['flickr_hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover .sns_icon_r_'.$i.'{fill:'.$sns_info['flickr_r_color'].';}';
				$sns_info['hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover{background:#fff;}';
			}


		}
	}


	if($sns_info['icon_shape'] === "icon_character"){

		if($sns_info['change_color'] === '00' || $sns_info['change_color'] === '01' ){
			$sns_info['External_fill'] = 'fill:'.$sns_info['base_color'].';';
			if( 'instagram' === $sns_info['icon'][$i] ){
				$sns_info['External_fill'] = 'fill:url(#instagram);';
			}
		}
		if($sns_info['change_color'] === '10' || $sns_info['change_color'] === '11' ){
			$sns_info['External_fill'] = 'fill:'.$sns_info['icon_user_color'].';';
		}
		if($sns_info['change_color'] === '01' || $sns_info['change_color'] === '11' ){
			$sns_info['hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover .sns_icon_'.$i.'{fill:'.$sns_info['icon_user_hover_color'].';}';
		}
		if($sns_info['change_color'] === '10'){

			$sns_info['hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover .sns_icon_'.$i.'{fill:'.$sns_info['base_color'].';}';
			if( 'instagram' === $sns_info['icon'][$i] ){
				$sns_info['hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover .sns_icon_'.$i.'{fill:url(#instagram);}';
			}
		}
	}






	
	if($sns_info['icon_tooltip'] === ' sns_tooltip'){

		$sns_info['tooltip_color'] = '#fff';
		$sns_info['tooltip_bg_color'] = $sns_list[$sns_info['icon'][$i]]['color'];

		if($sns_info['icon'][$i] ==='flickr'){
			$sns_info['tooltip_bg_color'] = '#000';
		}

		if($sns_info['icon_user_hover_color'] !== ''){
			$sns_info['tooltip_bg_color'] = $sns_info['icon_user_hover_color'];
		}

		$sns_info['hover_style'] .= '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover:after{color:'.$sns_info['tooltip_color'].';background:'.$sns_info['tooltip_bg_color'].';}#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).':hover:before{color:'.$sns_info['tooltip_bg_color'].';}';

	}


	$out_put_style .= '#'.$sns_info['widget_id'].' .sns_icon_'.$i.'{'.$sns_info['External_fill'].'}';
	$out_put_style .= $sns_info['External_color'] === '' && $sns_info['External_bg_color'] === '' ? '' : '#'.$sns_info['widget_id'].' .sns_'.esc_attr( $sns_info['icon'][$i] ).'{'.$sns_info['External_color'].$sns_info['External_bg_color'].'}';
	$out_put_style .= $sns_info['hover_style'].$sns_info['flickr_style'].$sns_info['flickr_hover_style'];


	if($sns_info['icon'][$i] != 'none' && $sns_info['url'][$i] != ''){
		echo '<li>';
		echo '<a href="'.$sns_info['url'][$i].'"'.$sns_info['javascript'].' target="_blank" rel="noopener noreferrer" class="sns_'.esc_attr( $sns_info['icon'][$i] ).' sns_icon_base f_box ai_c jc_c '.esc_attr( $sns_info['icon_shape'] ). esc_attr($sns_info['icon_tooltip']) .' non_hover flow_box tap_no'.esc_attr($sns_info['icon_attribute']).'"';
		echo ' title="'.esc_attr( $sns_info['title'][$i] ) .'"';
		echo ' style="'. $sns_info['inline_style'];

		echo '">';
		if( 'flickr' !== $sns_info['icon'][$i] ){
			echo '<svg class="svg-icon" '.$sns_info['svg_size'].' viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">'.$sns_info['svg_radialGradient'].'<path class="sns_icon_'.$i.'" '.$sns_list[$sns_info['icon'][$i]]['svg'].'/></svg>';
		}else{
			echo '<svg class="svg-icon svg_flickr" '.$sns_info['svg_size'].' viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path class="sns_icon_l_'.$i.'" '.$sns_list['flickr']['svg_L'].'/><path class="sns_icon_r_'.$i.'" '.$sns_list['flickr']['svg_R'].'/></svg>';
		}


		echo'</a></li>';
	}



}

echo '</ul>';



if(!YAHMAN_ADDONS_TEMPLATE){
	add_action( 'wp_footer', 'yahman_addons_enqueue_style_social' );
}

if($out_put_style !== ''){

	add_action( 'wp_footer', function () use ($out_put_style) {
		echo '<style type="text/css">' . $out_put_style . '</style>' . "\n";
	} );

}


}
