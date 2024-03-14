<?php 
//header("Content-type:text/css");
/**
 * Stylesheet for tgchannel widget
 *
 * @version 2017-12-12
 */
$size = get_option('TG_Channel_body_size'); 
?>


 <style type="text/css">
<?php
if ($size == "small"){
?>
.tgchannelwdgbody {
    min-width: 200px;
    min-height: 100px;
    width: 200px !important;
    height: <?php echo get_option('TG_Channel_Body_height');?> !important;
}
	
#insidertg {
    transform: scale(0.6);
    margin-bottom: -500px;
	    margin-top: -500px;
    margin-left: -27%;
}

#insidetgbox{
    transform: scale(0.6);
    margin-bottom: -500px;
	    margin-top: -500px;
    margin-left: -27%;
}	
<?php
}
?>


<?php
if ($size == "medium"){
?>
.tgchannelwdgbody {
    min-width: 265px;
    min-height: 100px;
    width: 265px !important;
    height: <?php echo get_option('TG_Channel_Body_height');?> !important;
}
	
#insidertg {
    transform: scale(0.8);
    margin-bottom: -500px;
    margin-left: -11%;
}

#insidetgbox{
    transform: scale(0.8);
    margin-bottom: -500px;
    margin-left: -11%;
}	
<?php
}
?>


<?php
if ($size == "custom"){
?>
	.tgchannelwdgbody{
	min-width:310px;	
	min-height:100px;	
	width :	<?php echo get_option('TG_Channel_Body_width');?> !important;	
	height : <?php echo get_option('TG_Channel_Body_height');?> !important;	
	}		
<?php
}
?>


 h1, h2, h3, h4, h5, h6 {
	    font-family: "Segoe UI",Arial,sans-serif;
	    font-weight: 400;
	    margin: 10px 0;
	    font-size: 15px;
	}

	#tg_short_joinchannel {			
    display: block;
    position: initial;
    left: 0px;
    width: 100%;
    height: <?php echo get_option('TG_Channel_Footer_Height'); ?> !important;
    text-align: center;
    font-size: 10px;
    background: <?php echo get_option('TG_Channel_Footer_color'); ?>;
    margin-top: 0px;
    border: solid;
    border-width: 1px;
    border-color: #ececec;
	}
	
	#joinchannel {	
    display: block;
    position: initial;
    left: 0px;
    width: 100%;
    height: <?php echo get_option('TG_Channel_Footer_Height'); ?> !important;
    text-align: center;
    font-size: 10px;
    background: <?php echo get_option('TG_Channel_Footer_color'); ?>;
    margin-top: 0px;
    border: solid;
    border-width: 1px;
    border-color: #ececec;
	}
	
	
	#joinchannel a{
	color : <?php echo get_option('TG_Channel_Footer_Font_color'); ?>;	 
		font-weight: bold;
		font-size:  <?php echo get_option('TG_Channel_Footer_Font_size'); ?>;	
		 text-decoration:none;
	}
	
	
	#tg_short_joinchannel a{
	color : <?php echo get_option('TG_Channel_Footer_Font_color'); ?>;	 
		font-weight: bold;
		font-size:  <?php echo get_option('TG_Channel_Footer_Font_size'); ?>;	
		 text-decoration:none;
	}	
	
	
	#tgwidgetbox {
		    direction: ltr;
    overflow: auto;
    overflow-y: scroll;
    overflow-x: hidden;
    height: 85%;
    width: 100%;
    border-left: solid;
    border-right: solid;
    border-left-width: 1px;
	border-right-width: 1px;
    border-color: #f1f1f1;
			background:url("<?php echo get_option('TG_Channel_background'); ?>") <?php echo get_option('TG_Channel_background_color'); ?>;
	}


	
	#tgbox {
		    direction: ltr;
    overflow: auto;
    overflow-y: scroll;
    overflow-x: hidden;
    height: 85%;
	width: 100%;
    border-left: solid;
    border-right: solid;
    border-left-width: 1px;
	border-right-width: 1px;
    border-color: #f1f1f1;
		background:url("<?php echo get_option('TG_Channel_background'); ?>") <?php echo get_option('TG_Channel_background_color'); ?>;
	}
	
	#tgwidget{
	color : <?php echo get_option('TG_Channel_Header_Font_color'); ?>;	
    display: block;
    position: initial;
    left: 0px;
    width: 100%;
    height: <?php echo get_option('TG_Channel_Header_Height'); ?> !important;
    line-height: 2;
    text-align: center;	
    background: <?php echo get_option('TG_Channel_Header_color'); ?>;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
	} 

	#tg_short_head{
	color : <?php echo get_option('TG_Channel_Header_Font_color'); ?>;			
    display: block;
    position: initial;
    left: 0px;
    width: 100%;
    height: <?php echo get_option('TG_Channel_Header_Height'); ?> !important;	
    line-height: 2;
    text-align: center;
    background: <?php echo get_option('TG_Channel_Header_color'); ?>;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
	} 
	
	#tgwidget h3{
    font-size: <?php echo get_option('TG_Channel_Header_Font_size'); ?> !important;	
    }
	
	#tg_short_head h3{
    font-size: <?php echo get_option('TG_Channel_Header_Font_size'); ?> !important;	
	}	
	
	

	
	
	
	/* Let's get this party started */
	#tgbox::-webkit-scrollbar {
	    width: 6px;
	}
	 
	
	
	#tgbox::-webkit-scrollbar-thumb {
	    -webkit-border-radius: 10px;
	    border-radius: 2px;
	    background: rgb(48, 136, 207);
	    -webkit-tgbox-shadow: inset 0 0 6px rgba(0,0,0,0);
	}
	
	
	#tgbox::-webkit-scrollbar-track {
	   /* -webkit-tgbox-shadow: inset 0 0 3px rgba(3, 6, 5, 0.2); */
	    -webkit-border-radius: 0;
	    border-radius: 0;
	}
	#tgbox::-webkit-scrollbar-thumb:window-inactive {
		background: rgba(255,0,0,0.4); 
	}

    	/* Let's get this party started */
	#tgwidgetbox::-webkit-scrollbar {
	    width: 6px;
	}
	 
	
	
	#tgwidgetbox::-webkit-scrollbar-thumb {
	    -webkit-border-radius: 10px;
	    border-radius: 2px;
	    background: rgb(48, 136, 207);
	    -webkit-tgbox-shadow: inset 0 0 6px rgba(0,0,0,0);
	}
	
	
	#tgwidgetbox::-webkit-scrollbar-track {
	   /* -webkit-tgbox-shadow: inset 0 0 3px rgba(3, 6, 5, 0.2); */
	    -webkit-border-radius: 0;
	    border-radius: 0;
	}
	#tgwidgetbox::-webkit-scrollbar-thumb:window-inactive {
		background: rgba(255,0,0,0.4); 
	}
</style>

	
