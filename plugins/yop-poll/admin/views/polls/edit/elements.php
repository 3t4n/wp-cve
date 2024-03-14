<?php
include YOP_POLL_PATH . 'admin/views/polls/elements-definitions.php';
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
			<?php
			foreach ( $poll->elements as $element ) {
				switch ( $element->etype ) {
					case 'text-question': {
						?>
						<div class="poll-element question" data-type="text-question" data-id="<?php echo esc_attr( $element->id ); ?>" data-remove="">
							<div class="title-bar">
								<span class="bar-title pull-left poll-element-collapse">
									<span class="glyphicon glyphicon-chevron-down hspace collapse-element" aria-hidden="true"></span>
								</span>
								<span class="bar-title pull-left poll-element-collapse element-title">
									<?php echo esc_html( substr( $element->etext, 0, 50 ) ); ?>
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
										<input type="text" class="form-control input-lg question-value" name="question text" value="<?php echo esc_attr( $element->etext ); ?>" placeholder="<?php esc_html_e( 'Question text', 'yop-poll' ); ?>">
									</div>
								</div>
								<div class="answers">
									<?php
									foreach ( $element->answers as $answer ) {
										?>
										<div class="answer" data-id="<?php echo esc_attr( $answer->id ); ?>">
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
																<input type="text" class="form-control answer-value" name="question text" value="<?php echo esc_attr( $answer->stext ); ?>" placeholder="<?php esc_html_e( 'Answer text', 'yop-poll' ); ?>">
															</div>
															<div class="col-sm-1">
																<label class="pull-right set-as-default-inline" title="<?php esc_html_e( 'Set as default', 'yop-poll' ); ?>">
																	<?php
	                                                                if ( 'yes' === $answer->meta_data['makeDefault'] ) {
	                                                                    $answer_make_default = 'checked';
	                                                                } else {
	                                                                    $answer_make_default = '';
	                                                                }
	                                                                ?>
																	<input type="checkbox" class="answer-is-default" <?php echo esc_attr( $answer_make_default ); ?>>
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
																	  <input type="checkbox" class="answer-make-default" <?php echo esc_attr( $answer_make_default ); ?>> <?php esc_html_e( 'Set as default', 'yop-poll' ); ?>
																	</label>
																  </div>
															</div>
															<div class="col-md-4">
																<div class="checkbox">
																	<label>
																		<?php
                                                                        if ( 'yes' === $answer->meta_data['makeLink'] ) {
                                                                            $answer_make_link = 'checked';
																			$answer_make_link_class = '';
                                                                        } else {
                                                                            $answer_make_link = '';
																			$answer_make_link_class = 'hide';
                                                                        }
                                                                        ?>
																		<input type="checkbox" class="answer-make-link" <?php echo esc_attr( $answer_make_link ); ?>> <?php esc_html_e( 'Make it a link', 'yop-poll' ); ?>
																	</label>
																  </div>
															</div>
															<div class="col-md-4">
																<div class="checkbox">
																	<label>
																		<?php esc_html_e( 'Results color', 'yop-poll' ); ?>
																	</label>
																	<input type="text" value="<?php echo esc_attr( $answer->meta_data['resultsColor'] ); ?>" class="form-control answer-results-color" />
																</div>
															</div>
														</div>
														<div class="row answer-link-section <?php echo esc_attr( $answer_make_link_class ); ?>">
															<div class="col-md-12">
																<input type="text" class="form-control border answer-link" value="<?php echo esc_attr( $answer->meta_data['link'] ); ?>" placeholder="http://">
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
										<?php
									}
									?>
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
												<?php
												if ( 'yes' === $element->meta_data['allowOtherAnswers'] ) {
													$element_allow_other_answers_yes = 'selected';
													$element_allow_other_answers_no = '';
													$element_allow_other_answers_class = '';
												} else {
													$element_allow_other_answers_yes = '';
													$element_allow_other_answers_no = 'selected';
													$element_allow_other_answers_class = 'hide';
												}
												?>
												<select class="allow-other-answers admin-select" style="width:100%">
													<option value="yes" <?php echo esc_attr( $element_allow_other_answers_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
													<option value="no" <?php echo esc_attr( $element_allow_other_answers_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
												</select>
											</div>
										</div>
										<div class="other-answers-section <?php echo esc_attr( $element_allow_other_answers_class ); ?>">
											<div class="form-group">
												<div class="col-md-3">
													<?php esc_html_e( 'Label for Other Answers', 'yop-poll' ); ?>
												</div>
												<div class="col-md-9">
													<input type="text" name="" value="<?php echo esc_attr( $element->meta_data['otherAnswersLabel'] ); ?>" class="form-control other-answers-label" />
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
													<?php
													if ( 'yes' === $element->meta_data['addOtherAnswers'] ) {
														$element_add_other_answers_yes = 'selected';
														$element_add_other_answers_no = '';
													} else {
														$element_add_other_answers_yes = '';
														$element_add_other_answers_no = 'selected';
													}
													?>
													<select class="add-other-answers admin-select" style="width:100%">
														<option value="yes" <?php echo esc_attr( $element_add_other_answers_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
														<option value="no" <?php echo esc_attr( $element_add_other_answers_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-3">
													<?php esc_html_e( 'Display other answers in results list', 'yop-poll' ); ?>
												</div>
												<div class="col-md-9">
													<?php
													if ( 'yes' === $element->meta_data['displayOtherAnswersInResults'] ) {
														$element_display_other_answers_in_results_yes = 'selected';
														$element_display_other_answers_in_results_no = '';
														$other_answers_results_color_section_class = '';
													} else {
														$element_display_other_answers_in_results_yes = '';
														$element_display_other_answers_in_results_no = 'selected';
														$other_answers_results_color_section_class = 'hide';
													}
													?>
													<select class="display-other-answers-in-results admin-select" style="width:100%">
														<option value="yes" <?php echo esc_attr( $element_display_other_answers_in_results_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
														<option value="no" <?php echo esc_attr( $element_display_other_answers_in_results_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
													</select>
												</div>
											</div>
											<div class="other-answers-results-color-section <?php echo esc_attr( $other_answers_results_color_section_class ); ?>">
												<?php
												if ( false === isset( $element->meta_data['resultsColorForOtherAnswers'] ) ) {
													$element->meta_data['resultsColorForOtherAnswers'] = '#000000';
												}
												?>
												<div class="form-group">
													<div class="col-md-3">
														<?php esc_html_e( 'Results Color', 'yop-poll' ); ?>
													</div>
													<div class="col-md-9">
														<input type="text" value="<?php echo esc_attr( $element->meta_data['resultsColorForOtherAnswers'] ); ?>" class="form-control other-answers-results-color" />
													</div>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-3">
												<?php esc_html_e( 'Allow multiple answers', 'yop-poll' ); ?>
											</div>
											<div class="col-md-9">
												<?php
												if ( 'yes' === $element->meta_data['allowMultipleAnswers'] ) {
													$element_allow_multiple_answers_yes = 'selected';
													$element_allow_multiple_answers_no = '';
													$element_allow_multiple_answers_class = '';
												} else {
													$element_allow_multiple_answers_yes = '';
													$element_allow_multiple_answers_no = 'selected';
													$element_allow_multiple_answers_class = 'hide';
												}
												?>
												<select class="allow-multiple-answers admin-select" style="width:100%">>
													<option value="yes" <?php echo esc_attr( $element_allow_multiple_answers_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
													<option value="no"  <?php echo esc_attr( $element_allow_multiple_answers_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
												</select>
											</div>
										</div>
										<div class="multiple-answers-section <?php echo esc_attr( $element_allow_multiple_answers_class ); ?>">
											<div class="form-group">
												<div class="col-md-3">
													<?php esc_html_e( 'Minimum answers required', 'yop-poll' ); ?>
												</div>
												<div class="col-md-9">
													<input type="text" name="" value="<?php echo esc_attr( $element->meta_data['multipleAnswersMinim'] ); ?>" class="form-control multiple-answers-minim"/>
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-3">
													<?php esc_html_e( 'Maximum answers allowed', 'yop-poll' ); ?>
												</div>
												<div class="col-md-9">
													<input type="text" name="" value="<?php echo esc_attr( $element->meta_data['multipleAnswersMaxim'] ); ?>" class="form-control multiple-answers-maxim"/>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-3">
												<?php esc_html_e( 'Display answers', 'yop-poll' ); ?>
											</div>
											<div class="col-md-9">
												<?php
												switch ( $element->meta_data['answersDisplay'] ) {
													case 'vertical': {
														$element_answers_display_vertical = 'selected';
														$element_answers_display_horizontal = '';
														$element_answers_display_columns = '';
														$answers_display_columns_class = 'hide';
														break;
													}
													case 'horizontal': {
														$element_answers_display_vertical = '';
														$element_answers_display_horizontal = 'selected';
														$element_answers_display_columns = '';
														$answers_display_columns_class = 'hide';
														break;
													}
													case 'columns': {
														$element_answers_display_vertical = '';
														$element_answers_display_horizontal = '';
														$element_answers_display_columns = 'selected';
														$answers_display_columns_class = '';
														break;
													}
												}
												?>
												<select class="answers-display admin-select" style="width:100%">
													<option value="vertical" <?php echo esc_attr( $element_answers_display_vertical ); ?>>
														<?php esc_html_e( 'Vertical', 'yop-poll' ); ?>
													</option>
													<option value="horizontal" <?php echo esc_attr( $element_answers_display_horizontal ); ?>>
														<?php esc_html_e( 'Horizontal', 'yop-poll' ); ?>
													</option>
													<option value="columns" <?php echo esc_attr( $element_answers_display_columns ); ?>>
														<?php esc_html_e( 'Columns', 'yop-poll' ); ?>
													</option>
												</select>
											</div>
										</div>
										<div class="form-group answers-display-section <?php echo esc_attr( $answers_display_columns_class ); ?>">
											<div class="col-md-3">
												<?php esc_html_e( 'Display answers', 'yop-poll' ); ?>
											</div>
											<div class="col-md-9">
												<input type="text" name="button-label" value="<?php echo esc_attr( $element->meta_data['answersColumns'] ); ?>" class="form-control answers-columns"/>&nbsp;<?php esc_html_e( 'columns', 'yop-poll' ); ?>
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
												<?php
												$element_answers_sort_as_defined = '';
												$element_answers_sort_alphabetically_asc = '';
												$element_answers_sort_alphabetically_desc = '';
												$element_answers_sort_random = '';
												if ( false === isset( $element->meta_data['answersSort'] ) ) {
													$element->meta_data['answersSort'] = 'as-defined';
													$element_answers_sort_as_defined = 'selected';
												} else {
													switch ( $element->meta_data['answersSort'] ) {
														case 'as-defined': {
															$element_answers_sort_as_defined = 'selected';
															break;
														}
														case 'alphabetically-asc': {
															$element_answers_sort_alphabetically_asc = 'selected';
															break;
														}
														case 'alphabetically-desc': {
															$element_answers_sort_alphabetically_desc = 'selected';
															break;
														}
														case 'random': {
															$element_answers_sort_random = 'selected';
															break;
														}
														default: {
															$element_answers_sort_as_defined = 'selected';
															break;
														}
													}
												}
												?>
												<select class="answers-sort admin-select" style="width:100%">
													<option value="as-defined" <?php echo esc_attr( $element_answers_sort_as_defined ); ?>>
														<?php esc_html_e( 'As Defined', 'yop-poll' ); ?>
													</option>
													<option value="alphabetically-asc" <?php echo esc_attr( $element_answers_sort_alphabetically_asc ); ?>>
														<?php esc_html_e( 'Alphabetically Ascending', 'yop-poll' ); ?>
													</option>
													<option value="alphabetically-desc" <?php echo esc_attr( $element_answers_sort_alphabetically_desc ); ?>>
														<?php esc_html_e( 'Alphabetically Descending', 'yop-poll' ); ?>
													</option>
													<option value="random" <?php echo esc_attr( $element_answers_sort_random ); ?>>
														<?php esc_html_e( 'Randomly', 'yop-poll' ); ?>
													</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
						break;
					}
					case 'custom-field': {
						?>
						<div class="poll-element question" data-type="custom-field" data-id="<?php echo esc_attr( $element->id ); ?>" data-remove="">
							<div class="title-bar">
								<span class="bar-title pull-left poll-element-collapse">
									<span class="glyphicon glyphicon-chevron-down hspace collapse-element" aria-hidden="true"></span>
								</span>
								<span class="bar-title pull-left poll-element-collapse element-title">
									<?php echo esc_html( substr( $element->etext, 0, 50 ) ); ?>
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
												<input type="text" class="form-control input-lg custom-field-name" name="question text" value="<?php echo esc_attr( $element->etext ); ?>" placeholder="<?php esc_html_e( 'Custom Field', 'yop-poll' ); ?>">
											</div>
											<div class="col-sm-1">
												<label class="pull-right set-as-default-inline" title="<?php esc_html_e( 'Set as required', 'yop-poll' ); ?>">
													<?php
                                                    if ( 'yes' === $element->meta_data['makeRequired'] ) {
                                                        $element_custom_field_required = 'checked';
                                                    } else {
                                                        $element_custom_field_required = '';
                                                    }
                                                    ?>
													<input type="checkbox" class="custom-field-required" <?php echo esc_attr( $element_custom_field_required ); ?>>
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
											<?php
											$custom_field_type_textfield = '';
											$custom_field_type_textarea = '';
											if ( true === isset( $element->meta_data['cType'] ) ) {
												switch ( $element->meta_data['cType'] ) {
													case 'textfield': {
														$custom_field_type_textfield = 'selected';
														break;
													}
													case 'textarea': {
														$custom_field_type_textarea = 'selected';
														break;
													}
													default: {
														$custom_field_type_textfield = 'selected';
														break;
													}
												}
											} else {
												$custom_field_type_textfield = 'selected';
											}
											?>
											<select class="custom-field-type admin-select" style="width: 100%">
												<option value="textfield" <?php echo esc_attr( $custom_field_type_textfield ); ?>>
													<?php esc_html_e( 'Textfield', 'yop-poll' ); ?>
												</option>
												<option value="textarea" <?php echo esc_attr( $custom_field_type_textarea ); ?>>
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
													  <input type="checkbox" class="custom-field-make-required" <?php echo esc_attr( $element_custom_field_required ); ?>> <?php esc_html_e( 'Set as Required', 'yop-poll' ); ?>
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
						<?php
						break;
					}
				}
			}
			?>
		</div>
	</div>
</div>
