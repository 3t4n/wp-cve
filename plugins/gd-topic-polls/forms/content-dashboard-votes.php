<?php

use Dev4Press\Plugin\GDPOL\Basic\Poll;

$data  = gdpol_db()->dashboard_votes_counts();
$votes = gdpol_db()->dashboard_votes();

?>
<div class="d4p-group d4p-dashboard-card d4p-dashboard-card-votes">
    <h3><?php esc_html_e( 'Votes', 'gd-topic-polls' ); ?></h3>
    <div class="d4p-group-header">
        <ul>
            <li><a href="admin.php?page=gd-topic-polls-votes">
                    <i aria-hidden="true" class="d4p-icon d4p-ui-vote-yea"></i>
                    <strong><?php echo $data->votes; ?></strong>
					<?php esc_html_e( 'Total Votes', 'gd-topic-polls' ); ?></a>
            </li>
            <li><a href="admin.php?page=gd-topic-polls-votes">
                    <i aria-hidden="true" class="d4p-icon d4p-ui-users"></i>
                    <strong><?php echo $data->users; ?></strong>
					<?php esc_html_e( 'Total Voters', 'gd-topic-polls' ); ?></a>
            </li>
        </ul>
        <div class="d4p-clearfix"></div>
    </div>
    <div class="d4p-group-inner">
        <h4><?php esc_html_e( 'Recent Votes', 'gd-topic-polls' ); ?></h4>
		<?php

		if ( empty( $votes ) ) {

			?><p><?php esc_html_e( 'No votes to display.', 'gd-topic-polls' ); ?></p><?php

		} else {

			?>
            <ul class="d4p-with-bullets d4p-full-width">

			<?php foreach ( $votes as $vote ) { ?>
                <li>
					<?php

					if ( $vote->user_id > 0 ) {
						$voter_url  = bbp_get_user_profile_url( $vote->user_id );
						$voter_name = get_the_author_meta( 'display_name', $vote->user_id );

						if ( empty( $voter_name ) ) {
							$voter_url  = '';
							$voter_name = __( 'Unknown', 'gd-topic-polls' );
						}
					} else {
						$voter_url  = '';
						$voter_name = __( 'Anonymous', 'gd-topic-polls' );
					}

					$poll = Poll::load( $vote->poll_id );

					echo sprintf( __( '<strong>%s</strong> voted in poll <strong>%s</strong> for <strong>%s</strong> %s ago.', 'gd-topic-polls' ),
						empty( $voter_url ) ? $voter_name : '<a href="' . $voter_url . '">' . $voter_name . '</a>',
						'<a href="' . $poll->url() . '">' . $poll->question . '</a>',
						$poll->get_answer_by_id( $vote->answer_id, 'UNK' ),
						human_time_diff( strtotime( $vote->voted ) )
					);

					?>
                </li>
			<?php } ?>

            </ul><?php

		}

		?>
    </div>
    <div class="d4p-group-footer">
        <a href="admin.php?page=gd-topic-polls-votes" class="button-primary"><?php esc_html_e( 'All Votes', 'gd-topic-polls' ); ?></a>
    </div>
</div>
