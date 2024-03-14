<?php

/**
 * @package WPEXPERTS PDF
 * 
 * Here we define plugin action hook
 * it will add link in plugin action bar
 * and all plugin setting and saving data
 * 
 */
if ( ! defined( 'ABSPATH' ) ) exit;
if(!class_exists('WPEXPERTS_PDF')){
    class WPEXPERTS_PDF{
        public function __construct() {
            $this->wpexperts_pdf_init();
        }
        /**
         * 
         *  Here active wpexperts pdf
         * 
         */
        public function wpexperts_pdf_init(){
            add_action( 'init', array( $this, 'wpexperts_pdf_load_textdomain' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'wpexperts_pdf_admin_enqueue_scripts' ) );
            add_action( 'wp_head', array( $this, 'wpexperts_pdf_scripts'), 10 );
            add_action( 'admin_menu', array( $this, 'wpexperts_pdf_setting_page' ) );
            add_action( 'wp_footer', array ( $this, 'wpexperts_pdf_generate_PDF' ),200 );
            add_action( 'wp_head', array ( $this, 'wpexperts_pdf_style_frontend' ) );
            add_action( 'body_class', array ( $this, 'wpexperts_pdf_add_body_class' ) );
            add_shortcode( 'wp_objects_pdf', array( $this, 'wpexperts_pdf_shortcode' ) );
        }
        /**
         * 
         * Here we are installing plugin options
         * and also plugin require data
         * 
         */
        static function wpexperts_pdf_install(){
            $wpexperts_pdf_options = array(
                'wpexperts-pdf-select' => 'wpexperts-pdf-head-image-btn',
                'wpexperts-pdf' => 'wpexperts-pdf-1',
                'wpexperts_pdf_text' => 'Download PDF',
                'wpexperts-pdf-page' => 'portrait',
                'wpexperts-pdf-page-size' => 'a3'
            );
            add_option( 'wpexperts_pdf_option', $wpexperts_pdf_options );
        }
        /**
         * 
         * Here we are uninstalling plugin options
         * and also plugin setup data
         * 
         */
        static function wpexperts_pdf_uninstall(){
            delete_option('wpexperts_pdf_option');
        }
        /**
         * 
         * Here We are setting wpexperts pdf text domain
         * 
         */
        public function wpexperts_pdf_load_textdomain() {
            load_plugin_textdomain( 'wpexperts', false, WPEXPERTS_PDF_LANG ); 
        }
        /**
         * 
         * Here We are enqueue style for admin setting page
         * 
         */
        public function wpexperts_pdf_admin_enqueue_scripts(){
            wp_register_style( 'wpexperts-pdf', WPEXPERTS_PDF_ASSETS . 'css/style.css' , false, WPEXPERTS_PDF_VERSION );
            wp_enqueue_style( 'wpexperts-pdf' );
        }
        /**
         * 
         *  Here is wpexperts pdf enqueue script and style
         * 
         */
        public function wpexperts_pdf_scripts(){
            wp_register_script( 'wpexperts-es6-promise-auto-min', WPEXPERTS_PDF_ASSETS . 'js/es6-promise.auto.min.js', array(), WPEXPERTS_PDF_VERSION, false );
            wp_register_script( 'wpexperts-jspdf-min', WPEXPERTS_PDF_ASSETS . 'js/jspdf.min.js', array(), WPEXPERTS_PDF_VERSION, false );
            wp_register_script( 'wpexperts-jhtml2canvas', WPEXPERTS_PDF_ASSETS . 'js/html2canvas.min.js', array(), WPEXPERTS_PDF_VERSION, false );
            wp_register_script( 'wpexperts-html2pdf-min', WPEXPERTS_PDF_ASSETS . 'js/html2pdf.min.js', array(), WPEXPERTS_PDF_VERSION, false );
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'wpexperts-es6-promise-auto-min' );
            wp_enqueue_script( 'wpexperts-jspdf-min' );
            wp_enqueue_script( 'wpexperts-jhtml2canvas' );
            wp_enqueue_script( 'wpexperts-html2pdf-min' );
        }
        /**
         * 
         * Here We are register wpexperts pdf setting page
         * 
         */
        public function wpexperts_pdf_setting_page(){
            add_menu_page('WP PDF', 'PDF', 'manage_options', 'pdf-settings', array( $this, 'wpexperts_pdf_menu_page' ), plugins_url('/assets/images/pdf-icon.gif', __FILE__), 10);
        }
        /**
         * 
         * Here We are setting wpexperts pdf setting page
         * where user handel there wpexperts pdf option
         * 
         */
        public function wpexperts_pdf_menu_page(){
            $this->wpexperts_pdf_save_data();
            $options = get_option( 'wpexperts_pdf_option' );
            $wpexperts_pdf_btn_text = ( isset( $options['wpexperts_pdf_text']) ) ? $options['wpexperts_pdf_text'] : '';
            $wpexperts_pdf_fieldname = ( isset( $options['wpexperts-fieldname'] ) ) ? $options['wpexperts-fieldname']  :'';
            $nonce = wp_create_nonce('wp_pdf_setting_save');
            echo '<div class="wpexperts_pdf_main">';
            echo '<form action="" method="post">';
            echo '<input type="text" name="wp_pdf_gen[nonce]" value="' . esc_attr($nonce) . '" hidden>';
            echo '<div class="wpexperts_pdf_title">Choose Your PDF Button Style</div>';
            $this->wpexperts_pdf_heading_radio_buttons( 'PDF Image Button', 'image-btn' );
            $this->wpexperts_pdf_radio_buttons();
            $this->wpexperts_pdf_heading_radio_buttons( 'PDF Text Button', 'text-btn' );
			echo '<input type="text" name="wpexperts_pdf_text" value="' . esc_attr($wpexperts_pdf_btn_text) . '" placeholder="Enter Your Button Text here" /><hr />';
            echo '<div class="fileNameHead">Filename</div>';
			echo  '<input type="text" name="fieldname" value="' . esc_attr($wpexperts_pdf_fieldname) . '" class="file_name" placeholder="PDF-name">';
            ?>
            <script>

             jQuery(document).ready(function() {
                    jQuery("#wpexperts_pdf_save").click(function() { 
                        var name = jQuery(".file_name").val();
                    });
                });</script>
             <?php
            echo '<br/>';
            echo '<hr/>';
            $this->wpexperts_pdf_page_radio_buttons( 'Page Portrait', 'portrait' );
            $this->wpexperts_pdf_page_radio_buttons( 'Page Landscape', 'landscape' );
            echo '<hr />';
            $this->wpexperts_pdf_page_size_radio_buttons( 'Page A3', 'a3' );
            $this->wpexperts_pdf_page_size_radio_buttons( 'Page A4', 'a4' );
            $this->wpexperts_pdf_page_size_radio_buttons( 'Page A5', 'a5' );
            echo '<p><input type="submit" name="wpexperts_pdf_save" id="wpexperts_pdf_save" class="button button-primary" value="Save and Apply"/></p>';
            echo '</form>';
            echo '</div>';
        }
        /**
         * 
         * Here We are radio button for setting page head
         * 
         */
        private function wpexperts_pdf_heading_radio_buttons( $wpexperts_pdf_name , $wpexperts_pdf_value ){
            echo '<div class="wpexperts_pdf_head"><div class="btn-box-head">';
			echo '<input type="radio" name="wpexperts-pdf-select" value="wpexperts-pdf-head-' . esc_attr($wpexperts_pdf_value) . '" id="' . esc_attr_e($wpexperts_pdf_value) . '"  ' . esc_attr($this->wpexperts_pdf_radio_checked( $wpexperts_pdf_value)) . '/></div>';
			echo '<div class="btn-pfd-text"><label for="' . esc_attr($wpexperts_pdf_value) . '">' . esc_attr($wpexperts_pdf_name) . '</label></div></div>';
        }
        /**
         * 
         * Here We are radio button for setting page ( page size )
         * 
         */
        private function wpexperts_pdf_page_radio_buttons( $wpexperts_pdf_name = null , $wpexperts_pdf_value ){
            echo '<div class="wpexperts_pdf_head"><div class="btn-box-head">';
			echo '<input type="radio" name="wpexperts-pdf-page" value="' . esc_attr($wpexperts_pdf_value). '" id="' . esc_attr($wpexperts_pdf_value) . '"  ' . esc_attr($this->wpexperts_pdf_radio_checked( $wpexperts_pdf_value )) . '/></div>';
			echo '<div class="btn-pfd-text"><label for="' . esc_attr($wpexperts_pdf_value) . '">' . esc_attr($wpexperts_pdf_name) . '</label></div></div>';
        }
        /**
         * 
         * Here We are radio button for setting page ( page size )
         * 
         */
        private function wpexperts_pdf_page_size_radio_buttons( $wpexperts_pdf_name = null , $wpexperts_pdf_value ){
            echo '<div class="wpexperts_pdf_head"><div class="btn-box-head">';
			echo '<input type="radio" name="wpexperts-pdf-page-size" value="' . esc_attr($wpexperts_pdf_value)  . '" id="' . esc_attr($wpexperts_pdf_value)  . '"  ' . esc_attr($this->wpexperts_pdf_radio_checked( $wpexperts_pdf_value )) . '/></div>';
			echo '<div class="btn-pfd-text"><label for="' . esc_attr($wpexperts_pdf_value)  . '">' . esc_attr($wpexperts_pdf_name)  . '</label></div></div>';
        }
        /**
         * 
         * Here We are radio button for setting page
         * 
         */
        private function wpexperts_pdf_radio_buttons(){
            echo '<div class="wpexperts_pdf_radio_set">';
            for ($wpexperts_pdf = 1; $wpexperts_pdf <= 5; $wpexperts_pdf++) {
                echo '<div class="btn-box">';
				echo '<label for="wpexperts_pdf_set-' . esc_attr($wpexperts_pdf) . '"><img src="' . esc_attr(WPEXPERTS_PDF_ASSETS) . 'images/button/wpexperts-pdf-' . esc_attr($wpexperts_pdf) . '.jpg" width="25" /></label>';
				echo '<div class="btn-pfd-input"><input type="radio" name="wpexperts-pdf" value="wpexperts-pdf-' . esc_attr($wpexperts_pdf) . '" id="wpexperts_pdf_set-' . esc_attr($wpexperts_pdf) . '" ' . esc_attr($this->wpexperts_pdf_radio_checked($wpexperts_pdf) ) . ' /></div></div>';
            }
            echo '</div><hr />';
        }
        /**
         * 
         * Here We are chaking radio button value
         * 
         */
        private function wpexperts_pdf_radio_checked( $checked_val ){
            $options = get_option( 'wpexperts_pdf_option' );
            $checked = 'checked="checked"';
            $wpexperts_pdf_btn_style    = sanitize_text_field($options['wpexperts-pdf-select']);
			$wpexperts_pdf_btn_set      = sanitize_text_field($options['wpexperts-pdf']);
			$wpexperts_pdf_page         = sanitize_text_field($options['wpexperts-pdf-page']);
			$wpexperts_pdf_page_size    = sanitize_text_field($options['wpexperts-pdf-page-size']);
            if( $wpexperts_pdf_btn_style == 'wpexperts-pdf-head-' . $checked_val )
                return $checked;
            if( $wpexperts_pdf_btn_set == 'wpexperts-pdf-' . $checked_val )
                return $checked;
            if( $wpexperts_pdf_page == $checked_val )
                return $checked;
            if( $wpexperts_pdf_page_size == $checked_val )
                return $checked;
        }
        /**
         * 
         * Here We are saving setting page data
         * 
         */
        private function wpexperts_pdf_save_data(){
            $nonce = isset($_POST['wp_pdf_gen']['nonce']) ? sanitize_text_field($_POST['wp_pdf_gen']['nonce']) : '';
			if ( isset( $_POST['wpexperts_pdf_save'] ) && wp_verify_nonce($nonce , 'wp_pdf_setting_save' )) {
                $wpexperts_pdf_style        = isset($_POST['wpexperts-pdf-select']) ? sanitize_text_field($_POST['wpexperts-pdf-select']) : ''; 
				$wpexperts_pdf_btn_set      = isset($_POST['wpexperts-pdf']) ? sanitize_text_field($_POST['wpexperts-pdf']) : ''; 
				$wpexperts_pdf_text         = isset($_POST['wpexperts_pdf_text']) ? sanitize_text_field($_POST['wpexperts_pdf_text']) : ''; 
				$wpexperts_pdf_page         = isset($_POST['wpexperts-pdf-page']) ? sanitize_text_field($_POST['wpexperts-pdf-page']) : '';
				$wpexperts_pdf_page_size    = isset($_POST['wpexperts-pdf-page-size']) ? sanitize_text_field($_POST['wpexperts-pdf-page-size']) : ''; 
				$fieldname                  = isset($_POST['fieldname']) ? sanitize_text_field($_POST['fieldname']) : '';                  
                $wpexperts_pdf_option['wpexperts-pdf-select']   = $wpexperts_pdf_style;
                $wpexperts_pdf_option['wpexperts-pdf']          = $wpexperts_pdf_btn_set;
                $wpexperts_pdf_option['wpexperts_pdf_text']     = $wpexperts_pdf_text;
                $wpexperts_pdf_option['wpexperts-pdf-page']     = $wpexperts_pdf_page;
                $wpexperts_pdf_option['wpexperts-pdf-page-size']= $wpexperts_pdf_page_size;
                $wpexperts_pdf_option['wpexperts-fieldname']= $fieldname;
                update_option( 'wpexperts_pdf_option', $wpexperts_pdf_option );
            }
        }
        /**
         * 
         * Here We are adding little style for
         * shortcode button
         * 
         */
        public function wpexperts_pdf_style_frontend(){ ?>
            <style>
            #wpexperts_pdf_generate_file{
                overflow: hidden;
                padding: 5px;
                cursor: pointer;
            }
        </style>
        <?php
    }
        /**
         * 
         * Here We are adding body class
         * that will be use in genrate Pdf
         * 
         */
        public function wpexperts_pdf_add_body_class(){
            $classes[] = 'wpexperts-page';
            return $classes;
        }
        /**
         * 
         * Here We are creating PDF
         * 
         */
        public function wpexperts_pdf_generate_PDF(){ 
            global $post;
            $this->wpexperts_pdf_save_data();
            $options = get_option( 'wpexperts_pdf_option' );
            $wpexperts_pdf_fieldname = isset($options['wpexperts-fieldname']) ? $options['wpexperts-fieldname'] : '';
            $options = get_option( 'wpexperts_pdf_option' );
            $wpexperts_pdf_page         = $options['wpexperts-pdf-page'];
            $wpexperts_pdf_page_size    = $options['wpexperts-pdf-page-size'];
            ?> 
            <script>
               
             jQuery(document).ready(function($) {
                $( "#wpexperts_pdf_generate_file" ).click(function() {
                    $("#wpexperts_pdf_generate_file").css("display", "none");
                    var element = document.getElementsByClassName('wpexperts-page')[0];
                    var opt = {
                        margin:       1,
						filename:     '<?php esc_attr_e($wpexperts_pdf_fieldname); ?>-<?php esc_attr_e($post->post_name); ?>' + '.pdf',
                        image:        { type: 'jpeg', quality: 0.98 },
                        html2canvas:  { scale: 2 },
						jsPDF:        { unit: 'pt', format: '<?php esc_attr_e($wpexperts_pdf_page_size); ?>', orientation: '<?php esc_attr_e($wpexperts_pdf_page); ?>' }
                    };
                    html2pdf().from(element).set(opt).save();
                    setTimeout(function(){
                        $('#wpexperts_pdf_generate_file').show();
                    }, 5000);
                });
            });
        </script>
        <?php
    }
        /**
         * 
         * Here We are creating shortcode
         * 
         */
        public function wpexperts_pdf_shortcode(){ 
            ob_start();   
            $options = get_option( 'wpexperts_pdf_option' );
            $wpexperts_pdf_btn_style = $options['wpexperts-pdf-select'];
            $wpexperts_pdf_btn_set = $options['wpexperts-pdf'];
            $wpexperts_pdf_btn_text = $options['wpexperts_pdf_text'];
            echo '<a class="button" id="wpexperts_pdf_generate_file">';
            if (  'wpexperts-pdf-head-image-btn' == $wpexperts_pdf_btn_style) {
				echo '<img src="' . esc_attr(WPEXPERTS_PDF_ASSETS) . 'images/button/' . esc_attr($wpexperts_pdf_btn_set) . '.jpg" width="25" />';
			}
            if (  'wpexperts-pdf-head-text-btn'== $wpexperts_pdf_btn_style ) {
				esc_attr_e($wpexperts_pdf_btn_text);
			}
            echo '</a>';
            return ob_get_clean();
        }
    }
}