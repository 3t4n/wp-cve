<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<button id="saveMethods" class="button-primary woocommerce-save-button">Zapisz</button>
<form id="panel-form-methods">
<?php foreach($zones as $zone) { ?>
    <div class="epaka-zone">
        <h2><?php echo $zone['zone_name']?></h2>
        <?php foreach($zone['shipping_methods'] as $method) {?>
            <?php $method_title = preg_replace("/[^a-zA-Z0-9\']/","", $method->get_title());?>
            <?php if(get_class($method) != "WPDesk_Flexible_Shipping") {?>
                <div>
                    <input value="<?php echo $method->get_title()?>" type="text" readonly>
                    <select class="method-courier-map-value" name="data[Epaka_Shipping_Mapping][<?php echo $zone["zone_id"]?>][<?php echo $method_title?>][epaka_courier]" >
                        <option value="">Wybierz kuriera Epaka</option>
                        <?php foreach($availableCouriers as $courier){ ?>
                            <option value="<?php echo $courier['courierId']?>" 
                            <?php 
                                if(!empty($savedShippingMapping)){
                                    if(!empty($savedShippingMapping['Epaka_Shipping_Mapping'][$zone["zone_id"]][$method_title])){
                                        if($savedShippingMapping['Epaka_Shipping_Mapping'][$zone["zone_id"]][$method_title]['epaka_courier'] == $courier['courierId']){
                                            echo "selected";
                                        }
                                    }
                                }
                            ?>><?php echo urldecode($courier['courierName'])?></option>
                        <?php } ?>
                    </select>
                    <input name="data[Epaka_Shipping_Mapping][<?php echo $zone["zone_id"]?>][<?php echo $method_title?>][map_enabled]" type="checkbox" style="display:none;" />
                    <input class="map_source_url" name="data[Epaka_Shipping_Mapping][<?php echo $zone["zone_id"]?>][<?php echo $method_title?>][map_source_url]" type="text" style="display:none;"/>
                    <input class="map_source_name" name="data[Epaka_Shipping_Mapping][<?php echo $zone["zone_id"]?>][<?php echo $method_title?>][map_source_name]" type="text" style="display:none;"/>
                    <input class="map_source_id" name="data[Epaka_Shipping_Mapping][<?php echo $zone["zone_id"]?>][<?php echo $method_title?>][map_source_id]" type="text" style="display:none;"/>
                </div>
                <br/>
            <?php } else {?>
                <?php foreach($method->get_shipping_methods() as $flex_method) { ?>
                    <div>
                        <input value="<?php echo $flex_method['method_title']?>" type="text" readonly>
                        <select class="method-courier-map-value" name="data[Epaka_Shipping_Mapping][<?php echo $zone["zone_id"]?>][<?php echo $method_title?>][epaka_courier]">
                            <option value="">Wybierz kuriera Epaka</option>
                            <?php foreach($availableCouriers as $courier){ ?>
                                <option value="<?php echo $courier['courierId']?>"
                                <?php 
                                if(!empty($savedShippingMapping)){
                                        if(!empty($savedShippingMapping['Epaka_Shipping_Mapping'][$zone["zone_id"]][$method_title])){
                                            if($savedShippingMapping['Epaka_Shipping_Mapping'][$zone["zone_id"]][$method_title]['epaka_courier'] == $courier['courierId']){
                                                echo "selected";
                                            }
                                        }
                                    }
                                ?>
                                ><?php echo urldecode($courier['courierName'])?></option>
                            <?php } ?>
                        </select>
                        <input name="data[Epaka_Shipping_Mapping][<?php echo $zone["zone_id"]?>][<?php echo $method_title?>][map_enabled]" type="checkbox" style="display:none;"/>
                        <input class="map_source_url" name="data[Epaka_Shipping_Mapping][<?php echo $zone["zone_id"]?>][<?php echo $method_title?>][map_source_url]" type="text" style="display:none;"/>
                        <input class="map_source_name" name="data[Epaka_Shipping_Mapping][<?php echo $zone["zone_id"]?>][<?php echo $method_title?>][map_source_name]" type="text" style="display:none;"/>
                        <input class="map_source_id" name="data[Epaka_Shipping_Mapping][<?php echo $zone["zone_id"]?>][<?php echo $method_title?>][map_source_id]" type="text" style="display:none;"/>
                    </div>
                    <br/>
                <?php }?>
            <?php }?>
        <?php } ?>
    </div>
<?php } ?>
</form>

<script>
    window.setAvailableCouriers(JSON.parse('<?php echo json_encode(array_values($availableCouriers))?>'));
</script>