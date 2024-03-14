<?php
/**
* Dynamic Styles for the Emma Emarketing Plugin
*
* Outputs a custom stylesheet for the plugin that only gets instantiated on the pages the widget or shortcode is used on,
* @package Emma_Emarketing
* @author ah so designs
* @version 1.0
* @abstract
* @copyright not yet
*/

// output dynamic form stylesheet
//http://css-tricks.com/css-variables-with-php/
//header("Content-type: text/css; charset: UTF-8");

class Emma_Style {

    private $settings;

    function __construct() {

        $this->settings = (array) get_option( Form_Custom::$key );
		
		$user_set_width = $this->settings['submit_btn_width'];
		if ( $this->settings['submit_btn_width'] !== '' ) {
			if ( $this->strposa($user_set_width, array('px','%')) ) {
				$this->settings['submit_btn_width'] = $user_set_width;
			} else {
				// If they used any characters EXCEPT for 'px' or '%'
				// convert it to an integer pixel value
				$this->settings['submit_btn_width'] = intval($user_set_width) . 'px';
			}
		}
    }
    
     public function strposa($haystack, $needle, $offset=0) {
	    if(!is_array($needle)) $needle = array($needle);
	    foreach($needle as $query) {
	        if(strpos($haystack, $query, $offset) !== false) return true; // stop on first true result
	    }
	    return false;
	}

