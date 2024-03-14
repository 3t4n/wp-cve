<?php
$plgName = EzGA::getPlgName();
$plgSlug = EzGA::getSlug();
$plgPrice = EzGA::$plgPrice;
if ($plgName == 'Google AdSense Ultra') {
  ?>
<div class="col-md-3 col-sm-6 goPro">
  <a data-toggle="tooltip" title="Do you have an eBook, image or any other digital product to sell? Get EZ PayPal and start your e-store in minutes!." class="well top-block goPro" href="http://buy.thulasidas.com/ezpaypal">
    <i class="glyphicon glyphicon-shopping-cart red"></i>
    <div>Get EZ PayPal Pro</div>
    <div>$19.95. Instant Download</div>
  </a>
</div>  <?php
}
else if (EzGA::isPro()) {
  ?>
  <div class="col-md-3 col-sm-3 col-xs-6 goPro">
    <a data-toggle="tooltip" title="Start your own ad server for only $15.95. Instant download. Fully compatible with Easy AdSense and AdSense Now! Serve and track ads to multiple blogs and websites." class="well top-block goPro" href="http://buy.thulasidas.com/ads-ez" data-product='ads-ez'>
      <i class="glyphicon glyphicon-shopping-cart red center-text"></i>
      <div>Get Ads EZ Pro &nbsp;&nbsp;<span class='label label-info moreInfo'>More Info</span></div>
      <div>$15.95. Instant Download</div>
    </a>
  </div>
  <?php
}
else {
  $basePlgName = str_replace(" Lite", "", $plgName);
  ?>
  <div class="col-md-3 col-sm-3 col-xs-6 goPro" data-product="<?php echo $plgSlug; ?>">
    <a data-toggle="tooltip" title="Get the Pro version of <?php echo $basePlgName; ?> for only $<?php echo $plgPrice[$plgSlug]; ?>. Tons of extra features. Instant download." class="well top-block goPro" href="http://buy.thulasidas.com/<?php echo $plgSlug; ?>">
      <i class="glyphicon glyphicon-shopping-cart red"></i>
      <div>Get <?php echo $basePlgName; ?> Pro</div>
      <div>$<?php echo $plgPrice[$plgSlug]; ?>. Instant Download</div>
      <span class="notification red">Pro</span>
    </a>
  </div>
  <?php
}
?>
<div class="col-md-3 col-sm-3 col-xs-6">
  <a data-toggle="tooltip" title="See other premium WordPress plugins and PHP programs by the same author." class="well top-block" href="http://www.thulasidas.com/render" target="_blank">
    <i class="glyphicon glyphicon-star green"></i>
    <div>Other Plugins and Programs</div>
    <div>From the author</div>
  </a>
</div>
<div class="col-md-3 col-sm-3 col-xs-6">
  <a data-toggle="tooltip" title="Check out the author credentials in the form of a CV." class="well top-block" href="http://www.thulasidas.com/col/Manoj-CV.pdf" target="_blank">
    <i class="glyphicon glyphicon-user blue"></i>
    <div>Author Profile</div>
    <div>CV from Author's blog</div>
  </a>
</div>
<div class="col-md-3 col-sm-3 col-xs-6">
  <a data-toggle="tooltip" title="If you need customized PHP development for your site, or have some other questions, contact the author." class="well top-block" href="http://www.thulasidas.com/professional-php-services/" target="_blank">
    <i class="glyphicon glyphicon-envelope yellow"></i>
    <div>Contact</div>
    <div>Enquiries and Support</div>
  </a>
</div>
<div class="clearfix"></div>
<script>
  $(".moreInfo").click(function () {
    var product = $(this).parent().closest('a').attr('data-product');
    ezPopUp("http://www.thulasidas.com/" + product, product, 1000, 1024);
    return false;
  });
</script>
