<?php
$plgName = EzGA::getPlgName();
$plgSlug = EzGA::getSlug();
$plgPrice = EzGA::$plgPrice;
openCell("Option Sets", 'zoom-in', 5);
?>
<p>
  In the <a data-toggle="tooltip" title="Get the Pro version of <?php echo $plgName; ?> for only $<?php echo $plgPrice[$plgSlug]; ?>. Tons of extra features. Instant download." href="http://buy.thulasidas.com/<?php echo $plgSlug; ?>" class='goPro' data-product="<?php echo $plgSlug; ?>">Pro version</a> of this plugin, you can create as many <strong>Option Sets</strong> as you like, and set any one of them as active. Using this feature, you can experiment with a new set of options and leave them as active if satisfactory, or revert to your original set if unsatisfactory.
</p>
<p>
  If you enable <strong>Mobile Support</strong> (another <a data-toggle="tooltip" title="Get the Pro version of <?php echo $plgName; ?> for only $<?php echo $plgPrice[$plgSlug]; ?>. Tons of extra features. Instant download." href="http://buy.thulasidas.com/<?php echo $plgSlug; ?>" class='goPro' data-product="<?php echo $plgSlug; ?>">Pro feature</a>), two <strong>Option Sets</strong> (<strong><code>Phone</code></strong> and <strong><code>Tablet</code></strong>) will be created for you. They will be used when your blog is being viewed on phones and tablets respectively.
</p>
<?php
closeCell();
