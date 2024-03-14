<?php 
			$global_css = get_option( 'cclw_global_css' ); 
			/*header settings*/
			if(isset($global_css['cclw_heading_group'][0]) && $global_css['cclw_heading_group'][0] !='')
		    {
			  	
			    $headers = $global_css['cclw_heading_group'][0];
					if(!isset($headers['cclw_heading_border_style']))
					{
					$headers['cclw_heading_border_width'] = 3;
					$headers['cclw_heading_border'] = '#000';
					$headers['cclw_heading_border_style'] = 'left';	
					}
												   
			}
			else
			{
				$headers['cclw_heading_background'] = '#77b0eb';
				$headers['cclw_heading_text_color'] = '#000';
				$headers['cclw_heading_border_width'] = 3;
				$headers['cclw_heading_border'] = '#000';
				$headers['cclw_heading_border_style'] = 'left';
				
				
			}
			/*button settings*/
			if(isset($global_css['cclw_button_group'][0]) && $global_css['cclw_button_group'][0] !='')
		    {
			  $buttons = $global_css['cclw_button_group'][0];
			}
			else
			{
			    $buttons['cclw_button_color'] = '#195bbc';
				$buttons['cclw_buttontext_color'] = '#fff';	
				$buttons['cclw_button_hover_color'] = '#195bbc';
				$buttons['cclw_buttontext_hover_color'] = '#fff';	
			}

			?>
<style>
			:root {
			--main-bg-color: <?php echo $headers['cclw_heading_background']?>;  
			--main-bor-text-color: <?php echo $headers['cclw_heading_text_color']?>;
			--main-bor-width: <?php echo $headers['cclw_heading_border_width'].'px'?>;
			--main-bor-color: <?php echo $headers['cclw_heading_border']?>;
			
			--main-button-color: <?php echo $buttons['cclw_button_color']?>;
			--main-buttontext-color: <?php echo $buttons['cclw_buttontext_color']?>;
			--main-buttonhover-color: <?php echo $buttons['cclw_button_hover_color']?>;
			--main-buttonhovertext-color: <?php echo $buttons['cclw_buttontext_hover_color']?>;
					}
			.woocommerce-checkout .cclw_opc_main  .border_html
            {
				border-<?php echo $headers['cclw_heading_border_style'];?>-style : solid;
				border-width: <?php echo $headers['cclw_heading_border_width'];?>px;
                border-color: <?php echo $headers['cclw_heading_border'];?>;
			}				
			
</style>
