<?php

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/screen.php' );
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Click5_Grid_Plugins extends WP_List_Table { 
    public function __construct() {
		parent::__construct(
			array(
				'post_type' => 'c5_history_plugins',
				'plural'    => 'records',
				'screen'    => null,
			)
		);
        global $wpdb;
		$prefix = apply_filters( 'wp_c5_history_db_tables_prefix', $wpdb->base_prefix );
        add_screen_option(
            'per_page',
            array(
                'default' => 999,
                'label'   => __( 'Records per page', 'c5_history_plugins' ),
                'option'  => 'edit_c5_history_plugins_per_page',
            )
        );
		set_screen_options();	
    }

    public function no_items() {
		?>
			<div class="c5_history-list-table-no-items">
				<p><?php esc_html_e( 'No history to display.', 'c5_history_plugins' ); ?></p>
			</div>
		<?php
	}

    public function get_columns() {
		return apply_filters(
			'wp_c5_history_plugins_list_table_columns',
			array(
				'name' => __( 'Plugin Name', 'c5_history_plugins' ),
				'support' => __( "<div style='text-align: center;'>Tracking Support</div>", 'c5_history_plugins' ),
				'track' => __( "<div style='text-align: center;'>Disable / Enable</div>", 'c5_history_plugins' ),
			)
		);
	}

    public function columns_sortable() {
		return array(
			//'name' => array( 'name', false ),
		);
	}

    public function prepare_items() {  
    $current_color = get_user_option( 'admin_color' ); 
    $background_color = get_background_color();
    
      $post_data = get_plugins();
    
    $support_plugin_list = array(
    "advanced-custom-fields/acf.php", 
    "acf-repeater/acf-repeater.php", 
    "all-in-one-seo-pack/all_in_one_seo_pack.php",
    "all-in-one-seo-pack-pro/all_in_one_seo_pack.php",
    "classic-editor/classic-editor.php",
    "contact-form-7/wp-contact-form-7.php",
    "disable-comments-by-click5/disable-comments-by-click5.php",
    "google-site-kit/google-site-kit.php",
    "history-log-by-click5/history-log-by-click5.php",
    "seo-by-rank-math/rank-math.php",
    "seo-by-rank-math-pro/rank-math-pro.php",
    "sitemap-by-click5/sitemap-by-click5.php",
    "wordfence/wordfence.php",
    "wordpress-seo/wp-seo.php",
    "wordpress-seo-premium/wp-seo-premium.php",
    "cf7-add-on-by-click5/cf7-addon-by-click5.php",
    "wpf-add-on-by-click5/wpf-addon-by-click5.php",
    "gf-add-on-by-click5/gf-addon-by-click5.php",
    "click5-crm-add-on-to-ninja-forms/ninja-addon-by-click5.php",
    "wpforms-lite/wpforms.php",
    "wpforms/wpforms.php",
    "ninja-forms/ninja-forms.php",
    "advanced-custom-fields-pro/acf.php",
    "better-search-replace/better-search-replace.php",
    "better-search-replace-pro/better-search-replace.php",
    "redirection/redirection.php",
    "health-check/health-check.php",
    "classic-widgets/classic-widgets.php",
    "instagram-feed/instagram-feed.php",
    "wp-google-maps/wpGoogleMaps.php",
    "wp-google-maps-pro/wp-google-maps-pro.php",
    "jetpack/jetpack.php",
    "duplicate-post/duplicate-post.php",
    "all-in-one-wp-migration/all-in-one-wp-migration.php",
    "updraftplus/updraftplus.php",
    "duplicator/duplicator.php",
    "loco-translate/loco.php",
    "polylang/polylang.php",
    "limit-login-attempts-reloaded/limit-login-attempts-reloaded.php",
    "sg-cachepress/sg-cachepress.php",
    "user-role-editor/user-role-editor.php",
    "backwpup/backwpup.php",
    "string-locator/string-locator.php",
    "wp-mail-log/wp-mail-log.php"
  );
    $data = array();
    
   
    foreach($post_data as $key => $item) {
      if ( is_plugin_active($key) ) {
          $support = "no";
          $track = '';
          $track_enable = false;
          $user_name_log = wp_get_current_user()->user_login;
          foreach($support_plugin_list as $support_plugin_item) {
            if($support_plugin_item == $key) {
              $plugin_version = get_plugin_data(WP_PLUGIN_DIR . '/' . $support_plugin_item)['Version'];
              global $min_version_support_plugin_list;
              if(isset($min_version_support_plugin_list[$support_plugin_item]))
              {
                if(version_compare($plugin_version,$min_version_support_plugin_list[$support_plugin_item]) > 0){
                  $support = "yes";
                  $track_enable = true;
                  
                }else if(!isset($min_version_support_plugin_list[$support_plugin_item])){
                  $support = "yes";
                  $track_enable = true;
                }
              }
              else
              {
                $support = "yes";
                $track_enable = true;
              }
              if($track_enable){
                if(get_option('click5_history_log_' . $support_plugin_item) == "1") {
                  /*$track = '<div style="text-align: center;"><input onChange="setPluginSupport(this)"  user=' . $user_name_log . ' type="checkbox" checked id=' . $key . '
                      class="checkbox widgets-chooser-selected" style="display: none" />
                      <label for=' . $key . ' class="toggle"></div>';*/
                  $track = '<div style="text-align: center;"><label class="switch">
                      <input type="checkbox" onChange="setPluginSupport(this)"  user=' . $user_name_log . ' checked id=' . $key . '>
                      <span class="slider round"></span>
                      </label for=' . $key . '></div>';    
                }
                if(get_option('click5_history_log_' . $support_plugin_item) == "0") {
                  /*$track = '<div style="text-align: center;"><input onChange="setPluginSupport(this)" user=' . $user_name_log . ' type="checkbox" id=' . $key . '
                      class="checkbox widgets-chooser-selected" style="display: none" />
                      <label for=' . $key . ' class="toggle"></div>';*/
                  $track = '<div style="text-align: center;"><label class="switch">
                      <input type="checkbox" onChange="setPluginSupport(this)" user=' . $user_name_log . ' id=' . $key . '>
                      <span class="slider round"></span>
                      </label for=' . $key . '></div>';
                }
              }
            }
          }
          array_push($data,
          array(
            'name' => $item['Name'],
            'support' => "$support",
            'track' => $track,
          )
        ); 
      }
    }    

    $existClick5_Sitemap =  false;
    foreach($data as $item)
    {
      if($item["name"] == "Sitemap by click5"){
        $existClick5_Sitemap = true;
        break;
      }
    }
    
    if(!$existClick5_Sitemap)
    {

      $c5SiteMap_Pathpluginurl = WP_PLUGIN_DIR . '/sitemap-by-click5/sitemap-by-click5.php';
      $c5SiteMap_IsInstalled = file_exists( $c5SiteMap_Pathpluginurl );

      if($c5SiteMap_IsInstalled)
      {
        $click5_install_link =  home_url().'/wp-admin/plugins.php?action=activate&plugin=sitemap-by-click5%2Fsitemap-by-click5.php';
        $track = '<div style="text-align: center;"><a href="history-log-by-click5.php?install-sitemap-by-click5=true">Activate</a></div>';
        array_push($data,
            array(
              'name' => "Sitemap by click5",
              'support' => "yes",
              'track' => $track,
            )
          );
      }
      else
      {

      $click5_install_link =  home_url().'/wp-admin/plugin-install.php?s=sitemap%20by%20click5&tab=search&type=term';
      $track = '<div style="text-align: center;"><a href='.$click5_install_link.'>Install Plugin</a></div>';
      array_push($data,
          array(
            'name' => "Sitemap by click5",
            'support' => "yes",
            'track' => $track,
          )
        );
      }
        if(isset($data))
        {
        usort($data, function($x, $s) {
          return strcmp(strtolower($x["name"]), strtolower($s["name"]));
        });
        }
        
    }


    $this->_column_headers = array(
			$this->get_columns(),
            array(),
            $this->columns_sortable(),
			$this->get_columns()['name']
		);
        $this->items = $data;
		$this->set_pagination_args(
			array(
				//'total_items' => 3,
				'per_page'    => $this->get_items_per_page( 'edit_c5_history_plugins_per_page', 20 ),
			)
		);
    }

    public function display() {
		parent::display();
	}

    public function column_default( $data, $column ) {
		$out    = '';
		switch ( $column ) {
			case 'name':
				$out =  "<b>" . $data["name"] . "</b>";
				break;

			case 'support':
        if($data["support"] == "yes") {
          $out = "<div style='text-align: center;'><span>&#10003;</span></div>";
        } else {
          $out = "<div style='text-align: center;'><span>&#x2715;</span></div>";
        }
				break;

			case 'track':
				return $data[$column];
				//reak;	
		

			default:
				$out = "no data";
        
		}

		$tags = wp_kses_allowed_html( 'post' );
		$tags['time'] = array('datetime' => true, 'class' => true);
		$tags['img']['srcset'] = true;
		echo wp_kses( $out, $tags );
	}

}   