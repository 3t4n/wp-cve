<?php
/*
Plugin Name: WP Typed JS
Plugin URI: http://dicm.dk/
Description: Adds a shortcode that allowes text to show up as a typewriter function.
Version: 1.0.3
Author: Kim Vinberg
Author URI: http://dicm.dk/
Text Domain: wp-typed-js
Tested up to: 5.9
*/

/**
 *
 * Class base
 */

class WP_Typed_JS {

    /**
     *
     * Call actions / filters / etc. from constrict
     *
     */

    public function __construct() {

        //Call scripts and styles
        add_action( 'wp_enqueue_scripts', array( $this, 'typedjs_scripts_and_styles' ) );

        //Add JS in head
        add_action('wp_head', array( $this, 'hook_js' ));

        //Add shorcode
        add_shortcode( 'typedjs', array( $this, 'typedjs_shortcode' ) );



    }



    /**
    *
    * Call scripts on frontend
    *
    */
    public function typedjs_scripts_and_styles() {

        wp_enqueue_style( 'typedjs-style', plugins_url( '/css/typedjs.min.css' , __FILE__ ) );
        wp_enqueue_script( 'typedjs-script', plugins_url( '/js/typed.min.js' , __FILE__ ). '', array( 'jquery' ) );

    }

    /**
     *
     * ADD shortcode
     * Usage: [typedjs]CONTENT[/typedjs]
     Multiple lines: add , between
    */


    // Add Shortcode
    public function typedjs_shortcode( $atts , $content = null ) {

        //Loop
        $exp = explode(",", $content);
        $sentence = "";

        foreach($exp AS $sentence_raw) {

            $sentence .= "<p>$sentence_raw</p>";

        }

        $output = "<div class=\"type-wrap\" style=\"display:none;\">
        <div id=\"typed-strings\">$sentence</div>
        <span id=\"typed\" style=\"white-space:pre;\"></span>
        </div>";

        return $output;

    }


    /**
    *
    * Add code to header
    *
    */
    public function hook_js() {

	$output = "<script>
    jQuery(function($){

    $('.type-wrap').show();

        $('#typed').typed({
            stringsElement: $('#typed-strings'),
            typeSpeed: 65,
            backDelay: 2500,
            loop: false,
            contentType: 'html', // or text
            loopCount: false,
            callback: function(){  },
            resetCallback: function() { newTyped(); }
        });

        $('.reset').click(function(){
            $('#typed').typed('reset');
        });

    });

    </script>";

	echo $output;

    }


}


$WP_typed_JS = new WP_Typed_JS();
