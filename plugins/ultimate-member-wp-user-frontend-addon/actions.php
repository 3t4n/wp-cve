<?php
    $roles = get_option('settings_basic');
    if(is_array($roles)){
      $slugadd = $roles['slug_add'];
      $slugview = $roles['slug_view'];
    }else{
      $slugadd = 'add-post';
      $slugview = 'my-posts';
    }
    if(is_array($roles) && !empty($roles['id_form']) ){
      $formid = $roles['id_form'];
    }
    /* Zakładka moje wpisy w profilu */
    add_filter('um_profile_tabs', 'moje_wpisy_tab', 1000 );
    function moje_wpisy_tab( $tabs  ) {
		global $slugview;

		$tabs[$slugview] = array(
			'name' => __( 'My posts', 'ufa' ),
			'icon' => 'um-faicon-pencil-square',
			'custom' => true
		);

		return $tabs;
    }

    add_action('um_profile_content_'.$slugview.'_default', 'moje_wpisy_tab_default');
    function moje_wpisy_tab_default( $args ) {
      echo do_shortcode('[wpuf_dashboard_new]');
    }
    
    /* Zakładka dodaj wpis w profilu */
    add_filter('um_profile_tabs', 'dodaj_wpis_tab', 1010 );
    function dodaj_wpis_tab( $tabs ) {
        global $slugadd;
        $tabs[$slugadd] = array(
          'name' => __( 'Add post', 'ufa' ),
          'icon' => 'um-faicon-pencil-square-o',
			'custom' => true
        );
        return $tabs;
    }
    add_action('um_profile_content_'.$slugadd.'_default', 'dodaj_wpis_tab_default');
    function dodaj_wpis_tab_default( $args ) {
      global $formid;
      if(isset($formid)){
        echo do_shortcode('[wpuf_form id="'.$formid.'"]');
      }else{
        echo __('Add form ID in Options', 'ufa');
      }
    }

    if(is_array($roles)){

      add_filter('um_user_profile_tabs', 'disable_user_tab', 1000 );

      function disable_user_tab( $tabs ) {
		global $roles, $slugview, $slugadd;
		$user_id = um_user('ID');
		um_fetch_user( $user_id );
		$role = UM()->user()->get_role();
		if(!um_is_myprofile() || !in_array($role, $roles['roles'])){
			unset( $tabs[$slugadd] );
			unset( $tabs[$slugview] );
		}

		return $tabs;
      }

    }
?>