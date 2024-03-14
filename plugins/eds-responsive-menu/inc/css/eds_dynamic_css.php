<?php
	header("Content-type: text/css");
	$absolute_path = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
	$wp_load = $absolute_path[0] . 'wp-load.php';
	require_once($wp_load);
	
	$eds_options=egs_get_all_option('_eds_Options' );
	if(count($eds_options)>0):
	
	
	
	function hex2rgba($color, $opacity = false) {
 
	$default = 'rgb(0,0,0)';
 
	//Return default if no color providedli.menu-item-has-children a i.eds-arrows::after, li.menu-item-has-children .eds-arrows-back::after
	if(empty($color))
          return $default; 
 
	//Sanitize $color if "#" is provided 
        if ($color[0] == '#' ) {
        	$color = substr( $color, 1 );
        }
 
        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }
 
        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);
 
        //Check if opacity is set(rgba or rgb)
        if($opacity){
        	if(abs($opacity) > 1)
        		$opacity = 1.0;
        	$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
        	$output = 'rgb('.implode(",",$rgb).')';
        }
 
        //Return rgb(a) color string
        return $output;
}
?>

.eds-lines {
    background:<?php echo $eds_options['symbol_color'];?>;
    width: <?php echo ($eds_options['toggle_width']-10);?>px; 
    top:50%;
    height:<?php echo $eds_options['symbol_line_height'];?>px;
    margin-top:-<?php echo $eds_options['symbol_line_height']/2;?>px;
}
.eds-lines:before, .eds-lines:after {
    background:<?php echo $eds_options['symbol_color'];?>;
    width: <?php echo ($eds_options['toggle_width']-10);?>px;
    height:<?php echo $eds_options['symbol_line_height'];?>px;
   
}
    <?php $toggle_height=($eds_options['toggle_height'])/3;?>
.eds-lines:before {
    top: <?php echo ($toggle_height-$eds_options['symbol_line_height'])-1;?>px; }
    .eds-lines:after {
    top: -<?php echo ($toggle_height-$eds_options['symbol_line_height'])-1;?>px; }
    
    
.eds-toggle-icon {
	background:<?php echo $eds_options['symbol_bg'];?>;
    height:<?php echo ($eds_options['toggle_height']);?>px;
    <?php if($eds_options['toggle_position']=='left'){
		echo 'left:'.$eds_options['toggle_margin_left_right'].'px;';
	}else{
		echo 'right: '.$eds_options['toggle_margin_left_right'].'px;';
	}
	?>
     width: <?php echo ($eds_options['toggle_width']+10);?>px;
     top:<?php echo ($eds_options['toggle_margin_top']);?>px;
     <?php if($eds_options['toggle_type']=='absolute'){?>
        position: absolute;
        <?php }?>
     
}
.eds-toggle-icon i,.eds-toggle-icon:hover i{
	font-size:<?php echo ($eds_options['toggle_width']-8);?>px;
    color:<?php echo $eds_options['symbol_color'];?>;
    line-height:<?php echo ($eds_options['toggle_width']+2);?>px;
}
.eds-lines-button {
	height:<?php echo ($eds_options['toggle_height']);?>px;
}

.eds-lines-button.minus.close .eds-lines:before, .eds-lines-button.minus.close .eds-lines:after {
  width: <?php echo ($eds_options['toggle_width']-10);?>px; 
  }

.eds-lines-button.x.close .eds-lines:before, .eds-lines-button.x.close .eds-lines:after {
    width: <?php echo ($eds_options['toggle_width']-10);?>px; 
  }
.eds-toggle-icon .close .eds-lines:before, .close .eds-lines:after {
    color:<?php echo $eds_options['symbol_color'];?>;
    background:<?php echo $eds_options['symbol_color'];?>;
}

<?php
/* Responsive Menu :- 1 */

