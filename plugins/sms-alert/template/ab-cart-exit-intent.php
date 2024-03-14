<?php
/**
 * Abandoned cart template.
  * PHP version 5
 *
 * @category Template
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 */
use Elementor\Frontend;
if (! defined('ABSPATH') ) {
    exit;
}

 $post = get_page_by_path( 'exitintent_style', OBJECT, 'sms-alert' );
 if ( is_plugin_active('elementor/elementor.php') && !empty($post)) {  
	$post_id= $post->ID;	
     $frontent = new Frontend();
    $content =  $frontent->get_builder_content($post_id);	
}  
  else{
	$content = SAPopup::getExitIntentStyle();
	
}
?>
<div id="cart-exit-intent-form" class="<?php echo esc_attr($this->exit_intent_type()); ?>" style="background-color: rgba(0,0,0,0.5);">
    <div id="cart-exit-intent-form-container" style="background-color:<?php echo esc_attr($args['main_color']); ?>">
        <?php
        $kses_defaults = wp_kses_allowed_html('post');

        $svg_args = array(
                'svg'  => true,
                'line' => array(
                    'x1'           => true,
                    'y1'           => true,
                    'x2'           => true,
                    'y2'           => true,
                    'stroke'       => true,
                    'stroke-width' => true,
                ),
        );

        $allowed_tags = array_merge($kses_defaults, $svg_args);
        ?>
        
        <div id="cart-exit-intent-close">
        <?php
        echo wp_kses(sprintf('<svg><line x1="1" y1="11" x2="11" y2="1" stroke="%s" stroke-width="2"/><line x1="1" y1="1" x2="11" y2="11" stroke="%s" stroke-width="2"/></svg>', esc_attr($args['inverse_color']), esc_attr($args['inverse_color'])), $allowed_tags);
        ?>
        </div>
        <div id="cart-exit-intent-form-content">
		<?php
		 echo $content;
		?>
        </div>
    </div>
    <div id="cart-exit-intent-form-backdrop" style="background-color:<?php echo esc_attr($args['inverse_color']); ?>; opacity: 0;"></div>
</div>