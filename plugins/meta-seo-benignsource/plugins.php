<div class="wrap">
<style >

.bs_product_box {
    background: #f2f2f2;
    border: 1px solid #CACACA;
    float: left;
    margin: 10px 10px 0 0;
    width: 320px;
    border-radius: 3px;

    }
.bs_product_image {
    padding: 20px;
}
.bs_product_content, .bws_product_image {
    float: left;
}
.bs_product_image img {
    height: 60px;
}
.bs_product_content {
    max-width: 200px;
    padding: 15px 0 7px;
}
.bs_product_content, .bs_product_image {
    float: left;
}
.bs_product_title {
    font-size: 14px;
    line-height: initial;
    font-weight: bolder;
	text-decoration:none;
	color:#333333;
}
.bs-version {
    margin-top: 6px;
}
.bs_product_description {
    font-size: 13px;
    line-height: 18px;
    margin: 6px 0 2px;
    color: #666;
}
.bs_product_links {
    margin: 15px 0;
}
.bs_product_content a.bs_upgrade_button, .bs_product_content a.bs_upgrade_button:hover {
    background: #dd6930;
    border: 1px solid #c16436;
    color: #fff;
    margin-right: 12px;
}	
    </style>
<div id="poststuff">
<div id="post-body" class="metabox-holder columns-2">
<div id="post-body-content">
<div id="normal-sortables" class="meta-box-sortables ui-sortable">                        
<div class="postbox" style="float:left; padding:15px; width:100%;">
<div style="padding:10px; margin-top:20px; margin-bottom:20px; padding-right:5px; width:99%;border-bottom: 4px #e96656 solid; background-color: #433a3b; float:left;">
<div style="width:30%; float:left;"><h2 style=" color:#FFFFFF; font-size:18px;">BenignSource Plugins</h2></div>
<div style="width:150px; padding:10px; float: right;color:#FFFFFF;"><a style="color:#FFFFFF; text-decoration:none; font-size:14px;" href="http://www.benignsource.com" target="_blank">Home</a>&nbsp;|&nbsp;<a style="color:#FFFFFF; text-decoration:none; font-size:14px;" href="http://www.benignsource.com/forums/" target="_blank">Support</a></div>
</div>
<div style="width:100%; float:left; padding:20px;">
<div style="width:97%; text-align:center; float:left;"><h2 style=" color:#666; font-size:14px;">BenignSource Dashboard</h2></div>
<div style="width:98%; float:left;"><h2 style="border-bottom: 4px #00CC00 solid; color:#333; font-size:18px;">Active Plugins</h2></div>
<?php
if ( is_plugin_active( 'meta-seo-benignsource/meta-seobenignsource.php' ) ) {
  //plugin is activated
?>
<div class="bs_product_box" style="height: 120px; width:300px;">
<div style=" width:100px; float:left;">
<div class="bs_product_image">
<a href="https://www.benignsource.com/product/meta-seo-benignsource-for-woocommerce-wordpress/" target="_blank" class="bs_product_title">
<?php echo '<img src="' . esc_attr( plugins_url( 'img/ms_bsicon-128x128.jpg', __FILE__ ) ) . '" alt="Meta SEO BenignSource" border="0px"> ';?></a></div>
</div>
<div class="bs_product_content">
<div class="bs_product_title"><a href="https://www.benignsource.com/product/meta-seo-benignsource-for-woocommerce-wordpress/" target="_blank" class="bs_product_title">Meta SEO BenignSource</a></div>
<div class="bs-version">

<?php
if ( is_plugin_active( 'meta-seo-benignsource/meta-seobenignsource.php' ) ) {
  //plugin is activated
?>
<span style="color:#dd6930;">Plugin Active</span>
<?php
} else {?>

<?php }?>
</div>

<div class="bs_product_links">
<?php 
if ( is_plugin_active( 'meta-seo-benignsource/meta-seobenignsource.php' ) ) {
  //plugin is activated
?>
<a class="button button-secondary bs_upgrade_button" href="options-general.php?page=meta-seobenignsource.php">Settings</a>
<?php
} else {?>
<?php }?></div></div>
<div class="clear"></div></div>
<?php
} else {}?>
<!--end meta-->
<!--start protect-->
<?php
if ( is_plugin_active( 'protect-benignsource/protectbs.php' ) ) {
  //plugin is activated
?>
<div class="bs_product_box" style="height: 120px; width:300px;">
<div style=" width:100px; float:left;">
<div class="bs_product_image">
<a href="https://www.benignsource.com/product/protect-benignsource/" class="bs_product_title">
<?php echo '<img src="' . esc_attr( plugins_url( 'img/p_bsicon-128x128.jpg', __FILE__ ) ) . '" alt="Protect BenignSource" border="0px"> ';?></a></div>
</div>
<div class="bs_product_content">
<div class="bs_product_title"><a href="https://www.benignsource.com/product/protect-benignsource/" target="_blank" class="bs_product_title">Protect BenignSource</a></div>
<div class="bs-version">

<?php
if ( is_plugin_active( 'protect-benignsource/protectbs.php' ) ) {
  //plugin is activated
?>
<span style="color:#dd6930;">Plugin Active</span>
<?php
} else {?>

<?php }?>
</div>

<div class="bs_product_links">
<?php 
if ( is_plugin_active( 'protect-benignsource/protectbs.php' ) ) {
  //plugin is activated
?>
<a class="button button-secondary bs_upgrade_button" href="options-general.php?page=protect-benignsource">Settings</a>
<?php
} else {?>
<?php }?></div></div>
<div class="clear"></div></div>
<?php
} else {}?>

