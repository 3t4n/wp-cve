<?php $_poll = gdpol_get_poll(); ?>

<div class="gdpol-topic-poll" id="gdpol-form-<?php echo $_poll->id; ?>">
    <header>
        <h2><?php echo $_poll->question; ?></h2>

		<?php if ( ! empty( $_poll->description ) ) { ?>
            <p><?php echo $_poll->description; ?></p>
		<?php } ?>
    </header>

    <div class="gdpol-choices-list">
		<?php

		$_poll->render_message();

		if ( $_poll->show_results() ) {

			$_poll->render_results();

		} else if ( $_poll->show_form() ) {

			bbp_get_template_part( 'gdpol-poll', 'respond' );

		} else if ( $_poll->show_choices() ) {

			$_poll->render_list_choices();

		}

		?>
    </div>

    <footer>
        <div class="gdpol-footer-voters">
			<?php

			$_status = array(
				sprintf( __( 'Voters: <strong>%s</strong>', 'gd-topic-polls' ), $_poll->count_voters() ),
			);

			if ( $_poll->is_multi_choice() ) {
				$_status[] = sprintf( __( 'Votes: <strong>%s</strong>', 'gd-topic-polls' ), $_poll->count_votes() );
			}

			echo join( ' | ', $_status );

			?>
        </div>
        <div class="gdpol-footer-actions">
			<?php

			$_actions = $_poll->actions();

			echo join( ' | ', $_actions );

			?>
        </div>
    </footer>
</div>
