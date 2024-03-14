<?php
function coinmotion_create_box_horizontal($currency, $color, $data, $text_color, $percent, $actual_currency, $background_color, $rand, $odd_even){
    $rand = rand();
    return "<div style='width: 250px; position: relative; float: left;'>
                <div style='padding-bottom: 0.3rem; height: 100%; border-right: 1px dotted #ababab;'>
                    <div style='width: 20%; padding: 5px; position: relative; float: left; margin-left: 10px;'>
                        <img id='coinmotion_img_crypto_top".$rand."' style='' src='".plugin_dir_url( __FILE__ ).'../public/imgs/'.strtolower($currency).".svg' />
                    </div>
                    <div style='width: 75%; padding: 5px; position: relative; float: left;'>
                        <span id='coinmotion_data".$rand."' style='font-size: 24px; color: " . $text_color . "; font-weight: 400;'>".
                            $data." ".strtoupper($actual_currency['default_currency'])."
                        </span>
                        <br/>
                        <span style='font-size: 20px; font-weight: 400; color: ".$color."'>".$percent."</span>
                    </div>
                </div>
            </div>
    <style>
    #coinmotion_img_crypto_side".$rand."{
        margin-top: -5px;
        width: 100%;
        height: auto;
    }

    #coinmotion_img_crypto_top".$rand."{
        /*display: none;*/
        margin-top: -5px;
        width: 100%;
        height: auto;
    }

    #coinmotion_data_crypto_top".$rand."{
        display: none;
    }

    /*@media (max-width: 480px) {
      #coinmotion_img_crypto_side".$rand."{
        display: none;
      }
      #coinmotion_img_crypto_top".$rand."{
        display: block;
      }
      #coinmotion_data_crypto_top".$rand."{
        display: block;
      }
      #coinmotion_data_crypto_side".$rand."{
        display: none;
      }
      #coinmotion_data".$rand."{
        display: block;
      }
    }*/
    </style>";
}

function coinmotion_create_box_vertical($currency, $color, $data, $text_color, $percent, $actual_currency, $background_color, $rand, $odd_even){
    $rand = rand();
    return "
                <div style='padding: 20px 0 20px 0; height: 90px; background-color: " . $odd_even . "'>
                    <div style='width: 25%; position: relative; float: left; padding: 10px; margin-top: 0px;'>
                        <img id='coinmotion_img_crypto_side".$rand."' style='' src='".plugin_dir_url( __FILE__ ).'../public/imgs/'.strtolower($currency).".svg' />
                    </div>
                    <div style='width: 74%; position: relative; float: left; padding: 0px;'>
                        <span id='coinmotion_data".$rand."' style='font-size: 22px; color: " . $text_color . "; font-weight: 400; padding: 0px;'>
                            <span style='color: " . $text_color . ";'>".$data." ".strtoupper($actual_currency['default_currency'])."</span>
                        </span>
                        <br/>
                        <span style='font-size: 20px; font-weight: 400; color: ".$color."'>".$percent."</span>
                    </div>
                </div>
            
    <style>
    #coinmotion_img_crypto_side".$rand."{
        margin-top: -5px;
        width: 100%;
        height: auto;
    }

    #coinmotion_img_crypto_top".$rand."{
        /*display: none;*/
        margin-top: -5px;
        width: 100%;
        height: auto;
    }

    #coinmotion_data_crypto_top".$rand."{
        display: none;
    }

    /*@media (max-width: 480px) {
      #coinmotion_img_crypto_side".$rand."{
        display: none;
      }
      #coinmotion_img_crypto_top".$rand."{
        display: block;
      }
      #coinmotion_data_crypto_top".$rand."{
        display: block;
      }
      #coinmotion_data_crypto_side".$rand."{
        display: none;
      }
      #coinmotion_data".$rand."{
        display: block;
      }
    }*/
    </style>";
}

/**
 * Configuracion: title, background, orientation y text_color
 * 
 * 
 */
