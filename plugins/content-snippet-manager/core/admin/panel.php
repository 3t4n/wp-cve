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

				"Snippet_Generator"	=> esc_html__( "Snippet Generator","content-snippet-manager"),
				"Import_Export"	=> esc_html__( "Import/Export","content-snippet-manager"),
				"free_vs_pro" => esc_html__( "Free vs Pro","content-snippet-manager"),
				CSM_UPGRADE_LINK . "/?ref=2&campaign=csm-panel"	=> esc_html__( "Upgrade to Premium","content-snippet-manager"),

			),

			"start" => "<ul>",
			"end" => "</ul>"
	),

	array(	"tab" => "Snippet_Generator",
			"element" =>

		array(	"type" => "start-form",
				"name" => "Snippet_Generator"),

			array(	"type" => "start-open-container",
					"name" => esc_html__( "Snippet Generator","content-snippet-manager")),

				array(	"name" => esc_html__( "Snippet Content","content-snippet-manager"),
						"id" => "csm_snippets",
						"data" => "array",
						"type" => "scriptGenerator",
						"std" => ""),

			array(	"type" => "end-container"),

		array(	"type" => "end-form"),

	),
	
	array(	"tab" => "Import_Export",
			"element" =>

		array(	"type" => "start-form",
				"name" => "Import_Export"),

			array(	"type" => "start-open-container",
					"name" => esc_html__( "Import / Export", "content-snippet-manager")),

				array(	"name" => esc_html__( "Import / Export", "content-snippet-manager"),
						"type" => "import_export"),

			array(	"type" => "end-container"),

		array(	"type" => "end-form"),

	),

	array(	"tab" => "free_vs_pro",
			"element" =>

		array(	"type" => "start-form",
				"name" => "free_vs_pro"),

			array(	"type" => "start-open-container",
					"name" => esc_html__( "Free vs Pro", "custom-thank-you-page")),

				array(	"name" => esc_html__( "Import / Export", "custom-thank-you-page"),
						"type" => "free_vs_pro"),

			array(	"type" => "end-container"),

		array(	"type" => "end-form"),

	),

	array(	"type" => "end-tab"),

);

new csm_panel ($optpanel);

?>
