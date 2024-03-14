<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
if ( ! class_exists( 'sfwa_ads' ) ) {
class sfwa_ads extends WP_Widget {

/**
 * Sets up the widgets name etc
 */
    public function __construct() {
		$widget_ops = array( 
			'classname' => 'sfwa_ads',
			'description' => __( 'Add advertisement', SFWA_TEXT_DOMAIN ),
		);
		parent::__construct( 'sfwa_ads', __('SFWA Advertisement Widget', SFWA_TEXT_DOMAIN), $widget_ops );
	}

/**
 * Outputs the content of the widget
 *
 * @param array $args
 * @param array $instance
 */

    function widget( $args, $instance ) {

        if ( ! isset( $args['widget_id'] ) ) $args['widget_id'] = null;
        extract( $args, EXTR_SKIP );

        echo $before_widget;

        $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base);
        if( $title ) echo $before_title . $title . $after_title;
        
        if($instance['layout'] == 'half'){
            $sfwa_layout = 'sfwa-half-grid';
        }else{
            $sfwa_layout = 'sfwa-full-grid';
        }
        echo '<div class="clearfix sfwa_adv_wrapper '.$sfwa_layout.'">';
        for($i=1; $i<=4;$i++){
            if($instance['sfwa_ad_check'.$i.''] == 'image'){
                if($instance['sfwa_ad_img_'.$i.'']){
                    echo '<div class="sfwa_adv_grid">';
                    if($instance['sfwa_ad_link_'.$i.'']){
                        $before_link = '<a href="'.$instance['sfwa_ad_link_'.$i.''].'">';
                        $after_link = '</a>';
                    }
                    echo $before_link;
                    echo '<img class="sfwa_img_advertisement" src="'.$instance['sfwa_ad_img_'.$i.''].'">';
                    echo $after_link;
                    echo '</div>';
                }
            }elseif($instance['sfwa_ad_check'.$i.''] == 'code'){
                if($instance['sfwa_ad_code_'.$i.'']){
                    echo '<div class="sfwa_adv_grid">';
                    echo $instance['sfwa_ad_code_'.$i.''];
                    echo '</div>';
                }
            }
        }
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
        $instance = $old_instance;

        $instance['title'] 	= strip_tags( $new_instance['title'] );
        $instance['layout'] 	= strip_tags( $new_instance['layout'] );
        for($i=1; $i<=4;$i++){
            $instance['sfwa_ad_img_'.$i.''] 	= strip_tags( $new_instance['sfwa_ad_img_'.$i.''] );
            $instance['sfwa_ad_link_'.$i.''] 	= strip_tags( $new_instance['sfwa_ad_link_'.$i.''] );
            $instance['sfwa_ad_code_'.$i.''] 	= $new_instance['sfwa_ad_code_'.$i.''];
            $instance['sfwa_ad_check'.$i.''] 	= $new_instance['sfwa_ad_check'.$i.''];
        }
        
        return $instance;
    }

/**
 * Outputs the options form on admin
 *
 * @param array $instance The widget options
 */
    function form( $instance ) {
        $title 	= isset( $instance['title']) 	? esc_attr( $instance['title'] ) 	: '';
?>
    <div class="sfwa_widget_form">
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php esc_html_e( 'Title:', SFWA_TEXT_DOMAIN ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php esc_html_e( 'Layout:', SFWA_TEXT_DOMAIN ); ?>
            </label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>">
                <option value="full" <?php selected($instance['layout'], 'full'); ?>>Full width</option>
                <option value="half" <?php selected($instance['layout'], 'half'); ?>>Half width</option>
            </select>
        </p>
        <p class="description">
            Recommended sizes:
            <br>125x125 </p>
        <?php for($i=1; $i<=4;$i++){ ?>
            <p><strong><?php esc_html_e( 'Ad '.$i.':', SFWA_TEXT_DOMAIN ); ?></strong></p>
            <div class="tab_wrapper">
                <input class="adv_image_input" type="radio" name="<?php echo $this->get_field_name('sfwa_ad_check'.$i.''); ?>" value="image" <?php if($instance['sfwa_ad_check'.$i.'']=='' || $instance['sfwa_ad_check'.$i.''] == 'image'){echo 'checked';} ?>> <?php esc_html_e( 'Image Adv', SFWA_TEXT_DOMAIN ); ?>
                &nbsp;
                <input class="adv_code_input" type="radio" name="<?php echo $this->get_field_name('sfwa_ad_check'.$i.''); ?>" value="code" <?php if($instance['sfwa_ad_check'.$i.''] == 'code'){echo 'checked';} ?>> <?php esc_html_e( 'Code Adv', SFWA_TEXT_DOMAIN ); ?>
                <div class="sfwa_image_adv">
                    <p>
                        <label for="<?php echo $this->get_field_id('sfwa_ad_img_'.$i.''); ?>">
                            <?php esc_html_e( 'Image Link:', SFWA_TEXT_DOMAIN ); ?>
                        </label>
                        <input class="widefat" type="text" id="<?php echo $this->get_field_id('sfwa_ad_img_'.$i.''); ?>" name="<?php echo $this->get_field_name('sfwa_ad_img_'.$i.''); ?>" value="<?php echo $instance['sfwa_ad_img_'.$i.'']; ?>" />
                    </p>
                    <p>
                        <label for="<?php echo $this->get_field_id('sfwa_ad_link_'.$i.''); ?>">
                            <?php esc_html_e( 'Ad Link:', SFWA_TEXT_DOMAIN ); ?>
                        </label>
                        <input class="widefat" type="text" id="<?php echo $this->get_field_id('sfwa_ad_link_'.$i.''); ?>" name="<?php echo $this->get_field_name('sfwa_ad_link_'.$i.''); ?>" value="<?php echo $instance['sfwa_ad_link_'.$i.'']; ?>" />
                    </p>
                </div>
                <div class="sfwa_code_adv">
                    <p>
                        <label for="<?php echo $this->get_field_id('sfwa_ad_code_'.$i.''); ?>">
                            <?php esc_html_e( 'Code:', SFWA_TEXT_DOMAIN ); ?>
                        </label>
                        <textarea id="<?php echo $this->get_field_id('sfwa_ad_code_'.$i.''); ?>" class="widefat" rows="3" name="<?php echo $this->get_field_name('sfwa_ad_code_'.$i.''); ?>"><?php echo $instance['sfwa_ad_code_'.$i.'']; ?></textarea>
                    </p>
                </div>
            </div>
        <?php 
           }
        ?>
    </div>
    <style>
        .sfwa_image_adv,
        .sfwa_code_adv {
            display: none;
        }
        
        .tab_wrapper input.adv_image_input:checked ~ .sfwa_image_adv {
            display: block;
        }
        
        .tab_wrapper input.adv_code_input:checked ~ .sfwa_code_adv {
            display: block;
        }
    </style>
    <?php
    }
}
}
?>