<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Component Class.
 *
 * @since 1.0.0
 */
class Better_Messages_Group extends BP_Group_Extension
{

    public static function instance()
    {
        // Store the instance locally to avoid private static replication
        static $instance = null;

        // Only run these methods if they haven't been run previously
        if ( null === $instance ) {
            $instance = new Better_Messages_Group;
            $instance->setup_hooks();
        }

        // Always return the instance
        return $instance;

        // The last metroid is in captivity. The galaxy is at peace.
    }

    /**
     * @since 1.0.0
     */
    public function __construct()
    {
        if( Better_Messages()->settings['enableGroups'] === '1' ){
            add_action('bp_ready', array( $this, 'init_group' ), 20 );

            add_filter('better_messages_thread_title', array( $this, 'group_thread_title' ), 10, 3 );
            add_filter('better_messages_thread_image', array( $this, 'group_thread_image' ), 10, 3 );
            add_filter('better_messages_thread_url',   array( $this, 'group_thread_url' ), 10, 3 );
        }
    }

    public function init_group(){
        $args = array(
            'slug'              => Better_Messages()->settings['bpGroupSlug'],
            'name'              => __( 'Messages', 'bp-better-messages' ),
            'nav_item_position' => 105,
            'enable_nav_item'   => apply_filters( 'bp_better_messages_enable_groups_tab', true ),
            'screens'           => array(),
            'visibility'        => 'private',
            'access'            => 'member'
        );

        global $bp;

        if( isset( $bp->groups->current_group->id ) ) {
            $enabled = ( $this->is_group_messages_enabled( $bp->groups->current_group->id ) === 'enabled' );
            if( $enabled ){
                parent::init( $args );
            }
        } else if(  is_customize_preview() ){
            parent::init( $args );
        }
    }

