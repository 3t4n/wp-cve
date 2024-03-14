<?php if ( $active_tab == 'multisite' ): ?>
<?php
    // Pagination setup
    $limit  = 50;
    $offset = 0;

	// phpcs:disable WordPress.Security.NonceVerification.Recommended
    if ( isset( $_GET['offset'] ) ) {
        $offset = intval( $_GET['offset'] );
    }

    if ( isset( $_GET['limit'] ) ) {
        $limit = intval( $_GET['limit'] );
    }

    $pagination = Sendgrid_Tools::get_multisite_pagination( $offset, $limit );
    $sites = get_sites( array( 'offset' => $offset, 'number' => $limit ) );
	
	// phpcs:enable WordPress.Security.NonceVerification.Recommended
	
?>

<p class="description">
    <?php
        printf(
			// translators: %s = <br>, <br>, <strong>, </strong>
			esc_html__( 'On this page you can grant each subsite the ability to manage SendGrid settings. %sIf the checkbox is unchecked then that site will not see the SendGrid settings page and will use the settings set on the network. %s%sWarning!%s When you activate the management for a subsite, that site will not be able to send emails until the subsite admin updates his SendGrid settings.', 'connect-sendgrid-for-emails' ),
			'<br>',
			'<br>',
			'<strong>',
			'</strong>'
		);
    ?>
</p>

<p class="sendgrid-multisite-pagination">
    <?php
	   // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML output
       echo $pagination['previous_button'] . ' ' . $pagination['next_button'];
    ?>
</p>

<form method="POST" action="<?php echo esc_attr(Sendgrid_Tools::get_form_action()); ?>">
<table class="widefat fixed" id="subsites-table-sg" cellspacing="0">
    <thead>
        <tr valign="top">
            <th scope="col" class="manage-column column-columnname num" colspan="5">
                <?php
                    printf( esc_html__( 'Page %d of %d' ), (int) $pagination['current_page'], (int) $pagination['total_pages'] );
                ?>
            </th>
        </tr>
        <tr valign="top">
            <th scope="col" class="manage-column column-columnname num"> <?php esc_html_e( 'ID', 'connect-sendgrid-for-emails' ); ?></th>
            <th scope="col" class="manage-column column-columnname"> <?php esc_html_e( 'Name', 'connect-sendgrid-for-emails' ); ?></th>
            <th scope="col" class="manage-column column-columnname"> <?php esc_html_e( 'Public', 'connect-sendgrid-for-emails' ); ?></th>
            <th scope="col" class="manage-column column-columnname"> <?php esc_html_e( 'Site URL', 'connect-sendgrid-for-emails' ); ?></th>
            <th scope="col" class="manage-column"><input style="margin:0 0 0 0px;" type="checkbox" id="sg-check-all-sites"/> <?php esc_html_e( 'Self-Managed?', 'connect-sendgrid-for-emails' ); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $sites as $index => $site ): ?>
            <?php if ( ! is_main_site( $site->blog_id ) ): ?>
                <?php $site_info = get_blog_details ($site->blog_id ); ?>
                    <tr <?php echo ( $index % 2 == 1 ) ? 'class="alternate"' : ''?>>
                        <td class="column-columnname num" scope="row"><?php echo (int) $site_info->blog_id; ?></td>
                        <td class="column-columnname" scope="row"><?php echo esc_html($site_info->blogname); ?></td>
                        <td class="column-columnname" scope="row"><?php echo $site_info->public ? "true" : "false"; ?></td>
                        <td class="column-columnname" scope="row">
                            <a href="<?php echo esc_url($site_info->siteurl); ?>"><?php echo esc_url($site_info->siteurl); ?><a>
                        </td>
                        <td class="column-columnname" scope="row" aligh="center">
                            <input type="checkbox" id="check-can-manage-sg" name="checked_sites[<?php echo (int) $site_info->blog_id ?>]"
                                <?php echo ( get_blog_option( $site_info->blog_id, 'sendgrid_can_manage_subsite', 0 ) ? "checked" : "" ) ?> />
                        </td>
                    </tr>
                <?php endif; ?>
        <?php endforeach; ?>
    </tbody>
</table>
<p class="sendgrid-multisite-pagination">
    <?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML output
        echo $pagination['previous_button'] . ' ' . $pagination['next_button'];
    ?>
</p>
<p class="sendgrid-multisite-submit">
    <input type="submit" id="doaction" class="button button-primary" value="<?php esc_html_e( 'Save Settings', 'connect-sendgrid-for-emails' ); ?>">
</p>
<input type="hidden" name="subsite_settings" value="true"/>
<input type="hidden" name="sgnonce" value="<?php echo esc_attr( wp_create_nonce('sgnonce') ); ?>"/>
</form>
<?php endif; ?>