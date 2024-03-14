<?php
/**
 * Plugin Name: WP DLM FAQ
 * Plugin URI: https://wordpress.org/plugins/wp-dlm-faq/
 * Description: The plugin used for faq.
 * Version: 1.5.1
 * Author: DLM
 * Author URI: https://dlmconversion.com/wordpress-plugins/
 */


function enqueue_related_pages_scripts_and_styles(){
        wp_enqueue_style('related-styles', plugins_url('/assets/faq.css', __FILE__));
        wp_enqueue_script('releated-script', plugins_url( '/assets/faq.js' , __FILE__ ), array('jquery','jquery-ui-droppable','jquery-ui-draggable', 'jquery-ui-sortable'));
    }
add_action('wp_enqueue_scripts','enqueue_related_pages_scripts_and_styles');


class MySettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'DLM FAQ', 
            'manage_options', 
            'my-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'my_option_name' );
        
        echo '<div class="wrap">
            <h1>Dlm Faq Setting</h1>
            <form method="post" action="options.php">';
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );
                do_settings_sections( 'my-setting-admin' );
                submit_button();
            echo '</form></div>';
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'my_option_group', // Option group
            'my_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'my-setting-admin' // Page
        );  

        add_settings_field(
            'id_number', // ID
            'Display as accordion', // Title 
            array( $this, 'id_number_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section           
        );  

         add_settings_field(
            'id_number_acc', // ID
            'No. of accordions', // Title 
            array( $this, 'id_number_acc_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'title', 
            'ACF Field Name', 
            array( $this, 'title_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        ); 

        add_settings_field(
            'faq_categories', // ID
            'Generate Shortcode', // Title 
            array( $this, 'faq_categories_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section           
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['id_number'] ) )
            $new_input['id_number'] = absint( $input['id_number'] );
        
        if( isset( $input['id_number_acc'] ) )
            $new_input['id_number_acc'] = absint( $input['id_number_acc'] );

        if( isset( $input['title'] ) )
            $new_input['title'] = sanitize_text_field( $input['title'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
       // print 'Enter your settings below:';
    }

    public function faq_categories_callback(){
        if ( $post = get_page_by_path( 'dlm-internal', OBJECT, 'post' ) )
            $id = $post->ID;
        else
            $id = 0;
        $field_name = $my_options['title'];

        if($field_name==''){
            $field_name = 'dlm_faq_array';
        }
        $faq = get_field($field_name, $id);
        $faq = clean_faqs($faq);
        
        $character = json_decode($faq, true);
        echo '<label>Categories</label><select id="faq_categories">';
        echo '<option value="">Select Category</option>';
        foreach ($character as $key=>$cat) {
            echo '<option value="'.$key.'">'.$key.'</option>';
        }
        echo '</select>';

        echo '<label>Sub Categories</label><select id="faq_sub_categories"><option value="">Select Sub Category</option></select>';

        echo '<input type="button" name="generate" id="generate_code" value="Generate" class="button" />
        <p class="show_code"></p>';
        echo '<script>jQuery(document).ready( function() {
           jQuery("#faq_categories").change( function(e) {
              e.preventDefault(); 
              cat_name = jQuery(this).val();
              jQuery.ajax({
                 type : "post",
                 dataType : "json",
                 url : "/wp-admin/admin-ajax.php",
                 data : {action: "get_sub_cats", cat_name : cat_name, title : "'.$field_name.'"},
                 success: function(response) {
                    console.log(response);
                    if(response.type == "success") {
                       jQuery("#faq_sub_categories").html(response.subcats);
                    }
                    else {
                       alert("Your like could not be added");
                    }
                 }
              });
           });
           jQuery("#generate_code").click( function(e) {
              e.preventDefault(); 
              cat_name = jQuery("#faq_categories").val();
              sub_cat_name = jQuery("#faq_sub_categories").val();
              str_cat ="";
              str_subcat ="";
              if(cat_name){
                str_cat ="cat=\'"+cat_name+"\'";
              }

              if(sub_cat_name){
                str_subcat ="sub_cat=\'"+sub_cat_name+"\'";
              }

              jQuery(".show_code").html("[inject-faq "+str_cat+" "+str_subcat+"]");
           });
        });
        </script>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function id_number_callback()
    {
        printf(
            '<input type="number" maxlength="1" min="0" max="1" id="id_number" name="my_option_name[id_number]" value="%s" /> <em>Enter 1 to show in accordion form.</em>',
            isset( $this->options['id_number'] ) ? esc_attr( $this->options['id_number']) : '1'
        );

    }
     public function id_number_acc_callback()
    {
        printf(
            '<input type="number" maxlength="1" min="1" max="100" id="id_number_acc" name="my_option_name[id_number_acc]" value="%s" /> <em>Enter no to show that much of accordions.</em>',
            isset( $this->options['id_number_acc'] ) ? esc_attr( $this->options['id_number_acc']) : '10'
        );

    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function title_callback()
    {
        printf(
            '<input type="text" id="title" name="my_option_name[title]" value="%s" /> <em>Enter acf field name in this box.</em>',
            $this->options['title'] ? esc_attr( $this->options['title']) : 'dlm_faq_array'
        );
    }
}


function inject_faq($atts = '') {
    global $schema;
    $i=1;
    $my_options = get_option( 'my_option_name' );
    
    extract(  
        $attr = shortcode_atts( 
            array(
                'cat' => '',
                'sub_cat' => '',
            ), 
        $atts )
    );
        if ( $post = get_page_by_path( 'dlm-internal', OBJECT, 'post' ) )
            $id = $post->ID;
        else
            $id = 0;
        wp_reset_query();
        $field_name = $my_options['title'];

        if($field_name==''){
            $field_name = 'dlm_faq_array';
        }
        $faq = get_field($field_name, $id);
    
        $faq = clean_faqs($faq);
        $character = json_decode($faq, true);
        if($my_options['id_number']!=1){
            $wrap .= '<div class="common normal" id="dlm_faq">';
        }else{
            $wrap .= '<div class="common accordion-1" id="dlm_faq">';
        }

        if($my_options['id_number_acc']==""){
            $my_options['id_number_acc']=10;
        }

    
        if(isset($atts['sub_cat'])):

            foreach ($character as $key=>$cat) {
                foreach ($cat as $key1=>$subcat) {
                    if($key1==$atts['sub_cat']):
                        foreach ($subcat as $value) {

                            if("faq-no-".$i == "faq-no-".$my_options['id_number_acc']){
                                $wrap .= '<div class="faq-item faq-no-'.$i.' nxtall"><h3 class=""><span class="ui-state-active ui-icon"></span>'.str_replace("u2019", "'", $value['question']).'</h3><div style="display: none;"><p>'.str_replace("u2019", "'", str_replace('•', '<br>• ', $value['answer'])).'</p></div></div>';
                            }
                            else{
                            $wrap .= '<div class="faq-item faq-no-'.$i.'"><h3 class=""><span class="ui-state-active ui-icon"></span>'.str_replace("u2019", "'", $value['question']).'</h3><div style="display: none;"><p>'.str_replace("u2019", "'", str_replace('•', '<br>• ', $value['answer'])).'</p></div></div>';
                            }
                             $i++;
                        }
                       
                    endif;
                }
                
            }
        elseif(isset($atts['cat'])):
            
            foreach ($character as $key=>$cat) {
                
                if($key==$atts['cat']):

                    foreach ($cat as $subcat) {
                        foreach ($subcat as $value) {
                            //print_r($value);
                           if("faq-no-".$i == "faq-no-".$my_options['id_number_acc']){
                                $wrap .= '<div class="faq-item faq-no-'.$i.' nxtall"><h3 class=""><span class="ui-state-active ui-icon"></span>'.str_replace("u2019", "'", $value['question']).'</h3><div style="display: none;"><p>'.str_replace("u2019", "'", str_replace('•', '<br>• ', $value['answer'])).'</p></div></div>';
                            }
                            else{
                            $wrap .= '<div class="faq-item faq-no-'.$i.'"><h3 class=""><span class="ui-state-active ui-icon"></span>'.str_replace("u2019", "'", $value['question']).'</h3><div style="display: none;"><p>'.str_replace("u2019", "'", str_replace('•', '<br>• ', $value['answer'])).'</p></div></div>';
                            }
                            $i++;
                        }
                        
                    }
                endif;
            }

        else:
            
            foreach ($character as $cat) {
                foreach ($cat as $subcat) {
                    foreach ($subcat as $value) {
                        //print_r($value);
                        if("faq-no-".$i == "faq-no-".$my_options['id_number_acc']){
                                $wrap .= '<div class="faq-item faq-no-'.$i.' nxtall"><h3 class=""><span class="ui-state-active ui-icon"></span>'.str_replace("u2019", "'", $value['question']).'</h3><div style="display: none;"><p>'.str_replace("u2019", "'", str_replace('•', '<br>• ', $value['answer'])).'</p></div></div>';
                            }
                            else{
                            $wrap .= '<div class="faq-item faq-no-'.$i.'"><h3 class=""><span class="ui-state-active ui-icon"></span>'.str_replace("u2019", "'", $value['question']).'</h3><div style="display: none;"><p>'.str_replace("u2019", "'", str_replace('•', '<br>• ', $value['answer'])).'</p></div></div>';
                            }
                            $i++;
                    }
                    
                }
            }
        endif;
        $wrap .='</div>';
        if($i>$my_options['id_number_acc']){
            $wrap .='<div class="faq-bottom"><a class="btn btn-gold all-btn" href="#dlm_faq">See All</a></div>';
        }

    return $wrap;
   
}
add_shortcode('inject-faq', 'inject_faq');

add_action('wp_head', 'add_jsonld_head', 1000);

function add_jsonld_head(){

    global $post;
    //print_r($post);
    $attr = explode('inject-faq ', $post->post_content);

    //[inject-faq sub_cat='Botox' cat='Fillers and Injectables' ]

    if(count($attr)>1){
        $str = explode(']', $attr[1]);
        if(count($str)>1){
            $str = explode('cat=', str_replace('"', '', str_replace("'", "", $str[0])));
            if($str[0]=='sub_'){
                $atts['sub_cat'] = trim($str[1]);
                $atts['cat'] = trim($str[2]);
            }else{
                $atts['sub_cat'] = $str[2];
                $str = explode(' sub_', $str[1]);
                $atts['cat'] = $str[0];
            }

            //print_r($atts);
            $faqschema = array();
            $my_options = get_option( 'my_option_name' );
            
                if ( $post = get_page_by_path( 'dlm-internal', OBJECT, 'post' ) )
                    $id = $post->ID;
                else
                    $id = 0;
            
                wp_reset_query();
        
                $field_name = $my_options['title'];

                if($field_name==''){
                    $field_name = 'dlm_faq_array';
                }
                $faq = get_field($field_name, $id);
            
                $faq = clean_faqs($faq);
            
                $character = json_decode($faq, true);

                if(isset($atts['sub_cat'])):

                    foreach ($character as $key=>$cat) {
                        foreach ($cat as $key1=>$subcat) {
                            if($key1==$atts['sub_cat']):
                                foreach ($subcat as $value) {
                                    $faqschema[] = array(
                                                        '@type'=>'Question', 
                                                        'name'=>str_replace("u2019", "'", $value['question']),
                                                        'acceptedAnswer' => array(
                                                            '@type' => 'Answer',
                                                            'text'  => str_replace("u2019", "'", $value['answer'])
                                                        )
                                                    );
                                }
                            endif;
                        }
                    }

                elseif(isset($atts['sub_cat'])):

                    foreach ($character as $key=>$cat) {
                        
                        if($key==$atts['cat']):

                            foreach ($cat as $subcat) {
                                foreach ($subcat as $value) {
                                    if($value['answer']!=""):
                                        $faqschema[] = array(
                                                            '@type'=>'Question', 
                                                            'name'=>str_replace("u2019", "'", $value['question']),
                                                            'acceptedAnswer' => array(
                                                                '@type' => 'Answer',
                                                                'text'  => str_replace("u2019", "'", $value['answer'])
                                                            )
                                                        );
                                    endif;
                                }
                            }
                        endif;
                    }

                else:

                    foreach ($character as $cat) {
                        foreach ($cat as $subcat) {
                            foreach ($subcat as $value) {
                                $faqschema[] = array(
                                                    '@type'=>'Question', 
                                                    'name'=>str_replace("u2019", "'", $value['question']),
                                                    'acceptedAnswer' => array(
                                                        '@type' => 'Answer',
                                                        'text'  => str_replace("u2019", "'", $value['answer'])
                                                    )
                                                );
                            }
                        }
                    }
                endif;

                $schema = array(
                                '@context'  => 'https://schema.org',
                                '@type'     => 'FAQPage',
                                'mainEntity'=> $faqschema
                            );
            echo '<script type="application/ld+json">';
            echo json_encode($schema);
            echo '</script>';
        }
    }
}

add_action("wp_ajax_get_sub_cats", "get_sub_cats");
add_action("wp_ajax_nopriv_get_sub_cats", "please_login");

// define the function to be fired for logged in users
function get_sub_cats() {  

    if ( $post = get_page_by_path( 'dlm-internal', OBJECT, 'post' ) )
        $id = $post->ID;
    else
        $id = 0;
    
    wp_reset_query();

    $faq = get_field($_REQUEST["title"], $id);
    $faq = clean_faqs($faq);
    $character = json_decode($faq, true);

    $str  = '<select id="faq_sub_categories">';
    foreach ($character as $key=>$cat) {
        if($key==$_REQUEST["cat_name"]){
            foreach ($cat as $key1=>$subcat) {
                $str .= '<option value="'.$key1.'">'.$key1.'</option>';
            }
        }
    }
    $str  .= '</select>';

    $result['type'] = "success";
    $result['subcats'] = $str;

    echo json_encode($result);

    // don't forget to end your scripts with a die() function - very important
    die();
}

function clean_faqs($faq){



    
//      $faq = str_replace('u201c', '“', str_replace('U201C', '“', $faq));
//      $faq = str_replace('u201d', '”', str_replace('U201D', '”', $faq));
//      $faq = str_replace('U00AE', '®', str_replace('u00ae', '®', $faq));
//      $faq = str_replace('U2122', '™', str_replace('u2122', '™', $faq));
//      $faq = str_replace('U2122', '™', str_replace('u2122', '™', $faq));
//      $faq = str_replace('u00a9', '©', str_replace('U00A9', '©', $faq));
//      $faq = str_replace('NUF0B7', '', str_replace('nuf0b7', '', $faq));
        //$faq = str_replace('n•', '•', $faq);



    $search  = array('\"', "'", '"', '{', '}', ':[{', ':{', '}],', 'answer:', 'question:', ',answer', '}]"}"}', '}]"},');
    $replace = array('', '', '', '{"', '"}', '":[{', '":{', '}],"', 'answer":"', 'question":"', '","answer', '}]}}', '}]},"');

    $faq = str_replace('u2122', '\u2122', $faq);
    $faq = str_replace('u201c', '\u201c', $faq);
    $faq = str_replace('u201d', '\u201d', $faq);
    $faq = str_replace("u00ae", "\u00ae", $faq);
    
    return str_replace($search, $replace, $faq);
}


if( is_admin() )
    $my_settings_page = new MySettingsPage();