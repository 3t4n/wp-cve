<?php
/*
 *  display the settings page
*/
// class tpg_gp_settings extends tpg_get_posts {
class tpg_gp_admin {

    //sec since last update 30x24x60= 43200 sec in 30 day month
    private $update_time=60;

    private $pp_btn='';
    private $resp_data=array(
                    'dl-url'=>'',
                    'dl-link'=>'',
                    );
    private $ext_name='class-tpg-gp-process-ext.php';

    //versions
    public $v_store = '';
    private $v_store_norm = 0.0;
    private $v_plugin = '';
    private $v_plugin_norm = 0.0;
    private $v_plugin_ext = '';
    private $v_plugin_ext_norm = 0.0;

    protected $vl=object;

    //variables set by constructor
    public $gp_opts=array();
    public $gp_paths=array();
    public $module_data=array();
    public $plugin_data=array();
    public $plugin_ext_data=array();

    function __construct($opts,$paths,$notice=false) {
        $this->gp_opts=$opts;
        $this->gp_paths=$paths;
        $this->module_data= array(
                'updt-sys'=>'wp',
                "module"=>'tpg-get-posts',
                );

        // get the plugin info
        $this->get_plugin_info();

        // Register link to the pluging list- if not called from the admin_notice hook
        if (!$notice) {
            add_filter('plugin_action_links', array(&$this, 'tpg_get_posts_settings_link'), 10, 2);
        }
        // Add the admin menu item
        add_action('admin_menu', array(&$this,'tpg_get_posts_admin'));


        if ($opts['show-ids']) {
            if ($opts['valid-lic'] && file_exists($paths['dir']."ext/class-tpg-show-ids.php")) {
                $ssid = tpg_gp_factory::create_show_ids($this->gp_opts,$this->gp_paths);
            }
        }

        //check for stopping of updates
        if ($opts['freeze']) {
            add_filter('site_transient_update_plugins', array(&$this, 'tpg_gp_freeze'));
        }
    }

    /**
     *	add footer info on admin page
     *
     * @package WordPress
     * @subpackage tpg_get_posts
     * @since 1.3
     *
     * write the footer information on options page
     *
     * @param	array	$links
     * @param 	 		$file
     * @return	array	$links
     *
    */
    public function tpg_gp_footer() {
        printf('%1$s by %2$s<br />', $this->plugin_data['Title'].'  Version: '.$this->plugin_data['Version'], $this->plugin_data['Author']);
    }

    /*
     *	add link to plugin doc & settings
     * @package WordPress
     * @subpackage tpg_get_posts
     * @since 1.3
     *
     * add the settings link in the plugin description area
     *
     * @param	array	$links
     * @param 	 		$file
     * @return	array	$links
     */

    function tpg_get_posts_settings_link($links, $file) {
        static $this_plugin;
        if (!$this_plugin) $this_plugin = plugin_basename($this->gp_paths['base']);
        if ($file == $this_plugin){
            $settings_link = '<a href="options-general.php?page=tpg-get-posts-settings">'.__('Settings/Doc', 'tpg-get-posts').'</a>';
            array_unshift($links, $settings_link);
        }
        return $links;
}

    /**
     *	add admin menu
     * @package WordPress
     * @subpackage tpg_get_posts
     * @since 1.3
     *
     * add the TPG GET POSTS menu item to the Setting tab
     *
     * @param    void
     * @return   void
     *
     */
    function tpg_get_posts_admin () {
        // if we are in administrator environment
        if (function_exists('add_submenu_page')) {
            add_options_page('TPG Get Posts Settings',
                            'TPG Get Posts',
                            'manage_options',
                            'tpg-get-posts-settings',
                            array(&$this,'tpg_gp_show_settings')
                            );
        }
    }

    /*
     * show the settings page
     *
     * @package WordPress
     * @subpackage tpg_get_posts
     * @since 2.8
     *
     * the html text for the setting page is loaded into the content variable
     * and then printed.
     * the style sheet is enqueued using the wp enqueue process
     *
     * @param    type    $id    post id
     * @return   string         category ids for selection
     *
     */
    public function tpg_gp_show_settings() {
        // get css, js
        global $gp;
        $this->gp_admin_load_inc();

        // footer info for settings page
        add_action('in_admin_footer', array($this,'tpg_gp_footer'));

        //if options have been set, process them & update array
        if( isset($_POST['gp_opts']) ) {
            // if nonce misssing, do not update
			if ( ! isset( $_POST['tgp_wpnonce'] )
    	    || ! wp_verify_nonce( $_POST['tgp_wpnonce'], 'tgp_updt_nonce' )
			) {
				//print 'Invalid update requested.';
				//exit;
				echo '<div id="message" class="updated fade"><p><strong>' . __('Invalid update requested.') . '</strong></p></div>';
			} else {
                $new_opts = $_POST['gp_opts'];

                $_func = $_POST['func'];

                switch ($_func) {
                    case 'updt_opts':
                        $this->update_options($new_opts);
                        break;
                }
                // refresh options
                $this->gp_opts=$gp->get_options();
            }
        }

        ob_start();
        include($this->gp_paths['inc'].'doc-text.php');
        $page_content = ob_get_contents();
        ob_end_clean();

        // replace tokens in text
        $page_content = str_replace("{settings}",$this->tpg_gp_bld_setting(),$page_content);
        $page_content = str_replace("{donate}",$this->pp_btn,$page_content);

        echo $page_content;

    }

