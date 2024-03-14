<?php

class Youzify_Tabs {

    public function __construct() {

    	// Create Profile Tabs.
		// $this->add_profile_tabs();
    	add_action( 'bp_setup_nav', array( $this, 'add_profile_tabs' ) );
    	// add_action( 'init', array( $this, 'add_profile_tabs' ) );
    }

	/**
	 * Add Profile Tabs.
	 */
	function add_profile_tabs() {

	    global $bp;

		// Determine user to use.
		if ( bp_displayed_user_url() ) {
			$user_domain = bp_displayed_user_url();
		} elseif ( bp_loggedin_user_domain() ) {
			$user_domain = bp_loggedin_user_domain();
		}

	    $overview_args = apply_filters( 'youzify_profile_overview_tab_args', array(
	        'position' => 1,
	        'slug' => 'overview',
	        'default_subnav_slug' => 'overview',
	        'parent_slug' => $bp->profile->slug,
	        'name' => __( 'Overview', 'youzify' ),
	        'screen_function' => array( $this, 'overview_screen' ),
	    ) );

	    $info_args = apply_filters( 'youzify_profile_info_tab_args', array(
	        'position' => 2,
	        'slug' => 'info',
	        'parent_slug' => $bp->profile->slug,
	        'name' => __( 'Info', 'youzify' ),
	        'screen_function' => array( $this, 'info_screen' ),
	    ) );

	    $comments_args = apply_filters( 'youzify_profile_comments_tab_args', array(
	        'position' => 15,
	        'slug' => 'comments',
	        'parent_slug' => $bp->profile->slug,
	        'name' => __( 'Comments', 'youzify' ),
	        'screen_function' => array( $this, 'comments_screen' ),
	    ) );

	    // Add Overview Tab.
	    bp_core_new_nav_item( $overview_args );

	    // Add Infos Tab.
	    bp_core_new_nav_item( $info_args );

		if ( apply_filters( 'youzify_add_profile_posts_tab', true ) ) {
		    $posts_args = apply_filters( 'youzify_profile_posts_tab_args', array(
		        'position' => 14,
		        'slug' => 'posts',
		        'parent_slug' => $bp->profile->slug,
		        'name' => __( 'Posts', 'youzify' ),
		        'screen_function' => array( $this, 'posts_screen' ),
		    ) );

		    // Add Posts Tab.
		    bp_core_new_nav_item( $posts_args );


			// Add Sub Tab.
		    bp_core_new_subnav_item(
		    	array(
		            'slug' => 'all',
		            'parent_slug' => 'posts',
		            'name' => __( 'Posts', 'youzify-fes' ),
		            'parent_url' => bp_displayed_user_url()  . 'posts/',
		            'screen_function' => array( $this, 'posts_screen' ),
		        )
		    );

		}

		// //add courses tab
		// if ( apply_filters( 'youzify_add_profile_courses_tab', true ) ) {
		//     $courses_args = apply_filters( 'youzify_profile_courses_tab_args', array(
		//         'position' => 16,
		//         'slug' => 'courses',
		//         'parent_slug' => $bp->profile->slug,
		//         'name' => __( 'Courses', 'youzify' ),
		//         'screen_function' => array( $this, 'courses_screen' ),
		//     ) );

		//     // Add courses Tab.
		//     bp_core_new_nav_item( $courses_args );


		// 	// Add Sub Tab.
		//     bp_core_new_subnav_item(
		//     	array(
		//             'slug' => 'all',
		//             'parent_slug' => 'courses',
		//             'name' => __( 'Courses', 'youzify-fes' ),
		//             'parent_url' => bp_displayed_user_url()  . 'courses/',
		//             'screen_function' => array( $this, 'courses_screen' ),
		// 		),
		//     );

		// 	// Add Sub Tab.
		//     bp_core_new_subnav_item(
		//     	array(
		//             'slug' => 'certificates',
		//             'parent_slug' => 'courses',
		//             'name' => __( 'Certificates', 'youzify-fes' ),
		//             'parent_url' => bp_displayed_user_url()  . 'courses/',
		//             'screen_function' => array( $this, 'certificates_screen' ),
		// 		),
		//     );

		// }
		// class_exists( 'Tutor' ) &&
		// //add tutor courses tab
		if ( defined( 'TUTOR_VERSION' ) && apply_filters( 'youzify_add_profile_tutor_courses_tab', true ) ) {

			if ( bp_is_my_profile() || youzify_option( 'youzify_make_courses_tab_public', 'off' ) == 'on' || is_super_admin() ) {

			    $courses_args = apply_filters( 'youzify_profile_tutor_courses_tab_args', array(
			        'position' => 16,
			        'slug' => 'courses',
			        'parent_slug' => $bp->profile->slug,
			        'name' => __( 'Courses', 'youzify' ),
			        'screen_function' => array( $this, 'tutor_courses_screen' ),
			    ) );

			    // Add courses Tab.
			    bp_core_new_nav_item( $courses_args );

				// Add Sub Tab.
			    bp_core_new_subnav_item(
			    	array(
			            'slug' => 'all',
			            'parent_slug' => 'courses',
			            'name' => __( 'Courses', 'youzify' ),
			            'parent_url' => bp_displayed_user_url()  . 'courses/',
			            'screen_function' => array( $this, 'tutor_courses_screen' ),
					),
			    );

			}

			// // Add Sub Tab.
		    // bp_core_new_subnav_item(
		    // 	array(
		    //         'slug' => 'certificates',
		    //         'parent_slug' => 'courses',
		    //         'name' => __( 'Certificates', 'youzify-fes' ),
		    //         'parent_url' => bp_displayed_user_url()  . 'courses/',
		    //         'screen_function' => array( $this, 'certificates_screen' ),
			// 	),
		    // );

		}

		// //add certificates tab
		// if ( apply_filters( 'youzify_add_profile_certificates_tab', true ) ) {
		//     $certificates_args = apply_filters( 'youzify_profile_certificates_tab_args', array(
		//         'position' => 16,
		//         'slug' => 'certificates',
		//         'parent_slug' => $bp->profile->slug,
		//         'name' => __( 'Certificates', 'youzify' ),
		//         'screen_function' => array( $this, 'certificates_screen' ),
		//     ) );

		//     // Add certificates Tab.
		//     bp_core_new_nav_item( $certificates_args );


		// 	// Add Sub Tab.
		//     bp_core_new_subnav_item(
		//     	array(
		//             'slug' => 'all',
		//             'parent_slug' => 'certificates',
		//             'name' => __( 'Certificates', 'youzify-fes' ),
		//             'parent_url' => bp_displayed_user_url()  . 'certificates/',
		//             'screen_function' => array( $this, 'certificates_screen' ),
		// 		),
		//     );

		// 	// Add Sub Tab.
		//     bp_core_new_subnav_item(
		//     	array(
		//             'slug' => 'certificates',
		//             'parent_slug' => 'certificates',
		//             'name' => __( 'Certificates', 'youzify-fes' ),
		//             'parent_url' => bp_displayed_user_url()  . 'certificates/',
		//             'screen_function' => array( $this, 'certificates_screen' ),
		// 		),
		//     );

		// }

	    // Add Comments Tab.
	    bp_core_new_nav_item( $comments_args );

	    if ( bp_is_active( 'activity' ) ) {

		    // Get Media Slug.
		    $media_slug = youzify_profile_media_slug();

		    $media_args = apply_filters( 'youzify_profile_media_tab_args', array(
		        'position' => 3,
		        'slug' => $media_slug,
		        'parent_slug' => $bp->profile->slug,
		        'default_subnav_slug' => apply_filters( 'youzify_profile_media_default_tab', 'all' ),
		        'name' => __( 'Media', 'youzify' ),
		        'screen_function' => array( $this, 'media_screen' ),
		    ) );

		    // Add Media Tab.
		    bp_core_new_nav_item( $media_args );

	        if ( bp_is_current_component( $media_slug ) ) {

		        // Add Media Sub Pages.
		        foreach ( $this->get_media_subtabs( 'profile' ) as $page ) {

		            if (  $page['slug'] != 'all' && 'on' != youzify_option( 'youzify_show_profile_media_tab_' . $page['slug'], 'on' ) ) {
		                continue;
		            }

		            bp_core_new_subnav_item(
		            	array(
		                    'slug' => $page['slug'],
		                    'name' => $page['title'],
		                    'position' => $page['position'],
		                    'parent_slug' => $media_slug,
		                    'parent_url' => $user_domain . "$media_slug/",
		                    'screen_function' => array( $this, 'media_screen' ),
		                ), 'members'
		            );

		        }

	        }

	        // Check if its a group page.
	        if ( bp_is_groups_component() && bp_is_single_item() && 'on' == youzify_option( 'youzify_enable_groups_media', 'on' ) ) {

		        $group = $bp->groups->current_group;

		        // Add Group 'Media' Nav.
		        bp_core_new_subnav_item(
		            array(
		                'slug' => youzify_group_media_slug(),
		                'parent_slug' => $group->slug,
		                'name' => __( 'Media', 'youzify' ),
		                'parent_url' => bp_get_group_url( $group ),
		                'screen_function' => array( $this, 'groups_media_screen' ),
		                'default_subnav_slug' => 'all',
		                'position' => 12
		            ), 'groups'
		        );

	        }

		    if ( $this->is_user_can_see_bookmarks() ) {

			    bp_core_new_nav_item(
			    	apply_filters( 'youzify_profile_bookmarks_tab_args', array(
				        'position' => 200,
				        'slug' => 'bookmarks',
				        'name' => __( 'Bookmarks' , 'youzify' ),
				        'default_subnav_slug' => 'activities',
				        'parent_slug' => $bp->profile->slug,
				        'screen_function' => array( $this, 'bookmarks_screen' ),
				        // 'parent_url' => bp_displayed_user_url() . "bookmarks/"
				    ) )
			    );

		    }

	    }

	    if ( youzify_is_bpfollowers_active() ) {

			$follow_slug = apply_filters( 'youzify_bpfollowers_follows_tab_slug', 'follows' );

			// Add Follows Tab.
			bp_core_new_nav_item(
			    array(
			        'position' => 100,
			        'slug' => $follow_slug,
			        'name' => __( 'Follows' , 'youzify' ),
			        'default_subnav_slug' => 'following',
			        'parent_slug' => $bp->profile->slug,
			        'screen_function' => array( $this, 'follows_screen' ),
			        // 'parent_url' => bp_loggedin_user_domain() . "$follow_slug/"
			    )
			);

			// Add Following Sub Tab.
		    bp_core_new_subnav_item( array(
		            'slug' => 'following',
		            'name' => __( 'Following', 'youzify' ),
		            'parent_slug' => $follow_slug,
		            'parent_url' => bp_displayed_user_url() . "$follow_slug/",
		            'screen_function' => array( $this, 'follows_screen' ),
		        )
		    );

			// Add Follwers Sub Tab.
		    bp_core_new_subnav_item( array(
		            'slug' => 'followers',
		            'name' => __( 'Followers', 'youzify' ),
		            'parent_slug' => $follow_slug,
		            'parent_url' => bp_displayed_user_url() . "$follow_slug/",
		            'screen_function' => array( $this, 'follows_screen' ),
		        )
		    );

	    }

		if ( defined( 'myCRED_BADGE_VERSION' ) && youzify_is_mycred_active() ) {

			// Add Badges Tab.
			bp_core_new_nav_item(
			    array(
			        'position' => 100,
			        'slug' => apply_filters( 'youzify_mycred_badges_slug', 'badges' ),
			        'parent_slug' => $bp->profile->slug,
			        'name' => youzify_option( 'youzify_mycred_badges_tab_title', __( 'Badges', 'youzify' ) ),
			        'screen_function' => 'youzify_profile_mycred_badges_tab_screen',
			    )
			);

		}

		if ( defined( 'GAMIPRESS_VER' ) && youzify_is_gamipress_active() ) {

			// Add Badges Tab.
			bp_core_new_nav_item(
			    array(
			        'position' => 100,
			        'slug' => apply_filters( 'youzify_gamipress_badges_slug', 'gamipress_badges' ),
			        'parent_slug' => $bp->profile->slug,
			        'name' => youzify_option( 'youzify_gamipress_badges_tab_title', __( 'Badges', 'youzify' ) ),
			        'screen_function' => 'youzify_profile_gamipress_badges_tab_screen',
			    )
			);

		}

	    // Add My Profile Page.
	    bp_core_new_nav_item(
	        array(
	            'position' => 200,
	            'slug' => 'youzify-home',
	            'parent_slug' => $bp->profile->slug,
	            'show_for_displayed_user' => bp_core_can_edit_settings(),
	            'default_subnav_slug' => 'youzify-home',
	            'name' => __( 'My Profile', 'youzify' ),
	            'parent_url' => bp_loggedin_user_domain() . '/youzify-home/'
	        )
	    );

	    // Get Custom Tabs.
	    $custom_tabs = youzify_option( 'youzify_custom_tabs' );

	    if ( ! empty( $custom_tabs ) ) {

	    	// Get Youzify Tabs
    		$tabs = youzify_option( 'youzify_profile_tabs' );

    		$restricted_custom_tabs = apply_filters( 'youzify_restricted_custom_tabs', array() );

		    foreach ( $custom_tabs as $tab_id => $data ) {

		        // Hide Tab For Non Logged-In Users.
		        if ( 'false' == $data['display_nonloggedin'] && ! is_user_logged_in() ) {
		            continue;
		        }

		        // Get Slug.
		        $tab_slug = $data['type'] == 'shortcode' ? $data['slug'] : $tab_id;

		        // Add New Tab.
		        if ( isset( $tabs[ $tab_slug ]['deleted'] ) ) {
		        	continue;
		        }

		        if ( in_array( $tab_slug, $restricted_custom_tabs ) ) {
		        	continue;
		        }

		        bp_core_new_nav_item(
		            array(
		                'position' => 100,
		                'slug' => $tab_slug,
		                'name' => apply_filters( 'youzify_custom_profile_tab_title', $data['title'], $tab_slug ),
		                'default_subnav_slug' => $tab_slug,
		                'screen_function' => array( $this, 'custom_tabs_screen' ),
		            )
		        );

		        if ( $data['type'] == 'link' ) {

		        	$user_id = bp_displayed_user_id() ? bp_displayed_user_id() : bp_loggedin_user_id();

				    // Get Displayed profile username.
				    $displayed_username = bp_members_get_user_slug( $user_id );

				    // Replace Tags.
				    $tab_link = wp_kses_decode_entities( str_replace( '{username}', $displayed_username, $data['link'] ) );

				    // Edit Nav.
				    $bp->members->nav->edit_nav( array( 'link' => $tab_link ), $tab_slug );

				    // Add New Tag
				    $bp->members->nav->edit_nav( array( 'custom_link' => true ), $tab_slug );

		        }

	    	}
	    }

	    do_action( 'youzify_add_new_profile_tabs' );
	}

