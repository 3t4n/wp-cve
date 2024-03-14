<?php

/**
 * Wp in Progress
 * 
 * @package Wordpress
 * @author WPinProgress
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * It is also available at this URL: http://www.gnu.org/licenses/gpl-3.0.txt
 */

$optpanel = array (

	array (	"name" => "Navigation",  
			"type" => "navigation",  
			"item" => array( 
				"Logo"			=> esc_html__( "Logo","wip-custom-login"),
				"Background"	=> esc_html__( "Background","wip-custom-login"),
				"Form"			=> esc_html__( "Form","wip-custom-login"),
				"Import_Export"	=> esc_html__( "Import/Export","wip-custom-login"),
			),   
			
			"start" => "<ul>", 
			"end" => "</ul>"
	),  

	array(	"tab" => "Logo",
			"element" =>
		   
		array(	"type" => "start-form",
				"name" => "Logo"),

			array(	"type" => "start-open-container",
					"name" => esc_html__( "Logo","wip-custom-login")),
			
				array(	"name" => esc_html__( "Logo","wip-custom-login"),
						"desc" => esc_html__( "Upload your admin logo.","wip-custom-login"),
						"id" => "wip_custom_login_logo_url",
						"data" => "array",
						"type" => "upload",
						"std" => ""),
			
				array(	"name" => esc_html__( "Logo width","wip-custom-login"),
						"desc" => esc_html__( "Insert the width of your logo.","wip-custom-login"),
						"id" => "wip_custom_login_logo_width",
						"type" => "text",
						"std" => "100%"),
			
				array(	"name" => esc_html__( "Logo height","wip-custom-login"),
						"desc" => esc_html__( "Insert the height of your logo.","wip-custom-login"),
						"id" => "wip_custom_login_logo_height",
						"type" => "text",
						"std" => "84px"),
			
				array(	"type" => "save-button",
						"value" => "Save",
						"class" => "Logo"),
			
			array(	"type" => "end-container"),

		array(	"type" => "end-form"),

	),
	
	array(	"tab" => "Background",
			"element" =>
		   
		array(	"type" => "start-form",
				"name" => "Background"),

			array(	"type" => "start-open-container",
					"name" => esc_html__( "Background","wip-custom-login")),
			
				array(	"name" => esc_html__( "Background Color","wip-custom-login"),
						"desc" => esc_html__( "Choose a color for body background.","wip-custom-login"),
						"id" => "wip_custom_login_background_color",
						"type" => "color",
						"std" => "#f1f1f1"),
				
				array(	"name" => esc_html__( "Custom image background","wip-custom-login"),
						"desc" => esc_html__( "Upload a image for body background.","wip-custom-login"),
						"id" => "wip_custom_login_background_image",     
						"data" => "array",
						"type" => "upload",
						"class" => "hidden-element",
						"std" => ""),
				
				array(	"name" => esc_html__( "Repeat","wip-custom-login"),
						"desc" => esc_html__( "Repeat","wip-custom-login"),
						"id" => "wip_custom_login_background_repeat",
						"type" => "select",
						"options" => array(
							"" => "None",
							"repeat" => esc_html__( "Repeat","wip-custom-login"),
							"no-repeat" => esc_html__( "No repeat","wip-custom-login"),
							"repeat-x" => esc_html__( "Repeat orizzontal","wip-custom-login"),
							"repeat-y" => esc_html__( "Repeat vertical","wip-custom-login"),
						),
						"std" => "repeat"),
				
				array(	"name" => esc_html__( "Background Position","wip-custom-login"),
						"desc" => esc_html__( "Background Position","wip-custom-login"),
						"id" => "wip_custom_login_background_position",
						"type" => "select",
						"options" => array(
							"" => "None",
							"top left" => "top left",
							"top center" => "top center",
							"top right" => "top right",
							"center" => "center",
							"bottom left" => "bottom left",
							"bottom center" => "bottom center",
							"bottom right" => "bottom right",
						),
						"std" => ""),
				
				array(	"name" => esc_html__( "Background Attachment","wip-custom-login"),
						"desc" => esc_html__( "Background Attachment","wip-custom-login"),
						"id" => "wip_custom_login_background_attachment",
						"type" => "select",
						"options" => array(
							"normal" => "normal",
							"fixed" => "fixed",
						),
						"std" => "normal"),
		   
				array(	"type" => "save-button",
						"value" => "Save",
						"class" => "Background"),
			
			array(	"type" => "end-container"),

		array(	"type" => "end-form"),

	),
	
	array(	"tab" => "Form",
			"element" =>
		   
		array(	"type" => "start-form",
				"name" => "Form"),

			array(	"type" => "start-open-container",
					"name" => esc_html__( "Form","wip-custom-login")),

				array(	"name" => esc_html__( "Character sets","wip-custom-login"),
						"desc" => esc_html__( "Choose the character sets you want:","wip-custom-login"),
						"id" => "wip_custom_login_charset",
						"type" => "multioptions",
						"options" => array(
							"latin" => esc_html__( "Latin","wip-custom-login"),
							"latin-ext" => esc_html__( "Latin Extended","wip-custom-login"),
							"cyrillic" => esc_html__( "Cyrillic","wip-custom-login"),
							"cyrillic-ext" => esc_html__( "Cyrillic Extended","wip-custom-login"),
							"greek" => esc_html__( "Greek","wip-custom-login"),
							"greek-ext" => esc_html__( "Greek Extended","wip-custom-login"),
							"vietnamese" => esc_html__( "Vietnamese","wip-custom-login"),
						),
				),

				array(	"name" => esc_html__( "Login font","wip-custom-login"),
						"desc" => esc_html__( "Select a font of login box","wip-custom-login"),
						"id" => "wip_custom_login_font",
						"type" => "select",
						"options" => wip_custom_login_get_font("", "getlist"),
						"std" => "Montserrat"),

				array(	"name" => esc_html__( "Login box Position","wip-custom-login"),
						"desc" => esc_html__( "Login box Position","wip-custom-login"),
						"id" => "wip_custom_login_loginbox_position",
						"type" => "select",
						"options" => array(
							"left" => "Left",
							"right" => "Right",
							"center" => "Center",
						),
						"std" => "center"),

				array(	"name" => esc_html__( "Login box textcolor","wip-custom-login"),
						"desc" => esc_html__( "Choose a color for the text of login box.","wip-custom-login"),
						"id" => "wip_custom_login_loginbox_textcolor",
						"type" => "color",
						"std" => "#333"),
		   
				array(	"name" => esc_html__( "Login box link color at hover","wip-custom-login"),
						"desc" => esc_html__( "Choose a color for the link of login box at hover.","wip-custom-login"),
						"id" => "wip_custom_login_loginbox_linkcolor_hover",
						"type" => "color",
						"std" => "#008ec2"),
		   
				array(	"name" => esc_html__( "Login box background color","wip-custom-login"),
						"desc" => esc_html__( "Choose a background color for the login box.","wip-custom-login"),
						"id" => "wip_custom_login_loginbox_background",
						"type" => "color",
						"std" => "#fff"),
				
				array(	"name" => esc_html__( "Login box border color","wip-custom-login"),
						"desc" => esc_html__( "Choose a color for the border of login box.","wip-custom-login"),
						"id" => "wip_custom_login_loginbox_border",
						"type" => "color",
						"std" => "#d2d2d2"),

				array(	"name" => esc_html__( "Login box width","wip-custom-login"),
						"desc" => esc_html__( "Insert the width of your logo.","wip-custom-login"),
						"id" => "wip_custom_login_loginbox_width",
						"type" => "text",
						"std" => "350px"),
			
				array(	"type" => "save-button",
						"value" => "Save",
						"class" => "Form"),
			
			array(	"type" => "end-container"),

		array(	"type" => "end-form"),

	),
	
	array(	"tab" => "Import_Export",
			"element" =>
		   
		array(	"type" => "start-form",
				"name" => "Import_Export"),

			array(	"type" => "start-open-container",
					"name" => esc_html__( "Import / Export", "wip-custom-login")),
			
				array(	"name" => esc_html__( "Import / Export", "wip-custom-login"),
						"type" => "import_export"),
				
			array(	"type" => "end-container"),

		array(	"type" => "end-form"),

	),
	
	array(	"type" => "end-tab"),

	array(	"type" => "end-panel"),  

);

new wip_custom_login_panel ($optpanel);

?>