<!--end protect-->
<?php
if ( is_plugin_active( 'seo-converter-benignsource/seo-converter-benignsource.php' ) ) {
  //plugin is activated
?>
<div class="bs_product_box" style="height: 120px; width:300px;">
<div style=" width:100px; float:left;">
<div class="bs_product_image">
<a href="https://www.benignsource.com/product/seo-converter-benignsource/" target="_blank" class="bs_product_title">
<?php echo '<img src="' . esc_attr( plugins_url( 'img/sc_bsicon-128x128.jpg', __FILE__ ) ) . '" alt="Seo Converter BenignSource" border="0px"> ';?></a></div>
</div>
<div class="bs_product_content">
<div class="bs_product_title"><a href="https://www.benignsource.com/product/seo-converter-benignsource/" target="_blank" class="bs_product_title">Seo Converter BenignSource</a></div>
<div class="bs-version">

<?php
if ( is_plugin_active( 'seo-converter-benignsource/seo-converter-benignsource.php' ) ) {
  //plugin is activated
?>
<span style="color:#dd6930;">Plugin Active</span>
<?php
} else {?>

<?php }?>
</div>

<div class="bs_product_links">
<?php 
if ( is_plugin_active( 'seo-converter-benignsource/seo-converter-benignsource.php' ) ) {
  //plugin is activated
?>
<a class="button button-secondary bs_upgrade_button" href="options-general.php?page=seo-converter-benignsource">Settings</a>
<?php
} else {?>
<?php }?></div></div>
<div class="clear"></div></div>
<?php
} else {}?>
<!--end convert-->
<?php
if ( is_plugin_active( 'woo-product-design-benignsource/woo-product-design-benignsource.php' ) ) {
  //plugin is activated
?>
<div class="bs_product_box" style="height: 120px; width:300px;">
<div style=" width:100px; float:left;">
<div class="bs_product_image">
<a href="https://www.benignsource.com/product/woo-product-design-benignsource/"  target="_blank"class="bs_product_title">
<?php echo '<img src="' . esc_attr( plugins_url( 'img/wpd_bsicon-128x128.jpg', __FILE__ ) ) . '" alt="Woo Product Design BenignSource" border="0px"> ';?></a></div>
</div>
<div class="bs_product_content">
<div class="bs_product_title"><a href="https://www.benignsource.com/product/woo-product-design-benignsource/" target="_blank" class="bs_product_title">Woo Product Design BenignSource</a></div>
<div class="bs-version">

<?php
if ( is_plugin_active( 'woo-product-design-benignsource/woo-product-design-benignsource.php' ) ) {
  //plugin is activated
?>
<span style="color:#dd6930;">Plugin Active</span>
<?php
} else {?>

<?php }?>
</div>

<div class="bs_product_links">
<?php 
if ( is_plugin_active( 'woo-product-design-benignsource/woo-product-design-benignsource.php' ) ) {
  //plugin is activated
?>
<a class="button button-secondary bs_upgrade_button" href="edit.php?post_type=product">Start Design</a>
<?php
} else {?>
<?php }?></div></div>
<div class="clear"></div></div>
<?php
} else {}?>
<!--end design-->
<?php
if ( is_plugin_active( 'cart-promotion-benignsource/cart-promotion-benignsource.php' ) ) {
  //plugin is activated
?>
<div class="bs_product_box" style="height: 120px; width:300px;">
<div style=" width:100px; float:left;">
<div class="bs_product_image">
<a href="https://www.benignsource.com/product/cart-promotion-benignsource/" target="_blank" class="bs_product_title">
<?php echo '<img src="' . esc_attr( plugins_url( 'img/cp_bsicon-128x128.jpg', __FILE__ ) ) . '" alt="Cart Promotion BenignSource" border="0px"> ';?></a></div>
</div>
<div class="bs_product_content">
<div class="bs_product_title"><a href="https://www.benignsource.com/product/cart-promotion-benignsource/" target="_blank" class="bs_product_title">Cart Promotion BenignSource</a></div>
<div class="bs-version">

<?php
if ( is_plugin_active( 'cart-promotion-benignsource/cart-promotion-benignsource.php' ) ) {
  //plugin is activated
?>
<span style="color:#dd6930;">Plugin Active</span>
<?php
} else {?>

<?php }?>
</div>

<div class="bs_product_links">
<?php 
if ( is_plugin_active( 'cart-promotion-benignsource/cart-promotion-benignsource.php' ) ) {
  //plugin is activated
?>
<a class="button button-secondary bs_upgrade_button" href="edit.php?post_type=product">Edit Products</a>
<?php
} else {?>
<?php }?></div></div>
<div class="clear"></div></div>
<?php
} else {}?>
<!--end cart-->
<?php
if ( is_plugin_active( 'specification-benignsource/specification-benignsource.php' ) ) {
  //plugin is activated
?>
<div class="bs_product_box" style="height: 120px; width:300px;">
<div style=" width:100px; float:left;">
<div class="bs_product_image">
<a href="https://www.benignsource.com/product/specification-benignsource/" target="_blank" class="bs_product_title">
<?php echo '<img src="' . esc_attr( plugins_url( 'img/s_bsicon-128x128.jpg', __FILE__ ) ) . '" alt="Specification BenignSource" border="0px"> ';?></a></div>
</div>
<div class="bs_product_content">
<div class="bs_product_title"><a href="https://www.benignsource.com/product/specification-benignsource/" target="_blank" class="bs_product_title">Specification BenignSource</a></div>
<div class="bs-version">

<?php
if ( is_plugin_active( 'specification-benignsource/specification-benignsource.php' ) ) {
  //plugin is activated
?>
<span style="color:#dd6930;">Plugin Active</span>
<?php
} else {?>

<?php }?>
</div>

<div class="bs_product_links">
<?php 
if ( is_plugin_active( 'specification-benignsource/specification-benignsource.php' ) ) {
  //plugin is activated
?>
<a class="button button-secondary bs_upgrade_button" href="edit.php?post_type=product">Edit Products</a>
<?php
} else {?>
<?php }?></div></div>
<div class="clear"></div></div>
<?php
} else {}?>
<!--end spefi-->
<?php
if ( is_plugin_active( 'loyal-customer-benignsource/loyal-customer-benignsource.php' ) ) {
  //plugin is activated
?>
<div class="bs_product_box" style="height: 120px; width:300px;">
<div style=" width:100px; float:left;">
<div class="bs_product_image">
<a href="https://www.benignsource.com/product/loyal-customer-benignsource/" target="_blank" class="bs_product_title">
<?php echo '<img src="' . esc_attr( plugins_url( 'img/lc_bsicon-128x128.jpg', __FILE__ ) ) . '" alt="Loyal Customer BenignSource" border="0px"> ';?></a></div>
</div>
<div class="bs_product_content">
<div class="bs_product_title"><a href="https://www.benignsource.com/product/loyal-customer-benignsource/" target="_blank" class="bs_product_title">Loyal Customer BenignSource</a></div>
<div class="bs-version">

<?php
if ( is_plugin_active( 'loyal-customer-benignsource/loyal-customer-benignsource.php' ) ) {
  //plugin is activated
?>
<span style="color:#dd6930;">Plugin Active</span>
<?php
} else {?>

<?php }?>
</div>

<div class="bs_product_links">
<?php 
if ( is_plugin_active( 'loyal-customer-benignsource/loyal-customer-benignsource.php' ) ) {
  //plugin is activated
?>
<a class="button button-secondary bs_upgrade_button" href="options-general.php?page=loyal-customer-benignsource">Settings</a>
<?php
} else {?>
<?php }?></div></div>
<div class="clear"></div></div>
<?php
} else {}?>
<!--end Loyal-->
<?php
if ( is_plugin_active( 'real-performance-benignsource/real-performancebs.php' ) ) {
  //plugin is activated
?>
<div class="bs_product_box" style="height: 120px; width:300px;">
<div style=" width:100px; float:left;">
<div class="bs_product_image">
<a href="https://www.benignsource.com/product/real-performance-benignsource/" target="_blank" class="bs_product_title">
<?php echo '<img src="' . esc_attr( plugins_url( 'img/rp_bsicon-128x128.jpg', __FILE__ ) ) . '" alt="Real Performance BenignSource" border="0px"> ';?></a></div>
</div>
<div class="bs_product_content">
<div class="bs_product_title"><a href="https://www.benignsource.com/product/real-performance-benignsource/" target="_blank" class="bs_product_title">Real Performance BenignSource</a></div>
<div class="bs-version">

<?php
if ( is_plugin_active( 'real-performance-benignsource/real-performancebs.php' ) ) {
  //plugin is activated
?>
<span style="color:#dd6930;">Plugin Active</span>
<?php
} else {?>

<?php }?>
</div>

<div class="bs_product_links">
<?php 
if ( is_plugin_active( 'real-performance-benignsource/real-performancebs.php' ) ) {
  //plugin is activated
?>
<a class="button button-secondary bs_upgrade_button" href="options-general.php?page=real-performance-benignsource">Settings</a>
<?php
} else {?>
<?php }?></div></div>
<div class="clear"></div></div>
<?php
} else {}?>
<!--end Real-->
</div>
<div style="width:100%; float:left; padding:20px;">
<div style="width:98%; float:left;"><h2 style="border-bottom: 4px #e96656 solid; color: #333333;  font-size:18px;">All Plugins</h2></div>
<div class="bs_product_box" style="height: 190px; width:300px;">
<div style=" width:100px; float:left;">
<div class="bs_product_image">
<?php echo '<img src="' . esc_attr( plugins_url( 'img/wpd_bsicon-128x128.jpg', __FILE__ ) ) . '" alt="Woo Product Design BenignSource" border="0px"> ';?></div>
</div>
<div class="bs_product_content">
<div class="bs_product_title"><a href="https://www.benignsource.com/download/" class="bs_product_title">Woo Product Design BenignSource</a></div>
<div class="bs-version">
<?php
if ( is_plugin_active( 'woo-product-design-benignsource/woo-product-design-benignsource.php' ) ) {
  //plugin is activated
?>
<span style="color:#dd6930;">Plugin Active</span>
<?php
} else {?>
<span>Not installed</span>
<?php }?>
</div>
<div class="bs_product_description">
Woo Product Design make your products with different designs and style! </div>
<div class="bs_product_links">
<?php 
if ( is_plugin_active( 'woo-product-design-benignsource/woo-product-design-benignsource.php' ) ) {
  //plugin is activated
?>
<a class="button button-secondary bs_upgrade_button" href="http://www.benignsource.com/product/woo-product-design-benignsource/" target="_blank">Upgrade to Premium</a>
<?php
} else {?>
<a class="button button-secondary" href="https://www.benignsource.com/product/woo-product-design-benignsource/" title="Download Now" target="_blank">Download Now</a><?php }?></div></div>
<div class="clear"></div></div>

