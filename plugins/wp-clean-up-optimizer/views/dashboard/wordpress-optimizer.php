<?php
/**
 * This Template is used for managing WordPress data manually.
 *
 * @author Tech Banker
 * @package wp-cleanup-optimizer/views/dashboard
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly
if ( ! is_user_logged_in() ) {
	return;
} else {
	$access_granted = false;
	if ( isset( $user_role_permission ) && count( $user_role_permission ) > 0 ) {
		foreach ( $user_role_permission as $permission ) {
			if ( current_user_can( $permission ) ) {
				$access_granted = true;
				break;
			}
		}
	}
	if ( ! $access_granted ) {
		return;
	} elseif ( WORDPESS_OPTIMIZER_CLEAN_UP_OPTIMIZER === '1' ) {
		$wordpress_data_manual_clean_up = wp_create_nonce( 'wordpress_data_manual_clean_up' );
		$empty_manual_clean_up          = wp_create_nonce( 'empty_manual_clean_up' );
		?>
		<div class="page-bar">
			<ul class="page-breadcrumb">
			<li>
				<i class="icon-custom-home"></i>
				<a href="admin.php?page=cpo_dashboard">
					<?php echo esc_attr( $cpo_clean_up_optimizer ); ?>
				</a>
				<span>></span>
			</li>
			<li>
				<a href="admin.php?page=cpo_dashboard">
					<?php echo esc_attr( $cpo_dashboard ); ?>
				</a>
				<span>></span>
			</li>
			<li>
				<span>
					<?php echo esc_attr( $cpo_wp_optimizer ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-note"></i>
						<?php echo esc_attr( $cpo_wp_optimizer ); ?>
					</div>
					<p class="premium-editions-clean-up-optimizer">
							<?php echo esc_attr( $cpo_upgrade_know_about ); ?> <a href="<?php echo esc_attr( TECH_BANKER_BETA_URL ); ?>" target="_blank" class="premium-editions-documentation"> <?php echo esc_attr( $cpo_full_features ); ?></a> <?php echo esc_attr( $cpo_chek_our ); ?> <a href="<?php echo esc_attr( TECH_BANKER_BETA_URL ); ?>/backend-demos" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpo_online_demos ); ?></a>
						</p>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_manual_clean_up">
						<div class="form-body">
						<div class="table-margin-top">
							<select name="ux_ddl_bulk_action" id="ux_ddl_bulk_action" class="custom-bulk-width">
								<option value=""><?php echo esc_attr( $cpo_bulk_action_dropdown ); ?></option>
								<option value="empty"><?php echo esc_attr( $cpo_empty ); ?></option>
							</select>
							<input type="button" id="ux_btn_apply" name="ux_btn_apply" class="btn vivid-green" value="<?php echo esc_attr( $cpo_apply ); ?>" onclick="bulk_empty_clean_up_optimizer();">
						</div>
						<div class="line-separator"></div>
						<table class="table table-striped table-bordered table-hover table-margin-top" id="ux_tbl_wp_manual_clean_up">
							<thead>
								<tr>
									<th style="text-align:center;width:5%;">
									<input type="checkbox" id="ux_chk_select_all" name="ux_chk_select_all">
								</th>
								<th style="width: 65%;">
									<?php echo esc_attr( $cpo_type_of_data ); ?>
								</th>
								<th style="text-align:center; width: 15%;">
									<?php echo esc_attr( $cpo_count ); ?>
								</th>
								<th style="text-align:center; width: 15%;">
									<?php echo esc_attr( $cpo_action ); ?>
								</th>
								</tr>
							</thead>
							<tbody class="check-all">
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_auto_draft" name="ux_chk_auto_draft" value="1" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_auto_drafts ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo intval( count_clean_up_optimizer( 'autodraft' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_btn_auto_draft" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>" onclick="selected_empty_clean_up_optimizer(1);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_dashboard_transient_feed" value="2" name="ux_chk_dashboard_transient_feed" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_dashboard_transient_feed ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo intval( count_clean_up_optimizer( 'transient_feed' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_btn_dashboard_transient_feed" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>" onclick="selected_empty_clean_up_optimizer(2);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_unapproved_comments" value="3" name="ux_chk_unapproved_comments" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_unapproved_comments ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo intval( count_clean_up_optimizer( 'unapproved_comments' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_chk_unapproved_comments" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>" onclick="selected_empty_clean_up_optimizer(3);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_orphan_comments_meta" value="4" name="ux_chk_orphan_comments_meta" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_orphan_comment_meta ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo intval( count_clean_up_optimizer( 'comments_meta' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_btn_orphan_comments_meta" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>" onclick="selected_empty_clean_up_optimizer(4);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_orphan_posts_meta" value="5" name="ux_chk_orphan_posts_meta" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_orphan_post_meta ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo intval( count_clean_up_optimizer( 'posts_meta' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_btn_orphan_posts_meta" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>" onclick="selected_empty_clean_up_optimizer(5);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_orphan_relationships" value="6" name="ux_chk_orphan_relationships" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_orphan_relationships ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo intval( count_clean_up_optimizer( 'relationships' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_btn_orphan_relationships" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>" onclick="selected_empty_clean_up_optimizer(6);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_revision" value="7" name="ux_chk_revision" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_revisions ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo intval( count_clean_up_optimizer( 'revision' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_btn_revision" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>" onclick="selected_empty_clean_up_optimizer(7);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_remove_pingbacks" value="8" name="ux_chk_remove_pingbacks" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_remove_pingbacks ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo intval( count_clean_up_optimizer( 'remove_pingbacks' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_btn_remove_pingbacks" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>"onclick="selected_empty_clean_up_optimizer(8);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_remove_transient_options" value="9" name="ux_chk_remove_transient_options" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_remove_transient_options ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo intval( count_clean_up_optimizer( 'remove_transient_options' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_btn_remove_transient_options" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>" onclick="selected_empty_clean_up_optimizer(9);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_remove_trackbacks" value="10" name="ux_chk_remove_trackbacks" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_remove_trackbacks ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo intval( count_clean_up_optimizer( 'remove_trackbacks' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_btn_remove_trackbacks" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>" onclick="selected_empty_clean_up_optimizer(10);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_spam_comments" value="11" name="ux_chk_spam_comments" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_spam_comments ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo intval( count_clean_up_optimizer( 'spam' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_btn_spam_comments" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>" onclick="selected_empty_clean_up_optimizer(11);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_trash_comments" value="12" name="ux_chk_trash_comments" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_trash_comments ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo intval( count_clean_up_optimizer( 'trash' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_chk_trash_comments" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>" onclick="selected_empty_clean_up_optimizer(12);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_draft" value="13" name="ux_chk_draft" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_drafts ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo intval( count_clean_up_optimizer( 'draft' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_chk_draft" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>" onclick="selected_empty_clean_up_optimizer(13);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_deleted_posts" value="14" name="ux_chk_deleted_posts" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_deleted_posts ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo intval( count_clean_up_optimizer( 'deleted_posts' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_chk_deleted_posts" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>" onclick="selected_empty_clean_up_optimizer(14);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_duplicated_postmeta" value="15" name="ux_chk_duplicated_postmeta" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_duplicated_post_meta ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo esc_attr( count_clean_up_optimizer( 'duplicated_postmeta' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_chk_duplicated_postmeta" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>" onclick="selected_empty_clean_up_optimizer(15);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_oembed_caches_in_post_meta" value="16" name="ux_chk_oembed_caches_in_post_meta" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_oembed_caches_post_meta ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo esc_attr( count_clean_up_optimizer( 'oembed_caches' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_chk_oembed_caches_in_post_meta" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>" onclick="selected_empty_clean_up_optimizer(16);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_duplicated_comment_meta" value="17" name="ux_chk_duplicated_comment_meta" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_duplicated_comment_meta ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo intval( count_clean_up_optimizer( 'duplicated_commentmeta' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_chk_duplicated_comment_meta" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>" onclick="selected_empty_clean_up_optimizer(17);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_orphan_user_meta" value="18" name="ux_chk_orphan_user_meta" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_orphan_user_meta ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo intval( count_clean_up_optimizer( 'orphan_user_meta' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_chk_orphan_user_meta" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>" onclick="selected_empty_clean_up_optimizer(18);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_duplicated_usermeta" value="19" name="ux_chk_duplicated_usermeta" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_duplicated_user_meta ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo intval( count_clean_up_optimizer( 'duplicated_usermeta' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_chk_duplicated_usermeta" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>"onclick="selected_empty_clean_up_optimizer(19);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_orphaned_term_relationships" value="20" name="ux_chk_orphaned_term_relationships" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_orphaned_term_relationships ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo intval( count_clean_up_optimizer( 'orphaned_term_relationships' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_chk_orphaned_term_relationships" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>" onclick="selected_empty_clean_up_optimizer(20);">
								</td>
								</tr>
								<tr>
									<td class="custom-align-chk">
									<input type="checkbox" id="ux_chk_unused_terms" value="21" name="ux_chk_unused_terms" onclick="check_all_clean_up_optimizer('#ux_chk_select_all')">
								</td>
								<td style="width: 65%;">
									<label>
										<?php echo esc_attr( $cpo_unused_terms ); ?>
									</label>
								</td>
								<td class="custom-align">
									<label>
										<?php echo intval( count_clean_up_optimizer( 'unused_terms' ) ); ?>
									</label>
								</td>
								<td class="custom-align">
									<input type="button" id="ux_chk_unused_terms" class="btn vivid-green btn-align" value="<?php echo esc_attr( $cpo_empty ); ?>" onclick="selected_empty_clean_up_optimizer(21);">
								</td>
								</tr>
							</tbody>
						</table>
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
		<?php
	} else {
		?>
		<div class="page-bar">
			<ul class="page-breadcrumb">
			<li>
				<i class="icon-custom-home"></i>
				<a href="admin.php?page=cpo_dashboard">
					<?php echo esc_attr( $cpo_clean_up_optimizer ); ?>
				</a>
				<span>></span>
			</li>
			<li>
				<a href="admin.php?page=cpo_dashboard">
					<?php echo esc_attr( $cpo_dashboard ); ?>
				</a>
				<span>></span>
			</li>
			<li>
				<span>
					<?php echo esc_attr( $cpo_wp_optimizer ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-note"></i>
						<?php echo esc_attr( $cpo_wp_optimizer ); ?>
					</div>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_manual_clean_up">
						<div class="form-body">
						<strong><?php echo esc_attr( $cpo_roles_capabilities_message ); ?></strong>
					</div>
				</div>
			</div>
		</div>
	</div>
		<?php
	}
}
