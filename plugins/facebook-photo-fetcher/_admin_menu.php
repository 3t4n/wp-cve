<?php

/*
 * Tell WP about the Admin page
 */
add_action('admin_menu', 'fpf_add_admin_page', 99);
function fpf_add_admin_page()
{
    global $fpf_name;
    add_options_page("$fpf_name Options", 'FB Photo Fetcher' . (defined('FPF_ADDON') ? "+" : ""), 'administrator', "fb-photo-fetcher", 'fpf_admin_page');
}


/**
 * Link to Settings on Plugins page 
 */
add_filter('plugin_action_links', 'fpf_add_plugin_links', 10, 2);
function fpf_add_plugin_links($links, $file)
{
    if (dirname(plugin_basename(__FILE__)) == dirname($file))
        $links[] = '<a href="options-general.php?page=' . "fb-photo-fetcher" . '">' . __('Settings', 'facebook-photo-fetcher') . '</a>';
    return $links;
}

/**
 * Styles
 */
add_action('admin_head', 'fpf_admin_styles');
function fpf_admin_styles()
{
    echo '<style type="text/css">' .
        '.fpf-admin_warning     {background-color: #FFEBE8; border:1px solid #C00; padding:0 .6em; margin:10px 0 15px; -khtml-border-radius:3px; -webkit-border-radius:3px; border-radius:3px;}' .
        '.fpf-admin_wrapper     {clear:both; background-color:#FFFEEB; border:1px solid #CCC; padding:0 8px; }' .
        '.fpf-admin_tabs        {width:100%; clear:both; float:left; margin:0 0 -0.1em 0; padding:0;}' .
        '.fpf-admin_tabs li     {list-style:none; float:left; margin:0; padding:0.2em 0.5em 0.2em 0.5em; }' .
        '.fpf-admin_tab_selected{background-color:#FFFEEB; border-left:1px solid #CCC; border-right:1px solid #CCC; border-top:1px solid #CCC;}' .
        '</style>';
}

/**
 * Output the plugin's Admin Page 
 */
