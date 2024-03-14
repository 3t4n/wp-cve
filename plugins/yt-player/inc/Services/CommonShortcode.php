<?php

namespace YTP\Services;


class CommonShortcode {

    public function register(){
        add_shortcode('youtube_player', [$this, 'youtube_player']);
    }

    public function youtube_player($attrs){
        extract( shortcode_atts( array(
            'src' => null,
        ), $attrs ) ); 



        Ob_start(); 

        echo "youtube video player";

        return ob_get_clean();
    }

}