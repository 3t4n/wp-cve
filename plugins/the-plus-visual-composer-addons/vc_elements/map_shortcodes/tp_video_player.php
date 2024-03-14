<?php 
// Video Box Elements
if(!class_exists("ThePlus_video_player")){
	class ThePlus_video_player{
		function __construct(){
			add_action( 'init', array($this, 'init_tp_video_player') );
			add_shortcode( 'tp_video_player',array($this,'tp_video_player_shortcode'));
		}
		function tp_video_player_shortcode($atts,$content = null){
			extract( shortcode_atts( array(
				'video_type' => 'youtube',
				'youtube_id'	=>'2ReiWfKUxIM',
				'image_banner' =>'banner_img',
				'background_color' => '#ffffff',
				'title_color'	=>'#685e52',
				'title_size'  =>'25px',
				'vimeo_id'	=>'',
				'only_img' =>'',
				'banner_image'=>'',
				'image_video' => '',
				'mp4_link' => '',
				'ogg_link' => '',
				
				
				'video_title' => 'The Plus',
				'display_banner_image' => '',
				'popup_video' => 'on',
					"style" => 'style-2',
				'btn_hover_style'=>'hover-left',
				'btn_padding'=>'15px 30px',
				"btn_text" => 'The Plus',
				'btn_hover_text'=>'',
				"btn_icon" => 'fontawesome',
			  'icon_fontawesome'=>'fa fa-arrow-right',
			   'icon_openiconic'=> 'vc-oi vc-oi-dial',
				 'icon_typicons'=> 'typcn typcn-adjust-brightness',
			   'icon_entypo'=> 'entypo-icon entypo-icon-note',    
				 'icon_linecons'=> 'vc_li vc_li-heart',
				 'icon_monosocial'=> 'vc-mono vc-mono-fivehundredpx',
				"before_after" => 'after',
				"btn_url" => '',
				'btn_align' =>'text-left',
				'select_bg_option'=>'normal',
				'normal_bg_color'=>'#252525',
				
				//'gradient_opacity'=>'1',
				'select_bg_hover_option'=>'normal',
				
				'normal_bg_hover_color'=>'#ff214f',
				'normal_bg_hover_color1'=>'#d3d3d3',
				
				
				'font_size'=>'20px',
				'line_height'=>'25px',
				'text_color'=>'#8a8a8a',
				'text_hover_color'=>'#252525',
				'border_color'=>'#252525',
				'border_hover_color'=>'#252525',
				'border_radius'=>'30px',
				
				'full_width_btn'=>'',
				'icon_align' =>'text-left',
				
				'animation_effects'=>'no-animation',
				'animation_delay'=>'50',
				
				'el_class' =>'',
				), $atts ) );
				$rand_no=rand(1000000, 1500000);
				$data_class=$data_attr=$a_href=$a_title=$a_target=$a_rel=$style_content=$icons_before=$icons_after=$button_text=$button_hover_text=$gradient_color=$gradient_hover_color='';
				
				$data_class=' button-'.esc_attr($rand_no).' ';
				$data_class .=' button-'.esc_attr($style).' ';
				
				if($full_width_btn=='yes'){
					$data_class .=' full-button ';
				}
								
				if($select_bg_option=='normal'){
					$bg_color = $normal_bg_color;
				}else{
					$bg_color = '';
				}
				if($select_bg_hover_option=='normal'){
					$bg_hover_color = $normal_bg_hover_color;
				}else{
						$bg_hover_color='';
				}
					
					$btn_url = ( '||' === $btn_url ) ? '' : $btn_url;
					$btn_url_a= vc_build_link( $btn_url);
					
					$a_href = $btn_url_a['url'];
					$a_title = $btn_url_a['title'];
					$a_target = $btn_url_a['target'];
					$a_rel = $btn_url_a['rel'];
					if ( ! empty( $a_rel ) ) {
						$a_rel = ' rel="' . esc_attr( trim( $a_rel ) ) . '"';
					}
				
				if(!empty($btn_icon)){
			  vc_icon_element_fonts_enqueue( $btn_icon );
			  $icon_class = isset( ${'icon_' . $btn_icon} ) ? esc_attr( ${'icon_' . $btn_icon} ) : 'fa fa-arrow-right';
			  
			  if($before_after=='before'){
			   $icons_before = '<i class="btn-icon button-'.esc_attr($before_after).' '.esc_attr($icon_class).'"></i>';
			  }else{
			   $icons_after = '<i class="btn-icon button-'.esc_attr($before_after).' '.esc_attr($icon_class).'"></i>';
			  }
			 }
				
				if($style=='style-2'){
					$button_text =$icons_before . $btn_text . $icons_after;
				}
				
				if($style=='style-9'){
					$button_text =$btn_text.'<span class="btn-arrow"><i class="fa-show fa fa-chevron-right" aria-hidden="true"></i><i class="fa-hide fa fa-chevron-right" aria-hidden="true"></i></span>';
				}
				if($style=='style-11'){
					$button_text ='<span>'.$icons_before . $btn_text . $icons_after.'</span>';
					if(!empty($btn_hover_text)){
						$button_hover_text =' data-hover="'.esc_attr($btn_hover_text).'" ';
					}else{
						$button_hover_text =' data-hover="'.esc_attr($btn_text).'" ';
					}
					$data_class .=' '.esc_attr($btn_hover_style).' ';
				}
				if($style=='style-12'){
					$button_text ='<span>'.$icons_before . $btn_text . $icons_after.'</span>';
				}
				
				
				$the_button ='<div class="'.esc_attr($btn_align).' ts-button">';
					$the_button .='<div class="pt_plus_button '.esc_attr($data_class).'" '.$data_attr.'>';
						$the_button .='<span class="button-link-wrap"  '.$button_hover_text.'>';
							$the_button .=$button_text;
							$the_button .=$style_content;
						$the_button .='</span>';
					$the_button .='</div>';
				$the_button .='</div>';		
				
				
				$video_content=$banner_url=$video_space=$image_video_url=$image_video_src=$only_image=$title='';
				
			$title_css = ' style="';
			 if($title_color!= "") {
			  $title_css .= 'color: '.esc_attr($title_color).';';
			  }
			  if($title_size!= "") {
			  $title_css .= 'font-size: '.esc_attr($title_size).';';
			  }
			   if($background_color!= "") {
			  $title_css .= 'background-color: '.esc_attr($background_color).';';
			  }
			 $title_css .= '"';

				$icon_align_video = '';
			if(!empty($video_title)){
					$title = '<div class="ts-video-caption-text" '.$title_css. '>'.esc_html($video_title).'</div>';
			}
			  if(!empty($only_img)){
						$only_img_icon = wp_get_attachment_image_src( $only_img,true);
						$only_image_src = $only_img_icon[0];
						$only_image .='<img class="ts-video-only-icon" src="'.esc_url($only_img_icon[0]).'" alt="" />';
				}
				
			 if(!empty($image_video)){
						$image_video = wp_get_attachment_image_src( $image_video,true);
						$image_video_src = $image_video[0];
						$image_video_url .='<img class="ts-video-icon" src="'.esc_url($image_video[0]).'" alt="" />';
				}
			if(!empty($banner_image)){
						$banner_image = wp_get_attachment_image_src( $banner_image,true);
						$banner_url .='<img class="ts-video-image-zoom set-image" src="'.esc_url($banner_image[0]).'" alt="" /><div class="ts-video-caption" style="background-image:url('.esc_url($image_video_src).')"></div>'.$title;
				}
			
			if ($image_banner == 'banner_img'){
					if($display_banner_image =='on'){
						if($popup_video =='on'){
							if($video_type=='youtube'){
									$video_content .='<a href="https://www.youtube.com/embed/'.esc_attr($youtube_id).'" data-lity >'.$banner_url.'</a>';
								} else if($video_type=='vimeo') {
									$video_content .='<a href="https://player.vimeo.com/video/'.esc_attr($vimeo_id).'" data-lity >'.$banner_url.'</a>';
								} else if ($video_type=='self-hosted')  {
									$video_content .='<a href="'.esc_url($mp4_link).'" data-lity type="video/mp4">'.$banner_url.'</a>';						
							   }
								  $video_space = 'video-space';
						}else{
							if($video_type=='youtube'){
								$video_content .='<div class="ts-video-wrapper ts-video-hover-effect-zoom ts-type-'.esc_attr($video_type).'" data-mode="lazyload" data-provider="'.$video_type.'" id="ts-video-video-6" itemscope="" itemtype="http://schema.org/VideoObject" data-grow=""><div class="ts-video-embed-container" ><img class="ts-video-thumbnail" data-object-fit="" itemprop="thumbnailUrl" src="'.esc_url($banner_image[0]).'" alt="'.esc_attr("Video Thumbnail").'"><h5 itemprop="name" class="ts-video-title">'.$title.'</h5><span class="ts-video-lazyload" data-allowfullscreen="" data-class="pt-plus-video-frame fitvidsignore" data-frameborder="0" data-scrolling="no" data-src="https://www.youtube.com/embed/'.esc_attr($youtube_id).'?html5=1&amp;title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1"  data-sandbox="allow-scripts allow-same-origin allow-presentation allow-forms" data-width="480" data-height="270"></span><button class="ts-video-play-btn ts-video-blay-btn-youtube" type="button">'.$image_video_url.'</button></div></div>';
							}else if($video_type=='vimeo'){
								$video_content .='<div class="ts-video-wrapper ts-video-hover-effect-zoom ts-type-'.esc_attr($video_type).'" data-mode="lazyload" data-provider="'.$video_type.'" id="ts-video-video-6" itemscope="" itemtype="http://schema.org/VideoObject" data-grow=""><div class="ts-video-embed-container" ><img class="ts-video-thumbnail" data-object-fit="" itemprop="thumbnailUrl" src="'.esc_url($banner_image[0]).'" alt="'.esc_attr("Video Thumbnail").'"><h5 itemprop="name" class="ts-video-title">'.$title.'</h5><span class="ts-video-lazyload" data-allowfullscreen="" data-class="pt-plus-video-frame fitvidsignore" data-frameborder="0" data-scrolling="no" data-src="https://player.vimeo.com/video/'.esc_attr($vimeo_id).'?html5=1&amp;title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1" data-sandbox="allow-scripts allow-same-origin allow-presentation allow-forms" data-width="480" data-height="270"></span><button class="ts-video-play-btn ts-video-blay-btn-youtube" type="button">'.$image_video_url.'</button></div></div>';
							
							}else if($video_type=='self-hosted'){
								$video_content .='<div class="ts-video-wrapper ts-video-hover-effect-zoom ts-type-'.esc_attr($video_type).'" data-mode="lazyload" data-provider="'.$video_type.'" id="ts-video-video-6" itemscope="" itemtype="http://schema.org/VideoObject" data-grow=""><div class="ts-video-embed-container" ><img class="ts-video-thumbnail" data-object-fit="" itemprop="thumbnailUrl" src="'.esc_url($banner_image[0]).'" alt="'.esc_attr("Video Thumbnail").'"><h5 itemprop="name" class="ts-video-title">'.$title.'</h5><div class="video_container"><video class="ts-video-poster" width="100%" poster="'.esc_url($banner_image[0]).'" controls > <source src="'.esc_url($mp4_link).'" type="video/mp4" > <source src="'.esc_url($ogg_link).'" type="video/ogg"></video></div></span><button class="ts-video-play-btn ts-video-blay-btn-youtube" type="button">'.$image_video_url.'</button></div></div>'; 							
							}
						}
					}else{
						if($video_type=='youtube'){
							$video_content .='<div class="ts-video-wrapper embed-container  ts-type-'.esc_attr($video_type).'"><iframe width="100%"  src="https://www.youtube.com/embed/'.esc_attr($youtube_id).'?modestbranding=1&amp;rel=0&amp;autohide=1&amp;showtitle=0" frameborder="0" allowfullscreen></iframe></div>';
						}else if($video_type=='vimeo'){
							$video_content .='<div class="ts-video-wrapper embed-container  ts-type-'.esc_attr($video_type).'"><iframe src="https://player.vimeo.com/video/'.$vimeo_id.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
						}else if($video_type=='self-hosted'){
							$video_content .='<div class="ts-video-wrapper ts-type-'.esc_attr($video_type).'"><video width="100%"> <source src="'.esc_url($mp4_link).'" type="video/mp4" > <source src="'.esc_url($ogg_link).'" type="video/ogg"></video></div>';
								
						}
					}
			}else if ($image_banner == 'only_icon'){
					if($display_banner_image!='on'){
							if($video_type=='youtube'){
									$video_content .='<a href="https://www.youtube.com/embed/'.esc_attr($youtube_id).'" data-lity >'.$only_image.'</a>';
								} else if($video_type=='vimeo') {
									$video_content .='<a href="https://player.vimeo.com/video/'.esc_attr($vimeo_id).'" data-lity >'.$only_image.'</a>';
								} else if ($video_type=='self-hosted')  {
									$video_content .='<a href="'.esc_url($mp4_link).'" data-lity type="video/mp4">'.$only_image.'</a>';	
								}
					}
					$icon_align_video= $icon_align;
			}else if ($image_banner == 'banner_button'){
							if($video_type=='youtube'){
									$video_content .='<a href="https://www.youtube.com/embed/'.esc_attr($youtube_id).'" data-lity >'.$the_button.'</a>';
								} else if($video_type=='vimeo') {
									$video_content .='<a href="https://player.vimeo.com/video/'.esc_attr($vimeo_id).'" data-lity >'.$the_button.'</a>';
								} else if ($video_type=='self-hosted')  {
									$video_content .='<a href="'.esc_url($mp4_link).'" data-lity type="video/mp4">'.$the_button.'</a>';	
								}

			}

			$uid=uniqid('video_player');
			
			$video_player ='<div class="pt_plus_video-box-shadow '.esc_attr($uid).'">';
			$video_player .='<div class="pt_plus_video_player '.esc_attr($video_space).' '.esc_attr($el_class).' '.esc_attr($icon_align_video).'">';
					$video_player .=$video_content;
					if($display_banner_image=='off'){
					$video_player .=$banner_url;
					}
			$video_player .='</div>';
			$video_player .='</div>';
			
			$css_rule='';
			$css_rule .= '<style>';
			if ($image_banner == 'banner_button'){	
				$css_rule .= include THEPLUS_PLUGIN_PATH.'vc_elements/vc_param/button_css.php';
			}
			$css_rule .= '</style>';	
			
			return $css_rule.$video_player;
		}
		function init_tp_video_player(){
			if(function_exists("vc_map"))
			{
				vc_map(array(
					"name" => __("Video Box", "pt_theplus"),
					"base" => "tp_video_player",
					"icon" => "tp-video-player",
					"category" => __("The Plus", "pt_theplus"),
					"description" => esc_html__('Showcase your Videos', 'pt_theplus'),
					"params" => array(
					array(
					"type" => "dropdown",
					"heading" =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('YouTube / Vimeo / Self Hosted ? You can Choose Video Source Type from this options.','pt_theplus').'</span></span>'.esc_html__('Choose Video Source', 'pt_theplus')),
					"param_name" => "video_type",
					"value" => array(
					__("Youtube", "pt_theplus") => "youtube",
					__("Vimeo", "pt_theplus") => "vimeo",
					__("Self Hosted Video", "pt_theplus") => "self-hosted",
					),
					"description" => "",
					"std" =>'youtube',            
					),
					array(
					'type' => 'textfield',
					"heading" =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You need to put YouTube id here. For Example, Here is a Youtube Id (2ReiWfKUxIM) from It&#39;s Permalink (https://youtu.be/2ReiWfKUxIM).','pt_theplus').'</span></span>'.esc_html__('YouTube Id', 'pt_theplus')),
					'param_name' => 'youtube_id',
					'value' => __('2ReiWfKUxIM','pt_theplus'),
					'description' => '',							
					'dependency' => array('element' => 'video_type','value' => 'youtube'),
					),
					array(
					'type' => 'textfield',
					"heading" =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You need to put Vimeo id here. For Example, Here is a Vimeo Id (1819835) from It&#39;s Permalink (https://vimeo.com/1819835).','pt_theplus').'</span></span>'.esc_html__('Vimeo Id', 'pt_theplus')),
					'param_name' => 'vimeo_id',
					'value' => '',
					'description' => '',							
					'dependency' => array('element' => 'video_type','value' => 'vimeo'),
					),
					array(
					'type' => 'textfield',
					"heading" =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can upload Mp4 Version of  video in Media Section of WordPress Backend and Put It&#39;s Link Here. For Chrome, Safari and IE.','pt_theplus').'</span></span>'.esc_html__('Mp4 Video Link', 'pt_theplus')),
					'param_name' => 'mp4_link',
					'value' => '',
					'description' => '',							
					'dependency' => array('element' => 'video_type','value' => 'self-hosted'),
					),
					array(
					'type' => 'textfield',
					"heading" =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can upload Mp4 Version of  video in Media Section of WordPress Backend and Put It&#39;s Link Here. For Firefox and Opera.','pt_theplus').'</span></span>'.esc_html__('Ogg Video Link', 'pt_theplus')),
					'param_name' => 'ogg_link',
					'value' => '',
					'description' => '',							
					'dependency' => array('element' => 'video_type','value' => 'self-hosted'),
					),
					
					array(
					"type" => "dropdown",
					"heading" =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can show normal image and icon thumbnail using FULL BANNER Option and Using ONLY ICON you can setup on popup option for video.','pt_theplus').'</span></span>'.esc_html__('Only Icon / Full Banner', 'pt_theplus')),
					"param_name" => "image_banner",
					"value" => array(
					__("Only Icon image", "pt_theplus") => "only_icon",
					__("Banner Image", "pt_theplus") => "banner_img",
					__("button", "pt_theplus") => "banner_button",
					),
					"description" => "",
					"std" =>'banner_img',            
					),
					array(
					'type' => 'attach_image',
					'heading' => __('Choose Image ','pt_theplus'),
					'param_name' => 'only_img',
					'value' => '',
					'dependency' => array('element' => 'image_banner','value' => 'only_icon'),
					),
					array(
					'type' => 'dropdown',
					'heading' => __('Text Align ','pt_theplus'),
					'param_name' => 'icon_align',
					'value' => array(
					__("Left", "pt_theplus") => "text-left",
					__("Center", "pt_theplus") => "text-center",
					__("Right", "pt_theplus") => "text-right",
					),
					'std'=>'text-left',
					'dependency' => array('element' => 'image_banner','value' => 'only_icon'),
					),
					
					array(
						'type' => 'pt_theplus_checkbox',
						'class' => '',
						"heading" =>  __('Banner Image', 'pt_theplus'),
						'param_name' => 'display_banner_image',
						'description' => '',
						'value' => '',
						'options' => array(
							'on' => array(
								'label' => '',
								'on' => 'on',
								'off' => 'off',
							),
						),
						'dependency' => array('element' => 'image_banner','value' => 'banner_img'),
					),
					array(
					'type' => 'attach_image',
					'heading' => __('Image Upload','pt_theplus'),
					'param_name' => 'banner_image',
					'value' => '',
					"edit_field_class" => "vc_col-xs-6",
					'dependency' => array('element' => 'display_banner_image','value' => 'on'),
					
					),
					array(
					'type' => 'attach_image',
					'heading' => __('Icon Upload ','pt_theplus'),
					'param_name' => 'image_video',
					'value' => '',
					"edit_field_class" => "vc_col-xs-6",
					'dependency' => array('element' => 'display_banner_image','value' => 'on'),
					
					),
					array(
					'type' => 'textfield',
					'class' => '',
					"heading" =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add title of video using this option.','pt_theplus').'</span></span>'.esc_html__('Title of Video', 'pt_theplus')),
					'param_name' => 'video_title',
					"edit_field_class" => "vc_col-xs-6",
					'value' => __('The Plus','pt_theplus'),
					'dependency' => array('element' => 'display_banner_image','value' => 'on'),	
					),
					array(
					'type' => 'textfield',
					'class' => '',
					"heading" =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font size in Pixels using this option. E.g. 14px, 20px, etc.','pt_theplus').'</span></span>'.esc_html__('Title Font Size', 'pt_theplus')),
					'param_name' => 'title_size',
					'value' => '25px',
					"edit_field_class" => "vc_col-xs-6",
					'description' => '',
					"edit_field_class" => "vc_col-xs-6",
					'dependency' => array('element' => 'display_banner_image','value' => 'on'),
					),
					
					array(
					'type' => 'colorpicker',
					"heading" =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can select color and Opacity for border using this option.','pt_theplus').'</span></span>'.esc_html__('Title Color', 'pt_theplus')),
					'param_name' => 'title_color',	
					'value' =>'#685e52',
					"edit_field_class" => "vc_col-xs-6",
					'dependency' => array('element' => 'display_banner_image','value' => 'on'),
					'description' => '',
					),
					array(
					'type' => 'colorpicker',
					"heading" =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select Background color and Opacity for border using this option.','pt_theplus').'</span></span>'.esc_html__('Background Color', 'pt_theplus')),
					'param_name' => 'background_color',	
					'value' =>'#ffffff',		
					"edit_field_class" => "vc_col-xs-6",
					'dependency' => array('element' => 'display_banner_image','value' => 'on'),
					'description' => '',
					),
					array(
					"type" => "dropdown",
					"heading" => __("Style", "pt_theplus"),
					"param_name" => "style",
					"value" => array(
					__("Style-1", "pt_theplus") => "style-2",
					__("Style-2", "pt_theplus") => "style-9",
					__("Style-3", "pt_theplus") => "style-11",
					__("Style-4", "pt_theplus") => "style-12",
					),
					"description" => "",
					"std" =>'style-2',
					'dependency' => array('element' => 'image_banner','value' => 'banner_button'),            
					),
					array(
					"type" => "dropdown",
					"heading" => __("Hover Style", "pt_theplus"),
					"param_name" => "btn_hover_style",
					"value" => array(
					__("On Left", "pt_theplus") => "hover-left",
					__("On Right", "pt_theplus") => "hover-right",
					__("On Top", "pt_theplus") => "hover-top",
					__("On Bottom", "pt_theplus") => "hover-bottom",
					),
					"description" => "",
					"std" =>'hover-left',
					'dependency' => array(
					'element' => 'style',
					'value' => array('style-11'),
					),
					),
					
					array(
					"type" => "textfield",
					"heading" => esc_html__("Button Text", 'pt_theplus'),
					"param_name" => "btn_text",
					"value" => 'The Plus',
					'description' => '',
					'dependency' => array('element' => 'image_banner','value' => 'banner_button'),
					),
					
					array(
					"type" => "textfield",
					"heading" => esc_html__("Button Hover Text", 'pt_theplus'),
					"param_name" => "btn_hover_text",
					"value" => '',
					'description' => '',
					'dependency' => array(
					'element' => 'style',
					'value' => array('style-11'),
					),
					),
					array(
					"type" => "textfield",
					"heading" => esc_html__("Button Inner Padding", 'pt_theplus'),
					"param_name" => "btn_padding",
					"value" => '15px 30px',
					'description' => '',
					'dependency' => array('element' => 'image_banner','value' => 'banner_button'),
					),
					array(
					'type' => 'dropdown',
					'heading' => __( 'Icon library', 'pt_theplus' ),
					'value' => array(
					__( 'Select Icon', 'pt_theplus' ) => '',
					__( 'Font Awesome', 'pt_theplus' ) => 'fontawesome',
					__( 'Open Iconic', 'pt_theplus' ) => 'openiconic',
					__( 'Typicons', 'pt_theplus' ) => 'typicons',
					__( 'Entypo', 'pt_theplus' ) => 'entypo',
					__( 'Mono Social', 'pt_theplus' ) => 'monosocial',
					),
					'std'=>'fontawesome',
					'param_name' => 'btn_icon',
					'description' => '',
					'dependency' => array(
					'element' => 'style',
					'value' => array('style-2','style-11','style-12'),
					),
					),
					array(
					'type' => 'iconpicker',
					'heading' => __( 'Icon', 'pt_theplus' ),
					'param_name' => 'icon_fontawesome',
					'value' => 'fa fa-arrow-right', // default value to backend editor admin_label
					'settings' => array(
					'emptyIcon' => false,
					'iconsPerPage' => 4000,
					),
					'dependency' => array(
					'element' => 'btn_icon',
					'value' => 'fontawesome',
					),
					'description' => '',
					),
					array(
					'type' => 'iconpicker',
					'heading' => __( 'Icon', 'pt_theplus' ),
					'param_name' => 'icon_openiconic',
					'value' => 'vc-oi vc-oi-dial',
					'settings' => array(
					'emptyIcon' => false,
					'type' => 'openiconic',
					'iconsPerPage' => 4000,
					),
					'dependency' => array(
					'element' => 'btn_icon',
					'value' => 'openiconic',
					),
					
					'description' => '',
					),
					array(
					'type' => 'iconpicker',
					'heading' => __( 'Icon', 'pt_theplus' ),
					'param_name' => 'icon_typicons',
					'value' => 'typcn typcn-adjust-brightness',
					'settings' => array(
					'emptyIcon' => false,
					'type' => 'typicons',
					'iconsPerPage' => 4000,
					),
					'dependency' => array(
					'element' => 'btn_icon',
					'value' => 'typicons',
					),
					
					'description' => '',
					),
					array(
					'type' => 'iconpicker',
					'heading' => __( 'Icon', 'pt_theplus' ),
					'param_name' => 'icon_entypo',
					'value' => 'entypo-icon entypo-icon-note',
					'settings' => array(
					'emptyIcon' => false,
					'type' => 'entypo',
					'iconsPerPage' => 4000,
					),
					'dependency' => array(
					'element' => 'btn_icon',
					'value' => 'entypo',
					),
					),
					array(
					'type' => 'iconpicker',
					'heading' => __( 'Icon', 'pt_theplus' ),
					'param_name' => 'icon_linecons',
					'value' => 'vc_li vc_li-heart',
					'settings' => array(
					'emptyIcon' => false,
					'type' => 'linecons',
					'iconsPerPage' => 4000,
					),
					'dependency' => array(
					'element' => 'btn_icon',
					'value' => 'linecons',
					),	
					
					'description' => '',
					),
					array(
					'type' => 'iconpicker',
					'heading' => __( 'Icon', 'pt_theplus' ),
					'param_name' => 'icon_monosocial',
					'value' => 'vc-mono vc-mono-fivehundredpx',
					'settings' => array(
					'emptyIcon' => false,
					'type' => 'monosocial',
					'iconsPerPage' => 4000,
					),
					'dependency' => array(
					'element' => 'btn_icon',
					'value' => 'monosocial',
					),
					'description' => '',
					),
					array(
					"type" => "dropdown",
					"heading" => __("Icon Before/After", "pt_theplus"),
					"param_name" => "before_after",
					"value" => array(
					__("After Icon", "pt_theplus") => "after",
					__("Before Icon", "pt_theplus") => "before",
					),
					"description" => "",
					"std" =>'after', 
					'dependency' => array(
					'element' => 'style',
					'value' => array('style-2','style-11','style-12'),
					),
					),
					array(
					'type'			=> 'vc_link',
					'heading'		=> esc_html__('URL', 'pt_theplus'),
					'param_name'	=> 'btn_url',
					'description'	=> '',
					'dependency' => array('element' => 'image_banner','value' => 'banner_button'),
					),
					array(
					'type'				=> 'pt_theplus_heading_param',
					'text'				=> esc_html__('Button Text Style', 'pt_theplus'),
					'param_name'		=> 'text_style',
					'edit_field_class'	=> 'pt_theplus-heading-param-style vc_col-sm-12',
					"group" => esc_attr__('Style', 'pt_theplus'),
					'dependency' => array('element' => 'image_banner','value' => 'banner_button'),			
					),
					array(
					"type" => "textfield",
					"heading" => esc_html__("Font size", 'pt_theplus'),
					"param_name" => "font_size",
					"value" => '20px',
					'description' => "",
					"edit_field_class" => "vc_col-xs-4",
					"group" => esc_attr__('Style', 'pt_theplus'),
					'dependency' => array('element' => 'image_banner','value' => 'banner_button'),				
					),
					array(
					"type" => "textfield",
					"heading" => esc_html__("line Height", 'pt_theplus'),
					"param_name" => "line_height",
					"value" => '25px',
					'description' => '',
					"edit_field_class" => "vc_col-xs-4",
					"group" => esc_attr__('Style', 'pt_theplus'),
					'dependency' => array('element' => 'image_banner','value' => 'banner_button'),				
					),
					
					array(
					'type' => 'colorpicker',
					'heading' => __( 'color', 'pt_theplus' ),
					'param_name' => 'text_color',
					"value" => '#8a8a8a',
					"description" => "",			
					'group' => esc_attr__('Style', 'pt_theplus'),
					"edit_field_class" => "vc_col-xs-6",
					'dependency' => array('element' => 'image_banner','value' => 'banner_button'),
					),
					array(
					'type' => 'colorpicker',
					'heading' => __( 'Hover color', 'pt_theplus' ),
					'param_name' => 'text_hover_color',
					"value" => '#252525',
					"description" => "",			
					'group' => esc_attr__('Style', 'pt_theplus'),
					"edit_field_class" => "vc_col-xs-6",
					'dependency' => array('element' => 'image_banner','value' => 'banner_button'),
					),
					array(
					'type'				=> 'pt_theplus_heading_param',
					'text'				=> esc_html__('Border Style', 'pt_theplus'),
					'param_name'		=> 'border_style',
					'edit_field_class'	=> 'pt_theplus-heading-param-style vc_col-sm-12',
					"group" => esc_attr__('Style', 'pt_theplus'), 
					'dependency' => array(
					'element' => 'style',
					'value' => array('style-11','style-12'),
					),
					),
					array(
					'type' => 'colorpicker',
					'heading' => __( 'Border color', 'pt_theplus' ),
					'param_name' => 'border_color',
					"value" => '#252525',
					"description" => "",			
					'group' => esc_attr__('Style', 'pt_theplus'),
					"edit_field_class" => "vc_col-xs-4",
					'dependency' => array(
					'element' => 'style',
					'value' => array('style-11','style-12'),
					),
					),
					array(
					'type' => 'colorpicker',
					'heading' => __( 'Border Hover color', 'pt_theplus' ),
					'param_name' => 'border_hover_color',
					"value" => '#252525',
					"description" => "",			
					'group' => esc_attr__('Style', 'pt_theplus'),
					"edit_field_class" => "vc_col-xs-4",
					'dependency' => array(
					'element' => 'style',
					'value' => array('style-11','style-12'),
					),
					),
					array(
					"type" => "textfield",
					"heading" => __("Border Radius", "pt_theplus"),
					"param_name" => "border_radius",
					"value" => "30px",
					"description" => "",
					'group' => esc_attr__('Style', 'pt_theplus'),
					"edit_field_class" => "vc_col-xs-4",
					'dependency' => array(
					'element' => 'style',
					'value' => array('style-11'),
					),
					),
					array(
					'type'				=> 'pt_theplus_heading_param',
					'text'				=> esc_html__('Background Style', 'pt_theplus'),
					'param_name'		=> 'background_style_heading',
					'edit_field_class'	=> 'pt_theplus-heading-param-style vc_col-sm-12',
					"group" => esc_attr__('Style', 'pt_theplus'),
					'dependency' => array(
					'element' => 'style',
					'value' => array('style-11','style-12'),
					),
					),
					array(
					"type" => "dropdown",
					"heading" => __("Select Background Option", "pt_theplus"),
					"param_name" => "select_bg_option",
					"value" => array(
					__("Normal color", "pt_theplus") => "normal",
					__("Gradient color (Premium)", "pt_theplus") => "gradient",
					__("Bg Image (Premium)", "pt_theplus") => "image",
					),
					"description" => "",
					"std" =>'normal',
					'group' => esc_attr__('Style', 'pt_theplus'),
					'dependency' => array(
					'element' => 'style',
					'value' => array('style-11','style-12'),
					),
					),
					array(
					'type' => 'colorpicker',
					'heading' => __( 'color', 'pt_theplus' ),
					'param_name' => 'normal_bg_color',
					"description" => "",			
					'group' => esc_attr__('Style', 'pt_theplus'),
					"value" => '#252525',
					'dependency' => array('element' => 'select_bg_option','value' => 'normal'),
					),
					array(
					"type" => "dropdown",
					"heading" => __("Hover Background Option", "pt_theplus"),
					"param_name" => "select_bg_hover_option",
					"value" => array(
					__("Normal color", "pt_theplus") => "normal",
					__("Gradient color", "pt_theplus") => "gradient",
					__("Bg Image", "pt_theplus") => "image",
					),
					"description" => "",
					"std" =>'normal',
					'group' => esc_attr__('Style', 'pt_theplus'),
					'dependency' => array(
					'element' => 'style',
					'value' => array('style-11','style-12'),
					),
					),
					array(
					'type' => 'colorpicker',
					'heading' => __( 'Hover Bg color', 'pt_theplus' ),
					'param_name' => 'normal_bg_hover_color',
					"description" => "",			
					'group' => esc_attr__('Style', 'pt_theplus'),
					"value" => '#ff214f',
					'dependency' => array('element' => 'select_bg_hover_option','value' => 'normal'),
					),					
					
					array(
					'type' => 'checkbox',
					'heading' => __( 'Full Width Button', 'pt_theplus' ),
					'param_name' => 'full_width_btn',
					'value' => array( __( 'Yes', 'pt_theplus' ) => 'yes' ),
					'description' => '',
					'std'=>'',
					'dependency' => array(
					'element' => 'style',
					'value' => array('style-11','style-12'),
					),
					),
					
					array(
					'type' => 'dropdown',
					'heading' => __( 'Button align', 'pt_theplus' ),
					'param_name' => 'btn_align',
					'value' => array(
					__( 'Left', 'pt_theplus' ) => 'text-left',
					__( 'Center', 'pt_theplus' ) => 'text-center',
					__( 'Right', 'pt_theplus' ) => 'text-right',                                
					),
					'dependency' => array('element' => 'image_banner','value' => 'banner_button'),
					'std'=>'text-left',
					"description" => "", 
					),
					
					array(
						'type' => 'pt_theplus_checkbox',
						'class' => '',
						'heading' => __('Video On Popup', 'pt_theplus'),
						'param_name' => 'popup_video',
						'description' => '',
						'dependency' => array('element' => 'image_banner','value' => 'banner_img'),
						'value' => 'on',
						'options' => array(
							'on' => array(
								'label' => '',
								'on' => 'on',
								'off' => 'off',
							),
						),
					),
					array(
						'type' => 'pt_theplus_heading_param',
						'text' => esc_html__('Animation Settings (Premium)', 'pt_theplus'),
						'param_name' => 'annimation_effect',
						'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
					),	
					array(
					"type"        => "dropdown",
					"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Choose Animation Effect When This Element will be load on scroll. It have many modern options for you to choose from. ','pt_theplus').'</span></span>'.esc_html__('Choose Animation Effect', 'pt_theplus')),
					"param_name"  => "animation_effects",
					"admin_label" => true,
					"value"       => array(
					__( 'No-animation', 'pt_theplus' )             => 'no-animation',
					__( 'FadeIn', 'pt_theplus' )             => 'transition.fadeIn',
					__( 'FlipXIn', 'pt_theplus' )            => 'transition.flipXIn',
					__( 'FlipYIn', 'pt_theplus' )            => 'transition.flipYIn',
					__( 'FlipBounceXIn', 'pt_theplus' )      => 'transition.flipBounceXIn',
					__( 'FlipBounceYIn', 'pt_theplus' )      => 'transition.flipBounceYIn',
					__( 'SwoopIn', 'pt_theplus' )            => 'transition.swoopIn',
					__( 'WhirlIn', 'pt_theplus' )            => 'transition.whirlIn',
					__( 'ShrinkIn', 'pt_theplus' )           => 'transition.shrinkIn',
					__( 'ExpandIn', 'pt_theplus' )           => 'transition.expandIn',
					__( 'BounceIn', 'pt_theplus' )           => 'transition.bounceIn',
					__( 'BounceUpIn', 'pt_theplus' )         => 'transition.bounceUpIn',
					__( 'BounceDownIn', 'pt_theplus' )       => 'transition.bounceDownIn',
					__( 'BounceLeftIn', 'pt_theplus' )       => 'transition.bounceLeftIn',
					__( 'BounceRightIn', 'pt_theplus' )      => 'transition.bounceRightIn',
					__( 'SlideUpIn', 'pt_theplus' )          => 'transition.slideUpIn',
					__( 'SlideDownIn', 'pt_theplus' )        => 'transition.slideDownIn',
					__( 'SlideLeftIn', 'pt_theplus' )        => 'transition.slideLeftIn',
					__( 'SlideRightIn', 'pt_theplus' )       => 'transition.slideRightIn',
					__( 'SlideUpBigIn', 'pt_theplus' )       => 'transition.slideUpBigIn',
					__( 'SlideDownBigIn', 'pt_theplus' )     => 'transition.slideDownBigIn',
					__( 'SlideLeftBigIn', 'pt_theplus' )     => 'transition.slideLeftBigIn',
					__( 'SlideRightBigIn', 'pt_theplus' )    => 'transition.slideRightBigIn',
					__( 'PerspectiveUpIn', 'pt_theplus' )    => 'transition.perspectiveUpIn',
					__( 'PerspectiveDownIn', 'pt_theplus' )  => 'transition.perspectiveDownIn',
					__( 'PerspectiveLeftIn', 'pt_theplus' )  => 'transition.perspectiveLeftIn',
					__( 'PerspectiveRightIn', 'pt_theplus' ) => 'transition.perspectiveRightIn',
					),
					
					'std' =>'no-animation',
					'edit_field_class' => 'vc_col-sm-6',
					),		
					array(
					"type"        => "textfield",
					"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' Add value of delay in transition on scroll in millisecond. 1 sec = 1000 Millisecond ','pt_theplus').'</span></span>'.esc_html__('Animation Delay', 'pt_theplus')),	
					"param_name"  => "animation_delay",	
					"value"       => '50',
					'edit_field_class' => 'vc_col-sm-6',
					"description" => "",
					),
					 array(
							'type' => 'pt_theplus_heading_param',
							'text' => esc_html__('Extra Settings', 'pt_theplus'),
							'param_name' => 'extra_option',
							'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
						),
					 array(
							"type" => "textfield",
							"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can add Extra Class here to use for Customisation Purpose.','pt_theplus').'</span></span>'.esc_html__('Extra Class', 'pt_theplus')),
							"param_name" => "el_class",
							'edit_field_class' => 'vc_col-sm-6',
						),
					)
				) );
			}
		}
	}
	new ThePlus_video_player;

	if(class_exists('WPBakeryShortCode') && !class_exists('WPBakeryShortCode_tp_video_player'))
	{
		class WPBakeryShortCode_tp_video_player extends WPBakeryShortCode
		{
			protected function contentInline($atts, $content = null)
			{
			}
		}
	}
}