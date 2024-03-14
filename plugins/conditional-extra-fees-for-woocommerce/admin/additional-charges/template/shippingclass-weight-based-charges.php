<script>
    var pi_shippingclass_weight_charges_count = <?php echo count(!empty($data['pi_shippingclass_weight_charges']) && is_array($data['pi_shippingclass_weight_charges'])? $data['pi_shippingclass_weight_charges'] : array()) ; ?>
</script>
<div class="p-3 bg-dark">
<div class="row">
    <div class="col-7"><label for="pi_enable_additional_charges_shippingclass_weight" class="mb-0 text-light">Change Fees based on Shipping Class Weight</label> <?php //pisol_help::youtube('qIZM7VUUy1c','Know more about the Shipping class weight based charge'); ?></div>
    <div class="col-5">
        <div class="custom-control custom-switch">
            <input type="checkbox" value="1" <?php echo $data['pi_enable_additional_charges_shippingclass_weight']; ?> class="custom-control-input" name="pi_enable_additional_charges_shippingclass_weight" id="pi_enable_additional_charges_shippingclass_weight">
            <label class="custom-control-label" for="pi_enable_additional_charges_shippingclass_weight"></label>
        </div>
    </div>
</div>
</div>
<div id="additional_charges_shippingclass_weight_container">
<div class="row py-3">
    <div class="col-6">
        <a href="javascript:void(0)" class="btn btn-primary btn-sm" id="add_shippingclass_weight_charges_range">Add Rule</a>
    </div>
    <div class="col-6">
        <?php pisol_cefw_additional_charges_form::sumOfCharges('pi_cefw_shippingclass_weight_sum_of_charges', $data); ?>
    </div>
</div>
<template id="shippingclass_weight_charges_template" >
    <tr>
        <td><select required name="pi_shippingclass_weight_charges[{{count}}][shippingclass]" class="form-control" >
        <?php 
         if(is_array($data['present_shipping_classes']) && !empty($data['present_shipping_classes'])){
            foreach($data['present_shipping_classes'] as $shipping_class){ 
                echo sprintf('<option value="%s">%s</option>', esc_attr($shipping_class->term_id), esc_html($shipping_class->name));
            } 
         }
        ?>
        </select></td>
        <td class="pi-min-col"><input type="number" required name="pi_shippingclass_weight_charges[{{count}}][min]" min="0" class="form-control" step="0.0001"></td>
        <td class="pi-max-col"><input type="number" name="pi_shippingclass_weight_charges[{{count}}][max]" min="0"  class="form-control" step="0.0001"></td>
        <td  class="pi-fee-col"><input type="text" required name="pi_shippingclass_weight_charges[{{count}}][charge]" class="form-control"></td>
        <td><button class="delete-additional-charges btn btn-danger btn-sm"><span class="dashicons dashicons-trash"></span></button></td>
    </tr>
</template>
<table id="shippingclass_weight_charges_table" class="table">
    <thead>
        <tr>
            <th>Shipping class</th>
            <th class="pi-min-col">Min Weight</th>
            <th class="pi-min-col">Max Weight</th>
            <th class="pi-fee-col">Fees <?php pisol_help::inline('shippingclass_weight_charge_short_code_help', 'Using short code'); ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php $this->rowTemplate($data); ?>
    </tbody>
</table>
</div>
