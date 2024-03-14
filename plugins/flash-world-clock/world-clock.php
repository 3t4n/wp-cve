<?php
/*
  Plugin Name: Flash World Clock 
  Description: World clock showing the local time at six major cities round the world. The plugin provides a choice of analog and digital clocks, colors and sizes.
  Author: enclick
  Version: 1.1
  Author URI: http://localtimes.info
  Plugin URI: http://localtimes.info/wordpress-world-clock-plugin/
*/




function world_clock_init() 
{

	#     if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
	#    	   return; 

    function world_clock_control() 
    {


        $newoptions = get_option('world_clock');
    	$options = $newoptions;
		$options_flag=0;

    	if ( empty($newoptions) )
		{
			$options_flag=1;
			$newoptions = array(
				'title'=>'World Clock',
				'titleflag'=>'0', 
				'transparentflag'=>'0', 
				'ampmflag'=>'1', 
				'size' => '100',
				'type' => '1',
				'typeflag' => '1',
				'text_color' => '#000000',
				'border_color' => '#963939',
				'background_color' => '#FFFFFF',
				'orientation' => '0',
				'capital_group' => '0'
				);
		}

		if ( $_POST['world-clock-submit'] ) {
			$options_flag=1;
			$newoptions['title'] = strip_tags(stripslashes($_POST['world-clock-title']));
			$newoptions['titleflag'] = strip_tags(stripslashes($_POST['world-clock-titleflag']));
			$newoptions['transparentflag'] = strip_tags(stripslashes($_POST['world-clock-transparentflag']));
			$newoptions['ampmflag'] = strip_tags(stripslashes($_POST['world-clock-ampmflag']));
			$newoptions['size'] = strip_tags(stripslashes($_POST['world-clock-size']));
			$newoptions['type'] = strip_tags(stripslashes($_POST['world-clock-type']));
			$newoptions['typeflag'] = strip_tags(stripslashes($_POST['world-clock-typeflag']));
			$newoptions['text_color'] = strip_tags(stripslashes($_POST['world-clock-text-color']));
			$newoptions['border_color'] = strip_tags(stripslashes($_POST['world-clock-border-color']));
			$newoptions['background_color'] = strip_tags(stripslashes($_POST['world-clock-background-color']));
			$newoptions['capital_group'] = strip_tags(stripslashes($_POST['world-clock-capital-group']));
			$newoptions['orientation'] = strip_tags(stripslashes($_POST['world-clock-orientation']));
        }

      	if ( $options_flag ==1 ) {
			$options = $newoptions;
			update_option('world_clock', $options);
      	}


      	// Extract value from vars
      	$title = htmlspecialchars($options['title'], ENT_QUOTES);
      	$titleflag = htmlspecialchars($options['titleflag'], ENT_QUOTES);
      	$transparent_flag = htmlspecialchars($options['transparentflag'], ENT_QUOTES);
      	$size = htmlspecialchars($options['size'], ENT_QUOTES);
      	$type = htmlspecialchars($options['type'], ENT_QUOTES);
      	$typeflag = htmlspecialchars($options['typeflag'], ENT_QUOTES);
      	$ampmflag = htmlspecialchars($options['ampmflag'], ENT_QUOTES);
      	$text_color = htmlspecialchars($options['text_color'], ENT_QUOTES);
      	$border_color = htmlspecialchars($options['border_color'], ENT_QUOTES);
      	$background_color = htmlspecialchars($options['background_color'], ENT_QUOTES);
      	$capital_group_flag = htmlspecialchars($options['capital_group'], ENT_QUOTES);
      	$orientation_flag = htmlspecialchars($options['orientation'], ENT_QUOTES);

		// REMOVING LINK //
      	echo '<ul>';

      	// Set Clock size
		echo "\n";
      	echo '<li style="list-style: none;"><label for="world-clock-size">'.'Clock Size: &nbsp;'.
			'<select id="world-clock-size" name="world-clock-size"  style="width:75px">';
      	print_wthesize_list($size);
      	echo '</select></label></li>';

      	// Set clock type
      	echo '<li style="list-style: none;"><label for="world-clock-type">'.'Clock Type:&nbsp;';
       	echo '<select id="world-clock-type" name="world-clock-type"  style="width:75px" >';
      	print_wtype_list($type);
      	echo '</select></label>';
      	echo '</li>';

      	// Set clock orientation
      	echo '<li style="list-style: none;"><label for="world-clock-orientation">'.'Orientation:&nbsp;';
       	echo '<select id="world-clock-orientation" name="world-clock-orientation"  style="width:90px" >';
      	print_worientation_list($orientation_flag);
      	echo '</select></label>';
      	echo '</li>';

      	// Set capital group
      	echo '<li style="list-style: none;"><label for="world-clock-capital-group">'.'Capital Group:&nbsp;';
       	echo '<select id="world-clock-capital-group" name="world-clock-capital-group"  style="width:100px" >';
      	print_wcapital_list($capital_group_flag);
      	echo '</select></label>';
      	echo '</li>';


      	// Set Text Clock color
      	echo '<li style="list-style: none;"><label for="world-clock-text-color">'.'Text Color:&nbsp;';
       	echo '<select id="world-clock-text-color" name="world-clock-text-color"  style="width:75px" >';
      	print_wtextcolor_list($text_color);
      	echo '</select></label>';
      	echo '</li>';

      	// Set Background Clock color
      	echo '<li style="list-style: none;"><label for="world-clock-background-color">'.'Background Color:&nbsp;';
       	echo '<select id="world-clock-background-color" name="world-clock-background-color"  style="width:75apx" >';
      	print_wbackgroundcolor_list($background_color);
      	echo '</select></label>';
      	echo '</li>';

		//   Transparent option

		$transparent_checked = "";
		if ($transparent_flag =="1")
			$transparent_checked = "CHECKED";

		echo "\n";
        echo '<li style="list-style: none;"><label for="world-clock-transparentflag"> Transparent: 
	<input type="checkbox" id="world-clock-transparentflag" name="world-clock-transparentflag" value=1 '.$transparent_checked.' /> 
	</label></li>';

		//   ampm option

		$ampm_checked = "";
		if ($ampmflag =="1")
			$ampm_checked = "CHECKED";

		echo "\n";
        echo '<li style="list-style: none;"><label for="world-clock-ampmflag"> am/pm format: 
	<input type="checkbox" id="world-clock-ampmflag" name="world-clock-ampmflag" value=1 '.$ampm_checked.' /> 
	</label></li>';


      	// Hidden "OK" button
      	echo '<label for="world-clock-submit">';
      	echo '<input id="world-clock-submit" name="world-clock-submit" type="hidden" value="Ok" />';
      	echo '</label>';

		$title ="World Clock";

        echo '<label for="world-clock-title"> <input type="hidden" id="world-clock-title" name="world-clock-title" value="'.$title.'" /> </label>';

		$title_checked = "";
		if ($titleflag =="1")
			$title_checked = "CHECKED";

		echo "\n";
        echo '<li style="list-style: none;"><label for="world-clock-titleflag"> Clock Title: 
	<input type="checkbox" id="world-clock-titleflag" name="world-clock-titleflag" value=1 '.$title_checked.' /> 
	</label></li>';

		echo "</ul>";
    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //	OUTPUT CLOCK WIDGET
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////

	function world_clock($args) 
	{
		// Get values 
      	extract($args);

      	$options = get_option('world_clock');

		// Get Title,Location,Size,
      	$title = htmlspecialchars($options['title'], ENT_QUOTES);
      	$titleflag = htmlspecialchars($options['titleflag'], ENT_QUOTES);
      	$transparent_flag = htmlspecialchars($options['transparentflag'], ENT_QUOTES);
      	$size = htmlspecialchars($options['size'], ENT_QUOTES);
      	$type = htmlspecialchars($options['type'], ENT_QUOTES);
      	$typeflag = htmlspecialchars($options['typeflag'], ENT_QUOTES);
      	$ampmflag = htmlspecialchars($options['ampmflag'], ENT_QUOTES);
      	$text_color = htmlspecialchars($options['text_color'], ENT_QUOTES);
      	$border_color = htmlspecialchars($options['border_color'], ENT_QUOTES);
      	$background_color = htmlspecialchars($options['background_color'], ENT_QUOTES);
      	$capital_group_flag = htmlspecialchars($options['capital_group'], ENT_QUOTES);
      	$orientation_flag = htmlspecialchars($options['orientation'], ENT_QUOTES);


		echo $before_widget; 




		// Output title
		echo $before_title . $after_title; 


		// Output Clock

		if($orientation_flag == 0)
			$widget_number = 10000;
		else
			$widget_number = 20000;

		// analog/digital clocks 
		if($type == 1){
			$widget_number += 1000;	// digital
			if($orientation_flag == 1) 
				$factor = 7;
			else
				$factor = 2.3;
		}
		else{
			$widget_number += 100;	// analog
			if($orientation_flag == 1) 
				$factor = 7.1;
			else
				$factor = 1.2;
		}




		// capital group 
      	$widget_number += $capital_group_flag;

		$widget_call_string = 'https://localtimes.info/wp_world_clock.php?';
		$widget_call_string .= 'widget_number='.$widget_number;

		$transparent_string = "&hbg=0";
		if($transparent_flag == 1){
     	    $transparent_string = "&hbg=1";
     	    $background_color=""; 
		}

		$ampm_string = "&ham=1";
		if($ampmflag == 1)
			$ampm_string = "&ham=0";


		if($titleflag != 1){
			$noscript_start = "<noscript>";
			$noscript_end = "</noscript>";
		}

		$target_url = "https://localtimes.info/difference/";

		//
		//
		//
		echo '<br style="line-height:15px">';	
		echo'<!--World Clock widget - HTML code - localtimes.info --><div style="align:center;width: auto;text-align:center;background:'.$background_color.' ;';
		echo 'color:'.$text_color.';width:'.($size*$factor).'px;padding:8px 8px;">' ;

		echo $noscript_start . '<div style="align:center;text-align:center;width:auto;border:0;margin-bottom:5px;background:'.$background_color.' ;color:'.$text_color.' ;font-weight:bold">';
		echo '<a style="padding:2px 1px;margin:2px 1px;font-size:13px;line-height:16px;font-family:arial;text-decoration:none;color:'.$text_color. ' ;" href="'.$target_url.'">';
		echo '&nbsp;&nbsp;'.$title.'</a></div>' . $noscript_end;

		$text_color = str_replace("#","",$text_color);
		$background_color = str_replace("#","",$background_color);
		$border_color = str_replace("#","",$border_color);


		$widget_call_string .= '&cp3_Hex='.$border_color.'&cp2_Hex='.$background_color.'&cp1_Hex='.$text_color. $transparent_string . $ampm_string. '&fwdt='.$size;

		#print $widget_call_string;
		echo '<script type="text/javascript" src="'.$widget_call_string . '"></script></div><!-end of code-->';

		echo $after_widget;



    }
  
    register_sidebar_widget('World Clock', 'world_clock');
    register_widget_control('World Clock', 'world_clock_control', 245, 300);


}


add_action('plugins_loaded', 'world_clock_init');


// This function print for selector clock color list
include("world_functions.php");


?>