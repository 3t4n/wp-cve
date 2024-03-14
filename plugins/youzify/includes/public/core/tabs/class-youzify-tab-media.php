<?php

/***
 * Media Tab.
 */
class Youzify_Media_Tab {

    /**
     * Constructor
     */
    function __construct() {

        add_action( 'bp_enqueue_scripts', array( $this, 'scripts' ) );

    }

    /**
     * Group Tab.
     */
    function group_tab() {

        do_action( 'youzify_group_media_tab' );

        $current_tab = bp_action_variable();

        if ( empty( $current_tab ) || $current_tab == 'all' ) {
            $layout = youzify_option( 'youzify_group_media_tab_layout', '4columns' );
            $limit = youzify_option( 'youzify_group_media_tab_per_page', 8 );
        } else {
            $layout = youzify_option( 'youzify_group_media_subtab_layout', '3columns' );
            $limit = youzify_option( 'youzify_group_media_subtab_per_page', 24 );
        }

        $args = array( 'group_id' => bp_get_current_group_id(), 'layout' => $layout, 'limit' => $limit, 'pagination' => true );

        ?>

        <div class="item-list-tabs no-ajax" id="subnav" aria-label="<?php esc_attr_e( 'Group secondary navigation', 'youzify' ); ?>" role="navigation">
            <ul><?php bp_get_options_nav( youzify_group_media_slug() ); ?></ul>
        </div>

        <div class="youzify-tab youzify-media youzify-media-<?php echo $args['layout']; ?>">

        <?php

        switch ( $current_tab ) {

            case '':
            case 'all':

                // Delete Pagination.
                unset( $args['pagination'] );

                do_action( 'youzify_group_before_all_media_tab' );

                if ( 'on' == youzify_option( 'youzify_show_group_media_tab_photos', 'on' ) ) $this->get_photos( $args );
                if ( 'on' == youzify_option( 'youzify_show_group_media_tab_videos', 'on' ) ) $this->get_videos( $args );
                if ( 'on' == youzify_option( 'youzify_show_group_media_tab_audios', 'on' ) ) $this->get_audios( $args );
                if ( 'on' == youzify_option( 'youzify_show_group_media_tab_files', 'on' ) ) $this->get_files( $args );

                do_action( 'youzify_group_after_all_media_tab' );

                break;

            case 'photos':
                $this->get_photos( $args );
                break;

            case 'videos':
                $this->get_videos( $args );
                break;

            case 'audios':
                $this->get_audios( $args );
                break;

            case 'files':
                $this->get_files( $args );
                break;

            case 'albums':
                do_action( 'youzify_group_media_albums_content' );
                break;

            default:
                do_action( 'youzify_profile_media_content' );
                break;
        }

        ?>

        </div>

        <?php
    }

    /**
     * Tab.
     */
    function tab() {

        do_action( 'youzify_profile_media_tab' );

        $current_tab = bp_current_action();

        if ( empty( $current_tab ) || $current_tab == 'all' ) {
            $layout = youzify_option( 'youzify_profile_media_tab_layout', '4columns' );
            $limit = youzify_option( 'youzify_profile_media_tab_per_page', 8 );
        } else {
            $layout = youzify_option( 'youzify_profile_media_subtab_layout', '3columns' );
            $limit = youzify_option( 'youzify_profile_media_subtab_per_page', 24 );
        }

        $args = array( 'user_id' => bp_displayed_user_id(), 'layout' => $layout, 'limit' => $limit, 'pagination' => true );

        ?>

        <div class="youzify-tab youzify-media youzify-media-<?php echo $args['layout']; ?>">

        <?php
            switch ( $current_tab ) {

                case 'all':

                    // Delete Pagination.
                    unset( $args['pagination'] );


                    do_action( 'youzify_profile_before_all_media_tab' );

                    if ( 'on' == youzify_option( 'youzify_show_profile_media_tab_photos', 'on' ) ) $this->get_photos( $args );
                    if ( 'on' == youzify_option( 'youzify_show_profile_media_tab_videos', 'on' ) ) $this->get_videos( $args );
                    if ( 'on' == youzify_option( 'youzify_show_profile_media_tab_audios', 'on' ) ) $this->get_audios( $args );
                    if ( 'on' == youzify_option( 'youzify_show_profile_media_tab_files', 'on' ) ) $this->get_files( $args );

                    do_action( 'youzify_profile_after_all_media_tab' );

                    break;

                case 'photos':
                    $this->get_photos( $args );
                    break;

                case 'videos':
                    $this->get_videos( $args );
                    break;

                case 'audios':
                    $this->get_audios( $args );
                    break;

                case 'files':
                    $this->get_files( $args );
                    break;

                case 'albums':
                    do_action( 'youzify_profile_media_albums_content' );
                    break;

                default:
                    do_action( 'youzify_profile_media_content' );
                    break;
            }

        ?>

        </div>

        <?php
    }