function coinmotion_currency_carousel_shortcode($atts = [])
{	
    //
    $rand_vertical = rand();
    $rand_horizontal = rand();
    $rand = '';
    $atts = array_change_key_case((array)$atts, CASE_LOWER);
    $curren = new CoinmotionGetCurrencies();
    $actual_currency = coinmotion_get_widget_data();
    $actual_curr_value = floatval($curren->getCotization($actual_currency['default_currency']));
    $coinmotion_atts = shortcode_atts(['title' => 'Coinmotion'], $atts, 'coinmotion');
    $orientation = "HORIZONTAL";
    $text_color = "black";
    $background_color = "none";
    $show_button = "false";

    if (isset($atts['text_color'])) 
        $text_color = $atts['text_color'];
    
    if (isset($atts['orientation']))
        $orientation = $atts['orientation'];

    if (isset($atts['background'])){
        $background_color = $atts['background'];
    }

    if (isset($atts['show_button'])){
        $show_button = $atts['show_button'];
    }

    if (isset($atts['currency'])){
        $currencies = explode(",", $atts['currency']);
    }
    else{
        $currencies = ['btc',
                        'ltc',
                        'eth',
                        'xrp',
                        'xlm',
                        'aave',
                        'link',
                        'usdc',
                        'uni',
                        'usdt',
                        'dot',
                        'sol',
                        'matic',
                        'sand',
                        'mana'];
    }
    $currencies = array_map( 'strtolower', $currencies ); 
    // Get data from API
    $comm = new CoinmotionComm();
    $data = json_decode($comm->getRates(), true);

    $output = "";
    $string = "";    
    $total_cryptos = count($currencies);
    $call_to_funcion = "coinmotion_create_box_horizontal";
    (strtoupper($orientation) === "HORIZONTAL") ? $rand = $rand_horizontal : $rand = $rand_vertical;

    if (strtoupper($orientation) === "HORIZONTAL"){
        $css_output = "<style>#coinmotion_currency_carousel".$rand." {
            background-color: " . $atts['background'] . ";
            display: -webkit-box;
            width:100%;
            height:85px;
            overflow: hidden;
        }
        #coinmotion_currency_carousel".$rand." div {
            height:60px;
        }</style>";
    }
    else{
        $call_to_funcion = "coinmotion_create_box_vertical";
        $css_output = "<style>#coinmotion_currency_carousel".$rand." {
            background-color: " . $atts['background'] . ";
            width: 270px;
            padding: 5px;
            height: 425px;
            overflow: hidden;
        }
        #coinmotion_currency_carousel".$rand." div{
         margin-top: 5px;
         margin-bottom: 5px;
        }</style>";
    }

    $odd_even = 'white';
    $cont_odd_even = 0;
    foreach ($currencies as $curr){
        $curr_text = '';
        if (array_key_exists($curr, CoinmotionComm::COINMOTION_OUTSIDE_COINMOTION_TRANSLATAION)){
            $curr_text = CoinmotionComm::COINMOTION_OUTSIDE_COINMOTION_TRANSLATAION[$curr] . 'Eur';
        }
        else{
            $curr_text = $curr . 'Eur';
        }

        if ((float)$data[$curr_text]['changeAmount'] > 0){
            $color = "green";
        }
        elseif ((float)$data[$curr_text]['changeAmount'] === 0){
            $color = "black";
        }
        else{
            $color = "red";
        }
        $string .= $call_to_funcion(strtoupper($curr), $color, getFormattedData((float)$data[$curr_text]['buy'] *$actual_curr_value, false), $text_color, $data[$curr_text]['fchangep'], $actual_currency, $background_color, $rand, $odd_even);
        if (($cont_odd_even % 2) === 0)
            $odd_even = '#fafafa';
        else
            $odd_even = 'white';
        $cont_odd_even++;
    }
    
    $button = new Coinmotion_Affiliate_Button();
    
    $output .= "<h4>".$atts['title']."</h4>";
    if (strtoupper($orientation) === "HORIZONTAL"){
        $output .= '
        <div id="coinmotion_currency_carousel'.$rand.'">
            <div class="marquee' . $rand . '">
                    '.$string.'
            </div>
        </div>
        <style>
            .marquee'.$rand.' {
                height: 160px;
                width: 100%;
                overflow: hidden;
                position: relative;  
                /*width: fit-content;
                display: flex;          */
            }

            .marquee__inner'.$rand.' {
                
                position: relative;
                /*transform: translateX(0);
                animation: marquee 30s linear infinite;
                animation-play-state: running;*/
            }

            .marquee'.$rand.' span {
                /*padding: 0 1rem;*/
            }

            /*@keyframes marquee'.$rand.' {
                0% { left: 100%; }
                50% { left: -100%; }
                100% {left: -100%}
            }*/
        </style>';
    }
    else{
        $output .= '<div id="coinmotion_currency_carousel'.$rand.'">
            <div class="marquee'.$rand.'">
                    '.$string.'
            </div>
        </div>
        <style>
            .marquee'.$rand.' {
                height: 100%;
                width: 250px;
                overflow: hidden;
                position: relative;    
                width: fit-content;        
            }

            .marquee__inner'.$rand.' {
                width: fit-content;
                position: relative;
                /*transform: translateY(0);
                animation: marquee 30s linear infinite;
                animation-play-state: running;*/
                overflow: hidden;
            }

            .marquee'.$rand.' span {
                padding: 0 1rem;
                width: 270px;
            }

            /*.marquee'.$rand.' span::before {
                content: "\A";
            }

            @keyframes marquee'.$rand.' {
                0% { left: 100%; }
                50% { left: -100%; }
                100% {left: -100%}
            }*/
        </style>';
    }
    $output .= "<script>
                var len = jQuery('script[src=\'//cdn.jsdelivr.net/jquery.marquee/1.4.0/jquery.marquee.min.js\']').length;
                if (len === 0) {
                    jQuery.getScript('//cdn.jsdelivr.net/jquery.marquee/1.4.0/jquery.marquee.min.js');
                }
                </script>";
    $direction = 'up';
    if (strtoupper($orientation) === "HORIZONTAL"){
        $direction = 'left';
    }

    $output .= '<script>
            window.addEventListener("load",function(event) {
                    jQuery(".marquee'.$rand.'").marquee({
                        duration: 15000,
                        gap: 0,
                        delayBeforeStart: 0,
                        direction: "' . $direction . '",
                        duplicated: true
                    });
                },false);
                </script>';
    return $css_output.$output;
} 
?>