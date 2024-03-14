<?php
/**
 * Page Links Options
 *
 * @category Page_Links_Options
 * @package Page_Links
*/



/**
 * Page Links Options class
 * Sets up the option page and groups.
 *
 * @category Page_Links_Options
 * @package Page_Link
 */
class SH_PageLinks_Options
{



    /**
     * Handle for menu page.
     *
     * @var string
     */
    protected $menu_page;

	/**
	 * Option group name
	 *
	 * @var string
	 */
    protected $option_group_name = "sh_page_links_options";

    /**
     * PHP5 constructor function
     *
     * @return void
     */
    public function __construct($args = array())
    {
        add_action("{$this->option_group_name}_option_fields", array($this, 'set_options_fields'), 10, 1);
        add_action("{$this->option_group_name}_option_section", array($this, 'set_options_sections'), 10, 1);
        add_action('admin_init', array($this, 'options_init'));
        add_action('admin_menu', array($this, 'admin_menu'));
    }
 
    /**
     * admin_menu()
     * Hook function for admin_menu hook.
     *
     * @return void
     */
    public function admin_menu()
    {
        
        global $menu_page;
        if (empty($menu_page)){
            $menu_page = add_menu_page(
                __('Page-Links Plus', SH_PAGE_LINKS_DOMAIN),
                __('Page-Links Plus', SH_PAGE_LINKS_DOMAIN),
                'manage_options',
                'sh-page-links-options',
                array($this, 'show_menu_page'),
                SH_PAGE_LINKS_URL . 'images/logo-16x16.png'
            );
            add_action('admin_print_styles-' . $menu_page, array($this, 'enqueue_scripts'));
            add_submenu_page(
                'sh-page-links-options',
                __('Single Page', SH_PAGE_LINKS_DOMAIN),
                __('Single Page', SH_PAGE_LINKS_DOMAIN),
                'manage_options',
                'sh-page-links-options',
                array($this, 'show_menu_page')
            );
        }
        add_action( 'admin_enqueue_scripts', array($this, 'admin_styles') );
    }
 
    /**
     * admin_styles()
     * Enqueue special style for admin
     *
     * @return void
     */
    public function admin_styles()
    {
        
        return wp_enqueue_style( 'plp-global' );
    }



    /**
     * Enqueue scripts
     *
     * @return void
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script('jquery-ui', null, array('jquery'));
        wp_enqueue_script('jquery-ui-tabs', null, array('jquery'));
		
		wp_register_script('admin-menu-current', plugins_url('js/admin-menu-current.min.js', __FILE__),array('jquery'),SH_PAGE_LINKS_VER);	
        wp_register_script('pagination_option_validation', plugins_url('js/pagination_option_validation.min.js', __FILE__),array('jquery'), SH_PAGE_LINKS_VER);

        wp_enqueue_script('admin-menu-current');
		wp_enqueue_script('pagination_option_validation');
        wp_enqueue_style('jquery-ui-smoothness');
    }



    /**
     * Display options page
     * @return void
     */
    public function show_menu_page()
    {
        include_once SH_PAGE_LINKS_PATH . 'pages/page-plugin-options.php';
    }



	/**
	 * Registers options group. Performs all processing for
	 * adding options fields and settings
	 *
	 * @return void
	 */
    public function options_init()
    {
        /*
         * register_setting()
         * Settings should be stored as an array in the options table to
         * limit the number of queries made to the DB. The option name should
         * be the same as the option group.
         *
         * Using the options group in a page registered with add_options_page():
         * settings_fields($my_options_class->get_optiongroup_name())
         */
        register_setting(
            $this->option_group_name,
            $this->option_group_name,
            array($this, 'sanitize_options')
        );
        $sections = apply_filters("{$this->option_group_name}_option_sections", array());
        foreach ($sections as $section_name => $data) {
            add_settings_section(
                "{$this->option_group_name}-{$section_name}",
                $data['title'],
                array($this, 'settings_section_cb'),
                "{$this->option_group_name}-{$section_name}"
            );
        }
        $this->output_settings_fields();
    }



