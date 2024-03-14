<?php
defined( 'ABSPATH' ) || exit;

class Better_Messages_Shortcodes
{

    public static function instance()
    {

        // Store the instance locally to avoid private static replication
        static $instance = null;

        // Only run these methods if they haven't been run previously
        if ( null === $instance ) {
            $instance = new Better_Messages_Shortcodes;
            $instance->setup_actions();
        }

        // Always return the instance
        return $instance;

        // The last metroid is in captivity. The galaxy is at peace.
    }

    public function setup_actions(){
        add_shortcode( 'bp_better_messages_unread_counter', array( $this, 'unread_counter_shortcode' ) );
        add_shortcode( 'better_messages_unread_counter', array( $this, 'unread_counter_shortcode' ) );

        add_shortcode( 'bp_better_messages_my_messages_url', array( $this, 'bp_better_messages_url' ) );
        add_shortcode( 'better_messages_my_messages_url', array( $this, 'bp_better_messages_url' ) );

        add_shortcode( 'bp_better_messages_pm_button', array( $this, 'bp_better_messages_pm_button' ) );
        add_shortcode( 'better_messages_pm_button', array( $this, 'bp_better_messages_pm_button' ) );

        add_shortcode( 'bp_better_messages', array( $this, 'bp_better_messages' ) );
        add_shortcode( 'better_messages', array( $this, 'bp_better_messages' ) );

        /**
         * Premium buttons
         */
        add_shortcode( 'bp_better_messages_mini_chat_button',  array( $this, 'bp_better_messages_mini_chat_button' ) );
        add_shortcode( 'better_messages_mini_chat_button',  array( $this, 'bp_better_messages_mini_chat_button' ) );

        add_shortcode( 'bp_better_messages_video_call_button', array( $this, 'bp_better_messages_video_call_button' ) );
        add_shortcode( 'better_messages_video_call_button', array( $this, 'bp_better_messages_video_call_button' ) );

        add_shortcode( 'bp_better_messages_audio_call_button', array( $this, 'bp_better_messages_audio_call_button' ) );
        add_shortcode( 'better_messages_audio_call_button', array( $this, 'bp_better_messages_audio_call_button' ) );

        add_shortcode( 'better_messages_single_conversation', array( $this, 'better_messages_single_conversation' ) );

        add_shortcode( 'better_messages_live_chat_button',  array( $this, 'better_messages_live_chat_button' ) );
    }

    function esc_brackets($text = ''){
        return str_replace( [ "[" , "]" ] , [ "&#91;" , "&#93;" ] , $text );
    }

    public function better_messages_live_chat_button($args){
        $class  = 'bm-lc-button';
        $attrs  = '';
        $alt    = '';
        $text   = __('Live Chat', 'bp-better-messages');
        $type   = 'link';

        if( isset( $args['class'] ) ) {
            $class .= ' ' . $args['class'];
        }

        if( isset( $args['type']) && $args['type'] === 'button' ){
            $type = 'button';
        }

        if( isset( $args['text'] ) ) {
            $text = $args['text'];
        }

        if( isset( $args['alt'] ) ) {
            $alt = $args['alt'];
        }

        if( isset( $args['subject'] ) ) {
            $attrs .= ' data-subject="' . $args['subject'] . '"';
        }

        if( isset( $args['target'] ) ) {
            $attrs .= ' target="' . esc_attr( $args['target'] ) . '"';
        }

        if( isset( $args['unique_tag'] ) ) {
            $attrs .= ' data-bm-unique-key="' . urlencode($args['unique_tag']) . '"';
        }

        if( isset( $args['object_id'] ) ) {
            $attrs .= ' data-bm-object-id="' . intval($args['object_id']) . '"';
        }

        if( $alt !== '' ){
            $attrs .= ' title="' . esc_html( $alt ) . '"';
        }

        if( isset( $args['user_id'] ) ) {
            $user_id = (int) $args['user_id'];
        } else {
            $user_id = (int) Better_Messages()->functions->get_member_id();
        }

        if( Better_Messages()->functions->get_current_user_id() === $user_id ){
            $class .= ' bm-self-button';
        }

        $link = '#';

        if( ! is_user_logged_in() ) {
            $link = Better_Messages()->functions->get_link(Better_Messages()->functions->get_current_user_id());
            if( ! Better_Messages()->guests->guest_access_enabled() ){
                $attrs .= ' onclick="event.preventDefault(); location.href = \'' . $link . '\'; "';
            }
        }

        Better_Messages()->enqueue_css();

        if( $type === 'button'){
            return '<button class="' . esc_attr($class) . '" data-user-id="' . $user_id . '" ' . $attrs . '><span class="bm-button-text">' . wp_kses($text, ['i' => [ 'class' => [] ]]) . '</span></button>';
        } else {
            return '<a href="' . esc_url($link) .  '" class="' . esc_attr($class) . '" data-user-id="' . $user_id . '" ' . $attrs . '><span class="bm-button-text">' . wp_kses($text, ['i' => [ 'class' => [] ]]) . '</span></a>';
        }
    }

