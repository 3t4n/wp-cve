<?php
  /**
   * Plugin Name: ConvertCalculator
   * Plugin URI: https://www.convertcalculator.com/platforms/wordpress
   * Description: Easily embed ConvertCalculator calculators' on your WordPress website
   * Version: 1.1.1
   * Author: ConvertCalculator
   * Author URI: http://www.convertcalculator.com
   */

  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

  function convertcalculator_add_calculator($calculator_id, $type) {
    echo convertcalculator_embed_calculator(array('id' => $calculator_id, 'type' => $type));
  }

  function convertcalculator_embed_calculator( $atts ){
    if (!isset($atts['id'])) {
      return '<div>You need to add an "id" to the "convertcalculator" shortcode. You can find the calculator id in the <a href="https://app.convertcalculator.co">editor</a>.';
    }

    $type = isset($atts['type']) ? $atts['type'] : "framed";

    return '<div class="calculator" data-calc-id="' . $atts['id'] .'" data-type="' . $type . '"></div><script src="https://scripts.convertcalculator.com/embed.js" async="true"></script>';
  }

  add_shortcode( 'convertcalculator', 'convertcalculator_embed_calculator' );
?>