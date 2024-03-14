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
					<?php
			        $vote_permissions_guest = '';
			        $vote_permissions_wordpress = '';
			        $vote_permissions_facebook = '';
			        $vote_permissions_google = '';
			        if ( true === in_array( 'guest', $poll->meta_data['options']['access']['votePermissions'] ) ) {
			            $vote_permissions_guest = 'checked';
			        }
			        if ( true === in_array( 'wordpress', $poll->meta_data['options']['access']['votePermissions'] ) ) {
			            $vote_permissions_wordpress = 'checked';
			        }
			        if ( true === in_array( 'facebook', $poll->meta_data['options']['access']['votePermissions'] ) ) {
			            $vote_permissions_facebook = 'checked';
			        }
			        if ( true === in_array( 'google', $poll->meta_data['options']['access']['votePermissions'] ) ) {
			            $vote_permissions_google = 'checked';
			        }
			        ?>
			        <div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="vote-permissions" class="vote-permissions" value="guest" <?php echo esc_attr( $vote_permissions_guest ); ?>>
							<?php esc_html_e( 'Guest', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="vote-permissions" class="vote-permissions" value="wordpress" <?php echo esc_attr( $vote_permissions_wordpress ); ?>>
							<?php esc_html_e( 'WordPress', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="vote-permissions" class="vote-permissions" value="facebook" <?php echo esc_attr( $vote_permissions_facebook ); ?>>
							<?php esc_html_e( 'Facebook', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="vote-permissions" class="vote-permissions" value="google" <?php echo esc_attr( $vote_permissions_google ); ?>>
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
				<?php
			        $block_voters_no_block = '';
			        $block_voters_by_cookie = '';
			        $block_voters_by_ip = '';
					$block_voters_by_user_id = '';
					$block_voters_by_fingerprint = '';
					$block_type_section_class = 'hide';
					$block_time_section_class = 'hide';
			        if ( ( true === in_array( 'no-block', $poll->meta_data['options']['access']['blockVoters'] ) ) || ( 0 === count( $poll->meta_data['options']['access']['blockVoters'] ) ) ) {
						$block_voters_no_block = 'checked';
						$block_type_section_class = 'hide';
						$block_time_section_class = 'hide';
			        }
			        if ( true === in_array( 'by-cookie', $poll->meta_data['options']['access']['blockVoters'] ) ) {
						$block_voters_by_cookie = 'checked';
						$block_type_section_class = '';
			            $block_time_section_class = '';
			        }
			        if ( true === in_array( 'by-ip', $poll->meta_data['options']['access']['blockVoters'] ) ) {
						$block_voters_by_ip = 'checked';
						$block_type_section_class = '';
			            $block_time_section_class = '';
			        }
			        if ( true === in_array( 'by-user-id', $poll->meta_data['options']['access']['blockVoters'] ) ) {
						$block_voters_by_user_id = 'checked';
						$block_type_section_class = '';
			            $block_time_section_class = '';
			        }
			        ?>
			        <div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="block-voters" class="block-voters" value="no-block" <?php echo esc_attr( $block_voters_no_block ); ?>>
							<?php esc_html_e( 'Don\'t Block', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="block-voters" class="block-voters" value="by-cookie" <?php echo esc_attr( $block_voters_by_cookie ); ?>>
							<?php esc_html_e( 'By Cookie', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="block-voters" class="block-voters" value="by-ip" <?php echo esc_attr( $block_voters_by_ip ); ?>>
							<?php esc_html_e( 'By Ip', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="block-voters" class="block-voters" value="by-user-id" <?php echo esc_attr( $block_voters_by_user_id ); ?>>
							<?php esc_html_e( 'By User Id', 'yop-poll' ); ?>
						</label>
					</div>
					<div class="checkbox-inline">
						<label class="admin-label">
							<input type="checkbox" name="block-voters" class="block-voters" value="by-fingerprint" <?php echo esc_attr( $block_voters_by_fingerprint ); ?>>
							<?php esc_html_e( 'By Fingerprint', 'yop-poll' ); ?>
						</label>
					</div>
				</div>
			</div>
			<div class="form-group block-type-section <?php echo esc_attr( $block_type_section_class ); ?>">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Block Period', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
				<?php
				$block_length_type_forever = '';
				$block_length_type_limited = '';
				if ( false === isset( $poll->meta_data['options']['access']['blockLengthType'] ) ) {
					$poll->meta_data['options']['access']['blockLengthType'] = 'limited-time';
				}
				switch ( $poll->meta_data['options']['access']['blockLengthType'] ) {
					case 'forever':{
						$block_length_type_forever = 'selected';
						break;
					}
					case 'limited-time':{
						$block_length_type_limited = 'selected';
						break;
					}
					default: {
						$block_length_type_forever = 'selected';
						break;
					}
				}
				?>
					<select class="block-length-type admin-select" style="width:100%">
						<option value="forever" <?php echo esc_attr( $block_length_type_forever ); ?>>
							<?php esc_html_e( 'Forever', 'yop-poll' ); ?>
						</option>
						<option value="limited-time" <?php echo esc_attr( $block_length_type_limited ); ?>>
							<?php esc_html_e( 'Limited Time', 'yop-poll' ); ?>
						</option>
					</select>
				</div>
			</div>
			<?php
			if ( ( 'forever' === $poll->meta_data['options']['access']['blockLengthType'] ) || ( true === in_array( 'no-block', $poll->meta_data['options']['access']['blockVoters'] ) ) || ( 0 === count( $poll->meta_data['options']['access']['blockVoters'] ) ) ) {
				$block_time_section_class = 'hide';
			} else {
				$block_time_section_class = '';
			}
			?>
			<div class="form-group block-length-section <?php echo esc_attr( $block_time_section_class ); ?>">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Period', 'yop-poll' ); ?>
				</div>
				<div class="col-md-2">
					<input type="text" class="form-control block-length-1" value="<?php echo esc_attr( $poll->meta_data['options']['access']['blockForValue'] ); ?>"/>
					<?php
		            $block_for_period_minutes = '';
		            $block_for_period_hours = '';
		            $block_for_period_days = '';
		            switch ( $poll->meta_data['options']['access']['blockForPeriod'] ) {
		                case 'minutes': {
		                    $block_for_period_minutes = 'selected';
		                    break;
		                }
		                case 'hours': {
		                    $block_for_period_hours = 'selected';
		                    break;
		                }
		                case 'days': {
		                    $block_for_period_days = 'selected';
		                    break;
		                }
		            }
		            ?>
				</div>
				<div class="col-md-7">
		            <select class="block-length-2 admin-select" style="width:100%">
		                <option value="minutes" <?php echo esc_attr( $block_for_period_minutes ); ?>><?php esc_html_e( 'Minutes', 'yop-poll' ); ?></option>
		                <option value="hours" <?php echo esc_attr( $block_for_period_hours ); ?>><?php esc_html_e( 'Hours', 'yop-poll' ); ?></option>
		                <option value="days" <?php echo esc_attr( $block_for_period_days ); ?>><?php esc_html_e( 'Days', 'yop-poll' ); ?></option>
		            </select>
				</div>
			</div>
			<?php
			if ( ( true === in_array( 'wordpress', $poll->meta_data['options']['access']['votePermissions'] ) ) && ( false === in_array( 'guest', $poll->meta_data['options']['access']['votePermissions'] ) ) ) {
				$limit_votes_per_user_section_class = '';
			} else {
				$limit_votes_per_user_section_class = 'hide';
			}
			?>
			<div class="form-group limit-votes-per-user-section <?php echo esc_attr( $limit_votes_per_user_section_class ); ?>">
				<div class="col-md-3">
					<?php esc_html_e( 'Limit Number Of Votes per User', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
			        if ( 'yes' === $poll->meta_data['options']['access']['limitVotesPerUser'] ) {
			            $limit_votes_per_user_yes = 'selected';
			            $limit_votes_per_user_no = '';
			            $votes_per_user_section_class = '';
			        } else {
			            $limit_votes_per_user_yes = '';
			            $limit_votes_per_user_no = 'selected';
			            $votes_per_user_section_class = 'hide';
			        }
			        ?>
			        <select class="limit-votes-per-user admin-select" style="width:100%">
			            <option value="no" <?php echo esc_attr( $limit_votes_per_user_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			            <option value="yes" <?php echo esc_attr( $limit_votes_per_user_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group votes-per-user-section <?php echo esc_attr( $votes_per_user_section_class ); ?>">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Votes per user', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control votes-per-user-allowed" value="<?php echo esc_attr( $poll->meta_data['options']['access']['votesPerUserAllowed'] ); ?>" style="width:100%" />
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
