<style>
* {
  box-sizing: border-box;
}
</style>
<div>
    <h2>Quick Child Theme Generator</h2>
    <div id="tabs" class="m-r-20">
        <ul>
            <li><a href="#tabs-1">Create Child Theme</a></li>
            <li><a href="#tabs-2">Info</a></li>
        </ul>
        <div id="tabs-1">
            <?php
            $getThemes = QCTHG_Helper::qcthgGetParntThemesList();
            if(empty($getThemes)) {
                wp_die('Something went wrong! Themes not found.');
            }
            $getTheme = wp_get_theme();
            ?>
            <div style="font-family:cursive;">
                <h3 style="color:#444 !important;" class="afterNoticesCls">Create a new child theme from the parent theme<p>Active Theme: ( <?php echo $getTheme; ?> )</p></h3>
                <hr>
            </div>
            <div>
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" autocomplete="on">
                    <input type="hidden" name="action" value="qcthg_create_theme">
                    <?php wp_nonce_field( 'qcthg_create_theme', '_wpnonce' ); ?>
                    <table style="color:#333;" border="0px" cellspacing="8px">
                        <tr>
                            <th class="txt-left">Select Parent Theme&nbsp;<span style="color:#d43f3a;">*</span></th>
                            <td>
                                <select name="theme_template">
                                    <?php
                                    foreach($getThemes as $themeVal) {
                                    ?>
                                        <option value="<?php esc_attr_e($themeVal["Template"]); ?>"><?php esc_html_e(($getTheme['Template'] === $themeVal["Template"]) ? $themeVal["Name"] . ' (Active) ' : $themeVal["Name"]); ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th class="txt-left">Theme Name&nbsp;<span style="color:#d43f3a;">*</span></th>
                            <td>
                                <input type="text" placeholder="Child Theme Name" name="child_theme_name" value="" maxlength="40" required>
                            </td>
                        </tr>

                        <tr>
                            <th class="txt-left">Theme URL</th>
                            <td>
                                <input type="text" placeholder="Child Theme URL" name="child_theme_url" value="">
                            </td>
                        </tr>

                        <tr>
                            <th class="txt-left">Description</th>
                            <td>
                                <input type="text" placeholder="Child Theme Description" name="child_theme_desc" value="" autocomplete="off">
                            </td>
                        </tr>

                        <tr>
                            <th class="txt-left">Author</th>
                            <td>
                                <input type="text" placeholder="Child Theme Author" name="child_theme_author" value="">
                            </td>
                        </tr>

                        <tr>
                            <th class="txt-left">Author URL</th>
                            <td>
                                <input type="text" placeholder="Child Theme Author URL" name="child_theme_author_url" value="">
                            </td>
                        </tr>

                        <tr>
                            <th class="txt-left">Version</th>
                            <td>
                                <input type="text" placeholder="1.0.0" name="child_theme_version" value="" autocomplete="off">
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <button type="submit" class="bttn bttn-primary">Create Now</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div id="tabs-2">
            <div class="info-row">
                <div class="info-column">
                    <p class="info-box">About Plugin</p>
                    <p>Hello, <i>Quick Child Theme Generator</i> is a very easy, quick and user friendly plugin to create a child theme.<br/>
                    Thank you for using <i>Quick Child Theme Generator</i> and this plugin is free. So, you can use it whenever you wish.<br/><br/>
                    </p>
                </div>
                <div class="info-column">
                    <p class="info-box">Rate & Review</p>
                    <p>Your feedback and review both are important for this plugin. Please <a href="https://wordpress.org/support/plugin/quick-child-theme-generator/reviews/" target="_blank" class="anchor-tag">Rate this plugin</a>.</p>
                </div>
                <div class="info-column">
                    <p class="info-box">Reach me</p>
                    <p>
                        <a href="https://sharmajay.com/" target="_blank" class="anchor-tag">Click to reach on the web</a><br/>
                        <a href="https://wordpress.org/plugins/quick-child-theme-generator/" target="_blank" class="anchor-tag">Click to reach on wordpress.org</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
