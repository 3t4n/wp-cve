<?php

// option that contains view and features data of plugin
$woocart = get_option( 'woo_sticky_cart' );

$product = wc_get_product();
$id = $product->get_id();
$price = $product->get_price_html();
$average = $product->get_average_rating();
$review_count = $product->get_review_count();
$type = $product->get_type();
$site_url = get_site_url();
$stock = "";
$cart_text = 'Add To Cart';

if(!isset($woocart["enable"])){
	return;
}
if ( ! $product->managing_stock() && ! $product->is_in_stock() ){
	$stock = 'Out of stock';
}
elseif($product->managing_stock() == true){
	$stock = $product->get_stock_quantity().' in stock';
}

if(isset($woocart["hide_bar_if_outofstock"]) ) {
	if($product->get_stock_quantity() === 0 || !$product->is_in_stock()){
		return;
	}
}

if($woocart["add_cart_text"] != "") {
	$cart_text = $woocart["add_cart_text"];
}

if($type == 'variable'){
    $cart_text = 'Select Options';
    if(isset($woocart["show_range_price_variable"])) {
		$price = $product->get_price_html();
	}
	else{
		$price = "From ".$product->get_variation_price("min");	
	}
} elseif ($type == 'external') {
	$external_url = $product->add_to_cart_url();
	$cart_text = $product->get_button_text();
}

// Custom designing css options
$css = '@media only screen and (min-width: 700px){.wsc-main{display:'.(isset($woocart["desktop"]) ? "inherit" : "none") .';}}';

$css .= '@media only screen and (max-width: 700px){.wsc-main{display:'.(isset($woocart["mobile"]) ? "inherit" : "none") .';}}';

if(!isset($woocart["show_image"])){
	$css .='.wsc-left-section-img{display:none!important;}';
}

if(!isset($woocart["star"])){
	$css .='.wsc-left-sec-star{display:none!important;}';
}

if(!isset($woocart["review"])){
	$css .='.wcs-review-count{display:none!important;}';
}
if (isset($woocart["review_count_color"])) {
	$css .='.wcs-review-count{color:'.$woocart["review_count_color"].';}';
}

if(!isset($woocart["stock"])){
	$css .='.wsc-center-section-stock{display:none!important;}';
}
if (isset($woocart["stock_color"])) {
	$css .='.wsc-center-section-stock{color:'.$woocart["stock_color"].';}';
}

$css .='.wcs-star-rating{color:'.(isset($woocart["star_color"]) ? $woocart["star_color"] : "#000000").';}';

$css .='.wsc-wrapper{'.
$woocart["position"].':0;height:'.($woocart["height"] != "" ? $woocart["height"].'px' : "auto").';'.
(isset($woocart["bg_color"]) && $woocart["bg_color"] != "" ? 'background-color:'.$woocart["bg_color"].';' : "" ).
(isset($woocart["border_color"]) && $woocart["border_color"] != "" ? 'border: 1px solid '.$woocart["border_color"].';' : "" ).
(isset($woocart["border_shadow"]) ? '-webkit-box-shadow: 0 0 20px 0 rgba(0,0,0,0.15); -moz-box-shadow: 0 0 20px 0 rgba(0,0,0,0.15); box-shadow: 0 0 20px 0 rgba(0,0,0,0.15);' : "" ).'}';

$css .='.wsc-cart-button{'.
(isset($woocart["cart_btn_bg"]) && $woocart["cart_btn_bg"] != "" ? 'background-color:'.$woocart["cart_btn_bg"].';' : "" ).
(isset($woocart["btn_text_color"]) && $woocart["btn_text_color"] != "" ? 'color:'.$woocart["btn_text_color"].';' : "" ).
'display: inline-block;}';

$css .='.wsc-cart-button:hover{'.
(isset($woocart["cart_btn_bg_hover"]) && $woocart["cart_btn_bg_hover"] != "" ? 'background-color:'.$woocart["cart_btn_bg_hover"].';' : "" ).
(isset($woocart["btn_text_color_hover"]) && $woocart["btn_text_color_hover"] != "" ? 'color:'.$woocart["btn_text_color_hover"].';' : "" ).
'animation: none;'.'}';

$css .='.wsc-show-option-button{'.
(isset($woocart["cart_btn_bg"]) && $woocart["cart_btn_bg"] != "" ? 'background-color:'.$woocart["cart_btn_bg"].';' : "" ).
(isset($woocart["btn_text_color"]) && $woocart["btn_text_color"] != "" ? 'color:'.$woocart["btn_text_color"].';' : "" ).
'display: inline-block;}';

$css .='.wsc-show-option-button:hover{'.
(isset($woocart["cart_btn_bg_hover"]) && $woocart["cart_btn_bg_hover"] != "" ? 'background-color:'.$woocart["cart_btn_bg_hover"].';' : "" ).
(isset($woocart["btn_text_color_hover"]) && $woocart["btn_text_color_hover"] != "" ? 'color:'.$woocart["btn_text_color_hover"].';' : "" ).
'animation: none;'.'}';

$css .='.wsc-center-section-price{'.
(isset($woocart["price_text_color"]) && $woocart["price_text_color"] != "" ? 'color:'.$woocart["price_text_color"].';' : "" ).'}';

