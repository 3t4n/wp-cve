<?php
defined( 'ABSPATH' ) || exit;

add_action( 'bp_better_messages_activation', 'bp_install_email_templates' );

function bp_install_email_templates()
{
    if ( ! function_exists( 'bp_get_email_post_type' ) ) return;

    $defaults = array(
        'post_status' => 'publish',
        'post_type'   => bp_get_email_post_type(),
    );

    $emails = array(
        'messages-unread-group' => array(
            /* translators: do not remove {} brackets or translate its contents. */
            'post_title'   => __( '[{{{site.name}}}] You have unread messages: {{subject}}', 'bp-better-messages' ),
            /* translators: do not remove {} brackets or translate its contents. */
            'post_content' => __( "You have unread messages: &quot;{{subject}}&quot;\n\n{{{messages.html}}}\n\n<a href=\"{{{thread.url}}}\">Go to the discussion</a> to reply or catch up on the conversation.", 'bp-better-messages' ),
            /* translators: do not remove {} brackets or translate its contents. */
            'post_excerpt' => __( "You have unread messages: \"{{subject}}\"\n\n{{messages.raw}}\n\nGo to the discussion to reply or catch up on the conversation: {{{thread.url}}}", 'bp-better-messages' ),
        )
    );

    $descriptions[ 'messages-unread-group' ] = __( 'A member has unread private messages.', 'bp-better-messages' );

    // Add these emails to the database.
    foreach ( $emails as $id => $email ) {
        $post_args = bp_parse_args( $email, $defaults, 'install_email_' . $id );

        $template = get_page_by_title( $post_args[ 'post_title' ], OBJECT, bp_get_email_post_type() );
        if ( $template ) $post_args[ 'ID' ] = $template->ID;

        $post_id = wp_insert_post( $post_args );

        if ( !$post_id ) {
            continue;
        }

        $tt_ids = wp_set_object_terms( $post_id, $id, bp_get_email_tax_type() );
        foreach ( $tt_ids as $tt_id ) {
            $term = get_term_by( 'term_taxonomy_id', (int)$tt_id, bp_get_email_tax_type() );
            wp_update_term( (int)$term->term_id, bp_get_email_tax_type(), array(
                'description' => $descriptions[ $id ],
            ) );
        }
    }
}

add_action( 'bp_better_messages_activation', 'bm_install_tables' );
function bm_install_tables(){
    require_once("api/db-migrate.php");

    Better_Messages_Rest_Api_DB_Migrate::instance()->install_tables();
    Better_Messages_Rest_Api_DB_Migrate::instance()->migrations();
}

add_action( 'bp_better_messages_deactivation', 'bp_better_messages_unschedule_cron' );

function bp_better_messages_unschedule_cron()
{
	wp_unschedule_event( wp_next_scheduled( 'bp_better_messages_send_notifications' ), 'bp_better_messages_send_notifications' );
	wp_unschedule_event( wp_next_scheduled( 'bp_better_messages_clear_attachments' ), 'bp_better_messages_clear_attachments' );
}


function bp_better_messages_activation()
{
    require_once trailingslashit( dirname(__FILE__) ) . 'api/db-migrate.php';
    require_once trailingslashit( dirname(__FILE__) ) . 'users.php';
    Better_Messages_Rest_Api_DB_Migrate()->install_tables();
    Better_Messages_Rest_Api_DB_Migrate()->migrations();

    do_action( 'bp_better_messages_activation' );
}

function bp_better_messages_deactivation()
{
    do_action( 'bp_better_messages_deactivation' );
}
