<?php $options = get_option("ariafont_themes_fonts"); ?>
<div class="wrap" id="themes-fonts">
    <h2><?php echo _e("Theme's fonts", "aria-font"); ?></h2>
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
    ?>

    <form method="post" action="options.php">
        <?php settings_fields("ariafont_themes_fonts"); ?>
        <hr/>
        <table class="form-table">
            <?php
                foreach(aria_font::$tags as $tag)
                {
                ?>
                    <tr valign="top">
                        <th scope="row"><?php echo sprintf(__("%s's font", "aria-font"), $tag == "all" ? __("Entire website", "aria-font") : $tag); ?></th>
                        <td>
                            <select 
                                name="ariafont_themes_fonts[<?php echo $tag; ?>]" 
                                id="ariafont_themes_fonts[<?php echo $tag; ?>]"
                            >
                                <option value="">
                                    <?php echo sprintf(__("None %s", "aria-font"), $tag != aria_font::$dafault_font_tag && isset($options[aria_font::$dafault_font_tag]) && !empty($options[aria_font::$dafault_font_tag]) ? "(" . $options[aria_font::$dafault_font_tag] . ")" : ""); ?>
                                </option>
                                    <?php
                                        foreach(array_keys(aria_font::$fonts) as $fonts)
                                        {
                                            ?>
                                                <optgroup label="<?php echo sprintf(__("%s fonts", "aria-font"), $fonts); ?>">
                                                <?php
                                                    foreach(aria_font::$fonts[$fonts] as $font)
                                                    {
                                                        ?>
                                                            <option 
                                                                <?php 
                                                                    echo isset($options[$tag]) && $options[$tag] == $font ? "selected ": ""; 
                                                                ?> 
                                                                value="<?php echo $font; ?>">
                                                                <?php echo $font; ?>
                                                            </option>
                                                        <?php
                                                    }
                                                ?>
                                                </optgroup>
                                            <?php
                                        }
                                    ?>
                            </select>
                            <p class="description">
                                <?php _e("Please select your font.", "aria-font"); ?>
                            </p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo sprintf(__("Set %s's font by force", "aria-font"), $tag == "all" ? __("Entire website", "aria-font") : $tag); ?></th>
                        <td>
                            <select 
                                name="ariafont_themes_fonts[<?php echo $tag; ?>-force]" 
                                id="ariafont_themes_fonts[<?php echo $tag; ?>-force]"
                            >
                                <option value="">
                                    <?php _e("No","aria-font"); ?>
                                </option>
                                <option value="true" <?php echo isset($options[$tag . "-force"]) && $options[$tag . "-force"] == "true" ? "selected" : ""; ?>>
                                    <?php _e("Yes","aria-font"); ?>
                                </option>
                            </select>
                            <p class="description">
                                <?php _e("You can load font by force here.", "aria-font"); ?>
                            </p>
                        </td>
                    </tr>
                <?php
                    if($tag == "all")
                    {
                        ?>
                        </table>
                            <hr/>
                            <h3>
                                <?php _e("Be careful for using the fonts below, try to use <strong class='warning'>only one font</strong> for your website and load it from the option at the top (named <a>Entire Website's font</a>)", "aria-font"); ?>
                            </h3>
                            <?php
                                if(isset($options["all"]) && $options["all"] != "")
                                {
                                    ?>
                                    <h3>
                                        <?php 
                                            echo sprintf(
                                                __("At the moment, the font of all places of your website is: <strong class='current-font'>%s</strong>, if you want to use other fonts for other places, set them by the options below.", 
                                                "aria-font"),
                                                $options["all"]
                                            ); 
                                        ?>
                                    </h3>
                                    <?php
                                }
                            ?>
                        <table class="form-table">
                        <?php
                    }
                }
            ?>
        </table>
        
        <hr/>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e("Save Changes", "aria-font"); ?>" />
        </p>
    </form>
</div>
