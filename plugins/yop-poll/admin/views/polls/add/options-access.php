<div class="row">
	<div class="col-md-12">
		&nbsp;
	</div>
</div>
<div class="row poll-options-access">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-12">
				<h4>
					<?php esc_html_e( 'Permissions', 'yop-poll' ); ?>
				</h4>
			</div>
		</div>
		<div class="form-horizontal">
			<div class="form-group">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Vote Permissions', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="vote-permissions" class="vote-permissions" value="guest">
							<?php esc_html_e( 'Guest', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="vote-permissions" class="vote-permissions" value="wordpress">
							<?php esc_html_e( 'WordPress', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="vote-permissions" class="vote-permissions" value="facebook">
							<?php esc_html_e( 'Facebook', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="vote-permissions" class="vote-permissions" value="google">
							<?php esc_html_e( 'Google', 'yop-poll' ); ?>
						</label>
					</div>
				</div>
			</div>
			<div class="form-group block-voters-section">
				<div class="col-md-3">
					<?php esc_html_e( 'Block Voters', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="block-voters" class="block-voters" value="no-block" checked>
							<?php esc_html_e( 'Don\'t Block', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="block-voters" class="block-voters" value="by-cookie">
							<?php esc_html_e( 'By Cookie', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="block-voters" class="block-voters" value="by-ip">
							<?php esc_html_e( 'By Ip', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="block-voters" class="block-voters" value="by-user-id">
							<?php esc_html_e( 'By User Id', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="block-voters" class="block-voters" value="by-fingerprint">
							<?php esc_html_e( 'By Fingerprint', 'yop-poll' ); ?>
						</label>
					</div>
				</div>
			</div>
			<div class="form-group block-type-section hide">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Block Period', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<select class="block-length-type admin-select" style="width:100%">
						<option value="forever" selected>
							<?php esc_html_e( 'Forever', 'yop-poll' ); ?>
						</option>
						<option value="limited-time">
							<?php esc_html_e( 'Limited Time', 'yop-poll' ); ?>
						</option>
					</select>
				</div>
			</div>
			<div class="form-group block-length-section hide">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Period', 'yop-poll' ); ?>
				</div>
				<div class="col-md-2">
					<input type="text" class="form-control block-length-1" value=""/>
				</div>
				<div class="col-md-7">
					<select class="block-length-2 admin-select" style="width:100%;">
	                    <option value="minutes" selected><?php esc_html_e( 'Minutes', 'yop-poll' ); ?></option>
	                    <option value="hours"><?php esc_html_e( 'Hours', 'yop-poll' ); ?></option>
	                    <option value="days"><?php esc_html_e( 'Days', 'yop-poll' ); ?></option>
	                </select>
				</div>
			</div>
			<div class="form-group limit-votes-per-user-section hide">
				<div class="col-md-3">
					<?php esc_html_e( 'Limit Number Of Votes per User', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<select class="limit-votes-per-user admin-select" style="width:100%">
		                <option value="no" selected><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
		                <option value="yes"><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
		            </select>
				</div>
			</div>
			<div class="form-group votes-per-user-section hide">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Votes per user', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control votes-per-user-allowed" style="width:100%" />
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
