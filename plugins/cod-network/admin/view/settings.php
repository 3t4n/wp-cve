<?php
/*
 * Admin page
 */
?>
<div class="row">
    <div class="col-md-12">
        <form action="#" method="post">
            <h1> COD.network </h1>
            <fieldset>
                <span>COD Network Plugin allows you to linking your store to your COD Network account, you benefits also from easy listing and a fast synchronisation between the two platforms to make your work quit easy.</span>

                <div class="inner-block has-padding">
                    <div class="block-header">
                        <div class="block-title"><h2>Setup and link your own COD Network account</h2></div>
                    </div>
                    <div class="block-content is-grid grid-3">
                        <div class="content"><h4 class="guide-title">Steps to link your Store with COD Network</h4>
                            <ul class="guide-list">
                                <li>Click on <span>"Link my store"</span> in the bottom of the page</li>
                                <li>Once you click on it, you will be redirected to your COD Network account <span>(make sure you are logging to your account in another tab).</span>
                                </li>
                                <li>Click on <span>"Connect to WooCommerce"</span></li>
                                </li>
                                <li>Your store is now linked and synchronized with your COD Network accounts</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </fieldset>

            <?php
            if ($codNetwork->has_token()) { ?>
                <a href="#" class="btn-link-cod-network btn-link-cod-network-success">Your store is now linked and synchronized with your COD Network account</a>
            <?php } elseif (codn_wc_plugin_is_loaded() && codn_wc_plugin_is_active()) {
                echo sprintf('<a href="%s" class="btn-link-cod-network" target="_blank">Link your store</a>', esc_url($connectLink));
             }
            ?>
        </form>
    </div>
</div> 
