<?php

$classes = apply_filters( 'photo_gallery_extra_classes', 'photo-gallery-wp photo-gallery', $data->settings );
$items_attributes = apply_filters( 'photo_gallery_items_attributes', array(),$data->settings );

?>


<div id="<?php echo esc_attr($data->gallery_id) ?>" class="<?php echo esc_attr($classes); ?>" style="padding:0px 15px 0px 0px;" data-config="<?php echo esc_attr( json_encode( $data->js_config ) ) ?>">

<?php

$layout = $data->settings['layout'];

require "design/$layout/index.php";
?>
</div>
<?php 
return;

?>





	

