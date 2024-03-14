<?php 

use Adminz\Admin\Adminz as Adminz;
add_action('ux_builder_setup', 'adminz_countdown');
add_shortcode('adminz_countdown', 'adminz_countdown_function');

function adminz_countdown(){
    add_ux_builder_shortcode('adminz_countdown', array(
        'name'      => __('Number Count Down','administrator-z'),
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'countdown' . '.svg',
        'options' => array(
            'text_days' => array(
                'type'       => 'textfield',
                'heading'    => 'Days',
                'default'    => 'Days',
            ),
            'text_hours' => array(
                'type'       => 'textfield',
                'heading'    => 'Hours',
                'default'    => 'Hours',
            ),
            'text_minutes' => array(
                'type'       => 'textfield',
                'heading'    => 'Minutes',
                'default'    => 'Minutes',
            ),
            'text_seconds' => array(
                'type'       => 'textfield',
                'heading'    => 'Secconds',
                'default'    => 'Secconds',
            ),
            'rowspacing'=> array(
                'type'       => 'select',
                'heading'    => 'Row spacing',
                'default'    => 'small',
                'options'=> [
                        'small'=>'small',
                        'large'=>'large',
                        'collapse'=>'collapse',
                    ]
            ),
            'padding' => array(
                'type'       => 'slider',
                'unit' => 'px',
                'min'=> 0,
                'max'=> 100,
                'heading'    => 'Row padding',
                'default' => '15',
            ),
            'timeleft'=> array(
                'heading' => "Time left",
                'type' =>'slider',
                'default' => 30,
                'unit' => "minutes",
                'min'=> 1,
                'max'=> 10080
            )
        ),
    ));
}

function adminz_countdown_function($atts){    
    extract(shortcode_atts(array(
        'padding'=> '15',
        'rowspacing'=> 'small',
        'text_days'    => 'Days',
        'text_hours'    => 'Hours',
        'text_minutes'    => 'Minutes',
        'text_seconds'    => 'Secconds',
        'timeleft' => 30
    ), $atts));          
    ob_start();
    ?>
    <div class="ux-timer-wrapper row row-<?php echo esc_attr($rowspacing); ?>">
        <div class="col small-3 cd countdown-item text-center">
        	<div class="col-inner" style="border: 1px solid #ccc;padding: <?php echo esc_attr($padding); ?>px 0px;">
		        <h3 class="top countdown-day"> 00 </h3>
		        <?php echo esc_attr($text_days); ?>
	        </div>
        </div>
        <div class="col small-3 cd countdown-item text-center">
        	<div class="col-inner" style="border: 1px solid #ccc;padding: <?php echo esc_attr($padding); ?>px 0px;">
		        <h3 class="top countdown-hour"> 00 </h3>
		        <?php echo esc_attr($text_hours); ?>
	        </div>
        </div>
        <div class="col small-3 cd countdown-item text-center">
        	<div class="col-inner" style="border: 1px solid #ccc;padding: <?php echo esc_attr($padding); ?>px 0px;">
		        <h3 class="top countdown-minute"> 00 </h3>
		        <?php echo esc_attr($text_minutes); ?>
	        </div>
        </div>
        <div class="col small-3 cd countdown-item text-center">
        	<div class="col-inner" style="border: 1px solid #ccc;padding: <?php echo esc_attr($padding); ?>px 0px;">
		        <h3 class="top countdown-second"> 00 </h3>
		        <?php echo esc_attr($text_seconds); ?>
	        </div>
        </div>
    </div>
    <script type="text/javascript">
        window.addEventListener('DOMContentLoaded', function() {
            (function($){
                if(cookie("adminz_countdown")){
                    var future = cookie("adminz_countdown");
                }else{
                    var future = new Date().getTime()/1000 + <?php echo esc_attr($timeleft); ?>*60;
                    cookie("adminz_countdown",future,365);
                }                               

                function calculateHMSleft() {
                    var diff = Math.floor(future- new Date().getTime()/1000);
                    
                    var dayleft = Math.floor((diff / (24*60*60)));
                    var hoursleft = Math.floor((diff % (24*60*60)) /60/60);
                    var minutesleft = Math.floor((diff % (60*60)) / 60);
                    var secondsleft = Math.floor((diff % (60*60)) % 60);
                    
                    
                    var now = new Date();
                    
                    if(!((dayleft <0)|| (hoursleft < 0) || (minutesleft <0) ||  (secondsleft < 0))){
                        if (dayleft < 10) dayleft = "0" + dayleft;
                        if (hoursleft < 10) hoursleft = "0" + hoursleft;
                        if (minutesleft < 10) minutesleft = "0" + minutesleft;
                        if (secondsleft < 10) secondsleft = "0" + secondsleft;

                        var timer_wrapper = $('.ux-timer-wrapper');
                        timer_wrapper.each(function(){
                            $(this).find(".countdown-day").html(dayleft);
                            $(this).find(".countdown-hour").html(hoursleft);                    
                            $(this).find(".countdown-minute").html(minutesleft);                    
                            $(this).find(".countdown-second").html(secondsleft);                    
                        });
                    }else{
                        cookie("adminz_countdown",false);
                    }
                    
                    
                  }
                
                  calculateHMSleft();
                  setInterval(calculateHMSleft, 1000);
            })(jQuery);
        });

    </script>
<?php
    return apply_filters('adminz_output_debug',ob_get_clean());    
}