<div class="bs_product_box" style="height: 190px; width:300px;">
<div style=" width:100px; float:left;">
<div class="bs_product_image">
<?php echo '<img src="' . esc_attr( plugins_url( 'img/lc_bsicon-128x128.jpg', __FILE__ ) ) . '" alt="Loyal Customer BenignSource" border="0px"> ';?></div>
</div>
<div class="bs_product_content">
<div class="bs_product_title"><a href="https://www.benignsource.com/product/loyal-customer-benignsource/" class="bs_product_title">Loyal Customer BenignSource</a></div>
<div class="bs-version">
<?php 
if ( is_plugin_active( 'loyal-customer-benignsource/loyal-customer-benignsource.php' ) ) {
  //plugin is activated
?>
<span style="color:#dd6930;">Plugin Active</span>
<?php
} else {?>
<span>Not installed</span>
<?php }?>
</div>
<div class="bs_product_description">
Create a campaign for regular customers or new ones.</div>
<div class="bs_product_links">
<?php 
if ( is_plugin_active( 'loyal-customer-benignsource/loyal-customer-benignsource.php' ) ) {
  //plugin is activated
?>
<a class="button button-secondary bs_upgrade_button" href="http://www.benignsource.com/product/loyal-customer-benignsource/" target="_blank">Upgrade to Premium</a>
<?php
} else {?>
<a class="button button-secondary" href="https://www.benignsource.com/product/loyal-customer-benignsource/" title="Download Now" target="_blank">Download Now</a><?php }?>
</div></div>
<div class="clear"></div></div>

