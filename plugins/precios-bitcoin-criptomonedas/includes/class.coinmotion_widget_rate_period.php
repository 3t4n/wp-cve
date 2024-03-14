<?php

function coinmotion_widget_rate_period_shortcode($atts = [])
{
    $params = [];

    if (isset($atts['title'])) {
        $params['title'] = $atts['title'];
    }
    else {
        $params['title'] = '';
    }

    if (isset($atts['currency'])) {
        $params['currency'] = $atts['currency'];
    }
    else {
        $params['currency'] = 'btc';
    }

    if (isset($atts['background_color'])) {
        $params['background'] = $atts['background_color'];
    }
    else {
        $params['background'] = '#ffffff';
    }

    if (isset($atts['line_color'])) {
        $params['line_color'] = $atts['line_color'];
    }
    else {
        $params['line_color'] = '#009ac0';
    }

    if (isset($atts['graph'])) {
        $params['graph'] = $atts['graph'];
    }
    else {
        $params['graph'] = 'line';
    }

    if (isset($atts['period'])) {
        $params['period'] = ucwords($atts['period']);
    }
    else {
        $params['period'] = 'Day';
    }

    if (isset($atts['type'])) {
        $params['type'] = $atts['type'];
    }
    else {
        $params['type'] = 'price';
    }

    if (isset($atts['height'])) {
        $params['height'] = $atts['height'];
    }
    else {
        $params['height'] = '300px';
    }

    if (isset($atts['width'])) {
        $params['width'] = $atts['width'];
    }
    else {
        $params['width'] = '300px';
    }

    if (isset($atts['points'])) {
        $params['points'] = $atts['points'];
    }
    else {
        $params['points'] = '10';
    }

    if (isset($atts['show_button'])) {
        $params['show_button'] = $atts['show_button'];
    }
    else {
        $params['show_button'] = 'false';
    }

    return Coinmotion_Widget_Rate_Period::getRatePeriodHtmlCode($params);
}


class Coinmotion_Widget_Rate_Period extends WP_Widget {

	public $periods = ['hour', 'Day', 'Week', 'Month', '3_months', 'Year'];
	public const CURRENCIES = ['BTC', 'LTC', 'ETH', 'XRP', 'XLM', 'AAVE', 'LINK', 'USDC', 'UNI', 'USDT', 'DOT', 'SOL', 'MATIC', 'SAND', 'MANA'];
	public $types = ['price', 'interest'];
	var $graph_type = ['line', 'bar'];

 	public function __construct() {
 		$options = array(
 			'classname' => 'coinmotion_widget_rate_period',
 			'description' => __( 'Sidebar widget to display price evolution.', 'coinmotion' )
 		);
 		$widget_title = __( 'Coinmotion: Price Evolution', 'coinmotion' );
 		parent::__construct(
 			'coinmotion_widget_rate_period', $widget_title, $options
 		);
 	}

 	// Contenido del widget
 	public function widget( $args, $instance ) {
 		$params = coinmotion_get_widget_rate_period_data();
 		
 		$return = $args['before_widget'];
 		//Título del widget por defecto

 		if ( ! empty( $instance[ 'title' ] ) ) {
 		  $return .= $args[ 'before_title' ] . apply_filters( 'widget_title', $instance[ 'title' ] ) . $args[ 'after_title' ];
 		}
 		if ( ! empty( $instance[ 'title' ] ) ) {
 		  $params['title'] = $instance[ 'title' ];
 		}
 		if ( ! empty( $instance[ 'line_color' ] ) ) {
 		  $params['line_color'] = $instance[ 'line_color' ];
 		}
 		if ( ! empty( $instance[ 'period' ] ) ) {
 		  $params['period'] = $instance[ 'period' ];
 		}
 		if ( ! empty( $instance[ 'currency' ] ) ) {
 		  $params['currency'] = $instance[ 'currency' ];
 		}
 		if ( ! empty( $instance[ 'type' ] ) ) {
 		  $params['type'] = $instance[ 'type' ];
 		}
 		if ( ! empty( $instance[ 'graph' ] ) ) {
 		  $params['graph'] = $instance[ 'graph' ];
 		}
 		if ( ! empty( $instance[ 'width' ] ) ) {
 		  $params['width'] = $instance[ 'width' ];
 		}
 		if ( ! empty( $instance[ 'height' ] ) ) {
 		  $params['height'] = $instance[ 'height' ];
 		}
 		if ( ! empty( $instance[ 'background' ] ) ) {
 		  $params['background'] = $instance[ 'background' ];
 		}
 		if ( ! empty( $instance[ 'points' ] ) ) {
 		  $params['points'] = $instance[ 'points' ];
 		}
 		if ( isset( $instance[ 'show_button' ] ) ) {
            $params['show_button'] = $instance[ 'show_button' ];
        }
        else{
            $params['show_button'] = 'false';
        }
 		
 		$return .= self::getRatePeriodHtmlCode($params);
 				
 		$return .=  $args[ 'after_widget' ];

         echo $return;
 	}