    /**
     * Overview Screen.
     */
    function overview_screen() {

	    require_once YOUZIFY_CORE . 'tabs/class-youzify-tab-overview.php';

		$overview = new Youzify_Overview_Tab();

        // Call Posts Tab Content.
        add_action( 'bp_template_content', array( $overview, 'tab' ) );

        // Load Tab Template
        bp_core_load_template( 'buddypress/members/single/plugins' );

    }

    /**
     * Info Screen.
     */
    function info_screen() {

	    require_once YOUZIFY_CORE . 'tabs/class-youzify-tab-info.php';

		$info = new Youzify_Info_Tab();

        // Call Tab Content.
        add_action( 'bp_template_content', array( $info, 'tab' ) );

        // Load Tab Template
        bp_core_load_template( 'buddypress/members/single/plugins' );

    }

    /**
     * Posts Screen.
     */
    function posts_screen() {

    	// Styling.
		youzify_styling()->custom_styling( 'posts' );

	    require_once YOUZIFY_CORE . 'tabs/class-youzify-tab-posts.php';

		$posts = new Youzify_Posts_Tab();

        // Call Posts Tab Content.
        add_action( 'bp_template_content', array( $posts, 'tab' ) );

        // Load Tab Template
        bp_core_load_template( 'buddypress/members/single/plugins' );

    }
    
