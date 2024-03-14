<div id="yop-main-area" class="bootstrap-yop wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h1>
        <span class="glyphicon glyphicon-signal" style="margin-right:10px;"></span><?php esc_html_e( 'Poll results for', 'yop-poll' ); ?> <?php echo esc_html( $poll->name ); ?>
        <a href="
			<?php
			echo esc_url(
				add_query_arg(
					array(
						'page' => 'yop-polls',
						'action' => false,
						'poll_id' => false,
						'_token' => false,
						'order_by' => false,
						'sort_order' => false,
						'q' => false,
						'exportCustoms' => false,
					)
				)
			);
			?>
			" class="page-title-action">
            <?php esc_html_e( 'All Polls', 'yop-poll' ); ?>
        </a>
    </h1>
    <div class="container-fluid">
        <div class="row submenu" style="margin-top:30px; margin-bottom: 50px;">
            <div class="col-md-4">
                <a href="
					<?php
					echo esc_url(
						add_query_arg(
							array(
								'page' => 'yop-polls',
								'action' => 'results',
								'poll_id' => $poll->id,
								'_token' => false,
								'order_by' => false,
								'sort_order' => false,
								'q' => false,
								'exportCustoms' => false,
							)
						)
					);
					?>
					" class="btn btn-link btn-block">
                    <?php esc_html_e( 'Results', 'yop-poll' ); ?>
                </a>
            </div>
            <div class="col-md-4">
                <a class="btn btn-link btn-block btn-underline">
                    <?php esc_html_e( 'View votes', 'yop-poll' ); ?>
                </a>
            </div>
            <div class="col-md-4"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form method="get" action="" id="searchForm">
					<input type="hidden" name="page" value="yop-polls">
					<input type="hidden" name="action" value="view-votes">
					<input type="hidden" name="poll_id" value="<?php echo esc_attr( $poll->id ); ?>">
					<input type="hidden" id="votes-search-input" name="q" value="<?php echo esc_attr( $search_term ); ?>">
                    <button class="export-logs-button button" id="doaction" type="button" name="export">
						<?php esc_html_e( 'Export', 'yop-poll' ); ?>
					</button>
                    <input type="hidden" name="doExport" id="doExport" value="">
                    <button class="add-votes-button button" type="button" name="add-votes">
						<?php esc_html_e( 'Add Votes', 'yop-poll' ); ?>
					</button>
					<div id="modal-add-votes-manually" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">
									<?php esc_html_e( 'Add Votes Manually', 'yop-poll' ); ?>
								</h4>
							</div>
							<div class="modal-body section-main-add-votes-manually">
								<?php
								foreach ( $poll->elements as $poll_element ) {
									switch ( $poll_element->etype ) {
										case 'text-question': {
											?>
											<div class="question-section" data-id="<?php echo esc_attr( $poll_element->id ); ?>">
												<h4 class="text-center">
													<?php echo esc_html( $poll_element->etext ); ?>
												</h4>
												<?php
												foreach ( $poll_element->answers as $answer ) {
													?>
													<div class="row answer-section">
														<div class="col-md-12">
															<h5>
																<?php
																	echo esc_html( $answer->stext );
																?>
															</h5>
														</div>
													</div>
													<div class="row">
														<div class="col-md-4">
															<div class="input-group">
																<input type="text" class="form-control answer-element" value="0" data-id="<?php echo esc_attr( $answer->id ); ?>">
																<span class="help-block">
																	<?php esc_html_e( 'Number of votes for this answer', 'yop-poll' ); ?>
																</span>
															</div>
														</div>
														<div class="col-md-8">&nbsp;</div>
													</div>
													<?php
												}
											?>
											</div>
											<?php
											break;
										}
									}
								}
								?>
							</div>
							<div class="modal-footer section-footer-add-votes-manually">
								<input type="hidden" name="_token-add-votes-manually" value="<?php echo esc_attr( wp_create_nonce( 'yop-poll-add-votes-manually' ) ); ?>">
								<button type="button" class="btn btn-default btn-cancel-add-votes-manually">
									<?php esc_html_e( 'Cancel', 'yop-poll' ); ?>
								</button>
								<button type="button" class="btn btn-primary btn-submit-add-votes-manually" data-poll-id="<?php echo esc_attr( $poll->id ); ?>">
									<?php esc_html_e( 'Add Votes', 'yop-poll' ); ?>
								</button>
								<span class="spinner hide"></span>
							</div>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
                </form>
				<div class="meta-box-sortables ui-sortable">
					<form method="get">
						<input type="hidden" name="page" value="yop-polls">
						<input type="hidden" name="action" value="view-votes">
						<input type="hidden" name="poll_id" value="<?php echo esc_attr( $poll->id ); ?>">
						<?php
						$votes_list->prepare_items();
						$votes_list->search_box(
							esc_html__( 'Search', 'yop-poll' ),
							'yop-poll'
						);
						?>
					</form>
					<?php
					$votes_list->display();
					?>
				</div>
            </div>
        </div>
    </div>
</div>
