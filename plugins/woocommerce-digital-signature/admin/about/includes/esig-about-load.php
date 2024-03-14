<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!class_exists('esig_Addon_About')) :
    
    class esig_Addon_About {
    
        
        
        private  $_name = "";
        
        /**
         * Initialize the plugin by loading admin scripts & styles and adding a
         * settings page and menu.
         * @since     0.1
         */
        function __construct($_name="") {
            $this->_name = $_name ; 
            
           // $this->hooks();
  
        }
        
        public function hooks() {
            add_action('admin_menu', array($this, 'adminmenu'));
        }

        private function isAllowedScreen()
        {
            $page = (isset($_GET['page']))? esig_woocommerce_get('page') : false;

            $screens = array("esign-licenses-general");

            if($page)
            {
                if(in_array($page,$screens))
                {
                    return false;
                }
            }
            
            return true;
        }
        
        final function requirement() {

            if(!$this->isAllowedScreen())
            {
                return false;
            }

            $esigStatus = esig_get_activation_state();
            $message = call_user_func(strtolower($this->_name) . "_message", $esigStatus,strtolower($this->_name));
            if($message)
            {
                $this->loadCss();
                echo '<div class="bangBar error ' . strtolower($this->_name) . '  ">' . $message . '</div>';
            }
            
        }
    
        final function loadCss(){
            
                $page  = isset($_GET['page']) ? esig_woocommerce_get('page') : false ;

                if(!empty($page)  && preg_match("/esign-/i",$page)){
                  return false;
                }

                wp_enqueue_style("esig-icon-css");

                echo '<link id="esig-about-alert" href="'. plugins_url( '/assets/css/esig-about-alert.css', dirname(__FILE__)) .'" rel="stylesheet">';
                
        }


        final function adminmenu() {
         
            add_submenu_page(" ", __('About', 'esig'), __('About', 'esig'), 'read', 'esign-' . strtolower($this->_name) . '-about', array($this, 'about_page'));

            if (!function_exists('WP_E_Sig')) {

                if (empty($GLOBALS['admin_page_hooks']['esign'])) {
                    add_menu_page('E-Signature', 'E-Signature', 'read', 'esign-' . strtolower($this->_name) . '-about', array($this, 'about_page'), plugins_url('../../assets/images/pen_icon.svg', __FILE__));
                    add_submenu_page('esign-' . strtolower($this->_name) . '-about', $this->_name . " E-signature",$this->_name . " E-signature", 'read', 'esign-' . strtolower($this->_name) . '-about', array($this, 'about_page'));
                }
                else{
                    add_submenu_page('esign-' . strtolower($this->_name) . '-about',$this->_name . " E-signature", $this->_name . " E-signature", 'read', 'esign-' . strtolower($this->_name) . '-about', array($this, 'about_page'));
                }
                
                return;
            }
        }

        public function about_page() {
            
            include_once(constant("ESIGN_". strtoupper($this->_name) ."_ABOUT_PATH") . "/views/esig-addon-about.php");
        }

        public function core_page() {

            include_once(constant("ESIGN_". strtoupper($this->_name) ."_ABOUT_PATH") . "/views/core-about.php");
        }
        
    }
    
endif;