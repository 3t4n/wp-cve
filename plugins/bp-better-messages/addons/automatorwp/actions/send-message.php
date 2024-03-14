<?php
if( !defined( 'ABSPATH' ) ) exit;

class AutomatorWP_Better_Messages_Send_Message extends AutomatorWP_Integration_Action {

    public $integration = 'better_messages';
    public $action = 'better_messages_send_message';

    public $thread_id;
    public $message_id;

    /**
     * Register the trigger
     *
     * @since 1.0.0
     */
    public function register() {
        automatorwp_register_action( $this->action, array(
            'integration'       => $this->integration,
            'label'             => _x( 'Send <strong>a private message</strong> to the user', 'Uncanny AutomatorWP Integration', 'bp-better-messages' ),
            'select_option'     => _x( 'Send <strong>a private message</strong> to the user', 'Uncanny AutomatorWP Integration', 'bp-better-messages' ),
            /* translators: %1$s: User. */
            'edit_label'        => sprintf( _x( 'Send a %1$s to the user', 'Uncanny AutomatorWP Integration', 'bp-better-messages' ), '{private_message}' ),
            /* translators: %1$s: User. */
            'log_label'         => sprintf( _x( 'Send a %1$s to the user', 'Uncanny AutomatorWP Integration', 'bp-better-messages' ), '{private_message}' ),
            'options'           => array(
                'private_message' => array(
                    'default' => _x( 'a private message', 'Uncanny AutomatorWP Integration', 'bp-better-messages' ),
                    'fields' => array(
                        'sender_id' => array(
                            'name' => _x( 'Sender', 'Uncanny AutomatorWP Integration', 'bp-better-messages' ),
                            'desc' => _x( 'Accepts User ID', 'Uncanny AutomatorWP Integration', 'bp-better-messages'  ),
                            'type' => 'text',
                            'required'  => true,
                            'default' => ''
                        ),
                        'recipient' => array(
                            'name' => _x( 'Recipient', 'Uncanny AutomatorWP Integration', 'bp-better-messages' ),
                            'desc' => _x( 'Accepts User ID', 'Uncanny AutomatorWP Integration', 'bp-better-messages'  ),
                            'type' => 'text',
                            'required'  => true,
                            'default' => ''
                        ),
                        'subject' => array(
                            'name' => _x( 'Subject', 'Uncanny AutomatorWP Integration', 'bp-better-messages' ),
                            'type' => 'text',
                            'required'  => false,
                            'default' => ''
                        ),
                        'unique_tag' => array(
                            'name' => _x( 'Unique Tag', 'Uncanny AutomatorWP Integration', 'bp-better-messages' ),
                            'desc' => _x( 'If you dont want to create many conversations with same user. You need to put unique tag, which will create conversation or send reply to conversation within the unique tag. For example for all system messages you can put "system" tag and they all will be sending to same conversation.', 'Uncanny Automator Integration', 'bp-better-messages' ),
                            'type' => 'text',
                            'required'  => false,
                            'default' => ''
                        ),
                        'content' => array(
                            'name' => _x( 'Content', 'Uncanny AutomatorWP Integration', 'bp-better-messages' ),
                            'type' => 'textarea',
                            'required'  => false,
                            'default' => ''
                        )
                    )
                )
            ),
        ) );

    }

    /**
     * Action execution function
     *
     * @since 1.0.0
     *
     * @param stdClass  $action             The action object
     * @param int       $user_id            The user ID
     * @param array     $action_options     The action's stored options (with tags already passed)
     * @param stdClass  $automation         The action's automation object
     */
    public function execute( $action, $user_id, $action_options, $automation ) {

        $sender_id  = (int) $action_options['sender_id'];
        $recipient  = (int) $action_options['recipient'];
        $subject    = $action_options['subject'];
        $content    = $action_options['content'];
        $unique_tag = trim($action_options['unique_tag']);

        $data = array(
            'sender_id'  => $sender_id,
            'recipients' => $recipient,
            'subject'    => $subject,
            'content'    => $content,
            'return'     => 'wp_error',
        );

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

        $result = Better_Messages()->functions->new_message( $data );

        // If there was an error, it'll be logged in action log with an error message.
        if ( is_wp_error( $result ) ) {
            $error_message = $result->get_error_message();
            // Complete action with errors and log Error message.

        } else {
            $this->thread_id  = $result['thread_id'];
            $this->message_id = $result['message_id'];
        }
    }

    /**
     * Register required hooks
     *
     * @since 1.0.0
     */
    public function hooks() {
        parent::hooks();
    }

}

new AutomatorWP_Better_Messages_Send_Message();
