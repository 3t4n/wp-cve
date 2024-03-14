<?php

/**
 * WordPress settings API
 *
 *
 */
if ( !class_exists('WeDevs_Settings_API_Match' ) ):
class WeDevs_Settings_API_Match {

    private $settings_api;

    function __construct() {
        $this->settings_api = new WeDevs_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_options_page( 'BP Match', 'BP Match', 'delete_posts', 'mp_bp_match', array($this, 'plugin_page') );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'hmk_percentages',
                'title' => __('Percentage Settings', 'wedevs' ),
                'desc' => __( 'You can set percentage for each xprofile field here. Please make sure total of percentage of all fields does not cross 100', 'wedevs' )
            ),

        );
        return $sections;
    }

	 function hmk_build_field_options() {
	    global $wpdb;
		$hmk_fields=  array();

    if ( is_multisite() ) {

      $bp_blog_id = BP_ROOT_BLOG;
      $base_prefix = $wpdb->get_blog_prefix($bp_blog_id);

      $hmk_db_prefix = $base_prefix;

    }else{

      $hmk_db_prefix = $wpdb->prefix;

    }



		$xprofile_table =  $hmk_db_prefix.'bp_xprofile_fields';

    if($wpdb->get_var("SHOW TABLES LIKE '$xprofile_table'") != $xprofile_table) {

      return;
    }

		$sql = "SELECT id,name FROM $xprofile_table WHERE type !='option'";

	  $result = $wpdb->get_results($sql);

    

    if(empty($result)) {

      return;

    }

		foreach( $result as $results ) {

			$fd_name = $results->name;
			$fd_id = $results->id;
			 $hmk_fields[] = array(
						'name'              => 'hmk_field_percentage_'.$fd_id ,
						'label'             => $fd_name,
						'type'              => 'text',
						'size'              => 'small',
						'default'           => 0,
						'sanitize_callback' => 'intval'
					);
		   }


		return $hmk_fields;

	 }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
	    $hmk_build_field_options = $this->hmk_build_field_options();
        $settings_fields = array(
            'hmk_percentages' => $hmk_build_field_options

        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap mp-container">';

        echo "<div class='mp-settings-content'>";

        echo "<h1> Match me for BuddyPress by MeshPros </h1>";
        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();
        
        echo "</div>";


        echo '</div>';

    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;
