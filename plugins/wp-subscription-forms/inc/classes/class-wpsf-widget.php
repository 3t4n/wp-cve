<?php
if ( !class_exists( 'WPSF_Widget' ) ) {

    class WPSF_Widget extends WP_Widget {

        function __construct() {

            parent::__construct(
                    'WPSF_Widget', // Base ID
                    'WP Subscription Forms'   // Name
            );

            add_action( 'widgets_init', function() {
                register_widget( 'WPSF_Widget' );
            } );
        }

        public $args = array(
            'before_title' => '<h4 class="widgettitle">',
            'after_title' => '</h4>',
            'before_widget' => '<div class="widget-wrap">',
            'after_widget' => '</div></div>'
        );

        public function widget( $args, $instance ) {

            echo $args['before_widget'];

            if ( !empty( $instance['title'] ) ) {
                echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
            }

            echo '<div class="textwidget">';
            if ( !empty( $instance['form_alias'] ) ) {
                echo do_shortcode( '[wp_subscription_forms alias="' . $instance['form_alias'] . '"]' );
            }

            echo '</div>';

            echo $args['after_widget'];
        }

        public function form( $instance ) {

            $title = !empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'wp-subscription-forms' );
            $form_alias = !empty( $instance['form_alias'] ) ? $instance['form_alias'] : esc_html__( '', 'wp-subscription-forms' );
            ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title', 'wp-subscription-forms' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
            </p>
            <p>

                <label for="<?php echo esc_attr( $this->get_field_id( 'Text' ) ); ?>"><?php echo esc_html__( 'Subscription Forms', 'wp-subscription-forms' ); ?></label>
                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'form_alias' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'form_alias' ) ); ?>">
                    <option value=""><?php esc_html_e( 'Choose Form', 'wp-subscription-forms' ); ?></option>
                    <?php
                    global $wpdb;
                    $subscription_form_table = WPSF_FORM_TABLE;
                    $forms = $wpdb->get_results( "select * from $subscription_form_table order by form_title asc" );
                    if ( !empty( $forms ) ) {
                        foreach ( $forms as $form ) {
                            ?>
                            <option value="<?php echo esc_attr( $form->form_alias ); ?>" <?php selected( $form_alias, $form->form_alias ); ?>><?php echo esc_html( $form->form_title ); ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>

            </p>
            <?php
        }

        public function update( $new_instance, $old_instance ) {

            $instance = array();

            $instance['title'] = (!empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
            $instance['form_alias'] = (!empty( $new_instance['form_alias'] ) ) ? sanitize_text_field( $new_instance['form_alias'] ) : '';

            return $instance;
        }

    }

    new WPSF_Widget();
}