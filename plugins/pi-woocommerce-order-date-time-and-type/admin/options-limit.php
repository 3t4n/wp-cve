<?php

class pisol_dtt_option_order_limit{

    private $settings = array();

    private $active_tab;

    private $this_tab = 'pi_time_limit';

    private $tab_name = "Order limit (pro)";

    private $setting_key = 'pisol_time_limit';

    private $pro_version = false;

    public $tab;

    function __construct(){

        $this->pro_version = pi_dtt_pro_check();

        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        $this->settings = array(
               array('field'=>'pi_time_slot')
                
            );
        

        if($this->this_tab == $this->active_tab){
            add_action('pisol_dtt_tab_content', array($this,'tab_content'));
        }

        add_action('pisol_dtt_tab', array($this,'tab'),6);
        
        $this->register_settings();

        
    }

    function register_settings(){   

        foreach($this->settings as $setting){
                register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        $page = sanitize_text_field(filter_input( INPUT_GET, 'page'));
        ?>
        <a class=" pi-side-menu  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?> hide-pro" href="<?php echo admin_url( 'admin.php?page='.$page.'&tab='.$this->this_tab ); ?>">
        <span class="dashicons dashicons-editor-contract"></span>  <?php _e( $this->tab_name, 'pisol-dtt' ); ?> 
        </a>
        <?php
    }

    function tab_content(){
       ?>
       <div class="alert bg-primary text-light">
       <strong>Available in PRO</strong>
        Using this you can set day or date specific order limit
       </div>
       <h1>Setting Day specific order limit</h1>
        <img src="<?php echo PISOL_DTT_URL; ?>admin/img/day-limit.png" class="img-fluid">
       <h1>Setting Date specific order limit</h1>
        <img src="<?php echo PISOL_DTT_URL; ?>admin/img/date-limit.png" class="img-fluid">
       <?php
    }

}

new pisol_dtt_option_order_limit();
