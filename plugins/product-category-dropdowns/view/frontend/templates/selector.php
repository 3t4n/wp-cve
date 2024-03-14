<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="pcd-selector-box" id="pcd_<?php echo $this->getWidgetId(); ?>">
  <div class="block-content">	            	            
    <div class="pcd-category-container"></div>               			
    <button type="button" title="<?php echo __('Search', 'product-category-dropdowns') ?>" class="button pcd-submit" ><span><span><?php echo __('Search', 'product-category-dropdowns') ?></span></span></button>             		      	          		      	      
  </div>
</div>
<script>
  jQuery(function($){
    $('#pcd_<?php echo $this->getWidgetId(); ?>').productCategoryDropdowns({             
      categoryDefOptionTitle   : "<?php echo __('-- select category --', 'product-category-dropdowns') ?>",           
      preCategories            : <?php echo $this->getCategoriesJson(); ?>,
      selectedIds              : <?php echo $this->getSelectedIdsJson(); ?>                  
    });
  });
</script>