?>
<?php  $transition=$eds_options['animation_speed']/1000;?>
.sidr {
	<?php if($eds_options['menu_wrp_bg']['image']!=""):?>
    	background:url(<?php echo $eds_options['menu_wrp_bg']['image'];?>)  <?php echo $eds_options['menu_wrp_bg']['repeat'];?>  <?php echo $eds_options['menu_wrp_bg']['position'];?>  <?php echo $eds_options['menu_wrp_bg']['attachment'];?>
    	<?php echo $eds_options['menu_wrp_bg']['color'];?>
    <?php else: ?>
		<?php if($eds_options['menu_wrp_bg']['color']!=""):?>
             background:<?php echo hex2rgba($eds_options['menu_wrp_bg']['color'],$eds_options['opacity']);?>;
        <?php endif;?>
    <?php endif;?>
}
<?php
	$padding=str_replace(array('px',','),'px ', $eds_options['seperator']);
?>
ul.eds-responsive-menu li a,ul.eds-responsive-menu li li.back-pre-nav a{
	font-size:<?php echo $eds_options['font_size'];?>px;
	padding:<?php echo $padding;?>px;
    line-height:<?php echo $eds_options['line_height'];?>px;
    font-family: '<?php echo $eds_options['font_family']['family'];?>';
    font-style: <?php echo $eds_options['font_family']['variant'];?>;
    color:<?php echo $eds_options['menu_text_color'];?>;
    <?php if($eds_options['transparent']==true){?>
    background:<?php echo hex2rgba($eds_options['menu_bg_color'],$eds_options['opacity']);?>;
	<?php }else{ ?>
    background:<?php echo $eds_options['menu_bg_color'];?>;
    <?php }?>
    border-bottom:<?php echo $eds_options['boder_size'];?>px solid <?php echo $eds_options['menu_boder_bottom'];?>;
    
      <?php if($eds_options['somoot_hover']==true){?>
    -webkit-transition: all <?php echo $transition;?>s ease-in-out;
	-moz-transition: all <?php echo $transition;?>s ease-in-out;
	-o-transition: all <?php echo $transition;?>s ease-in-out;
	-ms-transition: all <?php echo $transition;?>s ease-in-out;
	transition: all <?php echo $transition;?>s ease-in-out;
     <?php }?>
}
ul.eds-responsive-menu li a:hover,ul.eds-responsive-menu li.current_page_item > a{
	color:<?php echo $eds_options['menu_text_color_hover'];?>;
    <?php if($eds_options['transparent']==true){?>
    background:<?php echo hex2rgba($eds_options['menu_bg_color_hover'],$eds_options['opacity']);?>;
	<?php }else{ ?>
    background:<?php echo $eds_options['menu_bg_color_hover'];?>;
    <?php }?>   
}

<?php
	$sub_menu_padding=str_replace(array('px',','),'px ', $eds_options['sub_seperator']);
?>
ul.eds-responsive-menu li li a{
	font-size:<?php echo $eds_options['sub_font_size'];?>px;
	padding:<?php echo $sub_menu_padding;?>px;
    color:<?php echo $eds_options['sub_menu_text_color'];?>;
    <?php if($eds_options['transparent']==true){?>
    background:<?php echo hex2rgba($eds_options['sub_menu_bg_color'],$eds_options['opacity']);?>;
	<?php }else{ ?>
    background:<?php echo $eds_options['sub_menu_bg_color'];?>;
    <?php }?>
    border-bottom:<?php echo $eds_options['sub_menu_boder_size'];?>px solid <?php echo $eds_options['sub_menu_boder_bottom'];?>;
    line-height:<?php echo $eds_options['sub_line_height'];?>px;
}
ul.eds-responsive-menu li li a:hover,ul.eds-responsive-menu li li.current_page_item > a{
        color:<?php echo $eds_options['sub_menu_text_color_hover'];?>;
        <?php if($eds_options['transparent']==true){?>
        background:<?php echo hex2rgba($eds_options['sub_menu_bg_color_hover'],$eds_options['opacity']);?>;
        <?php }else{ ?>
        background:<?php echo $eds_options['sub_menu_bg_color_hover'];?>;
        <?php }?>
}
<?php
	$sub_expolde=explode(',',$eds_options['sub_seperator']);
	$sub_expolde[3]=($sub_expolde[3]*2)/3 *2;
	$sub_sub_menu_padding=implode('px ',$sub_expolde);