	/**
     * Courses Screen.
     */
    function courses_screen() {

    	// Styling.
		youzify_styling()->custom_styling( 'courses' );

	    require_once YOUZIFY_CORE . 'tabs/class-youzify-tab-courses.php';

		$courses = new Youzify_Courses_Tab();

        // Call courses Tab Content.
        add_action( 'bp_template_content', array( $courses, 'tab' ) );

        // Load Tab Template
        bp_core_load_template( 'buddypress/members/single/plugins' );

    }
	
	/**
     * Tutor Courses Screen.
     */
    function tutor_courses_screen() {

    	// Styling.
		youzify_styling()->custom_styling( 'courses' );

	    require_once YOUZIFY_CORE . 'tabs/class-youzify-tab-tutor-courses.php';

		$courses = new Youzify_Tutor_Courses_Tab();

        // Call courses Tab Content.
        add_action( 'bp_template_content', array( $courses, 'tab' ) );

        // Load Tab Template
        bp_core_load_template( 'buddypress/members/single/plugins' );

    }

	/**
     * Posts Screen.
     */
    function certificates_screen() {

    	// Styling.
		youzify_styling()->custom_styling( 'certificates' );

	    require_once YOUZIFY_CORE . 'tabs/class-youzify-tab-certificates.php';

		$certificates = new Youzify_Certificates_Tab();

        // Call certificates Tab Content.
        add_action( 'bp_template_content', array( $certificates, 'tab' ) );

        // Load Tab Template
        bp_core_load_template( 'buddypress/members/single/plugins' );

    }

