<?php
/**
 * Wall Media
 */
class Youzify_Media {

    /**
     * Instance of this class.
     */
    protected static $instance = null;

    /**
     * Return the instance of this class.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

	function __construct( ) {

		// Media - Ajax Pagination
		add_action( 'wp_ajax_youzify_media_pagination', array( $this, 'ajax_pagination' ) );
		add_action( 'wp_ajax_nopriv_youzify_media_pagination', array( $this, 'ajax_pagination' ) );

	}

	/***
	 * Get Medi Photos
	 */
	function get_media( $args ) {

		global $bp, $wpdb, $Youzify_media_table;

		// Get Shortcode Args.
		$args = wp_parse_args( $args );

		$query = ! isset( $args['query'] ) || $args['query'] == 'data' ? 'm.id, m.media_id, m.item_id, m.data' : 'COUNT(*)';

		$page = isset( $args['page'] ) ? absint( sanitize_text_field( $args['page'] ) ): 1;

        $limit = isset( $args['limit'] ) ? absint( sanitize_text_field( $args['limit'] ) ): 9;

        // Get Offset.
		$offset = isset( $limit ) ? ( $page - 1 ) * $limit : 0;

		// Get Activity Types.
		$type = isset( $args['type'] ) ? $this->get_activity_types( $args['type'] ) : $this->get_activity_types( 'photos' );

		// Prepare Sql
		$sql ="SELECT $query FROM $Youzify_media_table AS m JOIN {$bp->activity->table_name} AS a ON m.item_id = a.id WHERE ";

        // Set Activity Component.
		if ( ! isset( $args['group_id'] ) ) {

            $sql .= apply_filters( 'youzify_activity_media_query_component', " m.component = 'activity'", $args );

            // If is not admin hide hidden posts.
            if ( ! current_user_can( 'administrator' ) ) {
                $sql .= " AND a.hide_sitewide = 0";
            }

		} else {
			$sql .= $wpdb->prepare( " m.component = 'groups' AND a.item_id = %d", $args['group_id'] );
        }

        // Get User ID.
        $user_id = isset( $args['user_id'] ) ? absint( sanitize_text_field( $args['user_id'] ) ) : '';

		if ( ! empty( $user_id ) ) {
			$sql .= $wpdb->prepare( " AND a.user_id = %d", $user_id );
		}

        // Set Privacy.
        $privacy = $this->get_activities_privacy( $user_id );

        // Set Privacy.
        if ( ! empty( $privacy ) ) {
            $sql .= " AND a.privacy IN ( $privacy )";
        }

        // Hide Spam Posts AND Set posts type.
        $sql .= " AND a.is_spam = 0 AND a.type IN ( $type )";

        // Filter SQL
        $sql = apply_filters( 'youzify_activity_media_sql', $sql, $args );

		if ( $query == 'COUNT(*)' ) {
			// Get Result
			$result = $wpdb->get_var( $sql );
		} else {

    		if ( ! empty( $limit ) ) {
    			$sql .= $wpdb->prepare( " GROUP BY m.id ORDER BY m.id DESC LIMIT %d", $limit );
    		}

    		if ( ! empty( $offset ) ) {
    			$sql .= $wpdb->prepare( " OFFSET %d", $offset );
    		}

			// Get Result
			$result = $wpdb->get_results( $sql , ARRAY_A );
		}

		return $result;

	}

    /**
     * Check Sticky Post Visibilyty.
     */
    function get_activities_privacy( $user_id = null ) {

        $privacy = "'public'";

        if ( is_user_logged_in() ) {

            // Add Members Privacy.
            $privacy .= ", 'members'";

            if ( ! empty( $user_id ) ) {

                if ( $user_id == bp_loggedin_user_id() ) {
                    return '';
                }

                if ( bp_is_active( 'friends' ) && friends_check_friendship( bp_loggedin_user_id(), $user_id ) ) {
                    $privacy .=", 'friends'";
                }
            }

        }

        return $privacy;

    }

	/**
	 * Get User Media Count.
	 */
	function get_media_count( $args = null ) {

		global $bp, $wpdb, $Youzify_media_table;

		// Prepare Sql
		$sql ="SELECT count(*) FROM $Youzify_media_table WHERE user_id = {$args['user_id']} ";

		return $wpdb->get_var( $sql );
	}

