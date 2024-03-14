<?php

if (!defined("ABSPATH")) exit; // Exit if accessed directly
/** 

 * Admin Page

**/

?>

<div class="wrap"><br/>
<h1> Top Scroller Settings <font size="2"> v1.0.0</font></h1>

<?php

	/****

	* Save button is clicked
  	**/

  	$Totop_save = @$_POST['Totop_save'];
  	$Totop_save = wp_kses($Totop_save,array()); 

  	if( isset( $Totop_save )){

  		// nonce check

  		if( isset( $_POST['_wpnonce']) && $_POST['_wpnonce'] ) {

  			if( check_admin_referer('Scrolltotop_plugin','_wpnonce')){

  				// Post variables

  				// for btn speed
  				$scroll_to_top_speed = ( @$_POST['scroll_to_top_speed'] == 'fast' )? 'fast' : 'slow';

  				// Register to the database

  				update_option('scroll_to_top_speed',$scroll_to_top_speed);


  				// for btn color

  				$scroll_to_top_btn_color = sanitize_text_field($_POST['scroll_to_top_btn_color']);
  				
  				function btn_color_is_valid( $scroll_to_top_btn_color ) {
				    // Scenario 1: empty.
				    if ( empty( $scroll_to_top_btn_color ) ) {
				        return false;
				    }
				 
				    // Scenario 2: more than 10 characters.
				    if ( 7 < strlen( trim( $scroll_to_top_btn_color ) ) ) {
				        return false;
				    }
				 
				 
				    // Passed successfully.
				    return true;
				}

  				if (isset($_POST['scroll_to_top_btn_color']) && btn_color_is_valid($_POST['scroll_to_top_btn_color'])) {
				        
				        // Register to the database
  				 	update_option('scroll_to_top_btn_color',$scroll_to_top_btn_color);

				        }




  				// for btn hover Color

  				 $scroll_to_top_hvr_color = sanitize_text_field($_POST['scroll_to_top_hvr_color']);
  				
  				function hvr_color_is_valid( $scroll_to_top_hvr_color ) {
				    // Scenario 1: empty.
				    if ( empty( $scroll_to_top_hvr_color ) ) {
				        return false;
				    }
				 
				    // Scenario 2: more than 10 characters.
				    if ( 7 < strlen( trim( $scroll_to_top_hvr_color ) ) ) {
				        return false;
				    }
				 
				 
				    // Passed successfully.
				    return true;
				}

  				if (isset($_POST['scroll_to_top_hvr_color']) && hvr_color_is_valid($_POST['scroll_to_top_hvr_color'])) {
				        
				        // Register to the database
  				 	update_option('scroll_to_top_hvr_color',$scroll_to_top_hvr_color);

				        }
  		

  				// for icon

  				$scroll_to_top_icon = sanitize_text_field($_POST['scroll_to_top_icon']);
  				
  				function icon_is_valid( $scroll_to_top_icon ) {
				    // Scenario 1: empty.
				    if ( empty( $scroll_to_top_icon ) ) {
				        return false;
				    }
				 
				   if ( ! preg_match( '~\b(fa|fas|far)\b~i', $scroll_to_top_icon ) ) {
					        return false;
					    }
				 
				 
				    // Passed successfully.
				    return true;
				}

  				if (isset($_POST['scroll_to_top_icon']) && icon_is_valid($_POST['scroll_to_top_icon'])) {
				        
				        // Register to the database
  				 	update_option('scroll_to_top_icon',$scroll_to_top_icon);

				        }
  				

  				


  				// for value exclude

  				$scroll_to_top_value_exclude = sanitize_text_field($_POST['scroll_to_top_value_exclude']);
				        
				        // Register to the database
  				 	update_option('scroll_to_top_value_exclude',$scroll_to_top_value_exclude);


  			


  				// for icon size

  				$scroll_to_top_font_size = sanitize_text_field($_POST['scroll_to_top_font_size']);
  				
  				function font_size_is_valid( $scroll_to_top_font_size ) {
				    // Scenario 1: empty.
				    if ( empty( $scroll_to_top_font_size ) ) {
				        return false;
				    }
				 
				    // Scenario 2: more than 10 characters.
				    if ( 2 < strlen( trim( $scroll_to_top_font_size ) ) ) {
				        return false;
				    }
				 	
				 	if ( ! preg_match( '/^[1-9][0-9]*$/ ', $scroll_to_top_font_size ) ) {
					        return false;
					    }
				 
				 
				    // Passed successfully.
				    return true;
				}

  				if (isset($_POST['scroll_to_top_font_size']) && font_size_is_valid($_POST['scroll_to_top_font_size'])) {
				        
				        // Register to the database
  				 	update_option('scroll_to_top_font_size',$scroll_to_top_font_size);

				        }
  				


  				// for icon color
  				$scroll_to_top_icon_color = sanitize_text_field($_POST['scroll_to_top_icon_color']);
  				
  				function icon_color_is_valid( $scroll_to_top_icon_color ) {
				    // Scenario 1: empty.
				    if ( empty( $scroll_to_top_icon_color ) ) {
				        return false;
				    }
				 
				    // Scenario 2: more than 10 characters.
				    if ( 7 < strlen( trim( $scroll_to_top_icon_color ) ) ) {
				        return false;
				    }
				 
				 
				    // Passed successfully.
				    return true;
				}

  				if (isset($_POST['scroll_to_top_icon_color']) && icon_color_is_valid($_POST['scroll_to_top_icon_color'])) {
				        
				        // Register to the database
  				 	update_option('scroll_to_top_icon_color',$scroll_to_top_icon_color);

				        }



  				// for icon hover color
  				$scroll_to_top_hvr_icon_color = sanitize_text_field($_POST['scroll_to_top_hvr_icon_color']);
  				
  				function icon_hvr_color_is_valid( $scroll_to_top_hvr_icon_color ) {
				    // Scenario 1: empty.
				    if ( empty( $scroll_to_top_hvr_icon_color ) ) {
				        return false;
				    }
				 
				    // Scenario 2: more than 10 characters.
				    if ( 7 < strlen( trim( $scroll_to_top_hvr_icon_color ) ) ) {
				        return false;
				    }
				 
				 
				    // Passed successfully.
				    return true;
				}

  				if (isset($_POST['scroll_to_top_hvr_icon_color']) && icon_hvr_color_is_valid($_POST['scroll_to_top_hvr_icon_color'])) {
				        
				        // Register to the database
  				 	update_option('scroll_to_top_hvr_icon_color',$scroll_to_top_hvr_icon_color);

				        }

  			}
  		}
  	}

