<?php

global $rninstance;


?>

<style>
    .AdditionalPluginItem{
        background-color: white;
        padding: 20px;
        margin:20px;
        display: inline-block;
        width: 300px;
        vertical-align: top;
    }

    .AdditionalPluginItem label{
        font-weight: bold;
    }

    .AdditionalPluginItem ul{
        list-style: disc;
        list-style-position: inside;
    }

    .AdditionalPluginItem .PluginTitle{
        min-height: 90px;
        border-bottom: 1px solid #ccc;
    }

    .AdditionalPluginItem ul{
        min-height:130px;
    }
</style>



<div style="padding: 20px">
    <div class="AdditionalPluginItem">
        <div class="productImage" style="text-align: center">
            <img src="<?php echo RednaoWooCommercePDFInvoice::$URL ?>images/advancedemailing128.png"/>
        </div>

        <div class="PluginTitle">
            <label>Advanced Emailing for WooCommerce</label>
            <p>Crete custom emails that can be sent manually or when an order met a condition</p>
        </div>
        <p style="font-weight: bold;">Useful for:</p>
        <ul>
            <li>Add more email options to WooCommerce</li>
            <li>The emails can be sent automatically when a condition is met (such like when a particular product is purchases or when an order reaches a status)</li>
            <li>Integration with other plugins supported. You can add data that other plugins added to your order or products. No coding skills required</li>
        </ul>

        <div style="text-align: center">
            <a target="_blank" href="https://advancedemailingwc.rednao.com/get-it/">View Details</a>
        </div>
    </div>

    <div class="AdditionalPluginItem">
        <div class="productImage" style="text-align: center">
            <img src="<?php echo RednaoWooCommercePDFInvoice::$URL ?>images/productBuilder128.jpg"/>
        </div>
        <div class="PluginTitle">
            <label>Product Options Builder for WooCommerce</label>
            <p>Let your users customize their products with a bunch of fields at your disposal</p>
        </div>
        <p style="font-weight: bold;">Useful for:</p>
        <ul>
            <li>Letting your users customize a product right before adding it to the cart</li>
            <li>+40 fields to customize your product exactly as you want it</li>
            <li>Global options supported. You can configure options once and apply then to all the products that met a condition</li>
        </ul>

        <div style="text-align: center">
            <a target="_blank" href="https://productbuilder.rednao.com/getit/">View Details</a>
        </div>
    </div>
</div>



