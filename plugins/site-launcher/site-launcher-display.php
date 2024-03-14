<?php 
/*
 This file is part of Site Launcher.
 Site Launcher is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.
 Site Launcher is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with Site Launcher.  If not, see <http://www.gnu.org/licenses/>.
 */

function display_site_down_page( $options, $status, $path )
{
	if ( $status == 'coming_soon' ) {
		$background_color	=		$options['background_color'];
		$background_image	=		$options['background_image'];
		$background_repeat	=		$options['background_repeat'];
		$message_text		=		$options['message_text'];
		$fine_print		=		$options['fine_print'];
		$show_message_box	=		$options['show_message_box'];
		$text_color		=		$options['text_color'];
		$message_box_width	=		$options['message_box_width'];
		$message_box_opacity	=		$options['message_box_opacity'];
		$message_box_border	= 		$options['message_box_border'];
		$font			=		$options['font'];
		$show_login		=		$options['show_login'];
		$login_message		=		$options['login_message'];
	} elseif  ( $status == 'site_suspended' ) {
		$background_color	=		$options['background_color_suspended'];
		$background_image	=		$options['background_image_suspended'];
		$background_repeat	=		$options['background_repeat_suspended'];
		$message_text		=		$options['message_text_suspended'];
		$fine_print		=		$options['fine_print_suspended'];
		$show_message_box	=		$options['show_message_box_suspended'];
		$text_color		=		$options['text_color_suspended'];
		$message_box_width	=		$options['message_box_width_suspended'];
		$message_box_opacity	=		$options['message_box_opacity_suspended'];
		$message_box_border	= 		$options['message_box_border_suspended'];
		$font			=		$options['font_suspended'];
		$show_login		=		$options['show_login_suspended'];
		$login_message		=		$options['login_message_suspended'];
	} else {
		return;
	}

	$fine_print = str_replace( '\"', '"', $fine_print );
	
	if ( $message_box_border == '3-d' )
	{
		$message_box_border_string = '6px ridge '.$background_color;
	}
	elseif ( $message_box_border == 'simple' )
	{
		$message_box_border_string = '6px double '.$background_color;
	}
	else
	{
		$message_box_border_string = 'none';	
	}


?>
 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	<!-- -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <?php if ( $status == 'coming_soon' ) { ?>
        <meta name="author" content="WordPress Site Launcher Plugin">
        <?php } ?>
        <link href='http://fonts.googleapis.com/css?family=Special+Elite|Playfair+Display|Griffy|Indie+Flower|Open+Sans|Poiret+One|Philosopher|Orbitron|Patua+One|Limelight|Ubuntu|Roboto|Raleway|Roboto+Slab' rel='stylesheet' type='text/css'>
        <title>
           <?php bloginfo( 'name' ); ?>
        </title>
	<style type="text/css">
		body {
		background-color: <?php echo $background_color; ?>;
		<?php if ( strpos( $background_image, '.jpg' ) || strpos( $background_image, '.png' ) || strpos( $background_image, '.gif' ) ) { ?>
		background-image: url(<?php echo $path.'images/full/'.$background_image; ?>);
		background-position: center top;
		background-repeat: no-repeat;
		background-size: 100% auto;
		<?php } else { ?>
		background-image: none;
		<?php } ?>
		color: <?php echo $text_color; ?>;
		}

		body, h1, p {
		font-family: <?php echo $font; ?>;
		font-weight: normal;
		margin: 0;
		padding: 0;
		text-align: center;
		}
		
		.container {
		<?php if ( $show_message_box == 0) { ?>
			display: none;
		<?php } else { ?>
			display: block;
		<?php } ?>
		max-width: 80%;
		width:  <?php echo $message_box_width; ?>;
		padding: 70px 40px;
		<?php if ( $status == 'coming_soon' ) { ?>
			background-color: rgba(255,255,255, <?php echo $message_box_opacity; ?>);
		<?php } else { ?>
			background-color: rgba(0,0,0, <?php echo $message_box_opacity; ?>);		
		<?php } ?>
		margin: 90px auto;
		border: <?php echo $message_box_border_string; ?>;
		}

		h1 {
		font-size: 48px;
		font-weight: normal;
		margin: 0 0 20px 0;
		}

		p {
		font-size: 22px;
		font-weight: normal;
		margin-bottom: 20px;
		margin: 0 0 10px;
		}

		p.fineprint {
		font-size: 22px;
		}
		
		a {
		color: <?php echo $text_color; ?>;
		}
		
	</style>
	
	<!-- show vertical slice of background for phones in portrait orientation -->
	<script language="javascript" type="text/javascript">
		function resizeBackground()
		{
			if (document.body.clientWidth < 600)
			{
				document.body.style.backgroundSize =  '300% auto';
			}
			else if (document.body.clientWidth < 800)
			{
				document.body.style.backgroundSize =  '200% auto';
			}
			else if (document.body.clientWidth < 1000)
			{
				document.body.style.backgroundSize =  '150% auto';
			}
			else
			{
				document.body.style.backgroundSize =  '100% auto';
			}
		}
	</script>

    </head>
    <body onLoad="resizeBackground()" onResize="resizeBackground()">
	<div class="container">
		<h1><?php bloginfo( 'name' ); ?> <?php echo $message_text; ?></h1>
		<p class="fineprint"><?php echo $fine_print; ?></p>
		<?php if ( $show_login != 0 ) { ?>
		<br /><p><?php echo $login_message; ?></p>
		<div style="width:400px; margin:10px auto;"><?php wp_login_form( ); ?> </div>
		<?php } ?>
	</div>
    </body>
</html>
<?php 
}