    public function output() { ?>

        <style id="emma-emarketing" type="text/css" media="all">
            /**
            * Emma Emarketing Plugin Stylesheet
            */

            /** Basics **/
            #emma-form { max-width: 100%; }
            #emma-subscription-form { width: 100%; }
            ul#emma-form-elements { list-style-type: none; margin: 0; padding: 0; }
            ul#emma-form-elements li.emma-form-row { list-style-type: none; width: 90%; margin: 3px auto; display: block; }
            ul#emma-form-elements .emma-form-label { float: left; width: 27%; }
            ul#emma-form-elements .emma-form-input { float: right; width: 69%;}
            ul#emma-form-elements .emma-form-row-last { clear: both; }
            ul#emma-form-elements .emma-required { color: #C00; }
            ul#emma-form-elements #emma-form-submit { float: right; }
            ul#emma-form-elements .emma-form-label-required { width: 40%; }
            .emma-status-msg { width: 90%; margin: 0 auto; }
            .emma-error { width: 90%; margin: 0 auto; color: #C00; }
            #emma-subscription-form .validation-container {display: none !important;position: absolute !important;left: -9000px;}

			/* Deprecated */
            #emma-form.x-small { width: 200px; }
            #emma-form.small { width: 280px; }
            #emma-form.medium { width: 300px; }
            #emma-form.large { width: 340px; }
            
            #emma-form.emma-horizontal-layout { width: auto; }
			.emma-horizontal-layout ul#emma-form-elements li.emma-form-row { width:24%; float:left; margin-right: 1%; }
			.emma-horizontal-layout ul#emma-form-elements .emma-form-input { width: 100%; }
			.emma-horizontal-layout #emma-form-submit { width: 100%; padding: 0; height: 37px; }
			.emma-horizontal-layout ul#emma-form-elements li.emma-form-row-last { margin-right: 0; clear: none; }
			.emma-horizontal-layout .emma-form-label,.emma-horizontal-layout .emma-form-label-required { display:none; }
			
			#emma-form.emma-only-email.emma-horizontal-layout ul#emma-form-elements li.emma-form-row { width: 49%; }

			.emma-cf:before,.emma-cf:after { content: " "; display: table; }
			.emma-cf:after { clear: both; }
			.emma-cf { *zoom: 1; }

            /** Customizable Elements **/
            ul#emma-form-elements .emma-form-input {
                border: <?php echo $this->settings['border_width'] . 'px ' . $this->settings['border_type'] . ' #' . $this->settings['border_color']; ?>;
                color: #<?php echo $this->settings['txt_color']; ?>;
                background-color: #<?php echo $this->settings['bg_color']; ?>;
            }
            #emma-form input[type="submit"], #emma-form a#emma-form-submit {
                border: <?php echo $this->settings['submit_border_width'] . 'px ' . $this->settings['submit_border_type'] . ' #' . $this->settings['submit_border_color']; ?>;
                color: #<?php echo $this->settings['submit_txt_color']; ?>;
                background-color: #<?php echo $this->settings['submit_bg_color']; ?>;
                <?php if ( isset( $this->settings['submit_btn_width'] ) ) { echo 'width: ' . $this->settings['submit_btn_width'] .';'; } ?>
            }
            #emma-form input[type="submit"]:hover, , #emma-form a#emma-form-submit:hover {
                border: <?php echo $this->settings['submit_hover_border_width'] . 'px ' . $this->settings['submit_hover_border_type'] . ' #' . $this->settings['submit_hover_border_color']; ?>;
                color: #<?php echo $this->settings['submit_hover_txt_color']; ?>;
                background-color: #<?php echo $this->settings['submit_hover_bg_color']; ?>;
            }

            #emma-form.x-small ul#emma-form-elements .emma-form-input,
            #emma-form.x-small ul#emma-form-elements .emma-form-label { float: left; width: 97%; }
            
            .spinner{
	            background: url(wp-includes/images/spinner.gif) #fff center no-repeat;
				-webkit-background-size: 20px 20px;
				display: none;
				opacity: 1.7;
				width: 20px;
				height: 20px;
				padding: 15px;
				margin: 30px auto 50px;
				border-radius: 4px;
				box-shadow: 0px 0px 7px rgba(0,0,0,.1);
			}
            
            /* alert text */
            .emma-status, .emma-alert { 
	            width: 100%;
				margin: 1em auto;
				padding: 1em 1em 1em 5em;
				background: rgb(255, 235, 235);
				font-size: .8em;
				font-family: sans-serif;
				font-style: italic;
				color: rgb(71, 71, 71);
				border-radius: 3px;
				border: thin solid rgb(247, 195, 195);
				position: relative;
				box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.2);
			}
			
			.emma-status {
				background: rgb(255, 235, 235);
				border: thin solid rgb(247, 195, 195);
			}
			
			.emma-status:before, .emma-alert:before {
				content: '!';
				position: absolute;
				top: .6em;
				top: 13px;
				left: .5em;
				left: 9px;
				color: #ffffff;
				background: rgb(208, 45, 45);
				height: 30px;
				width: 30px;
				text-align: center;
				font-family: 'Georgia' serif;
				font-size: 1.4em;
				line-height: 1.5em;
				font-style: normal;
				border-radius: 50%;
			}
			
			.emma-status:not(.emma-alert):before {
			    content: '';
			    background: rgb(85, 182, 85);
			}
			
			.emma-status:not(.emma-alert):after {
			    content:'';
			    border: 4px solid #fff;
			    border-top: none;
			    border-left: none;
			    width: 11px;
			    height: 18px;
			    display:block;
			    position: absolute; 
			    top: 16px; 
			    left: 19px;
			    transform: 		   rotate(45deg);
				-webkit-transform: rotate(45deg);
				   -moz-transform: rotate(45deg);
				   	 -o-transform: rotate(45deg);
				   	-ms-transform: rotate(45deg);
			}
			
			.emma-status:not(.emma-alert) {
			    background: rgb(230, 250, 230);
			    border: thin solid rgb(166, 187, 166);
			}
			
			.recaptcha-popup {
				position: fixed;
				z-index: 999999;
				top: 0;
				right: 0;
				bottom: 0;
				left: 0;
				background: rgba(0,0,0,.6);
			}
			
			.recaptcha-popup.hidden {
				display: none;
			}
			
			.recaptcha-popup .inner {
				position: absolute;
				top: 25vh;
				left: 25vw;
				width: 50vw;
				background: #fff;
				border-radius: 2px;
				padding: 2em;
				box-sizing: border-box;
			}
			
			.recaptcha-popup .inner p {
				color: #030303;
				text-align: center;
				font-size: 1.5vw;
				font-size: 1.25rem;
			}
			
			.recaptcha-popup .recaptcha-container > div {
				margin: 0 auto;
				max-width: 100%;
			}
			
			@media only screen and (max-width: 760px) {
				.recaptcha-popup .inner {
					width: 90vw;
					left: 5vw;
					padding: 2em .5em;
				}
				.recaptcha-popup .inner p {
					font-size: 1.25rem;
				}
			}

        </style>

    <?php }

} // end class Style