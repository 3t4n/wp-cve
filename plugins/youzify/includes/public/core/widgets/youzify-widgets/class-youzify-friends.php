<?php

class Youzify_Profile_Friends_Widget {

    /**
     * Content.
     */
    function widget() {

        if ( ! bp_is_active( 'friends' ) ) {
            return;
        }

        // Get Member Friends Number
        $friends_nbr = friends_get_total_friend_count( bp_displayed_user_id() );

        if ( $friends_nbr <= 0 ) {
            return;
        }

        // Get Widget Layout.
        $widget_layout = youzify_option( 'youzify_wg_friends_layout', 'list' );

        // Get User Max Friends Number to show in the widget.
        $max_friends = youzify_option( 'youzify_wg_max_friends_items', 5 );

        // Get User Friends List.
        $user_friends = apply_filters( 'youzify_friends_get_friend_user_ids', friends_get_friend_user_ids( bp_displayed_user_id() ) );

        // Limit Friends Number
        $friend_ids = array_slice( $user_friends, 0, $max_friends );

        // Get Widget Class.
        $list_class = array(
            'youzify-profile-friends-widget',
            'youzify-items-' . $widget_layout . '-widget',
            'youzify-profile-' . $widget_layout . '-widget',
            'youzify-list-avatar-circle'
        );

        ?>

        <div class="<?php echo youzify_generate_class( $list_class ); ?>">

        <div class="youzify-list-inner">

            <?php foreach ( $friend_ids as $friend_id ) : ?>

            <div <?php if ( 'avatars' == $widget_layout ) echo 'data-youzify-tooltip="' . bp_core_get_user_displayname( $friend_id )  . '"'; ?> class="youzify-list-item">

                <a href="<?php echo bp_members_get_user_url( $friend_id ); ?>" class="youzify-item-avatar"><?php echo bp_core_fetch_avatar( array( 'item_id' => $friend_id, 'type' => 'full', 'width' => '60px', 'height' => '60px' ) ); ?></a>

                <?php if ( 'list' == $widget_layout ) : ?>

                    <div class="youzify-item-data">
                        <a href="<?php echo bp_members_get_user_url( $friend_id ); ?>" class="youzify-item-name"><?php echo bp_core_get_user_displayname( $friend_id ); ?><?php youzify_the_user_verification_icon( $friend_id ); ?></a>
                        <div class="youzify-item-meta">
                            <div class="youzify-meta-item">@<?php echo bp_members_get_user_slug( $friend_id ); ?></div>
                        </div>
                    </div>

                <?php endif; ?>

            </div>

            <?php endforeach; ?>

            <?php if ( $friends_nbr > $max_friends ) : ?>
                <?php $more_nbr = $friends_nbr - $max_friends; ?>
                <?php $more_title = ( 'list' == $widget_layout ) ? sprintf( __( 'Show All Friends ( %s )', 'youzify' ), $friends_nbr ) : '+' . $more_nbr; ?>
                <div class="youzify-more-items" <?php if ( 'avatars' == $widget_layout ) echo 'data-youzify-tooltip="' . __( 'Show All Friends', 'youzify' )  . '"'; ?>>
                    <a href="<?php echo bp_members_get_user_url( bp_displayed_user_id() ) . bp_get_friends_slug();?>"><?php echo $more_title; ?></a>
                </div>
            <?php endif; ?>

        </div>
        </div>

        <?php
    }

}