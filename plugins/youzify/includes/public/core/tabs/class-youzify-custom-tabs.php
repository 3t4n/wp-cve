<?php

class Youzify_Custom_Tabs {

    public function __construct() {
        $this->hide_custom_tab_sidebar();
    }

    /**
     * Custom Tab Hide Sidebar
     */
    function hide_custom_tab_sidebar() {

        if ( 'youzify-horizontal-layout' != youzify_get_profile_layout() ) {
            return;
        }

        // Get Custom Tabs
        $data = $this->get_all_data( $this->get_current_tab_name() );

		if ( 'false' == $data['display_sidebar'] ) {

			// Remove Old Structure.
	    	remove_action( 'youzify_profile_main_content', array( youzify_profile(), 'profile_main_content' ) );

	    	// Add Wild Content.
			add_action( 'youzify_profile_main_content', array( $this, 'tab' ) );

		}

    }

	/**
	 * Tab Core
	 */
	function tab() {

        // Hide sidebar if profile is private.
        if ( ! youzify_display_profile() ) {
            youzify_private_account_message();
            return false;
        }

        // Get Tab Content.
        echo '<div class="youzify-tab youzify-custom">';
        $this->tab_content();
        echo '</div>';

	}

	/**
	 * Tab Content
	 */
	function tab_content() {

        // Get Custom Tabs
        $data = $this->get_all_data( $this->get_current_tab_name() );

        // Get Content
        $content = urldecode( $data['content'] );

        // Filter Content
        $content = youzify_convert_content_tags( $content );

        // Display Widget.
        echo "<div class='youzify-custom-tab'>";
        echo apply_filters( 'the_content', $content );
        echo "</div>";

	}

    /**
     * Get Custom Widget data.
     */
    function get_all_data( $tab_name ) {
        $tabs = youzify_option( 'youzify_custom_tabs' );
        return $tabs[ $tab_name ];
    }

    /**
     * Get Args.
     */
    function get_args() {

        // Get Custom Tabs
        $data = $this->get_all_data( $this->get_current_tab_name() );

		// Get Custom Tab Args.
		$args = array(
			'tab_name'    => 'custom',
			'tab_title'   => $data['title'],
            'tab_slug'	  => isset( $data['slug'] ) ? $data['slug'] : youzify_get_custom_tab_slug( $data['title'] )
		);

		return $args;
    }

    /**
     * Get Current Tab name.
     */
    function get_current_tab_name() {
    	return youzify_get_tab_name_by_slug( bp_current_action() );
    }

    /**
     * Get Custom Tab data.
     */
    function get_tab_data( $tab_name, $data_type ) {
        $tabs = youzify_option( 'youzify_custom_tabs' );
        return $tabs[ $tab_name ][ $data_type ];
    }
}