    /**
     * @param string $title
     * @param int $thread_id
     * @param BM_Thread $thread
     * @return string
     */
    public function group_thread_title(string $title, int $thread_id, $thread ){
        $thread_type = Better_Messages()->functions->get_thread_type( $thread_id );
        if( $thread_type !== 'group' ) return $title;

        $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'group_id');
        $group = new BP_Groups_Group( (int) $group_id );
        if( $group->id > 0 ) {
            return bp_get_group_name($group);
        } else {
            return $title;
        }
    }

    /**
     * @param string $title
     * @param int $thread_id
     * @param BM_Thread $thread
     * @return string
     */
    public function group_thread_image($image, int $thread_id, $thread ){
        $thread_type = Better_Messages()->functions->get_thread_type( $thread_id );
        if( $thread_type !== 'group' ) return $image;

        $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'group_id');

        $avatar = bp_core_fetch_avatar( array(
            'item_id'    => $group_id,
            'avatar_dir' => 'group-avatars',
            'object'     => 'group',
            'type'       => 'thumb',
            'html'       => false
        ));

        if( $avatar ){
            return $avatar;
        }

        return $image;
    }

    /**
     * @param string $title
     * @param int $thread_id
     * @param BM_Thread $thread
     * @return string
     */
    public function group_thread_url(string $title, int $thread_id, $thread ){
        $thread_type = Better_Messages()->functions->get_thread_type( $thread_id );
        if( $thread_type !== 'group' ) return $title;

        $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'group_id');
        $group = new BP_Groups_Group( $group_id );
        return bp_get_group_permalink( $group );
    }

    /**
     * Set some hooks to maximize BuddyPress integration.
     *
     * @since 1.0.0
     */
    public function setup_hooks()
    {
        if( Better_Messages()->settings['enableGroups'] === '1' ) {
            add_action( 'groups_join_group',            array( $this, 'on_groups_member_status_change'), 10, 2 );
            add_action( 'groups_leave_group',           array( $this, 'on_groups_member_status_change'), 10, 2 );
            add_action( 'groups_ban_member',            array( $this, 'on_groups_member_status_change'), 10, 2 );
            add_action( 'groups_remove_member',         array( $this, 'on_groups_member_status_change'), 10, 2 );
            add_action( 'groups_unban_member',          array( $this, 'on_groups_member_status_change'), 10, 2 );
            add_action( 'groups_group_request_managed', array( $this, 'on_groups_member_status_change'), 10, 1 );
            add_action( 'groups_member_after_remove',   array( $this, 'groups_member_after_remove'), 10, 1 );

            add_action( 'groups_member_after_save',      array( $this, 'on_group_member_change' ) );
            add_action( 'groups_member_after_remove',    array( $this, 'on_group_member_change' ) );
            add_action( 'bp_groups_member_after_delete', array( $this, 'group_member_deleted' ), 999, 2 );

            add_action( 'bp_invitations_accepted_invite',    array( $this, 'bp_invitations_accepted_invite'), 10, 1 );

            add_action( 'groups_delete_group',               array( $this, 'on_delete_group'), 10, 1 );

            add_action( 'bp_rest_group_members_create_item', array( $this, 'on_groups_member_rest_update'), 10, 5 );
            add_action( 'bp_rest_group_members_update_item', array( $this, 'on_groups_member_rest_update'), 10, 5 );
            add_action( 'bp_rest_group_members_delete_item', array( $this, 'on_groups_member_rest_update'), 10, 5 );

            add_filter( 'better_messages_rest_thread_item', array( $this, 'rest_thread_item'), 10, 3 );

            add_action('bp_after_group_settings_admin',         array($this, 'layout_group_setting'), 10);
            add_action('bp_after_group_settings_creation_step', array($this, 'layout_group_setting'), 10);
            add_action( 'groups_settings_updated',              array( $this, 'group_setting_update'), 10 );
            add_action( 'groups_create_group_step_complete',    array( $this, 'group_setting_create'), 10 );
            add_filter( 'better_messages_has_access_to_group_chat', array( $this, 'has_access_to_group_chat' ), 10, 3 );

            if( Better_Messages()->settings['enableGroupsFiles'] === '0' ) {
                add_action('bp_better_messages_user_can_upload_files', array($this, 'disable_upload_files'), 10, 3);
            }
        }
    }

    public function on_group_member_change($group_member){
        $group_id  = $group_member->group_id;
        $thread_id = $this->get_group_thread_id( $group_id );
        $this->sync_thread_members( $thread_id );
    }

    public function group_member_deleted( $user_id, $group_id ){
        $thread_id = $this->get_group_thread_id( $group_id );
        $this->sync_thread_members( $thread_id );
    }

    public function has_access_to_group_chat( $has_access, $thread_id, $user_id ){
        $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'group_id');
        if ( !! $group_id ) {
            return BP_Groups_Member::check_is_member( $user_id, $group_id );
        }

        return $has_access;
    }


    public function rest_thread_item( $thread_item, $thread_id, $thread_type ){
        if( $thread_type !== 'group'){
            return $thread_item;
        }

        return $thread_item;
    }

    public function disable_upload_files( $can_upload, $user_id, $thread_id ){
        if( Better_Messages()->functions->get_thread_type( $thread_id ) === 'group' ) {
            return false;
        }

        return $can_upload;
    }

    public function bp_invitations_accepted_invite($r){
        if( isset( $r['class'] ) && isset ( $r['item_id'] ) ){
            if( $r['class'] === 'BP_Groups_Invitation_Manager' ){
                $group_id = $r['item_id'];
                $this->on_groups_member_status_change( $group_id );
            }
        }
    }

    public function on_delete_group($group_id){
        global $wpdb;

        $thread_id = (int) $wpdb->get_var( $wpdb->prepare( "
        SELECT bm_thread_id 
        FROM `" . bm_get_table('threadsmeta') . "` 
        WHERE `meta_key` = 'group_id' 
        AND   `meta_value` = %s
        ", $group_id ) );

        if( !! $thread_id ){
            Better_Messages()->functions->delete_thread_meta( $thread_id, 'group_id' );
            Better_Messages()->functions->delete_thread_meta( $thread_id, 'group_thread' );
        }
    }

    public function is_group_messages_enabled( $group_id = false ){
        if( Better_Messages()->settings['enableGroups'] !== '1' ){
            return 'disabled';
        }

        $messages = 'enabled';
        if( !! $group_id ) {
            $messages = groups_get_groupmeta( $group_id, 'bpbm_messages' );
            if( empty( $messages ) ) $messages = 'enabled';
        }

        return $messages;
    }

    public function group_setting_create(){
        if( isset($_POST['bpbm_messages']) ){
            global $bp;
            $group_id = $bp->groups->new_group_id;
            $messages_status = sanitize_text_field($_POST['bpbm_messages']);
            groups_update_groupmeta( $group_id, 'bpbm_messages', $messages_status );
        }
    }

    public function group_setting_update( $group_id ){
        if( isset($_POST['bpbm_messages']) ){
            $messages_status = sanitize_text_field($_POST['bpbm_messages']);
            groups_update_groupmeta( $group_id, 'bpbm_messages', $messages_status );
        }
    }

    public function layout_group_setting(){
        if( doing_action('bp_after_group_settings_creation_step') ) {
            $group_id = false;
        } else {
            $group_id = bp_get_group_id();
        }

        $messages = $this->is_group_messages_enabled( $group_id ); ?>
        <div class="group-settings-selections">
        <fieldset class="radio ">
            <legend><?php esc_html_e( 'Group Messages', 'bp-better-messages' ); ?></legend>

            <p tabindex="0"><?php _ex( 'Enable Group Messages feature for this group', 'BuddyPress Groups', 'bp-better-messages' ); ?></p>
            <p tabindex="0"><?php _ex( 'All members of the group will be automatically joined to the conversation of this group', 'BuddyPress Groups', 'bp-better-messages' ); ?></p>

            <label for="group-bp-messages-enabled">
                <input type="radio" name="bpbm_messages" id="group-bp-messages-enabled" value="enabled" <?php checked($messages, 'enabled'); ?>/>
                <?php esc_html_e( 'Enabled', 'bp-better-messages' ); ?>
            </label>

            <label for="group-bp-messages-disabled">
                <input type="radio" name="bpbm_messages" id="group-bp-messages-disabled" value="disabled" <?php checked($messages, 'disabled'); ?> />
                <?php esc_html_e( 'Disabled', 'bp-better-messages' ); ?>
            </label>

        </fieldset>
        </div>
        <?php
    }

    public function groups_member_after_remove( $object ){
        $this->on_groups_member_status_change( $object->group_id, $object->user_id );
    }

    public function on_groups_member_rest_update( $user, $group_member, $group, $response, $request ){
        $this->on_groups_member_status_change( $group->id, $user->id );
    }

    public function on_groups_member_status_change( $group_id, $user_id = false ){
        $thread_id = $this->get_group_thread_id( $group_id );
        $this->sync_thread_members( $thread_id );
    }

    public function get_group_thread_id( $group_id ){
        global $wpdb;

        $thread_id = (int) $wpdb->get_var( $wpdb->prepare( "
        SELECT bm_thread_id 
        FROM `" . bm_get_table('threadsmeta') . "` 
        WHERE `meta_key` = 'group_id' 
        AND   `meta_value` = %s
        ", $group_id ) );

        $thread_exist = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*)  FROM `" . bm_get_table('threads') . "` WHERE `id` = %d", $thread_id));

        if( $thread_exist === 0 ){
            $thread_id = false;
        }

        if( ! $thread_id ) {
            $wpdb->query( $wpdb->prepare( "
            DELETE  
            FROM `" . bm_get_table('threadsmeta') . "` 
            WHERE `meta_key` = 'group_id' 
            AND   `meta_value` = %s
            ", $group_id ) );

            $group = new BP_Groups_Group( $group_id );

            $wpdb->insert(
                bm_get_table('threads'),
                array(
                    'subject' => $group->name,
                    'type'    => 'group'
                )
            );

            $thread_id = $wpdb->insert_id;

            Better_Messages()->functions->update_thread_meta( $thread_id, 'group_thread', true );
            Better_Messages()->functions->update_thread_meta( $thread_id, 'group_id', $group_id );

            $this->sync_thread_members( $thread_id );
        }

        return $thread_id;
    }

    public function sync_thread_members( $thread_id ){
        wp_cache_delete( 'thread_recipients_' . $thread_id, 'bm_messages' );
        wp_cache_delete( 'bm_thread_recipients_' . $thread_id, 'bm_messages' );
        $group_id = Better_Messages()->functions->get_thread_meta( $thread_id, 'group_id' );
        $group    = new BP_Groups_Group( $group_id );

        if( ! $group ) {
            return false;
        }

        global $wpdb;
        $members   = BP_Groups_Member::get_group_member_ids( $group_id );
        $array     = [];
        $user_ids  = [];
        $removed_ids = [];

        /**
         * All users ids in thread
         */
        $recipients = Better_Messages()->functions->get_recipients( $thread_id );

        foreach( $members as $index => $member ){
            if( isset( $recipients[$member] ) ){
                unset( $recipients[$member] );
                continue;
            }

            $user_ids[] = $member;

            $array[] = [
                $member,
                $thread_id,
                0,
                0,
            ];
        }

        $changes = false;

        if( count($array) > 0 ) {
            $sql = "INSERT IGNORE INTO " . bm_get_table('recipients') . "
            (user_id, thread_id, unread_count, is_deleted)
            VALUES ";

            $values = [];

            foreach ($array as $item) {
                $values[] = $wpdb->prepare( "(%d, %d, %d, %d)", $item );
            }

            $sql .= implode( ',', $values );

            $wpdb->query( $sql );
            $changes = true;
        }

        if( count($recipients) > 0 ) {
            foreach ($recipients as $user_id => $recipient) {
                $wpdb->delete( bm_get_table('recipients'), [
                    'thread_id' => $thread_id,
                    'user_id'   => $user_id
                ], ['%d','%d'] );

                $removed_ids[] = $user_id;
            }

            $changes = true;
        }

        Better_Messages()->hooks->clean_thread_cache( $thread_id );

        if( $changes ){
            do_action( 'better_messages_thread_updated', $thread_id );
            do_action( 'better_messages_info_changed', $thread_id );
            do_action( 'better_messages_participants_added', $thread_id, $user_ids );
            do_action( 'better_messages_participants_removed', $thread_id, $removed_ids );
        }

        return true;
    }

    function display( $group_id = NULL ) {
        echo Better_Messages()->functions->get_group_page( $group_id );
    }
}

bp_register_group_extension( 'Better_Messages_Group' );

function Better_Messages_Group()
{
    return Better_Messages_Group::instance();
}
