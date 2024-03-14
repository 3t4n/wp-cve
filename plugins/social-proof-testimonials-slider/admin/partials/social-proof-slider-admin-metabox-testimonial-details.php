<?php

/**
 * Provide the view for a metabox
 *
 * @link       https://thebrandiD.com
 * @since      2.0.0
 *
 * @package    Social_Proof_Slider
 * @subpackage Social_Proof_Slider/admin/partials
 */

wp_nonce_field( $this->plugin_name, 'testimonial_details' );

$atts 					= array();
$atts['description'] 	= '';
$atts['id'] 			= 'socialproofslider_testimonial_author_name';
$atts['label'] 			= 'Author Name';
$atts['settings']['textarea_name'] = 'socialproofslider_testimonial_author_name';
//$settings = array( 'editor_height' => 100 )
$atts['settings']['editor_height'] = 100;
$atts['value'] 			= '';

$socialproofslider_testimonial_author_name = get_post_meta( $post->ID, 'socialproofslider_testimonial_author_name', true );

if ( ! empty( $this->meta[$atts['id']][0] ) ) {

	$atts['value'] = $this->meta[$atts['id']][0];

} else{

	$atts['value'] = $socialproofslider_testimonial_author_name;

}

apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

?>
<section>
	<?php include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-editor.php' ); ?>
</section>
<?php


$atts 					= array();
$atts['description'] 	= '';
$atts['id'] 			= 'socialproofslider_testimonial_author_title';
$atts['label'] 			= 'Author Title';
$atts['settings']['textarea_name'] = 'socialproofslider_testimonial_author_title';
$atts['settings']['editor_height'] = 100;
$atts['value'] 			= '';

$socialproofslider_testimonial_author_title = get_post_meta( $post->ID, 'socialproofslider_testimonial_author_title', true );

if ( ! empty( $this->meta[$atts['id']][0] ) ) {

	$atts['value'] = $this->meta[$atts['id']][0];

} else{

	$atts['value'] = $socialproofslider_testimonial_author_title;

}

apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

?><section><?php

include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-editor.php' );

?></section>
<?php


$atts 					= array();
$atts['description'] 	= '';
$atts['id'] 			= 'socialproofslider_testimonial_text';
$atts['label'] 			= 'Testimonial Text';
$atts['settings']['textarea_name'] = 'socialproofslider_testimonial_text';
$atts['settings']['editor_height'] = 100;
$atts['value'] 			= '';

$socialproofslider_testimonial_text = get_post_meta($post->ID, 'socialproofslider_testimonial_text', true);

if ( ! empty( $this->meta[$atts['id']][0] ) ) {

	$atts['value'] = $this->meta[$atts['id']][0];

} else{

	$atts['value'] = $socialproofslider_testimonial_text;

}

apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

?><section><?php

include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-editor.php' );

?></section>
<?php
