<?php
if (!defined('ABSPATH')) die('-1');

class VCE_component {

    protected $vars;
    protected $dataId;

    function __construct() {

        // We safely integrate with VC with this hook
        $this->dataId = uniqid('vce_');

        // Register CSS and JS
        add_action( 'init', array( $this, 'integrateWithVC' ) );
        //add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    public function componentClass() {
      return get_class( $this );
    }

    public function integrateWithVC() {
        $className = (string) str_replace('VCE_', '', $this->componentClass() );

        global $fmc_widgets_integration;
        $info = $fmc_widgets_integration[$className];

      if(class_exists($className)) {
          $component = new $className();
          $this->vars = $component->integration_view_vars();
          if (empty($this->vars)) return;
      }
        // Check if WPBakery Page Builder is installed
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            // Display notice that Extend WPBakery Page Builder is required
            add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
            return;
        }

        /*
        Add your WPBakery Page Builder logic here.
        Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => $this->setTitle($info),
            "description" => $info['description'],
            "base" => $info['shortcode'],
            "class" => "",
            "controls" => "full",
            "show_settings_on_create" => "false",
            "icon" => "flexmls_pin", // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => 'FlexMLS®',
            'admin_enqueue_js' => $this->initLocation(),
            "params" => array_merge($this->setParams(), $this->setDesignOptions()),
        ) );
    }

    protected function setTitle($info){
        $title = str_replace('&reg;', '®', $info['title']);
        return $title;
    }

    protected function setParams(){
        extract($this->vars);
        $fmc_params = array();
        return $fmc_params;
    }

    protected function modify_array($arr){
        $options = array();
        if(!is_array($arr)) {
            return $options;
        }
        foreach ($arr as $value => $label) {
          $options[] = array("value" => $value, "label" => $label);
        };

        return $options;
    }

    protected function setDesignOptions(){
        $options = array(
            array(
                'type' => 'css_editor',
                'heading' => esc_html__( 'CSS box', 'js_composer' ),
                'param_name' => 'css',
                'group' => esc_html__( 'Design Options', 'js_composer' ),
            ),
        );
        return $options;
    }
    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
      wp_register_style( 'vc_extend_style', plugins_url('assets/vc_extend.css', __FILE__) );
      //wp_enqueue_script( 'vc_extend_js', plugins_url('assets/vc_extend.js', __FILE__), array('jquery') );

      // If you need any javascript files on front end, here is how you can load them.
    }

    protected function initLocation(){
        return '';//array(plugins_url('../init_widgets.js', __FILE__));
    }

    /*
    Show notice if your plugin is activated but Visual Composer is not
    */
    public function showVcVersionNotice() {
        $plugin_data = get_plugin_data(__FILE__);
        echo '
        <div class="updated">
          <p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'vc_extend'), $plugin_data['Name']).'</p>
        </div>';
    }
}
