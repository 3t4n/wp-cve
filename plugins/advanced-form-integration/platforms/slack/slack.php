<?php

add_filter( 'adfoin_action_providers', 'adfoin_slack_actions', 10, 1 );

function adfoin_slack_actions( $actions ) {

    $actions['slack'] = array(
        'title' => __( 'Slack', 'advanced-form-integration' ),
        'tasks' => array(
            'sendmsg'   => __( 'Send Channel Message', 'advanced-form-integration' )
        )
    );

    return $actions;
}

add_action( 'adfoin_action_fields', 'adfoin_slack_action_fields' );

function adfoin_slack_action_fields() {
    ?>
    <script type="text/template" id="slack-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'sendmsg'">
                <th scope="row">
                    <?php esc_attr_e( 'Subscriber Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr class="alternate" v-if="action.task == 'sendmsg'">
                <td>
                    <label for="tablecell">
                        <?php esc_attr_e( 'Instructions', 'advanced-form-integration' ); ?>
                    </label>
                </td>

                <td>
                    <p><a target="_blank" rel="noopener noreferrer" href="https://advancedformintegration.com/docs/receiver-platforms/slack/">Documentation</a></p>
                </td>
            </tr>

            <tr class="alternate" v-if="action.task == 'sendmsg'">
                <td>
                    <label for="tablecell">
                        <?php esc_attr_e( 'Inbound Webhook URL', 'advanced-form-integration' ); ?>
                    </label>
                </td>

                <td>
                    <input type="text" class="regular-text" v-model="fielddata.url" name="fieldData[url]" placeholder="<?php _e( 'Enter URL here', 'advanced-form-integration'); ?>" required="required">
                </td>
            </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
        </table>
    </script>
    <?php
}

add_action( 'adfoin_slack_job_queue', 'adfoin_slack_job_queue', 10, 1 );

function adfoin_slack_job_queue( $data ) {
    adfoin_slack_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Sends data to Slack API
 */
function adfoin_slack_send_data( $record, $posted_data ) {

    $record_data = json_decode( $record["data"], true );

    if( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if( !adfoin_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }

    $data = $record_data["field_data"];
    $task = $record["task"];

    if( $task == "sendmsg" ) {
        $url     = empty( $data["url"] ) ? "" : adfoin_get_parsed_values( $data["url"], $posted_data );
        $message = empty( $data["message"] ) ? "" : adfoin_get_parsed_values( $data["message"], $posted_data );

        if( !$url ) {
            return;
        }

        $data = array(
            'text' => $message
        );

        $args = array(

            'headers' => array(
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode( $data )
        );

        $return = wp_remote_post( $url, $args );

        adfoin_add_to_log( $return, $url, $args, $record );
    }

    return;
}