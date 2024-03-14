<?php

class WPEM_Event_Summary extends ET_Builder_Module {

    public $slug       = 'wpem_single_event_summary';
    public $vb_support = 'off';

    protected $module_credits = array(
        'module_uri' => 'www.wp-eventmanager.com',
        'author'     => 'Wpem Team',
        'author_uri' => 'www.wp-eventmanager.com',
    );

    public function init() {
        $this->name = esc_html__( 'Single Event Summary', 'wpem-wpem-event-dashboard' );
    }

    public function get_fields() {
        $args = array(
            'post_type'		=> 'event_listing',
            'post_status'	=> 'publish',
            'posts_per_page'=> -1,
            'suppress_filters' => 0,
        );

        $events = get_posts( $args );

        $options = [];
        if ( !empty( $events ) ) {
            foreach ( $events as $event ) {
                $options[$event->ID] = $event->post_title;
            }
        } else {
            $options[] = __( 'Not Found Event', 'wp-event-manager' );
        }
        return array(
            'event_id'                => array(
                'label'            => esc_html__( 'Select Event', 'wp-event-manager-divi-elements' ),
                'type'             => 'select',
                'option_category'  => 'basic_option',
                'options'          => $options,
                'default_on_front' => '',
                'description'      => esc_html__( 'Choose which type of event view you would like to display.', 'wp-event-manager-divi-elements' ),
                'toggle_slug'      => 'main_content',
            ),
            'width'        => array(
                'default'          => '250px',
                'label'            => esc_html__( 'Width', 'wp-event-manager-divi-elements' ),
                'type'             => 'text',
                'option_category'  => 'configuration',
                'description'      => esc_html__( 'Define the number of events that should be displayed per page.', 'wp-event-manager-divi-elements' ),
                'computed_affects' => array(
                    '__event_listing',
                ),
                'toggle_slug'      => 'main_content',
            ),
            'align'             => array(
                'label'            => esc_html__( 'Align', 'wp-event-manager-divi-elements' ),
                'type'             => 'select',
                'option_category'  => 'configuration',
                'options'          => array(
                    'left' => esc_html__( 'Left', 'wp-event-manager-divi-elements' ),
                    'center' => esc_html__( 'Center', 'wp-event-manager-divi-elements' ),
                    'right' => esc_html__( 'Right', 'wp-event-manager-divi-elements' ),

                ),
                'default_on_front' => 'left',
                'description'      => esc_html__( 'Choose how featured event will show.', 'wp-event-manager-divi-elements' ),
                'toggle_slug'      => 'main_content',
            ),
            'featured'             => array(
                'label'            => esc_html__( 'Featured', 'wp-event-manager-divi-elements' ),
                'type'             => 'select',
                'option_category'  => 'configuration',
                'options'          => array(
                    'true' => esc_html__( 'True', 'wp-event-manager-divi-elements' ),
                    'false' => esc_html__( 'False', 'wp-event-manager-divi-elements' ),

                ),
                'default_on_front' => '',
                'description'      => esc_html__( 'Choose how featured event will show.', 'wp-event-manager-divi-elements' ),
                'toggle_slug'      => 'main_content',
            ),
        );
    }

    public function render( $attrs, $content, $render_slug ) {

        $event_id = $this->props['event_id'];
        $width = $this->props['width'];
        $align = $this->props['align'];
        $featured = $this->props['featured'];

        $shortcode = sprintf(
            '[event_summary id="%1$s" width="%2$s" align="%3$s" featured="%4$s"]',
            esc_attr( $event_id ),
            esc_attr( $width ),
            esc_attr( $align ),
            esc_attr( $featured ),
        );

        $output_events = do_shortcode( $shortcode );

        $output = sprintf(
            '<div>
				%1$s
			</div>',
            $output_events
        );

        return $output;
    }
}

new WPEM_Event_Summary;