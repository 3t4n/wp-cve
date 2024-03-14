<p class="sbspf-contents-links" id="general">
    <span>Quick links: </span>
    <a href="#layout"><?php _e( 'Layout', $text_domain ); ?></a>
    <a href="#info_display"><?php _e( 'Info Display', $text_domain ); ?></a>
    <a href="#header"><?php _e( 'Header', $text_domain ); ?></a>
    <a href="#loadmore"><?php _e( 'Buttons', $text_domain ); ?></a>
    <a href="#experience"><?php _e( 'Experience', $text_domain ); ?></a>
    <a href="#moderation"><?php _e( 'Moderation', $text_domain ); ?></a>
    <a href="#gdpr"><?php _e( 'GDPR', $text_domain ); ?></a>
    <a href="#advanced"><?php _e( 'Advanced', $text_domain ); ?></a>

</p>

<form method="post" action="">
	<?php $this->hidden_fields_for_tab( 'customize' ); ?>

	<?php foreach ( $this->get_sections( 'customize' ) as $section ) : ?>
        <span id="<?php echo str_replace( 'sbspf_', '', $section['id'] ); ?>"></span>
		<?php do_settings_sections( $section['id'] ); // matches the section name ?>

    <?php if ( $section['id'] === 'sbspf_advanced' ) {
    $usage_tracking = get_option( 'sby_usage_tracking', false );

    if ( isset( $_POST['sby_settings'] ) ) {
	    $usage_tracking['enabled'] = false;
	    if ( isset( $_POST['sby_usage_tracking_enable'] ) ) {
		    $usage_tracking['enabled'] = true;
	    }
	    update_option( 'sby_usage_tracking', $usage_tracking, false );
    }
    $sby_usage_tracking_enable = isset( $usage_tracking['enabled'] ) ? $usage_tracking['enabled'] : true;

    // only show this setting after they have opted in or opted out using the admin notice
    ?>
    <table class="form-table" role="presentation">
        <tbody>
        <tr>

            <th scope="row"><label class="bump-left"><?php _e("Enable Usage Tracking", $text_domain ); ?></label></th>
            <td>
                <input name="sby_usage_tracking_enable" type="checkbox" id="sby_usage_tracking_enable" <?php if( $sby_usage_tracking_enable ) echo "checked"; ?> />
                <label for="sby_usage_tracking_enable"><?php _e('Yes', $text_domain); ?></label>
                <a class="sbspf_tooltip_link" href="JavaScript:void(0);"><?php _e('What is usage tracking?', $text_domain ); ?></a>
                <p class="sbspf_tooltip sbspf_more_info"><?php _e("Feeds for YouTube will record information and statistics about your site in order for the team at Smash Balloon to learn more about how our plugins are used. The plugin will never collect any sensitive information like access tokens, email addresses, or user information.", $text_domain ); ?> <a href='https://smashballoon.com/youtube-feed-old/docs-old/usage-tracking/' target='_blank'><?php _e("See here", $text_domain ); ?></a> <?php _e("for more information.", $text_domain ); ?></p>
            </td>
        </tr>
        </tbody>
    </table>
        <?php } ?>
		<?php if ( $section['save_after'] ) : ?>
            <p class="submit"><input class="button-primary" type="submit" name="save" value="<?php esc_attr_e( 'Save Changes' ); ?>" /></p>
        <?php endif; ?>
        <hr>
	<?php endforeach; ?>

</form>
