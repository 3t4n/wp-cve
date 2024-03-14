<?php
/*
Plugin Name: Zakah Calculator
Plugin URI: https://www.islam.com.kw/
Description: It is a simple and easy way to know how to fulfill the obligation of Zakah. Just enter the amount of money and you will get the amount you should give out.
Version: 1.5
Author: EDC Team (E-Da`wah Committee)
Author URI: https://www.islam.com.kw/
*/

class Zakah_Calculator extends WP_Widget {
	private $plugin_base_id = 'zakah_calculator_widget';
	private $plugin_domain = 'zakah_calculator_domain';
	private $plugin_title = 'Zakah Calculator';
	private $plugin_description = 'Add Zakah Calculator in sidebar.';

	function __construct() {
		parent::__construct($this->plugin_base_id, __($this->plugin_title, $this->plugin_domain), array('description' => __($this->plugin_description, $this->plugin_domain)));
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$type = $instance['type'];

		$text_your_amount = ( !empty($instance['text_your_amount']) ) ? strip_tags( $instance['text_your_amount'] ) : 'Your Amount';
		$text_your_zakah = ( !empty($instance['text_your_zakah']) ) ? strip_tags( $instance['text_your_zakah'] ) : 'Your Zakah';
		$text_calculate = ( !empty($instance['text_calculate']) ) ? strip_tags( $instance['text_calculate'] ) : 'Calculate';
		$text_reset = ( !empty($instance['text_reset']) ) ? strip_tags( $instance['text_reset'] ) : 'Reset';

		wp_enqueue_script('zakah-js', plugin_dir_url( __FILE__ ).'js/zakah.js', array ( 'jquery' ), 1.0, false);
		wp_enqueue_style('zakah-css', plugin_dir_url( __FILE__ ).'css/style.css',false,'1.0','all');

		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		?>
		<script type="text/javascript">
			function zakah_print(zakah){
			var div_id = document.getElementById("zakah_result");
			if(zakah == 0){
				div_id.innerHTML = '';
			}else{
				div_id.innerHTML = zakah+' <?php echo __($text_your_zakah, $this->plugin_domain); ?>';
			}
			}
		</script>
		<?php
		echo '<form action="" name="calculate_zakah" id="calculate_zakah">';

		echo '<div class="zakah_input">';
		echo '<label for="amount"><span class="your_amount_text">'. __($text_your_amount, $this->plugin_domain) .'</span></label>';
		echo '<input type="number" name="amount" id="amount" value="0" onfocus="zakah_blankfield(amount)" onBlur="zakah_check_empty(amount)" />';
		echo '</div>';

		echo '<input type="hidden" name="total_amount" onfocus="zakah_lostfocus(total_amount)" />';

		if($type == 1){
			echo '<div class="zakah_input">';
			echo '<label for="zakah"><span class="your_zakah_text">'. __($text_your_zakah, $this->plugin_domain) .'</span></label>';
			echo '<input type="number" name="zakah" id="zakah" onfocus="zakah_lostfocus(zakah)" />';
			echo '</div>';
			echo '<div id="zakah_result" style="display:none;"></div>';
		}else{
			echo '<div class="zakah_input" style="display:none;">';
			echo '<label for="zakah"><span class="your_zakah_text">'. __($text_your_zakah, $this->plugin_domain) .'</span></label>';
			echo '<input type="number" name="zakah" id="zakah" onfocus="zakah_lostfocus(zakah)" />';
			echo '</div>';
			echo '<div id="zakah_result"></div>';
		}

		echo '<div class="zakah_input">';
		echo '<input type="reset" name="reset" onclick="reset_zakah_print()" value="'. __($text_reset, $this->plugin_domain) .'"> <input name="calculate" type="button" id="calculate" value="'. __($text_calculate, $this->plugin_domain) .'">';
		echo '</div>';

		echo '</form>';

		echo $args['after_widget'];
	}

