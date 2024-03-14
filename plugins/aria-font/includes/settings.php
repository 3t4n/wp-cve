<?php

if(!class_exists("aria_font_settings_page"))
{
	class aria_font_settings_page 
	{
		function __construct()
		{
			add_action("admin_menu", [$this, "create_menu"]);
		}

		function create_menu()
		{
		    add_menu_page(
		        __("Aria Font", "aria-font"),
		        __("Aria Font", "aria-font"),
		        "manage_options",
		        "aria-font-settings",
		        [$this, "themes_fonts_page"],
		        "dashicons-admin-customizer"
		    );
		    add_submenu_page(
		        "aria-font-settings",
		        __("Theme's Fonts", "aria-font"),
		        __("Theme's Fonts", "aria-font"),
		        "manage_options",
		        "aria-font-settings",
		        [$this, "themes_fonts_page"]
		    );
		    if (is_plugin_active( 'elementor/elementor.php' )) {
			    add_submenu_page(
			        "aria-font-settings",
			        __("Elementor's Fonts", "aria-font"),
			        __("Elementor's Fonts", "aria-font"),
			        "manage_options",
			        "aria-font-elementor",
			        [$this, "elementors_fonts_page"]
			    );
			}
			add_submenu_page(
		        "aria-font-settings",
		        __("Admin's Font", "aria-font"),
		        __("Admin's Font", "aria-font"),
		        "manage_options",
		        "aria-font-admin",
		        [$this, "admins_font_page"]
		    );
		    add_submenu_page(
		        "aria-font-settings",
		        __("Support", "aria-font"),
		        __("Support", "aria-font"),
		        "manage_options",
		        "aria-font-support",
		        [$this, "support_page"]
		    );
		    add_action("admin_init", [$this, "settings_registration"]);
		}

		function settings_registration()
		{
		    register_setting("ariafont_themes_fonts", "ariafont_themes_fonts");
		    register_setting("ariafont_admins_font", "ariafont_admins_font");
		    register_setting("ariafont_elementors_fonts", "ariafont_elementors_fonts");
		}

		function themes_fonts_page()
		{
			$this->admin_scripts();
		    include "themes-fonts.php";
		}

		function elementors_fonts_page()
		{
			$this->admin_scripts();
		    include "elementors-fonts.php";
		}

		function admins_font_page()
		{
			$this->admin_scripts();
		    include "admins-font.php";
		}

		function support_page()
		{
			$this->admin_scripts();
		    include "support.php";
		}

		private function admin_scripts()
		{
			wp_enqueue_style(
	    		'aria-font-admin-style', 
	    		ARIAFONTPLUGINURL . 'assets/admin/css/main.css',
	    		[],
	    		get_plugin_data(ARIAFONTPLUGINPATH . "aria-font.php")["Version"]
	    	);
		}
	}

	$aria_font_settings_page = new aria_font_settings_page();
}
?>