<?php $options = get_option("ariafont_admins_font"); ?>
<div class="wrap" id="themes-fonts">
    <h2><?php echo _e("Admin's fonts", "aria-font"); ?></h2>
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

        $tag = "all";
    ?>

    <form method="post" action="options.php">
        <?php settings_fields("ariafont_admins_font"); ?>
        <hr/>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php echo sprintf(__("%s's font", "aria-font"), $tag == "all" ? __("WP Admin dashboard", "aria-font") : $tag); ?></th>
                <td>
                    <select 
                        name="ariafont_admins_font[<?php echo $tag; ?>]" 
                        id="ariafont_admins_font[<?php echo $tag; ?>]"
                    >
                        <option value="">
                            <?php echo _e("None", "aria-font"); ?>
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
                <th scope="row"><?php echo sprintf(__("Set %s's font by force", "aria-font"), $tag == "all" ? __("WP Admin dashboard", "aria-font") : $tag); ?></th>
                <td>
                    <select 
                        name="ariafont_admins_font[<?php echo $tag; ?>-force]" 
                        id="ariafont_admins_font[<?php echo $tag; ?>-force]"
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
        </table>
        
        <hr/>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e("Save Changes", "aria-font"); ?>" />
        </p>
    </form>
</div>
