<?php

if( !defined( 'ABSPATH' ) ){
    exit;
}

function pic_logoshowcasepro_metaboxes_register() {
    add_meta_box( 
        'pic_logoshowcasepro_meta_id',                              # Metabox
        __( 'Logo Showcase All Settings', 'logo-showcase-free' ),   # Title
        'pic_logoshowcasepro_metaboxes_reg',                        # Call Back func
        'piklogoshowcase',                                          # post type
        'normal'                                                    # Text Content
    );
    add_meta_box(
        'pic_logoshowcasepro_meta_id1',                             # Metabox
        __( 'Logo Showcase Shortcode', 'logo-showcase-free' ),      # Title
        'pe_logoshowcase_display_shortcode',                        # $callback
        'piklogoshowcase',                                          # $page
        'side'
    );
    add_meta_box(
        'pic_logoshowcasepro_meta_id2',                             # Metabox
        __( 'Need Support', 'logo-showcase-free' ),                 # Title
        'pe_logoshowcase_display_raigngs',                          # $callback
        'piklogoshowcase',                                          # $page
        'side'
    );
}
add_action( 'add_meta_boxes', 'pic_logoshowcasepro_metaboxes_register' );

function pic_logoshowcasepro_metaboxes_reg( $post, $args ){

    $nav_value                              = get_post_meta($post->ID, 'nav_value', true);
    $image_name 				            = get_post_meta($post->ID, 'image_name', true);
    if(empty($image_name)){
    	$image_name = array();
    }
    $bend_single_logo_name                  = get_post_meta($post->ID, 'bend_single_logo_name', true);
    if(empty($bend_single_logo_name)){
        $bend_single_logo_name = array();
    }
    $bend_single_logo_desc 					= get_post_meta($post->ID, 'bend_single_logo_desc', true);
    if(empty($bend_single_logo_desc)){
    	$bend_single_logo_desc = array();
    }
    $bend_single_logo_url 					= get_post_meta($post->ID, 'bend_single_logo_url', true);
    if(empty($bend_single_logo_url)){
    	$bend_single_logo_url = array();
    }
    $pkslogo_styles    		    = get_post_meta($post->ID, 'pkslogo_styles', true);
    $pkslogo_autoplayoptions    = get_post_meta($post->ID, 'pkslogo_autoplayoptions', true);
    $pkslogo_pausehover         = get_post_meta($post->ID, 'pkslogo_pausehover', true);
    $pkslogo_autoplayspeed      = get_post_meta($post->ID, 'pkslogo_autoplayspeed', true);
    $pkslogo_dotsoptions        = get_post_meta($post->ID, 'pkslogo_dotsoptions', true);
    $pklslogo_dotcolor          = get_post_meta($post->ID, 'pklslogo_dotcolor', true);
    $pklslogo_dotactcolor       = get_post_meta($post->ID, 'pklslogo_dotactcolor', true);
    $pkslogo_arrowoptions       = get_post_meta($post->ID, 'pkslogo_arrowoptions', true);
    $pkslogo_arrow_margin       = get_post_meta($post->ID, 'pkslogo_arrow_margin', true);
    $pklslogo_arrowcolor        = get_post_meta($post->ID, 'pklslogo_arrowcolor', true);
    $pklslogo_arrow_hovercolor  = get_post_meta($post->ID, 'pklslogo_arrow_hovercolor', true);
    $pklslogo_arrow_bgcolor     = get_post_meta($post->ID, 'pklslogo_arrow_bgcolor', true);
    $pklslogo_arrow_hbgcolor    = get_post_meta($post->ID, 'pklslogo_arrow_hbgcolor', true);
    $pkslogo_arrow_radius       = get_post_meta($post->ID, 'pkslogo_arrow_radius', true);
    $pkslogo_pausehover         = get_post_meta($post->ID, 'pkslogo_pausehover', true);
    $pkslogo_displayitems       = get_post_meta($post->ID, 'pkslogo_displayitems', true);
    $pkslogo_mediumitems        = get_post_meta($post->ID, 'pkslogo_mediumitems', true);
    $pkslogo_smallitems         = get_post_meta($post->ID, 'pkslogo_smallitems', true);
    $pkslogo_swipeoptions       = get_post_meta($post->ID, 'pkslogo_swipeoptions', true);
    $pkslogo_dragsoptions       = get_post_meta($post->ID, 'pkslogo_dragsoptions', true);
    $pkslogo_columns            = get_post_meta($post->ID, 'pkslogo_columns', true);
    $pklslogo_margin_bottom     = get_post_meta($post->ID, 'pklslogo_margin_bottom', true);
    $pklslogo_margin_lfr        = get_post_meta($post->ID, 'pklslogo_margin_lfr', true);
    $pklslogo_bordersize        = get_post_meta($post->ID, 'pklslogo_bordersize', true);
    $pkslogo_borderstyles       = get_post_meta($post->ID, 'pkslogo_borderstyles', true);
    $pklslogo_borderclr         = get_post_meta($post->ID, 'pklslogo_borderclr', true);
    $pklslogo_border_hvrclr     = get_post_meta($post->ID, 'pklslogo_border_hvrclr', true);
    $pklslogo_bag_color         = get_post_meta($post->ID, 'pklslogo_bag_color', true);
    $pklslogo_title_color       = get_post_meta($post->ID, 'pklslogo_title_color', true);
    $pkls_logotitle_font_size   = get_post_meta($post->ID, 'pkls_logotitle_font_size', true);
    $pkls_logotitle_transfrom   = get_post_meta($post->ID, 'pkls_logotitle_transfrom', true);
    $pkls_logotitle_fontstyle   = get_post_meta($post->ID, 'pkls_logotitle_fontstyle', true);
    $pkslogo_content_hide       = get_post_meta($post->ID, 'pkslogo_content_hide', true);
    $pkls_logocontent_size      = get_post_meta($post->ID, 'pkls_logocontent_size', true);
    $pkls_logocontent_transfrom = get_post_meta($post->ID, 'pkls_logocontent_transfrom', true);
    $pkls_logocontent_fontstyle = get_post_meta($post->ID, 'pkls_logocontent_fontstyle', true);
    $pkslogo_title_hide         = get_post_meta($post->ID, 'pkslogo_title_hide', true);
    $pklslogo_content_color     = get_post_meta($post->ID, 'pklslogo_content_color', true);
    $pkls_logo_padding_size     = get_post_meta($post->ID, 'pkls_logo_padding_size', true);
    $pkls_logotooltip           = get_post_meta($post->ID, 'pkls_logotooltip', true);
    $pkls_logotooltipclr        = get_post_meta($post->ID, 'pkls_logotooltipclr', true);
    $pkls_logotooltiptclr       = get_post_meta($post->ID, 'pkls_logotooltiptclr', true);
    $pkls_remoreclr             = get_post_meta($post->ID, 'pkls_remoreclr', true);
    $pkls_remorebgclr           = get_post_meta($post->ID, 'pkls_remorebgclr', true);
    $pkls_remorehvbgclr         = get_post_meta($post->ID, 'pkls_remorehvbgclr', true);
    $pkslogo_types              = get_post_meta($post->ID, 'pkslogo_types', true);
    $pkslogo_heights            = get_post_meta($post->ID, 'pkslogo_heights', true);
    $pkslogo_custom             = get_post_meta($post->ID, 'pkslogo_custom', true);
    $pkslogo_imggray            = get_post_meta($post->ID, 'pkslogo_imggray', true);
    $pkslogo_img_anim           = get_post_meta($post->ID, 'pkslogo_img_anim', true);
    $pkslogo_bshadow            = get_post_meta($post->ID, 'pkslogo_bshadow', true);
?>

<div class="pklslogo">
    <ul class="tab-nav">
        <li nav="1" class="nav1 <?php if($nav_value == 1){echo "active";}?>"><i class="fa fa-clipboard" aria-hidden="true" ></i> <?php _e('Upload Logos','logo-showcase-free'); ?></li>
        <li nav="2" class="nav2 <?php if($nav_value == 2){echo "active";}?>"><i class="fa fa-gear" aria-hidden="true"></i> <?php _e('General Settings','logo-showcase-free'); ?></li>
        <li nav="3" class="nav3 <?php if($nav_value == 3){echo "active";}?>"><i class="fa fa-code" aria-hidden="true"></i> <?php _e('Slider/Grid Settings','logo-showcase-free'); ?></li>
    </ul> <!-- tab-nav end -->
    <?php $getNavValue = ""; if(!empty($nav_value)){ $getNavValue = $nav_value; } else { $getNavValue = 1; }?>
    <input type="hidden" name="nav_value" id="nav_value" value="<?php echo $getNavValue; ?>"> 

<ul class="box">
    <li style="<?php if($nav_value == 1){echo "display: block;";} else{ echo "display: none;"; }?>" class="box1 tab-box <?php if($nav_value == 1){echo "active";}?>">

        <div class="imageupload_btn_area">
	    	<span id="add_more"> <i class="fa fa-image"></i> <?php _e('Upload','logo-showcase-free'); ?> </span>
	    </div>
         <div class="sortable-history"><a style="color:red;text-decoration: none" target="_blank" href="https://pickelements.com/logoshowcasefree"><?php _e( 'Drag & Drop Logo Order Feature Only Available on Premium Version.', 'logo-showcase-free' ); ?></a></div>

	    <table class="form-table">
	        <tbody>
	            <tr valign="top">
	                <td style="vertical-align: middle;">
	                	<div id="main_media">
							<?php $neptune = 700;
                            for($i=0; $i<= count($image_name)-1; $i++){ ?>
								<div class="bend_single_logos" id="bend_single_logos<?php echo $neptune; ?>">
									<div class="image_area">
										<img src="<?php echo $image_name[$i]; ?>" >
										<input type="hidden" name="image_name[]" value="<?php echo $image_name[$i]; ?>">
									</div>
									<span class="pels_logo_remove"><i class="fa fa-times" aria-hidden="true" onclick="del_Saveddiv(<?php echo $neptune; ?>);"></i></span>
									<div class="logo-content-holder">
		                                <div class="input_area">
		                                	<input type="text" name="bend_single_logo_name[]" class="widefat" placeholder="Insert Logo Title" value="<?php if(!empty($bend_single_logo_name[$i])){ echo $bend_single_logo_name[$i]; } ?>">
		                                </div>
                                        <div class="input_area">
                                            <input type="url" name="bend_single_logo_url[]" class="widefat" placeholder="Insert Logo URL" value="<?php if(!empty($bend_single_logo_url[$i])){ echo $bend_single_logo_url[$i]; } ?>">
                                        </div>
		                                <div class="input_area">
                                            <textarea id="logdesc" name="bend_single_logo_desc[]" class="widefat" placeholder="Logo Description (Only Premium Version)"><?php if(!empty($bend_single_logo_desc[$i])){ echo $bend_single_logo_desc[$i]; } ?></textarea>
		                                </div>
		                            </div>
								</div>
							<?php $neptune++; } ?>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
    </li>

    <li style="<?php if($nav_value == 2){echo "display: block;";} else{ echo "display: none;"; }?>" class="box2 tab-box <?php if($nav_value == 2){echo "active";}?>">
        <div class="option-box">
            <p class="option-title"><?php _e('Logo Settings','logo-showcase-free'); ?> - <span><a target="_blank" href="https://pickelements.com/logoshowcasefree"><?php _e('Unlock All Features', 'logo-showcase-free');?></a></span></p>

            <div class="wrap">
                <div class="pkslogo-customize-area">
                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Select Logo Style', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Select logo showcase styles.', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkslogo_styles" id="pkslogo_styles" class="timezone_string">
                                <option value="1" <?php if ( isset ( $pkslogo_styles ) ) selected( $pkslogo_styles, '1' ); ?>><?php _e('Style 1', 'logo-showcase-free');?></option>
                                <option disabled value="2" <?php if ( isset ( $pkslogo_styles ) ) selected( $pkslogo_styles, '2' ); ?>><?php _e('Style 2 (Only Pro)', 'logo-showcase-free');?></option>
                                <option disabled value="3" <?php if ( isset ( $pkslogo_styles ) ) selected( $pkslogo_styles, '3' ); ?>><?php _e('Style 3 (Only Pro)', 'logo-showcase-free');?></option>
                                <option disabled value="4" <?php if ( isset ( $pkslogo_styles ) ) selected( $pkslogo_styles, '4' ); ?>><?php _e('Style 4 (Only Pro)', 'logo-showcase-free');?></option>
                                <option disabled value="5" <?php if ( isset ( $pkslogo_styles ) ) selected( $pkslogo_styles, '5' ); ?>><?php _e('List Left Style (Only Pro)', 'logo-showcase-free');?></option>
                                <option disabled value="6" <?php if ( isset ( $pkslogo_styles ) ) selected( $pkslogo_styles, '6' ); ?>><?php _e('List Right Style (Only Pro)', 'logo-showcase-free');?></option>
                                <option disabled value="7" <?php if ( isset ( $pkslogo_styles ) ) selected( $pkslogo_styles, '7' ); ?>><?php _e('List Center Style (Only Pro)', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End Logo Style -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Select Logo Type', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Select Logo Showcase type grid, list, table, or slider. all options are available on the grid & slider settings tab.', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkslogo_types" id="pkslogo_types" class="timezone_string">
                                <option value="1" <?php if ( isset ( $pkslogo_types ) ) selected( $pkslogo_types, '1' ); ?>><?php _e('Slider', 'logo-showcase-free');?></option>
                                <option value="2" <?php if ( isset ( $pkslogo_types ) ) selected( $pkslogo_types, '2' ); ?>><?php _e('Grid', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End Logo Showcase Type -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Items Height', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Select Logo Showcase items height. default height: auto.', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkslogo_heights" id="pkslogo_heights" class="timezone_string">
                                <option value="1" <?php if ( isset ( $pkslogo_heights ) ) selected( $pkslogo_heights, '1' ); ?>><?php _e('Default', 'logo-showcase-free');?></option>
                                <option value="2" <?php if ( isset ( $pkslogo_heights ) ) selected( $pkslogo_heights, '2' ); ?>><?php _e('Custom', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End Height Options -->

                    <div class="pkslogo-customize-inner" id="pkslghi2">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Custom Height', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase items Custom height. Custom height: 110px.', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="number" name="pkslogo_custom" id="pkslogo_custom" maxlength="4" class="timezone_string" value="<?php if($pkslogo_custom !=''){echo $pkslogo_custom; }else{ echo '110';} ?>">px
                        </div>
                    </div><!-- End Custom Height -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Item Box Shadow (Pro)', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Logo Showcase item box shadow.', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkslogo_bshadow" id="pkslogo_bshadow" class="timezone_string">
                                <option value="1" <?php if ( isset ( $pkslogo_bshadow ) ) selected( $pkslogo_bshadow, '1' ); ?>><?php _e('Default', 'logo-showcase-free');?></option>
                                <option disabled value="2" <?php if ( isset ( $pkslogo_bshadow ) ) selected( $pkslogo_bshadow, '2' ); ?>><?php _e('Shadow Style 2(Pro)', 'logo-showcase-free');?></option>
                                <option disabled value="3" <?php if ( isset ( $pkslogo_bshadow ) ) selected( $pkslogo_bshadow, '3' ); ?>><?php _e('Shadow Style 3(Pro)', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End Box Shadow Options -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Logo Image Effect (Pro)', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Logo Showcase image style default or grayscale. default style: default.', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkslogo_imggray" id="pkslogo_imggray" class="timezone_string">
                                <option value="1" <?php if ( isset ( $pkslogo_imggray ) ) selected( $pkslogo_imggray, '1' ); ?>><?php _e('Default', 'logo-showcase-free');?></option>
                                <option disabled value="2" <?php if ( isset ( $pkslogo_imggray ) ) selected( $pkslogo_imggray, '2' ); ?>><?php _e('Grayscale (Only Pro)', 'logo-showcase-free');?></option>
                                <option disabled value="3" <?php if ( isset ( $pkslogo_imggray ) ) selected( $pkslogo_imggray, '3' ); ?>><?php _e('Blur (Only Pro)', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End Grayscale Options -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Logo Image Animation (Pro)', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase image animation style.', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkslogo_img_anim" id="pkslogo_img_anim" class="timezone_string">
                                <option value="1" <?php if ( isset ( $pkslogo_img_anim ) ) selected( $pkslogo_img_anim, '1' ); ?>><?php _e('Default', 'logo-showcase-free');?></option>
                                <option disabled value="2" <?php if ( isset ( $pkslogo_img_anim ) ) selected( $pkslogo_img_anim, '2' ); ?>><?php _e('Zoom In (Only Pro)', 'logo-showcase-free');?></option>
                                <option disabled value="3" <?php if ( isset ( $pkslogo_img_anim ) ) selected( $pkslogo_img_anim, '3' ); ?>><?php _e('Zoom Out (Only Pro)', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End Logo Image Animation -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Logo Border Style (Pro)', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Select Logo Showcase items border styles. default style: Solid', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkslogo_borderstyles" id="pkslogo_borderstyles" class="timezone_string">
                                <option value="solid" <?php if ( isset ( $pkslogo_borderstyles ) ) selected( $pkslogo_borderstyles, 'solid' ); ?>><?php _e('Solid', 'logo-showcase-free');?></option>
                                <option disabled value="dotted" <?php if ( isset ( $pkslogo_borderstyles ) ) selected( $pkslogo_borderstyles, 'dotted' ); ?>><?php _e('Dotted (Only Pro)', 'logo-showcase-free');?></option>
                                <option disabled value="dashed" <?php if ( isset ( $pkslogo_borderstyles ) ) selected( $pkslogo_borderstyles, 'dashed' ); ?>><?php _e('Dashed (Only Pro)', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End Border -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Logo Item Border', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase grid items border size. default size: 1px', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="number" name="pklslogo_bordersize" id="pklslogo_bordersize" min="0" max="10" class="timezone_string" required value="<?php  if($pklslogo_bordersize !=''){echo $pklslogo_bordersize; }else{ echo '1';} ?>">
                        </div>
                    </div><!-- End -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Logo Border Color', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase grid items border size. default Color: #ccc', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="text" name="pklslogo_borderclr" id="pklslogo_borderclr" class="timezone_string" value="<?php  if($pklslogo_borderclr !=''){echo $pklslogo_borderclr; }else{ echo '#ccc';} ?>">
                        </div>
                    </div><!-- End Logo Border Color -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Logo Border Hover Color', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase grid items border size. default Color: #ccc', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="text" name="pklslogo_border_hvrclr" id="pklslogo_border_hvrclr" class="timezone_string" value="<?php  if($pklslogo_border_hvrclr !=''){echo $pklslogo_border_hvrclr; }else{ echo '#ccc';} ?>">
                        </div>
                    </div><!-- End Border Hover Color -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Hide Logo Title', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Show or Hide logo title. default Title: Hide', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkslogo_title_hide" id="pkslogo_title_hide" class="timezone_string">
                                <option value="1" <?php if ( isset ( $pkslogo_title_hide ) ) selected( $pkslogo_title_hide, '1' ); ?>><?php _e('Show', 'logo-showcase-free');?></option>
                                <option disabled value="2" <?php if ( isset ( $pkslogo_title_hide ) ) selected( $pkslogo_title_hide, '2' ); ?>><?php _e('Hide (Only Pro)', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End Title Hide -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Logo Titile Color', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase grid items title color. default Color: #666', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="text" name="pklslogo_title_color" id="pklslogo_title_color" class="timezone_string" value="<?php  if($pklslogo_title_color !=''){echo $pklslogo_title_color; }else{ echo '#666';} ?>">
                        </div>
                    </div><!-- End Title Color -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Logo Titile Font Size', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase grid items title font size. default Size: 15px', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="number" name="pkls_logotitle_font_size" id="pkls_logotitle_font_size" maxlength="4" class="timezone_string" value="<?php if($pkls_logotitle_font_size !=''){echo $pkls_logotitle_font_size; }else{ echo '15';} ?>">px
                        </div>
                    </div><!-- End Title Font Size -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Logo Titile Text Transform', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo title Text Transform. default text transform: capitalize', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkls_logotitle_transfrom" id="pkls_logotitle_transfrom" class="timezone_string">
                                <option value="unset" <?php if ( isset ( $pkls_logotitle_transfrom ) ) selected( $pkls_logotitle_transfrom, 'unset' ); ?>><?php _e('Default', 'logo-showcase-free');?></option>
                                <option disabled value="capitalize" <?php if ( isset ( $pkls_logotitle_transfrom ) ) selected( $pkls_logotitle_transfrom, 'capitalize' ); ?>><?php _e('Capitilize (Only Pro)', 'logo-showcase-free');?></option>
                                <option disabled value="lowercase" <?php if ( isset ( $pkls_logotitle_transfrom ) ) selected( $pkls_logotitle_transfrom, 'lowercase' ); ?>><?php _e('Lowercase (Only Pro)', 'logo-showcase-free');?></option>
                                <option disabled value="uppercase" <?php if ( isset ( $pkls_logotitle_transfrom ) ) selected( $pkls_logotitle_transfrom, 'uppercase' ); ?>><?php _e('Uppercase (Only Pro)', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End Title Text Transfrom -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Title Font Style', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo title Text Style. default: Normal', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkls_logotitle_fontstyle" id="pkls_logotitle_fontstyle" class="timezone_string">
                                <option value="normal" <?php if ( isset ( $pkls_logotitle_fontstyle ) ) selected( $pkls_logotitle_fontstyle, 'normal' ); ?>><?php _e('Normal', 'logo-showcase-free');?></option>
                                <option disabled value="italic" <?php if ( isset ( $pkls_logotitle_fontstyle ) ) selected( $pkls_logotitle_fontstyle, 'italic' ); ?>><?php _e('Italic (Only Pro)', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End Title Font Style -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Hide Logo Content', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Show or Hide logo Details. default :Hide', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkslogo_content_hide" id="pkslogo_content_hide" class="timezone_string">
                                <option value="2" <?php if ( isset ( $pkslogo_content_hide ) ) selected( $pkslogo_content_hide, '2' ); ?>><?php _e('Hide', 'logo-showcase-free');?></option>
                                <option disabled value="1" <?php if ( isset ( $pkslogo_content_hide ) ) selected( $pkslogo_content_hide, '1' ); ?>><?php _e('Show (Only Pro)', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End Hide Logo Content -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Logo Content Font Size', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase grid items Content font size. default Size: 13px', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="number" name="pkls_logocontent_size" id="pkls_logocontent_size" maxlength="4" class="timezone_string" value="<?php if($pkls_logocontent_size !=''){echo $pkls_logocontent_size; }else{ echo '13';} ?>">px
                        </div>
                    </div><!-- End Logo Content Font Size -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Logo Content Color', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase grid items Content color. default Color: #666', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="text" name="pklslogo_content_color" id="pklslogo_content_color" class="timezone_string" value="<?php  if($pklslogo_content_color !=''){echo $pklslogo_content_color; }else{ echo '#666';} ?>">
                        </div>
                    </div><!-- End Logo Content Color -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Content Text Transform', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Content Text Transform. default text transform: capitalize', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkls_logocontent_transfrom" id="pkls_logocontent_transfrom" class="timezone_string">
                                <option value="unset" <?php if ( isset ( $pkls_logocontent_transfrom ) ) selected( $pkls_logocontent_transfrom, 'unset' ); ?>><?php _e('Default', 'logo-showcase-free');?></option>
                                <option value="capitalize" <?php if ( isset ( $pkls_logocontent_transfrom ) ) selected( $pkls_logocontent_transfrom, 'capitalize' ); ?>><?php _e('Capitilize', 'logo-showcase-free');?></option>
                                <option value="lowercase" <?php if ( isset ( $pkls_logocontent_transfrom ) ) selected( $pkls_logocontent_transfrom, 'lowercase' ); ?>><?php _e('Lowercase', 'logo-showcase-free');?></option>
                                <option value="uppercase" <?php if ( isset ( $pkls_logocontent_transfrom ) ) selected( $pkls_logocontent_transfrom, 'uppercase' ); ?>><?php _e('Uppercase', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End Content Text Transform -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Content Font Style', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Content Text Style. default: Normal', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkls_logocontent_fontstyle" id="pkls_logocontent_fontstyle" class="timezone_string">
                                <option value="normal" <?php if ( isset ( $pkls_logocontent_fontstyle ) ) selected( $pkls_logocontent_fontstyle, 'normal' ); ?>><?php _e('Normal', 'logo-showcase-free');?></option>
                                <option value="italic" <?php if ( isset ( $pkls_logocontent_fontstyle ) ) selected( $pkls_logocontent_fontstyle, 'italic' ); ?>><?php _e('Italic', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End Content Font Style -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Logo Background Color', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase grid items background color. default Color: #f8f8f8', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="text" name="pklslogo_bag_color" id="pklslogo_bag_color" class="timezone_string" value="<?php  if($pklslogo_bag_color !=''){echo $pklslogo_bag_color; }else{ echo '#f8f8f8';} ?>">
                        </div>
                    </div><!-- End Logo Background Color -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Logo Padding Size', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase grid logo padding size. default Size: 20px', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="number" name="pkls_logo_padding_size" id="pkls_logo_padding_size" maxlength="4" class="timezone_string" value="<?php if($pkls_logo_padding_size !=''){echo $pkls_logo_padding_size; }else{ echo '20';} ?>">px
                        </div>
                    </div><!-- End Logo Padding Size -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Tooltip (Show/Hide)', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo tooltip show/hide option. default: Show', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkls_logotooltip" id="pkls_logotooltip" class="timezone_string">
                                <option value="1" <?php if ( isset ( $pkls_logotooltip ) ) selected( $pkls_logotooltip, '1' ); ?>><?php _e('Show', 'logo-showcase-free');?></option>
                                <option disabled value="2" <?php if ( isset ( $pkls_logotooltip ) ) selected( $pkls_logotooltip, '2' ); ?>><?php _e('Hide (Only Pro)', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End Tooltip Style -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Tooltip Background Color', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo tooltip background color. default Color: #000', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="text" name="pkls_logotooltipclr" id="pkls_logotooltipclr" class="timezone_string" value="<?php  if($pkls_logotooltipclr !=''){echo $pkls_logotooltipclr; }else{ echo '#000';} ?>">
                        </div>
                    </div><!-- End Tooltip background color -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Tooltip Text Color', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo tooltip text color. default Color: #fff', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="text" name="pkls_logotooltiptclr" id="pkls_logotooltiptclr" class="timezone_string" value="<?php  if($pkls_logotooltiptclr !=''){echo $pkls_logotooltiptclr; }else{ echo '#fff';} ?>">
                        </div>
                    </div><!-- End Tooltip text color -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Read More Text Color', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Read more Button Text Color. default Color: #fff', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="text" name="pkls_remoreclr" id="pkls_remoreclr" class="timezone_string" value="<?php  if($pkls_remoreclr !=''){echo $pkls_remoreclr; }else{ echo '#fff';} ?>">
                        </div>
                    </div><!-- End read more text color -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Read More BG Color', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Read More Button Background Color. default Color: #000', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="text" name="pkls_remorebgclr" id="pkls_remorebgclr" class="timezone_string" value="<?php  if($pkls_remorebgclr !=''){echo $pkls_remorebgclr; }else{ echo '#000';} ?>">
                        </div>
                    </div><!-- End read more background color -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Read More Hover BG Color', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Read More Button Hover Background Color. default Color: #ddd', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="text" name="pkls_remorehvbgclr" id="pkls_remorehvbgclr" class="timezone_string" value="<?php  if($pkls_remorehvbgclr !=''){echo $pkls_remorehvbgclr; }else{ echo '#ddd';} ?>">
                        </div>
                    </div><!-- End read more background color -->

                </div>
            </div>
        </div>
    </li> 

    <li style="<?php if($nav_value == 3){echo "display: block;";} else{ echo "display: none;"; }?>" class="box3 tab-box <?php if($nav_value == 3){echo "active";}?>">
        <div class="option-boxs" id="test01">
            <div class="option-box">
                <p class="option-title"><?php _e('Slider Settings','logo-showcase-free'); ?> - <span><a target="_blank" href="https://pickelements.com/logoshowcasefree"><?php _e('Unlock All Features', 'logo-showcase-free');?></a></span></p>
                <div class="wrap">
                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('AutoPlay(Yes/No)', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Select Logo Showcase autoplay options. default: Yes', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkslogo_autoplayoptions" id="pkslogo_autoplayoptions" class="timezone_string">
                                <option value="true" <?php if ( isset ( $pkslogo_autoplayoptions ) ) selected( $pkslogo_autoplayoptions, 'true' ); ?>><?php _e('Yes', 'logo-showcase-free');?></option>
                                <option value="false" <?php if ( isset ( $pkslogo_autoplayoptions ) ) selected( $pkslogo_autoplayoptions, 'false' ); ?>><?php _e('No', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End AutoPlay(Yes/No) -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Stop Hover(Yes/No)', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase Pause On Hover options. default: Yes', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkslogo_pausehover" id="pkslogo_pausehover" class="timezone_string">
                                <option value="true" <?php if ( isset ( $pkslogo_pausehover ) ) selected( $pkslogo_pausehover, 'true' ); ?>><?php _e('Yes', 'logo-showcase-free');?></option>
                                <option value="false" <?php if ( isset ( $pkslogo_pausehover ) ) selected( $pkslogo_pausehover, 'false' ); ?>><?php _e('No', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End Pause Hover -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('AutoPlay Speed(1500)', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase autoplay Speed. default: 1500', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="number" name="pkslogo_autoplayspeed" id="pkslogo_autoplayspeed" maxlength="4" class="timezone_string" value="<?php if($pkslogo_autoplayspeed !=''){echo $pkslogo_autoplayspeed; }else{ echo '1500';} ?>">
                        </div>
                    </div><!-- End Autoplay Speed -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Display Items', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose total items display in per slider. default: 4', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="number" name="pkslogo_displayitems" id="pkslogo_displayitems" maxlength="4" class="timezone_string" value="<?php if($pkslogo_displayitems !=''){echo $pkslogo_displayitems; }else{ echo '4';} ?>">
                        </div>
                    </div><!-- End Total Items -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Items Medium Devices', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose items display in medium devices. default: 2', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="number" name="pkslogo_mediumitems" id="pkslogo_mediumitems" maxlength="4" class="timezone_string" value="<?php if($pkslogo_mediumitems !=''){echo $pkslogo_mediumitems; }else{ echo '2';} ?>">
                        </div>
                    </div><!-- End Total Medium -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Items Small Devices', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose items display in small devices. default: 1', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="number" name="pkslogo_smallitems" id="pkslogo_smallitems" maxlength="4" class="timezone_string" value="<?php if($pkslogo_smallitems !=''){echo $pkslogo_smallitems; }else{ echo '1';} ?>">
                        </div>
                    </div><!-- End Total Medium -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Swipe (Yes/No)', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase Swipe options. default: Yes', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkslogo_swipeoptions" id="pkslogo_swipeoptions" class="timezone_string">
                                <option value="true" <?php if ( isset ( $pkslogo_swipeoptions ) ) selected( $pkslogo_swipeoptions, 'true' ); ?>><?php _e('Yes', 'logo-showcase-free');?></option>
                                <option disabled value="false" <?php if ( isset ( $pkslogo_swipeoptions ) ) selected( $pkslogo_swipeoptions, 'false' ); ?>><?php _e('No (Only Pro)', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End Swipe -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Draggable (Yes/No)', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase Draggable options. default: Yes', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkslogo_dragsoptions" id="pkslogo_dragsoptions" class="timezone_string">
                                <option value="true" <?php if ( isset ( $pkslogo_dragsoptions ) ) selected( $pkslogo_dragsoptions, 'true' ); ?>><?php _e('Yes', 'logo-showcase-free');?></option>
                                <option disabled value="false" <?php if ( isset ( $pkslogo_dragsoptions ) ) selected( $pkslogo_dragsoptions, 'false' ); ?>><?php _e('No (Only Pro)', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End Draggable -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Dots (Yes/No)', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase Dots options. default: Yes', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkslogo_dotsoptions" id="pkslogo_dotsoptions" class="timezone_string">
                                <option value="true" <?php if ( isset ( $pkslogo_dotsoptions ) ) selected( $pkslogo_dotsoptions, 'true' ); ?>><?php _e('Yes', 'logo-showcase-free');?></option>
                                <option disabled value="false" <?php if ( isset ( $pkslogo_dotsoptions ) ) selected( $pkslogo_dotsoptions, 'false' ); ?>><?php _e('No (Only Pro)', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End Dots -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Dots Color', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase Dots color. default: #dd0000', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="text" name="pklslogo_dotcolor" id="pklslogo_dotcolor" class="timezone_string" value="<?php  if($pklslogo_dotcolor !=''){echo $pklslogo_dotcolor; }else{ echo '#dd0000';} ?>">
                        </div>
                    </div><!-- End Dots color -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Dots Active Color', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase Dots Active color. default: #000', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="text" name="pklslogo_dotactcolor" id="pklslogo_dotactcolor" class="timezone_string" value="<?php  if($pklslogo_dotactcolor !=''){echo $pklslogo_dotactcolor; }else{ echo '#000';} ?>">
                        </div>
                    </div><!-- End Dots color -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Arrows (Yes/No)', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase Arrow options. default: Yes', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkslogo_arrowoptions" id="pkslogo_arrowoptions" class="timezone_string">
                                <option value="true" <?php if ( isset ( $pkslogo_arrowoptions ) ) selected( $pkslogo_arrowoptions, 'true' ); ?>><?php _e('Yes', 'logo-showcase-free');?></option>
                                <option disabled value="false" <?php if ( isset ( $pkslogo_arrowoptions ) ) selected( $pkslogo_arrowoptions, 'false' ); ?>><?php _e('No (Only Pro)', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End Arrow -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Arrow Margin (Pro)', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Please use margin top if you need to center the arrow key.', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="number" name="pkslogo_arrow_margin" id="pkslogo_arrow_margin" maxlength="4" class="timezone_string" value="<?php if($pkslogo_arrow_margin !=''){echo $pkslogo_arrow_margin; }else{ echo '0';} ?>">
                        </div>
                    </div><!-- End Arrow Margin -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Arrow Text Color', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase Arrow text color. default: #000', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="text" name="pklslogo_arrowcolor" id="pklslogo_arrowcolor" class="timezone_string" value="<?php  if($pklslogo_arrowcolor !=''){echo $pklslogo_arrowcolor; }else{ echo '#000';} ?>">
                        </div>
                    </div><!-- End Arrow color -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Arrow Text Hover Color', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase Arrow hover text color. default: #000', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="text" name="pklslogo_arrow_hovercolor" id="pklslogo_arrow_hovercolor" class="timezone_string" value="<?php  if($pklslogo_arrow_hovercolor !=''){echo $pklslogo_arrow_hovercolor; }else{ echo '#000';} ?>">
                        </div>
                    </div><!-- End Arrow Text Hover Color -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Arrow Bg Color', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase Arrow background color. default: #ddd', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="text" name="pklslogo_arrow_bgcolor" id="pklslogo_arrow_bgcolor" class="timezone_string" value="<?php if($pklslogo_arrow_bgcolor !=''){echo $pklslogo_arrow_bgcolor; }else{ echo '#ddd';} ?>">
                        </div>
                    </div><!-- End Arrow Bg Color -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Arrow Hover Bg Color', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase Arrow hover background color. default: #ddd', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="text" name="pklslogo_arrow_hbgcolor" id="pklslogo_arrow_hbgcolor" class="timezone_string" value="<?php if($pklslogo_arrow_hbgcolor !=''){echo $pklslogo_arrow_hbgcolor; }else{ echo '#ddd';} ?>">
                        </div>
                    </div><!-- End Arrow Hover Bg Color -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Arrow Border Radius (Pro)', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose logo showcase arrow border radius. default: 50', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="number" name="pkslogo_arrow_radius" id="pkslogo_arrow_radius" maxlength="4" class="timezone_string" value="<?php if($pkslogo_arrow_radius !=''){echo $pkslogo_arrow_radius; }else{ echo '50';} ?>">
                        </div>
                    </div><!-- End Arrow Border Radius -->

                </div>
             </div>
        </div>

        <div class="option-box" id="test02">
            <div class="option-box">
                <p class="option-title"><?php _e('Grid Settings','logo-showcase-free'); ?> - <span><a target="_blank" href="https://pickelements.com/logoshowcasefree"><?php _e('Unlock All Features', 'logo-showcase-free'); ?></a></span></p>
                <div class="wrap">
                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Select Logo Column', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Select Logo Showcase grid columns. default column: 3', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <select name="pkslogo_columns" id="pkslogo_columns" class="timezone_string">
                                <option value="3" <?php if ( isset ( $pkslogo_columns ) ) selected( $pkslogo_columns, '3' ); ?>><?php _e('Column 3', 'logo-showcase-free');?></option>
                                <option value="2" <?php if ( isset ( $pkslogo_columns ) ) selected( $pkslogo_columns, '2' ); ?>><?php _e('Column 2', 'logo-showcase-free');?></option>
                                <option disabled value="4" <?php if ( isset ( $pkslogo_columns ) ) selected( $pkslogo_columns, '4' ); ?>><?php _e('Column 4 (Only Pro)', 'logo-showcase-free');?></option>
                                <option disabled value="5" <?php if ( isset ( $pkslogo_columns ) ) selected( $pkslogo_columns, '5' ); ?>><?php _e('Column 5 (Only Pro)', 'logo-showcase-free');?></option>
                                <option disabled value="6" <?php if ( isset ( $pkslogo_columns ) ) selected( $pkslogo_columns, '6' ); ?>><?php _e('Column 6 (Only Pro)', 'logo-showcase-free');?></option>
                            </select>
                        </div>
                    </div><!-- End -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Grid Margin Bottom', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase grid items margin Bottomm. default margin: 10px. (Only Pro)', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="number" name="pklslogo_margin_bottom" id="pklslogo_margin_bottom" min="0" max="100" class="timezone_string" required value="<?php  if($pklslogo_margin_bottom !=''){echo $pklslogo_margin_bottom; }else{ echo '10';} ?>">
                        </div>
                    </div><!-- End -->

                    <div class="pkslogo-customize-inner">
                        <div class="pkslogo-heading-area">
                            <span class="sub-heading"><?php _e('Grid Margin Left/Right', 'logo-showcase-free');?></span>
                            <span class="sub-description"><?php _e('Choose Logo Showcase grid items margin Left/Right. default margin: 5px. (Only Pro)', 'logo-showcase-free');?> </span>
                        </div>
                        <div class="pkslogo-select-items">
                            <input type="number" name="pklslogo_margin_lfr" id="pklslogo_margin_lfr" min="0" max="100" class="timezone_string" required value="<?php  if($pklslogo_margin_lfr !=''){echo $pklslogo_margin_lfr; }else{ echo '5';} ?>">
                        </div>
                    </div><!-- End  -->
                </div>
             </div>
        </div>
    </li>

    <script>
        jQuery(document).ready(function(){
            jQuery("#pklslogo_borderclr, #pklslogo_border_hvrclr, #pklslogo_bag_color, #pklslogo_title_color, #pklslogo_content_color, #pklslogo_arrowcolor, #pklslogo_arrow_hovercolor, #pkls_remorebgclr, #pklslogo_arrow_hbgcolor, #pklslogo_arrow_bgcolor, #pkls_remorehvbgclr, #pklslogo_dotcolor, #pklslogo_dotactcolor, #pkls_remoreclr, #pkls_logotooltiptclr, #pkls_logotooltipclr").wpColorPicker();
        });
    </script>

</ul>
</div>    
<?php }
# Data save in custom metabox field
function fwp_saved( $post_id ){ 

    # Doing autosave then return.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;  

    #nav value
    if( isset( $_POST[ 'nav_value' ] ) ) {
        update_post_meta( $post_id, 'nav_value', $_POST['nav_value'] );
    } else {
        update_post_meta( $post_id, 'nav_value', 1 );
    } 

    #image_name
    if( isset( $_POST[ 'image_name' ] ) ) {
        update_post_meta( $post_id, 'image_name', $_POST['image_name'] );
    } 

    #pkslogo_title_color
    if( isset( $_POST[ 'bend_single_logo_name' ] ) ) {
        update_post_meta( $post_id, 'bend_single_logo_name', $_POST['bend_single_logo_name'] );
    }

    #pkslogo_title_color
    if( isset( $_POST[ 'bend_single_logo_desc' ] ) ) {
        update_post_meta( $post_id, 'bend_single_logo_desc', $_POST['bend_single_logo_desc'] );
    }

    #pkslogo_title_color
    if( isset( $_POST[ 'bend_single_logo_url' ] ) ) {
        update_post_meta( $post_id, 'bend_single_logo_url', $_POST['bend_single_logo_url'] );
    }            

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_styles' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_styles', $_POST['pkslogo_styles'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_autoplayoptions' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_autoplayoptions', $_POST['pkslogo_autoplayoptions'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_pausehover' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_pausehover', $_POST['pkslogo_pausehover'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_autoplayspeed' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_autoplayspeed', $_POST['pkslogo_autoplayspeed'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_displayitems' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_displayitems', $_POST['pkslogo_displayitems'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_mediumitems' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_mediumitems', $_POST['pkslogo_mediumitems'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_smallitems' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_smallitems', $_POST['pkslogo_smallitems'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_swipeoptions' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_swipeoptions', $_POST['pkslogo_swipeoptions'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_dragsoptions' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_dragsoptions', $_POST['pkslogo_dragsoptions'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_dotsoptions' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_dotsoptions', $_POST['pkslogo_dotsoptions'] );
    }

    #pklslogo_dotcolor
    if( isset( $_POST[ 'pkslogo_dotsoptions' ] ) ) {
        update_post_meta( $post_id, 'pklslogo_dotcolor', $_POST['pklslogo_dotcolor'] );
    }

    #pklslogo_dotcolor
    if( isset( $_POST[ 'pklslogo_dotactcolor' ] ) ) {
        update_post_meta( $post_id, 'pklslogo_dotactcolor', $_POST['pklslogo_dotactcolor'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_arrowoptions' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_arrowoptions', $_POST['pkslogo_arrowoptions'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_arrow_margin' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_arrow_margin', $_POST['pkslogo_arrow_margin'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pklslogo_arrowcolor' ] ) ) {
        update_post_meta( $post_id, 'pklslogo_arrowcolor', $_POST['pklslogo_arrowcolor'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pklslogo_arrow_hovercolor' ] ) ) {
        update_post_meta( $post_id, 'pklslogo_arrow_hovercolor', $_POST['pklslogo_arrow_hovercolor'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pklslogo_arrow_hbgcolor' ] ) ) {
        update_post_meta( $post_id, 'pklslogo_arrow_hbgcolor', $_POST['pklslogo_arrow_hbgcolor'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pklslogo_arrow_bgcolor' ] ) ) {
        update_post_meta( $post_id, 'pklslogo_arrow_bgcolor', $_POST['pklslogo_arrow_bgcolor'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_arrow_radius' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_arrow_radius', $_POST['pkslogo_arrow_radius'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkls_logotooltip' ] ) ) {
        update_post_meta( $post_id, 'pkls_logotooltip', $_POST['pkls_logotooltip'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkls_logotooltipclr' ] ) ) {
        update_post_meta( $post_id, 'pkls_logotooltipclr', $_POST['pkls_logotooltipclr'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkls_logotooltiptclr' ] ) ) {
        update_post_meta( $post_id, 'pkls_logotooltiptclr', $_POST['pkls_logotooltiptclr'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkls_remoreclr' ] ) ) {
        update_post_meta( $post_id, 'pkls_remoreclr', $_POST['pkls_remoreclr'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkls_remorebgclr' ] ) ) {
        update_post_meta( $post_id, 'pkls_remorebgclr', $_POST['pkls_remorebgclr'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkls_remorehvbgclr' ] ) ) {
        update_post_meta( $post_id, 'pkls_remorehvbgclr', $_POST['pkls_remorehvbgclr'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_columns' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_columns', $_POST['pkslogo_columns'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pklslogo_margin_bottom' ] ) ) {
        update_post_meta( $post_id, 'pklslogo_margin_bottom', $_POST['pklslogo_margin_bottom'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pklslogo_margin_lfr' ] ) ) {
        update_post_meta( $post_id, 'pklslogo_margin_lfr', $_POST['pklslogo_margin_lfr'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pklslogo_bordersize' ] ) ) {
        update_post_meta( $post_id, 'pklslogo_bordersize', $_POST['pklslogo_bordersize'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_borderstyles' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_borderstyles', $_POST['pkslogo_borderstyles'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pklslogo_borderclr' ] ) ) {
        update_post_meta( $post_id, 'pklslogo_borderclr', $_POST['pklslogo_borderclr'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pklslogo_border_hvrclr' ] ) ) {
        update_post_meta( $post_id, 'pklslogo_border_hvrclr', $_POST['pklslogo_border_hvrclr'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pklslogo_bag_color' ] ) ) {
        update_post_meta( $post_id, 'pklslogo_bag_color', $_POST['pklslogo_bag_color'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pklslogo_title_color' ] ) ) {
        update_post_meta( $post_id, 'pklslogo_title_color', $_POST['pklslogo_title_color'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkls_logotitle_font_size' ] ) ) {
        update_post_meta( $post_id, 'pkls_logotitle_font_size', $_POST['pkls_logotitle_font_size'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkls_logotitle_transfrom' ] ) ) {
        update_post_meta( $post_id, 'pkls_logotitle_transfrom', $_POST['pkls_logotitle_transfrom'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkls_logotitle_fontstyle' ] ) ) {
        update_post_meta( $post_id, 'pkls_logotitle_fontstyle', $_POST['pkls_logotitle_fontstyle'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_content_hide' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_content_hide', $_POST['pkslogo_content_hide'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkls_logocontent_size' ] ) ) {
        update_post_meta( $post_id, 'pkls_logocontent_size', $_POST['pkls_logocontent_size'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkls_logocontent_transfrom' ] ) ) {
        update_post_meta( $post_id, 'pkls_logocontent_transfrom', $_POST['pkls_logocontent_transfrom'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkls_logocontent_fontstyle' ] ) ) {
        update_post_meta( $post_id, 'pkls_logocontent_fontstyle', $_POST['pkls_logocontent_fontstyle'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_title_hide' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_title_hide', $_POST['pkslogo_title_hide'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pklslogo_content_color' ] ) ) {
        update_post_meta( $post_id, 'pklslogo_content_color', $_POST['pklslogo_content_color'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkls_logo_padding_size' ] ) ) {
        update_post_meta( $post_id, 'pkls_logo_padding_size', $_POST['pkls_logo_padding_size'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_types' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_types', $_POST['pkslogo_types'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_heights' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_heights', $_POST['pkslogo_heights'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_custom' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_custom', $_POST['pkslogo_custom'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_imggray' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_imggray', $_POST['pkslogo_imggray'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_img_anim' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_img_anim', $_POST['pkslogo_img_anim'] );
    }

    #pkslogo_styles
    if( isset( $_POST[ 'pkslogo_bshadow' ] ) ) {
        update_post_meta( $post_id, 'pkslogo_bshadow', $_POST['pkslogo_bshadow'] );
    }
}
add_action( 'save_post', 'fwp_saved' );


function pe_logoshowcase_display_shortcode( $post, $args ) { ?>
    <p class="option-info"><?php _e('Copy this shortcode and paste it on the page or post where you want to display the Logo Showcase.','logo-showcase-free'); ?></p>
    <textarea cols="35" rows="1" onClick="this.select();" >[piclogofree <?php echo 'id="'.$post->ID.'"';?>]</textarea>
    <?php
}   

function pe_logoshowcase_display_raigngs( $post, $args ) { ?>
    <div class="support-area">
        <p><?php echo esc_html__('If you need any questions or find any bugs in our plugin please do not hesitate to post it on the plugin support section. we are happy to solve our issues as soon as we can.', 'logo-showcase-free');?></p>
        <div class="sp-review">
            <a target="_blank" class="spbtn" href="https://pickelements.com/contact"><?php echo esc_html__('Support', 'logo-showcase-free');?></a>
        </div>
    </div>
    <?php
}

// Review Notice Message
function picklogo_review_notice_message() {
    // Show only to Admins
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $installed = get_option( 'piclogo_installed' );
    if ( !$installed ) {
        update_option( 'piclogo_installed', time() );
    }

    $dismiss_notice  = get_option( 'picklogo_free_review_notice_dismiss', 'no' );
    // $activation_time = strtotime( '-15 days' );
    $activation_time = get_option( 'piclogo_installed' );
    $plugin_info     = get_plugin_data( __FILE__ , true, true );
    $plugin_url      = esc_url( 'https://wordpress.org/support/plugin/'. sanitize_title( $plugin_info['Name'] ) . '/reviews/' );

    // check if it has already been dismissed
    // and don't show notice in 15 days of installation, 1296000 = 15 Days in seconds
    if ( 'yes' === $dismiss_notice ) {
        return;
    }

    if ( time() - $activation_time < 345600 ) {
        return;
    }

    ?>
        <div id="picklogo-review-notice" class="picklogo-review-notice">
            <div class="picklogo-review-text">
                <h3><?php echo wp_kses_post( 'Enjoying Logo Showcase Free?', 'logo-showcase-free' ); ?></h3>
                <p><?php echo wp_kses_post( 'You have been using <b> Logo Showcase Free </b> for a while. Would you please show us a little love by rating us in the <a href="https://wordpress.org/support/plugin/logo-showcase-free/reviews/#new-post" target="_blank"><strong>WordPress.org</strong></a>?', 'logo-showcase-free' ); ?></p>
                <ul class="picklogo-review-ul">
                    <li><a href="https://wordpress.org/support/plugin/logo-showcase-free/reviews/#new-post" target="_blank"><span class="dashicons dashicons-external"></span><?php esc_html_e( 'Sure! I\'d love to!', 'logo-showcase-free' ); ?></a></li>
                    <li><a href="#" class="notice-dismiss"><span class="dashicons dashicons-smiley"></span><?php esc_html_e( 'I\'ve already left a review', 'logo-showcase-free' ); ?></a></li>
                    <li><a href="#" class="notice-dismiss"><span class="dashicons dashicons-dismiss"></span><?php esc_html_e( 'Never show again', 'logo-showcase-free' ); ?></a></li>
                 </ul>
            </div>
        </div>
        <style type="text/css">
            #picklogo-review-notice .notice-dismiss{
                padding: 0 0 0 26px;
            }
            #picklogo-review-notice .notice-dismiss:before{
                display: none;
            }
            #picklogo-review-notice.picklogo-review-notice {
                padding: 15px;
                background-color: #fff;
                border-radius: 3px;
                margin: 30px 20px 0 0;
                border-left: 4px solid transparent;
            }
            #picklogo-review-notice .picklogo-review-text {
                overflow: hidden;
            }
            #picklogo-review-notice .picklogo-review-text h3 {
                font-size: 24px;
                margin: 0 0 5px;
                font-weight: 400;
                line-height: 1.3;
            }
            #picklogo-review-notice .picklogo-review-text p {
                font-size: 15px;
                margin: 0 0 10px;
            }
            #picklogo-review-notice .picklogo-review-ul {
                margin: 0;
                padding: 0;
            }
            #picklogo-review-notice .picklogo-review-ul li {
                display: inline-block;
                margin-right: 15px;
            }
            #picklogo-review-notice .picklogo-review-ul li a {
                display: inline-block;
                color: #2271b1;
                text-decoration: none;
                padding-left: 26px;
                position: relative;
            }
            #picklogo-review-notice .picklogo-review-ul li a span {
                position: absolute;
                left: 0;
                top: -2px;
            }
        </style>
        <script type='text/javascript'>
            jQuery('body').on('click', '#picklogo-review-notice .notice-dismiss', function(e) {
                e.preventDefault();
                jQuery("#picklogo-review-notice").hide();

                wp.ajax.post('picklogo-dismiss-review-notice', {
                    dismissed: true,
                    _wpnonce: '<?php echo esc_attr( wp_create_nonce( 'picklogo_nonce' ) ); ?>'
                });
            });
        </script>
    <?php
}

add_action( 'admin_notices', 'picklogo_review_notice_message' );

// Dismiss Review Notice
function pic_logo_dismiss_review_notice() {
    if ( empty( $_POST['_wpnonce'] ) ) {
         wp_send_json_error( __( 'Unauthorized operation', 'logo-showcase-free' ) );
    }
    if ( isset( $_POST['_wpnonce'] ) && ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_wpnonce'] ) ), 'picklogo_nonce' ) ) {
        wp_send_json_error( __( 'Unauthorized operation', 'logo-showcase-free' ) );
    }
    if ( ! empty( $_POST['dismissed'] ) ) {
        update_option( 'picklogo_free_review_notice_dismiss', 'yes' );
    }
}
add_action( 'wp_ajax_picklogo-dismiss-review-notice', 'pic_logo_dismiss_review_notice' );