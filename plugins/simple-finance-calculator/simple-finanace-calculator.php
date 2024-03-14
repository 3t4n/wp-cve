<?php
/*
Plugin Name: Simple Finance Calculator
Plugin URI: http://weissmike.com/simple-finance-calculator-widget
Description: Creates a very simple form that can be used to calculate monthly payments or loan amount based on entered information
Version: 1.0
Author: Mike Weiss
Author URI: http://weissmike.com
License: GPLv2 or later
*/
/*  Copyright 2013  Mike Weiss  (email : mike@weissmike.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class simple_finance_calculator {
    public function __construct()  
    {
        add_shortcode('finance_calculator', array($this, 'calculator_shortcode'));  
		add_action('wp_enqueue_scripts', array($this, 'add_stylesheet'));
    }

    public function add_stylesheet() {
        wp_register_style( 'sfc-style', plugins_url('simple-finance-calculator.css', __FILE__) );
        wp_enqueue_style( 'sfc-style' );
    }
    public function calculator_shortcode($atts) {
    	$output = $this->calculator();
    	
    	return $output;
    }
	/**
	 * Form and result Output
	 */
    public function calculator() {
        $output = '<form class="sfc_calculator" method="post">';
        $err = array();
        $result = '';
        
        $r = '';
        if (isset($_POST['r'])) {
            $r = $_POST['r'];
            if ((!is_float(0 + $r) && !is_int(0 + $r)) || '' == $r) {
                $err['r'] = __('Please enter a valid interest rate', 'sfc');
            }
        }
                
        $n = '';
        if (isset($_POST['n'])) {
            $n = $_POST['n'];
            if (!is_int(0 + $n) || 0 == 0 + $n) {
                $err['n'] = __('Please enter a valid term, in months', 'sfc');
            }
        } 
        
        $a = '';
        if (isset($_POST['a'])) {
            $a = preg_replace('/[\$,]/', '', $_POST['a']);
        }
        
        $payment= '';
        if (isset($_POST['payment'])) {
            $payment= preg_replace('/[\$,]/', '', $_POST['payment']);
        }

        if ((is_numeric($a) || is_numeric($payment))) {
            if ($a && (is_float(0 + $a) || is_int(0 + $a))) {
                // Amount Works.
            } else {
                // Loan amount isn't float. Try payment.
                if ($payment && (is_float(0 + $payment) || is_int(0 + $payment))) {
                    // Payment will work. Clear amount and proceed.
                    $a = '';
                } else {
                    // Both are bad. Set error.
                    $err['ap'] = __('Please enter a valid loan amount or monthly payment', 'sfc');
                }
            } 
        } else if ($r && $n) {
            $err['ap'] = __('Please enter a valid loan amount or monthly payment', 'sfc');
        }
        
        if (count($err) > 0) {
            $output .= '<p class="sfc_validation_error">' . __('There was an error with your submission', 'sfc') . '</p>';
        } else if ($a && ($r || 0 == $r) && $n) {
            // We have a loan amount, calculate monthly payment
            $int = $r/1200; 
            if ($int > 0) {
                $int1 = 1+$int; 
                $r1 = pow($int1, $n); 
                
                $payment = round(($a * ($int*$r1) / ($r1-1)), 2);
            } else {
                $payment = round(($a / $n), 2);
            }
            $result = __('Monthly Payment: $', 'sfc') . number_format($payment, 2);
        } else if($payment && ($r || 0 == $r) && $n) {
            // We have a monthly payment, calculate loan amount
            $int = $r/1200; 

            if ($int > 0) {
                $int1 = 1+$int; 
                $r1 = pow($int1, $n); 
                
                $a = round((($payment * ($r1-1)) / ($int*$r1)), 2);
            } else {
                $a = round(($payment * $n), 2);
            }
            $result = __('Loan Amount: $', 'sfc') . number_format($a, 2);
        }

        $output .= 
            '<div>
                <label>' . __('Interest Rate', 'sfc') . '<br />
                    <input type="text" name="r" value="' . $r . '" />
                </label>' .
                (isset($err['r']) ? '<p class="sfc_error">' . $err['r'] . '</p>' : '') .
            '</div>
            <div>
                <label>' . __('Term, In Months', 'sfc') . '<br />
                    <input type="text" name="n" value="' . $n . '" />
                </label>' .
                (isset($err['n']) ? '<p class="sfc_error">' . $err['n'] . '</p>' : '') .
            '</div>' .
            (isset($err['ap']) ? '<p class="sfc_error">' . $err['ap'] . '</p>' : '') .
            '<div class="anp">
            <div>
                <label>' . __('Loan Amount', 'sfc') . '<br />
                    <input type="text" name="a" value="' . (($a) ? number_format($a, 2) : '') . '" />
                </label>
            </div>
            <div class="separator"><div>' . __('OR', 'sfc') . '</div></div>
            <div>
                <label>' . __('Monthly Payment', 'sfc') . '<br />
                    <input type="text" name="payment" value="' . (($payment) ? number_format($payment, 2) : '') . '" />
                </label>
            </div>
            </div>
            <div>
                <input type="submit" value="Submit" class="wpb_button" />
            </div>' .
            (($result) ? '<p class="sfc_results">' . $result . '</p>' : '') .
        '</form>';
        
        return $output;
    }
}

$calc = new simple_finance_calculator();

/**
 * Adds SFC_Widget widget.
 */
class SFC_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'sfc_widget', // Base ID
			'Simple Finance Calculator', // Name
			array( 'description' => __( 'Simple Finance Calculator', 'sfc' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
        $calc = new simple_finance_calculator();

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		echo $calc->calculator();
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( '', 'sfc' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

}


add_action( 'widgets_init', create_function( '', 'register_widget( "sfc_widget" );' ) );