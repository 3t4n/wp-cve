<?php
/**
 * Single project: discussion
 *
 * @package UpStream
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php
if ( upstream_are_project_comments_enabled() && ! upstream_are_comments_disabled() ) :
	$plugin_options     = get_option( 'upstream_general' );
	$collapse_box       = isset( $plugin_options['collapse_project_discussion'] ) && true === (bool) $plugin_options['collapse_project_discussion'];
	$collapse_box_state = \UpStream\Frontend\upstream_get_section_collapse_state( 'discussion' );
	$project_id         = upstream_post_id();

	if ( false !== $collapse_box_state ) {
		$collapse_box = 'closed' === $collapse_box_state;
	}
	?>

	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="x_panel" data-section="discussion">
			<div class="x_title" id="discussion">
				<h2>
					<i class="fa fa-bars sortable_handler"></i>
					<i class="fa fa-comments"></i> <?php echo esc_html( upstream_discussion_label() ); ?>
				</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link">
							<i class="fa fa-chevron-<?php echo $collapse_box ? 'down' : 'up'; ?>"></i>
						</a>
					</li>
					<?php do_action( 'upstream_project_discussion_top_right', $project_id ); ?>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content" style="display: <?php echo $collapse_box ? 'none' : 'block'; ?>;">
				<?php
				$r = upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $project_id, null, null, 'comments', UPSTREAM_PERMISSIONS_ACTION_VIEW );
				if ( $r ) {
					?>
					<?php upstream_render_comments_box(); ?>
					<?php
				}
				?>
			</div>
		</div>
	</div>
<?php endif; ?>
