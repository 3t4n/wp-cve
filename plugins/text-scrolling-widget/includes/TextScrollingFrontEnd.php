<?php

class TextScrollingFrontEnd extends WP_Widget
{
    function __construct(){
        $params = array(
            'description'=>'Use this widget to show vertical and Horizontal scrolling text',
            'name'=>'Text Scrolling'

        );
        parent::__construct('TextScrolling','',$params);

    }

    public function form($instance){
        extract($instance);
        if($this->get_field_id('title') == '')
        {
            include_once('templates/text-scrolling-front-end.php');
        }else {


            ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
                <input
                    class="widefat"
                    id="<?php echo $this->get_field_id('title'); ?>"
                    name="<?php echo $this->get_field_name('title'); ?>"
                    value="<?php if (isset($title)) {
                        echo esc_attr($title);
                    } ?>"
                >
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('description'); ?>">Description:</label>
                <?php
                /*if (isset($description)) {
                    $content = esc_attr($description);
                }else{
                    $content = '';
                }

                $editor_id = 'description';
                $settings = array( 'media_buttons' => false );
                wp_editor( $content, $editor_id, $settings );*/
                ?>
                <textarea
                    class="widefat"
                    rows="10"
                    id="<?php echo $this->get_field_id('description'); ?>"
                    name="<?php echo $this->get_field_name('description'); ?>"
                ><?php if( isset($description) ) { echo esc_attr($description); } ?></textarea>
            </p>
            <?php
        }
    }

    public function widget($args,$instance){

        if(get_option( 'tsw_direction') !='' )
            {
            $direction = get_option( 'tsw_direction');
        }else{
            $direction = 'up';
        }

        if(get_option('tsw_speed') == ""){
            $speed = 2;
        }else{
            $speed = get_option('tsw_speed');
        }

        extract($args);
        extract($instance);

        echo $before_widget;
        echo $before_title . $title . $after_title;
        echo '<marquee behavior="scroll" scrollAmount="'.$speed.'" direction="'.$direction.'">'.$description.'</marquee>';
        echo $after_widget;

    }

}

?>