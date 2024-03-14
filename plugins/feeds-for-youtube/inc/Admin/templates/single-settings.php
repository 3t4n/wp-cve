
<?php

use SmashBalloon\YouTubeFeed\Pro\SBY_CPT;
use SmashBalloon\YouTubeFeed\Pro\SBY_YT_Query;

$sby_videos_settings = SBY_CPT::get_sby_cpt_settings();

if ( isset( $_POST[ SBY_CPT . '_settings_tab_marker' ] ) ) {
	$valid_options = SBY_CPT::validate_options( $_POST[ SBY_CPT . '_settings' ], SBY_CPT . '_settings' );
	update_option( SBY_CPT . '_settings', $valid_options );
	$sby_videos_settings = $valid_options;
	?>
    <div class="updated"><p><strong><?php _e('Settings saved.', SBY_CPT ); ?></strong></p></div>
	<?php
}
?>

<div id="sbspf_admin" class="sby_cpt_manager_wrap wrap">
    <h1 class="wp-heading-inline"><?php _e( 'Manage Single Videos', SBY_TEXT_DOMAIN  ); ?></h1>

    <h2 class="wp-heading-inline"><?php _e( 'Single Video Settings', SBY_TEXT_DOMAIN  ); ?></h2>
    <form method="post" action="">
        <?php
        wp_nonce_field( SBY_CPT . '_settings_validate', SBY_CPT . '_settings_validate', true, true );
        ?>
        <input type="hidden" name="<?php echo SBY_CPT . '_settings_tab_marker'; ?>" value="1"/>
    <table class="form-table">
        <tbody>
        <tr valign="top">
            <th scope="row"><label><?php _e( 'Video Post Status', SBY_TEXT_DOMAIN  ); ?></label></th>
            <td>
                <label for="sby_video_setting_post_status"><?php _e( 'Save new video posts as ', SBY_TEXT_DOMAIN  ); ?></label>
                <select name="<?php echo SBY_CPT::setting_name( 'post_status' ); ?>" id="sby_video_setting_post_status">
                    <option value="draft"<?php if ( $sby_videos_settings['post_status'] === 'draft' ) echo ' selected'; ?>><?php _e( 'Draft', SBY_TEXT_DOMAIN  ); ?></option>
                    <option value="publish"<?php if ( $sby_videos_settings['post_status'] === 'publish' ) echo ' selected'; ?>><?php _e( 'Publish', SBY_TEXT_DOMAIN  ); ?></option>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <?php
            $text_domain = SBY_TEXT_DOMAIN;
            $include_options = array(
	            array(
		            'label' => __( 'Title', $text_domain ),
		            'value' => 'title'
	            ),
	            array(
		            'label' => __( 'User Name', $text_domain ),
		            'value' => 'user'
	            ),
	            array(
		            'label' => __( 'Views', $text_domain ),
		            'value' => 'views'
	            ),
	            array(
		            'label' => __( 'Date', $text_domain ),
		            'value' => 'date'
	            ),
	            array(
		            'label' => __( 'Live Stream Countdown (when applies)', $text_domain ),
		            'value' => 'countdown'
	            ),
	            array(
		            'label' => __( 'Stats (like and comment counts)', $text_domain ),
		            'value' => 'stats'
	            )
            )
            ?>
            <th scope="row"><label><?php _e( 'Information to Display', SBY_TEXT_DOMAIN  ); ?></label></th>
            <td>
                <?php foreach ( $include_options as $include_option ) :
                    $checked = in_array( $include_option['value'], $sby_videos_settings['include'], true ) ? ' checked="checked"' : '';
                    ?>
                <div>
                    <input name="<?php echo SBY_CPT::setting_name( 'include', true ); ?>" type="checkbox" id="sby_video_setting<?php echo $include_option['value']; ?>" value="<?php echo $include_option['value']; ?>"<?php echo $checked; ?>>
                    <label for="sby_video_setting<?php echo $include_option['value']; ?>"><?php echo $include_option['label']; ?></label>
                </div>
                <?php endforeach; ?>
            </td>
        </tr>
        <?php do_action( 'sby_single_settings_after_settings', $sby_videos_settings ); ?>

        </tbody>
    </table>
        <p class="submit"><input class="button-primary" type="submit" name="save" value="<?php esc_attr_e( 'Save Changes' ); ?>" /></p>

    </form>
    <div class="sby-channel-importer">
        <h2 class="wp-heading-inline"><?php _e( 'Import Videos', SBY_TEXT_DOMAIN  ); ?></h2>
        <p class="sbspf_aside"><?php _e( 'Generate WordPress posts from your YouTube channel. Enter the channel name or ID and the number of videos to import below and click "Import".', SBY_TEXT_DOMAIN  ); ?></p>
        <input type="text" id="sby_import" name="sby_import_channel" value="" placeholder="<?php _e( 'Channel name or ID', SBY_TEXT_DOMAIN  ); ?>"><input type="number" name="sby_import_number" min="1" max="5000" id="sby_num" value="100"><button id="sby_do_import" class="button button-secodary"><?php _e( 'Import', SBY_TEXT_DOMAIN  ); ?></button>
        <div class="sby_status" style="display: none">
            <strong><?php _e( 'Remaining: ', SBY_TEXT_DOMAIN  ); ?></strong>
            <div class="sby_status_bar">
                <span class="sby_remaining">0</span>
            </div>
        </div>
        <input type="hidden" id="sby_import_data" name="sby_import_data" data-next="" value="">
    </div>
    <h2 class="wp-heading-inline"><?php _e( 'Manage Videos By Channel', SBY_TEXT_DOMAIN  ); ?></h2>
    <table class="wp-list-table widefat fixed striped posts">
        <thead>
        <tr>
            <td id="cb" class="manage-column column-cb check-column">
                <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                <input id="cb-select-all-1" type="checkbox">
            </td>
            <th scope="col" id="title" class="manage-column column-title column-primary">
                <span><?php _e( 'Channel Title', SBY_TEXT_DOMAIN ) ?></span>
            </th>
            <th scope="col" id="date" class="manage-column column-published">
                <span><?php _e( 'Published', SBY_TEXT_DOMAIN ) ?></span>
            </th>
            <th scope="col" id="channel_title" class="manage-column column-drafts">
                <span><?php _e( 'Drafts', SBY_TEXT_DOMAIN ) ?></span>
            </th>
        </tr>
        </thead>

        <tbody id="the-list">

        <?php
        $channels = SBY_YT_Query::get_unique_channel_ids();
        foreach ( $channels as $channel_id ) :
            if ( ! empty( $channel_id ) ) :
	        $args = array(
		        'channel_id' => $channel_id,
		        'post_status' => array( 'draft', 'pending' ),
		        'posts_per_page' => -1
	        );
	        $draft_posts = new SBY_YT_Query( $args );
	        $draft_posts_arr = $draft_posts->get_posts();
	        $num_draft_posts = count( $draft_posts_arr );

	        $first_post = isset( $draft_posts_arr[0] ) ? $draft_posts_arr[0] : false;


	        $args = array(
		        'channel_id' => $channel_id,
		        'post_status' => array( 'publish' ),
		        'posts_per_page' => -1
	        );
	        $publish_posts = new SBY_YT_Query( $args );
	        $publish_posts_arr = $publish_posts->get_posts();
	        $num_publish_posts = count( $publish_posts_arr );

	        if ( ! $first_post && isset( $publish_posts_arr[0] ) ) {
		        $first_post = $publish_posts_arr[0];
            }

	        if ( $first_post ) :

	        $channel_title = get_post_meta( $first_post->ID, 'sby_channel_title', true );
	        ?>
            <tr id="post-<?php echo esc_attr( $channel_id ); ?>" class="iedit author-self post-1525 type-sby_channels status-draft hentry entry">
                <th scope="row" class="check-column">
                    <label class="screen-reader-text" for="cb-select-<?php echo esc_attr( $channel_id ); ?>"><?php echo esc_html( $channel_title ); ?></label>
                    <input id="cb-select-<?php echo esc_attr( $channel_id ); ?>" type="checkbox" name="channel[]" value="<?php echo esc_attr( $channel_id ); ?>">
                    <div class="locked-indicator">
                        <span class="locked-indicator-icon" aria-hidden="true"></span>
                        <span class="screen-reader-text"><?php echo esc_html( $channel_title ); ?></span>
                    </div>
                </th>
                <td class="title column-title has-row-actions column-primary page-title" data-colname="Title"><div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
                    <strong><?php echo esc_html( $channel_title ); ?></strong>

                    <div class="hidden" id="inline_1525">
                        <div class="post_title"><?php echo esc_html( $channel_title ); ?></div><div class="post_name"></div>
                    </div>
                    <div class="row-actions">
                        <span class="sby_view"><a href="<?php echo admin_url( 'edit.php?post_type=sby_videos' ); ?>&channel_id=<?php echo urlencode( $channel_id ); ?>" aria-label="View <?php echo esc_html( $channel_title ); ?>"><?php _e ( 'View All' ); ?></a> | </span>
                        <span class="sby_publish"><a href="<?php echo admin_url( 'admin.php?page=youtube-feed-single-videos' ); ?>&sby_action=publish&channel=<?php echo urlencode( $channel_id ); ?>" aria-label="Publish <?php echo esc_html( $channel_title ); ?>"><?php _e ( 'Publish All' ); ?></a> | </span>
                        <span class="sby_trash"><a href="<?php echo admin_url( 'admin.php?page=youtube-feed-single-videos' ); ?>&sby_action=trash&channel=<?php echo urlencode( $channel_id ); ?>" aria-label="Trash <?php echo esc_html( $channel_title ); ?>"><?php _e ( 'Trash All' ); ?></a></span>
                    </div>
                </td>
                <td class="column-published" data-colname="<?php _e( 'Published', SBY_TEXT_DOMAIN ) ?>"><?php echo esc_html( $num_publish_posts ); ?></td>
                <td class="column-drafts" data-colname="<?php _e( 'Drafts', SBY_TEXT_DOMAIN ) ?>"><?php echo esc_html( $num_draft_posts ); ?></td>
            </tr>

        <?php endif; endif; endforeach; ?>

        </tbody>

        <tfoot>
        <tr>
            <td id="cb" class="manage-column column-cb check-column">
                <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                <input id="cb-select-all-2" type="checkbox">
            </td>
            <th scope="col" id="title" class="manage-column column-title column-primary">
                <span><?php _e( 'Channel Title', SBY_TEXT_DOMAIN ) ?></span>
            </th>
            <th scope="col" id="date" class="manage-column column-published">
                <span><?php _e( 'Published', SBY_TEXT_DOMAIN ) ?></span>
            </th>
            <th scope="col" id="channel_title" class="manage-column column-drafts">
                <span><?php _e( 'Drafts', SBY_TEXT_DOMAIN ) ?></span>
            </th>
        </tfoot>

    </table>





</div>