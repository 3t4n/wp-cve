<?php if($company->type == 'success'): ?>
<?php 
global $pagenow;
if(!isset($meta['send_to_company'])){
    $meta['send_to_company'] = 0;
}
?>
<fieldset>
    <p>
    <?php echo $company->data ? $company->data->name : "No Company Found";  ?>
    </p>
</fieldset>
<?php elseif($company->type == 'duplicate'): ?>
<fieldset>
    <p style="color:red">
        Error: This API is already attached with webiste: <?php echo $company->site_data->site_name."(".$company->site_data->site_url.")" ?>
    </p>
</fieldset>
<?php else: ?>
<fieldset>
    <p style="color:red">
        Error: No Company Found
    </p>
    <p class="description">Please add valid Api key to select the company from your CRM account.</p>
</fieldset>
<?php endif; ?>