<?php 
$class = "$id product-page " . $product->get_type();
if ($options['displayImage'] == 'yes') {
    $class .= " display-image";
}
if ($options['displayName'] == 'yes') {
    $class .= " display-name";
}
if ($options['displayRating'] == 'yes') {
    $class .= " display-rating";
}
if ($options['displayPrice'] == 'yes') {
    $class .= " display-price";
}
if ($options['displayQuantity'] == 'yes') {
    $class .= " display-quantity";
}
if ($options['displayButton'] == 'yes') {
    $class .= " display-button";
}
?>

<div class="<?php echo $class; ?>">
	<div class="<?php echo $id . '-container';?>">
<?php
if ($options['displayImage'] == 'yes') {
    $image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'single-post-thumbnail');
?>
	<section class="image">
		<span><img src="<?php echo $image[0]?>" alt="<?php echo $product->get_name(); ?>"></span>
    </section>
<?php 
}
if ($options['displayName'] == 'yes') {
?>
    <section class="name">
        <span><?php echo $product->get_name(); ?></span>
    </section>
<?php 
}
if ($options['displayRating'] == 'yes') {
?>
    <section class="rating">
	    <div class="rateyo" data-rateyo-read-only="true" data-rateyo-num-stars="5" data-rateyo-rating="<?php echo $product->get_average_rating() / 5 * 100 ; ?>%"></div> 
	</section>
<?php 
}
if ($options['displayPrice'] == 'yes') {
?>
    <section class="price">
<?php
	if ($options['displayPriceRange'] == 'yes' || $product->get_type() != 'variable') { 
			echo $product->get_price_html(); 
	}
?>
    </section>
<?php 
}
if ($options['displayQuantity'] == 'yes') {
    ?>
        <section class="quantity">
            <input class="input-text qty text" step="1" min="<?php echo $product->get_min_purchase_quantity() > 0 ? $product->get_min_purchase_quantity() : ""; ?>" max="<?php echo $product->get_max_purchase_quantity() > 0 ? $product->get_max_purchase_quantity() : ""; ?>" name="quantity" value="1" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric" aria-labelledby="" type="number"/>
        </section>
    <?php 
}    
if ($options['displayButton'] == 'yes') {
   if ($isInStock) {
?>	
    <section class="button">
        <a href="#" class="action-button">
            <?php echo $product->single_add_to_cart_text(); ?>
        </a>
    </section>
<?php			
	} else {
?>	
    <section class="button outofstock">
	    <span><?php echo _e($options['textOutOfStock'], $id); ?></span>
	</section>
<?php 
    }
}
?>
	</div>
</div>