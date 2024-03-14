<?php

class WPEM_Single_Venue extends ET_Builder_Module {

    public $slug       = 'wpem_single_venue';
    public $vb_support = 'off';

    protected $module_credits = array(
        'module_uri' => 'www.wp-eventmanager.com',
        'author'     => 'Wpem Team',
        'author_uri' => 'www.wp-eventmanager.com',
    );

    public function init() {
        $this->name = esc_html__( 'Single Venue', 'wpem-wpem-event-dashboard' );
    }

    public function get_fields() {
        $args = array(
            'post_type'		=> 'event_venue',
            'post_status'	=> 'publish',
            'posts_per_page'=> -1,
            'suppress_filters' => 0,
        );

        $venues = get_posts( $args );

        $options = [];
        if ( !empty( $venues ) ) {
            foreach ( $venues as $venue ) {
                $options[$venue->ID] = $venue->post_title;
            }
        } else {
            $options[] = __( 'Not Found Venue', 'wp-event-manager' );
        }
        return array(
            'venue_id'                => array(
                'label'            => esc_html__( 'Select Venue', 'wp-event-manager-divi-elements' ),
                'type'             => 'select',
                'option_category'  => 'basic_option',
                'options'          => $options,
                'default_on_front' => '',
                'description'      => esc_html__( 'Choose which type of event view you would like to display.', 'wp-event-manager-divi-elements' ),
                'toggle_slug'      => 'main_content',
            ),
        );
    }

    public function render( $attrs, $content, $render_slug ) {


        $venue_id = $this->props['venue_id'];

        $shortcode = sprintf(
            '[event_venue id="%1$s"]',
            esc_attr( $venue_id ),
        );

        $output_event = do_shortcode( $shortcode );

        $output = sprintf(
            '<div>
				%1$s
			</div>',
            $output_event
        );

        return $output;
    }
}

new WPEM_Single_Venue;