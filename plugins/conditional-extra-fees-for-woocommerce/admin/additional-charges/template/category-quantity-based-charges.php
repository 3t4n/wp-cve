<script>
    var pi_category_quantity_charges_count = <?php echo count(!empty($data['pi_category_quantity_charges']) && is_array($data['pi_category_quantity_charges'])? $data['pi_category_quantity_charges'] : array()) ; ?>
</script>
<div class="p-3 bg-dark">
<div class="row">
    <div class="col-6"><label for="pi_enable_additional_charges_category_quantity" class="mb-0 text-light">Change Fees based on Category Quantity</label> <?php ////pisol_help::youtube('6S1eVLuR6b8','Know more about the Category Quantity based charge'); ?></div>
    <div class="col-6">
        <div class="custom-control custom-switch">
            <input type="checkbox" value="1" <?php echo $data['pi_enable_additional_charges_category_quantity']; ?> class="custom-control-input" name="pi_enable_additional_charges_category_quantity" id="pi_enable_additional_charges_category_quantity">
            <label class="custom-control-label" for="pi_enable_additional_charges_category_quantity"></label>
        </div>
    </div>
</div>
</div>
<div id="additional_charges_category_quantity_container">
<div class="row py-3">
    <div class="col-6">
        <a href="javascript:void(0)" class="btn btn-primary btn-sm" id="add_category_quantity_charges_range">Add Rule</a>
    </div>
    <div class="col-6">
        <?php pisol_cefw_additional_charges_form::sumOfCharges('pi_cefw_category_quantity_sum_of_charges', $data); ?>
    </div>
</div>
<template id="category_quantity_charges_template" >
    <tr>
        <td><select required name="pi_category_quantity_charges[{{count}}][category]" class="pi_extra_charge_dynamic_value form-control" data-get="category">
        </select></td>
        <td class="pi-min-col"><input type="number" required name="pi_category_quantity_charges[{{count}}][min]" min="1" class="form-control"></td>
        <td class="pi-max-col"><input type="number" name="pi_category_quantity_charges[{{count}}][max]" min="1"  class="form-control"></td>
        <td  class="pi-fee-col"><input type="text" required name="pi_category_quantity_charges[{{count}}][charge]" class="form-control"></td>
        <td><button class="delete-additional-charges btn btn-danger btn-sm"><span class="dashicons dashicons-trash"></span></button></td>
    </tr>
</template>
<table id="category_quantity_charges_table" class="table">
    <thead>
        <tr>
            <th>Category</th>
            <th class="pi-min-col">Min Qty</th>
            <th class="pi-min-col">Max Qty</th>
            <th class="pi-fee-col">Fees <?php pisol_help::inline('category_quantity_charge_short_code_help', 'Using short code'); ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php $this->rowTemplate($data); ?>
    </tbody>
</table>
</div>