<div class="bs_product_box" style="height: 190px; width:300px;">
<div style=" width:100px; float:left;">
<div class="bs_product_image">
<?php echo '<img src="' . esc_attr( plugins_url( 'img/cp_bsicon-128x128.jpg', __FILE__ ) ) . '" alt="Caet Promotion BenignSource" border="0px"> ';?></div>
</div>
<div class="bs_product_content">
<div class="bs_product_title"><a href="http://www.benignsource.com/product/cart-promotion-benignsource/" target="_blank" class="bs_product_title">Cart Promotion BenignSource</a></div>
<div class="bs-version">
<?php 
if ( is_plugin_active( 'cart-promotion-benignsource/cart-promotion-benignsource.php' ) ) {
  //plugin is activated
?>
<span style="color:#dd6930;">Plugin Active</span>
<?php
} else {?>
<span>Not installed</span>
<?php }?>

</div>
<div class="bs_product_description">
Promote Special Product or Accessories for each product in Shopping Cart.</div>
<div class="bs_product_links">
<?php 
if ( is_plugin_active( 'cart-promotion-benignsource/cart-promotion-benignsource.php' ) ) {
  //plugin is activated
?>
<a class="button button-secondary bs_upgrade_button" href="http://www.benignsource.com/product/cart-promotion-benignsource/" target="_blank">Upgrade to Premium</a>
<?php
} else {?>
<a class="button button-secondary" href="https://www.benignsource.com/product/cart-promotion-benignsource/" title="Download Nown" target="_blank">Download Now</a><?php }?></div></div>
<div class="clear"></div></div>

