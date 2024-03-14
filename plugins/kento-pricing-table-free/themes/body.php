<?php

function wpt_price_table_body($postid){

	$wpt_column_width 				= get_post_meta( $postid, 'wpt_column_width', true );
	$wpt_corner_radius 				= get_post_meta( $postid, 'wpt_corner_radius', true );
	$wpt_style 						= get_post_meta( $postid, 'wpt_style', true );
	$wpt_total_row 					= get_post_meta( $postid, 'wpt_total_row', true );
	$wpt_total_column 				= get_post_meta( $postid, 'wpt_total_column', true );
	$wpt_table_field 				= get_post_meta( $postid, 'wpt_table_field', true );
	$wpt_table_field_header 		= get_post_meta( $postid, 'wpt_table_field_header', true );
	$wpt_table_field_price 			= get_post_meta( $postid, 'wpt_table_field_price', true );
	$wpt_table_column_ribon 		= get_post_meta( $postid, 'wpt_table_column_ribon', true );
	$wpt_table_column_signup_text 	= get_post_meta( $postid, 'wpt_table_column_signup_text', true );
	$wpt_table_column_signup_url 	= get_post_meta( $postid, 'wpt_table_column_signup_url', true );
	$wpt_column_margin 				= get_post_meta( $postid, 'wpt_column_margin', true );
	$wpt_featured_column 			= get_post_meta( $postid, 'wpt_featured_column', true );
	$wpt_themes 					= get_post_meta( $postid, 'wpt_themes', true );

	if($wpt_themes=="default"){
		$wpt = "";
		$wpt.= "<div id='price-table-main' class='price-table-main ".$wpt_themes."' >";
		$wpt.=  "<div class='price-table' >";
		$j = 1;
		while($j<=$wpt_total_column){
			$wpt.="<ul class='price-table-column'>";
			$wpt.="<li class='price-table-row' >";
			if($wpt_featured_column==$j){
				$wpt.="<ul class='price-table-column-items wpt-featured table-column-".$j."'>";
			}
			else{
				$wpt.="<ul class='price-table-column-items table-column-".$j."'>";
			}
			$i = 1;
			while($i<=$wpt_total_row){
				if($wpt_style=="style1"){
					if($i==1){
						$wpt.=  "<li class='price-table-items'>";
						$wpt.=  "<div class='wpt-header' >";
						$wpt.=  $wpt_table_field_header[$j];
						$wpt.=  "</div>";
						$wpt.=  "</li>";
					}
					elseif($i==2){
						$wpt.=  "<li class='price-table-items'>";
						$wpt.=  "<div class='price' >";
						$wpt.=  $wpt_table_field_price[$j];
						$wpt.=  "</div>";
						$wpt.=  "</li>";
					}
					elseif($i==$wpt_total_row){
						if(trim($wpt_table_column_signup_text[$j]=="")){
							$wpt.=  "<li class='price-table-items'>";
							$wpt.=  "</li>";
						}
						else{
							$wpt.=  "<li class='price-table-items'>";
							$wpt.=  "<div class='signup' ><a href='".$wpt_table_column_signup_url[$j]."'>";
							$wpt.=  $wpt_table_column_signup_text[$j];
							$wpt.=  "</a></div>";
							$wpt.=  "</li>";
						}
					}
					else{
						if(empty($wpt_table_field[$j.$i])){
							$wpt.=  "<li class='price-table-items li-item-empty'>";
							$wpt.=  "<span class='item-empty'>&nbsp;</span>";
							$wpt.=  "</li>";
						}
						else{
							$wpt.=  "<li class='price-table-items'>";
							$wpt.=  "<div>";
							$wpt.=  $wpt_table_field[$j.$i];
							$wpt.=  "</div>";
							$wpt.=  "</li>";
						}
					}
				}
				elseif($wpt_style=="style2"){
					if($i==1){
						$wpt.=  "<li class='price-table-items'>";
						$wpt.=  "<div class='wpt-header'>";
						$wpt.=  $wpt_table_field_header[$j];
						$wpt.=  "</div>";
						$wpt.=  "</li>";
					}
					elseif($i==2){
						$wpt.=  "<li class='price-table-items'>";
						$wpt.=  "<div class='price' >";
						$wpt.=  $wpt_table_field_price[$j];
						$wpt.=  "</div>";
						$wpt.=  "</li>";
					}
					elseif($i==$wpt_total_row){
						if(trim($wpt_table_column_signup_text[$j]=="")){
							$wpt.=  "<li class='price-table-items'>";
							$wpt.=  "</li>";
						}
						else{
							$wpt.=  "<li class='price-table-items'>";
							$wpt.=  "<div class='signup' ><a href='".$wpt_table_column_signup_url[$j]."'>";
							$wpt.=  $wpt_table_column_signup_text[$j];
							$wpt.=  "</a></div>";
							$wpt.=  "</li>";
						}
					}
					else{
						if(empty($wpt_table_field[$j.$i])){

						}
						else{
							$wpt.=  "<li class='price-table-items'>";
							$wpt.=  "<div>";
							$wpt.=  $wpt_table_field[$j.$i];
							$wpt.=  "</div>";
							$wpt.=  "</li>";
						}
					}
				}
				$i++;
			}
			$wpt.=  "</ul>";
			$wpt.=  "</li>";
			$wpt.=  "</ul>";
			$j++;
		}
		$wpt.=  "</div>";
		$wpt.=  "</div>";
		return $wpt;
	}
}

?>