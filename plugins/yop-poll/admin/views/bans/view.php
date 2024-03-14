<div id="yop-main-area" class="bootstrap-yop wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h1>
		<?php esc_html_e( 'Bans', 'yop-poll' ); ?>
		<a href="
		<?php
		echo esc_url(
			add_query_arg(
				array(
					'page' => 'yop-poll-bans',
					'action' => 'add',
					'ban_id' => false,
					'_token' => false,
					'order_by' => false,
					'sort_order' => false,
					'q' => false,
				)
			)
		);
		?>
		" class="page-title-action">
			<?php esc_html_e( 'Add New', 'yop-poll' ); ?>
		</a>
	</h1>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-1">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<form method="get">
						<input type="hidden" name="page" value="yop-poll-bans">
						<?php
						$bans->prepare_items();
						$bans->search_box(
							esc_html__( 'Search', 'yop-poll' ),
							'yop-poll'
						);
						?>
					</form>
					<?php
					$bans->display();
					?>
				</div>
			</div>
		</div>
		<br class="clear">
	</div>
</div>