/***

	** Reciving the Data

***/	 

// registerd data

$scroll_to_top_speed = get_option('scroll_to_top_speed');
$scroll_to_top_btn_color = get_option('scroll_to_top_btn_color');
$scroll_to_top_hvr_color = get_option('scroll_to_top_hvr_color');
$scroll_to_top_icon = get_option('scroll_to_top_icon');
$scroll_to_top_value_exclude = get_option('scroll_to_top_value_exclude');
$scroll_to_top_font_size = get_option('scroll_to_top_font_size');
$scroll_to_top_icon_color = get_option('scroll_to_top_icon_color');
$scroll_to_top_hvr_icon_color = get_option('scroll_to_top_hvr_icon_color');


?>

<form method="post" id="scroll_to_top_form" action="">
	<?php wp_nonce_field('Scrolltotop_plugin','_wpnonce')?>

<table class="form-table">
	
	<tr valign="top">
		<th width="50" scope="row">Top Scroller Speed</th>
		<td>
			<input type="radio" name="scroll_to_top_speed" value="fast" <?php if($scroll_to_top_speed == 'fast') echo("checked"); ?> /> 
			Fast <br /> <br />
			
			<input type="radio" name="scroll_to_top_speed" value="slow" <?php if ($scroll_to_top_speed == 'slow') echo ("checked"); ?> /> 
			Slow <br /><br />

		</td>	


	</tr>


				<!-------------------------  background color --------------------------------->

	    <tr valign="top">
			<th width="40" scope="row">Top Scroller Background Color</th>
			<td>
				<input type="text" name="scroll_to_top_btn_color" maxlength="7" value= "<?php echo esc_attr($scroll_to_top_btn_color); ?>" class= "scroll_to_topColorPicker">

			</td>	


			
		</tr>

			<!-------------------------  hover color --------------------------------->

		<tr valign="top">
			<th width="40" scope="row">Top Scroller Hover Color</th>
			 <td>
				<input type="text" name="scroll_to_top_hvr_color" maxlength="7" value= "<?php echo esc_attr($scroll_to_top_hvr_color); ?>" class= "scroll_to_topHvrColor">

			</td>	

		</tr>


		<!-------------------------  icon color --------------------------------->

		<tr valign="top">
			<th width="40" scope="row">Top Scroller Icon Color</th>
			 <td>
				<input type="text" name="scroll_to_top_icon_color" maxlength="7" value= "<?php echo esc_attr($scroll_to_top_icon_color); ?>" class= "scroll_to_topIconColor">

			</td>	

		</tr>


		<!-------------------------  icon hover color --------------------------------->

		<tr valign="top">
			<th width="40" scope="row">Icon Color When Hover</th>
			 <td>
				<input type="text" name="scroll_to_top_hvr_icon_color" maxlength="7" value= "<?php echo esc_attr($scroll_to_top_hvr_icon_color); ?>" class= "scroll_to_topHvrIconColor">

			</td>	

		</tr>


			<!-------------------------  Icon size --------------------------------->

		<tr valign="top">
			<th width="40" scope="row">Top Scroller Icon Size</th>
			 <td>
				<input type="text" name="scroll_to_top_font_size" maxlength="2" value= "<?php echo esc_attr($scroll_to_top_font_size); ?>" class= "scroll_to_topFontSize" size="1">px

			</td>	

		</tr>

		<!-------------------------  Select Icon --------------------------------->

		<tr valign="top">
			<th width="40" scope="row">Top Scroller Fontawesome Icon</th>
			<td>
			 <input type="text" name="scroll_to_top_icon" value= "<?php echo esc_attr($scroll_to_top_icon); ?>" class= "scroll_to_topIcon"><br>
				<span>ex [ fa fa-arrow-up, fas fa-arrow, far fa-hand-pointer ]</span><hr>


			</td>	

		</tr>		

		<!-------------------------  Exclude post and pages --------------------------------->
		

			<tr valign="top">
			<th width="108" scope="row" >Exclude Posts and Pages:</th>
			<td>
				<input type="text" name="scroll_to_top_value_exclude" value= "<?php echo esc_attr($scroll_to_top_value_exclude); ?>" size="35" class= "scroll_to_topValueExlude"><br>
				<span>Input post or page Slug or id. ex [ home, about-us, 1,2 ]</span><hr>

			</td>	

		</tr>



		<tr>
			<th width="50" scope="row">Save this setting</th>
			<td>
			<input type="submit" name="Totop_save" value="Save"  /><br />
			</td>
		</tr>


</table>