<div class="bs_product_box" style="height: 190px; width:300px;">
<div style=" width:100px; float:left;">
<div class="bs_product_image">
<?php echo '<img src="' . esc_attr( plugins_url( 'img/s_bsicon-128x128.jpg', __FILE__ ) ) . '" alt="Specification BenignSource" border="0px"> ';?></div>
</div>
<div class="bs_product_content">
<div class="bs_product_title"><a href="https://www.benignsource.com/product/specification-benignsource/" class="bs_product_title">Specification BenignSource</a></div>
<div class="bs-version">
<?php 
if ( is_plugin_active( 'specification-benignsource/specification-benignsource.php' ) ) {
  //plugin is activated
?>
<span style="color:#dd6930;">Plugin Active</span>
<?php
} else {?>
<span>Not installed</span>
<?php }?>

</div>
<div class="bs_product_description">
Full Specification Product Tab, Delivery & Returns Policy Tab.</div>
<div class="bs_product_links">
<?php 
if ( is_plugin_active( 'specification-benignsource/specification-benignsource.php' ) ) {
  //plugin is activated
?>
<a class="button button-secondary bs_upgrade_button" href="http://www.benignsource.com/product/specification-benignsource/" target="_blank">Upgrade to Premium</a>
<?php
} else {?>
<a class="button button-secondary" href="https://www.benignsource.com/product/specification-benignsource/" title="Download Now" target="_blank">Download Now</a><?php }?></div></div>
<div class="clear"></div></div>