    /**
	 * Output setting fields
	 *
     * @return void
     */
    public function output_settings_fields()
    {
        $field_sections = apply_filters("{$this->option_group_name}_option_fields", array());
        foreach ($field_sections as $field_section => $field) {
            foreach ($field as $field_name => $field_data) {
                add_settings_field(
                    "{$field_section}_options-{$field_data['id']}",
                    (isset($field_data['title']) ? $field_data['title'] : " "),
                    $field_data['callback'],
                    "{$this->option_group_name}-{$field_section}",
                    "{$this->option_group_name}-{$field_section}",
                    array_merge(array('name' => $field_name), $field_data, array('section' => $field_section))
                );
            }
        }
    }



	/**
	 * Returns the options sections.
	 *
	 * @return array
	 */
    public function get_sections()
    {
        return apply_filters("{$this->option_group_name}_option_sections", array());
    }



	/**
	 * Returns the options fields.
	 *
	 * @return array
	 */
    public function get_fields()
    {
        return apply_filters("{$this->option_group_name}_option_fields", array());
    }



	/**
	 * Helper method for setting fields, used to create *_option_fields hook
	 * for other plugins to add fields.
	 *
	 * @return array
	 */
    public function set_options_fields($fields = array())
    {
        return $fields;
    }



	/**
	 * Helper method for setting sections, used to create *_option_section hook
	 * for other plugins to add sections.
	 *
	 * @return array
	 */
    public function set_options_sections($sections = array())
    {
        return $sections;
    }



    /**
     *
     * settings_section_cb()
     * Outputs Settings Sections
     *
     * @param string $section Name of section
     * @return void
     */
    public function settings_section_cb($section)
    {
        $options = $this->get_sections();
        $current = (substr($section['id'], strpos($section['id'], '-') + 1));
        echo "<p>{$options[$current]['description']}</p>";
    }



