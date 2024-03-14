<?php

require_once FNSF_AF2_MENU_PARENTS_CLASS;
class Fnsf_Af2Checklist extends Fnsf_Af2MenuCustom {

    public $healthchecks = [];

    // protected function fnsf_get_heading() { return 'First steps'; }
    protected function fnsf_get_heading() { return __('First steps', 'funnelforms-free') ; }
    protected function fnsf_get_menu_custom_template() { return FNSF_AF2_CUSTOM_MENU_CHECKLIST; }

    protected function fnsf_get_af2_custom_contents_() { 
        require_once FNSF_AF2_HEALTHCHECK_PATH;
        $healthcheck = new Af2Healthcheck();

        $this->healthchecks = $healthcheck->get_healthchecks();

        $this->fnsf_check_checklist_options();
        return array(
            array( 'label' => __('Create the necessary questions for your first Funnelform', 'funnelforms-free') , 'success' => get_option('checklist_question') == 'true' ? true : false, 'url' => admin_url('/admin.php?page='.FNSF_FRAGE_SLUG.'&action=af2CreatePost&custom_post_type='.FNSF_FRAGE_POST_TYPE.'&redirect_slug='.FNSF_FRAGENBUILDER_SLUG.'&time='.time() ) ),
            array( 'label' => __('Create a contact form to collect the most important personal informations of your leads', 'funnelforms-free'), 'success' => get_option('checklist_contactform') == 'true' ? true : false, 'url' => admin_url('/admin.php?page='.FNSF_KONTAKTFNSF_FORMULAR_SLUG.'&action=af2CreatePost&custom_post_type='.FNSF_KONTAKTFNSF_FORMULAR_POST_TYPE.'&redirect_slug='.FNSF_KONTAKTFNSF_FORMULARBUILDER_SLUG.'&time='.time() ) ),
            array( 'label' => __('Create your first Funnelform by linking all questions and contact forms in the form editor', 'funnelforms-free') , 'success' => get_option('checklist_form') == 'true' ? true : false, 'url' => admin_url('/admin.php?page='.FNSF_FORMULAR_SLUG.'&action=af2CreatePost&custom_post_type='.FNSF_FORMULAR_POST_TYPE.'&redirect_slug='.FNSF_FORMULARBUILDER_SLUG.'&time='.time() ) ),
            array( 'label' => __('Copy the shortcode and embed the form on your website', 'funnelforms-free'), 'success' => get_option('checklist_shortcode') == 'true' ? true : false, 'url' => admin_url('/admin.php?page='.FNSF_FORMULAR_SLUG.'&time='.time() ) ),
        );
    }

    protected function fnsf_load_resources() {
        wp_enqueue_style('af2_checklist_style');
        parent::fnsf_load_resources();
    }

    private function fnsf_check_checklist_options() {
        require_once FNSF_AF2_MISC_FUNCTIONS_PATH;

        if(get_option('checklist_question') == 'true') {}
        else {
            $posts = $this->Admin->fnsf_af2_get_posts(FNSF_FRAGE_POST_TYPE);
            foreach($posts as $post) {
                update_option('checklist_question', 'true');
            }
        }

        if(get_option('checklist_contactform') == 'true') {}
        else {
            $posts = $this->Admin->fnsf_af2_get_posts(FNSF_KONTAKTFNSF_FORMULAR_POST_TYPE);
            foreach($posts as $post) {
                update_option('checklist_contactform', 'true');
            }
        }

        if(get_option('checklist_form') == 'true') {}
        else {
            $posts = $this->Admin->fnsf_af2_get_posts(FNSF_FORMULAR_POST_TYPE);
            foreach($posts as $post) {
                update_option('checklist_form', 'true');
            }
        }

        if(get_option('checklist_shortcode') == 'true') {}
        else {
            // Done in frontend.php
        }
    }
}