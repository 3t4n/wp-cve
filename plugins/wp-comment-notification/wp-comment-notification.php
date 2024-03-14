<?php
/*
Plugin Name: WP Comment Notification
Plugin URI: https://wpexpertshub.com
Description: Manage wordpress Comment Notifications.
Author: WpExperts Hub
Version: 1.4
Author URI: https://wpexpertshub.com
Text Domain: wp-comment-notification
*Requires at least: 5.6
*Tested up to: 6.0
*Requires PHP: 7.2
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if(!class_exists('WP_Comment_Notification')) {
    class WP_Comment_Notification{

        function __construct(){
            add_filter('notify_moderator',array($this,'remove_notifications'),10,2);
            add_action('comment_post',array($this,'init_notifications'),999,3);
            add_action('admin_menu',array($this,'admin_menu'));
            add_action('admin_init',array($this,'wp_comment_notification_settings_init'));
        }

        function admin_menu(){
            add_options_page(__('WP Comment Notification','wp-comment-notification'),__('WP Comment Notification','wp-comment-notification'),'manage_options','wp-comment-notification',array($this,'settings_page'));
        }

        function wp_comment_notification_settings_init(){
            register_setting( 'wp_comment_notification', 'wp_comment_notification_settings' );

            add_settings_section(
                'wp_comment_notification_section',
                '',
                array($this,'wp_comment_notification_settings_section_callback'),
                'wp_comment_notification'
            );

            add_settings_field(
                'wp_comment_notification_emails',
                __( 'Notification email id(s)','wp-comment-notification' ),
                array($this,'wp_comment_notification_textarea_render'),
                'wp_comment_notification',
                'wp_comment_notification_section'
            );

            add_settings_field(
                'wp_comment_notification_author',
                __( 'Notify post author', 'wp-comment-notification' ),
                array($this,'wp_comment_notification_checkbox_render'),
                'wp_comment_notification',
                'wp_comment_notification_section'
            );


        }


        function wp_comment_notification_textarea_render(){
            $wpcn_mails = $this->get_notification_mail_ids();
            ?>
            <textarea cols='60' rows='10' name='wp_comment_notification_settings[wp_comment_notification_emails]'><?php echo $wpcn_mails; ?></textarea>
            <br><span>Add comma separated email ids</span>
            <?php
        }


        function wp_comment_notification_checkbox_render(){
            $options = get_option('wp_comment_notification_settings');
            $checked = isset($options['wp_comment_notification_author']) ? checked($options['wp_comment_notification_author'],1,false) : '';
            echo '<input type="checkbox" name="wp_comment_notification_settings[wp_comment_notification_author]" value="1" "'.$checked.'">';
        }


        function wp_comment_notification_settings_section_callback(){
            echo __('WP Comment Notification Setting','wp-comment-notification');
        }

        function settings_page(){
            ?>
            <form action='options.php' method='post'>
                <h2><?php echo __( 'WP Comment Notification','wp-comment-notification'); ?></h2>
                <?php
                settings_fields( 'wp_comment_notification' );
                do_settings_sections( 'wp_comment_notification' );
                submit_button();
                ?>
            </form>
            <?php
        }

        function get_default_notification_mails(){
            $emails = array( get_option( 'admin_email' ) );
            $blogusers = get_users('role=administrator');
            if(is_array($blogusers) && count($blogusers)>0){
                foreach($blogusers as $admin_user){
                    if ( 0 !== strcasecmp( $admin_user->data->user_email, get_option( 'admin_email' ) ) ){
                        $emails[] = $admin_user->data->user_email;
                    }
                }
            }
            return $emails;
        }

        function get_notification_mail_ids(){
            $options = get_option('wp_comment_notification_settings');
            $wpcn_mails = isset($options['wp_comment_notification_emails']) ? $options['wp_comment_notification_emails'] : implode(',',$this->get_default_notification_mails());
            return $wpcn_mails;
        }


        function remove_notifications($maybe_notify,$comment_ID){
	        $maybe_notify = false;
            return $maybe_notify;
        }

        function init_notifications($comment_id,$comment_approved,$commentdata){

            global $wpdb;
            $comment = get_comment($comment_id);
	        if($comment->comment_approved!='0'){
		        return false;
	        }

	        $post = get_post($comment->comment_post_ID);
            $options = get_option('wp_comment_notification_settings');

            // Add Admin Emails
            $emails = explode(',',$this->get_notification_mail_ids());

            // Add Author Email
            if(isset($options['wp_comment_notification_author']) && $options['wp_comment_notification_author']=='1'){
                $author  = get_userdata($post->post_author);
                if($author){
                    $emails[] = $author->user_email;
                }
            }
            $emails = array_map('trim',$emails);
	        if(is_array($emails) && count($emails)>0){
		        $emails = array_unique($emails);
	        }



	        $switched_locale = switch_to_locale( get_locale() );

	        $comment_author_domain = '';
	        if ( WP_Http::is_ip_address( $comment->comment_author_IP ) ) {
		        $comment_author_domain = gethostbyaddr( $comment->comment_author_IP );
	        }

	        $comments_waiting = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '0'" );

	        // The blogname option is escaped with esc_html() on the way into the database in sanitize_option().
	        // We want to reverse this for the plain text arena of emails.
	        $blogname        = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	        $comment_content = wp_specialchars_decode( $comment->comment_content );

	        switch ( $comment->comment_type ) {
		        case 'trackback':
			        /* translators: %s: Post title. */
			        $notify_message  = sprintf( __( 'A new trackback on the post "%s" is waiting for your approval' ), $post->post_title ) . "\r\n";
			        $notify_message .= get_permalink( $comment->comment_post_ID ) . "\r\n\r\n";
			        /* translators: 1: Trackback/pingback website name, 2: Website IP address, 3: Website hostname. */
			        $notify_message .= sprintf( __( 'Website: %1$s (IP address: %2$s, %3$s)' ), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
			        /* translators: %s: Trackback/pingback/comment author URL. */
			        $notify_message .= sprintf( __( 'URL: %s' ), $comment->comment_author_url ) . "\r\n";
			        $notify_message .= __( 'Trackback excerpt: ' ) . "\r\n" . $comment_content . "\r\n\r\n";
			        break;

		        case 'pingback':
			        /* translators: %s: Post title. */
			        $notify_message  = sprintf( __( 'A new pingback on the post "%s" is waiting for your approval' ), $post->post_title ) . "\r\n";
			        $notify_message .= get_permalink( $comment->comment_post_ID ) . "\r\n\r\n";
			        /* translators: 1: Trackback/pingback website name, 2: Website IP address, 3: Website hostname. */
			        $notify_message .= sprintf( __( 'Website: %1$s (IP address: %2$s, %3$s)' ), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
			        /* translators: %s: Trackback/pingback/comment author URL. */
			        $notify_message .= sprintf( __( 'URL: %s' ), $comment->comment_author_url ) . "\r\n";
			        $notify_message .= __( 'Pingback excerpt: ' ) . "\r\n" . $comment_content . "\r\n\r\n";
			        break;

		        default: // Comments.
			        /* translators: %s: Post title. */
			        $notify_message  = sprintf( __( 'A new comment on the post "%s" is waiting for your approval' ), $post->post_title ) . "\r\n";
			        $notify_message .= get_permalink( $comment->comment_post_ID ) . "\r\n\r\n";
			        /* translators: 1: Comment author's name, 2: Comment author's IP address, 3: Comment author's hostname. */
			        $notify_message .= sprintf( __( 'Author: %1$s (IP address: %2$s, %3$s)' ), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
			        /* translators: %s: Comment author email. */
			        $notify_message .= sprintf( __( 'Email: %s' ), $comment->comment_author_email ) . "\r\n";
			        /* translators: %s: Trackback/pingback/comment author URL. */
			        $notify_message .= sprintf( __( 'URL: %s' ), $comment->comment_author_url ) . "\r\n";

			        if ( $comment->comment_parent ) {
				        /* translators: Comment moderation. %s: Parent comment edit URL. */
				        $notify_message .= sprintf( __( 'In reply to: %s' ), admin_url( "comment.php?action=editcomment&c={$comment->comment_parent}#wpbody-content" ) ) . "\r\n";
			        }

			        /* translators: %s: Comment text. */
			        $notify_message .= sprintf( __( 'Comment: %s' ), "\r\n" . $comment_content ) . "\r\n\r\n";
			        break;
	        }

	        /* translators: Comment moderation. %s: Comment action URL. */
	        $notify_message .= sprintf( __( 'Approve it: %s' ), admin_url( "comment.php?action=approve&c={$comment_id}#wpbody-content" ) ) . "\r\n";

	        if ( EMPTY_TRASH_DAYS ) {
		        /* translators: Comment moderation. %s: Comment action URL. */
		        $notify_message .= sprintf( __( 'Trash it: %s' ), admin_url( "comment.php?action=trash&c={$comment_id}#wpbody-content" ) ) . "\r\n";
	        } else {
		        /* translators: Comment moderation. %s: Comment action URL. */
		        $notify_message .= sprintf( __( 'Delete it: %s' ), admin_url( "comment.php?action=delete&c={$comment_id}#wpbody-content" ) ) . "\r\n";
	        }

	        /* translators: Comment moderation. %s: Comment action URL. */
	        $notify_message .= sprintf( __( 'Spam it: %s' ), admin_url( "comment.php?action=spam&c={$comment_id}#wpbody-content" ) ) . "\r\n";

	        $notify_message .= sprintf(
	                           /* translators: Comment moderation. %s: Number of comments awaiting approval. */
		                           _n(
			                           'Currently %s comment is waiting for approval. Please visit the moderation panel:',
			                           'Currently %s comments are waiting for approval. Please visit the moderation panel:',
			                           $comments_waiting
		                           ),
		                           number_format_i18n( $comments_waiting )
	                           ) . "\r\n";
	        $notify_message .= admin_url( 'edit-comments.php?comment_status=moderated#wpbody-content' ) . "\r\n";

	        /* translators: Comment moderation notification email subject. 1: Site title, 2: Post title. */
	        $subject         = sprintf( __( '[%1$s] Please moderate: "%2$s"' ), $blogname, $post->post_title );
	        $message_headers = '';

	        /**
	         * Filters the list of recipients for comment moderation emails.
	         *
	         * @since 3.7.0
	         *
	         * @param string[] $emails     List of email addresses to notify for comment moderation.
	         * @param int      $comment_id Comment ID.
	         */
	        $emails = apply_filters( 'comment_moderation_recipients', $emails, $comment_id );

	        /**
	         * Filters the comment moderation email text.
	         *
	         * @since 1.5.2
	         *
	         * @param string $notify_message Text of the comment moderation email.
	         * @param int    $comment_id     Comment ID.
	         */
	        $notify_message = apply_filters( 'comment_moderation_text', $notify_message, $comment_id );

	        /**
	         * Filters the comment moderation email subject.
	         *
	         * @since 1.5.2
	         *
	         * @param string $subject    Subject of the comment moderation email.
	         * @param int    $comment_id Comment ID.
	         */
	        $subject = apply_filters( 'comment_moderation_subject', $subject, $comment_id );

	        /**
	         * Filters the comment moderation email headers.
	         *
	         * @since 2.8.0
	         *
	         * @param string $message_headers Headers for the comment moderation email.
	         * @param int    $comment_id      Comment ID.
	         */
	        $message_headers = apply_filters( 'comment_moderation_headers', $message_headers, $comment_id );

	        foreach ( $emails as $email ) {
		        wp_mail( $email, wp_specialchars_decode( $subject ), $notify_message, $message_headers );
	        }

	        if ( $switched_locale ) {
		        restore_previous_locale();
	        }

	        return true;


        }
    }
}
new WP_Comment_Notification();
?>