<?php
/**
 * LaStudioKit Elementor Extension Module.
 *
 * Version: 1.0.0
 */

namespace LaStudioKitExtensions\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

use LaStudioKitExtensions\Module_Base;

class Module extends Module_Base {

    /**
     * @var array
     */
    public $active_extensions;

    /**
     * @var array
     */
    protected static $cacheIconFonts = [];
    /**
     * @var array
     */
    public static $iconResources = [];

    public function __construct()
    {
        $this->active_extensions = lastudio_kit_settings()->get('avaliable_extensions', [
            'motion_effects'        => true,
            'floating_effects'      => true,
            'css_transform'         => true,
            'wrapper_link'          => true,
            'element_visibility'    => true,
            'custom_css'            => true,
        ]);

        $this->init_extensions();

        add_action( 'elementor/controls/register',  array( $this, 'register_controls' ) );
        add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ) );
        add_action( 'elementor/ajax/register_actions', [ $this, 'register_ajax_actions' ] );
        add_action( 'elementor/icons_manager/additional_tabs', [ $this, 'enqueue_custom_icon_fonts' ] );

        // Register private actions
        $priv_actions = array(
            'lakit_theme_search_posts' => array( $this, 'search_posts' ),
            'lakit_theme_search_pages' => array( $this, 'search_pages' ),
            'lakit_theme_search_cats'  => array( $this, 'search_cats' ),
            'lakit_theme_search_tags'  => array( $this, 'search_tags' ),
            'lakit_theme_search_terms' => array( $this, 'search_terms' ),
        );

        foreach ( $priv_actions as $tag => $callback ) {
            add_action( 'wp_ajax_' . $tag, $callback );
        }

        add_action('lastudiokit/renderIconSVGSprite', [ $this, 'getRenderSVGSprite' ]);
        add_action('wp_footer', [ $this, 'getRenderSVGSprite' ], 9999);
    }

    public static function is_active(){
        return true;
    }

    public function init_extensions(){

        $maps = [
            'motion_effects'        => 'Motion_Effects',
            'floating_effects'      => 'Floating_Effects',
            'css_transform'         => 'CSS_Transform',
            'wrapper_link'          => 'Wrapper_Link',
            'element_visibility'    => 'Element_Visibility',
            'custom_css'            => 'Custom_CSS',
        ];

        foreach ($this->active_extensions as $active_extension => $status){
            if(filter_var( $status, FILTER_VALIDATE_BOOLEAN ) && isset($maps[$active_extension])){
                $class_name =  $maps[$active_extension];
                $instance = __NAMESPACE__ . '\\' . $class_name;
                new $instance;
            }
        }

        new General_Extensions();
        new Header_Vertical();

    }

    /**
     * Register new controls.
     *
     * @param  object $controls_manager Controls manager instance.
     * @return void
     */
    public function register_controls( $controls_manager ) {

        $controls_manager->add_group_control( Controls\Group_Control_Query::get_type(),      new Controls\Group_Control_Query() );
        $controls_manager->add_group_control( Controls\Group_Control_Related::get_type(),    new Controls\Group_Control_Related() );
        $controls_manager->add_group_control( Controls\Group_Control_Box_Style::get_type(),  new Controls\Group_Control_Box_Style() );
        $controls_manager->add_group_control( Controls\Group_Control_Box_Shadow::get_type(),  new Controls\Group_Control_Box_Shadow() );

        $controls_manager->register( new Controls\Control_Query() );
        $controls_manager->register( new Controls\Control_Search() );

    }

    /**
     * @param \Elementor\Core\Common\Modules\Ajax\Module $ajax_manager
     */
    public function register_ajax_actions( $ajax_manager ){

        if( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'elementor_ajax' ){
            $need_update = false;
            $action_require = ['app_site_editor_template_types', 'get_revisions'];
            $request = json_decode( stripslashes( $_REQUEST['actions'] ), true );
            foreach ($request as $k => &$value){
                if( in_array($k, $action_require) && !isset($value['data'])){
                    $value['data'] = [];
                    $need_update = true;
                }
            }
            if($need_update){
                $_REQUEST['actions'] = json_encode($request);
            }
        }

        $class_query = Classes\Query_Control::get_instance();
        $ajax_manager->register_ajax_action( 'lastudiokit_query_control_value_titles', [ $class_query, 'ajax_posts_control_value_titles' ] );
        $ajax_manager->register_ajax_action( 'lastudiokit_query_control_filter_autocomplete', [ $class_query, 'ajax_posts_filter_autocomplete' ] );

    }

    /**
     * Enqueue editor scripts.
     */
    public function enqueue_editor_scripts() {
        wp_enqueue_script(
            'lastudio-kit-ext-editor',
            lastudio_kit()->plugin_url('includes/extensions/elementor/assets/js/editor.js'),
            array( 'jquery' ),
            lastudio_kit()->get_version(true),
            true
        );
    }

    /**
     * Enqueue custom icon fonts
     *
     * @param $tabs
     */
    public function enqueue_custom_icon_fonts( $tabs ){

        if(!lastudio_kit()->get_theme_support('elementor::inline-icon')) {
            $tabs['dlicon'] = [
                'name' => 'dlicon',
                'label' => __( 'DL Icons', 'lastudio-kit' ),
                'url' =>  lastudio_kit()->plugin_url('includes/extensions/elementor/assets/css/dlicon.css'),
                'prefix' => '',
                'displayPrefix' => 'dlicon',
                'labelIcon' => 'fas fa-star',
                'ver' => '1.0.0',
                'fetchJson' => lastudio_kit()->plugin_url('includes/extensions/elementor/assets/fonts/dlicon.json'),
                'native' => false,
                'render_callback' => [ $this, 'render_callback_icon__dlicon' ]
            ];
        }
        else{
            $tabs['dlicon'] = [
                'name' => 'dlicon',
                'label' => __( 'DL Icons', 'lastudio-kit' ),
                'enqueue' => [ lastudio_kit()->plugin_url('includes/extensions/elementor/assets/css/dlicon.css') ],
                'prefix' => '',
                'displayPrefix' => 'dlicon',
                'labelIcon' => 'fas fa-star',
                'ver' => '1.0.0',
                'fetchJson' => lastudio_kit()->plugin_url('includes/extensions/elementor/assets/fonts/dlicon.json'),
                'native' => false,
                'render_callback' => [ $this, 'render_callback_icon__dlicon' ]
            ];
        }

        if(!lastudio_kit()->get_theme_support('elementor::inline-icon')) {

            $tabs['splicon'] = [
                'name' => 'splicon',
                'label' => __('Simple Line Icon', 'lastudio-kit'),
                'url' => lastudio_kit()->plugin_url('includes/extensions/elementor/assets/css/simple-line-icons.css'),
                'prefix' => 'icon-',
                'displayPrefix' => 'splicon',
                'labelIcon' => 'fas fa-star',
                'ver' => '1.0.0',
                'fetchJson' => lastudio_kit()->plugin_url('includes/extensions/elementor/assets/fonts/simple-line-icons.json'),
                'native' => false
            ];

            $tabs['icofont'] = [
                'name' => 'icofont',
                'label' => __('IcoFont', 'lastudio-kit'),
                'url' => lastudio_kit()->plugin_url('includes/extensions/elementor/assets/css/icofont.css'),
                'prefix' => 'icofont-',
                'displayPrefix' => 'icofont',
                'labelIcon' => 'fas fa-star',
                'ver' => '1.0.0',
                'fetchJson' => lastudio_kit()->plugin_url('includes/extensions/elementor/assets/fonts/icofont.json'),
                'native' => false
            ];
        }

        if(lastudio_kit()->get_theme_support('elementor::lastudio-icon')){
            $tabs['lastudioicon'] = [
                'name' => 'lastudioicon',
                'label' => esc_html__( 'LaStudio Icons', 'lastudio-kit' ),
                'prefix' => 'lastudioicon-',
                'displayPrefix' => '',
                'labelIcon' => 'fas fa-star',
                'ver' => '1.0.1',
                'fetchJson' => lastudio_kit()->plugin_url('includes/extensions/elementor/assets/fonts/LaStudioIcons.json'),
                'native' => false,
                'render_callback' => [ $this, 'render_callback_icon__laicon' ]
            ];
        }
        else{
            $tabs['lastudioicon'] = [
                'name' => 'lastudioicon',
                'label' => __( 'LaStudio Icons', 'lastudio-kit' ),
                'url' =>  lastudio_kit()->plugin_url('includes/extensions/elementor/assets/css/lastudioicon.css'),
                'prefix' => 'lastudioicon-',
                'displayPrefix' => '',
                'labelIcon' => 'fas fa-star',
                'ver' => '1.0.1',
                'fetchJson' => lastudio_kit()->plugin_url('includes/extensions/elementor/assets/fonts/LaStudioIcons.json'),
                'native' => false,
                'render_callback' => [ $this, 'render_callback_icon__laicon' ]
            ];
        }

        return $tabs;
    }

    /**
     * Serch page
     *
     * @return [type] [description]
     */
    public function search_pages() {

        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json( array() );
        }

        $query = isset( $_GET['q'] ) ? esc_attr( $_GET['q'] ) : '';
        $ids   = isset( $_GET['ids'] ) ? esc_attr( $_GET['ids'] ) : array();

        wp_send_json( array(
            'results' => lastudio_kit_helper()->search_posts_by_type( 'page', $query, $ids ),
        ) );
    }

    /**
     * Serch post
     *
     * @return [type] [description]
     */
    public function search_posts() {

        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json( array() );
        }

        $query     = isset( $_GET['q'] ) ? esc_attr( $_GET['q'] ) : '';
        $post_type = isset( $_GET['preview_post_type'] ) ? esc_attr( $_GET['preview_post_type'] ) : ( isset($_GET['post_type']) ? esc_attr( $_GET['post_type'] ) : 'post' );
        $ids       = isset( $_GET['ids'] ) ? esc_attr( $_GET['ids'] ) : array();
	    $exclude   = ! empty( $_GET['exclude'] ) ? explode( ',', $_GET['exclude'] ) : array();

        wp_send_json( array(
            'results' => lastudio_kit_helper()->search_posts_by_type( $post_type, $query, $ids, $exclude )
        ) );

    }

    /**
     * Serch category
     *
     * @return [type] [description]
     */
    public function search_cats() {

        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json( array() );
        }

        $query = isset( $_GET['q'] ) ? esc_attr( $_GET['q'] ) : '';
        $ids   = isset( $_GET['ids'] ) ? esc_attr( $_GET['ids'] ) : array();

        wp_send_json( array(
            'results' => lastudio_kit_helper()->search_terms_by_tax( 'category', $query, $ids ),
        ) );

    }

    /**
     * Serch tag
     *
     * @return [type] [description]
     */
    public function search_tags() {
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json( array() );
        }
        $query = isset( $_GET['q'] ) ? esc_attr( $_GET['q'] ) : '';
        $ids   = isset( $_GET['ids'] ) ? esc_attr( $_GET['ids'] ) : array();
        wp_send_json(array(
            'results' => lastudio_kit_helper()->search_terms_by_tax( 'post_tag', $query, $ids ),
        ));
    }

    /**
     * Serach terms from passed taxonomies
     * @return [type] [description]
     */
    public function search_terms() {

        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json( array() );
        }

        $query = isset( $_GET['q'] ) ? esc_attr( $_GET['q'] ) : '';

        $tax = '';

        if ( isset( $_GET['conditions_archive-tax_tax'] ) ) {
            $tax = $_GET['conditions_archive-tax_tax'];
        }

        if ( isset( $_GET['conditions_singular-post-from-tax_tax'] ) ) {
            $tax = $_GET['conditions_singular-post-from-tax_tax'];
        }

        $tax = explode( ',', $tax );

        $ids = isset( $_GET['ids'] ) ? esc_attr( $_GET['ids'] ) : array();

        wp_send_json( array(
            'results' => lastudio_kit_helper()->search_terms_by_tax( $tax, $query, $ids ),
        ) );

    }

    public function addIconFontCache( $type, $icon )  {
        $tmp = explode(' ', $icon);
        if(isset($tmp[1])){
            $icon = $tmp[1];
        }
        $icon = str_replace('lastudioicon-', '', $icon);

        $oldData = self::$cacheIconFonts;
        if(empty($oldData[$type])){
            $oldData[$type] = [ $icon ];
        }
        else{
            if(!in_array($icon, $oldData[$type])){
                $oldData[$type][] = $icon;
            }
        }
        self::$cacheIconFonts = $oldData;
    }

    private function render_callback_icon( $icon, $attributes, $tag, $type){
        $this->addIconFontCache( $type, $icon['value']);
        return $this->maybeRenderSVGSpriteIcon( $icon['value'], $type );
    }

    public function render_callback_icon__dlicon( $icon, $attributes, $tag){
        return $this->render_callback_icon($icon, $attributes, $tag, 'dlicon');
    }
    public function render_callback_icon__laicon( $icon, $attributes, $tag ){
        return $this->render_callback_icon($icon, $attributes, $tag, 'LaStudioIcons');
    }

    public function loadSVGJSON(){
        if( !empty(self::$iconResources) ){
            return self::$iconResources;
        }
        $svg = [
            'dlicon' => [
                'version' => '1.0.0',
                'path' => lastudio_kit()->plugin_path('includes/extensions/elementor/assets/fonts/dlicon-svg.json')
            ],
            'LaStudioIcons' => [
                'version' => '1.0.1',
                'path' => lastudio_kit()->plugin_path('includes/extensions/elementor/assets/fonts/LaStudioIcons-svg.json')
            ]
        ];
        $cache_key = 'lakit-icons-data';
        $cache = get_transient($cache_key);
        if(empty($cache)){
            $saved_data = [];
            foreach ($svg as $k => $item){
                $fileData = \Elementor\Utils::file_get_contents($item['path']);
                $saved_data[$k] = [
                    'version' => $item['version'],
                    'data' => !empty($fileData) ? $fileData : ''
                ];
            }
            set_transient($cache_key, $saved_data, WEEK_IN_SECONDS);
            $cache = $saved_data;
        }
        else{
            $updated = false;
            $newData = [];
            foreach ($cache as $k => $item){
                if(isset($svg[$k]) && $item['version'] !== $svg[$k]['version']){
                    $updated = true;
                    $fileData = \Elementor\Utils::file_get_contents($svg[$k]['path']);
                    $item = [
                        'version' => $svg[$k]['version'],
                        'data' => !empty($fileData) ? $fileData : ''
                    ];
                }
                $newData[$k] = $item;
            }
            if($updated){
                set_transient($cache_key, $newData, WEEK_IN_SECONDS);
                $cache = $newData;
            }
        }
        self::$iconResources = $cache;
        return $cache;
    }

    public function maybeRenderSVGSpriteIcon( $icon = '', $type = '' ){
        if(empty($type) || empty($type)){
            return '';
        }
        $tmp = explode(' ', $icon);
        if(isset($tmp[1])){
            $icon = $tmp[1];
        }
        $icon = str_replace('lastudioicon-', '', $icon);
        $output = '';
        $cacheSVG = $this->loadSVGJSON();
        $svgData = [];
        if(empty($cacheSVG)){
            return '';
        }
        foreach ($cacheSVG as $k => $v){
            $svgData[$k] = !empty($v['data']) ? json_decode($v['data'], true) : [];
        }
        if( isset( $svgData[$type][$icon] )){
            $_id = $type . '___' . str_replace('-', '_', $icon);
            $_w = $svgData[$type][$icon][0];
            $_h = $svgData[$type][$icon][1];
            $_d = $svgData[$type][$icon][4];
            if(wp_doing_ajax() || lastudio_kit()->elementor()->editor->is_edit_mode()) {
                $output = sprintf('<svg xmlns="http://www.w3.org/2000/svg" width="%1$s" height="%2$s" viewBox="0 0 %1$s %2$s" class="lakit-font-icon-svg" data-icon-name="%5$s" data-icon-type="%4$s"><path d="%3$s" fill="currentColor"/></svg>', $_w, $_h, $_d, $type, $icon);
            }
            else{
                $output = sprintf('<svg xmlns="http://www.w3.org/2000/svg" width="%1$s" height="%2$s" viewBox="0 0 %1$s %2$s" class="lakit-font-icon-svg"><use xlink:href="#%3$s"></use></svg>', $_w, $_h, $_id);
            }
        }
        return $output;
    }

    public function getRenderSVGSprite() {
        $cacheFonts = self::$cacheIconFonts;
        $cacheSVG = $this->loadSVGJSON();
        $svgData = [];
        foreach ($cacheSVG as $k => $v){
            $svgData[$k] = !empty($v['data']) ? json_decode($v['data'], true) : [];
        }
        $output = [];
        foreach ($cacheFonts as $type => $icons){
            foreach ($icons as $icon){
                if( isset( $svgData[$type][$icon] )){
                    $_id = $type . '___' . str_replace('-', '_', $icon);
                    $_path = $svgData[$type][$icon][4];
                    $output[] = sprintf('<g id="%1$s"><path d="%2$s"/></g>', $_id, $_path);
                }
            }
        }
        if(!empty($output)){
            printf('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 0 0" width="0" height="0" focusable="false" role="none" style="visibility: hidden; position: absolute; left: -9999px; overflow: hidden;" class="hidden"><defs>%1$s</defs></svg>', join('', $output));
        }
    }
}