    /**
     * Comments Screen.
     */
    function comments_screen() {

    	// Styling.
		youzify_styling()->custom_styling( 'comments' );

	    require_once YOUZIFY_CORE . 'tabs/class-youzify-tab-comments.php';

		$comments = new Youzify_Comments_Tab();

        // Call Posts Tab Content.
        add_action( 'bp_template_content', array( $comments, 'tab' ) );

        // Load Tab Template
        bp_core_load_template( 'buddypress/members/single/plugins' );

    }

	/**
	 * Tab Core.
	 */
	function core( $args ) { ?>

		<div class="youzify-tab <?php echo 'youzify-' . $tab_name; ?>">
			<?php $this->{$args['tab_name']}->tab_content(); ?>
		</div>

		<?php

	}

	/**
	 * Get Follows Tab Screen Function.
	 */
	function follows_screen() {

		do_action( 'bp_follow_screen_following' );

	    add_action( 'bp_template_content', array( $this, 'get_user_following_template' ) );

	    // Load Tab Template
	    bp_core_load_template( 'buddypress/members/single/plugins' );
	}

	/**
	 * Get Follows Tab Content.
	 */
	function get_user_following_template() {
		bp_get_template_part( 'members/single/follows' );
	}

    /**
     * Bookmarks Screen.
     */
    function bookmarks_screen() {

	    require YOUZIFY_CORE . 'tabs/class-youzify-tab-bookmarks.php';

		$bookmarks = new Youzify_Bookmarks_Tab();

        // Call Posts Tab Content.
        add_action( 'bp_template_content', array( $bookmarks, 'tab' ) );

        // Load Tab Template
        bp_core_load_template( 'buddypress/members/single/plugins' );

    }

