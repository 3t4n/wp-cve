<?php
defined('ABSPATH') or die('Invalid request.'); ?>
<link rel="stylesheet" href="<?php echo $this->get_url_static_file('admin.css'); ?>" type="text/css" />
<!-- Preview template -->
<div id="EPA_preview_template">
<?php
foreach ([
'guest.html',
'guest_call_number.html',
'guest_call_sms_mes.html',
] as $v) {
$a = $this->get_tmp($v);
$a = $this->replace_tmp($a);
echo $a;
} ?>
</div>
<!-- END Preview template -->
<div class="wrap epa-wrap">
<h1><?php echo EPA_THIS_PLUGIN_NAME; ?> version <?php echo EPA_DF_VERSION; ?></h1>
<form method="post" action="<?php echo admin_url('options.php'); ?>" novalidate="novalidate">
<?php settings_fields($this->optionGroup); ?>
<table class="form-table epa-table">
<tbody>
<?php
foreach ($this->defaultOptions as $k => $v) {
if (!isset($this->defaultNameOptions[$k])) {
$this->defaultNameOptions[$k] = []; }
if (!isset($this->defaultNameOptions[$k]['type']) || $this->defaultNameOptions[$k]['type'] == '') {
$this->defaultNameOptions[$k]['type'] = 'text'; }
if (!isset($this->defaultNameOptions[$k]['name']) || $this->defaultNameOptions[$k]['name'] == '') {
$this->defaultNameOptions[$k]['name'] = str_replace('_', ' ', $k);
} ?>
<tr id="tr_<?php echo $k; ?>">
<th scope="row">
<label for="<?php echo $k; ?>"><?php echo $this->defaultNameOptions[$k]['name']; ?></label>
</th>
<td>
<?php
if ($this->defaultNameOptions[$k]['type'] == 'checkbox') { ?>
<input type="checkbox" id="<?php echo $k; ?>" value="1" <?php checked(1, $this->my_settings[$k]); ?> data-for="<?php echo $k; ?>" /> Active
<input type="hidden" value="1" data-k="<?php echo $k; ?>" name="<?php echo $this->optionName; ?>[<?php echo $k; ?>]" />
<?php
} else if ($this->defaultNameOptions[$k]['type'] == 'select') { ?>
<select name="<?php echo $this->optionName; ?>[<?php echo $k; ?>]" id="<?php echo $k; ?>" data-value="<?php echo esc_attr($this->my_settings[$k]); ?>" class="postform each-to-selected">
<?php
foreach ($this->defaultNameOptions[$k]['option'] as $k2 => $v2) { ?>
<option value="<?php echo $k2; ?>"><?php echo $v2; ?></option>
<?php } ?>
</select>
<?php
} else if ($this->defaultNameOptions[$k]['type'] == 'textarea') { ?>
<textarea id="<?php echo $k; ?>" name="<?php echo $this->optionName; ?>[<?php echo $k; ?>]" rows="5" style="width: 99%;max-width: 666px;"><?php echo esc_attr($this->my_settings[$k]); ?></textarea>
<?php } else { ?>
<input type="<?php echo $this->defaultNameOptions[$k]['type']; ?>" id="<?php echo $k; ?>" value="<?php echo esc_attr($this->my_settings[$k]); ?>" name="<?php echo $this->optionName; ?>[<?php echo $k; ?>]">
<?php } ?>
<?php
if (isset($this->defaultNameOptions[$k]['description'])) { ?>
<p class="description"><?php echo $this->defaultNameOptions[$k]['description']; ?></p>
<?php } ?>
</td>
</tr>
<?php } ?>
</tbody>
</table>
<?php
do_settings_fields($this->optionGroup, 'default');
do_settings_sections($this->optionGroup, 'default'); ?>
<p>* Note: Default values will be used if custom values are not set.</p>
<table class="form-table epa-table">
<tbody>
<tr>
<th scope="row">&nbsp;</th>
<td>
<?php
submit_button(); ?>
</td>
</tr>
</tbody>
</table>
</form>
</div>
<div class="epa-exemple-preview-css"></div>
<br>
<script>
var arr_my_settings = <?php echo json_encode($this->my_settings); ?>;
var arr_default_settings = <?php echo json_encode($this->defaultOptions); ?>;
</script>
<script src="<?php echo $this->get_url_static_file('admin.js'); ?>" defer></script>
<p>* Other <a href="<?php echo admin_url('plugin-install.php'); ?>?s=itvn9online&tab=search&type=author" target="_blank">WordPress Plugins</a> written by the same author. Thanks for choose us!</p>