    function tpg_gp_bld_setting() {
        $form_output = $this->build_form();
        // set action link for form
        $action_link = str_replace( '%7E', '~', $_SERVER['REQUEST_URI'])."#gp-settings";
        // replace tokens in text
        $form_output = str_replace("{action-link}",$action_link,$form_output);

        // set default values
        $upd_button='';
        $valid_txt='';

        //set tokens in form
        $form_output = str_replace("{valid-lic-msg}",$valid_txt,$form_output);
        $form_output = str_replace("{update-button}",$upd_button,$form_output);
        $form_output = str_replace("{download-url}",$this->resp_data['dl-url'],$form_output);

        return $form_output;
    }

    /*
     *	update_options
     *  update the wp plugin options
     *
     * @subpackage tpg_get_posts
     * @since 2.0
     *
     * update options
     *
     * @param    null
     * @return   null
     */
    function update_options($new_opts){
        //chk box will not return values for unchecked items
        if (!array_key_exists("show-ids",$new_opts)) {
            $new_opts['show-ids'] = false;
        } else {
            $new_opts['show-ids'] = true;
        }

        if (!array_key_exists("keep-opts",$new_opts)) {
            $new_opts['keep-opts'] = false;
        } else {
            $new_opts['keep-opts'] = true;
        }

        if (!array_key_exists("active-in-widgets",$new_opts)) {
            $new_opts['active-in-widgets'] = false;
        } else {
            $new_opts['active-in-widgets'] = true;
        }

        if (!array_key_exists("freeze",$new_opts)) {
            $new_opts['freeze'] = false;
        } else {
            $new_opts['freeze'] = true;
        }

        if (!array_key_exists("active-in-backend",$new_opts)) {
            $new_opts['active-in-backend'] = false;
        } else {
            $new_opts['active-in-backend'] = true;
        }

        // apply new values to gp_opts
        foreach($new_opts as $key => $value) {
            $this->gp_opts[$key] = $value;
        }

        //update with new values
        update_option( 'tpg_gp_opts', $this->gp_opts);

        echo '<div id="message" class="updated fade"><p><strong>' . __('Settings saved.','tpg-get-posts') . '</strong></p></div>';
    }

    /*
     *	tpg gp freeze
     *  stop the update of the plugin to freeze it at a level
     *
     * @param    object
     * @return   object
     */
    function tpg_gp_freeze($value) {
        unset($value->response[ $this->gp_paths['base'] ]);
        return $value;
        }

    /**
     * Returns current plugin version and extension data.
     *
     * @param string optional get ext info
     * @return string Plugin version
     */
    function get_plugin_info($_ext='') {
        if ( ! function_exists( 'get_plugin_data' ) )
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        $plugin_name=$this->gp_paths['name'].'.php';
        $this->plugin_data = get_plugin_data( $this->gp_paths['dir'].$plugin_name );
        if ($_ext != '' && file_exists($this->gp_paths['ext'].$_ext)) {
            $this->plugin_ext_data = get_plugin_data( $this->gp_paths['ext'].$_ext );
        } else {
            $this->plugin_ext_data = array('Version'=>'0.0.0','Description'=>__('Ext file not found','tpg-get-posts'));
        }

        return;
    }

    /*
     *	gp_admin_load_inc
     *  enque css, js and other items for admin page
     *
     * @package WordPress
     * @subpackage tpg_phplist
     * @since 0.1
     *
     * enque the css, js and other items only when the admin page is called.
     *
     * @param    null
     * @return   null
     */
    function gp_admin_load_inc(){
        //enque css style

        $tgp_css = "tpg-get-posts-admin.css";
        //check if file exists with path
        if (file_exists($this->gp_paths['css'].$tgp_css)) {
            wp_enqueue_style('tpg_get_posts_admin_css',$this->gp_paths['css_url'].$tgp_css);
        }
        if (file_exists($this->gp_paths['css']."user-get-posts-style.css")) {
            wp_enqueue_style('user_get_posts_css',$this->gp_paths['css_url']."user-get-posts-style.css");
        }

        //get jquery tabs code
        wp_enqueue_script('jquery-ui-tabs');

        //load admin js code
        if (file_exists($this->gp_paths['js']."tpg-get-posts-admin.js")) {
            wp_enqueue_script('tpg_get-posts_admin_js',$this->gp_paths['js_url']."tpg-get-posts-admin.js");
        }

        //generate pp donate button
        $ppb = tpg_gp_factory::create_paypal_button();
        $ask="<p>".__('If this plugin helps you build a website, please consider a small donation of $5 or $10 to continue the support of open source software.  Taking one hour&lsquo;s fee and spreading it across multiple plugins is an investment that generates amazing returns.','tpg-get-posts')."</p><p>".__('Thank you for supporting open source software.','tpg-get-posts')."</p>";
        $ppb->set_var("for_text","wordpress plugin tpg-get-posts");
        $ppb->set_var("desc",$ask);
        $this->pp_btn = $ppb->gen_donate_button();
    }