    /**
     * Custom Tabs
     */
    function custom_tabs_screen() {

        require_once YOUZIFY_CORE . 'tabs/class-youzify-custom-tabs.php';

		$custom = new Youzify_Custom_Tabs();

        // Call Posts Tab Content.
        add_action( 'bp_template_content', array( $custom, 'tab' ) );

        // Load Tab Template
        bp_core_load_template( 'buddypress/members/single/plugins' );

    }

	/**
	 * Check is User Can see Bookmarks.
	 */
	function is_user_can_see_bookmarks() {

		// Init var.
		$visibility = false;

		if ( bp_core_can_edit_settings() ) {
			$visibility = true;
		} else {

			// Get Who can see bookmarks.
			$privacy = youzify_option( 'youzify_enable_bookmarks_privacy', 'private' );

			switch ( $privacy ) {

				case 'public':
					$visibility = true;
					break;

				case 'private':
					$visibility = bp_core_can_edit_settings() ? true : false;
					break;

				case 'loggedin':
					$visibility = is_user_logged_in() ? true : false;
					break;

				case 'friends':

					if ( bp_is_active( 'friends' ) ) {

						// Get User ID
						$loggedin_user = bp_loggedin_user_id();

						// Get Profile User ID
						$profile_user = bp_displayed_user_id();

						$visibility = friends_check_friendship( $loggedin_user, $profile_user ) ? true : false;

					}

					break;

				default:
					$visibility = false;
					break;

			}

		}

		return apply_filters( 'youzify_is_user_can_see_bookmarks', $visibility );

	}

