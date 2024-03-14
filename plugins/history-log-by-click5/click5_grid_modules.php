<?php

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/screen.php' );
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Click5_Grid_modules extends WP_List_Table { 
    public function __construct() {
		parent::__construct(
			array(
				'post_type' => 'c5_history_modules',
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
                'label'   => __( 'Records per page', 'c5_history_modules' ),
                'option'  => 'edit_c5_history_modules_per_page',
            )
        );
		set_screen_options();	
    }

    public function no_items() {
		?>
			<div class="c5_history-list-table-no-items">
				<p><?php esc_html_e( 'No modules to display.', 'c5_history_modules' ); ?></p>
			</div>
		<?php
	}

    public function get_columns() {
		return apply_filters(
			'wp_c5_history_modules_list_table_columns',
			array(
				'name' => __( 'Module Name', 'c5_history_modules' ),
				'track' => __( "<div style='text-align: center;'>Disable / Enable</div>", 'c5_history_modules' ),
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
    
    $support_module_list = array(
      "404_error" => array("name" => "404 Errors", "default" => "0"),
      "media" => array("name" => "Media", "default" => "1"),
      "menu" => array("name" => "Menu", "default" => "1"),
      "pages" => array("name" => "Pages", "default" => "1"),
      "plugins" => array("name" => "Plugins", "default" => "1"),
      "posts" => array("name" => "Posts", "default" => "1"),
      "settings" => array("name" => "Settings", "default" => "1"),
      "site_health" => array("name" => "Site Health", "default" => "1"),
      "themes" => array("name" => "Themes", "default" => "1"),
      "users" => array("name" => "Users", "default" => "1"),
      "widgets" => array("name" => "Widgets", "default" => "1"),
      "wordpress_core" => array("name" => "WordPress Core", "default" => "1")
    );

    if(defined('WPE_CACHE_PLUGIN_VERSION') === true){
      $support_module_list["wp_engine"] = array("name" => "WP Engine", "default" => "1");
    }

    $data = array();
    
          $user_name_log = wp_get_current_user()->user_login;
          foreach($support_module_list as $optionName => $support_plugin_item) {

            if(get_option('click5_history_log_module_' . $optionName) == "1") {
                  $track = '<div style="text-align: center;"><label class="switch">
                      <input type="checkbox" onChange="setModuleSupport(this)" data-name="'.$support_plugin_item['name'].'"  user=' . $user_name_log . ' checked id=' . $optionName . '>
                      <span class="slider round"></span>
                      </label for=' . $optionName . '></div>';    
                }
                if(get_option('click5_history_log_module_' . $optionName) == "0") {
                  $track = '<div style="text-align: center;"><label class="switch">
                      <input type="checkbox" onChange="setModuleSupport(this)" data-name="'.$support_plugin_item['name'].'" user=' . $user_name_log . ' id=' . $optionName . '>
                      <span class="slider round"></span>
                      </label for=' . $optionName . '></div>';
                }

                array_push($data,
                array(
                  'name' => $support_plugin_item['name'],
                  'track' => $track,
                )
              );  
          }  

        /*if(isset($data))
        {
        usort($data, function($x, $s) {
          return strcmp(strtolower($x["name"]), strtolower($s["name"]));
        });
        }*/


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
				'per_page'    => $this->get_items_per_page( 'edit_c5_history_modules_per_page', 20 ),
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