 	//Formulario widget
	public function form( $instance ) {
		$defaults = coinmotion_get_widget_rate_period_data();

	  	if ( ! empty( $instance[ 'title' ] ) )
	  		$defaults['title'] =  $instance[ 'title' ];

	  	if ( ! empty( $instance[ 'line_color' ] ) )
	  		$defaults['line_color'] = $instance[ 'line_color' ];

	  	if ( ! empty( $instance[ 'period' ] ) )
	  		$defaults['period'] = $instance[ 'period' ];

	  	if ( ! empty( $instance[ 'currency' ] ) )
	  		$defaults['currency'] = $instance[ 'currency' ];

	  	if ( ! empty( $instance[ 'type' ] ) )
	  		$defaults['type'] = $instance[ 'type' ];

	  	if ( ! empty( $instance[ 'height' ] ) )
	  		$defaults['height'] = $instance[ 'height' ];

	  	if ( ! empty( $instance[ 'points' ] ) )
	  		$defaults['points'] = $instance[ 'points' ];

	  	if ( ! empty( $instance[ 'width' ] ) )
	  		$defaults['width'] = $instance[ 'width' ];
	  	
	  	if ( ! empty( $instance[ 'background' ] ) )
	  		$defaults['background'] = $instance[ 'background' ];

	  	if ( ! empty( $instance[ 'graph' ] ) )
	  		$defaults['graph'] = $instance[ 'graph' ];

	  	if ( ! empty( $instance[ 'show_button' ] ) )
	  		$defaults['show_button'] = $instance[ 'show_button' ];
	  
	  	$widget_title = __( 'Price Evolution Title', 'coinmotion' );
	  	$widget_line_color = __( 'Line Color', 'coinmotion' );
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
	  		<legend><strong><?= __('Design Options', 'coinmotion') ?></strong></legend>
	  		<table>
	  		<tr>
	  			<td width="25%">
	  				<label for="<?= $this->get_field_id( 'line_color' ) ?>">
						<?= __('Line<br/>Color', 'coinmotion') ?>
					</label> 
			 			
					<input 
					  class="coinmotion_widefat_coin2" 
					  id="<?= $this->get_field_id( 'line_color' ) ?>" 
					  name="<?= $this->get_field_name( 'line_color' ) ?>" 
					  type="color" 
					  value="<?= $defaults['line_color'] ?>"
					  style="height: 30px; width: 45px; display: table;">
	  			</td>
	  			<td width="25%">
	  				<label for="<?= $this->get_field_id( 'background' ) ?>">
						<?= __('Background<br/>Color', 'coinmotion') ?>
					</label> 
			 			
					<input 
					  class="coinmotion_widefat_coin2" 
					  id="<?= $this->get_field_id( 'background' ) ?>" 
					  name="<?= $this->get_field_name( 'background' ) ?>" 
					  type="color" 
					  value="<?= $defaults['background'] ?>"
					  style="height: 30px; width: 45px; display: table;">
	  			</td>
	  			<td width="25%">
	  				<label for="<?= $this->get_field_id( 'width' ) ?>">
						<?= __('Width<br/>(px or %)', 'coinmotion') ?>
					</label> 
			 			
					<input 
					  class="coinmotion_widefat_coin2" 
					  id="<?= $this->get_field_id( 'width' ) ?>" 
					  name="<?= $this->get_field_name( 'width' ) ?>" 
					  type="text" 
					  value="<?= $defaults['width'] ?>">
	  			</td>
	  			<td width="25%">
	  				<label for="<?= $this->get_field_id( 'height' ) ?>">
						<?= __('Height<br/>(px or %)', 'coinmotion') ?>
					</label> 
			 			
					<input 
					  class="coinmotion_widefat_coin2" 
					  id="<?= $this->get_field_id( 'height' ) ?>" 
					  name="<?= __( $this->get_field_name( 'height' ) )?>" 
					  type="text" 
					  value="<?= __( $defaults['height'] ) ?>">
	  			</td>
	  		</tr>
	  		<tr>
              <td colspan="4">
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
	  		</tr>
	  	</table>
	  	</fieldset>
	  	<fieldset style="margin-top: 20px;">
	  		<legend><strong><?= __('Graph Options', 'coinmotion') ?></strong></legend>
	  		<table>
	  			<tr>
	  				<td>
	  					<label for="<?= $this->get_field_id( 'points' ) ?>">
							<?= __('Points', 'coinmotion') ?>
						</label> 
				 			
						<input 
						  class="coinmotion_widefat_coin2" 
						  id="<?= $this->get_field_id( 'points' ) ?>" 
						  name="<?= $this->get_field_name( 'points' ) ?>" 
						  type="text" 
						  value="<?= $defaults['points'] ?>">
	  				</td>
	  				<td>
	  					<label for="<?= $this->get_field_id( 'period' ) ?>">
						<?= __( 'Period', 'coinmotion' ) ?>
						</label> 
				 			
						<select class="coinmotion_widefat_coin2" 
						  id="<?= $this->get_field_id( 'period' ) ?>" 
						  name="<?= $this->get_field_name( 'period' ) ?>" >
						  <?php
				          foreach ($this->periods as $p){
				          	$cad = explode("_", $p);
				          	if (count($cad) >= 2){
				          		$show = __($cad[0] . " " . str_replace("_", " ", ucfirst($cad[1])), 'coinmotion');
				          	}
				          	else
				          		$show = __(ucfirst($p), 'coinmotion');
				          	if ($p == 'hour')
				          		$show = __($p, 'coinmotion');
                            if ($p == '3_months')
				          		$show = '3 ' . __('Months', 'coinmotion');

				          	if ($p === $defaults['period']){
				          	?>
				            	<option value="<?= $p ?>" selected><?= ucfirst($show) ?></option>
				            <?php
				            }
				            else{
				            ?>
				            	<option value="<?= $p ?>"><?= ucwords($show) ?></option>
				            <?php
				            }
				          }
				          ?>       
						</select>
	  				</td>
	  			</tr>
	  			<tr>
					<td>
						<label for="<?= $this->get_field_id( 'currency' ) ?>">
						<?= __( 'Crypto', 'coinmotion' ); ?>
						</label> 
				 			
						<select class="coinmotion_widefat_coin2" 
						  id="<?= $this->get_field_id( 'currency' ) ?>" 
						  name="<?= $this->get_field_name( 'currency' ) ?>" >
						  <?php
				          foreach (self::CURRENCIES as $c){
				          	if ($c === $defaults['currency']){
				            ?>
				            	<option value="<?= $c ?>" selected><?= strtoupper($c) ?></option>
				            <?php
				            }
				            else{
				            ?>
				            	<option value="<?= $c ?>"><?= strtoupper($c) ?></option>
				            <?php
				            }
				          }
				          ?>       
						</select>
					</td>
					<td>
						<label for="<?= $this->get_field_id( 'type' ) ?>">
						<?= __( 'Type', 'coinmotion' ); ?>
						</label> 
				 			
						<select class="coinmotion_widefat_coin2" 
						  id="<?= $this->get_field_id( 'type' ) ?>" 
						  name="<?= $this->get_field_name( 'type' ) ?>" >
						  <?php
				          foreach ($this->types as $t){
				          	$t2 = __($t, 'coinmotion');
				          	if ($t === $defaults['type']){
				            ?>
				            	<option value="<?= $t ?>" selected><?= ucfirst($t2) ?></option>
				            <?php
				            }
				            else{
				            ?>
				            	<option value="<?= $t ?>"><?= ucfirst($t2)  ?></option>
				            <?php
				            }
				          }
				          ?>       
						</select>
					</td>
				</tr>
			</table>
		</fieldset>		  
	  	<?php
 	}
 	
