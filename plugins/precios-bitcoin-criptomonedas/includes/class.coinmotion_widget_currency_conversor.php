<?php
function coinmotion_widget_currency_conversor_shortcode($atts = []): string
{
    $params = [];

    if (isset($atts['title'])) {
        $params['title'] = $atts['title'];
    }
    else {
        $params['title'] = '';
    }

    if (isset($atts['background_color'])) {
        $params['background_color'] = $atts['background_color'];
    }
    else {
        $params['background_color'] = '#ffffff';
    }

    if (isset($atts['text_color'])) {
        $params['text_color'] = $atts['text_color'];
    }
    else {
        $params['text_color'] = '#a7a7a7';
    }
    
    if (isset($atts['show_button'])) {
        $params['show_button'] = $atts['show_button'];
    }
    else {
        $params['show_button'] = 'false';
    }

    return Coinmotion_Widget_Currency_Conversor::getCurrencyConversorHtmlCode($params);
}



class Coinmotion_Widget_Currency_Conversor extends WP_Widget {

	public const CURRENCIES = ['btcEur',
                        'ltcEur',
                        'ethEur',
                        'xrpEur',
                        'xlmEur',
                        'aaveEur',
                        'linkEur',
                        'usdcEur',
                        'uniEur',
                        'tetherEur',
                        'polkadotEur',
                        'solanaEur',
                        'matic-networkEur',
                        'the-sandboxEur',
                        'decentralandEur',];
	public const CURRENCIES_DISPLAY = ['BTC',
                                'LTC',
                                'ETH',
                                'XRP',
                                'XLM',
                                'AAVE',
                                'LINK',
                                'USDC',
                                'UNI',
                                'USDT',
                                'DOT',
                                'SOL',
                                'MATIC',
                                'SAND',
                                'MANA'];
	
 	public function __construct() {
 		$options = array(
 			'classname' => 'coinmotion_widget_currency_conversor',
 			'description' => __( 'Sidebar widget to display currency conversor.', 'coinmotion' )
 		);
 		$widget_title = __( 'Coinmotion: Currency/Crypto Conversor', 'coinmotion' );
 		parent::__construct(
 			'coinmotion_widget_currency_conversor', $widget_title, $options
 		);
 	}

 	// Contenido del widget
 	public function widget( $args, $instance ) {
 		$params = coinmotion_get_widget_currency_conversor_data();
 		
 		$return = $args['before_widget'];

 		//Título del widget por defecto
 		if ( ! empty( $instance[ 'title' ] ) ) {
 		  $return .= $args[ 'before_title' ] . apply_filters( 'widget_title', $instance[ 'title' ] ) . $args[ 'after_title' ];
 		}
 		
 		if ( ! empty( $instance[ 'background_color' ] ) ) {
 		  $params['background_color'] = $instance[ 'background_color' ];
 		}
 		if ( ! empty( $instance[ 'text_color' ] ) ) {
 		  $params['text_color'] = $instance[ 'text_color' ];
 		}
 		if ( ! empty( $instance[ 'show_button' ] ) ) {
 		  $params['show_button'] = $instance[ 'show_button' ];
 		}

 		$return .= self::getCurrencyConversorHtmlCode($params);

		$return .= $args[ 'after_widget' ];

        echo $return;
 	}

