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
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="show-results-moment" class="show-results-moment" value="before-vote">
							<?php esc_html_e( 'Before vote', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="show-results-moment" class="show-results-moment" value="after-vote">
							<?php esc_html_e( 'After vote', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="show-results-moment" class="show-results-moment" value="after-end-date">
							<?php esc_html_e( 'After poll end date', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="show-results-moment" class="show-results-moment" value="custom-date">
							<?php esc_html_e( 'Custom Date', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="show-results-moment" class="show-results-moment" value="never">
							<?php esc_html_e( 'Never', 'yop-poll' ); ?>
						</label>
					</div>
				</div>
			</div>
			<div class="form-group custom-date-results-section hide">
				<div class="col-md-3">
				</div>
				<div class="col-md-9">
					<div class="input-group">
						<input type="text" class="form-control custom-date-results" readonly />
						<input type="hidden" class="form-control custom-date-results-hidden" />
		                <div class="input-group-addon">
							<span class="dashicons dashicons-calendar-alt show-custom-date-results"></span>
		                </div>
					</div>
				</div>
			</div>
			<div class="form-group show-results-to-section hide">
				<div class="col-md-3">
					<?php esc_html_e( 'Show results to', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="show-results-to" class="show-results-to" value="guest">
							<?php esc_html_e( 'Guest', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="show-results-to" class="show-results-to" value="registered">
							<?php esc_html_e( 'Registered', 'yop-poll' ); ?>
						</label>
					</div>
				</div>
			</div>
            <div class="form-group show-results-details-section hide">
                <div class="col-md-3">
					<?php esc_html_e( 'Show Details as', 'yop-poll' ); ?>
                </div>
                <div class="col-md-9">
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="results-details-option" class="results-details-option"  value="votes-number">
							<?php esc_html_e( 'Votes Number', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="results-details-option" class="results-details-option"  value="percentages" checked>
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
					<select class="back-to-vote-option admin-select" style="width:100%">
			            <option value="no" selected><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			            <option value="yes"><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group back-to-vote-caption-section hide">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( '[Back to vote] caption', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control back-to-vote-caption" value="<?php esc_html_e( 'Back to vote', 'yop-poll' ); ?>"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Sort results', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<select class="sort-results admin-select" style="width:100%">
			            <option value="as-defined" selected><?php esc_html_e( 'As Defined', 'yop-poll' ); ?></option>
			            <option value="alphabetical"><?php esc_html_e( 'Alphabetical Order', 'yop-poll' ); ?></option>
			            <option value="number-of-votes"><?php esc_html_e( 'Number of votes', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group sort-results-rule-section hide">
				<div class="col-md-3">
					<?php esc_html_e( 'Sort rule', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<select class="sort-results-rule admin-select" style="width:100%">
			            <option value="asc" selected><?php esc_html_e( 'Ascending', 'yop-poll' ); ?></option>
			            <option value="desc"><?php esc_html_e( 'Descending', 'yop-poll' ); ?></option>
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
					<select class="display-results-as admin-select" style="width:100%">
			            <option value="bar" selected><?php esc_html_e( 'Bars', 'yop-poll' ); ?></option>
			            <option value="pie"><?php esc_html_e( 'Pie', 'yop-poll' ); ?></option>
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
