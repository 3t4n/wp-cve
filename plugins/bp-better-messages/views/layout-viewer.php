<?php
/**
 * Settings page
 */
defined( 'ABSPATH' ) || exit;
global $wpdb;
$messages_table = bm_get_table('messages');

$page = (isset($_GET['cpage'])) ? intval( $_GET['cpage'] ) : 1;
$sender_id = isset($_GET['sender_id']) ? intval($_GET['sender_id']) : false;

$sender_sql = '';
if( $sender_id ) $sender_sql = $wpdb->prepare('AND `sender_id` = %d', $sender_id);

$messages_total = $wpdb->get_var("
SELECT COUNT(*) 
FROM {$messages_table} 
WHERE `date_sent` > '0000-00-00 00:00:00'
AND `message` != '<!-- BBPM START THREAD -->'
$sender_sql
ORDER BY `id` DESC
");

$per_page = 20;
$offset = 0;
if( $page > 1 ){
    $offset = ( $page - 1 ) * $per_page;
}

$messages = $wpdb->get_results("
SELECT * 
FROM {$messages_table} 
WHERE `date_sent` > '0000-00-00 00:00:00'
AND `message` != '<!-- BBPM START THREAD -->'
$sender_sql
ORDER BY `id` DESC
LIMIT {$offset}, {$per_page}
");
?>


<link rel='stylesheet' href='<?php echo Better_Messages()->url; ?>/assets/admin/viewer.css?ver=<?php echo Better_Messages()->version; ?>' media='all' />
<div class="wrap">
    <h1><?php _ex( 'Messages Viewer', 'WP Admin','bp-better-messages' ); ?></h1>

    <div id="messages-viewer"></div>

    <form method="GET" action="">
        <input type="hidden" name="page" value="better-messages-viewer">

        <div style="margin-bottom: 10px; max-width: 300px">
            <label style="display: block;margin-bottom: 5px"><?php _ex( 'Filter by Sender', 'WP Admin','bp-better-messages' ); ?></label>
            <select id="bm-user-selector" name="sender_id" placeholder="<?php _ex( 'Type user name or user id', 'WP Admin','bp-better-messages' ); ?>"></select>
        </div>

        <button type="submit" class="button button-primary"><?php _ex( 'Filter', 'WP Admin','bp-better-messages' ); ?></button>
    </form>
    <table class="bp-messages-list widefat fixed">
        <thead>
            <tr>
                <th><?php _ex( 'Sender', 'WP Admin','bp-better-messages' ); ?></th>
                <th><?php _ex( 'Message', 'WP Admin','bp-better-messages' ); ?></th>
                <th><?php _ex( 'Conversation',  'WP Admin','bp-better-messages' ); ?></th>
                <th><?php _ex( 'Time Sent', 'WP Admin','bp-better-messages' ); ?></th>
            </tr>
        </thead>
        <?php foreach ( $messages as $message ){
            $attachments = Better_Messages()->functions->get_message_meta( $message->id, 'attachments', true );
            ?>
        <tr>
            <td class="user-td"><?php
                $userdata = get_userdata( $message->sender_id );

                if( ! $userdata ){
                    _e( 'Deleted User', 'bp-better-messages' );
                } else {
                    $link = bp_core_get_userlink( $message->sender_id, false, true );

                    echo Better_Messages()->functions->get_avatar($message->sender_id, 20);
                    echo '<a href="' . $link . '" target="_blank">';
                    echo Better_Messages()->functions->get_name($message->sender_id);
                    echo '</a>';
                }
                ?></td>
            <td>
                <?php
                echo $message->message;

                if( is_array($attachments) && count( $attachments ) > 0 ){

                    echo '<div>';
                    printf( _x( 'This message contains %s attachment(s):', 'WP Admin', 'bp-better-messages' ), count( $attachments ) );

                    echo '<ul>';
                    foreach ( $attachments as $id => $attachment ){
                        $attachments = esc_url( $attachment );
                        echo '<li><a target="_blank" href="' . $attachment . '">' . $attachment . '</a></li>';
                    }
                    echo '</ul>';

                    echo '</div>';
                }
                ?>
            </td>
            <td><?php
                $participants = Better_Messages()->functions->get_participants( $message->thread_id );
                _ex( 'Conversation ID:',  'WP Admin','bp-better-messages' );
                echo ' ' . $message->thread_id . '<br>';
                _ex( 'Participants Count:',  'WP Admin','bp-better-messages' );
                echo ' ' . $participants['count'] . '<br>';
                $view_link = Better_Messages()->functions->add_hash_arg('conversation/' . $message->thread_id, [
                    'scrollToContainer' => ''
                ], Better_Messages()->functions->get_link() );

                echo '<a href="' . $view_link . '" target="_blank">';
                _ex( 'View conversation', 'WP Admin', 'bp-better-messages' );
                echo '</a>';
                ?></td>
            <td><?php echo $message->date_sent; ?></td>
        </tr>
        <?php } ?>
    </table>
    <?php
    echo '<div class="pagination">';
    echo paginate_links( array(
        'base' => add_query_arg( 'cpage', '%#%' ),
        'format' => '',
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
        'total' => ceil($messages_total / $per_page),
        'current' => $page,
        'type' => 'list'
    ));
    echo '</div>';
    ?>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($){
        var $select = $('#bm-user-selector').selectize({
            //maxItems: null,
            valueField: 'id',
            labelField: "name",
            searchField: ["id", "name"],
            searchConjunction: "or",
            options: [],
            preload: true,
            create: false,
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '<?php echo Better_Messages()->functions->get_rest_api_url('wp/v2') ?>users',
                    type: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest'); ?>'
                    },
                    data: {
                        search: query,
                    },
                    error: function() {
                        callback();
                    },
                    success: function(res) {
                        callback(res);
                    }
                });
            }
        });

    })
</script>
