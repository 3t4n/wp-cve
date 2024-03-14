<?php

class WPEM_Submit_Event_Form extends ET_Builder_Module {

    public $slug       = 'wpem_submit_event_form';
    public $vb_support = 'off';

    protected $module_credits = array(
        'module_uri' => 'www.wp-eventmanager.com',
        'author'     => 'Wpem Team',
        'author_uri' => 'www.wp-eventmanager.com',
    );

    public function init() {
        $this->name = esc_html__( 'Submit Event Form', 'wpem-wpem-event-dashboard' );
    }

    public function get_fields() {
        return array();
    }

    public function render( $attrs, $content, $render_slug ) {


        $output_event = do_shortcode( '[submit_event_form]' );

        $output = sprintf(
            '<div>
				%1$s
			</div>',
            $output_event
        );

        return $output;
    }
}

new WPEM_Submit_Event_Form;