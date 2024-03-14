<?php 
use Adminz\Helper\ADMINZ_Helper_Flatsome_Shortcodes;

$flatsome_accordion_icon = new ADMINZ_Helper_Flatsome_Shortcodes;
$flatsome_accordion_icon->shortcode_name = 'adminz_accordion_icons';
$flatsome_accordion_icon->shortcode_title = 'Accordion Icon';
$flatsome_accordion_icon->shortcode_icon = 'accordion'; 
$flatsome_accordion_icon->options = [
	'options'=>[
		'type' => 'group',
		'heading' => __( 'Options','administrator-z' ),
		'description'=> 'Set accordions Element a Class name and copy it here',
    	'options' => [
    		'accordion_class' => array(
		        'type' => 'textfield',
		        'heading' => 'Accordion Element class',
		        'default' => '',
		    ),
			'ids' => array(
				'type' => 'gallery',
				'heading' => 'Icons',
			),
    	]
	]
	
];
$flatsome_accordion_icon->shortcode_callback = function($atts){
	extract( 
		shortcode_atts( 
			[
				'accordion_class'=>'',
				'ids'=>'',
			],
			$atts 
		) 
	);
	ob_start();
	?>
	<style type="text/css">
		.<?php echo esc_attr($accordion_class); ?> .accordion-item>a>span{
			display: inline-flex;
			gap: 0.5em;
		    align-items: center;
			justify-content: center;
		}
		.<?php echo esc_attr($accordion_class); ?> .accordion-item>a>span:before{
		    content: "";
		    width: 2em;
		    height: 2em;
		    background-size: contain;
		    background-repeat: no-repeat;
		    background-position: center center;
		}
		<?php
			$ids = explode(',',$ids);
			if(!empty($ids) and is_array($ids)){
				foreach ($ids as $key => $id) {
					?>
					.<?php echo esc_attr($accordion_class); ?> .accordion-item:nth-child(<?php echo esc_attr($key+1); ?>)>a>span:before{
						background-image: url("<?php echo wp_get_attachment_image_url( $id, 'full', false ); ?>");
					}
					<?php
				}
			}
		?>
	</style>
	<?php
	return apply_filters('adminz_output_debug',ob_get_clean());
};
$flatsome_accordion_icon->general_element();