    /*
     *	build form for options
     *
     * @package WordPress
     * @subpackage tpg_get_posts
     * @since 2.0
     *
     * @param    null
     * @return   null
     */
    function build_form() {
        //array to hold changes
        $gp_opts = array();

        //test the check boxes to see if the value should be checked
        $ck_show_ids = ($this->gp_opts['show-ids'])? 'checked=checked' : '';
        $ck_keep_opts = ($this->gp_opts['keep-opts'])? 'checked=checked' : '';
        $ck_widgets_opts = ($this->gp_opts['active-in-widgets'])? 'checked=checked' : '';
        $ck_freeze = ($this->gp_opts['freeze'])? 'checked=checked' : '';
        $ck_backend = ($this->gp_opts['active-in-backend'])? 'checked=checked' : '';
        $btn_updt_opts_txt = __('Update Options', 'tpg-get-posts' ) ;
        $btn_val_lic_txt = __('Validate Lic', 'tpg-get-posts' ) ;

        // creat the nonce function for use in heredoc text of forms
		$wpnonce = 'wp_nonce_field';

        //hack for translation
        $__ = '__';
        //create output form
        $output = <<<EOT
        <div class="wrap">
    <div class="postbox-container" style="width:100%; margin-right:5%; " >
        <div class="metabox-holder">
            <div id="jq_effects" class="postbox">
                <div class="handlediv" title="Click to toggle"><br /></div>

                <h3><a class="togbox">+</a> {$__('TPG Get Posts Options','tpg-get-posts')} </h3>
                {donate}
                <div class="inside"  style="padding:10px;">
                    <form name="getposts_options" method="post" action="{action-link}">

                        <h4>{$__('Base Options','tpg-get-posts')} </h4>
                        <table class="form-table">
                            <tr>
                            <td>{$__('Freeze Updates:','tpg-get-posts')}  </td><td><input type="checkbox" name="gp_opts[freeze]" id="id_freeze" value="true" $ck_freeze /></td><td>{$__('This option prevents the update notice from being displayed.  Use this if you wish to stop any future updates to the plugin.','tpg-get-posts')}</td>
                            </tr>
                            <tr>
                            <td>{$__('Keep Options on uninstall:','tpg-get-posts')}  </td><td><input type="checkbox" name="gp_opts[keep-opts]" id="id_keep_opts" value="false" $ck_keep_opts /></td><td>{$__('If checked, options will not be deleted on uninstall.  Useful when upgrading.  Uncheck to completely remove premium version.','tpg-get-posts')}</td>
                            </tr>
                            <tr>
                            <td>{$__('Show Ids:','tpg-get-posts')}  </td><td><input type="checkbox" name="gp_opts[show-ids]" id="id_show_id" value="true" $ck_show_ids /></td><td>{$__('This option applies modifications to the show cat (and other admin pages) to show the id of the entires.  This number is needed for the some of the premium selection options and for the category selector.','tpg-get-posts')} </td>
                            </tr>
                            <tr>
                            <td>{$__('Activate in Widgets:','tpg-get-posts')}  </td><td><input type="checkbox" name="gp_opts[active-in-widgets]" id="id_widgets" value="true" $ck_widgets_opts /></td><td>{$__('If you want this plugin active in text widgets, check this box to activate the shortcodes for widgets.','tpg-get-posts')}</td>
                            </tr>
                            <tr>
                            <td>{$__('Activate in Backend:','tpg-get-posts')}  </td><td><input type="checkbox" name="gp_opts[active-in-backend]" id="id_backend" value="true" $ck_backend /></td><td>{$__('If you want this plugin active in the administrative (backend) section, check this box.  This adds extra processing to admin side, but is required from some plugins to work correctly, such as WPMU_eNewsletter.','tpg-get-posts')}</td>
                            </tr>
                        </table>

                            <!--//values are used in switch to determine processing-->
                            <p class="submit">
                            <button type="submit" class="button-primary tpg-settings-btn" name="func" value="updt_opts" />$btn_updt_opts_txt</button>
                            {$wpnonce('tgp_updt_nonce', 'tgp_wpnonce')}
                            &nbsp;&nbsp;
                            {update-button}
                            </p>


                    </form>
                </div>
            </div>
        </div>
    </div>
EOT;

        return $output;
    }

}
?>
