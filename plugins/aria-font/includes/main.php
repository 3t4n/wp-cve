<?php

if(!class_exists("aria_font"))
{
	class aria_font 
	{
		public static $fonts = [
			"Persian" => [
				"Yekan",
				"IranNastaliq",
				"DroidNaskh",
				"DroidKufi",
				"BBadr",
				"BBaran",
				"BBardiya",
				"BCompset",
				"BDavat",
				"BElham",
				"BEsfehanBold",
				"BFantezy",
				"BFarnaz",
				"BFerdosi",
				"BHamid",
				"BHelal",
				"BHoma",
				"BJadidBold",
				"BJalal",
				"BKoodakBold",
				"BKourosh",
				"BLotus",
				"BMahsa",
				"BMehrBold",
				"BMitra",
				"BMorvarid",
				"BNarm",
				"BNasimBold",
				"BNazanin",
				"BRoya",
				"BSetarehBold",
				"BShiraz",
				"BSinaBold",
				"BTabassom",
				"BTehran",
				"BTitrBold",
				"BTitrTGEBold",
				"BTraffic",
				"BVahidBold",
				"BYas",
				"BYagut",
				"BYekan",
				"BZar",
				"BZiba",
			],
		];

		public static $dafault_font_tag = "all";
		public static $tags = [
			"all",
			"body",
			"h1",
			"h2",
			"h3",
			"h4",
			"h5",
			"h6",
			"p",
			"span",
			"a",
		];
	}
}

if(!class_exists("aria_font_main"))
{
	class aria_font_main
	{
		function __construct()
		{
			$this->themes_fonts = get_option('ariafont_themes_fonts');
			$this->elementors_fonts = get_option('ariafont_elementors_fonts');
			$this->admins_font = get_option('ariafont_admins_font');
			
			add_action('wp_enqueue_scripts', [$this, 'load_front_font'], 100);
			add_action('admin_enqueue_scripts', [$this, 'load_admin_font'], 100);
			add_action('wp_head', [$this, 'load_front_css']);
			add_action('admin_head', [$this, 'load_admin_css']);
			add_filter('elementor/fonts/groups', [$this, 'elementors_custom_fonts_group']);
			add_filter('elementor/fonts/additional_fonts', [$this, 'elementors_custom_fonts'], 10, 1);
		}

		function elementors_custom_fonts_group($groups)
		{
			$new_group["aria_font"] = __('Aria Font', 'awp');
			$groups = $new_group + $groups;

			return $groups;
		}
		
		function elementors_custom_fonts($additional_fonts)
		{
			foreach(aria_font::$tags as $tag)
			{
				$selected_font = isset($this->themes_fonts[$tag]) ? $this->themes_fonts[$tag] : "";
				if(!empty($selected_font))
				{
					$additional_fonts[$selected_font] = 'aria_font';
				}
			}

			foreach(array_keys(aria_font::$fonts) as $fonts)
            {
                foreach(aria_font::$fonts[$fonts] as $font)
                {
                	$selected_font = isset($this->elementors_fonts["extra-fonts-" . $font]) ? $this->elementors_fonts["extra-fonts-" . $font] : "";
					if(!empty($selected_font))
					{
						$additional_fonts[$font] = 'aria_font';
	                }
                }
            }
				
			return $additional_fonts;
		}

		function load_front_font() 
		{
			foreach(aria_font::$tags as $tag)
			{
				$selected_font = isset($this->themes_fonts[$tag]) ? $this->themes_fonts[$tag] : "";
				if(!empty($selected_font))
				{
			    	wp_enqueue_style(
			    		'aria-font-' . $selected_font . "-style", 
			    		ARIAFONTPLUGINURL . 'assets/fonts/' . 
			    			urlencode(esc_html($selected_font)) . '/' . urlencode(esc_html($selected_font)) . '.css',
			    		[],
			    		get_plugin_data(ARIAFONTPLUGINPATH . "aria-font.php")["Version"]
			    	);
			    }
			}

			foreach(array_keys(aria_font::$fonts) as $fonts)
            {
                foreach(aria_font::$fonts[$fonts] as $font)
                {
                	$selected_font = isset($this->elementors_fonts["extra-fonts-" . $font]) ? $this->elementors_fonts["extra-fonts-" . $font] : "";
					if(!empty($selected_font))
					{
	                	wp_enqueue_style(
				    		'aria-font-' . $font . "-style", 
				    		ARIAFONTPLUGINURL . 'assets/fonts/' . 
				    			urlencode(esc_html($font)) . '/' . urlencode(esc_html($font)) . '.css',
				    		[],
				    		get_plugin_data(ARIAFONTPLUGINPATH . "aria-font.php")["Version"]
				    	);
	                }
                }
            }
		}

		function load_front_css()
		{
			?>
				<style>
					<?php 
						foreach (aria_font::$tags as $tag)
						{
							if(isset($this->themes_fonts[$tag]))
							{
								$selected_font = $this->themes_fonts[$tag];
								$selected_font_force = $this->themes_fonts[$tag . "-force"];
								if(!empty($selected_font))
								{
									?>
										<?php echo esc_html($tag) == "all" ? implode(", ", aria_font::$tags) . ", " . implode(", .rtl ", aria_font::$tags) : esc_html($tag); ?> {
											font-family: "<?php echo esc_html($selected_font); ?>", tahoma<?php echo $selected_font_force ? " !important" : ""; ?>;
										}
									<?php
								}
							}
						}
					?>
				</style>
			<?php
		}

		function load_admin_font()
		{
			if(isset($this->admins_font["all"]) && $this->admins_font["all"] != "")
			{
				$selected_font = $this->admins_font["all"];
				if(!empty($selected_font))
				{
			    	wp_enqueue_style(
			    		'aria-font-' . $selected_font . "-style", 
			    		ARIAFONTPLUGINURL . 'assets/fonts/' . 
			    			urlencode(esc_html($selected_font)) . '/' . urlencode(esc_html($selected_font)) . '.css',
			    		[],
			    		get_plugin_data(ARIAFONTPLUGINPATH . "aria-font.php")["Version"]
			    	);
			    }
			}
		}

		function load_admin_css()
		{
			if(isset($this->admins_font["all"]) && $this->admins_font["all"] != "")
			{
				$selected_font = $this->admins_font["all"];
				$selected_font_force = $this->admins_font["all-force"];
				if(!empty($selected_font))
				{
					?>
						<style>
							body.rtl, <?php echo implode(", ", aria_font::$tags); ?> {
								font-family: "<?php echo esc_html($selected_font); ?>", tahoma<?php echo $selected_font_force ? " !important" : ""; ?>;
							}

							body.rtl, <?php echo implode(", .rtl ", aria_font::$tags); ?> {
								font-family: "<?php echo esc_html($selected_font); ?>", tahoma<?php echo $selected_font_force ? " !important" : ""; ?>;
							}

							body.rtl #wpadminbar *, body #wpadminbar * {
								font-family: "<?php echo esc_html($selected_font); ?>", tahoma<?php echo $selected_font_force ? " !important" : ""; ?>;
							}
						</style>
					<?php
				}
			}
		}
	}

	$aria_font_main = new aria_font_main();
}

?>
