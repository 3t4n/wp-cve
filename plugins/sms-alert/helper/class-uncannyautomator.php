<?php

/**
 * Backend helper.
 *
 * PHP version 5
 *
 * @category Helper
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 */

if (! defined('ABSPATH') ) {
    exit;
}

if (! is_plugin_active('uncanny-automator/uncanny-automator.php') ) { 
    return;   
}

add_action('automator_add_integration', 'SAintegrationLoadFiles');

/**
 * SAintegration load files
 *
 * Returns an array of registered post types.
 * 
 * @return void
 */
function SAintegrationLoadFiles()
{
    
    if (! class_exists('\Uncanny_Automator\Integration') ) {
        return;
    }    
    $helpers = new SAAutoMateHelpers();
    new SAUncanny_Automator($helpers);
    new Send_SMS($helpers);
    add_action('wp_ajax_automator_sample_get_posts', array( $helpers, 'ajax_get_posts' ));    
}
 
/**
 * Backend helper.
 *
 * PHP version 5
 *
 * @category Helper
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 
 * Class SAUncanny_Automator
 */
class SAUncanny_Automator extends \Uncanny_Automator\Integration
{
    
    /**
     * Setup
     *
     * Returns an array of registered post types.
     * 
     * @return void
     */
    protected function setup()
    {
    
        $this->set_integration('SAUncanny_Automator');
        $this->set_name('SMS Alert');
        $this->set_icon_url(plugin_dir_url(__FILE__) . '../images/www.smsalert.co.in.png');
    }
}

/**
 * Backend helper.
 *
 * PHP version 5
 *
 * @category Helper
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 
 * Class SAAutoMateHelpers
 */
class SAAutoMateHelpers
{

    /**
     * Get post types
     *
     * Returns an array of registered post types.
     * 
     * @return void
     */
    public function get_post_types()
    {

        $options = array();
        $options[] = array(
        'text' => __('Any post type', 'sms-alert'),
        'value' => '-1'
        );

        $post_types = get_post_types();

        foreach ( $post_types as $type ) {

            $options[] = array(
            'text' => $type,
            'value' => $type
            );
        }

        return $options;
    }

    /**
     * Ajax_get_posts
     *
     * Returns an array of registered post types.
     * 
     * @return void
     */
    public function ajax_get_posts()
    {        
        Automator()->utilities->ajax_auth_check();        
        $values = automator_filter_input_array('values', INPUT_POST);
        $options = array();
        $options[] = array(
        'text' => __('Any post', 'sms-alert'),
        'value' => '-1'
        );
        if (empty($values['POST_TYPE']) ) {
            wp_send_json( 
                array(
                'success' => false,
                'error'   => esc_html__("Please select the post type first.", 'sms-alert'),
                'options' => $options
                )    
            );
        }
        $args = array(
        'post_type' => $values['POST_TYPE'],
        'numberposts' => -1
        );
        $posts = get_posts($args);
        foreach ( $posts as $post ) {

            $options[] = array(
            'text' => $post->post_title,
            'value' => $post->ID
            );
        }
        wp_send_json( 
            array(
            'success' => true,
            'options' => $options
            )    
        );
    }

    /**
     * Post_is_being_published
     * 
     * Checks if the post status changed from non 'publish' to 'publish'
     *
     * @param $post        as post
     * @param $post_before as post_before
     *
     * @return void
     */
    public function post_is_being_published( $post, $post_before )
    {
        if ('publish' !== $post->post_status ) {
            return false;
        }
        if (! empty($post_before->post_status) && 'publish' === $post_before->post_status ) {
            return false;
        }
        return true;
    }
}


/**
 * Backend helper.
 *
 * PHP version 5
 *
 * @category Helper
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 *
 * Class Send_SMS
 */
class Send_SMS extends \Uncanny_Automator\Recipe\Action
{

    /**
     * Setup_action     
     *
     * @return void
     */
    protected function setup_action()
    {
        $this->set_integration('SAUncanny_Automator');
        $this->set_action_code('Send_SMS');
        $this->set_action_meta('sa_mobile');
        $this->set_sentence(sprintf(esc_attr__('Send an sms to {{a number:%1$s}} from SMS Alert', 'sms-alert'), $this->get_action_meta()));
        $this->set_readable_sentence(esc_attr__('Send an {{SMS}} from SMS Alert', 'sms-alert'));
    }
    
    /**
     * Define the Action's options
     *
     * @return void
     */
    public function options()
    {

        return array(            
        Automator()->helpers->recipe->field->text(
            array(
            'option_code' => 'sa_mobile',
            'label'       => 'Mobile Number',           
            'input_type'  => 'text',
            )
        ),
        Automator()->helpers->recipe->field->text(
            array(
                    'option_code' => 'SMS_BODY',
                    'label'       => 'SMS BODY',
                    'input_type'  => 'textarea',
                    'required'         => true,
                    'tokens'           => true,
                    'supports_tinymce' => false,
            )
        ),
        );
    }
    
    /**
     * Define_tokens
     *
     * @return array
     */
    public function define_tokens()
    {
        return array(
        'STATUS' => array(
        'name' => __('Send status', 'sms-alert'),
        'type' => 'text',
        ),
        );
    }

    /**
     * Process_action
     *
     * @param int   $user_id     as user_id
     * @param array $action_data as action_data
     * @param int   $recipe_id   as recipe_id 
     * @param array $args        as args
     * @param $parsed      as parsed
     *
     * @return array
     */
    protected function process_action( $user_id, $action_data, $recipe_id, $args, $parsed )
    {        
        $action_meta = $action_data['maybe_parsed'];
        $to = wp_filter_post_kses(stripslashes(( Automator()->parse->text($action_meta['sa_mobile'], $recipe_id, $user_id, $args) )));
        $body = wp_filter_post_kses(stripslashes(( Automator()->parse->text($action_meta['SMS_BODY'], $recipe_id, $user_id, $args) )));
        
        //Send sms        
        do_action('sa_send_sms', $to, $body);
        $status = true;
        $status_string = $status ? __('SMS was sent', 'sms-alert') : __('SMS was not sent', 'sms-alert');
        $this->hydrate_tokens( 
            array( 
            'STATUS' => $status_string 
            ) 
        );
        if (! $status ) {
            $this->add_log_error($status_string);
            return false;
        }
        return true;
    }
}