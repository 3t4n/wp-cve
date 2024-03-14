<?php
if(!empty($accessToken)) {
    $tokenLength = strlen($accessToken);
    $abbreviatedToken = substr($accessToken, 0, 12) . "......" .
        substr($accessToken, $tokenLength - 16, 16) . " &#x2705;";
}
else {
    $abbreviatedToken = "- not authenticated -";
}

?>

<form method="post" action="options.php">

    <?php settings_fields('mm-video'); ?>
    <?php do_settings_sections('mm-video'); ?>

    <table class="form-table">

        <tr valign="bottom">
            <th scope="row">Access Token</th>
            <td>
                <input type="text" disabled="disabled"
                       value="<?php echo esc_attr($abbreviatedToken); ?>"
                       class="regular-text code"/>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Client ID</th>
            <td>
                <input type="text" name="mm_client_id"
                       value="<?php echo esc_attr($clientId); ?>"
                       class="regular-text code" />
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">Client Secret</th>
            <td>
                <input type="text" name="mm_client_secret"
                       value="<?php echo esc_attr($clientSecret); ?>"
                       class="regular-text code" />
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">Tenant ID</th>
            <td>
                <input type="text" name="mm_tenant_id"
                       value="<?php echo esc_attr($tenantId); ?>"
                       class="regular-text code" />
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">Property ID</th>
            <td>
                <input type="text" name="mm_property_id"
                       value="<?php echo esc_attr($propertyId); ?>"
                       class="regular-text code" />
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">Player ID</th>
            <td>
                <input type="text" name="mm_player_id"
                       value="<?php echo esc_attr($playerId); ?>"
                       class="regular-text code" />
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">Enable Featured Video</th>
            <td>
                <input type="checkbox" name="mm_enable_featured_video"
                       value="1" <?php if($enableFeaturedVideo) { echo esc_attr("checked"); } ?>/>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">Enable Video Upload</th>
            <td>
                <input type="checkbox" name="mm_enable_video_upload"
                       value="1" 
                       <?php 
                            if($enableVideoUpload) { echo esc_attr("checked"); } 
                       ?>
                       />
            </td>
        </tr>
    </table>

    <?php submit_button(); ?>
</form>