 	function update( $new_instance, $old_instance ) {
 		$instance = $old_instance;
 		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
 		$instance[ 'currency' ] = strip_tags( $new_instance[ 'currency' ] );
 		$instance[ 'line_color' ] = strip_tags( $new_instance[ 'line_color' ] );
 		$instance[ 'period' ] = strip_tags( $new_instance[ 'period' ] );
 		$instance[ 'background' ] = strip_tags( $new_instance[ 'background' ] );
 		$instance[ 'type' ] = strip_tags( $new_instance[ 'type' ] );
 		$instance[ 'height' ] = strip_tags( $new_instance[ 'height' ] );
 		$instance[ 'width' ] = strip_tags( $new_instance[ 'width' ] );
 		$instance[ 'graph' ] = isset($new_instance[ 'graph' ]) ? strip_tags( $new_instance[ 'graph' ] ) : 'line';
 		$instance[ 'points' ] = strip_tags( $new_instance[ 'points' ] );
         
 		if (isset($new_instance['show_button'])) {
            $instance['show_button'] = $new_instance['show_button'];
        }
 		else {
            $instance['show_button'] = 'false';
        }
  
 		return $instance;
 	}

    public static function getRatePeriodHtmlCode($params): string
    {
        $comm = new CoinmotionComm();
        $return = '';
        $curren = new CoinmotionGetCurrencies();
        $actual_currency = coinmotion_get_widget_data();
        $actual_curr_value = (float)$curren->getCotization($actual_currency['default_currency']);

        $data = json_decode($comm->getRateHistory(strtolower($params['currency']), $params['period'], $params['type']), true);

        $data_cripto = $comm->getDetails($params['currency'], $params['type']);

        $button = new Coinmotion_Affiliate_Button();

        //Contenido
        $rand = rand();
        $return .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.css" integrity="sha256-IvM9nJf/b5l2RoebiFno92E5ONttVyaEEsdemDC6iQA=" crossorigin="anonymous" />';
        $return .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js" integrity="sha256-TQq84xX6vkwR0Qs1qH5ADkP+MvH0W+9E7TdHJsoIQiM=" crossorigin="anonymous"></script>';
        $return .= "<div class='coinmotion-widget-container_rate_period" . $rand . "'>";
        $return .= '<div style="width: 35%; position: relative; float: left;">';
        $return .= '<span style="display: block; font-weight: bold; line-height: 20px; font-size: 17px;">' . strtoupper($params['currency']) ." - ".strtoupper($actual_currency['default_currency']) . '</span>';

        $period_show = __($params['period'], 'coinmotion');
        if ($params['period'] === '3_months') {
            $period_show = '3 ' . __('Months', 'coinmotion');
        }
        else {
            $period_show = '1 ' . $period_show;
        }
        $return .= '<span style="text-transform: uppercase;">'.strtoupper($period_show).'</span>';
        $return .= '</div>';
        $return .= '<div style="width: 65%; position: relative; float: right; line-height: 40px; font-size: 160%;">';
        $indicator_color = '<span style="color: #31ba2d;">&#x25B2;</span>';

        if ((int)$data_cripto['variation_' . strtolower($params['period'])] < 0) {
            $indicator_color = '<span style="color: red;">&#9660;</span>';
        }
        $return .= '<span style="color: #163951; float: right; margin-right: 10px;">' . $indicator_color . (number_format($data_cripto['actual_price']*$actual_curr_value, 2, ',', '.')).' '.$actual_currency['default_currency'].'</span>';
        $return .= '</div>';
        $return .= '<div style="position: relative; float: right;"><span style="color: #009ac0; font-size: 45px; line-height: 0px; margin-right: 10px;">. . . . . . . . .</span></div>';
        $return .= '<div class="chart-container" style="clear: both; height:275px; width: 100%;">';
        $id_canvas = "coinmotion_rate_period_chart".rand();
        $return .= '<canvas id="'.$id_canvas.'"></canvas>';
        $return .= '</div>';
        $return .= '<div style="margin: 20px;">';
        $return .= $button->generateCMLink('currency_crypto_conversor');
        $return .= '</div>';
        if ($params['show_button'] === 'true'){
            $return .= $button->generateButton();
        }

        $return .= '</div>';
        $data_for_js = "";

        $labels_for_js = "";
        $total_data = count($data);
        $counter = $total_data / ((int)$params['points'] - 1);
        $offset = (int)get_option('gmt_offset');
        if ($offset > 0) {
            $offset = "+" . $offset;
        }

        for ($i = 0; $i < ($total_data - 1); $i += $counter){
            $data_for_js .= ($data[$i][0]*$actual_curr_value) . ",";
            $labels_for_js .= "'" . date('d-m-Y H:i:s', strtotime($offset . " hours", $data[$i][1])) . "',";
        }
        $data_for_js .= ($data[$total_data - 1][0]*$actual_curr_value) . ",";
        $labels_for_js .= "'" . date('d-m-Y H:i:s', strtotime($offset . " hours", $data[$total_data - 1][1])) . "',";
        $data_for_js = substr($data_for_js, 0, -1);
        $labels_for_js = substr($labels_for_js, 0, -1);
        $color = hex2rgba_coinmotion($params['line_color'], 0.7);
        $bg_color = hex2rgba_coinmotion($params['background'], 0.2);

        $return .= '<style>
        .coinmotion-widget-container_rate_period' . $rand . ' {
            background-color: ' . $bg_color . ';
            font-family: "Oxygen", sans-serif;
            padding: 10px;
        }
    </style>';
        $return .= "<script>
        var ctx = document.getElementById('".$id_canvas."');
        var coinmotion_chart_rate_period = new Chart(ctx, {
            type: '".$params['graph']."',
            data: {
                labels: [".$labels_for_js."],
                datasets: [{
                    data: [".$data_for_js."],
                    borderColor: '".$color."',
                    backgroundColor: '".$bg_color. "',
                    borderWidth: 1,
                    fill: true
                }]
            },
            options: {
                elements: {
                    point:{
                        radius: 3
                    }
                },
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem) {
                                return addCommas(parseFloat(tooltipItem.yLabel).toFixed(2));
                        }
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            display: false
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                }
            }
        });
        function addCommas(nStr)
        {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? ',' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + '.' + '$2');
            }
            return x1 + x2;
        }
        </script>";

        return $return;
    }

}

?>