<div class="yop-elements">
	<div class="custom-field-definition hide">
		<div class="poll-element question" data-type="custom-field" data-id="" data-remove="">
			<div class="title-bar">
				<span class="bar-title pull-left poll-element-collapse">
					<span class="glyphicon glyphicon-chevron-down hspace collapse-element" aria-hidden="true"></span>
				</span>
				<span class="bar-title pull-left poll-element-collapse element-title">
					<?php esc_html_e( 'Custom Field', 'yop-poll' ); ?>
				</span>
				<span class="pull-right actions">
					<a href="#" class="hspace custom-field-edit-more" title="<?php esc_html_e( 'Edit', 'yop-poll' ); ?>">
						<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
					</a>
					<a href="#" class="hspace custom-field-edit-clone" title="<?php esc_html_e( 'Duplicate', 'yop-poll' ); ?>">
						<span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
					</a>
					<a href="#" class="hspace custom-field-edit-delete" title="<?php esc_html_e( 'Delete', 'yop-poll' ); ?>">
						<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
					</a>
				</span>
			</div>
			<div class="content-inside">
				<div class="question-text">
					<div class="form-horizontal">
						<div class="form-group">
							<div class="col-sm-11">
								<input type="text" class="form-control input-lg custom-field-name" name="question text" value="<?php esc_html_e( 'Custom Field', 'yop-poll' ); ?>" placeholder="<?php esc_html_e( 'Custom Field', 'yop-poll' ); ?>">
							</div>
							<div class="col-sm-1">
								<label class="pull-right set-as-default-inline" title="<?php esc_html_e( 'Set as required', 'yop-poll' ); ?>">
									<input type="checkbox" class="custom-field-required">
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="custom-field-options">
					<div class="form-group">
						<div class="col-md-2">
							<a href="#" class="upgrade-to-pro" data-screen="pie-results">
								<img src="<?php echo esc_url( YOP_POLL_URL ); ?>admin/assets/images/pro-horizontal.svg" class="responsive" />
							</a>
							<?php esc_html_e( 'Type', 'yop-poll' ); ?>
						</div>
						<div class="col-md-10">
							<select class="custom-field-type admin-select" style="width: 100%">
								<option value="textfield" selected>
									<?php esc_html_e( 'Textfield', 'yop-poll' ); ?>
								</option>
								<option value="textarea">
									<?php esc_html_e( 'Textarea', 'yop-poll' ); ?>
								</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-4">
								<div class="checkbox">
									<label>
									  <input type="checkbox" class="custom-field-make-required"> <?php esc_html_e( 'Set as Required', 'yop-poll' ); ?>
									</label>
								  </div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="button" class="btn btn-default custom-field-edit-done">
									<?php esc_html_e( 'Done', 'yop-poll' ); ?>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="text-question-definition hide">
		<div class="poll-element question" data-type="text-question" data-id="" data-remove="">
			<div class="title-bar">
				<span class="bar-title pull-left poll-element-collapse">
					<span class="glyphicon glyphicon-chevron-down hspace collapse-element" aria-hidden="true"></span>
				</span>
				<span class="bar-title pull-left poll-element-collapse element-title">
					<?php esc_html_e( 'Do you have a question', 'yop-poll' ); ?>
				</span>
				<span class="pull-right actions">
					<a href="#" class="hspace add-text-answer" title="<?php esc_html_e( 'Add Answer', 'yop-poll' ); ?>">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					</a>
					<a href="#" class="hspace text-question-edit-clone" title="<?php esc_html_e( 'Duplicate', 'yop-poll' ); ?>">
						<span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
					</a>
					<a href="#" class="hspace text-question-edit-delete" title="<?php esc_html_e( 'Delete', 'yop-poll' ); ?>">
						<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
					</a>
				</span>
			</div>
			<div class="content-inside">
				<div class="question-text">
					<div class="form-group">
						<input type="text" class="form-control input-lg question-value" name="question text" value="<?php esc_html_e( 'Do you have a question?', 'yop-poll' ); ?>" placeholder="<?php esc_html_e( 'Question text', 'yop-poll' ); ?>">
					</div>
				</div>
				<div class="answers">
				</div>
				<div class="question-options">
					<h4>
						<?php esc_html_e( 'OPTIONS', 'yop-poll' ); ?>
					</h4>
					<div class="form-horizontal">
						<div class="form-group">
							<div class="col-md-3">
								<?php esc_html_e( 'Allow other answers', 'yop-poll' ); ?>
							</div>
							<div class="col-md-9">
								<select class="allow-other-answers admin-select" style="width:100%">
									<option value="yes"><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
									<option value="no" selected><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
								</select>
							</div>
						</div>
						<div class="other-answers-section hide">
							<div class="form-group">
								<div class="col-md-3">
									<?php esc_html_e( 'Label for Other Answers', 'yop-poll' ); ?>
								</div>
								<div class="col-md-9">
									<input type="text" name="" value="<?php esc_html_e( 'Other', 'yop-poll' ); ?>" class="form-control other-answers-label" />
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3">
									<?php esc_html_e( 'Add other answers in answers list', 'yop-poll' ); ?>
								</div>
								<div class="col-md-9">
									<select class="add-other-answers admin-select" style="width:100%">
										<option value="yes"><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
										<option value="no" selected><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3">
									<?php esc_html_e( 'Display other answers in results list', 'yop-poll' ); ?>
								</div>
								<div class="col-md-9">
									<select class="display-other-answers-in-results admin-select" style="width:100%">
										<option value="yes"><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
										<option value="no" selected><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
									</select>
								</div>
							</div>
							<div class="other-answers-results-color-section hide">
								<div class="form-group">
									<div class="col-md-3">
										<?php esc_html_e( 'Results Color', 'yop-poll' ); ?>
									</div>
									<div class="col-md-9">
										<input type="text" value="#000" class="form-control other-answers-results-color" />
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-3">
								<?php esc_html_e( 'Allow multiple answers', 'yop-poll' ); ?>
							</div>
							<div class="col-md-9">
								<select class="allow-multiple-answers admin-select" style="width:100%">
									<option value="yes"><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
									<option value="no" selected><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
								</select>
							</div>
						</div>
						<div class="multiple-answers-section hide">
							<div class="form-group">
								<div class="col-md-3">
									<?php esc_html_e( 'Minimum answers required', 'yop-poll' ); ?>
								</div>
								<div class="col-md-9">
									<input type="text" name="" value="1" class="form-control multiple-answers-minim"/>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3">
									<?php esc_html_e( 'Maximum answers allowed', 'yop-poll' ); ?>
								</div>
								<div class="col-md-9">
									<input type="text" name="" value="1" class="form-control multiple-answers-maxim"/>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-3">
								<?php esc_html_e( 'Display answers', 'yop-poll' ); ?>
							</div>
							<div class="col-md-9">
								<select class="answers-display admin-select" style="width:100%">
									<option value="vertical" selected>
										<?php esc_html_e( 'Vertical', 'yop-poll' ); ?>
									</option>
									<option value="horizontal">
										<?php esc_html_e( 'Horizontal', 'yop-poll' ); ?>
									</option>
									<option value="columns">
										<?php esc_html_e( 'Columns', 'yop-poll' ); ?>
									</option>
								</select>
							</div>
						</div>
						<div class="form-group answers-display-section hide">
							<div class="col-md-3">
								<?php esc_html_e( 'Display answers', 'yop-poll' ); ?>
							</div>
							<div class="col-md-9">
								<input type="text" name="button-label" class="form-control answers-columns"/>&nbsp;<?php esc_html_e( 'columns', 'yop-poll' ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="text-answer-definition hide">
		<div class="answer" data-id="">
			<div class="title-bar">
				<span class="bar-title pull-left">
					<?php esc_html_e( 'Answer', 'yop-poll' ); ?>
				</span>
				<span class="pull-right actions">
					<a href="#" class="hspace text-answer-edit-more" title="<?php esc_html_e( 'Edit', 'yop-poll' ); ?>">
						<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
					</a>
					<a href="#" class="hspace text-answer-edit-clone" title="<?php esc_html_e( 'Duplicate', 'yop-poll' ); ?>">
						<span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
					</a>
					<a href="#" class="hspace text-answer-edit-delete" title="<?php esc_html_e( 'Delete', 'yop-poll' ); ?>">
						<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
					</a>
				</span>
			</div>
			<div class="content-inside">
				<div class="answer-text">
					<div class="form-horizontal">
						<div class="form-group">
							<div class="col-sm-11">
								<input type="text" class="form-control answer-value" name="question text" value="<?php esc_html_e( 'New Answer', 'yop-poll' ); ?>" placeholder="<?php esc_html_e( 'Answer text', 'yop-poll' ); ?>">
							</div>
							<div class="col-sm-1">
								<label class="pull-right set-as-default-inline" title="<?php esc_html_e( 'Set as default', 'yop-poll' ); ?>">
									<input type="checkbox" class="answer-is-default">
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="answer-options">
					<div class="form-group">
						<div class="row">
							<div class="col-md-4">
								<div class="checkbox">
									<label>
									  <input type="checkbox" class="answer-make-default"> <?php esc_html_e( 'Set as default', 'yop-poll' ); ?>
									</label>
								  </div>
							</div>
							<div class="col-md-4">
								<div class="checkbox">
									<label>
									  <input type="checkbox" class="answer-make-link"> <?php esc_html_e( 'Make it a link', 'yop-poll' ); ?>
									</label>
								  </div>
							</div>
							<div class="col-md-4">
								<div class="checkbox">
									<label>
										<?php esc_html_e( 'Results color', 'yop-poll' ); ?>
									</label>
									<input type="text" value="#000" class="form-control answer-results-color" />
								</div>
							</div>
						</div>
						<div class="row answer-link-section hide">
							<div class="col-md-12">
								<input type="text" class="form-control border answer-link" name="" placeholder="http://">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="button" class="btn btn-default text-answer-edit-done">
									<?php esc_html_e( 'Done', 'yop-poll' ); ?>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
