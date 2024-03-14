<?php
function coinmotion_widget_currency_details_shortcode($atts = [])
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

    if (isset($atts['currency'])) {
        $params['currency'] = $atts['currency'];
    }
    else {
        $params['currency'] = 'btc';
    }

    if (isset($atts['type'])) {
        $params['type'] = $atts['type'];
    }
    else {
        $params['type'] = 'price';
    }

    return Coinmotion_Widget_Currency_Details::getCurrencyDetailsHtmlCode($params);
}

class Coinmotion_Widget_Currency_Details extends WP_Widget {

	public const CURRENCIES = ['BTC', 'LTC', 'ETH', 'XRP', 'XLM', 'AAVE', 'LINK', 'USDC', 'UNI', 'USDT', 'DOT', 'SOL', 'MATIC', 'SAND', 'MANA'];
	public $types = ['price', 'interest'];
	
 	public function __construct() {
 		$options = array(
 			'classname' => 'coinmotion_widget_currency_details',
 			'description' => __( 'Sidebar widget to display historical data.', 'coinmotion' )
 		);
 		$widget_title = __( 'Coinmotion: Historical Data', 'coinmotion' );
 		parent::__construct(
 			'coinmotion_widget_currency_details', $widget_title, $options
 		);
 	}

 	// Contenido del widget
 	public function widget( $args, $instance ) {
        $params = coinmotion_get_widget_currency_details_data();
        $return = '';
        $return .= $args['before_widget'];

        //Título del widget por defecto
        if ( ! empty( $instance[ 'title' ] ) ) {
            $return .= $args[ 'before_title' ] . apply_filters( 'widget_title', $instance[ 'title' ] ) . $args[ 'after_title' ];
            $params['title'] = $instance[ 'title' ];
        }
        if ( ! empty( $instance[ 'currency' ] ) ) {
            $params['currency'] = $instance[ 'currency' ];
        }
        if ( ! empty( $instance[ 'type' ] ) ) {
            $params['type'] = $instance[ 'type' ];
        }
        if ( ! empty( $instance[ 'background_color' ] ) ) {
            $params['background_color'] = $instance[ 'background_color' ];
        }
        if ( ! empty( $instance[ 'text_color' ] ) ) {
            $params['text_color'] = $instance[ 'text_color' ];
        }
        if ( isset( $instance[ 'show_button' ] ) ) {
            $params['show_button'] = $instance['show_button'];
        }
        else{
            $params['show_button'] = 'false';
        }
        
        $return .= self::getCurrencyDetailsHtmlCode($params);

        $return .= $args[ 'after_widget' ];
        
        echo $return;
 	}

 	//Formulario widget
	public function form( $instance ): void
    {
		$defaults = coinmotion_get_widget_currency_details_data();
	  	if ( ! empty( $instance[ 'title' ] ) )
	  		$defaults['title'] =  $instance[ 'title' ];

	  	if ( ! empty( $instance[ 'currency' ] ) ) {
            $defaults['currency'] = $instance['currency'];
        }

	  	if ( ! empty( $instance[ 'type' ] ) ) {
            $defaults['type'] = $instance['type'];
        }

	  	if ( ! empty( $instance[ 'background_color' ] ) ) {
            $defaults['background_color'] = $instance['background_color'];
        }

	  	if ( ! empty( $instance[ 'text_color' ] ) ) {
            $defaults['text_color'] = $instance['text_color'];
        }

	  	if ( ! empty( $instance[ 'show_button' ] ) ) {
            $defaults['show_button'] = $instance['show_button'];
        }

	  	$widget_title = __( 'Historical Data Title', 'coinmotion' );
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
	  		<legend><strong><?= __('Data Options', 'coinmotion') ?></strong></legend>
	  		<table>	  			
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
				            	<option value="<?= $c ?>" selected><?= $c ?></option>
				            <?php
				            }
				            else{
				            ?>
				            	<option value="<?= $c ?>"><?= $c ?></option>
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
				          	if ($t === $defaults['type']){
				            ?>
				            	<option value="<?= $t ?>" selected><?= ucfirst(__(strtolower($t), 'coinmotion')) ?></option>
				            <?php
				            }
				            else{
				            ?>
				            	<option value="<?= $t ?>"><?= ucfirst(__(strtolower($t), 'coinmotion'))  ?></option>
				            <?php
				            }
				          }
				          ?>       
						</select>
					</td>
					
