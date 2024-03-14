<?php defined('FIRAN_VERSION') OR exit('Go way!'); ?>

<input type="hidden" name="fiwp_nonce" value="<?php echo wp_create_nonce('fiwp') ?>" />


<div class="col box-side-manager">

 <ul class="fi_side_options">
  
<?php 

$i = 0;
foreach($this->options as $key=>$element) { 

	$tab = (isset($element['tab'])) ? $element['tab'] : $i;
	$checked = (isset($this->options[$tab]['stat'] )) ? 'checked' : '';
	$label = (isset($element['label'])) ? $element['label'] : 'کلاس سفارشی';

	$active = ($i<1) ? ' class="active"' : null;
	
	?>
	<li<?php echo $active; ?>>
    <input name="fi_ops[<?php echo $tab; ?>][od]" value="1" type="hidden">
     <div class="fi-active-row"><input name="fi_ops[<?php echo $tab; ?>][stat]" class="choose_element" <?php echo $checked; ?> type="checkbox"></div>
    <span class="fi_panel_name" data-tab="<?php echo $tab; ?>"><?php echo $label;?></span>
    <span class="fcp-bar fi-order-options"></span>  
    <span class="fcp-close fi-order-options fi-remove-option" style="left:40px;cursor: pointer !important;"></span>
    </li>
    
<?php $i++; } ?>
 
  </ul>
</div>
<span class="button-secondary" id="fi_add_rule" style="float:right;margin: 15px 0;">افزودن کلاس سفارشی </span>