<?php declare(strict_types=1);

/**
 * Our main filter widget.
 *
 * @author
 */
class Mailup_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'mailup-widget',
            'MailUp Widget',
            [
                'description' => __('Add a MailUp Form to the sidebar', 'mailup'),
            ]
        );
    }

    public function form($instance): void
    {
        ?>
<p></p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        // processes widget options to be saved
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;
    }

    public function register_inline($style): void
    {
        wp_register_style('mupwp-inline-style', false, [Mailup::MAILUP_NAME()]);
        wp_enqueue_style('mupwp-inline-style');
        wp_add_inline_style('mupwp-inline-style', $style);
    }

    public function widget($args, $instance): void
    {
        $model = new Mailup_Model(Mailup::MAILUP_NAME());
        $form = $model->get_fe_form();

        // outputs the content of the widget
        if ($model->has_tokens() && $form) {
            extract($args);
            $title = apply_filters('widget_title', $instance['title']);
            echo $before_widget;
            ob_start();
            $this->register_inline($form->custom_css);

            include plugin_dir_path(__DIR__).'public/partials/mailup-public-display.php';
            echo ob_get_clean();

            echo $after_widget;
        }
    }
}
