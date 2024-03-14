<?php

$classes = apply_filters( 'img_slider_extra_classes', 'img-slider image-slider', $data->settings );
$items_attributes = apply_filters( 'img_slider_items_attributes', array(),$data->settings );

?>




<div id="<?php echo esc_attr($data->gallery_id) ?>" class="<?php echo esc_attr($classes); ?> <?php echo ( $data->settings['align'] != '' ) ? esc_attr( 'align' . $data->settings['align'] ) : ''; ?>" style="margin: 50px auto;" data-config="<?php echo esc_attr( json_encode( $data->js_config ) ) ?>">

<?php


$layout = $data->settings['designName'];

require "design/$layout/index.php";

?>
</div>





	