<div class="bs_product_box" style="height: 190px; width:300px;">
<div style=" width:100px; float:left;">
<div class="bs_product_image">
<?php echo '<img src="' . esc_attr( plugins_url( 'img/sc_bsicon-128x128.jpg', __FILE__ ) ) . '" alt="Seo Converter BenignSource" border="0px"> ';?></div>
</div>
<div class="bs_product_content">
<div class="bs_product_title"><a href="https://www.benignsource.com/product/seo-converter-benignsource/" class="bs_product_title">SEO Converter BenignSource</a></div>
<div class="bs-version">
<?php 
if ( is_plugin_active( 'seo-converter-benignsource/seo-converter-benignsource.php' ) ) {
  //plugin is activated
?>
<span style="color:#dd6930;">Plugin Active</span>
<?php
} else {?>
<span>Not installed</span>
<?php }?>
</div>
<div class="bs_product_description">
Convert your WordPress to Real HTML Categories, Post and Products!</div>
<div class="bs_product_links">
<?php 
if ( is_plugin_active( 'seo-converter-benignsource/seo-converter-benignsource.php' ) ) {
  //plugin is activated
?>
<a class="button button-secondary bs_upgrade_button" href="http://www.benignsource.com/product/seo-converter-benignsource/" target="_blank">Upgrade to Premium</a>
<?php
} else {?>
<a class="button button-secondary" href="https://www.benignsource.com/product/seo-converter-benignsource/" title="Download Now" target="_blank">Download Now</a><?php }?></div></div>
<div class="clear"></div></div>
<div class="bs_product_box" style="height: 190px; width:300px;">
<div style=" width:100px; float:left;">
<div class="bs_product_image">
<?php echo '<img src="' . esc_attr( plugins_url( 'img/rp_bsicon-128x128.jpg', __FILE__ ) ) . '" alt="Real Performance BenignSource" border="0px"> ';?></div>
</div>
<div class="bs_product_content">
<div class="bs_product_title"><a href="https://www.benignsource.com/product/real-performance-benignsource/" class="bs_product_title">Real Performance BenignSource</a></div>
<div class="bs-version">
<?php 
if ( is_plugin_active( 'real-performance-benignsource/real-performancebs.php' ) ) {
  //plugin is activated
?>
<span style="color:#dd6930;">Plugin Active</span>
<?php
} else {?>
<span>Not installed</span>
<?php }?>
</div>
<div class="bs_product_description">
Real Performance Search Engine (SEO) & Performance Optimization in Real Time!</div>
<div class="bs_product_links">
<?php 
if ( is_plugin_active( 'real-performance-benignsource/real-performancebs.php' ) ) {
  //plugin is activated
?>
<a class="button button-secondary bs_upgrade_button" href="http://www.benignsource.com/product/real-performance-benignsource/" target="_blank">Upgrade to Premium</a>
<?php
} else {?>
<a class="button button-secondary" href="https://www.benignsource.com/product/real-performance-benignsource/" title="Download Now" target="_blank">Download Now</a><?php }?></div></div>
<div class="clear"></div></div>
<div class="bs_product_box" style="height: 190px; width:300px;">
<div style=" width:100px; float:left;">
<div class="bs_product_image">
<?php echo '<img src="' . esc_attr( plugins_url( 'img/p_bsicon-128x128.jpg', __FILE__ ) ) . '" alt="Protect BenignSource" border="0px"> ';?></div>
</div>
<div class="bs_product_content">
<div class="bs_product_title"><a href="https://www.benignsource.com/product/protect-benignsource/" class="bs_product_title">Protect BenignSource</a></div>
<div class="bs-version">
<?php 
if ( is_plugin_active( 'protect-benignsource/protectbs.php' ) ) {
  //plugin is activated
?>
<span style="color:#dd6930;">Plugin Active</span>
<?php
} else {?>
<span>Not installed</span>
<?php }?>
</div>
<div class="bs_product_description">
Protect your WordPress.</div>
<div class="bs_product_links">
<?php 
if ( is_plugin_active( 'protect-benignsource/protectbs.php' ) ) {
  //plugin is activated
?>
<a class="button button-secondary bs_upgrade_button" href="http://www.benignsource.com/product/protect-benignsource/" target="_blank">Upgrade to Premium</a>
<?php
} else {?>
<a class="button button-secondary" href="https://www.benignsource.com/product/protect-benignsource/" title="Download Now" target="_blank">Download Now</a><?php }?></div></div>
<div class="clear"></div></div>

