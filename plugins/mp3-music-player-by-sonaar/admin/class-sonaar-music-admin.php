<?php

/**
* The admin-specific functionality of the plugin.
*
* @link       sonaar.io
* @since      1.0.0
*
* @package    Sonaar_Music
* @subpackage Sonaar_Music/admin
*/

/**
* The admin-specific functionality of the plugin.
*
* Defines the plugin name, version, and two examples hooks for how to
* enqueue the admin-specific stylesheet and JavaScript.
*
* @package    Sonaar_Music
* @subpackage Sonaar_Music/admin
* @author     Edouard Duplessis <eduplessis@gmail.com>
*/

class Sonaar_Music_Admin {

    /**
    * The ID of this plugin.
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $plugin_name    The ID of this plugin.
    */
    private $plugin_name;
    
    /**
    * The version of this plugin.
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $version    The current version of this plugin.
    */
    private $version;
    
    public $plugin_basename;

    /**
    * Initialize the class and set its properties.
    *
    * @since    1.0.0
    * @param      string    $plugin_name       The name of this plugin.
    * @param      string    $version    The version of this plugin.
    */
    public function __construct( $plugin_name, $version ) {
        
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->plugin_basename = plugin_basename( plugin_dir_path( dirname( __FILE__ ) ) . 'sonaar-music.php' );
        $this->load_dependencies();
        
    }
    /**
    * Load the required dependencies for the admin area.
    *
    * Include the following files that make up the plugin:
    *
    * @since		1.0.0
    */
    public function load_dependencies(){
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/cmb2/init.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/cmb2-calltoaction/cmb2-calltoaction.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/cmb2-conditionals/cmb2-conditionals.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/cmb2-image-select-field-type/image_select_metafield.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/cmb2-post-search-field/cmb2_post_search_field.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/cmb2-store-list/song-store-field-type.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/cmb2-typography/typography-field-type.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/cmb2-multiselect/cmb2-multiselect.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/cmb2-switch-button-metafield/switch_metafield.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/cmb2-field-faiconselect/iconselect.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sonaar-music-widget.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sonaar-music-block.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/Shortcode_Button/shortcode-button.php';  
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/srmp3_options_importer.php';
        if (did_action('elementor/loaded')) {
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/srmp3_templates_importer.php';
        }
        add_action( 'admin_init', array( $this, 'srmp3_load_plugin_action' ) );
        add_action( 'admin_notices', array( $this, 'businessplan_required') );
        add_filter( 'enter_title_here', array( $this, 'srmp3_title_place_holder' ), 20 , 2 );


    }
    public function srmp3_title_place_holder($title , $post){

        if( $post->post_type == SR_PLAYLIST_CPT ){
            $my_title = $this->sr_GetString('Add Title');
            return $my_title;
        }

        return $title;

    }
    public function srmp3_load_plugin_action() {
        add_filter( 'plugin_action_links_' . $this->plugin_basename, array( $this, 'srmp3_add_action_links' ) );
    }
    public function businessplan_required() {
        $screen = get_current_screen();
        $sonaar_music_licence = get_site_option('sonaar_music_licence');

        // Check if you are on the desired options page. Replace 'your_options_page_slug' with the slug of your options page.
        if ( $screen->id === 'sr_playlist_page_srmp3_settings_audiopreview' ) {
            if ($sonaar_music_licence == '' || get_site_option('SRMP3_ecommerce') !== '1') {
                echo '
                <div class="notice notice-error">
                    <p>MP3 Audio Player Pro - <strong>Business Plan or higher</strong> is required. </strong> <a href="https://sonaar.io/mp3-audio-player-pro/pricing/" target="_blank">View Pricing</a></p>
                </div>';
            }     
        }
    }
    public function srmp3_add_action_links( $links ) {
        // add Settings and Go Pro links on the plugin.php screen
        $mylinks = array(
            '<a href="' . admin_url( 'edit.php?post_type=' . SR_PLAYLIST_CPT . '&page=srmp3_settings_general' ) . '">Settings</a>'
        );

        if ( ! function_exists( 'run_sonaar_music_pro' ) ) {
            array_push(
                $mylinks,
                '<span><a href="https://sonaar.io/mp3-audio-player-pro/?utm_source=Sonaar+Music+Free+Plugin&utm_medium=plugin" style="color:#39b54a;font-weight:700;">Go Pro</a></span>'
            );
        }

        return array_merge( $links, $mylinks );
    }
    /**
    * Register the stylesheets for the admin area.
    *
    * @since    1.0.0
    */
    public function editor_scripts() {
        wp_enqueue_style( 'sonaar-elementor-editor', plugin_dir_url(dirname(__FILE__)) . 'admin/css/elementor-editor.css', array(), $this->version, 'all' );
    }

    public function enqueue_styles() {
		$post_types = Sonaar_Music::get_option('srmp3_posttypes', 'srmp3_settings_general');
        wp_enqueue_style( 'sonaar-music-admin', plugin_dir_url( __FILE__ ) . 'css/sonaar-music-admin.css', array(), $this->version, 'all' );
        wp_enqueue_style( 'cmb2_switch-css', plugin_dir_url( __FILE__ ) . 'library/cmb2-switch-button-metafield/switch_metafield.css', false, $this->version ); //CMB2 Switch Styling
		
		
		if ( !isset($_GET['page']) && ( get_post_type() == 'sr_playlist' || (is_array($post_types) && in_array(get_post_type(), $post_types)))) {
            wp_enqueue_style( 'fontawesomeiselect', plugin_dir_url( __FILE__ ) . 'library/cmb2-field-faiconselect/css/faws/css/font-awesome.min.css', array( 'jqueryfontselector' ), $this->version );
			wp_enqueue_style( 'jqueryfontselectormain', plugin_dir_url( __FILE__ ) . 'library/cmb2-field-faiconselect/css/css/base/jquery.fonticonpicker.min.css', array(), $this->version );
			wp_enqueue_style( 'jqueryfontselector', plugin_dir_url( __FILE__ ) . 'library/cmb2-field-faiconselect/css/css/themes/grey-theme/jquery.fonticonpicker.grey.min.css', array(), $this->version );
        }
        
        
    }
    /**
    * Register the JavaScript for the admin area.
    *
    * @since    1.0.0
    */
    public function enqueue_scripts( $hook ) {

		$post_types = Sonaar_Music::get_option('srmp3_posttypes', 'srmp3_settings_general');
		wp_enqueue_script( 'sonaar-admin', plugin_dir_url( dirname( __FILE__ ) ) . 'admin/js/sonaar-admin.js', false, $this->version, true );
        wp_enqueue_script( 'cmb2_switch-js', plugin_dir_url( __FILE__ ) . 'library/cmb2-switch-button-metafield/switch_metafield.js' , '', '1.0.0', true );  // CMB2 Switch Event

        if ($hook == SR_PLAYLIST_CPT . '_page_iron_music_player' || $hook == SR_PLAYLIST_CPT . '_page_sonaar_music_promo' || $hook == SR_PLAYLIST_CPT . '_page_sonaar_music_promo' || $hook == SR_PLAYLIST_CPT . '_page_srmp3-import-templates') { // (RetroCompatibility)'_page_iron_music_player' is the hook for the old plugin settings page. 
            wp_enqueue_script( 'vuejs', plugin_dir_url( __DIR__ ) . 'public/js/vue.min.js' , array(), '2.6.14', false );
            wp_enqueue_script( 'polyfill', plugin_dir_url( __DIR__ ) . 'public/js/polyfill.min.js' , array(), '6.26.0', false );
            wp_enqueue_script( 'bootstrap-vue', plugin_dir_url( __DIR__ ) . 'public/js/bootstrap-vue.min.js' , array(), '2.21.2', false );
            wp_enqueue_style( 'bootstrap-css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), '5.1.3', 'all' );
            wp_enqueue_style( 'bootstrapvue-css', plugin_dir_url( __FILE__ ) . 'css/bootstrap-vue.min.css', array(), $this->version, 'all' );            
        }
        if( $hook == SR_PLAYLIST_CPT . '_page_srmp3-import-templates'){
            wp_enqueue_script( 'sonaar-list', plugin_dir_url( __DIR__ ) . 'public/js/list.min.js' , array(), $this->version, false );
        }
       
		if ( !isset($_GET['page']) && ( get_post_type() == 'sr_playlist' || (is_array($post_types) && in_array(get_post_type(), $post_types)))) {
			wp_enqueue_script( 'jqueryfontselector',  plugin_dir_url( __FILE__ ) . 'library/cmb2-field-faiconselect/js/jquery.fonticonpicker.min.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( 'mainjsiselect', plugin_dir_url( __FILE__ ) . 'library/cmb2-field-faiconselect/js/main.js', array( 'jqueryfontselector' ), $this->version, true );
            if (!wp_script_is('sonaar-music-mp3player', 'enqueued')){
                // we want to localize var sonaar_music only if its not already enqueued previously. var sonaar_music is not enqueued with classic editor. eg: when editing a WooCommerce product: Needded for the tracklist expand/collapse fnc.
                wp_localize_script( 'sonaar-admin', 'sonaar_music', array(
                    'plugin_dir_url'    => plugin_dir_url( dirname( __FILE__ ) ),
                    'option'            => Sonaar_Music::get_option( 'allOptions' )
                ));
            }
		}
       
        if ($hook == 'term.php' || $hook == SR_PLAYLIST_CPT . '_page_iron_music_player' || $hook == SR_PLAYLIST_CPT . '_page_sonaar_music_promo' || $hook == SR_PLAYLIST_CPT . '_page_sonaar_music_promo' || strpos($hook, SR_PLAYLIST_CPT . '_page_srmp3_settings_') === 0) {
                wp_enqueue_script( 'cmb2_conditionallogic-js', plugin_dir_url( __FILE__ ) . 'library/cmb2-conditional-logic/cmb2-conditional-logic.min.js' , '', '1.0.0', true );  // Used for plugin settings page only. it does not work on group repeater fields
        }
        if (strpos($hook, SR_PLAYLIST_CPT . '_page_srmp3_settings_') === 0) {
            wp_enqueue_script( 'cmb2_image_select_metafield-js', plugin_dir_url( __FILE__ ) . 'library/cmb2-image-select-field-type/image_select_metafield.js' , '', '1.0.0', true );  // Used for plugin settings page only. it does not work on group repeater fields
            wp_enqueue_script( 'sonaar-music', plugin_dir_url( __DIR__ ) . 'public/js/sonaar-music-public.js', array( 'jquery' ), $this->version, true ); // used for peak generation
           
            wp_localize_script('sonaar-admin', 'sonaar_music', array(
                'ajax' => array(
                    'ajax_url' => admin_url( 'admin-ajax.php' ),
                    'ajax_nonce' => wp_create_nonce( 'sonaar_music_admin_ajax_nonce' ),
                ),
            ));
            

        }
       
        if (wp_script_is('sonaar-admin', 'enqueued')) {
            wp_localize_script('sonaar-admin', 'sonaar_admin_ajax', array(
                'ajax' => array(
                    'ajax_url' => admin_url( 'admin-ajax.php' ),
                    'ajax_nonce' => wp_create_nonce( 'sonaar_music_admin_ajax_nonce' ),
                ),
            ));
        }
       
        
    }
    public function init_options() {
        function index_get_generalfields(){
            $fields = array();
            
            if ( Sonaar_Music::get_option('player_type', 'srmp3_settings_general') == 'podcast' ){
                $fields =  array(
                    'post_title'            => 'Post Title',
                    'track_mp3_title'       => 'MP3 ID3 Episode Title',
                    'track_mp3_album'       => 'MP3 ID3 Podcast Name',
                    'track_mp3_artist'      => 'MP3 ID3 Host Name',
                    'track_mp3_length'      => 'MP3 ID3 Duration',
                    'stream_title'          => 'Stream Title',
                    'stream_album'          => 'Stream Podcast Name',
                    'artist_name'           => 'Stream Host Name',
                    'stream_lenght'         => 'Stream Duration',
                    'track_description'     => 'Episode Description',
                );
            }else{
                $fields = array(
                    'post_title'            => 'Post Title',
                    'track_mp3_title'       => 'MP3 ID3 Title',
                    'track_mp3_album'       => 'MP3 ID3 Album Name',
                    'track_mp3_artist'      => 'MP3 ID3 Artist Name',
                    'track_mp3_length'      => 'MP3 ID3 Duration',
                    'stream_title'          => 'Stream Title',
                    'stream_album'          => 'Stream Album Name',
                    'artist_name'           => 'Stream Artist Name',
                    'stream_lenght'         => 'Stream Duration',
                    'track_description'     => 'Track Description',
                );
            }
            return $fields;
        }
        function index_get_taxonomies(){
            $taxonomies = array();
            
            if ( Sonaar_Music::get_option('player_type', 'srmp3_settings_general') == 'podcast' ){
                $taxonomies =  array(
                    'playlist-cat'  => 'Podcast Categories',
                    'playlist-tag'  => 'Podcast Tags',
                    'podcast-show'  => 'Podcast Show'
                );
            }else{
                $taxonomies = array(
                    'playlist-cat' => 'Playlist Categories',
                    'playlist-tag' => 'Playlist Tags',
                );
            }
            if ( defined( 'WC_VERSION' )) {
                $taxonomies +=  array(
                    'product_cat' => 'Product Categories',
                    'product_tag' => 'Product Tags',
                );
            }
            return $taxonomies;
        }
        function get_the_cpt(){
                $post_types = get_post_types(['public'   => true, 'show_ui' => true], 'objects');
                $posts = array();
                foreach ($post_types as $post_type) {
                    if ($post_type->name == 'attachment' || $post_type->name == 'elementor_library' )
                        continue; 

                    $posts[$post_type->name] = $post_type->labels->singular_name;
                }
                return $posts;
        }
        function music_player_coverSize(){
            $music_player_coverSize = array();
            $imageSizes = get_intermediate_image_sizes();
            foreach ($imageSizes as $value) {
                $music_player_coverSize[$value] = $value;
            }
            return $music_player_coverSize;
        }
        function promo_ad_cb( $field_args, $field ) {
            
            if ( !function_exists('run_sonaar_music_pro') || function_exists('run_sonaar_music_pro') && get_site_option('SRMP3_ecommerce') != '1' && $field->options('plan_required') === 'business'){
                $textpromo = ( $field->options('textpromo')) ? $field->options('textpromo') : esc_html__('Premium Feature | Upgrade to Pro', 'sonaar-music');
                echo '<div class="prolabel"><a href="https://sonaar.io/mp3-audio-player-pro/pricing/?utm_source=Sonaar+Music+Free+Plugin&utm_medium=plugin" target="_blank"><i class="sricon-Sonaar-symbol"></i> ' . esc_html($textpromo) . ' </a></div>';
            }
        }
        function audiopreview_controller_classes( $field_args, $field ) {
            //Dont enable the options if not pro and ffmpeg not installed.
            $classes = array();
            if (class_exists('Sonaar_Music_Pro_Admin') && 
                method_exists('Sonaar_Music_Pro_Admin', 'is_ffmpeg_exist') && 
                get_site_option('SRMP3_ecommerce') == '1') {
            
                $ffmpeg = Sonaar_Music_Pro_Admin::is_ffmpeg_exist();
                if (!$ffmpeg){
                    array_push($classes, 'ffmpeg-not-installed');
                }
            } else {
                array_push($classes, 'audiopreview-denied');
            }
            
            if (!empty($field->args( 'custom_class' ))) {
                array_push($classes, $field->args( 'custom_class' ));
            }
    
            return $classes;
        }
        function remove_pro_label_if_pro( $field_args, $field ) {
            $classes = array(
                'srmp3-pro-feature',
                'prolabel--nomargin',
            );

            if (!empty($field->args( 'custom_class' ))) {
                array_push($classes, $field->args( 'custom_class' ));
            }

            if ( function_exists('run_sonaar_music_pro')){
                array_push($classes, 'prolabel--nohide');
            }
                return $classes;
        }
        function srmp3_add_tooltip_to_label( $field_args, $field ) {
            $escapedVar = array(
                
                'div' => array(
                    'class' => array(),
                ),
                'em' => array(),
                'strong' => array(),
                'a' => array(
                    'href' => array(),
                    'title' => array(),
                    'target' => array()
                ),
                'img' => array(
                    'src' => array(),
                ),
                'br' => array(),
                'i' => array(
                    'class' => array(),
                ),
            );
            // Get default label
            $value = '';
            $pro_badge = ( $field->tooltip( 'pro' ) ) ? '<div class="srmp3_pro_badge"><i class="sricon-Sonaar-symbol">&nbsp;</i>Pro Feature</div>' : '';
            $field_label = '<label style="display:inline-block;margin-right:4px;">' . esc_html( $field->name() ) . '</label>';
            $field_title = ( !$field->tooltip( 'title' ) ) ? $field->name() : $field->tooltip( 'title' );
            

            if ( $field->tooltip( 'text' ) ) {
                $imgSrc = ($field->tooltip( 'image' )) ?  '<img src="' . esc_url( plugin_dir_url( __FILE__ ) . 'img/tip/' . esc_html($field->tooltip( 'image' ))) . '">' : '';
                $value .= '
                <div class="srmp3_tooltip"><i class="sricon-info"></i>
                    <div class="srmp3_tooltiptext srmp3_tooltip-right">
                        ' . wp_kses($imgSrc, $escapedVar) . '
                        <div class="srmp3_tooltip_title">' . esc_html( $field_title ) . wp_kses($pro_badge, $escapedVar) . '</div>

                        <div class="srmp3_tooltip_desc">' . wp_kses($field->tooltip( 'text' ), $escapedVar) . '</div>
                    </div>
                </div>
                ';
            
            }
            if($field->label_cb() === 'srmp3_add_tooltip_to_label')
                $value = $field_label . $value;

            return $value;
        }
        /**
         * Hook in and register a metabox to handle a theme options page and adds a menu item.
         */
        $escapedVar = array(
                
            'div' => array(
                'class' => array(),
            ),
            'em' => array(),
            'strong' => array(),
            'a' => array(
                'href' => array(),
                'title' => array(),
                'target' => array()
            ),
            'img' => array(
                'src' => array(),
            ),
            'br' => array(),
            'i' => array(
                'class' => array(),
            ),
        );
        $options_name = array();

            /**
             * Registers main options page menu item and form.
             */
            $args = array(
                'id'           => 'sonaar_music_network_option_metabox',
                'title'        => esc_html__( 'Settings', 'sonaar-music' ),
                'object_types' => array( 'options-page' ),
                'option_key'   => 'srmp3_settings_general', // The option key and admin menu page slug. 'yourprefix_main_options',
                'tab_group'    => 'yourprefix_main_options',
                'parent_slug'  => 'edit.php?post_type=' . SR_PLAYLIST_CPT, // Make options page a submenu item of the themes menu. // 'yourprefix_main_options',
                'tab_title'    => esc_html__( 'General', 'sonaar-music' ),
            );

            // 'tab_group' property is supported in > 2.4.0.
            if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
                $args['display_cb'] = 'yourprefix_options_display_with_tabs';
            }

            $general_options = new_cmb2_box( $args );
            array_push($options_name, $general_options);

            /**
             * Options fields ids only need
             * to be unique within this box.
             * Prefix is not needed.
             */

            $general_options->add_field( array(
                'name'          => esc_html__('Audio Player General Settings', 'sonaar-music'),
                'type'          => 'title',
                'id'            => 'music_player_title'
            ) );
            $general_options->add_field( array(
                'name'          => esc_html__('Website Type', 'sonaar-music'),
                'description'   => esc_html__('Music, Podcast or Radio Website?','sonaar-music'),
                'id'            => 'player_type',
                'label_cb'         => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => esc_html__('What is your style?', 'sonaar-music'),
                    'text'      => __('Either you run a Music, Podcast, Audiobook or Radio website, we\'ve got you covered. This only affect how we assign labels & strings in the admin dashboard.<br><br>Turning Podcast Mode On will unlock dedicated podcast features such as an RSS Importer, Subscribe Buttons, Podcast Show taxonomy and other neat features for podcast and audiobooks. <br><br>If you have Music AND Podcast, set Podcast Oriented. This will unlock extra features for the podcast and can also be used for music.', 'sonaar-music'),
                    'image'     => '',
                    'pro'       => '',
                ),
                'type'          => 'select',
                'options'       => array(
                    'classic'    => esc_html__('Music oriented (For Musicians, Artists, Labels, Producers, etc)', 'sonaar-music'),
                    'podcast'    => esc_html__('Podcast oriented (For Podcast, Audiobook, Meditation, etc) ', 'sonaar-music'),
                    'radio'    => esc_html__('Radio oriented (For Radio, Online Station, Live feed, etc) ', 'sonaar-music'),
                ),
                'default'       => 'classic'
            ) );
            $general_options->add_field( array(
                'name' => esc_html__('Player Layout', 'sonaar-music'),
                'label_cb'      => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => esc_html__('Default Player Layout', 'sonaar-music'),
                    'text'      => sprintf(__('We have designed 2 different audio player layouts. One has a floated style with tracklist and the cover image side-by-side while the other has a boxed layout with full-width playlist below the player.<br><br> The player layout is used in the playlist/episode single page. <br><br>You can customize the default player look and feel in Settings > Widget tab.<br><br>You can also customize each player\'s instance with shortcode attributes, Elementor Widget or Gutenberg block. %1$sLearn More%2$s', 'sonaar-music'), '<a href="https://sonaar.io/go/mp3player-shortcode-attributes" target="_blank">', '</a>' ),
                    'image'     => '',
                ),
                'id'   =>   'player_widget_type',
                'type' => 'image_select',
                'width' => '350px',
                'options' => array(
                    'skin_float_tracklist' => array('title' => 'Floated', 'alt' => 'Floated', 'img' => plugin_dir_url( __FILE__ ) . 'img/player_type_floated.svg'),
                    'skin_boxed_tracklist' => array('title' => 'Boxed', 'alt' => 'Boxed', 'img' => plugin_dir_url( __FILE__ ) . 'img/player_type_boxed.svg'),
                ),
                'default' => 'skin_float_tracklist',
            ));
            $general_options->add_field( array(
                'name'          => esc_html('Post Types', 'sonaar-music'),
                //'desc'          => esc_html('Select the post types for which you want to enable playlist creation', 'sonaar-music'),
                'label_cb'      => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => esc_html__('Where are you using the player?', 'sonaar-music'),
                    'text'      => esc_html__('We will display our custom fields in the selected post types so you can add audio tracks by editing their single post.', 'sonaar-music'),
                    'image'     => 'postype.svg',
                ),
                'id'            => 'srmp3_posttypes',
                'type'          => 'multicheck',
                'select_all_button' => false,
                'options'       => get_the_cpt(),
                'default'        => array(SR_PLAYLIST_CPT, 'product'),
            ) );
           
            $general_options->add_field( array(
                'name'          => esc_html__('Waveform Type', 'sonaar-music'),
                'id'            => 'waveformType',
                'type'          => 'select',
                'label_cb'      => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => '',
                    'text'      => __('Choose between 2 different types of progress bars.<br><br>
                    Waveform:<br>Creates an automatic, real soundwave, with a fallback to a simulated one if needed, especially for external audio URLs or > 45 min audio tracks. Use Tools > Generate Peaks to force peak generation for all tracks.<br><br>
                    Simple Bar:<br>Offers a straightforward, clean progress bar without waveform generation.', 'sonaar-music'),
                    'image'     => 'waveform.svg',
                ),
                'options'       => array(
                    'mediaElement'  => 'Waveform',
                    'simplebar'     => 'Simple Bar',
                    //'wavesurfer'    => 'Dynamic Waveform (slower)'
                ),
                'default'       => 'mediaElement',
            ) ); 
            
            $general_options->add_field( array(
                'name'          => esc_html__('Use Simulated Waveform only (save 4kb/waveform)', 'sonaar-music'),
                'classes'       => 'srmp3-settings--subitem',
                'id'            => 'music_player_load_fakewave_only',
                'type'          => 'checkbox',
                'attributes'    => array(
                    'data-conditional-id'    => 'waveformType',
                    'data-conditional-value' => wp_json_encode( array( 'mediaElement' ) ),
                ),
            ) ); 

            $general_options->add_field( array(
                'name'          => esc_html__('Progress Bar Height (px)', 'sonaar-music'),
                'classes'       => 'srmp3-settings--subitem',
                'id'            => 'sr_soundwave_height_simplebar',
                'type'          => 'text_small',
                'attributes'    => array(
                    'type' => 'number',
                    'data-conditional-id'    => 'waveformType',
                    'data-conditional-value' => wp_json_encode( array( 'simplebar' ) ),
                ),
                'default'       => 5,
            ) );  
            
            $general_options->add_field( array(
                'name'          => esc_html__('Bar Width (px)', 'sonaar-music'),
                'classes'       => 'srmp3-settings--subitem',
                'id'            => 'music_player_barwidth',
                'type'          => 'text_small',
                'attributes'    => array(
                    //'type' => 'number',
                    'data-conditional-id'    => 'waveformType',
                    'data-conditional-value' => wp_json_encode( array( 'mediaElement' ) ),
                ),
                'default'       => 1,
            ) );
            $general_options->add_field( array(
                'name'          => esc_html__('Bar Gap (px)', 'sonaar-music'),
                'classes'       => 'srmp3-settings--subitem',
                'id'            => 'music_player_bargap',
                'type'          => 'text_small',
                'attributes'    => array(
                    //'type' => 'number',
                    'data-conditional-id'    => 'waveformType',
                    'data-conditional-value' => wp_json_encode( array( 'mediaElement' ) ),
                ),
                'default'       => 1,
            ) );
            $general_options->add_field( array(
                'name'          => esc_html__('SimpleBar Radius', 'sonaar-music'),
                'classes'       => 'srmp3-settings--subitem',
                'id'            => 'music_player_simplebar_radius',
                'type'          => 'text_small',
                'attributes'    => array(
                    'data-conditional-id'    => 'waveformType',
                    'data-conditional-value' => wp_json_encode( array( 'simplebar' ) ),
                ),
                'default'       => 0,
            ) );

            $general_options->add_field( array(
                'name'          => esc_html__('Bar Line Cap', 'sonaar-music'),
                'classes'       => 'srmp3-settings--subitem',
                'id'            => 'music_player_linecap',
                'type'          => 'select',
                'options'       => array(
                    'square'     => esc_html__( 'Square', 'sonaar-music' ),
                    'round'  => esc_html__( 'Round', 'sonaar-music' ),
                    'butt'     => esc_html__( 'Butt', 'sonaar-music' ),
                ),
                'attributes'    => array(
                    'data-conditional-id'    => 'waveformType',
                    'data-conditional-value' => wp_json_encode( array( 'mediaElement' ) ),
                ),
                'default'       => 'square'
            ) );
            $general_options->add_field( array(
                'name'          => esc_html__('Waveform Max Height', 'sonaar-music'),
                'classes'       => 'srmp3-settings--subitem',
                'id'            => 'sr_soundwave_height',
                'type'          => 'select',
                'options'       => array(
                    "70"      => esc_html__('Default (70px)', 'sonaar-music'),
                    "20"    => esc_html__('Tiny (20px)', 'sonaar-music'),
                    "40"    => esc_html__('Small (40px)', 'sonaar-music'),
                    "120"    => esc_html__('Huge (120px)', 'sonaar-music'),
                ),
                'attributes'    => array(
                    'data-conditional-id'    => 'waveformType',
                    'data-conditional-value' => wp_json_encode( array( 'mediaElement' ) ),
                ),
                //'default'       => 1,
            ) );
            $general_options->add_field( array(
                'name'          => esc_html__('Disable Waveform FadeIn', 'sonaar-music'),
                'classes'       => 'srmp3-settings--subitem',
                'id'            => 'music_player_wave_disable_fadein',
                'type'          => 'checkbox',
                'attributes'    => array(
                    'data-conditional-id'    => 'waveformType',
                    'data-conditional-value' => wp_json_encode( array( 'mediaElement' ) ),
                ),
            ) ); 
            

            $general_options->add_field( array(
                'name'          => esc_html__( 'Playback Speed', 'sonaar-music'),
                'id'            => 'playback_speed',
                'type'          => 'text_medium',
                'default'       => '0.5, 1, 1.2, 1.5, 2',
                'attributes'    => array(
                    'placeholder' => '0.5, 1, 1.2, 1.5, 2',
                ),
            ) );
            if ( function_exists( 'run_sonaar_music_pro' ) ){
                $general_options->add_field( array(
                    'name'          => esc_html__('Default Volume (%)', 'sonaar-music'),
                    'id'            => 'general_volume',
                    'type'          => 'text_small',
                    'default'       => 100,
                ) );
            }
            $general_options->add_field( array(
                'name'          => $this->sr_GetString('Display Artist Name'),
                'id'            => 'show_artist_name',
                'type'          => 'checkbox',
                'label_cb'      => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => '',
                    'text'      => esc_html__('When enabled, we display the artist name beside each of your track. E.g. < Track Title "by" Artist Name >. You can change the separator text label as well.', 'sonaar-music'),
                    'image'     => 'artistname.svg',
                ),
            ) );
            $general_options->add_field( array(
                'name'          => $this->sr_GetString('Artist Name Prefix Separator'),
                'classes'       => 'srmp3-settings--subitem',
                'id'            => 'artist_separator',
                'type'          => 'text_small',
                'default'       => esc_html__('by', 'sonaar-music'),
                'attributes'    => array(
                    'data-conditional-id'    => 'show_artist_name',
                    'data-conditional-value' => 'on',
                    'placeholder' => 'by',
                ),
            ) );
            if ( function_exists( 'run_sonaar_music_pro' ) ){
                $general_options->add_field( array(
                    'name'          => esc_html__('Download Buttons', 'sonaar-music'),
                    'type'          => 'title',
                    'id'            => 'force_cta_download_settings_title'
                ) );
                $general_options->add_field( array(
                    'name'          => __('Download Button Label', 'sonaar-music'),
                    'id'            => 'force_cta_download_label',
                    'type'          => 'text_small',
                    'default'       => esc_html__('Download', 'sonaar-music'),
                    'attributes'    => array(
                        'placeholder' => 'Download',
                    ),
                ) );
                $general_options->add_field( array(
                    'name'          => esc_html__('Add Download Button for each Track', 'sonaar-music'),
                    'id'            => 'force_cta_download',
                    'type'          => 'switch',
                    'label_cb'      => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => '',
                        'text'      => esc_html__('A Download Button will be added for each audio track, allowing users to download their audio files by simply clicking the button.', 'sonaar-music'),
                        'image'     => '',
                    ),
                ) );
                if ( get_site_option('SRMP3_ecommerce') == '1'){
                    $general_options->add_field( array(
                        'name'          => esc_html__('Enable Dynamic Visibility', 'sonaar-music'),
                        'id'            => 'cta_dl_dv_enable_main_settings',
                        'type'          => 'switch',
                        'label_cb'      => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => esc_html__('Control visibility of the Download buttons according to different conditions', 'sonaar-music'),
                            'image'     => '',
                        ),
                    ) );
                    $general_options->add_field( array(
                        'name'          => esc_html__('Visibility State', 'sonaar-music'),
                        'classes'       => 'srmp3-settings--subitem',
                        'id'            => 'cta_dl_dv_state_main_settings',
                        'type'          => 'select',
                        'options' 						=> [
                            '' 							=> __( 'Select a State', 'sonaar-music' ),
                            'show' 						=> __( 'Show button if condition met', 'sonaar-music' ),
                            'hide' 						=> __( 'Hide button if condition met', 'sonaar-music' ),
                        ],
                        'attributes'    => array(
                            'data-conditional-id'    => 'cta_dl_dv_enable_main_settings',
                            'data-conditional-value' => 'true',
                        ),
                        'default'       => ''
                    ) );
                    $general_options->add_field( array(
                        'name'          => esc_html__('Visibility Condition', 'sonaar-music'),
                        'classes'       => 'srmp3-settings--subitem',
                        'id'            => 'cta_dl_dv_condition_main_settings',
                        'type'          => 'select',
                        'options' 						=> [
                            '' 							=> __( 'Select Condition', 'sonaar-music' ),
                            'user_logged_in' 			=> __( 'User logged in', 'sonaar-music' ),
                            'user_logged_out' 			=> __( 'User logged out', 'sonaar-music' ),
                            'user_role_is' 				=> __( 'User Role is', 'sonaar-music' ),
                        ],
                        'attributes'    => array(
                            'data-conditional-id'    => 'cta_dl_dv_enable_main_settings',
                            'data-conditional-value' => 'true',
                        ),
                        'default'       => ''
                    ) ); 
                    
                    $general_options->add_field( array(
                        'name'          => esc_html__('Role is', 'sonaar-music'),
                        'classes'       => 'srmp3-settings--subitem srmp3-settings--subitem2',
                        'id'            => 'cta_dl_dv_role_main_settings',
                        'type'          => 'multicheck',
                        'options'       => Sonaar_Music::get_user_roles(),
                        'attributes'  => array(
                            'data-conditional-id'    => 'cta_dl_dv_condition_main_settings',
                            'data-conditional-value' => wp_json_encode( array( 'user_role_is' ) ),
                            
                        ),
                        'default'       => ''
                    ) );
                    $general_options->add_field( array(
                        'name'          => esc_html__('If condition NOT met, enable redirection on button click', 'sonaar-music'),
                        'classes'       => 'srmp3-settings--subitem',
                        'id'            => 'cta_dl_dv_enable_redirect_main_settings',
                        'type'          => 'switch',
                        'label_cb'      => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => __( 'If user is logged out or not met the conditions, display the button but redirect people to a link or popup', 'sonaar-music'),
                            'image'     => '',
                        ),
                        'attributes'    => array(
                            'data-conditional-id'    => 'cta_dl_dv_enable_main_settings',
                            'data-conditional-value' => 'true',
                        ),
                    ) );
                    $general_options->add_field( array(
                        'name'          => esc_html__('Redirection URL', 'sonaar-music'),
                        'classes'       => 'srmp3-settings--subitem srmp3-settings--subitem2',
                        'id'            => 'cta_dl_dv_redirection_url_main_settings',
                        'type'          => 'text_medium',
                        'label_cb'      => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => __('The URL to redirect when user click on the button. If you are using Elementor Popup, set this to #popup and in the Advanced Tab of your Popup > Open By Selector create an anchor trigger link shortcode (example: a[href="#popup"] )', 'sonaar-music'),
                            'image'     => '',
                        ),
                        'attributes'    => array(
                            'placeholder'            => 'https://yourdomain.com/login',
                            'data-conditional-id'    => 'cta_dl_dv_enable_redirect_main_settings',
                            'data-conditional-value' => 'true',
                        ),
                    ) );
                }
                $general_options->add_field( array(
                    'name'          => esc_html__('View Details Buttons', 'sonaar-music'),
                    'type'          => 'title',
                    'id'            => 'force_cta_postlink_settings_title'
                ) );
                $general_options->add_field( array(
                    'name'          => __('Link Button Label', 'sonaar-music'),
                    'id'            => 'force_cta_singlepost_label',
                    'type'          => 'text_small',
                    'default'       => esc_html__('View Details', 'sonaar-music'),
                    'attributes'    => array(
                        'placeholder' => 'View More',
                    ),
                ) ); 
                $general_options->add_field( array(
                    'name'          => esc_html__('Add Post Link Button for each track', 'sonaar-music'),
                    'id'            => 'force_cta_singlepost',
                    'type'          => 'switch',
                    'label_cb'      => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => '',
                        'text'      => esc_html__('A link button will be added beside each audio track, allowing users to be redirected to the related single playlist post.', 'sonaar-music'),
                        'image'     => '',
                    ),
                ) );
            }
            $general_options->add_field( array(
                'name'          => esc_html__('Podcast RSS Feed', 'sonaar-music'),
                'type'          => 'title',
                'id'            => 'podcast_setting_rssfeed_title',
                'attributes'    => array(
                    'data-conditional-id'    => 'player_type',
                    'data-conditional-value' => 'podcast',
                    'placeholder' => 'podcast',
                ),
            ) );
            $general_options->add_field( array(
                'name'          => esc_html__('Disable the RSS Feed', 'sonaar-music'),
                'id'            => 'podcast_setting_rssfeed_disable',
                'type'          => 'switch',
                'label_cb'      => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => '',
                    'text'      => esc_html__('No RSS Feed will be generated from our plugin', 'sonaar-music'),
                    'image'     => '',
                ),
                'attributes'    => array(
                    'data-conditional-id'    => 'player_type',
                    'data-conditional-value' => 'podcast',
                    'placeholder' => 'podcast',
                ),
            ) );
            $general_options->add_field( array(
                'name'          => esc_html__('Custom RSS Slug', 'sonaar-music'),
                'id'            => 'podcast_setting_rssfeed_redirect',
                'type'          => 'switch',
                'label_cb'      => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => '',
                    'text'      => esc_html__('Change the default "podcast" slug with your own slug for your RSS Feed', 'sonaar-music'),
                    'image'     => '',
                ),
                'attributes'    => array(
                    'data-conditional-id'    => 'player_type',
                    'data-conditional-value' => 'podcast',
                    'placeholder' => 'podcast',
                ),
            ) );
            $general_options->add_field( array(
                'name'          => esc_html__('Podcast RSS Feed Slug', 'sonaar-music'),
                'id'            => 'podcast_setting_rssfeed_slug',
                'classes'       => 'srmp3-settings--subitem',
                'before'        => get_site_url( null, '/feed/'),
                'after'         => '/',
                'type'          => 'text_small',
                'default'       => '',
                'attributes'    => array(
                    'data-conditional-id'    => 'podcast_setting_rssfeed_redirect',
                    'data-conditional-value' => 'true',
                    'placeholder' => 'podcast',
                ),
            ) );
           
            $general_options->add_field( array(
                'name'          => esc_html__('Admin Settings', 'sonaar-music'),
                'type'          => 'title',
                'id'            => 'srmp3_admin_settings'
            ) );
            $general_options->add_field( array(
                'name'          => esc_html__('Collapse/close the tracklist in the admin area'),
                'id'            => 'collapse_tracklist_backend',
                'type'          => 'switch',
                'label_cb'      => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => '',
                    'text'      => esc_html__('When enabled, we will collapse all your track infos under an accordeon style in the admin area. This prevent having an endless scrollbar when editing a post with many tracks.', 'sonaar-music'),
                    'image'     => '',
                ),
            ) );
            $general_options->add_field( array(
                'name'          => esc_html__('Use HTML Text Editor for Track/Episode Descriptions'),
                'id'            => 'use_wysiwyg_for_trackdesc',
                'type'          => 'switch',
                'label_cb'      => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => '',
                    'text'      => __('By enabling this feature, you\'ll be able to use an HTML Text Editor for your Track/Episode Descriptions. This editor provides a richer and more flexible way to format your descriptions.<br><br>IMPORTANT! If your posts have a lot of tracks, activating this feature might make your admin page load slower. This is because for every track on your admin page, a separate HTML Editor needs to be loaded. So, the more tracks you have, the more editors the page has to load, which can lead to noticeable delays.', 'sonaar-music'),
                    'image'     => '',
                ),
            ) );
            $general_options->add_field( array(
                'name'          => esc_html__('Load Sonaar Scripts on Every Pages'),
                'id'            => 'always_load_scripts',
                'type'          => 'checkbox',
                'label_cb'      => 'srmp3_add_tooltip_to_label',
            ) );
          
            /**
             * Registers fourth options page, and set main item as parent.
             */
            $args = array(
                'id'           => 'yourprefix_fourth_options_page',
                'menu_title'   => esc_html__( 'Widget Settings', 'sonaar-music' ),
                'title'        => esc_html__( 'Widget Player Settings', 'sonaar-music' ),
                'object_types' => array( 'options-page' ),
                'option_key'   => 'srmp3_settings_widget_player', // The option key and admin menu page slug. 'yourprefix_tertiary_options',
                'parent_slug'  => 'edit.php?post_type=' . SR_PLAYLIST_CPT, // Make options page a submenu item of the themes menu. //'yourprefix_main_options',
                'tab_group'    => 'yourprefix_main_options',
                'tab_title'    => esc_html__( 'Widget Player', 'sonaar-music' ),
            );

            // 'tab_group' property is supported in > 2.4.0.
            if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
                $args['display_cb'] = 'yourprefix_options_display_with_tabs';
            }

            $widget_player_options = new_cmb2_box( $args );
            array_push($options_name, $widget_player_options);

            if ( function_exists( 'run_sonaar_music_pro' ) ){
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Default Widget Player Settings', 'sonaar-music'),
                    'type'          => 'title',
                    'id'            => 'widget_player_settings'
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Display Skip 15/30 Seconds button', 'sonaar-music'),
                    'id'            => 'player_show_skip_bt',
                    'type'          => 'switch',
                    'default'       => 0,
                    'after'         => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => '',
                        'text'      => esc_html__('A listener just missed something in your track? Add a 15 seconds backward button so he can quickly catch-up. Same thing if he want to quickly skip a segment or two.', 'sonaar-music'),
                        'image'     => 'skip30.svg',
                        'pro'       => true,
                    ),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Display Playback Speed Button', 'sonaar-music'),
                    'id'            => 'player_show_speed_bt',
                    'type'          => 'switch',
                    'default'       => 0,
                    'after'         => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => esc_html__('Default Playback Speed', 'sonaar-music'),
                        'text'      => esc_html__('A speed rate button gives your user the ability to change the playback speed from 0.5x, 1x, 1.2x, 1.5x and 2x', 'sonaar-music'),
                        'image'     => 'speedrate.svg',
                        'pro'       => true,
                    ),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Display Volume Control', 'sonaar-music'),
                    'id'            => 'player_show_volume_bt',
                    'type'          => 'switch',
                    'default'       => 0,
                    'after'         => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => esc_html__('Default Volume Controller Button', 'sonaar-music'),
                        'text'      => esc_html__('We will add a cool volume control under your player so the user may adjust the volume level. The volume level is retained in its browser session.', 'sonaar-music'),
                        'image'     => 'volume.svg',
                        'pro'       => true,
                    ),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Display Shuffle Button', 'sonaar-music'),
                    'id'            => 'player_show_shuffle_bt',
                    'type'          => 'switch',
                    'default'       => 0,
                    'after'         => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => esc_html__('Default Shuffle Button', 'sonaar-music'),
                        'text'      => esc_html__('Allow the ability to shuffle the tracks randomly within the Playlist.', 'sonaar-music'),
                        'image'     => 'shuffle.svg',
                        'pro'       => true,
                    ),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Display Date in the Player', 'sonaar-music'),
                    'id'            => 'player_show_publish_date',
                    'type'          => 'switch',
                    'default'       => 0,
                    'after'         => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => esc_html__('Display Published Date in Player', 'sonaar-music'),
                        'text'      => esc_html__('We will display the published date of the current Playlist/Episode that is being played within the player. You can change the published date by editing the post\'s date.', 'sonaar-music'),
                        'image'     => 'playerdate.svg',
                        'pro'       => true,
                    ),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Display Date in the Tracklist', 'sonaar-music'),
                    'id'            => 'player_show_track_publish_date',
                    'type'          => 'switch',
                    'default'       => 0,
                    'after'         => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => esc_html__('Display Published Dates in Tracklist', 'sonaar-music'),
                        'text'      => esc_html__('We will display the published date for each track in the playlist. Useful if you run a podcast and you want to display dates for each of your episode in the tracklist.', 'sonaar-music'),
                        'image'     => 'tracklistdate.svg',
                        'pro'       => true,
                    ),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Display Total Number of Tracks', 'sonaar-music'),
                    'id'            => 'player_show_tracks_count',
                    'type'          => 'switch',
                    'default'       => 0,
                    'after'         => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => esc_html__('', 'sonaar-music'),
                        'text'      => esc_html__('Sometimes its useful to let your visitor knows how many tracks contains the playlist. We will show this label in the player. Below, you can change and translate the track label for something that better suits your needs such as 10 Songs, Tracks, Episodes, Sermons, etc.', 'sonaar-music'),
                        'image'     => 'totaltrack.svg',
                        'pro'       => true,
                    ),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Display Total Playlist Time Duration', 'sonaar-music'),
                    'id'            => 'player_show_meta_duration',
                    'type'          => 'switch',
                    'default'       => 0,
                    'after'         => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => esc_html__('Display Total Playlist Duration', 'sonaar-music'),
                        'text'      => esc_html__('As the name suggest, we will calculate the sum of each track\'s duration and will display the total amount of the duration in the player. You can change and translate the label of hours and minutes.', 'sonaar-music'),
                        'image'     => 'totaltime.svg',
                        'pro'       => true,
                    ),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Enable Track Redirection to the Single Post', 'sonaar-music'),
                    'id'            => 'player_post_link',
                    'type'          => 'switch',
                    'default'       => 0,
                    'after'         => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => esc_html__('Link to Single Post', 'sonaar-music'),
                        'text'      => esc_html__('When enabled, track titles in your playlist will link to their single posts. This feature is mostly used for podcasters.', 'sonaar-music'),
                        'image'     => 'redirectpost.svg',
                        'pro'       => true,
                    ),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Display Text label for Call-to-Action Icon', 'sonaar-music'),
                    'id'            => 'show_label',
                    'type'          => 'switch',
                    'default'       => 'false',
                    'after'         => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => esc_html__('Text Label for Call-to-Actions', 'sonaar-music'),
                        'text'      => esc_html__('When you add a call to action button for your tracks, we only show the icon by default to maximize the space for the track title. By enabling this option, we will also show its label name.', 'sonaar-music'),
                        'image'     => 'textlabel_cta.svg',
                        'pro'       => true,
                    ),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Hide Track Number', 'sonaar-music'),
                    'id'            => 'player_hide_track_number',
                    'type'          => 'switch',
                    'default'       => 0,
                    'after'         => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => esc_html__('Hide Track Number', 'sonaar-music'),
                        'text'      => esc_html__('Remove the track number in the tracklist.', 'sonaar-music'),
                        //'image'     => 'textlabel_play.svg',
                        'pro'       => true,
                    ),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Display Text Label for the Play Button', 'sonaar-music'),
                    'id'            => 'player_use_play_label',
                    'type'          => 'switch',
                    'default'       => 0,
                    'after'         => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => esc_html__('Text Label instead of Play Icon', 'sonaar-music'),
                        'text'      => esc_html__('Only used for the boxed layout player. We will replace the big Play/Pause Icon in the player by a text button. You can translate the Play & Pause strings by anything you like below.', 'sonaar-music'),
                        'image'     => 'textlabel_play.svg',
                        'pro'       => true,
                    ),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Play Button Label', 'sonaar-music'),
                    'id'            => 'labelPlayTxt',
                    'type'          => 'text_small',
                    'attributes'    => array( 'placeholder' => esc_html__( "Play", 'sonaar-music' ) ),
                    'default'       => esc_html__('Play', 'sonaar-music'),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Pause Button Label', 'sonaar-music'),
                    'id'            => 'labelPauseTxt',
                    'type'          => 'text_small',
                    'attributes'    => array( 'placeholder' => esc_html__( "Pause", 'sonaar-music' ) ),
                    'default'       => esc_html__('Pause', 'sonaar-music'),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Total Number of Tracks Label', 'sonaar-music'),
                    'id'            => 'player_show_tracks_count_label',
                    'type'          => 'text_small',
                    'default'       => esc_html__('Tracks', 'sonaar-music'),
                    'attributes'    => array( 'placeholder' => esc_html__( "Tracks", 'sonaar-music' ) ),
                    'after'         => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'text'      => esc_html__('Label displayed after the total number of tracks. Eg: 6 Tracks, 6 Episodes, 6 Songs, 6 Sermons', 'sonaar-music'),
                        'pro'       => false,
                    ),
                ) );
                if( function_exists( 'run_sonaar_music_pro' ) ){
                    $widget_player_options->add_field( array(
                        'name'          => esc_html__('Title Column Heading', 'sonaar-music'),
                        'id'            => 'tracklist_column_title_label',
                        'type'          => 'text_small',
                        'default'       => esc_html__('Title', 'sonaar-music'),
                        'attributes'    => array( 'placeholder' => esc_html__( "Title", 'sonaar-music' ) ),
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'text'      => esc_html__('Label displayed in the column heading for the Title. Eg: Title, Track Title, Episode Name, Audio Title', 'sonaar-music'),
                            'pro'       => true,
                        ),
                    ) );
                    $widget_player_options->add_field( array(
                        'name'          => esc_html__('Search', 'sonaar-music'),
                        'id'            => 'tracklist_search_label',
                        'type'          => 'text_medium',
                        'default'       => esc_html__('Search', 'sonaar-music'),
                        'attributes'    => array( 'placeholder' => esc_html__( 'Search', 'sonaar-music' ) ),
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'text'      => esc_html__('This is used in the chips result only. When using chips widget, people will see a chip name similar to "Search: Keywords Seached". If you want to change/translate the Search label in the chip, enter your label here', 'sonaar-music'),
                            'pro'       => true,
                        ),
                    ) );
                    $widget_player_options->add_field( array(
                        'name'          => esc_html__('Search Placeholder', 'sonaar-music'),
                        'id'            => 'tracklist_search_placeholder',
                        'type'          => 'text_medium',
                        'default'       => esc_html__('Enter any keyword', 'sonaar-music'),
                        'attributes'    => array( 'placeholder' => esc_html__( 'Enter any keyword', 'sonaar-music' ) ),
                        
                    ) );
                    $widget_player_options->add_field( array(
                        'name'          => esc_html__('No results | Heading', 'sonaar-music'),
                        'id'            => 'tracklist_no_result_1_label',
                        'type'          => 'text_medium',
                        'default'       => esc_html__('Sorry, no results.', 'sonaar-music'),
                        'attributes'    => array( 'placeholder' => esc_html__( 'Sorry, no results.', 'sonaar-music' ) ),
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'text'      => esc_html__('Label displayed as main heading when no results found from a search in the tracklist', 'sonaar-music'),
                            'pro'       => true,
                        ),
                    ) );
                    $widget_player_options->add_field( array(
                        'name'          => esc_html__('No results | Sub heading', 'sonaar-music'),
                        'id'            => 'tracklist_no_result_2_label',
                        'type'          => 'text_medium',
                        'default'       => esc_html__('Please try another keyword', 'sonaar-music'),
                        'attributes'    => array( 'placeholder' => esc_html__( 'Please try another keyword', 'sonaar-music' ) ),
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'text'      => esc_html__('Label displayed just below the main heading when no results found from a search in the tracklist', 'sonaar-music'),
                            'pro'       => true,
                        ),
                    ) );
                }
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Hours Total Duration Label', 'sonaar-music'),
                    'id'            => 'player_hours_label',
                    'type'          => 'text_small',
                    'attributes'    => array( 'placeholder' => esc_html__( "hr.", 'sonaar-music' ) ),
                    'default'       => esc_html__('hr.', 'sonaar-music'),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Minutes Total Duration Label', 'sonaar-music'),
                    'id'            => 'player_minutes_label',
                    'type'          => 'text_small',
                    'attributes'    => array( 'placeholder' => esc_html__( "min.", 'sonaar-music' ) ),
                    'default'       => esc_html__('min.', 'sonaar-music'),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Date Format', 'sonaar-music'),
                    'id'            => 'player_date_format',
                    'type'          => 'text_small',
                    'default'       => '',
                    'attributes'    => array( 'placeholder' => esc_html__( "F j, Y", 'sonaar-music' ) ),
                    'after'         => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => esc_html__('Date Format', 'sonaar-music'),
                        'text'      => sprintf(__('Here are some examples of date format with the result output.<br><br>
                        F j, Y g:i a – November 6, 2010 12:50 am<br>
                        F j, Y – November 6, 2010<br>
                        F, Y – November, 2010<br>
                        g:i a – 12:50 am<br>
                        g:i:s a – 12:50:48 am<br>
                        l, F jS, Y – Saturday, November 6th, 2010<br>
                        M j, Y @ G:i – Nov 6, 2010 @ 0:50<br>
                        Y/m/d \a\t g:i A – 2010/11/06 at 12:50 AM<br>
                        Y/m/d \a\t g:ia – 2010/11/06 at 12:50am<br>
                        Y/m/d g:i:s A – 2010/11/06 12:50:48 AM<br>
                        Y/m/d – 2010/11/06<br><br>%1$sView documentation%2$s on date and time formatting.', 'sonaar-music'), '<a href="https://wordpress.org/support/article/formatting-date-and-time/" target="_blank">', '</a>' ),
                        'pro'       => true,
                    ),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Single Post Settings', 'sonaar-music'),
                    'type'          => 'title',
                    'id'            => 'widget_player_single_post_title'
                ) );
                $widget_player_options->add_field( array(
                    'name'          => sprintf( esc_html__('Single %1$s Page Slug', 'sonaar-music'), ucfirst($this->sr_GetString('playlist'))),
                    'id'            => 'sr_singlepost_slug',
                    'type'          => 'text_medium',
                    'attributes'    => array( 'placeholder' => $this->sr_GetString('album_slug') ),
                    'after'         => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => esc_html__('', 'sonaar-music'),
                        'text'      => sprintf(__('Each single %2$s page has a unique URL and is represented by a slug name. You can replace it by anything you like.<br><br>eg: http://www.domain.com/<strong>%1$s</strong>/post-title', 'sonaar-music'), $this->sr_GetString('album_slug'), $this->sr_GetString('playlist')),
                        'image'     => '',
                        'pro'       => true,
                    ),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Category Slug', 'sonaar-music'),
                    'id'            => 'sr_category_slug',
                    'type'          => 'text_medium',
                    'attributes'    => array( 'placeholder' => $this->sr_GetString('category_slug') ),
                    'after'         => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => esc_html__('', 'sonaar-music'),
                        'text'      => sprintf(__('Each %2$s\'s category page has a unique URL and is represented by a slug name. You can replace it by anything you like.<br><br>eg: http://www.domain.com/<strong>%1$s</strong>/category-title', 'sonaar-music'), $this->sr_GetString('category_slug'), $this->sr_GetString('playlist')),
                        'image'     => '',
                        'pro'       => true,
                    ),
                ) );
                
                if (Sonaar_Music::get_option('player_type', 'srmp3_settings_general') == 'podcast' ){
                    $widget_player_options->add_field( array(
                        'name'          => esc_html__('Podcast Show Slug', 'sonaar-music'),
                        'id'            => 'sr_podcastshow_slug',
                        'type'          => 'text_medium',
                        'attributes'    => array( 'placeholder' => esc_attr('podcast-show') ),
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('', 'sonaar-music'),
                            'text'      => __('A podcast show is like a podcast category but its dedicated for Podcast shows. Main difference is that the podcast show taxonomy contains your podcast settings for your RSS feed.<br><br>Each podcast show page has a unique URL and is represented by a slug name. You can replace it by anything you like.<br><br>eg: http://www.domain.com/<strong>podcast-show</strong>/show-title', 'sonaar-music'),
                            'image'     => '',
                            'pro'       => true,
                        ),
                    ) );
                }
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Adaptive Colors AI', 'sonaar-music'),
                    'id'            => 'sr_single_post_use_dynamic_ai',
                    'type'          => 'switch',
                    'default'       => 0,
                    'after'         => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => esc_html__('Enable Adaptive Colors AI', 'sonaar-music'),
                        'text'      => esc_html__('We will match the look and feel of your player widget to your audio image cover color palette. Works best with boxed layout. ', 'sonaar-music'),
                        'image'     => '',
                        'pro'       => true,
                    ),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Use Advanced Player Shortcode for the Single Post', 'sonaar-music'),
                    'id'            => 'sr_single_post_use_custom_shortcode',
                    'type'          => 'switch',
                    'default'       => 0,
                    'after'         => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => esc_html__('Custom Shortcode in Single Post', 'sonaar-music'),
                        'text'      => sprintf(__('The player in your single %3$s page can be changed/tweaked by entering our shortcode and our supported attributes.<br><br>Will override the default player widget in the single post page by your own shortcode and attributes.<br><br>View shortcode & supported attributes %1$sdocumentation%2$s', 'sonaar-music'), '<a href="https://sonaar.io/go/mp3player-shortcode-attributes" target="_blank">', '</a>', $this->sr_GetString('playlist')),
                        'image'     => '',
                        'pro'       => true,
                    ),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Custom Shortcode', 'sonaar-music'),
                    'classes'       => 'srmp3-settings--subitem',
                    'type'          => 'textarea_small',
                    'id'            => 'sr_single_post_shortcode',
                    'description'          => sprintf( wp_kses( __('For shortcode attributes, %1$s read this article%2$s.','sonaar-music'), $escapedVar), '<a href="https://sonaar.io/go/mp3player-shortcode-attributes" target="_blank">', '</a>'),
                    'default'       => '[sonaar_audioplayer player_layout="skin_boxed_tracklist" sticky_player="true" post_link="false" hide_artwork="false" show_playlist="true" show_track_market="true" show_album_market="true" hide_progressbar="false" hide_times="false" hide_track_title="true"]',
                    'attributes'    => array(
                        'data-conditional-id'    => 'sr_single_post_use_custom_shortcode',
                        'data-conditional-value' => 'true',
                    ),

                ) );                
            }
            $widget_player_options->add_field( array(
                'name'          => esc_html__('Widget Player & Controls', 'sonaar-music'),
                'type'          => 'title',
                'id'            => 'widget_player_controls_title'
            ) );   
            $widget_player_options->add_field( array(
                'id'            => 'music_player_icon_color',
                'type'          => 'colorpicker',
                'name'          => esc_html__('Player Control', 'sonaar-music'),
                'class'         => 'color',
                'default'       => 'rgba(127, 127, 127, 1)',
                'options'       => array(
                    'alpha'         => true, // Make this a rgba color picker.
                ),
            ) );
            $widget_player_options->add_field( array(
                'id'            => 'music_player_artwork_icon_color',
                'type'          => 'colorpicker',
                'name'          => esc_html__('Player Control over Image', 'sonaar-music'),
                'class'         => 'color',
                'default'       => '#f1f1f1',
                'options'       => array(
                    'alpha'         => true, // Make this a rgba color picker.
                ),
            ) );
            if ( function_exists( 'run_sonaar_music_pro' ) ){
                $widget_player_options->add_field( array(
                    'id'            => 'labelPlayColor',
                    'type'          => 'colorpicker',
                    'name'          => esc_html__('Play Text Label', 'sonaar-music'),
                    'class'         => 'color',
                    'default'       => '',
                    'attributes'    => array(
                        'data-conditional-id'    => 'player_use_play_label',
                        'data-conditional-value' => 'true',
                    ),
                ) );
            }
            $widget_player_options->add_field( array(
                'id'            => 'music_player_progress_color',
                'type'          => 'colorpicker',
                'name'          => esc_html__('Waveform/Timeline Progress Color', 'sonaar-music'),
                'class'         => 'color',
                'default'       => 'rgba(13, 237, 180, 1)',
                'options'       => array(
                    'alpha'         => true, // Make this a rgba color picker.
                ),
            ) );
            $widget_player_options->add_field( array(
                'id'            => 'music_player_timeline_color',
                'type'          => 'colorpicker',
                'name'          => esc_html__('Waveform/Timeline Color', 'sonaar-music'),
                'class'         => 'color',
                'default'       => 'rgba(31, 31, 31, 1)',
                'options'       => array(
                    'alpha'         => true, // Make this a rgba color picker.
                ),
            ) );
            $widget_player_options->add_field( array(
                'id'            => 'music_player_bgcolor',
                'type'          => 'colorpicker',
                'name'          => esc_html__('Player Background Color', 'sonaar-music'),
                'desc'          => esc_html__('Apply on boxed player layout and the players in the single post','sonaar-music'),
                'class'         => 'color',
                'options'       => array(
                    'alpha'         => true, // Make this a rgba color picker.
                ),
            ) );
            $widget_player_options->add_field( array(
                'id'            => 'music_player_playlist_bgcolor',
                'type'          => 'colorpicker',
                'name'          => esc_html__('Player Playlist Background Color', 'sonaar-music'),
                'desc'          => esc_html__('Apply on boxed player layout and the players in the single post','sonaar-music'),
                'class'         => 'color',
                'options'       => array(
                    'alpha'         => true, // Make this a rgba color picker.
                ),
            ) );
            $widget_player_options->add_field( array(
                'id'            => 'music_player_coverSize',
                'type'          => 'select',
                'name'          => $this->sr_GetString('Album cover size image source'),
                'show_option_none' => false,
                'default'       => 'large',
                'options'       => music_player_coverSize(),
            ) );
            $widget_player_options->add_field( array(
                'name'          => esc_html__('Tracklist Fonts & Colors', 'sonaar-music'),
                'type'          => 'title',
                'id'            => 'music_player_typography'
            ) );
            $widget_player_options->add_field( array(
                'id'            => 'music_player_album_title',
                'type'          => 'typography',
                'name'          => $this->sr_GetString('Album Title'),
                'fields'        => array(
                    'font-weight' 		=> false,
                    'background' 		=> false,
                    'text-align' 		=> false,
                    'text-transform' 	=> false,
                    'line-height' 		=> false,
                )
            ) );
            $widget_player_options->add_field( array(
                'id'            => 'music_player_date',
                'type'          => 'typography',
                'name'          => $this->sr_GetString('Album Subtitle 2'),
                'fields'        => array(
                    'font-weight' 		=> false,
                    'background' 		=> false,
                    'text-align' 		=> false,
                    'text-transform' 	=> false,
                    'line-height' 		=> false,
                )
            ) );
            $widget_player_options->add_field( array(
                'id'            => 'music_player_metas',
                'type'          => 'typography',
                'name'          => esc_html__('Player meta headings', 'sonaar-music'),
                'fields'        => array(
                    'font-weight' 		=> false,
                    'background' 		=> false,
                    'text-align' 		=> false,
                    'text-transform' 	=> false,
                    'line-height' 		=> false,
                )
            ) );
            $widget_player_options->add_field( array(
                'id'            => 'music_player_playlist',
                'type'          => 'typography',
                'name'          => esc_html__('Tracklist', 'sonaar-music'),
                'fields'        => array(
                    'font-weight' 		=> false,
                    'background' 		=> false,
                    'text-align' 		=> false,
                    'text-transform'    => false,
                    'line-height' 		=> false,
                )
            ) );
            if ( function_exists( 'run_sonaar_music_pro' ) ){
                $widget_player_options->add_field( array(
                    'id'            => 'player_track_desc_style',
                    'type'          => 'typography',
                    'name'          => esc_html__('Tracklist description', 'sonaar-music'),
                    'fields'        => array(
                        'font-weight' 		=> false,
                        'background' 		=> false,
                        'text-align' 		=> false,
                        'text-transform'    => false,
                        'line-height' 		=> false,
                    ),
                ) );
            }
            $widget_player_options->add_field( array(
                'id'            => 'music_player_featured_color',
                'type'          => 'colorpicker',
                'name'          => esc_html__('Tracklist Play/Pause Color', 'sonaar-music'),
                'class'         => 'color',
                'default'       => 'rgba(0, 0, 0, 1)',
                'options'       => array(
                    'alpha'         => true, // Make this a rgba color picker.
                ),
            ) );
            $widget_player_options->add_field( array(
                'name'          => esc_html__('Optional Calls to Action Buttons', 'sonaar-music'),
                'type'          => 'title',
                'id'            => 'CTA_Section_title'
            ) );
            $widget_player_options->add_field( array(
                'id'            => 'music_player_store_drawer',
                'type'          => 'colorpicker',
                'name'          => esc_html__('CTA 3-Dots Drawer Colors', 'sonaar-music'),
                'class'         => 'color',
                'default'       => '#BBBBBB',
                'options'       => array(
                    'alpha'         => true, // Make this a rgba color picker.
                ),
            ) );
            if ( function_exists( 'run_sonaar_music_pro' ) ){
                $widget_player_options->add_field( array(
                    'id'            => 'music_player_wc_bt_color',
                    'type'          => 'colorpicker',
                    'name'          => esc_html__('CTA Text Color', 'sonaar-music'),
                    'class'         => 'color',
                    'default'       => 'rgba(255, 255, 255, 1)',
                ) );
                $widget_player_options->add_field( array(
                    'id'            => 'music_player_wc_bt_bgcolor',
                    'type'          => 'colorpicker',
                    'name'          => esc_html__('CTA Button Color', 'sonaar-music'),
                    'class'         => 'color',
                    'default'       => 'rgba(0, 0, 0, 1)',
                    'options'       => array(
                        'alpha'         => true, // Make this a rgba color picker.
                    ),
                ) );
                $widget_player_options->add_field( array(
                    'name'          => esc_html__('Load All Font-Awesome Icons Library', 'sonaar-music'),
                    'id'            => 'cta_load_all_icons',
                    'type'          => 'switch',
                    'default'       => 0,
                    'after'         => 'srmp3_add_tooltip_to_label',
                    'tooltip'       => array(
                        'title'     => '',
                        'text'      => __('By default, we display only the most popular and widely-used icons in the call-to-action icon selector. Activating this feature will show all icons in the CTA\'s icon picker.<br><br>Be aware, this could potentially reduce the speed of your admin page due to numerous entries. With this setting, over 1000 icons become accessible in the selector. Opt for this only if it\'s absolutely necessary.', 'sonaar-music'),
                        'image'     => '',
                        'pro'       => true,
                    ),
                ) );
            }
                if ( !function_exists('run_sonaar_music_pro')){
                    $widget_player_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Pro Options', 'sonaar-music'),
                        'type'          => 'title',
                        'id'            => 'promo_music_player_sticky_title',
                        'after'         => 'promo_ad_cb',
                    ) );
                    $widget_player_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Display Volume Control', 'sonaar-music'),
                        'id'            => 'promo_player_show_volume_bt',
                        'type'          => 'switch',
                        'default'       => 0,
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('Default Volume Controller Button', 'sonaar-music'),
                            'text'      => esc_html__('We will add a cool volume control under your player so the user may adjust the volume level. The volume level is retained in its browser session.', 'sonaar-music'),
                            'image'     => 'volume.svg',
                            'pro'       => true,
                        ),
                    ) );
                    $widget_player_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Display Skip 15/30 Seconds button', 'sonaar-music'),
                        'id'            => 'promo_player_show_skip_bt',
                        'type'          => 'switch',
                        'default'       => 0,
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => esc_html__('A listener just missed something in your track? Add a 15 seconds backward button so he can quickly catch-up. Same thing if he want to quickly skip a segment or two.', 'sonaar-music'),
                            'image'     => 'skip30.svg',
                            'pro'       => true,
                        ),
                    ) );
                    $widget_player_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Display Playback Speed Button', 'sonaar-music'),
                        'id'            => 'promo_player_show_speed_bt',
                        'type'          => 'switch',
                        'default'       => 0,
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('Default Playback Speed', 'sonaar-music'),
                            'text'      => esc_html__('A speed rate button gives your user the ability to change the playback speed from 0.5x, 1x, 1.2x, 1.5x and 2x', 'sonaar-music'),
                            'image'     => 'speedrate.svg',
                            'pro'       => true,
                        ),
                    ) );
                    $widget_player_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Display Shuffle Button', 'sonaar-music'),
                        'id'            => 'promo_player_show_shuffle_bt',
                        'type'          => 'switch',
                        'default'       => 0,
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('Default Shuffle Button', 'sonaar-music'),
                            'text'      => esc_html__('Allow the ability to shuffle the tracks randomly within the Playlist.', 'sonaar-music'),
                            'image'     => 'shuffle.svg',
                            'pro'       => true,
                        ),
                    ) );
                    $widget_player_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Display Dates', 'sonaar-music'),
                        'id'            => 'promo_player_show_track_publish_date',
                        'type'          => 'switch',
                        'default'       => 0,
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('Display Published Dates', 'sonaar-music'),
                            'text'      => esc_html__('We will display the published date for each track in the playlist. Useful if you run a podcast and you want to display dates for each of your episode in the tracklist.', 'sonaar-music'),
                            'image'     => 'tracklistdate.svg',
                            'pro'       => true,
                        ),
                    ) );
                    $widget_player_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Display Text label for Call-to-Action Icon', 'sonaar-music'),
                        'id'            => 'promo_show_label',
                        'type'          => 'switch',
                        'default'       => 'false',
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('Text Label for Call-to-Actions', 'sonaar-music'),
                            'text'      => esc_html__('When you add a call to action button for your tracks, we only show the icon by default to maximize the space for the track title. By enabling this option, we will also show its label name.', 'sonaar-music'),
                            'image'     => 'textlabel_cta.svg',
                            'pro'       => true,
                        ),
                    ) );
                    $widget_player_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => sprintf( esc_html__('Single %1$s Page Slug', 'sonaar-music'), ucfirst($this->sr_GetString('playlist'))),
                        'id'            => 'promo_sr_singlepost_slug',
                        'type'          => 'text_medium',
                        'attributes'    => array( 'placeholder' => $this->sr_GetString('album_slug') ),
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('', 'sonaar-music'),
                            'text'      => sprintf(__('Each single %2$s page has a unique URL and is represented by a slug name. You can replace it by anything you like.<br><br>eg: http://www.domain.com/<strong>%1$s</strong>/post-title', 'sonaar-music'), $this->sr_GetString('album_slug'), $this->sr_GetString('playlist')),
                            'image'     => '',
                            'pro'       => true,
                        ),
                    ) );
                    $widget_player_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Category Slug', 'sonaar-music'),
                        'id'            => 'promo_sr_category_slug',
                        'type'          => 'text_medium',
                        'attributes'    => array( 'placeholder' => $this->sr_GetString('category_slug') ),
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('', 'sonaar-music'),
                            'text'      => sprintf(__('Each %2$s\'s category page has a unique URL and is represented by a slug name. You can replace it by anything you like.<br><br>eg: http://www.domain.com/<strong>%1$s</strong>/category-title', 'sonaar-music'), $this->sr_GetString('category_slug'), $this->sr_GetString('playlist')),
                            'image'     => '',
                            'pro'       => true,
                        ),
                    ) );
                    if (Sonaar_Music::get_option('player_type', 'srmp3_settings_general') == 'podcast' ){
                        $widget_player_options->add_field( array(
                            'classes'       => 'srmp3-pro-feature',
                            'name'          => esc_html__('Podcast Show Slug', 'sonaar-music'),
                            'id'            => 'promo_sr_podcastshow_slug',
                            'type'          => 'text_medium',
                            'attributes'    => array( 'placeholder' => esc_attr('podcast-show') ),
                            'after'         => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'title'     => esc_html__('', 'sonaar-music'),
                                'text'      => __('A podcast show is like a podcast category but its dedicated for Podcast shows. Main difference is that the podcast show taxonomy contains your podcast settings for your RSS feed.<br><br>Each podcast show page has a unique URL and is represented by a slug name. You can replace it by anything you like.<br><br>eg: http://www.domain.com/<strong>podcast-show</strong>/show-title', 'sonaar-music'),
                                'image'     => '',
                                'pro'       => true,
                            ),
                        ) );
                    }
                    $widget_player_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Use Advanced Player Shortcode for the Single Post', 'sonaar-music'),
                        'id'            => 'promo_sr_single_post_use_custom_shortcode',
                        'type'          => 'switch',
                        'default'       => 0,
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('Custom Shortcode in Single Post', 'sonaar-music'),
                            'text'      => sprintf(__('The player in your single %3$s page can be changed/tweaked by entering our shortcode and our supported attributes.<br><br>Will override the default player widget in the single post page by your own shortcode and attributes.<br><br>View shortcode & supported attributes %1$sdocumentation%2$s', 'sonaar-music'), '<a href="https://sonaar.io/go/mp3player-shortcode-attributes" target="_blank">', '</a>', $this->sr_GetString('playlist')),
                            'image'     => '',
                            'pro'       => true,
                        ),
                    ) );
                }

            
                /**
                 * Registers secondary options page, and set main item as parent.
                 */
                $args = array(
                    'id'           => 'yourprefix_secondary_options_page',
                    'title'        => esc_html__( 'Sticky Player Settings', 'sonaar-music' ),
                    'menu_title'   => esc_html__( 'Sticky Player Settings', 'sonaar-music' ),
                    'object_types' => array( 'options-page' ),
                    'option_key'   => 'srmp3_settings_sticky_player', // The option key and admin menu page slug. 'yourprefix_secondary_options',
                    'parent_slug'  => 'edit.php?post_type=' . SR_PLAYLIST_CPT, // Make options page a submenu item of the themes menu. // 'yourprefix_main_options',
                    'tab_group'    => 'yourprefix_main_options',
                    'tab_title'    => esc_html__( 'Sticky Player', 'sonaar-music' ),
                );

                // 'tab_group' property is supported in > 2.4.0.
                if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
                    $args['display_cb'] = 'yourprefix_options_display_with_tabs';
                }

                $sticky_player_options = new_cmb2_box( $args );
                array_push($options_name, $sticky_player_options);

                if ( !function_exists( 'run_sonaar_music_pro' ) ){
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Sticky Player Settings', 'sonaar-music'),
                        'type'          => 'title',
                        'id'            => 'promo_music_player_sticky_title',
                        'after'         => 'promo_ad_cb',
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Enable Sticky Player', 'sonaar-music'),
                        'id'            => 'promo_enable_sticky_player',
                        'type'          => 'switch',
                        'default'       => 0,
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('', 'sonaar-music'),
                            'text'      => sprintf(__('We will automatically display the sticky footer player on your site.<br><br>Create a %1$s post in WP-Admin > MP3 Player then set your audio track(s) using our custom fields.<br><br>Come back here and choose the %1$s(s) post to play in the sticky player.<br><br>Note that you can also enable the sticky player on each player instance and widget.', 'sonaar-music'), $this->sr_GetString('playlist')),
                            'image'     => 'sticky.svg',
                            'pro'       => true,
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Enable Continuous Player', 'sonaar-music'),
                        'id'            => 'promo_enable_continuous_player',
                        'type'          => 'switch',
                        'default'       => 0,
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('', 'sonaar-music'),
                            'text'      => sprintf(__('Having a continuous audio playback is a stunning feature and will improve the overall UX of your website.<br><br>The concept is pretty simple. Your visitor starts the audio player from any player on your site. We save the revelant times in a cookie. When user loads a new page, everything is reloaded but the audio player resume where it left.<br><br>You can also exclude pages to prevent sticky player loads on them.<br><br>%1$sLearn More About Continuous Player%2$s', 'sonaar-music'), '<a href="https://sonaar.io/tips-and-tricks/continuous-audio-player-on-wordpress/" target="_blank">', '</a>'),
                            'image'     => 'continuous.svg',
                            'pro'       => true,
                        ),
                    ) );
                }
                if ( function_exists( 'run_sonaar_music_pro' ) ){
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Sticky Player Settings', 'sonaar-music'),
                        'type'          => 'title',
                        'id'            => 'music_player_sticky_title'
                    ) );
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__( 'Select Playlist IDs to play in Sticky Player when site loads', 'sonaar-music'),
                        'id'            => 'overall_sticky_playlist',
                        'type'          => 'post_search_text', // This field type
                        'post_type'     => ( Sonaar_Music::get_option('srmp3_posttypes', 'srmp3_settings_general') != null ) ? Sonaar_Music::get_option('srmp3_posttypes', 'srmp3_settings_general') : SR_PLAYLIST_CPT,
                        'desc'          => sprintf(__('Enter a comma separated list of playlist IDs. Enter <i>latest</i> to always load the latest published %1$s playlist. Click the magnifying glass to search for playlist','sonaar-music'), $this->sr_GetString('playlist') ),
                        // Default is 'checkbox', used in the modal view to select the post type
                        'select_type'   => 'checkbox',
                        // Will replace any selection with selection from modal. Default is 'add'
                        'select_behavior' => 'add',
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('', 'sonaar-music'),
                            'text'      => sprintf(__('We will automatically display the sticky footer player on your site.<br><br>Create a %1$s post in WP-Admin > MP3 Player then set your audio track(s) using our custom fields.<br><br>Come back here and enter the %1$s(s) post ID to play in the sticky player.<br><br>Enter \'latest\' (without single quotes) to automatically play the latest published post.<br><br>Note that you can also enable the sticky player on each player instance and widget.', 'sonaar-music'), $this->sr_GetString('playlist')),
                            'image'     => 'sticky.svg',
                            'pro'       => true,
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Use Sticky Player in the single post', 'sonaar-music'),
                        'id'            => 'use_sticky_cpt',
                        'type'          => 'switch',
                        'default'       => 0,
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('', 'sonaar-music'),
                            'text'      => sprintf(__('Launch the sticky player when user click play from the single %1$s post page. Default is disabled.', 'sonaar-music'), $this->sr_GetString('playlist')),
                            'image'     => '',
                            'pro'       => true,
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Display Previous/Next button', 'sonaar-music'),
                        'id'            => 'sticky_show_nextprevious_bt',
                        'type'          => 'switch',
                        'default'       => 'true',
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('', 'sonaar-music'),
                            'text'      => esc_html__('Display the previous/next track button in the sticky player. Default is enabled.', 'sonaar-music'),
                            'image'     => 'nextprevious.svg',
                            'pro'       => true,
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Display Skip 15/30 seconds button', 'sonaar-music'),
                        'id'            => 'sticky_show_skip_bt',
                        'type'          => 'switch',
                        'default'       => 0,
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('', 'sonaar-music'),
                            'text'      => esc_html__('A listener just missed something in your track? Add a 15 seconds backward button so he can quickly catch-up. Same thing if he want to quickly skip a segment or two.', 'sonaar-music'),
                            'image'     => 'skip30.svg',
                            'pro'       => true,
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__( 'Show Speed Lecture button (0.5x, 1x, 2x)', 'sonaar-music'),
                        'id'            => 'sticky_show_speed_bt',
                        'type'          => 'switch',
                        'default'       => 0,
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('Default Playback Speed', 'sonaar-music'),
                            'text'      => esc_html__('A speed rate button gives your user the ability to change the playback speed from 0.5x, 1x, 1.2x, 1.5x and 2x', 'sonaar-music'),
                            'image'     => 'speedrate.svg',
                            'pro'       => true,
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Display Tracklist button', 'sonaar-music'),
                        'id'            => 'sticky_show_tracklist_bt',
                        'type'          => 'switch',
                        'default'       => 'true',
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('', 'sonaar-music'),
                            'text'      => esc_html__('Display a tracklist button on the sticky player to show all tracks in the playlist. Default is enabled.', 'sonaar-music'),
                            'image'     => 'tracklist.svg',
                            'pro'       => true,
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Show Related Post in Tracklist', 'sonaar-music'),
                        'id'            => 'sticky_show_related-post',
                        'type'          => 'switch',
                        'default'       => 0,
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('', 'sonaar-music'),
                            'text'      => sprintf(__('When enabled, we will show all tracks from all posts related to the same category than the current track being played.<br><br>These tracks will appear when tracklist button is clicked in the sticky player.<br><br>This is useful if sticky player is launched from a single post by example, and you want to show all other %1$s related to this post in the sticky.', 'sonaar-music'), $this->sr_GetString('playlist')),
                            'image'     => 'tracklist.svg',
                            'pro'       => true,
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Display Shuffle button', 'sonaar-music'),
                        'id'            => 'sticky_show_shuffle_bt',
                        'type'          => 'switch',
                        'default'       => 'true',
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('', 'sonaar-music'),
                            'text'      => esc_html__('Allow the ability to shuffle the tracks randomly within the Playlist.', 'sonaar-music'),
                            'image'     => 'shuffle.svg',
                            'pro'       => true,
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Display Track Description', 'sonaar-music'),
                        'id'            => 'sticky_show_description',
                        'type'          => 'switch',
                        'default'       => 'true',
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('', 'sonaar-music'),
                            'image'     => 'shuffle.svg',
                            'pro'       => true,
                        ),
                    ) );
                    
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Sticky Player Preset', 'sonaar-music'),
                        'id'            => 'sticky_preset',
                        'type'          => 'select',
                        'options'       => array(
                            'fullwidth'         => esc_html__('Fullwidth', 'sonaar-music'),
                            'mini_fullwidth'    => esc_html__('Mini Fullwidth', 'sonaar-music'),
                            'float'             => esc_html__('Float', 'sonaar-music'),
                        ),
                        'default'       => 'fullwidth',
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('', 'sonaar-music'),
                            'text'      => __('We have 3 different layouts for the sticky player. <br><br><strong>Fullwidth</strong> is a full width and 90px tall player.<br><br><strong>Mini Fullwidth</strong> is a full width player but 42px tall.<br><br><strong>Float</strong> is a floated & draggable sticky player that can be positioned on the left, center or right bottom of your screen. It\'s more discreet.', 'sonaar-music'),
                            'image'     => '',
                            'pro'       => true,
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Float Position', 'sonaar-music'),
                        'classes'       => 'srmp3-settings--subitem',
                        'id'            => 'float_pos',
                        'type'          => 'select',
                        'options'       => array(
                            'left'         => esc_html__('Left', 'sonaar-music'),
                            'center'    => esc_html__('Center', 'sonaar-music'),
                            'right'             => esc_html__('Right (Default)', 'sonaar-music'),
                        ),
                        'default'       => 'right',
                        'attributes'  => array(
                            'data-conditional-id'    => 'sticky_preset',
                            'data-conditional-value' => wp_json_encode( array( 'float' ) ),
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Allow users to drag the player', 'sonaar-music'),
                        'id'            => 'make_draggable',
                        'type'          => 'switch',
                        'default'       => 0,
                        'attributes'  => array(
                            'data-conditional-id'    => 'sticky_preset',
                            'data-conditional-value' => wp_json_encode( array( 'float' ) ),
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Sticky Roundness (px)', 'sonaar-music'),
                        'id'            => 'float_radius',
                        'type'          => 'text_small',
                        'default'       => 30,
                        'attributes'  => array(
                            'data-conditional-id'    => 'sticky_preset',
                            'data-conditional-value' => wp_json_encode( array( 'float' ) ),
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Show Controls on Hover Only', 'sonaar-music'),
                        'desc'          => esc_html__('User have to hover sticky player to display controls','sonaar-music'),
                        'id'            => 'show_controls_hover',
                        'type'          => 'switch',
                        'default'       => 1,
                        'attributes'  => array(
                            'data-conditional-id'    => 'sticky_preset',
                            'data-conditional-value' => wp_json_encode( array( 'float' ) ),
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Hide Progress Bar', 'sonaar-music'),
                        'id'            => 'sticky_hide_progress_bar',
                        'type'          => 'switch',
                        'default'       => 1,
                        'attributes'  => array(
                            'data-conditional-id'    => 'sticky_preset',
                            'data-conditional-value' => wp_json_encode( array( 'float' ) ),
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Enable shuffle mode', 'sonaar-music'),
                        'id'            => 'overall_shuffle',
                        'type'          => 'checkbox',
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('', 'sonaar-music'),
                            'text'      => esc_html__('When the sticky footer player kicks in, we will automatically set the shuffle mode On so the tracks play randomly.', 'sonaar-music'),
                            'image'     => 'shuffle.svg',
                            'pro'       => true,
                        ),
                    ) );

                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Continuous/Persistent Audio Player', 'sonaar-music'),
                        'type'          => 'title',
                        'id'            => 'enable_continuous_player_title'
                    ) );
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Enable Continuous Player', 'sonaar-music'),
                        'id'            => 'enable_continuous_player',
                        'type'          => 'switch',
                        'default'       => 0,
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('', 'sonaar-music'),
                            'text'      => sprintf(__('Having a continuous audio playback is a stunning feature and will improve the overall UX of your website.<br><br>The concept is pretty simple. Your visitor starts the audio player from any player on your site. We save the revelant times in a cookie. When user loads a new page, everything is reloaded but the audio player resume where it left.<br><br>You can also exclude pages to prevent sticky player loads on them.<br><br>%1$sLearn More About Continuous Player%2$s', 'sonaar-music'), '<a href="https://sonaar.io/tips-and-tricks/continuous-audio-player-on-wordpress/" target="_blank">', '</a>'),
                            'image'     => 'continuous.svg',
                            'pro'       => true,
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'name'              => esc_html__('Set Duration for Continuous Playback Memory via Browser Cookie', 'sonaar-music'),
                        'classes'       => 'srmp3-settings--subitem',
                        'id'                => 'srmp3_cookie_expiration',
                        'type'              => 'text',
                        'desc'              => esc_html__('Define the duration (in seconds) for remembering the playback position in the user\'s browser. For example, 1 hour equals 3600; 12 hours equals 43200. The continuous playback state is cleared after this period. Use \'default\' to clear when the browser session ends.', 'sonaar-music'),
                        'default'           => '3600',
                        'attributes'    => array(
                            'data-conditional-id'    => 'enable_continuous_player',
                            'data-conditional-value' => 'true',
                        ),
                    ));
                    $sticky_player_options->add_field( array(
                        'name'              => esc_html__('Exclude Continuous Player to PLAY on the following slug URL(s)  ', 'sonaar-music'),
                        'classes'       => 'srmp3-settings--subitem',
                        'id'                => 'sr_prevent_continuous_url',
                        'type'              => 'textarea_small',
                        'desc'              => esc_html__('Prevent continuous player to auto-resume on these excluded URLs. One path URL per line (eg: /cart/ )', 'sonaar-music'),
                        'attributes'    => array(
                            'data-conditional-id'    => 'enable_continuous_player',
                            'data-conditional-value' => 'true',
                        ),
                    ));
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Hide Sticky Player on the excluded URLs above', 'sonaar-music'),
                        'classes'       => 'srmp3-settings--subitem',
                        'id'            => 'sr_prevent_continuous_sticky_to_show',
                        'type'          => 'switch',
                        'default'       => 1,
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'attributes'    => array(
                            'data-conditional-id'    => 'enable_continuous_player',
                            'data-conditional-value' => 'true',
                        ),
                        'tooltip'       => array(
                            'title'     => esc_html__('Hide/Remove the sticky player on the excluded URLs', 'sonaar-music'),
                            'text'      => esc_html__('This will prevent the sticky player to be displayed on the excluded slug URLs as well', 'sonaar-music'),
                            'pro'       => true,
                        ),
                    ) );
                    
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Animated Audio Spectrum', 'sonaar-music'),
                        'type'          => 'title',
                        'id'            => 'sticky_spectro_title'
                    ) );
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Spectro Animation', 'sonaar-music'),
                        'id'            => 'sticky_spectro_style',
                        'type'          => 'select',
                        'options'       => array(
                            'none' 			=> esc_html__( 'None', 'sonaar-music' ),
                            'bars' 			=> esc_html__( 'Animated Bars', 'sonaar-music' ),
                            'bricks' 		=> esc_html__( 'Animated Bricks', 'sonaar-music' ),
                            'shockwave' 	=> esc_html__( 'Animated Shockwaves', 'sonaar-music' ),
                            'string' 		=> esc_html__( 'Animated String', 'sonaar-music' ),
                        ),
                        'default'       => 'none',
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field',
                        'name'          => esc_html__('Add Shadow FX', 'sonaar-music'),
                        'id'            => 'sticky_spectro_shadow',
                        'type'          => 'switch',
                        'default'       => 'false',
                        'attributes'  => array(
                            'data-conditional-id'    => 'sticky_spectro_style',
                            'data-conditional-value' => wp_json_encode( array( 'bars' , 'shockwave' , 'string' ) ),
                            
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field',
                        'name'          => esc_html__('Add Reflection FX', 'sonaar-music'),
                        'id'            => 'sticky_spectro_reflect',
                        'type'          => 'switch',
                        'default'       => 'false',
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field',
                        'name'          => esc_html__('Sharp Peaks', 'sonaar-music'),
                        'id'            => 'sticky_spectro_sharpends',
                        'type'          => 'switch',
                        'default'       => 'false',
                        'attributes'  => array(
                            'data-conditional-id'    => 'sticky_spectro_style',
                            'data-conditional-value' => wp_json_encode( array( 'bricks' ) ),
                            
                        ),
                        
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field',
                        'id'            => 'sticky_spectro_color1',
                        'type'          => 'colorpicker',
                        'name'          => esc_html__('Color 1', 'sonaar-music'),
                        'class'         => 'color',
                        'default'       => 'rgba(116, 221, 199, 1)',
                        'options'       => array(
                            'alpha'         => true, // Make this a rgba color picker.
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field',
                        'id'            => 'sticky_spectro_color2',
                        'type'          => 'colorpicker',
                        'name'          => esc_html__('Color 2 (Will enable a gradient)', 'sonaar-music'),
                        'class'         => 'color',
                        'options'       => array(
                            'alpha'         => true, // Make this a rgba color picker.
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field',
                        'name'          => esc_html__('Gradient Direction', 'sonaar-music'),
                        'id'            => 'sticky_spectro_gradientdir',
                        'type'          => 'select',
                        'options'       => array(
                            'vertical' 			=> esc_html__( 'Vertical', 'sonaar-music' ),
                            'horizontal' 		=> esc_html__( 'Horizontal', 'sonaar-music' ),
                        ),
                        'default'       => 'vertical',
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field',
                        'name' => esc_html__('Shockwave Vibrance', 'sonaar-music'),
                        'id'   => 'sticky_spectro_vibrance',
                        'default' => 40,
                        'type' => 'text_small',
                        'attributes' => array(
                            'type' => 'number',
                            'pattern' => '\d*',
                            'data-conditional-id'    => 'sticky_spectro_style',
                            'data-conditional-value' => wp_json_encode( array( 'shockwave' ) )
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field',
                        'name' => esc_html__('Bar Count', 'sonaar-music'),
                        'id'   => 'sticky_spectro_barcount',
                        'default' => 100,
                        'type' => 'text_small',
                        'attributes' => array(
                            'type' => 'number',
                            'pattern' => '\d*',
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field',
                        'name' => esc_html__('Bar Width', 'sonaar-music'),
                        'after_field'     => ' px',
                        'id'   => 'sticky_spectro_barwidth',
                        'default' => 2,
                        'type' => 'text_small',
                        'attributes' => array(
                            'type' => 'number',
                            'pattern' => '\d*',
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field',
                        'name' => esc_html__('Brick Height', 'sonaar-music'),
                        'after_field'     => ' px',
                        'id'   => 'sticky_spectro_blockheight',
                        'default' => 2,
                        'type' => 'text_small',
                        'attributes' => array(
                            'type' => 'number',
                            'pattern' => '\d*',
                            'data-conditional-id'    => 'sticky_spectro_style',
                            'data-conditional-value' => wp_json_encode( array( 'bricks' ) )
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field',
                        'name' => esc_html__('Brick Vertical Gap', 'sonaar-music'),
                        'after_field'     => ' px',
                        'id'   => 'sticky_spectro_blockgap',
                        'default' => 2,
                        'type' => 'text_small',
                        'attributes' => array(
                            'type' => 'number',
                            'pattern' => '\d*',
                            'data-conditional-id'    => 'sticky_spectro_style',
                            'data-conditional-value' => wp_json_encode( array( 'bricks' ) )
                        ),
                    ) );

                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field',
                        'name' => esc_html__('Bar Gap', 'sonaar-music'),
                        'after_field'     => ' px',
                        'id'   => 'sticky_spectro_bargap',
                        'default' => 2,
                        'type' => 'text_small',
                        'attributes' => array(
                            'type' => 'number',
                            'pattern' => '\d*',
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field',
                        'name' => esc_html__('Canvas Height', 'sonaar-music'),
                        'after_field'     => ' px',
                        'id'   => 'sticky_spectro_canvasheight',
                        'default' => 70,
                        'type' => 'text_small',
                        'attributes' => array(
                            'type' => 'number',
                            'pattern' => '\d*',
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field',
                        'name'          => esc_html__('Bars Alignment', 'sonaar-music'),
                        'id'            => 'sticky_spectro_valign',
                        'type'          => 'select',
                        'options'       => array(
                            'bottom'    => esc_html__('Bottom', 'sonaar-music'),
                            'middle'    => esc_html__('Middle', 'sonaar-music'),
                            'top'       => esc_html__('Top', 'sonaar-music'),
                        ),
                        'default'       => 'bottom',
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field',
                        'name'          => esc_html__('Remove Progress Bar?', 'sonaar-music'),
                        'id'            => 'sticky_spectro_container',
                        'type'          => 'select',
                        'options'       => array(
                            'inside' 		=> esc_html__( 'Yes', 'sonaar-music' ),
                            'outside' 			=> esc_html__( 'No', 'sonaar-music' ),
                        ),
                        'default'       => 'inside',
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field srmp3-settings--subitem',
                        'name' => esc_html__('Spectro Bottom Position', 'sonaar-music'),
                        'after_field'     => ' px',
                        'id'   => 'sticky_spectro_posbottom',
                        'default' => 9,
                        'type' => 'text_small',
                        'attributes' => array(
                            'type' => 'number',
                            'data-conditional-id'    => 'sticky_spectro_container',
                            'data-conditional-value' => wp_json_encode( array( 'outside' ) )
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field srmp3-settings--subitem',
                        'name' => esc_html__('Spectro Left Position', 'sonaar-music'),
                        'after_field'     => ' px',
                        'id'   => 'sticky_spectro_posleft',
                        'default' => 0,
                        'type' => 'text_small',
                        'attributes' => array(
                            'type' => 'number',
                            'data-conditional-id'    => 'sticky_spectro_container',
                            'data-conditional-value' => wp_json_encode( array( 'outside' ) )
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field srmp3-settings--subitem',
                        'name' => esc_html__('Small Device Spectro Bottom Position', 'sonaar-music'),
                        'after_field'     => ' px',
                        'id'   => 'mobile_sticky_spectro_posbottom',
                        'default' => 9,
                        'type' => 'text_small',
                        'attributes' => array(
                            'type' => 'number',
                            'data-conditional-id'    => 'sticky_spectro_container',
                            'data-conditional-value' => wp_json_encode( array( 'outside' ) )
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field srmp3-settings--subitem',
                        'name' => esc_html__('Small Device Spectro Left Position', 'sonaar-music'),
                        'after_field'     => ' px',
                        'id'   => 'mobile_sticky_spectro_posleft',
                        'default' => 0,
                        'type' => 'text_small',
                        'attributes' => array(
                            'type' => 'number',
                            'data-conditional-id'    => 'sticky_spectro_container',
                            'data-conditional-value' => wp_json_encode( array( 'outside' ) )
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'classes'       => 'srmp3_spectro_field',
                        'name'          => esc_html__('Responsive', 'sonaar-music'),
                        'id'            => 'sticky_spectro_responsive',
                        'type'          => 'select',
                        'options'       => array(
                            'all'       => esc_html__('Show on all devices', 'sonaar-music'),
                            'hide_tablet'    => esc_html__('Hide on Tablet & Mobile', 'sonaar-music'),
                            'hide_mobile'    => esc_html__('Hide on Mobile only', 'sonaar-music'),
                        ),
                        'default'       => 'all',
                    ) );







                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Sticky Player Typography and Colors', 'sonaar-music'),
                        'type'          => 'title',
                        'id'            => 'music_player_sticky_lookandfeel_title'
                    ) );
                    
                    $sticky_player_options->add_field( array(
                        'id'            => 'sticky_player_typo',
                        'type'          => 'typography',
                        'name'          => esc_html__('Typography', 'sonaar-music'),
                        'fields'        => array(
                            'font-weight'       => false,
                            'background'        => false,
                            'text-align'        => false,
                            'text-transform'    => false,
                            'line-height'       => false,
                        )
                    ) );
                    $sticky_player_options->add_field( array(
                        'id'            => 'sticky_player_featured_color',
                        'type'          => 'colorpicker',
                        'name'          => esc_html__('Featured Color', 'sonaar-music'),
                        'class'         => 'color',
                        'default'       => 'rgba(116, 221, 199, 1)',
                        'options'       => array(
                            'alpha'         => true, // Make this a rgba color picker.
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'id'            => 'sticky_player_labelsandbuttons',
                        'type'          => 'colorpicker',
                        'name'          => esc_html__('Labels and Buttons', 'sonaar-music'),
                        'class'         => 'color',
                        'default'       => 'rgba(255, 255, 255, 1)',
                        'options'       => array(
                            'alpha'         => true, // Make this a rgba color picker.
                        ),
                    ) );     
                    $sticky_player_options->add_field( array(
                        'id'            => 'sticky_player_soundwave_progress_bars',
                        'type'          => 'colorpicker',
                        'name'          => esc_html__('Waveform/Timeline Progress Color', 'sonaar-music'),
                        'class'         => 'color',
                        'default'       => 'rgba(116, 221, 199, 1)',
                        'options'       => array(
                            'alpha'         => true, // Make this a rgba color picker.
                        ),
                    ) );       
                    $sticky_player_options->add_field( array(
                        'id'            => 'sticky_player_soundwave_bars',
                        'type'          => 'colorpicker',
                        'name'          => esc_html__('Waveform/Timeline Color', 'sonaar-music'),
                        'class'         => 'color',
                        'default'       => 'rgba(79, 79, 79, 1)',
                        'options'       => array(
                            'alpha'         => true, // Make this a rgba color picker.
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'id'            => 'mobile_progress_bars',
                        'type'          => 'colorpicker',
                        'name'          => esc_html__('Mobile Progress Bars', 'sonaar-music'),
                        'class'         => 'color',
                        'default'       => '',
                        'options'       => array(
                            'alpha'         => true, // Make this a rgba color picker.
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'id'            => 'sticky_player_background',
                        'type'          => 'colorpicker',
                        'name'          => esc_html__('Sticky Background Color', 'sonaar-music'),
                        'class'         => 'color',
                        'default'       => 'rgba(0, 0, 0, 1)',
                        'options'       => array(
                            'alpha'         => true, // Make this a rgba color picker.
                        ),
                    ) );
                    $sticky_player_options->add_field( array(
                        'name'          => esc_html__('Disable Adaptive Colors on the Sticky', 'sonaar-music'),
                        'id'            => 'sticky_player_disable_adaptive_colors',
                        'type'          => 'switch',
                        'default'       => 'false',
                    ) );
            }


              /**
                * Registers fifth options page, and set main item as parent.
                */
                $args = array(
                    'id'           => 'audiopreview_srmp3_cmb2_tab',
                    'menu_title'   => esc_html__( 'Audio Preview & Restrictions', 'sonaar-music' ),
                    'title'        => esc_html__( 'Audio Preview & Restrictions', 'sonaar-music' ),
                    'object_types' => array( 'options-page' ),
                    'option_key'   => 'srmp3_settings_audiopreview', // The option key and admin menu page slug. 'yourprefix_tertiary_options',
                    'parent_slug'  => 'edit.php?post_type=' . SR_PLAYLIST_CPT, // Make options page a submenu item of the themes menu. //'yourprefix_main_options',
                    'tab_group'    => 'yourprefix_main_options',
                    'tab_title'    => esc_html__( 'Audio Preview & Restrictions', 'sonaar-music' ),
                );

                // 'tab_group' property is supported in > 2.4.0.
                if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
                    $args['display_cb'] = 'yourprefix_options_display_with_tabs';
                }

                $audiopreview_options = new_cmb2_box( $args );
                array_push($options_name, $audiopreview_options);

                    $audiopreview_options->add_field( array(
                        'name'          => esc_html__('Audio Preview & Restriction', 'sonaar-music'),
                        'type'          => 'title',
                        'id'            => 'audiopreview_settings_title',
                        'classes_cb'    => 'audiopreview_controller_classes'
                    ) );
    
                    $audiopreview_options->add_field( array(
                        'name'          => esc_html__('Enable Audio Preview & Restrictions [BETA]', 'sonaar-music'),
                        'id'            => 'force_audio_preview',
                        'type'          => 'switch',
                        'default'       => '',
                        'after'      => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => __('What is Audio Preview?', 'sonaar-music'),
                            'text'      => __('
                            Audio Preview, also known as an <em>"audio snippet"</em> or <em>"sample"</em>, it is a brief segment from a longer audio recording. It provides listeners a glimpse of the full content without playing the entire track.<br><br>
                            Used predominantly in <strong>online music stores</strong> and <strong>audiobook platforms</strong>, these short clips, lasting from a few seconds to minutes, assist potential buyers in their purchasing decisions.<br><br>
                            They also serve as a protective measure against <strong>unauthorized downloads</strong> by only showcasing a segment. Some previews might even include voiceovers or watermarks like <strong>"sample"</strong> to deter misuse.<br><br>
                            When a user <strong>(with restricted role)</strong> visit your website, it\'s this audio snippet they hear or download.<br><br>
                            Remember: If you do not set audio preview, it\'s OK! In this case, the full track will be available.<br><br>
                            If you want to provide full track access for a specific playlist or track, you can enable & disable those settings per individual post.<br><br>
                            ---- IMPORTANT ----<br>
                            This feature use FFMpeg Library. You must have a VPS or Dedicated Server to run FFMpeg <a href="https://sonaar.io/docs/how-to-add-audio-preview-in-wordpress/" target="_blank">(Learn More)</a><br><br>
                            In addition, MP3 Audio Player Pro - Business Plan - is required <a href="https://sonaar.io/mp3-audio-player-pro/pricing/" target="_blank">(View Pricing)</a>
                            
                            ', 'sonaar-music'),
                            'image'     => '',
                            'pro'       => true,
                        ),
                    ) );
                    $audiopreview_options->add_field( array(
                        'name'          => esc_html__('Who get the previews? Guest (logged-out vistors) automatically get audio previews, but you can set additional user roles.', 'sonaar-music'),
                        'id'            => 'audiopreview_access_roles',
                        'type'          => 'multicheck',
                        'options'       => Sonaar_Music::get_user_roles(),
                        'attributes'    => array(
                            'data-conditional-id'    => 'force_audio_preview',
                            'data-conditional-value' => 'true',
                        ),
                        'default'       => ''
                    ) );
                    $audiopreview_options->add_field( array(
                        'id'            => 'audiopreview_ffmpeg_path',
                        'name'          => __( 'FFMpeg Custom Path (Optional)', 'sonaar-music' ),
                        'type'          => 'text_medium',
                        'default'       => '',
                        'attributes' => array(
                            'placeholder' => esc_html__('/usr/local/bin/ffmpeg', 'sonaar_music'),
                            'data-conditional-id'    => 'force_audio_preview',
                            'data-conditional-value' => 'true',
                        ),
                    ) );
                    
                    $ffmpeg_path_found = (get_option('srmp3_ffmpeg_path') !== '') ? ' ' . __('FFMpeg found in: ', 'sonaar-music') . ' <span style="color:green;">' . get_option('srmp3_ffmpeg_path') . ' </span>': ' FFMpeg not found';
                    $audiopreview_options->add_field( array(
                        'name'          => esc_html__('Generate Previews with FFMPEG', 'sonaar-music'),
                        'type'          => 'title',
                        'id'            => 'audiopreview_generate_settings_title',
                        'after'         => '<span style="font-size:11px;">' . $ffmpeg_path_found . '</span>',
                        'classes_cb'    => 'audiopreview_controller_classes',
                        'attributes' => array(
                            'data-conditional-id'    => 'force_audio_preview',
                            'data-conditional-value' => 'true',
                        ),
                    ) );
                    $audiopreview_options->add_field( array(
                        'name'          => __( 'Fade In Length', 'sonaar-music' ),
                        'classes'       => 'ffmpeg_field',
                        'id'            => 'fadein_duration',
                        'type'          => 'text_small',
                        'default'       => 3,
                        'after'           => esc_html(' seconds', 'sonaar_music'),
                        'attributes' => array(
                            'type' => 'number',
                            'pattern' => '\d*',
                            'data-conditional-id'    => 'force_audio_preview',
                            'data-conditional-value' => 'true',
                        ),
                    ) );
                    $audiopreview_options->add_field( array(
                        'name'          => __( 'Fade Out Length', 'sonaar-music' ),
                        'classes'       => 'ffmpeg_field',
                        'id'            => 'fadeout_duration',
                        'type'          => 'text_small',
                        'default'       => 3,
                        'after'           => esc_html(' seconds', 'sonaar_music'),
                        'attributes' => array(
                            'type' => 'number',
                            'pattern' => '\d*',
                            'data-conditional-id'    => 'force_audio_preview',
                            'data-conditional-value' => 'true',
                        ),
                    ) );
                    $audiopreview_options->add_field( array(
                        'id'            => 'audio_watermark',
                        'name'          => esc_html__('Audio Watermark', 'sonaar-music'),
                        'classes'       => 'ffmpeg_field',
                        'description'   => esc_html__('Watermark will be added every 10 seconds','sonaar-music'),
                        'type'          => 'file',
                        'query_args'    => array(
                            'type'          => 'audio',
                        ),
                        'options' => array(
                            'url' => false, // Hide the text input for the url
                        ),
                        'label_cb'      => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => esc_html__('Audio Watermark can be set here. Keep it short and make sure it does not contain silences, Watermarks will be looped every 10 seconds.', 'sonaar-music'),
                            'image'     => '',
                        ),
                        'attributes' => array(
                            'data-conditional-id'    => 'force_audio_preview',
                            'data-conditional-value' => 'true',
                        ),
                    ) );
                    $audiopreview_options->add_field( array(
                        'name'          => __( 'Loop Watermark every', 'sonaar-music' ),
                        'classes'       => 'ffmpeg_field',
                        'id'            => 'watermark_spacegap',
                        'type'          => 'text_small',
                        'default'       => 6,
                        'after'           => esc_html(' seconds', 'sonaar_music'),
                        'attributes' => array(
                            //'type' => 'number',
                           // 'pattern' => '\d*',
                            'data-conditional-id'    => 'force_audio_preview',
                            'data-conditional-value' => 'true',
                        ),
                    ) );
                    $audiopreview_options->add_field( array(
                        'id'            => 'ad_preroll',
                        'name'          => esc_html__('Pre-roll Ad (optional)', 'sonaar-music'),
                        'classes'       => 'ffmpeg_field',
                        'description'   => esc_html__('Recommended Format: MP3 file encoded at 320kbps with sample rate of 44.1kHz','sonaar-music'),
                        'type'          => 'file',
                        'query_args'    => array(
                            'type'          => 'audio',
                        ),
                        'options' => array(
                            'url' => false, // Hide the text input for the url
                        ),
                        'label_cb'      => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => esc_html__('Add a pre-roll audio advertising here. Keep it short and make sure it does not contain silences.', 'sonaar-music'),
                            'image'     => '',
                        ),
                        'attributes' => array(
                            'data-conditional-id'    => 'force_audio_preview',
                            'data-conditional-value' => 'true',
                        ),
                    ) );
                    $audiopreview_options->add_field( array(
                        'id'            => 'ad_postroll',
                        'name'          => esc_html__('Post-roll Ad (optional)', 'sonaar-music'),
                        'classes'       => 'ffmpeg_field',
                        'description'   => esc_html__('Recommended Format: MP3 file encoded at 320kbps with sample rate of 44.1kHz','sonaar-music'),
                        'type'          => 'file',
                        'query_args'    => array(
                            'type'          => 'audio',
                        ),
                        'options' => array(
                            'url' => false, // Hide the text input for the url
                        ),
                        'label_cb'      => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => esc_html__('Add a post-roll audio advertising here. Keep it short and make sure it does not contain silences.', 'sonaar-music'),
                            'image'     => '',
                        ),
                        'attributes' => array(
                            'data-conditional-id'    => 'force_audio_preview',
                            'data-conditional-value' => 'true',
                        ),
                    ) );
                    $audiopreview_options->add_field( array(
                        'id'            => 'trimstart',
                        'name'          => __( 'Trim Start at', 'sonaar-music' ),
                        'classes'       => 'ffmpeg_field',
                        'type'          => 'text_time',
                        'default'       => '00:00:00',
                        'time_format'   => 'H:i:s',
                        'attributes'    => array(
                            'data-timepicker'   => json_encode( array(
                                'timeOnlyTitle'     => __( 'Start at', 'sonaar-music' ),
                                'timeText'          => __( 'Start at', 'sonaar-music' ),
                                'timeFormat'        => 'HH:mm:ss',
                                'stepMinute'        => 1, // 1 minute increments instead of the default 5
                            ) ),
                            'data-conditional-id'    => 'force_audio_preview',
                            'data-conditional-value' => 'true',
                        ),
                        'label_cb'      => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => esc_html__('Set the time point (in seconds) from which your preview starts playing. Warning: If your full track duration is less than this number, its preview wont be generated.', 'sonaar-music'),
                            'image'     => '',
                        ),
                    ) );
                    $audiopreview_options->add_field( array(
                        'id'            => 'audiopreview_duration',
                        'name'          => __( 'Preview Length', 'sonaar-music' ),
                        'classes'       => 'ffmpeg_field',
                        'type'          => 'text_time',
                        'default'       => '00:00:30',
                        'time_format'   => 'H:i:s',
                        'attributes'    => array(
                            'data-timepicker'   => json_encode( array(
                                'timeOnlyTitle'     => __( 'Preview Length', 'sonaar-music' ),
                                'timeText'          => __( 'Length', 'sonaar-music' ),
                                'timeFormat'        => 'HH:mm:ss',
                                'stepMinute'        => 1, // 1 minute increments instead of the default 5
                            ) ),
                            'data-conditional-id'    => 'force_audio_preview',
                            'data-conditional-value' => 'true',
                        ),
                        'label_cb'      => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => esc_html__('This represents the trim cut duration of your track. For full track length, use infinite number (eg: 99999).', 'sonaar-music'),
                            'image'     => '',
                        ),
                    ) );
                    $audiopreview_options->add_field( array(
                        'id'            => 'preview_overwrite',
                        'name'          => esc_html__('Overwrite existing files?', 'sonaar-music'),
                        'classes'       => 'ffmpeg_field',
                        'type'          => 'switch',
                        'return'        => 'true',
                        'default'       => 'false',
                        'attributes' => array(
                            'data-conditional-id'    => 'force_audio_preview',
                            'data-conditional-value' => 'true',
                        ),
                    ) );
                    $audiopreview_options->add_field( array(
                        'id'            => 'preview_folder_name',
                        'name'          => __( 'Folder Output', 'sonaar-music' ),
                        'classes'       => 'ffmpeg_field',
                        'type'          => 'text_medium',
                        'default'       => 'audio_preview',
                        'before'        => esc_html('/wp-content/uploads/', 'sonaar_music'),
                        'after'        => esc_html('/', 'sonaar_music'),
                        'attributes' => array(
                            'data-conditional-id'    => 'force_audio_preview',
                            'data-conditional-value' => 'true',
                        ),
                    ) );
                    $audiopreview_options->add_field( array(
                        'name'          => esc_html__('Buffer Size Limit', 'sonaar-music'),
                        'id'            => 'preview_batch_size',
                        'classes'       => 'ffmpeg_field',
                        'type'          => 'select',
                        'options'       => array(
                            '1'   => esc_html__('1', 'sonaar-music'),
                            '5'    => esc_html__('5', 'sonaar-music'),
                            '10'    => esc_html__('10', 'sonaar-music'),
                            '50'     => esc_html__('50', 'sonaar-music'),
                        ),
                        'default'       => '1',
                        'attributes' => array(
                            'data-conditional-id'    => 'force_audio_preview',
                            'data-conditional-value' => 'true',
                        ),
                        'label_cb'      => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('Set the number of posts to process in a single batch','sonaar-music'),
                            'text'      => esc_html__('A higher number speeds up generation but may be limited by your server\'s maximum execution time. For example, "1" processes one post per request, while "50" processes fifty posts at once.', 'sonaar-music'),
                            'image'     => '',
                        ),
                    ) );
                    $audiopreview_options->add_field( array(
                        'id'            => 'srmp3-settings-generate-bt-container',
                        'classes'       => 'ffmpeg_field srmp3-settings-generate-bt-container',
                        'description'   => __('
                        <div class="srmp3-bulk-wrapper">
                            <div>
                                <button id="srmp3_index_audio_preview" class="srmp3-generate-bt srmp3-audiopreview-bt showSpinner">Generate Files</button>
                                <span id="srmp3_indexTracks_status"></span>
                                <progress id="indexationProgress" style="width:100%;margin-top:10px;display:none;" value="0" max="100"></progress>
                                <span id="progressText"></span>
                            </div>
                            <button id="srmp3-bulkRemove-bt" class="srmp3-generate-bt"><span class="dashicons dashicons-trash"></span>Remove All Preview Files</button>
                        </div>
                        ','sonaar-music'),
                        'type'          => 'text_small',
                        'attributes' => array(
                            'data-conditional-id'    => 'force_audio_preview',
                            'data-conditional-value' => 'true',
                        ),
                    ) );
                    
                //}





                if ( defined( 'WC_VERSION' )) {
                    /**
                     * Registers tertiary options page, and set main item as parent.
                     */
                    $args = array(
                        'id'           => 'yourprefix_tertiary_options_page',
                        'menu_title'   => esc_html__( 'WooCommerce Settings', 'sonaar-music' ),
                        'title'        => esc_html__( 'WooCommerce Settings', 'sonaar-music' ),
                        'object_types' => array( 'options-page' ),
                        'option_key'   => 'srmp3_settings_woocommerce', // The option key and admin menu page slug. 'yourprefix_tertiary_options',
                        'parent_slug'  => 'edit.php?post_type=' . SR_PLAYLIST_CPT, // Make options page a submenu item of the themes menu. //'yourprefix_main_options',
                        'tab_group'    => 'yourprefix_main_options',
                        'tab_title'    => esc_html__( 'WooCommerce', 'sonaar-music' ),
                    );

                    // 'tab_group' property is supported in > 2.4.0.
                    if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
                        $args['display_cb'] = 'yourprefix_options_display_with_tabs';
                    }

                    $woocommerce_options = new_cmb2_box( $args );
                    array_push($options_name, $woocommerce_options);
                    if ( !function_exists( 'run_sonaar_music_pro' ) || get_site_option('SRMP3_ecommerce') != '1'){
                        $woocommerce_options->add_field( array(
                            'classes'       => 'srmp3-pro-feature',
                            'name'          => esc_html__('WooCommerce Setting', 'sonaar-music'),
                            'type'          => 'title',
                            'id'            => 'promo_music_player_sticky_title',
                            'after'         => 'promo_ad_cb',
                            'options' => array(
                                'textpromo' => esc_html__('BUSINESS PLAN REQUIRED | UPGRADE HERE', 'sonaar-music'),
                                'plan_required'      => 'business',
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'classes'       => 'srmp3-pro-feature',
                            'name'          => esc_html__('Enable Player in WooCommerce Grid', 'sonaar-music'),
                            'type'          => 'switch',
                            'default'       => 'false',
                            'id'            => 'promo_sr_woo_shop_setting_heading',
                            'after'         => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'title'     => esc_html__('WC Shop Loop', 'sonaar-music'),
                                'text'      => __('When you display your WooCommerce products in a grid, shop page or archive, you may want to display audio players on each instance.<br><br>You can choose to display audio controls over or below the thumbnail.', 'sonaar-music'),
                                'image'     => 'wc_shoploop.svg',
                                'pro'       => true,
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'classes'       => 'srmp3-pro-feature',
                            'name'          => esc_html__('Enable Player in WooCommerce Page', 'sonaar-music'),
                            'type'          => 'switch',
                            'default'       => 'false',
                            'id'            => 'promo_sr_enable_player_wc_page',
                            'after'         => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'title'     => esc_html__('Enable Player in WC Single Page', 'sonaar-music'),
                                'text'      => sprintf(__('For each single product page, we automatically display the audio player if you have set tracks using our custom fields.<br><br>The player is shown within the product\'s detail page.
                                You can either use the settings below to setup the player layout, or use our shortcode with any of our supported attributes for more flexibility.<br><br>
                                View shortcode & supported attributes %1$sdocumentation%2$s.', 'sonaar-music'),'<a href="https://sonaar.io/go/mp3player-shortcode-attributes" target="_blank">', '</a>' ),
                                'image'     => '',
                                'pro'       => true,
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'classes'       => 'srmp3-pro-feature',
                            'name'          => esc_html__('Enable Music Licenses & Contracts Post Type', 'sonaar-music'),
                            'id'            => 'promo_wc_enable_licenses_cpt',
                            'type'          => 'switch',
                            'default'       => 'false',
                            'after'         => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'title'     => esc_html__('', 'sonaar-music'),
                                'text'      => esc_html__('This will enable the Music Licenses & Contracts custom post type used to sell music license with product variation in WooCommerce', 'sonaar-music'),
                                'pro'       => true,
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'classes'       => 'srmp3-pro-feature',
                            'name'          => esc_html__('WooCommerce CTA Buttons in tracklist', 'sonaar-music'),
                            'id'            => 'promo_wc_bt_type',
                            'type'          => 'select',
                            'options'       => array(
                                'wc_bt_type_label_price'    => 'Label + Price',
                                'wc_bt_type_label'          => 'Label Only',
                                'wc_bt_type_price'          => 'Price Only',
                                'wc_bt_type_inactive'       => 'Inactive',
                            ),
                            'default'       => 'wc_bt_type_price',
                            'after'         => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'title'     => esc_html__('Call-to-Action Buttons', 'sonaar-music'),
                                'text'      => __('When tracks are added through a WooCommerce product post, we automatically display Buy Now / Add to Cart call-to-action buttons beside each track.<br><br>
                                Here you can set what to display in the call-to-action buttons.<br><br>
                                Example:<br><br>
                                <strong>Label + Price</strong> = [ Buy Now $9.99 ]<br>
                                <strong>Label Only</strong> = [ Buy Now ]<br>
                                <strong>Price Only</strong> = [ $9.99 ]<br>
                                <strong>Inactive</strong> = No button will be displayed<br><br>
                                You can disable call-to-action buttons for specific products by editing the product post.<br><br>
                                You can change or translate the label strings below.', 'sonaar-music'),
                                'image'     => 'woocommerce_cta.svg',
                                'pro'       => true,
                            ),
                        ) );
                        
                    }
                    if ( function_exists( 'run_sonaar_music_pro' ) && get_site_option('SRMP3_ecommerce') == '1'){
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('WooCommerce CTA Buttons in tracklist', 'sonaar-music'),
                            'id'            => 'wc_bt_type',
                            'type'          => 'select',
                            'options'       => array(
                                'wc_bt_type_label_price'    => 'Label + Price',
                                'wc_bt_type_label'          => 'Label Only',
                                'wc_bt_type_price'          => 'Price Only',
                                'wc_bt_type_inactive'       => 'Inactive',
                            ),
                            'default'       => 'wc_bt_type_price',
                            'after'         => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'title'     => esc_html__('Call-to-Action Buttons', 'sonaar-music'),
                                'text'      => __('When tracks are added through a WooCommerce product post, we automatically display Buy Now / Add to Cart call-to-action buttons beside each track.<br><br>
                                Here you can set what to display in the call-to-action buttons.<br><br>
                                Example:<br><br>
                                <strong>Label + Price</strong> = [ Buy Now $9.99 ]<br>
                                <strong>Label Only</strong> = [ Buy Now ]<br>
                                <strong>Price Only</strong> = [ $9.99 ]<br>
                                <strong>Inactive</strong> = No button will be displayed<br><br>
                                You can disable call-to-action buttons for specific products by editing the product post.<br><br>
                                You can change or translate the label strings below.', 'sonaar-music'),
                                'image'     => 'woocommerce_cta.svg',
                                'pro'       => true,
                            ),
                        ) ); 
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Add to Cart Label', 'sonaar-music'),
                            'classes'       => 'srmp3-settings--subitem',
                            'id'            => 'wc_add_to_cart_text',
                            'type'          => 'text_medium',
                            'default'       => esc_html__('', 'sonaar-music'),
                            'attributes'  => array(
                                'placeholder' => 'Add to Cart',
                                'data-conditional-id'    => 'wc_bt_type',
                                'data-conditional-value' => wp_json_encode( array( 'wc_bt_type_label_price', 'wc_bt_type_label' ) ),
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Buy Now Label', 'sonaar-music'),
                            'classes'       => 'srmp3-settings--subitem',
                            'id'            => 'wc_buynow_text',
                            'type'          => 'text_medium',
                            'default'       => esc_html__('', 'sonaar-music'),
                            'attributes'  => array(
                                'placeholder' => 'Buy Now',
                                'data-conditional-id'    => 'wc_bt_type',
                                'data-conditional-value' => wp_json_encode( array( 'wc_bt_type_label_price', 'wc_bt_type_label' ) ),
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Show icon on WooCommerce buttons', 'sonaar-music'),
                            'classes'       => 'srmp3-settings--subitem',
                            'id'            => 'wc_bt_show_icon',
                            'type'          => 'switch',
                            'default'       => 'true',
                            'attributes'  => array(
                                'data-conditional-id'    => 'wc_bt_type',
                                'data-conditional-value' => wp_json_encode( array( 'wc_bt_type_label_price', 'wc_bt_type_label', 'wc_bt_type_price' ) ),
                            ),
                            'after'         => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'title'     => esc_html__('', 'sonaar-music'),
                                'text'      => esc_html__('Show a small cart icon beside the button text label', 'sonaar-music'),
                                'image'     => 'wc_noicon.svg',
                                'pro'       => true,
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Enable AJAX Add-to-Cart', 'sonaar-music'),
                            'id'            => 'wc_enable_ajax_addtocart',
                            'type'          => 'switch',
                            'default'       => 'true',
                            'after'         => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'text'      => esc_html__('When people add product to the cart, page will not refresh.', 'sonaar-music'),
                                'pro'       => true,
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Enable lightbox for variable products', 'sonaar-music'),
                            'id'            => 'wc_variation_lb',
                            'type'          => 'switch',
                            'default'       => 'true',
                            'after'         => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'title'     => esc_html__('', 'sonaar-music'),
                                'text'      => esc_html__('When users add a product that contains product variations to their cart, a lightbox will be displayed where they can choose which product variation/usage license to buy. If disabled, users will be redirected to the single product page in order to choose the variation.', 'sonaar-music'),
                                //'image'     => 'wc_noicon.svg',
                                'pro'       => true,
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Add Custom Link in the lightbox', 'sonaar-music'),
                            'classes'       => 'srmp3-settings--subitem',
                            'id'            => 'wc_enable_custom_link_in_modal',
                            'type'          => 'switch',
                            'after'         => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'title'     => '',
                                'text'      => esc_html__('A custom link will be added beside the Add to Cart button in the lightbox panel. This can be useful for directing users to a license comparison page or to the individual product page for more details.', 'sonaar-music'),
                                'pro'       => true,
                            ),
                            'attributes'  => array(
                                'data-conditional-id'    => 'wc_variation_lb',
                                'data-conditional-value' => 'true',
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__( 'Icon', 'sonaar-music' ),
                            'classes'       => 'srmp3-settings--subitem srmp3-settings--subitem2',
                            'id'            => 'wc_enable_custom_link_icon',
                            'type'          => 'faiconselect',
                            'options_cb'    => 'srmp3_returnRayFaPre',
                            'attributes'    => array(
                                'data-conditional-id'    => 'wc_enable_custom_link_in_modal',
                                'data-conditional-value' => 'true',
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Button Label', 'sonaar-music'),
                            'classes'       => 'srmp3-settings--subitem srmp3-settings--subitem2',
                            'id'            => 'wc_enable_custom_link_label',
                            'type'          => 'text_medium',
                            'default'       => esc_html__('Learn More','sonaar-music'),
                            'label_cb'      => 'srmp3_add_tooltip_to_label',
                            'attributes'    => array(
                                'placeholder'            => esc_html__('Learn More','sonaar-music'),
                                'data-conditional-id'    => 'wc_enable_custom_link_in_modal',
                                'data-conditional-value' => 'true',
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Link URL', 'sonaar-music'),
                            'classes'       => 'srmp3-settings--subitem srmp3-settings--subitem2',
                            'id'            => 'wc_enable_custom_link_is_custom',
                            'type'          => 'text_medium',
                            'label_cb'      => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'title'     => '',
                                'text'      => __('The URL to redirect when user click on the button. If you are using Elementor Popup, set this to #popup and in the Advanced Tab of your Popup > Open By Selector create an anchor trigger link shortcode (example: a[href="#popup"] )', 'sonaar-music'),
                                'image'     => '',
                            ),
                            'attributes'    => array(
                                'placeholder'            => 'https://yourdomain.com/licenses',
                                'data-conditional-id'    => 'wc_enable_custom_link_in_modal',
                                'data-conditional-value' => 'true',
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Target', 'sonaar-music'),
                            'classes'       => 'srmp3-settings--subitem srmp3-settings--subitem2',
                            'id'            => 'wc_enable_custom_link_target',
                            'type'          => 'select',
                            'options'       => array(
                                '_self'     => '_self',
                                '_blank'    => '_blank',
                            ),
                            'default'       => '_self',
                            'after'         => 'srmp3_add_tooltip_to_label',
                            'attributes'    => array(
                                'data-conditional-id'    => 'wc_enable_custom_link_in_modal',
                                'data-conditional-value' => 'true',
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Or Link to the product page', 'sonaar-music'),
                            'classes'       => 'srmp3-settings--subitem srmp3-settings--subitem2',
                            'id'            => 'wc_enable_custom_link_is_product',
                            'type'          => 'switch',
                            'after'         => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'title'     => '',
                                'text'      => esc_html__('The custom link will be the product page.', 'sonaar-music'),
                                'pro'       => true,
                            ),
                            'attributes'  => array(
                                'data-conditional-id'    => 'wc_enable_custom_link_in_modal',
                                'data-conditional-value' => 'true',
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Enable Music Licenses & Contracts CPT', 'sonaar-music'),
                            'id'            => 'wc_enable_licenses_cpt',
                            'type'          => 'switch',
                            'default'       => 'true',
                            'after'         => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'title'     => esc_html__('', 'sonaar-music'),
                                'text'      => esc_html__('This will enable the Music Licenses & Contracts custom post type used to sell music license with product variation in WooCommerce', 'sonaar-music'),
                                'pro'       => true,
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('WooCommerce Shop Loop/Archive Page', 'sonaar-music'),
                            'type'          => 'title',
                            'id'            => 'sr_woo_shop_setting_heading',
                            'after'         => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'title'     => esc_html__('WC Shop Loop', 'sonaar-music'),
                                'text'      => __('When you display your WooCommerce products in a grid, shop page or archive, you may want to display audio players on each instance.<br><br>You can choose to display audio controls over or below the thumbnail.', 'sonaar-music'),
                                'image'     => 'wc_shoploop.svg',
                                'pro'       => true,
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Player Position', 'sonaar-music'),
                            'id'            => 'sr_woo_shop_position',
                            'type'          => 'select',
                            'options'       => array(
                                'disable'   => esc_html__('Inactive', 'sonaar-music'),
                                'over_image'    => esc_html__('Over the image', 'sonaar-music'),
                                'before'    => esc_html__('Before the title', 'sonaar-music'),
                                'after'     => esc_html__('After the title', 'sonaar-music'),
                                'after_item'     => esc_html__('After the block item', 'sonaar-music'),
                            ),
                            'default'       => 'disable'
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Show Control On Hover', 'sonaar-music'),                
                            'id'            => 'sr_woo_button_hover',
                            'type'          => 'switch',
                            'default'       => 1,
                            'attributes'    => array(
                                'data-conditional-id'    => 'sr_woo_shop_position',
                                'data-conditional-value' => wp_json_encode( array( 'over_image' ) ),
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Design Preset', 'sonaar-music'),
                            'classes'       => 'srmp3-settings--subitem',
                            'id'            => 'sr_woo_skin_shop',
                            'type'          => 'select',
                            'options'       => array(
                            // 'over_image'            => esc_html__('Player Over Image', 'sonaar-music'),
                                'preset'                => esc_html__('Use Settings Below', 'sonaar-music'),
                                'custom_shortcode'      => esc_html__('Custom Shortcode', 'sonaar-music'),
                            ),
                            'attributes'    => array(
                                'data-conditional-id'    => 'sr_woo_shop_position',
                                'data-conditional-value' => wp_json_encode( array( 'before', 'after', 'after_item' ) ),
                            ),
                            //'default'       => 'over_image'
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Player Shortcode', 'sonaar-music'),
                            'classes'       => 'srmp3-settings--subitem',
                            'type'          => 'textarea_small',
                            'id'            => 'sr_woo_shop_player_shortcode',
                            'description'          => sprintf( wp_kses( __('For shortcode attributes, %1$s read this article%2$s.','sonaar-music'), $escapedVar), '<a href="https://sonaar.io/go/mp3player-shortcode-attributes" target="_blank">', '</a>'),
                            'default'       => '[sonaar_audioplayer sticky_player="true" hide_artwork="true" show_playlist="false" show_track_market="false" show_album_market="false" hide_progressbar="false" hide_times="true" hide_track_title="true"]',
                            'attributes'    => array(
                                'data-conditional-id'    => 'sr_woo_skin_shop',
                                'data-conditional-value' => wp_json_encode( array( 'custom_shortcode' ) ),
                            ),
        
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Remove WooCommerce Featured Image', 'sonaar-music'),  
                            'classes'       => 'srmp3-settings--subitem',              
                            'id'            => 'remove_wc_featured_image',
                            'type'          => 'switch',
                            'default'       => 0,
                            
                            'attributes'    => array(
                                'data-conditional-id'    => 'sr_woo_shop_position',
                                'data-conditional-value' => wp_json_encode( array( 'before', 'after', 'after_item' ) ),
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Display Sticky Player on Play', 'sonaar-music'),                
                            'id'            => 'sr_woo_skin_shop_attr_sticky_player',
                            'type'          => 'switch',
                            'default'       => 1,
                            'attributes'    => array(
                                'data-conditional-id'    => 'sr_woo_shop_position',
                                'data-conditional-value' => wp_json_encode( array( 'over_image', 'before', 'after', 'after_item' ) ),
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Display Tracklist', 'sonaar-music'),                
                            'id'            => 'sr_woo_skin_shop_attr_tracklist',
                            'type'          => 'switch',
                            'default'       => 0,
                            'attributes'    => array(
                                'data-conditional-id'    => 'sr_woo_shop_position',
                                'data-conditional-value' => wp_json_encode( array( 'over_image', 'before', 'after', 'after_item' ) ),
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Display Progress Bar', 'sonaar-music'),                
                            'id'            => 'sr_woo_skin_shop_attr_progressbar',
                            'type'          => 'switch',
                            'default'       => 0,
                            'attributes'    => array(
                                'data-conditional-id'    => 'sr_woo_shop_position',
                                'data-conditional-value' => wp_json_encode( array( 'over_image', 'before', 'after', 'after_item' ) ),
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Progress Bar Inline', 'sonaar-music'),                
                            'id'            => 'sr_woo_skin_shop_attr_progress_inline',
                            'type'          => 'switch',
                            'default'       => 1,
                            'attributes'    => array(
                                'data-conditional-id'    => 'sr_woo_shop_position',
                                'data-conditional-value' => wp_json_encode( array( 'over_image', 'before', 'after', 'after_item' ) ),
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('WooCommerce PRODUCT Page', 'sonaar-music'),
                            'type'          => 'title',
                            'id'            => 'sr_woo_product_setting_heading',
                            'after'         => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'title'     => esc_html__('', 'sonaar-music'),
                                'text'      => sprintf(__('For each single product page, we automatically display the audio player if you have set tracks using our custom fields.<br><br>The player is shown within the product\'s detail page.
                                You can either use the settings below to setup the player layout, or use our shortcode with any of our supported attributes for more flexibility.<br><br>
                                View shortcode & supported attributes %1$sdocumentation%2$s.', 'sonaar-music'),'<a href="https://sonaar.io/go/mp3player-shortcode-attributes" target="_blank">', '</a>' ),
                                'image'     => '',
                                'pro'       => true,
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Player Position', 'sonaar-music'),
                            'id'            => 'sr_woo_product_position',
                            'type'          => 'select',
                            'options'       => array(
                                'disable'   => esc_html__('Inactive', 'sonaar-music'),
                                'before'    => esc_html__('Before the title', 'sonaar-music'),
                                'after'     => esc_html__('After the title', 'sonaar-music'),
                                'before_rating'     => esc_html__('Before the rating', 'sonaar-music'),
                                'after_price'     => esc_html__('After the price', 'sonaar-music'),
                                'after_add_to_cart'     => esc_html__('After Add to Cart', 'sonaar-music'),
                                'before_excerpt'     => esc_html__('Before short description', 'sonaar-music'),
                                'after_excerpt'     => esc_html__('After short description', 'sonaar-music'),
                                'before_meta'     => esc_html__('Before metadata', 'sonaar-music'),
                                'after_meta'     => esc_html__('After metadata', 'sonaar-music'),
                                'after_summary'     => esc_html__('After the summary', 'sonaar-music'),
                            ),
                            'default'       => 'disable'
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Design Preset', 'sonaar-music'),
                            'id'            => 'sr_woo_skin_product',
                            'type'          => 'select',
                            'options'       => array(
                            // 'over_image'            => esc_html__('Player Over Image', 'sonaar-music'),
                                'preset'                => esc_html__('Use Settings Below', 'sonaar-music'),
                                'custom_shortcode'      => esc_html__('Custom Shortcode', 'sonaar-music'),
                            ),
                            'attributes'    => array(
                                'data-conditional-id'    => 'sr_woo_product_position',
                                'data-conditional-value' => wp_json_encode( array( 'before', 'after', 'before_rating','after_price', 'after_add_to_cart', 'before_excerpt', 'after_excerpt', 'before_meta', 'after_meta', 'after_summary' ) ),
                            ),
                            //'default'       => 'over_image'
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('WooCommerce Product Player Shortcode', 'sonaar-music'),
                            'classes'       => 'srmp3-settings--subitem',
                            'type'          => 'textarea_small',
                            'id'            => 'sr_woo_product_player_shortcode',
                            'desc'          => sprintf( wp_kses( __('For shortcode attributes, %1$s read this article%2$s.','sonaar-music'), $escapedVar), '<a href="https://sonaar.io/go/mp3player-shortcode-attributes" target="_blank">', '</a>'),
                            'default'       => '[sonaar_audioplayer sticky_player="true" hide_artwork="true" show_playlist="false" show_track_market="false" show_album_market="false" hide_progressbar="false" hide_times="true" hide_track_title="true"]',
                            'attributes'    => array(
                                'data-conditional-id'    => 'sr_woo_skin_product',
                                'data-conditional-value' => wp_json_encode( array( 'custom_shortcode' ) ),
                            ),
        
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Display Sticky Player on Play', 'sonaar-music'),                
                            'id'            => 'sr_woo_skin_product_attr_sticky_player',
                            'type'          => 'switch',
                            'default'       => 1,
                            'attributes'    => array(
                                'data-conditional-id'    => 'sr_woo_product_position',
                                'data-conditional-value' => wp_json_encode( array( 'before', 'after', 'before_rating','after_price', 'after_add_to_cart', 'before_excerpt', 'after_excerpt', 'before_meta', 'after_meta', 'after_summary' ) ),
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Display Tracklist', 'sonaar-music'),                
                            'id'            => 'sr_woo_skin_product_attr_tracklist',
                            'type'          => 'switch',
                            'default'       => 0,
                            'attributes'    => array(
                                'data-conditional-id'    => 'sr_woo_product_position',
                                'data-conditional-value' => wp_json_encode( array( 'before', 'after', 'before_rating','after_price', 'after_add_to_cart', 'before_excerpt', 'after_excerpt', 'before_meta', 'after_meta', 'after_summary' ) ),
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Tracklist Title', 'sonaar-music'),                
                            'id'            => 'sr_woo_skin_product_attr_albumtitle',
                            'type'          => 'switch',
                            'default'       => 0,
                            'attributes'    => array(
                                'data-conditional-id'    => 'sr_woo_product_position',
                                'data-conditional-value' => wp_json_encode( array( 'before', 'after', 'before_rating','after_price', 'after_add_to_cart', 'before_excerpt', 'after_excerpt', 'before_meta', 'after_meta', 'after_summary' ) ),
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Tracklist Subtitle', 'sonaar-music'),                
                            'id'            => 'sr_woo_skin_product_attr_albumsubtitle',
                            'type'          => 'switch',
                            'default'       => 0,
                            'attributes'    => array(
                                'data-conditional-id'    => 'sr_woo_product_position',
                                'data-conditional-value' => wp_json_encode( array( 'before', 'after', 'before_rating','after_price', 'after_add_to_cart', 'before_excerpt', 'after_excerpt', 'before_meta', 'after_meta', 'after_summary' ) ),
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Display Progress Bar', 'sonaar-music'),                
                            'id'            => 'sr_woo_skin_product_attr_progressbar',
                            'type'          => 'switch',
                            'default'       => 0,
                            'attributes'    => array(
                                'data-conditional-id'    => 'sr_woo_product_position',
                                'data-conditional-value' => wp_json_encode( array( 'before', 'after', 'before_rating','after_price', 'after_add_to_cart', 'before_excerpt', 'after_excerpt', 'before_meta', 'after_meta', 'after_summary' ) ),
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Progress Bar Inline', 'sonaar-music'),                
                            'id'            => 'sr_woo_skin_product_attr_progress_inline',
                            'type'          => 'switch',
                            'default'       => 1,
                            'attributes'    => array(
                                'data-conditional-id'    => 'sr_woo_product_position',
                                'data-conditional-value' => wp_json_encode( array( 'before', 'after', 'before_rating','after_price', 'after_add_to_cart', 'before_excerpt', 'after_excerpt', 'before_meta', 'after_meta', 'after_summary' ) ),
                            ),
                        ) );
                        $woocommerce_options->add_field( array(
                            'name'          => esc_html__('Display Control Buttons', 'sonaar-music'),                
                            'id'            => 'sr_woo_skin_product_attr_control',
                            'type'          => 'switch',
                            'default'       => 0,
                            'attributes'    => array(
                                'data-conditional-id'    => 'sr_woo_product_position',
                                'data-conditional-value' => wp_json_encode( array( 'before', 'after', 'before_rating','after_price', 'after_add_to_cart', 'before_excerpt', 'after_excerpt', 'before_meta', 'after_meta', 'after_summary' ) ),
                            ),
                        ) );
                }
            }

           


  /**
                * Registers fifth options page, and set main item as parent.
                */
                $args = array(
                    'id'           => 'favorites_srmp3_cmb2_tab',
                    'menu_title'   => esc_html__( 'Favorites', 'sonaar-music' ),
                    'title'        => esc_html__( 'Favorites', 'sonaar-music' ),
                    'object_types' => array( 'options-page' ),
                    'option_key'   => 'srmp3_settings_favorites', // The option key and admin menu page slug. 'yourprefix_tertiary_options',
                    'parent_slug'  => 'edit.php?post_type=' . SR_PLAYLIST_CPT, // Make options page a submenu item of the themes menu. //'yourprefix_main_options',
                    'tab_group'    => 'yourprefix_main_options',
                    'tab_title'    => esc_html__( 'Add to Favorites', 'sonaar-music' ),
                );

                // 'tab_group' property is supported in > 2.4.0.
                if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
                    $args['display_cb'] = 'yourprefix_options_display_with_tabs';
                }

                $favorites_options = new_cmb2_box( $args );
                array_push($options_name, $favorites_options);
                if ( !function_exists( 'run_sonaar_music_pro' ) || get_site_option('SRMP3_ecommerce') != '1'){
                    $favorites_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Favorites - Settings', 'sonaar-music'),
                        'type'          => 'title',
                        'id'            => 'promo_music_player_favorites_title',
                        'after'         => 'promo_ad_cb',
                        'options' => array(
                            'textpromo' => esc_html__('BUSINESS PLAN REQUIRED | UPGRADE HERE', 'sonaar-music'),
                            'plan_required'      => 'business',
                        ),
                    ) );
                    $favorites_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Enable Favorites Options', 'sonaar-music'),
                        'id'            => 'promo_enable_favorites',
                        'type'          => 'switch',
                        'default'       => 0,
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => '',
                            'image'     => '',
                            'pro'       => true,
                        ),
                    ) );
                }
                if(function_exists( 'run_sonaar_music_pro' ) &&  get_site_option('SRMP3_ecommerce') == '1'){
                    $favorites_options->add_field( array(
                        'name'          => esc_html__('Add to Favorites', 'sonaar-music'),
                        'type'          => 'title',
                        'id'            => 'general_favorites_heading'
                    ) );
                    $favorites_options->add_field( array(
                        'name'          => esc_html__('Add to Favorite Button on each track', 'sonaar-music'),
                        'id'            => 'force_cta_favorite',
                        'type'          => 'switch',
                        'label_cb'      => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => __('This will add a "Favorite" icon to each tracks on all your player instances. Adding favorite icons will enable users to conveniently mark tracks they love.<br><br>This option can also be enabled/disabled independantly on each player instances.<br><br>For users to easily access and listen to all their marked favorites, you would need to manually set up a player on your page, and designate the favorite tracks as the source for this player.<br><br>This way, all favorited songs can be found and enjoyed in one centralized player.', 'sonaar-music'),
                            'image'     => '',
                        ),
                    ) );
                    $favorites_options->add_field( array(
                        'name'          => esc_html__( 'Add to Favorites Icon', 'sonaar-music' ),
                        'classes'       => 'srp_fav_add_icon',
                        'id'            => 'srp_fav_add_icon',
                        'default'       => 'sricon-heart-fill',
                        'type'          => 'faiconselect',
                        'options_cb'    => 'srmp3_srIconOnly'
                    ) );
                    $favorites_options->add_field( array(
                        'name'          =>  esc_html__( 'Add to Favorite Button Label', 'sonaar-music' ),
                        'id'            => 'fav_label_add_action',
                        'type'          => 'text',
                        'default'       => '',
                        'attributes'    => array(
                            'placeholder' => esc_html__( 'Eg: Add', 'sonaar-music' ),
                        ),
                    ) );
                    $favorites_options->add_field( array(
                        'name'          =>  esc_html__( 'Add: Success Message', 'sonaar-music' ),
                        'id'            => 'fav_label_add',
                        'type'          => 'text',
                        'default'       => esc_html__( 'Added to your Favorite', 'sonaar-music' ),
                        'attributes'    => array(
                            'placeholder' => esc_html__( 'Added to your Favorite', 'sonaar-music' ),
                        ),
                    ) );
                    $favorites_options->add_field( array(
                        'name'          => esc_html__( 'Remove from Favorites Icon', 'sonaar-music' ),
                        'classes'       => 'srp_fav_remove_icon',
                        'id'            => 'srp_fav_remove_icon',
                        'default'       => 'sricon-heart',
                        'type'          => 'faiconselect',
                        'options_cb'    => 'srmp3_srIconOnly'
                    ) );
                    $favorites_options->add_field( array(
                        'name'          =>  esc_html__( 'Remove from Favorite Button Label', 'sonaar-music' ),
                        'id'            => 'fav_label_remove_action',
                        'type'          => 'text',
                        'default'       => '',
                        'attributes'    => array(
                            'placeholder' => esc_html__( 'Eg: Remove', 'sonaar-music' ),
                        ),
                    ) );
                    $favorites_options->add_field( array(
                        'name'          =>  esc_html__( 'Remove: Success Message', 'sonaar-music' ),
                        'id'            => 'fav_label_remove',
                        'type'          => 'text',
                        'default'       => esc_html__( 'Removed from your Favorite', 'sonaar-music' ),
                        'attributes'    => array(
                            'placeholder' => esc_html__( 'Removed from your Favorite', 'sonaar-music' ),
                        ),
                    ) );
                    $favorites_options->add_field( array(
                        'name'          =>  esc_html__( 'No favorite track Label', 'sonaar-music' ),
                        'id'            => 'fav_label_notfound',
                        'type'          => 'text',
                        'default'       => esc_html__( 'You haven\'t liked any tracks yet.', 'sonaar-music' ),
                        'attributes'    => array(
                            'placeholder' => esc_html__( 'You haven\'t liked any tracks yet.', 'sonaar-music' ),
                        ),
                    ) );
                    $favorites_options->add_field( array(
                        'name'          => esc_html__('Display Button to Remove All Favorites at once', 'sonaar-music'),
                        'id'            => 'fav_removeall_bt',
                        'type'          => 'switch',
                        'label_cb'      => 'srmp3_add_tooltip_to_label',
                        'default'       => 'true',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => esc_html__('We will display a Remove All button in the favorite player so user can remove all their favorites at once.', 'sonaar-music'),
                            'image'     => '',
                        ),
                    ) );
                    $favorites_options->add_field( array(
                        'name'          =>  esc_html__( 'Remove all favorites button label', 'sonaar-music' ),
                        'classes'       => 'srmp3-settings--subitem',
                        'id'            => 'fav_label_removeall',
                        'type'          => 'text',
                        'default'       => esc_html__( 'Remove All Favorites', 'sonaar-music' ),
                        'attributes'    => array(
                            'data-conditional-id'    => 'fav_removeall_bt',
                            'data-conditional-value' => 'true',
                            'placeholder' => esc_html__( 'Remove All Favorites', 'sonaar-music' ),
                        ),
                    ) );
                    $favorites_options->add_field( array(
                        'name'          => esc_html__('Enable Contextual Menu on Favorite Playlists', 'sonaar-music'),
                        'id'            => 'fav_enable_contextual_menu',
                        'type'          => 'switch',
                        'label_cb'      => 'srmp3_add_tooltip_to_label',
                        'default'       => 'true',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => esc_html__('When enabled, the user can hold down the Shift or Command key to select multiple tracks. They can then right-click to remove these selected tracks.', 'sonaar-music'),
                            'image'     => '',
                        ),
                       
                    ) );
                    $favorites_options->add_field( array(
                        'name'          =>  esc_html__( 'Right Click Contextual Menu', 'sonaar-music' ),
                        'classes'       => 'srmp3-settings--subitem',
                        'id'            => 'fav_label_rightclick_remove',
                        'type'          => 'text',
                        'default'       => esc_html__( 'Remove from your Liked Songs', 'sonaar-music' ),
                        'attributes'    => array(
                            'placeholder' => esc_html__( 'Remove from your Liked Songs', 'sonaar-music' ),
                            'data-conditional-id'    => 'fav_enable_contextual_menu',
                            'data-conditional-value' => 'true',
                        ),
                    ) );
                    $favorites_options->add_field( array(
                        'name'          => esc_html__('Unauthentificated Users', 'sonaar-music'),
                        'type'          => 'title',
                        'id'            => 'general_favorites_anonymous_heading'
                    ) );
                    $favorites_options->add_field( array(
                        'name'          => esc_html__('Enable Favorites for NON logged users', 'sonaar-music'),
                        'id'            => 'enable_favorites_for_anonymous',
                        'type'          => 'switch',
                        'default'       => 'true',
                        'label_cb'      => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => __('Unauthentificated users will be able to add favorites via a browser cookie.<br>For logged in users, favorites are saved in the user\'s metadata instead of cookies.', 'sonaar-music'),
                            'image'     => '',
                        ),
                    ) );
                    $favorites_options->add_field( array(
                        'name'          => esc_html__('Dynamic Visibility', 'sonaar-music'),
                        'type'          => 'title',
                        'id'            => 'general_favorites_dv_heading'
                    ) );
                    $favorites_options->add_field( array(
                        'name'          => esc_html__('Enable Dynamic Visibility', 'sonaar-music'),
                        'id'            => 'cta_favorites_dv_enable_main_settings',
                        'type'          => 'switch',
                        'label_cb'      => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => esc_html__('Control visibility of the button according to different conditions', 'sonaar-music'),
                            'image'     => '',
                        ),
                    ) );
                    $favorites_options->add_field( array(
                        'name'          => esc_html__('Visibility State', 'sonaar-music'),
                        'classes'       => 'srmp3-settings--subitem',
                        'id'            => 'cta_favorites_dv_state_main_settings',
                        'type'          => 'select',
                        'options' 						=> [
                            '' 							=> __( 'Select a State', 'sonaar-music' ),
                            'show' 						=> __( 'Show button if condition met', 'sonaar-music' ),
                            'hide' 						=> __( 'Hide button if condition met', 'sonaar-music' ),
                        ],
                        'attributes'    => array(
                            'data-conditional-id'    => 'cta_favorites_dv_enable_main_settings',
                            'data-conditional-value' => 'true',
                        ),
                        'default'       => ''
                    ) );
                    $favorites_options->add_field( array(
                        'name'          => esc_html__('Visibility Condition', 'sonaar-music'),
                        'classes'       => 'srmp3-settings--subitem',
                        'id'            => 'cta_favorites_dv_condition_main_settings',
                        'type'          => 'select',
                        'options' 						=> [
                            '' 							=> __( 'Select Condition', 'sonaar-music' ),
                            'user_logged_in' 			=> __( 'User logged in', 'sonaar-music' ),
                            'user_logged_out' 			=> __( 'User logged out', 'sonaar-music' ),
                            'user_role_is' 				=> __( 'User Role is', 'sonaar-music' ),
                        ],
                        'attributes'    => array(
                            'data-conditional-id'    => 'cta_favorites_dv_enable_main_settings',
                            'data-conditional-value' => 'true',
                        ),
                        'default'       => ''
                    ) ); 
                    
                    $favorites_options->add_field( array(
                        'name'          => esc_html__('Role is', 'sonaar-music'),
                        'classes'       => 'srmp3-settings--subitem srmp3-settings--subitem2',
                        'id'            => 'cta_favorites_dv_role_main_settings',
                        'type'          => 'multicheck',
                        'options'       => Sonaar_Music::get_user_roles(),
                        'attributes'  => array(
                            'data-conditional-id'    => 'cta_favorites_dv_condition_main_settings',
                            'data-conditional-value' => wp_json_encode( array( 'user_role_is' ) ),
                            
                        ),
                        'default'       => ''
                    ) );
                    
                    $favorites_options->add_field( array(
                        'name'          => esc_html__('If condition NOT met, enable redirection on button click', 'sonaar-music'),
                        'classes'       => 'srmp3-settings--subitem',
                        'id'            => 'cta_favorites_dv_enable_redirect_main_settings',
                        'type'          => 'switch',
                        'label_cb'      => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => __( 'If user is logged out or not met the conditions, display the button but redirect people to a link or popup', 'sonaar-music'),
                            'image'     => '',
                        ),
                        'attributes'    => array(
                            'data-conditional-id'    => 'cta_favorites_dv_enable_main_settings',
                            'data-conditional-value' => 'true',
                        ),
                    ) );
                    $favorites_options->add_field( array(
                        'name'          => esc_html__('Redirection URL', 'sonaar-music'),
                        'classes'       => 'srmp3-settings--subitem srmp3-settings--subitem2',
                        'id'            => 'cta_favorites_dv_redirection_url_main_settings',
                        'type'          => 'text_medium',
                        'label_cb'      => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => __('The URL to redirect when user click on the button. If you are using Elementor Popup, set this to #popup and in the Advanced Tab of your Popup > Open By Selector create an anchor trigger link shortcode (example: a[href="#popup"] )', 'sonaar-music'),
                            'image'     => '',
                        ),
                        'attributes'    => array(
                            'placeholder'            => 'https://yourdomain.com/login',
                            'data-conditional-id'    => 'cta_favorites_dv_enable_redirect_main_settings',
                            'data-conditional-value' => 'true',
                        ),
                    ) );
                }

                /**
                * Registers fifth options page, and set main item as parent.
                */
                $args = array(
                    'id'           => 'share_srmp3_cmb2_tab',
                    'menu_title'   => esc_html__( 'Share a Track', 'sonaar-music' ),
                    'title'        => esc_html__( 'Share a Track', 'sonaar-music' ),
                    'object_types' => array( 'options-page' ),
                    'option_key'   => 'srmp3_settings_share', // The option key and admin menu page slug. 'yourprefix_tertiary_options',
                    'parent_slug'  => 'edit.php?post_type=' . SR_PLAYLIST_CPT, // Make options page a submenu item of the themes menu. //'yourprefix_main_options',
                    'tab_group'    => 'yourprefix_main_options',
                    'tab_title'    => esc_html__( 'Share a Track', 'sonaar-music' ),
                );

                // 'tab_group' property is supported in > 2.4.0.
                if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
                    $args['display_cb'] = 'yourprefix_options_display_with_tabs';
                }

                $share_options = new_cmb2_box( $args );
                array_push($options_name, $share_options);
                if ( !function_exists( 'run_sonaar_music_pro' ) ){
                    $share_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Share a Track - Popup Settings', 'sonaar-music'),
                        'type'          => 'title',
                        'id'            => 'promo_music_player_share_title',
                        'after'         => 'promo_ad_cb',
                    ) );
                    $share_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Add Share Link Button for each track', 'sonaar-music'),
                        'id'            => 'promo_enable_share',
                        'type'          => 'switch',
                        'default'       => 0,
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => esc_html__('A share button will be included next to every audio track, enabling users to distribute the audio via a link or on social media platforms. Additionally, this feature can be managed individually for each player widget.', 'sonaar-music'),
                            'image'     => '',
                            'pro'       => true,
                        ),
                    ) );
                    $share_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'    => 'Social Media Platforms',
                        'id'      => 'promo_share_socialmedia',
                        'type'    => 'multicheck',
                        'select_all_button' => false,
                        'options' => array(
                            '1' => esc_html__('Facebook', 'sonaar-music'),
                            '2' => esc_html__('WhatsApp', 'sonaar-music'),
                            '3' => esc_html__('Twitter', 'sonaar-music'),
                            '4' => esc_html__('Email', 'sonaar-music'),
                            '5' => esc_html__('SMS', 'sonaar-music'),
                        ),
                        'default' => array( '1','2','3', '4' ),
                    ) );
                    $share_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Email Default Subject', 'sonaar-music'),
                        'id'            => 'promo_share_email_subject',
                        'type'          => 'text',
                        'default'       => esc_html__('Check out this track I\'ve discovered!', 'sonaar-music'),
                    ) );
                    $share_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Email Default Body', 'sonaar-music'),
                        'id'            => 'promo_share_email_body',
                        'type'          => 'text',
                        'default'       => esc_html__('Hey there, I have come across a remarkable song for our next project. Plug in your headphones and get ready to be blown away!', 'sonaar-music'),
                    ) );
                }
                if (function_exists( 'run_sonaar_music_pro' ) ){
                    $share_options->add_field( array(
                        'name'          => esc_html__('Add Share Link Button for each track', 'sonaar-music'),
                        'id'            => 'force_cta_share',
                        'type'          => 'switch',
                        'label_cb'      => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => __('A share button will be included next to every audio track, enabling users to distribute the audio via a link or on social media platforms.<br><br>Additionally, this feature can be managed individually for each player widget.', 'sonaar-music'),
                            'image'     => '',
                        ),
                    ) );
                    $share_options->add_field( array(
                        'name'          => esc_html__('Lightbox Title Label', 'sonaar-music'),
                        'id'            => 'share_label_title',
                        'type'          => 'text_medium',
                        'default'       => esc_html__('Share Track', 'sonaar-music'),
                        'attributes'    => array( 'placeholder' => esc_html__( 'Share Track', 'sonaar-music' ) ),
                        
                    ) );
                    $share_options->add_field( array(
                        'name'          => esc_html__('Full URL Label', 'sonaar-music'),
                        'id'            => 'share_label_url',
                        'type'          => 'text_medium',
                        'default'       => esc_html__('Full URL', 'sonaar-music'),
                        'attributes'    => array( 'placeholder' => esc_html__( 'Full URL', 'sonaar-music' ) ),
                        
                    ) );
                    $share_options->add_field( array(
                        'name'          => esc_html__('Copy Button Label', 'sonaar-music'),
                        'id'            => 'share_label_copy',
                        'type'          => 'text_medium',
                        'default'       => 'Copy&nbsp;', // there is an conflict with a CMB2 function. We cannot use 'Copy' we need to add non breaking space.
                        'attributes'    => array( 'placeholder' => esc_html__( 'Copy', 'sonaar-music' ) ),
                        
                    ) );
                    $share_options->add_field( array(
                        'name'          => esc_html__('Copied Button Label', 'sonaar-music'),
                        'id'            => 'share_label_copied',
                        'type'          => 'text_medium',
                        'default'       => esc_html__('Copied to Clipboard!', 'sonaar-music'),
                        'attributes'    => array( 'placeholder' => esc_html__( 'Copied to Clipboard!', 'sonaar-music' ) ),
                        
                    ) );
                    $share_options->add_field( array(
                        'name'          => esc_html__('Display text label on the button, not just the icon', 'sonaar-music'),
                        'id'            => 'cta_share_view_label',
                        'type'          => 'switch',
                        'default'       => 'false',
                        'label_cb'      => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => esc_html__('In modern applications, a "Share" buttons often only display a share icon, which can sometimes lead to confusion or a lack of clarity for users unfamiliar with the symbol. This feature adds a text label next to the share icon, ensuring that the button\'s purpose is clear to all users, irrespective of their familiarity with iconography.', 'sonaar-music'),
                            'image'     => '',
                        ),
                    ) );
                    $share_options->add_field( array(
                        'name'          => __('Share Button Label', 'sonaar-music'),
                        'classes'       => 'srmp3-settings--subitem',
                        'id'            => 'force_cta_share_label',
                        'type'          => 'text_small',
                        'default'       => esc_html__('Share', 'sonaar-music'),
                        'attributes'    => array(
                            'data-conditional-id'    => 'cta_share_view_label',
                            'data-conditional-value' => 'true',
                            'placeholder' => esc_html__( 'Share', 'sonaar-music' )
                        ),
                    ) );
                    $share_options->add_field( array(
                        'name'          => esc_html__('Option to share current page with sticky player ready', 'sonaar-music'),
                        'id'            => 'share_stickyplayer',
                        'type'          => 'switch',
                        'default'       => 'true',
                        'label_cb'      => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => '',
                            'text'      => __('Upon opening the share popup, users encounter a "Full URL" leading to the specific post related to the track or product they want to share.<br><br>With this feature enabled, we add a checkbox option beneath the Full URL, allowing users to share the URL of the current page they\'re browsing, with the Sticky Player pre-loaded with shared track.<br><br>Useful if a user is enjoying a podcast on your site and decides to share it with their network, the shared link will open the webpage with the same podcast ready to play in the sticky player.<br><br>This seamless shareability helps to enrich the user experience of your site, making it easy for your audience to share their favorite tracks, podcasts, or any audio content you provide.', 'sonaar-music'),
                            'image'     => '',
                        ),
                    ) );
                    $share_options->add_field( array(
                        'name'          => esc_html__('Share current page with sticky player ready Label', 'sonaar-music'),
                        'classes'       => 'srmp3-settings--subitem',
                        'id'            => 'share_label_stickyplayer',
                        'type'          => 'text',
                        'default'       => esc_html__('Share current page with sticky player ready', 'sonaar-music'),
                        'attributes'    => array(
                            'data-conditional-id'    => 'share_stickyplayer',
                            'data-conditional-value' => 'true',
                            'placeholder' => esc_html__( 'Share current page with sticky player ready', 'sonaar-music' )
                        ),
                    ) );
                    $share_options->add_field( array(
                        'name'          => esc_html__('Start At', 'sonaar-music'),
                        'classes'       => 'srmp3-settings--subitem',
                        'id'            => 'share_label_startat',
                        'type'          => 'text',
                        'default'       => esc_html__('Start At', 'sonaar-music'),
                        'attributes'    => array(
                            'data-conditional-id'    => 'share_stickyplayer',
                            'data-conditional-value' => 'true',
                            'placeholder' => esc_html__( 'Start At', 'sonaar-music' )
                        ),
                    ) );
                    $share_options->add_field( array(
                        'name'          => esc_html__('Enable Social Media Platforms', 'sonaar-music'),
                        'id'            => 'share_socialmedia_enable',
                        'type'          => 'switch',
                        'default'       => 'true',
                    ) );
                    $share_options->add_field( array(
                        'name'    => 'Social Media Platforms',
                        'classes'       => 'srmp3-settings--subitem',
                        'id'      => 'share_socialmedia',
                        'type'    => 'multicheck',
                        'select_all_button' => false,
                        'options' => array(
                            'facebook' => esc_html__('Facebook', 'sonaar-music'),
                            'whatsapp' => esc_html__('WhatsApp', 'sonaar-music'),
                            'twitter' => esc_html__('Twitter', 'sonaar-music'),
                            'sms' => esc_html__('SMS', 'sonaar-music'),
                            'email' => esc_html__('Email', 'sonaar-music'),
                        ),
                        'default' => array( 'facebook','whatsapp','twitter', 'sms', 'email' ),
                        'attributes'    => array(
                            'data-conditional-id'    => 'share_socialmedia_enable',
                            'data-conditional-value' => 'true',
                        ),
                    ) );
                    $share_options->add_field( array(
                        'name'          => esc_html__('Email Default Subject', 'sonaar-music'),
                        'classes'       => 'srmp3-settings--subitem',
                        'id'            => 'share_email_subject',
                        'type'          => 'text',
                        'default'       => esc_html__('Check out this audio I\'ve discovered!', 'sonaar-music'),
                        'attributes'    => array(
                            'data-conditional-id'    => 'share_socialmedia_enable',
                            'data-conditional-value' => 'true',
                            'placeholder' => esc_html__( 'Check out this audio I\'ve discovered!', 'sonaar-music' )
                        ),
                    ) );
                    $share_options->add_field( array(
                        'name'          => esc_html__('Email Default Body', 'sonaar-music'),
                        'classes'       => 'srmp3-settings--subitem',
                        'id'            => 'share_email_body',
                        'type'          => 'textarea_small',
                        'default'       => esc_html__('I have chosen these from {{website_name}} and think they would work very well with our project. Check them out and let me know what you think.', 'sonaar-music'),
                        'attributes'    => array(
                            'data-conditional-id'    => 'share_socialmedia_enable',
                            'data-conditional-value' => 'true',
                            'placeholder' => esc_html__( 'I have chosen these from {{website_name}} and think they would work very well with our project. Check them out and let me know what you think.', 'sonaar-music' )
                        ),
                    ) );

                    if ( get_site_option('SRMP3_ecommerce') == '1'){
                        $share_options->add_field( array(
                            'name'          => esc_html__('Dynamic Visibility', 'sonaar-music'),
                            'type'          => 'title',
                            'id'            => 'general_share_dv_heading'
                        ) );
                        $share_options->add_field( array(
                            'name'          => esc_html__('Enable Dynamic Visibility', 'sonaar-music'),
                            'id'            => 'cta_share_dv_enable_main_settings',
                            'type'          => 'switch',
                            'label_cb'      => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'title'     => '',
                                'text'      => esc_html__('Control visibility of the button according to different conditions', 'sonaar-music'),
                                'image'     => '',
                            ),
                        ) );
                        $share_options->add_field( array(
                            'name'          => esc_html__('Visibility State', 'sonaar-music'),
                            'classes'       => 'srmp3-settings--subitem',
                            'id'            => 'cta_share_dv_state_main_settings',
                            'type'          => 'select',
                            'options' 						=> [
                                '' 							=> __( 'Select a State', 'sonaar-music' ),
                                'show' 						=> __( 'Show button if condition met', 'sonaar-music' ),
                                'hide' 						=> __( 'Hide button if condition met', 'sonaar-music' ),
                            ],
                            'attributes'    => array(
                                'data-conditional-id'    => 'cta_share_dv_enable_main_settings',
                                'data-conditional-value' => 'true',
                            ),
                            'default'       => ''
                        ) );
                        $share_options->add_field( array(
                            'name'          => esc_html__('Visibility Condition', 'sonaar-music'),
                            'classes'       => 'srmp3-settings--subitem',
                            'id'            => 'cta_share_dv_condition_main_settings',
                            'type'          => 'select',
                            'options' 						=> [
                                '' 							=> __( 'Select Condition', 'sonaar-music' ),
                                'user_logged_in' 			=> __( 'User logged in', 'sonaar-music' ),
                                'user_logged_out' 			=> __( 'User logged out', 'sonaar-music' ),
                                'user_role_is' 				=> __( 'User Role is', 'sonaar-music' ),
                            ],
                            'attributes'    => array(
                                'data-conditional-id'    => 'cta_share_dv_enable_main_settings',
                                'data-conditional-value' => 'true',
                            ),
                            'default'       => ''
                        ) ); 
                        
                        $share_options->add_field( array(
                            'name'          => esc_html__('Role is', 'sonaar-music'),
                            'classes'       => 'srmp3-settings--subitem srmp3-settings--subitem2',
                            'id'            => 'cta_share_dv_role_main_settings',
                            'type'          => 'multicheck',
                            'options'       => Sonaar_Music::get_user_roles(),
                            'attributes'  => array(
                                'data-conditional-id'    => 'cta_share_dv_condition_main_settings',
                                'data-conditional-value' => wp_json_encode( array( 'user_role_is' ) ),
                                
                            ),
                            'default'       => ''
                        ) );
                        
                        $share_options->add_field( array(
                            'name'          => esc_html__('If condition NOT met, enable redirection on button click', 'sonaar-music'),
                            'classes'       => 'srmp3-settings--subitem',
                            'id'            => 'cta_share_dv_enable_redirect_main_settings',
                            'type'          => 'switch',
                            'label_cb'      => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'title'     => '',
                                'text'      => __( 'If user is logged out or not met the conditions, display the button but redirect people to a link or popup', 'sonaar-music'),
                                'image'     => '',
                            ),
                            'attributes'    => array(
                                'data-conditional-id'    => 'cta_share_dv_enable_main_settings',
                                'data-conditional-value' => 'true',
                            ),
                        ) );
                        $share_options->add_field( array(
                            'name'          => esc_html__('Redirection URL', 'sonaar-music'),
                            'classes'       => 'srmp3-settings--subitem srmp3-settings--subitem2',
                            'id'            => 'cta_share_dv_redirection_url_main_settings',
                            'type'          => 'text_medium',
                            'label_cb'      => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'title'     => '',
                                'text'      => __('The URL to redirect when user click on the button. If you are using Elementor Popup, set this to #popup and in the Advanced Tab of your Popup > Open By Selector create an anchor trigger link shortcode (example: a[href="#popup"] )', 'sonaar-music'),
                                'image'     => '',
                            ),
                            'attributes'    => array(
                                'placeholder'            => 'https://yourdomain.com/login',
                                'data-conditional-id'    => 'cta_share_dv_enable_redirect_main_settings',
                                'data-conditional-value' => 'true',
                            ),
                        ) );
                    }
                    $share_options->add_field( array(
                        'name'          => esc_html__('Things to know', 'sonaar-music'),
                        //'desc'          => __('When a link is shared on social media platforms such as Facebook, Twitter, or LinkedIn, these platforms crawl and parse the webpage information in order to display a link preview.<br>The link preview typically includes a title, a description, and an image, if available. Different platforms use different metadata protocols to extract this information.<br><br>WordPress users can use SEO plugins, like Yoast SEO or All-in-One SEO, which provide user-friendly interfaces to set these meta tags on a per-page or per-post basis. They can also provide default settings for posts where custom tags are not set.', 'sonaar-music'),
                        'desc' => __('When a link is shared on social media platforms such as Facebook, Twitter, or LinkedIn, these platforms crawl and parse the webpage information in order to display a link preview.<br>The link preview typically includes a title, a description, and an image, if available. Different platforms use different metadata protocols to extract this information.<br><br>WordPress users can use SEO plugins, like <a href="https://wordpress.org/plugins/wordpress-seo/" target="_blank" rel="noopener noreferrer">Yoast SEO</a> or <a href="https://wordpress.org/plugins/all-in-one-seo-pack/" target="_blank" rel="noopener noreferrer">All-in-One SEO</a>, which provide user-friendly interfaces to set these meta tags on a per-page or per-post basis. They can also provide default settings for posts where custom tags are not set.', 'sonaar-music'),
                        'type'          => 'title',
                        'id'            => 'share_title_admin'
                    ) );

                }










                /**
                 * Registers fifth options page, and set main item as parent.
                 */
                $args = array(
                    'id'           => 'yourprefix_fifth_options_page',
                    'menu_title'   => esc_html__( 'Popup Settings', 'sonaar-music' ),
                    'title'        => esc_html__( 'Popup Call-to-Action Settings', 'sonaar-music' ),
                    'object_types' => array( 'options-page' ),
                    'option_key'   => 'srmp3_settings_popup', // The option key and admin menu page slug. 'yourprefix_tertiary_options',
                    'parent_slug'  => 'edit.php?post_type=' . SR_PLAYLIST_CPT, // Make options page a submenu item of the themes menu. //'yourprefix_main_options',
                    'tab_group'    => 'yourprefix_main_options',
                    'tab_title'    => esc_html__( 'Popup / Lightbox', 'sonaar-music' ),
                );

                // 'tab_group' property is supported in > 2.4.0.
                if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
                    $args['display_cb'] = 'yourprefix_options_display_with_tabs';
                }

                $popup_options = new_cmb2_box( $args );
                array_push($options_name, $popup_options);
                if (!function_exists( 'run_sonaar_music_pro' ) ){
                     // POP-UP IF PRO PLUGIN IS INSTALLED
                     $popup_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Popup / Lightbox / Modal Look and feel', 'sonaar-music'),
                        'type'          => 'title',
                        'id'            => 'promo_music_player_sticky_title',
                        'after'         => 'promo_ad_cb',
                    ) );
                     $popup_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Enable Call-to-Action Pop-up', 'sonaar-music'),
                        'type'          => 'switch',
                        'default'       => 0,
                        'id'            => 'promo_cta-popup',
                        'after'         => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'title'     => esc_html__('', 'sonaar-music'),
                                'text'      => sprintf(__('You can display call-to-action buttons beside each track, by editing your %1$s\'s post.<br><br>For each button, you can choose its action between a link URL or a Popup to display content such as text, forms or any third party plugin shortcodes in a popup window.<br><br>
                                You can also display track description and we will display it within a lightbox. Useful for episode and show notes!<br><br>
                                This is the popup lightbox look and feel settings', 'sonaar-music'), $this->sr_GetString('playlist')),
                                'image'     => 'popup.svg',
                                'pro'       => true,
                            ),
                    ) );

                }
                if ( function_exists( 'run_sonaar_music_pro' ) ){
                    // POP-UP IF PRO PLUGIN IS INSTALLED
                    $popup_options->add_field( array(
                        'name'          => esc_html__('Popup / Lightbox / Modal Look and feel', 'sonaar-music'),
                        'type'          => 'title',
                        'id'            => 'cta-popup',
                        'after'         => 'srmp3_add_tooltip_to_label',
                            'tooltip'       => array(
                                'title'     => esc_html__('', 'sonaar-music'),
                                'text'      => sprintf(__('You can display call-to-action buttons beside each track, by editing your %1$s\'s post.<br><br>For each button, you can choose its action between a link URL or a Popup to display content such as text, forms or any third party plugin shortcodes in a popup window.<br><br>
                                You can also display track description and we will display it within a lightbox. Useful for episode and show notes!<br><br>
                                This is the popup lightbox look and feel settings', 'sonaar-music'), $this->sr_GetString('playlist')),
                                'image'     => 'popup.svg',
                                'pro'       => true,
                            ),
                    ) );
                    $popup_options->add_field( array(
                        'id'            => 'cta-popup-typography',
                        'type'          => 'typography',
                        'name'          => esc_html__('Typography', 'sonaar-music'),
                        'fields'        => array(
                            'font-weight'       => false,
                            'background'        => false,
                            'text-align'        => false,
                            'text-transform'    => false,
                            'line-height'       => false,
                        )
                    ) );
                    $popup_options->add_field( array(
                        'id'            => 'cta-popup-close-btn-color',
                        'type'          => 'colorpicker',
                        'name'          => esc_html__('Close button color', 'sonaar-music'),
                        'class'         => 'color',
                        'default'       => '#000000',
                        'options'       => array(
                            'alpha'         => false,
                        ),
                    ) ); 
                    $popup_options->add_field( array(
                        'id'            => 'cta-popup-background',
                        'type'          => 'colorpicker',
                        'name'          => esc_html__('Background Color', 'sonaar-music'),
                        'class'         => 'color',
                        'default'       => '#ffffff',
                        'options'       => array(
                            'alpha'         => false,
                        ),
                    ) );  
                    if ( defined( 'WC_VERSION' ) && Sonaar_Music::get_option('wc_variation_lb', 'srmp3_settings_woocommerce') != 'false' ){     
                        $popup_options->add_field( array(
                            'id'            => 'cta-popup-variant-bg-color',
                            'type'          => 'colorpicker',
                            'name'          => esc_html__('Product Variation Background Color', 'sonaar-music'),
                            'class'         => 'color',
                            'default'       => '#0202022b',
                            'options'       => array(
                                'alpha'         => true,
                            ),
                            //'description'   => esc_html__('For Product Variations Popup Modal', 'sonaar-music'),
                        ) ); 
                        $popup_options->add_field( array(
                            'id'            => 'cta-popup-variant-ac-color',
                            'type'          => 'colorpicker',
                            'name'          => esc_html__('Product Variation Accent Color', 'sonaar-music'),
                            'class'         => 'color',
                            'default'       => '#02020261',
                            'options'       => array(
                                'alpha'         => true,
                            ),
                            //'description'   => esc_html__('For Product Variations Popup Modal', 'sonaar-music'),
                        ) ); 
                    }   
                    $popup_options->add_field( array(
                        'id'            => 'cta-popup-btn-bg-color',
                        'type'          => 'colorpicker',
                        'name'          => esc_html__('Button Color', 'sonaar-music'),
                        'class'         => 'color',
                        'default'       => '#0170b9',
                        'options'       => array(
                            'alpha'         => false,
                        ),
                        'description'   => esc_html__('For Product Variations Popup Modal', 'sonaar-music'),
                    ) );  
                    $popup_options->add_field( array(
                        'id'            => 'cta-popup-btn-txt-color',
                        'type'          => 'colorpicker',
                        'name'          => esc_html__('Button Text Color', 'sonaar-music'),
                        'class'         => 'color',
                        'default'       => '#ffffff',
                        'options'       => array(
                            'alpha'         => false,
                        ),
                        'description'   => esc_html__('For Product Variations Popup Modal', 'sonaar-music'),
                    ) );      
                }
                 /**
                 * Registers fifth options page, and set main item as parent.
                 */
                $args = array(
                    'id'           => 'yourprefix_sixth_options_page',
                    'menu_title'   => esc_html__( 'Stats & Reports', 'sonaar-music' ),
                    'title'        => esc_html__( 'Statistic & Report Settings', 'sonaar-music' ),
                    'object_types' => array( 'options-page' ),
                    'option_key'   => 'srmp3_settings_stats', // The option key and admin menu page slug. 'yourprefix_tertiary_options',
                    'parent_slug'  => 'edit.php?post_type=' . SR_PLAYLIST_CPT, // Make options page a submenu item of the themes menu. //'yourprefix_main_options',
                    'tab_group'    => 'yourprefix_main_options',
                    'tab_title'    => esc_html__( 'Stats & Reports', 'sonaar-music' ),
                );

                // 'tab_group' property is supported in > 2.4.0.
                if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
                    $args['display_cb'] = 'yourprefix_options_display_with_tabs';
                }

                $stats_options = new_cmb2_box( $args );
                array_push($options_name, $stats_options);
                if (!function_exists( 'run_sonaar_music_pro' ) || !get_site_option( 'sonaar_music_licence', '' )){
                     $stats_options->add_field( array(
                        'classes'       => 'srmp3-pro-feature',
                        'name'          => esc_html__('Statistic & Report', 'sonaar-music'),
                        'type'          => 'title',
                        'id'            => 'promo_music_player_sticky_title',
                        'after'         => 'promo_ad_cb',
                        'options' => array(
                            'textpromo' => esc_html__('PRO LICENCE REQUIRED | UPGRADE TO PRO', 'sonaar-music'),
                        ),
                    ) );
                    $stats_options->add_field( array(
                        'name'          => esc_html__('Google Analytics Tracking Code', 'sonaar-music'),
                        'classes'       => 'srmp3-pro-feature',
                        'id'            => 'promo_srmp3_ga_tag',
                        'type'          => 'text_medium',
                        'attributes'    => array( 'placeholder' => esc_html__( "GA_MEASUREMENT_ID", 'sonaar-music' ) ),
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('', 'sonaar-music'),
                            'text'      => sprintf(__('MP3 Audio Player PRO can connect to your Google Analytics so you will receive statistics report such as number of plays and number of downloads for each tracks directly in your Google Analytics Dashboard. GA_MEASUREMENT_ID should be replaced with the Google Analytics property ID for the website you want to track. All you need is a %1$sGoogle Analytics%2$s account.<br><br>Read %3$sthis article%2$s to learn more', 'sonaar-music'), '<a href="http://www.google.com/analytics/" target="_blank">', '</a>', '<a href="https://sonaar.io/docs/use-google-analytics-to-track-audio-player-statistics/" target="_blank">'),
                            'image'     => '',
                            'pro'       => true,
                        ),
                    ) );
                }
                if ( function_exists( 'run_sonaar_music_pro' ) && get_site_option( 'sonaar_music_licence', '' )){
                    // POP-UP IF PRO PLUGIN IS INSTALLED
                    if(class_exists( 'PiwikTracker' )){
                        $matomoActivated = __('<br><br><div style="color:green;">Matomo activated and currently tracking!</div>', 'sonaar-music');
                    }else{
                        $matomoActivated = __('<br><br><div style="color:gray;">Matomo is not installed</div>', 'sonaar-music');;
                    }
                    $stats_options->add_field( array(
                        'name'          => esc_html__('Matomo Analytics Free Plugin [Recommended]', 'sonaar-music'),
                        'type'          => 'title',
                        'description'   => __( 'Matomo is a popular open-source web analytics plugin that offers a range of features for tracking and analyzing website traffic and user behavior!<br>
                        MP3 Audio Player Pro integrates Matomo seamlessly and allows you to see how many times your track have been played or downloaded. All Free!<br>
                        <a href="https://wordpress.org/plugins/matomo/" target="_blank">Download Matomo for WP!</a>', 'sonaar-music'),
                        'id'            => 'stats_report_matomo_title',
                        'after'         => $matomoActivated,
                        'after_field'   => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => 'What is Matomo?',
                            'text'      => __('Matomo, formerly known as Piwik, is a popular open-source web analytics platform that offers a range of features for tracking and analyzing website traffic and user behavior.<br><br>
                            MP3 Audio Player Pro integrates Matomo seamlessly and allows you to see how many times your track have been played or downloaded. All Free!<br><br><a href="https://wordpress.org/plugins/matomo/" target="_blank">Download Matomo for WP!</a>
                            <br><br>
                            Wait, there is more! If you are a professional and want powerful insights into how your audience listens to your audio, Matomo has a <strong>premium addon</strong> called <a href="https://plugins.matomo.org/MediaAnalytics?wp=1" target="_blank">Media Analytics</a>.<br><br>With Media Analytics, you can obtain metrics such as who and how often a media was played, the duration for which they played it, real-time reports of interactions with your audio content, etc.', 'sonaar-music'),
                            'image'     => '',
                            'pro'       => true,
                        ),
                    ) );
                    if(class_exists( 'PiwikTracker' ) && class_exists('Piwik\Plugins\MediaAnalytics\MediaAnalytics') ){
                        $stats_options->add_field( array(
                            'name'          => esc_html__('Delegate analytics to MediaAnalytics [Recommended]', 'sonaar-music'),
                            'id'            => 'srmp3_use_matomo_mediaanalytics',
                            'type'          => 'switch',
                            'default'       => '',
                            'after'         => 'srmp3_add_tooltip_to_label',
                            //This option allows Media Analytics to take over the tracking of audio analytics, ensuring precise data while preventing duplicate tracking entries from your MP3 Audio Player Plugin.
                            'tooltip'       => array(
                                'title'     => __('Delegate analytics to MediaAnalytics', 'sonaar-music'),
                                'text'      => __('This option allows Matomo\'s Media Analytics to take over the tracking of audio analytics, ensuring precise data while preventing duplicate tracking entries from your MP3 Audio Player Pro Plugin.', 'sonaar-music'),
                                'image'     => '',
                                'pro'       => true,
                            ),
                        ) );
                    }
                    $stats_options->add_field( array(
                        'name'          => esc_html__('Google Analytics', 'sonaar-music'),
                        'description'   => __('MP3 Audio Player Pro is able to connect to your Google Analytics so you will receive statistics report such as number of plays and number of downloads for each tracks directly in your Google Analytics Dashboard', 'sonaar-music'),
                        'type'          => 'title',
                        'id'            => 'stats_report_ga_title',
                    ) );
                    $stats_options->add_field( array(
                        'name'          => esc_html__('Google Analytics Tracking Code', 'sonaar-music'),
                        'id'            => 'srmp3_ga_tag',
                        'type'          => 'text_medium',
                        'attributes'    => array( 'placeholder' => esc_html__( "GA_MEASUREMENT_ID", 'sonaar-music' ) ),
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('', 'sonaar-music'),
                            'text'      => sprintf(__('MP3 Audio Player PRO can connect to your Google Analytics so you will receive statistics report such as number of plays and number of downloads for each tracks directly in your Google Analytics Dashboard. GA_MEASUREMENT_ID should be replaced with the Google Analytics property ID for the website you want to track. All you need is a %1$sGoogle Analytics%2$s account.<br><br>Read %3$sthis article%2$s to learn more', 'sonaar-music'), '<a href="http://www.google.com/analytics/" target="_blank">', '</a>', '<a href="https://sonaar.io/docs/use-google-analytics-to-track-audio-player-statistics/" target="_blank">'),
                            'image'     => '',
                            'pro'       => true,
                        ),
                    ) );
                    $stats_options->add_field( array(
                        'name'          => esc_html__('Use Built-In Stats Report [Deprecated]', 'sonaar-music'),
                        'type'          => 'title',
                        'id'            => 'stats_report_builtin_title',
                    ) );
                    $stats_options->add_field( array(
                        'name'          => esc_html__('Use Built-In Stats Report (Deprecated)', 'sonaar-music'),
                        'id'            => 'srmp3_use_built_in_stats',
                        'type'          => 'switch',
                        'after'         => 'srmp3_add_tooltip_to_label',
                        'tooltip'       => array(
                            'title'     => esc_html__('Deprecated Option', 'sonaar-music'),
                            'text'      => esc_html__('This option is deprecated and will be removed in the next update. Use Matomo Analytics instead.', 'sonaar-music'),
                            'image'     => '',
                            'pro'       => true,
                        ),
                    ) );
                }
            /**
             * Registers Settings Tools options page, and set main item as parent.
             */
            $args = array(
                'id'           => 'srmp3_settings_tools',
                'menu_title'   => esc_html__( 'Tools', 'sonaar-music' ),
                'title'        => esc_html__( 'Tools & Importer', 'sonaar-music' ),
                'object_types' => array( 'options-page' ),
                'option_key'   => 'srmp3_settings_tools', // The option key and admin menu page slug. 'yourprefix_tertiary_options',
                'parent_slug'  => 'edit.php?post_type=' . SR_PLAYLIST_CPT, // Make options page a submenu item of the themes menu. //'yourprefix_main_options',
                'tab_group'    => 'yourprefix_main_options',
                'tab_title'    => esc_html__( 'Tools / Import', 'sonaar-music' ),
            );
            $setting_tools = new_cmb2_box( $args );

            $setting_tools->add_field( array(
                'name'          => esc_html__('Waveform & Peaks Generation', 'sonaar-music'),
                'type'          => 'title',
                'description'   => __('Use this tool to batch generate waveform and peaks for all your tracks.', 'sonaar-music'),
                'id'            => 'srtools_peak_gen_title'
            ) );
            $setting_tools->add_field( array(
                'id'            => 'peaks_overwrite',
                'name'          => esc_html__('Overwrite existing Peaks?', 'sonaar-music'),
                //'classes'       => 'ffmpeg_field',
                'type'          => 'switch',
                'return'        => 'true',
                'default'       => 'false',
            ) );
            $setting_tools->add_field( array(
                'id'            => 'srmp3-settings-generatepeaks-bt-container',
                'classes'       => 'srmp3-settings-generatepeaks-bt-container',
                'description'   => __('
                <div class="srmp3-bulk-wrapper">
                    <div>
                        <button id="srmp3_index_audio_peak" class="srmp3-generate-bt srmp3-generatepeaks-bt showSpinner">Generate Peaks</button>
                        <span id="srmp3_indexTracks_status"></span>
                        <progress id="indexationProgress" style="width:100%;margin-top:10px;display:none;" value="0" max="100"></progress>
                        <span id="progressText"></span>
                    </div>
                    <button id="srmp3-bulkRemove-bt" class="srmp3-generate-bt"><span class="dashicons dashicons-trash"></span>Remove All Peaks</button>
                </div>
                ','sonaar-music'),
                'type'          => 'text_small',
            ) );

            if (Sonaar_Music::get_option('player_type', 'srmp3_settings_general') == 'podcast' ){
                $setting_tools->add_field( array(
                    'name'          => esc_html__('Podcast RSS Importer', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => sprintf( __( '%1$sImport your existing Podcast Episodes%2$s by using your RSS Feed URL provided by your Podcast provider. We support all major podcast distributors!', 'sonaar-music' ), '<a href="' . esc_url(get_admin_url( null, 'admin.php?import=podcast-rss' )) . '" target="' . wp_strip_all_tags( '_self' ) . '">', '</a>' ),
                    'id'            => 'srtools_podcast_importer'
                ) );
                if ( !function_exists('run_sonaar_music_pro')){
                    $setting_tools->add_field( array(
                        'name'          => esc_html__('Podcast Fetcher : Automatically import New Episode', 'sonaar-music'),
                        'after'         => 'promo_ad_cb',
                        'classes'       => array('srmp3-pro-feature', 'prolabel--nomargin', 'prolabel--nohide'),
                        'type'          => 'title',
                        'description'   => sprintf( __( 'Give you the ability to automatically fetch/import new episodes on your website from your existing Podcast distributor as soon as a new episode came out! %1$sLearn More%2$s ', 'sonaar-music' ), '<a href="https://sonaar.io/docs/automatically-fetch-import-new-episodes-from-your-podcast-rss-feed/" target="_blank">', '</a>' ),
                        'id'            => 'srtools_podcast_importer_cron'
                    ) );
                }else{
                    $setting_tools->add_field( array(
                        'name'          => esc_html__('Podcast Fetcher : Automatically import New Episode', 'sonaar-music'),
                        'type'          => 'title',
                        'description'   => sprintf( __( 'Give you the ability to automatically fetch/import new episodes on your website from your existing Podcast distributor as soon as a new episode came out! %1$sLearn More%2$s ', 'sonaar-music' ), '<a href="https://sonaar.io/docs/automatically-fetch-import-new-episodes-from-your-podcast-rss-feed/" target="_blank">', '</a>' ),
                        'id'            => 'srtools_podcast_importer_cron'
                    ) );
                }
            }
            if ( !function_exists('run_sonaar_music_pro')){
                $setting_tools->add_field( array(
                    'after'         => 'promo_ad_cb',
                    //'classes'       => array('srmp3-pro-feature', 'prolabel--nomargin', 'prolabel--nohide'),
                    'classes'       => array('srmp3-pro-feature', 'prolabel--nomargin', 'prolabel--nohide'),
                    'name'          => esc_html__('Playlist Bulk Importer', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   =>  esc_html__( 'Import Audio from CSV file or Media Library and create post(s) or product(s) in 1-click!', 'sonaar-music' ),
                    'id'            => 'srtools_importer'
                ) );
            }else{
                if (did_action('elementor/loaded')) {
                    $setting_tools->add_field( array(
                        'name'          => esc_html__('Import Player Elementor Templates', 'sonaar-music'),
                        'type'          => 'title',
                        'description'   => sprintf( __( '%1$sImport stylish and beautiful audio player skins in Elementor%2$s', 'sonaar-music' ), '<a href="' . esc_url(get_admin_url( null, 'edit.php?post_type=' . SR_PLAYLIST_CPT . '&page=srmp3-import-templates' )) . '" target="' . wp_strip_all_tags( '_self' ) . '">', '</a>' ),
                        'id'            => 'srtools_templates_importer'
                    ) );
                }
                $setting_tools->add_field( array(
                    'name'          => esc_html__('Playlist Bulk Importer', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => sprintf( __( '%1$sImport Audio from CSV file or Media Library and create post(s) or product(s) in 1-click!%2$s', 'sonaar-music' ), '<a href="' . esc_url(get_admin_url( null, 'edit.php?post_type=' . SR_PLAYLIST_CPT . '&page=sonaar_music_pro_tools' )) . '" target="' . wp_strip_all_tags( '_self' ) . '">', '</a>' ),
                    'id'            => 'srtools_importer'
                ) );
                
                if(get_site_option('SRMP3_ecommerce') == '1'){
                    $setting_tools->add_field( array(
                        'name'          => esc_html__('Tracks Indexation', 'sonaar-music'),
                        'type'          => 'title',
                        'description'   => __('This tool indexes the music track titles for efficient search when using the Lazyload Pagination', 'sonaar_music'),
                        'id'            => 'srtools_regenerate_track'
                    ) );
                    $setting_tools->add_field( array(
                        'name'          => esc_html('General Fields', 'sonaar-music'),
                        'id'            => 'srtools_regenerate_generalfields',
                        'type'          => 'multicheck',
                        'select_all_button' => false,
                        'options'       => index_get_generalfields(),
                        'default'       => array('post_title', 'track_mp3_title', 'track_mp3_album','track_mp3_artist', 'stream_title','stream_album','artist_name','track_description'),
                    ) );
                    if(function_exists('acf')){
                        function get_acf_dropdown_options() {
                            $acf_field_groups = acf_get_field_groups();
                            $options = array();
                    
                            // Loop through each ACF field group
                            foreach ($acf_field_groups as $field_group) {
                                $fields = acf_get_fields($field_group['key']);
                    
                                // Loop through each field within the field group
                                foreach ($fields as $field) {
                                    $options[$field['name']] = $field_group['title'] . ' > ' . $field['label'];
                                }
                            }
                    
                            return $options;
                        }
                        $setting_tools->add_field( array(
                            'name'          => esc_html__( 'ACF Custom Fields', 'sonaar-music'),
                            'id'            => 'srtools_regenerate_acf_field',
                            'type'          => 'multicheck',
                            'default'       => '',
                            'select_all_button' => false,
                            'options'       => get_acf_dropdown_options(),
                        ) );
                    }
                    if ( function_exists('jet_engine') && jet_engine()->meta_boxes ) {
                        function get_jetengine_dropdown_options() {
                            $fields = jet_engine()->meta_boxes->get_fields_for_select('plain');
                            $options = array();
                        
                            // Loop through each group of options and extract them
                            foreach ($fields as $field_group) {
                                if (isset($field_group['label']) && isset($field_group['options']) && $field_group['label'] != 'Default user fields') {
                                    foreach ($field_group['options'] as $key => $value) {
                                        // Combine the parent label with the field label
                                        $options[$key] = $field_group['label'] . ' > ' . $value;
                                    }
                                }
                            }
                        
                            return $options;
                        }
                        $setting_tools->add_field( array(
                            'name'          => esc_html__( 'JetEngine Meta Boxes', 'sonaar-music'),
                            'id'            => 'srtools_regenerate_jetengine_field',
                            'type'          => 'multicheck',
                            'default'       => '',
                            'select_all_button' => false,
                            'options'       => get_jetengine_dropdown_options(),
                        ) );
                    }
                    $setting_tools->add_field( array(
                        'name'          => esc_html('Taxonomies', 'sonaar-music'),
                        'id'            => 'srtools_regenerate_tax',
                        'description'   => __('<br><br><button id="srmp3_indexTracks" class="srmp3-generate-bt showSpinner">Rebuild Index</button> <span id="srmp3_indexTracks_status"></span><progress id="indexationProgress" style="width:100%;margin-top:10px;display:none;" value="0" max="100"></progress><span id="progressText"></span>','sonaar-music'),
                        'type'          => 'multicheck',
                        'select_all_button' => false,
                        'options'    => index_get_taxonomies(),
                        'default'       => array('playlist-cat', 'playlist-tag', 'podcast-show', 'product_cat', 'product_tag'),
                    ) );
                    

                }
               
            }
            $setting_tools->add_field( array(
                'name'          => esc_html__('Global Settings Import/Export', 'sonaar-music'),
                'type'          => 'title',
                'description'   => sprintf( __( '%1$sImport/Export plugin settings from/to another website%2$s', 'sonaar-music' ), '<a href="' . esc_url(get_admin_url( null, 'edit.php?post_type=' . SR_PLAYLIST_CPT . '&page=srmp3-import-page' )) . '" target="' . wp_strip_all_tags( '_self' ) . '">', '</a>' ),
                'id'            => 'srtools_option_importer'
            ) );

            if (did_action('elementor/loaded')) {
                //add_submenu_page('edit.php?post_type=' . SR_PLAYLIST_CPT, __( 'Import Elementor Player Templates', 'sonaar-music' ), 'Import Player Templates', 'manage_options', 'srmp3-import-templates','srmp3_get_json_url');
                $cmb_options_sr_elementor_import = new_cmb2_box( array(
            
                    'id'           		=> 'sonaar_music_pro_elementor_import_metabox',
                    'title'        		=> esc_html__( 'Sonaar Music', 'sonaar-music-pro' ),
                    'object_types' 		=> array( 'options-page' ),
                    'option_key'      	=> 'srmp3-import-templates', // The option key and admin menu page slug.
                    'icon_url'        	=> 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
                    'menu_title'      	=> esc_html__( 'Import Templates', 'sonaar-music-pro' ), // Falls back to 'title' (above).
                    'parent_slug'     	=> 'edit.php?post_type=' . SR_PLAYLIST_CPT, // Make options page a submenu item of the themes menu.
                    'capability'      	=> 'manage_options', // Cap required to view options-page.
                    'enqueue_js' 		=> false,
                    'cmb_styles' 		=> false,
                    'display_cb'		=> 'srmp3_get_json_url',
                    'position' 			=> 998,
                    ) );
            }
            // 'tab_group' property is supported in > 2.4.0.
            if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
                $args['display_cb'] = 'yourprefix_options_display_with_tabs';
            }
            $category_options = array(
                'Arts'                       => esc_html__( 'Arts', 'sonaar' ),
                'Business'                   => esc_html__( 'Business', 'sonaar' ),
                'Comedy'                     => esc_html__( 'Comedy', 'sonaar' ),
                'Education'                  => esc_html__( 'Education', 'sonaar' ),
                'Fiction'                  	 => esc_html__( 'Fiction', 'sonaar' ),
                'Government' 				 => esc_html__( 'Government', 'sonaar' ),
                'Health &amp; Fitness'           => esc_html__( 'Health & Fitness', 'sonaar' ),
                'History'           		 => esc_html__( 'History', 'sonaar' ),
                'Kids &amp; Family'              => esc_html__( 'Kids & Family', 'sonaar' ),
                'Leisure'           		 => esc_html__( 'Leisure', 'sonaar' ),
                'Music'                      => esc_html__( 'Music', 'sonaar' ),
                'News'           			 => esc_html__( 'News', 'sonaar' ),
                'Religion &amp; Spirituality'    => esc_html__( 'Religion & Spirituality', 'sonaar' ),
                'Science'        			 => esc_html__( 'Science', 'sonaar' ),
                'Society &amp; Culture'          => esc_html__( 'Society & Culture', 'sonaar' ),
                'Sports'        			 => esc_html__( 'Sports', 'sonaar' ),
                'Technology'                 => esc_html__( 'Technology', 'sonaar' ),
                'True Crime'                 => esc_html__( 'True Crime', 'sonaar' ),
                'TV &amp; Film'                  => esc_html__( 'TV & Film', 'sonaar' ),
            );
            $subcategory_options = array(
                'None'                       => esc_html__( '-- None --', 'sonaar' ),
    
                'Books'                      => esc_html__( 'Arts > Books', 'sonaar' ),
                'Design'                     => esc_html__( 'Arts > Design', 'sonaar' ),
                'Fashion & Beauty'           => esc_html__( 'Arts > Fashion & Beauty', 'sonaar' ),
                'Food'                       => esc_html__( 'Arts > Food', 'sonaar' ),
                'Performing Arts'            => esc_html__( 'Arts > Performing Arts', 'sonaar' ),
                'Visual Arts'                => esc_html__( 'Arts > Visual Arts', 'sonaar' ),
    
                'Careers'                    => esc_html__( 'Business > Careers', 'sonaar' ),
                'Enterpreneurship'           => esc_html__( 'Business > Enterpreneurship', 'sonaar' ),
                'Investing'                  => esc_html__( 'Business > Investing', 'sonaar' ),
                'Management'                 => esc_html__( 'Business > Management', 'sonaar' ),
                'Marketing'                  => esc_html__( 'Business > Marketing', 'sonaar' ),
                'Non-profit'                 => esc_html__( 'Business > Non-profit', 'sonaar' ),
    
                'Comedy Interviews'          => esc_html__( 'Comedy > Comedy Interviews', 'sonaar' ),
                'Improv'                     => esc_html__( 'Comedy > Improv', 'sonaar' ),
                'Standup'                    => esc_html__( 'Comedy > Standup', 'sonaar' ),
    
                'Courses'                    => esc_html__( 'Education > Courses', 'sonaar' ),
                'How to'                     => esc_html__( 'Education > How to', 'sonaar' ),
                'Language Learning'          => esc_html__( 'Education > Language Learning', 'sonaar' ),
                'Self Improvement'           => esc_html__( 'Education > Self Improvement', 'sonaar' ),
    
                'Comedy Fiction'             => esc_html__( 'Fiction > Comedy Fiction', 'sonaar' ),
                'Drama'                      => esc_html__( 'Fiction > Drama', 'sonaar' ),
                'Science Fiction'            => esc_html__( 'Fiction > Science Fiction', 'sonaar' ),
    
                'Alternative Health'         => esc_html__( 'Health & Fitness > Alternative Health', 'sonaar' ),
                'Fitness'                    => esc_html__( 'Health & Fitness > Fitness', 'sonaar' ),
                'Medicine'                   => esc_html__( 'Health & Fitness > Medicine', 'sonaar' ),
                'Mental Health'              => esc_html__( 'Health & Fitness > Mental Health', 'sonaar' ),
                'Nutrition'                  => esc_html__( 'Health & Fitness > Nutrition', 'sonaar' ),
                'Sexuality'                  => esc_html__( 'Health & Fitness > Sexuality', 'sonaar' ),
    
                'Education for Kids'         => esc_html__( 'Kids & Family > Education for Kids', 'sonaar' ),
                'Parenting'                  => esc_html__( 'Kids & Family > Parenting', 'sonaar' ),
                'Pets & Animals'             => esc_html__( 'Kids & Family > Pets & Animals', 'sonaar' ),
                'Stories for Kids'           => esc_html__( 'Kids & Family > Stories for Kids', 'sonaar' ),
    
                'Animation & Manga'          => esc_html__( 'Leisure > Animation & Manga', 'sonaar' ),
                'Automotive'                 => esc_html__( 'Leisure > Automotive', 'sonaar' ),
                'Aviation'                   => esc_html__( 'Leisure > Aviation', 'sonaar' ),
                'Crafts'                     => esc_html__( 'Leisure > Crafts', 'sonaar' ),
                'Games'                      => esc_html__( 'Leisure > Games', 'sonaar' ),
                'Hobbies'                    => esc_html__( 'Leisure > Hobbies', 'sonaar' ),
                'Home & Garden'              => esc_html__( 'Leisure > Home & Garden', 'sonaar' ),
                'Video Games'                => esc_html__( 'Leisure > Video Games', 'sonaar' ),
    
                'Music Commentary'           => esc_html__( 'Music > Music Commentary', 'sonaar' ),
                'Music History'              => esc_html__( 'Music > Music History', 'sonaar' ),
                'Music Interviews'           => esc_html__( 'Music > Music Interviews', 'sonaar' ),
    
                'Business News'              => esc_html__( 'News > Business News', 'sonaar' ),
                'Daily News'                 => esc_html__( 'News > Daily News', 'sonaar' ),
                'Entertainment News'         => esc_html__( 'News > Entertainment News', 'sonaar' ),
                'News Commentary'            => esc_html__( 'News > News Commentary', 'sonaar' ),
                'Politics'                   => esc_html__( 'News > Politics', 'sonaar' ),
                'Sports News'                => esc_html__( 'News > Sports News', 'sonaar' ),
                'Tech News'                  => esc_html__( 'News > Tech News', 'sonaar' ),
    
                'Buddhism'                   => esc_html__( 'Religion & Spirituality > Buddhism', 'sonaar' ),
                'Christianity'               => esc_html__( 'Religion & Spirituality > Christianity', 'sonaar' ),
                'Hinduism'                   => esc_html__( 'Religion & Spirituality > Hinduism', 'sonaar' ),
                'Islam'                      => esc_html__( 'Religion & Spirituality > Islam', 'sonaar' ),
                'Judaism'                    => esc_html__( 'Religion & Spirituality > Judaism', 'sonaar' ),
                'Religion'                   => esc_html__( 'Religion & Spirituality > Religion', 'sonaar' ),
                'Spirituality'               => esc_html__( 'Religion & Spirituality > Spirituality', 'sonaar' ),
                'Buddhism'                   => esc_html__( 'Religion & Spirituality > Buddhism', 'sonaar' ),
    
    
                'Astronomy'                  => esc_html__( 'Science > Astronomy', 'sonaar' ),
                'Chemistry'                  => esc_html__( 'Science > Chemistry', 'sonaar' ),
                'Earth Sciences'             => esc_html__( 'Science > Earth Sciences', 'sonaar' ),
                'Life Sciences'              => esc_html__( 'Science > Life Sciences', 'sonaar' ),
                'Mathematics'                => esc_html__( 'Science > Mathematics', 'sonaar' ),
                'Natural Sciences'           => esc_html__( 'Science > Natural Sciences', 'sonaar' ),
                'Nature'                   	 => esc_html__( 'Science > Nature', 'sonaar' ),
                'BuddhPhysicssm'             => esc_html__( 'Science > Physics', 'sonaar' ),
                'Social Sciences'            => esc_html__( 'Science > Social Sciences', 'sonaar' ),
    
                'Documentary'                => esc_html__( 'Society & Culture > Documentary', 'sonaar' ),
                'Personal Journals'          => esc_html__( 'Society & Culture > Personal Journals', 'sonaar' ),
                'Philosophy'                 => esc_html__( 'Society & Culture > Philosophy', 'sonaar' ),
                'Places & Travel'            => esc_html__( 'Society & Culture > Places & Travel', 'sonaar' ),
                'Relationships'              => esc_html__( 'Society & Culture > Relationships', 'sonaar' ),
    
                'Baseball'                   => esc_html__( 'Sports > Baseball', 'sonaar' ),
                'Basketball'                 => esc_html__( 'Sports > Basketball', 'sonaar' ),
                'Cricket'                    => esc_html__( 'Sports > Cricket', 'sonaar' ),
                'Fantasy Sports'             => esc_html__( 'Sports > Fantasy Sports', 'sonaar' ),
                'Football'                   => esc_html__( 'Sports > Football', 'sonaar' ),
                'Golf'                   	 => esc_html__( 'Sports > Golf', 'sonaar' ),
                'Hockey'                     => esc_html__( 'Sports > Hockey', 'sonaar' ),
                'Rugby'                      => esc_html__( 'Sports > Rugby', 'sonaar' ),
                'Running'                    => esc_html__( 'Sports > Running', 'sonaar' ),
                'Soccer'                     => esc_html__( 'Sports > Soccer', 'sonaar' ),
                'Swimming'                   => esc_html__( 'Sports > Swimming', 'sonaar' ),
                'Tennis'                     => esc_html__( 'Sports > Tennis', 'sonaar' ),
                'Volleyball'                 => esc_html__( 'Sports > Volleyball', 'sonaar' ),
                'Wilderness'                 => esc_html__( 'Sports > Wilderness', 'sonaar' ),
                'Wrestling'                  => esc_html__( 'Sports > Wrestling', 'sonaar' ),
    
                'After Shows'                => esc_html__( 'TV & Film > After Shows', 'sonaar' ),
                'Film History'               => esc_html__( 'TV & Film > Film History', 'sonaar' ),
                'Film Interviews'            => esc_html__( 'TV & Film > Film Interviews', 'sonaar' ),
                'Film Reviews'               => esc_html__( 'TV & Film > Film Reviews', 'sonaar' ),
                'TV Reviews'                 => esc_html__( 'TV & Film > TV Reviews', 'sonaar' ),
    
    
            );
            if ( Sonaar_Music::get_option('player_type', 'srmp3_settings_general') == 'podcast' ){
                $args = array(
                    'id'           => 'srmp3_settings_podcast_tag_tool',
                    'title'        => esc_html__( 'Podcast RSS Tools', 'sonaar-music' ),
                    'object_types' => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
                    'classes'      => array( 'cmb2-options-page', 'srmp3_podcast_rss', 'srmp3_podcast_rss_url' ),
                    'taxonomies'   => array( 'podcast-show' ), // Tells CMB2 which taxonomies should have these fields
                    'new_term_section' => true,
                );
    
                $srmp3_settings_podcast_tag_tool = new_cmb2_box( $args );
                
                $srmp3_settings_podcast_tag_tool->add_field( array(
                    'name'          => esc_html__('RSS Importer', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => sprintf( __( 'Import your existing Podcast Episodes %1$shere.%2$s', 'sonaar-music' ), '<a href="' . esc_url(get_admin_url( null, 'admin.php?import=podcast-rss' )) . '" target="' . wp_strip_all_tags( '_self' ) . '">', '</a>' ),
                    'id'            => 'srpodcast_importer'
                ) );
                if ( !function_exists('run_sonaar_music_pro')){
                    $srmp3_settings_podcast_tag_tool->add_field( array(
                        'name'          => esc_html__('Podcast Fetcher : Automatically import New Episode', 'sonaar-music'),
                        'after'         => 'promo_ad_cb',
                        'classes'       => array('srmp3-pro-feature', 'prolabel--nomargin', 'prolabel--nohide'),
                        'type'          => 'title',
                        'description'   => sprintf( __( 'Give you the ability to automatically fetch/import new episodes on your website from your existing Podcast distributor as soon as a new episode came out! %1$sLearn More%2$s<br><br>', 'sonaar-music' ), '<a href="https://sonaar.io/docs/automatically-fetch-import-new-episodes-from-your-podcast-rss-feed/" target="_blank">', '</a>' ),
                        'id'            => 'srpodcast_importer_cronjob'
                    ) );
                }else{
                    $srmp3_settings_podcast_tag_tool->add_field( array(
                        'name'          => esc_html__('Podcast Fetcher : Automatically import New Episode', 'sonaar-music'),
                        'type'          => 'title',
                        'description'   => sprintf( __( 'Give you the ability to automatically fetch/import new episodes on your website from your existing Podcast distributor as soon as a new episode came out! %1$sLearn More%2$s<br><br>', 'sonaar-music' ), '<a href="https://sonaar.io/docs/automatically-fetch-import-new-episodes-from-your-podcast-rss-feed/" target="_blank">', '</a>' ),
                        'id'            => 'srpodcast_importer_cronjob'
                    ) );
                }
            
            $args = array(
                'id'           => 'srmp3_settings_podcast_rss',
                'menu_title'   => esc_html__( 'Podcast RSS Settings', 'sonaar-music' ),
                'title'        => esc_html__( 'Podcast RSS Settings', 'sonaar-music' ),
                'object_types' => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
                'classes'      => array( 'cmb2-options-page', 'srmp3_podcast_rss' ),
                'taxonomies'   => array( 'podcast-show' ), // Tells CMB2 which taxonomies should have these fields
                'new_term_section' => true,
                'option_key'   => 'srmp3_settings_podcast_rss', // The option key and admin menu page slug. 'yourprefix_tertiary_options',
            );

            $podcast_rss = new_cmb2_box( $args );

            if( isset( $_REQUEST['tag_ID'] ) ){
                $term_id = (int) $_REQUEST['tag_ID'];
                $term = get_term( $term_id, 'podcast-show' );
                $slug = ( isset($term) ) ? $term->slug : '';
                $podcast_cat_id = ( isset($term_id) ) ? $term_id : '';
               //$podcast_original_feed =  ( isset($term_id) && get_term_meta($term_id, 'srpodcast_data_feedurl', true)) ? get_term_meta($term_id, 'srpodcast_data_feedurl', true) : '';
            }else{
                $slug = '';
                $podcast_cat_id = '';
            }
            
            $podcast_rss->add_field( array(
                'name'          => esc_html__('Original RSS Feed URL', 'sonaar-music'),
                'id'            => 'srpodcast_data_feedurl',
                'type'          => 'hidden',
                'description'   => esc_html__('A description/summary of your podcast - no HTML allowed.', 'sonaar-music'),
            ) );

            $sr_disable_rss = (Sonaar_Music::get_option('podcast_setting_rssfeed_disable', 'srmp3_settings_general') === "true") ? true : false;
            if( !$sr_disable_rss ){
                if( Sonaar_Music::get_option('podcast_setting_rssfeed_redirect', 'srmp3_settings_general') === "true" && Sonaar_Music::get_option('podcast_setting_rssfeed_slug', 'srmp3_settings_general') != ''){
                    $podcast_feed_slug = Sonaar_Music::get_option('podcast_setting_rssfeed_slug', 'srmp3_settings_general');
                }else{
                    $podcast_feed_slug = 'podcast'; //default
                }
                $podcast_rss->add_field( array(
                    'name'          => esc_html__('RSS Feed Settings', 'sonaar-music'),
                    'type'          => 'title',
                    'id'            => 'srpodcast_rss_feed_settings',
                    'description'   => sprintf( __( '
                    Your Podcast Show ID is %4$s<br>
                    Your WordPress RSS Feed URL is %1$s %3$s %2$s<br><br>
                    An RSS feed is the only way an audience can access a podcast\'s content. Without an RSS feed, your podcast will not appear on your website or any podcasting directories, making it impossible for people to listen to it. Every podcast needs an RSS feed, there are not any exceptions.', 'sonaar-music' ),
                    '<a href="' . esc_url(get_site_url( null, '/feed/'. $podcast_feed_slug .'/?show=' . $slug )) . '" target="' . wp_strip_all_tags( '_blank' ) . '">',
                    '</a>',
                    esc_url(get_site_url( null, '/feed/'. $podcast_feed_slug .'/?show=' . $slug )), $podcast_cat_id),
                ) );

            }else{

                $podcast_rss->add_field( array(
                    'name'          => esc_html__('RSS Feed Settings', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   =>  __( 'Your RSS Feed is <strong>currently disabled</strong>. Go to WP-Admin > MP3 Player > Settings to enable it', 'sonaar-music'),
                    'id'            => 'srpodcast_rss_feed_settings',
                ));

            }
           
            $podcast_rss->add_field( array(
                'name'          => esc_html__('Podcast Title', 'sonaar-music'),
                'id'            => 'srpodcast_data_title',
                'type'          => 'text',
            ) );
            $podcast_rss->add_field( array(
                'name'          => esc_html__('Podcast Subtitle', 'sonaar-music'),
                'id'            => 'srpodcast_data_subtitle',
                'type'          => 'text',
            ) );
            $podcast_rss->add_field( array(
                'name'          => esc_html__('Podcast Description', 'sonaar-music'),
                'id'            => 'srpodcast_data_description',
                'type'          => 'textarea',
                'description'   => esc_html__('A description/summary of your podcast - no HTML allowed.', 'sonaar-music'),
            ) );
            $podcast_rss->add_field( array(
                'name'              =>  esc_html__('Podcast Show Cover Image', 'sonaar-music'),
                'id'                => 'srpodcast_data_image',
                'type'              => 'file',
                'text'              => array(
                    'add_upload_file_text' => 'Add Image' // Change upload button text. Default: "Add or Upload File"
                ),
                'preview_size' => array( 120, 120 ),  // Image size to use when previewing in the admin.
                'options' => array(
                    'url' => false, // Hide the text input for the url
                ),
                // query_args are passed to wp.media's library query.
                'query_args'        => array(
                    // Or only allow gif, jpg, or png images
                    'type'  => array(
                         'image/gif',
                         'image/jpeg',
                         'image/png',
                    ),
                ),
            ) );
            $podcast_rss->add_field( array(
                'name'          => esc_html__('Podcast Author Name', 'sonaar-music'),
                'id'            => 'srpodcast_data_author',
                'type'          => 'text',
            ) );
            $podcast_rss->add_field( array(
                'name'          => esc_html__('Podcast Owner Name', 'sonaar-music'),
                'id'            => 'srpodcast_data_owner_name',
                'type'          => 'text',
            ) );
            $podcast_rss->add_field( array(
                'name'          => esc_html__('Podcast Author Email', 'sonaar-music'),
                'id'            => 'srpodcast_data_owner_email',
                'type'          => 'text_email',
            ) );
            $podcast_rss->add_field( array(
                'name'          => esc_html__('Podcast Language', 'sonaar-music'),
                'id'            => 'srpodcast_data_language',
                'type'          => 'text_small',
                'description'   => sprintf( __( 'Your podcast\'s language in %1$sISO-639-1 format%2$s.', 'sonaar' ), '<a href="' . esc_url( 'http://www.loc.gov/standards/iso639-2/php/code_list.php' ) . '" target="' . wp_strip_all_tags( '_blank' ) . '">', '</a>' ),
                'default'       => get_bloginfo ( 'language' )
            ) );
            $podcast_rss->add_field( array(
                'name'          => esc_html__('Podcast Copyright', 'sonaar-music'),
                'id'            => 'srpodcast_data_copyright',
                'type'          => 'text',
                'default'       => esc_html(get_bloginfo( 'name' )) . ' &#xA9; ' . esc_html(date( 'Y' )) . ' - All Rights Reserved.' ,
            ) );
            $podcast_rss->add_field( array(
                'id'                => 'srpodcast_data_category',
                'type'              => 'select',
                'name'              => esc_html('Podcast Catergory', 'sonaar-music'),
                'show_option_none'  => false,
                'options'           => $category_options,
            ) );
            $podcast_rss->add_field( array(
                'id'                => 'srpodcast_data_subcategory',
                'type'              => 'select',
                'name'              => esc_html('Podcast subcategory (Optional)', 'sonaar-music'),
                'show_option_none'  => false,
                'options'           => $subcategory_options,
                'description'   => esc_html__('Attention! Make sure you choose a subcategory that belong to the choosen Category above otherwise Apple will reject it. ', 'sonaar-music'),
            ) );
            $podcast_rss->add_field( array(
                'name'          => esc_html__('Is your podcast explicit?', 'sonaar-music'),                
                'id'            => 'srpodcast_explicit',
                'type'          => 'switch',
                'label'    => array('on'=> 'Yes', 'off'=> 'No')
            ) );
            $podcast_rss->add_field( array(
                'name'          => esc_html__('Is your podcast complete?', 'sonaar-music'),                
                'description'   => esc_html__('Mark if this podcast is complete or not. Only do this if no more episodes are going to be added to this feed.', 'sonaar-music'),
                'id'            => 'srpodcast_complete',
                'type'          => 'switch',
                'label'    => array('on'=> 'Yes', 'off'=> 'No')
            ) );
            $podcast_rss->add_field( array(
                'id'                => 'srpodcast_consume_order',
                'type'              => 'select',
                'name'              => esc_html('Show Type', 'sonaar-music'),
                'show_option_none'  => false,
                'options'     => array(
                    'episodic' => esc_html__( 'Episodic', 'sonaar-music' ),
                    'serial'   => esc_html__( 'Serial', 'sonaar-music' )
                ),
                'description'   => sprintf( __( 'The order your podcast episodes will be listed. %1$sMore details here.%2$s', 'sonaar-music' ), '<a href="' . esc_url( 'https://www.google.com/search?q=apple+podcast+episodes+serial+vs+episodic' ) . '" target="' . wp_strip_all_tags( '_blank' ) . '">', '</a>' ),
            ) );
            $podcast_rss->add_field( array(
                'name'          => esc_html__('Redirect this feed to a new URL', 'sonaar-music'),
                'description'   => esc_html__('Redirect your feed to a new URL (specified below).', 'sonaar-music'),
                'id'            => 'srpodcast_redirect_feed',
                'type'          => 'switch',
                'label'    => array('on'=> 'Yes', 'off'=> 'No')
            ) );
            $podcast_rss->add_field( array(
                'name'          => esc_html__('Podcast feed URL redirection', 'sonaar-music'),
                'id'            => 'srpodcast_new_feed_url',
                'type'          => 'text_url',
                'attributes'    => array(
                    'required'               => false, // Will be required only if visible.
                    'data-conditional-id'    => 'srpodcast_redirect_feed',
                    'data-conditional-value' => 'true',
                )
            ) );

            $args = array(
                'id'           => 'srmp3_settings_podcast_rss_url',
                //'menu_title'   => esc_html__( 'Podcast RSS Settings', 'sonaar-music' ),
                'title'        => esc_html__( 'Podcast RSS Settings', 'sonaar-music' ),
                'object_types' => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
                'classes'      => array( 'cmb2-options-page', 'srmp3_podcast_rss', 'srmp3_podcast_rss_url' ),
                'taxonomies'   => array( 'podcast-show' ), // Tells CMB2 which taxonomies should have these fields
                'new_term_section' => true,
                //'option_key'   => 'srmp3_settings_podcast_rss_url', // The option key and admin menu page slug. 'yourprefix_tertiary_options',
            );

            $podcast_rss_url = new_cmb2_box( $args );

            $links_group = $podcast_rss_url->add_field( array(
                'id'            => 'podcast_rss_url',            
                'type'          => 'group',
                'description'   => esc_html__('Where your listeners can subscribe? Will display a follow/subscribe button on the player. You can reorder the buttons by using the up/down arrows. ', 'sonaar-music'),
                'name' 			=> 'Subscribe Button Links',
                'classes'       => 'srpodcast_url_group',
                'repeatable'    => true, // use false if you want non-repeatable group
                'options'       => array(
                    'add_button'    => esc_html__('Add Subscribe Button', 'sonaar-music'),
                    'remove_button' => esc_html__('Remove Button', 'sonaar-music'),
                    'sortable'      => true, // beta
                    'closed'        => false, // true to have the groups closed by default
                ),
            ) );
            $podcast_rss_url->add_group_field( $links_group ,array(
                'name'          => esc_html__( 'Icon', 'sonaar-music' ),
                'classes'       => 'srpodcast_url_icon',
                'id'            => 'srpodcast_url_icon',
                'type'          => 'faiconselect',
                'options_cb'    => 'srmp3_returnRayFaPre'
            ) );
            /*$podcast_rss_url->add_group_field( $links_group ,array(
                'name'              => esc_html__( 'Podcast Platform', 'sonaar-music' ),
                'id'                => 'srpodcast_url_icon',
                'classes'       => 'srpodcast_url_icon',
                'type'              => 'select',
                'show_option_none'  => true,
                'options' => $this->getPodcastPlatforms(),
                'default'           => '',
            ));*/
            $podcast_rss_url->add_group_field( $links_group ,array(
                'name'          => esc_html__('Label', 'sonaar-music'),
                'id'            => 'srpodcast_name',
                'type'          => 'text_medium',
                'classes'       => 'srpodcast_url_link',
                //'default'       => $this->getPodcastPlatforms(),
            ));    
            $podcast_rss_url->add_group_field( $links_group ,array(
                'name'          => esc_html__('Link URL', 'sonaar-music'),
                'id'            => 'srpodcast_url',
                'type'          => 'text_url',
                'classes'       => 'srpodcast_url_link',
            ));  
        }
        // migrate and convert iron_music_player key in separate setting tabs since version 3.0
        if( FALSE != get_option('iron_music_player') && !get_option('srmp3_settings_general') ){
            foreach($options_name as $option_name){
            $options_key_name = $option_name->meta_box['option_key'][0];
                if (FALSE === get_option($options_key_name) && FALSE === update_option($options_key_name, FALSE)){
                    foreach ($option_name->meta_box['fields'] as $field ) {
                        $getKey = ( isset(get_option('iron_music_player')[$field['id']]) ) ? get_option('iron_music_player')[$field['id']] : '';
                        if( $getKey != '' ){
                            cmb2_update_option($options_key_name, $field['id'], $getKey );
                        }
                    }
                }
            }
        }
        /**
         * A CMB2 options-page display callback override which adds tab navigation among
         * CMB2 options pages which share this same display callback.
         *
         * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
         */
        function yourprefix_options_display_with_tabs( $cmb_options ) {            
            
            $tabs = yourprefix_options_page_tabs( $cmb_options );
            ?>
            <div class="wrap cmb2-options-page option-<?php echo esc_attr($cmb_options->option_key); ?>">
                <?php if ( get_admin_page_title() ) : ?>
                    <h2><?php echo wp_kses_post( get_admin_page_title() ); ?></h2>
                <?php endif; ?>
                <h2 class="nav-tab-wrapper">
                    <?php foreach ( $tabs as $option_key => $tab_title ) : ?>
                        <a class="nav-tab<?php if ( isset( $_GET['page'] ) && $option_key === $_GET['page'] ) : ?> nav-tab-active<?php endif; ?>" href="<?php menu_page_url( $option_key ); ?>"><?php echo wp_kses_post( $tab_title ); ?></a>
                    <?php endforeach; ?>
                </h2>
                <form class="cmb-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" id="<?php echo esc_attr($cmb_options->cmb->cmb_id); ?>" enctype="multipart/form-data" encoding="multipart/form-data">
                    <input type="hidden" name="action" value="<?php echo esc_attr( $cmb_options->option_key ); ?>">
                    <?php $cmb_options->options_page_metabox(); ?>
                    <?php submit_button( esc_attr( $cmb_options->cmb->prop( 'save_button' ) ), 'primary', 'submit-cmb' ); ?>
                </form>
            </div>
            <?php
        }

        /**
         * Gets navigation tabs array for CMB2 options pages which share the given
         * display_cb param.
         *
         * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
         *
         * @return array Array of tab information.
         */
        function yourprefix_options_page_tabs( $cmb_options ) {
            $tab_group = $cmb_options->cmb->prop( 'tab_group' );
            $tabs      = array();

            foreach ( CMB2_Boxes::get_all() as $cmb_id => $cmb ) {
                if ( $tab_group === $cmb->prop( 'tab_group' ) ) {
                    $tabs[ $cmb->options_page_keys()[0] ] = $cmb->prop( 'tab_title' )
                        ? $cmb->prop( 'tab_title' )
                        : $cmb->prop( 'title' );
                }
            }

            return $tabs;
        }
        

        $cmb_options = new_cmb2_box( array(
            'id'            => 'sonaar_music_network_option_metabox',
            'title'         => esc_html__( 'Sonaar Music', 'sonaar-music' ),
            'object_types'  => array( 'options-page' ),
            'option_key'    => 'iron_music_player', // The option key and admin menu page slug.
            'icon_url'      => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
            'menu_title'    => esc_html__( 'Settings', 'sonaar-music' ), // Falls back to 'title' (above).
            'parent_slug'   => 'edit.php?post_type=' . SR_PLAYLIST_CPT, // Make options page a submenu item of the themes menu.
            'capability'    => 'manage_options', // Cap required to view options-page.
            'position'      => 1,
        ) );

        function srmp3_import_options( $val ) {
            $val[] = array (
                'General Settings'          => 'srmp3_settings_general',
                'Widget Player Settings'    => 'srmp3_settings_widget_player',
                'Sticky Player Settings'    => 'srmp3_settings_sticky_player',
                'Audio Preview Settings'    => 'srmp3_settings_audiopreview',
                'WooCommerce Settings'      => 'srmp3_settings_woocommerce',
                'Popup Settings'            => 'srmp3_settings_popup',
                'Stats Settings'            => 'srmp3_settings_stats',
                'Favorites Settings'        => 'srmp3_settings_favorites',
                'Share Settings'            => 'srmp3_settings_share',
            );
            return $val;
        }
        add_filter( 'srmp3_export_options', 'srmp3_import_options' ); 
        
        /**
        * Manually render a field.
        *
        * @param  array      $field_args Array of field arguments.
        * @param  CMB2_Field $field      The field object
        */
        function banner_row( $field_args, $field ) {
            require_once plugin_dir_path( __FILE__ ) . 'partials/sonaar-music-admin-display.php';
        }
    }

    /**
    * Register post fields
    **/
    public static function getPodcastPlatforms() {
        return array(
            'sricon-apple-podcasts'         => esc_html__( 'Apple Podcasts', 'sonaar-music' ),
            'sricon-spotify'                => esc_html__( 'Spotify', 'sonaar-music' ),
            'sricon-google-podcast'         => esc_html__( 'Google Podcasts', 'sonaar-music' ),
            'sricon-amazonmusic'            => esc_html__( 'Amazon Music', 'sonaar-music' ),
            'sricon-castbox'                => esc_html__( 'Castbox', 'sonaar-music' ),
            'sricon-castro'                 => esc_html__( 'Castro', 'sonaar-music' ),
            'sricon-deezer'                 => esc_html__( 'Deezer', 'sonaar-music' ),
            'sricon-iheartradio'            => esc_html__( 'iHeart Radio', 'sonaar-music' ),
            'sricon-overcast'               => esc_html__( 'Overcast', 'sonaar-music' ),
            'sricon-pandora'                => esc_html__( 'Pandora', 'sonaar-music' ),
            'sricon-playerfm'               => esc_html__( 'Player FM', 'sonaar-music' ),
            'sricon-pocketcasts'            => esc_html__( 'Pocket Casts', 'sonaar-music' ),
            'sricon-podcastaddict'          => esc_html__( 'Podcast Addict', 'sonaar-music' ),
            'sricon-podcastindex'           => esc_html__( 'Podcast Index', 'sonaar-music' ),
            'sricon-podchaser'              => esc_html__( 'Podchaser', 'sonaar-music' ),
            'sricon-stitcher'               => esc_html__( 'Stitcher', 'sonaar-music' ),
            'sricon-tunein'                 => esc_html__( 'TuneIn', 'sonaar-music' ),
            'sricon-rss-feed'               => esc_html__( 'RSS Feed', 'sonaar-music' ),
        );
    }
    
    
    public static function sr_GetString($string)
    {
        $playerType = Sonaar_Music::get_option('player_type', 'srmp3_settings_general');
    
        $labels = [
            'Album Infos' => [
                'classic' => __('Playlist Content', 'sonaar-music'),
                'podcast' => __('Episode Content', 'sonaar-music'),
                'radio'   => __('Station Content', 'sonaar-music'),
            ],
            'Track Title' => [
                'classic'   => __('Track Title', 'sonaar-music'),
                'podcast' => __('Episode Title', 'sonaar-music'),
                'radio'   => __('Default Track Title', 'sonaar-music'),
            ],
            'Add Title' => [
                'classic'   => __('Add Playlist or Track Title', 'sonaar-music'),
                'podcast' => __('Add Podcast or Episode Title', 'sonaar-music'),
                'radio'   => __('Add Title', 'sonaar-music'), 
            ],
            'Track Album' => [
                'classic'   => __('Track Album', 'sonaar-music'),
                'podcast' => __('Podcast Name', 'sonaar-music'),
                'radio'   => __('Station Name', 'sonaar-music'),
            ],
            'Artist Name' => [
                'classic'   => __('Artist Name', 'sonaar-music'),
                'podcast' => __('Speaker Name', 'sonaar-music'),
                'radio'   => __('Radio Host Name', 'sonaar-music'),
            ],
            'Album Subtitle' => [
                'classic'   => __('Playlist Subtitle', 'sonaar-music'),
                'podcast' => __('Episode Subtitle', 'sonaar-music'),
                'radio'   => __('Station Subtitle', 'sonaar-music'),
            ],
            'Do not skip to the next track' => [
                'classic'   => __('Do not skip to the next track', 'sonaar-music'),
                'podcast' => __('Do not skip to the next episode', 'sonaar-music'),
                'radio'   => __('Do not skip to the next station', 'sonaar-music'),
            ],
            'Track {#}' => [
                'classic'   => __('Track {#}', 'sonaar-music'),
                'podcast' => __('Episode {#}', 'sonaar-music'),
                'radio'   => __('Station {#}', 'sonaar-music'),
            ],
            'Add Another track' => [
                'classic'   => __('Add Another Track', 'sonaar-music'),
                'podcast' => __('Add Another Episode', 'sonaar-music'),
                'radio'   => __('Add Another Station', 'sonaar-music'),
            ],
            'Remove Track' => [
                'classic'   => __('Remove Track', 'sonaar-music'),
                'podcast' => __('Remove Episode', 'sonaar-music'),
                'radio'   => __('Remove Station', 'sonaar-music'),
            ],
            'Optional Track Image' => [
                'classic'   => __('Optional Track Image', 'sonaar-music'),
                'podcast' => __('Optional Episode Cover', 'sonaar-music'),
                'radio'   => __('Optional Station Image', 'sonaar-music'),
            ],
            'Playlist Cover Image' => [
                'classic'   => __('Playlist Cover Image', 'sonaar-music'),
                'podcast' => __('Podcast Cover Image', 'sonaar-music'),
                'radio'   => __('Station Cover Image', 'sonaar-music'),
            ],
            'Remove Playlist Cover' => [
                'classic'   => __('Remove Playlist Cover', 'sonaar-music'),
                'podcast' => __('Remove Podcast Cover', 'sonaar-music'),
                'radio'   => __('Remove Station Cover', 'sonaar-music'),
            ],
            'All Playlists' => [
                'classic'   => __('All Playlists & Tracks', 'sonaar-music'),
                'podcast' => __('All Episodes', 'sonaar-music'),
                'radio'   => __('All Stations', 'sonaar-music'),
            ],
            'Playlists' => [
                'classic'   => __('Tracks', 'sonaar-music'),
                'podcast' => __('Episodes', 'sonaar-music'),
                'radio'   => __('Stations', 'sonaar-music'),
            ],
            'Playlist' => [
                'classic'   => __('Tracks', 'sonaar-music'),
                'podcast' => __('Episode', 'sonaar-music'),
                'radio'   => __('Station', 'sonaar-music'),
            ],
            'playlist' => [
                'classic'   => __('playlist', 'sonaar-music'),
                'podcast' => __('episode', 'sonaar-music'),
                'radio'   => __('station', 'sonaar-music'),
            ],
            'Tracklist' => [
                'classic'   => __('Tracklist', 'sonaar-music'),
                'podcast' => __('Episodes List', 'sonaar-music'),
                'radio'   => __('Station List', 'sonaar-music'),
            ],
            'Add New Playlist' => [
                'classic'   => __('Add New Playlist', 'sonaar-music'),
                'podcast' => __('Add New Episode', 'sonaar-music'),
                'radio'   => __('Add New Station', 'sonaar-music'),
            ],        
            'Edit Playlist' => [
                'classic'   => __('Edit Playlist', 'sonaar-music'),
                'podcast' => __('Edit Episode', 'sonaar-music'),
                'radio'   => __('Edit Station', 'sonaar-music'),
            ],
            'album_slug' => [
                'classic'   => __('album', 'sonaar-music'),
                'podcast' => __('episode', 'sonaar-music'),
                'radio'   => __('station', 'sonaar-music'),
            ],
            'category_slug' => [
                'classic'   => __('playlist-category', 'sonaar-music'),
                'podcast' => __('podcast-category', 'sonaar-music'),
                'radio'   => __('station-category', 'sonaar-music'),
            ],
            'tag_slug' => [
                'classic'   => __('playlist-tag', 'sonaar-music'),
                'podcast' => __('podcast-tag', 'sonaar-music'),
                'radio'   => __('station-tag', 'sonaar-music'),
            ],
            'Display Artist Name' => [
                'classic'   => __('Display Artist Name', 'sonaar-music'),
                'podcast' => __('Display Author Name', 'sonaar-music'),
                'radio'   => __('Display Host Name', 'sonaar-music'),
            ],
            'Artist Name Prefix Separator' => [
                'classic'   => __('Artist Name Prefix Separator', 'sonaar-music'),
                'podcast' => __('Author Name Prefix Separator', 'sonaar-music'),
                'radio'   => __('Host Name Prefix Separator', 'sonaar-music'),
            ],
            'Album Title' => [
                'classic'   => __('Album & Track Titles', 'sonaar-music'),
                'podcast' => __('Episode & Podcast Titles', 'sonaar-music'),
                'radio'   => __('Track Titles', 'sonaar-music'),
            ],
            'Album Subtitle 2' => [
                'classic'   => __('Album Subtitle', 'sonaar-music'),
                'podcast' => __('Player Subtitle', 'sonaar-music'),
                'radio'   => __('Station Subtitle', 'sonaar-music'),
            ],
            'Album cover size image source' => [
                'classic'   => __('Album cover size image source', 'sonaar-music'),
                'podcast' => __('Episode cover size image source', 'sonaar-music'),
                'radio'   => __('Station cover size image source', 'sonaar-music'),
            ],
            'Optional Track Information' => [
                'classic'   => __('Optional Track Information', 'sonaar-music'),
                'podcast' => __('Episode Description/Notes', 'sonaar-music'),
                'radio'   => __('Optional Station Description', 'sonaar-music'),
            ],
            'track' => [
                'classic'   => __('track', 'sonaar-music'),
                'podcast' => __('episode', 'sonaar-music'),
                'radio'   => __('track', 'sonaar-music'),
            ],
            'playlist/podcast' => [
                'classic'   => __('playlist', 'sonaar-music'),
                'podcast' => __('podcast', 'sonaar-music'),
                'radio'   => __('station', 'sonaar-music'),
            ],
            'album/podcast' => [
                'classic'   => __('album', 'sonaar-music'),
                'podcast' => __('podcast', 'sonaar-music'),
                'radio'   => __('show', 'sonaar-music'),
            ],     
        ];

        $label = $labels[$string][$playerType] ?? $string;
    
        return esc_html($label);
    }

    public function init_postField(){
        function sr_check_if_wc(){
            if (get_post_type() == 'product'){
                return true;
            }
            return false;
        }
        function sr_check_if_sr_posttype(){
            if (get_post_type() == SR_PLAYLIST_CPT){
                return true;
            }
            return false;
        }
        function srmp3_sanitize_trackdescription( $value, $field_args, $field ) {

            /*
             * Do your custom sanitization. 
             * strip_tags can allow whitelisted tags
             * http://php.net/manual/en/function.strip-tags.php
             */
            $value = strip_tags($value, '<p><a><strong><em><u><s><blockquote><ul><ol><li><h1><h2><h3><h4><h5><h6><br><hr><img><pre>');
            return $value;
        }
        function srmp3_sanitize_lyrics( $value, $field_args, $field ) {

            /*
             * Do your custom sanitization. 
             * strip_tags can allow whitelisted tags
             * http://php.net/manual/en/function.strip-tags.php
             */
            $value = strip_tags( $value, '<p><a><br><br/>' );
        
            return $value;
        }
        function sr_admin_column_count( $field_args, $field) {
            global $post;
            $list = get_post_meta($post->ID, $field_args['id'], true);
            
            if(!is_array($list) || empty($list)){
                return esc_html__('N/A', 'sonaar-music'); 
            }

            return count($list);
        }

        if ( function_exists( 'run_sonaar_music_pro' ) ){
            $cmb_post_album = new_cmb2_box( array(
                'id'            => 'acf_post_albums',
                'title'         => 'Player Settings',//$this->sr_GetString('Album Infos'),
                'object_types'  => SR_PLAYLIST_CPT,
                'context'       => 'normal',
                'priority'      => 'low',
                'show_names'    => true,
                'capability'    => 'manage_options', // Cap required to view options-page.
            ) );
        
            $cmb_post_album->add_field( array(
                'name'          => esc_html__('NEW! Player Design', 'sonaar-music'),
                'id'            => 'post_player_type',
                'type'          => 'select',
                'options'       => array(
                    'default'    => esc_html__('Default (Same as the General Settings)', 'sonaar-music'),
                    'skin_float_tracklist'         => esc_html__('Floated', 'sonaar-music'),
                    'skin_boxed_tracklist'    => esc_html__('Boxed', 'sonaar-music'),
                ),
                'default'       => 'default'
            ) );
        }
       
        /////////////////////////////////
        $cmb_album = new_cmb2_box( array(
            'id'            => 'acf_albums_infos',
            'title'         => $this->sr_GetString('Album Infos'),
            'object_types'  => ( Sonaar_Music::get_option('srmp3_posttypes', 'srmp3_settings_general') != null ) ? Sonaar_Music::get_option('srmp3_posttypes', 'srmp3_settings_general') : SR_PLAYLIST_CPT,
            'context'       => 'normal',
            'priority'      => 'low',
            'show_names'    => true,
            'capability'    => 'manage_options', // Cap required to view options-page.
        ) );
        $cmb_album->add_field( array(
            'classes'       => 'srmp3-cmb2-row-mini',
            'id'            => 'alb_release_date',
            'name'          => $this->sr_GetString('Album Subtitle'),
            'description'   => esc_html__('E.g. New Album, Coming Soon, Sold Out, Release Date: August 28th, etc. ', 'sonaar-music'),
            'type'          => 'text'
        ) );

        if ( !function_exists( 'run_sonaar_music_pro' ) ){
            $cmb_album->add_field( array(
                'show_on_cb'    => 'sr_check_if_wc',
                'id'            => 'promo_wc_add_to_cart',
                'before_field'  => 'promo_ad_cb',
                'classes'       => 'srmp3-pro-feature srmp3-cmb2-row-mini',
                'object_types'  => 'product',
                'default'       => 'true',
                'name'          => esc_html__('Add to Cart button', 'sonaar-music'),
                'after'         => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => esc_html__('Add to Cart button link', 'sonaar-music'),
                    'text'      => sprintf( __('Display an Add to Cart button in the %s', 'sonaar-music'), (function_exists( 'run_sonaar_music_pro' )) ? 'Sticky Player and in the "Available On" module.' : '"Available On" module.' ),
                    'pro'       => true,
                ),
                'type'          => 'switch'            
                
            ));
            $cmb_album->add_field( array(
                'show_on_cb'    => 'sr_check_if_wc',
                'id'            => 'promo_wc_buynow_bt',
                'before_field'  => 'promo_ad_cb',
                'classes'       => 'srmp3-pro-feature srmp3-cmb2-row-mini',
                'object_types'  => 'product',
                'default'       => 'false',
                'name'          => esc_html__('Buy Now button', 'sonaar-music'),
                'after'         => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => esc_html__('Buy Now button link', 'sonaar-music'),
                    'text'      => sprintf( __('Display a Buy Now button in the %s', 'sonaar-music'), (function_exists( 'run_sonaar_music_pro' )) ? 'Sticky Player and in the "Available On" module.' : '"Available On" module.' ),
                    'pro'       => true,
                ),
                'type'          => 'switch'
                
            ));
        }else{
            $cmb_album->add_field( array(
                'classes'       => 'srmp3-cmb2-row-mini',
                'show_on_cb'    => 'sr_check_if_wc',
                'id'            => 'wc_add_to_cart',
                'object_types'  => 'product',
                'default'       => 'true',
                'name'          => esc_html__('Add to Cart button', 'sonaar-music'),
                'after'         => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => esc_html__('Add to Cart button link', 'sonaar-music'),
                    'text'      => sprintf( __('Display an Add to Cart button in the %s', 'sonaar-music'), (function_exists( 'run_sonaar_music_pro' )) ? 'Sticky Player and in the "Available On" module.' : '"Available On" module.' ),
                    'pro'       => true,
                ),
                'type'          => 'switch'            
                
            ));
            $cmb_album->add_field( array(
                'classes'       => 'srmp3-cmb2-row-mini',
                'show_on_cb'    => 'sr_check_if_wc',
                'id'            => 'wc_buynow_bt',
                'object_types'  => 'product',
                'default'       => 'false',
                'name'          => esc_html__('Buy Now button', 'sonaar-music'),
                'after'         => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => esc_html__('Buy Now button link', 'sonaar-music'),
                    'text'      => sprintf( __('Display a Buy Now button in the %s', 'sonaar-music'), (function_exists( 'run_sonaar_music_pro' )) ? 'Sticky Player and in the "Available On" module.' : '"Available On" module.' ),
                    'pro'       => true,
                ),
                'type'          => 'switch'
                
            ));
        }
        
        if ( function_exists( 'run_sonaar_music_pro' ) ){            
            $cmb_album->add_field( array(
                'classes'       => 'srmp3-cmb2-row-mini',
                'id'            => 'no_loop_tracklist',
                'name'          => sprintf( esc_html__('Do not loop %s', 'sonaar-music'), strtolower($this->sr_GetString('Tracklist'))),
                'description'   => sprintf( esc_html__('When %s ends, do not loop to first %s automatically.', 'sonaar-music'), strtolower($this->sr_GetString('Tracklist')), $this->sr_GetString('track')),
                'type'          => 'checkbox'
            ));
        }

        if ( function_exists( 'run_sonaar_music_pro' ) ){            
            $cmb_album->add_field( array(
                'classes'       => 'srmp3-cmb2-row-mini',
                'id'            => 'no_track_skip',
                'name'          => $this->sr_GetString('Do not skip to the next track'),
                'description'   => sprintf( esc_html__('When the current %s ends, do not play the second %s automatically.', 'sonaar-music'), $this->sr_GetString('track'), $this->sr_GetString('track')),
                'type'          => 'checkbox'
            ));
        }

        $cmb_album->add_field( array(
            'classes'       => 'srmp3-cmb2-row-mini',
            'id'            => 'reverse_post_tracklist',
            'name'          => esc_html__('Reverse Order', 'sonaar-music'),
            'description'   => 'Display tracklist in reverse order on the front-end',
            'type'          => 'checkbox'
        ));
        if ( function_exists( 'run_sonaar_music_pro' ) && get_site_option('SRMP3_ecommerce') == '1' && Sonaar_Music::get_option('force_audio_preview', 'srmp3_settings_audiopreview') === 'true' ){
            $cmb_album->add_field( array(
                'name'          => esc_html__('Audio Preview', 'sonaar-music'),
                'id'            => 'post_audiopreview_all',
                'custom_class'  => 'srmp3-cmb2-row-mini',
                'classes_cb'    => 'audiopreview_controller_classes',
                'type'          => 'select',
                'label_cb'      => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => '',
                    'text'      => __('Also known as an <em>"audio snippet"</em> or <em>"sample,"</em> it is a brief segment from a longer audio recording. It provides listeners a glimpse of the full content without playing the entire track.<br><br>
                    Used predominantly in <strong>online music stores</strong> and <strong>audiobook platforms</strong>, these short clips, lasting from a few seconds to minutes, assist potential buyers in their purchasing decisions.<br><br>
                    They also serve as a protective measure against <strong>unauthorized downloads</strong> by only showcasing a segment. Some previews might even include voiceovers or watermarks like <strong>"sample"</strong> to deter misuse.<br><br>
                    When a user <strong>(with restricted role)</strong> visit your website, it\'s this audio snippet they hear or download. You can set the restricted role in WP-Admin > MP3 Player > Settings<br><br>You can also disable this audio preview to allow everyone to listen and download your full track.<br><br> Remember: If you do not set audio preview, it\'s OK! In this case, the full track will be available.', 'sonaar-music'),
                    'image'     => '',
                ),
                'options'       => array(
                   // 'default'   => esc_html__('Default (Use options in the General Settings)', 'sonaar-music'),
                    'enabled'      => esc_html__('Enable', 'sonaar-music'),
                    'disabled'     => esc_html__('Disable', 'sonaar-music'),
                ),
                'default'       => 'true'
            ) );
            $cmb_album->add_field( array(
                'classes'       => 'ffmpeg_field srmp3-settings-generate-bt-container srmp3-cmb2-row-mini',
                'name'          => '_',
                'description'   => __('
                <button id="srmp3_index_audio_preview" class="srmp3-generate-bt srmp3-post-all-audiopreview-bt showSpinner">Generate Preview Files for all tracks below</button> 
                <span id="srmp3_indexTracks_status"></span><progress id="indexationProgress" style="width:100%;margin-top:10px;display:none;" value="0" max="100"></progress>
                <span id="progressText"></span>
                ','sonaar-music'),
                'id'            => 'srmp3-settings-generate-bt-container',
                'type'          => 'text_small',
                'attributes'        => array(
                    'data-conditional-id'    => 'post_audiopreview_all',
                    'data-conditional-value' => 'enabled',
                ),
                
            ) );
        }
        $cmb_album->add_field( array(
            'classes'       => 'srmp3-cmb2-row-mini',
            'name'          => __('Tracklist Source','sonaar-music'),
            'id'            => 'post_playlist_source',
            'type'          => 'select',
            'options'       => array(
                'default'   => esc_html__('Default', 'sonaar-music'),
                'rss'       => esc_html__('Podcast RSS Feed', 'sonaar-music'),
                'csv'      => esc_html__('CSV File', 'sonaar-music'),
            ),
            'default'       => 'default'
        ) );
        $cmb_album->add_field( array(
            'classes_cb'    => 'remove_pro_label_if_pro',
            'custom_class'  => 'srmp3-cmb2-row-mini',
            'before'        => 'promo_ad_cb',
            'id'            => 'playlist_csv_file',
            'name'          => esc_html__('Tracklist CSV File','sonaar-music'),
            'type'          => 'file',
            'description' => sprintf(
                esc_html__('Example of CSV File format %1s.', 'sonaar-music'),
                '<a href="' . esc_url( plugin_dir_url(dirname(__FILE__)) ) . 'templates/example_of_csv_file_to_import.csv' . '" target="_blank">' . esc_html__('here', 'sonaar-music') . '</a>'
            ),
            'query_args'    => array(
                'type'          => array( 'text/csv', 'application/vnd.ms-excel' ),
            ),
            'options' => array(
                'url' => true, // Hide the text input for the url
            ),
            'attributes'        => array(
                'data-conditional-id'    => 'post_playlist_source',
                'data-conditional-value' => 'csv',
            ),
        ));
        $cmb_album->add_field( array(
            'classes'       => 'srmp3-cmb2-row-mini',
            'id'            => 'playlist_rss',
            'name'          => esc_html__('RSS Feed URL','sonaar-music'),
            'description' => sprintf(
                esc_html__('Make sure you have a valid RSS Feed. You can validate it %1s.', 'sonaar-music'),
                '<a href="https://podba.se/validate/' . '" target="_blank">' . esc_html__('here', 'sonaar-music') . '</a>'
            ),
            'type'          => 'text_url',
            'attributes'        => array(
                'data-conditional-id'    => 'post_playlist_source',
                'data-conditional-value' => 'rss',
            ),
        ));
        
        $album_collapse = (Sonaar_Music::get_option('collapse_tracklist_backend', 'srmp3_settings_general') != null && Sonaar_Music::get_option('collapse_tracklist_backend', 'srmp3_settings_general') == "true") ? true : false;
        $tracklist = $cmb_album->add_field( array(
            'id'            => 'alb_tracklist',         
            'type'          => 'group',
            'name' 			=> $this->sr_GetString('Tracklist'),
            'repeatable'    => true, // use false if you want non-repeatable group
            'options'       => array(
                'group_title'   => $this->sr_GetString('Track {#}'),//__( 'Track {#}', 'sonaar-music' ), // since version 1.1.4, {#} gets replaced by row number
                'add_button'    => $this->sr_GetString('Add Another track'),
                'remove_button' => $this->sr_GetString('Remove Track'),
                'sortable'      => true, // beta
                'closed'        => $album_collapse, // true to have the groups closed by default
            ),
            'column' => array(
                'name'     => esc_html__( 'Audio Tracks', 'sonaar-music' ),
            ),
            'display_cb'    => 'sr_admin_column_count',
        ) );
        $cmb_album->add_group_field( $tracklist ,array(
            'name'              => esc_html__('Source File', 'sonaar-music'),
            'classes'           => 'srmp3-fileorstream',
            'description'       => 'Please select which type of audio source you want for this track',
            'id'                => 'FileOrStream',
            'type'              => 'select',
            'show_option_none'  => false,
            'options'           => array(
                'mp3'               => 'Local MP3',
                'stream'            => 'External Audio File',
                'icecast'           => 'Icecast'
            ),
            'default'           => 'mp3'
        ));
        
        $cmb_album->add_group_field($tracklist, array(
            'id'            => 'track_mp3',
            'classes'       => 'srmp3-cmb2-file srmp3-settings--subitem',
            'name'          => esc_html__('MP3 File','sonaar-music'),
            'type'          => 'file',
            'description'   => esc_html__('Recommended Format: MP3 file encoded at 320kbps with sample rate of 44.1kHz','sonaar-music'),
            'query_args'    => array(
                'type'          => 'audio',
            ),
             // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'attributes'    => array(
                'required'               => false, // Will be required only if visible.
                'data-conditional-id'    => wp_json_encode( array( $tracklist, 'FileOrStream' )),
                'data-conditional-value' => 'mp3',
            )
        ));
        
        $cmb_album->add_group_field($tracklist, array(
            'id'            => 'stream_link',
            'classes'       => 'sr-stream-url-field srmp3-settings--subitem',
            'name'          => esc_html__('External Audio link','sonaar-music'),
            'type'          => 'text_url',
            'description'   => sprintf( esc_html__('Enter URL that points to your audio file. See %s for supported providers', 'sonaar-music'), '<a href="https://sonaar.io/docs/supported-audio-streaming-providers/" target="_blank">this article</a>'),
            'attributes'    => array(
                'required'               => false, // Will be required only if visible.
                'data-conditional-id'    => wp_json_encode( array( $tracklist, 'FileOrStream' )),
                'data-conditional-value' => 'stream',
            )
        ));
        $cmb_album->add_group_field($tracklist, array(
            'id'            => 'stream_title',
            'classes'       => 'sr-stream-title-field srmp3-cmb2-file srmp3-settings--subitem',
            'name'          => $this->sr_GetString('Track Title'),
            'type'          => 'text',
            'attributes'    => array(
                'required'               => false, // Will be required only if visible.
                'data-conditional-id'    => wp_json_encode( array( $tracklist, 'FileOrStream' )),
                'data-conditional-value' => 'stream',
            )
        ));
        $cmb_album->add_group_field($tracklist, array(
            'id'            => 'stream_album',
            'classes'       => 'sr-stream-album-field srmp3-settings--subitem',
            'name'          => $this->sr_GetString('Track Album'),
            'description'   => esc_html__("Leave blank if it's the same as the post title",'sonaar-music'),
            'type'          => 'text',
            'attributes'    => array(
                'required'               => false, // Will be required only if visible.
                'data-conditional-id'    => wp_json_encode( array( $tracklist, 'FileOrStream' )),
                'data-conditional-value' => 'stream',
            )
        ));
        $cmb_album->add_group_field($tracklist, array(
            'id'            => 'artist_name',
            'classes'       => 'srmp3-settings--subitem',
            'name'          => $this->sr_GetString('Artist Name'),
            'type'          => 'text',
            'attributes'    => array(
                'required'               => false, // Will be required only if visible.
                'data-conditional-id'    => wp_json_encode( array( $tracklist, 'FileOrStream' )),
                'data-conditional-value' => 'stream',
            )
        ));
        $cmb_album->add_group_field($tracklist, array(
            'id'            => 'stream_lenght',
            'classes'       => 'sr-stream-lengh srmp3-settings--subitem',
            'name'          => esc_html__('Audio Duration', 'sonaar-music'),
            'description'   => esc_html__('Format accepted: 01:20:30 (Eg: For 1 hour 20 minutes and 30 seconds duration)','sonaar-music'),
            'type'          => 'text',
            'attributes'    => array(
                'required'               => false, // Will be required only if visible.
                'data-conditional-id'    => wp_json_encode( array( $tracklist, 'FileOrStream' )),
                'data-conditional-value' => 'stream',
            )
        ));
        $cmb_album->add_group_field($tracklist, array(
            'id'            => 'icecast_link',
            'classes'       => 'sr-stream-url-field  srmp3-cmb2-file srmp3-settings--subitem',
            'name'          => __('Icecast Feed','sonaar-music'),
            'type'          => 'text_url',
            'description'   => __('Enter URL that points to your Icecast Feed.', 'sonaar-music'),
            'attributes'    => array(
                'required'               => false, // Will be required only if visible.
                'data-conditional-id'    => wp_json_encode( array( $tracklist, 'FileOrStream' )),
                'data-conditional-value' => 'icecast',
            )
        ));
        $cmb_album->add_group_field($tracklist, array(
            'classes_cb'        => 'remove_pro_label_if_pro',
            'before'            => 'promo_ad_cb',
            'id'            => 'icecast_json',
            'name'          => __('Icecast JSON file', 'sonaar-music'),
            'description'   => esc_html__("Allow to fetch the content of what is currently playing from your feed (Track Title, Artist Name, Image Cover). Usually https://yourstream.com:xxxx/status-json.xsl )",'sonaar-music'),
            'type'          => 'text_url',
            'attributes'    => array(
                'required'               => false, // Will be required only if visible.
                'data-conditional-id'    => wp_json_encode( array( $tracklist, 'FileOrStream' )),
                'data-conditional-value' => 'icecast',
            ),
            'options' => array(
                'textpromo' => esc_html__('Pro Feature', 'sonaar-music'),
            ),
        ));
        $cmb_album->add_group_field($tracklist, array(
            'classes_cb'        => 'remove_pro_label_if_pro',
            'before'            => 'promo_ad_cb',
            'id'            => 'icecast_mount',
            'name'          => __('Icecast Mountpoint', 'sonaar-music'),
            'description'   => esc_html__("Optional. Enter your mountpoint if you have one and include the '/'. Icecast mountpoint is a unique URL path representing a single audio stream on an Icecast server.",'sonaar-music'),
            'type'          => 'text',
            'attributes'    => array(
                'required'               => false, // Will be required only if visible.
                'data-conditional-id'    => wp_json_encode( array( $tracklist, 'FileOrStream' )),
                'data-conditional-value' => 'icecast',
            ),
            'options' => array(
                'textpromo' => esc_html__('Pro Feature', 'sonaar-music'),
            ),
        ));
        $cmb_album->add_group_field($tracklist, array(
            'id'            => 'icecast_title',
            'classes'       => 'sr-stream-title-field',
            'name'          => __('Feed Title', 'sonaar-music'),
            'description'   => esc_html__("If we cannot retrieve what is currently playing from Icecast, we will show this default title",'sonaar-music'),
            'type'          => 'text',
            'attributes'    => array(
                'required'               => false, // Will be required only if visible.
                'data-conditional-id'    => wp_json_encode( array( $tracklist, 'FileOrStream' )),
                'data-conditional-value' => 'icecast',
            )
        ));
        $cmb_album->add_group_field($tracklist, array(
            'id'            => 'icecast_album',
            'classes'       => 'sr-stream-album-field',
            'name'          => __('Station Name', 'sonaar-music'),
            'description'   => esc_html__("If you leave this field blank, we use the post title as fallback",'sonaar-music'),
            'type'          => 'text',
            'attributes'    => array(
                'required'               => false, // Will be required only if visible.
                'data-conditional-id'    => wp_json_encode( array( $tracklist, 'FileOrStream' )),
                'data-conditional-value' => 'icecast',
            )
        ));
        $cmb_album->add_group_field($tracklist, array(
            'id'            => 'icecast_hostname',
            'name'          => __('Station Host', 'sonaar-music'),
            'description'   => esc_html__("Name of the person or company hosting this station",'sonaar-music'),
            'type'          => 'text',
            'attributes'    => array(
                'required'               => false, // Will be required only if visible.
                'data-conditional-id'    => wp_json_encode( array( $tracklist, 'FileOrStream' )),
                'data-conditional-value' => 'icecast',
            )
        ));
        if ( function_exists( 'run_sonaar_music_pro' ) && get_site_option('SRMP3_ecommerce') == '1' && Sonaar_Music::get_option('force_audio_preview', 'srmp3_settings_audiopreview') === 'true' ){
            $cmb_album->add_group_field($tracklist, array(
                'name'          => esc_html__('Audio Preview', 'sonaar-music'),
                'id'            => 'post_audiopreview',
                'type'          => 'select',
                'label_cb'      => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => '',
                    'text'      => __('Also known as an <em>"audio snippet"</em> or <em>"sample,"</em> it is a brief segment from a longer audio recording. It provides listeners a glimpse of the full content without playing the entire track.<br><br>
                    Used predominantly in <strong>online music stores</strong> and <strong>audiobook platforms</strong>, these short clips, lasting from a few seconds to minutes, assist potential buyers in their purchasing decisions.<br><br>
                    They also serve as a protective measure against <strong>unauthorized downloads</strong> by only showcasing a segment. Some previews might even include voiceovers or watermarks like <strong>"sample"</strong> to deter misuse.<br><br>
                    When a user <strong>(with restricted role)</strong> visit your website, it\'s this audio snippet they hear or download. You can set the restricted role in WP-Admin > MP3 Player > Settings<br><br>You can also disable this audio preview to allow everyone to listen and download your full track.<br><br> Remember: If you do not set audio preview, it\'s OK! In this case, the full track will be available.', 'sonaar-music'),
                    'image'     => '',
                ),
                'options'       => array(
                   // 'default'   => esc_html__('Default (Use options in the General Settings)', 'sonaar-music'),
                    'enabled'      => esc_html__('Enable', 'sonaar-music'),
                    'disabled'     => esc_html__('Disable', 'sonaar-music'),
                ),
                'default'       => 'true'
            ) );
            
            $cmb_album->add_group_field($tracklist, array(
                'classes'       => 'ffmpeg_field srmp3-settings--subitem',
                'name'          => esc_html__('Preview Settings', 'sonaar-music'),
                'id'            => 'post_audiopreview_settings',
                'type'          => 'select',
                'options'       => array(
                    'default'   => esc_html__('Use General Settings', 'sonaar-music'),
                    'custom'      => esc_html__('Custom Settings', 'sonaar-music'),
                ),
                'default'       => 'default',
                'label_cb'      => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => '',
                    'text'      => esc_html__('If you want to use a custom settings for this preview (and by-pass the general settings located in WP-Admin > MP3 Player > Settings), use Custom Settings.', 'sonaar-music'),
                    'image'     => '',
                ),
                'attributes' => array(
                    'data-conditional-id'    => wp_json_encode( array( $tracklist, 'post_audiopreview' )),
                    'data-conditional-value' => 'enabled',
                ),
            ) );
            $cmb_album->add_group_field($tracklist, array(
                'classes'       => 'ffmpeg_field srmp3-settings--subitem',
                'name'          => __( 'Trim Start at', 'sonaar-music' ),
                'id'            => 'post_trimstart',
                'type'          => 'text_small',
                'default'       => '00:00:00',
                'attributes'    => array(
                    'data-conditional-id'    => wp_json_encode( array( $tracklist, 'post_audiopreview_settings' )),
                    'data-conditional-value' => 'custom',
                ),
                'after'         => esc_html(' Format must be HH:MM:SS', 'sonaar_music'),
                'label_cb'      => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => '',
                    'text'      => esc_html__('Set the time point (in seconds) from which your preview starts playing. Warning: If your full track duration is less than this number, its preview wont be generated.', 'sonaar-music'),
                    'image'     => '',
                ),
            ) );
            $cmb_album->add_group_field($tracklist, array(
                'classes'       => 'ffmpeg_field srmp3-settings--subitem',
                'name'          => __( 'Preview Length', 'sonaar-music' ),
                'id'            => 'post_audiopreview_duration',
                'type'          => 'text_small',
                'default'       => '00:00:30',
                'attributes'    => array(
                    'data-conditional-id'    => wp_json_encode( array( $tracklist, 'post_audiopreview_settings' )),
                    'data-conditional-value' => 'custom',
                ),
                'after'         => esc_html(' Format must be HH:MM:SS', 'sonaar_music'),
                'label_cb'      => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => '',
                    'text'      => esc_html__('This represents the trim cut duration of your track. For full track length, use infinite number (eg: 99999).', 'sonaar-music'),
                    'image'     => '',
                ),
            ) );
            $cmb_album->add_group_field($tracklist, array(
                'classes'       => 'ffmpeg_field srmp3-settings--subitem',
                'name'          => __( 'Fade In Length', 'sonaar-music' ),
                'id'            => 'post_fadein_duration',
                'type'          => 'text_small',
                'default'       => 3,
                'after'           => esc_html(' seconds', 'sonaar_music'),
                'attributes' => array(
                    'type' => 'number',
                    'pattern' => '\d*',
                    'data-conditional-id'    => wp_json_encode( array( $tracklist, 'post_audiopreview_settings' )),
                    'data-conditional-value' => 'custom',
                ),
            ) );
            $cmb_album->add_group_field($tracklist, array(
                'classes'       => 'ffmpeg_field srmp3-settings--subitem',
                'name'          => __( 'Fade Out Length', 'sonaar-music' ),
                'id'            => 'post_fadeout_duration',
                'type'          => 'text_small',
                'default'       => 3,
                'after'           => esc_html(' seconds', 'sonaar_music'),
                'attributes' => array(
                    'type' => 'number',
                    'pattern' => '\d*',
                    'data-conditional-id'    => wp_json_encode( array( $tracklist, 'post_audiopreview_settings' )),
                    'data-conditional-value' => 'custom',
                ),
            ) );
            $cmb_album->add_group_field($tracklist, array(
                'classes'       => 'ffmpeg_field srmp3-cmb2-file srmp3-settings--subitem',
                'id'            => 'post_audio_watermark',
                'name'          => esc_html__('Audio Watermark', 'sonaar-music'),
                'type'          => 'file',
                'query_args'    => array(
                    'type'          => 'audio',
                ),
                'options' => array(
                    'url' => false, // Hide the text input for the url
                ),
                'label_cb'      => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => '',
                    'text'      => esc_html__('Audio Watermark can be set here. Keep it short and make sure it does not contain silences, Watermarks will be looped every 10 seconds.', 'sonaar-music'),
                    'image'     => '',
                ),
                'attributes' => array(
                    'data-conditional-id'    => wp_json_encode( array( $tracklist, 'post_audiopreview_settings' )),
                    'data-conditional-value' => 'custom',
                ),
            ) );
            $cmb_album->add_group_field($tracklist, array(
                'classes'       => 'ffmpeg_field srmp3-cmb2-file srmp3-settings--subitem',
                'id'            => 'post_ad_preroll',
                'name'          => esc_html__('Pre-roll Ad (optional)', 'sonaar-music'),
                'type'          => 'file',
                'query_args'    => array(
                    'type'          => 'audio',
                ),
                'options' => array(
                    'url' => false, // Hide the text input for the url
                ),
                'label_cb'      => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => '',
                    'text'      => esc_html__('Add a pre-roll audio advertising here. Keep it short and make sure it does not contain silences.', 'sonaar-music'),
                    'image'     => '',
                ),
                'attributes' => array(
                    'data-conditional-id'    => wp_json_encode( array( $tracklist, 'post_audiopreview_settings' )),
                    'data-conditional-value' => 'custom',
                ),
            ) );
            $cmb_album->add_group_field($tracklist, array(
                'classes'       => 'ffmpeg_field srmp3-settings--subitem',
                'id'            => 'post_ad_postroll',
                'name'          => esc_html__('Post-roll Ad (optional)', 'sonaar-music'),
                'type'          => 'file',
                'query_args'    => array(
                    'type'          => 'audio',
                ),
                'options' => array(
                    'url' => false, // Hide the text input for the url
                ),
                'label_cb'      => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => '',
                    'text'      => esc_html__('Add a post-roll audio advertising here. Keep it short and make sure it does not contain silences.', 'sonaar-music'),
                    'image'     => '',
                ),
                'attributes' => array(
                    'data-conditional-id'    => wp_json_encode( array( $tracklist, 'post_audiopreview_settings' )),
                    'data-conditional-value' => 'custom',
                ),
            ) );
            $cmb_album->add_group_field($tracklist, array(
                'classes'       => 'srmp3-cmb2-preview-file srmp3-settings--subitem',
                'name'          => __( 'Preview File', 'sonaar-music' ),
                'before'   => __('<button id="srmp3_index_audio_preview" class="srmp3-generate-bt srmp3-audiopreview-bt showSpinner">Generate Preview File</button> <span id="srmp3_indexTracks_status"></span><progress id="indexationProgress" style="width:100%;margin-top:10px;display:none;" value="0" max="100"></progress><span id="progressText"></span><br><br>','sonaar-music'),
                'id'            => 'audio_preview',
                'type'          => 'file',
                'default'       => '',
                'text' => array(
                    'file_text' => 'File', // default: "File:"
                    'file_download_text' => 'Listen', // default: "Download"
                    'remove_text' => 'Remove', // default: "Remove"
                ),
                'label_cb'      => 'srmp3_add_tooltip_to_label',
                'tooltip'       => array(
                    'title'     => '',
                    'text'      => __('Click Generate Preview to generate automatically an audio snippet from your original file.<br><br>You can also upload your own preview file. If you do so, make sure to NOT click the Generate Preview button otherwise it will be overwritten.<br><br>If you do not set audio preview, it\'s OK! In this case, the full track will be available.', 'sonaar-music'),
                    'image'     => '',
                ),
                'attributes' => array(
                    'placeholder'   => __('No preview file set. Full track will be played.', 'sonaar-music'),
                    'data-conditional-id'    => wp_json_encode( array( $tracklist, 'post_audiopreview' )),
                    'data-conditional-value' => 'enabled',
                ),
            ) );
            
        
        
        
        
        }
        if ( Sonaar_Music::get_option('use_wysiwyg_for_trackdesc', 'srmp3_settings_general') === 'true' ){
            $cmb_album->add_group_field($tracklist, array(
                'classes_cb'        => 'remove_pro_label_if_pro',
                'before'            => 'promo_ad_cb',
                'id'            => 'track_description',
                'name'          => $this->sr_GetString('Optional Track Information'),
                'description'   => esc_html__("BPM, Hashtag, Description, etc. Will appear below track title in the playlist.",'sonaar-music'),
                'type'          => 'wysiwyg',
                'options' => array(
                    'textpromo' => esc_html__('Pro Feature', 'sonaar-music'),
                    'wpautop' => false, // use wpautop?
                    'media_buttons' => true, // show insert/upload button(s)
                    'textarea_rows' => get_option('default_post_edit_rows', 6), // rows="..."
                    'tabindex' => '',
                    'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
                    'editor_class' => '', // add extra class(es) to the editor textarea
                    'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
                    'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
                    'quicktags' => true, // load Quicktags, can be used to pass settings directly to Quicktags using an array()
                   
                )
            ));
        }else{
            $cmb_album->add_group_field($tracklist, array(
                'classes_cb'        => 'remove_pro_label_if_pro',
                'before'            => 'promo_ad_cb',
                'id'            => 'track_description',
                'name'          => $this->sr_GetString('Optional Track Information'),
                'description'   => esc_html__("BPM, Hashtag, Description, etc. Will appear below track title in the playlist.",'sonaar-music'),
                'type'          => 'textarea',
                'sanitization_cb' => 'srmp3_sanitize_trackdescription', // function should return a sanitized value
            ));
        }
        
    
        $cmb_album->add_group_field( $tracklist ,array(
            'name'              => $this->sr_GetString('Optional Track Image'),
            //'classes'           => array('srmp3-pro-feature', 'prolabel--nomargin'),
            'classes_cb'        => 'remove_pro_label_if_pro',
            'before'            => 'promo_ad_cb',
            'id'                => 'track_image',
            'type'              => 'file',
            'text'              => array(
                'add_upload_file_text' => 'Add Image' // Change upload button text. Default: "Add or Upload File"
            ),
            'preview_size' => array( 60, 60 ),  // Image size to use when previewing in the admin.
            'options' => array(
                'textpromo' => esc_html__('Pro Feature', 'sonaar-music'),
                'url' => false, // Hide the text input for the url
            ),
            // query_args are passed to wp.media's library query.
            'query_args'        => array(
                // Or only allow gif, jpg, or png images
                'type'  => array(
                     'image/gif',
                     'image/jpeg',
                     'image/png',
                ),
            ),
        ));
        $cmb_album->add_group_field($tracklist, array(
            'classes_cb'        => 'remove_pro_label_if_pro',
            'before'            => 'promo_ad_cb',
            'id'            => 'track_lyrics',
            'name'          => $this->sr_GetString('Lyrics/Karaoke file (.ttml)'),
            'description'   => sprintf( esc_html__('We support Timed Text Markup Language (TTML). You can generate your .ttml file %1$shere%3$s. Fun & Easy! %2$sLearn More%3$s','sonaar-music'), '<a href="https://lyricpotato.com/" target="_blank">', '<a href="https://sonaar.io/docs/add-lyrics-karaoke-to-audio-player-wordpress/" target="_blank">', '</a>'),
            'type'          => 'file',
            'query_args'        => array(
                'type'  => array(
                     'text/xml',
                ),
            ),
            'options' => array(
                'textpromo' => esc_html__('Pro Feature', 'sonaar-music'),
            ), 
        )); 
        $cmb_album->add_group_field( $tracklist, array(
            'id'            => 'song_store_list',
            'type'          => 'store_list',
            'name' 			=> esc_html__('Optional Call to Action','sonaar-music'),
            'repeatable'    => true,
            'icon'          => true,
            'options'       => array(
                'sortable'      => true, // beta
            ),
            'text'          => array(
                'add_row_text'      => 'Add Call to Action',
                'store_icon_text'   => '',
                'store_name_desc'   => esc_html__('Eg: Spotify, SoundCloud, Buy Now', 'sonaar-music'),
                'store_showlabel_desc'   => esc_html__('Make sure playlist is wide enough to display the label', 'sonaar-music'),
                'store_link_desc'   => '',
                'store_content_desc'   => esc_html__('Eg: Text, Lyrics, Shortcodes and HTML accepted','sonaar-music'),
            
            ) 
        ));
        if ( Sonaar_Music::get_option('player_type', 'srmp3_settings_general') == 'podcast' ){
            $cmb_album->add_group_field( $tracklist ,array(
                'name'          => esc_html__('Mark this episode as explicit', 'sonaar-music'),
                'id'            => 'podcast_explicit_episode',
                'show_on_cb'    => 'sr_check_if_sr_posttype',
                'type'          => 'switch',
                'default'       => 0,
            ) );
            $cmb_album->add_group_field( $tracklist ,array(
                'name'          => esc_html__('Block episode from appearing in the RSS', 'sonaar-music'),
                'id'            => 'podcast_itunes_notshow',
                'show_on_cb'    => 'sr_check_if_sr_posttype',
                'type'          => 'switch',
                'default'       => 0,
            ) );
            $cmb_album->add_group_field( $tracklist ,array(
                'name'          => esc_html__('iTunes Episode Title (exclude series or show number)', 'sonaar-music'),
                'id'            => 'podcast_itunes_episode_title',
                'show_on_cb'    => 'sr_check_if_sr_posttype',
                'type'          => 'text',
            ) );
            $cmb_album->add_group_field( $tracklist ,array(
                'name'          => esc_html__('Episode Number. Leave blank if none', 'sonaar-music'),
                'id'            => 'podcast_itunes_episode_number',
                'show_on_cb'    => 'sr_check_if_sr_posttype',
                'type'          => 'text_small',
            ) );
            $cmb_album->add_group_field( $tracklist ,array(
                'name'          => esc_html__('Season number. Leave blank if none', 'sonaar-music'),
                'id'            => 'podcast_itunes_season_number',
                'show_on_cb'    => 'sr_check_if_sr_posttype',
                'type'          => 'text_small',
            ) );
            $cmb_album->add_group_field( $tracklist ,array(
                'name'              => esc_html__( 'Episode Type', 'sonaar-music'),
                'id'                => 'podcast_itunes_episode_type',
                'show_on_cb'    => 'sr_check_if_sr_posttype',
                'type'              => 'select',
                'show_option_none'  => true,
                'options'           => array(
                    'full'        => esc_html__( 'Full', 'sonaar-music' ),
                    'trailer'              => esc_html__( 'Trailer', 'sonaar-music' ),
                    'bonus'              => esc_html__( 'Bonus', 'sonaar-music' ),
                ),
                'default'           => 'full',
            ) );

        }
        $cmb_album->add_group_field($tracklist, array( // this field is hidden in the admin because its useless for the user.
            'classes'        => 'srmp3_hide',
            'id'            => 'track_peaks',
            'name'          => esc_html__("Waveform Peak File",'sonaar-music'),
            'type'          => 'text',
        )); 
        $cmb_album->add_field( array(
            'id'            => 'alb_store_list',
            'type'          => 'store_list',
            'name'          => esc_html__('External Links Buttons','sonaar-music'),
            'repeatable'    => true,
            'column' => array(
                'name'     => esc_html__( 'Store Links', 'sonaar-music' ),
            ),
            'display_cb'    => 'sr_admin_column_count',
            'icon'          => true,
            'text'          => array(
                'add_row_text'      => esc_html__('Add Link', 'sonaar-music'),
            )
        ));
        if ( !function_exists( 'run_sonaar_music_pro' ) ){
            $cmb_album_promo = new_cmb2_box( array(
                'id'            => 'sonaar_promo',
                'title'        	=> esc_html__( 'Why MP3 Player PRO?', 'sonaar-music' ),
                'object_types' 	=> array( SR_PLAYLIST_CPT ),
                'context'       => 'side',
                'priority'      => 'low',
                'show_names'    => false,
                'capability'    => 'manage_options', // Cap required to view options-page.
            ) );
        
            
            $cmb_album_promo->add_field( array(
                'id'            => 'calltoaction',
                'name'	        => esc_html__('sonaar pro', 'sonaar-music'),
                'type'          => 'calltoaction',
                'href'          => esc_html('https://sonaar.io/mp3-audio-player-pro/?utm_source=Sonaar+Music+Free+Plugin&utm_medium=plugin'),
                'img'           => esc_url(plugin_dir_url(dirname(__FILE__)) . 'admin/img/sonaar-music-pro-banner-cpt.jpg'),                
            ));
        }
        
        /**
        * LICENSE AND CONTRACTS
        **/

        if ( defined( 'WC_VERSION' ) && Sonaar_Music::get_option('wc_enable_licenses_cpt', 'srmp3_settings_woocommerce') == 'true' ) {
            if ( function_exists( 'run_sonaar_music_pro' ) ){
                $cmb_post_usageterms = new_cmb2_box( array(
                    'id'            => 'cmb2_usage_terms_box',
                    'title'         => esc_html__('What is included in the license', 'sonaar-music'),
                    'object_types'  => 'usage-terms',
                    'context'       => 'normal',
                    'priority'      => 'low',
                    'show_names'    => true,
                    'capability'    => 'manage_options',
                ) );
                $cmb_post_usageterms->add_field( array(
                    'name'              => esc_html__('Bind this license to which product attribute?', 'sonaar-music'),
                    'description'       => esc_html__('Product attributes are created in Products > Attributes.', 'sonaar-music'),
                    'id'                => 'usageterms_product_variation',
                    'show_option_none'  => true,
                    'column' => array(
                        'position' => 2,
                        'name'     => esc_html__('Linked Product Attribute','sonaar-music')
                    ),
                    'type'              => 'select',
                    'options_cb'        => [$this, 'srmp3_get_terms_variations'],
                    
                ) );
                $cmb_post_usageterms->add_field( array(
                    'name'    => 'Files to Deliver when this license is Purchased',
                    'id'      => 'usageterms_filetypes',
                    'type'    => 'multicheck',
                    'select_all_button' => false,
                    'column' => array(
                        'position' => 2,
                        'name'     => esc_html__('File Included','sonaar-music')
                    ),
                    'options' => array(
                        'mp3' => esc_html__('MP3', 'sonaar-music'),
                        'wav' => esc_html__('WAV', 'sonaar-music'),
                        'stems' => esc_html__('TRACK STEMS', 'sonaar-music'),
                    ),
                    'default' => 'mp3'
                ) );
                $cmb_post_usageterms->add_field( array(
                    'name'          => esc_html__('Producer Name', 'sonaar-music'),
                    'description'   => esc_html__('Enter the contract producer name', 'sonaar-music'),
                    'id'            => 'usageterms_producer_alias',
                    'type'          => 'text_medium',
                ) );
                $cmb_post_usageterms->add_field( array(
                    'name'          => esc_html__('Number of distribution copies', 'sonaar-music'),
                    'description'   => esc_html__('Enter a number or the word Unlimited', 'sonaar-music'),
                    'id'            => 'usageterms_num_dist_copies',
                    'column' => array(
                        'position' => 3,
                        'name'     => esc_html__('Distribution Copies','sonaar-music')
                    ),
                    'type'          => 'text_small',
                ) );
                $cmb_post_usageterms->add_field( array(
                    'name'          => esc_html__('Number of audio streams', 'sonaar-music'),
                    'description'   => esc_html__('Enter a number or the word Unlimited', 'sonaar-music'),
                    'id'            => 'usageterms_num_audio_streams',
                    'type'          => 'text_small',
                    'column' => array(
                        'position' => 3,
                        'name'     => esc_html__('Audio Streams','sonaar-music')
                    ),
                ) );
                $cmb_post_usageterms->add_field( array(
                    'name'          => esc_html__('Number of radio stations', 'sonaar-music'),
                    'description'   => esc_html__('Enter a number or the word Unlimited', 'sonaar-music'),
                    'id'            => 'usageterms_num_radio_stations',
                    'type'          => 'text_small',
                    'column' => array(
                        'position' => 3,
                        'name'     => esc_html__('Radio','sonaar-music')
                    ),
                ) );
                $cmb_post_usageterms->add_field( array(
                    'name'          => esc_html__('Number of free downloads', 'sonaar-music'),
                    'description'   => esc_html__('Enter a number or the word Unlimited', 'sonaar-music'),
                    'id'            => 'usageterms_num_free_downloads',
                    'type'          => 'text_small',
                    'column' => array(
                        'position' => 3,
                        'name'     => esc_html__('Free Downloads','sonaar-music')
                    ),
                ) );
                $cmb_post_usageterms->add_field( array(
                    'name'          => esc_html__('Number of music video', 'sonaar-music'),
                    'description'   => esc_html__('Enter a number or the word Unlimited', 'sonaar-music'),
                    'id'            => 'usageterms_num_music_videos',
                    'type'          => 'text_small',
                    'column' => array(
                        'position' => 3,
                        'name'     => esc_html__('Music Videos','sonaar-music')
                    ),
                ) );
                $cmb_post_usageterms->add_field( array(
                    'name'          => esc_html__('Number of video streams', 'sonaar-music'),
                    'description'   => esc_html__('Enter a number or the word Unlimited', 'sonaar-music'),
                    'id'            => 'usageterms_num_monetized_video_streams',
                    'type'          => 'text_small',
                    'column' => array(
                        'position' => 3,
                        'name'     => esc_html__('Video Streams','sonaar-music')
                    ),
                ) );
                $cmb_post_usageterms->add_field( array(
                    'name'          => esc_html__('State or province', 'sonaar-music'),
                    'description'   => esc_html__('Enter your state or province', 'sonaar-music'),
                    'id'            => 'usageterms_state',
                    'type'          => 'text_medium',
                ) );
                $cmb_post_usageterms->add_field( array(
                    'name'          => esc_html__('Country', 'sonaar-music'),
                    'description'   => esc_html__('Enter your country name', 'sonaar-music'),
                    'id'            => 'usageterms_country',
                    'type'          => 'text_medium',
                ) );
                $cmb_post_usageterms->add_field( array(
                    'name'              => esc_html__('Allow for profit live performances', 'sonaar-music'),
                    'id'                => 'usageterms_allow_profit_performances',
                    'type'              => 'select',
                    'show_option_none'  => true,
                    'options'           => array(
                        'yes'               => esc_html__( 'Yes', 'sonaar-music' ),
                        'no'                => esc_html__( 'No', 'sonaar-music' ),
                    ),
                ) );

                $cmb_post_usageterms_custom_group = $cmb_post_usageterms->add_field( array(
                    'id'            => 'usageterms_custom_options_group',            
                    'type'          => 'group',
                    'name' 			=> 'Add your own custom license options',
                    'repeatable'    => true, // use false if you want non-repeatable group
                    'options'       => array(
                        'add_button'    => esc_html__('Add License Option', 'sonaar-music'),
                        'remove_button' => esc_html__('Remove Option', 'sonaar-music'),
                        'sortable'      => true, // beta
                        'closed'        => false, // true to have the groups closed by default
                    ),
                ) );

                $cmb_post_usageterms->add_group_field( $cmb_post_usageterms_custom_group ,array(
                    'name'          => esc_html__('Option Name', 'sonaar-music'),
                    'description'   => esc_html__('eg: 10 videos included', 'sonaar-music'),
                    'id'            => 'usageterms_custom_options_item_name',
                    'type'          => 'text_medium',
                ));
                $cmb_post_usageterms->add_group_field( $cmb_post_usageterms_custom_group ,array(
                    'name'          => esc_html__('ID Variable for the contract', 'sonaar-music'),
                    'description'   => esc_html__('ID without space and between curly brackets. ', 'sonaar-music'),
                    'id'            => 'usageterms_custom_options_item_var',
                    'type'          => 'text_medium',
                    'attributes'    => array(
                        'placeholder' => '{your-unique-id}',
                    ),
                ));
                $cmb_post_usageterms->add_group_field( $cmb_post_usageterms_custom_group ,array(
                    'name' => __( 'Icon', 'sonaar-music' ),
                    'id'   => 'usageterms_custom_options_item_icon',
                    'type' => 'faiconselect',
                    'options_cb' => 'srmp3_returnRayFaPre'
                ) );
                $cmb_post_usageterms->add_field( array(
                    'id'            => 'usageterms_contract',
                    'name'          => $this->sr_GetString('The Contract'),
                    //'description'   => esc_html__("BPM, Hashtag, Description, etc. Will appear below track title in the playlist.",'sonaar-music'),
                    'type'          => 'wysiwyg',
                    'options' => array(
                        //'textpromo' => esc_html__('Pro Feature', 'sonaar-music'),
                        'wpautop' => false, // use wpautop?
                        'media_buttons' => false, // show insert/upload button(s)
                        'textarea_rows' => get_option('default_post_edit_rows', 40), // rows="..."
                        'tabindex' => '',
                        'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
                        'editor_class' => '', // add extra class(es) to the editor textarea
                        'teeny' => true, // output the minimal editor config used in Press This
                        'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
                        'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
                        'quicktags' => true, // load Quicktags, can be used to pass settings directly to Quicktags using an array()
                    ),
                    'default'           => __('
<h1>{LICENSE_NAME}</h1>

<p>This License Agreement (the “Agreement”), having been made on and effective as of <strong>{CONTRACT_DATE}</strong> (the “Effective Date”) by and between <strong>{PRODUCER_ALIAS}</strong> (the “Producer” or “Licensor”); and you, <strong>{CUSTOMER_FULLNAME}</strong> (“You” or “Licensee”), residing at <strong>{CUSTOMER_ADDRESS}</strong>, sets forth the terms and conditions of the Licensee’s use, and the rights granted in, the Producer’s instrumental music file entitled <strong>{PRODUCT_TITLE}</strong> (the “Beat”) in consideration for Licensee’s payment, on a so-called “<strong>{LICENSE_NAME}</strong>” basis.</p>

<p>This Agreement is issued solely in connection with and for Licensee use of the Beat pursuant and subject to all terms and conditions set forth herein.</p>

<h3>License Fee:</h3>
<p>The Licensee to shall make payment of the License Fee to Licensor on the date of this Agreement. All rights granted to Licensee by Producer in the Beat are conditional upon Licensee’s timely payment of the License Fee. The License Fee is a one-time payment for the rights granted to Licensee and this Agreement is not valid until the License Fee has been paid.</p>

<h3>Delivery of the Beat:</h3>
<p>Licensor agrees to deliver the Beat as a high-quality file, as such terms are understood in the music industry. Licensor shall use commercially reasonable efforts to deliver the Beat to Licensee immediately after payment of the License Fee is made. Licensee will receive the Beat via email, to the email address Licensee provided to Licensor.</p>

<h3>Term:</h3>
<p>The Term of this Agreement shall be ten (10) years and this license shall expire on the ten (10) year anniversary of the Effective Date.</p>

<h3>Use of the Beat:</h3>
<p>In consideration for Licensee’s payment of the License Fee, the Producer hereby grants Licensee a limited non-exclusive, nontransferable license and the right to incorporate, include and/or use the Beat in the preparation of one (1) new song or to incorporate the Beat into a new piece of instrumental music created by the Licensee. Licensee may create the new song or new instrumental music by recording his/her written lyrics over the Beat and/or by incorporating portions/samples of the Beat into pre-existing instrumental music written, produced and/or owned by Licensee. The new song or piece of instrumental music created by the Licensee which incorporates some or all of the Beat shall be referred to as the “New Song”. Permission is granted to Licensee to modify the arrangement, length, tempo, or pitch of the Beat in preparation of the New Song for public release.</p>
<p>This License grants Licensee a worldwide, non-exclusive license to use the Beat as incorporated in the New Song in the manners and for the purposes expressly provided for herein, subject to the sale restrictions, limitations and prohibited uses stated in this Agreement. Licensee acknowledges and agrees that any and all rights granted to Licensee in the Beat pursuant to this Agreement are on a NON-EXCLUSIVE basis and Producer shall continue to license the Beat upon the same or similar terms and conditions as this Agreement to other potential third-party licensees.</p>
<p>The New Song may be used for any promotional purposes, including but not limited to, a release in a single format, for inclusion in a mixtape or free compilation of music bundled together (EP or album), and/or promotional, non-monetized digital streaming;</p>
<p>Licensee <strong>{PERFORMANCES_FOR_PROFIT}</strong> perform the song publicly for-profit performances, including but not limited to, at a live performance (i.e. concert, festival, nightclub etc.), on terrestrial or satellite radio, and/or on the internet via third-party streaming services (Spotify, YouTube, iTunes Radio etc.). The New Song may be played on {NUMBER_OF_RADIO_STATIONS} terrestrial or satellite radio stations;</p>
<p>The Licensee may use the New Song in synchronization with <strong>{MONETIZED_MUSIC_VIDEOS}</strong> audiovisual work no longer than five (5) minutes in length (a “Video”). In the event that the New Song itself is longer than five (5) minutes in length, the Video may not play for longer than the length of the New Song. The Video may be broadcast on any television network and/or uploaded to the internet for digital streaming and/or free download by the public including but not limited to on YouTube and/or Vevo. Producer grants no other synchronization rights to Licensee;</p>
<p>The Licensee may make the New Song available for sale in physical and/or digital form and sell <strong>{DISTRIBUTE_COPIES}</strong> downloads/physical music products and are allowed <strong>{AUDIO_STREAMS}</strong> monetized audio streams, <strong>{MONETIZED_VIDEO_STREAMS_ALLOWED}</strong> monetized video streams and are allowed <strong>{FREE_DOWNLOADS}</strong> free downloads. The New Song may be available for sale as a single and/or included in a compilation of other songs bundled together by Licensee as an EP or a full-length Album. The New Song may be sold via digital retailers for permanent digital download in mp3 format and/or physical format, including compact disc and vinyl records. For clarity and avoidance of doubt, the Licensee does NOT have the right to sell the Beat in the form that it was delivered to Licensee. The Licensee must create a New Song (or instrumental as detailed above) for its rights under this provision to a vest. Any sale of the Beat in its original form by Licensee shall be a material breach of this Agreement and the Licensee shall be liable to the Licensor for damages as provided hereunder.</p>
<p>Subject to the Licensee’s compliance with the terms and conditions of this Agreement, Licensee shall not be required to account or pay to Producer any royalties, fees, or monies paid to or collected by the Licensee (expressly excluding mechanical royalties), or which would otherwise be payable to Producer in connection with the use/exploitation of the New Song as set forth in this Agreement.</p>
<p>Restrictions on the Use of the Beat: Licensee hereby agrees and acknowledges that it is expressly prohibited from taking any action(s) and from engaging in any use of the Beat or New Song in the manners, or for the purposes, set forth below:</p>
<p>The rights granted to Licensee are NON-TRANSFERABLE and that Licensee may not transfer or assign any of its rights hereunder to any third-party;</p>
<p>The Licensee shall not synchronize, or permit third parties to synchronize, the Beat or New Song with any audiovisual works EXCEPT as expressly provided for and pursuant to Paragraph 4(b)(iii) of this Agreement for use in one (1) Video. This restriction includes, but is not limited to, use of the Beat and/or New Song in television, commercials, film/movies, theatrical works, video games, and in any other form on the Internet which is not expressly permitted herein.</p>
<p>The Licensee shall not have the right to license or sublicense any use of the Beat or of the New Song, in whole or in part, for any so-called “samples”.</p>
<p>Licensee shall not engage in any unlawful copying, streaming, duplicating, selling, lending, renting, hiring, broadcasting, uploading, or downloading to any database, servers, computers, peer to peer sharing, or other file-sharing services, posting on websites, or distribution of the Beat in the form, or a substantially similar form, as delivered to Licensee. Licensee may send the Beat file to any individual musician, engineer, studio manager or other people who are working on the New Song.</p>
<p>THE LICENSEE IS EXPRESSLY PROHIBITED FROM REGISTERING THE BEAT AND/OR NEW SONG WITH ANY CONTENT IDENTIFICATION SYSTEM, SERVICE PROVIDER, MUSIC DISTRIBUTOR, RECORD LABEL OR DIGITAL AGGREGATOR (for example TuneCore or CDBaby, and any other provider of user-generated content identification services). The purpose of this restriction is to prevent you from receiving a copyright infringement takedown notice from a third party who also received a non-exclusive license to use the Beat in a New Song. The Beat has already been tagged for Content Identification (as that term is used in the music industry) by Producer as a pre-emptive measure to protect all interested parties in the New Song. If you do not adhere to this policy, you are in violation of the terms of this License and your license to use the Beat and/or New Song may be revoked without notice or compensation to you.</p>
<p>As applicable to both the underlying composition in the Beat and to the master recording of the Beat: (i) The parties acknowledge and agree that the New Song is a “derivative work”, as that term is used in the United States Copyright Act; (ii) As applicable to the Beat and/or the New Song, there is no intention by the parties to create a joint work; and (iii) There is no intention by the Licensor to grant any rights in and/or to any other derivative works that may have been created by other third-party licensees.</p>

<h3>Ownership:</h3>
<p>The Producer is and shall remain the sole owner and holder of all rights, title, and interest in the Beat, including all copyrights to and in the sound recording and the underlying musical compositions written and composed by Producer. Nothing contained herein shall constitute an assignment by Producer to Licensee of any of the foregoing rights. Licensee may not, under any circumstances, register or attempt to register the New Song and/or the Beat with the U.S. Copyright Office. The aforementioned right to register the New Song and/or the Beat shall be strictly limited to Producer. Licensee will, upon request, execute, acknowledge and deliver to Producer such additional documents as Producer may deem necessary to evidence and effectuate Producer’s rights hereunder, and Licensee hereby grants to Producer the right as attorney-in-fact to execute, acknowledge, deliver and record in the U.S. Copyright Office or elsewhere any and all such documents if Licensee shall fail to execute same within five (5) days after so requested by Producer.</p>
<p>For the avoidance of doubt, you do not own the master or the sound recording rights in the New Song. You have been licensed the right to use the Beat in the New Song and to commercially exploit the New Song based on the terms and conditions of this Agreement.</p>
<p>Notwithstanding the above, you do own the lyrics or other original musical components of the New Song that were written or composed solely by you.</p>
<p>With respect to the publishing rights and ownership of the underlying composition embodied in the New Song, the Licensee, and the Producer hereby acknowledge and agree that the underlying composition shall be owned/split between them as follows:</p>
<p>Producer shall own, control, and administer Fifty Percent (50%) of the so-called “Publisher’s Share” of the underlying composition.</p>
<p>In the event that Licensee wishes to register his/her interests and rights to the underlying composition of the New Song with their Performing Rights Organization (“PRO”), Licensee must simultaneously identify and register the Producer’s share and ownership interest in the composition to indicate that Producer wrote and owns 50% of the composition in the New Song and as the owner of 50% of the Publisher’s share of the New Song.</p>
<p>The licensee shall be deemed to have signed, affirmed and ratified its acceptance of the terms of this Agreement by virtue of its payment of the License Fee to Licensor and its electronic acceptance of its terms and conditions at the time Licensee made payment of the License Fee.</p>
<p>Mechanical License: If any selection or musical composition, or any portion thereof, recorded in the New Song hereunder is written or composed by Producer, in whole or in part, alone or in collaboration with others, or is owned or controlled, in whole or in part, directly or indirectly, by Producer or any person, firm, or corporation in which Producer has a direct or indirect interest, then such selection and/or musical composition shall be hereinafter referred to as a “Controlled Composition”.</p>
<p>Producer hereby agrees to issue or cause to be issued, as applicable, to Licensee, mechanical licenses in respect of each Controlled Composition, which are embodied on the New Song. For that license, on the United States and Canada sales, Licensee will pay mechanical royalties at one hundred percent (100%) of the minimum statutory rate, subject to no cap of that rate for albums and/or EPs. For license outside the United States and Canada, the mechanical royalty rate will be the rate prevailing on an industry-wide basis in the country concerned on the date that this agreement has been entered into.</p>

<h3>Credit:</h3>
<p>Licensee shall have the right to use and permit others to use Producer’s approved name, approved likeness, and other approved identification and approved biographical material concerning the Producer solely for purposes of trade and otherwise without restriction solely in connection with the New Song recorded hereunder.</p>
<p>Licensee shall use best efforts to have Producer credited as a “producer” and shall give Producer appropriate production and songwriting credit on all compact discs, record, music video, and digital labels or any other record configuration manufactured which is now known or created in the future that embodies the New Song created hereunder and on all cover liner notes, any records containing the New Song and on the front and/or back cover of any album listing the New Song and other musician credits. The licensee shall use its best efforts to ensure that Producer is properly credited and Licensee shall check all proofs for the accuracy of credits, and shall use its best efforts to cure any mistakes regarding Producers credit. In the event of any failure by Licensee to issue the credit to Producer, Licensee must use reasonable efforts to correct any such failure immediately and on a prospective basis. Such credit shall be in the substantial form: “Produced by <strong>{PRODUCER_ALIAS}</strong>”.</p>
<p>Licensor Option: Licensor shall have the option, at Licensors sole discretion, to terminate this License at any time within three (3) years of the date of this Agreement upon written notice to Licensee. In the event that Licensor exercises this option, Licensor shall pay to Licensee a sum equal to Two Hundred Percent (200%) of the License Fee paid by Licensee. Upon Licensor’s exercise of the option, Licensee must immediately remove the New Song from any and all digital and physical distribution channels and must immediately cease access to any streams and/or downloads of the New Song by the general public.</p>

<h3>Breach by Licensee:</h3>
<p>The licensee shall have five (5) business days from its receipt of written notice by Producer and/or Producer’s authorized representative to cure any alleged breach of this Agreement by Licensee. Licensee’s failure to cure the alleged breach within five (5) business days shall result in Licensee’s default of its obligations, its breach of this Agreement, and at Producer’s sole discretion, the termination of Licensee’s rights hereunder.</p>
<p>If Licensee engages in the commercial exploitation and/or sale of the Beat or New Song outside of the manner and amount expressly provided for in this Agreement, Licensee shall be liable to Producer for monetary damages in an amount equal to any and all monies paid, collected by, or received by Licensee, or any third party on its behalf, in connection with such unauthorized commercial exploitation of the Beat and/or New Song.</p>
<p>Licensee recognizes and agrees that a breach or threatened breach of this Agreement by Licensee give rise to irreparable injury to Producer, which may not be adequately compensated by damages. Accordingly, in the event of a breach or threatened breach by the Licensee of the provisions of this Agreement, Producer may seek and shall be entitled to a temporary restraining order and a preliminary injunction restraining the Licensee from violating the provisions of this Agreement. Nothing herein shall prohibit Producer from pursuing any other available legal or equitable remedy from such breach or threatened breach, including but not limited to the recovery of damages from the Licensee. The Licensee shall be responsible for all costs, expenses or damages that Producer incurs as a result of any violation by the Licensee of any provision of this Agreement. Licensee’ obligation shall include court costs, litigation expenses, and reasonable attorneys’ fees.</p>

<h3>Warranties, Representations, and Indemnification:</h3>
<p>Licensee hereby agrees that Licensor has not made any guarantees or promises that the Beat fits the particular creative use or musical purpose intended or desired by the Licensee. The Beat, its sound recording, and the underlying musical composition embodied therein are licensed to the Licensee “as is” without warranties of any kind or fitness for a particular purpose.</p>
<p>Producer warrants and represents that he has the full right and ability to enter into this agreement, and is not under any disability, restriction, or prohibition with respect to the grant of rights hereunder. Producer warrants that the manufacture, sale, distribution, or other exploitation of the New Song hereunder will not infringe upon or violate any common law or statutory right of any person, firm, or corporation; including, without limitation, contractual rights, copyrights, and right(s) of privacy and publicity and will not constitute libel and/or slander.</p>
<p>Licensee warrants that the manufacture, sale, distribution, or other exploitation of the New Song hereunder will not infringe upon or violate any common law or statutory right of any person, firm, or corporation; including, without limitation, contractual rights, copyrights, and right(s) of privacy and publicity and will not constitute libel and/or slander. The foregoing notwithstanding, Producer undertakes no responsibility whatsoever as to any elements added to the New Song by Licensee, and Licensee indemnifies and holds Producer harmless for any such elements. Producer warrants that he did not “sample” (as that term is commonly understood in the recording industry) any copyrighted material or sound recordings belonging to any other person, firm, or corporation (hereinafter referred to as “Owner”) without first having notified Licensee.</p>
<p>The licensee shall have no obligation to approve the use of any sample thereof; however, if approved, any payment in connection therewith, including any associated legal clearance costs, shall be borne by Licensee. Knowledge by Licensee that “samples” were used by Producer which was not affirmatively disclosed by Producer to Licensee shall shift, in whole or in part, the liability for infringement or violation of the rights of any third party arising from the use of any such “sample” from Producer to Licensee.</p>
<p>Parties hereto shall indemnify and hold each other harmless from any and all third party claims, liabilities, costs, losses, damages or expenses as are actually incurred by the non-defaulting party and shall hold the non-defaulting party, free, safe, and harmless against and from any and all claims, suits, demands, costs, liabilities, loss, damages, judgments, recoveries, costs, and expenses; (including, without limitation, reasonable attorneys’ fees), which may be made or brought, paid, or incurred by reason of any breach or claim of breach of the warranties and representations hereunder by the defaulting party, their agents, heirs, successors, assigns and employees, which have been reduced to final judgment;</p>
<p>provided that prior to final judgment, arising out of any breach of any representations or warranties of the defaulting party contained in this agreement or any failure by defaulting party to perform any obligations on its part to be performed hereunder the non-defaulting party has given the defaulting party prompt written notice of all claims and the right to participate in the defense with counsel of its choice at its sole expense. In no event shall Artist be entitled to seek injunctive or any other equitable relief for any breach or non-compliance with any provision of this agreement.</p>

<h3>Miscellaneous:</h3>
<p>This Agreement constitutes the entire understanding of the parties and is intended as a final expression of their agreement and cannot be altered, modified, amended or waived, in whole or in part, except by written instrument (email being sufficient) signed by both parties hereto. This agreement supersedes all prior agreements between the parties, whether oral or written. Should any provision of this agreement be held to be void, invalid or inoperative, such decision shall not affect any other provision hereof, and the remainder of this agreement shall be effective as though such void, invalid or inoperative provision had not been contained herein.</p>
<p>No failure by Licensor hereto to perform any of its obligations hereunder shall be deemed a material breach of this agreement until the Licensee gives Licensor written notice of its failure to perform, and such failure has not been corrected within thirty (30) days from and after the service of such notice, or, if such breach is not reasonably capable of being cured within such thirty (30) day period, Licensor does not commence to cure such breach within said time period, and proceed with reasonable diligence to complete the curing of such breach thereafter. This agreement shall be governed by and interpreted in accordance with the laws of the <strong>{STATE_PROVINCE_COUNTRY}</strong> applicable to agreements entered into and wholly performed in said State, without regard to any conflict of laws principles.</p>
<p>You hereby agree that the exclusive jurisdiction and venue for any action, suit or proceeding based upon any matter, claim or controversy arising hereunder or relating hereto shall be in the state or federal courts located in the <strong>{STATE_PROVINCE_COUNTRY}</strong>. You shall not be entitled to any monies in connection with the Master(s) other than as specifically set forth herein.</p>
<p>All notices pursuant to this agreement shall be in writing and shall be given by registered or certified mail, return receipt requested (prepaid) at the respective addresses hereinabove set forth or such other address or addresses as may be designated by either party. Such notices shall be deemed given when received. A copy of all such notices sent to Producer shall be concurrently sent to [[lawfirm_name_address]]. Any notice mailed will be deemed to have been received five (5) business days after it is mailed; any notice dispatched by expedited delivery service will be deemed to be received two (2) business days after it is dispatched.</p>
<p>YOU ACKNOWLEDGE AND AGREE THAT YOU HAVE READ THIS AGREEMENT AND HAVE BEEN ADVISED BY US OF THE SIGNIFICANT IMPORTANCE OF RETAINING AN INDEPENDENT ATTORNEY OF YOUR CHOICE TO REVIEW THIS AGREEMENT ON YOUR BEHALF. YOU ACKNOWLEDGE AND AGREE THAT YOU HAVE HAD THE UNRESTRICTED OPPORTUNITY TO BE REPRESENTED BY AN INDEPENDENT ATTORNEY. IN THE EVENT OF YOUR FAILURE TO OBTAIN AN INDEPENDENT ATTORNEY OR WAIVER THEREOF, YOU HEREBY WARRANT AND REPRESENT THAT YOU WILL NOT ATTEMPT TO USE SUCH FAILURE AND/OR WAIVER as a basis to avoid any obligations under this agreement, or to invalidate this agreement or To render this agreement or any part thereof unenforceable.</p>
<p>This agreement may be executed in counterparts, each of which shall be deemed an original, and said counterparts shall constitute one and the same instrument. In addition, a signed copy of this agreement transmitted by facsimile or scanned into an image file and transmitted via email shall, for all purposes, be treated as if it was delivered containing an original manual signature of the party whose signature appears thereon and shall be binding upon such party as though an originally signed document had been delivered. Notwithstanding the foregoing, in the event that you do not sign this Agreement, your acknowledgment that you have reviewed the terms and conditions of this Agreement and your payment of the License Fee shall serve as your signature and acceptance of the terms and conditions of this Agreement.</p>
', 'sonaar-music'),


                ));
                $cmb_post_usageterms->add_field( array(
                    'name'          => esc_html__('Use the variables below to build your contract', 'sonaar-music'),
                    'type'          => 'title',
                    'id'            => 'var_usageterms_build_your_contract'
                ) );
                if(function_exists('acf')){
                    $cmb_post_usageterms->add_field( array(
                        'classes'       => 'srmp3-var-licensecontract',
                        'name'          => esc_html__('{acf_Your-ACF-ID-Here}', 'sonaar-music'),
                        'type'          => 'title',
                        'description'   => __( 'Want to use ACF Field from your product ?<br> use the ACF ID prefixed with {acf_xxxx}', 'sonaar-music' ),
                        'id'            => 'var_usageterms_acf'
                    ) );
                }
                $cmb_post_usageterms->add_field( array(
                    'classes'       => 'srmp3-var-licensecontract',
                    'name'          => esc_html__('{LICENSE_NAME}', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => __( 'Name of this license', 'sonaar-music' ),
                    'id'            => 'var_usageterms_license'
                ) );
                $cmb_post_usageterms->add_field( array(
                    'classes'       => 'srmp3-var-licensecontract',
                    'name'          => esc_html__('{CONTRACT_DATE}', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => __( 'Date of the contract', 'sonaar-music' ),
                    'id'            => 'var_usageterms_date'
                ) );
                $cmb_post_usageterms->add_field( array(
                    'classes'       => 'srmp3-var-licensecontract',
                    'name'          => esc_html__('{CUSTOMER_FULLNAME}', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => __( 'Customer fullname', 'sonaar-music' ),
                    'id'            => 'var_usageterms_fullname'
                ) );
                $cmb_post_usageterms->add_field( array(
                    'classes'       => 'srmp3-var-licensecontract',
                    'name'          => esc_html__('{CUSTOMER_EMAIL}', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => __( 'Customer email address', 'sonaar-music' ),
                    'id'            => 'var_usageterms_email'
                ) );
                $cmb_post_usageterms->add_field( array(
                    'classes'       => 'srmp3-var-licensecontract',
                    'name'          => esc_html__('{CUSTOMER_ADDRESS}', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => __( 'Customer address', 'sonaar-music' ),
                    'id'            => 'var_usageterms_address'
                ) );
                
                $cmb_post_usageterms->add_field( array(
                    'classes'       => 'srmp3-var-licensecontract',
                    'name'          => esc_html__('{PRODUCER_ALIAS}', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => __( 'Producer Name', 'sonaar-music' ),
                    'id'            => 'var_usageterms_producer_alias'
                ) );
                $cmb_post_usageterms->add_field( array(
                    'classes'       => 'srmp3-var-licensecontract',
                    'name'          => esc_html__('{PRODUCT_TITLE}', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => __( 'Title of the purchased product', 'sonaar-music' ),
                    'id'            => 'var_usageterms_product_title'
                ) );
                /*$cmb_post_usageterms->add_field( array(
                    'classes'       => 'srmp3-var-licensecontract',
                    'name'          => esc_html__('{PRODUCT_PRICE}', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => __( 'Price of the purchased product', 'sonaar-music' ),
                    'id'            => 'var_usageterms_product_price'
                ) );*/
                $cmb_post_usageterms->add_field( array(
                    'classes'       => 'srmp3-var-licensecontract',
                    'name'          => esc_html__('{PERFORMANCES_FOR_PROFIT}', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => __( 'Allowed for profit live performance ?', 'sonaar-music' ),
                    'id'            => 'var_usageterms_performances_for_profit'
                ) );
                $cmb_post_usageterms->add_field( array(
                    'classes'       => 'srmp3-var-licensecontract',
                    'name'          => esc_html__('{NUMBER_OF_RADIO_STATIONS}', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => __( 'Number of radio stations allowed', 'sonaar-music' ),
                    'id'            => 'var_usageterms_radio_station'
                ) );
                $cmb_post_usageterms->add_field( array(
                    'classes'       => 'srmp3-var-licensecontract',
                    'name'          => esc_html__('{DISTRIBUTE_COPIES}', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => __( 'Number of distribution copies allowed', 'sonaar-music' ),
                    'id'            => 'var_usageterms_dist_copies'
                ) );
                $cmb_post_usageterms->add_field( array(
                    'classes'       => 'srmp3-var-licensecontract',
                    'name'          => esc_html__('{AUDIO_STREAMS}', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => __( 'Number of audio streams allowed', 'sonaar-music' ),
                    'id'            => 'var_usageterms_audiostreams'
                ) );
                $cmb_post_usageterms->add_field( array(
                    'classes'       => 'srmp3-var-licensecontract',
                    'name'          => esc_html__('{MONETIZED_VIDEO_STREAMS_ALLOWED}', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => __( 'Number of monitized music videos streams allowed', 'sonaar-music' ),
                    'id'            => 'var_usageterms_musicvideosstreams'
                ) );
                /*$cmb_post_usageterms->add_field( array(
                    'classes'       => 'srmp3-var-licensecontract',
                    'name'          => esc_html__('{NONMONETIZED_VIDEO_STREAMS_ALLOWED}', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => __( 'Number of non-monitized music videos streams allowed', 'sonaar-music' ),
                    'id'            => 'var_usageterms_nonmonitezmusicvideosstreams'
                ) );*/
                $cmb_post_usageterms->add_field( array(
                    'classes'       => 'srmp3-var-licensecontract',
                    'name'          => esc_html__('{MONETIZED_MUSIC_VIDEOS}', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => __( 'Number of monitized music videos allowed', 'sonaar-music' ),
                    'id'            => 'var_usageterms_musicvideos'
                ) );
                $cmb_post_usageterms->add_field( array(
                    'classes'       => 'srmp3-var-licensecontract',
                    'name'          => esc_html__('{FREE_DOWNLOADS}', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => __( 'Number of free downloads allowed', 'sonaar-music' ),
                    'id'            => 'var_usageterms_freedownloads'
                ) );
                $cmb_post_usageterms->add_field( array(
                    'classes'       => 'srmp3-var-licensecontract',
                    'name'          => esc_html__('{STATE_PROVINCE_COUNTRY}', 'sonaar-music'),
                    'type'          => 'title',
                    'description'   => __( 'States/Provinces and Country of the seller', 'sonaar-music' ),
                    'id'            => 'var_usageterms_state'
                ) );

                 // Add the default price field to the term edit form.
                function register_attribute_srmp3_license_default_price_field() {
                    $attribute_taxonomies = wc_get_attribute_taxonomies();
                
                    if ($attribute_taxonomies) {
                        foreach ($attribute_taxonomies as $attribute) {
                            $taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);
                            add_action("{$taxonomy}_edit_form_fields", 'add_term_srmp3_license_default_price_field', 10, 1);
                            add_action("edited_{$taxonomy}", 'save_term_srmp3_license_default_price_field', 10, 1);
                        }
                    }
                }
                add_action('admin_init', 'register_attribute_srmp3_license_default_price_field', 999);
                
                // Add the default price field to the term edit form.
                function add_term_srmp3_license_default_price_field($term) {
                    $term_id = $term->term_id;
                    $default_price = get_term_meta($term_id, '_srmp3_license_default_price', true);
                    ?>
                    <tr class="form-field">
                        <th scope="row" valign="top"><label for="default_price"><?php _e('Default Price', 'sonaar-music-pro'); ?></label></th>
                        <td>
                            <input type="text" id="default_price" name="default_price" value="<?php echo esc_attr($default_price); ?>">
                            <p class="description"><?php _e('We use this default price when creating product variation from the MP3 Audio Player Pro bulk importer tool.', 'sonaar-music-pro'); ?></p>
                        </td>
                    </tr>
                    <?php
                }
                
                // Save the default price when updating the term.
                function save_term_srmp3_license_default_price_field($term_id) {
                    if (isset($_POST['default_price'])) {
                        $default_price = sanitize_text_field($_POST['default_price']);
                        update_term_meta($term_id, '_srmp3_license_default_price', $default_price);
                    }
                }
    
            }
        }
        
    }
    /**
    * return WC Product Variations
    **/
     function srmp3_get_terms_variations( $fields ) {
        $taxonomies = get_object_taxonomies('product');
        $result = array();
        foreach ( $taxonomies as $tax ) : 
            if ( strpos($tax, 'pa_' ) !== false ) : 

                $terms = get_terms($tax, [
                    'hide_empty' => false,
                ] );

                $product_var_label = str_replace('Product ', '', get_taxonomy($tax)->label);
                
                foreach( $terms as $term ) :
                    $result[$term->term_id] = $product_var_label . ' | ' . $term->name;
                endforeach;

            endif;
        endforeach;

        return $result;
    }
    /**
    * return CPT name "sr_playlist" or "album" for backward compatibility
    **/
    public function setPlaylistCPTName(){
        if( wp_get_theme()->template === 'sonaar' ){ // If Sonaar Theme is activated
			$cptName = 'sr_playlist';
		}else{
			$query = new WP_Query(array(
				'post_type' => 'album',
                'post_status' => array('publish')
			));
            if ($query->have_posts()) { 
                //CPT Album already present!
                $first_post = $query->posts[0];
                $meta = get_post_meta($first_post->ID, '', true);
              
                if( is_array($meta) && array_key_exists('artist_of_album', $meta) ){ //If album post has been created by sonaar theme
                    $cptName = 'sr_playlist';
                }else{
                    if( is_array($meta) && array_key_exists('alb_tracklist', $meta) ){
                        // alb_tracklist exist, so album post has been created by a old MP3 player version, keep the same CPT name which is album
                        $cptName = 'album';
                    }else{
                        // album post type already exist and is created by a third party plugin so use sr_playlist
                        $cptName = 'sr_playlist';
                    }
                }
            }else{
				$cptName = 'sr_playlist';
			}
		}
        return $cptName;
    }

    /**
    * Create custom posttype
    **/
    public function initCPT(){
        define('SR_PLAYLIST_CPT', $this->setPlaylistCPTName());
        delete_option('player_type');
        
	}
    
    public function srmp3_create_postType(){

        $podcast_shows_args = array(
            'public'            => true,
            'show_ui'           => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => false,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'show_tagcloud'     => true,
            'query_var'         => false,
            'rewrite'           => true,
            'hierarchical'      => false,
            'sort'              => false,
            'labels'            => array(
                'name'          => $this->sr_GetString('Podcast Show'),
                'all_items'     => esc_html_x('All Show',       'Taxonomy : all_items',     'sonaar-music'),
                'singular_name' => esc_html_x('Podcast Show',       'Taxonomy : singular_name', 'sonaar-music'),
                'add_new_item'  => esc_html_x('Add New Show',       'Taxonomy : add_new_item',  'sonaar-music'),
                'not_found'     => esc_html_x('No show founds.', 'Taxonomy : not_found',     'sonaar-music')
            ),
        );    
        $podcast_shows_slug = ( Sonaar_Music::get_option('sr_podcastshow_slug', 'srmp3_settings_widget_player') != null && Sonaar_Music::get_option('sr_podcastshow_slug', 'srmp3_settings_widget_player') != '') ? Sonaar_Music::get_option('sr_podcastshow_slug', 'srmp3_settings_widget_player') : 'podcast-show' ;       
        $podcast_shows_args['rewrite'] = array(
            'slug' => $podcast_shows_slug,
        );
        if ( Sonaar_Music::get_option('player_type', 'srmp3_settings_general') == 'podcast' ){
            register_taxonomy('podcast-show', SR_PLAYLIST_CPT, $podcast_shows_args);
        }

        $album_args = array(
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'has_archive'         => true,
            'query_var'           => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_icon'           => 'dashicons-format-audio',
            'exclude_from_search' => false,
            'delete_with_user'    => false,
            'show_in_rest'        => true,
        );
        
        $album_args['labels'] = array(
            'name'               => $this->sr_GetString('Playlists'),
            'singular_name'      => sprintf(esc_html__('%1$s (MP3 Audio Player Pro) ', 'sonaar-music'), ucfirst($this->sr_GetString('playlist'))),
            'name_admin_bar'     => esc_html__('Playlist', 'sonaar-music'),
            'menu_name'          => esc_html__('MP3 Player', 'sonaar-music'),
            'all_items'          => $this->sr_GetString('All Playlists'),
            'add_new'            => $this->sr_GetString('Add New'),
            'add_new_item'       => $this->sr_GetString('Add New'),
            'edit_item'          => $this->sr_GetString('Edit'),
            'new_item'           => esc_html__('New', 'sonaar-music'),
            'view_item'          => esc_html__('View', 'sonaar-music'),
            'search_items'       => esc_html__('Search', 'sonaar-music'),
            'not_found'          => esc_html__('No playlists or tracks found.', 'sonaar-music'),
            'not_found_in_trash' => esc_html__('No playlists or tracks found in the Trash.', 'sonaar-music'),
            'featured_image'     => $this->sr_GetString('Playlist Cover Image'),
            'set_featured_image' => esc_html__('Set Playlist Cover', 'sonaar-music'),
            'remove_featured_image' => $this->sr_GetString('Remove Playlist Cover')
        );
        
        $album_args['supports'] = array(
            'title',
            'editor',
            'excerpt',
            'author',
            'thumbnail',
            'comments'
        );
        
        $playlist_single_slug = ( Sonaar_Music::get_option('sr_singlepost_slug', 'srmp3_settings_widget_player') != null && Sonaar_Music::get_option('sr_singlepost_slug', 'srmp3_settings_widget_player') != '') ? Sonaar_Music::get_option('sr_singlepost_slug', 'srmp3_settings_widget_player') : $this->sr_GetString('album_slug') ;       
        $album_args['rewrite'] = array(
            'slug' => esc_attr($playlist_single_slug),
        );
        

        register_post_type( SR_PLAYLIST_CPT , $album_args);
        
        
        $album_category_args = array(
            'public'            => true,
            'show_ui'           => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => false,
            'show_admin_column' => true,
            'show_tagcloud'     => false,
            'query_var'         => false,
            'show_in_rest'      => true,
            'rewrite'           => true,
            'hierarchical'      => true,
            'sort'              => false,
            'labels'            => array(
                'name'          => __('Categories', 'sonaar-music'),
                'all_items'     => esc_html_x('All Categories',       'Taxonomy : all_items',     'sonaar-music'),
                'singular_name' => esc_html_x('Category',             'Taxonomy : singular_name', 'sonaar-music'),
                'add_new_item'  => esc_html_x('Add New Category',     'Taxonomy : add_new_item',  'sonaar-music'),
                'not_found'     => esc_html_x('No categories found.', 'Taxonomy : not_found',     'sonaar-music')
            ),
        );    
        $category_slug = ( Sonaar_Music::get_option('sr_category_slug', 'srmp3_settings_widget_player') != null && Sonaar_Music::get_option('sr_category_slug', 'srmp3_settings_widget_player') != '') ? Sonaar_Music::get_option('sr_category_slug', 'srmp3_settings_widget_player') : $this->sr_GetString('category_slug') ;       
        $album_category_args['rewrite'] = array(
            'slug' => $category_slug,
        );

        register_taxonomy('playlist-category', SR_PLAYLIST_CPT, $album_category_args);


        $album_tag_args = array(
            'hierarchical' => true,
            'show_admin_column' => true,
            'labels' => array(
                'name'              => __('Tags', 'sonaar-music'),
                'singular_name'     => __('Tag', 'sonaar-music'),
                'search_items'      => __('Search Tags', 'sonaar-music'),
                'all_items'         => __('All Tags', 'sonaar-music'),
                'parent_item'       => __('Parent Tag', 'sonaar-music'),
                'parent_item_colon' => __('Parent Tag', 'sonaar-music'),
                'edit_item'         => __('Edit Tag', 'sonaar-music'),
                'update_item'       => __('Update Tag', 'sonaar-music'),
                'add_new_item'      => __('Add New Tag', 'sonaar-music'),
                'new_item_name'     => __('New Tag Name', 'sonaar-music'),
                'menu_name'         => __('Tags', 'sonaar-music'),
            ),
            'public' => true,
            'rewrite' => array('slug' => $this->sr_GetString('tag_slug')),
            'show_in_rest'      => true
        );

        register_taxonomy('playlist-tag', SR_PLAYLIST_CPT, $album_tag_args);

        
        if ( function_exists('add_theme_support') ) {
            add_theme_support( 'post-thumbnails', array( SR_PLAYLIST_CPT ) );
        }

        if ( defined( 'WC_VERSION' ) && Sonaar_Music::get_option('wc_enable_licenses_cpt', 'srmp3_settings_woocommerce') == 'true' && function_exists( 'run_sonaar_music_pro' )) {
            $usage_terms_args = array(
                'public'              => true,
                'show_ui'             => true,
                'show_in_menu'        => 'edit.php?post_type=' . SR_PLAYLIST_CPT,
                'has_archive'         => false,
                'query_var'           => true,
                'show_in_nav_menus'   => true,
                'show_in_admin_bar'   => false,
                'menu_icon'           => 'dashicons-format-audio',
                'exclude_from_search' => true,
                'delete_with_user'    => false,
                'show_in_rest'        => true,
            );
            
            $usage_terms_args['labels'] = array(
                'name'               => $this->sr_GetString('Licenses & Contracts'),
                'singular_name'      => $this->sr_GetString('License'),
                //'menu_name'          => esc_html__('MP3 Player', 'sonaar-music'),
                'all_items'          => $this->sr_GetString('Music Licenses & Contracts'),
                'add_new'            => $this->sr_GetString('Add New'),
                'add_new_item'       => $this->sr_GetString('Add New'),
                'edit_item'          => $this->sr_GetString('Edit'),
                'new_item'           => esc_html__('New', 'sonaar-music'),
                'view_item'          => esc_html__('View', 'sonaar-music'),
                'search_items'       => esc_html__('Search', 'sonaar-music'),
            );
            
            $usage_terms_args['supports'] = array(
                'title',
            );
            
            $usage_terms_args['rewrite'] = array(
                'slug' => 'usage-terms',
            );
            

            register_post_type( 'usage-terms' , $usage_terms_args);
        }
        do_action( 'srmp3player_after_register_post_type' );
        flush_rewrite_rules(); 
    }
    
    public function register_widget(){
        register_widget( 'Sonaar_Music_Widget' );
    }
    

    public function srmp3_add_shortcode(){
    
        function sonaar_shortcode_audioplayer( $atts ) {
            
    		/* Enqueue Sonaar Music related CSS and Js file */
    		wp_enqueue_style( 'sonaar-music' );
    		wp_enqueue_style( 'sonaar-music-pro' );
    		wp_enqueue_script( 'sonaar-music-mp3player' );
    		wp_enqueue_script( 'sonaar-music-pro-mp3player' );
    		wp_enqueue_script( 'sonaar_player' );
    		
    		if ( function_exists('sonaar_player') ) {
    			add_action('wp_footer','sonaar_player', 12);
    		}
    		
            extract( shortcode_atts( array(
                'title' => '',
                'albums' => '',
                'show_playlist' => '',
                'hide_artwork' => '',
                'show_album_market' => '',
                'show_track_market' => '',
                'remove_player' => '',
                'enable_sticky_player' => '',
            ), $atts ) );
            
            ob_start();
            
            the_widget('Sonaar_Music_Widget', $atts, array('widget_id'=>'arbitrary-instance-'.uniqid(), 'before_widget'=>'<article class="iron_widget_radio">', 'after_widget'=>'</article>'));
                $output = ob_get_contents();
                ob_end_clean();
                
                return $output;
        }

        add_shortcode( 'sonaar_audioplayer', 'sonaar_shortcode_audioplayer' );
    }

    public function init_my_shortcode_button() {
        $button_slug = 'sonaar_audioplayer';
        $escapedVar = array(
                
            'div' => array(
                'class' => array(),
            ),
            'em' => array(),
            'strong' => array(),
            'a' => array(
                'href' => array(),
                'title' => array(),
                'target' => array()
            ),
            'img' => array(
                'src' => array(),
            ),
            'br' => array(),
            'i' => array(
                'class' => array(),
            ),
        );
        $js_button_data = array(
            'qt_button_text' => esc_html__( 'MP3 Player Shortcode Generator', 'sonaar-music' ),
            'button_tooltip' => esc_html__( 'MP3 Player Shortcode Generator', 'sonaar-music' ),
            'icon'           => 'dashicons-format-audio',
            'author'         => 'Sonaar Music',
            'authorurl'      => 'https://sonaar.io',
            'infourl'        => 'https://sonaar.io',
            'version'        => '1.0.0',
            'include_close'  => true, // Will wrap your selection in the shortcode
            'mceView'        => false, // Live preview of shortcode in editor. YMMV.
            'l10ncancel'     => esc_html__( 'Cancel', 'sonaar-music' ),
            'l10ninsert'     => esc_html__( 'Insert Shortcode', 'sonaar-music' ),
        );

        $shorcodeGeneratorFields = array();
        array_push($shorcodeGeneratorFields, 
            array(
                'name'              => esc_html__( 'Choose Playlist Type:', 'sonaar-music' ),
                'id'                => 'playlist_type',
                'type'              => 'select',
                'show_option_none'  => true,
                'options'           => array(
                    'predefined'        => esc_html__( 'Predefined Playlists', 'sonaar-music' ),
                    'feed'              => esc_html__( 'Audio URL inputs (advanced)', 'sonaar-music' ),
                ),
                'default'           => '',
            ),
            array(
                'name'        => esc_html__( 'Choose Playlist(s)' ),
                'id'          => 'albums',
                'type'        => 'post_search_text', // This field type
                'post_type'=> ( Sonaar_Music::get_option('srmp3_posttypes', 'srmp3_settings_general') != null ) ? Sonaar_Music::get_option('srmp3_posttypes', 'srmp3_settings_general') : SR_PLAYLIST_CPT,
                'desc'          => sprintf(__('Enter a comma separated list of post IDs. Enter <i>latest</i> to always load the latest published %1$s post. Click the magnifying glass to search for content','sonaar-music'), $this->sr_GetString('playlist') ),
                // Default is 'checkbox', used in the modal view to select the post type
                'select_type' => 'checkbox',
                // Will replace any selection with selection from modal. Default is 'add'
                'select_behavior' => 'add',
                'attributes'        => array(
                    'data-conditional-id'    => 'playlist_type',
                    'data-conditional-value' => 'predefined',
                ),
            ),
        
            array(
                'name'              => esc_html__( 'Playlist Title', 'sonaar-music' ),
                'id'                => 'playlist_title',
                'type'              => 'text',
                'attributes'        => array(
                    'data-conditional-id'    => 'playlist_type',
                    'data-conditional-value' => 'feed',
                ),
            ),
            array(
                'name'              => $this->sr_GetString('Playlist Cover Image'),
                'id'                => 'artwork',
                'type'              => 'file',
                'text'              => array(
                    'add_upload_file_text' => 'Add Image' // Change upload button text. Default: "Add or Upload File"
                ),
                // query_args are passed to wp.media's library query.
                'query_args'        => array(
                    // Or only allow gif, jpg, or png images
                    'type'  => array(
                         'image/gif',
                         'image/jpeg',
                         'image/png',
                    ),
                ),
                'attributes'        => array(
                    'data-conditional-id'    => 'playlist_type',
                    'data-conditional-value' => 'feed',
                ),
            ),
            array(
                'name'              => esc_html__( 'Track MP3 URLs', 'sonaar-music' ),
                'id'                => 'feed',
                'description'    => sprintf( wp_kses( __('eg: https://yourdomain.com/01.mp3 || https://yourdomain.com/02.mp3 . URL must be separated by || . See %1$sthis article%2$s for supported streaming providers.','sonaar-music'), $escapedVar), '<a href="https://sonaar.io/docs/supported-audio-streaming-providers/" target="_blank">', '</a>'),
                'type'              => 'textarea_small',
                'attributes'        => array(
                    'data-conditional-id'    => 'playlist_type',
                    'data-conditional-value' => 'feed',
                ),
            ),
            array(
                'name'              => esc_html__( 'Track Titles', 'sonaar-music' ),
                'id'                => 'feed_title',
                'description'       => esc_html__('eg: trackname 01 || trackname 02. Titles must be separated by ||', 'sonaar-music'),
                'type'              => 'textarea_small',
                'attributes'        => array(
                    'data-conditional-id'    => 'playlist_type',
                    'data-conditional-value' => 'feed',
                ),
            ),
            array(
                'name'              => esc_html__( 'Track Image URLs', 'sonaar-music' ),
                'id'                => 'feed_img',
                'description'       => esc_html__('eg: https://yourdomain.com/img01.jpg || https://yourdomain.com/img02.jpg . URL must be separated by ||', 'sonaar-music'),
                'type'              => 'textarea_small',
                'attributes'        => array(
                    'data-conditional-id'    => 'playlist_type',
                    'data-conditional-value' => 'feed',
                ),
            ),
            array(
                'name'              => esc_html__( 'Player Layout:', 'sonaar-music' ),
                'id'                => 'player_layout',
                'type'              => 'select',
                'show_option_none'  => true,
                'options'           => array(
                    'skin_float_tracklist'              => esc_html__( 'Floated', 'sonaar-music' ),
                    'skin_boxed_tracklist'              => esc_html__( 'Boxed', 'sonaar-music' ),
                ),
                'default'           => 'skin_float_tracklist',
                'attributes'    => array(
                    'data-conditional-id'       => 'playlist_type',
                    'data-conditional-value'    => wp_json_encode( array( 'predefined', 'feed' ) ),
                ),
            ));

        if( function_exists( 'run_sonaar_music_pro' ) ){
            array_push($shorcodeGeneratorFields, 
                array(
                    'name'              => esc_html__( 'Show Skip 15/30 seconds button:', 'sonaar-music' ),
                    'id'                => 'show_skip_bt',
                    'type'              => 'select',
                    'show_option_none'  => true,
                    'options'           => array(
                        'default'        => esc_html__( 'default', 'sonaar-music' ),
                        'true'              => esc_html__( 'Yes', 'sonaar-music' ),
                        'false'              => esc_html__( 'No', 'sonaar-music' ),
                    ),
                    'default'           => 'default',
                    'attributes'    => array(
                        'data-conditional-id'       => 'playlist_type',
                        'data-conditional-value'    => wp_json_encode( array( 'predefined', 'feed' ) ),
                    ),
                ),
                array(
                    'name'              => esc_html__( 'Show Shuffle button', 'sonaar-music'),
                    'id'                => 'show_shuffle_bt',
                    'type'              => 'select',
                    'show_option_none'  => true,
                    'options'           => array(
                        'default'        => esc_html__( 'default', 'sonaar-music' ),
                        'true'              => esc_html__( 'Yes', 'sonaar-music' ),
                        'false'              => esc_html__( 'No', 'sonaar-music' ),
                    ),
                    'default'           => 'default',
                    'attributes'    => array(
                        'data-conditional-id'       => 'playlist_type',
                        'data-conditional-value'    => wp_json_encode( array( 'predefined', 'feed' ) ),
                    ),
                ),
                array(
                    'name'              => esc_html__( 'Show Speed Lecture button (0.5x, 1x, 2x)', 'sonaar-music'),
                    'id'                => 'show_speed_bt',
                    'type'              => 'select',
                    'show_option_none'  => true,
                    'options'           => array(
                        'default'        => esc_html__( 'default', 'sonaar-music' ),
                        'true'              => esc_html__( 'Yes', 'sonaar-music' ),
                        'false'              => esc_html__( 'No', 'sonaar-music' ),
                    ),
                    'default'           => 'default',
                    'attributes'    => array(
                        'data-conditional-id'       => 'playlist_type',
                        'data-conditional-value'    => wp_json_encode( array( 'predefined', 'feed' ) ),
                    ),
                ),
                array(
                    'name'              => esc_html__( 'Show Volume button', 'sonaar-music'),
                    'id'                => 'show_volume_bt',
                    'type'              => 'select',
                    'show_option_none'  => true,
                    'options'           => array(
                        'default'        => esc_html__( 'default', 'sonaar-music' ),
                        'true'              => esc_html__( 'Yes', 'sonaar-music' ),
                        'false'              => esc_html__( 'No', 'sonaar-music' ),
                    ),
                    'default'           => 'default',
                    'attributes'    => array(
                        'data-conditional-id'       => 'playlist_type',
                        'data-conditional-value'    => wp_json_encode( array( 'predefined', 'feed' ) ),
                    ),
                ),
                array(
                    'name'              => esc_html__( 'Show Publish Date', 'sonaar-music'),
                    'id'                => 'show_publish_date',
                    'type'              => 'select',
                    'show_option_none'  => true,
                    'options'           => array(
                        'default'        => esc_html__( 'default', 'sonaar-music' ),
                        'true'              => esc_html__( 'Yes', 'sonaar-music' ),
                        'false'              => esc_html__( 'No', 'sonaar-music' ),
                    ),
                    'default'           => 'default',
                    'attributes'    => array(
                        'data-conditional-id'       => 'playlist_type',
                        'data-conditional-value'    => wp_json_encode( array( 'predefined', 'feed' ) ),
                    ),
                ),
                array(
                    'name'              => esc_html__( 'Show Number of Player Tracks', 'sonaar-music'),
                    'id'                => 'show_tracks_count',
                    'type'              => 'select',
                    'show_option_none'  => true,
                    'options'           => array(
                        'default'        => esc_html__( 'default', 'sonaar-music' ),
                        'true'              => esc_html__( 'Yes', 'sonaar-music' ),
                        'false'              => esc_html__( 'No', 'sonaar-music' ),
                    ),
                    'default'           => 'default',
                    'attributes'    => array(
                        'data-conditional-id'       => 'playlist_type',
                        'data-conditional-value'    => wp_json_encode( array( 'predefined', 'feed' ) ),
                    ),
                ),
                array(
                    'name'              => esc_html__( 'Show Meta Duration', 'sonaar-music'),
                    'id'                => 'show_meta_duration',
                    'type'              => 'select',
                    'show_option_none'  => true,
                    'options'           => array(
                        'default'        => esc_html__( 'default', 'sonaar-music' ),
                        'true'              => esc_html__( 'Yes', 'sonaar-music' ),
                        'false'              => esc_html__( 'No', 'sonaar-music' ),
                    ),
                    'default'           => 'default',
                    'attributes'    => array(
                        'data-conditional-id'       => 'playlist_type',
                        'data-conditional-value'    => wp_json_encode( array( 'predefined', 'feed' ) ),
                    ),
                ));
        };
        array_push($shorcodeGeneratorFields, 
            array(
                'name'              => esc_html__( 'Remove Progress Bar', 'sonaar-music' ),
                'id'                => 'hide_progressbar',
                'type'              => 'select',
                'show_option_none'  => true,
                'options'           => array(
                    'default'        => esc_html__( 'default', 'sonaar-music' ),
                    'true'              => esc_html__( 'Yes', 'sonaar-music' ),
                    'false'              => esc_html__( 'No', 'sonaar-music' ),
                ),
                'default'           => 'default',
                'attributes'    => array(
                    'data-conditional-id'       => 'playlist_type',
                    'data-conditional-value'    => wp_json_encode( array( 'predefined', 'feed' ) ),
                ),
            ),
            array(
                'name'          => esc_html__( 'Show Controls over Image Cover', 'sonaar-music' ),
                'id'            => 'display_control_artwork',
                'type'          => 'switch',
                'default'       => false,
                'attributes'    => array(
                    'data-conditional-id'       => 'playlist_type',
                    'data-conditional-value'    => wp_json_encode( array( 'predefined', 'feed' ) ),
                ),
            ),
            array(
                'name'              => esc_html__( 'Hide Cover Image', 'sonaar-music' ),
                'id'                => 'hide_artwork',
                'type'              => 'switch',
                'label'             => array('off'=> 'Show', 'on'=> 'Hide'), //default On, Off
                'default'           => false,
                'attributes'        => array(
                    'data-conditional-id'    => 'playlist_type',
                    'data-conditional-value' => 'predefined',
                ),
            ),
            array(
                'name'              => esc_html__( 'Show Tracklist', 'sonaar-music' ),
                'id'                => 'show_playlist',
                'type'              => 'switch',
                'label'             => array('on'=> 'Yes', 'off'=> 'No'), //default On, Off
                'default'           => true,
                'attributes'        => array(
                    'data-conditional-id'    => 'playlist_type',
                    'data-conditional-value'  => wp_json_encode( array( 'predefined', 'feed' ) ),
                ),
            ),
            array(
                'name'              => esc_html__( 'Show Track Store', 'sonaar-music' ),
                'id'                => 'show_track_market',
                'type'              => 'switch',
                'label'             => array('on'=> 'Yes', 'off'=> 'No'), //default On, Off
                'default'           => true,
                'attributes'        => array(
                    'data-conditional-id'    => 'playlist_type',
                    'data-conditional-value' => 'predefined',
                ),
            ),
            array(
                'name'              => esc_html__( 'Show Album Store', 'sonaar-music' ),
                'id'                => 'show_album_market',
                'type'              => 'switch',
                'label'             => array('on'=> 'Yes', 'off'=> 'No'), //default On, Off
                'default'           => true,
                'attributes'        => array(
                    'data-conditional-id'    => 'playlist_type',
                    'data-conditional-value' => 'predefined',
                ),
            ),
            array(
                'name'              => esc_html__( 'Remove Player', 'sonaar-music' ),
                'id'                => 'hide_timeline',
                'type'              => 'switch',
                'label'             => array('on'=> 'Yes', 'off'=> 'No'), //default On, Off
                'default'           => false,
                'attributes'        => array(
                    'data-conditional-id'    => 'playlist_type',
                    'data-conditional-value'  => wp_json_encode( array( 'predefined', 'feed' ) ),
                ),
            ));

        $additional_args = array(
            // Can be a callback or metabox config array
            'cmb_metabox_config'   => array(
                'id'                    => 'shortcode_'. esc_attr($button_slug),
                'fields'                => $shorcodeGeneratorFields,
                'show_on'           => array( 'key' => 'options-page', 'value' => esc_attr($button_slug) ),
            ),

            // Set the conditions of the shortcode buttons
            'conditional_callback'  => 'shortcode_button_only_pages',
        );
    
        if ( function_exists( 'run_sonaar_music_pro' ) ){
            $proParameters = array(
                array(
                    'name'          => esc_html__( 'Show Thumbnail for Each Track', 'sonaar-music' ),
                    'id'            => 'track_artwork',
                    'type'          => 'switch',
                    'default'       => false,
                    'attributes'    => array(
                        'data-conditional-id'       => 'playlist_type',
                        'data-conditional-value'    => wp_json_encode( array( 'predefined', 'feed' ) ),
                    ),
                ),
                array(
                    'name'          => esc_html__( 'Enable Scrollbar on Tracklist', 'sonaar-music' ),
                    'id'            => 'scrollbar',
                    'type'          => 'switch',
                    'default'       => false,
                    'attributes'    => array(
                        'data-conditional-id'       => 'playlist_type',
                        'data-conditional-value'    => wp_json_encode( array( 'predefined', 'feed' ) ),
                    ),

                ),
                array(
                    'name'          => esc_html__( 'Enable Shuffle', 'sonaar-music' ),
                    'id'            => 'shuffle',
                    'type'          => 'switch',
                    'default'       => false,
                    'attributes'    => array(
                        'data-conditional-id'       => 'playlist_type',
                        'data-conditional-value'    => wp_json_encode( array( 'predefined', 'feed' ) ),
                    ),
                ),
                array(
                    'name'          => esc_html__( 'Enable Sticky Player', 'sonaar-music' ),
                    'id'            => 'sticky_player',
                    'label'         => array('on'=> 'Yes', 'off'=> 'No'), //default On, Off
                    'type'          => 'switch',
                    'default'       => true,
                    'attributes'    => array(
                        'data-conditional-id'       => 'playlist_type',
                        'data-conditional-value'    => wp_json_encode( array( 'predefined', 'feed' ) ),
                    ),
                ),
            );

            foreach ($proParameters as &$parameter) {
                array_push( $additional_args['cmb_metabox_config']['fields'], $parameter);
            }
        }

        $button = new Shortcode_Button( $button_slug, $js_button_data, $additional_args );
    }


    /**
    * Callback dictates that shortcode button will only display if we're on a 'page' edit screen
    *
    * @return bool Expects a boolean value
    */
    function shortcode_button_only_pages() {
        if ( ! is_admin() || ! function_exists( 'get_current_screen' ) ) {
            return false;
        }
        
        $current_screen = get_current_screen();
        
        if ( ! isset( $current_screen->parent_base ) || $current_screen->parent_base != 'edit' ) {
            return false;
        }
        
        if ( ! isset( $current_screen->post_type ) || $current_screen->post_type != 'page' ) {
            return false;
        }
        
        // Ok, guess we're on a 'page' edit screen
        return true;
    }



    public function manage_album_columns ($columns){
        $iron_cols = array(
            'alb_shortcode'     => esc_html('')
        );
        
        $columns = Sonaar_Music::array_insert($columns, $iron_cols, 'date', 'before');
        
        $iron_cols = array('alb_icon' => '');
        
        $columns = Sonaar_Music::array_insert($columns, $iron_cols, 'title', 'before');
        
        $columns['date'] = esc_html__('Published', 'sonaar-music');   // Renamed date column
        
        return $columns;
    }


    public function manage_album_custom_column ($column, $post_id){
        switch ($column){                        
            case 'alb_shortcode':
                add_thickbox();
                
                echo '<div id="my-content-' . esc_attr($post_id) . '" style="display:none;">
                <h1>Playlist Shorcode</h1>
                <p>Here you can copy and paste the following shortcode anywhere your page</p>
                <textarea name="" id="" style="width:100%; height:150px;"> [sonaar_audioplayer title="' . esc_html(get_the_title( $post_id )) . '" albums="' . esc_attr($post_id) . '" hide_artwork="false" show_playlist="true" show_track_market="true" show_album_market="true" hide_timeline="true"][/sonaar_audioplayer]</textarea>
                </div>';
                echo '<a href="#TB_inline?width=600&height=300&inlineId=my-content-' . esc_attr($post_id) . '" class="thickbox"><span class="dashicons dashicons-format-audio"></span></a>';
                break;
            case 'alb_icon':
                $att_title = _draft_or_post_title();
                
                echo '<a href="' . esc_url(get_edit_post_link( $post_id, true )) . '" title="' . esc_attr( sprintf( esc_html__('Edit &#8220;%s&#8221;', 'sonaar-music'), $att_title ) ) . '">';
                
                if ( $thumb = get_the_post_thumbnail( $post_id, array(64, 64) ) ){
                    echo $thumb;
            }else{
                echo '<img width="46" height="60" src="' . esc_url(wp_mime_type_icon('image/jpeg')) . '" alt="">';
            }
            
            echo '</a>';
            
            break;
        }
    }
    
    public function prefix_admin_scripts( $hook ) {
		global $wp_version;
		if( version_compare( $wp_version, '5.4.2' , '>=' ) ) {
			wp_localize_script(
			  'wp-color-picker',
			  'wpColorPickerL10n',
			  array(
				'clear'            => esc_html__( 'Clear', 'sonaar-music'),
				'clearAriaLabel'   => esc_html__( 'Clear color', 'sonaar-music'),
				'defaultString'    => esc_html__( 'Default', 'sonaar-music'),
				'defaultAriaLabel' => esc_html__( 'Select default color', 'sonaar-music'),
				'pick'             => esc_html__( 'Select Color', 'sonaar-music'),
				'defaultLabel'     => esc_html__( 'Color value', 'sonaar-music')
			  )
			);
		}
	}

    public function srp_rename_theme_playlists_menu() {
        global $menu;
    
        // Define the main menu slugs for the post types
        $main_menu_slugs = [
            'edit.php?post_type=album',
            'edit.php?post_type=podcast',
            'edit.php?post_type=podcastshow'
        ];
    
        // Loop through the top-level menu items
        foreach ($menu as $key => $item) {
            // Check if the menu item's slug matches any of the post type slugs
            if (isset($item[2]) && in_array($item[2], $main_menu_slugs)) {
                // Rename the top-level menu item to include 'Deprecated (Use MP3 Player instead)'
                $menu[$key][0] = sprintf('%s %s', $item[0], __('[Obsolete] Use MP3 Player instead', 'sonaar-music'));
                $menu[$key][4] .= ' playlists-obsolete';
                // No need to break here, as we want to check all menu items
            }
        }
    }
    public function srp_rename_theme_add_custom_menu_styles() {
        echo '<style>
            .playlists-obsolete a.playlists-obsolete{
                text-decoration: unset!important;
            }
            .playlists-obsolete a, .acf-field-artist-hero-playlist{
                text-decoration: line-through!important;
                opacity:0.5;
                font-size:12px!important;
            }
        </style>';
    }

    public function srp_add_go_pro_submenu(){
		if ( function_exists( 'run_sonaar_music_pro' ) )
        return;
		
        $parent_slug = 'edit.php?post_type=' . SR_PLAYLIST_CPT; // Make options page a submenu item of the themes menu.
        $page_title = esc_html__( 'Go Pro', 'sonaar-music' );
        $menu_title =  '<span class="dashicons dashicons-star-filled" style="font-size: 14px"></span> ' . esc_html__( 'Go Pro', 'sonaar-music' ); // Falls back to 'title' (above).
        $capablity = 'manage_options';
        $menu_slug = '?page=go_pro';  
        $callback = $this->srp_handle_external_redirects();
        add_submenu_page( $parent_slug, $page_title, $menu_title, $capablity, $menu_slug, $callback, 999 );
    }
    public function srp_handle_external_redirects() {
        if ( empty( $_GET['page'] ) ) {
            return;
        }
        if ( 'go_pro' === $_GET['page'] ) {
            wp_redirect( 'https://sonaar.io/mp3-audio-player-pro/pricing/?utm_source=Sonaar+Music+Free+Plugin&utm_medium=plugin'  );
            die;
        }
    }
    /*public function checkAlbumVersion(){
        $albums = get_posts( array(
            'post_type' => SR_PLAYLIST_CPT,
            'post_status' => 'publish',
            'posts_per_page' => -1
    	));
    	foreach ( $albums as $album ) {
    		$oldVersion = ( get_post_meta($album->ID,'_alb_tracklist', true) !== '');

    		if ( $oldVersion ) {
                $meta = get_post_meta( $album->ID );
                $newList = array();

                for ($i=0; $i < $meta['alb_tracklist'][0] ; $i++) { 
                    
                    $newStructure = array(
                        'FileOrStream' =>  $meta['alb_tracklist_'. $i .'_FileOrStream'][0],
                        'track_mp3_id' =>  $meta['alb_tracklist_0_track_mp3'][0],
                        'track_mp3' =>  $meta['alb_tracklist_'. $i .'_track_mp3'][0],
                        'stream_link' =>  $meta['alb_tracklist_'. $i .'_stream_link'][0],
                        'stream_title' =>  $meta['alb_tracklist_'. $i .'_stream_title'][0],
                        'stream_artist' =>  $meta['alb_tracklist_'. $i .'_stream_artist'][0],
                        'stream_album' =>  $meta['alb_tracklist_'. $i .'_stream_album'][0],
                        'song_store_list' => array()
                    );

                    for ($a=0; $a < $meta['alb_tracklist_' . $i . '_song_store_list'][0] ; $a++) {
                        $newStructure['song_store_list'][$a] = array(
                            'store-icon'=> 'fab ' . $meta['alb_tracklist_' . $i . '_song_store_list_' . $a . '_song_store_icon'][0],
                            'store-name'=> $meta['alb_tracklist_' . $i . '_song_store_list_' . $a . '_song_store_name'][0],
                            'store-link'=> $meta['alb_tracklist_' . $i . '_song_store_list_' . $a . '_store_link'][0],
                            'store-target'=> $meta['alb_tracklist_' . $i . '_song_store_list_' . $a . '_song_store_target'][0],
                        );
                    }
                    $newList[$i] = $newStructure; 
                }
                    
                delete_post_meta( $album->ID, '_alb_tracklist' );
                update_post_meta( $album->ID, 'alb_tracklist', $newList );

            }
        }
    }*/

    public function yourprefix_remove_submenus( $submenu_file ) {
        global $plugin_page;
        $slug = 'edit.php?post_type=' . SR_PLAYLIST_CPT;
        $hidden_submenus = array(
            'srmp3_settings_widget_player' => true,
            'srmp3_settings_sticky_player' => true,
            'srmp3_settings_audiopreview' => true,
            'srmp3_settings_woocommerce' => true,
            'srmp3_settings_popup' => true,
            'srmp3_settings_stats' => true,
            'srmp3_settings_favorites' => true,
            'srmp3_settings_share' => true,
            'sonaar_music_pro_tools' => true,
            'srmp3-import-page' => true,
        );
       
        // Select another submenu item to highlight (optional).
        if($plugin_page == 'sonaar_music_pro_tools' || $plugin_page == 'srmp3-import-page'){
            $submenu_file = 'srmp3_settings_tools';
        }else if ( $plugin_page && isset( $hidden_submenus[ $plugin_page ] ) ) {
            $submenu_file = 'srmp3_settings_general';
        }
    
        // Hide the submenu.
        foreach ( $hidden_submenus as $submenu => $unused ) {
            remove_submenu_page( $slug , $submenu );
        }

        return $submenu_file;
    }
    

}