function fpf_admin_page()
{
    global $fpf_name, $fpf_version, $fpf_identifier, $fpf_homepage, $fpf_apiver;
    global $fpf_opt_access_token, $fpf_opt_token_expiration, $fpf_opt_last_uid_search;
    global $fpf_shown_tab;
    $fpf_shown_tab   = 2;
    $allTabsClass    = "fpf_admin_tab";
    $allTabBtnsClass = "fpf_admin_tab_btn";
    $tab1Id          = "fpf_admin_fbsetup";
    $tab2Id          = "fpf_admin_utils";
    $tab3Id          = "fpf_admin_addon";
    $tab4Id          = "fpf_admin_supportinfo";

    ?><div class="wrap">
        <h2><?php echo $fpf_name ?></h2>
        <?php

            //Check $_POST for what we're doing, and update any necessary options
            if (isset($_POST[$fpf_opt_access_token]))  //User connected a facebook session (login+save)
            {
                //We're saving a new access token.  Let's use it to try and fetch the userID, to verify that it's valid before saving.
                //Also, store the expiration timestamp.  We need to store this as the debug_token endpoint is only available to the current
                //app's developer (so a regular user can't get it again - only when the token is first assigned).
                $user = fpf_get("https://graph.facebook.com/$fpf_apiver/me?access_token=" . $_POST[$fpf_opt_access_token] . "&fields=name,id");
                if (isset($user->id) && !isset($user->error)) {
                    update_option($fpf_opt_access_token, $_POST[$fpf_opt_access_token]);
                    if (isset($_POST[$fpf_opt_token_expiration]) && $_POST[$fpf_opt_token_expiration] && $_POST[$fpf_opt_token_expiration] != '0')
                        update_option($fpf_opt_token_expiration, time() + $_POST[$fpf_opt_token_expiration]);
                    else
                        update_option($fpf_opt_token_expiration, 0);
                    ?><div class="updated">
                    <p><strong><?php echo sprintf(__('Facebook Session Saved (Name: %s, ID: %s)', 'facebook-photo-fetcher'), $user->name, $user->id) ?></strong></p>
                </div><?php
                                } else {
                                    update_option($fpf_opt_access_token, 0);
                                    update_option($fpf_opt_token_expiration, 0);
                                    ?><div class="updated">
                    <p><strong><?php echo __('Error: Failed to get a valid access token from Facebook.  Response:', 'facebook-photo-fetcher') . ' ' . (isset($user->error->message) ? $user->error->message : "Unknown"); ?></strong></p>
                </div><?php
                                }
                            } else if (isset($_POST['delete_token'])) //User wants to remove the current access token.
                            {                                        //No need to output an 'updated' message, because the lack of a token will be detected and shown as an error below.
                                update_option($fpf_opt_access_token, 0);
                            } else if (isset($_POST[$fpf_opt_last_uid_search]))    //User clicked "Search," which saves 'last searched uid'
                            {
                                update_option($fpf_opt_last_uid_search, esc_html($_POST[$fpf_opt_last_uid_search]));
                                ?><div class="updated">
                <p><strong><?php _e('Album search completed.', 'facebook-photo-fetcher') ?></strong></p>
            </div><?php
                        } else                                                 //Allow optional addons to perform actions
                        {
                            do_action('fpf_extra_panel_actions', $_POST);
                        }

                        //Whenever the admin panel is loaded, verify that the access_token is valid by trying to fetch the name and id.
                        //If not, clear it from the database, forcing the user to (re-)validate.
                        $access_token = get_option($fpf_opt_access_token);
                        $user = fpf_get("https://graph.facebook.com/$fpf_apiver/me?access_token=" . $access_token . "&fields=name,id");
                        if (!$access_token) {
                            ?><div class="error">
                <p><strong><?php _e('This plugin does not have a valid Facebook access token.  Please authorize it by logging in below.', 'facebook-photo-fetcher') ?></strong></p>
            </div><?php
                        } else if (!$user) {
                            ?><div class="error">
                <p><strong><?php _e('An error occurred while validating your Facebook access token (empty reply).  Please re-authorize by logging in below.', 'facebook-photo-fetcher') ?></strong></p>
            </div><?php
                            update_option($fpf_opt_access_token, 0);
                        } else if (isset($user->error)) {
                            ?><div class="error">
                <p><strong><?php echo $user->error->message . "<br /><br />" . __('Please re-authorize this plugin by logging into Facebook below.', 'facebook-photo-fetcher') ?></strong></p>
            </div><?php
                            update_option($fpf_opt_access_token, 0);
                        }

                        //Re-get the access_token, in case it was cleared by an error above)
                        $access_token = get_option($fpf_opt_access_token);
                        if (!$access_token) $fpf_shown_tab = 1;
                        ?>

        <!-- Tab Navigation -->
        <script type="text/javascript">
            function fpf_swap_tabs(show_tab_id) {
                //Hide all the tabs, then show just the one specified
                jQuery(".<?php echo $allTabsClass ?>").hide();
                jQuery("#" + show_tab_id).show();

                //Unhighlight all the tab buttons, then highlight just the one specified
                jQuery(".<?php echo $allTabBtnsClass ?>").attr("class", "<?php echo $allTabBtnsClass ?>");
                jQuery("#" + show_tab_id + "_btn").addClass("fpf-admin_tab_selected");
            }
        </script>

        <div>
            <ul class="fpf-admin_tabs">
                <li id="<?php echo $tab1Id ?>_btn" class="<?php echo $allTabBtnsClass ?> <?php echo ($fpf_shown_tab == 1 ? "fpf-admin_tab_selected" : "") ?>"><a href="javascript:void(0);" onclick="fpf_swap_tabs('<?php echo $tab1Id ?>');"><?php _e('Facebook Setup', 'facebook-photo-fetcher') ?></a></li>
                <li id="<?php echo $tab2Id ?>_btn" class="<?php echo $allTabBtnsClass ?> <?php echo ($fpf_shown_tab == 2 ? "fpf-admin_tab_selected" : "") ?>"><a href="javascript:void(0);" onclick="fpf_swap_tabs('<?php echo $tab2Id ?>')" ;><?php _e('Utilities', 'facebook-photo-fetcher') ?></a></li>
                <?php if (defined('FPF_ADDON')) : ?>
                    <li id="<?php echo $tab3Id ?>_btn" class="<?php echo $allTabBtnsClass ?> <?php echo ($fpf_shown_tab == 3 ? "fpf-admin_tab_selected" : "") ?>"><a href="javascript:void(0);" onclick="fpf_swap_tabs('<?php echo $tab3Id ?>')" ;><?php _e('Addon', 'facebook-photo-fetcher') ?></a></li>
                <?php endif; ?>
                <li id="<?php echo $tab4Id ?>_btn" class="<?php echo $allTabBtnsClass ?> <?php echo ($fpf_shown_tab == 4 ? "fpf-admin_tab_selected" : "") ?>"><a href="javascript:void(0);" onclick="fpf_swap_tabs('<?php echo $tab4Id ?>')" ;><?php _e('Support Info', 'facebook-photo-fetcher') ?></a></li>
            </ul>
        </div>

        <!--Start Main panel content-->
        <div class="fpf-admin_wrapper">
            <div class="<?php echo $allTabsClass ?>" id="<?php echo $tab1Id ?>" style="display:<?php echo ($fpf_shown_tab == 1 ? "block" : "none") ?>">
                <h3><?php _e('Overview', 'facebook-photo-fetcher') ?></h3>
                <?php _e('This plugin allows you to create Wordpress photo galleries from Facebook albums.', 'facebook-photo-fetcher') ?><br /><br />
                <?php _e('To get started, you must first connect with your Facebook account using the link below.  Once connected, you can create a gallery by making a new Wordpress post or page, switching to the Code Editor (under the "3 dots" menu at the top right), and pasting in one line of special HTML, like this:', 'facebook-photo-fetcher') ?><br /><br />
                <b>&lt;!--<?php echo $fpf_identifier ?> 1234567890123456789 --&gt;&lt;!--/<?php echo $fpf_identifier ?>--&gt;</b><br /><br />
                <?php _e('Whenever you save a post or page containing these tags, this plugin will download the album information and insert its contents between them (if you don\'t see it right after saving, click "View Page," or just refresh the editor).  You are free to include any normal content you like before or after, as usual.', 'facebook-photo-fetcher') ?><br /><br />
                <?php echo sprintf(__("The example number above (%s) is an ID that tells the plugin which Facebook album you'd like to import.  To find a list of available albums, you can use the 'Search for Albums' feature under the 'Utilities' tab.", 'facebook-photo-fetcher'), '1234567890123456789') ?><br /><br />
                <?php echo sprintf(__("That's all there is to it!  For more information on how to customize your albums, help, and a demo, please see the full documentation on the <a href='%s'>plugin homepage</a>.", 'facebook-photo-fetcher'), $fpf_homepage) ?><br /><br />
                <?php echo sprintf(__("And if you like this plugin, please consider %s donating %s to help support its future development.", 'facebook-photo-fetcher'), '<a href="javascript:void(0);" onclick="fpf_swap_tabs(\'' . $tab2Id . '\');jQuery(\'html, body\').animate({ scrollTop: jQuery(document).height() }, \'slow\');">', "</a>") ?><br /><br />
                <hr />

                <?php //SECTION - Facebook Authorization. See notes at the bottom of this file. 
                    ?>
                <h3><?php _e('Facebook Authorization', 'facebook-photo-fetcher') ?></h3>
                <?php if ($access_token) : ?> <i><?php echo sprintf(__("This plugin is successfully connected with %s's Facebook account and is ready to create galleries.", 'facebook-photo-fetcher'), "<b>$user->name</b>") ?></i> <?php _e("If you'd like to remove the connection and authorize a different user, click the button below:", 'facebook-photo-fetcher') ?><br /><br />
                <?php else :                ?> <?php echo __('Before this plugin can be used, you must connect it to your Facebook account. Please click the following link to visit the authorization page:', 'facebook-photo-fetcher') ?><br /><br />
                <?php endif; ?>

                <!--Deauthorize button-->
                <?php if ($access_token) : ?>
                    <form method="post" action="">
                        <input type="hidden" id="delete_token" name="delete_token" value="0" />
                        <input type="submit" class="button-secondary" style="width:127px;" value="<?php _e('Deauthorize', 'facebook-photo-fetcher') ?>" />
                    </form>
                <?php endif; ?>

                <!--Login/Renew button-->
                <!--Facebook requires the auth dialog to be shown from the domain specified in the FPF app settings.  I therefore redirect to a page-->
                <!--on my server where the user logs in, & it posts the token back to this page.-->
                <!--The link may be named "Login" or "Renew," based on if there's already a token in the database.-->
                <?php
                    $returnUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    $buttonText = ($access_token ? __("Renew", 'facebook-photo-fetcher') : __("Login with Facebook", 'facebook-photo-fetcher'));
                    $args = "return=" . urlencode($returnUrl) . "&ver=" . urlencode($fpf_version) . "&btn=" . urlencode($buttonText);
                    echo "<a href='https://www.justin-klein.com/auth/FPF-Auth/?$args'>$buttonText</a>";
                    ?>
                <?php if ($access_token) : ?>
                    <?php if (get_option($fpf_opt_token_expiration)) : ?>
                        - <?php echo sprintf(__("Expires in %s.", 'facebook-photo-fetcher'), human_time_diff(get_option($fpf_opt_token_expiration))) ?>
                    <?php endif; ?>
                <?php else : ?>
                    <br clear="all" /><br /><small><i><?php _e("Note: To comply with Facebook's security rules, this app may only authorize from one specific, known location.  The above link will therefore redirect you to an authorization page on my site.  During the authorization process, no personal information will be transferred; Facebook handles the entire process, and only supplies me with the resulting token, which I then hand back to your site to be stored.  This is what the plugin uses in order to fetch the photos.  For more information about how the Facebook authorization process works, please see their documentation:", 'facebook-photo-fetcher') ?> <a href="https://developers.facebook.com/docs/reference/dialogs/oauth/" target="fpf">here</a>.</i></small>
                <?php endif; ?>
                <br clear="all" />

                <hr />
                <?php
                    //Output the token expiration, for testing.
                    //NOTE: This will only work for MY user account (they only allow the developer of an app to debug that app's access tokens)
                    //See https://developers.facebook.com/docs/howtos/login/debugging-access-tokens
                    echo "<small><strong>Debug</strong><br />";
                    if ($access_token) {
                        echo "Token: $access_token<br />";
                        echo "Expected Expiration: " . human_time_diff(get_option($fpf_opt_token_expiration)) . "<br />";
                        $tokenResponse = fpf_get("https://graph.facebook.com/$fpf_apiver/debug_token?input_token=" . get_option($fpf_opt_access_token) . '&access_token=' . get_option($fpf_opt_access_token));
                        if (isset($tokenResponse->data->expires_at)) {
                            $expiresMin = (int) (($tokenResponse->data->expires_at - time()) / 60);
                            $expiresH = (int) ($expiresMin / 60);
                            $expiresMin -= $expiresH * 60;
                            echo "True Expiration: $expiresH" . "h $expiresMin" . "m";
                        } else
                            echo "True Expiration: Unknown";
                    } else
                        echo "Token: None";
                    echo "</small>";
                    ?>
            </div>
            <!--end tab-->

            <div class="<?php echo $allTabsClass ?>" id="<?php echo $tab2Id ?>" style="display:<?php echo ($fpf_shown_tab == 2 ? "block" : "none") ?>">
                <?php //SECTION - Search for albums
                    ?>

                <?php if (!get_option($fpf_opt_access_token)) : ?>
                    <h3><?php _e('Utilities', 'facebook-photo-fetcher') ?></h3>
                    <?php _e("Please use the 'Facebook Setup' tab to authorize your Facebook account.", 'facebook-photo-fetcher') ?><br /><br />
                <?php else : ?>
                    <h3><?php _e('Search for Albums', 'facebook-photo-fetcher') ?></h3>

                    <form name="listalbums" method="post" action="">
                        <?php _e('To get a list of album IDs that you can use to create galleries, enter a Facebook Page or User ID below and click "Search."', 'facebook-photo-fetcher') ?><br /><br />
                        <?php echo sprintf(__("Your User ID is %s.  To get a page's ID, visit the page &amp; click 'About,' and it should be listed at the bottom. If you can't find it, please Google 'How To Find Facebook Page ID' (their layout changes periodically). Note that you can only fetch photos from pages of which you are an admin.", 'facebook-photo-fetcher'), "<b>$user->id</b>", "<b>facebook.com/photos/a.123.456.789</b>", "789") ?><br /><br />
                        <input type="text" name="<?php echo $fpf_opt_last_uid_search ?>" value="<?php echo get_option($fpf_opt_last_uid_search) ?>" size="20">
                        <input type="submit" class="button-secondary" name="Submit" value="<? _e("Search", 'facebook-photo-fetcher') ?>" />
                    </form>

                    <?php
                            //If we just requested a search, do it and show results.
                            add_option($fpf_opt_last_uid_search, $user->id);
                            if (isset($_POST[$fpf_opt_last_uid_search])) {
                                //Get the name of the user/page whose ID we're searching
                                $search_uid = get_option($fpf_opt_last_uid_search);
                                $response = fpf_get("https://graph.facebook.com/$fpf_apiver/$search_uid?access_token=$access_token&fields=name");
                                if (!isset($response->name) || !$response->name) $search_name = "(" . __("Unknown User", 'facebook-photo-fetcher') . ")";
                                else $search_name = $response->name;

                                //Get the list of albums
                                $response = fpf_get("https://graph.facebook.com/$fpf_apiver/$search_uid/albums?access_token=$access_token&limit=999&fields=id,link,name,count");
                                if (isset($response->data)) $albums = $response->data;
                                else $albums = array();

                                //..And show the list.
                                ?><div class='postbox' style='margin-top:5px;'>
                            <h3 class='hndle' style='padding:6px;'>
                                <span><?php echo sprintf(__("Available Facebook Albums for %s:", 'facebook-photo-fetcher'), "<a href='http://www.facebook.com/profile.php?id=$search_uid' target='_fb'>$search_name</a>") ?></span>
                            </h3><?php
                                                echo "<div class='inside'><small>";
                                                if (is_array($albums) && count($albums) > 0)
                                                    foreach ($albums as $album) {
                                                        echo '<textarea style="width:350px;overflow:hidden;resize:none;line-height:1;font-family:inherit;font-size:inherit;" readonly="readonly" onclick="this.focus();this.select();">';
                                                        echo '&lt;!--' . $fpf_identifier . ' ' . $album->id . ' --&gt;&lt;!--/' . $fpf_identifier . '--&gt;';
                                                        echo '</textarea>';
                                                        echo '(<a target="searchedalbum" href="' . $album->link . '">' . $album->name . '</a>; ' . sprintf(__('%s photos', 'facebook-photo-fetcher'), $album->count) . ')<br />';
                                                    } else
                                                    echo __("None found.", 'facebook-photo-fetcher') . "<br />";
                                                echo "</small></div></div>";
                                            }
                                            ?>
                        <hr />

                        <?php //SECTION - Fetch all albums 
                                ?>
                        <h3><?php _e('Refresh Albums from Facebook', 'facebook-photo-fetcher') ?></h3>
                        <?php _e("This will scan all your posts and pages for galleries created with this plugin, and regenerate each one it finds by re-fetching its information from Facebook.  " .
                                    "The only reason to use this would be if you've changed or updated something in many of your albums and want those changes to be reflected here as well.  It can be slow if you have lots of galleries, so use with caution.", 'facebook-photo-fetcher') ?><br /><br />

                        <div class="postbox" style="width:400px; height:80px; padding:10px; float:left; text-align:center;">
                            <form name="fetchallposts" method="post" action="">
                                <input type="hidden" name="fetch_pages" value="Y">
                                <input type="submit" class="button-secondary" name="Submit" value="<?php _e('Re-Fetch All Albums in Pages', 'facebook-photo-fetcher') ?>" />
                            </form>
                            <br />
                            <form name="fetchallpages" method="post" action="">
                                <input type="hidden" name="fetch_posts" value="Y">
                                <input type="submit" class="button-secondary" name="Submit" value="<?php _e('Re-Fetch All Albums in Posts', 'facebook-photo-fetcher') ?>" />
                            </form>
                        </div>
                        <?php
                                //For an old custom addon I implemented for a customer; leave it for backwards-compatilibity.
                                if (function_exists('fpf_output_cron_panel')) fpf_output_cron_panel();
                                ?>
                        <br clear="all" />
                        <?php
                                //When we click one of the "fetch now" buttons  
                                if (isset($_POST['fetch_pages']) || isset($_POST['fetch_posts'])) {
                                    //Get the collection of pages or posts
                                    if (isset($_POST['fetch_pages'])) {
                                        echo "<b>" . __("Checking All Pages for Facebook Albums:", 'facebook-photo-fetcher') . "</b><br />";
                                        $pages = get_pages(array('post_status' => 'publish,private'));
                                    } else {
                                        echo "<b>" . __("Checking All Posts for Facebook Albums:", 'facebook-photo-fetcher') . "</b><br />";
                                        $pages = get_posts('post_type=post&numberposts=-1&post_status=publish,private');
                                    }

                                    echo "<div class='postbox' style='width:90%;padding:10px;'><pre>";
                                    echo fpf_refetch_all($pages, true);
                                    echo "</pre></div>";
                                }
                                ?>

                    <?php endif; //If not authorized 
                        ?>

                    <hr />
                    <h3><?php _e('Donate', 'facebook-photo-fetcher') ?></h3>
                    <?php _e("Many hours have gone into making this plugin as versatile and easy to use as possible, far beyond my own personal needs. Although I offer it to you freely, please keep in mind that each hour spent extending and supporting it was an hour that could've also gone towards income-generating work. If you find it useful, please consider donating to help support its continued development.", 'facebook-photo-fetcher') ?><br /><br />

                    <div style="float:left;text-align:center;width:200px;">
                        <b>Bitcoin Address:</b><br />
                        <textarea style="padding:0;font-size:11px;height:34px;background:none;" readonly="readonly" onclick="this.focus();this.select();">358B63YntkzmcdMbT7VKLENi8LRZZgpdWR</textarea>
                    </div>
                    <div style="float:left;text-align:center;width:200px;">
                        <b>Bitcoin QR Code:</b><br />
                        <img width="150" height="150" src="<?php echo plugins_url(dirname(plugin_basename(__FILE__))) ?>/img/qr.png" alt="QR" />
                    </div>
                    <div class="donateButton" style="float:left;text-align:center;width:200px;">
                        <b>PayPal:</b><br />
                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                            <input type="hidden" name="cmd" value="_s-xclick" />
                            <input type="hidden" name="hosted_button_id" value="L32NVEXQWYN8A" />
                            <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" />
                            <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
                        </form>
                    </div><br style="clear:left;" />

                        </div>
                        <!--end tab-->

                        <div class="<?php echo $allTabsClass ?>" id="<?php echo $tab3Id ?>" style="display:<?php echo ($fpf_shown_tab == 3 ? "block" : "none") ?>">
                            <h3><?php _e("Addon Options", 'facebook-photo-fetcher') ?> <small>(Version <?php echo defined('FPF_ADDON_VER') ? FPF_ADDON_VER : "?" ?>)</small></h3>
                            <?php do_action('fpf_addon_admin_tab'); ?>
                        </div>
                        <!--end tab-->

                        <div class="<?php echo $allTabsClass ?>" id="<?php echo $tab4Id ?>" style="display:<?php echo ($fpf_shown_tab == 4 ? "block" : "none") ?>">
                            <h3><?php _e('Support Information', 'facebook-photo-fetcher') ?></h3>
                            <div style="width:600px;">
                                <?php echo sprintf(__('Before submitting a support request, please make sure to carefully read all the documentation and FAQs on the <a href="%s">plugin homepage</a>.  Every problem that\'s ever been reported has a solution posted there.', 'facebook-photo-fetcher'), $fpf_homepage) ?><br /><br />
                                <?php echo sprintf(__('If you do choose to submit a request, please do so on the <a href="%s">plugin homepage</a>, not on Wordpress.org (which I rarely check).  Also, be sure to include the following information about your Wordpress hosting environment:', 'facebook-photo-fetcher'), $fpf_homepage) ?><br />
                            </div>
                            <div style="width:600px; padding:5px; margin:8px 0; background-color:#EEEDDA; border:1px solid #CCC;">
                                <b>Host URL: </b> <?php echo $_SERVER["HTTP_HOST"] ?><br />
                                <b>Site URL: </b> <?php echo get_bloginfo('url') ?><br />
                                <b>Wordpress URL: </b> <?php echo get_bloginfo('wpurl') ?><br />
                                <b>Wordpress Version:</b> <?php echo $GLOBALS['wp_version']; ?><br />
                                <b>Plugin Version:</b> <?php echo $fpf_version ?><br />
                                <b>Browser:</b> <?php echo $_SERVER['HTTP_USER_AGENT'] ?><br />
                                <b>Theme:</b> <?php echo wp_get_theme(); ?><br />
                                <b>Server:</b> <?php echo substr($_SERVER['SERVER_SOFTWARE'], 0, 45) . (strlen($_SERVER['SERVER_SOFTWARE']) > 45 ? "..." : ""); ?><br />
                                <b>Active Plugins:</b>
                                <?php $active_plugins = get_option('active_plugins');
                                    $plug_info = get_plugins();
                                    echo "<b>" . count($active_plugins) . "</b><small> (";
                                    foreach ($active_plugins as $name) echo $plug_info[$name]['Title'] . " " . $plug_info[$name]['Version'] . "; ";
                                    echo "</small>)<br />"
                                    ?>
                            </div>

                        </div>
                        <!--end tab-->

            </div><!-- div fpf-admin_wrapper -->
        </div><!-- div wrap -->
    <?php
    }


    /*
 * Go through each post/page and if it contains the magic tags, re-save it (which will cause the wp_insert_post filter to run)
 * Display or return a string summarizing what was done.
 */
    function fpf_refetch_all($pages, $printProgress = false)
    {
        //Increase the timelimit of the script to make sure it can finish
        if (!ini_get('safe_mode') && !strstr(ini_get('disabled_functions'), 'set_time_limit')) set_time_limit(500);

        $outputString = "";
        $total = count($pages);
        $index = 0;
        foreach ($pages as $page) {
            $index++;
            $outputString .= "$index/$total: $page->post_title......";
            if (!fpf_find_tags($page->post_content)) {
                $outputString .= __("No gallery tag found.", 'facebook-photo-fetcher') . "\n";
            } else {
                //Categories need special handling; before re-saving the post, we need to explicitly place a list of cats or they'll be lost.
                $cats = get_the_category($page->ID);
                $page->post_category = array();
                foreach ($cats as $cat) array_push($page->post_category, $cat->cat_ID);

                $outputString .= __("Found! Fetching...", 'facebook-photo-fetcher') . "\n.......<b>";
                if ($printProgress) {
                    echo $outputString;
                    $outputString = "";
                }
                wp_insert_post($page);
                $fetchCount = get_post_meta($page->ID, '_fpf_album_size', true);
                if (!$fetchCount) $fetchCount = "0";
                $outputString .= sprintf(__("%s photos fetched.", 'facebook-photo-fetcher'), $fetchCount) . "</b>\n";
            }
            if ($printProgress) {
                echo $outputString;
                $outputString = "";
            }
        }
        return $outputString;
    }




    /*
NOTES ON AUTHORIZATION:
->There are 2 kinds of tokens: App access tokens (which don't expire) and user access tokens (which do).
  I need a USER ACCESS TOKEN to query information; app access tokens are just for publishing.
  See https://developers.facebook.com/docs/opengraph/using-app-tokens 
  
->See https://developers.facebook.com/roadmap/offline-access-removal for where I got the following info...
  *User access tokens can either be short-lived (~2 hrs) or long-lived (~60 days).  Which you get depends on how you authenticated:
  *If your app is classified as "Native/Desktop," you always get long-lived (See 'Exception 1')
  *If your app is classified as "Web" and you authenticate with PHP (server-side), you get long-lived (See "Scenario 3")
  *If your app is classified as "Web" and you authenticate with Javascript (client-side), you get short-lived 
  *Once you have a short-lived access token, you can extend it to a long-lived access token, but long-lived access tokens can't be
   extended (See "Scenario 4").  Thus, getting short-lived and extending it is effectively the same as just getting long-lived 
   originally; you can't keep extending over and over.  'Extending' refers only to turning short-lived into long-lived.
  *Under Scenario 3, it specifically says: "The user must access your application before you're able to get a valid 
   "authorization Code" to be able to make the server-side OAuth call again. Apps will not be able to setup a background/cron job
   that tries to automatically extend the expiration time, because the "authorization code" is short-lived and will have expired."
  *In other words, once I have a long-lived token, there's no way to auto-extend it; the USER must do something to extend it.
 
->Whether you authorize on client or server side, it can only be done on the domain specified under "App Domains..."
  *In the case of server-side auth, the required redirect_uri (where it sends the access token) must be in the app domain.
   Wordbooker solves this by putting a PHP script on his own server, redirecting there after the login, parsing the access_token,
   and re-POSTing it back to the original admin panel (via a redirect URL he also had the Facebook auth pass to his server).  
   This was the first approach I took, but it was pretty complicated.  Also, the off-site POST was blocked by Bad-Behavior.
  *In the case of client-side auth, you can only init the JS auth dialog on the app domain.  An easy way around this is to init
   the dialog in an iFrame (on my server), and then have the iFrame communicate the token back to the parent (admin panel) once
   the popup dialog is closed.  You can communicate between the panel/iframe with URL hashing, or via a 3rd party library.
   See http://stackoverflow.com/questions/6642155/javascript-iframe-communication
   See http://softwareas.com/cross-domain-communication-with-iframes
  *I used the client-side auth approach - it seems cleaner, and doesn't require a page refresh to get the token.
   No redirects outside of the user's admin panel/site, either.
  *ADDENDUM: Later, the iFrame method broke, due to newer browsers not allowing cross-domain iFrames.  So I went back to the Wordbooker approach.

->SO, HOW CAN TOKENS BE RENEWED (SO THEY DON'T EXPIRE)?
  *Simplest: Just put an 'expires-in' indicator, and leave it up to the user to deauthorize and reauthorize before that time.
  *Idea: See "Server-side Login" here: https://developers.facebook.com/docs/howtos/login/debugging-access-tokens.
   In order to issue a new token, the user needs to be sent through the full authentication flow.  However, what if I just
   load the graph auth URL in an INVISIBLE iFrame, whenever the admin panel is loaded? As the docs say, this should be transparent
   and immediately redirect them to the redirect_url.  I'll set the redirect_url to my auth script, which can get the new token,
   and communicate it back to the panel with JS like I'm doing for the initial authentication, then store it in the DB with ajax.
   Since this is all happening in an invisible iFrame, they won't be aware of it - the token will just 'be updated.'
   The only downfall is that the user does have to actually use the admin panel (i.e. it's not as automatic as a cronjob), but
   they don't have to manually use a button to deauth/reauth.
*/


    /*
NOTES ON IDs:
->The original version of this plugin used actual AlbumIDs; I'm now using Graph Object IDs, which aren't the same.
  For example, my 'foursquare photos' album has the graph object_id '10100683023951354' - this is what's returned
  by graph calls like graph.facebook.com/me/albums, and what's returned by the v2 search panel.  Its true aid, however 
  (which is what the OLD plugin's search returned) is 14212660965572571.  Graph IDs are more convenient because the query format
  is the same regardless of whether we're getting photos from a user, page, etc (i.e. graph.facebook.com/pageid/albums is the same
  as graph.facebook.com/userid/albums, etc).

->Example queries:
  1) Search for graph IDs of albums:    https://graph.facebook.com/me/albums?access_token=<token>&limit=999&fields=id,link,name;
  2) Search for albumIDs:               https://graph.facebook.com/fql?q=SELECT+aid,name+FROM+album+WHERE+owner=me()&access_token=<token>
  3) Get photos from graph ID of album: https://graph.facebook.com/ID/photos?access_token=<token>
  4) Get photos from albumID:           https://graph.facebook.com/fql?q=SELECT+caption+FROM+photo+WHERE+aid='14212660965572571'&access_token=<token>
  5) You can also get photos from a graphID with FQL (alt for #3): https://graph.facebook.com/fql?q=SELECT+caption+FROM+photo+WHERE+album_object_id='10100683023951354'&access_token=<token>

->This change in ID scheme is why I changed the magic tag identifier from FBGallery to FBGallery2; existing magic tags
  wouldn't work without updating their IDs too, so by changing the identifier, existing albums will be left untouched 
  by the new plugin unless explicitly updated.
    
->Good info on this: http://facebook.stackoverflow.com/questions/6022425/facebook-graph-api-get-a-photos-album-id
  (Specifically, see Richard Barnett's answer) 
*/
    ?>