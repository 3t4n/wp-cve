<?php

function wpt_price_table_style($postid){

		$wpt_themes 						= get_post_meta( $postid, 'wpt_themes', true );
		$wpt_column_width 					= get_post_meta( $postid, 'wpt_column_width', true );
		$wpt_corner_radius 					= get_post_meta( $postid, 'wpt_corner_radius', true );
		$wpt_corner_gradient 				= get_post_meta( $postid, 'wpt_corner_gradient', true );
		$wpt_table_column_color 			= get_post_meta( $postid, 'wpt_table_column_color', true );
		$wpt_total_column 					= get_post_meta( $postid, 'wpt_total_column', true );
		$wpt_bg_img 						= get_post_meta( $postid, 'wpt_bg_img', true );
		$wpt_featured_column 				= get_post_meta( $postid, 'wpt_featured_column', true );
		$wpt_column_margin 					= get_post_meta( $postid, 'wpt_column_margin', true );
		$wpt_table_field_header_height 		= get_post_meta( $postid, 'wpt_table_field_header_height', true );
		$wpt_table_field_header_font_size 	= get_post_meta( $postid, 'wpt_table_field_header_font_size', true );
		$wpt_table_field_header_font_color 	= get_post_meta( $postid, 'wpt_table_field_header_font_color', true );
		
		$wpt_table_field_price_height 		= get_post_meta( $postid, 'wpt_table_field_price_height', true );
		$wpt_table_field_price_font_size 	= get_post_meta( $postid, 'wpt_table_field_price_font_size', true );
		$wpt_table_field_price_font_color 	= get_post_meta( $postid, 'wpt_table_field_price_font_color', true );
		$wpt_table_field_signup_height 		= get_post_meta( $postid, 'wpt_table_field_signup_height', true );
		$wpt_table_field_signup_font_size 	= get_post_meta( $postid, 'wpt_table_field_signup_font_size', true );
		$wpt_table_field_signup_font_color 	= get_post_meta( $postid, 'wpt_table_field_signup_font_color', true );

		if($wpt_themes=="default"){
			echo "<style type='text/css'>";
	
			if(isset($wpt_table_field_header_height)){
				echo ".price-table-main.default .price-table ul li ul li:first-child{
						height: ".$wpt_table_field_header_height."px;
					}";
			}
			if(isset($wpt_table_field_header_font_size)){
				echo ".price-table-main.default .price-table .price-table-items .wpt-header{
					font-size: ".$wpt_table_field_header_font_size."px;
					}";
			}
			if(isset($wpt_table_field_header_font_color)){
				echo ".price-table-main.default .price-table .price-table-items .wpt-header{
					color: ".$wpt_table_field_header_font_color.";
					}";
			}
			if(isset($wpt_table_field_price_height)){
				echo ".price-table-main.default .price-table ul li ul li:nth-child(2){
					height: ".$wpt_table_field_price_height."px;
				}";
			}
			if(isset($wpt_table_field_price_font_size)){
				echo ".price-table-main.default .price-table ul li ul li:nth-child(2) .price{
					font-size: ".$wpt_table_field_price_font_size."px !important;
				}";
			}
			if(isset($wpt_table_field_price_font_color)){
				echo ".price-table-main.default .price-table ul li ul li:nth-child(2) .price{
					color: ".$wpt_table_field_price_font_color.";}";
			}
			if(isset($wpt_table_field_signup_height)){
				echo ".price-table-main.default .price-table ul li ul li:last-child{
					height: ".$wpt_table_field_signup_height."px;}";
			}
			if(isset($wpt_table_field_signup_font_size)){
				echo ".price-table-main.default .price-table ul li ul li:last-child div.signup{
						font-size: ".$wpt_table_field_signup_font_size."px;
					}";
			}
			if(isset($wpt_table_field_signup_font_color)){
				echo ".price-table-main.default .price-table ul li ul li:last-child div.signup a{
						color: ".$wpt_table_field_signup_font_color.";
					}";
			}

		
			if(($wpt_column_margin!=NULL)){
				echo ".price-table-main.default .price-table ul li{
					margin: 0 ".$wpt_column_margin."px;}";
			}

			if(isset($wpt_corner_radius)){
					echo ".price-table-main.default .price-table .price-table-column-items.wpt-featured{
						border-radius: ".$wpt_corner_radius."px;}";
			}

			if(isset($wpt_bg_img)){
				$bg_dir_url = plugins_url("kento-pricing-table-free/css/bg/");
				$bg_name = str_replace($bg_dir_url,"",$wpt_bg_img);
				
				if($bg_name=="wpt-bg-1.jpg"){
						echo ".price-table-main.default .price-table
								{background:none}";
				}
				else{
						echo ".price-table-main.default .price-table
								{background:url('".$wpt_bg_img."') repeat scroll 0 0 rgba(0, 0, 0, 0);}";
					}
			}
			if(!empty($wpt_column_width)){
				echo ".price-table-main.default .price-table ul li ul li
						{width: ".$wpt_column_width."px;}";		
			}
			if(!empty($wpt_table_column_signup_text[$j])){

			}

			$j = 1;
			while($j<=$wpt_total_column){
					echo "
					.price-table-main.default .table-column-".$j." li:first-child
						{
						border-top-left-radius: ".$wpt_corner_radius."px;
						border-top-right-radius: ".$wpt_corner_radius."px;
						}
					.price-table-main.default .table-column-".$j." li:last-child
						{
						border-bottom-left-radius: ".$wpt_corner_radius."px;
						border-bottom-right-radius: ".$wpt_corner_radius."px;
						}";			
					
						if(!empty($wpt_table_column_color[$j])){
							echo "
							.price-table-main.default .table-column-".$j." li:first-child
								{
								border-bottom: 1px solid ".wpt_style_dark_color($wpt_table_column_color[$j])." !important;
								background: linear-gradient(to bottom, ".wpt_style_dark_color($wpt_table_column_color[$j])." 0%, ".$wpt_table_column_color[$j]." ".$wpt_corner_gradient."%) !important;
								background: ".$wpt_table_column_color[$j].";
								background: -moz-linear-gradient(top, ".wpt_style_dark_color($wpt_table_column_color[$j])." 0%, ".$wpt_table_column_color[$j]." ".$wpt_corner_gradient."%);
								background: -webkit-linear-gradient(top, ".wpt_style_dark_color($wpt_table_column_color[$j])." 0%, ".$wpt_table_column_color[$j]." ".$wpt_corner_gradient."%);
								background: linear-gradient(to bottom, ".wpt_style_dark_color($wpt_table_column_color[$j])." 0%, ".$wpt_table_column_color[$j]." ".$wpt_corner_gradient."%);
								}
							.price-table-main.default .table-column-".$j." li:last-child
								{
								background: ".$wpt_table_column_color[$j].";
								background: -moz-linear-gradient(bottom, ".wpt_style_dark_color($wpt_table_column_color[$j])." 0%, ".$wpt_table_column_color[$j]." ".$wpt_corner_gradient."%);
								background: -webkit-linear-gradient(bottom, ".wpt_style_dark_color($wpt_table_column_color[$j])." 0%, ".$wpt_table_column_color[$j]." ".$wpt_corner_gradient."%);
								background: linear-gradient(to top, ".wpt_style_dark_color($wpt_table_column_color[$j])." 0%, ".$wpt_table_column_color[$j]." ".$wpt_corner_gradient."%);
								}
							.price-table-main.default .table-column-".$j." li:nth-child(2)
								{background-color: ".$wpt_table_column_color[$j]." !important;}
							.price-table-main.default .table-column-".$j." li:last-child div a
								{background-color: ".wpt_style_dark_color($wpt_table_column_color[$j])." !important;}";
						}			
						else{
							echo ".price-table-main.default .table-column-".$j." li:first-child
								{
								border-bottom: 1px solid #23c8a7;
								background: #25f5cb;
								background: -moz-linear-gradient(top, #148b73 0%, #25f5cb ".$wpt_corner_gradient."%);
								background: -webkit-linear-gradient(top, #148b73 0%, #25f5cb ".$wpt_corner_gradient."%);
								background: linear-gradient(to bottom, #148b73 0%, #25f5cb ".$wpt_corner_gradient."%);
								}
							.price-table-main.default .table-column-".$j." li:last-child
								{
								background: #25f5cb;
								background: -moz-linear-gradient(bottom, #148b73 0%, #25f5cb ".$wpt_corner_gradient."%);
								background: -webkit-linear-gradient(bottom, #148b73 0%, #25f5cb ".$wpt_corner_gradient."%);
								background: linear-gradient(to top, #148b73 0%, #25f5cb ".$wpt_corner_gradient."%);
								}
							.price-table-main.default .table-column-".$j." li:nth-child(2)
								{background-color: #25f5cb !important;}
							.price-table-main.default .table-column-".$j." li:last-child div a
								{background-color: #11705d !important;}";
						}
					$j++; 	
			}
			echo "</style>";
				
		}
		
		
	}




?>