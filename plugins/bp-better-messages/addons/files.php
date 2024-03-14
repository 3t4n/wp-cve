<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_Files' ) ):

    class Better_Messages_Files
    {

        public static function instance()
        {
            static $instance = null;

            if ( null === $instance ) {
                $instance = new Better_Messages_Files();
            }

            return $instance;
        }


        public function __construct()
        {
            //add_action( 'wp_ajax_bp_better_messages_deattach_file', array( $this, 'handle_delete' ) );

            /**
             * Modify message before save
             */
            add_filter( 'bp_better_messages_pre_format_message', array( $this, 'nice_files' ), 90, 4 );

            add_action( 'init', array($this, 'register_cleaner') );

            add_action( 'bp_better_messages_clear_attachments', array($this, 'remove_old_attachments') );

            add_action( 'rest_api_init',  array( $this, 'rest_api_init' ) );

            add_filter( 'bp_better_messages_script_variable', array( $this, 'attachments_script_vars' ), 10, 1 );

            add_filter( 'better_messages_rest_message_meta', array( $this, 'files_message_meta'), 10, 4 );

            add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ), 9 );
            add_action( 'better_messages_register_script_dependencies', array( $this, 'load_scripts' ) );
        }

        public $scripts_loaded = false;
        public function load_scripts(){
            if( $this->scripts_loaded ) return;

            $this->scripts_loaded = true;

            wp_register_script(
                'better-messages-uppy',
                Better_Messages()->url . 'assets/js/uppy.min.js',
                [],
                '2.3.18b'
            );

            add_filter('better_messages_script_dependencies', function( $deps ) {
                $deps[] = 'better-messages-uppy';
                return $deps;
            } );
        }

        public function files_message_meta( $meta, $message_id, $thread_id, $content ){
            $attachments = Better_Messages()->functions->get_message_meta( $message_id, 'attachments', true );

            $files = [];

            if( is_array( $attachments) && count( $attachments ) > 0 ){
                foreach ( $attachments as $attachment_id => $url ) {
                    $attachment = get_post( $attachment_id );
                    if( ! $attachment ) continue;

                    $url = apply_filters('better_messages_attachment_url', $url, $attachment_id, $message_id, $thread_id );

                    $file = [
                        'id'       => $attachment->ID,
                        'thumb'    => wp_get_attachment_image_url($attachment->ID, array(200, 200)),
                        'url'      => $url,
                        'mimeType' => $attachment->post_mime_type
                    ];

                    $path = get_attached_file( $attachment_id );
                    $size = filesize($path);
                    $ext = pathinfo( $url, PATHINFO_EXTENSION );
                    $name = get_post_meta($attachment_id, 'bp-better-messages-original-name', true);
                    if( empty($name) ) $name = wp_basename( $url );

                    $file['name']  = $name;
                    $file['size'] = $size;
                    $file['ext']  = $ext;

                    $files[] = $file;
                }
            }

            if( count( $files ) > 0 ){
                $meta['files'] = $files;
            }

            return $meta;
        }

        public function attachments_script_vars( $vars ){
            if ( Better_Messages()->settings['attachmentsEnable'] !== '1' ) return $vars;

            $attachments = [
                'maxSize'    => intval(Better_Messages()->settings['attachmentsMaxSize']),
                'maxItems'   => intval(Better_Messages()->settings['attachmentsMaxNumber']),
                'formats'    => array_map(function ($str) { return ".$str"; }, Better_Messages()->settings['attachmentsFormats']),
                'allowPhoto' => (int) ( Better_Messages()->settings['attachmentsAllowPhoto'] == '1' ? '1' : '0' )
            ];

            $vars['attachments'] = $attachments;

            return $vars;
        }

        public function rest_api_init(){
            register_rest_route('better-messages/v1', '/thread/(?P<id>\d+)/upload/(?P<message_id>\d+)', array(
                'methods' => 'POST',
                'callback' => array( $this, 'handle_upload' ),
                'permission_callback' => array(Better_Messages_Rest_Api(), 'can_reply')
            ));

            register_rest_route('better-messages/v1', '/thread/(?P<id>\d+)/upload/(?P<message_id>\d+)/unsuccessful', array(
                'methods' => 'POST',
                'callback' => array( $this, 'process_unsuccessful' ),
                'permission_callback' => array(Better_Messages_Rest_Api(), 'can_reply')
            ));
        }

        public function process_unsuccessful( WP_REST_Request $request ){
            $user_id    = Better_Messages()->functions->get_current_user_id();

            $thread_id  = intval($request->get_param('id'));
            $message_id = intval($request->get_param('message_id'));

            $message = Better_Messages()->functions->get_message( $message_id );

            if( $thread_id !== (int) $message->thread_id ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Conversation mismatch while trying to upload file', 'File Uploader Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            if( $user_id !== (int) $message->sender_id ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to upload files for this message', 'File Uploader Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            return Better_Messages()->functions->delete_message( $message_id );
        }

        public function register_cleaner()
        {
            if ( ! wp_next_scheduled( 'bp_better_messages_clear_attachments' ) ) {
                wp_schedule_event( time(), 'fifteen_minutes', 'bp_better_messages_clear_attachments' );
            }
        }

        public function remove_old_attachments(){
            $delete_after_days = (int) Better_Messages()->settings['attachmentsRetention'];
            if( $delete_after_days < 1 ) {
                return;
            }

            $delete_after = $delete_after_days * 24 * 60 * 60;
            $delete_after_time = time() - $delete_after;

            global $wpdb;

            $sql = $wpdb->prepare("SELECT {$wpdb->posts}.ID
            FROM {$wpdb->posts}
            INNER JOIN {$wpdb->postmeta}
            ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )
            INNER JOIN {$wpdb->postmeta} AS mt1
            ON ( {$wpdb->posts}.ID = mt1.post_id )
            WHERE 1=1
            AND ( ( {$wpdb->postmeta}.meta_key = 'bp-better-messages-attachment'
            AND {$wpdb->postmeta}.meta_value = '1' )
            AND ( mt1.meta_key = 'bp-better-messages-upload-time'
            AND mt1.meta_value < %d ) )
            AND {$wpdb->posts}.post_type = 'attachment'
            AND (({$wpdb->posts}.post_status = 'inherit'))
            GROUP BY {$wpdb->posts}.ID
            ORDER BY {$wpdb->posts}.post_date DESC
            LIMIT 0, 50", $delete_after_time);

            $old_attachments = $wpdb->get_col( $sql );

            foreach($old_attachments as $attachment){
                $this->remove_attachment($attachment);
            }
        }

        public function remove_attachment($attachment_id){
            global $wpdb;
            $message_id = get_post_meta($attachment_id, 'bp-better-messages-message-id', true);
            if( ! $message_id ) return false;

            // Get Message
            $table = bm_get_table('messages');
            $message_attachments = Better_Messages()->functions->get_message_meta($message_id, 'attachments', true);

            wp_delete_attachment($attachment_id, true);

            /**
             * Deleting attachment from message
             */
            if( isset( $message_attachments[$attachment_id] ) ) {
                $message = $wpdb->get_row( $wpdb->prepare("SELECT * FROM `{$table}` WHERE `id` = %d", $message_id) );

                if( ! $message ){
                    Better_Messages()->functions->delete_all_message_meta($message_id);
                    return true;
                }

                $content = str_replace($message_attachments[$attachment_id], '', $message->content);

                if( empty( trim( $content ) ) ){
                    Better_Messages()->functions->delete_all_message_meta($message_id);
                    $wpdb->delete($table, array('id' => $message_id));
                } else {
                    unset($message_attachments[$attachment_id]);
                    Better_Messages()->functions->update_message_meta($message_id, 'attachments', $message_attachments);
                    $wpdb->update($table, array('content' => $content), array('id' => $message_id));
                }
            }

            return true;

        }

        public function nice_files( $message, $message_id, $context, $user_id )
        {
            if( $context === 'email'  ) {

                if( class_exists('Better_Messages_Voice_Messages') ){
                    $is_voice_message = Better_Messages()->functions->get_message_meta( $message_id, 'bpbm_voice_messages', true );

                    if ( ! empty($is_voice_message) ) {
                        return __('Voice Message', 'bp-better-messages');
                    }
                }
            }

            $attachments = Better_Messages()->functions->get_message_meta( $message_id, 'attachments', true );

            $desc = false;
            if( is_array($attachments) ) {
                if (count($attachments) > 0) {
                    $desc = "<i class=\"fas fa-file\"></i> " . count($attachments) . " " . __('attachments', 'bp-better-messages');
                }
            }

            if ( $context !== 'stack' ) {
                if( $desc !== false ){
                    foreach ( $attachments as $attachment ){
                        $message = str_replace($attachment, '', $message);
                    }

                    if( ! empty( trim($message) ) ){
                        $message .= "";
                    }

                    $message .= $desc;
                }

                return $message;
            }

            if ( !empty( $attachments ) ) {
                foreach ( $attachments as $attachment_id => $url ) {
                    $message = str_replace( array( $url . "\n", "" . $url, $url ), '', $message );
                }

            }

            return $message;
        }

        public function get_archive_extensions(){
            return array(
                "7z",
                "a",
                "apk",
                "ar",
                "cab",
                "cpio",
                "deb",
                "dmg",
                "egg",
                "epub",
                "iso",
                "jar",
                "mar",
                "pea",
                "rar",
                "s7z",
                "shar",
                "tar",
                "tbz2",
                "tgz",
                "tlz",
                "war",
                "whl",
                "xpi",
                "zip",
                "zipx"
            );
        }

        public function get_text_extensions(){
            return array(
                "txt", "rtf"
            );
        }

        public function random_string($length) {
            $key = '';
            $keys = array_merge(range(0, 9), range('a', 'z'));

            for ($i = 0; $i < $length; $i++) {
                $key .= $keys[array_rand($keys)];
            }

            return $key;
        }

        public function handle_delete()
        {
            $user_id       = (int) Better_Messages()->functions->get_current_user_id();
            $attachment_id = intval( $_POST[ 'file_id' ] );
            $thread_id     = intval( $_POST[ 'thread_id' ] );
            $attachment    = get_post( $attachment_id );

            $has_access = Better_Messages()->functions->check_access( $thread_id, $user_id );

            if( $thread_id === 0 ){
                $has_access = true;
            }
            // Security verify 1
            if ( ( ! $has_access && ! current_user_can('manage_options') ) ||
                ! wp_verify_nonce( $_POST[ 'nonce' ], 'file-delete-' . $thread_id ) ||
                ( (int) $attachment->post_author !== $user_id ) || ! $attachment
            ) {
                wp_send_json( false );
                exit;
            }

            // Security verify 2
            if ( (int) get_post_meta( $attachment->ID, 'bp-better-messages-thread-id', true ) !== $thread_id ) {
                wp_send_json( false );
                exit;
            }

            // Looks like we can delete it now!
            $result = wp_delete_attachment( $attachment->ID, true );
            if ( $result ) {
                wp_send_json( true );
            } else {
                wp_send_json( false );
            }

            exit;
        }

        public function upload_dir($dir){
            $dirName = apply_filters('bp_better_messages_upload_dir_name', 'bp-better-messages');

            return array(
                    'path'   => $dir['basedir'] . '/' . $dirName,
                    'url'    => $dir['baseurl'] . '/' . $dirName,
                    'subdir' => '/' . $dirName
                ) + $dir;
        }

        public function upload_mimes($mimes, $user){
            $allowedExtensions = Better_Messages()->settings['attachmentsFormats'];
            $allowed = array();


            foreach(wp_get_mime_types() as $extensions => $mime_type){
                $key = array();

                foreach(explode('|', $extensions) as $ext){
                    if( in_array($ext, $allowedExtensions) ) $key[] = $ext;
                }

                if( ! empty($key) ){
                    $key = implode('|', $key);
                    $allowed[$key] = $mime_type;

                    if( str_contains( $key, 'jpg' ) || str_contains( $key, 'jpe' ) ){
                        $allowed['webp'] = 'image/webp';
                    }
                }
            }

            return $allowed;
        }

        public function handle_upload( WP_REST_Request $request )
        {
            add_filter( 'upload_dir', array( $this, 'upload_dir' ) );
            add_filter( 'upload_mimes', array( $this, 'upload_mimes' ), 10, 2 );

            $user_id    = Better_Messages()->functions->get_current_user_id();
            $thread_id  = intval($request->get_param('id'));
            $message_id = intval($request->get_param('message_id'));

            $message = Better_Messages()->functions->get_message( $message_id );

            if( $thread_id !== (int) $message->thread_id ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Conversation mismatch while trying to upload file', 'File Uploader Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            if( $user_id !== (int) $message->sender_id ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to upload files for this message', 'File Uploader Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $result = array(
                'result' => false,
                'error'  => ''
            );

            $files = $request->get_file_params();

            if ( isset( $files['file']) && ! empty( $files[ 'file' ] ) ) {

                $file = $files['file'];

                // The nonce was valid and the user has the capabilities, it is safe to continue.

                $can_upload = $this->user_can_upload( Better_Messages()->functions->get_current_user_id(), $thread_id );

                if ( ! $can_upload ) {
                    return new WP_Error(
                        'rest_forbidden',
                        _x( 'Sorry, you are not allowed to upload files', 'File Uploader Error', 'bp-better-messages' ),
                        array( 'status' => rest_authorization_required_code() )
                    );
                }

                $extensions = apply_filters( 'bp_better_messages_attachment_allowed_extensions', Better_Messages()->settings['attachmentsFormats'], $thread_id, $user_id );

                $file_type = explode( '/', $file['type'] );

                if( count( $file_type ) !== 2 ){
                    return new WP_Error(
                        'rest_forbidden',
                        _x( 'Sorry, you are not allowed to upload this file type', 'File Uploader Error', 'bp-better-messages' ),
                        array( 'status' => rest_authorization_required_code() )
                    );
                }

                $ext = $file_type[1];
                $name = wp_basename($file['name']);

                if( Better_Messages()->settings['attachmentsRandomName'] === '1'){
                    $_FILES['file']['name'] = Better_Messages()->functions->random_string(20) . '.' . $ext;
                }

                if( ! in_array( $ext, $extensions ) ){
                    return new WP_Error(
                        'rest_forbidden',
                        _x( 'Sorry, you are not allowed to upload this file type', 'File Uploader Error', 'bp-better-messages' ),
                        array( 'status' => rest_authorization_required_code() )
                    );
                }

                $maxSizeMb = apply_filters( 'bp_better_messages_attachment_max_size', Better_Messages()->settings['attachmentsMaxSize'], $thread_id, $user_id );
                $maxSize = $maxSizeMb * 1024 * 1024;

                if($file['size'] > $maxSize){
                    $result[ 'error' ] = sprintf(_x( '%s is too large! Please upload file up to %d MB.', 'File Uploader Error', 'bp-better-messages' ), $file['name'], $maxSizeMb);
                    status_header( 403 );
                    wp_send_json( $result );
                }
                // These files need to be included as dependencies when on the front end.
                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
                require_once( ABSPATH . 'wp-admin/includes/media.php' );

                add_filter( 'intermediate_image_sizes', '__return_empty_array' );
                $attachment_id = media_handle_upload( 'file', 0 );
                remove_filter( 'intermediate_image_sizes', '__return_empty_array' );


                if ( is_wp_error( $attachment_id ) ) {
                    // There was an error uploading the image.
                    status_header( 400 );
                    $result[ 'error' ] = $attachment_id->get_error_message();
                } else {
                    // The image was uploaded successfully!
                    add_post_meta( $attachment_id, 'bp-better-messages-attachment', true, true );
                    add_post_meta( $attachment_id, 'bp-better-messages-thread-id', $thread_id, true );
                    add_post_meta( $attachment_id, 'bp-better-messages-upload-time', time(), true );
                    add_post_meta( $attachment_id, 'bp-better-messages-original-name', $name, true );
                    add_post_meta( $attachment_id, 'bp-better-messages-message-id', $message_id, true );

                    $attachment_meta = Better_Messages()->functions->get_message_meta( $message_id, 'attachments', true );

                    if( ! $attachment_meta ){
                        $attachment_meta = array();
                    }

                    $attachment_meta[ $attachment_id ] = wp_get_attachment_url( $attachment_id );
                    Better_Messages()->functions->update_message_meta( $message_id, 'attachments', $attachment_meta );

                    Better_Messages()->functions->update_message([
                        'message_id'  => $message->id,
                        'thread_id'   => $message->thread_id,
                        'content'     => $message->message,
                        'mobile_push' => true,
                    ]);

                    status_header( 200 );

                    do_action('better_messages_user_file_uploaded', $attachment_id, $message_id, $thread_id );

                    $result[ 'result' ] = $attachment_id;
                    $result['update'] = Better_Messages_Rest_Api()->get_messages( $thread_id, [$message->id] );
                }
            } else {
                status_header( 406 );
                $result[ 'error' ] = _x( 'Your request is empty.', 'File Uploader Error', 'bp-better-messages' );
            }

            remove_filter( 'upload_dir', array( $this, 'upload_dir' ) );
            remove_filter( 'upload_mimes', array( $this, 'upload_mimes' ), 10 );

            if( $result['error'] ){
                return new WP_Error(
                    'rest_upload_failed',
                    $result['error'],
                    array( 'status' => 403 )
                );
            }

            return $result;
        }

        public function user_can_upload( $user_id, $thread_id )
        {
            if ( Better_Messages()->settings['attachmentsEnable'] !== '1' ) return false;

            if( $thread_id === 0 ) return true;
            return apply_filters( 'bp_better_messages_user_can_upload_files', Better_Messages()->functions->check_access( $thread_id, $user_id ), $user_id, $thread_id );
        }

    }

endif;


function Better_Messages_Files()
{
    return Better_Messages_Files::instance();
}