    /**
     * Media Screen.
     */
    function media_screen() {

	    require YOUZIFY_CORE . 'tabs/class-youzify-tab-media.php';

		$media = new Youzify_Media_Tab();

        // Call Tab Content.
        add_action( 'bp_template_content', array( $media, 'tab' ) );

        // Load Tab Template
        bp_core_load_template( 'buddypress/members/single/plugins' );

    }

    /**
     * Get Group Tab.
     */
    function groups_media_screen() {

	    require YOUZIFY_CORE . 'tabs/class-youzify-tab-media.php';
		$media = new Youzify_Media_Tab();

		global $bp;

        $group = $bp->groups->current_group;

        // Get Media Slug.
        $group_media_slug = youzify_group_media_slug();

        // Add Media Sub Pages.
        foreach ( $this->get_media_subtabs( 'group' ) as $page ) {

            if ( $page['slug'] != 'all' && 'on' != youzify_option( 'youzify_show_group_media_tab_' . $page['slug'], 'on' ) ) {
                continue;
            }

            bp_core_new_subnav_item(
            	array(
                    'slug' => $page['slug'],
                    'name' => $page['title'],
		            'position' => $page['position'],
                    'parent_slug' => $group_media_slug,
                    'item_css_id' => 'media-' . $page['slug'],
                    'parent_url' => bp_get_group_url( $group )  . "$group_media_slug/",
                    'screen_function' => array( $this, 'groups_media_screen' ),
                ), 'groups'
            );

        }

        // Call Media Tab Content.
        add_action( 'bp_template_content', array( $media, 'group_tab' ) );

        // Load Tab Template
        bp_core_load_template( 'buddypress/groups/single/plugins' );

    }

	/**
	 * Get Media Subtabs.
	 */
	function get_media_subtabs( $component ) {

		return apply_filters( 'youzify_' . $component . '_media_subtabs', array(
	        'all' => array(
	            'title' => __( 'All', 'youzify' ),
	            'slug' => 'all',
	            'position' => 10,
	        ),
	        'photos' => array(
	            'title' => __( 'Photos', 'youzify' ),
	            'slug' => 'photos',
	            'position' => 20
	        ),
	        'videos' => array(
	            'title' => __( 'Videos', 'youzify' ),
	            'slug' => 'videos',
	            'position' => 30
	        ),
	        'audios' => array(
	            'title' => __( 'Audios', 'youzify' ),
	            'slug' => 'audios',
	            'position' => 40
	        ),
	        'files' => array(
	            'title' => __( 'Files', 'youzify' ),
	            'slug' => 'files',
	            'position' => 50
	        )
	    )

		);

	}

}

new Youzify_Tabs();