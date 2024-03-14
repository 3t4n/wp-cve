<?php

class pisol_cefw_options{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'extra_options';

    private $tab_name = "Extra settings";

    private $setting_key = 'cefw_extra_setting';
    
    

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;


        $this->settings = array(
           

            array('field'=>'pisol_cefw_optional_services', 'label'=>__('Optional services label', 'conditional-extra-fees-woocommerce'), 'desc'=>__('This label is shown above the fees', 'conditional-extra-fees-woocommerce'), 'type'=>'text', 'default'=>__('Optional services', 'conditional-extra-fees-woocommerce')),

            array('field'=>'pisol_cefw_fees_option_cart', 'label'=>__('Show optional fees checkbox on cart page', 'conditional-extra-fees-woocommerce'), 'desc'=>__('If enabled it will show the optional fees checkbox on the cart page as well', 'conditional-extra-fees-woocommerce'), 'type'=>'switch', 'default'=>"0", 'pro'=>true),
        );
        
        $this->tab = filter_input( INPUT_GET, 'tab' );
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }


        add_action($this->plugin_name.'_tab', array($this,'tab'),10);

       
        $this->register_settings();

    }

    
    function delete_settings(){
        foreach($this->settings as $setting){
            delete_option( $setting['field'] );
        }
    }

    function register_settings(){   

        foreach($this->settings as $setting){
            register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        $page = filter_input( INPUT_GET, 'page' );
        ?>
        <a class=" px-3 py-2 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.$page.'&tab='.$this->this_tab ); ?>">
            <?php _e( $this->tab_name); ?> 
        </a>
        <?php
    }

    function tab_content(){
        
       ?>
        <form method="post" action="options.php"  class="pisol-setting-form">
        <?php settings_fields( $this->setting_key ); ?>
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form_sn($setting, $this->setting_key);
            }
        ?>
        <input type="submit" class="mt-3 btn btn-primary btn-sm" value="Save Option" />
        </form>
       <?php
    }
}

new pisol_cefw_options($this->plugin_name);