    /**
     * Output option fields
     *
     * @param mixed $option Current option to output
     * @return string
     */
    public static function settings_field_cb($option)
    {
        global $sh_page_links;
        $option_str    = "";

        $option_values = $sh_page_links->get_options();
        if (isset($option['type'])) {
            if ($option['type'] == 'checkbox') {
                $value = !empty($option_values[$option['section']][$option['name']])
                            ? intval($option_values[$option['section']][$option['name']])
                            : 0;
                $description = isset($option['description']) ? $option['description'] : '';
                $option_str = "<label for=\"{$option['id']}\">"
                            . "<input type=\"checkbox\" "
                            . "name=\"sh_page_links_options[{$option['section']}]"
                            . "[{$option['name']}]\" "
                            . "id=\"{$option['id']}\" "
                            . "value=\"{$option['default']}\" "
                            . checked($option['default'], $value, false)
                            . " /></label> </td><td class='description'>{$description}";
            }
            if ($option['type'] == 'select') {
                $value = !empty($option_values[$option['section']][$option['name']])
                            ? intval($option_values[$option['section']][$option['name']])
                            : 0;
                $description = isset($option['description']) ? $option['description'] : '';
                $option_str = "<select "
                            . "name=\"sh_page_links_options[{$option['section']}]"
                            . "[{$option['name']}]\" "
                            . "id=\"{$option['id']}\" >";

                foreach ($option['choices'] as $key => $choice) {
                    $option_str .= '<option value="'. $key .'" '. selected( $key, $value, false ) .'>'. $choice . '</option>';
                }
                $option_str .= "</select></td><td class='description'>{$description}";
            }

            if ($option['type'] == 'multicheckcp') {
                $cps =  get_post_types(array('public' => true), 'objects');
                
                try {
                    @$value = unserialize($option_values[$option['section']][$option['name']]);  
                } catch (Exception $e) {
                    $value = array('A');
                }

                $i = 0;
                $customposts = "";
                foreach ($cps as $cp ) {

                    if ($cp->name=="attachment")
                        continue;

                    $i++;
                    $option_str_holder = "";
                    
                    $checked = " ";
                    if (is_array($value))
                        if (in_array($cp->name, $value)) $checked = "CHECKED ";
                    else
                        if ($cp->name == $value) $checked = "CHECKED ";

                    $option_str_holder .= "<label for=\"{$option['id']}_$i\">"
                            . "<input type=\"checkbox\" "
                            . "name=\"sh_page_links_options[{$option['section']}]"
                            . "[{$option['name']}][]\" "
                            . "id=\"{$option['id']}_$i\" "
                            . "class=\"{$option['name']}\" "
                            . "value=\"". $cp->name ."\" "
                            . $checked
                            . " /> ". $cp->labels->name
                            . "</label><BR />";

                    if ($cp->name=="page")
                        $first = $option_str_holder;
                    elseif ($cp->name=="post")
                        $second = $option_str_holder;
                    else
                        $option_str .= $option_str_holder;
                }
                $option_str = $first . $second . $option_str . "<input type=\"hidden\" "
                        . "name=\"sh_page_links_options[{$option['section']}]"
                        . "[{$option['name']}][]\" "
                        . "id=\"{$option['id']}_$i\" "
                        . "value=\"_empty_\" ";
                            
            }
            
            if ($option['type'] == 'text') {
                $description = !empty($option['description'])
                            ? "<span class=\"description\">{$option['description']}</span>" : '';
                $value = empty($option_values[$option['section']][$option['name']])
                            ? $option['default']
                            : esc_attr($option_values[$option['section']][$option['name']]);
                $description = isset($option['description']) ? $option['description'] : '';
                $option_str = "<label for=\"{$option['id']}\">"
                            . "<input type=\"text\" "
                            . "name=\"sh_page_links_options[{$option['section']}]"
                            . "[{$option['name']}]\" "
                            . "id=\"{$option['id']}\" "
                            . "value=\"{$value}\" "
                            . " /></label> </td><td class='description'>{$description}";
            }

            if ($option['type'] == 'phpstatus') {
                
                $option_str = '<div class="phpstatus">';

                if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
                    $option_str .= '<li>'. __("PHP Version", SH_PAGE_LINKS_DOMAIN) . ': <strong>' . PHP_VERSION . '</strong></li>';
                } else {
                    $option_str .= '<li class="false">' . __("PHP Version", SH_PAGE_LINKS_DOMAIN) . ': <strong>' . PHP_VERSION . '</strong> <em>' . __("You'll want to make sure your server's running the latest version of PHP. The demo site runs the 'bleeding edge' release, but as long as your environment runs the latest version of 5.3+, PLP will operate as intended.<br /><br />Please contact your host for more information.", SH_PAGE_LINKS_DOMAIN) . '</em></li>';
                }

                global $wp_version;
                if (version_compare($wp_version, '3.0.0') >= 0){
                    $option_str .= '<li>'. __("WordPress Version", SH_PAGE_LINKS_DOMAIN) . ': <strong>' . $wp_version . '</strong></li>';
                } else {
                    $option_str .= '<li class="false">' . __("WordPress Version", SH_PAGE_LINKS_DOMAIN) . ': <strong>' . $wp_version . '</strong> <em>' . __("The Page-Links Plus demo site always runs the latest WordPress nightly release. However, as long as your site runs WP 3.0+, PLP will operate as intended.<br /><br />Please update your version of WordPress as soon as possible.", SH_PAGE_LINKS_DOMAIN) . '</em></li>';
                }

                $dir = get_stylesheet_directory() .'/style.css';
                if (@fopen($dir, 'r')) {
                    $dir = get_stylesheet_directory() .'/single.php';
                    $handle = @fopen($dir, 'r');
                    if ($handle) {
                        $contents = fread($handle, filesize($dir));
                        if (strpos($contents, "wp_link_pages")) {
                            $option_str .= '<li>' . __("Pagination Function", SH_PAGE_LINKS_DOMAIN) . ': <strong>' . __("Found", SH_PAGE_LINKS_DOMAIN) . '</strong></li>';
                        } else {
                            $option_str .= '<li class="false">' . __("Pagination Function", SH_PAGE_LINKS_DOMAIN) . ': <strong>' . __("Not Found", SH_PAGE_LINKS_DOMAIN) . '</strong> <em>' . sprintf(__("You'll want to verify your theme uses the %s function, which is the preferred, best-practices WP pagination function.<br /><br />Usually, this function appears in the single.php template, but it might also be elsewhere. If your theme doesn't include this function, you'll want to swap it in.", SH_PAGE_LINKS_DOMAIN), '<a target="_blank" href="http://codex.wordpress.org/Function_Reference/wp_link_pages">wp_link_pages</a>') . '</em></li>';
                        }
                    } else {
                        $option_str .= '<li class="null">' . __("Pagination Function", SH_PAGE_LINKS_DOMAIN) . ': <strong>' . __("File not Found", SH_PAGE_LINKS_DOMAIN) . '</strong> <em>' . sprintf(__("You'll want to verify your theme uses the %s function, which is the preferred, best-practices WP pagination function.<br /><br />Usually, this function appears in the single.php template, but it might also be elsewhere. If your theme doesn't include this function, you'll want to swap it in.", SH_PAGE_LINKS_DOMAIN), '<a target="_blank" href="http://codex.wordpress.org/Function_Reference/wp_link_pages">wp_link_pages</a>') . '</em></li>';
                    }
                } else {
                    $option_str .= '<li class="null">' . __("Pagination Function", SH_PAGE_LINKS_DOMAIN) . ': <strong>' . __("Can't access theme files", SH_PAGE_LINKS_DOMAIN) . '</strong> <em>' . sprintf(__("You'll want to verify your theme uses the %s function, which is the preferred, best-practices WP pagination function.<br /><br />Usually, this function appears in the single.php template, but it might also be elsewhere. If your theme doesn't include this function, you'll want to swap it in.", SH_PAGE_LINKS_DOMAIN), '<a target="_blank" href="http://codex.wordpress.org/Function_Reference/wp_link_pages">wp_link_pages</a>') . '</em></li>';
                }

                $updates = get_site_transient('update_plugins');
                
                if (isset($updates->response['page-links-single-page-option/page-links.php'])) {
                    $option_str .= '<li class="false">' . __("Page Links Version", SH_PAGE_LINKS_DOMAIN) . ': <strong>' . SH_PAGE_LINKS_VER . '</strong> <em>'. __("New version available", SH_PAGE_LINKS_DOMAIN) . " : " . $updates->response['page-links-single-page-option/page-links.php']->new_version . '<a href="update-core.php">' . __("Update it!", SH_PAGE_LINKS_DOMAIN) .'</a></em></li>';
                } else {
                    $option_str .= '<li>' . __("Page Links Version", SH_PAGE_LINKS_DOMAIN) . ': <strong>' . SH_PAGE_LINKS_VER . '</strong></li>';
                }
                

                if (class_exists('SH_PageLinks_PagStyles_Bootstrap')) {
                    if (!isset($updates->response['pagination-styles/pagination-styles.php'])) {
                        $option_str .= '<li>' . __("Pagination Controls Version", SH_PAGE_LINKS_DOMAIN) . ': <strong>' . SH_PAGSTYLES_VER . '</strong></li>';
                    } else {
                        $option_str .= '<li class="false">' . __("Pagination Controls Version", SH_PAGE_LINKS_DOMAIN) . ': <strong>' . SH_PAGSTYLES_VER . '</strong> <em>'. __("New version available", SH_PAGE_LINKS_DOMAIN) . " : " . $updates->response['pagination-styles/pagination-styles.php']->new_version . '<a href="update-core.php">' . __("Update it!", SH_PAGE_LINKS_DOMAIN) .'</a></em></li>';
                    }
                }

                if (class_exists('SH_PageLinks_AutoPag_Bootstrap')) {
                    if (!isset($updates->response['auto-pagination/auto-pagination.php'])) {
                        $option_str .= '<li>' . __("Auto Pagination Version", SH_PAGE_LINKS_DOMAIN) . ': <strong>' . SH_AUTOPAGE_VER . '</strong></li>';
                    } else {
                        $option_str .= '<li class="false">' . __("Auto Pagination Version", SH_PAGE_LINKS_DOMAIN) . ': <strong>' . SH_AUTOPAGE_VER . '</strong> <em>'. __("New version available", SH_PAGE_LINKS_DOMAIN) . " : " . $updates->response['auto-pagination/auto-pagination.php']->new_version . '<a href="update-core.php">' . __("Update it!", SH_PAGE_LINKS_DOMAIN) .'</a></em></li>';
                    }
                }

                if (class_exists('SH_PageLinks_ScrollingPagination_Bootstrap')) {
                    if (!isset($updates->response['scrolling-pagination/scrolling-pagination.php'])) {
                        $option_str .= '<li>' . __("Scrolling Pagination Version", SH_PAGE_LINKS_DOMAIN) . ': <strong>' . SH_SCROLLINGPAG_VER . '</strong></li>';
                    } else {
                        $option_str .= '<li class="false">' . __("Scrolling Pagination Version", SH_PAGE_LINKS_DOMAIN) . ': <strong>' . SH_SCROLLINGPAG_VER . '</strong> <em>'. __("New version available", SH_PAGE_LINKS_DOMAIN) . " : " . $updates->response['scrolling-pagination/scrolling-pagination.php']->new_version . '<a href="update-core.php">' . __("Update it!", SH_PAGE_LINKS_DOMAIN) .'</a></em></li>';
                    }
                }

            }
        }
        