    public function better_messages_single_conversation( $args ){
        $thread_id = intval( $args['thread_id'] );
        $thread = Better_Messages()->functions->get_thread( $thread_id );

        if( $thread ) {
            return Better_Messages()->functions->get_conversation_layout($thread_id);
        } else {
            return '<p>' . __('Conversation not exists', 'bp-better-messages') .  '</p>';
        }
    }

    public function bp_better_messages(){
        ob_start();

        if( ! is_user_logged_in() && ! Better_Messages()->guests->guest_access_enabled() ){
            echo Better_Messages()->functions->render_login_form();
        } else {
            if (function_exists('bp_is_user') && bp_is_user()) {
                echo Better_Messages()->functions->get_page();
            } else {
                echo Better_Messages()->functions->get_page(true);
            }
        }

        return ob_get_clean();
    }

    public function bp_better_messages_pm_button( $args ){
        $class   = 'bpbm-pm-button';
        $target  = '';
        $text    = __('Private Message', 'bp-better-messages');
        $subject = '';
        $message = '';
        $fast    = true;
        $return_url = false;

        if( isset( $args['class'] ) ) {
            $class .= ' ' . $args['class'];
        }

        if( isset( $args['target'] ) ) {
            $target .= ' target="' .  esc_attr( $args['target'] ) . '"';
        }

        if( isset( $args['text'] ) ) {
            $text = $args['text'];
        }

        if( isset( $args['subject'] ) ) {
            $subject = urlencode($args['subject']);
        }

        if( isset( $args['message'] ) ) {
            $message = urlencode($args['message']);
        }

        if( isset( $args['fast_start'] ) && $args['fast_start'] === '0' ) {
            $fast = false;
        }

        if( isset( $args['url_only'] ) && $args['url_only'] === '1' ) {
            $return_url = true;
        }

        if( isset( $args['user_id'] ) ) {
            $user_id = (int) $args['user_id'];
        } else {
            $user_id = (int) Better_Messages()->functions->get_member_id();
        }

        if( ! Better_Messages()->functions->is_user_exists( $user_id ) ) return '';

        if( $user_id === Better_Messages()->functions->get_current_user_id() ) $class .= ' bm-self-button';

        $args = [
            'to' => $user_id
        ];

        $base_url = Better_Messages()->functions->get_link( Better_Messages()->functions->get_current_user_id() );

        if( Better_Messages()->settings['fastStart'] == '1' && $fast ){
            $args['bm-fast-start'] = '1';
            $class .= ' bm-fast-start';
        }

        if( ! empty( $subject ) ){
            $args['subject'] = $subject;
        }

        if( ! empty( $message ) ){
            $args['message'] = $message;
        }

        $attributes = '';

        if( isset( $args['subject'] ) ) {
            $attributes .= ' data-bm-subject="' . $args['subject'] . '"';
        }

        if( isset( $args['unique_tag'] ) ) {
            $attributes .= ' data-bm-unique-key="' . urlencode($args['unique_tag']) . '"';
        }

        if( isset($args['bm-fast-start']) && $args['bm-fast-start'] ){
            $link = add_query_arg( $args, $base_url );
        } else {
            $link = Better_Messages()->functions->add_hash_arg('new-conversation', $args, $base_url);
        }

        if( $return_url ) {
            return $link;
        }

        Better_Messages()->enqueue_css();

        return '<a href="' . esc_url($link) .  '" class="' . esc_attr($class) . '"' . $target . ' data-user-id="' . $user_id. '"' . $attributes . '><span class="bm-button-text">' . esc_attr($text) . '</span></a>';
    }

    public function bp_better_messages_video_call_button( $args ){
        $class   = 'bpbm-pm-button video-call';
        $target  = '';
        $text    = __('Video Call', 'bp-better-messages');
        $return_url = false;

        if( isset( $args['class'] ) ) {
            $class .= ' ' . $args['class'];
        }

        if( isset( $args['target'] ) ) {
            $target .= ' target="' . esc_attr( $args['target'] ) . '"';
        }

        if( isset( $args['text'] ) ) {
            $text = $args['text'];
        }

        if( isset( $args['url_only'] ) && $args['url_only'] === '1' ) {
            $return_url = true;
        }

        if( isset( $args['user_id'] ) ) {
            $user_id = (int) $args['user_id'];
        } else {
            $user_id = (int) Better_Messages()->functions->get_member_id();
        }

        if( $user_id === Better_Messages()->functions->get_current_user_id() ) $class .= ' bm-self-button';

        if( ! Better_Messages()->functions->is_user_exists( $user_id ) ) return '';

        $args = [
            'fast-call' => '',
            'to' => $user_id,
            'type' => 'video'
        ];

        $base_url = Better_Messages()->functions->get_link( Better_Messages()->functions->get_current_user_id() );

        $link = add_query_arg( $args, $base_url );

        if( $return_url ) {
            return $link;
        }

        Better_Messages()->enqueue_css();

        return '<a href="' . esc_url($link) .  '" class="' . esc_attr($class) . '" data-user-id="' . $user_id . '"><span class="bm-button-text">' . esc_attr($text) . '</span></a>';
    }

