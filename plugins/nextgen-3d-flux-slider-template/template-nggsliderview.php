<?php 
/**
Slider integration

Follow variables are useable :

	$gallery     : Contain all about the gallery
	$images      : Contain all images, path, title
	$pagination  : Contain the pagination content

 You can check the content when you insert the tag <?php var_dump($variable) ?>
 If you would like to show the timestamp of the image ,you can use <?php echo $exif['created_timestamp'] ?>
**/

?>
<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?><?php if (!empty ($gallery)) : ?>
<div id="<?php echo $gallery->anchor ?>" class="3dfluxslider fluxslider" width="100%" style="overflow:hidden; text-align:center">
	<?php foreach ($images as $image) : ?>	
         <img src="<?php echo $image->imageURL ?>" alt="<?php echo $image->alttext ?>" width="<?=get_option('ng_3dfluxslider_image_width')?>" title="<?php echo $image->alttext ?>" />
 	<?php endforeach; ?>
</div>


<script type="text/javascript" defer="defer">
    jQuery(document).ready(function($) {

        window.f = new flux.slider('#<?php echo $gallery->anchor ?>', {

            <?php  
                $delay = get_option('ng_3dfluxslider_delay')*1000;
                if($delay<1000)                    $delay = 4000;
                if(get_option('ng_3dfluxslider_pagination')) 
                   echo 'pagination:true,';
                else
                    echo 'pagination:false,';
                if(get_option('ng_3dfluxslider_controls')) 
                    echo 'controls:true,';
                else
                    echo 'controls:false,';
                if(get_option('ng_3dfluxslider_caption')) 
                    echo 'captions:true,';
                else
                    echo 'captions:false,';
                $transitions = get_option('ng_3dfluxslider_transitions');
                if(!empty($transitions))
                    echo 'transitions: '. json_encode($transitions).',';
                echo 'delay: '.$delay;
            ?>
                
	});

    });	
</script>
<?php endif; ?>