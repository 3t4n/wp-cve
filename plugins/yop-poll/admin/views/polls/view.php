<div id="yop-main-area" class="bootstrap-yop wrap">
	<div class="wrap yop-poll-polls-container">
		<h1>
			<?php
			esc_html_e( 'All Polls', 'yop-poll' );
			?>
			<a href="<?php echo esc_attr( $add_new_link ); ?>" class="page-title-action yop-poll-view-polls-add-new-button">
				<?php
				esc_html_e( 'Add New', 'yop-poll' );
				?>
			</a>
		</h1>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-1">
				<div id="post-body-content">
					<div class="meta-box-sortables ui-sortable">
						<form method="get">
							<input type="hidden" name="page" value="yop-polls">
							<?php
							$polls_list->prepare_items();
							$polls_list->search_box(
								esc_html__( 'Search', 'yop-poll' ),
								'yop-poll'
							);
							?>
						</form>
						<?php
						$polls_list->display();
						?>
					</div>
				</div>
			</div>
			<br class="clear">
		</div>
	</div>
</div>
<div class="bootstrap-yop">
    <div id="shortcode-popup" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">
                        <?php esc_html_e( 'Customize Poll Shortcode', 'yop-poll' ); ?>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">
                        <br/>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">
                                <?php esc_html_e( 'Tracking Id', 'yop-poll' ); ?>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control shortcode-tracking-id" placeholder="<?php esc_html_e( 'Leave empty if none', 'yop-poll' ); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">
                                <?php esc_html_e( 'Display Results Only', 'yop-poll' ); ?>
                            </label>
                            <div class="col-md-8">
                                <select class="shortcode-show-results admin-select" style="width:100%">
                                    <option value="yes"><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
                                    <option value="no" selected><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 text-center">
                                <button class="btn btn-primary generate-yop-poll-code" type="button" data-id="">
                                    <?php esc_html_e( 'Generate Code', 'yop-poll' ); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <br/><br/>
                    <div class="form-horizontal">
                        <div class="form-group">
							<label class="col-sm-4 control-label">
								<?php esc_html_e( 'Shortcode', 'yop-poll' ); ?>
							</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control poll-code" id="yop-poll-shortcode" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 text-center">
                                <button class="btn btn-primary" type="button" id="copy-yop-poll-code" data-clipboard-target="#yop-poll-shortcode"><?php esc_html_e( 'Copy to Clipboard', 'yop-poll' ); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <?php
    if ( 'yes' === $show_guide ) {
    ?>
    <div id="yop-poll-guide-modal" class="modal fade" role="dialog" style="margin-top: 10px;">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: 0px!important;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <?php
					$guide_page = rand( 1, 2 );
					//$guide_page = 2;
					if ( 1 === $guide_page ) {
						include YOP_POLL_PATH . 'admin/views/general/guide-1.php';
					} else {
						include YOP_POLL_PATH . 'admin/views/general/guide-2.php';
					}
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
</div>
