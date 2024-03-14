<?php

if( !defined( 'ABSPATH') ) exit();

class Hybrid_Gallery_Attach_Fields
{
    function __construct()
    {        
        add_filter('attachment_fields_to_edit', array(
            $this,
            'applyFilter'
        ), 11, 2);
        add_filter('attachment_fields_to_save', array(
            $this,
            'saveFields'
        ), 11, 2);
    }
    
    public function applyFilter($form_fields, $post = null)
    {
        if ($this->options()) {
            foreach ($this->options() as $field => $values) {
                if ($post->post_mime_type == "image/jpeg" || $post->post_mime_type == "image/png" || $post->post_mime_type == "image/gif") {
                    
                    $meta = get_post_meta($post->ID, "_" . $field, true);
                    
                    switch ($values['input']) {
                        default:
                        case 'text':
                            $values['input'] = "text";
                            break;
                        
                        case 'textarea':
                            $values['input'] = "textarea";
                            break;
                        
                        case 'select':
                            
                            $values['input'] = "html";
                            
                            $html = "<select name='attachments[" . $post->ID . "][" . $field . "]'>";
                            
                            
                            if (isset($values['options'])) {
                                
                                foreach ($values['options'] as $k => $v) {
                                    
                                    if ($meta == $k)
                                        $selected = " selected='selected'";
                                    else
                                        $selected = "";
                                    
                                    $html .= "<option$selected value='" . $k . "'>" . $v . "</option>";
                                }
                            }
                            
                            $html .= "</select>";
                            
                            // Set the html content
                            $values['html'] = $html;
                            
                            break;
                        
                        case 'checkbox':
                            
                            // Checkbox type doesn't exist either
                            $values['input'] = "html";
                            
                            // Set the checkbox checked or not
                            if ($meta == "on")
                                $checked = " checked='checked'";
                            else
                                $checked = "";
                            
                            $html = "<input$checked type='checkbox' name='attachments[" . $post->ID . "][" . $field . "]' id='attachments-" . $post->ID . "-" . $field . "' />";
                            
                            $values['html'] = $html;
                            
                            break;
                        
                        case 'radio':
                            
                            $values['input'] = "html";
                            
                            $html = "";
                            
                            if (!empty($values['options'])) {
                                $i = 0;
                                
                                foreach ($values['options'] as $k => $v) {
                                    if ($meta == $k)
                                        $checked = " checked='checked'";
                                    else
                                        $checked = "";
                                    
                                    $html .= "<input$checked value='" . $k . "' type='radio' name='attachments[" . $post->ID . "][" . $field . "]' id='" . sanitize_key($field . "_" . $post->ID . "_" . $i) . "' /> <label for='" . sanitize_key($field . "_" . $post->ID . "_" . $i) . "'>" . $v . "</label><br />";
                                    $i++;
                                }
                            }
                            
                            $values['html'] = $html;
                            
                            break;
                    }
                    
                    $values['value'] = $meta;
                    
                    $form_fields[$field] = $values;
                }
            }
        }
        
        return $form_fields;
    }
    
    function saveFields($post, $attachment)
    {
        if ($this->options()) {
            foreach ($this->options() as $field => $values) {
                if (isset($attachment[$field])) {
                    update_post_meta($post['ID'], "_" . $field, $attachment[$field]);
                } else {
                    delete_post_meta($post['ID'], $field);
                }
            }
        }
        
        return $post;
    }
    
    public function options()
    {
        $options = array(
            'hybrig_gallery_attach_filter' => array(
                'label' => '(Hybrid Gallery) - ' . esc_html__('Filter', 'hybrid-gallery'),
                'input' => 'text',
                'helps' => '',
                'application' => 'image'
            ),
            'hybrig_gallery_attach_link' => array(
                'label' => '(Hybrid Gallery) - ' . esc_html__('Link', 'hybrid-gallery'),
                'input' => 'text',
                'helps' => '',
                'application' => 'image'
            ),
            'hybrig_gallery_attach_video' => array(
                'label' => '(Hybrid Gallery) - ' . esc_html__('Video', 'hybrid-gallery'),
                'input' => 'text',
                'helps' => '',
                'application' => 'image'
            ),
            'hybrig_gallery_attach_type' => array(
                'label' => '(Hybrid Gallery) - ' . esc_html__('Defaul Type', 'hybrid-gallery'),
                'input' => 'select',
                'options' => array(
                    'image' => esc_html__('Image', 'hybrid-gallery'),
                    'video' => esc_html__('Video', 'hybrid-gallery')
                ),
                'application' => 'image'
            )
        );

        return $options;
    }
    
}

new Hybrid_Gallery_Attach_Fields();