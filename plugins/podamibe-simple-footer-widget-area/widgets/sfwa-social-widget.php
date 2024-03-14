<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );

global $social_accounts;
$social_accounts = array(
    'twitter' => 'twitter',
    'facebook' => 'facebook',
    'pinterest-p' => 'pinterest',
    'instagram' => 'instagram',
    'tumblr' => 'tumblr',
    'snapchat-ghost' => 'snapchat',
    'vimeo' => 'vimeo',
    'vine' => 'vine',
    'youtube' => 'youtube',
);
if ( ! class_exists( 'sfwa_social_Widget' ) ) {
    class sfwa_social_Widget extends WP_Widget {
    /**
     * Sets up the widgets name etc
     */
        public function __construct() {
            parent::__construct(
                'sfwa_social_Widget', // Base ID
                'SFWA Social Icons', // Widget Name
                array(
                    'classname' => 'social-icons',
                    'description' => 'Social icons with the link to your profile.',
                ),
                array(
                    'width' => 600,
                )
            );
        }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */

        function widget( $args, $instance ) {
    global $social_accounts;
            if ( ! isset( $args['widget_id'] ) ) $args['widget_id'] = null;
            extract( $args, EXTR_SKIP );

            echo $before_widget;

            $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base);
            if( $title ) echo $before_title . $title . $after_title;
            echo '<div class="sfwa-social-icons">';
            foreach($social_accounts as $social_title => $id) :
                if($instance[$id] != '' && $instance[$id] != 'http://') :
                    echo '<a href="'.esc_attr($instance[$id]).'" title="'.$social_title.'" target="_blank">';
                    echo '<i class="fa fa-'.$social_title.'"></i>';
                    echo '</a>';
                endif;
            endforeach;
            echo '</div>';
            echo $after_widget;
        }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     */
        function update( $new_instance, $old_instance ) {
            global $social_accounts;
            $instance = array();
            foreach ($social_accounts as $site => $id) {
                $instance[$id] = $new_instance[$id];
            }
            $instance['title'] = $new_instance['title'];

            return $instance;
        }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
        function form( $instance ) {
            global $social_accounts;
            foreach ($social_accounts as $site => $id) {
                if(!isset($instance[$id])) { 
                    $instance[$id] = ''; 
                }
                elseif($instance[$id] == 'http://') { 
                    $instance[$id] = ''; 
                }
            }
            if(!isset($instance['title'])) { $instance['title'] = ''; }
    ?>
        <div class="wordpress">
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:',SFWA_TEXT_DOMAIN); ?></label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
            </p>
            <h3><?php _e('Social Links',SFWA_TEXT_DOMAIN);?></h3>
            <ul class="social_accounts widefat">
                <?php foreach ($social_accounts as $site => $id) : ?>
                    <li style="width:45%;display:inline-block;margin-right:15px;"><label for="<?php echo $this->get_field_id($id); ?>" class="<?php echo $id; ?>"><?php echo $id; ?>:</label>
                        <input class="widefat" type="text" id="<?php echo $this->get_field_id($id); ?>" name="<?php echo $this->get_field_name($id); ?>" value="<?php echo esc_attr($instance[$id]); ?>" placeholder="http://" /></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <?php
        }
    }
}
?>