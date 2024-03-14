<?php

if(!$_page['isPro']) {
    echo '<p>' . $_page['i18n']['PRO_DESCRIPTION'] . '</p>';
}

?>

<div <?php echo (!$_page['isPro'] ? 'style="color:#bbb" class="disabled"' : ''); ?> id="ezme-access-control-selections"> 
<script type="text/javascript">
    jQuery(document).ready(function() {

	let selectOpts = {
	    allowClear: false,
	    multiple: true,
	    width: 'style',
	    closeOnSelect: true,
	    placeholder: '...',
	    theme: 'default'
	};

	selectOpts.data = <?php echo json_encode($_page['subs']); ?>;
	jQuery('#ezme-multiselect-subs').select2(selectOpts);

	selectOpts.data = <?php echo json_encode($_page['courses']); ?>;
	jQuery('#ezme-multiselect-courses').select2(selectOpts);

	selectOpts.data = <?php echo json_encode($_page['tags']); ?>;
	jQuery('#ezme-multiselect-tags').select2(selectOpts);
	
	// stupid hack to prevent open on removal
	let justDeleted = false;
	jQuery(document).on('select2:unselecting', (evt) => {
	    justDeleted = true;
	    setTimeout(() => {
		justDeleted = false;
	    }, 300);
	});

	jQuery(document).on('select2:opening', (evt) => {
	    if(justDeleted) {
		evt.preventDefault();
	    }
	});
	
    });
</script>

<style type="text/css">
  #ezme-access-control-selections .select2-container--default .select2-selection--multiple .select2-selection__choice {
  margin: 2px;
  background-color: #fff;
  border-color: #ccc;
  color: #333;
  padding: 3px;
  }
  .ezme-select2 {
  margin-bottom: 15px
  }
</style>

<input type="hidden" name="easyme-auth-metabox-nonce" value="<?php echo $_page['nonce']; ?>">
<p style="margin-top: 20px; font-weight: bold"><?php _e('Client must have at least one of', 'easyme'); ?>:</p>

<div class="ezme-select2">
  <label><?php _e('Tags', 'easyme'); ?>:</label>
  <select id="ezme-multiselect-tags" name="tags[]" style="width: 100%"></select>
</div>

<div class="ezme-select2">
  <label><?php _e('Subscriptions', 'easyme'); ?>:</label>
  <select id="ezme-multiselect-subs" name="subscriptions[]" style="width: 100%"></select>
</div>

<div class="ezme-select2">
  <label><?php _e('Online courses', 'easyme'); ?>:</label>
  <select id="ezme-multiselect-courses" name="online_products[]" style="width: 100%"></select>
</div>

</div>
