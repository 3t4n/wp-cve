<?php

class Class_Pi_Edd_Extra{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'extras';

    private $tab_name = "Extras (PRO)";

    private $setting_key = 'extra_setting';

    public $tab;


    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;
        
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        add_action($this->plugin_name.'_tab', array($this,'tab'),9);

        $this->settings = array(
            array('field'=>'title'),
            
        );

        if(isset($_GET['disable_estimate']) && $_GET['disable_estimate'] == 1 && $this->this_tab == $this->active_tab){
           add_action('wp_loaded', array($this,'disableEstimateForAllProducts'));
        }

        if(isset($_GET['enable_estimate']) && $_GET['enable_estimate'] == 1 && $this->this_tab == $this->active_tab){
            add_action('wp_loaded', array($this,'enableEstimateForAllProducts'));
         }

        $this->register_settings();
        
        if(PISOL_EDD_DELETE_SETTING){
            $this->delete_settings();
        }
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
        $this->tab_name = __('Extras (PRO)','pi-edd');
        ?>
        <a class="pro-feature px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ); ?>">
        <span class="dashicons dashicons-external"></span> <?php echo $this->tab_name; ?> 
        </a>
        <?php
    }

    function tab_content(){
       ?>
       
        <form method="post" action="options.php"  class="free-version p-4 pisol-setting-form">
        <?php settings_fields( $this->setting_key ); 
        $dates = get_option("pi_edd_holidays");
        ?>
        <input type="hidden" id="pi_edd_holidays" name="pi_edd_holidays" value="<?php echo $dates; ?>">
        
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form_edd($setting, $this->setting_key);
            }
        ?>
        <p style="font-size:18px;">By default estimate are shown on all the product, all though there is option to disable the estimate for specific product. But if you have large number of products and you only want to show estimate on some of the product then you can use this button, this will disable the estimate on all the products. Once disabled then you can go inside each product where you want to show the estimate and enable the estimate option</p>
        <a href="<?php echo admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab.'&disable_estimate=1' ); ?>" class="btn btn-primary my-4 btn-lg">Disable Estimate option for all product</a>
            <hr>
        <p style="font-size:18px;">This enables the estimate for all the product, this button is mostly not needed as by default estimate is enabled for all the products</p>
        <a href="<?php echo admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab.'&enable_estimate=1' ); ?>" class="btn btn-primary my-4  btn-lg">Enable Estimate option for all product</a>
        </form>

       <?php
    }

    function disableEstimateForAllProducts(){
       
    }

    function enableEstimateForAllProducts(){
       
    }
}

new Class_Pi_Edd_Extra($this->plugin_name);