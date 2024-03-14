<?php
$isEsignatureActive  = (function_exists("WP_E_Sig")) ? true : false;
$isEsigAndProActive = (function_exists("WP_E_Sig") && class_exists("ESIG_SAD_Admin")) ? true : false;
?>
<!-- Start Step 1 -->
<div class="approveme-feature-section approveme-table">
    <div class="approveme-row">
        <div class="approveme-column-2">
            <h4><span class="esig-step">1</span> Download and Install the required plugins</h4>
            <p>Congrats on installing our Digital Signature WooCommerce Add-on. You're almost ready! ApproveMe's WP E-Signature requires a few more plugins/add-ons so you can unlock the power and automate WooCommerce and legally binding Digital Signatures. You'll need to download the following:</p>

            <p>1. <a href="http://www.woothemes.com/woocommerce/">WooCommerce</a><br>2. <a href="https://www.approveme.com/wp-e-signature/?utm_source=wprepo&utm_medium=link&utm_campaign=woocommerce">WP E-Signature</a> <em>(Elite,Plus license)<br> </em>3. WP E-Signature Add-ons Pack <em>(included with the Elite and Plus license)</em></p>
        </div>

        <div class="approveme-column-2 approveme-center">.
            <div class="approveme-image">
                <p>
                    <br> <img src="<?php echo esc_url(plugins_url('../assets/images/woo-add-on-step-1.png', __FILE__)); ?>" class="esig-woo-pic-border" width="100%">
                </p>
            </div>
        </div>
    </div>

</div>
<!-- End Step 1 -->
<!-- Start Step 2 -->
<div class="approveme-feature-section approveme-table">
    <div class="approveme-row">


        <div class="approveme-column-2 approveme-center">

            <div class="approveme-image">
                <p><br> <img src="<?php echo esc_url(plugins_url('../assets/images/woo-add-on-step-2.jpg', __FILE__)); ?>" class="esig-woo-pic-border" width="100%"></p>
            </div>


        </div>

        <div class="approveme-column-2">
            <h4><span class="esig-step">2</span> Enter your ApproveMe license</h4>
            <?php $licenseUrl = (function_exists("WP_E_Sig")) ? '<a href="admin.php?page=esign-licenses-general">enter your license key</a>' : 'enter your license key' ; ?>
            <p>Once you have purchased an Elite or Plus licenses from ApproveMe you will need to <?php echo $licenseUrl; ?> to activate updates and support.</p>

            <p>To view your license key you can log into your <a href="https://www.approveme.com/sign-in/?utm_source=wprepo&utm_medium=link&utm_campaign=woocommerce">ApproveMe account here</a> (password was emailed with your receipt)</p>

        </div>


    </div>
</div>
<!-- End Step 2 -->
<!-- Start Step 3 -->
<div class="approveme-feature-section approveme-table">
    <div class="approveme-row">
        <div class="approveme-column-2">
            <h4><span class="esig-step">3</span> Create a blank WordPress page</h4>
            <?php $settingsUrl =  ($isEsignatureActive) ? '<a href="admin.php?page=esign-settings">settings are saved</a>' : 'settings are saved'; ?>
            <p>After your WP E-Signature plugins are installed and your <?php echo $settingsUrl; ?>, you will need to create a blank WordPress page for each stand alone document that you will be creating. To start, create one blank WordPress page, give it a title, and publish it with the content blank.</p>

            <?php if ($isEsigAndProActive) : ?>
                <div class="approveme-btn">
                    <p class="approveme-mini-btn"><a href="post-new.php?post_type=page" class="approveme-mini-btn outlined" target="_blank">Create a Blank Page Now</a></p>
                </div>
            <?php endif; ?>
            <p>To learn about stand alone documents, you can visit this <a href="https://wpe.approveme.com/article/156-basic-document-vs-stand-alone-document/?utm_source=wprepo&utm_medium=link&utm_campaign=woocommerce" target="_blank">helpful article</a></p>
        </div>

        <div class="approveme-column-2 approveme-center">
            <div class="approveme-image">
                <p><br> <img src="<?php echo esc_url(plugins_url('../assets/images/woo-add-on-step-3.png', __FILE__)); ?>" class="esig-woo-pic-border" width="100%"></p>
            </div>
        </div>
    </div>

