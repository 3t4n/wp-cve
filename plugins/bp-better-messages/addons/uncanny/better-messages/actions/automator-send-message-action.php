<?php

use Uncanny_Automator\Recipe;

/**
 * Class Automator_Send_Action
 */
class Automator_Send_Message_Action {
	use Recipe\Actions;


	/**
	 * Automator_Sample_Action constructor.
	 */
	public function __construct() {
		$this->setup_action();
	}

	/**
	 *
	 */

	protected function setup_action() {

		$this->set_integration( 'BETTER_MESSAGES' );
		$this->set_action_code( 'BM_SEND_MESSAGE' );
		$this->set_action_meta( 'BM_SEND_PRIVATE_MESSAGE' );
		/* translators: Action - WordPress */
		$this->set_sentence( sprintf( esc_attr_x( 'Send {{a private message:%1$s}} to the user', 'Uncanny Automator Integration', 'bp-better-messages' ), $this->get_action_meta() ) );
		/* translators: Action - WordPress */
		$this->set_readable_sentence( esc_attr_x( 'Send {{a private message}} to the user', 'Uncanny Automator Integration', 'bp-better-messages' ) );

		$options_group = array(
			$this->get_action_meta() => array(
                Automator()->helpers->recipe->field->int([
                    'option_code' => 'BM_SENDER',
                    'label' => esc_attr_x( 'Sender', 'Uncanny Automator Integration', 'bp-better-messages' ),
                    'tokens' => true,
                    'default' => '',
                    'required' => true,
                    'description' => esc_attr_x( 'Accepts User ID', 'Uncanny Automator Integration', 'bp-better-messages' ),
                ]),
                Automator()->helpers->recipe->field->int([
                    'option_code' => 'BM_RECIPIENT',
                    'label' => esc_attr_x( 'Recipient', 'Uncanny Automator Integration', 'bp-better-messages' ),
                    'tokens' => true,
                    'default' => '',
                    'required' => true,
                    'description' => esc_attr_x( 'Accepts User ID', 'Uncanny Automator Integration', 'bp-better-messages' ),
                ]),
                Automator()->helpers->recipe->field->text(
                    array(
                        'option_code' => 'BM_SUBJECT',
                        'label'       => 'Subject',
                        'input_type'  => 'text',
                        'required' => false,
                    )
                ),
                Automator()->helpers->recipe->field->text(
                    array(
                        'option_code' => 'BM_UNIQUE_TAG',
                        'label'       => 'Unique Tag',
                        'tokens'      => false,
                        'input_type'  => 'text',
                        'required'    => false,
                        'description' => esc_attr_x( 'If you dont want to create many conversations with same user. You need to put unique tag, which will create conversation or send reply to conversation within the unique tag. For example for all system messages you can put "system" tag and they all will be sending to same conversation.', 'Uncanny Automator Integration', 'bp-better-messages' ),
                    )
                ),
                array(
                    'option_code' => 'BM_CONTENT',
                    'label'       => 'Content',
                    'input_type'  => 'textarea',
                    'required' => true,
                ),
			),
		);

		$this->set_options_group( $options_group );

		$this->register_action();
	}

	/**
	 * @param int $user_id
	 * @param array $action_data
	 * @param int $recipe_id
	 * @param array $args
	 * @param $parsed
	 */
	protected function process_action( $user_id, $action_data, $recipe_id, $args, $parsed ) {
		$action_meta = $action_data['meta'];

        $sender_id = (int) Automator()->parse->text( $action_meta['BM_SENDER'], $recipe_id, $user_id, $args );
        $recipient = (int) Automator()->parse->text( $action_meta['BM_RECIPIENT'], $recipe_id, $user_id, $args );
        $subject   = Automator()->parse->text( $action_meta['BM_SUBJECT'], $recipe_id, $user_id, $args );
        $content   = Automator()->parse->text( $action_meta['BM_CONTENT'], $recipe_id, $user_id, $args );

		// Parsing fields to return an actual value from token
		$data = array(
			'sender_id'  => $sender_id,
			'recipients' => $recipient,
			'subject'    => $subject,
			'content'    => $content,
            'return'     => 'wp_error',
		);

        $unique_tag = trim( Automator()->parse->text( $action_meta['BM_UNIQUE_TAG'], $recipe_id, $user_id, $args ) );

        if( ! empty( $unique_tag ) ){
            $user_ids   = [ $sender_id, $recipient ];

            $thread_id = Better_Messages()->functions->get_unique_conversation_id( $user_ids, $unique_tag, $subject );

            $data = array(
                'thread_id'  => $thread_id,
                'sender_id'  => $sender_id,
                'content'    => $content,
                'return'     => 'both',
                'error_type' => 'wp_error'
            );
        }

        $send = Better_Messages()->functions->new_message( $data );

		// If there was an error, it'll be logged in action log with an error message.
		if ( is_wp_error( $send ) ) {
			$error_message = $send->get_error_message();
			// Complete action with errors and log Error message.
			Automator()->complete->action( $user_id, $action_data, $recipe_id, $error_message );
		}

		// Everything went fine. Complete action.
		Automator()->complete->action( $user_id, $action_data, $recipe_id );
	}
}