<div class="bs_product_box" style="height: 190px; width:300px;">
<div style=" width:100px; float:left;">
<div class="bs_product_image">
<?php echo '<img src="' . esc_attr( plugins_url( 'img/ms_bsicon-128x128.jpg', __FILE__ ) ) . '" alt="Meta SEO BenignSource" border="0px"> ';?></div>
</div>
<div class="bs_product_content">
<div class="bs_product_title"><a href="https://www.benignsource.com/product/meta-seo-benignsource-for-woocommerce-wordpress/" class="bs_product_title">Meta SEO BenignSource</a></div>
<div class="bs-version">
<?php 
if ( is_plugin_active( 'meta-seo-benignsource/meta-seobenignsource.php' ) ) {
  //plugin is activated
?>
<span style="color:#dd6930;">Plugin Active</span>
<?php
} else {?>
<span>Not installed</span>
<?php }?>
</div>
<div class="bs_product_description">
Optimize your WordPress.</div>
<div class="bs_product_links">
<?php 
if ( is_plugin_active( 'meta-seo-benignsource/meta-seobenignsource.php' ) ) {
  //plugin is activated
?>
<a class="button button-secondary bs_upgrade_button" href="http://www.benignsource.com/product/meta-seo-benignsource-for-woocommerce-wordpress/" target="_blank">Upgrade to Premium</a>
<?php
} else {?>
<a class="button button-secondary" href="https://www.benignsource.com/product/meta-seo-benignsource-for-woocommerce-wordpress/" title="Download Now" target="_blank">Download Now</a><?php }?></div></div>
<div class="clear"></div></div></div>
<div style="width:100%; float:left; text-align:center;">Copyright &copy; 2001 - <?php printf(__('%1$s | %2$s'), date("Y"), ''); ?> <a href="http://www.benignsource.com/" target="_blank" title="BenignSource">BenignSource</a> Company, All Rights Reserved.</div>
</div></div></div></div></div>