				</tr>
			</table>
		</fieldset>
		<fieldset style="margin-top: 20px;">
	  		<legend><strong><?= __('Design Options', 'coinmotion') ?></strong></legend>
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
				</tr>
			</table>
		</fieldset>		  
	  	<?php
 	}
 	
 	function update( $new_instance, $old_instance ) {
 		$instance = $old_instance;
 		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
 		$instance[ 'type' ] = strip_tags( $new_instance[ 'type' ] );
 		$instance[ 'currency' ] = strip_tags( $new_instance[ 'currency' ] );
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
    public static function getCurrencyDetailsHtmlCode($params): string
    {
        $curren = new CoinmotionGetCurrencies();
        $return = '';

        $actual_currency = coinmotion_get_widget_data();
        $actual_curr_value = (float)$curren->getCotization($actual_currency['default_currency']);

        $comm = new CoinmotionComm();
        $data = $comm->getDetails($params['currency'], $params['type']);

        $rand = rand();
        $var_day = getFormattedData((float)$data['variation_day']);
        $var_week = getFormattedData((float)$data['variation_week']);
        $var_month = getFormattedData((float)$data['variation_month']);
        $var_3_months = getFormattedData((float)$data['variation_3_months']);
        $var_year = getFormattedData((float)$data['variation_year']);
        $return .= '<style>
        .coinmotion_details'.$rand.'{
            background-color: '.$params['background_color'].'; 
            padding: 10px 10px 10px 10px;
            font-family: "Oxygen", sans-serif;
        }
    </style>';
        $return .= "<div class='coinmotion_details".$rand."'>";
        $return .= '<div style="text-align: center; width: 100%"><span style="color: ' . $params['text_color'] . '; font-weight: bold; font-size: 20px;">'. strtoupper($params['currency']) .' ' . $actual_currency['default_currency'] . '</span></div>';
        $return .= '<div style="text-align: center; width: 100%; margin-top: 10px;"><span style="color: ' . $params['text_color'] . '; font-weight: bold; font-size: 26px;">'.(number_format($data['actual_price']*$actual_curr_value, 2, ',', '.')).' '.$actual_currency['default_currency'].'</span></div>';
        $return .= '<div style="text-align: center; width: 100%; height: 10px;"><span style="color: #009ac0; font-size: 50px; line-height: 0px;">. . . . . . . . .</span></div>';
        $color = "black";
        if ((float)str_replace(',', '.', $var_day) > 0.0) {
            $color = "green";
        }
        elseif ((float)str_replace(',', '.', $var_day) < 0.0) {
            $color = "red";
        }
        $return .= '<div style="margin-top: 20px; padding: 5px; height: 35px; background-color: ' . $params['background_color'] . ';">';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 50%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;"><span>1 ' . __('Day', 'coinmotion') . '</span></div>';
        $return .= '<div style="width: 50%; text-align: right; position: relative; float: left; font-size: 14px;"><span style="color: ' . $color . '">' . $var_day . '%</span></div>';
        $return .= '</div>';

        $color = "black";
        if ((float)str_replace(',', '.', $var_week) > 0.0) {
            $color = "green";
        }
        elseif ((float)str_replace(',', '.', $var_week) < 0.0) {
            $color = "red";
        }
        $return .= '<div style="padding: 5px; height: 35px; background-color: #eaeaea;">';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 50%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;"><span>1 ' . __('Week', 'coinmotion') . '</span></div>';
        $return .= '<div style="width: 50%; text-align: right; position: relative; float: left; font-size: 14px;"><span style="color: ' . $color . '">' . $var_week . '%</span></div>';
        $return .= '</div>';

        $color = "black";
        if ((float)str_replace(',', '.', $var_month) > 0.0) {
            $color = "green";
        }
        elseif ((float)str_replace(',', '.', $var_month) < 0.0) {
            $color = "red";
        }
        $return .= '<div style="padding: 5px; height: 35px; background-color: ' . $params['background_color'] . ';">';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 50%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;"><span>1 ' . __('Month', 'coinmotion') . '</span></div>';
        $return .= '<div style="width: 50%; text-align: right; position: relative; float: left; font-size: 14px;"><span style="color: ' . $color . '">' . $var_month . '%</span></div>';
        $return .= '</div>';

        $color = "black";
        if ((float)str_replace(',', '.', $var_3_months) > 0.0) {
            $color = "green";
        }
        elseif ((float)str_replace(',', '.', $var_3_months) < 0.0) {
            $color = "red";
        }
        $return .= '<div style="padding: 5px; height: 35px; background-color: #eaeaea;">';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 50%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;"><span>3 ' . __('Months', 'coinmotion') . '</span></div>';
        $return .= '<div style="width: 50%; text-align: right; position: relative; float: left; font-size: 14px;"><span style="color: ' . $color . '">' . $var_3_months . '%</span></div>';
        $return .= '</div>';

        $color = "black";
        if ((float)str_replace(',', '.', $var_year) > 0.0) {
            $color = "green";
        }
        elseif ((float)str_replace(',', '.', $var_year) < 0.0) {
            $color = "red";
        }
        $return .= '<div style="padding: 5px; height: 35px; background-color: ' . $params['background_color'] . ';">';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 50%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;"><span>1 ' . __('Year', 'coinmotion') . '</span></div>';
        $return .= '<div style="width: 50%; text-align: right; position: relative; float: left; font-size: 14px;"><span style="color: ' . $color . '">' . $var_year . '%</span></div>';
        $return .= '</div>';


        $return .= '<div style="margin: 20px;">';
        $button = new Coinmotion_Affiliate_Button();

        $return .= '</div>';

        if ($params['show_button'] === 'true'){
            $return .= $button->generateButton();
        }

        $return .= '<div style="margin-top: 20px; padding: 5px; height: 35px; background-color: ' . $params['background_color'] . ';">';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 40%; height: 10px; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;"></div>';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 30%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px; font-weight: bold;">' . __('High', 'coinmotion') . '</div>';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 30%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px; font-weight: bold;">' . __('Low', 'coinmotion') . '</div>';
        $return .= '</div>';

        $return .= '<div style="padding: 5px; height: 35px; background-color: #eaeaea;">';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 40%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;">1 ' . __('Day', 'coinmotion') . '</div>';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 30%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;">'.getFormattedData($data['higher_day'],false).'</div>';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 30%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;">'.getFormattedData($data['lower_day'], false).'</div>';
        $return .= '</div>';

        $return .= '<div style="padding: 5px; height: 35px; background-color:  ' . $params['background_color'] . ';">';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 40%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;">1 ' . __('Week', 'coinmotion') . '</div>';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 30%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;">'.getFormattedData($data['higher_week'], false).'</div>';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 30%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;">'.getFormattedData($data['lower_week'], false).'</div>';
        $return .= '</div>';

        $return .= '<div style="padding: 5px; height: 35px; background-color: #eaeaea;">';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 40%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;">1 ' . __('Month', 'coinmotion') . '</div>';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 30%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;">'.getFormattedData($data['higher_month'], false).'</div>';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 30%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;">'.getFormattedData($data['lower_month'], false).'</div>';
        $return .= '</div>';

        $return .= '<div style="padding: 5px; height: 35px;  background-color:  ' . $params['background_color'] . ';">';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 40%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;">3 ' . __('Months', 'coinmotion') . '</div>';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 30%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;">'.getFormattedData($data['higher_3_months'], false).'</div>';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 30%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;">'.getFormattedData($data['lower_3_months'], false).'</div>';
        $return .= '</div>';

        $return .= '<div style="padding: 5px; height: 35px; background-color: #eaeaea; margin-bottom: 20px;">';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 40%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;">1 ' . __('Year', 'coinmotion') . '</div>';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 30%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;">'.getFormattedData($data['higher_year'], false).'</div>';
        $return .= '<div style="color: ' . $params['text_color'] . ';width: 30%; text-align: center; position: relative; float: left; text-transform: uppercase; font-size: 14px;">'.getFormattedData($data['lower_year'], false).'</div>';
        $return .= '</div>';

        $return .= $button->generateCMLink('historical_data');

        $return .= '</div>';

        return $return;
    }

}
?>