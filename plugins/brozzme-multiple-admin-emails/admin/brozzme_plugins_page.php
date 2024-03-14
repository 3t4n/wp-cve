<?php

/**
 * Created by PhpStorm.
 * User: benoti
 * Date: 04/05/2017
 * Time: 10:57
 */

defined('ABSPATH') or exit();

class brozzme_plugins_page{

    public function __construct()
    {

        $this->plugin_path = realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR;

        $this->plugin_url = $this->path2url($this->plugin_path);

        add_action('admin_menu', array($this, 'add_admin_plugins_groupe_menu'));

        $this->website_active_plugin = get_option('active_plugins');

        $this->brozzme_api_url = 'https://api.brozzme.com/brozzme_plugins_rest_base.php';

        $this->fields = array(
            'short_description' => false,
            'description' =>false,
            'sections' => false,
            'tested' => true ,
            'rating' => true,
            'num_ratings' => true,
            'downloaded' => true,
            'active_installs' => true,
            'download_link' => true,
            'last_updated' => true ,
            'homepage' => false,
            'tags' => true
        );

        $this->wp_api_url = 'https://api.wordpress.org/plugins/info/1.0/';

        add_action('admin_footer_text', array($this, 'footer_credits'));

        $this->txt_domain = 'brozzme-plugins-central';

        add_action( 'plugins_loaded', array($this, '_load_textdomain') );
    }

