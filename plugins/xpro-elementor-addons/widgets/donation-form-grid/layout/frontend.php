<?php
$forms               = ( 'yes' === $settings['all_forms'] ? '' : $settings['form_ids'] );
$columns             = esc_html( $settings['columns'] );
$orderby             = esc_html( $settings['orderby'] );
$order               = esc_html( $settings['order'] );
$exclude             = esc_html( $settings['exclude'] );
$cats                = esc_html( $settings['cats'] );
$tags                = esc_html( $settings['tags'] );
$show_title          = esc_html( $settings['show_title'] );
$show_goal           = esc_html( $settings['show_goal'] );
$show_bar            = esc_html( $settings['show_bar'] );
$show_excerpt        = esc_html( $settings['show_excerpt'] );
$excerpt_length      = esc_html( $settings['excerpt_length'] );
$show_featured_image = esc_html( $settings['show_featured_image'] );
$display_style       = esc_html( $settings['display_style'] );
$show_donate_button  = esc_html( $settings['show_donate_button'] );

$html = do_shortcode(
	'[give_form_grid 
				ids="' . $forms . '" 
				columns="' . $columns . '" 
				order="' . $order . '" 
				exclude="' . $exclude . '" 
				cats="' . $cats . '" 
				tags="' . $tags . '" 
				show_title="' . $show_title . '" 
				show_goal="' . $show_goal . '" 
				show_bar="' . $show_bar . '" 
				show_excerpt="' . $show_excerpt . '" 
				excerpt_length="' . $excerpt_length . '" 
				show_featured_image="' . $show_featured_image . '" 
				show_donate_button="' . $show_donate_button . '" 
				display_style="' . $display_style . '" 
				orderby="' . $orderby . '"]'
);
?>

<div class="xpro-givewp-form-grid-wrapper">
	<?php xpro_elementor_kses( $html ); ?>
</div>
