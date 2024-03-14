<style>
* {
  box-sizing: border-box;
}
td.borderedBottom {
    border-bottom: 1px solid #CCC;
    font-family: cursive;
    padding-top: 10px;
}
</style>
<div>
    <h2>Quick Child Theme Generator</h2>
    <div id="tabs" class="m-r-20">
        <ul>
            <li><a href="#tabs-1">Create Template</a></li>
        </ul>
        <div id="tabs-1">
            <?php
            if(is_child_theme()) {
                $getChildTheme = wp_get_theme();
            ?>
                <div style="font-family:cursive;">
                    <h3 style="color:#444 !important;" class="afterNoticesCls">Create a new blank template for child theme<p>Active Child Theme: ( <?php echo $getChildTheme; ?> )</p></h3>
                    <hr>
                </div>
                <div>
                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" autocomplete="on">
                        <input type="hidden" name="action" value="qcthg_create_template">
                        <?php wp_nonce_field( 'qcthg_create_template', '_wpnonce' ); ?>
                        <table style="color:#333;" border="0px" cellspacing="8px">
                            <tr>
                                <th class="txt-left">Template Name&nbsp;<span style="color:#d43f3a;">*</span>&nbsp;<span title="Template name is your custom page name that will appear you in the admin pages (Template name like this: Home Page)" class="dashicons dashicons-info"></span>&nbsp;<a href="https://prnt.sc/18rb1dv" title="Click for admin page templates reference" class="dashicons dashicons-admin-links" target="_blank"></a></th>
                                <td>
                                    <input type="text" placeholder="Template Name" name="tmp_name" value="" maxlength="40" required>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" class="borderedBottom">
                                    <span title="These are optional, in case child theme have custom header/footer." class="dashicons dashicons-info"></span>
                                    <span style="color:#e3a825;"><b>Optional for custom header/footer</b></span>
                                </td>
                            </tr>

                            <tr>
                                <th class="txt-left">Header Name&nbsp;<span title="In case your child theme have custom header file (e.g. header-cus for header-cus.php file)" class="dashicons dashicons-info"></span></th>
                                <td>
                                    <input type="text" placeholder="Theme Header Name" name="tmp_header_name" value="" maxlength="40">
                                </td>
                            </tr>

                            <tr>
                                <th class="txt-left">Footer Name&nbsp;<span title="In case your child theme have custom footer file (e.g. footer-cus for footer-cus.php file)" class="dashicons dashicons-info"></span></th>
                                <td>
                                    <input type="text" placeholder="Theme Footer Name" name="tmp_footer_name" value="" maxlength="40">
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <button type="submit" class="bttn bttn-primary">Create Template</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            <?php } else { ?>
                <div><b>No Child theme activated. Create a new child theme or activate existing child theme first.<b></div>
            <?php } ?>
        </div>

    </div>
</div>