    /**
     * Get Photos
     **/
    function get_photos( $args = null ) {

        ?>

        <div class="youzify-media-group youzify-media-group-photos">

            <div class="youzify-media-group-head">
                <div class="youzify-media-head-left">
                    <div class="youzify-media-group-icon"><i class="fas fa-image"></i></div>
                    <div class="youzify-media-group-title"><?php _e( 'Photos', 'youzify' ); ?></div>
                </div>
                <div class="youzify-media-head-right">
                    <?php if ( bp_current_action() != 'photos' ) : ?>
                    <a href="<?php echo youzify_media()->get_media_by_type_slug( $args ) . '/photos'; ?>" class="youzify-media-group-view-all"><?php _e( 'View All', 'youzify' ); ?></a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="youzify-media-group-content">
                <div class="youzify-media-items">
                    <?php youzify_media()->get_photos_items( $args ); ?>
                </div>
            </div>

        </div>
        <?php
    }

    /**
     * Get Videos
     **/
    function get_videos( $args = null ) {

        ?>

        <div class="youzify-media-group youzify-media-group-videos">

            <div class="youzify-media-group-head">
                <div class="youzify-media-head-left">
                    <div class="youzify-media-group-icon"><i class="fas fa-film"></i></div>
                    <div class="youzify-media-group-title"><?php _e( 'Videos', 'youzify' ); ?></div>
                </div>
                <div class="youzify-media-head-right">
                    <?php if ( bp_current_action() != 'videos' ) : ?>
                    <a href="<?php echo youzify_media()->get_media_by_type_slug( $args ). '/videos'; ?>" class="youzify-media-group-view-all"><?php _e( 'View All', 'youzify' ); ?></a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="youzify-media-group-content">
                <div class="youzify-media-items">
                    <?php youzify_media()->get_videos_items( $args ); ?>
                </div>
            </div>

        </div>

        <?php
    }

    /**
     * Get Audios
     **/
    function get_audios( $args = null ) {

        ?>

        <div class="youzify-media-group youzify-media-group-audios">

            <div class="youzify-media-group-head">
                <div class="youzify-media-head-left">
                    <div class="youzify-media-group-icon"><i class="fas fa-volume-up"></i></div>
                    <div class="youzify-media-group-title"><?php _e( 'Audios', 'youzify' ); ?></div>
                </div>
                <div class="youzify-media-head-right">

                    <?php if ( bp_current_action() != 'audios' ) : ?>
                    <a href="<?php echo youzify_media()->get_media_by_type_slug( $args ) . '/audios'; ?>" class="youzify-media-group-view-all"><?php _e( 'View All', 'youzify' ); ?></a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="youzify-media-group-content">
                <div class="youzify-media-items">
                    <?php youzify_media()->get_audios_items( $args ); ?>
                </div>
            </div>

        </div>

        <?php
    }

    /**
     * Get Files
     **/
    function get_files( $args = null ) {

        ?>

        <div class="youzify-media-group youzify-media-group-files">

            <div class="youzify-media-group-head">
                <div class="youzify-media-head-left">
                    <div class="youzify-media-group-icon"><i class="fas fa-file-import"></i></div>
                    <div class="youzify-media-group-title"><?php _e( 'Files', 'youzify' ); ?></div>
                </div>
                <div class="youzify-media-head-right">

                    <?php if ( bp_current_action() != 'files' ) : ?>
                    <a href="<?php echo youzify_media()->get_media_by_type_slug( $args ) . '/files'; ?>" class="youzify-media-group-view-all"><?php _e( 'View All', 'youzify' ); ?></a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="youzify-media-group-content">
                <div class="youzify-media-items">
                    <?php youzify_media()->get_files_items( $args ); ?>
                </div>
            </div>

        </div>

        <?php
    }

    /**
     * Scripts
     */
    function scripts() {
        youzify_media()->scripts();
    }
}