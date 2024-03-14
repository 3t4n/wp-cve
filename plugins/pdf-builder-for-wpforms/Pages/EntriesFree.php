<?php
global $rninstance;
/** @var Loader $loader */

use rednaoformpdfbuilder\core\Loader;

$loader=$rninstance;
?>
<div style="position: relative;display: inline-block;">
    <img src="<?php echo $loader->URL?>images/fullentries.png"/>
    <div style="position: absolute;top:0;left:0;width:100%;height: 100%;background-color: black;opacity: .5;"></div>
    <div style="position: absolute;top:0;left:0;width:100%;height:100%;display: flex;align-items: center;justify-content: center;">
        <h1 style="color:white;"><?php echo __("The entries screen is only available in the full version.","rnpdfbuilder")?> <a target="_blank" style="color:white;" href="<?php echo $loader->GetPurchaseURL() ?>">Get it here</a> </h1>
    </div>
</div>