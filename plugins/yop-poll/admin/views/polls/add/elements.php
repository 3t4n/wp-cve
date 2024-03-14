<?php
include YOP_POLL_PATH . 'admin/views/polls/elements-definitions.php'
?>
<div class="row">
	<div class="col-md-12">
		&nbsp;
	</div>
</div>
<div class="row">
	<div class="col-md-12 buttons-row">
		<div class="row">
			<div class="col-md-4 col-sm-4">
				<span class="regular-buttons">
					<a class="btn btn-primary lite-button poll-element-add add-custom-field" href="#" role="button">
						<i class="glyphicon glyphicon-plus" aria-hidden="true"></i>
						<br>
						<?php esc_html_e( 'Custom Field', 'yop-poll' ); ?>
					</a>
				</span>
			</div>
			<div class="col-md-8 col-sm-8">
				<div class="row">
					<div class="col-md-1 col-sm-1">
						<span class="premium">
							<a href="#" class="upgrade-to-pro" data-screen="multiple-questions">
								<img src="<?php echo esc_url( YOP_POLL_URL ); ?>admin/assets/images/pro-vertical.svg" class="responsive" />
							</a>
						</span>
					</div>
					<div class="col-md-11 col-sm-11">
						<a class="btn btn-warning premium-button add-text-question" href="#" role="button">
							<i class="glyphicon glyphicon-plus" aria-hidden="true"></i>
							<br>
							<?php esc_html_e( 'Text Question', 'yop-poll' ); ?>
						</a>
						<a class="btn btn-warning premium-button add-media-question" href="#" role="button">
							<i class="glyphicon glyphicon-plus" aria-hidden="true"></i>
							<br>
							<?php esc_html_e( 'Images Question', 'yop-poll' ); ?>
						</a>
						<a class="btn btn-warning premium-button add-media-question" href="#" role="button">
							<i class="glyphicon glyphicon-plus" aria-hidden="true"></i>
							<br>
							<?php esc_html_e( 'Videos Question', 'yop-poll' ); ?>
						</a>
						<a class="btn btn-warning premium-button add-media-question" href="#" role="button">
							<i class="glyphicon glyphicon-plus" aria-hidden="true"></i>
							<br>
							<?php esc_html_e( 'Text Slider Question', 'yop-poll' ); ?>
						</a>
						<a class="btn btn-warning premium-button add-space-separator" href="#" role="button">
							<i class="glyphicon glyphicon-plus" aria-hidden="true"></i>
							<br>
							<?php esc_html_e( 'Space Separator', 'yop-poll' ); ?>
						</a>
						<a class="btn btn-warning premium-button add-text-block" href="#" role="button">
							<i class="glyphicon glyphicon-plus" aria-hidden="true"></i>
							<br>
							<?php esc_html_e( 'Text Block', 'yop-poll' ); ?>
						</a>
						<a class="btn btn-warning premium-button add-text-block" href="#" role="button">
							<i class="glyphicon glyphicon-plus" aria-hidden="true"></i>
							<br>
							<?php esc_html_e( 'Multi Line Text', 'yop-poll' ); ?>
						</a>
						<a class="btn btn-warning premium-button add-text-block" href="#" role="button">
							<i class="glyphicon glyphicon-plus" aria-hidden="true"></i>
							<br>
							<?php esc_html_e( 'Text Slider', 'yop-poll' ); ?>
						</a>
						<a class="btn btn-warning premium-button add-text-block" href="#" role="button">
							<i class="glyphicon glyphicon-plus" aria-hidden="true"></i>
							<br>
							<?php esc_html_e( 'Countdown Timer', 'yop-poll' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="poll-elements" data-remove="">
		<div class="poll-elements-list" style="min-height: 200px;" data-remove="">
			<div class="poll-element question" data-type="text-question" data-id="" data-remove="">
				<div class="title-bar">
					<span class="bar-title pull-left poll-element-collapse">
						<span class="glyphicon glyphicon-chevron-down hspace collapse-element" aria-hidden="true"></span>
					</span>
					<span class="bar-title pull-left poll-element-collapse element-title">
						<?php esc_html_e( 'Do you have a question?', 'yop-poll' ); ?>
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
												<input type="text" class="form-control answer-value" name="question text" value="Yes" placeholder="Question text">
											</div>
											<div class="col-sm-1">
												<label class="pull-right set-as-default-inline" title="Set as default">
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
												<input type="text" class="form-control answer-value" name="question text" value="Yes" placeholder="Question text">
											</div>
											<div class="col-sm-1">
												<label class="pull-right set-as-default-inline" title="Set as default">
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
										<a href="#" class="upgrade-to-pro" data-screen="sort-answers">
											<img src="<?php echo esc_url( YOP_POLL_URL ); ?>admin/assets/images/pro-horizontal.svg" class="responsive" />
										</a>
										<?php esc_html_e( 'Field Type', 'yop-poll' ); ?>
									</div>
									<div class="col-md-9">
										<select class="other-answers-type admin-select" style="min-width:100%">
											<option value="textfield" selected><?php esc_html_e( 'Textfield', 'yop-poll' ); ?></option>
											<option value="textarea"><?php esc_html_e( 'Textarea', 'yop-poll' ); ?></option>
										</select>
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
									<?php esc_html_e( 'Number of columns', 'yop-poll' ); ?>
								</div>
								<div class="col-md-9">
									<input type="text" name="button-label" class="form-control answers-columns"/>&nbsp;<?php esc_html_e( 'columns', 'yop-poll' ); ?>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3">
									<a href="#" class="upgrade-to-pro" data-screen="sort-answers">
										<img src="<?php echo esc_url( YOP_POLL_URL ); ?>admin/assets/images/pro-horizontal.svg" class="responsive" />
									</a>
									<?php esc_html_e( 'Sort Answers', 'yop-poll' ); ?>
								</div>
								<div class="col-md-9">
									<select class="answers-sort admin-select" style="width:100%">
										<option value="as-defined" selected>
											<?php esc_html_e( 'As Defined', 'yop-poll' ); ?>
										</option>
										<option value="alphabetically-asc">
											<?php esc_html_e( 'Alphabetically Ascending', 'yop-poll' ); ?>
										</option>
										<option value="alphabetically-desc">
											<?php esc_html_e( 'Alphabetically Descending', 'yop-poll' ); ?>
										</option>
										<option value="random">
											<?php esc_html_e( 'Randomly', 'yop-poll' ); ?>
										</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		&nbsp;
  </div>
</div>
