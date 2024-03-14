<style>
#scr_wrapper{
	margin: 10px;	
	max-width: 120px;
	max-height: 175px;
	position:fixed;
	bottom:0%;
	left:<?php echo $scr_position;?>;
	display:none;
	z-index:99999;

}
#pre_des_icons{
	width:<?php echo $scr_width;?>px; 
	height:<?php echo $scr_height;?>px;
    display:<?php echo $activate_pre_icon;?>;	     	
}

.scr_container{
		border-radius:<?php echo $scr_border_radius;?>;
	width:<?php echo $scr_width;?>; 
	height:<?php echo $scr_height;?>;
	text-align:center;
	background-color:<?php echo $scr_background_color;?>;
	display: <?php echo $activate_text_icon;?>;	 
}
.scr_container a {
	text-align: center;
	text-decoration: none;
	font-family: verdana,sans-serif,arial;
	padding:0;
	text-align: center;
    margin: 0; 
}
.scr_container a:hover{
	text-decoration: none;
	color:#fff;
}



.scr_icon{
   color:<?php echo $scr_color;?>;
   font-size:<?php echo $scr_font_size;?>;
   text-align:center;
   margin:0 auto;margin-top:0;
   margin-left:0;
   position: relative;
   top:20%;
   font-family:verdana;
   font-style:bold;

}

.scr_container:hover  .scr_icon{

    color:;
	transition:color 1s ease-in-out;
	-webkit-transition:color 1s ease-in-out;
	-o-transition:color 1s ease-in-out;
	-moz-transition:color 1s ease-in-out;
	 
}  



</style>