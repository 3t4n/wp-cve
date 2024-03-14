<?php
/*
* Plugin Name: Social Icon Widget
* Description: This is a simple plugin to disply social icons in widget areas
* Plugin URI: https://mostafizshamim.com/social-icon-widget
* Author: Mostafiz Shamim
* Author URI: https://mostafizshamim.com
* Version: 1.0.2
* License: GPL2
* Text Domain: socialiconwidget
*/

/*

    Copyright (C) 2016  Mostafiz, (email: mostafizsh@gmail.com) all rights reserved

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

    if ( !defined('ABSPATH') ) exit;

    include_once('social-icon-func.php');


    //register widget backend
    class social_icon_widget extends WP_Widget{
        public function __construct(){
            parent::__construct('social_icon_widget', __('Social Icon Widget', 'socialiconwidget'), array(
                    'description' => __('This is a custom widget to display your social links by social icons', 'socialiconwidget')
                ));
        }

        public function widget($args, $instance){

            $title = $instance['title'];

            $twitter = $instance['twitter'];

            $facebook = $instance['facebook'];

            $google_plus = $instance['google-plus'];

            $linkedin = $instance['linkedin'];

            $pinterest = $instance['pinterest'];

            $instagram = $instance['instagram'];

            $youtube = $instance['youtube'];

            $flickr = $instance['flickr'];

            $tumblr = $instance['tumblr'];

            $vine = $instance['vine'];

            $vk = $instance['vk'];

            $reddit = $instance['reddit'];

            $skype = $instance['skype'];

            $vimeo = $instance['vimeo'];

            $trello = $instance['trello'];

            $xing = $instance['xing'];

            $soundcloud = $instance['soundcloud'];

            $github = $instance['github'];

            $snapchat = $instance['snapchat'];


            //set Widget front end
            echo $args['before_widget'] ;
            
            if(isset($title) && !empty($title)){
                   
               echo  $args['before_title'] . $title . $args['after_title'] ;

            }
            if(isset($twitter) && !empty($twitter)){
                
               echo '<a title="Twitter" target="_blank" href="' . esc_url($twitter) . '"><i class="fa fa-twitter"></i></a>' ;

            }
            if(isset($facebook) && !empty($facebook)){
                
              echo '<a title="Facebook" target="_blank" href="' . esc_url($facebook) . '"><i class="fa fa-facebook"></i></a>' ;

            }
            if(isset($google_plus) && !empty($google_plus)){
                
              echo '<a title="Google Plus" target="_blank" href="' . esc_url($google_plus) . '"><i class="fa fa-google-plus"></i></a>' ;

            }
            if(isset($linkedin) && !empty($linkedin)){
                
              echo '<a title="LinkedIn" target="_blank" href="' . esc_url($linkedin) . '"><i class="fa fa-linkedin"></i></a>' ;

            }
            if(isset($pinterest) && !empty($pinterest)){
                
               echo '<a title="Pinterest" target="_blank" href="' . esc_url($pinterest) . '"><i class="fa fa-pinterest"></i></a>';

            }
            if(isset($instagram) && !empty($instagram)){
                
               echo '<a title="Instagram" target="_blank" href="' . esc_url($instagram) . '"><i class="fa fa-instagram"></i></a>';

            } 
            if(isset($youtube) && !empty($youtube)){
                
               echo '<a title="Youtube" target="_blank" href="' . esc_url($youtube) . '"><i class="fa fa-youtube"></i></a>';

            } 
            if(isset($flickr) && !empty($flickr)){
                
               echo '<a title="Flickr" target="_blank" href="' . esc_url($flickr) . '"><i class="fa fa-flickr"></i></a>';

            }
            if(isset($tumblr) && !empty($tumblr)){
                
               echo '<a title="Tumblr" target="_blank" href="' . esc_url($tumblr) . '"><i class="fa fa-tumblr"></i></a>';

            }
            if(isset($vine) && !empty($vine)){
                
               echo '<a title="Vine" target="_blank" href="' . esc_url($vine) . '"><i class="fa fa-vine"></i></a>';

            }
            if(isset($vk) && !empty($vk)){
                
               echo '<a title="VK" target="_blank" href="' . esc_url($vk) . '"><i class="fa fa-vk"></i></a>';

            }
            if(isset($reddit) && !empty($reddit)){
                
               echo '<a title="Reddit" target="_blank" href="' . esc_url($reddit) . '"><i class="fa fa-reddit"></i></a>';

            }
            if(isset($skype) && !empty($skype)){
                
               echo '<a title="Skype" target="_blank" href="' . esc_url($skype) . '"><i class="fa fa-skype"></i></a>';

            }
            if(isset($vimeo) && !empty($vimeo)){
                
               echo '<a title="Vimeo" target="_blank" href="' . esc_url($vimeo) . '"><i class="fa fa-vimeo"></i></a>';

            }
            if(isset($trello) && !empty($trello)){
                
               echo '<a title="Trello" target="_blank" href="' . esc_url($trello) . '"><i class="fa fa-trello"></i></a>';

            }
            if(isset($xing) && !empty($xing)){
                
               echo '<a title="Xing" target="_blank" href="' . esc_url($xing) . '"><i class="fa fa-xing"></i></a>';

            }
            if(isset($soundcloud) && !empty($soundcloud)){
                
               echo '<a title="Soundcloud" target="_blank" href="' . esc_url($soundcloud) . '"><i class="fa fa-soundcloud"></i></a>';

            }
            if(isset($github) && !empty($github)){
                
               echo '<a title="Github" target="_blank" href="' . esc_url($github) . '"><i class="fa fa-github"></i></a>';

            }
            if(isset($snapchat) && !empty($snapchat)){
                
               echo '<a title="Snapchat" target="_blank" href="' . esc_url($snapchat) . '"><i class="fa fa-snapchat-ghost" aria-hidden="true"></i></a>';

            }
            echo $args['after_widget'];
                
            
        }

        //set widget fiields 
        public function form($instance){
           

            if(isset($instance['title'])){
              $title = $instance['title'];
            }
            if(isset($instance['twitter'])){
              $twitter = $instance['twitter'];
            }
            if(isset($instance['facebook'])){
                $facebook = $instance['facebook'];
            }
            if(isset($instance['google-plus'])){
                $google_plus = $instance['google-plus'];
            }
            if(isset($instance['linkedin'])){
                $linkedin = $instance['linkedin'];
            }
            if(isset($instance['pinterest'])){
                $pinterest = $instance['pinterest'];
            }
            if(isset($instance['instagram'])){
                $instagram = $instance['instagram'];
            }
            if(isset($instance['youtube'])){
                $youtube = $instance['youtube'];
            }
            if(isset($instance['flickr'])){
                $flickr = $instance['flickr'];
            }
            if(isset($instance['tumblr'])){
                $tumblr = $instance['tumblr'];
            }
            if(isset($instance['vine'])){
                $vine = $instance['vine'];
            }
            if(isset($instance['vk'])){
                $vk = $instance['vk'];
            }
            if(isset($instance['reddit'])){
                $reddit = $instance['reddit'];
            }
            if(isset($instance['skype'])){
                $skype = $instance['skype'];
            }
            if(isset($instance['vimeo'])){
                $vimeo = $instance['vimeo'];
            }
            if(isset($instance['trello'])){
                $trello = $instance['trello'];
            } 
            if(isset($instance['xing'])){
                $xing = $instance['xing'];
            }
            if(isset($instance['soundcloud'])){
                $soundcloud = $instance['soundcloud'];
            }
            if(isset($instance['github'])){
                $github = $instance['github'];
            }
            if(isset($instance['snapchat'])){
                $snapchat = $instance['snapchat'];
            }

            ?>
            
            <!-- title field -->
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if(isset($title)){ echo $title; }; ?>" type="text">
            <br>
            <br>

            <!-- twitter field -->
            <label for="<?php echo $this->get_field_id('twitter'); ?>">Twitter Profile Link / URL:</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" value="<?php if(isset($twitter)){ echo $twitter; }; ?>" type="text">
            <br>
            <br>

            <!-- facebook field -->
            <label for="<?php echo $this->get_field_id('facebook'); ?>">Facebook Profile Link / URL:</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" value="<?php if(isset($facebook)){ echo $facebook; }; ?>" type="text">
            <br>
            <br>

            <!-- Google plus field -->
            <label for="<?php echo $this->get_field_id('google-plus'); ?>">Google Plus Profile Link / URL:</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('google-plus'); ?>" name="<?php echo $this->get_field_name('google-plus'); ?>" value="<?php if(isset($google_plus)){ echo $google_plus; }; ?>" type="text">
            <br>
            <br>

            <!-- LinkedIn field -->
            <label for="<?php echo $this->get_field_id('linkedin'); ?>">LinkedIn Profile Link / URL:</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('linkedin'); ?>" name="<?php echo $this->get_field_name('linkedin'); ?>" value="<?php if(isset($linkedin)){ echo $linkedin; }; ?>" type="text">
            <br>
            <br>

            <!-- Pinterest field -->
            <label for="<?php echo $this->get_field_id('pinterest'); ?>">Pinterest Profile Link / URL:</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('pinterest'); ?>" name="<?php echo $this->get_field_name('pinterest'); ?>" value="<?php if(isset($pinterest)){ echo $pinterest; }; ?>" type="text">
            <br>
            <br>

            <!-- instagram field -->
            <label for="<?php echo $this->get_field_id('instagram'); ?>">Instagram Profile Link / URL:</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('instagram'); ?>" name="<?php echo $this->get_field_name('instagram'); ?>" value="<?php if(isset($instagram)){ echo $instagram; }; ?>" type="text">
            <br>
            <br>

            <!-- youtube field -->
            <label for="<?php echo $this->get_field_id('youtube'); ?>">Youtube Profile Link / URL:</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('youtube'); ?>" name="<?php echo $this->get_field_name('youtube'); ?>" value="<?php if(isset($youtube)){ echo $youtube; }; ?>" type="text">
            <br>
            <br> 

            <!-- flickr field -->
            <label for="<?php echo $this->get_field_id('flickr'); ?>">Flickr Profile Link / URL:</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('flickr'); ?>" name="<?php echo $this->get_field_name('flickr'); ?>" value="<?php if(isset($flickr)){ echo $flickr; }; ?>" type="text">
            <br>
            <br> 

            <!-- tumblr field -->
            <label for="<?php echo $this->get_field_id('tumblr'); ?>">Tumblr Profile Link / URL:</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('tumblr'); ?>" name="<?php echo $this->get_field_name('tumblr'); ?>" value="<?php if(isset($tumblr)){ echo $tumblr; }; ?>" type="text">
            <br>
            <br>

            <!-- vine field -->
            <label for="<?php echo $this->get_field_id('vine'); ?>">Vine Profile Link / URL:</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('vine'); ?>" name="<?php echo $this->get_field_name('vine'); ?>" value="<?php if(isset($vine)){ echo $vine; }; ?>" type="text">
            <br>
            <br>

            <!-- VK field -->
            <label for="<?php echo $this->get_field_id('vk'); ?>">VK Profile Link / URL:</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('vk'); ?>" name="<?php echo $this->get_field_name('vk'); ?>" value="<?php if(isset($vk)){ echo $vk; }; ?>" type="text">
            <br>
            <br>

            <!-- Reddit field -->
            <label for="<?php echo $this->get_field_id('reddit'); ?>">Reddit Profile Link / URL:</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('reddit'); ?>" name="<?php echo $this->get_field_name('reddit'); ?>" value="<?php if(isset($reddit)){ echo $reddit; }; ?>" type="text">
            <br>
            <br> 

            <!-- skype field -->
            <label for="<?php echo $this->get_field_id('skype'); ?>">Skype Profile Link / URL:</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('skype'); ?>" name="<?php echo $this->get_field_name('skype'); ?>" value="<?php if(isset($skype)){ echo $skype; }; ?>" type="text">
            <br>
            <br> 

            <!-- vimeo field -->
            <label for="<?php echo $this->get_field_id('vimeo'); ?>">Vimeo Profile Link / URL:</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('vimeo'); ?>" name="<?php echo $this->get_field_name('vimeo'); ?>" value="<?php if(isset($vimeo)){ echo $vimeo; }; ?>" type="text">
            <br>
            <br>

            <!-- trello field -->
            <label for="<?php echo $this->get_field_id('trello'); ?>">Trello Profile Link / URL:</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('trello'); ?>" name="<?php echo $this->get_field_name('trello'); ?>" value="<?php if(isset($trello)){ echo $trello; }; ?>" type="text">
            <br>
            <br>

            <!-- xing field -->
            <label for="<?php echo $this->get_field_id('xing'); ?>">Xing Profile Link: / URL</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('xing'); ?>" name="<?php echo $this->get_field_name('xing'); ?>" value="<?php if(isset($xing)){ echo $xing; }; ?>" type="text">
            <br>
            <br>

            <!-- soundcloud field -->
            <label for="<?php echo $this->get_field_id('soundcloud'); ?>">Soundcloud Profile Link / URL:</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('soundcloud'); ?>" name="<?php echo $this->get_field_name('soundcloud'); ?>" value="<?php if(isset($soundcloud)){ echo $soundcloud; }; ?>" type="text">
            <br>
            <br>

            <!-- github field -->
            <label for="<?php echo $this->get_field_id('github'); ?>">Github Profile Link / URL:</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('github'); ?>" name="<?php echo $this->get_field_name('github'); ?>" value="<?php if(isset($github)){ echo $github; }; ?>" type="text">
            <br>
            <br>

            <!-- Snapchat field -->
            <label for="<?php echo $this->get_field_id('snapchat'); ?>">Snapchat Profile Link / URL:</label>
            <br>
                <input class="widefat" id="<?php echo $this->get_field_id('snapchat'); ?>" name="<?php echo $this->get_field_name('snapchat'); ?>" value="<?php if(isset($snapchat)){ echo $snapchat; }; ?>" type="text">
            <br>
            <br>

            <?php 
        }
    }

    //regoster widget with hook
    function register_social_icon_widget(){
        register_widget( 'social_icon_widget' );
    }
    add_action( 'widgets_init', 'register_social_icon_widget');