if(! $product->is_in_stock() && isset($woocart["out_stock_color"]) && $woocart["out_stock_color"] != ""){
	$css .='.wsc-center-section-stock{color:'.$woocart["out_stock_color"].';}';
}

if(isset($woocart["product_text_color"]) && $woocart["product_text_color"] != ""){
	$css .='.wsc-left-sec-product{color:'.$woocart["product_text_color"].';}';
}

$css .='.wsc-left-section-img>img{border-radius:'.$woocart["image_shape"].';}'
?>
<div class="wsc-main">
	<div class="wsc-wrapper">
		<div class="wsc-left-div">
			<div class="wsc-left-section-img">
				<?php echo $product->get_image(); ?>
			</div>
			<div class="wsc-left-section-details">
				<div class="wsc-left-sec-product">
					<?php echo $product->get_name(); ?>
				</div>
				<?php if($review_count > '0'){ ?>	
					<div class="wsc-left-sec-star">
						<?php echo '<div class="wcs-star-rating"><span style="width:'.( ( $average / 5 ) * 100 ) . '%"></span></div>';?> 
						<?php echo '<div class="wcs-review-count">('.$review_count.')</div>'; ?>
					</div>
				<?php } ?>
			</div>
		</div>  
		<div class="wsc-center-div">
			<div class="wsc-center-section-price">
				<?php echo "$price"; ?>
			</div>
			<div class="wsc-center-section-stock">
				<?php echo($stock); ?>
			</div>
		</div>
		<div class="wsc-right-div">
			<div class="wsc-right-section-qty">
				<div class="wsc-input-group">
					<div type="button" class="wsc-button-minus" data-field="quantity"> - </div>
					<input type="number" step="1" max="" value="1" name="quantity" class="wsc-quantity-field">
					<div type="button" class="wsc-button-plus" data-field="quantity"> + </div>

				</div>
			</div>
			<div class="wsc-right-section-cart">
				<?php 
					if($type === 'simple' || $type === 'variable' || $type === 'grouped' ) {
						echo '<a class="wsc-cart-button" href="?add-to-cart='.$id.'"> '.$cart_text.' </a>';
					}
					if ($type == 'external') {
						echo '<a class="wsc-cart-button" href="'.$external_url.'"> '.$cart_text.' </a>';
					}
				?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	<?php if($type === 'simple' || $type === 'variable' || $type === 'grouped' ) { ?>
		changeAddCartUrl();
	<?php } ?>

	<?php if(isset($woocart["scroll"])){ ?>
		jQuery(document).ready(function() {
			jQuery('.wsc-wrapper').fadeOut();
		});
		jQuery(document).scroll(function() {
			var y = jQuery(this).scrollTop();
			if (y > <?php echo $woocart["scroll_height"] != "" ? $woocart["scroll_height"] : "100" ?>) {
				jQuery('.wsc-wrapper').fadeIn();
			} else {
				jQuery('.wsc-wrapper').fadeOut();
			}
		});
	<?php } ?>
	jQuery(".wsc-input-group , .wsc-right-section-option ").on("change , click", function(e) {
		changeAddCartUrl();
	});
	function changeAddCartUrl() {
		var wsc_cart_button = jQuery(".wsc-quantity-field").parent().find(".wsc-quantity-field");
		var qt =  wsc_cart_button.val();
		jQuery(".wsc-cart-button").attr("href", "?add-to-cart=<?php echo $id; ?>" +"&quantity=" + qt);
	}
	function incrementValue(e) {
		e.preventDefault();
		var fieldName = jQuery(e.target).data('field');
		var parent = jQuery(e.target).closest('div').parent().closest('div');
		var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

		if (!isNaN(currentVal)) {
			parent.find('input[name=' + fieldName + ']').val(currentVal + 1);
		} else {
			parent.find('input[name=' + fieldName + ']').val(0);
		}
	}

	function decrementValue(e) {
		e.preventDefault();
		var fieldName = jQuery(e.target).data('field');
		var parent = jQuery(e.target).closest('div').parent().closest('div');;
		var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

		if (!isNaN(currentVal) && currentVal > 0) {
			parent.find('input[name=' + fieldName + ']').val(currentVal - 1);
		} else {
			parent.find('input[name=' + fieldName + ']').val(0);
		}
	}

	<?php if($type == 'variable'){ ?>
		jQuery(".wsc-cart-button").on("click", function(e) {
			e.preventDefault();
			jQuery('html, body').animate({
        		scrollTop: jQuery(".variations").offset().top - 70
    		}, 1000);
		});
	<?php } ?>

	jQuery('.wsc-input-group').on('click', '.wsc-button-plus', function(e) {
		incrementValue(e);
	});

	jQuery('.wsc-input-group').on('click', '.wsc-button-minus', function(e) {
		decrementValue(e);
	});


</script>
<style type="text/css">
.wsc-input-group .wsc-button-minus, .wsc-input-group .wsc-button-plus {
	-webkit-appearance:none;
	-webkit-border-radius:0px;
}
.wsc-input-group input[type="number"] {
	-moz-appearance: textfield;
}
	<?php echo $css; ?>
</style>