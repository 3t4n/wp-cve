<?php
global $wpdb;

$log              = new Advanced_Form_Integration_Log();
$result           = $log->get_row( "SELECT * FROM {$log->table} WHERE id = {$id}" );
$integration_id   = isset( $result['integration_id'] ) && $result['integration_id'] ? $result['integration_id'] : '';
$request_data     = isset( $result['request_data'] ) && $result['request_data'] ? json_decode( $result['request_data'], true ) : '';
$response_code    = isset( $result['response_code'] ) && $result['response_code'] ? $result['response_code'] : '';
$response_data    = isset( $result['response_data'] ) && $result['response_data'] ? json_decode( $result['response_data'], true ) : '';
$response_message = isset( $result['response_message'] ) && $result['response_message'] ? $result['response_message'] : '';
$time             = isset( $result['time'] ) && $result['time'] ? $result['time'] : '';
$nonce            = wp_create_nonce( 'adfoin-resend-log' );
$full_log = array(
    'integration_id'   => $integration_id,
    'response_code'    => $response_code,
    'response_message' => $response_message,
    'request_data'     => $request_data,
    'response_data'    => $response_data,
    'time'             => $time
);
?>

<div class="wrap">

    <div id="icon-options-general" class="icon32">  </div>
    <h1> <?php esc_attr_e( 'Log', 'advanced-form-integration' ); ?>
        <a href="<?php echo admin_url( 'admin.php?page=advanced-form-integration-log' ); ?>" class="page-title-action"><?php _e( 'Back', 'advanced-form-integration' ); ?></a>
        <a href="" class="page-title-action button-copy-full-log"><?php _e( 'Copy Full Log', 'advanced-form-integration' ); ?></a>
    </h1>


    <div>

        <div id="post-body" class="metabox-holder ">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"> <?php _e( 'Time', 'advanced-form-integration' ); ?></th>
                    <td>
                        <p>
                            <?php echo esc_attr( $time ); ?>
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php _e( 'Integration ID', 'advanced-form-integration' ); ?></th>
                    <td>
                        <p>
                            <?php echo esc_attr( $integration_id ); ?>
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php _e( 'Request Data', 'advanced-form-integration' ); ?></th>
                    <td>
                        <!-- <div class="log-edit-form" style="display:none;"> -->
                            <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="resend-log-data">
                                <input type="hidden" name="action" value="adfoin_resend_log_data">
                                <input type="hidden" name="log_id" value="<?php echo $id; ?>">
                                <input type="hidden" name="integration_id" value="<?php echo $integration_id; ?>">
                                <input type="hidden" name="_wpnonce" value="<?php echo $nonce; ?>" />
                                <textarea id="adfoin-log-request-data" name="request-data"></textarea>
                                <input style="margin-top:5px;" class="button-primary" type="submit" name="resend_log" value="<?php esc_attr_e( 'Resend', 'advanced-form-integration' ); ?>" />
                            </form>
                        <!-- </div> -->
                        
                        <!-- <div style="background:#fff;border: 1px solid #e5e5e5;box-shadow: 0 1px 1px rgb(0 0 0 / 4%);padding: 5px 20px;">
                            <pre id="request-data" style="font-family: monospace;white-space: pre-wrap;word-wrap: break-word;"></pre>
                        </div> -->
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php _e( 'Response Code', 'advanced-form-integration' ); ?></th>
                    <td>
                        <p>
                            <?php echo stripslashes( $response_code ); ?>
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php _e( 'Response Data', 'advanced-form-integration' ); ?></th>
                    <td>
                        <div>
                            <pre id="response-data"></pre>
                        </div>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php _e( 'Response Message', 'advanced-form-integration' ); ?></th>
                    <td>
                        <p>
                            <?php echo stripslashes( $response_message ); ?>
                        </p>
                    </td>
                </tr>
            </table>

        </div>
        <!-- #post-body .metabox-holder .columns-2 -->

        <br class="clear">
    </div>
    <!-- #poststuff -->

</div> <!-- .wrap -->

<script>
    var requestData  = <?php echo json_encode( $request_data, true ); ?>;
    var responseData = <?php echo json_encode( $response_data, true ); ?>;
    var fullLog = <?php echo json_encode( $full_log, true ); ?>;

    // document.getElementById("request-data").textContent = JSON.stringify(requestData, undefined, 2);
    document.getElementById("response-data").textContent = JSON.stringify(responseData, undefined, 2);

    document.getElementById("adfoin-log-request-data").textContent = JSON.stringify(requestData, undefined, 2);

    jQuery(document).ready(function($) {
        wp.codeEditor.initialize($('#adfoin-log-request-data'), adfoin);

        $('.button-copy-full-log').on( 'click', function(e) {
            e.preventDefault();
            var $this = $(this);
            $this.text( 'Copying...');
            navigator.clipboard.writeText(JSON.stringify(fullLog));

            setTimeout(function() {
                $this.text('Copied to Clipboard');
            }, 1000);
        });

        $('.adfoin-log-edit').on('click', function(){
            $('.log-edit-form').show();
        });
    });
</script>