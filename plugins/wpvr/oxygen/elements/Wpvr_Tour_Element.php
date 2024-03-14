<?php

class Wpvr_Tour_Element extends WPVR_CUSTOM_OXY_ELEMENT
{

    public function name()
    {
        return 'WP VR Tour';
    }

    public function controls()
    {
        /*
         * Adds a control to the element or a section (depends on the caller)
         */

         $posts = get_posts([
            'post_type'         => 'wpvr_item',
            'post_status'       => 'publish',
            'orderby'           => 'ID',
            'order'             => 'DESC',
            'numberposts'       => -1,
          ]);

          $array = array();
          $array[0] = "None";
          foreach ($posts as $post) {
             $id = $post->ID;
             $title = $id .' : '.$post->post_title;
             if (!$post->post_title) {
               $title = $id .' : '."No title";
             }
             $array[$id] = $title;
          }
        $this->addOptionControl([
            "type" => "dropdown",
            "name" => "Tour ID",
            "slug" => "tour_id",
            "value" => $array
        ]);

        $this->addOptionControl([
            "type" => "textfield",
            "name" => "Height",
            "slug" => "tour_height",
            "value" => "400"
        ]);
        $this->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => __("Height Unit","wpvr"),
                "slug" => 'tour_height_unit',
                "default" => "px",
            )
        )->setValue(array(
            'px'       => __('px' ,"wpvr"),
            'vh'       => __('vh',"wpvr" ),
        ))->rebuildElementOnChange();

        $this->addOptionControl([
            "type" => "textfield",
            "name" => "Width",
            "slug" => "tour_width",
            "value" => "600",
            "condition" => 'tour_width_fullwidth=off',
        ]);
        $this->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => __("Width Unit","wpvr"),
                "slug" => 'tour_width_unit',
                "default" => "px",
                "condition" => 'tour_width_fullwidth=off',
            )
        )->setValue(array(
            'px'       => __('px' ,"wpvr"),
            '%'       => __('%',"wpvr" ),
            'vw'       => __('vw',"wpvr" ),
        ))->rebuildElementOnChange();
        $this->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => __("Width Fullwidth","wpvr"),
                "slug" => 'tour_width_fullwidth',
                "default" => "off",
            )
        )->setValue(array(
            'on'       => __('ON' ,"wpvr"),
            'off'       => __('OFF',"wpvr" ),
        ))->rebuildElementOnChange();

        $this->addOptionControl([
            "type" => "textfield",
            "name" => "Radius",
            "slug" => "tour_radius",
            "value" => "0"
        ]);
        $this->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => __("Radius Unit","wpvr"),
                "slug" => 'tour_radius_unit',
                "default" => "px",
            )
        )->setValue(array(
            'px'       => __('px' ,"wpvr"),
        ))->rebuildElementOnChange();

        $this->addOptionControl([
            "type" => "textfield",
            "name" => "Mobile Height",
            "slug" => "tour_mobile_height",
            "value" => ""
        ]);
        $this->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => __("Mobile Height Unit","wpvr"),
                "slug" => 'tour_mobile_height_unit',
                "default" => "px",
            )
        )->setValue(array(
            'px'       => __('px' ,"wpvr"),
        ))->rebuildElementOnChange();

    }

    /*
     * @param {array} $options   values you set in the controls
     * @param {array} $defaults  default values for all controls
     * @param {array} $content   shortcode that holds all nested elements (more on this later)
     */
    public function render($options, $defaults, $content)
    {

        $id = 0;
        $width = "600px";
        $height = "400px";
        $radius = "0px";
        $id = $options['tour_id'];
        $width = $options['tour_width'].$options['tour_width_unit'];
        $height = $options['tour_height'].$options['tour_height_unit'];
        $radius = $options['tour_radius'].$options['tour_radius_unit'];
        $tour_mobile_height = $options['tour_mobile_height'].$options['tour_mobile_height_unit'];
        if (empty($width)) {
            $width = "600px";
        }
        if($options['tour_width_fullwidth'] == 'on'){
            $width = "fullwidth";
        }
        if (empty($height)) {
            $height = "400px";
        }
        if (empty($radius)) {
            $radius = "0px";
        }
        if(empty($tour_mobile_height)){
            $tour_mobile_height = "300px";
        }

        if ($id) {
            $shortcode = do_shortcode( shortcode_unautop( '[wpvr id="'.$id.'" width="'.$width.'" height="'.$height.'" radius="'.$radius.'" mobile_height="'.$tour_mobile_height.'"]'  ) );
            echo $shortcode;
        }
    }
}

new Wpvr_Tour_Element();
