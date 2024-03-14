<?php
$btn_css ='';
	
		if(!empty($btn_font_family) && isset($btn_font_family)){
			$btn_css .='.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap {'.esc_js($btn_font_family).'}';
		}
		
		if($style=='style-2'){
			$btn_css .='.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap{font-size:'.esc_js($font_size).';line-height:'.esc_js($line_height).';color:'.esc_js($text_color).';padding:'.esc_js($btn_padding).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap:hover{color:'.esc_js($text_hover_color).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button_line{background:'.esc_js($border_color).';}';;
		
			$btn_css .='.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap{font-size:'.esc_js($font_size).';line-height:'.esc_js($line_height).';color:'.esc_js($text_color).';padding:'.esc_js($btn_padding).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap:hover{color:'.esc_js($text_hover_color).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button_line{background:'.esc_js($border_color).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap i.button-after{background: '.esc_js($bg_color).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap:hover i{background: '.esc_js($bg_hover_color).';}';
		}
		
		if($style=='style-9'){
			$btn_css .='.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap{font-size:'.esc_js($font_size).';line-height:'.esc_js($line_height).';color:'.esc_js($text_color).';padding:'.esc_js($btn_padding).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap:hover{color:'.esc_js($text_hover_color).';}.button-'.esc_js($rand_no).'.button-style-7 .button-link-wrap:after{border-color:'.esc_js($text_color).';}.button-'.esc_js($rand_no).'.button-style-7 .button-link-wrap .btn-arrow:after{border-color:'.esc_js($text_hover_color).';}';
		}
		if($style=='style-8'){
			$btn_css .='.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap{font-size:'.esc_js($font_size).';line-height:'.esc_js($line_height).';color:'.esc_js($text_color).';border-color:'.esc_js($border_color).';background: '.esc_js($bg_color).';padding:'.esc_js($btn_padding).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap:hover{background: '.esc_js($bg_hover_color).';color:'.esc_js($text_hover_color).';border-color:'.esc_js($border_hover_color).';}';
		}
		if($style=='style-10'){
			$btn_css .='.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap{font-size:'.esc_js($font_size).';line-height:'.esc_js($line_height).';color:'.esc_js($text_color).';padding:'.esc_js($btn_padding).';background: '.esc_js($bg_color).';border-color:'.esc_js($border_color).';-moz-border-radius:'.esc_js($border_radius).';-webkit-border-radius: '.esc_js($border_radius).';border-radius:'.esc_js($border_radius).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap:hover{color:'.esc_js($text_hover_color).';border-color:'.esc_js($border_hover_color).';background: '.esc_js($bg_hover_color).';}';
		}
		if($style=='style-11'){
			$btn_css .='.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap{background: '.esc_js($bg_color).';font-size:'.esc_js($font_size).';line-height:'.esc_js($line_height).';color:'.esc_js($text_color).';-moz-border-radius:'.esc_js($border_radius).';-webkit-border-radius: '.esc_js($border_radius).';border-radius:'.esc_js($border_radius).';border-color:'.esc_js($border_color).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap::before{color:'.esc_js($text_hover_color).';background: '.esc_js($bg_hover_color).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap::before{border-radius:'.esc_js($border_radius).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap > span,.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap::before{padding:'.esc_js($btn_padding).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap:hover{border-color:'.esc_js($border_hover_color).';}';
			if($select_bg_option!='normal'){
				$btn_css .='.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap,.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap:hover{border:0px;}';
			}
		}
		if($style=='style-12'){
			$btn_css .='.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap{font-size:'.esc_js($font_size).';line-height:'.esc_js($line_height).';color:'.esc_js($text_color).';padding:'.esc_js($btn_padding).';border-color:'.esc_js($border_color).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap:hover{color:'.esc_js($text_hover_color).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap::before{background: '.esc_js($bg_hover_color).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap:hover{border-color:'.esc_js($border_hover_color).';}';
		}
		if($style=='style-16'){
			$btn_css .='.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap{font-size:'.esc_js($font_size).';line-height:'.esc_js($line_height).';color:'.esc_js($text_color).';-moz-border-radius:'.esc_js($border_radius).';-webkit-border-radius: '.esc_js($border_radius).';border-radius:'.esc_js($border_radius).';padding:'.esc_js($btn_padding).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap:hover{color:'.esc_js($text_hover_color).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap::before{border-color:'.esc_js($border_hover_color).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap::after{background:'.esc_js($bg_color).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap::before{background:'.esc_js($bg_hover_color).';}';
		}
		if($style=='style-17'){
			$btn_css .='.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap{font-size:'.esc_js($font_size).';line-height:'.esc_js($line_height).';color:'.esc_js($text_color).';border-color:'.esc_js($border_color).';-moz-border-radius:'.esc_js($border_radius).';-webkit-border-radius: '.esc_js($border_radius).';border-radius:'.esc_js($border_radius).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap:hover,.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap .btn-icon{color:'.esc_js($text_hover_color).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap::before{background:'.esc_js($bg_hover_color).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap span{padding:'.esc_js($btn_padding).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap:hover{border-color:'.esc_js($border_hover_color).';}';
		}
		if($style=='style-19' || $style=='style-20' || $style=='style-21'){
			$btn_css .='.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap{font-size:'.esc_js($font_size).';line-height:'.esc_js($line_height).';color:'.esc_js($text_color).';-moz-border-radius:'.esc_js($border_radius).';-webkit-border-radius: '.esc_js($border_radius).';border-radius:'.esc_js($border_radius).';padding:'.esc_js($btn_padding).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap:hover{color:'.esc_js($text_hover_color).';border-color:'.esc_js($border_hover_color).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap:after{background:'.esc_js($bg_hover_color).';}';
		}
		if($style=='style-19' || $style=='style-20'){
			$btn_css .='.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap{border-color:'.esc_js($border_color).';}';
		}
		if($style=='style-22'){
			$btn_css .='.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap{font-size:'.esc_js($font_size).';line-height:'.esc_js($line_height).';color:'.esc_js($text_color).';background:'.esc_js($bg_color).';border-color:'.esc_js($border_color).';-moz-border-radius:'.esc_js($border_radius).';-webkit-border-radius: '.esc_js($border_radius).';border-radius:'.esc_js($border_radius).';padding:'.esc_js($btn_padding).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap:hover{color:'.esc_js($text_hover_color).';border-color:'.esc_js($border_hover_color).';background:'.esc_js($bg_hover_color).';}.button-'.esc_js($rand_no).'.button-'.$style.' .button-link-wrap .btn-icon{color:'.esc_js($text_hover_color).';}';
		}
		
		return $btn_css;
?>