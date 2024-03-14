<?php
/**
 * Single project: details
 *
 * @package UpStream
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$project_id = (int) upstream_post_id();
$project    = get_up_stream_project_details_by_id( $project_id );

$project_timeframe               = '';
$project_date_start_is_not_empty = $project->date_start > 0;
$project_date_end_is_not_empty   = $project->date_end > 0;
if ( $project_date_start_is_not_empty || $project_date_end_is_not_empty ) {
	if ( $project->date_start_ymd ) {
		$project->date_start = esc_html( \UpStream_Model_Object::ymdToTimestamp( $project->date_start_ymd ) );
	}
	if ( $project->date_end_ymd ) {
		$project->date_end = esc_html( \UpStream_Model_Object::ymdToTimestamp( $project->date_end_ymd ) );
	}

	if ( ! $project_date_end_is_not_empty ) {
		$project_timeframe = '<i class="text-muted">' . __(
			'Start Date',
			'upstream'
		) . ': </i>' . esc_html( upstream_format_date( $project->date_start ) );
	} elseif ( ! $project_date_start_is_not_empty ) {
		$project_timeframe = '<i class="text-muted">' . __(
			'End Date',
			'upstream'
		) . ': </i>' . esc_html( upstream_format_date( $project->date_end ) );
	} else {
		$project_timeframe = esc_html( upstream_format_date( $project->date_start ) . ' - ' . upstream_format_date( $project->date_end ) );

	}
}

$plugin_options         = get_option( 'upstream_general' );
$collapse_details       = isset( $plugin_options['collapse_project_details'] ) && true === (bool) $plugin_options['collapse_project_details'];
$collapse_details_state = \UpStream\Frontend\upstream_get_section_collapse_state( 'details' );

if ( ! is_null( $collapse_details_state ) ) {
	$collapse_details = 'closed' === $collapse_details_state;
}

$is_clients_disabled = upstream_is_clients_disabled();
?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="x_panel details-panel" data-section="details">
		<div class="x_title">
			<h2>
				<i class="fa fa-bars sortable_handler"></i>
				<?php
				printf(
					'<i class="fa fa-info-circle"></i> ' .
					// translators: %s: Title.
					esc_html__( '%s Details', 'upstream' ),
					esc_html( upstream_project_label() )
				);
				?>
				<?php do_action( 'upstream:frontend.project.details.after_title', $project ); ?>
			</h2>
			<ul class="nav navbar-right panel_toolbox">
				<li>
					<a class="collapse-link">
						<i class="fa fa-chevron-<?php echo $collapse_details ? 'down' : 'up'; ?>"></i>
					</a>
				</li>
			</ul>
			<div class="clearfix"></div>
		</div>
		<div class="x_content" style="display: <?php echo $collapse_details ? 'none' : 'block'; ?>;">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 upstream-pd-id">
					<p class="title"><?php esc_html_e( 'ID', 'upstream' ); ?></p>
					<span><?php echo esc_html( $project_id ); ?></span>
				</div>

				<?php if ( ! empty( $project_timeframe ) ) : ?>
					<div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 upstream-pd-timeframe">
						<p class="title"><?php esc_html_e( 'Timeframe', 'upstream' ); ?></p>
						<?php
						if ( upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $project_id, null, 0, 'start', UPSTREAM_PERMISSIONS_ACTION_VIEW ) &&
							upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $project_id, null, 0, 'end', UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) :
							?>
						<span><?php echo wp_kses_post( $project_timeframe ); /* already sanitized */ ?></span>
						<?php else : ?>
							<span class="label up-o-label" style="background-color:#666;color:#fff">(hidden)</span>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! $is_clients_disabled && $project->client_id > 0 ) : ?>
					<div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 upstream-pd-client">
						<p class="title"><?php echo esc_html( upstream_client_label() ); ?></p>
						<span>
						<?php
						echo $project->client_id > 0 && ! empty( $project->client_name ) ? esc_html( $project->client_name ) : '<i class="text-muted">(' . esc_html__(
							'none',
							'upstream'
						) . ')</i>';
						?>
								</span>
					</div>
				<?php endif; ?>

				<div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 upstream-pd-progress">
					<p class="title"><?php esc_html_e( 'Progress', 'upstream' ); ?></p>
					<span>
					<?php if ( upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $project_id, null, 0, 'progress', UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) : ?>
						<?php echo esc_html( $project->progress ); ?>% <?php esc_html_e( 'complete', 'upstream' ); ?>
					<?php else : ?>
						<span class="label up-o-label" style="background-color:#666;color:#fff">(hidden)</span>
					<?php endif; ?>
					</span>
				</div>
				<?php if ( $project->owner_id > 0 ) : ?>
					<div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 upstream-pd-owner">
						<p class="title"><?php esc_html_e( 'Owner', 'upstream' ); ?></p>
						<?php if ( upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $project_id, null, 0, 'progress', UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) : ?>
							<span>
							<?php
							echo $project->owner_id > 0 ? wp_kses_post( upstream_user_avatar( $project->owner_id ) ) : '<i class="text-muted">(' . esc_html__(
								'none',
								'upstream'
							) . ')</i>';
							?>
									</span>
						<?php else : ?>
							<span class="label up-o-label" style="background-color:#666;color:#fff">(hidden)</span>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! $is_clients_disabled && $project->client_id > 0 ) : ?>
					<div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 upstream-pd-clientusers">
						<p class="title">
						<?php
						printf(
							// translators: %s: Users counter.
							esc_html__( '%s Users', 'upstream' ),
							esc_html( upstream_client_label() )
						);
						?>
						</p>
						<?php if ( is_array( $project->client_users ) && count( $project->client_users ) > 0 ) : ?>
							<?php upstream_output_client_users(); ?>
						<?php else : ?>
							<span><i class="text-muted">(<?php esc_html_e( 'none', 'upstream' ); ?>)</i></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 upstream-pd-members">
					<p class="title"><?php esc_html_e( 'Members', 'upstream' ); ?></p>
					<?php upstream_output_project_members(); ?>
				</div>

				<?php do_action( 'upstream:frontend.project.render_details', $project->id ); ?>
			</div>
			<?php if ( ! empty( $project->description ) ) : ?>
				<div class="upstream-pd-description">
					<p class="title"><?php esc_html_e( 'Description', 'upstream' ); ?></p>
					<?php if ( upstream_override_access_field( true, UPSTREAM_ITEM_TYPE_PROJECT, $project_id, null, 0, 'description', UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) : ?>
						<blockquote
								style="font-size: 1em;"><?php echo wp_kses_post( upstream_esc_w( nl2br( htmlspecialchars_decode( $project->description ) ) ) ); ?></blockquote>
					<?php else : ?>
						<span class="label up-o-label" style="background-color:#666;color:#fff">(hidden)</span>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
