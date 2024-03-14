<?php
if(!class_exists('Icon_Manager_Param')) {
	class Icon_Manager_Param {
		function __construct() {

			add_action( 'admin_enqueue_scripts', array( $this, 'icon_fonts_styles' ) );
			
			$GLOBALS['pid']=0;
			$id=null;
			$pcnt=null;
			if(defined('WPB_VC_VERSION') && version_compare(WPB_VC_VERSION, 4.8) >= 0) {
				if(function_exists('vc_add_shortcode_param'))
				{
					vc_add_shortcode_param('iconmanager', array($this,'iconmanager'));
				}
			}
			else {
				if(function_exists('add_shortcode_param'))
				{
					add_shortcode_param('iconmanager', array($this,'iconmanager'));
				}
			}
		}
		
		function iconmanager($settings, $value)
		{
			$GLOBALS['pid'] = $GLOBALS['pid'] + 1;
			$pcnt=$GLOBALS['pid'];
			
			$Borderless_IF = new Borderless_IF;
			$font_manager = $Borderless_IF->get_font_manager($pcnt);
			$dependency = '';
			
			$params = parse_url($_SERVER['HTTP_REFERER']);
			$vc_is_inline = false;
			if(isset($params['query'])){
				parse_str($params['query'],$params);
				$vc_is_inline = isset($params['vc_action']) ? true : false;
			}
			
			$output = '<div class="my_param_block">'
			.'<input name="'.esc_attr($settings['param_name']).'"
			class="wpb_txt_icon_value wpb_vc_param_value wpb-textinput '.esc_attr($settings['param_name']).' 
			'.esc_attr($settings['type']).'_field" type="hidden" 
			
			value="'.esc_attr($value).'" ' . $dependency . ' id="'.esc_attr($pcnt).'"/>'
			.'</div>';
			if($vc_is_inline){
				$output .= '<script type="text/javascript">
				var val=jQuery("#'.esc_attr($pcnt).'").val();
				//alert("yes");
				var val=jQuery("#'.esc_attr($pcnt).'").val();
				var pmid="'.esc_attr($pcnt).'";
				var pmid="'.esc_attr($pcnt).'";
				var val=jQuery("#'.esc_attr($pcnt).'").val();
				if(val==""){
					val="none";
				}
				if(val=="icon_color="){
					val="none";
				}
				
				jQuery(".preview-icon-'.esc_attr($pcnt).'").html("<i class="+val+"></i>");
				
				jQuery(".icon-list-'.esc_attr($pcnt).' li[data-icons=\'"+ val+"\']").addClass("selected");
				
				jQuery(".icons-list li").click(function() {
					
					var id=jQuery(this).attr("id");
					//alert(id);
					jQuery(this).attr("class","selected").siblings().removeAttr("class");
					var icon = jQuery(this).attr("data-icons");
					
					jQuery("#"+id).val(icon);
					jQuery(".preview-icon-"+id).html("<i class=\'"+icon+"\'></i>");
				});
				
				</script>';
			} else {
				
				
				$output .= '<script type="text/javascript">
				
				
				jQuery(document).ready(function(){
					var pmid="'.esc_attr($pcnt).'";
					var val=jQuery("#'.esc_attr($pcnt).'").val();
					if(val==""){
						val="none";
					}
					if(val=="icon_color="){
						val="none";
					}
					
					jQuery(".preview-icon-'.esc_attr($pcnt).'").html("<i class="+val+"></i>");
					
					jQuery(".icon-list-'.esc_attr($pcnt).' li[data-icons=\'"+ val+"\']").addClass("selected");
				});
				jQuery(".icons-list li").click(function() {
					var id=jQuery(this).attr("id");
					//alert(id);
					jQuery(this).attr("class","selected").siblings().removeAttr("class");
					var icon = jQuery(this).attr("data-icons");
					
					jQuery("#"+id).val(icon);
					jQuery(".preview-icon-"+id).html("<i class=\'"+icon+"\'></i>");
				});
				</script>';
			}
			$output .= '<div class="icon-manager-section" data-old-icon-value="'.esc_attr($pcnt).'">'.$font_manager.'</div>';
			return $output;
		}

		public function icon_fonts_styles( $hook ) {
			wp_register_style( 'borderless_wpbakery_icon_fonts', BORDERLESS__URL . 'assets/styles/wpbakery/wpbakery-icon-fonts.css', null, BORDERLESS__VERSION );

			if ( 'post.php' == $hook || 'post-new.php' == $hook ) {
					wp_enqueue_style( 'borderless_wpbakery_icon_fonts' );
			}
		}
		
	}
}

if(class_exists('Icon_Manager_Param'))
{
	$Icon_Manager_Param = new Icon_Manager_Param();
}
