<script>
    var pi_shippingclass_quantity_charges_count = <?php echo count(!empty($data['pi_shippingclass_quantity_charges']) && is_array($data['pi_shippingclass_quantity_charges'])? $data['pi_shippingclass_quantity_charges'] : array()) ; ?>
</script>
<div class="p-3 bg-dark">
<div class="row">
    <div class="col-7"><label for="pi_enable_additional_charges_shippingclass_quantity" class="mb-0 text-light">Change Fees based on Shipping Class Quantity</label> <?php //pisol_help::youtube('DK04pdaB4u0','Know more about the Shipping class Quantity based charge'); ?></div>
    <div class="col-5">
        <div class="custom-control custom-switch">
            <input type="checkbox" value="1" <?php echo $data['pi_enable_additional_charges_shippingclass_quantity']; ?> class="custom-control-input" name="pi_enable_additional_charges_shippingclass_quantity" id="pi_enable_additional_charges_shippingclass_quantity">
            <label class="custom-control-label" for="pi_enable_additional_charges_shippingclass_quantity"></label>
        </div>
    </div>
</div>
</div>
<div id="additional_charges_shippingclass_quantity_container">
<div class="row py-3">
    <div class="col-6">
        <a href="javascript:void(0)" class="btn btn-primary btn-sm" id="add_shippingclass_quantity_charges_range">Add Rule</a>
    </div>
    <div class="col-6">
        <?php pisol_cefw_additional_charges_form::sumOfCharges('pi_cefw_shippingclass_quantity_sum_of_charges', $data); ?>
    </div>
</div>
<template id="shippingclass_quantity_charges_template" >
    <tr>
        <td><select required name="pi_shippingclass_quantity_charges[{{count}}][shippingclass]" class="form-control" >
        <?php 
         if(is_array($data['present_shipping_classes']) && !empty($data['present_shipping_classes'])){
            foreach($data['present_shipping_classes'] as $shipping_class){ 
                echo sprintf('<option value="%s">%s</option>', esc_attr($shipping_class->term_id), esc_html($shipping_class->name));
            } 
         }
        ?>
        </select></td>
        <td class="pi-min-col"><input type="number" required name="pi_shippingclass_quantity_charges[{{count}}][min]" min="1" class="form-control"></td>
        <td class="pi-max-col"><input type="number" name="pi_shippingclass_quantity_charges[{{count}}][max]" min="1"  class="form-control"></td>
        <td  class="pi-fee-col"><input type="text" required name="pi_shippingclass_quantity_charges[{{count}}][charge]" class="form-control"></td>
        <td><button class="delete-additional-charges btn btn-danger btn-sm"><span class="dashicons dashicons-trash"></span></button></td>
    </tr>
</template>
<table id="shippingclass_quantity_charges_table" class="table">
    <thead>
        <tr>
            <th>Shipping class</th>
            <th class="pi-min-col">Min Qty</th>
            <th class="pi-min-col">Max Qty</th>
            <th class="pi-fee-col">Fees <?php pisol_help::inline('shippingclass_quantity_charge_short_code_help', 'Using short code'); ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php $this->rowTemplate($data); ?>
    </tbody>
</table>
</div>
