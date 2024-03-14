<div id="yop-main-area" class="bootstrap-yop wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h1>
		<?php esc_html_e( 'Logs', 'yop-poll' ); ?>
	</h1>
	<form method="get" action="" id="searchForm">
		<input type="hidden" name="page" value="yop-poll-logs">
		<input type="hidden" name="sterm" value="<?php echo esc_attr( $search_term ); ?>">
		<input type="hidden" name="_token" value="<?php echo esc_attr( wp_create_nonce( 'yop-poll-export-logs' ) ); ?>">
		<button class="export-logs-button button" id="doaction" type="button" name="export"><?php echo esc_html__( 'Export', 'yop-poll' ); ?></button>
		<input type="hidden" name="doExport" id="doExport" value="">
	</form>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-1">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<form method="get">
						<input type="hidden" name="page" value="yop-poll-logs">
						<?php
						$logs->prepare_items();
						$logs->search_box(
							esc_html__( 'Search', 'yop-poll' ),
							'yop-poll'
						);
						?>
					</form>
					<?php
					$logs->display();
					?>
				</div>
			</div>
		</div>
		<br class="clear">
	</div>
</div>