	/**
	 * Get Media Activity Types.
	 */
	function get_activity_types( $type ) {

		switch ( $type ) {
			case 'photos':
				$types = "'activity_photo','activity_slideshow','activity_quote','activity_link','new_avatar','new_cover'";
				break;
			case 'videos':
				$types = "'activity_video'";
				break;
			case 'audios':
				$types = "'activity_audio'";
				break;
			case 'files':
				$types = "'activity_file'";
				break;
		}

		return apply_filters( 'youzify_get_media_activity_types', $types, $type );
	}

    /**
     * Get Photos Items.
     **/
    function get_photos_items( $args = null ) {

    	// Add Query Type.
    	$args['type'] = 'photos';

        $photos = $this->get_media( $args );

        if ( empty( $photos ) ) {
            echo '<div class="youzify-media-no-items">' . __( 'Sorry, no items found.', 'youzify' ) . '</div>';
            return;
        }

        foreach ( $photos as $photo ) : ?>
            <div data-item-id="<?php echo $photo['id']; ?>" class="youzify-media-item">
                <div class="youzify-media-item-img" >
                    <img loading="lazy" <?php echo youzify_get_image_attributes( $photo['media_id'], 'youzify-thumbnail', 'media-photos' ); ?> alt="">
                    <div class="youzify-media-item-tools">
                        <a href="<?php echo bp_activity_get_permalink( $photo['item_id'] );?>"><i class="fas fa-link youzify-media-post-link"></i></a>
                        <a data-youzify-lightbox="youzify-media-lightbox<?php if ( isset( $args['box'] ) ) echo '-' . $args['box']; ?>" href="<?php echo wp_get_attachment_url( $photo['media_id'] ); ?>"><i class="fas fa-search youzify-media-zoom-photo"></i></a>
                    </div>
                </div>
                <?php do_action( 'youzify_after_media_item', $photo['media_id'], $args ); ?>
            </div>
            <?php do_action( 'youzify_after_media_item_content', $photo['media_id'], $args ); ?>
        <?php endforeach;

        $this->pagination( $args );

    }

    /**
     * Get Videos Items.
     **/
    function get_videos_items( $args = null ) {

    	// Add Query Type.
    	$args['type'] = 'videos';

        $videos = $this->get_media( $args );

        if ( empty( $videos ) ) {
            echo '<div class="youzify-media-no-items">' . __( 'Sorry, no items found.', 'youzify' ) . '</div>';
            return;
        }

        $thumbnail_size = isset( $args['layout'] ) && $args['layout'] == '3columns' ? 'full' : 'medium';

        $thumbnail_size = apply_filters( 'youzify_media_videos_thumbnail_size', $thumbnail_size );

        foreach ( $videos as $video ) :

            // Get Data.
            $thumbnail = '';
            $thumbnail_type = 'image';
            $src = (array) maybe_unserialize( $video['data'] );

            if ( ! isset( $src['provider'] ) ) {
                continue;
            }

        	if ( $src['provider'] == 'local' ) {

                if ( isset( $src['thumbnail'] ) ) {
                    $thumbnail = youzify_get_image_attributes( $src['thumbnail'], 'youzify-thumbnail', 'media-video' );
                    $src['id'] = wp_get_attachment_url( $video['media_id'] );
                } else {
                    $thumbnail = wp_get_attachment_url( $video['media_id'] );
                    $thumbnail_type = 'video';
                }

        	} else {

                if ( isset( $src[ $thumbnail_size ] ) ) {
                    $thumbnail = youzify_get_image_attributes_by_link( $src[ $thumbnail_size ] );
                }

                if ( empty( $thumbnail ) ) {
                    $video_thumbnail = youzify_get_embed_video_thumbnails( $src['provider'], $src['id'], $thumbnail_size );
                    if ( ! empty( $video_thumbnail ) ) {
                        $thumbnail = youzify_get_image_attributes_by_link( $video_thumbnail );
                    }
                }

        	}

        	?>

        	<div class="youzify-media-item">
        	<?php if ( $thumbnail_type == 'image' ) : ?>
        		<div data-activity-id="<?php echo $video['item_id']; ?>" class="youzify-media-item-content">
                    <div class="youzify-media-item-img youzify-<?php echo $src['provider']; ?>-item"><img loading="lazy" <?php echo $thumbnail; ?> alt=""><?php if ( $src['provider'] == 'local' ) echo '<i class="fas fa-play-circle youzify-media-local-video"></i>'; ?><div class="youzify-media-item-tools"><a href="<?php echo bp_activity_get_permalink( $video['item_id'] );?>"><i class="fas fa-link youzify-media-post-link"></i></a><a class="youzify-video-lightbox" data-<?php echo $this->get_video_data( $src['provider'] ); ?>="<?php echo $src['id']; ?>"><i class="fas fa-play-circle youzify-media-video-play"></i></a></div></div></div>
        	<?php else : ?>
            	<div data-activity-id="<?php echo $video['item_id']; ?>" class="youzify-media-item-content"><div class="youzify-media-item-video"><video width="100%" controls preload="metadata"><source class="lazyload" src="<?php echo wp_get_attachment_url( $video['media_id'] ); ?>" type="video/mp4"><?php _e( 'Your browser does not support the video tag.', 'youzify' ); ?></video></div></div>
        	<?php endif; ?>
            <?php do_action( 'youzify_after_media_item', $video['media_id'], $args ); ?>
        	</div>
            <?php do_action( 'youzify_after_media_item_content', $video['media_id'], $args ); ?>

        <?php endforeach;

        $this->pagination( $args );

    }

