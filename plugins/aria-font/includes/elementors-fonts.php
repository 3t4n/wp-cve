<?php $options = get_option("ariafont_elementors_fonts"); ?>
<div class="wrap" id="elementors-fonts">
    <h2><?php echo _e("Elementor's font", "aria-font"); ?></h2>
    <?php
        if (isset($_REQUEST["settings-updated"]) && $_REQUEST["settings-updated"]): ?>
            <div id="message" class="updated below-h2 notice is-dismissible">
                <p><?php _e("Settings has been saved successfully!", "aria-font"); ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">
                        <?php _e("Close", "aria-font"); ?>
                    </span>
                </button>
            </div>
        <?php 
        endif; 

        $themes_fonts = get_option('ariafont_themes_fonts');
    	
    	$loaded_fonts = [];
    	foreach(aria_font::$tags as $tag)
		{
			$selected_font = $themes_fonts[$tag];
			if(!empty($selected_font))
			{
				array_push($loaded_fonts, $selected_font);
			}
		}
	?>

    <form method="post" action="options.php">
    	<?php settings_fields("ariafont_elementors_fonts"); ?>
        <hr/>
        <?php
        if(count($loaded_fonts) > 0)
        {
	        ?>
	        <h3>
	        	<?php 
	        		echo sprintf(
	        			__(
	        				"At the moment, the fonts below, are being loaded into elementor (because you have selected them in the <a href='%s'>theme's fonts</a> settings): ",
	        				"aria-font"
	        			), get_admin_url() . "admin.php?page=aria-font-settings"
	        		); 
	        	?>
	        </h3>

	        <ul>
		        <?php
		    		foreach(array_unique($loaded_fonts) as $loaded_font)
		    		{
		    			echo "<li><h4>" . $loaded_font . "</h4></li>";
		    		}
		    	?>
	    	</ul>
	        
	        <h3>
	        	<?php 
	        		echo sprintf(
	        			__(
	        				"So if you need any extra fonts, to be loaded only in elementor, select them from here.",
	        				"aria-font"
	        			), get_admin_url() . "admin.php?page=aria-font-settings"
	        		); 
	        	?>
	        </h3>
			<?php	        
		}
		else
		{
			?>
				<h3>
		        	<?php 
		        		echo sprintf(
		        			__(
		        				"It is worthy to say that, the fonts, you select here will only be loaded in elementor, if you want to load some fonts for you theme, you can set the fonts in <a href='%s'>theme's fonts</a> settings.",
		        				"aria-font"
		        			), get_admin_url() . "admin.php?page=aria-font-settings"
		        		); 
		        	?>
		        </h3>
			<?php
		}
		?>

        <table class="form-table">
	    	<?php
	    		foreach(array_keys(aria_font::$fonts) as $fonts)
	            {
	                ?>
	                <tr valign="top">
		                <th scope="row"><?php echo sprintf(__("Extra %s fonts for elementor", "aria-font"), $fonts); ?></th>
		                <td>
	                            <div class="fonts">
	                                <?php
	                                    foreach(aria_font::$fonts[$fonts] as $font)
	                                    {
	                                    	$already_loaded = array_search($font, $loaded_fonts) !== false;
	                                        ?>
		                                        <div class="font">
		                                            <input 
			                                            name="ariafont_elementors_fonts[extra-fonts-<?php echo $font; ?>]" 
			                        					id="ariafont_elementors_fonts[extra-fonts-<?php echo $font; ?>]"
			                                            <?php 
			                                                echo isset($options["extra-fonts-" . $font]) && $options["extra-fonts-" . $font] == true ? "checked" : ""; 
			                                            ?> 
			                                            value="true"
			                                            type="checkbox"
			                                            <?php echo $already_loaded ? 'disabled="disabled"' : ""; ?>
			                                        />
			                                        <label for="ariafont_elementors_fonts[extra-fonts-<?php echo $font; ?>]">
			                                        	<?php echo $font . ($already_loaded ? " (" . __("Already loaded via theme's settings", "aria-font") . ")" : ""); ?>
			                                        </label>
			                                    </div>
	                                        <?php
	                                    }
	                                ?>
	                            </div>
	                       	    <p class="description">
		                        	<?php sprintf(__("Please select your %s fonts here.", "aria-font"), $fonts); ?>
		                    	</p>
		                	</td>
		            	</tr>
	                <?php
	            }
	    	?>
        </table>
        
        <hr/>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e("Save Changes", "aria-font"); ?>" />
        </p>
    </form>
</div>
