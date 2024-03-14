<?php

 /*
 * Plugin Name: IP2Location World Clock
 * Plugin URI:  https://www.ip2location.com/
 * Description: Display analog or digital clock on your site based on time selection
 * Version:     1.1.6
 * Author:      IP2Location
 * Author URI:  https://www.ip2location.com
 */

defined( 'DS' ) or define( 'DS', DIRECTORY_SEPARATOR );
$upload_dir = wp_upload_dir();
defined('IP2LOCATION_DIR') or define('IP2LOCATION_DIR', str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $upload_dir['basedir']) . DIRECTORY_SEPARATOR . 'ip2location' . DIRECTORY_SEPARATOR);
define( 'IP2LOCATION_WORLD_CLOCK_ROOT', dirname( __FILE__ ) . DS );
require plugin_dir_path( __FILE__ ) .'ip2location-world-clock-menu.php';

new ip_world_clock( __FILE__, '1.0.0' );  

add_action('widgets_init', function() {

    register_widget('ip_world_clock');

});


Class ip_world_clock extends WP_Widget {

    public function __construct(){
        parent::__construct( 'iwc','IP2Location World Clock' );

        if (!file_exists(IP2LOCATION_DIR)) {
            wp_mkdir_p(IP2LOCATION_DIR);
        }
    }


    public function form($instance){ ?>
    
    <p> Display analog or digital clock on your site</p>
    <?php
      
      if (!isset($instance['title'])) { $instance['title']= ""; }

    ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>">
        </p>
        <?php
        
    }


    public function widget($args,$instance) { 

        echo $args['before_widget'];

        // Display the widget title.
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        //Get selection value from option menu
        $clock = get_option('ip2location_world_clock_design');
        $timeformat = get_option('ip2location_world_clock_time_format');
        $time = get_option('ip2location_world_clock_display_time');
        $utc_value = get_option('ip2location_world_clock_display_time2');

        if ($time == 't2') {
            // Get timezone from IP
            $ip_address = $_SERVER['REMOTE_ADDR'];

            if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 ) ) {
                $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }

            $result = $this->get_location( $ip_address );
            $visitortime = substr( $result['timeZone'], 0, strpos( $result['timeZone'], ':' ) );
        }

        //Load style and script
        wp_enqueue_style('css', plugins_url('assets/css/style.css',__FILE__));
        wp_enqueue_script('script', plugins_url('assets/js/script.js', __FILE__ ) ,array('jquery'), true );
        // Set up the required data 
        if ($time == 't2') {
            $data = $visitortime;
        } else {
            $data = get_option('ip2location_world_clock_display_time2');
        }
        if ($data != null){
           // Localise the data, specifying our registered script and a global variable name to be used in the script tag
           wp_localize_script( 'script', 'data', $data);
        }
        if ($timeformat != null){
           wp_localize_script( 'script', 'timeformat', $timeformat);
        }


        //Display Local Time for all clock designs
        if($clock == 'a1' && $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock1">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }     
        
        if($clock == 'a2'&& $time == 't1'){ ?>

            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock2">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>

            <?php
        }

        if($clock == 'a3'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock3">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            
            <?php
        }

        if($clock == 'a4'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock4">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'a5'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock5">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>

            <?php
        }


        if($clock == 'a6'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock6">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            
            <?php
        }

        if($clock == 'a7'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock7">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'a8'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock8">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }


        if($clock == 'a9'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock9">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'a10'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock10">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a11'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock15">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a12'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock16">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a13'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock19">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a14'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock20">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a15'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock23">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a16'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock24">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a17'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock27">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a18'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock28">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a19'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock29">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a20'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock30">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a21'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock31">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a22'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock32">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a23'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock33">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'a24'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock39">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'a25'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock40">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a26'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock43">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a27'&& $time == 't1'){ ?>
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock44">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'd1' && $time == 't1'){ ?>
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock11">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'd2'&& $time == 't1'){ ?>
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock12">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'd3'&& $time == 't1'){ ?>
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock13">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'd4'&& $time == 't1'){ ?>
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock14">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd5'&& $time == 't1'){ ?>
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock17">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd6'&& $time == 't1'){ ?>
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock18">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'd7'&& $time == 't1'){ ?>
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock21">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd8'&& $time == 't1'){ ?>
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock22">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd9'&& $time == 't1'){ ?>
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock25">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd10'&& $time == 't1'){ ?>
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock26">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd11'&& $time == 't1'){ ?>
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock34">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd12'&& $time == 't1'){ ?>
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock35">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd13'&& $time == 't1'){ ?>
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock36">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd14'&& $time == 't1'){ ?>
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock37">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd15'&& $time == 't1'){ ?>
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock38">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd16'&& $time == 't1'){ ?>
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock41">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd17'&& $time == 't1'){ ?>
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock42">
                        <div class="time">
                            <span class="iwc-span2"><span class="hours"></span><span class="minutes"></span></span>
                            <span class="iwc-span2 seconds" style="display:none;"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd18'&& $time == 't1'){ ?>
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock45">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd19'&& $time == 't1'){ ?>
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock46">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        //Display Custom Time Zone for all clock designs 
        if($clock == 'a1' && $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock1">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }     
        
        if($clock == 'a2'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock2">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'a3'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock3">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            
            <?php
        }

        if($clock == 'a4'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock4">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'a5'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock5">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }


        if($clock == 'a6'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock6">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
             <?php
        }

        if($clock == 'a7'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock7">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'a8'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock8">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }


        if($clock == 'a9'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock9">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'a10'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock10">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a11'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock15">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a12'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock16">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a13'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock19">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a14'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock20">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'a15'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock23">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a16'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock24">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a17'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock27">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a18'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock28">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a19'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock29">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a20'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock30">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a21'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock31">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a22'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock32">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a23'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock33">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a24'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock39">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a25'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock40">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a26'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock43">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a27'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock44">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'd1' && $time == 't3'&& $utc_value != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock11">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'd2'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock12">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'd3'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock13">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'd4'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock14">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd5'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock17">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd6'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock18">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'd7'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock21">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd8'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock22">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd9'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock25">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd10'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock26">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd11'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock34">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd12'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock35">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd13'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock36">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd14'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock37">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd15'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock38">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd16'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock41">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd17'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock42">
                        <div class="time">
                            <span class="iwc-span2"><span class="hours"></span><span class="minutes"></span></span>
                            <span class="iwc-span2 seconds" style="display:none;"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd18'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock45">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd19'&& $time == 't3'&& $utc_value != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock46">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        //Display Visitor's Time for all clock designs 
        if($clock == 'a1' && $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock1">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }     
        
        if($clock == 'a2'&& $time == 't2' && $visitortime != null){ ?>

            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock2">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>

            <?php
        }

        if($clock == 'a3'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock3">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            
            <?php
        }

        if($clock == 'a4'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock4">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'a5'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock5">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>

            <?php
        }


        if($clock == 'a6'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock6">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            
            <?php
        }

        if($clock == 'a7'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock7">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'a8'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock8">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }


        if($clock == 'a9'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock9">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'a10'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock10">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a11'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock15">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a12'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock16">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a13'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock19">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a14'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock20">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a15'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock23">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a16'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock24">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a17'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock27">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a18'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock28">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a19'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock29">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a20'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock30">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a21'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock31">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a22'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock32">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a23'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock33">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a24'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock39">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a25'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock40">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a26'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock43">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'a27'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock44">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd1' && $time == 't2' && $visitortime != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock11">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'd2'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock12">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'd3'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock13">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'd4'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock14">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd5'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock17">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd6'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock18">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }

        if($clock == 'd7'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock21">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd8'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock22">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd9'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock25">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd10'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock26">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd11'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock34">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd12'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock35">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd13'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock36">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd14'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock37">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd15'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock38">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd16'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock41">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd17'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock42">
                        <div class="time">
                            <span class="iwc-span2"><span class="hours"></span><span class="minutes"></span></span>
                            <span class="iwc-span2 seconds" style="display:none;"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd18'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock45">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        if($clock == 'd19'&& $time == 't2' && $visitortime != null){ ?>
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock46">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            <?php
        }
        
        echo $args['after_widget'];
    }
    

    
    public function update( $new_instance, $old_instance ) {
    $instance          = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

    return $instance;
}


    function get_location( $ip ) {
        if ( !is_file( IP2LOCATION_DIR . get_option( 'ip2location_world_clock_database' ) ) ) {
            return false;
        }

        if ( ! class_exists( 'IP2Location\\Database' ) ) {
            require_once( IP2LOCATION_WORLD_CLOCK_ROOT . 'class.IP2Location.php' );
        }

        $geo = new \IP2Location\Database( IP2LOCATION_DIR . get_option( 'ip2location_world_clock_database' ), \IP2Location\Database::FILE_IO );

        $response = $geo->lookup( $ip, \IP2Location\Database::ALL );

        return array(
            'ipAddress' => $ip,
            'countryCode' => $response['countryCode'],
            'countryName' => $response['countryName'],
            'regionName' => $response['regionName'],
            'cityName' => $response['cityName'],
            'latitude' => $response['latitude'],
            'longitude' => $response['longitude'],
            // 'isp'=> $response['isp'],
            // 'domainName' => $response['domainName'],
            'zipCode' => $response['zipCode'],
            'timeZone' => $response['timeZone'],
            // 'netSpeed' => $response['netSpeed'],
            // 'iddCode' => $response['iddCode'],
            // 'areaCode' => $response['areaCode'],
            // 'weatherStationCode' => $response['weatherStationCode'],
            // 'weatherStationName' =>$response['weatherStationName'],
            // 'mcc' => $response['mcc'],
            // 'mnc' => $response['mnc'],
            // 'mobileCarrierName' => $response['mobileCarrierName'],
            // 'elevation' => $response['elevation'],
            // 'usageType' => $response['usageType'],
        );
    }

};

//For feedback modal when deactivate
function ip2location_world_clock_plugin_enqueues($hook) {

    if ($hook == 'plugins.php') {
    // Add in required libraries for feedback modal
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_style('wp-jquery-ui-dialog');
        wp_enqueue_script('script', plugins_url('/assets/js/feedback.js', __FILE__), array('jquery'), true);
    }
}add_action('admin_enqueue_scripts','ip2location_world_clock_plugin_enqueues');


//shortcode function
function ip2location_world_clock_shortcode($atts){

    // define attributes and their defaults
    extract( shortcode_atts( array (
        'design' => 'a1',
        'time' => 't1',
        'utc' => '',
    ), $atts ) );
 
    if (isset($atts['design'])){
        $clock = $atts['design'];
    }else{
        $clock = get_option('ip2location_world_clock_design');
    }
    if (isset($atts['time'])){
        if ($atts['time'] == 'local'){$time = 't1';}
        else if ($atts['time'] == 'visitor'){$time = 't2';}
        else if ($atts['time'] == 'custom'){$time = 't3';}
    }
    else{
        $time = get_option('ip2location_world_clock_display_time');
    }
    if (isset($atts['utc'])){
        $utc_value = $atts['utc'];
    }
    else{
        $utc_value = get_option('ip2location_world_clock_display_time2');
    }

    if ($time == 't2') {
        // Get timezone from IP
        $ip_address = $_SERVER['REMOTE_ADDR'];

        if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 ) ) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        $result = get_location( $ip_address );
        $visitortime = substr( $result['timeZone'], 0, strpos( $result['timeZone'], ':' ) );
    }

    wp_enqueue_style('css', plugins_url('assets/css/style.css',__FILE__));
    wp_enqueue_script('script', plugins_url('assets/js/script.js', __FILE__ ) ,array('jquery'), true );
    // Set up the required data 
    if ($time == 't2') {
        $data = $visitortime;
    } 
    else{
        if (isset($atts['utc'])){
            $data = $atts['utc'];
        }
        else{
            $data = get_option('ip2location_world_clock_display_time2');
        }
    }

    if ($data != null){
    // Localise the data, specifying our registered script and a global variable name to be used in the script tag
        wp_localize_script( 'script', 'datas', $data);
    }


        //Display Local Time for all clock designs
        if($clock == 'a1' && $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock1">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }     
        
        if($clock == 'a2'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock2">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>

            ';
            return $output;
        }

        if($clock == 'a3'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock3">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            
            ';
            return $output;
        }

        if($clock == 'a4'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock4">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'a5'&& $time == 't1'){
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock5">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }


        if($clock == 'a6'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock6">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            
            ';
            return $output;
        }

        if($clock == 'a7'&& $time == 't1'){
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock7">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'a8'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock8">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }


        if($clock == 'a9'&& $time == 't1'){
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock9">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'a10'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock10">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a11'&& $time == 't1'){
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock15">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a12'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock16">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a13'&& $time == 't1'){
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock19">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a14'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock20">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a15'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock23">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a16'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock24">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a17'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock27">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a18'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock28">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a19'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock29">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a20'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock30">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a21'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock31">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a22'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock32">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a23'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock33">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a24'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock39">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a25'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock40">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a26'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock43">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a27'&& $time == 't1'){ 
            $output = '
            <body onload="rClock()">
                <div class="iwc-container">
                    <div id="clock44">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'd1' && $time == 't1'){ 
            $output = '
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock11">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'd2'&& $time == 't1'){ 
            $output = '
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock12">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'd3'&& $time == 't1'){
            $output = '
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock13">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'd4'&& $time == 't1'){ 
            $output = '
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock14">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd5'&& $time == 't1'){
            $output = '
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock17">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
           
            ';
            return $output;
        }
        
        if($clock == 'd6'&& $time == 't1'){ 
            $output = '
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock18">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd7'&& $time == 't1'){
            $output = '
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock21">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
           
            ';
            return $output;
        }
        
        if($clock == 'd8'&& $time == 't1'){ 
            $output = '
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock22">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd9'&& $time == 't1'){ 
            $output = '
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock25">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd10'&& $time == 't1'){ 
            $output = '
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock26">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd11'&& $time == 't1'){ 
            $output = '
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock34">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd12'&& $time == 't1'){ 
            $output = '
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock35">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        if($clock == 'd13'&& $time == 't1'){ 
            $output = '
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock36">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd14'&& $time == 't1'){ 
            $output = '
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock37">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd15'&& $time == 't1'){ 
            $output = '
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock38">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd16'&& $time == 't1'){ 
            $output = '
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock41">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd17'&& $time == 't1'){ 
            $output = '
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock42">
                        <div class="time">
                            <span class="iwc-span2"><span class="hours"></span><span class="minutes"></span></span>
                            <span class="iwc-span2 seconds" style="display:none;"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd18'&& $time == 't1'){ 
            $output = '
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock45">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd19'&& $time == 't1'){ 
            $output = '
            <body onload="dClock()">
                <div class="iwc-container2">
                    <div id="clock46">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        //Display Custom Time Zone for all clock designs 
        if($clock == 'a1' && $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock1">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }     
        
        if($clock == 'a2'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock2">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>

            ';
            return $output;
        }

        if($clock == 'a3'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock3">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            
            ';
            return $output;
        }

        if($clock == 'a4'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock4">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'a5'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock5">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>

            ';
            return $output;
        }


        if($clock == 'a6'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock6">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            
            ';
            return $output;
        }

        if($clock == 'a7'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock7">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'a8'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock8">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }


        if($clock == 'a9'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock9">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'a10'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock10">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a11'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock15">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a12'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock16">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a13'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock19">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a14'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock20">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a15'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock23">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a16'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock24">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a17'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock27">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a18'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock28">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a19'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock29">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a20'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock30">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a21'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock31">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a22'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock32">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a23'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock33">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a24'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock39">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a25'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock40">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a26'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock43">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a27'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock44">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }


        if($clock == 'd1' && $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock11">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'd2'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock12">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'd3'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock13">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'd4'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock14">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd5'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock17">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd6'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock18">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd7'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock21">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd8'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock22">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd9'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock25">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd10'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock26">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd11'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock34">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd12'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock35">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd13'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock36">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd14'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock37">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd15'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock38">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd16'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock41">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd17'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock42">
                        <div class="time">
                            <span class="iwc-span2"><span class="hours"></span><span class="minutes"></span></span>
                            <span class="iwc-span2 seconds" style="display:none;"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd18'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock45">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd19'&& $time == 't3'&& $utc_value != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock46">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        //Display Visitor's Time for all clock designs 
        if($clock == 'a1' && $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock1">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }     
        
        if($clock == 'a2'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock2">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>

            ';
            return $output;
        }

        if($clock == 'a3'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock3">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            
            ';
            return $output;
        }

         if($clock == 'a4'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock4">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'a5'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock5">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }


        if($clock == 'a6'&& $time == 't2' && $visitortime != null){
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock6">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'a7'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock7">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'a8'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock8">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }


        if($clock == 'a9'&& $time == 't2' && $visitortime != null){
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock9">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'a10'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock10">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'a11'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock15">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
           ';
            return $output;
        }
        
        if($clock == 'a12'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock16">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
           ';
            return $output;
        }
        
        if($clock == 'a13'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock19">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
           ';
            return $output;
        }
        
        if($clock == 'a14'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock20">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
           ';
            return $output;
        }
        
        if($clock == 'a15'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock23">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
           ';
            return $output;
        }
        
        if($clock == 'a16'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock24">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
           ';
            return $output;
        }

        if($clock == 'a17'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock27">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
           ';
            return $output;
        }
        
        if($clock == 'a18'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock28">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
           ';
            return $output;
        }
        
        if($clock == 'a19'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock29">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
           ';
            return $output;
        }
        
        if($clock == 'a20'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock30">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
           ';
            return $output;
        }
        
        if($clock == 'a21'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock31">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
           ';
            return $output;
        }
        
        if($clock == 'a22'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock32">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
           ';
            return $output;
        }
        
        if($clock == 'a23'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock33">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
           ';
            return $output;
        }
        
        if($clock == 'a24'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock39">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
           ';
            return $output;
        }
        
        if($clock == 'a25'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock40">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
           ';
            return $output;
        }
        
        if($clock == 'a26'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock43">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
           ';
            return $output;
        }
        
        if($clock == 'a27'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="rtClock()">
                <div class="iwc-container">
                    <div id="clock44">
                        <div class="h-hand"></div>
                        <div class="m-hand"></div>
                        <div class="s-hand"></div>
                    </div>
                </div>
            </body>
           ';
            return $output;
        }
        
        if($clock == 'd1' && $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock11">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'd2'&& $time == 't2' && $visitortime != null){ 
           $output = '
           <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock12">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'd3'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock13">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }

        if($clock == 'd4'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock14">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd5'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock17">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
           
        }
        
        if($clock == 'd6'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock18">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd7'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock21">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
           
        }
        
        if($clock == 'd8'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock22">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd9'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock25">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd10'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock26">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
         }
         
         if($clock == 'd11'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock34">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
         }
         
         if($clock == 'd12'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock35">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
         }
         
         if($clock == 'd13'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock36">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
         }
         
         if($clock == 'd14'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock37">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
         }
         
         if($clock == 'd15'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock38">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
         }
         
         if($clock == 'd16'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock41">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd17'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock42">
                        <div class="time">
                            <span class="iwc-span2"><span class="hours"></span><span class="minutes"></span></span>
                            <span class="iwc-span2 seconds" style="display:none;"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd18'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock45">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
        
        if($clock == 'd19'&& $time == 't2' && $visitortime != null){ 
            $output = '
            <body onload="dtClock()">
                <div class="iwc-container2">
                    <div id="clock46">
                        <div class="time">
                            <span class="iwc-span hours"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span minutes"></span>
                            <span class="iwc-span colon">:</span>
                            <span class="iwc-span seconds"></span>
                        </div>
                        <div class="session"></div>
                        <div class="week" style="display:none;"></div>
                    </div>
                </div>
            </body>
            ';
            return $output;
        }
}
    function get_location( $ip ) {
        if ( !is_file( IP2LOCATION_DIR . get_option( 'ip2location_world_clock_database' ) ) ) {
            return false;
        }

        if ( ! class_exists( 'IP2Location\\Database' ) ) {
            require_once( IP2LOCATION_WORLD_CLOCK_ROOT . 'class.IP2Location.php' );
        }

        $geo = new \IP2Location\Database( IP2LOCATION_DIR . get_option( 'ip2location_world_clock_database' ), \IP2Location\Database::FILE_IO );

        $response = $geo->lookup( $ip, \IP2Location\Database::ALL );

        return array(
            'ipAddress' => $ip,
            'countryCode' => $response['countryCode'],
            'countryName' => $response['countryName'],
            'regionName' => $response['regionName'],
            'cityName' => $response['cityName'],
            'latitude' => $response['latitude'],
            'longitude' => $response['longitude'],
            // 'isp'=> $response['isp'],
            // 'domainName' => $response['domainName'],
            'zipCode' => $response['zipCode'],
            'timeZone' => $response['timeZone'],
            // 'netSpeed' => $response['netSpeed'],
            // 'iddCode' => $response['iddCode'],
            // 'areaCode' => $response['areaCode'],
            // 'weatherStationCode' => $response['weatherStationCode'],
            // 'weatherStationName' =>$response['weatherStationName'],
            // 'mcc' => $response['mcc'],
            // 'mnc' => $response['mnc'],
            // 'mobileCarrierName' => $response['mobileCarrierName'],
            // 'elevation' => $response['elevation'],
            // 'usageType' => $response['usageType'],
        );
    }
add_shortcode( 'ip2location_world_clock', 'ip2location_world_clock_shortcode' );