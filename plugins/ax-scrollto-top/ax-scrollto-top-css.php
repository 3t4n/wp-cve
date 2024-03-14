<?php
require( '../../../wp-load.php' );
header('Content-Type: text/css');
    $ax_icon_select      = get_option('ax_icon_select');
    $ax_width            = get_option('ax_width');
    $ax_height           = get_option('ax_height');
    $ax_background_color = get_option('ax_background_color');
    $ax_text_color       = get_option('ax_text_color');
    $ax_font_size        = get_option('ax_font_size');
    $ax_padding          = get_option('ax_padding');


	    if(!($ax_icon_select == 'none' && $ax_width && $ax_height && $ax_background_color && $ax_text_color && $ax_font_size && $ax_padding)){	
	    
		    $ax_width            = "auto";
	   	    $ax_height           = "auto";
    		    $ax_background_color = "#0064cd";
		    $ax_text_color       = "#ffffff";
		    $ax_font_size        = "13";
    		    $ax_padding          = "5";
    	    

	    } 
?>
.axScrollToTop {
   background-color:<?php echo $ax_background_color;?>;
   width:<?php echo $ax_width; ?>px;
   height:<?php echo $ax_height; ?>px;
   
}
.axScrollToTop{
	display:block;
	float:right;
	position:fixed;
	z-index:999999;
	opacity:0.8;
   	-webkit-border-radius: 4px;
  	-moz-border-radius: 4px;
  	border-radius: 4px;
  	font-size: <?php echo $ax_font_size; ?>px;
	padding:<?php echo $ax_padding; ?>px;
	text-align:center;

}

.axScrollToTop:hover{
	opacity:1;

}

.axScrollToTop img{
	float:right;
}

#axScrollTo:link, #axScrollTo:visited, #axScrollTo:active, #axScrollTo:hover{
	color:<?php echo $ax_text_color; ?>;
	text-decoration:none;
}
#axScrollTo{
	right:5px;
	bottom:5px;
}