 	//Formulario widget
	public function form( $instance ) {
		$defaults = coinmotion_get_widget_currency_conversor_data();

	  	if ( ! empty( $instance[ 'title' ] ) )
	  		$defaults['title'] =  $instance[ 'title' ];

	  	if ( ! empty( $instance[ 'background_color' ] ) ) {
            $defaults['background_color'] = $instance['background_color'];
        }

	  	if ( ! empty( $instance[ 'text_color' ] ) ) {
            $defaults['text_color'] = $instance['text_color'];
        }

	  	if ( ! empty( $instance[ 'show_button' ] ) ) {
            $defaults['show_button'] = $instance['show_button'];
        }

	  	$widget_title = __( 'Currency/Crypto Conversor Title', 'coinmotion' );
	  	?>
	  	<!-- Estructura formulario-->
	  	<table style="margin-top: 10px;">
	  		<tr>
	  			<td>	  				
	  				<label for="<?= $this->get_field_id( 'title' ) ?>">
						<strong><?= __('Widget Title', 'coinmotion') ?></strong>
					</label> 
			 			
					<input 
					  class="coinmotion_widefat_coin2" 
					  id="<?= $this->get_field_id( 'title' ) ?>" 
					  name="<?= $this->get_field_name( 'title' ) ?>" 
					  type="text" 
					  value="<?= $defaults['title']; ?>">
	  			</td>
	  		</tr>
	  	</table>	
	  	<fieldset style="margin-top: 20px;">
	  		<legend><strong><?= __( 'Design Options', 'coinmotion' ) ?></strong></legend>
	  		<table>
	  		<tr>
	  			<td>
	  				<label for="<?= $this->get_field_id( 'text_color' ) ?>">
						<?= __('Text<br/>Color', 'coinmotion') ?>
					</label> 
			 			
					<input 
					  class="coinmotion_widefat_coin2" 
					  id="<?= $this->get_field_id( 'text_color' ) ?>" 
					  name="<?= $this->get_field_name( 'text_color' ) ?>" 
					  type="color" 
					  value="<?= $defaults['text_color'] ?>"
					  style="height: 30px; width: 45px; display: table;">
	  			</td>
	  			<td>
	  				<label for="<?= $this->get_field_id( 'background_color' ) ?>">
						<?= __('Background<br/>Color', 'coinmotion') ?>
					</label> 
			 			
					<input 
					  class="coinmotion_widefat_coin2" 
					  id="<?= $this->get_field_id( 'background_color' ) ?>" 
					  name="<?= $this->get_field_name( 'background_color' ) ?>" 
					  type="color" 
					  value="<?= $defaults['background_color'] ?>"
					  style="height: 30px; width: 45px; display: table;">
	  			</td>
	  		</tr>
	  		<tr>
	  			<td colspan="2">
	  				<label for="<?= $this->get_field_id( 'show_button' ) ?>">
						<?= __('Show<br/>Coinmotion Button', 'coinmotion') ?>
					</label> 
			 			<?php
			 			$checkbox = "";
			 			if ($defaults['show_button'] === 'true'){
			 				$checkbox = " checked ";
			 			} 
			 			?>
					<input 
					  class="coinmotion_widefat_coin2" 
					  id="<?= $this->get_field_id( 'show_button' ) ?>" 
					  name="<?= $this->get_field_name( 'show_button' ) ?>" 
					  type="checkbox" 
					  value="true"
					  <?= $checkbox ?>>
	  			</td>
	  			<td>	  				
	  			</td>
	  		</tr>
	  	</table>
	  	</fieldset>  	
	  	<?php
 	}
 	
 	function update( $new_instance, $old_instance ) {
 		$instance = $old_instance;
 		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
 		$instance[ 'background_color' ] = strip_tags( $new_instance[ 'background_color' ] );
 		$instance[ 'text_color' ] = strip_tags( $new_instance[ 'text_color' ] );
 		
        if (isset($new_instance['show_button'])) {
            $instance['show_button'] = $new_instance['show_button'];
        }
 		else {
            $instance['show_button'] = 'false';
        }
 		return $instance;
 	}

