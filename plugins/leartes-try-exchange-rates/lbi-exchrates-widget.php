<?php
class LBI_Exchange_Rates_Widget extends WP_Widget {
	private $rates;
	public function __construct() {
		//$this->rates = new LBI_Exchange_Rates_Data;
        $this->rates = new LBI_Exchange_Rates;
		$widget_ops = array(
            'classname'   => 'widget_exchrates',
            'description' => __('Gets TRY Exchange Rates from TCMB (Turkish Central Bank)','lbi-exchrates')
    	);
		$control_ops = array('id_base'     => 'lbi_exch_rates');
		parent::__construct('lbi_exch_rates', __('Turkish Lira Exchange Rates','lbi-exchrates'), $widget_ops, $control_ops);
	}


	public function widget( $args, $instance ) {
        extract( $args );
        $title    = apply_filters('widget_title', $instance['title']);

        $defaults = array(
            'title'          => $title,
            'currencies_all' => empty($instance['currencies_all']) ? 'true' : $instance['currencies_all'],
            'currencies'     => empty($instance['currencies']) ? 'USD' : implode(',', $instance['currencies']),
            'caption'        => empty($instance['caption']) ? 'name' : $instance['caption'],
            'captions'       => empty($instance['captions']) ? '': $instance['captions'] ,
            'unit'           => empty($instance['unit']) ? '': $instance['unit'] ,
            'flag'           => empty($instance['flag']) ? 'true' : $instance['flag'],
            'flag_path'      => $instance['flag_path'],
            'fb'             => empty($instance['fb']) ? '' : $instance['fb'],
            'fs'             => empty($instance['fs']) ? 'true' : $instance['fs'],
            'bb'             => empty($instance['bb']) ? '' : $instance['bb'],
            'bs'             => empty($instance['bs']) ? '' : $instance['bs'],
            'cr'             => empty($instance['cr']) ? '' : $instance['cr'],
            'showdate'       => empty($instance['showdate']) ? '' : $instance['showdate'],
            'showsource'     => empty($instance['showsource']) ? '' : $instance['showsource'] ,
            'class'          => $instance['class'],
            'widget'         => 'true'
        );

		echo $before_widget;
		
		if ( $title ) {
			echo $before_title . $title . $after_title; 
		}
		//$xml_data = $this->rates->xml_data();
        echo  $this->rates->shortcode_exchange_rates($defaults);

		echo $after_widget;
	}

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        /* Strip tags for title and name to remove HTML (important for text inputs). */
        $instance['title']          = strip_tags( $new_instance['title'] );
        $instance['currencies_all'] = $new_instance['currencies_all'];
        $instance['currencies']     = $new_instance['currencies'];
        $instance['caption']        = $new_instance['caption'];
        $instance['unit']           = $new_instance['unit'];
        $instance['captions']       = $new_instance['captions'];
        $instance['flag']           = $new_instance['flag'];
        $instance['flag_path']      = strip_tags( $new_instance['flag_path'] );
        $instance['fb']             = $new_instance['fb'];
        $instance['fs']             = $new_instance['fs'];
        $instance['bb']             = $new_instance['bb'];
        $instance['bs']             = $new_instance['bs'];
        $instance['cr']             = $new_instance['cr'];
        $instance['showdate']       = $new_instance['showdate'];
        $instance['showsource']     = $new_instance['showsource'];
        $instance['class']          = strip_tags( $new_instance['class'] );