?>
ul.eds-responsive-menu ul ul a{
	padding:<?php echo $sub_sub_menu_padding;?>px;
}
<?php $sub_icon=explode(',',$eds_options['seperator']);?>
li.menu-item-has-children a i.eds-arrows:after,li.menu-item-has-children .eds-arrows-back:after{
	line-height:<?php echo $eds_options['line_height']+$sub_icon[0]+$sub_icon[2];?>px;
    color:<?php echo $eds_options['arrows_color'];?>;
    background:<?php echo $eds_options['arrows_bg'];?>;
    bottom:1px;
}
<?php $sub_sub_icon=explode(',',$eds_options['sub_seperator']);?>
li.menu-item-has-children li a i.eds-arrows:after{
	line-height:<?php echo $eds_options['sub_line_height']+$sub_sub_icon[0]+$sub_sub_icon[2];?>px;
       color:<?php echo $eds_options['arrows_color'];?>;
    background:<?php echo $eds_options['arrows_bg'];?>;
}

ul.move-out,#eds_drop_down_menu,#eds_down_up_menu,ul.eds-responsive-menu,ul.eds-responsive-menu li ul{
	-webkit-transition: all <?php echo $transition;?>s ease-in-out;
	-moz-transition: all <?php echo $transition;?>s ease-in-out;
	-o-transition: all <?php echo $transition;?>s ease-in-out;
	-ms-transition: all <?php echo $transition;?>s ease-in-out;
	transition: all <?php echo $transition;?>s ease-in-out;
    
 }
 ul.eds-responsive-menu ul.sub-menu.accordion_drop_down{
 -webkit-transition: all 0s ease-in-out;
	-moz-transition: all 0s ease-in-out;
	-o-transition: all 0s ease-in-out;
	-ms-transition: all 0s ease-in-out;
	transition: all 0s ease-in-out;
 }
 .sidr {
        width: <?php echo $eds_options['menu_width'];?>%;
        max-width:<?php echo $eds_options['max_width'];?>px;
        min-width:<?php echo $eds_options['min_width'];?>px;
 }
 #eds_simply_drop_down{
    top:<?php echo ($eds_options['toggle_height']+$eds_options['toggle_margin_top']+2);?>px;
    <?php if($eds_options['toggle_position']=='left'){
		echo 'left:'.$eds_options['toggle_margin_left_right'].'px;';
	}else{
		echo 'right: '.$eds_options['toggle_margin_left_right'].'px;';
	}
	?>
     width: <?php echo ($eds_options['toggle_width']+10);?>px;
 }
 
 .eds-social-profile a{
 	<?php if($eds_options['eds_icon_type']=='round'){?>
    -webkit-border-radius:50%;
    -moz-border-radius:50%;
    border-radius:50%;
    <?php }?>
    background:<?php echo $eds_options['eds_social_profile_bg'];?>;
    color:<?php echo $eds_options['eds_social_profile_color'];?>;
 }
  #simple-menu.eds-toggle-icon,.eds-responsive-menu-wrp.sidr{
	display:none;	
}
 @media only screen and (min-width : 320px) and (max-width :<?php echo $eds_options['eds_menu_breakpoint'];?>px) {
        #simple-menu.eds-toggle-icon,.eds-responsive-menu-wrp.sidr{
        	display:block!important;	
        }
		 <?php if($eds_options['eds_elements_hide'] !=""){?>
		<?php echo $eds_options['eds_elements_hide'];?>{
             display:none!important;
             visibility:hidden;
        }
        <?php }?>
        
 }
 
 <?php endif;?>
