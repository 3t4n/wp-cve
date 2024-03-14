<?php

class pisol_cefw_combine_methods{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'combine_fees';

    private $tab_name = "Combine multiple fees";

    private $setting_key = 'cefw_combine_method';
    
    

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;


        $this->settings = array(
           
            
           
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
        <style>
            .video-container {
                overflow: hidden;
                position: relative;
                width:100%;
            }

            .video-container::after {
                padding-top: 56.25%;
                display: block;
                content: '';
            }

            .video-container iframe {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }
        </style>
        <div class="alert alert-info my-3"><strong>Combine multiple fees is available in PRO Version watch its working in below Video</strong></div>
        <div class="video-container">
        <iframe style="margin-top:20px;" src="https://www.youtube.com/embed/lE6ELZ0v_68" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
       <?php
    }
}

new pisol_cefw_combine_methods($this->plugin_name);