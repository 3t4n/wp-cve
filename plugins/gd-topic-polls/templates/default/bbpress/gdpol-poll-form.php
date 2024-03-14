<?php

use Dev4Press\v43\Core\UI\Elements;

$_poll = gdpol_get_poll();

?>

<div class="gdpol-fields-wrapper" style="display: <?php echo $_poll->status == 'enable' ? 'block' : 'none'; ?>;">

    <div class="gdpol-field gdpol-field-regular">
        <label>
            <span><?php _e( 'Poll Question', 'gd-topic-polls' ); ?> <span class="gdpol-required" title="<?php _e( 'This field is required', 'gd-topic-polls' ); ?>">*</span></span>
            <input class="gdpol-poll-question" type="text" name="gdpol[poll][question]" value="<?php echo esc_attr( $_poll->question ); ?>" placeholder="<?php _e( 'Your poll question here', 'gd-topic-polls' ); ?>"/>
        </label>
    </div>

	<?php if ( gdpol_settings()->get( 'poll_field_description' ) ) { ?>

        <div class="gdpol-field gdpol-field-regular">
            <label>
                <span><?php _e( 'Poll Description', 'gd-topic-polls' ); ?></span>
                <textarea name="gdpol[poll][description]" placeholder="<?php _e( 'Describe your poll', 'gd-topic-polls' ); ?>"><?php echo esc_textarea( $_poll->description ); ?></textarea>
            </label>
        </div>

	<?php } ?>

    <div class="gdpol-field gdpol-field-responses">
        <label>
            <span><?php _e( 'List of possible responses', 'gd-topic-polls' ); ?></span>
        </label>
        <ul class="gdpol-responses-list">
			<?php

			$i = 0;
			$m = 0;

			foreach ( $_poll->responses as $response ) {
				$i ++;

				?>

                <li><?php gdpol_response_edit_template( $i, $response['id'], $response['response'] ); ?></li>

				<?php

				if ( $response['id'] > $m ) {
					$m = $response['id'];
				}
			}

			?>
        </ul>
        <button class="gdpol-new-response" type="button"><?php _e( 'Add Response', 'gd-topic-polls' ); ?></button>

		<?php if ( bbp_is_topic_edit() ) { ?>

            <p class="gdpol-description">
				<?php _e( 'Removing responses will invalidate votes assigned to removed responses. Changing the responses order is not affecting saved votes.', 'gd-topic-polls' ); ?>
            </p>

		<?php } ?>
    </div>

    <div class="gdpol-field gdpol-field-regular">
        <label>
            <span><?php _e( 'How many responses are allowed', 'gd-topic-polls' ); ?></span>
			<?php

			Elements::instance()->select( $_poll->form_data( 'choice' ), array(
				'class'    => 'gdpol-select-choices gdpol-field-extra-select',
				'selected' => $_poll->choice,
				'name'     => 'gdpol[poll][choice]',
			), array(
				'data-field' => 'gdpol-select-choices',
			) );

			?>
        </label>
    </div>

    <div class="gdpol-select-choices-field gdpol-select-choices-limit gdpol-field gdpol-field-regular gdpol-field-inside" style="display: <?php echo $_poll->choice == 'limit' ? 'block' : 'none'; ?>;">
        <label>
            <span><?php _e( 'Limit number of choices', 'gd-topic-polls' ); ?></span>
            <input min="2" step="1" type="number" name="gdpol[poll][choice_limit]" value="<?php echo esc_attr( $_poll->choice_limit ); ?>"/>
        </label>
    </div>

    <div class="gdpol-field gdpol-field-regular">
        <label>
            <span><?php _e( 'Closing the poll', 'gd-topic-polls' ); ?></span>
			<?php

			$_close_items = $_poll->form_data( 'close' );

			if ( ! bbp_is_topic_edit() ) {
				unset( $_close_items['closed'] );
			}

			Elements::instance()->select( $_close_items, array(
				'class'    => 'gdpol-select-closing gdpol-field-extra-select',
				'selected' => $_poll->close,
				'name'     => 'gdpol[poll][close]',
			), array(
				'data-field' => 'gdpol-select-closing',
			) );

			?>
        </label>
    </div>

	<?php if ( gdpol_settings()->get( 'poll_field_show_included' ) ) { ?>

        <div class="gdpol-field gdpol-field-regular">
            <label>
                <span><?php _e( 'Show results', 'gd-topic-polls' ); ?></span>
				<?php

				Elements::instance()->select( $_poll->form_data( 'show' ), array(
					'selected' => $_poll->show,
					'name'     => 'gdpol[poll][show]',
				) );

				?>
            </label>
        </div>

	<?php } else { ?>

        <input type="hidden" name="gdpol[poll][show]" value="<?php echo esc_attr( $_poll->show ); ?>"/>

	<?php } ?>

    <script type="text/template" id="gdpol-response-item-template">
		<?php gdpol_response_edit_template( '{{SEQID}}', '{{RESID}}', '{{RESPONSE}}' ); ?>
    </script>

    <script type="text/javascript">
        jQuery(document).ready(function() {
            window.wp.gdpol.edit.run(<?php echo $i; ?>, <?php echo $m; ?>);
        });
    </script>

</div>