	public function form( $instance ) {
		if(isset($instance[ 'title' ])){
			$title = esc_attr($instance['title']);
			$type = intval($instance['type']);
			$text_your_amount = esc_attr($instance['text_your_amount']);
			$text_your_zakah = esc_attr($instance['text_your_zakah']);
			$text_calculate = esc_attr($instance['text_calculate']);
			$text_reset = esc_attr($instance['text_reset']);
		}else{
			$title = __('Zakah Calculator', $this->plugin_domain);
			$type = 1;
			$text_your_amount = '';
			$text_your_zakah = '';
			$text_calculate = '';
			$text_reset = '';
		}

		$inputs = '<p>';
		$inputs .= '<label for="'.$this->get_field_id( 'title' ).'">'. __('Title:', $this->plugin_domain) .'</label>';
		$inputs .= '<input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'" />';
		$inputs .= '</p>';

		$inputs .= '<p>';
		$inputs .= '<label for="'.$this->get_field_id('type').'">';
		if($type){ $type_checked = ' checked="checked"'; }else{ $type_checked = ''; }
		$inputs .= '<input id="'.$this->get_field_id('type').'" name="'.$this->get_field_name('type').'" type="checkbox"'.$type_checked.' /> ';
		$inputs .= __('Result by input', $this->plugin_domain);
		$inputs .= '</label>';
		$inputs .= '</p>';

		$inputs .= '<p>';
		$inputs .= '<label for="'.$this->get_field_id( 'text_your_amount' ).'">'. __('Your Amount text:', $this->plugin_domain) .'</label>';
		$inputs .= '<input class="widefat" id="'.$this->get_field_id('text_your_amount').'" name="'.$this->get_field_name('text_your_amount').'" type="text" value="'.$text_your_amount.'" />';
		$inputs .= '</p>';

		$inputs .= '<p>';
		$inputs .= '<label for="'.$this->get_field_id( 'text_your_zakah' ).'">'. __('Your Zakah text:', $this->plugin_domain) .'</label>';
		$inputs .= '<input class="widefat" id="'.$this->get_field_id('text_your_zakah').'" name="'.$this->get_field_name('text_your_zakah').'" type="text" value="'.$text_your_zakah.'" />';
		$inputs .= '</p>';

		$inputs .= '<p>';
		$inputs .= '<label for="'.$this->get_field_id( 'text_calculate' ).'">'. __('Calculate text:', $this->plugin_domain) .'</label>';
		$inputs .= '<input class="widefat" id="'.$this->get_field_id('text_calculate').'" name="'.$this->get_field_name('text_calculate').'" type="text" value="'.$text_calculate.'" />';
		$inputs .= '</p>';

		$inputs .= '<p>';
		$inputs .= '<label for="'.$this->get_field_id( 'text_reset' ).'">'. __('Reset text:', $this->plugin_domain) .'</label>';
		$inputs .= '<input class="widefat" id="'.$this->get_field_id('text_reset').'" name="'.$this->get_field_name('text_reset').'" type="text" value="'.$text_reset.'" />';
		$inputs .= '</p>';

		echo $inputs;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['type'] = ( isset( $new_instance['type'] ) ? 1 : 0 );
		$instance['text_your_amount'] = ( ! empty( $new_instance['text_your_amount'] ) ) ? strip_tags( $new_instance['text_your_amount'] ) : '';
		$instance['text_your_zakah'] = ( ! empty( $new_instance['text_your_zakah'] ) ) ? strip_tags( $new_instance['text_your_zakah'] ) : '';
		$instance['text_calculate'] = ( ! empty( $new_instance['text_calculate'] ) ) ? strip_tags( $new_instance['text_calculate'] ) : '';
		$instance['text_reset'] = ( ! empty( $new_instance['text_reset'] ) ) ? strip_tags( $new_instance['text_reset'] ) : '';
		return $instance;
	}
}

function zakah_calculator_load() {
	register_widget( 'Zakah_Calculator' );
}
add_action( 'widgets_init', 'zakah_calculator_load' );
