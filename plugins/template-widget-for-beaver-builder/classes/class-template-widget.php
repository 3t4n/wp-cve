<?php

/**
 * @class BB_Template_Widget Extends WP_Widget class.
 */
class BB_Template_Widget extends WP_Widget
{
    /**
	 * Register widget with WordPress.
	 */
    public function __construct()
    {
        parent::__construct(
            'bb_template_widget',
            esc_html__( 'Beaver Builder Template', 'bb-template-widget' ),
            array(
                'classname'     => 'BB_Template_Widget',
                'description'   => esc_html__( 'A widget to display saved templates of Beaver Builder.', 'bb-template-widget' ),
            )
        );
    }

    /**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
    public function widget( $args, $instance )
    {
        $title = apply_filters( 'widget_title', $instance[ 'title' ] );
        $template = isset( $instance['template'] ) ? $instance['template'] : '';
        $template_site = isset( $instance['template_site'] ) ? $instance['template_site'] : '';

        echo $args['before_title'] . $title . $args['after_title'];

        if ( $template && 'none' != $template ) {
            ob_start();
            if ( absint( $template_site ) == 1 ) {
                echo do_shortcode('[fl_builder_insert_layout id="'.$template.'" site="1"]');
            }
            else {
                echo do_shortcode('[fl_builder_insert_layout id="'.$template.'"]');
            }
            echo ob_get_clean();
        }
    }

    /**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
    public function form( $instance )
    {
        $title          = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $template       = isset( $instance['template'] ) ? $instance['template'] : 'none';
        $template_site  = isset( $instance['template_site'] ) ? $instance['template_site'] : null;
        $saved_modules  = twbb_get_saved_templates( 'module' ); // Get saved modules.
        $saved_rows     = twbb_get_saved_templates( 'row' ); // Get saved rows.
        $saved_layouts  = twbb_get_saved_templates( 'layout' ); // Get saved layouts.
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'template' ); ?>">Select Template:</label>
            <select class="widefat bb-template-widget-select" id="<?php echo $this->get_field_id( 'template' ); ?>" name="<?php echo $this->get_field_name( 'template' ); ?>">
                <option value="none"><?php _e( 'None', 'bb-template-widget' ); ?></option>
                <?php if ( count( $saved_modules ) ) : ?>
                    <optgroup label="<?php _e( 'Saved Modules', 'bb-template-widget' ); ?>">
                        <?php foreach ( $saved_modules as $key => $value ) : ?>
                            <option data-site="<?php echo $value['site']; ?>" value="<?php echo $key; ?>"<?php echo $template == $key ? ' selected="selected"' : ''; ?>><?php echo $value['title']; ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endif; ?>
                <?php if ( count( $saved_rows ) ) : ?>
                    <optgroup label="<?php _e( 'Saved Rows', 'bb-template-widget' ); ?>">
                        <?php foreach ( $saved_rows as $key => $value ) : ?>
                            <option data-site="<?php echo $value['site']; ?>" value="<?php echo $key; ?>"<?php echo $template == $key ? ' selected="selected"' : ''; ?>><?php echo $value['title']; ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endif; ?>
                <?php if ( count( $saved_layouts ) ) : ?>
                    <optgroup label="<?php _e( 'Saved Layouts', 'bb-template-widget' ); ?>">
                        <?php foreach ( $saved_layouts as $key => $value ) : ?>
                            <option data-site="<?php echo $value['site']; ?>" value="<?php echo $key; ?>"<?php echo $template == $key ? ' selected="selected"' : ''; ?>><?php echo $value['title']; ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endif; ?>
            </select>
            <?php if ( is_multisite() ) { ?>
                <input type="hidden" name="<?php echo $this->get_field_name( 'template_site' ); ?>" value="<?php echo $template_site; ?>" />
            <?php } ?>
        </p>
        <?php
        $this->print_scripts();
    }

    /**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
    public function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
        $instance[ 'template' ] = $new_instance[ 'template' ];
        $instance[ 'template_site' ] = $new_instance[ 'template_site' ];

        return $instance;
    }

    /**
	 * Render script.
	 *
	 * @since 1.0.1
     */
    public function print_scripts()
    {
        if ( is_multisite() ) {
        ?>
        <script type="text/javascript">
        (function($) {
            $('.bb-template-widget-select').on('change', function() {
                var siteId = $(this).find('option:selected').data('site');
                $(this).parent().find('input[type="hidden"]').val(siteId);
            });
        })(jQuery);
        </script>
        <?php
        }
    }
}

// Register BB_Template_Widget widget.
function twbb_register_widget() {
    register_widget( 'BB_Template_Widget' );
}
add_action( 'widgets_init', 'twbb_register_widget' );