    public static function getCurrencyConversorHtmlCode($params): string
    {
        $curren = new CoinmotionGetCurrencies();

        $actual_currency = coinmotion_get_widget_data();
        $return = '';

        $actual_curr_value = (float)$curren->getCotization($actual_currency['default_currency']);

        $comm = new CoinmotionComm();
        $data = json_decode($comm->getRates(), true);

        //Contenido

        $select = "";
        $initial_crypto = strtoupper(Coinmotion_Widget_Currency_Conversor::CURRENCIES_DISPLAY[0]);
        for ($i = 0, $iMax = count(Coinmotion_Widget_Currency_Conversor::CURRENCIES); $i < $iMax; $i++){
            $curr = Coinmotion_Widget_Currency_Conversor::CURRENCIES[$i];
            $curr_display = Coinmotion_Widget_Currency_Conversor::CURRENCIES_DISPLAY[$i];
            if ($i === 0)
                $select .= "<option  selected value='".($data[$curr]['buy']*$actual_curr_value)."'>".strtoupper($curr_display)."</value>";
            else
                $select .= "<option value='".($data[$curr]['buy']*$actual_curr_value)."'>".strtoupper($curr_display)."</value>";
        }
        $rand = rand();
        $return .= '<link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oxygen:wght@300;400;700&display=swap" rel="stylesheet">';
        $return .= "<script>";
        $return .= "var vc_cripto_prices_conversor".$rand." = [];";
        foreach ($data as $key => $value){
            if (isset($value['currencyCode']) && isset($value['buy']))
                $return .= "vc_cripto_prices_conversor".$rand."['" . $key . "'] = {code: '" . $value['currencyCode'] . "', buy: '" . $value['buy'] . "'};";
        }
        $return .= "jQuery(document).ready(function(){
        jQuery('#coinmotion_conv_input".$rand."').on('change paste keyup', function(){
            input = jQuery('#coinmotion_conv_input".$rand."').val();
            
            if ((input === '') || (input === undefined))
                input = 1;
            jQuery('#coinmotion_conv_output".$rand."').val(					 
                (Number.parseFloat( input ) / 
                Number.parseFloat( jQuery('#coinmotion_change_currency".$rand."').val() )).toFixed(4)
            );
        });

        jQuery('#coinmotion_conv_output".$rand."').on('change paste keyup', function(){
            input = jQuery('#coinmotion_conv_output".$rand."').val();
            
            if ((input === '') || (input === undefined))
                input = 1;
            jQuery('#coinmotion_conv_input".$rand."').val(					 
                (Number.parseFloat( input ) *
                Number.parseFloat( jQuery('#coinmotion_change_currency".$rand."').val() )).toFixed(4)
            );
        });

        jQuery('#coinmotion_change_currency".$rand."').on('change', function(){
            var cripto = jQuery('#coinmotion_change_currency".$rand." option:selected' ).text();
            var cripto_value = jQuery('#coinmotion_change_currency".$rand." option:selected' ).val();
            var currency = '" . $actual_currency['default_currency'] . "';
            jQuery('#vc_conversor_cripto_curr_text".$rand."').html(currency + ' ' + cripto);
            input = jQuery('#coinmotion_conv_input".$rand."').val();
            
            if ((input === '') || (input === undefined))
                input = 1;

            jQuery('#coinmotion_conv_output".$rand."').val(					
                (Number.parseFloat( input ) /
                Number.parseFloat( jQuery('#coinmotion_change_currency".$rand."').val() )).toFixed(4)
            );

        });

        jQuery('.select-selected".$rand."').bind('DOMSubtreeModified', function(){
            input = jQuery('#coinmotion_conv_input".$rand."').val();
            
            if ((input === '') || (input === undefined))
                input = 1;
            jQuery('#coinmotion_conv_output".$rand."').val(					
                (Number.parseFloat( input ) /
                Number.parseFloat( jQuery('#coinmotion_change_currency".$rand."').val() )).toFixed(4)
            );
        });
    })";
        $return .= "</script>";

        $return .= "<style>#coinmotion_conversor".$rand." {
        font-family: 'Oxygen', sans-serif;
        display: table;
        table-layout: fixed;        
        width:100%;
        max-width: 400px;
        padding: 10px;
        background-color: ".$params['background_color'].";
        color: ".$params['text_color']."
    }
    #coinmotion_conversor".$rand." input{
        background-color: #eaeaea;
        color: ".$params['text_color'].";
        border-top: 0;
        border-right: 0;
        border-left: 0;
        border-bottom: 0;
        box-shadow: 0px;
        font-size: 14px;
        width: 100%;
        outline: none;
    }
    #coinmotion_conversor".$rand." td, th{
        border: 0px;
        background-color: ".$params['background_color'].";
    }
    div.coinmotion_conversor_line".$rand." {
        display: table-cell;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }</style>";

        $return .= "<style>";
        $return .= " .custom-select".$rand." {
        position: relative;
        font-family: Arial;
    }
    
    .custom-select".$rand." select {
        display: none;
    }
    #coinmotion_change_currency".$rand." > option {
        background-color: #ff9900;
        color: white;
        font-family: 'Oxygen', sans-serif;;
    }
    .select-selected".$rand." {
        background-color: ".$params['background_color'].";
        color: ".$params['text_color'].";
        text-align: center;
    }

    .select-selected".$rand.":after {
        position: absolute;
        content: '';
        top: 17px;
        right: 0px;
        width: 0;
        height: 0;
        border: 6px solid transparent;
        border-color: ".$params['text_color']." transparent transparent transparent;
    }
    
    .select-selected".$rand.".select-arrow-active".$rand.":after {
        border-color: transparent transparent ".$params['text_color']." transparent;
        top: 7px;
    }
    
    .select-items".$rand." div,.select-selected".$rand." {
        color: ".$params['text_color'].";
        padding: 8px 16px;
        border: 1px solid transparent;
        /*border-color: transparent transparent rgba(0, 0, 0, 0.1) transparent;*/
        cursor: pointer;
        font-size: 13px;
    }
    
    .select-items".$rand." {
        position: absolute;
        background-color: ".$params['background_color'].";
        top: 100%;
        left: 0;
        right: 0;
        z-index: 99;
    }
    
    .select-hide".$rand." {
        display: none;
    }
    
    .select-items".$rand." div:hover, .same-as-selected".$rand." {
        background-color: rgba(0, 0, 0, 0.1);
    } ";

        $return .= "</style>";

        $return .= "<div id='coinmotion_conversor".$rand."'>";
        $return .= '<div style="text-align: center; width: 100%"><span style="color: ' . $params['text_color'] . '; font-weight: bold; font-size: 20px;" id="vc_conversor_cripto_curr_text'.$rand.'">' . $actual_currency['default_currency'] . ' BTC</span></div>';
        $return .= '<div style="text-align: center; width: 100%; height: 10px;"><span style="color: #009ac0; font-size: 50px; line-height: 0px;">. . . . . . . . .</span></div>';

        $return .= '<div style="width: 98%; margin-top: 25px; height: 37px;background-color: #eaeaea; border-radius: 10px;-webkit-box-shadow: 5px 5px 15px 5px rgba(0,0,0,0.20); box-shadow: 5px 5px 15px 5px rgba(0,0,0,0.20);">';
        $return .= '<div style="position: relative; float: left; width: 70%; "><input style="" placeholder="'.($actual_curr_value*$data[Coinmotion_Widget_Currency_Conversor::CURRENCIES[0]]['buy']).'" type="number" step="any" id="coinmotion_conv_input'.$rand.'" name="coinmotion_conv_input'.$rand.'" value="'.($actual_curr_value*$data[Coinmotion_Widget_Currency_Conversor::CURRENCIES[0]]['buy']).'" /></div>';
        $return .= '<div style="text-align: center; width: 25%; position: relative; float: right; padding-top: 5px; background-color: #009ac0; border-radius: 0 10px 10px 0; height: 100%;"><span style="color: white; font-weight: bold;">' . $actual_currency['default_currency'] . '</span></div>';
        $return .= '</div>';

        $return .= '<div style="text-align: center; padding: 10px 0;"></div>';
        $return .= '<div style="width: 98%; height: 37px; background-color: #eaeaea; border-radius: 10px;-webkit-box-shadow: 5px 5px 15px 5px rgba(0,0,0,0.20); box-shadow: 5px 5px 15px 5px rgba(0,0,0,0.20);">';
        $return .= '<div style="position: relative; float: left; width: 70%; "><input type="number" step="any" id="coinmotion_conv_output' . $rand . '" name="coinmotion_conv_output ' . $rand . '" placeholder="1" value="1" /></div>';
        $return .= '<div style="text-align: center; width: 25%; position: relative; float: right; background-color: #ff9900; border-radius: 0 10px 10px 0; height: 100%;"><select style="height: 35px; padding: 0px; color: white;text-align-last:center; background: transparent; border: 0;" id="coinmotion_change_currency' . $rand . '">' . $select . '</select></div>';
        $return .= '</div>';

        $button = new Coinmotion_Affiliate_Button();
        $return .= '<div style="margin: 25px;">';
        $return .= $button->generateCMLink('currency_crypto_conversor', $params['text_color']);
        $return .= '</div>';
        if ($params['show_button'] === 'true'){
            $return .= $button->generateButton();
        }

        $return .= '</div>';
        $return .= '<script>';
        $return .= 'var x, i, j, selElmnt, a, b, c;
    x = document.getElementsByClassName("custom-select'.$rand.'");
    for (i = 0; i < x.length; i++) {
        selElmnt = x[i].getElementsByTagName("select")[0];
        a = document.createElement("DIV");
        a.setAttribute("class", "select-selected'.$rand.'");
        a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
        x[i].appendChild(a);
        b = document.createElement("DIV");
        b.setAttribute("class", "select-items'.$rand.' select-hide'.$rand.'");
        for (j = 0; j < selElmnt.length; j++) {
        c = document.createElement("DIV");
        c.innerHTML = selElmnt.options[j].innerHTML;
        c.addEventListener("click", function(e) {
            var y, i, k, s, h;
            s = this.parentNode.parentNode.getElementsByTagName("select")[0];
            h = this.parentNode.previousSibling;
            for (i = 0; i < s.length; i++) {
                if (s.options[i].innerHTML == this.innerHTML) {
                s.selectedIndex = i;
                h.innerHTML = this.innerHTML;
                y = this.parentNode.getElementsByClassName("same-as-selected'.$rand.'");
                for (k = 0; k < y.length; k++) {
                    y[k].removeAttribute("class");
                }
                this.setAttribute("class", "same-as-selected'.$rand.'");
                break;
                }
            }
            h.click();
        });
        b.appendChild(c);
        }
        x[i].appendChild(b);
        a.addEventListener("click", function(e) {
        e.stopPropagation();
        closeAllSelect(this);
        this.nextSibling.classList.toggle("select-hide'.$rand.'");
        this.classList.toggle("select-arrow-active");
        });
    } 
    
    function closeAllSelect(elmnt) {
        var x, y, i, arrNo = [];
        x = document.getElementsByClassName("select-items'.$rand.'");
        y = document.getElementsByClassName("select-selected'.$rand.'");
        for (i = 0; i < y.length; i++) {
        if (elmnt == y[i]) {
            arrNo.push(i)
        } else {
            y[i].classList.remove("select-arrow-active'.$rand.'");
        }
        }
        for (i = 0; i < x.length; i++) {
        if (arrNo.indexOf(i)) {
            x[i].classList.add("select-hide'.$rand.'");
        }
        }
    }
    
    document.addEventListener("click", closeAllSelect); ';
        $return .= '</script>';


        return $return;
    }
}
?>