    public function bp_better_messages_audio_call_button( $args ){
        $class   = 'bpbm-pm-button audio-call';
        $text    = __('Audio Call', 'bp-better-messages');
        $return_url = false;

        if( isset( $args['class'] ) ) {
            $class .= ' ' . $args['class'];
        }

        if( isset( $args['text'] ) ) {
            $text = $args['text'];
        }

        if( isset( $args['url_only'] ) && $args['url_only'] === '1' ) {
            $return_url = true;
        }

        if( isset( $args['user_id'] ) ) {
            $user_id = (int) $args['user_id'];
        } else {
            $user_id = (int) Better_Messages()->functions->get_member_id();
        }

        if( $user_id === Better_Messages()->functions->get_current_user_id() ) $class .= ' bm-self-button';

        if( ! Better_Messages()->functions->is_user_exists( $user_id ) ) return '';

        $args = [
            'fast-call' => '',
            'to' => $user_id,
            'type' => 'audio'
        ];

        $base_url = Better_Messages()->functions->get_link(Better_Messages()->functions->get_current_user_id());
        $link = add_query_arg( $args, $base_url );

        if( $return_url ) {
            return $link;
        }

        Better_Messages()->enqueue_css();

        return '<a href="' . esc_url($link) .  '" class="' . esc_attr($class) . '" data-user-id="' . $user_id . '"><span class="bm-button-text">' . esc_attr($text) . '</span></a>';
    }

    public function bp_better_messages_mini_chat_button( $args ){
        if (Better_Messages()->settings['miniChatsEnable'] !== '1') {
            return '';
        }

        $class   = 'bpbm-pm-button open-mini-chat';
        $text    = __('Private Message', 'bp-better-messages');

        if( isset( $args['class'] ) ) {
            $class .= ' ' . $args['class'];
        }

        if( isset( $args['text'] ) ) {
            $text = $args['text'];
        }

        if( isset( $args['user_id'] ) ) {
            $user_id = (int) $args['user_id'];
        } else {
            $user_id = (int) Better_Messages()->functions->get_member_id();
        }

        if( $user_id === Better_Messages()->functions->get_current_user_id() ) $class .= ' bm-self-button';

        if( ! Better_Messages()->functions->is_user_exists( $user_id ) ) return '';

        $attributes = '';

        if( isset( $args['subject'] ) ) {
            $attributes .= ' data-bm-subject="' . urlencode($args['subject']) . '"';
        }

        if( isset( $args['unique_tag'] ) ) {
            $attributes .= ' data-bm-unique-key="' . urlencode($args['unique_tag']) . '"';
        }

        $link = '#';

        if( ! is_user_logged_in() ) {
            $link = Better_Messages()->functions->get_link(Better_Messages()->functions->get_current_user_id());
        }

        Better_Messages()->enqueue_css();

        return '<a href="' . esc_url($link) .  '" class="' . esc_attr($class) . '" data-user-id="' . $user_id . '" '. $attributes. '><span class="bm-button-text">' . esc_attr($text) . '</span></a>';
    }

    public function bp_better_messages_url(){
        if( ! is_user_logged_in() ){
            return '';
        }

        return Better_Messages()->functions->get_link( Better_Messages()->functions->get_current_user_id() );
    }

    function unread_counter_shortcode( $args ) {
        if( ! is_user_logged_in() ){
            return '';
        }

        $hide_when_no_messages = false;
        $preserve_space = false;
        if( isset( $args['hide_when_no_messages'] ) && $args['hide_when_no_messages'] === '1' ) {
            $hide_when_no_messages = true;
        }

        if( isset( $args['preserve_space'] ) && $args['preserve_space'] === '1' ) {
            $preserve_space = true;
        }

        $classes = ['bp-better-messages-unread', 'bpbmuc'];
        if( $hide_when_no_messages ){
            $classes[] = 'bpbmuc-hide-when-null';
        }

        if( $preserve_space ){
            $classes[] = 'bpbmuc-preserve-space';
        }

        $class = implode(' ', $classes );
        if( Better_Messages()->settings['mechanism'] !== 'websocket'){
            $unread = Better_Messages()->functions->get_total_threads_for_user( Better_Messages()->functions->get_current_user_id(), 'unread' );
            return '<span class="' . $class . '" data-count="' . $unread . '">' . $unread . '</span>';
        } else {
            return '<span class="' . $class . '" data-count="0">0</span>';
        }
    }

}

function Better_Messages_Shortcodes()
{
    return Better_Messages_Shortcodes::instance();
}
