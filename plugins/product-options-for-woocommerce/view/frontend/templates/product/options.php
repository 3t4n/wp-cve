<?php
if (!defined('ABSPATH')) exit;
?>
<div class="pofw-product-options-wrapper" id="pofw_product_options">
  <div class="fieldset">
    <?php foreach($this->getOptions() as $id => $option): ?>         
      <div class="field <?php echo $option['required'] == 1 ? 'pofw-required' : ''; ?>">
        <label for="select_<?php echo $id; ?>">
          <span><?php echo htmlspecialchars($option['title']); ?></span>
          <?php if (($option['type'] == 'field' || $option['type'] == 'area') && $option['price'] != 0): ?>
           <span class="pofw-price"><?php echo $this->formatPrice($option['price']);?></span> 
          <?php endif; ?>
        </label>
        <div class="control">
          <?php if ($option['type'] == 'radio'): ?>
            <div class="options-list nested">
              <?php if ($option['required'] != 1): ?>
              <div class="choice">
                <input type="radio" name="pofw_option[<?php echo $id; ?>]" id="pofw_option_[<?php echo $id; ?>]_none_value" class="pofw-option" value="">
                <label for="pofw_option_[<?php echo $id; ?>]_none_value"><span><?php echo __('None', 'product-options-for-woocommerce') ?></span></label>
              </div>              
              <?php endif; ?>              
              <?php foreach($option['values'] as $vid => $value): ?>        
                <div class="choice">
                  <input type="radio" name="pofw_option[<?php echo $id; ?>]" id="pofw_option_value_<?php echo $vid; ?>" class="pofw-option" value="<?php echo $vid; ?>">
                  <label for="pofw_option_value_<?php echo $vid; ?>"><span><?php echo htmlspecialchars($value['title']); ?></span><?php echo $value['price'] != 0 ? '<span class="pofw-price"> '. $this->formatPrice($value['price']) .'</span>' : ''; ?></label>
                </div>
              <?php endforeach; ?>          
            </div>
          <?php elseif ($option['type'] == 'checkbox'): ?>         
            <div class="options-list nested">
              <?php foreach($option['values'] as $vid => $value): ?>        
                <div class="choice">
                  <input type="checkbox" name="pofw_option[<?php echo $id; ?>][]" id="pofw_option_value_<?php echo $vid; ?>" class="pofw-option" value="<?php echo $vid; ?>">
                  <label for="pofw_option_value_<?php echo $vid; ?>"><span><?php echo htmlspecialchars($value['title']); ?></span><?php echo $value['price'] != 0 ? '<span class="pofw-price"> '. $this->formatPrice($value['price']) .'</span>' : ''; ?></label>
                </div>
              <?php endforeach; ?>          
            </div>
          <?php elseif ($option['type'] == 'drop_down'): ?>         
            <select name="pofw_option[<?php echo $id; ?>]" id="pofw_option_<?php echo $id; ?>" class="pofw-option">
              <option value=""><?php echo esc_html__('-- please select --', 'product-options-for-woocommerce') ?></option>
              <?php foreach($option['values'] as $vid => $value): ?>   
                <option value="<?php echo $vid; ?>"><?php echo htmlspecialchars($value['title']) .' '. $this->formatPrice($value['price']); ?></option>                   
              <?php endforeach; ?>          
            </select>    
          <?php elseif ($option['type'] == 'multiple'): ?>         
            <select name="pofw_option[<?php echo $id; ?>][]" id="pofw_option_<?php echo $id; ?>" class="pofw-option" multiple="multiple">
              <option value=""><?php echo esc_html__('-- please select --', 'product-options-for-woocommerce') ?></option>
              <?php foreach($option['values'] as $vid => $value): ?>   
                <option value="<?php echo $vid; ?>"><?php echo htmlspecialchars($value['title']) .' '. $this->formatPrice($value['price']); ?></option>                   
              <?php endforeach; ?>          
            </select>   
          <?php elseif ($option['type'] == 'field'): ?>         
            <input type="text" name="pofw_option[<?php echo $id; ?>]" id="pofw_option_<?php echo $id; ?>" class="pofw-option" value="" autocomplete="off">  
          <?php elseif ($option['type'] == 'area'): ?>         
            <textarea name="pofw_option[<?php echo $id; ?>]" id="pofw_option_<?php echo $id; ?>" class="pofw-option" rows="4"></textarea>                                                           
          <?php endif; ?>                                    
        </div>
      </div>
    <?php endforeach; ?>                
  </div>
</div>
<script type="text/javascript">

  var config = {  
    requiredText : "<?php echo __('This field is required.', 'product-options-for-woocommerce'); ?>",
    productId : <?php echo (int) $this->getProductId(); ?>,    
    productPrice : <?php echo (float) $this->getProductPrice(); ?>,
    numberOfDecimals : <?php echo (int) $this->getNumberOfDecimals(); ?>,    
    decimalSeparator : "<?php echo $this->getDecimalSeparator(); ?>",
    thousandSeparator : "<?php echo $this->getThousandSeparator(); ?>",
    currencyPosition : "<?php echo $this->getCurrencyPosition(); ?>",
    isOnSale : <?php echo (int) $this->getIsOnSale(); ?>       
  };
  
  var optionData = <?php echo $this->getOptionDataJson(); ?>;
   
  jQuery.extend(config, optionData);
    
  jQuery('#pofw_product_options').pofwProductOptions(config);    

</script>