    /**
     * Get Video Thumbnail By Provider
     **/
    function get_video_data( $provider ) {

    	switch ( $provider ) {

    		case 'youtube':
    			$data = 'youzify-yt';
    			break;

    		case 'vimeo':
    			$data = 'youzify-vimeo';
    			break;

            case 'dailymotion':
                $data = 'youzify-dailymotion';
                break;

            case 'local':
                $data = 'youzify-video';
                break;

            default:
                $data = '';
                break;

    	}

    	return apply_filters( 'youzify_get_wall_embed_video_data', $data );

    }

 	/**
     * Get Audio Items.
     **/
    function get_audios_items( $args = null ) {

    	// Add Query Type.
    	$args['type'] = 'audios';

        $audios = $this->get_media( $args );

        if ( empty( $audios ) ) {
            echo '<div class="youzify-media-no-items">' . __( 'Sorry, no items found.', 'youzify' ) . '</div>';
            return;
        }

        foreach ( $audios as $audio ) :

        	?>

        	<div class="youzify-media-item">

                <a class="youzify-media-item-content" data-activity-id="<?php echo $audio['item_id']; ?>" href="<?php echo bp_activity_get_permalink( $audio['item_id'] );?>"><div class="youzify-media-item-img"><i class="fas fa-headphones-alt youzify-media-audio-play"></i></div></a>
                <audio controls>
                    <source src="<?php echo wp_get_attachment_url( $audio['media_id'] ); ?>" type="audio/mpeg">
                    <?php _e( 'Your browser does not support the audio element.', 'youzify' ); ?>
                </audio>
                <?php do_action( 'youzify_after_media_item', $audio['media_id'], $args ); ?>
            </div>
            <?php do_action( 'youzify_after_media_item_content', $audio['media_id'], $args ); ?>

        <?php endforeach;

        $this->pagination( $args );

    }