</div>
<!-- End Step 3 -->
<!-- Start Step 4 -->
<div class="approveme-feature-section approveme-table">
    <div class="approveme-row">
        <div class="approveme-column-2 approveme-center">
            <div class="approveme-image">
                <p><br> <img src="<?php echo esc_url(plugins_url('../assets/images/woo-add-on-step-4.png', __FILE__)); ?>" class="esig-woo-pic-border" width="100%"></p>
            </div>
        </div>

        <div class="approveme-column-2">
            <h4><span class="esig-step">4</span> Create a Stand Alone Document</h4>
            <p>You're almost there! Now that you've created a blank WordPress page, we need to create a stand alone document and connect it with that WordPress page.</p>

            <?php if ($isEsigAndProActive) : ?>
                <div class="approveme-btn">
                    <p class="approveme-mini-btn approveme-center"><a href="edit.php?post_type=esign&page=esign-add-document&esig_type=sad" class="approveme-mini-btn outlined" target="_blank">Create Stand Alone Doc Now</a></p>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>
<!-- End Step 4 -->
<!-- Start Option 1 -->
<div class="approveme-feature-section approveme-table">
    <div class="approveme-row">
        <div class="approveme-column-2">
            <h4><span class="esig-step">5a</span> Per-Product Contracts</h4>
            <p>This last step will vary based on your desired outcome. Digital signature gives you two contract WooCommerce E-Signature options. The first option is per-products agreements.</p>


            <p>If a customer adds a product that has a "per-product" contract attached to it into their e-commerce shopping cart, they will be required to sign your contract before entering their credit card information. You can attach a unique contract for each unique product you offer.</p>

            <p><strong>How to Attach a Contract to a Unique Product</strong><br>
            <ol>
                <?php $wooLink  = (function_exists("wc")) ? '<a href="edit.php?post_type=product">Navigate to the product</a>' : 'Navigate to the product' ; ?>
                <li><?php echo $wooLink; ?> you would like to attach your contract to.</li>
                <li>Select your desired Stand Alone Document from the product sidebar using the dropdown menu.</li>
                <li>Save your changes.</li>
            </ol>
            </p>
        </div>

        <div class="approveme-column-2 approveme-center">
            <div class="approveme-image">
                <p><br> <img src="<?php echo esc_url(plugins_url('../assets/images/woo-add-on-step-5a.jpg', __FILE__)); ?>" class="esig-woo-pic-border" width="100%"> </p>
            </div>
        </div>
    </div>

</div>
<!-- End Option 1 -->
<!-- Start Option 2 -->
<div class="approveme-feature-section approveme-table">
    <div class="approveme-row">
        <div class="approveme-column-2 approveme-center">
            <div class="approveme-image">
                <p><br> <img src="<?php echo esc_url(plugins_url('../assets/images/woo-add-on-step-5b.jpg', __FILE__)); ?>" class="esig-woo-pic-border" width="100%"></p>
            </div>
        </div>

        <div class="approveme-column-2">
            <h4><span class="esig-step">5b</span> Global Contracts</h4>
            <p>A Global Contract is pretty rad... it lets you set a &quot;global contract&quot; or "global agreement" for your entire e-commerce store. In short you can require ALL customers (regardless of the products they purchase) to sign a legal contract before completing their checkout.</p>

            <p><strong>How to Define Your Global Contract</strong><br>
            <ol>
                <?php $checkoutLink = (function_exists("wc")) ? '<a href="admin.php?page=wc-settings&tab=checkout">WooCommerce Checkout Options</a>' : 'WooCommerce Checkout Options' ; ?>
                <li>Navigate to the <?php echo $checkoutLink ; ?></li>
                <li>Select your desired Stand Alone Document from the agreements dropdown menu.</li>
                <li>Save your changes.</li>
            </ol>
            </p>

        </div>
    </div>

</div>
<!-- End Option 2 -->