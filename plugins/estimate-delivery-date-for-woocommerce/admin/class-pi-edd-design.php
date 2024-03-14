<?php

class Class_Pi_Edd_Design{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'design';

    private $tab_name = "Design";

    private $setting_key = 'design_settting';

    public $tab;

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;
        
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        add_action($this->plugin_name.'_tab', array($this,'tab'),4);

        add_action('woocommerce_init', array($this,'shipping_zone_to_array'));
        
        
    }

    function shipping_zone_to_array(){
      
        $this->settings = array(
            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__( "Single product page design", 'pi-edd' ), 'type'=>"setting_category"),

            array('field'=>'pi_product_bg_color', 'label'=>__('Background color of message','pi-edd'),'type'=>'color', 'default'=>'#f0947e',   'desc'=>__('Background color', 'pi-edd' )),

            array('field'=>'pi_product_text_color', 'label'=>__('Text color of message','pi-edd'),'type'=>'color', 'default'=>'#fff',  'desc'=>__( 'Text color', 'pi-edd' )),
            
            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Shop / Category page design", 'pi-edd' ), 'type'=>"setting_category"),

            array('field'=>'pi_loop_bg_color', 'label'=>__('Background color of message','pi-edd'),'type'=>'color', 'default'=>'#f0947e',   'desc'=>__('Background color', 'pi-edd' )),

            array('field'=>'pi_loop_text_color', 'label'=>__('Text color of message','pi-edd'),'type'=>'color', 'default'=>'#fff',  'desc'=>__('Text color', 'pi-edd' )),

            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Cart / Checkout page design", 'pi-edd' ), 'type'=>"setting_category"),

            array('field'=>'pi_cart_bg_color', 'label'=>__('Background color of message','pi-edd'),'type'=>'color', 'default'=>'#f0947e',   'desc'=>__('Background color', 'pi-edd' )),
            
            array('field'=>'pi_cart_text_color', 'label'=>__('Text color of message','pi-edd'),'type'=>'color', 'default'=>'#ffffff',  'desc'=>__('Text color', 'pi-edd' )),
           
           
        );
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
        $this->tab_name = __('Design','pi-edd');
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ); ?>">
        <span class="dashicons dashicons-art"></span> <?php echo $this->tab_name; ?> 
        </a>
        <?php
    }

    function tab_content(){
       ?>
        <form method="post" action="options.php"  class="pisol-setting-form">
        <?php settings_fields( $this->setting_key ); ?>
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form_edd($setting, $this->setting_key);
            }
        ?>
        <input type="submit" class="mt-3 btn btn-primary btn-sm" value="Save Option" />
        </form>
       <?php
    }
}

new Class_Pi_Edd_Design($this->plugin_name);