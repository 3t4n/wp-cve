<!--<div class="alert alert-info mt-2"><strong><?php //pisol_help::youtube('oGE6daMXrOk','Know more about the Additional Charges'); ?> Click to Know more about this Additional Charges feature </strong></div>-->
<div class="border bg-secondary p-3 mt-2">
<div class="row">
    <div class="col-6"><label for="pi_enable_additional_charges" class="text-light mb-0">Increase / Decrease Fees by this extra rules</label><?php pisol_help::inline('inc_dec_fees_help', 'Increase / Decrease Fees '); ?></div>
    <div class="col-6">
        <div class="custom-control custom-switch">
            <input type="checkbox" value="1" <?php echo $data['pi_enable_additional_charges']; ?> class="custom-control-input" name="pi_enable_additional_charges" id="pi_enable_additional_charges">
            <label class="custom-control-label" for="pi_enable_additional_charges"></label>
        </div>
    </div>
</div>
</div>
<div id="additional-charges-container">
    <div class="row no-gutters">
        <div class="col-2">
            <?php do_action('pi_cefw_additional_charges_tab', $data); ?>
        </div>
        <div class="col-10">
            <?php do_action('pi_cefw_additional_charges_tab_content', $data); ?>
        </div>
    </div>
</div>