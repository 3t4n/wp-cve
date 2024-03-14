<div class="row">
	<div class="col-md-12">
		&nbsp;
	</div>
</div>
<div class="row poll-results-options">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-12">
				<h4>
					<?php esc_html_e( 'Display', 'yop-poll' ); ?>
				</h4>
			</div>
		</div>
		<div class="form-horizontal">
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Show results', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
			        $show_results_moment_before_vote = '';
			        $show_results_moment_after_vote = '';
			        $show_results_moment_after_end_date = '';
			        $show_results_moment_never = '';
			        $show_results_moment_custom_date = '';
			        $show_results_moment_custom_date_class = 'hide';
			        $show_results_to_class = 'hide';
			        if ( true === in_array( 'before-vote', $poll->meta_data['options']['results']['showResultsMoment'] ) ) {
			            $show_results_moment_before_vote = 'checked';
			            $show_results_to_class = '';
			        }
			        if ( true === in_array( 'after-vote', $poll->meta_data['options']['results']['showResultsMoment'] ) ) {
			            $show_results_moment_after_vote = 'checked';
			            $show_results_to_class = '';
			        }
			        if ( true === in_array( 'after-end-date', $poll->meta_data['options']['results']['showResultsMoment'] ) ) {
			            $show_results_moment_after_end_date = 'checked';
			            $show_results_to_class = '';
			        }
			        if ( true === in_array( 'custom-date', $poll->meta_data['options']['results']['showResultsMoment'] ) ) {
			            $show_results_moment_custom_date = 'checked';
			            $show_results_moment_custom_date_class = '';
			            $show_results_to_class = '';
			        }
			        if ( true === in_array( 'never', $poll->meta_data['options']['results']['showResultsMoment'] ) ) {
			            $show_results_moment_never = 'checked';
			            $show_results_to_class = 'hide';
			        }
			        ?>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="show-results-moment" class="show-results-moment" value="before-vote" <?php echo esc_attr( $show_results_moment_before_vote ); ?>>
							<?php esc_html_e( 'Before vote', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="show-results-moment" class="show-results-moment" value="after-vote" <?php echo esc_attr( $show_results_moment_after_vote ); ?>>
							<?php esc_html_e( 'After vote', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="show-results-moment" class="show-results-moment" value="after-end-date" <?php echo esc_attr( $show_results_moment_after_end_date ); ?>>
							<?php esc_html_e( 'After poll end date', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="show-results-moment" class="show-results-moment" value="custom-date" <?php echo esc_attr( $show_results_moment_custom_date ); ?>>
							<?php esc_html_e( 'Custom Date', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="show-results-moment" class="show-results-moment" value="never" <?php echo esc_attr( $show_results_moment_never ); ?>>
							<?php esc_html_e( 'Never', 'yop-poll' ); ?>
						</label>
					</div>
				</div>
			</div>
			<div class="form-group custom-date-results-section <?php echo esc_attr( $show_results_moment_custom_date_class ); ?>">
				<div class="col-md-3">
				</div>
				<div class="col-md-9">
					<div class="input-group">
						<input type="text" class="form-control custom-date-results" value="<?php echo esc_attr( $poll->meta_data['options']['results']['customDateResults'] ); ?>" readonly />
						<input type="hidden" class="form-control custom-date-results-hidden" value="<?php echo esc_attr( $poll->meta_data['options']['results']['customDateResults'] ); ?>" />
		                <div class="input-group-addon">
							<span class="dashicons dashicons-calendar-alt show-custom-date-results"></span>
		                </div>
					</div>
				</div>
			</div>
			<div class="form-group show-results-to-section <?php echo esc_attr( $show_results_to_class ); ?>">
				<div class="col-md-3">
					<?php esc_html_e( 'Show results to', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
			        $show_results_to_guest = '';
			        $show_results_to_registered = '';
					if ( true === is_array( $poll->meta_data['options']['results']['showResultsTo'] ) ) {
						if ( true === in_array( 'guest', $poll->meta_data['options']['results']['showResultsTo'] ) ) {
							$show_results_to_guest = 'checked';
						}
						if ( true === in_array( 'registered', $poll->meta_data['options']['results']['showResultsTo'] ) ) {
							$show_results_to_registered = 'checked';
						}
					}
			        ?>
			        <div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="show-results-to" class="show-results-to" value="guest" <?php echo esc_attr( $show_results_to_guest ); ?>>
							<?php esc_html_e( 'Guest', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="show-results-to" class="show-results-to" value="registered" <?php echo esc_attr( $show_results_to_registered ); ?>>
							<?php esc_html_e( 'Registered', 'yop-poll' ); ?>
						</label>
					</div>
				</div>
			</div>
            <div class="form-group">
                <div class="col-md-3">
					<?php esc_html_e( 'Show Details as', 'yop-poll' ); ?>
                </div>
                <div class="col-md-9">
	                <?php
	                $results_details_votes_number = '';
	                $results_details_percentages = '';
	                if ( true === in_array( 'votes-number', $poll->meta_data['options']['results']['resultsDetails'] ) ) {
		                $results_details_votes_number = 'checked';
	                }
	                if ( true === in_array( 'percentages', $poll->meta_data['options']['results']['resultsDetails'] ) ) {
		                $results_details_percentages = 'checked';
	                }
	                ?>
                    <div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="results-details-option" class="results-details-option"  value="votes-number" <?php echo esc_attr( $results_details_votes_number ); ?>>
							<?php esc_html_e( 'Votes Number', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="results-details-option" class="results-details-option"  value="percentages"  <?php echo esc_attr( $results_details_percentages ); ?>>
							<?php esc_html_e( 'Percentages', 'yop-poll' ); ?>
						</label>
					</div>
                </div>
            </div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Display [Back to vote] link', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
			        if ( 'yes' === $poll->meta_data['options']['results']['backToVoteOption'] ) {
			            $back_to_vote_option_yes = 'selected';
			            $back_to_vote_option_no = '';
			            $back_to_vote_caption_class = '';
			        } else {
			            $back_to_vote_option_yes = '';
			            $back_to_vote_option_no = 'selected';
			            $back_to_vote_caption_class = 'hide';
			        }
			        ?>
			        <select class="back-to-vote-option admin-select" style="width:100%">
			            <option value="no" <?php echo esc_attr( $back_to_vote_option_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			            <option value="yes" <?php echo esc_attr( $back_to_vote_option_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group back-to-vote-caption-section <?php echo esc_attr( $back_to_vote_caption_class ); ?>">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( '[Back to vote] caption', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control back-to-vote-caption" value="<?php echo esc_attr( $poll->meta_data['options']['results']['backToVoteCaption'] ); ?>"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Sort results', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
			        switch ( $poll->meta_data['options']['results']['sortResults'] ) {
			            case 'as-defined': {
			                $sort_results_as_defined = 'selected';
			                $sort_results_as_alphabetical = '';
			                $sort_results_as_number_of_votes = '';
			                $sort_results_rule_class = 'hide';
			                break;
			            }
			            case 'alphabetical': {
			                $sort_results_as_defined = '';
			                $sort_results_as_alphabetical = 'selected';
			                $sort_results_as_number_of_votes = '';
			                $sort_results_rule_class = '';
			                break;
			            }
			            case 'number-of-votes': {
			                $sort_results_as_defined = '';
			                $sort_results_as_alphabetical = '';
			                $sort_results_as_number_of_votes = 'selected';
			                $sort_results_rule_class = '';
			                break;
			            }
			            default: {
			                $sort_results_as_defined = '';
			                $sort_results_as_alphabetical = '';
			                $sort_results_as_number_of_votes = '';
			                $sort_results_rule_class = 'hide';
			                break;
			            }
			        }
			        ?>
			        <select class="sort-results admin-select" style="width:100%">
			            <option value="as-defined" <?php echo esc_attr( $sort_results_as_defined ); ?>>
			                <?php esc_html_e( 'As Defined', 'yop-poll' ); ?>
			            </option>
			            <option value="alphabetical" <?php echo esc_attr( $sort_results_as_alphabetical ); ?>>
			                <?php esc_html_e( 'Alphabetical Order', 'yop-poll' ); ?>
			            </option>
			            <option value="number-of-votes" <?php echo esc_attr( $sort_results_as_number_of_votes ); ?>>
			                <?php esc_html_e( 'Number of votes', 'yop-poll' ); ?>
			            </option>
			        </select>
				</div>
			</div>
			<div class="form-group sort-results-rule-section <?php echo esc_attr( $sort_results_rule_class ); ?>">
				<div class="col-md-3">
					<?php esc_html_e( 'Sort rule', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
			        if ( 'asc' === $poll->meta_data['options']['results']['sortResultsRule'] ) {
			            $sort_results_rule_asc = 'selected';
			            $sort_results_rule_desc = '';
			        } else {
			            $sort_results_rule_asc = '';
			            $sort_results_rule_desc = 'selected';
			        }
			        ?>
			        <select class="sort-results-rule admin-select" style="width:100%">
			            <option value="asc" <?php echo esc_attr( $sort_results_rule_asc ); ?>><?php esc_html_e( 'Ascending', 'yop-poll' ); ?></option>
			            <option value="desc" <?php echo esc_attr( $sort_results_rule_desc ); ?>><?php esc_html_e( 'Descending', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<a href="#" class="upgrade-to-pro" data-screen="pie-results">
						<img src="<?php echo esc_url( YOP_POLL_URL ); ?>admin/assets/images/pro-horizontal.svg" class="responsive" />
					</a>
					<?php esc_html_e( 'Display Results As', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
			        if ( 'bar' === $poll->meta_data['options']['results']['displayResultsAs'] ) {
			            $display_results_as_bar = 'selected';
			            $display_results_as_pie = '';
			        } else {
			            $display_results_as_bar = '';
			            $display_results_as_pie = 'selected';
			        }
			        ?>
			        <select class="display-results-as admin-select" style="width:100%">
			            <option value="bar" <?php echo esc_attr( $display_results_as_bar ); ?>><?php esc_html_e( 'Bars', 'yop-poll' ); ?></option>
			            <option value="pie" <?php echo esc_attr( $display_results_as_pie ); ?>><?php esc_html_e( 'Pie', 'yop-poll' ); ?></option>
			        </select>
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
