<div id="yop-main-area" class="bootstrap-yop wrap add-edit-poll" data-reCaptcha-enabled="<?php echo esc_attr( $integrations['reCaptcha']['enabled'] ); ?>" data-reCaptcha-site-key="<?php echo esc_attr( $integrations['reCaptcha']['site-key'] ); ?>">
    <h1>
        <?php esc_html_e( 'Create Poll', 'yop-poll' ); ?>
    </h1>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content" style="position:relative">
				<form id="yop-poll-form" action="#">
	                <input type="hidden" id="_token" value="<?php echo esc_attr( wp_create_nonce( 'yop-poll-add-poll' ) ); ?>" />
	                <div class="meta-box-sortables ui-sortable">
	                    <div id="titlediv">
	                        <div id="titlewrap">
	                            <input name="poll[name]" size="30" value="" id="title"
	                                spellcheck="true" autocomplete="off" type="text"
	                                class="form-control"
	                                placeholder="<?php esc_html_e( 'Name goes here', 'yop-poll' ); ?>" />
	                        </div>
	                        <div class="inside"></div>
	                    </div>
                        <div class="container-fluid yop-poll-hook">
						<div class="tabs-container">
							<!-- Nav tabs -->
							<ul class="main nav nav-tabs poll-steps" role="tablist">
								<li role="presentation" class="step-design active">
									<a href="#poll-design" aria-controls="design" role="tab" data-toggle="tab">
										<?php esc_html_e( 'Design', 'yop-poll' ); ?>
									</a>
								</li>
								<li role="presentation" class="step-elements">
									<a href="#poll-questions" aria-controls="questions" role="tab" data-toggle="tab">
										<?php esc_html_e( 'Question & Answers', 'yop-poll' ); ?>
									</a>
								</li>
								<li role="presentation" class="step-options">
									<a href="#poll-options" aria-controls="options" role="tab" data-toggle="tab">
										<?php esc_html_e( 'Options', 'yop-poll' ); ?>
									</a>
								</li>
							</ul>
							<div class="tab-content poll-steps-content">
								<div role="tabpanel" class="tab-pane active" id="poll-design">
									<br><br>
							    	<div class="row submenu">
										<div class="col-md-4">
											<a class="btn btn-link btn-block submenu-item submenu-item-active btn-underline" data-content="content-design-templates">
												<?php esc_html_e( 'Choose a template', 'yop-poll' ); ?>
											</a>
										</div>
										<div class="col-md-4">
											<a class="btn btn-link btn-block submenu-item" data-content="content-design-predefined-styles">
												<?php esc_html_e( 'Predefined Styles', 'yop-poll' ); ?>
											</a>
										</div>
										<div class="col-md-4">
                                            <a class="btn btn-link btn-block submenu-item" data-content="content-design-custom-style">
												<?php esc_html_e( 'Custom Style', 'yop-poll' ); ?>
											</a></div>
									</div>
									<div class="row submenu-content content-design-templates">
										<div class="col-md-12">
                                            <div>&nbsp;</div>
											<?php include YOP_POLL_PATH . 'admin/views/polls/add/design-templates.php'; ?>
										</div>
									</div>
                                    <div class="row submenu-content content-design-predefined-styles hide">
										<div class="col-md-12">
											<?php include YOP_POLL_PATH . 'admin/views/polls/add/design-predefined-styles.php'; ?>
										</div>
									</div>
                                    <div class="row submenu-content content-design-custom-style hide">
										<div class="col-md-12">
											<?php include YOP_POLL_PATH . 'admin/views/polls/add/design-custom-styles.php'; ?>
										</div>
									</div>
								</div>
								<div role="tabpanel" class="tab-pane" id="poll-questions">
                                    <br><br>
                                    <div class="row submenu">
                                        <div class="col-md-4">
                                            <a class="btn btn-link btn-block btn-underline submenu-item-active submenu-item" data-content="content-qa-elements">
												<?php esc_html_e( 'Poll Elements', 'yop-poll' ); ?>
											</a>
                                        </div>
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4"></div>
                                    </div>
									<div class="row submenu-content content-qa-elements">
                                        <div class="col-md-12">
                                            <?php include( YOP_POLL_PATH . 'admin/views/polls/add/elements.php' ); ?>
                                        </div>
                                    </div>
								</div>
								<div role="tabpanel" class="tab-pane" id="poll-options">
                                    <br><br>
                                    <div class="row submenu">
                                        <div class="col-md-4">
                                            <a class="btn btn-link btn-block submenu-item submenu-item-active btn-underline" data-content="content-options-poll">
												<?php esc_html_e( 'Poll', 'yop-poll' ); ?>
											</a>
                                        </div>
                                        <div class="col-md-4">
                                            <a class="btn btn-link btn-block submenu-item" data-content="content-options-access">
												<?php esc_html_e( 'Access', 'yop-poll' ); ?>
											</a>
                                        </div>
                                        <div class="col-md-4">
                                            <a class="btn btn-link btn-block submenu-item" data-content="content-options-results">
												<?php esc_html_e( 'Results', 'yop-poll' ); ?>
											</a>
                                        </div>
                                    </div>
                                    <div class="row submenu-content content-options-poll">
                                        <div class="col-md-12">
                                            <?php include( YOP_POLL_PATH . 'admin/views/polls/add/options-poll.php' ); ?>
                                        </div>
                                    </div>
                                    <div class="row submenu-content content-options-access hide">
                                        <div class="col-md-12">
                                            <?php include( YOP_POLL_PATH . 'admin/views/polls/add/options-access.php' ); ?>
                                        </div>
                                    </div>
                                    <div class="row submenu-content content-options-results hide">
                                        <div class="col-md-12">
                                            <?php include( YOP_POLL_PATH . 'admin/views/polls/add/options-results.php' ); ?>
                                        </div>
                                    </div>
								</div>
							</div>
						</div>
                        </div> <!-- /.container -->
					</div>
				</form>
            </div>
			<div id="postbox-container-1" class="postbox-container">
                <div id="side-sortables" class="meta-box-sortables ui-sortable">
                    <div class="postbox" id="submitdiv">
                        <button type="button" class="handlediv button-link" aria-expanded="true">
                            <span class="screen-reader-text">
                                <?php esc_html_e( 'Toggle panel: Publish', 'yop-poll' ); ?>
                            </span>
                            <span class="toggle-indicator" aria-hidden="true"></span>
                        </button>
                        <h2 class="hndle ui-sortable-handle">
                            <span>
                                <?php esc_html_e( 'Publish', 'yop-poll' ); ?>
                            </span>
                        </h2>
                        <div class="inside">
                            <div id="submitpoll" class="submitbox">
                                <div id="minor-publishing">
                                    <div id="minor-publishing-actions">
                                        <div id="peview-action">
                                            <a class="button preview-poll" id="poll-preview">
                                                <?php esc_html_e( 'Preview', 'yop-poll' ); ?>
                                            </a>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div id="misc-publishing-actions">
                                        <div class="misc-pub-section misc-pub-post-status">
                                            <label for="post_status">
                                                <?php esc_html_e( 'Status:', 'yop-poll' ); ?>
                                            </label>
                                            <span id="post-status-display" class="poll-status">
                                                Published
                                            </span>
                                            <a href="#" class="edit-poll-status hide-if-no-js">
                                                <span aria-hidden="true">
                                                    <?php esc_html_e( 'Edit', 'yop-poll' ); ?>
                                                </span>
                                                <span class="screen-reader-text">
                                                    <?php esc_html_e( 'Edit status', 'yop-poll' ); ?>
                                                </span>
                                            </a>
                                            <div id="poll-status-select" class="hide-if-js">
                                                <select name="poll_status" id="poll_status">
                                                    <option value="published">
                                                        <?php esc_html_e( 'Published', 'yop-poll' ); ?>
                                                    </option>
                                                    <option value="draft">
                                                        <?php esc_html_e( 'Draft', 'yop-poll' ); ?>
                                                    </option>
                                                </select>
                                                <a href="#" class="save-poll-status hide-if-no-js button">
                                                    <?php esc_html_e( 'OK', 'yop-poll' ); ?>
                                                </a>
                                                <a href="#" class="cancel-poll-status hide-if-no-js button-cancel">
                                                    <?php esc_html_e( 'Cancel', 'yop-poll' ); ?>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="misc-pub-section curtime misc-pub-curtime">
                                            <span id="timestamp">
                                                <?php esc_html_e( 'Publish immediately', 'yop-poll' ); ?>
                                            </span>
                                            <a href="#" class="edit-timestamp hide-if-no-js">
                                                <span aria-hidden="true">
                                                    <?php esc_html_e( 'Edit', 'yop-poll' ); ?>
                                                </span>
                                                <span class="screen-reader-text">
                                                    <?php esc_html_e( 'Edit status', 'yop-poll' ); ?>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                    <div id="major-publishing-actions">
                                        <div id="publishing-action">
                                            <span class="spinner publish"></span>
                                        	<input name="original_publish" id="original_publish" value="<?php esc_html_e( 'Publish', 'yop-poll' ); ?>" type="hidden">
                                        	<input name="publish"
                                                id="publish-poll"
                                                class="button button-primary button-large"
                                                value="<?php esc_html_e( 'Publish', 'yop-poll' ); ?>"
                                                type="submit">
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>
    <?php
    $upgrade_page = rand( 1, 2 );
    if ( 1 === $upgrade_page ) {
        include YOP_POLL_PATH . 'admin/views/general/upgrade-short-1.php';
    } else {
        include YOP_POLL_PATH . 'admin/views/general/upgrade-short-2.php';
    }
    ?>
</div>
<!-- begin live preview -->
<div class="bootstrap-yop">
    <div id="yop-poll-preview" class="hide">
    </div>
</div>
<!-- end live preview -->
