<?php

require_once FNSF_AF2_MENU_PARENTS_CLASS;
class Fnsf_Af2LeadsDetails extends Fnsf_Af2MenuCustom {

    protected function fnsf_get_heading() { 
        $post = get_post(sanitize_text_field($_GET['id']));
        return __('Lead from', 'funnelforms-free').' '.get_post_field('post_date', $post); 
    }
    protected function fnsf_get_menu_custom_template() { return FNSF_AF2_CUSTOM_MENU_LEADDETAILS; }
    protected function fnsf_get_show_sidebar_() { return true; }

    protected function fnsf_get_af2_custom_contents_() { 
        return $this->fnsf_get_lead_details(true);
    }

    protected function fnsf_load_resources() {
        wp_enqueue_style('af2_leaddetails_style');
        parent::fnsf_load_resources();
    }

    protected function fnsf_get_menu_hook_inline_button_form_() { 
        $idvalled = sanitize_text_field($_GET['id']);
        return array( 
            'icon' => 'fas fa-download', 
            'label' => __('Export CSV file' , 'funnelforms-free'), 
            'id' => 'af2_export_csv', 
            'id_label' => 'id',
            'id_value' => $idvalled,
            'bonus_label' => 'af2_single_lead_download',
            'bonus_value' => 'true',
            'bonus_class' => 'af2_btn_disabled af2_btn_disabled_free',
        ); 
    }
    

    private function fnsf_get_lead_details($get) {
        $idGetled  = sanitize_text_field($_GET['id']);
        if(isset($_POST['id'])){
            $idPostled = sanitize_text_field($_POST['id']);
        }
        
        $post = get_post($get ? $idGetled : $idPostled );

        $lead_details_array = array();

        $post_content = unserialize( urldecode( get_post_field( 'post_content', $post ) ) );

        if(isset($post_content['questions']) && is_array($post_content['questions'])) {
            foreach( $post_content['questions'] as $field ) {
                $label = $field['frage'];

                $value = $field['antwort'];
                if( is_array( $field['antwort'] ) ) {
                    $value = '';
                    $i = 0;
                    for ($i=0; $i < sizeof($field['antwort']); $i++) { 
                        $value .= $field['antwort'][$i];
                        if($i < sizeof($field['antwort'])-1) $value .= ', ';
                    }
                }
                $new_field = array( 'label' => $label, 'value' => $value );
                array_push($lead_details_array, $new_field);
            }
        }

        if(isset($post_content['contact_form']) && is_array($post_content['contact_form'])) {
            foreach( $post_content['contact_form'] as $field ) {

                if(isset($field['label']) && !empty($field['label'])){
                            $label = $field['label'] ;
                        }else{
                                $label = $field['id'];
                        }
                /*  $label = isset($field['label']) && !empty($field['label']) ? $field['label'] : $field['id'];  */ 
                $val = $field['input'];
                if($field['input'] === 'true' || $field['input'] === true) $val = __('true', 'funnelforms-free');
                if($field['input'] === 'false' || $field['input'] === false) $val = __('false', 'funnelforms-free');
                $new_field = array( 'label' => $label, 'value' => $val );
                array_push($lead_details_array, $new_field);
            }
        }

        if(isset($post_content['analyticsData']) && is_array($post_content['analyticsData'])) {
            foreach( $post_content['analyticsData'] as $field ) {
                $label = $field['id'];
                if($label == 'queryString') $label = __('Received URL parameters', 'funnelforms-free');
                if($label == 'url') $label = __('URL on which the form was submitted', 'funnelforms-free');
                $new_field = array( 'label' => $label, 'value' => $field['value'] );
                array_push($lead_details_array, $new_field);
            }
        }

        if(!isset($post_content['questions']) && !isset($post_content['contact_form']) && !isset($post_content['analyticsData'])) {
            foreach($post_content as $field) {

                if(isset($field['title']) && !empty($field['title'])){
                            $label = $field['label'] ;
                        }else{
                                $label = $field['id'];
                        }
                /* $label = isset($field['title']) && !empty($field['title']) ? $field['title'] : $field['id']; */
                 if(is_array($field['answer'])){
                            $value = '---' ;
                        }else{
                            $value = $field['answer'] ;
                        }
                $value = is_array($field['answer']) ? '---' : $field['answer'];

                $new_field = array( 'label' => $label, 'value' => $value );
                array_push($lead_details_array, $new_field);
            }
        }


        return $lead_details_array;
    }
}