 	/**
     * Get Files Items.
     **/
    function get_files_items( $args = null ) {

    	// Add Query Type.
    	$args['type'] = 'files';

        $files = $this->get_media( $args );

        if ( empty( $files ) ) {
            echo '<div class="youzify-media-no-items">' . __( 'Sorry, no items found.', 'youzify' ) . '</div>';
            return;
        }

        foreach ( $files as $file ) :

			// Get Attachment Data
			$attachments = bp_activity_get_meta( $file['item_id'], 'youzify_attachments' );

            // Get File Data.
            $data = $attachments[ $file['media_id'] ];

        	?>

        	<div class="youzify-media-item">

				<a class="youzify-media-content" data-activity-id="<?php echo $file['item_id']; ?>" href="<?php echo bp_activity_get_permalink( $file['item_id'] );?>">
					<div class="youzify-media-content-inner">
						<div class="youzify-media-icon"><i class="fas fa-cloud-download-alt youzify-file-icon"></i></div>
                        <div class="youzify-media-head-area">
    						<div class="youzify-media-title" title="<?php echo $data['real_name']; ?>"><?php echo $data['real_name']; ?></div>
    						<div class="youzify-media-size"><?php echo youzify_file_format_size( $data['file_size'] ); ?></div>
                        </div>
					</div>
				</a>

				<div class="youzify-media-download">
					<a rel="nofollow" href="<?php echo wp_get_attachment_url( $file['media_id'] ); ?>"><i class="fas fa-download"></i><span><?php _e( 'Download', 'youzify' ); ?><span></a>
				</div>
                <?php do_action( 'youzify_after_media_item', $file['media_id'], $args ); ?>
            </div>
            <?php do_action( 'youzify_after_media_item_content', $file['media_id'], $args ); ?>

        <?php endforeach;

        $this->pagination( $args );
    }

    /**
     * Get Media Pagination Items.
     **/
    function pagination( $args ) {

        if ( ! isset( $args['pagination'] ) ) {
            return;
        }

        // Pagination Args.
        $p_args = array( 'type' => $args['type'], 'query' =>'count' );

        if ( isset( $args['user_id'] ) ) {
            $p_args['user_id'] = $args['user_id'];
        } elseif( isset( $args['group_id'] ) ) {
            $p_args['group_id'] = $args['group_id'];
        }

        // Get Total.
        $args['total'] = $this->get_media( $p_args ) ;

        // Add Pagination.
        youzify_pagination( $args );

    }

    /**
     * Media Pagination.
     */
    function ajax_pagination() {

        // Get Data.
        $data = array_map( 'sanitize_text_field' , $_POST['data'] );

        // Add Current Page.
        $data['page'] = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;

        if ( $data['type'] == 'photos' ) {
            $this->get_photos_items( $data );
        } elseif ( $data['type'] == 'videos' ) {
            $this->get_videos_items( $data );
        } elseif( $data['type'] == 'audios' ) {
            $this->get_audios_items( $data );
        } elseif( $data['type'] == 'files' ) {
            $this->get_files_items( $data );
        }

        if ( ( isset( $data['user_id'] ) || isset( $data['group_id'] ) ) && isset( $data['view_all'] ) ) {
            $total = $this->get_media( array_merge( $data, array( 'query' => 'count' ) ) );
            if ( $total > $data['limit'] ) { ?>
            <a class="youzify-media-view-all" href="<?php echo $this->get_media_by_type_slug( $data ); ?>"><?php echo sprintf( __( 'View All %1s ( %2d )', 'youzify' ), $data['title'], $total ); ?></a>
            <?php }
        }

        die();

    }

    /**
     * Get Slug
     **/
    function get_media_by_type_slug( $args ) {

        $slug = '';

        if ( isset( $args['user_id'] ) ) {
            $slug = bp_members_get_user_url( $args['user_id'] ) . youzify_profile_media_slug();
        } elseif ( isset( $args['group_id'] ) ) {
            $group = groups_get_group( $args['group_id'] );
            $slug = bp_get_group_url( $group ) . youzify_group_media_slug();
        }

        if ( isset( $args['type'] ) ) {
            $slug .= '/' . $args['type'];
        }

        return $slug;

    }

    /**
     * Scripts.
     */
    function scripts() {
        wp_enqueue_style( 'youzify-media', YOUZIFY_ASSETS . 'css/youzify-media.min.css', array(), YOUZIFY_VERSION );
        wp_enqueue_script( 'youzify-media', YOUZIFY_ASSETS . 'js/youzify-media.min.js', array( 'jquery' ), YOUZIFY_VERSION, true );
    }

}


/**
 * Get a unique instance of Youzify Media.
 */
function youzify_media() {
    return Youzify_Media::get_instance();
}

/**
 * Launch Youzify Media!
 */
youzify_media();