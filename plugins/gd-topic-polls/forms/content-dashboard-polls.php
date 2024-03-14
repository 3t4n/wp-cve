<?php

$data = gdpol_db()->dashboard_polls();

?>
<div class="d4p-group d4p-dashboard-card d4p-dashboard-card-polls">
    <h3><?php esc_html_e( 'Polls', 'gd-topic-polls' ); ?></h3>
    <div class="d4p-group-header">
        <ul>
            <li><a href="admin.php?page=gd-topic-polls-polls">
                    <i aria-hidden="true" class="d4p-icon d4p-ui-poll"></i>
                    <strong><?php echo $data['count']; ?></strong>
					<?php esc_html_e( 'Polls', 'gd-topic-polls' ); ?></a>
            </li>
        </ul>
        <div class="d4p-clearfix"></div>
    </div>
    <div class="d4p-group-inner">
        <h4><?php esc_html_e( 'Recent Polls', 'gd-topic-polls' ); ?></h4>
		<?php

		if ( empty( $data['polls'] ) ) {

			?><p><?php esc_html_e( 'No polls to display.', 'gd-topic-polls' ); ?></p><?php

		} else {

			?>
            <ul class="d4p-with-bullets d4p-full-width">

			<?php foreach ( $data['polls'] as $poll ) { ?>
                <li>
					<?php

					$author_url  = esc_url( bbp_get_user_profile_url( $poll->author_id ) );
					$author_name = esc_html( get_the_author_meta( 'display_name', $poll->author_id ) );

					echo sprintf( __( '%s created poll <strong>%s</strong> for topic %s %s ago.', 'gd-topic-polls' ),
						'<a href="' . $author_url . '">' . $author_name . '</a>',
						$poll->question,
						'<a href="' . $poll->url() . '">' . bbp_get_topic_title( $poll->topic_id ) . '</a>',
						$poll->get_posted_human()
					);

					?>
                </li>
			<?php } ?>

            </ul><?php

		}

		?>
    </div>
    <div class="d4p-group-footer">
        <a href="admin.php?page=gd-topic-polls-polls" class="button-primary"><?php esc_html_e( 'All Polls', 'gd-topic-polls' ); ?></a>
    </div>
</div>