        return $instance;
    }

	public function form( $instance ) {
	    global $exch_currencies;
        $defaults = array(
            'title'          => '',
            'currencies_all' => 'true',
            'currencies'     => array('USD'),
            'caption'        => 'name',
            'captions'       => '',
            'unit'           => '',
            'flag'           => 'true',
            'flag_path'      => '',
            'fb'             => '',
            'fs'             => 'true',
            'bb'             => '',
            'bs'             => '',
            'cr'             => '',
            'showdate'       => '',
            'showsource'     => '',
            'class'          => 'zebra'
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $currencies_array =  empty($instance['currencies']) ? array('USD') : $instance['currencies'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Widget Title:', 'lbi-exchrates'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" class="widefat xtr-leartes" />
        </p>
 		<p>
			<label for="<?php echo $this->get_field_id('currencies_all'); ?>"><?php _e( 'Currencies:', 'lbi-exchrates' ); ?>&nbsp;</label>
            <span>
                <label for="<?php echo $this->get_field_id('currencies_all_true'); ?>"><input class="widefat" id="<?php echo $this->get_field_id('currencies_all_true'); ?>"  name="<?php echo $this->get_field_name('currencies_all'); ?>" type="radio" value="true"  <?php checked($instance['currencies_all'], 'true'); ?> /> <?php _e('Show all', 'lbi-exchrates'); ?></label>&nbsp;
			    <label for="<?php echo $this->get_field_id('currencies_all_false'); ?>"><input class="widefat" id="<?php echo $this->get_field_id('currencies_all_false'); ?>" name="<?php echo $this->get_field_name('currencies_all'); ?>" type="radio" value="false" <?php checked($instance['currencies_all'], 'false'); ?> /> <?php _e('Selective','lbi-exchrates'); ?></label>
            </span>
		</p>

 		<p>
            <select multiple id="<?php echo $this->get_field_id( 'currencies' ); ?>" name="<?php echo $this->get_field_name( 'currencies' ); ?>[]" class="widefat">
            <?php foreach ($exch_currencies as $code => $currency) { ?>
                <option <?php echo(in_array($code, $currencies_array) ? 'selected': ''); ?> value="<?php echo $code; ?>"><?php echo __($currency, 'lbi-exchrates'); ?></option>
            <?php } ?>
            </select>
		</p>

        <strong><?php _e('Rates', 'lbi-exchrates') ?></strong>
        <p>
            <label><input <?php checked($instance['fb'], 'true'); ?> type="checkbox" id="<?php echo $this->get_field_id( 'fb' ); ?>" name="<?php echo $this->get_field_name( 'fb' ); ?>" value="true" /> <?php _e('Forex Buying', 'lbi-exchrates'); ?></label>
        </p>
        <p>
            <label><input <?php checked($instance['fs'], 'true'); ?> type="checkbox" id="<?php echo $this->get_field_id( 'fs' ); ?>" name="<?php echo $this->get_field_name( 'fs' ); ?>" value="true" /> <?php _e('Forex Selling', 'lbi-exchrates'); ?></label>
        </p>
        <p>
            <label><input <?php checked($instance['bb'], 'true'); ?> type="checkbox" id="<?php echo $this->get_field_id( 'bb' ); ?>" name="<?php echo $this->get_field_name( 'bb' ); ?>" value="true" /> <?php _e('Banknote Buying', 'lbi-exchrates'); ?></label>
        </p>
        <p>
            <label><input <?php checked($instance['bs'], 'true'); ?> type="checkbox" id="<?php echo $this->get_field_id( 'bs' ); ?>" name="<?php echo $this->get_field_name( 'bs' ); ?>" value="true" /> <?php _e('Banknote Selling', 'lbi-exchrates'); ?></label>
        </p>
        <p>
            <label><input <?php checked($instance['cr'], 'true'); ?> type="checkbox" id="<?php echo $this->get_field_id( 'cr' ); ?>" name="<?php echo $this->get_field_name( 'cr' ); ?>" value="true" /> <?php _e('Cross Rate', 'lbi-exchrates'); ?></label>
        </p>
        <br />
 		<p>
			<label for="<?php echo $this->get_field_id('caption'); ?>"><?php _e( 'Curreny Title:', 'lbi-exchrates' ); ?>&nbsp;</label>
            <div>
                <input class="widefat" id="<?php echo $this->get_field_id('caption'); ?>" name="<?php echo $this->get_field_name('caption'); ?>" type="radio" value="code" <?php checked($instance['caption'], 'code'); ?> /> <?php _e('Code', 'lbi-exchrates'); ?> &nbsp;
			    <input class="widefat" id="<?php echo $this->get_field_id('caption'); ?>" name="<?php echo $this->get_field_name('caption'); ?>" type="radio" value="name" <?php checked($instance['caption'], 'name'); ?> /> <?php _e('Name','lbi-exchrates'); ?> &nbsp;
                <input class="widefat" id="<?php echo $this->get_field_id('caption'); ?>" name="<?php echo $this->get_field_name('caption'); ?>" type="radio" value="both" <?php checked($instance['caption'], 'both'); ?> /> <?php _e('Both','lbi-exchrates'); ?>
            </div>
		</p>

        <p>
            <label><input <?php checked($instance['captions'], 'true'); ?> type="checkbox" id="<?php echo $this->get_field_id( 'captions' ); ?>" name="<?php echo $this->get_field_name( 'captions' ); ?>" value="true" /> <?php _e('Show Rate Captions', 'lbi-exchrates'); ?></label>
        </p>

        <p>
            <label><input <?php checked($instance['unit'], 'true'); ?> type="checkbox" id="<?php echo $this->get_field_id( 'unit' ); ?>" name="<?php echo $this->get_field_name( 'unit' ); ?>" value="true" /> <?php _e('Show Currency Unit', 'lbi-exchrates'); ?></label>
        </p>

        <p>
            <label><input <?php checked($instance['flag'], 'true'); ?> type="checkbox" id="<?php echo $this->get_field_id( 'flag' ); ?>" name="<?php echo $this->get_field_name( 'flag' ); ?>" value="true" /> <?php _e('Show Flags', 'lbi-exchrates'); ?></label>
        </p>
        <p id="<?php echo $this->get_field_id( 'flag_path_p' ); ?>">
            <label for="<?php echo $this->get_field_id( 'flag_path' ); ?>"><?php _e('Flag Path:', 'lbi-exchrates'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'flag_path' ); ?>" name="<?php echo $this->get_field_name( 'flag_path' ); ?>" value="<?php echo $instance['flag_path']; ?>" class="widefat" />
            <small><?php _e('Leave empty to use default','lbi-exchrates'); ?></small>
        </p>

        <strong><?php _e('Footer', 'lbi-exchrates') ?></strong>
        <p>
            <label><input <?php checked($instance['showdate'], 'true'); ?> type="checkbox" id="<?php echo $this->get_field_id( 'showdate' ); ?>" name="<?php echo $this->get_field_name( 'showdate' ); ?>" value="true" /> <?php _e('Show Date Announced', 'lbi-exchrates'); ?></label>
        </p>

        <p>
            <label><input <?php checked($instance['showsource'], 'true'); ?> type="checkbox" id="<?php echo $this->get_field_id( 'showsource' ); ?>" name="<?php echo $this->get_field_name( 'showsource' ); ?>" value="true" /> <?php _e('Show Data Source', 'lbi-exchrates'); ?></label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'class' ); ?>"><?php _e('Custom CSS Class:', 'lbi-exchrates'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'class' ); ?>" name="<?php echo $this->get_field_name( 'class' ); ?>" value="<?php echo $instance['class']; ?>" style="width:100%;" class="widefat" />
        </p>
        <?php
	}
}
?>