<?php

class Class_Pi_cefw_List{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'default';

    private $tab_name = "fees rules";

    private $setting_key = 'pi_cefw_list';
    
    

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

        $this->tab_name = __("Extra fees rules", 'conditional-extra-fees-woocommerce');
       
        $this->tab = filter_input( INPUT_GET, 'tab' );
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }


        add_action($this->plugin_name.'_tab', array($this,'tab'),1);

        $action = filter_input(INPUT_GET, 'action');
        if($action == 'cefw_delete'){
            $this->post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            add_action('init',array($this,'deletePost' ));
        }

    }

    
    function tab(){
        $page =  filter_input( INPUT_GET, 'page' );
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.$page.'&tab='.$this->this_tab ); ?>">
            <?php _e( $this->tab_name); ?> 
        </a>
        <?php
    }

    function tab_content(){
       $this->listShippingMethod();
    }

    function listShippingMethod(){
        
        include plugin_dir_path( __FILE__ ) . 'partials/listfeesRule.php';
    }

    function deletePost(){
        if(!current_user_can( 'manage_options' )) {
            wp_safe_redirect( esc_url( admin_url( '/admin.php?page=pisol-cefw' ) ) );
            exit();
        }

        $submitted_value = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';
        if(!wp_verify_nonce($submitted_value, 'cefw-delete')){
            wp_die( 'Your page has expired, refresh and try again' );
        }

        wp_delete_post($this->post_id);
        wp_safe_redirect( esc_url( admin_url( '/admin.php?page=pisol-cefw' ) ) );
        exit();
    }
    
}

new Class_Pi_cefw_List($this->plugin_name);