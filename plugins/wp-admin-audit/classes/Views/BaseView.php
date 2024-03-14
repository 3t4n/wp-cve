<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

trait WADA_View_BaseView
{
    abstract protected function execute();
    public $messages = array();

    public function getCurrentPage(){
        if(isset($_GET['page'])){
            return esc_attr(sanitize_text_field(wp_unslash($_GET['page'])));
        }
        return false;
    }

    public function displayMessages(){
        $html = '';
        foreach($this->messages AS $key => $message){
            $html .= '<div class="notice notice-'.esc_attr($message['type']).' notice-no-'.esc_attr($key).'">';
            $html .= '<p><strong>'.esc_html($message['message']).'</strong></p>';
            $html .= '</div>';
        }
        echo $html;
    }

    public function enqueueMessage($message, $type='info'){
        $type = in_array($type, array('info', 'success', 'warning', 'error')) ? $type : 'info';
        $this->messages[] = array('message'=>$message, 'type'=>$type);
    }
}