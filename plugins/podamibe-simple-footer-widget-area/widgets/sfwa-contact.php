<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );

global $contact_info;
$contact_info = array(
    'Address' => 'address-card',
    'Phone' => 'phone',
    'Mobile' => 'mobile-phone',
    'Fax' => 'fax',
    'Email Address' => 'envelope',
    'Website URL (with HTTP)' => 'globe',
);

if ( ! class_exists( 'sfwa_contact_widget' ) ) {
class sfwa_contact_widget extends WP_Widget {
/**
 * Sets up the widgets name etc
 */
    public function __construct() {
		parent::__construct(
			'sfwa_contact_widget', // Base ID
			'SFWA Contact Info', // Widget Name
			array(
				'classname' => 'sfwa-contact-info',
				'description' => 'Add your contact info to footer.',
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
global $contact_info;
        if ( ! isset( $args['widget_id'] ) ) $args['widget_id'] = null;
        extract( $args, EXTR_SKIP );

        echo $before_widget;

        $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base);
        if( $title ) echo $before_title . $title . $after_title;
        echo '<ul class="sfwa-contact-informations">';
        foreach($contact_info as $contact => $id) :
	        if($instance[$id] != '') :
                echo '<li><span><i class="fa fa-'.$id.'"></i>';
                if($id == 'envelope'){
                    $before_envelope = '<a href="mailto:'.esc_attr($instance[$id]).'" title="'.$contact.'">';
                    $after_envelope = '</a>';
                }elseif($id == 'globe'){
                    $before_envelope = '<a href="'.esc_attr($instance[$id]).'" title="'.$contact.'" target="_blank">';
                    $after_envelope = '</a>';
                }else{
                    $before_envelope = '';
                    $after_envelope = '';
                }
                echo $before_envelope;
                echo esc_attr($instance[$id]);
                echo $after_envelope;
                echo '</span></li>';
            endif;
        endforeach;
        echo '</ul>';
        echo $after_widget;
    }
    
/**
 * Processing widget options on save
 *
 * @param array $new_instance The new options
 * @param array $old_instance The previous options
 */
    function update( $new_instance, $old_instance ) {
        global $contact_info;
        $instance = array();
        foreach ($contact_info as $site => $id) {
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
        global $contact_info;
        foreach ($contact_info as $site => $id) {
            if(!isset($instance[$id])) { 
                $instance[$id] = ''; 
            }
            elseif($instance[$id] == 'http://') { 
                $instance[$id] = ''; 
            }
        }
        if(!isset($instance['title'])) { $instance['title'] = ''; }
?>
    <div class="sfwa-widget-wrapper">
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:',SFWA_TEXT_DOMAIN); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>
        <h3><?php _e('Contact Information',SFWA_TEXT_DOMAIN);?></h3>
        <ul class="contact-info-options widefat">
            <?php foreach ($contact_info as $site => $id) : ?>
                <li style="width:45%;display:inline-block;margin-right:20px;">
                    <label for="<?php echo $this->get_field_id($id); ?>" class="<?php echo $id; ?>"><?php echo $site; ?>:</label>
                    <input class="widefat" type="text" id="<?php echo $this->get_field_id($id); ?>" name="<?php echo $this->get_field_name($id); ?>" value="<?php echo esc_attr($instance[$id]); ?>" placeholder="" />
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php
    }
}
}
?>