        echo $option_str;
    }


    
	/**
	 * Sanitizes option fields
	 *
	 * @return void
	 */
    public function sanitize_options($options)
    {
		$fields = $this->get_fields();
		$new_options = $options;
        //
        // Workaround for returning to current jQuery UI-generated tab
        $current_tab = '';
        if (!empty($_POST['current_tab'])) {
            $current_tab = $_POST['current_tab'];
        }
        add_settings_error(
            $this->option_group_name,
            'current_tab',
            esc_attr($current_tab),
            'updated'
        );
		
		if ($options) {
			foreach ($options as $option_section => $option) {
				foreach ($option as $option_name => $option_value) {
					$field_data = !empty($fields[$option_section][$option_name])
									? $fields[$option_section][$option_name] : '';
					if ($field_data !== '') {
						switch ($field_data['valid']) {
							case 'boolean' :
							case 'integer':
								if (!isset($field_data['min'])){
									$value = is_numeric($option_value) ? intval($option_value) : 0;
								} else {
									$minval = intval($field_data['min']);
									$newval = intval($option_value);
									$value = ($newval < $minval) ? $minval : $newval;
								}
							break;
							
							case 'array':
								
								$data = @unserialize($option_value);
								if ($data !== false) {
									$value = $option_value;
								} else {
									$value = serialize($option_value);
								}
								
							break;
							default:
								if ($field_data['valid'] == 'html'){
									$value = tag_escape($option_value);
								} else if ($field_data['valid'] == 'html-id'
										|| $field_data['valid'] == 'html-class') {
									$value = sanitize_html_class($option_value);
								} else if ($field_data['valid'] == 'formatted') {
									$value = html_entity_decode($option_value);
								} else { // just strip html out
									$value = sanitize_title($option_value);
								}
								$value = str_replace(array("<script>","</script>"),array("<p>","</p>"),$value);
						}
						$new_options[$option_section][$option_name] = $value;
					}
				}
			}
		}
        return $new_options;
	}
	public function reset_button() {
	
		$val =1;
	 	return $val;
	 } 
}