    /**
     * @param $file
     * @return string
     */
    public function path2url($file) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        return $protocol.$_SERVER['HTTP_HOST'].str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
    }
    /**
     * Load aggregated Brozzme plugins panel menu
     */
    public function add_admin_plugins_groupe_menu() {
       
        if (empty($GLOBALS['admin_page_hooks'][BFSL_PLUGINS_DEV_GROUPE_ID])):
            add_menu_page(BFSL_PLUGINS_DEV_GROUPE,
                BFSL_PLUGINS_DEV_GROUPE,
                'manage_options',
                BFSL_PLUGINS_DEV_GROUPE_ID,
                array($this, 'add_admin_plugins_groupe_page'),
                'dashicons-screenoptions',
                61
            );
        endif;
    }

    /**
     * load text domain
     */
    public function _load_textdomain() {
        load_plugin_textdomain( $this->txt_domain, false, BFSL_PLUGINS_SLUG . '/languages' );
    }


    /**
     * @param $footer_text
     * @return string
     */
    public function footer_credits($footer_text){
        global $pagenow;

        if ( get_current_screen()->id == 'toplevel_page_' . BFSL_PLUGINS_DEV_GROUPE_ID ) { // Don't forget to add a check for your plugin's page here
            $footer_text = esc_html__( 'Thank you for using Brozzme plugins, ', $this->txt_domain );
            $footer_text = sprintf( __( 'If you like <strong>Brozzme</strong> plugins, please leave ratings %1s. A huge thanks in advance!', $this->txt_domain ), '<a href="https://wordpress.org/plugins/search/brozzme/" target="_blank" alt="wordpress.org" title="wordpress.org">&#9733;&#9733;&#9733;&#9733;&#9733;</a>' );
        }
        return $footer_text;
    }
    /**
     * search and display details from api
     * Install comes only from wordpress.org 
     * Compares version and activation
     * 
     */
    public function add_admin_plugins_groupe_page(){

        if ( false === ( $datas = get_transient( 'brozzme_plugins_api_results' ) ) ) {
            $datas = wp_remote_get($this->brozzme_api_url);

            if(!is_wp_error($datas)){
                $datas = $datas['body'];
                if($datas == '') {
                    $datas = wp_remote_get($this->brozzme_api_url);
                }
                set_transient( 'brozzme_plugins_api_results', $datas, 24 * HOUR_IN_SECONDS );

            }
        }
        $datas = json_decode($datas, true);
        ?>
        <div class="wrap">
        <?php
        $this->_welcome_screen();

        $keys = array_keys($datas);
        shuffle($keys);
        $random = array();
        foreach ($keys as $key) {
            $random[$key] = $datas[$key];
        }

        foreach ($random as $k=>$data) {
            ?>
            <div class="brozzme-plugin-info">
                <div class="brozzme-plugin-thumbnail"><img src="<?php echo esc_url($data['bplugin_thumbnail_id']);?>" width="100px" height="100px" style="float:right" /></div>
                <?php
                echo '<h3>'. esc_html($data['title']['rendered']).'</h3>' ;?>
                <div class="brozzme-plugin-description">
                    <?php if($data['bplugin_thumbnail_id'] != ''){
                    ?><?php
                    }
                    echo $data['excerpt']['rendered'].'<br/>';


                    if($data['bplugin_is_product_or_repo'] != 'true'){ // true for a product

                        echo '<a href="https://wordpress.org/plugins/'.$data['bplugin_repo_slug'].'" target="_blank">'.__('See more details about this plugin', $this->txt_domain).'</a><br/>';
                        if($this->_is_plugin_active($data['bplugin_repo_slug']) === true){
                            echo esc_html__('Version: ', $this->txt_domain) . $this->_folder_version_check($data['bplugin_repo_slug']) . ' ' . $this->compare_version_check($data['bplugin_repo_slug']);
                        }
                    }
                    ?>
                </div>
                <div class="download-plugin">
                    <?php
                    if($data['bplugin_is_product_or_repo'] == 'true'){ // true for a product
                        echo '<a href="'.esc_url($data['bplugin_link']).'" class ="button button-primary" target="_blank">'.__('More details', $this->txt_domain).'</a>';
                    }
                    else{
                        // verify if plugin is activate for this install
                        if($this->_is_plugin_active($data['bplugin_repo_slug']) === true){

                            echo '<a href="'. admin_url().'admin.php?page='.$data['bplugin_repo_slug'].'" class="button button-primary">'. __('Already activated', $this->txt_domain).'</a>';

                        }else{
                            /* Install plugin from wordpress.org repositary */
                            $nonce = wp_create_nonce('install-plugin_'. $data['bplugin_repo_slug']);
                            echo '<a href="'.admin_url() . 'update.php?action=install-plugin&plugin='. $data['bplugin_repo_slug'] .'&_wpnonce='. $nonce .'" class ="button button-primary" >'.__('Install this plugin', $this->txt_domain).'</a>';
                        }
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        ?></div><?php
    }

    /**
     *
     */
    public function _welcome_screen(){
        
        ?>
        <div class="brozzme-info-wide">
            <?php echo $this->_social_screen();?>
            <h1 class="brozzme-title" align="center"><?php esc_html_e('WELCOME IN THE BROZZME ADMIN PANEL', $this->txt_domain);?></h1>
            <p align="center"><?php esc_html_e('Thank you for using Brozzme plugin. Here is a general panel to show you all available Brozzme plugins to enhance your workflow and design.', $this->txt_domain);?></p>
            <p align="center"><?php esc_html_e('Install directly free plugin from wordpress.org without the need to search them in the repository.', $this->txt_domain);?></p>

        </div>

        <?php echo $this->_affiliate_screen();?>
        <?php

    }

    /**
     * @return string
     */
    public function _social_screen(){

        $output = '<div class="brozzme-di">
            <a class="dashicons di-facebook" target="_blank" href="https://www.facebook.com/Brozzme/" title="'. esc_html__('Follow Brozzme on Facebook', $this->txt_domain) .'"></a>
            <a class="dashicons dashicons-groups" target="_blank" href="https://www.facebook.com/groups/388353651546567/" title="'. esc_html__('I Love My WordPress group on Facebook', $this->txt_domain) .'"></a>
            <a class="dashicons dashicons-info" class="a-propos-link" href="#openModal" title="'. esc_html__('About me', $this->txt_domain) .'"></a>          
            </div>
        ';
        
        $output .= '<div id="openModal" class="a-propos">
                <div>
                    <a href="#close" title="Close" class="close"><span class="dashicons dashicons-dismiss"></span></a>
                    <h2>'. __('About', $this->txt_domain).'</h2>
                    <p><span class="dashicons dashicons-admin-users"></span> Benoît Faure - benoti</p>
                    <p><span class="dashicons dashicons-email-alt"></span> dev@brozzme.com</p>
                    <p><span class="dashicons dashicons-admin-site"></span> '. esc_html__('More information', $this->txt_domain).' <a href="https://brozzme.com/" target="_blank">Brozzme</a></p>
                    <p><span class="dashicons dashicons-admin-tools"></span> '. esc_html__('Development for the web: WordPress plugins, theming, Php front & back-end developper...', $this->txt_domain).'</p>
                    <p><span class="dashicons dashicons-networking"></span> '.esc_html__('Contact slack: ', $this->txt_domain) .'@benoti</p>

                </div>
            </div>';

        return $output;
    }

    /**
     * Affiliate screen only show with FR locale, that's why strings are not translated.
     * @return string
     */
    public function _affiliate_screen(){

        $locale = get_locale();
        $pos = strpos($locale, 'fr');

        if($pos !== false){
            $output = '<div class="brozzme-plugin-info">
                    <div class="brozzme-plugin-thumbnail" style="display: flex;justify-content: center;">
                        <a href="https://www.wpserveur.net/?refwps=221" target="_blank"><img src="'.$this->plugin_url.'admin/img/anim-wpserveur---300.gif" style="float:left" /></a>
                        </div>
                        <h3>WPServeur</h3>
                        <div class="brozzme-plugin-description">
                        Des offres d\'hébergement de qualité pour vos WordPress, simple d\'utilisation, rapide et sécurisé. 
                        </div>
                    </div>                      
            ';
            return $output;
        }

    }
    /**
     * @param $slug
     * @return bool
     */
    public function _is_plugin_active($slug){

        foreach($this->website_active_plugin as $k=>$active_plugin){

            if(strpos( $active_plugin, $slug)!== false){
                return true; // plugin is activate
            }
        }
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function _folder_version_check($slug){

        $plugin_files = $this->get_plugin_files($slug);

        $plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_files[1] );

        return $plugin_data['Version'];
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function _api_version_check($slug){


        if ( false === ( $response = get_transient( 'brozzme_plugins_api_version_'.$slug ) ) ) {
            $response = $this->send_to_api($slug);
            set_transient('brozzme_plugins_api_version_'.$slug, $response, 24 * HOUR_IN_SECONDS );
        }
        
        return $response->version;
    }

    /**
     * @param $slug
     * @return string
     */
    public function compare_version_check($slug){

        $folder_version = $this->_folder_version_check($slug);
        $api_version = $this->_api_version_check($slug);

        if($folder_version == $api_version){

            $output = '<span class="dashicons dashicons-yes" style="color:green;" alt="'.esc_html__('OK, last version installed.', $this->txt_domain).'" title="'.__('OK, last version installed.', $this->txt_domain).'"></span>';
        }
        else{
            $output = '<span class="dashicons dashicons-no" style="color:red" alt="'.esc_html__('New updated available, please update this plugin.', $this->txt_domain).'" title="'.__('New updated available, please update this plugin.', $this->txt_domain).'"></span>';
        }
        return $output;
    }
    /**
     * Send request to plugin api
     * @param $plugin_slug
     * @return array|mixed|object|string|WP_Error
     */

    public function send_to_api($plugin_slug){

        //if ( false === ( $body = get_transient( 'brozzme_plugin_api_details_' . $plugin_slug ) ) ) {
            $payload = $this->payload_factory($plugin_slug, 'plugin_information', 'slug', $this->fields);

            $body = wp_remote_post( $this->wp_api_url, array( 'body' => $payload) );

            if ( is_wp_error( $body ) ) {
                $error_message = $body->get_error_message();
                $body = "Something went wrong: $error_message";
            } else {

                $body = unserialize($body['body']);

                $body = (object)$body;
              //  set_transient('brozzme_plugin_api_details_' . $plugin_slug, $body, 12 * HOUR_IN_SECONDS );
            }

        //}

        return $body;
    }

    /**
     * payload for send_to_api
     * @param $search
     * @param $action
     * @param $search_type
     * @param $fields
     * @return array
     */
    public function payload_factory($search, $action, $search_type, $fields){
        $action_array = array(
            'plugin_information'=> array('key'=> 'slug'),
            'query_plugins'     => array('key'=> 'search'),
            'hot_tags'          => array('key'=> '')
        );

        if($action == 'query_plugins'){
            // possible search type : search, tag, author
            $key = $search_type;

        }
        elseif($action == 'plugin_information'){
            $key = 'slug';
        }
        else{
            $key = 'search';
        }

        $payload = array(
            'action' => $action,
            'request' => serialize(
                (object)array(
                    $key => $search,
                    'fields' => $fields,
                    'per_page' => 10,
                    'page'=> 1
                )
            )
        );

        return $payload;
    }

    /**
     * Get a list of a plugin's files.
     *
     * @since 2.8.0
     *
     * @param string $plugin Plugin ID
     * @return array List of files relative to the plugin root.
     */

    public function get_plugin_files($plugin){

        $plugin_file = WP_PLUGIN_DIR . '/' . $plugin;
        $dir = $plugin_file;
        $plugin_files = array($plugin);

        if ( is_dir($dir) && $dir != WP_PLUGIN_DIR ) {
            $plugins_dir = @ opendir( $dir );

            if ( $plugins_dir ) {
                while (($file = readdir( $plugins_dir ) ) !== false ) {
                    if ( substr($file, 0, 1) == '.' )
                        continue;
                    if ( is_dir( $dir . '/' . $file ) ) {
                        $plugins_subdir = @ opendir( $dir . '/' . $file );
                        if ( $plugins_subdir ) {
                            while (($subfile = readdir( $plugins_subdir ) ) !== false ) {
                                if ( substr($subfile, 0, 1) == '.' )
                                    continue;
                                $plugin_files[] = plugin_basename("$dir/$file/$subfile");
                            }
                            @closedir( $plugins_subdir );
                        }
                    } else {
                        if ( plugin_basename("$dir/$file") != $plugin )
                            $plugin_files[] = plugin_basename("$dir/$file");
                    }
                }
                @closedir( $plugins_dir );
            }
        }

        return $plugin_files;
    }

}

new brozzme_plugins_page();