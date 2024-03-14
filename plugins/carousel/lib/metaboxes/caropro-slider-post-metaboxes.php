<?php

	if( !defined( 'ABSPATH' ) ){
	    exit;
	}

function carpro_register_meta_boxes() {

	$carousel = array( 'carousel_shortcode');
    add_meta_box(
        'carpro_details_meta_box_set1',                         # Metabox
        __( 'Carousel Settings', 'carosuelfree' ),           	# Title
        'carpro_display_post_type_function',                    # Call Back func
       	$carousel,                                         		# post type
        'normal'                                                # Text Content
    );

    add_meta_box(
        'carpro_details_meta_box_set3',                 		# Metabox
        __( 'Carousel Details', 'carosuelfree' ),           		# Title
        'carpro_details_settings_function',              		# Call Back func
       	'tpmfcarousel',                                        	# post type
        'normal'                                                # Text Content
    );

}
add_action( 'add_meta_boxes', 'carpro_register_meta_boxes' );

# Call Back Function...
function carpro_display_post_type_function( $post, $args){

	#Call get post meta.
	$carpro_slider_postoptions		= get_post_meta($post->ID, 'carpro_slider_postoptions', true );
	if(!empty($carpro_slider_postoptions['post_types'])){
		$post_types = $carpro_slider_postoptions['post_types'];
	}
	else{
		$post_types = array();
	}
	if(!empty($carpro_slider_postoptions['categories'])){
		$categories = $carpro_slider_postoptions['categories'];
	}
	else{
		$categories = array();
	}
	

	$carpro_slider_styles 			  	= get_post_meta($post->ID, 'carpro_slider_styles', true);
	$carpro_slider_order_cat 			= get_post_meta($post->ID, 'carpro_slider_order_cat', true);
	$carprobg_color 					= get_post_meta($post->ID, 'carprobg_color', true);
	$carpro_slider_order 				= get_post_meta($post->ID, 'carpro_slider_order', true);
	$carpro_slider_hide_img				= get_post_meta($post->ID, 'carpro_slider_hide_img', true);
	$carpro_slider_hide_img_captions	= get_post_meta($post->ID, 'carpro_slider_hide_img_captions', true);
	$carpro_slider_hide_link			= get_post_meta($post->ID, 'carpro_slider_hide_link', true);
	$carpro_contentclr					= get_post_meta($post->ID, 'carpro_contentclr', true);
	$carpro_con_align					= get_post_meta($post->ID, 'carpro_con_align', true);
	$carpro_datetime_hide				= get_post_meta($post->ID, 'carpro_datetime_hide', true);
	$carpro_date_align					= get_post_meta($post->ID, 'carpro_date_align', true);
	$carpro_dattext_size				= get_post_meta($post->ID, 'carpro_dattext_size', true);
	$carpro_datetclr					= get_post_meta($post->ID, 'carpro_datetclr', true);
	$carpro_context_size				= get_post_meta($post->ID, 'carpro_context_size', true);
	$carpro_slider_img_height           = get_post_meta($post->ID, 'carpro_slider_img_height', true);
	$carpro_slider_overlay_color 		= get_post_meta($post->ID, 'carpro_slider_overlay_color', true);
	$carpro_slider_overlaycolor_icons 	= get_post_meta($post->ID, 'carpro_slider_overlaycolor_icons', true);
	$carpro_slider_border_icons 		= get_post_meta($post->ID, 'carpro_slider_border_icons', true);
	$carpro_slider_overlayicons_color 	= get_post_meta($post->ID, 'carpro_slider_overlayicons_color', true);
	$carpro_slider_excerpt_color        = get_post_meta($post->ID, 'carpro_slider_excerpt_color', true);
	$carpro_excerptbg_color       		= get_post_meta($post->ID, 'carpro_excerptbg_color', true);
	$carpro_slider_capbg_color          = get_post_meta($post->ID, 'carpro_slider_capbg_color', true);
	$carpro_slider_captext_color        = get_post_meta($post->ID, 'carpro_slider_captext_color', true);
	$carpro_slider_captext_size         = get_post_meta($post->ID, 'carpro_slider_captext_size', true);
	$carpro_slider_captext_align        = get_post_meta($post->ID, 'carpro_slider_captext_align', true);
	$excerpt_lenght 	  				= get_post_meta($post->ID, 'excerpt_lenght', true);
	$btn_readmore         				= get_post_meta($post->ID, 'btn_readmore', true);
	$nav_value              			= get_post_meta( $post->ID, 'nav_value', true);
	
	
	
	
	#Call get post meta.
	$carpro_slider_autoplay   			= get_post_meta($post->ID, 'carpro_slider_autoplay', true);
	$carpro_slider_autoplay_speed   	= get_post_meta($post->ID, 'carpro_slider_autoplay_speed', true);
	$carpro_slider_items 				= get_post_meta($post->ID, 'carpro_slider_items', true);
	$carpro_slider_itemsdesktop   		= get_post_meta($post->ID, 'carpro_slider_itemsdesktop', true);
	$carpro_slider_itemsdesktopsmall  	= get_post_meta($post->ID, 'carpro_slider_itemsdesktopsmall', true);
	$carpro_slider_itemsmobile   		= get_post_meta($post->ID, 'carpro_slider_itemsmobile', true);
	$carpro_slider_loop 				= get_post_meta($post->ID, 'carpro_slider_loop', true);
	$carpro_slider_margin 				= get_post_meta($post->ID, 'carpro_slider_margin', true);
	$carpro_slider_autoplaytimeout    	= get_post_meta($post->ID, 'carpro_slider_autoplaytimeout', true);
	$carpro_slider_stophover   			= get_post_meta($post->ID, 'carpro_slider_stophover', true);
	$carpro_slider_navigation 			= get_post_meta($post->ID, 'carpro_slider_navigation', true);
	$carpro_slider_navigation_position 	= get_post_meta($post->ID, 'carpro_slider_navigation_position', true);
	$carpro_slider_pagination 			= get_post_meta($post->ID, 'carpro_slider_pagination', true);
	$carpro_slider_paginationposition 	= get_post_meta($post->ID, 'carpro_slider_paginationposition', true);
	$carpro_slider_navtext_color     	= get_post_meta($post->ID, 'carpro_slider_navtext_color', true);	
	$carpro_slider_navtext_hovercolor   = get_post_meta($post->ID, 'carpro_slider_navtext_hovercolor', true);	
	$carpro_slider_navbg_color       	= get_post_meta($post->ID, 'carpro_slider_navbg_color', true);
	$carpro_slider_navbg_hovercolor     = get_post_meta($post->ID, 'carpro_slider_navbg_hovercolor', true);
	$carpro_slider_pagination_color   	= get_post_meta($post->ID, 'carpro_slider_pagination_color', true);
	$carpro_slider_pagination_bgcolor	= get_post_meta($post->ID, 'carpro_slider_pagination_bgcolor', true);
	$carpro_slider_pagination_style		= get_post_meta($post->ID, 'carpro_slider_pagination_style', true);

?>

	<div class="logoshowcase_backend_settings post-grid-metabox">
		<!-- <div class="wrap"> -->
		<ul class="tab-nav">
			<li nav="1" class="nav1 <?php if($nav_value == 1){echo "active";}?>"><?php _e('Shortcodes','carosuelfree'); ?></li>
			<li nav="2" class="nav2 <?php if($nav_value == 2){echo "active";}?>"><?php _e('Query Carousel ','carosuelfree'); ?></li>
			<li nav="3" class="nav3 <?php if($nav_value == 3){echo "active";}?>"><?php _e('Carousel Settings ','carosuelfree'); ?></li>
			<li nav="4" class="nav4 <?php if($nav_value == 4){echo "active";}?>"><?php _e('General Settings ','carosuelfree'); ?></li>
			<li nav="5" class="nav5 <?php if($nav_value == 5){echo "active";}?>"><?php _e('Support','carosuelfree'); ?></li>
		</ul> <!-- tab-nav end -->
		<?php
			$getNavValue = "";
			if(!empty($nav_value)){ $getNavValue = $nav_value; } else { $getNavValue = 1; }
		?>
		<input type="hidden" name="nav_value" id="nav_value" value="<?php echo $getNavValue; ?>"> 

		<ul class="box">
			<!-- Tab 1  -->
			<li style="<?php if($nav_value == 1){echo "display: block;";} else{ echo "display: none;"; }?>" class="box1 tab-box <?php if($nav_value == 1){echo "active";}?>">
				<div class="option-box">
					<p class="option-title"><?php _e('Shortcode','carosuelfree'); ?></p>
					<p class="option-info"><?php _e('Copy this shortcode and paste on page or post where you want to display Carousel Ultimate. <br />Use PHP code to your themes file to display Carousel Ultimate.','carosuelfree'); ?></p>
					<textarea cols="50" rows="1" onClick="this.select();" >[carousel_composer <?php echo 'id="'.$post->ID.'"';?>]</textarea>
					<br /><br />
					<p class="option-info"><?php _e('PHP Code:','carosuelfree'); ?></p>
					<textarea cols="50" rows="2" onClick="this.select();" ><?php echo '<?php echo do_shortcode("[carousel_composer id='; echo "'".$post->ID."']"; echo '"); ?>'; ?></textarea>  
				</div>
			</li>

			<!-- Tab 2  -->
			<li style="<?php if($nav_value == 2){echo "display: block;";} else{ echo "display: none;"; }?>" class="box2 tab-box <?php if($nav_value == 2){echo "active";}?>">

				<div class="wrap">
					<table class="form-table">
						<div class="option-box">
							<p class="option-title"><?php _e('Query Carousel','carosuelfree'); ?></p>
							<tr valign="top">
								<th scope="row">
									<label for="post-type"><?php _e('Post Type', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select required name="carpro_slider_postoptions[post_types][]" id="carpro_slider_postoptions" class="timezone_string changer">
										<option value="">Select</option>	
											<?php 
											foreach ( get_post_types( '', 'names' ) as $post_type ){
												global $wp_post_types;
												if(in_array($post_type,$post_types)){
													$selected = "selected";
												}
												else{
													$selected = '';
												}
											?>
										<option value="<?php echo $post_type; ?>" <?php echo $selected; ?>><?php _e($post_type, 'carosuelfree')?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<!-- End Post Type -->

							<tr valign="top">
								<th scope="row">
									<label for="pgpro_name"><?php _e('Categories', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;" id="get_cate_area">
									<div class="bubblingG" style="display:none;">
										<span id="bubblingG_1">
										</span>
										<span id="bubblingG_2">
										</span>
										<span id="bubblingG_3">
										</span>
									</div>			
									<?php
										echo carpros_get_taxonomy_categories( get_the_ID() );
									?> 
								</td>
							</tr>
							<!-- End Categories -->	

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_styles"><?php _e('Carousel Styles', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_styles" id="carpro_slider_styles" class="timezone_string">
										<option value="1" <?php if ( isset ( $carpro_slider_styles ) ) selected( $carpro_slider_styles, '1' ); ?>><?php _e('Carousal Style 1', 'carosuelfree')?></option>
										<option disabled value="2" <?php if ( isset ( $carpro_slider_styles ) ) selected( $carpro_slider_styles, '2' ); ?>><?php _e('Carousal Style 2 (Only Pro)', 'carosuelfree')?></option>
										<option disabled value="3" <?php if ( isset ( $carpro_slider_styles ) ) selected( $carpro_slider_styles, '3' ); ?>><?php _e('Carousal Style 3 (Only Pro)', 'carosuelfree')?></option>
										<option disabled value="4" <?php if ( isset ( $carpro_slider_styles ) ) selected( $carpro_slider_styles, '4' ); ?>><?php _e('Carousal Style 4 (Only Pro)', 'carosuelfree')?></option>
										<option disabled value="5" <?php if ( isset ( $carpro_slider_styles ) ) selected( $carpro_slider_styles, '5' ); ?>><?php _e('Carousal Style 5 (Only Pro)', 'carosuelfree')?></option>
										<option disabled value="6" <?php if ( isset ( $carpro_slider_styles ) ) selected( $carpro_slider_styles, '6' ); ?>><?php _e('Carousal Style 6 (Only Pro)', 'carosuelfree')?></option>
										<option value="7" <?php if ( isset ( $carpro_slider_styles ) ) selected( $carpro_slider_styles, '7' ); ?>><?php _e('Post Style 1', 'carosuelfree')?></option>
										<option disabled value="8" <?php if ( isset ( $carpro_slider_styles ) ) selected( $carpro_slider_styles, '8' ); ?>><?php _e('Post Style 2 (Only Pro)', 'carosuelfree')?></option>
										<option disabled value="9" <?php if ( isset ( $carpro_slider_styles ) ) selected( $carpro_slider_styles, '9' ); ?>><?php _e('Post Style 3 (Only Pro)', 'carosuelfree')?></option>
										<option disabled value="10" <?php if ( isset ( $carpro_slider_styles ) ) selected( $carpro_slider_styles, '10' ); ?>><?php _e('Post Style 4 (Only Pro)', 'carosuelfree')?></option>
									</select>
								</td>
							</tr>
							<!-- End Carousel Styles -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_order_cat"><?php _e('Order By', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_order_cat" id="carpro_slider_order_cat" class="timezone_string">
										<option value="author" <?php if ( isset ( $carpro_slider_order_cat ) ) selected( $carpro_slider_order_cat, 'author' ); ?>><?php _e('Author', 'carosuelfree')?></option>
										<option value="post_date" <?php if ( isset ( $carpro_slider_order_cat ) ) selected( $carpro_slider_order_cat, 'post_date' ); ?>><?php _e('date', 'carosuelfree')?></option>
										<option value="title" <?php if ( isset ( $carpro_slider_order_cat ) ) selected( $carpro_slider_order_cat, 'title' ); ?>><?php _e('Title', 'carosuelfree')?></option>
										<option value="modified" <?php if ( isset ( $carpro_slider_order_cat ) ) selected( $carpro_slider_order_cat, 'modified' ); ?>><?php _e('Modified', 'carosuelfree')?></option>
										<option value="rand" <?php if ( isset ( $carpro_slider_order_cat ) ) selected( $carpro_slider_order_cat, 'rand' ); ?>><?php _e('Rand', 'carosuelfree')?></option>
									</select>
								</td>
							</tr>
							<!-- End Order By -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_order"><?php _e('Order', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_order" id="carpro_slider_order" class="timezone_string">
										<option value="DESC" <?php if ( isset ( $carpro_slider_order ) ) selected( $carpro_slider_order, 'DESC' ); ?>><?php _e('Descending', 'carosuelfree')?></option>
										<option value="ASC" <?php if ( isset ( $carpro_slider_order ) ) selected( $carpro_slider_order, 'ASC' ); ?>><?php _e('Ascending', 'carosuelfree')?></option>
									</select>
								</td>
							</tr>
							<!-- End Order -->
						</div>								
					</table>
				</div>
			</li>

			
			
			<li style="<?php if($nav_value == 3){echo "display: block;";} else{ echo "display: none;"; }?>" class="box3 tab-box <?php if($nav_value == 3){echo "active";}?>">

				<div class="wrap">
					<table class="form-table">
						<div class="option-box">
							<p class="option-title"><?php _e('Slider Settings','carosuelfree'); ?></p>
							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_autoplay"><?php _e('Autoplay', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_autoplay" id="carpro_slider_autoplay" class="timezone_string">
										<option value="true" <?php if ( isset ( $carpro_slider_autoplay ) ) selected( $carpro_slider_autoplay, 'true' ); ?>><?php _e('True', 'carosuelfree')?></option>
										<option value="false" <?php if ( isset ( $carpro_slider_autoplay ) ) selected( $carpro_slider_autoplay, 'false' ); ?>><?php _e('False', 'carosuelfree')?></option>
									</select>
								</td>
							</tr>
							<!-- End Autoplay -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_autoplay_speed"><?php _e('Slide Delay', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="text" name="carpro_slider_autoplay_speed" id="carpro_slider_autoplay_speed" maxlength="4" class="timezone_string" required value="<?php  if($carpro_slider_autoplay_speed !=''){echo $carpro_slider_autoplay_speed; }else{ echo '700';} ?>"> (Only Pro)							
								</td>
							</tr>
							<!-- End Slide Delay -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_stophover"><?php _e('Stop Hover', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_stophover" id="carpro_slider_stophover" class="timezone_string">
										<option value="true" <?php if ( isset ( $carpro_slider_stophover ) ) selected( $carpro_slider_stophover, 'true' ); ?>><?php _e('True', 'carosuelfree')?></option>	
										<option value="false" <?php if ( isset ( $carpro_slider_stophover ) ) selected( $carpro_slider_stophover, 'false' ); ?>><?php _e('False', 'carosuelfree')?></option>
									</select>							
								</td>
							</tr>
							<!-- End Stop Hover -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_autoplaytimeout"><?php _e('Autoplay Time Out (Sec)', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_autoplaytimeout" id="carpro_slider_autoplaytimeout" class="timezone_string">
										<option value="1000" <?php if ( isset ( $carpro_slider_autoplaytimeout ) ) selected( $carpro_slider_autoplaytimeout, '1000' ); ?>><?php _e('1', 'carosuelfree')?></option>
										<option disabled><?php _e('2 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('3 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('4 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('5 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('6 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('7 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('8 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('9 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('10 (Only Pro)', 'carosuelfree')?></option>
									</select>							
								</td>
							</tr>
							<!-- End Autoplay Time Out -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_items"><?php _e('Items No', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_items" id="carpro_slider_items" class="timezone_string">
										<option value="3" <?php if ( isset ( $carpro_slider_items ) )  selected( $carpro_slider_items, '3' ); ?>><?php _e('3', 'carosuelfree')?></option>
										<option value="1" <?php if ( isset ( $carpro_slider_items ) )  selected( $carpro_slider_items, '1' ); ?>><?php _e('1', 'carosuelfree')?></option>
										<option value="2" <?php if ( isset ( $carpro_slider_items ) )  selected( $carpro_slider_items, '2' ); ?>><?php _e('2', 'carosuelfree')?></option>
										<option disabled><?php _e('4 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('5 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('6 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('7 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('8 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('9 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('10 (Only Pro)', 'carosuelfree')?></option>
									</select>
								</td>
							</tr>
							<!-- End Items No -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_itemsdesktop"><?php _e('Items Desktop', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_itemsdesktop" id="carpro_slider_itemsdesktop" class="timezone_string">
										<option value="3" <?php if ( isset ( $carpro_slider_itemsdesktop ) ) selected( $carpro_slider_itemsdesktop, '3' ); ?>><?php _e('3', 'carosuelfree')?></option>
										<option disabled><?php _e('1 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('2 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('3 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('4 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('5 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('6 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('7 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('8 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('9 (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('10 (Only Pro)', 'carosuelfree')?></option>
									</select>
								</td>
							</tr>
							<!-- End Items Desktop -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_itemsdesktopsmall"><?php _e('Items Desktop Small', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_itemsdesktopsmall" id="carpro_slider_itemsdesktopsmall" class="timezone_string">
										<option value="1" <?php if ( isset ( $carpro_slider_itemsdesktopsmall ) ) selected( $carpro_slider_itemsdesktopsmall, '1' ); ?>><?php _e('1', 'carosuelfree')?></option>
										<option value="2" <?php if ( isset ( $carpro_slider_itemsdesktopsmall ) ) selected( $carpro_slider_itemsdesktopsmall, '2' ); ?>><?php _e('2', 'carosuelfree')?></option>
										<option value="3" <?php if ( isset ( $carpro_slider_itemsdesktopsmall ) ) selected( $carpro_slider_itemsdesktopsmall, '3' ); ?>><?php _e('3', 'carosuelfree')?></option>
										<option value="4" <?php if ( isset ( $carpro_slider_itemsdesktopsmall ) ) selected( $carpro_slider_itemsdesktopsmall, '4' ); ?>><?php _e('4', 'carosuelfree')?></option>
										<option value="5" <?php if ( isset ( $carpro_slider_itemsdesktopsmall ) ) selected( $carpro_slider_itemsdesktopsmall, '5' ); ?>><?php _e('5', 'carosuelfree')?></option>
										<option value="6" <?php if ( isset ( $carpro_slider_itemsdesktopsmall ) ) selected( $carpro_slider_itemsdesktopsmall, '6' ); ?>><?php _e('6', 'carosuelfree')?></option>
										<option value="7" <?php if ( isset ( $carpro_slider_itemsdesktopsmall ) ) selected( $carpro_slider_itemsdesktopsmall, '7' ); ?>><?php _e('7', 'carosuelfree')?></option>
										<option value="8" <?php if ( isset ( $carpro_slider_itemsdesktopsmall ) ) selected( $carpro_slider_itemsdesktopsmall, '8' ); ?>><?php _e('8', 'carosuelfree')?></option>
										<option value="9" <?php if ( isset ( $carpro_slider_itemsdesktopsmall ) ) selected( $carpro_slider_itemsdesktopsmall, '9' ); ?>><?php _e('9', 'carosuelfree')?></option>
										<option value="10" <?php if ( isset ( $carpro_slider_itemsdesktopsmall ) ) selected( $carpro_slider_itemsdesktopsmall, '10' ); ?>><?php _e('10', 'carosuelfree')?></option>
									</select>			
								</td>
							</tr>
							<!-- End Items Desktop Small -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_itemsmobile"><?php _e('Items Mobile', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_itemsmobile" id="carpro_slider_itemsmobile" class="timezone_string">
										<option value="1" <?php if ( isset ( $carpro_slider_itemsmobile ) ) selected( $carpro_slider_itemsmobile, '1' ); ?>><?php _e('1', 'carosuelfree')?></option>
										<option value="2" <?php if ( isset ( $carpro_slider_itemsmobile ) ) selected( $carpro_slider_itemsmobile, '2' ); ?>><?php _e('2', 'carosuelfree')?></option>
										<option value="3" <?php if ( isset ( $carpro_slider_itemsmobile ) ) selected( $carpro_slider_itemsmobile, '3' ); ?>><?php _e('3', 'carosuelfree')?></option>
										<option value="4" <?php if ( isset ( $carpro_slider_itemsmobile ) ) selected( $carpro_slider_itemsmobile, '4' ); ?>><?php _e('4', 'carosuelfree')?></option>
										<option value="5" <?php if ( isset ( $carpro_slider_itemsmobile ) ) selected( $carpro_slider_itemsmobile, '5' ); ?>><?php _e('5', 'carosuelfree')?></option>
										<option value="6" <?php if ( isset ( $carpro_slider_itemsmobile ) ) selected( $carpro_slider_itemsmobile, '6' ); ?>><?php _e('6', 'carosuelfree')?></option>
										<option value="7" <?php if ( isset ( $carpro_slider_itemsmobile ) ) selected( $carpro_slider_itemsmobile, '7' ); ?>><?php _e('7', 'carosuelfree')?></option>
										<option value="8" <?php if ( isset ( $carpro_slider_itemsmobile ) ) selected( $carpro_slider_itemsmobile, '8' ); ?>><?php _e('8', 'carosuelfree')?></option>
										<option value="9" <?php if ( isset ( $carpro_slider_itemsmobile ) ) selected( $carpro_slider_itemsmobile, '9' ); ?>><?php _e('9', 'carosuelfree')?></option>
										<option value="10" <?php if ( isset ( $carpro_slider_itemsmobile ) ) selected( $carpro_slider_itemsmobile, '10' ); ?>><?php _e('10', 'carosuelfree')?></option>
									</select>
								</td>
							</tr>
							<!-- End Items Mobile -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_loop"><?php _e('Loop', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_loop" id="carpro_slider_loop" class="timezone_string">
										<option disabled value="true" <?php if ( isset ( $carpro_slider_loop ) ) selected( $carpro_slider_loop, 'true' ); ?>><?php _e('True', 'carosuelfree')?></option>
										<option value="false" <?php if ( isset ( $carpro_slider_loop ) ) selected( $carpro_slider_loop, 'false' ); ?>><?php _e('False', 'carosuelfree')?></option>
									</select>
								</td>
							</tr>
							<!-- End Loop -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_margin"><?php _e('Margin', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="number" name="carpro_slider_margin" id="carpro_slider_margin" maxlength="3" class="timezone_string" value="<?php if($carpro_slider_margin !=''){echo $carpro_slider_margin;} else{ echo '0'; } ?>" value="0">
								</td>
							</tr>
							<!-- End Margin -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_navigation"><?php _e('Navigation', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_navigation" id="carpro_slider_navigation" class="timezone_string">
										<option value="true" <?php if ( isset ( $carpro_slider_navigation ) ) selected( $carpro_slider_navigation, 'true' ); ?>><?php _e('True', 'carosuelfree')?></option>
										<option value="false" <?php if ( isset ( $carpro_slider_navigation ) ) selected( $carpro_slider_navigation, 'false' ); ?>><?php _e('False', 'carosuelfree')?></option>
									</select>
								</td>
							</tr>
							<!-- End Navigation -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_navigation_position"><?php _e('Navigation Position', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_navigation_position" id="carpro_slider_navigation_position" class="timezone_string">
										<option value="1" <?php if ( isset ( $carpro_slider_navigation_position ) ) selected( $carpro_slider_navigation_position, '1' ); ?>><?php _e('Top Right', 'carosuelfree')?></option>
										<option disabled><?php _e('Top Left (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('Bottom Center (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('Bottom Left (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('Bottom Right (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('Centred (Only Pro)', 'carosuelfree')?></option>
									</select>
								</td>
							</tr>
							<!-- End Navigation Position -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_navtext_color"><?php _e('Navigation Color', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="text" name="carpro_slider_navtext_color" value="<?php if($carpro_slider_navtext_color !=''){echo $carpro_slider_navtext_color;} else{ echo "#A28352";} ?>" class="jscolor" readonly>
								</td>
							</tr>
							<!-- End Navigation Color -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_navtext_hovercolor"><?php _e('Navigation Hover Color', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="text" name="carpro_slider_navtext_hovercolor" value="<?php if($carpro_slider_navtext_hovercolor !=''){echo $carpro_slider_navtext_hovercolor;} else{ echo "#A28352";} ?>" class="jscolor" readonly>
								</td>
							</tr>
							<!-- End Navigation Hover Color -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_navbg_color"><?php _e('Navigation Background Color', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="text" name="carpro_slider_navbg_color" value="<?php if($carpro_slider_navbg_color !=''){echo $carpro_slider_navbg_color;} else{ echo "#DBEAF7";} ?>" class="jscolor" readonly>
								</td>
							</tr>
							<!-- End Navigation Background Color -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_navbg_hovercolor"><?php _e('Navigation Hover Background Color', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="text" name="carpro_slider_navbg_hovercolor" value="<?php if($carpro_slider_navbg_hovercolor !=''){echo $carpro_slider_navbg_hovercolor;} else{ echo "#DBEAF7";} ?>" class="jscolor" readonly>
								</td>
							</tr>
							<!-- End Navigation Hover Background Color -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_pagination"><?php _e('Pagination', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_pagination" id="carpro_slider_pagination" class="timezone_string">
										<option value="true" <?php if ( isset ( $carpro_slider_pagination ) ) selected( $carpro_slider_pagination, 'true' ); ?>><?php _e('True', 'carosuelfree')?></option>
										<option value="false" <?php if ( isset ( $carpro_slider_pagination ) ) selected( $carpro_slider_pagination, 'false' ); ?>><?php _e('False', 'carosuelfree')?></option>
									</select>							
								</td>
							</tr>
							<!-- End Pagination -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_pagination_color"><?php _e('Pagination Color', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="text" name="carpro_slider_pagination_color" value="<?php if($carpro_slider_pagination_color !=''){echo $carpro_slider_pagination_color;} else{ echo "#f001f0";} ?>" class="jscolor" readonly>
								</td>
							</tr>
							<!-- End Pagination Color -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_pagination_bgcolor"><?php _e('Pagination Background Color', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="text" name="carpro_slider_pagination_bgcolor" value="<?php if($carpro_slider_pagination_bgcolor !=''){echo $carpro_slider_pagination_bgcolor;} else{ echo "#7A4B94";} ?>" class="jscolor" readonly>
								</td>
							</tr>
							<!-- End Pagination Background Color -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_pagination_style"><?php _e('Pagination Style', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_pagination_style" id="carpro_slider_pagination_style" class="timezone_string">
										<option value="1" <?php if ( isset ( $carpro_slider_pagination_style ) ) selected( $carpro_slider_pagination_style, '1' ); ?>><?php _e('Round', 'carosuelfree')?></option>
										<option disabled><?php _e('Square (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('Star (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('Line (Only Pro)', 'carosuelfree')?></option>
									</select>
								</td>
							</tr>
							<!-- End Pagination Position -->

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_paginationposition"><?php _e('Pagination Position', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_paginationposition" id="carpro_slider_paginationposition" class="timezone_string">
										<option value="center" <?php if ( isset ( $carpro_slider_paginationposition ) ) selected( $carpro_slider_paginationposition, 'center' ); ?>><?php _e('Center', 'carosuelfree')?></option>
										<option disabled><?php _e('Left (Only Pro)', 'carosuelfree')?></option>
										<option disabled><?php _e('Right (Only Pro)', 'carosuelfree')?></option>
									</select>
								</td>
							</tr>
							<!-- End Pagination Position -->
						</div>
					</table>
				</div>
			</li>
			
			
			
			<!-- Tab 4  -->
			<li style="<?php if($nav_value == 4){echo "display: block;";} else{ echo "display: none;"; }?>" class="box4 tab-box <?php if($nav_value == 4){echo "active";}?>">

				<div class="wrap">
					<table class="form-table">
						<div class="option-box">
							<p class="option-title"><?php _e('General Settings','carosuelfree'); ?></p>
							
							<tr valign="top" id="bgcontroller">
								<th scope="row">
									<label for="carprobg_color"><?php _e('Background Color', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="text" class="color-picker" data-alpha="true" data-default-color="#ddd" name="carprobg_color" value="<?php echo $carprobg_color?>"/>
								</td>
							</tr>				
							<!-- End Background Color -->
							
							

							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_hide_img"><?php _e('Image', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_hide_img" id="carpro_slider_hide_img" class="timezone_string">
										<option value="1" <?php if ( isset ( $carpro_slider_hide_img ) ) selected( $carpro_slider_hide_img, '1' ); ?>><?php _e('Show', 'carosuelfree')?></option>
										<option value="2" <?php if ( isset ( $carpro_slider_hide_img ) ) selected( $carpro_slider_hide_img, '2' ); ?>><?php _e('Hide', 'carosuelfree')?></option>
									</select>
								</td>
							</tr>
							<!-- End Image Show/Hide -->
							
							<tr valign="top" id="img_controller2" style="<?php if($carpro_slider_hide_img == 2){	echo "display:none;"; }?>">
								<th scope="row">
									<label for="carpro_slider_img_height"><?php _e('Image Height (px)', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="text" name="carpro_slider_img_height" id="carpro_slider_img_height" maxlength="4" class="timezone_string" required value="<?php  if($carpro_slider_img_height !=''){echo $carpro_slider_img_height; }else{ echo '250';} ?>">
								</td>
							</tr>		
							<!-- End Image Size -->
							
							
							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_hide_img_captions"><?php _e('Image Caption', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_hide_img_captions" id="carpro_slider_hide_img_captions" class="timezone_string">
										<option value="1" <?php if ( isset ( $carpro_slider_hide_img_captions ) ) selected( $carpro_slider_hide_img_captions, '1' ); ?>><?php _e('Show', 'carosuelfree')?></option>
										<option value="2" <?php if ( isset ( $carpro_slider_hide_img_captions ) ) selected( $carpro_slider_hide_img_captions, '2' ); ?>><?php _e('Hide', 'carosuelfree')?></option>
									</select>
								</td>
							</tr>
							<!-- End Image Show/Hide Caption -->
							
							<tr valign="top" id="caphidden">
								<th scope="row">
									<label for="carpro_slider_capbg_color"><?php _e('Caption Background Color', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="text" class="color-picker" data-alpha="true" data-default-color="#f1f1f1" name="carpro_slider_capbg_color" value="<?php echo $carpro_slider_capbg_color?>" readonly />
								</td>
							</tr>				
							<!-- End Caption Background Color -->
							
							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_captext_color"><?php _e('Caption Text Color', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="text" class="color-picker" data-alpha="true" data-default-color="#000" name="carpro_slider_captext_color" value="<?php echo $carpro_slider_captext_color?>" readonly />
								</td>
							</tr>				
							<!-- End Caption Background Color -->
							
							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_captext_size"><?php _e('Caption Font Size', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_captext_size" id="carpro_slider_captext_size">
										<?php for($i=12; $i<=72; $i++){?>
										<option value="<?php echo $i; ?>" <?php if ( isset ( $carpro_slider_captext_size ) ) selected( $carpro_slider_captext_size, $i ); ?>><?php echo $i."px";?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<!-- End Caption Font Size-->
							
							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_captext_align"><?php _e('Caption Alignment', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_captext_align" id="carpro_slider_captext_align" class="timezone_string">
										<option value="center" <?php if ( isset ( $carpro_slider_captext_align ) ) selected( $carpro_slider_captext_align, 'center' ); ?>><?php _e('Center', 'carosuelfree')?></option>
										<option disabled><?php _e('Left', 'carosuelfree')?></option>
										<option disabled><?php _e('Right', 'carosuelfree')?></option>
									</select>
								</td>
							</tr>
							<!-- End Caption Text Alignment-->
							
							<tr valign="top">
								<th scope="row">
									<label for="carpro_slider_hide_link"><?php _e('Permalink', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_slider_hide_link" id="carpro_slider_hide_link" class="timezone_string">
										<option value="1" <?php if ( isset ( $carpro_slider_hide_link ) ) selected( $carpro_slider_hide_link, '1' ); ?>><?php _e('Show', 'carosuelfree')?></option>
										<option value="2" <?php if ( isset ( $carpro_slider_hide_link ) ) selected( $carpro_slider_hide_link, '2' ); ?>><?php _e('Hide', 'carosuelfree')?></option>
									</select>
								</td>
							</tr>
							<!-- End Show/Hide Permalink -->
							
							<tr valign="top" id="datetimes">
								<th scope="row">
									<label for="carpro_datetime_hide"><?php _e('Date & Time', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_datetime_hide" id="carpro_datetime_hide" class="timezone_string">
										<option value="1" <?php if ( isset ( $carpro_datetime_hide ) ) selected( $carpro_datetime_hide, '1' ); ?>><?php _e('Show', 'carosuelfree')?></option>
										<option value="2" <?php if ( isset ( $carpro_datetime_hide ) ) selected( $carpro_datetime_hide, '2' ); ?>><?php _e('Hide', 'carosuelfree')?></option>
									</select>
								</td>
							</tr>
							<!-- End Date & Time Show/Hide -->
							
							
							<tr valign="top" id="datealign">
								<th scope="row">
									<label for="carpro_date_align"><?php _e('Date Text Alignment', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_date_align" id="carpro_date_align" class="timezone_string">
										<option value="center" <?php if ( isset ( $carpro_date_align ) ) selected( $carpro_date_align, 'center' ); ?>><?php _e('Center', 'carosuelfree')?></option>
										<option disabled><?php _e('Left', 'carosuelfree')?></option>
										<option disabled><?php _e('Right', 'carosuelfree')?></option>
									</select>
								</td>
							</tr>
							<!-- End Date Text Alignment-->
							
							
							<tr valign="top" id="datefsize">
								<th scope="row">
									<label for="carpro_dattext_size"><?php _e('Date Font Size', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_dattext_size" id="carpro_dattext_size">
										<?php for($i=12; $i<=72; $i++){?>
										<option value="<?php echo $i; ?>" <?php if ( isset ( $carpro_dattext_size ) ) selected( $carpro_dattext_size, $i ); ?>><?php echo $i."px";?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<!-- End Content Font Size-->
							
							<tr valign="top" id="datefcolors">
								<th scope="row">
									<label for="carpro_datetclr"><?php _e('Date Text Color', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input name="carpro_datetclr" value="<?php if($carpro_datetclr !=''){echo $carpro_datetclr;} else{ echo "#fff";} ?>" class="jscolor" />
								</td>
							</tr>				
							<!-- End Content Font Color -->

							<tr valign="top" id="conalign">
								<th scope="row">
									<label for="carpro_con_align"><?php _e('Contetn Text Alignment', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_con_align" id="carpro_con_align" class="timezone_string">
										<option value="center" <?php if ( isset ( $carpro_con_align ) ) selected( $carpro_con_align, 'center' ); ?>><?php _e('Center', 'carosuelfree')?></option>
										<option disabled><?php _e('Left', 'carosuelfree')?></option>
										<option disabled><?php _e('Right', 'carosuelfree')?></option>
									</select>
								</td>
							</tr>
							<!-- End Content Text Alignment-->
							
							<tr valign="top" id="content_textsize">
								<th scope="row">
									<label for="carpro_context_size"><?php _e('Content Font Size', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<select name="carpro_context_size" id="carpro_context_size">
										<?php for($i=12; $i<=72; $i++){?>
										<option value="<?php echo $i; ?>" <?php if ( isset ( $carpro_context_size ) ) selected( $carpro_context_size, $i ); ?>><?php echo $i."px";?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<!-- End Content Font Size-->
							
							<tr valign="top" id="conhide">
								<th scope="row">
									<label for="carpro_contentclr"><?php _e('Content Text Color', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input name="carpro_contentclr" value="<?php if($carpro_contentclr !=''){echo $carpro_contentclr;} else{ echo "#000";} ?>" class="jscolor" />
								</td>
							</tr>				
							<!-- End Content Font Color -->
							
							

							<tr valign="top" id="hide1">
								<th scope="row">
									<label for="carpro_slider_overlay_color"><?php _e('Overlay Color', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="text" class="color-picker" data-alpha="true" data-default-color="rgba(0,0,0,0.6)" name="carpro_slider_overlay_color" value="<?php echo $carpro_slider_overlay_color?>" readonly />
								</td>
							</tr>				
							<!-- End Overlay Color -->

							<tr valign="top" id="hide2">
								<th scope="row">
									<label for="carpro_slider_overlayicons_color"><?php _e('Icon Background Color', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="text" class="color-picker" data-alpha="true" data-default-color="#fff" name="carpro_slider_overlayicons_color" value="<?php echo $carpro_slider_overlayicons_color?>" readonly />
								</td>
							</tr>				
							<!-- End Overlay Icons Background Color -->

							<tr valign="top" id="hide3">
								<th scope="row">
									<label for="carpro_slider_overlaycolor_icons"><?php _e('Icons Color', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="text" class="color-picker" data-alpha="true" data-default-color="#000" name="carpro_slider_overlaycolor_icons" value="<?php echo $carpro_slider_overlaycolor_icons?>" readonly />
								</td>
							</tr>				
							<!-- End Overlay Icons Color -->

							<tr valign="top" id="hide4">
								<th scope="row">
									<label for="carpro_slider_border_icons"><?php _e('Border Color', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="text" class="color-picker" data-alpha="true" data-default-color="#ddd" name="carpro_slider_border_icons" value="<?php echo $carpro_slider_border_icons?>" readonly />
								</td>
							</tr>				
							<!-- End Overlay Icons Color -->
							
							
							<tr valign="top" id="cp_ex_length_area">
								<th scope="row">
									<label for="excerpt_lenght"><?php _e('Excerpt Length in Characters', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input type="number" name="excerpt_lenght"  id="excerpt_lenght" maxlength="3" class="timezone_string" value="<?php echo $excerpt_lenght; ?>" >

									<input type="text" name="btn_readmore" id="btn_readmore" maxlength="20" class="timezone_string" value="<?php echo $btn_readmore; ?>" >
								</td>
							</tr>
							<!-- End Excerpt Length -->

							<tr valign="top" id="hide5">
								<th scope="row">
									<label for="carpro_slider_excerpt_color"><?php _e('Excerpt Font Color', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input name="carpro_slider_excerpt_color" value="<?php if($carpro_slider_excerpt_color !=''){echo $carpro_slider_excerpt_color;} else{ echo "#000";} ?>" class="jscolor" />
								</td>
							</tr>
							<!-- End Excerpt Color -->

							<tr valign="top" id="hide6">
								<th scope="row">
									<label for="carpro_excerptbg_color"><?php _e('Excerpt Background Color', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<input name="carpro_excerptbg_color" value="<?php if($carpro_excerptbg_color !=''){echo $carpro_excerptbg_color;} else{ echo "#7862D4";} ?>" class="jscolor" />
								</td>
							</tr>
							<!-- End Excerpt Color -->
						</div>								
					</table>
				</div>
			</li>			
			
			<!-- Tab 5  -->
			<li style="<?php if($nav_value == 5){echo "display: block;";} else{ echo "display: none;"; }?>" class="box5 tab-box <?php if($nav_value == 5){echo "active";}?>">			
				<div class="wrap">
					<table class="form-table">
						<div class="option-box">
							<p class="option-title"><?php _e('Help & Support','carosuelfree'); ?></p>
							<tr valign="top">
								<th scope="row">
									<label for="carpro_support"><?php _e('Need Support', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									<?php _e('Do you have questions or issues with Carousel', 'carosuelfree')?> <a target="_blank" href="https://themepoints.com/questions-answer/">Ask for support here</a>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="carpro_support"><?php _e('Happy User', 'carosuelfree')?></label>
								</th>
								<td style="vertical-align: middle;">
									We spend plenty of time to develop a plugin like this. If you happy to using this plugin, please <a href="https://wordpress.org/plugins/carousel" target="_blank">rate it 5 stars</a>. If you have any problems with the plugin, please let us know before leaving a review.
								</td>
							</tr>


						</div>
					</table>
				</div>
			</li>
			
		</ul>
	</div>
<?php }   //


	function carpro_details_settings_function($post, $args){

		#Call get post meta.
		$carpro_slider_ulr_input        = get_post_meta($post->ID, 'company_website_input', true);
		$carpro_slider_ulr_target       = get_post_meta($post->ID, 'carpro_slider_ulr_target', true);
		?>

		<div class="wrap">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="company_website_input"><?php _e('Website URL', 'carosuelfree')?></label>
					</th>
					<td style="vertical-align: middle;">
						<input type="text" name="company_website_input" id="company_website_input" class="regular-text code" value="<?php echo get_post_meta($post->ID, 'any_web_links', true); ?>" />
					</td>
				</tr>
				<!-- End Navigation Background Color -->

				<tr valign="top">
					<th scope="row">
						<label for="carpro_slider_ulr_target"><?php _e('Target URL', 'carosuelfree')?></label>
					</th>
					<td style="vertical-align: middle;">
						<select name="carpro_slider_ulr_target" id="carpro_slider_ulr_target" class="timezone_string">
							<option value="_self" <?php if ( isset ( $carpro_slider_ulr_target ) ) selected( $carpro_slider_ulr_target, '_self' ); ?>><?php _e('Open Link Same Page', 'carosuelfree')?></option>
							<option value="_blank" <?php if ( isset ( $carpro_slider_ulr_target ) ) selected( $carpro_slider_ulr_target, '_blank' ); ?>><?php _e('Open Link New Page', 'carosuelfree')?></option>
						</select>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}
	
# Data save in custom metabox field
function meta_box_save_func($post_id){

	# Doing autosave then return.
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;

	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_slider_postoptions' ] ) ) {
	    update_post_meta( $post_id, 'carpro_slider_postoptions', $_POST['carpro_slider_postoptions'] );
	}

	#Checks for input and saves if needed
	if( isset( $_POST[ 'company_website_input' ] ) ) {
	    update_post_meta( $post_id, 'any_web_links', $_POST['company_website_input'] );
	}
	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_slider_ulr_target' ] ) ) {
	    update_post_meta( $post_id, 'carpro_slider_ulr_target', $_POST['carpro_slider_ulr_target'] );
	}

	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_slider_styles' ] ) ) {
	    update_post_meta( $post_id, 'carpro_slider_styles', $_POST['carpro_slider_styles'] );
	}

	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_slider_order_cat' ] ) ) {
	    update_post_meta( $post_id, 'carpro_slider_order_cat', $_POST[ 'carpro_slider_order_cat' ] );
	}

	#Checks for input and saves if needed
	if( isset( $_POST[ 'carprobg_color' ] ) ) {
	    update_post_meta( $post_id, 'carprobg_color', $_POST[ 'carprobg_color' ] );
	}

	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_slider_order' ] ) ) {
	    update_post_meta( $post_id, 'carpro_slider_order', $_POST[ 'carpro_slider_order' ] );
	}

	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_slider_hide_img' ] ) ) {
	    update_post_meta( $post_id, 'carpro_slider_hide_img', $_POST[ 'carpro_slider_hide_img' ] );
	}
	
	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_slider_hide_img_captions' ] ) ) {
	    update_post_meta( $post_id, 'carpro_slider_hide_img_captions', $_POST[ 'carpro_slider_hide_img_captions' ] );
	}
	
	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_slider_img_height' ] ) ) {
	    update_post_meta( $post_id, 'carpro_slider_img_height', $_POST[ 'carpro_slider_img_height' ] );
	}
	
	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_slider_border_icons' ] ) ) {
	    update_post_meta( $post_id, 'carpro_slider_border_icons', $_POST[ 'carpro_slider_border_icons' ] );
	}

	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_slider_capbg_color' ] ) ) {
	    update_post_meta( $post_id, 'carpro_slider_capbg_color', $_POST[ 'carpro_slider_capbg_color' ] );
	}

	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_slider_captext_color' ] ) ) {
	    update_post_meta( $post_id, 'carpro_slider_captext_color', $_POST[ 'carpro_slider_captext_color' ] );
	}

	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_slider_hide_link' ] ) ) {
	    update_post_meta( $post_id, 'carpro_slider_hide_link', $_POST[ 'carpro_slider_hide_link' ] );
	}

	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_contentclr' ] ) ) {
	    update_post_meta( $post_id, 'carpro_contentclr', $_POST[ 'carpro_contentclr' ] );
	}

	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_datetime_hide' ] ) ) {
	    update_post_meta( $post_id, 'carpro_datetime_hide', $_POST[ 'carpro_datetime_hide' ] );
	}

	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_date_align' ] ) ) {
	    update_post_meta( $post_id, 'carpro_date_align', $_POST[ 'carpro_date_align' ] );
	}

	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_datetclr' ] ) ) {
	    update_post_meta( $post_id, 'carpro_datetclr', $_POST[ 'carpro_datetclr' ] );
	}
	
	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_dattext_size' ] ) ) {
	    update_post_meta( $post_id, 'carpro_dattext_size', $_POST[ 'carpro_dattext_size' ] );
	}
	
	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_con_align' ] ) ) {
	    update_post_meta( $post_id, 'carpro_con_align', $_POST[ 'carpro_con_align' ] );
	}
	
	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_context_size' ] ) ) {
	    update_post_meta( $post_id, 'carpro_context_size', $_POST[ 'carpro_context_size' ] );
	}
	
	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_slider_captext_align' ] ) ) {
	    update_post_meta( $post_id, 'carpro_slider_captext_align', $_POST[ 'carpro_slider_captext_align' ] );
	}
	
	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_slider_captext_size' ] ) ) {
	    update_post_meta( $post_id, 'carpro_slider_captext_size', $_POST[ 'carpro_slider_captext_size' ] );
	}

	#Checks for input and saves
	if( isset( $_POST[ 'carpro_slider_overlay_color' ] ) ) {
	    update_post_meta( $post_id, 'carpro_slider_overlay_color', esc_html($_POST['carpro_slider_overlay_color']) );
	}

	#Checks for input and saves
	if( isset( $_POST[ 'carpro_slider_overlayicons_color' ] ) ) {
	    update_post_meta( $post_id, 'carpro_slider_overlayicons_color', esc_html($_POST['carpro_slider_overlayicons_color']) );
	}

	#Checks for input and saves
	if( isset( $_POST[ 'carpro_slider_overlaycolor_icons' ] ) ) {
	    update_post_meta( $post_id, 'carpro_slider_overlaycolor_icons', esc_html($_POST['carpro_slider_overlaycolor_icons']) );
	}
	
	
	#Checks for input and sanitizes/saves if needed
    if( isset($_POST['excerpt_lenght']) && ($_POST['excerpt_lenght'] != '')  && ($_POST['excerpt_lenght'] != '0') && (strlen($_POST['excerpt_lenght']) <= 3)) {
        update_post_meta( $post_id, 'excerpt_lenght', esc_html($_POST['excerpt_lenght']) );
    } else{
    	update_post_meta( $post_id, 'excerpt_lenght', 100 );
    }

	#Checks for input and sanitizes/saves if needed
    if( isset( $_POST['btn_readmore'] ) && ( $_POST['btn_readmore'] != '') && ( strlen($_POST['btn_readmore']) <= 20) ) {
        update_post_meta( $post_id, 'btn_readmore', esc_html($_POST['btn_readmore']) );
    } else{
        update_post_meta( $post_id, 'btn_readmore', 'Read More' );

    }
	
	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_slider_excerpt_color' ] ) ) {
	    update_post_meta( $post_id, 'carpro_slider_excerpt_color', $_POST[ 'carpro_slider_excerpt_color' ] );
	}
	
	#Checks for input and saves if needed
	if( isset( $_POST[ 'carpro_excerptbg_color' ] ) ) {
	    update_post_meta( $post_id, 'carpro_excerptbg_color', $_POST[ 'carpro_excerptbg_color' ] );
	}


    // Carousal Settings

	#Checks for input and sanitizes/saves if needed
    if( isset($_POST['carpro_slider_items']) && ($_POST['carpro_slider_items'] != '') ) {
        update_post_meta( $post_id, 'carpro_slider_items', esc_html($_POST['carpro_slider_items']) );
    }

 	#Checks for input and sanitizes/saves if needed    
    if( isset($_POST['carpro_slider_loop']) && ($_POST['carpro_slider_loop'] != '') ) {
        update_post_meta( $post_id, 'carpro_slider_loop', esc_html($_POST['carpro_slider_loop']) );
    }

 	#Checks for input and sanitizes/saves if needed    
    if( isset( $_POST['carpro_slider_margin'] ) ) {
    	if(strlen($_POST['carpro_slider_margin']) > 2){     // input value length greate than 2 
    	} else{
	    	if( $_POST['carpro_slider_margin'] == '' || $_POST['carpro_slider_margin'] == is_null($_POST['carpro_slider_margin']) ){
	    		update_post_meta( $post_id, 'carpro_slider_margin', 0 );
	    	}
	    	else{
	    		if (is_numeric($_POST['carpro_slider_margin'])) {
	    			update_post_meta( $post_id, 'carpro_slider_margin', esc_html($_POST['carpro_slider_margin']) );
	    		}
	    	}
    	}
    }

 	#Checks for input and sanitizes/saves if needed    
    if( isset($_POST['carpro_slider_navigation']) && ($_POST['carpro_slider_navigation'] != '') ) {
        update_post_meta( $post_id, 'carpro_slider_navigation', esc_html($_POST['carpro_slider_navigation']) );
    }

 	#Checks for input and sanitizes/saves if needed    
    if( isset($_POST['carpro_slider_navigation_position']) && ($_POST['carpro_slider_navigation_position'] != '') ) {
        update_post_meta( $post_id, 'carpro_slider_navigation_position', esc_html($_POST['carpro_slider_navigation_position']) );
    }

 	#Checks for input and sanitizes/saves if needed    
    if( isset($_POST['carpro_slider_paginationposition']) && ($_POST['carpro_slider_paginationposition'] != '') ) {
        update_post_meta( $post_id, 'carpro_slider_paginationposition', esc_html($_POST['carpro_slider_paginationposition']) );
    }

 	#Checks for input and sanitizes/saves if needed    
    if( isset($_POST['carpro_slider_pagination']) && ($_POST['carpro_slider_pagination'] != '') ) {
        update_post_meta( $post_id, 'carpro_slider_pagination', esc_html($_POST['carpro_slider_pagination']) );
    }  

 	#Checks for input and sanitizes/saves if needed    
    if( isset($_POST['carpro_slider_pagination_color']) && ($_POST['carpro_slider_pagination_color'] != '') ) {
        update_post_meta( $post_id, 'carpro_slider_pagination_color', esc_html($_POST['carpro_slider_pagination_color']) );
    }

 	#Checks for input and sanitizes/saves if needed    
    if( isset($_POST['carpro_slider_pagination_bgcolor']) && ($_POST['carpro_slider_pagination_bgcolor'] != '') ) {
        update_post_meta( $post_id, 'carpro_slider_pagination_bgcolor', esc_html($_POST['carpro_slider_pagination_bgcolor']) );
    }

 	#Checks for input and sanitizes/saves if needed    
    if( isset($_POST['carpro_slider_pagination_style']) && ($_POST['carpro_slider_pagination_style'] != '') ) {
        update_post_meta( $post_id, 'carpro_slider_pagination_style', esc_html($_POST['carpro_slider_pagination_style']) );
    }

 	#Checks for input and sanitizes/saves if needed    
    if( isset($_POST['carpro_slider_autoplay']) && ($_POST['carpro_slider_autoplay'] != '') ) {
        update_post_meta( $post_id, 'carpro_slider_autoplay', esc_html($_POST['carpro_slider_autoplay']) );
    }

 	#Checks for input and sanitizes/saves if needed    
    if( isset( $_POST['carpro_slider_autoplay_speed'] ) ) {
    	if(strlen($_POST['carpro_slider_autoplay_speed']) > 4 ){
    	} else{
    		if($_POST['carpro_slider_autoplay_speed'] == '' || is_null($_POST['carpro_slider_autoplay_speed'])){
    			update_post_meta( $post_id, 'carpro_slider_autoplay_speed', 700 );
    		}
    		else{
	    		if (is_numeric($_POST['carpro_slider_margin']) && strlen($_POST['carpro_slider_autoplay_speed']) <= 4) {
	    			update_post_meta( $post_id, 'carpro_slider_autoplay_speed', esc_html($_POST['carpro_slider_autoplay_speed']) );
	    		}
    		}
    	}
    }
	
	#Value check and saves if needed
	if( isset( $_POST[ 'nav_value' ] ) ) {
		update_post_meta( $post_id, 'nav_value', $_POST['nav_value'] );
	} else {
		update_post_meta( $post_id, 'nav_value', 1 );
	}

 	#Checks for input and sanitizes/saves if needed    
    if( isset($_POST['carpro_slider_stophover']) && ($_POST['carpro_slider_stophover'] != '') ) {
        update_post_meta( $post_id, 'carpro_slider_stophover', esc_html($_POST['carpro_slider_stophover']) );
    }

 	#Checks for input and sanitizes/saves if needed    
    if( isset($_POST['carpro_slider_itemsdesktop']) && ($_POST['carpro_slider_itemsdesktop'] != '') ) {
        update_post_meta( $post_id, 'carpro_slider_itemsdesktop', esc_html($_POST['carpro_slider_itemsdesktop']) );
    }

 	#Checks for input and sanitizes/saves if needed    
    if( isset($_POST['carpro_slider_itemsdesktopsmall']) && ($_POST['carpro_slider_itemsdesktopsmall'] != '') ) {
        update_post_meta( $post_id, 'carpro_slider_itemsdesktopsmall', esc_html($_POST['carpro_slider_itemsdesktopsmall']) );
    }

 	#Checks for input and sanitizes/saves if needed    
    if( isset($_POST['carpro_slider_itemsmobile']) && ($_POST['carpro_slider_itemsmobile'] != '') ) {
        update_post_meta( $post_id, 'carpro_slider_itemsmobile', esc_html($_POST['carpro_slider_itemsmobile']) );
    }

 	#Checks for input and sanitizes/saves if needed    
    if( isset($_POST['carpro_slider_autoplaytimeout']) && ($_POST['carpro_slider_autoplaytimeout'] != '') ) {
        update_post_meta( $post_id, 'carpro_slider_autoplaytimeout', esc_html($_POST['carpro_slider_autoplaytimeout']) );
    }

 	#Checks for input and sanitizes/saves if needed    
    if( isset($_POST['carpro_slider_navtext_color']) && ($_POST['carpro_slider_navtext_color'] != '') ) {
        update_post_meta( $post_id, 'carpro_slider_navtext_color', esc_html($_POST['carpro_slider_navtext_color']) );
    }

 	#Checks for input and sanitizes/saves if needed    
    if( isset($_POST['carpro_slider_navtext_hovercolor']) && ($_POST['carpro_slider_navtext_hovercolor'] != '') ) {
        update_post_meta( $post_id, 'carpro_slider_navtext_hovercolor', esc_html($_POST['carpro_slider_navtext_hovercolor']) );
    }

 	#Checks for input and sanitizes/saves if needed    
    if( isset($_POST['carpro_slider_navbg_color']) && ($_POST['carpro_slider_navbg_color'] != '') ) {
        update_post_meta( $post_id, 'carpro_slider_navbg_color', esc_html($_POST['carpro_slider_navbg_color']) );
    }

 	#Checks for input and sanitizes/saves if needed    
    if( isset($_POST['carpro_slider_navbg_hovercolor']) && ($_POST['carpro_slider_navbg_hovercolor'] != '') ) {
        update_post_meta( $post_id, 'carpro_slider_navbg_hovercolor', esc_html($_POST['carpro_slider_navbg_hovercolor']) );
    }

}
add_action( 'save_post', 'meta_box_save_func' );
# Custom metabox field end