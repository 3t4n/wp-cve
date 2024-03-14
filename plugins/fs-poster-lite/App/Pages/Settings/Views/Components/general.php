<?php

namespace FSPoster\App\Pages\Settings\Views;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;
?>

<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Hide FS Poster for', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Select the user roles to hide the FS Poster plugin for some users.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<select class="fsp-form-input select2-init" id="fs_hide_for_roles" name="fs_hide_for_roles[]" multiple>
			<?php
			$hideForRoles = explode( '|', Helper::getOption( 'hide_menu_for', '', TRUE ) );
			$wp_roles     = get_editable_roles();
			foreach ( $wp_roles as $roleId => $roleInf )
			{
				if ( $roleId === 'administrator' )
				{
					continue;
				}

				echo '<option value="' . htmlspecialchars( $roleId ) . '"' . ( in_array( $roleId, $hideForRoles ) ? ' selected' : '' ) . '>' . htmlspecialchars( $roleInf[ 'name' ] ) . '</option>';
			}
			?>
		</select>
	</div>
</div>
<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Show FS Poster column on the posts table', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo ! empty( [ Pages::asset( 'Base', 'img/fs_poster_column_help.png' ) ] ) ? __( vsprintf(  'If you don\'t want to show FS Poster <i class="far fa-question-circle fsp-tooltip"  data-title="Click to learn more" data-open-img="%s"></i> column on posts table, you can disable this option.', [ Pages::asset( 'Base', 'img/fs_poster_column_help.png' ) ] ), 'fs-poster' ) : __( 'If you don\'t want to show FS Poster <i class="far fa-question-circle fsp-tooltip"  data-title="Click to learn more" data-open-img="%s"></i> column on posts table, you can disable this option.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<div class="fsp-toggle">
			<input type="checkbox" name="fs_show_fs_poster_column" class="fsp-toggle-checkbox" id="fs_show_fs_poster_column"<?php echo Helper::getOption( 'show_fs_poster_column', '1', TRUE ) ? ' checked' : ''; ?>>
			<label class="fsp-toggle-label" for="fs_show_fs_poster_column"></label>
		</div>
	</div>
</div>
<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Hide notifications', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Enable the option to hide notifications for failed posts and disconnected accounts.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<div class="fsp-toggle">
			<input type="checkbox" name="fs_hide_notifications" class="fsp-toggle-checkbox" id="fspHideNotifications" <?php echo Helper::getOption( 'hide_notifications', '0', TRUE ) ? 'checked' : ''; ?>>
			<label class="fsp-toggle-label" for="fspHideNotifications"></label>
		</div>
	</div>
</div>
<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Collect FS Poster statistics', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'The plugin appends a "feed_id" parameter to a post link to get statistics. Disabling the option prevents you from getting statistics in the Dashboard tab. And because the plugin does not collect statistics, you might also have duplicate posts on Social Networks even if you select the "Randomly (without duplicates)" option when you use the schedule module.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<div class="fsp-toggle">
			<input type="checkbox" name="fs_collect_statistics" class="fsp-toggle-checkbox" id="fs_collect_statistics"<?php echo Helper::getOption( 'collect_statistics', '1', TRUE ) ? ' checked' : ''; ?>>
			<label class="fsp-toggle-label" for="fs_collect_statistics"></label>
		</div>
	</div>
</div>
<div class="fsp-settings-row">
    <div class="fsp-settings-col">
        <div class="fsp-settings-label-text"><?php echo esc_html__( 'Share posts automatically', 'fs-poster' ); ?></div>
        <div class="fsp-settings-label-subtext"><?php echo esc_html__( 'When you publish a new post, the plugin shares the post on all active social accounts automatically.', 'fs-poster' ); ?></div>
    </div>
    <div class="fsp-settings-col">
        <div class="fsp-toggle">
            <input type="checkbox" name="fs_auto_share_new_posts" class="fsp-toggle-checkbox" id="fs_auto_share_new_posts"<?php echo Helper::getOption( 'auto_share_new_posts', '1', TRUE ) ? ' checked' : ''; ?>>
            <label class="fsp-toggle-label" for="fs_auto_share_new_posts"></label>
        </div>
    </div>
</div>
<div class="fsp-settings-row">
    <div class="fsp-settings-col">
        <div class="fsp-settings-label-text"><?php echo esc_html__( 'Keep the shared post log', 'fs-poster' ); ?></div>
        <div class="fsp-settings-label-subtext"><?php echo esc_html__( 'If you don\'t want to keep the shared post logs, you need to disable the option. Disabling the option prevents you view your insights.', 'fs-poster' ); ?></div>
    </div>
    <div class="fsp-settings-col">
        <div class="fsp-toggle">
            <input type="checkbox" name="fs_keep_logs" class="fsp-toggle-checkbox" id="fs_keep_logs"<?php echo Helper::getOption( 'keep_logs', '1', TRUE ) ? ' checked' : ''; ?>>
            <label class="fsp-toggle-label" for="fs_keep_logs"></label>
        </div>
    </div>
</div>